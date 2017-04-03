<?php
/**
 * โปรแกรม Import ทะเบียนรับภายนอก
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 *
 */
class ImportController extends ECMController {
    /**
     * Initialization method
     */
    public function init() {
        //include_once ('Account.Entity.php');
        //include_once ('Passport.Entity.php');
        //include_once ('Role.Entity.php');
        //include_once ('Command.Entity.php');
       	//include_once ('Organize.Entity.php');
        //include_once 'Order.Entity.php';
        //require_once 'Document.php';
        //require_once 'DFTransaction.php';
        
        $this->setupECMActionController ();
        $this->setECMViewModule ( 'default' );
    }
    /**
     * หน้าจอรอ Import Excel
     *
     */
    public function regbookAction() {
        global $config;
        
        echo "<form action=\"/{$config['appName']}/import/regbook-process\" method=\"POST\" enctype=\"multipart/form-data\">
        Excel<input type=\"file\" name=\"excelReport\" />
        <input type=\"submit\" value=\"Import\" />
        </form>";
    }
    
    /**
     * action /regbook-process ทำการ Import
     *
     */
    public function regbookProcessAction() {
        global $util;
        global $sessionMgr;
        global $sequence;
        
        loadExternalLib('ExcelReader');
        $excelReader = new Spreadsheet_Excel_Reader();
        $excelReader->setOutputEncoding('tis-620');
        $excelReader->read($_FILES["excelReport"]["tmp_name"]);
        
        echo $_FILES["excelReport"]["tmp_name"]."<br/>";
        echo "จำนวนทั้งหมด ".count($excelReader->sheets)." หน้า<br/>";
        $maxRegNo = 0;
        echo "<hr/>ดำเนินการนำเข้าเรียบร้อยแล้ว <a href=\"./regbook\">กลับไปเริ่ม Import</a><br/>";
        for($i = 0; $i< count($excelReader->sheets);$i++) {
            echo "<hr/>";
            $showPage = $i+1;
            echo "หน้าที่ {$showPage}<br/>";
            for($row = 6; $row <= 24 ;$row+=4) {
                //var_dump($excelReader->sheets[$i]['cells']);
                $regno=$excelReader->sheets[$i]['cells'][$row][1];
                if($regno > $maxRegNo) {
                    $maxRegNo = $regno;
                }
                
                $recvDate=$excelReader->sheets[$i]['cells'][$row][2];
                $recvTime=$excelReader->sheets[$i]['cells'][$row+1][2];
                $docNo=$excelReader->sheets[$i]['cells'][$row][3];
                $docDate=$excelReader->sheets[$i]['cells'][$row][4];
                $from=$excelReader->sheets[$i]['cells'][$row][5];
                $to=$excelReader->sheets[$i]['cells'][$row][6];
                $subject=$excelReader->sheets[$i]['cells'][$row][8];
                $command=$excelReader->sheets[$i]['cells'][$row][9];
                
                echo "เลขรับ :".$regno."<br/>";
                echo "วันที่รับ :".$recvDate."<br/>";
                echo "เวลาที่รับ :".$recvTime."<br/>";
                echo "เลขที่หนังสือ :".$docNo."<br/>";
                echo "ลงวันที่ :".$docDate."<br/>";
                echo "จาก :".$from."<br/>";
                echo "ถึง :".$to."<br/>";
                echo "เรื่อง :".$subject."<br/>";
                echo "สั่งการ :".$command."<br/>";
                
                
                
                
                $this->receiveExternalGlobal($regno,$recvDate,$recvTime,$docNo,$docDate,$from,$to,$subject,$command);
                echo "<br/><br/>";  
            }
        }
        
        $recvType = 3;
        $regBookID = 0;        
            
        $recvSequence = "receiveRegNo_0_{$recvType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
        if (! $sequence->isExists ( $recvSequence )) {
            $sequence->create ( $recvSequence );
        }
        
        $sequence->set($recvSequence,$maxRegNo);
        
        //var_dump($_FILES);
                     
        echo "<hr/>ดำเนินการนำเข้าเรียบร้อยแล้ว <a href=\"./regbook\">กลับไปเริ่ม Import</a>";
    }
    
    /**
     * ทำการบันทึกข้อมูบ
     *
     * @param string $regno
     * @param string $recvDate
     * @param string $recvTime
     * @param string $docNo
     * @param string $docDate
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $commandText
     */
    public function receiveExternalGlobal($regno,$recvDate,$recvTime,$docNo,$docDate,$from,$to,$subject,$commandText) {
        global $config;
        global $sequence;
        global $sessionMgr;
        
        /*checkSessionJSON();*/
        
		//require_once 'Document.php';
        //require_once 'DFTransaction.php';
        
        $docCreator = new Document ( );
        $formID = $config ['defaultImportSarabanFormID'];
        $hasTempPage = false;
        $tempID = 0;
        $createInDMS = false;
        $parentMode = 'mydoc';
        $DMSParentID = 0;
        //0=นำส่ง
        $docDeliverType = 0;
        //0=default
        $regBookID = 0;
        //
        $recvRegNo = $regno;
        $recvDate = $recvDate;
        $recvTime = substr($recvTime,0,5);
        $sendName = $from;
        $sendOrg = '';
        $attendName = $to;
        $attengOrg = '';
        
        $securityModifier = 0;
        $speedModifier = 0;
        if ($securityModifier > 0) {
            $originTransType = 'RS';
            $recvType = 4;
        } else {
            $originTransType = 'RG';
            $recvType = 3;
        }
        
        
        $title = $subject;
        $docNo = $docNo;
        $docDate = $docDate;
        $sendFrom = $sendName;
        
        if($docCreator->checkDuplicate($docNo,$docDate,$title,$sendFrom)){
            $response = Array();
            $response['success'] = 0;
            $response['duplicate'] = 1;
            //echo json_encode($response);
            //die();
            echo "<font color=\"Red\">Duplicate</font>";
        }
        
        $docID = $docCreator->createImportDocument($formID,$hasTempPage,$tempID,$title,$docNo,$docDate,"","",$from,$to,"",$recvRegNo,0,$createInDMS, $parentMode, $DMSParentID);
        //$docID = $docCreator->createFromPost ( $formID, $hasTempPage, $tempID, $createInDMS, $parentMode, $DMSParentID );
        
        $transCreator = new DFTransaction ( );
        //$transCreator->externalOrg ( UTFEncode ( $sendName ) );
        $transCreator->externalOrg ( UTFDecode ( $sendName ) );
        $transID = $transCreator->createMainTransaction ( $docID, $originTransType, 1, $securityModifier, $speedModifier );
        echo $sendName."<br>";
        echo $attendName."<br>";
        $response = $transCreator->createReceiveTransaction ( $transID, $recvType, $docDeliverType, 1, $regBookID, $recvRegNo, 0, 0, 0, 0, 0, $recvDate, $recvTime, UTFEncode($sendName), $sendOrg, UTFEncode($attendName), $attengOrg ,0,0,0,3,$sessionMgr->getCurrentAccID(),$sessionMgr->getCurrentRoleID(),$sessionMgr->getCurrentOrgID());
        
        
        list($transMainID2,$transMainSeq2,$transID2) = split("_",$response['transID']);
        if($response['transID'] != '') {
            echo "Attach Command to transID [{$response['transID']}]<br/>";
            
            $command = new CommandEntity();    
            $command->f_cmd_id = $sequence->get ( 'cmdID' ); 
            $command->f_trans_main_id = $transMainID2;
            $command->f_trans_main_seq = $transMainSeq2;
            $command->f_sub_trans_id = $transID2;
            $command->f_acc_id = $sessionMgr->getCurrentAccID();
            $command->f_cmd_org_id = $sessionMgr->getCurrentOrgID();
            $command->f_cmd_role_id = $sessionMgr->getCurrentRoleID();
            $command->f_timestamp = time();
            $command->f_command_text = $commandText;
            $command->f_type = 0;
            $command->f_cmd_acc_id = $sessionMgr->getCurrentAccID();
            
            var_dump($command);
            
            try{
               $command->Save();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo "<br/>";
            }
            unset($command);
        }
        
        
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
                }
            }
        }
        echo json_encode ( $response );
    }
}
