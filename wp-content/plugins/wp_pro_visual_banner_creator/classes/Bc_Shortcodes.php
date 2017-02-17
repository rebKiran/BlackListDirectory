<?php
header ("Expires: ".gmdate("D, d M Y H:i:s", time())." GMT");  
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");
wp_cache_flush();
class Bc_Shortcodes {	

	public function __construct() 
	{
		add_shortcode('visual_banner_creator', array($this, 'sc_visual_banner_creator'));
	}
	
	
	
	
	/*
	 * Shortcode function - [visual_banner_creator]
	 *
	 * @access public
	 * @return array
	*/
	public function sc_visual_banner_creator( $atts, $content = null ) 
	{
		global $bc_banner_creator, $vbc_js;
		
		$vbc_js = true;
		
		extract( shortcode_atts( array(
			'frontend'      => 1,
			'image_upload'  => 1,
			'save_design'   => 0,
			'export'        => 0,
			//'svg'           => '',
			//'json'          => '',
			'json_64'       => ''
		), $atts ) );
		
		$arr = array(
			'frontend'     => $frontend,
			'image_upload' => $image_upload,
			'save_design'  => $save_design,
			'export'       => $export,
			//'svg'          => $svg,
			//'json'         => $json,
			'json_64'      => $json_64
		);
		
		
		return $bc_banner_creator->show_banner_creator( $arr );
	}
	
}
?>