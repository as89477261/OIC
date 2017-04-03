<?php
/**
 * Class จัดการ Transaction งานสารบรรณ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Transaction Class
 */
class DFTransaction {
	/**
	 * Constructor ตรวจสอบ และสร้าง Sequence DFMainTransID
	 *
	 */
	public function __construct() {
		global $sequence;
		if (! $sequence->isExists ( 'DFMainTransID' )) {
			$sequence->create ( 'DFMainTransID' );
		}
	}

	/**
	 * ขอจำนวนหนังสือดึงคืน
	 *
	 * @return int
	 */
	public function getCallBackCount() {
		global $conn;
		global $sessionMgr;
		$sqlCount = "select count(*) as COUNT_EXP from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_callback = 1 ";
		$sqlCount .= " and (a.f_callback_ack = 0 or a.f_callback_ack is null)";
		$sqlCount .= " and a.f_callback_role_id = {$sessionMgr->getCurrentRoleID()}";
		$sqlCount .= " and a.f_callback_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()}";
		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนหนังสือดึงคืนของหน่วยงาน
	 *
	 * @param int $org
	 * @return int
	 */
	public function getCallBackOrgCount($org) {
		global $conn;
		//global $sessionMgr;

		$sqlCount = "select count(*) as COUNT_EXP from tbl_trans_df_send a where ";
		$sqlCount .= " a.f_callback = 1 ";
		$sqlCount .= " and (a.f_callback_ack = 0 or a.f_callback_ack is null)";
		//$sqlCount .= " and a.f_callback_role_id = {$sessionMgr->getCurrentRoleID()}";
		$sqlCount .= " and a.f_callback_org_id = {$org}";

		$rsCount = $conn->Execute ( $sqlCount );

		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ตรวจสอบชื่อหน่วยงานภายนอก และสร้างถ้าไม่มี
	 *
	 * @param string $name
	 */
	public function externalOrg($name,$oldName) {
		global $sequence;

		$oldName = trim($oldName);
		$oldExternalOrg = new ExternalSenderEntity();
		if ($oldExternalOrg->Load ( "f_ext_sender_name = '".str_replace( "'", "''", $oldName )."'" )) {
			$oldExternalOrg->f_ext_sender_status = 0;
			$oldExternalOrg->Save();
		}

		$name = trim($name);
		$externalOrg = new ExternalSenderEntity();
		if (!$externalOrg->Load ( "f_ext_sender_name = '".str_replace( "'", "''", $name )."'" )) {
			$addExtOrg = new ExternalSenderEntity();
			$addExtOrg->f_ext_sender_id = $sequence->get ( 'ExtSender' );
			$addExtOrg->f_ext_sender_name = $name;
			$addExtOrg->f_ext_sender_freq = 0;
			$addExtOrg->f_ext_sender_status = 1;
			$addExtOrg->f_ext_sender_code = 0;
			$addExtOrg->Save();	
		}elseif($oldName != '') {
			$externalOrg->f_ext_sender_status = 1;
			$externalOrg->Save();
			$oldExternalOrg->f_ext_sender_status = 1;
			$oldExternalOrg->Save();
		}else {
			unset ( $externalOrg );
		}
	}

	/**
	 * สร้าง Main Transaction และส่งคืน Transaction Main ID
	 *
	 * @param int $docID
	 * @param string $startType
	 * @param int $ownerType
	 * @param int $security
	 * @param int $speed
	 * @return int
	 */
	public function createMainTransaction($docID, $startType = 'RI', $ownerType = 1, $security, $speed) {
		global $sequence;
		global $sessionMgr;

		$now = time ();
		$yearNow = date ( 'Y', $now ) + 543;
		$transID = $sequence->get ( 'DFMainTransID' );
		$transMas = new TransMasterDfEntity ( );
		$transMas->f_trans_main_id = $transID;
		$transMas->f_doc_id = $docID;
		$transMas->f_start_type = $startType;
		$transMas->f_start_stamp = time ();
		$transMas->f_start_date = "$yearNow" . date ( 'md', $now );
		$transMas->f_start_time = date ( 'Hi', $now );
		$transMas->f_stop_stamp = 0;
		$transMas->f_stop_date = '';
		$transMas->f_stop_time = '';
		$transMas->f_owner_type = $ownerType;
		$transMas->f_trans_year = ( int ) date ( 'Y', $now );
		switch ($ownerType) {
			case 1 :
				$transMas->f_owner_id = $sessionMgr->getCurrentAccID ();
				$transMas->f_owner_role_id = $sessionMgr->getCurrentRoleID ();
				$transMas->f_owner_org_id = $sessionMgr->getCurrentOrgID ();
				break;
			case 2 :
				$transMas->f_owner_id = 0;
				$transMas->f_owner_role_id = $sessionMgr->getCurrentRoleID ();
				$transMas->f_owner_org_id = $sessionMgr->getCurrentOrgID ();
				break;
			case 3 :
				$transMas->f_owner_id = 0;
				$transMas->f_owner_role_id = 0;
				$transMas->f_owner_org_id = $sessionMgr->getCurrentOrgID ();
				break;
		}

		$transMas->f_security_modifier = $security;
		$transMas->f_urgent_modifier = $speed;
		$transMas->f_hold_job = 0;
		$transMas->f_hold_type = 0;
		$transMas->f_hold_uid = 0;
		$transMas->f_hold_role_id = 0;
		$transMas->f_hold_org_id = 0;
		$transMas->f_auto_route = 0;
		$transMas->f_route_seq = 0;
		$transMas->Save ();
		return $transID;
	}

	/**
	 * สร้าง Transaction ในการลงรับ
	 *
	 * @param int $transMainID
	 * @param int $recvType
	 * @param int $docDeliverType
	 * @param int $showInRegBook
	 * @param int $regBookID
	 * @param string $recvRegNo
	 * @param int $isForwardTrans
	 * @param int $sendID
	 * @param int $sendSeq
	 * @param int $sendMainID
	 * @param int $sendMainSeq
	 * @param string $recvDate
	 * @param string $recvTime
	 * @param string $sendFullname
	 * @param string $sendOrgName
	 * @param string $attendFullName
	 * @param string $attendOrgName
	 * @param int $senderUID
	 * @param int $senderRoleID
	 * @param int $senderOrgID
	 * @param int $recvDocType
	 * @param int $receiverUID
	 * @param int $receiverRoleID
	 * @param inr $receiverOrgID
	 * @return array of result
	 */
	public function &createReceiveTransaction($transMainID, $recvType, $docDeliverType, $showInRegBook, $regBookID, $recvRegNo, $isForwardTrans, $sendID, $sendSeq, $sendMainID, $sendMainSeq, $recvDate, $recvTime, $sendFullname, $sendOrgName, $attendFullName, $attendOrgName, $senderUID = 0, $senderRoleID = 0, $senderOrgID = 0, $recvDocType = 0, $receiverUID = 0, $receiverRoleID = 0, $receiverOrgID = 0) {
		global $sequence;
		global $sessionMgr;
		global $util;
		global $config;

		$orgID = $sessionMgr->getCurrentOrgID ();
		$roleID = $sessionMgr->getCurrentRoleID ();
		$accID = $sessionMgr->getCurrentAccID ();

		$now = time ();
		$yearNow = date ( 'Y', $now );

		if (! $sequence->isExists ( "DFMainTransSeq_{$transMainID}" )) {
			$sequence->create ( "DFMainTransSeq_{$transMainID}" );
		}

		if (! $sequence->isExists ( "DFRecvTrans_{$transMainID}" )) {
			$sequence->create ( "DFRecvTrans_{$transMainID}" );
		}

		if ($recvType == 3) {
			$recvSequence = "receiveRegNo_0_{$recvType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
		} else {
			$recvSequence = "receiveRegNo_{$orgID}_{$recvType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
		}

		if (! $sequence->isExists ( $recvSequence )) {
			$sequence->create ( $recvSequence );
		}

		$transSeq = $sequence->get ( "DFMainTransSeq_{$transMainID}" );
		$recvTransID = $sequence->get ( "DFRecvTrans_{$transMainID}" );

		$transRecv = new TransDfRecvEntity ( );
		$transRecv->f_recv_trans_main_id = $transMainID;
		$transRecv->f_recv_trans_main_seq = $transSeq;
		$transRecv->f_recv_id = $recvTransID;
		$transRecv->f_recv_type = $recvType;
		if ($recvType == 5) {
			$transRecv->f_is_circ = 1;
		} else {
			$transRecv->f_is_circ = 0;
		}
		$transRecv->f_doc_recv_type = $docDeliverType;
		//$transRecv
		$transRecv->f_show_in_reg_book = $showInRegBook;

		if ($showInRegBook == 1) {
			if ($recvRegNo == 'Default') {
				$recvRegNo = $sequence->get ( $recvSequence );
				$transRecv->f_recv_reg_no = $recvRegNo;
			} else {
				$transRecv->f_recv_reg_no = $recvRegNo;
			}
			$transRecv->f_reg_book_id = $regBookID;
		} else {
			$transRecv->f_recv_reg_no = 0;
			$transRecv->f_reg_book_id = 0;
		}

		$transRecv->f_recv_reg_no_full = $transRecv->f_recv_reg_no;
		if ($isForwardTrans != 0) {
			$transRecv->f_is_forwarded_trans = 1;
			$transRecv->f_send_id = $sendID;
			$transRecv->f_send_seq = $sendSeq;
			$transRecv->f_send_trans_main_id = $sendMainID;
			$transRecv->f_send_trans_main_seq = $sendMainSeq;
		} else {
			$transRecv->f_is_forwarded_trans = 0;
			$transRecv->f_send_id = 0;
			$transRecv->f_send_seq = 0;
			$transRecv->f_send_trans_main_id = 0;
			$transRecv->f_send_trans_main_seq = 0;
		}

		$transRecv->f_read = 0;
		$transRecv->f_forwarded = 0;
		$transRecv->f_accept_uid = $accID;
		$transRecv->f_accept_role_id = $roleID;
		$transRecv->f_accept_org_id = $orgID;
		$transRecv->f_recv_sys_stamp = $now;
		$transRecv->f_recv_stamp = $now;
		$transRecv->f_recv_year = ( int ) date ( 'Y', $now );

		/*
		if ($recvDate == 'Default') {
			$recvDate = $yearNow . date ( 'md', $now );
			$transRecv->f_recv_date = $recvDate;
		} else {
			$recvDate = UTFDecode ( $recvDate );
			$transRecv->f_recv_date = $util->parseDateStrToSLCDateFormat ( $recvDate );
		}

		if ($recvTime == 'Default') {
			$recvTime = date ( 'Hi', $now );
			$transRecv->f_recv_time = $recvTime;
		} else {
			$recvTime = UTFDecode ( $recvTime );
			$recvTime = str_replace ( ":", '', $recvTime );
			$transRecv->f_recv_time = $recvTime;
		}
        */

		if ($recvDate == 'Default') {
			$recvDate = $yearNow . date ( 'md', $now );
			$oriRecvDate = $recvDate;
			$transRecv->f_recv_date = $recvDate;
		} else {
			$recvDate = $util->parseDateStrToSLCDateFormat ( UTFDecode ( $recvDate ) );
			$oriRecvDate = $recvDate;
			$transRecv->f_recv_date = $recvDate;
		}

		//die($origSendDate);
		if ($recvTime == 'Default') {
			$recvTime = date ( 'Hi', $now );
			$origRecvTime = $recvTime;
			$transRecv->f_recv_time = $recvTime;
		} else {
			$recvTime = UTFDecode ( $recvTime );
			$recvTime = str_replace ( ":", '', $recvTime );
			$origRecvTime = $recvTime;
			$transRecv->f_recv_time = $recvTime;
		}

		$transRecv->f_commit = 0;
		$transRecv->f_commit_sys_stamp = 0;
		$transRecv->f_commit_stamp = 0;
		$transRecv->f_commit_date = '';
		$transRecv->f_commit_time = '';
		$transRecv->f_sendback = 0;
		$transRecv->f_sendback_uid = 0;
		$transRecv->f_sendback_role_id = 0;
		$transRecv->f_sendback_org_id = 0;
		$transRecv->f_cancel = 0;
		$transRecv->f_cancel_uid = 0;
		$transRecv->f_cancel_role_id = 0;
		$transRecv->f_cancel_org_id = 0;
		$transRecv->f_is_egif_trans = 0;
		$transRecv->f_egif_recv_stamp = 0;
		$transRecv->f_egif_reply_complete = 0;
		#if (! eregi ( '\u', $sendFullname )) {
			$sendFullname = UTFDecode ( $sendFullname );
		#}

		#if (! eregi ( '\u', $sendOrgName )) {
			$sendOrgName = UTFDecode ( $sendOrgName );
		#}

		#if (! eregi ( '\u', $attendFullName )) {
			$attendFullName = UTFDecode ( $attendFullName );
		#}

		#if (! eregi ( '\u', $attendOrgName )) {
			$attendOrgName = UTFDecode ( $attendOrgName );
		#}

		$transRecv->f_send_fullname = $sendFullname;
		$transRecv->f_send_org_name = $sendOrgName;

		if ($attendFullName == 'Default') {
			$attendFullName = $util->getDefaultReceiveInternalOrgName ();
		}
		$transRecv->f_attend_fullname = $attendFullName;
		$transRecv->f_attend_org_name = $attendOrgName;
		$transRecv->f_recv_doc_type = $recvDocType;

		$transRecv->f_receiver_uid = $receiverUID;
		$transRecv->f_receiver_role_id = $receiverRoleID;
		$transRecv->f_receiver_org_id = $receiverOrgID;

		//TODO : Check Circular Book


		$transRecv->f_governer_approve = 0;

		$transRecv->Save ();
		$transID = $transRecv->f_recv_trans_main_id . '_' . $transRecv->f_recv_trans_main_seq . '_' . $transRecv->f_recv_id;
		$arrayResult = Array ('success' => 1, 'transID' => $transID, 'recvType' => $recvType, 'sequence' => $transSeq, 'regNo' => $recvRegNo, 'recvDate' => UTFEncode ( $util->parseSLCDate ( $recvDate ) ), 'recvTime' => $util->parseSLCTime ( $recvTime ) );
		return $arrayResult;
	}

	/**
	 * สร้าง Transaction สำหรับการส่ง
	 *
	 * @param int $transMainID
	 * @param int $sendType
	 * @param int $receiverUID
	 * @param int $receiverRoleID
	 * @param int $receiverOrgID
	 * @param int $docDeliverType
	 * @param int $showInRegBook
	 * @param int $regBookID
	 * @param string $sendRegNo
	 * @param int $isForwardTrans
	 * @param int $recvID
	 * @param int $recvSeq
	 * @param string $sendDate
	 * @param string $sendTime
	 * @param string $recvFullname
	 * @param string $recvOrgName
	 * @param string $sendFullName
	 * @param string $sendOrgName
	 * @param int $sendDocType
	 * @param int $isCirc
	 * @return array of result
	 */
	public function createSendTransaction($transMainID, $sendType, $receiverUID, $receiverRoleID, $receiverOrgID, $docDeliverType, $showInRegBook, $regBookID, $sendRegNo, $isForwardTrans, $recvID, $recvSeq, $sendDate, $sendTime, $recvFullname, $recvOrgName, $sendFullName, $sendOrgName, $sendDocType = 0, $isCirc = 0) {
		global $sequence;
		global $sessionMgr;
		global $util;

		$egif = new Egif ( );

		$transMaster = new TransMasterDfEntity ( );
		$transMaster->Load ( "f_trans_main_id = '{$transMainID}'" );

		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$transMaster->f_doc_id}'" );

		//echo $document->f_doc_no;


		$orgID = $sessionMgr->getCurrentOrgID ();
		$roleID = $sessionMgr->getCurrentRoleID ();
		$accID = $sessionMgr->getCurrentAccID ();

		$origSendDate = $sendDate;
		$origSendTime = $sendTime;

		$now = time ();
		$yearNow = date ( 'Y', $now );

		if (! $sequence->isExists ( "DFMainTransSeq_{$transMainID}" )) {
			$sequence->create ( "DFMainTransSeq_{$transMainID}" );
		}

		if (! $sequence->isExists ( "DFSendTrans_{$transMainID}" )) {
			$sequence->create ( "DFSendTrans_{$transMainID}" );
		}

		if ($sendType == 3) {
			$sendSequence = "sendRegNo_0_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
		} else {
			$sendSequence = "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
		}

		if (! $sequence->isExists ( $sendSequence )) {
			$sequence->create ( $sendSequence );
		}

		$transSeq = $sequence->get ( "DFMainTransSeq_{$transMainID}" );
		$sendTransID = $sequence->get ( "DFSendTrans_{$transMainID}" );

		$transSend = new TransDfSendEntity ( );

		$transSend->f_send_trans_main_id = $transMainID;
		$transSend->f_send_trans_main_seq = $transSeq;
		$transSend->f_send_id = $sendTransID;
		$transSend->f_send_seq = 1;
		$transSend->f_send_type = $sendType;
		$transSend->f_show_in_reg_book = $showInRegBook;

		if ($showInRegBook == 1) {
			if ($sendRegNo == 'Default') {
				$sendRegNo = $sequence->get ( $sendSequence );
				$transSend->f_send_reg_no = $sendRegNo;
			} else {
				$transSend->f_send_reg_no = $sendRegNo;
			}
			$transSend->f_reg_book_id = $regBookID;
		} else {
			//$sendRegNo = 0;
			$sendRegNo = $sequence->getLast ( $sendSequence );
			$transSend->f_send_reg_no = $sendRegNo;
			$transSend->f_reg_book_id = $regBookID;
		}

		$transSend->f_send_reg_no_full = $transSend->f_send_reg_no;

		$transSend->f_is_forward_trans = $isForwardTrans;
		if ($isForwardTrans == 1) {
			$transSend->f_recv_trans_main_id = $recvID;
			$transSend->f_recv_trans_main_seq = $recvSeq;
		} else {
			$transSend->f_recv_trans_main_id = 0;
			$transSend->f_recv_trans_main_seq = 0;
		}
		$transSend->f_received = 0;
		$transSend->f_sendback = 0;
		$transSend->f_sendback_uid = 0;
		$transSend->f_sendback_role_id = 0;
		$transSend->f_sendback_org_id = 0;
		$transSend->f_sendback_comment = '';

		$transSend->f_callback = 0;
		$transSend->f_callback_uid = 0;
		$transSend->f_callback_role_id = 0;
		$transSend->f_callback_org_id = 0;
		$transSend->f_callback_comment = '';

		$transSend->f_request_action = 1;
		$transSend->f_sender_type = 1;
		$transSend->f_sender_uid = $accID;
		$transSend->f_sender_role_id = $roleID;
		$transSend->f_sender_org_id = $orgID;

		$transSend->f_send_year = ( int ) date ( 'Y', $now );

		$updateSendfullname = false;
		if ($sendFullName == 'Default' || $sendFullName == '') {
			$updateSendfullname = true;
			$sendFullName = $util->getDefaultReceiveInternalOrgName ();
		}

		if ($sendDate == 'Default') {
			$sendDateTmp = $yearNow . date ( 'md', $now );
			$transRecv->f_recv_date = $sendDateTmp;
		} else {
			$sendDateTmp = UTFDecode ( $sendDate );
			$transRecv->f_recv_date = $util->parseDateStrToSLCDateFormat ( $sendDateTmp );
		}

		if ($sendTime == 'Default') {
			$sendTimeTmp = date ( 'Hi', $now );
			$transRecv->f_recv_time = $sendTimeTmp;
		} else {
			$sendTimeTmp = UTFDecode ( $sendTime );
			$sendTimeTmp = str_replace ( ":", '', $sendTimeTmp );
			$transRecv->f_recv_time = $sendTimeTmp;
		}

		//die($sendDate);
		if ($sendDate == 'Default') {
			$sendDate = $yearNow . date ( 'md', $now );
			$origSendDate = $sendDate;
			$transSend->f_send_date = $sendDate;
		} else {
			$sendDate = $util->parseDateStrToSLCDateFormat ( UTFDecode ( $sendDate ) );
			$origSendDate = $sendDate;
			$transSend->f_send_date = $sendDate;
		}

		//die($origSendDate);
		if ($sendTime == 'Default') {
			$sendTime = date ( 'Hi', $now );
			$origSendTime = $sendTime;
			$transSend->f_send_time = $sendTime;
		} else {
			$sendTime = UTFDecode ( $sendTime );
			$sendTime = str_replace ( ":", '', $sendTime );
			$origSendTime = $sendTime;
			$transSend->f_send_time = $sendTime;
		}

		$transSend->f_send_doc_type = $sendDocType;

		//Check for completeness
		//Fixed 27/6/2551
		if ($receiverUID != 0) {
			$receiverType = 1;
		} else {
			if ($receiverRoleID != 0) {
				$receiverType = 2;
			} else {
				$receiverType = 3;
			}
		}
		//$transSend->f_receiver_type = 1;
		$transSend->f_receiver_type = $receiverType;
		$transSend->f_receiver_role_id = $receiverRoleID;
		$transSend->f_receiver_org_id = $receiverOrgID;
		$transSend->f_receiver_uid = $receiverUID;

		$transSend->f_send_sys_stamp = $now;
		/* TODO: Send stamp is not 100% correct */
		$transSend->f_send_stamp = $now;
		//$transSend->f_send_date = $sendDate;
		//$transSend->f_send_time = $sendTime;


		/* Check From Cookie */
		$transSend->f_is_egif_trans = 0;
		$transSend->f_egif_send_stamp = 0;
		$transSend->f_egif_send_status = 0;
		$transSend->f_egif_recv_dept_id = '';
		$transSend->f_egif_recv_ministry_id = '';
		$transSend->f_egif_recv_reg_no = '';
		if ($recvFullname != '' && ! is_null ( $recvFullname )) {
			if (eregi ( '\u', $recvFullname )) {
				$recvFullname = UTFDecode ( $recvFullname );
			}
			$transSend->f_recv_fullname = UTFDecode ( $recvFullname );
		}

		if ($recvOrgName != '' && ! is_null ( $recvOrgName )) {
			if (eregi ( '\u', $recvOrgName )) {
				$recvOrgName = UTFDecode ( $recvOrgName );
			}
			$transSend->f_recv_org_name = UTFDecode ( $recvOrgName );
		}

		if ($sendFullName != '' && ! is_null ( $sendFullName )) {
			if (eregi ( '\u', $sendFullName )) {
				$sendFullName = UTFDecode ( $sendFullName );
			}
			if ($updateSendfullname) {
				$transSend->f_send_fullname = $sendFullName;
			} else {
				$transSend->f_send_fullname = UTFDecode ( $sendFullName );
			}
		}

		if ($sendOrgName != '' && ! is_null ( $sendOrgName )) {
			if (eregi ( '\u', $sendOrgName )) {
				$sendOrgName = UTFDecode ( $sendOrgName );
			}
			$transSend->f_send_org_name = UTFDecode ( $sendOrgName );
		}

		$transSend->f_is_circ = $isCirc;

		$transSend->f_egit_recv_flag = 0;

		$transSend->f_egif_recv_no = '';

		try {
			$transSend->save ();
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}

		$transID = $transSend->f_send_trans_main_id . '_' . $transSend->f_send_trans_main_seq . '_' . $transSend->f_send_id;
		$arrayResult = Array ('success' => 1, 'transID' => $transID, 'sequence' => $transSeq, 'regNo' => $sendRegNo, 'recvDate' => UTFEncode ( $util->parseSLCDate ( $origSendDate ) ), 'recvTime' => $util->parseSLCTime ( $origSendTime ) );

		/**
		 * EGIF Transaction
		 * Implementing Fake EGIF
		 *
		 */
		if ($egif->isEGIFOrganize ( UTFDecode ( $recvFullname ) )) {
			$fp = fopen ( 'c:/egifSend.txt', 'a+' );
			fwrite ( $fp, UTFDecode ( $recvFullname ) . "\r\n" );

			$url = "http://" . $egif->getEgifGatewayHost ( UTFDecode ( $recvFullname ) ) . "/ECMDev/egif-gateway/send";
			$ch = curl_init ();
			$orgMain = new OrganizeEntity();
			$orgMain->Load('f_org_id = 0');
			$request=Array(
			'f_ref_trans_id'=>$transID,
			'f_book_no'=>$document->f_doc_no,
			'f_book_date'=>$document->f_doc_date,
			'f_title'=>$document->f_title,
			'f_description'=>$document->f_description,
			'f_speed'=>$document->f_urgent_modifier,
			'f_security'=>$document->f_security_modifier,

			'f_sender_fullname'=>UTFDecode ( $sendFullName ),
			'f_sender_org_full'=>$orgMain->f_org_name,
			'f_receiver_fullname'=>'เจ้าหน้าที่',
			'f_receiver_org_full'=>UTFDecode ( $recvFullname ),
			'f_ref_url'=>$egif->getEgifGatewayHost($orgMain->f_org_name)

			/*
				public $f_attach_1_name;
				public $f_attach_1_base64;
				public $f_attach_2_name;
				public $f_attach_2_base64;
				public $f_attach_3_name;
				public $f_attach_3_base64;
				public $f_attach_4_name;
				public $f_attach_4_base64;
				public $f_attach_5_name;
				public $f_attach_5_base64;
			*/

			);
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $request );
			//curl_setopt ( $ch, CURLOPT_HEADER, (($isRequestHeader) ? 1 : 0) );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			//if (is_array ( $exHeaderInfoArr ) && ! empty ( $exHeaderInfoArr )) {
			//	curl_setopt ( $ch, CURLOPT_HTTPHEADER, $exHeaderInfoArr );
			//}
			$response = curl_exec ( $ch );
			curl_close ( $ch );
			fwrite ( $fp, $response . "\r\n" );
			fclose ( $fp );
		}
		return $arrayResult;
	}

	/**
	 * แก้ไข Property ของ Transaction การส่ง
	 *
	 * @param int $sendTransMainID
	 * @param int $sendTransMainSeq
	 * @param string $attribute
	 * @param variant $value
	 * @return boolean
	 */
	public function modifySendTransaction($sendTransMainID, $sendTransMainSeq, $attribute, $value) {
		$transSend = new TransDfSendEntity ( );
		if (! $transSend->Load ( "f_send_trans_main_id = '$sendTransMainID' and f_send_trans_main_seq = '$sendTransMainSeq'" )) {
			return false;
		} else {
			$transSend->{$attribute} = $value;
			$transSend->Save ();
			return true;
		}
	}

	/**
	 * แก้ไข Propert ของ Transaction การรับ
	 *
	 * @param int $recvTransMainID
	 * @param int $recvTransMainSeq
	 * @param string $attribute
	 * @param variant $value
	 * @return boolean
	 */
	public function modifyReceiveTransaction($recvTransMainID, $recvTransMainSeq, $attribute, $value) {
		$transRecv = new TransDfRecvEntity ( );
		if (! $transRecv->Load ( "f_recv_trans_main_id = '$recvTransMainID' and f_recv_trans_main_seq = '$recvTransMainSeq'" )) {
			return false;
		} else {
			$transRecv->{$attribute} = $value;
			$transRecv->Save ();
			return true;
		}
	}

	/**
	 * ดึงข้อมูล Transaction ส่ง
	 *
	 * @param int $sendTransMainID
	 * @param int $sendTransMainSeq
	 * @return mixed
	 */
	public function getSendTransaction($sendTransMainID, $sendTransMainSeq) {
		$transSend = new TransDfSendEntity ( );
		if (! $transSend->Load ( "f_send_trans_main_id = '$sendTransMainID' and f_send_trans_main_seq = '$sendTransMainSeq'" )) {
			//return "failed";
			return false;
		} else {
			$array = Array ();
			foreach ( $transSend as $key => $value ) {
				$array [$key] = $value;
				// echo $key."=>".$value;
			}
			return $array;
			//$transRecv->{$attribute} = $value;
		//$transRecv->Save();
		//return true;
		}
	}

	/**
	 * ดึงข้อมูล Transaction รับ
	 *
	 * @param int $recvTransMainID
	 * @param int $recvTransMainSeq
	 * @return mixed
	 */
	public function getReceiveTransaction($recvTransMainID, $recvTransMainSeq) {
		$transRecv = new TransDfRecvEntity ( );
		if (! $transRecv->Load ( "f_receive_trans_main_id = '$recvTransMainID' and f_receive_trans_main_seq = '$recvTransMainSeq'" )) {
			//return "failed";
			return false;
		} else {
			$array = Array ();
			foreach ( $transRecv as $key => $value ) {
				$array [$key] = $value;
				//echo $key."=>".$value;
			}
			return $array;
			//$transRecv->{$attribute} = $value;
		//$transRecv->Save();
		//return true;
		}
	}

	/**
	 * ขอจำนวนหนังสือรอลงรับของหน่วยงาน
	 *
	 * @param int $orgID
	 * @return int
	 */
	public function getUnreceivedOrgItemCount($orgID) {
		global $conn;
		//global $sessionMgr;


		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_receiver_org_id = {$orgID}";
		//$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		//$sqlCount .= " and a.f_send_type = 1";
		//$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_received = 0";
		$sqlCount .= " and a.f_sendback = 0";
		$sqlCount .= " and a.f_callback = 0";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนหนังสือรอลงรับ
	 *
	 * @return int
	 */
	public function getUnreceivedItemCount() {
		global $conn;
		global $sessionMgr;

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		//$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		//$sqlCount .= " and a.f_send_type = 1";
		//$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_received = 0";
		$sqlCount .= " and a.f_sendback = 0";
		$sqlCount .= " and a.f_callback = 0";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$sqlCount .= " and (b.f_abort is null OR a.f_send_year = '".$sessionMgr->getCurrentYear()."')";
		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();

		$sqlCountEgif = "select count(*) as count_exp from tbl_fake_egif where f_received = 0";
		$rsCountEgif = $conn->Execute($sqlCountEgif);
		$tmpCountEgif = $rsCountEgif->FetchNextObject();
		return $tmpCount->COUNT_EXP + $tmpCountEgif->COUNT_EXP;
	}

	/**
	 * ขอจำนวนงานที่มอบหมายไปของหน่วยงาน
	 *
	 * @param int $org
	 * @return int
	 */
	public function getOrderAssignedOrgCount($org) {
		global $conn;
		//global $sessionMgr;
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_order a";
		$sqlCount .= " where a.f_org_id = '{$org}'";
		//$sqlCount .= " and a.f_assign_uid = '{$sessionMgr->getCurrentAccID()}'";
		$sqlCount .= " and (a.f_complete = 0 or a.f_complete is null)";

		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนงานที่มอบหมายไป
	 *
	 * @return int
	 */
	public function getOrderAssignedCount() {
		global $conn;
		global $sessionMgr;
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_order a, tbl_trans_df_recv b";
		//$sqlCount .= " where a.f_org_id = '{$sessionMgr->getCurrentOrgID()}'";
		$sqlCount .= " where a.f_assign_uid = '{$sessionMgr->getCurrentAccID()}'";
		$sqlCount .= " and (a.f_complete = 0 or a.f_complete is null)";
		$sqlCount .= " and a.f_trans_main_id = b.f_recv_trans_main_id ";
		$sqlCount .= " and a.f_trans_main_seq = b.f_recv_trans_main_seq ";
		$sqlCount .= " and a.f_trans_id = b.f_recv_id ";
        $sqlCount .= " and b.f_recv_year = {$sessionMgr->getCurrentYear()} ";

		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนงานที่ได้รับมอบหมาย
	 *
	 * @return int
	 */
	public function getOrderReceivedCount() {
		global $conn;
		global $sessionMgr;
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_order a, tbl_trans_df_recv b";
		//$sqlCount .= " where a.f_org_id = '{$sessionMgr->getCurrentOrgID()}'";
		$sqlCount .= " where a.f_received_uid = '{$sessionMgr->getCurrentAccID()}'";
		$sqlCount .= " and (a.f_complete = 0 or a.f_complete is null)";
		$sqlCount .= " and a.f_trans_main_id = b.f_recv_trans_main_id";
		$sqlCount .= " and a.f_trans_main_seq = b.f_recv_trans_main_seq";
		$sqlCount .= " and a.f_trans_id = b.f_recv_id";
        $sqlCount .= " and b.f_recv_year = {$sessionMgr->getCurrentYear()} ";
		//echo "alert('$sqlCount');";
		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนหนังสือส่งกลับของหน่วยงาน
	 *
	 * @param int $org
	 * @return int
	 */
	public function getSendbackOrgItemCount($org) {
		global $conn;
		//global $sessionMgr;


		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$org}";
		//$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		//$sqlCount .= " and a.f_send_type = 1";
		//$sqlCount .= " and a.f_show_in_reg_book = 1";
		//$sqlCount .= " and a.f_received = 0";
		$sqlCount .= " and a.f_sendback = 1";
		$sqlCount .= " and a.f_sendback_ack = 0";
		$sqlCount .= " and (a.f_sendback_ack = 0 or a.f_sendback_ack is null)";
		//$sqlCount .= " and a.f_callback = 0";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนหนังสือส่งกลับ
	 *
	 * @return int
	 */
	public function getSendbackItemCount() {
		global $conn;
		global $sessionMgr;

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		//$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		//$sqlCount .= " and a.f_send_type = 1";
		//$sqlCount .= " and a.f_show_in_reg_book = 1";
		//$sqlCount .= " and a.f_received = 0";
		$sqlCount .= " and a.f_sendback = 1";
		$sqlCount .= " and a.f_sendback_ack = 0";
		//$sqlCount .= " and a.f_callback = 0";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} "; 
		//echo "alert('$sqlCount');";
		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนหนังสือรอดำเนินการของหน่วยงาน
	 *
	 * @param int $org
	 * @return int
	 */
	public function getPersonalReceivedOrgCount($org) {

		global $conn;
		//global $sessionMgr;


		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$countSQL .= " ((a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()})";
		//$countSQL .= " or (a.f_receiver_role_id = {$sessionMgr->getCurrentRoleID()})";
		//$countSQL .= " or (a.f_receiver_uid = {$sessionMgr->getCurrentAccID()} ))";
		$countSQL .= " a.f_receiver_org_id = {$org}";
		//$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and a.f_governer_approve = 0";
		//$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		//$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()}";

		$countSQL .= " and b.f_doc_id = c.f_doc_id";
		$rsCount = $conn->Execute ( $countSQL );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;

	}

	/**
	 * ขอจำนวนหนังสือรอดำเนินการ
	 *
	 * @return int
	 */
	public function getPersonalReceivedCount() {
		global $conn;
		global $sessionMgr;

		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$countSQL .= " ((a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()})";
		//$countSQL .= " or (a.f_receiver_role_id = {$sessionMgr->getCurrentRoleID()})";
		//$countSQL .= " or (a.f_receiver_uid = {$sessionMgr->getCurrentAccID()} ))";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		//$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and a.f_governer_approve = 0";
		//$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		//$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()}";

		$countSQL .= " and b.f_doc_id = c.f_doc_id";
		$rsCount = $conn->Execute ( $countSQL );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;

	}

	/**
	 * ขอจำนวนหนังสือส่งออก
	 *
	 * @param int $regBookID
	 * @return int
	 */
	public function getPersonalOutgoingCount($regBookID) {
		global $conn;
		global $sessionMgr;

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_uid = {$sessionMgr->getCurrentAccID()}";
		//$sqlCount .= " and a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		// for case outgoing we should ignore all sending type
		//$sqlCount .= " and a.f_send_type = 1";
		$sqlCount .= " and a.f_is_forward_trans = 0";
		$sqlCount .= " and a.f_sendback = 0";
		$sqlCount .= " and a.f_callback = 0";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()}";

		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอชื่อต้นเรื่องแรกเริ่มของ Transaction
	 *
	 * @param int $transMainID
	 * @return string
	 */
	public function getOriginalFrom($transMainID) {
		$dfMainTrans = new TransMasterDfEntity ( );
		if (! $dfMainTrans->Load ( "f_trans_main_id = '{$transMainID}'" )) {
			return "";
		} else {
			$sendTypes = array ('SI', 'SE', 'SG', 'SC', 'SS', 'SIG', 'SSI', 'SSE' );
			if (in_array ( $dfMainTrans->f_start_type, $sendTypes )) {
				$dfTrans = new TransDfSendEntity ( );
				$dfTrans->Load ( "f_send_trans_main_id = '{$transMainID}'  and f_send_trans_main_seq = '1' and f_send_id = '1'" );
				$from = $dfTrans->f_send_fullname;
				unset ( $dfTrans );
			} else {
				$dfTrans = new TransDfRecvEntity ( );
				$dfTrans->Load ( "f_recv_trans_main_id = '{$transMainID}' and f_recv_trans_main_seq = '1' and f_recv_id = '1'" );
				$from = $dfTrans->f_send_fullname;
				unset ( $dfTrans );
			}
			return $from;
		}
	}

	/**
	 * ขอ f_recv_doc_type แรกเริ่มของ Transaction
	 *
	 * @param int $transMainID
	 * @return string
	 */
	public function getOriginalDocType($transMainID) {
		$dfMainTrans = new TransMasterDfEntity ( );
		if (! $dfMainTrans->Load ( "f_trans_main_id = '{$transMainID}'" )) {
			return "";
		} else {
			$sendTypes = array ('SI', 'SE', 'SG', 'SC', 'SS', 'SIG', 'SSI', 'SSE' );
			if (in_array ( $dfMainTrans->f_start_type, $sendTypes )) {
				$docType = 0;
			} else {
				$dfTrans = new TransDfRecvEntity ( );
				$dfTrans->Load ( "f_recv_trans_main_id = '{$transMainID}' and f_recv_trans_main_seq = '1' and f_recv_id = '1'" );
				$docType = $dfTrans->f_recv_doc_type;
				unset ( $dfTrans );
			}
			return $docType;
		}
	}

	/**
	 * ขอจำนวนหนังสือส่งต่อ
	 *
	 * @param int $regBookID
	 * @return int
	 */
	public function getPersonalForwardedCount($regBookID) {
		global $conn;
		global $sessionMgr;

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_uid = {$sessionMgr->getCurrentAccID()}";
		//$sqlCount .= " and a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		// for case forward the case should be scope to only send type = 1
		//$sqlCount .= " and a.f_send_type = 1";
		//$sqlCount .= " and a.f_send_type = 6";
		$sqlCount .= " and a.f_sendback = 0";
		$sqlCount .= " and a.f_callback = 0";
		$sqlCount .= " and a.f_is_forward_trans = 1";
		//$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_show_in_reg_book = 0";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()}";

		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนหนังสือเวียน
	 *
	 * @return int
	 */
	public function getPersonalCircBookInternalCount() {

		global $conn;
		global $sessionMgr;

		$regBookID = 0;
		//$conn->debug = true;
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_read_circ b where ";
		$countSQL .= " a.f_trans_m = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 1";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		// for circBook we should add a filter f_is_circ = 1 & f_governer_approve = 1 too ***
		$countSQL .= " and a.f_is_circ = 1";
		$countSQL .= " and a.f_governer_approve = 1";

		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";

		$countSQL = "SELECT  count(distinct (b.f_trans_main_id))  as count_exp FROM  tbl_trans_df_recv a RIGHT JOIN tbl_read_circ b ON ";
		$countSQL .= " (b.f_trans_main_id=a.f_recv_trans_main_id and b.f_trans_main_seq=a.f_recv_trans_main_seq and b.f_trans_recv_id=a.f_recv_id)";
		$countSQL .= " WHERE";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()} and a.f_recv_type = 5 and a.f_recv_year = {$sessionMgr->getCurrentYear()} and b.f_acc_id={$sessionMgr->getCurrentAccID()} and b.f_dismiss != 1 and b.f_read_stamp = 0"; 

		$rsCount = $conn->Execute ( $countSQL );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;

	}

	/**
	 * ขอจำนวนหนังสือเวียนภายนอก
	 *
	 * @return int
	 */
	public function getPersonalCircBookExternalCount() {

		global $conn;
		global $sessionMgr;

		$regBookID = 0;

		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 2";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";

		/* $countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " ((a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()})";
		$countSQL .= " or (a.f_receiver_role_id = {$sessionMgr->getCurrentRoleID()})";
		$countSQL .= " or (a.f_receiver_uid = {$sessionMgr->getCurrentAccID()} ))";
		//$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id";*/
		$rsCount = $conn->Execute ( $countSQL );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;

	}

	/**
	 * ขอจำนวนหนังสือติดตาม
	 *
	 * @return int
	 */
	public function getTrackCount() {

		global $conn;
		global $sessionMgr;

		//$regBookID = 0;


		$countSQL = "select count(b.f_trans_main_id) as COUNT_EXP from tbl_trans_df_track a,tbl_trans_master_df b";
		$countSQL .= " where a.f_trans_main_id = b.f_trans_main_id ";
		$countSQL .= " and a.f_acc_id = {$sessionMgr->getCurrentAccID()}";

		$rsCount = $conn->Execute ( $countSQL );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;

	}

	/**
	 * สร้างรายการหนังสือเวียนสำหรับผู้ใช้ในหน่วยงาน
	 *
	 * @param int $mainTransID
	 * @param int $mainTranSeq
	 * @param int $recvID
	 */
	public function CreateOrganizeMemberReceiveRecord($mainTransID, $mainTranSeq, $recvID) {
		//global $conn;
		global $util;
		global $sessionMgr;

		//include_once 'ReadCirc.Entity.php';
		$receiverArray = $util->getOrganizeMemberAccountIDWithRoleID ( $sessionMgr->getCurrentOrgID () );
		foreach ( $receiverArray as $key => $arrValue ) {
			$readRecord = new ReadCircEntity ( );
			$readRecord->f_trans_main_id = $mainTransID;
			$readRecord->f_trans_main_seq = $mainTranSeq;
			$readRecord->f_trans_recv_id = $recvID;
			$readRecord->f_acc_id = $arrValue ['accID'];
			$readRecord->f_role_id = $arrValue ['role'];
			$readRecord->f_read_stamp = 0;
			$readRecord->f_dismiss = 0;

			$readRecord->Save ();
			unset ( $readRecord );
		}
	}

	/**
	 * ขอจำนวนงานที่เสร็จแล้วของหน่วยงาน
	 *
	 * @param int $org
	 * @return int
	 */
	public function getCompletedOrgItem($org) {
		global $conn;
		//global $sessionMgr;


		$sql = "select count(*) as COUNT_EXP from tbl_order ";
		$sql .= " where f_complete = 1";
		$sql .= " and (f_dismiss_assign_complete = 0 or f_dismiss_assign_complete is null)";
		//$sql .= " and f_assign_uid = '{$sessionMgr->getCurrentAccID()}'";
		$sql .= " and f_org_id = '{$org}'";

		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนงานที่เสร็จแล้ว
	 *
	 * @return int
	 */
	public function getCompletedItem() {
		global $conn;
		global $sessionMgr;

		$sql = "select count(*) as COUNT_EXP from tbl_order ";
		$sql .= " where f_complete = 1";
		$sql .= " and (f_dismiss_assign_complete = 0 or f_dismiss_assign_complete is null)";
		$sql .= " and f_assign_uid = '{$sessionMgr->getCurrentAccID()}'";

		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	/**
	 * ขอจำนวนงานที่ทำเสร็จ
	 *
	 * @return int
	 */
	public function getCommittedItem() {
		global $conn;
		global $sessionMgr;

		$sql = "select count(*) as COUNT_EXP from tbl_order a, tbl_trans_df_recv b,tbl_trans_master_df c,tbl_doc_main d";
		$sql .= " where f_complete = 1";
		$sql .= " and (f_dismiss_recv_complete = 0 or f_dismiss_recv_complete is null)";
		$sql .= " and f_received_uid = '{$sessionMgr->getCurrentAccID()}'";
		$sql .= " and (b.f_recv_trans_main_id = a.f_trans_main_id and b.f_recv_trans_main_seq = a.f_trans_main_seq and b.f_recv_id = a.f_trans_id)";
		$sql .= " and (a.f_trans_main_id = c.f_trans_main_id)";
		$sql .= " and (c.f_doc_id = d.f_doc_id)";                         
		$sql .= " and b.f_recv_year = {$sessionMgr->getCurrentYear()} ";

		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}
	/**
	 * แสดงเลขรับภายนอก
	 *
	 * @param int $transMainID
	 * @return varchar
	 */
	public function getReceiveExternalNumber($transMainID) {
		$dfMainTrans = new TransMasterDfEntity ( );
		if (! $dfMainTrans->Load ( "f_trans_main_id = '{$transMainID}' and (f_start_type = 'RE' or f_start_type = 'RG')"  )) {
			return "-";
		} else {
			$dfTrans = new TransDfRecvEntity ( );
			$dfTrans->Load ( "f_recv_trans_main_id = '{$transMainID}' and f_recv_trans_main_seq = '1'" );
			$from = $dfTrans->f_recv_reg_no_full;
			unset ( $dfTrans );
		}
		return $from;
	}

	/**
	 * ขอจำนนหนังสือจองเลข
	 *
	 * @return int
	 */
	public function getReserveBookItem() {
		global $conn;
		global $sessionMgr;

		$sql = "select count(*) as COUNT_EXP ";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c, tbl_reserve_no d where";
		$sql .= " (a.f_governer_approve = 0 or a.f_governer_approve is null) and";
		$sql .= " a. f_send_trans_main_seq = 1 and a.f_send_year = {$sessionMgr->getCurrentYear()} and";
		$sql .= " a.f_send_trans_main_id = b.f_trans_main_id and";
		$sql .= " c.f_gen_ext_bookno = 0 and d.f_used = 0 and";
		$sql .= " b.f_doc_id = c.f_doc_id and c.f_doc_id = d.f_doc_id";

		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}
}
