<?php
/**
 * Entity Class �红����ż���Ѻ/����¹͡˹��§ҹ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class ExternalSenderEntity extends ADODB_Active_Record {
	public $_table = 'tbl_external_sender';
	public $f_ext_sender_id;
	public $f_ext_sender_name;
	public $f_ext_sender_freq;
	public $f_ext_sender_status;
	public $f_ext_sender_code=0;
}
