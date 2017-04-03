<?php
/**
 * Spool File Import
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category importXZZ
 *
 */
class SpoolImportController extends ECMController  {
	public function init() {
		$this->setupECMActionController ();
        $this->setECMViewModule ( 'default' );
	}
	
	public function indexAction() {
		echo "<h1>Spool file import utility</h1>";
		echo "<hr/>";
	}
}
