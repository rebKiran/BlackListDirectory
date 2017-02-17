<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */
get_header(); 
?>

<?php 
	$trns_location = $redux_demo['trns_location'];
	$trns_category = $redux_demo['trns_category'];
	$trns_premium_ads = $redux_demo['trns_premium_ads'];
	$trns_classify_ads = $redux_demo['trns_classify_ads'];
	$trns_latest_ads = $redux_demo['trns_latest_ads'];
	$trns_popular_ads = $redux_demo['trns_popular_ads'];
	$trns_random_ads = $redux_demo['trns_random_ads'];
	$trns_skeywords = $redux_demo['trns_skeywords'];
	
	$page = get_page($post->ID);
	$current_page_id = $page->ID;

	$page_slider = get_post_meta($current_page_id, 'page_slider', true); 

	global $redux_demo, $maximRange; 
	$max_range = $redux_demo['max_range'];
	if(!empty($max_range)) {
		$maximRange = $max_range;
	} else {
		$maximRange = 1000;
	}

?>

		<div id="advanced-search-widget-version2" class="home-search">

			<div id="Search_Page" class="container widgets_on_page">

				<div class="advanced-search-widget-content">

					<form action="<?php echo home_url(); ?>" method="get" id="views-exposed-form-search-view-other-ads-page" accept-charset="UTF-8">

						<div id="edit-search-api-views-fulltext-wrapper" class="views-exposed-widget views-widget-filter-search_api_views_fulltext">
					        <div class="views-widget">
					          	<div class="control-group form-type-textfield form-item-search-api-views-fulltext form-item">
									<div class="controls"> 
										<input placeholder="<?php echo $trns_skeywords; ?>" type="text" id="edit-search-api-views-fulltext" name="s" value="" size="30" maxlength="128" class="form-text">
										<input type="hidden" id="hidden-keyword" name="s" value="all" size="30" maxlength="128" class="form-text">
									</div>
								</div>
						    </div>
						</div>
						          						
						<div id="edit-ad-location-wrapper" class="views-exposed-widget views-widget-filter-field_ad_location">
						   	<div class="views-widget">
						        <div class="control-group form-type-select form-item-ad-location form-item">
									<div class="controls"> 
										<select id="edit-ad-location" name="post_location" class="form-select fa fa-caret-square-o-down fa-lg" style="display: none;">
											<option value="All" selected="selected"><?php echo $trns_location; ?>...</option>

											<?php

												$args_location = array( 'posts_per_page' => -1 );
												$lastposts = get_posts( $args_location );

												$all_post_location = array();
												foreach( $lastposts as $post ) {
													$all_post_location[] = get_post_meta( $post->ID, 'post_location', true );
												}

												$directors = array_unique($all_post_location);
												foreach ($directors as $director) { ?>
													<option value="<?php echo $director; ?>"><?php echo $director; ?></option>
												<?php }

											?>

											<?php wp_reset_query(); ?>

										</select>
									</div>
								</div>
						    </div>
						</div>

						<div id="edit-field-category-wrapper" class="views-exposed-widget views-widget-filter-field_category">
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
												foreach ($categories as $cat) {
													if ($cat->category_parent == 0) { 
														$catID = $cat->cat_ID;
													?>
														<option value="<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?></option>
																			
												<?php 
													$args2 = array(
														'hide_empty' => '0',
														'parent' => $catID
													);
													$categories = get_categories($args2);
													foreach ($categories as $cat) { ?>
														<option value="<?php echo $cat->slug; ?>">- <?php echo $cat->cat_name; ?></option>
												<?php } ?>

												<?php } else { ?>
												<?php }
											} ?>

										</select>
									</div>
								</div>
						    </div>
						</div>

						<div class="advanced-search-slider">

							<div class="geo-location-button">

								<div class="geo-location-switch off"><i class="fa fa-location-arrow"></i></div>

							</div>

							<div id="advance-search-slider" class="value-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
								<a class="ui-slider-handle ui-state-default ui-corner-all" href="#">
									
								</a>
								<div class="range-pin">
										<input type="text" name="geo-radius" id="geo-radius" value="100" data-default-value="100" />
								</div>
							</div>

						</div>


						<input type="text" name="geo-location" id="geo-location" value="off" data-default-value="off">

						<input type="text" name="geo-radius-search" id="geo-radius-search" value="500" data-default-value="500">

						<input type="text" name="geo-search-lat" id="geo-search-lat" value="0" data-default-value="0">

						<input type="text" name="geo-search-lng" id="geo-search-lng" value="0" data-default-value="0">


						<div class="views-exposed-widget views-submit-button">
						    <button class="btn btn-primary form-submit" id="edit-submit-search-view" name="" value="Search" type="submit"><?php printf( __( 'Search', 'classify' )); ?></button>
						</div>

					</form>

				</div>

			</div>

		</div>

    <?php 

		global $redux_demo; 

		$featured_ads_option = $redux_demo['featured-options-on'];

	?>

	<?php if($featured_ads_option == 1) { ?>

    <section id="featured-abs">
        
        <div class="container">
            
            <h2 class="main-title"><?php echo $trns_premium_ads; ?></h2>
            
            <div id="tabs" class="full">
			    	
                <?php $cat_id = get_cat_ID(single_cat_title('', false)); ?>
			    	
                <ul class="tabs quicktabs-tabs quicktabs-style-nostyle"> 
			    	<li class="grid-feat-ad-style"><a class="" href="#"><?php esc_html_e( 'Grid View', 'classify' ); ?></a></li>
			    	<li class="list-feat-ad-style"><a class="" href="#"><?php esc_html_e( 'List View', 'classify' ); ?></a></li>
                </ul>

                <div class="pane">
                 
                    <div id="carousel-buttons">
			    	    <a href="#" id="carousel-prev"><i class="fa fa-angle-left"></i></a>
			    	    <a href="#" id="carousel-next"><i class="fa fa-angle-right"></i></a>
			        </div>

					<div id="projects-carousel">

			    		<?php

							global $paged, $wp_query, $wp;							
							

							$args = wp_parse_args($wp->matched_query);

							$temp = $wp_query;

							$wp_query= null;

							$wp_query = new WP_Query();

							$wp_query->query('post_type=post&posts_per_page=-1');

							$current = -1;

						?>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post();

							$featured_post = "0";

							$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
							$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
							$post_price_plan_expiration_date_noarmal = get_post_meta($post->ID, 'post_price_plan_expiration_date_normal', true);
							$todayDate = strtotime(date('m/d/Y h:i:s'));
							$expireDate = $post_price_plan_expiration_date;

							if(!empty($post_price_plan_activation_date)) {

								if(($todayDate < $expireDate) or $post_price_plan_expiration_date == 0) {
									$featured_post = "1";
								}

						} ?>

						<?php if($featured_post == "1") { 

							$current++;

						?>

						<div class="ad-box span3">
							<?php
								if ( has_post_thumbnail()) {
								   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
								   echo '<a class="ad-image" href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" >';
								   echo get_the_post_thumbnail($post->ID, 'single-post-image'); 
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
							
								<div class="post-title">
									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 40) ? substr($theTitle,0,37).'...' : $theTitle; echo $theTitle; ?></a>
								</div>
			    				

			    			

						</div>

			    		<?php } ?>

			    		<?php endwhile; ?>	
												
						<?php wp_reset_query(); ?>

			    	</div>

			    	<?php wp_enqueue_script( 'jquery-carousel', get_template_directory_uri().'/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'),'',true); ?>
										
					<script>

						jQuery(document).ready(function () {
							jQuery('#projects-carousel').carouFredSel({
								auto: false,
								prev: '#carousel-prev',
								next: '#carousel-next',
								pagination: "#carousel-pagination",
								mousewheel: true,
								swipe: {
									onMouse: true,
									onTouch: true
								} 
							});

						});
											
					</script>
					<!-- end scripts -->

			    </div>


			</div>
        
        </div>

    </section>

    <?php }else{ ?>

    <section id="featured-abs">
        
        <div class="container">
            
            <h2 class="main-title"><?php echo $trns_premium_ads; ?></h2>
            
            <div id="tabs" class="full">
			    	
                <?php $cat_id = get_cat_ID(single_cat_title('', false)); ?>
			    	
                <ul class="tabs quicktabs-tabs quicktabs-style-nostyle"> 
			    	<li class="grid-feat-ad-style"><a class="" href="#"><?php esc_html_e( 'Grid View', 'classify' ); ?></a></li>
			    	<li class="list-feat-ad-style"><a class="" href="#"><?php esc_html_e( 'List View', 'classify' ); ?></a></li>
                </ul>

                <div class="pane">
                 
                    <div id="carousel-buttons">
			    	    <a href="#" id="carousel-prev"><i class="fa fa-angle-left"></i></a>
			    	    <a href="#" id="carousel-next"><i class="fa fa-angle-right"></i></a>
			        </div>

					<div id="projects-carousel">

			    		<?php

							global $paged, $wp_query, $wp;							
							
							$featured_cat = $redux_demo['featured_cat'];
							
							$args = wp_parse_args($wp->matched_query);

							$temp = $wp_query;

							$wp_query= null;

							$wp_query = new WP_Query();

							$wp_query->query('post_type=post&posts_per_page=-1&category_name='.$featured_cat.'');

							$current = -1;

						?>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post();

							$featured_post = "1";

							$post_price_plan_activation_date = get_post_meta($post->ID, 'post_price_plan_activation_date', true);
							$post_price_plan_expiration_date = get_post_meta($post->ID, 'post_price_plan_expiration_date', true);
							$post_price_plan_expiration_date_noarmal = get_post_meta($post->ID, 'post_price_plan_expiration_date_normal', true);
							?>

						<?php if($featured_post == "1") { 

							$current++;

						?>

						<div class="ad-box span3">
							<?php
								if ( has_post_thumbnail()) {
								   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
								   echo '<a class="ad-image" href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" >';
								   echo get_the_post_thumbnail($post->ID, 'single-post-image'); 
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
							
								<div class="post-title">
									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 40) ? substr($theTitle,0,37).'...' : $theTitle; echo $theTitle; ?></a>
								</div>
			    				

			    			

						</div>

			    		<?php } ?>

			    		<?php endwhile; ?>	
												
						<?php wp_reset_query(); ?>

			    	</div>

			    	<?php wp_enqueue_script( 'jquery-carousel', get_template_directory_uri().'/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'),'',true); ?>
										
					<script>

						jQuery(document).ready(function () {
							jQuery('#projects-carousel').carouFredSel({
								auto: false,
								prev: '#carousel-prev',
								next: '#carousel-next',
								pagination: "#carousel-pagination",
								mousewheel: true,
								swipe: {
									onMouse: true,
									onTouch: true
								} 
							});

						});
											
					</script>
					<!-- end scripts -->

			    </div>


			</div>
        
        </div>

    </section>

    <?php } ?>

    <section id="ads-homepage">
	
		  <h2 class="main-title"><?php echo $trns_classify_ads; ?></h2>
        
        <div class="container">

        	<ul class="tabs quicktabs-tabs quicktabs-style-nostyle">
			    <li >
			    	<a style="font-size: 14px !important; " class="current" href="#"><?php echo $trns_latest_ads; ?></a>
			    </li>
			    <li>
			    	<a style="font-size: 14px !important;" class="" href="#"><?php echo $trns_popular_ads; ?></a>
			    </li>
			    <li>
			    	<a style="font-size: 14px !important;" class="" href="#"><?php echo $trns_random_ads; ?></a>
			    </li>
			</ul>

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

					$wp_query = new WP_Query();

					$wp_query->query('post_type=post&posts_per_page=12&paged='.$paged.'&cat='.$cat_id);

					$current = -1;
					$current2 = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>

						<div class="ad-box span3 latest-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

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
								<div class="post-title">
									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>
								</div>

						</div>

					<?php endwhile; ?>

				</div>
											
			<!-- Begin wpcrown_pagination-->	
			<?php get_template_part('pagination'); ?>
			<!-- End wpcrown_pagination-->	
																
			<?php wp_reset_query(); ?>

			</div>

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


						$current = -1;
						$current2 = 0;


						$popularpost = new WP_Query( array( 'posts_per_page' => '12', 'cat' => $cat_id, 'posts_type' => 'post', 'paged' => $paged, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );										

						while ( $popularpost->have_posts() ) : $popularpost->the_post(); $current++; $current2++;

						?>

						<div class="ad-box span3 popular-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

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
							<div class="post-title">
									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>

							</div>

						</div>

					<?php endwhile; ?>

				</div>
											
				<!-- Begin wpcrown_pagination-->	
				<?php get_template_part('pagination'); ?>
				<!-- End wpcrown_pagination-->	
																
				<?php wp_reset_query(); ?>

			</div>

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

					$temp = $wp_query;

					$wp_query= null;

					$wp_query = new WP_Query();

					$wp_query->query('orderby=title&post_type=post&posts_per_page=12&paged='.$paged.'&cat='.$cat_id);

					$current = -1;
					$current2 = 0;

					?>

					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>

						<div class="ad-box span3 random-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

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
							<div class="post-title">
								
				    			<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>

							</div>
						</div>

					<?php endwhile; ?>

				</div>
											
				<!-- Begin wpcrown_pagination-->	
				<?php get_template_part('pagination'); ?>
				<!-- End wpcrown_pagination-->	
																
				<?php wp_reset_query(); ?>

			</div>

        </div>

    </section>

    <script>
		// perform JavaScript after the document is scriptable.
		jQuery(function() {
			jQuery("ul.tabs").tabs("> .pane", {effect: 'fade', fadeIn: 200});
		});
	</script>

<?php get_footer(); ?>