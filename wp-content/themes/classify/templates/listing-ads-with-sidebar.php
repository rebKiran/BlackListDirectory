		<?php 
			  global $current_view_type;
			  global $permalink_type;
			  global $redux_demo;
			  $trns_classify_ads = $redux_demo['trns_classify_ads'];
			  $trns_latest_ads = $redux_demo['trns_latest_ads'];
			  $trns_popular_ads = $redux_demo['trns_popular_ads'];
			  $trns_random_ads = $redux_demo['trns_random_ads'];
			  $trns_read_btn = $redux_demo['read_more_btn'];
		?>
	<div class="container listing-with-sidebar">
		<section id="ads-homepage" class="listing-ads with-sidebar span8">
		<h2 class="main-title"><?php echo $trns_classify_ads; ?></h2>
			<div class="h2-seprator"></div>		       
        <div class="span8">	
				<ul class="tabs quicktabs-tabs quicktabs-style-nostyle clearfix">
				<div class="three-tabs">
					<li >
						<a style="font-size: 14px !important; " class="current" href="#"><?php echo $trns_latest_ads; ?></a>
					</li>
					<li>
						<a style="font-size: 14px !important;" class="" href="#"><?php echo $trns_popular_ads; ?></a>
					</li>
					<li>
						<a style="font-size: 14px !important;" class="" href="#"><?php echo $trns_random_ads; ?></a>
					</li>
					
					</div>					
				</ul>
				<ul class="view-types">
					<li class="view-type list-view <?php if($current_view_type == 'list'){ echo "activate";} ?>">
						<a href="<?php echo get_permalink().$permalink_type."view-type=list"; ?>">
							<i class="fa fa-list-ul"></i>
						</a>
					</li>
					<li class="view-type grid-view <?php if($current_view_type == 'grid'){ echo "activate";} ?>">
						<a href="<?php echo get_permalink().$permalink_type."view-type=grid"; ?>">
							<i class="fa fa-th"></i>
						</a>
					</li>
				</ul>
				<!--Start Latest Ads -->
			<div class="pane latest-ads-holder">
				<div class="latest-ads-grid-holder">
				<?php
					global $paged, $wp_query, $wp;
					$args = wp_parse_args($wp->matched_query);
					if ( !empty ( $args['paged'] ) && 0 == $paged ) {
						$wp_query->set('paged', $args['paged']);
						$paged = $args['paged'];
					}

					$cat_id = get_cat_ID(single_cat_title('', false));
					$temp = $wp_query;
					$wp_query= null;					
					$ads_counter = $redux_demo['home-ads-counter'];					
					$wp_query = new WP_Query();
					$wp_query->query('post_type=post&posts_per_page='.$ads_counter.'&paged='.$paged);
					$current = -1;
					$current2 = 0;
					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>
						<div class="span8 ad-box-main">						
							<div class="ad-box span3">
								<?php
								if ( has_post_thumbnail()) {
								   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
								   echo '<a class="ad-image" href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" >';
								   echo get_the_post_thumbnail($post->ID, '291x250'); 
								   echo '</a>';
								 }
								?>
								<div class="ad-hover-content">
									<div class="ad-category">												
										<?php
											$category = get_the_category();
											if ($category[0]->category_parent == 0) {
												$tag = $category[0]->cat_ID;
												$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
												if (isset($tag_extra_fields[$tag])) {
													$category_icon_code = $tag_extra_fields[$tag]['category_icon_code']; 
													$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
												}
											} else {
												$tag = $category[0]->category_parent;
												$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
												if (isset($tag_extra_fields[$tag])) {
													$category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
													$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
												}
											}
											if(!empty($category_icon_code)) {
										?>

										<div class="category-icon-box" style="background-color: <?php echo $category_icon_color; ?>;"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>
										<?php } 
										$category_icon_code = "";
										?>
									</div>										
									<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
									<div class="add-price"><span><?php echo $post_price; ?></span></div> 
								</div>	
							</div>	
								
							<div class="post-title-cat span3">
								<div class="post-title-list">
									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,50).'...' : $theTitle; echo $theTitle; ?></a>
								</div>
							</div>
							<?php if(function_exists('the_ratings')) { ?>

								<span class="ad-ratings"><?php the_ratings(); ?></span>

							<?php } ?>
								
							<div class="ad-description span5">
								<?php echo substr(get_the_content(), 0, 200); ?> 
							</div>
							<div class="ads-tags span3">
								<i class="fa fa-tags"></i><span class="tag-title"><a><?php _e('Tags', 'classify'); ?>:</a></span><span><?php the_tags('','',''); ?></span>
							</div>
							<div class="readbutton clearfix">
								<div class="readbutton-inner">
									<a href="<?php the_permalink(); ?> ">
										<span><?php echo $trns_read_btn; ?></span>
									</a>
								</div>				
							</div>	
						</div>
					<?php endwhile; ?>

				</div>
			<!-- Begin wpcrown_pagination-->	
				<?php get_template_part('pagination'); ?>
				<!-- End wpcrown_pagination-->
				<div class="clearfix"></div>				
			<?php wp_reset_query(); ?>
			</div>
			<!--End Latest Ads -->
			<!--Start Popular Ads -->
			<div class="pane popular-ads-grid-holder">
				<div class="popular-ads-grid">
					<?php
						global $paged, $wp_query, $wp;
						$args = wp_parse_args($wp->matched_query);
						if ( !empty ( $args['paged'] ) && 0 == $paged ) {
							$wp_query->set('paged', $args['paged']);
							$paged = $args['paged'];
						}
						$cat_id = get_cat_ID(single_cat_title('', false));
						$ads_counter = $redux_demo['home-ads-counter'];
						$current = -1;
						$current2 = 0;

						$popularpost = new WP_Query( array( 'posts_per_page' => $ads_counter, 'cat' => $cat_id, 'posts_type' => 'post', 'paged' => $paged, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );	
						
						while ( $popularpost->have_posts() ) : $popularpost->the_post(); $current++; $current2++;
						?>
						<div class="span8 ad-box-main">						
								<div class="ad-box span3">
									<?php
										if ( has_post_thumbnail()) {
										   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
										   echo '<a class="ad-image" href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" >';
										   echo get_the_post_thumbnail($post->ID, '291x250'); 
										   echo '</a>';
										 }
									?>
									<!--Ad Hover Content -->
									<div class="ad-hover-content">
										<div class="ad-category">												
											<?php
												$category = get_the_category();
												if ($category[0]->category_parent == 0) {
													$tag = $category[0]->cat_ID;
													$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
													if (isset($tag_extra_fields[$tag])) {
														$category_icon_code = $tag_extra_fields[$tag]['category_icon_code']; 
														$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
													}
												} else {
													$tag = $category[0]->category_parent;
													$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
													if (isset($tag_extra_fields[$tag])) {
														$category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
														$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
													}
												}
												if(!empty($category_icon_code)) {
											?>

											<div class="category-icon-box" style="background-color: <?php echo $category_icon_color; ?>;"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>
											<?php } 
											$category_icon_code = "";
											?>
										</div>										
										<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
										<div class="add-price"><span><?php echo $post_price; ?></span></div> 
									</div>
									<!--Ad Hover Content -->
										
								</div>
								
								<div class="post-title-cat span3">
									<div class="post-title-list">
										<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,50).'...' : $theTitle; echo $theTitle; ?></a>
									</div>
								</div>
								<?php if(function_exists('the_ratings')) { ?>

									<span class="ad-ratings"><?php the_ratings(); ?></span>

								<?php } ?>
								
								<div class="ad-description span5">
									<?php echo substr(get_the_content(), 0, 200); ?> 
								</div>
								
								<div class="ads-tags span3">
									<i class="fa fa-tags"></i><span class="tag-title"><a><?php _e('Tags', 'classify'); ?>:</a></span><span><?php the_tags('','',''); ?></span>
								</div>
								
								<div class="readbutton clearfix">
									<div class="readbutton-inner">
										<a href="<?php the_permalink(); ?> ">
											<span><?php echo $trns_read_btn; ?></span>
										</a>
									</div>				
								</div>		

							
						</div>

					<?php endwhile; ?>

				</div>
											
				<!-- Begin wpcrown_pagination-->	
				<?php get_template_part('pagination'); ?>
				<!-- End wpcrown_pagination-->
				<div class="clearfix"></div>
				<?php wp_reset_query(); ?>
			</div>
			<!--End Popular Ads -->
			<!--Start Random ads -->
							<div class="pane random-ads-grid-holder">
				<div class="random-ads-grid">
					<?php
					global $paged, $wp_query, $wp;
					$args = wp_parse_args($wp->matched_query);
					if ( !empty ( $args['paged'] ) && 0 == $paged ) {
						$wp_query->set('paged', $args['paged']);
						$paged = $args['paged'];
					}
					$cat_id = get_cat_ID(single_cat_title('', false));
					$ads_counter = $redux_demo['home-ads-counter'];
					$temp = $wp_query;
					$wp_query= null;
					$wp_query = new WP_Query();
					$wp_query->query('orderby=title&post_type=post&posts_per_page='.$ads_counter.'&paged='.$paged.'&cat='.$cat_id);
					$current = -1;
					$current2 = 0;
					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>
						<div class="span8 ad-box-main">						
								<div class="ad-box span3">
									<?php
										if ( has_post_thumbnail()) {
										   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
										   echo '<a class="ad-image" href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" >';
										   echo get_the_post_thumbnail($post->ID, '291x250'); 
										   echo '</a>';
										 }
									?>
										<!--Ad Hover Content -->
									<div class="ad-hover-content">
										<div class="ad-category">												
											<?php
												$category = get_the_category();
												if ($category[0]->category_parent == 0) {
													$tag = $category[0]->cat_ID;
													$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
													if (isset($tag_extra_fields[$tag])) {
														$category_icon_code = $tag_extra_fields[$tag]['category_icon_code']; 
														$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
													}
												} else {
													$tag = $category[0]->category_parent;
													$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
													if (isset($tag_extra_fields[$tag])) {
														$category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
														$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
													}
												}
												if(!empty($category_icon_code)) {
											?>

											<div class="category-icon-box" style="background-color: <?php echo $category_icon_color; ?>;"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>
											<?php } 
											$category_icon_code = "";
											?>
										</div>										
										<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
										<div class="add-price"><span><?php echo $post_price; ?></span></div> 
									</div>
										<!--Ad Hover Content -->
								</div>
								<!--Post Title -->
								<div class="post-title-cat span3">
									<div class="post-title-list">
										<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,50).'...' : $theTitle; echo $theTitle; ?></a>
									</div>
								</div>
								<!--Post Title -->
								
								<?php if(function_exists('the_ratings')) { ?>

									<span class="ad-ratings"><?php the_ratings(); ?></span>

								<?php } ?>
								
								<div class="ad-description span5">
									<?php echo substr(get_the_content(), 0, 200); ?> 
								</div>
								
								<div class="ads-tags span3">
									<i class="fa fa-tags"></i><span class="tag-title"><a><?php _e('Tags', 'classify'); ?>:</a></span><span><?php the_tags('','',''); ?></span>
								</div>
								
								<div class="readbutton clearfix">
									<div class="readbutton-inner">
										<a href="<?php the_permalink(); ?> ">
											<span><?php echo $trns_read_btn; ?></span>
										</a>
									</div>				
								</div>	

							
						</div>

					<?php endwhile; ?>

				</div>
											
				<!-- Begin wpcrown_pagination-->	
				<?php get_template_part('pagination'); ?>
				<!-- End wpcrown_pagination-->	
				<div class="clearfix"></div>
				<?php wp_reset_query(); ?>

			</div>
			<!--End Random ads -->
        </div>

    </section>
	
	<div class="span4" >

		    <?php get_sidebar('listing'); ?>

	</div>
	<div class="clearfix"></div>
	</div>

    <script>
		// perform JavaScript after the document is scriptable.
		jQuery(function() {
			jQuery("ul.tabs").tabs("> .pane", {effect: 'fade', fadeIn: 200});
		});
	</script>
