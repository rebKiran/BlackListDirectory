<?php
/**
 * Template name: Admin Transactions Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */

if ( !current_user_can( 'update_core' ) ) { 

	wp_redirect(home_url()); exit;

}

global $redux_demo; 

global $current_user, $user_id;
wp_get_current_user();
$user_id = $current_user->ID; // You can set $user_id to any users, but this gets the current users ID.

get_header(); 

$trns_account_overview = $redux_demo['trns_account_overview'];
$trns_account_sttings_title = $redux_demo['trns_account_sttings_title'];
$trns_ragular_ads = $redux_demo['trns_ragular_ads'];
$trns_featured_ads = $redux_demo['trns_featured_ads'];
$trns_featured_ads_left = $redux_demo['trns_featured_ads_left'];
$trns_contact_details = $redux_demo['trns_contact_details'];
$trns_description = $redux_demo['trns_description'];

?>



	<?php 

		global $redux_demo; 

		$featured_ads_option = $redux_demo['featured-options-on'];

	?>



    <section id="ads-profile">
	<div class="ad-title">
	
        		<h2><?php esc_html_e( 'All Transactions', 'classify' ); ?></h2> 	
	</div>
        
        <div class="container">
			<div class="span12 first">
	    	<?php 

				global $redux_demo; 

				$featured_ads_option = $redux_demo['featured-options-on'];

			?>

			<?php if($featured_ads_option == 1) { ?>

	    	
				<div class="full">


				<div class="full" style="margin-left: 0px;">

					<?php 

						global $current_user;
      					wp_get_current_user();

      					$userID = $current_user->ID;

						$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal ORDER BY main_id DESC" );

						if ( $result ) { ?>

						    <div class="full-boxed-pricing">

						        <div class="price-table-header">
									<div class="price-table-header-name" style="width: 4%;"><span><?php esc_html_e( 'ID', 'classify' ); ?></span></div>								
									<div class="price-table-header-name" style="width: 11%;"><span><?php esc_html_e( 'Username', 'classify' ); ?></span></div>
									<div class="price-table-header-name" style="width: 11%;"><span><?php esc_html_e( 'Plan Name', 'classify' ); ?></span></div>
									<div class="price-table-header-ads"><span><?php esc_html_e( 'Ads', 'classify' ); ?></span></div>
									<div class="price-table-header-used"><span><?php esc_html_e( 'Used', 'classify' ); ?></span></div>
									<div class="price-table-header-days"><span><?php esc_html_e( 'Active', 'classify' ); ?></span></div>
									<div class="price-table-header-price"><span><?php esc_html_e( 'Price', 'classify' ); ?></span></div>
									<div class="price-table-header-status"><span><?php esc_html_e( 'Status', 'classify' ); ?></span></div>
									<div class="price-table-header-date"><span><?php esc_html_e( 'Date', 'classify' ); ?></span></div>

								</div>

							<?php 

							    foreach ( $result as $info ) { 
							?>

								<div class="price-table-row" <?php if($info->status == "pending" || $info->status == "in progress") {  ?>style="background: #fce3e3;"<?php } ?>>
									<?php  $user_info = get_userdata($info->user_id); ?>
									<div class="price-table-header-name" style="width: 4%;"><span><?php echo  $user_info->ID; ?></span></div>								
									<div class="price-table-header-name" style="width: 11%;"><span><?php echo  $user_info->user_login; ?></span></div>
									<div class="price-table-row-name" style="width: 11%;"><span><?php echo $info->name; ?></span></div>
									<div class="price-table-row-ads"><span><?php if(empty($info->ads)) { ?> ∞ <?php } else { echo $info->ads; } ?></span></div>
									<div class="price-table-row-used"><span><?php echo $info->used; ?></span></div>
									<div class="price-table-row-days"><span><?php if(empty($info->days)) { ?>∞<?php } else { echo $info->days; } ?> <?php esc_html_e( 'Days', 'classify' ); ?></span></div>
									<div class="price-table-row-price"><span><?php echo $info->price; ?> <?php echo $info->currency; ?></span></div>
									<div class="price-table-row-status"><span <?php if($info->status == "success") {  ?>style="color: #40a000;"<?php } elseif($info->status == "pending") {  ?>style="color: #a02600;"<?php } ?>><?php echo $info->status; ?></span></div>
									<div class="price-table-row-date"><span><?php echo $info->date; ?></span></div>

								</div>

								<?php 
							} ?>

						</div>

					<?php } ?>        	

				</div>

			</div> 

			<?php } ?> 
			</div>
		</div>

    </section>


<?php get_footer(); ?>