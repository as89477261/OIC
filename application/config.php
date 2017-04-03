<?php
global $config;
global $ieBrowser;
global $ffBrowser;
global $debugMode;
global $availableTheme;
global $availableLang;
global $requestTimestamp;
global $serverName;
##################################################################
# Section 1 : ค่าเริ่มต้นระบบ
##################################################################
/**
 * Default request timestamp intercept
 */
$requestTimestamp = $_SERVER ['REQUEST_TIME'];
$config['appPort'] = 80;
$config['appSSLPort'] = 443;
/**
 * Debug mode config
 */
$debugMode = true;
$config ['debugMode'] = $debugMode;
$config ['disableDebugOnIE'] = true;
$config ['AdminAlwaysEnableDebugMode'] = true;


/**
 * ค่าเริ่มต้นให้ย่อ/ขยาย Explorer ด้านซ้ายมือเมื่อเข้าระบบ
 * $config['autoExpandExplorer'] = true/false
 * $config['activeExplorerTab'] = 'DMS/อะไรก็ได้'
 */
if(array_key_exists('PLM',$_GET)) {
	$config['portletLayoutMode'] = $_GET['PLM'];
} else {
	$config['portletLayoutMode'] = 'DF';
}

$config['portletLayoutMode'] = 'DF';
$config ['autoExpandExplorer'] = false;
$config ['activeExplorerTab'] = 'DMS';

if($config['portletLayoutMode'] == 'DMS') {
	$config ['autoExpandExplorer'] = true;
	$config ['activeExplorerTab'] = 'DMS';
}

/**
 * Default วันหมดอายุเอกสาร (ปี)
 */
$config ['defaultDocExpire'] = 10;

//Runtime javascript alias name
$config ['runtimeJS'] = 'runtimejs';

//Application name setting
$config ['appName'] = 'ECM';

//Application default encoding
$config ['appEncoding'] = 'TIS-620';

//Default Application console
// Index , AppSelect 
$config ['defaultAppConsole'] = 'Index';

//Application Logo setting
$config ['logo'] = 'new2';
$config ['bg'] = 'default';

//Login Failed attemp
$config ['tries'] = 5;

//option : pck1,pck2,client1,client2
$config['logonScreen'] = "oic";

//wordwrap
$config['wordwrap'] = false;

/**
 * Date mode setting
 * B - Bhuddhist date mode
 * C - Internation date mode
 */
$config ['datemode'] = 'B';

//Default application language setting
$config ['defaultLang'] = 'th';

//Application timezone setting
$config ['timezone'] = 'Asia/Bangkok';

//Bubble message setting
$config ['showBubbleMsg'] = false;

//Notify Config
$config ['notify'] ['xmppWelcome'] = false;

//Concurrent Strict Mode
$config['concurrentCfg']['strict'] = false;

//Library Version
$config ['libVersion'] ['ext'] = '2.2';
$config ['libVersion'] ['zf'] = '1.8.1';
$config ['libVersion'] ['adodb'] = '5.0.6a';
$config ['libVersion'] ['javaBridge'] = '5.1.0';

##################################################################
# Section 2 : Module/Feature Configuration
##################################################################
$config ['module'] ['Docflow'] = true;
$config ['module'] ['Docimage'] = true;
$config ['module'] ['KBase'] = false;
$config ['module'] ['Workflow'] = false;
$config ['module'] ['RoomBooking'] = true;
$config ['module'] ['Meeting'] = true;
$config ['module'] ['CarBooking'] = false;
$config ['module'] ['Cluster'] = true;

/**
 * option
 * 005,010,015,020,025,030,035,040,050,100,150,200,250,300,U
 */
$config ['concurrent'] = 'U';

//Module disabling settings
$config ['disableWorkflow'] = false;
$config ['disableSaraban'] = false;
$config ['disableSarabanRoute'] = true;
$config ['disableArChiver'] = true;
$config ['disableCalendar'] = false;
$config ['disableReport'] = false;
$config ['disableAddon'] = true;
$config ['disableWorkflowUser'] = true;
$config ['disableSarabanReport'] = false;
$config ['disableAdminReport'] = false;
$config ['disableExecutiveReport'] = false;


/**
 * ค่าปรับแต่งการใช้งานแบบ Cluster/Load Balance
 * $config['clusterMode'] = true/false
 * $config['clusterName'= ชื่อของเครื่อง/hardware balancer
 */
$config ['clusterMode'] = true;
$config ['clusterName'] = 'backoffice.oic.or.th';
$config ['clusterMember'] = Array('192.168.8.121','192.168.8.122');

/**
 * ค่าปรับแต่งให้ระบบบังคับใช้ SSL เข้ารหัสในการ Login
 */
$config ['requireSSL'] = false;

##################################################################
# Section 3 : Path Configuration
##################################################################


//Application path setting
$config ['appPath'] = 'd:/ECM/';

//Application runtime library setting
$config ['runtimePath'] = 'd:/ECMRuntime/';

//Application Temp Path
$config ['mainTempPath'] = 'd:/ECMTemp/';

// Path Settings
//$config ['ZendFrameworkPath'] = $config ['runtimePath'] . 'ZF/1.5.0/library'; /* ZF 1.5*/
$config ['ZendFrameworkPath'] = $config ['runtimePath'] . 'ZF/' . $config ['libVersion'] ['zf'] . '/library'; /* ZF 1.6 */
$config ['appTempPath'] = $config ['mainTempPath'] . "tmp/app/";
$config ['pubTempPath'] = $config ['mainTempPath'] . "tmp/pub/";
$config ['langPath'] = $config ['appPath'] . 'application/languages/';
$config ['reportPath'] = $config ['appPath'] . 'application/reports/';
$config ['portletPath'] = $config ['appPath'] . 'application/portlets/';
$config ['entityPath'] = $config ['appPath'] . 'application/entities/';
$config ['classPath'] = $config ['appPath'] . 'application/classes/';
//$config ['tempPath'] = $config ['mainTempPath'] . 'temp/';
$config ['tempPath'] = $config ['pubTempPath'] . 'temp/';
//$config ['scanPath'] = $config ['mainTempPath'] . 'scan/';
$config ['scanPath'] = $config ['pubTempPath'] . 'scan/';
//$config ['logPath'] = $config ['mainTempPath'] . 'logs/';
$config ['logPath'] = $config ['mainTempPath'] . 'logs/';
$config ['storageTempPath'] = $config ['appTempPath'] . 'storage/';
$config ['thumbnailCachePath'] = $config ['appTempPath'] . 'thumbnail/';

//Log File Settings
$config ['commonLogFile'] = $config ['logPath'] . 'log_' . date ( 'Y_m_d' ) . '.log';
$config ['errorLogFile'] = $config ['logPath'] . 'error_log_' . date ( 'Y_m_d' ) . '.log';
$config ['SyncHRLogFile'] = $config ['logPath'] . 'sync_hr_log_' . date ( 'Y_m_d' ) . '.log';

//Cache Settings
$config ['cachePath'] = $config ['mainTempPath'] . 'cache/';
$config ['reportTempPath'] = $config ['cachePath'] . 'report/';
//$config ['cachePath'] = $config['mainTempPath'] .'cache/';
$config ['adodbCachePath'] = $config ['cachePath'] . 'adodb/';
$config ['zendCachePath'] = $config ['cachePath'] . 'zend/';
$config ['browscapCachePath'] = $config ['cachePath'] . 'browscap/';

##################################################################
# Section 4 : Database Configuration
##################################################################
//MSSQL
$config ['db']['logSQL'] = false;

/*
$config ['db'] ['control'] ['type'] = 'mssql';
$config ['db'] ['control'] ['host'] = 'localhost';
$config ['db'] ['control'] ['uid'] = 'sa';
$config ['db'] ['control'] ['pwd'] = 'mpijittum';
$config ['db'] ['control'] ['database'] = 'ECMDB';
*/
//$config ['db'] ['control'] ['type'] = 'mssql';
//$config ['db'] ['control'] ['host'] = 'localhost';
//$config ['db'] ['control'] ['uid'] = 'sa';
//$config ['db'] ['control'] ['pwd'] = '';
//$config ['db'] ['control'] ['database'] = 'ECM_eastw';


//MYSQL
//$config ['db'] ['control'] ['type'] = 'mysql';
//$config ['db'] ['control'] ['host'] = 'localhost';
//$config ['db'] ['control'] ['uid'] = 'root';
//$config ['db'] ['control'] ['pwd'] = 'mpijittum';
//$config ['db'] ['control'] ['database'] = 'ECMRev22';


//PGSQL
//$config ['db'] ['control'] ['type'] = 'postgres';
//$config ['db'] ['control'] ['host'] = 'localhost';
//$config ['db'] ['control'] ['uid'] = 'postgres';
//$config ['db'] ['control'] ['pwd'] = 'mpijittum';
//$config ['db'] ['control'] ['database'] = 'ECMRev2';


//ORACLE
/*
$config ['db'] ['control'] ['type'] = 'oci8';
$config ['db'] ['control'] ['host'] = 'localhost';
$config ['db'] ['control'] ['uid'] = 'EOFFICE';
$config ['db'] ['control'] ['pwd'] = 'EOFFICE';
$config ['db'] ['control'] ['database'] = 'ORCL';
*/

/*$config ['db'] ['control'] ['type'] = 'oci8';
$config ['db'] ['control'] ['host'] = 'localhost';
$config ['db'] ['control'] ['uid'] = 'ecm';
$config ['db'] ['control'] ['pwd'] = 'ecm1';
$config ['db'] ['control'] ['database'] = 'davinci';*/

/*$config ['db'] ['control'] ['type'] = 'oci8';
$config ['db'] ['control'] ['host'] = 'localhost';
$config ['db'] ['control'] ['uid'] = 'OIC';
$config ['db'] ['control'] ['pwd'] = 'slcadmin';
$config ['db'] ['control'] ['database'] = 'davinci';*/

$config ['db'] ['control'] ['type'] = 'oci8';
$config ['db'] ['control'] ['host'] = false;
$config ['db'] ['control'] ['uid'] = 'ecm54';
$config ['db'] ['control'] ['pwd'] = 'ecm54';
$config ['db'] ['control'] ['database'] = 'PMIS';

//$config ['db'] ['control'] ['uid'] = 'ECM';
//$config ['db'] ['control'] ['pwd'] = 'ECM1';
//$config ['db'] ['control'] ['database'] = 'BKOFF_CLB';

//$config['jdbcURL']  = "jdbc:oracle:thin:@192.168.8.235:1522:ora92tst";
$config['jdbcURL']  = "jdbc:oracle:thin:@192.168.8.121:1521:orcl";
//$config['jdbcURL'] = "jdbc:oracle:thin:@192.168.8.233:1521:BKOFF1";


##################################################################
# Section 5 : Cache Configuration
##################################################################


//Cache Config
$config ['disableZendCache'] = true;

//Cache Length Setting
$config ['defaultCacheSecs'] = 15;
$config ['defaultMidCacheSecs'] = 30;
$config ['defaultLongCacheSecs'] = 60;

$config ['shortCache'] = $config ['defaultCacheSecs'];
$config ['mediumCache'] = $config ['defaultMidCacheSecs'];
$config ['longCache'] = $config ['defaultLongCacheSecs'];

//Cache Interface Settings
$config ['memcacheInterface'] = false;
$config ['memcacheCompress'] = false;
$config ['adodbCacheOnMemcache'] = false;
$config ['zendCacheOnMemcache'] = false;

##################################################################
# Section 6 : Other Server Configuration (FTP,XMPP,SMTP)
##################################################################
//Login Console Settings
//ถ้าไม่ใช้ LDAPIntegrated ต้องระบุ useCHAPSLogin เป็น true เสมอ
$config ['useCHAPSLogin'] = true;

$config ['LDAPIntegrated'] = true;

// LDAP/AD Integration Settings
if ($config ['LDAPIntegrated']) {
	$config ['useCHAPSLogin'] = false;
	//$config ['LDAPoptions'] = Array ('account_suffix' => '@oic.or.th', 'base_dn' => 'OU=OIC,DC=oic,DC=or,DC=th', 'domain_controllers' => array ('192.168.110.92','192.168.110.91'), 'ad_username' => 'backoffice', 'ad_password' => 'u5hw7gsb' );
	$config ['LDAPoptions'] = Array ('account_suffix' => '@oic.or.th', 'base_dn' => 'OU=OIC,DC=oic,DC=or,DC=th', 'domain_controllers' => array ('soul.oic.or.th','heart.oic.or.th'), 'ad_username' => 'backoffice', 'ad_password' => 'u5hw7gsb' );
} else {
	$config ['useCHAPSLogin'] = true;
}


//FTP Settings
$config ['ftp'] ['enable'] = false;
$config ['ftp'] ['host'] = 'nb51-037';
$config ['ftp'] ['port'] = '21';
$config ['ftp'] ['username'] = 'ecmftp';
$config ['ftp'] ['password'] = 'ecm2008';

//SMTP Settings
$config ['smtp'] ['enable'] = false;
$config ['smtp'] ['host'] = 'nb51-037';
$config ['smtp'] ['authen'] = false;
$config ['smtp'] ['username'] = 'ecmmail';
$config ['smtp'] ['password'] = '1234';

//Time Server (SNTP)
$config ['TimeServerIP'] = '192.168.1.155';
$config ['TimeServerPort'] = 123;

//XMPP(Jive) Settings
$config ['xmpp'] ['enable'] = false;
$config ['xmpp'] ['domain'] = 'hades';
$config ['xmpp'] ['host'] = 'hades';
$config ['xmpp'] ['broadcastServiceName'] = 'broadcast.hades';
$config ['xmpp'] ['username'] = 'admin';
$config ['xmpp'] ['password'] = '#maverick$';

#$config ['xmpp'] ['enable'] = false;
#$config ['xmpp'] ['domain'] = 'onepapp';
#$config ['xmpp'] ['host'] = '172.20.3.11';
#$config ['xmpp'] ['broadcastServiceName'] = 'broadcast.onepapp';
#$config ['xmpp'] ['username'] = 'admin';
#$config ['xmpp'] ['password'] = 'isecure2003';


//Memcache Interface Settings
$config ['memcache'] ['server'] = '127.0.0.1';
$config ['memcache'] ['port'] = 11211;

$config ['NATEnabled'] = true;
$config ['NATMapping'] = Array(
'192.168.8.121'=>'172.22.3.121',
'192.168.8.122'=>'172.22.3.122',
'192.168.8.123'=>'172.22.3.123'
);

##################################################################
# Section 7 : Portlet Config
##################################################################
//Portlet Settings
//2col = 2 Column , 3col = 3 Column
$config ['portletLayout'] = 2; 

// Unused ?
$config ['portlet'] ['style'] = '2col';

//
$config ['portlet'] [1] ['enable'] = true;

//
$config ['portlet'] [2] ['enable'] = true;

//
$config ['portlet'] [3] ['enable'] = true;

//
$config ['portlet'] [4] ['enable'] = true;

//
$config ['portlet'] [5] ['enable'] = true;

//
$config ['portlet'] [6] ['enable'] = true;
$config ['portlet'] [6] ['name'] = 'OICApplicationPortlet';

//
$config ['portlet'] [7] ['enable'] = true;   

//สัญญาเงินกู้   
$config ['portlet'] [8] ['enable'] = false;

//Application Portlet Settings
$config ['applicationURL'] ['carReserve'] = 'http://nb51-044/pdmo/loginECM.php';
$config ['applicationURL'] ['roomReserve'] = 'http://nb51-044/pdmo/loginECM.php';
$config ['applicationURL'] ['assetSystem'] = 'http://nb51-044/pdmo/loginECM.php';

##################################################################
# Section 8 : Saraban Specific Config
##################################################################

$config ['defaultImportSarabanFormID'] = 1;                                       

//Always show Org.Name  in register book instead of user name,role name 
$config ['registerShowOrgOnly'] = true;

//Ignore change accepting date and time
$config ['docflow'] ['disableChangeAcceptDateTime'] = false;

//
$config ['disableOverrideRecvDateTime'] = true;

//
$config ['disableOverrideSendDateTime'] = true;

//
$config ['receiveFlowByOrigin'] = true;

//
$config ['sendTimeout'] = 120000;

//
$config ['AJAXTimeout'] = 120000;  

//ให้มี Governer ได้หลายคนในหนึ่งหน่วยงาน
$config ['multipleGoverner'] = true;

//ออกเลขหนังสือลับรวมกับภายใน
$config ['runningSecretWithNormal'] = true;

//ไม่ให้คีย์ออกเลขเอง
$config ['disableOverrideDocNo'] = false;

//Doc.Flow Circular Doc Identifier
$config ['circDocIdentifier'] = 'ว';

//แฟลกให้ออกเลขหนังสือภายในโดยให้ออกเลขของเจ้าของเรื่อง
$config ['genIntCircDocNoUseOwner'] = true;

//แฟลกให้ทำการตรวจสอบหนังสือซ้ำก่อนลงรับ
$config['checkReceiveDuplicate'] = false;

//แฟลกให้ทำการอนุมัติเอกสารอัตโนมัติหลังลงรับภายนอกและส่งต่อ
$config['autoApproveReceiveExternal']=true;

$config['enablePrintMasterDoc']=false;

$config['enableReportSpeed'] = false;

##################################################################
# Section 9 : Document Image
##################################################################
//ExtJS Version
$config['thumbnailPerPage'] = 5;
$config['doc2000SearchURL'] = "http://backoffice.oic.or.th/doc2000/search.php";
##################################################################
# Section 10 : Misc
##################################################################

//Manual URL Settings
$config['manual']['disable'] = false;
$config['manual']['DFEnable'] = true;
$config['manual']['DF'] = "http://backoffice.oic.or.th/downloads/manual.pdf";

$config['manual']['DMSEnable'] = false;
$config['manual']['DMS'] = "http://backoffice.oic.or.th/downloads/ecm.pdf";

$config['manual']['WFEnable'] = false;
$config['manual']['WF'] = "http://localhost/ecm.pdf";

$config ['integration']['benefit'] = true;
$config ['integration']['benefitUrl'] = "../workflow/index.php?module=ECMAutoLogin&action=Login";

$config['EGIFServer'] = array(
'www.zelda.go.th'=>'127.0.0.1',
'www.oic.go.th'=>'192.168.100.12',
'www.onep.go.th'=>'192.168.100.14',
'www.govt.go.th'=>'192.168.100.16'
);
##################################################################
# Advance Configuration
##################################################################\

/**
 * สำหรับ Advance Configuration Setup เท่านั้น
 * ห้ามแก้ไขถ้าไม่รู้ว่ากำลังจะทำอะไร
 * อาจจะทำให้ระบบทำงานผิดพลาดได้
 */

//Available language setting
$availableLang =Array ('th', 'en' );

//กำหนด Cookie เพื่อขยาย Explorer ด้านซ้ายมืออัตโนมัติ
if ($config ['autoExpandExplorer'] || $config['portletLayoutMode']=='DMS') {
	if (!array_key_exists ( 'axe', $_COOKIE ) || $_COOKIE ['axe'] != 1) {
		setcookie ( 'axe', 1, 0, '/' );
	}
	if ($config ['activeExplorerTab'] == 'DMS') {
		if (array_key_exists ( 'atet', $_COOKIE )) {
			if($_COOKIE ['atet'] != 1) {
					setcookie ( 'atet', 1, 0, '/' );
			}
		} else {
			setcookie ( 'atet', 1, 0, '/' );
		}
	} else {
		if (array_key_exists ( 'atet', $_COOKIE )) {
			if ($_COOKIE ['atet'] != 0) {
				setcookie ( 'atet', '0', 0, '/' );
			}
		} else {
			setcookie ( 'atet', '0', 0, '/' );
		}
	}
} else {
	if (array_key_exists ( 'axe', $_COOKIE )) {
		if ($_COOKIE ['axe'] != 0) {
			setcookie ( 'axe', 0, 0, '/' );
			setcookie ( 'atet', '0', 0, '/' );
		}
	}
}

//Available Themes
//$availableTheme = Array ('default', 'purple', 'slate', 'halo', 'gray', 'galdaka', 'black', 'darkgray', 'olive' ,'aero','vista');
$availableTheme = Array ('default', 'purple', 'slate', 'halo', 'gray', 'galdaka', 'black', 'darkgray', 'olive' );

//Theme Settings
if (array_key_exists ( 'ECMTheme', $_GET )) {
	setcookie ( 'ECMTheme', $_GET ['ECMTheme'], 0, '/' . $config ['appName'] );
	$config ['theme'] = $_GET ['ECMTheme'];
} else {
	if (array_key_exists ( 'ECMTheme', $_COOKIE )) {
		$config ['theme'] = $_COOKIE ['ECMTheme'];
	} else {
		$config ['theme'] = 'default';
	}
}

//Icon Settings
if (array_key_exists ( 'ECMIcon', $_GET )) {
	setcookie ( 'ECMIcon', $_GET ['ECMIcon'], 0, '/' . $config ['appName'] );
} else {
	if (!array_key_exists ( 'ECMIcon', $_COOKIE )) {
		setcookie ( 'ECMIcon', 0, 0, '/' . $config ['appName'] );
	}
}

//Language Settings
if (array_key_exists ( 'appLang', $_GET )) {
	setcookie ( 'appLang', $_GET ['appLang'], 0, '/' . $config ['appName'] );
	$config ['defaultLang'] = $_GET ['appLang'];
	$_COOKIE ['appLang'] = $config ['defaultLang'];
	$_SESSION ['appLang'] = $config ['defaultLang'];
} else {
	if (array_key_exists ( 'appLang', $_COOKIE )) {
		$config ['defaultLang'] = $_COOKIE ['appLang'];
		$_COOKIE ['appLang'] = $config ['defaultLang'];
		$_SESSION ['appLang'] = $config ['defaultLang'];
	} else {
		$config ['defaultLang'] = 'th';
        $_COOKIE ['appLang'] = $config ['defaultLang'];
        $_SESSION ['appLang'] = $config ['defaultLang'];
	}
}

if((array_key_exists('appLang',$_COOKIE)&&$_COOKIE['appLang'] == 'th')||$_SESSION ['appLang'] == 'th') {
	$config ['datemode'] = 'B';
} else {
	$config ['datemode'] = 'C';
}

define ( 'BROWSCAP_LIB', $config ['runtimePath'] . '/phpbrowscap/browscap/Browscap.php' );
include_once BROWSCAP_LIB;

//Get Browser Capability Object
$bc = new Browscap ( $config ['browscapCachePath'] );
$currentBrowser = $bc->getBrowser ();
if (strtolower ( $currentBrowser->Browser ) == 'firefox') {
	$ffBrowser = true;
	$ieBrowser = false;
} else {
	if (strtolower ( $currentBrowser->Browser ) == 'ie') {
		$ieBrowser = true;
		$ffBrowser = false;
	}
}


if($ieBrowser && $config['disableDebugOnIE']) {
	$debugMode = false;
}

//FCKEditor & Form Designer Settings
if ($ffBrowser) {
	$config ['fckeditorRelPath'] = "/{$config['runtimeJS']}/fckeditorFF/";
	$config ['fckeditorPath'] = $config ['runtimePath'] . 'js/fckeditorFF/';
} else {
	$config ['fckeditorRelPath'] = "/{$config['runtimeJS']}/fckeditor/";
	$config ['fckeditorPath'] = $config ['runtimePath'] . 'js/fckeditor/';
}

$config ['formPath'] = $config ['appPath'] . 'application/formDesign/';
$config ['fckImagePath'] = "{$config ['appPath']}public/images/upload/";

if ($config ['clusterMode']) {
	$serverName = $config ['clusterName'];
} else {
	$serverName = $_SERVER ['SERVER_NAME'];
}

