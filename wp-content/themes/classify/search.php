<?php
/**
 * The template for displaying Category pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */

get_header(); ?>

<?php 

	// Retrieve the URL variables (using PHP).
	//$trns_premium_ads = $redux_demo['trns_premium_ads'];
	//$trns_classify_ads = $redux_demo['trns_classify_ads'];
	/*$keyword = $_GET['s'];
	$post_location_search = $_GET['post_location'];		
	if(isset( $_GET['category_name'])) {
		$category_name = $_GET['category_name'];		
	}		
	if(isset( $_GET['select-category'])) {	
		$category_name = $_GET['select-category'];		
	}	
	$geo_location = $_GET['geo-location'];
	$geo_radius_search = $_GET['geo-radius-search'];
	$geo_search_lat = $_GET['geo-search-lat'];
	$geo_search_lng = $_GET['geo-search-lng'];
	$trns_skeywords = $redux_demo['trns_skeywords'];*/
	
	$keyword = trim($_GET['s']);
	$category_name = trim( $_GET['category'] );
	
	if( !empty( $category_name )) {
		 $keyword = '';
	}
	
	if( empty($category_name)) {
		
		$category_name = trim( $_GET['select-category'] );
	}

	$catSearchID = '';

	if($category_name != "All") { 
		  /* $thisCat = get_cat_ID( $category_name );*/

		  $catSearchID = $category_name;

	} else {
		$catSearchID = '';
	}

	if($keyword == "all") {
		$keyword = '';
	} else {
		$keyword = $keyword;
	}
   		
	if( !empty( $category_name) && !empty( $catSearchID ) || !empty( $keyword )) { 	
		$args = array(
			'post_type' => 'post',
			'post_status'=> 'publish',
			's' => $keyword,
			'cat' => $catSearchID,
			'posts_per_page' => -1,
		);
		
		$wp_query = new WP_Query($args); 
		
		/*print_r($wp_query);
		die( 'Here' );  */
		$wp_query = null;
		
		global $paged, $wp_query, $wpdb;
		$search = '';
		$searchCond = '';
		
		if(!empty($catSearchID)) {
			$search = ' INNER JOIN wp_term_relationships ON ( wp_posts.ID = wp_term_relationships.object_id AND  wp_term_relationships.term_taxonomy_id IN ('. $catSearchID .')   )';
			//$searchCond = ' ';
		}
		
		
		$words = explode(' ',$keyword);
		$conds  = array();
		foreach ($words as $val) {

			$conds[] = "( wp_posts.post_title LIKE '%". $val ."%' )";
			$conds[] = "( wp_postmeta.meta_key LIKE 'state' AND wp_postmeta.meta_value LIKE '%". $val ."%' ) ";
			$conds[] = "( wp_postmeta.meta_key LIKE 'city' AND wp_postmeta.meta_value LIKE '%". $val ."%' ) ";
			
		}
		$query .= implode(' OR ', $conds);
		
		/* $sql = "SELECT wp_posts.* FROM wp_posts INNER JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id and $wpdb->posts.post_status = 'publish')". $search ." WHERE 1=1 AND  ". $searchCond ." (((wp_posts.post_title LIKE '%". $keyword ."%') OR (wp_posts.post_excerpt LIKE '%". $keyword ."%') OR (wp_posts.post_content LIKE '%". $keyword ."%') OR (wp_postmeta.meta_key LIKE 'state' AND wp_postmeta.meta_value LIKE '%". $keyword ."%' ) OR (wp_postmeta.meta_key LIKE 'city' AND wp_postmeta.meta_value LIKE '%". $keyword ."%' ) ) ) AND (wp_posts.post_password = '') AND wp_posts.post_type = 'post' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC";*/
		 
		 $querystr = "SELECT wp_posts.* FROM wp_posts INNER JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id and $wpdb->posts.post_status = 'publish')". $search ." WHERE 1=1  AND";
		 
		 $querystr .= $query ;
		
		 $querystr .= " AND wp_posts.post_type = 'post' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC";
		
		
		 $prop_selection = $wpdb->get_results($querystr, OBJECT);
		 
		 $current = -1;
		 $current2 = 0;

		 $emptyPost2 = 0;
	 
	 
		?>
	<div class="ad-title">
	<?php 
   $result = '';
   if( !empty( $keyword )) { 
		$result .=  $keyword;
		
   }
   if( !empty( $catSearchID )) { 
		$cat = get_cat_name( $catSearchID ); 
		$result .=  (empty($result)) ? $cat : ', ' . $cat;
   }
	$result = trim($result);
	?>
	<h2><?php printf( __( 'Search Results for: ', 'classify' )); ?> <?php echo stripslashes($result); ?></h2>

	</div>
	<section id="ads-homepage">
	<div class="container">
		<div class="pane latest-ads-holder">
		
			<div class="latest-ads-grid-holder">
			
		<?php 
			if (!empty($prop_selection)) {
				
				foreach ($prop_selection as $post) {
				setup_postdata($post);
				$current++; $current2++; $emptyPost2++;
		?>
				<div class="ad-box span3 latest-posts-grid <?php if($current%4 == 0) { echo 'first'; } ?>">

							<?php
								if ( has_post_thumbnail()) {
								   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail');
								 
								   echo '<a class="ad-image" href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" >'; 
								   //echo get_the_post_thumbnail($post->ID, '291x250'); 
								  if( strpos( $large_image_url[0], '/WPC/') !== false ) {  
										if (getimagesize( $large_image_url[0].'/part1/part1.png') !== false ) {
											
								   ?>
								   <img src="<?php echo $large_image_url[0] .'/part1/part1.png';?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image"  width="290" height="250"/>
										<?php } } else {  
										if (getimagesize($large_image_url[0]) !== false) {
										?>
								    <img src="<?php echo $large_image_url[0];?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image"  width="290" height="250"/>
										<?php }
										} ?>
								   <?php echo '</a>';
								 }
							?>

				    		<div class="ad-hover-content">

				    			<span class="ad-category">
				    					
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

				    			</span>

				    			
				    			<?php $post_price = get_post_meta($post->ID, 'post_price', true); ?>
								<div class="add-price"><span><?php echo $post_price; ?></span></div> 
								
				    		</div>
								<div class="post-title">
									<a href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 50) ? substr($theTitle,0,47).'...' : $theTitle; echo $theTitle; ?></a>
								</div>

						</div>
							
		  <?php 	}
				} ?>
				
			</div>
		
			<?php wp_reset_query(); ?>
			
			<?php if($emptyPost2 == 0) { ?>
				<div class="full view-empty">
					<p><h4 class="widgettitle"><?php _e( 'No results found', 'classify' ); ?></h4></p>
				</div>
			<?php } ?>

		</div>	
	</div>	
</section>
<?php } else { ?>
	<div class="ad-title">
	<?php 
   $result = '';
   if( !empty( $keyword )) { 
		$result .=  $keyword;
		
   }
   if( !empty( $catSearchID )) { 
		$cat = get_cat_name( $catSearchID ); 
		$result .=  (empty($result)) ? $cat : ', ' . $cat;
   }
	$result = trim($result);
	?>
	<h2><?php printf( __( 'Search Results for: ', 'classify' )); ?> <?php echo stripslashes($result); ?></h2>

	</div>
	<section id="ads-homepage">
	<div class="container">
		<div class="pane latest-ads-holder">
		
			<div class="full view-empty">
					<p><h4 class="widgettitle"><?php _e( 'No results found', 'classify' ); ?></h4></p>
				</div>
		</div>
    </div>	
    </section>	
<?php } ?>
<?php get_footer(); ?>