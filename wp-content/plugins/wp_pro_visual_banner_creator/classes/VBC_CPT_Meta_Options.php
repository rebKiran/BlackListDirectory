<?php
class VBC_CPT_Meta_Options {
	
	
	/*
	 * Adds a box to the main column on the Post and Page edit screens.
	 *
	 * @access public
	*/
	public function vbc_banners_meta_options() 
	{
		$screens = array( 'vbc_banners' );
	
		foreach ( $screens as $screen ) 
		{	
			add_meta_box( $screen.'_meta_options_id', sprintf(__( '%s Options:', 'wpproads' ), $screen), array($this, $screen.'_meta_options_custom_box'), $screen, 'normal', 'high' );
		}
	}
	
	
	
	
	
	
	function vbc_banners_meta_options_custom_box( $post ) 
	{
		global $bc_banner_creator;
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'vbc_banners_meta_options_inner_custom_box', 'wp_pro_ads_advertisers_meta_options_inner_custom_box_nonce' );
	
		/*	
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		*/
		//$advertiser_email       = get_post_meta( $post->ID, '_proad_advertiser_email', true );
		//$wpuser_id              = get_post_meta( $post->ID, '_proad_advertiser_wpuser', true );
		?>
		<div class="tuna_meta">
			<table class="form-table">
				<tbody>
		  			<tr valign="top">
                        <tr>
                            <td colspan="2">
                                <h3 style="margin:0 0 10px 0; padding:0 0 10px 0; border-bottom: 1px solid #EEE;"><?php _e('Visual Banner Creator:','wpproads'); ?></h3>
                                <?php echo $bc_banner_creator->show_banner_creator(); ?>
                            </td>
                        </tr>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        <?php
	}
	
	
	
	
	function vbc_banners_meta_options_save_postdata( $post_id ) 
	{
	  /*
	   * We need to verify this came from the our screen and with proper authorization,
	   * because save_post can be triggered at other times.
	   */
	
	  // Check if our nonce is set.
	  if ( ! isset( $_POST['vbc_banners_meta_options_inner_custom_box_nonce'] ) )
		return $post_id;
	
	  $nonce = $_POST['vbc_banners_meta_options_inner_custom_box_nonce'];
	
	  // Verify that the nonce is valid.
	  if ( ! wp_verify_nonce( $nonce, 'vbc_banners_meta_options_inner_custom_box' ) )
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
	  
	  // Check if email exists in our user database.
	  $wpuser = get_user_by( 'email', $_POST['proad_advertiser_email'] );
	  $wpuid = !empty($wpuser) ? $wpuser->ID : '';
	
	  // Sanitize user input.
	  $advertiser_email  = sanitize_text_field( $_POST['proad_advertiser_email'] );
	
	  // Update the meta field in the database.
	  //update_post_meta( $post_id, '_proad_advertiser_email', $advertiser_email );
	  //update_post_meta( $post_id, '_proad_advertiser_wpuser', $wpuid );
	
	}
	
}