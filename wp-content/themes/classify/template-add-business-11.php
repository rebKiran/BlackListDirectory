<?php
WC()->cart->empty_cart( true );

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
wp_session_unset();
$wp_session = WP_Session::get_instance();
$unique_id  = md5(uniqid(rand(), true));
$wp_session['number'] = $unique_id; 



$url ="http://blacklistdir.rebelute.in/packages/";

global $wpdb;

				
if(isset($_POST['submitted'])) {
		
	if(trim($_POST['firstname']) === '') {
		$firstnameError = esc_html__( 'Please enter a Firstname.', 'classify' );
		$hasError = true;
	} else {
		$firstname = trim($_POST['firstname']);
	}

	if(trim($_POST['lastname']) === '') {
		$lastnameError = esc_html__( 'Please enter a Lastname.', 'classify' );
		$hasError = true;
	} else {
		$lastname = trim($_POST['lastname']);
	}
	
	if(trim($_POST['email_address']) === '') {
		$email_addressError = esc_html__( 'Please enter a Email Address.', 'classify' );
		$hasError = true;
	} else {
		$email_address = trim($_POST['email_address']);
	}
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
	
	$owner    = $_POST['owner'];
	$business_role = $_POST['business_role'];
	$phone    = $_POST['contact']; //numeric value use: %d
	$state  	 = $_POST['state']; //string value use: %s
	$city   = $_POST['city']; //string value use: %s
	$mailing_address   = $_POST['mailing_address']; //string value use: %s
	$street_address   = $_POST['street_address']; //string value use: %s
	$address   = $_POST['address']; //string value use: %s
	$contact   = $_POST['contact']; //string value use: %s
	//$category_name = $_POST['category_name'];
	
	/*$product   = $_POST['contact']; //string value use: %s
	$banner_id   = $_POST['contact']; //string value use: %s
	$data_uri   = $_POST['contact']; //string value use: %s
	$price   = $_POST['contact']; //string value use: %s*/
	
	


	global $wpdb;
	
	 $sql = "INSERT INTO `wp_questionnaire` 
	   (`owner`, `business_role`, `firstname`, `lastname`, `mailing_address`, `street_address`, `address`, `city`, `state`, `zipcode`, `email`, `contact`, `product_id`, `banner_id`,  `price`,  `image`, `banner_url`,`business_name`, `web_address`, `category` , `business_phone_number`, `business_purpose`, `number` )
	   values ('$owner','$business_role','$firstname','$lastname', '$mailing_address', '$street_address', '$address',
			 '$city', '$state', '422105', '$email_address', '$contact', '$product','$banner_id', '$price','$data_uri','$banner_url', '$business_name', '$web_address', '$category_name' ,'$phone_number', '$business_purpose', '$unique_id')";

	$wpdb->query($sql);
	?>
	<script type="text/javascript">
               window.location='<?php echo $url;?>';
     </script>
<?php 
} 


?>	
 <script type='text/javascript' src='http://blacklistdir.rebelute.in/wp-content/themes/classify/js/jquery.maskedinput.js'></script>
<section class="ads-main-page ">
	<div class="container">
		<div >
		 <!-- Nav tabs -->
		  <ul class="nav nav-tabs custom-tab" role="tablist">
				<li role="presentation" class="active" id="about_you">
				   <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"> 1. About You</a>
			   </li>
				<li class="add_active" role="presentation" id="about_your_business">
					<a  href="javascript:;" aria-controls="business" role="tab" data-toggle="tab">2. About Your Business</a>
				</li>
		  </ul>

			<div class="tabs-stage no_border">
    <div id="tab-1" >
        <div id="upload-ad" >

					<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
					 <!-- Tab panes -->
  <div class="tab-content">

					 <div role="tabpanel" class="tab-pane active" id="profile">
	  <fieldset class="input-title">
						<label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block"><?php _e('Owner', 'classify') ?></label>
						<div class="padl10 field-type-list-boolean field-name-field-featured field-widget-options-onoff form-wrapper" id="edit-field-featured">

								<label class="option checkbox control-label" for="edit-field-featured-und">
									<input style="margin-right: 10px;" type="radio" id="owner" name="owner" value="Yes" ><?php _e('Yes', 'classify') ?> 
								</label>
<label class="option checkbox control-label" for="edit-field-featured-und">
									<input style="margin-right: 10px;" type="radio" id="owner" name="owner" value="No" ><?php esc_html_e( 'No', 'classify' ); ?>
								</label>
									</div>
					</fieldset>	
<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:none" id="lbl_business">Role in the business<span class="gfield_required">*</span></label>
							<input type="text" id="business_role" name="business_role" value="" size="60" class="form-text required input-textarea half" placeholder="Role in the business" style="display: none">
							
							
							<div class="clearfix"></div>

							<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Name<span class="gfield_required">*</span></label>
	<div style="width:50%;float:left;">
							<input type="text" id="firstname" name="firstname" style="margin-left:10px" value="" size="60" class="form-text required input-textarea half" placeholder="First"><div id="first-err"></div>
							
							</div>
							<div style="width:50%;float:left;">
						<input type="text" id="lastname" name="lastname" value="" size="60" class="form-text required input-textarea half" placeholder="Last">	
							<div id="last-err"></div>
							</div>
							<div class="clearfix"></div>

							<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Mailing Address<span class="gfield_required">*</span></label>
							
<div style="width:50%;float:left;">
<input style="margin-left:10px" type="text" id="mailing_address" name="mailing_address" value="" size="12" class="form-text required input-textarea half" placeholder="Mailing Address">	
<div id="mail-addr-err"></div>
</div>
<div style="width:50%;float:left;">
<input type="text" id="street_address" name="street_address" value="" style="margin-left:10px"class="form-text required input-textarea half" placeholder="Street Address">
							</div>
							
							
							<input  style="margin-left:10px" type="text" id="address1" name="address" value="" class="form-text required input-textarea half" placeholder="Address Line 2">


<input type="text" style="margin-left:10px" id="state" name="state" value="" class="form-text required input-textarea half" placeholder="State">						
							<input type="text" style="margin-left:10px" id="city" name="city" value="" class="form-text required input-textarea half" placeholder="City">


<input type="text" style="margin-left:10px" id="zipcode" name="zipcode" value="" class="form-text required input-textarea half" placeholder="zipcode">
		
<div class="clearfix"></div>

	<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Email Address<span class="gfield_required">*</span></label>

	<input style="margin-left:10px" type="text" id="email_address" name="email_address" value="" class="form-text required input-textarea half" placeholder="Email Address">
							
							<br clear="all">
	<div id="email-err"></div>							

	<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Contact Number<span class="gfield_required">*</span></label>
<input style="margin-left:10px" type="text" id="contact" name="contact" value="" class="form-text required input-textarea half" placeholder="Contact Number">

<br clear="all">	
	<div id="contact-err"></div>							

						<div class="publish-ad-button" style="text-align:center;margin: 41px 0px;">
							
							<input type="hidden" name="submitted" id="next" value="true" />
	<a  href="#business" aria-controls="business" role="tab" data-toggle="tab" class="theme_btn apend_active" id="edit-next" name="op" value="Publish Ad" type="submit">
        <?php _e('Next', 'classify') ?>
        </a>
						</div>

	</div>
	
	  <div role="tabpanel" class="tab-pane" id="business">
     <label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Business Name<span class="gfield_required">*</span></label>
<input style="margin-left:10px" type="text" id="business_name" name="business_name" value="" size="60" class="form-text required input-textarea half" required placeholder="Business Name">
							
<div class="clearfix"></div>
<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Web Address</label>
<input style="margin-left:10px" type="text" id="web_address" name="web_address" value="" size="60" class="form-text required input-textarea half" placeholder="Web Address">
<div class="clearfix"></div>

<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Phone Number<span class="gfield_required">*</span></label>

		
							
							<input style="margin-left:10px" type="text" id="phone_number" name="phone_number" value="" size="60" class="form-text required input-textarea half" placeholder="Phone Number" required><div class="clearfix"></div>

				<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Business Type</label>			
							
							<div style="margin-left:10px" id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
						    <div class="views-widget">
						        <div class="control-group form-type-select form-item-field-category form-item">
									<div class="controls"> 
										<select id="edit-field-category" name="category_name" class="form-select" style="display: none;">
													
											<option value="All" selected="selected"><?php echo $trns_category; ?>...</option>
											<?php
											$args = array(
												'hierarchical' => '0',
												'hide_empty' => '0'
											);
											$categories = get_categories($args);
										/*	print_r($categories);
											die('Here');*/
												foreach ($categories as $cat) {
													if ($cat->category_parent == 0) { 
														$catID = $cat->cat_ID;
													?>
														<option value="<?php echo $catID; ?>"><?php echo $cat->cat_name; ?></option>
																			
												<?php 
													$args2 = array(
														'hide_empty' => '0',
														'parent' => $catID
													);
													$categories = get_categories($args2);
													foreach ($categories as $cat) { ?>
														<option value="<?php echo  $cat->cat_ID; ?>">- <?php echo $cat->cat_name; ?></option>
												<?php } ?>

												<?php } else { ?>
												<?php }
											} ?>

										</select>
									</div>
								</div>
						    </div>
						</div>
					    <br clear="all">

						<fieldset class="input-title">
<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
    font-weight: bold !important;
    color: #1e73be !important;
    border-bottom: 1px solid;
    width: 96%;
margin:10px;
    padding-bottom: 8px;display:inline-block">Briefly describe the purpose of the business</label>
							<textarea name="business_purpose" id="video" cols="15" rows="5" placeholder="Briefly describe the purpose of the business" ></textarea>
							<p class="help-block"></p>
						</fieldset>	
						
						<br clear="all">	
							

						<div class="publish-ad-button" style="text-align:center">
							
							<input type="hidden" name="submitted" id="submitted" value="true" />
							<button class="theme_btn" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Lets Get Listed', 'classify') ?></button>
						</div>
						
   </div>
  
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
		
        if ($(this).val() == 'No') {
            $('#business_role').show();
 		    $('#lbl_business').show();

        }
        else {
            $('#business_role').hide();
			$('#lbl_business').hide();
        }
    });
	
	$('.publish-ad-button #edit-next').click(function () {
		
		/*$('#about_your_business').addClass("active");
		$('#about_you').removeClass("active");
		$('#about_you a').attr("href", "javascript:;");*/
               var first = $('#firstname').val();
		 var last  =  $('#lastname').val();
		 var mailing_address =  $('#mailing_address').val();
		 var email_address =  $('#email_address').val();
		 var contact =  $('#contact').val();
		  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		 var valid = 'true';
		 $('#first-err').empty();
		 $('#last-err').empty();
		 $('#mail-addr-err').empty();
		 $('#email-err').empty();
		 $('#contact-err').empty();
		 if( '' == first ) {
              $('#first-err').html( "<span style='color:red;margin-left:10px;'>Firstname is required.</span>" );
  		      $('#firstname').focus();
			  valid = 'false';
	     } else if( '' == last ) {
                          $('#last-err').html( "<span style='color:red;margin-left:10px;'>Lastname is required.</span>" );
			  $('#lastname').focus();
			  valid = 'false';
	     } else if( '' == mailing_address ) {
                      $('#mail-addr-err').html( "<span style='color:red;margin-left:10px;'>Mailing address is required.</span>" );
			  $('#mailing_address').focus();
			  valid = 'false';
	     } else if( '' == email_address ) {
              $('#email-err').html( "<span style='color:red;margin-left:10px;'>Email address is required.</span>" );
			  $('#email_address').focus();
			  valid = 'false';
	     } else if( '' == contact ) {
              $('#contact-err').html( "<span style='color:red;margin-left:10px;'>Contact number is required.</span>" );
			  $('#contact').focus();
			  valid = 'false';
	     } else if( '' != email_address ) {
			 email_address = email_address.trim();
			 if( false == re.test(email_address)) {
				 
				  $('#email-err').html( "<span style='color:red;margin-left:10px;'>Please enter a valid email address.</span>" );
				  $('#email_address').focus();
				  valid = 'false';
			 } else {
				  $('#email-err').html();
			 }
	     }
		 
		if( 'true' == valid ) {
			$('#business').removeClass('tab-pane');
			$('#profile').addClass('tab-pane');
			$('#business').show();
			$('#about_you').removeClass('active');
			$('#about_you').addClass('add_active');
			$('#about_your_business').removeClass('add_active');
			$('#about_your_business').addClass('active');
			
		} else {
			$('#profile').removeClass('tab-pane');
			$('#business').hide();
			 /*$('.tab-active').removeClass('tab-active');
		     $(this).parent().addClass('tab-active');*/
			
		}
	});
});

jQuery.noConflict();
jQuery( document ).ready(function(  ) {
	jQuery.mask.definitions['~'] = "[+-]";
	jQuery("#contact").mask("(999) 999-9999");
	jQuery("#phone_number").mask("(999) 999-9999");
	
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
