<?php
/**
 * Class Data Filter
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category System
 */
class ECMFilter {
	/**
	 * Default Filter
	 *
	 * @var array
	 */
	private $defaultFilter;
	/**
	 * Filters
	 *
	 * @var array
	 */
	private $filter;
	
	/**
	 * Initialize Filter and Default Filter
	 *
	 */
	public function __construct() {
		$this->filter = Array();
		$this->defaultFilter = Array();
	}
	
	/**
	 * Generate Auto Filter
	 *
	 */
	public function generateAutoFilter() {
		
	}
	
	/**
	 * ตั้งค่า Filter สำหรับการกรองข้อมูล 
	 *
	 * @param array $filterDefArray
	 */
	public function setFilter($filterDefArray) {
		//$this->filter = Array();
		$this->filter = $filterDefArray;
	}
	
	/**
	 * ทำการกรองข้อมูล
	 *
	 * @param string $method
	 * @return array
	 */
	public function &filter($method='POST') {
		switch (strtoupper($method)) {
			case 'POST' :
				$retData = filter_input_array ( INPUT_POST, $this->filter );
				break;
				
			case 'GET' :
				$retData = filter_input_array ( INPUT_GET, $this->filter );
				break;
				
		}
		return $retData;
	}
}
