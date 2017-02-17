<?php
class Bc_Templates {	

	public function __construct() 
	{	
		//date_default_timezone_set(get_option('timezone_string'));
	}
	
	
	
	
	
	
	public function banner_creator_canvas_area( $args = array() )
	{
		$defaults = array(
			'frontend'      => 0,
			'post_id'       => 0,
			'data_uri'      => '',
			'json'          => '',
			'save_design'   => 1,
			'export'        => 1
		);
		$arr = wp_parse_args( $args, $defaults );
		if ( !is_admin() ) { 
			global $wpdb;
				$last = $wpdb->get_row("SHOW TABLE STATUS LIKE '$wpdb->posts'");
				$postid = $last->Auto_increment;
				$arr['post_id'] = $postid;
		}
		$html = '';
		$html.= '<div class="pro_ads_banner_creator span6">';
            $html.= '<div id="bc_canvas_holder">';
                $html.= '<canvas id="bcCanvas" width="450" height="500">'.__('Canvas not suported','wpproads').'</canvas>';
            $html.= '</div>';
            
            $html.= '<div id="button_tools_cont" style="margin:10px 0 20px 0;"> ';
                $html.= '<a class="button-secondary" id="clear_bc">'.__('Clear','wpproads').'</a> ';
                $html.= '<a class="button-secondary" id="preview_bc" title="'.__('Preview Banner','wpproads').'"><i class="fa fa-search"></i></a> ';
                $html.= $arr['save_design'] ? '<a class="button-primary" id="save_bc" title="'.__('Save Design','wpproads').'" ><i class="fa fa-save"></i></a> ' : '';
                $html.= $arr['export'] ? '<a class="button-primary" id="export_bc">'.__('Export Banner (.PNG)','wpproads').'</a>' : '';
            $html.= '</div>'; 
			
            $html.= '<div id="button_tools_cont" style="margin:10px 0 20px 0;"></div>';
           
            $html.= '<ul id="log"></ul>';
            
			$html.= '<input type="hidden" id="banner_id" name="banner_id" value="'.$arr['post_id'].'" />';
			$html.= '<input type="hidden" id="image_save_id" name="image_save_id" value="0" />';
			$html.= '<input type="hidden" id="bc_ajaxurl" value="'.admin_url('admin-ajax.php').'" />';
			$html.= '<input type="hidden" id="banner_url" name="banner_url" value="" />';
            $html.= '<input type="hidden" id="banner_data_uri" name="banner_data_uri" value="'.$arr['data_uri'].'" />';
            $html.= '<textarea style="display:none;" id="banner_json" name="banner_json">'.$arr['json'].'</textarea>';
			$html.= '<textarea style="display:none;" id="banner_json_64" name="banner_json_64">'.base64_encode($arr['json']).'</textarea>';
        $html.= '</div>';
		
		return $html;
	}
	
	
	
	
	
	
	
	public function banner_creator_sidebar_area( $args = array() )
	{
		$defaults = array(
			'frontend'       => 0,
			'image_upload'   => 1,
			'data_uri'       => '',
			'json'           => '',
			'size'           => '300x250'
		);
		$arr = wp_parse_args( $args, $defaults );
		
		$html = '';
		$html.= '<div class="sidebar vbc_sidebar span6">';
            $html.= '<div id="widgets-right" class="pro_ads_banner_creator">';
			
				$html.= '<div class="menu_cont">';
	
					$html.= '<div class="menu_icons">';
						$html.= '<a class="vbc_tooltip selected" title="Banner Size" data-target="vbc_banner_size"><i class="fa fa-expand"></i></a>';
						$html.= '<a class="vbc_tooltip vbc_edit_element_btn" title="Edit Selected Element" data-target="vbc_edit_elements"><i class="fa fa-pencil-square-o"></i></a>';
						$html.= '<a class="vbc_tooltip" title="Add Images" data-target="vbc_add_images"><i class="fa fa-image"></i></a>';
						$html.= '<a class="vbc_tooltip" title="Add Objects" data-target="vbc_add_objects"><i class="fa fa-square"></i></a>';
						$html.= '<a class="vbc_tooltip" title="Add Text" data-target="vbc_add_text"><i class="fa fa-font"></i></a>';
						$html.= '<a class="vbc_tooltip" title="Load SVG" data-target="vbc_load_svg"><i class="fa fa-star-half-o"></i></a>';
						$html.= '<a class="vbc_tooltip" title="Drawing Mode" data-target="vbc_drawing"><i class="fa fa-pencil"></i></a>';
					$html.= '</div>';
					$html.= '<div class="menu_options">';
						$html.= '<div class="vbc_banner_size vbc_menu_option" style="display:inline-block;">';
							$html.= $this->bc_banner_size_wgt( $arr['size'] );
						$html.= '</div>';
						$html.= '<div class="vbc_edit_elements vbc_menu_option">';
							$html.= $this->bc_edit_element_wgt();
						$html.= '</div>';
						$html.= '<div class="vbc_add_images vbc_menu_option">';
							$html.= $this->bc_add_imaget_wgt( $arr['frontend'], $arr['image_upload'] );
						$html.= '</div>';
						$html.= '<div class="vbc_add_objects vbc_menu_option">';
							$html.= $this->bc_add_object_wgt();
						$html.= '</div>';
						$html.= '<div class="vbc_add_text vbc_menu_option">';
							$html.= $this->bc_add_text_wgt();
						$html.= '</div>';
						$html.= '<div class="vbc_load_svg vbc_menu_option">';
							$html.= $this->bc_load_svg_wgt();
						$html.= '</div>';
						$html.= '<div class="vbc_drawing vbc_menu_option">';
							$html.= $this->bc_load_drawing_wgt();
						$html.= '</div>';
					  $html.= '</div>';
					
					 $html.= '</div>';
					
					$html.= '<div class="clearFix"></div>';
				$html.= '</div>';
				 $html.= '<div style="padding:5px;margin-left:100px;padding-top:20px;"><input name="add-to-cart" id="add-to-cart" type="hidden" value="" /><input name="quantity" type="hidden" value="1" min="1"  />
					<a class="theme_btn" id="add-to-cart_product" class="create_cart">'.__('Add To Cart','wpproads').'</a></div>';
			$html.= '</div>';
		return $html;
	}
	
	
	
	
	
	
	
	
	
	/*
	 * SIDEBAR WIDGETS
	*/
	
	// Banner Size
	public function bc_banner_size_wgt( $size = '300x250' )
	{	
		$default_banner_sizes = array(
			array(
				'size'  => '468x60',
				'label' => 'IAB Full Banner (468 x 60)'
			),
			array(
				'size'  => '120x600',
				'label' => 'IAB Skyscraper (120 x 600)'
			),
			array(
				'size'  => '728x90',
				'label' => 'IAB Leaderboard (728 x 90)'
			),
			array(
				'size'  => '300x250',
				'label' => 'IAB Medium Rectangle (300 x 250)'
			),
			array(
				'size'  => '120x90',
				'label' => 'IAB Button 1 (120 x 90)'
			),
			array(
				'size'  => '160x600',
				'label' => 'IAB Wide Skyscraper (160 x 600)'
			),
			array(
				'size'  => '120x60',
				'label' => 'IAB Button 2 (120 x 60)'
			),
			array(
				'size'  => '125x125',
				'label' => 'IAB Square Button (125 x 125)'
			),
			array(
				'size'  => '180x150',
				'label' => 'IAB Rectangle (180 x 150)'
			),
		);
		
		$custom_banner_sizes = apply_filters( 'vbc_custom_banner_sizes', array() );
		 if ( is_admin() ) { 
			$banner_sizes = wp_parse_args( $custom_banner_sizes, $default_banner_sizes );
			
		} else { 
			global $wpdb;
			$querystr = "
							   SELECT $wpdb->postmeta.meta_value as size, $wpdb->posts.post_title as label
							   FROM $wpdb->posts, $wpdb->postmeta
							   WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
							   AND $wpdb->postmeta.meta_key = '_banner_bc_size' 
							   AND $wpdb->posts.post_status = 'publish' 
							   AND $wpdb->posts.post_type = 'vbc_banners'
							   ORDER BY $wpdb->posts.post_title ASC
							";
			//echo $querystr;
			$banner_sizes = $wpdb->get_results($querystr,ARRAY_A);
			
		}
		$size = empty($size) ? '300x250' : $size;
		$bc_size = explode('x', $size);
		
		$size = $size != '468x60' && $size != '120x600' && $size != '728x90' && $size != '300x250' && $size != '120x90' && $size != '160x600' && $size != '120x60' && $size != '125x125' && $size != '180x150' ? 'custom' : $size;
		
		
		$html = '';
		$html.= '<div>';
			$html.= '<div id="sidebar" >';
				$html.= '<div class="sidebar-name open" style="background:#FFF;">';
					//$html.= '<div class="sidebar-name-arrow"></div>';
					$html.= '<h3 style="background-image: none;"><span class="vbc_btn_icon">-</span> '.__('Banner Size','wpproads').'</h3>';
				$html.= '</div>';
				$html.= '<div class="sidebar-description" style="padding:10px; background:#EFEFEF;">';
					$html.= '<div style="padding:5px;">';
						$html.= '<select id="banner_size" name="banner_size">';
						$html.= '<option value="" selected="selected">'.__('Select Banner','wpproads').'</option>';
							/*$html.= '<option value="">'.__('-- Select your banner size --','wpproads').'</option>';
							$html.= $size == '468x60' ? '<option value="468x60" selected="selected">' : '<option value="468x60">';
								$html.= 'IAB Full Banner (468 x 60)';
							$html.= '</option>';
							$html.= $size == '120x600' ? '<option value="120x600" selected="selected">' : '<option value="120x600">';
								$html.= 'IAB Skyscraper (120 x 600)';
							$html.= '</option>';
							$html.= $size == '728x90' ? '<option value="728x90" selected="selected">' : '<option value="728x90">';
								$html.= 'IAB Leaderboard (728 x 90)';
							$html.= '</option>';
							$html.= $size == '300x250' ? '<option value="300x250" selected="selected">' : '<option value="300x250">';
								$html.= 'IAB Medium Rectangle (300 x 250)';
							$html.= '</option>';
							$html.= $size == '120x90' ? '<option value="120x90" selected="selected">' : '<option value="120x90">';
								$html.= 'IAB Button 1 (120 x 90)';
							$html.= '</option>';
							$html.= $size == '160x600' ? '<option value="160x600" selected="selected">' : '<option value="160x600">';
								$html.= 'IAB Wide Skyscraper (160 x 600)';
							$html.= '</option>';
							$html.= $size == '120x60' ? '<option value="120x60" selected="selected">' : '<option value="120x60">';
								$html.= 'IAB Button 2 (120 x 60)';
							$html.= '</option>';
							$html.= $size == '125x125' ? '<option value="125x125" selected="selected">' : '<option value="125x125">';
								$html.= 'IAB Square Button (125 x 125)';
							$html.= '</option>';
							$html.= $size == '180x150' ? '<option value="180x150" selected="selected">' : '<option value="180x150">';
								$html.= 'IAB Rectangle (180 x 150)';
							$html.= '</option>';
							*/
							foreach( $banner_sizes as $banner_size )
							{
								
								$html.= $size == $banner_size['size'] ? '<option value="'.$banner_size['size'].'" >' : '<option value="'.$banner_size['size'].'">';
									$html.= $banner_size['label'];
								$html.= '</option>';
							}
							 if ( is_admin() ) { 
							$html.= $size == 'custom' ? '<option value="custom" selected="selected">' : '<option value="custom">';
								$html.= __('Custom','wpproads');
							$html.= '</option>';
							 }
						$html.= '</select>';
					$html.= '</div>';
					$html.= '<hr />';
				  $html.= '<span id="banner_price" class="woocommerce-Price-amount amount" style="display:none;"></span>';
					$html.= '<input type="hidden" id="hid_banner_price" name="hid_banner_price" value=""  />';
				   $html.= '<input type="hidden" id="hid_product_id" name="hid_product_id" value=""  />';
					//$html.= '<hr />';
					 if ( is_admin() ) { 
						$html.= $size != 'custom' ? '<div id="custom_size" style="padding:5px; display:none;">' : '<div id="custom_size" style="padding:5px;">';
						$html.= '<div>'.__('Width:','wpproads').'</div><div><input type="number" value="'.$bc_size[0].'" id="canvas_width" style="width:100px;" />px.</div>';
						$html.= '<div>'.__('Height:','wpproads').'</div><div><input type="number" value="'.$bc_size[1].'" id="canvas_height" style="width:100px;" />px.</div>';
					 	
					$html.= '</div>';
					
					
				 // $html.= '</div>';
				}
				$html.= '<div style="padding:5px;"><a class="button-secondary" id="update_canvas">'.__('Update','wpproads').'</a></div>';
			$html.= '</div>';
		$html.= '</div>';
		$html.= '</div>';
		
		
		return $html;
	}
	
	
	// Edit Elements
	public function bc_edit_element_wgt()
	{
		global $bc_google_fonts;
		
		$html = '';
		$html.= '<div>';
			$html.= '<div id="sidebar" >';
				$html.= '<div class="sidebar-name edit-element-tab open" style="background:#FFF;">';
					//$html.= '<div class="sidebar-name-arrow"></div>';
					$html.= '<h3><span class="vbc_btn_icon">-</span> '.__('Edit Elements','wpproads').'</h3>';
				$html.= '</div>';
				$html.= '<div class="sidebar-description" style="padding:10px; background:#EFEFEF;">';
					//$html.= '<h4>'.__('Edit the current selected element.','wpproads').'</h4>';
					
					// No item selected
					$html.= '<div class="edt_no_item_selected" style="padding:5px;">';
						$html.= '<div><small>'.__('- No item selected -','wpproads').'</small></div>';
					$html.= '</div>';
					
					$html.= '<div class="edt_container" style="display:none;">';
						// Main options
						$html.= '<div style="padding:5px;">';
							$html.= '<a class="button-secondary bc_remove_selected_element vbc_tooltip" title="'.__('Remove selected item','wpproads').'"><i class="fa fa-trash"></i></a> ';
							$html.= '<a class="button-secondary bc_bring_to_front_selected_element vbc_tooltip" title="'.__('Move Up','wpproads').'"><i class="fa fa-long-arrow-up"></i></a> ';
							$html.= '<a class="button-secondary bc_send_to_back_selected_element vbc_tooltip" title="'.__('Move Down','wpproads').'"><i class="fa fa-long-arrow-down"></i></a>';
							$html.= '<a class="button-secondary vbc_tooltip" id="undo_action" title="'.__('Undo','wpproads').'" style="margin-left: 3px;">'.__('Undo','wpproads').'</a>';
						$html.= '</div>';
						
						// rect border radius
						$html.= '<div style="display:none;" class="edt_border_radius_box">';
							$html.= '<div><h4>'.__('Object Settings.','wpproads').'</h4></div>';
							$html.= '<div style="padding:5px;">';
								$html.= '<div><small>'.__('Border Radius','wpproads').'</small></div>';
								$html.= '<input type="number" min="0" step="1" class="edt_object_border_radius" value="0" />';
							$html.= '</div>';
						$html.= '</div>';
						
						// Text values
						$html.= '<div class="text_values" style="display:none;">';
							$html.= '<div><h4>'.__('Font Settings.','wpproads').'</h4></div>';
							
							$html.= '<div style="margin: 4px;">';
								$html.= '<div><small>'.__('Font','wpproads').'</small></div>';
								$html.= '<select style="position:absolute; left:0; top:0; width:100%;" class="edt_font_name chosen-select" data-placeholder="'.__('Choose a Font','wpproads').'">';
									$html.= '<optgroup label="Classic Fonts">';
										$html.= '<option value="Verdana"></option>';
										$html.= '<option value="Verdana">Verdana</option>';
										$html.= '<option value="Arial">'.__('Arial','wpproads').'</option>';
										$html.= '<option value="Impact">'.__('Impact','wpproads').'</option>';
									$html.= '</optgroup>';
									$html.= '<optgroup label="Google Fonts">';
										$html.= $bc_google_fonts->form_select_options();
									$html.= '</optgroup>';
								$html.= '</select>';
								
								$html.= '<script type="text/javascript">';
								$html.= 'jQuery(document).ready(function($){';
									$html.= 'var $input = jQuery(\'.edt_font_name\');';
									$html.= 'var $select = jQuery(\'.edt_font_name_select\');';
									 
									$html.= '$select.change(function(){ modify(); })';
									$html.= 'function modify(){ $input.val($select.val()) /*output(); }'; 
									$html.= 'function output(){ jQuery(\'p\').text(\'value: \' + $input.val()); }';
									 
									$html.= ' $input.on(\'click\', function(){';
										$html.= 'jQuery(this).select()';
									$html.= '}).on(\'blur\', function(){';
										$html.= '//output();';
									$html.= '})';
									 
									$html.= 'modify();';
								$html.= '});';
								$html.= '</script>';
							$html.= '</div>';
							$html.= '<div style="padding:5px;">';
								$html.= '<div><small>'.__('Text Align','wpproads').'</small></div>';
								$html.= '<a class="button-secondary txt_align_left" title="'.__('Left','wpproads').'"><i class="fa fa-align-left"></i></a>';
								$html.= '<a class="button-secondary txt_align_center" title="'.__('Center','wpproads').'"><i class="fa fa-align-center"></i></a>';
								$html.= '<a class="button-secondary txt_align_right" title="'.__('Right','wpproads').'"><i class="fa fa-align-right"></i></a>';
							$html.= '</div>';
							$html.= '<div style="padding:5px;">';
								$html.= '<div><small>'.__('LineHeight','wpproads').'</small></div>';
								$html.= '<input type="number" class="txt_line_height" step=".1" placeholder="'.__('Line Height','wpproads').'" />';
							$html.= '</div>';
							$html.= '<div style="padding:5px;">';
								$html.= '<div><small>'.__('Font Weight','wpproads').'</small></div>';
								$html.= '<select class="txt_font_weight">';
									$html.= '<option value="100">100</option>';
									$html.= '<option value="200">200</option>';
									$html.= '<option value="300">300</option>';
									$html.= '<option value="400">400 - normal</option>';
									$html.= '<option value="500">500</option>';
									$html.= '<option value="600">600</option>';
									$html.= '<option value="700">700</option>';
									$html.= '<option value="800">800 - bold</option>';
									$html.= '<option value="900">900</option>';
								$html.= '</select>';
							$html.= '</div>';
						$html.= '</div>';
						
						// Color
						$html.= '<div><h4>'.__('Default options','wpproads').'</h4></div>';
						$html.= '<div style="padding:5px;">';
							$html.= '<div><small>'.__('Fill Color','wpproads').'</small></div>';
							$html.= '<input type="text" id="update_object_bg_color" value="" class="imge-color-field" />';
						
						$html.= '</div>';
						
						$html.= '<div style="padding:5px;">';
							$html.= '<div><small>'.__('Stroke Color','wpproads').'</small></div>';
							$html.= '<input type="text" id="update_object_stroke_color" value="" class="imge-color-field" />';
						$html.= '</div>';
						
						// Opacity
						$html.= '<div style="padding:5px;">';
							$html.= '<div><small>'.__('Opacity','wpproads').'</small></div>';
							$html.= '<input type="range" min="0" max="1" step=".1" class="edt_object_opacity" value="1" />';
						$html.= '</div>';
						
						$html.= '<div style="padding:5px;"><a class="button-primary" id="update_element">'.__('Update Element','wpproads').'</a></div>';
					$html.= '</div>';
					
				$html.= '</div>';
			$html.= '</div>';
		$html.= '</div>';
		
		return $html;
	}
	
	
	
	
	
	
	// Add Image
	public function bc_add_imaget_wgt( $frontend = 0, $image_upload = 1 )
	{
		$html = '';
		$html.= '<div>';
			$html.= '<div id="sidebar" >';
				$html.= '<div class="sidebar-name open" style="background:#FFF;">';
					//$html.= '<div class="sidebar-name-arrow"></div>';
					$html.= '<h3><span class="vbc_btn_icon">-</span> '.__('Add Image','wpproads').'</h3>';
				$html.= '</div>';
				$html.= '<div class="sidebar-description" style="padding:10px; background:#EFEFEF;">';
					
					if( $image_upload )
					{
						$html.= '<div id="img_loader_cont" style="display:none;"><!--<img style="display:none;" id="image_loader" src="" />--></div>';
					
						// Backend upload
						if( !$frontend )
						{
							$html.= '<div style="padding:5px;">';
								$html.= '<a class="button-secondary" id="select_image_url">'.__('Select Image','wpproads').'</a>';
								$html.= '<span id="img_url" style="font-size:11px;"></span>';
								$html.= '<input type="hidden" id="image_url" readonly="readonly" />';
							$html.= '</div>';
						}
						else
						{
							// Frontend Upload
							$html.= '<div style=" display:inline-block;">';
								
								$adzone_size_style = !empty($arr['size']) ? 'width:'.$arr['size'][0].'px; height:'.$arr['size'][1].'px;' : 'width:100%; height:auto;';
								
								$html.= '<div id="visualbannercreator-upload-container">';
									$html.= '<a id="visualbannercreator-uploader" class="visualbannercreator_button wpproads_button" href="#">'.__('Select your Banner','wpproads').'</a>';
								
									$html.= '<div id="visualbannercreator-upload-imagelist">';
										$html.= '<ul id="visualbannercreator-ul-list" class="visualbannercreator-upload-list"></ul>';
									$html.= '</div>';
									
								$html.= '</div>';
							$html.= '</div>';
							$html.= '<script>';
								$html.= 'vbc_load_ajax_upload("'.admin_url( 'admin-ajax.php').'");';
							$html.= '</script>';
							// end frontend upload
						}
						
						
						
						$html.= '<div style="padding:5px;">';
							$html.= '<div><small>'.__('Opacity','wpproads').'</small></div>';
							$html.= '<input type="range" min="0" max="1" step=".1" id="image_opacity" value="1" />';
						$html.= '</div>';
						$html.= '<div style="padding:5px;"><a class="button-primary "id="add_image">'.__('Add Image','wpproads').'</a></div>';
					}
					else
					{
						$html.= __('Image uploads have been disabled','wpproads');
					}
				$html.= '</div>';
			$html.= '</div>';
		$html.= '</div>';
        
        return $html;
	}
	
	
	
	
	
	
	// Add Object
	public function bc_add_object_wgt()
	{
		$html = '';
		$html.= '<div>';
			$html.= '<div id="sidebar" >';
				$html.= '<div class="sidebar-name open" style="background:#FFF;">';
					//$html.= '<div class="sidebar-name-arrow"></div>';
					$html.= '<h3><span class="vbc_btn_icon">-</span> '.__('Add Object','wpproads').'</h3>';
				$html.= '</div>';
				$html.= '<div class="sidebar-description" style="padding:10px; background:#EFEFEF;">';
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Object type','wpproads').'</small></div>';
						$html.= '<select id="object_type">';
							$html.= '<option value="circle">'.__('Circle','wpproads').'</option>';
							$html.= '<option value="rectangle">'.__('Rectangle','wpproads').'</option>';
							$html.= '<option value="triangle">'.__('Triangle','wpproads').'</option>';
						$html.= '</select>';
					$html.= '</div>';
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Color','wpproads').'</small></div>';
						$html.= '<input type="text" id="object_bg_color" value="" class="imge-color-field" />';
					$html.= '</div>';
					$html.= '<div style="padding:5px; display:none;" class="border_radius_box">';
						$html.= '<div><small>'.__('Border Radius','wpproads').'</small></div>';
						$html.= '<input type="number" min="0" step="1" id="object_border_radius" value="0" />';
					$html.= '</div>';
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Opacity','wpproads').'</small></div>';
						$html.= '<input type="range" min="0" max="1" step=".1" id="object_opacity" value="1" />';
					$html.= '</div>';
					$html.= '<div style="padding:5px;"><a class="button-primary "id="add_object">'.__('Add Object','wpproads').'</a></div>';
				$html.= '</div>';
			$html.= '</div>';
		$html.= '</div>';
		
		return $html;
	}
	
	
	
	
	
	// Add Text
	public function bc_add_text_wgt()
	{
		global $bc_google_fonts;
		
		$html = '';
		$html.= '<div>';
			$html.= '<div id="sidebar" >';
				$html.= '<div class="sidebar-name open" style="background:#FFF;">';
					//$html.= '<div class="sidebar-name-arrow"></div>';
					$html.= '<h3><span class="vbc_btn_icon">-</span> '.__('Add Text','wpproads').'</h3>';
				$html.= '</div>';
				$html.= '<div class="sidebar-description" style="padding:10px; background:#EFEFEF; position:relative;">';
					$html.= '<div style="padding:5px;"><textarea id="text_ipt" placeholder="'.__('Text','wpproads').'"></textarea></div>';
					
					$html.= '<div style="margin: 4px;">';
						$html.= '<div><small>'.__('Font','wpproads').'</small></div>';
						$html.= '<select id="font_name" style="position:absolute; left:0; top:0; width:100%;" class="chosen-select" data-placeholder="'.__('Choose a Font','wpproads').'">';
							$html.= '<optgroup label="Classic Fonts">';
								$html.= '<option value="Verdana"></option>';
								$html.= '<option value="Verdana">Verdana</option>';
								$html.= '<option value="Arial">'.__('Arial','wpproads').'</option>';
								$html.= '<option value="Impact">'.__('Impact','wpproads').'</option>';
							$html.= '</optgroup>';
							$html.= '<optgroup label="Google Fonts">';
								$html.= $bc_google_fonts->form_select_options();
							$html.= '</optgroup>';
						$html.= '</select>';
						
						$html.= '<script type="text/javascript">';
						$html.= 'jQuery(document).ready(function($){';
							$html.= 'var $input = jQuery(\'#font_name\');';
							$html.= 'var $select = jQuery(\'#font_name_select\');';
							 
							$html.= '$select.change(function(){ modify(); })';
							 
							$html.= 'function modify(){ $input.val($select.val()) /*output(); }';
							 
							$html.= 'function output(){ jQuery(\'p\').text(\'value: \' + $input.val()); }';
							 
							$html.= ' $input.on(\'click\', function(){';
								$html.= 'jQuery(this).select()';
							$html.= '}).on(\'blur\', function(){';
							    $html.= '//output();';
							$html.= '})';
							 
							$html.= 'modify();';
						$html.= '});';
						$html.= '</script>';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Font Size','wpproads').'</small></div>';
						$html.='<input type="number" id="font_size" min="1" placeholder="'.__('Font Size','wpproads').'" />';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Font Weight','wpproads').'</small></div>';
						$html.= '<select id="font_weight">';
							$html.= '<option value="100">100</option>';
							$html.= '<option value="200">200</option>';
							$html.= '<option value="300">300</option>';
							$html.= '<option value="400">400 - normal</option>';
							$html.= '<option value="500">500</option>';
							$html.= '<option value="600">600</option>';
							$html.= '<option value="700">700</option>';
							$html.= '<option value="800">800 - bold</option>';
							$html.= '<option value="900">900</option>';
						$html.= '</select>';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Font Color','wpproads').'</small></div>';
						$html.= '<input type="text" id="text_color" value="" class="imge-color-field" />';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Text Align','wpproads').'</small></div>';
						$html.= '<select id="text_align">';
							$html.= '<option value="left">'.__('Left','wpproads').'</option>';
							$html.= '<option value="center">'.__('Center','wpproads').'</option>';
							$html.= '<option value="right">'.__('Right','wpproads').'</option>';
						$html.= '</select>';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Text Decoration','wpproads').'</small></div>';
						$html.= '<select id="text_decoration">';
							$html.= '<option value="">'.__('None','wpproads').'</option>';
							$html.= '<option value="underline">'.__('Underline','wpproads').'</option>';
							$html.= '<option value="line-through">'.__('Line Through','wpproads').'</option>';
							$html.= '<option value="overline">'.__('Overline','wpproads').'</option>';
						$html.= '</select>';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('LineHeight','wpproads').'</small></div>';
						$html.= '<input type="number" id="line_height" step=".1" placeholder="'.__('Line Height','wpproads').'" />';
					$html.= '</div>';
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Opacity','wpproads').'</small></div>';
						$html.= '<input type="range" min="0" max="1" step=".1" id="font_opacity" value="1" />';
					$html.= '</div>';
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Border Width','wpproads').'</small></div>';
						$html.= '<input type="number" id="font_border_width" placeholder="'.__('Border Width','wpproads').'" />';
					$html.= '</div>';
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Border Color','wpproads').'</small></div>';
						$html.= '<input type="text" id="text_border_color" value="" class="imge-color-field" />';
					$html.= '</div>';
					
					
					$html.= '<div style="padding:5px;"><a class="button-primary "id="add_text">'.__('Add Text','wpproads').'</a></div>';
					
				$html.= '</div>';
			$html.= '</div>';
		$html.= '</div>';
		
		return $html;	
	}
	
	
	
	
	// Load SVG
	public function bc_load_svg_wgt()
	{
		global $vbc_design_items;
		
		$html = '';
		$html.= '<div>';
			$html.= '<div id="sidebar" >';
				
				$html.= '<div class="sidebar-name vbc_desings open" style="background:#FFF;">';
					$html.= '<h3><span class="vbc_btn_icon">-</span> '.__('Designs/Shapes','wpproads').'</h3>';
				$html.= '</div>';
				$html.= '<div class="sidebar-description" style="padding:10px; background:#EFEFEF; position:relative;">';
				
						$html.= $vbc_design_items->design_items();
					
					$html.= '<div class="clearFix"></div>';
					
				$html.= '</div>';
			
			
				$html.= '<div class="sidebar-name open" style="background:#FFF;">';
					$html.= '<h3><span class="vbc_btn_icon">+</span> '.__('Load SVG','wpproads').'</h3>';
				$html.= '</div>';
				$html.= '<div class="sidebar-description" style="padding:10px; background:#EFEFEF; position:relative; display:none;">';
					$html.= '<div style="padding:5px;"><textarea id="load_svg_code" placeholder="'.__('SVG Code','wpproads').'"></textarea></div>';
					$html.= '<div style="padding:5px;">';
						$html.= '<a class="button-primary "id="load_svg">'.__('Load SVG','wpproads').'</a>';
						$html.= '<span style="font-size:11px; margin-left:5px; display:inline-block"><input type="checkbox" class="vbc_keep_layers" /> '.__('Keep Layers','wpproads').'</span>';
						$html.= '<h4>'.__('Example SVG Code:','wpproads').'</h4>';
						$html.= '<textarea style="background:#EFEFEF; border:none; font-size:11px; height: 90px; color: #999; box-shadow:none;"><svg height="100" width="100">
  <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" /></textarea>';
						//$html.= '<a class="button-secondary "id="load_svg_layers" style="margin-left:3px;"><i class="fa fa-upload"></i> '.__('Layers','wpproads').'</a>';
					$html.= '</div>';
				$html.= '</div>';
				
			$html.= '</div>';
		$html.= '</div>';
		
		return $html;
	}
	
	
	
	// Drawing wgt
	public function bc_load_drawing_wgt()
	{
		$html = '';
		$html.= '<div>';
			$html.= '<div id="sidebar" >';
				
				$html.= '<div class="sidebar-name vbc_desings open" style="background:#FFF;">';
					$html.= '<h3><span class="vbc_btn_icon">-</span> '.__('Drawing','wpproads').'</h3>';
				$html.= '</div>';
				$html.= '<div class="sidebar-description" style="padding:10px; background:#EFEFEF; position:relative;">';
					$html.= '<a class="button-primary "id="start_drawing">'.__('Start Drawing','wpproads').'</a>';
					$html.= '<a class="button-secondary "id="stop_drawing" style="display:none;">'.__('Stop Drawing','wpproads').'</a>';
				$html.= '</div>';
				
				$html.= '<div class="drawing_settings_cont" style="display:none;">';
					
					// color
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Color','wpproads').'</small></div>';
						$html.= '<input type="text" id="update_drawing_color" value="#000000" class="imge-color-field" />';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Line Width','wpproads').'</small></div>';
						$html.='<input type="range" value="5" min="1" max="150" id="vbc_drawing_line_width">';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<div><small>'.__('Brushes','wpproads').'</small></div>';
						$html.= '<select class="vbc_drawing_brush_type">';
							$html.= '<option value="Pencil">Pencil</option>';
							$html.= '<option value="Circle">Circle</option>';
							$html.= '<option value="Spray">Spray</option>';
							$html.= '<option value="Pattern">Pattern</option>';
							
							$html.= '<option>hline</option>';
							$html.= '<option>vline</option>';
							$html.= '<option>square</option>';
							$html.= '<option>diamond</option>';
						$html.= '</select>';
					$html.= '</div>';
					
					$html.= '<div style="padding:5px;">';
						$html.= '<a class="button-primary "id="update_drawing_settings">'.__('Update Settings','wpproads').'</a>';
					$html.= '</div>';
				$html.= '</div>';
									
			$html.= '</div>';
		$html.= '</div>';
		
		return $html;
	}
	
}
?>