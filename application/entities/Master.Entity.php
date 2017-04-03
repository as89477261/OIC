<?php
/**
 * Entity Class เก็บ Master 
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class MasterEntity extends ADODB_Active_Record {
	public $_table = 'tbl_master';
	public $f_mas_id;
	public $f_ctl_id;
	public $f_name;
	public $f_description;
	public $f_value;
	public $f_status;
}
