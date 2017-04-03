<?php

/**
 * Entity Class เก็บวัตถุประสงค์ดำเนินการ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class PurposeEntity extends ADODB_Active_Record {
	public $_table = 'tbl_purpose';
	public $f_purpose_id;
	public $f_purpose_name;
	public $f_desc;
	public $f_status;
	
}
