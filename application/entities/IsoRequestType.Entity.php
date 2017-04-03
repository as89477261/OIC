<?php
/**
 * Entity Class เก็บ Request ขอ Approve ISO Document
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class IsoRequestType extends ADODB_Active_Record {
	public $_table = 'tbl_iso_request_type';
    public $f_iso_req_type;
    public $f_desc;
}
