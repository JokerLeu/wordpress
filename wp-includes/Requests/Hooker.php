<?php
/**
 * 事件调度器
 * Event dispatcher
 *
 * @package Requests
 * @subpackage Utilities
 */

/**
 * 事件调度器
 * Event dispatcher
 *
 * @package Requests
 * @subpackage Utilities
 */
interface Requests_Hooker {
	/**
     * 注册一个钩子的回调函数。
	 * Register a callback for a hook
	 *
	 * @param string $hook Hook name
	 * @param callback $callback Function/method to call on event
	 * @param int $priority Priority number. <0 is executed earlier, >0 is executed later
	 */
	public function register($hook, $callback, $priority = 0);

	/**
     * 发送一个消息
	 * Dispatch a message
	 *
	 * @param string $hook Hook name
	 * @param array $parameters Parameters to pass to callbacks
	 * @return boolean Successfulness
	 */
	public function dispatch($hook, $parameters = array());
}