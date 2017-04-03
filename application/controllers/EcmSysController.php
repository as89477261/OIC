<?php
/**
 * โปรแกรมจัดการส่วนระบบ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 */
class EcmSysController extends ECMController {
	/**
	 * ทำการเปลี่ยนตำแหน่ง
	 *
	 */
	function changeRoleAction() {
		global $cache;
		global $conn;
		global $sessionMgr;
		$accID = $sessionMgr->getCurrentAccID ();
		$cache->remove ( 'console_' . $accID );
		$newRoleID = $_POST ['changeRoleID'];
		$sqlClearDefaultRole = "update tbl_passport set f_default_role = 0 where f_acc_id = '$accID'";
		$sqlSetDefaultRole = "update tbl_passport set f_default_role = 1 where f_acc_id = '$accID' and f_role_id = '$newRoleID'";
		
		$conn->Execute ( $sqlClearDefaultRole );
		$conn->Execute ( $sqlSetDefaultRole );
		
		$_SESSION ['accID'] = $accID;
		$_SESSION ['roleID'] = $newRoleID;
		$_SESSION ['loggedIn'] = true;
		$_SESSION ['workingYear'] = $sessionMgr->getCurrentYear();
		
		$response = Array ();
		$response ['success'] = 1;
		
		echo json_encode ( $response );
	}
	
	function changeYearAction() {
		global $cache;
		global $conn;
		global $sessionMgr;
		global $util;
		$_SESSION ['workingYear'] = date('Y',$util->dateToStamp(UTFDecode($_POST['txtChangeYear'])));
		/*
		$accID = $sessionMgr->getCurrentAccID ();
		$cache->remove ( 'console_' . $accID );
		$newRoleID = $_POST ['changeRoleID'];
		$sqlClearDefaultRole = "update tbl_passport set f_default_role = 0 where f_acc_id = '$accID'";
		$sqlSetDefaultRole = "update tbl_passport set f_default_role = 1 where f_acc_id = '$accID' and f_role_id = '$newRoleID'";
		
		$conn->Execute ( $sqlClearDefaultRole );
		$conn->Execute ( $sqlSetDefaultRole );
		
		$_SESSION ['accID'] = $accID;
		$_SESSION ['roleID'] = $newRoleID;
		$_SESSION ['loggedIn'] = true;
		$_SESSION ['workingYear'] = date ( 'Y' );
		*/
		$response = Array ();
		$response ['success'] = 1;
		
		echo json_encode ( $response );
	}
	
	/**
	 * ทำการเปลี่ยนรหัสผ่าน
	 *
	 */
	function changePasswordAction() {
		global $config;
		global $conn;
		global $util;
		
		$uid = $_POST ['changePwdAccID'];
		$salt = $_POST ['changePwdSalt'];
		$oldPassword = $_POST ['oldPassword'];
		$newPassword = $_POST ['newPassword'];
		
		//include_once 'Account.Entity.php';
		$account = new AccountEntity ( );
		$response = Array ();
		if (! $account->Load ( "f_acc_id = '{$uid}'" )) {
			$response ['success'] = 0;
			unset ( $account );
		} else {
			if ($account->f_ldap_bind == 1) {
				$response ['success'] = 0;
			} else {
				if ($oldPassword != md5 ( $salt . $account->f_login_password )) {
					$response ['success'] = 0;
				} else {
					$account->f_login_password = $newPassword;
					$account->f_last_change_pwd = $util->getTimestamp();
					$account->f_force_change_pwd = 0;
					$account->Update ();
					$response ['success'] = 1;
				}
			}
		}
		
		echo json_encode ( $response );
	}
}
