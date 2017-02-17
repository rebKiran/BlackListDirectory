<?php


/**
 * Template Name: Add Business
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */


get_header(); ?>

<?php 	

$querystr = "
			   SELECT $wpdb->postmeta.meta_value as size, $wpdb->posts.post_title as label, $wpdb->posts.ID as id
			   FROM $wpdb->posts, $wpdb->postmeta
			   WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
			   AND $wpdb->postmeta.meta_key = '_banner_bc_size' 
			   AND $wpdb->posts.post_status = 'publish' 
			   AND $wpdb->posts.post_type = 'vbc_banners'
			   ORDER BY $wpdb->posts.post_title ASC
			";
			
$banner_sizes = $wpdb->get_results($querystr,ARRAY_A);

 $query = "SELECT products.ID as product_id,products.post_title,postmeta.meta_value AS price
						FROM {$wpdb->prefix}postmeta AS postmeta
					   JOIN {$wpdb->prefix}posts AS products ON ( products.ID = postmeta.post_id )
						WHERE postmeta.meta_key = '_sale_price'
						AND products.ID IN ('2077','2078','2079','2081')";
						
	$results = $wpdb->get_results($query,  ARRAY_A );		
	
	$query2 = "SELECT products.ID as product_id,products.post_title,postmeta.meta_value AS price
						FROM {$wpdb->prefix}postmeta AS postmeta
					   JOIN {$wpdb->prefix}posts AS products ON ( products.ID = postmeta.post_id )
						WHERE postmeta.meta_key = '_sale_price'
						AND products.ID IN ('2082','2087','2084','2086')";
						
	$results2 = $wpdb->get_results($query2,  ARRAY_A );				
	
$wp_session = WP_Session::get_instance();
$data = json_decode($wp_session->json_out() ); 


global $wpdb;
			
if('Submit' == $_POST['op'] && isset($_POST['submitted_package']) ) {
	

	$url ="http://blacklistdir.rebelute.in/design-your-ads/";
	$city = trim($_POST['city']);
	$banner = $_POST['banner'];
	$image = $_POST['image'];
	
	global $wpdb;
	
	$sql = "INSERT INTO `wp_packages` (`city`, `banner`, `banner_option`, `banner_image`, `banner_id`, `number`) VALUES ('$city', '$banner', '$image', NULL, '$banner_id','$data->number')";
  
	$wpdb->query($sql);
	?>
	<script type="text/javascript">
               window.location='<?php echo $url;?>';
     </script>
<?php 
} 


?>	
  
<section class="ads-main-page ">
	<div class="container">
		<div>

		<div class="tabs-stage no_border">
		
			<div id="tab-1" >
			<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
				<!-- <div id="upload-ad" > -->
				<div>

							

									<h2 style="margin-left:10px">Packages</h2>
									<fieldset class="input-title">
															<label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
										font-weight: bold !important;
										color: #1e73be !important;
										border-bottom: 1px solid;
										width: 96%;
									margin:10px;
										padding-bottom: 8px;display:inline-block"><?php _e('City', 'classify') ?></label>
								
								<div class="field-type-list-boolean field-name-field-featured field-widget-options-onoff form-wrapper" id="edit-field-featured">

										<label class="option checkbox control-label" for="edit-field-featured-und" style="margin-right: 20px;" >
											<input style="margin-right: 10px;" type="radio" id="chicago" name="city" value="Chicago" ><?php _e('Chicago', 'classify') ?> 
										</label>
										<label class="option checkbox control-label" for="edit-field-featured-und" style="margin-right: 20px;">
											<input style="margin-right: 10px;" type="radio" id="outside_chicago" name="city" value="Outside Chicago" ><?php esc_html_e( 'Outside Chicago', 'classify' ); ?>
										</label>
								</div>
							</fieldset>	
							<div id="chicago_business" style="display:none;">
								<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
									font-weight: bold !important;
									color: #1e73be !important;
									border-bottom: 1px solid;
									width: 96%;
								margin:10px;
									padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Chicago Black Owned Business</label>
								
								<?php foreach( $banner_sizes as $banner_size )
								 { ?>
										<label class="option checkbox control-label" for="edit-field-featured-und" style="width:80% !important;">
												<input style="margin-right: 10px;" type="radio" id="banner_<?php echo $banner_size['id'];?>" name="banner" value="<?php echo $banner_size['id']; ?>" ><?php _e($banner_size['label'], 'classify') ?> 
										</label>
										<div class="clearfix"></div>
								 <?php } ?>
								 
								 <div style="display:none;" id="template">
									 <fieldset class="input-title" >
											<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
									font-weight: bold !important;
									color: #1e73be !important;
									border-bottom: 1px solid;
									width: 96%;
								margin:10px;
									padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Select Your Option</label>
									
											<div class="field-type-list-boolean field-name-field-featured field-widget-options-onoff form-wrapper" id="edit-field-featured">

													<label class="option checkbox control-label" for="edit-field-featured-und" style="margin-right: 20px;" >
														<input style="margin-right: 10px;" type="radio" id="display_image" name="image" value="Upload Image" ><?php _e('Upload Image', 'classify') ?> 
													</label>
													<label class="option checkbox control-label" for="edit-field-featured-und" style="margin-right: 20px;">
														<input style="margin-right: 10px;" type="radio" id="create_image" name="image" value="Create Your Design" ><?php esc_html_e( 'Create Your Design', 'classify' ); ?>
													</label>
											</div>
									 </fieldset>	
								 </div>
								 <div style="display:none;" id="upload_template_image">
									<fieldset class="input-title">
									<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
									font-weight: bold !important;
									color: #1e73be !important;
									border-bottom: 1px solid;
									width: 96%;
								margin:10px;
									padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Upload Image</label>
										<label for="edit-field-category-und" class="control-label"><?php _e('Images', 'classify') ?></label>
									<input id="upload-images-ad" type="file" name="upload_attachment[]" multiple />

								</fieldset>
								
								
								
								</div>
								
								
							</div>
							<div id="outside_chicago_listing" style="display:none;">
								<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
									font-weight: bold !important;
									color: #1e73be !important;
									border-bottom: 1px solid;
									width: 96%;
								margin:10px;
									padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Website Listings</label>
								<div id="edit-field-featured">
								<label class="option checkbox control-label" for="edit-field-featured-und">
										<input style="margin-right: 10px;margin-top: 0px;" type="radio" id="basic" name="listing" value="Basic" ><?php _e('Basic', 'classify') ?> 
								</label>
										
								<label class="option checkbox control-label" for="edit-field-featured-und">
										<input style="margin-right: 10px;margin-top: 0px;" type="radio" id="logo_display" name="listing" value="Logo Displayed" ><?php esc_html_e( 'Logo Displayed', 'classify' ); ?>
								</label>
								
								</div>
								</div>
								<div style="clear:both"></div>
								<br>
								<div id="weblisting_basic" style="display:none;">
									<table class="weblist">
									  <tbody>
										<tr>
											<td><strong>PRICE</strong></td>
											<td><strong>$100 </strong></td>
										  </tr>
										  <tr>
											<td><p>Website Listing</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Company Name</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Company Address (if applicable) </p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Company Phone</p> </td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Company Web Address <strong>(if applicable)</strong></p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Your Website Link</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Subjected to Reviews</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Photo of you showing you are listed Us will be on our photo gallery and Facebook Page</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Directory Listing (not applicable)</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										</tbody>
									</table>
									<div id="weblist_chicago">
										
											<fieldset class="input-title">
											<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
											font-weight: bold !important;
											color: #1e73be !important;
											border-bottom: 1px solid;
											width: 96%;
										margin:10px;
											padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Additional Costs (optional)</label>
											
											<div class="ginput_container ginput_container_checkbox">
												<ul class="gfield_checkbox" id="input_4_37">
													<li class="gchoice_4_37_1">
															<input name="name_in_caps" type="checkbox" value="5" id="product_id_2" tabindex="5">
															<label for="choice_4_37_1" id="label_4_37_1" price=" +$5.00">NAME IN ALL CAPS<span class="ginput_price"> +$5.00</span></label>
													</li>
													<li class="gchoice_4_37_2">
														<input name="bold_print" type="checkbox" value="10" id="product_id_3" tabindex="6">
														<label for="choice_4_37_2" id="label_4_37_2" price=" +$10.00">Bold Print<span class="ginput_price"> +$10.00</span></label>
													</li>
												</ul>
											</div>
											<div class="clearfix"></div>
											<div class="">
														<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
												font-weight: bold !important;
												color: #1e73be !important;
												border-bottom: 1px solid;
												width: 96%;
											margin:10px;
												padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Price</label>
												<div class="padl10 ginput_container ginput_container_singleproduct">
													<input type="hidden" name="input_9.1" value="Price" class="gform_hidden">
													<span class="ginput_product_price_label">Price:</span> 
													<span class="ginput_product_price price_color" id="input_4_9">$100.00</span>
													<input type="hidden" name="input_9.2" id="ginput_base_price_4_9" class="gform_hidden" value="$100.00">
													<input type="hidden" name="input_9.3" value="1" class="ginput_quantity_4_9 gform_hidden">
													<input type="hidden" name="product_id_1" id="product_id_1" value="<?php if(!empty($results)) { echo $results[0]['product_id'];  } ?>" class="ginput_quantity_4_9 gform_hidden">
													<input type="hidden" name="product_id_21" id="product_id_21" value="<?php if(!empty($results)) { echo $results[1]['product_id'];  } ?>" class="ginput_quantity_4_9 gform_hidden">
													<input type="hidden" name="product_id_33" id="product_id_33" value="<?php if(!empty($results)) { echo $results[2]['product_id'];  } ?>" class="ginput_quantity_4_9 gform_hidden">
													
													<input type="hidden" name="product_id_4" id="product_id_4" value="<?php if(!empty($results)) { echo $results[3]['product_id'];  } ?>" class="ginput_quantity_4_9 gform_hidden">
												</div>
											</div>
											<div class="clearfix"></div>
											<div>
												<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
														font-weight: bold !important;
														color: #1e73be !important;
														border-bottom: 1px solid;
														width: 96%;
													margin:10px;
														padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Total Price</label>
														<div class="padl10 ginput_container ginput_container_total">
															<span class="ginput_total ginput_total_4 total_price">$100.00</span>
															<input type="hidden" name="input_38" id="input_4_38" class="gform_hidden" value="100">
														</div>
											</div>
											
									</div>
								</div>	
								
								<div id="weblisting_logo_display" style="display:none;">
									<table class="weblist">
									  <tbody>
										<tr>
											<td><strong>PRICE</strong></td>
											<td><strong>$125</strong></td>
										  </tr>
										  <tr>
											<td><p>Website Listing</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Company Name</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Company Address (if applicable) </p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Company Phone</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Company Web Address <strong>(if applicable)</strong></p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Your Website Link</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Subjected to Reviews</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Photo of you showing you are listed Us will be on our photo gallery and Facebook Page</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										  <tr>
											<td><p>Directory Listing (not applicable)</p></td>
											<td><img src="http://websmartteam.com/blacklist/wp-content/uploads/2016/10/tick.png"></td>
										  </tr>
										</tbody>
									</table>
									
									<div id="weblist_outside">
										
											<fieldset class="input-title">
											<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
											font-weight: bold !important;
											color: #1e73be !important;
											border-bottom: 1px solid;
											width: 96%;
										margin:10px;
											padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Additional Costs (optional)</label>
											
											<div class="ginput_container ginput_container_checkbox">
												<ul class="gfield_checkbox" id="input_4_37">
													<li class="gchoice_4_37_1">
															<input name="name_in_caps" type="checkbox" value="5" id="product_id_12" tabindex="5">
															<label for="choice_4_37_1" id="label_4_37_1" price=" +$5.00">NAME IN ALL CAPS<span class="ginput_price"> +$5.00</span></label>
													</li>
													<li class="gchoice_4_37_2">
														<input name="bold_print" type="checkbox" value="10" id="product_id_13" tabindex="6">
														<label for="choice_4_37_2" id="label_4_37_2" price=" +$10.00">Bold Print<span class="ginput_price"> +$10.00</span></label>
													</li>
												</ul>
											</div>
											<div class="clearfix"></div>
											<div>
														<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
												font-weight: bold !important;
												color: #1e73be !important;
												border-bottom: 1px solid;
												width: 96%;
											margin:10px;
												padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Price</label>
												<div class="padl10 ginput_container ginput_container_singleproduct">
													<input type="hidden" name="input_9.1" value="Price" class="gform_hidden">
													<span class="ginput_product_price_label">Price:</span> <span class="ginput_product_price price_color" id="input_4_9">$125.00</span>
													<input type="hidden" name="input_9.2" id="ginput_base_price_4_9" class="gform_hidden" value="$100.00">
													<input type="hidden" name="input_9.3" value="1" class="ginput_quantity_4_9 gform_hidden">
																										
													<input type="hidden" name="product_id_11" id="product_id_11" value="<?php if(!empty($results2)) { echo $results2[0]['product_id'];  } ?>" class="ginput_quantity_4_9 gform_hidden">
													<input type="hidden" name="product_id_22" id="product_id_22" value="<?php if(!empty($results2)) { echo $results2[1]['product_id'];  } ?>" class="ginput_quantity_4_9 gform_hidden">
													<input type="hidden" name="product_id_23" id="product_id_23" value="<?php if(!empty($results2)) { echo $results2[2]['product_id'];  } ?>" class="ginput_quantity_4_9 gform_hidden">
													
													<input type="hidden" name="product_id_14" id="product_id_14" value="<?php if(!empty($results2)) { echo $results2[3]['product_id'];  } ?>" class="ginput_quantity_4_9 gform_hidden">
												</div>
											</div>
											<div class="clearfix"></div>
											<div>
												<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
														font-weight: bold !important;
														color: #1e73be !important;
														border-bottom: 1px solid;
														width: 96%;
													margin:10px;
														padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">Total Price</label>
														<div class="padl10 ginput_container ginput_container_total">
															<span class="ginput_outside_total ginput_total_4 total_price">$125.00</span>
															<input type="hidden" name="input_38" id="input_4_38" class="gform_hidden" value="100">
														</div>
											</div>
											
									</div>
								</div>
								
							   
							
							</div>
							<div class="publish-ad-button" id="chicago_button">
									<input type="hidden" name="submitted_package" id="submitted_package" value="true" />
									<input  type="submit" class="theme_btn" id="edit-submit" name="op" value="Submit"  style="margin-left: 20px;margin-top: 20px;"/>

							</div>
							</form>
							<div style="clear:both"></div>
							<br>
							<div class="publish-ad-button" id="outside_chicago_button" style="display:none;">
									<input name="add-to-cart" id="add-to-cart" type="hidden" value="<?php if(!empty($results)) { echo $results[0]['product_id'];  } ?>" />
									<input name="quantity" type="hidden" value="1" min="1"   />
									<input  type="submit"  class="button-secondary theme_btn" id="add-to-cart-listing" style="    width: 98px !important;    padding: 10px 3px;    font-size: 14px;" />
									
							</div> 
							<div style="clear:both"></div>
					<br>
			</div>
			
		  
		</div>
	</div>
	</div>
</section>
<script type="text/javascript">//<![CDATA[
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
	 $('.tabs-nav li a').on('click', function (event) { 
		event.preventDefault();
		
	    $('.tab-active').removeClass('tab-active');
		$(this).parent().addClass('tab-active');
		//$('.tabs-stage div').hide();
		//alert( $(this).attr('href'));
		if( '#tab-1' == $(this).attr('href')) {
			$('#tab-1').show();
			$('#tab-2').hide();
		} else {
			$('#tab-2').show();
			$('#tab-1').hide();
		 }	
		//alert( $(this).parent(). );
		///alert( $(this).attr('href') );
		//$( $(this).attr('href')).show();
		//$($(this).attr('href')).show();
	});
	$('.tabs-nav a:first').trigger('click'); // Default
});
// C
/*$(function(){
// From http://learn.shayhowe.com/advanced-html-css/jquery

// Change tab class and display content


	$('.tabs-nav a:first').trigger('click'); // Default
});//]]> 
*/
jQuery( document ).ready(function( $ ) {
	
	
    $('form input[type="radio"]').on('click', function() {
		 $('#form input[name="banner"]').each(function(){
			  $(this).checked = false;  
		  });
		 $('#template').hide();
         if ($(this).val() == 'Chicago') {
			

            $('#chicago_business').show();
			$('#outside_chicago_listing').hide();
			$('#weblisting_basic').hide();
			$('#weblisting_logo_display').hide();
			$('#outside_chicago_button').hide();
			$('#chicago_button').show();
			
        }
        else {
            $('#chicago_business').hide();
			$('#outside_chicago_listing').show();
			$('#weblisting_basic').hide();
			$('#weblisting_logo_display').hide();
			$('#chicago_button').hide();
			$('#outside_chicago_button').show();
			
        }
    });
	
	$('form input[name="listing"]').on('click', function() {
		
        if ($(this).val() == 'Basic') {
			$('#add-to-cart').val( $('#product_id_1').val() );
            $('#weblisting_basic').show();
			$('#weblisting_logo_display').hide();
        }
        else {
			$('#add-to-cart').val( $('#product_id_11').val() );
            $('#weblisting_basic').hide();
			$('#weblisting_logo_display').show();
        }
    });
	
	$('form input[name="banner"]').on('click', function() {
		
        /*if ($(this).val() == 'Upload Image') {*/
            $('#template').show();
			 $('#outside_chicago_listing').hide();
			 $('#chicago_business').show();
			 $('#weblisting_logo_display').hide();
			 $('#outside_chicago_button').hide();
			$('#chicago_button').show();
       /* } else {
			$('#template').hide();
		}*/
    });
	$('form input[name="image"]').on('click', function() {
		
		$('#weblisting_basic').hide();
		$('#weblisting_logo_display').hide();
		$('#outside_chicago_button').hide();
			$('#chicago_button').show();
        if ($(this).val() == 'Upload Image') {
             $('#template').show();
			 $('#outside_chicago_listing').hide();
			 $('#chicago_business').show();
			 $('#upload_template_image').show();
			 
			 
      } else {
			 $('#template').show();
			 $('#outside_chicago_listing').hide();
			 $('#chicago_business').show();
			 $('#upload_template_image').hide();
			 
			
		}
    });
	var price = 100;
	
	$('#weblist_chicago .ginput_container input[type="checkbox"]').on('click', function() {
		
		if((this).checked ) {
			price += parseFloat($(this).val());
			$('#weblist_chicago .ginput_total').html();
			$('#weblist_chicago .ginput_total').html( '$ '+ price);
			
		} else {
			price -= parseFloat($(this).val());
			$('#weblist_chicago .ginput_total').html();
			$('#weblist_chicago .ginput_total').html( '$ '+ price);
		}
		
		if( $('#product_id_2').is(':checked')  == false && $('#product_id_3').is(':checked') == false ) { 
			$('#add-to-cart').val( $('#product_id_1').val() );
		} else if ( $('#product_id_2').is(':checked') == true  &&  $('#product_id_3').is(':checked') == false ){
			$('#add-to-cart').val( $('#product_id_21').val() );
		} else if ( $('#product_id_2').is(':checked') == false &&  $('#product_id_3').is(':checked') == true ) {	
			$('#add-to-cart').val( $('#product_id_33').val() );
		} else {
			$('#add-to-cart').val( $('#product_id_4').val() );
		}

    });
	 
	var new_price = 125;
	$('#weblist_outside input[type="checkbox"]').on('click', function() {
	
		if((this).checked ) {
			new_price += parseFloat($(this).val());
			$('#weblist_outside .ginput_outside_total').html();
			$('#weblist_outside .ginput_outside_total').html( '$ '+ new_price);
			
		} else {
			new_price -= parseFloat($(this).val());
			$('#weblist_outside .ginput_outside_total').html();
			$('#weblist_outside .ginput_outside_total').html( '$ '+ new_price);
			
		}
		//alert(' 2 '+ $('#product_id_2').is(':checked'));
		//	alert(' 3 '+ $('#product_id_3').is(':checked'));
			
		
		if( $('#product_id_12').is(':checked')  == false && $('#product_id_13').is(':checked') == false ) { 
			$('#add-to-cart').val( $('#product_id_11').val() );
		} else if ( $('#product_id_12').is(':checked') == true  &&  $('#product_id_13').is(':checked') == false ){ 
			$('#add-to-cart').val( $('#product_id_22').val() );
		} else if ( $('#product_id_12').is(':checked') == false &&  $('#product_id_13').is(':checked') == true ) {
			$('#add-to-cart').val( $('#product_id_23').val() );
		} else {
			$('#add-to-cart').val( $('#product_id_14').val() );
		}

    });

$('#add-to-cart-listing').click(function(){
		  var product_id = $('#add-to-cart').val();
		
		var link_url = 'http://blacklistdir.rebelute.in/?add-to-cart='+product_id+'&quantity=1';
	
		
		window.location.href =  link_url;
	});
});


</script>
  
<style>

.step-tab {
	padding: 39px 30px;
}

ul, li, div {
    background: hsla(0, 0%, 0%, 0);
    border: 0;
    font-size: 100%;
    margin: 0;
    outline: 0;
    padding: 0;
    vertical-align: baseline;
    font: 13px/20px "Droid Sans", Arial, "Helvetica Neue", "Lucida Grande", sans-serif;

}
li {
    display: list-item;
    text-align: -webkit-match-parent;
}
.tabs-nav {
    list-style: none;
    margin: 0;
    padding: 0;
}
.tabs-nav li:first-child a {
    border-right: 0;
    -moz-border-radius-topleft: 6px;
    -webkit-border-top-left-radius: 6px;
    border-top-left-radius: 6px;
}
.tabs-nav .tab-active a {
    background: hsl(0, 100%, 100%);
    border-bottom-color: hsla(0, 0%, 0%, 0);
    color: hsl(85, 54%, 51%);
    cursor: default;
}
.tabs-nav a {
    background: hsl(120, 11%, 96%);
    border: 1px solid hsl(210, 6%, 79%);
    color: hsl(215, 6%, 57%);
    display: block;
    font-size: 11px;
    font-weight: bold;
    height: 40px;
    line-height: 44px;
    text-align: center;
    text-transform: uppercase;
    width: 140px;
}
.tabs-nav li {
    float: left;
}
.tabs-stage {
    border: 1px solid hsl(210, 6%, 79%);
    -webkit-border-radius: 0 0 6px 6px;
    -moz-border-radius: 0 0 6px 6px;
    -ms-border-radius: 0 0 6px 6px;
    -o-border-radius: 0 0 6px 6px;
    border-radius: 0 0 6px 6px;

    clear: both;
    margin-bottom: 20px;
    position: relative;
    top: -1px;
    width: 100%;
}
.tabs-stage p {
    margin: 0;
    padding: 20px;
    color: hsl(0, 0%, 33%);
}
</style>
<?php get_footer(); ?>
