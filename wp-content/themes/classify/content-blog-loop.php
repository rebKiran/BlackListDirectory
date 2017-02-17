<?php 
/* 
 * used to display blog posts items in archives and listings 
 *
 * need to be called within the loop
 */
 ?>					
                    <div class="blog-archive-item">

						<?php //display featured image if any
                        if ( has_post_thumbnail ){
                            ?>
                            <div class="blog-featured-img">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php } ?>
                        
                        <div class="blog-post-title">	
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2> 	
                        </div>
        
                        <div class="ad-detail-content blog-post">
                        	
                            <div class="blog-post-content">
	                            <?php the_excerpt(); ?>
                            </div>
							<div class="readbutton clearfix blogMain">
							<?php 
							global $redux_demo;
							$trns_read_btn = $redux_demo['read_more_btn']; 
							?>
								<div class="readbutton-inner blogInner">
									<a href="<?php the_permalink(); ?> ">
										<span><?php echo $trns_read_btn; ?></span>
									</a>
								</div>				
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
                            <div class="clear"></div>

                    	</div>

					</div>