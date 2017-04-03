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
		'01' => 'มกราคม',
		'02' => 'กุมภาพันธ์',
		'03' => 'มีนาคม',
		'04' => 'เมษายน',
		'05' => 'พฤษภาคม',
		'06' => 'มิถุนายน',
		'07' => 'กรกฎาคม',
		'08' => 'สิงหาคม',
		'09' => 'กัยยายน',
		'10' => 'ตุลาคม',
		'11' => 'พฤศจิกายน',
		'12' => 'ธันวาคม'
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
		array('value' => 'กระบี่', 'title' => 'กระบี่'),
		array('value' => 'กรุงเทพ', 'title' => 'กรุงเทพ'),
		array('value' => 'กาญจนบุรี', 'title' => 'กาญจนบุรี'),
		array('value' => 'กาฬสินธุ์', 'title' => 'กาฬสินธุ์'),
		array('value' => 'กำแพงเพชร', 'title' => 'กำแพงเพชร'),
		array('value' => 'ขอนแก่น', 'title' => 'ขอนแก่น'),
		array('value' => 'จันทบุรี', 'title' => 'จันทบุรี'),
		array('value' => 'ฉะเชิงเทรา', 'title' => 'ฉะเชิงเทรา'),
		array('value' => 'ชลบุรี', 'title' => 'ชลบุรี'),
		array('value' => 'ชัยนาท', 'title' => 'ชัยนาท'),
		array('value' => 'ชัยภูมิ', 'title' => 'ชัยภูมิ'),
		array('value' => 'ชุมพร', 'title' => 'ชุมพร'),
		array('value' => 'เชียงราย', 'title' => 'เชียงราย'),
		array('value' => 'เชียงใหม่', 'title' => 'เชียงใหม่'),
		array('value' => 'ตรัง', 'title' => 'ตรัง'),
		array('value' => 'ตราด', 'title' => 'ตราด'),
		array('value' => 'ตาก', 'title' => 'ตาก'),
		array('value' => 'นครนายก', 'title' => 'นครนายก'),
		array('value' => 'นครปฐม', 'title' => 'นครปฐม'),
		array('value' => 'นครพนม', 'title' => 'นครพนม'),
		array('value' => 'นครราชสีมา', 'title' => 'นครราชสีมา'),
		array('value' => 'นครศรีธรรมราช', 'title' => 'นครศรีธรรมราช'),
		array('value' => 'นครสวรรค์', 'title' => 'นครสวรรค์'),
//		array('value' => 'นนทบุรี', 'title' => 'นนทบุรี'),
		array('value' => 'นราธิวาส', 'title' => 'นราธิวาส'),
		array('value' => 'น่าน', 'title' => 'น่าน'),
		array('value' => 'บุรีรัมย์', 'title' => 'บุรีรัมย์'),
		array('value' => 'ปทุมธานี', 'title' => 'ปทุมธานี'),
		array('value' => 'ประจวบคีรีขันธ์', 'title' => 'ประจวบคีรีขันธ์'),
		array('value' => 'ปราจีนบุรี', 'title' => 'ปราจีนบุรี'),
		array('value' => 'ปัตตานี', 'title' => 'ปัตตานี'),
		array('value' => 'พระนครศรีอยุธยา', 'title' => 'พระนครศรีอยุธยา'),
		array('value' => 'พะเยา', 'title' => 'พะเยา'),
		array('value' => 'พังงา', 'title' => 'พังงา'),
		array('value' => 'พัทลุง', 'title' => 'พัทลุง'),
		array('value' => 'พิจิตร', 'title' => 'พิจิตร'),
		array('value' => 'พิษณุโลก', 'title' => 'พิษณุโลก'),
		array('value' => 'เพชรบุรี', 'title' => 'เพชรบุรี'),
		array('value' => 'เพชรบูรณ์', 'title' => 'เพชรบูรณ์'),
		array('value' => 'แพร่', 'title' => 'แพร่'),
		array('value' => 'ภูเก็ต', 'title' => 'ภูเก็ต'),
		array('value' => 'มหาสารคาม', 'title' => 'มหาสารคาม'),
		array('value' => 'มุกดาหาร', 'title' => 'มุกดาหาร'),
		array('value' => 'ยะลา', 'title' => 'ยะลา'),
		array('value' => 'ยโสธร', 'title' => 'ยโสธร'),
		array('value' => 'ระนอง', 'title' => 'ระนอง'),
		array('value' => 'ระยอง', 'title' => 'ระยอง'),
		array('value' => 'ราชบุรี', 'title' => 'ราชบุรี'),
		array('value' => 'ร้อยเอ็ด', 'title' => 'ร้อยเอ็ด'),
		array('value' => 'ลพบุรี', 'title' => 'ลพบุรี'),
		array('value' => 'ลำปาง', 'title' => 'ลำปาง'),
		array('value' => 'ลำพูน', 'title' => 'ลำพูน'),
		array('value' => 'เลย', 'title' => 'เลย'),
		array('value' => 'ศรีสะเกษ', 'title' => 'ศรีสะเกษ'),
		array('value' => 'สกลนคร', 'title' => 'สกลนคร'),
		array('value' => 'สงขลา', 'title' => 'สงขลา'),
		array('value' => 'สตูล', 'title' => 'สตูล'),
		array('value' => 'สมุทรปราการ', 'title' => 'สมุทรปราการ'),
		array('value' => 'สมุทรสงคราม', 'title' => 'สมุทรสงคราม'),
		array('value' => 'สมุทรสาคร', 'title' => 'สมุทรสาคร'),
		array('value' => 'สระบุรี', 'title' => 'สระบุรี'),
		array('value' => 'สระแก้ว', 'title' => 'สระแก้ว'),
		array('value' => 'สิงห์บุรี', 'title' => 'สิงห์บุรี'),
		array('value' => 'สุพรรณบุรี', 'title' => 'สุพรรณบุรี'),
		array('value' => 'สุราษฎร์ธานี', 'title' => 'สุราษฎร์ธานี'),
		array('value' => 'สุรินทร์', 'title' => 'สุรินทร์'),
		array('value' => 'สุโขทัย', 'title' => 'สุโขทัย'),
		array('value' => 'หนองคาย', 'title' => 'หนองคาย'),
		array('value' => 'หนองบัวลำภู', 'title' => 'หนองบัวลำภู'),
		array('value' => 'อำนาจเจริญ', 'title' => 'อำนาจเจริญ'),
		array('value' => 'อุดรธานี', 'title' => 'อุดรธานี'),
		array('value' => 'อุตรดิตถ์', 'title' => 'อุตรดิตถ์'),
		array('value' => 'อุทัยธานี', 'title' => 'อุทัยธานี'),
		array('value' => 'อุบลราชธานี', 'title' => 'อุบลราชธานี'),
		array('value' => 'อ่างทอง', 'title' => 'อ่างทอง'),
		array('value' => 'แม่ฮ่องสอน', 'title' => 'แม่ฮ่องสอน')
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
		'status_description',
		'create_date',
		'road',
		'zone'
	);
	var $charector = array(
		65 => 'A',
		66 => 'B',
		67 => 'C',
		68 => 'D',
		69 => 'E',
		70 => 'F',
		71 => 'G',
		72 => 'H',
		73 => 'I',
		74 => 'J',
		75 => 'K',
		76 => 'L',
		77 => 'M',
		78 => 'N',
		79 => 'O',
		80 => 'P',
		81 => 'Q',
		82 => 'R',
		83 => 'S',
		84 => 'T',
		85 => 'U',
		86 => 'V',
		87 => 'W',
		88 => 'X',
		89 => 'Y',
		90 => 'Z',
		91 => 'AA',
		92 => 'AB',
		93 => 'AC',
		94 => 'AD',
		95 => 'AE',
		96 => 'AF',
		97 => 'AG',
		98 => 'AH',
		99 => 'AI',
		100 => 'AJ',
		101 => 'AK',
		102 => 'AL',
		103 => 'AM',
		104 => 'AN',
		105 => 'AO',
		106 => 'AP',
		107 => 'AQ',
		108 => 'AR',
		109 => 'AS',
		110 => 'AT',
		111 => 'AU',
		112 => 'AV',
		113 => 'AW',
		114 => 'AX',
		115 => 'AY',
		116 => 'AZ',
		117 => 'BA',
		118 => 'BB',
		119 => 'BC',
		120 => 'BD',
		121 => 'BE',
		122 => 'BF',
		123 => 'BG',
		124 => 'BH',
		125 => 'BI',
		126 => 'BJ',
		127 => 'BK',
		128 => 'BL',
		129 => 'BM',
		130 => 'BN',
		131 => 'BO',
		132 => 'BP',
		133 => 'BQ',
		134 => 'BR',
		135 => 'BS',
		136 => 'BT',
		137 => 'BU',
		138 => 'BV',
		139 => 'BW',
		140 => 'BX',
		141 => 'BY',
		142 => 'BZ',
		143 => 'CA',
		144 => 'CB',
		145 => 'CC',
		146 => 'CD',
		147 => 'CE',
		148 => 'CF',
		149 => 'CG',
		150 => 'CH',
	);
	var $primary_key = 'booking_id';
	var $table_name = 'tbl_booking';
	var $model_name = 'booking';
	var $order_by = array('booking_id' => ORDERBY_ASC);
	var $string_column_list = array('status', 'comment_detail', 'deparment_id', 'account_id', 'position_id', 'goto', 'address', 'province', 'objective', 'chief_id', 'go_date', 'back_date', 'create_date', 'road', 'zone', 'phone');

//	var $join_models = array(MCar => array('car_id'), MDriver => array('driver_id'));

	function searchBooking($criteria, $where = array())
	{
		if ($criteria['status'])
			$where['status'] = $criteria['status'];

		$where['go_date <='] = $this->convertFormate($criteria['to_date']);
		$where['back_date >='] = $this->convertFormate($criteria['from_date']);

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
			$bookingList['road'] = $booking['road'];
			$bookingList['zone'] = $booking['zone'];
			$bookingList['create_date'] = $booking['create_date'];

			$chief = $m_account->findById($booking['chief_id']);
			$approve = $m_account->findById($booking['approve_id']);

			$bookingList['chief_id'] = $booking['chief_id'];
			$bookingList['chief_name'] = $chief['f_name'] . ' ' . $chief['f_last_name'];
			$bookingList['approve_name'] = $approve['f_name'] . ' ' . $approve['f_last_name'];

			$bookingList['allow_appv'] = CONST_YES;
			$m_car = new MCar($this->parent);
			$car = $m_car->findAll(array('status' => 0));
			$bookingList['car'] = $m_car->findById($booking['car_id']);
			$bookingList['car_list'] = getList($car, 'car_id', 'car_license', $booking['car_id']);
			if (count($bookingList['car_list']) < 1)
			{
				$bookingList['allow_appv'] = CONST_NO;
			}

			$m_driver = new MDriver($this->parent);
			$driver = $m_driver->findAll(array('status' => 0));
			$bookingList['driver'] = $m_driver->findById($booking['driver_id']);
			$bookingList['driver_list'] = getList($driver, 'driver_id', 'first_name', $booking['driver_id']);
			if (count($bookingList['driver_list']) < 1)
			{
				$bookingList['allow_appv'] = CONST_NO;
			}
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
			$deparment = array_shift($deparment);
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
		$bookingList['deparment_name'] = $deparment['f_org_name'];

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
		if (!array_key_exists('province', $booking))
			$booking['province'] = 'กรุงเทพ/นนทบุรี';

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

		if ($book['status'] == 'UAVL')
		{
			$book['car_id'] = '';
			$book['driver_id'] = '';
		}

		$book = $this->arrayConvertEncoding('UTF-8', 'TIS-620', $book);
		//if (!array_key_exists('province', $book) || $book['type'] == '0')
		if ($book['type'] == '0')
			$book['province'] = 'กรุงเทพ/นนทบุรี';
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
			$go_minute = str_pad($value['go_minute'], 2, '0', STR_PAD_LEFT);
			$back_minute = str_pad($value['back_minute'], 2, '0', STR_PAD_LEFT);
			$value['go_date_in_formate'] = $this->convertFormate($value['go_date'], 'out') . ' ' . $value['go_hour'] . ':' . $go_minute;
			$value['back_date_in_formate'] = $this->convertFormate($value['back_date'], 'out') . ' ' . $value['back_hour'] . ':' . $back_minute;
			$value['res_date'] = $value['create_date'];
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
		elseif(count($maxMin) > 1)
		{
			return max($maxMin);
		}
		else
		{
			return 0;
		}
	}

	function countbookingByDriver0($arg_input, $drivers, $driverBookings)
	{
		$srYear = $arg_input['start_year_1'];
		$spYear = $arg_input['stop_year_1'];
		$result = array();


		$reportPath = $this->getPreferences('ReportTemp');

		$fname = $reportPath . "/report" . uniqid(time()) . ".xls";

		$workbook = & new writeexcel_workbook($fname);
		$worksheet = & $workbook->addworksheet('รายงานการประวัติขับรถ');
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


		$headings1 = array("สำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย", '');
		$headings2 = array("รายงานการปฏิบัติงานของพนักงานปฏิบัติงาน", '');
		$headings3 = array("ประจำเดือน " . $this->month[$arg_input['start_mounth_1']] . " ปี " . $srYear . " ถึงเดือน " . $this->month[$arg_input['stop_mounth_1']] . " ปี " . $spYear, '');

		$center = (int) (count($drivers) / 2) + 67;
		$worksheet->write_row($this->charector[$center] . '1', $headings1, $heading0);
		$worksheet->write_row($this->charector[$center] . '2', $headings2, $heading);
		$worksheet->write_row($this->charector[$center] . '3', $headings3, $heading);


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
		$format3->set_align("center");

		$format4 = & $workbook->addformat();
		$format4->set_text_wrap();
		$format4->set_bottom(1);
		$format4->set_left(1);
		$format4->set_right(1);
		$format4->set_top(1);
		$format4->set_size(10);
		$format4->set_align("left");

		$format5 = & $workbook->addformat(array('bold' => 1));
		$format5->set_text_wrap();
		$format5->set_bottom(1);
		$format5->set_left(1);
		$format5->set_right(1);
		$format5->set_top(1);
		$format5->set_size(10);
		$format5->set_bold(1);
		$format5->set_align("center");


		$worksheet->set_column(1, 1, 20);
		$colWidth = 2;
		foreach ($drivers as $value)
		{
			$worksheet->set_column($colWidth, $colWidth, 12);
			$worksheet->set_column($colWidth + 1, $colWidth + 1, 12);
			$colWidth += 2;
		}

		$count_col_xls = 67;
		$count_row_xls = 6;

		$worksheet->write($this->charector[65] . $count_row_xls, 'ชื่อ', $format2);
		$worksheet->write($this->charector[66] . $count_row_xls, '', $format2);

		$worksheet->merge_cells($this->charector[65] . $count_row_xls . ':' . $this->charector[66] . $count_row_xls);

		$worksheet->write($this->charector[65] . ($count_row_xls + 1), 'ปี', $format2);
		$worksheet->write($this->charector[66] . ($count_row_xls + 1), 'เดือน', $format2);
		$count_col_xls_merge = $count_col_xls;
		foreach ($drivers as $value)
		{
			$worksheet->write($this->charector[$count_col_xls_merge] . $count_row_xls, $value['first_name'] . ' ' . $value['last_name'], $format2);
			$worksheet->write($this->charector[$count_col_xls_merge + 1]	 . $count_row_xls, '', $format2);

			$worksheet->write($this->charector[$count_col_xls_merge] . ($count_row_xls + 1), 'ส่วนกลาง', $format2);
			$worksheet->write($this->charector[$count_col_xls_merge + 1] . ($count_row_xls + 1), 'ต่างจังหวัด', $format2);

			$worksheet->merge_cells($this->charector[$count_col_xls_merge] . $count_row_xls . ':' . $this->charector[$count_col_xls_merge + 1] . $count_row_xls);
			$count_col_xls_merge += 2;
		}
		$count_row_xls += 2;


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
			$worksheet->write($this->charector[$count_col_xls] . $count_row_xls, 'ปี ' . $cYear, $format4);
			$blank = 0;
			for ($cMounth = $srMount; $cMounth <= $spMount; $cMounth++)
			{
				$mounth = $cMounth;

				if ($cMounth < 10 && strlen($cMounth) < 2)
					$mounth = '0' . $mounth;
				$count_col_xls = 66;
				$worksheet->write($this->charector[$count_col_xls] . $count_row_xls, $this->month[$mounth], $format4);
				if ($blank != 0)
					$worksheet->write($this->charector[65] . $count_row_xls, '', $format4);
				$blank = 1;
				$count_col_xls++;
				$srDate = $cYear . $mounth . '00';
				$spDate = $cYear . $mounth . '99';
				//print_r($drivers);die();
				foreach ($drivers as $driver)
				{
					$countIn = 0;
					$countOut = 0;
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
							if ($driverBooking['type'] == 0)
								$countIn++;
							else
								$countOut++;
						}
					}
					$result[$cYear]['mount_list'][$mounth]['count_data'][$driver['driver_id']]['data'] = $count;
					$result[$cYear]['mount_list'][$mounth]['count_data'][$driver['driver_id']]['drivetr_id'] = $driver['driver_id'];
					$worksheet->write($this->charector[$count_col_xls] . $count_row_xls, $countIn, $format3);
					$worksheet->write($this->charector[$count_col_xls + 1] . $count_row_xls, $countOut, $format3);
					$count_col_xls += 2;
				}

				$count_row_xls++;
				$result[$cYear]['mount_list'][$mounth]['mount'] = $this->month[$mounth];
			}
			$result[$cYear]['year'] = $cYear;
		}

		$worksheet->write($this->charector[65] . $count_row_xls, 'รวม', $format3);
		$worksheet->write($this->charector[66] . $count_row_xls, '', $format3);
		$worksheet->merge_cells($this->charector[65] . $count_row_xls . ':' . $this->charector[66] . $count_row_xls);

		$count_col_xls = 67;
		foreach ($drivers as $value)
		{
			$bookingIn = $this->findAll(array('driver_id' => $value['driver_id'], 'type' => 0, 'go_date >=' => $srYear . $arg_input['start_mounth_1'] . '00', 'back_date <=' => $spYear . $spMount = $arg_input['stop_mounth_1'] . '99'));
			$bookingOut = $this->findAll(array('driver_id' => $value['driver_id'], 'type' => 1, 'go_date >=' => $srYear . $arg_input['start_mounth_1'] . '00', 'back_date <=' => $spYear . $spMount = $arg_input['stop_mounth_1'] . '99'));

			$countIn = count($bookingIn);
			$countOut = count($bookingOut);

			$worksheet->write($this->charector[$count_col_xls] . $count_row_xls, $countIn, $format5);
			$worksheet->write($this->charector[$count_col_xls + 1] . $count_row_xls, $countOut, $format5);

			$count_col_xls += 2;
		}

		$workbook->close();
		$result['real_path'] = $fname;

		return $result;
	}

	function countbookingByDriver1($arg_input, $drivers, $driverBookings)
	{
		$reportPath = $this->getPreferences('ReportTemp');
		$srYear = $arg_input['start_year_1'];
		$spYear = $arg_input['stop_year_1'];
		$result = array();

		$fname = $reportPath . "/report" . uniqid(time()) . ".xls";

		$workbook = & new writeexcel_workbook($fname);
		$worksheet = & $workbook->addworksheet('รายงานประวัติคนขับรถ');
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


		$headings1 = array("สำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย", '');
		$headings2 = array("รายงานเปรียบเทียบการปฏิบัติงานของพนักงานปฏิบัติงาน", '');
		$headings3 = array("ระหว่างปี $srYear ถึงปี $spYear", '');

		$center = (int) (count($drivers) / 2) + 67;
		$worksheet->write_row($this->charector[$center] . '1', $headings1, $heading0);
		$worksheet->write_row($this->charector[$center] . '2', $headings2, $heading);
		$worksheet->write_row($this->charector[$center] . '3', $headings3, $heading);


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
		$format3->set_align("center");

		$format4 = & $workbook->addformat();
		$format4->set_text_wrap();
		$format4->set_bottom(1);
		$format4->set_left(1);
		$format4->set_right(1);
		$format4->set_top(1);
		$format4->set_size(10);
		$format4->set_align("left");

		$format5 = & $workbook->addformat(array('bold' => 1));
		$format5->set_text_wrap();
		$format5->set_bottom(1);
		$format5->set_left(1);
		$format5->set_right(1);
		$format5->set_top(1);
		$format5->set_size(10);
		$format5->set_align("center");

		$colWidth = 1;
		foreach ($drivers as $value)
		{
			$worksheet->set_column($colWidth, $colWidth, 12);
			$worksheet->set_column($colWidth + 1, $colWidth + 1, 12);
			$colWidth += 2;
		}

		$count_col_xls = 67;
		$count_row_xls = 6;

		$worksheet->write($this->charector[66] . $count_row_xls, 'ชื่อ', $format2);
		$worksheet->write($this->charector[66] . ($count_row_xls + 1), 'ปี ', $format2);
		$count_col_xls_merge = $count_col_xls;
		foreach ($drivers as $value)
		{
			$worksheet->write($this->charector[$count_col_xls_merge] . $count_row_xls, $value['first_name'] . ' ' . $value['last_name'], $format2);
			$worksheet->write($this->charector[$count_col_xls_merge + 1] . $count_row_xls, '', $format2);

			$worksheet->write($this->charector[$count_col_xls_merge] . ($count_row_xls + 1), 'ส่วนกลาง', $format2);
			$worksheet->write($this->charector[$count_col_xls_merge + 1] . ($count_row_xls + 1), 'ต่างจังหวัด', $format2);

			$worksheet->merge_cells($this->charector[$count_col_xls_merge] . $count_row_xls . ':' . $this->charector[$count_col_xls_merge + 1] . $count_row_xls);
			$count_col_xls_merge += 2;
		}
		$count_row_xls += 2;



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
			$count_col_xls = 66;
			$worksheet->write($this->charector[$count_col_xls] . $count_row_xls, $cYear, $format4);
			$count_col_xls++;


			$srDate = $cYear . '0000';
			$spDate = $cYear . '9999';
			//print_r($drivers);die();
			foreach ($drivers as $driver)
			{
				$countIn = 0;
				$countOut = 0;
				if (!is_array($driverBookings))
				{
					$result[$cYear][$mounth] = $count;
					continue;
				}
				foreach ($driverBookings as $driverBooking)
				{
					if ($driverBooking['driver_id'] == $driver['driver_id'] &&
							$driverBooking['go_date'] >= $srDate &&
							$driverBooking['back_date'] <= $spDate)
					{
						if ($driverBooking['type'] == 0)
							$countIn++;
						else
							$countOut++;
					}
				}
				$result[$cYear]['count_data'][$driver['driver_id']]['data'] = $count;
				$result[$cYear]['count_data'][$driver['driver_id']]['drivetr_id'] = $driver['driver_id'];
				$worksheet->write($this->charector[$count_col_xls] . $count_row_xls, $countIn, $format3);
				$worksheet->write($this->charector[$count_col_xls + 1] . $count_row_xls, $countOut, $format3);
				$count_col_xls += 2;
			}
			$count_row_xls++;
			$result[$cYear]['year'] = $cYear;
		}

		$worksheet->write($this->charector[66] . $count_row_xls, 'รวม', $format3);

		$count_col_xls = 67;
		foreach ($drivers as $value)
		{
			$bookingIn = $this->findAll(array('driver_id' => $value['driver_id'], 'type' => 0, 'go_date >=' => $srYear . '0000', 'back_date <=' => $spYear . '9999'));
			$bookingOut = $this->findAll(array('driver_id' => $value['driver_id'], 'type' => 1, 'go_date >=' => $srYear . '0000', 'back_date <=' => $spYear . '9999'));

			$countIn = count($bookingIn);
			$countOut = count($bookingOut);

			$worksheet->write($this->charector[$count_col_xls] . $count_row_xls, $countIn, $format5);
			$worksheet->write($this->charector[$count_col_xls + 1] . $count_row_xls, $countOut, $format5);

			$count_col_xls += 2;
		}

		$workbook->close();

		$result['real_path'] = $fname;
//		print $content;

		return $result;
	}

	function checkCheifId($idCheif = '', $cheifName = '')
	{
		//$cheifName = iconv('UTF-8', 'TIS-620', $cheifName);
		$m_account = new MAccount($this->parent);
		$account = $m_account->findById(array('f_acc_id' => $idCheif));

		$realCheifName = $account['f_name'] . ' ' . $account['f_last_name'];
		if ($realCheifName == $cheifName)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}

?>