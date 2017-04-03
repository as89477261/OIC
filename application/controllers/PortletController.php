<?php
/**
 * โปรแกมทำการ Load Portlet
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 * 
 *
 */

class PortletController extends Zend_Controller_Action {
	/**
	 * action /get-portlet-content ทำการแสดงผล Porltet
	 *
	 */
	public function getPortletContentAction() {
		checkSessionPortlet();
		$portletClass = $this->getRequest()->getParam('portletClass');
		$portletMethod = $this->getRequest()->getParam('portletMethod');
		if(class_exists($portletClass)) {
			$portletInstance = new $portletClass;
			echo $portletInstance->{$portletMethod}();	
		}else{
			echo "portlet not exists";
		}
	}
}
