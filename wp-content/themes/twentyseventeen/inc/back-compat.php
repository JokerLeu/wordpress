<?php
/**
 * 2017后台编译功能
 * Twenty Seventeen back compat functionality
 *
 * Prevents Twenty Seventeen from running on WordPress versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 */

/**
 * 防止在旧版本的WordPress上切换到2017。
 * Prevent switching to Twenty Seventeen on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Twenty Seventeen 1.0
 */
function twentyseventeen_switch_theme() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'twentyseventeen_upgrade_notice' );
}
add_action( 'after_switch_theme', 'twentyseventeen_switch_theme' );

/**
 * 为主题切换不成功添加消息。
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Twenty Seventeen on WordPress versions prior to 4.7.
 *
 * @since Twenty Seventeen 1.0
 *
 * @global string $wp_version WordPress version.
 */
function twentyseventeen_upgrade_notice() {
	$message = sprintf( __( 'Twenty Seventeen requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'twentyseventeen' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * 防止定制器在4.7之前加载到WordPress版本上。
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.7.
 *
 * @since Twenty Seventeen 1.0
 *
 * @global string $wp_version WordPress version.
 */
function twentyseventeen_customize() {
	wp_die(
		sprintf( __( 'Twenty Seventeen requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'twentyseventeen' ), $GLOBALS['wp_version'] ), '', array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'twentyseventeen_customize' );

/**
 * 防止主题预览在4.7之前加载到WordPress版本上。
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.7.
 *
 * @since Twenty Seventeen 1.0
 *
 * @global string $wp_version WordPress version.
 */
function twentyseventeen_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'Twenty Seventeen requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'twentyseventeen' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'twentyseventeen_preview' );
