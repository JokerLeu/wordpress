<?php
/**
 * WordPress选项头部。
 * WordPress Options Header.
 *
 * 如果更新的变量是URL查询的一部分，则显示更新的消息。
 * Displays updated message, if updated variable is part of the URL query.
 *
 * @package WordPress
 * @subpackage Administration
 */

// 基于$_GET和$_POST重置全局变量
wp_reset_vars( array( 'action' ) );

if ( isset( $_GET['updated'] ) && isset( $_GET['page'] ) ) {
	// 对于不使用设置API的插件，在重定向时只设置更新＝1。
    // For back-compat with plugins that don't use the Settings API and just set updated=1 in the redirect.
	// 注册要显示给用户的设置错误
    add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
}

// 显示由add_settings_error()注册的设置错误。
settings_errors();
