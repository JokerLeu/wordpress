<?php
/**
 * 核心管理API
 * Core Administration API
 *
 * @package WordPress
 * @subpackage Administration
 * @since 2.3.0
 */

if ( ! defined('WP_ADMIN') ) {
	/*
	 * This file is being included from a file other than wp-admin/admin.php, so
	 * some setup was skipped. Make sure the admin message catalog is loaded since
	 * load_default_textdomain() will not have done so in this context.
	 */
	load_textdomain( 'default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo' );
}

/**
 * 管理钩子
 * WordPress Administration Hooks
 */
require_once(ABSPATH . 'wp-admin/includes/admin-filters.php');

/**
 * 书签管理API
 * WordPress Bookmark Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/bookmark.php');

/**
 * 评论管理API
 * WordPress Comment Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/comment.php');

/**
 * 管理文件API
 * WordPress Administration File API
 */
require_once(ABSPATH . 'wp-admin/includes/file.php');

/**
 * 图像管理API
 * WordPress Image Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/image.php');

/**
 * 媒体管理API
 * WordPress Media Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/media.php');

/**
 * 导入管理API
 * WordPress Import Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/import.php');

/**
 *  混合管理API
 * WordPress Misc Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/misc.php');

/**
 * 选项管理API
 * WordPress Options Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/options.php');

/**
 * 插件管理API
 * WordPress Plugin Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * 文章管理API
 * WordPress Post Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/post.php');

/**
 * 管理屏幕API
 * WordPress Administration Screen API
 */
require_once(ABSPATH . 'wp-admin/includes/class-wp-screen.php');
require_once(ABSPATH . 'wp-admin/includes/screen.php');

/**
 * 分类管理API
 * WordPress Taxonomy Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

/**
 * 模板管理API
 * WordPress Template Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/template.php');

/**
 * 列表管理API和基类
 * WordPress List Table Administration API and base class
 */
require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table-compat.php');
require_once(ABSPATH . 'wp-admin/includes/list-table.php');

/**
 * 主题管理API
 * WordPress Theme Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/theme.php');

/**
 * 用户管理API
 * WordPress User Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/user.php');

/**
 * 站点图标API
 * WordPress Site Icon API
 */
require_once(ABSPATH . 'wp-admin/includes/class-wp-site-icon.php');

/**
 * 更新管理API
 * WordPress Update Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/update.php');

/**
 * 弃置管理API
 * WordPress Deprecated Administration API
 */
require_once(ABSPATH . 'wp-admin/includes/deprecated.php');

/**
 * 多站点支持API
 * WordPress Multisite support API
 */
if ( is_multisite() ) {
	require_once(ABSPATH . 'wp-admin/includes/ms-admin-filters.php');
	require_once(ABSPATH . 'wp-admin/includes/ms.php');
	require_once(ABSPATH . 'wp-admin/includes/ms-deprecated.php');
}
