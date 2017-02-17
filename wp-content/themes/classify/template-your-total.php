<?php
session_start();

/**
 * Template Name: Business Total
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
$woocommerce->session->set_customer_session_cookie(true);
get_header(); ?>

<?php 	


$directory_total = 0;
$listing_total = 0;
$address_total = 0;	
/*$wp_session = WP_Session::get_instance();
$data = json_decode($wp_session->json_out() ); */

$query = "SELECT q.business_name,q.web_address,q.id
			FROM {$wpdb->prefix}questionnaire As q
			WHERE q.id = '".$_SESSION['questionnaire_id']."'";
						
$results = $wpdb->get_results($query,  ARRAY_A );

$mylisting = $wpdb->get_row( "SELECT * FROM wp_website_listing WHERE questionnaire_id = '".$_SESSION['questionnaire_id']."' ", ARRAY_A );	


$package = $wpdb->get_row( "SELECT * FROM wp_questionnaire WHERE id = '".$_SESSION['questionnaire_id']."' ", ARRAY_A );	

				
if(!empty($mylisting)){
	
	$qry_business = "SELECT ba.*
			FROM {$wpdb->prefix}business_addresses As ba
			WHERE ba.questionnaire_id ='". $mylisting['questionnaire_id']."'";
						
	$arrBusinessDetails = $wpdb->get_results($qry_business,  ARRAY_A );
}
	
if( !empty( $arrBusinessDetails ) ) {	
    foreach( $arrBusinessDetails as $key => $business_details ){  
	
         if( 'Inside' == $package['il_status'] ) {
	      $directory_total += $business_details['price'];
         } else {
             $address_total += $business_details['price'];
         }
    }
}

if( 'Inside' == $package['il_status'] ) {
$qry_dir_listing = "SELECT dl.name_in_caps, dl.bold_print, dl.border, dl.product_id
			FROM {$wpdb->prefix}directory_listing As dl
			WHERE dl.questionnaire_id = '".$_SESSION['questionnaire_id']."'";
						
$res_list = $wpdb->get_results($qry_dir_listing,  ARRAY_A );


   foreach( $res_list as $key => $listing_options ){  
 
	if('Yes' == $listing_options['name_in_caps'] ) {
		$directory_total += 5;
	}
	if('Yes' == $listing_options['bold_print'] ) {
		$directory_total += 10;
	}
	if('Yes' == $listing_options['border'] ) {
		$directory_total += 20;
	}
	if( !empty($listing_options['product_id']) ) {
		$directory_total += get_post_meta( $listing_options['product_id'], '_sale_price', true );
	}
   }
}

   $additional_total = 0;		
   $deal_total = 0;

   if(!empty($mylisting)) {
	if('Yes' == $mylisting['name_in_caps']) {
		$additional_total	+= 5;
	}
	if('Yes' == $mylisting['bold_print']) {
		$additional_total	+= 10;
	}
	if('Yes' == $mylisting['upload_logo']) {
		$additional_total	+= 25;
	}
	if('Yes' == $mylisting['deal']) {
		$deal_total	+= 25;
	}
        if('Outside' == $package['il_status'] && !empty($mylisting['product_id'])) {
              if( 2077 ==  $mylisting['product_id'] ) {
		$listing_total+= 135;
              } else {
                $listing_total+= 125;
              }
	}
   }


global $wpdb, $woocommerce;
$valid = 'true';			
if( 'Next' == $_POST['next_btn'] ) { 
     
     WC()->cart->empty_cart();
    
     if( !empty( $arrBusinessDetails ) ) {	
         foreach( $arrBusinessDetails as $key => $business_details ){  
             
            $woocommerce->cart->add_to_cart($business_details['product_id'], 1 );
             
         }
     }

     if( 'Inside' == $package['il_status'] ) {
          foreach( $res_list as $key => $listing_options ){  
 
	      if('Yes' == $listing_options['name_in_caps'] ) {
		  $woocommerce->cart->add_to_cart( 2087, 1 );
	      }

	      if('Yes' == $listing_options['bold_print'] ) {
		 $woocommerce->cart->add_to_cart( 2084, 1 );
	      }

	      if('Yes' == $listing_options['border'] ) {
		  $woocommerce->cart->add_to_cart( 2650, 1 );
	      }

	      if( !empty($listing_options['product_id']) ) {
		  $woocommerce->cart->add_to_cart( $listing_options['product_id'], 1 );
	      }
          }

     }

     if(!empty($mylisting)) {
	if('Yes' == $mylisting['name_in_caps']) {
		 $woocommerce->cart->add_to_cart( 2087, 1 );
	}
	if('Yes' == $mylisting['bold_print']) {
		$woocommerce->cart->add_to_cart( 2084, 1 );
	}
	if('Yes' == $mylisting['upload_logo']) {
		$woocommerce->cart->add_to_cart( 2653, 1 );
	}
	if('Yes' == $mylisting['deal']) {
		$woocommerce->cart->add_to_cart( 2651, 1 );
	}
        if('Outside' == $package['il_status'] && !empty($mylisting['product_id'])) {
              if( 2077 ==  $mylisting['product_id'] ) {
		   $woocommerce->cart->add_to_cart( $mylisting['product_id'], 1 );
              } 
              if( 2082 ==  $mylisting['product_id'] ) {
		   $woocommerce->cart->add_to_cart( $mylisting['product_id'], 1 );
              }
	}
    }


	 /* $url = "http://blacklistdir.rebelute.in/thank-you-for-your-submisson/"; */
         $url = "http://blacklistdir.rebelute.in/checkout/";
	 ?>
	   <script type="text/javascript">
               window.location='<?php echo $url;?>';
        </script>
<?php 
}  
if( 'Back' == $_POST['back_btn'] ) {
	 $url = "http://blacklistdir.rebelute.in/web-listing-details/";
	 ?>
	   <script type="text/javascript">
               window.location='<?php echo $url;?>';
        </script>
<?php
}
?>	
<div class="ad-title">
	<h2>YOUR TOTAL</h2>
</div>
<section class="ads-main-page ">
	<div class="container">

		<div class="span10 first" style="padding: 30px 0;">

			<div class="ad-detail-content your-total-style" >

													
				<div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-2">
						<div class="vc_column-inner vc_custom_1485194156356">
							<div class="wpb_wrapper"></div>
						</div>
					</div>
					<div class="wpb_column vc_column_container vc_col-sm-8">
						<div class="vc_column-inner "><div class="wpb_wrapper">
							<div class="wpb_text_column wpb_content_element ">
								<div class="wpb_wrapper  append-text">
										<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data" >
											<!-- <div id="upload-ad" > -->
											
										
														   
												<div><?php if(!empty($package) && 'Inside' == $package['il_status']) { ?> 
														<h4>Directory Total :- $<?php echo $directory_total; } ?></h4>
												</div>


<?php if(!empty($package) && 'Outside' == $package['il_status']) { ?>
												<div> 
													<h4>Business Address Total  - $<?php echo $address_total; ?>
													</h4>
												</div>
												<?php } ?>

												<?php if(!empty($package) && 'Outside' == $package['il_status']) { ?>
												<div> 
													<h4>Website Listings Options - <?php echo '(' . $mylisting['listing_type'] . ')';?> :-  <?php echo '$' . get_post_meta( $mylisting['product_id'], '_sale_price', true ); ?>
													</h4>
												</div>
												<?php } ?>
												<div class="clearfix">
													<h4>Website Addition Total :- $<?php echo $additional_total; ?></h4>
												</div>

												<div class="clearfix" ><h4>Deal :- $<?php echo $deal_total;?></h4></div>
											
												<h4> <?php if(!empty($package) && 'Inside' == $package['il_status']) { ?>DIRECTORY & <?php } ?>TOTAL WEBSITE LISTING = $<?php echo $directory_total+$additional_total+$deal_total+$listing_total+$address_total; ?></h4>
												
															

											   <h4>No Nudity.. No Profanity.. Will be allowed in THE BLACK LIST LLC..</h4>
											

                                            <div style="text-align:center;" class="form-submit-global">
											   <div class="center-div publish-ad-button"  id="chicago_button">
												<?php 

												if( 'Inside' == $package['il_status']) {?>
																	<input  type="submit" class="" id="back-submit" name="back_btn" value="Back" />
												<?php } ?>
													<input  type="submit" class=" ad-button" id="next-submit" name="next_btn" value="Next"/>
												</div>
											</div>	
										</form>
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="wpb_column vc_column_container vc_col-sm-2">
						<div class="vc_column-inner">
							<div class="wpb_wrapper"></div>
						</div>
					</div>
				</div>
																
			</div>

					
		</div>

		<div class="span4">
		</div>
	 </div>
</section>
<script type="text/javascript">//<![CDATA[

/*$(function(){
// From http://learn.shayhowe.com/advanced-html-css/jquery

// Change tab class and display content


	$('.tabs-nav a:first').trigger('click'); // Default
});//]]> 
*/
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
	 margin-top:5px;
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
.abc {
	font-size: 20px !important;
	font-weight: bold !important;
	color: #1e73be !important;
	border-bottom: 1px solid;
	width: 96%;
	margin:10px;
	padding-bottom: 8px;display:inline-block
}	
form h5 {
    text-align: center;
    font-size: 15px;
    font-weight: 900;
}		
form h4 {
    text-align: center;
    font-weight: 900;
}	
form h2 {
    text-align: center;
    font-weight: 900;
}	
#il_business {
	text-align: center;
}
.total_heading {  
    margin: 0;
    padding: 0;
    background: none;
    letter-spacing: 0;
}
.author-info h4{
    margin:0;
    letter-spacing:0;
}
</style>
<?php get_footer(); ?>
