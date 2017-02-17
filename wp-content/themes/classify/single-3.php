<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */

get_header(); ?>
	
	<?php while ( have_posts() ) : the_post(); ?>

<?php 
	$blogpost = get_post_meta($post->ID, 'blog_posts', true);	
	if ($blogpost == 'blog_posts'){
		echo "this is blog post";
	}
?>
<?php 

global $redux_demo; 

global $current_user; wp_get_current_user(); $user_ID == $current_user->ID;

$contact_email = get_the_author_meta( 'user_email' );
$wpcrown_contact_email_error = $redux_demo['contact-email-error'];
$wpcrown_contact_name_error = $redux_demo['contact-name-error'];
$wpcrown_contact_message_error = $redux_demo['contact-message-error'];
$wpcrown_contact_thankyou = $redux_demo['contact-thankyou-message'];

global $nameError;
global $emailError;
global $commentError;
global $subjectError;
global $humanTestError;

//If the form is submitted
if(isset($_POST['submitted'])) {
	
		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError = $wpcrown_contact_name_error;
			$hasError = true;
		} elseif(trim($_POST['contactName']) === 'Name*') {
			$nameError = $wpcrown_contact_name_error;
			$hasError = true;
		}	else {
			$name = trim($_POST['contactName']);
		}

		//Check to make sure that the subject field is not empty
		if(trim($_POST['subject']) === '') {
			$subjectError = $wpcrown_contact_subject_error;
			$hasError = true;
		} elseif(trim($_POST['subject']) === 'Subject*') {
			$subjectError = $wpcrown_contact_subject_error;
			$hasError = true;
		}	else {
			$subject = trim($_POST['subject']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = $wpcrown_contact_email_error;
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = $wpcrown_contact_email_error;
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
			
		//Check to make sure comments were entered	
		if(trim($_POST['comments']) === '') {
			$commentError = $wpcrown_contact_message_error;
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}

		//Check to make sure that the human test field is not empty
		if(trim($_POST['humanTest']) != '8') {
			$humanTestError = "Not Human :(";
			$hasError = true;
		} else {

		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {

			$emailTo = $contact_email;
			$subject = $subject;	
			$body = "Name: $name \n\nEmail: $email \n\nMessage: $comments";
			$headers = 'From <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			wp_mail($emailTo, $subject, $body, $headers);

			$emailSent = true;

	}
}
if(isset($_POST['follower'])){
	$author_id = $_POST['author_id'];
	$follower_id = $_POST['follower_id'];
	echo wp_authors_insert($author_id, $follower_id);	
}
if(isset($_POST['unfollow'])){
	$author_id = $_POST['author_id'];
	$follower_id = $_POST['follower_id'];
	echo wp_authors_unfollow($author_id, $follower_id);	
}
if(isset($_POST['favorite'])){
	$author_id = $_POST['author_id'];
	$post_id = $_POST['post_id'];
	echo wp_favorite_insert($author_id, $post_id);
}


?>

    <section class="ads-main-page">
        <div class="container">
        <?php
            if( get_post_status ( $post->ID ) == 'pending'  ){

                $string = $_SERVER[REQUEST_URI];
                preg_match_all("/(&preview=false)/", $string, $matches);

                if( ( $matches[0][0] === '&preview=false') || ( $matches[1][0] === '&preview=false') ){
                        echo do_action('review_post_init');
                }else{
                        echo do_shortcode('[notification_box]<strong>Congratulation!</strong> Your Ad has submitted and pending for review. After review your Ad will be live for all users. You may not preview it more than once.[/notification_box]');
                }
            }                                        
        ?>
        </div>	
				<div class="ad-title">
					<h2><?php the_title(); ?>
						<?php global $current_user; wp_get_current_user(); 
						if ($post->post_author == $current_user->ID) { ?>

						<?php 
							global $redux_demo; 
							$edit_post_page_id = $redux_demo['edit_post'];
							$postID = $post->ID;

							global $wp_rewrite;
							if ($wp_rewrite->permalink_structure == '')
							//we are using ?page_id
								$edit_post = $edit_post_page_id."&post=".$postID;
							else
							//we are using permalinks
								$edit_post = $edit_post_page_id."?post=".$postID;

							?>
							<a href="<?php echo $edit_post; ?>"><?php esc_html_e( '(Edit)', 'classify' ); ?></a>

						<?php } ?> - <span class="ad-page-price"><?php $post_price = get_post_meta($post->ID, 'post_price', true); echo $post_price; ?></span>
					</h2>
				</div>

    	<div class="container">

	    	<div class="span8 first">
			
				<?php
				$attachments = get_children(array('post_parent' => $post->ID,
							'post_status' => 'inherit',
							'post_type' => 'attachment',
							'post_mime_type' => 'image',
							'order' => 'ASC',
							'orderby' => 'menu_order ID'));	
				if(!empty($attachments)){
				?>
				<style scoped>#galleria{height:500px;margin-bottom:80px;}</style>
				<?php
				}
				?>
				
	    		<div id="galleria">
	    			<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>

						<?php 

						$params = array( 'width' => 770, 'height' => 500, 'crop' => true );
						$params_small = array( 'width' => 100, 'height' => 70, 'crop' => true );

											

						foreach($attachments as $att_id => $attachment) {

							$full_img_url = wp_get_attachment_url($attachment->ID);

							echo "<a href='" . bfi_thumb( "$full_img_url", $params ) . "'><img alt='image' src='" . bfi_thumb( $full_img_url, $params_small ) . "' data-big='" . $full_img_url . "' ></a>";
							

						} 

					?>
		            
		        </div>


	    		<?php 

	    			$post_video = get_post_meta($post->ID, 'post_video', true);

	    			if(!empty($post_video)) {

	    		?>

	    		<div id="ab-video-text"><span><i class="fa fa-youtube-play"></i><?php _e( 'Video', 'classify' ); ?></span></div>

	    		<div id="ab-video"><?php echo $post_video; ?></div>

	    		<?php } ?>
					
					<div class="author-info clearfix">
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
					</div>
				<table class="ad-detail-half-box">
					
					<tr>
						<td>
							<div class="detail-cat">
								<?php
									$category = get_the_category();
									
									$tag = get_cat_ID( $category[0]->name );
									$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
									if (isset($tag_extra_fields[$tag])) {
										$category_icon_code = $tag_extra_fields[$tag]['category_icon_code']; 
										$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
									}
								?>
								<div class="category-icon">
									<?php if(!empty($category_icon_code)) { ?>

										<div class="category-icon-box" style="background-color: <?php echo $category_icon_color; ?>;"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>

									<?php } ?>
								</div>
				    			<?php 
								
									
									if ($category) {
										echo '<a href="' . get_category_link( $category[0]->term_id ) . '" title="' . sprintf( __( "View all posts in %s", "classify" ), $category[0]->name ) . '" ' . '>' . $category[0]->name.'</a> ';
									}
								?>
							</div>
								<div class="divider"></div>
								<!--CustomFieldsarea-->
							<?php 
							$classifyCustomFields = get_post_meta($post->ID, 'custom_field', true);
							if(!empty($classifyCustomFields)) {
										for ($i = 0; $i < count($classifyCustomFields); $i++) {
											
											if($classifyCustomFields[$i][2] != 'dropdown' && $classifyCustomFields[$i][2] != 'checkbox'){
												
												?>
												<?php if(!empty($classifyCustomFields[$i][1]) && !empty($classifyCustomFields[$i][0]) ) { ?>
												<span class="ad-detail-info">
													<span class="ad-detail">
														<?php echo $classifyCustomFields[$i][1]; ?>
													</span>
													<?php echo $classifyCustomFields[$i][0]; ?>
												</span>

												<?php }  ?>
											<?php } ?>
										<?php }
										
										for ($i = 0; $i < count($classifyCustomFields); $i++) {
											
											if($classifyCustomFields[$i][2] == 'dropdown'){
												?>
												<?php if(!empty($classifyCustomFields[$i][1]) && !empty($classifyCustomFields[$i][0]) ) { ?>
												<span class="ad-detail-info">
													<span class="ad-detail">
														<?php echo $classifyCustomFields[$i][1]; ?>
													</span>
														<?php echo $classifyCustomFields[$i][0]; ?>
												</span>

												<?php }  ?>
											<?php } ?>
										<?php }  ?>	
											<!--CheckBox-->
											<?php for ($i = 0; $i < count($classifyCustomFields); $i++) {
											
											if($classifyCustomFields[$i][2] == 'checkbox'){
												?>
												<?php if(!empty($classifyCustomFields[$i][1]) && !empty($classifyCustomFields[$i][0]) ) { ?>
												<span class="ad-detail-info">
													<span class="ad-detail">
														<?php esc_html_e( 'Yes', 'classiera' ); ?>
													</span>
														<?php echo $classifyCustomFields[$i][0]; ?>
												</span>

												<?php }  ?>
											<?php } ?>
										<?php }  ?>	
											<!--Checkbox-->
										
									<?php } ?>
							<!--CustomFieldsarea-->
							
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
							<div class="ad-detail-info"><?php _e( 'Description:', 'classify' ); ?> 
								<?php echo the_content(); ?>
							</div>
							<div class="ads-tags">

								<i class="fa fa-tag"></i><span><a><?php esc_html_e( 'Tags', 'classify' ); ?></a></span><span><?php the_tags('','',''); ?></span>
								<ul class="links">

									<li class="service-links-facebook-share">
										<div id="fb-root"></div>
										<script>(function(d, s, id) {
											var js, fjs = d.getElementsByTagName(s)[0];
											if (d.getElementById(id)) return;
											js = d.createElement(s); js.id = id;
											js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=247363645312964";
											fjs.parentNode.insertBefore(js, fjs);
											}(document, 'script', 'facebook-jssdk'));</script>
										<div class="fb-share-button" data-href="<?php the_permalink(); ?>" data-type="button_count"></div>
									</li>

									<li class="service-links-google-plus-one last">
										<!-- Place this tag where you want the share button to render. -->
										<div class="g-plus" data-action="share" data-annotation="bubble"></div>

										<!-- Place this tag after the last share tag. -->
										<script type="text/javascript">
											(function() {
												var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
												po.src = 'https://apis.google.com/js/platform.js';
												var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
											})();
										</script>
									</li>

									<li class="service-links-twitter-widget first">
										    <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en"><?php esc_html_e( 'Tweet', 'classify' ); ?></a>

										<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
									</li>
								</ul>

							</div>
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
				

				<div class="ad-detail-content">	    			
	    			<?php wp_link_pages(); ?>

	    		</div>
				<?php
	    		global $redux_demo;
				$hiderads = $redux_demo['hide-rads'];
				?>			
				<?php if($hiderads == 1) { ?>
	    		<div class="related-ads">
				
				<?php 
					$homeAdImg= $redux_demo['post_ad']['url']; 
					$homeAdImglink= $redux_demo['post_ad_link'];
					$homeAdCode = $redux_demo['post_ad_code_client']; 
					
					if(!empty($homeAdCode) || !empty($homeAdImg)){
					if(!empty($homeAdCode)){
							$homeAd = $homeAdCode;
					}else{
							$homeAd = '<a href="'.$homeAdImglink.'" target="_blank"><img alt="image" src="'.$homeAdImg.'" /></a>';
					}
					}
				?>
				
				<div class="post-page-ad">				
					<?php if(isset($homeAd)){ echo $homeAd;} ?>
				</div>

	    			<h3><?php _e( 'RELATED ADS', 'classify' ); ?></h3>

	    			<div class="full">

	    				<?php  
							$orig_post = $post;  
							global $post;  
							$tags = wp_get_post_tags($post->ID);  
										      
							if ($tags) {  
								$tag_ids = array();  
								foreach($tags as $individual_tag) 
								$tag_ids[] = $individual_tag->term_id; 
									
								$args=array(  
								    'tag__in' => $tag_ids,  
								    'post__not_in' => array($post->ID),  
								    'posts_per_page'=>3, // Number of related posts to display.  
								    'ignore_sticky_posts'=>1  
								);  

								$current = -1;
								      
								$my_query = new wp_query( $args );  
								while( $my_query->have_posts() ) { 

								    $my_query->the_post();  
								    global $postID;

								    $current++;
									
									$category = get_the_category();
									
									$tag = get_cat_ID( $category[0]->name );
									$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
									if (isset($tag_extra_fields[$tag])) {
										$category_icon_code = $tag_extra_fields[$tag]['category_icon_code']; 
										$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
									}
								
								?>  
		    						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		    						<div class=" ad-box span3 <?php if($current%4 == 0) { echo 'first'; } ?>">
		    							
		    							<div class="field-content">

		    								<div class="ad-image-related">

		    									<a href="<?php the_permalink(); ?>">
		    										<?php 

		    										$thumb_id = get_post_thumbnail_id();
													$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);

													$params = array( 'width' => 255, 'height' => 218, 'crop' => true );
													echo "<img alt='image' class='add-box-main-image' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";

													?></a>

		    								</div>	    									
		    								
		    								
		    							</div> 									
										
										<div class="ad-hover-content">
										
											<div class="ad-category">
												<?php if(!empty($category_icon_code)) { ?>

													<div class="category-icon-box" style="background-color: <?php echo $category_icon_color; ?>;"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>

												<?php } ?>
											</div>
											
		    									<span class="add-price"><?php $postID = get_the_ID(); echo get_post_meta($postID, 'post_price', true); ?></span>
		    							</div>
										
										<div class="post-title">
												<a href="<?php the_permalink(); ?>">
		    											<span class="title"><?php the_title(); ?></span>
		    									</a>
										</div>

		    						</div>
		    						</div>

		    			<?php 	}  
							}  
							$post = $orig_post;  
							wp_reset_query();  
						?>

	    			</div>

	    		</div>
				<?php } ?>
				
				<?php
				$author_message_box_on = $redux_demo['author-msg-box-off'];
				if($author_message_box_on == 1){
				?>
				<div class="full">
						<h3><?php _e( 'TO AUTHOR', 'classify' ); ?></h3>

						
						<div id="contact-ad-owner-v2">

							<?php if(isset($emailSent) && $emailSent == true) { ?>

								<div class="full">
									<h5><?php echo $wpcrown_contact_thankyou ?></h5> 
								</div>

							<?php } else { ?>

							<?php if($nameError != '') { ?>
								<div class="full">
									<h5><?php echo $nameError;?></h5> 
								</div>										
							<?php } ?>
															
							<?php if($emailError != '') { ?>
								<div class="full">
									<h5><?php echo $emailError;?></h5>
								</div>
							<?php } ?>

							<?php if($subjectError != '') { ?>
								<div class="full">
									<h5><?php echo $subjectError;?></h5>  
								</div>
							<?php } ?>
															
							<?php if($commentError != '') { ?>
								<div class="full">
									<h5><?php echo $commentError;?></h5>
								</div>
							<?php } ?>

							<?php if($humanTestError != '') { ?>
								<div class="full">
									<h5><?php echo $humanTestError;?></h5>
								</div>
							<?php } ?>

							<form name="contactForm" action="<?php the_permalink(); ?>" id="contact-form" method="post" class="contactform" >
																
								<input type="text" onfocus="if(this.value=='Name*')this.value='';" onblur="if(this.value=='')this.value='Name*';" name="contactName" id="contactName" value="Name*" class="input-textarea half" />
															 
								<input type="text" onfocus="if(this.value=='Email*')this.value='';" onblur="if(this.value=='')this.value='Email*';" name="email" id="email" value="Email*" class="input-textarea half" style="margin-right:0px !important;" />

								<input type="text" onfocus="if(this.value=='Subject*')this.value='';" onblur="if(this.value=='')this.value='Subject*';" name="subject" id="subject" value="Subject*" class="input-textarea" />
															 
								<textarea name="comments" id="commentsText" cols="8" rows="5" ></textarea>

								<p class="humantest"><?php _e("Human test. Please input the result of 5+3=?", "classify"); ?></p>

								<input type="text" onfocus="if(this.value=='')this.value='';" onblur="if(this.value=='')this.value='';" name="humanTest" id="humanTest" value="" class="input-textarea half" />
																
								<input style="margin-bottom: 0; float: left;" name="submitted" type="submit" value="Send Message" class="input-submit"/>	
															
							</form>

							<?php } ?>

						</div>

					</div>
					<?php } ?>
					
					

	    		<div id="ad-comments">
					
	    		</div>

	    	</div>

			<div class="span4">

		    	<?php get_sidebar('pages'); ?>


	    	</div>

	    </div>

    </section>



    <?php endwhile; ?>


<?php get_footer(); ?>
