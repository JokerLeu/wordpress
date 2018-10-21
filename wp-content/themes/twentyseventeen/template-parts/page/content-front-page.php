<?php
/**
 * 显示首页内容
 * Displays content for front page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<!--首页内容 代码在模板\template-parts\page\content-front-page.php-->
<article id="post-<?php
// 在WordPress循环中显示当前项目的ID。
the_ID();
?>" <?php
// 显示帖子div的类。
post_class( 'twentyseventeen-panel ' );
?> >

	<?php
    // 检查POST是否附有图像。
	if ( has_post_thumbnail() ) :
        // 检索图像以表示附件。
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'twentyseventeen-featured-image' );

		// 计算纵横比：H/W * 100%。
        // Calculate aspect ratio: h / w * 100%.
		$ratio = $thumbnail[2] / $thumbnail[1] * 100;
		?>

		<div class="panel-image" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>);">
			<div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%"></div>
		</div><!-- .panel-image -->

	<?php endif; ?>

	<div class="panel-content">
		<div class="wrap">
			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>

				<?php
                // 返回一个便于访问的链接来编辑帖子或页面。
                twentyseventeen_edit_link( get_the_ID() ); ?>

			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
					/* translators: %s: Name of current post */
                    // 显示文章内容。
					the_content(
						sprintf(
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
							// 检索帖子标题。
                            get_the_title()
						)
					);
				?>
			</div><!-- .entry-content -->

		</div><!-- .wrap -->
	</div><!-- .panel-content -->

</article><!-- #post-## -->
