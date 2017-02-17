<?php 
session_start();
/*
WC()->cart->empty_cart();
print_r(WC()->cart);*/

/**
 * Template Name: Directory Total
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<?php 	
$total = 0;
$additional_total = 0;
$listing = 0;
$business_address = 0;


$querystr = "
			   SELECT $wpdb->postmeta.meta_value as size, $wpdb->posts.post_title as label, $wpdb->posts.ID as id,wp_product_banner.product_id 
			   FROM $wpdb->posts, $wpdb->postmeta, wp_product_banner
			   WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
			   AND $wpdb->posts.ID = wp_product_banner.banner_id
			   AND $wpdb->postmeta.meta_key = '_banner_bc_size' 
			   AND $wpdb->posts.post_status = 'publish' 
			   AND $wpdb->posts.post_type = 'vbc_banners'
			   ORDER BY $wpdb->posts.post_title ASC
			";
			
$banner_sizes = $wpdb->get_results($querystr,ARRAY_A);

	
$wp_session = WP_Session::get_instance();
$data = json_decode($wp_session->json_out() ); 

$query = "SELECT q.business_name,q.web_address,q.id
			FROM {$wpdb->prefix}questionnaire As q
			WHERE q.number = '".$_SESSION['number']."'";
						
$results = $wpdb->get_results($query,  ARRAY_A );	

$qry_dir_listing = "SELECT dl.name_in_caps, dl.bold_print, dl.border, dl.product_id
			FROM {$wpdb->prefix}directory_listing As dl
			WHERE dl.questionnaire_id = '".$_SESSION['questionnaire_id']."'";

$res_list = $wpdb->get_results($qry_dir_listing,  ARRAY_A );	


$qry_dir_listing = "SELECT dl.name_in_caps, dl.bold_print, dl.border, dl.product_id
			FROM {$wpdb->prefix}directory_listing As dl
			WHERE dl.questionnaire_id = '".$_SESSION['questionnaire_id']."'";
						


foreach( $res_list as $key => $listing_options ){  
 
	if('Yes' == $listing_options['name_in_caps'] ) {
		$total += 5;
                $additional_total += 5;
	}
	if('Yes' == $listing_options['bold_print'] ) {
		$total += 10;
                $additional_total += 10;
	}
	if('Yes' == $listing_options['border'] ) {
		$total += 20;
                $additional_total += 20;
	}
	if( !empty($listing_options['product_id']) ) {
		$total += get_post_meta( $listing_options['product_id'], '_sale_price', true );
               $listing += get_post_meta( $listing_options['product_id'], '_sale_price', true );
	}
}						
					
if(!empty($results)){
	
	$qry_business = "SELECT ba.*
			FROM {$wpdb->prefix}business_addresses As ba
			WHERE ba.questionnaire_id ='". $results[0]['id']."'";
						
	$arrBusinessDetails = $wpdb->get_results($qry_business,  ARRAY_A );
        if(0 != count($arrBusinessDetails)) {
            $business_address = 50 * count($arrBusinessDetails);
        }
}	
/*echo '<pre/>';
print_r( $arrBusinessDetails );
die( 'Here' );*/

global $wpdb;
$valid = 'true';			
if( 'Next' == $_POST['next_btn'] ) { 
	 $url = "http://blacklistdir.rebelute.in/website-listing-options/";
	 ?>
	   <script type="text/javascript">
               window.location='<?php echo $url;?>';
        </script>
<?php 
}  
if( 'Back' == $_POST['back_btn'] ) {
	 $url = "http://blacklistdir.rebelute.in/member-packages/";
	 ?>
	   <script type="text/javascript">
               window.location='<?php echo $url;?>';
        </script>
<?php
}
?>		
<div class="ad-title">
	 <h2>Directory Listing</h2>
</div>
<section class="ads-main-page ">
	<div class="container">
		<div class="span10 first" style="padding: 30px 0;">
			<div class="ad-detail-content">
				<div class="vc_row wpb_row vc_row-fluid">
					<div class="wpb_column vc_column_container vc_col-sm-2">
						<div class="vc_column-inner vc_custom_1485194156356">
							<div class="wpb_wrapper"></div>
						</div>
					</div>
					<div class="wpb_column vc_column_container vc_col-sm-8">
						<div class="vc_column-inner "><div class="wpb_wrapper">
							<div class="wpb_text_column wpb_content_element ">
								<div class="wpb_wrapper  append-text">
									<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data" >
										
											<div id="chicago_business" class="author-info clearfix">
												<fieldset class="input-title">
													<label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3">Business Name :- <?php echo ucfirst($results[0]['business_name']);?></label>
																	
													<label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3">Web Address :- <?php echo $results[0]['web_address'];?></label>
												</fieldset>	
												<div>					
												<?php 
													if(!empty($arrBusinessDetails )){
														foreach( $arrBusinessDetails as $key => $business_details ){  
																			$total += $business_details['price'];
												?>

														<span class="ad-detail-info">
															<label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3"><?php echo $business_details['street_address'] . ', ' . $business_details['address'] . ', ' . $business_details['city'];?></label>
													
															<label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3"><?php echo $business_details['state'] . ', ' . $business_details['country']. ', ' . $business_details['contact'];?></label>
														</span>					
											  <?php	} } ?>
												</div>
												
											</div>
<div style="text-align:center" class="your-total-style"><center>
											<h4>Total Business address : $<?php echo $business_address; ?> </h4>
											<h4>Banner price : $<?php echo $listing; ?> </h4>
											<h4>Total additional directory option : $<?php echo $additional_total; ?> </h4>
											<h4>Directory Total: $<?php echo $total; ?> </h4>
		</center> 		</div>						 <div style="text-align:center;" class="form-submit-global">
									              <div class="center-div publish-ad-button" id="chicago_button">
											<input  type="submit" class="" id="back-submit" name="back_btn" value="Back" />
											<input  type="submit" class="ad-button" id="next-submit" name="next_btn" value="Next"/>
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
		<div class="span4"></div>
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
.abc {
	font-size: 20px !important;
	font-weight: bold !important;
	color: #1e73be !important;
	border-bottom: 1px solid;
	width: 96%;
	margin:10px;
	padding-bottom: 8px;display:inline-block
}									
</style>
<?php get_footer(); ?>
