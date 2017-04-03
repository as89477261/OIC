<?php

require_once('CWebInterface.php');
require_once('MBooking.php');
require_once('MCar.php');
require_once('MRole.php');
require_once('MGroupPolicy.php');
require_once('MPassport.php');

//require_once('MCar.php');

class CwiBookingManagement extends CWebInterface
{

	var $function_list = array(
		'booking-ui' => array(FN_ID => 'booking-ui', FN_NAME => 'getUi', FN_DESC => 'My Booking List'),
		'booking-ui-all' => array(FN_ID => 'booking-ui-all', FN_NAME => 'getUiAll', FN_DESC => 'My Booking List'),
		'booking-list' => array(FN_ID => 'booking-list', FN_NAME => 'listBooking', FN_DESC => 'My Booking List'),
		'booking-my-list' => array(FN_ID => 'booking-list', FN_NAME => 'listMyBooking', FN_DESC => 'My Booking List'),
		'booking-list-all' => array(FN_ID => 'booking-list-all', FN_NAME => 'listAllBookings', FN_DESC => 'All Booking List'),
		'booking-add' => array(FN_ID => 'booking-add', FN_NAME => 'addBooking', FN_DESC => 'Add Booking'),
		'booking-edit' => array(FN_ID => 'booking-edit', FN_NAME => 'editBooking', FN_DESC => 'Edit Booking'),
		'booking-delete' => array(FN_ID => 'booking-delete', FN_NAME => 'deleteBooking', FN_DESC => 'Delete Booking'),
		'booking-approve' => array(FN_ID => 'booking-approve', FN_NAME => 'approveBooking', FN_DESC => 'Approve Booking'),
		'booking-close' => array(FN_ID => 'booking-close', FN_NAME => 'closeBooking', FN_DESC => 'Close Booking'),
		'job-summary-report' => array(FN_ID => 'job-summary-report', FN_NAME => 'jobSummaryReport', FN_DESC => 'Summary Report'),
		'booking-create-form' => array(FN_ID => 'booking-create-form', FN_NAME => 'createForm', FN_DESC => 'My Booking List'),
		'find-cheif-id' => array(FN_ID => 'find-cheif-id', FN_NAME => 'findCheifId', FN_DESC => 'My Booking List')
	);

	function hasTime(&$book_id, $date, $time, $books)
	{
		if (empty($books))
		{
			return false;
		}
		foreach ($books as $booking)
		{//echo $time . '->' . $date . ' -> ' . $booking['go_date'] . '(' . $booking['go_hour'] . ') ->' . $booking['back_date'] . '(' . $booking['back_hour'] . ')<br>';
			if ($date >= $booking['go_date'] && $date <= $booking['back_date'])
			{
				if ($date == $booking['go_date'] && $date == $booking['back_date'])
				{ //�ó�仡�Ѻ��ѹ���ǡѹ
					if ($time >= $booking['go_hour'] && $time <= $booking['back_hour'])
					{
						$book_id = $booking['booking_id'];
						return true;
					}
				}
				elseif ($date == $booking['go_date'] && $date != $booking['back_date'])
				{ //�ó�仡�Ѻ�����ѹ�ѹ ����ѹ���ٵç�Ѻ�ѹ�
					if ($time >= $booking['go_hour'])
					{
						$book_id = $booking['booking_id'];
						return true;
					}
				}
				elseif ($date != $booking['go_date'] && $date == $booking['back_date'])
				{ //�ó�仡�Ѻ�����ѹ�ѹ ����ѹ���ٵç�Ѻ�ѹ��Ѻ
					if ($time <= $booking['back_hour'])
					{
						$book_id = $booking['booking_id'];
						return true;
					}
				}
				elseif ($date == $booking['go_date'] && $date != $booking['back_date'])
				{ //�ó�仡�Ѻ�����ѹ�ѹ ����ѹ��������㹪�ǧ�ѹ�����ѹ��Ѻ
					$book_id = $booking['booking_id'];
					return true;
				}
			}
		}
		return false;
	}

	function margeFormBooking(&$arg_output, &$arg_template)
	{
		$m_booking = new MBooking($this);
		$date = $m_booking->convertFormate($arg_output['date']);
		$where = array('go_date <=' => $date, 'back_date >=' => $date);
		$books = $m_booking->findAll($where);
		$booking = makeGroupedArray($books, 'car_id');
		$book = makeGroupedArray($books, 'booking_id');
		$car = $arg_output['car_rows'];
		for ($i = 0; $i < count($car); $i++)
		{
			$time = array();
			$rangeTime = array();
			$status = 0;
			for ($j = 0; $j <= 24; $j++)
			{
				$book_id = 0;
				if ($this->hasTime($book_id, $date, $j, $booking[$car[$i]['car_id']]) && $j < 24)
				{
					if ($status == 0)
					{
						$rangeTime['start'] = $j;
						$status = 1;
//$time[$j] = $book[$book_id];
					}
				}
				else
				{
					if ($status == 1)
					{
						$rangeTime['stop'] = $j;
						$time[] = $rangeTime;
						$status = 0;
					}
//$time[$j] = array('booking_id' => 0);
				}
			}
			$arg_output['car_rows'][$i]['time_rows'] = $time;
		}
		$arg_template = 'PageControlBooking';
	}

	function getUi(&$arg_output, &$arg_template, $arg_input)
	{
		$m_booking = new MBooking($this);
		$positionId = $_SESSION['roleID'];
		$arg_output['policy'] = $m_booking->checkPermission($positionId);
		$arg_template = 'PageBooking';
		return RESULTTYPE_LAYOUT;
	}

	function getUiAll(&$arg_output, &$arg_template, $arg_input)
	{
		$m_booking = new MBooking($this);
		$positionId = $_SESSION['roleID'];
		$arg_output['policy'] = $m_booking->checkPermission($positionId);
		$arg_output[FN_FN] = $arg_input[FN_FN];
		$arg_template = 'PageListBooking';
		return RESULTTYPE_LAYOUT;
	}

	function listBooking(&$arg_output, &$arg_template, $arg_input)
	{
		$m_car = new MCar($this);
		$arg_output['car_rows'] = $m_car->findAll();
		$arg_output['result_count'] = count($arg_output['car_rows']);

		$m_booking = new MBooking($this);
		$positionId = $_SESSION['roleID'];
		$arg_output['policy'] = $m_booking->checkPermission($positionId);
		
		if (isset($arg_input['date']))
		{
			$arg_output['date'] = $arg_input['date'];
		}
		else
		{
			$arg_output['date'] = date('Y-m-d');
		}
		$this->margeFormBooking($arg_output, $arg_template);

		return RESULTTYPE_LAYOUT;
	}

	function listAllBookings(&$arg_output, &$arg_template, $arg_input)
	{
		$m_booking = new MBooking($this);
//$m_booking->has_one = array(MCar => array('car_id'), MDriver => array('driver_id'));

		if (isset($arg_input['from_date']) && isset($arg_input['to_date']))
		{
			$arg_output['from_date'] = $arg_input['from_date'];
			$arg_output['to_date'] = $arg_input['to_date'];
		}
		else
		{
			$arg_output['from_date'] = date('Y-m-d');
			$arg_output['to_date'] = date('Y-m-d');
			$arg_input['from_date'] = date('Y-m-d');
			$arg_input['to_date'] = date('Y-m-d');
		}

		switch ($arg_input[FN_FN])
		{
			case 'my_booking' :
				$arg_output['booking_rows'] = $m_booking->searchBooking($arg_input, array('account_id' => $_SESSION['accID']));
				//print_r($arg_output);die();
				break;
			case 'booking_list_all' :
				$arg_output['booking_rows'] = $m_booking->searchBooking($arg_input);
				break;
		}

		$arg_output[FN_FN] = $arg_input[FN_FN];
		$arg_output['result_count'] = count($arg_output['booking_rows']);
		$status = $arg_input['status'];
		$arg_output['booking_status_list'] = $m_booking->getBookingStatusList($status);
		selectList($arg_output['booking_status_list'], $status);

		$positionId = $_SESSION['roleID'];
		$arg_output['policy'] = $m_booking->checkPermission($positionId);
		
		$arg_template = 'PageListBookingData';

		return RESULTTYPE_LAYOUT;
	}

	function mergeFormAddEditCar(&$arg_output, &$arg_template)
	{
		$m_car = new MCar($this);
		$m_car->setListValueItem('car_id', 'car_license');
		$arg_output['car_list'] = $m_car->getList($arg_output['booking']['car_id']);
	}

	function mergeFormAddEditDriver(&$arg_output, &$arg_template)
	{
		$m_driver = new MDriver($this);
		$m_driver->setListValueItem('driver_id', 'first_name');
		$arg_output['driver_list'] = $m_driver->getList($arg_output['booking']['driver_id']);
	}

	function addBooking(&$arg_output, &$arg_template, $arg_input)
	{
		$m_booking = new MBooking($this);

		switch ($arg_input[FN_FN])
		{
			case '' :
				$arg_output['fid'] = 'booking-add';
				$arg_output['stage'] = 'add';
				$arg_output['cancle'] = 'booking-list';
				//$this->mergeFormAddEditCar($arg_output, $arg_template);
				//$this->mergeFormAddEditDriver($arg_output, $arg_template);

				$arg_output['booking'] = $m_booking->getDefaultData();

				$arg_template = 'PageAddEditBooking'; //print_r($arg_output);
				return RESULTTYPE_LAYOUT;
				break;
			case 'save' :
				$booking = $arg_input['booking'];

				$booking['go_date'] = $m_booking->convertFormate($booking['go_date']);
				$booking['back_date'] = $m_booking->convertFormate($booking['back_date']);

				$result = $m_booking->insertBooking($booking);
				//$arg_output['HttpHeader'][] = 'Location: http://handicap/ECMDev/reservation/./reservation.php?fid=booking-list';
				//return RESULTTYPE_HTTPHDR;
				break;
		}
	}

	function editBooking(&$arg_output, &$arg_template, $arg_input)
	{

		$m_booking = new MBooking($this);
		switch ($arg_input[FN_FN])
		{
			case '' :
				$m_booking->join_models = '';
				$arg_output['booking'] = $m_booking->getDefaultData($arg_input['booking_id']);

				$arg_output['fid'] = 'booking-edit';
				$status = $arg_output['booking']['status'];
				$arg_output['booking_status_list'] = $m_booking->getBookingStatusList($status);
				selectList($arg_output['booking_status_list'], $status);
//				$this->mergeFormAddEditCar($arg_output, $arg_template);
//				$this->mergeFormAddEditDriver($arg_output, $arg_template);

				$arg_output['appv'] = $arg_input['appv'];

				if ($arg_input['appv'] == 1)
				{
					$arg_output['booking']['approve_id'] = $_SESSION['accID'];
					$arg_template = 'PageAppv';
				}
				elseif ($arg_input['appv'] == 2)
				{
					$arg_template = 'PageClose';
				}
				else
				{
					$arg_template = 'PageAddEditBooking';
				}
				return RESULTTYPE_LAYOUT;
				break;
			case 'save' :
				$result = $m_booking->editBooking($arg_input);
				break;
		}
	}

	function deleteBooking(&$arg_output, &$arg_template, $arg_input)
	{
//$arg_input['booking_id'] = 19;
		$m_booking = new MBooking($this);
		$result = $m_booking->delete($arg_input['booking_id']);

	}

	function approveBooking(&$arg_output, &$arg_template, $arg_input)
	{
		$m_booking = new MBooking($this);
		switch ($arg_input[FN_FN])
		{
			case '' :
				break;
			case 'approve' :
//$arg_output['HttpHeader'][] = 'Location: http://handicap/ECMDev/reservation/./reservation.php?fid=driver-list';
				break;
		}
	}

	function closeBooking(&$arg_output, &$arg_template, $arg_input)
	{
		$m_booking = new MBooking($this);
		switch ($arg_input[FN_FN])
		{
			case '' :
				break;
			case 'close' :
//$arg_output['HttpHeader'][] = 'Location: http://handicap/ECMDev/reservation/./reservation.php?fid=driver-list';
				break;
		}
	}

	function createForm(&$arg_output, &$arg_template, $arg_input)
	{
		$m_booking = new MBooking($this);
		$arg_output = $m_booking->getDefaultData($arg_input['booking_id']);

		if (strlen($arg_output['go_hour']) == 1)
			$arg_output['go_hour'] = '0' . $arg_output['go_hour'];
		if (strlen($arg_output['go_minute']) == 1)
			$arg_output['go_minute'] = '0' . $arg_output['go_minute'];
		if (strlen($arg_output['back_hour']) == 1)
			$arg_output['back_hour'] = '0' . $arg_output['back_hour'];
		if (strlen($arg_output['back_minute']) == 1)
			$arg_output['back_minute'] = '0' . $arg_output['back_minute'];

		$arrayMonth = array(
			'01' => '���Ҥ�',
			'02' => '����Ҿѹ��',
			'03' => '�չҤ�',
			'04' => '����¹',
			'05' => '����Ҥ�',
			'06' => '�Զع�¹',
			'07' => '�á�Ҥ�',
			'08' => '�ԧ�Ҥ�',
			'09' => '�ѹ��¹',
			'10' => '���Ҥ�',
			'11' => '��Ȩԡ�¹',
			'12' => '�ѹ�Ҥ�',
		);

		$go_date = (string) $arg_output['go_date'];
		$back_date = (string) $arg_output['back_date'];

		$arg_output['go_day'] = date('d', strtotime($go_date));
		$arg_output['go_month'] = $arrayMonth[date('m', strtotime($go_date))];
		$arg_output['go_year'] = (int) (date('Y', strtotime($go_date))) + 543;

		$arg_output['back_day'] = date('d', strtotime($go_date));;
		$arg_output['back_month'] = $arrayMonth[date('m', strtotime($go_date))];
		$arg_output['back_year'] = (int) (date('Y', strtotime($go_date))) + 543;

		$arg_output['current_day'] = date('d');
		$arg_output['current_month'] = $arrayMonth[(string) (date('m'))];
		$arg_output['current_year'] = date('Y') + 543;

		if ($arg_output['type'] == 0)
		{
			$arg_template = 'InProvinceForm';
		}
		else
		{
			$arg_template = 'OutProvinceForm';
		}
		return RESULTTYPE_LAYOUT;
	}

	function findCheifId(&$arg_output, &$arg_template, $arg_input)
	{
		$dAccount = new MPassport($this);
		$account = $dAccount->findAllAccount($_SESSION['accID'], iconv('UTF-8', 'TIS-620', $arg_input['q']));
//echo '<pre>';print_r($account);die();
		$empText='';
		foreach($account as $empKey=>$empValue)
		{
			$empText.=$empValue['f_acc_id'].':'.$empValue['f_name'].' '.$empValue['f_last_name'] . ':' . $empValue['f_role_id'] . ':' . $empValue['f_role_name'] . ':' . $empValue['f_org_id'] . ':' . $empValue['f_org_name'] ."|\n";
		}
		echo $empText;

//		echo '<pre>';print_r($account);
	}

}

?>
