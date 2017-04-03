<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MPassport
 *
 * @author admin
 */

require_once('CReservationModel.php');

class MPassport extends CReservationModel
{

	var $primary_key = array('f_role_id', 'f_acc_id');
	var $table_name = 'tbl_passport';
	var $model_name = 'tbl_passport';
	var $has_one = array(MAccount => 'f_acc_id');
	var $order_by = array('f_acc_id' => ORDERBY_ASC);

	function findAllAccount($accountId, $keyword)
	{
		$result = array();
		$result = $this->findAll(array('f_acc_id <>' => $accountId));

		$dAccount = new MAccount($this->parent);
//		$ds = $dAccount->getDataStore();
//		$ds->debug_sql = true;
		$accouts = $dAccount->findAll(array('OR 1' => array('f_name LIKE' => $keyword . '%', 'f_last_name LIKE' => $keyword . '%')));
		$result = $this->mergeArray($result, $accouts, 'f_acc_id');

		$dPole = new MRole($this->parent);
		$role = $dPole->findAll();
		$result = $this->mergeArray($result, $role, 'f_role_id');

		$dOrg = new MOrganize($this->parent);
		$org = $dOrg->findAll();
		$result = $this->mergeArray($result, $org, 'f_org_id');

		$result = copyRowsValue($result, array('f_acc_id', 'f_name', 'f_last_name', 'f_role_id', 'f_role_name', 'f_org_id', 'f_org_name'));

		return $result;
	}

	function mergeArray($mainArray1, $array2, $keyword)
	{
		$array2 = makeAssocArray($array2, $keyword);
		foreach($mainArray1 as $key => &$main)
		{
			$arrTmp = $array2[$main[$keyword]];
			if(!is_array($arrTmp))
			{
				unset($mainArray1[$key]);
				continue;
			}
			$main = array_merge($main, $arrTmp);
		}
		return $mainArray1;
	}
}
?>
