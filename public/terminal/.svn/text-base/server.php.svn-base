<?php
require_once('../../application/maintenance.php');

if (! empty ( $_POST ['input'] )) {
	$st = $_POST ['input'];
	
	switch ($st) {
		case 'maintenance on':
			$fp = fopen(realpath('../../application/maintenance.php'),'w+');
			echo "Maintenance mode ENABLED";
			fseek($fp,0);
			fwrite($fp,"<?php\r\n");
			fwrite($fp,"define('ECM_MAINTENANCE',1);\r\n");
			fclose($fp);
			break;
		case 'maintenance off':
			$fp = fopen(realpath('../../application/maintenance.php'),'w+');
			echo "Maintenance mode DISABLED";
			fseek($fp,0);
			fwrite($fp,"<?php\r\n");
			fwrite($fp,"define('ECM_MAINTENANCE',0);\r\n");
			fclose($fp);
			break;
		case 'login' :
			$_SESSION ['term'] ['loggedIn'] = true;
			echo "login successfully";
			break;
		case 'logout' :
			$_SESSION ['term'] ['loggedIn'] = false;
			echo "logout successfully";
			break;
		case 'status' :
			if ($_SESSION ['term'] ['loggedIn']) {
				echo "<font color=\"green\">Session already logged in</font>\r\n";
			} else {
				echo "<font color=\"red\">Session NOT loged in</font>\r\n";
			}
			if(ECM_MAINTENANCE) {
				echo "<font color=\"red\">Maintenance mode is ENABLED</font>\r\n";
			} else {
				echo "<font color=\"green\">Maintenance mode is DISABLED</font>\r\n";
			}
			if (array_key_exists('webEnabled',$_SESSION) && $_SESSION ['webEnabled']) {
				echo "<font color=\"green\">Web session is enabled for maintenance mode</font>";
			} else {
				echo "<font color=\"red\">Web session is disabled for maintenance mode</font>";
			}
			break;
		case 'web session start' :
			$_SESSION['webEnabled'] = true;
			echo "Web Session ENABLED";
			break;
		case 'web session stop' :
			echo "Web Session DISBLED";
			$_SESSION['webEnabled'] = false;
			break;
		case 'session id' :
			echo "Session ID : " . session_id ();
			break;
		case 'numbers' :
			print join ( "\n", array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ) );
			break;
		case 'sleep' :
			print "sleeping...\n";
			sleep ( 2 );
			print "slept!";
			break;
		case 'error' :
			header ( 'HTTP/1.0 Internal Server Error' );
			print 'AAAAAAAAAHHHHHHHHHH!!! That hurt!!!';
			break;
		case 'setup db' :
			echo "Please specify db type :";
			break;
		case 'help' :
			echo <<<HELP
Benefit&copy;ECM Administrator Terminal version 0.1.2

Available commands:
	numbers		Print numbers from 1..10
	sleep		Sleep for 2 seconds
	error		Return a 500 Server Error
	help		Print this help message
	
HELP;
			break;
		case 'reverse' :
		default :
			print "Unknown command type help for list of commands";
		//print strrev ( $_POST ['input'] );
	}
} else {
	print 'i need some input';
}
print "\n";