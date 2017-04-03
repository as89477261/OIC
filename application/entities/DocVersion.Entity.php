<?php
/**
 * Entity Class เก็บ Version ของเอกสาร
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class DocVersionEntity extends ADODB_Active_Record {
    public $_table = 'tbl_doc_version';
    public $f_doc_id;
    public $f_revision;
    public $f_version;
    public $f_uid;
    public $f_timestamp;
}