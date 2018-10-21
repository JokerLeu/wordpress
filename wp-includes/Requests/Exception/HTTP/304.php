<?php
/**
 * 304未修改响应异常
 * Exception for 304 Not Modified responses
 *
 * @package Requests
 */

/**
 * 304未修改响应异常 继承 基于HTTP响应的异常 继承 HTTP请求异常 继承 异常
 * Exception for 304 Not Modified responses
 *
 * @package Requests
 */
class Requests_Exception_HTTP_304 extends Requests_Exception_HTTP {
	/**
     * HTTP状态码
	 * HTTP status code
	 *
	 * @var integer
	 */
	protected $code = 304;

	/**
     * 原因短语
	 * Reason phrase
	 *
	 * @var string
	 */
	// 未修改
	protected $reason = 'Not Modified';
}