<?php
/*
 * Plugin Name: Post Type Slider for Customizr
 * Plugin URI: http://risbl.co/wp/post-type-slider-for-customizr-plugin/
 * Description: Alternative slider creation for Customizr theme. Replace the existing slider with any choosen post type.
 * Version: 0.1
 * Author: Kharis Sulistiyono
 * Author URI: http://risbl.co/wp/about
 * Requires at least: 4.4
 * Tested up to: 4.4
 *
 * Text Domain: risbl-cpt-slider-customizr
 * Domain Path: /lang/
 *
 * Copyright: @ 2016 Kharis Sulistiyono.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Constants

if (!defined('RISBL_CUSTOMIZR_SLIDER_VERSION'))
  define('RISBL_CUSTOMIZR_SLIDER_VERSION', '0.1');

if (!defined('RISBL_CUSTOMIZR_SLIDER_PLUGIN_DIR'))
  define('RISBL_CUSTOMIZR_SLIDER_PLUGIN_DIR',str_replace('\\','/',dirname(__FILE__)));

if (!defined('RISBL_CUSTOMIZR_SLIDER_BASE_NAME'))
  define('RISBL_CUSTOMIZR_SLIDER_BASE_NAME', plugin_basename( __FILE__ ));

if (!defined('RISBL_CUSTOMIZR_SLIDER_PLUGIN_URL'))
  define('RISBL_CUSTOMIZR_SLIDER_PLUGIN_URL', plugins_url('', __FILE__));


// Plugin admin panel
include('class-admin.php');

// Meta box
include('class-metabox.php');


add_action( 'plugins_loaded', 'risbl_prefix_load_textdomain' );
if ( ! function_exists( 'risbl_prefix_load_textdomain' ) ) :

  /**
  * Load plugin textdomain.
  *
  * @since 1.0.0
  */
  function risbl_prefix_load_textdomain() {
   load_plugin_textdomain( 'risbl-cpt-slider-customizr', false, RISBL_CUSTOMIZR_SLIDER_BASE_NAME . '/i18n/languages' );
  }

endif;



/**
 * Check if slider is enabled
 */

function risbl_prefix_is_enabled(){

  $options = get_option('risbl_slider_customizr_settings');
  if( isset($options["enable"]) && $options["enable"] == '1'){
    return true;
  }else{
    return false;
  }

}


/**
 * Stop here if plugin dasabled
 *
 * @since 1.0.0
 * @return bool
 */
$is_enabled = risbl_prefix_is_enabled();
if(!$is_enabled){
  return;
}


add_filter('tc_show_slider_edit_link', 'risbl_prefix_remove_edit_slider_link');
if ( ! function_exists( 'risbl_prefix_remove_edit_slider_link' ) ) :

  /**
   * Remove the existing slider query.
   *
   * @since 0.1
   * @return bool
   */
  function risbl_prefix_remove_edit_slider_link(){
    return false;
  }

endif;

add_filter('tc_the_slides', 'risbl_prefix_get_slides_slides');
if ( ! function_exists( 'risbl_prefix_get_slides_slides' ) ) :

  /**
   * Change the existing slider query.
   *
   * @since 0.1
   * @return array Slider items
   */

  function risbl_prefix_get_slides_slides(){

    $slides = array();

    $args = array();
    $args['post_type'] = isset($options["post_type"]) ? $options["post_type"] : ''; // Your post type
    $args['post_status'] = 'publish';
    $args['meta_query'] = array(
      'relation' => 'AND',
      array(
        'key'     => 'risbl_prefix_set_as_slider',
        'value'   => 1,
        'compare' => '=',
      ),
      array(
        'key'     => 'risbl_slide_order_index'
      ),
    );
    $args['posts_per_page'] = -1;
    $args['orderby'] = 'meta_value';
    $args['meta_key'] = 'risbl_slide_order_index';
    $args['order']     = 'ASC';

    $queryposts = new WP_Query( $args );

    if( $queryposts->have_posts() ):
      $_loop_index = 0;
      while ( $queryposts->have_posts() ) : $queryposts->the_post();
        global $post;

        $key = array(
            'title_id'						=> 'risbl_prefix_title_id',
            'text_id'							=> 'risbl_prefix_text_id',
            'color_id'						=> 'risbl_prefix_color_id',
            'button_id'						=> 'risbl_prefix_button_id',
            'color_value'					=> 'risbl_prefix_color_value',
            'link_id'							=> 'risbl_prefix_link_id',
            'custom_link_id'			=> 'risbl_prefix_custom_link_id',
            'link_target_id'			=> 'risbl_prefix_link_target_id',
            'link_whole_slide_id'	=> 'risbl_prefix_link_whole_slide_id'
        );

        $thumbnail = get_the_post_thumbnail($post->ID,'slider-full');

        $slides[] = array(
          'title'               =>  get_post_meta($post->ID, $key['title_id'], true),
          'text'                =>  get_post_meta($post->ID, $key['text_id'], true),
          'button_text'         =>  get_post_meta($post->ID, $key['button_id'], true),
          'link_id'             =>  get_post_meta($post->ID, $key['link_id'], true),
          'link_url'            =>  get_post_meta($post->ID, $key['custom_link_id'], true),
          'link_target'         =>  get_post_meta($post->ID, $key['link_target_id'], true),
          'link_whole_slide'    =>  get_post_meta($post->ID, $key['link_whole_slide_id'], true),
          'active'              =>  ( 0 == $_loop_index ) ? 'active' : '',
          'color_style'         =>  get_post_meta($post->ID, $key['color_value'], true),
          'slide_background'    =>  $thumbnail
        );

        $_loop_index++;

      endwhile; wp_reset_postdata();
    endif;

    return $slides;

  }
endif;
