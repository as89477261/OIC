<?php

require_once('CWebInterface.php');
require_once('MDriver.php');
require_once('MBooking.php');

class CwiDriverManagement extends CWebInterface
{

    var $function_list = array (
			'driver-ui' => array ( FN_ID => 'driver-ui', FN_NAME => 'getUi', FN_DESC => 'Driver Ui'),
            'driver-list' => array ( FN_ID => 'driver-list', FN_NAME => 'driverList', FN_DESC => 'Driver List'),
            'driver-add' => array ( FN_ID => 'driver-add', FN_NAME => 'driverAdd', FN_DESC => 'Add a New Driver'),
			'driver-edit' => array ( FN_ID => 'driver-edit', FN_NAME => 'driverEdit', FN_DESC => 'Driver Update'),
			'driver-delete' => array ( FN_ID => 'driver-delete', FN_NAME => 'driverDelete', FN_DESC => 'Driver Delete')
            );

	function getUi (&$arg_output, &$arg_template, $arg_input)
	{
		$m_booking = new MBooking($this);
		$positionId = $_SESSION['roleID'];
		$arg_output['policy'] = $m_booking->checkPermission($positionId);
		$arg_template = 'PageDriver';
        return RESULTTYPE_LAYOUT;
	}

	function driverList(&$arg_output, &$arg_template, $arg_input)
	{
        $m_driver = new MDriver($this);
        $m_booking = new MBooking($this);
		$arg_output['driver_rows'] = $m_driver->findAll();
		$arg_output['result_count'] = count($arg_output['driver_rows']);
        $m_booking->checkDelete($arg_output['driver_rows'], 'driver_id');
        $arg_template = 'PageListDriver';
        return RESULTTYPE_LAYOUT;
    }

	function mergeFormAddEditDriver(&$arg_output, &$arg_template)
	{
        $m_car = new MCar($this);
		$m_car->setListValueItem('car_id', 'car_license');
        $arg_output['car_list'] = $m_car->getList($arg_output['driver']['car_id']);//echo '&nbsp;';
        $arg_template = 'PageAddEditDriver';
		return RESULTTYPE_LAYOUT;
	}

    function driverAdd(&$arg_output, &$arg_template, $arg_input)
    {
        $m_driver = new MDriver($this);
		switch ($arg_input[FN_FN])
		{
			case '' :
                $arg_output['driver'] = $m_driver->getDefaultData();
				$arg_output['fid'] = 'driver-add';
				
				$this->mergeFormAddEditDriver($arg_output, $arg_template);
				$arg_template = 'PageAddEditDriver';echo '&nbsp;';
                return RESULTTYPE_LAYOUT;
				break;
			case 'save' :
				$data = $arg_input['driver'];
				$result = $m_driver->insertDriver($data);
				break;
		}
    }

	function driverEdit(&$arg_output, &$arg_template, $arg_input)
	{
		$m_driver = new MDriver($this);
		switch ($arg_input[FN_FN])
		{
			case '' :
                $arg_output['driver'] = $m_driver->getDefaultData($arg_input['driver_id']);
				$arg_output['fid'] = 'driver-edit';
				$this->mergeFormAddEditDriver($arg_output, $arg_template);
				$arg_template = 'PageAddEditDriver';echo '&nbsp;';
                return RESULTTYPE_LAYOUT;
				break;
			case 'save' :
				$data = $arg_input['driver'];print_r($data);
				$result = $m_driver->edit($data);
				break;
		}
	}

	function driverDelete(&$arg_output, &$arg_template, $arg_input)
	{
		$m_driver = new MDriver($this);
		$result = $m_driver->delete($arg_input['driver_id']);
		//$arg_output['HttpHeader'][] = 'Location: http://handicap/ECMDev/reservation/./reservation.php?fid=driver_list';
		//return RESULTTYPE_HTTPHDR;
		break;
	}

}

?>
