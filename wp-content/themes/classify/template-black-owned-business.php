<?php
/**
 * Template name: Blacked Owned Business
 * 
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */

get_header(); ?>

<?php 
	$trns_location = $redux_demo['trns_location'];
	$trns_category = $redux_demo['trns_category'];
	$trns_premium_ads = $redux_demo['trns_premium_ads'];
	$trns_categories = $redux_demo['trns_categories'];
	$trns_others = $redux_demo['trns_others'];
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



<?php
	
		global $redux_demo; 

		$category_block = $redux_demo['category-block'];

	?>

    <section id="ads-homepage">
	
		  <h2 class="main-title"><?php echo $trns_classify_ads; ?></h2>
        
        <div class="container">
   <?php echo do_shortcode( '[searchandfilter taxonomies="search,category"]' ); ?>
			

				<!-- Begin wpcrown_pagination-->	
				<?php get_template_part('pagination'); ?>
				<!-- End wpcrown_pagination-->	
																
				<?php wp_reset_query(); ?>

			</div>

        </div>

    </section>
	
   

<?php get_footer(); ?>