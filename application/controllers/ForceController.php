<?php

/**
 * ���������Ѻ�ѧ�Ѻ���ӧҹ����
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
	 * action /change-pwd ˹�Ҩ�����¹���ʼ�ҹ
	 *
	 */
	public function changePwdAction() {
		//checkSessionPortlet();
		//echo "Force Change Password";
		//echo $this->ECMView->render ( 'forceChangePassword.phtml' );
		echo $this->ECMView->render ( 'changePasswordAD.php' );
	}
	
	/**
	 * action /post-pwd ���������¹���ʼ�ҹ�ͧ�����ҹ
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
	 * action /expired ˹�Ҩͺѭ����ª����������
	 *
	 */
	public function expiredAction() {
		echo $this->ECMView->render ( 'accountExpired.phtml' );
	}
}

