<?php
/**
 * 写作设置管理面板。设置-撰写
 * Writing settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

// 用户权限检测
if ( ! current_user_can( 'manage_options' ) )
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );

// 页面标题
$title = __('Writing Settings');
// 父页面
$parent_file = 'options-general.php';

// 帮助-概述
get_current_screen()->add_help_tab( array(
	'id'      => 'overview',
	'title'   => __('Overview'),
	'content' => '<p>' . __('You can submit content in several different ways; this screen holds the settings for all of them. The top section controls the editor within the dashboard, while the rest control external publishing methods. For more information on any of these methods, use the documentation links.') . '</p>' .
		'<p>' . __('You must click the Save Changes button at the bottom of the screen for new settings to take effect.') . '</p>',
) );

/**
 * 这个过滤器被记录在wp-admin/options.php中。
 * This filter is documented in wp-admin/options.php
 */
if ( apply_filters( 'enable_post_by_email_configuration', true ) ) {
    // 帮助-通过电子邮件发布
	get_current_screen()->add_help_tab( array(
		'id'      => 'options-postemail',
		'title'   => __( 'Post Via Email' ),
		'content' => '<p>' . __( 'Post via email settings allow you to send your WordPress installation an email with the content of your post. You must set up a secret email account with POP3 access to use this, and any mail received at this address will be posted, so it&#8217;s a good idea to keep this address very secret.' ) . '</p>',
	) );
}

/**
 * 这个过滤器被记录在wp-admin/options-writing.php中。
 * This filter is documented in wp-admin/options-writing.php
 */
if ( apply_filters( 'enable_update_services_configuration', true ) ) {
    // 帮助-更新服务
	get_current_screen()->add_help_tab( array(
		'id'      => 'options-services',
		'title'   => __( 'Update Services' ),
		'content' => '<p>' . __( 'If desired, WordPress will automatically alert various services of your new posts.' ) . '</p>',
	) );
}

// 帮助-更多信息：
get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . __('<a href="https://codex.wordpress.org/Settings_Writing_Screen">Documentation on Writing Settings</a>') . '</p>' .
	'<p>' . __('<a href="https://wordpress.org/support/">Support Forums</a>') . '</p>'
);

// 管理界面头部
include( ABSPATH . 'wp-admin/admin-header.php' );
?>

<!--设置-撰写页面 由wp-admin/options-writing.php生成-->
<div class="wrap">
<h1><?php echo esc_html( $title ); ?></h1>

<form method="post" action="options.php">
<?php
// 设置页的输出nonce、action和option_page页字段。
settings_fields('writing');
?>

<table class="form-table">
<?php
// 基于选项名称检索选项值。
if ( get_site_option( 'initial_db_version' ) < 32453 ) :
    ?>
<tr>
<th scope="row"><?php _e('Formatting') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Formatting') ?></span></legend>
<label for="use_smilies">
<input name="use_smilies" type="checkbox" id="use_smilies" value="1" <?php checked('1', get_option('use_smilies')); ?> />
<?php _e('Convert emoticons like <code>:-)</code> and <code>:-P</code> to graphics on display') ?></label><br />
<label for="use_balanceTags"><input name="use_balanceTags" type="checkbox" id="use_balanceTags" value="1" <?php checked('1', get_option('use_balanceTags')); ?> /> <?php _e('WordPress should correct invalidly nested XHTML automatically') ?></label>
</fieldset></td>
</tr>
<?php endif; ?>
    <!--默认文章分类目录-->
<tr>
<th scope="row"><label for="default_category"><?php _e('Default Post Category') ?></label></th>
<td>
<?php
// 显示或检索类别的HTML下拉列表。
wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'default_category', 'orderby' => 'name', 'selected' => get_option('default_category'), 'hierarchical' => true));
?>
</td>
</tr>
<?php
// 获取文章格式字符串
$post_formats = get_post_format_strings();
unset( $post_formats['standard'] );
?>
    <!--默认文章形式-->
<tr>
<th scope="row"><label for="default_post_format"><?php _e('Default Post Format') ?></label></th>
<td>
	<select name="default_post_format" id="default_post_format">
		<option value="0"><?php echo get_post_format_string( 'standard' ); ?></option>
<?php foreach ( $post_formats as $format_slug => $format_name ): ?>
		<option<?php selected( get_option( 'default_post_format' ), $format_slug ); ?> value="<?php echo esc_attr( $format_slug ); ?>"><?php echo esc_html( $format_name ); ?></option>
<?php endforeach; ?>
	</select>
</td>
</tr>
<?php
if ( get_option( 'link_manager_enabled' ) ) :
?>
<tr>
<th scope="row"><label for="default_link_category"><?php _e('Default Link Category') ?></label></th>
<td>
<?php
wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'default_link_category', 'orderby' => 'name', 'selected' => get_option('default_link_category'), 'hierarchical' => true, 'taxonomy' => 'link_category'));
?>
</td>
</tr>
<?php endif; ?>

<?php
do_settings_fields('writing', 'default');
do_settings_fields('writing', 'remote_publishing'); // A deprecated section. 不受欢迎的部分
?>
</table>

<?php
/** This filter is documented in wp-admin/options.php */
if ( apply_filters( 'enable_post_by_email_configuration', true ) ) {
?>
    <!--通过电子邮件发布-->
<h2 class="title"><?php _e( 'Post via email' ) ?></h2>
<p><?php
printf(
	/**
     * 译者：1, 2, 3：随机电子邮件地址的例子 随机字符串
     * translators: 1, 2, 3: examples of random email addresses
     */
	__( 'To post to WordPress by email you must set up a secret email account with POP3 access. Any mail received at this address will be posted, so it&#8217;s a good idea to keep this address very secret. Here are three random strings you could use: %1$s, %2$s, %3$s.' ),
	sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) ),
	sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) ),
	sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) )
);
?></p>

<table class="form-table">
    <!--邮件服务器-->
<tr>
<th scope="row"><label for="mailserver_url"><?php _e('Mail Server') ?></label></th>
<td><input name="mailserver_url" type="text" id="mailserver_url" value="<?php form_option('mailserver_url'); ?>" class="regular-text code" />
<label for="mailserver_port"><?php _e('Port') ?></label>
<input name="mailserver_port" type="text" id="mailserver_port" value="<?php form_option('mailserver_port'); ?>" class="small-text" />
</td>
</tr>
    <!--登录名-->
<tr>
<th scope="row"><label for="mailserver_login"><?php _e('Login Name') ?></label></th>
<td><input name="mailserver_login" type="text" id="mailserver_login" value="<?php form_option('mailserver_login'); ?>" class="regular-text ltr" /></td>
</tr>
    <!--密码-->
<tr>
<th scope="row"><label for="mailserver_pass"><?php _e('Password') ?></label></th>
<td>
<input name="mailserver_pass" type="text" id="mailserver_pass" value="<?php form_option('mailserver_pass'); ?>" class="regular-text ltr" />
</td>
</tr>
    <!--默认邮件发表分类目录-->
<tr>
<th scope="row"><label for="default_email_category"><?php _e('Default Mail Category') ?></label></th>
<td>
<?php
// 显示或检索类别的HTML下拉列表。
wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'default_email_category', 'orderby' => 'name', 'selected' => get_option('default_email_category'), 'hierarchical' => true));
?>
</td>
</tr>
<?php
// 打印特定设置部分的设置字段
do_settings_fields('writing', 'post_via_email');
?>
</table>
<?php } ?>

<?php
/**
 * 过滤器是否启用更新服务部分的写作设置屏幕。
 * Filters whether to enable the Update Services section in the Writing settings screen.
 *
 * @since 3.0.0
 *
 * @param bool $enable Whether to enable the Update Services settings area. Default true.
 */
if ( apply_filters( 'enable_update_services_configuration', true ) ) {
?>
<h2 class="title"><?php _e( 'Update Services' ) ?></h2>

<?php if ( 1 == get_option('blog_public') ) : ?>

	<p><label for="ping_sites"><?php
		printf(
			/* translators: %s: Codex URL */
			__( 'When you publish a new post, WordPress automatically notifies the following site update services. For more about this, see <a href="%s">Update Services</a> on the Codex. Separate multiple service URLs with line breaks.' ),
			__( 'https://codex.wordpress.org/Update_Services' )
		);
	?></label></p>

	<textarea name="ping_sites" id="ping_sites" class="large-text code" rows="3"><?php echo esc_textarea( get_option('ping_sites') ); ?></textarea>

<?php else : ?>

	<p><?php
		printf(
			/* translators: 1: Codex URL, 2: Reading Settings URL */
			__( 'WordPress is not notifying any <a href="%1$s">Update Services</a> because of your site&#8217;s <a href="%2$s">visibility settings</a>.' ),
			__( 'https://codex.wordpress.org/Update_Services' ),
			'options-reading.php'
		);
	?></p>

<?php endif; ?>
<?php } // enable_update_services_configuration 启用更新服务配置 ?>

<?php
// 打印添加到特定设置页的所有设置部分
do_settings_sections('writing');
?>

<?php
// 用提交的文本和适当的类回送提交按钮。
submit_button();
?>
</form>
</div>

<?php
// 页脚
include( ABSPATH . 'wp-admin/admin-footer.php' );
?>
