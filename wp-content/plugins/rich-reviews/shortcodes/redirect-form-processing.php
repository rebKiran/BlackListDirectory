<?php

	// Load WP

	$path = realpath(null);
    $parts = explode('wp-content', $path);
    if (count($parts) == 2) {
        $load_path = $parts[0] . 'wp-load.php';
    } else {
        die();
    }
    require_once($load_path);

    global $wpdb,$richReviews;

    if (!isset($_GET) || !isset($_GET['category']) || !isset($_GET['key']) || $_GET['category'] == '' || $_GET['key'] == '' || !isset($_GET['postID']) || $_GET['postID'] == '') {
    	// Something has happened so we'll return some kinda error
    	// Redirect
    	die();
    } else {
    	$key = $_GET['key'];
    	$category = $_GET['category'];
    	$postID = $_GET['postID'];
    }

    $user = wp_get_current_user();
    $options = $richReviews->rr_options;

    $rName  = '';
	$rEmail = '';
	$rTitle = '';
	$rText  = '';
	$rAuthorImage = '';
	$rAuthorId = '';
	$output = '';
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
			$rPostID   = $postID;
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


				do_action('rr_on_valid_data', $newSubmission, $options, $richReviews->sqltable);
				$return_uri = $_SESSION['form_uri'];
				unset($_SESSION['form_uri']);
				$_SESSION['form_return'] = array(
					'success_message' => true,
					'newSubmission'	=> $newSubmission
				);
				header('Location: ' . $return_uri);
				die();
			} else {
				$return_uri = $_SESSION['form_uri'];
				unset($_SESSION['form_uri']);

				$_SESSION['form_return'] = array(
					'newData'	=>	$newData,
					'errors'	=>	$errors
				);
				header('Location: ' . $return_uri);
				die();
			}
		}
	}
