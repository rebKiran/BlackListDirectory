<?php
	// THIS FILE NEEDS SOME WORK AT SOME POINT.... good god...

	$options = $this->parent->shopApp->options->get_option();
	if (isset($options['total_review_count']) && is_int($options['reviews_pulled_count'])) {
		$unpulled_reviews = intval($options['total_review_count']) - intval($options['reviews_pulled_count']);
		if ($unpulled_reviews > 0) {
			$unpulled_reviews = (string)$unpulled_reviews;
		} else {
			$unpulled_reviews = '0';
		}
	} else {
		$unpulled_reviews = null;
	}

	$init_active = '';
	$info_active = '';


	if (isset($options['api_url']) && $options['api_url'] != '') {
		$init_active = 'active';
	} else {
		$info_active = 'active';
	}
		if (isset($options['site_id']) && isset($options['site_token']) && $options['site_id'] != '' && $options['site_token'] != '') {
			if(  isset($options['dismissed_help_notice']) && !$options['dismissed_help_notice']) {
				?>
					<div class="notice notice-info is-dismissible internal-notice"><p><?php echo __('Need help configuring Shopper Approved? Read our tutorial', 'rich-reviews') . ' <a href="http://hubs.ly/H02f5kp0" targe=_blank>' . __('here', 'rich-reviews') . '</a>, '. __('or get', 'rich-reviews') . ' <a href="http://plugins.nuancedmedia.com/buy-support/" target=_blank>' . __('Installation Assistance', 'rich-reviews'); ?></a>.</p></div>
					<script>
						jQuery(function() {
							setTimeout(function() {
								jQuery('.internal-notice .notice-dismiss').click(function() {
									jQuery.ajax({
										type: 'POST',
										dataType: 'text',
										url: '/wp-admin/admin-ajax.php',
								        data: {
								        	action: 'rr_dismissed_help_notice'
								    	}
								    });
								});
							}, 300);
						});
					</script>
				<?php
			}
		}
		?>
		<div class="rr_shortcode_container shop-app-tab">
			<?php
			if( !(isset($options['site_id']) && isset($options['site_token']) && $options['site_id'] != '' && $options['site_token'] != '')) {
			?>
				<div class="sa-short-info">
					<h2><?php _e('Are you interested in Increased CTR? Decreased bounce rates? Star ratings in both PPC and Organic search results?', 'rich-reviews'); ?></h2>
					<p>
						 <?php echo __('If you’re mumbling a “yes please!” to yourself, it may be the right time to become a ', 'rich-reviews') . '<a target=_blank href="http://hubs.ly/H02f4C40">Shopper Approved member</a>. ' . __('Having access to your Shopper Approved account through the Rich Reviews plugin will create ease in managing both accounts and also accelerate fulfilling your marketing and revenue goals.', 'rich-reviews'); ?>
					</p>
					<p>
						 <?php _e('By being a Shopper Approved member, you’ll be able to', 'rich-reviews'); ?>:
					</p>
					<ul>
						<li><strong><?php _e('Team Up', 'rich-reviews'); ?></strong> <?php _e('with a  Google review site who has authority and will give your reviews maximum online exposure', 'rich-reviews'); ?>.</li>
						<li><strong><?php _e('Rise', 'rich-reviews'); ?></strong> <?php _e('to the top of SERPs and outrank your competitors', 'rich-reviews'); ?>.</li>
						<li><strong><?php _e('Increase', 'rich-reviews'); ?></strong> <?php _e('Click-Through-Rates', 'rich-reviews'); ?> / <strong><?php _e('Decrease', 'rich-reviews'); ?></strong> <?php _e('Cost-Per-Clicks and Bounce rates', 'rich-reviews'); ?>.</li>
						<li><strong><?php _e('Syndicate', 'rich-reviews'); ?></strong> <?php _e('your reviews through Google, Yahoo & Bing as well as Google Shopping & Product Listing Ads', 'rich-reviews'); ?>.</li>
					</ul>
				</div>
				<h2><?php _e('Why do I need Shopper Approved?', 'rich-reviews'); ?></h2>
				<p>
					<?php echo __('Shopper Approved is a ', 'rich-reviews') . '<a target=_blank href="http://hubs.ly/H02f5480">' . __('Google approved review website for Seller Ratings', 'rich-reviews') . '</a>.' . __(' Basically, Google wants to make sure you are collecting reviews ethically and Shopper Approved, a Google Authorized 3rd Party Product Review Aggregator, facilitates this. Reviews collected via Shopper Approved will show up in your organic and PPC search results in Google, Yahoo & Bing.', 'rich-reviews'); ?>
				</p>
				<a target=_blank href="http://hubs.ly/H02f56Q0">
					<img src="<?php echo $this->parent->plugin_url; ?>images/RR-sa-cta.jpg" class="sa-cta" />
				</a>
				<div class="rr-one-half rr-right">
					<h3><?php _e('Quick Links', 'rich-reviews'); ?></h3>
					<div style="padding:0 5%;">
						<ul>
							<li><a target=_blank href="http://hubs.ly/H02f54K0"><?php _e('Seller Rating What are they?', 'rich-reviews'); ?></a></li>
							<li><a target=_blank href="http://hubs.ly/H02f5kp0"><?php _e('Shopper Approved Setup Tutorial', 'rich-reviews'); ?></a></li>
							<li><a target=_blank href="http://hubs.ly/H02f5ln0"><?php _e('Which Seller Rating company should I use?', 'rich-reviews'); ?></a></li>
						</ul>
					</div>
				</div>
				<div class="rr-one-half rr-left middle-padded">
					<div class="button" id="sa-more"><?php _e('Read More', 'rich-reviews'); ?></div>
					<div class="sa-more-replace">
						<span class="stacked-header">Shopper</span>
						<span class="stacked-header">Approved</span>
					</div>
					<script>
						jQuery(function() {
							jQuery('#sa-more').click(function(e) {
								e.preventDefault();
								jQuery('.sa-long-info').addClass('active');
								jQuery(e.target).remove();
								jQuery('.sa-more-replace').toggleClass('active');
								target = jQuery('.sa-long-info').offset().top - 170;
								jQuery('html, body').animate({scrollTop: target}, 600);
							});
						});
					</script>
				</div>
				<div class="clear"></div>

				<div class="sa-long-info">
					<p>
						<?php _e('Shopper Approved is a seller rating extension that allows you to collect, manage and promote your customer reviews online for your business. Showcasing reviews creates an overall positive image for companies to current and potential customers. Since Shopper Approved is a certified Google partner, your reviews will get maximum exposure in the online world. Read more about the importance of Shopper Approved', 'rich-reviews'); ?> <a target=_blank href="http://hubs.ly/H02f5m00"><?php _e('here', 'rich-reviews'); ?></a>.
					</p>

					<h3><?php _e('Why choose Shopper Approved?', 'rich-reviews'); ?></h3>
					<p>
						<?php _e('Below is a list of benefits and features of Shopper Approved.', 'rich-reviews'); ?>
						<ul>
							<li><?php _e('Collects 70% more reviews and are visible on more search engines than their competitors.', 'rich-reviews'); ?></li>
							<li><?php _e('Provides clients the ability to improve customer engagement.', 'rich-reviews'); ?></li>
							<li><?php _e('Makes customer reviews visible to public on SERPs - 88% of consumers trust online reviews as much as personal recommendations.', 'rich-reviews'); ?></li>
							<li><?php _e('Increased CTR - Google found that there was an average of a 17% rise in CTR for advertisers with seller rating extensions.', 'rich-reviews'); ?></li>
							<li><?php _e('Companies can improve their brand image by displaying customer reviews on product, merchant and local listings.', 'rich-reviews'); ?></li>
							<li><?php _e('Increased quality score of your PPC.', 'rich-reviews'); ?></li>
							<li><?php _e('Reduction of CPC.', 'rich-reviews'); ?></li>
							<li><?php _e('Increased conversion rates.', 'rich-reviews'); ?></li>
							<li><?php _e('Shows company transparency, which gains trust from customers', 'rich-reviews'); ?>.</li>
							<li><?php _e('Easy to set up - learn how to set up your account here (link Gabe’s blog).', 'rich-reviews'); ?></li>
							<li><?php _e('There are tools to create surveys, special offers, and promotional emails.', 'rich-reviews'); ?></li>
							<li><?php _e('Offers a full and free 30-day trial with no strings attached.', 'rich-reviews'); ?></li>
							<li><?php _e('Has less expensive monthly subscription compared to some competitors.', 'rich-reviews'); ?></li>
						</ul>
					</p>

					<h3><?php _e('Set it up and Try it out!', 'rich-reviews'); ?></h3>
					<p>
						<?php _e('It’s human nature to research and test things out before committing, especially when it’s dealing with important information such as customer opinions about your company and/or products. Doing a trial run takes a few easy steps', 'rich-reviews'); ?>:
						<ol>
							<li><?php _e('Sign up - Take advantage of the free 30-day trial by signing up under our Nuanced Media', 'rich-reviews'); ?> <a target=_blank href="http://hubs.ly/H02f56Q0"><?php _e('discount code', 'rich-reviews'); ?></a>, <?php _e('which will give you an extra 20% off a membership if you choose to continue.', 'rich-reviews'); ?></li>
							<li><?php echo __('Learn how to easily set up your account through our ', 'rich-reviews') . '<a target=_blank href="http://hubs.ly/H02f5kp0" >' . __('tutorial.', 'rich-reviews') . '</a>'; ?></li>
							<li><?php _e('Explore and test Shopper Approved for 30 days.', 'rich-reviews'); ?></li>
						</ol>
					</p>

					<h3><?php _e('More Information', 'rich-reviews'); ?></h3>
					<p>
						<?php _e('For more information or help with Shopper Approved, read our company’s ', 'rich-reviews'); ?><a target=_blank href="http://hubs.ly/H02f57m0"><?php _e('thoughts and experiences', 'rich-reviews'); ?></a> <?php _e('with Shopper Approved or visit the', 'rich-reviews'); ?> <a target=_blank href="http://hubs.ly/H02f4C40"><?php _e('Shopper Approved website', 'rich-reviews'); ?></a>.
					</p>
				</div>
				<br/>
				<hr>
				<br/>

			<?php } ?>
			<!-- check if this is already set -->

			<div class="shop-app-info <?php echo $info_active; ?> options-section">
				<h3><?php _e('Already registered with Shopper Approved?', 'rich-reviews'); ?></h3>
				<button class="button toggle-shop-app-config">
					<?php _e('Configure Shopper Approved Extension', 'rich-reviews'); ?>
				</button>
			</div>
			<script type="text/javascript">
				jQuery('.toggle-shop-app-config').click(function(){
					jQuery('.shop-app-init').toggleClass('active');
					jQuery('.shop-app-info').toggleClass('active');

				});
			</script>
			<div class="shop-app-init <?php echo $init_active; ?> options-section">
				<form id="shopAppAdmin"method="post" action="">
					<input type="hidden" name="dinner" value="served" />
					<div class="label-container rr-one-fifth">
						<label for="api_url" style="font-size:21px;">
							<?php _e('Enter API Url (json):', 'rich-reviews'); ?>
						</label>
					</div>
					<br />
					<div class="input-container" >
						<input type="text" name="api_url" placeholder="<?php _e('API URL', 'rich-reviews'); ?>" <?php if($options['api_url'] != null) { echo 'value="' . $options['api_url'] . '"';} ?> />
					</div>
					<div class="clear"></div>
					<br />
					<input type="submit" class="button" id="submit-api-url" value="<?php _e('Submit Url', 'rich-reviews'); ?>" style="float:right;" />
					<div class="clear"></div>

			<br />
				<?php
					if((isset($options['site_id']) && isset($options['site_token'])) && ($options['site_id'] != '' && $options['site_token'] != '')) {
						?>
						<div class="label-container" style="width:30%;float:left;">
							<label for="api_url" style="float:right;">
								<?php _e('Extracted Site ID:', 'rich-reviews'); ?>
							</label>
						</div>
						<div class="input-container" style="width:66%;float:right;">
							<input type="text" name="site_id" style="width: 100%;float:left;" placeholder="<?php _e('API URL', 'rich-reviews'); ?>" style="vertical-align:bottom;" <?php echo 'value="' . $options['site_id'] . '"'; ?> disabled />
						</div>
						<div class="clear input-break"></div>
						<br />
						<div class="label-container" style="width:30%;float:left;">
							<label for="api_url" style="float:right;">
								<?php _e('Extracted Site Token:', 'rich-reviews'); ?>
							</label>
						</div>
						<div class="input-container" style="width:66%;float:right;">
							<input type="text" name="site_token" style="width: 100%;float:left;" placeholder="API URL" style="vertical-align:bottom;" <?php echo 'value="' . $options['site_token'] . '"';?> disabled />
						</div>
						<div class="clear input-break"></div>
						<br />
						<div class="label-container" style="width:30%;float:left;">
							<label for="total_review_count" style="float:right;">
								<?php _e('Total Shopper Approved Reviews:', 'rich-reviews'); ?>
							</label>
						</div>
						<div class="input-container" style="width:66%;float:right;">
							<input type="text" name="total_review_count" disabled class="" style="width:100%;float:left;" <?php if($options['total_review_count'] != null) { echo 'value="' . $options['total_review_count'] . '"';} ?> disabled />
						</div>
						<div class="clear input-break"></div>
						<br />
						<div class="label-container" style="width:30%;float:left;">
							<label for="average_score" style="float:right;">
								<?php _e('Average Score:', 'rich-reviews'); ?>
							</label>
						</div>
						<div class="input-container" style="width:66%;float:right;">
							<input type="text" name="average_score" disabled class="" style="width:100%;float:left;" <?php if($options['average_score'] != null) { echo 'value="' . $options['average_score'] . '"';} ?> disabled />
						</div>
						<div class="clear input-break"></div>
						</form>
					</div>

					<div class="clear"></div>

					<div class="options-section">
						<h2><?php _e('Snippet Settings', 'rich-reviews'); ?></h2>
						<hr>
						<div class="label-container" style="width:30%; float:left;">
							<label for="html-markup" style="float:right;">
								<?php _e('Shopper Approved Merchant Markup:', 'rich-reviews'); ?>
								<div style="font-size: 11px; font-weight: 400; margin-top: 8px; font-style: italic;"><?php _e('Use the shortcode', 'rich-reviews'); ?><br/><code style="font-style: normal; font-size: 11px;">[RR_SHOPPER_APPROVED get="merchant-schema"]</code> <?php _e('to output this markup', 'rich-reviews'); ?></div>
							</label>
						</div>
						<div class="input-container" style="width:66%;float:right;">
							<code name="Shopper Approved" placeholder="API Key" rows="10" cols="100" style="overflow:scroll;width:100%;float:left;" >
							<?php if($options['merchant_markup'] != null) { echo htmlspecialchars($options['merchant_markup']);} ?>
							</code>
						</div>
						<div class="clear input-break"></div>
						<br />
						<?php
							if (isset($options['product_catalog_ids']) && !empty($options['product_catalog_ids'])) {
								?>
									<div class="label-container" style="width:30%; float:left;">
										<label for="html-markup" style="float:right;">
											<?php _e('Shopper Approved Product Markup:', 'rich-reviews'); ?>
											<div style="font-size: 11px; font-weight: 400; margin-top: 8px; font-style: italic;"><?php _e('Use the shortcode', 'rich-reviews'); ?> <br/><code style="font-style: normal; font-size: 11px;">[RR_SHOPPER_APPROVED get="product-schema" ids="productId"]</code> <?php echo __('to output this markup. Note: Replace productId with the catalog id of the product for which you would like to output the schema for. To output multiple product\'s schema use comma separated ids.', 'rich-reviews') . '<br/>' . __('(ex. ids="productId1,productId2,productId3")', 'rich-reviews'); ?></div>
										</label>
									</div>
									<div class="input-container" style="width:66%;float:right;">
										<code name="Shopper Approved" rows="10" cols="100" style="overflow:scroll;width:100%;float:left;" >
										<?php if(isset($options['product_markup']) && !empty($options['product_markup'])) { echo htmlspecialchars(array_shift($options['product_markup']));} ?>
										</code>
									</div>
									<div class="clear input-break"></div>
									<br />
								<?php
							}
						?>
						<div class="label-container" style="width:30%;float:left;">
							<label for="last_update" style="float:right;">
								<?php _e('Last Updated:', 'rich-reviews'); ?>
							</label>
						</div>
						<div class="input-container" style="width:66%;float:right;">
							<input type="text" name="last_update" disabled class="" style="width:100%;float:left;" <?php if($options['last_update'] != null) { echo 'value="' . $options['last_update'] . '"';} ?>/>
						</div>
						<div class="clear input-break"></div>
						<br/>
						<div class="label-container" style="width:30%;float:left;">
							<input type="submit" form="shopAppAdmin" class="button" id="force-update" value="<?php _e('Manual Update', 'rich-reviews'); ?>"/>
						</div>
						<div class="clear"></div>
					</div>
					<div class="options-section">
						<h2><?php _e('Shopper Approved Shortcode Options', 'rich-reviews'); ?></h2>
						<hr>
						<?php if(!isset($options['site_code']) || $options['site_code'] ==  '') {
							?>
								<h4><?php _e('To Enable Link Shortcode Options, please copy the "Link to Full Survey" from the Merchant Reviews Survey Options tab of Shopper Approved and enter it below.', 'rich-reviews'); ?></h4>
								<form name="site-code-grab" method="post">
									<input type="hidden" name="grabbing-site-code" value="roger" />
									<input name="full-link-drop" style="width:80%;margin:auto;" />
									<input type="submit" class="button rr-right" value="<?php _e('Submit Link', 'rich-reviews'); ?>" />
								</form>
							<?php
						}
						?> <form name="shopper-approved-shortcode-options" method="post">
								<input type="hidden" name="napolean" value="complex" />
						<?php
						if (isset($options['site_code']) && $options['site_code'] !=  '') { ?>


								<div class="link-options">
									<div class="merchant-options">
										<h3>Merchant Review Link</h3>
										<div class="label-container" style="width:30%;float:left;">
											<label for="reviews-link-shortcode" style="float:right;">
												<?php _e('Output Link to Full Review', 'rich-reviews'); ?>:
											</label>
										</div>
										<div class="input-container" style="width:66%;float:right;">
											<span class="" style="width:100%;float:left;">
												[RR_SHOPPER_APPROVED get="merchant-link"]
											</span>
										</div>
										<div class="clear input-break"></div>
										<br/>
										<div class="label-container" style="width:30%;float:left;">
											<label for="merchant_link_text" style="float:right;">
												<?php _e('Link Text', 'rich-reviews'); ?>:
											</label>
										</div>
										<div class="input-container" style="width:66%;float:right;">
											<input type="text" name="merchant_link_text" class="" style="width:100%;float:left;" <?php if($options['merchant_link_text'] != null) { echo 'value="' . $options['merchant_link_text'] . '"';} ?>/>
										</div>
										<div class="clear input-break"></div>
										<br />

										<div class="label-container" style="width:30%;float:left;">
											<label for="merchant_link_element_class" style="float:right;">
												<?php _e('Link Element Class', 'rich-reviews'); ?>:
											</label>
										</div>
										<div class="input-container" style="width:66%;float:right;">
											<input type="text" name="merchant_link_element_class" class="" style="width:100%;float:left;" <?php if($options['merchant_link_element_class'] != null) { echo 'value="' . $options['merchant_link_element_class'] . '"';} ?>/>
										</div>
										<div class="clear input-break"></div>
									</div>
									<div class="product-options">
										<h3>Product Review Link</h3>
										<div class="label-container" style="width:30%;float:left;">
											<label for="reviews-link-shortcode" style="float:right;">
												<?php _e('Output Link to Full Review', 'rich-reviews'); ?>:
											</label>
										</div>
										<div class="input-container" style="width:66%;float:right;">
											<span class="" style="width:100%;float:left;">
												[RR_SHOPPER_APPROVED get="product-link"]
											</span>
										</div>
										<div class="clear input-break"></div>
										<br/>
										<div class="label-container" style="width:30%;float:left;">
											<label for="product_link_text" style="float:right;">
												<?php _e('Link Text', 'rich-reviews'); ?>:
											</label>
										</div>
										<div class="input-container" style="width:66%;float:right;">
											<input type="text" name="product_link_text" class="" style="width:100%;float:left;" <?php if($options['product_link_text'] != null) { echo 'value="' . $options['product_link_text'] . '"';} ?>/>
										</div>
										<div class="clear input-break"></div>
										<br />

										<div class="label-container" style="width:30%;float:left;">
											<label for="product_link_element_class" style="float:right;">
												<?php _e('Link Element Class', 'rich-reviews'); ?>:
											</label>
										</div>
										<div class="input-container" style="width:66%;float:right;">
											<input type="text" name="product_link_element_class" class="" style="width:100%;float:left;" <?php if($options['product_link_element_class'] != null) { echo 'value="' . $options['product_link_element_class'] . '"';} ?>/>
										</div>
										<div class="clear input-break"></div>
									</div>
								</div>
								<?php
								}
								?>

							<br />
							<div class="trigger-options">
								<h3>Review Trigger</h3>
								<div class="label-container" style="width:30%;float:left;">
									<label for="reviews-trigger-shortcode" style="float:right;">
										<?php _e('Trigger Review Form', 'rich-reviews'); ?>:
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<span class="" style="width:100%;float:left;">
										[RR_SHOPPER_APPROVED get="trigger"]
									</span>
									<br />
									<br />
									<span style="font-size:10px;"><?php echo __('This shortcode should be placed on the Thank You page of your checkout process. If you opt to collect product reviews, the product ids involved in the order will need to be populated, comma separated if more than one, to the ids parameter of the shortcode. This will require specific integration with your chosen e-commerce platform. E-commerce automation support will be added an update in the near future to simplifiy this process.', 'rich-reviews') . '<br /><br />' . __('(ex. [RR_SHOPPER_APPROVED get="trigger" ids="productId1,productId2,productId3"])', 'rich-reviews'); ?></span>
								</div>
								<div class="clear input-break"></div>
								<br/>
								<div class="label-container" style="width:30%;float:left;">
									<label for="review_header_override" style="float:right;text-align:right;">
										<?php _e('Override Review Header', 'rich-reviews'); ?>:
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<input type="Text" name="review_header_override" class="" style="width: 100%;" value="<?php echo $options['review_header_override']; ?>"/>
								</div>
								<br/>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="inline_review_form" style="float:right;text-align:right;">
										<?php _e('Display Review Inline', 'rich-reviews'); ?>:<br/>
										<span style="font-size:10px;">(<?php _e('default shows form in modal, note this option overrides the option set on Shopper Approved)', 'rich-reviews'); ?></span>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<input type="checkbox" name="inline_review_form" class="" <?php if($options['inline_review_form']) { echo 'checked';} ?>/>
								</div>
								<br />
								<div class="clear input-break"></div>
							</div>
							<br/>
							<input type="submit" value="<?php _e('Update Shortcode Options', 'rich-reviews'); ?>" class="button" />
						</form>
					</div>
					<div class="options-section">
						<h2><?php _e('Widget Options', 'rich-reviews'); ?></h2>
						<hr>
						<p class="options-info">
							<?php echo __('The following options correpsond to the options provided in the Shopper Approved dashboard. These options are provided here for convenience. If using the [RR_SHOPPER_APPROVED get="merchant-widget"], these options will take precedence over their counter part set in your Shopper Approved dashboard. In addition if you would prefer to use the Rich Reviews display settings, specified in the ', 'rich-reviews') .  '<a href="' . admin_url() . '/admin.php?page=fp_admin_options_page' . '">' . __('default options tab', 'rich-reviews') .  '</a>, ' . __('select the Rich Reviews style override option below.'); ?>
						</p>
						<div class="widget-options-scetion functional-options">
							<h3>Widget Properties</h3>
							<form id="widgetOptions" method="post">
								<input type="hidden" name="widgetize-me" value="captain" />
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_display_orientation" style="float:right;">
										<?php _e('Display H/V:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<div class="two-wide-radio-container">
										<input type="radio" name="sa_display_orientation" class="" <?php if($options['sa_display_orientation'] == 'sa_vertical') {echo 'checked';} ?> value="sa_vertical" /><span class="radio-label"> <?php _e('Display Vertically', 'rich-reviews'); ?></span>
									</div>
									<div class="two-wide-radio-container">
										<input type="radio" name="sa_display_orientation" class="" <?php if($options['sa_display_orientation'] == 'sa_horizontal') {echo 'checked';} ?> value="sa_horizontal" /><span class="radio-label"> <?php  _e('Display Horizontally', 'rich-reviews'); ?></span>
									</div>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_reviews_rotation" style="float:right;">
										<?php _e('Auto Rotate:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<div class="radio-container">
										<input type="radio" name="sa_reviews_rotation" class="" <?php if ($options['sa_reviews_rotation'] == 'sa_rotate') { echo 'checked';} ?> value="sa_rotate" /><span class="radio-label"> <?php  _e('Rotate Reviews', 'rich-reviews'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_reviews_rotation" class="" <?php if ($options['sa_reviews_rotation'] == 'sa_static') { echo 'checked';} ?> value="sa_static" /><span class="radio-label"> <?php  _e('Keep static', 'rich-reviews'); ?></span>
									</div>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_rotation_rate" style="float:right;">
										<?php _e('Rotation Time:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<input type="number" min="5" max="99" name="sa_rotation_rate" class="" value="<?php echo $options['sa_rotation_rate']; ?>" /> <?php  _e('Seconds', 'rich-reviews'); ?>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_review_count" style="float:right;">
										<?php _e('Number of Reviews:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<input type="number" min="1" name="sa_review_count" class="" value="<?php echo $options['sa_review_count']; ?>"/>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_enable_links" style="float:right;">
										<?php _e('Link to Full Review:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<div class="radio-container">
										<input type="radio" name="sa_enable_links" class="" <?php if ($options['sa_enable_links'] == 'sa_showlinks') { echo 'checked';} ?> value="sa_showlinks" /><span class="radio-label"> <?php  _e('Show Links', 'rich-reviews'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_enable_links" class="" <?php if ($options['sa_enable_links'] == 'sa_hidelinks') { echo 'checked';} ?> value="sa_hidelinks" /><span class="radio-label"> <?php  _e('Hide Links', 'rich-reviews'); ?></span>
									</div>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_page_shade" style="float:right;">
										<?php _e('Parent Page Color:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<div class="radio-container">
										<input type="radio" name="sa_page_shade" class="" <?php if ($options['sa_page_shade'] == 'sa_parentLight') { echo 'checked';} ?> value="sa_parentLight" /><span class="radio-label"> <?php  _e('Light', 'rich-reviews'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_page_shade" class="" <?php if ($options['sa_page_shade'] == 'sa_parentDark') { echo 'checked';} ?> value="sa_parentDark" /><span class="radio-label"> <?php  _e('Dark', 'rich-reviews'); ?></span>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="widget-options-scetion display-options">
								<h3>Display Options</h3>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_rr_style_override" style="float:right;">
										<?php _e('Use Rich Reviews Override:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<input type="checkbox" name="sa_rr_style_override" class="" style="float:left;" <?php if ($options['sa_rr_style_override']){ echo 'checked'; }?>/>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_background_color" style="float:right;">
										<?php _e('Background Color:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container color-container" style="width:66%;float:right;">
									<input type="radio" name="sa_background_color" class="color-radio " style="float:left;" <?php if ($options['sa_background_color'] == 'sa_bgWhite') {echo 'checked'; } ?> value="sa_bgWhite" />
									<input type="radio" name="sa_background_color" class="color-radio " style="float:left;" <?php if ($options['sa_background_color'] == 'sa_bgGrey') {echo 'checked'; } ?> value="sa_bgGrey" />
									<input type="radio" name="sa_background_color" class="color-radio " style="float:left;" <?php if ($options['sa_background_color'] == 'sa_bgBlack') {echo 'checked'; } ?> value="sa_bgBlack" />
									<input type="radio" name="sa_background_color" class="color-radio " style="float:left;" <?php if ($options['sa_background_color'] == 'sa_bgBlue') {echo 'checked'; } ?> value="sa_bgBlue" />
									<input type="radio" name="sa_background_color" class="color-radio " style="float:left;" <?php if ($options['sa_background_color'] == 'sa_bgGold') {echo 'checked'; } ?> value="sa_bgGold" />
									<input type="radio" name="sa_background_color" class="color-radio " style="float:left;" <?php if ($options['sa_background_color'] == 'sa_bgGreen') {echo 'checked'; } ?> value="sa_bgGreen" />
									<input type="radio" name="sa_background_color" class="color-radio " style="float:left;" <?php if ($options['sa_background_color'] == 'sa_bgRed') {echo 'checked'; } ?> value="sa_bgRed" />
									<input type="radio" name="sa_background_color" class="inherit-color-radio " style="float:left;" <?php if ($options['sa_background_color'] == 'sa_bgInherit') {echo 'checked'; } ?> value="sa_bgInherit" />
								</div>
								<style>
									input[type="radio"].color-radio, input[type="radio"].inherit-color-radio {
										margin-right: 30px;
										position: relative;
									}
	 								input[type="radio"].color-radio:after {
									    position: absolute;
									    content: " ";
									    left: -50%;
									    border: 15px solid black;
									    top: -50%;
									    border-radius: 100%;
									}
									input[type="radio"].inherit-color-radio:after {
										position: absolute;
										left: 20px;
										top:3px;
										content: "Inherit";
									}
									input[type="radio"].inherit-color-radio:checked:after {
										font-size: 18px;
										font-weight: bold;
										margin-top: 0;
									}
									input[type="radio"].color-radio:checked:after {
										border: 20px solid black;
										margin-left: -5px;
										margin-top: -5px;
										content: " ";
										height:0;
										width:0;
									}
									input[value="sa_bgWhite"].color-radio:after, input[value="sa_bgWhite"].color-radio:checked:after, input[value="sa_borderWhite"].color-radio:after, input[value="sa_borderWhite"].color-radio:checked:after, input[value="sa_colorWhite"].color-radio:after, input[value="sa_colorWhite"].color-radio:checked:after {
										border-color: #ffffff;
									}
									input[value="sa_bgGrey"].color-radio:after, input[value="sa_bgGrey"].color-radio:checked:after, input[value="sa_borderGrey"].color-radio:after, input[value="sa_borderGrey"].color-radio:checked:after {
										border-color: #59525c;
									}
									input[value="sa_bgBlack"].color-radio:after, input[value="sa_bgBlack"].color-radio:checked:after, input[value="sa_borderBlack"].color-radio:after, input[value="sa_borderBlack"].color-radio:checked:after, input[value="sa_colorBlack"].color-radio:after, input[value="sa_colorBlack"].color-radio:checked:after {
										border-color: #0c0d0d;
									}
									input[value="sa_bgBlue"].color-radio:after, input[value="sa_bgBlue"].color-radio:checked:after {
										border-color: #017a9c;
									}
									input[value="sa_bgGold"].color-radio:after, input[value="sa_bgGold"].color-radio:checked:after {
										border-color: #c27805;
									}
									input[value="sa_bgGreen"].color-radio:after, input[value="sa_bgGreen"].color-radio:checked:after {
										border-color: #28882f;
									}
									input[value="sa_bgRed"].color-radio:after, input[value="sa_bgRed"].color-radio:checked:after {
										border-color: #c10808;
									}
									.color-container {
										padding: 18px 0;
									}
									.input-container {
										margin-bottom: 13px;
									}
									.radio-container {
										width:32%;
										float: left;
										margin-right: 2%;
										padding: 0 0 13px 0;
									}
									.radio-container:nth-of-type(3n) {
										margin-right: 0;
									}
									.two-wide-radio-container {
										width: 49%;
										float: left;
										padding: 0 0 13px 0;

									}
									.two-wide-radio-container:first-of-type() {
										margin-right: 2%;
									}

									.radio-container > p {
										font-size: 10px;
										font-style: italic;
									}
									.radio-container > input[type="radio"]:checked + .radio-label, .two-wide-radio-container > input[type="radio"]:checked + .radio-label {
										font-size: 14px;
										font-weight: bold;
									}
									input[type=number] {
									    height: 30px;
									    line-height: 30px;
									    font-size: 18px;
									    padding: 0 8px;
									    background: none;
									    box-shadow: none;
									    border: none;
									    width: 70px;
									}
									input[type=number]::-webkit-inner-spin-button {
									    -webkit-appearance: none;
									    cursor:pointer;
									    display:block;
									    color: #333;
									    text-align:center;
									    position:relative;
									    background: #eee url('http://i.stack.imgur.com/YYySO.png') no-repeat 50% 50%;
									    width: 14px;
									    height: 14px;
									    padding: 4px;
									    right: 4px;
									    border-radius: 28px;
									    opacity: 1;
									}
									input[type=number]:hover::-webkit-inner-spin-button {

									}
								</style>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_text_color" style="float:right;">
										<?php _e('Text Color:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container color-container" style="width:66%;float:right;">
									<input type="radio" name="sa_text_color" class="color-radio " style="float:left;" <?php if ($options['sa_text_color'] == 'sa_colorWhite' ) { echo 'checked'; } ?> value="sa_colorWhite" />
									<input type="radio" name="sa_text_color" class="color-radio " style="float:left;" <?php if ($options['sa_text_color'] == 'sa_colorBlack' ) { echo 'checked'; } ?> value="sa_colorBlack" />
									<input type="radio" name="sa_text_color" class="inherit-color-radio " style="float:left;" <?php if ($options['sa_text_color'] == 'sa_colorInherit' ) { echo 'checked'; } ?> value="sa_colorInherit" />
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_border_color" style="float:right;">
										<?php _e('Border Color:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container color-container" style="width:66%;float:right;">
									<input type="radio" name="sa_border_color" class="color-radio " style="float:left;" <? if ($options['sa_border_color'] == 'sa_borderWhite' ) { echo 'checked'; } ?> value="sa_borderWhite" />
									<input type="radio" name="sa_border_color" class="color-radio " style="float:left;" <? if ($options['sa_border_color'] == 'sa_borderGrey' ) { echo 'checked'; } ?> value="sa_borderGrey" />
									<input type="radio" name="sa_border_color" class="color-radio " style="float:left;" <? if ($options['sa_border_color'] == 'sa_borderBlack' ) { echo 'checked'; } ?> value="sa_borderBlack" />
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_border_style" style="float:right;">
										<?php _e('Border Style:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<div class="radio-container">
										<input type="radio" name="sa_border_style" class="" <?php if ($options['sa_border_style'] == 'sa_rounded') { echo 'checked'; } ?> value="sa_rounded" /><span class="radio-label"> <?php  _e('Round', 'rich-reviews'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_border_style" class="" <?php if ($options['sa_border_style'] == 'sa_topborder') { echo 'checked'; } ?> value="sa_topborder" /><span class="radio-label"> <?php  _e('Top', 'rich-reviews'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_border_style" class="" <?php if ($options['sa_border_style'] == 'sa_noborder') { echo 'checked'; } ?> value="sa_noborder" /><span class="radio-label"> <?php  _e('None', 'rich-reviews'); ?></span>
									</div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_date_format" style="float:right;">
										<?php _e('Review Date:', 'rich-reviews'); ?>
									</label>
								</div>

								<div class="input-container" style="width:66%;float:right;">
									<div class="radio-container">
										<input type="radio" name="sa_date_format" class="" <?php if ($options['sa_date_format'] == 'sa_jMY') { echo 'checked'; } ?> value="sa_jMY" /><span class="radio-label"> <?php echo date('j M Y'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_date_format" class="" <?php if ($options['sa_date_format'] == 'sa_mdY') { echo 'checked'; } ?> value="sa_mdY" /><span class="radio-label"> <?php echo date('m/d/Y'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_date_format" class="" <?php if ($options['sa_date_format'] == 'sa_dmY') { echo 'checked'; } ?> value="sa_dmY" /><span class="radio-label"> <?php echo date('d/m/Y'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_date_format" class="" <?php if ($options['sa_date_format'] == 'sa_FjY') { echo 'checked'; } ?> value="sa_FjY" /><span class="radio-label"> <?php echo date('F j, Y'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_date_format" class="" <?php if ($options['sa_date_format'] == 'sa_nodate') { echo 'checked'; } ?> value="sa_nodate" /><span class="radio-label"> <?php echo "No Date"; ?></span>
									</div>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_vertical_display" style="float:right;">
										<?php _e('Vertical Display Type:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<div class="radio-container">
										<input type="radio" name="sa_vertical_display" class="" <?php if ($options['sa_vertical_display'] == 'sa_fixed') { echo 'checked'; } ?> value="sa_fixed" /><span class="radio-label"> <?php  _e('Fixed', 'rich-reviews'); ?></span>
										<p class="vertical-displa-info">
											<?php  _e('The widgets will be a standard size', 'rich-reviews'); ?>
										</p>
									</div><div class="radio-container">
										<input type="radio" name="sa_vertical_display" class="" <?php if ($options['sa_vertical_display'] == 'sa_fill') { echo 'checked'; } ?> value="sa_fill" /><span class="radio-label"> <?php  _e('Fill', 'rich-reviews'); ?></span>
										<p class="vertical-displa-info">
											<?php  _e('The widgets will fill the vertical space. Note: the parent container must have a fixed size > 250px for each review.', 'rich-reviews'); ?>
										</p>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_vertical_display" class="" <?php if ($options['sa_vertical_display'] == 'sa_flex') { echo 'checked'; } ?> value="sa_flex" /><span class="radio-label"> <?php  _e('Flex', 'rich-reviews'); ?></span>
										<p class="vertical-displa-info">
											<?php  _e('The widgets will be individually sized according to the length of the review.', 'rich-reviews'); ?>
										</p>
									</div>
								</div>
								<div class="clear"></div>
								<div class="label-container" style="width:30%;float:left;">
									<label for="sa_widget_size" style="float:right;">
										<?php _e('Size:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:66%;float:right;">
									<div class="radio-container">
										<input type="radio" name="sa_widget_size" class="" <?php if ($options['sa_widget_size'] == 'sa_large') { echo 'checked'; } ?> value="sa_large" /><span class="radio-label"> <?php  _e('Large', 'rich-reviews'); ?></span>
									</div>
									<div class="radio-container">
										<input type="radio" name="sa_widget_size" class="" <?php if ($options['sa_widget_size'] == 'sa_narrow') { echo 'checked'; } ?> value="sa_narrow" /><span class="radio-label"> <?php _e('Small', 'rich-reviews'); ?></span>
									</div>
								</div>
								<div class="clear"></div>
								<input type="submit" class="button button-primary" value="update Widget Options" />
							</form>
						</div>
					</div>
					<div class="options-section">
						<h2><?php _e('Pull Reviews', 'rich-reviews'); ?></h2>
						<hr>
						<div class="label-container" style="width:30%;float:left;">
							<label for="reviews_last_pulled" style="float:right;">
								<?php _e('Last Pulled:', 'rich-reviews'); ?>
							</label>
						</div>
						<div class="input-container" style="width:66%;float:right;">
							<input type="text" name="reviews_last_pulled" disabled class="" style="width:100%;float:left;" <?php if($options['reviews_last_pulled'] != null) { echo 'value="' . $options['reviews_last_pulled'] . '"';} ?> disabled />
						</div>
						<div class="clear"></div>
						<br />
						<?php
						if(isset($options['reviews_pulled_count']) && $options['reviews_last_pulled'] != 'not yet pulled') {
							?>
							<div class="label-container" style="width:30%;float:left;">
								<label for="unpulled_reviews" style="float:right;">
									<?php _e('New Unpulled Reviews:', 'rich-reviews'); ?>
								</label>
							</div>
							<div class="input-container" style="width:66%;float:right;">
								<input type="text" name="reviews_last_pulled" disabled class="" style="width:100%;float:left;" <?php if($unpulled_reviews != null) { echo 'value="' . $unpulled_reviews . '"';} ?> disabled />
							</div>
							<br />
							<div class="clear"></div>
							<?php
						}
						?>
						<form name="pullReviewsButton" method="post" action="" >
							<input type="hidden" name="Whoop" value="There it is" />
							<div class="input-container " style="width:33%;float:left;">
								<div style="width: 100%;float:left;">
									<input type="submit" class="button rr-left" value="<?php _e('Pull Reviews', 'rich-reviews'); ?>" />
								</div>
							</div>
						</form>
						<div class="clear"></div>
					</div>
					<div class="options-section">
						<h2><?php _e('Export Current Reviews', 'rich-reviews'); ?></h2>
						<hr>
						<p>
							<?php echo __('Press the button below to download a csv file of all of the reviews currently in your Rich Reviews. This way you can send these reviews to Shopper Approved and have them imported to your Shopper Approved Merchant or Product rating. Further instruction on how to do this can be found', 'rich-reviews') . ' <a href="http://hubs.ly/H029MF00" target=_blank> ' . __('here', 'rich-reviews') . '</a>. ' . __('(note: CSV file uses pipe, " | ", as a delimeter.)'); ?>
						</p>
						<a href="<?php echo $this->parent->plugin_url; ?>richreviews-download-script.php?download=csv" class="button rr-left"><?php _e('Download Reviews', 'rich-reviews'); ?></a>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
					<div class="options-section">
						<h2><?php _e('Integrate Product Reviews', 'rich-reviews'); ?></h2>
						<hr>
						<form id="productReviewsIntegration" action="<?php echo $this->parent->plugin_url; ?>richreviews-update-product-index.php" method="post">
							<input type="hidden" name="updating_product_catalog" value="updatingCatalog" />
							<p>
								<?php _e('Enter the slug for the post type under which your products are categorized, or select to use your Rich Reviews category structure (only one can be used for initialization). This will help to generate the needed xml file to communicate product listings and the associated review information to Shopper Approved (note: CSV file uses pipe, " | ", as a delimeter). After initial configuration, you can force an update by pressing the button below.'); ?>
							</p>
							<div class="initialize-options">
								<div class="label-container" style="width:40%;float:left;padding-top:3px;">
									<label for="product_pt_slug" style="float:right;margin-right:5px;">
										<?php _e('Product post type slug:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:60%;float:right;">
									<input type="text" name="product_pt_slug" class="" style="width:100%;float:left;" <?php if(isset($options['product_pt_slug']) && $options['product_pt_slug'] != '') { echo 'value="' . $options['product_pt_slug'] . '"';} ?>/>
								</div>
								<div class="clear"></div>
								<br />
								<h3 style="text-align:center;"><span class="line"></span>  OR  <span class="line"></span></h3>
								<br/>
								<div class="label-container" style="width:40%;float:left;">
									<label for="use_rr_categories" style="float:right;margin-right:5px;">
										<?php _e('Use Rich Reviews Categories:', 'rich-reviews'); ?>
									</label>
								</div>
								<div class="input-container" style="width:60%;float:right;">
									<input type="checkbox" name="use_rr_categories" class="" <?php if(isset($options['use_rr_categories']) && $options['use_rr_categories'] == 'checked') { echo 'checked';} ?>/>
								</div>
								<script type="text/javascript">
									jQuery(function() {
										jQuery('input[name="product_pt_slug"]').keyup(function() {
											if (jQuery(this).val() != '') {
												jQuery('input[name="use_rr_categories"]').prop("checked", false);
											}
										});
										jQuery('input[name="use_rr_categories"]').change(function() {
											if (jQuery(this).val() == 'on') {
												jQuery('input[name="product_pt_slug"]').val('');
											}
										});
									});
								</script>
								<div class="clear"></div>
							</div>
							<p>
								<?php _e('Update and Download your Shopper Approved product record. Pleas double check that the file is output and formatted to the requirements described by Shopper Approved in the Product Reviews "Integration Options" tab. For more information on this process, refer to you Shopper Approved Dashboard ', 'rich-reviews'); ?> <a href="http://hubs.ly/H02f4C40" target=_blank><?php _e('here', 'rich-reviews'); ?></a>.
							</p>
							<input type="submit" class="button" value="<?php _e('Update Product Index', 'rich-reviews'); ?>" form="productReviewsIntegration" />
							<?php if (isset($options['product_feed_url']) && $options['product_feed_url'] != '') {
								?>
									<a href="<?php echo $this->parent->plugin_url; ?>lib/rrShopApp/rrShopAppProductFeed.csv" class="button rr-left"><?php _e('Download Product Index', 'rich-reviews'); ?></a>
								<?php
							}
							?>
							<div class="clear"></div>
						</form>
						<br/>
						<div class="label-container" style="width:25%;float:left;padding-top:5px;">
							<label for="product_feed_url" >
								<?php _e('Product Feed URL', 'rich-reviews'); ?>:
							</label>
						</div>
						<div class="input-container" style="width:75%;float:right;">
							<input type="text" name="product_feed_url" class="rr-one" value="<?php if(isset($options['product_feed_url'])) { echo $options['product_feed_url'];} ?>" disabled/>
						</div>
						<div class="clear"></div>
						<br/>
						<?php
							if (isset($options['product_catalog_ids']) && !empty($options['product_catalog_ids'])) {
								?>
								<div class="rr-one">
									<h2><?php echo _e('Product Catalog Ids', 'rich-reviews'); ?></h2>
									<hr>
									<?php
										$count = 0;
										foreach($options['product_catalog_ids'] as $catalogged_id => $data) {
											$count++;
										?>
											<div class="single-product-info">
												<a href="<?php echo admin_url() . 'admin.php?page=edit_single_product_index&id=' . $catalogged_id; ?>"  class="product-edit-link rr-right"><i class="dashicon-edit"></i> <?php _e('Edit', 'rich-reviews'); ?></a>
												<summary>
												<h3>
													<?php _e('ID', 'rich-reviews'); ?>: <?php echo $catalogged_id; ?>
												</h3>
												<details>
													<span class="single-product-detail"><?php _e('Name', 'rich-reviews'); ?>: <?php if ($data['name'] != '') { echo '<span class="good">' . __('SET', 'rich-reviews') . '</span>';} else { echo '<span class="bad">' . __('UNSET', 'rich-reviews') . '</span>';} ?></span>
													<span class="single-product-detail"><?php _e('Description', 'rich-reviews'); ?>: <?php if ($data['description'] != '') { echo '<span class="good">' . __('SET', 'rich-reviews') . '</span>';} else { echo '<span class="bad">' . __('UNSET', 'rich-reviews') . '</span>';} ?></span>
													<span class="single-product-detail"><?php _e('Manufacturer', 'rich-reviews'); ?>: <?php if ($data['manufacturer'] != '') { echo '<span class="good">' . __('SET', 'rich-reviews') . '</span>';} else { echo '<span class="bad">' . __('UNSET', 'rich-reviews') . '</span>';} ?></span>
													<span class="single-product-detail"><?php _e('Product Url', 'rich-reviews'); ?>: <?php if ($data['product_url'] != '') { echo '<span class="good">' . __('SET', 'rich-reviews') . '</span>';} else { echo '<span class="bad">' . __('UNSET', 'rich-reviews') . '</span>';} ?></span>
													<span class="single-product-detail"><?php _e('Image Url', 'rich-reviews'); ?>: <?php if ($data['image_url'] != '') { echo '<span class="good">' . __('SET', 'rich-reviews') . '</span>';} else { echo '<span class="bad">' . __('UNSET', 'rich-reviews') . '</span>';} ?></span>
													<span class="single-product-detail"><?php _e('Manufacturer Part Number', 'rich-reviews'); ?>: <?php if ($data['mpn'] != '') { echo '<span class="good">' . __('SET', 'rich-reviews') . '</span>';} else { echo '<span class="bad">' . __('UNSET', 'rich-reviews') . '</span>';} ?></span>
												</details>
												</summary>
											</div>
										<?php
											if($count%3 == 0) {
												echo '<div class="clear"></div>';
											}
										}
									?>
									<div class="clear"></div>
									<br />
									<a href="<?php echo admin_url() . 'admin.php?page=edit_single_product_index&new=true'; ?>" class="button"><?php _e('Add New Product Listing', 'rich-reviews'); ?></a>
								</div>
								<?php
							}
						?>
					</div>
					<div class="clear"></div>
				</div>

			<?php	} else { ?>
				</form>
			</div>

			<div class="clear"></div>
		</div>
<?php	} ?>




