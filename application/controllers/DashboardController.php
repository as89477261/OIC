<?php
/**
 * โปรแกรมแสดง Summary Dashboard 
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
	 * แสดงหน้าจอ Dashboard สำหรับงานสารบรรณ
	 *
	 */
	function docflowAction() {
		$output = $this->ECMView->render ( 'df.phtml' );
		echo $output;
	}

}
