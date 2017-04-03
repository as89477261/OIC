<?php
/**
 * โปรแกรมรายงานระบบ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 *
 */
class SystemReportController extends ECMController {
	/**
	 * action /user-usage แสดงรายการเข้าใช้ระบบ
	 *
	 */
	public function userUsageAction() {
		global $config;
		global $util;
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$fromTimestamp = $util->dateToStamp ( $_POST ['userUsageFrom'] );
		$toTimestamp = $util->dateToStamp ( $_POST ['userUsageTo'] );
		
		$dateFrom = $fromTimestamp;
		$dateTo = $toTimestamp;
		
		$reportParam = $util->getJDBCDriverParam ();
		
		//$server = $_SERVER ['SERVER_NAME'];
		

		$compileManager = new JavaClass ( "net.sf.jasperreports.engine.JasperCompileManager" );
		try {
			
			$report = $compileManager->compileReport ( realpath ( $config ['reportPath'] . "{$config ['db'] ['control'] ['type']}/userUsageReport.jrxml" ) );
		} catch ( Exception $e1 ) {
			echo "Case 1";
			var_dump ( $e1->getTrace () );
		} catch ( java_RuntimeException $e ) {
			echo "Case 2";
			echo $e->getMessage ();
		}
		$fillManager = new JavaClass ( "net.sf.jasperreports.engine.JasperFillManager" );
		
		$params = new Java ( "java.util.HashMap" );
		
		$params->put ( "start", ( int ) $dateFrom );
		$params->put ( "stop", ( int ) $dateTo );
		
		$class = new JavaClass ( "java.lang.Class" );
		$class->forName ( "{$reportParam['class']}" );
		//$class->forName ( "net.sourceforge.jtds.jdbc.Driver" );
		//$class->forName ( "oracle.jdbc.driver.OracleDriver" );
		$driverManager = new JavaClass ( "{$reportParam['driver']}" );
		$conn = $driverManager->getConnection ( "{$reportParam['url']}", "{$config ['db'] ['control'] ['uid']}", "{$config ['db'] ['control'] ['pwd']}" );
		
		$jasperPrint = $fillManager->fillReport ( $report, $params, $conn );
		
		$filename = uniqid ( 'Report_' );
		$outputPath = $config ['reportTempPath'] . "app_log_{$filename}.pdf";
		
		$exportManager = new JavaClass ( "net.sf.jasperreports.engine.JasperExportManager" );
		$exportManager->exportReportToPdfFile ( $jasperPrint, $outputPath );
		$util->force_download ( file_get_contents ( $outputPath ), "UsageReport.pdf", 'application/pdf', filesize ( $outputPath ) );
	}
	
	/**
	 * action /admin-usage แสดงรายงานการใช้งานของผู้ดูแลระบบ
	 *
	 */
	public function adminUsageAction() {
		global $config;
		global $util;
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$fromTimestamp = $util->dateToStamp ( $_POST ['adminUsageFrom'] );
		$toTimestamp = $util->dateToStamp ( $_POST ['adminUsageTo'] );
		
		$dateFrom = $fromTimestamp;
		$dateTo = $toTimestamp;
		
		$reportParam = $util->getJDBCDriverParam ();
		
		//$server = $_SERVER ['SERVER_NAME'];
		

		$compileManager = new JavaClass ( "net.sf.jasperreports.engine.JasperCompileManager" );
		try {
			
			$report = $compileManager->compileReport ( realpath ( $config ['reportPath'] . "{$config ['db'] ['control'] ['type']}/adminUsageReport.jrxml" ) );
		} catch ( Exception $e1 ) {
			echo "Case 1";
			var_dump ( $e1->getTrace () );
		} catch ( java_RuntimeException $e ) {
			echo "Case 2";
			echo $e->getMessage ();
		}
		$fillManager = new JavaClass ( "net.sf.jasperreports.engine.JasperFillManager" );
		
		$params = new Java ( "java.util.HashMap" );
		
		$params->put ( "start", ( int ) $dateFrom );
		$params->put ( "stop", ( int ) $dateTo );
		
		$class = new JavaClass ( "java.lang.Class" );
		$class->forName ( "{$reportParam['class']}" );
		//$class->forName ( "net.sourceforge.jtds.jdbc.Driver" );
		//$class->forName ( "oracle.jdbc.driver.OracleDriver" );
		$driverManager = new JavaClass ( "{$reportParam['driver']}" );
		$conn = $driverManager->getConnection ( "{$reportParam['url']}", "{$config ['db'] ['control'] ['uid']}", "{$config ['db'] ['control'] ['pwd']}" );
		
		$jasperPrint = $fillManager->fillReport ( $report, $params, $conn );
		
		$filename = uniqid ( 'Report_' );
		$outputPath = $config ['reportTempPath'] . "app_log_{$filename}.pdf";
		
		$exportManager = new JavaClass ( "net.sf.jasperreports.engine.JasperExportManager" );
		$exportManager->exportReportToPdfFile ( $jasperPrint, $outputPath );
		$util->force_download ( file_get_contents ( $outputPath ), "AdminUsageReport.pdf", 'application/pdf', filesize ( $outputPath ) );
	}
	
	/**
	 * action /user-usage แสดงรายการเข้าใช้ระบบ
	 *
	 */
	public function dmsStatsAction() {
		global $config;
		global $util;
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$fromTimestamp = $util->dateToStamp ( $_POST ['userUsageFrom'] );
		$toTimestamp = $util->dateToStamp ( $_POST ['userUsageTo'] );
		
		$dateFrom = $fromTimestamp;
		$dateTo = $toTimestamp;
		
		$reportParam = $util->getJDBCDriverParam ();
		
		//$server = $_SERVER ['SERVER_NAME'];
		

		$compileManager = new JavaClass ( "net.sf.jasperreports.engine.JasperCompileManager" );
		try {
			
			$report = $compileManager->compileReport ( realpath ( $config ['reportPath'] . "{$config ['db'] ['control'] ['type']}/DMSStats.jrxml" ) );
		} catch ( Exception $e1 ) {
			echo "Case 1";
			var_dump ( $e1->getTrace () );
		} catch ( java_RuntimeException $e ) {
			echo "Case 2";
			echo $e->getMessage ();
		}
		$fillManager = new JavaClass ( "net.sf.jasperreports.engine.JasperFillManager" );
		
		$params = new Java ( "java.util.HashMap" );
		
		$params->put ( "start", ( int ) $dateFrom );
		$params->put ( "stop", ( int ) $dateTo );
		
		$class = new JavaClass ( "java.lang.Class" );
		$class->forName ( "{$reportParam['class']}" );
		//$class->forName ( "net.sourceforge.jtds.jdbc.Driver" );
		//$class->forName ( "oracle.jdbc.driver.OracleDriver" );
		$driverManager = new JavaClass ( "{$reportParam['driver']}" );
		$conn = $driverManager->getConnection ( "{$reportParam['url']}", "{$config ['db'] ['control'] ['uid']}", "{$config ['db'] ['control'] ['pwd']}" );
		$emptyDataSource = new Java ( "net.sf.jasperreports.engine.JREmptyDataSource" );
		$jasperPrint = $fillManager->fillReport ( $report, $params, $emptyDataSource );
		
		//$jasperPrint = $fillManager->fillReport ( $report, $params, $conn );
		

		$filename = uniqid ( 'Report_' );
		$outputPath = $config ['reportTempPath'] . "app_log_{$filename}.pdf";
		
		$exportManager = new JavaClass ( "net.sf.jasperreports.engine.JasperExportManager" );
		$exportManager->exportReportToPdfFile ( $jasperPrint, $outputPath );
		$util->force_download ( file_get_contents ( $outputPath ), "DMSCreateStats.pdf", 'application/pdf', filesize ( $outputPath ) );
	}
	
	/**
	 * action /user-usage แสดงรายการเข้าใช้ระบบ
	 *
	 */
	public function dmsCreateStatsAction() {
		global $config;
		global $util;
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$fromTimestamp = $util->dateToStamp ( $_POST ['userUsageFrom'] );
		$toTimestamp = $util->dateToStamp ( $_POST ['userUsageTo'] );
		
		$dateFrom = $fromTimestamp;
		$dateTo = $toTimestamp;
		
		$reportParam = $util->getJDBCDriverParam ();
		
		//$server = $_SERVER ['SERVER_NAME'];
		

		$compileManager = new JavaClass ( "net.sf.jasperreports.engine.JasperCompileManager" );
		try {
			
			$report = $compileManager->compileReport ( realpath ( $config ['reportPath'] . "{$config ['db'] ['control'] ['type']}/DMSCreateStats.jrxml" ) );
		} catch ( Exception $e1 ) {
			echo "Case 1";
			var_dump ( $e1->getTrace () );
		} catch ( java_RuntimeException $e ) {
			echo "Case 2";
			echo $e->getMessage ();
		}
		$fillManager = new JavaClass ( "net.sf.jasperreports.engine.JasperFillManager" );
		
		$params = new Java ( "java.util.HashMap" );
		
		$params->put ( "start", ( int ) $dateFrom );
		$params->put ( "stop", ( int ) $dateTo );
		
		$class = new JavaClass ( "java.lang.Class" );
		$class->forName ( "{$reportParam['class']}" );
		//$class->forName ( "net.sourceforge.jtds.jdbc.Driver" );
		//$class->forName ( "oracle.jdbc.driver.OracleDriver" );
		$driverManager = new JavaClass ( "{$reportParam['driver']}" );
		$conn = $driverManager->getConnection ( "{$reportParam['url']}", "{$config ['db'] ['control'] ['uid']}", "{$config ['db'] ['control'] ['pwd']}" );
		
		$jasperPrint = $fillManager->fillReport ( $report, $params, $conn );
		
		$filename = uniqid ( 'Report_' );
		$outputPath = $config ['reportTempPath'] . "app_log_{$filename}.pdf";
		
		$exportManager = new JavaClass ( "net.sf.jasperreports.engine.JasperExportManager" );
		$exportManager->exportReportToPdfFile ( $jasperPrint, $outputPath );
		$util->force_download ( file_get_contents ( $outputPath ), "DMSCreateStats.pdf", 'application/pdf', filesize ( $outputPath ) );
	}
	
	/**
	 * action /user-usage แสดงรายการเข้าใช้ระบบ
	 *
	 */
	public function docflowStatsAction() {
		global $config;
		global $util;
		global $conn;
		global $sessionMgr;
		
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$recv_t1 = 0;
		$recv_t2 = 0;
		$recv_t3 = 0;
		$recv_t4 = 0;
		$recv_t5 = 0;
		
		$send_t1 = 0;
		$send_t2 = 0;
		$send_t3 = 0;
		$send_t4 = 0;
		$send_t5 = 0;
		$send_t6 = 0;
		$send_t7 = 0;
		
		
		$org = new OrganizeEntity();
		$org->Load("f_org_id = '{$sessionMgr->getCurrentOrgID()}'");
		$fromTimestamp = $util->dateToStamp ( $_POST ['userUsageFrom'] );
		$toTimestamp = $util->dateToStamp ( $_POST ['userUsageTo'] );
		
		$dateFrom = $fromTimestamp;
		$dateTo = $toTimestamp;
		
		$dateFromTxt = $_POST['dateSummaryFrom'];
		$dateToTxt = $_POST['dateSummaryTo'];
		
		$reportParam = $util->getJDBCDriverParam ();
		
		//$server = $_SERVER ['SERVER_NAME'];
		

		$compileManager = new JavaClass ( "net.sf.jasperreports.engine.JasperCompileManager" );
		try {
			
			$report = $compileManager->compileReport ( realpath ( $config ['reportPath'] . "{$config ['db'] ['control'] ['type']}/SarabanSummaryReport.jrxml" ) );
		} catch ( Exception $e1 ) {
			echo "Case 1";
			var_dump ( $e1->getTrace () );
		} catch ( java_RuntimeException $e ) {
			echo "Case 2";
			echo $e->getMessage ();
		}
		$fillManager = new JavaClass ( "net.sf.jasperreports.engine.JasperFillManager" );
		
		
		//var_dump($_POST);
		//die();
		$params = new Java ( "java.util.HashMap" );
		
		$sqlRecvT1 = "select count(*) as COUNT_EXP from tbl_trans_df_recv
		where f_recv_type = 1
		and  f_accept_org_id = {$org->f_org_id}";
		$rsRecvT1 = $conn->Execute($sqlRecvT1);
		$tmpRecvT1 = $rsRecvT1->FetchNextObject();
		$params->put ( "recv_t1", ( string ) $tmpRecvT1->COUNT_EXP );
		
		$sqlRecvT2 = "select count(*) as COUNT_EXP from tbl_trans_df_recv
		where f_recv_type = 2
		and  f_accept_org_id = {$org->f_org_id}";
		$rsRecvT2 = $conn->Execute($sqlRecvT2);
		$tmpRecvT2 = $rsRecvT2->FetchNextObject();
		$params->put ( "recv_t2", ( string ) $tmpRecvT2->COUNT_EXP );
		
		$sqlRecvT3 = "select count(*) as COUNT_EXP from tbl_trans_df_recv
		where f_recv_type = 3
		and  f_accept_org_id = {$org->f_org_id}";
		$rsRecvT3 = $conn->Execute($sqlRecvT3);
		$tmpRecvT3 = $rsRecvT3->FetchNextObject();
		$params->put ( "recv_t3", ( string ) $tmpRecvT3->COUNT_EXP );
		
		$sqlRecvT4 = "select count(*) as COUNT_EXP from tbl_trans_df_recv
		where f_recv_type = 5
		and  f_accept_org_id = {$org->f_org_id}";
		$rsRecvT4 = $conn->Execute($sqlRecvT4);
		$tmpRecvT4 = $rsRecvT4->FetchNextObject();
		$params->put ( "recv_t4", ( string ) $tmpRecvT4->COUNT_EXP );
		
		$sqlRecvT5 = "select count(*) as COUNT_EXP from tbl_trans_df_recv
		where f_recv_type = 4
		and  f_accept_org_id = {$org->f_org_id}";
		$rsRecvT5 = $conn->Execute($sqlRecvT5);
		$tmpRecvT5 = $rsRecvT5->FetchNextObject();
		$params->put ( "recv_t5", ( string ) $tmpRecvT5->COUNT_EXP );
		
		$sqlSendT1 = "select count(*) as COUNT_EXP from tbl_trans_df_send
		where f_send_type = 1
		and  f_sender_org_id = {$org->f_org_id}";
		$rsSendT1 = $conn->Execute($sqlSendT1);
		$tmpSendT1 = $rsSendT1->FetchNextObject();
		$params->put ( "send_t1", ( string ) $tmpSendT1->COUNT_EXP );
		
		$sqlSendT2 = "select count(*) as COUNT_EXP from tbl_trans_df_send
		where f_send_type = 2
		and  f_sender_org_id = {$org->f_org_id}";
		$rsSendT2 = $conn->Execute($sqlSendT2);
		$tmpSendT2 = $rsSendT2->FetchNextObject();
		$params->put ( "send_t2", ( string ) $tmpSendT2->COUNT_EXP );
		
		$sqlSendT3 = "select count(*) as COUNT_EXP from tbl_trans_df_send
		where f_send_type = 3
		and  f_sender_org_id = {$org->f_org_id}";
		$rsRecvT3 = $conn->Execute($sqlSendT3);
		$tmpRecvT3 = $rsRecvT3->FetchNextObject();
		$params->put ( "send_t3", ( string ) $send_t3 );
		
		$sqlSendT4 = "select count(*) as COUNT_EXP from tbl_trans_df_send
		where f_send_type = 7
		and  f_sender_org_id = {$org->f_org_id}";
		$rsSendT4 = $conn->Execute($sqlSendT4);
		$tmpSendT4 = $rsSendT4->FetchNextObject();
		$params->put ( "send_t4", ( string ) $tmpSendT4->COUNT_EXP);
		
		$sqlSendT5 = "select count(*) as COUNT_EXP from tbl_trans_df_send
		where f_send_type = 4
		and  f_sender_org_id = {$org->f_org_id}";
		$rsSendT5 = $conn->Execute($sqlSendT5);
		$tmpSendT5 = $rsSendT5->FetchNextObject();
		$params->put ( "send_t5", ( string ) $tmpSendT5->COUNT_EXP );
		
		$sqlSendT6 = "select count(*) as COUNT_EXP from tbl_trans_df_send
		where f_send_type = 5
		and  f_sender_org_id = {$org->f_org_id}";
		$rsSendT6 = $conn->Execute($sqlSendT6);
		$tmpSendT6 = $rsSendT6->FetchNextObject();
		$params->put ( "send_t6", ( string ) $tmpSendT6->COUNT_EXP );
		
		$sqlSendT7 = "select count(*) as COUNT_EXP from tbl_trans_df_send
		where f_send_type = 6
		and  f_sender_org_id = {$org->f_org_id}";
		$rsSendT7 = $conn->Execute($sqlSendT7);
		$tmpSendT7 = $rsSendT7->FetchNextObject();
		$params->put ( "send_t7", ( string ) $tmpSendT7->COUNT_EXP );
		
		$params->put ( "org_name", ( string ) UTFEncode($org->f_org_name ));
		$params->put ( "date_txt_from", ( string ) UTFEncode($dateFromTxt ));
		$params->put ( "date_txt_to", ( string ) UTFEncode($dateToTxt ));
		$params->put ( "print_date", ( string ) date('d/m/Y') );
		$params->put ( "print_time", ( string ) date('h:i') );
		
		$class = new JavaClass ( "java.lang.Class" );
		$class->forName ( "{$reportParam['class']}" );
		//$class->forName ( "net.sourceforge.jtds.jdbc.Driver" );
		//$class->forName ( "oracle.jdbc.driver.OracleDriver" );
		$driverManager = new JavaClass ( "{$reportParam['driver']}" );
		$conn = $driverManager->getConnection ( "{$reportParam['url']}", "{$config ['db'] ['control'] ['uid']}", "{$config ['db'] ['control'] ['pwd']}" );
		
		//$jasperPrint = $fillManager->fillReport ( $report, $params, $conn );
		$emptyDataSource = new Java ( "net.sf.jasperreports.engine.JREmptyDataSource" );
		$jasperPrint = $fillManager->fillReport ( $report, $params, $emptyDataSource );
		
		$filename1 = "dfSum_".uniqid ( 'Report_' ).".pdf";
		$outputPath = $config ['reportTempPath'] .$filename1;
		
		$exportManager = new JavaClass ( "net.sf.jasperreports.engine.JasperExportManager" );
		$exportManager->exportReportToPdfFile ( $jasperPrint, $outputPath );
		
		sleep ( 3 );
		while ( ! file_exists ( $outputPath ) && ! is_file ( $outputPath ) && ! (filesize ( $outputPath ) > 0) ) {
			sleep ( 2 );
		}
		//$report1 = $outputPath;
		unset($compileManager);
		unset($fillManager);
		unset($report);
		unset($params);
		unset($conn);
		unset($jasperPrint);
		unset($emptyDataSource);
		unset($exportManager);
		
		//Report #2
		$compileManager = new JavaClass ( "net.sf.jasperreports.engine.JasperCompileManager" );
		try {
			
			$report = $compileManager->compileReport ( realpath ( $config ['reportPath'] . "{$config ['db'] ['control'] ['type']}/SarabanSummaryReport2.jrxml" ) );
		} catch ( Exception $e1 ) {
			echo "Case 1";
			var_dump ( $e1->getTrace () );
		} catch ( java_RuntimeException $e ) {
			echo "Case 2";
			echo $e->getMessage ();
		}
		$fillManager = new JavaClass ( "net.sf.jasperreports.engine.JasperFillManager" );
		
		$params = new Java ( "java.util.HashMap" );
		
		$params->put ( "recv_t1", ( string ) $recv_t1 );
		$params->put ( "recv_t2", ( string ) $recv_t2 );
		$params->put ( "recv_t3", ( string ) $recv_t3 );
		$params->put ( "recv_t4", ( string ) $recv_t4 );
		$params->put ( "recv_t5", ( string ) $recv_t5 );
		
		$params->put ( "send_t1", ( string ) $send_t1 );
		$params->put ( "send_t2", ( string ) $send_t2 );
		$params->put ( "send_t3", ( string ) $send_t3 );
		$params->put ( "send_t4", ( string ) $send_t4);
		$params->put ( "send_t5", ( string ) $send_t5 );
		$params->put ( "send_t6", ( string ) $send_t6 );
		
		$params->put ( "org_name", ( string ) UTFEncode($org->f_org_name ));
		$params->put ( "date_txt_from", ( string ) UTFEncode($dateFromTxt ));
		$params->put ( "date_txt_to", ( string ) UTFEncode($dateToTxt ));
		$params->put ( "print_date", ( string ) date('d/m/Y') );
		$params->put ( "print_time", ( string ) date('h:i') );
		
		$class = new JavaClass ( "java.lang.Class" );
		$class->forName ( "{$reportParam['class']}" );
		
		$driverManager = new JavaClass ( "{$reportParam['driver']}" );
		$conn = $driverManager->getConnection ( "{$reportParam['url']}", "{$config ['db'] ['control'] ['uid']}", "{$config ['db'] ['control'] ['pwd']}" );
		try {
			$jasperPrint = $fillManager->fillReport ( $report, $params, $conn );
		} catch (Exception $e) {
			$emptyDataSource = new Java ( "net.sf.jasperreports.engine.JREmptyDataSource" );
			$jasperPrint = $fillManager->fillReport ( $report, $params, $emptyDataSource );
			//die('error');
		}
		
		$filename2 = "dfSum2_".uniqid ( 'Report_' ).".pdf";
		$outputPath2 = $config ['reportTempPath'] . $filename2;
		
		$exportManager = new JavaClass ( "net.sf.jasperreports.engine.JasperExportManager" );
		$exportManager->exportReportToPdfFile ( $jasperPrint, $outputPath2 );
		
		sleep ( 3 );
		while ( ! file_exists ( $outputPath2 ) && ! is_file ( $outputPath2 ) && ! (filesize ( $outputPath2 ) > 0) ) {
			sleep ( 2 );
		}
		
		
		$mergePDF = new MergePDF($config ['reportTempPath']);
		$uuid = uniqid();
		$mergePDF->mergeFile(array($filename1,$filename2),"ReportSarabanSummary_{$uuid}.pdf");
	}
	
	/**
	 * action /user-usage แสดงรายการเข้าใช้ระบบ
	 *
	 */
	public function commandStatsAction() {
		global $config;
		global $sessionMgr;
		global $util;
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$fromTimestamp = $util->dateToStamp ( $_POST ['userUsageFrom'] );
		$toTimestamp = $util->dateToStamp ( $_POST ['userUsageTo'] );
		
		$dateFrom = $fromTimestamp;
		$dateTo = $toTimestamp;
		
		$reportParam = $util->getJDBCDriverParam ();
			//var_dump($_POST);
		if(strtolower(trim($_POST ['searchOrgAnnounceName'] )) == 'default') {
			$reportName = "CommandStats3";
		
		} else {
			$reportName = "CommandStats2";
		}
		//die();
		
		//$server = $_SERVER ['SERVER_NAME'];
		

		$compileManager = new JavaClass ( "net.sf.jasperreports.engine.JasperCompileManager" );
		try {
			
			$report = $compileManager->compileReport ( realpath ( $config ['reportPath'] . "{$config ['db'] ['control'] ['type']}/{$reportName}.jrxml" ) );
		} catch ( Exception $e1 ) {
			echo "Case 1";
			var_dump ( $e1->getTrace () );
		} catch ( java_RuntimeException $e ) {
			echo "Case 2";
			echo $e->getMessage ();
		}
		
		$fillManager = new JavaClass ( "net.sf.jasperreports.engine.JasperFillManager" );
		
		$params = new Java ( "java.util.HashMap" );
		switch ($_POST ['reportAnnouncType']) {
			default :
			case 'คำสั่ง' :
				$announceType = 0;
				break;
			default :
			case 'ระเบียบ' :
				$announceType = 1;
				break;
			default :
			case 'ประกาศ' :
				$announceType = 2;
				break;
			default :
			case 'ข้อบังคับ' :
				$announceType = 3;
				break;
			default :
			case 'อื่นๆ' :
				$announceType = 4;
				break;
		}
		
		$announceCat = new AnnounceCategoryEntity ( );
		$announceCat->Load ( "f_name = '{$_POST['reportAnnounSubType']}'" );
		
		$params->put ( "announce_type", ( string ) $announceType );
		$params->put ( "announce_cat", ( string ) $announceCat->f_announce_cat_id );
		$params->put ( "org_name", ( string ) UTFEncode ( trim($_POST ['searchOrgAnnounceName'] ) ));
		$params->put ( "date_txt_begin", ( string ) UTFEncode ( $_POST ['dateReportAnnounceFrom'] ) );
		$params->put ( "date_txt_end", ( string ) UTFEncode ( $_POST ['dateReportAnnounceTo'] ) );
		$params->put ( "date_start", ( string ) $util->dateToStamp ( $_POST ['dateReportAnnounceFrom'] ) );
		$params->put ( "date_stop", ( string ) $util->dateToStamp ( $_POST ['dateReportAnnounceTo'] ) );
		$params->put ( "print_date", ( string ) date ( 'd/m/Y' ) );
		$params->put ( "print_time", ( string ) date ( 'H:i' ) );
		
		
		
		$class = new JavaClass ( "java.lang.Class" );
		$class->forName ( "{$reportParam['class']}" );
		//$class->forName ( "net.sourceforge.jtds.jdbc.Driver" );
		//$class->forName ( "oracle.jdbc.driver.OracleDriver" );
		$driverManager = new JavaClass ( "{$reportParam['driver']}" );
		$conn = $driverManager->getConnection ( "{$reportParam['url']}", "{$config ['db'] ['control'] ['uid']}", "{$config ['db'] ['control'] ['pwd']}" );
		
		$jasperPrint = $fillManager->fillReport ( $report, $params, $conn );
		
		echo $announceType."<br/>";
		echo  $announceCat->f_announce_cat_id."<br/>";
		echo  $_POST ['searchOrgAnnounceName'] ."<br/>";
		echo  $_POST ['dateReportAnnounceFrom'] ."<br/>";
		echo  $_POST ['dateReportAnnounceTo']."<br/>";
		echo  $util->dateToStamp ( $_POST ['dateReportAnnounceFrom'] )."<br/>";
		echo  $util->dateToStamp ( $_POST ['dateReportAnnounceTo'] )."<br/>";
		echo  date ( 'd/m/Y' ) ."<br/>";
		echo  date ( 'H:i' ) ."<br/>";
		
		//die('connected');
		//die($reportParam['url']);
		try {
			$jasperPrint = $fillManager->fillReport ( $report, $params, $conn );
		} catch (Exception $e) {
			$emptyDataSource = new Java ( "net.sf.jasperreports.engine.JREmptyDataSource" );
			$jasperPrint = $fillManager->fillReport ( $report, $params, $emptyDataSource );
			die('error');
		}
		//die('connected');

		$filename = uniqid ( 'Report_' );
		$outputPath = $config ['reportTempPath'] . "app_log_{$filename}.pdf";
		
		$exportManager = new JavaClass ( "net.sf.jasperreports.engine.JasperExportManager" );
		$exportManager->exportReportToPdfFile ( $jasperPrint, $outputPath );
		sleep ( 3 );
		while ( ! file_exists ( $outputPath ) && ! is_file ( $outputPath ) && ! (filesize ( $outputPath ) > 0) ) {
			sleep ( 2 );
		}
		$util->force_download ( file_get_contents ( $outputPath ), "announceReport.pdf", 'application/pdf', filesize ( $outputPath ) );
	}
}

