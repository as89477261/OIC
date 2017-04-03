<?php
/**
 * Class สำหรับ Authenticate กับ LDAP/Active Directory
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Authenticate Class
 */
class AuthenLDAP extends AuthenBase implements IAuthen {
	/**
	 * Class Construction ทำการ Load ADLDap Library
	 *
	 */
	public function __construct() {
		parent::__construct();
		loadExternalLib('ADLib');
	}
	
	/**
	 * authenticate ที่ Implement ตาม IAuthen Interface สำหรับ Authenticate กับ LDAP/AD
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $salt
	 * @return array of authentication result
	 */
	public function authenticate($username, $password, $salt = '') {
		/**
		 * Salt will never used for LDAP/AD Login
		 */
		global $config;
		global $util;
		
		$result = Array ();
		
		if($salt == '') {
			unset($salt);
		}
		
		$concurrent = new ConcurrentEntity ( );
		
		$account = new AccountEntity ( );
		if (! $account->Load ( "f_login_name = '{$username}'" )) {
			$result ['success'] = false;
			$result ['param'] = 4;
			return $result;
		} else {
			$adldap = new adLDAP ( $config ['LDAPoptions'] );
			
			if ($adldap->authenticate ( $username, $password )) {
				if (! $concurrent->Load ( "f_acc_id = '{$account->f_acc_id}'" )) {
					return $this->loginProcess ( $account->f_acc_id,$account->f_account_type, true );
				} else {
					if ($config ['concurrentCfg'] ['strict']) {
						if ($util->getIPAddress () != $concurrent->f_ip_address) {
							$result ['success'] = false;
							$result ['param'] = 2;
							$result ['ip'] = $concurrent->f_ip_address;
							return $result;
						}
					}else {
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
