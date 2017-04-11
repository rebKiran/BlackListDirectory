<?php
session_start();

wp_cache_flush();


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


	$woocommerce->session->set_customer_session_cookie(true);
	get_header(); ?>

	<?php 	

	global $wpdb, $woocommerce;
      
	/* $wp_session = WP_Session::get_instance();
	$data = json_decode($wp_session->json_out() ); */

	$owner = "";
	$business_role = "";
	$firstname = "";
	$lastname = "";
	$mailing_address = "";
	$street_address = "";
	$address = "";
	$city = "";
	$state = "";
	$country = "";
	$zipcode = "";
	$email = "";
	$contact = "";
	$business_name = "";
	$web_address = "";
	$category = "";
	/*$business_phone_number = ""; */
	$business_purpose = "";
	$mob_business_city = "";
	$mob_business_state = "";
	$mob_business_country = "";
	$mob_business_contact = "";
	$mob_business_zipcode = "";
	

       
	$arrBusinessDetails = array();

	if( !empty($_SESSION['questionnaire_id'])) {	
		
		$res_about_us = $wpdb->get_row( "SELECT * FROM wp_questionnaire WHERE id = '".$_SESSION['questionnaire_id']."' ", ARRAY_A );

		$owner = $res_about_us['owner'];
		$business_role = $res_about_us['business_role'];
		$firstname = $res_about_us['firstname'];
		$lastname = $res_about_us['lastname'];
		$mailing_address = $res_about_us['mailing_address'];
		$street_address = $res_about_us['street_address'];
		$address = $res_about_us['address'];
		$city = $res_about_us['city'];
		$state = $res_about_us['state'];
		$zipcode = $res_about_us['zipcode'];
		$country = $res_about_us['country'];
		$email = $res_about_us['email'];
		$contact = $res_about_us['contact'];
		$business_name = $res_about_us['business_name'];
		$web_address = $res_about_us['web_address'];
		$category = $res_about_us['category'];
		$subcategory = $res_about_us['subcategory'];
		/*$business_phone_number = $res_about_us['business_phone_number'];*/
		$business_purpose = $res_about_us['business_purpose'];
		$mob_business_city = $res_about_us['mob_business_city'];
		$mob_business_state = $res_about_us['mob_business_state'];
		$mob_business_country = $res_about_us['mob_business_country'];
		$mob_business_contact = $res_about_us['mob_business_contact'];
		$mob_business_zipcode = $res_about_us['mob_business_zipcode'];
		$qry_business = "SELECT ba.*
			FROM {$wpdb->prefix}business_addresses As ba
			WHERE ba.questionnaire_id ='". $res_about_us['id']."'";
						
		$arrBusinessDetails = $wpdb->get_results($qry_business,  ARRAY_A );
			
	} else {

		   // wp_session_unset();
			/*$wp_session = WP_Session::get_instance(); */
		$unique_id  = md5(uniqid(rand(), true));
		/* $wp_session['number'] = $unique_id; */
		$_SESSION['number']= $unique_id;
	}

	//$url ="http://localhost/blacklistdir_project/packages/";
	$url = "http://blacklistdir.rebelute.in/member-packages/";
		
					
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

		/*if(trim($_POST['phone_number']) === '') {
			$lastnameError = esc_html__( 'Please enter a Phone Number.', 'classify' );
			$hasError = true;
		} else {
			$phone_number = trim($_POST['phone_number']);
		}*/
		
		$web_address = trim($_POST['web_address']);
		$business_purpose = trim($_POST['business_purpose']);
		$category_name = $_POST['category_name'];
		if(isset($_POST['sub_category_name'])) {
			$subcategory = $_POST['sub_category_name'];
		} else {
			$subcategory = 0;
		}	
		$owner    = trim($_POST['owner']);
		$business_role = trim($_POST['business_role']);
		$phone    = $_POST['contact']; //numeric value use: %d
		$state    = trim($_POST['state']); //string value use: %s
		$country = trim($_POST['country']); //string value use: %s
		$zipcode = trim($_POST['zipcode']); //string value use: %s
		$city   = $_POST['city']; //string value use: %s
		
		$street_address   = $_POST['street_address']; //string value use: %s
		$address   = trim($_POST['address']); //string value use: %s
		$contact   = $_POST['contact']; //string value use: %s
		
		$hidden_business = $_POST['hid_business'];
		
		
		if( 1 == $hidden_business ) {
			$mobile_city = '';
			$mobile_state = '';
			$mobile_country = '';
			$mobile_contact = '';
			$mobile_zipcode = '';
		} else {
			$mobile_city = trim( $_POST['mobile_city'] );
			$mobile_state = trim( $_POST['mobile_state'] );
			$mobile_country = trim( $_POST['mobile_country'] );
			$mobile_contact = $_POST['mobile_contact'];
			$mobile_zipcode = trim($_POST['mobile_zipcode']);
		}
		$il_status = 'Outside';
		if( 'Illinois' == $state ) {
			$il_status = 'Inside';
		}

		global $wpdb;
		
		/*$wp_session = WP_Session::get_instance();
		$data = json_decode($wp_session->json_out() ); */
		if( empty( $_SESSION['questionnaire_id'] )) {
			
			 $sql = "INSERT INTO `wp_questionnaire` 
		   (`owner`, `business_role`, `firstname`, `lastname`, `mailing_address`, `street_address`, `address`, `city`, `state`, `country`,`zipcode`, `email`, `contact`, `product_id`, `banner_id`,  `price`,  `image`, `banner_url`,`business_name`, `web_address`, `category` , `subcategory`, `business_purpose`, `number`,`mob_business_city`, `mob_business_state`, `mob_business_country`, `mob_business_contact`, `mob_business_zipcode`,`il_status` )
		   values ('$owner','$business_role','$firstname','$lastname', '', '$street_address', '$address',
				 '$city', '$state', '$country','$zipcode', '$email_address', '$contact', '$product','$banner_id', '$price','$data_uri','$banner_url', '$business_name', '$web_address', '$category_name' , '$subcategory' , '$business_purpose', '$unique_id', '$mobile_city', '$mobile_state', '$mobile_country', '$mobile_contact', '$mobile_zipcode','$il_status' )";


			
			$wpdb->query($sql);
		} else {
			 $sql = " UPDATE 
							`wp_questionnaire` 
						SET 
						`owner` = '$owner',
						`business_role` = '$business_role',
						`firstname` = '$firstname',
						`lastname` ='$lastname',
						`street_address` = '$street_address',
						`address` = '$address',
						`city` = '$city',
						`state` = '$state',
						`zipcode` = '$zipcode',
						`country` = '$country',
						`email` = '$email_address',
						`contact` = '$contact',
						`product_id` = '$product',
						`banner_id` = '$banner_id',
						`price` = '$price',
						`image`= '$data_uri',
						`banner_url` = '$banner_url',
						`business_name` = '$business_name',
						`web_address` = '$web_address',
						`category` = '$category_name',
						`subcategory` = '$subcategory',
						`business_purpose` = '$business_purpose',
						`mob_business_city` = '$mobile_city',
						`mob_business_state` = '$mobile_state',
						`mob_business_country` = '$mobile_country',
						`mob_business_zipcode` = '$mobile_zipcode',
						`mob_business_contact` = '$mobile_contact',
						`il_status`	= '$il_status'
						WHERE 
							`wp_questionnaire`.`id` = '".$_SESSION['questionnaire_id']."'";
						
							$wpdb->query($sql);
		}
		

		if( empty( $_SESSION['questionnaire_id'] )) {

				$lastInsertId = $wpdb->insert_id; 
				$_SESSION['questionnaire_id'] = $lastInsertId; 
				
				$hidden_business = $_POST['hid_business'];

				if( 1 == $hidden_business ) {
				   
					if(isset($_POST['business_contact']) && !empty($_POST['business_contact'] ) ) {
					
						$businessContacts 	= $_POST['business_contact'];
						$streetAddr		= $_POST['street_add'];
						$bussinessAddr		= $_POST['bussiness_addr'];
						$bussinessCity		= $_POST['business_city'];
						$businessState		= $_POST['business_state'];
						$businessZipcode	= $_POST['business_zipcode'];
						$businessCountry	= $_POST['business_country'];

					/* $cntAddr = 0; */

						foreach( $businessContacts as $index => $businessContact ){
							
							$street = trim($streetAddr[$index]);
							$business_addr = trim($bussinessAddr[$index]);
							$bussn_city = trim($bussinessCity[$index]);
							$bussn_state = trim($businessState[$index]);
							$bussn_zip = trim($businessZipcode[$index]);
							$bussn_contact = trim($businessContacts[$index]);
							$bussn_country = trim($businessCountry[$index]);
							$price = 50;           
							$sql_business = "INSERT INTO `wp_business_addresses` (`questionnaire_id`, `street_address`, `address`, `city`, `state`, `country`, `ziocode`, `contact`, `price`, `product_id`) VALUES ('$lastInsertId', '$business_addr', '$street' ,'$bussn_city', '$bussn_state', '$bussn_country', '$bussn_zip', '$bussn_contact', '$price', '2652' )";

							$wpdb->query($sql_business);
										  /* $cntAddr++;*/
						}
						/*if ( 0 != $cntAddr ) {
						$addr_product = '2652';
						$woocommerce->cart->add_to_cart($addr_product, $cntAddr );
					}*/
				}	
				
			}
		} else {
			
			$hidden_business = $_POST['hid_business'];

			if( 1 == $hidden_business ) {
				
				$businessContacts 	= $_POST['business_contact'];
				$streetAddr		= $_POST['street_add'];
				$bussinessAddr		= $_POST['bussiness_addr'];
				$bussinessCity		= $_POST['business_city'];
				$businessState		= $_POST['business_state'];
				$businessCountry	= $_POST['business_country'];
				
				$businessZipcode	= $_POST['business_zipcode'];
			
				$qry_busn = "SELECT ba.id
					FROM {$wpdb->prefix}business_addresses As ba
					WHERE ba.questionnaire_id ='". $_SESSION['questionnaire_id'] ."'";
							
				$arrBusinessIds = $wpdb->get_results($qry_busn,  ARRAY_A );


				
				if(!empty( $arrBusinessIds )) {
					$arrIds = array();
					foreach( $arrBusinessIds as $keyIndex => $business_det ){
						$arrIds[] = $business_det['id'];
					}	
					$address = $_POST['addres_id'];
					/* $cntAddr = 0; */
							
					if(isset($_POST['addres_id']) && isset($_POST['business_contact'])) {
						$address = $_POST['addres_id'];
						foreach( $businessContacts as $key => $addr_id ) {
							
							if( array_key_exists( $key, $address ) ) {
								
								$street = trim($streetAddr[$key]);
								$business_addr = trim($bussinessAddr[$key]);
								$bussn_city = trim($bussinessCity[$key]);
								$bussn_state = trim($businessState[$key]);
								$bussn_zip = trim($businessZipcode[$key]);
								$bussn_contact = trim($businessContacts[$key]);
								$bussn_country = trim($businessCountry[$key]);
							
								$price = 50;
							   
							        $business_id = $address[$key];
								 $sql_business = 
									"UPDATE 
											`wp_business_addresses` 
									SET 
										`street_address` = '$business_addr',
										`address` = '$street',
										`city` = '$bussn_city',
										`state` = '$bussn_state',
										`ziocode` = '$bussn_zip',
										`country`= '$bussn_country',
										`contact` = '$bussn_contact',
										`price` = '$price'
									WHERE
										`wp_business_addresses`.`questionnaire_id` = '".$_SESSION['questionnaire_id']."' and `wp_business_addresses`.`id` = '".$business_id."'";
									
								$wpdb->query($sql_business); 
							} else {
								
								$street = trim($streetAddr[$key]);
								$business_addr = trim($bussinessAddr[$key]);
								$bussn_city = trim($bussinessCity[$key]);
								$bussn_state = trim($businessState[$key]);
								$bussn_zip = trim($businessZipcode[$key]);
								$bussn_contact = trim($businessContacts[$key]);
								$bussn_country = trim($businessCountry[$key]);
								$price = 50;
								
								$sql_business = "INSERT INTO `wp_business_addresses` (`questionnaire_id`, `street_address`, `address`, `city`, `state`, `ziocode`,`country`,`contact`,`price`,`product_id`) VALUES ('".$_SESSION['questionnaire_id']."', '$business_addr', '$street' ,'$bussn_city', '$bussn_state', '$bussn_country' ,'$bussn_zip', '$bussn_contact', '$price','2652')";
								

								$wpdb->query($sql_business);
							}
								$cntAddr++;
						}
						/*if( 0 != $cntAddr ) {
							$addr_product = '2652';
							$woocommerce->cart->add_to_cart($addr_product, $cntAddr );
						}*/

					}	
					$missing = array_diff($arrIds, $address);
					
					if( !empty($missing)) {
						$deletedElement = implode(',', $missing );
						$deletedElement = trim( $deletedElement, ',');
						
						$wpdb->query('DELETE from wp_business_addresses where id IN ('. $deletedElement.')');
					}
				} else {
									
					if(isset($_POST['business_contact'])) {
						//$cntAddr= 0;
						foreach( $businessContacts as $index => $businessContact ){
							
							$street = trim($streetAddr[$index]);
							$business_addr = trim($bussinessAddr[$index]);
							$bussn_city = trim($bussinessCity[$index]);
							$bussn_state = trim($businessState[$index]);
							$bussn_zip = trim($businessZipcode[$index]);
							$bussn_contact = trim($businessContacts[$index]);
							$bussn_country = trim($businessCountry[$index]);
							$price = 50;
							
							$sql_business = "INSERT INTO `wp_business_addresses` (`questionnaire_id`, `street_address`, `address`, `city`, `state`, `ziocode`, `country` ,`contact`,`price`, `product_id`) VALUES ('".$_SESSION['questionnaire_id']."', '$business_addr', '$street' ,'$bussn_city', '$bussn_state', '$bussn_zip', '$bussn_country', '$bussn_contact', '$price', '2652')";

							$wpdb->query($sql_business);
							//$cnt++;
						}
										   
					}
					/*if( 0 != $cntAddr ) {
						$addr_product = '2652';
						$woocommerce->cart->add_to_cart($addr_product, $cntAddr );
					}*/
				}	
				
			} else {
				
				$address = $_POST['addres_id'];
							if(!empty( $address)) {
					$address = implode( ',', $address);
					$address = trim($address, ',');
				
					$wpdb->query('DELETE from wp_business_addresses where id IN ('. $address.')');
							}
			}
					$qry_business = "SELECT ba.*
						FROM {$wpdb->prefix}business_addresses As ba
						WHERE ba.questionnaire_id ='". $_SESSION['questionnaire_id']."'";
							
				$arrBusinessDetails = $wpdb->get_results($qry_business,  ARRAY_A );

				/* if ( 0 != count($arrBusinessDetails) ) { 
						$cntAddr = count($arrBusinessDetails);
						$addr_product = '2652';
						$woocommerce->cart->add_to_cart($addr_product, $cntAddr );

				} */	
		
		}

		?>
		<script type="text/javascript">
				   
				   window.location='<?php echo $url;?>';
			</script>
	<?php 
	} 

	$states['US'] = array('Alabama'=>"Alabama",  

	'Alaska'=>"Alaska",  

	'Arizona'=>"Arizona",  

	'Arkansas'=>"Arkansas",  

	'California'=>"California",  

	'Colorado'=>"Colorado",  

	'Connecticut'=>"Connecticut",  

	'Delaware'=>"Delaware",  

	'District Of Columbia'=>"District Of Columbia",  

	'Florida'=>"Florida",  

	'Georgia'=>"Georgia",  

	'Hawaii'=>"Hawaii",  

	'Idaho'=>"Idaho",  

	'Illinois'=>"Illinois",  

	'Indiana'=>"Indiana",  

	'Iowa'=>"Iowa",  

	'Kansas'=>"Kansas",  

	'Kentucky'=>"Kentucky",  

	'Louisiana'=>"Louisiana",  

	'Maine'=>"Maine",  

	'Maryland'=>"Maryland",  

	'Massachusetts'=>"Massachusetts",  

	'Michigan'=>"Michigan",  

	'Minnesota'=>"Minnesota",  

	'Mississippi'=>"Mississippi",  

	'Missouri'=>"Missouri",  

	'Montana'=>"Montana",

	'Nebraska'=>"Nebraska",

	'Nevada'=>"Nevada",

	'New Hampshire'=>"New Hampshire",

	'New Jersey'=>"New Jersey",

	'New Mexico'=>"New Mexico",

	'New York'=>"New York",

	'North Carolina'=>"North Carolina",

	'North Dakota'=>"North Dakota",

	'Ohio'=>"Ohio",  

	'Oklahoma'=>"Oklahoma",  

	'Oregon'=>"Oregon",  

	'Pennsylvania'=>"Pennsylvania",  

	'Rhode Island'=>"Rhode Island",  

	'South Carolina'=>"South Carolina",  

	'South Dakota'=>"South Dakota",

	'Tennessee'=>"Tennessee",  

	'Texas'=>"Texas",  

	'Utah'=>"Utah",  

	'VT'=>"Vermont",  

	'Virginia'=>"Virginia",  

	'Washington'=>"Washington",  

	'West Virginia'=>"West Virginia",  

	'Wisconsin'=>"Wisconsin",  

	'Wyoming'=>"Wyoming");

	?>	

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

						<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data" onsubmit="return validate()">
						 <!-- Tab panes -->
	  <div id="responsive-elements" class="tab-content">

						 <div role="tabpanel" class="tab-pane active" id="profile">
		  <fieldset class="input-title">
							<label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3"><?php _e('Owner', 'classify') ?></label>
							<div class="padl10 field-type-list-boolean field-name-field-featured field-widget-options-onoff form-wrapper" id="edit-field-featured">

									<label class="option checkbox control-label" for="edit-field-featured-und">
										<input type="radio" id="owner" name="owner" value="Yes" <?php if('Yes' == $owner){ ?> checked="checked"<?php } ?> ><?php _e('Yes', 'classify') ?> 
									</label>
	<label class="option checkbox control-label" for="edit-field-featured-und">
										<input type="radio" id="owner" name="owner" value="No" <?php if('No' == $owner){ ?> checked="checked"<?php } ?> ><?php esc_html_e( 'No', 'classify' ); ?>
									</label>
										</div>
						</fieldset>	
	<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="<?php if('No' == $owner){ ?>display:block<?php }else{ ?> display:none <?php } ?>" id="lbl_business">Role in the business</label>
								<input type="text" id="business_role" name="business_role" value="<?php if('No' == $owner){ echo $business_role; } ?>" size="60" class="form-text required input-textarea half" placeholder="Role in the business" style="<?php if('No' == $owner){ ?>display:block<?php }else{ ?>display:none <?php } ?>" maxlength="50" >
								
								
								<div class="clearfix"></div>

								<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Name<span class="gfield_required">*</span></label>
		
		
		<div class="Width_Form_Fixed">
								<input type="text" id="firstname" name="firstname" value="<?php echo $firstname;?>" size="60" class="form-text required input-textarea half" placeholder="First" maxlength="50">
<div id="first-err"></div>
								
								</div>
								
								<div class="Width_Form_Fixed">
							<input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" size="60" class="form-text required input-textarea half" placeholder="Last" maxlength="50">	
								<div id="last-err"></div>
								</div>
								<div class="clearfix"></div>

								<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Mailing Address<span class="gfield_required">*</span></label>

	<div class="Width_Form_Fixed">
	<input type="text" id="street_address" name="street_address" value="<?php echo $street_address; ?>" class="form-text required input-textarea half" placeholder="Street Address" maxlength="255">
	<div id="street_addr_err"></div>
								</div>
								
								<div class="Width_Form_Fixed">
								<input type="text" id="address1" name="address" value="<?php echo $address; ?>" class="form-text required input-textarea half" placeholder="Address Line 2">
</div>

	<div class="Width_Form_Fixed">
								<input type="text" id="city" name="city" value="<?php echo $city; ?>" class="form-text required input-textarea half" placeholder="City" maxlength="50">
	<div id="city_err"></div>
	</div>

	<div class="Width_Form_Fixed">							
<select id="state" name="state" class="form-select">
								<option value="">Select State</option>
								<?php foreach ($states['US'] as $key => $val) { ?>
								<option value="<?php echo $key; ?>" <?php if( $key == $state ) {?> selected <?php } ?> ><?php echo $val; ?></option>
									<?php } ?>
								</select>
	<div id="state_err"></div>
	</div>
							
	<div class="clearfix"></div>
	<div class="Width_Form_Fixed">
	<input type="text" id="zipcode" name="zipcode" value="<?php echo $zipcode; ?>" class="form-text required input-textarea half" placeholder="zipcode" maxlength="6">
	<div id="zipcode_err"></div>
	</div>	
	<div class="Width_Form_Fixed">
	<input type="text" id="country" name="country" value="<?php echo $country;?>" class="form-text required input-textarea half" placeholder="Country" maxlength="50">
	<div id="country_err"></div>
	</div>		
	<div class="clearfix"></div>

	<div class="Width_Form_Fixed">
<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Contact Number<span class="gfield_required">*</span></label>
<input type="text" id="contact" name="contact" value="<?php echo $contact; ?>" class="form-text required input-textarea half" placeholder="Contact Number" maxlength="15">
		
		<div id="contact-err"></div>
	</div>

	<div class="Width_Form_Fixed"><label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Email Address<span class="gfield_required">*</span></label>

<input type="text" id="email_address" name="email_address" value="<?php echo $email; ?>" class="form-text required input-textarea half" placeholder="Email Address" maxlength="150">
								
								<br clear="all">
		<div id="email-err"></div>

	</div>

						
							
	<div class="clearfix"></div>
							<div class="center-div publish-ad-button">
								
								<input type="hidden" name="submitted" id="next" value="true" />
		<input  href="#business" aria-controls="business" role="tab" data-toggle="tab" class="apend_active" id="edit-next" name="op" value="Next" type="submit">
			<?php _e('', 'classify') ?>
			</input>
							</div>

		</div>
		
		  <div role="tabpanel" class="tab-pane" id="business">
		 <label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Business Name<span class="gfield_required">*</span></label>
	<input type="text" id="business_name" name="business_name"  value="<?php echo $business_name; ?>" size="60" class="form-text required input-textarea full" placeholder="Business Name" maxlength="50">
								
	<div class="clearfix"></div>
	<div id="business-err"></div>
	<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Web Address</label>
	<input  type="text" id="web_address" name="web_address" value="<?php echo $web_address; ?>" size="60" class="form-text required input-textarea full" placeholder="Web Address"  maxlength="100">
	<div class="clearfix"></div>

	<div id="business_section">
	<?php 
	$total = count($arrBusinessDetails);
	?>
	   <div  id="business_div" style="<?php if( empty($_SESSION['questionnaire_id']) || 0 != $total ) { ?> display:block; <?php } else { ?> display:none;  <?php } ?>">
	<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Business Address<a href="javascript:;" id="remove_addr">This Business Has No Address</a></label>
	</div>	

<?php 
$total = count($arrBusinessDetails);
	
if( empty($_SESSION['questionnaire_id']) ||  0 == $total  ) { ?>
 <div id="primary_addr" style="
 <?php if( empty($_SESSION['questionnaire_id']) ) { ?> display:block; <?php } elseif( 0 == $total) { ?> display:none; <?php } else { ?> display:block; <?php } ?>">

	<input type="text" id="street_add" name="street_add[]" value="" class="form-text required input-textarea full" placeholder="Street Address">
								
								
								<input type="text" id="bussiness_addr" name="bussiness_addr[]" value="" class="form-text required input-textarea full" placeholder="Address Line 2">
								 <div class="Width_Form_Fixed">
								<input type="text"  id="business_city" name="business_city[]" value="" class="form-text required input-textarea half" placeholder="City"> </div>

							<div class="Width_Form_Fixed">
								<select id="business_state" name="business_state[]" class="form-select">
								<option value="">Select State</option>
								<?php foreach ($states['US'] as $key => $val) { ?>
								<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
									<?php } ?>
								</select>

							</div>

						<div class="clearfix"></div>
	<div class="Width_Form_Fixed">
							<input type="text" id="business_zipcode" name="business_zipcode[]" value="" class="form-text required input-textarea half" placeholder="zipcode"></div>
	 <div class="Width_Form_Fixed">
	<input type="text" id="business_country" name="business_country[]" value="" class="form-text required input-textarea half" placeholder="Country" >
	</div>
				
	<div class="Width_Form_Fixed">							
<input type="text" id="business_contact0" name="business_contact[]" value="" size="60" class="form-text required input-textarea half contact_business" placeholder="Contact Number">
	<div id="business_contact_err_0"></div>
	</div>
	<div class="clearfix"></div>
								
	<div class="spoouter">
		<a href="javascript:;" id="add_address">Add Another Address</a>
		<center><span class="menu-item-text"><p><span class="menu-text">Each Additional Address is $50.</span></p></span></center>
	</div>
 </div>
<?php } else {  ?>
 <div id="primary_addr">
  <?php 
		if( 0 != $total ) { 
			foreach( $arrBusinessDetails as $keyIndex => $business_details ){ ?>
		<div>
			<?php if(0 != $keyIndex ) { ?>
			<div>
				<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Business Address</label>
			</div>
	 <?php } ?>
			
			<input type="text" id="street_add<?php echo $keyIndex;?>" name="street_add[]" value="<?php echo $business_details['street_address']; ?>" class="form-text required input-textarea full" placeholder="Street Address"/>
			

			<input type="text" id="bussiness_addr<?php echo $keyIndex;?>" name="bussiness_addr[]" value="<?php echo $business_details['address']; ?>" class="form-text required input-textarea full" placeholder="Address Line 2"/>
				
	 <div class="Width_Form_Fixed">
			<input type="text"  id="business_city<?php echo $keyIndex;?>" name="business_city[]" value="<?php echo $business_details['city']; ?>" class="form-text required input-textarea half" placeholder="City"/>
			</div>
			 <div class="Width_Form_Fixed">
				<select id="business_state<?php echo $keyIndex;?>" name="business_state[]" class="form-select">
					<option value="">Select State</option>
					<?php foreach ($states['US'] as $key => $val) { ?>
				<option value="<?php echo $key; ?>" <?php if($key == $business_details['state']){ ?> selected="selected" <?php } ?>><?php echo $val; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="clearfix"></div>
 <div class="Width_Form_Fixed">
			<input type="text" id="business_zipcode<?php echo $keyIndex;?>" name="business_zipcode[]" value="<?php echo $business_details['ziocode']; ?>" class="form-text required input-textarea half" placeholder="zipcode"/>
</div>
			 <div class="Width_Form_Fixed">
	<input type="text" id="business_country" name="business_country[]" value="<?php echo $business_details['country']; ?>" class="form-text required input-textarea half" placeholder="Country">
	</div>
			
			
			
			 <div class="Width_Form_Fixed">
			<input type="text" id="business_contact<?php echo $keyIndex;?>" name="business_contact[]" value="<?php echo $business_details['contact']; ?>" size="60" class="form-text required input-textarea half contact_business" placeholder="Contact Number"/>
			<div id="business_contact_err_<?php echo $keyIndex;?>"></div>
		</div>
		<div class="clearfix"></div>
		<?php if(0 == $keyIndex ) { ?>
			<div class="spoouter">
				<a href="javascript:;" id="add_address">Add Another Address</a>
				<center><span class="menu-item-text"><p><span class="menu-text">Each Additional Address is $50.</span></p></span></center>
			</div>
		<?php } else {
			$p = $keyIndex + 1;
		?><a href="javascript:;" id="rem_addr<?php echo $keyIndex;?>" class="remove_field" style="<?php if($total == $p ){ ?>display:block; <?php } else { ?> display:none; <?php } ?>">Remove Address</a>
		<?php } ?>
		<input type="hidden" id="addres_id<?php echo $keyIndex;?>" name="addres_id[]" value="<?php echo $business_details['id']; ?>" />
		</div>
	 <?php } ?>
	<?php } ?>
</div>
<?php } ?>
	
	  <div class="input_fields_wrap2"></div>
	  </div>
	<div style="<?php if( empty($_SESSION['questionnaire_id']) ) { ?> display:none; <?php } elseif( 0 == $total) { ?> display:block; <?php } elseif(0 != $total) { ?> display:none; <?php } else { ?>display:block; <?php } ?>" id="add_bussn_addr">
<a href="javascript:;" id="insert_addr">Add business address</a></div>

	<!-- Mobile business -->
	
	<div id="mobile_buinsess_div" style="<?php if( !empty($_SESSION['questionnaire_id']) && 0 == $total){ ?> display:block <?php } else { ?> display:none <?php } ?>">
	<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Mobile Business<span class="gfield_required">*</span></label>
								
	 <div class="Width_Form_Fixed">
	<input type="text"  id="mobile_city" name="mobile_city" value="<?php echo $mob_business_city; ?>" class="form-text required input-textarea half" placeholder="City" maxlength="50">
	<div id="mobile_city_err"></div>
	</div>
	 <div class="Width_Form_Fixed">

								<select id="mobile_state" name="mobile_state" class="form-select">
								<option value="">Select State</option>
								<?php foreach ($states['US'] as $key => $val) { ?>
								<option value="<?php echo $key; ?>" <?php if( $key == $mob_business_state){?> selected <?php } ?> ><?php echo $val; ?></option>
									<?php } ?>
								</select>
							<div id="mobile_state_err"></div>
								</div>
	<div class="clearfix"></div>
	 <div class="Width_Form_Fixed">
	<input type="text" id="mobile_zipcode" name="mobile_zipcode" value="<?php echo $mob_business_zipcode;?>" class="form-text required input-textarea half" placeholder="Zipcode">
	<div id="mobile_zipcode_err"></div>
	</div>
	 <div class="Width_Form_Fixed">
	<input type="text" id="mobile_country" name="mobile_country" value="<?php echo $mob_business_country;?>" class="form-text required input-textarea half" placeholder="Country">
	<div id="mobile_country_err"></div>
	</div>

		
	 <div class="Width_Form_Fixed">		
	<input  type="text" id="mobile_contact" name="mobile_contact" value="<?php echo $mob_business_contact; ?>" size="60" class="form-text required input-textarea half" placeholder="Contact Number">
	<div id="mobile_contact_err"></div>
	</div>
	</div>
	
	<div class="clearfix"></div>
	
	
			
		 <!--<div class="Width_Form_Fixed">		
<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Phone Number<span class="gfield_required">*</span></label>
				
								<input type="text" id="phone_number" name="phone_number" value="<?php echo $business_phone_number; ?>" size="60" class="form-text required input-textarea half" placeholder="Phone Number" maxlength="15"><div class="clearfix"></div>
	<div id="phone-no-err"></div>
</div> -->

							
								 <div class="Width_Form_Fixed">

<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Business Type<span class="gfield_required">*</span></label>			
								<div id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
								<div class="views-widget">
									<div class="control-group form-type-select form-item-field-category form-item">
										<div class="controls"> 
											<select id="category" name="category_name" class="form-select">
														
												<option value=""></option>
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
															<option value="<?php echo $catID; ?>" <?php if( $catID == $category ){?> selected="selected" <?php } ?> ><?php echo $cat->cat_name; ?></option>
																				
													

													<?php } 
												} ?>

											</select>
										</div>
									</div>
								</div>
							</div>
                                                   <div id="cat-err"></div>
                                               </div>
											   <?php if( empty($_SESSION['questionnaire_id']) ) { ?>
											   	 <div class="Width_Form_Fixed" id="sub_cat_id" style="display:none;">


                                               </div>
											   <?php } else { 
					
				$categories = get_categories(array('child_of' => $category));
?> <div class="Width_Form_Fixed" id="sub_cat_id">
   <label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Subcategory</label>			
								<div id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
								<div class="views-widget">
									<div class="control-group form-type-select form-item-field-category form-item">
										<div class="controls">
										 <?php if(!empty($subcategory)){ ?>
											<select id="subcategory" name="sub_category_name" class="form-select">
														
												<option value=""></option>
												<?php
												
													foreach ($categories as $cat) {
														
															$catID = $cat->cat_ID;
														?>
															<option value="<?php echo $catID; ?>" <?php if( $catID == $subcategory ){?> selected="selected" <?php } ?> ><?php echo $cat->cat_name; ?></option>
																				
													

													<?php
												} ?>

											</select>
										 <?php } else { ?>
													<div class="Width_Form_Fixed">No Sub Category Found</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
</div>	
											   <?php  } ?>
											
							<br clear="all">

							<fieldset class="input-title">
	<label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Briefly describe the purpose of the business</label>
								<textarea name="business_purpose" id="video" cols="15" rows="5" placeholder="Briefly describe the purpose of the business" maxlength="1000"><?php echo $business_purpose; ?></textarea>
								<p class="help-block"></p>
							</fieldset>	
							
							<br clear="all">	
								

							<div class="center-div publish-ad-button">
								

	<input  href="#business" aria-controls="business" role="tab" data-toggle="tab" class="apend_active" type="submit" value="Back" id="edit-back">
			<?php _e('', 'classify') ?>
			</input>

								<input type="hidden" name="submitted" id="submitted" value="true" />
	<input type="hidden" id="hid_business" name="hid_business" value="1" />
								<input class="" id="edit-submit" name="op" value="Lets get Listed" type="submit"><?php _e('', 'classify') ?></input>
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


	 <script type='text/javascript' src='http://blacklistdir.rebelute.in/wp-content/themes/classify/js/jquery.maskedinput.js'></script>
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
			
			

					$('#about_you a').attr("href", "javascript:;");

					var first = $('#firstname').val();
					first = first.trim();

			 var last  =  $('#lastname').val();
					 last = last.trim();
				
					 var city = $('#city').val();
					 city = city.trim();
					  
					 var zipcode = $('#zipcode').val();
					 zipcode = zipcode.trim();

					 var state = $('#state').val();
					 state = state.trim();
					 
					 
					 var country = $('#country').val();
					 country = country.trim();

					 var street_address  = $('#street_address').val();
					 street_address  = street_address.trim();

			 var email_address =  $('#email_address').val();
					 email_address = email_address.trim();

			 var contact =  $('#contact').val();
					 contact = contact.trim();

			  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			 var valid = 'true';
			 $('#first-err').empty();
			 $('#last-err').empty();
					 $('#street_addr_err').empty();
			 $('#city_err').empty();
			 $('#state_err').empty();
					 $('#zipcode_err').empty();
					 $('#country_err').empty();
			 $('#email-err').empty();
			 $('#contact-err').empty(); 
			 
					if( '' == first ) {
						$('#first-err').html( "<span style='color:red;margin-left:10px;'>Firstname is required.</span>" );
					  $('#firstname').focus();
				  valid = 'false';
							  return false;
					}
						if( '' == last ) {
							  $('#last-err').html( "<span style='color:red;margin-left:10px;'>Lastname is required.</span>" );
				  $('#lastname').focus();
				  valid = 'false';
							  return false;
					} 
						if( '' == street_address ) {
							  $('#street_addr_err').html( "<span style='color:red;margin-left:10px;'>Street address is required.</span>" );
				  $('#street_address').focus();
				  valid = 'false';
							  return false;
					}

					   if( '' == city) {
							  $('#city_err').html( "<span style='color:red;margin-left:10px;'>City is required.</span>" );
				  $('#city').focus();
				  valid = 'false';
							  return false;
					}
					if( '' == state) {
							  $('#state_err').html( "<span style='color:red;margin-left:10px;'>State is required.</span>" );
				  $('#state').focus();
				  valid = 'false';
							  return false;
					}
						if( '' == zipcode) {
							  $('#zipcode_err').html( "<span style='color:red;margin-left:10px;'>Zipcode is required.</span>" );
				  $('#zipcode').focus();
				  valid = 'false';
							  return false;
					}
						if( '' == country) {
							  $('#country_err').html( "<span style='color:red;margin-left:10px;'>Country is required.</span>" );
				  $('#country').focus();
				  valid = 'false';
							  return false;
					}
						if( '' == contact ) {
							  $('#contact-err').html( "<span style='color:red;margin-left:10px;'>Contact number is required.</span>" );
				  $('#contact').focus();
				  valid = 'false';
							  return false;
					} 
						
						if( '' == email_address ) {
							  $('#email-err').html( "<span style='color:red;margin-left:10px;'>Email address is required.</span>" );
				  $('#email_address').focus();
				  valid = 'false';
							  return false;
					} 
		   
						if( '' != email_address ) {
				 email_address = email_address.trim();
				 if( false == re.test(email_address)) {
					 
					  $('#email-err').html( "<span style='color:red;margin-left:10px;'>Please enter a valid email address.</span>" );
					  $('#email_address').focus();
					  valid = 'false';
									  return false;
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
				 
				
				}
		}); 

			 $('.publish-ad-button #edit-back').click(function () {
			
			$('#about_you').show();
			$('#business').hide();
			$('#business').addClass('tab-pane');
			$('#profile').removeClass('tab-pane');
			$('#about_your_business').removeClass('active');
			$('#about_your_business').addClass('add_active');
			$('#about_you').addClass('active');
			$('#about_you').removeClass('add_active');
			
		});


	});	jQuery( document ).ready(function( $ ) {		jQuery(".contact_business").mask("(999) 999-9999");	});	
	jQuery.noConflict();
	jQuery( document ).ready(function(  ) {
		jQuery.mask.definitions['~'] = "[+-]";
		jQuery("#contact").mask("(999) 999-9999");
		jQuery("#phone_number").mask("(999) 999-9999");
			jQuery("#mobile_contact").mask("(999) 999-9999");
			
		
	});

	function validate() {

		var business_name = document.getElementById('business_name').value;
			 business_name = business_name.trim();

		/* var phone_number  = document.getElementById('phone_number').value; 
			 phone_number = phone_number.trim();*/
			
		 var mobile_div_style = jQuery("#mobile_buinsess_div").css('display');
	     
		 if( 'block' == mobile_div_style ) {		 
			 var mobile_city  = document.getElementById('mobile_city').value; 
				 mobile_city = mobile_city.trim();
			  
			 var mobile_state  = document.getElementById('mobile_state').value; 
				 mobile_state = mobile_state.trim();
				 
			 var mobile_zipcode  = document.getElementById('mobile_zipcode').value; 
				 mobile_zipcode = mobile_zipcode.trim();
				 
			 var mobile_country  = document.getElementById('mobile_country').value; 
				 mobile_country = mobile_country.trim();
				 
			 var mobile_contact= document.getElementById('mobile_contact').value; 
				 mobile_contact= mobile_contact.trim();
		 }
		 
		 var category  = document.getElementById('category').value; 
		 var allContacts = document.getElementsByName("business_contact[]");

		 var business =  jQuery("#business_div").css("display");

		 document.getElementById('business-err').innerHTML = "";
		/* document.getElementById('phone-no-err').innerHTML = "";*/
		 document.getElementById('cat-err').innerHTML = "";

		 if( 'block' == mobile_div_style ) {	
			 document.getElementById('mobile_city_err').innerHTML = "";
			 document.getElementById('mobile_state_err').innerHTML = "";
			 document.getElementById('mobile_zipcode_err').innerHTML = "";
			 document.getElementById('mobile_country_err').innerHTML = "";
			 document.getElementById('mobile_contact_err').innerHTML = "";
		 }
		 var valid = true;

		 if( '' == business_name ) { 
			document.getElementById('business-err').innerHTML = "<span style='color:red;margin-left:10px;'>Business name is required.</span>" ;
				document.getElementById('business_name').focus();
					valid = false;
					return false;
		 } 

		 if( 'block' == business ) {
			if( 0 == allContacts.length ) {
				document.getElementById('hid_business').value = 0;
			}
			 if(allContacts.length > 0 ) {
				  for (var i = 0; i < allContacts.length; i++) {
					 
					  if( 'undefined'!= document.getElementById('business_contact'+i)) {
							if ('' == allContacts[i].value) { 
					 
								document.getElementById('business_contact_err_'+i).innerHTML = "<span style='color:red;margin-left:10px;'>Contact number is required.</span>" ;
								if( 0 == i ) {
									document.getElementById('business_contact0').focus();
								} else {
									  document.getElementById('business_contact'+i).focus();
								}
								valid = false;
								break;
								 
							} else {
								document.getElementById('business_contact_err_'+i).innerHTML = "";
							}
						}	
					}
					if( false == valid ) {
							return false;
					} 
		 }
	   } else {
		   document.getElementById('hid_business').value = 0;
	   }
	   if( 'block' == mobile_div_style ) {
		   if( '' == mobile_city) { 
				document.getElementById('mobile_city_err').innerHTML = "<span style='color:red;margin-left:10px;'>City is required.</span>" ;
					document.getElementById('mobile_city').focus();
					valid = false;
					return false;
			} 
			if( '' == mobile_state) { 
				document.getElementById('mobile_state_err').innerHTML = "<span style='color:red;margin-left:10px;'>State is required.</span>" ;
					document.getElementById('mobile_state').focus();
					valid = false;
					return false;
			} 
		 
			if( '' == mobile_zipcode) { 
				document.getElementById('mobile_zipcode_err').innerHTML = "<span style='color:red;margin-left:10px;'>Zipcode is required.</span>" ;
					document.getElementById('mobile_zipcode').focus();
					valid = false;
					return false;
			} 
			if( '' == mobile_country) { 
				document.getElementById('mobile_country_err').innerHTML = "<span style='color:red;margin-left:10px;'>Country is required.</span>" ;
					document.getElementById('mobile_country').focus();
					valid = false;
					return false;
			} 
			if( '' == mobile_contact) { 
				document.getElementById('mobile_contact_err').innerHTML = "<span style='color:red;margin-left:10px;'>Contact number is required.</span>" ;
				document.getElementById('mobile_contact').focus();
				valid = false;
				return false;
			} 
				if( '' == phone_number ) { 
				document.getElementById('phone-no-err').innerHTML = "<span style='color:red;margin-left:10px;'>Phone number is required.</span>" ;
				document.getElementById('phone_number').focus();
				valid = false;
				return false;
			 }
	    }			

		 if( '' == category ) { 
			document.getElementById('cat-err').innerHTML = "<span style='color:red;margin-left:10px;'>Business type is required.</span>" ;
				document.getElementById('category').focus();
			valid = false;
					return false;
		 }
			  
			

			 if( false == valid ) {
			return false;
		 } 
                 
	}

	jQuery( document ).ready(function( $ ) {
		$("#myModal").hide();
		var max_fields      = 3; //maximum input boxes allowed
		var wrapper         = $(".input_fields_wrap2"); //Fields wrapper
		var add_button      = $("#add_address"); //Add button ID
	   var y = 1;
	  

		$(add_button).click(function(e){ //on add input button click
		   
    		var address_div = document.getElementsByName("business_contact[]");
			var x = address_div.length;
			var y = address_div.length;

			//e.preventDefault();
			if(x < max_fields){ //max input box allowed
				x++; //text box increment
				var p = x - 2;
				$(wrapper).append('<div><div><label class="gfield_label gfield_label_before_complex" for="input_1_30_3">Business Address</label></div><div><input type="text" id="street_add'+y+'" name="street_add[]" value="" class="form-text required input-textarea full" placeholder="Street Address"></div><input type="text" id="bussiness_addr'+y+'" name="bussiness_addr[]" value="" class="form-text required input-textarea full" placeholder="Address Line 2"><div class="clearfix"></div><div class="Width_Form_Fixed"><input type="text" id="business_city'+y+'" name="business_city[]" value="" class="form-text required input-textarea half" placeholder="City"></div><div><div class="Width_Form_Fixed"><select id="business_state'+y+'" name="business_state[]" class="form-select"><option value="">Select State</option><?php foreach ($states['US'] as $key => $val) { ?><option value="<?php echo $key; ?>"><?php echo $val; ?></option><?php } ?></select></div></div><div class="clearfix"></div><div class="Width_Form_Fixed"><input type="text" id="business_zipcode'+y+'" name="business_zipcode[]" value="" class="form-text required input-textarea half" placeholder="zipcode"></div><div class="Width_Form_Fixed"><input type="text" style="margin-left:5px" id="business_country'+y+'" name="business_country[]" value="" class="form-text required input-textarea half" placeholder="Country"></div><div class="Width_Form_Fixed"><input type="text" id="business_contact'+y+'" name="business_contact[]" value="" size="60" class="form-text required input-textarea half contact_business" placeholder="Phone Number"><div id="business_contact_err_'+y+'"></div></div><div class="clearfix"></div><a href="javascript:;" class="remove_field" id="rem_addr'+y+'">Remove Address</a></div>'); //add input box

				if($('#rem_addr'+p).length > 0) {
					$('#rem_addr'+p).hide();
				}
					
				y++;
			} else {
				alert( 'You can add maxiumum 3 business addresses.' );
			}
			jQuery(".contact_business").mask("(999) 999-9999");
		});
		$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
			
			var address_div = document.getElementsByName("business_contact[]");
			var p = address_div.length - 2;
				
			if($('#rem_addr'+p).length > 0) {
						
				$('#rem_addr'+p).show();
			}
			
			e.preventDefault(); $(this).parent('div').remove(); /* x--; */
		});
		 $('.remove_field').on("click", function(e){ //user click on remove text
		 
		   var address_div = document.getElementsByName("business_contact[]");
			var p = address_div.length - 2;
				
			if($('#rem_addr'+p).length > 0) {
						
				$('#rem_addr'+p).show();
			}
				var address_div = document.getElementsByName("business_contact[]");
				var x = address_div.length;
				e.preventDefault(); $(this).parent('div').remove(); x--;
			});
		 $('#remove_addr').click(function(e){ //on add input button click
		 
			document.getElementById('hid_business').value = '0';
			//$('#business_section').empty();
			$('#mobile_buinsess_div').show();
			$('#primary_addr').hide();
			$('#business_div').hide();
			$('#add_bussn_addr').show();
			$('.input_fields_wrap2').empty();
		 });
		 
		 $('#insert_addr').click(function(e){ //on add input button click
                         document.getElementById('hid_business').value = 1;
						 $('#mobile_buinsess_div').hide();
			 $('#add_bussn_addr').hide();
			 $('#primary_addr').show();
			 if($('#edit_addr').length > 0 ) {
				 $('#edit_addr').show();
			 }
			 $('#business_div').show();
		 });
		 $('#edit_addr').click(function(e){ //on add input button click
			 
			 $('#primary_addr').show();
			 $('#business_div').show();
		 });
		 
		 $('#category').change(function(e) {
			$("#sub_cat_id").hide();
			 var category = $(this).val();
			 $.ajax({
				type: "POST",
				url: "http://blacklistdir.rebelute.in/wp-admin/admin-ajax.php",
				data: "action=get_subcategory&category_id="+category,
				success: function( obj ){
					$("#sub_cat_id").show();
					$("#sub_cat_id").html(obj);
				}	
				    
			});
				
		 });	 

	});	
	</script>
	  
	<style>
	@media only screen and (max-width: 400px){
		
		.Width_Form_Fixed{
			
			width:100% !important;
			margin-left:10px
			
		}
		
		.Width_Form_Fixed input{
			
		
			margin-left:0px !important;
			width:100% !important;
			
		}
		
		
		#tab-1 .input-textarea.half{
			
			width:87% !important;
			
		}
		
		.Width_Form_Fixed #state {
			
		
			width:94% !important;
			
		}
		
		
		
		
	}
	
	@media only screen and (min-width: 500px){
	.Width_Form_Fixed input{
			
		
			
			width:95% !important;
			
		}
		
		
	
	
	}
	
	@media only screen and (max-width: 500px){
	#tab-1 .input-textarea.half{
		
		width:78% !important
		
	}
	
	}
	
	@media only screen and (min-width: 400px){
	.Width_Form_Fixed #state {
			
		
			width:96% !important;
			
		}
	}


#street_addr_err span {
float:left;
}


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
	#category_chosen {
		width: 480px !important;
		min-height: 44px;
		min-width: 79px;
		border-left: 1px solid #dedede;
	}
	#state_chosen {
	 display:none;
	}
	#business_state_chosen {
	   display:none;
	}
        #business_state0_chosen {
           display:none;
        }
	#mobile_state_chosen {

	   display:none;
	}
	#subcategory_chosen {
		display:none;
	}
	#state {
	 display:block !important;
	}
	#business_state {
	   display:block !important;
	}
	#business_state0 {
	   display:block !important;
	}
	#mobile_state {

	   display:block !important;
	}
	#upload-ad input {
		color: #4e4e4e !important;
	}
	#category_chosen {
	   display:none;
	}
	#category {
	   display:block !important; 
	}
	#subcategory {
	   display:block !important; 
	}
       .textwidget #contact-form #contactName {
           width: 85% !important;
           border-radius: 2px;
        }
      .textwidget #contact-form #email {
            width: 85% !important;
           border-radius: 2px;
       }
      .textwidget #contact-form #commentsText {
           width: 100% !important;
          height: 82px !important;
          border-radius: 2px;
      }
      footer #contact-form #contactName, #contact-form #email, #contact-form #commentsText {
             height: 22px !important;
             width: 100% !important;
       }
     .input-textarea {
         background-color: #f8f8f8 !important;
         color: #888888 !important;
         font-size: 13px !important;
         height: 30px !important;
         padding-left: 50px !important;
         width: 707px !important;
     }
	</style>
	<?php get_footer(); ?>
