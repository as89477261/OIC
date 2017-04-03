<?php 
loadExternalLib('ExcelWriter');
$data = $this->data;
$countData = $this->countData;
$ann_label = $this->ann_label;
$ann = $this->ann;
$org = $this->org;
$dateFromTxt = $this->dateFromTxt;
$dateToTxt = $this->dateToTxt;
$dateTime = date("G.i.s");
$printDate = $this->printDate;
		
ini_set('display_error', 1);
ini_set('error_reporting', E_ALL);		

$fname = "report".uniqid(time()).".xls";
$workbook =& new writeexcel_workbook($fname);
$worksheet =& $workbook->addworksheet('รายงานรายงานประวัติการเข้าใช้ระบบ');
$worksheet->set_landscape();

#######################################################################
#
# Write a general heading
#
$heading0  =& $workbook->addformat(array(
color   => 'black',
size    => 22,
align => 'center'
));
$heading  =& $workbook->addformat(array(
color   => 'black',
size    => 18,
align => 'center'
));
$heading2  =& $workbook->addformat(array(
color   => 'black',
size    => 18,
align => 'left'
));
$subheading  =& $workbook->addformat(array(
color   => 'black',
size    => 16
));
$subheadingright  =& $workbook->addformat(array(
color   => 'black',
size    => 16,
align   => 'right'
));

$headings1 = array("สำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย", '');
$headings2 = array("รายงานสรุปปริมาณการรับ - ส่งหนังสือ", '');
$headings3 = array("$org", '');
$headings4 = array("วันที่จาก $dateFromTxt ถึง $dateToTxt", '');
$headings5 = array("วันที่ : $printDate", '');
$headings6 = array("เวลา : $dateTime", '');

$worksheet->write_row('C1', $headings1, $heading0);
$worksheet->write_row('C2', $headings2, $heading);
$worksheet->write_row('C3', $headings3, $heading);
$worksheet->write_row('C4', $headings4, $heading);
$worksheet->write_row('D2', $headings5, $heading2);
$worksheet->write_row('D3', $headings6, $heading2);

$heading  =& $workbook->addformat(array(
bold    => 1,
color   => 'black',
size    => 16
));
$format2 =& $workbook->addformat();
$format2->set_text_wrap();
$format2->set_bottom(1);
$format2->set_left(1);
$format2->set_right(1);
$format2->set_top(1);
$format2->set_size(14);
$format2->set_align("center");
/*******************************************************************/
$worksheet->set_column(0,0, 20);
$worksheet->set_column(1,1, 30);
$worksheet->set_column(2,2, 100);
$worksheet->set_column(3,3, 30);
$worksheet->write('B6','ลำดับที่',$format2);
$worksheet->write('C6','ประเภทการรับ - ส่ง',$format2);
$worksheet->write('D6','จำนวน',$format2);


$format3 =& $workbook->addformat();
$format3->set_text_wrap();
$format3->set_bottom(1);
$format3->set_left(1);
$format3->set_right(1);
$format3->set_top(1);
$format3->set_size(14);
$format3->set_align("left");
$orwExcel=7;
$itemNo = 1;
for($i=0;$i<count($countData);$i++){
	if($countData[$i] != 0){
		$worksheet->write("B$orwExcel",$itemNo,$format2);
		$worksheet->write("C$orwExcel",$data[$i],$format3);
		$worksheet->write("D$orwExcel",$countData[$i],$format2);
		$orwExcel++;
		$itemNo++;
	}
}

if($commandCondition == TRUE){
		$worksheet->write("B$orwExcel",$itemNo,$format2);
		$worksheet->write("C$orwExcel",$data[$i],$format3);
		$worksheet->write("D$orwExcel","",$format2);
		$orwExcel++;

			for($j=0;$j<count($ann_label);$j++){
				if($ann[$j] != 0){
					$worksheet->write("B$orwExcel","",$format2);
					$worksheet->write("C$orwExcel","       ".$ann_label[$j],$format3);
					$worksheet->write("D$orwExcel",$ann[$j],$format2);
					$orwExcel++;
				}
			}
}


$workbook->close();
header("Content-Type: application/x-msexcel");
header('Content-disposition: attachment; filename=' . $fname);

$content = file_get_contents($fname);
print $content;
@unlink($fname);



?>