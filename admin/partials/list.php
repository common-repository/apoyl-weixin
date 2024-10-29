<?php
/*
 * @link http://www.girltm.com
 * @since 1.0.0
 * @package APOYL_WEIXIN
 * @subpackage APOYL_WEIXIN/admin/partials
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
?>

<div class="wrap">
<?php   require_once APOYL_WEIXIN_DIR . 'admin/partials/nav.php';?>
<strong> 
<?php
$file = apoyl_weixin_file('list');
if ($file) {
    include $file;
} else {
    ?>   	
<?php   
_e('calldev_desc', 'apoyl-weixin');
}
?>
</strong>
</div>