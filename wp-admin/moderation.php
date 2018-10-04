<?php
/**
 * 定义了评论审核函数。
 * Comment Moderation Administration Screen.
 * 评论审核管理屏幕。
 *
 * Redirects to edit-comments.php?comment_status=moderated.
 *
 * @package WordPress
 * @subpackage Administration
 */
require_once( dirname( dirname( __FILE__ ) ) . '/wp-load.php' );
wp_redirect( admin_url('edit-comments.php?comment_status=moderated') );
exit;
