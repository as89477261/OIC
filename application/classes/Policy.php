<?php
/**
 * Policy Class
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category System
 */
class Policy {
	/**
	 * á¿Å¡¡ÒÃâËÅ´¹âÂºÒÂ
	 *
	 * @var boolean
	 */
	private $policyLoaded;
	/**
	 * ¹âÂºÒÂ·Õè·Ó¡ÒÃ»ÃĞÁÇÅ¼ÅáÅéÇ
	 *
	 * @var variant
	 */
	private $policyRecord;
	
	/**
	 * ¡ÓË¹´¤èÒàÃÔèÁµé¹¢Í§ Policy Class
	 *
	 * @param int $policyID
	 */
	public function __construct($policyID) {
		//require_once 'GroupPolicy.Entity.php';
		$this->policyLoaded = false;
		$this->policyRecord = new GroupPolicyEntity ( );
		if ($policyID != 0) {
			if (! $this->policyRecord->Load ( "f_gp_id = '{$policyID}'" )) {
				$this->policyLoaded = false;
			} else {
				$this->policyLoaded = true;
			}
		}
	}
	
	/**
	 * ¿Ñ§¡ìªÑ¹µÃÇ¨ÊÍºÇèÒ Policy valid ËÃ×ÍäÁè
	 *
	 * @return boolean
	 */
	public function isValid() {
		return $this->policyLoaded;
	}
	
	/**
	 * ÊÒÁÒÃ¶á¹º/Êá¡¹àÍ¡ÊÒÃä´é
	 *
	 * @return boolean
	 */
	public function canAttach() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_attach == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function canAttachDMS() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_dms_attach == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function canScanDMS() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_dms_scan == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ÊÒÁÒÃ¶Êè§ÍÍ¡ÀÒÂ¹Í¡·ĞàºÕÂ¹¡ÅÒ§ä´é
	 *
	 * @return boolean
	 */
	public function canReceiveExternalGlobal() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_recv_egif == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ÊÒÁÒÃ¶Êè§ÍÍ¡ÀÒÂ¹Í¡ä´é
	 *
	 * @return boolean
	 */
	public function canSendExternalGlobal() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_send_egif == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ÊÒÁÒÃ¶ÃÑºÀÒÂã¹ä´é
	 *
	 * @return boolean
	 */
	public function canReceiveInternal() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_recv_int == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ÊÒÁÒÃ¶Êè§ÀÒÂã¹ä´é
	 *
	 * @return boolean
	 */
	public function canSendInternal() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_send_int == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ÊÒÁÒÃ¶ÃÑºÀÒÂ¹Í¡ä´é
	 *
	 * @return boolean
	 */
	public function canReceiveExternal() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_recv_ext == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ÊÒÁÒÃ¶Êè§ÀÒÂ¹Í¡ä´é
	 *
	 * @return boolean
	 */
	public function canSendExternal() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_send_ext == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ÊÒÁÒÃ¶à¢éÒ¶Ö§·ĞàºÕÂ¹ÅÑºä´é
	 *
	 * @return boolean
	 */
	public function canAccessClassifiedRegister() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_secret_lvl > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * à»ç¹¹ÒÂ·ĞàºÕÂ¹ÅÑºä´é
	 *
	 * @return boolean
	 */
	public function isSecretAgent() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_secret_agent == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * à»ç¹ Saraban Master
	 *
	 * @return boolean
	 */
	public function isSarabanMaster() {
		global $sessionMgr;
		if ($sessionMgr->isDegradeMode ())
			return false;
		if (( int ) $this->policyRecord->f_sb_master == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function isSG() {
		global $sessionMgr;
		if($sessionMgr->isDegradeMode()) 
			return false;
		if (( int ) $this->policyRecord->f_sb_access == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ·Ó¡ÒÃâËÅ´ DMS Policy
	 *
	 * @return array
	 */
	public function getCurrentDMSPolicy() {
		$authorize = Array ('f_dms_master' => $this->policyRecord->f_dms_master, 'f_dms_access' => $this->policyRecord->f_dms_access, 'f_dms_create_folder' => $this->policyRecord->f_dms_create_folder, 'f_dms_modify_folder' => $this->policyRecord->f_dms_modify_folder, 'f_dms_delete_folder' => $this->policyRecord->f_dms_delete_folder, 'f_dms_create_doc' => $this->policyRecord->f_dms_create_doc, 'f_dms_modify_doc' => $this->policyRecord->f_dms_modify_doc, 'f_dms_delete_doc' => $this->policyRecord->f_dms_delete_doc, 'f_dms_create_shortcut' => $this->policyRecord->f_dms_create_shortcut, 'f_dms_modify_shortcut' => $this->policyRecord->f_dms_modify_shortcut, 'f_dms_delete_shortcut' => $this->policyRecord->f_dms_delete_shortcut, 'f_dms_move' => $this->policyRecord->f_dms_move, 'f_dms_share' => $this->policyRecord->f_dms_share, 'f_dms_export' => $this->policyRecord->f_dms_export, 'f_dms_grant' => $this->policyRecord->f_dms_grant, 'f_dms_scan' => $this->policyRecord->f_dms_scan, 'f_dms_attach' => $this->policyRecord->f_dms_attach, 'f_dms_print' => $this->policyRecord->f_dms_print, 'f_dms_annotate' => $this->policyRecord->f_dms_annotate, 'f_dms_create_folder_loc' => $this->policyRecord->f_dms_create_folder_loc, 'f_dms_modify_folder_loc' => $this->policyRecord->f_dms_modify_folder_loc, 'f_dms_delete_folder_loc' => $this->policyRecord->f_dms_delete_folder_loc, 'f_dms_view_loc' => $this->policyRecord->f_dms_view_loc, 'f_dms_create_doc_loc' => $this->policyRecord->f_dms_create_doc_loc, 'f_dms_modify_doc_loc' => $this->policyRecord->f_dms_modify_doc_loc, 'f_dms_delete_doc_loc' => $this->policyRecord->f_dms_delete_doc_loc, 'f_dms_create_shortcut_loc' => $this->policyRecord->f_dms_create_shortcut_loc, 'f_dms_modify_shortcut_loc' => $this->policyRecord->f_dms_modify_shortcut_loc, 'f_dms_delete_shortcut_loc' => $this->policyRecord->f_dms_delete_shortcut_loc, 'f_dms_move_loc' => $this->policyRecord->f_dms_move_loc, 'f_dms_share_loc' => $this->policyRecord->f_dms_share_loc, 'f_dms_export_loc' => $this->policyRecord->f_dms_export_loc, 'f_dms_grant_loc' => $this->policyRecord->f_dms_grant_loc, 'f_dms_scan_loc' => $this->policyRecord->f_dms_scan_loc, 'f_dms_attach_loc' => $this->policyRecord->f_dms_attach_loc, 'f_dms_print_loc' => $this->policyRecord->f_dms_print_loc, 'f_dms_annotate_loc' => $this->policyRecord->f_dms_annotate_loc );
		return array_change_key_case ( $authorize, CASE_UPPER );
	}
	
	/**
	 * ·Ó¡ÒÃâËÅ´ DMS Policy µÒÁ LOC
	 *
	 * @return array
	 */
	public function getCurrentDMSLOCPolicy() {
		$authorize = Array ('f_dms_create_folder_loc' => $this->policyRecord->f_dms_create_folder_loc, 'f_dms_modify_folder_loc' => $this->policyRecord->f_dms_modify_folder_loc, 'f_dms_delete_folder_loc' => $this->policyRecord->f_dms_delete_folder_loc, 'f_dms_view_loc' => $this->policyRecord->f_dms_view_loc, 'f_dms_create_doc_loc' => $this->policyRecord->f_dms_create_doc_loc, 'f_dms_modify_doc_loc' => $this->policyRecord->f_dms_modify_doc_loc, 'f_dms_delete_doc_loc' => $this->policyRecord->f_dms_delete_doc_loc, 'f_dms_create_shortcut_loc' => $this->policyRecord->f_dms_create_shortcut_loc, 'f_dms_modify_shortcut_loc' => $this->policyRecord->f_dms_modify_shortcut_loc, 'f_dms_delete_shortcut_loc' => $this->policyRecord->f_dms_delete_shortcut_loc, 'f_dms_move_loc' => $this->policyRecord->f_dms_move_loc, 'f_dms_share_loc' => $this->policyRecord->f_dms_share_loc, 'f_dms_export_loc' => $this->policyRecord->f_dms_export_loc, 'f_dms_grant_loc' => $this->policyRecord->f_dms_grant_loc, 'f_dms_scan_loc' => $this->policyRecord->f_dms_scan_loc, 'f_dms_attach_loc' => $this->policyRecord->f_dms_attach_loc, 'f_dms_print_loc' => $this->policyRecord->f_dms_print_loc, 'f_dms_annotate_loc' => $this->policyRecord->f_dms_annotate_loc );
		return array_change_key_case ( $authorize, CASE_UPPER );
	}
	
	/**
	 * DMS Policy áºº No Access
	 *
	 * @return array
	 */
	public function getDMSLOCNoAccess() {
		$authorize = Array ('f_dms_create_folder_loc' => 0, 'f_dms_modify_folder_loc' => 0, 'f_dms_delete_folder_loc' => 0, 'f_dms_view_loc' => 0, 'f_dms_create_doc_loc' => 0, 'f_dms_modify_doc_loc' => 0, 'f_dms_delete_doc_loc' => 0, 'f_dms_create_shortcut_loc' => 0, 'f_dms_modify_shortcut_loc' => 0, 'f_dms_delete_shortcut_loc' => 0, 'f_dms_move_loc' => 0, 'f_dms_share_loc' => 0, 'f_dms_export_loc' => 0, 'f_dms_grant_loc' => 0, 'f_dms_scan_loc' => 0, 'f_dms_attach_loc' => 0, 'f_dms_print_loc' => 0, 'f_dms_annotate_loc' => 0 );
		return array_change_key_case ( $authorize, CASE_UPPER );
	}
	
	/**
	 * µÃÇ¨ÇèÒà»ç¹ÊÁÒªÔ¡¢Í§ Secure Group
	 *
	 * @param int $secureGroupID
	 * @return boolean
	 */
	public function isSecureGroupMember($secureGroupID) {
		global $conn;
		global $util;
		global $sessionMgr;
		
		$sql = "select * from tbl_secure_group_member where f_secure_id ='{$secureGroupID}' and f_allow = 1";
		//Logger::debug("get security group member : ".$sql);
		$rs = $conn->Execute ( $sql );
		$members = Array ();
		while ( $tmp = $rs->FetchNextObject () ) {
			if ($tmp->F_MEMBER_TYPE == 1) {
				$members [] = $tmp->F_MEMBER_ID;
			}
			
			if ($tmp->F_MEMBER_TYPE == 2) {
				$members = array_merge ( $members, $util->getRoleMembers ( $tmp->F_MEMBER_ID ) );
			}
			
			if ($tmp->F_MEMBER_TYPE == 1) {
				$members = array_merge ( $members, $util->getOrganizeMember ( $tmp->F_MEMBER_ID ) );
			}
		}
		//Logger::dump("Group Member",$members);
		if (in_array ( $sessionMgr->getCurrentAccID (), $members ) || count ( $members ) == 0) {
			//Logger::debug("You are in this group");
			return true;
		} else {
			//Logger::debug("You are NOT in this group");
			return false;
		}
	}
	
	public function canChangeFlag() {
		if (( int ) $this->policyRecord->f_sb_flag == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function canUseGlobalSearch() {
		if (( int ) $this->policyRecord->f_sb_global_search == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function canReserveBookno() {
		if (( int ) $this->policyRecord->f_reserve_book_no == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function canRoomAccess(){
		if (( int ) $this->policyRecord->f_room_access == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isRoomAdmin(){
		if (( int ) $this->policyRecord->f_room_admin == 1) {
			return true;
		} else {
			return false;
		}
	}

}
