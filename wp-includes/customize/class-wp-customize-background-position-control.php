<?php
/**
 * 自定义API：WP_自定义_背景_位置_控制类
 * Customize API: WP_Customize_Background_Position_Control class
 *
 * @package WordPress
 * @subpackage Customize
 * @since 4.7.0
 */

/**
 * 定制背景位置控制类。
 * Customize Background Position Control class.
 *
 * @since 4.7.0
 *
 * @see WP_Customize_Control
 */
class WP_Customize_Background_Position_Control extends WP_Customize_Control {

	/**
	 * Type.
	 *
	 * @since 4.7.0
	 * @var string
	 */
	public $type = 'background_position';

	/**
     * 不要将控制内容从PHP呈现出来，因为它是通过JS加载来呈现的。
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 4.7.0
	 */
	public function render_content() {}

	/**
     * 为位置控件的内容绘制一个JS模板。
	 * Render a JS template for the content of the position control.
	 *
	 * @since 4.7.0
	 */
	public function content_template() {
		$options = array(
			array(
				'left top'   => array( 'label' => __( 'Top Left' ), 'icon' => 'dashicons dashicons-arrow-left-alt' ),
				'center top' => array( 'label' => __( 'Top' ), 'icon' => 'dashicons dashicons-arrow-up-alt' ),
				'right top'  => array( 'label' => __( 'Top Right' ), 'icon' => 'dashicons dashicons-arrow-right-alt' ),
			),
			array(
				'left center'   => array( 'label' => __( 'Left' ), 'icon' => 'dashicons dashicons-arrow-left-alt' ),
				'center center' => array( 'label' => __( 'Center' ), 'icon' => 'background-position-center-icon' ),
				'right center'  => array( 'label' => __( 'Right' ), 'icon' => 'dashicons dashicons-arrow-right-alt' ),
			),
			array(
				'left bottom'   => array( 'label' => __( 'Bottom Left' ), 'icon' => 'dashicons dashicons-arrow-left-alt' ),
				'center bottom' => array( 'label' => __( 'Bottom' ), 'icon' => 'dashicons dashicons-arrow-down-alt' ),
				'right bottom'  => array( 'label' => __( 'Bottom Right' ), 'icon' => 'dashicons dashicons-arrow-right-alt' ),
			),
		);
		?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<div class="customize-control-content">
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e( 'Image Position' ); ?></span></legend>
				<div class="background-position-control">
				<?php foreach ( $options as $group ) : ?>
					<div class="button-group">
					<?php foreach ( $group as $value => $input ) : ?>
						<label>
							<input class="screen-reader-text" name="background-position" type="radio" value="<?php echo esc_attr( $value ); ?>">
							<span class="button display-options position"><span class="<?php echo esc_attr( $input['icon'] ); ?>" aria-hidden="true"></span></span>
							<span class="screen-reader-text"><?php echo $input['label']; ?></span>
						</label>
					<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
				</div>
			</fieldset>
		</div>
		<?php
	}
}
