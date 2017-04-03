<?php
/**
 * Entity เก็บประวัติการยืม-คืน
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 */
class BorrowRecordEntity extends ADODB_Active_Record {
	public $_table = 'tbl_borrow_record';
	public $f_borrow_id;
	public $f_doc_id;
	public $f_borrow_uid;
	public $f_borrower_uid;
	public $f_due_date;
    public $f_return_flag;
    public $f_detail;
}
