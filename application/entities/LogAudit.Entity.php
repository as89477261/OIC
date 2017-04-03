<?php
/**
 * Entity Class เก็บ Audit Log
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class LogAuditEntity extends ADODB_Active_Record {
	public $_table = 'tbl_log_audit';
	public $f_audit_log_id;
	public $f_acc_id;
	public $f_object_type;
    public $f_activity_type=0;
	public $f_object_id;
	public $f_message;
	public $f_ip_address;
	public $f_timestamp;
	public $f_archived;
}
