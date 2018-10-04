<?php
/**
 * HTTP响应类
 * HTTP response class
 *
 * Contains a response from Requests::request()
 * @package Requests
 */

/**
 * HTTP响应类
 * HTTP response class
 *
 * Contains a response from Requests::request()
 * @package Requests
 */
class Requests_Response {
	/**
     * 构造器
	 * Constructor
	 */
	public function __construct() {
		$this->headers = new Requests_Response_Headers();
		$this->cookies = new Requests_Cookie_Jar();
	}

	/**
     * 应答体
	 * Response body
	 *
	 * @var string
	 */
	public $body = '';

	/**
     * 来自传输的原始HTTP数据。
	 * Raw HTTP data from the transport
	 *
	 * @var string
	 */
	public $raw = '';

	/**
     * 头，作为一个关联数组。
	 * Headers, as an associative array
	 *
	 * @var Requests_Response_Headers Array-like object representing headers
	 */
	public $headers = array();

	/**
     * 状态码，如果非阻塞则为假。
	 * Status code, false if non-blocking
	 *
	 * @var integer|boolean
	 */
	public $status_code = false;

	/**
     * 协议版本，如果非阻塞则为false。
	 * Protocol version, false if non-blocking
	 * @var float|boolean
	 */
	public $protocol_version = false;

	/**
     * 请求是否成功。
	 * Whether the request succeeded or not
	 *
	 * @var boolean
	 */
	public $success = false;

	/**
     * 使用的重定向数量。
	 * Number of redirects the request used
	 *
	 * @var integer
	 */
	public $redirects = 0;

	/**
     * URL请求
	 * URL requested
	 *
	 * @var string
	 */
	public $url = '';

	/**
     * 先前的请求(重定向)
	 * Previous requests (from redirects)
	 *
	 * @var array Array of Requests_Response objects
	 */
	public $history = array();

	/**
	 * Cookies from the request
	 *
	 * @var Requests_Cookie_Jar Array-like object representing a cookie jar
	 */
	public $cookies = array();

	/**
     * 响应是重定向吗?
	 * Is the response a redirect?
	 *
	 * @return boolean True if redirect (3xx status), false if not.
	 */
	public function is_redirect() {
		$code = $this->status_code;
		return in_array($code, array(300, 301, 302, 303, 307)) || $code > 307 && $code < 400;
	}

	/**
     * 如果请求不成功抛出异常。
	 * Throws an exception if the request was not successful
	 *
	 * @throws Requests_Exception If `$allow_redirects` is false, and code is 3xx (`response.no_redirects`)
	 * @throws Requests_Exception_HTTP On non-successful status code. Exception class corresponds to code (e.g. {@see Requests_Exception_HTTP_404})
	 * @param boolean $allow_redirects Set to false to throw on a 3xx as well
	 */
	public function throw_for_status($allow_redirects = true) {
		if ($this->is_redirect()) {
			if (!$allow_redirects) {
				throw new Requests_Exception('Redirection not allowed', 'response.no_redirects', $this);
			}
		}
		elseif (!$this->success) {
			$exception = Requests_Exception_HTTP::get_class($this->status_code);
			throw new $exception(null, $this);
		}
	}
}
