<?php
/**
 * โปรแกรมแสดง Matching HR Data
 * @author Sarayut Anukool
 * @version 1.0.0
 * @package controller
 * @category Workflow
 *
 */

class MatchHrController extends ECMController {

	public function getRoleEcmAction($roleId) {
		global $conn;

		$sql = "select ecm_role_id from tbl_match_role where hr_role_id=" . $roleId;
		$role = $conn->Execute ( $sql );
		$tmp = $role->FetchRow();
		$ECMRole = $tmp['ECM_ROLE_ID'];
		
		return $ECMRole;
	}

	public function getAccountEcmAction($accountId) {
		global $conn;

		$sql = "select ecm_account_id from tbl_match_account where hr_account_id=" . $accountId;
		$rs = $conn->Execute ( $sql );
		$tmp = $rs->FetchRow();
		$ECMAccount = $tmp['ECM_ACCOUNT_ID'];
		
		return $ECMAccount;
	}

	public function getOrganizeEcmAction($orgId) {
		global $conn;

		$sql = "select ecm_oganize_id from tbl_match_oganize where hr_oganize_id=" . $orgId;
		$rs = $conn->Execute ( $sql );
		$tmp = $rs->FetchRow();
		$ECMOrganize = $tmp['ECM_ORGANIZE_ID'];
		
		return $ECMOrganize;
	}
}
?>