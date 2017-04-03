<?php
/**
 * Entity Class เก็บข้อมูลอ้างอิเอกสาร/คำสั่ง-ประกาศ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class RefAnnounceEntity extends ADODB_Active_Record {
    public $_table = 'tbl_ref_announce';
    public $f_doc_id;
    public $f_announce_id;
}
