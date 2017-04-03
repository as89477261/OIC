<?php
/**
 * Class Document ÊÓËÃÑº¡ÒÃ´Óà¹Ô¹¡ÒÃàÍ¡ÊÒÃ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category DMS
 */
class Document {
	/**
	 * µÃÇ¨ÊÍº áÅĞÊÃéÒ§ Sequence "docID"
	 *
	 */
	public function __construct() {
		global $sequence;
		
		//include_once 'Form.Entity.php';
		//include_once 'FormStructure.Entity.php';
		//include_once 'DocMain.Entity.php';
		//include_once 'DocPage.Entity.php';
		//include_once 'DocPageTemp.Entity.php';
		//include_once 'DocValue.Entity.php';
		

		if (! $sequence->isExists ( 'docID' )) {
			$sequence->create ( 'docID' );
		}
	}
	
	/**
	 * µÃÇ¨ÊÍºàÍ¡ÊÒÃ«éÓã¹ÃĞºº
	 *
	 * @param int $docNo
	 * @param string $docDate
	 * @param string $docTitle
	 * @param string $docFrom
	 * @return boolean
	 */
	public function checkDuplicate($docNo, $docDate, $docTitle, $docFrom) {
		//global $config;
		global $conn;
		
		//$conn->debug = true;
		$docTitle = trim ( $conn->qstr ( $docTitle ) );
		$docDate = trim ( $docDate );
		$docFrom = trim ( $docFrom );
		//$sqlCheckDuplicate1 = "select count(*) from tbl_doc_main a where f_title = '{$docTitle}' and f_doc_date = '{$docDate}'";
		if ((trim ( $docNo ) != '' and ! is_null ( $docNo )) || $docNo != '(äÁèÁÕàÅ¢)') {
			$sqlCheckDuplicate1 = "select a.f_doc_id,b.f_trans_main_id from tbl_doc_main a,tbl_trans_master_df b where a.f_doc_no = '{$docNo}' and a.f_title = {$docTitle} and a.f_doc_date = '{$docDate}' and a.f_doc_id = b.f_doc_id";
		} else {
			$sqlCheckDuplicate1 = "select a.f_doc_id,b.f_trans_main_id from tbl_doc_main a,tbl_trans_master_df b where a.f_title = {$docTitle} and a.f_doc_date = '{$docDate}' and a.f_doc_id = b.f_doc_id";
		}
		
		$rsCheckDuplicate1 = $conn->Execute ( $sqlCheckDuplicate1 );
		$duplicate = false;
		while ( $tmpCheckdup = $rsCheckDuplicate1->FetchNextObject () ) {
			
			/*  comment by slc236 (ole)
				for filter org_id */

			$sqlCheckDuplicate2 = "select count(*) as COUNT_EXP from tbl_trans_df_recv,tbl_role where f_recv_trans_main_id = '{$tmpCheckdup->F_TRANS_MAIN_ID}' and f_send_fullname = '{$docFrom}' and f_accept_org_id = f_org_id and f_role_id = '".$_SESSION['roleID']."' ";

			$rsCheckDuplicate2 = $conn->Execute ( $sqlCheckDuplicate2 );
			$tmpCheckdup2 = $rsCheckDuplicate2->FetchNextObject ();
			if ($tmpCheckdup2->COUNT_EXP > 0) {
				$duplicate = true;
			}
		}

		error_log($sqlCheckDuplicate1);
		error_log($sqlCheckDuplicate2);
		error_log(serialize($_SESSION));

		return $duplicate;
	}
	
	/**
	 * ÊÃéÒ§àÍ¡ÊÒÃ¨Ò¡ POST Request
	 *
	 * @param string $formID
	 * @param boolean $hasTemp
	 * @param int $tempID
	 * @param boolean $createInDMS
	 * @param int $parentMode
	 * @param int $DMSParentID
	 */
	public function createFromPost($formID, $hasTemp = false, $tempID, $createInDMS = false, $parentMode = 'mydoc', $DMSParentID = 0, $customDocNo = '') {
		global $config;
		global $sequence;
		global $util;
		global $sessionMgr;
		
		$form = new FormEntity ( );
		$form->Load ( "f_form_id = '$formID'" );
		$formStructures = new FormStructureEntity ( );
		$structureArray = & $formStructures->Find ( "f_form_id = '$formID'" );
		$inputFilter = Array ();
		
		//Flag ÍÍ¡àÅ¢Ë¹Ñ§Ê×Í
		if (! array_key_exists ( 'genIntDocNo', $_POST )) {
			$genIntDocNo = 0;
		} else {
			if ($_POST ['genIntDocNo'] == 'on') {
				$genIntDocNo = 1;
			} else {
				$genIntDocNo = 0;
			}
		}
		if (! array_key_exists ( 'genExtDocNo', $_POST )) {
			$genExtDocNo = 0;
		} else {
			if ($_POST ['genExtDocNo'] == 'on') {
				$genExtDocNo = 1;
			} else {
				$genExtDocNo = 0;
			}
		}
		
		//¢ÍÍÍ¡ÃĞàºÕÂº
		if (! array_key_exists ( 'requestOrder', $_POST )) {
			$requestOrder = 0;
		} else {
			if ($_POST ['requestOrder'] == 'on') {
				$requestOrder = 1;
			} else {
				$requestOrder = 0;
			}
		}
		
		//¢ÍÍÍ¡»ÃĞ¡ÒÈ
		if (! array_key_exists ( 'requestCommand', $_POST )) {
			$requestCommand = 0;
		} else {
			if ($_POST ['requestCommand'] == 'on') {
				$requestCommand = 1;
			} else {
				$requestCommand = 0;
			}
		}
		
		//¢ÍÍÍ¡»ÃĞ¡ÒÈ
		if (! array_key_exists ( 'requestAnnounce', $_POST )) {
			$requestAnnounce = 0;
		} else {
			if ($_POST ['requestAnnounce'] == 'on') {
				$requestAnnounce = 1;
			} else {
				$requestAnnounce = 0;
			}
		}
		
		//1=äÁè¢ÍÍÍ¡àÅ¢(à¾ÃÒĞá¿Å¡ = 1 ¤×ÍÍÍ¡ä»áÅéÇ) , 0=¢ÍÍÍ¡àÅ¢
		if (! array_key_exists ( 'requestExtDocNo', $_POST )) {
			$requestExtDocNo = 1;
		} else {
			if ($_POST ['requestExtDocNo'] == 'on') {
				$requestExtDocNo = 0;
			} else {
				$requestExtDocNo = 1;
			}
		}
		
		if (! array_key_exists ( 'requestOther', $_POST )) {
			$requestOther = 0;
		} else {
			if ($_POST ['requestOther'] == 'on') {
				$requestOther = 1;
			} else {
				$requestOther = 0;
			}
		}
		
		$documentID = $sequence->get ( 'docID' );
		
		$arrayFieldToSave = array ();
		foreach ( $structureArray as $structure ) {
			//echo $structure->f_is_title;
			//echo $structure->f_struct_name;
			$inputFilter [$structure->f_struct_name] = FILTER_SANITIZE_STRING;
			if ($structure->f_is_title == 1) {
				$titleStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'title';
			}
			
			if ($structure->f_is_receiver_text == 1) {
				$receiverStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'receiver';
			}
			
			if ($structure->f_is_desc == 1) {
				$descStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'description';
			}
			//TODO: Not Implement Doc.Date Structure/Doc.No. Structure Yet
			if ($structure->f_is_doc_date == 1) {
				//$docDateStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'docdate';
			}
			if ($structure->f_is_doc_no == 1) {
				//$docNoStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'docno';
			}
			if ($structure->f_struct_name == 'atc_sender') {
				$receiverStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'sendFromExt';
			}
		
		}

		$formData = filter_input_array ( INPUT_POST, $inputFilter );
		$stampNow = time ();

		if (array_key_exists ( 'recvIntDocNo', $_POST )) {
			$title = $_POST ['recvIntTitle'] ;
			$docNo = $_POST ['recvIntDocNo'] ;
			$docDate = $_POST ['recvIntDocDate'] ;
			$refer = $_POST ['recvIntTitle'] ;
			$attach = $_POST ['recvIntTitle'] ;
			$sendFrom = $_POST ['recvIntTitle'] ;
			$sendTo = $_POST ['recvIntSendTo'] ;
			$Description = $_POST ['recvIntDesc'] ;
			$recvNo = $_POST ['recvIntRecvNo'] ;
			$regBookID = $_POST ['recvIntRegBookID'] ;
			$flagGenIntDocNo = 1;
			$flagGenExtDocNo = 1;
			$flagGenExtType = 1;
		} elseif (array_key_exists ( 'recvExtDocNo', $_POST )) {
			$title = $_POST ['recvExtTitle'] ;
			$docNo = $_POST ['recvExtDocNo'] ;
			$docDate = $_POST ['recvExtDocDate'] ;
			$refer = $_POST ['recvExtTitle'] ;
			$attach = $_POST ['recvExtTitle'] ;
			$sendFrom = $_POST ['recvExtTitle'] ;
			$sendTo = $_POST ['recvExtSendTo'] ;
			$Description = $_POST ['recvExtDesc'] ;
			$recvNo = $_POST ['recvExtRecvNo'] ;
			$regBookID = $_POST ['recvExtRegBookID'] ;
			$flagGenIntDocNo = 1;
			$flagGenExtDocNo = 1;
			$flagGenExtType = 1;
		} elseif (array_key_exists ( 'recvExtGlobalDocNo', $_POST )) {
			$title = $_POST ['recvExtGlobalTitle'] ;
			$docNo = $_POST ['recvExtGlobalDocNo'] ;
			$docDate = $_POST ['recvExtGlobalDocDate'] ;
			$refer = $_POST ['recvExtGlobalTitle'] ;
			$attach = $_POST ['recvExtGlobalTitle'] ;
			$sendFrom = $_POST ['recvExtGlobalTitle'] ;

			if ($_POST ['recvExtGlobalSendTo'] == 'Default') {
				$recvOrgID = $sessionMgr->getCurrentOrgID ();
				$organize = new OrganizeEntity ( );
				$organize->Load ( "f_org_id = '{$recvOrgID}'" );
				$sendTo = UTFEncode ( $organize->f_org_name );
			} else {
				$sendTo = $_POST ['recvExtGlobalSendTo'] ;
			}

			$sendFromExt = UTFDecode($_POST ['recvExtGlobalSendFrom']) ;
			$Description = $_POST ['recvExtGlobalDesc'] ;
			$recvNo = $_POST ['recvExtGlobalRecvNo'] ;
			$regBookID = $_POST ['recvExtGlobalRegBookID'] ;
			$flagGenIntDocNo = 1;
			$flagGenExtDocNo = 1;
			$flagGenExtType = 1;
		
		} elseif (array_key_exists ( 'sendIntGlobalDocNo', $_POST )) {
			$title = $_POST ['sendIntGlobalTitle'] ;
			$docNo = $_POST ['sendIntGlobalDocNo'] ;
			$docDate = $_POST ['sendIntGlobalDocDate'] ;
			$refer = $_POST ['sendIntGlobalTitle'] ;
			$attach = $_POST ['sendIntGlobalTitle'] ;
			$sendFrom = $_POST ['sendIntGlobalTitle'] ;
			$sendTo = $_POST ['sendIntGlobalSendTo'] ;
			$Description = $_POST ['sendIntGlobalDesc'] ;
			$recvNo = $_POST ['sendIntGlobalSendNo'] ;
			$regBookID = $_POST ['sendIntGlobalRegBookID'] ;
			$flagGenIntDocNo = $genIntDocNo;
			$flagGenExtDocNo = $requestExtDocNo;
			$flagGenExtType = 0;
		} elseif (array_key_exists ( 'sendIntDocNo', $_POST )) {
			$title = $_POST ['sendIntTitle'] ;
			$docNo = $_POST ['sendIntDocNo'] ;
			$docDate = $_POST ['sendIntDocDate'] ;
			$refer = $_POST ['sendIntTitle'] ;
			$attach = $_POST ['sendIntTitle'] ;
			$sendFrom = $_POST ['sendIntTitle'] ;
			$sendTo = $_POST ['sendIntSendTo'] ;
			$Description = $_POST ['sendIntDesc'] ;
			$recvNo = $_POST ['sendIntSendNo'] ;
			$regBookID = $_POST ['sendIntRegBookID'] ;
			$flagGenIntDocNo = $genIntDocNo;
			$flagGenExtDocNo = $requestExtDocNo;
			$flagGenExtType = 0;
		} elseif (array_key_exists ( 'sendExtDocNo', $_POST )) {
			$title = $_POST ['sendExtTitle'] ;
			$docNo = $_POST ['sendExtDocNo'] ;
			$docDate = $_POST ['sendExtDocDate'] ;
			$refer = $_POST ['sendExtTitle'] ;
			$attach = $_POST ['sendExtTitle'] ;
			$sendFrom = $_POST ['sendExtTitle'] ;
			$sendTo = $_POST ['sendExtSendTo'] ;
			$Description = $_POST ['sendExtDesc'] ;
			$recvNo = $_POST ['sendExtSendNo'] ;
			$regBookID = $_POST ['sendExtRegBookID'] ;
			$flagGenIntDocNo = 1;
			$flagGenExtDocNo = 1;
			$flagGenExtType = 1;
		} else {
			$title = $_POST ['sendExtGlobalTitle'] ;
			$docNo = $_POST ['sendExtGlobalDocNo'] ;
			$docDate = $_POST ['sendExtGlobalDocDate'] ;
			$refer = $_POST ['sendExtGlobalTitle'] ;
			$attach = $_POST ['sendExtGlobalTitle'] ;
			$sendFrom = $_POST ['sendExtGlobalTitle'] ;
			$sendTo = $_POST ['sendExtGlobalSendTo'] ;
			$Description = $_POST ['sendExtGlobalDesc'] ;
			$recvNo = $_POST ['sendExtGlobalSendNo'] ;
			$regBookID = $_POST ['sendExtGlobalRegBookID'] ;
			$flagGenIntDocNo = 1;
			$flagGenExtDocNo = 1;
			$flagGenExtType = 1;
		}

		/* Create Main Document */
		$docMain = new DocMainEntity ( );
		$docMain->f_doc_id = $documentID;
		$docMain->f_form_id = $formID;
		$title = UTFDecode ( $title );
		$docMain->f_title = $title;
		$Description = UTFDecode ( $Description );
		$docMain->f_description = $Description;
		//Update
		if (array_key_exists ( 'sendIntDocNo', $_POST )) {
			//$_POST['sendIntAttend'];
			$receiver = UTFDecode ( $_POST ['sendIntAttend'] );
		} else {
			$receiver = UTFDecode ( $sendTo );
		}
		$docNo = UTFDecode ( $docNo );
		if ($customDocNo == '') {
			$docMain->f_doc_no = $docNo;
		} else {
			$docMain->f_doc_no = $customDocNo;
		}
		
		$docDate = UTFDecode ( $docDate );
		if ($docDate == 'Default') {
			$docDate = $util->getDateString ();
			$docMain->f_doc_date = $docDate;
			$docMain->f_doc_realdate = $util->getTimestamp();
		} else {
			$docMain->f_doc_date = $docDate;
			$docMain->f_doc_realdate = $util->dateToStamp( $docDate );
		}
		
		$docMain->f_doc_stamp = $stampNow;
		$docMain->f_flowed = 0;
		$docMain->f_flow_type = 0;
		$docMain->f_create_stamp = $stampNow;
		$docMain->f_create_uid = $_SESSION ['accID'];
		$docMain->f_last_update_stamp = $stampNow;
		$docMain->f_last_update_user = $_SESSION ['accID'];
		$docMain->f_mark_delete = 0;
		$docMain->f_mark_delete_user = 0;
		$docMain->f_delete = 0;
		$docMain->f_doc_revision = 1;
		$docMain->f_delete_user = 0;
		$docMain->f_orphaned = 0;
		$docMain->f_status = 1;
		$docMain->f_gen_int_bookno = $flagGenIntDocNo;
		$docMain->f_gen_ext_bookno = $flagGenExtDocNo;
		$docMain->f_gen_ext_type = $flagGenExtType;
		$docMain->f_ref_gen_from_doc_id = 0;
		$docMain->f_request_order = $requestOrder;
		$docMain->f_request_command = $requestCommand;
		$docMain->f_request_announce = $requestAnnounce;
		if ($requestOther == 1) {
			$docMain->f_request_order = 1;
		}
		
		try {
			$docMain->Save ();
		} catch ( Exception $e ) {
			logger::dump ( 'sql', $e->getMessage () );
		}
		
		// Save Document Field
		foreach ( $structureArray as $structure ) {
			$docValue = new DocValueEntity ( );
			$docValue->f_doc_id = $documentID;
			$docValue->f_struct_id = $structure->f_struct_id;
			if (in_array ( $structure->f_struct_id, array_keys ( $arrayFieldToSave ) )) {
				$tokenID = $structure->f_struct_id;
				$token = $arrayFieldToSave [$tokenID];
				switch ($token) {
					case 'title' :
						$docValue->f_value = $title;
						break;
					case 'description' :
						$docValue->f_value = $Description;
						break;
					case 'docno' :
						if ($customDocNo == '') {
							$docValue->f_value = $docNo;
						} else {
							$docValue->f_value = $customDocNo;
						}
						break;
					case 'receiver' :
						$docValue->f_value = $receiver;
						break;
					case 'docdate' :
						$docValue->f_value = $docDate;
						break;
					case 'sendFromExt' :
						$docValue->f_value = $sendFromExt;
						break;
				}
			
			} else {
				$docValue->f_value = '';
			}
			$docValue->Save ();
			unset ( $docValue );
		}
		
		//Move Attachment to Storage
		//TODO: Move Attachment to Storage
		if ($hasTemp) {
			//include_once 'Storage.Entity.php';
			$defaultStorage = new StorageEntity ( );
			$defaultStorage->Load ( "f_default = '1'" );
			
			if ($defaultStorage->f_st_type == 0) {
				//include_once 'DAVStorage.php';
				$storage = new DAVStorage ( );
			}
			
			$uniqueFileID = uniqid ();
			$collectionName = substr ( $uniqueFileID, 0, 3 );
			$storage->connect ( $defaultStorage->f_st_server, $defaultStorage->f_st_uid, $defaultStorage->f_st_pwd, $defaultStorage->f_st_path );
			$storage->newCollection ( $collectionName );
			$docPageTemps = new DocPageTempEntity ( );
			$pageTempArray = & $docPageTemps->Find ( "f_doc_id = '$tempID'" );
			if (! $sequence->isExists ( "pageDoc{$documentID}" )) {
				$sequence->create ( "pageDoc{$documentID}" );
			}
			foreach ( $pageTempArray as $pageTemp ) {
				$tempFilePath = $config ['tempPath'] . "/{$_SESSION['accID']}/create/{$pageTemp->f_sys_file_name}.{$pageTemp->f_extension}";
				$storage->addFile ( $collectionName, $pageTemp->f_orig_file_name, file_get_contents ( $tempFilePath ) );
				$docPage = new DocPageEntity ( );
				$docPage->f_doc_id = $documentID;
				$docPage->f_page_no = $sequence->get ( "pageDoc{$documentID}" );
				$docPage->f_st_id = $defaultStorage->f_st_id;
				$docPage->f_mime_type = $pageTemp->f_mime_type;
				$docPage->f_orig_file_name = $pageTemp->f_orig_file_name;
				$docPage->f_sys_file_name = $pageTemp->f_sys_file_name;
				$docPage->f_extension = $pageTemp->f_extension;
				$docPage->f_file_size = $pageTemp->f_file_size;
				$docPage->f_moved_to_storage = 1;
				$docPage->f_fulltext_indexed = 0;
				$docPage->f_deleted = 0;
				$docPage->f_delete_user = 0;
				$docPage->f_major_version = 1;
				$docPage->f_minor_version = 0;
				$docPage->f_branch_version = 0;
				$docPage->Save ();
				unset ( $docPage );
			}
		}
		
		if ($createInDMS) {
			$parentID = $DMSParentID;
			if ($parentMode == 'mydoc') {
				// Create in My Document
				

				if (! $sequence->isExists ( 'myDocID' )) {
					$sequence->create ( 'myDocID' );
				}
				
				//include_once 'DmsMyObject.Entity.php';
				$node = new DmsMyObjectEntity ( );
				$node->f_myobj_id = $sequence->get ( 'myDocID' );
				if ($parentID == 'MyDocRoot') {
					$parentNode = 0;
				} else {
					$parentNode = $parentID;
				}
				$node->f_myobj_pid = $parentNode;
				$node->f_myobj_type = 4;
				$node->f_owner_id = $_SESSION ['accID'];
				$node->f_name = UTFDecode ( $formData [$titleStructure] );
				$node->f_description = UTFDecode ( $formData [$descStructure] );
				$node->f_keyword = UTFDecode ( str_replace ( null, '', $node->f_name . " " . $node->f_description ) );
				$node->f_location = '';
				$node->f_doc_id = $docMain->f_doc_id;
				$node->f_created_stamp = $stampNow;
				$node->f_mark_delete = 0;
				$node->f_delete = 0;
				$node->f_last_update_stamp = 0;
				$node->f_published = 0;
				$node->f_status = 1;
				$node->Save ();
			
			} else {
				// Create in DMS System
				if (! $sequence->isExists ( 'docID' )) {
					$sequence->create ( 'docID' );
				}
				
				//include_once 'DmsObject.Entity.php';
				$node = new DmsObjectEntity ( );
				$node->f_obj_id = $sequence->get ( 'docID' );
				if ($parentID == 'MyDocRoot') {
					$parentNode = 0;
				} else {
					$parentNode = $parentID;
				}
				$node->f_obj_pid = $parentNode;
				$node->f_obj_type = 4;
				
				$node->f_name = UTFDecode ( $formData [$titleStructure] );
				$node->f_description = UTFDecode ( $formData [$descStructure] );
				$node->f_keyword = UTFDecode ( str_replace ( null, '', $node->f_name . " " . $node->f_description ) );
				$node->f_location = '';
				$node->f_doc_id = $docMain->f_doc_id;
				$node->f_created_user = $_SESSION ['accID'];
				$node->f_created_stamp = $stampNow;
				$node->f_mark_delete = 0;
				$node->f_mark_delete_user = 0;
				$node->f_delete = 0;
				$node->f_delete_user = 0;
				$node->f_last_update_stamp = 0;
				$node->f_last_update_user = 0;
				$node->f_locked = 0;
				$node->f_password = '';
				$node->f_checkout = 0;
				$node->f_checkout_user = 0;
				$node->f_published = 0;
				$node->f_publish_user = 0;
				$node->f_owner_type = 3;
				$node->f_owner_id = $_SESSION ['accID'];
				$node->f_override = 0;
				$node->f_borrowed = 0;
				$node->f_orphaned = 0;
				$node->f_status = 1;
				$node->Save ();
				//$fp = fopen('d:/log.log','w+');
				//fwrite($fp,serialize($node));
				//fclose($fp);
				unset ( $node );
			}
		}
		return $documentID;
	}
	
	public function createDocument($title, $docNo, $docDate, $desc, $From, $to) {
		global $sequence;
		$flagGenIntDocNo = 1;
		$flagGenExtDocNo = 1;
		$flagGenExtType = 1;
		/***************************************************************/
		$stampNow = time ();
		$documentID = $sequence->get ( 'docID' );
		$docMain = new DocMainEntity ( );
		$docMain->f_doc_id = $documentID;
		$docMain->f_form_id = 1;
		$docMain->f_title = $title;
		$docMain->f_description = $desc;
		//Update
		$receiver = $to;
		$docMain->f_doc_no = $docNo;
		$docMain->f_doc_date = $docDate;
		$docMain->f_doc_realdate = $util->dateToStamp( $docDate );
		
		$docMain->f_doc_stamp = $stampNow;
		$docMain->f_flowed = 0;
		$docMain->f_flow_type = 0;
		$docMain->f_create_stamp = $stampNow;
		$docMain->f_create_uid = $_SESSION ['accID'];
		$docMain->f_last_update_stamp = $stampNow;
		$docMain->f_last_update_user = $_SESSION ['accID'];
		$docMain->f_mark_delete = 0;
		$docMain->f_mark_delete_user = 0;
		$docMain->f_delete = 0;
		$docMain->f_doc_revision = 1;
		$docMain->f_delete_user = 0;
		$docMain->f_orphaned = 0;
		$docMain->f_status = 1;
		$docMain->f_gen_int_bookno = $flagGenIntDocNo;
		$docMain->f_gen_ext_bookno = $flagGenExtDocNo;
		$docMain->f_gen_ext_type = $flagGenExtType;
		$docMain->f_ref_gen_from_doc_id = 0;
		$docMain->f_request_order = 0;
		$docMain->f_request_command = 0;
		$docMain->f_request_announce = 0;
		$docMain->f_request_order = 0;
		
		$docMain->Save ();
		
		$formStructures = new FormStructureEntity ( );
		$structureArray = & $formStructures->Find ( "f_form_id = '1'" );
		foreach ( $structureArray as $structure ) {
			$docValue = new DocValueEntity ( );
			$docValue->f_doc_id = $documentID;
			$docValue->f_struct_id = $structure->f_struct_id;
			$docValue->f_value = '';
			
			if ($structure->f_is_title == 1) {
				$docValue->f_value = $title;
			}
			
			if ($structure->f_is_receiver_text == 1) {
				$docValue->f_value = $to;
			}
			
			if ($structure->f_is_desc == 1) {
				$docValue->f_value = $desc;
			}
			
			//TODO: Not Implement Doc.Date Structure/Doc.No. Structure Yet
			if ($structure->f_is_doc_date == 1) {
				$docValue->f_value = $docDate;
			}
			
			if ($structure->f_is_doc_no == 1) {
				$docValue->f_value = $docNo;
			}
			
			$docValue->Save ();
			unset ( $docValue );
		}
		return $documentID;
	/***************************************************************/
	}
	/**
	 * ÊÃéÒ§àÍ¡ÊÒÃ¨Ò¡¡ÒÃ Import
	 *
	 * @param int $formID
	 * @param boolean $hasTemp
	 * @param int $tempID
	 * @param string $title
	 * @param string $docNo
	 * @param string $docDate
	 * @param string $refer
	 * @param string $attach
	 * @param string $sendFrom
	 * @param string $sendTo
	 * @param string $Description
	 * @param string $recvNo
	 * @param int $regBookID
	 * @param boolean $createInDMS
	 * @param string $parentMode
	 * @param int $DMSParentID
	 * @return int
	 */
	public function createImportDocument($formID, $hasTemp = false, $tempID, $title, $docNo, $docDate, $refer, $attach, $sendFrom, $sendTo, $Description, $recvNo, $regBookID, $createInDMS = false, $parentMode = 'mydoc', $DMSParentID = 0) {
		
		//global $config;
		global $sequence;
		global $util;
		
		$hasTemp = false;
		$tempID = 0;
		
		$form = new FormEntity ( );
		$form->Load ( "f_form_id = '$formID'" );
		$formStructures = new FormStructureEntity ( );
		$structureArray = & $formStructures->Find ( "f_form_id = '$formID'" );
		$inputFilter = Array ();
		
		$arrayFieldToSave = array ();
		foreach ( $structureArray as $structure ) {
			//echo $structure->f_is_title;
			//echo $structure->f_struct_name;
			$inputFilter [$structure->f_struct_name] = FILTER_SANITIZE_STRING;
			if ($structure->f_is_title == 1) {
				$titleStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'title';
			}
			
			if ($structure->f_is_receiver_text == 1) {
				$receiverStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'receiver';
			}
			
			if ($structure->f_is_desc == 1) {
				$descStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'description';
			}
			//TODO: Not Implement Doc.Date Structure/Doc.No. Structure Yet
			if ($structure->f_is_doc_date == 1) {
				//$docDateStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'docdate';
			}
			if ($structure->f_is_doc_no == 1) {
				//$docNoStructure = $structure->f_struct_name;
				$filedID = $structure->f_struct_id;
				$arrayFieldToSave [$filedID] = 'docno';
			}
		
		}
		
		//Flag ÍÍ¡àÅ¢Ë¹Ñ§Ê×Í
		$genIntDocNo = 0;
		$genExtDocNo = 0;
		$requestOrder = 0;
		$requestCommand = 0;
		$requestAnnounce = 0;
		//1=äÁè¢ÍÍÍ¡àÅ¢(à¾ÃÒĞá¿Å¡ = 1 ¤×ÍÍÍ¡ä»áÅéÇ) , 0=¢ÍÍÍ¡àÅ¢     
		$requestExtDocNo = 1;
		$requestOther = 0;
		
		$documentID = $sequence->get ( 'docID' );
		
		$formData = filter_input_array ( INPUT_POST, $inputFilter );
		$stampNow = time ();
		
		$flagGenIntDocNo = 1;
		$flagGenExtDocNo = 1;
		$flagGenExtType = 1;
		
		/* Create Main Document */
		$docMain = new DocMainEntity ( );
		$docMain->f_doc_id = $documentID;
		$docMain->f_form_id = $formID;
		//$title = UTFDecode ( $title );
		$docMain->f_title = $title;
		//$Description = UTFDecode ( $Description );
		$docMain->f_description = $Description;
		$receiver = $sendTo;
		//$docNo = UTFDecode ( $docNo );
		if ($customDocNo == '') {
			$docMain->f_doc_no = $docNo;
		} else {
			$docMain->f_doc_no = $customDocNo;
		}
		
		//$docDate = UTFDecode ( $docDate );
		if ($docDate == 'Default') {
			$docDate = $util->getDateString ();
			$docMain->f_doc_date = $docDate;
			$docMain->f_doc_realdate = $util->getTimestamp();
		} else {
			$docMain->f_doc_date = $docDate;
			$docMain->f_doc_realdate = $util->dateToStamp( $docDate );
		}
		
		$docMain->f_doc_stamp = $stampNow;
		$docMain->f_flowed = 0;
		$docMain->f_flow_type = 0;
		$docMain->f_create_stamp = $stampNow;
		$docMain->f_create_user = 0;
		$docMain->f_last_update_stamp = $stampNow;
		$docMain->f_last_update_user = 0;
		$docMain->f_mark_delete = 0;
		$docMain->f_mark_delete_user = 0;
		$docMain->f_delete = 0;
		$docMain->f_doc_revision = 1;
		$docMain->f_delete_user = 0;
		$docMain->f_orphaned = 0;
		$docMain->f_status = 1;
		$docMain->f_gen_int_bookno = $flagGenIntDocNo;
		$docMain->f_gen_ext_bookno = $flagGenExtDocNo;
		$docMain->f_gen_ext_type = $flagGenExtType;
		$docMain->f_ref_gen_from_doc_id = 0;
		$docMain->f_request_order = $requestOrder;
		$docMain->f_request_command = $requestCommand;
		$docMain->f_request_announce = $requestAnnounce;
		if ($requestOther == 1) {
			$docMain->f_request_order = 1;
		}
		
		try {
			$docMain->Save ();
		} catch ( Exception $e ) {
			logger::dump ( 'sql', $e->getMessage () );
		}
		
		// Save Document Field
		foreach ( $structureArray as $structure ) {
			$docValue = new DocValueEntity ( );
			$docValue->f_doc_id = $documentID;
			$docValue->f_struct_id = $structure->f_struct_id;
			if (in_array ( $structure->f_struct_id, array_keys ( $arrayFieldToSave ) )) {
				$tokenID = $structure->f_struct_id;
				$token = $arrayFieldToSave [$tokenID];
				switch ($token) {
					case 'title' :
						$docValue->f_value = $title;
						break;
					case 'description' :
						$docValue->f_value = $Description;
						break;
					case 'docno' :
						if ($customDocNo == '') {
							$docValue->f_value = $docNo;
						} else {
							$docValue->f_value = $customDocNo;
						}
						break;
					case 'receiver' :
						$docValue->f_value = $receiver;
						break;
					case 'docdate' :
						$docValue->f_value = $docDate;
						break;
				}
			} else {
				$docValue->f_value = '';
			}
			$docValue->Save ();
			unset ( $docValue );
		}
		return $documentID;
	}
	
	/**
	 * ·Ó¡ÒÃ Backup History ¢Í§àÍ¡ÊÒÃ
	 *
	 * @param int $docID
	 */
	
	public function backupHistory($docID) {
	}
	
	/**
	 * ¢Í¢éÍ¤ÇÒÁÊ¶Ò¹Ğ¢Í§àÍ¡ÊÒÃ
	 *
	 * @param int $checkout
	 * @return string
	 */
	public function getDocumentStatus($checkout) {
		global $lang;
		
		switch ($checkout) {
			case 0 :
				$result = $lang ['dms'] ['baselineStatus'];
				break;
			case 1 :
				$result = $lang ['dms'] ['workingStatus'];
				break;
		}
		return $result;
	}
	
	/**
	 * ¢Íª×èÍ¼Ùéãªé
	 *
	 * @param int $accID
	 * @return string
	 */
	public function getAccountName($accID) {
		//include_once 'Account.Entity.php';
		$acc = new AccountEntity ( );
		try {
			$acc->Load ( "f_acc_id = {$accID}" );
			$result = $acc->f_name . '  ' . $acc->f_last_name . ' (' . $acc->f_login_name . ')';
		} catch ( Exception $e ) {
			$result = '';
			//Logger::dump('error detail', __FILE__.__LINE__.$e->getMessage());
		}
		return $result;
	}
}
