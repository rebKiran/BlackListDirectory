<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-wpd-product
 *
 * @author HL
 */
class WPD_Product {

    public $variation_id;
    public $root_product_id;
    public $product;
    public $settings;
    public $variation_settings;

    public function __construct($id) {
        if ($id) {
            $this->root_product_id = $this->get_parent($id);
            //If it's a variable product
            if ($id != $this->root_product_id)
                $this->variation_id = $id;
            //Simple product and others
            else
                $this->variation_id = $this->root_product_id;

            $this->product = wc_get_product($id);
            $this->settings = get_post_meta($this->root_product_id, "wpc-metas", true);
            $this->variation_settings = get_proper_value($this->settings, $this->variation_id, array());
        }
    }

    function wpc_register_product_metabox() {
        add_meta_box('customizable-product', __('Customizable Product'), array($this, 'add_customizable_meta_box'), 'product', 'side', 'default');
    }

    function add_customizable_meta_box($product) {
        $wpc_metas = get_post_meta($product->ID, 'wpc-metas', true);
        $templates_page = get_proper_value($wpc_metas, 'templates-page', "");
        $selected_options_form = get_proper_value($wpc_metas, 'ninja-form-options', "");
        $is_checked = $this->get_checkbox_value($wpc_metas, 'is-customizable', '');

        echo "<label for='is-customizable'>";
        echo "<input type='checkbox' name='wpc-metas[is-customizable]' id='is-customizable' value='1' $is_checked />Customizable product</label><br>";
        $is_checked = $this->get_checkbox_value($wpc_metas, 'can-design-from-blank', '');

        echo "<label for='can-design-from-blank'>";
        echo "<input type='checkbox' name='wpc-metas[can-design-from-blank]' id='can-design-from-blank' value='1' $is_checked />The clients can design from blank</label><br>";
        $is_checked = $this->get_checkbox_value($wpc_metas, 'can-upload-custom-design', '');

        echo "<label for='can-upload-custom-design'>";
        echo "<input type='checkbox' name='wpc-metas[can-upload-custom-design]' id='can-upload-custom-design' value='1' $is_checked />The clients can upload their designs</label><br>";
        ?>
        <div>
            <label>
                <select name="wpc-metas[templates-page]" class="mg-top-10"> 
                    <option value="">
                        <?php echo _e("Default", "wpd"); ?></option>
                    <?php
                    $args = array("post_status" => "publish");
                    $pages = get_pages($args);
                    foreach ($pages as $page) {
                        if ($templates_page == $page->ID)
                            $option = '<option value="' . $page->ID . '" selected>';
                        else
                            $option = '<option value="' . $page->ID . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    ?>
                </select>
            </label>
        </div>
        <?php
        if (function_exists('ninja_forms_get_all_forms')) {
            $forms = ninja_forms_get_all_forms();
            ?>
            <div>
                <h4><?php echo _e("Customized product options form", "wpd"); ?></h4>
                <select name="wpc-metas[ninja-form-options]" class="mg-top-10"> 
                    <option value="">
                        <?php echo _e("No option form needed", "wpd"); ?></option>
                    <?php
                    foreach ($forms as $form) {
                         if(!is_array($form))
                             continue;
                        if ($selected_options_form == $form['id'])
                            $option = '<option value="' . $form['id'] . '" selected>';
                        else
                            $option = '<option value="' . $form['id'] . '">';
                        $option .= $form['data']['form_title'];
                        $option .= '</option>';
                        echo $option;
                    }
                    ?>
                </select>
            </div>
            <?php
        }
    }

    private function get_checkbox_value($values, $search_key, $default_value) {
        if (get_proper_value($values, $search_key, $default_value) == 1)
            $is_checked = "checked='checked'";
        else
            $is_checked = "";
        return $is_checked;
    }

    function get_customizable_product_errors() {
        $post_type = get_post_type();
        if ($post_type == "product") {
            $product_id = get_the_ID();
            $wpd_product = new WPD_Product($product_id);
            $wpc_metas = get_post_meta($product_id, 'wpc-metas', true);
            if (isset($wpc_metas['is-customizable']) && !empty($wpc_metas['is-customizable'])) {
                $parts = get_option("wpc-parts");
                if (empty($parts)) {
                    $wpc_metas['is-customizable'] = "";
                    update_post_meta($product_id, 'wpc-metas', $wpc_metas);
                    ?>
                    <div class="error">
                        <p><?php _e('Error: empty product parts list. At least one part is required to create a customizable product.', 'wpd'); ?></p>
                    </div>
                    <?php
                } else if (!$wpd_product->has_part()) {
                    $wpc_metas['is-customizable'] = "";
                    update_post_meta($product_id, 'wpc-metas', $wpc_metas);
                    ?>
                    <div class="error">
                        <p><?php _e('Error: No active part defined for this product. A customizable product should have at least one part defined.', 'wpd'); ?></p>
                    </div>
                    <?php
                }
            }
        }
    }

    /**
     * Saves the product custom data
     * @param type $product_id Product ID
     */
//    function save_customizable_meta($product_id) {
//        if (isset($_POST['wpc-metas']))
//        {
//            update_post_meta($product_id, 'wpc-metas', $_POST['wpc-metas']);
//        }
//    }

    /**
     * Adds new tabs in the product page
     */
    function get_product_tab_label() {
        ?>
        <li class="wpc_canvas_tab show_if_simple"><a href="#wpc_canvas_tab_data"><?php _e('Canvas', 'wpd'); ?></a></li>
        <li class="wpc_bbox_tab show_if_simple"><a href="#wpc_bbox_tab_data"><?php _e('Bounding box', 'wpd'); ?></a></li>
        <li class="wpc_parts_tab show_if_simple"><a href="#wpc_parts_tab_data"><?php _e('Product parts', 'wpd'); ?></a></li>
        <li class="wpc_additional_price_tab"><a href="#wpc_additional_price_tab_data"><?php _e('Pricing rules', 'wpd'); ?></a></li>
        <li class="wpc_output_setting_tab show_if_simple"><a href="#wpc_output_setting_tab_data"><?php _e('Output settings', 'wpd'); ?></a></li>
        <li class="wpc_related_products_tab show_if_variable"><a href="#wpc_related_products_tab_data"><?php _e('Related Products / Quantities', 'wpd'); ?></a></li>
        <?php
    }

    /**
     * Adds the Custom column to the default products list to help identify which ones are custom
     * @param array $defaults Default columns
     * @return array
     */
    function get_product_columns($defaults) {
        $defaults['is_customizable'] = __('Custom', 'wpd');
        return $defaults;
    }

    /**
     * Sets the Custom column value on the products list to help identify which ones are custom
     * @param type $column_name Column name
     * @param type $id Product ID
     */
    function get_products_columns_values($column_name, $id) {
        if ($column_name === 'is_customizable') {
            $wpc_metas = get_post_meta($id, 'wpc-metas', true);
            $is_customizable = get_proper_value($wpc_metas, 'is-customizable', "");
            if (!empty($is_customizable))
                _e("Yes", "wpd");
            else
                _e("No", "wpd");
        }
    }

    public function is_customizable() {
        $is_customizable = get_proper_value($this->settings, 'is-customizable', "");
        return (!empty($is_customizable));
    }

    private function get_rule_tpl($params, $with_price = false, $default_param = false, $default_operator = "<", $default_value = "", $default_price = "", $default_scope = "per_item", $count = 1) {
        ob_start();
        $operators = array("<" => __("is less than", "wpd"),
            "<=" => __("is less or equal to", "wpd"),
            "==" => __("equals", "wpd"),
            ">" => __("more than", "wpd"),
            ">=" => __("more or equal to", "wpd"));
        $scopes = array(
            "item" => __("Per item", "wpd"),
            "additional-items" => __("Per additional item", "wpd"),
            "design" => __("On whole design", "wpd")
        );
        ?>
        <tr data-id="rule_{rule-group}">
            <td class="param">
                <select id="wpc-group_{rule-group}_rule_{rule-index}_param" class="select wpc-pricing-group-param" name="wpc-metas[pricing-rules][group_{rule-group}][rules][rule_{rule-index}][param]">
                    <?php
                    foreach ($params as $param_key => $param_val) {
                        if ($param_key == $default_param) {
                            ?><option value='<?php echo $param_key; ?>' selected="selected"><?php echo $param_val; ?></option><?php
                        } else {
                            ?><option value='<?php echo $param_key; ?>'><?php echo $param_val; ?></option><?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="operator">
                <select id="wpc-pricing-group_{rule-group}_rule_{rule-index}_operator" class="select" name="wpc-metas[pricing-rules][group_{rule-group}][rules][rule_{rule-index}][operator]">
                    <?php
                    foreach ($operators as $operator_key => $operator_val) {
                        if ($operator_key == $default_operator) {
                            ?><option value='<?php echo $operator_key; ?>' selected="selected"><?php echo $operator_val; ?></option><?php
                        } else {
                            ?><option value='<?php echo $operator_key; ?>'><?php echo $operator_val; ?></option><?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="value">
                <input type="text" name="wpc-metas[pricing-rules][group_{rule-group}][rules][rule_{rule-index}][value]" value="<?php echo $default_value; ?>" placeholder="number">
            </td>
            <?php
            if ($with_price) {
                ?>
                <td class="a_price" rowspan="<?php echo $count; ?>">
                    <input type="number" name="wpc-metas[pricing-rules][group_{rule-group}][a_price]" value="<?php echo $default_price; ?>" placeholder="price" step="any">
                    <select id="wpc-pricing-group_{rule-group}_rule_{rule-index}_scope" class="select" name="wpc-metas[pricing-rules][group_{rule-group}][scope]">
                        <?php
                        foreach ($scopes as $scope_key => $scope_val) {
                            if ($scope_key == $default_scope) {
                                ?><option value='<?php echo $scope_key; ?>' selected="selected"><?php echo $scope_val; ?></option><?php
                            } else {
                                ?><option value='<?php echo $scope_key; ?>'><?php echo $scope_val; ?></option><?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <?php
            }
            ?>
            <td class="add">
                <a class="wpc-add-rule button" data-group='{rule-group}'><?php echo __("and", "wpd"); ?></a>
            </td>
            <td class="remove">
                <a class="wpc-remove-rule acf-button-remove"></a>
            </td>
        </tr>
        <?php
        $rule_tpl = ob_get_contents();
        ob_end_clean();
        return $rule_tpl;
    }

    private function get_bounding_box_settings_tab($product_id) {
        $begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpd-bbox-settings',
//                        'table' => 'options',
        );

        $box_x = array(
            'title' => __('X', 'wpd'),
            'name' => "wpc-metas[$product_id][bounding_box][x]",
            'type' => 'number',
            'desc' => __('in pixels. Box X coordinate on the canvas.', 'wpd'),
        );
        $box_y = array(
            'title' => __('Y', 'wpd'),
            'name' => "wpc-metas[$product_id][bounding_box][y]",
            'type' => 'number',
            'desc' => __('in pixels. Box Y coordinate on the canvas.', 'wpd'),
        );
        $width = array(
            'title' => __('Width', 'wpd'),
            'name' => "wpc-metas[$product_id][bounding_box][width]",
            'type' => 'number',
            'desc' => __('Box width on the canvas.', 'wpd'),
        );
        $height = array(
            'title' => __('Height', 'wpd'),
            'name' => "wpc-metas[$product_id][bounding_box][height]",
            'type' => 'number',
            'desc' => __('Box height on the canvas.', 'wpd'),
        );
        $radius_rec = array(
            'title' => __('Radius (Rounded rect)', 'wpd'),
            'name' => "wpc-metas[$product_id][bounding_box][r_radius]",
            'type' => 'number',
            'default' => 0,
            'desc' => __('Box radius (used to create rounded rectangles box).', 'wpd'),
        );
        $radius_circ = array(
            'title' => __('Radius (Circle)', 'wpd'),
            'name' => "wpc-metas[$product_id][bounding_box][radius]",
            'type' => 'text',
            'default' => 0,
            'desc' => __('Box radius. Used if the box shape is a circle', 'wpd'),
        );
        $type = array(
            'title' => __('Box shape', 'wpd'),
            'name' => "wpc-metas[$product_id][bounding_box][type]",
            'type' => 'select',
            'options' => array(
                "rect" => __("Rectangle", "wpd"),
                "arc" => __("Circle", "wpd")
            ),
            'desc' => __('Shape of the bounding box', 'wpd'),
        );
        $border_color = array(
            'title' => __('Border color', 'wpd'),
            'name' => "wpc-metas[$product_id][bounding_box][border_color]",
            'type' => 'text',
            'desc' => __('Box border color', 'wpd'),
        );

        $end = array('type' => 'sectionend');
        $settings = apply_filters("wpd_bbox_settings", array(
            $begin,
            $box_x,
            $box_y,
            $width,
            $height,
            $radius_rec,
            $radius_circ,
            $type,
            $border_color,
            $end
        ));
        echo o_admin_fields($settings);
    }

    private function get_canvas_settings_tab($product_id) {
        GLOBAL $wpd_settings;
        $canvas_global_settings = get_proper_value($wpd_settings, 'wpc-general-options', array());

        $args = array("post_status" => "publish");
        $pages = get_pages($args);
        $pages_arr = array();

        foreach ($pages as $page) {
            $pages_arr[$page->ID] = $page->post_title;
        }
        $begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpd-canvas-settings',
//                        'table' => 'options',
        );

        $templates_page = array(
            'title' => __('Templates page', 'wpd'),
            'name' => "wpc-metas[$product_id][templates-page]",
            'type' => 'post-type',
            'first_value' => array(
                ""=>__("Default", "wpd")
                ),
            'args' => array("post_type"=>"page"),
        );
        $max_width = array(
            'title' => __('Canvas max width', 'wpd'),
            'name' => "wpc-metas[$product_id][canvas-w]",
            'type' => 'number',
            'desc' => __('in pixels. Canvas max width. Leave this field empty to use the value defined in the global settings.', 'wpd'),
            'default' => get_proper_value($canvas_global_settings, 'canvas-w')
        );
        $max_height = array(
            'title' => __('Canvas max height', 'wpd'),
            'name' => "wpc-metas[$product_id][canvas-h]",
            'type' => 'number',
            'desc' => __('in pixels. Canvas max width. Leave this field empty to use the value defined in the global settings.', 'wpd'),
            'default' => get_proper_value($canvas_global_settings, 'canvas-h')
        );
        $watermark = array(
            'title' => __('Watermark', 'wpd'),
            'name' => "wpc-metas[$product_id][watermark]",
            'type' => 'image',
            'desc' => 'Image which will be applied on top of all previews to prevent the copy of the designs from the website.'
        );


        $end = array('type' => 'sectionend');
        $settings = apply_filters("wpd_canvas_settings", array(
            $begin,
            $templates_page,
            $max_width,
            $max_height,
            $watermark,
            $end
                ), $product_id);
        echo o_admin_fields($settings);
    }

    private function get_parts_settings_tab($product_id) {

        $parts = get_option("wpc-parts");
        if (is_array($parts)) {
            foreach ($parts as $part) {
                $part_key = sanitize_title($part);
                $begin = array(
                    'type' => 'sectionbegin',
                    'id' => uniqid('wpd-parts-settings'),
                        //                        'table' => 'options',
                );

                $part_enabled = array(
                    'title' => $part,
                    'name' => "wpc-metas[$product_id][parts][$part_key][enabled]",
                    'type' => 'checkbox',
                    'value' => 1
//                    'desc' => __('Enable / Disable this part for this product'),
                );

                $bg_inc = array(
                    'title' => __('Background (included)', 'wpd'),
                    'name' => "wpc-metas[$product_id][parts][$part_key][bg-inc]",
                    'type' => 'image',
                    'desc' => __('Canvas background image included in the output.', 'wpd'),
                );
                $bg_not_inc = array(
                    'title' => __('Background (not included)', 'wpd'),
                    'name' => "wpc-metas[$product_id][parts][$part_key][bg-not-inc]",
                    'type' => 'image',
                    'desc' => __('Canvas background image not included in the output.', 'wpd'),
                );
                $overlay = array(
                    'title' => __('Image', 'wpd'),
                    'name' => "wpc-metas[$product_id][parts][$part_key][ov][img]",
                    'type' => 'image',
                );
                $overlay_inc = array(
                    'title' => __('Included in the output', 'wpd'),
                    'name' => "wpc-metas[$product_id][parts][$part_key][ov][inc]",
                    'type' => 'checkbox',
                    'value' => 1,
                );

                $overlay_group = array(
                    'title' => __('Overlay', 'wpd'),
                    'type' => 'groupedfields',
                    'fields' => array($overlay, $overlay_inc),
                    'desc' => __('Image which will be on top of everything  on the canvas.', 'wpd'),
                );

                $end = array('type' => 'sectionend');
                $settings = apply_filters("wpd_parts_settings", array(
                    $begin,
                    $part_enabled,
                    $bg_inc,
                    $bg_not_inc,
                    $overlay_group,
                    $end
                ));
                echo o_admin_fields($settings);
            }
        }
    }

    function get_product_tab_data() {
        $product_id = get_the_ID();
        $wpc_metas = get_post_meta($product_id, 'wpc-metas', true);
        ?>
        <div id="wpc_canvas_tab_data" class="panel woocommerce_options_panel">
            <?php
            $this->get_canvas_settings_tab($product_id);
            ?>
        </div>
        <div id="wpc_bbox_tab_data" class="panel woocommerce_options_panel">
            <?php
            $this->get_bounding_box_settings_tab($product_id);
            ?>
        </div>
        <div id="wpc_parts_tab_data" class="panel woocommerce_options_panel">
            <?php
            //$this->get_product_tab_data_content("wpc_parts_tab_data");
            //_e("Loading...", "wpd");
            $this->get_parts_settings_tab($product_id);
            ?>
        </div>
        <div id="wpc_additional_price_tab_data" class="panel woocommerce_options_panel wpc-sh-triggerable">
            <?php $this->get_rules_tab($wpc_metas); ?>

        </div>
        <div id="wpc_output_setting_tab_data" class="panel woocommerce_options_panel">
            <?php
            //$this->get_product_tab_data_content("wpc_output_setting_tab_data");
//            _e("Loading...", "wpd");
            $this->get_outputs_settings($product_id);
            ?>
        </div>
        <div id="wpc_related_products_tab_data" class="panel woocommerce_options_panel wpc-sh-triggerable">
            <div class="related-products-container">
                <?php
                $this->get_related_products_quantities_content($product_id, $wpc_metas);
                ?>
            </div>
        </div>
        <?php
    }

    function get_rules_tab($wpc_metas) {
        $params = array(
            "txt_nb_chars" => __("NB chars in text", "wpd"),
            "txt_nb_lines" => __("NB lines in text", "wpd"),
            "img_nb" => __("NB images", "wpd"),
            "path_nb" => __("NB vectors", "wpd"),
			"name_in_all_caps" => __("Name In All Caps", "wpd"),
			"font_color" => __("Font Color", "wpd")
        );
        $first_rule = $this->get_rule_tpl($params, true);
        $rule_tpl = $this->get_rule_tpl($params, false);
        ?>
        <div class='wpc-rules-table-container'>
            <textarea id='wpc-rule-tpl' style='display: none;'>
                <?php echo $rule_tpl; ?>
            </textarea>
            <textarea id='wpc-first-rule-tpl' style='display: none;'>
                <?php echo $first_rule; ?>
            </textarea>
            <?php
            $pricing_rules = array();
            if (isset($wpc_metas['pricing-rules']))
                $pricing_rules = $wpc_metas['pricing-rules'];
            if (is_array($pricing_rules) && !empty($pricing_rules)) {
                $rule_group = 0;
                foreach ($pricing_rules as $rules_group) {
                    $rule_index = 0;
                    $rules = $rules_group["rules"];
                    $a_price = $rules_group["a_price"];
                    $scope = $rules_group["scope"];
                    ?>
                    <table class="wpc-rules-table widefat">
                        <tbody>
                            <?php
                            foreach ($rules as $rule_arr) {
                                if ($rule_index == 0)
                                    $rule_html = $this->get_rule_tpl($params, true, $rule_arr["param"], $rule_arr["operator"], $rule_arr["value"], $a_price, $scope, count($rules));
                                else
                                    $rule_html = $this->get_rule_tpl($params, false, $rule_arr["param"], $rule_arr["operator"], $rule_arr["value"]);
                                $r1 = str_replace("{rule-group}", $rule_group, $rule_html);
                                $r2 = str_replace("{rule-index}", $rule_index, $r1);
                                echo $r2;
                                $rule_index++;
                            }
                            $rule_group++;
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
            }
            else {
                ?>
                <table class="wpc-rules-table widefat">
                    <tbody>
                        <?php
                        $rule_group = 0;
                        $rule_index = 0;
                        $r1 = str_replace("{rule-group}", $rule_group, $first_rule);
                        $r2 = str_replace("{rule-index}", $rule_index, $r1);
                        echo $r2;
                        ?>
                    </tbody>
                </table>
                <?php
            }
            ?>

        </div>
        <a class="button wpc-add-group mg-bot-10i">Add rule group</a>
        <?php
    }

    function get_related_products_content_ajx() {
        $product_id = $_POST["product_id"];
        $wpc_metas = get_post_meta($product_id, 'wpc-metas', true);
        $this->get_related_products_quantities_content($product_id, $wpc_metas);
        die();
    }

    private function get_related_products_quantities_content($product_id, $wpc_metas) {
        $product = wc_get_product($product_id);
        $wpd_product = new WPD_Product($product_id);
        if ($product->product_type == "variable") {
            $usable_attributes = $wpd_product->extract_usable_attributes();

            $keys = array(
                "related-products" => __("Which attributes should be used for the related products box?", "wpd"),
                "related-quantities" => __("Which attributes should be used for the related quantities box?", "wpd"));

            foreach ($keys as $key => $desc) {
                echo $desc . "<br>";

                $related_products = array();
                if (isset($wpc_metas[$key]))
                    $related_products = $wpc_metas[$key];

                foreach ($usable_attributes as $attribute_name => $attribute_data) {
                    $variation_label = $attribute_data["label"];
                    $is_checked = (in_array($attribute_data["key"], $related_products)) ? "checked='checked'" : "";
                    ?>
                    <input type="checkbox" name="wpc-metas[<?php echo $key; ?>][]" value="<?php echo $attribute_data["key"]; ?>" <?php echo $is_checked; ?>>
                    <?php echo $variation_label; ?>
                    <br>
                    <?php
                }

                echo "<br>";
            }
        } else
            echo "This feature is only available for variable products.";
    }

    public function extract_usable_attributes() {
        $product = $this->product;
        $attributes = $product->get_attributes();
        $usable_attributes = array();
        foreach ($attributes as $attribute) {
            $sanitized_name = sanitize_title($attribute["name"]);
            if ($attribute["is_visible"] && $attribute["is_variation"]) {
                if ($attribute["is_taxonomy"]) {
                    $values = wc_get_product_terms($product->id, $attribute['name'], array('fields' => 'all'));
                    $taxonomy = get_taxonomy($attribute["name"]);
                    $key = "attribute_" . $sanitized_name;
                    $usable_attributes[$attribute["name"]] = array("key" => $key, "label" => $taxonomy->labels->name, "values" => $values); //$values;
//                        var_dump($values);
                } else {
                    $key = "attribute_" . $sanitized_name;
                    // Convert pipes to commas and display values
                    $values = array_map('trim', explode(WC_DELIMITER, $attribute['value']));
                    $usable_attributes[$attribute["name"]] = array("key" => $key, "label" => $attribute["name"], "values" => $values);
                }
            }
        }

        return $usable_attributes;
    }

    private function get_outputs_settings($product_id) {
        GLOBAL $wpd_settings;
        $output_global_settings = get_proper_value($wpd_settings, 'wpc-output-options', array());

        $options = array();
        $output_w = array(
            'title' => __('Output width (px)', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][wpc-min-output-width]',
            'type' => 'text',
            'default' => get_proper_value($output_global_settings, 'wpc-min-output-width')
        );
        $output_loop_delay = array(
            'title' => __('Output loop delay (milliseconds)', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][wpc-output-loop-delay]',
            'type' => 'text',
            'default' => get_proper_value($output_global_settings, 'wpc-output-loop-delay')
        );
        $zip_folder_name = array(
            'title' => __('Zip output folder name', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][zip-folder-name]',
            'type' => 'text',
        );
        $cmyk_conversion = array(
            'title' => __('CMYK conversion (Requires ImageMagick)', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][wpc-cmyk-conversion]',
            'type' => 'radio',
            'options' => array(
                'no' => __('No', 'wpd'),
                'yes' => __('Yes', 'wpd')
            ),
            'default' => get_proper_value($output_global_settings, 'wpc-cmyk-conversion')
//            'class' => 'chosen_select_nostd'
        );

        $pdf_format = array(
            'title' => __('PDF Format', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][pdf-format]',
            'type' => 'groupedselect',
            'data-id' => $product_id,
            'options' => get_wpd_pdf_formats(),
            'class' => 'o-select2',
            'default' => get_proper_value($output_global_settings, 'pdf-format')
        );
        
        $pdf_w=array(
            'title' => __('Width', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][pdf-width]',
            'type' => 'number',
            'default' => get_proper_value($output_global_settings, 'pdf-width')
        );
        
        $pdf_h=array(
            'title' => __('Height', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][pdf-height]',
            'type' => 'number',
            'default' => get_proper_value($output_global_settings, 'pdf-height')
        );
        
        $pdf_unit=array(
            'title' => __('Unit', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][pdf-unit]',
            'type' => 'select',
            'options' => array(
                'pt'=>__('Point', 'wpd'), 
                'mm'=>__('Millimeter', 'wpd'), 
                'cm'=>__('Centimeter', 'wpd'), 
                'in'=>__('Inch', 'wpd')),
            'default' => get_proper_value($output_global_settings, 'pdf-unit')
        );
        
        $pdf_custom_dimensions=array(
            "title" => "Custom PDF dimensions",
            "desc" => __('These dimensions will only be used if the PDF format is set to Custom.', 'wpd'),
            "type" => "groupedfields",
            "fields"=> array($pdf_w, $pdf_h, $pdf_unit)
        );

        $pdf_margin_top_bottom = array(
            'title' => __('PDF Margin Top & Bottom', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][pdf-margin-tb]',
            'type' => 'text',
            'default' => get_proper_value($output_global_settings, 'pdf-margin-tb', 20)
        );

        $pdf_margin_left_right = array(
            'title' => __('PDF Margin Left & Right', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][pdf-margin-lr]',
            'type' => 'text',
            'default' => get_proper_value($output_global_settings, 'pdf-margin-lr', 20)
        );

        $pdf_orientation = array(
            'title' => __('PDF Orientation', 'wpd'),
            'name' => 'wpc-metas[' . $product_id . '][output-settings][pdf-orientation]',
            'default' => 'P',
            'type' => 'select',
            'options' => array(
                'P' => __('Portrait', 'wpd'),
                'L' => __('Landscape', 'wpd')
            ),
            'default' => get_proper_value($output_global_settings, 'pdf-orientation')
        );

        $output_options_begin = array(
            'type' => 'sectionbegin',
            'title' => __('Output Settings', 'wpd'),
            'table' => 'metas',
            'id' => 'wpc_product_output'
        );

        $output_options_end = array('type' => 'sectionend',
            'id' => 'wpc_product_output'
        );

        array_push($options, $output_options_begin);
        array_push($options, $output_w);
        array_push($options, $output_loop_delay);
        array_push($options, $cmyk_conversion);
        array_push($options, $pdf_format);
        array_push($options, $pdf_custom_dimensions);
        array_push($options, $pdf_margin_left_right);
        array_push($options, $pdf_margin_top_bottom);
        array_push($options, $pdf_orientation);
        array_push($options, $zip_folder_name);
        array_push($options, $output_options_end);
        echo o_admin_fields($options);
    }

    private function get_product_output_settings($variation_id, $attributes_str) {
        ?>
        <div class="wc-metaboxes-wrapper">
            <div class="wc-metabox">
                <h3>
                    <div class="handlediv" title="Click to toggle"></div>
                    <strong><?php echo "#$variation_id - $attributes_str"; ?></strong>
                </h3>
                <div class="wpc-output-dimensions-block">
                    <?php
                    $this->get_outputs_settings($variation_id);
                    ?>
                </div>

            </div>
        </div>
        <?php
    }

    /**
     * Checks the product contains at least one active part
     * @return boolean
     */
    public function has_part() {
        $parts = get_option("wpc-parts");
        $wc_product = wc_get_product($this->root_product_id);
        $wpc_metas = get_post_meta($this->root_product_id, 'wpc-metas', true);
        if ($wc_product->product_type == "variable") {
            $variations = $wc_product->get_available_variations();
            foreach ($variations as $variation) {
                $variation_id = $variation['variation_id'];
                foreach ($parts as $part) {
                    $part_key = sanitize_title($part);
                    if (
                            get_proper_value($wpc_metas, $variation_id, array()) && get_proper_value($wpc_metas[$variation_id], 'parts', array()) && get_proper_value($wpc_metas[$variation_id]['parts'], $part_key, array())) {
//                        $part_media_id = get_proper_value($wpc_metas[$variation_id]['parts'][$part_key], 'bg-inc', "");
                        $enabled = get_proper_value($wpc_metas[$variation_id]['parts'][$part_key], "enabled", false);
                    } else {
//                        $part_media_id = "";
                        $enabled = false;
                    }
                    if (/*                     * $part_media_id || $part_media_id == "0"||* */$enabled)
                        return true;
                }
            }
        }
        else {
            foreach ($parts as $part) {
                $part_key = sanitize_title($part);
                if (get_proper_value($wpc_metas, $this->root_product_id, array()) && get_proper_value($wpc_metas[$this->root_product_id], 'parts', array()) && get_proper_value($wpc_metas[$this->root_product_id]['parts'], $part_key, array())) {
//                    $part_media_id = get_proper_value($wpc_metas[$this->root_product_id]['parts'][$part_key], 'bg-inc', "");
                    $enabled = get_proper_value($wpc_metas[$this->root_product_id]['parts'][$part_key], "enabled", false);
                } else {
//                        $part_media_id = "";
                    $enabled = false;
                }
                if (/*                 * $part_media_id || $part_media_id == "0"||* */$enabled)
                    return true;
            }
        }
        return false;
    }

    /**
     * Returns the customization page URL
     * @global Array $wpd_settings
     * @param int $design_index Saved design index to load
     * @param mixed $cart_item_key Cart item key to edit
     * @param int $order_item_id Order item ID to load
     * @param int $tpl_id ID of the template to load
     * @return String
     */
    public function get_design_url($design_index = false, $cart_item_key = false, $order_item_id = false, $tpl_id = false) {
        GLOBAL $wpd_settings;

        if ($this->variation_id)
            $item_id = $this->variation_id;
        else
            $item_id = $this->root_product_id;

        $options = $wpd_settings['wpc-general-options'];
        $wpc_page_id = $options['wpc_page_id'];
        if (function_exists("icl_object_id")) {
            $wpc_page_id = icl_object_id($wpc_page_id, 'page', false, ICL_LANGUAGE_CODE);
        }
        $wpc_page_url = "";
        if ($wpc_page_id) {
            $wpc_page_url = get_permalink($wpc_page_id);
            if ($item_id) {
                $query = parse_url($wpc_page_url, PHP_URL_QUERY);
                // Returns a string if the URL has parameters or NULL if not
                if (get_option('permalink_structure')) {
                    if (substr($wpc_page_url, -1) != '/') {
                        $wpc_page_url .= '/';
                    }
                    if ($design_index || $design_index === 0) {
                        $wpc_page_url .= "saved-design/$item_id/$design_index/";
                    } elseif ($cart_item_key) {
                        $wpc_page_url .= "edit/$item_id/$cart_item_key/";
                    } elseif ($order_item_id) {
                        $wpc_page_url .= "ordered-design/$item_id/$order_item_id/";
                    } else {
                        $wpc_page_url .= 'design/' . $item_id . '/';
                        if ($tpl_id) {
                            $wpc_page_url .= "$tpl_id/";
                        }
                    }
                } else {
                    if ($design_index) {
                        $wpc_page_url .= '&product_id=' . $item_id . '&design_index=' . $design_index;
                    } elseif ($cart_item_key) {
                        $wpc_page_url .= '&product_id=' . $item_id . '&edit=' . $cart_item_key;
                    } elseif ($order_item_id) {
                        $wpc_page_url .= '&product_id=' . $item_id . '&oid=' . $order_item_id;
                    } else {
                        $wpc_page_url .= '&product_id=' . $item_id;
                        if ($tpl_id) {
                            $wpc_page_url .= "&tpl=$tpl_id";
                        }
                    }
                }
            }
        }

        return $wpc_page_url;
    }

    /**
     * Returns a variation root product ID
     * @param type $variation_id Variation ID
     * @return int
     */
    public function get_parent($variation_id) {
        $variable_product = wc_get_product($variation_id);
        if (!$variable_product)
            return false;
        if ($variable_product->product_type == "simple")
            $product_id = $variation_id;
        else {
            //$product_id=$variable_product->parent->id;
            $product_id = $variable_product->id;
        }

        return $product_id;
    }

    /**
     * Returns the defined value for a product setting which can be local(product metas) or global (options)
     * @param array $product_settings Product options
     * @param array $global_settings Global options
     * @param string $option_name Option name / Meta key
     * @param int $field_value Default value to return if empty
     * @return string
     */
    public function get_option($product_settings, $global_settings, $option_name, $field_value = "") {
        if (isset($product_settings[$option_name]) && !empty($product_settings[$option_name]))
            $field_value = $product_settings[$option_name];
        else if (isset($global_settings[$option_name]) && !empty($global_settings[$option_name]))
            $field_value = $global_settings[$option_name];

        return $field_value;
    }

    function set_custom_upl_cart_item_data($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
        $element_id = $product_id;
//        if (isset($variation_id) && !empty($variation_id))
//            $element_id = $variation_id;

        if (isset($_SESSION["wpc-user-uploaded-designs"][$element_id])) {
            $cart_item_data["variation"] = "wpc-custom-upl";
            if (!isset($_SESSION["wpc-uploaded-designs"][$cart_item_key]))
                $_SESSION["wpc-uploaded-designs"][$cart_item_key] = array();
            array_push($_SESSION["wpc-uploaded-designs"][$cart_item_key], $_SESSION["wpc-user-uploaded-designs"][$element_id]);
            unset($_SESSION["wpc-user-uploaded-designs"][$element_id]);
        }
        if (!isset($_SESSION["wpc_design_pricing_options"]))
            $_SESSION["wpc_design_pricing_options"] = array();

        if (isset($_POST['wpd-design-opt']))
            $_SESSION["wpc_design_pricing_options"][$cart_item_key] = $_POST['wpd-design-opt'];
    }

    /**
     * Returns the minimum and maximum order quantities
     * @return type
     */
    function get_purchase_properties() {
        if ($this->variation_id) {
            $defined_min_qty = get_post_meta($this->variation_id, 'variation_minimum_allowed_quantity', true);
            //We consider the values defined for the all of them
            if (!$defined_min_qty)
                $defined_min_qty = get_post_meta($this->root_product_id, 'minimum_allowed_quantity', true);

            if (!$defined_min_qty)
                $defined_min_qty = 0;

            $defined_max_qty = get_post_meta($this->variation_id, 'variation_maximum_allowed_quantity', true);
            //We consider the values defined for the all of them
            if (!$defined_max_qty)
                $defined_max_qty = get_post_meta($this->root_product_id, 'maximum_allowed_quantity', true);
        }
        else {
            $defined_min_qty = get_post_meta($this->root_product_id, 'minimum_allowed_quantity', true);
            if (!$defined_min_qty)
                $defined_min_qty = 0;

            $defined_max_qty = get_post_meta($this->root_product_id, 'variation_maximum_allowed_quantity', true);
        }


        $step = apply_filters('woocommerce_quantity_input_step', '1', $this->product);
        $min_qty = apply_filters('woocommerce_quantity_input_min', $defined_min_qty, $this->product);

        if (!$defined_max_qty)
            $defined_max_qty = apply_filters('woocommerce_quantity_input_max', $this->product->backorders_allowed() ? '' : $this->product->get_stock_quantity(), $this->product);

        $min_to_purchase = $min_qty;
        if (!$min_qty)
            $min_to_purchase = 1;

        return array(
            "min" => $min_qty,
            "min_to_purchase" => $min_to_purchase,
            "max" => $defined_max_qty,
            "step" => $step
        );
    }

    function get_related_product_desc() {
        $purchase_properties = $this->get_purchase_properties();
        return __("Requires a minimum purchase of ", "wpd") . $purchase_properties["min"] . __(" item(s).", "wpd");
    }

    function get_variable_products_settings($loop, $variation_data, $variation) {
        $tab_id = uniqid();
//        var_dump($loop);
//        var_dump($variation_data);
//        var_dump($variation);
        ?>
        <div id="<?php echo $tab_id; ?>" class="TabbedPanels VTabbedPanels wpd-variation-settings">
            <ul class="TabbedPanelsTabGroup ">
                <li class="TabbedPanelsTab " tabindex="1"><span><?php _e('Canvas', 'wpd'); ?></span> </li>
                <li class="TabbedPanelsTab " tabindex="2"><span><?php _e('Bounding box', 'wpd'); ?></span> </li>
                <li class="TabbedPanelsTab" tabindex="3"><span><?php _e('Product parts', 'wpd'); ?></span></li>
                <li class="TabbedPanelsTab" tabindex="4"><span><?php _e('Output settings', 'wpd'); ?></span></li>
            </ul>

            <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent">
                    <?php $this->get_canvas_settings_tab($variation->ID); ?>
                </div>
                <div class="TabbedPanelsContent">
                    <?php $this->get_bounding_box_settings_tab($variation->ID); ?>
                </div>
                <div class="TabbedPanelsContent">
                    <?php $this->get_parts_settings_tab($variation->ID); ?>
                </div>
                <div class="TabbedPanelsContent">
                    <?php $this->get_outputs_settings($variation->ID); ?>
                </div>

            </div>
        </div>
        <?php
    }

    public function save_product_settings_fields($item_id) {
        $meta_key = "wpc-metas";
        if (isset($_POST[$meta_key])) {
//           var_dump($_POST[$meta_key]);
//           echo "<hr>";
            $variation = wc_get_product($item_id);
            //If we're dealing with a variation, Product ID is the root ID of the product
            if (get_class($variation) == "WC_Product_Variation")
                $product_id = $variation->parent->id;
            else
                $product_id = $item_id;
            //Careful this hooks only send the updated data, not the complete form
            $old_metas = get_post_meta($product_id, $meta_key, true);
            if (empty($old_metas))
                $old_metas = array();
            $new_metas = array_replace($old_metas, $_POST[$meta_key]);

            //If the related products and quantities are not in the post variable, that means the user is disabling them
            if (!isset($_POST[$meta_key]["related-products"]))
                $new_metas["related-products"] = array();
            if (!isset($_POST[$meta_key]["related-quantities"]))
                $new_metas["related-quantities"] = array();
            if (!isset($_POST[$meta_key]['can-upload-custom-design']))
                $new_metas['can-upload-custom-design'] = '';
            if (!isset($_POST[$meta_key]['can-design-from-blank']))
                $new_metas['can-design-from-blank'] = '';
            if (!isset($_POST[$meta_key]['is-customizable']))
                $new_metas['is-customizable'] = '';
            update_post_meta($product_id, $meta_key, $new_metas);
        }
    }

    public function get_custom_products_body_class($classes, $class) {
        if (is_singular(array("product"))) {
            GLOBAL $wpd_settings;
            $general_options = $wpd_settings['wpc-general-options'];
            $hide_cart_button = get_proper_value($general_options, 'wpd-hide-cart-button', true);
            $pid = get_the_ID();
            $product = new WPD_Product($pid);
            if ($product->is_customizable()) {
//                var_dump($hide_cart_button);
                array_push($classes, "wpd-is-customizable");
                if ($hide_cart_button)
                    array_push($classes, "wpd-hide-cart-button");
            }
        }
        return $classes;
    }

    /**
     * Returns the templates page URL
     * @return string
     */
    public function get_templates_page_url() {

        if ($this->variation_id)
            $item_id = $this->variation_id;
        else
            $item_id = $this->root_product_id;

        $wpd_templates_id = $this->get_templates_page_id();
        if (empty($wpd_templates_id))
            return false;

//        $wpd_templates_url = get_permalink($wpd_templates_id);
        $wpd_templates_url=  add_query_arg( 'for-product', $this->variation_id, get_permalink($wpd_templates_id) );
//        if ($item_id) {
//            $query = parse_url($wpd_templates_url, PHP_URL_QUERY);
//            // Returns a string if the URL has parameters or NULL if not
//            if (get_option('permalink_structure')) {
//                if (substr($wpd_templates_url, -1) != '/') {
//                    $wpd_templates_url .= '/';
//                }
//
//                $wpd_templates_url .= "for-product/$item_id/";
//            } else {
//                $wpd_templates_url .= '&for-product=' . $item_id;
//            }
//        }

        return $wpd_templates_url;
    }

    /**
     * Returns the templates page ID
     * @global Array $wpd_settings
     * @return int
     */
    function get_templates_page_id() {
        global $wpd_settings;
        $general_options = $wpd_settings['wpc-general-options'];
        $global_templates_page_id = get_proper_value($general_options, "wpd-templates-page");
        $product_templates_page_id = get_proper_value($this->settings, 'templates-page', $global_templates_page_id);
        if(empty($product_templates_page_id))
            $product_templates_page_id=$global_templates_page_id;
        if (!empty($product_templates_page_id) && function_exists("icl_object_id")) {
            $product_templates_page_id = icl_object_id($product_templates_page_id, 'page', false, ICL_LANGUAGE_CODE);
        }

        return $product_templates_page_id;
    }

    public function get_templates() {
        global $wpdb;
        if ($this->variation_id)
            $item_id = $this->variation_id;
        else
            $item_id = $this->root_product_id;
        $search = '"' . $item_id . '"';
        $templates = $wpdb->get_results(
                "
                           SELECT p.id
                           FROM $wpdb->posts p
                           JOIN $wpdb->postmeta pm on pm.post_id = p.id 
                           WHERE p.post_type = 'wpc-template'
                           AND (
                                    (pm.meta_key = 'base-product') AND (pm.meta_value ='$item_id')
                                    OR
                                    (pm.meta_key = 'base-product-alt') AND (pm.meta_value like '%$search%')
                                )
                           ");
        return $templates;
    }

    function get_buttons($with_upload=false) {
        ob_start();
        $product = $this->product;
        $wpc_metas = $this->settings;
        $product_page = get_permalink($product->id);
        
        if ($this->variation_id)
            $item_id = $this->variation_id;
        else
            $item_id = $this->root_product_id;
        
        if (!isset($wpc_metas['is-customizable']) || empty($wpc_metas['is-customizable']))
        {
            $output=  ob_get_clean();
            return $output;
        }

        wpd_generate_design_buttons_css();

        if ($product->product_type == 'variable') {
            $variations = $product->get_available_variations();
            foreach ($variations as $variation) {
                $wpd_product=new WPD_Product($variation["variation_id"]);
                echo $wpd_product->get_buttons($with_upload);
            }
            
        } else {
            ?>
                    <div class="wpd-buttons-wrap-<?php echo $product->product_type;?>" data-id="<?php echo $this->variation_id;?>">
            <?php
            //Design from blank
            if (isset($wpc_metas['can-design-from-blank'])&&!empty($wpc_metas['can-design-from-blank']))
            {
                $design_from_blank_url = $this->get_design_url();
                echo '<a  href="' . $design_from_blank_url . '" class="mg-top-10 wpc-customize-product">' . __("Design from blank", "wpd") . '</a>';
            }
            
            //Templates
            $templates = $this->get_templates();
            $templates_page = $this->get_templates_page_url();
            if (count($templates) && $templates_page) {
                if(count($templates)==1)
                {
                    $wpd_product=new WPD_Product($item_id);
                    $template_id=$templates[0]->id;
                    $customize_url = $wpd_product->get_design_url(false, false, false, $template_id);
                    $templates_btn = '<a href="' . $customize_url . '" data-id="' . $product->id . '" data-type="' . $product->product_type . '" class="btn-choose tpl">' . __("Customize", "wpd") . '</a>';
                }
                else
                    $templates_btn = '<a href="' . $templates_page . '" data-id="' . $product->id . '" data-type="' . $product->product_type . '" class="btn-choose tpl">' . __("Browse our templates", "wpd") . '</a>';
                echo apply_filters('wpd_browse_our_template_button', $templates_btn, $this);
            }
            
            //Upload my own design
            if (isset($wpc_metas['can-upload-custom-design'])&&!empty($wpc_metas['can-upload-custom-design']))
            {
                //To avoid having an upload form on the shop/categories pages
                if($with_upload)
                {
                    $modal_id = uniqid("wpc-modal");
                    ?><a data-id="<?php echo $item_id; ?>" data-type="<?php echo $product->product_type; ?>" data-toggle="o-modal" data-target="#<?php echo $modal_id; ?>" class="mg-top-10 wpc-upload-product-design"><?php _e("Upload my own design", "wpd"); ?></a><?php
                    $modals = '<div class="omodal fade wpc-modal wpc_part" id="' . $modal_id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="omodal-dialog">
                                          <div class="omodal-content">
                                            <div class="omodal-header">
                                              <button type="button" class="close" data-dismiss="omodal" aria-hidden="true">&times;</button>
                                              <h4 class="omodal-title">'.__("Pick a file", "wpd").'</h4>
                                            </div>
                                            <div class="omodal-body txt-center">
                                                ' . WPD_Design::get_custom_design_upload_form() . '
                                            </div>
                                          </div>
                                        </div>
                                      </div>';
                    array_push(wpd_retarded_actions::$code, $modals);
                    add_action('wp_footer', array('wpd_retarded_actions', 'display_code'), 10, 1);
                }
                else
                {
                    echo '<a href="' . $product_page . '" class="btn-choose custom mg-top-10 wpc-upload-product-design"> ' . __("Upload my own design", "wpd") . '</a>';
                }
            }
            ?>
                    </div>
            <?php
        }
        
        $output=  ob_get_clean();
        return $output;

    }

}
