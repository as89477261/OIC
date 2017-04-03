<?php

class SystemMonitor {
	/**
	 * �׹�ӹǹ User Concurrent 㹻Ѩ�غѹ
	 *
	 * @return int
	 */
	public function getCurrentConcurrentsCount() {
		global $conn;
		$sqlGetConcurrents = "select count(a.f_acc_id) as COUNT_CONS from tbl_concurrent a,tbl_account b where a.f_acc_id = b.f_acc_id and b.f_account_type < 3";
		$rs = $conn->Execute($sqlGetConcurrents);
		$tmp = $rs->FetchNextObject();
		return $tmp->COUNT_CONS;
	}
	
	/**
	 * �ͨӹǹ Admin Concurrent
	 *
	 * @return int
	 */
	public function getCurrentAdminConcurrentsCount() {
		global $conn;
		$sqlGetConcurrents = "select count(a.f_acc_id) as COUNT_CONS from tbl_concurrent a,tbl_account b where a.f_acc_id = b.f_acc_id and b.f_account_type >= 3";
		$rs = $conn->Execute($sqlGetConcurrents);
		$tmp = $rs->FetchNextObject();
		return $tmp->COUNT_CONS;
	}
	
	/**
	 * �ͨӹǹ Total Concurrent
	 *
	 * @return int
	 */
	public function getTotalConcurrents() {
		return ($this->getCurrentAdminConcurrentsCount()+$this->getCurrentConcurrentsCount());
	}
	
	public function getNonDegradeTotalConcurrents() {
		global $conn;
		$sqlGetConcurrents = "select count(a.f_acc_id) as COUNT_CONS from tbl_concurrent a where a.f_degrade = 0";
		$rs = $conn->Execute($sqlGetConcurrents);
		$tmp = $rs->FetchNextObject();
		return $tmp->COUNT_CONS;
	}
}
