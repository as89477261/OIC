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
		set_time_limit ( 0 );
		
		$org = new OrganizeEntity();
		$org->Load("f_org_id = '{$sessionMgr->getCurrentOrgID()}'");

		$defaultYear = $sessionMgr->getCurrentYear();
		if ($config ['datemode'] == 'B') {
			$defaultYear += 543;
		}

		$fromTimestamp = $util->dateToStamp ( $_POST ['dateSummaryFrom'] );
		$toTimestamp = $util->dateToStamp ( $_POST ['dateSummaryTo'] )+86399;
		/*
			get year from parameter date
		*/
		if ( trim($_POST ['dateSummaryFrom'])  != '' ) {
			$yearFrom = substr( $_POST ['dateSummaryFrom'] ,-4 );
		}
		if ( trim($_POST ['dateSummaryTo'])  != '' ) {
			$yearTo = substr( $_POST ['dateSummaryTo'] ,-4 );
		}
		if ($config ['datemode'] == 'B') {
			$yearFrom = $yearFrom - 543;
			$yearTo = $yearTo - 543;
		} else {
			
		}

		/**/
		
		$dateFrom = $fromTimestamp;
		$dateTo = $toTimestamp;
		$dateFromTxt = $_POST['dateSummaryFrom'];
		$dateToTxt = $_POST['dateSummaryTo'];
		
		$reportParam = $util->getJDBCDriverParam ();
		
		//ทะเบียนรับภายใน
		$sqlRecvT1 = "
		select count(*) as COUNT_EXP 
		from tbl_trans_df_recv a
		where a.f_recv_type = 1
		and a.f_show_in_reg_book = 1
        and a.f_recv_stamp >= {$dateFrom} 
		and a.f_recv_stamp <= {$dateTo}
       	and a.f_accept_org_id = {$org->f_org_id} 
		and a.f_recv_year >= {$yearFrom}
		and a.f_recv_year <= {$yearTo}
		";
		
		$rsRecvT1 = $conn->Execute($sqlRecvT1);
		$tmpRecvT1 = $rsRecvT1->FetchNextObject();
		//$params->put ( "recv_t1", ( string ) $tmpRecvT1->COUNT_EXP );
		
		//ทะเบียนรับภายนอก
		
		//echo "RECV_T2<br/>";
		$sqlRecvT2 = "select count(*) as COUNT_EXP 
		from tbl_trans_df_recv a
		where a.f_recv_type = 2
		 and a.f_show_in_reg_book = 1
        and a.f_recv_stamp >= {$dateFrom} 
		and a.f_recv_stamp <= {$dateTo}
		and  a.f_accept_org_id = {$org->f_org_id} 
		and a.f_recv_year >= {$yearFrom}
		and a.f_recv_year <= {$yearTo}		
		";
		
		$rsRecvT2 = $conn->Execute($sqlRecvT2);
		$tmpRecvT2 = $rsRecvT2->FetchNextObject();
		//$params->put ( "recv_t2", ( string ) $tmpRecvT2->COUNT_EXP );
		
		//ทะเบียนกลางรับเข้า

		//echo "RECV_T3<br/>";
		$sqlRecvT3 = "SELECT count(*) as COUNT_EXP 
		from tbl_trans_df_recv a 
/*			,tbl_trans_master_df b
			,tbl_doc_main c
			,tbl_account d  */
		WHERE a.f_recv_type = 3
		and a.f_show_in_reg_book = 1
/*		and a.f_recv_trans_main_id = b.f_trans_main_id
		and b.f_doc_id = c.f_doc_id 
		and a.f_receiver_uid = d.f_acc_id */
        and a.f_recv_stamp >= {$dateFrom} 
		and a.f_recv_stamp <= {$dateTo}
		and  a.f_accept_org_id = {$org->f_org_id}
		and a.f_recv_year >= {$yearFrom}
		and a.f_recv_year <= {$yearTo}
/*		GROUP BY c.f_doc_id */
		group by a.f_recv_trans_main_id
		";
		
		$rsRecvT3 = $conn->Execute($sqlRecvT3);
		if ( $rsRecvT3 ) {
			$tmpRecvT3 = $rsRecvT3->RecordCount();
		} else {
			$tmpRecvT3 = 0;
		}
		//$params->put ( "recv_t3", ( string ) $tmpRecvT3 );

		//ทะเบียนรับ (เวียน)
		
		$sqlRecvT4 = "select count(*) as COUNT_EXP 
		from tbl_trans_df_recv a
/*			,tbl_trans_master_df b
			,tbl_doc_main c */
		where  a.f_recv_type = 5
		and a.f_show_in_reg_book = 1
/*		and a.f_recv_trans_main_id = b.f_trans_main_id
		and b.f_doc_id = c.f_doc_id  */
        and a.f_recv_stamp >= {$dateFrom} 
		and a.f_recv_stamp <= {$dateTo}
		and  a.f_accept_org_id = {$org->f_org_id}
		and a.f_recv_year >= {$yearFrom}
		and a.f_recv_year <= {$yearTo}		
/*		group by c.f_doc_id */
		group by a.f_recv_trans_main_id
		";
		
		$rsRecvT4 = $conn->Execute($sqlRecvT4);
		if ( $rsRecvT4 ) {
			$tmpRecvT4 = $rsRecvT4->RecordCount();
		} else {
			$tmpRecvT4 = 0;
		}
		//$params->put ( "recv_t4", ( string ) $tmpRecvT4 );

		
		//ทะเบียนเอกสารลับ (รับเข้า)
		$sqlRecvT5 = "select count(*) as COUNT_EXP 
		from tbl_trans_df_recv a
/*			,tbl_trans_master_df b
			,tbl_doc_main c */
		where a.f_recv_type = 4
		 and a.f_show_in_reg_book = 1
/*		 and a.f_recv_trans_main_id = b.f_trans_main_id
		 and b.f_doc_id = c.f_doc_id  */
        and a.f_recv_stamp >= {$dateFrom} 
		and a.f_recv_stamp <= {$dateTo}
		and  a.f_accept_org_id = {$org->f_org_id}
		and a.f_recv_year >= {$yearFrom}
		and a.f_recv_year <= {$yearTo}				
/*		group by c.f_doc_id */
		group by a.f_recv_trans_main_id
		";
		$rsRecvT5 = $conn->Execute($sqlRecvT5);
		if ( $rsRecvT5 ) {
			$tmpRecvT5 = $rsRecvT5->RecordCount();
		} else {
			$tmpRecvT5 = 0;
		}
		//$params->put ( "recv_t5", ( string ) $tmpRecvT5 );
		
		//ทะเบียนส่ง (ภายใน)
		$sqlSendT1 = "select count(*) as COUNT_EXP 
		from tbl_trans_df_send a
/*			,tbl_trans_master_df b
			,tbl_doc_main c  */
		where a.f_send_type = 1
		 and a.f_show_in_reg_book = 1
/*		 and a.f_send_trans_main_id = b.f_trans_main_id
		 and b.f_doc_id = c.f_doc_id */
        and a.f_send_stamp >= {$dateFrom} 
		and a.f_send_stamp <= {$dateTo}
		and a.f_sender_org_id = {$org->f_org_id}
		and a.f_send_year >= {$yearFrom}
		and a.f_send_year <= {$yearTo}				
/*		GROUP BY c.f_doc_id */
		group by a.f_send_trans_main_id
		";
		
		$rsSendT1 = $conn->Execute($sqlSendT1);
		if ( $rsSendT1 ) {
			$tmpSendT1 = $rsSendT1->RecordCount();
		} else {
			$tmpSendT1 = 0;
		}
		//$params->put ( "send_t1", ( string ) $tmpSendT1 );
		
		
		//ทะเบียนส่ง (ภายนอก)
		$sqlSendT2 = "SELECT count(*) as COUNT_EXP 
		FROM tbl_trans_df_send a
/*		,tbl_trans_master_df b
		,tbl_doc_main c  */
		WHERE a.f_send_type = 2
		 and a.f_show_in_reg_book = 1
/*		 and a.f_send_trans_main_id = b.f_trans_main_id
		 and b.f_doc_id = c.f_doc_id  */
        and a.f_send_stamp >= {$dateFrom} 
		and a.f_send_stamp <= {$dateTo}
		and a.f_send_year >= {$yearFrom}
		and a.f_send_year <= {$yearTo}				
		and  a.f_sender_org_id = {$org->f_org_id}  
/*		
		GROUP BY c.f_doc_id
*/		
		group by a.f_send_trans_main_id
		";
		
		$rsSendT2 = $conn->Execute($sqlSendT2);
		if ( $rsSendT2 ) {
			$tmpSendT2 = $rsSendT2->RecordCount();
		} else {
			$tmpSendT2 = 0;
		}
		//$params->put ( "send_t2", ( string ) $tmpSendT2 );
 
		//ทะเบียนกลาง (ส่งออก)
		$sqlSendT3 = "SELECT count(*) as COUNT_EXP 
		 FROM tbl_trans_df_send a
/*		 ,tbl_trans_master_df b
		 ,tbl_doc_main c  */
		 WHERE a.f_send_type = 3
		 and a.f_show_in_reg_book = 1
/*		 and a.f_send_trans_main_id = b.f_trans_main_id
		 and b.f_doc_id = c.f_doc_id */
        and a.f_send_stamp >= {$dateFrom} 
		and a.f_send_stamp <= {$dateTo}
		and  a.f_sender_org_id = {$org->f_org_id}
		and a.f_send_year >= {$yearFrom}
		and a.f_send_year <= {$yearTo}
/*		GROUP BY c.f_doc_id */
		group by a.f_send_trans_main_id
		";
		$rsSendT3 = $conn->Execute($sqlSendT3);
		if ( $rsSendT3 ) {
			$tmpSendT3 = $rsSendT3->RecordCount();
		} else {
			$tmpSendT3 = 0;
		}
		//$params->put ( "send_t3", ( string ) $tmpSendT3 );
 		 
		 
		 //ทะเบียนกลาง(ส่งภายใน)
		$sqlSendT4 = "SELECT count(*) as COUNT_EXP 
		FROM tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c
		WHERE a.f_send_type = 7
         and a.f_show_in_reg_book = 1
         and a.f_send_trans_main_id = b.f_trans_main_id
         and b.f_doc_id = c.f_doc_id
        and a.f_send_sys_stamp >= {$dateFrom} 
		and a.f_send_sys_stamp <= {$dateTo}
		/*and  a.f_sender_org_id = {$org->f_org_id} */
		GROUP BY c.f_doc_id
		";
		
		$rsSendT4 = $conn->Execute($sqlSendT4);
		if ( $rsSendT4 ) {
			$tmpSendT4 = $rsSendT4->RecordCount();
		} else {
			$tmpSendT4 = 0;
		}
		//$params->put ( "send_t4", ( string ) $tmpSendT4);
		 
		 
		 //ทะเบียนเอกสารลับ(ส่งออก)
		$sqlSendT5 = "SELECT count(*) as COUNT_EXP 
		FROM tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c 
		WHERE a.f_send_type = 4
		 and a.f_show_in_reg_book = 1
		 and a.f_send_trans_main_id = b.f_trans_main_id
		 and b.f_doc_id = c.f_doc_id
        and a.f_send_sys_stamp >= {$dateFrom} 
		and a.f_send_sys_stamp <= {$dateTo}
		and  a.f_sender_org_id = {$org->f_org_id}
		GROUP BY c.f_doc_id
		";
		$rsSendT5 = $conn->Execute($sqlSendT5);
		if ( $rsSendT5 ) {
			$tmpSendT5 = $rsSendT5->RecordCount();
		} else {
			$tmpSendT5 = 0;
		}
		//$params->put ( "send_t5", ( string ) $tmpSendT5 );
		
		
		//ทะเบียนส่ง (เวียน)
		$sqlSendT6 = "SELECT count(*) as COUNT_EXP 
		FROM tbl_trans_df_send a,tbl_trans_master_df b,tbl_doc_main c
		WHERE a.f_send_type = 5
			and a.f_show_in_reg_book = 1
			and a.f_send_trans_main_id = b.f_trans_main_id
			and b.f_doc_id = c.f_doc_id
			and a.f_send_sys_stamp >= {$dateFrom} 
			and a.f_send_sys_stamp <= {$dateTo}
			and  a.f_sender_org_id = {$org->f_org_id}
		GROUP BY c.f_doc_id
        ";
		
		$rsSendT6 = $conn->Execute($sqlSendT6);
		if ( $rsSendT6 ) {
			$tmpSendT6 = $rsSendT6->RecordCount();
		} else {
			$tmpSendT6 = 0;
		}
		//$params->put ( "send_t6", ( string ) $tmpSendT6 );
	

		/* SLC045 Add Announce Stats */
		//รายการ คำสั่ง/ประกาศ/ระเบียบ/อื่น ๆ
		
		$sqlSendAnnounce = "
			SELECT   a.f_announce_type, a.f_announce_cat_id, a.f_name,
					 (SELECT COUNT (0)
						/*FROM tbl_announce b, tbl_passport c, tbl_role d*/
						FROM tbl_announce b
					   WHERE b.f_announce_category = a.f_announce_cat_id
						 AND ( b.f_announce_stamp >= {$dateFrom}
						 AND b.f_announce_stamp <= {$dateTo} ) 
						 /*AND b.F_ANNOUNCE_ORG_ID = {$org->f_org_id}
						 AND b.f_announce_user = c.f_acc_id
						 AND c.f_default_role = 1
						 AND c.f_role_id = d.f_role_id
						 AND d.f_org_id =  {$org->f_org_id} */
						 AND b.f_year = {$defaultYear}
						 
						 ) AS count_exp
				FROM tbl_announce_category a
			GROUP BY a.f_announce_type, a.f_announce_cat_id, a.f_name
			ORDER BY a.f_announce_type, a.f_announce_cat_id
		";
		
		$commandCondition = false;
		$ann_label = array();
		$ann = array();
		$rsAnnounce = $conn->execute( $sqlSendAnnounce );
		if ( $rsAnnounce ) {
			//$i=1;
			foreach ( $rsAnnounce as $da ) {
				//$params->put ( "ann_label_{$i}", ( string ) iconv('tis-620','utf-8',$da['F_NAME'] ) );
				//$params->put ( "ann_{$i}", (string ) $da['COUNT_EXP'] );
				//echo $da['F_NAME'];
				$ann_label[] = $da['F_NAME'];
				$ann[] = $da['COUNT_EXP'];
				if($da['COUNT_EXP'] != 0){
					$commandCondition = true;
				}
				//if ( $i == 19 ) break;
				//$i++;
			}
		}

		$printDate = date('d/m/');
		$tdate = date('Y')+543;
		$printDate .= $tdate;
		
		
		$data = array("ลงรับหนังสือภายนอกทะเบียนกลาง",
			"ส่งออกหนังสือภายนอกทะเบียนกลาง (ส่งออก)",
			"ส่งออกหนังสือภายในเบียนกลาง (ส่งภายใน)",
			"ลงรับหนังสือภายในทะเบียนรับภายใน",
			"ลงรับหนังสือภายนอกทะเบียนรับภายนอก",
			"ลงรับหนังสือเวียน",
			"ส่งออกภายในทะเบียนส่งภายใน",
			"ส่งออกภายนอกทะเบียนส่งภายนอก",
			"ส่งออกหนังสือเวียน",
			"ส่งออกหนังสือทะเบียนหนังสือส่งออก (ลับ)",
			"ลงรับหนังสือทะเบียนลงรับ (ลับ)",
			"คำสั่ง/ประกาศ/ระเบียน/อื่นๆ"
			);
		$countData = array($tmpRecvT3,
			$tmpSendT3,
			$tmpSendT4,
			$tmpRecvT1->COUNT_EXP,
			$tmpRecvT2->COUNT_EXP,
			$tmpRecvT4,
			$tmpSendT1,
			$tmpSendT2,
			$tmpSendT6,
			$tmpSendT5,
			$tmpRecvT5
			);

		if(isset($_GET['viewexcel']) && $_GET['viewexcel'] == 1){
			$filename = "sarabanExcel.php";
		}
		else{
			$filename = "saraban.php";
		}
		

		$this->setupECMActionController ();
		$this->setECMViewModule ( 'report' );
		
		$this->ECMView->countData = $countData;
		$this->ECMView->data = $data;
		$this->ECMView->ann_label = $ann_label;
		$this->ECMView->ann = $ann;
		$this->ECMView->org = $org->f_org_name;
		$this->ECMView->dateFromTxt = $dateFromTxt;
		$this->ECMView->dateToTxt = $dateToTxt;
		$this->ECMView->printDate = $printDate;
		$this->ECMView->commandCondition = $commandCondition;
		
			
		$output = $this->ECMView->render ( $filename );
		echo $output;
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

