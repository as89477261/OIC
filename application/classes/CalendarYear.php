<?php
/**
 * Class ����Ѻ��ԷԹ��»�
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Calendar Class
 */
class CalendarYear {
	/**
	 * �ѹ���
	 *
	 * @var int
	 */
	private $date;
	/**
	 * ��͹
	 *
	 * @var int
	 */
	private $month;
	/**
	 * ��
	 *
	 * @var int
	 */
	private $year;
	/**
	 * �ѹ�á�ͧ��͹
	 *
	 * @var int
	 */
	private $firstdayOnMonth;
	/**
	 * �ӹǹ�ѹ������͹
	 *
	 * @var int
	 */
	private $daysInMonth;
	/**
	 * �ѹ��軯ԷԹ
	 *
	 * @var int
	 */
	private $calendarDate;
	/**
	 * ����ͧ��ԷԹ
	 *
	 * @var int
	 */
	protected $CALENDARVIEW;
	
	/**
	 * ���������㹢ͧ Class
	 *
	 * @var array
	 */
	private $data;
	
	/**
	 * ��˹� View Script Path ��� ��˹� Initialize Data
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
	 * ���ѹ��軯ԷԹ
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
	 * �ʴ��Ż�ԷԹẺ����ѹ
	 *
	 * @return view output
	 */
	public function render() {
		$output = $this->ECMView->render ( 'year.phtml' );
		return $output;
	}

}
