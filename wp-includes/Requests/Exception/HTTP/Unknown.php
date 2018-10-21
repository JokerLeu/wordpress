<?php
/**
 * 未知状态响应异常
 * Exception for unknown status responses
 *
 * @package Requests
 */

/**
 * 未知状态响应异常 继承 基于HTTP响应的异常 继承 HTTP请求异常 继承 异常
 * Exception for unknown status responses
 *
 * @package Requests
 */
class Requests_Exception_HTTP_Unknown extends Requests_Exception_HTTP {
	/**
     * HTTP状态码
	 * HTTP status code
	 *
	 * @var integer|bool Code if available, false if an error occurred
	 */
	protected $code = 0;

	/**
     * 原因短语
	 * Reason phrase
	 *
	 * @var string
	 */
	protected $reason = 'Unknown';

	/**
     * 创建新异常
	 * Create a new exception
	 *
	 * If `$data` is an instance of {@see Requests_Response}, uses the status
	 * code from it. Otherwise, sets as 0
	 *
	 * @param string|null $reason Reason phrase
	 * @param mixed $data Associated data
	 */
	public function __construct($reason = null, $data = null) {
		if ($data instanceof Requests_Response) {
			$this->code = $data->status_code;
		}

		parent::__construct($reason, $data);
	}
}