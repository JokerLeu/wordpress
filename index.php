<?php
/**
 * wordpress核心索引文件，即博客输出文件。
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 * 文件功能：wprdpress 应用程序的前面。本文件除了加载wp-blog-header外不做任何事情。
 * 告诉wordpress加载主题。
 *
 * @package WordPress
 */

/**
 * 告诉wordpress加载主题然后输出
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/**
 * 加载wordpress环境 和 模板
 * Loads the WordPress Environment and Template
 */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
