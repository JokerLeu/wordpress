<?php
/**
 * WordPress查询API
 * WordPress Query API
 *
 * The query API attempts to get which part of WordPress the user is on. It
 * also provides functionality for getting URL query information.
 *
 * @link https://codex.wordpress.org/The_Loop More information on The Loop.
 *
 * @package WordPress
 * @subpackage Query
 */

/**
 * 检索WP_Query类变量。
 * Retrieve variable in the WP_Query class.
 *
 * @since 1.5.0
 * @since 3.9.0 The `$default` argument was introduced.
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param string $var       The variable key to retrieve.
 * @param mixed  $default   Optional. Value to return if the query variable is not set. Default empty.
 * @return mixed Contents of the query variable.
 */
function get_query_var( $var, $default = '' ) {
	global $wp_query;
	return $wp_query->get( $var, $default );
}

/**
 * 检索当前查询的对象。
 * Retrieve the currently-queried object.
 *
 * Wrapper for WP_Query::get_queried_object().
 *
 * @since 3.1.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return object Queried object.
 */
function get_queried_object() {
	global $wp_query;
	return $wp_query->get_queried_object();
}

/**
 * 检索当前查询对象的ID。
 * Retrieve ID of the current queried object.
 *
 * Wrapper for WP_Query::get_queried_object_id().
 *
 * @since 3.1.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return int ID of the queried object.
 */
function get_queried_object_id() {
	global $wp_query;
	return $wp_query->get_queried_object_id();
}

/**
 * 设置查询变量。
 * Set query variable.
 *
 * @since 2.2.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param string $var   Query variable key.
 * @param mixed  $value Query variable value.
 */
function set_query_var( $var, $value ) {
	global $wp_query;
	$wp_query->set( $var, $value );
}

/**
 * 设置具有查询参数的循环。
 * Sets up The Loop with query parameters.
 *
 * Note: This function will completely override the main query and isn't intended for use
 * by plugins or themes. Its overly-simplistic approach to modifying the main query can be
 * problematic and should be avoided wherever possible. In most cases, there are better,
 * more performant options for modifying the main query such as via the {@see 'pre_get_posts'}
 * action within WP_Query.
 *
 * This must not be used within the WordPress Loop.
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param array|string $query Array or string of WP_Query arguments.
 * @return array List of post objects.
 */
function query_posts($query) {
	$GLOBALS['wp_query'] = new WP_Query();
	return $GLOBALS['wp_query']->query($query);
}

/**
 * 销毁先前的查询并设置一个新查询。
 * Destroys the previous query and sets up a new query.
 *
 * This should be used after query_posts() and before another query_posts().
 * This will remove obscure bugs that occur when the previous WP_Query object
 * is not destroyed properly before another is set up.
 *
 * @since 2.3.0
 *
 * @global WP_Query $wp_query     Global WP_Query instance.
 * @global WP_Query $wp_the_query Copy of the global WP_Query instance created during wp_reset_query().
 */
function wp_reset_query() {
	$GLOBALS['wp_query'] = $GLOBALS['wp_the_query'];
	wp_reset_postdata();
}

/**
 * 在循环一个单独的查询之后，这个函数将$POST全局恢复到主查询中的当前POST。
 * After looping through a separate query, this function restores
 * the $post global to the current post in the main query.
 *
 * @since 3.0.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 */
function wp_reset_postdata() {
	global $wp_query;

	if ( isset( $wp_query ) ) {
		$wp_query->reset_postdata();
	}
}

/**
 * 查询类型检查。
 * Query type checks.
 */

/**
 * 是否存在对现有存档页的查询？
 * Is the query for an existing archive page?
 *
 * Month, Year, Category, Author, Post Type archive...
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_archive() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_archive();
}

/**
 * 是否存在对现有帖子类型归档页面的查询？
 * Is the query for an existing post type archive page?
 *
 * @since 3.1.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param string|array $post_types Optional. Post type or array of posts types to check against.
 * @return bool
 */
function is_post_type_archive( $post_types = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_post_type_archive( $post_types );
}

/**
 * 是否存在对现有附件页的查询？
 * Is the query for an existing attachment page?
 *
 * @since 2.0.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param int|string|array|object $attachment Attachment ID, title, slug, or array of such.
 * @return bool
 */
function is_attachment( $attachment = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_attachment( $attachment );
}

/**
 * 是否存在对现有作者档案页的查询？
 * Is the query for an existing author archive page?
 *
 * If the $author parameter is specified, this function will additionally
 * check if the query is for one of the authors specified.
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param mixed $author Optional. User ID, nickname, nicename, or array of User IDs, nicknames, and nicenames
 * @return bool
 */
function is_author( $author = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_author( $author );
}

/**
 * 是否存在对现有分类存档页的查询？
 * Is the query for an existing category archive page?
 *
 * If the $category parameter is specified, this function will additionally
 * check if the query is for one of the categories specified.
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param mixed $category Optional. Category ID, name, slug, or array of Category IDs, names, and slugs.
 * @return bool
 */
function is_category( $category = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_category( $category );
}

/**
 * 是否存在对现有标记存档页的查询？
 * Is the query for an existing tag archive page?
 *
 * If the $tag parameter is specified, this function will additionally
 * check if the query is for one of the tags specified.
 *
 * @since 2.3.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param mixed $tag Optional. Tag ID, name, slug, or array of Tag IDs, names, and slugs.
 * @return bool
 */
function is_tag( $tag = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_tag( $tag );
}

/**
 * 是否存在对现有自定义分类目录页的查询？
 * Is the query for an existing custom taxonomy archive page?
 *
 * If the $taxonomy parameter is specified, this function will additionally
 * check if the query is for that specific $taxonomy.
 *
 * If the $term parameter is specified in addition to the $taxonomy parameter,
 * this function will additionally check if the query is for one of the terms
 * specified.
 *
 * @since 2.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param string|array     $taxonomy Optional. Taxonomy slug or slugs.
 * @param int|string|array $term     Optional. Term ID, name, slug or array of Term IDs, names, and slugs.
 * @return bool True for custom taxonomy archive pages, false for built-in taxonomies (category and tag archives).
 */
function is_tax( $taxonomy = '', $term = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_tax( $taxonomy, $term );
}

/**
 * 是否存在对现有日期存档的查询？
 * Is the query for an existing date archive?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_date() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_date();
}

/**
 * 是否对现有的日存档进行查询？
 * Is the query for an existing day archive?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_day() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_day();
}

/**
 * 是对feed的查询吗？
 * Is the query for a feed?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param string|array $feeds Optional feed types to check.
 * @return bool
 */
function is_feed( $feeds = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_feed( $feeds );
}

/**
 * 查询注释的查询是什么？
 * Is the query for a comments feed?
 *
 * @since 3.0.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_comment_feed() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_comment_feed();
}

/**
 * 是对站点首页的查询吗？
 * Is the query for the front page of the site?
 *
 * This is for what is displayed at your site's main URL.
 *
 * Depends on the site's "Front page displays" Reading Settings 'show_on_front' and 'page_on_front'.
 *
 * If you set a static page for the front page of your site, this function will return
 * true when viewing that page.
 *
 * Otherwise the same as @see is_home()
 *
 * @since 2.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool True, if front of site.
 */
function is_front_page() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_front_page();
}

/**
 * 确定查询是否适用于博客主页。
 * Determines if the query is for the blog homepage.
 *
 * The blog homepage is the page that shows the time-based blog content of the site.
 *
 * is_home() is dependent on the site's "Front page displays" Reading Settings 'show_on_front'
 * and 'page_for_posts'.
 *
 * If a static page is set for the front page of the site, this function will return true only
 * on the page you set as the "Posts page".
 *
 * @since 1.5.0
 *
 * @see is_front_page()
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool True if blog view homepage, otherwise false.
 */
function is_home() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_home();
}

/**
 * 是否对现有的月份存档进行查询？
 * Is the query for an existing month archive?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_month() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_month();
}

/**
 * 是否对现有的单个页面进行查询？
 * Is the query for an existing single page?
 *
 * If the $page parameter is specified, this function will additionally
 * check if the query is for one of the pages specified.
 *
 * @see is_single()
 * @see is_singular()
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param int|string|array $page Optional. Page ID, title, slug, or array of such. Default empty.
 * @return bool Whether the query is for an existing single page.
 */
function is_page( $page = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_page( $page );
}

/**
 * 是页面结果的查询，而不是第一页的查询？
 * Is the query for paged result and not for the first page?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_paged() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_paged();
}

/**
 * 是对POST或页面预览的查询吗？
 * Is the query for a post or page preview?
 *
 * @since 2.0.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_preview() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_preview();
}

/**
 * 是机器人文件的查询吗？
 * Is the query for the robots file?
 *
 * @since 2.1.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_robots() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_robots();
}

/**
 * 是搜索的查询吗？
 * Is the query for a search?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_search() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_search();
}

/**
 * 是否对现有的单个帖子进行查询？
 * Is the query for an existing single post?
 *
 * Works for any post type, except attachments and pages
 *
 * If the $post parameter is specified, this function will additionally
 * check if the query is for one of the Posts specified.
 *
 * @see is_page()
 * @see is_singular()
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param int|string|array $post Optional. Post ID, title, slug, or array of such. Default empty.
 * @return bool Whether the query is for an existing single post.
 */
function is_single( $post = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_single( $post );
}

/**
 * 是对任何POST类型（POST、附件、页面、自定义POST类型）的现有单个POST的查询吗？
 * Is the query for an existing single post of any post type (post, attachment, page,
 * custom post types)?
 *
 * If the $post_types parameter is specified, this function will additionally
 * check if the query is for one of the Posts Types specified.
 *
 * @see is_page()
 * @see is_single()
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param string|array $post_types Optional. Post type or array of post types. Default empty.
 * @return bool Whether the query is for an existing single post of any of the given post types.
 */
function is_singular( $post_types = '' ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_singular( $post_types );
}

/**
 * 是查询特定时间吗？
 * Is the query for a specific time?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_time() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_time();
}

/**
 * 查询回溯端点调用吗？
 * Is the query for a trackback endpoint call?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_trackback() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_trackback();
}

/**
 * 是否对现有的年份存档进行查询？
 * Is the query for an existing year archive?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_year() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_year();
}

/**
 * 查询是404（返回没有结果）吗？
 * Is the query a 404 (returns no results)?
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_404() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_404();
}

/**
 * 是对嵌入式帖子的查询吗？
 * Is the query for an embedded post?
 *
 * @since 4.4.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool Whether we're in an embedded post or not.
 */
function is_embed() {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
		return false;
	}

	return $wp_query->is_embed();
}

/**
 * 查询是主查询吗？
 * Is the query the main query?
 *
 * @since 3.3.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function is_main_query() {
	if ( 'pre_get_posts' === current_filter() ) {
		$message = sprintf(
			/* translators: 1: pre_get_posts 2: WP_Query->is_main_query() 3: is_main_query() 4: link to codex is_main_query() page. */
			__( 'In %1$s, use the %2$s method, not the %3$s function. See %4$s.' ),
			'<code>pre_get_posts</code>',
			'<code>WP_Query->is_main_query()</code>',
			'<code>is_main_query()</code>',
			__( 'https://codex.wordpress.org/Function_Reference/is_main_query' )
		);
		_doing_it_wrong( __FUNCTION__, $message, '3.7.0' );
	}

	global $wp_query;
	return $wp_query->is_main_query();
}

/**
 * 循环。帖子循环控制。
 * The Loop. Post loop control.
 */

/**
 * 当前WordPress查询是否有循环的结果。
 * Whether current WordPress query has results to loop over.
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function have_posts() {
	global $wp_query;
	return $wp_query->have_posts();
}

/**
 * 调用方是否处于循环中。
 * Whether the caller is in the Loop.
 *
 * @since 2.0.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool True if caller is within loop, false if loop hasn't started or ended.
 */
function in_the_loop() {
	global $wp_query;
	return $wp_query->in_the_loop;
}

/**
 * 倒回循环posts
 * Rewind the loop posts.
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 */
function rewind_posts() {
	global $wp_query;
	$wp_query->rewind_posts();
}

/**
 * 在循环中迭代POST索引
 * Iterate the post index in the loop.
 *
 * @since 1.5.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 */
function the_post() {
	global $wp_query;
	$wp_query->the_post();
}

/**
 * 评论循环。
 * Comments loop.
 */

/**
 * 是否有评论循环。
 * Whether there are comments to loop over.
 *
 * @since 2.2.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return bool
 */
function have_comments() {
	global $wp_query;
	return $wp_query->have_comments();
}

/**
 * 在注释循环中迭代评论索引。
 * Iterate comment index in the comment loop.
 *
 * @since 2.2.0
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @return object
 */
function the_comment() {
	global $wp_query;
	return $wp_query->the_comment();
}

/**
 * 将旧片段重定向到正确的永久位置。
 * Redirect old slugs to the correct permalink.
 *
 * Attempts to find the current slug from the past slugs.
 *
 * @since 2.1.0
 */
function wp_old_slug_redirect() {
	if ( is_404() && '' !== get_query_var( 'name' ) ) {
		// Guess the current post_type based on the query vars.
		if ( get_query_var( 'post_type' ) ) {
			$post_type = get_query_var( 'post_type' );
		} elseif ( get_query_var( 'attachment' ) ) {
			$post_type = 'attachment';
		} elseif ( get_query_var( 'pagename' ) ) {
			$post_type = 'page';
		} else {
			$post_type = 'post';
		}

		if ( is_array( $post_type ) ) {
			if ( count( $post_type ) > 1 ) {
				return;
			}
			$post_type = reset( $post_type );
		}

		// Do not attempt redirect for hierarchical post types
		if ( is_post_type_hierarchical( $post_type ) ) {
			return;
		}

		$id = _find_post_by_old_slug( $post_type );

		if ( ! $id ) {
			$id = _find_post_by_old_date( $post_type );
		}

		/**
		 * Filters the old slug redirect post ID.
		 *
		 * @since 4.9.3
		 *
		 * @param int $id The redirect post ID.
		 */
		$id = apply_filters( 'old_slug_redirect_post_id', $id );

		if ( ! $id ) {
			return;
		}

		$link = get_permalink( $id );

		if ( get_query_var( 'paged' ) > 1 ) {
			$link = user_trailingslashit( trailingslashit( $link ) . 'page/' . get_query_var( 'paged' ) );
		} elseif( is_embed() ) {
			$link = user_trailingslashit( trailingslashit( $link ) . 'embed' );
		}

		/**
		 * Filters the old slug redirect URL.
		 *
		 * @since 4.4.0
		 *
		 * @param string $link The redirect URL.
		 */
		$link = apply_filters( 'old_slug_redirect_url', $link );

		if ( ! $link ) {
			return;
		}

		wp_redirect( $link, 301 ); // Permanent redirect
		exit;
	}
}

/**
 * 找到用于重新引导旧片段的帖子ID。
 * Find the post ID for redirecting an old slug.
 *
 * @see wp_old_slug_redirect()
 *
 * @since 4.9.3
 * @access private
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param string $post_type The current post type based on the query vars.
 * @return int $id The Post ID.
 */
function _find_post_by_old_slug( $post_type ) {
	global $wpdb;

	$query = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta, $wpdb->posts WHERE ID = post_id AND post_type = %s AND meta_key = '_wp_old_slug' AND meta_value = %s", $post_type, get_query_var( 'name' ) );

	// if year, monthnum, or day have been specified, make our query more precise
	// just in case there are multiple identical _wp_old_slug values
	if ( get_query_var( 'year' ) ) {
		$query .= $wpdb->prepare( " AND YEAR(post_date) = %d", get_query_var( 'year' ) );
	}
	if ( get_query_var( 'monthnum' ) ) {
		$query .= $wpdb->prepare( " AND MONTH(post_date) = %d", get_query_var( 'monthnum' ) );
	}
	if ( get_query_var( 'day' ) ) {
		$query .= $wpdb->prepare( " AND DAYOFMONTH(post_date) = %d", get_query_var( 'day' ) );
	}

	$id = (int) $wpdb->get_var( $query );

	return $id;
}

/**
 * 查找用于重定向旧日期的帖子 ID。
 * Find the post ID for redirecting an old date.
 *
 * @see wp_old_slug_redirect()
 *
 * @since 4.9.3
 * @access private
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param string $post_type The current post type based on the query vars.
 * @return int $id The Post ID.
 */
function _find_post_by_old_date( $post_type ) {
	global $wpdb;

	$date_query = '';
	if ( get_query_var( 'year' ) ) {
		$date_query .= $wpdb->prepare( " AND YEAR(pm_date.meta_value) = %d", get_query_var( 'year' ) );
	}
	if ( get_query_var( 'monthnum' ) ) {
		$date_query .= $wpdb->prepare( " AND MONTH(pm_date.meta_value) = %d", get_query_var( 'monthnum' ) );
	}
	if ( get_query_var( 'day' ) ) {
		$date_query .= $wpdb->prepare( " AND DAYOFMONTH(pm_date.meta_value) = %d", get_query_var( 'day' ) );
	}

	$id = 0;
	if ( $date_query ) {
		$id = (int) $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta AS pm_date, $wpdb->posts WHERE ID = post_id AND post_type = %s AND meta_key = '_wp_old_date' AND post_name = %s" . $date_query, $post_type, get_query_var( 'name' ) ) );

		if ( ! $id ) {
			// Check to see if an old slug matches the old date
			$id = (int) $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts, $wpdb->postmeta AS pm_slug, $wpdb->postmeta AS pm_date WHERE ID = pm_slug.post_id AND ID = pm_date.post_id AND post_type = %s AND pm_slug.meta_key = '_wp_old_slug' AND pm_slug.meta_value = %s AND pm_date.meta_key = '_wp_old_date'" . $date_query, $post_type, get_query_var( 'name' ) ) );
		}
	}

	return $id;
}

/**
 * 建立全局帖子数据。
 * Set up global post data.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability to pass a post ID to `$post`.
 *
 * @global WP_Query $wp_query Global WP_Query instance.
 *
 * @param WP_Post|object|int $post WP_Post instance or Post ID/object.
 * @return bool True when finished.
 */
function setup_postdata( $post ) {
	global $wp_query;

	if ( ! empty( $wp_query ) && $wp_query instanceof WP_Query ) {
		return $wp_query->setup_postdata( $post );
	}

	return false;
}
