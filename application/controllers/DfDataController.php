<?php

/**
 * โปรแกรมส่งข้อมูลแบบ JSON สำหรับงานสารบรรณ
 * 
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 */

class DFDataController extends Zend_Controller_Action {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		//include_once('DFTransaction.php');
	}
	
	/**
	 * action /set-filter/ กำหนด filter ของทะเบียนต่างๆ
	 *
	 */
	public function setFilterAction() {
		global $util;
		$response = Array ( );
		if (array_key_exists ( 'filterRecvType', $_POST )) {
			$filterRecvType = trim ( UTFDecode ( $_POST ['filterRecvType'] ) );
			$filterRecvNo = trim ( UTFDecode ( $_POST ['filterRecvNo'] ) );
			$filterRecvDocNo = trim ( UTFDecode ( $_POST ['filterRecvDocNo'] ) );
			
			$filterRecvDocDateFrom = trim ( UTFDecode ( $_POST ['filterRecvDocDateFrom'] ) );
			if (strtolower ( $filterRecvDocDate ) == 'default') {
				$filterRecvDocDate = "";
			}

			$filterRecvDocDateTo = trim ( UTFDecode ( $_POST ['filterRecvDocDateTo'] ) );
			if (strtolower ( $filterRecvDocDate ) == 'default') {
				$filterRecvDocDate = "";
			}

			$filterRecvTitle = trim ( UTFDecode ( $_POST ['filterRecvTitle'] ) );
			$filterRecvDocType = trim ( UTFDecode ( $_POST ['filterRecvDocType'] ) );
			
			$filterFrom =trim ( UTFDecode ( $_POST ['filterRecvFrom'] ) );
			$filterTo =trim ( UTFDecode ( $_POST ['filterRecvTo'] ) );
			$filterFromDate = $util->dateToStamp(UTFDecode($_POST['filterRecvDateFrom']));
			$filterToDate = $util->dateToStamp(UTFDecode($_POST['filterRecvDateTo']))+86399;

			$filterRecvTo =trim ( UTFDecode ( $_POST ['filterRecvTo'] ) );
			$filterRecvForwardTo =trim ( UTFDecode ( $_POST ['filterRecvForwardTo'] ) );
			$filterReceiver =trim ( UTFDecode ( $_POST ['filterReceiver'] ) );
		
			if ($filterRecvType == 'RecvInt') {
				$_SESSION ['FilterRecord'] ['RI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['RI'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['RI'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['RI'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['RI'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['RI'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['RI'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['RI'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['RI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['RI'] ['to'] = $filterTo;
				$_SESSION ['FilterRecord'] ['RI'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['RI'] ['toDate'] = $filterToDate;				
			} elseif($filterRecvType == 'RecvExt') {
				$_SESSION ['FilterRecord'] ['RE'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['RE'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['RE'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['RE'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['RE'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['RE'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['RE'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['RE'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['RE'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['RE'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['RE'] ['toDate'] = $filterToDate;
			} elseif($filterRecvType == 'RecvSec') {
				$_SESSION ['FilterRecord'] ['RS'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['RS'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['RS'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['RS'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['RS'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['RS'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['RS'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['RS'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['RS'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['RS'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['RS'] ['toDate'] = $filterToDate;
			} elseif($filterRecvType == 'RecvSecInt') {
				$_SESSION ['FilterRecord'] ['RSI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['RSI'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['RSI'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['RSI'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['RSI'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['RSI'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['RSI'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['RSI'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['RSI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['RSI'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['RSI'] ['toDate'] = $filterToDate;
			} elseif($filterRecvType == 'RecvSecExt') {
				$_SESSION ['FilterRecord'] ['RSE'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['RSE'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['RSE'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['RSE'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['RSE'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['RSE'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['RSE'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['RSE'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['RSE'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['RSE'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['RSE'] ['toDate'] = $filterToDate;
			} elseif($filterRecvType == 'RecvCirc') {
				$_SESSION ['FilterRecord'] ['RC'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['RC'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['RC'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['RC'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['RC'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['RC'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['RC'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['RC'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['RC'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['RC'] ['to'] = $filterTo;
				$_SESSION ['FilterRecord'] ['RC'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['RC'] ['toDate'] = $filterToDate;
			} elseif($filterRecvType == 'OrderAssigned') {
				$_SESSION ['FilterRecord'] ['OAI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['OAI'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['OAI'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['OAI'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['OAI'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['OAI'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['OAI'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['OAI'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['OAI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['OAI'] ['to'] = $filterTo;
				$_SESSION ['FilterRecord'] ['OAI'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['OAI'] ['toDate'] = $filterToDate;
			} elseif($filterRecvType == 'OrderReceived') {
				$_SESSION ['FilterRecord'] ['ORI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['ORI'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['ORI'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['ORI'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['ORI'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['ORI'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['ORI'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['ORI'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['ORI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['ORI'] ['to'] = $filterTo;
				$_SESSION ['FilterRecord'] ['ORI'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['ORI'] ['toDate'] = $filterToDate;
			} elseif($filterRecvType == 'Completed') {
				$_SESSION ['FilterRecord'] ['PCM'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['PCM'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['PCM'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['PCM'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['PCM'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['PCM'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['PCM'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['PCM'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['PCM'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['PCM'] ['to'] = $filterTo;
				$_SESSION ['FilterRecord'] ['PCM'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['PCM'] ['toDate'] = $filterToDate;
			} elseif($filterRecvType == 'Committed') {
				$_SESSION ['FilterRecord'] ['PCI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['PCI'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['PCI'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['PCI'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['PCI'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['PCI'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['PCI'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['PCI'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['PCI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['PCI'] ['to'] = $filterTo;
				$_SESSION ['FilterRecord'] ['PCI'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['PCI'] ['toDate'] = $filterToDate;
			} else {
				$_SESSION ['FilterRecord'] ['RG'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['RG'] ['recvType'] = $filterRecvType;
				$_SESSION ['FilterRecord'] ['RG'] ['recvNo'] = $filterRecvNo;
				$_SESSION ['FilterRecord'] ['RG'] ['docNo'] = $filterRecvDocNo;
				$_SESSION ['FilterRecord'] ['RG'] ['docDateFrom'] = $filterRecvDocDateFrom;
				$_SESSION ['FilterRecord'] ['RG'] ['docDateTo'] = $filterRecvDocDateTo;
				$_SESSION ['FilterRecord'] ['RG'] ['title'] = $filterRecvTitle;
				$_SESSION ['FilterRecord'] ['RG'] ['docType'] = $filterRecvDocType;
				$_SESSION ['FilterRecord'] ['RG'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['RG'] ['fromDate'] = $filterFromDate;
				$_SESSION ['FilterRecord'] ['RG'] ['toDate'] = $filterToDate;
				/* New Filter */
				$_SESSION ['FilterRecord'] ['RG'] ['to'] = $filterRecvTo;
				$_SESSION ['FilterRecord'] ['RG'] ['forwardTo'] = $filterRecvForwardTo;
				$_SESSION ['FilterRecord'] ['RG'] ['receiverName'] = $filterReceiver;
			}
		}
		
		if (array_key_exists ( 'filterOrderSendType', $_POST)){
			$filterOrderSendDocNo = trim ( UTFDecode ( $_POST ['filterOrderSendDocNo'] ) );
			$filterSendOrders = trim ( UTFDecode ( $_POST ['filterSendOrders'] ) );
			$filterOrderSendDocDateFrom = trim ( UTFDecode ( $_POST ['filterOrderSendDocDateFrom'] ) );
			$filterOrderSendDocDateTo = trim ( UTFDecode ( $_POST ['filterOrderSendDocDateTo'] ) );
			$filterOrderSendTitle = trim ( UTFDecode ( $_POST ['filterOrderSendTitle'] ) );
			$filterOrderSendOrg = trim ( UTFDecode ( $_POST ['filterOrderSendOrg'] ) );
			$filterOrderSendType = trim ( UTFDecode ( $_POST ['filterOrderSendType'] ) );
			if($filterOrderSendType == 'Orders') {
				$docno = explode('/',$filterOrderSendDocNo);
				$_SESSION ['FilterRecord'] ['ORD'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['ORD'] ['sendType'] = $filterOrderSendType;
				$_SESSION ['FilterRecord'] ['ORD'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['ORD'] ['docNo'] = $docno[0];
				$_SESSION ['FilterRecord'] ['ORD'] ['docYear'] = $docno[1];
				$_SESSION ['FilterRecord'] ['ORD'] ['name'] = $filterSendOrders;
				$_SESSION ['FilterRecord'] ['ORD'] ['docDateFrom'] = $filterOrderSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['ORD'] ['docDateTo'] = $filterOrderSendDocDateTo;
				$_SESSION ['FilterRecord'] ['ORD'] ['title'] = $filterOrderSendTitle;
				$_SESSION ['FilterRecord'] ['ORD'] ['org'] = $filterOrderSendOrg;
			}

		}

		if (array_key_exists ( 'filterSendType', $_POST )) {
			$filterSendType = trim ( UTFDecode ( $_POST ['filterSendType'] ) );
			$filterSendNo = trim ( UTFDecode ( $_POST ['filterSendNo'] ) );
			$filterSendDocNo = trim ( UTFDecode ( $_POST ['filterSendDocNo'] ) );
			
			$filterSendDocDateFrom = trim ( UTFDecode ( $_POST ['filterSendDocDateFrom'] ) );
			if (strtolower ( $filterSendDocDateFrom ) == 'default') {
				$filterSendDocDateFrom = "";
			}
			$filterSendDocDateTo = trim ( UTFDecode ( $_POST ['filterSendDocDateTo'] ) );
			if (strtolower ( $filterSendDocDateTo ) == 'default') {
				$filterSendDocDateTo = "";
			}
			$filterSendTitle = trim ( UTFDecode ( $_POST ['filterSendTitle'] ) );
			$filterFrom =trim ( UTFDecode ( $_POST ['filterSendFrom'] ) );		
			$filterTo =trim ( UTFDecode ( $_POST ['filterSendTo'] ) );
			
			if ($filterSendType == 'SendInt') {
				$_SESSION ['FilterRecord'] ['SI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SI'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SI'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SI'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SI'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SI'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SI'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SI'] ['to'] = $filterTo;
			} elseif($filterSendType == 'SendExt') {
				$_SESSION ['FilterRecord'] ['SE'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SE'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SE'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SE'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SE'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SE'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SE'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SE'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SE'] ['to'] = $filterTo;
			} elseif($filterSendType == 'SendSec') {
				$_SESSION ['FilterRecord'] ['SS'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SS'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SS'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SS'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SS'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SS'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SS'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SS'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SS'] ['to'] = $filterTo;
			} elseif($filterSendType == 'SendSecInt') {
				$_SESSION ['FilterRecord'] ['SSI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SSI'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SSI'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SSI'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SSI'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SSI'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SSI'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SSI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SSI'] ['to'] = $filterTo;
			} elseif($filterSendType == 'SendSecExt') {
				$_SESSION ['FilterRecord'] ['SSE'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SSE'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SSE'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SSE'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SSE'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SSE'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SSE'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SSE'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SSE'] ['to'] = $filterTo;
			} elseif($filterSendType == 'SendCirc') {
				$_SESSION ['FilterRecord'] ['SC'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SC'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SC'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SC'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SC'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SC'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SC'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SC'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SC'] ['to'] = $filterTo;
			} elseif($filterSendType == 'SendGlobalInt') {
				$_SESSION ['FilterRecord'] ['SGI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SGI'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SGI'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SGI'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SGI'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SGI'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SGI'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SGI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SGI'] ['to'] = $filterTo;
			} elseif($filterSendType == 'Forward') {
				$_SESSION ['FilterRecord'] ['FW'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['FW'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['FW'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['FW'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['FW'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['FW'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['FW'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['FW'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['FW'] ['to'] = $filterTo;
			} elseif($filterSendType == 'OutGoing') {
				$_SESSION ['FilterRecord'] ['OGI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['OGI'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['OGI'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['OGI'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['OGI'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['OGI'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['OGI'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['OGI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['OGI'] ['to'] = $filterTo;
			} elseif($filterSendType == 'CallBack') {
				$_SESSION ['FilterRecord'] ['CBI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['CBI'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['CBI'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['CBI'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['CBI'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['CBI'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['CBI'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['CBI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['CBI'] ['to'] = $filterTo;
			} elseif($filterSendType == 'SendBack') {
				$_SESSION ['FilterRecord'] ['SBI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SBI'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SBI'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SBI'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SBI'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SBI'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SBI'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SBI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SBI'] ['to'] = $filterTo;
			} elseif($filterSendType == 'Track') {
				$_SESSION ['FilterRecord'] ['TKI'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['TKI'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['TKI'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['TKI'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['TKI'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['TKI'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['TKI'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['TKI'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['TKI'] ['to'] = $filterTo;
			}else {
				$_SESSION ['FilterRecord'] ['SG'] ['filtered'] = true;
				$_SESSION ['FilterRecord'] ['SG'] ['sendType'] = $filterSendType;
				$_SESSION ['FilterRecord'] ['SG'] ['sendNo'] = $filterSendNo;
				$_SESSION ['FilterRecord'] ['SG'] ['docNo'] = $filterSendDocNo;
				$_SESSION ['FilterRecord'] ['SG'] ['docDateFrom'] = $filterSendDocDateFrom;
				$_SESSION ['FilterRecord'] ['SG'] ['docDateTo'] = $filterSendDocDateTo;
				$_SESSION ['FilterRecord'] ['SG'] ['title'] = $filterSendTitle;
				$_SESSION ['FilterRecord'] ['SG'] ['from'] = $filterFrom;
				$_SESSION ['FilterRecord'] ['SG'] ['to'] = $filterTo;
			}
		}
		
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	/**
	 * action /clear-filter/ ทำการเคลียร์ Filter
	 *
	 */
	public function clearFilterAction() {
		$response = Array ( );
		if (array_key_exists ( 'filterRecvType', $_POST )) {
			$filterRecvType = $_POST ['filterRecvType'];
			
			if ($filterRecvType == 'RecvInt') {
				$_SESSION ['FilterRecord'] ['RI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['RI'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['to'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['RI'] ['toDate'] = "";
			} elseif($filterRecvType == 'RecvExt') {
				$_SESSION ['FilterRecord'] ['RE'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['RE'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['RE'] ['toDate'] = "";
			}elseif($filterRecvType == 'RecvSec') {
				$_SESSION ['FilterRecord'] ['RS'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['RS'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['RS'] ['toDate'] = "";
			}elseif($filterRecvType == 'RecvSecInt') {
				$_SESSION ['FilterRecord'] ['RSI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['RSI'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['RSI'] ['toDate'] = "";
			}elseif($filterRecvType == 'RecvSecExt') {
				$_SESSION ['FilterRecord'] ['RSE'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['RSE'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['RSE'] ['toDate'] = "";
			}elseif($filterRecvType == 'RecvCirc') {
				$_SESSION ['FilterRecord'] ['RC'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['RC'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['to'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['RC'] ['toDate'] = "";
			} elseif($filterRecvType == 'OrderAssigned') {
				$_SESSION ['FilterRecord'] ['OAI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['OAI'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['to'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['OAI'] ['toDate'] = "";
			} elseif($filterRecvType == 'OrderReceived') {
				$_SESSION ['FilterRecord'] ['ORI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['ORI'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['to'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['ORI'] ['toDate'] = "";
			}elseif($filterRecvType == 'Completed') {
				$_SESSION ['FilterRecord'] ['PCM'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['PCM'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['to'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['PCM'] ['toDate'] = "";
			} elseif($filterRecvType == 'Committed') {
				$_SESSION ['FilterRecord'] ['PCI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['PCI'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['to'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['PCI'] ['toDate'] = "";
			}else {
				$_SESSION ['FilterRecord'] ['RG'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['RG'] ['recvType'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['recvNo'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['docType'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['fromDate'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['toDate'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['to'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['forwardTo'] = "";
				$_SESSION ['FilterRecord'] ['RG'] ['receiverName'] = "";
			}
		}

		if (array_key_exists ( 'filterOrderSendType', $_POST)){
			$filterOrderSendType = trim ( UTFDecode ( $_POST ['filterOrderSendType'] ) );
			if($filterOrderSendType == 'Orders') {
				$_SESSION ['FilterRecord'] ['ORD'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['ORD'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['ORD'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['ORD'] ['docYear'] = "";
				$_SESSION ['FilterRecord'] ['ORD'] ['name'] = "";
				$_SESSION ['FilterRecord'] ['ORD'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['ORD'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['ORD'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['ORD'] ['org'] = "";
			}
		}
		
		if (array_key_exists ( 'filterSendType', $_POST )) {
			$filterSendType = $_POST ['filterSendType'];
			
			if ($filterSendType == 'SendInt') {
				$_SESSION ['FilterRecord'] ['SI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SI'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SI'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SI'] ['to'] = "";
			} elseif($filterSendType == 'SendExt') {
				$_SESSION ['FilterRecord'] ['SE'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SE'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SE'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SE'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SE'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SE'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SE'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SE'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SE'] ['to'] = "";
			} elseif($filterSendType == 'SendSec') {
				$_SESSION ['FilterRecord'] ['SS'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SS'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SS'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SS'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SS'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SS'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SS'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SS'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SS'] ['to'] = "";
			} elseif($filterSendType == 'SendSecInt') {
				$_SESSION ['FilterRecord'] ['SSI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SSI'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SSI'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SSI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SSI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SSI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SSI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SSI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SSI'] ['to'] = "";
			} elseif($filterSendType == 'SendSecExt') {
				$_SESSION ['FilterRecord'] ['SSE'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SSE'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SSE'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SSE'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SSE'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SSE'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SSE'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SSE'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SSE'] ['to'] = "";
			} elseif($filterSendType == 'SendCirc') {
				$_SESSION ['FilterRecord'] ['SC'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SC'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SC'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SC'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SC'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SC'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SC'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SC'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SC'] ['to'] = "";
			} elseif($filterSendType == 'SendGlobalInt') {
				$_SESSION ['FilterRecord'] ['SGI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SGI'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SGI'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SGI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SGI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SGI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SGI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SGI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SGI'] ['to'] = "";
			}elseif($filterSendType == 'Forward') {
				$_SESSION ['FilterRecord'] ['FW'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['FW'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['FW'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['FW'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['FW'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['FW'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['FW'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['FW'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['FW'] ['to'] = "";
			}elseif($filterSendType == 'CallBack') {
				$_SESSION ['FilterRecord'] ['CBI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['CBI'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['CBI'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['CBI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['CBI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['CBI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['CBI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['CBI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['CBI'] ['to'] = "";
			}elseif($filterSendType == 'SendBack') {
				$_SESSION ['FilterRecord'] ['SBI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SBI'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SBI'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SBI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SBI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SBI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SBI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SBI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SBI'] ['to'] = "";
			}elseif($filterSendType == 'OutGoing') {
				$_SESSION ['FilterRecord'] ['OGI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['OGI'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['OGI'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['OGI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['OGI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['OGI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['OGI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['OGI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['OGI'] ['to'] = "";
			}elseif($filterSendType == 'Track') {
				$_SESSION ['FilterRecord'] ['TKI'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['TKI'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['TKI'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['TKI'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['TKI'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['TKI'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['TKI'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['TKI'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['TKI'] ['to'] = "";
			} else {
				$_SESSION ['FilterRecord'] ['SG'] ['filtered'] = false;
				$_SESSION ['FilterRecord'] ['SG'] ['sendType'] = "";
				$_SESSION ['FilterRecord'] ['SG'] ['sendNo'] = "";
				$_SESSION ['FilterRecord'] ['SG'] ['docNo'] = "";
				$_SESSION ['FilterRecord'] ['SG'] ['docDateFrom'] = "";
				$_SESSION ['FilterRecord'] ['SG'] ['docDateTo'] = "";
				$_SESSION ['FilterRecord'] ['SG'] ['title'] = "";
				$_SESSION ['FilterRecord'] ['SG'] ['from'] = "";
				$_SESSION ['FilterRecord'] ['SG'] ['to'] = "";
			}
		}
		
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	/**
	 * action /received-internal/
	 *
	 */
	public function receivedInternalAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		//include_once 'Account.Entity.php';
		//require_once 'Command.Entity.php';
		
		$dfTrans = new DFTransaction();
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'RI' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RI'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RI'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RI'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_recv_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_recv_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['RI'] ['recvNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['RI'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['RI'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['RI']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['RI'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['RI']['title']}%'";
			}
			
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['RI'] ['docType'] != '') {
				$filterSQL [] = " a.f_recv_doc_type = '{$_SESSION['FilterRecord']['RI']['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['RI'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['RI']['from']}%'";
			}

		// Filter To
			if ($_SESSION ['FilterRecord'] ['RI'] ['to'] != '') {
				$filterSQL [] = " a.f_attend_fullname like '%{$_SESSION['FilterRecord']['RI']['to']}%'";
			}	

			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['RI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['RI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['RI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['RI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['RI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
			
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		$regBookID = $_GET ['regBookID'];
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 1";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
        $countSQL .= " and b.f_doc_id = c.f_doc_id ";
		$countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";
		if ($util->isDFTransFiltered ( 'RI' )) {
			$countSQL .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 1";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";
		
		if ($util->isDFTransFiltered ( 'RI' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";
		
		if ($util->isDFTransFiltered ( 'RI' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//echo $sql;
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
		
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			
			$receiveExternalReg = $dfTrans->getReceiveExternalNumber( $tmp ['f_recv_trans_main_id'] );
			
			$receiverEnt = new AccountEntity();
			$receiverEnt->Load("f_acc_id = '{$tmp['f_accept_uid']}'");
			$receivedInternal [] = Array ('recvID' =>  trim ( $tmp ['f_recv_trans_main_id']) . '_' .  trim ( $tmp ['f_recv_trans_main_seq']) . '_' .  trim ( $tmp ['f_recv_id']), 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'receiveExternalRunning' => $receiveExternalReg, 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => UTFEncode($dfTrans->getOriginalFrom($tmp ['f_recv_trans_main_id'])), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ),'receivedUser'=>UTFEncode($receiverEnt->f_name.' '.$receiverEnt->f_last_name),'abort'=>$tmp['f_abort']);
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /received-circ/ ข้อมูลทะเบียนรับเวียน
	 *
	 */
	public function receivedCircAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;

		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		//$conn->debug = true;
		$dfTrans = new DFTransaction();
		
		//include_once 'Account.Entity.php';
		//require_once 'Command.Entity.php';
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'RC' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RC'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RC'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RC'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_recv_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_recv_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['RC'] ['recvNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['RC'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RC']['recvNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['RC'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['RC']['docNo']}%'";
			}
			$util = new ECMUtility();
			$datefrom = $util->dateToStamp($_SESSION ['FilterRecord'] ['RC'] ['docDateFrom']);
			$dateto = $util->dateToStamp($_SESSION ['FilterRecord'] ['RC'] ['docDateTo']);
			
			// Filter Receive Doc.Date From
			if ($datefrom != '') {
				$filterSQL [] = " c.f_doc_stamp >= {$datefrom} ";
			}

			// Filter Receive Doc.Date To	
			if ($dateto != ''){
				$filterSQL [] = " c.f_doc_stamp <=  {$dateto} ";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['RC'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['RC']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['RC'] ['docType'] != '') {
				$filterSQL [] = " a.f_recv_doc_type = '{$_SESSION['FilterRecord']['RC']['docType']}'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['RC'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['RC']['from']}%'";
			}

			//Filter Date Range
			if ($_SESSION ['FilterRecord'] ['RC'] ['fromDate'] != '') {
				if($_SESSION['FilterRecord']['RC']['fromDate'] == $_SESSION['FilterRecord']['RC']['toDate']) {
					$tmpToDate = $_SESSION['FilterRecord']['RC']['toDate'] + 86400;
					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RC']['fromDate']}' and a.f_recv_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RC']['fromDate']}' and a.f_recv_stamp <= '{$_SESSION['FilterRecord']['RC']['toDate']}'  )";
				}
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		//$fp = fopen ( 'd:\SQLFilter.txt', 'a+' );
		//fwrite ( $fp, $realFilter );
		//fclose ( $fp );
		

		$regBookID = $_GET ['regBookID'];
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 5";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
        $countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";  
		if ($util->isDFTransFiltered ( 'RC' )) {
			$countSQL .= $realFilter;
		}		
		$countSQL .= " order by a.f_recv_reg_no desc";


		$rsCount = $conn->Execute ( $countSQL );
		
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 5";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} "; 
		
		if ($util->isDFTransFiltered ( 'RC' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";
		
		if ($util->isDFTransFiltered ( 'RC' )) {
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//echo $sql;
		
		//Logger::debug ($sql);
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			
			$receiverEnt = new AccountEntity();
			$receiverEnt->Load("f_acc_id = '{$tmp['f_accept_uid']}'");
			
			$receiveExternalReg = $dfTrans->getReceiveExternalNumber( $tmp ['f_recv_trans_main_id'] );
			
			//var_dump($tmp);
			//$receivedInternal [] = Array ('recvID' => trim ( $tmp ['f_recv_trans_main_id'] ) . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . trim ( $tmp ['f_recv_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hold' => $tmp ['f_hold_job'], 'hasAttach' => $hasAttach, 'governerApprove' => $tmp ['f_governer_approve'], 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ) );
			//$receivedInternal [] = Array ('recvID' =>  trim ( $tmp ['f_recv_trans_main_id']) . '_' .  trim ( $tmp ['f_recv_trans_main_seq']) . '_' .  trim ( $tmp ['f_recv_id']), 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ),'receivedUser'=>UTFEncode($receiverEnt->f_name.' '.$receiverEnt->f_last_name),'abort'=>$tmp['f_abort']);
			//แก้ไขเรื่อง Notice ของ Line 523
			$receivedInternal [] = Array ('recvID' =>  trim ( $tmp ['f_recv_trans_main_id']) . '_' .  trim ( $tmp ['f_recv_trans_main_seq']) . '_' .  trim ( $tmp ['f_recv_id']), 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'receiveExternalRunning' => $receiveExternalReg, 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => UTFEncode($dfTrans->getOriginalFrom($tmp ['f_recv_trans_main_id'])),'from2' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), /*'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'],*/ 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ),'receivedUser'=>UTFEncode($receiverEnt->f_name.' '.$receiverEnt->f_last_name),'abort'=>$tmp['f_abort']);
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		//var_dump($receivedInternal);
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /received-classified/ ข้อมูลทะเบียนรับ(ลับ)
	 *
	 */
	public function receivedClassifiedAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$dfTrans = new DFTransaction();
		
		//include_once 'Account.Entity.php';
		//require_once 'Command.Entity.php';
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'RS' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RS'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RS'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RS'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_recv_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_recv_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['RS'] ['recvNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['RS'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RS']['recvNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['RS'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['RS']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['RS'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['RS']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['RS'] ['docType'] != '') {
				$filterSQL [] = " a.f_recv_doc_type = '{$_SESSION['FilterRecord']['RS']['docType']}'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['RS'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['RS']['from']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['RS'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RS'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RS'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['RS'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RS'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['RS'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['RS'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['RS'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RS'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		//$fp = fopen ( 'd:\SQLFilter.txt', 'a+' );
		//fwrite ( $fp, $realFilter );
		//fclose ( $fp );
		

		$regBookID = $_GET ['regBookID'];
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 4";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
        $countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";    
		if ($util->isDFTransFiltered ( 'RS' )) {
			$countSQL .= $realFilter;
		}
		
		$countSQL .= " order by a.f_recv_reg_no desc";

		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 4";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";    
		
		//echo $sql;
		

		if ($util->isDFTransFiltered ( 'RS' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";
		
		if ($util->isDFTransFiltered ( 'RS' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//echo $sql;
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			
			$receiverEnt = new AccountEntity();
			$receiverEnt->Load("f_acc_id = '{$tmp['f_accept_uid']}'");
			
			$receiveExternalReg = $dfTrans->getReceiveExternalNumber( $tmp ['f_recv_trans_main_id'] );
			
			$receivedInternal [] = Array ('recvID' =>  trim ( $tmp ['f_recv_trans_main_id']) . '_' .  trim ( $tmp ['f_recv_trans_main_seq']) . '_' .  trim ( $tmp ['f_recv_id']), 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'receiveExternalRunning' => $receiveExternalReg, 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ),'from' => UTFEncode($dfTrans->getOriginalFrom($tmp ['f_recv_trans_main_id'])), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ),'receivedUser'=>UTFEncode($receiverEnt->f_name.' '.$receiverEnt->f_last_name),'abort'=>$tmp['f_abort']);
			//$receivedInternal [] = Array ('recvID' => trim ( $tmp ['f_recv_trans_main_id'] ) . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . trim ( $tmp ['f_recv_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hold' => $tmp ['f_hold_job'], 'hasAttach' => $hasAttach, 'governerApprove' => $tmp ['f_governer_approve'] );
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		//var_dump($receivedInternal);
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /received-classified-int/ ข้อมูลทะเบียนรับภายใน(ลับ)
	 *
	 */
	public function receivedClassifiedIntAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$dfTrans = new DFTransaction();
		
		//include_once 'Account.Entity.php';
		//require_once 'Command.Entity.php';
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'RSI' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RSI'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RSI'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RSI'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_recv_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_recv_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['RSI'] ['recvNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['RSI'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RSI']['recvNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['RSI'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['RSI']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['RSI'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['RSI']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['RSI'] ['docType'] != '') {
				$filterSQL [] = " a.f_recv_doc_type = '{$_SESSION['FilterRecord']['RSI']['docType']}'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['RSI'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['RSI']['from']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['RSI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RSI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RSI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['RSI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RSI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['RSI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['RSI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['RSI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RSI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		//$fp = fopen ( 'd:\SQLFilter.txt', 'a+' );
		//fwrite ( $fp, $realFilter );
		//fclose ( $fp );
		

		$regBookID = $_GET ['regBookID'];
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type =6";
		$countSQL .= " and a.f_recv_doc_type = 1";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
        $countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";    
		if ($util->isDFTransFiltered ( 'RSI' )) {
			$countSQL .= $realFilter;
		}
		
		$countSQL .= " order by a.f_recv_reg_no desc";

		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 6";
		$sql .= " and a.f_recv_doc_type = 1";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";    
		
		if ($util->isDFTransFiltered ( 'RSI' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";

		if ($util->isDFTransFiltered ( 'RSI' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//echo $sql;
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			
			$receiverEnt = new AccountEntity();
			$receiverEnt->Load("f_acc_id = '{$tmp['f_accept_uid']}'");
			
			$receiveExternalReg = $dfTrans->getReceiveExternalNumber( $tmp ['f_recv_trans_main_id'] );
			
			$receivedInternal [] = Array ('recvID' =>  trim ( $tmp ['f_recv_trans_main_id']) . '_' .  trim ( $tmp ['f_recv_trans_main_seq']) . '_' .  trim ( $tmp ['f_recv_id']), 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'receiveExternalRunning' => $receiveExternalReg, 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ),'from' => UTFEncode($dfTrans->getOriginalFrom($tmp ['f_recv_trans_main_id'])), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ),'receivedUser'=>UTFEncode($receiverEnt->f_name.' '.$receiverEnt->f_last_name),'abort'=>$tmp['f_abort']);
			//$receivedInternal [] = Array ('recvID' => trim ( $tmp ['f_recv_trans_main_id'] ) . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . trim ( $tmp ['f_recv_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hold' => $tmp ['f_hold_job'], 'hasAttach' => $hasAttach, 'governerApprove' => $tmp ['f_governer_approve'] );
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		//var_dump($receivedInternal);
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}

	/**
	 * action /received-classified-ext/ ข้อมูลทะเบียนรับภายนอก(ลับ)
	 *
	 */
	public function receivedClassifiedExtAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$dfTrans = new DFTransaction();
		
		//include_once 'Account.Entity.php';
		//require_once 'Command.Entity.php';
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'RSE' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RSE'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RSE'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RSE'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_recv_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_recv_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['RSE'] ['recvNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['RSE'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RSE']['recvNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['RSE'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['RSE']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['RSE'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['RSE']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['RSE'] ['docType'] != '') {
				$filterSQL [] = " a.f_recv_doc_type = '{$_SESSION['FilterRecord']['RSE']['docType']}'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['RSE'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['RSE']['from']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['RSE'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RSE'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RSE'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['RSE'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RSE'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['RSE'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['RSE'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['RSE'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RSE'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		//$fp = fopen ( 'd:\SQLFilter.txt', 'a+' );
		//fwrite ( $fp, $realFilter );
		//fclose ( $fp );
		

		$regBookID = $_GET ['regBookID'];
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 7";
		$countSQL .= " and a.f_recv_doc_type = 2";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
        $countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";    
		if ($util->isDFTransFiltered ( 'RSE' )) {
			$countSQL .= $realFilter;
		}
		
		$countSQL .= " order by a.f_recv_reg_no desc";

		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 7";
		//$sql .= " and a.f_recv_doc_type = 2";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";    
		
		if ($util->isDFTransFiltered ( 'RSE' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";

		if ($util->isDFTransFiltered ( 'RSE' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//echo $sql;
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			
			$receiverEnt = new AccountEntity();
			$receiverEnt->Load("f_acc_id = '{$tmp['f_accept_uid']}'");
			
			$receiveExternalReg = $dfTrans->getReceiveExternalNumber( $tmp ['f_recv_trans_main_id'] );
			
			$receivedInternal [] = Array ('recvID' =>  trim ( $tmp ['f_recv_trans_main_id']) . '_' .  trim ( $tmp ['f_recv_trans_main_seq']) . '_' .  trim ( $tmp ['f_recv_id']), 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'receiveExternalRunning' => $receiveExternalReg, 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ),'from' => UTFEncode($dfTrans->getOriginalFrom($tmp ['f_recv_trans_main_id'])), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ),'receivedUser'=>UTFEncode($receiverEnt->f_name.' '.$receiverEnt->f_last_name),'abort'=>$tmp['f_abort']);
			//$receivedInternal [] = Array ('recvID' => trim ( $tmp ['f_recv_trans_main_id'] ) . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . trim ( $tmp ['f_recv_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hold' => $tmp ['f_hold_job'], 'hasAttach' => $hasAttach, 'governerApprove' => $tmp ['f_governer_approve'] );
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		//var_dump($receivedInternal);
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}

	/**
	 * action /received-external/ ข้อมูลทะเบียนรับภายนอก
	 *
	 */
	public function receivedExternalAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		//include_once 'Account.Entity.php';
		//require_once 'Command.Entity.php';
		
		$dfTrans = new DFTransaction();
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'RE' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RE'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RE'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RE'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_recv_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_recv_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['RE'] ['recvNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['RE'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RE']['recvNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['RE'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['RE']['docNo']}%'";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['RE'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['RE']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['RE'] ['docType'] != '') {
				$filterSQL [] = " a.f_recv_doc_type = '{$_SESSION['FilterRecord']['RE']['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['RE'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['RE']['from']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['RE'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RE'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RE'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['RE'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RE'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['RE'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['RE'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['RE'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RE'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		$regBookID = $_GET ['regBookID'];
		
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 2";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
        $countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";   
        
		if ($util->isDFTransFiltered ( 'RE' )) {
			$countSQL .= $realFilter;
		}

		$countSQL .= " order by a.f_recv_reg_no desc";

		
		//echo $countSQL;
		$rsCount = $conn->Execute ( $countSQL );
		
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 2";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id";
        $sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} ";   
        
		if ($util->isDFTransFiltered ( 'RE' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";
		
		//$rsCount = $conn->Execute ( $countSQL );
		

		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		

		if ($util->isDFTransFiltered ( 'RE' )) {
			//$rs = $conn->Execute ( $sql );
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		
		$receivedExternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
		
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			
			$receiverEnt = new AccountEntity();
			$receiverEnt->Load("f_acc_id = '{$tmp['f_accept_uid']}'");
			//var_dump($tmp);
			
			//$receivedInternal [] = Array ('recvID' => trim ( $tmp ['f_recv_trans_main_id'] ) . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . trim ( $tmp ['f_recv_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hold' => $tmp ['f_hold_job'], 'hasAttach' => $hasAttach, 'governerApprove' => $tmp ['f_governer_approve'], 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ) );
			//$receivedExternal [] = Array ('recvID' => trim ( $tmp ['f_recv_trans_main_id'] ) . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . trim ( $tmp ['f_recv_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ) );
			$receivedExternal [] = Array ('recvID' =>  trim ( $tmp ['f_recv_trans_main_id']) . '_' .  trim ( $tmp ['f_recv_trans_main_seq']) . '_' .  trim ( $tmp ['f_recv_id']), 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ),'from' => UTFEncode($dfTrans->getOriginalFrom($tmp ['f_recv_trans_main_id'])), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ),'receivedUser'=>UTFEncode($receiverEnt->f_name.' '.$receiverEnt->f_last_name),'abort'=>$tmp['f_abort']);
			
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedExternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /received-external-global/ ข้อมูลทะเบียนรับภายนอกทะเบียนกลาง
	 *
	 */
	public function receivedExternalGlobalAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		//include_once 'Account.Entity.php';
		//require_once 'Command.Entity.php';
        
        if (array_key_exists ( 'filter', $_GET )) {
            
            //Filter #1
            $filtervalue1 = UTFDecode ( $_GET ['filter'] [0] ['data'] ['value'] );
           
            if(trim($filtervalue1) != '') {
                if($_GET ['filter'] [0] ['field'] == 'from') {
                    $filterField1 = "a.f_send_fullname";
                } 
                $filterQuery = " and {$filterField1} like '%{$filtervalue1}%'";
            } else {
                $filterQuery = "";
            }
                    
            
        } else {
            $filterQuery = "";
        }
        
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'RG' )) {
			//Logger::debug('filter data');
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RG'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RG'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RG'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_recv_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_recv_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['RG'] ['recvNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['RG'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $startR and a.f_recv_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RG']['recvNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['RG'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['RG']['docNo']}%'";
			}
			
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['RG'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RG'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RG'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}
			if (($_SESSION ['FilterRecord'] ['RG'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RG'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['RG'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['RG'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['RG'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RG'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['RG'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['RG']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['RG'] ['docType'] != '') {
				$filterSQL [] = " a.f_recv_doc_type = '{$_SESSION['FilterRecord']['RG']['docType']}'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['RG'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['RG']['from']}%'";
			}
			
			//Filter Date Range
//			if ($_SESSION ['FilterRecord'] ['RG'] ['fromDate'] != '') {
//				if($_SESSION['FilterRecord']['RG']['fromDate'] == $_SESSION['FilterRecord']['RG']['toDate']) {
//					$tmpToDate = $_SESSION['FilterRecord']['RG']['toDate'] + 86400;
//					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RG']['fromDate']}' and a.f_recv_stamp <= '{$tmpToDate}'  )";
//				} else {
//					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RG']['fromDate']}' and a.f_recv_stamp <= '{$_SESSION['FilterRecord']['RG']['toDate']}'  )";
//				}
//			}


			if($_SESSION ['FilterRecord'] ['RG'] ['to'] !='') {
				//Logger::debug("Filter Recv Name".$_SESSION ['FilterRecord'] ['RG'] ['receiverName']);
				$filterSQL [] = " (a.f_attend_fullname like '%{$_SESSION['FilterRecord']['RG']['to']}%'  )";				
			}

			if($_SESSION ['FilterRecord'] ['RG'] ['forwardTo'] !='') {
				//Logger::debug("Filter Recv Name".$_SESSION ['FilterRecord'] ['RG'] ['receiverName']);
				$filterSQL [] = " (a.f_attend_fullname like '%{$_SESSION['FilterRecord']['RG']['to']}%'  )";				
			}

			if($_SESSION ['FilterRecord'] ['RG'] ['receiverName'] !='') {
				//Logger::debug("Filter Recv Name".$_SESSION ['FilterRecord'] ['RG'] ['receiverName']);
				$filterSQL [] = " (d.f_name like '%{$_SESSION['FilterRecord']['RG']['receiverName']}%' or d.f_last_name like '%{$_SESSION['FilterRecord']['RG']['receiverName']}%'  )";				
			}
			/*
			$_SESSION ['FilterRecord'] ['RG'] ['to'] = $filterRecvTo;
			$_SESSION ['FilterRecord'] ['RG'] ['forwardTo'] = $filterRecvForwardTo;
			$_SESSION ['FilterRecord'] ['RG'] ['receiverName'] = $filterReceiver;
			*/
			
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		$regBookID = $_GET ['regBookID'];
		
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c,tbl_account d  where ";
		//$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 3";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
		$countSQL .= " and a.f_receiver_uid = d.f_acc_id";
        $countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} {$filterQuery} ";    
/*        
		$fp = fopen( "d:/logQuery.log","a+");
		fwrite( $fp , "receivedExternalGlobalAction \r\n" );
		fwrite( $fp , $countSQL."\r\n" );
		fwrite( $fp , " \r\n" );
		fclose( $fp );
*/
		if ($util->isDFTransFiltered ( 'RG' )) {
			$countSQL .= $realFilter;
		}

		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " ,d.f_name,d.f_last_name ";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c,tbl_account d where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 3";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id";
		$sql .= " and a.f_receiver_uid = d.f_acc_id";
        $sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} {$filterQuery}";    
		//Logger::debug($countSQL);
		//Logger::debug($sql);
        
		if ($util->isDFTransFiltered ( 'RG' )) {
			$sql .= $realFilter;
		}

		$sql .= " order by a.f_recv_reg_no desc";
		
		//$rsCount = $conn->Execute ( $countSQL );
		

		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		

		if ($util->isDFTransFiltered ( 'RG' )) {
			//$rs = $conn->Execute ( $sql );
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		
		//Logger::debug($sql);
		$receivedExternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
		
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			
			$receiverEnt = new AccountEntity();
			$receiverEnt->Load("f_acc_id = '{$tmp['f_accept_uid']}'");
			
			$receivedExternal [] = Array ('recvID' =>  trim ( $tmp ['f_recv_trans_main_id']) . '_' .  trim ( $tmp ['f_recv_trans_main_seq']) . '_' .  trim ( $tmp ['f_recv_id']), 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'receivedStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_recv_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_recv_stamp'] ) ),'receivedUser'=>UTFEncode($receiverEnt->f_name.' '.$receiverEnt->f_last_name),'abort'=>$tmp['f_abort']);
			//$receivedExternal [] = Array ('recvID' => trim ( $tmp ['f_recv_trans_main_id'] ) . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . trim ( $tmp ['f_recv_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'] );
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedExternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /send-internal/ ข้อมูลทะเบียนส่งภายใน
	 *
	 */
	public function sendInternalAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		global $lang;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		//include_once 'Account.Entity.php';
		//require_once 'Command.Entity.php';
		//include_once 'TransDfRecv.Entity.php';
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'SI' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['SI'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['SI'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['SI'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['SI'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['SI'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['SI']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['SI'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SI']['docNo']}%'";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['SI'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SI']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['SI'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['SI']['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['SI'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SI']['to']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}
			if (($_SESSION ['FilterRecord'] ['SI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}	
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		//$conn->debug  = true; 
		$regBookID = $_GET ['regBookID'];
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 1";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";      
        
		if ($util->isDFTransFiltered ( 'SI' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " ,c.f_sign_uid,c.f_sign_role_id ";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 1";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";      
		
		if ($util->isDFTransFiltered ( 'SI' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		if ($util->isDFTransFiltered ( 'SI' )) {
			//$rs = $conn->Execute ( $sql );
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
		
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			
			$senderEnt = new AccountEntity();
			$senderEnt->Load("f_acc_id = '{$tmp['f_sender_uid']}'");
			
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_send_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_send_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			$sendStatus = $lang ['common'] ['unreceived'];
            $recvUser = '';
            $recvStamp = '';
			if($tmp['f_received'] ==1) {
                $transDFRecv = new TransDfRecvEntity();            
                $transDFRecv->Load("f_send_id = '{$tmp ['f_send_trans_main_id']}' and f_send_seq = '{$tmp ['f_send_trans_main_seq']}'");
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$transDFRecv->f_accept_uid}'");
				$sendStatus = $lang ['common'] ['received'];
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
			}
			if($tmp['f_callback'] ==1) {
				$sendStatus = $lang ['df'] ['callback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_callback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
			}
			if($tmp['f_sendback'] ==1) {
				$sendStatus = $lang ['df'] ['sendback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_sendback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
			}
			
			
            //$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ) );
			//$receivedInternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser ,'abort'=>$tmp['f_abort']);
			//แก้ไข Notice ของ Line 1367
			$receivedInternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), /*'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'],*/ 'sendback' => $tmp ['f_sendback'], /*'cancel' => $tmp ['f_cancel'],*/ 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser ,'abort'=>$tmp['f_abort']);
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /send-circ/ ข้อมูลทะเบียนส่งเวียน
	 *
	 */
	public function sendCircAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
        global $lang;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
        //include_once 'Account.Entity.php';
        //require_once 'Command.Entity.php';
        //include_once 'TransDfRecv.Entity.php';
        
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'SC' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['SC'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['SC'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['SC'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['SC'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['SC'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['SC']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['SC'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SC']['docNo']}%'";
			}
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			// Filter Receive Doc.Date From & To
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SC'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SC'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SC'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_stamp >= {$filterDateFrom}";
			}
			if (($_SESSION ['FilterRecord'] ['SC'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SC'] ['docDateTo']  ) != 'default')) {
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SC'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_stamp <= {$filterDateTo}";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['SC'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SC']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['SC'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['SC']['docType']}'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['SC'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SC']['to']}%'";
			}
			
			$filterSQL2 = Array();
			//Filter Date Range
			if ($_SESSION ['FilterRecord'] ['SC'] ['sendFromDate'] != '') {
				if($_SESSION['FilterRecord']['SC']['sendFromDate'] == $_SESSION['FilterRecord']['SC']['sendToDate']) {
					$tmpToDate = $_SESSION['FilterRecord']['SC']['sendToDate'] + 86400;
					$filterSQL2 [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SC']['sendFromDate']}' and a.f_send_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL2 [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SC']['sendFromDate']}' and a.f_send_stamp <= '{$_SESSION['FilterRecord']['SC']['sendToDate']}'  )";
				}
			}
		}

		
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " AND ({$filterPiece}) ";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		if ( $filterSQL2[0] ) {
		$realFilter .= ' and ('.$filterSQL2[0].')';
		} else {
			$realFilter .= '  ' ;
		}
		
		// Modify by slc045
		$currentYear = $sessionMgr->getCurrentYear();
		
		/* Comment by SLC045
			Modify Date 08/03/2010
			Modify Change Default Year from range date
		*/
		
		$yearSendDateFrom = substr($util->getDateString(  $_SESSION ['FilterRecord'] ['SC'] ['sendFromDate'] ),-4);
		if ( $yearSendDateFrom != '' ) {
			$yearSendDateTo = substr($util->getDateString( $_SESSION ['FilterRecord'] ['SC'] ['sendToDate'] ),-4);
			if ( $yearSendDateFrom > 2400 ) { // convert year to Internation date mode
				$yearSendDateFrom -= 543;
			}
			if ( $yearSendDateTo > 2400 ) { // convert year to Internation date mode
				$yearSendDateTo -= 543;
			}
			if ( $yearSendDateTo < $yearSendDateFrom ) {
				$yearSendDateTo = $yearSendDateFrom;
			}
		} else {
			$yearSendDateFrom = $sessionMgr->getCurrentYear();
		}
		if ( $yearSendDateFrom != $sessionMgr->getCurrentYear() ) {
			$sqlWhereYear .= " and ( a.f_send_year >= {$yearSendDateFrom} and a.f_send_year <= {$yearSendDateTo})";  
		} else {
			$sqlWhereYear .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
		}
		
		//$rangeYear = " and a.f_send_year = {$currentYear} "; 
		$rangeYear = $sqlWhereYear;

		// end modify 
		
		//$conn->debug  = true; 
		
		$regBookID = $_GET ['regBookID'];
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 5";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} "; 
		//$sqlCount .= $rangeYear;
        
		if ($util->isDFTransFiltered ( 'SC' )) {
			$sqlCount .= $realFilter;
		}
		
		//$conn->debug = true;
		$rsCount = $conn->Execute ( $sqlCount );
		
        $sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
        $sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
        $sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
        $sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
        $sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 5";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";
	    //$sql .= $rangeYear;
        
		if ($util->isDFTransFiltered ( 'SC' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc,f_send_trans_main_id desc,f_send_seq desc";
/*		$fp = fopen("d:/SQLLogSC.txt",'a+');
		fwrite($fp,$sqlCount."\r\n");
		fwrite($fp,$sql."\r\n");
		fclose($fp);*/
		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		if ($util->isDFTransFiltered ( 'SC' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
            
            
            
            if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
                $hasAttach = 1;
            } else {
                $hasAttach = 0;
            }
            
        
            if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
                $isCirc = 0;
            } else {
                $isCirc = 1;
            }
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }
            
            $ownerOrg = new OrganizeEntity ( );
            $ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
            
            $senderEnt = new AccountEntity();
            $senderEnt->Load("f_acc_id = '{$tmp['f_sender_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_send_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_send_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            
            $sendStatus = $lang ['common'] ['unreceived'];
            $recvUser = '';
            $recvStamp = '';
            if($tmp['f_received'] ==1) {
                $transDFRecv = new TransDfRecvEntity();            
                $transDFRecv->Load("f_send_id = '{$tmp ['f_send_trans_main_id']}' and f_send_seq = '{$tmp ['f_send_trans_main_seq']}'");
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$transDFRecv->f_accept_uid}'");
                $sendStatus = $lang ['common'] ['received'];
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_callback'] ==1) {
                $sendStatus = $lang ['df'] ['callback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_callback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_sendback'] ==1) {
                $sendStatus = $lang ['df'] ['sendback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_sendback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
			
			//$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ) );
            //$receivedInternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser ,'abort'=>$tmp['f_abort']);
            //แก้ไขเรื่อง Notice ของ Line 1605
            $receivedInternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), /*'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'],*/ 'sendback' => $tmp ['f_sendback'], /*'cancel' => $tmp ['f_cancel'],*/ 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser ,'abort'=>$tmp['f_abort']);
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /send-classified/ ข้อมูลทะเบียนส่ง(ลับ)
	 *
	 */
	public function sendClassifiedAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
        global $lang;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		/*
		if(array_key_exists('sort',$_GET)) {
			switch($sort) {
				case ''
				 
			}
		} 
		*/
		
        //include_once 'Account.Entity.php';
        //require_once 'Command.Entity.php';
        //include_once 'TransDfRecv.Entity.php';
        
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'SS' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['SS'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['SS'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['SS'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['SS'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['SS'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['SS']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['SS'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SS']['docNo']}%'";
			}

			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SS'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SS'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SS'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}
			if (($_SESSION ['FilterRecord'] ['SS'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SS'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SS'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SS'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SS'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SS'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['SS'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SS']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['SS'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['SS']['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['SS'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SS']['to']}%'";
			}
			
			//Filter Date Range
			if ($_SESSION ['FilterRecord'] ['SS'] ['sendFromDate'] != '') {
				if($_SESSION['FilterRecord']['SS']['sendFromDate'] == $_SESSION['FilterRecord']['SS']['sendToDate']) {
					$tmpToDate = $_SESSION['FilterRecord']['SS']['sendToDate'] + 86400;
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SS']['sendFromDate']}' and a.f_send_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SS']['sendFromDate']}' and a.f_send_stamp <= '{$_SESSION['FilterRecord']['SS']['sendToDate']}'  )";
				}
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		//$conn->debug  = true; 
		$regBookID = $_GET ['regBookID'];
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 4";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
        
		if ($util->isDFTransFiltered ( 'SS' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
        $sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
        $sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
        $sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
        $sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
        $sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 4";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
        
		if ($util->isDFTransFiltered ( 'SS' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		if ($util->isDFTransFiltered ( 'SS' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
            
            if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
                $hasAttach = 1;
            } else {
                $hasAttach = 0;
            }
            
        
            if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
                $isCirc = 0;
            } else {
                $isCirc = 1;
            }
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }
            
            $ownerOrg = new OrganizeEntity ( );
            $ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
            
            $senderEnt = new AccountEntity();
            $senderEnt->Load("f_acc_id = '{$tmp['f_sender_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_send_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_send_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            $sendStatus = $lang ['common'] ['unreceived'];
            $recvUser = '';
            $recvStamp = '';
            if($tmp['f_received'] ==1) {
                $transDFRecv = new TransDfRecvEntity();            
                $transDFRecv->Load("f_send_id = '{$tmp ['f_send_trans_main_id']}' and f_send_seq = '{$tmp ['f_send_trans_main_seq']}'");
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$transDFRecv->f_accept_uid}'");
                $sendStatus = $lang ['common'] ['received'];
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_callback'] ==1) {
                $sendStatus = $lang ['df'] ['callback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_callback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_sendback'] ==1) {
                $sendStatus = $lang ['df'] ['sendback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_sendback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            
            
            //$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ) );
            $receivedInternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser ,'abort'=>$tmp['f_abort']);
			
			//$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ) );
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}

	/**
	 * action /send-classified-Int/ ข้อมูลทะเบียนส่งภายใน(ลับ)
	 *
	 */
	public function sendClassifiedIntAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
        global $lang;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		/*
		if(array_key_exists('sort',$_GET)) {
			switch($sort) {
				case ''
				 
			}
		} 
		*/
		
        //include_once 'Account.Entity.php';
        //require_once 'Command.Entity.php';
        //include_once 'TransDfRecv.Entity.php';
        
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'SSI' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['SSI'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['SSI'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['SSI'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['SSI'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['SSI'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['SSI']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['SSI'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SSI']['docNo']}%'";
			}

			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SSI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SSI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SSI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}
			if (($_SESSION ['FilterRecord'] ['SSI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SSI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SSI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SSI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SSI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SSI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['SSI'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SS']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['SSI'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['SS']['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['SSI'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SS']['to']}%'";
			}
			
			//Filter Date Range
			if ($_SESSION ['FilterRecord'] ['SSI'] ['sendFromDate'] != '') {
				if($_SESSION['FilterRecord']['SSI']['sendFromDate'] == $_SESSION['FilterRecord']['SSI']['sendToDate']) {
					$tmpToDate = $_SESSION['FilterRecord']['SSI']['sendToDate'] + 86400;
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SSI']['sendFromDate']}' and a.f_send_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SSI']['sendFromDate']}' and a.f_send_stamp <= '{$_SESSION['FilterRecord']['SSI']['sendToDate']}'  )";
				}
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		//$conn->debug  = true; 
		$regBookID = $_GET ['regBookID'];
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 10";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and c.f_gen_ext_type = 0";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
        
		if ($util->isDFTransFiltered ( 'SSI' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
        $sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
        $sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
        $sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
        $sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
        $sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 10";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and c.f_gen_ext_type = 0";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
        
		if ($util->isDFTransFiltered ( 'SSI' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";

		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		if ($util->isDFTransFiltered ( 'SSI' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
            
            if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
                $hasAttach = 1;
            } else {
                $hasAttach = 0;
            }
            
        
            if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
                $isCirc = 0;
            } else {
                $isCirc = 1;
            }
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }
            
            $ownerOrg = new OrganizeEntity ( );
            $ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
            
            $senderEnt = new AccountEntity();
            $senderEnt->Load("f_acc_id = '{$tmp['f_sender_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_send_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_send_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            $sendStatus = $lang ['common'] ['unreceived'];
            $recvUser = '';
            $recvStamp = '';
            if($tmp['f_received'] ==1) {
                $transDFRecv = new TransDfRecvEntity();            
                $transDFRecv->Load("f_send_id = '{$tmp ['f_send_trans_main_id']}' and f_send_seq = '{$tmp ['f_send_trans_main_seq']}'");
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$transDFRecv->f_accept_uid}'");
                $sendStatus = $lang ['common'] ['received'];
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_callback'] ==1) {
                $sendStatus = $lang ['df'] ['callback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_callback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_sendback'] ==1) {
                $sendStatus = $lang ['df'] ['sendback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_sendback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            
            
            //$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ) );
            $receivedInternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser ,'abort'=>$tmp['f_abort']);
			
			//$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ) );
		}

		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}

	/**
	 * action /send-classified-Ext/ ข้อมูลทะเบียนส่งภายนอก(ลับ)
	 *
	 */
	public function sendClassifiedExtAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
        global $lang;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		/*
		if(array_key_exists('sort',$_GET)) {
			switch($sort) {
				case ''
				 
			}
		} 
		*/
		
        //include_once 'Account.Entity.php';
        //require_once 'Command.Entity.php';
        //include_once 'TransDfRecv.Entity.php';
        
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'SSE' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['SSE'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['SSE'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['SSE'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['SSE'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['SSE'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['SSE']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['SSE'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SSE']['docNo']}%'";
			}

			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SSE'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SSE'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SSE'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}
			if (($_SESSION ['FilterRecord'] ['SSE'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SSE'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SSE'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SSE'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SSE'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SSE'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['SSE'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SSE']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['SSE'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['SSE']['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['SSE'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SSE']['to']}%'";
			}
			
			//Filter Date Range
			if ($_SESSION ['FilterRecord'] ['SSE'] ['sendFromDate'] != '') {
				if($_SESSION['FilterRecord']['SSE']['sendFromDate'] == $_SESSION['FilterRecord']['SSE']['sendToDate']) {
					$tmpToDate = $_SESSION['FilterRecord']['SSE']['sendToDate'] + 86400;
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SSE']['sendFromDate']}' and a.f_send_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SSE']['sendFromDate']}' and a.f_send_stamp <= '{$_SESSION['FilterRecord']['SSE']['sendToDate']}'  )";
				}
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		//$conn->debug  = true; 
		$regBookID = $_GET ['regBookID'];
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 9";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and c.f_gen_ext_type = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
        
		if ($util->isDFTransFiltered ( 'SSE' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
        $sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
        $sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
        $sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
        $sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
        $sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 9";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and c.f_gen_ext_type = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";
        
		if ($util->isDFTransFiltered ( 'SSE' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		if ($util->isDFTransFiltered ( 'SSE' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
            
            if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
                $hasAttach = 1;
            } else {
                $hasAttach = 0;
            }
            
        
            if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
                $isCirc = 0;
            } else {
                $isCirc = 1;
            }
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }
            
            $ownerOrg = new OrganizeEntity ( );
            $ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
            
            $senderEnt = new AccountEntity();
            $senderEnt->Load("f_acc_id = '{$tmp['f_sender_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_send_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_send_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            $sendStatus = $lang ['common'] ['unreceived'];
            $recvUser = '';
            $recvStamp = '';
            if($tmp['f_received'] ==1) {
                $transDFRecv = new TransDfRecvEntity();            
                $transDFRecv->Load("f_send_id = '{$tmp ['f_send_trans_main_id']}' and f_send_seq = '{$tmp ['f_send_trans_main_seq']}'");
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$transDFRecv->f_accept_uid}'");
                $sendStatus = $lang ['common'] ['received'];
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_callback'] ==1) {
                $sendStatus = $lang ['df'] ['callback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_callback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_sendback'] ==1) {
                $sendStatus = $lang ['df'] ['sendback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_sendback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            
            
            //$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ) );
            $receivedInternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser ,'abort'=>$tmp['f_abort']);
			
			//$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ) );
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /send-external/ ข้่อมูลทะเบียนส่งภายนอก
	 *
	 */
	public function sendExternalAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
        global $lang;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		/*
		if(array_key_exists('sort',$_GET)) {
			switch($sort) {
				case ''
				 
			}
		} 
		*/
		
        //include_once 'Account.Entity.php';
        //require_once 'Command.Entity.php';
        //include_once 'TransDfRecv.Entity.php';
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'SE' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['SE'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['SE'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['SE'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['SE'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['SE'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['SE']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['SE'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SE']['docNo']}%'";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['SE'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SE']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['SE'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['SE']['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['SE'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SE']['to']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SE'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SE'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SE'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['SE'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SE'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SE'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SE'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SE'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SE'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		$regBookID = $_GET ['regBookID'];
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 2";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";    
        
        
		if ($util->isDFTransFiltered ( 'SE' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
        
        $sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
        $sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
        $sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
        $sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
        $sql .= " ,c.f_sign_uid,c.f_sign_role_id ";
        $sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";        
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 2";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";    
        
		if ($util->isDFTransFiltered ( 'SE' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		//echo $sql;
		//$conn->debug  = true;
		if ($util->isDFTransFiltered ( 'SE' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$receivedExternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
            
            if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
                $hasAttach = 1;
            } else {
                $hasAttach = 0;
            }
            
        
            if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
                $isCirc = 0;
            } else {
                $isCirc = 1;
            }
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }
            
            $ownerOrg = new OrganizeEntity ( );
            $ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
            
            $senderEnt = new AccountEntity();
            $senderEnt->Load("f_acc_id = '{$tmp['f_sender_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_send_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_send_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            $sendStatus = $lang ['common'] ['unreceived'];
            $recvUser = '';
            $recvStamp = '';
            if($tmp['f_received'] ==1) {
                $transDFRecv = new TransDfRecvEntity();            
                $transDFRecv->Load("f_send_id = '{$tmp ['f_send_trans_main_id']}' and f_send_seq = '{$tmp ['f_send_trans_main_seq']}'");
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$transDFRecv->f_accept_uid}'");
                $sendStatus = $lang ['common'] ['received'];
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_callback'] ==1) {
                $sendStatus = $lang ['df'] ['callback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_callback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_sendback'] ==1) {
                $sendStatus = $lang ['df'] ['sendback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_sendback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            
            //$receivedExternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ) );
            //$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ) );
            $receivedExternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), /*'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'],*/ 'sendback' => $tmp ['f_sendback'], /*'cancel' => $tmp ['f_cancel'],*/ 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser ,'abort'=>$tmp['f_abort']);          
			
		}
		
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		//$count = count ( $receivedInternal );
		$data = json_encode ( $receivedExternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /send-external-global/ ข้อมูลทะเบียนส่งภายนอกทะเบียนกลาง
	 *
	 */
	public function sendExternalGlobalAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
        global $lang;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
        //include_once 'Account.Entity.php';
        //require_once 'Command.Entity.php';
        //include_once 'TransDfRecv.Entity.php';
        
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'SG' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['SG'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['SG'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['SG'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['SG'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['SG'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['SG']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['SG'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SG']['docNo']}%'";
			}
			
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SG'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SG'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SG'] ['docDateFrom'] );
				//$filterSQL [] = " a.f_send_stamp >= {$filterDateFrom}";
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}

			if (($_SESSION ['FilterRecord'] ['SG'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SG'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SG'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SG'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SG'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SG'] ['docDateTo'] ) + 86399;				
				//$filterSQL [] = " a.f_send_stamp <= {$filterDateTo}";
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['SG'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SG']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['SG'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['SG']['docType']}'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['SG'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['SG']['from']}%'";
			}
			
			if ($_SESSION ['FilterRecord'] ['SG'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SG']['to']}%'";
			}
			
			//Filter Date Range
//			if ($_SESSION ['FilterRecord'] ['SG'] ['sendFromDate'] != '') {
//				if($_SESSION['FilterRecord']['SG']['sendFromDate'] == $_SESSION['FilterRecord']['SG']['sendToDate']) {
//					$tmpToDate = $_SESSION['FilterRecord']['SG']['sendToDate'] + 86399;
//					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SG']['sendFromDate']}' and a.f_send_stamp <= '{$tmpToDate}'  )";
//				} else {
//					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['SG']['sendFromDate']}' and a.f_send_stamp <= '{$_SESSION['FilterRecord']['SG']['sendToDate']}'  )";
//				}
//			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		$regBookID = $_GET ['regBookID'];

		/* Comment by SLC045
			Modify Date 08/03/2010
			Modify Change Default Year from range date
		*/
		/* Group these line with checking if-blank. 10/08/2010 by SLC245 (Art) */
		
		if ($util->isDFTransFiltered ( 'SG' )) 
		{
		if (array_key_exists('sendFromDate', $_SESSION['FilterRecord']['SG']) && $_SESSION['FilterRecord']['SG']['sendFromDate'])
		{
			$yearSendDateFrom = substr($util->getDateString(  $_SESSION ['FilterRecord'] ['SG'] ['sendFromDate'] ),-4);
			if ( $yearSendDateFrom > 2400 ) { // convert year to Internation date mode
				$yearSendDateFrom -= 543;
			}
		}
		if (array_key_exists('sendToDate', $_SESSION['FilterRecord']['SG']) && $_SESSION['FilterRecord']['SG']['sendToDate'])
		{
			$yearSendDateTo = substr($util->getDateString( $_SESSION ['FilterRecord'] ['SG'] ['sendToDate'] ),-4);
			if ( $yearSendDateTo > 2400 ) { // convert year to Internation date mode
				$yearSendDateTo -= 543;
			}
			if ( $yearSendDateTo < $yearSendDateFrom ) {
				$yearSendDateTo = $yearSendDateFrom;
			}
		}
		}
	
		if (isset($yearSendDateFrom) && ($yearSendDateFrom != $sessionMgr->getCurrentYear() )) {
			$sqlWhereYear .= " and ( a.f_send_year >= {$yearSendDateFrom} and a.f_send_year <= {$yearSendDateTo})";  
		} else {
			$sqlWhereYear .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
		}
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 3";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		/* Comment by SLC045
			Modify Date 08/03/2010
			Modify Change Default Year from range date
		*/		
//        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
		$sqlCount .= $sqlWhereYear;

		if ($util->isDFTransFiltered ( 'SG' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
        $sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
        $sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
        $sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
        $sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
        $sql .= " ,c.f_sign_uid,c.f_sign_role_id ";
        $sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		//$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		//$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 3";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		/* Comment by SLC045
			Modify Date 08/03/2010
			Modify Change Default Year from range date
		*/
       // $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";
	   $sql .= $sqlWhereYear;
        // End Modify 
		
		if ($util->isDFTransFiltered ( 'SG' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";

/*
		$fp = fopen( "d:/writeSQLlog.log","a+");
		fwrite( $fp, $sqlCount."\r\n".$sql."\r\n");
		fclose( $fp );
*/

		if ($util->isDFTransFiltered ( 'SG' )) {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$receivedExternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
            
            if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
                $hasAttach = 1;
            } else {
                $hasAttach = 0;
            }
            
        
            if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
                $isCirc = 0;
            } else {
                $isCirc = 1;
            }
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }
            
            $ownerOrg = new OrganizeEntity ( );
            $ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
            
            $senderEnt = new AccountEntity();
            $senderEnt->Load("f_acc_id = '{$tmp['f_sender_uid']}'");
            
            $command = new CommandEntity ( );
            
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_send_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_send_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            
            $sendStatus = $lang ['common'] ['unreceived'];
            $recvUser = '';
            $recvStamp = '';
            
            if($tmp['f_received'] ==1) {
                $transDFRecv = new TransDfRecvEntity();            
                $transDFRecv->Load("f_send_id = '{$tmp ['f_send_trans_main_id']}' and f_send_seq = '{$tmp ['f_send_trans_main_seq']}'");
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$transDFRecv->f_accept_uid}'");
                $sendStatus = $lang ['common'] ['received'];
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
			
            if($tmp['f_received'] ==2) {
            	$sendStatus = 'ปฏิเสธการรับ';
            }
            
            if($tmp['f_callback'] ==1) {
                $sendStatus = $lang ['df'] ['callback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_callback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            
            if($tmp['f_sendback'] ==1) {
                $sendStatus = $lang ['df'] ['sendback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_sendback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            
            //$receivedExternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ) );
            //$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ) );
            //$receivedExternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser,'abort'=>$tmp['f_abort']);
            //แก้ไข Notice ของ Line 2328
            $signUser="";
            if(!is_null($tmp['f_sign_uid'])){
            	$signedUserEntity = new AccountEntity();
            	$signedRoleEntity = new RoleEntity();
            	$signedUserEntity->Load("f_acc_id = '{$tmp['f_sign_uid']}'");
            	$signedRoleEntity->Load("f_role_id = '{$tmp['f_sign_role_id']}'");
            	$signUser = "{$signedUserEntity->f_name} {$signedUserEntity->f_last_name}({$signedRoleEntity->f_role_name})";
            }
            
            $receivedExternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), /*'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'],*/ 'sendback' => $tmp ['f_sendback'], /*'cancel' => $tmp ['f_cancel'],*/ 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser,'abort'=>$tmp['f_abort'],'egifRecv'=>$tmp['f_egif_recv_reg_no'],'signedUser'=>UTFEncode($signUser));
            
			
		}
		
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		//$count = count ( $receivedInternal );
		$data = json_encode ( $receivedExternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
    
	/**
	 * action /send-internal-global/ ข้อมูลทะเบียนส่งภายในทะเบียนกลาง
	 *
	 */
    public function sendInternalGlobalAction() {
        global $conn;
        //global $config;
        global $sessionMgr;
        global $util;
        global $lang;
        
        $start = $_GET ['start'];
        $limit = $_GET ['limit'];
        $sort = $_GET ['sort'];
        $sortDir = $_GET ['dir'];
        /*
        if(array_key_exists('sort',$_GET)) {
            switch($sort) {
                case ''
                 
            }
        } 
        */
        
        //include_once 'Account.Entity.php';
        //require_once 'Command.Entity.php';
        //include_once 'TransDfRecv.Entity.php';
        
        $filterSQL = Array ( );
        if ($util->isDFTransFiltered ( 'SGI' )) {
            // Filter Receive Number
            if ($_SESSION ['FilterRecord'] ['SGI'] ['sendNo'] != '') {
                if (ereg ( ",", $_SESSION ['FilterRecord'] ['SGI'] ['sendNo'] )) {
                    $regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['SGI'] ['sendNo'] );
                    $tmpFilterSQL = " (";
                    $tmpFilterSubSQL = "";
                    foreach ( $regNoArray as $regNo ) {
                        
                        if (ereg ( "-", $regNo )) {
                            list ( $startR, $stopR ) = split ( "-", $regNo );
                            $filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
                        } else {
                            if ($tmpFilterSubSQL == "") {
                                $tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
                            } else {
                                $tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
                            }
                        }
                    }
                    $tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
                    $filterSQL [] = $tmpFilterSQL;
                } else {
                    if (ereg ( "-", $_SESSION ['FilterRecord'] ['SGI'] ['sendNo'] )) {
                        list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['SGI'] ['sendNo'] );
                        $tmpFilterSQL = "";
                        $filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
                    } else {
                        $filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['SGI']['sendNo']}'";
                        //$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
                    }
                }
            
            }
            // Filter Receive Doc.No
            if ($_SESSION ['FilterRecord'] ['SGI'] ['docNo'] != '') {
                $filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SGI']['docNo']}%'";
            }

			// Filter Receive Title
            if ($_SESSION ['FilterRecord'] ['SGI'] ['title'] != '') {
                $filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SGI']['title']}%'";
            }
            // Filter Receive Doc.Type
            if ($_SESSION ['FilterRecord'] ['SGI'] ['docType'] != '') {
                $filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['SGI']['docType']}'";
            }
            
        // Filter From
			if ($_SESSION ['FilterRecord'] ['SGI'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['SGI']['from']}%'";
			}
			if ($_SESSION ['FilterRecord'] ['SGI'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SGI']['to']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SGI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SGI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SGI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}
			if (($_SESSION ['FilterRecord'] ['SGI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SGI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SGI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SGI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SGI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SGI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
        }
        
        if (count ( $filterSQL ) > 0) {
            if (count ( $filterSQL ) > 1) {
                //$realFilter = "";
                $tmpRealFilter = "";
                foreach ( $filterSQL as $filterPiece ) {
                    if ($tmpRealFilter == "") {
                        $tmpRealFilter = "({$filterPiece})";
                    } else {
                        $tmpRealFilter .= " and ({$filterPiece})";
                    }
                }
                $realFilter = " and ({$tmpRealFilter})";
            } else {
                $realFilter = " and {$filterSQL[0]}";
            }
        } else {
            $realFilter = "";
        }
        
        $regBookID = $_GET ['regBookID'];
        
        $sqlCount = "select count(*) as count_exp ";
        $sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
        //$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
        $sqlCount .= " a.f_reg_book_id = {$regBookID}";
        $sqlCount .= " and a.f_send_type = 7";
        $sqlCount .= " and a.f_show_in_reg_book = 1";
        $sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
        $sqlCount .= " and b.f_doc_id = c.f_doc_id";
        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";  
        
        if ($util->isDFTransFiltered ( 'SGI' )) {
            $sqlCount .= $realFilter;
        }
        
        $rsCount = $conn->Execute ( $sqlCount );
        
        $sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
        $sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
        $sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
        $sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
        $sql .= " ,c.f_sign_uid,c.f_sign_role_id ";
        $sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
        //$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
        //$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
        //$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
        //$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
        $sql .= " a.f_reg_book_id = {$regBookID}";
        $sql .= " and a.f_send_type = 7";
        $sql .= " and a.f_show_in_reg_book = 1";
        $sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
        $sql .= " and b.f_doc_id = c.f_doc_id ";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";
        
        if ($util->isDFTransFiltered ( 'SGI' )) {
            $sql .= $realFilter;
        }
        $sql .= " order by a.f_send_reg_no desc";
        
        //echo $sql;
        //$conn->debug  = true;
        if ($util->isDFTransFiltered ( 'SGI' )) {
            //$rs = $conn->SelectLimit ( $sql, $limit, $start );
            $rs = $conn->Execute ( $sql );
        } else {
            $rs = $conn->SelectLimit ( $sql, $limit, $start );
        }
        //$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
        $receivedExternal = Array ( );
        while ( $tmp = $rs->FetchRow () ) {
            checkKeyCase ( $tmp );
            //print_r($tmp);
            if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
                $docNo = "";
            } else {
                $docNo = UTFEncode ( $tmp ['f_doc_no'] );
            }
            
            if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
                $from = "";
            } else {
                $from = UTFEncode ( $tmp ['f_send_fullname'] );
            }
            
            if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
                $to = "";
            } else {
                $to = UTFEncode ( $tmp ['f_recv_fullname'] );
            }
            
            if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
                $hasAttach = 1;
            } else {
                $hasAttach = 0;
            }
            
        
            if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
                $isCirc = 0;
            } else {
                $isCirc = 1;
            }
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }
            
            $ownerOrg = new OrganizeEntity ( );
            $ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
            
            $senderEnt = new AccountEntity();
            $senderEnt->Load("f_acc_id = '{$tmp['f_sender_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_send_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_send_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            $sendStatus = $lang ['common'] ['unreceived'];
            $recvUser = '';
            $recvStamp = '';
            if($tmp['f_received'] ==1) {
                $transDFRecv = new TransDfRecvEntity();            
                $transDFRecv->Load("f_send_id = '{$tmp ['f_send_trans_main_id']}' and f_send_seq = '{$tmp ['f_send_trans_main_seq']}'");
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$transDFRecv->f_accept_uid}'");
                $sendStatus = $lang ['common'] ['received'];
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_callback'] ==1) {
                $sendStatus = $lang ['df'] ['callback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_callback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            if($tmp['f_sendback'] ==1) {
                $sendStatus = $lang ['df'] ['sendback'] ;
                $receiverEntity = new AccountEntity();
                $receiverEntity->Load("f_acc_id = '{$tmp['f_sendback_uid']}'");
                $recvUser = UTFEncode($receiverEntity->f_name.' '.$receiverEntity->f_last_name);
                $recvStamp = UTFEncode($util->getDateString($transDFRecv->f_recv_stamp).' '.$util->getTimeString($transDFRecv->f_recv_stamp));
            }
            
            //$receivedExternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ) );
            //$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ) );
            //$receivedExternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser,'abort'=>$tmp['f_abort']);
            //แก้ไข Notice ของ Line 2571
            $receivedExternal [] = Array ('sendID' =>  trim ( $tmp ['f_send_trans_main_id']) . '_' .  trim ( $tmp ['f_send_trans_main_seq']) . '_' .  trim ( $tmp ['f_send_id']), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), /*'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'],*/ 'sendback' => $tmp ['f_sendback'], /*'cancel' => $tmp ['f_cancel'],*/ 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach,  'governerApprove' => $tmp ['f_governer_approve'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $tmp ['f_doc_id'], 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold ,'isCirc'=>$isCirc, 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ),'sendUser'=>UTFEncode($senderEnt->f_name.' '.$senderEnt->f_last_name),'sendStatus'=>UTFEncode($sendStatus),'receiveStamp' =>$recvStamp,'receiveUser'=>$recvUser,'abort'=>$tmp['f_abort']);
            
            
        }
        
        $tmpCount = $rsCount->FetchNextObject ();
        $count = $tmpCount->COUNT_EXP;
        //$count = count ( $receivedInternal );
        $data = json_encode ( $receivedExternal );
        $cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
        print $cb . '({"total":"' . $count . '","results":' . $data . '})';
    
    }
	
    /**
     * action /unreceive/ ข้อมูลหนังสือรอลงรับ
     *
     */
	public function unreceiveAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
        //include_once('DFTransaction.php');
        $dfTrans = new DFTransaction();
        
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		//$conn->debug  = true; 
		//$regBookID = $_GET['regBookID'];
        
        if (array_key_exists ( 'filter', $_GET )) {
            
            //Filter #1
            $filtervalue1 = UTFDecode ( $_GET ['filter'] [0] ['data'] ['value'] );
           
            if(trim($filtervalue1) != '') {
                if($_GET ['filter'] [0] ['field'] == 'docNo') {
                    $filterField1 = "c.f_doc_no";
                } else {
                    $filterField1 = "c.f_title";
                }
                $filterQuery1 = " {$filterField1} like '%{$filtervalue1}%'";
            } else {
                $filterQuery1 = "";
            }
            
            //Filter #2
            $filtervalue2 = UTFDecode ( $_GET ['filter'] [1] ['data'] ['value'] );
            
            if(trim($filtervalue2) != '') {   
                if($_GET ['filter'] [1] ['field'] == 'docNo') {
                    $filterField2 = "c.f_doc_no";
                } else {
                    $filterField2 = "c.f_title";
                }
                $filterQuery2 = " {$filterField2} like '%{$filtervalue2}%'";
            } else {
                $filterQuery2 = "";
            }
            
            if($filterQuery1 != '') {
                if($filterQuery2 != '') {
                    $filterQuery = " and ({$filterQuery1} and {$filterQuery2})";
                } else {
                    $filterQuery = " and {$filterQuery1}";
                }
            } else {
                if($filterQuery2 != '') {
                    $filterQuery = " and {$filterQuery2}";
                } else {
                    $filterQuery = "";
                }
            }
            
        } else {
            $filterQuery = "";
        }
          
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
		$sqlCount .= " and (b.f_abort is null OR a.f_send_year = '".$sessionMgr->getCurrentYear()."')";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id {$filterQuery}";
		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_start_type,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		//$sql .= " and a.f_reg_book_id = {$regBookID}";
		//$sql .= " and a.f_send_type = 1";
		//$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_received = 0";
		$sql .= " and a.f_sendback = 0";
		$sql .= " and a.f_callback = 0";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and (b.f_abort is null OR a.f_send_year = '".$sessionMgr->getCurrentYear()."')";
		$sql .= " and b.f_doc_id = c.f_doc_id {$filterQuery} order by a.f_send_sys_stamp desc";
		
		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			/*
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode( $tmp ['f_send_fullname'] );
			}
			*/
			
			$from = UTFEncode ( $util->parseFullname ( $tmp ['f_sender_uid'], $tmp ['f_sender_role_id'], $tmp ['f_sender_org_id'] ) );
			
			/*
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode( $tmp ['f_recv_fullname'] );
			}
			*/
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}		
           
			$docType = $dfTrans->getOriginalDocType($tmp ['f_send_trans_main_id']);

            switch($tmp['f_start_type']) {
				case 'RSE':
                case 'RE':
                case 'RG':
                    $originType = 2;
                    break;
  				case 'RS':
					if ($docType == 2 || $docType == 3) {
						$originType = 2;
						break;
					} else {
						$originType = 1;
						break;
					}
                default :
                    $originType = 1;
                    break;                     
            }

			if($originType==1 and $tmp['f_send_type']==6) {
			$tmp['f_send_type'] = 6.1;
			}

			if($originType==2 and $tmp['f_send_type']==6) {
			$tmp['f_send_type'] = 6.2;
			}
            
            $sendStamp = "";
            $sendStamp = UTFEncode($util->getDateString($tmp['f_send_stamp']).' '.$util->getTimeString($tmp['f_send_stamp']));
			
			$receiveExternalReg = $dfTrans->getReceiveExternalNumber( $tmp ['f_send_trans_main_id'] );
            
			$to = UTFEncode ( $util->parseFullname ( $tmp ['f_receiver_uid'], $tmp ['f_receiver_role_id'], $tmp ['f_receiver_org_id'] ) );
			$receivedInternal [] = Array ('sendID' => $tmp ['f_send_trans_main_id'] . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . $tmp ['f_send_id'], 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'receiveExternalRunning' => $receiveExternalReg, 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )) , 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => UTFEncode($dfTrans->getOriginalFrom($tmp ['f_send_trans_main_id'])), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'hasAttach' => $hasAttach, 'isCirc' => $isCirc, 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'] ,'hold'=>$hold,'originType'=>$originType,'abort'=>$tmp['f_abort'],'sendStamp'=> $sendStamp);
		}
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		
		$sqlEGIF = "select a.*,c.f_security_modifier,c.f_urgent_modifier";
		$sqlEGIF .= " ,c.f_title as f_title_2,c.f_doc_no,c.f_doc_date,c.f_doc_id as f_doc_id_2";
		$sqlEGIF .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sqlEGIF .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sqlEGIF .= " from tbl_fake_egif a,tbl_doc_main c where ";
		//$sql .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		//$sql .= " and a.f_reg_book_id = {$regBookID}";
		//$sql .= " and a.f_send_type = 1";
		//$sql .= " and a.f_show_in_reg_book = 1";
		$sqlEGIF .= " a.f_received = 0 ";
		//$sql .= " and a.f_sendback = 0";
		//$sql .= " and a.f_callback = 0";
		//$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlEGIF .= " and a.f_doc_id = c.f_doc_id ";
		
		$rsEGIF = $conn->SelectLimit ( $sqlEGIF, $limit, $start );
		while ( $tmp = $rsEGIF->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			/*
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode( $tmp ['f_send_fullname'] );
			}
			*/
			
			$from = UTFEncode ( $tmp ['f_sender_org_full'] );
			
			/*
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode( $tmp ['f_recv_fullname'] );
			}
			*/
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}		
            
            switch($tmp['f_start_type']) {
                case 'RE':
                case 'RG':
                    $originType = 2;
                    break;
                //case '':
                default :
                    $originType = 1;
                    break;                     
            }
			
			$receiveExternalReg = "-";
            
			$sendStamp = "";
            $sendStamp = UTFEncode($util->getDateString($tmp['f_send_stamp']).' '.$util->getTimeString($tmp['f_send_stamp']));
			$sendStamp = "-";
			$to = UTFEncode ( $tmp ['f_receiver_org_full'] ) ;
			$receivedInternal [] = Array ('sendID' => $tmp ['f_egif_trans_id'],'sendType' => 999, 'secret' => 0, 'speed' => 0, 'receiveExternalRunning' => $receiveExternalReg, 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )) , 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'hasAttach' => $hasAttach, 'isCirc' => $isCirc, 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'] ,'hold'=>$hold,'originType'=>$originType,'abort'=>$tmp['f_abort'],'sendStamp'=>$sendStamp);
		}
		
		$sqlCountEgif = "select count(*) as count_exp from tbl_fake_egif where f_received = 0";
		$rsCountEgif = $conn->Execute($sqlCountEgif);
		$tmpCountEgif = $rsCountEgif->FetchNextObject();
		
		$count = $tmpCount->COUNT_EXP + $tmpCountEgif->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /sendback/ ข้อมูลทะเบียนส่งกลับ
	 *
	 */
	public function sendbackAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'SBI' )) {
			// Filter Receive Number
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['SBI'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['SBI']['docNo']}%'";
			}
			
			// Filter Receive Doc.Date
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SBI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SBI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SBI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['SBI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SBI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SBI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SBI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SBI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SBI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['SBI'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['SBI']['title']}%'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['SBI'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['SBI']['from']}%'";
			}

			// Filter To
			if ($_SESSION ['FilterRecord'] ['SBI'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SBI']['to']}%'";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}

		//$conn->debug  = true; 
//		$regBookID = $_GET['regBookID'];
		

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		//$sqlCount .= " a.f_sender_org_id in (select f_org_id from tbl_organize where f_org_pid = {$sessionMgr->getCurrentOrgID()} or f_org_id = {$sessionMgr->getCurrentOrgID()}) ";
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

		if ($util->isDFTransFiltered ('SBI')) {
			$sqlCount .= $realFilter;
		}

		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ,b.f_owner_org_id";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		//$sql .= " a.f_sender_org_id in (select f_org_id from tbl_organize where f_org_id = (select f_org_pid from tbl_organize where f_org_id = {$sessionMgr->getCurrentOrgID()}) or  f_org_id = {$sessionMgr->getCurrentOrgID()}) ";
		//$sql .= " and a.f_reg_book_id = {$regBookID}";
		//$sql .= " and a.f_send_type = 1";   
		//$sql .= " and a.f_show_in_reg_book = 1";
		//$sql .= " and a.f_received = 0";
		$sql .= " and a.f_sendback = 1";
		$sql .= " and a.f_sendback_ack = 0";
		//$sql .= " and a.f_callback = 0"; 
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";   

		if ($util->isDFTransFiltered ('SBI')) {
			$sql .= $realFilter;
		}		

		$sql .= " order by a.f_send_reg_no desc";

		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}		
			
			$receivedInternal [] = Array ('isCirc'=>$isCirc,'sendID' => $tmp ['f_send_trans_main_id'] . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . $tmp ['f_send_id'], 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'hasAttach' => $hasAttach, 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'] ,'hold'=>$hold,'comment'=>UTFEncode($tmp['f_sendback_comment']));
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /callback/ ข้อมูลทะเบียนดึงคืน
	 *
	 */
	public function callbackAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];

		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'CBI' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['CBI'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['CBI'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['CBI'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['CBI'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['CBI'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['CBI']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['CBI'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['CBI']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['CBI'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['CBI']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['CBI'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['CBI']['docType']}'";
			}

		// Filter From
			if ($_SESSION ['FilterRecord'] ['CBI'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['CBI']['from']}%'";
			}
			
		// Filter To
			if ($_SESSION ['FilterRecord'] ['CBI'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['CBI']['to']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['CBI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['CBI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['CBI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['CBI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['CBI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['CBI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['CBI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['CBI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['CBI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}

		$regBookID = $_GET ['regBookID'];
        
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_callback = 1";
		$sqlCount .= " and (a.f_callback_ack = 0 or a.f_callback_ack is null)";
		$sqlCount .= " and a.f_callback_role_id = {$sessionMgr->getCurrentRoleID()}";
		$sqlCount .= " and a.f_callback_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";   
		$rsCount = $conn->Execute ( $sqlCount );

		if ($util->isDFTransFiltered ('CBI')) {
			$sqlCount .= $realFilter;
		}
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_callback = 1 ";
		$sql .= " and (a.f_callback_ack = 0 or a.f_callback_ack is null)";
		$sql .= " and a.f_callback_role_id = {$sessionMgr->getCurrentRoleID()}";
		$sql .= " and a.f_callback_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";   

		if ($util->isDFTransFiltered ('CBI')) {
			$sql .= $realFilter;
		}

		$sql .= " order by a.f_send_reg_no desc";

		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}	
			
			$receivedInternal [] = Array ('isCirc'=>$isCirc,'sendID' => $tmp ['f_send_trans_main_id'] . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . $tmp ['f_send_id'], 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'hasAttach' => $hasAttach, 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'] ,'hold'=>$hold,'comment'=>UTFEncode($tmp['f_callback_comment']));
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}

	/**
	 * action /personal-receive/ ข้อมูลหนังสือรอดำเนินการ
	 *
	 */
	public function personalReceivedAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
        
        //include_once('DFTransaction.php');
        $dfTrans = new DFTransaction();
        
		//require_once 'Command.Entity.php';
		//require_once 'Organize.Entity.php';
		
		if (array_key_exists ( 'start', $_GET )) {
			$start = $_GET ['start'];
			$limit = $_GET ['limit'];
			$sort = $_GET ['sort'];
			$sortDir = $_GET ['dir'];
		} else {
			$start = 0;
			$limit = 25;
			//$sort = $_GET ['sort'];
		//$sortDir = $_GET ['dir'];
		}
        
        if (array_key_exists ( 'filter', $_GET )) {
            
            //Filter #1
            $filtervalue1 = UTFDecode ( $_GET ['filter'] [0] ['data'] ['value'] );
           
            if(trim($filtervalue1) != '') {
                if($_GET ['filter'] [0] ['field'] == 'docNo') {
                    $filterField1 = "c.f_doc_no";
                } else {
                    $filterField1 = "c.f_title";
                }
                $filterQuery1 = " {$filterField1} like '%{$filtervalue1}%'";
            } else {
                $filterQuery1 = "";
            }
            
            //Filter #2
            $filtervalue2 = UTFDecode ( $_GET ['filter'] [1] ['data'] ['value'] );
            
            if(trim($filtervalue2) != '') {   
                if($_GET ['filter'] [1] ['field'] == 'docNo') {
                    $filterField2 = "c.f_doc_no";
                } else {
                    $filterField2 = "c.f_title";
                }
                $filterQuery2 = " {$filterField2} like '%{$filtervalue2}%'";
            } else {
                $filterQuery2 = "";
            }
            
            if($filterQuery1 != '') {
                if($filterQuery2 != '') {
                    $filterQuery = " and ({$filterQuery1} and {$filterQuery2})";
                } else {
                    $filterQuery = " and {$filterQuery1}";
                }
            } else {
                if($filterQuery2 != '') {
                    $filterQuery = " and {$filterQuery2}";
                } else {
                    $filterQuery = "";
                }
            }
            
        } else {
            $filterQuery = "";
        }
		
		$regBookID = $_GET ['regBookID'];
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$countSQL .= " ((a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()})";
		//$countSQL .= " or (a.f_receiver_role_id = {$sessionMgr->getCurrentRoleID()})";
		//$countSQL .= " or (a.f_receiver_uid = {$sessionMgr->getCurrentAccID()} ))";
		

		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		//$countSQL .= " and a.f_governer_approve = 0";
        $countSQL .= " and (a.f_governer_approve = 0 or a.f_governer_approve is null)";  
		//$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()}";
		$countSQL .= " and b.f_doc_id = c.f_doc_id {$filterQuery}";
		
		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql .= " ((a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()})";
		//$sql .= " or (a.f_receiver_role_id = {$sessionMgr->getCurrentRoleID()})";
		//$sql .= " or (a.f_receiver_uid = {$sessionMgr->getCurrentAccID()} ))";
		

		$sql .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and (a.f_governer_approve = 0 or a.f_governer_approve is null)";
		//$sql .= " and a.f_reg_book_id = {$regBookID}";
		//$sql .= " and a.f_recv_type = 1";
		//$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()}";
		$sql .= " and b.f_doc_id = c.f_doc_id  {$filterQuery} order by a.f_recv_type asc,a.f_recv_reg_no desc";


		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		

		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			if($tmp['f_return_owner'] != 1 ||is_null($tmp['f_return_owner'])) {
				$returnOwner = 0;
			} else {
				$returnOwner = 1;
			}
			
			$hasReserve = 0;
			$sqlCheckHasReserve = "select count(*) as COUNT_EXP from tbl_reserve_no where f_doc_id = '{$tmp ['f_doc_id']}'";
			$rsCheckHasReserve = $conn->Execute($sqlCheckHasReserve);
			$tmpCheckHasReserve = $rsCheckHasReserve->FetchNextObject();
			if($tmpCheckHasReserve->COUNT_EXP > 0) {
				$hasReserve = 1;
			}
			
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );
			$receivedInternal [] = Array ('isCirc'=>$isCirc,'recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'recvType' => $tmp ['f_recv_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ),'from' => UTFEncode($dfTrans->getOriginalFrom($tmp ['f_recv_trans_main_id'])), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach, 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'], 'ownerOrgID' => $ownerOrg->f_org_id , 'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ),'hold'=>$hold,'hasReserved'=> $hasReserve ,'returnOwner' => $returnOwner);
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /personal-outgoing/ ข้อมูลหนังสือส่งออก
	 *
	 */
	public function personalOutgoingAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
        
        //include_once('DFTransaction.php');
        $dfTrans = new DFTransaction();
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'OGI' )) {
			// Filter Receive Number
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['OGI'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['OGI']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['OGI'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['OGI']['title']}%'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['OGI'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['OGI']['from']}%'";
			}

			// Filter To
			if ($_SESSION ['FilterRecord'] ['OGI'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['OGI']['to']}%'";
			}

			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['OGI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['OGI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['OGI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['OGI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['OGI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['OGI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['OGI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['OGI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['OGI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}									
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		//$conn->debug  = true; 
		$regBookID = $_GET ['regBookID'];
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_uid = {$sessionMgr->getCurrentAccID()}";
        //$sqlCount .= " and a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";  
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		//$sqlCount .= " and (a.f_send_type = 1 or a.f_send_type = 2)";
		
		$sqlCount .= " and a.f_is_forward_trans = 0";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_sendback = 0";
		$sqlCount .= " and a.f_callback = 0";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
        $sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()}";
         
//		echo "alert('$sqlCount');";
		if ($util->isDFTransFiltered ('OGI')) {
			$sqlCount .= $realFilter;
		}

		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_uid = {$sessionMgr->getCurrentAccID()}";
        //$sql .= " and a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";  
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		//$sql .= " and a.f_send_type = 1";
		$sql .= " and a.f_is_forward_trans = 0";
		$sql .= " and a.f_sendback = 0";
		$sql .= " and a.f_callback = 0";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()}";     
		$sql .= " and b.f_doc_id = c.f_doc_id ";      
		
//		echo "alert('$sql');";
		if ($util->isDFTransFiltered ( 'OGI' )) {
			$sql .= $realFilter;
		}

		$sql .= " order by a.f_send_type asc,a.f_send_sys_stamp desc";

		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode (stripslashes( $tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ),'from' => UTFEncode ( $dfTrans->getOriginalFrom($tmp ['f_send_trans_main_id'] )), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'hasAttach' => $hasAttach, 'received' => $tmp ['f_received'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'] ,'hold'=>$hold ,'senderOrg'=>$tmp['f_sender_org_id'], 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ));
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /personal-forward/ ข้อมูลหนังสือส่งต่อ
	 *
	 */
	public function personalForwardAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
        
        //include_once('DFTransaction.php');
        $dfTrans = new DFTransaction();
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'FW' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['FW'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['FW'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['FW'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['FW'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['FW'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['FW']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['FW'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['FW']['docNo']}%'";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['FW'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['FW']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['FW'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['FW']['docType']}'";
			}

		// Filter To
			if ($_SESSION ['FilterRecord'] ['FW'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['FW']['from']}%'";
			}
			
		// Filter To
			if ($_SESSION ['FilterRecord'] ['FW'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['FW']['to']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['FW'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['FW'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['FW'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['FW'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['FW'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['FW'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['FW'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['FW'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['FW'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		
		
		//$conn->debug  = true; 
		$regBookID = $_GET ['regBookID'];
		
		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
        $sqlCount .= " a.f_sender_uid = {$sessionMgr->getCurrentAccID()}";
		//$sqlCount .= " and a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		//Old
		//$sqlCount .= " and a.f_send_type = 1";
		//New
		//$sqlCount .= " and a.f_send_type = 6";
		$sqlCount .= " and a.f_is_forward_trans = 1";
		$sqlCount .= " and a.f_sendback = 0";
		$sqlCount .= " and a.f_callback = 0";
		//$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_show_in_reg_book = 0";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
        $sqlCount .= " and a.f_send_year = {$sessionMgr->getCurrentYear()}"; 
      
		if ($util->isDFTransFiltered ( 'FW' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_uid = {$sessionMgr->getCurrentAccID()}";
        //$sql .= " and a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}"; 
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		//Old
		//$sql .= " and a.f_send_type = 1";
		//New
		//$sql .= " and a.f_send_type = 6";
		$sql .= " and a.f_is_forward_trans = 1";
		$sql .= " and a.f_sendback = 0";
		$sql .= " and a.f_callback = 0";
		//$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_show_in_reg_book = 0";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
        $sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()}"; 
		$sql .= " and b.f_doc_id = c.f_doc_id ";
        
		if ($util->isDFTransFiltered ( 'FW' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_type asc,a.f_send_reg_no desc,a.f_send_sys_stamp desc";

		if ($util->isDFTransFiltered ( 'FW' )) {
			Logger::debug("case filter");
			//die("case no filter");
			$rs = $conn->Execute ( $sql );
		} else {
			Logger::debug("case no filter");
			//die("case no filter".$sql);
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
				$hold = 0;
			} else {
				$hold = 1;
			}
			
			$receivedInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'sendType' => $tmp ['f_send_type'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ),'from' => UTFEncode ( $dfTrans->getOriginalFrom($tmp ['f_send_trans_main_id'] )), 'from2' => $from, 'to' => $to, 'command' => UTFEncode ( '' ), 'hasAttach' => $hasAttach, 'received' => $tmp ['f_received'], 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce'] ,'hold' => $hold,'senderOrg'=>$tmp['f_sender_org_id'], 'sendStamp' => UTFEncode ( $util->getDateString ( $tmp ['f_send_stamp'] ) ) . ',' . UTFEncode ( $util->getTimeString ( $tmp ['f_send_stamp'] ) ));
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /personal-complete/ ข้อมูลหนังสืองานที่ปิดแล้ว
	 *
	 */
	public function personalCompletedAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		//include_once 'DFTransaction.php';
        //include_once 'Command.Entity.php';
        //include_once 'Account.Entity.php';

		$dfTrans = new DFTransaction ( );
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];

		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'PCM' )) {

			// Filter Receive Receive No
			if ($_SESSION ['FilterRecord'] ['PCM'] ['recvNo'] != '') {
				$filterSQL [] = " b.f_recv_reg_no like '%{$_SESSION['FilterRecord']['PCM']['recvNo']}%'";
			}

			// Filter Receive From
			if ($_SESSION ['FilterRecord'] ['PCM'] ['from'] != '') {
				$filterSQL [] = " b.f_send_fullname like '%{$_SESSION['FilterRecord']['PCM']['from']}%'";
			}

			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['PCM'] ['docNo'] != '') {
				$filterSQL [] = " d.f_doc_no like '%{$_SESSION['FilterRecord']['PCM']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['PCM'] ['title'] != '') {
				$filterSQL [] = " d.f_title like '%{$_SESSION['FilterRecord']['PCM']['title']}%'";
			}
			
		// Filter to
			if ($_SESSION ['FilterRecord'] ['PCM'] ['to'] != '') {
				$filterSQL [] = " b.f_attend_fullname like '%{$_SESSION['FilterRecord']['PCM']['to']}%'";
			}			

			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['PCM'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['PCM'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['PCM'] ['docDateFrom'] );
				$filterSQL [] = " d.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['PCM'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['PCM'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['PCM'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['PCM'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['PCM'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['PCM'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " d.f_doc_realdate <= {$filterDateTo}";
			}								
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}
		

		$regBookID = $_GET ['regBookID'];
		
		$sql = "select a.f_order_id,b.*,c.f_security_modifier as f_security,c.f_urgent_modifier as f_speed,c.f_hold_job";
		$sql .= " ,d.f_title,d.f_doc_no,d.f_doc_date,d.f_doc_id,a.f_assign_uid,a.f_received_uid,a.f_assigned_timestamp,a.f_report_text, a.f_close_timestamp";
		$sql .= " from tbl_order a,tbl_trans_df_recv b,tbl_trans_master_df c,tbl_doc_main d";
		$sql .= " where f_complete = 1";
		$sql .= " and (f_dismiss_assign_complete = 0 or f_dismiss_assign_complete is null)";
		$sql .= " and f_assign_uid = {$sessionMgr->getCurrentAccID()}";
		$sql .= " and (b.f_recv_trans_main_id = a.f_trans_main_id and b.f_recv_trans_main_seq = a.f_trans_main_seq and b.f_recv_id = a.f_trans_id)";
		$sql .= " and (a.f_trans_main_id = c.f_trans_main_id)";
		$sql .= " and (c.f_doc_id = d.f_doc_id)";		
        $sql .= " and b.f_recv_year = {$sessionMgr->getCurrentYear()} ";
		
		if ($util->isDFTransFiltered ('PCM')) {
			$sql .= $realFilter;
		}

		$sql .= " order by b.f_recv_reg_no desc";

		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
            
            $receiver = new AccountEntity();
            $receiver->Load("f_acc_id = '{$tmp['f_received_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            
            
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            } 
    
			if ( $tmp ['f_close_timestamp'] != 0 ) {
				$closeTime = "{$util->getDateString($tmp ['f_close_timestamp'])},{$util->getTimeString($tmp ['f_close_timestamp'])}";
			} else {
				$closeTime = "";
			}			

			$receivedInternal [] = Array ('recvType' => $tmp ['f_recv_type'], 'recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security'], 'speed' => $tmp ['f_speed'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'] ,'hold'=>$hold,'receiver'=>UTFEncode($receiver->f_name.' '.$receiver->f_last_name), 'closeTime'=>UTFEncode($closeTime));
		}
		
		$count = $dfTrans->getCompletedItem ();
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /reserve-book/ ข้อมูลหนังสือจองเลข
	 *
	 */
	public function reserveBookAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		//include_once 'DFTransaction.php';
        //include_once 'Command.Entity.php';
        //include_once 'Account.Entity.php';

		$dfTrans = new DFTransaction ( );
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];

		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'PRB' )) {

			// Filter Receive Receive No
			if ($_SESSION ['FilterRecord'] ['PRB'] ['recvNo'] != '') {
				$filterSQL [] = " b.f_recv_reg_no like '%{$_SESSION['FilterRecord']['PCM']['recvNo']}%'";
			}

			// Filter Receive From
			if ($_SESSION ['FilterRecord'] ['PRB'] ['from'] != '') {
				$filterSQL [] = " b.f_send_fullname like '%{$_SESSION['FilterRecord']['PCM']['from']}%'";
			}

			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['PRB'] ['docNo'] != '') {
				$filterSQL [] = " d.f_doc_no like '%{$_SESSION['FilterRecord']['PCM']['docNo']}%'";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['PRB'] ['title'] != '') {
				$filterSQL [] = " d.f_title like '%{$_SESSION['FilterRecord']['PCM']['title']}%'";
			}
			
		// Filter to
			if ($_SESSION ['FilterRecord'] ['PRB'] ['to'] != '') {
				$filterSQL [] = " b.f_attend_fullname like '%{$_SESSION['FilterRecord']['PCM']['to']}%'";
			}	

			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['PRB'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['PRB'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['PRB'] ['docDateFrom'] );
				$filterSQL [] = " d.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['PRB'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['PRB'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['PRB'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['PRB'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['PRB'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['PRB'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " d.f_doc_realdate <= {$filterDateTo}";
			}								
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}	

		$regBookID = $_GET ['regBookID'];
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c, tbl_reserve_no d where";
		$sql .= " (a.f_governer_approve = 0 or a.f_governer_approve is null) and";
		$sql .= " a. f_send_trans_main_seq = 1 and a.f_send_year = {$sessionMgr->getCurrentYear()} and";
		$sql .= " a.f_send_trans_main_id = b.f_trans_main_id and";
		$sql .= " c.f_gen_ext_bookno = 0 and d.f_used = 0 and";
		$sql .= " b.f_doc_id = c.f_doc_id and c.f_doc_id = d.f_doc_id  {$filterQuery} order by d.f_reserved_book_no desc";

		if ($util->isDFTransFiltered ('PRB')) {
			$sql .= $realFilter;
		}

		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$reserveBook = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
            
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_recv_fullname'] );
			}

            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            } 
 
			$hasReserve = 0;
			$sqlCheckHasReserve = "select count(*) as COUNT_EXP from tbl_reserve_no where f_doc_id = '{$tmp ['f_doc_id']}'";
			$rsCheckHasReserve = $conn->Execute($sqlCheckHasReserve);
			$tmpCheckHasReserve = $rsCheckHasReserve->FetchNextObject();
			if($tmpCheckHasReserve->COUNT_EXP > 0) {
				$hasReserve = 1;
			}			

			$sqlReserveNo = " select * from tbl_reserve_no where f_doc_id = '{$tmp ['f_doc_id']}' ";
			$rsReserveNo = $conn->Execute($sqlReserveNo);
			$tmpReserveNo = $rsReserveNo->FetchRow ();

			$reserver = new AccountEntity();
			$reserver->Load(" f_acc_id = '{$tmpReserveNo['F_ACC_ID']}' ");
	
			$ownerOrg = new OrganizeEntity ( );
			$ownerOrg->Load ( "f_org_id = '{$tmp['f_owner_org_id']}'" );

			$reserveBook [] = Array ('sendType' => $tmp ['f_send_type'], 'sendID' => $tmp ['f_send_trans_main_id'] . '_' . $tmp ['f_send_trans_main_seq'] . '_' . $tmp ['f_send_id'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'] ,'hold' => $hold,'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'hasReserved'=> $hasReserve, 'reserved_id' => $tmpReserveNo['F_RESERVED_BOOK_NO'], 'reserver'=>UTFEncode($reserver->f_name.' '.$reserver->f_last_name),'ownerIntDocCode' => UTFEncode ( $ownerOrg->f_int_code ), 'ownerExtDocCode' => UTFEncode ( $ownerOrg->f_ext_code ));
		}

		$count = $dfTrans->getReserveBookItem ();
		$data = json_encode ( $reserveBook );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}	

	/**
	 * action /personal-committed/ ข้อมูลหนังสือที่ทำเสร็จแล้ว
	 *
	 */
	public function personalCommittedAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		//include_once 'DFTransaction.php';
        //include_once 'Command.Entity.php';
        //include_once 'Account.Entity.php';
		
		$dfTrans = new DFTransaction ( );
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'PCI' )) {

			// Filter Receive Receive No
			if ($_SESSION ['FilterRecord'] ['PCI'] ['recvNo'] != '') {
				$filterSQL [] = " b.f_recv_reg_no like '%{$_SESSION['FilterRecord']['PCI']['recvNo']}%'";
			}

			// Filter Receive From
			if ($_SESSION ['FilterRecord'] ['PCI'] ['from'] != '') {
				$filterSQL [] = " b.f_send_fullname like '%{$_SESSION['FilterRecord']['PCI']['from']}%'";
			}

			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['PCI'] ['docNo'] != '') {
				$filterSQL [] = " d.f_doc_no like '%{$_SESSION['FilterRecord']['PCI']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['PCI'] ['title'] != '') {
				$filterSQL [] = " d.f_title like '%{$_SESSION['FilterRecord']['PCI']['title']}%'";
			}
			
		// Filter to
			if ($_SESSION ['FilterRecord'] ['PCI'] ['to'] != '') {
				$filterSQL [] = " b.f_attend_fullname like '%{$_SESSION['FilterRecord']['PCI']['to']}%'";
			}			

			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['PCI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['PCI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['PCI'] ['docDateFrom'] );
				$filterSQL [] = " d.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['PCI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['PCI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['PCI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['PCI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['PCI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['PCI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " d.f_doc_realdate <= {$filterDateTo}";
			}													
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}

		$regBookID = $_GET ['regBookID'];
		
		$sql = "select a.f_order_id,b.*,c.f_security_modifier as f_security,c.f_urgent_modifier as f_speed,c.f_hold_job";
		$sql .= " ,d.f_title,d.f_doc_no,d.f_doc_date,d.f_doc_id,a.f_assign_uid,a.f_received_uid,a.f_assigned_timestamp,a.f_report_text,a.f_close_timestamp";
		$sql .= " from tbl_order a,tbl_trans_df_recv b,tbl_trans_master_df c,tbl_doc_main d";
		$sql .= " where f_complete = 1";
		$sql .= " and (f_dismiss_recv_complete = 0 or f_dismiss_recv_complete is null)";
		$sql .= " and f_received_uid = {$sessionMgr->getCurrentAccID()}";
		$sql .= " and (b.f_recv_trans_main_id = a.f_trans_main_id and b.f_recv_trans_main_seq = a.f_trans_main_seq and b.f_recv_id = a.f_trans_id)";
		$sql .= " and (a.f_trans_main_id = c.f_trans_main_id)";
		$sql .= " and (c.f_doc_id = d.f_doc_id)";                         
        $sql .= " and b.f_recv_year = {$sessionMgr->getCurrentYear()} ";

		if ($util->isDFTransFiltered ('PCI')) {
			$sql .= $realFilter;
		}												

		$sql .= " order by b.f_recv_reg_no desc";

		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
            $assigner = new AccountEntity();
            $assigner->Load("f_acc_id = '{$tmp['f_assign_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }  
            
			if ( $tmp ['f_close_timestamp'] != 0 ) {
				$closeTime =  "{$util->getDateString($tmp ['f_close_timestamp'])},{$util->getTimeString($tmp ['f_close_timestamp'])}";
			} else {
				$closeTime = "";
			}

			$receivedInternal [] = Array ('recvType' => $tmp ['f_recv_type'], 'recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security'],'speed' => $tmp ['f_speed'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'],'hold'=>$hold,'assigner'=>UTFEncode($assigner->f_name.' '.$assigner->f_last_name), 'closeTime'=>UTFEncode($closeTime));
		}
		$count = $dfTrans->getCommittedItem ();
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /circbook-internal/ ข้อมูลหนังสือเวียนรออ่าน
	 *
	 */
	public function circbookInternalAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;

		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'RC' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RC'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RC'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RC'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['RC'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['RC'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['RC']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}

			// Filter Receive Receive No
			if ($_SESSION ['FilterRecord'] ['RC'] ['recvNo'] != '') {
				$filterSQL [] = " a.f_recv_reg_no like '%{$_SESSION['FilterRecord']['RC']['recvNo']}%'";
			}

			// Filter Receive From
			if ($_SESSION ['FilterRecord'] ['RC'] ['from'] != '') {
				$filterSQL [] = " a.f_send_fullname like '%{$_SESSION['FilterRecord']['RC']['from']}%'";
			}

			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['RC'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['RC']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['RC'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['RC']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['RC'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['RC']['docType']}'";
			}
			
		// Filter to
			if ($_SESSION ['FilterRecord'] ['RC'] ['to'] != '') {
				$filterSQL [] = " a.f_attend_fullname like '%{$_SESSION['FilterRecord']['RC']['to']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['RC'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RC'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RC'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['RC'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['RC'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['RC'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['RC'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['RC'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['RC'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}

		//require_once 'Command.Entity.php';
		
		$regBookID = $_GET ['regBookID'];
		
/*		//OLD Query
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 1";
		// for circBook we should add a filter f_is_circ = 1 & f_governer_approve = 1 too ***
		$countSQL .= " and a.f_is_circ = 1";
		$countSQL .= " and a.f_governer_approve = 1";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
		
		$countSQL = "SELECT  count(*)  as count_exp FROM  tbl_trans_df_recv a RIGHT JOIN tbl_read_circ b ON ";
		$countSQL .= " (b.f_trans_main_id=a.f_recv_trans_main_id and b.f_trans_main_seq=a.f_recv_trans_main_seq and b.f_trans_recv_id=a.f_recv_id)";
		$countSQL .= " WHERE";
		$countSQL .= " a.f_recv_type = 5 and b.f_acc_id={$sessionMgr->getCurrentAccID()} and b.f_dismiss != 1"; */
		$countSQL = "
				SELECT count(*) as count_exp 
				FROM tbl_doc_main c,
					 tbl_trans_master_df d,
					 tbl_trans_df_recv a RIGHT JOIN tbl_read_circ b ON (   b.f_trans_main_id = a.f_recv_trans_main_id  AND 
					                                                       b.f_trans_main_seq = a.f_recv_trans_main_seq AND 
																		   b.f_trans_recv_id = a.f_recv_id ) 
				WHERE a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()} AND
					  a.f_recv_type = 5 AND
					  a.f_recv_year = {$sessionMgr->getCurrentYear()} AND
				      c.F_DOC_ID = d.F_DOC_ID and 
					  d.F_TRANS_MAIN_ID = a.F_RECV_TRANS_MAIN_ID AND 
					  b.f_acc_id = {$sessionMgr->getCurrentAccID()} AND b.f_dismiss != 1 ";
		
		if ($util->isDFTransFiltered ( 'RC' )) {
			$countSQL .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $countSQL );
		/*		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id   ";
		//$sql .= " ,d.f_title,d.f_doc_no,d.f_doc_date,d.f_doc_id";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 1";
		// for circBook we should add a filter f_is_circ = 1 & f_governer_approve = 1 too ***
		$sql .= " and a.f_is_circ = 1";
		$sql .= " and a.f_governer_approve = 1";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		*/
		/*
		$sql = "SELECT  a.*,c.f_security_modifier,c.f_urgent_modifier,c.f_hold_job ";
		$sql .= " ,d.f_title,d.f_doc_no,d.f_doc_date,d.f_doc_id";
		$sql .= " ,d.f_gen_int_bookno,d.f_gen_ext_bookno,d.f_gen_ext_type";
		$sql .= " ,d.f_request_order,d.f_request_command,d.f_request_announce,b.f_read_stamp";
		//$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " FROM  tbl_trans_df_recv a RIGHT JOIN tbl_read_circ b ON ";
		$sql .= " (b.f_trans_main_id=a.f_recv_trans_main_id and b.f_trans_main_seq=a.f_recv_trans_main_seq and b.f_trans_recv_id=a.f_recv_id),tbl_trans_master_df c,tbl_doc_main d ";
		$sql .= " WHERE";
		$sql .= " a.f_recv_type = 5 and b.f_acc_id={$sessionMgr->getCurrentAccID()} and b.f_dismiss != 1";
		$sql .= " and a.f_recv_trans_main_id = c.f_trans_main_id";
		$sql .= " and c.f_doc_id = d.f_doc_id "; 
		*/

		/* Formatted on 2010/05/19 09:39 (Formatter Plus v4.8.6) */
		$sql = "
			SELECT a.*,d.f_security_modifier,d.f_urgent_modifier,d.f_hold_job,d.f_owner_org_id,d.f_abort,
			       c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type,
				   c.f_request_order,c.f_request_command,c.f_request_announce,b.f_read_stamp
			FROM tbl_doc_main c, 
			     tbl_trans_master_df d, 
				 tbl_trans_df_recv a RIGHT JOIN tbl_read_circ b ON (b.f_trans_main_id = a.f_recv_trans_main_id AND 
																    b.f_trans_main_seq = a.f_recv_trans_main_seq  AND 
																    b.f_trans_recv_id = a.f_recv_id) 
			WHERE a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()} AND
			      a.f_recv_type = 5 AND
				  a.f_recv_year = {$sessionMgr->getCurrentYear()} AND
			      c.f_doc_id = d.f_doc_id AND 
				  d.f_trans_main_id = a.f_recv_trans_main_id AND 
				  b.f_acc_id = {$sessionMgr->getCurrentAccID()} AND b.f_dismiss != 1 ";
		
		if ($util->isDFTransFiltered ( 'RC' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";

		$rs = $conn->SelectLimit ( $sql, $limit, $start );

		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			$command = new CommandEntity ( );
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
			
			if ($tmp ['f_is_circ'] != 1 || is_null ( $tmp ['f_is_circ'] )) {
				$isCirc = 0;
			} else {
				$isCirc = 1;
			}
			$receivedInternal [] = Array ('readstamp'=>$tmp['f_read_stamp'],'isCirc' => $isCirc, 'recvID' => trim ( $tmp ['f_recv_trans_main_id'] ) . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . trim ( $tmp ['f_recv_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hold' => $tmp ['f_hold_job'], 'hasAttach' => $hasAttach, 'governerApprove' => $tmp ['f_governer_approve'] , 'genIntBookno' => $tmp ['f_gen_int_bookno'], 'genExtBookno' => $tmp ['f_gen_ext_bookno'], 'genExtType' => $tmp ['f_gen_ext_type'], 'requestOrder' => $tmp ['f_request_order'], 'requestCommand' => $tmp ['f_request_command'], 'requestAnnounce' => $tmp ['f_request_announce']);
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		//var_dump($receivedInternal);
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
    
	/**
	 * action /orders/ ข้อมูลคำสั่ง/ประกาศ
	 *
	 */
    public function ordersAction() {
        global $conn;
        global $config;
        global $sessionMgr;
        global $util;

		//include_once 'DFTransaction.php';
        //include_once 'AnnounceCategory.Entity.php';
        //include_once 'Account.Entity.php';
        //include_once 'Role.Entity.php';
        
		//get Default Year 
		$defaultYear = $sessionMgr->getCurrentYear();
		// fix add 
		if ($config ['datemode'] == 'B') {
			$defaultYear += 543;
		}
		
        $start = $_GET ['start'];
        $limit = $_GET ['limit'];
        $sort = $_GET ['sort'];
        $sortDir = $_GET ['dir'];
        
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ('ORD')) {
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['ORD'] ['docNo'] != '') {
				$filterSQL [] = " a.f_announce_no like '%{$_SESSION['FilterRecord']['ORD']['docNo']}%'";
			}

			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['ORD'] ['docYear'] != '') {
				$filterSQL [] = " a.f_year like '%{$_SESSION['FilterRecord']['ORD']['docYear']}%'";
			}

			$util = new ECMUtility();
			$datefrom = $util->dateToStamp($_SESSION ['FilterRecord'] ['ORD'] ['docDateFrom']);
			$dateto = $util->dateToStamp($_SESSION ['FilterRecord'] ['ORD'] ['docDateTo']);
			
			// Filter Receive Doc.Date From
			if ($datefrom != '') {
				$filterSQL [] = " a.f_announce_stamp >= {$datefrom} ";
			}

			// Filter Receive Doc.Date To	
			if ($dateto != ''){
				$filterSQL [] = " a.f_announce_stamp <=  {$dateto} ";
			}

			// Filter Receive Name
			if ($_SESSION ['FilterRecord'] ['ORD'] ['name'] != '') {
				$filterSQL [] = " b.f_name like '%{$_SESSION['FilterRecord']['ORD']['name']}%'";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['ORD'] ['title'] != '') {
				$filterSQL [] = " a.f_title like '%{$_SESSION['FilterRecord']['ORD']['title']}%'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['ORD'] ['org'] != '') {
				$filterSQL [] = " a.f_announce_org_name like '%{$_SESSION['FilterRecord']['ORD']['org']}%'";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}

//        $regBookID = $_GET ['regBookID'];
        $dfTrans = new DFTransaction ( );
        $sqlCount = "select count(*) count_exp";
        $sqlCount .= " from tbl_announce a join tbl_announce_category b on a.f_announce_category = b.f_announce_cat_id ";
		$sqlCount .= " where  a.f_year = {$defaultYear} "; 

		if ($util->isDFTransFiltered ('ORD')) {
			$sqlCount .= $realFilter;
		}

        $rsCount = $conn->Execute ( $sqlCount );
        $tmpCount = $rsCount->FetchNextObject ();
        
        $sql = "select a.*,b.f_announce_cat_id,b.f_name  ";
        $sql .= " from tbl_announce a join tbl_announce_category b on a.f_announce_category = b.f_announce_cat_id ";
		$sql .= " where  a.f_year = {$defaultYear} ";   
		
		if ($util->isDFTransFiltered ('ORD')) {
			$sql .= $realFilter;
		}

		$sql .= " order by a.f_announce_type asc,a.f_announce_category asc,a.f_announce_no desc"; 

        $rs = $conn->SelectLimit ( $sql, $limit, $start );
		
//		$fp = fopen ( 'd:\testSQLFilter.txt', 'a+' );
//		fwrite ( $fp, $sql."\r\n".$sqlCount."\r\n" );
//		fclose ( $fp );		
		
        $receivedInternal = Array ( );
        while ( $tmp = $rs->FetchRow () ) {
            checkKeyCase ( $tmp );
            
            
            switch($tmp['f_announce_type']) {
                case 0:
                    $type = 'คำสั่ง';
                    break;
                case 1:
                    $type = 'ระเบียบ';
                    break;
                case 2:
                    $type = 'ประกาศ';
                    break;
                case 3:
                    $type = 'ข้อบังคับ';
                    break;
                case 4:
                    $type = 'อื่นๆ';
                    break;
            }
            
            $signuser = new AccountEntity();
            $signuser->Load("f_acc_id = '{$tmp['f_sign_uid']}'");    
            
            $role = new RoleEntity();
            $role->Load("f_role_id = '{$tmp['f_sign_role']}'");    
            
            
            $announceCat = new AnnounceCategoryEntity();
            $announceCat->Load("f_announce_cat_id = '{$tmp['f_announce_category']}' and f_announce_type = '{$tmp['f_announce_type']}'");
            
            if ($util->docUtil->hasAnnounceAttach($tmp ['f_announce_id'] )) {
                $hasAttach = 1;
            } else {
                $hasAttach = 0;
            }
            
            //
            $receivedInternal [] = Array (
            'id'=>$tmp['f_announce_id'],
            'docno'=>$tmp['f_announce_no'].'/'.$tmp['f_year'],
            'typename'=>UTFEncode($type), 
//            'catname'=>UTFEncode( $announceCat->f_name), 
            'catname'=>UTFEncode($tmp['f_name']), 
            'title'=>UTFEncode(stripslashes($tmp['f_title'])),
            'date'=>UTFEncode($tmp['f_announce_date']),
            'signuser'=>UTFEncode($signuser->f_name." ".$signuser->f_last_name),
            'signrole'=>UTFEncode($role->f_role_name),
            'remark'=>UTFEncode($tmp['f_remark']),
            'orgName'=>UTFEncode($tmp['f_announce_org_name']),
            'hasAttach'=>$hasAttach
			,'catno'=>UTFEncode($announceCat->f_announce_cat_id.' '.$announceCat->f_name)
            );
            //'orderID' => $tmp ['f_order_id'], 'recvType' => $tmp ['f_recv_type'], 'recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security'], 'speed' => $tmp ['f_speed'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( $tmp ['f_title'] ), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach ,'hold'=>$hold,'receiver'=>UTFEncode($receiver->f_name.' '.$receiver->f_last_name));
        }
        //echo $sql;
        //$count = $dfTrans->getOrderAssignedCount ();
		
//		$fp = fopen ( 'd:\testSQLFilter11.txt', 'a+' );
//		fwrite ( $fp, $sql."\r\n".print_r( $receivedInternal,true)."\r\n" );
//		fclose ( $fp );				
        $count = $tmpCount->COUNT_EXP;
        $data = json_encode ( $receivedInternal );
        $cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
        print $cb . '({"total":"' . $count . '","results":' . $data . '})';
    }
	
    /**
     * action /order-assigned/ ข้อมูลหนังสือที่มอบหมายไป
     *
     */
	public function orderAssignedAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		//include_once 'DFTransaction.php';
        //include_once 'Command.Entity.php';
        //include_once 'Account.Entity.php';
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'OAI' )) {

			// Filter Receive Receive No
			if ($_SESSION ['FilterRecord'] ['OAI'] ['recvNo'] != '') {
				$filterSQL [] = " b.f_recv_reg_no like '%{$_SESSION['FilterRecord']['OAI']['recvNo']}%'";
			}

			// Filter Receive From
			if ($_SESSION ['FilterRecord'] ['OAI'] ['from'] != '') {
				$filterSQL [] = " b.f_send_fullname like '%{$_SESSION['FilterRecord']['OAI']['from']}%'";
			}

			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['OAI'] ['docNo'] != '') {
				$filterSQL [] = " d.f_doc_no like '%{$_SESSION['FilterRecord']['OAI']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['OAI'] ['title'] != '') {
				$filterSQL [] = " d.f_title like '%{$_SESSION['FilterRecord']['OAI']['title']}%'";
			}
			
		// Filter to
			if ($_SESSION ['FilterRecord'] ['OAI'] ['to'] != '') {
				$filterSQL [] = " b.f_attend_fullname like '%{$_SESSION['FilterRecord']['OAI']['to']}%'";
			}			
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['OAI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['OAI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['OAI'] ['docDateFrom'] );
				$filterSQL [] = " d.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['OAI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['OAI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['OAI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['OAI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['OAI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['OAI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " d.f_doc_realdate <= {$filterDateTo}";
			}							
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}

		$regBookID = $_GET ['regBookID'];
		$dfTrans = new DFTransaction ( );
		
		$sqlCount = "SELECT  count(*) as count_exp ";
		$sqlCount .= " FROM tbl_order a, tbl_trans_df_recv b, tbl_trans_master_df c,tbl_doc_main d ";
		$sqlCount .= " WHERE (a.f_assign_uid = '{$sessionMgr->getCurrentAccID()}') AND ";
		//$sqlCount .= "and (a.f_org_id = '{$sessionMgr->getCurrentOrgID()}') ";
		$sqlCount .= " a.f_trans_main_id = c.f_trans_main_id and";
		$sqlCount .= " c.f_doc_id = d.f_doc_id ";
		$sqlCount .= " and a.f_trans_main_id = b.f_recv_trans_main_id ";
		$sqlCount .= " and a.f_trans_main_seq = b.f_recv_trans_main_seq ";
		$sqlCount .= " and a.f_trans_id = b.f_recv_id ";
		$sqlCount .= " and (a.f_complete = 0 or a.f_complete is null)";
        $sqlCount .= " and b.f_recv_year = {$sessionMgr->getCurrentYear()} ";

		if ($util->isDFTransFiltered ('OAI')) {
			$sqlCount .= $realFilter;
		}

//		echo "alert('$sqlCount');";
		
		$rsCount = $conn->Execute ( $sqlCount );
		$tmpCount = $rsCount->FetchNextObject ();
		
		$sql = "SELECT  a.* ,b.*,c.f_security_modifier as f_security,c.f_urgent_modifier as f_speed,c.f_hold_job,d.* ";
		//$sql .= " ,b.f_recv_reg_no,b.f_recv_type,b.f_recv_trans_main_id,b.f_recv_trans_main_seq,b.f_recv_id";
		$sql .= " FROM tbl_order a, tbl_trans_df_recv b, tbl_trans_master_df c,tbl_doc_main d";
		//$sql .= " FROM tbl_order a, tbl_trans_master_df c,tbl_doc_main d";
		$sql .= " WHERE   (a.f_assign_uid = '{$sessionMgr->getCurrentAccID()}') AND  ";
		//$sql .= " (a.f_org_id = '{$sessionMgr->getCurrentOrgID()}') and  ";
		$sql .= " a.f_trans_main_id = c.f_trans_main_id and";
		$sql .= " c.f_doc_id = d.f_doc_id ";
		$sql .= " and a.f_trans_main_id = b.f_recv_trans_main_id";
		$sql .= " and a.f_trans_main_seq = b.f_recv_trans_main_seq";
		$sql .= " and a.f_trans_id = b.f_recv_id";
		$sql .= " and (a.f_complete = 0 or a.f_complete is null)";
        $sql .= " and b.f_recv_year = {$sessionMgr->getCurrentYear()} ";

		if ($util->isDFTransFiltered ('OAI')) {
			$sql .= $realFilter;
		}

		$sql .= " order by b.f_recv_reg_no desc";
		
		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
            
            $receiver = new AccountEntity();
            $receiver->Load("f_acc_id = '{$tmp['f_received_uid']}'");
            
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }   
			
            $receivedInternal [] = Array ('orderID' => $tmp ['f_order_id'], 'recvType' => $tmp ['f_recv_type'], 'recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security'], 'speed' => $tmp ['f_speed'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach ,'hold'=>$hold,'receiver'=>UTFEncode($receiver->f_name.' '.$receiver->f_last_name));
		}
		//echo $sql;
		//$count = $dfTrans->getOrderAssignedCount ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /order-received/ ข้อมูลหนังสือที่ได้รับมอบหมาย
	 *
	 */
	public function orderReceivedAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		//include_once 'DFTransaction.php';
        //include_once 'Command.Entity.php';
        //include_once 'Account.Entity.php';
        
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'ORI' )) {

			// Filter Receive Receive No
			if ($_SESSION ['FilterRecord'] ['ORI'] ['recvNo'] != '') {
				$filterSQL [] = " b.f_recv_reg_no like '%{$_SESSION['FilterRecord']['ORI']['recvNo']}%'";
			}

			// Filter Receive From
			if ($_SESSION ['FilterRecord'] ['ORI'] ['from'] != '') {
				$filterSQL [] = " b.f_send_fullname like '%{$_SESSION['FilterRecord']['ORI']['from']}%'";
			}

			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['ORI'] ['docNo'] != '') {
				$filterSQL [] = " d.f_doc_no like '%{$_SESSION['FilterRecord']['ORI']['docNo']}%'";
			}
			
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['ORI'] ['title'] != '') {
				$filterSQL [] = " d.f_title like '%{$_SESSION['FilterRecord']['ORI']['title']}%'";
			}
			
		// Filter to
			if ($_SESSION ['FilterRecord'] ['ORI'] ['to'] != '') {
				$filterSQL [] = " b.f_attend_fullname like '%{$_SESSION['FilterRecord']['ORI']['to']}%'";
			}			

			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['ORI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['ORI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['ORI'] ['docDateFrom'] );
				$filterSQL [] = " d.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['ORI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['ORI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['ORI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['ORI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['ORI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['ORI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " d.f_doc_realdate <= {$filterDateTo}";
			}										
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}

		$regBookID = $_GET ['regBookID'];
		$dfTrans = new DFTransaction ( );
		
		$sql = "SELECT  a.* ,b.*,c.f_security_modifier as f_security,c.f_urgent_modifier as f_speed,c.f_hold_job,d.* ";
		//$sql .= " ,b.f_recv_reg_no,b.f_recv_type,b.f_recv_trans_main_id,b.f_recv_trans_main_seq,b.f_recv_id";
		$sql .= " FROM tbl_order a, tbl_trans_df_recv b, tbl_trans_master_df c,tbl_doc_main d";
		$sql .= " WHERE   (a.f_received_uid = '{$sessionMgr->getCurrentAccID()}') AND  ";
		//$sql .= " (a.f_org_id = '{$sessionMgr->getCurrentOrgID()}') and  ";
		//$sql .= " (a.f_trans_main_id = b.f_recv_trans_main_id) and ";
		$sql .= " a.f_trans_main_id = c.f_trans_main_id and";
		$sql .= " c.f_doc_id = d.f_doc_id";
		$sql .= " and a.f_trans_main_id = b.f_recv_trans_main_id";
		$sql .= " and a.f_trans_main_seq = b.f_recv_trans_main_seq";
		$sql .= " and a.f_trans_id = b.f_recv_id";
		$sql .= " and (a.f_complete = 0 or a.f_complete is null)";    
        $sql .= " and b.f_recv_year = {$sessionMgr->getCurrentYear()} ";
        
		if ($util->isDFTransFiltered ( 'ORI' )) {
			$sql .= $realFilter;
		}

		$sql .= " order by b.f_recv_reg_no desc";

		//echo $sql."\r\n";
		$rs = $conn->SelectLimit ( $sql, $limit, $start );
		$receivedInternal = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
            
            $assigner = new AccountEntity();
            $assigner->Load("f_acc_id = '{$tmp['f_assign_uid']}'");
            $command = new CommandEntity ( );
            if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
                $commandText = "";
            } else {
                $commandText = $command->f_command_text;
            }
            
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			
			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }    
            
			$receivedInternal [] = Array ('orderID' => $tmp ['f_order_id'], 'recvType' => $tmp ['f_recv_type'], 'recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security'], 'speed' => $tmp ['f_speed'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 'from' => $from, 'to' => $to, 'command' => UTFEncode ( $commandText ), 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hasAttach' => $hasAttach ,'hold'=>$hold,'assigner'=>UTFEncode($assigner->f_name.' '.$assigner->f_last_name));
		}
		$count = $dfTrans->getOrderReceivedCount ();
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /track-item/ ข้อมูลหนังสือติดตาม
	 *
	 */
	public function trackItemAction() {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		$start = $_GET ['start'];
		$limit = $_GET ['limit'];
		$sort = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		//require_once 'Command.Entity.php';
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( 'TKI' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['TKI'] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['TKI'] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['TKI'] ['sendNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $startR, $stopR ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
						} else {
							if ($tmpFilterSubSQL == "") {
								$tmpFilterSubSQL .= " a.f_send_reg_no = '$regNo' ";
							} else {
								$tmpFilterSubSQL .= " or a.f_send_reg_no = '$regNo' ";
							}
						}
					}
					$tmpFilterSQL .= "{$tmpFilterSubSQL}) ";
					$filterSQL [] = $tmpFilterSQL;
				} else {
					if (ereg ( "-", $_SESSION ['FilterRecord'] ['TKI'] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] ['TKI'] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord']['TKI']['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] ['TKI'] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord']['TKI']['docNo']}%'";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['TKI'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['TKI']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['TKI'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['TKI']['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] ['TKI'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['TKI']['to']}%'";
			}
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['TKI'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['TKI'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['TKI'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
			if (($_SESSION ['FilterRecord'] ['TKI'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['TKI'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['TKI'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['TKI'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['TKI'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['TKI'] ['docDateTo'] ) + 86399;				
				$filterSQL [] = " c.f_doc_realdate <= {$filterDateTo}";
			}
		}
		
		if (count ( $filterSQL ) > 0) {
			if (count ( $filterSQL ) > 1) {
				//$realFilter = "";
				$tmpRealFilter = "";
				foreach ( $filterSQL as $filterPiece ) {
					if ($tmpRealFilter == "") {
						$tmpRealFilter = "({$filterPiece})";
					} else {
						$tmpRealFilter .= " and ({$filterPiece})";
					}
				}
				$realFilter = " and ({$tmpRealFilter})";
			} else {
				$realFilter = " and {$filterSQL[0]}";
			}
		} else {
			$realFilter = "";
		}

		$countSQL = "select count(b.f_trans_main_id) as COUNT_EXP from tbl_trans_df_track a,tbl_trans_master_df b,tbl_doc_main c";
		$countSQL .= " where a.f_trans_main_id = b.f_trans_main_id ";
		$countSQL .= " and a.f_acc_id = {$sessionMgr->getCurrentAccID()}";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";

		if ($util->isDFTransFiltered ( 'TKI' )) {
			$sqlCount .= $realFilter;
		}

		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select b.*";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_track a,tbl_trans_master_df b,tbl_doc_main c";
		$sql .= " where a.f_trans_main_id = b.f_trans_main_id ";
		$sql .= " and a.f_acc_id = {$sessionMgr->getCurrentAccID()}";
		$sql .= " and b.f_doc_id = c.f_doc_id ";

//		echo "alert('$sql');";

		if ($util->isDFTransFiltered ( 'TKI' )) {
			$sql .= $realFilter;
		}

		//echo $sql;
		$rs = $conn->Execute ( $sql );
		$trackItemArray = Array ( );
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			
			/**
			แก้ไขปิด notice 
			$command = new CommandEntity ( );
			
			if (! $command->Load ( "f_trans_main_id= '{$tmp['f_recv_trans_main_id']}' and f_trans_main_seq = '{$tmp['f_recv_trans_main_seq']}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			**/
			

			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = UTFEncode ( $tmp ['f_doc_no'] );
			}
			
			/**
			แำก้ไขปิด Notice
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = UTFEncode ( $tmp ['f_send_fullname'] );
			}
			*/
			
			/**
			แก้ไขปิด notice
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = UTFEncode ( $tmp ['f_attend_fullname'] );
			}
			
			**/

			if ($util->docUtil->hasAttach ( $tmp ['f_doc_id'] )) {
				$hasAttach = 1;
			} else {
				$hasAttach = 0;
			}
            
            if ($tmp ['f_hold_job'] != 1 || is_null ( $tmp ['f_hold_job'] )) {
                $hold = 0;
            } else {
                $hold = 1;
            }
            
			//var_dump($tmp);
			$trackItemArray [] = Array (
				'transMainID' => trim ( $tmp ['f_trans_main_id'] ) . '_1_1', 
				'secret' => $tmp ['f_security_modifier'], 
				'speed' => $tmp ['f_urgent_modifier'], 
				/*'recvNo' => $tmp ['f_recv_reg_no'], */
				'docNo' => $docNo, 
				'docID' => $tmp ['f_doc_id'], 
				'title' => UTFEncode ( stripslashes($tmp ['f_title'] )), 
				'docDate' => UTFEncode ( $tmp ['f_doc_date'] ), 
				/*'from' => $from, 
				'to' => $to, 
				'command' => UTFEncode ( $commandText ), 
				'read' => $tmp ['f_read'], 
				'forward' => $tmp ['f_forwarded'], 
				'commit' => $tmp ['f_commit'], 
				'sendback' => $tmp ['f_sendback'], 
				'cancel' => $tmp ['f_cancel'], 
				'egif' => $tmp ['f_is_egif_trans'], */
				'hold' => $tmp ['f_hold_job'], 
				'hasAttach' => $hasAttach, 
				/*'governerApprove' => $tmp ['f_governer_approve'] ,*/
				'hold'=>$hold
			);
		}
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		//var_dump($receivedInternal);
		$data = json_encode ( $trackItemArray );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /reserve-no/ ข้อมูลเลขที่จองไว้
	 *
	 */
	public function reserveNoAction() {
		global $conn;
		global $sessionMgr;
		
		$sql = "select * from tbl_reserve_no where  f_reserved_type = 0 and f_used = 0 and f_org_id= '{$sessionMgr->getCurrentOrgID()}'";
		//$conn->debug = true;
		$rs=$conn->Execute($sql);
		
		//Logger::debug($sql);
		$data=Array();
		while($tmp = $rs->FetchNextObject()) {
			$acc = new AccountEntity();
			$acc->Load("f_acc_id = {$tmp->F_ACC_ID}");
			$data[] = array(
			'id'=>$tmp->F_RESERVED_ID
			, 'bookno'=>$tmp->F_RESERVED_BOOK_NO
			,'regno'=>$tmp->F_RESERVED_REG_NO
			,'reserveTxt'=>UTFEncode('เลขที่: '.$tmp->F_RESERVED_BOOK_NO.", เลขทะเบียน: ".$tmp->F_RESERVED_REG_NO)
			,'info'=>UTFEncode($acc->f_name." ".$acc->f_last_name)
			,'remark'=>'yyyy'
			);
			unset($acc);
		}
		$count = count($data);
		//var_dump($receivedInternal);
		$dataJSON = json_encode ( $data );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $dataJSON . '})';
	}
	
	/**
	 * action /reserve-global-no/ ข้อมูลเลขจองทะเบียนกลาง
	 *
	 */
	public function reserveGlobalNoAction() {
		global $conn;
		//global $sessionMgr;
		
		//$sql = "select * from tbl_reserve_no where  f_reserved_type = 1 and f_used = 0  and f_org_id= '{$sessionMgr->getCurrentOrgID()}'";
		$sql = "select * from tbl_reserve_no where  f_reserved_type = 1 and f_used = 0";
		//$conn->debug = true;
		$rs=$conn->Execute($sql);
		
		$data=Array();
		while($tmp = $rs->FetchNextObject()) {
			$acc = new AccountEntity();
			$acc->Load("f_acc_id = {$tmp->F_ACC_ID}");
			$data[] = array(
			'id'=>$tmp->F_RESERVED_ID
			, 'bookno'=>$tmp->F_RESERVED_BOOK_NO
			,'regno'=>$tmp->F_RESERVED_REG_NO
			,'reserveTxt'=>UTFEncode('เลขที่: '.$tmp->F_RESERVED_BOOK_NO.", เลขทะเบียน: ".$tmp->F_RESERVED_REG_NO)
			,'info'=>UTFEncode($acc->f_name." ".$acc->f_last_name)
			,'remark'=>'yyyy'
			);
			unset($acc);
		}
		$count = count($data);
		//var_dump($receivedInternal);
		$dataJSON = json_encode ( $data );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $dataJSON . '})';
	}

}
