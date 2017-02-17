<?php

class WPSites_Recent_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'wpsites_recent_posts', 'description' => __( "Show Latest Blog Posts.", 'classify') );
        parent::__construct('wpsites-recent-posts', __('Blog Recent Posts', 'classify'), $widget_ops);
        $this->alt_option_name = 'wpsites_recent_posts';

        add_action( 'save_post', array($this, 'flush_widget_cache') );
        add_action( 'deleted_post', array($this, 'flush_widget_cache') );
        add_action( 'switch_theme', array($this, 'flush_widget_cache') );
    }

    public function widget($args, $instance) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'wpsites_widget_recent_posts', 'widget' );
        }

        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

        ob_start();

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'classify' );

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;


        $r = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'post_type'           => array('blog_posts',
            'ignore_sticky_posts' => true
        ) ) ) );

        if ($r->have_posts()) :
?>
        <?php echo $args['before_widget']; ?>
        <?php if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
			echo '<div class="jw-recent-posts-widget blogPost">';
        } ?>
        <ul>
        <?php while ( $r->have_posts() ) : $r->the_post(); ?>
            <li class="widget-ad-list latestads-widget">
				<?php require_once(TEMPLATEPATH . '/inc/BFI_Thumb.php'); ?>
				<?php 

					$thumb_id = get_post_thumbnail_id();
					$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
					$params = array( 'width' => 80, 'height' => 80, 'crop' => true );
					echo "<img alt='image' class='widget-ad-image bImage' src='" . bfi_thumb( "$thumb_url[0]", $params ) . "'/>";											
				?>
				<div class="widget-ad-list-content">
					<span class="widget-ad-list-content-title bPost"><a href="<?php the_permalink(); ?>"><?php the_titlesmall('', '...', true, '50') ?></a></span>
				<?php if ( $show_date ) : ?>
					<p class="add-price"><?php echo get_the_date(); ?></p>
				<?php endif; ?>
				</div>
            </li>
        <?php endwhile; ?>
        </ul>
		<?php echo '</div>'; ?>
        <?php echo $args['after_widget']; ?>
<?php

        wp_reset_postdata();

        endif;

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'wpsites_widget_recent_posts', $cache, 'widget' );
        } else {
            ob_end_flush();
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['wpsites_recent_posts']) )
            delete_option('wpsites_recent_posts');

        return $instance;
    }

    public function flush_widget_cache() {
        wp_cache_delete('wpsites_widget_recent_posts', 'widget');
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'classify' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'classify' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?', 'classify' ); ?></label></p>
<?php
    }
}
function wpsites_widgets_init() {
    if ( !is_blog_installed() )
        return;

    register_widget('WPSites_Recent_Posts');
    do_action( 'widgets_init' );
}

add_action( 'init', 'wpsites_widgets_init', 2 );