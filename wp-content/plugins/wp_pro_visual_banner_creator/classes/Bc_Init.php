<?php
class Bc_Init {	

	public function __construct() 
	{	
		global $bc_templates, $bc_banner_creator, $vbc_cpts, $vbc_cpt_meta_options, $bc_shortcodes, $bc_google_fonts, $bc_ajax_image_upload, $vbc_design_items;
		
		// Load Functions ------------------------------------------------- 
		require_once( WP_BC_INC_DIR .'/ajax_functions.php');
		
		// Classes ------------------------------------------------------------
		require_once( WP_BC_DIR .'classes/VBC_CPT_Meta_Options.php');
		require_once( WP_BC_DIR .'classes/Bc_Banner_Creator.php');
		require_once( WP_BC_DIR .'classes/Bc_Templates.php');
		require_once( WP_BC_DIR .'classes/VBC_CPTs.php');
		require_once( WP_BC_DIR .'classes/Bc_Shortcodes.php');
		require_once( WP_BC_DIR .'classes/Bc_Google_Fonts.php');
		require_once( WP_BC_DIR .'classes/Bc_Ajax_Image_upload.php');
		require_once( WP_BC_DIR .'classes/VBC_Design_Items.php');
		
		
		/* ----------------------------------------------------------------
		 * Set Classes
		 * ---------------------------------------------------------------- */
		$vbc_cpt_meta_options = new VBC_CPT_Meta_Options();
		$bc_banner_creator = new Bc_Banner_Creator();
		$bc_templates = new Bc_Templates();
		$vbc_cpts = new VBC_CPTs();
		$bc_shortcodes = new Bc_Shortcodes();
		$bc_google_fonts = new Bc_Google_Fonts();
		$bc_ajax_image_upload = new Bc_Ajax_Image_upload();
		$vbc_design_items = new VBC_Design_Items();
		
		// Actions --------------------------------------------------------
		add_action('init', array( $this, 'init_method') );
		add_action('admin_menu', array( $this,'admin_actions') );
		add_action('admin_enqueue_scripts', array( $this,'admin_enqueue') );
		//add_action('wp_footer', array($this, 'footer_actions') );
		
		/*add_action( 'admin_print_scripts-edit.php', array($this, 'admin_vbc_js_enqueue') );
		add_action( 'admin_print_scripts-post.php', array($this, 'admin_vbc_js_enqueue') );
		add_action( 'admin_print_scripts-post-new.php', array($this, 'admin_vbc_js_enqueue') );*/
	}
	
	
	
	
	
	/*
	 * Init actions
	 *
	 * @access public
	 * @return null
	*/
	public function init_method() 
	{
		global $bc_ajax_image_upload;
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('vbc_fabric', WP_BC_INC_URL . '/js/fabric.min.js');
		
		wp_enqueue_script('vbc_js', WP_BC_INC_URL . '/js/visual_banner_creator.js', array('jquery'), WP_BC_VERSION, true);
		wp_enqueue_script('vbc_tooltip', WP_BC_INC_URL . '/js/jquery.tooltipster.min.js', array('jquery'), WP_BC_VERSION, true);
		
		//wp_register_script('vbc_js', WP_BC_INC_URL . '/js/visual_banner_creator.js', array('jquery'), WP_BC_VERSION, true);
		//wp_register_script('vbc_tooltip', WP_BC_INC_URL . '/js/jquery.tooltipster.min.js', array('jquery'), WP_BC_VERSION, true); // http://iamceege.github.io/tooltipster/
		//wp_enqueue_script('vbc_js', WP_BC_INC_URL . '/js/visual_banner_creator.js');
		
		wp_enqueue_style("wp_bc_banner_creator_css", WP_BC_INC_URL."/css/banner_creator.css", false, WP_BC_VERSION, "all");
		wp_enqueue_style("wp_bc_tooltip_css", WP_BC_INC_URL."/css/tooltipster.css", false, WP_BC_VERSION, "all");
		wp_enqueue_style("wp_bc_tooltip_theme_css", WP_BC_INC_URL."/css/tooltipster-light.css", false, WP_BC_VERSION, "all");
		//wp_enqueue_style("wp_bc_banner_combobox_css", WP_BC_INC_URL."/css/combobox.css", false, WP_BC_VERSION, "all");
		wp_enqueue_style( 'wpbc_fontawesome_css', WP_BC_INC_URL.'/fonts/awesome/assets/v4/css/font-awesome.min.css');
		
		// Chosen
		wp_enqueue_style( 'chosen_style', WP_BC_INC_URL . '/chosen/chosen.css', false, WP_BC_VERSION, "all" );
		wp_enqueue_script( 'chosen', WP_BC_INC_URL . '/chosen/chosen.jquery.min.js', array( 'jquery' ), false, true );
		
		// Ajax Upload
		add_action('wp_ajax_visualbannercreator_upload', array($bc_ajax_image_upload, 'upload'));
		add_action('wp_ajax_visualbannercreator_delete', array($bc_ajax_image_upload, 'delete_file'));
		/* For non logged-in user */
		add_action('wp_ajax_nopriv_visualbannercreator_upload', array($bc_ajax_image_upload, 'upload'));
		add_action('wp_ajax_nopriv_visualbannercreator_delete', array($bc_ajax_image_upload, 'delete_file'));
		
		/*
		 * FRONTEND ONLY
		*/
		if( !is_admin() )
		{
			wp_enqueue_style("wp_bc_banner_creator_frontend_css", WP_BC_INC_URL."/css/frontend_style.css", false, WP_BC_VERSION, "all");
			
			$bc_ajax_image_upload->add_script();
			
			/* 
			 * Color picker - allow also on frontend
			 * http://wordpress.stackexchange.com/questions/82718/how-do-i-implement-the-wordpress-iris-picker-into-my-plugin-on-the-front-end
			 * http://automattic.github.io/Iris/
			*/
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script(
				'iris',
				admin_url( 'js/iris.min.js' ),
				array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
				false,
				1
			);
			wp_enqueue_script(
				'wp-color-picker',
				admin_url( 'js/color-picker.min.js' ),
				array( 'iris' ),
				false,
				1
			);
			$colorpicker_l10n = array(
				'clear' => __( 'Clear' ),
				'defaultString' => __( 'Default' ),
				'pick' => __( 'Select Color' )
			);
			wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n ); 
		}
		
		load_plugin_textdomain('wpbc', false, basename( dirname( __FILE__ ) ) . '/localization' );
	}
	
	

	
	
	/*
	 * Admin page Init actions
	 *
	 * @access public
	 * @return null
	*/
	public function admin_actions() 
	{	
		wp_enqueue_style('wpbc_tuna_admin_style', WP_BC_INC_URL.'/css/tuna-admin.css', false, WP_BC_VERSION, "all");
		wp_enqueue_style("wpbc_toggle_style_css", WP_BC_INC_URL."/css/tuna-admin-styles.css", false, WP_BC_VERSION, "all");
		
		wp_enqueue_script('vbc_js_admin', WP_BC_INC_URL . '/js/visual_banner_creator_admin.js');
		
		// Color picker - admin side
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_style( 'wp-color-picker' );
		
		
		// Create menu
		if( function_exists('add_object_page') ) 
		{
			add_object_page(__('Banner Creator','wpbc'), __('Banner Creator','wpbc'), WP_BC_ROLE_ADMIN, "wp-banner-creator" , array($this, "wpbc_editor"), WP_BC_INC_URL."/images/icon.png");
		}
		else
		{
			add_menu_page(__('Banner Creator','wpbc'), __('Banner Creator','wpbc'), WP_BC_ROLE_ADMIN,  "wp-banner-creator" , array($this, "wpbc_editor"));
		}
		
		if( current_user_can('add_users') )
		{
			//add_submenu_page("wp-banner-creator", __('Banner Creator','wpbc'), __('Banner Creator','wpbc'), WP_BC_ROLE_ADMIN, "wp-banner-creator", array($this, "wpbc_editor"));
			//add_submenu_page("wp-banner-creator", __('Settings','wpbc'), __('Settings','wpbc'), WP_BC_ROLE_ADMIN, "wp-banner-creator-settings", array($this, "wpbc_settings"));
		}
	}
	
	
	
	
	function admin_enqueue()
	{
		if( function_exists('wp_enqueue_media') )
		{
			wp_enqueue_media();
		}
	}
	
	
	
	// MENU FUNCTIONS -------------------------------------------------------
	function wpbc_editor()
	{
		//include( WP_BC_TPL_DIR .'/image_editor.php');
	}
	function wpbc_settings()
	{
		include( WP_BC_TPL_DIR .'/settings.php');
	}
	
	
	
	/*function admin_vbc_js_enqueue() 
	{
		global $typenow;
		
		if( $typenow == 'vbc_banners' || $typenow == 'banners'  )
			wp_print_scripts('vbc_js');
			wp_print_scripts('vbc_tooltip');
	}
	
	// FOOTER ACTIONS --------------------------------------------------------
	public function footer_actions()
	{
		global $vbc_js;

		if ( ! $vbc_js )
			return;
	
		wp_print_scripts('vbc_js');
		wp_print_scripts('vbc_tooltip');
	}*/
	
}