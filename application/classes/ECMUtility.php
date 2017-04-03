<?php
/**
 * Class ECMUtility
 * 
 * @author Mahasak Pijittum
 * @version 1.0
 * @package classes
 * @category Utility Class
 */

class ECMUtility {
	/**
	 * ชื่อเดือนย่อ
	 *
	 * @var array
	 */
	protected $shortThaiMonth = Array (1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.' );
	/**
	 * ชื่อเดือนเต็ม
	 *
	 * @var array
	 */
	protected $fullThaiMonth = Array (1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม' );
	/**
	 * ชื่อเดือนย่อ(En)
	 *
	 * @var array
	 */
	protected $shortEngMonth = Array (1 => 'jan.', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'aug', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dec' );
	/**
	 * ชื่อเดือนเต็ม(En)
	 *
	 * @var array
	 */
	protected $fullEngMonth = Array (1 => 'january', 2 => 'febuary', 3 => 'march', 4 => 'april', 5 => 'may', 6 => 'june', 7 => 'july', 8 => 'august', 9 => 'september', 10 => 'october', 11 => 'november', 12 => 'december' );
	/**
	 * mapping เดือนไทยย่อ
	 *
	 * @var array
	 */
	protected $shortReverseThaiMonth = Array ('ม.ค.' => 1, 'ก.พ.' => 2, 'มี.ค.' => 3, 'เม.ย.' => 4, 'พ.ค.' => 5, 'มิ.ย.' => 6, 'ก.ค.' => 7, 'ส.ค.' => 8, 'ก.ย.' => 9, 'ต.ค.' => 10, 'พ.ย.' => 11, 'ธ.ค.' => 12 );
	/**
	 * mapping เดือน en ย่อ
	 *
	 * @var array
	 */
	protected $shortReverseMonth = Array ('jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12 );
	/**
	 * mapping เดือนไทยเต็ม
	 *
	 * @var array
	 */
	protected $fullReverseThaiMonth = Array ('มกราคม' => 1, 'กุมภาพันธ์' => 2, 'มีนาคม' => 3, 'เมษายน' => 4, 'พฤษภาคม' => 5, 'มิถุนายน' => 6, 'กรกฎาคม' => 7, 'สิงหาคม' => 8, 'กันยายน' => 9, 'ตุลาคม' => 10, 'พฤศจิกายน' => 11, 'ธันวาคม' => 12 );
	/**
	 * mapping เดือน en เต็ม
	 *
	 * @var array
	 */
	protected $fullReverseMonth = Array ('january' => 1, 'febuary' => 2, 'march' => 3, 'april' => 4, 'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8, 'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12 );
	/**
	 * Document Utility Object
	 *
	 * @var Object
	 */
	public $docUtil;
	/**
	 * Salt value
	 *
	 * @var unknown_type
	 */
	private $_salt;

	/**
	 * กำหนดค่า salt และ สร้าง Object DocUtil
	 *
	 */
	public function __construct() {
		//include_once 'DocUtil.php';
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Organize.Entity.php';
		//include_once 'xmpphp/1.0.0/xmpp.php';
		$this->_salt = md5 ( time () );
		$this->docUtil = new DocUtil ( );
	}
	
	/**
	 * แสดงค่า Byte จากหน่วยที่กำหนด
	 *
	 * @param int $size
	 * @param string $units
	 * @return string
	 */
	public function getByte2($size, $units) {
		switch ($units) {
			case 'B' :
				$round = 0;
				break;
			case 'KB' :
				$round = 1;
				break;
			case 'MB' :
				$round = 2;
				break;
			case 'GB' :
				$round = 3;
				break;
			case 'TB' :
				$round = 4;
				break;
		}
		$bytes = 1;
		for($i = 0; $i < $round; $i ++) {
			$bytes = $bytes * 1024;
		}
		return ($size * $bytes);
	}
	
	/**
	 * แปลงหน่วยจาก Byte เป็นหน่วยที่เหมาะสม
	 *
	 * @param int $filesize
	 * @param int $precision
	 * @param string $get
	 * @return string
	 */
	public function getByte($filesize, $precision = 2, $get = 'ALL') {
		$units = 0;
		while ( $filesize > 1024 ) {
			$filesize = $filesize / 1024;
			$units ++;
		}
		switch ($units) {
			case 0 :
				$strunit = 'Bytes';
				break;
			case 1 :
				$strunit = 'KB';
				break;
			case 2 :
				$strunit = 'MB';
				break;
			case 3 :
				$strunit = 'GB';
				break;
			case 4 :
				$strunit = 'TB';
				break;
		}
		$result ['filesize'] = round ( $filesize, $precision );
		$result ['strunit'] = $strunit;
		
		return ($get == 'ALL') ? round ( $filesize, $precision ) . ' ' . $strunit : $result [$get];
	}
	
	/**
	 * ขอค่า Salt
	 *
	 * @return string
	 */
	public function getSalt() {
		return $this->_salt;
	}
	
	/**
	 * get Thai month
	 *
	 * @param int $month month of the year
	 * @param iint $mode 1=short mode,2=full mode
	 * @return string
	 */
	public function getThaiMonth($month, $mode = 1) {
		if ($mode == 1) {
			$monthStr = $this->shortThaiMonth [$month];
		} else {
			$monthStr = $this->fullThaiMonth [$month];
		}
		return $monthStr;
	}
	
	/**
	 * Get Month
	 *
	 * @param int $month
	 * @param int $mode
	 * @return string
	 */
	public function getEngMonth($month, $mode = 1) {
		if ($mode == 1) {
			$monthStr = $this->shortEngMonth [$month];
		} else {
			$monthStr = $this->fullEngMonth [$month];
		}
		return $monthStr;
	}
	
	/**
	 * ขอเดือนที่จากเดือนไทย
	 *
	 * @param string $month
	 * @param int $mode
	 * @return int
	 */
	public function getThaiMonthNo($month, $mode = 1) {
		if ($mode == 1) {
			$monthNo = $this->shortReverseThaiMonth [$month];
		} else {
			$monthNo = $this->fullReverseThaiMonth [$month];
		}
		return $monthNo;
	}
	
	/**
	 * ขอเดือนที่จากเดือน En
	 *
	 * @param string $month
	 * @param int $mode
	 * @return int
	 */
	public function getEngMonthNo($month, $mode = 1) {
		if ($mode == 1) {
			$monthNo = $this->shortReverseMonth [$month];
		} else {
			$monthNo = $this->fullReverseMonth [$month];
		}
		return $monthNo;
	}
	
	/**
	 * ขอเดือนที่
	 *
	 * @param string $month
	 * @param int $mode
	 * @return int
	 */
	public function getMonthNo($month, $mode = 1) {
		if ($mode == 1) {
			$monthNo = $this->shortReverseMonth [$month];
		} else {
			$monthNo = $this->fullReverseMonth [$month];
		}
		return $monthNo;
	}
	
	/**
	 * ขอเวลา
	 *
	 * @param int $timestamp
	 * @return string
	 */
	public function getTimeString($timestamp = 0) {
		global $requestTimestamp;
		
		if ($timestamp == 0) {
			$timestamp = $requestTimestamp;
		}
		return date ( 'H:i:s', $timestamp );
	}
	
	/**
	 * แปลง Format ของวันเดือนปีจาก YYYYMMDD เป็น วันเดือนปีตามปกติ
	 *
	 * @param string $SLCDateStr
	 * @return string
	 */
	public function parseSLCDate($SLCDateStr) {
		global $config;
		global $util;
		$year = substr ( $SLCDateStr, 0, 4 );
		$month = substr ( $SLCDateStr, 4, 2 );
		$date = substr ( $SLCDateStr, 6, 2 );
		if ($config ['datemode'] == 'B') {
			$year = $year + 543;
			$dateStr = $date . " " . $util->getThaiMonth ( ( int ) $month ) . " " . $year;
		} else {
			$dateStr = $date . " " . $util->getThaiMonth ( ( int ) $month ) . " " . $year;
		}
		return $dateStr;
	}
	
	/**
	 * ขอชื่อหน่วยงานกรณีรับภายนอก
	 *
	 * @return string
	 */
	public function getDefaultReceiveExternalOrgName() {
		global $config;
		global $conn;
		
		$sqlGet = "select * from tbl_organize where f_org_id = '0'";
		$rs = $conn->CacheExecute ( $config ['defaultLongCacheSecs'], $sqlGet );
		$temp = $rs->FetchRow ();
		checkKeyCase ( $temp );
		return $temp ['f_org_name'];
	}
	
	/**
	 * ขอชื่อหน่วยงานกรณีรับภายใน
	 *
	 * @return string
	 */
	public function getDefaultReceiveInternalOrgName() {
		global $config;
		global $conn;
		global $sessionMgr;
		
		$sqlGet = "select * from tbl_organize where f_org_id = '{$sessionMgr->getCurrentOrgID()}'";
		$rs = $conn->CacheExecute ( $config ['defaultLongCacheSecs'], $sqlGet );
		$temp = $rs->FetchRow ();
		checkKeyCase ( $temp );
		return $temp ['f_org_name'];
	}
	
	/**
	 * แปลงเป็นวันเดือนปีปกติจาก HHMM
	 *
	 * @param unknown_type $SLCTimeStr
	 * @return unknown
	 */
	public function parseSLCTime($SLCTimeStr) {
		$hour = substr ( $SLCTimeStr, 0, 2 );
		$minute = substr ( $SLCTimeStr, 2, 2 );
		return "{$hour}:{$minute}";
	}
	
	/**
	 * ขอวันที่จาก Timestamp
	 *
	 * @param int $timestamp
	 * @param int $mode
	 * @return string
	 */
	public function getDateString($timestamp = 0, $mode = 1,$shortMode=false) {
		global $config;
		global $requestTimestamp;
		if ($timestamp == 0) {
			$timestamp = $requestTimestamp;
		}
		$date = date ( 'j', $timestamp );
		$month = date ( 'n', $timestamp );
		$year = date ( 'Y', $timestamp );
		$Tyear = $year + 543;
		if ($config ['datemode'] == 'B') {
			$outYear = $Tyear;
		} else {
			$outYear = $year;
		}
		if($shortMode) {
			$outYear = substr($outYear,2,2);
		}
		switch ($mode) {
			case '1' :
				if ($config ['datemode'] == 'B') {
					$dateStr = $date . " " . $this->getThaiMonth ( $month ) . " " . $outYear;
				} else {
					$dateStr = $date . " " . $this->getEngMonth ( $month ) . " " . $outYear;
				}
				break;
			case '2' :
				if ($config ['datemode'] == 'B') {
					$dateStr = $date . " " . $this->getThaiMonth ( $month ) . " " . $outYear;
				} else {
					$dateStr = $date . " " . $this->getEngMonth ( $month ) . " " . $outYear;
				}
				break;
		}
		return $dateStr;
	}
	
	/**
	 * แปลงวันเดือนปีปกติไปเป็น YYYYMMDD
	 *
	 * @param string $str
	 * @param int $mode
	 * @return string
	 */
	public function parseDateStrToSLCDateFormat($str, $mode = 1) {
		global $config;
		$dateArray = split ( " ", $str );
		//var_dump($dateArray);
		$date = $dateArray [0];
		$monthStr = $dateArray [1];
		$yearStr = $dateArray [2];
		//$config ['datemode'] = 'B';
		if ($config ['datemode'] == 'B') {
			$month = str_pad ( $this->getThaiMonthNo ( $monthStr, $mode ), 2, "0", STR_PAD_LEFT );
			$year = $yearStr - 543;
		} else {
			//$month = $this->getMonthNo ( $monthStr, $mode );
			$month = str_pad ( $this->getMonthNo ( $monthStr, $mode ), 2, "0", STR_PAD_LEFT );
			$year = $yearStr;
		}
		$dateStr = trim ( $year . $month . $date );
		//die($dateStr);
		return $dateStr;
	}
	
	/**
	 * แปลงเป็นชื่อตาม Config
	 *
	 * @param int $uid
	 * @param int $roleID
	 * @param int $orgID
	 * @return string
	 */
	public function parseFullname($uid, $roleID, $orgID) {
		global $config;
		
		if ($config ['registerShowOrgOnly']) {
			$organize = new OrganizeEntity ( );
			$organize->Load ( "f_org_id = '{$orgID}'" );
			$parsedName = $organize->f_org_name;
		} else {
			$organize = new OrganizeEntity ( );
			$organize->Load ( "f_org_id = '{$orgID}'" );
			$parsedName = $organize->f_org_name;
			if ($uid != 0) {
				$account = new AccountEntity ( );
				$account->Load ( "f_acc_id = '{$uid}'" );
				$userName = $account->f_name . " " . $account->f_last_name;
				$parsedName .= "({$userName})";
			} else {
				if ($roleID != 0) {
					$role = new RoleEntity ( );
					$role->Load ( "f_role_id = '{$roleID}'" );
					$roleName = $role->f_role_name;
					$parsedName .= "({$roleName})";
				}
			}
		}
		return $parsedName;
	}
	
	/**
	 * ขอ Theme Config
	 *
	 * @param string $theme
	 * @return int
	 */
	public function getTheme($theme) {
		global $availableTheme;
		global $config;
		
		if (in_array ( $theme, $availableTheme ) && $theme != 'default') {
			$theme = '/' . $config ['runtimeJS'] . '/ext' . $config ['libVersion'] ['ext'] . '/resources/css/xtheme-' . $theme . '.css';
		} else {
			$theme = "";
		}
		return $theme;
	}
	
	/**
	 * ขอ IP Address
	 *
	 * @return string
	 */
	public function getIPAddress() {
		if (getenv ( 'HTTP_X_FORWARDED_FOR' )) {
			$ip = getenv ( 'HTTP_X_FORWARDED_FOR' );
		} else {
			$ip = getenv ( 'REMOTE_ADDR' );
		}
		return $ip;
	}
	
	/**
	 * แสดง Bubble Message
	 *
	 * @param string $title
	 * @param string $message
	 * @return string
	 */
	public function bubbleMsg($title, $message) {
		global $config;
		global $debugMode;
		if ($config ['showBubbleMsg']) {
			if ($debugMode) {
				Logger::log ( 0, 0, $message, true, false );
			}
			return "Ext.ECM.msg('{$title}','{$message}');";
		
		} else {
			if ($debugMode) {
				Logger::log ( 0, 0, $message, true, false );
			}
			return "";
		}
	}
	
	/**
	 * สร้าง Folder Temp สำหรับ User
	 *
	 * @param int $accID
	 */
	public function createUserTemp($accID) {
		global $config;
		
		// Temp Path Create
		$userTempPath = $config ['tempPath'] . "/{$accID}";
		
		// Check for user temporary path existence
		if (! file_exists ( $userTempPath )) {
			@mkdir ( $userTempPath );
		}
		
		// Check for user temporary VIEW path existence
		if (! file_exists ( $userTempPath . "/view" )) {
			@mkdir ( $userTempPath . "/view" );
		}
		
		// Check for user temporary CREATE path existence
		if (! file_exists ( $userTempPath . "/create" )) {
			@mkdir ( $userTempPath . "/create" );
		}
		
		// Check for user temporary SCAN path existence
		if (! file_exists ( $userTempPath . "/scan" )) {
			@mkdir ( $userTempPath . "/scan" );
		}
	}
	
	/**
	 * สร้าง Folder Temp สำหรับการ Scan
	 *
	 * @param int $accountID
	 */
	public function createScanTemp($accountID) {
		global $config;
		$scanTempPath = $config ['scanPath'] . "$accountID";
		if (! file_exists ( $scanTempPath )) {
			mkdir ( $scanTempPath, 777 );
		}
	}
	
	/**
	 * เคลียร์ข้อมูลใน Temp
	 *
	 * @param int $accountID
	 */
	public function clearTempPath($accountID) {
		global $config;
		$userTempPath = $config ['tempPath'] . "{$accountID}";
		clearFolder ( $userTempPath . "/view" );
		clearFolder ( $userTempPath . "/create" );
		@unlink ( $userTempPath . "/view" );
		@unlink ( $userTempPath . "/create" );
		@unlink ( $userTempPath );
	}
	
	/**
	 * เคลียร์ข้อมูลใน Folder
	 *
	 * @param string $path
	 */
	public function clearFolder($path) {
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
	
	/**
	 * ขอนามสกุลของไฟล์
	 *
	 * @param string $filename
	 * @return string
	 */
	public function getFileExtension($filename) {
		$arrayName = explode ( ".", $filename );
		$count = count ( $arrayName );
		return $arrayName [$count - 1];
	}
	
	/**
	 * ขอ mimetype ของนามสกุล
	 *
	 * @param string $ext
	 * @return string
	 */
	public function getMimeType($ext) {
		switch (strtolower ( $ext )) {
			case 'pdf' :
				return 'application/pdf';
				break;
			case 'jpg' :
				return 'image/jpg';
				break;
			case 'tif' :
			case 'tiff' :
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
			case 'docx' :
				return 'application/word';
				break;
			case 'xls' :
			case 'xlsx' :
				return 'application/xls';
				break;
			case 'ppt' :
			case 'pptx' :
				return 'application/powerpoint';
				break;
			case 'mdb' :
				return 'application/msaccess';
				break;
			case 'pdf' :
				return 'application/pdf';
				break;
			default :
				return 'application/octet-stream';
				break;
		}
	}
	
	/**
	 * ขอ Icon URL ตาม Mimetype
	 *
	 * @param string $mimetype
	 * @return string
	 */
	public function getIconURLByMimeType($mimetype) {
		switch ($mimetype) {
			case 'application/pdf' :
				return "images/filetype/pdf.jpg";
				break;
			case 'image/jpg' :
				return "images/filetype/txt.jpg";
				break;
			case 'image/tif' :
				return "images/filetype/txt.jpg";
				break;
			case 'image/png' :
				return "images/filetype/txt.jpg";
				break;
			case 'image/gif' :
				return "images/filetype/txt.jpg";
				break;
			case 'image/bmp' :
				return "images/filetype/txt.jpg";
				break;
			case 'application/word' :
				return "images/filetype/word.jpg";
				break;
			case 'application/xls' :
				return "images/filetype/xls.jpg";
				break;
			case 'application/powerpoint' :
				return "images/filetype/ppt.jpg";
				break;
			case 'application/msaccess' :
				return "images/filetype/txt.jpg";
				break;
			case 'application/pdf' :
				return "images/filetype/pdf.jpg";
				break;
			default :
				return "images/filetype/txt.jpg";
				break;
		}
	}
	
	/**
	 * ขอ Icon ขนาดเล็กตาม Mimetypw
	 *
	 * @param string $mimetype
	 * @return string
	 */
	public function getSmalIconURLByMimeType($mimetype) {
		global $config;
		switch ($mimetype) {
			case 'application/pdf' :
				return "/{$config['appName']}/images/filetype/small/pdf.gif";
				break;
			case 'image/jpg' :
				return "/{$config['appName']}/images/filetype/small/jpg.gif";
				break;
			case 'image/tif' :
				return "/{$config['appName']}/images/filetype/small/tif.gif";
				break;
			case 'image/png' :
				return "/{$config['appName']}/images/filetype/small/txt.gif";
				break;
			case 'image/gif' :
				return "/{$config['appName']}/images/filetype/small/gif.gif";
				break;
			case 'image/bmp' :
				return "/{$config['appName']}/images/filetype/small//small.gif";
				break;
			case 'application/word' :
				return "/{$config['appName']}/images/filetype/small/doc.gif";
				break;
			case 'application/xls' :
				return "/{$config['appName']}/images/filetype/small/xls.gif";
				break;
			case 'application/powerpoint' :
				return "/{$config['appName']}/images/filetype/small/ppt.gif";
				break;
			case 'application/msaccess' :
				return "/{$config['appName']}/images/filetype/small/txt.gif";
				break;
			default :
				return "/{$config['appName']}/images/filetype/small/txt.gif";
				break;
		}
	}
	
	/**
	 * ทำ Force Download
	 *
	 * @param string $data
	 * @param string $name
	 * @param string $mimetype
	 * @param int $filesize
	 */
	function force_download($data, $name, $mimetype = '', $filesize = false) {
		// File size not set? 
		if ($filesize == false or ! is_numeric ( $filesize )) {
			$filesize = strlen ( $data );
		}
		//die($mimetype);
		// Mimetype not set? 
		if (empty ( $mimetype )) {
			$mimetype = 'application/octet-stream';
		}
		
		// Make sure there's not anything else left 
		$this->ob_clean_all ();
		
		// Start sending headers 
		header ( "Pragma: public" ); // required 
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Cache-Control: private", false ); // required for certain browsers 
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Content-Type: " . $mimetype );
		header ( "Content-Length: " . $filesize );
		header ( "Content-Disposition: attachment; filename=\"" . $name . "\";" );
		// Send data 
		echo $data;
		//echo "<script>top.window.close();</script>";
		die ();
	}
	
	/**
	 * Clean Output Buffering
	 *
	 * @return boolean
	 */
	function ob_clean_all() {
		$ob_active = ob_get_length () !== false;
		while ( $ob_active ) {
			ob_end_clean ();
			$ob_active = ob_get_length () !== false;
		}
		return true;
	}
	
	/**
	 * ทำการ Execute cURL
	 *
	 * @param string $url
	 * @return array
	 */
	function curl_string($url) {
		$ch = curl_init ();
		//curl_setopt ($ch, CURLOPT_PROXY, $proxy);
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_USERAGENT, "msie" );
		//curl_setopt ($ch, CURLOPT_COOKIEJAR, "c:\cookie.txt");
		//curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 120 );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		return $result;
	}
	
	/**
	 * ตรวจสอบว่าทำการกำหนด Filter ของทะเบียนไว้หรือไม่
	 *
	 * @param string $type
	 * @return boolean
	 */
	public function isDFTransFiltered($type) {
		if (! array_key_exists ( 'FilterRecord', $_SESSION )) {
			return false;
		}
		if (! array_key_exists ( $type, $_SESSION ['FilterRecord'] )) {
			return false;
		}
		if (array_key_exists ( 'filtered', $_SESSION ['FilterRecord'] [$type] )) {
			
			return $_SESSION ['FilterRecord'] [$type] ['filtered'];
		} else {
			return false;
		}
	}
	
	/**
	 * ส่งข้อความแจ้งเตือนทาง XMPP/Jabber
	 *
	 * @param string $userName
	 * @param string $message
	 */
	public function sendJabberNotifier($userName, $message) {
		global $config;
		if ($config ['xmpp'] ['enable']) {
			loadExternalLib('XMPPLib');
			error_reporting ( 0 );
			$printlog = False;
			$loglevel = LOGGING_INFO;
			$xmppConn = new XMPP ( $config ['xmpp'] ['host'], 5222, $config ['xmpp'] ['username'], $config ['xmpp'] ['password'], 'xmpphp', $config ['xmpp'] ['host'], $printlog, $loglevel );
			# the default, change to false if you don't have SSL extensions
			$xmppConn->use_encyption = false;
			$xmppConn->connect ();
			$xmppConn->processUntil ( 'session_start' );
			$messageUTF = UTFEncode ( $message );
			$xmppConn->message ( "{$userName}@{$config ['xmpp'] ['domain']}", $messageUTF, 'headline' );
			$xmppConn->disconnect ();
		}
	}
	
	/**
	 * การ Broadcast ทาง XMPP/Jabber
	 *
	 * @param string $message
	 */
	public function broadcastJabberMessage($message) {
		global $config;
		if ($config ['xmpp'] ['enable']) {
			loadExternalLib('XMPPLib');
			error_reporting ( 0 );
			$printlog = False;
			$loglevel = LOGGING_INFO;
			$xmppConn = new XMPP ( $config ['xmpp'] ['host'], 5222, $config ['xmpp'] ['username'], $config ['xmpp'] ['password'], 'xmpphp', $config ['xmpp'] ['host'], $printlog, $loglevel );
			# the default, change to false if you don't have SSL extensions
			$xmppConn->use_encyption = false;
			$xmppConn->connect ();
			$xmppConn->processUntil ( 'session_start' );
			$messageUTF = UTFEncode ( $message );
			$xmppConn->message ( "all@{$config ['xmpp'] ['broadcastServiceName']}", $messageUTF, 'headline' );
			$xmppConn->disconnect ();
		}
	}
	
	/**
	 * การทำ Psuedo Random
	 *
	 * @return string
	 */
	public function psuedoRandom() {
		$pr_bits = '';
		
		// Unix/Linux platform
		$fp = @fopen ( '/dev/urandom', 'rb' );
		if ($fp !== FALSE) {
			$pr_bits .= @fread ( $fp, 16 );
			@fclose ( $fp );
		}
		
		// MS-Windows platform
		if (@class_exists ( 'COM' )) {
			try {
				$CAPI_Util = new COM ( 'CAPICOM.Utilities.1' );
				$pr_bits .= $CAPI_Util->GetRandom ( 128, 0 );
				//if ($pr_bits) { $pr_bits = md5($pr_bits,TRUE); }
			} catch ( Exception $ex ) {
				echo 'Exception: ' . $ex->getMessage ();
			}
		}
		
		if (strlen ( $pr_bits ) < 16) {
			// do something to warn system owner that
		// pseudorandom generator is missing
		}
		return base64_decode ( $pr_bits );
	}
	
	/**
	 * การขอ Timestamp จาก Timestamp Server
	 *
	 * @return unknown
	 */
	public function getTimestamp() {
		global $config;
		return time ();
		
		// NTP client
		// Sends a message and reads the response
		require_once 'ntplite/NTPLite.php';
		
		// The server address
		$address = $config ['TimeServerIP'];
		$port = $config ['TimeServerPort'];
		
		// Opens the socket to the SNTP server, uses UDP datagrams
		$socket = @stream_socket_client ( "udp://$address:$port", $errno, $errstr );
		if (! $socket) {
			echo "Socket error $errno: $errstr\n";
			return - 1;
		}
		
		$NTP = new NTPLite ( false );
		
		// Fills in the message to send
		$NTP->leapIndicator = 0;
		$NTP->versionNumber = 3;
		$NTP->mode = 3;
		$NTP->stratum = 0;
		$NTP->pollInterval = 0;
		$NTP->precision = 0;
		$NTP->rootDelay = 0;
		$NTP->rootDispersion = 0;
		$NTP->referenceIdentifier = 0;
		
		// Timestamps
		$NTP->referenceTimestamp = 0;
		$NTP->originateTimestamp = 0;
		$NTP->receiveTimestamp = 0;
		$NTP->transmitTimestamp = 0;
		
		// Authentication
		$NTP->keyIdentifier = 0;
		$NTP->messageDigest = 0;
		
		// Displays the query message
		echo "Query:\n", $NTP, "\n";
		
		// Sends the query message
		$query = $NTP->writeMessage ();
		fwrite ( $socket, $query );
		
		// Tries to read the server response
		$response = fread ( $socket, 1500 );
		if ($NTP->readMessage ( $response )) {
			echo "Response:\n", $NTP;
			// Displays the server time
			$now = NTPLite::convertTsSntpToUnix ( $NTP->transmitTimestamp );
			// SNTP uses UTC timestamps
			echo "\nThe server time (UTC) is: " . gmdate ( 'l j F Y, H:i:s e', $now );
			echo "\nSet your local clock to:  " . date ( 'l j F Y, H:i:s e', $now ) . "\n";
			
			echo "<br/>current timestamp = " . $NTP->transmitTimestamp;
			echo "<br/>converted unix timestamp = " . NTPLite::convertTsSntpToUnix ( $NTP->transmitTimestamp );
			echo "<br/>unixtimestamp = " . time ();
		} else {
			echo "Failed to read server response\n";
		}
		
		fclose ( $socket );
		unset ( $NTP );
	}
	
	/**
	 * ขอรายการผู้ใช้ภายในหน่วยงานพร้อมรหัสตำแหน่ง
	 *
	 * @param string $orgID
	 * @return array
	 */
	public function getOrganizeMemberAccountIDWithRoleID($orgID) {
		//global $conn;
		//global $sessionMgr;
		
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Passport.Entity.php';
		//include_once 'Organize.Entity.php';
		
		$receiverArray = Array ();
		//ค้นหากลุ่มงานภายใต้หน่วยงาน และบันทึกรายชื่อที่อยู่ในหน่วยงาน
		$orgSearch = new OrganizeEntity ( );
		$orgSearchResult = $orgSearch->Find ( "f_org_type = 1 and f_org_pid = '{$orgID}'" );
		foreach ( $orgSearchResult as $org ) {
			$roleSearch = new RoleEntity ( );
			$roleSearchResult = $roleSearch->Find ( "f_org_id = '{$org->f_org_id}'" );
			foreach ( $roleSearchResult as $role ) {
				$passportSearch = new PassportEntity ( );
				$passportSearchResult = $passportSearch->Find ( "f_role_id = '{$role->f_role_id}'" );
				foreach ( $passportSearchResult as $passport ) {
					$index = "{$passport->f_acc_id}_{$role->f_role_id}";
					if (! array_key_exists ( $index, $receiverArray )) {
						$receiverArray [$index] = Array ('role' => $role->f_role_id, 'accID' => $passport->f_acc_id );
					}
				}
				unset ( $passportSearchs );
				unset ( $passportSearchResult );
			}
			unset ( $roleSearch );
			unset ( $roleSearchResult );
		}
		unset ( $orgSearch );
		unset ( $orgSearchResult );
		
		//ค้นหาตำแหน่งในหน่วยงาน
		$roleSearch = new RoleEntity ( );
		$roleSearchResult = $roleSearch->Find ( "f_org_id = '{$orgID}'" );
		foreach ( $roleSearchResult as $role ) {
			$passportSearch = new PassportEntity ( );
			$passportSearchResult = $passportSearch->Find ( "f_role_id = '{$role->f_role_id}'" );
			foreach ( $passportSearchResult as $passport ) {
				$index = "{$passport->f_acc_id}_{$role->f_role_id}";
				if (! array_key_exists ( $index, $receiverArray )) {
					$receiverArray [$index] = Array ('role' => $role->f_role_id, 'accID' => $passport->f_acc_id );
				}
			}
		}
		
		return $receiverArray;
	}
	
	/**
	 * ขอรายชื่อผู้ใช้ในหน่วยงาน
	 *
	 * @param string $orgID
	 * @return array
	 */
	public function getOrganizeMember($orgID) {
		//global $conn;
		//global $sessionMgr;
		
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Passport.Entity.php';
		//include_once 'Organize.Entity.php';
		
		$receiverArray = Array ();
		//ค้นหากลุ่มงานภายใต้หน่วยงาน และบันทึกรายชื่อที่อยู่ในหน่วยงาน
		$orgSearch = new OrganizeEntity ( );
		$orgSearchResult = $orgSearch->Find ( "f_org_type = 1 and f_org_pid = '{$orgID}'" );
		foreach ( $orgSearchResult as $org ) {
			$roleSearch = new RoleEntity ( );
			$roleSearchResult = $roleSearch->Find ( "f_org_id = '{$org->f_org_id}'" );
			foreach ( $roleSearchResult as $role ) {
				$passportSearch = new PassportEntity ( );
				$passportSearchResult = $passportSearch->Find ( "f_role_id = '{$role->f_role_id}'" );
				foreach ( $passportSearchResult as $passport ) {
					if (! in_array ( $passport->f_acc_id, $receiverArray )) {
						$receiverArray [] = $passport->f_acc_id;
					}
				}
				unset ( $passportSearchs );
				unset ( $passportSearchResult );
			}
			unset ( $roleSearch );
			unset ( $roleSearchResult );
		}
		unset ( $orgSearch );
		unset ( $orgSearchResult );
		//ค้นหาตำแหน่งในหน่วยงาน
		$roleSearch = new RoleEntity ( );
		$roleSearchResult = $roleSearch->Find ( "f_org_id = '{$orgID}'" );
		foreach ( $roleSearchResult as $role ) {
			$passportSearch = new PassportEntity ( );
			$passportSearchResult = $passportSearch->Find ( "f_role_id = '{$role->f_role_id}'" );
			foreach ( $passportSearchResult as $passport ) {
				if (! in_array ( $passport->f_acc_id, $receiverArray )) {
					$receiverArray [] = $passport->f_acc_id;
				}
			}
		}
		return $receiverArray;
	}
	
	/**
	 * ทำการ Redirect
	 *
	 * @param string $subURL
	 * @param boolean $ssl
	 */
	public function redirect($subURL, $ssl = false) {
		global $config;
		if ($ssl) {
			$protocol = "https";
		} else {
			$protocol = "http";
		}
		if ($config ['clusterMode']) {
			$serverName = $config ['clusterName'];
		} else {
			if ($config ['appPort'] != 80) {
				$serverName = $_SERVER ['SERVER_NAME'] . ":" . $config ['appPort'];
			} else {
				$serverName = $_SERVER ['SERVER_NAME'];
			}
		}
		
		$urlRedirect = "{$protocol}://{$serverName}/{$config['appName']}{$subURL}";
		header ( "Location: {$urlRedirect}" );
	}
	
	/**
	 * วันที่ 1 เดือนมกราคม ของปีที่ระบุวันที่
	 *
	 * @param string $date
	 * @return string $result
	 */
	function firstDateOfYear($date) {
		global $config;
		$arrDate = explode ( ' ', $date );
		$arrDate [0] = '01';
		$arrDate [1] = ($config ['datemode'] == 'B') ? $this->shortThaiMonth[1] : $this->shortEngMonth[1];
		return implode ( ' ', $arrDate );
	}

	/**
	 * แปลงวันที่เป็น Timestamp
	 *
	 * @param string $date
	 * @return int
	 */
	function dateToStamp($date) {
		global $config;
		
		$arrDate = explode ( ' ', $date );
		
		$day = $arrDate [0];
		$month = $this->getThaiMonthNo ( $arrDate [1], 1 );
		$year = ($config ['datemode'] == 'B') ? ($arrDate [2] - 543) : $arrDate [2];
		//Logger::dump("xxx",$arrDate);
		//Logger::debug("{$day} {$month} {$year}");
		$timestamp = mktime ( 0, 0, 0, $month, $day, $year, 0 );
		return $timestamp;
	}
	
	/**
	 * ขอ JDBC Driver Parameter สำหรับ iReport
	 *
	 * @return string
	 */
	function getJDBCDriverParam( ) {
		
		global $config;
		
		switch ($config ['db'] ['control'] ['type']) {
			case 'mssql' :
				$classForname = "net.sourceforge.jtds.jdbc.Driver";
				$driverManager = "java.sql.DriverManager";
				$JDBCUrl = "jdbc:jtds:sqlserver://{$config ['db'] ['control'] ['host']}/{$config ['db'] ['control'] ['database']};user={$config ['db'] ['control'] ['uid']};password={$config ['db'] ['control'] ['pwd']}";
				break;
			
			case 'oci8' :
				$classForname = "oracle.jdbc.driver.OracleDriver";
				$driverManager = "java.sql.DriverManager";
				$JDBCUrl = "jdbc:oracle:thin:@{$config ['db'] ['control'] ['host']}:1521:{$config ['db'] ['control'] ['database']}";
				$JDBCUrl = $config['jdbcURL'];
				break;
		}
		
		$response = Array ('class' => $classForname, 'driver' => $driverManager, 'url' => $JDBCUrl );
		return $response;		
	}
	
	/**
	 * การเพิ่มวัน
	 *
	 * @param int $interval
	 * @param int $number
	 * @param string $date
	 * @return string
	 */
	function dateAdd($interval, $number, $date) {
		
		$dateTimeArray = getdate ( $date );
		$hours = $dateTimeArray ['hours'];
		$minutes = $dateTimeArray ['minutes'];
		$seconds = $dateTimeArray ['seconds'];
		$month = $dateTimeArray ['mon'];
		$day = $dateTimeArray ['mday'];
		$year = $dateTimeArray ['year'];
		
		switch ($interval) {
			
			case 'yyyy' :
				$year += $number;
				break;
			case 'q' :
				$year += ($number * 3);
				break;
			case 'm' :
				$month += $number;
				break;
			case 'y' :
			case 'd' :
			case 'w' :
				$day += $number;
				break;
			case 'ww' :
				$day += ($number * 7);
				break;
			case 'h' :
				$hours += $number;
				break;
			case 'n' :
				$minutes += $number;
				break;
			case 's' :
				$seconds += $number;
				break;
		}
		
		$timestamp = mktime ( $hours, $minutes, $seconds, $month, $day, $year );
		return $timestamp;
	}
	
	/**
	 * ขอผู้ใช้ภายใต้ตำแหน่ง
	 *
	 * @param int $roleID
	 * @param boolean $defaultOnly
	 * @return array
	 */
	function getRoleMembers($roleID, $defaultOnly = false) {
		global $conn;
		if ($defaultOnly) {
			$appendQuery = " and f_default_role=1";
		} else {
			$appendQuery = "";
		}
		$sql = "select f_acc_id from tbl_passport where f_role_id = '{$roleID}' {$appendQuery}";
		$rs = $conn->Execute ( $sql );
		
		$acc = Array ();
		while ( $tmp = $rs->FetchNextObject () ) {
			$acc [] = $tmp->F_ACC_ID;
		}
		
		return $acc;
	}

}