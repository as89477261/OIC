<?php
/**
 * Controller ��ǹ����ʴ� Access Denied Page
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 *
 */
class AccessDeniedController extends ECMController {
	/**
	 * test-method2 action
	 *
	 */
	public function testMethod2Action() {
		$front = Zend_Controller_Front::getInstance();
		$front->setParam('noViewRenderer',true);
		unset ($front);
		global $conn;
		$perf =& NewPerfMonitor($conn);
		$perf->UI(5);
	}
	
	/**
	 * initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'default' );
	}
	
	/**
	 * �ʴ� Access Denied Screen
	 *
	 */
	public function indexAction() {
		echo "Access Denied";
	}
	
	/**
	 * �ʴ� Access Denied Screen & redirect ��Ҷ١��ͧ
	 *
	 */
	public function accessTimeAction() {
		global $util;
		global $sessionMgr;
		
		if ($sessionMgr->accessTimeValid ()) {
			$util->redirect ( '/Index' );
		}
		echo $this->ECMView->render ( 'accessTimeInvalid.phtml' );
	}
}
