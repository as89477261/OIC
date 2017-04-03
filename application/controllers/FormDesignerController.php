<?php
/**
 * โปรแกรม Design แบบฟอร์ม
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Form
 */
class FormDesignerController extends Zend_Controller_Action {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		//TODO: Convert FormDesignerController to ECMController
		//$this->setupECMActionController();
	}
	
	/**
	 * action /save-design ทำการบันทึกแบบฟอร์ม
	 *
	 */
	public function saveDesignAction() {
		global $config;
		$DesignerData = $_POST ['DesignerData'];
		$formID = $_POST ['formID'];
		$formVersion = $_POST ['formVersion'];
		
		$fp = fopen ( "{$config['formPath']}designedForm_{$formID}.html", "w+" );
		fseek ( $fp, 0 );
		fwrite ($fp, $DesignerData );
		fclose ( $fp );

		echo "<body style=\"margin: 0px;left: 0px;top: 0px;\"><iframe name=\"designerFrame_{$formID}_{$formVersion}\" src=\"/{$config['appName']}/form-designer/get-designer/?formID={$formID}&formVersion={$formVersion}\" width=\"100%\" height=\"100%\" frameborder=\"0\"></iframe></body>";
	}
	
	/**
	 * action /get-ui แสดงหน้่าจอการ Design แบบฟอร์ม
	 *
	 */
	public function getUiAction() {
		global $config;
		
		checkSessionPortlet();
		
		$formID = $_POST ['formID'];
		$formVersion = $_POST ['formVersion'];
		
		echo "<body style=\"margin: 0px;left: 0px;top: 0px;\"><iframe name=\"designerFrame_{$formID}_{$formVersion}\" src=\"/{$config['appName']}/form-designer/get-designer/?formID={$formID}&formVersion={$formVersion}\" width=\"100%\" height=\"100%\" frameborder=\"0\"></iframe></body>";
	}
	
	/**
	 * action /get-designer ทำการ แสดง WYSIWYG Editor
	 *
	 */
	public function getDesignerAction() {
		global $config;
		global $conn;
		
		checkSessionPortlet();
		
		$formID = $_GET ['formID'];
		$formVersion = $_GET ['formVersion'];
		
		include_once ("fckeditor.php");
		//include_once ("FormRenderer.php");
		
		$formRenderer = new FormRenderer ( );
		$sql = "select * from tbl_form_structure where f_form_id = '{$formID}' and f_version = '{$formVersion}'";
		$rs = $conn->Execute ( $sql );
		
		$html = "";
		if (file_exists ( "{$config['formPath']}designedForm_{$formID}.html" )) {
			$fileContent = file_get_contents("{$config['formPath']}designedForm_{$formID}.html")."<hr/>";
			$html .= $fileContent;
		}
		
		
		$html .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"300\" class=\"default\">";
		
		foreach ( $rs as $row ) {
			checkKeyCase($row);
			$tag = $formRenderer->create (  $row ['f_struct_name'], $row ['f_struct_type'], $row ['f_struct_name'] );
			$html .= "<tr><td>{$row['f_struct_name']}</td><td>{$tag}</td></tr>";
		}
		$html .= "</table>";
		
		$oFCKeditor = new FCKeditor ( 'DesignerData' );
		$oFCKeditor->BasePath = $config ['fckeditorRelPath'];
		$oFCKeditor->Value = $html;
		$oFCKeditor->Height = 545;
		
		echo "<html><head><title>Form Designer</title></head><body style=\"margin: 0;\">";
		echo "<form action=\"/{$config['appName']}/form-designer/save-design/\" method=\"POST\" target=\"designerFrame_{$formID}_{$formVersion}\">";
		echo "<input type=\"hidden\" name=\"formID\" value=\"{$formID}\">";
		echo "<input type=\"hidden\" name=\"formVersion\" value=\"{$formVersion}\">";
		
		$oFCKeditor->Create ();
		
		echo "</form>";
	}
}
