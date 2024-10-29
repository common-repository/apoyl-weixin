<?php
/*
 * Plugin Name: apoyl-weixin
 * Plugin URI:  http://www.girltm.com/
 * Description: 实现微信一键登录，让用户不在繁琐去注册用户，一键实现微信登录，极大的方便用户登录网站.
 * Version:     1.7.0
 * Author:      凹凸曼
 * Author URI:  http://www.girltm.com/
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: apoyl-weixin
 * Domain Path: /languages
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}
define('APOYL_WEIXIN_VERSION','1.7.0');
define('APOYL_WEIXIN_PLUGIN_FILE',plugin_basename(__FILE__));
define('APOYL_WEIXIN_URL',plugin_dir_url( __FILE__ ));
define('APOYL_WEIXIN_DIR',plugin_dir_path( __FILE__ ));

function activate_apoyl_weixin(){
    require plugin_dir_path(__FILE__).'includes/activator.php';
    Apoyl_Weixin_Activator::activate();
    Apoyl_Weixin_Activator::install_db();
}
register_activation_hook(__FILE__, 'activate_apoyl_weixin');

function uninstall_apoyl_weixin(){
    require plugin_dir_path(__FILE__).'includes/uninstall.php';
    Apoyl_Weixin_Uninstall::uninstall();
}

register_uninstall_hook(__FILE__,'uninstall_apoyl_weixin');

require plugin_dir_path(__FILE__).'includes/weixin.php';

function run_apoyl_weixin(){
    $plugin=new APOYL_WEIXIN();
    $plugin->run();
}

function apoyl_weixin_file($filename)
{
    $file = WP_PLUGIN_DIR . '/apoyl-common/v1/apoyl-weixin/components/' . $filename . '.php';
    if (file_exists($file))
        return $file;
    return '';
}
run_apoyl_weixin();
?>