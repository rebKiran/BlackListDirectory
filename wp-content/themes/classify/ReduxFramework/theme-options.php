<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "redux_demo";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }

    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();
    
    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Classify Options', 'classify' ),
        'page_title'           => __( 'Classify Options', 'classify' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-docs',
        'href'  => 'http://joinwebs.co.uk/docs/classify',
        'title' => __( 'Documentation', 'classify' ),
    );

    $args['admin_bar_links'][] = array(
        //'id'    => 'redux-support',
        'href'  => 'https://joinwebs.com/support/',
        'title' => __( 'Support', 'classify' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'https://www.youtube.com/user/JoinWebs',
        'title' => 'Visit us on YouTube',
        'icon'  => 'el el-youtube'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/joinwebs',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/joinwebs',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/joinwebs',
        'title' => 'Find us on LinkedIn',
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Thanks for using Classify Classified Ads WordPress Theme, You are always welcome for support and help.</p>', 'classify' ), $v );
    } else {
        $args['intro_text'] = __( '<p>Thanks for using Classify Classified Ads WordPress Theme, You are always welcome for support and help.</p>', 'classify' );
    }

    // Add content after the form.
    $args['footer_text'] = __( '<p>We have Build Classify with ReduxFramework , If you have any problem with our option you can contact us any time on support.</p>', 'classify' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'classify' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'classify' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'classify' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'classify' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'classify' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

    // -> START General Settings
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'General Settings', 'classify' ),
        'id'               => 'general-settings',        
        'customizer_width' => '500px',
		'icon'             => 'el el-home',
        'desc'             => __( 'These are really basic fields', 'classify' ),
        'fields'           => array(            
            array(
				'id'=>'logo',
				'type' => 'media', 
				'url'=> true,
				'title' => __('Logo', 'classify'),
				'compiler' => 'true',
				//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
				'desc'=> __('Upload your logo.Recommended (Height:40px, Width:200px)', 'classify'),
				'subtitle' => __('Logo Image', 'classify'),
				'default'=>array('url'=>''),
			),
			array(
				'id'=>'favicon',
				'type' => 'media', 
				'url'=> true,
				'title' => __('Favicon', 'classify'),
				'compiler' => 'true',
				//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
				'desc'=> __('Upload your favicon.', 'classify'),
				'subtitle' => __('Favicon', 'classify'),
				'default'=>array('url'=>''),
			),
			array(
				'id'=>'layout-version',
				'type' => 'radio',
				'title' => __('Layout', 'classify'), 
				'subtitle' => __('Select Layout', 'classify'),
				'desc' => __('You can use Boxed or Wide Layout', 'classify'),
				'options' => array('1' => 'Wide', '2' => 'Boxed'),//Must provide key => value pairs for radio options
				'default' => '1'
			),			
			array(
				'id'=>'measure-system',
				'type' => 'radio',
				'title' => __('Measurement system', 'classify'), 
				'subtitle' => __('Measurement', 'classify'),
				'desc' => __('Select Measurement', 'classify'),
				'options' => array('1' => 'Miles', '2' => 'Kilometers'),//Must provide key => value pairs for radio options
				'default' => '2'
			),
			array(
				'id'=>'max_range',
				'type' => 'text',
				'title' => __('Maxim Range', 'classify'),
				'subtitle' => __('Range', 'classify'),
				'desc' => __('You can add the max geolocation range (default: 1000', 'classify'),
				'default' => '1000'
			),
			array(
				'id'=>'ad_expiry',
				'type' => 'select',
				'title' => __('Regular Ads Expiry', 'classify'), 
				'subtitle' => __('Ads Expiry', 'classify'),
				'desc' => __('Select Ads Expiry', 'classify'),
				'options' => array('7' => 'One week', '30' => 'One Month', '60' => 'Two Months', '90' => 'Three Months', '120' => 'Four Months', '150' => 'Five Months', '180' => 'Six Month', '365' => 'One Year', '36500' => 'Life Time'),//Must provide key => value pairs for radio options
				'default' => '36500'
			),			
			array(
				'id' => 'featured-options-on',
				'type' => 'switch',
				'title' => __('Premium Ads', 'classify'),
				'subtitle' => __('Premium Ads On/OFF', 'classify'),
				'default' => 1,
            ),			
			array(
				'id' => 'hide-fslider',
				'type' => 'switch',
				'title' => __('Premium / Featured Ads Slider', 'classify'),
				'subtitle' => __('Featured Slider', 'classify'),
				'default' => 1,
            ),			
			array(
				'id'=>'featured_cat',
				'type' => 'text',
				'title' => __('Featured Category', 'classify'),
				'subtitle' => __('Featured Category', 'classify'),
				'desc' => __('Put here any category name same as there in categories for featured ads(It will work if featured ads disable)', 'classify'),
			),
			
		   array(
				'id' => 'featured-options-cat',
				'type' => 'switch',
				'title' => __('Featured Ads on category related to category', 'classify'),
				'subtitle' => __('Featured Ads', 'classify'),
				'default' => 0,
            ),			
			array(
				'id' => 'homepage-adloc-on',
				'type' => 'switch',
				'title' => __('Ads Location Box', 'classify'),
				'subtitle' => __('Ads Location Box on homepage', 'classify'),
				'default' => 1,
            ),
			array(
				'id'=>'locations',
				'type' => 'textarea',
				'title' => __('AD Locations', 'classify'),
				'subtitle' => __('Locations', 'classify'),
				'desc' => __('Put AD locations Here With comma seperation. Example: London,New york', 'classify'),
				'default' => ''
			),
			array(
				'id' => 'regular-free-ads',
				'type' => 'switch',
				'title' => __('Regular ad posting', 'classify'),
				'subtitle' => __('On/OFF Regular ad posting', 'classify'),
				'default' => 1,
            ),
			array(
				'id' => 'hide-map',
				'type' => 'switch',
				'title' => __('Map on single category', 'classify'),
				'subtitle' => __('Map', 'classify'),
				'default' => 1,
            ),
			array(
				'id'=>'tags_limit',
				'type' => 'text',
				'title' => __('Number of tags in tag Cloud widget', 'classify'),
				'subtitle' => __('Cloud widget', 'classify'),
				'desc' => __('Put here a number. Example "16"', 'classify'),
				'default' => '15'
			),
			array(
				'id'=>'google_id',
				'type' => 'text',
				'title' => __('Google Analytics Domain ID', 'classify'),
				'subtitle' => __('Google Analytics', 'classify'),
				'desc' => __('Get analytics on your site. Enter Google Analytics Domain ID (ex: UA-123456-1)', 'classify'),
				'default' => ''
			),
			array(
				'id'=>'clasify_google_api',
				'type' => 'text',
				'title' => __('Google API Key', 'classify'),
				'subtitle' => __('Google API Key', 'classify'),
				'desc' => __('Put Google API Key here to run Google MAP. If you dont know how to get API key Please Visit  <a href="http://www.tthemes.com/get-google-api-key/" target="_blank">Google API Key</a>', 'classify'),
				'default' => ''
			),
			array(
				'id'=>'footer_copyright',
				'type' => 'text',
				'title' => __('Footer Copyright Text', 'classify'),
				'subtitle' => __('Copyright', 'classify'),
				'desc' => __('You can add text and HTML in here.', 'classify'),
				'default' => ''
			),
			array(
				'id'=>'map-style',
				'type' => 'textarea',
				'title' => __('Map Styles', 'classify'), 
				'subtitle' => __('Check <a href="http://snazzymaps.com/" target="_blank">snazzymaps.com</a> for a list of nice google map styles.', 'classify'),
				'desc' => __('Ad here your google map style.', 'classify'),
				'validate' => 'html_custom',
				'default' => '',
				'allowed_html' => array(
					'a' => array(
						'href' => array(),
						'title' => array()
					),
					'br' => array(),
					'em' => array(),
					'strong' => array()
					)
			),
            
            
        )
    ) );
	// -> START Home settings
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Home settings', 'classify' ),
        'id'               => 'home-settings',
		'icon'             => 'el el-home',
        'customizer_width' => '500px',
        'desc'             => __( 'These are Home settings!', 'classify' ),
        'fields'           => array(
            array(
				'id'=>'home-cat-counter',
				'type' => 'select',
				'title' => __('How many Categories on homepage?', 'classify'),
				'subtitle' => __('Categories on homepage', 'classify'),
				'desc' => __('Categories on homepage', 'classify'),
				'options' => array('4' => '4', '8' => '8', '12' => '12' , '16' => '16', '20' => '20', '24' => '24', '28' => '28', '32' => '32', '36' => '36'),
				'default' => '8'
			),
			array(
				'id'=>'home-ads-counter',
				'type' => 'select',
				'title' => __('How many ads on homepage?', 'classify'),
				'subtitle' => __('Ads on homepage', 'classify'),
				'desc' => __('Ads on homepage', 'classify'),
				'options' => array('4' => '4','8' => '8','12' => '12', '16' => '16', '20' => '20', '24' => '24', '28' => '28', '32' => '32'),
				'default' => '12'
			),
			array(
				'id' => 'category-block',
				'type' => 'switch',
				'title' => __('Homepage Category Block', 'classify'),
				'subtitle' => __('On/OFF Homepage Category Block', 'classify'),
				'default' => 1,
            ),
			array(
				'id'=>'home-ads-view',
				'type' => 'select',
				'title' => __('Select Ads view type', 'classify'),
				'subtitle' => __('Ads view', 'classify'),
				'desc' => __('Select Ads view type', 'classify'),
				'options' => array('grid' => 'Grid view', 'list' => 'List view'),
				'default' => 'grid'
			),            
        )
    ) );
	// -> START Home settings
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Post Ads', 'classify' ),
        'id'               => 'post-ads',
		'icon'             => 'el el-home',
        'customizer_width' => '500px',
        'desc'             => __( 'Post Ads Page Settings', 'classify' ),
        'fields'           => array(
			array(
				'id' => 'post-options-on',
				'type' => 'switch',
				'title' => __('Post moderation', 'classify'),
				'subtitle' => __('Moderation', 'classify'),
				'default' => 1,
            ),
			array(
				'id' => 'hide-rads',
				'type' => 'switch',
				'title' => __('Related Ads on Single Post', 'classify'),
				'subtitle' => __('Related Ads', 'classify'),
				'default' => 1,
            ),
			array(
				'id'=>'free_price_text',
				'type' => 'text',
				'title' => __('Free listing tag', 'classify'),
				'subtitle' => __('Listing Tag', 'classify'),
				'desc' => __('Add here the text tag for free listings', 'classify'),
				'default' => 'Free'
			),
			array(
				'id' => 'post-options-edit-on',
				'type' => 'switch',
				'title' => __('Post moderation On every edit post', 'classify'),
				'subtitle' => __('Moderation edit post', 'classify'),
				'default' => 1,
           ),
		   array(
				'id' => 'author-msg-box-off',
				'type' => 'switch',
				'title' => __('Author Message Box On/OFF', 'classify'),
				'subtitle' => __('Author Message box on ad detail page', 'classify'),
				'default' => 1,
            ),
			array(
				'id' => 'classify-google-lat-long',
				'type' => 'switch',
				'title' => __('Latitude and Longitude', 'classify'),
				'subtitle' => __('Turn On/OFF Latitude and Longitude from Ads Post', 'classify'),
				'desc' => __('If You dont want user put Latitude and Longitude while posting ads then just turn OFF this option.', 'classify'),
				'default' => 1,
            ),
			array(
				'id' => 'classify-google-map-adpost',
				'type' => 'switch',
				'title' => __('Google MAP on Post Ads', 'classify'),
				'subtitle' => __('Turn On/OFF Google MAP from Ads Post', 'classify'),
				'desc' => __('If You want to hide Google MAP from Submit Ads Page And Single Ads Page Then Turn OFF this Option.', 'classify'),
				'default' => 1,
            ),
		)
    ) );
	// -> START Callout Settings
    Redux::setSection( $opt_name, array(
        'title'      => __( 'CallOut', 'classify' ),
        'id'         => 'callout',
		'icon'             => 'el el-bullhorn',        
        'desc'             => __( 'Callout Message For Home page!', 'classify' ),
        'fields'     => array(
            array(
				'id' => 'homepage-callout-on',
				'type' => 'switch',
				'title' => __('Callout Message Box', 'classify'),
				'subtitle' => __('Callout Message Box on homepage', 'classify'),
				'default' => 1,
            ),
		array(
				'id'=>'callout_title',
				'type' => 'text',
				'title' => __(' Callout Title', 'classify'),
				'desc'=> __('Put here your Callout title', 'classify'),
				'subtitle' => __('Callout title', 'classify'),
				'default'=>'Are you ready for the posting your ads on Classify ?',
			),
		array(
				'id'=>'callout_desc',
				'type' => 'textArea',
				'title' => __(' Callout Description', 'classify'),
				'desc'=> __('Put here your Callout Description', 'classify'),
				'subtitle' => __('Callout Description', 'classify'),
				'default'=>'Vivamus in lectus purus. Quisque rhoncus erat tincidunt, dignissim nunc at, sodales turpis. Donec convallis rhoncus lorem ac ullamcorper. Morbi a mi facilisis, feugiat mi vel, facilisis ipsum.',
			),
		array(
				'id'=>'callout_btn_text',
				'type' => 'text',
				'title' => __(' Callout Button Text', 'classify'),
				'desc'=> __('Put here your Callout Button Text', 'classify'),
				'subtitle' => __('Callout Button', 'classify'),
				'default'=>'',
			),
			array(
				'id'=>'callout_btn_url',
				'type' => 'text',
				'title' => __(' Callout Button URL', 'classify'),
				'desc'=> __('Put here your Callout Button URL', 'classify'),
				'subtitle' => __('Callout Button URL', 'classify'),
				'validate' => 'url',
				'default'=>'',
			),            
        )
    ) );
	// -> START Custom Ads Settings
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Advertainment', 'classify' ),        
        'id'               => 'classify-custom-ads',
        'icon'             => 'el el-usd',
        'customizer_width' => '500px',
        'fields'           => array(
            array(
				'id'=>'home_ad',
				'type' => 'media', 
				'url'=> true,
				'title' => __('Home Page Ad', 'classify'),
				'compiler' => 'true',
				//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
				'desc'=> __('Upload your Ad Image.', 'classify'),
				'subtitle' => __('Upload Banner', 'classify'),
				'default'=>array('url'=>''),
			),
			array(
				'id'=>'home_ad_link',
				'type' => 'text',
				'title' => __('Home Page Ad link URL', 'classify'),
				'subtitle' => __('Ad link URL', 'classify'),
				'desc' => __('You can add URL.', 'classify'),
				'default' => '',
				'validate' => 'url',
			),
			array(
				'id'=>'home_ad_code_client',
				'type' => 'textarea',
				'title' => __('Google ads or HTML Ads (HOME PAGE)', 'classify'),
				'subtitle' => __('HTML Ads', 'classify'),
				'desc' => __('Put your Google Ads code or HTML Ads code', 'classify'),
				'default' => ''
			),
			array(
				'id'=>'post_ad',
				'type' => 'media', 
				'url'=> true,
				'title' => __('Post Detail Page Ad', 'classify'),
				'compiler' => 'true',
				//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
				'desc'=> __('Upload your Ad Image.', 'classify'),
				'subtitle' => __('Upload Banner', 'classify'),
				'default'=>array('url'=>''),
			),
			array(
				'id'=>'post_ad_link',
				'type' => 'text',
				'title' => __('Post Page Ad link URL', 'classify'),
				'subtitle' => __('Ad link URL', 'classify'),
				'desc' => __('You can add URL.', 'classify'),
				'default' => '',
				'validate' => 'url',
			),
			array(
				'id'=>'post_ad_code_client',
				'type' => 'textarea',
				'title' => __('Google ads or HTML Ads (POST PAGE)', 'classify'),
				'subtitle' => __('HTML Ads', 'classify'),
				'desc' => __('Put your Google Ads code or HTML Ads code.', 'classify'),
				'default' => ''
			),
        )
    ) );
	// -> START Pages Settings
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Pages', 'classify' ),
        'id'         => 'classify-pages',
		'icon'             => 'el el-list-alt',        
        'fields'     => array(            
			array(
				'id'=>'login',
				'type' => 'text',
				'title' => __('Login Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Login Page', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'profile',
				'type' => 'text',
				'title' => __('Profile Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Profile Page', 'classify'),
				'validate' => 'url',
				'default' => ''
			),	
			array(
				'id'=>'edit',
				'type' => 'text',
				'title' => __('Edit Profile Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Edit Profile', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'register',
				'type' => 'text',
				'title' => __('Register Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Register Page', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'reset',
				'type' => 'text',
				'title' => __('Reset Password Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Reset Password', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'new_post',
				'type' => 'text',
				'title' => __('New Ad Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('New Ad Page', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'edit_post',
				'type' => 'text',
				'title' => __('Edit Ad Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Edit Ad Page', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'all-ads',
				'type' => 'text',
				'title' => __('Display All Ads Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Display All Ads', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'featured_plans',
				'type' => 'text',
				'title' => __('Featured Plans Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Featured Plans', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
        )
    ) );
	// -> START Fonts Settings
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Fonts', 'classify' ),
        'id'         => 'classify-fonts',
		'icon'             => 'el el-fontsize',
        'fields'     => array(            
			array(
				'id' => 'heading1-font',
				'type' => 'typography',
				'title' => __('H1 Font', 'classify'),
				'subtitle' => __('Specify the headings font properties.', 'classify'),
				'google' => true,
				'output' => array('h1, h1 a, h1 span'),
				'default' => array(
					'color' => '#3f3d59',
					'font-size' => '38.5px',
					'font-family' => 'Roboto Slab',
					'font-weight' => '700',
					'line-height' => '40px',
					),
         	),

			array(
				'id' => 'heading2-font',
				'type' => 'typography',
				'title' => __('H2 Font', 'classify'),
				'subtitle' => __('Specify the headings font properties.', 'classify'),
				'google' => true,
				'output' => array('h2, h2 a, h2 span'),
				'default' => array(
					'color' => '#3f3d59',
					'font-size' => '31.5px',
					'font-family' => 'Roboto Slab',
					'font-weight' => '700',
					'line-height' => '40px',
					),
         	),

			array(
				'id' => 'heading3-font',
				'type' => 'typography',
				'title' => __('H3 Font', 'classify'),
				'subtitle' => __('Specify the headings font properties.', 'classify'),
				'google' => true,
				'output' => array('h3, h3 a, h3 span'),
				'default' => array(
					'color' => '#3f3d59',
					'font-size' => '18px',
					'font-family' => 'Roboto Slab',
					'font-weight' => '700',
					'line-height' => '40px',
					),
         	),

			array(
				'id' => 'heading4-font',
				'type' => 'typography',
				'title' => __('H4 Font', 'classify'),
				'subtitle' => __('Specify the headings font properties.', 'classify'),
				'google' => true,
				'output' => array('h4, h4 a, h4 span'),
				'default' => array(
					'color' => '#3f3d59',
					'font-size' => '18px',
					'font-family' => 'Roboto Slab',
					'font-weight' => '700',
					'line-height' => '40px',
					),
         	),

			array(
				'id' => 'heading5-font',
				'type' => 'typography',
				'title' => __('H5 Font', 'classify'),
				'subtitle' => __('Specify the headings font properties.', 'classify'),
				'google' => true,
				'output' => array('h5, h5 a, h5 span'),
				'default' => array(
					'color' => '#484848',
					'font-size' => '14px',
					'font-family' => 'Roboto',
					'font-weight' => '300',
					'line-height' => '40px',
					),
         	),

			array(
				'id' => 'heading6-font',
				'type' => 'typography',
				'title' => __('H6 Font', 'classify'),
				'subtitle' => __('Specify the headings font properties.', 'classify'),
				'google' => true,
				'output' => array('h6, h6 a, h6 span'),
				'default' => array(
					'color' => '#484848',
					'font-size' => '11.9px',
					'font-family' => 'Roboto',
					'font-weight' => '300',
					'line-height' => '40px',
					),
         	),

			array(
				'id' => 'body-font',
				'type' => 'typography',
				'title' => __('Body Font', 'classify'),
				'subtitle' => __('Specify the body font properties.', 'classify'),
				'google' => true,
				'output' => array('span.ad-page-price,html, body, div, applet, object, iframe p, blockquote, a, abbr, acronym, address, big, cite, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video'),
				'default' => array(
					'color' => '#888888',
					'font-size' => '14px',
					'font-family' => 'Raleway',
					'font-weight' => 'Normal',
					'line-height' => '24px',
					),
         	),
        )
    ) );
	// -> START Colors Settings
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Colors', 'classify' ),
        'id'         => 'classify-colors',
		'icon'             => 'el el-brush',
        'fields'     => array(
            
			array(
				'id'       => 'color-primary',
				'type'     => 'color',
				'title'    => __('Primary Color', 'classify'), 
				'subtitle' => __('Pick a Primary Color (default: #3f3d59).', 'classify'),
				'default'  => '#3f3d59',
				'validate' => 'color',
				'transparent' => false,
			),
			
			array(
				'id'       => 'color-main',
				'type'     => 'color',
				'title'    => __('Link Color', 'classify'), 
				'subtitle' => __('Pick a color for link (default: #666).', 'classify'),
				'default'  => '#666',
				'validate' => 'color',
				'transparent' => false,
			),

			array(
				'id'       => 'color-main-hover',
				'type'     => 'color',
				'title'    => __('Hover/Active Link State Color', 'classify'), 
				'subtitle' => __('Pick a color for hover/active link state (default: #a0ce4e).', 'classify'),
				'default'  => '#a0ce4e',
				'validate' => 'color',
				'transparent' => false,
			),

			array(
				'id'       => 'button-color-main',
				'type'     => 'color',
				'title'    => __('Buttons Color', 'classify'), 
				'subtitle' => __('Pick a color for buttons (default: #a0ce4e).', 'classify'),
				'default'  => '#a0ce4e',
				'validate' => 'color',
				'transparent' => false,
			),

			array(
				'id'       => 'button-color-main-hover',
				'type'     => 'color',
				'title'    => __('Buttons Hover State Color', 'classify'), 
				'subtitle' => __('Pick a color for buttons hover state (default: #96c149).', 'classify'),
				'default'  => '#96c149',
				'validate' => 'color',
				'transparent' => false,
			),
			array(
				'id'       => 'fooer-color',
				'type'     => 'color',
				'title'    => __('Footer background color', 'classify'), 
				'subtitle' => __('Pick a color for Footer background (default: #3f3d59).', 'classify'),
				'default'  => '#3f3d59',
				'validate' => 'color',
				'transparent' => false,
			),
        )
    ) );
    // -> START Social Links
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Links', 'classify' ),
        'id'         => 'classify-social-links',
        'icon'  => 'el el-slideshare',
        'fields'     => array(            
			array(
				'id'=>'facebook-link',
				'type' => 'text',
				'title' => __('Facebook Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Facebook', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'twitter-link',
				'type' => 'text',
				'title' => __('Twitter Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Twitter', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'dribbble-link',
				'type' => 'text',
				'title' => __('Dribbble Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Dribbble', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'flickr-link',
				'type' => 'text',
				'title' => __('Flickr Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Flickr', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'github-link',
				'type' => 'text',
				'title' => __('Github Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Github', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'pinterest-link',
				'type' => 'text',
				'title' => __('Pinterest Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Pinterest', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'youtube-link',
				'type' => 'text',
				'title' => __('Youtube Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Youtube', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'google-plus-link',
				'type' => 'text',
				'title' => __('Google+ Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Google', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'linkedin-link',
				'type' => 'text',
				'title' => __('LinkedIn Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('LinkedIn', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'tumblr-link',
				'type' => 'text',
				'title' => __('Tumblr Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Tumblr', 'classify'),
				'validate' => 'url',
				'default' => ''
			),

			array(
				'id'=>'vimeo-link',
				'type' => 'text',
				'title' => __('Vimeo Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Vimeo', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'instagram-link',
				'type' => 'text',
				'title' => __('Instagram Page URL', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Instagram', 'classify'),
				'validate' => 'url',
				'default' => ''
			),	
            
            
        ),        
    ) );
	// -> START Twitter Settings
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Twitter Api', 'classify' ),
        'id'         => 'classify-twitter-api',
        'icon'  => 'el el-twitter',                
        'fields'     => array(            
			array(
				'id'=>'consumer_key',
				'type' => 'text',
				'title' => __('Consumer Key', 'classify'),
				'subtitle' => __('Consumer Key', 'classify'),
				'desc' => __('Twitter Consumer Key', 'classify'),
				'default' => ''
			),

			array(
				'id'=>'consumer_secret',
				'type' => 'text',
				'title' => __('Consumer Secret', 'classify'),
				'subtitle' => __('Consumer Secret', 'classify'),
				'desc' => __('Twitter Consumer Secret', 'classify'),
				'default' => ''
			),

			array(
				'id'=>'access_token',
				'type' => 'text',
				'title' => __('User Access Token', 'classify'),
				'subtitle' => __('User Access Token', 'classify'),
				'desc' => __('Twitter User Access Token', 'classify'),
				'default' => ''
			),

			array(
				'id'=>'access_token_secret',
				'type' => 'text',
				'title' => __('User Access Token Secret', 'classify'),
				'subtitle' => __('User Access Token Secret', 'classify'),
				'desc' => __('Twitter User Access Token Secret', 'classify'),
				'default' => ''
			),
        )
    ) );

    // -> START Translate Selection
    Redux::setSection( $opt_name, array(
        'title' => __( 'Translate', 'classify' ),
        'id'    => 'classify-Translate',
        'desc'  => __( 'You Can Translate Text Strings from this section.', 'classify' ),
        'icon'  => 'el el-flag',
		'fields'     => array(
			array(
				'id'=>'trns_post_ad_title',
				'type' => 'text',
				'title' => __('Post Your Ad', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Post Your Ad'
			),		
			array(
				'id'=>'trns_account_title',
				'type' => 'text',
				'title' => __('Your Acount Link', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'My Account'
			),			
			array(
				'id'=>'trns_account_sttings_title',
				'type' => 'text',
				'title' => __('ACCOUNT SETTINGS', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Settings'
			),		
			array(
				'id'=>'trns_login_title',
				'type' => 'text',
				'title' => __('Your Acount Login Link', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Login'
			),		
			array(
				'id'=>'trns_logout_title',
				'type' => 'text',
				'title' => __('Your Acount Logout Link', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Log out'
			),		
			array(
				'id'=>'trns_register_title',
				'type' => 'text',
				'title' => __('Your Acount Register Link', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Register'
			),
			array(
				'id'=>'trns_skeywords',
				'type' => 'text',
				'title' => __('Enter Keywords', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Enter keyword...'
			),		
			array(
				'id'=>'trns_location',
				'type' => 'text',
				'title' => __('Search Location', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Location'
			),
			array(
				'id'=>'trns_category',
				'type' => 'text',
				'title' => __('Search category', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Category'
			),
			array(
				'id'=>'trns_premium_ads',
				'type' => 'text',
				'title' => __('PREMIUM ADVERTISEMENT', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'PREMIUM ADVERTISEMENT'
			),			
			array(
				'id'=>'trns_categories',
				'type' => 'text',
				'title' => __('CATEGORIES', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'CATEGORIES'
			),			
			array(
				'id'=>'trns_others',
				'type' => 'text',
				'title' => __('Others', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Others'
			),
			array(
				'id'=>'trns_classify_ads',
				'type' => 'text',
				'title' => __('CLASSIFY ADVERTISEMENT', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'CLASSIFY ADVERTISEMENT'
			),			
			array(
				'id'=>'trns_latest_ads',
				'type' => 'text',
				'title' => __('Latest Ads', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Latest Ads'
			),			
			array(
				'id'=>'trns_popular_ads',
				'type' => 'text',
				'title' => __('Most Popular Ads', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Most Popular Ads'
			),		
			array(
				'id'=>'trns_random_ads',
				'type' => 'text',
				'title' => __('Random Ads', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Random Ads'
			),		
			array(
				'id'=>'trns_ragular_ads',
				'type' => 'text',
				'title' => __('Regular Ads', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Regular Ads'
			),		
			array(
				'id'=>'trns_featured_ads',
				'type' => 'text',
				'title' => __('Featured Ads', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Featured Ads'
			),			
			array(
				'id'=>'trns_featured_ads_left',
				'type' => 'text',
				'title' => __('Featured Ads Left', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Featured Ads Left'
			),			
			array(
				'id'=>'trns_account_overview',
				'type' => 'text',
				'title' => __('ACCOUNT OVERVIEW', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'ACCOUNT OVERVIEW'
			),			
			array(
				'id'=>'trns_related_ads',
				'type' => 'text',
				'title' => __('RELATED ADs', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'RELATED ADs'
			),		
			array(
				'id'=>'trns_sub_cat',
				'type' => 'text',
				'title' => __('SUBCATEGORIES', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'SUBCATEGORIES'
			),			
			array(
				'id'=>'trns_pricing_plan',
				'type' => 'text',
				'title' => __('PRICING PLANS', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'PRICING PLANS'
			),		
			array(
				'id'=>'trns_purchase_now',
				'type' => 'text',
				'title' => __('Purchase Now', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Purchase Now'
			),		
			array(
				'id'=>'trns_contact_details',
				'type' => 'text',
				'title' => __('CONTACT DETAILS', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'CONTACT DETAILS'
			),		
			array(
				'id'=>'trns_description',
				'type' => 'text',
				'title' => __('DESCRIPTION', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'DESCRIPTION'
			),
			array(
				'id'=>'trns_ads_locations',
				'type' => 'text',
				'title' => __('AD LOCATIONS', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'AD LOCATIONS'
			),
			array(
				'id'=>'read_more_btn',
				'type' => 'text',
				'title' => __('Read More Button', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Read More'
			),
			array(
				'id'=>'trns_listing_published',
				'type' => 'text',
				'title' => __('Publish Post Email Title', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Your Listing has been Published!'
			),
			array(
				'id'=>'trns_welcome_user_title',
				'type' => 'text',
				'title' => __('Welcome email title', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'Welcome To Classify'
			),
			array(
				'id'=>'trns_new_post_posted',
				'type' => 'text',
				'title' => __('New Post Email to Admin', 'classify'),
				'subtitle' => __('You can change with your own text.', 'classify'),
				'desc' => __('Translate Text', 'classify'),
				'default' => 'New Post has been Posted!'
			),
			
		),
    ) );
	// -> START Contact Settings
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Contact Page', 'classify' ),
        'id'         => 'classify-contact-page', 
		'icon'  => 'el el-envelope',
        'fields'     => array(            
            array(
				'id'=>'contact-email',
				'type' => 'text',
				'title' => __('Your email address', 'classify'),
				'subtitle' => __('This must be an email address.', 'classify'),
				'desc' => __('Email address', 'classify'),
				'validate' => 'email',
				'default' => ''
			),
			array(
				'id'=>'contact-email-error',
				'type' => 'text',
				'title' => __('Email error message', 'classify'),
				'subtitle' => __('Email error message', 'classify'),
				'desc' => __('Email error message', 'classify'),
				'default' => 'You entered an invalid email.'
			),
			array(
				'id'=>'contact-name-error',
				'type' => 'text',
				'title' => __('Name error message', 'classify'),
				'subtitle' => __('Name error message', 'classify'),
				'desc' => __('Name error message', 'classify'),
				'default' => 'You forgot to enter your name.'
			),
			array(
				'id'=>'contact-message-error',
				'type' => 'text',
				'title' => __('Message error', 'classify'),
				'subtitle' => __('Message error', 'classify'),
				'desc' => __('Message error', 'classify'),
				'default' => 'You forgot to enter your message.'
			),
			array(
				'id'=>'contact-thankyou-message',
				'type' => 'text',
				'title' => __('Thank you message', 'classify'),
				'subtitle' => __('Thank you message', 'classify'),
				'desc' => __('Thank you message', 'classify'),
				'default' => 'Thank you! We will get back to you as soon as possible.'
			),
			array(
				'id'=>'contact-latitude',
				'type' => 'text',
				'title' => __('Latitude', 'classify'),
				'subtitle' => __('Latitude', 'classify'),
				'desc' => __('Latitude', 'classify'),
				'default' => ''
			),
			array(
				'id'=>'contact-longitude',
				'type' => 'text',
				'title' => __('Longitude', 'classify'),
				'subtitle' => __('Longitude', 'classify'),
				'desc' => __('Longitude', 'classify'),
				'default' => ''
			),
			array(
				'id'=>'contact-zoom',
				'type' => 'text',
				'title' => __('Zoom level', 'classify'),
				'subtitle' => __('Zoom level', 'classify'),
				'desc' => __('Zoom level', 'classify'),
				'default' => ''
			),
        ),
    ) );
	// -> START PayPal Settings
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Paypal Settings', 'classify' ),
        'icon'  => 'el el-path',
        'id'         => 'classify-paypal-settings',        
        'fields'     => array(            
			array(
				'id'=>'paypal_api_environment',
				'type' => 'radio',
				'title' => __('PayPal environment', 'classify'), 
				'subtitle' => __('PayPal environment', 'classify'),
				'desc' => __('Select PayPal environment', 'classify'),
				'options' => array('1' => 'Sandbox - Testing', '2' => 'Live - Production'),//Must provide key => value pairs for radio options
				'default' => '1'
			),		
			array(
				'id'=>'paypal_api_username',
				'type' => 'text',
				'title' => __('API Username (required)', 'classify'),
				'subtitle' => __('API Username', 'classify'),
				'desc' => __('PayPal API Username', 'classify'),
				'default' => ''
			),	
			array(
				'id'=>'paypal_api_password',
				'type' => 'text',
				'title' => __('API Password (required)', 'classify'),
				'subtitle' => __('API Password', 'classify'),
				'desc' => __('PayPal API Password', 'classify'),
				'default' => ''
			),
			array(
				'id'=>'paypal_api_signature',
				'type' => 'text',
				'title' => __('API Signature (required)', 'classify'),
				'subtitle' => __('API Signature', 'classify'),
				'desc' => __('PayPal API Signature', 'classify'),
				'default' => ''
			),
			array(
				'id'=>'paypal_success',
				'type' => 'text',
				'title' => __('Thank you page - after successful payment', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Thank you page', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'paypal_fail',
				'type' => 'text',
				'title' => __('Thank you page - after failed payment', 'classify'),
				'subtitle' => __('This must be an URL.', 'classify'),
				'desc' => __('Thank you page ', 'classify'),
				'validate' => 'url',
				'default' => ''
			),
			array(
				'id'=>'currency_code',
				'type' => 'select',
				'title' => __('Currency', 'classify'), 
				'subtitle' => __('Select your currency.', 'classify'),
				'options' => array('AUD'=>'Australian Dollar', 'BRL'=>'Brazilian Real', 'CAD'=>'Canadian Dollar', 'CZK'=>'Czech Koruna', 'DKK'=>'Danish Krone', 'EUR'=>'Euro', 'HKD'=>'Hong Kong Dollar', 'HUF'=>'Hungarian Forint', 'ILS'=>'Israeli New Sheqel', 'JPY'=>'Japanese Yen', 'MYR'=>'Malaysian Ringgit', 'MXN'=>'Mexican Peso', 'NOK'=>'Norwegian Krone', 'NZD'=>'New Zealand Dollar', 'PHP'=>'Philippine Peso', 'PLN'=>'Polish Zloty', 'GBP'=>'Pound Sterling', 'SEK'=>'Swedish Krona', 'SGD'=>'Singapore Dollar', 'CHF'=>'Swiss Franc', 'TWD'=>'Taiwan New Dollar',  'THB'=>'Thai Baht', 'TRY'=>'Turkish Lira','USD'=>'U.S. Dollar'),
				'default' => 'USD',
			),
        ),
    ) );
	
    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'classify' ),
            'fields' => array(
                array(
                    'id'       => '17',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
        );
        Redux::setSection( $opt_name, $section );
    }
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links
   add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'classify' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'classify' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }

    /**
     * Removes the demo link and the notice of integrated demo from the redux-framework plugin
     */
    if ( ! function_exists( 'remove_demo' ) ) {
        function remove_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array(
                    ReduxFrameworkPlugin::instance(),
                    'plugin_metalinks'
                ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            }
        }
    }

