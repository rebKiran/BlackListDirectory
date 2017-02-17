<?php 

function wpcrown_wpcss_loaded() {

	// Return the lowest priority number from all the functions that hook into wp_head
	global $wp_filter;
	//$lowest_priority = max(array_keys($wp_filter['wp_head']));
 
	add_action('wp_head', 'wpcrown_wpcss_head', 10 + 1);
 
	$arr = $wp_filter['wp_head'];

}
add_action('wp_head', "wpcrown_wpcss_loaded");

function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        return implode(",", $rgb); // returns the rgb values separated by commas
        //return $rgb; // returns an array with the rgb values
    }
 
// wp_head callback functions
function wpcrown_wpcss_head() {

	global $redux_demo; 
	$wpcrown_primary_color = $redux_demo['color-primary'];
	$wpcrown_main_color = $redux_demo['color-main'];
	$wpcrown_main_color_hover = $redux_demo['color-main-hover'];
	$wpcrown_button_color_main = $redux_demo['button-color-main'];
	$wpcrown_button_color_main_hover = $redux_demo['button-color-main-hover'];
	$body_color = $redux_demo['body-font']['color'];
	$footer_color = $redux_demo['fooer-color'];
	$measure_system = $redux_demo['measure-system'];

	echo "<style type=\"text/css\">";

	// Main Color
	if(!empty($measure_system)) {

		if($measure_system == "1") {
			
			echo ".range-pin { background: transparent url(";
			echo get_template_directory_uri();
			echo "/images/range-mi.png) no-repeat top left; } ";

		}

	}
	?>
	.ad-detail-half-box .ad-detail-info {
		text-shadow: 0 0 0 <?php echo $body_color; ?>
	}
	<?php
	// Main Color
	if(!empty($wpcrown_primary_color)) {
		echo "#submit-plan, .view-pricing-plans .product-wrapper .price, #top-menu-block, #advanced-search-widget-version2, #ads-homepage ul.tabs li a:hover, #ads-homepage ul.tabs li a.current { background-color: ";
		echo $wpcrown_primary_color;
		echo "; } ";
		
		echo "h3,.detail-cat a, span.ad-page-price,.ad-title h2 ,.main-title, .category-box h4 { color: ";
		echo $wpcrown_primary_color;
		echo "; } ";
		
		echo ".ad-hover-content,.marker-info { background:rgba( ";
		echo hex2rgb($wpcrown_primary_color);
		echo ",.9 )} ";
		
		echo ".post-title{ background:rgba( ";
		echo hex2rgb($wpcrown_primary_color);
		echo ",.5 )} ";
		
		echo ".category-icon-box { background-color: ";		
		echo $wpcrown_primary_color;
		echo " !important; } ";
	
	}
	
	// footer Color
	if(!empty($footer_color)) {
		echo ".socket, footer{ background-color: ";
		echo $footer_color;
		echo "; } ";
	
	}
	// Main Color
	if(!empty($wpcrown_main_color)) {
			
		echo "a, #navbar .main_menu .menu li.current_page_item .sub-menu a:hover, #navbar .main_menu .menu li.current_page_item .children a:hover, #navbar .main_menu .menu li.current-menu-item  .children a:hover, .main_menu ul li ul.children li a:hover, .main_menu ul li ul.sub-menu li a:hover, .main_menu ul li ul.children li.current_page_item a, .main_menu ul li ul.children li.current-menu-item a, .main_menu .menu li.current_page_item .sub-menu a:hover, .main_menu .menu li.current-menu-item  .sub-menu a:hover, .main_menu .menu li.current_page_item .children a:hover, .main_menu .menu li.current-menu-item  .children a:hover, .geo-location-button .on .fa, .geo-location-button .fa:hover, ul.custom-tabs li a.current, h4.trigger:hover, h4.trigger.active:hover, h4.trigger.active,#navbar .main_menu .menu li .sub-menu li.current_page_item a, #navbar .main_menu .menu li .children li.current_page_item a, #navbar .main_menu .menu li .children li.current_page_item a:hover, #navbar .main_menu .menu li .children li .current-menu-item a:hover { color: ";
		echo $wpcrown_main_color;
		echo "; } ";

		echo ".ui-slider-horizontal .ui-slider-range-min, .quicktabs-tabs .grid-feat-ad-style a:hover, .quicktabs-tabs .list-feat-ad-style a:hover, .quicktabs-tabs .grid-feat-ad-style a.current, .quicktabs-tabs .list-feat-ad-style a.current, i.blogI { background-color: ";
		echo $wpcrown_main_color;
		echo "; } ";	

		echo "#contact-form #contactName:focus, #contact-form #author:focus, #contact-form #email:focus, #contact-form #url:focus, #contact-form #subject:focus, #contact-form #commentsText:focus, #contact-form #humanTest:focus { border: 1px solid ";
		echo $wpcrown_main_color;
		echo "; } ";

	}

	// Main Color Hover
	if(!empty($wpcrown_main_color_hover)) {
			
		echo ".widget-ad-list-content-title a,footer .jw-twitter li a,.view-pricing-plans .product-wrapper:hover .product-title h4,.publish-ad-button.login-page p a, .category-box:hover h4 , a:hover, a:active, .main_menu ul li:hover > a, .main_menu .menu li.current_page_item a, .main_menu .menu li.current-menu-item a { color: ";
		echo $wpcrown_main_color_hover;
		echo " ; } ";

		echo ".tagcloud a:hover,#register-login-block-top .login a:hover, .view-pricing-plans .product-wrapper:hover .price,.view-pricing-plans .product-wrapper:hover #submit-plan, .pagination span.current,.pagination a:hover, .ad-hover-content .category-icon-box, .category-box:hover .category-icon-box , #carousel-prev:hover,#carousel-next:hover, #advanced-search-widget-version2 .views-exposed-widget .btn-primary:hover,.top-social-icons a:hover,i.blogI:hover  { background-color: ";
		echo $wpcrown_main_color_hover;
		echo " !important; } ";
		
		echo ".cat-widget-content ul li:hover .category-icon-box{ background-color: ";
		echo $wpcrown_main_color_hover;
		echo " !important; } ";
		
		echo ".cat-widget-content ul li:hover a{color: ";
		echo $wpcrown_main_color_hover;
		echo " !important; } ";
		
		echo "#register-login-block-top .login a:hover,.pagination span.current,.pagination a:hover,.category-box:hover, #carousel-prev:hover,#carousel-next:hover, #advanced-search-widget-version2 .views-exposed-widget .btn-primary:hover{ border-color:";
		echo $wpcrown_main_color_hover;
		echo "; } ";
		// !Call Out Button
		echo ".callout-inner h4 span,section#locations .location a:hover,section#locations .location a:hover i,.ad-title a:hover,.top-email,.custom-cat-widget .custom-widget .cat-widget-content ul li:hover,footer .block-content .menu-footer-container ul li a:hover, #register-login-block-top li.first a:hover,body.category .custom-widget .cat-widget-content li:hover { color: ";
		echo $wpcrown_main_color_hover;
		echo " !important; } ";

		echo ".callout .callbutton:hover .callbutton-inner, .callbutton:hover .callbutton-inner, .btn-primary:hover{ background-color: ";
		echo $wpcrown_main_color_hover;
		echo " !important; } ";
		
		echo ".readbutton .readbutton-inner:hover{ background-color: ";
		echo $wpcrown_main_color_hover;
		echo " !important; } ";
		
		echo ".readbutton .readbutton-inner{ background-color: ";
		echo $wpcrown_primary_color;
		echo " !important; } ";
		
		echo ".callout .callbutton-inner{ background-color: ";
		echo $wpcrown_primary_color;
		echo " !important; } ";
		
		echo ".callout h4, .ad-title a { color: ";
		echo $wpcrown_primary_color;
		echo " !important; } ";
		
		echo ".downcolor { color: ";
		echo $wpcrown_primary_color;
		echo " !important; } ";
		
		echo ".main_menu ul li ul li:hover a { color: ";
		echo $wpcrown_main_color_hover;
		echo " !important; } ";
		
		
		// !Call Out Button
		
		echo ".category-icon-box:hover { background-color: ";		
		echo $wpcrown_main_color_hover;
		echo " !important; } ";
		
		echo ".browse-favourite i,.browse-favourite,.fav-form:hover i.favorite-i,.follow-btn input[name=favorite]:hover{ color:";
		echo '#fff';
		echo "!important; } ";
		
		echo ".woocommerce .summary .price ins,.woocommerce .summary .price del, .fav-form i.favorite-i:hover,.fav-form i.favorite-i,.follow-btn input[name=favorite]{ color:";
		echo $wpcrown_main_color_hover;
		echo "!important; } ";
		
		echo ".woocommerce ul.products li.product a:hover img, .woocommerce-page ul.products li.product a:hover img, .woocommerce #content div.product div.images a:hover img, .woocommerce div.product div.images a:hover img, .woocommerce-page #content div.product div.images a:hover img, .woocommerce-page div.product div.images a:hover img { border: 2px solid ";
		echo $wpcrown_main_color_hover;
		echo "; } ";

	}

	// Button Color
	if(!empty($wpcrown_button_color_main)) {
			
		echo ".woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,.woocommerce #content nav.woocommerce-pagination ul li a:focus, .woocommerce #content nav.woocommerce-pagination ul li a:hover, .woocommerce #content nav.woocommerce-pagination ul li span.current, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce-page #content nav.woocommerce-pagination ul li a:focus, .woocommerce-page #content nav.woocommerce-pagination ul li a:hover, .woocommerce-page #content nav.woocommerce-pagination ul li span.current, .woocommerce-page nav.woocommerce-pagination ul li a:focus, .woocommerce-page nav.woocommerce-pagination ul li a:hover, .woocommerce-page nav.woocommerce-pagination ul li span.current,.fav-form i.favorite-i:hover,.browse-favourite,.marker-info-link a,.button-ag,#new-post a.btn, input[type='submit'], .woocommerce span.onsale, .woocommerce-page span.onsale, .products li a.button, .woocommerce div.product form.cart .button, .woocommerce-page div.product form.cart .button, .woocommerce #content div.product form.cart .button, .woocommerce-page #content div.product form.cart .button, .woocommerce button.button, .woocommerce-page button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce #respond input#submit, .woocommerce-page #respond input#submit, .woocommerce #content input.button, .woocommerce-page #content input.button, #top-cart .button, form.cart .button-alt, .woocommerce #content input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce-page #content input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .bbp-submit-wrapper button.button, .woocommerce .quantity .minus, .woocommerce-page .quantity .minus, .woocommerce #content .quantity .minus, .woocommerce-page #content .quantity .minus, .woocommerce .quantity .plus, .woocommerce-page .quantity .plus, .woocommerce #content .quantity .plus, .woocommerce-page #content .quantity .plus, form.cart .plus, form.cart .minus, .product-quantity .plus, .product-quantity .minus, .woocommerce .quantity input.qty, .woocommerce-page .quantity input.qty, .woocommerce #content .quantity input.qty, .woocommerce-page #content .quantity input.qty, form.cart input.qty, form.cart input.qty, .product-quantity input.qty, .pricing-plans a.btn, #edit-submit, #navbar .btn-navbar { background: ";
		echo $wpcrown_button_color_main;
		echo "!important; } ";

		echo " .woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price { color: ";
		echo $wpcrown_button_color_main;
		echo "; } ";

		echo ".ads-tags a:hosver { background: #ecf0f1!important; color: ";
		echo $wpcrown_button_color_main;
		echo "!important; } ";

	}

	// Button Color Hover
	if(!empty($wpcrown_button_color_main_hover)) {
			
		echo ".fav-form:hover i.favorite-i,h4.trigger.active,.marker-info-link a:hover,#new-post a.btn:hover, input[type='submit']:hover, .products li a.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce-page div.product form.cart .button:hover, .woocommerce #content div.product form.cart .button:hover, .woocommerce-page #content div.product form.cart .button:hover, .woocommerce button.button:hover, .woocommerce-page button.button:hover, .woocommerce input.button:hover, .woocommerce-page input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce-page #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page #content input.button:hover, #top-cart .button:hover, form.cart .button-alt:hover, .woocommerce #content input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .bbp-submit-wrapper button.button:hover, .woocommerce .quantity .minus:hover, .woocommerce-page .quantity .minus:hover, .woocommerce #content .quantity .minus:hover, .woocommerce-page #content .quantity .minus:hover,.woocommerce .quantity .plus:hover, .woocommerce-page .quantity .plus:hover, .woocommerce #content .quantity .plus:hover, .woocommerce-page #content .quantity .plus:hover, form.cart .plus:hover, form.cart .minus:hover, .product-quantity .plus:hover, .product-quantity .minus:hover, .pricing-plans a.btn:hover, #edit-submit:hover, #navbar .btn-navbar:hover { background: ";
		echo $wpcrown_button_color_main_hover;
		echo "!important; } ";
		
		echo ".fav-form i.favorite-i:hover{ background: ";
		echo '#fff';
		echo "!important; } ";

	}

	echo "</style>";

}