<?php
/**
 * ������ʴ� Summary Dashboard 
 *
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */
class DashboardController extends ECMController {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'dashboard' );
	}
	
	/**
	 * �ʴ�˹�Ҩ� Dashboard ����Ѻ�ҹ��ú�ó
	 *
	 */
	function docflowAction() {
		$output = $this->ECMView->render ( 'df.phtml' );
		echo $output;
	}

}
