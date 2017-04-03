<?php
/**
 * Class ECMController เป็น Class ที่ Extend มาจาก Zend_Controller_Action
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0
 * @package classes
 * @category System
 */
class ECMController extends Zend_Controller_Action {
	/**
	 * Protected member ECMView : Custom Instance for Zend_View Class
	 *
	 * @var Zend_View
	 */
	protected $ECMView;
	
	/**
	 * setup an ECM Action Controller
	 */
	public function setupECMActionController() {
		// Load Class Zend_View via Zend_Loader
		Zend_Loader::loadClass ( 'Zend_View' );
		
		// Inherit Custom Zend_View to protected member ECMView
		$this->ECMView = new Zend_View ( );
		
		// Setup view script path
		$this->setECMViewModule ();
		
		// Setup a redirector module
		$this->_redirector = $this->_helper->getHelper ( 'Redirector' );
	}
	
	/**
	 * Setup a view module for Zend_View Custom Instance ECMView
	 *
	 * @param string $module
	 */
	public function setECMViewModule($module = 'default') {
		global $config;
		
		if ($module == 'default') {
			$this->ECMView->setScriptPath ( "{$config ['appPath']}application/views/default/" );
		} else {
			$this->ECMView->setScriptPath ( "{$config ['appPath']}application/views/{$module}/" );
		}
	}
}