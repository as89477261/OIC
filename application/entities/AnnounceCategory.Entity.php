<?php
/**
 * Entity Class เก็บประเภทคำสั่ง/ประกาศ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class AnnounceCategoryEntity extends ADODB_Active_Record {
	public $_table = 'tbl_announce_category';
	public $f_announce_cat_id;
	public $f_announce_type;
	public $f_name;
	public $f_desc;
	public $f_status;
}