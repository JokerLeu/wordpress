<?php
/**
 * 允许模板样式化的附加特性
 * Additional features to allow styling of the templates
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 */

/**
 * 将自定义类添加到体类数组中。
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function twentyseventeen_body_classes( $classes ) {
	// 将博客添加到超过1名作者的博客中。
    // Add class of group-blog to blogs with more than 1 published author.
	// 这个网站有不止一个作者吗?
    if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// 向非奇异页面添加HFEP类。
    // Add class of hfeed to non-singular pages.
    // 是对任何POST类型（POST、附件、页面、自定义POST类型）的现有单个POST的查询吗？
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// 添加类，如果我们正在查看定制的主题选项更容易造型。
    // Add class if we're viewing the Customizer for easier styling of theme options.
	// 在定制程序中是否正在预览站点。
    if ( is_customize_preview() ) {
		$classes[] = 'twentyseventeen-customizer';
	}

	// 在首页添加类。
    // Add class on front page.
	if ( is_front_page() && 'posts' !== get_option( 'show_on_front' ) ) {
		$classes[] = 'twentyseventeen-front-page';
	}

	// 如果有自定义标题，则添加一个类。
    // Add a class if there is a custom header.
    // 检查是否设置了标题图像。
	if ( has_header_image() ) {
		$classes[] = 'has-header-image';
	}

	// 如果使用边栏，则添加类。
    // Add class if sidebar is used.
    // 边栏是否在使用中
	if ( is_active_sidebar( 'sidebar-1' ) && ! is_page() ) {
		$classes[] = 'has-sidebar';
	}

	// 为一个或两个列页面布局添加类。
    // Add class for one or two column page layouts.
    // 是否对现有的单个页面进行查询？或 是否存在对现有存档页的查询？
	if ( is_page() || is_archive() ) {
		if ( 'one-column' === get_theme_mod( 'page_layout' ) ) {
			$classes[] = 'page-one-column';
		} else {
			$classes[] = 'page-two-column';
		}
	}

	// 如果站点标题和标号被隐藏，则添加类。
    // Add class if the site title and tagline is hidden.
    // 检索3或6位十六进制形式的自定义头文本颜色。
	if ( 'blank' === get_header_textcolor() ) {
		$classes[] = 'title-tagline-hidden';
	}

	// 如果没有颜色方案，则得到颜色方案或默认方案。
    // Get the colorscheme or the default if there isn't one.
    // 对色彩方案进行净化。检索当前主题的主题修改值
	$colors    = twentyseventeen_sanitize_colorscheme( get_theme_mod( 'colorscheme', 'light' ) );
	$classes[] = 'colors-' . $colors;

	return $classes;
}
add_filter( 'body_class', 'twentyseventeen_body_classes' );

/**
 * 计算活动面板的数量。
 * Count our number of active panels.
 *
 * Primarily used to see if we have any panels active, duh.
 */
function twentyseventeen_panel_count() {

	$panel_count = 0;

	/**
     * 2017页首页的过滤数。
	 * Filter number of front page sections in Twenty Seventeen.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param int $num_sections Number of front page sections.
	 */
	$num_sections = apply_filters( 'twentyseventeen_front_page_sections', 4 );

	// 为主题中的每个部分创建一个设置和控件。
    // Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		if ( get_theme_mod( 'panel_' . $i ) ) {
			$panel_count++;
		}
	}

	return $panel_count;
}

/**
 * 检查一下我们是否在首页。
 * Checks to see if we're on the front page or not.
 */
function twentyseventeen_is_frontpage() {
    // 是对站点首页的查询吗？和 确定查询是否适用于博客主页。
	return ( is_front_page() && ! is_home() );
}
