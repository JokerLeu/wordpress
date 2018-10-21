<?php
/**
 * 管理API：默认管理钩子
 * Administration API: Default admin hooks
 *
 * @package WordPress
 * @subpackage Administration
 * @since 4.3.0
 */

// 书签钩子。
// Bookmark hooks.
    // 管理员页面访问被拒绝。输出WordPress链接管理器的“禁用”消息。
add_action( 'admin_page_access_denied', 'wp_link_manager_disabled_message' );

// 仪表板钩子。
// Dashboard hooks.
    // 活动框结束。在仪表板上显示文件上传配额。
add_action( 'activity_box_end', 'wp_dashboard_quota' );

// 媒体钩子。
// Media hooks.
    // 附件提交箱混合动作。在发布元框中显示不可编辑的附件元数据。
add_action( 'attachment_submitbox_misc_actions', 'attachment_submitbox_metadata' );

    // 媒体上传图片。处理上传媒体的过程。
add_action( 'media_upload_image', 'wp_media_upload_handler' );
    // 媒体上传音频。处理上传媒体的过程。
add_action( 'media_upload_audio', 'wp_media_upload_handler' );
    // 媒体上传视频。处理上传媒体的过程。
add_action( 'media_upload_video', 'wp_media_upload_handler' );
    // 媒体上传文件。处理上传媒体的过程。
add_action( 'media_upload_file',  'wp_media_upload_handler' );

    // 文章上传后上传的用户界面。显示多文件上传消息。
add_action( 'post-plupload-upload-ui', 'media_upload_flash_bypass' );
    // 文章HTML上传用户界面。显示浏览器内置的上传消息。
add_action( 'post-html-upload-ui', 'media_upload_html_bypass'  );

    // 异步上传图像。检索HTML表单以修改图像附件。
add_filter( 'async_upload_image', 'get_media_item', 10, 2 );
    // 异步上传音频。检索HTML表单以修改图像附件。
add_filter( 'async_upload_audio', 'get_media_item', 10, 2 );
    // 异步上传视频。检索HTML表单以修改图像附件。
add_filter( 'async_upload_video', 'get_media_item', 10, 2 );
    // 异步上传文件。检索HTML表单以修改图像附件。
add_filter( 'async_upload_file',  'get_media_item', 10, 2 );
    // 保存字段。从media_upload_form_handler()中输入过滤器，如果没有提供，则从文件名中分配默认的POST标题。
add_filter( 'attachment_fields_to_save', 'image_attachment_fields_to_save', 10, 2 );

    // 媒体上传画廊。在iframe中检索传统媒体上传表单。
add_filter( 'media_upload_gallery', 'media_upload_gallery' );
    // 媒体上传库。在iframe中检索传统媒体库。
add_filter( 'media_upload_library', 'media_upload_library' );

    // 媒体上传标签。如果POST具有图像附件，则将画廊选项卡添加到选项卡数组。
add_filter( 'media_upload_tabs', 'update_gallery_tab' );

// 混合钩子
// Misc hooks.
    // 管理初始化。发送引用策略头，以便不从管理屏幕外部发送引用者。
add_action( 'admin_init', 'wp_admin_headers'         );
    // 登陆初始化。发送引用策略头，以便不从管理屏幕外部发送引用者。
add_action( 'login_init', 'wp_admin_headers'         );
    // 管理头部。删除单个使用的URL参数并创建基于新URL的规范链接。
add_action( 'admin_head', 'wp_admin_canonical_url'   );
    // 管理头部。WP颜色方案设置
add_action( 'admin_head', 'wp_color_scheme_settings' );
    // 管理头部。显示站点图标元标签。
add_action( 'admin_head', 'wp_site_icon'             );
    // 管理头部。ipad元
add_action( 'admin_head', '_ipad_meta'               );

// 隐私工具
// Privacy tools
    // 管理菜单。添加请求页。
add_action( 'admin_menu', '_wp_privacy_hook_requests_page' );
    // 加载工具页导出个人数据。为隐私请求屏幕添加选项。
add_action( 'load-tools_page_export_personal_data', '_wp_privacy_requests_screen_options' );
    // 加载工具页删除个人数据。为隐私请求屏幕添加选项。
add_action( 'load-tools_page_remove_personal_data', '_wp_privacy_requests_screen_options' );

// 预呈现
// Prerendering.
    // 在定制程序中是否正在预览站点。
if ( ! is_customize_preview() ) {
    // 管理打印样式。向浏览器打印资源提示，以便预取、预渲染和预连接到Web站点。
	add_filter( 'admin_print_styles', 'wp_resource_hints', 1 );
}
    //  管理打印脚本。如果用户用后退或前进按钮导航到页面，则输出重新加载页面的JS。
add_action( 'admin_print_scripts-post.php',     'wp_page_reload_on_back_button_js' );
    // 新发布的管理打印脚本。如果用户用后退或前进按钮导航到页面，则输出重新加载页面的JS。
add_action( 'admin_print_scripts-post-new.php', 'wp_page_reload_on_back_button_js' );

    // 更新选项首页。如果位置、首页或页面在前面改变，刷新重写规则。
add_action( 'update_option_home',          'update_home_siteurl', 10, 2 );
add_action( 'update_option_siteurl',       'update_home_siteurl', 10, 2 );
add_action( 'update_option_page_on_front', 'update_home_siteurl', 10, 2 );
add_action( 'update_option_admin_email',   'wp_site_admin_email_change_notification', 10, 3 );

add_action( 'add_option_new_admin_email',    'update_option_new_admin_email', 10, 2 );
add_action( 'update_option_new_admin_email', 'update_option_new_admin_email', 10, 2 );

add_filter( 'heartbeat_received', 'wp_check_locked_posts',  10,  3 );
add_filter( 'heartbeat_received', 'wp_refresh_post_lock',   10,  3 );
add_filter( 'wp_refresh_nonces', 'wp_refresh_post_nonces', 10,  3 );
add_filter( 'heartbeat_received', 'heartbeat_autosave',     500, 2 );

add_filter( 'heartbeat_settings', 'wp_heartbeat_set_suspension' );

// 导航菜单钩子。
// Nav Menu hooks.
add_action( 'admin_head-nav-menus.php', '_wp_delete_orphaned_draft_menu_items' );

// 插件钩子。
// Plugin hooks.
add_filter( 'whitelist_options', 'option_update_filter' );

// 插件安装钩子。
// Plugin Install hooks.
add_action( 'install_plugins_featured',               'install_dashboard' );
add_action( 'install_plugins_upload',                 'install_plugins_upload' );
add_action( 'install_plugins_search',                 'display_plugins_table' );
add_action( 'install_plugins_popular',                'display_plugins_table' );
add_action( 'install_plugins_recommended',            'display_plugins_table' );
add_action( 'install_plugins_new',                    'display_plugins_table' );
add_action( 'install_plugins_beta',                   'display_plugins_table' );
add_action( 'install_plugins_favorites',              'display_plugins_table' );
add_action( 'install_plugins_pre_plugin-information', 'install_plugin_information' );

// 模板钩子。
// Template hooks.
add_action( 'admin_enqueue_scripts', array( 'WP_Internal_Pointers', 'enqueue_scripts'                ) );
add_action( 'user_register',         array( 'WP_Internal_Pointers', 'dismiss_pointers_for_new_users' ) );

// 主题钩子。
// Theme hooks.
add_action( 'customize_controls_print_footer_scripts', 'customize_themes_print_templates' );

// 主题安装钩子。
// Theme Install hooks.
// add_action('install_themes_dashboard', 'install_themes_dashboard');
// add_action('install_themes_upload', 'install_themes_upload', 10, 0);
// add_action('install_themes_search', 'display_themes');
// add_action('install_themes_featured', 'display_themes');
// add_action('install_themes_new', 'display_themes');
// add_action('install_themes_updated', 'display_themes');
add_action( 'install_themes_pre_theme-information', 'install_theme_information' );

// 用户钩子。
// User hooks.
add_action( 'admin_init', 'default_password_nag_handler' );

add_action( 'admin_notices', 'default_password_nag' );
add_action( 'admin_notices', 'new_user_email_admin_notice' );

add_action( 'profile_update', 'default_password_nag_edit_user', 10, 2 );

add_action( 'personal_options_update', 'send_confirmation_on_profile_email' );

// 更新钩子。
// Update hooks.
add_action( 'load-plugins.php', 'wp_plugin_update_rows', 20 ); // After wp_update_plugins() is called.
add_action( 'load-themes.php', 'wp_theme_update_rows', 20 ); // After wp_update_themes() is called.

add_action( 'admin_notices', 'update_nag',      3  );
add_action( 'admin_notices', 'maintenance_nag', 10 );

add_filter( 'update_footer', 'core_update_footer' );

// 更新核心钩子。
// Update Core hooks.
add_action( '_core_updated_successfully', '_redirect_to_about_wordpress' );

// 更新钩子。
// Upgrade hooks.
add_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );
add_action( 'upgrader_process_complete', 'wp_version_check', 10, 0 );
add_action( 'upgrader_process_complete', 'wp_update_plugins', 10, 0 );
add_action( 'upgrader_process_complete', 'wp_update_themes', 10, 0 );

// 隐私钩子
// Privacy hooks
add_filter( 'wp_privacy_personal_data_erasure_page', 'wp_privacy_process_personal_data_erasure_page', 10, 5 );
add_filter( 'wp_privacy_personal_data_export_page', 'wp_privacy_process_personal_data_export_page', 10, 7 );
add_action( 'wp_privacy_personal_data_export_file', 'wp_privacy_generate_personal_data_export_file', 10 );
add_action( 'wp_privacy_personal_data_erased', '_wp_privacy_send_erasure_fulfillment_notification', 10 );

// 隐私策略文本更改检查。
// Privacy policy text changes check.
add_action( 'admin_init', array( 'WP_Privacy_Policy_Content', 'text_change_check' ), 100 );

// 显示一个“邮箱”的文本建议的隐私政策。
// Show a "postbox" with the text suggestions for a privacy policy.
add_action( 'edit_form_after_title', array( 'WP_Privacy_Policy_Content', 'notice' ) );

// 从WordPress中添加建议的策略文本。
// Add the suggested policy text from WordPress.
add_action( 'admin_init', array( 'WP_Privacy_Policy_Content', 'add_suggested_content' ), 1 );

// 在更新策略页时更新缓存策略信息。
// Update the cached policy info when the policy page is updated.
add_action( 'post_updated', array( 'WP_Privacy_Policy_Content', '_policy_page_updated' ) );

// 在“隐私”页下拉中添加“（草案）”草稿页标题。
// Append '(Draft)' to draft page titles in the privacy page dropdown.
add_filter( 'list_pages', '_wp_privacy_settings_filter_draft_page_titles', 10, 2 );
