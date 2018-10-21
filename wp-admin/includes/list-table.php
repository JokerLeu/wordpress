<?php
/**
 * 助手函数，用于显示Ajax化HTML表中的项目列表。
 * Helper functions for displaying a list of items in an ajaxified HTML table.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 */

/**
 * 获取一个WP_List_Table类的实例。
 * Fetch an instance of a WP_List_Table class.
 *
 * @access private
 * @since 3.1.0
 *
 * @global string $hook_suffix
 *
 * @param string $class The type of the list table, which is the class name.
 * @param array $args Optional. Arguments to pass to the class. Accepts 'screen'.
 * @return object|bool Object on success, false if the class does not exist.
 */
function _get_list_table( $class, $args = array() ) {
	$core_classes = array(
		// 站点管理
        // Site Admin
            // 核心类用于实现列表中的显示。
		'WP_Posts_List_Table' => 'posts',
		    // 用于在列表中显示媒体项的核心类。
		'WP_Media_List_Table' => 'media',
		    // 核心类实现一个列表显示术语。
		'WP_Terms_List_Table' => 'terms',
		    // 用于在列表表中显示用户的核心类。
		'WP_Users_List_Table' => 'users',
		    // 用于在列表表中显示注释的核心类。
		'WP_Comments_List_Table' => 'comments',
		    // 用于在列表表中显示注释的核心类。
		'WP_Post_Comments_List_Table' => array( 'comments', 'post-comments' ),
		    // 用于在列表表中显示链接的核心类。
        'WP_Links_List_Table' => 'links',
		    // 核心类用于实现在列表表中安装插件的显示。
		'WP_Plugin_Install_List_Table' => 'plugin-install',
		    // 核心类用于实现在列表表中显示已安装的主题。
		'WP_Themes_List_Table' => 'themes',
		    // 用于实现在列表表中安装显示主题的核心类。
        'WP_Theme_Install_List_Table' => array( 'themes', 'theme-install' ),
		    // 核心类用于实现在列表表中显示已安装的插件。
        'WP_Plugins_List_Table' => 'plugins',
		// 网络管理
        // Network Admin
            // 核心类用于在网络管理的列表表中实现显示站点。
		'WP_MS_Sites_List_Table' => 'ms-sites',
		    // 核心类用于在网络管理表列表中实现显示用户。
		'WP_MS_Users_List_Table' => 'ms-users',
		    // 用于在网络管理表列表中显示主题的核心类。
		'WP_MS_Themes_List_Table' => 'ms-themes',
	);

	if ( isset( $core_classes[ $class ] ) ) {
		foreach ( (array) $core_classes[ $class ] as $required )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-' . $required . '-list-table.php' );

		if ( isset( $args['screen'] ) )
			$args['screen'] = convert_to_screen( $args['screen'] );
		elseif ( isset( $GLOBALS['hook_suffix'] ) )
			$args['screen'] = get_current_screen();
		else
			$args['screen'] = null;

		return new $class( $args );
	}

	return false;
}

/**
 * 为特定屏幕注册列标题。
 * Register column headers for a particular screen.
 *
 * @since 2.7.0
 *
 * @param string $screen The handle for the screen to add help to. This is usually the hook name returned by the add_*_page() functions.
 * @param array $columns An array of columns with column IDs as the keys and translated column names as the values
 * @see get_column_headers(), print_column_headers(), get_hidden_columns()
 */
function register_column_headers($screen, $columns) {
	new _WP_List_Table_Compat( $screen, $columns );
}

/**
 * 打印特定屏幕的列标题。
 * Prints column headers for a particular screen.
 *
 * @since 2.7.0
 *
 * @param string|WP_Screen $screen  The screen hook name or screen object.
 * @param bool             $with_id Whether to set the id attribute or not.
 */
function print_column_headers( $screen, $with_id = true ) {
	$wp_list_table = new _WP_List_Table_Compat($screen);

	$wp_list_table->print_column_headers( $with_id );
}
