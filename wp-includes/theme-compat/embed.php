<?php
/**
 * 包含post嵌入基模板。
 * Contains the post embed base template
 *
 * 当一个帖子嵌入到一个iframe中，如果活动主题不包含embed.php模板，这个文件将被用来创建输出。
 * When a post is embedded in an iframe, this file is used to create the output
 * if the active theme does not include an embed.php template.
 *
 * @package WordPress
 * @subpackage oEmbed
 * @since 4.4.0
 */

get_header( 'embed' );

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		get_template_part( 'embed', 'content' );
	endwhile;
else :
	get_template_part( 'embed', '404' );
endif;

get_footer( 'embed' );
