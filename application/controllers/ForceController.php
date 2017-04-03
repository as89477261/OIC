<?php

/**
 * โปรแกรมสำหรับบังคับให้ทำงานอื่นๆ
 *
 */
class ForceController extends ECMController {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'default' );
	}
	
	/**
	 * action /change-pwd หน้าจอเปลี่ยนรหัสผ่าน
	 *
	 */
	public function changePwdAction() {
		//checkSessionPortlet();
		//echo "Force Change Password";
		//echo $this->ECMView->render ( 'forceChangePassword.phtml' );
		echo $this->ECMView->render ( 'changePasswordAD.php' );
	}
	
	/**
	 * action /post-pwd โปรแกรมเปลี่ยนรหัสผ่านของผู้ใช้งาน
	 *
	 */
	public function postPwdAction() {
		global $sessionMgr;
		global $util;
		if($sessionMgr->changePassword($_POST['passwordOldPwd'],$_POST['passwordPwd'])) {
			$util->redirect ( "/Index" );
		} else {
			$util->redirect ( "/force/change-pwd" );
		}
	}
	
	/**
	 * action /expired หน้าจอบัญชีรายชื่อหมดอายุ
	 *
	 */
	public function expiredAction() {
		echo $this->ECMView->render ( 'accountExpired.phtml' );
	}
}

