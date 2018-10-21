<?php
/**
 * HTTP请求异常
 * Exception for HTTP requests
 *
 * @package Requests
 */

/**
 * HTTP请求异常 继承 异常
 * Exception for HTTP requests
 *
 * @package Requests
 */
class Requests_Exception extends Exception {
	/**
     * 异常类型
	 * Type of exception
	 *
	 * @var string
	 */
	protected $type;

	/**
     * 与异常相关的数据。
	 * Data associated with the exception
	 *
	 * @var mixed
	 */
	protected $data;

	/**
     * 创建一个新的异常
	 * Create a new exception
	 *
	 * @param string $message Exception message
	 * @param string $type Exception type
	 * @param mixed $data Associated data
	 * @param integer $code Exception numerical code, if applicable
	 */
	public function __construct($message, $type, $data = null, $code = 0) {
		parent::__construct($message, $code);

		$this->type = $type;
		$this->data = $data;
	}

	/**
     * 类似{@see getCode()}，但是是一个字符串代码。
	 * Like {@see getCode()}, but a string code.
	 *
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
     * 给任何相关的数据
	 * Gives any relevant data
	 *
	 * @codeCoverageIgnore
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}
}