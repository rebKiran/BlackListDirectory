<?php

/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php 
	if (isset($_SERVER['HTTP_USER_AGENT']) &&
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        header('X-UA-Compatible: IE=9');
	global $redux_demo; 
	$favicon = $redux_demo['favicon']['url'];
	?>

	<?php if (!empty($favicon)) : ?>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
	<?php endif; ?>

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<?php 

$layout = $redux_demo['layout-version'];
$pagename = get_query_var('pagename');  
?>

<body <?php if($layout == 2){ ?>id="boxed" <?php } ?> <?php body_class(); ?>>
    
    <?php 
        global $redux_demo;
        $trns_post_ad_title = $redux_demo['trns_post_ad_title'];
        $trns_account_title = $redux_demo['trns_account_title'];
        $trns_login_title = $redux_demo['trns_login_title'];
        $trns_logout_title = $redux_demo['trns_logout_title'];
        $trns_register_title = $redux_demo['trns_register_title'];
    ?>


<div class="left_div <?php if( 'design-your-ads' != $pagename && 'design' != $pagename ) { ?>design-ad-show<?php } else { ?> design-ad-hide <?php } ?>">
<div id="mCSB_1_container" class="mCSB_container">
<header class="header-bar">
 <div class="branding">
	<img class="preload-me mCS_img_loaded" src="http://blacklistdir.rebelute.in/wp-content/uploads/2017/01/support-black-list-1.png" width="215" height="58" sizes="215px" alt="THE BLACK LIST">		
 </div>
<div class="branding">
	<div id="site-title" class="assistive-text">THE BLACK LIST</div>
	<div id="site-description" class="assistive-text">Directory of black owned business</div>
</div>
<div class="spoouter">
	<a href="http://blacklistdir.rebelute.in/please-sponsor/" class="sponc">Please Sponsor</a>
</div>
<ul class="main-nav underline-decoration upwards-line gradient-hover outside-item-remove-margin" role="menu">
	<li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-5 current_page_item menu-item-136 act first">
		<a href="http://blacklistdir.rebelute.in/" data-level="1">
		<span class="menu-item-text"><span class="menu-text">Home</span></span>
		</a>
	</li> 
	<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137">
		<a href="http://blacklistdir.rebelute.in/about/" data-level="1">
		<span class="menu-item-text"><span class="menu-text">About Us</span></span>
		</a>
	</li> 
	<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-141">
		<a href="http://blacklistdir.rebelute.in/upcoming-events/" data-level="1">
		<span class="menu-item-text"><span class="menu-text">Upcoming Events</span></span>
		</a>
	</li> 
	<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-139">
		<a href="http://blacklistdir.rebelute.in/find-your-black-owned-business/" data-level="1">
		<span class="menu-item-text"><span class="menu-text">Find Your Black Owned Business</span></span>
		</a>
	</li> 
	<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-139">
		<a href="http://blacklistdir.rebelute.in/check-out-our-members/" data-level="1">
		<span class="menu-item-text"><span class="menu-text">Check Out Our Members</span></span>
		</a>
	</li> 

       <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-183">
		<a href="http://blacklistdir.rebelute.in/deals/" data-level="1">
		<span class="menu-item-text"><span class="menu-text">Deals</span></span>
		</a>
	</li> 
	<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-183">
		<a href="http://blacklistdir.rebelute.in/become-a-member/" data-level="1">
		<span class="menu-item-text"><span class="menu-text">Become a Member</span></span>
		</a>
	</li> 
       
	<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-138">
		<a href="http://blacklistdir.rebelute.in/contact/" data-level="1">
		<span class="menu-item-text"><span class="menu-text">Contact Us</span></span>
		</a>
	</li> 
</ul>
<div class="star12">
<button type="button" id="rate"  class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Rate and tell us what you
think about <br/> THE BLACK LIST
</button>

	<?php echo do_shortcode('[RICH_REVIEWS_SNIPPET stars_only="true"]'); ?> <span>Average Rating</span>
</div>



<div class="mini-widgets">
<div class="soc-ico show-on-desktop near-logo-first-switch hide-on-second-switch custom-bg hover-accent-bg first">
<a title="Facebook" href="javascript:;" class="facebook" style="visibility: visible;">
<i class="fa fa-facebook"></i>
</a>
<a title="Twitter" href="javascript:;" class="twitter" style="visibility: visible;">
<i class="fa fa-twitter"></i>
</a>
<a title="Pinterest" href="javascript:;" class="pinterest" style="visibility: visible;">
<i class="fa fa-pinterest"></i>
</a>
<a title="Instagram" href="javascript:;" class="instagram" style="visibility: visible;">
<i class="fa fa-instagram"></i>
</a>
</div>
<span class="mini-contacts email show-on-desktop near-logo-first-switch in-menu-second-switch">
<i class="fa fa-envelope" aria-hidden="true"></i> theblacklistllc@gmail.com</span>
<br>
<span class="mini-contacts phone show-on-desktop near-logo-first-switch in-menu-second-switch last">
<i class="fa fa-phone" aria-hidden="true"></i> (773) 322-7025</span>
</div>
</header>
</div>
</div>

<div class="right_div <?php if( 'design-your-ads' == $pagename || 'design' == $pagename ) { ?>design-right-adv-full<?php } else { ?>design-right-adv-half<?php } ?>" >	
<div id="top-menu-block">
		<div class="container">
			<div id="register-login-block-top">
				<ul class="ajax-register-links inline">
					<?php 
						if ( is_user_logged_in() ) {

						$profile = $redux_demo['profile'];						
						
						$new_post = $redux_demo['new_post'];

					?>
					
					
					<li class="first">
						<a href="<?php echo $profile; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Profile"><?php echo $trns_account_title; ?></a>
					</li>
					
					<li class="last">
						<a href="<?php echo wp_logout_url(get_option('siteurl')); ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Logout"><?php echo $trns_logout_title; ?></a>
					</li>
					<?php } else { 

						$login = $redux_demo['login'];
						$register = $redux_demo['register'];
					?>

                                     <li class="first">
                                     <a href="javascript:;"><i class="fa fa-phone" aria-hidden="true"></i> (773) 322-7025 </a>
                                      </li>

                                     <li class="first">
                                     <a href="javascript:;"><i class="fa fa-envelope" aria-hidden="true"></i> theblacklistllc@gmail.com</a>
                                      </li>

					<!--li class="first">
						<a href="<?php echo $register; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Register"><?php echo $trns_register_title; ?></a>
					</li>
					<li class="last login">
						<a href="<?php echo $login; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login"><?php echo $trns_login_title; ?></a>
					</li-->
				<?php } ?>
				</ul>  
			</div>
			<div class="top-social-icons">

				<?php 

					$facebook_link = $redux_demo['facebook-link'];
					$twitter_link = $redux_demo['twitter-link'];
					$dribbble_link = $redux_demo['dribbble-link'];
					$flickr_link = $redux_demo['flickr-link'];
					$github_link = $redux_demo['github-link'];
					$pinterest_link = $redux_demo['pinterest-link'];
					$youtube_link = $redux_demo['youtube-link'];
					$google_plus_link = $redux_demo['google-plus-link'];
					$linkedin_link = $redux_demo['linkedin-link'];
					$tumblr_link = $redux_demo['tumblr-link'];
					$vimeo_link = $redux_demo['vimeo-link'];
					$instagram_link = $redux_demo['instagram-link'];

				?>

				<?php if(!empty($facebook_link)) { ?>

					<a href="javascript:;"><i class="fa fa-facebook"></i></a>

				<?php } ?>

				<?php if(!empty($twitter_link)) { ?>

					<a href="javascript:;"><i class="fa fa-twitter"></i></a>

				<?php } ?>

				<?php if(!empty($dribbble_link)) { ?>

					<a href="javascript:;"><i class="fa fa-dribbble"></i></a>

				<?php } ?>

				<?php if(!empty($flickr_link)) { ?>

					<a href="<?php echo $flickr_link; ?>"><i class="fa fa-flickr"></i></a>

				<?php } ?>

				<?php if(!empty($github_link)) { ?>

					<a href="<?php echo $github_link; ?>"><i class="fa fa-github"></i></a>

				<?php } ?>

				<?php if(!empty($pinterest_link)) { ?>

					<a class="target-blank" href="<?php echo $pinterest_link; ?>"><i class="fa fa-pinterest"></i></a>

				<?php } ?>

				<?php if(!empty($youtube_link)) { ?>

					<a class="target-blank" href="<?php echo $youtube_link; ?>"><i class="fa fa-youtube"></i></a>

				<?php } ?>

				<?php if(!empty($google_plus_link)) { ?>

					<a href="javascript:;"><i class="fa fa-google-plus"></i></a>

				<?php } ?>

				<?php if(!empty($linkedin_link)) { ?>

					<a href="javascript:;"><i class="fa fa-linkedin"></i></a>

				<?php } ?>

				<?php if(!empty($tumblr_link)) { ?>

					<a class="target-blank" href="<?php echo $tumblr_link; ?>"><i class="fa fa-tumblr"></i></a>

				<?php } ?>

				<?php if(!empty($vimeo_link)) { ?>

					<a class="target-blank" href="<?php echo $vimeo_link; ?>"><i class="fa fa-vimeo-square"></i></a>

				<?php } ?>
				
				<?php if(!empty($instagram_link)) { ?>

					<a class="target-blank" href="<?php echo $instagram_link; ?>"><i class="fa fa-instagram"></i></a>

				<?php } ?>

			</div>

		</div>

	</div>
		
	<header id="navbar">

		<div class="container">
			<a class="logo pull-left" href="<?php echo home_url(); ?>" title="Home">
				<?php $logo = $redux_demo['logo']['url']; if (!empty($logo)) { ?>
					<img src="<?php echo $logo; ?>" alt="Logo" />
				<?php } else { ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
				<?php } ?>
			</a>

			<div id="version-two-menu" class="main_menu">
				<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => 'false')); ?>
			</div>
		</div>

	</header><!-- #masthead -->