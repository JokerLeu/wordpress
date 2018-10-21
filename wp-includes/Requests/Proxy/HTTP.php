<?php
/**
 * HTTP代理连接接口
 * HTTP Proxy connection interface
 *
 * @package Requests
 * @subpackage Proxy
 * @since 1.6
 */

/**
 * HTTP代理连接接口
 * HTTP Proxy connection interface
 *
 * 通过HTTP代理提供连接的处理程序
 * Provides a handler for connection via an HTTP proxy
 *
 * @package Requests
 * @subpackage Proxy
 * @since 1.6
 */
class Requests_Proxy_HTTP implements Requests_Proxy {
	/**
     * 代理主机和端口
	 * Proxy host and port
	 *
	 * Notation: "host:port" (eg 127.0.0.1:8080 or someproxy.com:3128)
	 *
	 * @var string
	 */
	public $proxy;

	/**
     * 用户名
	 * Username
	 *
	 * @var string
	 */
	public $user;

	/**
     * 密码
	 * Password
	 *
	 * @var string
	 */
	public $pass;

	/**
     * 我们需要认证吗？（已提供用户名和密码）
	 * Do we need to authenticate? (ie username & password have been provided)
	 *
	 * @var boolean
	 */
	public $use_authentication;

	/**
     * 构造函数
	 * Constructor
	 *
	 * @since 1.6
	 * @throws Requests_Exception On incorrect number of arguments (`authbasicbadargs`)
	 * @param array|null $args Array of user and password. Must have exactly two elements
	 */
	public function __construct($args = null) {
		if (is_string($args)) {
			$this->proxy = $args;
		}
		elseif (is_array($args)) {
			if (count($args) == 1) {
				list($this->proxy) = $args;
			}
			elseif (count($args) == 3) {
				list($this->proxy, $this->user, $this->pass) = $args;
				$this->use_authentication = true;
			}
			else {
				throw new Requests_Exception('Invalid number of arguments', 'proxyhttpbadargs');
			}
		}
	}

	/**
     * 注册必要的回调
	 * Register the necessary callbacks
	 *
	 * @since 1.6
	 * @see curl_before_send
	 * @see fsockopen_remote_socket
	 * @see fsockopen_remote_host_path
	 * @see fsockopen_header
	 * @param Requests_Hooks $hooks Hook system
	 */
	public function register(Requests_Hooks &$hooks) {
		$hooks->register('curl.before_send', array(&$this, 'curl_before_send'));

		$hooks->register('fsockopen.remote_socket', array(&$this, 'fsockopen_remote_socket'));
		$hooks->register('fsockopen.remote_host_path', array(&$this, 'fsockopen_remote_host_path'));
		if ($this->use_authentication) {
			$hooks->register('fsockopen.after_headers', array(&$this, 'fsockopen_header'));
		}
	}

	/**
     * 在发送数据之前设置cURL参数
	 * Set cURL parameters before the data is sent
	 *
	 * @since 1.6
	 * @param resource $handle cURL resource
	 */
	public function curl_before_send(&$handle) {
		curl_setopt($handle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		curl_setopt($handle, CURLOPT_PROXY, $this->proxy);

		if ($this->use_authentication) {
			curl_setopt($handle, CURLOPT_PROXYAUTH, CURLAUTH_ANY);
			curl_setopt($handle, CURLOPT_PROXYUSERPWD, $this->get_auth_string());
		}
	}

	/**
     * 打开套接字连接之前更改远程套接字信息
	 * Alter remote socket information before opening socket connection
	 *
	 * @since 1.6
	 * @param string $remote_socket Socket connection string
	 */
	public function fsockopen_remote_socket(&$remote_socket) {
		$remote_socket = $this->proxy;
	}

	/**
     * 在获取流数据之前更改远程路径
	 * Alter remote path before getting stream data
	 *
	 * @since 1.6
	 * @param string $path Path to send in HTTP request string ("GET ...")
	 * @param string $url Full URL we're requesting
	 */
	public function fsockopen_remote_host_path(&$path, $url) {
		$path = $url;
	}

	/**
     * 在发送之前向请求添加额外的头文件
	 * Add extra headers to the request before sending
	 *
	 * @since 1.6
	 * @param string $out HTTP header string
	 */
	public function fsockopen_header(&$out) {
		$out .= sprintf("Proxy-Authorization: Basic %s\r\n", base64_encode($this->get_auth_string()));
	}

	/**
     * 获取验证字符串（用户：密码）
	 * Get the authentication string (user:pass)
	 *
	 * @since 1.6
	 * @return string
	 */
	public function get_auth_string() {
		return $this->user . ':' . $this->pass;
	}
}