<?php
/**
 * 根据博客参数定义博客页面显示内容。
 * Loads the WordPress environment and template.
 * 文件功能：加载wordpress环境 和 模板
 *
 * @package WordPress
 */

if ( !isset($wp_did_header) ) {

	$wp_did_header = true;

	// Load the WordPress library.
	// 加载WordPress库
	require_once( dirname(__FILE__) . '/wp-load.php' );

	// Set up the WordPress query.
	// 设置WordPress查询.
	wp();

	// Load the theme template.
	// 加载主题模板.
	require_once( ABSPATH . WPINC . '/template-loader.php' );

}
