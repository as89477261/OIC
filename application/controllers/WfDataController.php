<?php
/**
 * โปรแกรมส่งข้อมูลให้กับ Workflow Designer
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Workflow
 *
 */
class WFDataController extends ECMController {
	/**
	 * action /user ส่งข้อมูลผู้ใช้งาน
	 *
	 */
	public function userAction() {
		global $conn;
		
		$sql = "select f_acc_id,f_name,f_last_name from tbl_account order by f_name";
		$sql = stripslashes ( $sql );
		$rs = $conn->Execute ( $sql );
		if (eregi ( 'select', $sql )) {
			if (! $rs) {
				echo 'ERROR';
				exit ();
			} else {
				while ( $data = $rs->FetchRow () ) {
					$value = Array ();
					$value = array_values ( $data );
					$valueStr .= join ( "|", $value ) . "\r\n";
				}
				$valueStr = $valueStr . "@OWNER|@OWNER|\r\n";
				$rs->Close ();
			}
		}
		
		$conn->Close ();
		if (! $rs) {
			echo "ERROR";
		} else {
			if ($valueStr == '') {
				echo "EXCEPT";
			} else {
				echo $valueStr;
			}
		}
	}
	/**
	 * ทำการ Query
	 *
	 */
	public function sqlAction() {
		
	}
	/**
	 * ทำการถอดรหัสตำแหน่งเป็นตัวบุคคล
	 *
	 */
	public function rolDispatchAction() {
		
	}
	/**
	 * ส่งข้อมูลตำแหน่ง
	 *
	 */
	public function roleAction() {
		global $conn;
		
		$sql = "select f_role_id,f_role_name from tbl_role order by f_role_name";
		$sql = stripslashes ( $sql );
		$rs = $conn->Execute ( $sql );
		if (eregi ( 'select', $sql )) {
			if (! $rs) {
				echo 'ERROR';
				exit ();
			} else {
				while ( $data = $rs->FetchRow () ) {
					$value = Array ();
					$value = array_values ( $data );
					$valueStr .= join ( "|", $value ) . "\r\n";
				}
				$rs->Close ();
			}
		}
		
		$conn->Close ();
		if (! $rs) {
			echo "ERROR";
		} else {
			if ($valueStr == '') {
				echo "EXCEPT";
			} else {
				echo $valueStr;
			}
		}
	}
}

