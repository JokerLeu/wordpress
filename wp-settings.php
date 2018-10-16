<?php
/**
 * 运行执行前的例行程序，包括检查安装是否正确，使用辅助函数，应用用户插件，初始化执行计时器等等。
 * Used to set up and fix common variables and include
 * the WordPress procedural and class library.
 * 文件用途：用于设置和修复 通用变量 和 include wordpress 程序和类库
 *
 * Allows for some configuration in wp-config.php (see default-constants.php)
 * 允许在wp-config.php中配置一些配置项（查看default-constants.php）
 *
 * @package WordPress
 */

/**
 * 常量定义：存储函数、类和核心内容的WordPress目录的位置。
 * Stores the location of the WordPress directory of functions, classes, and core content.
 *
 *
 * @since 1.0.0
 */
define( 'WPINC', 'wp-includes' );

// 引入初始化所需的文件。
// Include files required for initialization.
require( ABSPATH . WPINC . '/load.php' );
require( ABSPATH . WPINC . '/default-constants.php' );
require_once( ABSPATH . WPINC . '/plugin.php' );

/*
 * These can't be directly globalized in version.php. When updating,
 * we're including version.php from another installation and don't want
 * these values to be overridden if already set.
 */
global $wp_version, $wp_db_version, $tinymce_version, $required_php_version, $required_mysql_version, $wp_local_package;
require( ABSPATH . WPINC . '/version.php' );

/**
 * If not already configured, `$blog_id` will default to 1 in a single site
 * configuration. In multisite, it will be overridden by default in ms-settings.php.
 *
 * @global int $blog_id
 * @since 2.0.0
 */
global $blog_id;

// 设置初始默认常数
// Set initial default constants including WP_MEMORY_LIMIT, WP_MAX_MEMORY_LIMIT, WP_DEBUG, SCRIPT_DEBUG, WP_CONTENT_DIR and WP_CACHE.
wp_initial_constants();

// 检查所需的PHP版本和MySQL扩展或数据库插入
// Check for the required PHP version and for the MySQL extension or a database drop-in.
wp_check_php_mysql_versions();

// 运行时禁用魔术引导
// Disable magic quotes at runtime. Magic quotes are added using wpdb later in wp-settings.php.
@ini_set( 'magic_quotes_runtime', 0 );
@ini_set( 'magic_quotes_sybase',  0 );

// WordPress计算UTC的偏移量
// WordPress calculates offsets from UTC.
date_default_timezone_set( 'UTC' );

// 关闭寄存器全局变量
// Turn register_globals off.
wp_unregister_GLOBALS();

// 通过设置标准化$_SERVER变量
// Standardize $_SERVER variables across setups.
wp_fix_server_vars();

// 检查我们是否收到了由于缺少favicon.ico的请求
// Check if we have received a request due to missing favicon.ico
wp_favicon_request();

// 检查我们是否处于维修模式
// Check if we're in maintenance mode.
wp_maintenance();

// 启动加载计时器
// Start loading timer.
timer_start();

// 检查我们是否处于WP_DEBUG调试模式。
// Check if we're in WP_DEBUG mode.
wp_debug_mode();

/**
 * 筛选是否启用加载的advanced-cache.php插入。
 * Filters whether to enable loading of the advanced-cache.php drop-in.
 *
 * This filter runs before it can be used by plugins. It is designed for non-web
 * run-times. If false is returned, advanced-cache.php will never be loaded.
 *
 * @since 4.6.0
 *
 * @param bool $enable_advanced_cache Whether to enable loading advanced-cache.php (if present).
 *                                    Default true.
 */
if ( WP_CACHE && apply_filters( 'enable_loading_advanced_cache_dropin', true ) ) {
	// 一个先进的缓存插件使用。使用静态插入，因为您只需要一个。
    // For an advanced caching plugin to use. Uses a static drop-in because you would only want one.
	WP_DEBUG ? include( WP_CONTENT_DIR . '/advanced-cache.php' ) : @include( WP_CONTENT_DIR . '/advanced-cache.php' );

	// 重新初始化由advanced-cache.php手工添加的任何钩子
    // Re-initialize any hooks added manually by advanced-cache.php
	if ( $wp_filter ) {
		$wp_filter = WP_Hook::build_preinitialized_hooks( $wp_filter );
	}
}

// 设置语言目录的位置。
// Define WP_LANG_DIR if not set.
wp_set_lang_dir();

// 加载早期WordPress文件。
// Load early WordPress files.
require( ABSPATH . WPINC . '/compat.php' );
require( ABSPATH . WPINC . '/class-wp-list-util.php' );
require( ABSPATH . WPINC . '/functions.php' );
require( ABSPATH . WPINC . '/class-wp-matchesmapregex.php' );
require( ABSPATH . WPINC . '/class-wp.php' );
require( ABSPATH . WPINC . '/class-wp-error.php' );
require( ABSPATH . WPINC . '/pomo/mo.php' );

// 包括WPDB类，如果存在，则将删除一个db.php数据库。
// Include the wpdb class and, if present, a db.php database drop-in.
global $wpdb;
// 加载数据库类文件并实例化“$wpdb”全局。
require_wp_db();

// 设置数据库表前缀和数据库表列的格式说明符。
// Set the database table prefix and the format specifiers for database table columns.
$GLOBALS['table_prefix'] = $table_prefix;
wp_set_wpdb_vars();

// 启动WordPress对象缓存
// Start the WordPress object cache, or an external object cache if the drop-in is present.
wp_start_object_cache();

// 附上默认的过滤器。
// Attach the default filters.
require( ABSPATH . WPINC . '/default-filters.php' );

// 初始化多站点如果启用了。
// Initialize multisite if enabled.
if ( is_multisite() ) {
	require( ABSPATH . WPINC . '/class-wp-site-query.php' );
	require( ABSPATH . WPINC . '/class-wp-network-query.php' );
	require( ABSPATH . WPINC . '/ms-blogs.php' );
	require( ABSPATH . WPINC . '/ms-settings.php' );
} elseif ( ! defined( 'MULTISITE' ) ) {
	define( 'MULTISITE', false );
}

// 在停机时注册一个执行函数
register_shutdown_function( 'shutdown_action_hook' );

// 如果我们只需要基本的东西，停止WordPress的大部分加载。
// Stop most of WordPress from being loaded if we just want the basics.
if ( SHORTINIT )
	return false;

// 加载本地化相关库
// Load the L10n library.
require_once( ABSPATH . WPINC . '/l10n.php' );
require_once( ABSPATH . WPINC . '/class-wp-locale.php' );
require_once( ABSPATH . WPINC . '/class-wp-locale-switcher.php' );

// 如果没有安装WordPress，运行安装程序。
// Run the installer if WordPress is not installed.
wp_not_installed();

// 加载最多的WordPress函数
// Load most of WordPress.
require( ABSPATH . WPINC . '/class-wp-walker.php' );
require( ABSPATH . WPINC . '/class-wp-ajax-response.php' );
require( ABSPATH . WPINC . '/formatting.php' );
require( ABSPATH . WPINC . '/capabilities.php' );
require( ABSPATH . WPINC . '/class-wp-roles.php' );
require( ABSPATH . WPINC . '/class-wp-role.php' );
require( ABSPATH . WPINC . '/class-wp-user.php' );
require( ABSPATH . WPINC . '/class-wp-query.php' );
require( ABSPATH . WPINC . '/query.php' );
require( ABSPATH . WPINC . '/date.php' );
require( ABSPATH . WPINC . '/theme.php' );
require( ABSPATH . WPINC . '/class-wp-theme.php' );
require( ABSPATH . WPINC . '/template.php' );
require( ABSPATH . WPINC . '/user.php' );
require( ABSPATH . WPINC . '/class-wp-user-query.php' );
require( ABSPATH . WPINC . '/class-wp-session-tokens.php' );
require( ABSPATH . WPINC . '/class-wp-user-meta-session-tokens.php' );
require( ABSPATH . WPINC . '/meta.php' );
require( ABSPATH . WPINC . '/class-wp-meta-query.php' );
require( ABSPATH . WPINC . '/class-wp-metadata-lazyloader.php' );
require( ABSPATH . WPINC . '/general-template.php' );
require( ABSPATH . WPINC . '/link-template.php' );
require( ABSPATH . WPINC . '/author-template.php' );
require( ABSPATH . WPINC . '/post.php' );
require( ABSPATH . WPINC . '/class-walker-page.php' );
require( ABSPATH . WPINC . '/class-walker-page-dropdown.php' );
require( ABSPATH . WPINC . '/class-wp-post-type.php' );
require( ABSPATH . WPINC . '/class-wp-post.php' );
require( ABSPATH . WPINC . '/post-template.php' );
require( ABSPATH . WPINC . '/revision.php' );
require( ABSPATH . WPINC . '/post-formats.php' );
require( ABSPATH . WPINC . '/post-thumbnail-template.php' );
require( ABSPATH . WPINC . '/category.php' );
require( ABSPATH . WPINC . '/class-walker-category.php' );
require( ABSPATH . WPINC . '/class-walker-category-dropdown.php' );
require( ABSPATH . WPINC . '/category-template.php' );
require( ABSPATH . WPINC . '/comment.php' );
require( ABSPATH . WPINC . '/class-wp-comment.php' );
require( ABSPATH . WPINC . '/class-wp-comment-query.php' );
require( ABSPATH . WPINC . '/class-walker-comment.php' );
require( ABSPATH . WPINC . '/comment-template.php' );
require( ABSPATH . WPINC . '/rewrite.php' );
require( ABSPATH . WPINC . '/class-wp-rewrite.php' );
require( ABSPATH . WPINC . '/feed.php' );
require( ABSPATH . WPINC . '/bookmark.php' );
require( ABSPATH . WPINC . '/bookmark-template.php' );
require( ABSPATH . WPINC . '/kses.php' );
require( ABSPATH . WPINC . '/cron.php' );
require( ABSPATH . WPINC . '/deprecated.php' );
require( ABSPATH . WPINC . '/script-loader.php' );
require( ABSPATH . WPINC . '/taxonomy.php' );
require( ABSPATH . WPINC . '/class-wp-taxonomy.php' );
require( ABSPATH . WPINC . '/class-wp-term.php' );
require( ABSPATH . WPINC . '/class-wp-term-query.php' );
require( ABSPATH . WPINC . '/class-wp-tax-query.php' );
require( ABSPATH . WPINC . '/update.php' );
require( ABSPATH . WPINC . '/canonical.php' );
require( ABSPATH . WPINC . '/shortcodes.php' );
require( ABSPATH . WPINC . '/embed.php' );
require( ABSPATH . WPINC . '/class-wp-embed.php' );
require( ABSPATH . WPINC . '/class-oembed.php' );
require( ABSPATH . WPINC . '/class-wp-oembed-controller.php' );
require( ABSPATH . WPINC . '/media.php' );
require( ABSPATH . WPINC . '/http.php' );
require( ABSPATH . WPINC . '/class-http.php' );
require( ABSPATH . WPINC . '/class-wp-http-streams.php' );
require( ABSPATH . WPINC . '/class-wp-http-curl.php' );
require( ABSPATH . WPINC . '/class-wp-http-proxy.php' );
require( ABSPATH . WPINC . '/class-wp-http-cookie.php' );
require( ABSPATH . WPINC . '/class-wp-http-encoding.php' );
require( ABSPATH . WPINC . '/class-wp-http-response.php' );
require( ABSPATH . WPINC . '/class-wp-http-requests-response.php' );
require( ABSPATH . WPINC . '/class-wp-http-requests-hooks.php' );
require( ABSPATH . WPINC . '/widgets.php' );
require( ABSPATH . WPINC . '/class-wp-widget.php' );
require( ABSPATH . WPINC . '/class-wp-widget-factory.php' );
require( ABSPATH . WPINC . '/nav-menu.php' );
require( ABSPATH . WPINC . '/nav-menu-template.php' );
require( ABSPATH . WPINC . '/admin-bar.php' );
require( ABSPATH . WPINC . '/rest-api.php' );
require( ABSPATH . WPINC . '/rest-api/class-wp-rest-server.php' );
require( ABSPATH . WPINC . '/rest-api/class-wp-rest-response.php' );
require( ABSPATH . WPINC . '/rest-api/class-wp-rest-request.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-posts-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-attachments-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-post-types-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-post-statuses-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-revisions-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-taxonomies-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-terms-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-users-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-comments-controller.php' );
require( ABSPATH . WPINC . '/rest-api/endpoints/class-wp-rest-settings-controller.php' );
require( ABSPATH . WPINC . '/rest-api/fields/class-wp-rest-meta-fields.php' );
require( ABSPATH . WPINC . '/rest-api/fields/class-wp-rest-comment-meta-fields.php' );
require( ABSPATH . WPINC . '/rest-api/fields/class-wp-rest-post-meta-fields.php' );
require( ABSPATH . WPINC . '/rest-api/fields/class-wp-rest-term-meta-fields.php' );
require( ABSPATH . WPINC . '/rest-api/fields/class-wp-rest-user-meta-fields.php' );

// 实例化富媒体类，如视频和图片到内容。
$GLOBALS['wp_embed'] = new WP_Embed();

// 加载多站点特定文件。
// Load multisite-specific files.
if ( is_multisite() ) {
	require( ABSPATH . WPINC . '/ms-functions.php' );
	require( ABSPATH . WPINC . '/ms-default-filters.php' );
	require( ABSPATH . WPINC . '/ms-deprecated.php' );
}

// 定义插件目录的WordPress常量。
// Define constants that rely on the API to obtain the default value.
// Define must-use plugin directory constants, which may be overridden in the sunrise.php drop-in.
wp_plugin_directory_constants();

$GLOBALS['wp_plugin_paths'] = array();

// 加载必须使用的插件
// Load must-use plugins.
foreach ( wp_get_mu_plugins() as $mu_plugin ) {
	include_once( $mu_plugin );
}
unset( $mu_plugin );

// 加载网络激活的插件
// Load network activated plugins.
if ( is_multisite() ) {
	foreach ( wp_get_active_network_plugins() as $network_plugin ) {
		wp_register_plugin_realpath( $network_plugin );
		include_once( $network_plugin );
	}
	unset( $network_plugin );
}

/**
 * 执行所有的必须使用和已经加载的网络激活的插件。
 * Fires once all must-use and network-activated plugins have loaded.
 *
 * @since 2.8.0
 */
do_action( 'muplugins_loaded' );

if ( is_multisite() )
    // 定义多站点Cookie常数。
	ms_cookie_constants(  );

// 在加载多站点后定义常量
// Define constants after multisite is loaded.
wp_cookie_constants();


// 定义和执行我们的SSL常量
// Define and enforce our SSL constants
wp_ssl_constants();

// 创建通用全局
// Create common globals.
require( ABSPATH . WPINC . '/vars.php' );

// 使插件和主题可用的分类和帖子
// Make taxonomies and posts available to plugins and themes.
// @plugin authors: warning: these get registered again on the init hook.
create_initial_taxonomies();
create_initial_post_types();

wp_start_scraping_edited_file_errors();

// 注册默认主题目录根目录
// Register the default theme directory root
register_theme_directory( get_theme_root() );

// 加载活动插件
// Load active plugins.
foreach ( wp_get_active_and_valid_plugins() as $plugin ) {
	wp_register_plugin_realpath( $plugin );
	include_once( $plugin );
}
unset( $plugin );

// 负载可插拔功能
// Load pluggable functions.
require( ABSPATH . WPINC . '/pluggable.php' );
require( ABSPATH . WPINC . '/pluggable-deprecated.php' );

// 设置内部编码
// Set internal encoding.
wp_set_internal_encoding();

// 如果启用对象缓存并运行该函数，则运行wp_cache_postload()
// Run wp_cache_postload() if object cache is enabled and the function exists.
if ( WP_CACHE && function_exists( 'wp_cache_postload' ) )
	wp_cache_postload();

/**
 * 激活插件被加载时触发
 * Fires once activated plugins have loaded.
 *
 * Pluggable functions are also available at this point in the loading order.
 *
 * @since 1.5.0
 */
do_action( 'plugins_loaded' );

// 定义与功能相关的WordPress常量。
// Define constants which affect functionality if not already defined.
wp_functionality_constants();

// 添加魔术引文并设置$_REQUEST ( $_GET + $_POST )
// Add magic quotes and set up $_REQUEST ( $_GET + $_POST )
wp_magic_quotes();

/**
 * 当评论cookie被清除时触发
 * Fires when comment cookies are sanitized.
 *
 * @since 2.0.11
 */
do_action( 'sanitize_comment_cookies' );

/**
 * WordPress Query object
 * @global WP_Query $wp_the_query
 * @since 2.0.0
 */
$GLOBALS['wp_the_query'] = new WP_Query();

/**
 * Holds the reference to @see $wp_the_query
 * Use this global for WordPress queries
 * @global WP_Query $wp_query
 * @since 1.5.0
 */
$GLOBALS['wp_query'] = $GLOBALS['wp_the_query'];

/**
 * Holds the WordPress Rewrite object for creating pretty URLs
 * @global WP_Rewrite $wp_rewrite
 * @since 1.5.0
 */
$GLOBALS['wp_rewrite'] = new WP_Rewrite();

/**
 * WordPress Object
 * @global WP $wp
 * @since 2.0.0
 */
$GLOBALS['wp'] = new WP();

/**
 * WordPress Widget Factory Object
 * @global WP_Widget_Factory $wp_widget_factory
 * @since 2.8.0
 */
$GLOBALS['wp_widget_factory'] = new WP_Widget_Factory();

/**
 * WordPress User Roles
 * @global WP_Roles $wp_roles
 * @since 2.0.0
 */
$GLOBALS['wp_roles'] = new WP_Roles();

/**
 * Fires before the theme is loaded.
 *
 * @since 2.6.0
 */
do_action( 'setup_theme' );

// 定义模板相关的WordPress常量。
// Define the template related constants.
wp_templating_constants(  );

// 加载默认文本本地化域。
// Load the default text localization domain.
load_default_textdomain();

// 检索当前语言环境。
$locale = get_locale();
$locale_file = WP_LANG_DIR . "/$locale.php";
if ( ( 0 === validate_file( $locale ) ) && is_readable( $locale_file ) )
	require( $locale_file );
unset( $locale_file );

/**
 * 用于加载区域设置域日期和各种字符串的WordPress区域设置对象。
 * WordPress Locale object for loading locale domain date and various strings.
 * @global WP_Locale $wp_locale
 * @since 2.1.0
 */
// 实例化存储地区的翻译数据类。
$GLOBALS['wp_locale'] = new WP_Locale();

/**
 * 用于切换区域的WordPress本地切换对象。
 * WordPress Locale Switcher object for switching locales.
 *
 * @since 4.7.0
 *
 * @global WP_Locale_Switcher $wp_locale_switcher WordPress locale switcher object.
 */
// 实例化切换地区类
$GLOBALS['wp_locale_switcher'] = new WP_Locale_Switcher();
$GLOBALS['wp_locale_switcher']->init();

// 加载活动主题的函数，适用于父主题和子主题。
// Load the functions for the active theme, for both parent and child theme if applicable.
if ( ! wp_installing() || 'wp-activate.php' === $pagenow ) {
	if ( TEMPLATEPATH !== STYLESHEETPATH && file_exists( STYLESHEETPATH . '/functions.php' ) )
		include( STYLESHEETPATH . '/functions.php' );
	if ( file_exists( TEMPLATEPATH . '/functions.php' ) )
		include( TEMPLATEPATH . '/functions.php' );
}

/**
 * 主题加载后触发
 * Fires after the theme is loaded.
 *
 * @since 3.0.0
 */
do_action( 'after_setup_theme' );

// 设置当前用户。
// Set up current user.
$GLOBALS['wp']->init();

/**
 * 在WordPress完成加载之后，但在发送任何报头之前触发
 * Fires after WordPress has finished loading but before any headers are sent.
 *
 * Most of WP is loaded at this stage, and the user is authenticated. WP continues
 * to load on the {@see 'init'} hook that follows (e.g. widgets), and many plugins instantiate
 * themselves on it for all sorts of reasons (e.g. they need a user, a taxonomy, etc.).
 *
 * If you wish to plug an action once WP is loaded, use the {@see 'wp_loaded'} hook below.
 *
 * @since 1.5.0
 */
do_action( 'init' );

// 检查站点状态，多站点？
// Check site status
if ( is_multisite() ) {
	if ( true !== ( $file = ms_site_check() ) ) {
		require( $file );
		die();
	}
	unset($file);
}

/**
 * 这个钩子被激发WP，所有插件，并且主题被完全加载和实例化。
 * This hook is fired once WP, all plugins, and the theme are fully loaded and instantiated.
 *
 * Ajax requests should use wp-admin/admin-ajax.php. admin-ajax.php can handle requests for
 * users not logged in.
 *
 * @link https://codex.wordpress.org/AJAX_in_Plugins
 *
 * @since 3.0.0
 */
do_action( 'wp_loaded' );
