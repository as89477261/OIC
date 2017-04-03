<?php
/**
 * Notifier Class
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Utility Class
 */
class Notifier {
	/**
	 * ทำการ Parse Template ของข้อความ
	 *
	 * @param string $template
	 * @return string
	 */
	public function parseTemplate($template = 'default') {
		$message = '';
		switch ($template) {
			case 'default' :
			default :
				$message = '';
				break;
		}
		
		return $message;
	}
	
	/**
	 * ทำการ Notify หน่วยงาน
	 *
	 * @param int $id
	 * @param string $message
	 */
	public function notifyOrganize($id, $message) {
		global $util;
		//global $sessionMgr;
		
		//include_once 'Account.Entity.php';
		$sendXMPPNotify = true;
		$receivers = $util->getOrganizeMember ( $id );
		foreach ( $receivers as $receiver ) {
			$acc = new AccountEntity ( );
			$acc->Load ( "f_acc_id = '{$receiver}'" );
			if ($sendXMPPNotify) {
				$util->sendJabberNotifier ( $acc->f_login_name,$message );
			}
			unset($acc);
		}
	
	}
	
	/**
	 * ทำการแจ้งเตือนผ่าน SMS
	 *
	 * @param int $accID
	 * @param string $message
	 */
	public function sms($accID, $message) {
	
	}
	
	/**
	 * ทำการแจ้งเตือนผ่าน eMail
	 *
	 * @param int $accID
	 * @param string $message
	 */
	public function email($accID, $message) {
	
	}
	
	/**
	 * ทำการแจ้งเตือนผ่าน XMPP
	 *
	 * @param int $accID
	 * @param string $message
	 */
	public function xmpp($accID, $message) {
	
	}
}
