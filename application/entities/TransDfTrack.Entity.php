<?php
/**
 * Entity Class เก็บ การติดตามงานสารบรรณ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class TransDfTrackEntity extends ADODB_Active_Record {
	public $_table = "tbl_trans_df_track";
	public $f_trans_main_id;
	public $f_acc_id;
	public $f_deadline;
	public $f_deadline_date;
	public $f_deadline_time;
	public $f_delete;
}
