<?php
/**
 * Entity Class for Table [tbl_workflow_participant]
 * Extended from ADODB_Active_Record Class
 * Generated by SLC Entity Class Generator 1.0
 * Generate Date : 11/06/2008 , 23:38:34
 */
/**
 * Entity Class �� Workflow Participant
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package entity
 * @category entity
 *
 */
class WorkflowParticipantEntity extends ADODB_Active_Record {
	public $_table = 'tbl_workflow_participant';
	public $f_wf_node_id;
	public $f_part_type;
	public $f_part_uid;
	public $f_part_role_id;
	public $f_part_org_id;
	public $f_use_server_fn;
	public $f_part_function;
}