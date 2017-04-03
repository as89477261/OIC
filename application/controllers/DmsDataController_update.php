<?php
/**
 * โปรแกรมสร้างข้อมูล JSON สำหรับระบบ DMS
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DMS
 */
class DMSDataController extends ECMController {
	private $keySearch;
	private $keySQL;
	
	private $start;
	private $limit;
	
	public $qMode = 'normal';

    private $operators;
	
	/**
	 * action /search-result/ ข้อมูลผลลัพธ์ค้นหา
	 *
	 */
	public function searchResultAction() {
		//		global $config;
		//		global $util;
		

		//		Logger::dump('GET', $_GET);
		//		Logger::dump('POST', $_POST);
		

		$this->start = $_GET ['start'];
		$this->limit = $_GET ['limit'];
		$this->qMode = $_GET ['qMode'];
		
		$resultIndex = Array ();
		$resultForm = Array ();

		// Case Index search
		if ($_GET ['keySearch'] != 'no') {
			//Logger::debug ( 'x1' );
			$this->manageKeySearchIndex ();
            $sql = $this->searchIndexSQL ();
			$resultIndex = $this->searchExecute ( $sql );
			$countIndex = $this->searchCount ( $sql );
			//Logger::dump('resultIndex', $resultIndex);
		}
		
		// Case Form search
		if ($_GET ['keySearch'] == 'no') {
			//Logger::debug ( 'x2' );
			$this->manageKeySearchForm ();
            $sql = $this->searchFormSQL ();
			$resultForm = $this->searchExecute ( $sql );
			$countForm = $this->searchCount ( $sql );
		}
		
		$searchResult = array_merge ( $resultIndex, $resultForm );
		
		$data = json_encode ( $searchResult );
		$count = $countIndex + $countForm;
		
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * ข้อมูลผลลัพธ์การค้นหา Loan
	 *
	 */
	public function searchLoanRequestAction() {
		//		global $config;
		//		global $util;
		

		//		Logger::dump('GET', $_GET);
		//		Logger::dump('POST', $_POST);
		

		$this->start = $_GET ['start'];
		$this->limit = $_GET ['limit'];
		$this->qMode = $_GET ['qMode'];
		
		$resultIndex = Array ();
		$resultForm = Array ();
		
		if ($_GET ['keySearch'] == 'no' && $_GET ['qMode'] == 'searchLoanRequest') {
			$this->manageKeySearchFormPDMO ();
			$resultForm = $this->searchLoanRequestExecute ( $this->searchFormPDMOSQL () );
			$countForm = $this->searchCount ( $this->searchFormPDMOSQL () );
		}
		
		$searchResult = array_merge ( $resultIndex, $resultForm );
		
		$data = json_encode ( $searchResult );
		$count = $countIndex + $countForm;
		
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * จัดการ Key Search
	 *
	 */
	private function manageKeySearchIndex() {
		
		$key = $_GET ['keySearch'];
        preg_match_all ( '/\|(.[^\|\^]*)\^/', $key, $operators );
        $this->operators = $operators [1];
        $key = preg_replace ( '/\|(.[^\|\^]*)\^/', ' ', $key );
        /*Logger::debug ( $key );
		$key = str_replace ( "^", ' ', $key );
		$key = str_replace ( '|and', ' ', $key );
		$key = str_replace ( '|not', ' ', $key );
		$key = str_replace ( '|or', ' ', $key );
		Logger::debug ( $key );
		//$key = UTFDecode($key);*/
		if (strpos ( $key, " " ) > 0) {
			$tmpKey = explode ( " ", $key );
			$tmpKey2 = Array ();
			foreach ( $tmpKey as $key1 ) {
				$tmpKey2 [] = UTFDecode ( $key1 );
			}
			$key = implode ( " ", $tmpKey2 );
		} else {
			$key = UTFDecode ( $key );
		}
		/*$fp = fopen ( "c:/text.txt", "a+" );
		fwrite ( $fp, $key . "\r\n" );
		fclose ( $fp );
		Logger::debug ( $key );*/
		if (strpos ( $key, '*' ) !== false) {
			$this->keySearch = str_replace ( '*', '', $key );
			$this->keySQL = str_replace ( '*', '%', $key );
		} else {
			$this->keySearch = $key;
			$this->keySQL = '%' . $key . '%';
		}
	}
	
	/**
	 * จัดการ Key Search ของ Form
	 *
	 */
	private function manageKeySearchForm() {
		
		$arrFormParam = unserialize ( $_GET ['formParam'] );
		//		Logger::dump('unserialize param',$arrFormParam);
		

		$whereCond = " and b.f_form_id = '{$arrFormParam['formID']} '";
		array_shift ( $arrFormParam );
		
		foreach ( $arrFormParam as $key => $value ) {
			//			$whereCond = "and ";
			if ($value) {
				if (strpos ( $value, '*' ) !== false) { // found
					$value = str_replace ( '*', '%', $value );
				} else {
					$value = '%' . $value . '%';
				}
				$key = str_ireplace ( 'struct_', '', $key );
				//$whereFvalue .= "c.f_struct_id = '$key' and c.f_value like '$value' or ";
				$whereFvalue .= "c.f_struct_id = '$key' and c.f_value like '$value' or ";
			}
		}
		$whereFvalue = substr ( $whereFvalue, 0, - 3 );
		$this->keySQL = $whereCond . " and ($whereFvalue)";
		//		Logger::dump('$whereCond', $this->keySQL);
	}
	
	/**
	 * จัดการ Key Search ของ Form (PDMO)
	 *
	 */
	private function manageKeySearchFormPDMO() {
		
		$arrFormParam = unserialize ( $_GET ['formParam'] );
		//		Logger::dump('unserialize param',$arrFormParam);
		

		$whereCond = " f_form_id = '{$arrFormParam['formID']} '";
		array_shift ( $arrFormParam );
		
		foreach ( $arrFormParam as $key => $value ) {
			//			$whereCond = "and ";
			if ($value) {
				if (strpos ( $value, '*' ) !== false) { // found
					$value = str_replace ( '*', '%', $value );
				} else {
					$value = '%' . $value . '%';
				}
				$key = str_ireplace ( 'struct_', '', $key );
				//$whereFvalue .= "c.f_struct_id = '$key' and c.f_value like '$value' or ";
				$whereFvalue .= "f_struct{$key} like '$value' and ";
			}
		}
		$whereFvalue = substr ( $whereFvalue, 0, - 4 );
		$this->keySQL = $whereCond . " and ($whereFvalue)";
		//		Logger::dump('$whereCond', $this->keySQL);
	}

	function getAllAllowedObject()
	{
		global $conn;
		global $sessionMgr;
		$sql_basic = "SELECT tbl_security_property.f_obj_id, tbl_secure_group_member.f_allow
                                        FROM tbl_security_property,
                                             tbl_secure_group,
                                             tbl_secure_group_member,
											 tbl_dms_object
                                       WHERE tbl_security_property.f_obj_id = tbl_dms_object.f_obj_id
									     AND tbl_dms_object.f_obj_pid = 0
									     AND tbl_security_property.f_security_context_id = tbl_secure_group.f_secure_id(+)
                                         AND tbl_secure_group.f_secure_id = tbl_secure_group_member.f_secure_id(+)
                                         AND (   (    tbl_secure_group_member.f_member_type = 1
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentAccID()}
                                                 )
                                              OR (    tbl_secure_group_member.f_member_type = 2
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentRoleID()}
                                                 )
                                              OR (    tbl_secure_group_member.f_member_type = 3
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentOrgID()}
                                                 ))";
												 logger::debug($sql_basic);
		$rs = $conn->Execute($sql_basic);
		$basic_list = array();
		while ($row = $rs->FetchRow())
			$basic_list[] = $row;
		$basic_ids = $this->extractArrayValue($basic_list, 'F_OBJ_ID');

		$allowed_ids = $unallowed_ids = array();
		foreach ($basic_list as $row)
		{
			if ($row['F_ALLOW'] > 0)
				$allowed_ids[] = $row['F_OBJ_ID'];
			else
				$unallowed_ids[] = $row['F_OBJ_ID'];
		}
		$loop_ids = $allowed_ids;
		$result_ids = array();
		while (count($loop_ids) > 0)
		{				
			// unallow by own right
			$loop_list = $this->splitSelect(array_unique($loop_ids));
			$loop_ids = $this->extractArrayValue($loop_list, 'F_OBJ_ID');
			$loop_ids = array_diff($loop_ids, $unallowed_ids);

			// unallow by object's right
			$allowed_ids = array_merge($allowed_ids, (array) $loop_ids);
		}
		logger::debug($allowed_ids);
		return $allowed_ids;
	}

	function splitSelect($all_ids)
	{
		global $conn;
		$result = array();
		$rounds = array_chunk($all_ids, 900);
		foreach ($rounds as $ids)
		{
			$sql_chunk = 'SELECT tbl_dms_object.f_obj_id FROM  tbl_dms_object WHERE f_obj_pid IN (' . implode(',', $ids) . ')';
			$rs = $conn->Execute($sql_chunk);
			while ($row = $rs->FetchRow())
				$result[] = $row;
		}
		return $result;
	}

	function extractArrayValue($list, $key)
	{
		$result = array();
		foreach ($list as $row)
			$result[] = $row[$key];
		return $result;
	}
	
	/**
	 * ทำการ Generate SQL
	 *
	 * @return string
	 */
	public function searchIndexSQL() {
        global $sessionMgr;
        
		switch ($this->qMode) {
			case 'normal' :
				$arrayKeySearch = split ( " ", $this->keySearch );
                $operators = $this->operators;
                $subQuery = "";

                if (!empty($this->keySearch) && count($arrayKeySearch)) {
                    foreach ($arrayKeySearch as $keySearch) {
                        if ($keySearch != '') {
                            if ($subQuery != '') {
                                if (count ( $operators )) {
                                    $subQuery .= ' ' . strtoupper ( array_shift ( $operators ) ) . ' ';
                                } else {
                                    $subQuery .= ' AND ';
                                }
                            }
                            $subQuery .= "(f_name like '%{$keySearch}%' or f_description like '%{$keySearch}%' or f_keyword like '%{$keySearch}%')";
                        }
                    }

                    $subQuery = "({$subQuery})";
                }


                if (! empty ( $subQuery )) {
					$subQuery .= ' AND f_mark_delete = 0';
				} else {
					$subQuery .= 'f_mark_delete = 0';
				}

				$allowed_ids = $this->getAllAllowedObject();

                $sql = "SELECT *
				FROM tbl_dms_object
				WHERE {$subQuery} AND f_obj_id in (".implode(',',$allowed_ids).")";
				break;
			case 'recyclebin' :
				$sql = "SELECT * 
				FROM tbl_dms_object 
				WHERE f_mark_delete = 1 and f_mark_delete_uid = {$_SESSION['accID']}";
				break;
			case 'expireDocument' :
				$sql = "SELECT * 
				FROM tbl_dms_object 
				WHERE f_mark_delete = 0 and f_expire_stamp > 0 and f_expire_stamp < " . time ();
				break;
			case 'checkout' :
				$sql = "SELECT * 
				FROM tbl_dms_object a, tbl_doc_main b 
				WHERE a.f_doc_id=b.f_doc_id and f_checkout_flag=1";
				break;
		}
		
		Logger::dump('searchIndexSQL', $sql);
		return $sql;
	}
	
	/**
	 * ทำการสร้าง SQL การค้นหาฟอร์ม
	 *
	 * @return string
	 */
	public function searchFormSQL() {
		
		$sql = "SELECT a.*, c.f_doc_id, c.f_struct_id, c.f_value
				FROM tbl_dms_object a, tbl_doc_main b, tbl_doc_value c
				WHERE a.f_doc_id = b.f_doc_id
				and b.f_doc_id = c.f_doc_id
				and a.f_delete = 0
				{$this->keySQL}
			";
		//		Logger::dump('searchFormSQL', $sql);
		return $sql;
	}
	
	/**
	 * ทำการสร้าง SQL ค้นหา Loan Key
	 *
	 * @return string
	 */
	public function searchFormPDMOSQL() {
		
		$sql = "SELECT *
				FROM tv_form_pdmo
				WHERE 
				{$this->keySQL}
			";
		//		Logger::dump('searchFormSQL', $sql);
		return $sql;
	}
	
	/**
	 * ทำการ Execute SQL
	 *
	 * @param string $sql
	 * @return string
	 */
	private function searchExecute($sql) {
		global $conn;
		global $dmsUtil;
		global $util;
		
		//		Logger::dump('searchExecute', $sql);
		$dmsUtil = new DMSUtil ( );
		
		//		Logger::dump('start', $this->start);
		//		Logger::dump('limit', $this->limit);
		$rs = $conn->SelectLimit ( $sql, $this->limit, $this->start );
        $searchResult = array();
		//		Logger::dump('rs', $rs);
		foreach ( $rs as $row ) {
			checkKeyCase ( $row );
			$searchResult [] = Array ('f_obj_id' => $row ['f_obj_id'], 'f_doc_id' => $row ['f_doc_id'], 'f_name' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_name'], $this->keySearch ) ), 'f_description' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_description'], $this->keySearch ) ), 'f_keyword' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_keyword'], $this->keySearch ) ), 'f_obj_type_image' => $dmsUtil->getIndexTypeImage ( $row ['f_obj_type'] ), 'f_obj_type' => UTFEncode ( $dmsUtil->getIndexType ( $row ['f_obj_type'] ) ), 'f_location' => UTFEncode ( $dmsUtil->getIndexLocationName ( $row ['f_obj_id'] ) ), 'f_created_date' => UTFEncode ( $util->getDateString ( $row ['f_created_stamp'] ) ), 'f_created_time' => UTFEncode ( $util->getTimeString ( $row ['f_created_stamp'] ) ), 'f_last_update_date' => UTFEncode ( $util->getDateString ( $row ['f_last_update_stamp'] ) ), 'f_last_update_time' => UTFEncode ( $util->getTimeString ( $row ['f_last_update_stamp'] ) ), 'f_expire_date' => UTFEncode ( $util->getDateString ( $row ['f_expire_stamp'] ) ), 'f_expire_time' => UTFEncode ( $util->getTimeString ( $row ['f_expire_stamp'] ) ) );
		}
		//		Logger::dump('result status', 'yes');
		return $searchResult;
	}
	
	/**
	 * ทำการค้นหา Loan Key
	 *
	 * @param unknown_type $sql
	 * @return unknown
	 */
	private function searchLoanRequestExecute($sql) {
		global $conn;
		global $dmsUtil;
		global $util;
		
		//		Logger::dump('searchExecute', $sql);
		$dmsUtil = new DMSUtil ( );
		
		//		Logger::dump('start', $this->start);
		//		Logger::dump('limit', $this->limit);
		//		logger::dump('sql', $sql);
		$rs = $conn->SelectLimit ( $sql, $this->limit, $this->start );
		//		Logger::dump('rs', $rs);
		$row = $rs->FetchNextObject ();
		$docID = $row->F_DOC_ID;
		
		$sql = "select * from tv_form_pdmo where f_doc_id = {$docID}";
		$rs = $conn->Execute ( $sql );
		//		Logger::dump('rs', $rs);
		foreach ( $rs as $row ) {
			checkKeyCase ( $row );
			$searchResult [] = Array ('f_doc_id' => $row ['f_doc_id'], 'f_form_id' => $row ['f_form_id'], 'f_struct1' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_struct1'], $this->keySearch ) ), 'f_struct2' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_struct2'], $this->keySearch ) ), 'f_struct3' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_struct3'], $this->keySearch ) ), 'f_struct4' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_struct4'], $this->keySearch ) ), 'f_struct5' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_struct5'], $this->keySearch ) ), 'f_struct6' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_struct6'], $this->keySearch ) ), 'f_struct7' => UTFEncode ( $dmsUtil->hilightText ( $row ['f_struct7'], $this->keySearch ) ) );
		}
		//		Logger::dump('result', $searchResult);
		return $searchResult;
	}
	
	/**
	 * จำนวนรายการผลลัพธ์ทั้งหมด
	 *
	 * @param string $sql
	 * @return string
	 */
	private function searchCount($sql) {
		global $conn;
		
		$rsCount = $conn->Execute ( $sql );
		$count = $rsCount->RecordCount ();
		
		return $count;
	}
	
	/**
	 * ข้อมูลการยืม/คืน
	 *
	 * @return string
	 */
	public function borrowAction() {
		global $conn;
		global $dmsUtil;
		global $util;
		global $sessionMgr;
		
		$dmsUtil = new DMSUtil ( );
		
		$sql = "select a.*,b.f_doc_no,b.f_title  ";
		$sql .= "from tbl_borrow_record a,tbl_doc_main b ";
		$sql .= " where f_borrow_uid = '{$sessionMgr->getCurrentAccID()}'";
		$sql .= " and a.f_doc_id = b.f_doc_id";
		$sql .= " and a.f_return_flag != 1";
		
		$rs = $conn->Execute ( $sql );
		$borrowItems = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase ( $row );
			$account = new AccountEntity ( );
			$account->Load ( "f_acc_id = '{$row['f_borrower_uid']}'" );
			
			$borrowItems [] = Array ('borrowID' => $row ['f_borrow_id'], 'docID' => UTFEncode ( $row ['f_doc_id'] ), 'docNo' => UTFEncode ( $row ['f_doc_no'] ), 'title' => UTFEncode ( $row ['f_title'] ), 'borrower' => UTFEncode ( $account->f_name . " " . $account->f_last_name ), 'dueDate' => UTFEncode ( $util->getDateString ( $row ['f_due_date'] ) ), 'detail' => UTFEncode ( $row ['f_detail'] ) );
		}
		
		$data = json_encode ( $borrowItems );
		$count = count ( $borrowItems );
		
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
		exit ();
	}
}
