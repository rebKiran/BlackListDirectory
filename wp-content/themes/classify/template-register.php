<?php
/**
 * Template name: Register Page
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

global $user_ID, $user_identity, $user_level, $registerSuccess;

$registerSuccess = "";


if (!$user_ID) {

	if($_POST) 

	{

		$message =  esc_html__( 'Registration successful.', 'classify' );

		$username = $wpdb->escape($_POST['username']);

		$email = $wpdb->escape($_POST['email']);

		$password = $wpdb->escape($_POST['pwd']);

		$confirm_password = $wpdb->escape($_POST['confirm']);

		$registerSuccess = 1;



		if(empty($username)) {
			$message =  esc_html__( 'User name should not be empty.', 'classify' );
			$registerSuccess = 0;
		}

		

		if(isset($email)) {

			if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)){ 

				wp_update_user( array ('ID' => $user_ID, 'user_email' => $email) ) ;

			}

			else { $message = esc_html__( 'Please enter a valid email.', 'classify' ); }

			$registerSuccess = 0;

		}

		if($password) {

			if (strlen($password) < 5 || strlen($password) > 15) {

				$message = esc_html__( 'Password must be 5 to 15 characters in length.', 'classify' );

				$registerSuccess = 0;

				}

			//elseif( $password == $confirm_password ) {

			elseif(isset($password) && $password != $confirm_password) {

				$message = esc_html__( 'Password Mismatch', 'classify' );

				$registerSuccess = 0;

			} elseif ( isset($password) && !empty($password) ) {

				$update = wp_set_password( $password, $user_ID );

				$message = esc_html__( 'Registration successful', 'classify' );

				$registerSuccess = 1;

			}

		}

		$status = wp_create_user( $username, $password, $email );
		if ( is_wp_error($status) ) {
			$registerSuccess = 0;
			$message = esc_html__( 'Username or E-mail already exists. Please try another one.', 'classify' );
		} else {
			classifyUserNotification( $email, $password, $username );		

			$registerSuccess = 1;
		}


		if($registerSuccess == 1) {

			$login_data = array();
			$login_data['user_login'] = $username;
			$login_data['user_password'] = $password;
			$user_verify = wp_signon( $login_data, false ); 

			global $redux_demo; 
			$profile = $redux_demo['profile'];
			wp_redirect( $profile ); exit;

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


				<?php 					
					if(get_option('users_can_register')) { //Check whether user registration is enabled by the administrator
				?>

				<div id="edit-profile" >
						<h2 class="login-title"><?php esc_html_e( 'REGISTER', 'classify' ); ?></h2> 
					
						<form class="form-item login-form" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

								<?php if($_POST) { 

									global $redux_demo; 
									$login = $redux_demo['login'];

									echo "<div id='result' style='margin-bottom: 30px;'><div class='message'><h4>".$message." ";

									if($registerSuccess == 1) {
										echo "<a href='".$login."'>Login</a>.";
									}

									echo "</h4></div></div>";

								} ?>

									<?php if($registerSuccess == 1) { } else { ?>

									

										
										
									<input id="contactName" pattern=".{4,}" required title="<?php esc_html_e( '4 characters minimum', 'classify' ); ?>" placeholder="<?php esc_html_e( 'Username', 'classify' ); ?>" type="text" name="username" class="text input-textarea half" value="" maxlength="30" required />

									

									

										
										<input id="email" placeholder="<?php esc_html_e( 'Email Address', 'classify' ); ?>" type="email" name="email" class="text input-textarea half" value=""  maxlength="30" required />

									

				
										<input id="password" pattern=".{6,}" required title="<?php esc_html_e( '6 characters minimum', 'classify' ); ?>" placeholder="<?php esc_html_e( 'Password', 'classify' ); ?>" type="password" name="pwd" class="text input-textarea half" maxlength="15"  value="" required />

	
										<input id="passwordr" placeholder="<?php esc_html_e( 'Retype Password', 'classify' ); ?>" type="password" name="confirm" class="text input-textarea half" maxlength="15" value="" required />

							

									<br/>

									<div class="publish-ad-button">
										<input type="hidden" name="submit" value="Register" id="submit" />
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
								$login = $redux_demo['login'];
								$reset = $redux_demo['reset'];

							?>
							
							<p><?php esc_html_e( 'Login with Social', 'classify' ); ?></p>

						</div>

					

	    		</div>

	    		<?php }
						
					else echo "<span class='registration-closed'>Registration is currently disabled. Please try again later.</span>";

				?>

	    	</div>
			


	    	

	    </div>

    </section>

<?php get_footer(); ?>