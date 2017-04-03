<?php
/**
 * Entity Class เก็บ Transaction การอ่านงานสารบรรณ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */

class TransDfReadEntity extends ADODB_Active_Record {
	public $_table = 'tbl_trans_df_read';
	public $f_trans_main_id;
	public $f_trans_main_seq;
	public $f_read_circ_uid;
	public $f_doc_id;
}
