<?php
/**
 * Base Class สำหรับการ Authenticate ในระบบ ECM
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Authenticate Class
 */
class AuthenBase {
	
	/**
	 * Class Constructor
	 *
	 */
	public function __construct() {
		//TODO: ยังไม่มีความต้องการ Impleme
	}
	
	/**
	 * กระบวนการในการ Login ทั้งหมด สำหรับ Authenticate Adapter ต่างๆ
	 *
	 * @param string $accID
	 * @param int $accType
	 * @param boolean $newConcurrent
	 * @return array of login process result
	 */
	function loginProcess($accID, $accType, $newConcurrent = true) {
		global $config;
		global $util;
		global $license;
		
		$passport = new PassportEntity ( );
		
		if (! $passport->Load ( "f_acc_id = '{$accID}' and f_default_role = '1'" )) {
			$result ['success'] = false;
			$result ['param'] = 3;
			return $result;
		} else {
			$util->createUserTemp ( $accID );
			$util->createScanTemp ( $accID );
			$degradeMode = false;
			if ($newConcurrent) {
				$concurrent = new ConcurrentEntity ( );
				$concurrent->f_acc_id = $accID;
				$concurrent->f_first_access = time ();
				$concurrent->f_last_access = time ();
				$concurrent->f_session_id = session_id ();
				$concurrent->f_ip_address = $util->getIPAddress ();
				if($license->canCreateNewConcurrent()) {
					$concurrent->f_degrade = 0;
					$degradeMode = false;
				} else {
					$concurrent->f_degrade = 1;
					$degradeMode = true;
				}
				$concurrent->Save ();
			} else {
				$concurrent = new ConcurrentEntity ( );
				$concurrent->Load ( "f_acc_id = '{$accID}'" );
				$concurrent->f_acc_id = $accID;
				$concurrent->f_first_access = time ();
				$concurrent->f_last_access = time ();
				$concurrent->f_session_id = session_id ();
				$concurrent->f_ip_address = $util->getIPAddress ();
				$concurrent->Update ();
			}
			
			/**
			 * Create Concurrent & Create session profile 
			 */
			
            $_SESSION ['accID'] = $concurrent->f_acc_id;
            $account = new AccountEntity();
            $account->Load("f_acc_id = '{$concurrent->f_acc_id}'");
            $_SESSION ['accountName'] = $account->f_login_name;
			$_SESSION ['benefitAutoLogin'] = false;
            if($config['integration']['benefit']) {
                $_SESSION ['benefitAutoLogin'] = true;
            }
			$_SESSION ['roleID'] = $passport->f_role_id;
			$_SESSION ['accType'] = $accType;
			$_SESSION ['loggedIn'] = true;
			$_SESSION ['workingYear'] = date ( 'Y' );
			if($degradeMode) {
				$_SESSION['degradeMode'] = 1;
			} else {
				$_SESSION['degradeMode'] = 0;
			}
			$result ['success'] = true;
			$result ['param'] = 0;
			return $result;
		}
	}
}
