<?php
/**
 * 页面 仪表板管理屏幕 仪表盘-首页
 * Dashboard Administration Screen
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * 加载WordPress引导
 * Load WordPress Bootstrap
 */
require_once( dirname( __FILE__ ) . '/admin.php' );

/**
 * 加载WordPress仪表板API
 * Load WordPress dashboard API
 */
require_once(ABSPATH . 'wp-admin/includes/dashboard.php');

// 注册仪表盘小部件
wp_dashboard_setup();

// 编排脚本（仪表盘）。
wp_enqueue_script( 'dashboard' );

if ( current_user_can( 'install_plugins' ) ) {
	wp_enqueue_script( 'plugin-install' );
	wp_enqueue_script( 'updates' );
}
if ( current_user_can( 'upload_files' ) )
	wp_enqueue_script( 'media-upload' );
// 输入默认的ThickBox js和CSS。
add_thickbox();

if ( wp_is_mobile() )
	wp_enqueue_script( 'jquery-touch-punch' );

$title = __('Dashboard');
$parent_file = 'index.php';

// 帮助-概述
$help = '<p>' . __( 'Welcome to your WordPress Dashboard! This is the screen you will see when you log in to your site, and gives you access to all the site management features of WordPress. You can get help for any screen by clicking the Help tab above the screen title.' ) . '</p>';

$screen = get_current_screen();

$screen->add_help_tab( array(
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' => $help,
) );

// 帮助标签
// Help tabs

// 帮助-导航
$help  = '<p>' . __( 'The left-hand navigation menu provides links to all of the WordPress administration screens, with submenu items displayed on hover. You can minimize this menu to a narrow icon strip by clicking on the Collapse Menu arrow at the bottom.' ) . '</p>';
$help .= '<p>' . __( 'Links in the Toolbar at the top of the screen connect your dashboard and the front end of your site, and provide access to your profile and helpful WordPress information.' ) . '</p>';

$screen->add_help_tab( array(
	'id'      => 'help-navigation',
	'title'   => __( 'Navigation' ),
	'content' => $help,
) );

// 帮助-布局
$help  = '<p>' . __( 'You can use the following controls to arrange your Dashboard screen to suit your workflow. This is true on most other administration screens as well.' ) . '</p>';
$help .= '<p>' . __( '<strong>Screen Options</strong> &mdash; Use the Screen Options tab to choose which Dashboard boxes to show.' ) . '</p>';
$help .= '<p>' . __( '<strong>Drag and Drop</strong> &mdash; To rearrange the boxes, drag and drop by clicking on the title bar of the selected box and releasing when you see a gray dotted-line rectangle appear in the location you want to place the box.' ) . '</p>';
$help .= '<p>' . __( '<strong>Box Controls</strong> &mdash; Click the title bar of the box to expand or collapse it. Some boxes added by plugins may have configurable content, and will show a &#8220;Configure&#8221; link in the title bar if you hover over it.' ) . '</p>';

$screen->add_help_tab( array(
	'id'      => 'help-layout',
	'title'   => __( 'Layout' ),
	'content' => $help,
) );

// 帮助-内容
$help  = '<p>' . __( 'The boxes on your Dashboard screen are:' ) . '</p>';
if ( current_user_can( 'edit_posts' ) )
	$help .= '<p>' . __( '<strong>At A Glance</strong> &mdash; Displays a summary of the content on your site and identifies which theme and version of WordPress you are using.' ) . '</p>';
	$help .= '<p>' . __( '<strong>Activity</strong> &mdash; Shows the upcoming scheduled posts, recently published posts, and the most recent comments on your posts and allows you to moderate them.' ) . '</p>';
if ( is_blog_admin() && current_user_can( 'edit_posts' ) )
	$help .= '<p>' . __( "<strong>Quick Draft</strong> &mdash; Allows you to create a new post and save it as a draft. Also displays links to the 5 most recent draft posts you've started." ) . '</p>';
if ( ! is_multisite() && current_user_can( 'install_plugins' ) )
	$help .= '<p>' . sprintf(
		/* translators: %s: WordPress Planet URL */
		__( '<strong>WordPress News</strong> &mdash; Latest news from the official WordPress project, the <a href="%s">WordPress Planet</a>, and popular plugins.' ),
		__( 'https://planet.wordpress.org/' )
	) . '</p>';
else
	$help .= '<p>' . sprintf(
		/* translators: %s: WordPress Planet URL */
		__( '<strong>WordPress News</strong> &mdash; Latest news from the official WordPress project and the <a href="%s">WordPress Planet</a>.' ),
		__( 'https://planet.wordpress.org/' )
	) . '</p>';
if ( current_user_can( 'edit_theme_options' ) )
	$help .= '<p>' . __( '<strong>Welcome</strong> &mdash; Shows links for some of the most common tasks when setting up a new site.' ) . '</p>';

$screen->add_help_tab( array(
	'id'      => 'help-content',
	'title'   => __( 'Content' ),
	'content' => $help,
) );

// 销毁变量
unset( $help );

// 帮助-更多信息
$screen->set_help_sidebar(
	'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
	'<p>' . __( '<a href="https://codex.wordpress.org/Dashboard_Screen">Documentation on Dashboard</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://wordpress.org/support/">Support Forums</a>' ) . '</p>'
);

include( ABSPATH . 'wp-admin/admin-header.php' );
?>

<div class="wrap">
	<h1><?php echo esc_html( $title ); ?></h1>

<?php if ( has_action( 'try_gutenberg_panel' ) ) :
	$classes = 'try-gutenberg-panel';

	$option = get_user_meta( get_current_user_id(), 'show_try_gutenberg_panel', true );
	// 0 = hide, 1 = toggled to show or single site creator, 2 = multisite site owner
	$hide = '0' === $option || ( '2' === $option && wp_get_current_user()->user_email !== get_option( 'admin_email' ) );
	if ( $hide )
		$classes .= ' hidden'; ?>

	<div id="try-gutenberg-panel" class="<?php echo esc_attr( $classes ); ?>">
		<?php wp_nonce_field( 'try-gutenberg-panel-nonce', 'trygutenbergpanelnonce', false ); ?>
		<a class="try-gutenberg-panel-close" href="<?php echo esc_url( admin_url( '?try_gutenberg=0' ) ); ?>" aria-label="<?php esc_attr_e( 'Dismiss the Try Gutenberg panel' ); ?>"><?php _e( 'Dismiss' ); ?></a>
		<?php
		/**
         * 将内容添加到管理仪表板上的“古腾堡”面板。
		 * Add content to the Try Gutenberg panel on the admin dashboard.
		 *
		 * To remove the Try Gutenberg panel, use remove_action():
		 *
		 *     remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
		 *
		 * @since 4.9.8
		 */
		do_action( 'try_gutenberg_panel' );
		?>
	</div>
<?php endif; ?>
<?php if ( has_action( 'welcome_panel' ) && current_user_can( 'edit_theme_options' ) ) :
	$classes = 'welcome-panel';

	$option = get_user_meta( get_current_user_id(), 'show_welcome_panel', true );
	// 0 = hide, 1 = toggled to show or single site creator, 2 = multisite site owner
	$hide = '0' === $option || ( '2' === $option && wp_get_current_user()->user_email != get_option( 'admin_email' ) );
	if ( $hide )
		$classes .= ' hidden'; ?>

	<div id="welcome-panel" class="<?php echo esc_attr( $classes ); ?>">
		<?php wp_nonce_field( 'welcome-panel-nonce', 'welcomepanelnonce', false ); ?>
		<a class="welcome-panel-close" href="<?php echo esc_url( admin_url( '?welcome=0' ) ); ?>" aria-label="<?php esc_attr_e( 'Dismiss the welcome panel' ); ?>"><?php _e( 'Dismiss' ); ?></a>
		<?php
		/**
         * 将内容添加到管理仪表板上的欢迎面板。
		 * Add content to the welcome panel on the admin dashboard.
		 *
		 * To remove the default welcome panel, use remove_action():
		 *
		 *     remove_action( 'welcome_panel', 'wp_welcome_panel' );
		 *
		 * @since 3.5.0
		 */
		do_action( 'welcome_panel' );
		?>
	</div>
<?php endif; ?>

	<div id="dashboard-widgets-wrap">
	<?php wp_dashboard(); ?>
	</div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->

<?php
// 渲染模板的事件的事件和新闻的部件。
wp_print_community_events_templates();

require( ABSPATH . 'wp-admin/admin-footer.php' );
