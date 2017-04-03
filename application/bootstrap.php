<?php
require_once 'maintenance.php';

//Define a global variables
global $audit;
global $cache;
global $config;
global $conn;
global $lang;
global $logger;
global $errorLog;
global $logWriter;
global $sequence;
global $sessionMgr;
global $store;
global $util;
global $ieBrowser;
global $ffBrowser;
global $ADODB_FETCH_MODE;
global $ADODB_CACHE_DIR;
global $debugMode;
global $policy;
global $license;
global $emergencyStatus;
global $emergencyCode;

//declare an emergency status
// 0 = none
// 100 = Database Failure
// 200 = Cache Failure
// 300 = License Failure
$emergencyStatus = false;
$emergencyCode = 0;

//checkpoint for IE & Mozilla Firefox
$ieBrowser = false;
$ffBrowser = false;

// detect session settings and try to start if session is not autostarted 
if (ini_get ( 'session.auto_start' ) != 1) {
	@session_start ();
}

//Load Application Configuration


require_once 'config.php';
require_once 'system.php';
//Load Function definition
require_once ('functions.php');

//Set default timezone
date_default_timezone_set ( $config ['timezone'] );

/**
 * Benefit ECM Include Path
 * 1 - Zend Framework
 * 2 - Language Path
 * 3 - Entity Path
 * 4 - Portlets Path
 * 5 - Classes Path
 * 6 - Runtime Path
 */

// setup BenefitECM Include Path 
set_include_path ( $config ['ZendFrameworkPath'] . PATH_SEPARATOR . $config ['langPath'] . PATH_SEPARATOR . $config ['entityPath'] . PATH_SEPARATOR . $config ['classPath'] . PATH_SEPARATOR . $config ['portletPath'] . PATH_SEPARATOR . $config ['runtimePath'] . PATH_SEPARATOR . $config ['fckeditorPath'] . PATH_SEPARATOR . get_include_path () );

//Detect for Application Language
if (! array_key_exists ( 'appLang', $_COOKIE )) {
	$_SESSION ['appLang'] = $config ['defaultLang'];
}
if (array_key_exists ( 'appLang', $_COOKIE )) {
	$_SESSION ['appLang'] = $_COOKIE ['appLang'];
}

//define Application Encoding constant
switch ($_SESSION ['appLang']) {
	case 'th' :
		define ( 'APP_ENC', 'TIS-620' );
		break;
	case 'utf' :
		define ( 'APP_ENC', 'UTF-8' );
		break;
	default :
		define ( 'APP_ENC', 'ISO-8859-1' );
		break;
}

//defining an language file
//in use
define ( 'LANG_MAIN', $config ['langPath'] . "main_{$_SESSION['appLang']}.php" );

//no use
define ( 'LANG_ECM', $config ['langPath'] . "ecm_{$_SESSION['appLang']}.php" );

// no use
define ( 'LANG_DMS', $config ['langPath'] . "dms_{$_SESSION['appLang']}.php" );

//no use
define ( 'LANG_SARABAN', $config ['langPath'] . "saraban_{$_SESSION['appLang']}.php" );

//Setup Zend Front Controller
require_once "Zend/Loader.php";


//For ZF Version > 1.8.0
if((float)$config ['libVersion'] ['zf'] >= 1.8 ) {
	require_once "Zend/Loader/Autoloader.php";
	Zend_Loader::loadClass ( 'Fenrir_Utility_AutoLoader', $config ['appPath'] . 'application/library' );	
	$autoloader = Zend_Loader_Autoloader::getInstance();	
	$fenrirAutoloader = new Fenrir_Utility_AutoLoader();	
	$autoloader->pushAutoloader($fenrirAutoloader);
} else {
	Zend_Loader::loadClass ( 'Fenrir_Utility_Loader', $config ['appPath'] . 'application/library' );
	Zend_Loader::registerAutoload ( 'Fenrir_Utility_Loader' );	
}

//Add Fenrir Helper Classes
Zend_Controller_Action_HelperBroker::addPath ( $config ['appPath'] . 'application/helpers', 'Zend_Controller_Action_Helper' );
Zend_Controller_Action_HelperBroker::addHelper ( new Fenrir_Helper_SslSwitch ( ) );
Zend_Controller_Action_HelperBroker::addHelper ( new Fenrir_Helper_NonSslSwitch ( ) );

//Load main language file
require_once (LANG_MAIN);

//Define ADODB_ASSOC_CASE ,as the manual mention that must be defined before load an library
define ( 'ADODB_ASSOC_CASE', 0 );

//load primitive classes
require_once 'ECM.php';
#require_once 'ECMUtility.php';
require_once 'SequenceManager.php';
require_once 'StoreManager.php';
require_once 'SessionManager.php';
require_once 'ECMController.php';
require_once 'AuditLog.php';

//load external library
loadExternalLib ( 'ADODB' );
loadExternalLib ( 'HTTPClient' );
loadExternalLib ( 'ExcelWriter' );
if ($ffBrowser && $config ['debugMode']) {
	loadExternalLib ( 'firePHP' );
	global $firePHP;
	$firePHP = FirePHP::getInstance ( true );
}

//Create log writer objects
$commonLogWriter = new Zend_Log_Writer_Stream ( $config ['commonLogFile'] );
$errorLogWriter = new Zend_Log_Writer_Stream ( $config ['errorLogFile'] );

//create logger objects
$logger = new Zend_Log ( $commonLogWriter );
$errorLog = new Zend_Log ( $errorLogWriter );

$util = new ECMUtility ( );
$logger->setEventItem ( 'ip', $util->getIPAddress () );
$errorLog->setEventItem ( 'ip', $util->getIPAddress () );

$logger->setEventItem ( 'ip', date ( "d/m/Y,H:i:s" ) );
$errorLog->setEventItem ( 'ip', $util->getIPAddress () );

$format = '[%ip%] %priorityName% (%priority%): %message% %timestamp% ' . PHP_EOL;
$formatter = new Zend_Log_Formatter_Simple ( $format );

$commonLogWriter->setFormatter ( $formatter );
$errorLogWriter->setFormatter ( $formatter );

//Setup universal logger
//require_once 'Logger.php';


//ADODB Setup
//$ADODB_ACTIVE_CACHESECS = 2;
$ADODB_FETCH_MODE = constant ( 'ADODB_FETCH_ASSOC' );
$ADODB_CACHE_DIR = $config ['adodbCachePath'];

//var_dump($config['db']);

//try creating an ADODB connection object
try {
	$conn = NewADOConnection ( $config ['db'] ['control'] ['type'] );
	//If Cache Queries to Memcached server config this
	if ($config ['memcacheInterface'] && $config ['adodbCacheOnMemcache']) {
		// should we use memCache instead of caching in files
		$conn->memCache = true;
		// memCache host
		$conn->memCacheHost = $config ['memcache'] ['server'];
		// this is default memCache port 
		$conn->memCachePort = $config ['memcache'] ['port'];
		// memcache gz compression 
		$conn->memCacheCompress = $config ['memcacheCompress'];
	}
	//Connect
	$conn->PConnect ( $config ['db'] ['control'] ['host'], $config ['db'] ['control'] ['uid'], $config ['db'] ['control'] ['pwd'], $config ['db'] ['control'] ['database'] );
	if (! $conn) {
		throw new Exception ( "Database could not be connect" );
	}
	if ($config ['db'] ['control'] ['type'] == 'mysql') {
		$conn->Execute ( 'set names tis620' );
	}
	//do SQL Log?
	$conn->LogSQL ( $config ['db'] ['logSQL'] );
	
	//setup ADODB Active Record Adapter
	ADODB_Active_Record::SetDatabaseAdapter ( $conn );
} catch ( Exception $e ) {
	Logger::log ( 0, 0, "Database Connection cannot be established", false, true );
	$emergencyStatus = true;
	$emergencyCode = 100;
	$errorView = new Zend_View ( );
	$errorView->setScriptPath ( "{$config ['appPath']}application/views/error/" );
	echo $errorView->render ( 'databaseError.phtml' );
	die ();
}

//setup cache frontend
$frontendOptions = array ('lifetime' => 7200, 'automatic_serialization' => true );
$checkSessionFrontendOptions = array ('lifetime' => 60, 'automatic_serialization' => true );

//setup cache backend
if ($config ['zendCacheOnMemcache']) {
	if ($config ['memcacheInterface']) {
		$backendName = "Memcached";
		$backendOptions = array ('servers' => array (array ('host' => 'localhost', 'port' => 11211, 'persistent' => true ) ) );
	} else {
		$backendName = "File";
		$backendOptions = array ('cache_dir' => $config ['zendCachePath'] );
	}
} else {
	$backendName = "File";
	$backendOptions = array ('cache_dir' => $config ['zendCachePath'] );
}

//create an common instances
$store = new StoreManager ( );
$sequence = new SequenceManager ( );
$audit = new AuditLog ( );
$sessionMgr = new SessionManager ( );
$license = new ECMLicense ( );
$license->startLicenseEngine ();

//create cache global instance
try {
	$cache = Zend_Cache::factory ( 'Core', $backendName, $frontendOptions, $backendOptions );
	//clean cache when theme change
	if (array_key_exists ( 'ECMTheme', $_GET )) {
		$cache->clean ( Zend_Cache::CLEANING_MODE_ALL );
	}
} catch ( Exception $e ) {
	Logger::log ( 0, 0, "Cache Intiailization Failed", false, true );
}

try {
	$checkSessionCache = Zend_Cache::factory ( 'Core', $backendName, $checkSessionFrontendOptions, $backendOptions );
	
} catch ( Exception $e ) {
	Logger::log ( 0, 0, "Check Session Cache Intiailization Failed", false, true );
}
$registry = Zend_Registry::getInstance();
$registry->set('checkSessionCache',$checkSessionCache);
unset($registry);

//include_once "Concurrent.Entity.php";
if (checkSession () && ! array_key_exists ( '_hb', $_GET )) {
	if (array_key_exists ( 'accID', $_SESSION )) {
		$concurrent = new ConcurrentEntity ( );
		$concurrent->Load ( "f_acc_id = '{$_SESSION['accID']}'" );
		$concurrent->f_last_access = time ();
		$concurrent->Update ();
	}
}

if ($config ['AdminAlwaysEnableDebugMode']) {
	if (( int ) $sessionMgr->getCurrentAccountEntity ()->f_account_type >= ( int ) 3) {
		$debugMode = true;
	}
	if($ieBrowser) {
		$debugMode = false;
	}
}