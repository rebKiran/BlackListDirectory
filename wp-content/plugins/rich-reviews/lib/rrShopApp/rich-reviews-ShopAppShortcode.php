<?php

function handle_shopper_approved($switch, $ids, $options, $path) {
	global $wpdb, $post;

	switch($switch) {
		case 'trigger':
			output_trigger_script($options, $ids);
			break;
		case 'merchant-link':
			output_merchant_review_link($options);
			break;
		case 'product-link':
			output_product_review_link($options);
			break;
		case 'merchant-schema':
			output_merchant_review_structured_snippet($options);
			break;
		case 'product-schema':
			if ($ids != '') {
				output_product_review_structured_snippet($options, $ids);
			}
		case 'merchant-widget':
			output_merchant_review_widget($options);
			break;
		default:
			break;
	}

}

function output_trigger_script($options, $ids) {
	$option = false;
	$sub = 'rate';
	if (isset($options['inline_review_form']) && $options['inline_review_form']) {
		$option = true;
	}

	if ($option) {
		$sub = 'inline';
		?>
			<div id="outer_shopper_approved"></div>
			<style>
				#sa_header_img {
					display: block;
				}
				<?php
					if (isset($options['review_header_override']) && $options['review_header_override'] != '') {
						?>
							#sa_header_text {
							    font-size: 0;
							}

							#sa_header_text:before {
							    font-size: 24px;
							    content: "<?php echo $options['review_header_override']; ?>";
							}
						<?php
					}
				?>
			</style>
		<?php
	}
	?>
		<!-- <pre> -->
			<script type="text/javascript">
				var sa_values = { "site":<?php echo $options['site_id']; ?> };
				function saLoadScript(src) {
					var js = window.document.createElement("script");
					js.src = src; js.type = "text/javascript";
					document.getElementsByTagName("head")[0].appendChild(js);
				}

				var d = new Date();
				if (d.getTime() - 172800000 > 1453483499000)
					saLoadScript("//www.shopperapproved.com/thankyou/<?php echo $sub; ?>/<?php echo $options['site_id']; ?>.js");
				else
					saLoadScript("//direct.shopperapproved.com/thankyou/<?php echo $sub; ?>/<?php echo $options['site_id']; ?>.js?d=" + d.getTime());
			</script>
		<!-- </pre> -->
	<?php
	if (isset($options['product_catalog_ids']) && !empty($options['product_catalog_ids']) &&  $ids != '') {
		$product_ids = explode(',', $ids);
		$ids_object = '';
		foreach($product_ids as $id) {
			if(isset($options['product_catalog_ids'][$id]) && is_array($options['product_catalog_ids'][$id]) && isset($options['product_catalog_ids'][$id]['name'])) {
				$ids_object .= '"' . $id . '":"' . $options['product_catalog_ids'][$id]['name']  . '", ';
			}
		}
		?>
		<script type="text/javascript">
			/* Include all products in the object below 'product id':'Product Name' */
			var sa_products = {<?php echo $ids_object; ?>};
		</script>
		<?php
	}
}

function output_merchant_review_link($options) {

	$link_text = 'Review Us';
	$link_class = '';
	if (isset($options['merchant_link_text']) && $options['merchant_link_text'] != '') {
		$link_text = $options['merchant_link_text'];
	}
	if (isset($options['merchant_link_class']) && $options['merchant_link_class'] != '') {
		$link_class = $options['merchant_link_class'];
	}
	if(isset($options['site_id']) && $options['site_id'] != '' && isset($options['site_token']) && $options['site_code'] != '') {
		$link_url = 'http://www.shopperapproved.com/surveys/full.php?id=' . $options['site_id'] . '&code=' . $options['site_code'];

		?>
			<a class="button <?php echo $link_class; ?>" href="<?php echo $link_url; ?>" ><?php echo $link_text; ?></a>
		<?php
	}
}

function output_product_review_link($options) {

	if(!isset($options['product_catalog_ids']) || empty($options['product_catalog_ids'])) {
		return;
	}
	$product_id_string = '';

	foreach($options['product_catalog_ids'] as $id => $data) {
		$product_id_string .= 'products[]=' . $id . '&';
	}
	$link_text = 'Review Our Product';
	$link_class = '';
	if (isset($options['product_link_text']) && $options['product_link_text'] != '') {
		$link_text = $options['product_link_text'];
	}
	if (isset($options['product_link_class']) && $options['product_link_class'] != '') {
		$link_class = $options['product_link_class'];
	}
	if(isset($options['site_id']) && $options['site_id'] != '' && isset($options['site_code']) && $options['site_code'] != '') {
		$link_url = 'http://www.shopperapproved.com/surveys/sale.php?' . $product_id_string . 'id=' . $options['site_id'] . '&code=' . $options['site_code'];

		?>
			<a class="button <?php echo $link_class; ?>" href="<?php echo $link_url; ?>" ><?php echo $link_text; ?></a>
		<?php
	}
}

function output_merchant_review_structured_snippet($options) {
	$tempRR = new RRShopApp();

	if(isset($tempRR->shopAppOptions['merchant_markup']) && $tempRR->shopAppOptions['merchant_markup'] != '') {
		echo $tempRR->display_handle('merchant');
		return;
	}
	return;
}

function output_product_review_structured_snippet($options, $ids = null) {
	$tempRR = new RRShopApp();

	if ($ids != null && $ids != '') {
		$id_array = explode(',', $ids);
		if ( !is_array($id_array) ) {
			return;
		}
	}

	if (isset($tempRR->shopAppOptions['product_markup'])) {
		foreach($id_array as $id) {
			echo $tempRR->display_handle('product', $id);
		}
	}
	return;
}

/**
 * Function to output the merchant review widget, contsructed from various bits of data that we have * collected from the User's shopper approved account.
 * @param Array $options ShopAppOpptions->get()
 * Outputs to buffer, returned by higher order function
 */
function output_merchant_review_widget($options) {
	// Note: build this from reverse engineered options, but for now,...

		if( !isset($options['site_name']) || !isset($options['site_id'])) {
			// We have big problems
			return;
		}

		$logoNames = array(
			'dark' => array(
				'large' => 'widgetfooter-darklogo.png',
				'small' => 'widgetfooter-darknarrow.png'
			),
			'light' => array(
				'large' => 'widgetfooter-whitelogo.png',
				'small' => 'widgetfooter-whitenarrow.png'
			),
		);
		if ($options['sa_page_shade'] == 'sa_parentLight') {
			$logoFile = $logoNames['dark'];
		} else {
			$logoFile = $logoNames['light'];
		}

		if ($options['sa_widget_size'] == 'sa_large') {
			$logoFile = $logoFile['large'];
		} else {
			$logoFile = $logoFile['small'];
		}

		$count_class = 'sa_count' . $options['sa_review_count'];
		if ($options['sa_reviews_rotation'] == 'sa_static') {
			$rotation_rate = 0;
		} else {
			$rotation_rate = $options['sa_rotation_rate'] * 1000;
		}


		$classes = array(
			$options['sa_reviews_rotation'],
			$options['sa_enable_links'],
			$options['sa_background_color'],
			$count_class,
			$options['sa_text_color'],
			$options['sa_border_color'],
			$options['sa_border_style'],
			$options['sa_date_format'],
			$options['sa_vertical_display'],
			$options['sa_widget_size'],
			$options['sa_display_orientation']
		);
		$classes = implode(' ', $classes);


	?>
		<div style="min-height: 100px; overflow: hidden;" class="shopperapproved_widget <?php echo $classes; ?>">
		</div>

		<script type="text/javascript">
			var sa_interval = <?php echo $rotation_rate; ?>;

			var sID = <?php echo $options['site_id']; ?>;

			function saLoadScript(src) {
				var js = window.document.createElement('script');
				 js.src = src;
				 js.type = 'text/javascript';
				 document.getElementsByTagName("head")[0].appendChild(js);
			}

			if (typeof(shopper_first) == 'undefined')
				saLoadScript('//www.shopperapproved.com/widgets/testimonial/3.0/' + sID + '.js');
			shopper_first = true;
		</script>

		<div style="text-align:right;">
			<a href="http://www.shopperapproved.com/reviews/<?php echo $options['site_name']; ?>/" target="_blank" rel="nofollow" class="sa_footer">
				<img class="sa_widget_footer" alt="" src="https://www.shopperapproved.com/widgets/<?php echo $logoFile; ?>" style="border: 0;">
			</a>
		</div>
	<?php
}
