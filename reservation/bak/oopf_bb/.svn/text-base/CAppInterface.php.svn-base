<?php 
require_once('CApplicationLogic.php');
//require_once('CValidator.php');

class CAppInterface extends CApplicationLogic
{

	function chooseLanguage()
	{
		$lang_id = (array_key_exists(LANG_ID, $_REQUEST) && $_REQUEST[LANG_ID]) ? $_REQUEST[LANG_ID] : '';

		if ($lang_id == '')
			$lang_id = parent::chooseLanguage();

		return $lang_id;
	}

}
