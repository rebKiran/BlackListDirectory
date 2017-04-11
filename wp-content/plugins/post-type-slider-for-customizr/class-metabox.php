<?php

/**
 * Create post type metabox fields.
 *
 * Fields will be visible if custom slider is enabled.
 *
 * @package 	Post Type Slider for Customizr
 * @since 		0.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Risbl_Prefix_Metabox' ) ) :


	/**
	 * Post type metabox fields class.
	 *
	 * @class Risbl_Prefix_Metabox
	 * @package 		Post Type Slider for Customizr
	 * @since 			0.1
	 * @author      Kharis Sulistiyono
	 */
  class Risbl_Prefix_Metabox {


		/**
     * Constructor
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
     */
    public function __construct(){

      add_action( '__post_slider_infos', array( $this, 'metadata' ), 999, 1 );
			add_action( 'save_post', array( $this, 'save_slider_data' ) );

    }

		/**
     * Fields key
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
		 * @return array
     */
		public function metadate_field_keys(){

			return array(
				'set_as_slider' 			=> 'risbl_prefix_set_as_slider',
				'title_id'						=> 'risbl_prefix_title_id',
				'text_id'							=> 'risbl_prefix_text_id',
				'color_id'						=> 'risbl_prefix_color_id',
				'color_value'					=> 'risbl_prefix_color_value',
				'button_id'						=> 'risbl_prefix_button_id',
				'link_id'							=> 'risbl_prefix_link_id',
				'custom_link_id'			=> 'risbl_prefix_custom_link_id',
				'link_target_id'			=> 'risbl_prefix_link_target_id',
				'link_whole_slide_id'	=> 'risbl_prefix_link_whole_slide_id'
			);

		}


		/**
     * Save metabox value
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
		 * @return array
     */
		public function save_slider_data($post_id){

			// verify if this is an auto save routine.
			// If it is our form has not been submitted, so we dont want to do anything
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
					return;

			// Check permissions
			if ( isset( $_POST['post_type']) && 'page' == $_POST['post_type'] )
			{
				if ( !current_user_can( 'edit_page' , $post_id ) )
						return;
			}
			else
			{
				if ( !current_user_can( 'edit_post' , $post_id ) )
						return;
			}

			if ( isset( $_POST['post_ID'])) {
	       $post_ID = $_POST['post_ID'];

				 $field_keys = $this->metadate_field_keys();

	 			foreach ($field_keys as $key => $field_key) {

	 				$postdata = (isset($_POST[$field_key])) ? sanitize_text_field( $_POST[$field_key] ) : '';


					$color = ( isset($_POST['risbl_prefix_color_id']) && $_POST['risbl_prefix_color_id'] != '') ? 'style="color:'.$_POST['risbl_prefix_color_id'].'"': '';

					if($key == 'color_value'){
						$postdata	= $color;
					}

	 				update_post_meta( $post_ID, $field_key, sanitize_text_field($postdata));

	 			}

				// Order key
				if( isset($_POST[$field_keys['set_as_slider']]) ):
					$order = get_post_meta( $post_ID, 'risbl_slide_order_index', true);
					if(!isset($order) || $order == ''){
						update_post_meta( $post_ID, 'risbl_slide_order_index', 0);
					}
				endif;


			}


		}


		/**
     * Fields key
		 *
		 * @package 		Post Type Slider for Customizr
		 * @since 			0.1
		 * @param 			$post_id Post ID
		 * @return 			string Metabox fields
     */
    public function metadata($post_id){


      global $post_type;

      $options = get_option('risbl_slider_customizr_settings');
      $selected_cpt = $options["post_type"];

			$is_enabled = risbl_prefix_is_enabled();
			if(!$is_enabled){
			  return;
			}

      if( $post_type != $selected_cpt ){
        return;
      }

			$fields = $this->metadate_field_keys();

      // Option enabled

      $risbl_set_as_slider_item_id = $fields['set_as_slider'];
      $risbl_set_as_slider_item_value = get_post_meta($post_id, $risbl_set_as_slider_item_id, true);


      // Option enabled ends

      $title_id = $fields['title_id'];
      $title_value = get_post_meta($post_id, $title_id, true);


      $text_id = $fields['text_id'];
      $text_value = get_post_meta($post_id, $text_id, true);

      $color_id = $fields['color_id'];
      $color_value = get_post_meta($post_id, $color_id, true);

      $button_id = $fields['button_id'];
      $button_value = get_post_meta($post_id, $button_id, true);

      $link_id = $fields['link_id'];
      $link_value = get_post_meta($post_id, $link_id, true);

      $tc_all_posts = array(); /// ??????

      $custom_link_id = $fields['custom_link_id'];
      $custom_link_value = get_post_meta($post_id, $custom_link_id, true);


      $link_target_id = $fields['link_target_id'];
      $link_target_value = get_post_meta($post_id, $link_target_id, true);


      $link_whole_slide_id = $fields['link_whole_slide_id'];
      $link_whole_slide_value = get_post_meta($post_id, $link_whole_slide_id, true);

      ?>

			<div class="risbl-metabox">

				<hr />

	      <h3><?php _e( 'Set as slider item?' , 'risbl-cpt-slider-customizr' ); ?></h3>

	      <input name="<?php echo $risbl_set_as_slider_item_id; ?>" id="<?php echo $risbl_set_as_slider_item_id; ?>" type="checkbox" class="iphonecheck" value="1" <?php checked( $risbl_set_as_slider_item_value, $current = true, $echo = true ) ?>/>

				<?php
				$css = '';
				if($risbl_set_as_slider_item_value == 1){
					$css = 'style="display: block;"';
				}
				?>

				<div class="risbl-metabox-slider-fields" <?php echo $css; ?>>

		      <div class="meta-box-item-title">
		          <h4><?php _e( 'Title text (80 char. max length)' , 'risbl-cpt-slider-customizr' ); ?></h4>
		      </div>
		      <div class="meta-box-item-content">
		          <input class="widefat" name="<?php echo esc_attr( $title_id); ?>" id="<?php echo esc_attr( $title_id); ?>" value="<?php echo esc_attr( $title_value); ?>" style="width:50%">
		      </div>

		      <div class="meta-box-item-title">
		          <h4><?php _e( 'Description text (below the title, 250 char. max length)' , 'risbl-cpt-slider-customizr' ); ?></h4>
		      </div>
		      <div class="meta-box-item-content">
		          <textarea name="<?php echo esc_attr( $text_id); ?>" id="<?php echo esc_attr( $text_id); ?>" style="width:50%"><?php echo esc_attr( $text_value); ?></textarea>
		      </div>

		       <div class="meta-box-item-title">
		          <h4><?php _e("Title and text color", 'risbl-cpt-slider-customizr' );  ?></h4>
		      </div>
		      <div class="meta-box-item-content">
		          <input id="<?php echo esc_attr( $color_id); ?>" name="<?php echo esc_attr( $color_id); ?>" value="<?php echo esc_attr( $color_value); ?>"/>
		          <div id="colorpicker"></div>
		      </div>

		       <div class="meta-box-item-title">
		          <h4><?php _e( 'Button text (80 char. max length)' , 'risbl-cpt-slider-customizr' ); ?></h4>
		      </div>
		      <div class="meta-box-item-content">
		          <input class="widefat" name="<?php echo esc_attr( $button_id); ?>" id="<?php echo esc_attr( $button_id); ?>" value="<?php echo esc_attr( $button_value); ?>" style="width:50%">
		      </div>

		      <div class="meta-box-item-title">
		          <h4><?php _e("Choose a linked page or post (among the last 100).", 'risbl-cpt-slider-customizr' ); ?></h4>
		      </div>
		      <div class="meta-box-item-content">
		          <select name="<?php echo esc_attr( $link_id); ?>" id="<?php echo esc_attr( $link_id); ?>">
		            <?php //no link option ?>
		            <option value="" <?php selected( $link_value, $current = null, $echo = true ) ?>> <?php _e( 'No link' , 'risbl-cpt-slider-customizr' ); ?></option>
		            <?php foreach( $tc_all_posts as $type) : ?>
		                <?php foreach ( $type as $key => $item) : ?>
		              <option value="<?php echo esc_attr( $item -> ID); ?>" <?php selected( $link_value, $current = $item -> ID, $echo = true ) ?>>{<?php echo esc_attr( $item -> post_type) ;?>}&nbsp;<?php echo esc_attr( $item -> post_title); ?></option>
		                <?php endforeach; ?>
		           <?php endforeach; ?>
		          </select><br />
		      </div>
		      <div class="meta-box-item-title">
		          <h4><?php _e("or a custom link (leave this empty if you already selected a page or post above)", 'risbl-cpt-slider-customizr' ); ?></h4>
		      </div>
		      <div class="meta-box-item-content">
		          <input class="widefat" name="<?php echo $custom_link_id; ?>" id="<?php echo $custom_link_id; ?>" value="<?php echo $custom_link_value; ?>" style="width:50%">
		      </div>
		      <div class="meta-box-item-title">
		          <h4><?php _e("Open link in a new page/tab", 'risbl-cpt-slider-customizr' );  ?></h4>
		      </div>
		      <div class="meta-box-item-content">
		          <input name="<?php echo $link_target_id; ?>" type="hidden" value="0"/>
		          <input name="<?php echo $link_target_id; ?>" id="<?php echo $link_target_id; ?>" type="checkbox" class="iphonecheck" value="1" <?php checked( $link_target_value, $current = true, $echo = true ) ?>/>
		      </div>
		      <div class="meta-box-item-title">
		          <h4><?php _e("Link the whole slide", 'risbl-cpt-slider-customizr' );  ?></h4>
		      </div>
		      <div class="meta-box-item-content">
		          <input name="<?php echo $link_whole_slide_id; ?>" type="hidden" value="0"/>
		          <input name="<?php echo $link_whole_slide_id; ?>" id="<?php echo $link_whole_slide_id; ?>" type="checkbox" class="iphonecheck" value="1" <?php checked( $link_whole_slide_value, $current = true, $echo = true ) ?>/>
		      </div>

				</div><!-- /.risbl-metabox-slider-fields -->

			</div><!-- /.risbl-metabox -->

      <?php

    }

  }

endif;

return new Risbl_Prefix_Metabox();
