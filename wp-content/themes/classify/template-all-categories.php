<?php
/**
 * Template name: All Categories
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Classify
 * @since Classify 2.5.2
 */


get_header(); ?>

<?php 
	
	$trns_categories = $redux_demo['trns_categories'];
	$trns_premium_ads = $redux_demo['trns_premium_ads'];
	$trns_others = $redux_demo['trns_others'];
	
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
	<!-- Page Title -->
	<div class="ad-title">
	
        		<h2><?php the_title(); ?></h2> 	
	</div>
	<!-- End Page Title -->
	<!-- Featured Ads Slider -->
	<?php 		
		global $redux_demo; 

		$featured_ads_option = $redux_demo['featured-options-on'];
		$hidefslider = $redux_demo['hide-fslider'];

	?>
	<?php if($featured_ads_option == 1 && $hidefslider == 1) { ?>
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
							"use strict";
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

    <?php }elseif($hidefslider == 1) { ?>

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
	<!-- End Featured Ads Slider -->
	<!-- All Categories Section -->
    <section id="categories-homepage">
        
        <div class="container">

	        <?php $categories = get_categories('hide_empty=0');

		    	$currentCat = 0;
							      
				foreach ($categories as $category) { 

					if ($category->category_parent == 0) {

						$currentCat++;

					}

				}

			?>
            
            <h2 class="main-title"><?php echo $trns_categories; ?></h2>
			<div class="h2-seprator"></div>

            <div class="full">

            	<?php
					$argsmain = array(
									'hide_empty' => 0,									
									 );
									
				$categories = get_categories($argsmain);

		    		$current = -1;
							      
					foreach ($categories as $category) { 

						if ($category->category_parent == 0) {

							$tag = $category->cat_ID;

							$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
							if (isset($tag_extra_fields[$tag])) {
								$category_icon_code = $tag_extra_fields[$tag]['category_icon_code']; 
								$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
							}

							$cat = $category->count;
							$catName = $category->cat_ID;

							$current++;
							$allPosts = 0;

							$categories = get_categories('child_of='.$catName); 
							foreach ($categories as $category) {
								$allPosts += $category->category_count;
							}

				 ?>

            	<div class="category-box span3 <?php if($current%3 == 0) { echo 'first'; } ?>">

            		<div class="category-header">

            			<div class="category-icon">
		    				<?php if(!empty($category_icon_code)) { ?>

						        <div class="category-icon-box"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>

						    <?php } ?>
		    			</div>

		    			<div class="cat-title"><a href="<?php echo get_category_link( $catName ) ?>"><h4><?php echo get_cat_name( $catName ); ?></h4></a></div>


            		</div>

            		<div class="category-content">

            			<ul>   

		    				<?php

		    					$currentCat = 0;

		    					$args2 = array(
									'type' => 'post',
									'child_of' => $catName,
									'parent' => get_query_var(''),
									'orderby' => 'name',
									'order' => 'ASC',
									'hide_empty' => 0,
									'hierarchical' => 1,
									'exclude' => '',
									'include' => '',
									'number' => '',
									'taxonomy' => 'category',
									'pad_counts' => true );

								$categories2 = get_categories($args2);

								foreach($categories2 as $category2) { 
									$currentCat++;
								}

								$args = array(
									'type' => 'post',
									'child_of' => $catName,
									'parent' => get_query_var(''),
									'orderby' => 'name',
									'order' => 'ASC',
									'hide_empty' => 0,
									'hierarchical' => 1,
									'exclude' => '',
									'include' => '',
									'number' => '5',
									'taxonomy' => 'category',
									'pad_counts' => true );

								$categories = get_categories($args);
								foreach($categories as $category) {
							?>

								<li>
								  	<a href="<?php echo get_category_link( $category->term_id )?>" title="View posts in <?php echo $category->name?>" class="cat-content-color">
										<?php $categoryTitle = $category->name; $categoryTitle = (strlen($categoryTitle) > 30) ? substr($categoryTitle,0,27).'...' : $categoryTitle; echo $categoryTitle; ?>
									</a>
								  	<span class="category-counter cat-content-color">( <?php echo $category->count ?> )</span>
								</li>

							<?php } ?> 

							<?php if($currentCat > 5) { ?>

		    					<li>
		    						<a href="<?php echo get_category_link( $catName ) ?>" class="cat-content-color"><?php echo $trns_others; ?> (<?php echo $allPosts; ?>)</a>
		    					</li>

		    				<?php } ?>

		    			</ul>

            		</div>

            	</div>

            	<?php } } ?>

            </div>
	

        </div>

    </section>    
    <script>
		// perform JavaScript after the document is scriptable.
		jQuery(function() {
			jQuery("ul.tabs").tabs("> .pane", {effect: 'fade', fadeIn: 200});
		});
	</script>
<!-- End Categories Section -->
<?php get_footer(); ?>