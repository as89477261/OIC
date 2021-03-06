<?php
/**
 * Entity Class for Table [tbl_contact_list]
 * Extended from ADODB_Active_Record Class
 * Generated by SLC Entity Class Generator 1.0
 * Generate Date : 11/06/2008 , 23:38:34
 */

/**
 * Entity Class �� Contact List
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class ContactListEntity extends ADODB_Active_Record {
	public $_table = 'tbl_contact_list';
	public $f_cl_id;
	public $f_cl_name;
	public $f_cl_public;
	public $f_cl_owner_type;
	public $f_cl_owner_uid;
	public $f_cl_owner_role_id;
	public $f_cl_owner_org_id;
	public $f_cl_to_list_hidden;
	public $f_cl_cc_list_hidden;
	public $f_cl_to_list;
	public $f_cl_cc_list;
}