<?php
/**
 * ������Ѵ��ÿ�����͡���
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Form
 * 
 *
 */
class FormController extends Zend_Controller_Action {
	/**
	 * action /instantiate ������ʴ������������ҧ���
	 *
	 */
	public function instantiateAction() {
		global $config;
		//global $sequence;
		//global $store;
		
		$formPath = $config['formPath'];
		$formID = $_POST['formID'];
		$tempID = $_POST['tempID'];
		$formDesign = "{$formPath}designedForm_{$formID}.html";
		$formContent = file_get_contents($formDesign);
		$formName = "instance_{$tempID}";
		$formMarkUp = "<form id=\"{$formName}\" name=\"{$formName}\">$formContent</form>";
		
		echo $formMarkUp;
	}
}
