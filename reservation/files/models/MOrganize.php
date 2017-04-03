<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DOrganize
 *
 * @author admin
 */

require_once('CReservationModel.php');

class MOrganize extends CReservationModel
{
    var $primary_key = 'f_org_id';
	var $table_name = 'tbl_organize';
	var $model_name = 'tbl_organize';

}
?>
