<?php
/**
 * ��������ҧ������ JSON ����Ѻ�к� DMS
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
	 * action /search-result/ �����ż��Ѿ�����
	 *
	 */
	public function searchResultAction() {
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
	 * �����ż��Ѿ���ä��� Loan
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
	 * �Ѵ��� Key Search
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
	 * �Ѵ��� Key Search �ͧ Form
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
	 * �Ѵ��� Key Search �ͧ Form (PDMO)
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
		$sql_basic = "SELECT tbl_security_property.f_obj_id
                                        FROM tbl_security_property,
                                             tbl_secure_group,
                                             tbl_secure_group_member,
											 tbl_dms_object
                                       WHERE tbl_security_property.f_obj_id = tbl_dms_object.f_obj_id
									     AND tbl_dms_object.f_obj_pid = 0
									     AND tbl_security_property.f_security_context_id = tbl_secure_group.f_secure_id
                                         AND tbl_secure_group.f_secure_id = tbl_secure_group_member.f_secure_id
                                         AND (   (    tbl_secure_group_member.f_member_type = 1
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentAccID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 )
                                              OR (    tbl_secure_group_member.f_member_type = 2
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentRoleID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 )
                                              OR (    tbl_secure_group_member.f_member_type = 3
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentOrgID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 ))";
		//logger::debug($sql_basic);
		$rs = $conn->Execute($sql_basic);

		$basic_list = array();
		while ($row = $rs->FetchRow())
			$basic_list[] = $row;
		$basic_ids = $this->extractArrayValue($basic_list, 'F_OBJ_ID');
		
		$allowed_ids = array();
		foreach ($basic_list as $row)
		{
			$allowed_ids[] = $row['F_OBJ_ID'];
		}

		$loop_ids = $allowed_ids;
		$result_ids = array();
		while (count($loop_ids) > 0)
		{				
			$loop_list = $this->getChildByParent(array_unique($loop_ids));
			$loop_ids = $this->extractArrayValue($loop_list, 'F_OBJ_ID');
			$allowed_ids = array_merge($allowed_ids, (array) $loop_ids);
		}
		//logger::debug($allowed_ids);
		return $allowed_ids;
	}

	function splitSelect($sql, $ids, $chunksize = 900)
	{	
		global $conn;
		$result = array();
		if ( !is_array($ids) || count($ids) < 1 ) return $result;
		if ( count($ids) < $chunksize ) $chunksize = count($ids);
		do
		{
			$subids = array_slice( $ids, 0, $chunksize );
			$ids = array_diff( $ids, $subids );
			$result[] = sprintf( $sql, implode( ',', $subids ) );
		} while( count( $ids ) > 0 );
		return (count($result) < 1)? false : 'SELECT %s FROM ('.implode(') UNION ALL (',$result).')';
	}

	function getChildByParent($all_ids, $listsize = 900)
	{
		global $conn;
		global $sessionMgr;
		$result = array();
		if (!is_array($all_ids) || count($all_ids) < 1 ) return $result;
		if ( count($all_ids) < $listsize )
		{
				$listsize = count($all_ids);
		}

		do
		{
			$ids = array_slice($all_ids,0,$listsize);
			$all_ids = array_diff($all_ids,$ids);
			$sql = 'SELECT tbl_dms_object.f_obj_id
					FROM tbl_security_property, tbl_secure_group, tbl_secure_group_member, tbl_dms_object
					WHERE tbl_security_property.f_obj_id = tbl_dms_object.f_obj_id
					AND tbl_dms_object.f_obj_pid in (' . implode(',', $ids) . ')
					AND tbl_security_property.f_security_context_id = tbl_secure_group.f_secure_id
					AND tbl_secure_group.f_secure_id = tbl_secure_group_member.f_secure_id
					AND (   (    tbl_secure_group_member.f_member_type = 1
						AND tbl_secure_group_member.f_member_id = (' . $sessionMgr->getCurrentAccID() . ')
						AND tbl_secure_group_member.f_allow = 1
					)
					OR (    tbl_secure_group_member.f_member_type = 2
						AND tbl_secure_group_member.f_member_id = (' . $sessionMgr->getCurrentRoleID() . ')
						AND tbl_secure_group_member.f_allow = 1
					   )
					OR (    tbl_secure_group_member.f_member_type = 3
						AND tbl_secure_group_member.f_member_id = (' . $sessionMgr->getCurrentOrgID() . ')
						AND tbl_secure_group_member.f_allow = 1
					   )
				   )';

			$rs = $conn->Execute($sql);
			while ($row = $rs->FetchRow())
				$result[] = $row;
			
			$sql = 'SELECT DISTINCT tbl_dms_object.f_obj_id 
						FROM tbl_dms_object
						WHERE NOT EXISTS (SELECT tbl_security_property.f_obj_id FROM tbl_security_property WHERE tbl_dms_object.f_obj_id = tbl_security_property.f_obj_id)
						AND tbl_dms_object.f_obj_pid IN (' . implode(',', $ids) . ')';

			$rs = $conn->Execute($sql);
			while ($row = $rs->FetchRow())
				$result[] = $row;
		} while(count($all_ids) > 0);
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
	 * �ӡ�� Generate SQL
	 *
	 * @return string
	 */
	public function searchIndexSQL( ) {
        global $sessionMgr;
        
		switch ($this->qMode) {
			case 'normal' :
				$arrayKeySearch = split ( " ", $this->keySearch );
                $operators = $this->operators;
				$where = '';

				$util = new ECMUtility ();

				/**
				 * ���ҵ���������͡���
				 */
				foreach ( $arrayKeySearch as $keySearch ) {
					if ($keySearch != '') {
						if ($where != '') {
							if (count ( $operators )) {
								$where .= ' ' . strtoupper ( array_shift ( $operators ) ) . ' ';
							} else {
								$where .= ' AND ';
							}
						}

						$subQuery = '';

						/**
						 * �͡���
						 */
						if ('true' == $_GET ['document']) {
							$arr = array ();

							$tmp = "
								EXISTS (
						          SELECT tbl_doc_main.f_doc_id
						            FROM tbl_doc_main, tbl_form_structure, tbl_doc_value
						           WHERE tbl_dms_object.f_doc_id = tbl_doc_main.f_doc_id
						             AND tbl_doc_main.f_form_id = tbl_form_structure.f_form_id
						             AND tbl_doc_main.f_doc_id = tbl_doc_value.f_doc_id
						             AND tbl_form_structure.f_struct_id = tbl_doc_value.f_struct_id
						             AND tbl_form_structure.%s = 1
						             AND LOWER(tbl_doc_value.f_value) like LOWER('%%%s%%'))";

							if ('true' == $_GET ['name']) {
								$arr [] = sprintf ( $tmp, 'f_is_title', $keySearch );
							}

							if ('true' == $_GET ['description']) {
								$arr [] = sprintf ( $tmp, 'f_is_desc', $keySearch );
							}

							if ('true' == $_GET ['keyword']) {
								$arr [] = sprintf ( $tmp, 'f_is_keyword', $keySearch );
							}

							if (count ( $arr )) {
								$subQuery .= '(f_obj_type = 1 AND ' . implode ( ' OR ', $arr ) . ')';
							}
						}

						/**
						 * ����͡��� ���� Shortcut
						 */
						if ('true' == $_GET ['folder'] || 'true' == $_GET ['shortcut']) {
							$arr = array ();

							if ('true' == $_GET ['name']) {
								$arr [] = "LOWER(f_name) like LOWER('%{$keySearch}%')";
							}

							if ('true' == $_GET ['description']) {
								$arr [] = "LOWER(f_description) like LOWER('%{$keySearch}%')";
							}

							if ('true' == $_GET ['keyword']) {
								$arr [] = "LOWER(f_keyword) like LOWER('%{$keySearch}%')";
							}

							if (count ( $arr )) {
								$type = array ();

								if ('true' == $_GET ['folder']) {
									$type [] = 0;
								}

								if ('true' == $_GET ['shortcut']) {
									$type [] = 2;
								}

								if (! empty ( $subQuery )) {
									$subQuery .= ' OR (f_obj_type IN (' . implode ( ', ', $type ) . ') AND (' . implode ( ' OR ', $arr ) . '))';
								} else {
									$subQuery .= '(f_obj_type IN (' . implode ( ', ', $type ) . ') AND (' . implode ( ' OR ', $arr ) . '))';
								}
							}
						}

						if (! empty ( $subQuery )) {
							$where .= '(' . $subQuery . ')';
						}
					}
				} // end of foreach
				
				/**
				 * ����੾���͡��÷�����١ź
				 */
                if (! empty ( $where )) {
					$where .= ' AND f_mark_delete = 0';
				} else {
					$where .= 'f_mark_delete = 0';
				}

				/**
				 * ���ҵ�����觤Ӥ�
				 */
				$type = array ('null' );

				if ('true' == $_GET ['folder']) {
					$type [] = 0;
				}

				if ('true' == $_GET ['document']) {
					$type [] = 1;
				}

				if ('true' == $_GET ['shortcut']) {
					$type [] = 2;
				}

				$where .= ' AND f_obj_type IN (' . implode ( ', ', $type ) . ')';

				/**
				 * ���ҵ���ѹ������ҧ
				 */
				if (! empty ( $_GET ['startDateFrom'] ) || ! empty ( $_GET ['startDateTo'] )) {
					$startDateFrom = $util->dateToStamp ( UTFDecode ( $_GET ['startDateFrom'] ) );
					$startDateTo = $util->dateToStamp ( UTFDecode ( $_GET ['startDateTo'] ) );
					$startDateTo = strtotime ( '+1 day', $startDateTo );

					if (! empty ( $_GET ['startDateFrom'] ) && ! empty ( $_GET ['startDateTo'] )) {
						$where .= " AND f_created_stamp BETWEEN {$startDateFrom} AND {$startDateTo}";
					} elseif (! empty ( $_GET ['startDateFrom'] )) {
						$where .= " AND f_created_stamp > {$startDateFrom}";
					} elseif (! empty ( $_GET ['startDateTo'] )) {
						$where .= " AND f_created_stamp < {$startDateTo}";
					}
				}
				$allowed_ids = $this->getAllAllowedObject();
				if ( $allowed_ids ) {
					$allowids = $this->splitSelect( 'SELECT f_obj_id FROM tbl_dms_object WHERE f_obj_id in (%s)', $allowed_ids );
					$subQuery = sprintf($allowids, 'f_obj_id');
				} else {
					$subQuery = 0;
				}

                $sql = "SELECT *
				FROM tbl_dms_object
				WHERE {$where} AND f_obj_id in (".$subQuery.")";
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
	 * �ӡ�����ҧ SQL ��ä��ҿ����
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
	 * �ӡ�����ҧ SQL ���� Loan Key
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
	 * �ӡ�� Execute SQL
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
	 * �ӡ�ä��� Loan Key
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
	 * �ӹǹ��¡�ü��Ѿ�������
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
	 * �����š�����/�׹
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
