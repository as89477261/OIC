<?php
require('../../application/bootstrap.php');
global $conn;

#session_start(); 
# session variables required for monitoring
//$conn = ADONewConnection($driver);
//$conn->Connect($server,$user,$pwd,$db);
define('ADODB_PERF_NO_RUN_SQL',1);

$perf =& NewPerfMonitor($conn);

$perf->UI($pollsecs=5);
