<?php
/**
 * โปรแกรมประมวลผลงานสารบรรณ
 * 
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 */

class DFActionController extends Zend_Controller_Action {
	/**
	 * Initializer
	 */
	public function init() {
		//include_once 'Account.Entity.php';
		//include_once 'Passport.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Organize.Entity.php';
		//include_once 'Order.Entity.php';
		//include_once 'DFTransaction.php';
	}
	
	public function reserveExtGlobalAction() {
		$docID = $_POST['docID'];
		/*
		$reserveMode = false;
		if (array_key_exists ( 'reserveExt', $_POST ) && $_POST ['reserveExt'] == 'on') {
			include_once 'ReserveNo.php';
			$reserveMode = true;
			$reserver = new ReserveNo ( );
			$response2 = $reserver->doReserve ( 0, false, $docID );
		}
		
		if (array_key_exists ( 'reserveExtGlobal', $_POST ) && $_POST ['reserveExtGlobal'] == 'on') {
			include_once 'ReserveNo.php';
			$reserveMode = true;
			$reserver = new ReserveNo ( );
			$response2 = $reserver->doReserve ( 1, false, $docID );
		}
		*/
		$reserver = new ReserveNo ( );
		$response2 = $reserver->doReserve ( 1, false, $docID );
		$response = array();
		$response['success'] = 1;
		$response ['reserve'] = 1;
		$response ['bookNoR'] = $response2 ['bookNo'];
		$response ['regNoR'] = $response2 ['regNo'];
		echo json_encode($response);
	}

	/**
	 * action /reserve-request/ ทำการจองเลข
	 *
	 */
	function reserveRequestAction() {
		//include_once 'ReserveNo.php';
		$reserver = new ReserveNo ( );
		if ($_POST ['hiddenReserveRegBookType'] == 'SE') {
			$reserveType = 0;
		} else {
			$reserveType = 1;
		}
		echo json_encode ( $reserver->doReserve ( $reserveType, false, 0 ) );
	}
	
	/**
	 * action /recv-same-regno/ ทำการลงรับเลขเดิม
	 *
	 */
	public function recvSameRegnoAction() {
		global $conn;
		global $sessionMgr;
		//global $sequence;
		
		global $util;
		
		$response = Array ();
		$response ['success'] = 1;
		
		//$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		//print $cb . '(' . json_encode ( $response ) . ')';
		//die();
		

		//require_once 'DFTransaction.php';
		//require_once 'TransMasterDf.Entity.php';
		//require_once 'Notifier.php';
		//require_once 'DocMain.Entity.php';
		
		$notifier = new Notifier ( );
		$conn->StartTrans ();
		if (! array_key_exists ( 'sendID', $_POST )) {
			//not found sendRecordID
		} else {
			
			$sendRecordID = $_POST ['sendID'];
			$docID = $_POST ['docID'];
			
			list ( $sendTransMainID, $sendTransMainSeq, $sendID ) = explode ( "_", $sendRecordID );
			
			$conn->RowLock ( "tbl_trans_df_send", "f_send_trans_main_id = '{$sendTransMainID}' and  f_send_trans_main_seq = '{$sendTransMainSeq}' and f_received = 0" );
			
			$sqlCheckUnreceived = "select count(*) as COUNT_EXP from tbl_trans_df_send where f_send_trans_main_id = '{$sendTransMainID}' and   f_send_trans_main_seq = '{$sendTransMainSeq}' and f_received = 0";
			$rsCheckUnreceived = $conn->Execute ( $sqlCheckUnreceived );
			$tmpCheckUnreceived = $rsCheckUnreceived->FetchNextObject ();
			if ($tmpCheckUnreceived->COUNT_EXP > 0) {
				
				$sqlGetType = "select f_recv_type as F_RECV_TYPE,f_doc_recv_type as F_DOC_RECV_TYPE from tbl_trans_df_recv a,tbl_trans_master_df b ";
				$sqlGetType .= "where a.f_recv_trans_main_id = b.f_trans_main_id and b.f_doc_id = '{$docID}' and a.f_receiver_org_id = '{$sessionMgr->getCurrentOrgID()}'";
				
				$rsGetType = $conn->Execute ( $sqlGetType );
				$tmpRsGetType = $rsGetType->FetchNextObject ();
				
				$receiveType = $tmpRsGetType->F_RECV_TYPE;
				$acceptDocType = $tmpRsGetType->F_DOC_RECV_TYPE;
				$docDeliverType = 1;
				$regBookID = 0;
				$recvRegNo = $_POST ['regNo'];
				$recvDate = 'Default';
				$recvTime = 'Default';
				$dfTrans = new DFTransaction ( );
				$parentTransaction = $dfTrans->getSendTransaction ( $sendTransMainID, $sendTransMainSeq );
				$transMaster = new TransMasterDfEntity ( );
				$transMaster->Load ( "f_trans_main_id = '{$sendTransMainID}'" );
				$document = new DocMainEntity ( );
				$document->Load ( "f_doc_id = '$transMaster->f_doc_id'" );
				if ($transMaster->f_security_modifier > 0) {
					$receiveType = 4;
				}
				$sendName = UTFEncode ( $util->parseFullname ( $parentTransaction ['f_sender_uid'], $parentTransaction ['f_sender_role_id'], $parentTransaction ['f_sender_org_id'] ) );
				$sendOrg = "";
				$attendName = UTFEncode ( $util->parseFullname ( $parentTransaction ['f_receiver_uid'], $parentTransaction ['f_receiver_role_id'], $parentTransaction ['f_receiver_org_id'] ) );
				$attendOrg = "";
				
				$response = $dfTrans->createReceiveTransaction ( $sendTransMainID, $receiveType, $docDeliverType, 1, $regBookID, $recvRegNo, 0, 0, 0, 0, 0, $recvDate, $recvTime, $sendName, $sendOrg, $attendName, $attendOrg, $parentTransaction ['f_sender_uid'], $parentTransaction ['f_sender_role_id'], $parentTransaction ['f_sender_org_id'], $acceptDocType, $parentTransaction ['f_receiver_uid'], $parentTransaction ['f_receiver_role_id'], $parentTransaction ['f_receiver_org_id'] );
				//$response ['recvType'] = $receiveType;
				if ($response ['success'] == 1) {
					$organizeSender = new OrganizeEntity ( );
					$organizeSender->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
					$notifier->notifyOrganize ( $sessionMgr->getCurrentOrgID (), "มีการลงรับหนังสือเลขที่ {$document->f_doc_no} จาก {$organizeSender->f_org_name} เรื่อง \"{$document->f_title}\" (เลขรับ{$response['recvType']} ที่{$response['regNo']})" );
					$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_received', 1 );
					$dfTrans->modifyReceiveTransaction ( $sendTransMainID, $response ['sequence'], 'f_forwarded_trans', 1 );
					$dfTrans->modifyReceiveTransaction ( $sendTransMainID, $response ['sequence'], 'f_send_id', $sendTransMainID );
					$dfTrans->modifyReceiveTransaction ( $sendTransMainID, $response ['sequence'], 'f_send_seq', $sendTransMainSeq );
				}
			} else {
				$response = Array ();
				$response ['success'] = 0;
				$response ['message'] = UTFEncode ( 'มีการลงรับไปแล้ว!!!' );
				//echo json_encode($response);
			}
			//echo json_encode ( $response );
		}
		//die();
		$conn->CompleteTrans ();
		$response ['success'] = 1;
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '(' . json_encode ( $response ) . ')';
	}
	
	/**
	 * action /check-recv-dup/ ตรวจสอบการลงรับซ้ำเรื่องเดิม
	 *
	 */
	public function checkRecvDupAction() {
		/**
		 * Remark ไม่ได้ Check ในกรณีรับเรื่อง 2 ทะเบียนเพราะจะเอาที่ทะเบียนแรกที่เจอเสมอ
		 */
		global $conn;
		global $sessionMgr;
		
		$response = Array ();
		
		$docID = $_POST ['docID'];
		
		$sqlCheckDup = "select count(*) as COUNT_EXP from tbl_trans_df_recv a,tbl_trans_master_df b ";
		$sqlCheckDup .= "where a.f_recv_trans_main_id = b.f_trans_main_id and b.f_doc_id = '{$docID}' and a.f_receiver_org_id = '{$sessionMgr->getCurrentOrgID()}'";
		
		$rsCheckDup = $conn->Execute ( $sqlCheckDup );
		$tmpCountDup = $rsCheckDup->FetchNextObject ();
		$countDup = $tmpCountDup->COUNT_EXP;
		
		if ($countDup > 0) {
			$sqlRegno = "select a.f_recv_reg_no as REG_NO from tbl_trans_df_recv a,tbl_trans_master_df b ";
			$sqlRegno .= "where a.f_recv_trans_main_id = b.f_trans_main_id and b.f_doc_id = '{$docID}' and a.f_receiver_org_id = '{$sessionMgr->getCurrentOrgID()}'";
			$rsRegno = $conn->Execute ( $sqlRegno );
			$tmpRsRegno = $rsRegno->FetchNextObj ();
			
			$response ['duplicate'] = 1;
			$response ['regNo'] = $tmpRsRegno->REG_NO;
		} else {
			$response ['duplicate'] = 0;
			$response ['regNo'] = 0;
		}
		
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '(' . json_encode ( $response ) . ')';
	}
	
	/**
	 * action /gen-bookno-int/ การออกเลขภายใน
	 *
	 */
	public function genBooknoIntAction() {
		global $sessionMgr;
		global $config;
		global $sequence;
		
		$id = $_POST ['id'];
		$type = $_POST ['type'];
		$response = Array ();
		$response ['success'] = 1;
		
		list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $id );
		//include_once 'TransMasterDf.Entity.php';
		//include_once 'TransDfRecv.Entity.php';
		//include_once 'TransDfSend.Entity.php';
		//include_once 'DocMain.Entity.php';
		//include_once 'DocValue.Entity.php';
		//include_once 'Organize.Entity.php';
		//include_once 'FormUtil.php';
		
		$org = new OrganizeEntity ( );
		
		$transMaster = new TransMasterDfEntity ( );
		$transMaster->Load ( "f_trans_main_id = '{$transMainID}'" );
		$transRecv = new TransDfRecvEntity ( );
		$transRecv->Load ( "f_recv_trans_main_id = '{$transMainID}' and f_recv_trans_main_seq = '{$transMainSeq}' and f_recv_id = '{$transID}'" );
		$transRecv->Load ( "f_recv_trans_main_id = '{$transMainID}' and f_recv_trans_main_seq = '{$transMainSeq}' and f_recv_id = '{$transID}'" );
		$transSend = New TransDfSendEntity ( );
		$transSend->Load ( "f_send_trans_main_id = '{$transRecv->f_send_id}' and f_send_trans_main_seq = '{$transRecv->f_send_seq}'" );
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$transMaster->f_doc_id}'" );
		$ownerOrgID = $transMaster->f_owner_org_id;
		$ownerOrg = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
		$ownerOrg->Load ( "f_org_id = '$ownerOrgID'" );
		switch ($type) {
			case 1 :
				//$org->Load ( "f_org_id = '$ownerOrgID'" );
				$docCode = $ownerOrg->f_int_code;
				$response ['message'] = UTFEncode ( 'ออกเลขหนังสือโดยใช้เลขหน่วยงานต้นเรื่อง [' . $ownerOrg->f_org_name . ']' );
				break;
			default :
			case 2 :
				//$org->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
				$docCode = $org->f_int_code;
				$response ['message'] = UTFEncode ( 'ออกเลขหนังสือโดยใช้เลขหน่วยงาน[' . $org->f_org_name . ']' );
				break;
		}
		
		if (! $sequence->isExists ( $docCode )) {
			$sequence->create ( $docCode );
		}

		
		if ($transSend->f_is_circ) {
			$docRunner = "RunIntCirc.{$sessionMgr->getCurrentYear()}.{$docCode}";
		} else {
			$docRunner = "RunInt.{$sessionMgr->getCurrentYear()}.{$docCode}";
		}

		if (! $sequence->isExists ( $docRunner )) {
			$sequence->create ( $docRunner );
		}
		
		if ($transSend->f_is_circ == 1) {
			$customDocNo = $ownerOrg->f_int_code . '/' . $config ['circDocIdentifier'] . $sequence->get ( $docRunner );
			//$customDocNo = $ownerOrg->f_int_code . '/' . $config ['circDocIdentifier'] . $sequence->get ( $docCode );
		} else {
			$customDocNo = $ownerOrg->f_int_code . '/' . $sequence->get ( $docRunner );
			//$customDocNo = $org->f_int_code . '/' . $sequence->get ( $docCode );
		}
		
		$formUtil = FormUtil::getInstance ();
		$docNoStructureID = $formUtil->getDocnoStructure ( $docMain->f_form_id );
		if (! $docNoStructureID) {
		
		} else {
			$docValue = new DocValueEntity ( );
			$docValue->Load ( "f_doc_id = '{$docMain->f_doc_id}' and f_struct_id = '{$docNoStructureID}'" );
			$docValue->f_value = $customDocNo;
			$docValue->Update ();
		
		}
		$docMain->f_doc_no = $customDocNo;
		$docMain->f_gen_int_bookno = 1;
		$docMain->Update ();
		
		$response ['bookno'] = $customDocNo;
		
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '(' . json_encode ( $response ) . ')';
	}
	
	/**
	 * action /receive-internal/ การลงรับภายใน
	 *
	 */
	public function receiveInternalAction() {
		//global $sequence;
		global $sessionMgr;
		//global $util;
		
		/*checkSessionJSON();*/
		
		//require_once 'Document.php';
		//require_once 'DFTransaction.php';
		//require_once 'Notifier.php';
		//require_once 'Account.Entity.php';
		///require_once 'Organize.Entity.php';
		//require_once 'DocMain.Entity.php';
		
		$notifier = new Notifier ( );
		
		$docCreator = new Document ( );
		$formID = $_POST ['recvIntFormID'];
		$hasTempPage = false;
		$tempID = 0;
		$createInDMS = false;
		$parentMode = 'mydoc';
		$DMSParentID = 0;
		$docDeliverType = $_POST ['recvIntDeliverType'];
		$regBookID = $_POST ['recvIntRegBookID'];
		$recvRegNo = $_POST ['recvIntRecvNo'];
		$recvDate = $_POST ['recvIntRecvDate'];
		$recvTime = $_POST ['recvIntRecvTime'];
		$sendName = $_POST ['recvIntSendFrom'];
		$sendOrg = '';
		$attendName = $_POST ['recvIntSendTo'];
		$attengOrg = '';
		
		$securityModifier = $_POST ['recvIntSecretLevel'];
		$speedModifier = $_POST ['recvIntSpeedLevel'];
		
		if ($securityModifier > 0) {
			if ($sessionMgr->getCurrentOrgID() == 374) {
			$originTransType = 'RS';
			$recvType = 4;
		} else {
				$originTransType = 'RSI';
				$recvType = 6;
			}
		} else {
			$originTransType = 'RI';
			$recvType = 1;
		}
		
		$title = $_POST ['recvIntTitle'];
		$docNo = $_POST ['recvIntDocNo'];
		$docDate = $_POST ['recvIntDocDate'];
		$sendFrom = $sendName;
		
		if ($docCreator->checkDuplicate ( UTFDecode ( $docNo ), UTFDecode ( $docDate ), UTFDecode ( $title ), UTFDecode ( $sendFrom ) )) {
			$response = Array ();
			$response ['success'] = 0;
			$response ['duplicate'] = 1;
			echo json_encode ( $response );
			die ();
		}
		
		$docID = $docCreator->createFromPost ( $formID, $hasTempPage, $tempID, $createInDMS, $parentMode, $DMSParentID );
		
		$transCreator = new DFTransaction ( );
		
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
		
		$transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
		$response = $transCreator->createReceiveTransaction ( $transID, $recvType, $docDeliverType, 1, $regBookID, $recvRegNo, 0, 0, 0, 0, 0, $recvDate, $recvTime, $sendName, $sendOrg, $attendName, $attengOrg, 0, 0, 0, 1, $sessionMgr->getCurrentAccID (), $sessionMgr->getCurrentRoleID (), $sessionMgr->getCurrentOrgID () );
		
		
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		//$response ['regno'] = UTFEncode ( $firstRegno );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		$notifier->notifyOrganize ( $sessionMgr->getCurrentOrgID (), "มีการลงรับหนังสือเลขที่ {$document->f_doc_no} เรื่อง \"{$document->f_title}\" จากหน่วยงาน {$sendName}" );
		echo json_encode ( $response );
	
	}
	
	/**
	 * action /report-order/ ทำการรายงานผลกลับ
	 *
	 */
	public function reportOrderAction() {
		//global $conn;
		global $util;
		//global $sessionMgr;
		
		//require_once 'Account.Entity.php';
		//require_once 'TransMasterDf.Entity.php';
		//require_once 'DocMain.Entity.php';
		//include_once 'Order.Entity.php';
		
		
		$response = Array ();
		$order = new OrderEntity ( );
		$orderID = $_POST ['reportOrderRefID'];
		$reportText = $_POST ['reportText'];
		if (! $order->Load ( "f_order_id = '{$orderID}'" )) {
			$response ['success'] = 0;
		} else {
			$accountReceiver = new AccountEntity ( );
			$accountReceiver->Load ( "f_acc_id = '$order->f_assign_uid'" );
			$accountSender = new AccountEntity ( );
			$accountSender->Load ( "f_acc_id = '$order->f_received_uid'" );
			$transMaster = new TransMasterDfEntity ( );
			$transMaster->Load ( "f_trans_main_id = '$order->f_trans_main_id'" );
			$document = new DocMainEntity ( );
			$document->Load ( "f_doc_id = '{$transMaster->f_doc_id}'" );
			$util->sendJabberNotifier ( $accountReceiver->f_login_name, "คุณได้รับรายงานจากการมอบหมายหนังสือเลขที่ {$document->f_doc_no} เรื่อง \"{$document->f_title}\" ที่มอบให้กับ {$accountSender->f_name} {$accountSender->f_last_name}" );
			$order->f_complete = 1;
			$order->f_complete_2 = 0;
			$order->f_dismiss_assign_complete = 0;
			$order->f_dismiss_recv_complete = 0;
			$order->f_close_timestamp = time ();
			$order->f_report_text = UTFDecode ( $reportText );
			$order->Update ();
			$response ['success'] = 1;
		}
		echo json_encode ( $response );
	}
	
	/**
	 * action /receive-external/ ทำการลงรับภายนอก
	 *
	 */
	public function receiveExternalAction() {
		global $config;
		global $sequence;
		global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		//require_once 'Document.php';
		//require_once 'DFTransaction.php';
		
		$docCreator = new Document ( );
		$formID = $_POST ['recvExtFormID'];
		$hasTempPage = false;
		$tempID = 0;
		$createInDMS = false;
		$parentMode = 'mydoc';
		$DMSParentID = 0;
		$docDeliverType = $_POST ['recvExtDeliverType'];
		$regBookID = $_POST ['recvExtRegBookID'];
		$recvRegNo = $_POST ['recvExtRecvNo'];
		$recvDate = $_POST ['recvExtRecvDate'];
		$recvTime = $_POST ['recvExtRecvTime'];
		$sendName = $_POST ['recvExtSendFrom'];
		$sendOrg = '';
		$attendName = $_POST ['recvExtSendTo'];
		$attengOrg = '';
		
		$title = $_POST ['recvExtTitle'];
		$docNo = $_POST ['recvExtDocNo'];
		$docDate = $_POST ['recvExtDocDate'];
		$sendFrom = $sendName;
		
		if ($docCreator->checkDuplicate ( UTFDecode ( $docNo ), UTFDecode ( $docDate ), UTFDecode ( $title ), UTFDecode ( $sendFrom ) )) {
			$response = Array ();
			$response ['success'] = 0;
			$response ['duplicate'] = 1;
			echo json_encode ( $response );
			die ();
		}
		
		$securityModifier = $_POST ['recvExtSecretLevel'];
		$speedModifier = $_POST ['recvExtSpeedLevel'];
		if ($securityModifier > 0) {
			if ($sessionMgr->getCurrentOrgID() == 374) {
			$originTransType = 'RS';
			$recvType = 4;
		} else {
				$originTransType = 'RSE';
				$recvType = 7;
			}
		} else {
			$originTransType = 'RE';
			$recvType = 2;
		}
		
		$docID = $docCreator->createFromPost ( $formID, $hasTempPage, $tempID, $createInDMS, $parentMode, $DMSParentID );
		
		$transCreator = new DFTransaction ( );
		//$transCreator->externalOrg ( UTFEncode ( $sendName ) );
		$sendName = trim($sendName);
		$transCreator->externalOrg ( UTFDecode ( $sendName ),UTFDecode ( $sendName ) );
		$transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
		$response = $transCreator->createReceiveTransaction ( $transID, $recvType, $docDeliverType, 1, $regBookID, $recvRegNo, 0, 0, 0, 0, 0, $recvDate, $recvTime, $sendName, $sendOrg, $attendName, $attengOrg, 0, 0, 0, 2, $sessionMgr->getCurrentAccID (), $sessionMgr->getCurrentRoleID (), $sessionMgr->getCurrentOrgID () );
		
		//Load Created Document
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
			
		//ลงรับ และ Forward Transaction ทันที
		if (array_key_exists ( 'recvExtForwardToHidden', $_POST ) && trim ( $_POST ['recvExtForwardToHidden'] ) != '') {
			
			if($config['autoApproveReceiveExternal']) {
				list ( $recvTransMainID, $recvTransMainSeq, $recvSubtransID ) = explode ( "_", $response['transID'] );
				$updateRecvTrans = new TransDfRecvEntity();
				if(!$updateRecvTrans->Load("f_recv_trans_main_id= '{$recvTransMainID}' and f_recv_trans_main_seq='{$recvTransMainSeq}' and f_recv_id='{$recvSubtransID}'")) {
					//Do nothing
				} else {
					Logger::debug('auto approve');
					$updateRecvTrans->f_governer_approve=1;
					$updateRecvTrans->Update();
				}
				
			}
			
			$notifier = new Notifier ( );
			$orgID = $sessionMgr->getCurrentOrgID ();
			$isCirc = 0;
			
			//การส่งต่อจะไม่แสดงในทะเบียนเลย
			$showInRegbook = 0;
			
			$forwardTransToHidden = $_POST ['recvExtForwardToHidden'];
			$forwardDocTransID = $transID;
			
			//$subtransID เป็นของ Receive Transaction
			list ( $transMainID, $transMainSeq, $subtransID ) = explode ( "_", $forwardDocTransID );
			$receiverList = Array ();
			$receiverList = explode ( ",", $forwardTransToHidden );
			
			$senderOrgID = $sessionMgr->getCurrentOrgID ();
			$senderRoleID = $sessionMgr->getCurrentRoleID ();
			$senderUID = $sessionMgr->getCurrentAccID ();
			$sendType = 1;
			
			$responseForward = Array ();
			if ($isCirc == 1) {
				$regBookID = 0;
				//sendType = 5 --> ส่งเวียน
				$sendType = 5;
				if (! $sequence->isExists ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
					$sequence->create ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
				}
				$defaultSendRegNo = $sequence->get ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
			} else {
				$sendType = 6;
				$defaultSendRegNo = 'Default';
			}
			
			$transMaster = new TransMasterDfEntity ( );
			$transMaster->Load ( "f_trans_main_id = '{$transMainID}'" );
			
			if ($transMaster->f_security_modifier > 0) {
				$sendType = 4;
			}
			
			foreach ( $receiverList as $receiver ) {
				list ( $receiverType, $receiverID ) = explode ( "_", $receiver );
				$readyToSend = false;
				switch ($receiverType) {
					case 1 :
						$readyToSend = true;
						$recvUID = $receiverID;
						$account = new AccountEntity ( );
						$account->Load ( "f_acc_id = '{$receiverID}'" );
						
						$passport = new PassportEntity ( );
						$passport->Load ( "f_acc_id = '{$receiverID}' and f_default_role = '1'" );
						$recvRoleID = $passport->f_role_id;
						$role = new RoleEntity ( );
						$role->Load ( "f_role_id = '{$recvRoleID}'" );
						$recvOrgID = $role->f_org_id;
						$recvName = UTFEncode ( $account->f_name . " " . $account->f_last_name );
						unset ( $passport );
						unset ( $role );
						break;
					case 2 :
						$readyToSend = true;
						$recvUID = 0;
						$recvRoleID = $receiverID;
						$role = new RoleEntity ( );
						$role->Load ( "f_role_id = '{$recvRoleID}'" );
						$recvOrgID = $role->f_org_id;
						$recvName = UTFEncode ( $role->f_role_name );
						unset ( $role );
						break;
					case 3 :
						$readyToSend = true;
						$recvUID = 0;
						$recvRoleID = 0;
						$recvOrgID = $receiverID;
						$organize = new OrganizeEntity ( );
						$organize->Load ( "f_org_id = '{$receiverID}'" );
						$recvName = UTFEncode ( $organize->f_org_name );
						break;
					default :
						$readyToSend = false;
						break;
				}
				
				$docDeliverType = 1;
				$regBookID = 0;
				if ($isCirc == 1) {
					$sendRegNo = $defaultSendRegNo;
				} else {
					$sendRegNo = 'Default';
				}
				
				$sendDate = 'Default';
				$sendTime = 'Default';
				// TODO: Name does not assigend yet
				$accountEntity = new AccountEntity ( );
				$accountEntity->Load ( "f_acc_id = '{$senderUID}'" );
				$sendName = UTFEncode ( $accountEntity->f_name . ' ' . $accountEntity->f_last_name );
				$orgEntity = new OrganizeEntity ( );
				$orgEntity->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
				
				$sendName = UTFEncode ($orgEntity->f_org_name);
				$sendOrgName = '';
				//$recvName = $_POST['sendIntSendTo'];
				$recvOrgName = '';
				
				if ($readyToSend) {
					$transCreator = new DFTransaction ( );
					//$transID = $transCreator->createMainTransaction($docID,'RE',1,$securityModifier,$speedModifier);
					$organizeSender = new OrganizeEntity ( );
					$organizeSender->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
					$responseTrans = $transCreator->createSendTransaction ( $transMainID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, $showInRegbook, $regBookID, $sendRegNo, 1, $transMainID, $transMainSeq, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, 0, $isCirc );
					$notifier->notifyOrganize ( $recvOrgID, "มีการส่งหนังสือเลขที่ {$document->f_doc_no} จาก {$organizeSender->f_org_name} เรื่อง \"{$document->f_title}\" (ยังไม่ได้ลงรับ)" );
					$responseForward [] = $responseTrans;
				}
			}
		}
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		//$response ['regno'] = UTFEncode ( $firstRegno );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		echo json_encode ( $response );
	}
	
	/**
	 * action /receive-external-global/ ทำการลงรับภายนอกทะเบียนกลาง
	 *
	 */
	public function receiveExternalGlobalAction() {
		global $config;
		global $sequence;
		global $sessionMgr;
		
		/*checkSessionJSON();*/
		
		//require_once 'Document.php';
		//require_once 'DFTransaction.php';
		
		$docCreator = new Document ( );
		$formID = $_POST ['recvExtGlobalFormID'];
		$hasTempPage = false;
		$tempID = 0;
		$createInDMS = false;
		$parentMode = 'mydoc';
		$DMSParentID = 0;
		$docDeliverType = $_POST ['recvExtGlobalDeliverType'];
		$regBookID = $_POST ['recvExtGlobalRegBookID'];
		$recvRegNo = $_POST ['recvExtGlobalRecvNo'];
		$recvDate = $_POST ['recvExtGlobalRecvDate'];
		$recvTime = $_POST ['recvExtGlobalRecvTime'];
		$sendName = $_POST ['recvExtGlobalSendFrom'];
		$sendOrg = '';
		$attendName = $_POST ['recvExtGlobalSendTo'];
		$attengOrg = '';

		$securityModifier = $_POST ['recvExtGlobalSecretLevel'];
		$speedModifier = $_POST ['recvExtGlobalSpeedLevel'];
		if ($securityModifier > 0) {
			$originTransType = 'RS';
			$recvType = 4;
		} else {
			$originTransType = 'RG';
			$recvType = 3;
		}
		
		$title = $_POST ['recvExtGlobalTitle'];
		$docNo = $_POST ['recvExtGlobalDocNo'];
		$docDate = $_POST ['recvExtGlobalDocDate'];
		$sendFrom = $sendName;
		
		if ($docCreator->checkDuplicate ( UTFDecode ( $docNo ), UTFDecode ( $docDate ), UTFDecode ( $title ), UTFDecode ( $sendFrom ) )) {
			$response = Array ();
			$response ['success'] = 0;
			$response ['duplicate'] = 1;
			echo json_encode ( $response );
			die ();
		}
		
		$docID = $docCreator->createFromPost ( $formID, $hasTempPage, $tempID, $createInDMS, $parentMode, $DMSParentID );
		
		$transCreator = new DFTransaction ( );
		//$transCreator->externalOrg ( UTFEncode ( $sendName ) );
		$sendName = trim($sendName);
		$transCreator->externalOrg ( UTFDecode ( $sendName ) );
		$transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
		$response = $transCreator->createReceiveTransaction ( $transID, $recvType, $docDeliverType, 1, $regBookID, $recvRegNo, 0, 0, 0, 0, 0, $recvDate, $recvTime, $sendName, $sendOrg, $attendName, $attengOrg, 0, 0, 0, 3, $sessionMgr->getCurrentAccID (), $sessionMgr->getCurrentRoleID (), $sessionMgr->getCurrentOrgID () );
		
		//Load Document
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
		//ลงรับ และ Forward Transaction ทันที
		if (array_key_exists ( 'recvExtGlobalForwardToHidden', $_POST ) && trim ( $_POST ['recvExtGlobalForwardToHidden'] ) != '') {
			
			$notifier = new Notifier ( );
			$orgID = $sessionMgr->getCurrentOrgID ();
			$isCirc = 0;
			
			//การส่งต่อจะไม่แสดงในทะเบียนเลย
			$showInRegbook = 0;
			
			$forwardTransToHidden = $_POST ['recvExtGlobalForwardToHidden'];
			$forwardDocTransID = $transID;
			
			//$subtransID เป็นของ Receive Transaction
			list ( $transMainID, $transMainSeq, $subtransID ) = explode ( "_", $forwardDocTransID );
			$receiverList = Array ();
			$receiverList = explode ( ",", $forwardTransToHidden );
			
			$senderOrgID = $sessionMgr->getCurrentOrgID ();
			$senderRoleID = $sessionMgr->getCurrentRoleID ();
			$senderUID = $sessionMgr->getCurrentAccID ();
			$sendType = 1;
			
			$responseForward = Array ();
			if ($isCirc == 1) {
				$regBookID = 0;
				//sendType = 5 --> ส่งเวียน
				$sendType = 5;
				if (! $sequence->isExists ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
					$sequence->create ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
				}
				$defaultSendRegNo = $sequence->get ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
			} else {
				$sendType = 6;
				$defaultSendRegNo = 'Default';
			}
			
			$transMaster = new TransMasterDfEntity ( );
			$transMaster->Load ( "f_trans_main_id = '{$transMainID}'" );
			
			
			if ($transMaster->f_security_modifier > 0) {
				$sendType = 4;
			}
			
			foreach ( $receiverList as $receiver ) {
				list ( $receiverType, $receiverID ) = explode ( "_", $receiver );
				$readyToSend = false;
				switch ($receiverType) {
					case 1 :
						$readyToSend = true;
						$recvUID = $receiverID;
						$account = new AccountEntity ( );
						$account->Load ( "f_acc_id = '{$receiverID}'" );
						
						$passport = new PassportEntity ( );
						$passport->Load ( "f_acc_id = '{$receiverID}' and f_default_role = '1'" );
						$recvRoleID = $passport->f_role_id;
						$role = new RoleEntity ( );
						$role->Load ( "f_role_id = '{$recvRoleID}'" );
						$recvOrgID = $role->f_org_id;
						$recvName = UTFEncode ( $account->f_name . " " . $account->f_last_name );
						unset ( $passport );
						unset ( $role );
						break;
					case 2 :
						$readyToSend = true;
						$recvUID = 0;
						$recvRoleID = $receiverID;
						$role = new RoleEntity ( );
						$role->Load ( "f_role_id = '{$recvRoleID}'" );
						$recvOrgID = $role->f_org_id;
						$recvName = UTFEncode ( $role->f_role_name );
						unset ( $role );
						break;
					case 3 :
						$readyToSend = true;
						$recvUID = 0;
						$recvRoleID = 0;
						$recvOrgID = $receiverID;
						$organize = new OrganizeEntity ( );
						$organize->Load ( "f_org_id = '{$receiverID}'" );
						$recvName = UTFEncode ( $organize->f_org_name );
						break;
					default :
						$readyToSend = false;
						break;
				}
				
				$docDeliverType = 1;
				$regBookID = 0;
				if ($isCirc == 1) {
					$sendRegNo = $defaultSendRegNo;
				} else {
					$sendRegNo = 'Default';
				}
				
				$sendDate = 'Default';
				$sendTime = 'Default';
				// TODO: Name does not assigend yet
				$accountEntity = new AccountEntity ( );
				$accountEntity->Load ( "f_acc_id = '{$senderUID}'" );
				$sendName = UTFEncode ( $accountEntity->f_name . ' ' . $accountEntity->f_last_name );
				$orgEntity = new OrganizeEntity ( );
				$orgEntity->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
				
				$sendName = UTFEncode($orgEntity->f_org_name);
				$sendOrgName = '';
				//$recvName = $_POST['sendIntSendTo'];
				$recvOrgName = '';
				
				if ($readyToSend) {
					$transCreator = new DFTransaction ( );
					//$transID = $transCreator->createMainTransaction($docID,'RE',1,$securityModifier,$speedModifier);
					$organizeSender = new OrganizeEntity ( );
					$organizeSender->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
					//$sendOrgName2 =$organizeSender->f_org_name;
					
					Logger::debug("Session ORG Name :".$sessionMgr->getCurrentOrgID());
					Logger::debug("SendName :".$sendName);
					
					$responseTrans = $transCreator->createSendTransaction ( $transMainID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, $showInRegbook, $regBookID, $sendRegNo, 1, $transMainID, $transMainSeq, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, 0, $isCirc );
					$notifier->notifyOrganize ( $recvOrgID, "มีการส่งหนังสือเลขที่ {$document->f_doc_no} จาก {$organizeSender->f_org_name} เรื่อง \"{$document->f_title}\" (ยังไม่ได้ลงรับ)" );
					$responseForward [] = $responseTrans;
				}
			}
		}
		
		if (($config['autoApproveReceiveExternal']) && (count($receiverList) > 0)) {
			list ( $recvTransMainID, $recvTransMainSeq, $recvSubtransID ) = explode ( "_", $response['transID'] );
			$updateRecvTrans = new TransDfRecvEntity();
			if(!$updateRecvTrans->Load("f_recv_trans_main_id= '{$recvTransMainID}' and f_recv_trans_main_seq='{$recvTransMainSeq}' and f_recv_id='{$recvSubtransID}'")) {
				//Do nothing
			} else {
				if ($updateRecvTrans->f_is_circ == 1) {
					$dfTrans = new DFTransaction ( );
					$dfTrans->CreateOrganizeMemberReceiveRecord ( $recvTransMainID, $recvTransMainSeq, $recvSubtransID );
				}
				$updateRecvTrans->f_governer_approve = 1;
				$updateRecvTrans->Update ();
			}
		}
		
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		//$response ['regno'] = UTFEncode ( $firstRegno );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		echo json_encode ( $response );
	}
	
	public function receiveEgifAction() {
		global $sequence;
		global $sessionMgr;
		
		$docID = $_POST['docID'];
		$egifID = $_POST['sendID'];
		
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
		
		$egif =new FakeEgifEntity();
		$egif->Load("f_egif_trans_id = '{$egifID}'");
		
		$transCreator = new DFTransaction ( );
		
		$sendName = trim($egif->f_sender_org_full);
		
		$transCreator->externalOrg ( $sendName );
		
		$egif->f_received = 1;
		$egif->Update();
		
		$transID = $transCreator->createMainTransaction ( $docID, 'RG' , 1, $egif->f_security, $egif->f_speed );
		
		$response = $transCreator->createReceiveTransaction ( $transID, 3, 4, 1, 0, 'Default', 0, 0, 0, 0, 0, 'Default', 'Default', UTFEncode($egif->f_sender_org_full), "", UTFEncode($egif->f_receiver_fullname), "", 0, 0, 0, 3, $sessionMgr->getCurrentAccID (), $sessionMgr->getCurrentRoleID (), $sessionMgr->getCurrentOrgID () );

		$url = "http://" . $egif->f_ref_url . "/ECMDev/egif-gateway/recv-ack";
		//die($url);
		$ch = curl_init ();
		$request=Array(
			'f_ref_trans_id'=>$egif->f_ref_trans_id,
			'f_received_reg_no'=>$response['regNo']			
		);
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $request );
		//curl_setopt ( $ch, CURLOPT_HEADER, (($isRequestHeader) ? 1 : 0) );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 0 );
		//if (is_array ( $exHeaderInfoArr ) && ! empty ( $exHeaderInfoArr )) {
		//	curl_setopt ( $ch, CURLOPT_HTTPHEADER, $exHeaderInfoArr );
		//}
		$responseEGIFAck = curl_exec ( $ch );
		curl_close ( $ch );
		
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		//$response ['regno'] = UTFEncode ( $firstRegno );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		echo json_encode ( $response );
	}
	
	public function rejectEgifAction() {
		global $sequence;
		global $sessionMgr;
		
		$docID = $_POST['docID'];
		$egifID = $_POST['sendID'];
		
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
		
		$egif =new FakeEgifEntity();
		$egif->Load("f_egif_trans_id = '{$egifID}'");
		
		$transCreator = new DFTransaction ( );
		
		$sendName = trim($egif->f_sender_org_full);
		
		$transCreator->externalOrg ( $sendName );
		
		$egif->f_received = 2;
		$egif->Update();
		
		
		$url = "http://" . $egif->f_ref_url . "/ECMDev/egif-gateway/reject-ack";
		//die($url);
		$ch = curl_init ();
		$request=Array(
			'f_ref_trans_id'=>$egif->f_ref_trans_id	
		);
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $request );
		//curl_setopt ( $ch, CURLOPT_HEADER, (($isRequestHeader) ? 1 : 0) );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 0 );
		//if (is_array ( $exHeaderInfoArr ) && ! empty ( $exHeaderInfoArr )) {
		//	curl_setopt ( $ch, CURLOPT_HTTPHEADER, $exHeaderInfoArr );
		//}
		$responseEGIFAck = curl_exec ( $ch );
		curl_close ( $ch );
		
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		//$response ['regno'] = UTFEncode ( $firstRegno );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		echo json_encode ( $response );
	}
	
	/**
	 * action /send-internal/ ทำการส่งภายใน
	 *
	 */
	public function sendInternalAction() {
		global $sequence;
		global $sessionMgr;
		global $config;
		
		set_time_limit ( 0 );
		
		/*checkSessionJSON();*/
		//require_once 'Document.php';
		//require_once 'DocMain.Entity.php';
		//require_once 'Notifier.php';
		//require_once 'DFTransaction.php';
		//include_once 'Organize.Entity.php';
		
		$orgID = $sessionMgr->getCurrentOrgID ();
		//Flag ออกเลขหนังสือ
		if (! array_key_exists ( 'genIntDocNo', $_POST )) {
			$genIntDocNo = 0;
		} else {
			if ($_POST ['genIntDocNo'] == 'on') {
				$genIntDocNo = 1;
			} else {
				$genIntDocNo = 0;
			}
		}
		$genExtDocNo = 0;
		//TODO:Check Doc code ในเคสส่งเวียนว่ารันเลขต่อเนื่องกับส่งภายในหรือไม่
		$orgID = $sessionMgr->getCurrentOrgID ();
		$formID = $_POST ['sendIntFormID'];
		$hasTempPage = false;
		$tempID = 0;
		$createInDMS = false;
		$parentMode = 'mydoc';
		$DMSParentID = 0;
		$docDeliverType = $_POST ['sendIntDeliverType'];
		$regBookID = $_POST ['sendIntRegBookID'];
		$securityModifier = $_POST ['sendIntSecretLevel'];
		$speedModifier = $_POST ['sendIntSpeedLevel'];
		$sendDocType = $_POST ['sendIntDocType'];
		
		if ($securityModifier > 0) {
			if ($sessionMgr->getCurrentOrgID() == 374) {
			$originTransType = 'SS';
			$sendType = 4;
		} else {
				$originTransType = 'SSI';
				$sendType = 10;
			}
		} else {
			$originTransType = 'SI';
			$sendType = 1;
		}
		$notifier = New Notifier ( );
		
		if (array_key_exists ( 'isCirc', $_GET )) {
			$isCirc = $_GET ['isCirc'];
		} else {
			$isCirc = 0;
		}
		logger::debug($_POST);
		#die();
		if (array_key_exists ( 'sendIntDocNo', $_POST )) {
			
			$docNo = UTFDecode ( $_POST ['sendIntDocNo'] );
			
			$org = new OrganizeEntity ( );
			if (! $org->Load ( "f_org_id = '{$orgID}'" )) {
				$docCode = '';
			} else {
				$docCode = $org->f_int_code;
			}
			
			if ($isCirc == 1) {
				$docRunner = "RunIntCirc.{$sessionMgr->getCurrentYear()}.{$docCode}";
			} else {
				if ($sendType == 4 && $config ['runningSecretWithNormal']) {
					$docRunner = "RunInt.{$sessionMgr->getCurrentYear()}.{$docCode}";
				} else {
					if ($sendType == 4) {
						$docRunner = "RunIntSecret.{$sessionMgr->getCurrentYear()}.{$docCode}";
					} else {
						$docRunner = "RunInt.{$sessionMgr->getCurrentYear()}.{$docCode}";
					}
				}
			}
			//logger::debug('docRunner: '.$docRunner);
			if (! $sequence->isExists ( $docRunner )) {
				$sequence->create ( $docRunner );
			}
			//logger::debug($docNo.' == '.$docCode);
			//แก้ไชออกเลขสำหรับหน่วยงานที่ใช้ config $config ['genIntCircDocNoUseOwner'] = true
			if ($docNo == $docCode . "/" || substr ( $docNo, strlen ( $docNo ) - 1, 1 ) == '/') {
				if (! $sequence->isExists ( $docCode )) {
					$sequence->create ( $docCode );
				}
				
				if ($genIntDocNo == 1) {
					if ($isCirc == 1) {
						$customDocNo = $docNo . $config ['circDocIdentifier'] . $sequence->get ( $docRunner );
						//$customDocNo = $docNo . $config ['circDocIdentifier'] . $sequence->get ( $docCode );
					} else {
						$customDocNo = $docNo . $sequence->get ( $docRunner );
						//$customDocNo = $docNo . $sequence->get ( $docCode );
					}
				}
				//logger::debug('CustomDocNo : '.$customDocNo);
			} else {
				$customDocNo = '';
			}
		} else {
			$customDocNo = '';
		}
		
		$docCreator = new Document ( );
		
		if ($isCirc == 1) {
			$sendType = 5;
			$sendSequence = "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
			
			if (! $sequence->isExists ( $sendSequence )) {
				$sequence->create ( $sendSequence );
			}
			$sendRegNo = $sequence->get ( $sendSequence );
		} else {
			$sendRegNo = $_POST ['sendIntSendNo'];
		}
		$sendDate = $_POST ['sendIntSendDate'];
		$sendTime = $_POST ['sendIntSendTime'];
		$sendName = $_POST ['sendIntSendFrom'];
		$sendOrgName = '';
		$recvName = $_POST ['sendIntSendTo'];
		$recvOrgName = '';
		$docID = $docCreator->createFromPost ( $formID, $hasTempPage, $tempID, $createInDMS, $parentMode, $DMSParentID, $customDocNo );
		
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
		
		$signID = $_POST['sendIntSignByID'];
		if(trim($signID) != '' and !is_null($signID)) {
			list($signRoleID,$signUid) = explode("_",$signID);
			//Logger::debug("role: {$signRoleID} uid {$signUid}");
			$getRoleEntity = new RoleEntity();
			$getRoleEntity->Load("f_role_id = '{$signRoleID}'");
			$document->f_signed = 1;
			$document->f_sign_org_id = $getRoleEntity->f_org_id;
			$document->f_sign_role_id = $signRoleID;
			$document->f_sign_uid = $signUid;	
		} else {
			$document->f_signed = 0;
		}
		$document->Update();
		
		$receiverHiddenField = $_POST ['sendIntSendToHidden'];
		$receiverList = Array ();
		$receiverList = explode ( ",", $receiverHiddenField );
		$senderOrgID = $sessionMgr->getCurrentOrgID ();
		$senderRoleID = $sessionMgr->getCurrentRoleID ();
		$senderUID = $sessionMgr->getCurrentAccID ();
		
		$response = Array ();
		//echo count($receiverList);
		//echo sizeof($receiverList);
		//die();
		

		$loopCount = ( int ) count ( $receiverList );
		if ($loopCount > 1) {
			$captureAllResponse = false;
		} else {
			$captureAllResponse = true;
		}
		
		$lastOfList = $loopCount;
		$count = 0;
		
		//echo "Loopcount : $captureAllResponse";
		//echo "Last : $loopCount";
		//echo "Count : $count";
		//die();
		

		foreach ( $receiverList as $receiver ) {
			
			list ( $receiverType, $receiverID ) = explode ( "_", $receiver );
			$readyToSend = false;
			switch ($receiverType) {
				case 1 :
					$readyToSend = true;
					$recvUID = $receiverID;
					$account = new AccountEntity ( );
					$account->Load ( "f_acc_id = '{$receiverID}'" );
					
					$passport = new PassportEntity ( );
					$passport->Load ( "f_acc_id = '{$receiverID}' and f_default_role = '1'" );
					$recvRoleID = $passport->f_role_id;
					$role = new RoleEntity ( );
					$role->Load ( "f_role_id = '{$recvRoleID}'" );
					$recvOrgID = $role->f_org_id;
					$recvName = UTFEncode ( $account->f_name . " " . $account->f_last_name );
					unset ( $passport );
					unset ( $role );
					break;
				case 2 :
					$readyToSend = true;
					$recvUID = 0;
					$recvRoleID = $receiverID;
					$role = new RoleEntity ( );
					$role->Load ( "f_role_id = '{$recvRoleID}'" );
					$recvOrgID = $role->f_org_id;
					$recvName = UTFEncode ( $role->f_role_name );
					unset ( $role );
					break;
				case 3 :
					$readyToSend = true;
					$recvUID = 0;
					$recvRoleID = 0;
					$recvOrgID = $receiverID;
					$organize = new OrganizeEntity ( );
					$organize->Load ( "f_org_id = '{$receiverID}'" );
					$recvName = UTFEncode ( $organize->f_org_name );
					break;
				default :
					//die('error');
					$readyToSend = false;
					break;
			}
			//$readyToSend = false;
			//echo $count;
			

			$firstRegno = "";
			if ($readyToSend) {
				$transCreator = new DFTransaction ( );
				//Logger::dump ( "Organization", $organize );
				$transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
				
				if ($captureAllResponse) {
					$responseTmp = $transCreator->createSendTransaction ( $transID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, 1, $regBookID, $sendRegNo, 0, 0, 0, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, $sendDocType, $isCirc );
					$response [] = $responseTmp;
					if ($firstRegno == "") {
						$firstRegno = $responseTmp ['sendNo'];
					}
				} else {
					if ($count == 0 || $count == $loopCount - 1) {
						$responseTmp = $transCreator->createSendTransaction ( $transID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, 1, $regBookID, $sendRegNo, 0, 0, 0, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, $sendDocType, $isCirc );
						$response [] = $responseTmp;
						if ($firstRegno == "") {
							$firstRegno = $responseTmp ['sendNo'];
						}
					} else {
						try {
							$responseTmp = $transCreator->createSendTransaction ( $transID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, 1, $regBookID, $sendRegNo, 0, 0, 0, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, $sendDocType, $isCirc );
						} catch ( Exception $e ) {
							die ( $e->getMessage () );
						}
					}
				}
				
				$organizeSender = new OrganizeEntity ( );
				$organizeSender->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
				$notifier->notifyOrganize ( $recvOrgID, "มีการส่งหนังสือเลขที่ {$document->f_doc_no} จาก {$organizeSender->f_org_name} เรื่อง \"{$document->f_title}\" (ยังไม่ได้ลงรับ)" );
			}
			$count ++;
		}
		/*if($_POST['reserveExtGlobal'] == ) {
		
		}*/
		$reserveMode = false;
		if (array_key_exists ( 'reserveExt', $_POST ) && $_POST ['reserveExt'] == 'on') {
			include_once 'ReserveNo.php';
			$reserveMode = true;
			$reserver = new ReserveNo ( );
			$response2 = $reserver->doReserve ( 0, false, $docID );
		}
		
		if (array_key_exists ( 'reserveExtGlobal', $_POST ) && $_POST ['reserveExtGlobal'] == 'on') {
			include_once 'ReserveNo.php';
			$reserveMode = true;
			$reserver = new ReserveNo ( );
			$response2 = $reserver->doReserve ( 1, false, $docID );
		}
		$response ['success'] = 1;
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		$response ['regno'] = UTFEncode ( $firstRegno );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		
		
		if ($reserveMode) {
			$response ['reserve'] = 1;
			$response ['bookNoR'] = $response2 ['bookNo'];
			$response ['regNoR'] = $response2 ['regNo'];
		}
		
		if ($loopCount <= 10) {
			$data = json_encode ( $response );
			echo $data;
			//do nothing	
		} elseif ($loopCount <= 50) {
			$data = json_encode ( $response );
			//sleep(5);
			echo $data;
		
		} elseif ($loopCount <= 100) {
			$data = json_encode ( $response );
			//sleep(7);
			echo $data;
		} else {
			$data = json_encode ( $response );
			echo $data;
		}
	}
	
	/**
	 * action /send-internal-global/ ทำการส่งภายในทะเบียนกลาง
	 *
	 */
	public function sendInternalGlobalAction() {
		global $sequence;
		global $sessionMgr;
		global $config;
		
		set_time_limit ( 0 );
		
		/*checkSessionJSON();*/
		//require_once 'Document.php';
		//require_once 'DocMain.Entity.php';
		//require_once 'Notifier.php';
		//require_once 'DFTransaction.php';
		//include_once 'Organize.Entity.php';
		
		$orgID = $sessionMgr->getCurrentOrgID ();
		$formID = $_POST ['sendIntGlobalFormID'];
		$hasTempPage = false;
		$tempID = 0;
		$createInDMS = false;
		$parentMode = 'mydoc';
		$DMSParentID = 0;
		$docDeliverType = $_POST ['sendIntGlobalDeliverType'];
		$regBookID = $_POST ['sendIntGlobalRegBookID'];
		$securityModifier = $_POST ['sendIntGlobalSecretLevel'];
		$speedModifier = $_POST ['sendIntGlobalSpeedLevel'];
		$sendDocType = $_POST ['sendIntGlobalDocType'];
		$sendType = 7;
		
		//Check Send Type Transform
		if ($securityModifier > 0) {
			$originTransType = 'SS';
			$sendType = 7;
		} else {
			$originTransType = 'SI';
			$sendType = 7;
		}
		
		$notifier = New Notifier ( );
		
		if (array_key_exists ( 'isCirc', $_GET )) {
			$isCirc = $_GET ['isCirc'];
		} else {
			$isCirc = 0;
		}
		
		//Flag ออกเลขหนังสือ
		if (! array_key_exists ( 'genIntDocNo', $_POST )) {
			$genIntDocNo = 0;
		} else {
			if ($_POST ['genIntDocNo'] == 'on') {
				$genIntDocNo = 1;
			} else {
				$genIntDocNo = 0;
			}
		}
		
		$genExtDocNo = 0;
		//TODO:Check Doc code ในเคสส่งเวียนว่ารันเลขต่อเนื่องกับส่งภายในหรือไม่
		$orgID = $sessionMgr->getCurrentOrgID ();
		if (array_key_exists ( 'sendIntGlobalDocNo', $_POST )) {
			
			$docNo = UTFDecode ( $_POST ['sendIntGlobalDocNo'] );
			
			$org = new OrganizeEntity ( );
			if (! $org->Load ( "f_org_id = '{$orgID}'" )) {
				$docCode = '';
			} else {
				$docCode = $org->f_int_code;
			}
			
			if ($isCirc == 1) {
				$docRunner = "RunIntCirc.{$sessionMgr->getCurrentYear()}.{$docCode}";
			} else {
				$docRunner = "RunInt.{$sessionMgr->getCurrentYear()}.{$docCode}";
			}
			
			$docRunner = 'RunIntGlobal.' . $sessionMgr->getCurrentYear ();
			
			if (! $sequence->isExists ( $docRunner )) {
				$sequence->create ( $docRunner );
			}
			if ($isCirc == 1) {
				$customDocNo = $docNo . $config ['circDocIdentifier'] . $sequence->get ( $docRunner );
				//$customDocNo = $docNo . $config ['circDocIdentifier'] . $sequence->get ( $docCode );
			} else {
				$customDocNo = $docNo . $sequence->get ( $docRunner );
				//$customDocNo = $docNo . $sequence->get ( $docCode );
			}
		} else {
			$customDocNo = '';
		}
		
		$docCreator = new Document ( );
		
		if ($isCirc == 1) {
			$sendType = 7;
			$sendSequence = "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
			
			if (! $sequence->isExists ( $sendSequence )) {
				$sequence->create ( $sendSequence );
			}
			$sendRegNo = $sequence->get ( $sendSequence );
		} else {
			$sendRegNo = $_POST ['sendIntGlobalSendNo'];
		}
		
		$sendDate = $_POST ['sendIntGlobalSendDate'];
		$sendTime = $_POST ['sendIntGlobalSendTime'];
		$sendName = $_POST ['sendIntGlobalSendFrom'];
		$sendOrgName = '';
		$recvName = $_POST ['sendIntGlobalSendTo'];
		$recvOrgName = '';
		$docID = $docCreator->createFromPost ( $formID, $hasTempPage, $tempID, $createInDMS, $parentMode, $DMSParentID, $customDocNo );
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
		
		$signID = $_POST['sendIntGlobalSignByID'];
		if(trim($signID) != '' and !is_null($signID)) {
			list($signRoleID,$signUid) = explode("_",$signID);
			Logger::debug("role: {$signRoleID} uid {$signUid}");
			$getRoleEntity = new RoleEntity();
			$getRoleEntity->Load("f_role_id = '{$signRoleID}'");
			$document->f_signed = 1;
			$document->f_sign_org_id = $getRoleEntity->f_org_id;
			$document->f_sign_role_id = $signRoleID;
			$document->f_sign_uid = $signUid;	
		} else {
			$document->f_signed = 0;
		}
		$document->Update();
		
		$receiverHiddenField = $_POST ['sendIntGlobalSendToHidden'];
		$receiverList = Array ();
		$receiverList = explode ( ",", $receiverHiddenField );
		$senderOrgID = $sessionMgr->getCurrentOrgID ();
		$senderRoleID = $sessionMgr->getCurrentRoleID ();
		$senderUID = $sessionMgr->getCurrentAccID ();
		
		$response = Array ();
		foreach ( $receiverList as $receiver ) {
			list ( $receiverType, $receiverID ) = explode ( "_", $receiver );
			$readyToSend = false;
			switch ($receiverType) {
				case 1 :
					$readyToSend = true;
					$recvUID = $receiverID;
					$account = new AccountEntity ( );
					$account->Load ( "f_acc_id = '{$receiverID}'" );
					
					$passport = new PassportEntity ( );
					$passport->Load ( "f_acc_id = '{$receiverID}' and f_default_role = '1'" );
					$recvRoleID = $passport->f_role_id;
					$role = new RoleEntity ( );
					$role->Load ( "f_role_id = '{$recvRoleID}'" );
					$recvOrgID = $role->f_org_id;
					$recvName = UTFEncode ( $account->f_name . " " . $account->f_last_name );
					unset ( $passport );
					unset ( $role );
					break;
				case 2 :
					$readyToSend = true;
					$recvUID = 0;
					$recvRoleID = $receiverID;
					$role = new RoleEntity ( );
					$role->Load ( "f_role_id = '{$recvRoleID}'" );
					$recvOrgID = $role->f_org_id;
					$recvName = UTFEncode ( $role->f_role_name );
					unset ( $role );
					break;
				case 3 :
					$readyToSend = true;
					$recvUID = 0;
					$recvRoleID = 0;
					$recvOrgID = $receiverID;
					$organize = new OrganizeEntity ( );
					$organize->Load ( "f_org_id = '{$receiverID}'" );
					$recvName = UTFEncode ( $organize->f_org_name );
					break;
				default :
					$readyToSend = false;
					break;
			}
			
			if ($readyToSend) {
				//Logger::debug ( "iscirc : {$isCirc}" );
				//Logger::debug ( "sendregno original : {$sendRegNo}" );
				$transCreator = new DFTransaction ( );
				//Logger::dump ( "Organization", $organize );
				$transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
				$organizeSender = new OrganizeEntity ( );
				$organizeSender->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
				$notifier->notifyOrganize ( $recvOrgID, "มีการส่งหนังสือเลขที่ {$document->f_doc_no} จาก {$organizeSender->f_org_name} เรื่อง \"{$document->f_title}\" (ยังไม่ได้ลงรับ)" );
				$response [] = $transCreator->createSendTransaction ( $transID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, 1, $regBookID, $sendRegNo, 0, 0, 0, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, $sendDocType, $isCirc );
			}
		}
		
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		echo json_encode ( $response );
	}
	
	/**
	 * action /change-receiver/ ทำการเปลี่ยนผู้รับหนังสือส่งกลับ/ดึงคืน
	 *
	 */
	public function changeReceiverAction() {
		$transID = $_POST ['refTransChangeIntID'];
		$changeRecvType = $_POST ['changeRecvType'];
		$changeRecvIntID = $_POST ['changeRecvIntID'];
		list ( $transMainID, $transSeq, $sendTransID ) = explode ( "_", $transID );
		//include_once "TransDfSend.Entity.php";
		$tranSend = new TransDfSendEntity ( );
		$response = Array ();
		if (! $tranSend->Load ( "f_send_trans_main_id = '{$transMainID}' and f_send_id = '{$sendTransID}' and f_send_trans_main_seq = '{$transSeq}' " )) {
			$response ['success'] = 0;
		} else {
			
			$tranSend->f_receiver_type = $changeRecvType;
			
			//User Case
			if ($changeRecvType == 1) {
			
			}
			
			//Role Case
			if ($changeRecvType == 2) {
				//include_once ('Role.Entity.php');
				$role = new RoleEntity ( );
				$role->Load ( "f_role_id = '{$changeRecvIntID}'" );
				
				$tranSend->f_receiver_org_id = $role->f_org_id;
				$tranSend->f_receiver_role_id = $changeRecvIntID;
				$tranSend->f_receiver_uid = 0;
				
				$tranSend->f_recv_fullname = $role->f_role_name;
			}
			
			//Org Case 
			if ($changeRecvType == 3) {
				//include_once ('Organize.Entity.php');
				$org = new OrganizeEntity ( );
				$org->Load ( "f_org_id = '{$changeRecvIntID}'" );
				
				$tranSend->f_receiver_org_id = $changeRecvIntID;
				$tranSend->f_receiver_role_id = 0;
				$tranSend->f_receiver_uid = 0;
				
				$tranSend->f_recv_fullname = $org->f_org_name;
			}
			
			$tranSend->f_received = 0;
			
			$tranSend->f_callback = 0;
			$tranSend->f_callback_ack = 0;
			$tranSend->f_callback_uid = 0;
			$tranSend->f_callback_role_id = 0;
			$tranSend->f_callback_org_id = 0;
			
			$tranSend->f_sendback = 0;
			$tranSend->f_sendback_ack = 0;
			$tranSend->f_sendback_uid = 0;
			$tranSend->f_sendback_role_id = 0;
			$tranSend->f_sendback_org_id = 0;
			
			$tranSend->Update ();
			$response ['success'] = 1;
		}
		
		echo json_encode ( $response );
	}
	
	/**
	 * action /change-receiver-external/ ทำการเปลี่ยนผู้รับภายนอกในกรณีส่งกลับ/ดึงคืน
	 *
	 */
	public function changeReceiverExternalAction() {
		$transID = $_POST ['refTransChangeExtID'];
		$changeRecvExtType = $_POST ['changeRecvExtType'];
		$changeRecvExtID = $_POST ['changeRecvExtID'];
		//Logger::dump ( "External : ", $_POST );
		list ( $transMainID, $transSeq, $sendTransID ) = explode ( "_", $transID );
		//include_once "TransDfSend.Entity.php";
		$tranSend = new TransDfSendEntity ( );
		$response = Array ();
		if (! $tranSend->Load ( "f_send_trans_main_id = '{$transMainID}' and f_send_id = '{$sendTransID}' and f_send_trans_main_seq = '{$transSeq}' " )) {
			$response ['success'] = 0;
			//Logger::dump ( "X1" );
		} else {
			
			$tranSend->f_receiver_type = $changeRecvExtType;
			if (trim ( $changeRecvExtID ) == '') {
			
			}
			//User Case
			if ($changeRecvExtType == 1) {
			
			}
			//Logger::dump ( "X2" );
			//Role Case
			if ($changeRecvExtType == 2) {
				//include_once ('Role.Entity.php');
				$role = new RoleEntity ( );
				$role->Load ( "f_role_id = '{$changeRecvIntID}'" );
				$tranSend->f_receiver_org_id = $role->f_org_id;
				$tranSend->f_receiver_role_id = $changeRecvExtID;
				$tranSend->f_receiver_uid = 0;
			}
			
			//Org Case 
			if ($changeRecvExtType == 3) {
				$tranSend->f_receiver_org_id = $changeRecvExtID;
				$tranSend->f_receiver_role_id = 0;
				$tranSend->f_receiver_uid = 0;
			}
			
			//include_once ('ExternalSender.Entity.php');
			if ($changeRecvExtID == '') {
				//Logger::debug ( "Trapped" );
				$dfTran = new DFTransaction ( );
				$newOrgName = UTFDecode ( trim($_POST ['textParam'] ));
				$dfTran->externalOrg ( $newOrgName );
				
				$extEntity = new ExternalSenderEntity ( );
				$extEntity->f_ext_sender_name;
				$extEntity->Load ( "f_ext_sender_name = '{$newOrgName}'" );
				
				$changeRecvExtID = $extEntity->f_ext_sender_id;
				
				$tranSend->f_receiver_org_id = $changeRecvExtID;
				$tranSend->f_receiver_role_id = 0;
				$tranSend->f_receiver_uid = 0;
				//$changeRecvExtType = $extEntity->f_
			}
			
			$extEnt = new ExternalSenderEntity ( );
			$extEnt->Load ( "f_ext_sender_id = '{$changeRecvExtID}'" );
			
			$tranSend->f_receiver_org_id = 0;
			$tranSend->f_receiver_role_id = 0;
			$tranSend->f_receiver_uid = 0;
			
			$tranSend->f_recv_fullname = $extEnt->f_ext_sender_name;
			
			$tranSend->f_received = 0;
			
			$tranSend->f_callback = 0;
			$tranSend->f_callback_ack = 0;
			$tranSend->f_callback_uid = 0;
			$tranSend->f_callback_role_id = 0;
			$tranSend->f_callback_org_id = 0;
			
			$tranSend->f_sendback = 0;
			$tranSend->f_sendback_ack = 0;
			$tranSend->f_sendback_uid = 0;
			$tranSend->f_sendback_role_id = 0;
			$tranSend->f_sendback_org_id = 0;
			
			$tranSend->Update ();
			$response ['success'] = 1;
		}
		
		echo json_encode ( $response );
	}
	
	/**
	 * action /send-external/ ทำการส่งภายนอก
	 *
	 */
	public function sendExternalAction() {
		global $sequence;
		global $sessionMgr;
		global $config;
		
		set_time_limit ( 0 );
		
		/*checkSessionJSON();*/
		
		//require_once 'Document.php';
		//require_once 'DFTransaction.php';
		//include_once 'DocMain.Entity.php';
		
		$orgID = $sessionMgr->getCurrentOrgID ();
		$formID = $_POST ['sendExtFormID'];
		$hasTempPage = false;
		$tempID = 0;
		$createInDMS = false;
		$parentMode = 'mydoc';
		$DMSParentID = 0;
		$docDeliverType = $_POST ['sendExtDeliverType'];
		$regBookID = $_POST ['sendExtRegBookID'];
		$securityModifier = $_POST ['sendExtSecretLevel'];
		$speedModifier = $_POST ['sendExtSpeedLevel'];
		$sendDocType = $_POST ['sendExtDocType'];
		
		if ($securityModifier > 0) {
			if ($sessionMgr->getCurrentOrgID() == 374) {
				$originTransType = 'SS';
				$sendType = 4;
			} else {
				$originTransType = 'SSE';
				$sendType = 9;
			}
		} else {
			$originTransType = 'SE';
			$sendType = 2;
		}
		
		if (array_key_exists ( 'isCirc', $_GET )) {
			$isCirc = $_GET ['isCirc'];
		} else {
			$isCirc = 0;
		}
		
		$docNo = UTFDecode ( $_POST ['sendExtDocNo'] );
		
		if (array_key_exists ( 'sendExtDocNo', $_POST )) {
			
			$org = new OrganizeEntity ( );
			if (! $org->Load ( "f_org_id = '{$orgID}'" )) {
				$docCode = '';
			} else {
				$docCode = $org->f_ext_code;
			}
			
			if (array_key_exists ( 'sendExtRefBookno', $_POST )) {
				if (trim ( $_POST ['sendExtRefBookno'] ) != '') {
					$modeGen2 = true;
				} else {
					$modeGen2 = false;
				}
			}
			
				if ($sendType == 9) {
					$docRunner = "RunExtSecret.{$sessionMgr->getCurrentYear()}.{$docCode}";
				} else {
					$docRunner = "RunExt.{$sessionMgr->getCurrentYear()}.{$docCode}";
				}
			if (! $sequence->isExists ( $docRunner )) {
				$sequence->create ( $docRunner );
			}
			if ($docNo == $docCode . "/") {
				//if (true) {    
				if (! $sequence->isExists ( $docCode )) {
					$sequence->create ( $docCode );
				}
				if ($isCirc == 1) {
					$customDocNo = $docCode . "/" . $config ['circDocIdentifier'] . $sequence->get ( $docRunner );
					//$customDocNo = $docCode . "/" . $config ['circDocIdentifier'] . $sequence->get ( $docCode );
				} else {
					$customDocNo = $docCode . "/" . $sequence->get ( $docRunner );
					//$customDocNo = $docCode . "/" . $sequence->get ( $docCode );
				}
			} else {
				
				if ($modeGen2) {
					if ($_POST ['sendExtUseReserveID'] != 0) {
						//case ออกเลขจากเลขจอง
						$customDocNo = UTFDecode ( $_POST ['sendExtDocNo'] );
					} else {
						//case ออกเลขหนังสือแทน
						$customDocNo = $docNo . $sequence->get ( $docRunner );
					}
					
				//$customDocNo = $docNo . $sequence->get($docCode);
				} else {
					$customDocNo = '';
				}
			}
		} else {
			$customDocNo = '';
		}
		
		$docCreator = new Document ( );
		if ($_POST ['sendExtUseReserveID'] != 0) {
			$sendRegNo = $_POST ['sendExtSendNo'];
		} else {
			if ($isCirc == 1) {
				//$sendType = 2;
				$sendRegNo = $sequence->get ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
			} else {
				$sendRegNo = $_POST ['sendExtSendNo'];
			}
		}
		
		if ($_POST ['sendExtUseReserveID'] != 0) {
			//include_once 'ReserveNo.Entity.php';
			$reserveNo = new ReserveNoEntity ( );
			$reserveNo->Load ( "f_reserved_id = '{$_POST['sendExtUseReserveID']}'" );
			$reserveNo->f_used = 1;
			$reserveNo->Update ();
		}
		
		$sendDate = $_POST ['sendExtSendDate'];
		$sendTime = $_POST ['sendExtSendTime'];
		$sendName = $_POST ['sendExtSendFrom'];
		$sendOrgName = '';
		$recvName = $_POST ['sendExtSendTo'];
		$recvOrgName = '';
		
		$docID = $docCreator->createFromPost ( $formID, $hasTempPage, $tempID, $createInDMS, $parentMode, $DMSParentID, $customDocNo );
		
		$recvUID = 0;
		$recvRoleID = 0;
		$recvOrgID = 0;
		
		$transCreator = new DFTransaction ( );
		
		$senderOrgID = $sessionMgr->getCurrentOrgID ();
		$senderRoleID = $sessionMgr->getCurrentRoleID ();
		$senderUID = $sessionMgr->getCurrentAccID ();
		
		/********************************************************************************************************/
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
		
		$signID = $_POST['sendExtSignByID'];
		if(trim($signID) != '' and !is_null($signID)) {
			list($signRoleID,$signUid) = explode("_",$signID);
			Logger::debug("role: {$signRoleID} uid {$signUid}");
			$getRoleEntity = new RoleEntity();
			$getRoleEntity->Load("f_role_id = '{$signRoleID}'");
			$document->f_signed = 1;
			$document->f_sign_org_id = $getRoleEntity->f_org_id;
			$document->f_sign_role_id = $signRoleID;
			$document->f_sign_uid = $signUid;	
		} else {
			$document->f_signed = 0;
		}
		$document->Update();
		
		$receiverHiddenField = $_POST ['sendExtSendToHidden'];
		//Logger::debug ( "step 1 : passed" );
		$receiverList = Array ();
		$recvNameList = Array ();
		
		$recvNameList = explode ( ",", $recvName );
		$receiverList = explode ( ",", $receiverHiddenField );
		$senderOrgID = $sessionMgr->getCurrentOrgID ();
		$senderRoleID = $sessionMgr->getCurrentRoleID ();
		$senderUID = $sessionMgr->getCurrentAccID ();
		$response = Array ();
		
		foreach ( $recvNameList as $recvNameIndiv ) {
			//Logger::debug ( "step 2 : passed" );
			$readyToSend = true;
			
			$recvUID = 0;
			$recvRoleID = 0;
			$recvOrgID = 0;
			
			if ($readyToSend) {
				$transCreator = new DFTransaction ( );
				
				$senderOrgID = $sessionMgr->getCurrentOrgID ();
				$senderRoleID = $sessionMgr->getCurrentRoleID ();
				$senderUID = $sessionMgr->getCurrentAccID ();
				/*
                $transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
                $transCreator->externalOrg ( $recvNameIndiv );
                $response = $transCreator->createSendTransaction ( $transID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, 1, $regBookID, $sendRegNo, 0, 0, 0, $sendDate, $sendTime, $recvNameIndiv, $recvOrgName, $sendName, $sendOrgName, $sendDocType, $isCirc );
                */
				
				$transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
				$recvNameIndiv = trim($recvNameIndiv);
				$transCreator->externalOrg (UTFDecode ($recvNameIndiv ),UTFDecode ($recvNameIndiv ));
				$response = $transCreator->createSendTransaction ( $transID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, 1, $regBookID, $sendRegNo, 0, 0, 0, $sendDate, $sendTime, $recvNameIndiv, $recvOrgName, $sendName, $sendOrgName, $sendDocType, $isCirc );
				//Logger::debug ( "step 3 : send to {$recvNameIndiv}" );
			}
		}
		/********************************************************************************************************/
		//$response2 = Array();
		

		//$response = $transCreator->createReceiveTransaction($transID,2,$docDeliverType,1,$regBookID,$recvRegNo,0,0,0,0,0,$recvDate,$recvTime,$sendName,$sendOrg,$attendName,$attengOrg);
		//$document = new DocMainEntity ( );
		//$document->Load ( "f_doc_id = '{$docID}'" );
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		
		echo json_encode ( $response );
	}
	
	/**
	 * action /send-external-global/ ทำการส่งภายนอกทะเบียนกลาง
	 *
	 */
	public function sendExternalGlobalAction() {
		global $sequence;
		global $sessionMgr;
		global $config;
		
		/*checkSessionJSON();*/
		set_time_limit ( 0 );
		
		//require_once 'Document.php';
		//require_once 'DFTransaction.php';
		
		$orgID = $sessionMgr->getCurrentOrgID ();
		
		$formID = $_POST ['sendExtGlobalFormID'];
		$hasTempPage = false;
		$tempID = 0;
		$createInDMS = false;
		$parentMode = 'mydoc';
		$DMSParentID = 0;
		$docDeliverType = $_POST ['sendExtGlobalDeliverType'];
		$regBookID = $_POST ['sendExtGlobalRegBookID'];
		$securityModifier = $_POST ['sendExtGlobalSecretLevel'];
		$speedModifier = $_POST ['sendExtGlobalSpeedLevel'];
		$sendDocType = $_POST ['sendExtGlobalDocType'];
		
		if ($securityModifier > 0) {
			$originTransType = 'SS';
			$sendType = 4;
			$sendRegCode = "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
		} else {
			$originTransType = 'SG';
			$sendType = 3;
			$sendRegCode = "sendRegNo_0_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
		}
		
		if (array_key_exists ( 'isCirc', $_GET )) {
			$isCirc = $_GET ['isCirc'];
		} else {
			$isCirc = 0;
		}
		
		if (array_key_exists ( 'sendExtGlobalDocNo', $_POST )) {
			$docNo = UTFDecode ( $_POST ['sendExtGlobalDocNo'] );
			
			$org = new OrganizeEntity ( );
			if (! $org->Load ( "f_org_id = '{$orgID}'" )) {
				$docCode = '';
			} else {
				$docCode = $org->f_ext_code;
			}
			
			if (array_key_exists ( 'sendExtGlobalRefBookno', $_POST )) {
				if (trim ( $_POST ['sendExtGlobalRefBookno'] ) != '') {
					$modeGen2 = true;
				} else {
					$modeGen2 = false;
				}
			}
			
			if(array_key_exists('sendExtGlobalRefDocID',$_POST)) {
				$sendExtGlobalRefDocID = $_POST['sendExtGlobalRefDocID'];
			} else {
				$sendExtGlobalRefDocID = 0;
			}
			if ($sendType == 4 && $config ['runningSecretWithNormal']) {
				$docRunner = "RunExtGlobal.{$sessionMgr->getCurrentYear()}";
			} else {
				if ($sendType == 4) {
					$docRunner = "RunExtGlobalSecret.{$sessionMgr->getCurrentYear()}";
				} else {
					$docRunner = "RunExtGlobal.{$sessionMgr->getCurrentYear()}";
				}
			
			}
			
			if (! $sequence->isExists ( $docRunner )) {
				$sequence->create ( $docRunner );
			}
			if ($docNo == $docCode . "/") {
				$runner = "globalSendDocNo";
				if (! $sequence->isExists ( $runner )) {
					$sequence->create ( $runner );
				}
				if ($isCirc == 1) {
					$customDocNo = $docCode . "/" . $config ['circDocIdentifier'] . $sequence->get ( $docRunner );
					//$customDocNo = $docCode . "/" . $config ['circDocIdentifier'] . $sequence->get ( $runner );
				} else {
					$customDocNo = $docCode . "/" . $sequence->get ( $docRunner );
					//$customDocNo = $docCode . "/" . $sequence->get ( $runner );
				}
			
			} else {
				if ($modeGen2) {
					if ($_POST ['sendExtGlobalUseReserveID'] != 0) {
						//case ออกเลขจากเลขจอง
						$customDocNo = UTFDecode ( $_POST ['sendExtGlobalDocNo'] );
					} else {
						//case ออกเลขหนังสือแทน
						if ($isCirc == 1) {
							$customDocNo = $docNo . $config ['circDocIdentifier'] . $sequence->get ( $docRunner );
						} else {
							$customDocNo = $docNo . $sequence->get ( $docRunner );
						}
					}
					
				//$customDocNo = $docNo . $sequence->get($docCode);
				} else {
					$customDocNo = '';
				}
			}
		} else {
			$customDocNo = '';
		}
		
		$docCreator = new Document ( );
		
		//กรณีส่งภายนอกให้นับอันเดียวเสมอ
		//if ($isCirc == 1) {
		if (true) {
			if ($_POST ['sendExtGlobalUseReserveID'] != 0) {
				$sendRegNo = $_POST ['sendExtGlobalSendNo'];
			} else {
				$sendRegNo = $sequence->get ( $sendRegCode );
			}
		} else {
			$sendRegNo = $_POST ['sendExtGlobalSendNo'];
		}
		
		if ($_POST ['sendExtGlobalUseReserveID'] != 0) {
			//include_once 'ReserveNo.Entity.php';
			$reserveNo = new ReserveNoEntity ( );
			$reserveNo->Load ( "f_reserved_id = '{$_POST['sendExtGlobalUseReserveID']}'" );
			$reserveNo->f_used = 1;
			$reserveNo->Update ();
		}
		
		$sendDate = $_POST ['sendExtGlobalSendDate'];
		$sendTime = $_POST ['sendExtGlobalSendTime'];
		$sendName = $_POST ['sendExtGlobalSendFrom'];
		$sendOrgName = '';
		$recvName = $_POST ['sendExtGlobalSendTo'];
		$remark = $_POST ['sendExtGlobalRemark'];
		$recvOrgName = '';
		
		$docID = $docCreator->createFromPost ( $formID, $hasTempPage, $tempID, $createInDMS, $parentMode, $DMSParentID, $customDocNo );
		$document = new DocMainEntity ( );
		
		$document->Load ( "f_doc_id = '{$docID}'" );
		
		$signID = $_POST['sendExtGlobalSignByID'];
		if(trim($signID) != '' and !is_null($signID)) {
			list($signRoleID,$signUid) = explode("_",$signID);
			Logger::debug("role: {$signRoleID} uid {$signUid}");
			$getRoleEntity = new RoleEntity();
			$getRoleEntity->Load("f_role_id = '{$signRoleID}'");
			$document->f_signed = 1;
			$document->f_sign_org_id = $getRoleEntity->f_org_id;
			$document->f_sign_role_id = $signRoleID;
			$document->f_sign_uid = $signUid;	
		} else {
			$document->f_signed = 0;
		}
		$document->f_remark = UTFDecode($remark);
		$document->f_ref_gen_from_doc_id = $sendExtGlobalRefDocID;
		Logger::debug("refID : ".$sendExtGlobalRefDocID);
		$document->Update();
		
		$receiverHiddenField = $_POST ['sendExtGlobalSendToHidden'];
		//Logger::debug ( "step 1 : passed" );
		$receiverList = Array ();
		$recvNameList = Array ();
		
		$recvNameList = explode ( ",", $recvName );
		$receiverList = explode ( ",", $receiverHiddenField );
		$senderOrgID = $sessionMgr->getCurrentOrgID ();
		$senderRoleID = $sessionMgr->getCurrentRoleID ();
		$senderUID = $sessionMgr->getCurrentAccID ();
		$response = Array ();
		
		foreach ( $recvNameList as $recvNameIndiv ) {
			//Logger::debug ( "step 2 : passed" );
			$readyToSend = true;
			
			$recvUID = 0;
			$recvRoleID = 0;
			$recvOrgID = 0;
			
			if ($readyToSend) {
				$transCreator = new DFTransaction ( );
				
				$senderOrgID = $sessionMgr->getCurrentOrgID ();
				$senderRoleID = $sessionMgr->getCurrentRoleID ();
				$senderUID = $sessionMgr->getCurrentAccID ();
				
				$transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
				$recvNameIndiv = trim($recvNameIndiv);
				$transCreator->externalOrg (UTFDecode($recvNameIndiv ));
				$response = $transCreator->createSendTransaction ( $transID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, 1, $regBookID, $sendRegNo, 0, 0, 0, $sendDate, $sendTime, $recvNameIndiv, $recvOrgName, $sendName, $sendOrgName, $sendDocType, $isCirc );
				//Logger::debug ( "step 3 : send to {$recvNameIndiv}" );
			}
			//$response = $transCreator->createReceiveTransaction($transID,2,$docDeliverType,1,$regBookID,$recvRegNo,0,0,0,0,0,$recvDate,$recvTime,$sendName,$sendOrg,$attendName,$attengOrg);
		}
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		$response ['title'] = UTFEncode ( $document->f_title );
		$response ['docID'] = $docID;
		$response ['remark'] = $remark;
		echo json_encode ( $response );
	}
	
	/**
	 * action /accept-document/ ทำการลงรับหนังสือ
	 *
	 */
	public function acceptDocumentAction() {
		global $conn;
		global $sessionMgr;
		global $sequence;
		global $util;
		
		//require_once 'DFTransaction.php';
		//require_once 'TransMasterDf.Entity.php';
		//require_once 'Notifier.php';
		//require_once 'DocMain.Entity.php';
	
		if (! array_key_exists ( 'acceptAsType', $_POST ) || ! array_key_exists ( 'acceptingDoctype', $_POST )) {
			$response = Array ();
			$response ['success'] = 0;
			$response ['message'] = UTFEncode ( 'Unable to accept!!!' );
			echo json_encode ( $response );
			die ();
		}
		$conn->StartTrans ();
		$notifier = new Notifier ( );
		if (! array_key_exists ( 'sendRecordCount', $_COOKIE )) {
			//not found sendRecordID
		} else {	
			$sendRecordCount = $_COOKIE ['sendRecordCount'];
			for($i=0;$i<$sendRecordCount;$i++){
					$sendRecordID = $_COOKIE ["sendRecordID$i"];
					list ( $sendTransMainID, $sendTransMainSeq, $sendID ) = explode ( "_", $sendRecordID );
					
					$conn->RowLock ( "tbl_trans_df_send", "f_send_trans_main_id = '{$sendTransMainID}' and  f_send_trans_main_seq = '{$sendTransMainSeq}' and f_received = 0" );
					
					$sqlCheckUnreceived = "select count(*) as COUNT_EXP from tbl_trans_df_send where f_send_trans_main_id = '{$sendTransMainID}' and   f_send_trans_main_seq = '{$sendTransMainSeq}' and f_received = 0";
					$rsCheckUnreceived = $conn->Execute ( $sqlCheckUnreceived );
					$tmpCheckUnreceived = $rsCheckUnreceived->FetchNextObject ();
					if ($tmpCheckUnreceived->COUNT_EXP > 0) {
						$receiveType = $_POST ['acceptAsType'];
						$acceptDocType = $_POST ['acceptingDoctype'];
						$docDeliverType = 1;
						$regBookID = 0;
						$recvRegNo = 'Default';
						$recvDate = 'Default';
						$recvTime = 'Default';
						$dfTrans = new DFTransaction ( );
						$parentTransaction = $dfTrans->getSendTransaction ( $sendTransMainID, $sendTransMainSeq );
						$transMaster = new TransMasterDfEntity ( );
						$transMaster->Load ( "f_trans_main_id = '{$sendTransMainID}'" );
						$document = new DocMainEntity ( );
						$document->Load ( "f_doc_id = '$transMaster->f_doc_id'" );
						if ($transMaster->f_security_modifier > 0) {
							if ($sessionMgr->getCurrentOrgID() == 374) {
							$receiveType = 4;
							} elseif ($receiveType == 1) {
								$receiveType = 6;
							} elseif ($receiveType == 2) {
								$receiveType = 7;
							}
						}
						Logger::dump ( "Docmain Entity", $document );
						//var_dump($parentTransaction);
						$sendName = UTFEncode ( $util->parseFullname ( $parentTransaction ['f_sender_uid'], $parentTransaction ['f_sender_role_id'], $parentTransaction ['f_sender_org_id'] ) );
						//echo $sendName;
						$sendOrg = "";
						$attendName = UTFEncode ( $util->parseFullname ( $parentTransaction ['f_receiver_uid'], $parentTransaction ['f_receiver_role_id'], $parentTransaction ['f_receiver_org_id'] ) );
						//echo $attendName;
						$attendOrg = "";
						
						//$senderOrgID = $sessionMgr->getCurrentOrgID ();
						//$senderRoleID = $sessionMgr->getCurrentRoleID ();
						//$senderUID = $sessionMgr->getCurrentAccID ();
						

						$response = $dfTrans->createReceiveTransaction ( $sendTransMainID, $receiveType, $docDeliverType, 1, $regBookID, $recvRegNo, 0, 0, 0, 0, 0, $recvDate, $recvTime, $sendName, $sendOrg, $attendName, $attendOrg, $parentTransaction ['f_sender_uid'], $parentTransaction ['f_sender_role_id'], $parentTransaction ['f_sender_org_id'], $acceptDocType, $parentTransaction ['f_receiver_uid'], $parentTransaction ['f_receiver_role_id'], $parentTransaction ['f_receiver_org_id'] );
						//$response ['recvType'] = $receiveType;
						if ($response ['success'] == 1) {
							$organizeSender = new OrganizeEntity ( );
							$organizeSender->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
							$notifier->notifyOrganize ( $sessionMgr->getCurrentOrgID (), "มีการลงรับหนังสือเลขที่ {$document->f_doc_no} จาก {$organizeSender->f_org_name} เรื่อง \"{$document->f_title}\" (เลขรับ{$response['recvType']} ที่{$response['regNo']})" );
							$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_received', 1 );
							$dfTrans->modifyReceiveTransaction ( $sendTransMainID, $response ['sequence'], 'f_forwarded_trans', 1 );
							$dfTrans->modifyReceiveTransaction ( $sendTransMainID, $response ['sequence'], 'f_send_id', $sendTransMainID );
							$dfTrans->modifyReceiveTransaction ( $sendTransMainID, $response ['sequence'], 'f_send_seq', $sendTransMainSeq );
						}
						
						//ลงรับ และ Forward Transaction ทันที
						if (array_key_exists ( 'acceptForwardToHidden', $_POST ) && trim ( $_POST ['acceptForwardToHidden'] ) != '') {
							$dfTrans->modifyReceiveTransaction ( $sendTransMainID, $response ['sequence'], 'f_governer_approve', 1 );
							$notifier = new Notifier ( );
							$orgID = $sessionMgr->getCurrentOrgID ();
							$isCirc = 0;
							
							//การส่งต่อจะไม่แสดงในทะเบียนเลย
							$showInRegbook = 0;
							
							$forwardTransToHidden = $_POST ['acceptForwardToHidden'];
							$forwardDocTransID = $sendRecordID;
							
							//$subtransID เป็นของ Receive Transaction
							list ( $transMainID, $transMainSeq, $subtransID ) = explode ( "_", $forwardDocTransID );
							$receiverList = Array ();
							$receiverList = explode ( ",", $forwardTransToHidden );
							
							$senderOrgID = $sessionMgr->getCurrentOrgID ();
							$senderRoleID = $sessionMgr->getCurrentRoleID ();
							$senderUID = $sessionMgr->getCurrentAccID ();
							$sendType = 1;
							
							$responseForward = Array ();
							if ($isCirc == 1) {
								$regBookID = 0;
								//sendType = 5 --> ส่งเวียน
								$sendType = 5;
								if (! $sequence->isExists ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
									$sequence->create ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
								}
								$defaultSendRegNo = $sequence->get ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
							} else {
								$sendType = 6;
								$defaultSendRegNo = 'Default';
							}
							
							$transMaster = new TransMasterDfEntity ( );
							$transMaster->Load ( "f_trans_main_id = '{$transMainID}'" );
							
							$document = new DocMainEntity ( );
							$document->Load ( "f_doc_id = '{$transMaster->f_doc_id}'" );
							if ($transMaster->f_security_modifier > 0) {
								$sendType = 4;
							}
							
							foreach ( $receiverList as $receiver ) {
								list ( $receiverType, $receiverID ) = explode ( "_", $receiver );
								$readyToSend = false;
								switch ($receiverType) {
									case 1 :
										$readyToSend = true;
										$recvUID = $receiverID;
										$account = new AccountEntity ( );
										$account->Load ( "f_acc_id = '{$receiverID}'" );
										
										$passport = new PassportEntity ( );
										$passport->Load ( "f_acc_id = '{$receiverID}' and f_default_role = '1'" );
										$recvRoleID = $passport->f_role_id;
										$role = new RoleEntity ( );
										$role->Load ( "f_role_id = '{$recvRoleID}'" );
										$recvOrgID = $role->f_org_id;
										$recvName = UTFEncode ( $account->f_name . " " . $account->f_last_name );
										unset ( $passport );
										unset ( $role );
										break;
									case 2 :
										$readyToSend = true;
										$recvUID = 0;
										$recvRoleID = $receiverID;
										$role = new RoleEntity ( );
										$role->Load ( "f_role_id = '{$recvRoleID}'" );
										$recvOrgID = $role->f_org_id;
										$recvName = UTFEncode ( $role->f_role_name );
										unset ( $role );
										break;
									case 3 :
										$readyToSend = true;
										$recvUID = 0;
										$recvRoleID = 0;
										$recvOrgID = $receiverID;
										$organize = new OrganizeEntity ( );
										$organize->Load ( "f_org_id = '{$receiverID}'" );
										$recvName = UTFEncode ( $organize->f_org_name );
										break;
									default :
										$readyToSend = false;
										break;
								}
								
								$docDeliverType = 1;
								$regBookID = 0;
								if ($isCirc == 1) {
									$sendRegNo = $defaultSendRegNo;
								} else {
									$sendRegNo = 'Default';
								}
								
								$sendDate = 'Default';
								$sendTime = 'Default';
								// TODO: Name does not assigend yet
								$accountEntity = new AccountEntity ( );
								$accountEntity->Load ( "f_acc_id = '{$senderUID}'" );
								$sendName = UTFEncode ( $accountEntity->f_name . ' ' . $accountEntity->f_last_name );
								$orgEntity = new OrganizeEntity ( );
								$orgEntity->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
								
								$sendName = $orgEntity->f_org_name;
								$sendOrgName = '';
								//$recvName = $_POST['sendIntSendTo'];
								$recvOrgName = '';
								
								if ($readyToSend) {
									$transCreator = new DFTransaction ( );
									//$transID = $transCreator->createMainTransaction($docID,'RE',1,$securityModifier,$speedModifier);
									$organizeSender = new OrganizeEntity ( );
									$organizeSender->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
									$responseTrans = $transCreator->createSendTransaction ( $transMainID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, $showInRegbook, $regBookID, $sendRegNo, 1, $transMainID, $transMainSeq, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, 0, $isCirc );
									$notifier->notifyOrganize ( $recvOrgID, "มีการส่งหนังสือเลขที่ {$document->f_doc_no} จาก {$organizeSender->f_org_name} เรื่อง \"{$document->f_title}\" (ยังไม่ได้ลงรับ)" );
									$responseForward [] = $responseTrans;
									Logger::dump ( "response", $responseTrans );
								}
							}
						}
					} else {
						$response = Array ();
						$response ['success'] = 0;
						$response ['message'] = UTFEncode ( 'มีการลงรับไปแล้ว!!!' );
						//echo json_encode($response);
					}
			}
			if($sendRecordCount>1){
				$response['multi']=1;
			}else{
				$response['multi']=0;
			}

			$conn->CompleteTrans ();
			echo json_encode ( $response );
		
		}
	}
	
	/**
	 * action /sendback-document/ ทำการส่งกลับเอกสาร
	 *
	 */
	public function sendbackDocumentAction() {
		//global $sequence;
		global $util;
		global $sessionMgr;
		
		//require_once 'DFTransaction.php';
		if (! array_key_exists ( 'sendbackRefID', $_POST )) {
			//not found sendRecordID
		} else {
			
			$sendRecordID = $_POST ['sendbackRefID'];
			$sendbackText = $_POST ['sendbackText'];
			//$sendRecordID = $_COOKIE ['sendRecordID'];
			list ( $sendTransMainID, $sendTransMainSeq, $sendID ) = explode ( "_", $sendRecordID );
			//$receiveType = $_POST['acceptAsType'];
			$docDeliverType = 1;
			$regBookID = 0;
			$recvRegNo = 'Default';
			$recvDate = 'Default';
			$recvTime = 'Default';
			$dfTrans = new DFTransaction ( );
			$parentTransaction = $dfTrans->getSendTransaction ( $sendTransMainID, $sendTransMainSeq );
			//var_dump($parentTransaction);
			$sendName = $util->parseFullname ( $parentTransaction ['f_sender_uid'], $parentTransaction ['f_sender_role_id'], $parentTransaction ['f_sender_org_id'] );
			$sendOrg = "";
			$attendName = $util->parseFullname ( $parentTransaction ['f_receiver_uid'], $parentTransaction ['f_receiver_role_id'], $parentTransaction ['f_receiver_org_id'] );
			$attendOrg = "";
			
			//$senderOrgID = $sessionMgr->getCurrentOrgID ();
			//$senderRoleID = $sessionMgr->getCurrentRoleID ();
			//$senderUID = $sessionMgr->getCurrentAccID ();
			

			//$response = $dfTrans->createReceiveTransaction($sendTransMainID,$receiveType,$docDeliverType,1,$regBookID,$recvRegNo,0,0,0,0,0,$recvDate,$recvTime,$sendName,$sendOrg,$attendName,$attendOrg,$parentTransaction['f_sender_uid'],$parentTransaction['f_sender_role_id'],$parentTransaction['f_sender_org_id']); 
			//if($response['success'] == 1) {
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_sendback', 1 );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_sendback_ack', 0 );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_sendback_comment', UTFDecode ( $sendbackText ) );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_sendback_uid', $sessionMgr->getCurrentAccID () );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_sendback_role_id', $sessionMgr->getCurrentRoleID () );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_sendback_org_id', $sessionMgr->getCurrentOrgID () );
			//}
			$response = Array ();
			echo json_encode ( $response );
		}
	}
	
	/**
	 * action /ack-sendback/ ทำการรับทราบการส่งกลับ
	 *
	 */
	public function ackSendbackAction() {
		//global $sequence;
		global $util;
		
		//require_once 'DFTransaction.php';
		if (! array_key_exists ( 'sendbackRecordID', $_COOKIE )) {
			//not found sendRecordID
		} else {
			
			$sendbackRecordID = $_COOKIE ['sendbackRecordID'];
			list ( $sendTransMainID, $sendTransMainSeq, $sendID ) = explode ( "_", $sendbackRecordID );
			//$receiveType = $_POST['acceptAsType'];
			$docDeliverType = 1;
			$regBookID = 0;
			$recvRegNo = 'Default';
			$recvDate = 'Default';
			$recvTime = 'Default';
			$dfTrans = new DFTransaction ( );
			$parentTransaction = $dfTrans->getSendTransaction ( $sendTransMainID, $sendTransMainSeq );
			//var_dump($parentTransaction);
			$sendName = $util->parseFullname ( $parentTransaction ['f_sender_uid'], $parentTransaction ['f_sender_role_id'], $parentTransaction ['f_sender_org_id'] );
			$sendOrg = "";
			$attendName = $util->parseFullname ( $parentTransaction ['f_receiver_uid'], $parentTransaction ['f_receiver_role_id'], $parentTransaction ['f_receiver_org_id'] );
			$attendOrg = "";
			
			//$senderOrgID = $sessionMgr->getCurrentOrgID ();
			//$senderRoleID = $sessionMgr->getCurrentRoleID ();
			//$senderUID = $sessionMgr->getCurrentAccID ();

			//$response = $dfTrans->createReceiveTransaction($sendTransMainID,$receiveType,$docDeliverType,1,$regBookID,$recvRegNo,0,0,0,0,0,$recvDate,$recvTime,$sendName,$sendOrg,$attendName,$attendOrg,$parentTransaction['f_sender_uid'],$parentTransaction['f_sender_role_id'],$parentTransaction['f_sender_org_id']); 
			//if($response['success'] == 1) {
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_sendback_ack', 1 );
			//}
			$response = Array ();
			echo json_encode ( $response );
		}
	}
	
	public function ackReturnAction() {
		//global $sequence;
		//global $util;
		
		//require_once 'DFTransaction.php';
		if (! array_key_exists ( 'id', $_POST )) {
			$response = Array ();
			$response['success'] = 0;
			echo json_encode ( $response );
		} else {
			$borrowID = $_POST ['id'];
			$borrowRecord = new BorrowRecordEntity();
			$borrowRecord->Load("f_borrow_id = '{$borrowID}'");
			$borrowRecord->f_return_flag = 1;
			$borrowRecord->Update();
			$response = Array ();
			$response['success'] = 1;
			echo json_encode ( $response );
		}
	}
	
	/**
	 * action /ack-callback/ ทำการรับทราบการดึงคืนเอกสาร
	 *
	 */
	public function ackCallbackAction() {
		//global $sequence;
		global $util;
		
		//require_once 'DFTransaction.php';
		if (! array_key_exists ( 'CallbackRecordID', $_COOKIE )) {
			//not found sendRecordID
		} else {
			
			$sendbackRecordID = $_COOKIE ['CallbackRecordID'];
			list ( $sendTransMainID, $sendTransMainSeq, $sendID ) = explode ( "_", $sendbackRecordID );
			//$receiveType = $_POST['acceptAsType'];
			$docDeliverType = 1;
			$regBookID = 0;
			$recvRegNo = 'Default';
			$recvDate = 'Default';
			$recvTime = 'Default';
			$dfTrans = new DFTransaction ( );
			$parentTransaction = $dfTrans->getSendTransaction ( $sendTransMainID, $sendTransMainSeq );
			//var_dump($parentTransaction);
			$sendName = $util->parseFullname ( $parentTransaction ['f_sender_uid'], $parentTransaction ['f_sender_role_id'], $parentTransaction ['f_sender_org_id'] );
			$sendOrg = "";
			$attendName = $util->parseFullname ( $parentTransaction ['f_receiver_uid'], $parentTransaction ['f_receiver_role_id'], $parentTransaction ['f_receiver_org_id'] );
			$attendOrg = "";
			
			//$senderOrgID = $sessionMgr->getCurrentOrgID ();
			//$senderRoleID = $sessionMgr->getCurrentRoleID ();
			//$senderUID = $sessionMgr->getCurrentAccID ();

			//$response = $dfTrans->createReceiveTransaction($sendTransMainID,$receiveType,$docDeliverType,1,$regBookID,$recvRegNo,0,0,0,0,0,$recvDate,$recvTime,$sendName,$sendOrg,$attendName,$attendOrg,$parentTransaction['f_sender_uid'],$parentTransaction['f_sender_role_id'],$parentTransaction['f_sender_org_id']); 
			//if($response['success'] == 1) {
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_callback_ack', 1 );
			//}
			$response = Array ();
			echo json_encode ( $response );
		}
	}
	
	/**
	 * action /return-transaction/ ทำการคืนเรื่องให้ต้นเรื่อง
	 *
	 */
	public function returnTransactionAction() {
		//global $sequence;
		global $sessionMgr;
		//TODO: We have to recheck the send reg.no because forward trans doesn't gen send reg.no
		//require_once 'Document.php';
		//require_once 'DFTransaction.php';
		//require_once 'Notifier.php';
		//require_once 'DocMain.Entity.php';
		//include_once 'TransMasterDf.Entity.php';
		//include_once 'Account.Entity.php';
		//include_once 'Organize.Entity.php';
		
		$notifier = new Notifier ( );
		$orgID = $sessionMgr->getCurrentOrgID ();
		if (array_key_exists ( 'isCirc', $_GET )) {
			$isCirc = $_GET ['isCirc'];
		} else {
			$isCirc = 0;
		}
		
		//การส่งต่อจะไม่แสดงในทะเบียนเลย
		$showInRegbook = 0;
		
		//$forwardTransToHidden = $_POST ['forwardTransToHidden'];
		$transCode = $_POST ['transCode'];
		$docID = $_POST ['docID'];
		//$forwardDocTransID = $_COOKIE ['fwD'];
		

		//$forwardModule = $_COOKIE ['rp'];
		//$subtransID เป็นของ Receive Transaction
		list ( $transMainID, $transMainSeq, $subtransID ) = explode ( "_", $transCode );
		//$receiverList = Array ();
		//$receiverList = explode ( ",", $forwardTransToHidden );
		$senderOrgID = $sessionMgr->getCurrentOrgID ();
		$senderRoleID = $sessionMgr->getCurrentRoleID ();
		$senderUID = $sessionMgr->getCurrentAccID ();
		$sendType = 6;
		$response = Array ();
		
		$transMaster = new TransMasterDfEntity ( );
		$transMaster->Load ( "f_trans_main_id = '{$transMainID}'" );
		
		$transRecv = new TransDfRecvEntity();
		$transRecv->Load ( "f_recv_trans_main_id = '{$transMainID}' and f_recv_trans_main_seq = '{$transMainSeq}' and f_recv_id = '{$subtransID}'" );
		$transRecv->f_return_owner = 1;
		$transRecv->Update();
		
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$transMaster->f_doc_id}'" );
		
		if ($transMaster->f_security_modifier > 0) {
			$sendType = 4;
		}
		
		$receiverType = 3;
		$receiverID = $transMaster->f_owner_org_id;
		
		$docDeliverType = 1;
		$regBookID = 0;
		if ($isCirc == 1) {
			$sendRegNo = $defaultSendRegNo;
		} else {
			$sendRegNo = 'Default';
		}
		$sendDate = 'Default';
		$sendTime = 'Default';
		// TODO: Name does not assigend yet
		$accountEntity = new AccountEntity ( );
		$accountEntity->Load ( "f_acc_id = '{$senderUID}'" );
		$sendName = UTFEncode ( $accountEntity->f_name . ' ' . $accountEntity->f_last_name );
		$orgEntity = new OrganizeEntity ( );
		$recvOrgEntity = new OrganizeEntity ( );
		$orgEntity->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
		$recvOrgEntity->Load ( "f_org_id = '{$transMaster->f_owner_org_id}'" );
		
		$sendName = UTFEncode ( $orgEntity->f_org_name );
		$sendOrgName = '';
		//$recvName = $_POST['sendIntSendTo'];
		$receiverID = $recvOrgEntity->f_org_id;
		
		$recvUID = 0;
		$recvRoleID = 0;
		$recvOrgID = $receiverID;
		$organize = new OrganizeEntity ( );
		$organize->Load ( "f_org_id = '{$recvOrgID}'" );
		
		$recvName = UTFEncode ( $organize->f_org_name );
		$recvOrgName = "";
		
		$transCreator = new DFTransaction ( );
		//$transID = $transCreator->createMainTransaction($docID,'RE',1,$securityModifier,$speedModifier);
		$responseTrans = $transCreator->createSendTransaction ( $transMainID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, $showInRegbook, $regBookID, $sendRegNo, 1, $transMainID, $transMainSeq, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, 0, $isCirc );
		$notifier->notifyOrganize ( $recvOrgID, "มีการส่งหนังสือเลขที่ {$document->f_doc_no} จาก {$organizeSender->f_org_name} เรื่อง \"{$document->f_title}\" (ยังไม่ได้ลงรับ)" );
		$response [] = $responseTrans;
		if ($responseTrans ['success'] == 1) {
			$completeForward = true;
		} else {
			$completeForward = false;
		}
		
		if ($isCirc == 1) {
			$response ['isCircTrans'] = 1;
		} else {
			$response ['isCircTrans'] = 0;
		}
		echo json_encode ( $response );
	}
	
	/**
	 * action /forward-transaction/ ทำการส่งต่อ
	 *
	 */
	public function forwardTransactionAction() {
		
		global $sequence;
		global $sessionMgr;
		
		set_time_limit ( 0 );

		$orgID = $sessionMgr->getCurrentOrgID ();
		if (array_key_exists ( 'isCirc', $_GET )) {
			$isCirc = $_GET ['isCirc'];
		} else {
			$isCirc = 0;
		}	
		//TODO: We have to recheck the send reg.no because forward trans doesn't gen send reg.no
		//require_once 'Document.php';
		//require_once 'DFTransaction.php';
		//require_once 'Notifier.php';
		//require_once 'DocMain.Entity.php';
		//include_once 'TransMasterDf.Entity.php';
		//include_once 'Account.Entity.php';
		//include_once 'Organize.Entity.php';

		$forwardDocTransID = explode ( ",", $_COOKIE ['fwD'] );
		$recvRecordCount = count($forwardDocTransID);
			
		for($i=0;$i<$recvRecordCount;$i++){

		$notifier = new Notifier ( );
		//การส่งต่อจะไม่แสดงในทะเบียนเลย
		$showInRegbook = 0;
		$forwardTransToHidden = $_POST ['forwardTransToHidden'];
		$forwardModule = $_COOKIE ['rp'];
		//$subtransID เป็นของ Receive Transaction
		list ( $transMainID, $transMainSeq, $subtransID ) = explode ( "_", $forwardDocTransID[$i] );
		$receiverList = Array ();
		$receiverList = explode ( ",", $forwardTransToHidden );
		$senderOrgID = $sessionMgr->getCurrentOrgID ();
		$senderRoleID = $sessionMgr->getCurrentRoleID ();
		$senderUID = $sessionMgr->getCurrentAccID ();
		$sendType = 1;
		$response = Array ();
		if ($isCirc == 1) {
			$regBookID = 0;
			//sendType = 5 --> ส่งเวียน
			$sendType = 5;
			if (! $sequence->isExists ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
				$sequence->create ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
			}
			$defaultSendRegNo = $sequence->get ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		} else {
			$sendType = 6;
			$defaultSendRegNo = 'Default';
		}
		$transMaster = new TransMasterDfEntity ( );
		$transMaster->Load ( "f_trans_main_id = '{$transMainID}'" );
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$transMaster->f_doc_id}'" );
		if ($transMaster->f_security_modifier > 0) {
			$sendType = 4;
		}
		
		foreach ( $receiverList as $receiver ) {
			list ( $receiverType, $receiverID ) = explode ( "_", $receiver );
			$readyToSend = false;
			switch ($receiverType) {
				case 1 :
					$readyToSend = true;
					$recvUID = $receiverID;
					$account = new AccountEntity ( );
					$account->Load ( "f_acc_id = '{$receiverID}'" );
					
					$passport = new PassportEntity ( );
					$passport->Load ( "f_acc_id = '{$receiverID}' and f_default_role = '1'" );
					$recvRoleID = $passport->f_role_id;
					$role = new RoleEntity ( );
					$role->Load ( "f_role_id = '{$recvRoleID}'" );
					$recvOrgID = $role->f_org_id;
					$recvName = UTFEncode ( $account->f_name . " " . $account->f_last_name );
					unset ( $passport );
					unset ( $role );
					break;
				case 2 :
					$readyToSend = true;
					$recvUID = 0;
					$recvRoleID = $receiverID;
					$role = new RoleEntity ( );
					$role->Load ( "f_role_id = '{$recvRoleID}'" );
					$recvOrgID = $role->f_org_id;
					$recvName = UTFEncode ( $role->f_role_name );
					unset ( $role );
					break;
				case 3 :
					$readyToSend = true;
					$recvUID = 0;
					$recvRoleID = 0;
					$recvOrgID = $receiverID;
					$organize = new OrganizeEntity ( );
					$organize->Load ( "f_org_id = '{$receiverID}'" );
					$recvName = UTFEncode ( $organize->f_org_name );
					break;
				default :
					$readyToSend = false;
					break;
			}
			
			$docDeliverType = 1;
			$regBookID = 0;
			if ($isCirc == 1) {
				$sendRegNo = $defaultSendRegNo;
			} else {
				$sendRegNo = 'Default';
			}
			
			$sendDate = 'Default';
			$sendTime = 'Default';
			// TODO: Name does not assigend yet
			$accountEntity = new AccountEntity ( );
			$accountEntity->Load ( "f_acc_id = '{$senderUID}'" );
			$sendName = UTFEncode ( $accountEntity->f_name . ' ' . $accountEntity->f_last_name );
			$orgEntity = new OrganizeEntity ( );
			$orgEntity->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
			//แก้ไขเป็น UTF Encode เมื่อ 20/1/52
			$sendName = UTFEncode ( $orgEntity->f_org_name );
			$sendOrgName = '';
			//$recvName = $_POST['sendIntSendTo'];
			$recvOrgName = '';
			
			if ($readyToSend) {
				$transCreator = new DFTransaction ( );
				//$transID = $transCreator->createMainTransaction($docID,'RE',1,$securityModifier,$speedModifier);
				$responseTrans = $transCreator->createSendTransaction ( $transMainID, $sendType, $recvUID, $recvRoleID, $recvOrgID, $docDeliverType, $showInRegbook, $regBookID, $sendRegNo, 1, $transMainID, $transMainSeq, $sendDate, $sendTime, $recvName, $recvOrgName, $sendName, $sendOrgName, 0, $isCirc );
				$notifier->notifyOrganize ( $recvOrgID, "มีการส่งหนังสือเลขที่ {$document->f_doc_no} จาก {$orgEntity->f_org_name} เรื่อง \"{$document->f_title}\" (ยังไม่ได้ลงรับ)" );
				$response [] = $responseTrans;
				if ($responseTrans ['success'] == 1) {
					$completeForward = true;
				} else {
					$completeForward = false;
				}
			}
		}
		
		if ($isCirc == 1) {
			$response ['isCircTrans'] = 1;
		} else {
			$response ['isCircTrans'] = 0;
		}
		
		$response ['docno'] = UTFEncode ( $document->f_doc_no );
		//TODO: update หน่อยนะวันหลัง
		if ($completeForward) {
			//Case callback
			if ($forwardModule == 'CBI') {
				//Logger::debug ( "Update Callback Transaction" );
				//include_once 'TransDfSend.Entity.php';
				$transUpdate = new TransDfSendEntity ( );
				$transUpdate->Load ( "f_send_trans_main_id='{$transMainID}' and  f_send_trans_main_seq='{$transMainSeq}'" );
				$transUpdate->f_callback_ack = 1;
				$transUpdate->Update ();
			}
			if ($forwardModule == 'SBI') {
				//Logger::debug ( "Update Sendback Transaction" );
				//include_once 'TransDfSend.Entity.php';
				$transUpdate = new TransDfSendEntity ( );
				$transUpdate->Load ( "f_send_trans_main_id='{$transMainID}' and  f_send_trans_main_seq='{$transMainSeq}'" );
				$transUpdate->f_se = 1;
				$transUpdate->Update ();
			}
		}
	}// ปิดloop for
		echo json_encode ( $response );
	}
	
	/**
	 * action /add-track/ ทำการบันทึกรายการติดตาม
	 *
	 */
	public function addTrackAction() {
		global $sessionMgr;
		
		//include_once 'TransDfTrack.Entity.php';
		$transID = $_POST ['id'];
		list ( $transMainID, $transMainSeq, $recvID ) = explode ( "_", $transID );
		$response = Array ();
		$transTrack = new TransDfTrackEntity ( );
		if (! $transTrack->Load ( "f_trans_main_id = '{$transMainID}' and f_acc_id= '{$sessionMgr->getCurrentAccID()}'" )) {
			$transTrack->f_trans_main_id = $transMainID;
			$transTrack->f_acc_id = $sessionMgr->getCurrentAccID ();
			$transTrack->f_delete = 0;
			$transTrack->f_deadline = 0;
			$transTrack->f_deadline_date = '';
			$transTrack->f_deadline_time = '';
			if ($transTrack->Save ()) {
				$response ['success'] = 1;
			} else {
				$response ['success'] = 0;
			}
		} else {
			$response ['success'] = 0;
		}
		echo json_encode ( $response );
	}
	
	/**
	 * action /remove-track/ ลบรายการติดตาม
	 *
	 */
	public function removeTrackAction() {
		global $sessionMgr;
		
		//include_once 'TransDfTrack.Entity.php';
		
		$transID = $_POST ['id'];
		
		list ( $transMainID, $transMainSeq, $recvID ) = explode ( "_", $transID );
		
		$response = Array ();
		$transTrack = new TransDfTrackEntity ( );
		if ($transTrack->Load ( "f_trans_main_id = '{$transMainID}' and f_acc_id= '{$sessionMgr->getCurrentAccID()}'" )) {
			//$transTrack->f_trans_main_id = $transMainID;
			//$transTrack->f_acc_id = $sessionMgr->getCurrentAccID ();
			//$transTrack->f_delete = 0;
			//$//transTrack->f_deadline = 0;
			//$transTrack->f_deadline_date = '';
			//$transTrack->f_deadline_time = '';
			if ($transTrack->Delete ()) {
				$response ['success'] = 1;
			} else {
				$response ['success'] = 0;
			}
		} else {
			$response ['success'] = 0;
		}
		echo json_encode ( $response );
	}
	
	/**
	 * action /hold/ ทำการหยุด transaction รอ
	 *
	 */
	public function holdAction() {
		global $sessionMgr;
		
		//include_once 'TransMasterDf.Entity.php';
		
		$transID = $_POST ['id'];
		list ( $transMainID, $transMainSeq, $recvID ) = explode ( "_", $transID );
		$response = Array ();
		$transMaster = new TransMasterDfEntity ( );
		if ($transMaster->Load ( "f_trans_main_id = '{$transMainID}'" )) {
			$transMaster->f_hold_job = 1;
			$transMaster->f_hold_type = 1;
			$transMaster->f_hold_uid = $sessionMgr->getCurrentAccID ();
			$transMaster->Update ();
			$response ['success'] = 1;
		} else {
			$response ['success'] = 0;
		}
		echo json_encode ( $response );
	}
	
	/**
	 * action /change-flag-internal/ ทำการ toggle flag ขอออกเลขภายใน
	 *
	 */
	public function changeFlagInternalAction() {
		//global $sessionMgr;
		
		//include_once 'DocMain.Entity.php';
		
		$docID = $_POST ['id'];
		
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		
		if ($docMain->f_gen_int_bookno == 0) {
			$docMain->f_gen_int_bookno = 1;
		} else {
			$docMain->f_gen_int_bookno = 0;
		}
		$docMain->Update ();
		
		$response = Array ();
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	/**
	 * action /change-flag-external/ ทำการ toggle flag ขอออกเลขภายนอก
	 *
	 */
	public function changeFlagExternalAction() {
		//global $sessionMgr;
		
		//include_once 'DocMain.Entity.php';
		$docID = $_POST ['id'];
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		if ($docMain->f_gen_ext_bookno == 0) {
			$docMain->f_gen_ext_bookno = 1;
		} else {
			$docMain->f_gen_ext_bookno = 0;
		}
		$docMain->Update ();
		$response = Array ();
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	public function changeFlagCommandAction() {
		//global $sessionMgr;
		
		//include_once 'DocMain.Entity.php';
		$docID = $_POST ['id'];
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		if ($docMain->f_request_order == 0) {
			$docMain->f_request_order = 1;
		} else {
			$docMain->f_request_order = 0;
		}
		$docMain->Update ();
		$response = Array ();
		$response ['success'] = 1;
		echo json_encode ( $response );
	}

/*  สลับค่าการใช้งานของผู้ส่งภายนอก */

	public function changeFlagExtSenderAction() {
		$senderID = $_POST ['extSenderID'];
		$extSender = new ExternalSenderEntity ( );
		$extSender->Load ( "f_ext_sender_id = '$senderID'" );
		if ($extSender->f_ext_sender_status == 0) {
			$extSender->f_ext_sender_status = 1;
		} else {
			$extSender->f_ext_sender_status = 0;
		}
		$extSender->Update ();
		$response = Array ();
		$response ['status'] = $extSender->f_ext_sender_status;
		$response ['success'] = 1;
		echo json_encode ( $response );
	}

/*  ลบรายชื่อผู้ส่งภายนอก  */

	public function deleteExtSenderAction() {
		$senderID = $_POST ['extSenderID'];
		$extSender = new ExternalSenderEntity ( );
		if($extSender->Load ( "f_ext_sender_id = '$senderID'" )){
			if($extSender->Delete()){
				$response['success'] = 1;
			}else{
				$response['success'] = 0;
			}
		}else{
			$response['success'] = 0;
		}
		echo json_encode ( $response );
	}

/* ตรวจสอบและส่งค่า id ของผู้ส่งภายนอก */

	public function getExternalSenderAction() {
		$senderName = UTFDecode($_POST ['extSenderTitle']);
		$extSender = new ExternalSenderEntity ( );
		$extSender->Load ( "f_ext_sender_name = '$senderName'");
		$response = Array ();
		$response ['extsenderid'] = $extSender->f_ext_sender_id;
		$response ['extstatus'] = $extSender->f_ext_sender_status;
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	/**
	 * action /abort/ ทำการยกเลิกทั้ง transaction
	 *
	 */
	public function abortAction() {
		//global $sessionMgr;
		
		//include_once 'TransMasterDf.Entity.php';
		$transID = $_POST ['id'];
		list ( $transMainID, $transMainSeq, $recvID ) = explode ( "_", $transID );
		$response = Array ();
		$transMaster = new TransMasterDfEntity ( );
		if ($transMaster->Load ( "f_trans_main_id = '{$transMainID}'" )) {
			$transMaster->f_abort = 1;
			$transMaster->Update ();
			$response ['success'] = 1;
		} else {
			$response ['success'] = 0;
		}
		echo json_encode ( $response );
	}

	/**
	 * action /unhold/ ยกเลิกการหยุดของ transaction
	 *
	 */
	public function unholdAction() {
		//global $sessionMgr;

		//include_once 'TransMasterDf.Entity.php';
		$transID = $_POST ['id'];
		$transArray = explode ( "_", $transID );
		$response = Array ();
		$transMaster = new TransMasterDfEntity ( );
		if ($transMaster->Load ( "f_trans_main_id = '{$transArray[0]}'" )) {
			$transMaster->f_hold_job = 0;
			$transMaster->f_hold_type = 0;
			$transMaster->f_hold_uid = 0;
			$transMaster->Update ();
			$response ['success'] = 1;
		} else {
			$response ['success'] = 0;
		}
		echo json_encode ( $response );
	}
	
	/**
	 * action /save-command/ ทำการบันทึกคำสั่งการ
	 *
	 */
	public function saveCommandAction() {
		global $sequence;
		global $sessionMgr;
		
		//include_once 'Command.Entity.php';
		$commandText = $_POST ['commandText'];
		$commanderHiddenID = $_POST ['commanderHiddenID'];
		$commandTransType = $_POST ['commandTransType'];
		//$commandTransCode = $_POST ['commandTransCode'];
		
		if($commandTransType == 'RI'){
			$commandTransCode = explode ( ",", $_COOKIE ['fwD'] );
		}else{
			$commandTransCode = explode ( ",", $_POST ['commandTransCode'] );
		}
		$recvRecordCount = count($commandTransCode);

		for($i=0;$i<$recvRecordCount;$i++){		
		$command = new CommandEntity ( );
		if (! $sequence->isExists ( 'cmdID' )) {
			$sequence->create ( 'cmdID' );
		}
		$commandID = $sequence->get ( 'cmdID' );
		$command->f_cmd_id = $commandID;
		$command->f_type = 0;
		$command->f_command_text = UTFDecode ( $commandText );
		
		list ( $transMainID, $transSeq, $transSubID ) = explode ( '_', $commandTransCode[$i] );
		$command->f_trans_main_id = $transMainID;
		$command->f_trans_main_seq = $transSeq;
		$command->f_sub_trans_id = $transSubID;
		$command->f_acc_id = $sessionMgr->getCurrentAccID ();
		$command->f_cmd_acc_id = $sessionMgr->getCurrentAccID ();
		$command->f_cmd_role_id = $commanderHiddenID;
		$command->f_cmd_org_id = $sessionMgr->getCurrentOrgID ();
		$command->f_timestamp = time ();
		$command->Save ();
		$response = Array ();
		$response ['success'] = 1;
		} //ปิด loop for
		echo json_encode ( $response );
	}
	
	/**
	 * action /edit-command/ ทำการบันทึกคำสั่งการ
	 *
	 */
	public function editCommandAction() {
		global $sequence;
		global $sessionMgr;

		//include_once 'Command.Entity.php';
		$commandID = $_POST['editCommandID'];
		$commandText = $_POST ['editCommandText'];
		$commanderHiddenID = $_POST ['editCommanderHiddenID'];
		$commandTransType = $_POST ['editCommandTransType'];
		$commandTransCode = $_POST ['editCommandTransCode'];
		$command = new CommandEntity ( );
		
		$command->Load("f_cmd_id = '{$commandID}'");
		$command->f_type = 0;
		$command->f_command_text = UTFDecode ( $commandText );
		list ( $transMainID, $transSeq, $transSubID ) = explode ( '_', $commandTransCode );
		$command->f_trans_main_id = $transMainID;
		$command->f_trans_main_seq = $transSeq;
		$command->f_sub_trans_id = $transSubID;
		$command->f_acc_id = $sessionMgr->getCurrentAccID ();
		$command->f_cmd_acc_id = $sessionMgr->getCurrentAccID ();
		if($commanderHiddenID){
		$command->f_cmd_role_id = $commanderHiddenID;
		} else {
			$command->f_cmd_role_id = $_SESSION['roleID'];
		}
		$command->f_cmd_org_id = $sessionMgr->getCurrentOrgID ();
		$command->f_timestamp = time ();
		$command->Update ();
		$response = Array ();
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	/**
	 * action /approve-recv-trans/ ทำการอนุมัติหนังสือ
	 *
	 */
	public function approveRecvTransAction() {
		
		$recvID = explode ( ",", $_COOKIE ['fwD'] );
		$recvRecordCount = count($recvID);

		for($i=0;$i<$recvRecordCount;$i++){

		list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $recvID[$i] );
		$DFrecvTrans = new TransDfRecvEntity ( );
		if (! $DFrecvTrans->Load ( "f_recv_trans_main_id = '{$transMainID}' and f_recv_trans_main_seq = '{$transMainSeq}' and f_recv_id = '{$transID}'" )) {
			$response = Array ();
			$response ['success'] = 0;
			echo json_encode ( $response );
		} else {
			if ($DFrecvTrans->f_is_circ == 1) {
				$dfTrans = new DFTransaction ( );
				$dfTrans->CreateOrganizeMemberReceiveRecord ( $transMainID, $transMainSeq, $transID );
			}
			$DFrecvTrans->f_governer_approve = 1;
			$DFrecvTrans->Update ();
			$response = Array ();
			$response ['success'] = 1;
		}		
		} //ปิดloop for			
			if($response ['success'] == 1){
			echo json_encode ( $response );
		}
	}

	
	/**
	 * action /request-global-regno/ ขอเลขภายนอกทะเบียนกลาง
	 *
	 */
	public function requestGlobalRegnoAction() {
		global $sequence;
		global $sessionMgr;
		$orgID = 0;
		$recvType = 3;
		$sendType = 3;
		$sendType2 = 7;
		$regBookID = 0;
		
		$this->checkDefaultDFSequence ( $orgID, $regBookID );
		
		//constructing the result
		$result = Array ('success' => 1, 'recv' => $sequence->getLast ( "receiveRegNo_{$orgID}_{$recvType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'send' => $sequence->getLast ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'send2' => $sequence->getLast ( "sendRegNo_{$orgID}_{$sendType2}_{$regBookID}_{$sessionMgr->getCurrentYear()}" ) );
		
		//return JSON Response to requester
		echo json_encode ( $result );
	}
	
	/**
	 * action/request-bookno/ ขอเลขที่หนังสือ
	 *
	 */
	public function requestBooknoAction() {
		global $sequence;
		global $sessionMgr;
		
		//include_once 'Organize.Entity.php';
		$orgID = $_POST ['orgID'];
		if ($orgID == 0) {
			$intCircDocCode = 'RunIntCircGlobal.' . $sessionMgr->getCurrentYear ();
			$intDocCode = 'RunIntGlobal.' . $sessionMgr->getCurrentYear ();
			$extDocCode = 'RunExtGlobal.' . $sessionMgr->getCurrentYear ();
		} else {
			$org = new OrganizeEntity ( );
			if (! $org->Load ( "f_org_id = '$orgID'" )) {
				$result = Array ('success' => 0 );
				echo json_encode ( $result );
				die ();
			} else {
				//$intDocCode = $org->f_int_code;
				//$extDocCode = $org->f_ext_code;
				$intCircDocCode = "RunIntCirc.{$sessionMgr->getCurrentYear()}.{$org->f_int_code}";
				$intDocCode = "RunInt.{$sessionMgr->getCurrentYear()}.{$org->f_int_code}";
				$extDocCode = "RunExt.{$sessionMgr->getCurrentYear()}.{$org->f_ext_code}";
			}
		}
		
		if (! $sequence->isExists ( $intDocCode )) {
			$sequence->create ( $intDocCode );
		}
		
		if (! $sequence->isExists ( $intCircDocCode )) {
			$sequence->create ( $intCircDocCode );
		}
		
		if (! $sequence->isExists ( $extDocCode )) {
			$sequence->create ( $extDocCode );
		}
		$result = Array ('success' => 1, 'circIntBookNo' => $sequence->getLast ( $intCircDocCode ), 'intBookNo' => $sequence->getLast ( $intDocCode ), 'extBookNo' => $sequence->getLast ( $extDocCode ) );
		//return JSON Response to requester
		echo json_encode ( $result );
	}
	
	/**
	 * action /request-regno/ ขอเลขทะเบียน
	 *
	 */
	public function requestRegnoAction() {
		global $sequence;
		global $sessionMgr;
		
		$orgID = $_POST ['orgID'];
		$regBookID = 0;
		
		$this->checkDefaultDFSequence ( $orgID, $regBookID );
		
		//constructing the result
		$result = Array (
			'success' => 1, 
			'recvInt' => $sequence->getLast ( "receiveRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 
			'recvExt' => $sequence->getLast ( "receiveRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 
			'recvClass' => $sequence->getLast ( "receiveRegNo_374_4_{$regBookID}_{$sessionMgr->getCurrentYear()}" ),
			'recvClassInt' => $sequence->getLast ( "receiveRegNo_{$orgID}_6_{$regBookID}_{$sessionMgr->getCurrentYear()}" ),
			'recvClassExt' => $sequence->getLast ( "receiveRegNo_{$orgID}_7_{$regBookID}_{$sessionMgr->getCurrentYear()}" ),
			'recvCirc' => $sequence->getLast ( "receiveRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 
			'sendInt' => $sequence->getLast ( "sendRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 
			'sendExt' => $sequence->getLast ( "sendRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 
			'sendClass' => $sequence->getLast ( "sendRegNo_374_4_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 
			'sendClassInt' => $sequence->getLast ( "sendRegNo_{$orgID}_10_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 
			'sendClassExt' => $sequence->getLast ( "sendRegNo_{$orgID}_9_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 
			'sendCirc' => $sequence->getLast ( "sendRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}" ) );
		//return JSON Response to requester
		echo json_encode ( $result );
	}
	
	/**
	 * action /request-stats/ ขอสถิติงานหนังสือ
	 *
	 */
	public function requestStatsAction() {
		global $sequence;
		
		//include_once 'DFTransaction.php';
		
		$dfTrans = new DFTransaction ( );
		
		$orgID = $_POST ['orgID'];
		$regBookID = 0;
		
		$this->checkDefaultDFSequence ( $orgID, $regBookID );
		
		//constructing the result
		$result = Array ('success' => 1, 'recvInt' => $sequence->getLast ( "receiveRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'sendInt' => $sequence->getLast ( "sendRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'recvExt' => $sequence->getLast ( "receiveRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'sendExt' => $sequence->getLast ( "sendRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'recvExtGlobal' => $sequence->getLast ( "receiveRegNo_{$orgID}_3_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'sendExtGlobal' => $sequence->getLast ( "sendRegNo_{$orgID}_3_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'recvClass' => $sequence->getLast ( "receiveRegNo_{$orgID}_4_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'sendClass' => $sequence->getLast ( "sendRegNo_{$orgID}_4_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'recvCirc' => $sequence->getLast ( "receiveRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'sendCirc' => $sequence->getLast ( "sendRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}" ), 'unreceive' => $dfTrans->getUnreceivedOrgItemCount ( $orgID ), 'waitCommand' => $dfTrans->getPersonalReceivedOrgCount ( $orgID ), 'sendback' => $dfTrans->getSendbackOrgItemCount ( $orgID ), 'callback' => $dfTrans->getCallBackOrgCount ( $orgID ), 'working' => $dfTrans->getOrderAssignedOrgCount ( $orgID ), 'complete' => $dfTrans->getCompletedOrgItem ( $orgID ) );
		
		//return JSON Response to requester
		echo json_encode ( $result );
	}
	
	/**
	 * action /set-global-regno/ ทำการตั้งเลขทะเบียนกลาง
	 *
	 */
	public function setGlobalRegnoAction() {
		global $sequence;
		global $sessionMgr;
		$orgID = 0;
		$recvType = 3;
		$sendType = 3;
		$sendType2 = 7;
		$regBookID = 0;
		$recvExt = $_POST ['recvExternalRegNo'];
		$sendExt = $_POST ['sendExternalRegNo'];
		$sendInt = $_POST ['sendInternalRegNo'];
		
		$this->checkDefaultDFSequence ( $orgID );
		
		$sequence->set ( "receiveRegNo_{$orgID}_{$recvType}_{$regBookID}_{$sessionMgr->getCurrentYear()}", $recvExt );
		$sequence->set ( "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}", $sendExt );
		$sequence->set ( "sendRegNo_{$orgID}_{$sendType2}_{$regBookID}_{$sessionMgr->getCurrentYear()}", $sendInt );
		
		//constructing the result
		$result = Array ('success' => 1 );
		
		//return JSON Response to requester
		echo json_encode ( $result );
	}
	
	/**
	 * action /set-bookno/ ทำการตั้งเลขที่หนังสือ
	 *
	 */
	public function setBooknoAction() {
		global $sequence;
		global $sessionMgr;
		
		$orgID = $_POST ['editBookNoOrgID'];
		$intBookNo = $_POST ['intBookNo'];
		$extBookNo = $_POST ['extBookNo'];
		$circIntBookNo = $_POST ['circIntBookNo'];
		
		if ($orgID == 0) {
			//$intDocCode = 'globalIntBookNo';
			//$extDocCode = 'globalExtBookNo';
			$circIntBookNo = 0;
			$intDocCode = 'RunIntGlobal.' . $sessionMgr->getCurrentYear ();
			$extDocCode = 'RunExtGlobal.' . $sessionMgr->getCurrentYear ();
			$circIntDocCode = "RunIntCirc.{$sessionMgr->getCurrentYear()}";
			//$extDocCode = 'RunExtGlobal.'.$sessionMgr->getCurrentYear();
		} else {
			$org = new OrganizeEntity ( );
			if (! $org->Load ( "f_org_id = '$orgID'" )) {
				$result = Array ('success' => 0 );
				echo json_encode ( $result );
				die ();
			} else {
				//$intDocCode = $org->f_int_code;
				//$extDocCode = $org->f_ext_code;                     
				$intDocCode = "RunInt.{$sessionMgr->getCurrentYear()}.{$org->f_int_code}";
				$extDocCode = "RunExt.{$sessionMgr->getCurrentYear()}.{$org->f_ext_code}";
				$circIntDocCode = "RunIntCirc.{$sessionMgr->getCurrentYear()}.{$org->f_int_code}";
			}
		}
		
		if (! $sequence->isExists ( $intDocCode )) {
			$sequence->create ( $intDocCode );
		}
		
		if (! $sequence->isExists ( $extDocCode )) {
			$sequence->create ( $extDocCode );
		}
		
		if (! $sequence->isExists ( $circIntDocCode )) {
			$sequence->create ( $circIntDocCode );
		}
		
		$sequence->set ( $circIntDocCode, $circIntBookNo );
		$sequence->set ( $intDocCode, $intBookNo );
		$sequence->set ( $extDocCode, $extBookNo );
		
		$result = Array ('success' => 1 );
		
		//return JSON Response to requester
		echo json_encode ( $result );
	}
	
	/**
	 * action /set-regno/ ทำการตั้งค่าเลขทะเบียน
	 *
	 */
	public function setRegnoAction() {
		global $sequence;
		global $sessionMgr;
		
		if (! array_key_exists ( 'editLocalOrgIDSaraban', $_POST )) {
		$orgID = $_POST ['editLocalOrgID'];
		} else {
			$orgID = $_POST ['editLocalOrgIDSaraban'];
		}

		$regBookID = 0;
		
		$this->checkDefaultDFSequence ( $orgID, $regBookID );

		if ($orgID == 374) {
			$recvInt = $_POST ['recvInternalLocalSaraban'];
			$recvExt = $_POST ['recvExternalLocalSaraban'];	
			$recvClass = $_POST ['recvClassifiedSaraban'];
			$recvCirc = $_POST ['recvCircSaraban'];

			$sendInt = $_POST ['sendInternalLocalSaraban'];
			$sendExt = $_POST ['sendExternalLocalSaraban'];		
			$sendClass = $_POST ['sendClassifiedSaraban'];
			$sendCirc = $_POST ['sendCircSaraban'];
		} else {
		$recvInt = $_POST ['recvInternalLocal'];
		$recvExt = $_POST ['recvExternalLocal'];
			$recvClassInt = $_POST ['recvClassifiedInt'];
			$recvClassExt = $_POST ['recvClassifiedExt'];
		$recvCirc = $_POST ['recvCirc'];
		
		$sendInt = $_POST ['sendInternalLocal'];
		$sendExt = $_POST ['sendExternalLocal'];
			$sendClassInt = $_POST ['sendClassifiedInt'];
			$sendClassExt = $_POST ['sendClassifiedExt'];	
		$sendCirc = $_POST ['sendCirc'];
		}
		
		$sequence->set ( "receiveRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}", $recvInt );
		$sequence->set ( "receiveRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}", $recvExt );
		$sequence->set ( "receiveRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}", $recvCirc );
		
		$sequence->set ( "sendRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}", $sendInt );
		$sequence->set ( "sendRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}", $sendExt );
		$sequence->set ( "sendRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}", $sendCirc );

		if ($orgID == 374) {
			$sequence->set ( "receiveRegNo_{$orgID}_4_{$regBookID}_{$sessionMgr->getCurrentYear()}", $recvClass );
			$sequence->set ( "sendRegNo_{$orgID}_4_{$regBookID}_{$sessionMgr->getCurrentYear()}", $sendClass );
		} else {
			$sequence->set ( "receiveRegNo_{$orgID}_6_{$regBookID}_{$sessionMgr->getCurrentYear()}", $recvClassInt );
			$sequence->set ( "receiveRegNo_{$orgID}_7_{$regBookID}_{$sessionMgr->getCurrentYear()}", $recvClassExt );
			$sequence->set ( "sendRegNo_{$orgID}_8_{$regBookID}_{$sessionMgr->getCurrentYear()}", $sendClassInt );
			$sequence->set ( "sendRegNo_{$orgID}_9_{$regBookID}_{$sessionMgr->getCurrentYear()}", $sendClassExt );	
		}

		//constructing the result
		$result = Array ('success' => 1 );
		
		//return JSON Response to requester
		echo json_encode ( $result );
	}
	
	/**
	 * ทำการตรวจสอบ sequence default ของระบบสารบรรณ
	 *
	 * @param int $orgID
	 * @param int $regBookID
	 */
	public function checkDefaultDFSequence($orgID, $regBookID = 0) {
		global $sequence;
		global $sessionMgr;
		//Create Sequence if not exists(Organization Level);
		

		if (! $sequence->isExists ( "receiveRegNo_0_3_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "receiveRegNo_0_3_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		
		if (! $sequence->isExists ( "sendRegNo_0_3_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "sendRegNo_0_3_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		
		if (! $sequence->isExists ( "sendRegNo_0_7_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "sendRegNo_0_7_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		//Create Sequence if not exists(OU Level)
		//Receive Internal Regno
		if (! $sequence->isExists ( "receiveRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "receiveRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		//Receive External Regno
		if (! $sequence->isExists ( "receiveRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "receiveRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		//Receive Classified Regno
		if (! $sequence->isExists ( "receiveRegNo_{$orgID}_4_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "receiveRegNo_{$orgID}_4_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		if (! $sequence->isExists ( "receiveRegNo_{$orgID}_6_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "receiveRegNo_{$orgID}_6_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		if (! $sequence->isExists ( "receiveRegNo_{$orgID}_7_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "receiveRegNo_{$orgID}_7_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		
		if (! $sequence->isExists ( "receiveRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "receiveRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		//Send Internal Regno
		if (! $sequence->isExists ( "sendRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "sendRegNo_{$orgID}_1_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		//Send External Regno
		if (! $sequence->isExists ( "sendRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "sendRegNo_{$orgID}_2_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		//Send Classified Regno
		if (! $sequence->isExists ( "sendRegNo_{$orgID}_4_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "sendRegNo_{$orgID}_4_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		if (! $sequence->isExists ( "sendRegNo_{$orgID}_8_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "sendRegNo_{$orgID}_8_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		if (! $sequence->isExists ( "sendRegNo_{$orgID}_9_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "sendRegNo_{$orgID}_9_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
		
		if (! $sequence->isExists ( "sendRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}" )) {
			$sequence->create ( "sendRegNo_{$orgID}_5_{$regBookID}_{$sessionMgr->getCurrentYear()}" );
		}
	}
	
	/**
	 * action /assign-order/ ทำการมอบหมายงาน
	 *
	 */
	public function assignOrderAction() {
		global $sequence;
		global $sessionMgr;
		global $util;
		global $requestTimestamp;
		
		//require_once 'Account.Entity.php';
		//require_once 'TransMasterDf.Entity.php';
		//require_once 'DocMain.Entity.php';
		//$notifier = new Notifier ( );
		
		$orderRecieverID = $_POST ['orderRecieverID'];
		//$transRefID = $_POST ['orderRecieverRefID'];

		$transRefID = explode ( ",", $_COOKIE ['fwD'] );		
		$recvRecordCount = count($transRefID);

		for($i=0;$i<$recvRecordCount;$i++){

		$order = new OrderEntity ( );
		if (! $sequence->isExists ( 'orderID' )) {
			$sequence->create ( 'orderID' );
		}
		$order->f_order_id = $sequence->get ( 'orderID' );
		$order->f_assign_uid = $sessionMgr->getCurrentAccID ();
		$order->f_received_uid = $orderRecieverID;
		$order->f_assigned_timestamp = $requestTimestamp;
		$order->f_close_timestamp = 0;
		$order->f_complete = 0;
		$order->f_expected_finish = 0;
		$order->f_report_text = '';
		$order->f_report_timestamp = 0;
		//Received Transaction Only
		//กรณีนี้จะมีเฉพาะรายการรับของ Transaction เท่านั้น

		list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $transRefID[$i] );
		$order->f_trans_main_id = $transMainID;
		$order->f_trans_main_seq = $transMainSeq;
		$order->f_trans_id = $transID;
		$order->f_org_id = $sessionMgr->getCurrentOrgID ();
		
		if (! $order->Save ()) {
			$response = Array ('success' => 0 );
		} else {
			$accountReceiver = new AccountEntity ( );
			$accountReceiver->Load ( "f_acc_id = '$order->f_received_uid'" );
			$accountSender = new AccountEntity ( );
			$accountSender->Load ( "f_acc_id = '$order->f_assign_uid'" );
			$transMaster = new TransMasterDfEntity ( );
			$transMaster->Load ( "f_trans_main_id = '$transMainID'" );
			$document = new DocMainEntity ( );
			$document->Load ( "f_doc_id = '{$transMaster->f_doc_id}'" );
			$util->sendJabberNotifier ( $accountReceiver->f_login_name, "คุณได้รับการมอบหมายหนังสือเลขที่{$document->f_doc_no} เรื่อง \"{$document->f_title}\" จาก {$accountSender->f_name} {$accountSender->f_last_name}" );
			$response = Array ('success' => 1 );
		}


		} //ปิด loop for
		
		echo json_encode ( $response );
	}
	
	public function reassignOrderAction() {
		global $sequence;
		global $sessionMgr;
		global $util;
		global $requestTimestamp;
		
		//require_once 'Account.Entity.php';
		//require_once 'TransMasterDf.Entity.php';
		//require_once 'DocMain.Entity.php';
		
		//$notifier = new Notifier ( );
		
		$orderID = $_POST ['orderID'];
		$orderRecieverID = $_POST ['orderRecieverID2'];
		$transRefID = $_POST ['orderRecieverRefID2'];
		$order = new OrderEntity ( );
		/*
		if (! $sequence->isExists ( 'orderID' )) {
			$sequence->create ( 'orderID' );
		}
		$order->f_order_id = $sequence->get ( 'orderID' );*/
		$order->Load("f_order_id='{$orderID}'");
		$order->f_assign_uid = $sessionMgr->getCurrentAccID ();
		$order->f_received_uid = $orderRecieverID;
		$order->f_assigned_timestamp = $requestTimestamp;
		$order->f_close_timestamp = 0;
		$order->f_complete = 0;
		$order->f_expected_finish = 0;
		$order->f_report_text = '';
		$order->f_report_timestamp = 0;
		//Received Transaction Only
		//กรณีนี้จะมีเฉพาะรายการรับของ Transaction เท่านั้น
		list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $transRefID );
		$order->f_trans_main_id = $transMainID;
		$order->f_trans_main_seq = $transMainSeq;
		$order->f_trans_id = $transID;
		$order->f_org_id = $sessionMgr->getCurrentOrgID ();
		
		if (! $order->Update()) {
			$response = Array ('success' => 0 );
		} else {
			$accountReceiver = new AccountEntity ( );
			$accountReceiver->Load ( "f_acc_id = '$order->f_received_uid'" );
			$accountSender = new AccountEntity ( );
			$accountSender->Load ( "f_acc_id = '$order->f_assign_uid'" );
			$transMaster = new TransMasterDfEntity ( );
			$transMaster->Load ( "f_trans_main_id = '$transMainID'" );
			$document = new DocMainEntity ( );
			$document->Load ( "f_doc_id = '{$transMaster->f_doc_id}'" );
			$util->sendJabberNotifier ( $accountReceiver->f_login_name, "คุณได้รับการมอบหมายหนังสือเลขที่{$document->f_doc_no} เรื่อง \"{$document->f_title}\" จาก {$accountSender->f_name} {$accountSender->f_last_name}" );
			$response = Array ('success' => 1 );
		}
		echo json_encode ( $response );
	}
	
	/**
	 * action /callback-document/ ทำการดึงคืนหนังสือ
	 *
	 */
	function callbackDocumentAction() {
		//global $sequence;
		global $util;
		global $sessionMgr;
		
		//require_once 'DFTransaction.php';
		if (! array_key_exists ( 'callbackRefID', $_POST )) {
			//not found sendRecordID
		} else {
			
			$sendRecordID = $_POST ['callbackRefID'];
			$callbackText = $_POST ['callbackText'];
			//$sendRecordID = $_COOKIE ['sendRecordID'];
			list ( $sendTransMainID, $sendTransMainSeq, $sendID ) = explode ( "_", $sendRecordID );
			//$receiveType = $_POST['acceptAsType'];
			$docDeliverType = 1;
			$regBookID = 0;
			$recvRegNo = 'Default';
			$recvDate = 'Default';
			$recvTime = 'Default';
			$dfTrans = new DFTransaction ( );
			$parentTransaction = $dfTrans->getSendTransaction ( $sendTransMainID, $sendTransMainSeq );
			//var_dump($parentTransaction);
			$sendName = $util->parseFullname ( $parentTransaction ['f_sender_uid'], $parentTransaction ['f_sender_role_id'], $parentTransaction ['f_sender_org_id'] );
			$sendOrg = "";
			$attendName = $util->parseFullname ( $parentTransaction ['f_receiver_uid'], $parentTransaction ['f_receiver_role_id'], $parentTransaction ['f_receiver_org_id'] );
			$attendOrg = "";
			
			//$senderOrgID = $sessionMgr->getCurrentOrgID ();
			//$senderRoleID = $sessionMgr->getCurrentRoleID ();
			//$senderUID = $sessionMgr->getCurrentAccID ();
			

			//$response = $dfTrans->createReceiveTransaction($sendTransMainID,$receiveType,$docDeliverType,1,$regBookID,$recvRegNo,0,0,0,0,0,$recvDate,$recvTime,$sendName,$sendOrg,$attendName,$attendOrg,$parentTransaction['f_sender_uid'],$parentTransaction['f_sender_role_id'],$parentTransaction['f_sender_org_id']); 
			//if($response['success'] == 1) {
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_callback', 1 );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_callback_uid', $sessionMgr->getCurrentAccID () );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_callback_role_id', $sessionMgr->getCurrentRoleID () );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_callback_org_id', $sessionMgr->getCurrentOrgID () );
			$dfTrans->modifySendTransaction ( $sendTransMainID, $sendTransMainSeq, 'f_callback_comment', UTFDecode ( $callbackText ) );
			//}
			$response = Array ();
			echo json_encode ( $response );
		
		}
	}
}
