<?php

	function post_type_portfolios() {
		$labels = array(
	    	'name' => _x('Pricing Plans', 'post type general name', 'classify'),
	    	'singular_name' => _x('Price Plans', 'post type singular name', 'classify'),
	    	'add_new' => _x('Add New Price Plan', 'book', 'classify'),
	    	'add_new_item' => __('Add New Price Plan', 'classify'),
	    	'edit_item' => __('Edit Price Plan', 'classify'),
	    	'new_item' => __('New Price Plan', 'classify'),
	    	'view_item' => __('View Price Plan', 'classify'),
	    	'search_items' => __('Search Price Plans', 'classify'),
	    	'not_found' =>  __('No Price Plan found', 'classify'),
	    	'not_found_in_trash' => __('No Price Plans found in Trash', 'classify'), 
	    	'parent_item_colon' => ''
		);		
		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
	    	'publicly_queryable' => true,
	    	'show_ui' => true, 
	    	'query_var' => true,
	    	'rewrite' => true,
	    	'capability_type' => 'post',
	    	'hierarchical' => false,
	    	'menu_position' => null,
	    	'supports' => array('title','editor', 'thumbnail'),
	    	'menu_icon' => get_template_directory_uri().'/images/plans.png'
		); 		

		register_post_type( 'price_plan', $args ); 				  
	} 
									  
	add_action('init', 'post_type_portfolios');


	add_action( 'add_meta_boxes', 'plan_ads_box' );
	function plan_ads_box() {
	    add_meta_box( 
	        'plan_ads_box',
	        __( 'Featured Ads', 'classify' ),
	        'plan_ads_content',
	        'price_plan',
	        'side',
	        'high'
	    );
	}

	function plan_ads_content( $post ) {

		$featured_ads = get_post_meta( $post->ID, 'featured_ads', true );

		echo '<label for="featured_ads"></label>';
		echo '<input type="text" id="featured_ads" name="featured_ads" placeholder="Leave empty for unlimited" value="';
		echo $featured_ads; 
		echo '">';
		
	}

	add_action( 'save_post', 'project_link_box_save' );
	function project_link_box_save( $post_id ) {		

		global $featured_ads;

		if(isset($_POST["featured_ads"]))
		$featured_ads = $_POST['featured_ads'];
		update_post_meta( $post_id, 'featured_ads', $featured_ads );

	}


	add_action( 'add_meta_boxes', 'plan_price_box' );
	function plan_price_box() {
	    add_meta_box( 
	        'plan_price_box',
	        __( 'Price', 'classify' ),
	        'plan_price_content',
	        'price_plan',
	        'side',
	        'high'
	    );
	}

	function plan_price_content( $post ) {

		$plan_price = get_post_meta( $post->ID, 'plan_price', true );

		echo '<label for="plan_price"></label>';
		echo '<input type="text" id="plan_price" name="plan_price" placeholder="" value="';
		echo $plan_price; 
		echo '">';
		
	}


	add_action( 'save_post', 'plan_price_save' );
	function plan_price_save( $post_id ) {		

		global $plan_price;

		if(isset($_POST["plan_price"]))
		$plan_price = $_POST['plan_price'];
		update_post_meta( $post_id, 'plan_price', $plan_price );

	}



	add_action( 'add_meta_boxes', 'plan_time_box' );
	function plan_time_box() {
	    add_meta_box( 
	        'plan_time_box',
	        __( 'Days', 'classify' ),
	        'plan_time_content',
	        'price_plan',
	        'side',
	        'high'
	    );
	}

	function plan_time_content( $post ) {

		$plan_time = get_post_meta( $post->ID, 'plan_time', true );

		echo '<label for="plan_time"></label>';
		echo '<input type="text" id="plan_time" name="plan_time" placeholder="Leave empty for unlimited" value="';
		echo $plan_time; 
		echo '">';
		
	}


	add_action( 'save_post', 'plan_time_save' );
	function plan_time_save( $post_id ) {		

		global $plan_time;

		if(isset($_POST["plan_time"]))
		$plan_time = $_POST['plan_time'];
		update_post_meta( $post_id, 'plan_time', $plan_time );

	}
	
	include("all_transactions.php");
	
/* Register Blog Post Type*/
function blog_categories_fc() {
	register_taxonomy(
		'blog_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'blog_posts',   		 //post type name
		array(
			'hierarchical' 		=> true,
			'label' 			=> 'Categories',  //Display name
			'query_var' 		=> true,
			'rewrite'			=> array(
					'slug' 			=> 'blog', // This controls the base slug that will display before each term
					'with_front' 	=> false // Don't display the category base before
					)
			)
		);
}
add_action( 'init', 'blog_categories_fc');

function filter_post_type_link( $link, $post) {
    if ( $post->post_type != 'blog_posts' )
        return $link;

    if ( $cats = get_the_terms( $post->ID, 'blog_categories' ) )
        $link = str_replace( '%blog_categories%', array_pop($cats)->slug, $link );
    return $link;
}
add_filter('post_type_link', 'filter_post_type_link', 10, 2);	

	function blog_post_type() {
		$labels = array(
	    	'name' => _x('Blog Posts', 'post type general name', 'heman'),
	    	'singular_name' => _x('Blog Posts', 'post type singular name', 'heman'),
	    	'add_new' => _x('Add New Blog Post', 'book', 'heman'),
	    	'add_new_item' => __('Add New Blog Post', 'heman'),
	    	'edit_item' => __('Edit Blog Post', 'heman'),
	    	'new_item' => __('New Blog Post', 'heman'),
	    	'view_item' => __('View Blog Post', 'heman'),
	    	'search_items' => __('Search Blog Posts', 'heman'),
	    	'not_found' =>  __('No Blog Post found', 'heman'),
	    	'not_found_in_trash' => __('No Blog Post found in Trash', 'heman'), 
	    	'parent_item_colon' => ''
		);		
		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
	    	'publicly_queryable' => true,
	    	'show_ui' => true, 
	    	'query_var' => true,	    	
	    	'capability_type' => 'post',
			'has_archive' => 'Blog',
	    	'hierarchical' => false,
	    	'menu_position' => null,
	    	'supports' => array('title','editor', 'thumbnail'),
			'taxonomies' => array('post_tag', 'blog_categories'),
			'rewrite' => true,			
	    	'menu_icon' => 'dashicons-admin-post'
		); 		

		register_post_type( 'blog_posts', $args );
		flush_rewrite_rules(true);
	} 

	add_action('init', 'blog_post_type');

?>