<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MAccount
 *
 * @author Man
 */

require_once('CReservationModel.php');
require_once('MRole.php');
require_once('MOrganize.php');
require_once('MPassport.php');

class MAccount extends CReservationModel
{

	var $primary_key = 'f_acc_id';
	var $table_name = 'tbl_account';
	var $model_name = 'tbl_account';
	var $order_by = array('f_name' => ORDERBY_ASC);

}

?>
