<?php
/**
 * â»Ãá¡ÃÁáÊ´§»¯Ô·Ô¹ÊèÇ¹µÑÇ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Calendar
 *
 */
class PersonalCalendarController extends ECMController {
	/**
	 * ORM à¡çº Event
	 *
	 * @var array
	 */
	private $event;
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->event = Array ();
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'calendar' );
	}
	/**
	 * action /week áÊ´§»¯Ô·Ô¹ÃÒÂÊÑ»´ÒËì
	 *
	 */
	public function weekAction() {
		
		checkSessionPortlet();
		//include_once 'CalendarWeek.php';
		
		$calWeek = new CalendarWeek();
		
		echo $calWeek->render();
	}
	/**
	 * action /year áÊ´§»¯Ô·Ô¹ÃÒÂ»Õ
	 *
	 */
	public function yearAction() {
		checkSessionPortlet();
		//include_once 'CalendarYear.php';
		
		$calYear = new CalendarYear();
		
		echo $calYear->render();
	}
	/**
	 * action /day áÊ´§»¯Ô·Ô¹ÃÒÂÇÑ¹
	 *
	 */
	public function dayAction() {
		checkSessionPortlet();
		//include_once 'CalendarDay.php';
		
		$calDay = new CalendarDay();
		
		echo $calDay->render();
	}
	
	/**
	 * action /month áÊ´§»¯Ô·Ô¹ÃÒÂà´×Í¹
	 *
	 */
	public function monthAction() {
		global $config;
		global $cache;
		global $license;
		
		checkSessionPortlet();
		
		//include_once 'CalendarMonth.php';
		
		if (array_key_exists ( 'month', $_POST )) {
			$month = $_POST ['month'];
		} else {
			$month = date ( 'm' );
		}
		
		if (array_key_exists ( 'year', $_POST )) {
			$year = $_POST ['year'];
		} else {
			$year = date ( 'Y' );
		}
		
		$calMonth = new CalendarMonth ( $month, $year);
		echo $calMonth->render ();
	}
}

