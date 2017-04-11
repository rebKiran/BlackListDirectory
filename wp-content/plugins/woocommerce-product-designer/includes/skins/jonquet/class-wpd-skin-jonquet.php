<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-vpc-default-skin
 *
 * @author HL
 */
class WPD_Skin_Jonquet {

    public $editor;
    public $wpc_metas;

    public function __construct($editor_obj, $wpc_metas) {
        if ($editor_obj) {
            $this->editor = $editor_obj;
            $this->wpc_metas = $wpc_metas;
        }
    }

    public function display() {
        GLOBAL $wpd_settings;
        $wpd_settings=  apply_filters("wpd_global_settings", $wpd_settings);
        ob_start();

        $this->register_styles();
        $this->register_scripts();


        $text_options = get_proper_value($wpd_settings, 'wpc-texts-options', array());
        $shapes_options = get_proper_value($wpd_settings, 'wpc-shapes-options', array());
        $cliparts_options = get_proper_value($wpd_settings, 'wpc-images-options', array());
        $uploads_options = get_proper_value($wpd_settings, 'wpc-upload-options', array());
        $designs_options = get_proper_value($wpd_settings, 'wpc-designs-options', array());
        $wpc_social_networks = get_proper_value($wpd_settings, 'wpc_social_networks', array());
        $ui_options = get_proper_value($wpd_settings, 'wpc-ui-options', array());

        $facebook_app_id = get_proper_value($wpc_social_networks, 'wpc-facebook-app-id', "");
        $facebook_app_secret = get_proper_value($wpc_social_networks, 'wpc-facebook-app-secret', "");
        $instagram_app_id = get_proper_value($wpc_social_networks, 'wpc-instagram-app-id', "");
        $instagram_app_secret = get_proper_value($wpc_social_networks, 'wpc-instagram-app-secret', "");

        $text_tab_visible = get_proper_value($text_options, 'visible-tab', 'yes');
        $shape_tab_visible = get_proper_value($shapes_options, 'visible-tab', 'yes');
        $clipart_tab_visible = get_proper_value($cliparts_options, 'visible-tab', 'yes');
        $design_tab_visible = get_proper_value($designs_options, 'visible-tab', 'yes');
        $upload_tab_visible = get_proper_value($uploads_options, 'visible-tab', 'yes');

        $responsive_behavior=get_proper_value($wpd_settings["wpc-general-options"], 'responsive',array());

        if ($responsive_behavior==1) {
            $wpd_responsive_class="wpd-responsive-mode";
            $wpd_responsive_menu='<div class="wpc-editor-menu-box"><button class="wpc-editor-menu">'.__("MENU", "wpd").'<i class="fa fa-bars"></i></button></div>';
            
        }else{
            $wpd_responsive_class="";
            $wpd_responsive_menu='';
        }

        
        $ui_fields = wpd_get_ui_options_fields();
        
        foreach ($ui_fields as $key => $field) {
            $icon_field_name = "";
            
            if(isset($field["icon"]) || isset($field["bg-color"]) || isset($field["txt-color"])){
            if (isset($field["icon"])) {
                $icon_field_name = $key . "-icon";
                    wpd_generate_css_tab($ui_options, "$key", $icon_field_name, $key);
            }
                if (isset($field["bg-color"]) || isset($field["text-color"])) {
                     wpd_generate_css_tab($ui_options, "$key", "", $field["fied-name"]);
        }
            }
            else
                wpd_generate_css_tab($ui_options, "$key", $icon_field_name, $key);
            //wpd_generate_css_tab($ui_options, "$key-tools", $icon_field_name, $key);
        }
        $wpd_root_product = new WPD_Product($this->editor->root_item_id);
        wpd_generate_css_tab($ui_options, "wpd-toolsbox-title", "", "separators");
        wpd_generate_css_tab($ui_options, "wpd-cart-title", false, "separators");
        ?>

        <div class='wpc-container o-wrap <?php echo $wpd_responsive_class; ?>'>
         
            <div class="wpc-editor-wrap">
                <div class="wpd-responsive-toolbar-box">
                    <?php echo $wpd_responsive_menu;?>
                    <?php $this->get_toolbar(); ?>
                </div>
                
                <div class="wpc-editor-col  col xl-1-4">
                    <div class="wpd-separator-title" id="wpd-toolsbox-title"><?php _e("Tools Box", "wpd");?></div>
                    <div id="wpc-tools-box-container" class="Accordion" tabindex="0">
                        <?php
                        if (isset($this->wpc_metas['related-products']) && !empty($this->wpc_metas['related-products']) && ($wpd_root_product->product->product_type == "variation")) {
                            ?>
                            <div class="AccordionPanel" id="related-products-panel">
                                <div id="related-products" class="AccordionPanelTab"><?php _e("PICK A PRODUCT", "wpd"); ?></div>
                                <div class="AccordionPanelContent">
                                    <?php
                                    $related_attributes = $this->wpc_metas['related-products'];
                                    
                                    $usable_attributes = $wpd_root_product->extract_usable_attributes();
                                    $variation = wc_get_product($this->editor->item_id);
                                    $selected_attributes = $variation->get_variation_attributes();
                                    $to_search = array();
                                    $edit_mode_indic = "";

//                                    var_dump($usable_attributes);

                                    foreach ($usable_attributes as $attribute_name => $attribute_data) {
                                        $attribute_key = $attribute_data["key"];
                                        if (in_array($attribute_key, $related_attributes)) {
                                            echo $attribute_data["label"] . ":<br>";
                                            ?>
                                            <div class="wpd-rp-attributes-container">
                                                <?php
                                                foreach ($attribute_data["values"] as $attribute_value) {
                                                    $to_search = $selected_attributes;
                                                    if (is_object($attribute_value)) {//Taxonomy
                                                        $sanitized_value = $attribute_value->slug;
                                                        $label = $attribute_value->name;
                                                    } else {
                                                        $sanitized_value = sanitize_title($attribute_value);
                                                        $label = $attribute_value;
                                                    }
                                                    //$to_search[$attribute_key] = sanitize_title($sanitized_value); //$attribute_value;//sanitize_title($sanitized_value);
                                                    //The sanitize does not display the related products when the attributes have "." or ","
                                                    $to_search[$attribute_key] = $attribute_value; //$attribute_value;//sanitize_title($sanitized_value);

                                                    $variation_to_load = wpd_get_variation_from_attributes($to_search, $this->editor->root_item_id);

                                                    if (!$variation_to_load)
                                                        continue;
                                                    $variation = wc_get_product($variation_to_load);
                                                    $img_id = $variation->get_image_id();
                                                    if ($img_id)
                                                        $glimpse = "<img src='" . wp_get_attachment_url($img_id) . "'>";
                                                    else
                                                        $glimpse = $label;

                                                    $design_index = false;
                                                    if (isset($wp_query->query_vars["design_index"]))
                                                        $design_index = $wp_query->query_vars["design_index"];

                                                    $cart_item_key = false;
                                                    if (isset($wp_query->query_vars["edit"])) {
                                                        $cart_item_key = $wp_query->query_vars["edit"];
                                                        $edit_mode_indic = "cart-item-edit";
                                                    }

                                                    $order_item_id = false;
                                                    if (isset($wp_query->query_vars["oid"]))
                                                        $order_item_id = $wp_query->query_vars["oid"];

                                                    $tpl_id = false;
                                                    if (isset($wp_query->query_vars["tpl"]))
                                                        $tpl_id = $wp_query->query_vars["tpl"];

                                                    $wpd_product = new WPD_Product($variation_to_load);
                                                    $design_url = $wpd_product->get_design_url($design_index, $cart_item_key, $order_item_id, $tpl_id);
                                                    $selected_class = ($variation_to_load == $this->editor->item_id) ? "selected" : "";

                                                    $wpd_variation_to_load = new WPD_Product($variation_to_load);
                                                    ?>
                                                    <a class="wpd-rp-attribute <?php echo $selected_class . " " . $edit_mode_indic; ?>" href="<?php echo $design_url; ?>" data-tooltip-title="<?php echo $label; ?>" data-desc="<?php echo $wpd_variation_to_load->get_related_product_desc(); ?>"><?php echo $glimpse; ?></a>
                                                    <?php
                                                }
                                                ?>
                                                <div id="wpd-rp-desc">
                                                    <?php echo $this->editor->wpd_product->get_related_product_desc(); ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        $colors_options = $wpd_settings['wpc-colors-options'];
                        if ($text_tab_visible == "yes") {
                            ?>
                            <div class="AccordionPanel" id="text-panel">
                                <div id="text" class="AccordionPanelTab"><?php _e("TEXT", "wpd"); ?></div>
                                <div class="AccordionPanelContent">
            <?php $this->get_text_tools($text_options, $colors_options); ?>
                                </div>
                            </div>
                            <?php
                        }
                        if ($shape_tab_visible == "yes") {
                            ?>
                            <div class="AccordionPanel" id="shapes-panel">
                                <div id="shapes" class="AccordionPanelTab"><?php _e("SHAPES", "wpd"); ?></div>
                                <div class="AccordionPanelContent">
            <?php $this->get_shapes_tools($shapes_options, $colors_options); ?>
                                </div>
                            </div>
                            <?php
                        }
                        if ($upload_tab_visible == "yes") {
//Create a conflict for admin post page so we disable it
//                            if (!is_admin()) {
                            ?>
                            <div class="AccordionPanel" id="uploads-panel">
                                <div id="uploads" class="AccordionPanelTab"><?php _e("UPLOADS", "wpd"); ?></div>
                                <div class="AccordionPanelContent">
            <?php $this->get_uploads_tools($uploads_options); ?>                                 
                                </div>
                            </div>
                            <?php
//                                    }
                        }
                        if ($clipart_tab_visible == "yes") {
                            ?>
                            <div class="AccordionPanel" id="cliparts-panel">
                                <div id="cliparts" class="AccordionPanelTab"><?php _e("CLIPARTS", "wpd"); ?></div>
                                <div class="AccordionPanelContent" id="wpd-cliparts-tools-fake-container">
                                    <?php
                                    wpd_generate_css_tab($ui_options, "wpd-cliparts-opener", "", "buttons");
                                    $modal_id = "wpd-cliparts-modal";
                                    echo '<a id="wpd-cliparts-opener" class="wpd-button" data-toggle="o-modal" data-target="#' . $modal_id . '">' . __("Browse", "wpd") . '</a>';
                                    $modal = '<div class="omodal fade o-modal wpc-modal ff-monserrat" id="' . $modal_id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="omodal-dialog">
                                                  <div class="omodal-content">
                                                    <div class="omodal-header">
                                                      <button type="button" class="close" data-dismiss="omodal" aria-hidden="true">&times;</button>
                                                      <h4 class="omodal-title" id="myModalLabel' . $modal_id . '">' . __("Browse cliparts", "wpd") . '</h4>
                                                    </div>
                                                    <div class="omodal-body">
                                                        ' . $this->get_images_tools($cliparts_options) . '
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>';
                                    array_push(wpd_retarded_actions::$code, $modal);
                                    if (is_admin())
                                        add_action('admin_footer', array('wpd_retarded_actions', 'display_code'), 10, 1);
                                    else
                                        add_action('wp_footer', array('wpd_retarded_actions', 'display_code'), 10, 1);

                                    //
                                    $options_array = array('grayscale', 'invert', 'sepia1', 'sepia2', 'blur', 'sharpen', 'emboss');
                                    foreach ($options_array as $option) {
                                        $filters_settings[$option] = get_proper_value($cliparts_options, $option, 'yes');
                                    }
                                    ?>

                                    <div class="filter-set-container">
                                        <?php
                                        if ($filters_settings['grayscale'] == "yes" || $filters_settings['invert'] == "yes" || $filters_settings['sepia1'] == "yes" || $filters_settings['sepia2'] == "yes" || $filters_settings['blur'] == "yes" || $filters_settings['sharpen'] == "yes" || $filters_settings['emboss'] == "yes") {
                                            ?>
                                            <span class="filter-set-label wpd-label"><?php _e("Filters", "wpd"); ?></span>
                                            <?php
                                        }
                                        ?>
                                        <span>
                                            <div class="mg-r-element ">
            <?php $this->get_image_filters(1, $cliparts_options); ?>
                                                <div id="clipart-bg-color-container"></div>

                                            </div>

                                        </span>

                                    </div>
                                    <?php
                                    $opacity = get_proper_value($cliparts_options, 'opacity', 'yes');
                                    if ($opacity == "yes") {
                                        ?>
                                        <div>
                                            <span class="wpd-label d-iblk"><?php _e("Opacity", "wpd"); ?></span>
                                            <span >   
                <?php wpd_get_opacity_dropdown("opacity", "txt-opacity-slider", "text-element-border text-tools-select"); ?>
                                            </span>
                                        </div>
            <?php } 
                    do_action('wpd_cliparts_section_end', $this->editor->wpd_product);
            ?>

                                </div>
                            </div>
                            <?php
                        }
                        if (!empty($facebook_app_id) && !empty($facebook_app_secret) && !is_admin()) {
                            ?>
                            <div class="AccordionPanel" id="facebook-panel">
                                <div id="facebook" class="AccordionPanelTab"><?php _e("FACEBOOK", "wpd"); ?></div>
                                <div class="AccordionPanelContent">
                                    <?php $this->get_facebook_tools(); ?>                                 
                                </div>
                            </div>
                            <?php
                        }
                        if (!empty($instagram_app_id) && !empty($instagram_app_secret) && !is_admin()) {
                            ?>
                            <div class="AccordionPanel" id="instagram-panel">
                                <div id="instagram" class="AccordionPanelTab"><?php _e("INSTAGRAM", "wpd"); ?></div>
                                <div class="AccordionPanelContent">
                                    <?php $this->get_instagram_tools(); ?>                                 
                                </div>
                            </div>
                            <?php
                        }

                        if ($design_tab_visible == "yes" && !is_admin()) {
                            ?>

                            <div class="AccordionPanel" id="user-designs-panel">
                                <div id="my-designs" class="AccordionPanelTab"><?php _e("MY DESIGNS", "wpd"); ?></div>
                                <div class="AccordionPanelContent">
                                    <?php $this->get_user_designs_tools(); ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                    
                    <?php
                    if (!is_admin())
                            $this->get_cart_actions_box();
                    ?>

                </div>
                <div class="wpc-editor-col-2  col xl-3-4">
                    <?php //$this->get_toolbar(); ?>
                    <div id="wpc-editor-container">
                        <canvas id="wpc-editor" ></canvas>
                    </div>

                    <div id="product-part-container" class="">
                        <?php $this->get_parts(); ?>
                    </div>
                    <?php
                    //We don't show the column at all if there is nothing to show inside
                    $general_options = $wpd_settings['wpc-general-options'];
                    if (isset($general_options['wpc-download-btn']))
                        $download_btn = $general_options['wpc-download-btn'];

                    if (isset($general_options['wpc-preview-btn']))
                        $preview_btn = $general_options['wpc-preview-btn'];

                    if (isset($general_options['wpc-save-btn']))
                        $save_btn = $general_options['wpc-save-btn'];

                    if (isset($general_options['wpc-cart-btn']))
                        $cart_btn = $general_options['wpc-cart-btn'];

                    if (
                            (isset($preview_btn) && $preview_btn !== "0") ||
                            (isset($download_btn) && $download_btn !== "0") ||
                            (isset($save_btn) && $save_btn !== "0") ||
                            (isset($cart_btn) && $cart_btn !== "0")
                    ) {
                        ?>
                            <?php
                            $this->get_design_actions_box();

                            ?>
                        <?php
                    }
                    ?>
                    <?php
                    if (!is_admin()) {
                        WPD_Design::get_option_form($this->editor->root_item_id, $this->wpc_metas);
                    }
                    ?>
                    <div id="debug"></div>

                </div>
            </div>

        </div>
        <?php
        $output = ob_get_clean();
        return $output;
    }

    private function get_toolbar() {
        GLOBAL $wpd_settings;
//        $general_options = $wpc_options_settings['wpc-general-options'];
        $ui_options = get_proper_value($wpd_settings, 'wpc-ui-options', array());

        $options_array = array(
            'grid-btn' => "grid",
            'clear_all_btn' => "clear",
            'delete_btn' => "delete",
            'copy_paste_btn' => "duplicate",
            'send_to_back_btn' => "send-to-back",
            'bring_to_front_btn' => "bring-to-front",
            'flip_v_btn' => "flipV",
            'flip_h_btn' => "flipH",
            'align_h_btn' => "centerH",
            'align_v_btn' => "centerV",
            'undo-btn' => "undo",
            'redo-btn' => "redo"
        );

        $attribut_value_array["background-size"] = '30px';
        $attribut_value_array["background-position"] = 'center';

        foreach ($options_array as $id => $field_name) {
            wpd_generate_css_tab($ui_options, $id, "", "$field_name");
            wpd_generate_css_tab($ui_options, $id, $field_name, '', $attribut_value_array);
        }
        ?>
        <div id="wpc-buttons-bar">
        <!--        <button id="zoom-in-btn" data-placement="top" data-tooltip-title="<?php // _e("Zoom in","wpd");         ?>"></button>
        <button id="zoom-out-btn" data-placement="top" data-tooltip-title="<?php // _e("Zoom out","wpd");         ?>"></button>
        <button id="zoom-reset-btn" data-placement="top" data-tooltip-title="<?php // _e("Zoom reset","wpd");         ?>"></button>-->
            <span id="grid-btn" data-placement="top" data-tooltip-title="<?php _e("grid", "wpd"); ?>"></span>
            <span id="clear_all_btn" data-placement="top" data-tooltip-title="<?php _e("Clear all", "wpd"); ?>"></span>
            <span id="delete_btn" data-placement="top" data-tooltip-title="<?php _e("Delete", "wpd"); ?>"></span>
            <span id="copy_paste_btn" data-placement="top" data-tooltip-title="<?php _e("Duplicate", "wpd"); ?>"></span>
            <span id="send_to_back_btn" data-placement="top" data-tooltip-title="<?php _e("Send to back", "wpd"); ?>"></span>
            <span id="bring_to_front_btn" data-placement="top" data-tooltip-title="<?php _e("Bring to front", "wpd"); ?>"></span>
            <span id="flip_h_btn" data-placement="top" data-tooltip-title="<?php _e("Flip horizontally", "wpd"); ?>"></span>
            <span id="flip_v_btn" data-placement="top" data-tooltip-title="<?php _e("Flip vertically", "wpd"); ?>"></span>
            <span id="align_h_btn" data-placement="top" data-tooltip-title="<?php _e("Center horizontally", "wpd"); ?>"></span>
            <span id="align_v_btn" data-placement="top" data-tooltip-title="<?php _e("Center vertically", "wpd"); ?>"></span>
            <span id="undo-btn" data-placement="top" data-tooltip-title="<?php _e("Undo", "wpd"); ?>"></span>
            <span id="redo-btn" data-placement="top" data-tooltip-title="<?php _e("Redo", "wpd"); ?>"></span>
        </div>
        <?php
    }

    private function get_text_tools($text_options) {
        GLOBAL $wpd_settings;
        $setting_text = get_proper_value($wpd_settings, "wpc-texts-options", array());
        $ui_options = get_proper_value($wpd_settings, 'wpc-ui-options', array());
        $default_text_color = get_proper_value($ui_options, 'default-text-color');
        $default_text_bg_color = get_proper_value($ui_options, 'default-background-color');
        $default_outline_bg_color = get_proper_value($ui_options, 'default-outline-background-color');

        $options_array = array('font-family', 'font-size', 'bold', 'italic', 'text-color', 'background-color', 'outline-width', 'outline', 'curved',
            'text-radius', 'text-spacing', 'opacity', 'text-alignment', 'underline', 'text-strikethrough', 'text-overline');

        foreach ($options_array as $option) {
            $text_components[$option] = get_proper_value($text_options, $option, 'yes');
        }

        $fonts = get_option("wpc-fonts");
        if (empty($fonts)) {
            $fonts = wpd_get_default_fonts();
        }
        wpd_generate_css_tab($ui_options, "wpc-add-text", "", "buttons");
        ?>
        <div class="text-tool-container dspl-table">
            <div >
                <span class="text-label"><?php _e("Text", "wpd"); ?></span>
                <span class="">
                    <textarea id = "new-text" class="text-element-border text-container "></textarea>
                </span>
            </div>
             <div >
                <span class="text-label wpd-empty-label"></span>
                <span class="">
                    <button id="wpc-add-text" class="wpc-btn-effect"><?php _e("ADD", "wpd"); ?></button>
                </span>
            </div>
            <?php
            if ($text_components['font-family'] == "yes") {
                ?>
                <div >
                    <span class="wpd-label"><?php _e("Font", "wpd"); ?></span>
                    <span class="font-selector-container">
                        <select id="font-family-selector" class="text-element-border">
                            <?php
                            $preload_div = "";
                            foreach ($fonts as $font) {
                                $font_label = $font[0];
                                echo "<optgroup style='font-family:$font_label'><option>$font_label</option></optgroup>";
                                $preload_div.="<span style='font-family: $font_label;'>.</span>";
                            }
                            ?>

                        </select>
                    </span>
                </div>
                <?php
                echo "<div id='wpd-fonts-preloader'>$preload_div</div>";
            }
            if ($text_components['font-size'] == "yes") {
                ?>
                <div >
                    <span class="wpd-label"><?php _e("Size", "wpd"); ?></span>
                    <span >
                        <!--<input id="font-size-selector" type="number" class="text-element-border size-set" value="14">-->
                        <?php
                        $options = array();
                        $max_filtered_size = apply_filters("wpd-max-font-size", 30);
                        $min_filtered_size = apply_filters("wpd-min-font-size", 8);
                        $selected_filtered_size = apply_filters("wpd-default-font-size", 30);


                        $default_size = intval(get_proper_value($setting_text, "default-font-size", $selected_filtered_size));
                        $min_size = intval(get_proper_value($setting_text, "min-font-size", $min_filtered_size));
                        $max_size = intval(get_proper_value($setting_text, "max-font-size", $max_filtered_size));

                        for ($i = $min_size; $i <= $max_size; $i++) {
                            $options[$i] = $i;
                        }
                        echo wpd_get_html_select("font-size-selector", "font-size-selector", "text-element-border text-tools-select", $options, $default_size);
                        ?>
                    </span>
                </div>
                <?php
            }
            if ($text_components['bold'] == "yes" || $text_components['italic'] == "yes" || $text_components['text-color'] == "yes" || $text_components['background-color'] == "yes") {
                ?>
                <div >
                    <span class="wpd-label">
                        <?php _e("Style", "wpd"); ?>
                    </span> 
                    <div class="mg-r-element ">
                        <?php
                        if ($text_components['bold'] == "yes") {
                            ?>
                            <input type="checkbox" id="bold-cb" class="custom-cb">
                            <label for="bold-cb" data-placement="top" data-tooltip-title="<?php _e("Bold", "wpd"); ?>"></label>
                            <?php
                        }
                        if ($text_components['italic'] == "yes") {
                            ?>
                            <input type="checkbox" id="italic-cb" class="custom-cb">
                            <label for="italic-cb" data-placement="top" data-tooltip-title="<?php _e("Italic", "wpd"); ?>"></label>
                            <?php
                        }
                        if ($text_components['text-color'] == "yes") {
                            ?>
                            <span id="txt-color-selector" class=" "  data-placement="top" data-tooltip-title="<?php _e("Text color", "wpd"); ?>" style="background-color: <?php echo $default_text_color; ?>;"></span>
                            <?php
                        }
                        if ($text_components['background-color'] == "yes") {
                            ?>
                            <span id="txt-bg-color-selector" class="bg-color-selector " data-placement="top" data-tooltip-title="<?php _e("Background color", "wpd"); ?>" style="background-color: <?php echo $default_text_bg_color; ?>;"></span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            if ($text_components['outline-width'] == "yes" || $text_components['outline'] == "yes") {
                ?>
                <div>
                    <span class="wpd-label"><?php _e("Outline", "wpd"); ?>
                    </span>
                    <div>
                        <?php
                        if ($text_components['outline-width'] == "yes") {
                            ?>
                            <label  for="o-thickness-slider" class="fs-9 width-label"><?php _e("Width", "wpd"); ?></label>
                            <?php
                            $options = array(0 => __("None", "wpd"), 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                            echo wpd_get_html_select("o-thickness-slider", "o-thickness-slider", "text-element-border text-tools-select", $options);
                        }
                        if ($text_components['outline'] == "yes") {
                            ?>
                            <div class="color-container">
                                <label for="color" class="fs-9 color-label"><?php _e("Color", "wpd"); ?></label> 
                                <span id="txt-outline-color-selector" class="bg-color-selector " data-placement="top" data-tooltip-title="<?php _e("Background color", "wpd"); ?>" style="background-color: <?php echo $default_outline_bg_color; ?>;"></span>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                </div>
                <?php
            }
            if ($text_components['curved'] == "yes") {
                ?>
                <div >
                    <span class="wpd-label"><?php _e("Curved", "wpd"); ?></span>
                    <div>
                        <input type="checkbox" id="cb-curved" class="custom-cb checkmark"> 
                        <label for="cb-curved" id="cb-curved-label" ></label>

                        <label for="radius" class="radius-label fs-9"><?php _e("Radius", "wpd"); ?></label>
                        <?php
                        $options = array();
                        for ($i = 1; $i <= 20; $i++) {
                            array_push($options, $i);
                        }
                        echo wpd_get_html_select("spacing", "curved-txt-spacing-slider", "text-element-border text-tools-select", $options, 9);
                        ?>
                        <div class="spacing-container">
                            <label for="spacing" class="spacing-label fs-9"><?php _e("Spacing", "wpd"); ?></label>
                            <?php
                            $options = array();
                            for ($i = 0; $i <= 30; $i++) {
                                $options[$i * 10] = $i * 10;
                            }
                            echo wpd_get_html_select("radius", "curved-txt-radius-slider", "text-element-border text-tools-select", $options, 150);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }

            if ($text_components['opacity'] == "yes") {
                ?>
                <div>
                    <span class="wpd-label"><?php _e("Opacity", "wpd"); ?></span>
                    <span >
                        <?php
                        wpd_get_opacity_dropdown("opacity", "opacity-slider", "text-element-border text-tools-select");
                        ?>
                    </span>
                </div>
                <?php
            }
            if ($text_components['text-alignment'] == "yes") {
                ?>
                <div>
                    <span class="wpd-label"><?php _e("Alignment", "wpd"); ?></span>
                    <div class="mg-r-element">
                        <input type="radio" id="txt-align-left" name="radio" class="txt-align" value="left"/>
                        <label for="txt-align-left" ><span></span></label>

                        <input type="radio" id="txt-align-center" name="radio" class="txt-align" value="center"/>
                        <label for="txt-align-center"><span ></span></label>

                        <input type="radio" id="txt-align-right" name="radio" class="txt-align" value="right"/>
                        <label for="txt-align-right"><span ></span></label>

                    </div>

                </div>
                <?php
            }
            if ($text_components['underline'] == "yes" || $text_components['text-strikethrough'] == "yes" || $text_components['text-overline'] == "yes") {
                ?>
                <div >
                    <span class="wpd-label"><?php _e("Decoration", "wpd"); ?></span>
                    <div class=" mg-r-element">
                        <?php
                        if ($text_components['underline'] == "yes") {
                            ?>
                            <input type="radio" id="underline-cb" name="txt-decoration" class="txt-decoration" value="underline">
                            <label for="underline-cb" data-placement="top" data-tooltip-title="<?php _e("Underline", "wpd"); ?>"><span></span></label>
                            <?php
                        }
                        if ($text_components['text-strikethrough'] == "yes") {
                            ?>
                            <input type="radio" id="strikethrough-cb" name="txt-decoration" class="txt-decoration" value="line-through">
                            <label for="strikethrough-cb" data-placement="top" data-tooltip-title="<?php _e("Strikethrough", "wpd"); ?>"><span></span></label>
                            <?php
                        }
                        if ($text_components['text-overline'] == "yes") {
                            ?>
                            <input type="radio" id="overline-cb" name="txt-decoration" class="txt-decoration" value="overline">
                            <label for="overline-cb" data-placement="top" data-tooltip-title="<?php _e("Overline", "wpd"); ?>"><span></span></label>
                            <?php
                        }
                        ?>
                        <input type="radio" id="txt-none-cb" name="txt-decoration" class="txt-decoration" value="none">
                        <label for="txt-none-cb" data-placement="top" data-tooltip-title="<?php _e("None", "wpd"); ?>"><span></span></label>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }

    private function get_shapes_tools($shapes_options, $colors_options) {
        global $wpd_settings;

        $options_array = array('background-color', 'outline-width', 'outline', 'opacity', 'square', 'r-square', 'circle', 'triangle', 'heart', 'polygon', 'star');
        $ui_options = get_proper_value($wpd_settings, 'wpc-ui-options', array());
        $default_shape_color = get_proper_value($ui_options, 'default-shape-background-color');
        $default_shape_outline_bg_color = get_proper_value($ui_options, 'default-shape-outline-background-color');
        foreach ($options_array as $option) {
            $shapes_components[$option] = get_proper_value($shapes_options, $option, 'yes');
        }
        ?>
        <div class="dspl-table">
            <?php
            if ($shapes_components['background-color'] == "yes") {
                ?>
                <div>
                    <span class="text-label"><?php _e("Background", "wpd"); ?></span>
                    <span class="">
                        <span id="shape-bg-color-selector" class="bg-color-selector " data-placement="top" data-tooltip-title="<?php _e("Background color", "wpd"); ?>" style="background-color: <?php echo $default_shape_color; ?>;"></span>
                    </span>
                </div>
                <?php
            }
            if ($shapes_components['outline-width'] == "yes" || $shapes_components['outline'] == "yes") {
                ?>
                <div>
                    <span class="text-label"><?php _e("Outline", "wpd"); ?></span>
                    <span class="">
                        <?php if ($shapes_components['outline-width'] == "yes") { ?>
                            <label class="width-label fs-11"><?php _e("Width", "wpd"); ?></label>
                            <?php
                            $options = array(0 => "None", 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                            echo wpd_get_html_select("shape-thickness-slider", "shape-thickness-slider", "text-element-border text-tools-select", $options);
                        }
                        if ($shapes_components['outline'] == "yes") {
                            ?>
                            <div class="color-container">
                                <label class="fs-11 color-label"><?php _e("Color", "wpd"); ?></label> 
                                <span id="shape-outline-color-selector" class="bg-color-selector " data-placement="top" data-tooltip-title="<?php _e("Outline color", "wpd"); ?>" style="background-color: <?php echo $default_shape_outline_bg_color; ?>;"></span>
                            </div>
                        <?php } ?>
                    </span>
                </div>
                <?php
            }
            if ($shapes_components['opacity'] == "yes") {
                ?>
                <div>
                    <span class="text-label"><?php _e("Opacity", "wpd"); ?></span>
                    <span class="">
                        <?php
                        echo wpd_get_opacity_dropdown("shape-opacity-slider", "shape-opacity-slider", "");
                        ?>
                    </span>
                </div>
                <?php
            }
            if ($shapes_components['square'] == "yes" || $shapes_components['r-square'] == "yes" || $shapes_components['circle'] == "yes" || $shapes_components['triangle'] == "yes" || $shapes_components['heart'] == "yes" || $shapes_components['polygon'] == "yes" || $shapes_components['star'] == "yes") {
                ?>
                <div>
                    <span class="text-label">
                        <?php _e("Shapes", "wpd"); ?>
                    </span>
                    <div class="img-container shapes">
                        <?php if ($shapes_components['square'] == "yes") { ?>
                            <span id="square-btn"></span>
                        <?php }if ($shapes_components['r-square'] == "yes") { ?>
                            <span id="r-square-btn"></span>
                        <?php }if ($shapes_components['circle'] == "yes") { ?>
                            <span id="circle-btn"></span>
                        <?php }if ($shapes_components['triangle'] == "yes") { ?>
                            <span id="triangle-btn"></span>
                        <?php }if ($shapes_components['heart'] == "yes") { ?>
                            <span id="heart-btn"><i class="fa fa-heart" aria-hidden="true"></i></span>
                        <?php }if ($shapes_components['polygon'] == "yes") { ?>
                            <span id="polygon5" class="polygon-btn" data-num="5"></span>
                            <span id="polygon6" class="polygon-btn" data-num="6"></span>
                            <span id="polygon7" class="polygon-btn" data-num="7"></span>
                            <span id="polygon8" class="polygon-btn" data-num="8"></span>
                            <span id="polygon9" class="polygon-btn" data-num="9"></span>
                            <span id="polygon10" class="polygon-btn" data-num="10"></span>
                        <?php }if ($shapes_components['star'] == "yes") { ?>
                            <span id="star5" class="star-btn" data-num="5"></span>
                            <span id="star6" class="star-btn" data-num="6"></span>
                            <span id="star7" class="star-btn" data-num="7"></span>
                            <span id="star8" class="star-btn" data-num="8"></span>
                            <span id="star9" class="star-btn" data-num="9"></span>
                            <span id="star10" class="star-btn" data-num="10"></span>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }

    private function get_uploads_tools($options) {
        $opacity = get_proper_value($options, 'opacity', 'yes');
        if (isset($options['wpc-uploader']))
            $uploader = $options['wpc-uploader'];
        $form_class = "custom-uploader";
        if ($uploader == "native")
            $form_class = "native-uploader";
        if (!is_admin()) {
            ?>
            <form id="userfile_upload_form" class="<?php echo $form_class; ?>" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wpc-picture-upload-nonce'); ?>">
                <input type="hidden" name="action" value="handle_picture_upload">
                <?php
                if ($uploader == "native") {
                    ?>
                    <input type="file" name="userfile" id="userfile">
                    <?php
                } else {
                    ?>        
                    <div id="drop">
                        <a><?php _e("Pick a file", "wpd"); ?></a>
                        <label for="userfile"></label>
                        <input type="file" name="userfile" id="userfile"/>
                        <div class="acd-upload-info"></div>
                    </div>
                    <?php
                }
                ?>
            </form>

            <div id="acd-uploaded-img" class="img-container"></div>
            <?php
        } else
            echo "<span class='filter-set-label wpd-label' style='display: inline-block;'></span><a id='wpc-add-img' class='button' style='margin-bottom: 10px;'>" . __("Add image", "wpd") . "</a>";

        $options_array = array('grayscale', 'invert', 'sepia1', 'sepia2', 'blur', 'sharpen', 'emboss');
        foreach ($options_array as $option) {
            $filters_settings[$option] = get_proper_value($options, $option, 'yes');
        }
        ?>

        <div class="filter-set-container">
            <?php
            if ($filters_settings['grayscale'] == "yes" || $filters_settings['invert'] == "yes" || $filters_settings['sepia1'] == "yes" || $filters_settings['sepia2'] == "yes" || $filters_settings['blur'] == "yes" || $filters_settings['sharpen'] == "yes" || $filters_settings['emboss'] == "yes") {
                ?>
                <span class="filter-set-label wpd-label"><?php _e("Filters", "wpd"); ?></span>
                <span>
                    <div class="mg-r-element ">

                        <?php $this->get_image_filters(2, $options); ?>
                        <div id="clipart-bg-color-container"></div>
                    </div>

                </span>
                <?php
            }
            ?>

        </div>
        <?php if ($opacity == "yes") { ?>
            <div>
                <span class="wpd-label d-iblk"><?php _e("Opacity", "wpd"); ?></span>
                <span >   
                    <?php
                    wpd_get_opacity_dropdown("img-opacity-slider", "img-opacity-slider", "text-element-border text-tools-select");
                    ?>
                </span>
            </div>
            <?php
        }
        do_action('wpd_uploads_section_end', $this->editor->wpd_product);
    }

    private function get_image_filters($index, $options) {
        $options_array = array('grayscale', 'invert', 'sepia1', 'sepia2', 'blur', 'sharpen', 'emboss');
        foreach ($options_array as $option) {
            $filters_settings[$option] = get_proper_value($options, $option, 'yes');
        }

        if ($filters_settings['grayscale'] == "yes") {
            ?> 
            <input type="checkbox" id="grayscale-<?php echo $index; ?>"  class="custom-cb filter-cb acd-grayscale">
            <label for="grayscale-<?php echo $index; ?>"><?php _e("Grayscale", "wpd"); ?></label>
            <?php
        }
        if ($filters_settings['invert'] == "yes") {
            ?>
            <input type="checkbox" id="invert-<?php echo $index; ?>" class="custom-cb filter-cb acd-invert">
            <label for="invert-<?php echo $index; ?>"><?php _e("Invert", "wpd"); ?></label>
            <?php
        }
        if ($filters_settings['sepia1'] == "yes") {
            ?>
            <input type="checkbox" id="sepia-<?php echo $index; ?>" class="custom-cb filter-cb acd-sepia">
            <label for="sepia-<?php echo $index; ?>"><?php _e("Sepia 1", "wpd"); ?></label>
            <?php
        }
        if ($filters_settings['sepia2'] == "yes") {
            ?>
            <input type="checkbox" id="sepia2-<?php echo $index; ?>" class="custom-cb filter-cb acd-sepia2">
            <label for="sepia2-<?php echo $index; ?>"><?php _e("Sepia 2", "wpd"); ?></label>
            <?php
        }
        if ($filters_settings['blur'] == "yes") {
            ?>
            <input type="checkbox" id="blur-<?php echo $index; ?>" class="custom-cb filter-cb acd-blur">
            <label for="blur-<?php echo $index; ?>"><?php _e("Blur", "wpd"); ?></label>
            <?php
        }
        if ($filters_settings['sharpen'] == "yes") {
            ?>
            <input type="checkbox" id="sharpen-<?php echo $index; ?>" class="custom-cb filter-cb acd-sharpen">
            <label for="sharpen-<?php echo $index; ?>"><?php _e("Sharpen", "wpd"); ?></label>
            <?php
        }
        if ($filters_settings['emboss'] == "yes") {
            ?>
            <input type="checkbox" id="emboss-<?php echo $index; ?>" class="custom-cb filter-cb acd-emboss">
            <label for="emboss-<?php echo $index; ?>"><?php _e("Emboss", "wpd"); ?></label>
            <?php
        }
    }

    private function get_images_tools_old($options) {
//        GLOBAL $wpc_options_settings;
        $cliparts_options = get_proper_value($options, 'wpc-images-options', array());
        $use_lazy_load = get_proper_value($cliparts_options, 'lazy', 'yes');
        if ($use_lazy_load == 'yes') {
            $clipart_class = 'o-lazy';
            $src_attr = "data-original";
        } else {
            $clipart_class = '';
            $src_attr = "src";
        }

        $opacity = get_proper_value($options, 'opacity', 'yes');

        $transient_key = "orion_wpd_cliparts_transient";
        $cached_output = get_transient($transient_key);
        if ($cached_output) {
            echo $cached_output;
        } else {

            $output = "";
            ?>
            <!--<div class="">-->
            <?php
            $args = array(
                'numberposts' => -1,
                'post_type' => 'wpc-cliparts'
            );
            $cliparts_groups = get_posts($args);
            $output.= '<div id="img-cliparts-accordion" class="Accordion minimal" tabindex="0">';
            foreach ($cliparts_groups as $cliparts_group) {
                $cliparts = get_post_meta($cliparts_group->ID, "wpc-cliparts", true);
                $cliparts_prices = get_post_meta($cliparts_group->ID, "wpc-cliparts-prices", true);
                if (!empty($cliparts)) {
                    $output.= '<div class="AccordionPanel">
                                    <div class="AccordionPanelTab">' . $cliparts_group->post_title . ' (' . count($cliparts) . ')</div>
                                    <div class="AccordionPanelContent img-container">';

                    foreach ($cliparts as $i => $clipart_id) {
                        $attachment_url = o_get_proper_image_url($clipart_id);
                        $price = 0;
                        if (isset($cliparts_prices[$i]))
                            $price = $cliparts_prices[$i];
                        $custom_attributes = apply_filters("wpd_cliparts_attributes", array(), $clipart_id, $cliparts_group);
                        $custom_attributes = wpd_build_attributes_from_array($custom_attributes);

                        $output.= "<span class='clipart-img'><img class='$clipart_class' $src_attr='$attachment_url' data-price='$price' " . implode(' ', $custom_attributes) . "></span>";
                    }
                    $output.= '</div>
                            </div>';
                }
            }
            $output.= '</div>';
            set_transient($transient_key, $output, 12 * HOUR_IN_SECONDS);
            echo $output;
        }
        $options_array = array('grayscale', 'invert', 'sepia1', 'sepia2', 'blur', 'sharpen', 'emboss');
        foreach ($options_array as $option) {
            $filters_settings[$option] = get_proper_value($options, $option, 'yes');
        }
        ?>

        <div class="filter-set-container">
            <?php
            if ($filters_settings['grayscale'] == "yes" || $filters_settings['invert'] == "yes" || $filters_settings['sepia1'] == "yes" || $filters_settings['sepia2'] == "yes" || $filters_settings['blur'] == "yes" || $filters_settings['sharpen'] == "yes" || $filters_settings['emboss'] == "yes") {
                ?>
                <span class="filter-set-label wpd-label"><?php _e("Filters", "wpd"); ?></span>
                <?php
            }
            ?>
            <span>
                <div class="mg-r-element ">
                    <?php $this->get_image_filters(1, $options); ?>
                    <div id="clipart-bg-color-container"></div>

                </div>

            </span>

        </div>
        <?php if ($opacity == "yes") { ?>
            <div>
                <span class="wpd-label"><?php _e("Opacity", "wpd"); ?></span>
                <span >   
                    <?php wpd_get_opacity_dropdown("opacity", "txt-opacity-slider", "text-element-border text-tools-select"); ?>
                </span>
            </div>
        <?php } ?>
        <?php
    }

    function get_images_tools($options) {
        GLOBAL $wpd_settings;
        $use_lazy_load = get_proper_value($options, 'lazy', 'yes');
        if ($use_lazy_load == 'yes') {
            $clipart_class = 'o-lazy';
            $src_attr = "data-original";
        } else {
            $clipart_class = '';
            $src_attr = "src";
        }

        $groups = "<ul class='wpd-cliparts-groups col xl-1-3'>";
        $cliparts_output = "";
        $args = array(
            'numberposts' => -1,
            'post_type' => 'wpc-cliparts'
        );

        $first_display = "style='display: block'";
        $cliparts_groups = get_posts($args);
        foreach ($cliparts_groups as $cliparts_group) {
            $cliparts = get_post_meta($cliparts_group->ID, "wpd-cliparts-data", true);
            if (!empty($cliparts)) {
                $groups.='<li data-groupid="' . $cliparts_group->ID . '">' . $cliparts_group->post_title . ' (' . count($cliparts) . ')</li>';
                $cliparts_output.= '<div class="wpd-cliparts-container" data-groupid="' . $cliparts_group->ID . '" ' . $first_display . '>';
                $first_display = "";

                foreach ($cliparts as $i => $clipart) {
                    //$attachment_url = o_get_proper_image_url($clipart_id);
                    $attachment_url = o_get_proper_image_url($clipart["id"]);
                    $price = get_proper_value($clipart, "price", 0);
                    $custom_attributes = apply_filters("wpd_cliparts_attributes", array(), $clipart["id"], $cliparts_group);
                    $custom_attributes = wpd_build_attributes_from_array($custom_attributes);

                    $cliparts_output.= "<span class='clipart-img' data-src='$attachment_url'><img class='$clipart_class' $src_attr='$attachment_url' data-price='$price' " . implode(' ', $custom_attributes) . "></span>";
                }
                $cliparts_output.= '</div>';
            }
        }
        $groups.="</ul>";
        $output = "<div id='wpd-cliparts-wrapper' class='o-wrap'>";
        $output.= "<div class='col xl-1-3'><input type='text' id='wpd-cliparts-search' placeholder='Search...'></div><div class='col xl-2-3'></div>";
        $output.= $groups;
        $output.= "<div class='col xl-2-3' id='wpd-search-cliparts-results'></div>";
        $output.= "<div class='col xl-2-3' id='wpd-all-cliparts'>" . $cliparts_output . "</div>";
        $output.= "</div>";
        return $output;
    }

    function get_social_login_url($network) {
        $url = $_SERVER["REQUEST_URI"];

        $url_parts = parse_url($url);
        if (!isset($url_parts['query']))
            $url_parts['query'] = "";
        parse_str($url_parts['query'], $params);

        $params['social-login'] = $network;

        $output_url = "?";
        $count = 1;
        foreach ($params as $key => $value) {
            $output_url.="$key=$value";
            if ($count < count($params))
                $output_url.="&";
        }


        return $output_url;
    }

    private function get_facebook_tools() {
        ?>
        <div class="wpc-rs-app">
            <a class="wpc-facebook acd-social-login" href="<?php echo $this->get_social_login_url("facebook"); ?>"><?php _e("Extract my pictures", "wpd"); ?></a>

        </div>
        <div class="img-container">
            <?php
            if (isset($_SESSION["wpd-facebook-images"])) {
                foreach ($_SESSION["wpd-facebook-images"] as $facebook_img) {
                    echo "<span class='clipart-img'><img src='$facebook_img'></span>";
                }
            }
            ?>
        </div>

        <?php
    }

    private function get_instagram_tools() {
        ?>
        <div class="wpc-rs-app">
            <a class="wpc-instagram acd-social-login" href="<?php echo $this->get_social_login_url("instagram"); ?>"><?php _e("Extract my pictures", "wpd"); ?></a>
        </div>
        <div class="img-container">
            <?php
            if (isset($_SESSION["wpd-instagram-images"])) {
                foreach ($_SESSION["wpd-instagram-images"] as $facebook_img) {
                    echo "<span class='clipart-img'><img src='$facebook_img'></span>";
                }
            }
            ?>
        </div>

        <?php
    }

    private function get_user_designs_tools() {
        if (is_user_logged_in()) {
            GLOBAL $current_user;
            GLOBAL $wpd_settings;
            $designs_options = get_proper_value($wpd_settings, 'wpc-designs-options', array());
            $saved_visible = get_proper_value($designs_options, 'saved', 'yes');
            $orders_visible = get_proper_value($designs_options, 'orders', 'yes');
            $user_designs = get_user_meta($current_user->ID, 'wpc_saved_designs');
            $user_orders_designs = wpd_get_user_orders_designs($current_user->ID);
            ?>
            <div id="my-designs-accordion" class="Accordion minimal" tabindex="0">
                <?php
                if ($saved_visible === "yes") {
                    ?>
                    <div class="AccordionPanel">
                        <div class="AccordionPanelTab"><?php _e("Saved Designs", "wpd"); ?></div>
                        <div class="AccordionPanelContent">
                            <?php echo $this->get_user_design_output_block($user_designs); ?>
                        </div>
                    </div>
                    <?php
                }

                if ($orders_visible === "yes") {
                    ?>
                    <div class="AccordionPanel">
                        <div class="AccordionPanelTab"><?php _e("Past Orders", "wpd"); ?></div>
                        <div class="AccordionPanelContent">
                            <?php echo $this->get_user_design_output_block($user_orders_designs); ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        } else {
            _e("You need to be logged in before loading your designs.", "wpd");
        }
    }

    private function get_user_design_output_block($user_designs) {
        GLOBAL $wpd_settings;
        $ui_options = get_proper_value($wpd_settings, 'wpc-ui-options', array());
        wpd_generate_css_tab($ui_options, "wpc-parts-bar li", "", "texts");
        $output = "";
        foreach ($user_designs as $s_index => $user_design) {
            if (!empty($user_design)) {
                $variation_id = $user_design[0];
                $save_time = $user_design[1];
                $design_data = $user_design[2];
                $order_item_id = "";
                //Comes from an order
                if (count($user_design) >= 4)
                    $order_item_id = $user_design[3];
                $output.="<div class='wpc_order_item' data-item='$variation_id'>";
                if (count($user_design) > 1)
                    $output.="<span data-tooltip-title='$save_time' class='info-icon'></span>";
                if (is_array($design_data)) {
                    //            var_dump($design_data);
                    $new_version = false;
                    $upload_dir = wp_upload_dir();
                    if (isset($design_data["output"]["files"])) {
                        $tmp_dir = $design_data["output"]["working_dir"];
                        $design_data = $design_data["output"]["files"];
                        $new_version = true;
                    }
                    foreach ($design_data as $data_key => $data) {
                        if (!empty($data)) {
                            if ($new_version) {
                                $generation_url = $upload_dir["baseurl"] . "/WPC/$tmp_dir/$data_key/";
                                $img_src = $generation_url . $data["image"];
                                $original_part_img_url = "";
                            } else {
                                if (!isset($data["image"]))
                                    continue;
                                $img_src = $data["image"];
                                $original_part_img_url = $data["original_part_img"];
                            }

                            if ($order_item_id)
                                $modal_id = $order_item_id . "_$variation_id" . "_$data_key";
                            else
                                $modal_id = $s_index . "_$variation_id" . "_$data_key";

                            $output.='<span><a class="wpd-button" data-toggle="o-modal" data-target="#' . $modal_id . '">' . ucfirst($data_key) . '</a></span>';
                            $modal = '<div class="omodal fade o-modal wpc-modal wpc_part" id="' . $modal_id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="omodal-dialog">
                                          <div class="omodal-content">
                                            <div class="omodal-header">
                                              <button type="button" class="close" data-dismiss="omodal" aria-hidden="true">&times;</button>
                                              <h4 class="omodal-title" id="myModalLabel' . $modal_id . '">' . __("Preview", "wpd") . '</h4>
                                            </div>
                                            <div class="omodal-body">
                                                <div style="background-image:url(' . $original_part_img_url . ')"><img src="' . $img_src . '"></div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>';
                            array_push(wpd_retarded_actions::$code, $modal);
                            add_action('wp_footer', array('wpd_retarded_actions', 'display_code'), 10, 1);
                        }
                    }

                    $wpd_product = new WPD_Product($variation_id);
                    if ($order_item_id)
                        $output.='<a class="wpd-button" href="' . $wpd_product->get_design_url(false, false, $order_item_id) . '">' . __("Load", "wpc") . '</a>';
                    else {
                        $output.='<a class="wpd-button" href="' . $wpd_product->get_design_url($s_index) . '">' . __("Load", "wpc") . '</a>';
                        $output.='<a class="wpd-button wpd-delete-design" data-index="' . $s_index . '">' . __("Delete", "wpc") . '</a>';
                    }
                }
                $output.="</div>";
            }
        }
        return $output;
    }

    private function get_parts() {
        $parts = get_option("wpc-parts");
        $is_first = true;
//        $wpc_metas = get_post_meta($this->editor->root_item_id, 'wpc-metas', true);
        ?>
        <div id="product-part-container">
            <ul id="wpc-parts-bar">
                <?php
                foreach ($parts as $part) {
                    $part_key = sanitize_title($part);
                    if (get_proper_value($this->wpc_metas, $this->editor->item_id, array()) && get_proper_value($this->wpc_metas[$this->editor->item_id], 'parts', array()) && get_proper_value($this->wpc_metas[$this->editor->item_id]['parts'], $part_key, array())) {
                        $bg_included_id = get_proper_value($this->wpc_metas[$this->editor->item_id]['parts'][$part_key], 'bg-inc');
                        $bg_not_included_id = get_proper_value($this->wpc_metas[$this->editor->item_id]['parts'][$part_key], 'bg-not-inc');
                        if (get_proper_value($this->wpc_metas[$this->editor->item_id]['parts'][$part_key], 'ov')) {
                            $part_ov_img = get_proper_value($this->wpc_metas[$this->editor->item_id]['parts'][$part_key]['ov'], 'img');
                            $overlay_included = get_proper_value($this->wpc_metas[$this->editor->item_id]['parts'][$part_key]['ov'], 'inc', "-1");
                            $enabled = get_proper_value($this->wpc_metas[$this->editor->item_id]['parts'][$part_key], 'enabled', false);
                        }
                    }
//                    if ((!($bg_included_id || $bg_included_id == "0"))||!$enabled)
                    if (!$enabled)
                        continue;
                    $class = "";
                    if ($is_first)
                        $class = "class='active'";
                    $is_first = false;
                    $img_ov_src = "";

                    if (isset($part_ov_img)) {
                        $img_ov_src = o_get_proper_image_url($part_ov_img);
                    }

                    $bg_not_included_src = "";
                    if (!empty($bg_not_included_id))
                        $bg_not_included_src = o_get_proper_image_url($bg_not_included_id);

                    if ($bg_included_id == "0") {
                        $bg_included_src = "";
                        $part_img = $part;
                    } else {
                        $bg_included_src = o_get_proper_image_url($bg_included_id);
                        if ($bg_included_src)
                            $part_img = '<img src="' . $bg_included_src . '">';
                        else
                            $part_img = $part;
                    }
                    ?>
                    <li data-id="<?php echo $part_key; ?>" data-url="<?php echo $bg_not_included_src; ?>" data-bg="<?php echo $bg_included_src; ?>" <?php echo $class; ?> data-placement="top" data-tooltip-title="<?php echo $part; ?>" data-ov="<?php echo $img_ov_src; ?>" data-ovni="<?php echo $overlay_included; ?>">
                        <?php echo $part_img; ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
    }

    private function get_design_actions_box() {
        GLOBAL $wpd_settings;
        $general_options = $wpd_settings['wpc-general-options'];
        $ui_options = get_proper_value($wpd_settings, 'wpc-ui-options', array());
        wpd_generate_css_tab($ui_options, "wpc-action-title", false, "cart-box");
        wpd_generate_css_tab($ui_options, "preview-btn", "icon", "preview-btn");
        wpd_generate_css_tab($ui_options, "download-btn", "icon", "download-btn");
        wpd_generate_css_tab($ui_options, "save-btn", "icon", "save-btn");


        if (isset($general_options['wpc-download-btn']))
            $download_btn = $general_options['wpc-download-btn'];
        if (isset($general_options['wpc-preview-btn']))
            $preview_btn = $general_options['wpc-preview-btn'];
        if (isset($general_options['wpc-save-btn']))
            $save_btn = $general_options['wpc-save-btn'];

        $design_index = -1;
        if (isset($_GET["design_index"])) {
            $design_index = $_GET["design_index"];
        }
        //We don't show the box at all if there is nothing to show inside
        if (isset($preview_btn) && $preview_btn === "0" && isset($download_btn) && $download_btn === "0" && isset($save_btn) && $save_btn === "0")
            return;
        ?>
        <div id="wpc-design-btn-box" >
            <?php
            if (isset($preview_btn) && $preview_btn !== "0") {
                ?>
                <button id="preview-btn" class="wpc-btn-effect"><?php _e("PREVIEW", "wpd"); ?></button>
                <?php
            }
            if (!is_admin()) {
                if (isset($download_btn) && $download_btn !== "0") {
                    ?>
                    <button id="download-btn" class="wpc-btn-effect"><?php _e("DOWNLOAD", "wpd"); ?></button>
                    <?php
                }
                if (isset($save_btn) && $save_btn !== "0") {
                    ?>
                    <button id="save-btn" class="wpc-btn-effect" data-index="<?php echo $design_index; ?>"><?php _e("SAVE", "wpd"); ?></button>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        $modal = '<div class="omodal fade o-modal wpd-modal" id="wpd-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="omodal-dialog">
              <div class="omodal-content">
                <div class="omodal-header">
                  <button type="button" class="close" data-dismiss="omodal" aria-hidden="true">&times;</button>
                  <h4 class="omodal-title" id="myModalLabel">' . __('PREVIEW', 'wpd') . '</h4>
                </div>
                <div class="omodal-body txt-center">
                </div>
              </div>
            </div>
        </div>';
        if (!is_admin()) {
            array_push(wpd_retarded_actions::$code, $modal);
            add_action('wp_footer', array('wpd_retarded_actions', 'display_code'), 10, 1);
        } else
            echo $modal;
    }

    private function get_cart_actions_box() {
        GLOBAL $wpd_settings, $wp_query;
        $general_options = $wpd_settings['wpc-general-options'];
        $ui_options = get_proper_value($wpd_settings, 'wpc-ui-options', array());
        wpd_generate_css_tab($ui_options, "wpd-cart-title", "", "action-box");
        wpd_generate_css_tab($ui_options, "minus", "", "minus-btn");
        wpd_generate_css_tab($ui_options, "plus", "", "plus-btn");
        wpd_generate_css_tab($ui_options, "add-to-cart-btn", "icon", "add-to-cart-btn");
        wpd_generate_css_tab($ui_options, "total-price", "", "texts", "", true);
        wpd_generate_css_tab($ui_options, "wpd-qty.wpc-custom-right-quantity-input", "", "texts", "", true);
        if (isset($general_options['wpc-cart-btn']))
            $cart_btn = $general_options['wpc-cart-btn'];

        $product = wc_get_product($this->editor->item_id);
        if (!$product->price)
            $product->price = 0;
        
        $tpl_price=0;
        if (isset($wp_query->query_vars["tpl"])) {
            $tpl_id = $wp_query->query_vars["tpl"];
            $tpl_price=  wpd_get_template_price($tpl_id);
        }
        
        if (isset($cart_btn) && $cart_btn !== "0") {
            GLOBAL $wp_query;
            $add_to_cart_label = __("ADD TO CART", "wpd");
            if (isset($wp_query->query_vars["edit"]))
                $add_to_cart_label = __("UPDATE CART ITEM", "wpd");
            ?>
            <div class="wpd-separator-title" id="wpd-cart-title"><?php _e("Cart", "wpd");?></div>
            <div id="wpc-cart-box" class="">
                <?php
//                $wpc_metas = get_post_meta($this->editor->root_item_id, 'wpc-metas', true);
                if (isset($this->wpc_metas['related-quantities']) && !empty($this->wpc_metas['related-quantities']) && $product->product_type == "variation") {
                    $related_attributes = $this->wpc_metas['related-quantities'];
                    $wpd_root_product = new WPD_Product($this->editor->root_item_id);
                    $usable_attributes = $wpd_root_product->extract_usable_attributes();
                    $variation = wc_get_product($this->editor->item_id);
                    $selected_attributes = $variation->get_variation_attributes();
                    $to_search = array();
                    foreach ($usable_attributes as $attribute_name => $attribute_data) {
                        $attribute_key = $attribute_data["key"];
                        if (in_array($attribute_key, $related_attributes)) {
//					echo $attribute_data["label"].":<br>";
                            ?>
                            <div class="wpd-rp-attributes-container">
                                <?php
                                foreach ($attribute_data["values"] as $attribute_value) {
                                    $to_search = $selected_attributes;
                                    if (is_object($attribute_value)) {//Taxonomy
                                        $sanitized_value = $attribute_value->slug;
                                        $label = $attribute_value->name;
                                    } else {
                                        $sanitized_value = sanitize_title($attribute_value);
                                        $label = $attribute_value;
                                    }
                                    $to_search[$attribute_key] = sanitize_title($sanitized_value); //$attribute_value;//sanitize_title($sanitized_value);
//                                                var_dump($to_search);
                                    $variation_to_load = wpd_get_variation_from_attributes($to_search, $this->editor->root_item_id);
                                    //if(!$variation_to_load||$variation_to_load==$this->editor->item_id)
                                    if (!$variation_to_load)
                                        continue;

                                    $variation_to_load_ob = wc_get_product($variation_to_load);
                                    $quantity_display = "";
                                    if ($variation_to_load_ob->is_sold_individually()) {
                                        $quantity_display = "style='display: none;'";
                                    }

                                    $wpd_variation = new WPD_Product($variation_to_load);
                                    $purchase_properties = $wpd_variation->get_purchase_properties();

                                    //Variation properties
                                    $price = $variation_to_load_ob->get_price()+$tpl_price;
//                                var_dump($price);
//                                    $product_price = ' <span class="total_order">' . number_format($price * $purchase_properties["min_to_purchase"], $nb_decimals, $decimal_sep, $thousand_sep) . '</span>';
                                    $price_html = ' <span class="total_order">' . wc_price($price * $purchase_properties["min_to_purchase"]) . '</span>';


                                    $variation_to_load_attributes = $variation_to_load_ob->get_variation_attributes();
                                    $attribute_str = "";

                                    foreach ($variation_to_load_attributes as $variation_to_load_attribute_key => $variation_to_load_attribute) {
                                        if (in_array($variation_to_load_attribute_key, $related_attributes)) {
                                            if (!empty($attribute_str))
                                                $attribute_str.="+";
                                            $attribute_str.=$variation_to_load_attribute;
                                        }
                                    }
                                    ?>
                                    <div class="wpc-qty-container" data-id="<?php echo $variation_to_load; ?>" <?php echo $quantity_display; ?>>
                                        <label><?php echo $attribute_str; ?></label>
                                        <div class="wpd-qty-wrapper">
                                            <input type="button" id="minus" value="-" class="minus wpc-custom-right-quantity-input-set wpc-btn-effect">
                                            <input type="number" step="<?php echo $purchase_properties["step"]; ?>" value="<?php echo $purchase_properties["min_to_purchase"]; ?>" class="wpc-custom-right-quantity-input wpd-qty" min="<?php echo $purchase_properties["min"]; ?>" max="<?php echo $purchase_properties["max"]; ?>" dntmesecondfocus="true" uprice="<?php echo $price; ?>">
                                            <input type="button" id="plus" value="+" class="plus wpc-custom-right-quantity-input-set wpc-btn-effect">
                                        </div>

                                        <div class="total-price">
                                            <?php echo $price_html; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                    }
                }
                else {
                    $purchase_properties = $this->editor->wpd_product->get_purchase_properties();
                    $price = $product->get_price()+$tpl_price;
                    $price_html = ' <span class="total_order">' . wc_price($price * $purchase_properties["min_to_purchase"]) . '</span>';

                    $quantity_display = "";
                    if ($product->is_sold_individually()) {
                        $quantity_display = "style='display: none;'";
                    }
                    ?>            
                    <div class="wpc-qty-container" data-id="<?php echo $this->editor->item_id; ?>" <?php echo $quantity_display; ?>>
                        <div class="wpd-qty-wrapper">
                        <input type="button" id="minus" value="-" class="minus wpc-custom-right-quantity-input-set wpc-btn-effect">
                        <input type="number" step="<?php echo $purchase_properties["step"]; ?>" value="<?php echo $purchase_properties["min_to_purchase"]; ?>" class="wpc-custom-right-quantity-input wpd-qty" min="<?php echo $purchase_properties["min"]; ?>" max="<?php echo $purchase_properties["max"]; ?>" dntmesecondfocus="true" uprice="<?php echo $price; ?>">
                        <input type="button" id="plus" value="+" class="plus wpc-custom-right-quantity-input-set wpc-btn-effect">
                        </div>

                        <div class="total-price">
                            <?php echo $price_html; ?>
                        </div>
                    </div>

                    <?php
                }
                do_action('wpd_cart_box', $this->editor->wpd_product);
                ?>
                <button id="add-to-cart-btn" class="wpc-btn-effect" data-id="<?php echo $this->editor->item_id ?>"><?php echo $add_to_cart_label; ?></button>
            </div>
            <?php
        }
    }

    private function register_scripts() {
        wp_enqueue_script('wpd-scrollevent', WPD_URL . 'public/js/scrollevent.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-qtip', WPD_URL . 'public/js/jquery.qtip-1.0.0-rc3.min.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-number-js', WPD_URL . 'public/js/jquery.number.min.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-fabric-js', WPD_URL . 'public/js/fabric.all.min.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-accounting-js', WPD_URL . 'public/js/accounting.min.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-editor-js', WPD_URL . 'public/js/editor.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-editor-text-controls', WPD_URL . 'public/js/editor.text.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-editor-toolbar-js', WPD_URL . 'public/js/editor.toolbar.js', array('jquery', 'wpd-editor-js'), WPD_VERSION, false);
        wp_enqueue_script('wpd-editor-shapes-js', WPD_URL . 'public/js/editor.shapes.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-accordion-js', WPD_URL . 'public/js/SpryAssets/SpryAccordion.min.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-editor-menu-js', WPD_URL . 'includes/skins/jonquet/js/jonquet.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-block-UI-js', WPD_URL . 'public/js/blockUI/jquery.blockUI.min.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-lazyload-js', WPD_URL . 'public/js/jquery.lazyload.min.js', array('jquery'), WPD_VERSION, false);
        wp_enqueue_script('wpd-editor-img-js', WPD_URL . 'public/js/editor.img.js', array('jquery', 'wpd-lazyload-js'), WPD_VERSION, false);
        wp_enqueue_script('wp-js-hooks', WPD_URL . 'public/js/wp-js-hooks.min.js', array('jquery'), WPD_VERSION, false);
        

        wpd_register_upload_scripts();
    }

    private function register_styles() {
        wp_enqueue_style("wpd-SpryAccordion-css", WPD_URL . 'public/js/SpryAssets/SpryAccordion.min.css', array(), WPD_VERSION, 'all');
        wp_enqueue_style("wpd-flexiblegs", WPD_URL . 'admin/css/flexiblegs.css', array(), WPD_VERSION, 'all');
        wp_enqueue_style("wpd-editor", WPD_URL . 'includes/skins/jonquet/css/jonquet.css', array(), WPD_VERSION, 'all');
        wp_enqueue_style("wpd-fancyselect-css", WPD_URL . 'public/css/fancySelect.min.css', array(), WPD_VERSION, 'all');
        wpd_register_fonts();
    }

}
