<?php
/**
 * 显示评论的模板
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

/**
 * 如果当前帖子受密码保护，并且访问者尚未输入密码，我们将提前返回而不加载评论。
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
// 是否提供了密码和正确的密码。
if ( post_password_required() ) {
	return;
}
?>
<!--评论 代码在模板的comments.php-->
<div id="comments" class="comments-area">

	<?php
	// 你可以在这里开始编辑--包括这个评论！
    // You can start editing here -- including this comment!
	if ( have_comments() ) :
	?>
		<h2 class="comments-title">
			<?php
            // 检索帖子的评论量
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( _x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'twentyseventeen' ), get_the_title() );
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Reply to &ldquo;%2$s&rdquo;',
						'%1$s Replies to &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'twentyseventeen'
					),
					// 将浮点数转换为基于区域设置的格式
					number_format_i18n( $comments_number ),
					// 检索帖子标题。
					get_the_title()
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
                // 列出注释。
				wp_list_comments(
					array(
						'avatar_size' => 100,
						'style'       => 'ol',
						'short_ping'  => true,
						'reply_text'  => twentyseventeen_get_svg( array( 'icon' => 'mail-reply' ) ) . __( 'Reply', 'twentyseventeen' ),
					)
				);
			?>
		</ol>

		<?php
        // 在适用时，显示下一个/先前评论集的分页导航。
		the_comments_pagination(
			array(
				'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous', 'twentyseventeen' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
			)
		);

	endif; // Check for have_comments().

	// 如果评论被关闭并且有评论，让我们留下一点注释，好吗？
    // If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>

		<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyseventeen' ); ?></p>
	<?php
	endif;

	// 在模板中使用完整的注释表单。
	comment_form();
	?>

</div><!-- #comments -->
