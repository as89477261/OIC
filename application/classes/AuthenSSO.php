<?php
include_once 'IAuthen.php';
include_once 'AuthenBase.php';
/**
 * for SQL authen ECM will force to use CHAPS Login system
 *
 */
class AuthenSSO extends AuthenBase implements IAuthen {
	public function doSSO($a,$b) {
		//return "xxxxxxxx";
	}

	public function authenticate($username, $password, $salt = '') {
		global $config;
		global $util;
		
		$result = Array ();
		
		$concurrent = new ConcurrentEntity ( );
		
		$account = new AccountEntity ( );
		if (! $account->Load ( "f_login_name = '{$username}'" )) {
			die($username);
			$result ['success'] = false;
			$result ['param'] = 4;
			return $result;
		} else {

			if ($config ['useCHAPSLogin']) {
				$realAuthToken = md5 ( $salt . $account->f_login_password );
			} else {
				$realAuthToken = $account->f_login_password;
			}

			if (! $concurrent->Load ( "f_acc_id = '{$account->f_acc_id}'" )) {
					return $this->loginProcess ( $account->f_acc_id, $account->f_account_type,true );
			} else {
				if ($util->getIPAddress() == $concurrent->f_ip_address) {
					return $this->loginProcess ( $account->f_acc_id, $account->f_account_type,false );
				} else {
					$result ['success'] = false;
					$result ['param'] = 2;
					$result ['ip'] = $concurrent->f_ip_address;
					return $result;
				}
			}
			
		}
	}
}
