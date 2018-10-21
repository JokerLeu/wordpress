<?php
/**
 * 需要筛选值的数组的迭代器
 * Iterator for arrays requiring filtered values
 *
 * @package Requests
 * @subpackage Utilities
 */

/**
 * 需要筛选值的数组的迭代器
 * Iterator for arrays requiring filtered values
 *
 * @package Requests
 * @subpackage Utilities
 */
class Requests_Utility_FilteredIterator extends ArrayIterator {
	/**
     * 作为过滤器运行的回调
	 * Callback to run as a filter
	 *
	 * @var callable
	 */
	protected $callback;

	/**
     * 创建一个新的迭代器
	 * Create a new iterator
	 *
	 * @param array $data
	 * @param callable $callback Callback to be called on each value
	 */
	public function __construct($data, $callback) {
		parent::__construct($data);

		$this->callback = $callback;
	}

	/**
     * 过滤后获取当前项的值
	 * Get the current item's value after filtering
	 *
	 * @return string
	 */
	public function current() {
		$value = parent::current();
		$value = call_user_func($this->callback, $value);
		return $value;
	}
}
