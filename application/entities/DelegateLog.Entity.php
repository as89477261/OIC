<?php
/**
 * Entity Class for Table [tbl_delegate_log]
 * Extended from ADODB_Active_Record Class
 * Generated by SLC Entity Class Generator 1.0
 * Generate Date : 11/06/2008 , 23:38:34
 */
/**
 * Entity Class �� Delegate Log
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class DelegateLogEntity extends ADODB_Active_Record {
	public $_table = 'tbl_delegate_log';
	public $f_task_type;
	public $f_task_id;
	public $f_old_owner_type;
	public $f_old_owner_uid;
	public $f_old_owner_role_id;
	public $f_old_owner_org_id;
	public $f_new_owner_type;
	public $f_new_owner_uid;
	public $f_new_owner_role_id;
	public $f_new_owner_org_id;
	public $f_delegator_uid;
	public $f_delegator_role_id;
	public $f_delegator_org_id;
	public $f_timestamp;
}