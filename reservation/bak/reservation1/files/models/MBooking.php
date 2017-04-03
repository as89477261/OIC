<?php

require_once('php_writeexcel-0.3.0/class.writeexcel_workbook.inc.php');
require_once('php_writeexcel-0.3.0/class.writeexcel_worksheet.inc.php');
require_once('CReservationModel.php');
//require_once('CshBooking.php');
require_once('MCar.php');
require_once('MDriver.php');
require_once('MAccount.php');
require_once('MRole.php');
require_once('MOrganize.php');
require_once('MGroupPolicy.php');

class MBooking extends CReservationModel
{

	var $month = array(
		'01' => '���Ҥ�',
		'02' => '����Ҿѹ��',
		'03' => '�չҤ�',
		'04' => '����¹',
		'05' => '����Ҥ�',
		'06' => '�Զع�¹',
		'07' => '�á�Ҥ�',
		'08' => '�ԧ�Ҥ�',
		'09' => '�����¹',
		'10' => '���Ҥ�',
		'11' => '��Ȩԡ�¹',
		'12' => '�ѹ�Ҥ�'
	);
	var $minute = array(
		array('value' => '00', 'title' => '00'),
		array('value' => '15', 'title' => '15'),
		array('value' => '30', 'title' => '30'),
		array('value' => '45', 'title' => '45')
	);
	var $hours = array(
		array('value' => '00', 'title' => '00'),
		array('value' => '01', 'title' => '01'),
		array('value' => '02', 'title' => '02'),
		array('value' => '03', 'title' => '03'),
		array('value' => '04', 'title' => '04'),
		array('value' => '05', 'title' => '05'),
		array('value' => '06', 'title' => '06'),
		array('value' => '07', 'title' => '07'),
		array('value' => '08', 'title' => '08'),
		array('value' => '09', 'title' => '09'),
		array('value' => '10', 'title' => '10'),
		array('value' => '11', 'title' => '11'),
		array('value' => '12', 'title' => '12'),
		array('value' => '13', 'title' => '13'),
		array('value' => '14', 'title' => '14'),
		array('value' => '15', 'title' => '15'),
		array('value' => '16', 'title' => '16'),
		array('value' => '17', 'title' => '17'),
		array('value' => '18', 'title' => '18'),
		array('value' => '19', 'title' => '19'),
		array('value' => '20', 'title' => '20'),
		array('value' => '21', 'title' => '21'),
		array('value' => '22', 'title' => '22'),
		array('value' => '23', 'title' => '23'),
		array('value' => '24', 'title' => '24')
	);
	var $province = array(
		array('value' => '��к��', 'title' => '��к��'),
		array('value' => '��ا෾', 'title' => '��ا෾'),
		array('value' => '�ҭ������', 'title' => '�ҭ������'),
		array('value' => '����Թ���', 'title' => '����Թ���'),
		array('value' => '��ᾧྪ�', 'title' => '��ᾧྪ�'),
		array('value' => '�͹��', 'title' => '�͹��'),
		array('value' => '�ѹ�����', 'title' => '�ѹ�����'),
		array('value' => '���ԧ���', 'title' => '���ԧ���'),
		array('value' => '�ź���', 'title' => '�ź���'),
		array('value' => '��¹ҷ', 'title' => '��¹ҷ'),
		array('value' => '�������', 'title' => '�������'),
		array('value' => '�����', 'title' => '�����'),
		array('value' => '��§���', 'title' => '��§���'),
		array('value' => '��§����', 'title' => '��§����'),
		array('value' => '��ѧ', 'title' => '��ѧ'),
		array('value' => '��Ҵ', 'title' => '��Ҵ'),
		array('value' => '�ҡ', 'title' => '�ҡ'),
		array('value' => '��ù�¡', 'title' => '��ù�¡'),
		array('value' => '��û��', 'title' => '��û��'),
		array('value' => '��þ��', 'title' => '��þ��'),
		array('value' => '����Ҫ����', 'title' => '����Ҫ����'),
		array('value' => '�����ո����Ҫ', 'title' => '�����ո����Ҫ'),
		array('value' => '������ä�', 'title' => '������ä�'),
		array('value' => '�������', 'title' => '�������'),
		array('value' => '��Ҹ����', 'title' => '��Ҹ����'),
		array('value' => '��ҹ', 'title' => '��ҹ'),
		array('value' => '���������', 'title' => '��ҹ'),
		array('value' => '�����ҹ�', 'title' => '�����ҹ�'),
		array('value' => '��ШǺ���բѹ��', 'title' => '��ШǺ���բѹ��'),
		array('value' => '��Ҩչ����', 'title' => '��Ҩչ����'),
		array('value' => '�ѵ�ҹ�', 'title' => '�ѵ�ҹ�'),
		array('value' => '��й�������ظ��', 'title' => '��й�������ظ��'),
		array('value' => '�����', 'title' => '�����'),
		array('value' => '�ѧ��', 'title' => '�ѧ��'),
		array('value' => '�ѷ�ا', 'title' => '�ѷ�ا'),
		array('value' => '�ԨԵ�', 'title' => '�ԨԵ�'),
		array('value' => '��ɳ��š', 'title' => '��ɳ��š'),
		array('value' => 'ྪú���', 'title' => 'ྪú���'),
		array('value' => 'ྪú�ó�', 'title' => 'ྪú�ó�'),
		array('value' => '���', 'title' => '���'),
		array('value' => '����', 'title' => '����'),
		array('value' => '�����ä��', 'title' => '�����ä��'),
		array('value' => '�ء�����', 'title' => '�ء�����'),
		array('value' => '����', 'title' => '����'),
		array('value' => '��ʸ�', 'title' => '��ʸ�'),
		array('value' => '�йͧ', 'title' => '�йͧ'),
		array('value' => '���ͧ', 'title' => '���ͧ'),
		array('value' => '�Ҫ����', 'title' => '�Ҫ����'),
		array('value' => '�������', 'title' => '�������'),
		array('value' => 'ž����', 'title' => 'ž����'),
		array('value' => '�ӻҧ', 'title' => '�ӻҧ'),
		array('value' => '�Ӿٹ', 'title' => '�Ӿٹ'),
		array('value' => '���', 'title' => '���'),
		array('value' => '�������', 'title' => '�������'),
		array('value' => 'ʡŹ��', 'title' => 'ʡŹ��'),
		array('value' => 'ʧ���', 'title' => 'ʧ���'),
		array('value' => 'ʵ��', 'title' => 'ʵ��'),
		array('value' => '��طû�ҡ��', 'title' => '��طû�ҡ��'),
		array('value' => '��ط�ʧ����', 'title' => '��ط�ʧ����'),
		array('value' => '��ط��Ҥ�', 'title' => '��ط��Ҥ�'),
		array('value' => '��к���', 'title' => '��к���'),
		array('value' => '������', 'title' => '������'),
		array('value' => '�ԧ�����', 'title' => '�ԧ�����'),
		array('value' => '�ؾ�ó����', 'title' => '�ؾ�ó����'),
		array('value' => '����ɮ��ҹ�', 'title' => '����ɮ��ҹ�'),
		array('value' => '���Թ���', 'title' => '���Թ���'),
		array('value' => '��⢷��', 'title' => '��⢷��'),
		array('value' => '˹ͧ���', 'title' => '˹ͧ���'),
		array('value' => '˹ͧ�������', 'title' => '˹ͧ�������'),
		array('value' => '�ӹҨ��ԭ', 'title' => '�ӹҨ��ԭ'),
		array('value' => '�شøҹ�', 'title' => '�شøҹ�'),
		array('value' => '�صôԵ��', 'title' => '�صôԵ��'),
		array('value' => '�ط�¸ҹ�', 'title' => '�ط�¸ҹ�'),
		array('value' => '�غ��Ҫ�ҹ�', 'title' => '�غ��Ҫ�ҹ�'),
		array('value' => '��ҧ�ͧ', 'title' => '��ҧ�ͧ'),
		array('value' => '�����ͧ�͹', 'title' => '�����ͧ�͹')
	);
	var $field = array(
		'account_id',
		'position_id',
		'deparment_id',
		'phone',
		'type',
		'goto',
		'address',
		'province',
		'zipcode',
		'objective',
		'person',
		'go_date',
		'go_hour',
		'go_minute',
		'back_date',
		'back_hour',
		'back_minute',
		'chief_id',
		'status',
		'mile_start',
		'mile_stop',
		'comment_detail',
		'car_id',
		'driver_id',
		'approve_id',
		'status_description'
	);
	var $primary_key = 'booking_id';
	var $table_name = 'tbl_booking';
	var $model_name = 'booking';
	var $order_by = array('booking_id' => ORDERBY_ASC);
	var $string_column_list = array('status', 'comment_detail', 'deparment_id', 'account_id', 'position_id', 'goto', 'address', 'province', 'objective', 'chief_id', 'go_date', 'back_date');

//	var $join_models = array(MCar => array('car_id'), MDriver => array('driver_id'));

	function searchBooking($criteria, $where = array())
	{
		if ($criteria['status'])
			$where['status'] = $criteria['status'];

		$where['go_date >='] = $this->convertFormate($criteria['from_date']);
		$where['back_date <='] = $this->convertFormate($criteria['to_date']);

//		$ds = $this->getDataStore();
//		$ds->debug_sql = true;

		$booking = $this->findAll($where);

		$m_car = new MCar($this->parent);
		$car = $m_car->findAll();
		$booking = $this->mergeBookingInfo($booking, $car, 'car_id');

		$m_driver = new MDriver($this->parent);
		$driver = $m_driver->findAll();
		$booking = $this->mergeBookingInfo($booking, $driver, 'driver_id');

		$m_account = new MAccount($this->parent);
		$account = $m_account->findAll();
		$booking = $this->mergeBookingInfo($booking, $account, 'f_acc_id', 'account_id');
		$booking = $this->mergeBookingInfo($booking, $account, 'f_acc_id', 'approve_id');

		$booking = $this->meargeStatus($booking);
		return $booking;
	}

	function mergeBookingInfo($booking, $info, $keyword, $diffKey = '')
	{
		$info = makeAssocArray($info, $keyword);
		$key = ($diffKey == '' ? $keyword : $diffKey);
		foreach ($booking as &$velue)
		{
			if (!is_array($info[$velue[$key]]))
			{
				continue;
			}
			$velue[$key] = $info[$velue[$key]];
		}
		return $booking;
	}

	function meargeStatus($booking)
	{
		$status = $this->getBookingStatusList();
		$status = makeAssocArray($status, 'Value');

		foreach ($booking as &$value)
		{
			$value['status_desc'] = $status[$value['status']]['Text'];
		}

		return $booking;
	}

	function getBookingStatusList()
	{
		if (isset($this->booking_status_list))
			$result = $this->booking_status_list;
		else
		{
			$result = $this->getListFromPreferences('BookingStatus');
			$this->booking_status_list = $result;
		}
		return $result;
	}

	function getDefaultData($bookingId = '')
	{

		$m_account = new MAccount($this->parent);
		$m_position = new MRole($this->parent);
		$m_deparment = new MOrganize($this->parent);

		$bookingList = array();
		$bookingList['booking_id'] = $bookingId;

		$phone = '';
		$provinceSelected = '';
		$goHourSelected = '';
		$goMinuteSelected = '';
		$backHourSelected = '';
		$backMinuteSelected = '';

		if ($bookingId != '')
		{
			$booking = $this->findAll(array('booking_id' => $bookingId));
			$booking = array_shift($booking);

			$accountId = $booking['account_id'];
			$positionId = $booking['position_id'];
			$deparmentId = $booking['deparment_id'];
			$phone = $booking['phone'];
			$provinceSelected = $booking;
			$goHourSelected = $booking['go_hour'];
			$goMinuteSelected = $booking['go_minute'];
			$backHourSelected = $booking['back_hour'];
			$backMinuteSelected = $booking['back_minute'];

			$bookingList['go_date'] = $this->convertFormate($booking['go_date'], 'out');
			$bookingList['back_date'] = $this->convertFormate($booking['back_date'], 'out');
			$bookingList['type'] = $booking['type'];
			$bookingList['goto'] = $booking['goto'];
			$bookingList['address'] = $booking['address'];
			$bookingList['zipcode'] = $booking['zipcode'];
			$bookingList['objective'] = $booking['objective'];
			$bookingList['person'] = $booking['person'];
			$bookingList['chief_id'] = $booking['chief_id'];
			$bookingList['status'] = $booking['status'];
			$bookingList['mile_start'] = $booking['mile_start'];
			$bookingList['mile_stop'] = $booking['mile_stop'];
			$bookingList['comment_detail'] = $booking['comment_detail'];
			$bookingList['status'] = $booking['status'];
			$bookingList['province'] = $booking['province'];
			$bookingList['go_hour'] = $booking['go_hour'];
			$bookingList['go_minute'] = $booking['go_minute'];
			$bookingList['back_hour'] = $booking['back_hour'];
			$bookingList['back_minute'] = $booking['back_minute'];
			$bookingList['status_description'] = $booking['status_description'];

			$chief = $m_account->findById($booking['chief_id']);
			$approve = $m_account->findById($booking['approve_id']);

			$bookingList['chief_id'] = $booking['chief_id'];
			$bookingList['chief_name'] = $chief['f_name'] . ' ' . $chief['f_last_name'];
			$bookingList['approve_name'] = $approve['f_name'] . ' ' . $approve['f_last_name'];

			$m_car = new MCar($this->parent);
			$car = $m_car->findAll(array('status' => 0));
			$bookingList['car'] = $m_car->findById($booking['car_id']);
			$bookingList['car_list'] = getList($car, 'car_id', 'car_license', $booking['car_id']);

			$m_driver = new MDriver($this->parent);
			$driver = $m_driver->findAll(array('status' => 0));
			$bookingList['driver'] = $m_driver->findById($booking['driver_id']);
			$bookingList['driver_list'] = getList($driver, 'driver_id', 'first_name', $booking['driver_id']);
		}
		else
		{
			$accountId = $_SESSION['accID'];
			$positionId = $_SESSION['roleID'];
			$deparmentId = '';

			$bookingList['go_date'] = date('d-m') . '-' . ((int) date('Y') + 543);
			$bookingList['back_date'] = date('d-m') . '-' . ((int) date('Y') + 543);
		}



		$account = $m_account->findAll(array('f_acc_id' => $accountId));
		$account = array_shift($account);

		$position = $m_position->findAll(array('f_role_id' => $positionId));
		$position = array_shift($position);

		if ($deparmentId != '')
		{
			$deparment = $m_deparment->findAll(array('f_org_id' => $deparmentId));
		}
		else
		{
			$deparment = $m_deparment->findAll(array('f_org_id' => $position['f_org_id']));
			$deparment = array_shift($deparment);
			$deparmentId = $position['f_org_id'];
		}

		$bookingList['account_id'] = $accountId;
		$bookingList['account_name'] = $account['f_name'] . ' ' . $account['f_last_name'];
		$bookingList['position_id'] = $positionId;
		$bookingList['position_name'] = $position['f_role_name'];
		$bookingList['deparment_id'] = $deparmentId;
		$bookingList['deparment_name'] = $deparment[0]['f_org_name'];

		if ($phone != '')
		{
			$bookingList['phone'] = $phone;
		}
		else
		{
			$bookingList['phone'] = $account['f_tel'];
		}

		$bookingList['province_list'] = getList($this->province, 'value', 'title', $provinceSelected);
		$bookingList['go_hours_list'] = getList($this->hours, 'value', 'title', $goHourSelected);
		$bookingList['go_minute_list'] = getList($this->minute, 'value', 'title', $goMinuteSelected);
		$bookingList['back_hours_list'] = getList($this->hours, 'value', 'title', $backHourSelected);
		$bookingList['back_minute_list'] = getList($this->minute, 'value', 'title', $backMinuteSelected);

		return $bookingList;
	}

	function insertBooking($booking)
	{

		$ds = $this->getDataStore();
		$ds->debug_sql = true;

		$booking = copyArrayValue($booking, $this->field);

		$booking['status'] = 'WAIT';
		$booking = $this->arrayConvertEncoding('UTF-8', 'TIS-620', $booking);
		$result = $this->insert($booking);

		return $result;
	}

	function editBooking($booking)
	{
		$book_id = $booking['booking_id'];
		$book = $booking['booking'];

		$book['go_date'] = $this->convertFormate($book['go_date']);
		$book['back_date'] = $this->convertFormate($book['back_date']);

		$book = copyArrayValue($book, $this->field);

//		$ds = $this->getDataStore();
//		$ds->debug_sql = true;
		$book = $this->arrayConvertEncoding('UTF-8', 'TIS-620', $book);
		return $this->updateById($book_id, $book);
	}

	function convertFormate($date, $formate = 'in')
	{
		if ($formate == 'in')
		{
			$date = explode('-', $date);
			$date = $date[2] . $date[1] . $date[0];
		}
		else
		{
			$date = substr($date, 6, 2) . '-' . substr($date, 4, 2) . '-' . substr($date, 0, 4);
		}
		return $date;
	}

	function convertShowFormate($arg_output)
	{
		foreach ($arg_output['booking_rows'] as &$value)
		{
			$value['go_date_in_formate'] = $this->convertFormate($value['go_date'], 'out');
			$value['back_date_in_formate'] = $this->convertFormate($value['back_date'], 'out');
		}
		return $arg_output;
	}

	function checkDelete(&$arg_output, $column)
	{
		$booking = $this->findAll();
		$bookingId = extractArrayValue($booking, $column);
		foreach ($arg_output as $key => $carValue)
		{
			$dalete = '1';
			if (in_array($carValue[$column], $bookingId))
			{
				$dalete = '0';
			}
			$arg_output[$key]['delete'] = $dalete;
		}
	}

	function checkPermission($positionId)
	{
		$dPosition = new MRole($this->parent);
		$position = $dPosition->findById($positionId);
		$GPId = $position['f_gp_id'];

		$dGroupPolicy = new MGroupPolicy($this->parent);
		$groupPolicy = $dGroupPolicy->findById($GPId);
		return $groupPolicy;
	}

	function findMaxBackDate($booking, $hour)
	{
		$maxMin = array();
		foreach ($booking as $key => $value)
		{
			if ($value['back_hour'] == $hour)
			{
				$maxMin[] = $value['back_minute'];
			}
		}
		if (count($maxMin) == 1)
		{
			return $maxMin[0];
		}
		else
		{
			return max($maxMin);
		}
	}

	function countbookingByDriver0($arg_input, $drivers, $driverBookings)
	{
		$srYear = $arg_input['start_year_1'];
		$spYear = $arg_input['stop_year_1'];
		$result = array();


		$reportPath = $this->getPreferences('ReportTemp');

		$fname = $reportPath . "/report" . uniqid(time()) . ".xls";
		echo realpath($fname);
		$workbook = & new writeexcel_workbook($fname);
		$worksheet = & $workbook->addworksheet('��§ҹ��û���ѵԢѺö');
		$worksheet->set_landscape();


		$heading0 = & $workbook->addformat(array(
					color => 'black',
					size => 18,
					align => 'center'
				));
		$heading = & $workbook->addformat(array(
					color => 'black',
					size => 14,
					align => 'center'
				));
		$heading2 = & $workbook->addformat(array(
					color => 'black',
					size => 14,
					align => 'left'
				));
		$subheading = & $workbook->addformat(array(
					color => 'black',
					size => 12
				));
		$subheadingright = & $workbook->addformat(array(
					color => 'black',
					size => 12,
					align => 'right'
				));


		$headings1 = array("�ӹѡ�ҹ��С�����áӡѺ������������û�Сͺ��áԨ��Сѹ���", '');
		$headings2 = array("��§ҹ��ػ����ҳ��âѺö", '');
		$headings3 = array("�ҡ��͹ " . $this->month[$arg_input['start_mounth_1']] . " �� " . $srYear . " ��͹ " . $this->month[$arg_input['sй�_mounth_1']] . " �� " . $spYear, '');

		$center = (int) (count($drivers) / 2) + 67;
		$worksheet->write_row(chr($center) . '1', $headings1, $heading0);
		$worksheet->write_row(chr($center) . '2', $headings2, $heading);
		$worksheet->write_row(chr($center) . '3', $headings3, $heading);


		$heading = & $workbook->addformat(array(
					bold => 1,
					color => 'black',
					size => 16
				));
		$format2 = & $workbook->addformat();
		$format2->set_text_wrap();
		$format2->set_bottom(1);
		$format2->set_left(1);
		$format2->set_right(1);
		$format2->set_top(1);
		$format2->set_size(10);
		$format2->set_align("center");

		$format3 = & $workbook->addformat();
		$format3->set_text_wrap();
		$format3->set_bottom(1);
		$format3->set_left(1);
		$format3->set_right(1);
		$format3->set_top(1);
		$format3->set_size(10);
		$format3->set_align("left");
		$worksheet->set_column(1, 1, 20);
		$colWidth = 2;
		foreach ($drivers as $value)
		{
			$worksheet->set_column($colWidth, $colWidth, 15);
			$colWidth++;
		}

		$count_col_xls = 67;
		$count_row_xls = 6;
		foreach ($drivers as $value)
		{
			$worksheet->write(chr($count_col_xls) . $count_row_xls, $value['first_name'] . ' ' . $value['last_name'], $format2);
			$count_col_xls++;
		}
		$count_row_xls++;


		for ($cYear = $srYear; $cYear <= $spYear; $cYear++)
		{
			if ($cYear == $srYear)
			{
				$srMount = $arg_input['start_mounth_1'];
			}
			else
			{
				$srMount = 1;
			}

			if ($cYear == $spYear)
			{
				$spMount = $arg_input['stop_mounth_1'];
			}
			else
			{
				$spMount = 12;
			}

			$count_col_xls = 65;
			$worksheet->write(chr($count_col_xls) . $count_row_xls, '�� ' . $cYear, $format3);
			$blank = 0;
			for ($cMounth = $srMount; $cMounth <= $spMount; $cMounth++)
			{
				$mounth = $cMounth;
				
				if ($cMounth < 10 && strlen($cMounth) < 2)
					$mounth = '0' . $mounth;
				$count_col_xls = 66;
				$worksheet->write(chr($count_col_xls) . $count_row_xls, $this->month[$mounth], $format3);
				if($blank != 0)
					$worksheet->write(chr(65) . $count_row_xls, '', $format3);
				$blank = 1;
				$count_col_xls++;
				$srDate = $cYear . $mounth . '00';
				$spDate = $cYear . $mounth . '99';
				//print_r($drivers);die();
				foreach ($drivers as $driver)
				{
					$count = 0;
					if (!is_array($driverBookings))
					{
						$result[$cYear][$mounth] = $count;
						continue;
					}
					foreach ($driverBookings as $driverBooking)
					{
//echo '\n' . $driverBooking['driver_id'] . '=>' . $driverBooking['go_date'] . '=>' . $srDate . '=>' . $driverBooking['back_date'] . '=>' . $spDate;
//echo $driver['driver_id'];
						if ($driverBooking['driver_id'] == $driver['driver_id'] &&
								$driverBooking['go_date'] >= $srDate &&
								$driverBooking['back_date'] <= $spDate)
						{
							$count++;
						}
					}
					$result[$cYear]['mount_list'][$mounth]['count_data'][$driver['driver_id']]['data'] = $count;
					$result[$cYear]['mount_list'][$mounth]['count_data'][$driver['driver_id']]['drivetr_id'] = $driver['driver_id'];
					$worksheet->write(chr($count_col_xls) . $count_row_xls, $count, $format3);
					$count_col_xls++;
				}
				
				$count_row_xls++;
				$result[$cYear]['mount_list'][$mounth]['mount'] = $this->month[$mounth];
			}
			$result[$cYear]['year'] = $cYear;
		}

		$workbook->close();
		$result['real_path'] = realpath($fname);

		return $result;
	}

	function countbookingByDriver1($arg_input, $drivers, $driverBookings)
	{
		$reportPath = $this->getPreferences('ReportTemp');
		$srYear = $arg_input['start_year_1'];
		$spYear = $arg_input['stop_year_1'];
		$result = array();

		$fname = $reportPath . "/report" . uniqid(time()) . ".xls";
		echo realpath($fname);
		$workbook = & new writeexcel_workbook($fname);
		$worksheet = & $workbook->addworksheet('��§ҹ��û���ѵԢѺö');
		$worksheet->set_landscape();


		$heading0 = & $workbook->addformat(array(
					color => 'black',
					size => 18,
					align => 'center'
				));
		$heading = & $workbook->addformat(array(
					color => 'black',
					size => 14,
					align => 'center'
				));
		$heading2 = & $workbook->addformat(array(
					color => 'black',
					size => 14,
					align => 'left'
				));
		$subheading = & $workbook->addformat(array(
					color => 'black',
					size => 12
				));
		$subheadingright = & $workbook->addformat(array(
					color => 'black',
					size => 12,
					align => 'right'
				));


		$headings1 = array("�ӹѡ�ҹ��С�����áӡѺ������������û�Сͺ��áԨ��Сѹ���", '');
		$headings2 = array("��§ҹ��ػ����ҳ��âѺö", '');
		$headings3 = array("�ҡ�� $srYear �֧�� $spYear", '');

		$center = (int) (count($drivers) / 2) + 67;
		$worksheet->write_row(chr($center) . '1', $headings1, $heading0);
		$worksheet->write_row(chr($center) . '2', $headings2, $heading);
		$worksheet->write_row(chr($center) . '3', $headings3, $heading);


		$heading = & $workbook->addformat(array(
					bold => 1,
					color => 'black',
					size => 16
				));
		$format2 = & $workbook->addformat();
		$format2->set_text_wrap();
		$format2->set_bottom(1);
		$format2->set_left(1);
		$format2->set_right(1);
		$format2->set_top(1);
		$format2->set_size(10);
		$format2->set_align("center");

		$format3 = & $workbook->addformat();
		$format3->set_text_wrap();
		$format3->set_bottom(1);
		$format3->set_left(1);
		$format3->set_right(1);
		$format3->set_top(1);
		$format3->set_size(10);
		$format3->set_align("left");

		$colWidth = 2;
		foreach ($drivers as $value)
		{
			$worksheet->set_column($colWidth, $colWidth, 15);
			$colWidth++;
		}

		$count_col_xls = 67;
		$count_row_xls = 6;
		foreach ($drivers as $value)
		{
			$worksheet->write(chr($count_col_xls) . $count_row_xls, $value['first_name'] . ' ' . $value['last_name'], $format2);
			$count_col_xls++;
		}



		for ($cYear = $srYear; $cYear <= $spYear; $cYear++)
		{
			$count_row_xls++;

			if ($cYear == $srYear)
			{
				$srMount = $arg_input['start_mounth_1'];
			}
			else
			{
				$srMount = 1;
			}

			if ($cYear == $spYear)
			{
				$spMount = $arg_input['stop_mounth_1'];
			}
			else
			{
				$spMount = 12;
			}
			$count_col_xls = 66;
			$worksheet->write(chr($count_col_xls) . $count_row_xls, '�� ' . $cYear, $format3);
			$count_col_xls++;


			$srDate = $cYear . '0000';
			$spDate = $cYear . '9999';
			//print_r($drivers);die();
			foreach ($drivers as $driver)
			{
				$count = 0;
				if (!is_array($driverBookings))
				{
					$result[$cYear][$mounth] = $count;
					continue;
				}
				foreach ($driverBookings as $driverBooking)
				{
//echo '\n' . $driverBooking['driver_id'] . '=>' . $driverBooking['go_date'] . '=>' . $srDate . '=>' . $driverBooking['back_date'] . '=>' . $spDate;
//echo $driver['driver_id'];
					if ($driverBooking['driver_id'] == $driver['driver_id'] &&
							$driverBooking['go_date'] >= $srDate &&
							$driverBooking['back_date'] <= $spDate)
					{
						$count++;
					}
				}
				$result[$cYear]['count_data'][$driver['driver_id']]['data'] = $count;
				$result[$cYear]['count_data'][$driver['driver_id']]['drivetr_id'] = $driver['driver_id'];
				$worksheet->write(chr($count_col_xls) . $count_row_xls, $count, $format3);
				$count_col_xls++;
			}

			$result[$cYear]['year'] = $cYear;
		}//echo '<pre>';print_r($result);
		$workbook->close();
//		header("Content-Type: application/x-msexcel");
//		header('Content-disposition: attachment; filename=' . $fname);
//
//		$content = file_get_contents($fname);
//		@unlink($fname);
//		echo $fname;
//		$result['content'] = $content;
		$result['real_path'] = realpath($fname);
//		print $content;

		return $result;
	}

}

?>