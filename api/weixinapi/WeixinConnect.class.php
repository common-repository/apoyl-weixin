<?php

/*
 * @link http://www.girltm.com/
 * @since 1.0.0
 * @package APOYL_WEIXIN
 * @subpackage APOYL_WEIXIN/api/weixinapi
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
class WeixinConnect
{

    const VERSION = "2.0";

    const GET_AUTH_CODE_URL = "https://open.weixin.qq.com/connect/qrconnect";

    const GET_ACCESS_TOKEN_URL = "https://api.weixin.qq.com/sns/oauth2/access_token";

    const GET_USER_INFO_URL = "https://api.weixin.qq.com/sns/userinfo";

    function __construct()
    {
        $this->cache = get_option('apoyl-weixin-settings');
        $this->init_callback();
    }

    function init_callback()
    {
        $ajaxurl = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('weixin_nonce');
        $params = array(
            'action' => 'apoyl_weixin_callback',
            '_ajax_nonce' => wp_create_nonce('weixin_nonce')
        );
        
        $this->callback = urlencode($ajaxurl . '?' . build_query($params));
    }

    public function weixin_login()
    {
        $appid = $this->cache['appid'];
        
        $scope = 'snsapi_login';
        
        $keysArr = array(
            "response_type" => "code",
            "appid" => $this->cache['appid'],
            "redirect_uri" => $this->callback,
            "state" => wp_create_nonce('weixin_nonce'),
            "scope" => $scope,
            "lang" => "cn"
        );
        
        $login_url = self::GET_AUTH_CODE_URL . '?' . build_query($keysArr);
        
        wp_redirect($login_url);
    }

    public function weixin_callback()
    {
        if (! wp_verify_nonce($_GET['state'], 'weixin_nonce'))
            throw new Exception("error:30001,description:state error", 500);
        $keysArr = array(
            "grant_type" => "authorization_code",
            "appid" => $this->cache['appid'],
            "secret" => $this->cache['appkey'],
            "code" => sanitize_text_field($_GET['code'])
        );
        
        $token_url = self::GET_ACCESS_TOKEN_URL . '?' . build_query($keysArr);
        
        $response = $this->httpGet($token_url);
        
        $re = json_decode($response);
        if (isset($re->errcode)) {
            throw new Exception("error:" . $re->errcode . ",description:" . $re->errmsg, 500);
        }
        return $re;
    }

    public function get_user_info($openid, $access_token)
    {
        $keysArr = array(
            "access_token" => $access_token,
            'openid' => $openid,
            'lang' => 'zh_CN'
        );
        
        $graph_url = self::GET_USER_INFO_URL . '?' . build_query($keysArr);
        $response = $this->httpGet($graph_url);
        
        $user = json_decode($response, true);
        if (isset($user->errcode)) {
            throw new Exception("error:" . $user->errcode . ",description:" . $user->errmsg, 500);
        }
        
        return $user;
    }

    private function httpGet($url, $param = array())
    {
        $res = wp_remote_retrieve_body(wp_remote_get($url, array(
            'timeout' => 30,
            'body' => $param
        )));
        
        return $res;
    }
}
