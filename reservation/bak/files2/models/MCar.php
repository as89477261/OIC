<?php
require_once('CModel.php');

class MCar extends CModel
{
    var $primary_key = 'car_id';
    var $table_name = 'tbl_car';
    var $model_name = 'car';
    var $order_by = array('car_id' => ORDERBY_ASC);
    var $string_column_list = array('car_license', 'car_detail');
	//var $has_one = array(MBooking => array('booking_id'));
	//var $join_models = array(MExpenseType => array('exp_type_id'));

	function getDefaultData()
	{
		$car['car_id'] = '';
		$car['car_license'] = '';
		$car['car_detail'] = '';
		return $car;
	}

	function insertCar($car)
	{
		
		unset($car['car_id']);
		//$ds = $this->getDataStore();
		//$ds->debug_sql = true;
        $car = $this->arrayConvertEncoding('UTF-8', 'TIS-620', $car);
		$result = $this->insert($car);
	}

	function getData ($car_id)
	{
		$car = $this->findById($car_id);
		return $car;
	}

	function edit($car)
	{
		$car_id = $car['car_id'];
		unset($car['car_id']);
        $car = $this->arrayConvertEncoding('UTF-8', 'TIS-620', $car);
		return $this->updateById($car_id, $car);
	}
}

?>