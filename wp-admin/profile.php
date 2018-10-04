<?php
/**
 * 用户配置文件管理屏幕。用户-我的个人资料
 * User Profile Administration Screen.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * This is a profile page.
 *
 * @since 2.5.0
 * @var bool
 */
define('IS_PROFILE_PAGE', true);

/** Load User Editing Page */
require_once( dirname( __FILE__ ) . '/user-edit.php' );
