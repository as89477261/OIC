<?php

$description = $this->description;
$userID = $this->userID;
$success = $this->success;


		$html = "
			<html>
				<header>
				</header>
				<body>
				<form name=\"loadAccountData\" action=\"load-acc-from-hr?check=1\" method=\"post\" >
					<div align=\"center\" >
					<br />
					<br />
					<span style=\"color: red;\" >$description</span><span style=\"color: blue;\" >$success</span>
					<br />
					<br />
					<table border=\"0\" >
						<tr>
							<td colspan=\"2\" >ใส่รหัสพนักงานที่ต้องการโหลดข้อมูลจาก HRMS ไปยัง ECM</td>
						</tr>
						<tr>
							<td colspan=\"2\" >&nbsp;</td>
						</tr>
						<tr>
							<td><input type=\"text\" name=\"userID\" value=\"$userID\" /></td>
							<td><input type=\"submit\" value=\"ทำการโหลดข้อมูล\" /></td>
						</tr>
					</table>
					</div>
				</form>
				</body>
			</html>
		";
		echo $html;

?>