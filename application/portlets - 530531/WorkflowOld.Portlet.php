<?php
/**
 * Portlet : งาน Workflow Version เก่า
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class WorkflowOldPortlet {
	public function getUI() {
		checkSessionPortlet();
		global $sessionMgr;
        global $config;
		return "<iframe width=\"100%\" height=\"100%\" src=\"{$config ['integration']['benefitUrl']}\"></iframe>"; 
	}
}

