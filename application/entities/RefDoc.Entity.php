<?php
/**
 * Entity Class เก็บการอ้างอิงระหว่างเอกสาร
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class RefDocEntity extends ADODB_Active_Record {
    public $_table = 'tbl_ref_doc';
    public $f_doc_id;
    public $f_ref_doc_id;
    public $f_ref_type;
}
