<?php
/**
 * Widget API: WP_Widget_Calendar class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * 核心类用于实现日历小部件。
 * Core class used to implement the Calendar widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class WP_Widget_Calendar extends WP_Widget {
	/**
     * 确保ID属性只在标记中出现一次
	 * Ensure that the ID attribute only appears in the markup once
	 *
	 * @since 4.4.0
	 *
	 * @static
	 * @var int
	 */
	private static $instance = 0;

	/**
     * 设置一个新的日历控件实例。
	 * Sets up a new Calendar widget instance.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_calendar',
			'description' => __( 'A calendar of your site&#8217;s Posts.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'calendar', __( 'Calendar' ), $widget_ops );
	}

	/**
     * 输出当前日历小部件实例的内容。
	 * Outputs the content for the current Calendar widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( 0 === self::$instance ) {
			echo '<div id="calendar_wrap" class="calendar_wrap">';
		} else {
			echo '<div class="calendar_wrap">';
		}
		get_calendar();
		echo '</div>';
		echo $args['after_widget'];

		self::$instance++;
	}

	/**
     * 处理当前日历控件实例的更新设置。
	 * Handles updating settings for the current Calendar widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	/**
     * 输出日历小部件的设置窗体。
	 * Outputs the settings form for the Calendar widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<?php
	}
}
