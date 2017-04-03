<?php
/**
 * ����� Batch ���ҧ˹��§ҹ��¹͡�ҡ Database OIC
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Batch
 */
class RebuildDataController extends ECMController {
    /**
     * Initialization method
     */
    public function init() {
        $this->setupECMActionController ();
        $this->setECMViewModule ( 'default' );
    }
    /**
     * action /index Forbidden
     *
     */
    public function indexAction() {
        echo "Access Denied";
    }
    
    /**
     * action /ext-contact-list ���ҧ external contact list
     *
     */
    public function extContactListAction() {
        global $conn;
        
        //include_once "ContactList.Entity.php";
        
        echo "External Contact List Rebuild<br/>";
        /* ��Сѹ���Ե */
        $cl = array();
        $sqlType1 = "select * from tbl_external_sender where f_ext_sender_name <> ' ' and f_ext_sender_name  not like 'tes%' and f_ext_sender_name is not null and  f_ext_sender_code like '1%'";
        $rsType1 = $conn->Execute($sqlType1);
        foreach($rsType1 as $rowType1) {
            checkKeyCase($rowType1);
            $cl[] = array(
                'dataid'=>$rowType1['f_ext_sender_id'],
                'name'=>UTFEncode($rowType1['f_ext_sender_name']),
                'description'=>UTFEncode('�.��Сѹ���Ե'),
                'typeid'=>1
            );
        }
        $clEntity = new ContactListEntity();
        if(!$clEntity->Load("f_cl_id = '1999999999999'")) {
            $clEntity->f_cl_id = '1999999999999';
            $clEntity->f_cl_name = '�.��Сѹ���Ե';
            $clEntity->f_cl_public = 3;
            $clEntity->f_cl_to_list = addslashes(json_encode($cl));
            $clEntity->Save();
        }   else {
            $clEntity->f_cl_name = '�.��Сѹ���Ե';
            $clEntity->f_cl_public = 3;
            $clEntity->f_cl_to_list = addslashes(json_encode($cl));
            $clEntity->Update();
        }
        unset($cl);
        unset($clEntity);
        
        echo "Generate Type #1 Complete<br/>";
        
        /* ��Сѹ�Թ�� */
        $cl = array();
        $sqlType2 = "select * from tbl_external_sender where f_ext_sender_name <> ' ' and f_ext_sender_name  not like 'tes%' and f_ext_sender_name is not null and  f_ext_sender_code like '2%'";
        $rsType2 = $conn->Execute($sqlType2);
        foreach($rsType2 as $rowType2) {
            checkKeyCase($rowType2);
            $cl[] = array(
                'dataid'=>$rowType2['f_ext_sender_id'],
                'name'=>UTFEncode($rowType2['f_ext_sender_name']),
                'description'=>UTFEncode('�.��Сѹ�Թ��'),
                'typeid'=>2
            );
        }
        $clEntity = new ContactListEntity();
        if(!$clEntity->Load("f_cl_id = '1999999999998'")) {
            $clEntity->f_cl_id = '1999999999998';
            $clEntity->f_cl_name = '�.��Сѹ�Թ��';
            $clEntity->f_cl_public = 3;
            $clEntity->f_cl_to_list = addslashes(json_encode($cl));
            $clEntity->Save();
        }   else {
            $clEntity->f_cl_name = '�.��Сѹ�Թ��';
            $clEntity->f_cl_public = 3;
            $clEntity->f_cl_to_list = addslashes(json_encode($cl));
            $clEntity->Update();
        }
        unset($cl);
        unset($clEntity);
        
        echo "Generate Type #2 Complete<br/>";
        
        /* ���˹��/�á���� */
        $cl = array();
        $sqlType3 = "select * from tbl_external_sender where f_ext_sender_name <> ' ' and f_ext_sender_name  not like 'tes%' and f_ext_sender_name is not null and  f_ext_sender_code like '3%'";
        $rsType3 = $conn->Execute($sqlType3);
        foreach($rsType3 as $rowType3) {
            checkKeyCase($rowType3);
            $cl[] = array(
                'dataid'=>$rowType3['f_ext_sender_id'],
                'name'=>UTFEncode($rowType3['f_ext_sender_name']),
                'description'=>UTFEncode('�.���˹��/������'),
                'typeid'=>3
            );
        }
        $clEntity = new ContactListEntity();
        if(!$clEntity->Load("f_cl_id = '1999999999997'")) {
            $clEntity->f_cl_id = '1999999999997';
            $clEntity->f_cl_name = '�.���˹��/�á����';
            $clEntity->f_cl_public = 3;
            $clEntity->f_cl_to_list = addslashes(json_encode($cl));
            $clEntity->Save();
        }   else {
            $clEntity->f_cl_name = '�.���˹��/�á����'; 
            $clEntity->f_cl_public = 3;
            $clEntity->f_cl_to_list = addslashes(json_encode($cl));
            $clEntity->Update();
        }
        unset($cl);
        unset($clEntity);
        
        echo "Generate Type #3 Complete<br/>";
        
        /* ��Сѹ������ */
        $cl = array();
        $sqlType4 = "select * from tbl_external_sender where f_ext_sender_name <> ' ' and f_ext_sender_name  not like 'tes%' and f_ext_sender_name is not null and f_ext_sender_code like '1%'
        union 
        select * from tbl_external_sender where f_ext_sender_name <> ' ' and f_ext_sender_name  not like 'tes%' and f_ext_sender_name is not null and f_ext_sender_code like '2%'
        union 
        select * from tbl_external_sender where f_ext_sender_name <> ' ' and f_ext_sender_name  not like 'tes%' and f_ext_sender_name is not null and f_ext_sender_code like '3%'";
        
        $rsType4 = $conn->Execute($sqlType4);
        foreach($rsType4 as $rowType4) {
            checkKeyCase($rowType4);
            if(substr($rowType4['f_ext_sender_code'],0,1) == 1) {
                $desc = "�.��Сѹ���Ե";
            }
            if(substr($rowType4['f_ext_sender_code'],0,1) == 2) {
                $desc = "�.��Сѹ�Թ��";
            }
            if(substr($rowType4['f_ext_sender_code'],0,1) == 3) {
                $desc = "�.���˹��/������";
            }
            $cl[] = array(
                'dataid'=>$rowType4['f_ext_sender_id'],
                'name'=>UTFEncode($rowType4['f_ext_sender_name']),
                'description'=>UTFEncode($desc),
                'typeid'=>3
            );
        }
        $clEntity = new ContactListEntity();
        if(!$clEntity->Load("f_cl_id = '1999999999996'")) {
            $clEntity->f_cl_id = '1999999999996';
            $clEntity->f_cl_name = '�.��Сѹ������';
            $clEntity->f_cl_public = 3;
            $clEntity->f_cl_to_list = addslashes(json_encode($cl));
            $clEntity->Save();
        } else {
            $clEntity->f_cl_name = '�.��Сѹ������'; 
            $clEntity->f_cl_public = 3;
            $clEntity->f_cl_to_list = addslashes(json_encode($cl));
            $clEntity->Update();
        }
        unset($cl);
        unset($clEntity);
        
        echo "Generate Type #4 Complete<br/>";
    }
}
