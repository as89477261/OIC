<?php
/**
 * ������觢��������Ѻ Workflow Designer
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Workflow
 *
 */
class WFDataController extends ECMController {
	/**
	 * action /user �觢����ż����ҹ
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
	 * �ӡ�� Query
	 *
	 */
	public function sqlAction() {
		
	}
	/**
	 * �ӡ�öʹ���ʵ��˹��繵�Ǻؤ��
	 *
	 */
	public function rolDispatchAction() {
		
	}
	/**
	 * �觢����ŵ��˹�
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

