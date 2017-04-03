<?php
/**
 * Utility ����Ѻ Form
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Form
 */
class FormUtil {
	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		//include_once 'Form.Entity.php';	
		//include_once 'FormStructure.Entity.php';
	}
	
	/**
	 * �� Instance �ͧ FormUTil
	 *
	 * @return Object
	 */
	public function getInstance() {
		static $instance;
		if(!isset($instance)) {
			$instance = new FormUtil();
		} 
		return $instance;
	}
	
	/**
	 * �ͪ��Ϳ�Ŵ��Ţ���˹ѧ���
	 *
	 * @param int $formID
	 * @return string
	 */
	public function getDocnoStructure($formID) {
		$formStructures = new FormStructureEntity();
		if(!$formStructures->Load("f_form_id = '$formID' and f_is_doc_no = '1'")) {
			return false;
		} else {
			return $formStructures->f_struct_id;
		}
	}
    
	/**
	 * �ͪ��Ϳ�Ŵ��������ͧ
	 *
	 * @param int $formID
	 * @return string
	 */
    public function getTitleDocnoStructure($formID) {
        $formStructures = new FormStructureEntity();
        if(!$formStructures->Load("f_form_id = '$formID' and f_is_title = '1'")) {
            return false;
        } else {
            return $formStructures->f_struct_id;
        }
    }
}

