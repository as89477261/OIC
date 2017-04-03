<?php
/**
 * Entity Class เก็บคำสั่ง/ประกาศ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class AnnounceEntity extends ADODB_Active_Record {
	public $_table = 'tbl_announce';
	public $f_announce_id;
	public $f_announce_type;
	public $f_announce_category;
	public $f_title;
	public $f_detail;
    public $f_announce_date;
    public $f_announce_no;
	public $f_announce_stamp;
	public $f_announce_sys_stamp;
	public $f_sign_uid;
	public $f_sign_role;
	public $f_remark;
	public $f_year;
	public $f_delete;
    public $f_delete_uid;
    public $f_announce_org_id;
	public $f_announce_org_name;
	public $f_announce_user;
}
