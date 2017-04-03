<?php
/**
 * Utility ÊÓËÃÑº Form
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
	 * ¢Í Instance ¢Í§ FormUTil
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
	 * ¢Íª×èÍ¿ÔÅ´ìàÅ¢·ÕèË¹Ñ§Ê×Í
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
	 * ¢Íª×èÍ¿ÔÅ´ìª×èÍàÃ×èÍ§
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

