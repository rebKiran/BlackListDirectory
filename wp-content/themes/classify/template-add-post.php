<?php
/**
 * Template name: New Ad Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */

if ( !is_user_logged_in() ) {

	global $redux_demo; 
	$login = $redux_demo['login'];
	wp_redirect( $login ); exit;	
}
global $redux_demo;
$trns_account_overview = $redux_demo['trns_account_overview'];
$trns_ragular_ads = $redux_demo['trns_ragular_ads'];
$trns_featured_ads = $redux_demo['trns_featured_ads'];
$trns_featured_ads_left = $redux_demo['trns_featured_ads_left'];
$classifyLatitude = $redux_demo['contact-latitude'];
$classifyLongitude = $redux_demo['contact-longitude'];
$classifyGoogleValue = $redux_demo['classify-google-lat-long'];
$classifyGoogleMAP = $redux_demo['classify-google-map-adpost'];

$postTitleError = '';
$post_priceError = '';
$catError = '';
$featPlanMesage = '';
$postContent = '';

if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

	if(trim($_POST['postTitle']) === '') {
		$postTitleError = esc_html__( 'Please enter a title.', 'classify' );
		$hasError = true;
	} else {
		$postTitle = trim($_POST['postTitle']);
	} 

	if(trim($_POST['cat']) === '-1') {
		$catError = esc_html__( 'Please select a category.', 'classify' );
		$hasError = true;
	} 



	if($hasError != true  && (!empty($_POST['edit-feature-plan']) || isset($_POST['regular-ads-status']))) {
		if(is_super_admin() ){
			$postStatus = 'publish';
		}elseif(!is_super_admin()){
			
			if($redux_demo['post-options-on'] == 1){
				$postStatus = 'pending';
			}else{
				$postStatus = 'publish';
			}
			}

		$post_information = array(
			'post_title' => esc_attr(strip_tags($_POST['postTitle'])),
			'post_content' => strip_tags($_POST['postContent'], '<a><h1><h2><h3><strong><b>'),
			'post-type' => 'post',
			'post_category' => array($_POST['cat']),
	        'tags_input'    => explode(',', $_POST['post_tags']),
	        'comment_status' => 'open',
	        'ping_status' => 'open',
			'post_status' => $postStatus
		);
		
		
		$post_id = wp_insert_post($post_information);

		$post_price_status = trim($_POST['post_price']);

		global $redux_demo; 
		$free_listing_tag = $redux_demo['free_price_text'];

		if(empty($post_price_status)) {
			$post_price_content = $free_listing_tag;
		} else {
			$post_price_content = $post_price_status;
		}
		$postlatitude = $_POST['latitude'];
		if(empty($postlatitude)){
			$latitude = $classifyLatitude;
		}else{
			$latitude = $postlatitude;
		}
		$postlongitude = $_POST['longitude'];
		if(empty($postlongitude)){
			$longitude = $classifyLongitude;
		}else{
			$longitude = $postlongitude;
		}
		
		$catID = $_POST['cat'].'custom_field';
		$custom_fields = $_POST[$catID];
		update_post_meta($post_id, 'post_category_type', esc_attr( $_POST['post_category_type'] ) );
		update_post_meta($post_id, 'post_video', $_POST['post_video'], $allowed);
		update_post_meta($post_id, 'custom_field', $custom_fields);
		update_post_meta($post_id, 'post_price', $post_price_content, $allowed);
		update_post_meta($post_id, 'post_location', wp_kses($_POST['post_location'], $allowed));
		update_post_meta($post_id, 'post_latitude', wp_kses($latitude, $allowed));
		update_post_meta($post_id, 'post_longitude', wp_kses($longitude, $allowed));
		update_post_meta($post_id, 'post_address', wp_kses($_POST['address'], $allowed));
		
		
		
		$permalink = get_permalink( $post_id );


		if(trim($_POST['edit-feature-plan']) != '') {

			$featurePlanID = trim($_POST['edit-feature-plan']);

			global $wpdb;

			global $current_user;
		    wp_get_current_user();

		    $userID = $current_user->ID;

			$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal WHERE main_id = $featurePlanID" );

			if ( $result ) {

				$featuredADS = 0;

				foreach ( $result as $info ) { 
					if($info->status != "in progress" && $info->status != "pending" && $info->status != "cancelled") {
																
						$featuredADS++;

						if(empty($info->ads)) {
							$availableADS = esc_html__( 'Unlimited', 'classify' );
							$infoAds = esc_html__( 'Unlimited', 'classify' );
						} else {
							$availableADS = $info->ads - $info->used;
							$infoAds = $info->ads;
						} 

						if(empty($info->days)) {
							$infoDays = esc_html__( 'Unlimited', 'classify' );
						} else {
							$infoDays = $info->days;
						} 

						if($info->used != "Unlimited" && $infoAds != "Ulimited" && $info->used == $infoAds) {

							$featPlanMesage = esc_html__( 'Please select another plan.', 'classify' );

						} else {

							global $wpdb;

							$newUsed = $info->used +1;

							$update_data = array('used' => $newUsed);
						    $where = array('main_id' => $featurePlanID);
						    $update_format = array('%s');
						    $wpdb->update('wpcads_paypal', $update_data, $where, $update_format);
						    update_post_meta($post_id, 'post_price_plan_id', $featurePlanID );

							$dateActivation = date('m/d/Y H:i:s');
							update_post_meta($post_id, 'post_price_plan_activation_date', $dateActivation );
							
							$daysToExpire = $infoDays;
							$dateExpiration_Normal = date("m/d/Y H:i:s", strtotime("+ ".$daysToExpire." days"));
							update_post_meta($post_id, 'post_price_plan_expiration_date_normal', $dateExpiration_Normal );

							$dateExpiration = strtotime(date("m/d/Y H:i:s", strtotime("+ ".$daysToExpire." days")));
							update_post_meta($post_id, 'post_price_plan_expiration_date', $dateExpiration );

							update_post_meta($post_id, 'featured_post', "1" );

					    }
					}
				}
			}

		}


		if ( $_FILES ) {
			$files = $_FILES['upload_attachment'];
			foreach ($files['name'] as $key => $value) {
				if ($files['name'][$key]) {
					$file = array(
						'name'     => $files['name'][$key],
						'type'     => $files['type'][$key],
						'tmp_name' => $files['tmp_name'][$key],
						'error'    => $files['error'][$key],
						'size'     => $files['size'][$key]
					);
		 
					$_FILES = array("upload_attachment" => $file);
		 
					foreach ($_FILES as $file => $array) {
						$newupload = wpcads_insert_attachment($file,$post_id);
					}
				}
			}
		}
		
		wp_redirect( $permalink ); exit;

	}
			$featured_plans = $redux_demo['featured_plans'];
			if(empty($_POST['edit-feature-plan']) && !isset($_POST['regular-ads-status'])) {
				if(!empty($featured_plans)) {
					wp_redirect( $featured_plans ); exit;
				}
			}
} 

get_header(); ?>
	
	<?php while ( have_posts() ) : the_post(); ?>
	
	<div class="ad-title">
	
        		<h2><?php the_title(); ?></h2> 	
	</div>


    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span8 first ad-post-main">
			<div class="account-overview clearfix">
				
				<div class="span6">
					<div class="span3"><h3 style="margin-top: 7px;"><?php echo $trns_account_overview ?></h3></div>
						<span class="ad-detail-info"><?php echo $trns_ragular_ads; ?>
						<span class="ad-detail"><?php echo $user_post_count = count_user_posts( $user_ID ); ?></span>
					</span>

					<?php 

						global $redux_demo; 
						$regular_ads_status = $redux_demo['regular-free-ads'];
						$featured_ads_option = $redux_demo['featured-options-on'];

					?>

					<?php if($featured_ads_option == 1) { ?>

					<?php

						global $paged, $wp_query, $wp;

						$args = wp_parse_args($wp->matched_query);

						$temp = $wp_query;

						$wp_query= null;

						$wp_query = new WP_Query();

						$wp_query->query('post_type=post&posts_per_page=-1&author='.$user_ID);

						$FeaturedAdsCount = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); 

						$featured_post = "0";

						$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
						$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
						$todayDate = strtotime(date('d/m/Y H:i:s'));
						$expireDate = strtotime($post_price_plan_expiration_date);  

						if(!empty($post_price_plan_activation_date)) {

							if(($todayDate < $expireDate) or empty($post_price_plan_expiration_date)) {
								$featured_post = "1";
							}

					} ?>

						<?php if($featured_post == "1") { $FeaturedAdsCount++; } ?>
						<?php endwhile; ?>
						<?php $wp_query = null; $wp_query = $temp;?>

						<span class="ad-detail-info"><?php echo $trns_featured_ads; ?>
							<span class="ad-detail"><?php echo $FeaturedAdsCount ?></span>
						</span>
					 <?php
					// set the meta_key to the appropriate custom field meta key
						
						global $wpdb,  $redux_demo;
						$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal WHERE user_id = " . $current_user->ID." ORDER BY main_id DESC" );

						if ( $result ) {

							$featuredADS = 0;

							foreach ( $result as $info ) { 
								if($info->status != "in progress" && $info->status != "pending" && $info->status != "failde") {										
									$featuredADS++;

									if(empty($info->ads)) {
										$availableADS = esc_html__( 'Unlimited', 'classify' );
										$infoAds = esc_html__( 'Unlimited', 'classify' );
									} else {
										$availableADS = $info->ads - $info->used;
										$infoAds = $info->ads;
									}?>

									<span class="ad-detail-info"><?php echo $trns_featured_ads_left; ?>
										<span class="ad-detail"><?php  echo $availableADS; ?></span>
									</span>

									<?php 
								}else{
									if($featuredADS == 0){
									?>
									<span class="ad-detail-info"><?php _e( 'Featured Ads left', 'classify' ); ?>
									<span class="ad-detail">0</span>
									</span>
									<?php
									} 
									$featuredADS++;
								}
							}
						}else{
					?>
					<span class="ad-detail-info"><?php echo $trns_featured_ads_left; ?>
					<span class="ad-detail">0</span>
					</span>
					<?php } ?>
					
					<?php } ?>
				</div>
				<div class="span2 author-avatar-edit-post">
					<?php $profile = $redux_demo['profile']; ?>
					<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); global $userdata; wp_get_current_user(); 
							$user_ID = $userdata->ID;
								
								$author_avatar_url = get_user_meta($user_ID, "classify_author_avatar_url", true); 

								if(!empty($author_avatar_url)) {

									$params = array( 'width' => 120, 'height' => 120, 'crop' => true );

									echo "<img class='author-avatar' src='" . bfi_thumb( "$author_avatar_url", $params ) . "' alt='' />";

								} else { 
									 echo get_avatar($user_ID, 120);
								}
								
								?>
					<span class="author-profile-ad-details"><a href="<?php echo $profile; ?>" class="button-ag large green"><span class="button-inner"><?php echo get_the_author_meta('display_name', $user_ID ); ?></span></a></span>
				</div>
			</div>

				<div id="upload-ad" class="ad-detail-content">

					<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

						<?php if($postTitleError != '') { ?>
							<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $postTitleError; ?></span>
							<div class="clearfix"></div>
						<?php } ?>


						<?php if($catError != '') { ?>
							<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $catError; ?></span>
							<div class="clearfix"></div>
						<?php } ?>

						
							
							<input type="text" id="postTitle" name="postTitle" value="" placeholder="<?php esc_html_e('Title', 'classify') ?>" class="form-text required input-textarea half" required>

							<?php wp_dropdown_categories( 'show_option_none=Category&hide_empty=0&hierarchical=1&orderby=name&id=catID' ); ?>

							

						<?php
				        	$args = array(
				        	  'hide_empty' => false,
							  'orderby' => 'count',
							  'order' => 'ASC'
							);

							$inum = 0;

							$categories = get_categories($args);
							  	foreach($categories as $category) {;

							  	$inum++;

				          		$user_name = $category->name;
				          		$user_id = $category->term_id; 


				          		$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
								$wpcrown_category_custom_field_option = $tag_extra_fields[$user_id]['category_custom_fields'];
								$wpcrown_category_custom_field_type = $tag_extra_fields[$user_id]['category_custom_fields_type'];

								if(empty($wpcrown_category_custom_field_option)) {

									$catobject = get_category($user_id,false);
									$parentcat = $catobject->category_parent;

									$wpcrown_category_custom_field_option = $tag_extra_fields[$parentcat]['category_custom_fields'];
									$wpcrown_category_custom_field_type = $tag_extra_fields[$parentcat]['category_custom_fields_type'];
									
								}
				          	?>

				          	<div id="cat-<?php echo $user_id; ?>" class="wrap-content custom_fielder" style="display: none;">
								<!--textfields-->
				             	<?php 
				            for ($i = 0; $i < (count($wpcrown_category_custom_field_option)); $i++) {
				              	if($wpcrown_category_custom_field_type[$i][1] == 'text'){?>
									<input type="hidden" class="custom_field" id="custom_field[<?php echo $i; ?>][0]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][0]" value="<?php echo $wpcrown_category_custom_field_option[$i][0] ?>" size="12">
									<input type="text" class="custom_field custom_field_visible input-textarea" id="custom_field[<?php echo $i; ?>][1]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][1]" onfocus="if(this.value=='<?php if (!empty($wpcrown_category_custom_field_option[$i][0])) echo $wpcrown_category_custom_field_option[$i][0]; ?>')this.value='';" onblur="if(this.value=='')this.value='<?php if (!empty($wpcrown_category_custom_field_option[$i][0])) echo $wpcrown_category_custom_field_option[$i][0]; ?>';" value="<?php if (!empty($wpcrown_category_custom_field_option[$i][0])) echo $wpcrown_category_custom_field_option[$i][0]; ?>" size="12">
								<?php }
				            }
				            ?>
							<!--textfields-->
							<div class="clearfix"></div>
							<!--dropdown-->
							<?php for ($i = 0; $i < (count($wpcrown_category_custom_field_option)); $i++) {?>
								<?php if($wpcrown_category_custom_field_type[$i][1] == 'dropdown'){?>
								<input type="hidden" class="custom_field" id="custom_field[<?php echo $i; ?>][0]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][0]" value="<?php echo $wpcrown_category_custom_field_option[$i][0] ?>" size="12">
								<input type="hidden" class="custom_field" id="custom_field[<?php echo $i; ?>][2]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][2]" value="<?php echo $wpcrown_category_custom_field_type[$i][1] ?>" size="12">
								<select class="optional_select classify-select" id="custom_field[<?php echo $i; ?>][1]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][1]" >
											<?php
									echo '<option>'.$wpcrown_category_custom_field_option[$i][0].'</option>';
									$options = $wpcrown_category_custom_field_type[$i][2];
									$optionsarray = explode(',',$options);
									foreach($optionsarray as $option){
										echo '<option value="'.$option.'">'.$option.'</option>';
									}
									?>
									
								</select>
								<?php }?>	
							<?php }?>		
							<!--dropdown-->
							<div class="clearfix"></div>
							<!--checkbox-->
							<div class="classifySpace">
							<?php for ($i = 0; $i < (count($wpcrown_category_custom_field_option)); $i++) {?>
								<?php if($wpcrown_category_custom_field_type[$i][1] == 'checkbox'){?>
								<input type="hidden" class="custom_field" id="custom_field[<?php echo $i; ?>][0]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][0]" value="<?php echo $wpcrown_category_custom_field_option[$i][0] ?>" size="12">
								<input type="hidden" class="custom_field" id="custom_field[<?php echo $i; ?>][2]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][2]" value="<?php echo $wpcrown_category_custom_field_type[$i][1] ?>" size="12">
								<div class="classify-checkbox">
								<div class="featurehide featurehide<?php echo $i; ?>">
								<?php esc_html_e('Select Features', 'classify') ?>
								</div>
									<input type="checkbox" class="custom_field_visible newcehckbox" id="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][1]" name="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][1]">
									<label for="<?php echo $user_id; ?>custom_field[<?php echo $i; ?>][1]" class="newcehcklabel"><?php echo $wpcrown_category_custom_field_option[$i][0]; ?></label>
								</div>
								<?php }?>
							<?php }?>	
							<!--checkbox-->
							</div>
				            </div>

				      	<?php } ?>

						
						<div class="clearfix"></div>
							
							<input type="text" id="post_price" name="post_price" placeholder="<?php esc_html_e('Price', 'classify') ?>" value="" class="form-text required input-textarea half" required>
								<?php
								$locations= $redux_demo['locations'];
								if(!empty($locations)){
								echo '<select name="post_location" id="post_location" >';
								echo '<option value="Not Provided">Select Location</option>';
									$comma_separated = explode(",", $locations);
									foreach($comma_separated as $comma){
										echo '<option>'.$comma.'</option>';
									}
								echo '</select>';
								}else{
							?>
							<input type="text" id="post_location" name="post_location" placeholder="<?php esc_html_e('Location', 'classify') ?>" value="" class="form-text required input-textarea half" style="float:right" required="required"> <?php } ?>


						
						<?php 
								
							$settings = array(
								'wpautop' => true,
								'postContent' => 'content',
								'media_buttons' => false,
								'tinymce' => array(
									'theme_advanced_buttons1' => 'bold,italic,underline,blockquote,separator,strikethrough,bullist,numlist,justifyleft,justifycenter,justifyright,undo,redo,link,unlink,fullscreen',
									'theme_advanced_buttons2' => 'pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,undo,redo',
									'theme_advanced_buttons3' => '',
									'theme_advanced_buttons4' => ''
								),
								'quicktags' => array(
									'buttons' => 'b,i,ul,ol,li,link,close'
								)
							);
									
							wp_editor( $postContent, 'postContent', $settings );

						?>

						
						
						<div id="map-container">

							<input id="address" name="address" type="textbox" placeholder="<?php esc_html_e('Address', 'classify') ?>" value="" class="input-textarea half" required>
							<input type="text" id="post_tags" name="post_tags" placeholder="<?php esc_html_e('Tags', 'classify') ?>" value="" class="form-text required input-textarea half" style="float:right" required>

							<p class="help-block"><?php _e('Start typing an address and select from the dropdown.', 'classify') ?></p>
						<?php if($classifyGoogleMAP == 1){?>
						    <div id="map-canvas"></div>

						    <script type="text/javascript">

								jQuery(document).ready(function($) {

									var geocoder;
									var map;
									var marker;

									var geocoder = new google.maps.Geocoder();

									function geocodePosition(pos) {
									  geocoder.geocode({
									    latLng: pos
									  }, function(responses) {
									    if (responses && responses.length > 0) {
									      updateMarkerAddress(responses[0].formatted_address);
									    } else {
									      updateMarkerAddress('Cannot determine address at this location.');
									    }
									  });
									}

									function updateMarkerPosition(latLng) {
									  jQuery('#latitude').val(latLng.lat());
									  jQuery('#longitude').val(latLng.lng());
									}

									function updateMarkerAddress(str) {
									  jQuery('#address').val(str);
									}

									function initialize() {

									  var latlng = new google.maps.LatLng(0, 0);
									  var mapOptions = {
									    zoom: 2,
									    center: latlng
									  }

									  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

									  geocoder = new google.maps.Geocoder();

									  marker = new google.maps.Marker({
									  	position: latlng,
									    map: map,
									    draggable: true
									  });

									  // Add dragging event listeners.
									  google.maps.event.addListener(marker, 'dragstart', function() {
									    updateMarkerAddress('Dragging...');
									  });
									  
									  google.maps.event.addListener(marker, 'drag', function() {
									    updateMarkerPosition(marker.getPosition());
									  });
									  
									  google.maps.event.addListener(marker, 'dragend', function() {
									    geocodePosition(marker.getPosition());
									  });

									}

									google.maps.event.addDomListener(window, 'load', initialize);

									jQuery(document).ready(function() { 
									         
									  initialize();
									          
									  jQuery(function() {
									    jQuery("#address").autocomplete({
									      //This bit uses the geocoder to fetch address values
									      source: function(request, response) {
									        geocoder.geocode( {'address': request.term }, function(results, status) {
									          response(jQuery.map(results, function(item) {
									            return {
									              label:  item.formatted_address,
									              value: item.formatted_address,
									              latitude: item.geometry.location.lat(),
									              longitude: item.geometry.location.lng()
									            }
									          }));
									        })
									      },
									      //This bit is executed upon selection of an address
									      select: function(event, ui) {
									        jQuery("#latitude").val(ui.item.latitude);
									        jQuery("#longitude").val(ui.item.longitude);

									        var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);

									        marker.setPosition(location);
									        map.setZoom(16);
									        map.setCenter(location);

									      }
									    });
									  });
									  
									  //Add listener to marker for reverse geocoding
									  google.maps.event.addListener(marker, 'drag', function() {
									    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
									      if (status == google.maps.GeocoderStatus.OK) {
									        if (results[0]) {
									          jQuery('#address').val(results[0].formatted_address);
									          jQuery('#latitude').val(marker.getPosition().lat());
									          jQuery('#longitude').val(marker.getPosition().lng());
									        }
									      }
									    });
									  });
									  
									});
									jQuery("#primaryPostForm").on("submit", function() {
										var selected_key = "cat-" + $("#catID").val();
										$(".custom_fielder").each(function(index, element) {
											if($(this).attr("id") != selected_key)
												$(this).remove();
										});
										return true;
									});
								});

						    </script>
						<?php }?>
						</div><br clear="all">
						<?php 
						if($classifyGoogleValue == 1){
							$cInputType = 'text';
						}else{
							$cInputType = 'hidden';
						}
						?>
							<input type="<?php echo $cInputType; ?>" id="latitude" name="latitude" placeholder="<?php esc_html_e('Latitude', 'classify') ?>" value="" class="form-text required input-textarea half" required="required">

							<input type="<?php echo $cInputType; ?>" id="longitude" name="longitude" placeholder="<?php esc_html_e('Longitude', 'classify') ?>" value="" class="form-text required input-textarea half" style="float:right" required="required">

						<fieldset class="input-title">

							<label for="edit-field-category-und" class="control-label"><?php _e('Images', 'classify') ?></label>
							<input id="upload-images-ad" type="file" name="upload_attachment[]" multiple />

						</fieldset>

						

						<fieldset class="input-title">
							
							<textarea name="post_video" id="video" cols="8" rows="5" placeholder="<?php esc_html_e('Video Code If You Want!', 'classify') ?>" ></textarea>
							<p class="help-block"><?php esc_html_e('Add video embedding code here (youtube, vimeo, etc)', 'classify') ?></p>

						</fieldset>

						<?php 

							global $redux_demo; 

							$featured_ads_option = $redux_demo['featured-options-on'];

						?>

						<?php if($featured_ads_option == 1) { ?>

						<fieldset class="input-title">

							<label for="edit-field-category-und" class="control-label"><?php _e('Ad Type', 'classify') ?></label>

								<?php if($featPlanMesage != '') { ?>
									<span class="error" style="color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;"><?php echo $featPlanMesage; ?></span>
									<div class="clearfix"></div>
								<?php } ?>

								<div class="field-type-list-boolean field-name-field-featured field-widget-options-onoff form-wrapper" id="edit-field-featured">

								<?php 
								global $current_user;
								wp_get_current_user();

								$userID = $current_user->ID;

								$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal WHERE user_id = $userID ORDER BY main_id DESC" );

								if ( $result ) {

									$featuredADS = 0;

									foreach ( $result as $info ) { 
										if($info->status != "in progress" && $info->status != "pending" && $info->status != "failde") {
														
														
												$featuredADS++;

												if(empty($info->ads)) {
													$availableADS = esc_html__( 'Unlimited', 'classify' );
													$infoAds = esc_html__( 'Unlimited', 'classify' );
												} else {
													$availableADS = $info->ads - $info->used;
													$infoAds = $info->ads;
												} 

												if(empty($info->days)) {
													$infoDays = esc_html__( 'Unlimited', 'classify' );
												} else {
													$infoDays = $info->days;
												} 

												if($info->used != "Unlimited" && $infoAds != "Ulimited" && $info->used == $infoAds) {

												} else {

													?>

												<label class="option checkbox control-label" for="edit-field-featured-und">
													<input style="margin-right: 10px;" type="radio" id="edit-feature-plan" name="edit-feature-plan" value="<?php echo $info->main_id; ?>" class="form-checkbox"  <?php if($regular_ads_status == 0 ){ echo 'checked="checked"';} ?>><?php echo $infoAds; ?> <?php if($infoAds>1) { ?><?php esc_html_e('Ads', 'classify') ?><?php } elseif($infoAds=="Unlimited") { ?><?php esc_html_e('Ads', 'classify') ?><?php } elseif($infoAds==1) { ?><?php esc_html_e('Ad', 'classify') ?><?php } ?> <?php esc_html_e('active for', 'classify') ?> <?php echo $infoDays ?> <?php esc_html_e('days', 'classify') ?> (<?php echo $availableADS; ?> <?php if($availableADS>1) { ?><?php esc_html_e('Ads', 'classify') ?><?php } elseif($availableADS=="Unlimited") { ?><?php esc_html_e('Ads', 'classify') ?><?php } elseif($availableADS==1) { ?><?php esc_html_e('Ad', 'classify') ?><?php } ?> <?php esc_html_e('available', 'classify') ?>)
												</label>

										<?php }
									}
								}
							}
										
						?>

									<?php
									if($regular_ads_status == 1 ){
									?>

										<label class="option checkbox control-label" for="edit-field-featured-und">
											<input style="margin-right: 10px;" type="radio" id="edit-feature-plan" name="edit-feature-plan" value="" class="form-checkbox" checked><?php _e('Regular', 'classify') ?>
										</label>
									
									<?php 
									}
									?>

									<?php 

										global $redux_demo; 
										$featured_plans = $redux_demo['featured_plans'];

									?>
									<?php if($featuredADS == "0" || empty($result)){ ?>
										<label class="option checkbox control-label" for="edit-field-featured-und">
											<input disabled="disabled" type="checkbox" id="edit-feature-plan" name="edit-feature-plan" value="" class="form-checkbox"><?php _e('Featured', 'classify') ?>
										</label>
										<p><?php _e('Currently you have no active plan. You must purchase a', 'classify') ?> <a href="<?php echo $featured_plans; ?>" target="_blank"><?php _e('Featured Pricing Plan', 'classify') ?></a> <?php _e('to be able to publish a Featured Ad.', 'classify') ?></p>
									<?php } ?>

							</div>

						</fieldset>

						<?php }  if($regular_ads_status == 1 ){ ?>

						<input type="hidden" name="regular-ads-status" value=""  >
						<?php } ?>
						<div class="publish-ad-button">
							<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
							<input type="hidden" name="submitted" id="submitted" value="true" />
							<button class="btn form-submit full-btn" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Publish Ad', 'classify') ?></button>
						</div>

					</form>

	    		</div>

	    	</div>

	    	<div class="span4">



		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>



    <?php endwhile; ?>

<?php get_footer(); ?>