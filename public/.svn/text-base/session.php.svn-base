<?php
#ini_set('session.save_handler',"memcache");
#ini_set('session.save_path', "tcp://localhost:11211?persistent=1&weight=1&timeout=1&retry_interval=15#");
#if(ini_get('session.auto_start')==0) {
#	session_start();
#}
function checkSession() {
	if ($_SESSION ['loggedIn'] && array_key_exists ( 'accID', $_SESSION )) {
		return true;
		/*
		//$cacheID = "check_session_{$_SESSION['accID']}";
		Logger::debug ( 'check session cache ID: ' . $cacheID );
		if (! ($output = $checkSessionCache->load ( $cacheID ))) {
			Logger::debug ( 'cache Not hit' );
			$sqlCheckConcurrent = "select count(*) as COUNT_EXP from tbl_concurrent where f_acc_id = '{$_SESSION['accID']}'";
			
			$rsCheck = $conn->CacheExecute ( 60, $sqlCheckConcurrent );
			$tmpCheck = $rsCheck->FetchNextObject ();
			
			if ($tmpCheck->COUNT_EXP == 0) {
				unset ( $_SESSION ['loggedIn'] );
				unset ( $_SESSION ['accID'] );
				$checkSessionCache->save ( false, $cacheID );
				return false;
			} else {
				$checkSessionCache->save ( true, $cacheID );
				return true;
			}
		
		} else {
			Logger::debug ( 'cache hit' );
			return $output;
		}*/
	} else {
		return false;
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

checkSessionJSON();