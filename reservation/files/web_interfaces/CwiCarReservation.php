<?php

require_once('CWebInterface.php');
require_once('MCar.php');
require_once('MBooking.php');

class CwiCarReservation extends CWebInterface
{

    var $function_list = array(
        'car-ui' => array(FN_ID => 'car-ui', FN_NAME => 'getUi', FN_DESC => 'Car Ui'),
        'car-list' => array(FN_ID => 'car-list', FN_NAME => 'carList', FN_DESC => 'Car List'),
        'car-add' => array(FN_ID => 'car-add', FN_NAME => 'carAdd', FN_DESC => 'Add a New Car'),
        'car-edit' => array(FN_ID => 'car-edit', FN_NAME => 'carEdit', FN_DESC => 'Car Update'),
        'car-delete' => array(FN_ID => 'car-delete', FN_NAME => 'carDelete', FN_DESC => 'Car Delete'),
		'car-cancel' => array(FN_ID => 'car-cancel', FN_NAME => 'cancelCar', FN_DESC => 'Car Cancel')
    );

    function getUi(&$arg_output, &$arg_template, $arg_input)
    {
		$m_booking = new MBooking($this);
		$positionId = $_SESSION['roleID'];
		$arg_output['policy'] = $m_booking->checkPermission($positionId);
		if($arg_output['policy']['f_rc_access'] == 0)
		{
			$arg_template = 'PageNotAccress';
		}
		else
		{
			$arg_template = 'PageCar';
		}
        return RESULTTYPE_LAYOUT;
    }

    function carList(&$arg_output, &$arg_template, $arg_input)
    {
        $m_car = new MCar($this);
        $m_booking = new MBooking($this);
        $arg_output['car_rows'] = $m_car->findAll();
        $arg_output['result_count'] = count($arg_output['car_rows']);
        $m_booking->checkDelete($arg_output['car_rows'], 'car_id');
        $arg_template = 'PageListCar';
        return RESULTTYPE_LAYOUT;
    }

    function carAdd(&$arg_output, &$arg_template, $arg_input)
    {
        $m_car = new MCar($this);
        switch ($arg_input[FN_FN])
        {
            case '' :
                $arg_output['car'] = $m_car->getDefaultData();
                $arg_output['fid'] = 'car-add';
                $arg_template = 'PageAddEditCar';
                return RESULTTYPE_LAYOUT;
                break;
            case 'save' :
                $car = $arg_input['car'];
                $result = $m_car->insertCar($car);
                break;
        }
    }

    function carEdit(&$arg_output, &$arg_template, $arg_input)
    {
        $m_car = new MCar($this);
        switch ($arg_input[FN_FN])
        {
            case '' :
                $arg_output['car'] = $m_car->getData($arg_input['car_id']);
                $arg_output['fid'] = 'car-edit';
                $arg_template = 'PageAddEditCar';
                return RESULTTYPE_LAYOUT;
                break;
            case 'save' :
                $data = $arg_input['car'];
                $result = $m_car->edit($data);
                //$arg_output['HttpHeader'][] = 'Location: http://handicap/ECMDev/reservation/./reservation.php?fid=car_list';

                return RESULTTYPE_HTTPHDR;
                break;
        }
    }

    function carDelete(&$arg_output, &$arg_template, $arg_input)
    {
        $m_car = new MCar($this);
        $result = $m_car->delete($arg_input['car_id']);
    }

	function cancelCar(&$arg_output, &$arg_template, $arg_input)
	{
		$m_car = new MCar($this);
        $result = $m_car->updateStatus($arg_input['car_id'], $arg_input['status']);
	}
}
?>