<?php
/**
 * Class สำหรับปฏิทินรายเดือน
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Calendar Class
 */
class CalendarMonth {
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
	 * จำนวนวันในเดือน
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
	 * มุมมองขอปฏิทิน
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
	 * Class Constructor สำหรับกำหนด View Script Path และกำหนดข้อมูลภายใน Class
	 *
	 * @param int $month
	 * @param int $year
	 */
	public function __construct($month, $year) {
		global $config;
		
		$this->month = $month;
		$this->year = $year;
		$this->firstdayOnMonth = date ( 'w', mktime ( 0, 0, 0, $this->month, 1, $this->year ) );
		$this->daysInMonth = cal_days_in_month ( CAL_GREGORIAN, $this->month, $this->year );
		$this->calendarDate = 0;
		
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
		global $util;
		
		for($week = 0; $week <= 5; $week ++) {
			for($day = 0; $day <= 6; $day ++) {
				$this->data [$week] [$day] = Array ();
				$this->data [$week] [$day] ['date'] = $this->getDate ( $week, $day );
				if ($this->data [$week] [$day] ['date'] == 0) {
					$this->data [$week] [$day] ['class'] = 'disabledCalendar';
				} else {
					if ($day == 0 || $day == 6) {
						$this->data [$week] [$day] ['class'] = 'weekendCalendar';
					} else {
						$this->data [$week] [$day] ['class'] = 'normalCalendar';
					}
				}
				if ($this->data [$week] [$day] ['date'] == date ( 'j' ) and date ( 'm' ) == $this->month && date ( 'Y' ) == $this->year) {
					$this->data [$week] [$day] ['class'] = 'todayCalendar';
				}
			}
		}
		
		if ($this->month == 1) {
			$this->ECMView->assign ( 'prevMonth', 12 );
			$this->ECMView->assign ( 'prevYear', $this->year - 1 );
		} else {
			$this->ECMView->assign ( 'prevMonth', $this->month - 1 );
			$this->ECMView->assign ( 'prevYear', $this->year );
		}
		
		if ($this->month == 12) {
			$this->ECMView->assign ( 'nextMonth', 1 );
			$this->ECMView->assign ( 'nextYear', $this->year + 1 );
		} else {
			$this->ECMView->assign ( 'nextMonth', $this->month + 1 );
			$this->ECMView->assign ( 'nextYear', $this->year );
		}
		
		$this->ECMView->assign ( 'month', $this->month );
		$this->ECMView->assign ( 'calendarMonth', $util->getThaiMonth ( $this->month ) );
		$this->ECMView->assign ( 'year', $this->year );
		$this->ECMView->assign ( 'calendarYear', $this->year + 543 );
		$this->ECMView->assign ( 'data', $this->data );
		$output = $this->ECMView->render ( 'month.phtml' );
		return $output;
	}

}
