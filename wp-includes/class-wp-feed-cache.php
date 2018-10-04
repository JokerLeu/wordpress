<?php
/**
 * 馈送API:WP_Feed_Cache类
 * Feed API: WP_Feed_Cache class
 *
 * @package WordPress
 * @subpackage Feed
 * @since 4.7.0
 */

/**
 * 核心类用于实现一个提要缓存。
 * Core class used to implement a feed cache.
 *
 * @since 2.8.0
 *
 * @see SimplePie_Cache
 */
class WP_Feed_Cache extends SimplePie_Cache {

	/**
     * 创建一个新的SimplePie_Cache对象。
	 * Creates a new SimplePie_Cache object.
	 *
	 * @since 2.8.0
	 *
	 * @param string $location  URL location (scheme is used to determine handler).
	 * @param string $filename  Unique identifier for cache object.
	 * @param string $extension 'spi' or 'spc'.
	 * @return WP_Feed_Cache_Transient Feed cache handler object that uses transients.
	 */
	public function create($location, $filename, $extension) {
		return new WP_Feed_Cache_Transient($location, $filename, $extension);
	}
}