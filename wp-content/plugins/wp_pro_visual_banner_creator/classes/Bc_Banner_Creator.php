<?php
class Bc_Banner_Creator {	

	public function __construct() 
	{
		// Actions --------------------------------------------------------
		add_action( 'add_meta_boxes', array($this, 'bc_meta_options'));
		add_action( 'save_post', array($this, 'pro_ads_bc_meta_options_save_postdata' ));
	}
	
	

	
	
	
	
	
	
	/*
	 * Show image editor
	 *
	 * @access public
	 * @return array
	*/
	public function show_banner_creator( $args = array() )
	{
		global $wpdb, $post, $bc_templates;
		
		
		$banner_bc_data_uri        = get_post_meta( $post->ID, '_banner_bc_data_uri', true );
		$banner_bc_json            = base64_decode(get_post_meta( $post->ID, '_banner_bc_json', true ));
		$banner_bc_size            = get_post_meta( $post->ID, '_banner_bc_size', true );

		$bc_size = explode('x', $banner_bc_size);
		$post_id = get_post_type($post->ID) == 'banners' || get_post_type($post->ID) == 'vbc_banners' ? $post->ID : 0;
                
		$defaults = array(
			'frontend'       => 0,
			'image_upload'   => 1,
			'post_id'        => $post_id,
			'data_uri'       => $banner_bc_data_uri,
			'json'           => $banner_bc_json,
			'json_64'        => '',
			'size'           => $banner_bc_size,
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$banner_import_data = !empty($banner_bc_json) ? $banner_bc_json : base64_decode($args['json_64']);
		
		$html = '';
		
		$html.= '<div class="vbc_container">';
                        
                        $html = '<div  class="canvas-border-div">';
			$html.= $bc_templates->banner_creator_canvas_area( $args );
			$html.= $bc_templates->banner_creator_sidebar_area( $args );
                       // $html.= '</div>';
                       // $html.= '</div>';

			$html.= '<div class="clearFix"></div>';
		
			$html.= '<script type="text/javascript">';
			//$html.= 'var canvas = this.__canvas = new fabric.Canvas(\'bcCanvas\');';
				$html.= 'jQuery(document).ready(function($){';
					if( !empty($banner_import_data ) )
					{
					
						//IMPORT BANNER ELEMENTS'
						$html.= 'var json_import = '.$banner_import_data.';';
						$html.= 'vbc_json_import( json_import );';
					
					
					}

					$html.= !empty( $banner_bc_size ) ? 'bc_canvas_update_size('.$bc_size[0].', '.$bc_size[1].');' : '';    //canvas.setHeight('.$bc_size[1].'); canvas.setWidth('.$bc_size[0].')
				$html.= '});';
			$html.= '</script>';
		$html.= '</div>';
		
		return $html;		
	}
	
	
	
	
	
	
	
	/*
	 * Adds a box to the main column on the Post and Page edit screens.
	 *
	 * @access public
	*/
	public function bc_meta_options() 
	{
		$screens = array( 'banners' );
	
		foreach ( $screens as $screen ) 
		{	
			//add_meta_box( 'pro_ads_bc_meta_options_id', __( 'Visual Banner Creator:', 'wpproads' ), array($this, 'pro_ads_bc_meta_options_custom_box'), $screen, 'normal', 'default' );
			
		}
	}
	
	
	
	
	
	function pro_ads_bc_meta_options_custom_box( $post ) 
	{
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pro_ads_bc_meta_options_inner_custom_box', 'pro_ads_bc_meta_options_inner_custom_box_nonce' );
		
		if( $post->ID )
		{
			?>
			<div class="tuna_meta">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<td>
								<?php $this->show_banner_creator(); ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
		}
		else
		{
			_e('You need to save the banner before you can use the Visual Banner Creator.','wpproads');	
		}
	}
	
	
	
	
	
	
	function pro_ads_bc_meta_options_save_postdata( $post_id ) 
	{
		// Check if our nonce is set.
		if ( ! isset( $_POST['pro_ads_bc_meta_options_inner_custom_box_nonce'] ) )
		return $post_id;
		$nonce = $_POST['pro_ads_bc_meta_options_inner_custom_box_nonce'];
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'pro_ads_bc_meta_options_inner_custom_box' ) )
		  return $post_id;
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		  return $post_id;
		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		} else {
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
		}
		/* OK, its safe for us to save the data now. */
			
		/*// Sanitize user input.
		$adzone_visualbannercreator_contract         = sanitize_text_field( $_POST['adzone_visualbannercreator_contract'] );
		$adzone_visualbannercreator_duration         = sanitize_text_field( $_POST['adzone_visualbannercreator_duration'] );
		$adzone_visualbannercreator_price            = sanitize_text_field( $_POST['adzone_visualbannercreator_price'] );
		$adzone_visualbannercreator_est_impressions  = sanitize_text_field( $_POST['adzone_visualbannercreator_est_impressions'] );
		
		// Update the meta field in the database.
		update_post_meta( $post_id, 'adzone_visualbannercreator_contract', $adzone_visualbannercreator_contract );
		update_post_meta( $post_id, 'adzone_visualbannercreator_duration', $adzone_visualbannercreator_duration );
		update_post_meta( $post_id, 'adzone_visualbannercreator_price', $adzone_visualbannercreator_price );
		update_post_meta( $post_id, 'adzone_visualbannercreator_est_impressions', $adzone_visualbannercreator_est_impressions );*/
	}
	
}
?>