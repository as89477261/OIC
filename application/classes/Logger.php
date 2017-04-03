<?php
/**
 * Logger Class Wrapper
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Logger Class
 *
 */
class Logger {
	/**
	 * ทำการ Debug ไปยัง FirePHP
	 *
	 * @param string $message
	 */
	public static function debug($message) {
		global $config;
		global $ffBrowser;
		
		if ($config ['debugMode'] && $ffBrowser) {
			global $firePHP;
			$firePHP->fb ( $message, FirePHP::INFO );
			//$firePHP->fb('Trace [Debug]', FirePHP::TRACE);
		}
	}
	
	/**
	 * ทำการ Dump data ลงไปยัง FirePHP
	 *
	 * @param string $label
	 * @param mixed $var
	 */
	public static function dump($label,$var) {
		global $config;
		global $ffBrowser;
		
		if ($config ['debugMode'] && $ffBrowser) {
			global $firePHP;
			$firePHP->log($var,$label);
			//$firePHP->fb('Trace : '.$label, FirePHP::TRACE);
		}
	}
	
	/**
	 * ทำการ Log ลงไปยัง Log Mechanism
	 *
	 * @param string $objType
	 * @param int $objID
	 * @param string $message
	 * @param boolean $auditLogger
	 * @param boolean $errorLogger
	 * @param boolean $activityType
	 */
	public static function log($objType, $objID, $message, $auditLogger = true, $errorLogger = true,$activityType=0) {
		global $audit;
		global $config;
		global $logger;
		global $errorLog;
		global $ffBrowser;
		
		//$logWrited = true;

		if ($objType != 0 || is_null ( $objType )) {
			$logMsg = "Object type [$objType]\r\nObject ID [$objID]\r\n";
		} else {
			$logMsg = "";
		}
		
		$logMsg .= $message;
		
		if ($auditLogger) {
			$logger->log ( $logMsg, Zend_Log::INFO );
			$audit->log ( $objType, $objID, $message,$activityType );
		}
		
		if ($errorLogger) {
			$errorLog->log ( $logMsg, Zend_Log::CRIT );
		}
		Logger::debug("FF Browser {$ffBrowser}");
		if ($config ['debugMode'] && $ffBrowser) {
			global $firePHP;
			if ($auditLogger) {
				$firePHP->fb ( $logMsg, FirePHP::INFO );
			}
			if ($errorLogger) {
				$firePHP->fb ( $logMsg, FirePHP::ERROR );
			}
		}
	}

	public function SyncHRLog($type,$param,$status)
    {
		global $config;

		$msgtype = '';
		switch($type)
		{
		case 1: $msgtype = 'DEPT_ADD'; break;
		case 2: $msgtype = 'DEPT_EDT'; break;
		case 3: $msgtype = 'ROLE_ADD'; break;
		case 4: $msgtype = 'ROLE_EDT'; break;
		case 5: $msgtype = 'ROLE_MOV'; break;
		case 6: $msgtype = 'ACCT_ADD'; break;
		case 7: $msgtype = 'ACCT_EDT'; break;
		case 8: $msgtype = 'ACCT_MOV'; break;
		}
		if ($msgtype == '') return false;
		$log = date('d.m.Y h:i:s').'  -  '.$msgtype.' - '.$status.' - '.http_build_query($param)."\n";
		error_log($log, 3, $config ['logPath'] . 'sycn_hr_log_' . date('Y_m_d') . '.log');
		return true;
    } 

}
