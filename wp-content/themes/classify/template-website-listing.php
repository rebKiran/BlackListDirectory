<?php
session_start();
/*
WC()->cart->empty_cart();
print_r(WC()->cart);*/

/**
 * Template Name: Website Listing
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */


get_header(); ?>
<?php 
$wp_session = WP_Session::get_instance();
$data = json_decode($wp_session->json_out() ); 


$query = "SELECT q.business_name,q.web_address,q.id
			FROM {$wpdb->prefix}questionnaire As q
			WHERE q.id = '".$_SESSION['questionnaire_id']."'";
						
$results = $wpdb->get_results($query,  ARRAY_A );

$mylisting = $wpdb->get_row( "SELECT * FROM wp_website_listing WHERE questionnaire_id = '".$_SESSION['questionnaire_id']."' ", ARRAY_A );


/*
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
*/
global $wpdb;
global $woocommerce;
$valid = 'true';	
	
if( 'Next' == $_POST['next_btn'] ) { 
		
    
          $url = "http://blacklistdir.rebelute.in/web-listing-details/"; 
    
	
		$wp_session = WP_Session::get_instance();
		$data = json_decode($wp_session->json_out() );
		

	        $listing			= trim($_POST['listing']);
		$name_in_caps 	= (isset($_POST['name_in_caps']) ) ? 'Yes' : 'No';
		$bold_print 	= (isset($_POST['bold_print']) ) ? 'Yes' : 'No';
		$upload_logo 	= (isset($_POST['upload_logo'])) ? 'Yes' : 'No';
		$deal 			= (isset($_POST['deal'])) ? 'Yes' : 'No';
		
		global $wpdb;
		if(!is_array($mylisting)) {
			
			$sql = "INSERT INTO `wp_website_listing` ( `questionnaire_id`, `listing_type`, `name_in_caps`, `bold_print`,`upload_logo`, `deal`,`token`) 
			VALUES ('".$_SESSION['questionnaire_id']."', '$listing', '$name_in_caps', '$bold_print', '$upload_logo', '$deal', '$data->number')";
	  
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
                       /* if(isset($_POST['name_in_caps'])) {
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
			}*/
		} else {
			$post_id = $mylisting['logo_post_id'];
			$business_id = $mylisting['id'];
			if( 'No' == $upload_logo && !empty($mylisting['logo_post_id'])) {
				wp_delete_post($mylisting['logo_post_id']);
				$post_id = 0;
			} else {
				//echo '<pre/>';
				 //print_r( array_values($_FILES['upload_attachment']['name'] ) );
				// die( 'Here' );
				if( empty( $_FILES['upload_attachment']['name'] )) {
					$post_id = $mylisting['logo_post_id'];
				} else {
					if ( $_FILES ) {
					
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
									}
								}			
							}
					   } else {
						   	$post_id = $mylisting['logo_post_id'];
					   }	
					}  else {

						 	$post_id = $mylisting['logo_post_id'];
					}	
				}		
			}
			
		$sql = 
				"UPDATE 
						`wp_website_listing` 
				SET 
					`name_in_caps` = '$name_in_caps',
					`bold_print` = '$bold_print',
					`upload_logo` = '$upload_logo',
					`deal` 			= '$deal',
					`logo_post_id` = '".intval($post_id)."'
				WHERE
					`wp_website_listing`.`questionnaire_id` = '".$_SESSION['questionnaire_id']."' and `wp_website_listing`.`id` = '$business_id'";

			

			$wpdb->query($sql);

                       /*if(isset($_POST['name_in_caps'])) {
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
			}*/
		}
		?>
	   <script type="text/javascript">
               window.location='<?php echo $url;?>';
        </script>
<?php 
} 
if( 'Back' == $_POST['back_btn'] ) {
     
	 $url = "http://blacklistdir.rebelute.in/directory-listing/";
      
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
			<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data" onsubmit="return validate()">
				<!-- <div id="upload-ad" > -->
				<h2 style="margin-left:10px"></h2>
				<?php if($valid == 'false') { ?>
					<span class="error" style="color: #d20000; margin-bottom: 13px; font-size: 18px; font-weight: bold; float: left;">Please upload valid image type.</span>
					<div class="clearfix"></div>
				<?php } ?>
				
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
								<input style="margin-right: 10px;margin-top: 0px;" type="radio" id="logo_display" name="listing" value="Logo Displayed" <?php if(!empty($mylisting) && 'Logo Displayed' == $mylisting['listing_type']){ ?> checked="checked" <?php } else { ?> checked="checked" <?php } ?> ><?php esc_html_e( 'Logo Displayed', 'classify' ); ?>
						</label>
								
					</div>
				</div>
				<div style="clear:both"></div>
				<br>
				
   			
				<div id="weblisting_logo_display" <?php if(!empty($mylisting) && 'Logo Displayed' == $mylisting['listing_type']){ ?> style="display:block;" <?php } else {?>
				style="display:block;" <?php } ?>>
					<table class="weblist">
					  <tbody>
						<tr>
							<td><strong>PRICE</strong></td>
							<td></td>
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
										<input name="name_in_caps" type="checkbox" value="2087" id="product_id_12" tabindex="5" <?php if(!empty($mylisting) && 'Yes' == $mylisting['name_in_caps'] ){ ?> checked="checked"  <?php } ?>>
										<label for="choice_4_37_1" id="label_4_37_1" price=" +$5.00">NAME IN ALL CAPS<span class="ginput_price"> +$5.00</span></label>
								</li>
								<li class="gchoice_4_37_2">
									<input name="bold_print" type="checkbox" value="2084" id="product_id_13" tabindex="6" <?php if(!empty($mylisting) && 'Yes' == $mylisting['bold_print'] ){ ?> checked="checked"  <?php } ?>>
									<label for="choice_4_37_2" id="label_4_37_2" price=" +$10.00">Bold Print<span class="ginput_price"> +$10.00</span></label>
								</li>
								<li class="gchoice_4_37_2">
									<input name="upload_logo" type="checkbox" value="2653" id="product_id_14" tabindex="6" <?php if(!empty($mylisting) && 'Yes' == $mylisting['upload_logo'] ){ ?> checked="checked"  <?php } ?>>
									<label for="choice_4_37_2" id="label_4_37_2" price=" +$25.00">Upload Graphic/Logo<span class="ginput_price"> +$25.00</span></label>
								</li>
							</ul>
						</div>




						<div  <?php if(!empty($mylisting) && 'Yes' == $mylisting['upload_logo'] ){ ?> style="display:block;"  <?php } else { ?> style="display:none;" <?php } ?> id="upload_template_image"  class="upload">
								<fieldset class="input-title">
								
									
								<input  id="upload-images-ad" type="file" name="upload_attachment[]" accept="image/*" />
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
						<div>
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
											<input name="deal" type="checkbox" value="2651" id="product_id_2" tabindex="5" <?php if(!empty($mylisting) && 'Yes' == $mylisting['deal'] ){ ?> checked="checked"  <?php } ?> >
											<label for="choice_4_37_1" id="label_4_37_1" price=" +$5.00">DEAL<span class="ginput_price"> +$25.00</span></label>
										</li>
									</ul>
								</div>
						</div>	
					
					</div>
				</div>
				<div class="center-div publish-ad-button" id="chicago_button">
					<input  type="submit" class="" id="back-submit" name="back_btn" value="Back" />
					<input  type="submit" class="ad-button" id="next-submit" name="next_btn" value="Next"/>
				</div>
			</form>
		    <div style="clear:both"></div>
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
    border: 1px solid #000;
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
    margin-left: 400px;
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
#upload_template_image label.control-label {
	display:none !important;
	
}
div.upload {
    width: 121px;
    height: 30px;
    background: url('http://blacklistdir.rebelute.in/wp-content/uploads/2017/02/upload-image.png');
    /*overflow: hidden;*/
}

div.upload input {
    display: block !important;
    width: 121px !important;
    height: 30px !important;
    opacity: 0 !important;
    overflow: hidden !important;
}
</style>

<?php get_footer(); ?>
