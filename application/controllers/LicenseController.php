<?php
/**
 * โปรแกรมแสดง License
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 */
class LicenseController extends ECMController {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'default' );
	}
	
	/**
	 * action /index หน้าจอแสดง License
	 *
	 */
	public function indexAction() {
		global $cache;
		global $license;
		
		$cacheID = "LicenseInfoPage";
		$templateName = 'licenseInfo.phtml';
		$this->ECMView->assign('License',$license->getLicenseStatus());
		
		if (! ($output = $cache->load ( $cacheID ))) {
			//No cache exists,generate output first and cache it
			Logger::log ( 0, 0, "Cache does not exists [{$cacheID}]", true, true );
			$output = $this->ECMView->render ( $templateName );
			if (! $config ['disableZendCache']) {
				$cache->save ( $output, $cacheID );
			}
		} else {
			//Cache hit
			Logger::log ( 0, 0, "Cache hit [{$cacheID}]", true, false );
		}
		echo $output;
	}
}

