<?php
/**
 * 显示页脚站点信息
 * Displays footer site info
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<div class="site-info">
	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
	    // 在适用时显示带有格式设置的隐私策略链接。
		the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
	}
	?>
    <!--站点信息 代码在主题的\template-parts\footer\site-info.php-->
	<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'twentyseventeen' ) ); ?>" class="imprint">
		<?php printf( __( 'Proudly powered by %s', 'twentyseventeen' ), 'WordPress' ); ?>
	</a>
</div><!-- .site-info -->
