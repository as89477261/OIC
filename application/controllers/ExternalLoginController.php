<?php
/**
 * โปรแกรมทำการ External Login ของ Exponare (AppWork)
 * @author Mahasak Pijittu,
 * @version 1.0.0
 * @package controller
 * @category External
 */
class ExternalLoginController Extends ECMController  {
	/**
	 * default action -> Forbidden
	 *
	 */
	public function indexAction() {
		die('Forbidden');
	}
	/**
	 * ทำการ Login
	 *
	 */
	public function postAction() {
		global $config;
		$fp = fopen("d:/AppWork/test.txt","a+");	
		fwrite($fp,"UID:".$_POST['uid']."\r\n");
		fwrite($fp,"PWD:".$_POST['pwd']."\r\n");
		fwrite($fp,"SALT:".$_POST['salt']."\r\n");
		
		//include_once 'AuthenSQL.php';
		$auth = new AuthenSQL();
		$salt = uniqid();
		if($config['useCHAPSLogin']) {
			$pwd = md5 ( $salt . $_POST['pwd'] );
			fwrite($fp,"CHAPs:".$_POST['salt']."\r\n");
		} else {
			$pwd = md5 ( $_POST['pwd'] );
			fwrite($fp,"NO CHAPs:".$_POST['salt']."\r\n");
		}
		
		$result = $auth->authenticate($_POST['uid'],$pwd,$salt);
		fwrite($fp,serialize($result)."\r\n");
		if($result['success']) {
			$authenticatedXML = 1;
			fwrite($fp,"RESULT:1\r\n");
		} else {
			$authenticatedXML = 0;
			fwrite($fp,"RESULT:0\r\n");
		}
		fclose($fp);
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
		$xml .= "<BenefitResponse>";
		$xml .= "<ResponseType>Authenticate</ResponseType>";
		$xml .= "<Authenticated>{$authenticatedXML}</Authenticated>";
		$xml .= "<Responses>";
		$xml .= "<Response><Name>Authenticated</Name><Value>1</Value></Response>";
		$xml .= "<Response><Name>menu1</Name><Value>1</Value></Response>";
		$xml .= "<Response><Name>menu2</Name><Value>0</Value></Response>";
		$xml .= "</Responses>";
		$xml .= "</BenefitResponse>";
		
		header("Content-Type: text/xml");
		
		
		
		echo $xml;
	}
}