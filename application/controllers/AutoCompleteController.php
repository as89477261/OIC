<?php

/**
 * Controller สำหรับทำ Autocomplete สำหรับ Store
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */

class AutoCompleteController extends Zend_Controller_Action {
	/**
	 * action /announce-sub-type/ ข้อมูลประเภทย่อยของคำสั่งประกาศ
	 *
	 */
	public function announceSubTypeAction() {
		global $config;
		global $conn;
		//global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if (array_key_exists ( 'edt', $_COOKIE )) {
			$type = $_COOKIE ['edt'];
		} else {
			$type = 0;
		}
		
		$noQueryMode = true;
		if ($query != '' || $noQueryMode) {
			$sqlGetContactList = "select * from tbl_announce_category where f_name like '%{$query}%' and f_announce_type = '{$type}' order by f_announce_type";
			
			//echo $sqlGetContactList;
			$rsGetContactList = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetContactList );
			$resultSet = Array ();
			foreach ( $rsGetContactList as $row ) {
				checkKeyCase ( $row );
				switch ($type) {
					case 0 :
						$desc = 'คำสั่ง';
						break;
					case 1 :
						$desc = 'ระเบียบ';
						break;
					case 2 :
						$desc = 'ข้อบังคับ';
						break;
					default :
					case 3 :
						$desc = 'อื่นๆ';
						break;
				
				}
				
				$resultSet [] = Array ('id' => $row ['f_announce_cat_id'], 'name' => UTFEncode ( $row ['f_name'] ), 'desctype' => UTFEncode ( $desc ) );
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /secure-group/ ข้อมูล security group ของ DMS Object
	 *
	 */
	public function secureGroupAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			$sqlGetSecureGroup = "select a.*, b.f_status, b.f_inherit
                                    from tbl_secure_group a left outer join tbl_security_property b on a.f_secure_id = b.f_security_context_id
                                    where a.f_secure_group_name like '%{$query}%' order by a.f_secure_group_name asc";
			$rsSecureGroup = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetSecureGroup );
			foreach ( $rsSecureGroup as $row ) {
				checkKeyCase ( $row );
				$resultSet [] = Array ('id' => $row ['f_secure_id'], 'name' => UTFEncode ( $row ['f_secure_group_name'] ), 'ownerid' => $row ['f_owner_id'], 'status' => ($row ['f_status']) ? UTFEncode ( 'ใช้งาน' ) : UTFEncode ( 'ไม่ใช้งาน' ) );
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /document-revision/ ข้อมูล revision ของเอกสาร
	 *
	 */
	public function documentRevisionAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		$resultSet [] = Array ('id' => $row ['f_revision'], 'name' => 'Current', 'value' => 0, 
		'typeid' => $row ['f_revision'], 'desc' => 'Revision' );
		
		$sqlGetSecureGroup = "select distinct f_revision from tbl_doc_hist_value";
		$rsGetSecureGroup = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetSecureGroup );
		//include_once 'DocRevision.Entity.php';
		foreach ( $rsGetSecureGroup as $row ) {
			$docRev = new DocRevisionEntity ( );
			if (! $docRev->Load ( "f_doc_id = '{$_GET['id']}' and f_revision = '{$row ['f_revision']}'" )) {
				$revisionBy = '';
			} else {
				$revisionByAccount = new AccountEntity ( );
				$revisionByAccount->Load ( "f_acc_id = '{$docRev->f_uid}'" );
				$revisionBy = UTFEncode ( $revisionByAccount->f_name . ' ' . $revisionByAccount->f_last_name );
			}
			checkKeyCase ( $row );
			$resultSet [] = Array ('id' => $row ['f_revision'], 'name' => UTFEncode ( 'Revision ' . $row ['f_revision'] ), 'value' => $row ['f_revision'], 
			'typeid' => $row ['f_revision'], 'desc' => $revisionBy );
		}
		
		$count = count ( $resultSet );
		$data = json_encode ( $resultSet );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /secure-object/ ข้อมูล secure object ของ DMS Object
	 *
	 */
	public function secureObjectAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			$sqlGetRole = "select * from tbl_role where f_role_name like '%{$query}%' order by f_role_name asc";
			$sqlGetUser = "select * from tbl_account where f_name like '%{$query}%' or f_mid_name like '%{$query}%' or f_last_name like '%{$query}%' order by f_name asc";
			$sqlGetOrg = "select * from tbl_organize where f_org_name like '%{$query}%' order by f_org_name asc";
			
			//$rsUser = $conn->Execute (  $sqlGetUser );
			$rsUser = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetUser );
			$resultSet = Array ();
			foreach ( $rsUser as $row ) {
				checkKeyCase ( $row );
				$resultSet [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . " " . $row ['f_mid_name'] . " " . $row ['f_last_name'] ), 'typeid' => 1, 'desctype' => UTFEncode ( 'บุคคล' ), 'allow' => 1 );
			}
			
			//$rsRole = $conn->Execute ( $sqlGetRole );
			$rsRole = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetRole );
			foreach ( $rsRole as $row ) {
				checkKeyCase ( $row );
				$resultSet [] = Array ('id' => $row ['f_role_id'], 'name' => UTFEncode ( $row ['f_role_name'] ), 'typeid' => 2, 'desctype' => UTFEncode ( 'ตำแหน่งงาน' ), 'allow' => 1 );
			}
			
			//$rsOrg = $conn->Execute ( $sqlGetOrg );
			$rsOrg = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetOrg );
			foreach ( $rsOrg as $row ) {
				checkKeyCase ( $row );
				$resultSet [] = Array ('id' => $row ['f_org_id'], 'name' => UTFEncode ( $row ['f_org_name'] ), 'typeid' => 3, 'desctype' => UTFEncode ( 'หน่วยงาน/กลุ่ม' ), 'allow' => 1 );
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /role-only/ ข้อมูลเฉพาะตำแหน่งงาน
	 *
	 */
	public function roleOnlyAction() {
		global $config;
		global $conn;
		//global $sessionMgr;
		
		/*checkSessionJSON();*/
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		if ($query != '') {
			$sqlGetContactList = "select * from tbl_role where f_role_name like '%{$query}%' order by f_role_name asc";
			
			$rsGetContactList = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetContactList );
			$resultSet = Array ();
			foreach ( $rsGetContactList as $row ) {
				checkKeyCase ( $row );
				$desctype = 'ตำแหน่งงาน';
				$resultSet [] = Array ('id' => $row ['f_role_id'], 'name' => UTFEncode ( $row ['f_role_name'] ), 'desctype' => UTFEncode ( $desctype ) );
			}
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /name-only/ ข้อมูลเฉพาะชื่อผู้ใช้เท่านั้น
	 *
	 */
	public function nameOnlyAction() {
		global $config;
		global $conn;
		//global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			$sqlGetContactList = "select * from tbl_account where f_name like '%{$query}%' or f_last_name like '%{$query}%' order by f_name asc,f_last_name asc";
			$rsGetContactList = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetContactList );
			$resultSet = Array ();
			foreach ( $rsGetContactList as $row ) {
				checkKeyCase ( $row );
				$desctype = 'บุคคล';
				$resultSet [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . ' ' . $row ['f_last_name'] ), 'desctype' => UTFEncode ( $desctype ) );
			}
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /name-with-role/ ข้อมูลเฉพาะชื่อผู้ใช้เท่านั้น
	 *
	 */
	public function nameWithRoleAction() {
		global $config;
		global $conn;
		//global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			$sqlGetContactList = "select a.*,c.*, d.f_org_name from tbl_role a,tbl_passport b,tbl_account c, tbl_organize d where a.f_role_id = b.f_role_id and c.f_acc_id = b.f_acc_id and a.f_org_id = d.f_org_id";
			$sqlGetContactList .= " and (c.f_name like '%{$query}%' or f_last_name like '%{$query}%' or a.f_role_name like '%{$query}%')";
			$rsGetContactList = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetContactList );
			$resultSet = Array ();
			foreach ( $rsGetContactList as $row ) {
				checkKeyCase ( $row );
				$desctype = $row['f_role_name'];
				$resultSet [] = Array ('id' => $row['f_role_id'].'_'.$row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . ' ' . $row ['f_last_name'] .'('. $desctype .')' ), 'desctype' => UTFEncode ( $desctype ), 'orgName' => UTFEncode ( $row['f_org_name'] ) );
			}
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /organize-only/ ช้อมูลเฉพาะหน่วยงานเท่านั้น
	 *
	 */
	public function organizeOnlyAction() {
		//global $config;
		global $conn;
		//global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		//get Org PID
		//include_once 'Organize.Entity.php';
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			$sqlGetOrg = "select * from tbl_organize a where a.f_org_name like '%{$query}%'  {$extendQuery} order by a.f_org_name asc";
			
			$rsOrg = $conn->Execute ( $sqlGetOrg );
			//$rsOrg = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetOrg ); 
			foreach ( $rsOrg as $row ) {
				checkKeyCase ( $row );
				//if($row['f_org_id'] != $sessionMgr->getCurrentOrgID()) {
				$resultSet [] = Array ('id' => $row ['f_org_id'], 'name' => UTFEncode ( $row ['f_org_name'] ), 'typeid' => 3, 'desctype' => UTFEncode ( 'หน่วยงาน/กลุ่ม' ) );
				//}
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /contact-list/ ข้อมูล contact list
	 *
	 */
	public function contactListAction() {
		global $config;
		global $conn;
		global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			$sqlGetContactList = "select * from tbl_contact_list where f_cl_name like '%{$query}%' and (f_cl_owner_uid = '{$sessionMgr->getCurrentAccID()}' and  f_cl_public = 0  or f_cl_public = 1) order by f_cl_name asc";
			$rsGetContactList = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetContactList );
			$resultSet = Array ();
			foreach ( $rsGetContactList as $row ) {
				checkKeyCase ( $row );
				if ($row ['f_cl_public'] == 1) {
					$desctype = 'Public';
				} else {
					$desctype = 'Private';
				}
				$tolist = json_decode ( stripslashes ( UTFEncode ( $row ['f_cl_to_list'] ) ) );
				$cclist = json_decode ( stripslashes ( UTFEncode ( $row ['f_cl_cc_list'] ) ) );
				
				$resultSet [] = Array ('id' => $row ['f_cl_id'], 'name' => UTFEncode ( $row ['f_cl_name'] ), 'desctype' => $desctype, 'tolist' => $tolist, 'cclist' => $cclist );
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /external-contact-list/ ข้อมูล contact list ภายนอก
	 *
	 */
	public function externalContactListAction() {
		global $config;
		global $conn;
		global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			$sqlGetContactList = "select * from tbl_contact_list where f_cl_name like '%{$query}%' and (f_cl_owner_uid = '{$sessionMgr->getCurrentAccID()}' and f_cl_public = 2  or f_cl_public = 3) order by f_cl_name asc";
			$rsGetContactList = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetContactList );
			$resultSet = Array ();
			foreach ( $rsGetContactList as $row ) {
				checkKeyCase ( $row );
				$desctype = 'Public';
				$tolist = json_decode ( stripslashes ( UTFEncode ( $row ['f_cl_to_list'] ) ) );
				$cclist = json_decode ( stripslashes ( UTFEncode ( $row ['f_cl_cc_list'] ) ) );
				
				$resultSet [] = Array ('id' => $row ['f_cl_id'], 'name' => UTFEncode ( $row ['f_cl_name'] ), 'desctype' => $desctype, 'tolist' => $tolist, 'cclist' => $cclist );
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /load-user-by-id/ ข้อมูลผู้ใช้จากรหัส f_acc_id
	 *
	 */
	public function loadUserByIdAction() {
		$accID = $_POST ['id'];
		
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Passport.Entity.php';
		
		$response = Array ();
		$passport = new PassportEntity ( );
		
		if (! $passport->Load ( "f_acc_id='{$accID}' and f_default_role='1'" )) {
			$response ['roleID'] = 0;
			$response ['roleName'] = UTFEncode ( "ไม่พบตำแหน่ง" );
			echo json_encode ( $response );
		} else {
			$role = new RoleEntity ( );
			if (! $role->Load ( "f_role_id = '{$passport->f_role_id}'" )) {
				$response ['roleID'] = 0;
				$response ['roleName'] = UTFEncode ( "ไม่พบตำแหน่ง" );
				echo json_encode ( $response );
			} else {
				$response ['roleID'] = $role->f_role_id;
				$response ['roleName'] = UTFEncode ( $role->f_role_name );
				echo json_encode ( $response );
			}
		}
	}
	
	/**
	 * action /load-org-by-id/ ข้อมูลหน่วยงาน
	 *
	 */
	public function loadOrgByIdAction() {
		$orgID = $_POST ['id'];
		
		//include_once 'Organize.Entity.php';
		
		$response = Array ();
		$org = new OrganizeEntity ( );
		
		if (! $org->Load ( "f_org_id='{$orgID}'" )) {
			$response ['orgID'] = 0;
			$response ['orgName'] = UTFEncode ( "ไม่พบหน่วยงาน" );
			echo json_encode ( $response );
		} else {
			$response ['orgID'] = $orgID;
			$response ['orgName'] = UTFEncode ( $org->f_org_name );
			echo json_encode ( $response );
		}
	}
	
	/**
	 * action /receiver-text/ ข้อมูลผู้รับ/ส่งถึง
	 *
	 */
	public function receiverTextAction() {
		//global $config;
		global $conn;
		global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		//get Org PID
		//include_once 'Organize.Entity.php';
		//include_once 'Role.Entity.php';
		$role = new RoleEntity ( );
		$role->Load ( "f_role_id = '{$sessionMgr->getCurrentRoleID()}'" );
		
		if ($role->f_unlimit_lookup != 1) {
			$org = new OrganizeEntity ( );
			$org->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
			
			$parentOID = $org->f_org_pid;
			$orgID = $org->f_org_id;
			
			$extendQuery = " and (a.f_org_pid = {$orgID} or a.f_org_pid = {$parentOID} or a.f_org_id = {$parentOID}) and a.f_org_id <> {$orgID}";
		} else {
			$extendQuery = "";
		}
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			//$sqlGetRole = "select * from tbl_role where f_role_name like '%{$query}%' order by f_role_name asc";
			//$sqlGetUser = "select * from tbl_account where f_name like '%{$query}%' or f_mid_name like '%{$query}%' or f_last_name like '%{$query}%' order by f_name asc";
			//$sqlGetOrg = "select * from tbl_organize where f_org_name like '%{$query}%' order by f_org_name asc";
			

			$sqlGetRole = "select * from tbl_role b,tbl_organize a where a.f_org_id = b.f_org_id and b.f_role_name like '%{$query}%' {$extendQuery} order by a.f_org_name asc, b.f_role_name asc";
			$sqlGetUser = "select * from tbl_account where f_name like '%{$query}%' or f_mid_name like '%{$query}%' or f_last_name like '%{$query}%' order by f_name asc";
			$sqlGetOrg = "select * from tbl_organize a where a.f_org_name like '%{$query}%'  {$extendQuery} order by a.f_org_name asc";

			//$rsUser = $conn->Execute (  $sqlGetUser );
			//No User
			/*
            $rsUser = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetUser );
            $resultSet = Array ();
            foreach ( $rsUser as $row ) {
                checkKeyCase ( $row );
              
                    $resultSet [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . " " . $row ['f_mid_name'] . " " . $row ['f_last_name'] ), 'typeid' => 1, 'desctype' => UTFEncode ( 'บุคคล' ) );
                }
            }
            */
			
			$rsOrg = $conn->Execute ( $sqlGetOrg );
			//$rsOrg = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetOrg ); 
			foreach ( $rsOrg as $row1 ) {
				checkKeyCase ( $row1 );
				if ($row1 ['f_org_id'] != $sessionMgr->getCurrentOrgID ()) {
					$resultSet [] = Array ('id' => $row1 ['f_org_id'], 'name' => UTFEncode ( $row1 ['f_org_name'] ), 'typeid' => 3, 'desctype' => UTFEncode ( 'หน่วยงาน/กลุ่ม' ) );
				}
			}
			
			$rsRole = $conn->Execute ( $sqlGetRole );
			//$rsRole = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetRole );         
			foreach ( $rsRole as $row2 ) {
				checkKeyCase ( $row2 );
				if ($row2 ['f_org_id'] != $sessionMgr->getCurrentOrgID ()) {
					$resultSet [] = Array ('id' => $row2 ['f_role_id'], 'name' => UTFEncode ( $row2 ['f_role_name'] ), 'typeid' => 2, 'desctype' => UTFEncode ( 'ตำแหน่งงาน' ), 'orgName' => UTFEncode ( $row2 ['f_org_name'] ) );
				}
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /sender-text/ ข้อมูลผู้ส่ง
	 *
	 */
	public function senderTextAction() {
		//global $config;
		global $conn;
		global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		//get Org PID
		//include_once 'Organize.Entity.php';
		//include_once 'Role.Entity.php';
		$role = new RoleEntity ( );
		$role->Load ( "f_role_id = '{$sessionMgr->getCurrentRoleID()}'" );
		
		/*if ($role->f_unlimit_lookup != 1) {
			$org = new OrganizeEntity ( );
			$org->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
			
			$parentOID = $org->f_org_pid;
			$orgID = $org->f_org_id;
			
			$extendQuery = " and (a.f_org_pid = {$orgID} or a.f_org_pid = {$parentOID} or a.f_org_id = {$parentOID}) and a.f_org_id <> {$orgID}";
		} else {
			$extendQuery = "";
		}*/
		$extendQuery = "";
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			//$sqlGetRole = "select * from tbl_role where f_role_name like '%{$query}%' order by f_role_name asc";
			//$sqlGetUser = "select * from tbl_account where f_name like '%{$query}%' or f_mid_name like '%{$query}%' or f_last_name like '%{$query}%' order by f_name asc";
			//$sqlGetOrg = "select * from tbl_organize where f_org_name like '%{$query}%' order by f_org_name asc";
			

			$sqlGetRole = "select * from tbl_role b,tbl_organize a where a.f_org_id = b.f_org_id and b.f_role_name like '%{$query}%' {$extendQuery} order by b.f_role_name asc";
			$sqlGetUser = "select * from tbl_account where f_name like '%{$query}%' or f_mid_name like '%{$query}%' or f_last_name like '%{$query}%' order by f_name asc";
			$sqlGetOrg = "select * from tbl_organize a where a.f_org_name like '%{$query}%'  {$extendQuery} order by a.f_org_name asc";
			
			//$rsUser = $conn->Execute (  $sqlGetUser );
			//No User
			/*
            $rsUser = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetUser );
            $resultSet = Array ();
            foreach ( $rsUser as $row ) {
                checkKeyCase ( $row );
              
                    $resultSet [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . " " . $row ['f_mid_name'] . " " . $row ['f_last_name'] ), 'typeid' => 1, 'desctype' => UTFEncode ( 'บุคคล' ) );
                }
            }
            */
			
			$rsRole = $conn->Execute ( $sqlGetRole );
			//$rsRole = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetRole );         
			foreach ( $rsRole as $row ) {
				checkKeyCase ( $row );
				//if ($row ['f_org_id'] != $sessionMgr->getCurrentOrgID ()) {
					$resultSet [] = Array ('id' => $row ['f_role_id'], 'name' => UTFEncode ( $row ['f_role_name'] ), 'typeid' => 2, 'desctype' => UTFEncode ( 'ตำแหน่งงาน' ) );
				//}
			}
			
			$rsOrg = $conn->Execute ( $sqlGetOrg );
			//$rsOrg = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetOrg ); 
			foreach ( $rsOrg as $row ) {
				checkKeyCase ( $row );
				//if ($row ['f_org_id'] != $sessionMgr->getCurrentOrgID ()) {
					$resultSet [] = Array ('id' => $row ['f_org_id'], 'name' => UTFEncode ( $row ['f_org_name'] ), 'typeid' => 3, 'desctype' => UTFEncode ( 'หน่วยงาน/กลุ่ม' ) );
				//}
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /receiver-text-unlimit/ ข้อมูลผู้รับ/ส่งถึงแบบ unlimited
	 *
	 */
	public function receiverTextUnlimitAction() {
		//global $config;
		global $conn;
		global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		//get Org PID
		//include_once 'Organize.Entity.php';
		//include_once 'Role.Entity.php';
		$role = new RoleEntity ( );
		$role->Load ( "f_role_id = '{$sessionMgr->getCurrentRoleID()}'" );
		
		/*
        if($role->f_unlimit_lookup != 1) {
            $org = new OrganizeEntity();
            $org->Load("f_org_id = '{$sessionMgr->getCurrentOrgID()}'");
            
        
            
            $parentOID = $org->f_org_pid;
            $orgID = $org->f_org_id;
            
            $extendQuery = " and (a.f_org_pid = {$orgID} or a.f_org_pid = {$parentOID} or a.f_org_id = {$parentOID}) and a.f_org_id <> {$orgID}";
        } else {
            $extendQuery = "";
        }
        */
		
		$extendQuery = "";
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = str_replace( "'", "''", UTFDecode ( $_GET ['query'] ) );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			//$sqlGetRole = "select a.*,b.f_ from tbl_role a, tbl_organize b where a.f_org_id=b.f_org_id and a.f_role_name like '%{$query}%' order by a.f_role_name asc";
			$sqlGetRole = "select * from tbl_role b,tbl_organize a where a.f_org_id = b.f_org_id and b.f_role_name like '%{$query}%' order by b.f_role_name asc";
			$sqlGetUser = "select * from tbl_account where f_name like '%{$query}%' or f_mid_name like '%{$query}%' or f_last_name like '%{$query}%' order by f_name asc";
			$sqlGetOrg = "select * from tbl_organize where f_org_name like '%{$query}%' order by f_org_name asc";
			
			//$sqlGetRole = "select * from tbl_role b,tbl_organize a where a.f_org_id = b.f_org_id and b.f_role_name like '%{$query}%' {$extendQuery} order by b.f_role_name asc";
			//$sqlGetUser = "select * from tbl_account where f_name like '%{$query}%' or f_mid_name like '%{$query}%' or f_last_name like '%{$query}%' order by f_name asc";
			//$sqlGetOrg = "select * from tbl_organize a where a.f_org_name like '%{$query}%'  {$extendQuery} order by a.f_org_name asc";
			

			//$rsUser = $conn->Execute (  $sqlGetUser );
			//No User
			/*
            $rsUser = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetUser );
            $resultSet = Array ();
            foreach ( $rsUser as $row ) {
                checkKeyCase ( $row );
              
                    $resultSet [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . " " . $row ['f_mid_name'] . " " . $row ['f_last_name'] ), 'typeid' => 1, 'desctype' => UTFEncode ( 'บุคคล' ) );
                }
            }
            */
			
			$rsRole = $conn->Execute ( $sqlGetRole );
			//$rsRole = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetRole );         
			foreach ( $rsRole as $row ) {
				checkKeyCase ( $row );
				if($row ['f_role_name'] == 'เลขาธิการ'){
					array_unshift ($resultSet, Array ('id' => $row ['f_role_id'], 'name' => UTFEncode ( $row ['f_role_name'] ), 'orgName' => UTFEncode ( $row ['f_org_name'] ), 'typeid' => 2, 'desctype' => UTFEncode ( 'ตำแหน่งงาน' ) ));
				}else{
				// if($row['f_org_id'] != $sessionMgr->getCurrentOrgID()) {
				$resultSet [] = Array ('id' => $row ['f_role_id'], 'name' => UTFEncode ( $row ['f_role_name'] ), 'orgName' => UTFEncode ( $row ['f_org_name'] ), 'typeid' => 2, 'desctype' => UTFEncode ( 'ตำแหน่งงาน' ) );
				// }
				}
			}
			//var_dump($resultSet);
			
			$rsOrg = $conn->Execute ( $sqlGetOrg );
			//$rsOrg = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetOrg ); 
			foreach ( $rsOrg as $row ) {
				checkKeyCase ( $row );
				//if($row['f_org_id'] != $sessionMgr->getCurrentOrgID()) {
				$resultSet [] = Array ('id' => $row ['f_org_id'], 'name' => UTFEncode ( $row ['f_org_name'] ), 'typeid' => 3, 'desctype' => UTFEncode ( 'หน่วยงาน/กลุ่ม' ) );
				//}
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /receiver-in-dept/ ข้อมูลผู้รับ/ส่งถึง เฉพาะหน่วยงานเท่านั้น
	 *
	 */
	public function receiverInDeptAction() {
		global $config;
		global $conn;
		global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		$orgID = $sessionMgr->getCurrentOrgID ();
		
		if ($query != '') {
			
			$sqlGetRole = "select * from 
            tbl_role where f_role_id in (
                    select distinct(b.f_role_id) from tbl_role b,
                    tbl_organize c,
                    tbl_passport d
                    where 
                    c.f_org_id = 1
                    and b.f_org_id = c.f_org_id
                    and d.f_role_id = b.f_role_id
            ) and f_role_name like '%{$query}%' order by f_role_name asc";
			
			$sqlGetUser = "select * from 
                tbl_account where f_acc_id in (
                    select distinct(a.f_acc_id) from tbl_account a,
                    tbl_role b,
                    tbl_organize c,
                    tbl_passport d
                    where 
                    c.f_org_id = '{$orgID}'
                    and b.f_org_id = c.f_org_id
                    and d.f_role_id = b.f_role_id
                    and d.f_acc_id = a.f_acc_id
                ) and (f_name like '%{$query}%' or f_mid_name like '%{$query}%' or f_last_name like '%{$query}%') order by f_name asc";
			
			//$rsUser = $conn->Execute (  $sqlGetUser );
			

			$resultSet = Array ();
			
			$rsRole = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetRole );
			foreach ( $rsRole as $row ) {
				checkKeyCase ( $row );
				$resultSet [] = Array ('id' => $row ['f_role_id'], 'name' => UTFEncode ( $row ['f_role_name'] ), 'typeid' => 2, 'desctype' => UTFEncode ( 'ตำแหน่งงาน' ) );
			}
			
			$rsUser = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetUser );
			foreach ( $rsUser as $row ) {
				checkKeyCase ( $row );
				$resultSet [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . " " . $row ['f_mid_name'] . " " . $row ['f_last_name'] ), 'typeid' => 1, 'desctype' => UTFEncode ( 'บุคคล' ) );
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /commander/ ข้อมูลผู้สั่งการ
	 *
	 */
	public function commanderAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = str_replace( "'", "''", UTFDecode ( $_GET ['query'] ) );
			//$query = UTFDecode ( $_GET ['query'] );
		} else {
			$query = '';
		}
		
		if ($query != '') {
			$sqlGetRole = "select * from tbl_role where f_role_name like '%{$query}%' order by f_role_name asc";
			
			$rsRole = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetRole );
			$resultSet = Array ();
			foreach ( $rsRole as $row ) {
				checkKeyCase ( $row );
				$resultSet [] = Array ('id' => $row ['f_role_id'], 'name' => UTFEncode ( $row ['f_role_name'] ), 'typeid' => 2, 'desctype' => UTFEncode ( 'ตำแหน่งงาน' ) );
			}
			
			$count = count ( $resultSet );
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /sender-external/ ข้อมูลผู้ส่งมาภายนอก
	 *
	 */
	public function senderExternalAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = str_replace( "'", "''", UTFDecode ( $_GET ['query'] ) );
		} else {
			$query = '';
		}
	
		if (array_key_exists ( 'txs', $_COOKIE )) {
			$senderType = $_COOKIE ['txs'];
		} else {
			$senderType = 1;
		}
		
		if ($query != '') {
			if ($senderType == 4) {
				$sqlGet = "select * from tbl_th_egif_department a,tbl_th_egif_ministry b where (a.f_department_name like '%{$query}%' or b.f_ministry_name like '%{$query}%') and a.f_ministry_id = b.f_ministry_id order by b.f_ministry_id asc";
				
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGet );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_department_id'], 'name' => UTFEncode ( $row ['f_department_name'] ), 'type' => 1, 'desctype' => UTFEncode ( $row ['f_ministry_name'] ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			} else {
				$sqlGet = "select * from tbl_external_sender where f_ext_sender_name like '%{$query}%' and f_ext_sender_status = 1 order by f_ext_sender_name asc";
				
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGet );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_ext_sender_id'], 'name' => UTFEncode ( $row ['f_ext_sender_name'] ), 'type' => 1, 'desctype' => UTFEncode ( 'บุคคล/หน่วยงานภายนอก' ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			}
		
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}

	public function jqueryContactListAction() {
		global $conn;
		
		if (array_key_exists ( 'q', $_GET )) {
			$query = UTFDecode ( $_GET ['q'] );
		} else {
			$query = '';
		}

		$sqlSender = "SELECT f_ext_sender_name FROM tbl_external_sender WHERE f_ext_sender_name LIKE '%{$query}%' and f_ext_sender_status = 1 order by f_ext_sender_name asc";

		$rsSender = $conn->Execute ( $sqlSender );
		foreach ($rsSender as $row){
			checkKeyCase ( $row );
			$cname = $row ['f_ext_sender_name'];
			echo "$cname\n";
		}
		
	}

	public function senderExternalListAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = str_replace( "'", "''", UTFDecode ( $_GET ['query'] ) );
		} else {
			$query = '';
		}
		
		if (array_key_exists ( 'txs', $_COOKIE )) {
			$senderType = $_COOKIE ['txs'];
		} else {
			$senderType = 1;
		}
		
		if ($query != '') {
			if ($senderType == 4) {
				$sqlGet = "select * from tbl_th_egif_department a,tbl_th_egif_ministry b where (a.f_department_name like '%{$query}%' or b.f_ministry_name like '%{$query}%') and a.f_ministry_id = b.f_ministry_id order by b.f_ministry_id asc";
				
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGet );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_department_id'], 'name' => UTFEncode ( $row ['f_department_name'] ), 'type' => 1, 'desctype' => UTFEncode ( $row ['f_ministry_name'] ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			} else {
				$sqlGet = "select * from tbl_external_sender where f_ext_sender_name like '%{$query}%' order by f_ext_sender_name asc";
				
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGet );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_ext_sender_id'], 'name' => UTFEncode ( $row ['f_ext_sender_name'] ), 'type' => 1, 'desctype' => UTFEncode ( 'บุคคล/หน่วยงานภายนอก' ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			}
		
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}

	public function senderExternalFilterAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = str_replace( "'", "''", UTFDecode ( $_GET ['query'] ) );
		} else {
			$query = '';
		}
		
		if (array_key_exists ( 'txs', $_COOKIE )) {
			$senderType = $_COOKIE ['txs'];
		} else {
			$senderType = 1;
		}
		
		if ($query != '') {
			if ($senderType == 4) {
				$sqlGet = "select * from tbl_th_egif_department a,tbl_th_egif_ministry b where (a.f_department_name like '%{$query}%' or b.f_ministry_name like '%{$query}%') and a.f_ministry_id = b.f_ministry_id order by b.f_ministry_id asc";
				
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGet );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_department_id'], 'name' => UTFEncode ( $row ['f_department_name'] ), 'type' => 1, 'desctype' => UTFEncode ( $row ['f_ministry_name'] ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			} else {
				$sqlGet = "select * from tbl_external_sender where f_ext_sender_name like '%{$query}%' and f_ext_sender_status = 1 order by f_ext_sender_name asc";
				
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGet );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_ext_sender_id'], 'name' => UTFEncode ( $row ['f_ext_sender_name'] ), 'type' => 1, 'desctype' => UTFEncode ( 'บุคคล/หน่วยงานภายนอก' ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			}
		
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /receiver-external/ ข้อมูลผู้รับภายนอก
	 *
	 */
	public function receiverExternalAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = str_replace( "'", "''", UTFDecode ( $_GET ['query'] ) );
		} else {
			$query = '';
		}
		
		if (array_key_exists ( 'txr', $_COOKIE )) {
			$receiverType = $_COOKIE ['txr'];
		} else {
			$receiverType = 1;
		}
		
		if ($query != '') {
			if ($receiverType == 4) {
				$sqlGetDept = "select * from tbl_th_egif_department a,tbl_th_egif_ministry b where (a.f_department_name like '%{$query}%' or b.f_ministry_name like '%{$query}%') and a.f_ministry_id = b.f_ministry_id order by b.f_ministry_id asc";
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetDept );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_department_id'], 'name' => UTFEncode ( $row ['f_department_name'] ), 'type' => 1, 'desctype' => UTFEncode ( $row ['f_ministry_name'] ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			} else {
				$sqlGet = "select * from tbl_external_sender where f_ext_sender_name like '%{$query}%' and f_ext_sender_status = 1 order by f_ext_sender_name asc";
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGet );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_ext_sender_id'], 'name' => UTFEncode ( $row ['f_ext_sender_name'] ), 'type' => 1, 'desctype' => UTFEncode ( 'บุคคล/หน่วยงานภายนอก' ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			}
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /receiver-external-all/ ข้อมูลผู้รับ/ส่งถึงภายนอกทั้งหมด
	 *
	 */
	public function receiverExternalAllAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		if (array_key_exists ( 'query', $_GET )) {
			$query = str_replace( "'", "''", UTFDecode ( $_GET ['query'] ) );
		} else {
			$query = '';
		}
		
		if (array_key_exists ( 'txr', $_COOKIE )) {
			$receiverType = $_COOKIE ['txr'];
		} else {
			$receiverType = 1;
		}
		
		if ($query != '') {
			if ($receiverType == 4) {
				$sqlGetDept = "select * from tbl_th_egif_department a,tbl_th_egif_ministry b where (a.f_department_name like '%{$query}%' or b.f_ministry_name like '%{$query}%') and a.f_ministry_id = b.f_ministry_id order by b.f_ministry_id asc";
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetDept );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_department_id'], 'name' => UTFEncode ( $row ['f_department_name'] ), 'type' => 1, 'desctype' => UTFEncode ( $row ['f_ministry_name'] ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			} else {
				$sqlGet = "select * from tbl_external_sender where f_ext_sender_name like '%{$query}%' and f_ext_sender_status = 1 order by f_ext_sender_name asc";
				//$rsUser = $conn->Execute (  $sqlGetUser );
				$rsSender = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGet );
				$resultSet = Array ();
				foreach ( $rsSender as $row ) {
					checkKeyCase ( $row );
					$resultSet [] = Array ('id' => $row ['f_ext_sender_id'], 'name' => UTFEncode ( $row ['f_ext_sender_name'] ), 'type' => 1, 'desctype' => UTFEncode ( 'บุคคล/หน่วยงานภายนอก' ) );
				}
				
				$count = count ( $resultSet );
				$data = json_encode ( $resultSet );
				$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
				print $cb . '({"total":"' . $count . '","results":' . $data . '})';
			}
		
		} else {
			$count = 0;
			$resultSet = Array ();
			$data = json_encode ( $resultSet );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		}
	}
	
	/**
	 * action /internal-organize/ ข้อมูลหน่วยงานภายใน
	 *
	 */
	public function internalOrganizeAction() {
		global $config;
		global $conn;
		
		/*checkSessionJSON();*/
		
		$query = UTFDecode ( $_GET ['query'] );
		$sql = "select * from tbl_position_master where f_pos_name like '%{$query}%' order by f_pos_level asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$positions = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase ( $row );
			$positions [] = Array ('id' => $row ['f_pos_id'], 'name' => UTFEncode ( $row ['f_pos_name'] ), 'description' => UTFEncode ( $row ['f_pos_desc'] ), 'level' => $row ['f_pos_level'], 'status' => $row ['f_pos_status'] );
		}
		
		$count = count ( $positions );
		$data = json_encode ( $positions );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}

}
