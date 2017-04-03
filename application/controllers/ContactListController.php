<?php

/**
 * โปรแกรมจัดการ Contact List
 * 
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */

class ContactListController extends ECMController {
	/**
	 * The default action ไม่มีอะไรให้ทำ
	 */
	public function indexAction() {
		// TODO: Auto-generated ContactListController::indexAction() default action
	}
	
	/**
	 * action /save/ บันทึก Contact List
	 *
	 */
	public function saveAction() {
		global $sessionMgr;
		global $sequence;
		
		//include_once 'ContactList.Entity.php';
		$listName = $_POST ['listName'];
		$sendTo = $_POST ['sendTo'];
		$sendCC = $_POST ['sendCC'];
		if (! $sequence->isExists ( 'contactListID' )) {
			$sequence->create ( 'contactListID' );
		}
		$contactList = new ContactListEntity ( );
		$response = Array ();
		if (! $contactList->Load ( "f_cl_owner_uid = '{$sessionMgr->getCurrentAccID()}' and f_cl_name = '{$listName}'" )) {
			$contactList->f_cl_id = $sequence->get ( 'contactListID' );
			$contactList->f_cl_name = UTFDecode( $listName );
			$contactList->f_cl_owner_uid = $sessionMgr->getCurrentAccID ();
			//$contactList->f_cl_to_list = addslashes ( UTFDecode( $sendTo ) );
			//$contactList->f_cl_cc_list = addslashes ( UTFDecode( $sendCC ) );
			$contactList->f_cl_to_list = UTFDecode( $sendTo );
			$contactList->f_cl_cc_list = UTFDecode( $sendCC );			
            $contactList->f_cl_public = 0;
			$contactList->Save ();
			$response ['success'] = 1;
		} else {
			$response ['success'] = 0;
		}
		echo json_encode ( $response );
	}
    
	/**
	 * action /sace-external/ บันทึก Contact List ภายนอก
	 *
	 */
    public function saveExternalAction() {
        global $sessionMgr;
        global $sequence;
        //include_once 'ContactList.Entity.php';
        $listName = $_POST ['listName'];
        $sendTo = $_POST ['sendTo'];
        $sendCC = $_POST ['sendCC'];
        if (! $sequence->isExists ( 'contactListID' )) {
            $sequence->create ( 'contactListID' );
        }
        $contactList = new ContactListEntity ( );
        $response = Array ();
        if (! $contactList->Load ( "f_cl_owner_uid = '{$sessionMgr->getCurrentAccID()}' and f_cl_name = '{$listName}'" )) {
            $contactList->f_cl_id = $sequence->get ( 'contactListID' );
            $contactList->f_cl_name = UTFDecode( $listName );
            $contactList->f_cl_owner_uid = $sessionMgr->getCurrentAccID ();
            $contactList->f_cl_to_list = UTFDecode( $sendTo );
            $contactList->f_cl_cc_list = UTFDecode( $sendCC );
            //$contactList->f_cl_to_list = addslashes ( UTFDecode( $sendTo ) );
            //$contactList->f_cl_cc_list = addslashes ( UTFDecode( $sendCC ) );            
            $contactList->f_cl_public = 2;
            $contactList->Save ();
            $response ['success'] = 1;
        } else {
            $response ['success'] = 0;
        }
        echo json_encode ( $response );
    }
	
    /**
     * action /delete/ ลบ contact list
     *
     */
	public function deleteAction() {
		global $sessionMgr;
	
		$listName = UTFDecode ( $_POST ['contactList'] ) ;
		$userD = $_SESSION ['accID'];
		
		$contactList = new ContactListEntity ( );	
		$response = Array ();	
        if ( $contactList->Load ( " f_cl_name = '{$listName}' and f_cl_owner_uid = '{$userD}' and (f_cl_public = '0' or f_cl_public = '2') " )) {        
	        $contactList->Delete ();
	        $response ['success'] = 1;
        } else {
	        $response ['success'] = 0;
        }
		echo json_encode ( $response );
	}
	
	/**
	 * action /load/ โหลดข้อมูล contact list
	 *
	 */
	public function load() {
	
	}

}
