<?php
/**
 * Entity Class for Table [tbl_organize]
 * Extended from ADODB_Active_Record Class
 * Generated by SLC Entity Class Generator 1.0
 * Generate Date : 11/06/2008 , 23:38:34
 */
/**
 * Entity Class ���ç���ҧ˹��§ҹ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class OrganizeEntity extends ADODB_Active_Record {
	public $_table = 'tbl_organize';
	public $f_org_id;
	public $f_org_pid;
	public $f_org_name;
	public $f_org_desc;
	public $f_org_type;
	public $f_org_status;
	public $f_ext_code;
	public $f_int_code;
	public $f_is_root;
    public $f_allow_int_doc_no=1;
}
