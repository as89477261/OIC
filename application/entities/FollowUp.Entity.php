<?php
/**
 * Entity Class เก็บงานที่ต้องติดตาม
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class FollowUpEntity extends ADODB_Active_Record {
	public $_table = "tbl_follow_up";
	public $f_follow_id;
	public $f_trans_main_id;
	public $f_trans_main_seq;
	public $f_trans_id;
	public $f_owner_uid;
	public $f_notify;
	public $f_expect_timestamp;
}
