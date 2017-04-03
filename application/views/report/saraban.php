<?php 

$data = $this->data;
$countData = $this->countData;
$ann_label = $this->ann_label;
$ann = $this->ann;
$org = $this->org;
$dateFromTxt = $this->dateFromTxt;
$dateToTxt = $this->dateToTxt;
$dateTime = date("G.i.s");
$printDate = $this->printDate;
$commandCondition = $this->commandCondition;
		
		$html = "
		<html>
		<head>
			<style>
			.setborder{ 
					border-left: 1px solid black;
				}
				.setborder tbody td{ 
					border-right: 1px solid black;
					border-bottom: 1px solid black; 
				}
				th.haveborder { 
					border-right: 1px solid black;
					border-bottom: 1px solid black; 
					border-top: 1px solid black; 
				} 
				th.nonborder { 
					border-top: 1px solid black; 
				} 
				body, td, th, div {font-family: Angsana New; font-size: 14pt;}
				h1{font-size: 18pt;}
				#fourth thead { display: table-header-group; }
				#fourth tfoot { display: table-footer-group; }
</style>
		</head>
		<body>
		<form name=\"reportForm\" method=\"post\" action=\"/{$config['appName']}/export-report/docflow-stats\" >
		<center>
		<table style=\"width:600px;\" border =\"0\" >
			<tr>
				<td style=\"width:60%\" colspan = \"3\"><center><h1>สำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย</h1></center></td>
			</tr>
			<tr>
				<td style=\"width:20%\"></td>
				<td style=\"width:60%\"><center>รายงานสรุปปริมาณการรับ - ส่งหนังสือ</center></td>
				<td style=\"width:20%\">วันที่ : {$printDate}</td>
			</tr>
			<tr>
				<td></td>
				<td ><center>{$org}</center></td>
				<td>เวลา : {$dateTime}</td>
			</tr>
			<tr>
				<td></td>
				<td ><center>ตั้งแต่วันที่ {$dateFromTxt} &nbsp;&nbsp;&nbsp;ถึงวันที่ {$dateToTxt}</center></td>
				<td></td>
			</tr>
		</table>
		<br />
		<div id=\"fourth\">
		<table class=\"setborder\"style=\"width:600px;\" cellspacing=\"0\" >
		<thead >
			<tr>
				<th class=\"haveborder\" style=\"width:10%\"><center>ลำดับที่</center></th>
				<th class=\"haveborder\" style=\"width:75%\"><center>ประเภทการรับ - ส่ง</center></th>
				<th class=\"haveborder\" style=\"width:15%\"><center>จำนวน</center></th>
			</tr>
		</thead>
		<!--tfoot>
			<tr>
				<th colspan=\"3\" >sdfsdfsdljkfsdlkfsdlfjkdjkf</th>
			</tr>
		</tfoot-->
		";
		$itemNo = 1;
		for($i = 0;$i<count($countData);$i++){
			if($countData[$i] != 0){
				$html .= "
					<tr>
						<td ><center>&nbsp;$itemNo</center></td>
						<td>&nbsp;$data[$i]</td>
						<td ><center>$countData[$i]</center></td>
					</tr>
				";
					$itemNo++;
			}
		}
		if($commandCondition == TRUE){
			$html .= "
						<tr>
							<td ><center>&nbsp;$itemNo</center></td>
							<td>&nbsp;$data[11]</td>
							<td>&nbsp;</td>
						</tr>
						";
		$dot = 1;
			for($j=0;$j<count($ann_label);$j++){
				if($dot == 12){
					$style = "style=\"page-break-after:always;\" ";
				}
				else{
					$style = "";
				}
				if($ann[$j] != 0){
					
					$html .= "
						<tr>
							<td>&nbsp;</td>
							<td $style >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$itemNo.$dot&nbsp;&nbsp;$ann_label[$j]</td>
							<td ><center>$ann[$j]</center></td>
						</tr>
					";
						$dot ++;
				}
			}
		}
		$html .= "
		</table>
		</div>
		</center>
		</form></body></html>";
		echo $html;


?>