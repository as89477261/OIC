<?php
/**
 * Authenticator Interface
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Authenticate Class
 */
interface IAuthen {
	/**
	 * Interface Method สำหรับการ authenticate
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $salt
	 */
	function authenticate($username,$password,$salt='');
}
