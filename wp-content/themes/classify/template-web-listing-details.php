<?php
session_start();
/*
  WC()->cart->empty_cart();
  print_r(WC()->cart); */

/**
 * Template Name: Business Details
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();
?>

<?php
$total = 0;
/*
$wp_session = WP_Session::get_instance();
$data = json_decode($wp_session->json_out());*/

 $query = "SELECT q.business_name,q.web_address,q.id
			FROM {$wpdb->prefix}questionnaire As q
			WHERE q.id = '".$_SESSION['questionnaire_id']."'";

$results = $wpdb->get_results($query, ARRAY_A);

$mylisting = $wpdb->get_row("SELECT * FROM wp_website_listing WHERE questionnaire_id = '" . $_SESSION['questionnaire_id'] . "' ", ARRAY_A);

$total = 0;
if (!empty($mylisting)) {
    if ('Yes' == $mylisting['name_in_caps']) {
        $total += 5;
    }
    if ('Yes' == $mylisting['bold_print']) {
        $total += 10;
    }
    if ('Yes' == $mylisting['upload_logo']) {
        $total += 25;
    }
    if ('Yes' == $mylisting['deal']) {
        $total += 25;
    }
}

if (!empty($mylisting)) {

    $qry_business = "SELECT ba.*
			FROM {$wpdb->prefix}business_addresses As ba
			WHERE ba.questionnaire_id ='" . $mylisting['questionnaire_id'] . "'";

    $arrBusinessDetails = $wpdb->get_results($qry_business, ARRAY_A);
}


global $wpdb;
$valid = 'true';
if ('Next' == $_POST['next_btn']) {
    $url = "http://blacklistdir.rebelute.in/your-total/";
    ?>
    <script type="text/javascript">
        window.location = '<?php echo $url; ?>';
    </script>
    <?php
}
if ('Back' == $_POST['back_btn']) {
    

    $package = $wpdb->get_row( "SELECT * FROM wp_questionnaire WHERE id = '".$_SESSION['questionnaire_id']."' ", ARRAY_A );
      if( 'Inside' == trim($package['il_status']) ) {
          $url = "http://blacklistdir.rebelute.in/website-listing-options/";
   } else {
          $url = "http://blacklistdir.rebelute.in/member-packages/";
      }
    ?>
    <script type="text/javascript">
        window.location = '<?php echo $url; ?>';
    </script>
    <?php
}
?>		
<section class="ads-main-page ">
    <div class="container">
        <div class="tabs-stage no_border">
            <form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data" onsubmit="return validate()">
                <!-- <div id="upload-ad" > -->
                <h2 style="margin-left:10px">Website Listing Details</h2>

<!--                <div id="il_business">
                    <fieldset class="input-title">
                        <label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3">Business Name :- <?php echo ucfirst($results[0]['business_name']); ?></label>

                        <label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3">Web Address :- <?php echo $results[0]['web_address']; ?></label>
                    </fieldset>	
                    <div>
                        <label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3"><?php if (!empty($arrBusinessDetails)) { ?> Business Address <?php } ?></label>					
                        <?php
                        if (!empty($arrBusinessDetails)) {
                            foreach ($arrBusinessDetails as $key => $business_details) {
                                ?>
                                <label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3"><?php echo $business_details['street_address'] . ', ' . $business_details['address'] . ', ' . $business_details['city']; ?></label>

                                <label for="edit-field-category-und" class="gfield_label gfield_label_before_complex" for="input_1_30_3"><?php echo $business_details['state'] . ', ' . $business_details['country'] . ', ' . $business_details['contact']; ?></label>

                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>-->

                <div style="background:#fff;padding:5px" class="author-info clearfix">


                    <div class="clearfix">
                        <div class="span7">
							<span class="ad-detail-info">
								<b class="ad-details"><?php echo ucfirst($results[0]['business_name']); ?></b>
							</span>
							
							 <?php
									if (!empty($arrBusinessDetails)) {
										foreach ($arrBusinessDetails as $key => $business_details) {
											?>
											<span class="ad-detail-info"> 
												<span class="ad-details"><?php echo $business_details['street_address'] . ', ' . $business_details['address'] . ', ' . $business_details['city']; ?></span>
											</span>
											<span class="ad-detail-info"> 
												<span class="ad-details"><?php echo $business_details['state'] . ', ' . $business_details['country'] . '<br><b><i style="color:#000" class="fa fa-phone"></i> ' . $business_details['contact']; ?></b></span>
											</span>

											<?php
										}
									}
								?>
							<span class="ad-detail-info">
								<span class="ad-details"><?php if(!empty( $results[0]['web_address'])) { ?><i style="color:#000" class="fa fa-globe"></i><a style="color:#000" href="javascript:;" onclick="website_link('<?php echo $results[0]['web_address']; ?>')" target="_blank"><?php echo $results[0]['web_address']; ?></a> <?php } ?></span>
							</span>
								
                        </div>
                        
                        <div class="span3 ratings">
			   <div class="follow-btn">
			     <?php echo do_shortcode('[RICH_REVIEWS_SNIPPET stars_only="true"]'); ?>
								<button class="revbtn" disabled>Review this</button>
                        </div>
                        </div>
                       
                    </div>


                </div>

                <h4 style="margin-left:10px"> WEBSITE ADDITON TOTAL: $<?php echo $total; ?> </h4>
                <h5>NOTE: ALL LISTINGS ARE POSTED FOR 3 MONTH INTERVALS FOR THE DIRECTORY & WEBSITE </h5>
                <div class="center-div publish-ad-button" id="chicago_button">

                    <input  type="submit" class="" id="back-submit" name="back_btn" value="Back" />

                    <input  type="submit" class="ad-button" id="next-submit" name="next_btn" value="Next"/>
                </div>
            </form>
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

 function website_link(url) {
                if (url == '') {
                    alert("No website url found");
                    return false;
                }
                if (!/^https?:\/\//i.test(url)) {
                    url = 'http://' + url;
                    console.log(url);
                    var win = window.open(url, '_blank');
                    win.focus();
                } else {
                    var win = window.open(url, '_blank');
                }
            }
</script>  
<style>
    .stars, .rr_star {
        color: #000000;
        font-size:26px;
    }

    .step-tab {
        padding: 39px 30px;
    }
    .revbtn{
        padding: 5px;
        border-radius: 5px;
        box-shadow: none;
        background: #1b1b1b;
        border: none;
        margin: 10px;
        color: #fff;
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
    .follow-btn {
    position: relative;
    left: 20px;
    top: 20px;
    }	
    .ratings {
      margin-bottom:20px;
     }				
</style>
<?php get_footer(); ?>
