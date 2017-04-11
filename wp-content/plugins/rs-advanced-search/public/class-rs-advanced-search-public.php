<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ratkosolaja.info/
 * @since      1.0.0
 *
 * @package    RS_Advanced_Search
 * @subpackage RS_Advanced_Search/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    RS_Advanced_Search
 * @subpackage RS_Advanced_Search/public
 * @author     Ratko Solaja <me@ratkosolaja.info>
 */
class RS_Advanced_Search_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$options = get_option( $this->plugin_name . '-settings' );
		
		if ( ! empty( $options['toggle-css'] ) && $options['toggle-css'] == 1 ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rs-advanced-search-public.css', array(), $this->version, 'all' );
		}
		if ( ! empty( $options['toggle-select2'] ) && $options['toggle-select2'] == 1 ) {
			wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), '4.0.3', 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$options = get_option( $this->plugin_name . '-settings' );

		if ( ! empty( $options['toggle-select2'] ) && $options['toggle-select2'] == 1 ) {
			wp_enqueue_script( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array( 'jquery' ), '4.0.3', true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rs-advanced-search-public.js', array( 'jquery' ), $this->version, true );
		}

	}

	/**
	 * Override search query.
	 *
	 * @since    1.0.0
	 */
	public function override_search_query( $query ) {

		$options = get_option( $this->plugin_name . '-settings' );
		$taxonomies = array();
		$relation = 'OR';

		if ( ! empty( $options['taxonomy'] ) ) {
			$taxonomies = $options['taxonomy'];
		}

		if ( ! empty( $options['toggle-relation'] ) ) {
			$relation = $options['toggle-relation'];
		}

		if ( ! empty( $taxonomies ) ) {
			if ( $query->is_search() ) {
				$filter = array(
					'relation' => $relation
				);
				foreach ( $taxonomies as $tax ) {
					if ( isset( $_GET['select-' . $tax . ''] ) ) {
						$selected = $_GET['select-' . $tax . ''];
						if ( $selected == 'all' ) {
							$terms = get_terms( $tax, array( 'hide_empty' => false, 'fields' => 'ids' ) );
							$filter[] = array(
								'taxonomy' => $tax,
								'field'    => 'term_id',
								'terms'    => $terms
							);
						} else {
							$filter[] = array(
								'taxonomy' => $tax,
								'field'    => 'term_id',
								'terms'    => $selected
							);
						}
					}
				}
				$query->set( 'tax_query', $filter );
				return $query;
			}
		}

	}

	/**
	 * Create advanced search shortcode.
	 *
	 * @since    1.0.0
	 */
	public function advanced_search_shortcode() {

	  
		/*$options = get_option( $this->plugin_name . '-settings' );
		$input = 0;
		$taxonomies = array();

		if ( ! empty( $options['toggle-search-input'] ) ) {
			$input = $options['toggle-search-input'];
		}
		if ( ! empty( $options['taxonomy'] ) ) {
			$taxonomies = $options['taxonomy'];
		}*/
		$taxonomies = array();
		$args = array(
			  'orderby' => 'name',
			  'order' => 'ASC'
			 
		);
		$categories = get_categories($args);
		$taxonomies = get_categories($args);

		$form = '<form role="search" class="search-form rs-advanced-search-form rs-advanced-search-shortcode" method="get" action="' . home_url( '/' ) . '">';
		
		$input_class = '';
		if ( $input == 1 ) {
			$input_class = 'search-field-hide';
		}
		$form .= '<div class="advanced-search-text"><input type="search" class="search-field input-text-search ' . esc_attr( $input_class ) . '" placeholder="' . esc_attr_x( 'Enter keyword', 'placeholder', 'rs-advanced-search' ) . '" name="s" /></div>';
		
		
		/*$form .= '<div class="advanced-search-text">';
		$form .= '<select id="edit-ad-category" name="category">';
							$form .= '<option value="all">' . esc_html( $menu_name, 'rs-advanced-search' ) . '</option>';
							foreach ( $terms as $term ) {
								$form .= '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';
							}
						$form .= '</select>';
		$form .= '</div>'; */
		$form .= '<div class="rs-advanced-search-inline-select">';
		$form .= '<select id="edit-ad-category" name="category" class="advanced-search-select-box">';
														
												$form .= '<option value="">Quick Search By Category</option>';
												foreach ($categories as $cat) {
														if ($cat->category_parent == 0) { 
															$catID = $cat->cat_ID;
														
															$form .= '<option value='. $catID. '> '.$cat->cat_name .' </option>';
																				
													
														$args2 = array(
															'hide_empty' => '0',
															'parent' => $catID
														);
														$categories = get_categories($args2);
														foreach ($categories as $cat) { 
															$form .= '<option value='. $cat->cat_ID. ' >'.$cat->cat_name.'</option>';
													 } 

													 }  
													
												} 
												
													

											$form .= '</select>';
		$form .='</div>';
		$form .= '<div class="rs-advanced-search-inline-select">';
		$form .= '<select id="select-category" name="select-category" class="advanced-search-select-box">';
														
												$form .= '<option value="">Full Search By Category</option>';
												foreach ($taxonomies as $cat) {
														if ($cat->category_parent == 0) { 
															$catID = $cat->cat_ID;
														
															$form .= '<option value='. $catID. '> '.$cat->cat_name .' </option>';
																				
													
														$args2 = array(
															'hide_empty' => '0',
															'parent' => $catID
														);
														$categories = get_categories($args2);
														foreach ($categories as $cat) { 
															$form .= '<option value='. $cat->cat_ID. ' >'.$cat->cat_name.'</option>';
														} 

													 }  
													
												} 
												
													

											$form .= '</select>';
		$form .='</div>';
		
		
		$form .= '<div class="search-btn-style"><input type="submit" class="search-submit-input apend_active" value="' . esc_attr_x( 'Search', 'submit button', 'rs-advanced-search' ) . '" /></div>';
		$form .= '</form>';

		return $form;

	}

}