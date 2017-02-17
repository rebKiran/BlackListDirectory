<?php
class TWBlogcategoryWidget extends WP_Widget {
    function TWBlogcategoryWidget() {
        $widget_ops = array('classname' => 'TWBlogcategoryWidget', 'description' => 'Blogs Categories.');
        parent::__construct(false, 'Blogs Categories ', $widget_ops);
		
    }
    function widget($args, $instance) {
        global $post;
		$title = apply_filters('widget_title', $instance['title']);
		
				?>
				<div class="custom-cat-widget">
				<div class="cat-widget custom-widget">
					<?php if (isset($before_widget))
            echo $before_widget;
        if ($title != '')
            echo $args['before_title']. $title . $args['after_title']; 
			?>
		    		<div class="cat-widget-content">


		    			<ul class="bUL"> 
						<?php
				$categories = get_terms(
				'blog_categories', 
				array('parent' => 0,'order'=> 'DESC','pad_counts'=>true)			
				);
		    		$current = -1;
					//print_r($categories);		      
					foreach ($categories as $category) {
							
							$tag = $category->term_id;
							
											$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
											if (isset($tag_extra_fields[$tag])) {
												$category_icon_code = $tag_extra_fields[$tag]['category_icon_code'];
												$category_icon_color = $tag_extra_fields[$tag]['category_icon_color'];
											}



										?>
							
							<li class="fa fa-chevron-right">
									<?php
									if(!empty($category_icon_code)) {

									?>

						        	<div class="category-icon-box"><?php $category_icon = stripslashes($category_icon_code); echo $category_icon; ?></div>

						        	<?php } ?>
						  			<a href="<?php echo get_category_link( $category->term_id )?>" title="View posts in <?php echo $category->name?>"><?php echo $category->name ?></a>

						  		</li>
								<?php
							}
						?>
						</ul>

		    		</div>

		    	</div>
		    	</div>
				<?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags($new_instance['title']);
       
        return $instance;
    }

    function form($instance) {
	extract($instance);
       ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title:", "classify");?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>"  />
        </p>
        <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("TWBlogcategoryWidget");'));

?>