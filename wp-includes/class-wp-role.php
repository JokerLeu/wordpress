<?php
/**
 * User API: WP_Role class
 *
 * @package WordPress
 * @subpackage Users
 * @since 4.4.0
 */

/**
 * 用于扩展用户角色API的核心类。
 * Core class used to extend the user roles API.
 *
 * @since 2.0.0
 */
class WP_Role {
	/**
     * 角色名称。
	 * Role name.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $name;

	/**
     * 角色包含的能力列表。
	 * List of capabilities the role contains.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	public $capabilities;

	/**
     * 构造函数-设置对象属性。
	 * Constructor - Set up object properties.
	 *
	 * The list of capabilities, must have the key as the name of the capability
	 * and the value a boolean of whether it is granted to the role.
	 *
	 * @since 2.0.0
	 *
	 * @param string $role Role name.
	 * @param array $capabilities List of capabilities.
	 */
	public function __construct( $role, $capabilities ) {
		$this->name = $role;
		$this->capabilities = $capabilities;
	}

	/**
     * 分配角色一个能力。
	 * Assign role a capability.
	 *
	 * @since 2.0.0
	 *
	 * @param string $cap Capability name.
	 * @param bool $grant Whether role has capability privilege.
	 */
	public function add_cap( $cap, $grant = true ) {
		$this->capabilities[$cap] = $grant;
		wp_roles()->add_cap( $this->name, $cap, $grant );
	}

	/**
     * 从角色中移除能力。
	 * Removes a capability from a role.
	 *
	 * This is a container for WP_Roles::remove_cap() to remove the
	 * capability from the role. That is to say, that WP_Roles::remove_cap()
	 * implements the functionality, but it also makes sense to use this class,
	 * because you don't need to enter the role name.
	 *
	 * @since 2.0.0
	 *
	 * @param string $cap Capability name.
	 */
	public function remove_cap( $cap ) {
		unset( $this->capabilities[$cap] );
		wp_roles()->remove_cap( $this->name, $cap );
	}

	/**
     * 确定角色是否具有给定的能力。
	 * Determines whether the role has the given capability.
	 *
	 * The capabilities is passed through the {@see 'role_has_cap'} filter.
	 * The first parameter for the hook is the list of capabilities the class
	 * has assigned. The second parameter is the capability name to look for.
	 * The third and final parameter for the hook is the role name.
	 *
	 * @since 2.0.0
	 *
	 * @param string $cap Capability name.
	 * @return bool True if the role has the given capability. False otherwise.
	 */
	public function has_cap( $cap ) {
		/**
         * 能力的过滤器。
		 * Filters which capabilities a role has.
		 *
		 * @since 2.0.0
		 *
		 * @param array  $capabilities Array of role capabilities.
		 * @param string $cap          Capability name.
		 * @param string $name         Role name.
		 */
		$capabilities = apply_filters( 'role_has_cap', $this->capabilities, $cap, $this->name );

		if ( !empty( $capabilities[$cap] ) )
			return $capabilities[$cap];
		else
			return false;
	}

}
