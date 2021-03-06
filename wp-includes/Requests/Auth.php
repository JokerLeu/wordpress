<?php
/**
 * 身份验证提供者接口
 * Authentication provider interface
 *
 * @package Requests
 * @subpackage Authentication
 */

/**
 * 身份验证提供者接口
 * Authentication provider interface
 *
 * 实现此接口以充当身份验证提供程序。
 * Implement this interface to act as an authentication provider.
 *
 * Parameters should be passed via the constructor where possible, as this
 * makes it much easier for users to use your provider.
 *
 * @see Requests_Hooks
 * @package Requests
 * @subpackage Authentication
 */
interface Requests_Auth {
	/**
     * 根据需要注册钩子
	 * Register hooks as needed
	 *
	 * This method is called in {@see Requests::request} when the user has set
	 * an instance as the 'auth' option. Use this callback to register all the
	 * hooks you'll need.
	 *
	 * @see Requests_Hooks::register
	 * @param Requests_Hooks $hooks Hook system
	 */
	public function register(Requests_Hooks &$hooks);
}