<?php
/**
 * 显示音频帖子的模板部件
 * Template part for displaying audio posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>

<!--帖子的音频内容 代码在模板的\template-parts\post\content-audio.php-->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if ( is_sticky() && is_home() ) {
		echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
	}
	?>
	<header class="entry-header">
		<?php
		if ( 'post' === get_post_type() ) {
			echo '<div class="entry-meta">';
			if ( is_single() ) {
			    // 为当前的发布日期/时间和作者打印具有元信息的HTML。
				twentyseventeen_posted_on();
			} else {
			    // 为已发布日期获取一个格式良好的字符串。
				echo twentyseventeen_time_link();
				// 返回一个便于访问的链接来编辑帖子或页面。
				twentyseventeen_edit_link();
			};
				echo '</div><!-- .entry-meta -->';
		};

		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} elseif ( is_front_page() && is_home() ) {
			the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->

	<?php
		$content = apply_filters( 'the_content', get_the_content() );
		$audio   = false;

		// 如果播放列表不存在，只能从内容中获取音频。
    // Only get audio from the content if a playlist isn't present.
	if ( false === strpos( $content, 'wp-playlist-script' ) ) {
		$audio = get_media_embedded_in_content( $content, array( 'audio' ) );
	}

	?>

	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">

		<?php
		if ( ! is_single() ) {

			// 如果不是单个帖子，则突出显示音频文件。
            // If not a single post, highlight the audio file.
			if ( ! empty( $audio ) ) {
				foreach ( $audio as $audio_html ) {
					echo '<div class="entry-audio">';
						echo $audio_html;
					echo '</div><!-- .entry-audio -->';
				}
			};

		};

		if ( is_single() || empty( $audio ) ) {

			/* translators: %s: Name of current post */
			the_content(
				sprintf(
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
					get_the_title()
				)
			);

			wp_link_pages(
				array(
					'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
					'after'       => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after'  => '</span>',
				)
			);

		};
		?>

	</div><!-- .entry-content -->

	<?php
	if ( is_single() ) {
	    // 打印HTML与元信息的类别，标签和评论。
		twentyseventeen_entry_footer();
	}
	?>

</article><!-- #post-## -->
