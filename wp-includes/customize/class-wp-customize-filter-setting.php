<?php
/**
 * 自定义API：WP_自定义_过滤器_设置类
 * Customize API: WP_Customize_Filter_Setting class
 *
 * @package WordPress
 * @subpackage Customize
 * @since 4.4.0
 */

/**
 * 用于筛选值但不保存结果的设置
 * A setting that is used to filter a value, but will not save the results.
 *
 * Results should be properly handled using another setting or callback.
 *
 * @since 3.4.0
 *
 * @see WP_Customize_Setting
 */
class WP_Customize_Filter_Setting extends WP_Customize_Setting {

	/**
     * 使用相关API保存设置的值
	 * Saves the value of the setting, using the related API.
	 *
	 * @since 3.4.0
	 *
	 * @param mixed $value The value to update.
	 */
	public function update( $value ) {}
}
