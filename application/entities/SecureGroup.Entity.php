<?php
/**
 * Entity Class เก็บ Secure Group
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class SecureGroupEntity extends ADODB_Active_Record {
	public $_table = 'tbl_secure_group';
	public $f_secure_id;
	public $f_secure_group_name;
	public $f_inherit_flag;
	public $f_delete;
	public $f_owner_id;
	public $f_create_stamp;
	public $f_delete_stamp;
	public $f_last_update_id;
	public $f_last_update_uid;
	public $f_last_update_stamp;
	public $f_allow_global_use;
}
