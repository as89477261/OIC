<?php
/**
 * Class ºÑ¹·Ö¡ Authenticate Log
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Logger Class
 */
class AuthLog {
	/**
	 * Class Constructor ·Ó¡ÒÃµÃÇ¨ÊÍº Sequence authLogID
	 *
	 */
	public function __construct() {
		global $sequence;
		if(!$sequence->isExists('authLogID')) {
			$sequence->create('authLogID');
		}
	}
	
	/**
	 * ºÑ¹·Ö¡ Log ¡ÒÃ Login
	 *
	 * @param int $accID
	 * @param int $time
	 * @param int $result
	 */
	public function login($accID,$time,$result) {
		global $sequence;
		global $util;
		
		$logID = $sequence->get('authLogID');
		$authLog = new LogAuthenEntity();
		$authLog->f_auth_log_id = $logID;
		$authLog->f_acc_id = $accID;
		$authLog->f_action = 1;
		$authLog->f_archived = 0;
		$authLog->f_ip_address = $util->getIPAddress();
		$authLog->f_result = $result;  
		$authLog->f_timestamp = $time;
		try {
			$authLog->Save();
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
	
	/**
	 * ºÑ¹·Ö¡ Log ¡ÒÃ Logout
	 *
	 * @param int $accID
	 * @param int $time
	 */
	public function logout($accID,$time) {
		global $sequence;
		global $util;
		$logID = $sequence->get('authLogID');
		$authLog = new LogAuthenEntity();
		$authLog->f_auth_log_id = $logID;
		$authLog->f_acc_id = $accID;
		$authLog->f_action = 2;
		$authLog->f_archived = 0;
		$authLog->f_ip_address = $util->getIPAddress();
		$authLog->f_result = 1;  
		$authLog->f_timestamp = $time;
		try {
			$authLog->Save();
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
}

