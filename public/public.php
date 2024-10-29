<?php

/*
 * @link http://www.girltm.com/
 * @since 1.0.0
 * @package APOYL_WEIXIN
 * @subpackage APOYL_WEIXIN/public
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
class Apoyl_Weixin_Public
{

    private $plugin_name;

    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function apoyl_weixin_callback()
    {
        global $wpdb;
        if (! check_ajax_referer('weixin_nonce'))
            exit();
        $redirect_to = home_url();
        $login_to = admin_url();
        try {
            require_once (APOYL_WEIXIN_DIR . 'api/weixinapi/WeixinConnect.class.php');
            $weixinobj = new WeixinConnect();
            $tokenobj = $weixinobj->weixin_callback();
            $openid=$tokenobj->openid;
            $data = $weixinobj->get_user_info($openid, $tokenobj->access_token);
        } catch (Exception $e) {
            wp_redirect($login_to);
            exit();
        }
        $weixinuser = $wpdb->get_row($wpdb->prepare("select id,
                userid
                from {$wpdb->prefix}apoyl_weixin
                where openid=%s
                limit 1;", $openid));
        
        if ($weixinuser->userid) {
            $userid = $weixinuser->userid;
            $secure_cookie = false;
            if (get_user_option('use_ssl', $userid)) {
                $secure_cookie = true;
            }
            wp_set_auth_cookie($userid, true, $secure_cookie);
            wp_redirect($redirect_to);
        } else {
            $arr = get_option('apoyl-weixin-settings');
            
            $userid = get_current_user_id();
            if ($userid <= 0) {
                $newuser = array(
                    'user_login' => $data->nickname,
                    'user_email' => '',
                    'user_pass' => wp_generate_password(12),
                    'role' => $arr['role']
                );
                $userid = wp_insert_user($newuser);
                if (! isset($userid->errors) && $userid > 0) {
                    $secure_cookie = false;
                    if (get_user_option('use_ssl', $userid)) {
                        $secure_cookie = true;
                    }
                    wp_set_auth_cookie($userid, true, $secure_cookie);
                }
            }
            if ($userid > 0) {
                
                $now = current_time('timestamp');
                $weixindata = array(
                    'openid' => $openid,
                    'userid' => $userid,
                    'nickname' => $data->nickname,
                    'sex' => $data->sex,
                    'province' => $data->province,
                    'city' => $data->city,
                    'country' => $data->country,
                    'privilege' => $data->privilege,
                    'headimgurl' => $data->headimgurl,
                    'unionid' => $data->unionid,
                    'modtime' => $now,
                    'addtime' => $now
                );
                
                $re = $wpdb->insert($wpdb->prefix . 'apoyl_weixin', $weixindata);
                if ($re)
                    wp_redirect($redirect_to);
                else
                    wp_redirect($login_to);
            } else {
                wp_redirect($login_to);
            }
        }
    }

    public function apoyl_weixin_ajax()
    {
        if (! check_ajax_referer('weixin_nonce'))
            exit();
        require_once (APOYL_WEIXIN_DIR . "api/weixinapi/WeixinConnect.class.php");
        $weixinobj = new WeixinConnect();
        $weixinobj->weixin_login();
    }

    public function login()
    {
        $arr = get_option('apoyl-weixin-settings');
        
        if ($arr['open']) {
            $ajaxurl = admin_url('admin-ajax.php');
            $nonce = wp_create_nonce('weixin_nonce');
            $params = array(
                'action' => 'apoyl_weixin_ajax',
                '_ajax_nonce' => wp_create_nonce('weixin_nonce')
            );
            $url = $ajaxurl . '?' . build_query($params);
            require_once plugin_dir_path(__FILE__) . 'partials/public-display.php';
        }
    }

    public function apoyl_weixin_sanitize_user($username, $raw_username, $strict)
    {
        $file = apoyl_weixin_file('chinese');
        if ($file) {
            include $file;
        } else {
            $raw_username = $username;
            $username = wp_strip_all_tags($username);
            $username = remove_accents($username);
            
            $username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);
            
            $username = preg_replace('/&.+?;/', '', $username);
            
            if ($strict) {
                $username = preg_replace('|[^a-z0-9 _.\-@]|i', '', $username);
            }
            
            $username = trim($username);
            
            $username = preg_replace('|\s+|', ' ', $username);
        }
        
        return $username;
    }
}