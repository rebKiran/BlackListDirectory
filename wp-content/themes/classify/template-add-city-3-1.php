<?php


/**
 * Template Name: Add Business
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
/*if ( !is_user_logged_in() ) {
	wp_redirect( home_url() ); exit;
}elseif(!(isset($_GET['post']))) {
	wp_redirect( home_url() ); exit;
	}else{ 
}*/

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
			//echo $querystr;
$banner_sizes = $wpdb->get_results($querystr,ARRAY_A);
			
$wp_session = WP_Session::get_instance();
/*echo '<pre/>';
print_r($wp_session);
die( 'Here' );*/
$url ="http://blacklistdir.rebelute.in/design-your-ads/";
$data = json_decode($wp_session->json_out() ); 
$product = $data->product;
$banner_id = $data->banner_id;
$price = $data->price;
$json = $data->json;
$data_uri = $data->data_uri;
$banner_url = $data->banner_url;

 //$url = get_permalink( $product );
/*echo '<pre/>';
print_r($wp_session);*/
//$product = $wp_session->container;

$data = json_decode($wp_session->json_out() ); 

global $wpdb;
			
if(isset($_POST['submitted_package'])) {
	
		
	$city = trim($_POST['city']);
	$banner = $_POST['banner'];
	$image = $_POST['image'];
	
	global $wpdb;
	
	$sql = "INSERT INTO `blacklistdir`.`wp_packages` (`city`, `banner`, `banner_option`, `banner_image`, `banner_id`) VALUES ('$city', '$banner', '$image', NULL, '$banner_id')";
  
	$wpdb->query($sql);
	?>
	<script type="text/javascript">
               window.location='<?php echo $url;?>';
     </script>
<?php 
} 

/*if(isset($_POST['submitted_cart'])) {
	
}*/
/*
if(isset($_POST['submitted_business'])) {

	if(trim($_POST['business_name']) === '') {
		$firstnameError = esc_html__( 'Please enter a Firstname.', 'classify' );
		$hasError = true;
	} else {
		$business_name = trim($_POST['business_name']);
	}

	if(trim($_POST['phone_number']) === '') {
		$lastnameError = esc_html__( 'Please enter a Phone Number.', 'classify' );
		$hasError = true;
	} else {
		$phone_number = trim($_POST['phone_number']);
	}
	

	$web_address = trim($_POST['web_address']);
	$business_purpose = $_POST['business_purpose'];
	$category_name = $_POST['category_name'];
	
		global $wpdb;
	
	
	$sql = "INSERT INTO `wp_about_business` (`name`, `web_address`, `phone_number`, `business_purpose`) VALUES ( '$business_name', '$web_address', '$$phone_number', '$$business_purpose ')";

	$wpdb->query($sql);
}*/
?>	
  
<section class="ads-main-page ">
<div class="container">
<div>
	<ul class="tabs-nav">
    <!--<li class=""><a href="#tab-1" rel="nofollow"></a>
    </li>-->
   <!-- <li class="tab-active"><a href="#tab-2" rel="nofollow">Packages</a>
    </li>-->
</ul>
<div class="tabs-stage">
    <div id="tab-1" >
        <div id="upload-ad" >

					<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

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
						<label class="option checkbox control-label" for="edit-field-featured-und" style="margin-right: 20px;width:20%;" >
									<input style="margin-right: 10px;width:30%;" type="radio" id="basic" name="listing" value="Basic" ><?php _e('Basic', 'classify') ?> 
								</label>
								
								<label class="option checkbox control-label" for="edit-field-featured-und" style="margin-right: 20px;">
									<input style="margin-right:10px;width:30%;" type="radio" id="logo_display" name="listing" value="Logo Displayed" ><?php esc_html_e( 'Logo Displayed', 'classify' ); ?>
								</label>
						</div>
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
									<td>Company Phone </td>
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
						</div>
						<div id="weblisting_logo_display" style="display:none;">
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
									<td>Company Phone </td>
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
						</div>
						<div class="publish-ad-button">
							<input type="hidden" name="submitted_package" id="submitted_package" value="true" />
							<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit" style="width:20%;"><?php _e('Lets Get Listed', 'classify') ?></button>
						</div>

					</form>

	    		</div>
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
        }
        else {
            $('#chicago_business').hide();
			$('#outside_chicago_listing').show();
        }
    });
	
	$('form input[name="listing"]').on('click', function() {
		
        if ($(this).val() == 'Basic') {
            $('#weblisting_basic').show();
			$('#weblisting_logo_display').hide();
        }
        else {
            $('#weblisting_basic').hide();
			$('#weblisting_logo_display').show();
        }
    });
	
	$('form input[name="banner"]').on('click', function() {
		
        /*if ($(this).val() == 'Upload Image') {*/
            $('#template').show();
			 $('#outside_chicago_listing').hide();
			 $('#chicago_business').show();
       /* } else {
			$('#template').hide();
		}*/
    });
	$('form input[name="image"]').on('click', function() {
		
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
