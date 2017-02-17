<?php
/**
 * Plugin Name: WP Pro Visual Banner Creator
 * Plugin URI: http://tunasite.com/wp-pro-banner-creator/
 * Description: Plugin to create banners on your website.
 * Version: 2.1.0
 * Author: Tunafish
 * Author URI: http://www.tunasite.com
 * Requires at least: 3.8
 * Tested up to: 4.1
 *
 * Text Domain: wpbc
 * Domain Path: /localization/
 *
 * @package Wp_Pro_Banner_Creator
 * @category Core
 * @author Tunafish
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Wp_Pro_Banner_Creator' ) ) :

final class Wp_Pro_Banner_Creator
{
	/**
	 * @var string
	 */
	public $version = '2.1.0';
	
	
	/**
	 * @var The single instance of the class
	 */
	protected static $_instance = null;
	
	
	
	
	/**
	 * Main Wp_Pro_Ad_System Instance
	 *
	 * Ensures only one instance of Wp_Pro_Ad_System is loaded or can be loaded.
	 *
	 * @since 4.0.0
	 * @static
	 * @see PAS()
	 * @return Wp_Pro_Ad_System - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	
	
	
	public function __construct() 
	{
		// Define constants
		$this->define_constants();
		
		// Classes ------------------------------------------------------------
		require_once( WP_BC_DIR .'classes/Bc_Init.php');
		
		
		/* ----------------------------------------------------------------
		 * Set Classes
		 * ---------------------------------------------------------------- */
		$bc_init = new Bc_Init();
	}
	
	
	private function define_constants() 
	{
		define( 'WP_BC_VERSION', $this->version );
		
		define( 'WP_BC_FILE', __FILE__ );
		
		define( 'WP_BC_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) );
		define( 'WP_BC_DIR', ABSPATH. 'wp-content/plugins/' .str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) );
		
		define( 'WP_BC_INC_URL', WP_BC_URL. 'includes' );
		define( 'WP_BC_INC_DIR', WP_BC_DIR. 'includes' );
		define( 'WP_BC_TPL_URL', WP_BC_URL. 'templates' );
		define( 'WP_BC_TPL_DIR', WP_BC_DIR. 'templates' );
		define( 'WP_BC_PLUGIN_SLUG', basename(dirname(__FILE__)) );
		
		define( 'WP_BC_ROLE_ADMIN', 'add_users' );
		define( 'WP_BC_ROLE_USER', 'read' );
	}
	
}

endif;



/**
 * Returns the main instance of PAS to prevent the need to use globals.
 *
 * @since  4.0.0
 * @return Wp_pro_ad_system
 */
function WPBC() {
	return Wp_Pro_Banner_Creator::instance();
}

WPBC();
?>