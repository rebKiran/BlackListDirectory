<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'VBC_CPTs' ) ) :


class VBC_CPTs extends VBC_CPT_Meta_Options {	
	

	public function __construct() 
	{
		add_action('init', array($this, 'register_wp_pro_ad_system_posttypes'));
		add_filter( 'enter_title_here', array($this, 'change_default_cpt_title'));
		
		add_action( 'add_meta_boxes', array($this, 'vbc_banners_meta_options'));
		add_action( 'save_post', array($this, 'vbc_banners_meta_options_save_postdata' ));
		
	}
	
	
	
	
	
	
	/*
	 * Create CPTs
	 *
	 * @access public
	 * @return null
	*/
	public function register_wp_pro_ad_system_posttypes() 
	{
		$cpts = array();
		$vbc_cpt_supports = apply_filters( 'vbc_cpt_supports', array('title') );
		$cpts[0] = array(
			'name'               => __('VBC Banners', 'wpproads'),
			'name_clean'         => 'vbc_banners',
			'singular_name'		 => __('VBC Banner', 'wpproads'),
			'supports'           => $vbc_cpt_supports //$supports = array('title','editor','author','thumbnail','excerpt','comments','revisions', 'custom-fields');
		);
		
		foreach( $cpts as $cpt )
		{	
			$labels = array(
				'name' 				=> $cpt['name'],
				'singular_name'		=> $cpt['singular_name'],
				'add_new' 			=> sprintf( __( 'Add New %s', 'wpproads' ), $cpt['singular_name']),
				'add_new_item' 		=> sprintf( __( 'Add New %s', 'wpproads' ), $cpt['singular_name']),
				'edit_item' 		=> sprintf( __( 'Edit %s', 'wpproads' ), $cpt['singular_name']),
				'new_item' 			=> sprintf( __( 'New %s', 'wpproads' ), $cpt['singular_name']),
				'view_item' 		=> sprintf( __( 'View %s', 'wpproads' ), $cpt['singular_name']),
				'search_items' 		=> sprintf( __( 'Search %s', 'wpproads' ), $cpt['name']),
				'not_found' 		=> sprintf( __( 'No %s Found', 'wpproads' ), $cpt['name']),
				'not_found_in_trash'=> sprintf( __( 'No %s Found in Trash', 'wpproads' ), $cpt['name']),
				'parent_item_colon' => '',
				'menu_name'			=> $cpt['name']
			);
			
			$taxonomies = array(); // 'post_tag', 'category'
			$supports = $cpt['supports'];
			
			$post_type_args = array(
				'labels' 			=> $labels,
				'singular_label' 	=> $cpt['name'],
				'public' 			=> true,
				'show_ui' 			=> true,
				'publicly_queryable'=> true,
				'query_var'			=> true,
				'capability_type' 	=> 'post',
				'has_archive' 		=> true,
				'hierarchical' 		=> false,
				'rewrite' 			=> array('slug' => $cpt['name_clean'], 'with_front' => false ),
				'supports' 			=> $supports,
				'show_in_menu'      => 'wp-banner-creator',
				'taxonomies'		=> $taxonomies
			 );
			 register_post_type($cpt['name_clean'], $post_type_args);
			 
			 // Extra Filters
			 add_filter('manage_edit-'.$cpt['name_clean'].'_columns', array($this, $cpt['name_clean'].'_columns'));
			 add_action('manage_posts_custom_column',  array($this, $cpt['name_clean'].'_show_columns'));
		}
	}
	
	
	
	
	/*
	 * Change the post title placeholder for the CPT's
	 *
	 * @access public
	 * @return string
	*/
	public function change_default_cpt_title( $title )
	{
		$screen = get_current_screen();
		
		if ( $screen->post_type == 'vbc_banners' )
		{
			$title = 'Banner Title';
		}
	}
	
	
	
	
	
	
	
	// vbc_banners ----------------------------------------------------------
	
	public function vbc_banners_columns( $existing_columns ) 
	{
		if ( empty( $existing_columns ) && ! is_array( $existing_columns ) ) {
			$existing_columns = array();
		}
		unset( $existing_columns['title'], $existing_columns['comments'], $existing_columns['date'] );

		$columns          = array();
		$columns['cb']    = '<input type="checkbox" />';
		$columns['vbc_banner'] = '';
		$columns['vbc_name'] = __('Name', 'wpproads');
		
		return array_merge( $columns, $existing_columns );
	}
	public function vbc_banners_show_columns($name) 
	{
		global $post;
		
		switch ($name) 
		{
			case 'vbc_banner' :
				
				$banner_url = get_post_meta( $post->ID, '_banner_url', true );
				$banner_data_uri = get_post_meta( $post->ID, '_banner_bc_data_uri', true );
				
				$img = !empty($banner_url) ? $banner_url : WP_BC_INC_URL.'/images/placeholder.png';
				$html = '<div class="preview_banner" style="background: url('.$img.') no-repeat center center; width:40px; height:40px; background-size: cover;"></div>';
				
				echo $html;
			break;
			case 'vbc_name' :
				$edit_link        = get_edit_post_link( $post->ID );
				$title            = _draft_or_post_title();
				$post_type_object = get_post_type_object( $post->post_type );
				$can_edit_post    = current_user_can( $post_type_object->cap->edit_post, $post->ID );

				echo '<strong><a class="row-title" href="' . esc_url( $edit_link ) .'">' . $title.'</a>';
					_post_states( $post );
				echo '</strong>';

				if ( $post->post_parent > 0 ) 
				{
					echo '&nbsp;&nbsp;&larr; <a href="'. get_edit_post_link( $post->post_parent ) .'">'. get_the_title( $post->post_parent ) .'</a>';
				}

				// Excerpt view
				if ( isset( $_GET['mode'] ) && 'excerpt' == $_GET['mode'] ) 
				{
					echo apply_filters( 'the_excerpt', $post->post_excerpt );
				}

				// Get actions
				$actions = array();

				$actions['id'] = 'ID: ' . $post->ID;

				if ( $can_edit_post && 'trash' != $post->post_status ) 
				{
					$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item', 'wpproads' ) ) . '">' . __( 'Edit', 'wpproads' ) . '</a>';
					$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline', 'wpproads' ) ) . '">' . __( 'Quick&nbsp;Edit', 'wpproads' ) . '</a>';
				}
				if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) 
				{
					if ( 'trash' == $post->post_status ) 
					{
						$actions['untrash'] = '<a title="' . esc_attr( __( 'Restore this item from the Trash', 'wpproads') ) . '" href="' . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-post_' . $post->ID ) . '">' . __( 'Restore', 'wpproads' ) . '</a>';
					} elseif ( EMPTY_TRASH_DAYS ) {
						$actions['trash'] = '<a class="submitdelete" title="' . esc_attr( __( 'Move this item to the Trash', 'wpproads' ) ) . '" href="' . get_delete_post_link( $post->ID ) . '">' . __( 'Trash', 'wpproads' ) . '</a>';
					}

					if ( 'trash' == $post->post_status || ! EMPTY_TRASH_DAYS ) 
					{
						$actions['delete'] = '<a class="submitdelete" title="' . esc_attr( __( 'Delete this item permanently', 'wpproads' ) ) . '" href="' . get_delete_post_link( $post->ID, '', true ) . '">' . __( 'Delete Permanently', 'wpproads' ) . '</a>';
					}
				}
				if ( $post_type_object->public ) 
				{
					if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) 
					{
						if ( $can_edit_post )
							$actions['view'] = '<a href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;', 'wpproads' ), $title ) ) . '" rel="permalink">' . __( 'Preview', 'wpproads' ) . '</a>';
					} elseif ( 'trash' != $post->post_status ) {
						$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;', 'wpproads' ), $title ) ) . '" rel="permalink">' . __( 'View', 'wpproads' ) . '</a>';
					}
				}

				$actions = apply_filters( 'post_row_actions', $actions, $post );

				echo '<div class="row-actions">';

				$i = 0;
				$action_count = sizeof($actions);

				foreach ( $actions as $action => $link ) 
				{
					++$i;
					( $i == $action_count ) ? $sep = '' : $sep = ' | ';
					echo '<span class="' . $action . '">' . $link . $sep . '</span>';
				}
				echo '</div>';
				
				get_inline_data( $post );

			break;
			
		}
	}
	
	
	
}

endif;
?>