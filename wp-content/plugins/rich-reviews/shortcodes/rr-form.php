<?php

function handle_form($atts, $options, $sqltable, $path) {
	global $wpdb, $post, $richReviews;
	extract(shortcode_atts(
		array(
			'category' => 'none',
			'unique_key' => ''
		)
	,$atts));

	//initialize all data vars
	$rName  = '';
	$rEmail = '';
	$rTitle = '';
	$rText  = '';
	$rAuthorImage = '';
	$rAuthorId = '';
	$output = '';
	$user = wp_get_current_user();
	$displayForm = true;
	$posted = false;
	$outputSubmissionMessage = false;
	$errors = array(
		'name'	=>	'',
		'email'	=>	'',
		'title'	=>	'',
		'rating'	=>	'',
		'content'=>	'',
		'reviewer-image' => ''
	);

	$newData = array(
		'reviewer_name'   => $rName,
		'reviewer_image' => $rAuthorImage,
		'reviewer_email'  => $rEmail,
		'review_title'    => $rTitle,
		// 'review_rating'   => intval($rRating),
		// 'review_image_id' => $rImage,
		'review_text'     => $rText,
		'errors'		  => $errors
	);

	if (isset($options['submit-form-redirect']) && $options['submit-form-redirect']) {
		// Maybe get data from Session var
		if (isset($_SESSION['form_return']) && is_array($_SESSION['form_return'])) {
			$returned_data = $_SESSION['form_return'];
			if (isset($returned_data['errors']) && is_array($returned_data['errors'])) {
				$errors = $returned_data['errors'];
			}
			if (isset($returned_data['newData']) && is_array($returned_data['newData'])) {
				$newData = $returned_data['newData'];
			}
			if(isset($returned_data['success_message']) && $returned_data['success_message'] == true && isset($returned_data['newSubmission']) && is_array($returned_data['newSubmission'])) {
				$outputSubmissionMessage = true;
				$newSubmission = $returned_data['newSubmission'];
			}
		}
		// clear and reset form session
		unset($_SESSION['form_return']);
		unset($_SESSION['form_uri']);
		$_SESSION['form_uri'] = $_SERVER['REQUEST_URI'];
	}


	if(isset($unique_key) && $unique_key != '') {
		$key = $unique_key;
	} else {
		$key = 'Y';
	}


	if (isset($_POST['submitted'])) {
		if ($_POST['submitted'] == $key) {



			$posted = true;

			$incomingData = $_POST;
			$incomingData = apply_filters('rr_process_form_data', $incomingData);

			if ($options['form-name-display']) {
				if (!isset($_POST['rName'])) {
					if ($options['integrate-user-info'] && $options['form-name-use-usernames']) {
						if ($user->ID) {
							$rName = $user->data->display_name;
						}
					}
				} else {
					$rName = fp_sanitize($_POST['rName']);
				}
			}
			if ($options['form-name-use-avatar']) {
				if ($user->ID) {
					$rAuthorImage = get_avatar_url($user->ID);
				} else if ($options['unregistered-allow-avatar-upload']) {
					if (isset($_FILES) && count($_FILES) == 1 && isset($_FILES['rrInsertReviewerImageFile'])) {
						if (isset($_POST['rrInsertReviewerImageDisplay']) && $_POST['rrInsertReviewerImageDisplay'] != '') {
							$imgUrl = $_POST['rrInsertReviewerImageDisplay'];
							$fileEnding = strrchr($imgUrl, '.');
							if ($fileEnding) {
								$fileEnding = substr($fileEnding, 1);
								$allowed_file_types = array ('jpg', 'png', 'gif'); //probably make an option for this eventually.
								if (!in_array($fileEnding, $allowed_file_types)) {
									$rAuthorImage = 'Invalid Type';
								} else {
									$imageId = media_handle_upload('rrInsertReviewerImageFile',0);
									if (is_int($imageId)) {
										$rAuthorImage = wp_get_attachment_url($imageId);
									} else {
										$rAuthorImage = 'Invalid Type';
									}

								}
							}
						}
					}
				}
			}

			if ($options['form-email-display']) {
				if (!isset($_POST['rEmail'])) {
					if ($options['integrate-user-info'] && $options['form-email-use-useremails']) {
						if ($user->ID) {
							$rEmail = $user->data->user_email;
						}
					}
				} else {
					$rEmail    = fp_sanitize($_POST['rEmail']);
				}
			}
			if ($options['form-title-display']) {
				$rTitle    = fp_sanitize($_POST['rTitle']);
			}
			$rRating   = fp_sanitize($_POST['rRating']);
			// if ($options['form-reviewed-image-display']) {
			// 	$imageId = media_handle_upload('rImage',0);
			// 	$rImage = $imageId;

			// }
			if ($options['form-content-display']) {
				$rText     = fp_sanitize($_POST['rText']);
			}

			if ($options['integrate-user-info']) {
				if ($user->ID) {
					$rAuthorId = $user->ID;
				}
			}

			$rDateTime = date('Y-m-d H:i:s');
			$incomingData['rDateTime'] = $rDateTime;
			if ($options['require_approval']) {$rStatus   = 0;} else {$rStatus   = 1;}
			$rIP       = $_SERVER['REMOTE_ADDR'];
			$rPostID   = $post->ID;
			$rCategory = fp_sanitize($category);

			$newData = array (

					'date_time'       => $rDateTime,
					'reviewer_name'   => $rName,
					'reviewer_image' => $rAuthorImage,
					'reviewer_email'  => $rEmail,
					'review_title'    => $rTitle,
					'review_rating'   => intval($rRating),
					// 'review_image_id' => $rImage,
					'review_text'     => $rText,
					'review_status'   => $rStatus,
					'reviewer_id'	  => $rAuthorId,
					'reviewer_ip'     => $rIP,
					'post_id'		  => $rPostID,
					'review_category' => $rCategory,
					'isValid'		  => true,
					'errors'		  => $errors

			);


			$newData = apply_filters('rr_check_required', $newData);
			if ($newData['isValid']) {
				$newData = apply_filters('rr_misc_validation', $newData);

			}
			if ($newData['isValid']) {

				$displayForm = false;

				$newSubmission = array(
					'date_time'       => $newData['date_time'],
					'reviewer_name'   => $newData['reviewer_name'],
					'reviewer_image' => $newData['reviewer_image'],
					'reviewer_email'  => $newData['reviewer_email'],
					'reviewer_id'	  => $newData['reviewer_id'],
					'review_title'    => $newData['review_title'],
					'review_rating'   => $newData['review_rating'],
				// 	// 'review_image_id' => $newData['review_image_id'],
					'review_text'     => $newData['review_text'],
					'review_status'   => $newData['review_status'],
					'reviewer_ip'     => $newData['reviewer_ip'],
					'post_id'		  => $newData['post_id'],
					'review_category' => $newData['review_category'],
				);


				do_action('rr_on_valid_data', $newSubmission, $options, $sqltable);
			}
		}
	} else {
		?> <span id="state" style="text-align: center;color: #6dab3c;margin-left: 180px;line-height:11px;"></span> <?php
	}
	if ($displayForm) {
		if ($options['require-login'] && !$user->ID) {
			?> <div class="rr_review_form">	<?php
				do_action('rr_display_form_gate', $options);
			?>	</div>  <?php
		} else {

			$errors = $newData['errors'];
			$errors = generate_error_text($errors, $options);

			if ($options['form-submit-text'] == '') {
				$submitText = __('Submit', 'rich-reviews');
			} else {
				$submitText = $options['form-submit-text'];
			}

		if ($outputSubmissionMessage) {
			rr_output_response_message($newSubmission, $options);
		}

		if(isset($options['submit-form-redirect']) && $options['submit-form-redirect']) {
			$form_action = $richReviews->plugin_url . 'shortcodes/redirect-form-processing.php?key=' . $key . '&category=' . $category . '&postID=' . $post->ID;
		} else {
			$form_action = '';
		}

		?>
<style>
.modal-body td {
     line-height:0px !important;
     width: 2px;
}
.modal-body p {
    line-height:0px !important;
   
}
.modal-body {
    padding: 8px !important;
   
   
}
.modal-body #state {
    margin-left: 200px !important;
   
   
}
.modal.fade.in {
    top: 3%;
}
</style>
		<form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" class="rr_review_form">
			<input type="hidden" name="submitted" value="<?php echo $key; ?>" />
			<input type="hidden" name="rRating" id="rRating" value="0" />
			<table class="form_table">
			<?php do_action('rr_do_form_fields', $options, $path, $newData, $errors); ?>

				<tr class="rr_form_row">
					<td></td>
					<td class="rr_form_input"><input class="btn btn-info btn-lg" id="submitReview" type="button" onclick="validateform();" value="<?php echo $submitText; ?>"/></td>
				</tr>
			</table>
		</form>
<script type="text/javascript">

jQuery( document ).ready(function() {


 jQuery('#myModal').on('show.bs.modal', function (e) {
      jQuery(".modal-body .form-err").empty();
      jQuery(".modal-body #state").empty();
      jQuery(".modal-header #review_msg").empty();
      jQuery(".modal-body input[name='rName']").val('');
			
      jQuery(".modal-body input[name='rEmail']").val('');
      jQuery(".modal-body input[name='rTitle']").val('');
      jQuery(".modal-body #rText123").val('');
      jQuery(".modal-body #rRating").val('');
      jQuery(".modal-body .rr_stars_container span").removeClass('glyphicon-star');
      jQuery(".modal-body .rr_stars_container span").addClass('glyphicon-star-empty');
  })
});



jQuery("form .rr_review_form .state").html('');
jQuery("form .rr_review_form input[name='rName']").parent('td').find('span').html('');

		function validateform() {
		
			jQuery(".modal-body .form-err").empty();
			jQuery(".modal-body .state").empty();
                        jQuery(".modal-header #review_msg").empty();
			
			jQuery("#rText123").parent('td').find('span').html();
			
			var name = jQuery("input[name='rName']").val();
			name = name.trim();

			var email = jQuery("input[name='rEmail']").val();
			email = email.trim();
				
			var title = jQuery("input[name='rTitle']").val();
			title = title.trim();
				
			var text1 = jQuery("#rText123").val();
			text1 = text1.trim();
			
			var rating = jQuery("#rRating").val();
			rating = rating.trim();
			
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			 var valid = true;
			 if( '' == name) {
				jQuery("input[name='rName']").parent('td').find('span').html("<font size='2'>Name is required.</font>");
			  
				valid = false;
			 } 
			 if( '' == email) {
				jQuery("input[name='rEmail']").parent('td').find('span').html("<font size='2'>Email is required.</font>");
			   
				valid = false;
			 } 
			 
			 if( '' == title) {
				jQuery("input[name='rTitle']").parent('td').find('span').html("<font size='2'>Review Title is required.</font>");
			   
				valid = false;
			 } 
			 if( 0 == rating) { 
				jQuery("#rating-err").html("<font size='2'>Please add rating.</font>");
			   
				valid = false;
			 } 
			 if( '' != email ) {
				 if( false == re.test(email)) {
					jQuery("input[name='rEmail']").parent('td').find('span').html("<font size='2'>Please enter valid email.</font>");
					valid = false;
					 
				 } else {
					 jQuery("input[name='rEmail']").parent('td').find('span').html();
				 }
			 } 
			 
			if( '' == text1) {
				jQuery("#rText123").parent('td').find('span').html("<font size='2'>Review Content is required.</font>");
			   // document.getElementByName('rName').focus();
				//return false;
				valid = false;
			 } 
				
			 if( false == valid ) {
				 return false;
			 } else {
				 jQuery("#submitReview").attr('disabled', true);
				 jQuery.ajax({
					type: "POST",
					url: "http://blacklistdir.rebelute.in/wp-admin/admin-ajax.php",
					data: "action=add_review&rName="+name+"&rEmail="+email+"&rTitle="+title+"&rText="+text1+"&rRating="+rating,
					success: function( obj ){

						jQuery(".modal-body #state").show();
						/*jQuery(".modal-body #state").html(obj);*/
                                                jQuery(".modal-header #review_msg").show();
                                                jQuery(".modal-header #review_msg").html(obj);
						jQuery("input[name='rName']").val('');
						jQuery("input[name='rEmail']").val('');
						jQuery("input[name='rTitle']").val('');
						jQuery(".modal-body .rr_stars_container span").removeClass('glyphicon-star');
                                                jQuery(".modal-body .rr_stars_container span").addClass('glyphicon-star-empty');
						jQuery("#rText123").val('');
						jQuery("#rRating").val(0);
                                                
                                                jQuery("#submitReview").attr('disabled', false);
						
					}
				});
				
				return true;
			 }
			 return false;
		}
</script>
		<?php

		}
	}
	do_action('rr_set_local_scripts');
}
function rr_do_form_gate($options) {

	if (isset($options['login-url']) && $options['login-url'] != '' ) {
		$loginUrl = $options['login-url'];
	} else {
		$loginUrl = wp_login_url();
	}
	?>
	<div class="upload-gate">
		<p class="rr-headline">You must be logged in to Submit a Review</p>
		<a href="<?php echo $loginUrl; ?>" class="button button-primary">
			Login/Create Account
		</a>
	</div>
	<style>
		.upload-gate {
			padding-top:0;
			border: solid 3px;
			border-radius: 8px;
			text-align: center;
			margin: 13px 5px;
			padding: 21px;
		}
		.rr-headline {
			font-size: 21px;
			margin: 21px 13px;
		}
		.button {
			padding: 0.4em 1.2em;
			border: solid 3px;
			border-radius: 3px;
		}
	</style>
	<?php
}
function generate_error_text($errors, $options) {


	$processed = array();
	foreach($errors as $key => $val) {

		$option_key = 'form-' . $key . '-label';
		$label = '';
		if ($options[$option_key] == '') {
			switch($key) {
				case 'name':
					$label = __('name', 'rich-reviews');
					break;
				case 'title':
					$label = __('title', 'rich-reviews');
					break;
				case 'email':
					$label = __('email', 'rich-reviews');
					break;
				case 'reviewer-image':
					$label = __('reviewer image');
					break;
				case 'content':
					$label = __('content', 'rich-reviews');
					break;
				case 'rating':
					$label = __('rating', 'rich-reviews');
					break;
				default:
					break;
			}
		} else {
			$label = $options[$option_key];
		}

		if ($val == 'absent required') {
			$processed[$key] = __('The', 'rich-reviews') . ' ' . $label . ' ' . __('field is required.', 'rich-reviews');
		} else if ($val == 'invalid input') {
			$processed[$key] = __('Please enter a valid', 'rich-reviews') . ' ' . $label;
		} else if ($val == 'length violation') {
			$processed[$key] = __('The', 'rich-reviews') . ' ' . $label . ' ' . __('that you entered is too long.', 'rich-reviews');
		} else {
			$processed[$key] = '';
		}
	}
	return $processed;
}

function sanitize_incoming_data($incomingData) {

	$modifiedData = array();
	foreach($incomingData as $field => $val) {
		$incomingData[$field] = fp_sanitize($val);
	}
	// $incomingData = $modifiedData;
	return $incomingData;
}

function rr_do_rating_field($options, $path, $rData = null, $errors = null) {
	$error = $errors['rating'];
	if ($options['form-rating-label'] == '') {
		$label = __('Rating', 'rich-reviews');
	} else {
		$label = $options['form-rating-label'];
	}

	@include $path . 'views/frontend/form/rr-star-input.php';

}

function render_custom_styles($options) {
	?>
	<style>
		.stars, .rr_star {
			color: <?php echo $options['star_color']?>;
		}
	</style>
	<?php
}

function rr_do_name_field($options, $path, $rData = null, $errors = null) {
	$inputId = 'Name';
	$require = false;
	$rFieldValue = '';
	$error = $errors['name'];

	if ($options['form-name-label'] == '') {
		$label = __('Name', 'rich-reviews');
	} else {
		$label = $options['form-name-label'];
	}

	$user = wp_get_current_user();
	$disable = false;
	if ($options['form-name-require']) {
		$require = true;
	}
	if ($rData['reviewer_name']) {
		$rFieldValue = $rData['reviewer_name'];
	} else {
		if ($options['integrate-user-info'] && $options['form-name-use-usernames']) {
			if ($user->ID) {
				$rFieldValue = $user->data->display_name;
				$disable = true;
			}
		}
	}

	@include $path . 'views/frontend/form/rr-text-input.php';
}

function rr_do_reviewer_img_field($options, $path, $rData = null, $errors = null) {
	$user = wp_get_current_user();
	if ($user->ID) {
		//do nothing, because we will be using the user avatar.
	} else {
		$require = false;
		$rFieldValue = '';

		if ($options['form-reviewer-image-label'] == '') {
			$label = __('Reviewer Image', 'rich-reviews');
		} else {
			$label = $options['form-reviewer-image-label'];
		}

		$error = $errors['reviewer-image'];
		if ($options['form-reviewer-image-require']) {
			$require = true;
		}
		if (isset($rData['reviewer_image']) && $rData['reviewer_image'] != '' ) {
			$rFieldValue = $rData['reviewer_image'];
		}
		//feed needed info.
		@include $path . 'views/frontend/form/rr-img-input.php';
	}
}


function rr_do_email_field($options, $path, $rData = null, $errors = null) {
	$inputId = 'Email';
	$require = false;
	$rFieldValue = '';
	$error = $errors['email'];

	if ($options['form-email-label'] == '') {
		$label = __('Email', 'rich-reviews');
	} else {
		$label = $options['form-email-label'];
	}

	$user = wp_get_current_user();
	$disable = false;

	if ($options['form-email-require']) {
		$require = true;
	}
	if ($rData['reviewer_email']) {
		$rFieldValue = $rData['reviewer_email'];
	} else {
		if ($options['integrate-user-info'] && $options['form-email-use-useremails']) {
			if ($user->ID) {
				$rFieldValue = $user->data->user_email;
				$disable = true;
			}
		}
	}

	@include $path . 'views/frontend/form/rr-text-input.php';
}

function rr_do_title_field($options, $path, $rData = null, $errors = null) {
	$inputId = 'Title';
	$require = false;
	$rFieldValue = '';
	$error = $errors['title'];

	if ($options['form-title-label'] == '') {
		$label = __('Title', 'rich-reviews');
	} else {
		$label = $options['form-title-label'];
	}

	if ($options['form-title-require']) {
		$require = true;
	}
	if ($rData['review_title']) {
		$rFieldValue = $rData['review_title'];
	}

	@include $path . 'views/frontend/form/rr-text-input.php';
}

function rr_do_content_field($options, $path, $rData = null, $errors = null) {
	$inputId = 'Text';
	$require = false;
	$rFieldValue = '';
	$error = $errors['content'];

	if ($options['form-content-label'] == '') {
		$label = __('Review Content', 'rich-reviews');
	} else {
		$label = $options['form-content-label'];
	}

	if ($options['form-content-require']) {
		$require = true;
	}
	if ($rData['review_text']) {

		$rFieldValue = $rData['review_text'];
	}
	@include $path . 'views/frontend/form/rr-textarea-input.php';
}

function rr_insert_new_review($data, $options, $sqltable) {

	global $wpdb;
	$wpdb->insert($sqltable, $data);

	// if ($data['review_status'] == 1) {
	// 	global $richReviews;
	// 	$richReviews->shortcode_reviews_to_posts($data);
	// }

}

function rr_output_response_message($data, $options) {

	?>
	<div class="rr_successful">
		<span class="rr_star glyphicon glyphicon-star left" style="font-size: 34px;"></span>
		<span class="rr_star glyphicon glyphicon-star big-star right" style="font-size: 34px;"></span>
		<center>
			<strong>
				<?php

					if ($options['form-name-display'] && $options['form-name-require']) {
						echo $data['reviewer_name'] . ', ' . __('your review has been recorded', 'rich-reviews');
					} else {
						_e('Your review has been recorded', 'rich-reviews');
					}
					if ($options['require_approval']) {
						_e(' and submitted for approval', 'rich-reviews');
					}
					echo '. ' . __('Thanks!', 'rich-reviews');
				?>
			</strong>
		</center>
		<div class="clear"></div>
	</div>
	<?php
}

function rr_output_scroll_script() {

	?>
		<script>
			jQuery(function(){
				if (jQuery(".rr_successful").is(":visible")) {

					offsetOne = jQuery(".rr_successful").offset();
					jQuery("html, body").animate({
						scrollTop: (offsetOne.top - 400)
					});
				} else {
					errorPresent = false;
					jQuery(".form-err").each(function () {
						if (this.innerHTML != ''){
							errorPresent = true;
						}
					});
					if (errorPresent) {

						offsetTwo = jQuery(".form-err.shown").offset();
						jQuery("html, body").animate({
							scrollTop: (offsetTwo.top + 200)
						});
					}
				}
			});
		</script>
	<?php

}

function rr_send_admin_email($data, $options) {

	extract($data);
	$message = "";
	$message .= "RichReviews User,\r\n";
	$message .= "\r\n";
	$message .= __("You have received a new review which is now pending your approval. The information from the review is listed below.", 'rich-reviews') . "\r\n";
	$message .= "\r\n";
	$message .= __("Review Date: ", 'rich-reviews') .$date_time."\r\n";
	if ( $reviewer_name != "" ) {
		if ($options["form-name-label"] != '') {
			$message .= $options["form-name-label"].": ".$reviewer_name."\r\n";
		} else {
			$message .= __('Name','rich-reviews') . ": ".$reviewer_name."\r\n";
		}
	}
	if ( $reviewer_email != "" ) {
		if ($options["form-email-label"] != '') {
			$message .= $options["form-email-label"].": ".$reviewer_email."\r\n";
		} else {
			$message .= __('Email','rich-reviews') . ": ".$reviewer_email."\r\n";
		}
	}
	if ( $review_title != "" ) {
		if ($options["form-title-label"] != '') {
			$message .= $options["form-title-label"].": ".$review_title."\r\n";
		} else {
			$message .= __('Title','rich-reviews') . ": ".$review_title."\r\n";
		}
	}
	$message .= __("Review Rating: ", 'rich-reviews'). $review_rating ."\r\n";
	if ($review_text != "" ) {
		if ($options["form-content-label"] != '') {
			$message .= $options["form-content-label"].": ".$review_text."\r\n";
		} else {
			$message .= __('Review Content','rich-reviews') . ": ".$review_text."\r\n";
		}
	}
	$message .= __("Review Category: ", 'rich-reviews') . $review_category ."\r\n\r\n";

	$message .= __("Click the link below to review and approve your new review.", 'rich-reviews'). "\r\n";
	$message .= admin_url() . "/admin.php?page=fp_admin_pending_reviews_page\r\n\r\n";
	$message .= __("Thanks for choosing Rich Reviews,", 'rich-reviews'). "\r\n";
	$message .= __("The Nuanced Media Team", 'rich-reviews');

	$mail_subject = __('New Pending Review', 'rich-reviews');

	mail($options['admin-email'], $mail_subject, $message);
}

// Validation for the existence of required fields.

function rr_require_name_field($incomingData) {

	if ($incomingData['reviewer_name'] == '') {
		$incomingData['isValid'] = false;
		$incomingData['errors']['name'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_title_field($incomingData) {

	if ($incomingData['review_title'] == '') {
		$incomingData['isValid'] = false;
		$incomingData['errors']['title'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_email_field($incomingData) {

	if ($incomingData['reviewer_email'] == '') {
		$incomingData['isValid'] = false;
		$incomingData['errors']['email'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_content_field($incomingData) {
	if ($incomingData['review_text'] == '') {
		$incomingData['isValid'] = false;
		$incomingData['errors']['content'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_rating_field($incomingData) {
	if ($incomingData['review_rating'] == 0) {
		$incomingData['isValid'] = false;
		$incomingData['errors']['rating'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_reviewer_image_field($incomingData) {
	$user = wp_get_current_user();
	if ($user->ID){
		return $incomingData;
	} else {
		if (!isset($incomingData['reviewer_image']) || $incomingData['reviewer_image'] == '') {
			$incomingData['isValid'] = false;
			$incomingData['errors']['reviewer-image'] = 'absent required';
		}
	}
	return $incomingData;
}

// Field Specific Validation ('rr_misc_validation')

function rr_validate_name_length($incomingData) {
	if (strlen($incomingData['reviewer_name']) > 40) {
		$incomingData['isValid'] = false;
		$incomingData['errors']['name'] = 'length violation';
	}
	return $incomingData;
}

function rr_validate_email($incomingData) {

	if ($incomingData['reviewer_email'] != '') {
		if (strlen($incomingData['reviewer_email']) > 150 ) {
			$incomingData['isValid'] = false;
			$incomingData['errors']['email'] = 'length violation';
		} else {
			$firstAtPos = strpos($incomingData['reviewer_email'],'@');
			$periodPos  = strpos($incomingData['reviewer_email'],'.');
			$lastAtPos  = strpos($incomingData['reviewer_email'],'@');
			if (($firstAtPos === false) || ($firstAtPos != $lastAtPos) || ($periodPos === false)) {
					$incomingData['isValid'] = false;
					$incomingData['errors']['email'] = 'invalid input';
			}
		}
	}
	return $incomingData;
}

function rr_validate_reviewer_image_input($incomingData) {
	$user = wp_get_current_user();
	if ($user->ID){
		return $incomingData;
	} else {
		if (isset($incomingData['reviewer_image']) && $incomingData['reviewer_image'] != '') {
			$fileEnding = strrchr($incomingData['reviewer_image'], '.');
			if ($fileEnding) {
				$fileEnding = substr($fileEnding, 1);
				$allowed_file_types = array ('jpg', 'png', 'gif'); //probably make an option for this eventually.
				if (!in_array($fileEnding, $allowed_file_types)) {
					$incomingData['isValid'] = false;
					$incomingData['errors']['reviewer-image'] = 'invalid input';
				}
			} else {
				$incomingData['isValid'] = false;
				$incomingData['errors']['reviewer-image'] = 'invalid input';
			}
		} else {
			$incomingData['isValid'] = false;
			//we shouldn't have gotten this far without
		}
	}

	return $incomingData;
}

function rr_validate_title_length($incomingData) {
	if ($incomingData['review_title'] != '' ) {
		if (strlen($incomingData['review_title']) > 40) {
			$incomingData['isValid'] = false;
			$incomingData['errors']['title'] = 'length violation';
		}
	}
	return $incomingData;
}

function rr_validate_content_length($incomingData) {
	if ($incomingData['review_text'] != '' ) {
		if (strlen($incomingData['review_title']) > 300) {
			$incomingData['isValid'] = false;
			$incomingData['errors']['content'] = 'length violation';
		}
	}
	return $incomingData;
}




function fp_sanitize($input) {

	if (is_array($input)) {
		foreach($input as $var=>$val) {
			$output[$var] = fp_sanitize($val);
		}
	}
	else {
		if (get_magic_quotes_gpc()) {
			//$input = stripslashes($input);
		}
		$input  = clean_input($input);
		//$output = mysql_real_escape_string($input);
		$output = $input;
	}
	return $output;
}

function clean_input($input) {
		/*$search = array(
			'@<script[^>]*?>.*?</script>@si',   // strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // strip multi-line comments
		);
		$output = preg_replace($search, '', $input);*/
		$handling = $input;

		/*$handling = strip_tags($handling);
		$handling = stripslashes($handling);
		$handling = esc_html($handling);
		$handling = mysql_real_escape_string($handling);*/

		$handling = sanitize_text_field($handling);
		$handling = stripslashes($handling);

		$output = $handling;
		return $output;
	}
