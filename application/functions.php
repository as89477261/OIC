<?php

function div($a, $b) {
	return ($a - ($a % $b)) / $b;
}

function mod($a, $b) {
	return ((($a % $b) + $b) % $b);
}

function JSONCallback($array) {
	$data = json_encode ( $array );
}

function loadExternalLib($libName) {
	static $loadedExternalLib;
	global $config;
	if (! is_array ( $loadedExternalLib )) {
		$loadedExternalLib = Array ();
	}
	
	if (in_array ( $libName, $loadedExternalLib )) {
		return false;
	}
	
	switch ($libName) {
		case 'ADODB' :
			define ( 'ADODB_ERROR_HANDLER_LIB_FULL_PATH', 'adodb/' . $config ['libVersion'] ['adodb'] . '/adodb-errorhandler.inc.php' );
			define ( 'ADODB_LIB_FULL_PATH', 'adodb/' . $config ['libVersion'] ['adodb'] . '/adodb.inc.php' );
			define ( 'ADODB_ACTIVE_RECORD_LIB_FULL_PATH', 'adodb/' . $config ['libVersion'] ['adodb'] . '/adodb-active-record.inc.php' );
			define ( 'ADODB_EXCEPTION_LIB_FULL_PATH', 'adodb/' . $config ['libVersion'] ['adodb'] . '/adodb-exceptions.inc.php' );
			
			#require_once ADODB_ERROR_HANDLER_LIB_FULL_PATH;
			include_once ADODB_EXCEPTION_LIB_FULL_PATH;
			include_once ADODB_LIB_FULL_PATH;
			include_once ADODB_ACTIVE_RECORD_LIB_FULL_PATH;
			break;
		case 'WebDAVClient' :
		case 'DAVClient' :
			define ( 'DAV_CLIENT_FULL_PATH', 'WebDAVClient/0.1.3/class_webdav_client.php' );
			include_once DAV_CLIENT_FULL_PATH;
			break;
		case 'HTTPClient' :
			define ( 'HTTP_CLIENT_FULL_PATH', 'NetHTTPClient/Client.php' );
			include_once HTTP_CLIENT_FULL_PATH;
			break;
		case 'XMPPLib' :
			define ( 'XMPP_LIB_PATH', 'xmpphp/1.0.0/xmpp.php' );
			//die(XMPP_LIB_PATH);
			include_once XMPP_LIB_PATH;
			break;
		case 'ExcelWriter' :
			define('EXCEL_WRITER_WORKBOOK','writeexcel/0.3.0/class.writeexcel_workbook.inc.php');
			define('EXCEL_WRITER_WORKSHEET','writeexcel/0.3.0/class.writeexcel_worksheet.inc.php');
			include_once EXCEL_WRITER_WORKBOOK;
			include_once EXCEL_WRITER_WORKSHEET;
			break;
		case 'ExcelReader' :
			define('EXCEL_READER','phpExcelReader/reader.php');
			include_once EXCEL_READER;
			break;
		case 'ADLib' :
			define('ADLDAP_LIB','adLDAP/adLDAP.php');
			include_once ADLDAP_LIB;
			break;
		case 'Browscap' :
			define('BROWSCAP_LIB_PHP','phpbrowscap/browscap/Browscap.php');
			include_once BROWSCAP_LIB_PHP;
			break;
		case 'javaBridge' :
			define('PHP_JAVA_BRIDGE_LIB','javaBridge/5.1.0/Java.inc');
			define('JAVA_HOSTS','localhost:18080');
			include_once PHP_JAVA_BRIDGE_LIB;
			break;
		case 'nusoap' :
			define('PHP_SOAP_NUSOAP_LIB','nusoap/0.7.3/lib/nusoap.php');
			include_once PHP_SOAP_NUSOAP_LIB;
			break;
		case 'firePHP' :
			define('FIREPHP_LIB','FirePHP/lib/FirePHPCore/FirePHP.class.php');
			include_once FIREPHP_LIB;
			break;
	}
	$loadedExternalLib [] = $libName;
	return true;
}

function clearTempPath($accountID) {
	global $config;
	$userTempPath = $config ['tempPath'] . "{$accountID}";
	clearFolder ( $userTempPath . "/view" );
	clearFolder ( $userTempPath . "/create" );
	@unlink ( $userTempPath . "/view" );
	@unlink ( $userTempPath . "/create" );
	@unlink ( $userTempPath );
}

function clearFolder($path) {
	if (is_dir ( $path )) {
		$dh = opendir ( $path );
		if ($dh) {
			while ( ($file = readdir ( $dh )) !== false ) {
				if (! in_array ( $file, array ('.', '..' ) )) {
					@unlink ( "{$path}/{$file}" );
				}
			}
			closedir ( $dh );
		}
	}
}

function getFileExtension($filename) {
	$arrayName = explode ( ".", $filename );
	$count = count ( $arrayName );
	return $arrayName [$count - 1];
}

function getMimeType($ext) {
	switch ($ext) {
		case 'pdf' :
			return 'application/pdf';
			break;
		case 'jpg' :
			return 'image/jpg';
			break;
		case 'tif' :
			return 'image/tif';
			break;
		case 'png' :
			return 'image/png';
			break;
		case 'gif' :
			return 'image/gif';
			break;
		case 'bmp' :
			return 'image/bmp';
			break;
		case 'doc' :
			return 'application/word';
			break;
		case 'xls' :
			return 'application/xls';
			break;
		case 'ppt' :
			return 'application/powerpoint';
			break;
		case 'mdb' :
			return 'application/msaccess';
			break;
		case 'pdf' :
			return 'application/pdf';
			break;
		case 'txt' :
			return 'text/plain';
			break;
		default :
			return 'application/octet-stream';
			break;
	}

}

function UTFEncode($string, $encoding = 'default') {
	global $config;
	
	if (strtolower ( $encoding ) == 'default') {
		$fromEncoding = $config ['appEncoding'];
	} else {
		$fromEncoding = $encoding;
	}
	
	return iconv ( $fromEncoding, 'UTF-8', $string );
}

function UTFDecode($string, $encoding = 'default') {
	global $config;
	
	if (strtolower ( $encoding ) == 'default') {
		$targetEncoding = $config ['appEncoding'];
	} else {
		$targetEncoding = $encoding;
	}
	
	return iconv ( 'UTF-8', $targetEncoding, $string );

}

function checkKeyCase(Array &$array) {
	global $config;
	if (! is_array ( $array )) {
		return false;
	} else {
		if ($config ['db'] ['control'] ['type'] == 'oci8') {
			$array = array_change_key_case ( $array, CASE_LOWER );
		}
		return true;
	}
}

function getDefaultStorage() {
	include_once 'Storage.Entity.php';
	include_once 'DAVStorage.php';
	
	$storage = new StorageEntity ( );
	if (! $storage->Load ( "f_default = 1" )) {
		echo "no default storage found";
		return false;
	} else {
		$dav = new DAVStorage ( );
		$dav->connect ( $storage->f_st_server, $storage->f_st_uid, $storage->f_st_pwd, $storage->f_st_path, $storage->f_st_port );
		return $dav;
	}
}

function checkSession() {
	global $conn;

	if(ECM_MAINTENANCE) {
		return false;
	}
	
	$registry = Zend_Registry::getInstance();
	$checkSessionCache = $registry->get('checkSessionCache');
	

	if (! array_key_exists ( 'loggedIn', $_SESSION )) {
		return false;
	}
	
	if ($_SESSION ['loggedIn'] && array_key_exists ( 'accID', $_SESSION )) {
		//return true;
		$cacheID = "check_session_{$_SESSION['accID']}";
		Logger::debug('check session cache ID: '.$cacheID);
		if (! ($output = $checkSessionCache->load ( $cacheID ))) {
			Logger::debug('cache Not hit');
			$sqlCheckConcurrent = "select count(*) as COUNT_EXP from tbl_concurrent where f_acc_id = '{$_SESSION['accID']}'";
			
			$rsCheck = $conn->CacheExecute ( 60, $sqlCheckConcurrent );
			$tmpCheck = $rsCheck->FetchNextObject ();
			
			if ($tmpCheck->COUNT_EXP == 0) {
				unset ( $_SESSION ['loggedIn'] );
				unset ( $_SESSION ['accID'] );
				$checkSessionCache->save(false,$cacheID);
				return false;
			} else {
				$checkSessionCache->save(true,$cacheID);
				return true;
			}
			
		} else {
			Logger::debug('cache hit');
			return $output;
		}
	} else {
		return false;
	}

}

function checkSessionPortlet() {
	if (! checkSession ()) {
		$script = "<script type=\"text/javascript\">sessionExpired();</script>";
		echo $script;
		die ();
	}
}

function checkSessionJSON() {
	$start = microtime();
	
	if (! checkSession ()) {
		$stop = microtime();
		$result ['microtime'] = $stop-$start;
		$result ['redirectLogin'] = 1;
		echo json_encode ( $result );
		ob_end_flush();
		die ();
	} else {
		$stop = microtime();
		$result ['microtime'] = $stop-$start;
		$result ['redirectLogin'] = 0;
		echo json_encode ( $result );
		ob_end_flush();
		die ();
	}
}

// encodeDataURL function encodes the dataURL before it's served to FusionCharts.
// If you've parameters in your dataURL, you necessarily need to encode it.
// Param: $strDataURL - dataURL to be fed to chart
// Param: $addNoCacheStr - Whether to add aditional string to URL to disable caching of data
function encodeDataURL($strDataURL, $addNoCacheStr = false) {
	//Add the no-cache string if required
	if ($addNoCacheStr == true) {
		// We add ?FCCurrTime=xxyyzz
		// If the dataURL already contains a ?, we add &FCCurrTime=xxyyzz
		// We replace : with _, as FusionCharts cannot handle : in URLs
		if (strpos ( $strDataURL, "?" ) != 0)
			$strDataURL .= "&FCCurrTime=" . Date ( "Y m s h_i_s A" );
		else
			$strDataURL .= "?FCCurrTime=" . Date ( "Y m s h_i_s A" );
	}
	// URL Encode it
	return urlencode ( $strDataURL );
}

// datePart function converts MySQL database based on requested mask
// Param: $mask - what part of the date to return "m' for month,"d" for day, and "y" for year
// Param: $dateTimeStr - MySQL date/time format (yyyy-mm-dd HH:ii:ss)
function datePart($mask, $dateTimeStr) {
	@list ( $datePt, $timePt ) = explode ( " ", $dateTimeStr );
	$arDatePt = explode ( "-", $datePt );
	$dataStr = "";
	// Ensure we have 3 parameters for the date
	if (count ( $arDatePt ) == 3) {
		list ( $year, $month, $day ) = $arDatePt;
		// determine the request
		switch ($mask) {
			case "m" :
				return $month;
			case "d" :
				return $day;
			case "y" :
				return $year;
		}
		// default to mm/dd/yyyy
		return (trim ( $month . "/" . $day . "/" . $year ));
	}
	return $dataStr;
}

// renderChart renders the JavaScript + HTML code required to embed a chart.
// This function assumes that you've already included the FusionCharts JavaScript class
// in your page.


// $chartSWF - SWF File Name (and Path) of the chart which you intend to plot
// $strURL - If you intend to use dataURL method for this chart, pass the URL as this parameter. Else, set it to "" (in case of dataXML method)
// $strXML - If you intend to use dataXML method for this chart, pass the XML data as this parameter. Else, set it to "" (in case of dataURL method)
// $chartId - Id for the chart, using which it will be recognized in the HTML page. Each chart on the page needs to have a unique Id.
// $chartWidth - Intended width for the chart (in pixels)
// $chartHeight - Intended height for the chart (in pixels)
// $debugMode - Whether to start the chart in debug mode
// $registerWithJS - Whether to ask chart to register itself with JavaScript
function renderChart($chartSWF, $strURL, $strXML, $chartId, $chartWidth, $chartHeight, $debugMode, $registerWithJS) {
	//First we create a new DIV for each chart. We specify the name of DIV as "chartId"Div.			
	//DIV names are case-sensitive.
	

	// The Steps in the script block below are:
	//
	//  1)In the DIV the text "Chart" is shown to users before the chart has started loading
	//    (if there is a lag in relaying SWF from server). This text is also shown to users
	//    who do not have Flash Player installed. You can configure it as per your needs.
	//
	//  2) The chart is rendered using FusionCharts Class. Each chart's instance (JavaScript) Id 
	//     is named as chart_"chartId".		
	//
	//  3) Check whether we've to provide data using dataXML method or dataURL method
	//     save the data for usage below 
	if ($strXML == "")
		$tempData = "//Set the dataURL of the chart\n\t\tchart_$chartId.setDataURL(\"$strURL\")";
	else
		$tempData = "//Provide entire XML data using dataXML method\n\t\tchart_$chartId.setDataXML(\"$strXML\")";
		
	// Set up necessary variables for the RENDERCAHRT
	$chartIdDiv = $chartId . "Div";
	$ndebugMode = boolToNum ( $debugMode );
	$nregisterWithJS = boolToNum ( $registerWithJS );
	
	// create a string for outputting by the caller
	$render_chart = <<<RENDERCHART

	<!-- START Script Block for Chart $chartId -->
	<div id='$chartIdDiv' align='center'>
		Chart.
	</div>
	<script type="text/javascript">	
		//Instantiate the Chart	
		var chart_$chartId = new FusionCharts("$chartSWF", "$chartId", "$chartWidth", "$chartHeight", "$ndebugMode", "$nregisterWithJS");
		$tempData
		//Finally, render the chart.
		chart_$chartId.render("$chartIdDiv");
	</script>	
	<!-- END Script Block for Chart $chartId -->

RENDERCHART;
	
	return $render_chart;
}

//renderChartHTML function renders the HTML code for the JavaScript. This
//method does NOT embed the chart using JavaScript class. Instead, it uses
//direct HTML embedding. So, if you see the charts on IE 6 (or above), you'll
//see the "Click to activate..." message on the chart.
// $chartSWF - SWF File Name (and Path) of the chart which you intend to plot
// $strURL - If you intend to use dataURL method for this chart, pass the URL as this parameter. Else, set it to "" (in case of dataXML method)
// $strXML - If you intend to use dataXML method for this chart, pass the XML data as this parameter. Else, set it to "" (in case of dataURL method)
// $chartId - Id for the chart, using which it will be recognized in the HTML page. Each chart on the page needs to have a unique Id.
// $chartWidth - Intended width for the chart (in pixels)
// $chartHeight - Intended height for the chart (in pixels)
// $debugMode - Whether to start the chart in debug mode
function renderChartHTML($chartSWF, $strURL, $strXML, $chartId, $chartWidth, $chartHeight, $debugMode) {
	// Generate the FlashVars string based on whether dataURL has been provided
	// or dataXML.
	$strFlashVars = "&chartWidth=" . $chartWidth . "&chartHeight=" . $chartHeight . "&debugMode=" . boolToNum ( $debugMode );
	if ($strXML == "")
		// DataURL Mode
		$strFlashVars .= "&dataURL=" . $strURL;
	else
		//DataXML Mode
		$strFlashVars .= "&dataXML=" . $strXML;
	
	$HTML_chart = <<<HTMLCHART
	<!-- START Code Block for Chart $chartId -->
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="$chartWidth" height="$chartHeight" id="$chartId">
		<param name="allowScriptAccess" value="always" />
		<param name="movie" value="$chartSWF"/>		
		<param name="FlashVars" value="$strFlashVars" />
		<param name="quality" value="high" />
		<embed src="$chartSWF" FlashVars="$strFlashVars" quality="high" width="$chartWidth" height="$chartHeight" name="$chartId" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
	<!-- END Code Block for Chart $chartId -->
HTMLCHART;
	
	return $HTML_chart;
}

// boolToNum function converts boolean values to numeric (1/0)
function boolToNum($bVal) {
	return (($bVal == true) ? 1 : 0);
}

//escapeXML function helps you escape special characters in XML
function escapeXML($strItem, $forDataURL) {
	if ($forDataURL) {
		//Convert ' to &apos; if dataURL
		$strItem = str_replace ( "'", "&apos;", $strItem );
	} else {
		//Else for dataXML 		
		//Convert % to %25 ... ' to %26apos;  ...  & to %26
		$findStr = array ("%", "'", "&", "<", ">" );
		$repStr = array ("%25", "%26apos;", "%26", "&lt;", "&gt;" );
		$strItem = str_replace ( $findStr, $repStr, $strItem );
	}
	//Common replacements
	$findStr = array ("<", ">" );
	$repStr = array ("&lt;", "&gt;" );
	$strItem = str_replace ( $findStr, $repStr, $strItem );
	//We've not considered any special characters here. 
	//You can add them as per your language and requirements.
	//Return
	return $strItem;
}

//getPalette method returns a value between 1-5 depending on which
//paletter the user wants to plot the chart with. 
//Here, we just read from Session variable and show it
//In your application, you could read this configuration from your 
//User Configuration Manager, database, or global application settings
function getPalette() {
	//Return
	return (((! isset ( $_SESSION ['palette'] )) || ($_SESSION ['palette'] == "")) ? "2" : $_SESSION ['palette']);
}

//getAnimationState returns 0 or 1, depending on whether we've to
//animate chart. Here, we just read from Session variable and show it
//In your application, you could read this configuration from your 
//User Configuration Manager, database, or global application settings
function getAnimationState() {
	//Return	
	return (($_SESSION ['animation'] != "0") ? "1" : "0");
}

//getCaptionFontColor function returns a color code for caption. Basic
//idea to use this is to demonstrate how to centralize your cosmetic 
//attributes for the chart
function getCaptionFontColor() {
	//Return a hex color code without #
	//FFC30C - Yellow Color
	return "666666";
}

// MonthName function converts a numeric integer into a month name
// Param: $intMonth - a numver between 1-12, otherwise defaults to 1
// Param: $flag -  if true, short name; if true, long name;
function MonthName($intMonth, $flag) {
	
	$arShortMonth = array (1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec" );
	$arLongMonth = array (1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December" );
	
	if ($intMonth < 1 || $intMonth > 12)
		$intMonth = 1;
	
	if ($flag)
		return $arShortMonth [$intMonth];
	else
		return $arLongMonth [$intMonth];
}
