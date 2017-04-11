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
 $trns_read_btn = $redux_demo['read_more_btn'];

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

$mylink = $wpdb->get_row( "SELECT * FROM wp_questionnaire WHERE number = '".$_SESSION['number']."' AND id = '". $_SESSION['questionnaire_id']."' ", ARRAY_A );

if( "Illinois" == $mylink['state'] || "illinois" == $mylink['state']) {
                             
	if(!empty($mylink['post_id'])) {
		 $image_post_id = $mylink['post_id'];
	} else {

        $listingPost = $wpdb->get_row( "SELECT logo_post_id FROM wp_website_listing WHERE questionnaire_id = '".$_SESSION['questionnaire_id']."' ", ARRAY_A );

		$image_post_id = $listingPost['logo_post_id'];
    }
                              
} else {
    
	$listingPost = $wpdb->get_row( "SELECT logo_post_id FROM wp_website_listing WHERE questionnaire_id = '". $_SESSION['questionnaire_id']."' ", ARRAY_A );

	$image_post_id = $listingPost['logo_post_id'];
}
						
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
<div class="ad-title">
	<h2 style="margin-left:10px">Website Listing Details</h2>
</div>	
<section class="ads-main-page ">
    <div class="container">
        <div class="tabs-stage no_border">
            <form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
                <!-- <div id="upload-ad" > -->
				
               
			<div class="span8 first">
			
   
   <?php        if(!empty($mylink['post_id'])) {

						$full_img_url = wp_get_attachment_url($image_post_id);					
						
					?>
                         <div class="listing-imgalign"><center>
						 <?php if( strpos( $full_img_url, '/WPC/') !== false ) {  ?>
                                <img class="img-style" src='<?php echo $full_img_url. '/part1/part1.png'; ?>'/>
						 <?php } else { ?>
								<img class="img-style" src='<?php echo $full_img_url; ?>'/>
						 <?php } ?>
							</center>
						</div>
				<?php 
						
					}		

				if(empty($mylink['post_id'])){
				?>
				
	    		<div class="listing-imgalign">
	    			<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

						<?php 

						$params = array( 'width' => 770, 'height' => 500, 'crop' => true );
						$params_small = array( 'width' => 100, 'height' => 70, 'crop' => true );
						
						
						//foreach($attachments as $att_id => $attachment) {

							$full_img_url = wp_get_attachment_url($image_post_id);		

							?>
							<div><center>
							<?php
								if(!empty($full_img_url)) { 							
									if( strpos( $full_img_url , '/WPC/') !== false ) {  ?>
								<img class="img-style" src='<?php echo $full_img_url .'/part1/part1.png'; ?>'/> 
							<?php } else { ?>

								<img class="img-style" src='<?php echo $full_img_url; ?>'/> 
							<?php } 
								}
							?>

							</center>
							</div>

					<?php	//} 

					?>
		            
		        </div>
					

	    		<?php 
				}

	    			$post_video = get_post_meta($post->ID, 'post_video', true);

	    			if(!empty($post_video)) {

	    		?>

	    		<div id="ab-video-text"><span><i class="fa fa-youtube-play"></i><?php _e( 'Video', 'classify' ); ?></span></div>

	    		<div id="ab-video"><?php echo $post_video; ?></div>

	    		<?php } ?>
					
					<!--<div class="author-info clearfix">
						<span class="author-avatar">
				    			<?php $user_ID = $post->post_author;
								
								$author_avatar_url = get_user_meta($user_ID, "classify_author_avatar_url", true); 

								if(!empty($author_avatar_url)) {

									$params = array( 'width' => 150, 'height' => 150, 'crop' => true );

									echo "<img class='avatar avatar-150 photo' src='" . bfi_thumb( "$author_avatar_url", $params ) . "' alt='' />";

								} else { 
									 echo get_avatar($user_ID, 150);
								}
								
								?>
				    		</span>
							
						<div class="author-detail-right clearfix">

				    		<?php $curauth = get_user_by( 'id', get_queried_object()->post_author ); // get the info about the current author ?>
																		
							<?php
								$wpcrown_author_address = $curauth->address;
																																
								if(!empty($wpcrown_author_address)) {
							?>
								<span class="ad-detail-info">
					    			<span class="ad-details"><i class="fa fa-map-marker"></i><?php echo $wpcrown_author_address; ?></span>
								</span>
							<?php
								} 		
							?>							
				    		<?php $curauth = get_user_by( 'id', get_queried_object()->post_author ); // get the info about the current author ?>
																		
							<?php
																			
								$wpcrown_author_phone = $curauth->phone;
																																
								if(!empty($wpcrown_author_phone)) {
							?>
								<span class="ad-detail-info"> 
				    				<span class="ad-details"><i class="fa fa-phone"></i><?php echo $wpcrown_author_phone; ?></span>
								</span>
							<?php
								} 		
							?>

							<?php $curauth = get_user_by( 'id', get_queried_object()->post_author ); // get the info about the current author ?>
																		
							<?php
								$wpcrown_author_web = $curauth->user_url;
																																
								if(!empty($wpcrown_author_web)) {
							?>
								<span class="ad-detail-info">
					    			<span class="ad-details"><i class="fa fa-globe"></i><a href="<?php echo $wpcrown_author_web; ?>"><?php echo $wpcrown_author_web; ?></a></span>
								</span>
							<?php } ?>
							</div>
							<div class="ad-detail-info author-btn">
								<span class="author-profile-ad-details"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="button-ag large green"><span class="button-inner"><?php echo get_the_author_meta('display_name', $user_ID ); ?></span></a></span>
							</div>
							<div class="follow-btn">
								<?php if ( is_user_logged_in() ) { 
								global $current_user;
									wp_get_current_user();
									$user_id = $current_user->ID;
								if($user_ID != $user_id){							
								echo wp_authors_follower_check($user_ID, $user_id);
								}} ?>
								<?php echo wp_authors_favorite_check($user_id,$post->ID); ?>
							</div>
					</div> -->
				<table class="ad-detail-half-box">
					
					<tr>
						<td>
							
								<div class="divider"></div>
								<div class="detail-cat">
								
								<div class="category-icon">
									<?php 
										echo '<h2>' . get_cat_name( $mylink['category'] ) . '</h2>';
									
								?>
								</div>
				    			
							</div>
								<!--CustomFieldsarea-->
							<div >
								<span class="ad-detail-info" style="text-align: center;font-size: 14px;color: #3f3d59;">About Your Business</span>
							
							</div>	
							<?php 
								$business_name = $mylink['business_name']; 
								if(!empty($business_name)) {
							?>

							<span class="ad-detail-info"><?php _e( 'Business Name :- ', 'classify' ); ?> <span class="ad-detail">
						    	<?php 
									if('Yes' == trim($mylisting['name_in_caps']) && 'No' == trim($mylisting['bold_print']) ) {
														
														echo strtoupper($business_name);
														
													} 
													if('Yes' == trim($mylisting['bold_print']) && 'No' == trim($mylisting['name_in_caps']) ) {
														
														echo '<b>' . $business_name . '</b>';
														
													} 
													if('Yes' == trim($mylisting['name_in_caps']) && 'Yes' == 			   trim($mylisting['bold_print'])) { 
														echo '<b>' . strtoupper($business_name) . '</b>';
														
													}  
													if('No' == trim($mylisting['name_in_caps']) && 'No' == trim($mylisting['bold_print'])) { 
														echo $business_name;
													}	
								?></span>
							</span>

							<?php } ?>	
							
							
							<?php 
								$web_address = $mylink['web_address']; 
								if(!empty($web_address)) {
							?>

							<span class="ad-detail-info"><?php _e( 'Web Address', 'classify' ); ?> <span class="ad-detail">
						    	<?php echo $web_address; ?></span>
							</span>

							<?php } ?>	
							
							<?php 
								$business_phone_number = $mylink['business_phone_number'];  
								if(!empty($business_phone_number)) {
							?>

							<span class="ad-detail-info"><?php _e( 'Phone Number', 'classify' ); ?> <span class="ad-detail">
						    	<?php echo $business_phone_number; ?></span>
							</span>

							<?php } ?>	
							
							<?php 
							
							    $business_type =  get_cat_name( $mylink['category'] ); 
								
								if(!empty($business_type)) {
									
								//	get_the_category_by_ID( int $cat_ID )
							?>

							<span class="ad-detail-info"><?php _e( 'Business Type', 'classify' ); ?> <span class="ad-detail">
						    	<?php echo $business_type; ?></span>
							</span>

							<?php } ?>	
							
<?php 
								$questionnaire_id = $mylink['id'];
												
$qry_busn = "SELECT ba.*
														FROM {$wpdb->prefix}business_addresses As ba
														WHERE ba.questionnaire_id ='". $questionnaire_id ."'";
																
												$arrBusinessDetails = $wpdb->get_results($qry_busn,  ARRAY_A );
												$addr = "";
												foreach( $arrBusinessDetails as $keyIndex => $business_details ){ 
													$addr.= $business_details['street_address'] . ', ' . $business_details['address']. ', '. $business_details['city']. ', '.
													$business_details['state'] . ', ' . $business_details['country'] . ', '. $business_details['ziocode'] . ', '.$business_details['contact'] .'.'. '<br/>';
												}
												$addr = trim($addr, ',');
								if(!empty($addr)) {
							?>
						<span class="ad-detail-info"><?php _e( 'Business Address:', 'classify' ); ?> <span> <?php echo $addr; ?></span></span>
							

							<?php } ?>	

								
							
							<span class="ad-detail-info"><?php _e( 'Added', 'classify' ); ?> <span class="ad-detail">
						    	<?php the_time('M j, Y') ?></span>
							</span>

							<?php 
								$post_location = get_post_meta($post->ID, 'post_location', true); 
								if(!empty($post_location)) {
							?>

							<span class="ad-detail-info"><?php _e( 'Location', 'classify' ); ?> <span class="ad-detail">
						    	<?php echo $post_location; ?></span>
							</span>

							<?php } ?>							

							<span class="ad-detail-info"><?php _e( 'Views', 'classify' ); ?> <span class="ad-detail">
				    			<?php echo wpb_get_post_views(get_the_ID()); ?></span>
							</span>


							<?php if(function_exists('the_ratings')) { ?>

								<div class="ad-detail-info"><?php _e( 'Rating', 'classify' ); ?> 
									<div class="ad-detail"><?php the_ratings(); ?></div>
								</div>

							<?php } ?>
							
                            <?php 
								$business_purpose = $mylink['business_purpose'];
								if(!empty($business_purpose)) {
							?>
						<span class="ad-detail-info"><?php _e( 'Description :', 'classify' ); ?> <span> <?php echo $business_purpose; ?></span></span>
							

							<?php } ?>
						</td>
					</tr>
				</table>
				
				
	    		<?php

					$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
					$post_longitude = get_post_meta($post->ID, 'post_longitude', true);
					$post_address = get_post_meta($post->ID, 'post_address', true);

					if(!empty($post_latitude)) {

				?>

			    <div id="single-page-map">

			    	<div id="ad-address"><span><i class="fa fa-map-marker"></i><?php echo $post_address; ?></span></div>

					<div id="single-page-main-map"></div>

					<script type="text/javascript">
					var mapDiv,
						map,
						infobox;
					jQuery(document).ready(function($) {

						mapDiv = $("#single-page-main-map");
						mapDiv.height(400).gmap3({
							map: {
								options: {
									"center": [<?php echo $post_latitude; ?>,<?php echo $post_longitude; ?>]
									,"zoom": 16
									,"draggable": true
									,"mapTypeControl": true
									,"mapTypeId": google.maps.MapTypeId.ROADMAP
									,"scrollwheel": false
									,"panControl": true
									,"rotateControl": false
									,"scaleControl": true
									,"streetViewControl": true
									,"zoomControl": true
									<?php global $redux_demo; $map_style = $redux_demo['map-style']; if(!empty($map_style)) { ?>,"styles": <?php echo $map_style; ?> <?php } ?>
								}
							}
							,marker: {
								values: [

								<?php

									$post_latitude = get_post_meta($post->ID, 'post_latitude', true);
									$post_longitude = get_post_meta($post->ID, 'post_longitude', true);

									$theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 40) ? substr($theTitle,0,37).'...' : $theTitle;

									$post_price = get_post_meta($post->ID, 'post_price', true);

									$category = get_the_category();

									if ($category[0]->category_parent == 0) {

										$tag = $category[0]->cat_ID;

										$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
										if (isset($tag_extra_fields[$tag])) {
											$your_image_url = $tag_extra_fields[$tag]['your_image_url']; //i added this line.
										}

									} else {

										$tag = $category[0]->category_parent;

										$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
										if (isset($tag_extra_fields[$tag])) {
											$your_image_url = $tag_extra_fields[$tag]['your_image_url']; //i added this line.
										}

									}

									if(!empty($your_image_url)) {

								    	$iconPath = $your_image_url;

								    } else {

								    	$iconPath = get_template_directory_uri() .'/images/icon-services.png';

								    }

									?>

										 	{
										 		<?php require_once(TEMPLATEPATH . "/inc/BFI_Thumb.php"); ?>
												<?php $params = array( "width" => 560, "height" => 390, "crop" => true ); $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "single-post-thumbnail" ); ?>

												latLng: [<?php echo $post_latitude; ?>,<?php echo $post_longitude; ?>],
												options: {
													icon: "<?php echo $iconPath; ?>",
													shadow: "<?php echo get_template_directory_uri() ?>/images/shadow.png",
												},
												data: '<div class="marker-holder"><div class="marker-content"><div class="marker-image"><img src="<?php echo bfi_thumb( "$image[0]", $params ) ?>" /></div><div class="marker-info-holder"><div class="marker-info"><div class="marker-info-title"><?php echo $theTitle; ?></div><div class="marker-info-extra"><div class="marker-info-price"><?php echo $post_price; ?></div><div class="marker-info-link"><a href="<?php the_permalink(); ?>"><?php _e( "Details", "classify" ); ?></a></div></div></div></div><div class="arrow-down"></div><div class="close"></div></div></div>'
											}	
									
								],
								options:{
									draggable: false
								},
								cluster:{
					          		radius: 20,
									// This style will be used for clusters with more than 0 markers
									0: {
										content: "<div class='cluster cluster-1'>CLUSTER_COUNT</div>",
										width: 62,
										height: 62
									},
									// This style will be used for clusters with more than 20 markers
									20: {
										content: "<div class='cluster cluster-2'>CLUSTER_COUNT</div>",
										width: 82,
										height: 82
									},
									// This style will be used for clusters with more than 50 markers
									50: {
										content: "<div class='cluster cluster-3'>CLUSTER_COUNT</div>",
										width: 102,
										height: 102
									},
									events: {
										click: function(cluster) {
											map.panTo(cluster.main.getPosition());
											map.setZoom(map.getZoom() + 2);
										}
									}
					          	},
								events: {
									click: function(marker, event, context){
										map.panTo(marker.getPosition());

										var ibOptions = {
										    pixelOffset: new google.maps.Size(-125, -88),
										    alignBottom: true
										};

										infobox.setOptions(ibOptions)

										infobox.setContent(context.data);
										infobox.open(map,marker);

										// if map is small
										var iWidth = 560;
										var iHeight = 560;
										if((mapDiv.width() / 2) < iWidth ){
											var offsetX = iWidth - (mapDiv.width() / 2);
											map.panBy(offsetX,0);
										}
										if((mapDiv.height() / 2) < iHeight ){
											var offsetY = -(iHeight - (mapDiv.height() / 2));
											map.panBy(0,offsetY);
										}

									}
								}
							}
							 		 	});

						map = mapDiv.gmap3("get");

					    infobox = new InfoBox({
					    	pixelOffset: new google.maps.Size(-50, -65),
					    	closeBoxURL: '',
					    	enableEventPropagation: true
					    });
					    mapDiv.delegate('.infoBox .close','click',function () {
					    	infobox.close();
					    });

					    if (Modernizr.touch){
					    	map.setOptions({ draggable : false });
					        var draggableClass = 'inactive';
					        var draggableTitle = "Activate map";
					        var draggableButton = $('<div class="draggable-toggle-button '+draggableClass+'">'+draggableTitle+'</div>').appendTo(mapDiv);
					        draggableButton.click(function () {
					        	if($(this).hasClass('active')){
					        		$(this).removeClass('active').addClass('inactive').text("Activate map");
					        		map.setOptions({ draggable : false });
					        	} else {
					        		$(this).removeClass('inactive').addClass('active').text("Deactivate map");
					        		map.setOptions({ draggable : true });
					        	}
					        });
					    }

					});
					</script>

				</div>

				<?php } ?>
		
	    	</div>
            <div class="ad-detail-content">
                <h4> WEBSITE ADDITION TOTAL: $<?php echo $total; ?> </h4>
                <h5>NOTE: ALL LISTINGS ARE POSTED FOR 3 MONTH INTERVALS FOR THE DIRECTORY & WEBSITE </h5>
			</div>
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
		font-weight: bold;
		margin-top: 50px;
		font-size: 18px;
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
#listing-content .ad-ratings {
    padding: 0px 15px !important;
    position: relative;
	 float: right;
	 margin-top: 20px;
}
.listing-ads .ad-ratings {
   
    padding: 15px;
    margin-right: 5px;
	font-family: "Open Sans";
	line-height: 24px;
	font-weight: 400;
	font-style: normal;
	color: #0a0a0a;
	font-size: 14px;
	
    
}	
#listing-content .post-title-list a {
    color: #000 !important;
} 
.small-desc {
	color: #0a0a0a;
	font-size: 14px;
	font-weight: normal;
    font-family: "Armata","Helvetica Neue",Arial,Helvetica,Geneva,sans-serif;
}	
.listing-ads #listing-content .post-title-cat {
    margin-top: 10px !important;
}
.listing-ads .post-title-cat {
    position: relative;
    border: none;
    box-shadow: none;
    height: 30px;
	margin-top: 10px !important;
}
.category-icon {
    left: 40%;
}
</style>
<?php get_footer(); ?>
