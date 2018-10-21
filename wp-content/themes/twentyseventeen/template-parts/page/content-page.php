<?php
/**
 * 在page.php中显示页面内容的模板部分
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>

<!--内容页面 代码在模板的\template-parts\page\content-page.php-->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php
        // 返回一个便于访问的链接来编辑帖子或页面。
        twentyseventeen_edit_link( get_the_ID() );
        ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
            // 显示文章内容。
			the_content();

			// 页面列表的格式化输出。
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
					'after'  => '</div>',
				)
			);
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
