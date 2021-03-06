<?php
/**
 * 不区分大小写的字典，适用于HTTP头。
 * Case-insensitive dictionary, suitable for HTTP headers
 *
 * @package Requests
 */

/**
 * 不区分大小写的字典，适用于HTTP头。
 * Case-insensitive dictionary, suitable for HTTP headers
 *
 * @package Requests
 */
class Requests_Response_Headers extends Requests_Utility_CaseInsensitiveDictionary {
	/**
     * 获取给定的头部
	 * Get the given header
	 *
	 * Unlike {@see self::getValues()}, this returns a string. If there are
	 * multiple values, it concatenates them with a comma as per RFC2616.
	 *
	 * Avoid using this where commas may be used unquoted in values, such as
	 * Set-Cookie headers.
	 *
	 * @param string $key
	 * @return string Header value
	 */
	public function offsetGet($key) {
		$key = strtolower($key);
		if (!isset($this->data[$key])) {
			return null;
		}

		return $this->flatten($this->data[$key]);
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

		if (!isset($this->data[$key])) {
			$this->data[$key] = array();
		}

		$this->data[$key][] = $value;
	}

	/**
     * 获取给定标题的所有值
	 * Get all values for a given header
	 *
	 * @param string $key
	 * @return array Header values
	 */
	public function getValues($key) {
		$key = strtolower($key);
		if (!isset($this->data[$key])) {
			return null;
		}

		return $this->data[$key];
	}

	/**
     * 将一个值变为字符串
	 * Flattens a value into a string
	 *
	 * Converts an array into a string by imploding values with a comma, as per
	 * RFC2616's rules for folding headers.
	 *
	 * @param string|array $value Value to flatten
	 * @return string Flattened value
	 */
	public function flatten($value) {
		if (is_array($value)) {
			$value = implode(',', $value);
		}

		return $value;
	}

	/**
     * 获取数据的迭代器
	 * Get an iterator for the data
	 *
	 * Converts the internal
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new Requests_Utility_FilteredIterator($this->data, array($this, 'flatten'));
	}
}
