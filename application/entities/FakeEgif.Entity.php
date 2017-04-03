<?php

class FakeEgifEntity  extends ADODB_Active_Record {
	public $_table = 'tbl_fake_egif';
	public $f_egif_trans_id;
	public $f_ref_trans_id;
	public $f_book_no;
	public $f_book_date;
	public $f_title;
	public $f_description;
	public $f_speed;
	public $f_security;
	public $f_sender_fullname;
	public $f_sender_org_full;
	public $f_receiver_fullname;
	public $f_receiver_org_full;
	public $f_attach_1_name;
	public $f_attach_1_base64;
	public $f_attach_2_name;
	public $f_attach_2_base64;
	public $f_attach_3_name;
	public $f_attach_3_base64;
	public $f_attach_4_name;
	public $f_attach_4_base64;
	public $f_attach_5_name;
	public $f_attach_5_base64;
	public $f_ref_url;
	public $f_doc_id;
	public $f_received;
	public $f_received_reg_no;
}
