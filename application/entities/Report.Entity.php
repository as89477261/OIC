<?php
/**
 * Entity Class for Table [tbl_report]
 * Extended from ADODB_Active_Record Class
 * Generated by SLC Entity Class Generator 1.0
 * Generate Date : 11/06/2008 , 23:38:34
 */
/**
 * Entity Class เก็บรายการรายงาน
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class ReportEntity extends ADODB_Active_Record {
	public $_table = 'tbl_report';
	public $f_report_id;
	public $f_report_cat_id;
	public $f_report_name;
	public $f_report_access_level;
}