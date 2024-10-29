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
<h1 class="wp-heading-inline"><?php _e('settings','apoyl-weixin'); ?></h1>
<p><?php _e('settings_desc','apoyl-weixin'); ?></p>
<ul class="subsubsub">
	<li><a href="options-general.php?page=apoyl-weixin-settings"
		<?php if($do=='') echo 'class="current"';?> aria-current="page"><?php _e('settingsname','apoyl-weixin'); ?><span
			class="count"></span></a> |</li>
	<li><a href="options-general.php?page=apoyl-weixin-settings&do=list"
		<?php if($do=='list') echo 'class="current"';?>><?php _e('list','apoyl-weixin'); ?><span
			class="count"></span></a></li>
</ul>

<div class="clear"></div>
<hr>