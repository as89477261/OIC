<?php
/**
 * Entity Class for Table [tbl_report_parameter]
 * Extended from ADODB_Active_Record Class
 * Generated by SLC Entity Class Generator 1.0
 * Generate Date : 11/06/2008 , 23:38:34
 */
/**
 * Entity Class เก็บรายการ Parameter ของรายงาน
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class ReportParameterEntity extends ADODB_Active_Record {
	public $_table = 'tbl_report_parameter';
	public $f_report_id;
	public $f_param_id;
	public $f_param_name;
	public $f_param_type;
	public $f_param_default;
}