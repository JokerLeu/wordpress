<?php
/**
 * 首页模板文件
 * The front page template file
 *
 * 如果用户为他们的主页选择了一个静态页面，这就是将会出现的。
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		// 显示选定的首页内容。
        // Show the selected front page content.
		if ( have_posts() ) :
			while ( have_posts() ) :
                // 在循环中迭代POST索引
				the_post();
		        // 将模板部件加载到模板中
				get_template_part( 'template-parts/page/content', 'front-page' );
			endwhile;
		else :
			get_template_part( 'template-parts/post/content', 'none' );
		endif;
		?>

		<?php
		// 获取我们的每一个面板，并显示帖子数据。
        // Get each of our panels and show the post data.
		if ( 0 !== twentyseventeen_panel_count() || is_customize_preview() ) : // If we have pages to show.

			/**
             * 2017首页的过滤数。
			 * Filter number of front page sections in Twenty Seventeen.
			 *
			 * @since Twenty Seventeen 1.0
			 *
			 * @param int $num_sections Number of front page sections.
			 */
			$num_sections = apply_filters( 'twentyseventeen_front_page_sections', 4 );
			global $twentyseventeencounter;

			// 为主题中的每个部分创建一个设置和控件。
            // Create a setting and control for each of the sections available in the theme.
			for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
				$twentyseventeencounter = $i;
				// 显示首页部分。
				twentyseventeen_front_page_section( null, $i );
			}

	endif; // The if ( 0 !== twentyseventeen_panel_count() ) ends here.
	?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
