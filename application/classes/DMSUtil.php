<?php
/**
 * Utility Class สำหรับระบบจัดเก็บเอกสาร
 * @author Arthit Boonyakiet
 * @version 1.0.0
 * @package classes
 * @category DMS Utility Class 
 */
class DMSUtil {
	/**
	 * Database Connection 
	 *
	 * @var resource
	 */
	private $conn;
	/**
	 * DMS Object
	 *
	 * @var resource
	 */
	private $dmsObj;
	/**
	 * Parents ID
	 *
	 * @var mixed
	 */
	private $parents;
	
	/**
	 * Constructor ดึง Connection จาก Global Scope มาเก็บไว้ และสร้าง DMSObjectEntity Object
	 *
	 */
	public function __construct() {
		global $conn;
		
		//include_once 'DmsObject.Entity.php';
		$this->conn = $conn;
		$this->dmsObj = new DmsObjectEntity ( );
	}
	
	/**
	 * ขอ Index Location Name
	 *
	 * @param int $id
	 * @return string
	 */
	public function getIndexLocationName($id) {
		$arrIndex = array ();
		$arrIndex = $this->getIndexParent ( $id, 'name' );
		//print_r($arrIndex);
		$arrResult = array_reverse ( $arrIndex );
		return implode ( "/", $arrResult );
	}
	
	/**
	 * ขอ Parent ของ Index
	 *
	 * @param int $id
	 * @param string $field
	 * @return variant
	 */
	public function getIndexParent($id, $field = 'id') {
		
		$arrIndex = array ();
		
		do {
			if ($this->dmsObj->Load ( "f_obj_id = {$id}" )) {
				$id = $this->dmsObj->f_obj_pid;
				
				switch ($field) {
					case 'name' :
						array_push ( $arrIndex, $this->dmsObj->f_name );
						break;
					default :
						array_push ( $arrIndex, $this->dmsObj->f_obj_id );
						break;
				}
			} else {
				break;
			}
		} while ( $this->dmsObj->f_obj_pid > 0 );
		return $arrIndex;
	}
	
	/**
	 * ขอ Child ของ Index
	 *
	 * @param int $id
	 * @param string $type
	 * @return array of index
	 */
	public function getIndexChild($id, $type = 'ALL') {
		
		$arrParent = array ($id );
		$arrIndex = array ($id );
		do {
			if (count ( $arrParent ) > 0) {
				$indexList = implode ( ',', $arrParent );
				$arrParent = array ();
			}
			$rs = $this->conn->Execute ( 'select f_obj_id, f_obj_type from tbl_dms_object where f_obj_pid in (' . $indexList . ')' );
			while ( $row = $rs->FetchRow () ) {
				checkKeyCase ( $row );
				if ($row ['f_obj_type'] == 0) {
					switch ($type) {
						case 'ALL' :
							array_push ( $arrParent, $row ['f_obj_id'] );
							array_push ( $arrIndex, $row ['f_obj_id'] );
							break;
						case 0 :
							if ($row ['f_obj_type'] == 0) {
								array_push ( $arrParent, $row ['f_obj_id'] );
								array_push ( $arrIndex, $row ['f_obj_id'] );
							}
							break;
						case 1 :
							if ($row ['f_obj_type'] == 1) {
								array_push ( $arrParent, $row ['f_obj_id'] );
								array_push ( $arrIndex, $row ['f_obj_id'] );
							}
							break;
					}
				}
			}
		} while ( count ( $arrParent ) > 0 );
		return $arrIndex;
	}
	
	/**
	 * ขอจำนวน Folder ภายใต้ Index
	 *
	 * @param int $id
	 * @return int
	 */
	public function getIndexFolderCount($id) {
		return count ( $this->getIndexChild ( $id ) ) - 1;
	}
	
	/**
	 * ขอจำนวน Document ภายใต้ Index
	 *
	 * @param int $id
	 * @return int
	 */
	public function getIndexDocumentCount($id) {
		return count ( $this->getIndexChild ( $id, 1 ) );
	}
	
	/**
	 * ขอจำนวนหน้าของเอกสาร
	 *
	 * @param int $docID
	 * @return int
	 */
	public function getPageCount($docID) {
		global $conn;
		$sql = "select count(*) as COUNT_EXP from tbl_doc_page where f_doc_id = '{$docID}'";
		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();
		return ( int ) $tmpCount->COUNT_EXP;
	}
	
	public function getVersionNumber($docID) {
		$doc = new DocMainEntity ( );
		$doc->Load ( "f_doc_id = '{$docID}'" );
		if (is_null ( $doc->f_version )) {
			$doc->f_version = "1.0.0";
			$doc->Update ();
		}
		return "{$doc->f_version}";
	}
	
	/**
	 * ขอจำนวนหน้าของ Thumbnail
	 *
	 * @param int $docID
	 * @return int
	 */
	public function getThumbnailPageCount($docID) {
		global $config;
		$pageCount = $this->getPageCount ( $docID );
		$maxpage = div ( $pageCount, $config ['thumbnailPerPage'] );
		if (mod ( $pageCount, $config ['thumbnailPerPage'] ) > 0) {
			$maxpage += 1;
		}
		//$maxpage = ($pageCount/$config['thumbnailPerPage'])+1;
		return $maxpage;
	}
	
	/**
	 * ขอหน้าของ Thumbnail จากหน้าที่ของเอกสาร
	 *
	 * @param int $pageNo
	 * @return int
	 */
	public function getThumbnailPage($pageNo) {
		global $config;
		$currentPage = div ( $pageNo, $config ['thumbnailPerPage'] );
		if (mod ( $pageNo, $config ['thumbnailPerPage'] ) > 0) {
			$currentPage += 1;
		}
		//$maxpage = ($pageCount/$config['thumbnailPerPage'])+1;
		return $currentPage;
	}
	
	/**
	 * ขอจำนวน Index ทั้งหมดภายใต้ Index นี้
	 *
	 * @param int $id
	 * @return int
	 */
	public function getIndexContain($id) {
		global $lang;
		return $this->getIndexFolderCount ( $id ) . ' ' . $lang ['dms'] ['folder'] . ', ' . $this->getIndexDocumentCount ( $id ) . ' ' . $lang ['dms'] ['document'];
	}
	
	/**
	 * ขอชนิดของ Index
	 *
	 * @param int $type
	 * @return string
	 */
	public function getIndexType($type) {
		global $lang;
		switch ($type) {
			case 0 :
				$result = $lang ['dms'] ['folder'];
				break;
			case 1 :
				$result = $lang ['dms'] ['document'];
				break;
			case 2 :
				$result = $lang ['dms'] ['shortcut'];
				break;
		}
		return $result;
	}
	
	/**
	 * ขอภาพของ Index ตามชนิดของ Index
	 *
	 * @param int $type
	 * @return string
	 */
	public function getIndexTypeImage($type) {
		global $config;
		switch ($type) {
			case 0 :
				$result = "<IMG src=\"/{$config['appName']}/images/icons/folder.gif\">";
				break;
			case 1 :
				$result = "<IMG src=\"/{$config['appName']}/images/icons/documents.gif\">";
				break;
			case 2 :
				$result = "<IMG src=\"/{$config['appName']}/images/icons/shortcut.png\">";
				break;
		}
		return $result;
	}
	
	/**
	 * ไฮไลท์ข้อความ
	 *
	 * @param string $text
	 * @param string $wordhilight
	 * @return string
	 */
	public function hilightText($text, $wordhilight) {
		return str_replace ( $wordhilight, "<font color=\"red\"><b><span style=\"background-color:#FFFF00\">{$wordhilight}</span></b></font>", $text );
	}
	
	/**
	 * ขอ Parent ของ Index
	 *
	 * @param index $id
	 * @param boolean $reset
	 */
	public function getParents($id, $reset = false) {
		if ($reset) {
			$this->parents = Array ();
		}
		$sqlGetParent = "select f_obj_pid,f_obj_type from tbl_dms_object where f_obj_id = '{$id}'";
		$rsGetParent = $this->conn->Execute ( $sqlGetParent );
		$parent = $rsGetParent->FetchNextObject ();
		$this->parents [] = $parent->F_OBJ_PID;
		if ($parent->F_OBJ_PID != 0) {
			$this->getParents ( $parent->F_OBJ_PID );
		}
	}
	
	/**
	 * ขอ Access Control List
	 *
	 * @param int $id
	 * @return array of ACLs
	 */
	public function acl($id) {
		global $policy;
		global $util;
		global $sessionMgr;
		
		if ($id == 0 || is_null ( $id )) {
			$DMSPolicy = Array ();
			$DMSPolicy = array_reverse ( $policy->getCurrentDMSPolicy () );
			$DMSPolicy ['F_OBJ_ID'] = $id;
			return array_reverse ( $DMSPolicy );
		}
		
		$IDs = array_reverse ( $this->getIndexParent ( $id ) );
		//Logger::dump ( "IDs To Loop :", $IDs );
		

		$allowList = Array ();
		$deniedList = Array ();
		
		$totalAllow = Array ();
		$totalDeny = Array ();
		
		$noPolicy = true;
		foreach ( $IDs as $loopID ) {
			$allowList [$loopID] = Array ();
			$deniedList [$loopID] = Array ();
			
			$sqlQueryID = "select count(*) as COUNT_EXP from tbl_security_property where f_obj_id = '{$loopID}' and f_status =1";
			//Logger::debug ( "ID[{$loopID}] : {$sqlQueryID}" );
			$rsSQLQueryID = $this->conn->Execute ( $sqlQueryID );
			$tmpSQLQueryID = $rsSQLQueryID->FetchNextObject ();
			//Logger::debug ( "ID[{$loopID}] has ({$tmpSQLQueryID->COUNT_EXP}) security property" );
			if ($tmpSQLQueryID->COUNT_EXP > 0) {
				$noPolicy = false;
				
				$sqlGetSecureGroup = "select * from tbl_security_property where f_obj_id ='{$loopID}' and f_status =1";
				$rsGetSecureGroup = $this->conn->Execute ( $sqlGetSecureGroup );
				/* Get Secure Group */
				while ( $tmpGetSecureGroup = $rsGetSecureGroup->FetchNextObject () ) {
					$sqlGetSecureMember = "select * from tbl_secure_group_member where f_secure_id = '{$tmpGetSecureGroup->F_SECURITY_CONTEXT_ID}'";
					$rsGetSecureMember = $this->conn->Execute ( $sqlGetSecureMember );
					/* Get Secure member*/
					while ( $tmpGetSecureMember = $rsGetSecureMember->FetchNextObject () ) {
						$tmpMembers = Array ();
						//Logger::dump ( "Secure Group Member", $tmpGetSecureMember );
						switch ($tmpGetSecureMember->F_MEMBER_TYPE) {
							case 1 :
								//Logger::debug ( 'User' );
								$tmpMembers [] = $tmpGetSecureMember->F_MEMBER_ID;
								break;
							case 2 :
								//Logger::debug ( 'Role' );
								$tmpMembers = array_merge ( $tmpMembers, $util->getRoleMembers ( $tmpGetSecureMember->F_MEMBER_ID ) );
								//Logger::dump ( "Roles ,Convert To Members:", $tmpMembers );
								break;
							case 3 :
								//Logger::debug ( 'Organize' );
								$tmpMembers = array_merge ( $tmpMembers, $util->getOrganizeMember ( $tmpGetSecureMember->F_MEMBER_ID ) );
								//Logger::dump ( "Organize ,Convert To Members:", $tmpMembers );
								break;
						}
						if ($tmpGetSecureMember->F_ALLOW == 1) {
							//Logger::dump ( 'Add to allow list', $tmpMembers );
							$allowList [$loopID] = array_merge ( $allowList [$loopID], $tmpMembers );
							$totalAllow = array_merge ( $totalAllow, $tmpMembers );
						} else {
							//Logger::dump ( 'Add to denied list', $tmpMembers );
							$deniedList [$loopID] = array_merge ( $deniedList [$loopID], $tmpMembers );
							$totalDeny = array_merge ( $totalDeny, $tmpMembers );
						}
					}
				}
			}
		}
		
		if ($noPolicy) {
			//Logger::debug ( 'No Policy !!!' );
			//Logger::debug ( 'Allow Access !!!' );
			//Logger::dump ( 'DMS Policy', $policy->getCurrentDMSPolicy () );
			$DMSPolicy = Array ();
			$DMSPolicy = array_reverse ( $policy->getCurrentDMSPolicy () );
			$DMSPolicy ['F_OBJ_ID'] = $id;
			return array_reverse ( $DMSPolicy );
		} else {
			//Logger::debug ( 'Policy EXISTS !!!' );
			//Logger::dump ( 'Allow List', $allowList );
			//Logger::dump ( 'Allow List', $totalAllow );
			//Logger::dump ( 'Deny List', $deniedList );
			//Logger::dump ( 'Deny List', $totalDeny );
			

			$DMSPolicy = Array ();
			$DMSPolicy ['F_OBJ_ID'] = $id;
			$DMSPolicy = array_reverse ( $policy->getCurrentDMSPolicy () );
			
			($DMSPolicy ['F_DMS_MASTER']) ? $arrVerify ['vfMaster'] = true : $arrVerify ['vfMaster'] = false;
			(in_array ( $sessionMgr->getCurrentAccID (), $totalAllow )) ? $arrVerify ['vfAllow'] = true : $arrVerify ['vfAllow'] = false;
			(in_array ( $sessionMgr->getCurrentAccID (), $totalDeny )) ? $arrVerify ['vfDeny'] = true : $arrVerify ['vfDeny'] = false;
			(count ( $totalAllow ) == 0) ? $arrVerify ['vfCountAllow'] = true : $arrVerify ['vfCountAllow'] = false;
			
			//Logger::dump ( 'DMSPolicy', $DMSPolicy );
			//Logger::dump ( 'getCurrentAccID', $sessionMgr->getCurrentAccID () );
			//Logger::dump ( 'totalAllow', $totalAllow);
			//Logger::dump ( 'arrVerify', $arrVerify);
			

			if (($arrVerify ['vfMaster']) || ($arrVerify ['vfAllow'] && ! $arrVerify ['vfDeny']) || (($arrVerify ['vfCountAllow']) && (! $arrVerify ['vfDeny']))) {
				//Logger::debug ( 'Allow Access !!!' );
			} else {
				//Logger::debug ( 'Denied Access !!!' );
				return false;
			}
		}
		
		$DMSPolicy = Array ();
		//$DMSPolicy = $policy->getCurrentDMSPolicy ();
		

		$DMSPolicy = array_reverse ( $policy->getCurrentDMSPolicy () );
		$DMSPolicy ['F_OBJ_ID'] = $id;
		$DMSPolicy = array_reverse ( $DMSPolicy );
		
		//Logger::dump ( "Initial DMS Policy", $DMSPolicy );
		foreach ( $IDs as $loopID ) {
			$sqlQueryID = "select count(*) as COUNT_EXP from tbl_security_property where f_obj_id = '{$loopID}' and f_status =1";
			//Logger::debug ( "ID[{$loopID}] : {$sqlQueryID}" );
			$rsSQLQueryID = $this->conn->Execute ( $sqlQueryID );
			$tmpSQLQueryID = $rsSQLQueryID->FetchNextObject ();
			//Logger::debug ( "ID[{$loopID}] has ({$tmpSQLQueryID->COUNT_EXP}) security property" );
			if ($tmpSQLQueryID->COUNT_EXP > 0) {
				//Logger::debug ( "Checking for Policy overriding" );
				$sqlQueryID2 = "select * from tbl_security_property where f_obj_id = '{$loopID}' and f_status =1";
				//Logger::dump("SQL",$sqlQueryID2);
				$rsQueryID2 = $this->conn->Execute ( $sqlQueryID2 );
				while ( $tmpQueryID2 = $rsQueryID2->FetchNextObject () ) {
					//Logger::dump ( 'temp', $tmpQueryID2 );
					if ($policy->isSecureGroupMember ( $tmpQueryID2->F_SECURITY_CONTEXT_ID )) {
						if ($DMSPolicy ['F_DMS_CREATE_FOLDER'] == 1) {
							if ($tmpQueryID2->F_CREATE_FOLDER == 0) {
								//Logger::debug ( "override F_DMS_CREATE_FOLDER" );
								$DMSPolicy ['F_DMS_CREATE_FOLDER'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_MODIFY_FOLDER'] == 1) {
							if ($tmpQueryID2->F_MODIFY_FOLDER == 0) {
								//Logger::debug ( "override F_DMS_MODIFY_FOLDER" );
								$DMSPolicy ['F_DMS_MODIFY_FOLDER'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_DELETE_FOLDER'] == 1) {
							if ($tmpQueryID2->F_DELETE_FOLDER == 0) {
								//Logger::debug ( "override F_DMS_DELETE_FOLDER" );
								$DMSPolicy ['F_DMS_DELETE_FOLDER'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_CREATE_DOC'] == 1) {
							if ($tmpQueryID2->F_CREATE_DOC == 0) {
								//Logger::debug ( "override F_DMS_CREATE_DOC" );
								$DMSPolicy ['F_DMS_CREATE_DOC'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_MODIFY_DOC'] == 1) {
							if ($tmpQueryID2->F_MODIFY_DOC == 0) {
								//Logger::debug ( "override F_DMS_MODIFY_DOC" );
								$DMSPolicy ['F_DMS_MODIFY_DOC'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_DELETE_DOC'] == 1) {
							if ($tmpQueryID2->F_DELETE_DOC == 0) {
								//Logger::debug ( "override F_DMS_DELETE_DOC" );
								$DMSPolicy ['F_DMS_DELETE_DOC'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_CREATE_SHORTCUT'] == 1) {
							if ($tmpQueryID2->F_CREATE_SHORTCUT == 0) {
								//Logger::debug ( "override F_DMS_CREATE_SHORTCUT" );
								$DMSPolicy ['F_DMS_CREATE_SHORTCUT'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_MODIFY_SHORTCUT'] == 1) {
							if ($tmpQueryID2->F_MODIFY_SHORTCUT == 0) {
								//Logger::debug ( "override F_DMS_MODIFY_SHORTCUT" );
								$DMSPolicy ['F_DMS_MODIFY_SHORTCUT'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_DELETE_SHORTCUT'] == 1) {
							if ($tmpQueryID2->F_DELETE_SHORTCUT == 0) {
								//Logger::debug ( "override F_DMS_DELETE_SHORTCUT" );
								$DMSPolicy ['F_DMS_DELETE_SHORTCUT'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_MOVE'] == 1) {
							if ($tmpQueryID2->F_MOVE == 0) {
								//Logger::debug ( "override F_DMS_MOVE" );
								$DMSPolicy ['F_DMS_MOVE'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_SHARE'] == 1) {
							if ($tmpQueryID2->F_SHARE == 0) {
								//Logger::debug ( "override F_DMS_SHARE" );
								$DMSPolicy ['F_DMS_SHARE'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_EXPORT'] == 1) {
							if ($tmpQueryID2->F_EXPORT == 0) {
								//Logger::debug ( "override F_DMS_EXPORT" );
								$DMSPolicy ['F_DMS_EXPORT'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_GRANT'] == 1) {
							if ($tmpQueryID2->F_GRANT == 0) {
								//Logger::debug ( "override F_DMS_GRANT" );
								$DMSPolicy ['F_DMS_GRANT'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_SCAN'] == 1) {
							if ($tmpQueryID2->F_SCAN == 0) {
								//Logger::debug ( "override F_DMS_SCAN" );
								$DMSPolicy ['F_DMS_SCAN'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_ATTACH'] == 1) {
							if ($tmpQueryID2->F_ATTACH == 0) {
								//Logger::debug ( "override F_DMS_ATTACH" );
								$DMSPolicy ['F_DMS_ATTACH'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_PRINT'] == 1) {
							if ($tmpQueryID2->F_PRINT == 0) {
								//Logger::debug ( "override F_DMS_PRINT" );
								$DMSPolicy ['F_DMS_PRINT'] = 0;
							}
						}
						
						if ($DMSPolicy ['F_DMS_ANNOTATE'] == 1) {
							if ($tmpQueryID2->F_ANNOTATE == 0) {
								//Logger::debug ( "override F_DMS_ANNOTATE" );
								$DMSPolicy ['F_DMS_ANNOTATE'] = 0;
							}
						}
					}
				}
			} else {
				//Logger::debug ( "NO Policy overriding" );
			}
		}
		//Logger::dump ( "DMS Policy returned", $DMSPolicy );
		return $DMSPolicy;
	}
	
	/**
	 * ขอจำนวน Recycle Bin
	 *
	 * @return int
	 */
	public function getRecyclebinCount() {
		global $conn;
		$sql = "select count(*) as count_exp from tbl_dms_object 
		where f_mark_delete=1 and f_mark_delete_uid={$_SESSION['accID']}";
		
		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}
	
	/**
	 * ขอจำนวนเอกสารหมดอายุ
	 *
	 * @return int
	 */
	public function getExpireDocumentCount() {
		global $conn;
		$sql = "select count(*) as count_exp from tbl_dms_object 
		where f_mark_delete=0 and f_expire_stamp > 0 and f_expire_stamp < " . time ();
		
		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}
	
	/**
	 * ขอจำนวนเอกสารที่ Checkout
	 *
	 * @return int
	 */
	public function getCheckoutCount() {
		global $conn;
		$sql = "select count(*) as count_exp from tbl_dms_object a, tbl_doc_main b
		where a.f_doc_id = b.f_doc_id and b.f_checkout_flag = 1 and b.f_checkout_user = {$_SESSION['accID']}";
		
		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}
	
	/**
	 * ขอจำนวนเอกสารที่มีคนยืมไป
	 *
	 * @return int
	 */
	public function getBorrowCount() {
		global $conn;
		global $sessionMgr;
		
		$sql = "select count(*) as COUNT_EXP from tbl_borrow_record where f_borrow_uid = '{$sessionMgr->getCurrentAccID()}' and f_return_flag = 0";
		$rsCount = $conn->Execute($sql);
		$tmpCount = $rsCount->FetchNExtObject();
		return $tmpCount->COUNT_EXP;
	}
	
	/**
	 * ขอจำนวนรายชื่อภายใน Contact List
	 *
	 * @param int $cList
	 */
	public function extractContactList($cList) {
		return $cList;
	}
	
	/**
	 * ทำการบันทึกยืมเอกสาร
	 *
	 * @param int $docID
	 * @param int $borrowerUID
	 * @param int $dueDateStamp
	 */
	public function borrowDocument($docID, $borrowerUID, $dueDateStamp = 0) {
		global $sequence;
		global $sessionMgr;
		
		$borrowRecord = new BorrowRecordEntity ( );
		if (! $sequence->isExists ( 'borrowID' )) {
			$sequence->create ( 'borrowID' );
		}
		
		$borrowRecord->f_borrow_id = $sequence->get ( 'borrowID' );
		$borrowRecord->f_borrow_uid = $sessionMgr->getCurrentAccID ();
		$borrowRecord->f_doc_id = $docID;
		$borrowRecord->f_borrower_uid = $borrowerUID;
		if ($dueDateStamp != 0) {
			$borrowRecord->f_due_date = $dueDateStamp;
		} else {
			$borrowRecord->f_due_date = time ();
		}
		$borrowRecord->f_return_flag = 0;
		$borrowRecord->Save();
	}
	
	/**
	 * ทำการบันทึกคืนเอกสาร
	 *
	 * @param int $borrowID
	 */
	public function returnDocument($borrowID) {
		$borrowRecord = new BorrowRecordEntity ( );
		
		$borrowRecord->Load("f_borrow_id = '{$borrowID}'");
		$borrowRecord->f_return_flag = 1;
		
		$borrowRecord->Update();
	}
	
}
