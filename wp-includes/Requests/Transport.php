<?php
/**
 * 基本HTTP传输
 * Base HTTP transport
 *
 * @package Requests
 * @subpackage Transport
 */

/**
 * 基本HTTP传输
 * Base HTTP transport
 *
 * @package Requests
 * @subpackage Transport
 */
interface Requests_Transport {
	/**
     * 执行一个请求
	 * Perform a request
	 *
	 * @param string $url URL to request
	 * @param array $headers Associative array of request headers
	 * @param string|array $data Data to send either as the POST body, or as parameters in the URL for a GET/HEAD
	 * @param array $options Request options, see {@see Requests::response()} for documentation
	 * @return string Raw HTTP result
	 */
	public function request($url, $headers = array(), $data = array(), $options = array());

	/**
     * 同时发送多个请求
	 * Send multiple requests simultaneously
	 *
	 * @param array $requests Request data (array of 'url', 'headers', 'data', 'options') as per {@see Requests_Transport::request}
	 * @param array $options Global options, see {@see Requests::response()} for documentation
	 * @return array Array of Requests_Response objects (may contain Requests_Exception or string responses as well)
	 */
	public function request_multiple($requests, $options);

	/**
     * 自行测试是否可以使用传输工具。
	 * Self-test whether the transport can be used
	 * @return bool
	 */
	public static function test();
}