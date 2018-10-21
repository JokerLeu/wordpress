<?php
/**
 * 在首页显示页面的模板部分
 * Template part for displaying pages on front page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

global $twentyseventeencounter;

?>

<!--内容首页页面面板 代码在模板的\template-parts\page\content-front-page-panels.php-->
<article id="panel<?php echo $twentyseventeencounter; ?>" <?php post_class( 'twentyseventeen-panel ' ); ?> >

	<?php
	if ( has_post_thumbnail() ) :
        // 检索图像以表示附件。
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'twentyseventeen-featured-image' );

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
                twentyseventeen_edit_link( get_the_ID() );
                ?>

			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
					/* translators: %s: Name of current post */
					the_content(
						sprintf(
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
							get_the_title()
						)
					);
				?>
			</div><!-- .entry-content -->

			<?php
            // 显示最近的博客文章，如果是博客帖子页面（注意get_option返回一个字符串，所以我们将结果作为int）。
			// Show recent blog posts if is blog posts page (Note that get_option returns a string, so we're casting the result as an int).
			if ( get_the_ID() === (int) get_option( 'page_for_posts' ) ) :
			?>

				<?php
                // 显示最近三篇文章。
				// Show three most recent posts.
                    // 实例化WordPress查询类。
				$recent_posts = new WP_Query(
					array(
						'posts_per_page'      => 3,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					)
				);
				?>

				<?php if ( $recent_posts->have_posts() ) : ?>

					<div class="recent-posts">

						<?php
						while ( $recent_posts->have_posts() ) :
							$recent_posts->the_post();
							get_template_part( 'template-parts/post/content', 'excerpt' );
						endwhile;
						// 在循环一个单独的查询之后，这个函数将$POST全局恢复到主查询中的当前POST。
						wp_reset_postdata();
						?>
					</div><!-- .recent-posts -->
				<?php endif; ?>
			<?php endif; ?>

		</div><!-- .wrap -->
	</div><!-- .panel-content -->

</article><!-- #post-## -->
