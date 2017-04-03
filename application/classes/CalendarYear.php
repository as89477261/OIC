<?php
/**
 * Class สำหรับปฏิทินรายปี
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Calendar Class
 */
class CalendarYear {
	/**
	 * วันที่
	 *
	 * @var int
	 */
	private $date;
	/**
	 * เดือน
	 *
	 * @var int
	 */
	private $month;
	/**
	 * ปี
	 *
	 * @var int
	 */
	private $year;
	/**
	 * วันแรกของเดือน
	 *
	 * @var int
	 */
	private $firstdayOnMonth;
	/**
	 * จำนวนวันภายในเดือน
	 *
	 * @var int
	 */
	private $daysInMonth;
	/**
	 * วันที่ปฏิทิน
	 *
	 * @var int
	 */
	private $calendarDate;
	/**
	 * มุมมองปฏิทิน
	 *
	 * @var int
	 */
	protected $CALENDARVIEW;
	
	/**
	 * ข้อมูลภายในของ Class
	 *
	 * @var array
	 */
	private $data;
	
	/**
	 * กำหนด View Script Path และ กำหนด Initialize Data
	 *
	 */
	public function __construct() {
		global $config;
		
		Zend_Loader::loadClass ( 'Zend_View' );
		
		// Inherit Custom Zend_View to protected member ECMView
		$this->ECMView = new Zend_View ( );
		
		// Setup view script path
		$this->ECMView->setScriptPath ( "{$config ['appPath']}application/views/calendar/" );

		// Initialize Data 
		$this->data = Array ();
	}
	
	/**
	 * ขอวันที่ปฏิทิน
	 *
	 * @param int $week
	 * @param int $day
	 * @return int
	 */
	private function getDate($week, $day) {
		if ($week == 0 && $this->calendarDate == 0 && $day == ($this->firstdayOnMonth)) {
			return ++ $this->calendarDate;
		} else {
			if ($this->calendarDate > 0 && $this->calendarDate < $this->daysInMonth) {
				return ++ $this->calendarDate;
			} else {
				return 0;
			}
		}
	}
	
	/**
	 * แสดงผลปฏิทินแบบรายวัน
	 *
	 * @return view output
	 */
	public function render() {
		$output = $this->ECMView->render ( 'year.phtml' );
		return $output;
	}

}
