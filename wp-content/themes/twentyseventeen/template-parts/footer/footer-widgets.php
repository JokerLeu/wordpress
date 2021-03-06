<?php
/**
 * 如果分配了页脚控件
 * Displays footer widgets if assigned
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>

<?php
if ( is_active_sidebar( 'sidebar-2' ) ||
	is_active_sidebar( 'sidebar-3' ) ) :
?>

<!--页脚控件 代码在主题\template-parts\footer\footer-widgets.php-->
	<aside class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'twentyseventeen' ); ?>">
		<?php
		if ( is_active_sidebar( 'sidebar-2' ) ) {
		?>
			<div class="widget-column footer-widget-1">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div>
		<?php
		}
		if ( is_active_sidebar( 'sidebar-3' ) ) {
		?>
			<div class="widget-column footer-widget-2">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</div>
		<?php } ?>
	</aside><!-- .widget-area -->

<?php endif; ?>
