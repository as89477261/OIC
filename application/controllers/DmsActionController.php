<?php
/**
 * โปรแกรมประมวลผลงานระบบ DMS
 *
 */
class DMSActionController extends ECMController {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		//include_once 'DmsObject.Entity.php';
		//include_once 'Notifier.Entity.php';
		//include_once 'DMSUtil.php';
	}
	
/*	public function deleteNodeAction() {
		//global $logger;		
		

		$objId = $_POST ['objId'];
		$dmsNode = new DmsObjectEntity ( );
		if (! $dmsNode->Load ( "f_obj_id='{$objId}'" )) {
			//Logger::log('DMSObject', $objId, 'Object does not exist');
		} else {
			
			if ($dmsNode->f_mark_delete == 1) {
				$dmsNode->Delete ();
				//Logger::log('DMSObject', $objId, 'Object has been deleted', true, false);
			} else {
				$dmsNode->f_mark_delete = 1;
				$dmsNode->Update ();
				//Logger::log('DMSObject', $objId, 'Object has been deleted', true, false);
			}
		}
		$result = array ('success' => 1 );
		echo json_encode ( $result );
	}*/
	
	/**
	 * action /move-node/ ย้าย
	 *
	 */
	public function moveNodeAction() {
		global $sessionMgr;
		global $conn;

		$objIdFrom = $_POST ['objIdFrom'];
		$objIdTo = $_POST ['objIdTo'];
/*		if ($objIdTo == 'DMSRooT') {
			$objIdTo = 0;
		}*/
//		logger::dump('objIdFrom',$objIdFrom);
		Logger::debug('To:'.$objIdTo);
		$arrObjId = Array();
		$dmsNode = new DmsObjectEntity ( );
		$deleteAction = false;
		
		$currentPerson = $sessionMgr->getCurrentAccountEntity();                   
		$arrObjId = explode(',', $objIdFrom);
		foreach ($arrObjId as $vObjId) {
//			if ($vObjId != 'recyclebin') {
				$sqlLoad = "f_obj_id = {$vObjId}";
//			} else {
//				$sqlLoad = "f_mark_delete = 1 and f_mark_delete_uid = {$_SESSION['accID']}";
//			}
				
			try {
				
				$dmsNode->Load ($sqlLoad);
				
//				Logger::dump('$objIdFrom',$objIdFrom . ' | pid:' . $dmsNode->f_obj_pid);
//				Logger::dump('$objIdTo',$objIdTo);
				if($dmsNode->f_obj_type == 1) {
					$nodeType = AuditLog::DOCUMENT;
				}
				
				if($dmsNode->f_obj_type == 0) {
					$nodeType = AuditLog::FOLDER;
				}
				
				// case empty recyclebin
				/*if ($objIdFrom == 'recyclebin' && $objIdTo == 'empty') {
//					Logger::debug(__FILE__.__LINE__);
					$dmsNode->f_delete = 1;
					$dmsNode->Update ();
				}*/
				
				// case delete from recyclebin
				if (($objIdTo == '' && $dmsNode->f_mark_delete == 1)) {
//					Logger::debug(__FILE__.__LINE__);
					//$dmsNode->Delete ();
					$dmsNode->f_delete = 1;
					$dmsNode->f_delete_uid = $_SESSION['accID'];
					$dmsNode->f_mark_delete = 2;
					$dmsNode->Update ();
					$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} delete (permanent) {$dmsNode->f_name} , id [{$dmsNode->f_obj_id}]";
					Logger::log($nodeType,$vObjId,$logMessage,true,false,AuditLog::ACTIVITY_DELETE_PERMANENT);
				}
				// case move from normal node to recyclebin
				if ($objIdTo == 'recyclebin' && $dmsNode->f_mark_delete == 0 || $objIdTo == '' && $dmsNode->f_mark_delete == 0) {
					
					$dmsNode->f_mark_delete = 1;
					$dmsNode->f_mark_delete_uid = $_SESSION['accID'];
					//$conn->debug = true;
					//$dmsNode->Update ();
					$sqlDelete = "update tbl_dms_object set f_mark_delete = '1' , f_mark_delete_uid = '{$_SESSION['accID']}' where f_obj_id = '{$vObjId}'";
					Logger::debug($sqlDelete);
					$conn->Execute($sqlDelete);
					$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} delete (recycle) {$dmsNode->f_name} , id [{$dmsNode->f_obj_id}]";
					Logger::log($nodeType,$vObjId,$logMessage,true,false,AuditLog::ACTIVITY_DELETE);
					$deleteAction = true;
				}
				// case move from normal node to my documents
				if ($objIdTo == 'MyDocRoot' && $dmsNode->f_in_mydoc == 0) {
//					Logger::debug(__FILE__.__LINE__);
					$dmsNode->f_owner_id = $_SESSION['accID'];
					$dmsNode->f_in_mydoc = 1;
//					$dmsNode->f_obj_pid = 0;
					$dmsNode->Update ();
					$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} Move  {$dmsNode->f_name} , id [{$dmsNode->f_obj_id}] to My Document";
					Logger::log($nodeType,$vObjId,$logMessage,true,false,AuditLog::ACTIVITY_MOVE_TO_MYDOC);
				}
				
				if ($objIdTo == 'MyDocRoot' && $dmsNode->f_in_mydoc == 1) {
//					Logger::debug(__FILE__.__LINE__);
					
					$dmsNode->f_owner_id = $_SESSION['accID'];
					$dmsNode->f_in_mydoc = 1;
					$dmsNode->f_obj_pid = 0;
					$dmsNode->Update ();
					$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} Move  {$dmsNode->f_name} , id [{$dmsNode->f_obj_id}] to My Document";
					Logger::log($nodeType,$vObjId,$logMessage,true,false,AuditLog::ACTIVITY_MOVE_TO_MYDOC);
				}
				// case move from my documents to normal node
				if ($objIdTo != 'MyDocRoot' && $dmsNode->f_in_mydoc == 1 && !$deleteAction) {
//					Logger::debug(__FILE__.__LINE__);
					$dmsToNode = new DmsObjectEntity();
					$dmsToNode->Load("f_obj_id = '{$objIdTo}'");
					if($dmsToNode->f_in_mydoc ==1) {
						$dmsNode->f_in_mydoc = 1;
					} else {
						$dmsNode->f_in_mydoc = 0;
					}
					
					$dmsNode->f_obj_pid = $objIdTo;
					$dmsNode->Update ();
					
					Logger::debug("MyDoc");

					$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} Move {$dmsNode->f_name} , id [{$dmsNode->f_obj_id}] to DMS";
					Logger::log($nodeType,$vObjId,$logMessage,true,false,AuditLog::ACTIVITY_MOVE_TO_DMS);
				} 
				// case move from normal node to normal node
				if ($objIdTo != 'recyclebin' && $objIdTo != '' && $dmsNode->f_mark_delete == 0) {
//					Logger::debug(__FILE__.__LINE__);
					$dmsNode->f_obj_pid = $objIdTo;
					$dmsNode->Update ();
					$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} Move {$dmsNode->f_name} , id [{$dmsNode->f_obj_id}] in DMS";
					Logger::log($nodeType,$vObjId,$logMessage,true,false,AuditLog::ACTIVITY_MOVE);
				}
				// case move from recyclebin to normal node
				if ($objIdTo != 'recyclebin' && $objIdTo != '' && $dmsNode->f_mark_delete == 1 && $objIdTo != 'restore' && $objIdTo != 'empty') {
//					Logger::debug(__FILE__.__LINE__);	
					$dmsNode->f_obj_pid = $objIdTo;
					$dmsNode->f_mark_delete = 0;
					$dmsNode->Update ();
					$dmsTarget = new DmsObjectEntity ( );
					$dmsTarget->Load("f_obj_id = '{$objIdTo}'");
					$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} restore {$dmsNode->f_name} , id [{$dmsNode->f_obj_id}] to [$dmsTarget->f_name] id [$dmsTarget->f_obj_id] ";
					Logger::log($nodeType,$vObjId,$logMessage,true,false,AuditLog::ACTIVITY_MOVE_RESTORE);
				}
				// case restore from recyclebin
				if ($objIdTo == 'restore') {
//					Logger::debug(__FILE__.__LINE__);
					$dmsNode->f_mark_delete = 0;
					$dmsNode->Update ();
					//$dmsTarget = new DmsObjectEntity ( );
					//$dmsTarget->Load("f_obj_id = '{$objIdTo}'");
					//$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} restore {$dmsNode->f_name} , id [{$dmsNode->f_obj_id}] to [$dmsTarget->f_name] id [$dmsTarget->f_obj_id] ";
					//Logger::log($nodeType,$vObjId,$logMessage,true,false,AuditLog::ACTIVITY_MOVE_RESTORE);
				}
				$result = array ('success' => 1 );
			} catch (Exception $e) {
				Logger::dump(__FILE__.__LINE__,$e->getMessage());
				//Logger::log('DMSObject', $vObjId, 'Object does not exist');
				$result = array ('fail' => 0 );
			}
		} // end for
		
		echo json_encode ( $result );
	}
	
	/**
	 * Publish เอกสาร
	 *
	 */
	public function publishNodeAction() {
		global $sessionMgr;
		
		$objId = $_POST ['objId'];
		$dmsObject = new DmsObjectEntity();
		
		$dmsObject->Load("f_obj_id = '{$objId}'");
		$dmsObject->f_published = 1;
		$dmsObject->f_publish_uid = $sessionMgr->getCurrentAccID();
		$dmsObject->f_publish_org_id = $sessionMgr->getCurrentOrgID();
		$dmsObject->f_published_role_id = $sessionMgr->getCurrentRoleID();
		
		$dmsObject->Update();
		
		Logger::debug($objId);
		
		$result = array ('success' => 1 );
		echo json_encode ( $result );
	}
	
	public function unpublishNodeAction() {
		global $sessionMgr;
		
		$objId = $_POST ['objId'];
		$dmsObject = new DmsObjectEntity();
		
		$dmsObject->Load("f_obj_id = '{$objId}'");
		$dmsObject->f_published =0;
		$dmsObject->f_publish_uid = $sessionMgr->getCurrentAccID();
		$dmsObject->f_publish_org_id = $sessionMgr->getCurrentOrgID();
		$dmsObject->f_published_role_id = $sessionMgr->getCurrentRoleID();
		
		$dmsObject->Update();
		
		Logger::debug($objId);
		
		$result = array ('success' => 1 );
		echo json_encode ( $result );
	}
	
	/**
	 * action /empty-recyclebin/  เคลียร์ Recycle Bin
	 *
	 */
	public function emptyRecyclebinAction() {
		global $conn;
		$sql = "update tbl_dms_object set f_delete=1, f_delete_uid={$_SESSION['accID']}, f_mark_delete=2 where f_mark_delete=1 and f_mark_delete_uid={$_SESSION['accID']}";
		try {
			$conn->Execute($sql);
			$result = array ('success' => 1 );
		} catch (Exception $e) {
			$result = array ('fail' => 0, 'message' => $e->getMessage());
		}
		echo json_encode ( $result );
	}
	
	/**
	 * action /request-aobject/ ขอข้อมูลเบื้องต้นของ DMS Object
	 *
	 */
	public function requestObjectAction() {
		$dmsNode = new DmsObjectEntity ( );		
		$objID = $_POST ['objID'];
		
		$dmsNode->Load ( "f_obj_id='{$objID}'" );
		
		//constructing the result
		$result = Array ('success' => 1, 'f_name' => $dmsNode->f_name, 'f_description' => $dmsNode->f_description, 'f_keyword' => $dmsNode->f_keyword);
		
		echo json_encode ( $result );
	}

	/**
	 * action /request-expired/ ขอรายการเอกสารที่หมดอายุ
	 *
	 */
	public function requestExpiredDocAction() {
		global $util;
		global $lang;
		
		$dmsNode = new DmsObjectEntity ( );

		if ($dmsNode->Load ( "f_obj_id='{$_GET ['objID']}'" )) {
			if ($dmsNode->f_expire_stamp == 0) {
				$expiredDate = $lang ['common'] ['default'];
			} else {
				$expiredDate = UTFEncode($util->getDateString($dmsNode->f_expire_stamp));
			}
			
			$result = Array ('success' => 1, 'f_expire_stamp' => $expiredDate);
		}
		echo json_encode ( $result );
	}
	
	/**
	 * action /input-document-expire/ บันทึกวันที่เอกสารหมดอายุ
	 *
	 */
	public function inputDocumentExpireAction() {
		global $lang;
		global $config;
		global $util;
		
//		logger::dump('post',$_POST);
		$dtpDocExpire = $_POST['dtpDocExpire'];
		$node = new DmsObjectEntity ( );
		$dmsUtil = new DMSUtil();
		
		try {
			$node->Load ( "f_obj_id={$_COOKIE['contextDMSObjectID']}" );
			if ($dtpDocExpire == $lang ['common'] ['default']){
				$dtpDocExpire = $util->dateAdd('yyyy', $config ['defaultDocExpire'], time());
			} else {
				$dtpDocExpire = $util->dateToStamp(UTFDecode($dtpDocExpire));
			}
			$node->f_expire_stamp = $dtpDocExpire;
			$node->Save ();
			
			$arrContactList = explode(',', $_POST['sendMailToHidden']);
			foreach ($arrContactList as $list) {
				list($contactType, $contactID) = explode('_', $list);
				switch ($contactType) {
					case 1:
						$uid = 0;
						$roleid = 0;
						$orgid = 0;
						break;
					case 2:
						$uid = 0;
						$roleid = $contactID;
						$orgid = 0;
						break;
					case 3:
						$uid = 0;
						$roleid = 0;
						$orgid = $contactID;
						break;
				}
				
				$notifier = new NotifierEntity();
				$dmsUtil->extractContactList($_POST['sendMailToHidden']);
				$notifier->f_notifier_task_type = 2;
				$notifier->f_notify_task_id = $_COOKIE['contextDMSObjectID'];
				$notifier->f_notify_task_sub_id = rand();
				$notifier->f_event = 0;
				$notifier->f_receiver_type = 0;
				$notifier->f_receiver_uid = $uid;
				$notifier->f_receiver_role_id = $roleid;
				$notifier->f_receiver_org_id = $orgid;
				$notifier->f_receiver_param = '';
				$notifier->f_notify_type = $_POST['cboNotifierType'];
				$notifier->f_notify_message = $lang ['dms']['mailNotifyMessage'] . ' : ' . $dmsUtil->getIndexLocationName($_COOKIE['contextDMSObjectID']) . ' : ' . UTFDecode($_POST['dtpDocExpire']);
				$notifier->Save();
			}
			
			$result = array ('success' => 1 );
		} catch (Exception $e) {
			Logger::dump(__FILE__.__LINE__,$e->getMessage());
			$result = array ('success' => 0 );
		}

		echo json_encode ( $result );
	}
	
	public function saveBorrowAction() {
		global $sequence;
		global $sessionMgr;
		global $util;
		
		if(!$sequence->isExists('borrowID')) {
			$sequence->create('borrowID');
		}
		
		$documentID = $_COOKIE['contextDocumentID'];
		$borrowRecord = new BorrowRecordEntity();
		$borrowRecord->f_borrow_id = $sequence->get('borrowID');
		$borrowRecord->f_borrow_uid = $sessionMgr->getCurrentAccID();
		$borrowRecord->f_borrower_uid = $_POST['lookupBorrowerHidden'];
		$borrowRecord->f_doc_id = $documentID;
		$borrowRecord->f_due_date = $util->dateToStamp(UTFDecode($_POST['dtpDueDate'])); 
		$borrowRecord->f_return_flag = 0;
		$borrowRecord->f_detail = UTFEncode($_POST['borrowDetail']);
		if(!$borrowRecord->Save()) {
			$result = array ('success' => 0 );
		} else {
		 
			$result = array ('success' => 1 );
		}
		echo json_encode ( $result );
	}
	
	/**
	 * action /restore-document-expire/ ทำการกู้คืนเอกสาร
	 *
	 */
	public function restoreDocumentExpireAction() {
		global $config;
		global $util;
		global $lang;
		
		$node = new DmsObjectEntity ( );
		
		$arrObjId = explode(',', $_GET['objID']);
		foreach ($arrObjId as $vObjId) {
			$sqlLoad = "f_obj_id = {$vObjId}";
				
			try {				
				$node->Load ($sqlLoad);
				$node->f_expire_stamp = $util->dateAdd('yyyy', $config ['defaultDocExpire'], time());
				$node->Save ();
				
				$result = array ('success' => 1 );
					
			} catch (Exception $e) {
				Logger::dump(__FILE__.__LINE__,$e->getMessage());
				$result = array ('success' => 0 );
			}
		}
		
		echo json_encode ( $result );
	}
}

?>