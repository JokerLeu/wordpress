<?php
/**
 * 处理评论帖子到WordPress并防止重复评论发布。
 * Handles Comment Post to WordPress and prevents duplicate comment posting.
 *
 * @package WordPress
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	$protocol = $_SERVER['SERVER_PROTOCOL'];
	if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ) ) ) {
		$protocol = 'HTTP/1.0';
	}

	header('Allow: POST');
	header("$protocol 405 Method Not Allowed");
	header('Content-Type: text/plain');
	exit;
}

/**
 * 设置WordPress环境。
 * Sets up the WordPress Environment.
 */
require( dirname(__FILE__) . '/wp-load.php' );

// 设置标题以防止不同浏览器的缓存。
nocache_headers();

// 处理评论的提交，通常通过评论表单提交给wp-comments-post.php（从字符串或字符串数组中删除斜杠。）
$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
if ( is_wp_error( $comment ) ) {
	$data = intval( $comment->get_error_data() );
	if ( ! empty( $data ) ) {
		wp_die( '<p>' . $comment->get_error_message() . '</p>', __( 'Comment Submission Failure' ), array( 'response' => $data, 'back_link' => true ) );
	} else {
		exit;
	}
}

// 实例化检索当前用户对象。
$user = wp_get_current_user();
$cookies_consent = ( isset( $_POST['wp-comment-cookies-consent'] ) );

/**
 * 在设置评论Cookie时执行其他操作。
 * Perform other actions when comment cookies are set.
 *
 * @since 3.4.0
 * @since 4.9.6 The `$cookies_consent` parameter was added.
 *
 * @param WP_Comment $comment         Comment object.
 * @param WP_User    $user            Comment author's user object. The user may not exist.
 * @param boolean    $cookies_consent Comment author's consent to store cookies.
 */
do_action( 'set_comment_cookies', $comment, $user, $cookies_consent );

// 检索到给定评论的链接。
$location = empty( $_POST['redirect_to'] ) ? get_comment_link( $comment ) : $_POST['redirect_to'] . '#comment-' . $comment->comment_ID;

/**
 * 过滤位置URI，在发布后发送收件人。
 * Filters the location URI to send the commenter after posting.
 *
 * @since 2.0.5
 *
 * @param string     $location The 'redirect_to' URI sent via $_POST.
 * @param WP_Comment $comment  Comment object.
 */
$location = apply_filters( 'comment_post_redirect', $location, $comment );

// 使用wp_redirect()执行安全的（本地）重定向。
wp_safe_redirect( $location );
exit;
