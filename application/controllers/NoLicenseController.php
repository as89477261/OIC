<?php
/**
 * โปรแกรมแสดงการไม่มี License
 *
 */
class NoLicenseController extends ECMController {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'default' );
	}
	
	/**
	 * แสดงหน้าจอไม่มี License
	 *
	 */
	public function indexAction() {
		global $cache;
		global $license;
		global $util;
		
		if(!$license->noLicense() ){
			$util->redirect("/Index");
			//$this->_redirector->gotoUrl('/Index');
		}
		
		$cacheID = "NoLicensePage";
		$templateName = 'nolicense.phtml';
		
		
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
	
	/**
	 * แสดงหน้าจอไม่มี Cluster License
	 *
	 */
	public function clusterAction() {
		global $cache;
		global $license;
		global $util;
		
		$cacheID = "NoClusterLicensePage";
		$templateName = 'noclusterlicense.phtml';
		
		
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

