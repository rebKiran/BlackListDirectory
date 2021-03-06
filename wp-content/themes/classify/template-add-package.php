<?php
session_start();

/*
echo '<pre/>';
print_r($_SESSION);*/

wp_cache_flush();
/*
WC()->cart->empty_cart();
print_r(WC()->cart);*/

/**
 * Template Name: Add Package
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */


get_header(); ?>
<?php 


$query = "SELECT q.business_name,q.web_address,q.id
			FROM {$wpdb->prefix}questionnaire As q
			WHERE q.id = '".$_SESSION['questionnaire_id']."'";
						
$results = $wpdb->get_results($query,  ARRAY_A );
$mystate = $wpdb->get_row( "SELECT * FROM wp_questionnaire WHERE id = '".$_SESSION['questionnaire_id']."' ", ARRAY_A );

$res_state = trim($mystate['state']);


$mylisting = $wpdb->get_row( "SELECT * FROM wp_directory_listing WHERE questionnaire_id = '".$_SESSION['questionnaire_id']."' ", ARRAY_A );

$querystr = "
			   SELECT $wpdb->postmeta.meta_value as size, $wpdb->posts.post_title as label, $wpdb->posts.ID as id,wp_product_banner.product_id 
			   FROM $wpdb->posts, $wpdb->postmeta, wp_product_banner
			   WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
			   AND $wpdb->posts.ID = wp_product_banner.banner_id
			   AND $wpdb->postmeta.meta_key = '_banner_bc_size' 
			   AND $wpdb->posts.post_status = 'publish' 
			   AND $wpdb->posts.post_type = 'vbc_banners'
			   ORDER BY wp_product_banner.ID ASC
			";
			
$banner_sizes = $wpdb->get_results($querystr,ARRAY_A);


global $wpdb;
$valid = 'true';	
global $woocommerce;
if( 'Next' == $_POST['next_btn'] ) { 
	
	global $wpdb;
	
	$mylisting = $wpdb->get_row( "SELECT * FROM  wp_questionnaire q 
								WHERE 
									q.id = '".$_SESSION['questionnaire_id']."' 
								 ", ARRAY_A );
	

	if( 'Inside' == $mylisting['il_status'] ) { 

		$directory_listing = $wpdb->get_row( "SELECT id FROM  wp_directory_listing dl 
								WHERE 
									dl.questionnaire_id= '".$_SESSION['questionnaire_id']."' 
								 ", ARRAY_A );
                

		$banner 	= trim($_POST['banner']);
	   $name_in_caps 	= (isset($_POST['name_in_caps'])) ? 'Yes' : 'No';
		$bold_print 	= (isset($_POST['bold_print'])) ? 'Yes' : 'No';
		$border 	= (isset($_POST['border'])) ? 'Yes' : 'No';
		
		if(!is_array($directory_listing )) {
			$sql = "INSERT INTO `wp_directory_listing` (`questionnaire_id`,`name_in_caps`, `bold_print`, `border`, `product_id`, `token`) VALUES ('".$_SESSION['questionnaire_id']."','$name_in_caps', '$bold_print', '$border', '$banner', '$data->number')"; 
	  
			$wpdb->query($sql);
		} else {
			$business_id = $directory_listing['id'];
			
		   $sql = 
				"UPDATE 
						`wp_directory_listing` 
				SET 
					`name_in_caps` = '$name_in_caps',
					`bold_print` = '$bold_print',
					`border` = '$border',
					`product_id` = '$banner'
				WHERE
					`wp_directory_listing`.`questionnaire_id` = '".$_SESSION['questionnaire_id']."' and `wp_directory_listing`.`id` = '$business_id'";
			
			$wpdb->query($sql);
		}
         
      if('2077' == $banner) { 
             $url = "http://blacklistdir.rebelute.in/directory-listing/";	
		?>
	   <script type="text/javascript">
                  window.location='<?php echo $url;?>';
		</script>

      <?php } else {  
				
			if(is_array($_SESSION['wpc_generated_data']) && !empty($_SESSION['wpc_generated_data'])) { 
					 if(!empty($_SESSION['product']) && $banner == $_SESSION['product'] ) {
						 
						 $product = $_SESSION['product'];
						 $edit_id = array_keys($_SESSION['wpc_generated_data'][$product]);
						 $url = "http://blacklistdir.rebelute.in/design/edit/" . $_SESSION['product'] . '/' .$edit_id[0] ;
					 } else {
						  $url = "http://blacklistdir.rebelute.in/design-your-ads/";
					 }	 
				 } else {  
                   $url = "http://blacklistdir.rebelute.in/design-your-ads/";
				 } 
							
             ?>
	        <script type="text/javascript">
                     window.location='<?php echo $url;?>';
               </script>
<?php
         }
      } else {
		
		$url = "http://blacklistdir.rebelute.in/web-listing-details/";	
		
		$listing	= trim($_POST['listing']);
		$name_in_caps 	= (isset($_POST['name_in_caps']) ) ? 'Yes' : 'No';
		$bold_print 	= (isset($_POST['bold_print']) ) ? 'Yes' : 'No';
		$upload_logo 	= (isset($_POST['upload_logo']) ) ? 'Yes' : 'No';
		$deal 		= (isset($_POST['deal']) ) ? 'Yes' : 'No';
		
		if( 'Basic' == $listing ) {
		   $product_id = 2077;
		} else {
		   $product_id = 2082;
		}

		$arrListing 	= $wpdb->get_row( "SELECT * FROM  wp_website_listing  
								WHERE 
									questionnaire_id = '".$_SESSION['questionnaire_id']."' 
								 ", ARRAY_A );
			
		if(!is_array($arrListing)) { 
			$sql = "INSERT INTO `wp_website_listing` (`questionnaire_id`, `listing_type`, `name_in_caps`, `bold_print`, `upload_logo`, `product_id`,`deal`,`token`) 
			VALUES ('".$_SESSION['questionnaire_id']."', '$listing','$name_in_caps', '$bold_print', '$upload_logo ' , '$product_id','$deal', '$data->number')";
	       
		   
			$wpdb->query($sql);
			$lastInsertId = $wpdb->insert_id; 
			if ( $_FILES ) { 
					
			  $files = $_FILES['upload_attachment'];
			  $types = array('image/jpeg', 'image/gif', 'image/png', 'image/jpg', 'image/bmp');
			 
			  foreach ($files['name'] as $key => $value) {

					if ($files['name'][$key] && in_array( $files['type'][$key], $types )) {
						
						$file = array(
							'name'     => $files['name'][$key],
							'type'     => $files['type'][$key],
							'tmp_name' => $files['tmp_name'][$key],
							'error'    => $files['error'][$key],
							'size'     => $files['size'][$key]
						);
			 
						$_FILES = array("upload_attachment" => $file);
			 
						foreach ($_FILES as $file => $array) {
							
							 $newupload = wpcads_insert_attachment( $file, $post_id );


							$wpdb->query("UPDATE 
										wp_website_listing 
									SET 
										logo_post_id = '".$newupload."'
										
									WHERE 
										id='".$lastInsertId ."' 
										and  questionnaire_id = '".$_SESSION['questionnaire_id'] ."'"
								); 
						}
					} 
				}
			}
		} else { 
		   $post_id = $arrListing['logo_post_id'];
			$business_id = $arrListing['id'];
			
			if( 'No' == $upload_logo && !empty($arrListing['logo_post_id'])) { 
				wp_delete_post($arrListing['logo_post_id']);
				$post_id = 0;


			} else { 
				
				if( empty( $_FILES['upload_attachment']['name'] )) {  
					$post_id = $arrListing['logo_post_id'];


				} else { 

					if ( isset($_FILES['upload_attachment']['name'] ) && !empty( $_FILES['upload_attachment']['name'] ) ) { 
					 

					  $files = $_FILES['upload_attachment'];
					  $types = array('image/jpeg', 'image/gif', 'image/png', 'image/jpg', 'image/bmp');
					  if ( !empty( $files['name'] ) ) {
					  foreach ($files['name'] as $key => $value) {

							if ($files['name'][$key] && in_array( $files['type'][$key], $types )) {
								
								$file = array(
									'name'     => $files['name'][$key],
									'type'     => $files['type'][$key],
									'tmp_name' => $files['tmp_name'][$key],
									'error'    => $files['error'][$key],
									'size'     => $files['size'][$key]
								);
					 
								$_FILES = array("upload_attachment" => $file);
					 
								foreach ($_FILES as $file => $array) {
									
									$newupload = wpcads_insert_attachment( $file, $post_id );
									$post_id = $newupload;
									 wp_delete_post($arrListing['logo_post_id']);
								}
							}			
						} 
					  } else {
							$post_id = $arrListing['logo_post_id'];
						}
						
					} else {

						$post_id = $arrListing['logo_post_id'];
					}
	
				}
	
			}
			
		   $sql = 
				"UPDATE 
					`wp_website_listing` 
				SET 
                                        `listing_type` = '$listing',
					`name_in_caps` = '$name_in_caps',
					`bold_print` = '$bold_print',
					`upload_logo` = '$upload_logo',
					`deal` 			= '$deal',
                                        `product_id` = '$product_id',
					`logo_post_id` = '$post_id'
				WHERE
					`wp_website_listing`.`questionnaire_id` = '".$_SESSION['questionnaire_id']."' and `wp_website_listing`.`id` = '$business_id'";
			
			$wpdb->query($sql);

		}
               /* if( 'Basic' == $listing ) {
                   $woocommerce->cart->add_to_cart(2077, 1);
                } else {
                    $woocommerce->cart->add_to_cart(2082, 1);
                }
               
		if(isset($_POST['name_in_caps'])) {
			$woocommerce->cart->add_to_cart($_POST['name_in_caps'], 1);
		}
		if(isset($_POST['bold_print'])) {
			$woocommerce->cart->add_to_cart($_POST['bold_print'], 1);
		}
		if(isset($_POST['upload_logo'])) {
			$woocommerce->cart->add_to_cart($_POST['upload_logo'], 1);
		}
                if(isset($_POST['deal'])) {
			$woocommerce->cart->add_to_cart($_POST['deal'], 1);
		} */

	}
	?>
	    <script type="text/javascript">
               window.location='<?php echo $url;?>';
		</script>
<?php
} 
if( 'Back' == $_POST['back_btn'] ) {
	 $url = "http://blacklistdir.rebelute.in/about-your-business/";
	 ?>
	   <script type="text/javascript">
               window.location='<?php echo $url;?>';
        </script>
<?php
}
?>	
<section class="ads-main-page ">
	<div class="container">
		<div class="tabs-stage no_border">
			<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
				<!-- <div id="upload-ad" > -->
				<h2 style="margin-left:10px"></h2>
				<?php if($valid == 'false') { ?>
					<span class="error" style="color: #d20000; margin-bottom: 13px; font-size: 18px; font-weight: bold; float: left;">Please upload valid image type.</span>
					<div class="clearfix"></div>
				<?php } ?>
				<?php 
				if( "Illinois" == $res_state || "illinois" == $res_state) {	?>
		
				<div id="chicago_business">
					<label class="gfield_label gfield_label_before_complex table_label" for="input_1_30_3"  id="lbl_business" >Illinois Black Owned Business Directory Packages</label>
					 <div class="label_header" >DIRECTORY LISTINGS</div>
					 <div style="overflow: hidden;width: 100%; overflow-x: auto;">
					 <div class="Table">
					   
						<?php 
						
						$i= 0;
						foreach( $banner_sizes as $key => $banner_size )
						{  
							foreach( $banner_size as $strkey => $banner )
							{ 
								$arrPackage = explode( '(', $banner_size['label']);
								$packageName = $arrPackage['0'];
								$packageSize = trim($arrPackage['1'], ')');
								
								if('size' == $strkey) {
									if( 0 == $i ){ ?>
										<div class="Heading test1">
									<?php	} ?>
										<div class="Cell cell_heading table_background"><?php echo $packageName; ?></div>
									<?php if( count($banner_sizes) - 1 == $i ){ ?>
										</div>
								<?php	}
								} 
							}  ?>
						<?php 
							
						$i++;
						} 
						$i=0;
						foreach( $banner_sizes as $key => $banner_size )
						{  
							foreach( $banner_size as $strkey => $banner )
							{ 
							   $arrPackage = explode( '(', $banner_size['label']);
								$packageName = $arrPackage['0'];
								$packageSize = trim($arrPackage['1'], ')');
								
								if('label' == $strkey ) { 
									if( 0 == $i ){ ?>
								<div class="Heading test">
								<?php	} ?>
									<div class="Cell cell_heading"><?php echo '$ ' . get_post_meta( $banner_size['product_id'], '_sale_price', true ); ?></div>
								<?php if( count($banner_sizes) - 1 == $i ){ ?>
										</div>
								<?php }
								}
							}
							$i++;
						}	
						$i=0;
						foreach( $banner_sizes as $key => $banner_size )
						{  
							foreach( $banner_size as $strkey => $banner )
							{ 
							   $arrPackage = explode( '(', $banner_size['label']);
								$packageName = $arrPackage['0'];
								$packageSize = trim($arrPackage['1'], ')');
								$packageSize	= str_replace('Size:', '', $packageSize);
								
								if('id' == $strkey ) { 
									if( 0 == $i ){ ?>
									<div class="Heading test">
									<?php	} ?>
										<div class="Cell size_container">
										<?php echo $size = (!empty($packageSize)) ? $packageSize : 'N/A'; ?></div>
									<?php if( count($banner_sizes) - 1 == $i ){ ?>
											</div>
									<?php }
								}
							}
							$i++;
						}	
						$i=0;
						foreach( $banner_sizes as $key => $banner_size )
						{  
							foreach( $banner_size as $strkey => $banner )
							{ 
								if('product_id' == $strkey ) { 
									if( 0 == $i ){ ?>
										<div class="Heading test">
									<?php	} ?>
										<div class="Cell"><input type="radio" id="banner_<?php echo $banner_size['product_id'];?>" name="banner" value="<?php echo $banner_size['product_id']; ?>" <?php if(!empty($mylisting) && $banner_size['product_id'] == $mylisting['product_id']){ ?> checked="checked" <?php } ?> ></div>
									<?php if( count($banner_sizes) - 1 == $i ){ ?>
											</div>
									<?php }
								}
							}
							$i++;
						}	
					?>
					</div>
					 </div>
			   </div>
			   <div>
					<fieldset class="input-title">
						<label class="gfield_label gfield_label_before_complex table_label" for="input_1_30_3" id="lbl_business">ADDITIONAL DIRECTORY OPTIONS</label>
											
					<div class="ginput_container ginput_container_checkbox">
						<ul class="gfield_checkbox" id="input_4_37">
							<li class="gchoice_4_37_1">
									<input name="name_in_caps" type="checkbox" value="2087" id="product_id_12" tabindex="5" <?php if(!empty($mylisting) && 'Yes' == $mylisting['name_in_caps'] ){ ?> checked="checked"  <?php } ?> >
									<label for="choice_4_37_1" id="label_4_37_1" price=" +$5.00">NAME IN ALL CAPS<span class="ginput_price"> +$5.00</span></label>
							</li>
							<li class="gchoice_4_37_2">
								<input name="bold_print" type="checkbox" value="2084" id="product_id_13" tabindex="6" <?php if(!empty($mylisting) && 'Yes' == $mylisting['bold_print'] ){ ?> checked="checked"  <?php } ?> >
								<label for="choice_4_37_2" id="label_4_37_2" price=" +$10.00">Bold Print<span class="ginput_price"> +$10.00</span></label>
							</li>
							<li class="gchoice_4_37_2">
								<input name="border" type="checkbox" value="2650" id="product_id_13" tabindex="6" <?php if(!empty($mylisting) && 'Yes' == $mylisting['border'] ){ ?> checked="checked"  <?php } ?> >
								<label for="choice_4_37_2" id="label_4_37_2" price=" +$20.00">Border<span class="ginput_price"> +$20.00</span></label>
							</li>
						</ul>
					</div>
			   </div>
			    <h5>NOTE: ALL LISTINGS ARE POSTED FOR 3 MONTH INTERVALS FOR THE DIRECTORY & WEBSITE </h5>
			   <div class="center-div" id="chicago_button">
					<input  type="submit" class="" id="back-submit" name="back_btn" value="Back" />
					<input  type="submit" class="ad-button" id="next-submit" name="next_btn" value="Next"/>
				</div>
			
		<?php } else {  
						$mylisting = $wpdb->get_row( "SELECT * FROM wp_website_listing wl 
								join  wp_questionnaire q ON (wl.questionnaire_id = q.id)
								WHERE 
									wl.questionnaire_id = '".$_SESSION['questionnaire_id']."' 
									and q.il_status = 'Outside' ", ARRAY_A );
		?>
			<div id="outside_il_listing">
					<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 20px !important;
						font-weight: bold !important;
						color: #1e73be !important;
						border-bottom: 1px solid;
						width: 96%;
					margin:10px;
						padding-bottom: 8px;display:inline-block" id="lbl_business">Website Listings options</label>
					<div id="edit-field-featured">
						<label class="option checkbox control-label" for="edit-field-featured-und">
								<input style="margin-right: 10px;margin-top: 0px;" type="radio" id="basic" name="listing" <?php if(!empty($mylisting) && 'Basic' == $mylisting['listing_type'] ){ ?> checked="checked" <?php }else{ ?>checked="checked" <?php } ?>  value="Basic" ><?php _e('Basic', 'classify') ?> 
						</label>
											
						<label class="option checkbox control-label" for="edit-field-featured-und">
								<input style="margin-right: 10px;margin-top: 0px;" type="radio" id="logo_display" name="listing" value="Logo Displayed" <?php if(!empty($mylisting) && 'Logo Displayed' == $mylisting['listing_type']){ ?> checked="checked" <?php } ?> ><?php esc_html_e( 'Logo Displayed', 'classify' ); ?>
						</label>
								
					</div>
			</div>
			<div style="clear:both"></div>
			<br>
			<div id="weblisting_basic" <?php if(( !empty($mylisting) && 'Basic' == $mylisting['listing_type'] ) ||  empty($mylisting) ) { ?> style="display:block;" <?php } else {?>
				style="display:none;" <?php } ?> >
					<table class="weblist">
					  <tbody>
						<tr>
							<td><strong>PRICE</strong></td>
							<td>$135</td>
						  </tr>
						  <tr>
							<td><p>Website Listing</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Company Name</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Company Address (if applicable) </p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Company Phone</p> </td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Company Web Address <strong>(if applicable)</strong></p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Your Website Link</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Subjected to Reviews</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Photo of you showing you are listed Us will be on our photo gallery and Facebook Page</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Directory Listing (not applicable)</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						</tbody>
					</table>
					  
			</div>	
   			
			<div id="weblisting_logo_display" <?php if(!empty($mylisting) && 'Logo Displayed' == $mylisting['listing_type']){ ?> style="display:block;" <?php } else {?>
				style="display:none;" <?php } ?>>
					<table class="weblist">
					  <tbody>
						<tr>
							<td><strong>PRICE</strong></td>
							<td>$125</td>
						  </tr>
						  <tr>
							<td><p>Website Listing</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Company Name</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Company Address (if applicable) </p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Company Phone</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Company Web Address <strong>(if applicable)</strong></p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Your Website Link</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Subjected to Reviews</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Photo of you showing you are listed Us will be on our photo gallery and Facebook Page</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						  <tr>
							<td><p>Directory Listing (not applicable)</p></td>
							<td><img src="http://blacklistdir.rebelute.in/wp-content/uploads/2016/10/tick.png"></td>
						  </tr>
						</tbody>
					</table>
			</div>
			<div id="weblist_il">
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
									<input name="name_in_caps" type="checkbox" value="2087" id="product_id_2" tabindex="5" <?php if(!empty($mylisting) && 'Yes' == $mylisting['name_in_caps'] ){ ?> checked="checked"  <?php } ?> >
									<label for="choice_4_37_1" id="label_4_37_1" price=" +$5.00">NAME IN ALL CAPS<span class="ginput_price"> +$5.00</span></label>
							</li>
							<li class="gchoice_4_37_2">
								<input name="bold_print" type="checkbox" value="2084" id="product_id_3" tabindex="6" <?php if(!empty($mylisting) && 'Yes' == $mylisting['bold_print'] ){ ?> checked="checked"  <?php } ?> >
								<label for="choice_4_37_2" id="label_4_37_2" price=" +$10.00">Bold Print<span class="ginput_price"> +$10.00</span></label>
							</li>
							<li class="gchoice_4_37_2">
								<input name="upload_logo" type="checkbox" value="2653" id="product_id_14" tabindex="6" <?php if(!empty($mylisting) && 'Yes' == $mylisting['upload_logo'] ){ ?> checked="checked"  <?php } ?>>
								<label for="choice_4_37_2" id="label_4_37_2" price=" +$25.00">Upload Graphic/Logo<span class="ginput_price"> +$25.00</span></label>
							</li>
						</ul>
					</div>
					<div  <?php if(!empty($mylisting) && 'Yes' == $mylisting['upload_logo'] ){ ?> style="display:block;"  <?php } else { ?> style="display:none;" <?php } ?> id="upload_template_image" class="upload">
								<fieldset class="input-title">
								
									
								<input id="upload-images-ad" type="file" name="upload_attachment[]" accept="image/*" />
								<span><?php 
								
								if( !empty($mylisting) && 'Yes' == $mylisting['upload_logo'] ){ 
									$attachment = get_post_meta( $mylisting['logo_post_id'] );
									$arrAttachFile = explode( '/', $attachment['_wp_attached_file'][0] );
									echo $filename = $arrAttachFile[count($arrAttachFile)-1];
								} ?>
								</span>
								</fieldset>
								<div id="upload_error"></div>
						</div>
					<div class="clearfix"></div>
											
					<label class="gfield_label gfield_label_before_complex" for="input_1_30_3" style="font-size: 15px !important;
							font-weight: bold !important;
							color: #1e73be !important;
							border-bottom: 1px solid;
							width: 96%;
							margin:10px;
							padding-bottom: 8px;display:inline-block" id="lbl_business" style="display:none;">GIVE YOUR CUSTOMERS A DEAL!!! </label>
												
					<div class="ginput_container ginput_container_checkbox">
						<ul class="gfield_checkbox" id="input_4_37">
							<li class="gchoice_4_37_1">
								<input name="deal" type="checkbox" value="2651" id="product_id_2" tabindex="5" <?php if(!empty($mylisting) && 'Yes' == $mylisting['deal'] ){ ?> checked="checked"  <?php } ?>>
								<label for="choice_4_37_1" id="label_4_37_1" price=" +$5.00">DEAL<span class="ginput_price"> +$25.00</span></label>
							</li>
						</ul>
					</div>
						
			</div>
			<div class="center-div" id="chicago_button">

					<input  type="submit" class="" id="back-submit" name="back_btn" value="Back" />

					<input  type="submit" class="ad-button" id="next-submit" name="next_btn" value="Next"/>
				</div>
			</form>
			<div style="clear:both"></div>
	<?php } ?>
	        </form>
		</div>
	</div>
</section>
<script type="text/javascript">//<![CDATA[
jQuery( document ).ready(function( $ ) {
	$('form input[name="listing"]').on('click', function() {

		if ($(this).val() == 'Basic') {
			$('#weblisting_logo_display').hide();
			$('#weblisting_basic').show();
			/*$('#chicago_business').show();
			$('#outside_chicago_listing').hide();
			$('#weblisting_logo_display').hide();
			$('#outside_chicago_button').hide();*/
			/* $('#chicago_button').show(); */
        }
        else {
			$('#weblisting_basic').hide();
			$('#weblisting_logo_display').show();
            /*$('#chicago_business').hide();
			$('#outside_chicago_listing').show();
			$('#weblisting_basic').hide();
			
			$('#chicago_button').hide();*/
			/* $('#outside_chicago_button').show(); */
        }
    });
	$('form input[name="upload_logo"]').on('click', function() { 
		if((this).checked ) {
		    $('#upload_template_image').show();
		} else {
			$('#upload_template_image').hide();
		} 
	});	
});	

jQuery(function ( $) {
    $(":file").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
             var file = this.files[0];
            
             var filename = file.name;
             $(this).next().html( file.name );
        }
    });
});

function imageIsLoaded(e) {
    jQuery('#upload-images-ad').attr('src', e.target.result);

};
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
Success!
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
  .Table
    {
        display: table;
		  width:100%;
    }
    .Title
    {
        display: table-caption;
        text-align: center;
        font-weight: bold;
        font-size: larger;
    }
    .Heading
    {
        display: table-row;
        font-weight: bold;
        text-align: center;
    }
    .Row
    {
        display: table-row;
    }
    .Cell
    {
        display: table-cell;
        border: solid;
        border-width: thin;
        padding-left: 28px;
		  padding-right: 5px;
		  padding-top: 10px;
		  padding-bottom: 10px;
    }
	.table_Cell
    {
        border: solid;
        border-width: thin;
        padding-left: 28px;
		  padding-right: 5px;
		  padding-top: 10px;
		  padding-bottom: 10px;
    }
	.cell_heading {
    color: #000;
    font-size: 15px;
    font-weight: bold;
	}
	.table_background {
    background-color: #1e73be;
  }
  .size_container {
    font-size: 15px;
    font-weight: 500;
    color: #000;
  }
  .table_label {
		font-size: 20px;
		font-weight: bold;
		color: #1e73be;
		border-bottom: 1px solid;
		width: 96%;
		margin:10px;
		padding-bottom: 8px;
		display:inline-block;
		text-align:center;
  }
 form.form-item div {
	 margin-top:25px;
 }  
 .label_header {
    text-align: center;
   
    width: 100%;
    color: #000;
    font-size: 16px;
    font-weight: bold;
    padding-top: 5px;
    padding-bottom: 5px;
}
.submit-ad-button {
    float: left;
    width: 100%;
    margin-bottom: 25px;
}
.ad-button {
    margin-left: 10px;
}
form h5 {
    text-align: center;
    font-size: 15px;
    font-weight: 900;
}
div.upload {
    width: 121px;
    height: 30px;
    background: url('http://blacklistdir.rebelute.in/wp-content/uploads/2017/02/upload-image.png');
   /*overflow: hidden;*/
}

div.upload input {
    display: block !important;
    width: 157px !important;
    height: 40px !important;
    opacity: 0 !important;
    overflow: hidden !important;
}
.Heading.test1 .Cell {
    padding: 10px;
    padding-bottom: 10px;
	width: 100px;
}
</style>
<?php get_footer(); ?>