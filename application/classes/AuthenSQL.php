<?php
/**
 * Class ����Ѻ Authenticate �Ѻ Internal Database
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Authenticate Class
 */
class AuthenSQL extends AuthenBase implements IAuthen {
	/**
	 * authenticate ��� Implement ��� IAuthen Interface ����Ѻ Authenticate �Ѻ Internal Database
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $salt
	 * @return array of authentication result
	 */
	public function authenticate($username, $password, $salt = '') {
		global $config;
		global $util;
		
		$result = Array ();
		
		$concurrent = new ConcurrentEntity ( );
		
		$account = new AccountEntity ( );
		if (! $account->Load ( "f_login_name = '{$username}'" )) {
			$result ['success'] = false;
			$result ['param'] = 4;
			return $result;
		} else {
			if ($config ['useCHAPSLogin']) {
				$realAuthToken = md5 ( $salt . $account->f_login_password );
			} else {
				$realAuthToken = $account->f_login_password;
			}
			if ($password == $realAuthToken) {
				
				if (! $concurrent->Load ( "f_acc_id = '{$account->f_acc_id}'" )) {
					return $this->loginProcess ( $account->f_acc_id, $account->f_account_type, true );
				} else {
					if ((bool)$config['concurrentCfg']['strict']) {
						if ($util->getIPAddress () != $concurrent->f_ip_address) {
							$result ['success'] = false;
							$result ['param'] = 2;
							$result ['ip'] = $concurrent->f_ip_address;
							return $result;
						}
					} else {
						return $this->loginProcess ( $account->f_acc_id, $account->f_account_type, false );
					}
				}
			
			} else {
				$result ['success'] = false;
				$result ['param'] = 1;
				return $result;
			}
		}
	}
}
