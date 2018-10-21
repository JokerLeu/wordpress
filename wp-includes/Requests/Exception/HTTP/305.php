<?php
/**
 * 305使用代理响应异常
 * Exception for 305 Use Proxy responses
 *
 * @package Requests
 */

/**
 * 305使用代理响应异常 继承 基于HTTP响应的异常 继承 HTTP请求异常 继承 异常
 * Exception for 305 Use Proxy responses
 *
 * @package Requests
 */
class Requests_Exception_HTTP_305 extends Requests_Exception_HTTP {
	/**
     * HTTP状态码
	 * HTTP status code
	 *
	 * @var integer
	 */
	protected $code = 305;

	/**
     * 原因短语
	 * Reason phrase
	 *
	 * @var string
	 */
	// 使用代理
	protected $reason = 'Use Proxy';
}
