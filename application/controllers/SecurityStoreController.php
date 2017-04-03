<?php
/**
 * โปรแกรมจัดการข้อมูล Security
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Security
 */
class SecurityStoreController extends ECMController {
	/**
	 * action /get-secure-group ส่งข้อมูล secure group
	 *
	 */
	public function getSecureGroupAction() {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		/*$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];*/
		
		//include_once 'SecurityProperty.Entity.php';
		//include_once 'SecureGroup.Entity.php';
		$securityProperty = new SecurityPropertyEntity ( );
		$trackItemArray = Array ();
		$id = $_COOKIE ['contextDMSObjectID'];
		$securityProperties = $securityProperty->Find ( "f_obj_id = '{$id}'" );
		$count = 0;
		foreach ( $securityProperties as $property ) {
			$count ++;
			//var_dump($property);
			$secureGroup = new SecureGroupEntity ( );
			$secureGroup->Load ( "f_secure_id = '{$property->f_security_context_id}'" );
			$tmpProperty = Array ();
			$tmpProperty ['id'] = $secureGroup->f_secure_id;
			$tmpProperty ['name'] = UTFEncode ( $secureGroup->f_secure_group_name );
			$tmpProperty ['active'] = $property->f_status;
			$tmpProperty ['inherit'] = $property->f_inherit;
			$trackItemArray [] = $tmpProperty;
		}
		$data = json_encode ( $trackItemArray );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","securegroup":' . $data . '})';
	}
	
	/**
	 * action /get-secure-member ส่งข้อมูล Secure Member
	 *
	 */
	public function getSecureMemberAction() {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Organize.Entity.php';
		//include_once 'SecureGroupMember.Entity.php';
		$secureMember = new SecureGroupMemberEntity ( );
		$id = $_GET ['id'];
		$secureMembers = $secureMember->Find ( "f_secure_id = '$id'" );
		$count = 0;
		$trackItemArray = Array ();
		foreach ( $secureMembers as $secureMemberObj ) {
			$count ++;
			$tmpMember = Array ();
			$tmpMember ['dataid'] = $secureMemberObj->f_member_id;
			switch ($secureMemberObj->f_member_type) {
				case 1 :
					$account = new AccountEntity ( );
					$account->Load ( "f_acc_id = '{$secureMemberObj->f_member_id}'" );
					$tmpMember ['name'] = UTFEncode ( $account->f_name . " " . $account->f_last_name );
					$tmpMember ['description'] = UTFEncode ( 'บุคคล' );
					break;
				case 2 :
					$role = new RoleEntity ( );
					$role->Load ( "f_role_id = '{$secureMemberObj->f_member_id}'" );
					$tmpMember ['name'] = UTFEncode ( $role->f_role_name );
					$tmpMember ['description'] = UTFEncode ( 'ตำแหน่ง' );
					
					break;
				case 3 :
					$org = new OrganizeEntity ( );
					$org->Load ( "f_org_id = '{$secureMemberObj->f_member_id}'" );
					$tmpMember ['name'] = UTFEncode ( $org->f_org_name );
					$tmpMember ['description'] = UTFEncode ( 'หน่วยงาน/กลุ่ม' );
					break;
			}
			
			$tmpMember ['typeid'] = $secureMemberObj->f_member_type;
			$tmpMember ['allow'] = $secureMemberObj->f_allow;
			$trackItemArray [] = $tmpMember;
		}
		$data = json_encode ( $trackItemArray );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","securemember":' . $data . '})';
	}
	
	/**
	 * action /get-secure-property ส่งข้อมูล Property ของ Secure Group
	 *
	 */
	public function getSecurePropertyAction() {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		/*$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];*/
		
		$sid = $_GET ['sid'];
		$oid = $_GET ['oid'];
		if ($sid != 0) {
			//include_once 'SecurityProperty.Entity.php';
			$securityProperty = new SecurityPropertyEntity ( );
			$securityProperty->Load ( "f_obj_id = '{$oid}' and f_security_context_id = '{$sid}'" );
			$trackItemArray = Array ();
			//$id = $_COOKIE['contextDMSObjectID'];
			//$securityProperties = $securityProperty->Find("f_obj_id = '{$id}'");
			$arrayTmp = Array ('id' => "{$sid}_1", 'name' => 'Create Folder', 'value' => ($securityProperty->f_create_folder==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_2", 'name' => 'Modify Folder', 'value' => ($securityProperty->f_modify_folder==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_3", 'name' => 'Delete Folder', 'value' => ($securityProperty->f_delete_folder==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			
			$arrayTmp = Array ('id' => "{$sid}_4", 'name' => 'Create Document', 'value' => ($securityProperty->f_create_doc==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_5", 'name' => 'Modify Document', 'value' => ($securityProperty->f_modify_doc==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_6", 'name' => 'Delete Document', 'value' => ($securityProperty->f_delete_doc==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			
			$arrayTmp = Array ('id' => "{$sid}_7", 'name' => 'Create Shortcut', 'value' => ($securityProperty->f_create_shortcut==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_8", 'name' => 'Modify Shortcut', 'value' => ($securityProperty->f_modify_shortcut==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_9", 'name' => 'Delete Shortcut', 'value' => ($securityProperty->f_delete_shortcut==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			
			$arrayTmp = Array ('id' => "{$sid}_10", 'name' => 'Move', 'value' => ($securityProperty->f_move==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			//$arrayTmp = Array ('id' => "{$sid}_11", 'name' => 'Share', 'value' => $securityProperty->f_share );
			//$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_12", 'name' => 'Export', 'value' => ($securityProperty->f_export==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_13", 'name' => 'Grant', 'value' => ($securityProperty->f_grant==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_14", 'name' => 'Scan', 'value' => ($securityProperty->f_scan==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_15", 'name' => 'Attach', 'value' => ($securityProperty->f_attach==1)?true: false );
			$trackItemArray [] = $arrayTmp;
			$arrayTmp = Array ('id' => "{$sid}_16", 'name' => 'Print', 'value' => ($securityProperty->f_print==1)?true: false );
			//$trackItemArray [] = $arrayTmp;
			//$arrayTmp = Array ('id' => "{$sid}_17", 'name' => 'Make Annotation', 'value' => $securityProperty->f_annotate );
			$trackItemArray [] = $arrayTmp;
			$count = count ( $trackItemArray );
			$data = json_encode ( $trackItemArray );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","secureproperty":' . $data . '})';
		
		} else {
			$trackItemArray = Array ();
			$count = count ( $trackItemArray );
			$data = json_encode ( $trackItemArray );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","secureproperty":' . $data . '})';
		}
	}
}
