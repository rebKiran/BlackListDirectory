<?php
/**
 * Template name: All Ads
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */


if ( !is_user_logged_in() ) { 

	global $redux_demo; 
	$login = $redux_demo['login'];
	wp_redirect( $login ); exit;

}

global $current_user, $user_id;
wp_get_current_user();
$user_id = $current_user->ID; // You can set $user_id to any users, but this gets the current users ID.



//..................................................................................im_start

global $redux_demo; 
$pagepermalink = get_permalink($post->ID);
if(isset($_GET['delete_id'])){
	$deleteUrl = $_GET['delete_id'];
	wp_delete_post($deleteUrl);
}
//.................................................................................im_end

get_header(); 

$trns_account_overview = $redux_demo['trns_account_overview'];
$trns_ragular_ads = $redux_demo['trns_ragular_ads'];
$trns_featured_ads = $redux_demo['trns_featured_ads'];
$trns_featured_ads_left = $redux_demo['trns_featured_ads_left'];
$trns_contact_details = $redux_demo['trns_contact_details'];
$trns_description = $redux_demo['trns_description'];

?>

<!--<p>template_all_ads</p>-->

	<?php 

		global $redux_demo; 

		$featured_ads_option = $redux_demo['featured-options-on'];

	?>



    <section id="ads-profile">
	<div class="ad-title">
	
        		<h2><?php echo $user_identity; ?> </h2> 	
	</div>
        
        <div class="container">
			<div class="span8 first">
                            
                            <!--..............................................im_start.....................................................................-->
				<div class="full">

                                    <h3><?php _e(strtoupper($user_identity).'\'s  ADS', 'classify' ); ?></h3>
				<div class="h3-seprator"></div>
			<div class="full" style="margin-left: 0px; padding-top: 20px;">
				<?php
				global $wp_query, $wp;

				$wp_query = new WP_Query();
						$kulPost = array(
								'post_type'  => 'post',
								'author' => $user_id,
								'posts_per_page' => 100,	
								);

						
						$wp_query = new WP_Query($kulPost);

						$current = -1;
						$current2 = 0;
						

						?>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>

						<div class="my-ad-box clearfix">
							<div class="my-ad-image">
							<?php
									if ( has_post_thumbnail()) {
									   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
									   echo '<a target="_blank" class="ad-image" href="' .get_permalink($post->ID). '" title="' . the_title_attribute('echo=0') . '" >';
									   echo get_the_post_thumbnail($post->ID, 'thumbnail'); 
									   echo '</a>';
									 }
								?>
							</div>
							<div class="my-ad-details">
								<a target="_blank" class="my-ad-title" href="<?php the_permalink(); ?>"><?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 22) ? substr($theTitle,0,22).'...' : $theTitle; echo $theTitle; ?></a>
								<span><i class="fa fa-check-square-o"></i> <?php echo get_post_status( $post->ID ); ?></span>
								<span><i class="fa fa-eye "></i> <?php echo wpb_get_post_views($post->ID); ?></span>
								<?php 
									global $redux_demo; 
									$edit_post_page_id = $redux_demo['edit_post'];
									$postID = $post->ID;

									global $wp_rewrite;
									if ($wp_rewrite->permalink_structure == ''){
									//we are using ?page_id
										$edit_post = $edit_post_page_id."&post=".$post->ID;
										$del_post = $pagepermalink."&delete_id=".$post->ID;
									}else{
									//we are using permalinks
										$edit_post = $edit_post_page_id."?post=".$post->ID;
										$del_post = $pagepermalink."?delete_id=".$post->ID;
										}
								?>
								
								<div class="delete-popup" id="examplePopup<?php echo $post->ID; ?>" style="display:none">
									<h4><?php esc_html_e( 'Are you free you want to delete this?', 'classify' ); ?></h4>
									<a class="button-ag large green" href="<?php echo $del_post; ?>">
									<span class="button-inner"><?php esc_html_e( 'Confirm', 'classify' ); ?></span>
									</a>
								</div>
								<?php
								if(get_post_status( $post->ID ) !== 'private'){ ?>
								<span class="my-ad-a"><i class="fa fa-pencil"></i> <a target="_blank" href="<?php echo $edit_post; ?>"><?php esc_html_e( 'Edit', 'classify' ); ?></a></span>
								<?php } ?>
								<span class="my-ad-a"><i class="fa fa-trash-o"></i> <a  class="thickbox" href="#TB_inline?height=150&amp;width=400&amp;inlineId=examplePopup<?php echo $post->ID; ?>"><?php esc_html_e( 'Delete', 'classify' ); ?></a></span>
								<span><i class="fa fa-clock-o"></i> <?php echo the_date('d M Y'); ?></span>
							</div>
						</div>
						
								
				

						<?php endwhile;	?>
				
																	
				<?php wp_reset_query(); ?>
					
				</div>

			</div> 
                            
                            <!--..............................................im_end.....................................................................-->



			</div>
       
			<div class="span4">

				
				<?php get_sidebar('pages'); ?>
			</div>
			
			
		</div>

    </section>


<?php get_footer(); ?>

