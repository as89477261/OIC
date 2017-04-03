<?php
/**
 * โปรแกรมส่งข้อมูลแบบ JSON
 * 
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */
class DataStoreController extends ECMController {
	/**
	 * ไม่มีการทำงาน
	 *
	 */
	public function indexAction() {
		// No Index Action
	}
    
	/**
	 * action /announce-type/ ข้อมูลประเภทคำสั่ง/ประกาศ
	 *
	 */
    public function announceTypeAction() {
        global $config;
        global $conn;
        
        $sql = "select * from tbl_rank order by f_rank_level asc";
        Logger::debug('loading data from tbl_rank use query: '.$sql);
        $rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
        $ranks = Array ();
        foreach ( $rs as $row ) {
            checkKeyCase($row);
            $ranks [] = Array ('id' => $row ['f_rank_id'], 'name' => UTFEncode ( $row ['f_rank_name'] ), 'description' => UTFEncode ( $row ['f_rank_desc'] ), 'level' => $row ['f_rank_level'], 'status' => $row ['f_rank_status'] );
        }
        
        $count = count ( $ranks );
        //$ranks = Array (array ('id' => 1, 'name' => 'Rank 1', 'description' => '', 'level' => '1', 'status' => '1' ), array ('id' => 2, 'name' => 'Rank 2', 'description' => '', 'level' => '2', 'status' => '2' ), array ('id' => 3, 'name' => 'Rank 3', 'description' => '', 'level' => '3', 'status' => '3' ), array ('id' => 4, 'name' => 'Rank 4', 'description' => '', 'level' => '4', 'status' => '4' ) );
        $data = json_encode ( $ranks );
        $cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
        print $cb . '({"total":"' . $count . '","results":' . $data . '})';
    }
	
    /**
     * action /rank/ ข้อมูลระดับขั้น
     *
     */
	public function rankAction() {
		global $config;
		global $conn;
		
		$sql = "select * from tbl_rank order by f_rank_level asc";
		//Logger::debug('loading data from tbl_rank use query: '.$sql);
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$ranks = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$ranks [] = Array ('id' => $row ['f_rank_id'], 'name' => UTFEncode ( $row ['f_rank_name'] ), 'description' => UTFEncode ( $row ['f_rank_desc'] ), 'level' => $row ['f_rank_level'], 'status' => $row ['f_rank_status'] );
		}
		
		$count = count ( $ranks );
		//$ranks = Array (array ('id' => 1, 'name' => 'Rank 1', 'description' => '', 'level' => '1', 'status' => '1' ), array ('id' => 2, 'name' => 'Rank 2', 'description' => '', 'level' => '2', 'status' => '2' ), array ('id' => 3, 'name' => 'Rank 3', 'description' => '', 'level' => '3', 'status' => '3' ), array ('id' => 4, 'name' => 'Rank 4', 'description' => '', 'level' => '4', 'status' => '4' ) );
		$data = json_encode ( $ranks );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /concurrent/ ข้อมูล concurrent
	 *
	 */
	public function concurrentAction() {
		global $config;
		global $conn;
		$limit = $_GET ['limit'];
		$offset = $_GET ['start'];
		//$sortID = $_GET ['sort'];
		//$sortDir = $_GET ['dir'];
        
        if (! array_key_exists ( 'sort', $_GET )) {
            $sortID = 'name';
        } else {
            $sortID = $_GET ['sort'];
        }
        
        if (! array_key_exists ( 'dir', $_GET )) {
            $sortDir = 'ASC';
        } else {
            $sortDir = $_GET ['dir'];  
        }
        
        if (array_key_exists ( 'filter', $_GET )) {
            $filtervalue = UTFDecode ( $_GET ['filter'] [0] ['data'] ['value'] );
            $filterQuery = " and (b.f_name like '%$filtervalue%'";
            $filterQuery .= " or b.f_mid_name like '%$filtervalue%'";
            $filterQuery .= " or b.f_last_name like '%$filtervalue%')";
        } else {
            $filterQuery = "";
        }
                                                  
        switch ($sortID) {
            case 'id' :               
            default : 
                $sortQuery = "order by b.f_first_access {$sortDir}";
                break;
            case 'name' :
                $sortQuery = "order by b.f_name {$sortDir},b.f_mid_name {$sortDir},b.f_last_name {$sortDir}";
                break;
            case 'ipaddress' :
                $sortQuery = "order by a.f_ip_address {$sortDir}";
                break;
            case 'firstaccess' :
                $sortQuery = "order by a.f_first_access {$sortDir}";
                break;
            case 'lastaccess' :
                $sortQuery = "order by a.f_last_access {$sortDir}";
                break;     
        }

		$sqlCountTotal = "select count(f_acc_id) as count_recs from tbl_concurrent";
		$rsCountRecs = $conn->Execute ( $sqlCountTotal );
		$tmpCountRecs = $rsCountRecs->FetchNextObject ();
        //if(array_key_exists('',$_GET)) {
        //}
		
		$sql = "select a.f_ip_address,a.f_first_access,a.f_last_access,b.* from tbl_concurrent a,tbl_account b where b.f_acc_id = a.f_acc_id {$filterQuery} {$sortQuery}";
		$rs = $conn->CacheSelectLimit ( $config ['defaultCacheSecs'], $sql, $limit, $offset );
		$concurrent = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$concurrent [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . ' ' . $row ['f_last_name'] ), 'ipaddress' => UTFEncode ( $row ['f_ip_address'] ), 'firstaccess' => date ( "d/m/Y, H:i:s ", $row ['f_first_access'] ), 'lastaccess' => date ( "d/m/Y, H:i:s ", $row ['f_last_access'] ) );
		}
		$count = $tmpCountRecs->COUNT_RECS;
		$data = json_encode ( $concurrent );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	
	}
	
	/**
	 * action /form/ ข้อมูลรายการ Form
	 *
	 */
	public function formAction() {
		global $config;
		global $conn;
		
		if (array_key_exists ( 'limit', $_GET )) {
			$limit = $_GET ['limit'];
		} else {
			$limit = 25;
		}
		
		if (array_key_exists ( 'start', $_GET )) {
			$offset = $_GET ['start'];
		} else {
			$offset = 0;
		}
		
		//$offset = $_GET ['start'];
		//$sortID = $_GET ['sort'];
		//$sortDir = $_GET ['dir'];
		

		$sqlCountTotal = "select count(f_form_id) as count_recs from tbl_form";
		$rsCountRecs = $conn->Execute ( $sqlCountTotal );
		$tmpCountRecs = $rsCountRecs->FetchNextObject ();
		
		$sql = "select * from tbl_form order by f_form_id asc";
		$rs = $conn->CacheSelectLimit ( $config ['defaultCacheSecs'], $sql, $limit, $offset );
		$forms = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$forms [] = Array ('id' => $row ['f_form_id'], 'name' => UTFEncode ( $row ['f_form_name'] ), 'description' => UTFEncode ( $row ['f_form_desc'] ), 'version' => $row ['f_version'], 'status' => $row ['f_status'], 'allowwf' => $row ['f_allow_wf'], 'allowdms' => $row ['f_allow_dms'], 'allowdf' => $row ['f_allow_df'], 'allowkb' => $row ['f_allow_kb'], 'allowcomment' => $row ['f_allow_comment'], 'allowattach' => $row ['f_allow_attach'] );
		}
		
		$count = $tmpCountRecs->COUNT_RECS;
		$data = json_encode ( $forms );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /form-no-limit/ ข้อมูลรายการฟอร์มทั้งหมด
	 *
	 */
	public function formNoLimitAction() {
		global $config;
		global $conn;
		
		//$limit = $_GET ['limit'];
		//$offset = $_GET ['start'];
		//$sortID = $_GET ['sort'];
		//$sortDir = $_GET ['dir'];

		$sqlCountTotal = "select count(f_form_id) as count_recs from tbl_form";
		$rsCountRecs = $conn->Execute ( $sqlCountTotal );
		$tmpCountRecs = $rsCountRecs->FetchNextObject ();
		
		$sql = "select * from tbl_form order by f_form_id asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$forms = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$forms [] = Array ('id' => $row ['f_form_id'], 'name' => UTFEncode ( $row ['f_form_name'] ), 'description' => UTFEncode ( $row ['f_form_desc'] ), 'version' => $row ['f_version'], 'status' => $row ['f_status'], 'allowwf' => $row ['f_allow_wf'], 'allowdms' => $row ['f_allow_dms'], 'allowdf' => $row ['f_allow_df'], 'allowkb' => $row ['f_allow_kb'], 'allowcomment' => $row ['f_allow_comment'], 'allowattach' => $row ['f_allow_attach'] );
		}
		
		$count = $tmpCountRecs->COUNT_RECS;
		$data = json_encode ( $forms );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /form-list-saraban/ ข้อมูลรายการฟอร์มงานสารบรรณ
	 *
	 */
	public function formListSarabanAction() {
		global $config;
		global $conn;
		
		$sqlCountTotal = "select count(f_form_id) as count_recs from tbl_form where f_allow_df = '1'";
		$rsCountRecs = $conn->Execute ( $sqlCountTotal );
		$tmpCountRecs = $rsCountRecs->FetchNextObject ();
		
		$sql = "select 
		f_form_id,
		f_form_name,
		f_form_desc,
		f_version,
		f_status,
		f_allow_wf,
		f_allow_dms,
		f_allow_df,
		f_allow_kb,
		f_allow_comment,
		f_allow_attach
		 from tbl_form  where f_allow_df = '1' order by f_form_id asc";
		
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$forms = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$forms [] = Array ('id' => $row ['f_form_id'], 
			'name' => UTFEncode ( $row ['f_form_name'] ), 
			'description' => UTFEncode ( $row ['f_form_desc'] ), 
			'version' => $row ['f_version'],
			 'status' => $row ['f_status'], 
			 'allowwf' => $row ['f_allow_wf'], 
			 'allowdms' => $row ['f_allow_dms'], 
			 'allowdf' => $row ['f_allow_df'], 
			 'allowkb' => $row ['f_allow_kb'], 
			 'allowcomment' => $row ['f_allow_comment'], 
			 'allowattach' => $row ['f_allow_attach'] );
		}
		
		$count = $tmpCountRecs->COUNT_RECS;
		$data = json_encode ( $forms );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /form-list-dms/ ข้อมูลฟอร์มเอกสารงาน DMS
	 *
	 */
	public function formListDmsAction() {
		global $config;
		global $conn;
		
		//$limit = $_GET ['limit'];
		//$offset = $_GET ['start'];
		//$sortID = $_GET ['sort'];
		//$sortDir = $_GET ['dir'];
		

		$sqlCountTotal = "select count(f_form_id) as count_recs from tbl_form where f_allow_df = '1'";
		$rsCountRecs = $conn->Execute ( $sqlCountTotal );
		$tmpCountRecs = $rsCountRecs->FetchNextObject ();
		
		$sql = "select * from tbl_form where f_allow_dms = 1 order by f_form_id asc";
		
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$forms = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$forms [] = Array ('id' => $row ['f_form_id'], 'name' => UTFEncode ( $row ['f_form_name'] ), 'description' => UTFEncode ( $row ['f_form_desc'] ), 'version' => $row ['f_version'], 'status' => $row ['f_status'], 'allowwf' => $row ['f_allow_wf'], 'allowdms' => $row ['f_allow_dms'], 'allowdf' => $row ['f_allow_df'], 'allowkb' => $row ['f_allow_kb'], 'allowcomment' => $row ['f_allow_comment'], 'allowattach' => $row ['f_allow_attach'] );
		}
		
		$count = $tmpCountRecs->COUNT_RECS;
		$data = json_encode ( $forms );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /position/ ข้อมูลระดับตำแหน่ง
	 *
	 */
	public function positionAction() {
		global $config;
		global $conn;
		$sql = "select * from tbl_position_master order by f_pos_level asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$positions = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$positions [] = Array ('id' => $row ['f_pos_id'], 'name' => UTFEncode ( $row ['f_pos_name'] ), 'description' => UTFEncode ( $row ['f_pos_desc'] ), 'level' => $row ['f_pos_level'], 'status' => $row ['f_pos_status'] );
		}
		
		$count = count ( $positions );
		$data = json_encode ( $positions );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /policy/ ข้อมูลรายการนโยบาย
	 *
	 */
	public function policyAction() {
		global $config;
		global $conn;
		$sql = "select f_gp_id,f_gp_name,f_gp_desc,f_gp_status from tbl_group_policy order by f_gp_name asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$ranks = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$ranks [] = Array ('id' => $row ['f_gp_id'], 'name' => UTFEncode ( $row ['f_gp_name'] ), 'description' => UTFEncode ( $row ['f_gp_desc'] ), 'status' => $row ['f_gp_status'] );
		}
		
		$count = count ( $ranks );
		$data = json_encode ( $ranks );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /policy-property/ ข้อมูลรายละเอียดนโยบาย
	 *
	 */
	public function policyPropertyAction() {
		global $config;
		global $conn;
		global $store;
		$policyID = $_GET ['policyID'];
		$sql = "select * from tbl_group_policy where f_gp_id = '$policyID'";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$policyRec = $rs->FetchNextObject ();
		$policyProperty = Array ();
		$propertyLang = $store->getPolicyProperty ( 'en' );
		foreach ( $policyRec as $key => $value ) {
			$key = strtolower ( $key );
            
			if (! in_array ( $key, array ('f_gp_id', 'f_gp_name', 'f_gp_desc', 'f_gp_status' ) )) {
                $tmpValue = $value;
				if (! in_array ( $key, array ('f_wf_secret_lvl', 'f_sb_secret_lvl' ) )) {
					if ($value == 0 || is_null ( $value )) {
						$value = 'false';
					} else {
						$value = 'true';
					}
				}
				$key = strtolower ( $key );
                $defaultEditor = 'boolean';
                if (in_array ($key, array ('f_quota'))) {
                    $defaultEditor = 'text';
                    $value  = (int)$tmpValue;
                }
				$policyProperty [] = Array ('id' => $store->getPolicyMapping ( $key, 'field' ), 'Name' => $propertyLang [$key], 'Value' => $value, 'Group' => $store->getPolicyMappingPropertyGroup ( $key ), 'EditorType' => $defaultEditor );
                //$storageProperty [] = Array ('id' => $store->getStorageMapping ( $key, 'field' ), 'Name' => $propertyLang [$key], 'Value' => $value, 'Group' => 0, 'EditorType' => $propertyEditor [$key] );
			}
		}
		/*foreach ( $rs as $row ) {
			$positions [] = Array ('id' => $row ['f_pos_id'], 'name' => UTFEncode( $row ['f_pos_name'] ), 'description' => UTFEncode( $row ['f_pos_desc'] ), 'level' => $row ['f_pos_level'], 'status' => $row ['f_pos_status'] );
		}*/
		/*$count = count ( $positions );
		 * 
		$data = json_encode ( $positions );*/
		$count = count ( $policyProperty );
		$data = json_encode ( $policyProperty );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /policy-property2/ ข้อมูลรายละเอียดนโยบาย
	 *
	 */
	public function policyProperty2Action() {
		global $config;
		global $conn;
		global $store;
		$policyID = $_GET ['policyID'];
		$sql = "select * from tbl_group_policy where f_gp_id = '$policyID'";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$policyRec = $rs->FetchNextObject ();
		$policyProperty = Array ();
		$propertyLang = $store->getPolicyProperty ( 'en' );
		foreach ( $policyRec as $key => $value ) {
			$key = strtolower ( $key );
			if (! in_array ( $key, array ('f_gp_id', 'f_gp_name', 'f_gp_desc', 'f_gp_status' ) )) {
				$key = strtolower ( $key );
                $tmpValue = $value;
                $defaultEditor = 'boolean';
				if (! in_array ( $key, array ('f_wf_secret_lvl', 'f_sb_secret_lvl' ) )) {
					if ($value == 0 || is_null ( $value )) {
						$value = false;
					} else {
						$value = true;
					}
				} else {
					$value = $value;
					$defaultEditor = 'secretEditor';
				}
				
                if (in_array ($key, array ('f_quota'))) {
                    $defaultEditor = 'text';
                    $value  = (int)$tmpValue;
                }
                if($defaultEditor == 'text') {
                	//$defaultEditor = '';
                }
                if($defaultEditor == 'boolean') {
                	$policyProperty [] = Array ('id' => $store->getPolicyMapping ( $key, 'field' ), 'name' => UTFEncode($propertyLang [$key]),'text' => UTFEncode($propertyLang [$key]), 'value' => (bool)$value, 'group' => UTFEncode($store->getPolicyMappingPropertyGroup ( $key )) );
                } elseif($defaultEditor == 'text') {
                	$policyProperty [] = Array ('id' => $store->getPolicyMapping ( $key, 'field' ), 'name' => UTFEncode($propertyLang [$key]),'text' => UTFEncode($propertyLang [$key]), 'value' => $value, 'group' => UTFEncode($store->getPolicyMappingPropertyGroup ( $key )) );
                } elseif($defaultEditor == 'secretEditor') {   
                	$policyProperty [] = Array ('id' => $store->getPolicyMapping ( $key, 'field' ), 'name' => UTFEncode($propertyLang [$key]),'text' => UTFEncode($propertyLang [$key]), 'value' => $value, 'group' => UTFEncode($store->getPolicyMappingPropertyGroup ( $key )), 'editor' => $defaultEditor );
                } else{            
                	$policyProperty [] = Array ('id' => $store->getPolicyMapping ( $key, 'field' ), 'name' => UTFEncode($propertyLang [$key]),'text' => UTFEncode($propertyLang [$key]), 'value' => $value, 'group' => UTFEncode($store->getPolicyMappingPropertyGroup ( $key )), 'editor' => $defaultEditor );
                }
				
                //$storageProperty [] = Array ('id' => $store->getStorageMapping ( $key, 'field' ), 'Name' => $propertyLang [$key], 'Value' => $value, 'Group' => 0, 'EditorType' => $propertyEditor [$key] );
			}
		}
		/*foreach ( $rs as $row ) {
			$positions [] = Array ('id' => $row ['f_pos_id'], 'name' => UTFEncode( $row ['f_pos_name'] ), 'description' => UTFEncode( $row ['f_pos_desc'] ), 'level' => $row ['f_pos_level'], 'status' => $row ['f_pos_status'] );
		}*/
		/*$count = count ( $positions );
		 * 
		$data = json_encode ( $positions );*/
		$count = count ( $policyProperty );
		$data = json_encode ( $policyProperty );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /account-property/ ข้อมูลรายละเอียดของบัญชีรายชื่อ
	 *
	 */
	public function accountPropertyAction() {
		global $config;
		global $conn;
		global $store;
		global $util;
		$accountID = $_GET ['accountID'];
		$sql = "select * from tbl_account where f_acc_id = '$accountID'";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$accountRec = $rs->FetchNextObject ();
		$accountProperty = Array ();
		
		$propertyLang = $store->getAccountProperty ( 'en' );
		$propertyEditor = $store->getAccountPropertyEditor ();
		
		foreach ( $accountRec as $key => $value ) {
			$key = strtolower ( $key );
			if (! in_array ( $key, array ('f_acc_id', 'f_login_password', 'f_last_change_pwd' ) )) {
				
				switch ($propertyEditor [$key]) {
					case 'date' :
						if(is_null($value) || $value == 0) {
							$value = '';
						} else {
							$value = UTFEncode($util->getDateString($value));
						}
						break;
					case 'text' :
						$value = UTFEncode ( $value );
						break;
					case 'boolean' :
						if ($value == 0 || is_null ( $value )) {
							$value = 'false';
						} else {
							$value = 'true';
						}
						break;
				}
				$accountProperty [] = Array ('id' => $store->getAccountMapping ( $key, 'field' ), 'Name' => $propertyLang [$key], 'Value' => $value, 'Group' => $store->getPolicyMappingPropertyGroup ( $key ), 'EditorType' => $propertyEditor [$key] );
			}
		}
		$count = count ( $accountProperty );
		$data = json_encode ( $accountProperty );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /account/ ข้อมูลรายการบัญชีรายชื่อ
	 *
	 */
	public function accountAction() {
		global $config;
		global $conn;
		$limit = $_GET ['limit'];
		$offset = $_GET ['start'];
		$sortID = $_GET ['sort'];
		$sortDir = $_GET ['dir'];
		
		if (! array_key_exists ( 'dir', $_GET )) {
			$sortDir = 'ASC';
		}
		
		if (array_key_exists ( 'filter', $_GET )) {
			$filtervalue = UTFDecode ( $_GET ['filter'] [0] ['data'] ['value'] );
			$filterQuery = " where (f_name like '%$filtervalue%'";
			$filterQuery .= " or f_mid_name like '%$filtervalue%'";
			$filterQuery .= " or f_last_name like '%$filtervalue%')";
		} else {
			$filterQuery = "";
		}
		
		switch ($sortID) {
			case 'id' :
				$sortQuery = "order by f_acc_id {$sortDir}";
				break;
			case 'name' :
				$sortQuery = "order by f_name {$sortDir},f_mid_name {$sortDir},f_last_name {$sortDir}";
				break;
			case 'rank' :
				$sortQuery = "order by f_rank_id {$sortDir}";
				break;
			case 'status' :
				$sortQuery = "order by f_status {$sortDir}";
				break;
			case 'type' :
				$sortQuery = "order by f_account_type {$sortDir}";
				break;
			case 'id' :
			default :
				$sortQuery = "order by f_acc_id {$sortDir}";
				break;
		}
		
		$sqlCountTotal = "select count(f_acc_id) as count_recs from tbl_account";
		$rsCount = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlCountTotal );
		$countRec = $rsCount->FetchNextObject ();
		$sql = "select * from tbl_account $filterQuery $sortQuery";
		$rs = $conn->CacheSelectLimit ( $config ['defaultCacheSecs'], $sql, $limit, $offset );
		$ranks = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$ranks [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . ' ' . $row ['f_mid_name'] . ' ' . $row ['f_last_name'] ), 'login' => UTFEncode ( $row ['f_login_name'] ), 'rank' => $row ['f_rank_id'], 'status' => $row ['f_status'], 'type' => $row ['f_account_type'] );
		}
		
		//$count = count ( $ranks );
		$data = json_encode ( $ranks );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $countRec->COUNT_RECS . '","results":' . $data . '})';
	}
	
	/**
	 * action /action-avail/ ข้อมูลบัญชีรายชื่อสำหรับการ map กับโครงสร้างหน่วยงาน
	 *
	 */
	public function accountAvailAction() {
		global $conn;
		//$limit = $_GET ['limit'];
		//$offset = $_GET ['start'];
		///$sortID = $_GET ['sort'];
		//$sortDir = $_GET ['dir'];

		//if (! array_key_exists ( 'dir', $_GET )) {
		//	$sortDir = 'ASC';
		//}

		/*
		switch ($sortID) {
			case 'id' :
				$sortQuery = "order by f_acc_id {$sortDir}";
				break;
			case 'name' :
				$sortQuery = "order by f_name {$sortDir},f_mid_name {$sortDir},f_last_name {$sortDir}";
				break;
			case 'rank' :
				$sortQuery = "order by f_rank_id {$sortDir}";
				break;
			case 'status' :
				$sortQuery = "order by f_status {$sortDir}";
				break;
			case 'type' :
				$sortQuery = "order by f_account_type {$sortDir}";
				break;
			case 'id' :
			default :
				$sortQuery = "order by f_acc_id {$sortDir}";
				break;
		}
		*/
		if (array_key_exists ( 'filter', $_GET )) {
			$filtervalue = UTFDecode ( $_GET ['filter'] [0] ['data'] ['value'] );
			$filterQuery = " and (e.f_name like '%$filtervalue%'";
			$filterQuery .= " or e.f_mid_name like '%$filtervalue%'";
			$filterQuery .= " or e.f_last_name like '%$filtervalue%')";
		} else {
			$filterQuery = "";
		}
		
		$roleID = $_GET ['roleID'];
		$sql = "select e.*,d.f_rank_name from tbl_role a,tbl_position_master b,tbl_mapping c,tbl_rank d ,tbl_account e";
		$sql .= " where a.f_role_id = '$roleID'";
		$sql .= " and b.f_pos_id = a.f_pos_id";
		$sql .= " and c.f_master_id = b.f_pos_id";
		$sql .= " and c.f_map_class = 'pos2rank'";
		$sql .= " and d.f_rank_id = c.f_slave_id and e.f_rank_id = d.f_rank_id {$filterQuery}";
		
		//$sqlCount = "select e.* from tbl_role a,tbl_position_master b,tbl_mapping c,tbl_rank d ,tbl_account e";
		//$sqlCount .= " where a.f_role_id = '$roleID'";
		//$sqlCount .= " and b.f_pos_id = a.f_pos_id";
		//$sqlCount .= " and c.f_master_id = b.f_pos_id";
		//$sqlCount .= " and c.f_map_class = 'pos2rank'";
		//$sqlCount .= " and d.f_rank_id = c.f_slave_id and e.f_rank_id = d.f_rank_id";
		

		$rs = $conn->Execute ( $sql );
		$data = "";
		$acc = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			if ($data != '') {
				$data .= ",";
			}
			$acc [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode ( $row ['f_name'] . ' ' . $row ['f_mid_name'] . ' ' . $row ['f_last_name'] ), 'rank' => $row ['f_rank_name'], 'status' => $row ['f_status'] );
			//$data .= "['{$row['f_acc_id']}','{$row['f_name']}','{$row['f_rank_id']}','{$row['f_status']}']";
		}
		/*
		$sqlCountTotal = "select count(f_acc_id) as count_recs from tbl_account";
		$rsCount = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlCountTotal );
		$countRec = $rsCount->FetchNextObject ();
		$sql = "select * from tbl_account $sortQuery";
		$rs = $conn->CacheSelectLimit ( $config ['defaultCacheSecs'], $sql, $limit, $offset );
		$ranks = Array ();
		*/
		//foreach ( $rs as $row ) {
		//	$row = array_change_key_case($row,CASE_LOWER);
		//	$ranks [] = Array ('id' => $row ['f_acc_id'], 'name' => UTFEncode( $row ['f_name'] . ' ' . $row ['f_mid_name'] . ' ' . $row ['f_last_name'] ), 'login' => UTFEncode( $row ['f_login_name'] ), 'rank' => $row ['f_rank_id'], 'status' => $row ['f_status'], 'type' => $row ['f_account_type'] );
		//}
		

		//$count = count ( $ranks );
		$data = json_encode ( $acc );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . count ( $acc ) . '","results":' . $data . '})';
	}
	
	/**
	 * action /load-organize/ ข้อมูลหน่วยงาน
	 *
	 */
	public function loadOrganizeAction() {
		global $conn;
		$objid = $_POST ['objid'];
		
		if ($objid == - 1) {
			$sql = "select * from tbl_organize where f_org_id = 0 ";
		} else {
			$sql = "select * from tbl_organize where f_org_pid = '{$objid}' ";
		}
		$rsGetOrg = $conn->Execute ( $sql );
		$node = Array ();
		$checkLeaf = "select count(f_role_id) as role_count from tbl_role where f_org_id = '{$objid}'";
		$rsCheckLeaf = $conn->Execute ( $checkLeaf );
		$checkLeaf = $rsCheckLeaf->FetchNextObject ();
		if ($checkLeaf->ROLE_COUNT > 0) {
			$sqlGetRoles = "select a.* from tbl_role a,tbl_position_master b where a.f_org_id = '{$objid}' and a.f_pos_id = b.f_pos_id order by b.f_pos_level asc";
			$rsGetRoles = $conn->Execute ( $sqlGetRoles );
			
			foreach ( $rsGetRoles as $role ) {
				checkKeyCase($role);
				$tmpRole = Array ();
				$tmpRole ['objid'] = $role ['f_role_id'];
				$tmpRole ['text'] = UTFEncode ( $role ['f_role_name'] );
				$tmpRole ['description'] = UTFEncode ( $role ['f_role_desc'] );
				$tmpRole ['type'] = 2;
				$tmpRole ['orgtype'] = 2;
				$tmpRole ['uiProviders'] = 'col';
				$tmpRole ['cls'] = 'master-task';
				if($role['f_is_governer'] == 1) {
					$tmpRole ['iconCls'] = 'governerRoleIcon';
				} else {
					$tmpRole ['iconCls'] = 'roleIcon';	
				}
				
				$tmpRole ['isroot'] = 0;
                $tmpRole ['unlimit'] = (int)$role ['f_unlimit_lookup'];
				$tmpRole ['intDocCode'] = '';
				$tmpRole ['extDocCode'] = '';
				$tmpRole ['policyID'] = $role ['f_gp_id'];
                $tmpRole ['allowInt'] = 0;
				$tmpRole ['governer'] = $role ['f_is_governer'];
				$tmpRole ['commander'] = (int)$role ['f_is_commander'];
				
				$node [] = $tmpRole;
			}
		}
		
		foreach ( $rsGetOrg as $row ) {
			checkKeyCase($row);
			$nodeTemp = Array ();
			$nodeTemp ['objid'] = $row ['f_org_id'];
			$nodeTemp ['text'] = UTFEncode ( $row ['f_org_name'] );
			$nodeTemp ['description'] = UTFEncode ( $row ['f_org_desc'] );
			if ($row ['f_org_pid'] == - 1) {
				$nodeTemp ['isroot'] = 1;
			} else {
				$nodeTemp ['isroot'] = 0;
			}
			$nodeTemp ['type'] = (int)$row ['f_org_type'];
			$nodeTemp ['orgtype'] = $row ['f_org_type'];
			$nodeTemp ['uiProvider'] = 'col';
			$nodeTemp ['cls'] = 'master-task';
			if ($row ['f_org_type'] == 0) {
				$nodeTemp ['iconCls'] = 'mainOrgIcon';
			}
			$nodeTemp ['intDocCode'] = UTFEncode ( $row ['f_int_code'] );
			$nodeTemp ['extDocCode'] = UTFEncode ( $row ['f_ext_code'] );
			$nodeTemp ['policyID'] = 0;
            $nodeTemp ['allowInt'] = (int)$row ['f_allow_int_doc_no'];     
            $nodeTemp ['unlimit'] = 0;    
			
			if ($row ['f_org_type'] == 1) {
				$nodeTemp ['iconCls'] = 'subOrgIcon';
			}
			$nodeTemp ['governer'] = 0;
			
			$node [] = $nodeTemp;
		}
		
		echo json_encode ( $node );
	}
	
	/**
	 * action /load-user-in-role/ ข้อมูลผู้ใช้ในตำแหน่ง
	 *
	 */
	public function loadUserInRoleAction() {
		global $conn;
		$objid = $_POST ['objid'];
		$sql = "select a.f_role_id,b.f_acc_id,b.f_name,b.f_last_name from tbl_passport a ,tbl_account b where a.f_acc_id = b.f_acc_id and a.f_role_id = '{$objid}'";
		$rsGetUser = $conn->Execute ( $sql );
		$node = Array ();
		foreach ( $rsGetUser as $row ) {
			checkKeyCase($row);
			$nodeTemp = Array ();
			$nodeTemp ['objid'] = $row ['f_acc_id'];
			$nodeTemp ['text'] = UTFEncode ( $row ['f_name'] . " " . $row ['f_last_name'] );
			$nodeTemp ['description'] = "";
			$nodeTemp ['type'] = 3;
			$nodeTemp ['orgtype'] = 3;
			$nodeTemp ['uiProvider'] = 'col';
			$nodeTemp ['cls'] = 'master-task';
			$nodeTemp ['isroot'] = 0;
			$nodeTemp ['iconCls'] = 'userIcon';
			$nodeTemp ['leaf'] = 'true';
			$nodeTemp ['intDocCode'] = '';
			$nodeTemp ['extDocCode'] = '';
			$nodeTemp ['policyID'] = 0;
			$nodeTemp ['governer'] = 0;
			$nodeTemp ['allowDrop'] = 'false';
			$nodeTemp ['allowChildren'] = 'false';
			$nodeTemp ['checked'] = 'true';
			
			$node [] = $nodeTemp;
		}
		echo json_encode ( $node );
	}
	
	/**
	 * action /form-structure/ ข้อมูลโครงสร้างของฟอร์ม
	 *
	 */
	public function formStructureAction() {
		//global $config;
		global $conn;
		
		$formID = $_GET ['formID'];
		$formVersion = $_GET ['formVersion'];
		
		$sql = "select * from tbl_form_structure where f_version = '{$formVersion}' and f_form_id = '{$formID}' order by f_struct_id asc";
		//$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$rs = $conn->Execute ( $sql );
		
		$formStructures = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$formStructures [] = Array ('formID' => $row ['f_form_id'], 'formVersion' => $row ['f_version'], 'structID' => $row ['f_struct_id'], 'structName' => $row ['f_struct_name'], 'structType' => $row ['f_struct_type'], 'structGroup' => $row ['f_struct_group'], 'dataType' => $row ['f_data_type'], 'useLookup' => $row ['f_use_lookup'], 'lookup' => $row ['f_lookup_id'], 'structParam' => $row ['f_struct_param'], 'initialValue' => $row ['f_initial_value'], 'isTitle' => $row ['f_is_title'], 'isDesc' => $row ['f_is_desc'], 'isKeyword' => $row ['f_is_keyword'], 'allowSearch' => $row ['f_allow_search'], 'isDocNo' => $row ['f_is_doc_no'], 'isDocDate' => $row ['f_is_doc_date'], 'isRequired' => $row ['f_is_required'], 'isColored' => $row ['f_is_colored'], 'color' => $row ['f_color'], 'isValidate' => $row ['f_is_validate'], 'validateFunction' => $row ['f_validate_fn'], 'isSenderText' => $row ['f_is_sender_text'], 'isReceiverText' => $row ['f_is_receiver_text'] );
		}
		
		$count = count ( $formStructures );
		$data = json_encode ( $formStructures );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /form-structure-property/ ข้อมูลคุณสมบัติของโครงสร้างฟอร์ม
	 *
	 */
	public function formStructurePropertyAction() {
		global $config;
		global $conn;
		global $store;
		
		$formID = $_GET ['formID'];
		$formVersion = $_GET ['formVersion'];
		$structID = $_GET ['structID'];
		
		$propertyLang = $store->getFormStructureProperty ( 'en' );
		$propertyEditor = $store->getFormStructurePropertyEditor ();
		
		$sql = "select * from tbl_form_structure where f_version = '{$formVersion}' and f_form_id = '{$formID}' and f_struct_id ='{$structID}'";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$formStructRec = $rs->FetchNextObject ();
		$formStructureProperty = Array ();
		
		foreach ( $formStructRec as $key => $value ) {
			$key = strtolower ( $key );
			if (! in_array ( $key, array ('f_form_id', 'f_version', 'f_struct_id' ) )) {
				
				switch ($propertyEditor [$key]) {
					case 'text' :
						$value = UTFEncode ( $value );
						break;
					case 'boolean' :
						if ($value == 0 || is_null ( $value )) {
							$value = 'false';
						} else {
							$value = 'true';
						}
						break;
				}
				$formStructureProperty [] = Array ('id' => $store->getFormStructureMapping ( $key, 'field' ), 'Name' => $propertyLang [$key], 'Value' => $value, 'Group' => 0, 'EditorType' => $propertyEditor [$key] );
			}
		}
		
		$count = count ( $formStructureProperty );
		$data = json_encode ( $formStructureProperty );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /storage/ ข้อมูลรายการ Storage
	 *
	 */
	public function storageAction() {
		global $config;
		global $conn;
		$sql = "select * from tbl_storage order by f_st_id asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$storage = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$storage [] = Array ('id' => $row ['f_st_id'], 'name' => UTFEncode ( $row ['f_st_name'] ), 'type' => $row ['f_st_type'], 'server' => $row ['f_st_server'], 'port' => $row ['f_st_port'], 'path' => $row ['f_st_path'], 'uid' => '********', 'pwd' => '********', 'limit' => $row ['f_st_limit'], 'size' => $row ['f_st_size'], 'status' => $row ['f_status'], 'default' => $row ['f_default'] );
		}
		
		$count = count ( $storage );
		$data = json_encode ( $storage );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /storage-property/ ข้อมูลคุณสมบัติของ Storage
	 *
	 */
	public function storagePropertyAction() {
		global $config;
		global $conn;
		global $store;
		$storageID = $_GET ['storageID'];
		$sql = "select * from tbl_storage where f_st_id = '$storageID'";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$storageRec = $rs->FetchNextObject ();
		$storageProperty = Array ();
		
		$propertyLang = $store->getStorageProperty ( 'en' );
		$propertyEditor = $store->getStoragePropertyEditor ();
		
		foreach ( $storageRec as $key => $value ) {
			$key = strtolower ( $key );
			if (! in_array ( $key, array ('f_st_id', 'f_st_uid', 'f_st_pwd' ) )) {
				
				switch ($propertyEditor [$key]) {
					case 'text' :
						$value = UTFEncode ( $value );
						break;
					case 'boolean' :
						if ($value == 0 || is_null ( $value )) {
							$value = 'false';
						} else {
							$value = 'true';
						}
						break;
				}
				$storageProperty [] = Array ('id' => $store->getStorageMapping ( $key, 'field' ), 'Name' => $propertyLang [$key], 'Value' => $value, 'Group' => 0, 'EditorType' => $propertyEditor [$key] );
			}
		}
		$count = count ( $storageProperty );
		$data = json_encode ( $storageProperty );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /document-type/ ข้อมูลประเภทของเอกสาร
	 *
	 */
	public function documentTypeAction() {
		global $config;
		global $conn;
		$sql = "select * from tbl_doc_type order by f_doctype_id asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$ranks = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$ranks [] = Array ('id' => $row ['f_doctype_id'], 'global' => $row ['f_global'], 'orgid' => $row ['f_org_id'], 'name' => UTFEncode ( $row ['f_name'] ), 'status' => $row ['f_status'] );
		}
		
		$count = count ( $ranks );
		//$ranks = Array (array ('id' => 1, 'name' => 'Rank 1', 'description' => '', 'level' => '1', 'status' => '1' ), array ('id' => 2, 'name' => 'Rank 2', 'description' => '', 'level' => '2', 'status' => '2' ), array ('id' => 3, 'name' => 'Rank 3', 'description' => '', 'level' => '3', 'status' => '3' ), array ('id' => 4, 'name' => 'Rank 4', 'description' => '', 'level' => '4', 'status' => '4' ) );
		$data = json_encode ( $ranks );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /attach-temp/ ข้อมูลเอกสารแนบของ Temp. Document
	 *
	 */
	public function attachTempAction() {
		global $config;
		global $conn;
		
		$userTempPath = $config ['tempPath'] . "/{$_SESSION['accID']}";
		
		$tempID = $_GET ['tempID'];
		$limit = $_GET ['limit'];
		$offset = $_GET ['start'];
		//$sortID = $_GET ['sort'];
		//$sortDir = $_GET ['dir'];
		

		$sqlCountTotal = "select count(*) as count_recs from tbl_doc_page_temp where f_doc_id = '{$tempID}'";
		$rsCount = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlCountTotal );
		$countRec = $rsCount->FetchNextObject ();
		$sql = "select * from tbl_doc_page_temp where f_doc_id = '{$tempID}'";
		$rs = $conn->CacheSelectLimit ( $config ['defaultCacheSecs'], $sql, $limit, $offset );
		
		$images = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$tempFile = "$userTempPath/create/{$row['f_sys_file_name']}.{$row['f_extension']}";
			//$size = filesize($tempFile);
			$lastmod = filemtime ( $tempFile ) * 1000;
			$imgURL = "images/filetype/word.jpg";
			$images [] = Array ('docid'=>$row['f_doc_id'],'pageid'=>$row['f_page_id'],'name' => UTFEncode ( $row ['f_orig_file_name'] ), 'size' => $row ['f_file_size'], 'lastmod' => $lastmod, 'url' => $imgURL );
		}
		
		//$count = count ( $ranks );
		$data = json_encode ( $images );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $countRec->COUNT_RECS . '","images":' . $data . '})';
	}
	
	/**
	 * action /attach/ ข้อมูลเอกสารแนบ
	 *
	 */
	public function attachAction() {
		global $config;
		global $conn;
		global $util;
		
		$userTempPath = $config ['tempPath'] . "/{$_SESSION['accID']}";
		
		$tempID = $_GET ['docID'];
		$limit = $_GET ['limit'];
		$offset = $_GET ['start'];
		
		$sqlCountTotal = "select count(*) as count_recs from tbl_doc_page where f_doc_id = '{$tempID}'";
		$rsCount = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlCountTotal );
		$countRec = $rsCount->FetchNextObject ();
		$sql = "select * from tbl_doc_page where f_doc_id = '{$tempID}'";
        $rs = $conn->SelectLimit ( $sql, $limit, $offset );
		//$rs = $conn->Execute (  $sql);
		
		$images = Array ();
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$tempFile = "$userTempPath/create/{$row['f_sys_file_name']}.{$row['f_extension']}";
			//$size = filesize($tempFile);
			$lastmod = @filemtime ( $tempFile ) * 1000;
			if (! $lastmod) {
				$lastmod = time ();
			}
			$imgURL = $util->getIconURLByMimeType ( $row ['f_mime_type'] );
			
			$sizeStr = $util->getByte($row['f_file_size']);
			if(is_null($row['f_create_stamp'])){
				$date = "Undefined";
			} else {
				$date = $util->getDateString($row['f_create_stamp']);	
			}
			if(is_null($row['f_create_uid'])) {
				$owner = "Scan/Batch";
			} else {
				$account = new AccountEntity();
				$account->Load("f_acc_id = '{$row['f_create_uid']}'");
				$owner= $account->f_name." ".$account->f_last_name;
				if(trim($owner) == "") {
					$owner = "Scan/Batch";
				}
			}
			
			//$imgURL = "images/filetype/word.jpg";
			$images [] = Array ('docid' => $row ['f_doc_id'], 'pageid' => $row ['f_page_id'], 'name' => UTFEncode ( $row ['f_orig_file_name'] ), 'size' => $row ['f_file_size'], 'lastmod' => $lastmod, 'url' => $imgURL,'owner'=>UTFEncode($owner),'sizeStr'=>$sizeStr,'date'=>UTFEncode($date),'version' => "{$row['f_major_version']}.{$row['f_minor_version']}.{$row['f_branch_version']}", 'createuid'=>$row['f_create_uid'] );
		}
		
		//$count = count ( $ranks );
		$data = json_encode ( $images );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $countRec->COUNT_RECS . '","images":' . $data . '})';
	}
    
	/**
	 * action /announce-attach/ ข้อมูลเอกสารแนบของคำสั่ง/ประกาศ
	 *
	 */
    public function announceAttachAction() {
        global $config;
        global $conn;
        global $util;
        
        $userTempPath = $config ['tempPath'] . "/{$_SESSION['accID']}";
        
        $tempID = $_GET ['docID'];
        $limit = $_GET ['limit'];
        $offset = $_GET ['start'];
        
        $sqlCountTotal = "select count(*) as count_recs from tbl_announce_page where f_announce_id = '{$tempID}'";
        $rsCount = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlCountTotal );
        $countRec = $rsCount->FetchNextObject ();
        $sql = "select * from tbl_announce_page where f_announce_id = '{$tempID}'";
        $rs = $conn->SelectLimit ( $sql, $limit, $offset );
        //$rs = $conn->Execute (  $sql);
        
        $images = Array ();
        foreach ( $rs as $row ) {
            checkKeyCase($row);
            $tempFile = "$userTempPath/create/{$row['f_sys_file_name']}.{$row['f_extension']}";
            //$size = filesize($tempFile);
            $lastmod = @filemtime ( $tempFile ) * 1000;
            if (! $lastmod) {
                $lastmod = time ();
            }
            $imgURL = $util->getIconURLByMimeType ( $row ['f_mime_type'] );
            
        	$sizeStr = $util->getByte($row['f_file_size']);
			if(is_null($row['f_create_stamp'])){
				$date = "Undefined";
			} else {
				$date = $util->getDateString($row['f_create_stamp']);	
			}
			if(is_null($row['f_create_uid'])) {
				$owner = "Unknown";
			} else {
				$account = new AccountEntity();
				$account->Load("f_acc_id = '{$row['f_create_uid']}'");
				$owner= $account->f_name." ".$account->f_last_name;
			}
            //$imgURL = "images/filetype/word.jpg";
            //$images [] = Array ('docid' => $row ['f_announce_id'], 'pageid' => $row ['f_page_id'], 'name' => UTFEncode ( $row ['f_orig_file_name'] ), 'size' => $row ['f_file_size'], 'lastmod' => $lastmod, 'url' => $imgURL );
            $images [] = Array ('docid' => $row ['f_announce_id'], 'pageid' => $row ['f_page_id'], 'name' => UTFEncode ( $row ['f_orig_file_name'] ), 'size' => $row ['f_file_size'], 'lastmod' => $lastmod, 'url' => $imgURL,'owner'=>UTFEncode($owner),'sizeStr'=>$sizeStr,'date'=>UTFEncode($date),'version' => "{$row['f_major_version']}.{$row['f_minor_version']}.{$row['f_branch_version']}" );
        }
        
        //$count = count ( $ranks );
        $data = json_encode ( $images );
        $cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
        print $cb . '({"total":"' . $countRec->COUNT_RECS . '","images":' . $data . '})';
    }
	
    /**
     * action /contact-lists/ ข้อมูล contact list
     *
     */
	public function contactListsAction() {
		//include_once 'ContactList.Entity.php';
		$contactListLoader = new ContactList ( );
		$contactLists = $contactListLoader->Find ( " f_cl_owner = '' or f_c_global = 1 order by f_cl_global desc,f_cl_name" );
		$count = 0;
		$contactListArray = Array ();
		foreach ( $contactLists as $contactList ) {
			echo $contactList;
			$count ++;
		}
		$data = json_encode ( $contactListArray );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","images":' . $data . '})';
	}
	
	/**
	 * action /document-type-list/ ข้อมูลประเภทเอกสาร
	 *
	 */
	public function documentTypeListAction() {
		global $config;
		global $conn;
		global $sessionMgr;
		
		$documentType = Array ();
	
			$sql = "select * from tbl_doc_type where f_org_id = '{$sessionMgr->getCurrentOrgID()}' or f_global = '1' order by f_doctype_id  asc";
			$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
			foreach ( $rs as $row ) {
				checkKeyCase($row);
				$documentType [] = Array ('id' => $row ['f_doctype_id'], 'name' => UTFEncode ( $row ['f_name'] ) );
			}
			$count = count ( $documentType );
			$data = json_encode ( $documentType );
			$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
			print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /receive-type-list/ ข้อมูลประเภทการรับเอกสาร
	 *
	 */
	public function receiveTypeListAction() {
		global $config;
		global $conn;
		
		$sendType = Array ();
		$sql = "select * from tbl_receive_type where f_status = 1 order by f_recv_id  asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$sendType [] = Array ('id' => $row ['f_recv_id'], 'name' => UTFEncode ( $row ['f_recv_name'] ) );
		}
		$count = count ( $sendType );
		$data = json_encode ( $sendType );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /purpose-saraban/ ข้อมูลวัตถุประสงค์
	 *
	 */
	public function purposeSarabanAction() {
		global $config;
		global $conn;
		
		$purpose = Array ();
		$sql = "select * from tbl_purpose where f_status = 1 order by f_purpose_id  asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$purpose [] = Array ('id' => $row ['f_purpose_id'], 'name' => UTFEncode ( $row ['f_purpose_name'] ) );
		}
		$count = count ( $purpose );
		$data = json_encode ( $purpose );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /register-book/ ข้อมูลรายการทะเบียน
	 *
	 */
	public function registerBookAction() {
		global $config;
		global $conn;
		
		$type = $_GET ['type'];
		$orgID = $_GET ['orgID'];
		
		$regBook = Array ();
		$sql = "select f_reg_book_id,f_reg_book_name from tbl_reg_book where f_owner_org_id = '{$orgID}' and f_reg_book_type = '$type' and f_status = 1 order by f_reg_book_id  asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		$regBook [] = Array ('id' => 0, 'name' => UTFEncode ( 'ทะเบียนหลัก' ) );
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$regBook [] = Array ('id' => $row ['f_reg_book_id'], 'name' => UTFEncode ( $row ['f_reg_book_name'] ) );
		}
		$count = count ( $regBook );
		$data = json_encode ( $regBook );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /role-available/ ข้อมูลรายการตำแหน่งที่มีอยู่
	 *
	 */
	public function roleAvailableAction() {
		global $config;
		global $conn;
		
		$accID = $_GET ['accID'];
		
		$roles = Array ();
		//$sql = "select a.f_role_id,b.f_role_name, c.f_org_name from tbl_passport a,tbl_role b, tbl_organize c where a.f_acc_id = '{$accID}' and a.f_role_id = b.f_role_id and b.f_org_id = c.f_org_id order by a.f_default_role desc";

		$sql = "select a.f_role_id,b.f_role_name, c.f_org_name from tbl_passport a,tbl_role b, tbl_organize c where a.f_acc_id = '{$accID}' and a.f_role_id = b.f_role_id and b.f_org_id = c.f_org_id order by a.f_default_role desc, c.f_org_name, b.f_role_name asc";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sql );
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$roles [] = Array ('id' => $row ['f_role_id'], 'name' => UTFEncode ( $row ['f_role_name'] ), 'orgName' => UTFEncode ( $row ['f_org_name'] ) );
		}
		$count = count ( $roles );
		$data = json_encode ( $roles );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /master-type/ ข้อมูลรายการชนิด Master
	 *
	 */
	public function masterTypeAction() {
		//global $conn;
		
		//include_once 'MasterType.Entity.php';
		
		$masterType = new MasterTypeEntity();
		$masterTypeArray = $masterType->Find("f_status >= 0");
		$masterTypeData = Array();
		foreach ($masterTypeArray as $master) {
			  
			  $masterTypeData[] = Array(
			  'id'=>$master->f_ctl_id,
			   'name'=>UTFEncode($master->f_mas_name),
			   'desc'=>UTFEncode($master->f_mas_description),
			   'status'=>$master->f_status
			  );
		}
		
		$count = count ( $masterTypeData );
		$data = json_encode ( $masterTypeData );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
}
