<?php
/*
 * @link       http://www.girltm.com/
 * @since      1.0.0
 * @package    APOYL_WEIXIN
 * @subpackage APOYL_WEIXIN/includes
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
class Apoyl_Weixin_i18n {


	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'apoyl-weixin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
