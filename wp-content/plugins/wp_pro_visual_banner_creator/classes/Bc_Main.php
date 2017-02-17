<?php
class Bc_Main {	

	public function __construct() 
	{	
		//date_default_timezone_set(get_option('timezone_string'));
	}
	
	
	
	
	/*
	 * Default Forum Settings
	 *
	 * @access public
	 * @return array
	*/
	public function imge_default_settings( $query = '')
	{
		global $wpdb;
		
		//$res = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wp_pro_forum_settings ".$query);
		
		return $res;
	}
	
}
?>