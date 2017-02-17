<?php
/**
 * The template for displaying archives of any blog taxonomy
 *
 * @package WordPress
 * @subpackage classify-child
 * @since classify 1.0
 */

get_header();

 ?>

    <div class="container">
    	<div class="blog-post-title blog-archive-title">	
        	<h2><?php $tax_term = $wp_query->get_queried_object(); echo $tax_term->name; ?> Archive</h2> 	
        </div>
    </div>   

    <section class="ads-main-page blog-main-page">
    	<div class="container">
	    	<div class="span8 first">
            
            	<?php if ( have_posts() ): ?>
                
                	<?php while ( have_posts() ) : the_post(); ?>
            	
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
	    		
	    	</div>

	    	<div class="span4 blog-sidebar" >

		    	<?php get_sidebar('blog'); ?>

	    	</div>

	    </div>

    </section>

<?php get_footer(); ?>