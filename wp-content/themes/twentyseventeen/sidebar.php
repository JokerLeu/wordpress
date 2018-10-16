<?php
/**
 * 包含主控件区域的侧边栏
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

// 检测栏是否在使用中
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<!--代码在模板的sidebar.php文件-->
<aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Blog Sidebar', 'twentyseventeen' ); ?>">
	<?php
    // 调用内核API-显示动态侧栏。
    dynamic_sidebar( 'sidebar-1' );
    ?>
</aside><!-- #secondary -->
