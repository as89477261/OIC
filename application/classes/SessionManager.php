<?php
/**
 * Session Manager
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category System
 */
class SessionManager {
	/**
	 * Concurrent Object
	 *
	 * @var Object
	 */
	private $concurrent;
	/**
	 * Account Object
	 *
	 * @var Object
	 */
	private $account;
	/**
	 * Role Object
	 *
	 * @var Object
	 */
	private $role;
	/**
	 * Organize Object
	 *
	 * @var Object
	 */
	private $org;
	/**
	 * Policy Object
	 *
	 * @var Object
	 */
	private $policy;
	/**
	 * Logon Flag
	 *
	 * @var boolean
	 */
	private $loggedOn;
	
	/**
	 * สร้าง Object เริ่มต้นใน Class
	 *
	 */
	public function __construct() {
		global $policy;
		
		#include_once 'Policy.php';
		#include_once 'Account.Entity.php';
		#include_once 'Concurrent.Entity.php';
		#include_once 'Role.Entity.php';
		#include_once 'Organize.Entity.php';
		
		$this->loadEntities ();
		$this->concurrent = new ConcurrentEntity ( );
		$policy = new Policy ( $this->getCurrentPolicyID () );
		if (! array_key_exists ( 'loggedIn', $_SESSION )) {
			$this->loggedOn = false;
		} else {
			$this->loggedOn = $_SESSION ['loggedIn'];
		}
	}
	
	/**
	 * เช็คว่า Logon แล้วหรือไม่
	 *
	 * @return boolean
	 */
	public function isLoggedOn() {
		return $this->loggedOn;
	}
	
	/**
	 * โหลดข้อมูลที่จำเป็น
	 *
	 */
	public function loadEntities() {
		//global $errorLog;
		
		//if account object is already load, unset it
		if (is_object ( $this->account ) || isset ( $this->account )) {
			unset ( $this->account );
		}
		
		//if role object is already load, unset it
		if (is_object ( $this->role ) || isset ( $this->role )) {
			unset ( $this->role );
		}
		
		//if org object is already load, unset it
		if (is_object ( $this->org ) || isset ( $this->org )) {
			unset ( $this->org );
		}
		
		//create essential entities instance
		$this->account = new AccountEntity ( );
		$this->role = new RoleEntity ( );
		$this->org = new OrganizeEntity ( );
		
		//loading account entity
		if (! $this->account->Load ( "f_acc_id = '{$this->getCurrentAccID()}'" )) {
			//$errorLog->log ( "Unable to load account information [{$this->getCurrentAccID()}]", Zend_Log::CRIT );
		}
		
		//loading role entity
		if (! $this->role->Load ( "f_role_id = '{$this->getCurrentRoleID()}'" )) {
			//$errorLog->log ( "Unable to load role information [{$this->getCurrentRoleID()}]", Zend_Log::CRIT );
		}
	}
    
	/**
	 * ทำการโหลด Account ปัจจุบันลงใน Object
	 *
	 * @return Object
	 */
    public function getCurrentAccountEntity() {
        return $this->account;
    }
	
	/**
	 * get last change password
	 *
	 * @return string
	 */
	public function getLastChangePassword() {
		global $lang;
		global $util;
		
		$this->loadEntities ();
		if ($this->account->f_last_change_pwd == 0) {
			return $lang ['label'] ['neverchange'];
		} else {
			return $util->getDateString ( $this->account->f_last_change_pwd );
		}
	}
	
	/**
	 * get current working year
	 *
	 * @return int
	 */
	public function getCurrentYear() {
		if (array_key_exists ( 'workingYear', $_SESSION )) {
			return $_SESSION ['workingYear'];
		} else {
			$_SESSION ['workingYear'] = date ( 'Y' );
			return $_SESSION ['workingYear'];
		}
	}
	
	/**
	 * get current account type
	 *
	 * @return int
	 */
	public function getCurrentAccountType() {
		if (array_key_exists ( 'accType', $_SESSION )) {
			return $_SESSION ['accType'];
		} else {
			return false;
		}
	}
	
	/**
	 * get current policy id
	 *
	 * @return int
	 */
	public function getCurrentPolicyID() {
		return $this->role->f_gp_id;
	}
	
	/**
	 * get current account ID
	 *
	 * @return int
	 */
	public function getCurrentAccID() {
		if (array_key_exists ( 'accID', $_SESSION )) {
			return $_SESSION ['accID'];
		} else {
			return 0;
		}
	}
	
	/**
	 * get Current user login
	 *
	 * @return string
	 */
	public function getCurrentUserLogin() {
		if (is_object ( $this->account )) {
			return $this->account->f_login_name;
		} else {
			return "";
		}
	}
	
	/**
	 * get current role ID
	 *
	 * @return int
	 */
	public function getCurrentRoleID() {
		if (array_key_exists ( 'roleID', $_SESSION )) {
			return $_SESSION ['roleID'];
		} else {
			return 0;
		}
	}
	
	/**
	 * get current organization unit ID
	 *
	 * @return unknown
	 */
	public function getCurrentOrgID() {
		if (array_key_exists ( 'roleID', $_SESSION )) {
			$roleID = $_SESSION ['roleID'];
		} else {
			return false;
		}
		$role = new RoleEntity ( );
		if (! $role->Load ( "f_role_id = '{$roleID}'" )) {
			return false;
		} else {
			$org = new OrganizeEntity ( );
			if (! $org->Load ( "f_org_id = '{$role->f_org_id}'" )) {
				return false;
			} else {
				if ($org->f_org_type == 0) {
					return $role->f_org_id;
				} else {
					$orgParent = new OrganizeEntity ( );
					if (! $orgParent->Load ( "f_org_id = '{$org->f_org_pid}'" )) {
						return false;
					} else {
						return $orgParent->f_org_id;
					}
				}
			}
		}
	}
	
	/**
	 * Is a governer role
	 *
	 * @return boolean
	 */
	public function isGoverner() {
		if($this->isDegradeMode()) return false;
		if ($this->role->f_is_governer == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function isSarabanMaster() {
        global $policy;
        if($this->isDegradeMode()) return false;
        return $policy->isSarabanMaster();
		//if()
	}

	public function isSG() {
        global $policy;
        if($this->isDegradeMode()) return false;
        return $policy->isSG();
		//if()
	}
	
	/**
	 * Check for concurrent
	 *
	 * @return boolean
	 */
	public function checkConcurrent() {
		global $lang;
		if (array_key_exists ( 'loggedIn', $_SESSION ) && $_SESSION ['loggedIn'] == true) {
			$sessionID = session_id ();
			
			if (! $this->concurrent->Load ( "f_session_id = '{$sessionID}'" )) {
				echo $lang ['session'] ['expired'];
				exit ();
			} else {
				$now = mktime ();
				$this->concurrent->f_last_access = $now;
				$this->concurrent->Update ();
				return true;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * ตรวจว่า Access Time ถูกต้อง
	 *
	 * @return boolean
	 */
	
	public function accessTimeValid() {
		if (! $this->isLoggedOn ()) {
			//special case (not logged in ,so discard checking)
			return true;
		}
		if ($this->account->f_access_to == '00:00' && $this->account->f_access_from = '00:00') {
			return true;
		}
		
		list ( $fromHour, $fromMin ) = split ( ":", $this->account->f_access_from );
		list ( $toHour, $toMin ) = split ( ":", $this->account->f_access_to );
		$accessFrom = mktime ( $fromHour, $fromMin );
		$accessTo = mktime ( $toHour, $toMin );
		
		$currentTime = time ();
		
		if ($accessFrom < $currentTime && $currentTime < $accessTo) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ตรวจว่า Password หมดอายุ
	 *
	 * @return boolean
	 */
	public function passwordExpire() {
		$currentTimestamp = time ();
		if ($this->account->f_force_change_pwd_timestamp ==0) {
			return false;
		}
		if ($currentTimestamp >= $this->account->f_force_change_pwd_timestamp) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * บังคับไปหน้าจอเปลี่ยน Password
	 *
	 */
	public function forceChangePassword() {
		global $util;
		if ($this->account->f_force_change_pwd_timestamp != 0) {
			$currentTimestamp = time ();
			if ($currentTimestamp >= $this->account->f_force_change_pwd_timestamp) {
				$util->redirect ( "/force/change-pwd", true );
			}
		}
	}
	
	/**
	 * ตรวจวันหมดอายุ
	 *
	 */
	public function checkExpire() {
		global $util;
		if (( bool ) $this->account->f_is_expired) {
			$util->redirect ( '/force/expired' );
		}
		
		$currentTimestamp = time ();
		if (! is_null ( $this->account->f_expired ) && $this->account->f_expired != 0) {
			if ($currentTimestamp > $this->account->f_expired) {
				$this->account->f_is_expired = 1;
				$this->account->Update ();
				$util->redirect ( '/force/expired' );
			}
		}
	}
    
	/**
	 * เปลี่ยนหรัสผ่าน AD
	 *
	 * @param string $username
	 * @param string $oldPass
	 * @param string $newPass
	 * @return boolean
	 */
    public function ADChangePassword($username,$oldPass,$newPass){
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL,"http://192.168.110.91/password/dompass.exe");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username={$username}&password={$oldPass}&newpass1={$newPass}&newpass2={$newPass}");
            //--- Start buffering
            //ob_start();
            $result = curl_exec ($ch);
            //--- End buffering and clean output
            //ob_end_clean();
            curl_close ($ch); 

            //if(eregi("Your password has been changed successfully",$result)) {
            return true;
            //} else {
            //    return false;
            //} 
            //echo $result;
    }
	
    /**
     * เปลี่ยนรหัสผ่าน
     *
     * @param string $oldPassword
     * @param string $newPassword
     * @return boolean
     */
	public function changePassword($oldPassword, $newPassword) {
        if($this->account->f_ldap_bind == 0) {
		    if (md5 ( $oldPassword ) == $this->account->f_login_password) {
			    $this->account->f_login_password = md5 ( $newPassword );
			    $this->account->f_force_change_pwd_timestamp = 0;
			    $this->account->f_last_change_pwd = time ();
			    $this->account->Update ();
			    return true;
		    } else {
			    return false;
		    }
        } else {
            return $this->ADChangePassword($this->account->f_login_name,$oldPassword,$newPassword);
        }
	
	}
	
	public function isDegradeMode() {
		if($_SESSION['degradeMode']==1) {
			return true;
		}
		return false;
	}
}
