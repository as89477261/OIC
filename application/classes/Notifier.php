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
	 * �ӡ�� Parse Template �ͧ��ͤ���
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
	 * �ӡ�� Notify ˹��§ҹ
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
	 * �ӡ������͹��ҹ SMS
	 *
	 * @param int $accID
	 * @param string $message
	 */
	public function sms($accID, $message) {
	
	}
	
	/**
	 * �ӡ������͹��ҹ eMail
	 *
	 * @param int $accID
	 * @param string $message
	 */
	public function email($accID, $message) {
	
	}
	
	/**
	 * �ӡ������͹��ҹ XMPP
	 *
	 * @param int $accID
	 * @param string $message
	 */
	public function xmpp($accID, $message) {
	
	}
}
