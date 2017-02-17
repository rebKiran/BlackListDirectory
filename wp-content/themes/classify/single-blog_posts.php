<?php
/**
 * The template for displaying the single blog posts.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage classify-child
 * @since classify 1.0
 */

get_header();

 ?>


<!-- Search Box End -->
    	<div class="blog-post-title ad-title">	
        	<h2><?php the_title(); ?></h2> 	
        </div>
	<div class="container">
    </div>

    <section class="ads-main-page blog-main-page">
    	<div class="container">
	    	<div class="span8 first">
            	
                <?php //display featured image if any
                if ( has_post_thumbnail ){
                    ?>
                    <div class="blog-featured-img">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php } ?>

				<div class="ad-detail-content blog-post">

	    			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
                        <div class="blog-post-content">
                        
							<?php the_content(); ?>
                            
                        </div>
                        
                        <div class="blog-post-meta">
							<span class="post-author"><?php esc_html_e( 'Posted By', 'classify' ); ?> <?php the_author(); ?> </span>
                            <span class="post-date"> <?php esc_html_e( 'On', 'classify' ); ?> <?php the_time( 'jS F, Y'); ?></span>
							<div class="post-categories">                            
								<?php echo get_the_term_list( $post->ID, 'blog_categories', '<strong>Categories: </strong>', ', ' ) ?>
                            </div>    
                            <div class="post-tags">
                                <?php echo get_the_term_list( $post->ID, 'post_tag', '<strong>Tags: </strong>', ' / ' ) ?>                    
                            </div>
                        
                        </div>
															
					<?php endwhile; endif; ?>

	    		</div>

				
	    		
	    	</div>

	    	<div class="span4 blog-sidebar" >

		    	<?php get_sidebar('blog'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>