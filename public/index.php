<?php

error_reporting(E_ALL & ~E_NOTICE);
#ini_set('session.save_handler',"memcache");
#ini_set('session.save_path', "tcp://localhost:11211?persistent=1&weight=1&timeout=1&retry_interval=15#");
#
#die(ini_get('session.save_handler'));
/* get the bootstrap & configuration */
require('../application/bootstrap.php');
//die(ECM_MAINTENANCE);

/* declare $config as global variable */
global $config;
global $sessionMgr;

//ini_set('session.save_path',"tcp://localhost:11211?persistent=1&weight=1&timeout=1&retry_interval=15");

if(ini_get('session.auto_start')==0) {
	session_start();
}
if(ECM_MAINTENANCE && !(array_key_exists('webEnabled',$_SESSION) && $_SESSION['webEnabled'])) {
	
		header('Location: /'.$config['appName'].'/maintenance/');
		die();
	
}
//$sessionMgr->checkConcurrent();

/* get new Zend Front Controller Instance */
$frontController = Zend_Controller_Front::getInstance();

/* set the controller path */
$frontController->setControllerDirectory(
array(
	'default'=>$config['appPath'].'application/controllers',
	'zelda' =>$config['appPath'].'application/modules/zelda'
)

);

/* disable auto view renderer */
$frontController->setParam('noViewRenderer', true);
//$frontController->setParam('useDefaultControllerAlways', true);

$frontController->returnResponse( true);


/* dispatch the request to controllers */
$response = $frontController ->dispatch();
$request = $frontController->getRequest();
//ทดสอบภาษาไทย

if ($response ->isException()) {
    $exceptions = $response->getException();
    $errorController = New ErrorController($request,$response);
    $errorController->dispatch('errorAction');
} else {
    $response->sendHeaders();
    $response->outputBody();
}

if (get_magic_quotes_gpc()) {
     $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
     while (list($key, $val) = each($process)) {
         foreach ($val as $k => $v) {
             unset($process[$key][$k]);
             if (is_array($v)) {
                 $process[$key][stripslashes($k)] = $v;
                 $process[] = &$process[$key][stripslashes($k)];
             } else {
                 $process[$key][stripslashes($k)] = stripslashes($v);
             }
         }
     }
     unset($process);
}