<?php
/**
 * �������§ҹ�ҹ��ú�ó
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 *
 */
class DFReportController extends ECMController {
	
	/**
	 * action /set-param/ ��˹� report parameter
	 *
	 */
	public function setParamsAction() {
	}
	
	/**
	 * action /receive-circ/ ��§ҹ����¹�Ѻ���¹
	 *
	 */
	public function receiveCircAction() {
		global $config;
		global $sessionMgr;
		global $util;
		global $lang;
		
		//include_once 'Organize.Entity.php';
		//require_once 'Command.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ����Ѻ���¹", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 8 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		$format2->set_bold ( 1 );
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( '�B5', '�ѹ/����ŧ�Ѻ' . $headtable, $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;        
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		$data = $this->receivedCircData ( $regBookID, $start, $limit );
		//var_dump($data);
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			
			$command = new CommandEntity ( );
			//var_dump($data[$j]);
			//die($data[$j]['f_recv_trans_main_id']);
			list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $data [$j] ['recvID'] );
			if (! $command->Load ( "f_trans_main_id= '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A{$i}", $data [$j] ['recvNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['receiveStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['receiveStamp'] ), $format3 );
			$worksheet->write ( "C{$i}", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D{$i}", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E{$i}", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F{$i}", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G{$i}", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H{$i}", $commandText, $format3 );
			$worksheet->write ( "I{$i}", "..........  ..........", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "receivedCirc.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	
	}
	
	/**
	 * action /receive-internal/ ��§ҹ����¹�Ѻ����
	 *
	 */
	public function receiveInternalAction() {
		global $config;
		global $sessionMgr;
		global $util;
		global $lang;
		
		//include_once 'Organize.Entity.php';
		//require_once 'Command.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ����Ѻ����", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 8 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		$format2->set_bold ( 1 );
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( '�B5', '�ѹ/����ŧ�Ѻ' . $headtable, $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;		
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		$data = $this->receivedInternalData ( $regBookID, $start, $limit );
		//var_dump($data);
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			
			$command = new CommandEntity ( );
			//var_dump($data[$j]);
			//die($data[$j]['f_recv_trans_main_id']);
			list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $data [$j] ['recvID'] );
			if (! $command->Load ( "f_trans_main_id= '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A{$i}", $data [$j] ['recvNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['receiveStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['receiveStamp'] ), $format3 );
			$worksheet->write ( "C{$i}", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D{$i}", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E{$i}", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F{$i}", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G{$i}", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H{$i}", $commandText, $format3 );
			$worksheet->write ( "I{$i}", "..........  ..........", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "receivedInternal.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	
	}
	
	/**
	 * ��������§ҹ����¹�Ѻ����
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function receivedInternalData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		$filterSQL = Array ();
		if ($util->isDFTransFiltered ( 'RI' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RI'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RI'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RI'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $start, $stop ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $start and a.f_recv_reg_no <= $stop)";
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
						list ( $start, $stop ) = split ( "-", $_SESSION ['FilterRecord'] ['RI'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $start and a.f_recv_reg_no <= $stop)";
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['RI'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['RI']['docDate']}%'";
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
		//$fp = fopen ( 'd:\SQLFilter.txt', 'a+' );
		//fwrite ( $fp, $realFilter );
		//fclose ( $fp );
		

		//$regBookID = $_GET ['regBookID'];
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 1";
		$countSQL .= " and a.f_recv_year = '{$sessionMgr->getCurrentYear()}'";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
		if ($util->isDFTransFiltered ( 'RI' )) {
			$countSQL .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 1";
		$sql .= " and a.f_recv_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		if ($util->isDFTransFiltered ( 'RI' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";
		
		if ($util->isDFTransFiltered ( 'RI' )) {
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		
		$receivedInternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			//var_dump($tmp);
			$receivedInternal [] = Array ('recvID' => $tmp ['f_recv_trans_main_id'] . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hold' => $tmp ['f_hold_job'], 'receiveStamp' => $tmp ['f_recv_stamp'] );
		}
		//$tmpCount = $rsCount->FetchNextObject ();
		//$count = $tmpCount->COUNT_EXP;
		//var_dump($receivedInternal);
		//$data = json_encode ( $receivedInternal );
		//$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		//print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $receivedInternal;
	}
	
	/**
	 * ��������§ҹ����¹�Ѻ���¹
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function receivedCircData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		//global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		$filterSQL = Array ();
		if ($util->isDFTransFiltered ( 'RC' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RC'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RC'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RC'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $start, $stop ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $start and a.f_recv_reg_no <= $stop)";
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
						list ( $start, $stop ) = split ( "-", $_SESSION ['FilterRecord'] ['RC'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $start and a.f_recv_reg_no <= $stop)";
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['RC'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['RC']['docDate']}%'";
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
				if ($_SESSION ['FilterRecord'] ['RC'] ['fromDate'] == $_SESSION ['FilterRecord'] ['RC'] ['toDate']) {
					$tmpToDate = $_SESSION ['FilterRecord'] ['RC'] ['toDate'] + 86400;
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
						$tmpRealFilter .= " or ({$filterPiece})";
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
		

		//$regBookID = $_GET ['regBookID'];
		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 5";
		$countSQL .= " and a.f_recv_year = '{$sessionMgr->getCurrentYear()}'";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
		if ($util->isDFTransFiltered ( 'RC' )) {
			$countSQL .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 5";
		$sql .= " and a.f_recv_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
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
		
		$receivedInternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			//var_dump($tmp);
			$receivedInternal [] = Array ('recvID' => $tmp ['f_recv_trans_main_id'] . '_' . trim ( $tmp ['f_recv_trans_main_seq'] ) . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'hold' => $tmp ['f_hold_job'], 'receiveStamp' => $tmp ['f_recv_stamp'] );
		}
		//$tmpCount = $rsCount->FetchNextObject ();
		//$count = $tmpCount->COUNT_EXP;
		//var_dump($receivedInternal);
		//$data = json_encode ( $receivedInternal );
		//$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		//print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $receivedInternal;
	}
	
	/**
	 * action /receive-external/ ��§ҹ����¹�Ѻ��¹͡
	 *
	 */
	public function receiveExternalAction() {
		global $config;
		global $sessionMgr;
		global $util;
		
		//include_once 'Organize.Entity.php';
		//require_once 'Command.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ����Ѻ��¹͡", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� :$currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		

		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����ŧ�Ѻ', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;		
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		//echo $regBookID;
		$data = $this->receivedExternalData ( $regBookID, $start, $limit );
		//var_dump($data);
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			$command = new CommandEntity ( );
			//var_dump($data[$j]);
			//die($data[$j]['f_recv_trans_main_id']);
			list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $data [$j] ['recvID'] );
			if (! $command->Load ( "f_trans_main_id= '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['recvNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B$i", $util->getDateString ( $data [$j] ['receiveStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['receiveStamp'] ), $format3 );
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write_string ( "G$i", "{$data [$j] ['title']}", $format3 );
			$worksheet->write ( "H$i", $commandText, $format3 );
			$worksheet->write ( "I$i", "........... ...........", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "receivedExternal.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	
	}
	
	/**
	 * ��������§ҹ����¹�Ѻ��¹͡
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function receivedExternalData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		#$conn->debug = true;
		$filterSQL = Array ();
		if ($util->isDFTransFiltered ( 'RE' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RE'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RE'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RE'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $start, $stop ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $start and a.f_recv_reg_no <= $stop)";
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
						list ( $start, $stop ) = split ( "-", $_SESSION ['FilterRecord'] ['RE'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $start and a.f_recv_reg_no <= $stop)";
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['RE'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['RE']['docDate']}%'";
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
		
		//$regBookID = $_GET ['regBookID'];
		

		$countSQL = "select count(*) as count_exp";
		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
		$countSQL .= " and a.f_recv_type = 2";
		$countSQL .= " and a.f_recv_year = '{$sessionMgr->getCurrentYear()}'";
		$countSQL .= " and a.f_show_in_reg_book = 1";
		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
		if ($util->isDFTransFiltered ( 'RE' )) {
			$countSQL .= $realFilter;
		}
		$rsCount = $conn->Execute ( $countSQL );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 2";
		$sql .= " and a.f_recv_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id";
		if ($util->isDFTransFiltered ( 'RE' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";
		
		//$rsCount = $conn->Execute ( $countSQL );
		

		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		

		if ($util->isDFTransFiltered ( 'RE' )) {
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		
		$receivedExternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			$receivedExternal [] = Array ('recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'receiveStamp' => $tmp ['f_recv_stamp'] );
		}
		//$tmpCount = $rsCount->FetchNextObject ();
		//$count = $tmpCount->COUNT_EXP;
		//$data = json_encode ( $receivedExternal );
		//$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		//print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $receivedExternal;
	}
	
	/**
	 * action /receive-external-global/ ��§ҹ����¹�Ѻ��¹͡����¹��ҧ
	 *
	 */
	public function receiveExternalGlobalAction() {
		global $config;
		global $sessionMgr;
		global $util;
		
		//include_once 'Organize.Entity.php';
		//require_once 'Command.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ����Ѻ��¹͡(��ǹ��ҧ)", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� :$currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		

		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����ŧ�Ѻ', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;		
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		//echo $regBookID;
		$data = $this->receivedExternalGlobalData ( $regBookID, $start, $limit );
		//var_dump($data);
		//die();
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			$command = new CommandEntity ( );
			//var_dump($data[$j]);
			//die($data[$j]['f_recv_trans_main_id']);
			list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $data [$j] ['recvID'] );
			if (! $command->Load ( "f_trans_main_id= '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['recvNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B$i", $util->getDateString ( $data [$j] ['receiveStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['receiveStamp'] ), $format3 );
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", str_replace('&#34;','"',htmlspecialchars_decode(($data [$j] ['title'] ))), $format3 );
			//$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $commandText, $format3 );
			$worksheet->write ( "I$i", "........... ...........", $format3 );
			$i ++;
		}
		
		#################################################################
		

		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "receivedExternalGlobal.xls", 'application/x-msexcel', filesize ( $fname ) );
		
		ob_end_flush ();
	
	}
	
	/**
	 * ��������§ҹ����¹��ҧ�Ѻ���
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function receivedExternalGlobalData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		$filterSQL = Array ();
		if ($util->isDFTransFiltered ( 'RG' )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] ['RG'] ['recvNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] ['RG'] ['recvNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] ['RG'] ['recvNo'] );
					$tmpFilterSQL = " (";
					$tmpFilterSubSQL = "";
					foreach ( $regNoArray as $regNo ) {
						
						if (ereg ( "-", $regNo )) {
							list ( $start, $stop ) = split ( "-", $regNo );
							$filterSQL [] = " (a.f_recv_reg_no >= $start and a.f_recv_reg_no <= $stop)";
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
						list ( $start, $stop ) = split ( "-", $_SESSION ['FilterRecord'] ['RG'] ['recvNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_recv_reg_no >= $start and a.f_recv_reg_no <= $stop)";
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['RG'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['RG']['docDate']}%'";
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
		
		//$regBookID = $_GET ['regBookID'];

//		$countSQL = "select count(*) as count_exp";
//		$countSQL .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
//		$countSQL .= " a.f_receiver_org_id = {$sessionMgr->getCurrentOrgID()}";
//		$countSQL .= " and a.f_reg_book_id = {$regBookID}";
//		$countSQL .= " and a.f_recv_type = 3";
//		$countSQL .= " and a.f_recv_year = '{$sessionMgr->getCurrentYear()}'";
//		$countSQL .= " and a.f_show_in_reg_book = 1";
//		$countSQL .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
//		$countSQL .= " and b.f_doc_id = c.f_doc_id ";
//		if ($util->isDFTransFiltered ( 'RG' )) {
//			$countSQL .= $realFilter;
//		}
//		$rsCount = $conn->Execute ( $countSQL );

		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 3";
		$sql .= " and a.f_recv_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id";
		if ($util->isDFTransFiltered ( 'RG' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_recv_reg_no desc";
	
		//$rsCount = $conn->Execute ( $countSQL );
		

		#echo $sql;
		#$conn->debug  = true;
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		
		if ($util->isDFTransFiltered ( 'RG' )) {
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		if (!$rs) return array();

		$receivedExternal = Array ();

		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			$receivedExternal [] = Array ('recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'receiveStamp' => $tmp ['f_recv_stamp'] );
		}
		//$tmpCount = $rsCount->FetchNextObject ();
		//$count = $tmpCount->COUNT_EXP;
		//$data = json_encode ( $receivedExternal );
		//$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		//print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $receivedExternal;
	}
	
	/**
	 * action /send-circ/ ��§ҹ����¹�����¹
	 *
	 */
	public function sendCircAction() {
		global $config;
		global $sessionMgr;
		global $util;
		global $lang;
		
		//include_once 'Organize.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ��������¹", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'H2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		$format2->set_bold ( 1 );
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 18 );
		$worksheet->set_column ( 5, 5, 18 );
		$worksheet->set_column ( 6, 6, 34 );
		$worksheet->set_column ( 7, 7, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/������', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;        
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		$data = $this->sendCircData ( $regBookID, $start, $limit );
		//var_dump($data);
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['sendNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['sendStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['sendStamp'] ), $format3 );
			//$worksheet->write("B$i",$data[$j]['docNo'],$format3);
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", "..........  ..........", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "sendCirc.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	
	}
	
	/**
	 * action /send-internal/ ��§ҹ����¹������
	 *
	 */
	public function sendInternalAction() {
		global $config;
		global $sessionMgr;
		global $util;
		global $lang;
		
		//include_once 'Organize.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ���������", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		$format2->set_bold ( 1 );
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/������', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;		
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		$data = $this->sendInternalData ( $regBookID, $start, $limit );
		//var_dump($data);
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['sendNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['sendStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['sendStamp'] ), $format3 );
			//$worksheet->write("B$i",$data[$j]['docNo'],$format3);
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $data [$j] ['command'], $format3 );
			$worksheet->write ( "I$i", "..........  ..........", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "sendInternal.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	
	}
	
	/**
	 * action /forward/ ��§ҹ����¹�觵��
	 *
	 */
	public function forwardAction() {
		global $config;
		global $sessionMgr;
		global $util;
		global $lang;
		
		//include_once 'Organize.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ����觵��", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		$format2->set_bold ( 1 );
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/������', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;		
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		$regBook = 0;
		$data = $this->forwardData ( $regBookID, $start, $limit );
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", '-' . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['sendStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['sendStamp'] ), $format3 );
			//$worksheet->write("B$i",$data[$j]['docNo'],$format3);
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $data [$j] ['command'], $format3 );
			$worksheet->write ( "I$i", "..........  ..........", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "ForwardReport.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	}
	
	/**
	 * ��������§ҹ����¹������
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function sendInternalData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		$filterSQL = Array ();
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['SI'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['SI']['docDate']}%'";
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
		//$regBookID = $_GET ['regBookID'];
		

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 1";
		$sqlCount .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		if ($util->isDFTransFiltered ( 'SI' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 1";
		$sql .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		if ($util->isDFTransFiltered ( 'SI' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		if ($util->isDFTransFiltered ( 'SI' )) {
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		$sendInternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_recv_fullname'];
			}
			
			$sendInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'sendStamp' => $tmp ['f_send_stamp'] );
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $sendInternal;
	}
	
	/**
	 * ��������§ҹ����¹�觵��
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function forwardData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		$filterSQL = Array ();
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['FW'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['FW']['docDate']}%'";
			}
			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] ['FW'] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord']['FW']['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] ['FW'] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord']['FW']['docType']}'";
			}
			
			// Filter From
			if ($_SESSION ['FilterRecord'] ['FW'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['FW']['to']}%'";
			}
			
			//Filter Date Range
			if ($_SESSION ['FilterRecord'] ['FW'] ['sendFromDate'] != '') {
				if ($_SESSION ['FilterRecord'] ['FW'] ['sendFromDate'] == $_SESSION ['FilterRecord'] ['FW'] ['sendToDate']) {
					$tmpToDate = $_SESSION ['FilterRecord'] ['SI'] ['sendToDate'] + 86400;
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['FW']['sendFromDate']}' and a.f_send_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord']['FW']['sendFromDate']}' and a.f_send_stamp <= '{$_SESSION['FilterRecord']['SI']['sendToDate']}'  )";
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
						$tmpRealFilter .= " or ({$filterPiece})";
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
		//$regBookID = $_GET ['regBookID'];
		

		//$regBookID = $_GET ['regBookID'];
		

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_uid = {$sessionMgr->getCurrentAccID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_is_forward_trans = 1";
		$sqlCount .= " and a.f_sendback = 0";
		$sqlCount .= " and a.f_callback = 0";
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
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_is_forward_trans = 1";
		$sql .= " and a.f_sendback = 0";
		$sql .= " and a.f_callback = 0";
		$sql .= " and a.f_show_in_reg_book = 0";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()}";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		
		if ($util->isDFTransFiltered ( 'FW' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		//echo $sql;
		//$conn->debug  = true;
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		if ($util->isDFTransFiltered ( 'FW' )) {
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		$sendInternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_recv_fullname'];
			}
			
			$sendInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'sendStamp' => $tmp ['f_send_stamp'] );
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $sendInternal;
	}
	
	/**
	 * ��������§ҹ����¹�����¹
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function sendCircData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		$filterSQL = Array ();
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['SC'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['SC']['docDate']}%'";
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
			
			//Filter Date Range
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SC'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SC'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SC'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}
			if (($_SESSION ['FilterRecord'] ['SC'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SC'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SC'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SC'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SC'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SC'] ['docDateTo'] ) + 86399;				
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
		//$regBookID = $_GET ['regBookID'];
		

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 5";
		$sqlCount .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		if ($util->isDFTransFiltered ( 'SC' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 5";
		$sql .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		if ($util->isDFTransFiltered ( 'SC' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		//echo $sql;
		//$conn->debug  = true;

		if ($util->isDFTransFiltered ( 'SC' )) {
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		$sendInternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_recv_fullname'];
			}
			
			$sendInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'sendStamp' => $tmp ['f_send_stamp'] );
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $sendInternal;
	}
	
	public function receivedReportAction() {
		global $config;
		global $sessionMgr;
		global $util;
		global $conn;
		
		//var_dump($_POST);
		//die();
		$account = $sessionMgr->getCurrentAccountEntity ();

		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );

		$type_id = $_POST ['type_id'];
		
		$currentDate = $util->getDateString ();
		$currentTime = date ( 'H:i:s' );
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		if ( $_POST['reportType'] == 'RecvExtGlobal' ){
		$headings = array ("��§ҹ����¹˹ѧ����Ѻ��¹͡(��ǹ��ҧ)", '' );
		}else if ( $_POST['reportType'] == 'inboundClassified' ){
			$headings = array ("��§ҹ����¹�͡����Ѻ(�Ѻ���)", '' );
		}else if ( $_POST['reportType'] == 'outboundClassified' ){
			$headings = array ("��§ҹ����¹�͡����Ѻ(���͡)", '' );
			
		}
		$worksheet->write_row ( 'E1', $headings, $heading );

		if($type_id=='2'){
		$roleIDTo = $_POST ['filterOrgID'];
		$roleTo = new RoleEntity ( );
		$roleTo->Load ( "f_role_id = '{$roleIDTo}'" );	
		}elseif($type_id=='3'){
		$orgIDTo = $_POST ['filterOrgID'];
		$orgTo = new OrganizeEntity ( );
		$orgTo->Load ( "f_org_id = '{$orgIDTo}'" );
		}
		
		if($type_id=='2'){
		$headings = array ("         ���˹觧ҹ����Ѻ  : " . $roleTo->f_role_name, '' );
		$worksheet->write_row ( 'E2', $headings, $subheading );
		}elseif($type_id=='3'){
		$headings = array ("         ˹��§ҹ/���������Ѻ  : " . $orgTo->f_org_name, '' );
		$worksheet->write_row ( 'E2', $headings, $subheading );
		}

		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("������� : {$account->f_name} {$account->f_last_name}", '' );
		$worksheet->write_row ( 'A3', $headings, $subheading );
		
		$headings = array ("�ѹ������� :$currentDate", '' );
		$worksheet->write_row ( 'H2', $headings, $subheadingright );
		$headings = array ("���Ҿ���� :$currentTime", '' );
		$worksheet->write_row ( 'H3', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		
		/*******************************************************************/
		
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 18 );
		$worksheet->set_column ( 5, 5, 18 );
		$worksheet->set_column ( 6, 6, 34 );
		$worksheet->set_column ( 7, 7, 10 );
		
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		
		if ( $_POST['reportType'] == 'RecvExtGlobal' ){
			$data = $this->receivedReportData ( $type_id,$_POST ['filterFromDocDate2'],$_POST ['filterFromTime2'],$_POST ['filterToDocDate2'],$_POST ['filterToTime2'],$_POST ['filterOrgID'] );
		}else if ( $_POST['reportType'] == 'inboundClassified' ){
			$data = $this->inboundClassifiedData ( $type_id,$_POST ['filterFromDocDate2'],$_POST ['filterFromTime2'],$_POST ['filterToDocDate2'],$_POST ['filterToTime2'],$_POST ['filterOrgID'] );
		}else if ( $_POST['reportType'] == 'outboundClassified' ){
			$data = $this->outboundClassifiedData ( $type_id,$_POST ['filterFromDocDate2'],$_POST ['filterFromTime2'],$_POST ['filterToDocDate2'],$_POST ['filterToTime2'],$_POST ['filterOrgID'] );
		}
		
		//var_dump($data);	
		//die();
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {

			$speedLabel = "�����";
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			$worksheet->write ( "A$i", $data [$j] ['f_recv_reg_no_full'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B$i", $util->getDateString ( $data [$j] ['f_recv_stamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['f_recv_stamp'] ), $format3 );
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			//$worksheet->write ( "F$i", $row ['f_recv_fullname'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			//$worksheet->write ( "G$i", stripslashes($data [$j] ['title']), $format3 );
			$worksheet->write ( "G$i", str_replace('&#34;','"',htmlspecialchars_decode(($data [$j] ['title'] ))), $format3 );
			$worksheet->write ( "H$i", "........... ...........", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "receivedExternalGlobal.xls", 'application/x-msexcel', filesize ( $fname ) );
		
		ob_end_flush ();
	}
	
	/**
	 * ��������§ҹ���Ѻ����¹��ҧ
	 */
		public function receivedReportData($type_id = 0) {
		global $conn;
		global $sessionMgr;
		global $util;
		$timestampFrom = $util->dateToStamp ( $_POST ['filterFromDocDate2'] );
		
		list ( $fromHour, $fromMinute ) = split ( ":", $_POST ['filterFromTime2'] );
		$time1 = mktime ( $fromHour, $fromMinute, 0, date ( 'm', $timestampFrom ), date ( 'd', $timestampFrom ), date ( 'Y', $timestampFrom ) );
		if (strtolower ( $_POST ['filterToDocDate2'] ) == 'default') {
			$time2 = mktime ( 23, 59, 59, date ( 'm' ), date ( 'd'), date ( 'Y' ) );
		} else {
			$timestampTo = $util->dateToStamp ( $_POST ['filterToDocDate2'] );
			list ( $toHour, $toMinute ) = split ( ":", $_POST ['filterToTime2'] );
			$time2 = mktime ( $toHour, $toMinute, 0, date ( 'm', $timestampTo ), date ( 'd', $timestampTo ), date ( 'Y', $timestampTo ) );
		}
		
		if($type_id=='2'){
		$roleIDTo = $_POST ['filterOrgID'];
		$roleTo = new RoleEntity ( );
		$roleTo->Load ( "f_role_id = '{$roleIDTo}'" );	
		}elseif($type_id=='3'){
		$orgIDTo = $_POST ['filterOrgID'];
		$orgTo = new OrganizeEntity ( );
		$orgTo->Load ( "f_org_id = '{$orgIDTo}'" );
		}
		
		$sql = "select a.*,c.f_doc_id ,d.f_receiver_org_id,c.f_doc_no,c.f_title,c.f_doc_date,d.f_recv_fullname,d.f_sender_org_id ";
		$sql .= "from tbl_trans_df_recv a left outer join tbl_trans_master_df b on a.f_recv_trans_main_id = b.f_trans_main_id ";
		$sql .= "left outer join tbl_doc_main c on b.f_doc_id = c.f_doc_id ";
		$sql .= "left outer join tbl_trans_df_send d on a.f_recv_trans_main_id = d.f_recv_trans_main_id ";
		$sql .= "where ";
		$sql .= "d.f_sender_org_id=a.f_receiver_org_id and a.f_recv_type =3 and a.f_recv_year = '{$sessionMgr->getCurrentYear()}' and a.f_recv_stamp >= $time1 and a.f_recv_stamp <= $time2 ";
		
		if (trim ( $roleIDTo ) != '') {
		$sql .= " and d.f_receiver_role_id = $roleIDTo";
		}elseif (trim ( $orgIDTo ) != '') {
		$sql .= " and d.f_receiver_org_id = $orgIDTo";
		$sql .= " and d.f_receiver_role_id = 0";
		}
		$sql .= " order by a.f_recv_reg_no_full desc";
		//echo $sql;
		//die();
		$i = 6;
		$rs = $conn->Execute ( $sql );
		
		$receivedReport = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
		
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
		
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			$receivedReport [] = Array ('f_recv_reg_no_full' => $tmp ['f_recv_reg_no_full'],'f_recv_stamp' => $tmp ['f_recv_stamp'],'sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '' );
		}
	return $receivedReport;
	}
	
	/**
	 * ��������§ҹ����¹���͡����Ѻ(�Ѻ���)
	 */
		public function inboundClassifiedData($type_id = 0) {
		global $conn;
		global $sessionMgr;
		global $util;
		$timestampFrom = $util->dateToStamp ( $_POST ['filterFromDocDate2'] );

		list ( $fromHour, $fromMinute ) = split ( ":", $_POST ['filterFromTime2'] );
		$time1 = mktime ( $fromHour, $fromMinute, 0, date ( 'm', $timestampFrom ), date ( 'd', $timestampFrom ), date ( 'Y', $timestampFrom ) );
		if (strtolower ( $_POST ['filterToDocDate2'] ) == 'default') {
			$time2 = mktime ( 23, 59, 59, date ( 'm' ), date ( 'd'), date ( 'Y' ) );
		} else {
			$timestampTo = $util->dateToStamp ( $_POST ['filterToDocDate2'] );
			list ( $toHour, $toMinute ) = split ( ":", $_POST ['filterToTime2'] );
			$time2 = mktime ( $toHour, $toMinute, 0, date ( 'm', $timestampTo ), date ( 'd', $timestampTo ), date ( 'Y', $timestampTo ) );
		}
		
		if($type_id=='2'){
		$roleIDTo = $_POST ['filterOrgID'];
		$roleTo = new RoleEntity ( );
		$roleTo->Load ( "f_role_id = '{$roleIDTo}'" );	
		}elseif($type_id=='3'){
		$orgIDTo = $_POST ['filterOrgID'];
		$orgTo = new OrganizeEntity ( );
		$orgTo->Load ( "f_org_id = '{$orgIDTo}'" );
		}
		$sql = "select a.*,c.f_doc_id ,d.f_receiver_org_id,c.f_doc_no,c.f_title,c.f_doc_date,d.f_recv_fullname,d.f_sender_org_id ";
		$sql .= "from tbl_trans_df_recv a left outer join tbl_trans_master_df b on a.f_recv_trans_main_id = b.f_trans_main_id ";
		$sql .= "left outer join tbl_doc_main c on b.f_doc_id = c.f_doc_id ";
		$sql .= "left outer join tbl_trans_df_send d on a.f_recv_trans_main_id = d.f_recv_trans_main_id ";
		$sql .= "where ";
		$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()} and ";
		//$sql .= "d.f_sender_org_id=a.f_accept_org_id and a.f_recv_type =4 and a.f_recv_year = '{$sessionMgr->getCurrentYear()}' and a.f_recv_stamp >= $time1 and a.f_recv_stamp <= $time2 ";
		$sql .= "a.f_recv_type =4 and a.f_recv_year = '{$sessionMgr->getCurrentYear()}' and a.f_recv_stamp >= $time1 and a.f_recv_stamp <= $time2 ";
		
		if (trim ( $roleIDTo ) != '') {
		$sql .= " and d.f_receiver_role_id = $roleIDTo";
		}elseif (trim ( $orgIDTo ) != '') {
		$sql .= " and d.f_receiver_org_id = $orgIDTo";
		$sql .= " and d.f_receiver_role_id = 0";
		}
		$sql .= " order by a.f_recv_reg_no desc";

		$i = 6;
		$rs = $conn->Execute ( $sql );

		$receivedReport = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			$receivedReport [] = Array ('f_recv_reg_no_full' => $tmp ['f_recv_reg_no_full'],'f_recv_stamp' => $tmp ['f_recv_stamp'],'sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '' );
		}
	return $receivedReport;
	}
	
		/**
	 * ��������§ҹ����¹���͡����Ѻ(���͡)
	*/
		public function outboundClassifiedData($type_id = 0) {
		global $conn;
		global $sessionMgr;
		global $util;
		$timestampFrom = $util->dateToStamp ( $_POST ['filterFromDocDate2'] );

		list ( $fromHour, $fromMinute ) = split ( ":", $_POST ['filterFromTime2'] );
		$time1 = mktime ( $fromHour, $fromMinute, 0, date ( 'm', $timestampFrom ), date ( 'd', $timestampFrom ), date ( 'Y', $timestampFrom ) );
		if (strtolower ( $_POST ['filterToDocDate2'] ) == 'default') {
			$time2 = mktime ( 23, 59, 59, date ( 'm' ), date ( 'd'), date ( 'Y' ) );
		} else {
			$timestampTo = $util->dateToStamp ( $_POST ['filterToDocDate2'] );
			list ( $toHour, $toMinute ) = split ( ":", $_POST ['filterToTime2'] );
			$time2 = mktime ( $toHour, $toMinute, 0, date ( 'm', $timestampTo ), date ( 'd', $timestampTo ), date ( 'Y', $timestampTo ) );
		}
		
		if($type_id=='2'){
		$roleIDTo = $_POST ['filterOrgID'];
		$roleTo = new RoleEntity ( );
		$roleTo->Load ( "f_role_id = '{$roleIDTo}'" );	
		}elseif($type_id=='3'){
		$orgIDTo = $_POST ['filterOrgID'];
		$orgTo = new OrganizeEntity ( );
		$orgTo->Load ( "f_org_id = '{$orgIDTo}'" );
		}
		$sql = "select a.*, c.f_doc_id, c.f_doc_no, c.f_title, c.f_doc_date ";
		$sql .= "from tbl_trans_df_send a LEFT OUTER JOIN tbl_trans_master_df b ON a.f_send_trans_main_id = b.f_trans_main_id ";
		$sql .= "LEFT OUTER JOIN tbl_doc_main c ON b.f_doc_id = c.f_doc_id ";
		$sql .= "where a.f_send_type = 4 and a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()} ";
		//$sql .= "and a.f_show_in_reg_book = 1 ";
		$sql .= "and a.f_send_reg_no != 0 ";
		$sql .= "and a.f_send_year = '{$sessionMgr->getCurrentYear()}' and a.f_send_stamp >= $time1 and a.f_send_stamp <= $time2 ";
		
		if (trim ( $roleIDTo ) != '') {
		$sql .= " and a.f_receiver_role_id = $roleIDTo";
		}elseif (trim ( $orgIDTo ) != '') {
		$sql .= " and a.f_receiver_org_id = $orgIDTo";
		$sql .= " and a.f_receiver_role_id = 0";
		}
		$sql .= " order by a.f_send_reg_no desc";
		$i = 6;
		$rs = $conn->Execute ( $sql );

		$receivedReport = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}

			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_recv_fullname'];
			}
			$receivedReport [] = Array ('f_recv_reg_no_full' => $tmp ['f_send_reg_no_full'],'f_recv_stamp' => $tmp ['f_send_stamp'],'sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '' );
		}
	return $receivedReport;
	}
	
	/**
	 * actiom /send-external/ ��§ҹ����¹����¹͡
	 *
	 */
	public function sendExternalAction() {
		global $config;
		global $sessionMgr;
		global $util;

		//include_once 'Organize.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (color => 'black', size => 28, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 16 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 16, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ�������¹͡", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("{$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ$currentDate", '' );
		$worksheet->write_row ( 'H2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		$format2->set_bg_color ( 'grey' );
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 14 );
		$format2->set_align ( "left" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 15 );
		$worksheet->set_column ( 1, 1, 10 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 50 );
		$worksheet->set_column ( 6, 6, 20 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->write ( 'A5', '�Ţ������¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '���', $format2 );
		$worksheet->write ( 'C5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'D5', '�ҡ', $format2 );
		$worksheet->write ( 'E5', '�֧', $format2 );
		$worksheet->write ( 'F5', '����ͧ', $format2 );
		$worksheet->write ( 'G5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'H5', '�����˵�', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;		
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		$data = $this->sendExternalData ( $regBookID, $start, $limit );
		//var_dump($data);
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			$worksheet->write ( "A$i", $data [$j] ['sendNo'], $format3 );
			$worksheet->write ( "B$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "C$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['command'], $format3 );
			$worksheet->write ( "H$i", "", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "sendExternal.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	
	}
	
	/**
	 * ��������§ҹ����¹����¹͡
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function sendExternalData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		$filterSQL = Array ();
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['SE'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['SE']['docDate']}%'";
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
		
		//$regBookID = $_GET ['regBookID'];
		

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 2";
		$sqlCount .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		if ($util->isDFTransFiltered ( 'SE' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 2";
		$sql .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		if ($util->isDFTransFiltered ( 'SE' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		//echo $sql;
		//$conn->debug  = true;
		if ($util->isDFTransFiltered ( 'SE' )) {
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$sendExternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_recv_fullname'];
			}
			$sendExternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '' );
		}
		
		//$tmpCount = $rsCount->FetchNextObject ();
		//$count = $tmpCount->COUNT_EXP;
		//$count = count ( $receivedInternal );
		//$data = json_encode ( $receivedExternal );
		//$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		//print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $sendExternal;
	}
	
	/**
	 * action /send-external-global/ ��§ҹ����¹����¹͡����¹��ҧ
	 *
	 */
	public function sendExternalGlobalAction() {
		global $config;
		global $sessionMgr;
		global $util;
		
		$account = $sessionMgr->getCurrentAccountEntity ();
		
		//include_once 'Organize.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheading2 = & $workbook->addformat ( array (bold => 1, color => 'black', size => 8, align => 'center' ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		//$headings = array ("��§ҹ����¹˹ѧ�������¹͡(����¹��ҧ)", '' );
		$headings = array ("�ӹѡ�ҹ��С�����áӡѺ������������û�Сͺ��áԨ��Сѹ���", '' );
		$headings2 = array ("��§ҹ����¹˹ѧ�������¹͡(����¹��ҧ)", '' );
		
		$worksheet->write_row ( 'd1', $headings, $heading );
		$worksheet->write_row ( 'f2', $headings2, $subheading2 );
		if ($util->isDFTransFiltered ( 'SG' )) {
			$worksheet->write_row ( 'f3', array ("�ҡ�ѹ���{$util->getDateString($_SESSION ['FilterRecord'] ['SG'] ['sendFromDate'])} - �֧�ѹ��� {$util->getDateString($_SESSION ['FilterRecord'] ['SG'] ['sendToDate'])}" ), $subheading2 );
		} else {
			$worksheet->write_row ( 'f3', array ("�ҡ�ѹ���{$util->getDateString()} - �֧�ѹ��� {$util->getDateString()}" ), $subheading2 );
		}
		
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A3', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'i2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		$headings = array ("���Ҿ���� : " . date ( 'H:i:s' ), '' );
		$worksheet->write_row ( 'i3', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		$headings = array ("������� : {$account->f_name} {$account->f_last_name}", '' );
		$worksheet->write_row ( 'i4', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		$format2->set_bold ( 1 );
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		
		$worksheet->set_column ( 0, 0, 5 );
		$worksheet->set_column ( 1, 1, 8 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 10 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 30 );
		$worksheet->set_column ( 7, 7, 8 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->set_column ( 9, 9, 10 );
		$worksheet->set_column ( 10, 10, 10 );		
		
		$worksheet->write ( 'A5', '�Ţ��˹ѧ����͡' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�Ţ��˹ѧ��͵�����ͧ', $format2 );
		$worksheet->write ( 'C5', '�ѹ��������ͧ', $format2 );
		$worksheet->write ( 'D5', '�ѹ����͡˹ѧ���', $format2 );
		$worksheet->write ( 'E5', 'ŧ���', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '���ͼ����', $format2 );
		$worksheet->write ( 'I5', '������ͧ', $format2 );
		$worksheet->write ( 'J5', '�����˵�', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;		
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		$data = $this->sendExternalGlobalData ( $regBookID, $start, $limit );
		//var_dump($data);
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			//$worksheet->write ( "A$i", $data [$j] ['sendNo'], $format3 );
			$worksheet->write ( "A$i", $data [$j] ['docNo'], $format3 );
			if($data[$j]['ownerDocNo'] == '') {
				$worksheet->write ( "B$i", '-' , $format3 );
			} else {
				$worksheet->write ( "B$i", $data [$j] ['ownerDocNo'], $format3 );
			}

			if($data[$j]['ownerDocNo'] == '') {
				$worksheet->write ( "C$i", '-', $format3 );
			} else {
				$worksheet->write ( "C$i", $data [$j] ['ownerDocDate'], $format3 );
			}
			

			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['signUser'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			//$worksheet->write ( "H$i", $data [$j] ['command'], $format3 );
			
			$account = new AccountEntity ( );
			$account->Load ( "f_acc_id = '{$data[$j]['senderID']}'" );
			
			$worksheet->write ( "H{$i}", "{$account->f_name} {$account->f_last_name}", $format3 );
			if( $data[$j]['ownerName'] == '') {
				$worksheet->write ( "I{$i}", "-", $format3 );
			} else {
				$worksheet->write ( "I{$i}", $data[$j]['ownerName'], $format3 );
			}
			$worksheet->write ( "J{$i}", $data[$j]['remark'], $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "sendExternalGlobal.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	
	}
	
	/**
	 * ��������§ҹ����¹͡����¹��ҧ
	 *
	 * @param unknown_type $regBookID
	 * @param unknown_type $start
	 * @param unknown_type $limit
	 * @return unknown
	 */
	public function sendExternalGlobalData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		$filterSQL = Array ();
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

			// Filter Receive Doc.Date
			$util = new ECMUtility();
			if (($_SESSION ['FilterRecord'] ['SG'] ['docDateFrom'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SG'] ['docDateFrom']  ) != 'default')) {
				$filterDateFrom = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SG'] ['docDateFrom'] );
				$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
			}

			if (($_SESSION ['FilterRecord'] ['SG'] ['docDateTo'] != '') && (strtolower ( $_SESSION ['FilterRecord'] ['SG'] ['docDateTo']  ) != 'default')) {
				if ($_SESSION ['FilterRecord'] ['SG'] ['docDateFrom'] == '' || strtolower ( $_SESSION ['FilterRecord'] ['SG'] ['docDateFrom']  ) == 'default')
				{
					$filterDateFrom = $util->dateToStamp ( $util->firstDateOfYear ( $_SESSION ['FilterRecord'] ['SG'] ['docDateTo'] ) );
					$filterSQL [] = " c.f_doc_realdate >= {$filterDateFrom}";
				}
				$filterDateTo = $util->dateToStamp ( $_SESSION ['FilterRecord'] ['SG'] ['docDateTo'] ) + 86399;
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
			if ($_SESSION ['FilterRecord'] ['SG'] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord']['SG']['to']}%'";
			}
			
			//Filter Date Range
//			if ($_SESSION ['FilterRecord'] ['SG'] ['sendFromDate'] != '') {
//				if ($_SESSION ['FilterRecord'] ['SG'] ['sendFromDate'] == $_SESSION ['FilterRecord'] ['SG'] ['sendToDate']) {
//					$tmpToDate = $_SESSION ['FilterRecord'] ['SG'] ['sendToDate'] + 86400;
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
		
		//$regBookID = $_GET ['regBookID'];
		//$regBookID = 0;
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

//		$sqlCount = "select count(*) as count_exp ";
//		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
//		//$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
//		$sqlCount .= " a.f_reg_book_id = {$regBookID}";
//		$sqlCount .= " and a.f_send_type = 3";
//		$sqlCount .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
//		$sqlCount .= " and a.f_show_in_reg_book = 1";
//		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
//		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
//		if ($util->isDFTransFiltered ( 'SG' )) {
//			$sqlCount .= $realFilter;
//		}
		
//		$rsCount = $conn->Execute ( $sqlCount );

		$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 3";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";
		$sqlCount .= $sqlWhereYear;

		if ($util->isDFTransFiltered ( 'SG' )) {
			$sqlCount .= $realFilter;
		}
		
		$rsCount = $conn->Execute ( $sqlCount );
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_sign_uid,c.f_sign_role_id ";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		//$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 3";
		//$sql .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		 $sql .= $sqlWhereYear;
		if ($util->isDFTransFiltered ( 'SG' )) {
			$sql .= $realFilter;
		}
		$sql .= " order by a.f_send_reg_no desc";
		
		//echo $sql;
		//Logger::debug($sql);
		//die($sql);
		//$conn->debug  = true;
		if ($util->isDFTransFiltered ( 'SG' )) {
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$sendExternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_recv_fullname'];
			}
			
			$signUser = "";
			if (! is_null ( $tmp ['f_sign_uid'] )) {
				$signedUserEntity = new AccountEntity ( );
				$signedRoleEntity = new RoleEntity ( );
				$signedUserEntity->Load ( "f_acc_id = '{$tmp['f_sign_uid']}'" );
				$signedRoleEntity->Load ( "f_role_id = '{$tmp['f_sign_role_id']}'" );
				$signUser = "{$signedUserEntity->f_name} {$signedUserEntity->f_last_name}({$signedRoleEntity->f_role_name})";
			}
			
			$pimaryDocument = new DocMainEntity();
			$pimaryDocument->Load("f_doc_id = '{$tmp ['f_doc_id']}' ");
			$ownerDocument = new DocMainEntity();
			$ownerDocument->Load("f_doc_id = '{$pimaryDocument->f_ref_gen_from_doc_id}' ");
			$ownerEntity = new AccountEntity() ;
			$ownerEntity->Load("f_acc_id = '{$ownerDocument->f_create_uid}'");
			$passport = new PassportEntity();
			$passport->Load("f_acc_id = '{$ownerDocument->f_create_uid}' and f_default_role='1'");
			$roleOwner = new RoleEntity();
			$roleOwner->Load("f_role_id = '{$passport->f_role_id}'");
			$orgOwner = new OrganizeEntity();
			$orgOwner->Load("f_org_id = '{$roleOwner->f_org_id}'");
			$sendExternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo , 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'signUser' => $signUser ,'senderID' => $tmp['f_sender_uid'], 'ownerDocNo' => $ownerDocument->f_doc_no , 'ownerName' => $orgOwner->f_org_name,'ownerDocDate'=>$ownerDocument->f_doc_date,'remark'=>$pimaryDocument->f_remark);
		}
		
		//$tmpCount = $rsCount->FetchNextObject ();
		//$count = $tmpCount->COUNT_EXP;
		//$count = count ( $receivedInternal );
		//$data = json_encode ( $receivedExternal );
		//$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		//print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $sendExternal;
	}
	
	/**
	 * action /send-internal-global/ ��§ҹ�����㹷���¹��ҧ
	 *
	 */
	public function sendInternalGlobalAction() {
		global $config;
		global $sessionMgr;
		global $util;
		global $lang;
		
		//include_once 'Organize.Entity.php';
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹˹ѧ���������(��ǹ��ҧ)", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();
		//$format2->set_bg_color ( 'grey' );
		$format2->set_bold ( 1 );
		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/������', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		//$countARR = count ( $data );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		//$start = 0;		
		$limit = 25;
		$start = ($page - 1) * $limit;
		//$sort = 'recvNo';
		//$dir='DESC';
		//$_dc=time();
		//$callback = 'stcCallback'.$_dc;
		//$sessionID = session_id();
		//$param ="regBookID={$regBookID}&start={$start}&limit={$limit}&sort={$sort}&dir={$dir}&_dc={$_dc}&callback={$callback}&PHPSESSID={$sessionID}";
		//$DataURL = "http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/df-data/received-internal?$param";
		//$data = $util->curl_string($DataURL);
		//var_dump($data);
		//echo "xxx";
		$data = $this->sendInternalGlobalData ( $regBookID, $start, $limit );
		//var_dump($data);
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['sendNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['sendStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['sendStamp'] ), $format3 );
			//$worksheet->write("B$i",$data[$j]['docNo'],$format3);
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $data [$j] ['command'], $format3 );
			$worksheet->write ( "I$i", "..........  ..........", $format3 );
			$i ++;
		}
		#################################################################
		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "sendInternal.xls", 'application/x-msexcel', filesize ( $fname ) );
		ob_end_flush ();
	
	}
	
	/**
	 * ��������§ҹ�����㹷���¹��ҧ
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function sendInternalGlobalData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
		
		//$conn->debug = true;
		$filterSQL = Array ();
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
			// Filter Receive Doc.Date
			if ($_SESSION ['FilterRecord'] ['SGI'] ['docDate'] != '') {
				
				$filterSQL [] = " c.f_doc_date like '%{$_SESSION['FilterRecord']['SGI']['docDate']}%'";
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
		
		//$conn->debug  = true; 
		//$regBookID = $_GET ['regBookID'];
		

		/*$sqlCount = "select count(*) as count_exp ";
		$sqlCount .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sqlCount .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sqlCount .= " and a.f_reg_book_id = {$regBookID}";
		$sqlCount .= " and a.f_send_type = 7";
		$sqlCount .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sqlCount .= " and a.f_show_in_reg_book = 1";
		$sqlCount .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sqlCount .= " and b.f_doc_id = c.f_doc_id";*/
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
		/*********************************/
		
		if ($util->isDFTransFiltered ( 'SGI' )) {
			$sqlCount .= $realFilter;
		}
		
		/*********************************/
		
		$rsCount = $conn->Execute ( $sqlCount );
		
		/*$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = 7";
		$sql .= " and a.f_send_year = '{$sessionMgr->getCurrentYear()}'";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";*/
		
		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
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
		
		//$rs = $conn->CacheExecute($config['defaultCacheSecs'],$sql);
		if ($util->isDFTransFiltered ( 'SGI' )) {
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}
		$sendInternal = Array ();
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_recv_fullname'];
			}
			
			$sendInternal [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'sendStamp' => $tmp ['f_send_stamp'] );
		}
		
		//$count = count ( $receivedInternal );
		$tmpCount = $rsCount->FetchNextObject ();
		$count = $tmpCount->COUNT_EXP;
		$data = json_encode ( $receivedInternal );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		return $sendInternal;
	}

	/**
	 * action /receive-secret/ ��§ҹ����¹�͡����Ѻ(�Ѻ���)
	 *
	 */
	public function receiveSecretAction() {
		global $config;
		global $sessionMgr;
		global $util;
	
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹�͡����Ѻ(�Ѻ���)", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� :$currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();		

		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����ŧ�Ѻ', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;	
		$limit = 25;
		$start = ($page - 1) * $limit;
		$data = $this->receivedSecretData ( $regBookID, $start, $limit );
		//var_dump($data);
		//die();
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			$command = new CommandEntity ( );
			//var_dump($data[$j]);
			//die($data[$j]['f_recv_trans_main_id']);
			list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $data [$j] ['recvID'] );
			if (! $command->Load ( "f_trans_main_id= '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['recvNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B$i", $util->getDateString ( $data [$j] ['receiveStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['receiveStamp'] ), $format3 );
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $commandText, $format3 );
			$worksheet->write ( "I$i", "........... ...........", $format3 );
			$i ++;
		}
		
		#################################################################
		

		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "receivedSecret.xls", 'application/x-msexcel', filesize ( $fname ) );
		
		ob_end_flush ();
	
	}

	/**
	 * ��������§ҹ����¹�͡����Ѻ(�Ѻ���)
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function receivedSecretData($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;

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
			
			$util = new ECMUtility();
			$datefrom = $util->dateToStamp($_SESSION ['FilterRecord'] ['RS'] ['docDateFrom']);
			$dateto = $util->dateToStamp($_SESSION ['FilterRecord'] ['RS'] ['docDateTo']);
			
			// Filter Receive Doc.Date From
			if ($datefrom != '') {
				$filterSQL [] = " c.f_doc_stamp >= {$datefrom} ";
			}

			// Filter Receive Doc.Date To	
			if ($dateto != ''){
				$filterSQL [] = " c.f_doc_stamp <=  {$dateto} ";
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
			if ($_SESSION ['FilterRecord'] ['RS'] ['fromDate'] != '') {
				if($_SESSION['FilterRecord']['RS']['fromDate'] == $_SESSION['FilterRecord']['RS']['toDate']) {
					$tmpToDate = $_SESSION['FilterRecord']['RS']['toDate'] + 86400;
					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RS']['fromDate']}' and a.f_recv_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RS']['fromDate']}' and a.f_recv_stamp <= '{$_SESSION['FilterRecord']['RS']['toDate']}'  )";
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
		
		$sql = "select a.f_recv_trans_main_id,a.f_recv_trans_main_seq,a.f_recv_id,a.f_read,a.f_forwarded,a.f_commit ";
		$sql .= " ,b.f_security_modifier,b.f_urgent_modifier,a.f_sendback,a.f_cancel,a.f_is_egif_trans,a.f_send_fullname ";
		$sql .= " ,a.f_recv_reg_no,b.f_doc_id,c.f_title,c.f_doc_date,a.f_recv_stamp,c.f_doc_no,a.f_attend_fullname ";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_accept_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_recv_type = 4";
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_recv_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
      	$sql .= " and a.f_recv_year = {$sessionMgr->getCurrentYear()} "; 

		if ($util->isDFTransFiltered ( 'RS' )) {
			$sql .= $realFilter;
		}

		$sql .= " order by a.f_recv_reg_no desc";


		if ($util->isDFTransFiltered ( 'RS' )) {
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}

		if (!$rs) return array();

		$receivedExternal = Array ();
		
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			$receivedSecret [] = Array ('recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'receiveStamp' => $tmp ['f_recv_stamp'] );
		}
		return $receivedSecret;
	}
	
	/**
	 * action /receive-secret-int/ ��§ҹ����¹�͡����Ѻ(�Ѻ����)
	 *
	 */
	public function receiveSecretIntAction() {
		global $config;
		global $sessionMgr;
		global $util;
	
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹�͡����Ѻ(�Ѻ����)", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� :$currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();		

		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����ŧ�Ѻ', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;	
		$limit = 25;
		$start = ($page - 1) * $limit;
		$data = $this->receivedSecretDataInt ( $regBookID, $start, $limit );
		//var_dump($data);
		//die();
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			$command = new CommandEntity ( );
			//var_dump($data[$j]);
			//die($data[$j]['f_recv_trans_main_id']);
			list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $data [$j] ['recvID'] );
			if (! $command->Load ( "f_trans_main_id= '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['recvNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B$i", $util->getDateString ( $data [$j] ['receiveStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['receiveStamp'] ), $format3 );
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $commandText, $format3 );
			$worksheet->write ( "I$i", "........... ...........", $format3 );
			$i ++;
		}
		
		#################################################################
		

		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "receivedSecret.xls", 'application/x-msexcel', filesize ( $fname ) );
		
		ob_end_flush ();
	
	}	

	/**
	 * ��������§ҹ����¹�͡����Ѻ(�Ѻ����)
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function receivedSecretDataInt($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;

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
			
			$util = new ECMUtility();
			$datefrom = $util->dateToStamp($_SESSION ['FilterRecord'] ['RSI'] ['docDateFrom']);
			$dateto = $util->dateToStamp($_SESSION ['FilterRecord'] ['RSI'] ['docDateTo']);
			
			// Filter Receive Doc.Date From
			if ($datefrom != '') {
				$filterSQL [] = " c.f_doc_stamp >= {$datefrom} ";
			}

			// Filter Receive Doc.Date To	
			if ($dateto != ''){
				$filterSQL [] = " c.f_doc_stamp <=  {$dateto} ";
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
			if ($_SESSION ['FilterRecord'] ['RSI'] ['fromDate'] != '') {
				if($_SESSION['FilterRecord']['RSI']['fromDate'] == $_SESSION['FilterRecord']['RSI']['toDate']) {
					$tmpToDate = $_SESSION['FilterRecord']['RSI']['toDate'] + 86400;
					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RSI']['fromDate']}' and a.f_recv_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RSI']['fromDate']}' and a.f_recv_stamp <= '{$_SESSION['FilterRecord']['RSI']['toDate']}'  )";
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
		
		$sql = "select a.f_recv_trans_main_id,a.f_recv_trans_main_seq,a.f_recv_id,a.f_read,a.f_forwarded,a.f_commit ";
		$sql .= " ,b.f_security_modifier,b.f_urgent_modifier,a.f_sendback,a.f_cancel,a.f_is_egif_trans,a.f_send_fullname ";
		$sql .= " ,a.f_recv_reg_no,b.f_doc_id,c.f_title,c.f_doc_date,a.f_recv_stamp,c.f_doc_no,a.f_attend_fullname ";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
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
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}

		if (!$rs) return array();
		
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			$receivedSecret [] = Array ('recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'receiveStamp' => $tmp ['f_recv_stamp'] );
		}
		return $receivedSecret;
	}

	/**
	 * action /receive-secret-ext/ ��§ҹ����¹�͡����Ѻ(�Ѻ��¹͡)
	 *
	 */
	public function receiveSecretExtAction() {
		global $config;
		global $sessionMgr;
		global $util;
	
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹�͡����Ѻ(�Ѻ��¹͡)", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� :$currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();		

		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����ŧ�Ѻ', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;	
		$limit = 25;
		$start = ($page - 1) * $limit;
		$data = $this->receivedSecretDataExt ( $regBookID, $start, $limit );
		//var_dump($data);
		//die();
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			$command = new CommandEntity ( );
			//var_dump($data[$j]);
			//die($data[$j]['f_recv_trans_main_id']);
			list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $data [$j] ['recvID'] );
			if (! $command->Load ( "f_trans_main_id= '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' " )) {
				$commandText = "";
			} else {
				$commandText = $command->f_command_text;
			}
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['recvNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B$i", $util->getDateString ( $data [$j] ['receiveStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['receiveStamp'] ), $format3 );
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $commandText, $format3 );
			$worksheet->write ( "I$i", "........... ...........", $format3 );
			$i ++;
		}
		
		#################################################################
		

		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "receivedSecret.xls", 'application/x-msexcel', filesize ( $fname ) );
		
		ob_end_flush ();
	
	}	

	/**
	 * ��������§ҹ����¹�͡����Ѻ(�Ѻ��¹͡)
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function receivedSecretDataExt($regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;

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
			
			$util = new ECMUtility();
			$datefrom = $util->dateToStamp($_SESSION ['FilterRecord'] ['RSE'] ['docDateFrom']);
			$dateto = $util->dateToStamp($_SESSION ['FilterRecord'] ['RSE'] ['docDateTo']);
			
			// Filter Receive Doc.Date From
			if ($datefrom != '') {
				$filterSQL [] = " c.f_doc_stamp >= {$datefrom} ";
			}

			// Filter Receive Doc.Date To	
			if ($dateto != ''){
				$filterSQL [] = " c.f_doc_stamp <=  {$dateto} ";
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
			if ($_SESSION ['FilterRecord'] ['RSE'] ['fromDate'] != '') {
				if($_SESSION['FilterRecord']['RSE']['fromDate'] == $_SESSION['FilterRecord']['RSE']['toDate']) {
					$tmpToDate = $_SESSION['FilterRecord']['RSE']['toDate'] + 86400;
					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RSE']['fromDate']}' and a.f_recv_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_recv_stamp >= '{$_SESSION['FilterRecord']['RSE']['fromDate']}' and a.f_recv_stamp <= '{$_SESSION['FilterRecord']['RSE']['toDate']}'  )";
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
		
		$sql = "select a.f_recv_trans_main_id,a.f_recv_trans_main_seq,a.f_recv_id,a.f_read,a.f_forwarded,a.f_commit ";
		$sql .= " ,b.f_security_modifier,b.f_urgent_modifier,a.f_sendback,a.f_cancel,a.f_is_egif_trans,a.f_send_fullname ";
		$sql .= " ,a.f_recv_reg_no,b.f_doc_id,c.f_title,c.f_doc_date,a.f_recv_stamp,c.f_doc_no,a.f_attend_fullname ";
		$sql .= " from tbl_trans_df_recv a,tbl_trans_master_df b,tbl_doc_main c where ";
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
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}

		if (!$rs) return array();
		
		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_attend_fullname'] ) || trim ( $tmp ['f_attend_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_attend_fullname'];
			}
			$receivedSecret [] = Array ('recvID' => $tmp ['f_recv_trans_main_id'] . '_' . $tmp ['f_recv_trans_main_seq'] . '_' . $tmp ['f_recv_id'], 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'recvNo' => $tmp ['f_recv_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'read' => $tmp ['f_read'], 'forward' => $tmp ['f_forwarded'], 'commit' => $tmp ['f_commit'], 'sendback' => $tmp ['f_sendback'], 'cancel' => $tmp ['f_cancel'], 'egif' => $tmp ['f_is_egif_trans'], 'receiveStamp' => $tmp ['f_recv_stamp'] );
		}
		return $receivedSecret;
	}

	/**
	 * action /send-secret/ ��§ҹ����¹�͡����Ѻ(���͡)
	 *
	 */
	public function sendSecretAction() {
		global $config;
		global $sessionMgr;
		global $util;
		
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹�͡����Ѻ(���͡)", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();

		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����ŧ�Ѻ', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		$limit = 25;
		$start = ($page - 1) * $limit;
		$data = $this->sendSecretData ( 'SS', $regBookID, $start, $limit );
		//var_dump($data);
		//die();
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['sendNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['sendStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['sendStamp'] ), $format3 );
			//$worksheet->write("B$i",$data[$j]['docNo'],$format3);
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $data [$j] ['command'], $format3 );
			$worksheet->write ( "I$i", "..........  ..........", $format3 );
			$i ++;
		}
		
		#################################################################
		

		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "sendSecret.xls", 'application/x-msexcel', filesize ( $fname ) );
		
		ob_end_flush ();
	
	}

	/**
	 * action /send-secret-int/ ��§ҹ����¹�͡����Ѻ(������)
	 *
	 */
	public function sendSecretIntAction() {
		global $config;
		global $sessionMgr;
		global $util;
		
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹�͡����Ѻ(������)", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();

		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����ŧ�Ѻ', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		$limit = 25;
		$start = ($page - 1) * $limit;
		$data = $this->sendSecretData ( 'SSI', $regBookID, $start, $limit );
		//var_dump($data);
		//die();
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['sendNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['sendStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['sendStamp'] ), $format3 );
			//$worksheet->write("B$i",$data[$j]['docNo'],$format3);
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $data [$j] ['command'], $format3 );
			$worksheet->write ( "I$i", "..........  ..........", $format3 );
			$i ++;
		}
		
		#################################################################
		

		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "sendSecret.xls", 'application/x-msexcel', filesize ( $fname ) );
		
		ob_end_flush ();
	
	}

	/**
	 * action /send-secret-ext/ ��§ҹ����¹�͡����Ѻ(����¹͡)
	 *
	 */
	public function sendSecretExtAction() {
		global $config;
		global $sessionMgr;
		global $util;
		
		error_reporting ( 0 );
		$orgID = $sessionMgr->getCurrentOrgID ();
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$orgID}'" );
		$currentDate = $util->getDateString ();
		
		$excelTempPath = $config ['tempPath'] . "excel";
		$fname = tempnam ( $excelTempPath, "report" . uniqid ( time () ) . ".xls" );
		$workbook = & new writeexcel_workbook ( $fname );
		$worksheet = & $workbook->addworksheet ( '��§ҹ����¹˹ѧ���-1' );
		$worksheet->set_landscape ();
		
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 12, align => 'left' ) );
		$subheading = & $workbook->addformat ( array (color => 'black', size => 10 ) );
		$subheadingright = & $workbook->addformat ( array (color => 'black', size => 10, align => 'right' ) );
		
		$headings = array ("��§ҹ����¹�͡����Ѻ(����¹͡)", '' );
		$worksheet->write_row ( 'E1', $headings, $heading );
		$headings = array ("˹��§ҹ : {$org->f_org_name}", '' );
		$worksheet->write_row ( 'A2', $headings, $subheading );
		
		$headings = array ("�ѹ������� : $currentDate", '' );
		$worksheet->write_row ( 'I2', $headings, $subheadingright );
		$heading = & $workbook->addformat ( array (bold => 1, color => 'black', size => 14 ) );
		
		$format2 = & $workbook->addformat ();
		$format2->set_text_wrap ();

		$format2->set_bottom ( 1 );
		$format2->set_left ( 1 );
		$format2->set_right ( 1 );
		$format2->set_top ( 1 );
		$format2->set_size ( 8 );
		$format2->set_align ( "center" );
		/*******************************************************************/
		$worksheet->set_column ( 0, 0, 8 );
		$worksheet->set_column ( 1, 1, 13 );
		$worksheet->set_column ( 2, 2, 10 );
		$worksheet->set_column ( 3, 3, 15 );
		$worksheet->set_column ( 4, 4, 15 );
		$worksheet->set_column ( 5, 5, 15 );
		$worksheet->set_column ( 6, 6, 25 );
		$worksheet->set_column ( 7, 7, 15 );
		$worksheet->set_column ( 8, 8, 10 );
		$worksheet->write ( 'A5', '����¹' . $headtable, $format2 );
		$worksheet->write ( 'B5', '�ѹ/����ŧ�Ѻ', $format2 );
		$worksheet->write ( 'C5', '���', $format2 );
		$worksheet->write ( 'D5', 'ŧ�ѹ���', $format2 );
		$worksheet->write ( 'E5', '�ҡ', $format2 );
		$worksheet->write ( 'F5', '�֧', $format2 );
		$worksheet->write ( 'G5', '����ͧ', $format2 );
		$worksheet->write ( 'H5', '��û�Ժѵ�', $format2 );
		$worksheet->write ( 'I5', 'ŧ���� �Ѻ�ѹ���', $format2 );
		
		$format3 = & $workbook->addformat ();
		$format3->set_text_wrap ();
		$format3->set_bottom ( 1 );
		$format3->set_left ( 1 );
		$format3->set_right ( 1 );
		$format3->set_top ( 1 );
		$format3->set_align ( "top" );
		
		$format4 = & $workbook->addformat ();
		$format4->set_text_wrap ();
		
		$format4->set_bottom ( 1 );
		$format4->set_left ( 1 );
		$format4->set_right ( 1 );
		$format4->set_top ( 1 );
		$format4->set_bold ( 1 );
		$format4->set_align ( "left" );
		$i = 6;
		
		$page = $_GET ['page'];
		$regBookID = 0;
		$limit = 25;
		$start = ($page - 1) * $limit;
		$data = $this->sendSecretData ( 'SSE', $regBookID, $start, $limit );
		//var_dump($data);
		//die();
		#################################################################
		$i = 6;
		for($j = 0; $j < count ( $data ); $j ++) {
			if ($data [$j] ['speed'] != 0) {
				$speed = $data [$j] ['speed'];
				$speedLabel = $lang ['common'] ['speed'] [$speed];
			} else {
				$speedLabel = "";
			}
			
			if (! $config ['enableReportSpeed']) {
				$speedLabel = "";
			}
			
			$worksheet->write ( "A$i", $data [$j] ['sendNo'] . "\r\n" . $speedLabel, $format3 );
			$worksheet->write ( "B{$i}", $util->getDateString ( $data [$j] ['sendStamp'] ) . ",\r\n" . $util->getTimeString ( $data [$j] ['sendStamp'] ), $format3 );
			//$worksheet->write("B$i",$data[$j]['docNo'],$format3);
			$worksheet->write ( "C$i", $data [$j] ['docNo'], $format3 );
			$worksheet->write ( "D$i", $data [$j] ['docDate'], $format3 );
			$worksheet->write ( "E$i", $data [$j] ['from'], $format3 );
			$worksheet->write ( "F$i", $data [$j] ['to'], $format3 );
			$worksheet->write ( "G$i", $data [$j] ['title'], $format3 );
			$worksheet->write ( "H$i", $data [$j] ['command'], $format3 );
			$worksheet->write ( "I$i", "..........  ..........", $format3 );
			$i ++;
		}
		
		#################################################################
		

		$workbook->close ();
		$util->force_download ( file_get_contents ( $fname ), "sendSecret.xls", 'application/x-msexcel', filesize ( $fname ) );
		
		ob_end_flush ();
	
	}
	
	/**
	 * ��������§ҹ����¹�͡����Ѻ(���͡)
	 *
	 * @param int $regBookID
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	public function sendSecretData($secretType, $regBookID = 0, $start = 0, $limit = 25) {
		global $conn;
		global $config;
		global $sessionMgr;
		global $util;
	
		$filterSQL = Array ( );
		if ($util->isDFTransFiltered ( $secretType )) {
			// Filter Receive Number
			if ($_SESSION ['FilterRecord'] [$secretType] ['sendNo'] != '') {
				if (ereg ( ",", $_SESSION ['FilterRecord'] [$secretType] ['sendNo'] )) {
					$regNoArray = explode ( ",", $_SESSION ['FilterRecord'] [$secretType] ['sendNo'] );
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
					if (ereg ( "-", $_SESSION ['FilterRecord'] [$secretType] ['sendNo'] )) {
						list ( $startR, $stopR ) = split ( "-", $_SESSION ['FilterRecord'] [$secretType] ['sendNo'] );
						$tmpFilterSQL = "";
						$filterSQL [] = " (a.f_send_reg_no >= $startR and a.f_send_reg_no <= $stopR)";
					} else {
						$filterSQL [] = " a.f_send_reg_no = '{$_SESSION['FilterRecord'][$secretType]['sendNo']}'";
						//$tmpFilterSQL = " a.f_recv_reg_no = '{$_SESSION['FilterRecord']['RI']['recvNo']}'";
					}
				}
			
			}
			// Filter Receive Doc.No
			if ($_SESSION ['FilterRecord'] [$secretType] ['docNo'] != '') {
				$filterSQL [] = " c.f_doc_no like '%{$_SESSION['FilterRecord'][$secretType]['docNo']}%'";
			}
			
			$util = new ECMUtility();
			$datefrom = $util->dateToStamp($_SESSION ['FilterRecord'] [$secretType] ['docDateFrom']);
			$dateto = $util->dateToStamp($_SESSION ['FilterRecord'] [$secretType] ['docDateTo']);
			
			// Filter Receive Doc.Date From
			if ($datefrom != '') {
				$filterSQL [] = " c.f_doc_stamp >= {$datefrom} ";
			}

			// Filter Receive Doc.Date To	
			if ($dateto != ''){
				$filterSQL [] = " c.f_doc_stamp <=  {$dateto} ";
			}

			// Filter Receive Title
			if ($_SESSION ['FilterRecord'] [$secretType] ['title'] != '') {
				$filterSQL [] = " c.f_title like '%{$_SESSION['FilterRecord'][$secretType]['title']}%'";
			}
			// Filter Receive Doc.Type
			if ($_SESSION ['FilterRecord'] [$secretType] ['docType'] != '') {
				$filterSQL [] = " a.f_send_doc_type = '{$_SESSION['FilterRecord'][$secretType]['docType']}'";
			}
			
		// Filter From
			if ($_SESSION ['FilterRecord'] [$secretType] ['to'] != '') {
				$filterSQL [] = " a.f_recv_fullname like '%{$_SESSION['FilterRecord'][$secretType]['to']}%'";
			}
			
			//Filter Date Range
			if ($_SESSION ['FilterRecord'] [$secretType] ['sendFromDate'] != '') {
				if($_SESSION['FilterRecord'][$secretType]['sendFromDate'] == $_SESSION['FilterRecord'][$secretType]['sendToDate']) {
					$tmpToDate = $_SESSION['FilterRecord'][$secretType]['sendToDate'] + 86400;
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord'][$secretType]['sendFromDate']}' and a.f_send_stamp <= '{$tmpToDate}'  )";
				} else {
					$filterSQL [] = " (a.f_send_stamp >= '{$_SESSION['FilterRecord'][$secretType]['sendFromDate']}' and a.f_send_stamp <= '{$_SESSION['FilterRecord'][$secretType]['sendToDate']}'  )";
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

		if ($secretType == 'SSI') {
			$sendType = '10';
		} elseif ($secretType == 'SSE') {
			$sendType = '9';
		} else {
			$sendType = '4';
		}

		$sql = "select a.*,b.f_security_modifier,b.f_urgent_modifier,b.f_hold_job,b.f_owner_org_id,b.f_abort  ";
		$sql .= " ,c.f_title,c.f_doc_no,c.f_doc_date,c.f_doc_id";
		$sql .= " ,c.f_gen_int_bookno,c.f_gen_ext_bookno,c.f_gen_ext_type";
		$sql .= " ,c.f_request_order,c.f_request_command,c.f_request_announce";
		$sql .= " from tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c where ";
		$sql .= " a.f_sender_org_id = {$sessionMgr->getCurrentOrgID()}";
		$sql .= " and a.f_reg_book_id = {$regBookID}";
		$sql .= " and a.f_send_type = " . $sendType;
		$sql .= " and a.f_show_in_reg_book = 1";
		$sql .= " and a.f_send_trans_main_id = b.f_trans_main_id";
		$sql .= " and b.f_doc_id = c.f_doc_id ";
		$sql .= " and a.f_send_year = {$sessionMgr->getCurrentYear()} ";

		if ($util->isDFTransFiltered ( $secretType )) {
			$sql .= $realFilter;
		}

		$sql .= " order by a.f_send_reg_no desc";
		
		if ($util->isDFTransFiltered ( $secretType )) {
			//$rs = $conn->SelectLimit ( $sql, $limit, $start );
			$rs = $conn->Execute ( $sql );
		} else {
			$rs = $conn->SelectLimit ( $sql, $limit, $start );
		}

		if (!$rs) return array();

		$sentSecret = Array ();

		while ( $tmp = $rs->FetchRow () ) {
			checkKeyCase ( $tmp );
			//print_r($tmp);
			if (is_null ( $tmp ['f_doc_no'] ) || trim ( $tmp ['f_doc_no'] ) == '') {
				$docNo = "";
			} else {
				$docNo = $tmp ['f_doc_no'];
			}
			
			if (is_null ( $tmp ['f_send_fullname'] ) || trim ( $tmp ['f_send_fullname'] ) == '') {
				$from = "";
			} else {
				$from = $tmp ['f_send_fullname'];
			}
			
			if (is_null ( $tmp ['f_recv_fullname'] ) || trim ( $tmp ['f_recv_fullname'] ) == '') {
				$to = "";
			} else {
				$to = $tmp ['f_recv_fullname'];
			}
			
			$sentSecret [] = Array ('sendID' => trim ( $tmp ['f_send_trans_main_id'] ) . '_' . trim ( $tmp ['f_send_trans_main_seq'] ) . '_' . trim ( $tmp ['f_send_id'] ), 'secret' => $tmp ['f_security_modifier'], 'speed' => $tmp ['f_urgent_modifier'], 'sendNo' => $tmp ['f_send_reg_no'], 'docNo' => $docNo, 'docID' => $tmp ['f_doc_id'], 'title' => stripslashes($tmp ['f_title']), 'docDate' => $tmp ['f_doc_date'], 'from' => $from, 'to' => $to, 'command' => '', 'sendStamp' => $tmp ['f_send_stamp'] );
		}
		return $sentSecret;
		}
		
}
