<?php
/**
 * โปรแกรมทะเบียนคำสั่งประกาศ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 */
class AnnounceController extends ECMController {
    
	/**
	 * action /index/ ไม่มีการทำงาน
	 *
	 */
    public function indexAction() {
        // No Index Action
    }
    
    /**
     * method สำหรับตรวจสอบและสร้างประเภทคำสั่ง/ประกาศถ้าไม่มีอยู่แล้ว
     *
     * @param int $type
     * @param string $name
     * @return int
     */
    public function checkNewType($type,$name) {
        global $conn;
        global $sequence;
        //$conn->debug = true;
        $sqlCount = "select count(*) as count_exp from tbl_announce_category where f_name = '{$name}' and f_announce_type = {$type}";
        $rsCount = $conn->Execute($sqlCount);
        $tmpCount = $rsCount->FetchNextObject();
        if($tmpCount->COUNT_EXP == 0) {
            //include_once 'AnnounceCategory.Entity.php';
            if(!$sequence->isExists('announce_'.$type)) {
                $sequence->create('announce_'.$type);
                $sequence->set('announce_'.$type,10000*($type+1));
            }
            $announceCat = new AnnounceCategoryEntity();
            $announceCat->f_announce_cat_id = $sequence->get('announce_'.$type);
            $announceCat->f_announce_type = $type;
            $announceCat->f_name = $name;
            $announceCat->f_desc = '';
            $announceCat->f_status = 1;
            $announceCat->Save();
        } else {
            $announceCat = new AnnounceCategoryEntity(); 
            $announceCat->Load("f_name = '{$name}' and f_announce_type = {$type}");
        }                                      
        return $announceCat->f_announce_cat_id;
    }
    
    /**
     * action /create/ สร้างคำสั่ง/ประกาศ
     *
     */
    public function createAction () {
        global $util;
        global $sessionMgr;
        global $sequence;
        
        $result = Array();
        
        if(!$sequence->isExists('announce')) {
            $sequence->create('announce');
        }
        Logger::debug('create step #1');
		
		$year = UTFDecode($_POST['extraDocDate']);
		$year = substr($year, -4) -543;
        
        //include_once 'AnnounceCategory.Entity.php';  
        //include_once 'Announce.Entity.php';  
        //include_once 'Organize.Entity.php';  
        
        //edt                                    
        //$type = UTFDecode($_POST['extraDocType']);
        
        $type = $_COOKIE['edt'];
        Logger::debug("create type :".$type);
        $typeName = UTFDecode($_POST['extraDocSubType']);
        Logger::debug("create subtype name :".$typeName);
        
        $announceType = new AnnounceCategoryEntity();
        $announce = new AnnounceEntity();
        
        if(!$announceType->Load("f_name = '{$typeName}' and f_announce_type = '{$type}'")) {
            $catID = $this->checkNewType($type,$typeName);     
        } else {
            $catID = $announceType->f_announce_cat_id;
        }
        Logger::debug("create subtype id :".$catID);
        
        //$this->checkNewType($type,$typeName);
        Logger::debug('create step #2');
        $announceType = new AnnounceCategoryEntity();
        $announceType->Load("f_name = '{$typeName}'");
        
        $announce->f_announce_category = $catID;
        $announce->f_announce_id = $sequence->get("announce");
        $announce->f_announce_stamp = $util->dateToStamp(UTFDecode($_POST['extraDocDate']));
        $announce->f_announce_sys_stamp = time();
        $announce->f_announce_type = $type;
        
		//if($type == 0) {
        if(!$sequence->isExists('command_'.$catID.'_'.$year)) {
			$sequence->create('command_'.$catID.'_'.$year);
        }
        //}

        $announce->f_announce_no = $sequence->get('command_'.$catID.'_'.$year);
        $announce->f_announce_date = UTFDecode($_POST['extraDocDate']);
        $announce->f_remark = UTFDecode($_POST['extraDocRemark']); 
        $announce->f_detail= UTFDecode($_POST['extraDocDesc']);     
        $announce->f_title= str_replace("\n","",str_replace("\r","",UTFDecode($_POST['extraDocTitle'])));
        $announce->f_year= $year+543; //UTFDecode($_POST['extraDocYear']); 
        $announce->f_sign_role= $_POST['extraDocSignRoleHidden'];
        $announce->f_sign_uid= $_POST['extraDocSignUserHidden'];
        $announce->f_announce_org_id = $_POST['extraOrgID'];
		// add user create to announce 
		$announce->f_announce_user = $sessionMgr->getCurrentAccID();
        
        $org = new OrganizeEntity();
        if(!$org->Load("f_org_id = '{$_POST['extraOrgID']}'")) {
            $announce->f_announce_org_name = UTFDecode($_POST['extraOrgName']);
        } else {
            $announce->f_announce_org_name = $org->f_org_name;
        }
        
        $announce->f_delete = 0;
        $announce->f_delete_uid = 0;                                         
        $announce->Save();
        Logger::debug('create step #3');
        
        $result['success'] = 1;
        $result['announceID'] = $announce->f_announce_id;
        $result['title'] = UTFEncode($announce->f_title);
        $result['announceType']=$type;
        $result['announceCategory']=$announce->f_announce_category;
        $result['announceTypeName']=UTFEncode($typeName);
        $result['announceNo']=$announce->f_announce_no.'/'.$announce->f_year;
        
        unset($_COOKIE['edt']);
        Logger::debug('create step #4');
        echo json_encode($result);
    }  
	

	/**
     * action /edit/ สร้างคำสั่ง/ประกาศ
     *
     */
    public function editAction () {
        global $util;
        //global $sessionMgr;
        global $sequence;

		$announce = new AnnounceEntity();
		if(!$announce->Load("f_announce_id = '{$_POST['instanceIDA']}'")) {
			$result['success'] = 0;
		} else {
//			$announce->f_remark = UTFDecode($_POST['announceRemark']); 
//			$announce->f_detail= UTFDecode($_POST['announceDetail']);     
//			$announce->f_title= str_replace("\n","",str_replace("\r","",UTFDecode($_POST['announceTitle'])));
			
//			$announce->f_announce_sys_stamp = time();
//			$announce->f_announce_type = $type;
//			$announce->f_announce_org_id = $_POST['extraOrgID'];
//			$announce->f_year= UTFDecode($_POST['extraDocYear']);

			$announce->f_announce_org_id = $_POST['extraOrgIDA'];
			
			$org = new OrganizeEntity();
			if(!$org->Load("f_org_id = '{$_POST['extraOrgIDA']}'")) {
				$announce->f_announce_org_name = UTFDecode($_POST['extraOrgNameA']);
			} else {
				$announce->f_announce_org_name = $org->f_org_name;
			}

			$announce->f_announce_date = UTFDecode($_POST['extraDocDateA']);
			$announce->f_remark = UTFDecode($_POST['extraDocRemarkA']); 
			$announce->f_detail= UTFDecode($_POST['extraDocDescA']);     
			
			$announce->f_title= str_replace("\n","",str_replace("\r","",UTFDecode($_POST['extraDocTitleA'])));

			$signUser = new AccountEntity ( );
			list ( $nameSign, $lastNameSign ) = explode(' ', UTFDecode($_POST['extraDocSignUserA']) );
			$nameSign = trim($nameSign);
			$lastNameSign = trim($lastNameSign);

			if (! $signUser->Load ( "f_name = '{$nameSign}' and f_last_name = '{$lastNameSign}' " )) {
				$signUserID = "";
			} else {
				$signUserID = "{$signUser->f_acc_id}";
			}
			
//			$fp = fopen("d:/aaa.log","a+");
//			fwrite( $fp , print_r( $signUser,true)."\r\n" );
//			fclose( $fp );
			
			$signRole = new RoleEntity ( );
			$roleDesc = trim(UTFDecode($_POST['extraDocSignRoleA']));
			if (! $signRole->Load ( "f_role_name = '{$roleDesc}' " )) {
				$signRoleID = "";
			} else {
				$signRoleID = $signRole->f_role_id;
			}

			
			$announce->f_sign_role = $signRoleID;
			$announce->f_sign_uid = $signUserID;
			
			$announce->f_delete = 0;
			$announce->f_delete_uid = 0;
			
			$announce->Update();
			$result['success'] = 1;
		}

		
        echo json_encode($result);
       
    } 
    
}
