<?php
/**
 * Template name: Login Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */

if ( is_user_logged_in() ) { 

	global $redux_demo; 
	$profile = $redux_demo['profile'];
	wp_redirect( $profile ); exit;

}

global $user_ID, $username, $password, $remember;

//We shall SQL escape all inputs
$username = esc_sql(isset($_REQUEST['username']) ? $_REQUEST['username'] : '');
$password = esc_sql(isset($_REQUEST['password']) ? $_REQUEST['password'] : '');
$remember = esc_sql(isset($_REQUEST['rememberme']) ? $_REQUEST['rememberme'] : '');
	
if($remember) $remember = "true";
else $remember = "false";
$login_data = array();
$login_data['user_login'] = $username;
$login_data['user_password'] = $password;
$login_data['remember'] = $remember;
$user_verify = wp_signon( $login_data, false ); 
//wp_signon is a wordpress function which authenticates a user. It accepts user info parameters as an array.
if($_POST){
	if ( is_wp_error($user_verify) ) {
		$UserError =  esc_html__( 'Invalid username or password. Please try again!', 'classify' );
	} else {

		global $redux_demo; 
		$profile = $redux_demo['profile'];
		wp_redirect( $profile ); exit;

	}
}

get_header(); ?>


	<div class="ad-title">
	
        		<h2><?php the_title(); ?> </h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="first clearfix log-in">

				<div id="edit-profile">

					
							<h2 class="login-title"><?php esc_html_e( 'LOGIN', 'classify' ); ?></h2> 

						<form class="form-item login-form" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

							<?php global $user_ID, $user_identity; wp_get_current_user(); ?>

							<?php if(!empty($UserError)) { ?>
								<span class='error' style='color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;'><?php echo $UserError; ?></span><div class='clearfix'></div>
							<?php } ?>

									<input type="text" id="contactName" name="username" placeholder="<?php esc_html_e( 'Username', 'classify' ); ?>" class="text input-textarea half" value="" />

							
									<input type="password" id="password" name="password" placeholder="<?php esc_html_e( 'Password', 'classify' ); ?>" class="text input-textarea half" value="" />



								<fieldset class="input-title">

									<label for="edit-title" class="remember-me">
										<input name="rememberme" type="checkbox" value="forever" style="float: left;"/><span style="margin-left: 10px; float: left;"><?php esc_html_e( 'Remember Me', 'classify' ); ?></span>

										<?php 

								    		global $redux_demo; 
											$reset = $redux_demo['reset'];

										?>

									</label>

								</fieldset>


								<div class="publish-ad-button">
									<input type="hidden" id="submitbtn" name="submit" value="Login" />
									<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Login', 'classify') ?></button>
								</div>
								<a style="float: left;" class="" href="<?php echo $reset; ?>"><?php printf( __( 'Forgot Password?', 'classify' )); ?></a>
							

						</form>
						<div class="clearfix"></div>
						<div class="register-page-title">

							<h1><?php _e( 'LOGIN VIA SOCIAL ACCOUNT', 'classify' ); ?></h1>

						</div>
						<div class="social-btn clearfix">
							<?php
							/**
							 * Detect plugin. For use on Front End only.
							 */
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

							// check for plugin using plugin name
							if ( is_plugin_active( "nextend-facebook-connect/nextend-facebook-connect.php" ) ) {
							  //plugin is activated
							
							?>

							

								<a class="register-social-button-facebook" href="<?php echo get_site_url(); ?>/wp-login.php?loginFacebook=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;"><i class="fa fa-facebook"></i></a>
								
							

							<?php } ?>

							<?php
							/**
							 * Detect plugin. For use on Front End only.
							 */
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

							// check for plugin using plugin name
							if ( is_plugin_active( "nextend-twitter-connect/nextend-twitter-connect.php" ) ) {
							  //plugin is activated
							
							?>

							

								<a class="register-social-button-twitter" href="<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1&redirect='+window.location.href; return false;"><i class="fa fa-twitter"></i></a>

							

							<?php } ?>

							<?php
							/**
							 * Detect plugin. For use on Front End only.
							 */
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

							// check for plugin using plugin name
							if ( is_plugin_active( "nextend-google-connect/nextend-google-connect.php" ) ) {
							  //plugin is activated
							
							?>

							

								<a class="register-social-button-google" href="<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1&redirect='+window.location.href; return false;"><i class="fa fa-google"></i></a>

							

							<?php } ?>
							
						</div>
						
						<div class="publish-ad-button login-page">

							<?php

								global $redux_demo; 
								$register = $redux_demo['register'];
								$reset = $redux_demo['reset'];

							?>
							
							<p><?php esc_html_e( 'Are You new here?', 'classify' ); ?> <a href="<?php echo $register; ?>"><?php _e( "Get Register Free", "classify" ); ?></a></p>

						</div>
						
					

					
	    		</div>

	    	</div>

	    	
	    </div>

    </section>

<?php get_footer(); ?>