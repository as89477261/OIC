<?php

require_once('CReservationModel.php');

require_once('MCar.php');

class MDriver extends CReservationModel
{

	var $primary_key = 'driver_id';
	var $table_name = 'tbl_driver';
	var $model_name = 'driver';
	var $order_by = array('driver_id' => ORDERBY_ASC, 'first_name' => ORDERBY_ASC, 'last_name' => ORDERBY_ASC);
	var $string_column_list = array('first_name', 'last_name');
	var $has_one = array(MCar => array('car_id'));

	function getDefaultData($driverId = '')
	{
		$driverTypeList = $this->getDriverType();
		
		if ($driverId != '')
		{
			$drivers = $this->findById($driverId);
			$driver['driver_id'] = $drivers['driver_id'];
			$driver['first_name'] = $drivers['first_name'];
			$driver['last_name'] = $drivers['last_name'];
			$driver['type'] = getList($driverTypeList, 'Value', 'Text', $drivers['type']);
		}
		else
		{
			$driver['driver_id'] = '';
			$driver['first_name'] = '';
			$driver['last_name'] = '';
			$driver['type'] = getList($driverTypeList, 'Value', 'Text');
		}

		return $driver;
	}

	function insertDriver($driver)
	{
		$ds = $this->getDataStore();
		$ds->debug_sql = true;
		unset($driver['driver_id']);
		$driver = $this->arrayConvertEncoding('UTF-8', 'TIS-620', $driver);
		$result = $this->insert($driver);
		return $result;
	}

	function edit($driver)
	{
		$driver_id = $driver['driver_id'];
		unset($driver['driver_id']);
		$driver = $this->arrayConvertEncoding('UTF-8', 'TIS-620', $driver);
		return $this->updateById($driver_id, $driver);
	}

	function getDriverType()
	{
		return $this->getListFromPreferences('DriverType');
	}

}

?>