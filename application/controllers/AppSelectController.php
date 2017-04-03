<?php
/**
 * โปรแกรมเลือก Application ใช้สำหรับเลือกเป็น Alternative Default Console
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */
class AppSelectController extends ECMController {
	/**
	 * initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'default' );
	}
	
	/**
	 * action /index/ แสดงหน้าจอเลือก Application
	 *
	 */
	public function indexAction() {
		global $util;
		global $sessionMgr;
		
		if(!$sessionMgr->isLoggedOn()) {
			$util->redirect("/Login");
			//$this->_redirector->gotoUrl ( '/Login' );
		}
		//include_once 'Account.Entity.php';	
		//checkSessionPortlet();
		
		$loginName = $sessionMgr->getCurrentAccID ();
		$account = new AccountEntity ( );
		$account->Load ( "f_acc_id = '{$loginName}'" );
		
		$this->ECMView->assign ( 'accID', $sessionMgr->getCurrentAccID () );
		$this->ECMView->assign ( 'loginName', $account->f_login_name );
		$output = $this->ECMView->render ( 'appSelect.phtml' );
		
		echo $output;
	}

}
