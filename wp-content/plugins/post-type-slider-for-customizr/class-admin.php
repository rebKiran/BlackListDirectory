<?php

/**
 * Create plugin admin panel.
 *
 * @package 	Post Type Slider for Customizr
 * @since 		0.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( ! class_exists( 'Risbl_Prefix_Admin' ) ) :


	/**
	 * Plugin admin panel class.
	 *
	 * @class       Risbl_Prefix_Admin
	 * @package 		Post Type Slider for Customizr
	 * @since 			0.1
	 * @author      Kharis Sulistiyono
	 */

  class Risbl_Prefix_Admin {

    /**
     * Constructor
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
     */
    public function __construct(){

			add_image_size( 'risbl-slider-thumb', 100, 100, true );
      add_action( 'admin_init', 						array( $this, 'option_init' ) );
      add_action( 'admin_menu', 						array( $this, 'option_menu' ) );
			add_action( 'admin_enqueue_scripts', 	array( $this , 'admin_scripts' ));
			add_filter( 'init', array( $this, 'update_slider_order'), 10, 2 );
			add_filter( 'plugin_action_links_'.RISBL_CUSTOMIZR_SLIDER_BASE_NAME, array( $this, 'add_settings_link'), 5, 2);

    }


		/**
     * Add plugin setting link
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
		 * @param				$links
		 * @return			string
     */
		function add_settings_link( $links ) {
		    $settings_link = '<a href="options-general.php?page=risbl_slider_customizr_settings">' . __( 'Settings', 'risbl-cpt-slider-customizr' ) . '</a>';
		    array_push( $links, $settings_link );
		  	return $links;
		}


		/**
     * Register plugin setting
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
     */
    public function option_init(){
        register_setting( 'risbl_setting_field_group', 'risbl_slider_customizr_settings' );
    }

		/**
     * Add plugin option page
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
     */
    public function option_menu() { // sinii

        // Sub menu under Settings
        add_options_page(__('CPT Customizr Slider', 'risbl-cpt-slider-customizr'), __('CPT Customizr Slider', 'risbl-cpt-slider-customizr'), 'manage_options', 'risbl_slider_customizr_settings', array( $this,'risbl_slider_customizr_settings_page_content') );

    }

		/**
     * Enqueue scripts
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
     */
		public function admin_scripts(){

			wp_enqueue_style( 'risbl-admin-css', RISBL_CUSTOMIZR_SLIDER_PLUGIN_URL . '/css/risbl-admin.css', array(), RISBL_CUSTOMIZR_SLIDER_VERSION );

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script('risbl-admin', RISBL_CUSTOMIZR_SLIDER_PLUGIN_URL .'/js/risbl-admin.js', array('jquery', 'jquery-ui-sortable'), RISBL_CUSTOMIZR_SLIDER_VERSION, true);

		}

		/**
     * Admin panel content markup
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
		 * @return 			string
     */
    public function risbl_slider_customizr_settings_page_content(){
    	?>
        <div class="wrap">
            <h2><?php echo __('CPT Customizr Slider Settings', 'risbl-cpt-slider-customizr'); ?></h2>
            <form method="post" action="options.php">
                <?php settings_fields('risbl_setting_field_group'); ?>
                <?php $options = get_option('risbl_slider_customizr_settings'); ?>

                <table class="form-table">

                    <tr>

                      <th scope="row"><?php echo __('Post Type', 'risbl-cpt-slider-customizr'); ?></th>

                      <td>

                      <select name="risbl_slider_customizr_settings[post_type]">

                      <?php

                      $args = array(
                         'public'   => true
                      );

                      $output = 'names'; // names or objects, note names is the default

                      $post_types = get_post_types( $args, $output );

                      foreach ( $post_types  as $post_type ) {

                          $setting = $options["post_type"];
                          $selected = selected( $setting, $post_type, false );
                          echo '<option vlaue="'.esc_attr($post_type).'" '.$selected.'>' . $post_type . '</option>';

                      }


                      ?>


                      </select>

                      </td>

                    </tr>


										<tr valign="top">
										    <th scope="row"><?php echo __('Order slider', 'risbl-cpt-slider-customizr'); ?></th>
										    <td>


													<div id="risbl_prefix_order_slider">

														<?php

														$saved_order = isset($options['slide_order']) ? $options['slide_order'] : array();

														?>

					                  <table class="widefat" cellspacing="0">
					                    <thead>
				                        <tr>
																	<th class="sort"></th>
				                          <th class="col-image" scope="col" width="10%"><?php echo __('Slide Image', 'risbl-cpt-slider-customizr'); ?></th>
				                          <th class="col-title" scope="col" width="70%"><?php echo __('Title', 'risbl-cpt-slider-customizr'); ?></th>
																	<th class="col-action" scope="col" width="10%"><?php echo __('Action', 'risbl-cpt-slider-customizr'); ?></th>
				                        </tr>
					                    </thead>

					                    <tbody class="ui-sortable">

																<?php

																$options = get_option('risbl_slider_customizr_settings');

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

																		?>

																			<tr>
																				<td class="sort"> = </td>
																				<td>

																					<?php
																					$tc_thumb_size = 'risbl-slider-thumb';
																					$thumb = get_the_post_thumbnail( $post->ID , $tc_thumb_size);
																					echo $thumb;
																					?>

																				</td>
																				<td>

																					<?php echo get_post_meta($post->ID, 'risbl_prefix_title_id', true); ?>
																					<input type="hidden" name="risbl_slider_customizr_settings[slide_order][]" value="<?php echo esc_attr($post->ID); ?>">

																				</td>
																				<td>
																					<a href="<?php echo esc_attr(get_edit_post_link($post->ID)); ?>" target="_blank" title="<?php echo __('Edit', 'risbl-cpt-slider-customizr'); ?>">
																						<?php echo __('Edit / Delete', 'risbl-cpt-slider-customizr'); ?>
																					</a>
																				</td>

																			</tr>

																		<?php

																		$_loop_index++;

																	endwhile; wp_reset_postdata();

																else :

																	echo '<tr>';
																	echo '<td colspan="3" style="padding-left: 10px; padding-right: 10px;">'.__('No slider has been set for this selected post type, yet.', 'risbl-cpt-slider-customizr').'</td>';
																	echo '</tr>';

																endif;



																?>

															</tbody>
														</table>

													</div><!-- /#risbl_prefix_order_slider -->

											  </td>
										</tr>

										<tr valign="top">
										    <th scope="row"><?php echo __('Enable?', 'risbl-cpt-slider-customizr'); ?></th>
										    <td>
													<input type="checkbox" name="risbl_slider_customizr_settings[enable]" value="1" <?php if(isset($options["enable"])){ checked('1', $options["enable"]); } ?> />
													<?php
													echo __('Yes, I want to use the selected post type to replace the default main slider.', 'risbl-cpt-slider-customizr');
													?>
										    </td>
										</tr>



                </table>
                <p class="submit">
    						<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'risbl-cpt-slider-customizr'); ?>" />
                </p>
            </form>
        </div>
    <?php
    }


		/**
     * Update slider order
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
     */
		public function update_slider_order(){

			$options = get_option('risbl_slider_customizr_settings');
			$saved_order = isset($options['slide_order']) ? $options['slide_order'] : array();

			if(isset($_POST)):
				if(is_array($saved_order)): foreach ($saved_order as $key => $val) {

					$index = ($key + 1);
					update_post_meta( $val, 'risbl_slide_order_index', $index );

				}
				endif;
			endif;

		}


  }

endif;

return new Risbl_Prefix_Admin();
