<?php
/**
 * 适用于HTTP报头的不区分大小写的字典
 * Case-insensitive dictionary, suitable for HTTP headers
 *
 * @package Requests
 * @subpackage Utilities
 */

/**
 * 适用于HTTP报头的不区分大小写的字典
 * Case-insensitive dictionary, suitable for HTTP headers
 *
 * @package Requests
 * @subpackage Utilities
 */
class Requests_Utility_CaseInsensitiveDictionary implements ArrayAccess, IteratorAggregate {
	/**
     * 实际项目数据
	 * Actual item data
	 *
	 * @var array
	 */
	protected $data = array();

	/**
     * 创建一个不区分大小写的字典。
	 * Creates a case insensitive dictionary.
	 *
	 * @param array $data Dictionary/map to convert to case-insensitive
	 */
	public function __construct(array $data = array()) {
		foreach ($data as $key => $value) {
			$this->offsetSet($key, $value);
		}
	}

	/**
     * 检查给定项目是否存在
	 * Check if the given item exists
	 *
	 * @param string $key Item key
	 * @return boolean Does the item exist?
	 */
	public function offsetExists($key) {
		$key = strtolower($key);
		return isset($this->data[$key]);
	}

	/**
     * 获取项目的值
	 * Get the value for the item
	 *
	 * @param string $key Item key
	 * @return string Item value
	 */
	public function offsetGet($key) {
		$key = strtolower($key);
		if (!isset($this->data[$key])) {
			return null;
		}

		return $this->data[$key];
	}

	/**
     * 设置给定项
	 * Set the given item
	 *
	 * @throws Requests_Exception On attempting to use dictionary as list (`invalidset`)
	 *
	 * @param string $key Item name
	 * @param string $value Item value
	 */
	public function offsetSet($key, $value) {
		if ($key === null) {
			throw new Requests_Exception('Object is a dictionary, not a list', 'invalidset');
		}

		$key = strtolower($key);
		$this->data[$key] = $value;
	}

	/**
     * 取消设置给定的头部
	 * Unset the given header
	 *
	 * @param string $key
	 */
	public function offsetUnset($key) {
		unset($this->data[strtolower($key)]);
	}

	/**
     * 获取数据的迭代器
	 * Get an iterator for the data
	 *
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator($this->data);
	}

	/**
     * 将标题作为数组
	 * Get the headers as an array
	 *
	 * @return array Header data
	 */
	public function getAll() {
		return $this->data;
	}
}
