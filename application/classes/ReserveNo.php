<?php
/**
 * Class สำหรับการจองเลข
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category DocFlow
 */
class ReserveNo {
	/**
	 * ทำการจองเลข
	 *
	 * @param int $type
	 * @param int $secret
	 * @param int $docID
	 * @return array
	 */
	function doReserve($type, $secret = false, $docID = 0) {
		//include_once 'ReserveNo.Entity.php';
		//include_once 'Organize.Entity.php';
		
		global $sequence;
		global $config;
		global $sessionMgr;
		
		$org = new OrganizeEntity ( );
		$org->Load ( "f_org_id = '{$sessionMgr->getCurrentOrgID()}'" );
		$orgID = $org->f_org_id;
		$regBookID = 0;
		$docCode = $org->f_ext_code;
		
		if (! $sequence->isExists ( 'reserve' )) {
			$sequence->create ( 'reserve' );
		}
		
		/**
		 * Send External Case
		 */
		
		if(!$_POST['reserveAmountNo']){
			$_POST['reserveAmountNo'] = 1;
			}

		for($i=0;$i<$_POST['reserveAmountNo'];$i++){

		if ($type == 0) {
			$sendType = 2;
			if ($secret) {
				$sendType = 4;
			}
			
			if ($sendType == 4 && $config ['runningSecretWithNormal']) {
				$docRunner = "RunExt.{$sessionMgr->getCurrentYear()}.{$docCode}";
			} else {
				if ($sendType == 4) {
					$docRunner = "RunExtSecret.{$sessionMgr->getCurrentYear()}.{$docCode}";
				} else {
					$docRunner = "RunExt.{$sessionMgr->getCurrentYear()}.{$docCode}";
				}
			}
			$sendRegNo = "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}" ;
		} else {
			$sendType = 3;
			if ($secret) {
				$sendType = 4;
				$sendRegNo = "sendRegNo_{$orgID}_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
			} else {
				$sendRegNo = "sendRegNo_0_{$sendType}_{$regBookID}_{$sessionMgr->getCurrentYear()}";
			}
			
			if ($sendType == 4 && $config ['runningSecretWithNormal']) {
				$docRunner = "RunExtGlobal.{$sessionMgr->getCurrentYear()}";
			} else {
				if ($sendType == 4) {
					$docRunner = "RunExtGlobalSecret.{$sessionMgr->getCurrentYear()}";
				} else {
					$docRunner = "RunExtGlobal.{$sessionMgr->getCurrentYear()}";
				}
			}
		}
		
		$now = time ();
		$reserve = new ReserveNoEntity ( );
		$reserve->f_reserved_id = $sequence->get ( 'reserve' );
		/**
		 * 0=Send External
		 * 1=Send External Global
		 */
		$reserve->f_reserved_type = $type;
		$reserve->f_reserved_org = $sessionMgr->getCurrentOrgID ();
		$reserve->f_acc_id = $sessionMgr->getCurrentAccID ();
		$reserve->f_role_id = $sessionMgr->getCurrentRoleID ();
		$reserve->f_org_id = $sessionMgr->getCurrentOrgID ();
		$reserve->f_reserved_stamp = $now;
		$reserve->f_used = 0;
		$reserve->f_used_stamp = 0;
		Logger::debug("docRunner: {$docRunner}");
		Logger::debug("regNo: {$sendRegNo}");
		if(!$sequence->isExists($docRunner)) {
			$sequence->create($docRunner);
		}
		if(!$sequence->isExists($sendRegNo)) {
			$sequence->create($sendRegNo);
		}
		$reserve->f_reserved_book_no = $sequence->get($docRunner);
		$reserve->f_reserved_reg_no = $sequence->get($sendRegNo);
		$reserve->f_reserved_timestamp = $now;
		$reserve->f_doc_id = $docID;
		$reserve->Save ();
		
		}

		$response = Array();
		$response['success'] = 1;
		$response['regNo'] = $reserve->f_reserved_reg_no;
		$response['bookNo'] = $reserve->f_reserved_book_no;
		$response['amoun'] = $_POST['reserveAmountNo'];
		return $response;
	}
}
