<?php
/**
 * Fabs Player Widget
 *
 */
class Fabs_Player_Widget extends WP_Widget {

	function Fabs_Player_Widget() {
		$widget_ops = array('classname' => 'widget_fabs_player', 'description' => __( "Fabs Player Widget") );
		$this->WP_Widget('fabs_player', __('Fabs Player'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

        fabs_player_instance($instance);
		echo $after_widget;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = @$instance['title'];
        $only_links = @$instance['only_links'];
        $post_id = @$instance['post_id'];
        ?>
        <p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('only_links'); ?>"><?php _e('Public Links Only:'); ?>
			<select class="widefat" id="<?php echo $this->get_field_id('only_links'); ?>" name="<?php echo $this->get_field_name('only_links'); ?>">
                <option value="1" <?php if(@$instance['facebook_id']){ ?>selected<?php } ?>>Yes</option>
                <option value="0" <?php if(!@$instance['facebook_id']){ ?>selected<?php } ?>>No</option>
            </select>
			</label>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('post_id'); ?>"><?php _e('Only Post ID:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('post_id'); ?>" name="<?php echo $this->get_field_name('post_id'); ?>" type="text" value="<?php echo esc_attr($post_id); ?>" />
			</label>
		</p>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '', 'facebook_id' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_id'] = strip_tags($new_instance['post_id']);
        $instance['only_links'] = (bool)strip_tags($new_instance['only_links']);
		return $instance;
	}

}


add_action('widgets_init', 'fabs_player_widgets_init');
function fabs_player_widgets_init(){
    register_widget('Fabs_Player_Widget');
}