<?php


/*
 * AJAX REQUEST FUNCTIONS
 *
 * http://codex.wordpress.org/AJAX_in_Plugins
 * For not logged-in users use: add_action('wp_ajax_nopriv_my_action', 'my_action_callback');
*/




/* -------------------------------------------------------------
 * Save Banner - Visual Banner Creator
 * ------------------------------------------------------------- */
add_action('wp_ajax_bc_save_banner', 'bc_save_banner_callback');
add_action('wp_ajax_nopriv_bc_save_banner', 'bc_save_banner_callback');
function bc_save_banner_callback() 
{
	if( isset($_POST['banner_id']) && !empty( $_POST['banner_id'] ))
	{
		$data_uri = !empty($_POST['data_uri']) ? $_POST['data_uri'] : '';
		$json = !empty($_POST['json']) ? base64_encode(stripslashes($_POST['json'])) : '';
		
		update_post_meta( $_POST['banner_id'], '_banner_bc_data_uri', $data_uri );
		update_post_meta( $_POST['banner_id'], '_banner_bc_json', $json );
		$price = !empty($_POST['price']) ? $_POST['price'] : '';
		/* $post_title = !empty($_POST['postTitle']) ? $_POST['postTitle'] : '';*/
		
		if( empty( $json ))
		{
			update_post_meta( $_POST['banner_id'], '_banner_bc_size', '' );
		}
		
	}
	else
	{
		$data_uri = !empty($_POST['data_uri']) ? $_POST['data_uri'] : '';
		$json = !empty($_POST['json']) ? base64_encode(stripslashes($_POST['json'])) : '';
	}
	
	global $user_ID, $wpdb, $current_user;

	get_currentuserinfo();
	
    $query = $wpdb->prepare(
        'SELECT ID FROM ' . $wpdb->posts . '  WHERE ID = '. $_POST['banner_id']
    );
    $wpdb->query( $query );

    if ( $wpdb->num_rows > 0 ) {
		
		/*$my_post = array(
			'ID'           => $_POST['banner_id'],
 		   'post_title'   => $post_title
 	   );

		// Update the post into the database
		wp_update_post( $my_post );*/
        
    } else {
		  
		
       /* $new_post = array(
            'post_title' => $post_title,
			   'post_author' => $user_ID,
            'post_content' => '',
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s'),
            'comment_status' => 'closed',
			   'ping_status' => 'closed',
            'post_type' => 'post',
            'post_category' => array(0)
        );

        $post_id = wp_insert_post($new_post);
			//add_post_meta( $post_id, 'post_price', '' ); 
        //add_post_meta($post_id, 'post_price', $price );
			add_post_meta($_POST['banner_id'], '_regular_price', $price );
			add_post_meta($_POST['banner_id'], '_sale_price', $price );
		*/
    }

	// Save file Filter
	$data = apply_filters( 'vbc_save_banner_design', array( 'banner_id' => $_POST['banner_id'], 'data_uri' => $data_uri, 'json' => $json) );
	
	echo json_encode( $data );
	
	exit;
}

add_action('wp_ajax_bc_save_banner_img', 'bc_save_banner_img_callback');
add_action('wp_ajax_nopriv_bc_save_banner_img', 'bc_save_banner_img_callback');
function bc_save_banner_img_callback() 
{
		$wp_session = WP_Session::get_instance();
		$data = json_decode($wp_session->json_out() );
              
                $data_uri = !empty($_POST['data_uri']) ? $_POST['data_uri'] : '';
		$json = !empty($_POST['json']) ? base64_encode(stripslashes($_POST['json'])) : '';
		$banner_id = !empty($_POST['banner_id']) ? $_POST['banner_id']: '';
		
		
		$wp_session['banner_id'] = $banner_id; 
		$wp_session['data_uri'] = $data_uri; 
		$wp_session['json'] = $json; 
		
		
		global $user_ID, $wpdb, $current_user;

		get_currentuserinfo();
		
		$upload_dir = wp_upload_dir();
		$filename = 'wpproads_banner_'.time().'.png';

		$img = $data_uri;
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);

		file_put_contents($upload_dir['path'].'/'.$filename, $data);
	
		// Check the type of tile. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $upload_dir['path'].'/'.$filename ), null );
	
		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $upload_dir['url'] . '/' . basename( $upload_dir['path'].'/'.$filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload_dir['path'].'/'.$filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
	
		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'].'/'.$filename );
		
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		
		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_dir['path'].'/'.$filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		
		if( isset($attach_id) && !empty($attach_id)) {

			$wp_session = WP_Session::get_instance();
		        $data = json_decode($wp_session->json_out() );			
			$wp_session['post_id'] = $attach_id; 
			

			$wpdb->query("UPDATE 
								wp_questionnaire 
							SET 
								post_id='".$attach_id."',
                                                                image='".$data_uri."',
                                                                banner_url= '". $upload_dir['url'].'/'.$filename ."'
								
							WHERE 
								number='".$data->number ."' "); 
	
								
		}
        // Uploaded file Filter
	$filter = apply_filters( 'vbc_upload_banner', $upload_dir['url'], $filename, $_POST['banner_id'] );
										
       echo json_encode( array( 'attach_id' => $attach_id, 'url' => $upload_dir['url'].'/'.$filename) );
       exit;

}

/* -------------------------------------------------------------
 * Export Banner as PNG - Visual Banner Creator
 * ------------------------------------------------------------- */
add_action('wp_ajax_bc_export_image', 'bc_export_image_callback');
add_action('wp_ajax_nopriv_bc_export_image', 'bc_export_image_callback');
function bc_export_image_callback() 
{
		$upload_dir = wp_upload_dir();
		$filename = 'wpproads_banner_'.time().'.png';
		
		$img = $_POST['img'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
	
		file_put_contents($upload_dir['path'].'/'.$filename, $data);
	
		// Check the type of tile. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $upload_dir['path'].'/'.$filename ), null );
	
		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $upload_dir['url'] . '/' . basename( $upload_dir['path'].'/'.$filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload_dir['path'].'/'.$filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
	
	// Insert the attachment.
	$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'].'/'.$filename );
	
	// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	
	// Generate the metadata for the attachment, and update the database record.
	$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_dir['path'].'/'.$filename );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	//
	
	if( isset($_POST['banner_id']) && !empty($_POST['banner_id']))
	{
		update_post_meta( $_POST['banner_id'], '_banner_url', $upload_dir['url'].'/'.$filename);
	}
	
	echo json_encode( array( 'url' => $upload_dir['url'].'/'.$filename) );

	exit;
}






/* -------------------------------------------------------------
 * Save Banner Size
 * ------------------------------------------------------------- */
add_action('wp_ajax_bc_save_banner_size', 'bc_save_banner_size_callback');
add_action('wp_ajax_nopriv_bc_save_banner_size', 'bc_save_banner_size_callback');
function bc_save_banner_size_callback() 
{  
	if( isset($_POST['banner_id']) && !empty( $_POST['banner_id'] ))
	{
		$size = $_POST['width'].'x'.$_POST['height'];
		update_post_meta( $_POST['banner_id'], '_banner_bc_size', $size );
		
		echo $size;
	}
	
	exit;
}

/* -------------------------------------------------------------
 * show Banner Price
 * ------------------------------------------------------------- */
add_action('wp_ajax_bc_banner_price', 'bc_banner_price_callback');
add_action('wp_ajax_nopriv_bc_banner_price', 'bc_banner_price_callback');
function bc_banner_price_callback() 
{
		
	
	if( isset($_POST['banner_id']) && !empty( $_POST['banner_id'] ))
	{
		global $wpdb;
		$banner_size	= ( isset($_POST['banner_size']) ) ? $_POST['banner_size'] : '';
		$banner_size	= trim($banner_size);
		$banner_size   = str_replace("x","*",$banner_size);
		
	       $my_query = "SELECT product_id from wp_product_banner where size ='" . $banner_size ."'";
					
		$results = $wpdb->get_results($my_query);
		
		
		if(!empty($results)) {
			 $product_id = $results[0]->product_id;
		}
		
		$query = "SELECT postmeta.meta_value AS price
						FROM {$wpdb->prefix}postmeta AS postmeta
					   JOIN {$wpdb->prefix}posts AS products ON ( products.ID = postmeta.post_id )
						WHERE postmeta.meta_key = '_sale_price'
						AND products.ID = " . $product_id;
		
		$results_price = $wpdb->get_results($query);

		/*header('Content-Type: application/json; charset=utf-8');*/

               echo json_encode( array( 'id' => $product_id, 'price' => $results_price[0]->price ) );

	      exit;
		
	}
        	
}

/* -------------------------------------------------------------
 * show Banner Price
 * ------------------------------------------------------------- */
add_action('wp_ajax_bc_add_to_listing', 'bc_add_to_listing_callback');
add_action('wp_ajax_nopriv_bc_add_to_listing', 'bc_add_to_listing_callback');
function bc_add_to_listing_callback() 
{ 
	//session_start();
	if( isset($_POST['id']) && !empty( $_POST['id'] ))
	{
	
		$product = isset($_POST['id']) ? $_POST['id'] : 0;
		$price	 = isset($_POST['price']) ? $_POST['price'] : 0;
		// One value
		
		//if(!isset($_SESSION['product'])){
			$wp_session = WP_Session::get_instance();
			$wp_session['product'] = $product; 
			$wp_session['price'] = $price; 
			$data_uri = !empty($_POST['data_uri']) ? $_POST['data_uri'] : '';
			$json = !empty($_POST['json']) ? base64_encode(stripslashes($_POST['json'])) : '';
			$banner_id = !empty($_POST['banner_id']) ? $_POST['banner_id']: '';
			$banner_url = !empty($_POST['banner_url']) ? $_POST['banner_url']: '';
			
			$wp_session['banner_id'] = $banner_id; 
			$wp_session['data_uri'] = $data_uri; 
			$wp_session['json'] = $json; 
			$wp_session['banner_url'] = $banner_url; 
			
		//}
		
	}
	
}

/* -------------------------------------------------------------
 * show Banner Price
 * ------------------------------------------------------------- */
add_action('wp_ajax_bc_add_to_cart', 'bc_add_to_cart_callback');
add_action('wp_ajax_nopriv_bc_add_to_cart', 'bc_add_to_cart_callback');
function bc_add_to_cart_callback() 
{ 
	//session_start();
	if( isset($_POST['id']) && !empty( $_POST['id'] ))
	{
	
		global $wpdb;
		
		$product = isset($_POST['id']) ? $_POST['id'] : 0;
		$price	 = isset($_POST['price']) ? $_POST['price'] : 0;
		// One value
		
		//if(!isset($_SESSION['product'])){
			$wp_session = WP_Session::get_instance();
			$data = json_decode($wp_session->json_out() );
			$wp_session['product'] = $product; 
			$wp_session['price'] = $price; 
			$data_uri = !empty($_POST['data_uri']) ? $_POST['data_uri'] : '';
			$json = !empty($_POST['json']) ? base64_encode(stripslashes($_POST['json'])) : '';
			$banner_id = !empty($_POST['banner_id']) ? $_POST['banner_id']: '';
			$banner_url = !empty($_POST['banner_url']) ? $_POST['banner_url']: '';
			
			$wp_session['banner_id'] = $banner_id; 
			$wp_session['data_uri'] = $data_uri; 
			$wp_session['json'] = $json; 
			$wp_session['banner_url'] = $banner_url; 
			
			if( $wpdb->query("UPDATE 
									wp_questionnaire 
								SET 
									banner_id='".$banner_id."', 
									image='".$data_uri."',
									banner_url= '". $banner_url ."',
									product_id='". $product ."',
									price='". $price ."'
								WHERE 
									number='".$data->number ."' ") ) {
										echo json_encode( array( 'id' => $product, 'price' => $price ) );
									}
			
		//}
		
	}
	exit;
}



/* -------------------------------------------------------------
 * Base64 Decode - Visual Banner Creator
 * ------------------------------------------------------------- */
add_action('wp_ajax_vbc_base64_decode', 'vbc_base64_decode_callback');
add_action('wp_ajax_nopriv_vbc_base64_decode', 'vbc_base64_decode_callback');
function vbc_base64_decode_callback() 
{
	if( isset($_POST['svg']) && !empty( $_POST['svg'] ))
	{
		echo base64_decode(json_decode(stripslashes($_POST['svg'])));
	}
	
	exit;
}
?>