<?php
/**
 * Entity Class for Table [tbl_media]
 * Extended from ADODB_Active_Record Class
 * Generated by SLC Entity Class Generator 1.0
 * Generate Date : 11/06/2008 , 23:38:34
 */
/**
 * Entity Class �� CD/DVD MEdia
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class MediaEntity extends ADODB_Active_Record {
	public $_table = 'tbl_media';
	public $f_media_id;
	public $f_media_type;
	public $f_media_name;
	public $f_media_size;
	public $f_current_size;
	public $f_global_access;
	public $f_owner_type;
	public $f_owner_uid;
	public $f_owner_role_id;
	public $f_owner_org_id;
	public $f_build;
	public $f_status;
	public $f_deleted;
}