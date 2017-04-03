<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRole
 *
 * @author admin
 */

require_once('CReservationModel.php');

class MRole extends CReservationModel
{
    var $primary_key = 'f_role_id';
	var $table_name = 'tbl_role';
	var $model_name = 'tbl_role';

}
?>
