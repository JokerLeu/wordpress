<?php
/**
 * 处理添加和分派事件。
 * Handles adding and dispatching events
 *
 * @package Requests
 * @subpackage Utilities
 */

/**
 * 处理添加和分派事件。
 * Handles adding and dispatching events
 *
 * @package Requests
 * @subpackage Utilities
 */
class Requests_Hooks implements Requests_Hooker {
	/**
     * 每个钩子的注册回调。
	 * Registered callbacks for each hook
	 *
	 * @var array
	 */
	protected $hooks = array();

	/**
     * 构造器
	 * Constructor
	 */
	public function __construct() {
		// pass
	}

	/**
     * 注册一个钩子的回调函数。
	 * Register a callback for a hook
	 *
	 * @param string $hook Hook name
	 * @param callback $callback Function/method to call on event
	 * @param int $priority Priority number. <0 is executed earlier, >0 is executed later
	 */
	public function register($hook, $callback, $priority = 0) {
		if (!isset($this->hooks[$hook])) {
			$this->hooks[$hook] = array();
		}
		if (!isset($this->hooks[$hook][$priority])) {
			$this->hooks[$hook][$priority] = array();
		}

		$this->hooks[$hook][$priority][] = $callback;
	}

	/**
     * 发送一个消息
	 * Dispatch a message
	 *
	 * @param string $hook Hook name
	 * @param array $parameters Parameters to pass to callbacks
	 * @return boolean Successfulness
	 */
	public function dispatch($hook, $parameters = array()) {
		if (empty($this->hooks[$hook])) {
			return false;
		}

		foreach ($this->hooks[$hook] as $priority => $hooked) {
			foreach ($hooked as $callback) {
				call_user_func_array($callback, $parameters);
			}
		}

		return true;
	}
}