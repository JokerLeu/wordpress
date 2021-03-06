<?php
/**
 * 安装插件管理面板。
 * Install plugin administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */
// 待办路线：此页通过特定的iframe处理程序而不是下面的do_action
// TODO route this pages via a specific iframe handler instead of the do_action below
if ( !defined( 'IFRAME_REQUEST' ) && isset( $_GET['tab'] ) && ( 'plugin-information' == $_GET['tab'] ) )
	define( 'IFRAME_REQUEST', true );

/**
 * WordPress管理引导。
 * WordPress Administration Bootstrap.
 */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can('install_plugins') )
	wp_die(__('Sorry, you are not allowed to install plugins on this site.'));

if ( is_multisite() && ! is_network_admin() ) {
    // 检索网络的管理区域的URL
	wp_redirect( network_admin_url( 'plugin-install.php' ) );
	exit();
}

// 获取一个WP_List_Table类的实例。核心类用于实现在列表表中安装插件的显示。
$wp_list_table = _get_list_table('WP_Plugin_Install_List_Table');
$pagenum = $wp_list_table->get_pagenum();

if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {
	$location = remove_query_arg( '_wp_http_referer', wp_unslash( $_SERVER['REQUEST_URI'] ) );

	if ( ! empty( $_REQUEST['paged'] ) ) {
		$location = add_query_arg( 'paged', (int) $_REQUEST['paged'], $location );
	}

	wp_redirect( $location );
	exit;
}

$wp_list_table->prepare_items();

$total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );

if ( $pagenum > $total_pages && $total_pages > 0 ) {
	wp_redirect( add_query_arg( 'paged', $total_pages ) );
	exit;
}

$title = __( 'Add Plugins' );
$parent_file = 'plugins.php';

wp_enqueue_script( 'plugin-install' );
if ( 'plugin-information' != $tab )
	add_thickbox();

$body_id = $tab;

wp_enqueue_script( 'updates' );

/**
 * 加载安装插件屏幕上的每个选项卡之前触发。
 * Fires before each tab on the Install Plugins screen is loaded.
 *
 * The dynamic portion of the action hook, `$tab`, allows for targeting
 * individual tabs, for instance 'install_plugins_pre_plugin-information'.
 *
 * @since 2.7.0
 */
do_action( "install_plugins_pre_{$tab}" );

/*
 * 在每个非上传插件安装屏幕上调用预上传操作，因为表单总是显示在这些屏幕上。
 * Call the pre upload action on every non-upload plugin installation screen
 * because the form is always displayed on these screens.
 */
if ( 'upload' !== $tab ) {
	/**
     * 此操作在wp-admin/plugin-install.php中被记录。
     * This action is documented in wp-admin/plugin-install.php
     */
	do_action( 'install_plugins_pre_upload' );
}
// 帮助-概述
get_current_screen()->add_help_tab( array(
'id'		=> 'overview',
'title'		=> __('Overview'),
'content'	=>
	'<p>' . sprintf( __('Plugins hook into WordPress to extend its functionality with custom features. Plugins are developed independently from the core WordPress application by thousands of developers all over the world. All plugins in the official <a href="%s">WordPress Plugin Directory</a> are compatible with the license WordPress uses.' ), __( 'https://wordpress.org/plugins/' ) ) . '</p>' .
	'<p>' . __( 'You can find new plugins to install by searching or browsing the directory right here in your own Plugins section.' ) . ' <span id="live-search-desc" class="hide-if-no-js">' . __( 'The search results will be updated as you type.' ) . '</span></p>'

) );
// 帮助-插件的添加
get_current_screen()->add_help_tab( array(
'id'		=> 'adding-plugins',
'title'		=> __('Adding Plugins'),
'content'	=>
	'<p>' . __('If you know what you&#8217;re looking for, Search is your best bet. The Search screen has options to search the WordPress Plugin Directory for a particular Term, Author, or Tag. You can also search the directory by selecting popular tags. Tags in larger type mean more plugins have been labeled with that tag.') . '</p>' .
	'<p>' . __( 'If you just want to get an idea of what&#8217;s available, you can browse Featured and Popular plugins by using the links above the plugins list. These sections rotate regularly.' ) . '</p>' .
	'<p>' . __( 'You can also browse a user&#8217;s favorite plugins, by using the Favorites link above the plugins list and entering their WordPress.org username.' ) . '</p>' .
	'<p>' . __( 'If you want to install a plugin that you&#8217;ve downloaded elsewhere, click the Upload Plugin button above the plugins list. You will be prompted to upload the .zip package, and once uploaded, you can activate the new plugin.' ) . '</p>'
) );

// 帮助-更多信息：
get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . __('<a href="https://codex.wordpress.org/Plugins_Add_New_Screen">Documentation on Installing Plugins</a>') . '</p>' .
	'<p>' . __('<a href="https://wordpress.org/support/">Support Forums</a>') . '</p>'
);

get_current_screen()->set_screen_reader_content( array(
	'heading_views'      => __( 'Filter plugins list' ),
	'heading_pagination' => __( 'Plugins list navigation' ),
	'heading_list'       => __( 'Plugins list' ),
) );

/**
 * WordPress管理模板头。
 * WordPress Administration Template Header.
 */
include(ABSPATH . 'wp-admin/admin-header.php');
?>
<div class="wrap <?php echo esc_attr( "plugin-install-tab-$tab" ); ?>">
<h1 class="wp-heading-inline"><?php
echo esc_html( $title );
?></h1>

<?php
if ( ! empty( $tabs['upload'] ) && current_user_can( 'upload_plugins' ) ) {
	printf( ' <a href="%s" class="upload-view-toggle page-title-action"><span class="upload">%s</span><span class="browse">%s</span></a>',
		( 'upload' === $tab ) ? self_admin_url( 'plugin-install.php' ) : self_admin_url( 'plugin-install.php?tab=upload' ),
		__( 'Upload Plugin' ),
		__( 'Browse Plugins' )
	);
}
?>

<hr class="wp-header-end">

<?php
/**
 * 在每个非上传插件安装屏幕上输出上传插件表单，这样就可以通过JavaScript显示它，而不是打开专用的上传插件页面。
 * Output the upload plugin form on every non-upload plugin installation screen, so it can be
 * displayed via JavaScript rather then opening up the devoted upload plugin page.
 */
if ( 'upload' !== $tab ) {
	?>
	<div class="upload-plugin-wrap">
		<?php
		/** This action is documented in wp-admin/plugin-install.php */
		do_action( 'install_plugins_upload' );
		?>
	</div>
	<?php
	$wp_list_table->views();
	echo '<br class="clear" />';
}

/**
 * 在安装插件屏幕的每个选项卡中，在插件列表表之后触发。
 * Fires after the plugins list table in each tab of the Install Plugins screen.
 *
 * The dynamic portion of the action hook, `$tab`, allows for targeting
 * individual tabs, for instance 'install_plugins_plugin-information'.
 *
 * @since 2.7.0
 *
 * @param int $paged The current page number of the plugins list table.
 */
do_action( "install_plugins_{$tab}", $paged ); ?>

	<span class="spinner"></span>
</div>

<?php
// 在需要时打印文件系统凭据模式。
wp_print_request_filesystem_credentials_modal();
// 打印更新管理通知的JavaScript模板。
wp_print_admin_notice_templates();

/**
 * WordPress管理模板页脚。
 * WordPress Administration Template Footer.
 */
include(ABSPATH . 'wp-admin/admin-footer.php');
