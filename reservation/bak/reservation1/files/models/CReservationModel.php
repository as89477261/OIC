<?

require_once('CModel.php');

class CReservationModel extends CModel
{
	function getListFromPreferences($pref_ksy)
	{
		$keys = array(WI_LIST_VALUE, WI_LIST_TEXT);
		$list = explode(',', $this->parent->app_info['Preferences'][$pref_ksy]);
		foreach ($list as $item)
			$result[] = array_combine($keys, explode(':', $item));
		return $result;
	}

	function getPreferences($pref_ksy)
	{
		$keys = array(WI_LIST_VALUE, WI_LIST_TEXT);
		$list = explode(',', $this->parent->app_info['Preferences'][$pref_ksy]);
		return array_shift($list);
	}
}