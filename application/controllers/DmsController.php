<?php

/**
 * Class DMSController
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DMS
 * 
 *
 */

class DMSController extends ECMController {
	/**
	 * action /create-cabinet/ ÊÃéÒ§µÙé
	 *
	 */
	public function createCabinetAction() {
		global $sequence;
		$cabinetParentType = $_POST ['DMSParentType'];
		if ($cabinetParentType == 'MyDocRoot') {
			$node = new DmsMyObjectEntity ( );
			if (! $sequence->isExists ( 'myDocID' )) {
				$sequence->create ( 'myDocID' );
			}
			$node->f_myobj_id = $sequence->get ( 'myDocID' );
			$node->f_myobj_pid = 0;
			$node->f_myobj_type = 1;
			$node->f_owner_id = $_SESSION ['accID'];
			$node->f_name = UTFDecode ( $_POST ['cabinetName'] );
			$node->f_description = UTFDecode ( $_POST ['cabinetDesc'] );
			$node->f_keyword = '';
			$node->f_location = '';
			$node->f_doc_id = 0;
			$node->f_created_stamp = time ();
			$node->f_mark_delete = 0;
			$node->f_delete = 0;
			$node->f_last_update_stamp = time ();
			$node->f_published = 0;
			$node->f_status = 1;
			$node->Save ();
		} else {

			$node = new DmsObjectEntity ( );
			if (! $sequence->isExists ( 'DMSID' )) {
				$sequence->create ( 'DMSID' );
			}
			
			$node->f_obj_id = $sequence->get ( 'DMSID' );
			$node->f_obj_pid = 0;
			$node->f_obj_type = 1;
			$node->f_name = UTFDecode ( $_POST ['cabinetName'] );
			$node->f_description = UTFDecode ( $_POST ['cabinetDesc'] );
			$node->f_keyword = '';
			$node->f_location = '';
			$node->f_doc_id = 0;
			$node->f_created_stamp = time ();
			$node->f_created_uid = $_SESSION ['accID'];
			$node->f_mark_delete = 0;
			$node->f_mark_delete_user = 0;
			$node->f_delete = 0;
			$node->f_delete_user = 0;
			$node->f_last_update_stamp = time ();
			$node->f_last_update_uid = $_SESSION ['accID'];
			$node->f_locked = 0;
			$node->f_password = '';
			$node->f_checkout = 0;
			$node->f_checkout_user = 0;
			$node->f_pulished = 0;
			$node->f_publish_user = 0;
			$node->f_owner_type = 0;
			$node->f_owner_id = 0;
			$node->f_override = 0;
			$node->f_borrowed = 0;
			$node->f_orphaned = 0;
			$node->f_status = 1;
			$node->Save ();
		}
	}
	
	/**
	 * action /create-drawer/ ÊÃéÒ§ÅÔé¹ªÑ¡
	 *
	 */
	public function createDrawerAction() {
		global $sequence;
		global $conn;
		
		$conn->debug = true;
		//$drawerParentType = $_POST['DMSParentType'];
		$drawerParentID = $_POST ['drawerParentID'];
		$objmode = $_POST ['drawerObjectMode'];
		if ($objmode == 'mydoc') {
			$node = new DmsMyObjectEntity ( );
			if (! $sequence->isExists ( 'myDocID' )) {
				$sequence->create ( 'myDocID' );
			}
			$node->f_myobj_id = $sequence->get ( 'myDocID' );
			$node->f_myobj_pid = $drawerParentID;
			$node->f_myobj_type = 2;
			$node->f_owner_id = $_SESSION ['accID'];
			$node->f_name = UTFDecode ( $_POST ['drawerName'] );
			$node->f_description = UTFDecode ( $_POST ['drawerDesc'] );
			$node->f_keyword = '';
			$node->f_location = '';
			$node->f_doc_id = 0;
			$node->f_created_stamp = time ();
			$node->f_mark_delete = 0;
			$node->f_delete = 0;
			$node->f_last_update_stamp = time ();
			$node->f_published = 0;
			$node->f_status = 1;
			$node->Save ();
		} else {

			$node = new DmsObjectEntity ( );
			if (! $sequence->isExists ( 'DMSID' )) {
				$sequence->create ( 'DMSID' );
			}
			
			$node->f_obj_id = $sequence->get ( 'DMSID' );
			$node->f_obj_pid = $drawerParentID;
			$node->f_obj_type = 2;
			$node->f_name = UTFDecode ( $_POST ['drawerName'] );
			$node->f_description = UTFDecode ( $_POST ['drawerDesc'] );
			$node->f_keyword = '';
			$node->f_location = '';
			$node->f_doc_id = 0;
			$node->f_created_stamp = time ();
			$node->f_created_uid = $_SESSION ['accID'];
			$node->f_mark_delete = 0;
			$node->f_mark_delete_user = 0;
			$node->f_delete = 0;
			$node->f_delete_user = 0;
			$node->f_last_update_stamp = time ();
			$node->f_last_update_uid = $_SESSION ['accID'];
			$node->f_locked = 0;
			$node->f_password = '';
			$node->f_checkout = 0;
			$node->f_checkout_user = 0;
			$node->f_pulished = 0;
			$node->f_publish_user = 0;
			$node->f_owner_type = 0;
			$node->f_owner_id = 0;
			$node->f_override = 0;
			$node->f_borrowed = 0;
			$node->f_orphaned = 0;
			$node->f_status = 1;
			$node->Save ();
		}
	}
	
	/**
	 * action /create-folder/ ÊÃéÒ§á¿éÁ
	 *
	 */
	public function createFolderAction() {
		global $sequence;
		global $util;
		global $sessionMgr;

		$folderParentID = $_POST ['folderParentID'];
		$objmode = $_POST ['folderObjectMode'];
		if ($objmode == 'mydoc') {
			Logger::debug ("x1");

			$node = new DmsObjectEntity ( );
			if (! $sequence->isExists ( 'DMSID' )) {
				$sequence->create ( 'DMSID' );
			}
			if ($folderParentID == 'MyDocRoot') {
				$folderParentID = 0;
			}
			
			$node->f_obj_id = $sequence->get ( 'DMSID' );
			$node->f_obj_pid = $folderParentID;
			$node->f_obj_lid = 0;
			$node->f_obj_type = 0;
			$node->f_obj_level = 0;
			$node->f_name = UTFDecode ( $_POST ['folderName'] );
			$node->f_description = UTFDecode ( $_POST ['folderDesc'] );
			$node->f_keyword = '';
			$node->f_location = '';
			$node->f_doc_id = 0;
			$node->f_created_stamp = time ();
			$node->f_created_uid = $_SESSION ['accID'];
			$node->f_mark_delete = 0;
			$node->f_mark_delete_user = 0;
			$node->f_delete = 0;
			$node->f_delete_user = 0;
			$node->f_last_update_stamp = time ();
			$node->f_last_update_uid = $_SESSION ['accID'];
			$node->f_locked = 0;
			$node->f_password = '';
			$node->f_checkout = 0;
			$node->f_checkout_user = 0;
			$node->f_pulished = 0;
			$node->f_publish_user = 0;
			$node->f_owner_type = 0;
			$node->f_owner_id = $_SESSION ['accID'];
			$node->f_override = 0;
			$node->f_borrowed = 0;
			$node->f_orphaned = 0;
			$node->f_status = 1;
			$node->f_is_expired = 0;
			$node->f_expire_stamp = 0;
			$node->f_in_mydoc = 1;
			$node->Save ();
		} else {
			$node = new DmsObjectEntity ( );
			if (! $sequence->isExists ( 'DMSID' )) {
				$sequence->create ( 'DMSID' );
			}
			
			$node->f_obj_id = $sequence->get ( 'DMSID' );
			$node->f_obj_pid = $folderParentID;
			$node->f_obj_lid = 0;
			$node->f_obj_type = 0;
			$node->f_obj_level = 0;
			$node->f_name = UTFDecode ( $_POST ['folderName'] );
			$node->f_description = UTFDecode ( $_POST ['folderDesc'] );
			$node->f_keyword = '';
			$node->f_location = '';
			$node->f_doc_id = 0;
			$node->f_created_stamp = time ();
			$node->f_created_uid = $_SESSION ['accID'];
			$node->f_mark_delete = 0;
			$node->f_mark_delete_user = 0;
			$node->f_delete = 0;
			$node->f_delete_user = 0;
			$node->f_last_update_stamp = time ();
			$node->f_last_update_uid = $_SESSION ['accID'];
			$node->f_locked = 0;
			$node->f_password = '';
			$node->f_checkout = 0;
			$node->f_checkout_user = 0;
			$node->f_pulished = 0;
			$node->f_publish_user = 0;
			$node->f_owner_type = 0;
			$node->f_owner_id = 0;
			$node->f_override = 0;
			$node->f_borrowed = 0;
			$node->f_orphaned = 0;
			$node->f_status = 1;
			$node->f_is_expired = 0;
			$node->f_expire_stamp = 0;
			$node->f_in_mydoc = 0;
			$node->Save ();
			
			if ( $folderParentID == 0 ) {
			
				$util = new ECMUtility ();
				$orgName = $util->getDefaultReceiveInternalOrgName ();
				$secureGroup = new SecureGroupEntity ();
				if ( !$secureGroup->Load(" f_secure_group_name = '{$orgName}' ") ) {

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
					$secureGroupName = $orgName;

					$secureMember = new SecureGroupMemberEntity ( );
					$secureMember->f_secure_member_id = $sequence->get ( 'secureGroupMember' );
					$secureMember->f_secure_id = $secureGroupID;
					$secureMember->f_member_type = 3;
					$secureMember->f_member_id = $sessionMgr->getCurrentOrgID ();
					$secureMember->f_deleted = 0;
					$secureMember->f_allow = 1;
					$secureMember->Save ();
					
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
					
					$secureProperty = new SecurityPropertyEntity ( );
					$secureProperty->f_uniq_id = $sequence->get ( 'securityProperty' );
					$secureProperty->f_obj_type = 0;
					$secureProperty->f_obj_id = $node->f_obj_id;
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
			
				} else {

					$secureProperty = new SecurityPropertyEntity ( );
					$secureProperty->f_uniq_id = $sequence->get ( 'securityProperty' );
					$secureProperty->f_obj_type = 0;
					$secureProperty->f_obj_id = $node->f_obj_id;
					$secureProperty->f_security_context_id = $secureGroup->f_secure_id;
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

				}
			}

		}
	}
	
	/**
	 * action /create-shortcut/ ÊÃéÒ§ shortcut
	 *
	 */
	public function createShortcutAction() {
		global $sequence;
		global $lang;
		
		//$drawerParentType = $_POST['DMSParentType'];
		$folderParentID = $_POST ['folderParentID'];
		$objmode = $_POST ['folderObjectMode'];
		if ($objmode == 'mydoc') {
			$node = new DmsMyObjectEntity ( );
			if (! $sequence->isExists ( 'myDocID' )) {
				$sequence->create ( 'myDocID' );
			}
			$node->f_myobj_id = $sequence->get ( 'myDocID' );
			$node->f_myobj_pid = $folderParentID;
			$node->f_myobj_type = 0;
			$node->f_owner_id = $_SESSION ['accID'];
			$node->f_name =  $_POST ['folderName'];
			$node->f_description = UTFDecode ( $_POST ['folderDesc'] );
			$node->f_keyword = UTFDecode ( $_POST ['folderKeyword'] );
			$node->f_location = '';
			$node->f_doc_id = 0;
			$node->f_created_stamp = time ();
			$node->f_mark_delete = 0;
			$node->f_delete = 0;
			$node->f_last_update_stamp = time ();
			$node->f_published = 0;
			$node->f_status = 1;
			$node->Save ();
		} else {

			$node = new DmsObjectEntity ( );
			if (! $sequence->isExists ( 'DMSID' )) {
				$sequence->create ( 'DMSID' );
			}
			
			try {
				Logger::dump('name',UTFDecode($_GET['shortcutName']));
				$node->f_obj_id = $sequence->get ( 'DMSID' );
				$node->f_obj_pid = 0;
				$node->f_obj_lid = $_COOKIE['contextDMSObjectID'];
				$node->f_obj_type = 2;
				$node->f_obj_level = 0;
				$node->f_name =  UTFDecode ( $_GET['shortcutName']); //$lang ['dms'] ['shortcut'] . $lang ['dms'] ['goto'] . 
				$node->f_description = //UTFDecode ( $_POST ['folderDesc'] );
				$node->f_keyword = '';
				$node->f_location = '';
				$node->f_doc_id = 0;
				$node->f_created_stamp = time ();
				$node->f_created_uid = $_SESSION ['accID'];
				$node->f_mark_delete = 0;
				$node->f_mark_delete_user = 0;
				$node->f_delete = 0;
				$node->f_delete_user = 0;
				$node->f_last_update_stamp = time ();
				$node->f_last_update_uid = $_SESSION ['accID'];
				$node->f_locked = 0;
				$node->f_password = '';
				$node->f_checkout = 0;
				$node->f_checkout_user = 0;
				$node->f_pulished = 0;
				$node->f_publish_user = 0;
				$node->f_owner_type = 0;
				$node->f_owner_id = $_SESSION ['accID'];
				$node->f_override = 0;
				$node->f_borrowed = 0;
				$node->f_orphaned = 0;
				$node->f_status = 1;
				$node->f_is_expired = 0;
				$node->f_expire_stamp = 0;
				$node->f_in_mydoc = 1;
				$node->Save();
				$result = array('success' => 1);
			} catch (Exception $e) {
				$result = array('success' => 0, 'message' => $e->getMessage());
			}
		}
		echo json_encode ( $result );
	}
	
	/**
	 * action /edit-folder/ á¡éä¢á¿éÁ
	 *
	 */
	public function editFolderAction() {
		
//		print_r ( $_POST );
		
		if ($_POST ['editFolderObjectMode'] == 'mydoc') {
			/*			
			 * include_once ('DmsMyObject.Entity.php');
			$node = new DmsMyObjectEntity ( );
			if (! $sequence->isExists ( 'myDocID' )) {
				$sequence->create ( 'myDocID' );
			}
			$node->f_myobj_id = $sequence->get ( 'myDocID' );
			$node->f_myobj_pid = $folderParentID;
			$node->f_myobj_type = 3;
			$node->f_owner_id = $_SESSION ['accID'];
			$node->f_name = UTFDecode( $_POST ['folderName'] );
			$node->f_description = UTFDecode( $_POST ['folderDesc'] );
			$node->f_keyword = '';
			$node->f_location = '';
			$node->f_doc_id = 0;
			$node->f_created_stamp = time ();
			$node->f_mark_delete = 0;
			$node->f_delete = 0;
			$node->f_last_update_stamp = time ();
			$node->f_published = 0;
			$node->f_status = 1;
			$node->Save ();*/
		} else {
			
			$node = new DmsObjectEntity ( );
			
			if ($node->Load ( "f_obj_id='{$_POST ['editFolderID']}'" )) {
				$node->f_name = UTFDecode ( $_POST ['editFolderName'] );
				$node->f_description = UTFDecode ( $_POST ['editFolderDesc'] );
				$node->f_keyword = UTFDecode( $_POST ['editFolderKeyword'] );
				$node->f_last_update_stamp = time ();
				$node->f_last_update_uid = $_SESSION ['accID'];
				
				try {
					$node->Save ();
				} catch ( exceptions $e ) {
					echo $e->getMessage ();
				}
			}
		}
	}
}
