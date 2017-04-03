<?php

class EgifGatewayController extends ECMController {
	public function recvAckAction() {
		list ( $transMainID, $sendID, $sendSeq ) = explode ( "_", $_POST ['f_ref_trans_id'] );
		$transSend = new TransDfSendEntity ( );
		$transSend->Load ( "f_send_trans_main_id = '$transMainID' and f_send_trans_main_seq = '$sendID' and f_send_id = '$sendSeq'" );
		$transSend->f_received = 1;
		$transSend->f_egif_recv_reg_no = $_POST ['f_received_reg_no'];
		$transSend->Update ();
	}
	
	public function rejectAckAction() {
		list ( $transMainID, $sendID, $sendSeq ) = explode ( "_", $_POST ['f_ref_trans_id'] );
		$transSend = new TransDfSendEntity ( );
		$transSend->Load ( "f_send_trans_main_id = '$transMainID' and f_send_trans_main_seq = '$sendID' and f_send_id = '$sendSeq'" );
		$transSend->f_received = 2;
		$transSend->Update ();
	}
	
	public function sendAction() {
		global $sequence;
		global $util;
		global $config;
		global $sessionMgr;
		
		if (! $sequence->isExists ( 'egif' )) {
			$sequence->create ( 'egif' );
		}
		
		$orgMain = new OrganizeEntity ( );
		$orgMain->Load ( 'f_org_id = 0' );
		
		$fakeEgif = new FakeEgifEntity ( );
		$fakeEgif->f_egif_trans_id = $sequence->get ( 'egif' );
		$fakeEgif->f_ref_trans_id = $_POST ['f_ref_trans_id'];
		$fakeEgif->f_book_no = $_POST ['f_book_no'];
		$fakeEgif->f_book_date = $_POST ['f_book_date'];
		$fakeEgif->f_title = $_POST ['f_title'];
		$fakeEgif->f_description = $_POST ['f_description'];
		
		$fakeEgif->f_speed = $_POST ['f_security'];
		$fakeEgif->f_security = $_POST ['f_security'];
		$fakeEgif->f_sender_fullname = $_POST ['f_sender_fullname'];
		$fakeEgif->f_sender_org_full = $_POST ['f_sender_org_full'];
		$fakeEgif->f_receiver_fullname = $_POST ['f_receiver_fullname'];
		$fakeEgif->f_receiver_org_full = $_POST ['f_receiver_org_full'];
		$fakeEgif->f_ref_url = $_POST ['f_ref_url'];
		
		$document = new Document ( );
		$docID = $document->createDocument ( $fakeEgif->f_title, $fakeEgif->f_book_no, $fakeEgif->f_book_date, $fakeEgif->f_description, $fakeEgif->f_sender_org_full, $orgMain->f_org_name );
		$fakeEgif->f_doc_id = $docID;
		$fakeEgif->f_received = 0;
		$fakeEgif->Save ();
		
		$sequencePageNo = 'docID_' . $docID;
		$sequencePageID = 'pageDoc_' . $docID;
		
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		if (! $sequence->isExists ( $sequencePageID )) {
			$sequence->create ( $sequencePageID );
		}
		
		$egifFakeFile = "c:/document.pdf";
		$extension = 'pdf';
		$uploadFilename= 'document.pdf';
		$sysFilename = uniqid ( 'file_' );
		$dav = new DAVStorage ( );
		$DocPage = new DocPageEntity ( );
		$DocPage->f_doc_id = $docID;
		$DocPage->f_page_id = $sequence->get ( $sequencePageID );
		$DocPage->f_page_no = $sequence->get ( $sequencePageNo );
		$DocPage->f_major_version = 1;
		$DocPage->f_minor_version = 0;
		$DocPage->f_branch_version = 0;
		$dav->connectToDefault ();
		$DocPage->f_st_id = $dav->getStorageID ();
		$DocPage->f_mime_type = $util->getMimeType ( $extension );
		$DocPage->f_orig_file_name = $uploadFilename;
		$DocPage->f_sys_file_name = $sysFilename;
		$DocPage->f_extension = $extension;
		$DocPage->f_file_size = filesize ( $egifFakeFile);
		$DocPage->f_moved_to_storage = 1;
		$DocPage->f_fulltext_indexed = 0;
		$DocPage->f_deleted = 0;
		$DocPage->f_delete_user = 0;
		$DocPage->f_create_stamp = time ();
		$DocPage->f_create_uid = $sessionMgr->getCurrentAccID ();
		$DocPage->f_create_role_id = $sessionMgr->getCurrentRoleID ();
		$DocPage->f_create_org_id = $sessionMgr->getCurrentOrgID ();
		$DocPage->Save ();
		
		if ($dav->save ( 'DF', "{$sysFilename}.{$extension}", file_get_contents ( $egifFakeFile ) )) {
		
		} else {
			//do something
		}
		$dav->disconnect ();
		unset ( $DocPage );
	
	}
}

