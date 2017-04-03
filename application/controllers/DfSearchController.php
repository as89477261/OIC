<?php
/**
 * โปรแกรมค้นหาข้อมูลงานสารบรรณ
 * 
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 * 
 *
 */

class DFSearchController extends ECMController {

	function getMinMaxSeqArray(&$Src, &$MinVal, &$MaxVal, $keyname)
	{
		$MinVal = array();
		$MaxVal = array();
		foreach ($Src as $row)
		{
			checkKeyCase($row);
			// min
			if (!array_key_exists($row[strtolower($keyname)], $MinVal))
				$MinVal[$row[strtolower($keyname)]] = $row;
			// max
			$MaxVal[$row[strtolower($keyname)]] = $row;
		}
	}
    /**
     * action /executive-search/ โปรแกรมค้นหาข้อมูลทะเบียนรวม
     *
     */
    public function executiveSearchAction() {
        global $config;
        global $conn;
		global $util;
		global $sessionMgr;

        $limit = $_GET['limit'];
        $start = $_GET['start'];
        if(array_key_exists('query',$_GET)) {
            $query = UTFDecode($_GET['query']);
            //setcookie('query',$query,3600,'/');
            $_COOKIE['query'] = $query;
        } else {
            if(array_key_exists('query',$_COOKIE)){
                $query = $_COOKIE['query'];
            } else {
                $query = '';
            }
        }
        
        if($query == '') {
            $books = Array();
            $count = count ( $books );                                                                                                                                                                                                                                                                                                                                                                                
            $data = json_encode ( $books );
            $cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
            print $cb . '({"total":"' . $count . '","results":' . $data . '})';
        } else {        
            $sqlCount = "select count(*) as COUNT_EXP
            from tbl_doc_main a, tbl_trans_master_df b where a.f_doc_id = b.f_doc_id
            and (a.f_title like '%{$query}%' or a.f_doc_no like '%{$query}%' or a.f_doc_date like '%{$query}%')";
            
            $rsCount = $conn->Execute($sqlCount);
            $tmpCount = $rsCount->FetchNextObject();

            $sql = "SELECT  b.f_trans_main_id, a.f_doc_id, a.f_title, a.f_doc_no, a.f_doc_date, a.f_doc_stamp, a.f_gen_int_bookno,
							a.f_gen_ext_bookno, a.f_gen_ext_type, a.f_request_order, a.f_request_command, a.f_request_announce,
							b.f_security_modifier, b.f_urgent_modifier, b.f_hold_job, b.f_start_type
						FROM tbl_doc_main a, tbl_trans_master_df b
						WHERE a.f_doc_id = b.f_doc_id
							AND b.f_trans_year = {$sessionMgr->getCurrentYear()}
							AND (a.f_title like '%{$query}%' OR a.f_doc_no like '%{$query}%' OR a.f_doc_date like '%{$query}%')
						ORDER BY b.f_start_stamp DESC";

            $rs = $conn->getall ( $sql );

			foreach ( $rs as $row ) {
				$trans_main_ids[] = $row['F_TRANS_MAIN_ID'];
			}

			if ( $trans_main_ids ) {
				$sql_recipients = 'SELECT f_recv_trans_main_id as f_trans_main_id, f_send_fullname, f_attend_fullname as f_recv_fullname FROM tbl_trans_df_recv WHERE f_recv_trans_main_id IN (' . implode(',', $trans_main_ids).') order by f_recv_trans_main_id asc, f_recv_trans_main_seq asc';
				$recipients = $conn->getall($sql_recipients);
				
				$sql_senders = 'SELECT f_send_trans_main_id as f_trans_main_id, f_send_fullname, f_recv_fullname FROM tbl_trans_df_send WHERE f_send_trans_main_id IN (' . implode(',', $trans_main_ids).') order by f_send_trans_main_id asc, f_send_trans_main_seq asc';
				$senders = $conn->getall($sql_senders);

				$recvStartTran= array();
				$recvEndTran = array();
				$sendStartTran = array();
				$sendEndTran = array();
				$this->getMinMaxSeqArray($recipients, $recvStartTran, $recvEndTran , F_TRANS_MAIN_ID);
				$this->getMinMaxSeqArray($senders, $sendStartTran, $sendEndTran , F_TRANS_MAIN_ID);
			}

            $books = Array ();
            foreach ( $rs as $row ) {
                checkKeyCase($row);

                if ($util->docUtil->hasAttach ( $row ['f_doc_id'] )) {
                    $hasAttach = 1;
                } else {
                    $hasAttach = 0;
                }

                if ($row ['f_hold_job'] != 1 || is_null ( $row ['f_hold_job'] )) {
                    $hold = 0;
                } else {
                    $hold = 1;
                }
				$tranMainId = $row['f_trans_main_id'];
                switch($row['f_start_type']) {
                    case 'RI':
                        $originalType = 'รับภายใน';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'RE':
                        $originalType = 'รับภายนอก';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $recvStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'RG':
                        $originalType = 'รับภายนอกทะเบียนกลาง';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $recvStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'RS':
                        $originalType = 'รับภายนอกทะเบียนลับ';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $recvStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'RC':
                        $originalType = 'รับเวียน';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'SI':
                        $originalType = 'ส่งภายใน';
						( $recvEndTran[$tranMainId] ) ? $recvTranID = $recvEndTran[$tranMainId] : $recvTranID = $sendStartTran[$tranMainId];
						$recvName = UTFEncode ( $recvTranID['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'SE':
                        $originalType = 'ส่งภายนอก';
						$recvName = UTFEncode ( $sendStartTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'SG':
                        $originalType = 'ส่งภายนอกทะเบียนกลาง';
						$recvName = UTFEncode ( $sendStartTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'SS':
                        $originalType = 'ส่งภายนอกทะเบียนลับ';
						$recvName = UTFEncode ( $sendStartTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'SC':
                        $originalType = 'ส่งเวียน';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'SSE':
                        $originalType = 'ส่งภายนอก(ลับ)';
						$recvName = UTFEncode ( $sendStartTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'SSI':
                        $originalType = 'ส่งภายใน(ลับ)';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'RSE':
                        $originalType = 'รับภายนอก(ลับ)';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $recvStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
                    case 'RSI':
                        $originalType = 'รับภายใน(ลับ)';
						$recvName = UTFEncode ( $recvEndTran[$tranMainId]['f_recv_fullname'] );
						$sendName = UTFEncode ( $sendStartTran[$row['f_trans_main_id']]['f_send_fullname'] );
                        break;
				}

                $books [] = Array (
                    'transID' =>$row['f_trans_main_id'].'_1_1',
                    'docID' =>$row['f_doc_id'], 
                    'docNo' =>UTFEncode($row['f_doc_no']), 
                    'title' =>UTFEncode($row['f_title']),
                    'bookdate' =>UTFEncode($row['f_doc_date']),
                    'originalType' =>UTFEncode($originalType),
                    'originate' =>'',
                    'speed' =>$row['f_urgent_modifier'],
                    'secret' =>$row['f_security_modifier'],
                    'hasAttach' => $hasAttach,
                    'hold'=>$hold,
                    'genIntBookno' => $row ['f_gen_int_bookno'], 
                    'genExtBookno' => $row ['f_gen_ext_bookno'], 
                    'genExtType' => $row ['f_gen_ext_type'], 
                    'requestOrder' => $row ['f_request_order'], 
                    'requestCommand' => $row ['f_request_command'], 
                    'requestAnnounce' => $row ['f_request_announce'],
					'sendName' => $sendName,
					'recvName' => $recvName
                );
				//print_r($books);
			}
            
            $count = $tmpCount->COUNT_EXP;
            //$ranks = Array (array ('id' => 1, 'name' => 'Rank 1', 'description' => '', 'level' => '1', 'status' => '1' ), array ('id' => 2, 'name' => 'Rank 2', 'description' => '', 'level' => '2', 'status' => '2' ), array ('id' => 3, 'name' => 'Rank 3', 'description' => '', 'level' => '3', 'status' => '3' ), array ('id' => 4, 'name' => 'Rank 4', 'description' => '', 'level' => '4', 'status' => '4' ) );
            $data = json_encode ( $books );
            $cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
            print $cb . '({"total":"' . $count . '","results":' . $data . '})';
        }
    }
}