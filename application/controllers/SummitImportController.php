<?php
/**
 * Summit Import Data Controller for Demo Sale Rattana
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Summit
 *
 */
class SummitImportController extends ECMController {
	public function indexAction() {
		global $config;
        echo "<h1>Import XLS Data</h1>";
        echo "<form action=\"/{$config['appName']}/summit-import/process\" method=\"POST\" enctype=\"multipart/form-data\">
        Excel<input type=\"file\" name=\"excelReport\" />
        <input type=\"submit\" value=\"Import\" />
        </form>";
	}
	
	public function PostUrl($vars,$url){
       $ch = curl_init();
       curl_setopt($ch,CURLOPT_URL,$url);
       curl_setopt($ch,CURLOPT_POST,1);
       #curl_setopt($ch,CURLOPT_UPLOAD,1);
       curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
       curl_exec($ch);
       curl_close($ch);
	}
	
	public function processAction() {
		loadExternalLib('ExcelReader');
        $excelReader = new Spreadsheet_Excel_Reader();
        $excelReader->setOutputEncoding('tis-620');
        $excelReader->read($_FILES["excelReport"]["tmp_name"]);
        
        $urlCreateDocument = "http://localhost/ECMDev/document/create-document";
        
        //echo $_FILES["excelReport"]["tmp_name"]."<br/>";
        echo "จำนวนทั้งหมด ".$excelReader->sheets[0]['numRows']. " รายการ<br/>";
        echo "<hr/>";
        for($row = 2; $row <= $excelReader->sheets[0]['numRows'] ;$row++) {
        	echo "เลขที่ Invoice : Autonumber<br/>";
        	echo "เลขที่สัญญา :" .$excelReader->sheets[0]['cells'][$row][4]."<br/>";
        	echo "วันที่ :" .$excelReader->sheets[0]['cells'][$row][5]."<br/>";
        	echo "ประเภท :" .$excelReader->sheets[0]['cells'][$row][6]."<br/>";
        	echo "ทะเบียนรถ :" .$excelReader->sheets[0]['cells'][$row][7]."<br/>";
        	echo "เลขตัวถัง :" .$excelReader->sheets[0]['cells'][$row][8]."<br/>";
        	echo "เลขเครื่องยนต์ :" .$excelReader->sheets[0]['cells'][$row][9]."<br/>";
        	echo "ทะเบียนหมดอายุ :" .$excelReader->sheets[0]['cells'][$row][10]."<br/>";
        	echo "ประกันหมดอายุ :" .$excelReader->sheets[0]['cells'][$row][11]."<br/>";
        	echo "รายการ :" .$excelReader->sheets[0]['cells'][$row][12]."<br/>";
        	echo "เลขที่อ้างอิง :" .$excelReader->sheets[0]['cells'][$row][13]."<br/>";
        	echo "งวดที่ :" .$excelReader->sheets[0]['cells'][$row][14]."<br/>";
        	echo "จำนวนเงิน :" .$excelReader->sheets[0]['cells'][$row][15]."<br/>";
        	echo "ภาษี :" .$excelReader->sheets[0]['cells'][$row][16]."<br/>";
        	echo "จำนวนเงินรวม :" .$excelReader->sheets[0]['cells'][$row][17]."<br/>";
        	echo "ชื่อ-สกุล :" .$excelReader->sheets[0]['cells'][$row][18]."<br/>";
        	echo "ที่อยู่1 :" .$excelReader->sheets[0]['cells'][$row][19]."<br/>";
        	echo "ที่อยู่2 :" .$excelReader->sheets[0]['cells'][$row][20]."<br/>";
        	echo "ที่อยู่3 :" .$excelReader->sheets[0]['cells'][$row][21]."<br/>";
        	echo "<hr/>";
        	
        	//$arrayData[] = $docname;
			$arrayCreateDocument = Array (
				'formID'=>10,
				'tempID'=>99899,
				'parentID'=>157,
				'parentType'=>'',
				'parentMode'=>'dms',
				'Date'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][5]),
				'Serial'=>iconv('TIS-620','UTF-8',"AutoNumber"),
				'ContractNo'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][4]),
				'ContractType'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][6]),
				'CarRegister'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][7]),
				'ChassiNo'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][8]),
				'EngineNo'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][9]),
				'ExpireDate'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][10]),
				'InsureExpireDate'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][11]),
				'Description'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][12]),
				'RefNo'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][13]),
				'PaymentNo'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][14]),
				'PaymentAmount'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][15]),
				'TaxAmount'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][16]),
				'TotalAmount'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][17]),
				'AddressLine1'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][18]),
				'AddressLine2'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][19]),
				'AddressLine3'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][20]),
				'AddressLine4'=>iconv('TIS-620','UTF-8',$excelReader->sheets[0]['cells'][$row][21])
			);
			$this->PostUrl($arrayCreateDocument,$urlCreateDocument);
        }
        echo "<hr/>ดำเนินการนำเข้าเรียบร้อยแล้ว <a href=\"./index\">กลับไปเริ่ม Import</a>";
	}
	/**
     * Initialization method
     */
    public function init() {
        $this->setupECMActionController ();
        $this->setECMViewModule ( 'default' );
	}
}
