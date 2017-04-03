<?php
/**
 * Class ����Ѻ��Ǩ�ͺ License
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category System
 */
class ECMLicense {
	/**
	 * License Loaded Array
	 *
	 * @var array
	 */
	private $loadLicenseArray;
	/**
	 * License Interpreted Array
	 *
	 * @var array
	 */
	private $ECMLicense;
	
	/**
	 * Concurrent Array
	 *
	 * @var array
	 */
	public $ECMConcurrent;
	
	/**
	 * ��˹����������鹢ͧ License Class
	 *
	 */
	public function __construct() {
		$this->ECMLicense = Array ();
		$this->loadLicenseArray = Array ();
		$this->initailize ();
	}
	
	/**
	 * �ӡ�� Load License
	 *
	 */
	public function initailize() {
		global $config;
		
		foreach ( $config ['module'] as $module => $activate ) {
			if ($activate) {
				if (! file_exists ( $config ['classPath'] . "licenses/{$module}.php" )) {
					Logger::log ( "99", $module, "loading license for module :  {$module} failed (file not found).", false, true );
				} else {
					$this->loadLicenseArray [] = $config ['classPath'] . "licenses/{$module}.php";
				}
			} else {
				$this->ECMLicense [$module] = array ();
				$this->ECMLicense [$module] ['status'] = false;
			}
			if(!defined('CONCURRENT_FILE')) {
				
					define('CONCURRENT_FILE',$config ['classPath'] . "concurrents/Concurrent-{$config['concurrent']}.php");	
			}
		}
	}
	
	public function noConcurrentLicense() {
		if(count($this->ECMConcurrent) ==0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * �ѧ��ѹ��Ǩ�ͺ�������� License 㹡�÷ӧҹ
	 *
	 * @return boolean
	 */
	public function noWorksLicense() {
		if($this->noConcurrentLicense()) {
			return true;
		}
		$modules = array ('Docflow', 'Workflow', 'CarBooking', 'RoomBooking', 'Meeting' );
		
		for($i = 0; $i < count ( $modules ); $i ++) {
			$idx = $modules [$i];
			if ($this->ECMLicense [$idx] ['status']) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * �ѧ��ѹ��Ǩ�ͺ����к������ License
	 *
	 * @return boolean
	 */
	public function noLicense() {
		if($this->noConcurrentLicense()) {
			return true;
		}
		$modules = array_keys ( $this->ECMLicense );
		for($i = 0; $i < count ( $modules ); $i ++) {
			$idx = $modules [$i];
			if ($this->ECMLicense [$idx] ['status']) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * �ӡ�û����� License
	 *
	 */
	public function startLicenseEngine() {
		/* unnkung */
			
		$this->ECMLicense ['Docflow'] ['status'] = true;
		$this->ECMLicense ['Workflow'] ['status'] = true;
		$this->ECMLicense ['Docimage'] ['status'] = true;
		$this->ECMLicense ['RoomBooking'] ['status'] = true;
		$this->ECMLicense ['CarBooking'] ['status'] = true;
		$this->ECMLicense ['Meeting'] ['status'] = true;
		$this->ECMLicense ['Cluster'] ['status'] = true;
		$this->ECMConcurrent['$mode']= 0x0F0F;
		$this->ECMConcurrent['$cons']= 9999999;
	
		include_once CONCURRENT_FILE;
		foreach ( $this->loadLicenseArray as $licenseFile ) {
			include_once $licenseFile;
		}
	}
	
	/**
	 * �ӡ�� Interprete License
	 *
	 * @param string $licenseName
	 * @return string
	 */
	public function check($licenseName) {
		switch ($licenseName) {
			case 'SARABAN' :
				return $this->ECMLicense ['Docflow'] ['status'];
				break;
			case 'WORKFLOW' :
				return $this->ECMLicense ['Workflow'] ['status'];
				break;
			case 'DOCUMENT' :
				return $this->ECMLicense ['Docimage'] ['status'];
				break;
			case 'KBASE' :
				return false;
			case 'ROOMBOOKING' :
				return $this->ECMLicense ['RoomBooking'] ['status'];
				break;
			case 'CARBOOKING' :
				return $this->ECMLicense ['CarBooking'] ['status'];
				break;
			case 'MEETING' :
				return $this->ECMLicense ['Meeting'] ['status'];
				break;
			case 'CLUSTER' :
				return $this->ECMLicense ['Cluster'] ['status'];
				break;
		}
	}
	
	/**
	 * �Ŵ License �͡
	 *
	 * @param unknown_type $licenseName
	 */
	public function unregister($licenseName) {
		$this->ECMLicense [$licenseName] = array ();
		$this->ECMLicense [$licenseName] ['status'] = false;
	}
	
	/**
	 * �ӡ�� reset concurrent license
	 *
	 */
	public function resetConcurrent() {
		$this->ECMConcurrent = array();
	}
	
	/**
	 * ŧ����¹ concurrent license
	 *
	 * @param Hex value $mode
	 * @param string $cons
	 */
	public function registerConcurrent($mode,$cons) {
		$this->ECMConcurrent['$mode'] = $mode;
		$this->ECMConcurrent['$cons'] = $cons;
	}
	
	/**
	 * ��Ǩ�ͺ�������ö���ҧ Concurrent �������ա�������
	 *
	 * @return boolean
	 */
	public function canCreateNewConcurrent() {
		global $config;
		
		
		if($this->ECMConcurrent['$mode']== 0x0F0F) {
			return true;
		} else {
			$systemMonitor = new SystemMonitor();
			$concurrentNo = (int)$config ['concurrent'];
			if($systemMonitor->getNonDegradeTotalConcurrents() < $concurrentNo) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * ŧ����¹ License
	 *
	 * @param unknown_type $licenseName
	 * @param unknown_type $licenseInfo
	 */
	public function register($licenseName, $licenseInfo) {
		$this->ECMLicense [$licenseName] = array ();
		$this->ECMLicense [$licenseName] ['status'] = true;
		$this->ECMLicense [$licenseName] ['info'] = $licenseInfo;
	}
	
	/**
	 * ��ʶҹТͧ License
	 *
	 * @return array of license
	 */
	public function getLicenseStatus() {
		return $this->ECMLicense;
	}
	
	public function getConcurrentStatus() {
		switch($this->ECMConcurrent['$mode']) {
			/**
			 * Unlimited
			 */
			case 0x0F0F :
				$concurrent['mode'] = 'Unlimited Concurrent';
				$concurrent['conxurrent'] = 'Unlimited';
				break;
			/**
			 * Unlimited
			 */
			case 0xF0F0 :
				$concurrent['mode'] = 'Limited Concurrent';
				$concurrent['conxurrent'] = $this->ECMConcurrent['$cons'];
				break;
		}
		return $concurrent;
	}
}
