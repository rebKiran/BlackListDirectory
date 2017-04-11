<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpd
 * @subpackage Wpd/admin
 * @author     ORION <support@orionorigin.com>
 */
class WPD_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    3.0
     * @access   private
     * @var      string    $wpd    The ID of this plugin.
     */
    private $wpd;

    /**
     * The version of this plugin.
     *
     * @since    3.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    3.0
     * @param      string    $wpd       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($wpd, $version) {

        $this->wpd = $wpd;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    3.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wpd_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wpd_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->wpd, plugin_dir_url(__FILE__) . 'css/wpd-admin.css', array(), $this->version, 'all');
        wp_enqueue_style("wpd-simplegrid", plugin_dir_url(__FILE__) . 'css/simplegrid.min.css', array(), $this->version, 'all');
        wp_enqueue_style("wpd-common", WPD_URL . 'public/css/wpd-common.css', array(), $this->version, 'all');
        wp_enqueue_style("wpd-tooltip-css", plugin_dir_url(__FILE__) . 'css/tooltip.min.css', array(), $this->version, 'all');
        wp_enqueue_style("wpd-colorpicker-css", plugin_dir_url(__FILE__) . 'js/colorpicker/css/colorpicker.css', array(), $this->version, 'all');
        wp_enqueue_style("wpd-o-ui", plugin_dir_url(__FILE__) . 'css/UI.css', array(), $this->version, 'all');
        wp_enqueue_style("wpd-bs-modal-css", WPD_URL . 'public/js/modal/modal.min.css', array(), $this->version, 'all');
        wp_enqueue_style("wpd-datatables-css", WPD_URL . 'admin/js/datatables/jquery.dataTables.min.css', array(), $this->version, 'all');
        wp_enqueue_style("select2-css", plugin_dir_url(__FILE__) . 'css/select2.min.css', array(), $this->version, 'all');
        wp_enqueue_style("o-flexgrid", plugin_dir_url(__FILE__) . 'css/flexiblegs.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    3.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script('wpd-tabs-js', plugin_dir_url(__FILE__) . 'js/SpryTabbedPanels.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('wpd-tooltip-js', plugin_dir_url(__FILE__) . 'js/tooltip.js', array('jquery'), $this->version, false);
        wp_enqueue_script('wpd-colorpicker-js', plugin_dir_url(__FILE__) . 'js/colorpicker/js/colorpicker.js', array('jquery'), $this->version, false);
        wp_enqueue_script('wpd-modal-js', WPD_URL . 'public/js/modal/modal.js', array('jquery'), false, false);
        wp_enqueue_script($this->wpd, plugin_dir_url(__FILE__) . 'js/wpd-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->wpd, 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('wpd-jquery-cookie-js', plugin_dir_url(__FILE__) . 'js/jquery.cookie.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('wpd-datatable-js', plugin_dir_url(__FILE__) . 'js/datatables/jquery.dataTables.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script("o-admin", plugin_dir_url(__FILE__) . 'js/o-admin.js', array('jquery', 'jquery-ui-sortable'), $this->version, false);
        wp_localize_script("o-admin", 'home_url', home_url("/"));
        wp_enqueue_script('select2-js', plugin_dir_url(__FILE__) . 'js/select2.min.js', array(), $this->version, 'all');
    }

    /**
     * Builds all the plugin menu and submenu
     */
    public function add_woo_parts_submenu() {
        $icon = WPD_URL . 'admin/images/wpc-dashicon.png';
        add_menu_page('Woocommerce Product Designer', 'WPD', 'manage_product_terms', 'wpc-manage-dashboard', array($this, 'get_parts_page'), $icon);
        add_submenu_page('wpc-manage-dashboard', __('Parts', 'wpd'), __('Parts', 'wpd'), 'manage_product_terms', 'wpc-manage-parts', array($this, 'get_parts_page'));
        add_submenu_page('wpc-manage-dashboard', __('Fonts', 'wpd'), __('Fonts', 'wpd'), 'manage_product_terms', 'wpc-manage-fonts', array($this, 'get_fonts_page'));
        add_submenu_page('wpc-manage-dashboard', __('Cliparts', 'wpd'), __('Cliparts', 'wpd'), 'manage_product_terms', 'edit.php?post_type=wpc-cliparts', false);
        add_submenu_page('wpc-manage-dashboard', __('Templates', 'wpd'), __('Templates', 'wpd'), 'manage_product_terms', 'edit.php?post_type=wpc-template', false);
        add_submenu_page('wpc-manage-dashboard', __('Templates categories', 'wpd'), __('Templates categories', 'wpd'), 'manage_product_terms', 'edit-tags.php?taxonomy=wpc-template-cat', false);
        add_submenu_page('wpc-manage-dashboard', __('Settings', 'wpd'), __('Settings', 'wpd'), 'manage_product_terms', 'wpc-manage-settings', array($this, 'get_settings_page'));
        add_submenu_page('wpc-manage-dashboard', __('Bulk definition', 'wpd'), __('Bulk definition', 'wpd'), 'manage_product_terms', 'wpd-bulk-definition', array($this, 'get_bulk_definition_page'));
        add_submenu_page('wpc-manage-dashboard', __('Get Started', 'wpd'), __('Get Started', 'wpd'), 'manage_product_terms', 'wpc-about', array($this, "get_about_page"));
    }

    /**
     * Builds the parts management page
     */
    function get_parts_page() {
        include_once( WPD_DIR . '/includes/wpd-add-parts.php' );
        woocommerce_add_parts();
    }

    /**
     * Builds the fonts management page
     */
    function get_fonts_page() {
        include_once( WPD_DIR . '/includes/wpd-add-fonts.php' );
        woocommerce_add_fonts();
    }

    /**
     * Initialize the plugin sessions
     */
    function init_sessions() {
        if (!session_id()) {
            session_start();
        }

        if (!isset($_SESSION["wpc_generated_data"]))
            $_SESSION["wpc_generated_data"] = array();
        if (!isset($_SESSION["wpd-data-to-load"]))
            $_SESSION["wpd-data-to-load"] = "";
	
        $_SESSION["wpd_calculated_totals"]=FALSE;
    }

    /**
     * Runs the new version check and upgrade process
     * @return \WPD_Updater
     */
    function get_updater() {
        do_action('wpd_before_init_updater');
        require_once( WPD_DIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'updaters' . DIRECTORY_SEPARATOR . 'class-wpd-updater.php' );
        $updater = new WPD_Updater();
        $updater->init();
        require_once( WPD_DIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'updaters' . DIRECTORY_SEPARATOR . 'class-wpd-updating-manager.php' );
        $updater->setUpdateManager(new WPD_Updating_Manager(WPD_VERSION, $updater->versionUrl(), WPD_MAIN_FILE));
        do_action('wpd_after_init_updater');
        return $updater;
    }

    /**
     * Redirects the plugin to the about page after the activation
     */
    function wpc_redirect() {
        if (get_option('wpc_do_activation_redirect', false)) {
            delete_option('wpc_do_activation_redirect');
            wp_redirect(admin_url('admin.php?page=wpc-about'));
        }
    }

    function get_bulk_definition_page() {
        $args = array(
            'post_type' => 'product',
            'meta_key' => 'wpc-metas',
            'posts_per_page' => -1,
            'numberposts' => -1
        );
        $source_products = get_posts($args);

        $args2 = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'numberposts' => -1
        );
        $all_products = get_posts($args2);
        ?>
        <div id='wpd-bulk-definition-page'>
            <div class="wrap">
                <H2><?php echo __("Bulk parameters definition", "wpd"); ?></H2>
                <?php
                if (!empty($_POST)) {
//                    var_dump($_POST);
                    $this->handle_bulk_definition($_POST);
                }
                ?>

                <div class="mg-top-20">
                    <?php _e("This page will allows you to extract the defined parameters from on a product and apply them on others.", "wpd"); ?>
                </div>
                <div>
                    <form method="POST">
                        <div class="mg-top-20">
                            <label>Data Source</label>
                            <select name="datasource">
                                <?php
                                foreach ($source_products as $source_product) {
                                    $product_obj = wc_get_product($source_product->ID);
                                    $wpc_metas = get_post_meta($source_product->ID, 'wpc-metas', true);
                                    if (!isset($wpc_metas['is-customizable']) || empty($wpc_metas['is-customizable']))
                                        continue;
                                    if ($product_obj->product_type == "simple") {
                                        echo "<option value='$source_product->ID'>$source_product->post_title</option>";
                                    } else {
                                        echo "<optgroup label='$source_product->post_title'>";
                                        $variations = $product_obj->get_available_variations();
                                        foreach ($variations as $variation) {
                                            $variation_id = $variation['variation_id'];
                                            $attributes = $variation["attributes"];
                                            $attributes_str = "";
                                            foreach ($attributes as $attribute) {
                                                $attributes_str.=" " . ucfirst($attribute);
                                            }

                                            echo "<option value='$variation_id'>$attributes_str</option>";
                                        }
                                        echo "</optgroup>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mg-top-20">
                            <strong><?php _e("What do you want to extract?", "wpd"); ?></strong>
                        </div>
                        <div class="mg-top-20">
                            <label>
                                <input type="checkbox" name="design-buttons"> <?php _e("Design buttons parameters", "wpd"); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="bounding-box"> <?php _e("Bounding box parameters", "wpd"); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="products-parts"> <?php _e("Products parts parameters", "wpd"); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="pricing-rules"> <?php _e("Pricing rules parameters", "wpd"); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="output-settings"> <?php _e("Output settings parameters", "wpd"); ?>
                            </label>
                        </div>
                        <div class="mg-top-20">
                            <strong><?php _e("On which products do you want to apply the parameters?", "wpd"); ?></strong>
                            <div class="mg-top-20">
                                <table class="datatable wp-list-table widefat fixed striped" id="bulk-definition-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;"><input type="checkbox" id="wpd-check-all-products"></th>
                                            <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($all_products as $product) {
                                            $product_obj = wc_get_product($product->ID);
                                            if ($product_obj->product_type == "simple") {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" value="<?php echo $product->ID; ?>" name="apply_to[<?php echo $product->ID; ?>]">
                                                    </td>
                                                    <td> <?php echo $product->post_title; ?></td>
                                                </tr>
                                                <?php
                                            } else if ($product_obj->product_type == "variable") {
                                                $variations = $product_obj->get_available_variations();
                                                foreach ($variations as $variation) {
                                                    $variation_id = $variation['variation_id'];
                                                    $attributes = $variation["attributes"];
                                                    $attributes_str = "";
                                                    foreach ($attributes as $attribute) {
                                                        $attributes_str.=" " . ucfirst($attribute);
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" value="<?php echo $variation_id; ?>" name="apply_to[<?php echo $product->ID; ?>][]">
                                                        </td>
                                                        <td> <?php echo $product->post_title . " $attributes_str"; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="submit" class="button button-primary button-large mg-top-20-i" value="<?php _e("save", "wpd"); ?>">
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Builds the about page
     */
    function get_about_page() {
        $wpc_logo = WPD_URL . 'admin/images/wpc.jpg';
        $img1 = WPD_URL . 'admin/images/install-demo-package.jpg';
        $img2 = WPD_URL . 'admin/images/set-basic-settings.jpg';
        $img3 = WPD_URL . 'admin/images/create-customizable-product.jpg';
        $img4 = WPD_URL . 'admin/images/manage-templates.jpg';
        ?>
        <div id='wpd-about-page'>
            <div class="about-heading">
                <div>
                    <H2><?php echo __("Welcome to WooCommerce Products Designer", "wpd") . " " . WPD_VERSION; ?></H2>
                    <H4><?php printf(__("Thanks for installing! WooCommerce Products Designer %s is more powerful, stable and secure than ever before. We hope you enjoy using it.", "wpd"), WPD_VERSION); ?></H4>
                </div>
                <div class="about-logo">
                    <img src="<?php echo $wpc_logo; ?>" />
                </div>
            </div>
            <div class="about-button">
                <div><a href="<?php echo admin_url('admin.php?page=wpc-manage-settings'); ?>" class="button">Settings</a></div>
                <div><a href="<?php echo WPD_URL . 'User_manual.pdf'; ?>" class="button">Docs</a></div>
            </div>

            <div id="TabbedPanels1" class="TabbedPanels">
                <ul class="TabbedPanelsTabGroup ">
                    <li class="TabbedPanelsTab " tabindex="4"><span><?php _e('Getting Started', 'wpd'); ?></span> </li>
                    <li class="TabbedPanelsTab" tabindex="5"><span><?php _e('Changelog', 'wpd'); ?> </span></li>
                    <li class="TabbedPanelsTab" tabindex="6"><span><?php _e('Follow Us', 'wpd'); ?></span></li>
                </ul>

                <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent">
                        <div class='wpc-grid wpc-grid-pad'>
                            <div class="wpc-col-3-12">
                                <div class="product-container">
                                    <a href="https://youtu.be/RXu3j4jK6lA?list=PLC9GLMXokPgVvkg4AsgTJwu3ax4cBi1OC" target="blank">
                                        <div class="img-container"><img src="<?php echo $img1; ?>"></div>
                                        <div class="img-title"><?php _e('How to install the demo package?', 'wpd'); ?></div>
                                    </a>
                                </div>
                            </div>
                            <div class="wpc-col-3-12">
                                <div class="product-container">
                                    <a href="https://youtu.be/rpf7XfR8ZXQ?list=PLC9GLMXokPgVvkg4AsgTJwu3ax4cBi1OC" target="blank">
                                        <div class="img-container"><img src="<?php echo $img2; ?>"></div>
                                        <div class="img-title"><?php _e('How to set the basic settings?', 'wpd'); ?></div>
                                    </a>
                                </div>
                            </div>
                            <div class="wpc-col-3-12">
                                <div class="product-container">
                                    <a href="https://youtu.be/j0mnfyy4JWg?list=PLC9GLMXokPgVvkg4AsgTJwu3ax4cBi1OC" target="blank">
                                        <div class="img-container"><img src="<?php echo $img3; ?>"></div>
                                        <div class="img-title"><?php _e('How to create a customizable product?', 'wpd'); ?></div>
                                    </a>
                                </div>
                            </div>
                            <div class="wpc-col-3-12">
                                <div class="product-container">
                                    <a href="https://youtu.be/LOUReg8UEY4?list=PLC9GLMXokPgVvkg4AsgTJwu3ax4cBi1OC" target="blank">
                                        <div class="img-container"><img src="<?php echo $img4; ?>"></div>
                                        <div class="img-title"><?php _e('How to manage your designs templates?', 'wpd'); ?></div>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="TabbedPanelsContent">
                        <div class='wpc-grid wpc-grid-pad'>
                            <?php
                            $file_path = WPD_DIR . "/changelog.txt";
                            $myfile = fopen($file_path, "r") or die(__("Unable to open file!", "wpd"));
                            while (!feof($myfile)) {
                                $line_of_text = fgets($myfile);
                                if (strpos($line_of_text, 'Version') !== false)
                                    print '<b>' . $line_of_text . "</b><BR>";
                                else
                                    print $line_of_text . "<BR>";
                            }
                            fclose($myfile);
                            ?>
                        </div>
                    </div>
                    <div class="TabbedPanelsContent">
                        <div class="wpc-grid wpc-grid-pad follow-us">
                            <div class="wpc-col-6-12 ">
                                <h3>Why?</h3>
                                <ul class="follow-us-list">
                                    <li>
                                        <a href="#">
                                            <span class="rs-ico"><img src="<?php echo WPD_URL; ?>/admin/images/love.png"></span>
                                            <span>
                                                <h4 class="title"> Show us some love of course!</h4>
                                                You like our product and you tried it. Cool! Then give us some boost by sharing it with friends or making interesting comments on our pages!
                                            </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <span class="rs-ico"><img src="<?php echo WPD_URL; ?>/admin/images/update.png"></span>
                                            <span>
                                                <h4 class="title"> Receive regular updates from us on our products.</h4>
                                                This is the best way to enjoy the full of the news features added to our plugins. 
                                            </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <span class="rs-ico"><img src="<?php echo WPD_URL; ?>/admin/images/features.png"></span>
                                            <span>
                                                <h4 class="title"> Suggest new features for the products you're interested in.</h4>
                                                One of our products arouses your interest but it’s not exactly what you want. If only some features can be added… You know what? Actually it’s possible! Just leave your suggestion and we’ll do our best! 
                                            </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <span class="rs-ico"><img src="<?php echo WPD_URL; ?>/admin/images/bug.png"></span>
                                            <span>
                                                <h4 class="title"> Become a beta tester for our pre releases.</h4>
                                                For each couple of feature up-coming we need beta tester to improve the final product we are about to propose. So if you want to be part of this, freely apply here.
                                            </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <span class="rs-ico"><img src="<?php echo WPD_URL; ?>/admin/images/free.png"></span>
                                            <span>
                                                <h4 class="title"> Access our freebies collection anytime.</h4>
                                                Find the coolest free collection of our plugins and make the most of it!
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div> 
                            <div id="separator"></div>
                            <div class="wpc-col-6-12 ">
                                <h3>How?</h3>
                                <div class="follow-us-text">
                                    <div>
                                        Easy!! Just access our social networks pages and follow/like us. Yeah just like that :).
                                    </div>

                                    <div class="btn-container">
                                        <a href="http://twitter.com/OrionOrigin" target="blank" style="display: inline-block;">
                                            <span class="rs-ico"><img src="<?php echo WPD_URL; ?>/admin/images/twitter.png"></span>
                                        </a>
                                        <a href="https://www.facebook.com/OrionOrigin" target="blank" style="display: inline-block;">
                                            <span class="rs-ico"><img src="<?php echo WPD_URL; ?>/admin/images/facebook-about.png"></span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div> 
                    </div>

                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Gets the settings and put them in a global variable
     * @global array $wpd_settings Settings
     */
    function init_globals() {
        GLOBAL $wpd_settings;
        $wpd_settings['wpc-general-options'] = get_option("wpc-general-options");
        $wpd_settings['wpc-texts-options'] = get_option("wpc-texts-options");
        $wpd_settings['wpc-shapes-options'] = get_option("wpc-shapes-options");
        $wpd_settings['wpc-images-options'] = get_option("wpc-images-options");
        $wpd_settings['wpc-designs-options'] = get_option("wpc-designs-options");
        $wpd_settings['wpc-colors-options'] = get_option("wpc-colors-options");
        $wpd_settings['wpc-output-options'] = get_option("wpc-output-options");
        $wpd_settings['wpc_social_networks'] = get_option("wpc_social_networks");
        $wpd_settings['wpc-upload-options'] = get_option("wpc-upload-options");
        $wpd_settings['wpc-licence'] = get_option("wpc-licence");
        $wpd_settings['wpc-ui-options'] = get_option("wpc-ui-options");
    }

    private function get_admin_option_field($title, $option_group, $field_name, $type, $default, $class, $css, $tip, $options_array) {
        $field = array(
            'title' => __($title, 'wpd'),
            'name' => $option_group . '[' . $field_name . ']',
            'type' => $type,
            'default' => $default,
            'class' => $class,
            'css' => $css,
            'desc' => __($tip, 'wpd')
        );
        if (!empty($options_array))
            $field['options'] = $options_array;
        return $field;
    }

    /**
     * Callbacks which prints the icon selector field
     * @param type $field Field to print
     */
    public function get_icon_selector_field($field) {
        echo $field["value"];
    }

    private function get_admin_color_field($group_option, $prefix = "") {
        if (!empty($prefix)) {
            return array(
                'label-color' => get_proper_value($group_option, $prefix . '-label-color', ""),
                'normal-color' => get_proper_value($group_option, $prefix . '-normal-color', ""),
                'selected-color' => get_proper_value($group_option, $prefix . '-selected-color')
            );
        } else {
            return array(
                'label-color' => get_proper_value($group_option, 'label-color', ""),
                'normal-color' => get_proper_value($group_option, 'normal-color', ""),
                'selected-color' => get_proper_value($group_option, 'selected-color', "")
            );
        }
    }

    /**
     * Builds the general settings options
     * @return array Settings
     */
    public function get_front_tools_settings() {

        $options = array();
        $defaults_text_fields = array();
        $defaults_shape_fields = array();

        $this->get_skins_settings();

        $this->get_toolbar_icons_settings($options);

        $this->get_toolbar_colors_settings($options);

        $this->get_features_icons_settings($options);

        $this->get_features_colors_settings($options);

        $this->get_separators_colors_settings($options);

        $this->get_buttons_colors_settings($options);


        $actions_options_begin = array(
            'type' => 'sectionbegin',
            'title' => __('Editor colors', 'wpd'),
            'id' => 'wpc-interface-colors',
            'table' => 'options'
        );
        $actions_options_end = array('type' => 'sectionend');

        $color_grouped_fields = $this->get_interface_color_fields();

        $text_default_color_field = $this->get_admin_option_field("Text", "wpc-ui-options", "default-text-color", 'text', '#4f71b9', 'wpc-color', '', '', '');
        $bg_default_color_field = $this->get_admin_option_field("Background", "wpc-ui-options", "default-background-color", 'text', '#4f71b9', 'wpc-color', '', '', '');
        $outline_bg_default_color_field = $this->get_admin_option_field("Outline", "wpc-ui-options", "default-outline-background-color", 'text', '#4f71b9', 'wpc-color', '', '', '');
        array_push($defaults_text_fields, $text_default_color_field);
        array_push($defaults_text_fields, $bg_default_color_field);
        array_push($defaults_text_fields, $outline_bg_default_color_field);
        $shape_default_color_field = $this->get_admin_option_field("Background", "wpc-ui-options", "default-shape-background-color", 'text', '#4f71b9', 'wpc-color', '', '', '');
        $shape_outline_bg_default_color_field = $this->get_admin_option_field("Outline", "wpc-ui-options", "default-shape-outline-background-color", 'text', '#4f71b9', 'wpc-color', '', '', '');
        array_push($defaults_shape_fields, $shape_default_color_field);
        array_push($defaults_shape_fields, $shape_outline_bg_default_color_field);
        $default_text_colors = array(
            'title' => __("Default Text Colors", "wpd"),
            'type' => 'groupedfields',
            'fields' => $defaults_text_fields
        );
        $default_shape_colors = array(
            'title' => __("Default Shape Colors", "wpd"),
            'type' => 'groupedfields',
            'fields' => $defaults_shape_fields
        );
        array_push($options, $actions_options_begin);
//        $options = array_merge($options, $color_grouped_fields);
        array_push($options, $default_text_colors);
        array_push($options, $default_shape_colors);
        array_push($options, $actions_options_end);

        echo o_admin_fields($options);
    }

    private function get_toolbar_colors_settings(&$options) {
        $options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc_toolbar_colors_options',
            'table' => 'options',
            'title' => __('Toolbar colors', 'wpd'),
        );

        $options_end = array('type' => 'sectionend');

        $globals_icons[] = array(
            'type' => 'text',
            'title' => "Background Color",
            'name' => "wpc-ui-options[toolbar-background-color]",
            'label_class' => 'col xl-1-2',
            'class' => 'wpc-color'
        );

        $globals_icons[] = array(
            'type' => 'text',
            'title' => "Background Color on hover",
            'name' => "wpc-ui-options[toolbar-background-color-hover]",
            'label_class' => 'col xl-1-2',
            'class' => 'wpc-color'
        );

        $toolbar = array(
            'title' => __('Toolbar Colors', 'wpd'),
            'type' => 'groupedfields',
            'fields' => $globals_icons);
        array_push($options, $options_begin);
        array_push($options, $toolbar);
        array_push($options, $options_end);
    }

    private function get_features_icons_settings(&$options) {
        $fields = wpd_get_ui_options_fields();

        $icons_options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc_features_options',
            'table' => 'options',
            'title' => __('Features icons', 'wpd'),
        );

        $icons_options_end = array('type' => 'sectionend');

        $globals_icons[] = array();

        foreach ($fields as $key => $field) {

            if (!isset($field["icon"]))
                continue;

            $icon_field_name = $key . "-icon";
            $globals_icons[] = array(
                'type' => 'image',
                'title' => get_proper_value($field, 'title', ''),
                'name' => "wpc-ui-options[$icon_field_name]",
                'set' => 'Set',
                'remove' => 'Remove',
                'label_class' => 'col xl-1-4 mg-bot-15',
            );
        }

        $toolbar_icons = array(
            'title' => __('Features Icons', 'wpd'),
            'type' => 'groupedfields',
            'fields' => $globals_icons);
        array_push($options, $icons_options_begin);
        array_push($options, $toolbar_icons);
        array_push($options, $icons_options_end);
    }

    private function get_features_colors_settings(&$options) {
        $options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc_features_colors_options',
            'table' => 'options',
            'title' => __('Features colors', 'wpd'),
        );

        $options_end = array('type' => 'sectionend');

        $fields[] = array(
            'type' => 'text',
            'title' => __("Column Bg Color", "wpd"),
            'name' => "wpc-ui-options[features-col-background-color]",
            'label_class' => 'col xl-1-3 mg-bot-15',
            'class' => 'wpc-color'
        );

        $fields[] = array(
            'type' => 'text',
            'title' => __("Feature Title Bg Color", "wpd"),
            'name' => "wpc-ui-options[feature-title-background-color]",
            'label_class' => 'col xl-1-3 mg-bot-15',
            'class' => 'wpc-color'
        );

        $fields[] = array(
            'type' => 'text',
            'title' => __("Feature Title Bg Color on hover", "wpd"),
            'name' => "wpc-ui-options[feature-title-background-color-hover]",
            'label_class' => 'col xl-1-3 mg-bot-15',
            'class' => 'wpc-color'
        );

        $fields[] = array(
            'type' => 'text',
            'title' => __("Feature Interior Bg", "wpd"),
            'name' => "wpc-ui-options[features-interior-bg-color]",
            'label_class' => 'col xl-1-3 mg-bot-15',
            'class' => 'wpc-color'
        );

        $fields[] = array(
            'type' => 'text',
            'title' => __("Text Color", "wpd"),
            'name' => "wpc-ui-options[feature-title-text-color]",
            'label_class' => 'col xl-1-3 mg-bot-15',
            'class' => 'wpc-color'
        );

        /* $fields[] = array(
          'type' => 'text',
          'title' => __("Text Color on hover", "wpd"),
          'name' => "wpc-ui-options[feature-title-text-color-hover]",
          'label_class' => 'col xl-1-3 mg-bot-15',
          'class' => 'wpc-color'
          ); */

        $toolbar = array(
            'title' => __('Features Column Colors', 'wpd'),
            'type' => 'groupedfields',
            'fields' => $fields);
        array_push($options, $options_begin);
        array_push($options, $toolbar);
        array_push($options, $options_end);
    }

    private function get_separators_colors_settings(&$options) {
        $options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc_separators_colors_options',
            'table' => 'options',
            'title' => __('Separators colors', 'wpd'),
        );

        $options_end = array('type' => 'sectionend');

        $fields[] = array(
            'type' => 'text',
            'title' => __("Bg Color", "wpd"),
            'name' => "wpc-ui-options[separators-background-color]",
            'label_class' => 'col xl-1-4 mg-bot-15',
            'class' => 'wpc-color'
        );

        $fields[] = array(
            'type' => 'text',
            'title' => __("Left Border Color", "wpd"),
            'name' => "wpc-ui-options[separators-left-border-color]",
            'label_class' => 'col xl-1-4 mg-bot-15',
            'class' => 'wpc-color'
        );

        $fields[] = array(
            'type' => 'text',
            'title' => __("Text Color", "wpd"),
            'name' => "wpc-ui-options[separators-text-color]",
            'label_class' => 'col xl-1-4 mg-bot-15',
            'class' => 'wpc-color'
        );

        /* $fields[] = array(
          'type' => 'text',
          'title' => __("Text Color on hover", "wpd"),
          'name' => "wpc-ui-options[separators-text-color-hover]",
          'label_class' => 'col xl-1-4 mg-bot-15',
          'class' => 'wpc-color'
          );
         */
        $toolbar = array(
            'title' => __('Separators Colors', 'wpd'),
            'type' => 'groupedfields',
            'fields' => $fields);
        array_push($options, $options_begin);
        array_push($options, $toolbar);
        array_push($options, $options_end);
    }

    private function get_buttons_colors_settings(&$options) {
        $options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc_separators_colors_options',
            'table' => 'options',
            'title' => __('Separators colors', 'wpd'),
        );

        $options_end = array('type' => 'sectionend');

        $fields[] = array(
            'type' => 'text',
            'title' => __("Bg Color", "wpd"),
            'name' => "wpc-ui-options[buttons-background-color]",
            'label_class' => 'col xl-1-4 mg-bot-15',
            'class' => 'wpc-color'
        );

        $fields[] = array(
            'type' => 'text',
            'title' => __("Bg Color on hover", "wpd"),
            'name' => "wpc-ui-options[buttons-background-color-hover]",
            'label_class' => 'col xl-1-4 mg-bot-15',
            'class' => 'wpc-color'
        );

        $fields[] = array(
            'type' => 'text',
            'title' => __("Text Color", "wpd"),
            'name' => "wpc-ui-options[buttons-text-color]",
            'label_class' => 'col xl-1-4 mg-bot-15',
            'class' => 'wpc-color'
        );

        /* $fields[] = array(
          'type' => 'text',
          'title' => __("Text Color on hover", "wpd"),
          'name' => "wpc-ui-options[buttons-text-color-hover]",
          'label_class' => 'col xl-1-4 mg-bot-15',
          'class' => 'wpc-color'
          ); */

        $toolbar = array(
            'title' => __('Buttons Colors', 'wpd'),
            'type' => 'groupedfields',
            'fields' => $fields);
        array_push($options, $options_begin);
        array_push($options, $toolbar);
        array_push($options, $options_end);
    }

    private function get_toolbar_icons_settings(&$options) {
        $icons_options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc_toolbar_icons_options',
            'table' => 'options',
            'title' => __('Toolbar icons', 'wpd'),
        );
        $icons_array = array(
            "grid" => __("Grid", 'wpd'),
            "clear" => __("Clear", 'wpd'),
            "delete" => __("Delete", 'wpd'),
            "duplicate" => __("Duplicate", 'wpd'),
            "send-to-back" => __("Send to back", 'wpd'),
            "bring-to-front" => __("Bring to front", 'wpd'),
            "flipV" => __("Vertical flip", 'wpd'),
            "flipH" => __("Horizontal flip", 'wpd'),
            "centerH" => __("Horizontal center", 'wpd'),
            "centerV" => __("Vertical center", 'wpd'),
            "undo" => __("Undo", 'wpd'),
            "redo" => __("Redo", 'wpd'),
        );

        $icons_options_end = array('type' => 'sectionend');

        foreach ($icons_array as $name => $label) {

            $id = "wpc-ui-options[$name]";
            $globals_icons[] = array(
                'type' => 'image',
                'title' => $label,
                'name' => $id,
                'set' => 'Set',
                'remove' => 'Remove',
                'label_class' => 'col xl-1-4 mg-bot-15',
            );
        }

        $toolbar_icons = array(
            'title' => __('Toolbar Icons', 'wpd'),
            'type' => 'groupedfields',
            'fields' => $globals_icons);
        array_push($options, $icons_options_begin);
        array_push($options, $toolbar_icons);
        array_push($options, $icons_options_end);
    }

    private function get_skins_settings() {
        $skin_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpd-skin-container',
            'table' => 'options',
        );
        $skins_arr = apply_filters("wpd_configuration_skins", array(
            "WPD_Skin_Default" => __("Default", "wpd"),
            "WPD_Skin_Jonquet" => __("Jonquet", "wpd"),
        ));


        $skins = array(
            'title' => __('Skin', 'wpd'),
            'name' => 'wpc-ui-options[skin]',
            'type' => 'select',
            'options' => $skins_arr,
//            'default' => 'WPD_Skin_Jonquet',
            'class' => 'chosen_select_nostd wpd-config-skin',
            'desc' => __('Editor look and feel.', 'wpd'),
        );

        $skin_end = array('type' => 'sectionend');
        $skin_settings = apply_filters("vpc_skins_settings", array(
            $skin_begin,
            $skins,
            $skin_end
        ));

        echo o_admin_fields($skin_settings);
    }

    private function get_interface_color_fields() {
        $fields = wpd_get_ui_options_fields();


        $color_grouped_fields = array(
        );
        foreach ($fields as $key => $field) {
            $grouped_field = array(
                'title' => get_proper_value($field, 'title', ''),
                'type' => 'groupedfields',
                'fields' => array()
            );
            if (isset($field["icon"])) {
                $icon_field_name = $key . "-icon";
                $icon = array(
                    'type' => 'image',
                    'title' => __('Icon', 'wpd'),
                    'name' => "wpc-ui-options[$icon_field_name]",
                    'set' => 'Set',
                    'remove' => 'Remove'
                );
                array_push($grouped_field["fields"], $icon);
            }
            $colors_options = array(
                "text-color" => __("Text color", "wpd"),
                "background-color" => __("Background color", "wpd"),
                "background-color-hover" => __("Background color on hover", "wpd")
            );
            foreach ($colors_options as $option_name => $option_label) {
                $color_field = $this->get_admin_option_field($option_label, "wpc-ui-options", "$key-$option_name", 'text', '', 'wpc-color', '', '', '');
                array_push($grouped_field["fields"], $color_field);
            }
            array_push($color_grouped_fields, $grouped_field);
        }

        return $color_grouped_fields;
    }

    private function get_general_settings() {
        $options = array();

        $general_options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc-general-options',
            'table' => 'options',
            'title' => __('General Settings', 'wpd')
        );

        $args = array(
            "post_type" => "page",
            "nopaging" => true,
        );

        $customizer_page = array(
            'title' => __('Design Page', 'wpd'),
            'desc' => __('This setting allows the plugin to locate the page where customizations are made. Please note that this page can only be accessed by our plugin and should not appear in any menu.', 'wpd'),
            'name' => 'wpc-general-options[wpc_page_id]',
            'type' => 'post-type',
            'default' => '',
            'class' => 'chosen_select_nostd',
            'args' => $args
        );

        $templates_page = array(
            'title' => __('Templates Page', 'wpd'),
            'desc' => __('This setting allows the plugin to locate the page where the products templates will be displayed. This setting can be overwritten per product.', 'wpd')
            . "<br><strong>Note: </strong>" . __("Use the shortcode [wpd-templates] where you want the templates to show up in the templates page.", "wpd"),
            'name' => 'wpc-general-options[wpd-templates-page]',
            'type' => 'post-type',
            'default' => '',
            'class' => 'chosen_select_nostd',
            'args' => $args
        );

        $content_filter = array(
            'title' => __('Automatically append canvas to the customizer page', 'wpd'),
            'name' => 'wpc-general-options[wpc-content-filter]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to define whether or not you want to use a shortcode to display the the customizer in the selected page.', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd',
        );


        $customizer_w = array(
            'title' => __('Canvas max width (px)', 'wpd'),
            'desc' => __('This option allows you to define canvas\'s width', 'wpd'),
            'name' => 'wpc-general-options[canvas-w]',
            'type' => 'text',
            'default' => '800'
        );
        $customizer_h = array(
            'title' => __('Canvas max height (px)', 'wpd'),
            'desc' => __('This option allows you to define canvas\'s height', 'wpd'),
            'name' => 'wpc-general-options[canvas-h]',
            'type' => 'text',
            'default' => '500'
        );

        $customizer_cart_display = array(
            'title' => __('Parts position in cart', 'wpd'),
            'name' => 'wpc-general-options[wpc-parts-position-cart]',
            'default' => 'thumbnail',
            'type' => 'radio',
            'desc' => __('This option allows you to set where to show your customized products parts on the cart page', 'wpd'),
            'options' => array(
                'thumbnail' => __('Thumbnail column', 'wpd'),
                'name' => __('Name column', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );


        $download_button = array(
            'title' => __('Download design', 'wpd'),
            'name' => 'wpc-general-options[wpc-download-btn]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to show/hide the download button on the customization page', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );
        $user_account_download_button = array(
            'title' => __('Download design from user account page', 'wpd'),
            'name' => 'wpc-general-options[wpc-user-account-download-btn]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to show/hide the download button on user account page', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );
        $send_attachments = array(
            'title' => __('Send the design as an attachment', 'wpd'),
            'name' => 'wpc-general-options[wpc-send-design-mail]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to send or not the design by mail after checkout', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );

        $preview_button = array(
            'title' => __('Preview design', 'wpd'),
            'name' => 'wpc-general-options[wpc-preview-btn]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to show/hide the preview button on the customization page', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );

        $cart_button = array(
            'title' => __('Add to cart', 'wpd'),
            'name' => 'wpc-general-options[wpc-cart-btn]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to show/hide the cart button on the customization page', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );
        $add_to_cart_action = array(
            'title' => __('Redirect after adding a custom design to the cart?', 'wpd'),
            'name' => 'wpc-general-options[wpc-redirect-after-cart]',
            'default' => '0',
            'type' => 'radio',
            'desc' => __('This option allows you to define what to do after adding a design to the cart', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );

        $responsive_canvas = array(
            'title' => __('Responsive behaviour', 'wpd'),
            'name' => 'wpc-general-options[responsive]',
            'default' => '0',
            'type' => 'radio',
            'desc' => __('This option allows you to define whether or not you want to enable the canvas responsive behaviour.', 'wpd'),
            'options' => array(
                '0' => __('No', 'wpd'),
                '1' => __('Yes', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );

        $hide_requirements_notices = array(
            'title' => __('Hide requirements notices', 'wpd'),
            'name' => 'wpc-general-options[hide-requirements-notices]',
            'default' => '0',
            'type' => 'radio',
            'desc' => __('This option allows you to define whether or not you want to hide the requirement notice.', 'wpd'),
            'options' => array(
                '0' => __('No', 'wpd'),
                '1' => __('Yes', 'wpd')
            ),
            'row_class' => 'wpd_hide_requirements',
            'class' => 'chosen_select_nostd'
        );

        $save_button = array(
            'title' => __('Save for later', 'wpd'),
            'name' => 'wpc-general-options[wpc-save-btn]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to show/hide the save for later button on the customization page', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );
        $hide_cart_button_for_custom_products = array(
            'title' => __('Hide Add to cart button for custom products', 'wpd'),
            'name' => 'wpc-general-options[wpd-hide-cart-button]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to define whether or not you want to hide the add to cart button for custom products on the products page.', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd',
        );

        $general_options_end = array('type' => 'sectionend');


        $conflicts_options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc_conflicts_options',
            'title' => __('Scripts management', 'wpd'),
            'table' => 'options'
        );

        $load_bs_modal = array(
            'title' => __('Load bootsrap modal', 'wpd'),
            'name' => 'wpc-general-options[wpc-load-bs-modal]',
            'default' => '1',
            'type' => 'radio',
            'desc' => __('This option allows you to enable/disable twitter\'s bootstrap modal script', 'wpd'),
            'options' => array(
                '1' => __('Yes', 'wpd'),
                '0' => __('No', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );
        $conflicts_options_end = array('type' => 'sectionend');



        array_push($options, $general_options_begin);
        array_push($options, $customizer_page);
        array_push($options, $templates_page);
        array_push($options, $content_filter);
        array_push($options, $customizer_w);
        array_push($options, $customizer_h);
        array_push($options, $customizer_cart_display);
        array_push($options, $preview_button);
        array_push($options, $save_button);
        array_push($options, $download_button);
        array_push($options, $user_account_download_button);
        array_push($options, $send_attachments);
        array_push($options, $cart_button);
        array_push($options, $add_to_cart_action);
        array_push($options, $responsive_canvas);
        array_push($options, $hide_cart_button_for_custom_products);
        array_push($options, $hide_requirements_notices);
        array_push($options, $general_options_end);
        array_push($options, $conflicts_options_begin);
        array_push($options, $load_bs_modal);
        array_push($options, $conflicts_options_end);

        $options = apply_filters("wpd_general_options", $options);
        echo o_admin_fields($options);
    }

    /**
     * Builds the uploads settings options
     * @return array Settings
     * @return array
     */
    private function get_uploads_settings() {

        $uploader_type = array(
            'title' => __('File upload script', 'wpd'),
            'name' => 'wpc-upload-options[wpc-uploader]',
            'default' => 'custom',
            'type' => 'radio',
            'desc' => __('This option allows you to set which file upload script you would like to use', 'wpd'),
            'options' => array(
                'custom' => __('Custom with graphical enhancements', 'wpd'),
                'native' => __('Normal', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );
        $min_upload_w = array(
            'title' => __('Uploads min width (px)', 'wpd'),
            'desc' => __('Uploaded images minimum width', 'wpd'),
            'name' => 'wpc-upload-options[wpc-min-upload-width]',
            'type' => 'text',
            'default' => ''
        );
        $min_upload_h = array(
            'title' => __('Uploads min height (px)', 'wpd'),
            'desc' => __('Uploaded images minimum height', 'wpd'),
            'name' => 'wpc-upload-options[wpc-min-upload-height]',
            'type' => 'text',
            'default' => ''
        );
        $upl_extensions = array(
            'title' => __('Allowed uploads extensions', 'wpd'),
            'name' => 'wpc-upload-options[wpc-upl-extensions]',
            'default' => array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'),
            'type' => 'multiselect',
            'desc' => __('Allowed extensions for uploads', 'wpd'),
            'options' => array(
                'jpg' => __('jpg', 'wpd'),
                'jpeg' => __('jpeg', 'wpd'),
                'png' => __('png', 'wpd'),
                'gif' => __('gif', 'wpd'),
                'bmp' => __('bmp', 'wpd'),
                'svg' => __('svg', 'wpd'),
                'psd' => __('psd', 'wpd'),
                'eps' => __('eps', 'wpd'),
                'pdf' => __('pdf', 'wpd'),
            )
        );

        $custom_designs_extensions = array(
            'title' => __('Custom designs allowed extensions (separated by commas)', 'wpd'),
            'desc' => __('Allowed extensions for custom designs. If not set, all extensions will be accepted.', 'wpd'),
            'name' => 'wpc-upload-options[wpc-custom-designs-extensions]',
            'type' => 'text',
            'default' => ''
        );
        $uploads_tab_visible = array(
            'title' => __('Active controls', 'wpd'),
            'label' => __('Show this tab', 'wpd'),
            'desc' => __('Whether or not to display this tab on the designer.', 'wpd'),
            'name' => 'wpc-upload-options[visible-tab]',
            'default' => 'yes',
            'type' => 'checkbox'
        );

        $uploads_all_options = array(
            array(
                'title' => __('Grayscale', 'wpd'),
                'name' => 'wpc-upload-options[grayscale]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Grayscale filter button in the uploads section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'start'
            ),
            array(
                'title' => __('Invert', 'wpd'),
                'desc' => __('Enable/Disable the Invert filter button in the uploads section.', 'wpd'),
                'label' => __('Enable', 'wpd'),
                'name' => 'wpc-upload-options[invert]',
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Sepia1', 'wpd'),
                'desc' => __('Enable/Disable the Sepia 1 filter button in the uploads section.', 'wpd'),
                'label' => __('Enable', 'wpd'),
                'name' => 'wpc-upload-options[sepia1]',
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Sepia2', 'wpd'),
                'desc' => __('Enable/Disable the Sepia 2 filter button in the uploads section.', 'wpd'),
                'label' => __('Enable', 'wpd'),
                'name' => 'wpc-upload-options[sepia2]',
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Blur', 'wpd'),
                'desc' => __('Enable/Disable the Blur filter button in the uploads section.', 'wpd'),
                'label' => __('Enable', 'wpd'),
                'name' => 'wpc-upload-options[blur]',
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Sharpen', 'wpd'),
                'desc' => __('Enable/Disable the Sharpen filter button in the uploads section.', 'wpd'),
                'label' => __('Enable', 'wpd'),
                'name' => 'wpc-upload-options[sharpen]',
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Opacity', 'wpd'),
                'desc' => __('Enable/Disable the opacity control field in the uploads section.', 'wpd'),
                'label' => __('Enable', 'wpd'),
                'name' => 'wpc-upload-options[opacity]',
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Emboss', 'wpd'),
                'desc' => __('Enable/Disable the emboss filter button in the uploads section.', 'wpd'),
                'label' => __('Enable', 'wpd'),
                'name' => 'wpc-upload-options[emboss]',
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
        );
        $upload_settings_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc-upload-options',
            'title' => __('Uploads Settings', 'wpd'),
            'table' => 'options'
        );

        $upload_settings_end = array(
            'type' => 'sectionend',
            'id' => 'wpc-upload-options'
        );

        $options = array();
        array_push($options, $upload_settings_begin);
        array_push($options, $uploads_tab_visible);
        $options = array_merge($options, $uploads_all_options);
        array_push($options, $uploader_type);
        array_push($options, $min_upload_w);
        array_push($options, $min_upload_h);
        array_push($options, $upl_extensions);
        array_push($options, $custom_designs_extensions);

        array_push($options, $upload_settings_end);
        $options = apply_filters("wpd_uploads_options", $options);
        echo o_admin_fields($options);
    }

    /**
     * Builds the social networks settings options
     * @return array Settings
     */
    private function get_social_networks_settings() {
        $options = array();


        $social_networks_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc_social_networks',
            'title' => __('Social Networks Settings', 'wpd'),
            'table' => 'options'
        );

        $social_networks_end = array(
            'type' => 'sectionend',
            'id' => 'wpc_social_networks'
        );
        $facebook_app_id = array(
            'title' => __('Facebook APP ID', 'wpd'),
            'desc' => __('This setting is mandatory so the user can use facebook connect', 'wpd'),
            'name' => 'wpc_social_networks[wpc-facebook-app-id]',
            'type' => 'text',
            'default' => ''
        );
        $facebook_app_secret = array(
            'title' => __('Facebook APP secret', 'wpd'),
            'desc' => __('This setting is mandatory so the user can use facebook connect', 'wpd'),
            'name' => 'wpc_social_networks[wpc-facebook-app-secret]',
            'type' => 'text',
            'default' => ''
        );

        $instagram_app_id = array(
            'title' => __('Instagram APP ID', 'wpd'),
            'desc' => __('This setting is mandatory so the user can use instagram connect', 'wpd'),
            'name' => 'wpc_social_networks[wpc-instagram-app-id]',
            'type' => 'text',
            'default' => ''
        );

        $instagram_app_secret = array(
            'title' => __('Instagram APP secret', 'wpd'),
            'desc' => __('This setting is mandatory so the user can use instagram connect', 'wpd'),
            'name' => 'wpc_social_networks[wpc-instagram-app-secret]',
            'type' => 'text',
            'default' => ''
        );

        array_push($options, $social_networks_begin);
        array_push($options, $facebook_app_id);
        array_push($options, $facebook_app_secret);
        array_push($options, $instagram_app_id);
        array_push($options, $instagram_app_secret);


        array_push($options, $social_networks_end);
        echo o_admin_fields($options);
    }

    /**
     * Builds the output settings options
     * @return array Settings
     */
    private function get_output_settings() {
        $options = array();
        $output_w = array(
            'title' => __('Output width (px)', 'wpd'),
            'desc' => __('Output files minimum width. If not set, the design area dimensions will be used for the generated designs.', 'wpd'),
            'name' => 'wpc-output-options[wpc-min-output-width]',
            'type' => 'text',
            'default' => ''
        );
        $output_loop_delay = array(
            'title' => __('Output loop delay (milliseconds)', 'wpd'),
            'desc' => __('Delay to go through each part. Should be increased when the plugin tries to handle high resolution files.', 'wpd'),
            'name' => 'wpc-output-options[wpc-output-loop-delay]',
            'type' => 'text',
            'default' => '1000'
        );

        $output_formats = array(
            array(
                'title' => __('Generated files', 'wpd'),
                'desc' => __('Generated files', 'wpd'),
                'checkboxgroup' => 'start'
            ),
//            array(
//                'title' => __('Layers', 'wpd'),
//                'name' => 'wpc-output-options[wpc-generate-layers]',
//                'label' => __('Enable', 'wpd'),
//                'desc' => __('Enable/Disable the layers generations in the output process.', 'wpd'),
//                'type' => 'checkbox',
//                'checkboxgroup' => '',
//                'default' => 'yes',
//            ),
            array(
                'title' => __('PDF', 'wpd'),
                'name' => 'wpc-output-options[wpc-generate-pdf]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the PDF output generation feature. If both PDF and SVG outputs are enabled, then the SVG will be included in the PDF file.', 'wpd'),
                'type' => 'checkbox',
                'checkboxgroup' => '',
                'default' => 'yes',
            ),
            array(
                'title' => __('SVG', 'wpd'),
                'name' => 'wpc-output-options[wpc-generate-svg]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the SVG output generation feature. If both PDF and SVG outputs are enabled, then the SVG will be included in the PDF file.', 'wpd'),
                'type' => 'checkbox',
                'checkboxgroup' => '',
                'default' => 'yes',
            ),
            array(
                'title' => __('Zip output folder', 'wpd'),
                'name' => 'wpc-output-options[wpc-generate-zip]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Allows you to  get all the output files in a zip file.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'end'
            ),
        );
        $zip_name = array(
            'title' => __('Zip output name', 'wpd'),
            'desc' => __('Zip output name.', 'wpd'),
            'name' => 'wpc-output-options[zip-folder-name]',
            'type' => 'text',
            'default' => ''
        );

        $cmyk_attr = array();
        if (!class_exists("Imagick"))
            $cmyk_attr = array(
                "disabled" => ""
            );
        $cmyk_conversion = array(
            'title' => __('CMYK conversion (Requires ImageMagick)', 'wpd'),
            'name' => 'wpc-output-options[wpc-cmyk-conversion]',
            'default' => 'no',
            'type' => 'radio',
            'desc' => __('This option allows you to set whether or not you need the output to be CMYK valid.', 'wpd') . "<br>" .
            __('Disabled if the Imagemagick extension is not installed and active.', 'wpd'),
            'options' => array(
                'no' => __('No', 'wpd'),
                'yes' => __('Yes', 'wpd')
            ),
            'custom_attributes' => $cmyk_attr,
            'class' => 'chosen_select_nostd'
        );
        $cmyk_profil = array(
            'title' => __('CMYK profile (when CMYK mode is enabled)', 'wpd'),
            'name' => 'wpc-output-options[wpc-cmyk-profil]',
            'type' => 'file',
            'desc' => __('This option allows you to set your own CMYK profile to use during the output conversion.', 'wpd') . "<br>" .
            __('Disabled if the Imagemagick extension is not installed and active.', 'wpd'),
            'custom_attributes' => array_merge($cmyk_attr, array('alt' => "color profil")),
            'set' => __('Set profil', 'wpd'),
            "remove" => __('Remove profil', 'wpd'),
        );
        $design_composition = array(
            'title' => __('Design Composition', 'wpd'),
            'name' => 'wpc-output-options[design-composition]',
            'default' => 'no',
            'type' => 'radio',
            'desc' => __('This option allows you to display or not design composition in the order ', 'wpd'),
            'options' => array(
                'no' => __('No', 'wpd'),
                'yes' => __('Yes', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );

        $pdf_format = array(
            'title' => __('PDF Format', 'wpd'),
            'name' => 'wpc-output-options[pdf-format]',
            'desc' => __('Output PDF format/size.', 'wpd'),
            'type' => 'groupedselect',
            'options' => get_wpd_pdf_formats(),
            'class' => 'chosen_select_nostd'
        );
        
        $pdf_w=array(
            'title' => __('Width', 'wpd'),
            'name' => 'wpc-output-options[pdf-width]',
            'type' => 'number',
        );
        
        $pdf_h=array(
            'title' => __('Height', 'wpd'),
            'name' => 'wpc-output-options[pdf-height]',
            'type' => 'number',
        );
        
        $pdf_unit=array(
            'title' => __('Unit', 'wpd'),
            'name' => 'wpc-output-options[pdf-unit]',
            'type' => 'select',
            'options' => array(
                'pt'=>__('Point', 'wpd'), 
                'mm'=>__('Millimeter', 'wpd'), 
                'cm'=>__('Centimeter', 'wpd'), 
                'in'=>__('Inch', 'wpd')),
            'default'=>'in'
        );
        
        $pdf_custom_dimensions=array(
            "title" => "Custom PDF dimensions",
            "desc" => __('These dimensions will only be used if the PDF format is set to Custom.', 'wpd'),
            "type" => "groupedfields",
            "fields"=> array($pdf_w, $pdf_h, $pdf_unit)
        );


        $pdf_margin_top_bottom = array(
            'title' => __('PDF Margin Top & Bottom', 'wpd'),
            'name' => 'wpc-output-options[pdf-margin-tb]',
            'desc' => __('Vertical margins in the output PDF.', 'wpd'),
            'type' => 'text',
            'default' => '20',
            'class' => 'chosen_select_nostd'
        );

        $pdf_margin_left_right = array(
            'title' => __('PDF Margin Left & Right', 'wpd'),
            'name' => 'wpc-output-options[pdf-margin-lr]',
            'desc' => __('Horizontal margins in the output PDF.', 'wpd'),
            'type' => 'text',
            'default' => '20',
            'class' => 'chosen_select_nostd'
        );

        $pdf_orientation = array(
            'title' => __('PDF Orientation', 'wpd'),
            'name' => 'wpc-output-options[pdf-orientation]',
            'desc' => __('Portrait or Landscape', 'wpd'),
            'default' => 'P',
            'type' => 'select',
            'options' => array(
                'P' => __('Portrait', 'wpd'),
                'L' => __('Landscape', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );

        $output_options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc-output-options',
            'title' => __('Output Settings', 'wpd'),
            'table' => 'options'
        );

        $output_options_end = array('type' => 'sectionend',
            'id' => 'wpc-output-options'
        );

        array_push($options, $output_options_begin);
        array_push($options, $output_w);
        array_push($options, $output_loop_delay);
        $options = array_merge($options, $output_formats);
        array_push($options, $zip_name);
        array_push($options, $cmyk_conversion);
        array_push($options, $cmyk_profil);
        array_push($options, $pdf_format);
        array_push($options, $pdf_custom_dimensions);
        array_push($options, $pdf_orientation);
        array_push($options, $pdf_margin_top_bottom);
        array_push($options, $pdf_margin_left_right);
        array_push($options, $design_composition);
        array_push($options, $output_options_end);
        echo o_admin_fields($options);
    }

    /**
     * Builds the colors settings options
     * @global array $wpd_settings
     * @return array Settings
     */
    private function get_colors_settings() {
        $options = array();

        $svg_colors = array(
            'title' => __('SVG colorization', 'wpd'),
            'name' => 'wpc-colors-options[wpc-svg-colorization]',
            'default' => 'by-path',
            'type' => 'radio',
            'desc' => __('This option allows you to set how you would like the SVG files to be colorized', 'wpd'),
            'options' => array(
                'by-path' => __('Path by path', 'wpd'),
                'by-colors' => __('Color by color', 'wpd'),
                'none' => __('None', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );
        $colors_palette = array(
            'title' => __('Colors palette', 'wpd'),
            'name' => 'wpc-colors-options[wpc-color-palette]',
            'default' => 'unlimited',
            'type' => 'radio',
            'desc' => __('This option allows you would like your clients to use in their designs', 'wpd'),
            'options' => array(
                'unlimited' => __('Unlimited', 'wpd'),
                'custom' => __('Custom', 'wpd')
            ),
            'class' => 'chosen_select_nostd'
        );
        $line_color = array(
            'title' => __('Line Color', 'wpd'),
            'name' => 'wpc-colors-options[line-color]',
            'type' => 'text',
            'class' => 'wpc-color',
        );
        $colors_options_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc-colors-options',
            'title' => __('Colors Settings', 'wpd'),
            'table' => 'options'
        );


        $colors_options_end = array(
            'type' => 'sectionend',
            'id' => 'wpc-colors-options'
        );
        array_push($options, $colors_options_begin);
        array_push($options, $line_color);
        array_push($options, $svg_colors);
        array_push($options, $colors_palette);
        array_push($options, $colors_options_end);
        echo o_admin_fields($options);
    }

    /**
     * Builds the text settings options
     * @global array $wpd_settings
     * @return array Settings
     */
    private function get_text_settings() {
        $options = array();

        $text_options_begin = array(
            'type' => 'sectionbegin',
            'title' => __('Text Settings', 'wpd'),
            'id' => 'wpc-texts-options',
            'table' => 'options'
        );
        $text_options_end = array('type' => 'sectionend');

        $text_tab_visible = array(
            'title' => __('Active controls', 'wpd'),
            'desc' => __('Show this tab', 'wpd'),
            'name' => 'wpc-texts-options[visible-tab]',
            'type' => 'checkbox',
            'default' => 'yes'
        );
        $text_all_options = array(
            array(
                'title' => __('Underline', 'wpd'),
                'name' => 'wpc-texts-options[underline]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Underline setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'start'
            ),
            array(
                'title' => __('Bold', 'wpd'),
                'name' => 'wpc-texts-options[bold]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Bold setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Italic', 'wpd'),
                'name' => 'wpc-texts-options[italic]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Italic setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Text Color', 'wpd'),
                'name' => 'wpc-texts-options[text-color]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Text Color setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Background Color', 'wpd'),
                'name' => 'wpc-texts-options[background-color]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Background Color setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Outline', 'wpd'),
                'name' => 'wpc-texts-options[outline]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Outline setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Curved', 'wpd'),
                'name' => 'wpc-texts-options[curved]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Curved Text setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Font Family', 'wpd'),
                'name' => 'wpc-texts-options[font-family]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Font Family setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Font Size', 'wpd'),
                'name' => 'wpc-texts-options[font-size]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Font Size setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Outline Width', 'wpd'),
                'name' => 'wpc-texts-options[outline-width]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Outline Width setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Opacity', 'wpd'),
                'name' => 'wpc-texts-options[opacity]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Opacity setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Alignment', 'wpd'),
                'name' => 'wpc-texts-options[text-alignment]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Text Alignment setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Strikethrough', 'wpd'),
                'name' => 'wpc-texts-options[text-strikethrough]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Strikethrough setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Overline', 'wpd'),
                'name' => 'wpc-texts-options[text-overline]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Overline setting in the text section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'end'
            ),
            array(
                'title' => __('Minimum font size', 'wpd'),
                'name' => 'wpc-texts-options[min-font-size]',
                'desc' => __('Minimum font size in the text section.', 'wpd'),
                'type' => 'number',
                'default' => 8,
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Maximum font size', 'wpd'),
                'name' => 'wpc-texts-options[max-font-size]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Maximum font size in the text section.', 'wpd'),
                'type' => 'number',
                'default' => 30,
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Default font size', 'wpd'),
                'name' => 'wpc-texts-options[default-font-size]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Default font size in the text section.', 'wpd'),
                'type' => 'number',
                'default' => 15,
                'checkboxgroup' => ''
            ),
        );

        array_push($options, $text_options_begin);
        array_push($options, $text_tab_visible);
        $options = array_merge($options, $text_all_options);
        array_push($options, $text_options_end);
        $options = apply_filters("wpd_text_options", $options);

        echo o_admin_fields($options);
    }

    /**
     * Builds the shapes settings options
     * @global array $wpd_settings
     * @return array Settings
     */
    private function get_shapes_settings() {
        $options = array();


        $shapes_options_begin = array(
            'type' => 'sectionbegin',
            'title' => __('Shapes Settings', 'wpd'),
            'id' => 'wpc-shapes-options',
            'table' => 'options'
        );

        $shapes_options_end = array('type' => 'sectionend');

        $shapes_tab_visible = array(
            'title' => __('Active controls', 'wpd'),
            'desc' => __('Show this tab', 'wpd'),
            'name' => 'wpc-shapes-options[visible-tab]',
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $shapes_all_options = array(
            array(
                'title' => __('Square', 'wpd'),
                'name' => 'wpc-shapes-options[square]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Square shape in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'start'
            ),
            array(
                'title' => __('Rounded square', 'wpd'),
                'name' => 'wpc-shapes-options[r-square]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Rounded Square shape in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Circle', 'wpd'),
                'name' => 'wpc-shapes-options[circle]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Circle shape in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Triangle', 'wpd'),
                'name' => 'wpc-shapes-options[triangle]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Triangle shape in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Polygon', 'wpd'),
                'name' => 'wpc-shapes-options[polygon]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Polygon shape in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Star', 'wpd'),
                'name' => 'wpc-shapes-options[star]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Star shape in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Heart', 'wpd'),
                'name' => 'wpc-shapes-options[heart]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Heart shape in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Outline', 'wpd'),
                'name' => 'wpc-shapes-options[outline]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Outline setting in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Background Color', 'wpd'),
                'name' => 'wpc-shapes-options[background-color]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Background Color setting in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Outline Width', 'wpd'),
                'name' => 'wpc-shapes-options[outline-width]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Outline Width setting in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Opacity', 'wpd'),
                'name' => 'wpc-shapes-options[opacity]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Opacity setting in the shapes section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'end'
            )
        );

        array_push($options, $shapes_options_begin);
        array_push($options, $shapes_tab_visible);
        $options = array_merge($options, $shapes_all_options);
        array_push($options, $shapes_options_end);
        echo o_admin_fields($options);
    }

    /**
     * Builds the images settings options
     * @global array $wpd_settings
     * @return array Settings
     */
    private function get_images_settings() {
        GLOBAL $wpd_settings;
        $options = array();

        $images_options_begin = array(
            'type' => 'sectionbegin',
            'title' => __('Image Settings', 'wpd'),
            'table' => 'options',
            'id' => 'wpc-images-options',
        );

        $images_options_end = array('type' => 'sectionend');
        $images_tab_visible = array(
            'title' => __('Active controls', 'wpd'),
            'desc' => __('Show this tab', 'wpd'),
            'name' => 'wpc-images-options[visible-tab]',
            'default' => 'yes',
            'type' => 'checkbox'
        );
        $images_all_options = array(
            array(
                'title' => __('Grayscale', 'wpd'),
                'name' => 'wpc-images-options[grayscale]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Grayscale filter button in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'start'
            ),
            array(
                'title' => __('Invert', 'wpd'),
                'name' => 'wpc-images-options[invert]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Invert filter button in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Sepia1', 'wpd'),
                'name' => 'wpc-images-options[sepia1]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Sepia 1 filter button in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Sepia2', 'wpd'),
                'name' => 'wpc-images-options[sepia2]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Sepia 2 filter button in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Blur', 'wpd'),
                'name' => 'wpc-images-options[blur]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Blur filter button in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => '',
            ),
            array(
                'title' => __('Sharpen', 'wpd'),
                'name' => 'wpc-images-options[sharpen]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Sharpen filter button in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Emboss', 'wpd'),
                'name' => 'wpc-images-options[emboss]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Emboss filter button in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Opacity', 'wpd'),
                'name' => 'wpc-images-options[opacity]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Opacity setting in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
            array(
                'title' => __('Enable lazyload for cliparts galleries', 'wpd'),
                'name' => 'wpc-images-options[lazy]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the lazyload behavior in the cliparts section.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => ''
            ),
        );

        array_push($options, $images_options_begin);
        array_push($options, $images_tab_visible);
        $options = array_merge($options, $images_all_options);
        array_push($options, $images_options_end);
        echo o_admin_fields($options);
    }

    /**
     * Builds the user design settings options
     * @global array $wpd_settings
     * @return array Settings
     */
    private function get_my_design_settings() {
        GLOBAL $wpd_settings;
        $options = array();

        $colors_array = array(
            'label-color' => __('Title section text color'),
            'normal-color' => __('Title section background color'),
            'selected-color' => __('Title section background color on hover')
        );

        $design_options_begin = array(
            'type' => 'sectionbegin',
            'title' => __('Designs Settings', 'wpd'),
            'table' => 'options',
            'id' => 'wpc-designs-options',
        );


        $design_options_end = array('type' => 'sectionend');

        $design_tab_visible = array(
            'title' => __('Active controls', 'wpd'),
            'desc' => __('Show this tab', 'wpd'),
            'name' => 'wpc-designs-options[visible-tab]',
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $design_all_options = array(
            array(
                'title' => __('Saved Designs', 'wpd'),
                'name' => 'wpc-designs-options[saved]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable the Saved Designs feature.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'start'
            ),
            array(
                'title' => __('Orders Designs', 'wpd'),
                'name' => 'wpc-designs-options[orders]',
                'label' => __('Enable', 'wpd'),
                'desc' => __('Enable/Disable access to the previous Orders Designs feature.', 'wpd'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'end'
        ));


        array_push($options, $design_options_begin);
        array_push($options, $design_tab_visible);
        $options = array_merge($options, $design_all_options);
        array_push($options, $design_options_end);
        echo o_admin_fields($options);
    }

    /**
     * Builds the social networks settings options
     * @return array Settings
     */
    private function get_licence_settings() {
        $options = array();
        $licence_begin = array(
            'type' => 'sectionbegin',
            'id' => 'wpc-licence',
            'title' => __('Licence Settings', 'wpd'),
            'table' => 'options'
        );

        $licence_end = array(
            'type' => 'sectionend',
            'id' => 'wpc-licence'
        );
        $envato_username = array(
            'title' => __('Envato Username', 'wpd'),
            'desc' => __('Your Envato username', 'wpd'),
            'name' => 'wpc-licence[envato-username]',
            'type' => 'text',
            'default' => ''
        );
        $envato_api_secret = array(
            'title' => __('Secret API Key', 'wpd'),
            'desc' => __("You can find API key by visiting your Envato Account page, then clicking the My Settings tab. At the bottom of the page you'll find your account's API key.", 'wpd'),
            'name' => 'wpc-licence[envato-api-key]',
            'type' => 'text',
            'default' => ''
        );
        $purchase_code = array(
            'title' => __('Purchase Code', 'wpd'),
            'desc' => " " . __(' You can find your purchase code by following the instructions <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-" target="blank">here</a>.', 'wpd'),
            'name' => 'wpc-licence[purchase-code]',
            'type' => 'text',
            'default' => ''
        );

        array_push($options, $licence_begin);
        array_push($options, $envato_username);
        array_push($options, $envato_api_secret);
        array_push($options, $purchase_code);
        array_push($options, $licence_end);
        echo o_admin_fields($options);
    }

    /**
     * Builds the settings page
     */
    function get_settings_page() {
        wpd_remove_transients();

        if (isset($_POST) && !empty($_POST)) {
            $this->save_wpc_tab_options();
            GLOBAL $wp_rewrite;
//            $this->wpd_add_rewrite_rules();
            $wp_rewrite->flush_rules(false);
        }
        wp_enqueue_media();
        ?>
        <form method="POST">
            <div id="wpc-settings">
                <div class="wrap">
                    <h2><?php _e("Woocommerce Products Designer Settings", "wpd"); ?></h2>
                </div>
                <div id="TabbedPanels1" class="TabbedPanels">
                    <ul class="TabbedPanelsTabGroup ">
                        <li class="TabbedPanelsTab " tabindex="1"><span><?php _e('General', 'wpd'); ?></span> </li>
                        <li class="TabbedPanelsTab" tabindex="2"><span><?php _e('Uploads', 'wpd'); ?> </span></li>
                        <li class="TabbedPanelsTab" tabindex="3"><span><?php _e('Social Networks', 'wpd'); ?></span></li>
                        <li class="TabbedPanelsTab" tabindex="4"><span><?php _e('Output', 'wpd'); ?></span></li>
                        <li class="TabbedPanelsTab" tabindex="5"><span><?php _e('Colors', 'wpd'); ?></span></li>

                        <li class="TabbedPanelsTab" tabindex="6"><span><?php _e('Text', 'wpd'); ?></span></li>
                        <li class="TabbedPanelsTab" tabindex="7"><span><?php _e('Shapes', 'wpd'); ?></span></li>
                        <li class="TabbedPanelsTab" tabindex="8"><span><?php _e('Cliparts', 'wpd'); ?></span></li>
                        <li class="TabbedPanelsTab" tabindex="9"><span><?php _e('Designs', 'wpd'); ?></span></li>
                        <li class="TabbedPanelsTab" tabindex="10"><span><?php _e('User Interface', 'wpd'); ?></span></li>
                        <li class="TabbedPanelsTab" tabindex="11"><span><?php _e('Licence', 'wpd'); ?></span></li>

                    </ul>

                    <div class="TabbedPanelsContentGroup">
                        <div class="TabbedPanelsContent">
                            <div class='wpc-grid wpc-grid-pad'>
        <?php
        $this->get_general_settings();
        ?>                              
                            </div>
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class='wpc-grid wpc-grid-pad'>
                                <?php
                                $this->get_uploads_settings();
                                ?>
                            </div>
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class="wpc-grid wpc-grid-pad">
                                <?php
                                $this->get_social_networks_settings();
                                ?>
                            </div> 
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class="wpc-grid wpc-grid-pad">
                                <?php
                                $this->get_output_settings();
                                ?>
                            </div> 
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class="wpc-grid wpc-grid-pad">
                                <?php
                                $this->get_colors_settings();
                                echo call_user_func(apply_filters('wpd_get_custom_palette_func', array($this, 'get_custom_palette')));
                                ?>
                            </div> 
                        </div>

                        <div class="TabbedPanelsContent">
                            <div class="wpc-grid wpc-grid-pad">
                                <?php
                                $this->get_text_settings();
                                ?>
                            </div> 
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class="wpc-grid wpc-grid-pad">
                                <?php
                                $this->get_shapes_settings();
                                ?>
                            </div> 
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class="wpc-grid wpc-grid-pad">
                                <?php
                                $this->get_images_settings();
                                ?>
                            </div> 
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class="wpc-grid wpc-grid-pad">
                                <?php
                                $this->get_my_design_settings();
                                ?>
                            </div> 
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class='wpc-grid wpc-grid-pad'>
                                <?php
                                $this->get_front_tools_settings();
                                ?>                              
                            </div>
                        </div>
                        <div class="TabbedPanelsContent">
                            <div class='wpc-grid wpc-grid-pad'>
                                <?php
                                $this->get_licence_settings();
                                ?>                              
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
            <input type="submit" value="<?php _e("Save", "wpd"); ?>" class="button button-primary button-large mg-top-10-i">
        </form>
        <?php
    }

    /**
     * Save the settings
     */
    private function save_wpc_tab_options() {
        if (isset($_POST) && !empty($_POST)) {
            $checkboxes_map = array(
                "wpc-output-options" => array("wpc-generate-layers", "wpc-generate-pdf", "wpc-generate-zip", "wpc-generate-svg"),
                "wpc-texts-options" => array("visible-tab", "underline", "text-spacing", "bold", "italic", "text-color", "background-color", "outline", "curved", "font-family", "font-size", "outline-width", "opacity", "text-alignment", "text-strikethrough", "text-overline"),
                "wpc-shapes-options" => array("visible-tab", "square", "r-square", "circle", "triangle", "polygon", "star", "heart", "background-color", "outline", "outline-width", "opacity"),
                "wpc-images-options" => array("visible-tab", "lazy", "emboss", "opacity", "sharpen", "blur", "sepia1", "sepia2", "invert", "grayscale"),
                "wpc-designs-options" => array("visible-tab", "saved", "orders"),
                "wpc-upload-options" => array("visible-tab", "grayscale", "invert", "sepia1", "sepia2", "blur", "emboss", "opacity", "sharpen")
            );
            foreach ($checkboxes_map as $key_map => $values) {
                if (isset($_POST[$key_map])) {
                    $this->transform_checkbox_value($key_map, $checkboxes_map[$key_map]);
                } else {
                    foreach ($checkboxes_map[$key_map] as $option) {
                        $_POST[$key_map][$option] = 'no';
                    }
                }
            }

            foreach ($_POST as $key => $values) {
                update_option($key, $_POST[$key]);
            }

            $this->init_globals();
            ?>
            <div id="message" class="updated below-h2"><p><?php echo __("Settings successfully saved.", "wpd"); ?></p></div>
            <?php
        }
    }

    private function get_custom_palette() {
        GLOBAL $wpd_settings;
        $colors_options = $wpd_settings['wpc-colors-options'];
        $wpc_palette_type = get_proper_value($colors_options, 'wpc-color-palette', "");
        $palette_style = "";
        if (isset($wpc_palette_type) && $wpc_palette_type != "custom")
            $palette_style = "style='display:none;'";
        $palette = get_proper_value($colors_options, 'wpc-custom-palette', "");
        $custom_palette = '<table class="form-table widefat" id="wpd-predefined-colors-options" ' . $palette_style . '>
                <tbody>
                    <tr valign="top">
                <th scope="row" class="titledesc"></th>
                    <td class="forminp">
                    <div class="wpc-colors">';
        if (isset($palette) && is_array($palette)) {
            foreach ($palette as $color) {
                $custom_palette.='<div>
                                    <input type="text" name="wpc-colors-options[wpc-custom-palette][]"style="background-color: ' . $color . '" value="' . $color . '" class="wpc-color">
                                        <button class="button wpc-remove-color">Remove</button>
                                </div>';
            }
        }
        $custom_palette.='</div>
                        <button class="button mg-top-10" id="wpc-add-color">Add color</button>
                    </td>
                    </tr>
                </tbody>
   </table>';
        return $custom_palette;
    }

    /**
     * Format the checkbox option in the settings
     * @param type $option_name
     * @param type $option_array
     */
    private function transform_checkbox_value($option_name, $option_array) {
        foreach ($option_array as $option) {
            if (!isset($_POST[$option_name][$option]))
                $_POST[$option_name][$option] = 'no';
        }
    }

    /**
     * Alerts the administrator if the customization page is missing
     * @global array $wpd_settings
     */
    function notify_customization_page_missing() {
        GLOBAL $wpd_settings;
        $options = $wpd_settings['wpc-general-options'];
        $wpc_page_id = $options['wpc_page_id'];
        $settings_url = get_bloginfo("url") . '/wp-admin/admin.php?page=wpc-manage-settings';
        if (empty($wpc_page_id))
            echo '<div class="error">
                   <p><b>Woocommerce product Designer: </b>The customizer page is not defined. Please configure it in <a href="' . $settings_url . '">woocommerce page settings</a>: .</p>
                </div>';
        if (!extension_loaded('zip'))
            echo '<div class="error">
                   <p><b>Woocommerce product Designer: </b>ZIP extension not loaded on this server. You won\'t be able to generate zip outputs.</p>
                </div>';
    }

    /**
     * Alerts the administrator if the minimum requirements are not met
     */
    function notify_minmimum_required_parameters() {
        GLOBAL $wpd_settings;
        $general_options = get_proper_value($wpd_settings, 'wpc-general-options');
        $hide_notices = get_proper_value($general_options, "hide-requirements-notices", false);
        if ($hide_notices)
            return;
        $message = "";
        $minimum_required_parameters = array(
            "memory_limit" => array(128, "M"),
            "max_file_uploads" => array(100, ""),
            "max_input_vars" => array(5000, ""),
            "post_max_size" => array(128, "M"),
            "upload_max_filesize" => array(128, "M"),
        );
        foreach ($minimum_required_parameters as $key => $min_arr) {
            $defined_value = ini_get($key);
            $defined_value_int = str_replace($min_arr[1], "", $defined_value);
            if ($defined_value_int < $min_arr[0])
                $message.="Your PHP setting <b>$key</b> is currently set to <b>$defined_value</b>. We recommand to set this value at least to <b>" . implode("", $min_arr) . "</b> to avoid any issue with our plugin.<br>";
        }

        $edit_msg = __("How to fix this: You can edit your php.ini file to increase the specified variables to the recommanded values or you can ask your hosting company to make the changes for you.", "wpd");

        if (!empty($message))
            echo '<div class="error">
                   <p><b>Woocommerce Product Designer: </b><br>' . $message . '<br>
                       <b>' . $edit_msg . '</b></p>
                </div>';
    }

    /**
     * Checks if the database needs to be upgraded
     */
    function run_wpc_db_updates_requirements() {
        //Checks db structure
        $db_version = get_option("wpd-db-version");
//        var_dump($db_version);
        GLOBAL $wpd_settings;
        $options = $wpd_settings['wpc-general-options'];
        $wpc_page_id = $options['wpc_page_id'];
        $custom_products = wpd_get_custom_products();
        if (
                $this->get_meta_count('customizable-product') > 0 || $this->get_meta_count('wpc-templates-page') > 0 || $this->get_meta_count('wpc-upload-design') > 0 || (!$db_version && !empty($custom_products) || ($db_version != WPD_VERSION))
        ) {
            ?>
            <div class="updated" id="wpc-updater-container">
                <strong><?php echo _e("Woocommerce Product Designer database update required", "wpd"); ?></strong>
                <div>
            <?php echo _e("Hi! This version of the Woocommerce Product Designer made some changes in the way it's data are stored. So in order to work properly, we just need you to click on the \"Run Updater\" button to move your old settings to the new structure. ", "wpd"); ?><br>
                    <input type="button" value="<?php echo _e("Run the updater", "wpd"); ?>" id="wpc-run-updater" class="button button-primary"/>
                    <div class="loading" style="display:none;"></div>
                </div>
            </div>
            <style>
                #wpc-updater-container
                {
                    padding: 3px 17px;
                    font-size: 15px;
                    line-height: 36px;
                    margin-left: 0px;
                    border-left: 5px solid #e14d43 !important;
                }
                #wpc-updater-container.done
                {
                    border-color: #7ad03a !important;
                }
                #wpc-run-updater {
                    background: #e14d43;
                    border-color: #d02a21;
                    color: #fff;
                    -webkit-box-shadow: inset 0 1px 0 #ec8a85,0 1px 0 rgba(0,0,0,.15);
                    box-shadow: inset 0 1px 0 #ec8a85,0 1px 0 rgba(0,0,0,.15);
                }

                #wpc-run-updater:focus, #wpc-run-updater:hover {
                    background: #dd362d;
                    border-color: #ba251e;
                    color: #fff;
                    -webkit-box-shadow: inset 0 1px 0 #e8756f;
                    box-shadow: inset 0 1px 0 #e8756f;
                }
                .loading
                {
                    background: url("<?php echo WPD_URL; ?>/admin/images/spinner.gif") 10% 10% no-repeat transparent;
                    background-size: 111%;
                    width: 32px;
                    height: 40px;
                    display: inline-block;
                }
            </style>
            <script>
                //jQuery('.loading').hide();
                jQuery('#wpc-run-updater').click('click', function () {
                    var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
                    if (confirm("It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now")) {
                        jQuery('.loading').show();
                        jQuery.post(
                                ajax_url,
                                {
                                    action: 'run_updater'
                                },
                        function (data) {
                            jQuery('.loading').hide();
                            jQuery('#wpc-updater-container').html(data);
                            jQuery('#wpc-updater-container').addClass("done");
                        }
                        );
                    }

                });
            </script>
            <?php
        }
//        else if (empty($wpc_page_id))//First installation
//            update_option("wpd-db-version", WPD_VERSION);
    }

    /**
     * Returns the number of occurences corresponding to a post meta key
     * @global type $wpdb Database object
     * @param type $meta_key Meta Key to check
     * @return int Number of occurences
     */
    private function get_meta_count($meta_key) {
        global $wpdb;
        $sql_result = $wpdb->get_var(
                "
                            SELECT count(*)
                            FROM $wpdb->posts p
                            JOIN $wpdb->postmeta pm on pm.post_id = p.id
                            WHERE p.post_type = 'product'
                            AND pm.meta_key = '" . $meta_key . "' 
                            AND p.post_status = 'publish'
                      ");
        return $sql_result;
    }

    /**
     * Runs the database upgrade
     */
    function run_wpc_updater() {
        if (!$this->update_db_for_v2_0()) {
            $message = __("Something went wrong...", "wpd");
        } elseif (!$this->update_db_for_v3_0()) {
            $message = __("Something went wrong...", "wpd");
        } elseif (!$this->update_db_for_v3_8_1()) {
            $message = __("Something went wrong...", "wpd");
        } elseif (!$this->update_db_for_v3_12()) {
            $message = __("Something went wrong...", "wpd");
        } else {
            update_option("wpd-db-version", WPD_VERSION);
            $message = __("Your database has been successfully updated.", "wpd");
        }
        echo $message;
        die();
    }

    private function map_part_datas($metas, $product_id) {
        $wpc_metas = $metas;
        $product = wc_get_product($product_id);
        $canvas_width_arr = get_post_meta($product_id, "wpc-canvas-w", true);
        $canvas_height_arr = get_post_meta($product_id, "wpc-canvas-h", true);
        $parts = get_option("wpc-parts");
        if ($product->product_type == "variable") {
            $variations = $product->get_available_variations();
            foreach ($variations as $variation) {
                $variation_id = $variation['variation_id'];
                $wpc_metas[$variation_id]['canvas-w'] = get_proper_value($canvas_width_arr, $variation_id, "");
                $wpc_metas[$variation_id]['canvas-h'] = get_proper_value($canvas_height_arr, $variation_id, "");
                $wpc_metas = $this->get_part_datas($wpc_metas, $parts, $variation_id);
            }
        } else {
            $wpc_metas[$product_id]['canvas-w'] = get_proper_value($canvas_width_arr, $product_id, "");
            $wpc_metas[$product_id]['canvas-h'] = get_proper_value($canvas_height_arr, $product_id, "");
            $wpc_metas = $this->get_part_datas($wpc_metas, $parts, $product_id);
        }
        delete_post_meta($product_id, "wpc-canvas-w");
        delete_post_meta($product_id, "wpc-canvas-h");
        return $wpc_metas;
    }

    /**
     * Runs the products meta migrations if needed
     * @return bool
     */
    function run_products_metas_migration_if_needed() {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'nopaging' => true,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'customizable-product'
                ),
                array(
                    'key' => 'wpc-templates-page'
                ),
                array(
                    'key' => 'wpc-upload-design'
                ),
                array(
                    'key' => 'wpc-design-from-blank'
                )
            )
        );
        $the_query = new WP_Query($args);
        $result = true;
        $bounding_box = array(
            "width" => "clip_width",
            "height" => "clip_height",
            "x" => "clip_x",
            "y" => "clip_y",
            "radius" => "clip_radius",
            "r_radius" => "clip_radius_rect",
            "type" => "clip_type",
            "border_color" => "clip_b_color"
        );
        if ($the_query->have_posts()) :
            while ($the_query->have_posts()) : $the_query->the_post();
                $keys_map = array(
                    "is-customizable" => "customizable-product",
                    "templates-page" => "wpc-templates-page",
                    "can-upload-custom-design" => "wpc-upload-design",
                    "can-design-from-blank" => "wpc-design-from-blank",
                    "pricing-rules" => "wpc-pricing",
                    "canvas-w" => "wpc-canvas-w",
                    "canvas-h" => "wpc-canvas-h",
                    "ninja-form-options" => "wpc-options-ninja-fom",
                    "output-settings" => "wpc-output-product-settings"
                );
                $product_id = get_the_ID();
                $metas = get_post_meta($product_id, 'wpc-metas', true);
                if (!is_array($metas))
                    $metas = array();
                foreach ($keys_map as $new_key => $old_key) {
                    $metas[$new_key] = get_post_meta($product_id, $old_key, true);
                    $result = delete_post_meta($product_id, $old_key);
                }
                foreach ($bounding_box as $new_box_meta => $old_box_key) {
                    $metas['bounding_box'][$new_box_meta] = get_post_meta($product_id, $old_box_key, true);
                    $result = delete_post_meta($product_id, $old_box_key);
                }
                $wpc_metas = $this->map_part_datas($metas, $product_id);
                $result = update_post_meta($product_id, "wpc-metas", $wpc_metas);
                if (!$result)
                    return $result;
            endwhile;
        endif;
    }

    /**
     * Runs the general options updates if needed
     * @param type $general_options
     * @return bool
     */
    private function run_general_options_migration($general_options) {
        $general_options['canvas-w'] = $general_options['wpc-canvas-w'];
        unset($general_options['wpc-canvas-w']);

        $general_options['canvas-h'] = $general_options['wpc-canvas-h'];
        unset($general_options['wpc-canvas-h']);
        $result = update_option("wpc-general-options", $general_options);
        return $result;
    }

    /**
     * Runs the database upgrade for V3.0
     * @return boolean
     */
    private function update_db_for_v3_0() {
        $this->run_products_metas_migration_if_needed();
        $general_options = get_option('wpc-general-options');
        if (isset($general_options['wpc-canvas-w']) || isset($general_options['wpc-canvas-h'])) {
            $result = $this->run_general_options_migration($general_options);
            return $result;
        }
        return true;
    }

    /**
     * Runs the database upgrade for V3.8.1
     * @return boolean
     */
    private function update_db_for_v3_8_1() {
        $db_version = get_option("wpd-db-version");
        $custom_products = wpd_get_custom_products();
        $parts = get_option("wpc-parts");
        if (!$db_version && !empty($custom_products)) {
            foreach ($custom_products as $product) {
                $wc_product = get_product($product->id);
                $wpc_metas = get_post_meta($product->id, 'wpc-metas', true);
                if ($wc_product->product_type == "variable") {
                    $variations = $wc_product->get_available_variations();
                    foreach ($variations as $variation) {
                        $variation_id = $variation['variation_id'];
                        foreach ($parts as $part) {
                            $part_key = sanitize_title($part);
                            if (
                                    get_proper_value($wpc_metas, $variation_id, array()) && get_proper_value($wpc_metas[$variation_id], 'parts', array()) && get_proper_value($wpc_metas[$variation_id]['parts'], $part_key, array())
                            ) {
                                $part_media_id = get_proper_value($wpc_metas[$variation_id]['parts'][$part_key], 'bg-inc', "");
                                if ($part_media_id || $part_media_id == "0")
                                    $wpc_metas[$variation_id]['parts'][$part_key]["enabled"] = 1;
                            }
                        }
                    }
                } else {
                    foreach ($parts as $part) {
                        $part_key = sanitize_title($part);
                        if (
                                get_proper_value($wpc_metas, $product->id, array()) && get_proper_value($wpc_metas[$product->id], 'parts', array()) && get_proper_value($wpc_metas[$product->id]['parts'], $part_key, array())
                        ) {
                            $part_media_id = get_proper_value($wpc_metas[$product->id]['parts'][$part_key], 'bg-inc', "");
                            if ($part_media_id || $part_media_id == "0")
                                $wpc_metas[$product->id]['parts'][$part_key]["enabled"] = 1;
                        }
                    }
                }

                update_post_meta($product->id, "wpc-metas", $wpc_metas);
            }

            $this->migrate_options_for_v3_8_1();

            update_option("wpd-db-version", "3.8.1");
        }
        return true;
    }

    private function migrate_options_for_v3_8_1() {
        $general = get_option("wpc-general-options");
        $text = get_option("wpc-texts-options");
        $shapes = get_option("wpc-shapes-options");
        $images = get_option("wpc-images-options");
        $designs = get_option("wpc-designs-options");
        $social = get_option("wpc_social_networks");
        $upload = get_option("wpc-upload-options");
        $user_interface = array(
            "action-box-text-color" => $general["action-label-color"],
            "action-box-background-color" => $general["action-label-color"],
            "action-box-background-color-hover" => $general["action-normal-color"],
            "cart-box-text-color" => $general["cart-label-color"],
            "cart-box-background-color" => $general["cart-normal-color"],
            "minus-btn-text-color" => $general["subtraction-label-color"],
            "minus-btn-background-color" => $general["subtraction-normal-color"],
            "plus-btn-text-color" => $general["addition-label-color"],
            "plus-btn-background-color" => $general["addition-normal-color"],
            "preview-btn-text-color" => $general["preview-label-color"],
            "preview-btn-background-color" => $general["preview-normal-color"],
            "preview-btn-background-color-hover" => $general["preview-selected-color"],
            "download-btn-text-color" => $general["download-label-color"],
            "download-btn-background-color" => $general["download-normal-color"],
            "download-btn-backhround-color-hover" => $general["download-selected-color"],
            "save-btn-text-color" => $general["save-label-color"],
            "download-btn-background-color" => $general["save-normal-color"],
            "download-btn-background-color-hover" => $general["save-selected-color"],
            "add-to-cart-btn-text-color" => $general["add-to-cart-label-color"],
            "add-to-cart-btn-background-color" => $general["add-to-cart-normal-color"],
            "add-to-cart-btn-background-color-hover" => $general["add-to-cart-selected-color"],
            "uploads-icon" => $upload["upload"],
            "uploads-text-color" => $upload["label-color"],
            "uploads-background-color" => $upload["normal-color"],
            "uploads-background-color-hover" => $upload["selected-color"],
            "facebook-icon" => $social["facebook"],
            "facebook-text-color" => $social["facebook-label-color"],
            "facebook-background-color" => $social["facebook-normal-color"],
            "facebook-background-color-hover" => $social["facebook-selected-color"],
            "instagram-icon" => $social["instagram"],
            "instagram-text-color" => $social["instagram-label-color"],
            "instagram-background-color" => $social["instagram-normal-color"],
            "instagram-background-color-hover" => $social["instagram-selected-color"],
            "text-icon" => $text["text"],
            "text-text-color" => $text["label-color"],
            "text-background-color" => $text["normal-color"],
            "text-background-color-hover" => $text["selected-color"],
            "shapes-icon" => $shapes["shapes"],
            "shapes-text-color" => $shapes["label-color"],
            "shapes-background-color" => $shapes["normal-color"],
            "shapes-background-color-hover" => $shapes["selected-color"],
            "cliparts-icon" => $images["image"],
            "cliparts-text-color" => $images["label-color"],
            "cliparts-background-color" => $images["normal-color"],
            "cliparts-background-color-hover" => $images["selected-color"],
            "my-designs-icon" => $designs["image"],
            "my-designs-text-color" => $designs["label-color"],
            "my-designs-background-color" => $designs["normal-color"],
            "my-designs-background-color-hover" => $designs["selected-color"],
            "grid" => $general["grid"],
            "clear" => $general["clear"],
            "delete" => $general["delete"],
            "duplicate" => $general["duplicate"],
            "send-to-back" => $general["back"],
            "bring-to-front" => $general["bring"],
            "flipV" => $general["flipV"],
            "flipH" => $general["flipH"],
            "centerH" => $general["centerH"],
            "centerV" => $general["centerV"],
            "undo" => $general["undo"],
            "redo" => $general["redo"],
        );

        update_option("wpc-ui-options", $user_interface);
    }

    /**
     * Runs the database upgrade for V3.12
     * @return boolean
     */
    private function update_db_for_v3_12() {
        $db_version = get_option("wpd-db-version");
        if (version_compare($db_version, "3.12", "<")) {
            $args = array(
                'numberposts' => -1,
                'post_type' => 'wpc-cliparts'
            );

            $cliparts_groups = get_posts($args);
            foreach ($cliparts_groups as $cliparts_group) {
                $cliparts = get_post_meta($cliparts_group->ID, "wpc-cliparts", true);
                $cliparts_prices = get_post_meta($cliparts_group->ID, "wpc-cliparts-prices", true);
                $group_meta = array();
                if (!empty($cliparts)) {
                    foreach ($cliparts as $i => $clipart_id) {
                        $attachment_url = o_get_proper_image_url($clipart_id);
                        $price = 0;
                        if (isset($cliparts_prices[$i]))
                            $price = $cliparts_prices[$i];
                        $row = array(
                            "id" => $clipart_id,
                            "url" => $attachment_url,
                            "price" => $price
                        );
                        array_push($group_meta, $row);
                    }
                }
                //New metas
                update_post_meta($cliparts_group->ID, "wpd-cliparts-data", $group_meta);
                //Old metas backup
                update_post_meta($cliparts_group->ID, "wpc-cliparts-old", $cliparts);
                update_post_meta($cliparts_group->ID, "wpc-cliparts-prices-old", $cliparts_prices);
                //Old meta removal
                delete_post_meta($cliparts_group->ID, "wpc-cliparts");
                delete_post_meta($cliparts_group->ID, "wpc-cliparts-prices");
            }
            update_option("wpd-db-version", "3.12");
        }
        return true;
    }

    private function get_part_datas($wpc_metas, $parts, $variation_id) {
        $global_array = $wpc_metas;
        if (is_array($parts)) {
            foreach ($parts as $part) {
                $part_key = sanitize_title($part);
                $global_array[$variation_id]['parts'][$part_key]['bg-inc'] = get_post_meta($variation_id, "wpc_$part_key", true);
                // delete_post_meta($variation_id,"wpc_$part_key");
                $global_array[$variation_id]['parts'][$part_key]['bg-not-inc'] = get_post_meta($variation_id, "wpc_bg-$part_key", true);
                //delete_post_meta($variation_id,"wpc_bg-$part_key");
                $global_array[$variation_id]['parts'][$part_key]['ov']['img'] = get_post_meta($variation_id, "wpc_ov-$part_key", true);
                //delete_post_meta($variation_id,"wpc_ov-$part_key");
                $global_array[$variation_id]['parts'][$part_key]['ov']['inc'] = get_post_meta($variation_id, "wpc_ovni-$part_key", true);
            }
        }
        return $global_array;
    }

    function wpc_add_custom_mime_types($mimes) {
        return array_merge($mimes, array(
            'svg' => 'image/svg+xml',
            'ttf' => 'application/x-font-ttf',
            'icc' => 'application/vnd.iccprofile',
                //'ttf' => 'application/x-font-truetype',
        ));
    }

    /**
     * Runs the products options migrations if needed
     * @return bool
     */
    function run_products_options_migration_if_needed() {
        $page_id = get_option('wpc_page_id');
        //if(!(!get_option('wpc_page_id')))
        $result = true;
        if ($page_id) {
            $general_options = get_option('wpc-general-options');
            $upload_options = get_option('wpc-upload-options');
            $colors_options = get_option('wpc-colors-options');
            $social_options = get_option('wpc_social_networks');
            $output_options = get_option('wpc-output-options');
            $general_options_datas = array('wpc_page_id', 'wpc-content-filter', 'wpc-parts-position-cart', 'wpc-download-btn', 'wpc-user-account-download-btn', 'wpc-send-design-mail',
                'wpc-preview-btn', 'wpc-save-btn', 'wpc-cart-btn', 'wpc-redirect-after-cart');
            $upload_options_datas = array("wpc-min-upload-width", "wpc-min-upload-height", "wpc-upl-extensions", "wpc-custom-designs-extensions", "wpc-uploader");
            $social_options_datas = array("wpc-facebook-app-id", "wpc-facebook-app-secret", "wpc-instagram-app-id", "wpc-instagram-app-secret");
            $output_options_datas = array("wpc-min-output-width", "wpc-output-loop-delay", "wpc-outputpdf-img-number", "wpc-outputpdf-img-col-number", "wpc-generate-layers",
                "wpc-generate-pdf", "wpc-generate-zip");

            if (empty($general_options)) {
                foreach ($general_options_datas as $general_option_data) {
                    $general_options[$general_option_data] = get_option($general_option_data);
                    delete_option($general_option_data);
                }
                $result = update_option("wpc-general-options", $general_options);
            }
            if ($result && empty($upload_options)) {
                foreach ($upload_options_datas as $upload_option_data) {
                    $upload_options[$upload_option_data] = get_option($upload_option_data);
                    delete_option($upload_option_data);
                }
                $result = update_option("wpc-upload-options", $upload_options);
            }
            if ($result && empty($colors_options)) {
                $colors_options['wpc-svg-colorization'] = get_option("wpc-svg-colorization");
                delete_option("wpc-svg-colorization");
                $result = update_option("wpc-colors-options", $colors_options);
            }
            if ($result && empty($social_options)) {
                foreach ($social_options_datas as $social_option_data) {
                    $social_options[$social_option_data] = get_option($social_option_data);
                    delete_option($social_option_data);
                }
                $result = update_option("wpc_social_networks", $social_options);
            }
            if ($result && empty($output_options)) {
                foreach ($output_options_datas as $output_option_data) {
                    $output_options[$output_option_data] = get_option($output_option_data);
                    delete_option($output_option_data);
                }
                update_option("wpc-output-options", $output_options);
            }
            if ($result)
                $this->init_globals();
        }
        return $result;
    }

    /**
     * Runs the database upgrade for V2.0
     * @return boolean
     */
    private function update_db_for_v2_0() {
        $message = $this->run_products_options_migration_if_needed();
        return $message;
    }

    private function handle_bulk_definition($data) {
        $buttons = get_proper_value($data, "design-buttons", false);
        $bounding_box = get_proper_value($data, "bounding-box", false);
        $products_parts = get_proper_value($data, "products-parts", false);
        $pricing_rules = get_proper_value($data, "pricing-rules", false);
        $output_settings = get_proper_value($data, "output-settings", false);
        $to_apply = $this->get_bulk_definition_data($data["datasource"], $buttons, $bounding_box, $products_parts, $pricing_rules, $output_settings);
        $keys = array("is-customizable", "can-design-from-blank", "can-upload-custom-design", "templates-page", "pricing-rules");
//                var_dump($to_apply);
        foreach ($data["apply_to"] as $root_product_id => $variations) {
//                    $tmp_meta=to_apply;
            $meta = get_post_meta($root_product_id, "wpc-metas", true);
            if (!is_array($meta))
                $meta = array();
            foreach ($keys as $key) {
                if (isset($to_apply[$key]))
                    $meta[$key] = $to_apply[$key];
            }

            if (isset($to_apply["variation_data"])) {
                //Variable product
                if (is_array($variations)) {
                    foreach ($variations as $variation_id) {
                        $meta[$variation_id] = $to_apply["variation_data"];
                    }
                } else {
                    //Simple product
                    $meta[$variations] = $to_apply["variation_data"];
                }
            }
//                    var_dump($root_product_id);
            update_post_meta($root_product_id, "wpc-metas", $meta);

//                    var_dump($root_product_id);
//                    echo "<br><br><br>";
//                    var_dump($meta);
//                    echo "<br><br><br>";
        }

        if (count($data["apply_to"])) {
            ?>
            <div class="notice notice-success">
                <p><?php _e("Done.", "wpd"); ?></p>
            </div>
            <?php
        }
    }

    private function get_bulk_definition_data($source_id, $buttons, $bounding_box, $products_parts, $pricing_rules, $output_settings) {
        $wpd_product = new WPD_Product($source_id);
        $root_id = $wpd_product->root_product_id;
        $new_metas = array();
        $metas = get_post_meta($root_id, "wpc-metas", true);
        if ($buttons) {
            $new_metas["is-customizable"] = $metas["is-customizable"];
            $new_metas["can-design-from-blank"] = $metas["can-design-from-blank"];
            $new_metas["can-upload-custom-design"] = $metas["can-upload-custom-design"];
            $new_metas["templates-page"] = $metas["templates-page"];
        }

        if ($bounding_box && isset($metas["bounding_box"]))
            $new_metas["bounding_box"] = $metas["bounding_box"];

        if ($products_parts) {
            $new_metas["variation_data"] = array(
                "canvas-w" => $metas[$source_id]["canvas-w"],
                "canvas-h" => $metas[$source_id]["canvas-h"],
                "watermark" => $metas[$source_id]["watermark"],
                "parts" => $metas[$source_id]["parts"]
            );
        }

        if ($output_settings)
            $new_metas["variation_data"]["output-settings"] = $metas[$source_id]["output-settings"];

        if ($pricing_rules)
            $new_metas["pricing-rules"] = $metas["pricing-rules"];

        return $new_metas;
    }

}
