<?php
/**
 * Authorize Service Controller สำหรับทำการ authenticate กับระบบ ECM จากภายนอก
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */
class AuthServiceController extends ECMController {
	/**
	 * action /check/ ทำการ authenticate กับระบบ Accept เฉพาะ SSL เท่านั้น
	 *
	 */
	public function checkAction() {
		//include_once 'Account.Entity.php';
		//include_once 'Concurrent.Entity.php';
		if (array_key_exists ( 'username', $_POST )) {
			$username = $_POST ['username'];
			$ip = $_POST ['ip'];
		} elseif (array_key_exists ( 'username', $_GET )) {
			$username = $_GET ['username'];
			$ip = $_GET ['ip'];
		} else {
			echo "<?xml version='1.0' ?><authorize><result>0</result></authorize>";
			die ();
		}
		
		$account = new AccountEntity ( );
		if (! $account->Load ( "f_login_name = '{$username}'" )) {
			echo "<?xml version='1.0' ?><authorize><result>0</result></authorize>";
			//die ();
		} else {
			$concurrent = new ConcurrentEntity ( );
			if (! $concurrent->Load ( "f_acc_id = '$account->f_acc_id' " )) {
			//if (! $concurrent->Load ( "f_acc_id = '$account->f_acc_id' and f_ip_address = '$ip'" )) {
				echo "<?xml version='1.0' ?><authorize><user>$username</user><ipaddress></ipaddress><result>0</result></authorize>";
				//die ();
			} else {
				echo "<?xml version='1.0' ?><authorize><user>$username</user><ipaddress>$ip</ipaddress><result>1</result></authorize>";
				//die ();
			}
		}
	}
}
