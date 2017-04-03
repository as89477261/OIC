<?php

class JasperController extends ECMController {
	public function printAction() {
		global $sessionMgr;
		
		loadExternalLib('javaBridge');
		
		//var_dump($_GET);
		
		$docMain = new DocMainEntity();
		$docMain->Load("f_doc_id = {$_GET['docID']}");
		
		$formID = $docMain->f_form_id;
		
		$formStructureFinder = new FormStructureEntity();
		
		$formStructures = $formStructureFinder->Find("f_form_id = '{$formID}'");
//		foreach ($formStructures as $struct) {
//			var_dump($struct);
//			echo "<hr/>";
//		}
		
		$params = new Java("java.util.HashMap");
		//$params->put("text", "This is a test string xxxxx               ".iconv('TIS-620','UTF-8','มหศักดิ์ พิจิตรธรรม'));
		//$params->put("number", 3.00);
		//$params->put("date", convertValue("2007-12-31 0:0:0", "java.sql.Timestamp"));
		foreach ($formStructures as $struct) {
			//var_dump($struct);
			$docValue = new DocValueEntity();
			$docValue->Load("f_doc_id = '{$_GET['docID']}' and f_struct_id = '{$struct->f_struct_id}'");
			//echo $struct->f_struct_name.'='.$docValue->f_value;
			$params->put($struct->f_struct_name,iconv('TIS-620','UTF-8',$docValue->f_value));
			//$params->put($struct->f_struct_name,$docValue->f_value);
			//echo "<hr/>";
		}
		
		//die;
		
		$compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
		$report = $compileManager->compileReport(realpath("D:/project/iReports/Memo_Govt.jrxml"));
		
		$fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
		
		
		
		$emptyDataSource = new Java("net.sf.jasperreports.engine.JREmptyDataSource");
		$jasperPrint = $fillManager->fillReport($report, $params, $emptyDataSource);
		
		$id = uniqid('_');
		$outputPath = "d:/wwwroot/shared/formReport/output{$id}.pdf";
		$outputPath2 = "d:/wwwroot/shared/formReport/output{$id}.rtf";
		
		$exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
		$exportManager->exportReportToPdfFile($jasperPrint, $outputPath);
		
		$RTFExport = new Java("net.sf.jasperreports.engine.export.JRRtfExporter");
		$expParam = new JavaClass("net.sf.jasperreports.engine.JRExporterParameter");
		$RTFExport->setParameter($expParam->JASPER_PRINT,$jasperPrint);
		$RTFExport->setParameter($expParam->OUTPUT_FILE_NAME,$outputPath2);
		$RTFExport->exportReport();
		//rtfExporter.setParameter(JRExporterParameter.JASPER_PRINT, jasperPrint);
		//rtfExporter.setParameter(JRExporterParameter.OUTPUT_FILE_NAME, “d:/test/test.rtf”);
		//rtfExporter.exportReport();
		
		//header("Content-type: application/pdf");
		//readfile($outputPath);
		//die('zelda');
		//unlink($outputPath);
		echo "เอกสารต้นฉบับสำหรับหนังสือ [{$docMain->f_title}] สร้างเรียบร้อย<hr/>";
		echo "ไฟล์ PDF : เปิดได้<a target=\"_blank\" href=\"http://localhost/shared/formReport/output{$id}.pdf\">ที่นี่</a><br/>";
		echo "ไฟล์ RTF(Word) : เปิดได้<a target=\"_blank\" href=\"http://localhost/shared/formReport/output{$id}.rtf\">ที่นี่</a>";
	}
}
