<?php
/**
 * Entity Class เก็บ การมอบหมายงาน
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class OrderEntity extends ADODB_Active_Record {
	public $_table = 'tbl_order';
	public $f_order_id;
	public $f_trans_main_id;
	public $f_trans_main_seq;
	public $f_trans_id;
	public $f_assign_uid;
	public $f_received_uid;
	public $f_complete;
	public $f_assigned_timestamp;
	public $f_report_text;
	public $f_expected_finish;
	public $f_report_timestamp;
	public $f_close_timestamp;
	public $f_org_id;
	public $f_complete_2;
	public $f_dismiss_recv_complete;
	public $f_dismiss_assign_complete;
}