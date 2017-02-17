<?php
/**
 * Template Name: Blog Main Page
 *
 * The template for displaying the Main Blog Page.
 *
 * it will loop and display the blog posts
 *
 * @package WordPress
 * @subpackage classify-child
 * @since classify 1.0
 */

get_header();

 ?>


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="blog-post-title ad-title">	
        	<h2><?php the_title(); ?></h2> 	
    </div>
    <div class="container">
        <div class="blog-caption">
			<?php the_content(); ?>
        </div>
    </div>
     

    <section class="ads-main-page blog-main-page">
    	<div class="container">
	    	<div class="span8 first">
            
            	<?php // query the blog posts and loop them 
				$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
				$blog_args = array (
					'post_type' => 'blog_posts',
					'paged' => $paged,
					);
				$blog_query = new WP_Query( $blog_args );	
				?>
            
            	<?php if ( $blog_query->have_posts() ): ?>
                
                	<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
            	
                		<?php get_template_part( 'content', 'blog-loop' ); ?>
                	
					<?php endwhile; ?>
                    
                    <div class="pagination-nav">
						<?php //pagination
                        $big = 999999999; // need an unlikely integer		
                        echo paginate_links( array(
                                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                'format' => '?paged=%#%',
                                'current' => max( 1, get_query_var('paged') ),
                                'total' => $blog_query->max_num_pages
                            ) );
                        ?>
                    </div>
               	
                <?php else :
					echo "Sorry, Nothing found";
				endif; ?>
                
                <?php // reset to main query
				wp_reset_postdata(); ?>
				
	    		
	    	</div>

	    	<div class="span4 blog-sidebar" >

		    	<?php get_sidebar('blog'); ?>

	    	</div>

	    </div>

    </section>
<?php endwhile; endif; ?>

<?php get_footer(); ?>