<?php
/**
 * โปรแกรมจัดการสิทธิ์เอกสาร
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Security
 *
 */
class SecureController extends ECMController {
	/**
	 * action /create-group สร้าง Secure Group
	 *
	 */
	public function createGroupAction() {
		global $sequence;
		global $sessionMgr;
		
		//include_once 'SecureGroup.Entity.php';
		//include_once 'SecureGroupMember.Entity.php';
		//include_once 'SecurityProperty.Entity.php';
		
		if (! $sequence->isExists ( 'securityProperty' )) {
			$sequence->create ( 'securityProperty' );
		}
		
		if (! $sequence->isExists ( 'secureGroup' )) {
			$sequence->create ( 'secureGroup' );
		}
		
		if (! $sequence->isExists ( 'secureGroupMember' )) {
			$sequence->create ( 'secureGroupMember' );
		}
		
		$secureGroupID = $sequence->get ( 'secureGroup' );
		$secureGroupName = UTFDecode ( $_POST ['groupName'] );
		$groupName = UTFDecode ( $_POST ['secureName'] );
		$groupID = $_POST ['secureID'];
		
		$secureIDArray = split ( ",", $groupID );
		$secureNameArray = split ( ",", $groupName );
		
		for($count = 0; $count < count ( $secureIDArray ); $count ++) {
			list ( $memberType, $memberID,$memberAllow ) = explode ( "_", $secureIDArray [$count] );
			$secureMember = new SecureGroupMemberEntity ( );
			$secureMember->f_secure_member_id = $sequence->get ( 'secureGroupMember' );
			$secureMember->f_secure_id = $secureGroupID;
			$secureMember->f_member_type = $memberType;
			$secureMember->f_member_id = $memberID;
			$secureMember->f_deleted = 0;
			$secureMember->f_allow = $memberAllow;
			$secureMember->Save ();
		}
		
		$secureGroup = new SecureGroupEntity ( );
		$secureGroup->f_secure_id = $secureGroupID;
		$secureGroup->f_secure_group_name = $secureGroupName;
		$secureGroup->f_inherit_flag = 0;
		$secureGroup->f_delete = 0;
		$secureGroup->f_delete_stamp = 0;
		$secureGroup->f_owner_id = $sessionMgr->getCurrentAccID ();
		$secureGroup->f_create_stamp = time ();
		$secureGroup->f_last_update_id = 0;
		$secureGroup->f_last_update_uid = $sessionMgr->getCurrentAccID ();
		$secureGroup->f_last_update_stamp = 0;
		$secureGroup->f_allow_global_use = 1;
		$secureGroup->Save ();
		
		//Logger::dump ( "post", $_POST );
		

		$secureProperty = new SecurityPropertyEntity ( );
		$secureProperty->f_uniq_id = $sequence->get ( 'securityProperty' );
		$secureProperty->f_obj_type = $_COOKIE ['contextDMSObjectType'];
		$secureProperty->f_obj_id = $_COOKIE ['contextDMSObjectID'];
		$secureProperty->f_security_context_id = $secureGroupID;
		$secureProperty->f_create_folder = 1;
		$secureProperty->f_modify_folder = 1;
		$secureProperty->f_delete_folder = 1;
		$secureProperty->f_create_doc = 1;
		$secureProperty->f_modify_doc = 1;
		$secureProperty->f_delete_doc = 1;
		$secureProperty->f_create_shortcut = 1;
		$secureProperty->f_modify_shortcut = 1;
		$secureProperty->f_delete_shortcut = 1;
		$secureProperty->f_move = 1;
		$secureProperty->f_share = 1;
		$secureProperty->f_export = 1;
		$secureProperty->f_grant = 1;
		$secureProperty->f_scan = 1;
		$secureProperty->f_attach = 1;
		$secureProperty->f_print = 1;
		$secureProperty->f_annotate = 1;
		$secureProperty->f_assign_stamp = time ();
		$secureProperty->f_assign_uid = $sessionMgr->getCurrentAccID ();
		$secureProperty->f_assign_role_id = $sessionMgr->getCurrentRoleID ();
		$secureProperty->f_assign_org_id = $sessionMgr->getCurrentOrgID ();
		$secureProperty->f_status = 1;
		$secureProperty->f_inherit = 1;
		$secureProperty->f_security_prop_type = 0;
		$secureProperty->Save ();
		
		$response = Array ( );
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	/**
	 * action /modify-group แก้ไข secure group
	 *
	 */
	public function modifyGroupAction() {
		global $conn;
		global $sequence;
		global $sessionMgr;
		
		//include_once 'SecureGroup.Entity.php';
		//include_once 'SecureGroupMember.Entity.php';
		//include_once 'SecurityProperty.Entity.php';
		
		if (! $sequence->isExists ( 'securityProperty' )) {
			$sequence->create ( 'securityProperty' );
		}
		
		if (! $sequence->isExists ( 'secureGroup' )) {
			$sequence->create ( 'secureGroup' );
		}
		
		if (! $sequence->isExists ( 'secureGroupMember' )) {
			$sequence->create ( 'secureGroupMember' );
		}
		
		$secureGroupID = $_POST['groupID'];
		$secureGroupName = UTFDecode ( $_POST ['groupName'] );
		
		$groupName = UTFDecode ( $_POST ['secureName'] );
		$groupID = $_POST ['secureID'];
		
		$sqlDropOldMember = "delete from tbl_secure_group_member where f_secure_id = '{$secureGroupID}'";
		$conn->Execute($sqlDropOldMember);
		
		$secureIDArray = split ( ",", $groupID );
		$secureNameArray = split ( ",", $groupName );
		
		for($count = 0; $count < count ( $secureIDArray ); $count ++) {
			list ( $memberType, $memberID,$memberAllow ) = explode ( "_", $secureIDArray [$count] );
			$secureMember = new SecureGroupMemberEntity ( );
			$secureMember->f_secure_member_id = $sequence->get ( 'secureGroupMember' );
			$secureMember->f_secure_id = $secureGroupID;
			$secureMember->f_member_type = $memberType;
			$secureMember->f_member_id = $memberID;
			$secureMember->f_deleted = 0;
			$secureMember->f_allow = $memberAllow;
			$secureMember->Save ();
		}
		

		$secureProperty = new SecurityPropertyEntity ( );
		$secureProperty->Load("f_security_context_id = '{$secureGroupID}'");
		$secureProperty->f_create_folder = 1;
		$secureProperty->f_modify_folder = 1;
		$secureProperty->f_delete_folder = 1;
		$secureProperty->f_create_doc = 1;
		$secureProperty->f_modify_doc = 1;
		$secureProperty->f_delete_doc = 1;
		$secureProperty->f_create_shortcut = 1;
		$secureProperty->f_modify_shortcut = 1;
		$secureProperty->f_delete_shortcut = 1;
		$secureProperty->f_move = 1;
		$secureProperty->f_share = 1;
		$secureProperty->f_export = 1;
		$secureProperty->f_grant = 1;
		$secureProperty->f_scan = 1;
		$secureProperty->f_attach = 1;
		$secureProperty->f_print = 1;
		$secureProperty->f_annotate = 1;
		$secureProperty->f_assign_stamp = time ();
		$secureProperty->f_assign_uid = $sessionMgr->getCurrentAccID ();
		$secureProperty->f_assign_role_id = $sessionMgr->getCurrentRoleID ();
		$secureProperty->f_assign_org_id = $sessionMgr->getCurrentOrgID ();
		$secureProperty->f_status = 1;
		$secureProperty->f_inherit = 1;
		$secureProperty->f_security_prop_type = 0;
		$secureProperty->Update ();
		
		$response = Array ( );
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	/**
	 * action /add-exists-group เพิ่มหน่วยงานที่มีอยู่แล้ว
	 *
	 */
	public function addExistsGroupAction() {
		global $sequence;
		global $sessionMgr;
		
		//include_once 'SecurityProperty.Entity.php';
		
		if (! $sequence->isExists ( 'securityProperty' )) {
			$sequence->create ( 'securityProperty' );
		}
		
		$secureGroupID = $_POST['sid'];
		
		$secureProperty = new SecurityPropertyEntity ( );
		$secureProperty->f_uniq_id = $sequence->get ( 'securityProperty' );
		$secureProperty->f_obj_type = $_COOKIE ['contextDMSObjectType'];
		$secureProperty->f_obj_id = $_COOKIE ['contextDMSObjectID'];
		$secureProperty->f_security_context_id = $secureGroupID;
		$secureProperty->f_create_folder = 1;
		$secureProperty->f_modify_folder = 1;
		$secureProperty->f_delete_folder = 1;
		$secureProperty->f_create_doc = 1;
		$secureProperty->f_modify_doc = 1;
		$secureProperty->f_delete_doc = 1;
		$secureProperty->f_create_shortcut = 1;
		$secureProperty->f_modify_shortcut = 1;
		$secureProperty->f_delete_shortcut = 1;
		$secureProperty->f_move = 1;
		$secureProperty->f_share = 1;
		$secureProperty->f_export = 1;
		$secureProperty->f_grant = 1;
		$secureProperty->f_scan = 1;
		$secureProperty->f_attach = 1;
		$secureProperty->f_print = 1;
		$secureProperty->f_annotate = 1;
		$secureProperty->f_assign_stamp = time ();
		$secureProperty->f_assign_uid = $sessionMgr->getCurrentAccID ();
		$secureProperty->f_assign_role_id = $sessionMgr->getCurrentRoleID ();
		$secureProperty->f_assign_org_id = $sessionMgr->getCurrentOrgID ();
		$secureProperty->f_status = 1;
		$secureProperty->f_inherit = 1;
		$secureProperty->f_security_prop_type = 0;
		$secureProperty->Save ();
		
		$response = Array ( );
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	/**
	 * action /update-property แก้ไข Property ของ Secure Group
	 *
	 */
	public function updatePropertyAction() {
		//include_once 'SecurityProperty.Entity.php';
		$data = split ( ",", $_POST ['data'] );
		foreach ( $data as $record ) {
			list ( $secureGroupID, $active, $inherit ) = explode ( "_", $record );
			
			$secureProperty = new SecurityPropertyEntity ( );
			$secureProperty->Load ( "f_security_context_id = '{$secureGroupID}'" );
			$secureProperty->f_status = $active;
			$secureProperty->f_inherit = $inherit;
			$secureProperty->Update ();
		}
		$response = Array ( );
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	/**
	 * action /drop-secure-mapping ลบ Secure Mapping
	 *
	 */
	public function dropSecureMappingAction() {
		global $conn;
		$id = $_POST['id'];
		$sid = $_POST['sid'];
		
		$sql = "delete from tbl_security_property where f_obj_id = '{$id}' and f_security_context_id = '{$sid}'";
		$rs = $conn->Execute($sql);
		$result = Array();
		$result['success'] = 1;
		echo json_encode($result);		
	}
	/**
	 * action /update-property-detail แก้ไขรายละเอียดของ Secure Property
	 *
	 */
	public function updatePropertyDetailAction() {
		//include_once 'SecurityProperty.Entity.php';
		$data = split ( ",", $_POST ['data'] );
		$oid = $_POST['oid'];
		$secureProperty = new SecurityPropertyEntity ( );
		list ( $secureGroupID, $id, $value ) = explode ( "_", $data [0] );
		$secureProperty->Load ( "f_security_context_id = '{$secureGroupID}' and f_obj_id = {$oid}" );
		foreach ( $data as $record ) {
			list ( $secureGroupID, $id, $value ) = explode ( "_", $record );
			
			if ($id == 1) {
				$secureProperty->f_create_folder = $value;
			}
			if ($id == 2) {
				$secureProperty->f_modify_folder = $value;
			}
			if ($id == 3) {
				$secureProperty->f_delete_folder = $value;
			}
			
			if ($id == 4) {
				$secureProperty->f_create_doc = $value;
			}
			if ($id == 5) {
				$secureProperty->f_modify_doc = $value;
			}
			if ($id == 6) {
				$secureProperty->f_delete_doc = $value;
			}
			
			if ($id == 7) {
				$secureProperty->f_create_shortcut = $value;
			}
			if ($id == 8) {
				$secureProperty->f_modify_shortcut = $value;
			}
			if ($id == 9) {
				$secureProperty->f_delete_shortcut = $value;
			}
			
			if ($id == 10) {
				$secureProperty->f_move = $value;
			}
			if ($id == 11) {
				$secureProperty->f_share = $value;
			}
			if ($id == 12) {
				$secureProperty->f_export = $value;
			}
			if ($id == 13) {
				$secureProperty->f_grant = $value;
			}
			if ($id == 14) {
				$secureProperty->f_scan = $value;
			}
			if ($id == 15) {
				$secureProperty->f_attach = $value;
			}
			if ($id == 16) {
				$secureProperty->f_print = $value;
			}
			if ($id == 17) {
				$secureProperty->f_annotate = $value;
			}
		}
		$secureProperty->Update ();
		$response = Array ( );
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
}
