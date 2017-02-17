<?php
/**
 * The template for displaying the blog page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */

get_header();
$trns_skeywords = $redux_demo['trns_skeywords'];
 ?>


<!-- Search Box End -->
    <div class="ad-title">
	
        		<h2><?php the_title(); ?></h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="span8 first" style="padding: 30px 0;">

				<div class="ad-detail-content">

	    			<?php 
 
//Define your custom post type name in the arguments
 
$args = array('post_type' => 'blog_posts');
 
//Define the loop based on arguments
 
$loop = new WP_Query( $args );
 
//Display the contents
 
while ( $loop->have_posts() ) : $loop->the_post();
?>
<h1 class="entry-title"><?php the_title(); ?></h1>
<div class="entry-content">
<?php the_content(); ?>
</div>
<?php endwhile;?>

	    		</div>

	    		
	    	</div>

	    	<div class="span4" >

		    	<?php get_sidebar('pages'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>