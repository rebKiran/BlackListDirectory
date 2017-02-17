<?php
/**
 * Template name: Reset Password Page
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

global $resetSuccess;

if (!$user_ID) {

	if($_POST) 

	{

		// First, make sure the email address is set
		if ( isset( $_POST['email'] ) && ! empty( $_POST['email'] ) ) {

		  	// Next, sanitize the data
		  	$email_addr = trim( strip_tags( stripslashes( $_POST['email'] ) ) );

		  	$user = get_user_by( 'email', $email_addr );
		  	$user_ID = $user->ID;

		  	if( !empty($user_ID)) {

				$new_password = wp_generate_password( 12, false ); 

				if ( isset($new_password) ) {

					wp_set_password( $new_password, $user_ID );

					$message = "Check your email for new password.";

			      	$from = get_option('admin_email');
					$headers = 'From: '.$from . "\r\n";
					$subject = "Password reset!";
					$msg = "Reset password.\nYour login details\nNew Password: $new_password";
					wp_mail( $email_addr, $subject, $msg, $headers );

					$resetSuccess = 1;

				}

		    } else {

		      	$message = "There is no user available for this email.";

		    } // end if/else

		} else {
			$message = "Email should not be empty.";
		}

	}

}

get_header(); ?>

	<div class="ad-title">
	
        		<h2><?php the_title(); ?> </h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">

	    	<div class="first clearfix log-in">

				<div id="edit-profile" >

					<h2 class="login-title"><?php esc_html_e( 'RESET PASSWORD', 'classify' ); ?></h2>
						<form class="form-item login-form" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

							<?php if($_POST) { 

								echo "<div id='result' style='margin-bottom: 30px;'><div class='message'><h3>".$message."</h3></div></div>";

							} ?>

								<?php if($resetSuccess == 1) { 

							} else { ?>


									<input id="email" placeholder="<?php esc_html_e( 'Email Address', 'classify' ); ?>" type="text" name="email" class="text input-textarea half" value="" maxlength="30" />



								<div class="publish-ad-button login-page">
									<input type="hidden" name="submit" value="Reset" id="submit" />
									<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Submit', 'classify') ?></button>
								</div>

							<?php } ?>

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
							
							<p><a href="<?php echo $register; ?>"><?php _e( "Register an account", "classify" ); ?></a></p>

						</div>

					</div>

	    		</div>


	    	</div>

	    	
	    </div>

    </section>

<?php get_footer(); ?>