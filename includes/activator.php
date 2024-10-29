<?php

/*
 * @link http://www.girltm.com/
 * @since 1.0.0
 * @package APOYL_WEIXIN
 * @subpackage APOYL_WEIXIN/includes
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
class Apoyl_Weixin_Activator
{

    public static function activate()
    {
        $options_name = 'apoyl-weixin-settings';
        $arr_options = array(
            'open' => 1,
            'appid' => '',
            'appkey' => '',
            'role'=>'',
            'bind'=>0,

        );
        add_option($options_name, $arr_options);
    }

    public static function install_db()
    {
        global $wpdb;
        $apoyl_weixin_db_version = APOYL_WEIXIN_VERSION;
        $tablename = $wpdb->prefix . 'apoyl_weixin';
        $ishave = $wpdb->get_var('show tables like \'' . $tablename . '\'');
        $sql='';
        if ($tablename != $ishave) {
            $sql = "CREATE TABLE " . $tablename . " (
                      `id`	bigint(20) unsigned  NOT NULL AUTO_INCREMENT,
                      `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
                      `openid` varchar(64) NOT NULL,
                      `nickname` varchar(100) NOT NULL,
                      `sex` tinyint(1) NOT NULL default '0',
                      `province` varchar(200) NOT NULL,
                      `city` varchar(200) NOT NULL,
                      `country` varchar(200) NOT NULL,
                      `headimgurl` varchar(200) NOT NULL,
                      `privilege` text NOT NULL,
                      `unionid` varchar(100) NOT NULL,
                      `addtime` int(10) NOT NULL default '0',
                      `modtime` int(10) NOT NULL default '0',
                      PRIMARY KEY (`id`),
                      KEY `userid` (`userid`)
                    );";
        }
    
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
        add_option('apoyl_weixin_db_version', $apoyl_weixin_db_version);
    }
}
?>