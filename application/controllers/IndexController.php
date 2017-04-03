<?php
/**
 * หน้าจอ Console หลัก
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 */

class IndexController extends ECMController {
	/**
	 * Initialization method
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'default' );
		//include all required Class/Entity
		//include_once 'Concurrent.Entity.php';
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Organize.Entity.php';
	}
	
	/**
	 * Default action (Main ECM Console)
	 *
	 */
	public function indexAction() {
		global $cache;
		global $config;
		global $license;
		global $logger;
		global $sessionMgr;
		global $util;
		
		if ($_SERVER ['SERVER_PORT'] == 443) {
            $this->_helper->nonSslSwitch();
            exit();
        }
		
		if ($license->noLicense ()) {
			$util->redirect ( "/NoLicense" );
			die ();
			#$this->_redirector->gotoUrl('/NoLicense');
		}
		/*
		if ($config ['clusterMode']) {
			if (! $license->check ( 'CLUSTER' )) {
				$util->redirect ( "/no-license/Cluster" );
				die ();
			}
		}
		*/
		//If not logged in re-route to Login panel
		if (! array_key_exists ( 'loggedIn', $_SESSION )) {
			//$this->_redirector->gotoUrl ( "/Login" );
			$util->redirect ( "/login/general", true );
		} else {
			$sessionMgr->checkExpire ();
			$sessionMgr->forceChangePassword ();
			if (! $sessionMgr->accessTimeValid ()) {
				$util->redirect ( "/access-denied/access-time" );
				die ();
			}
			//Login flag found,Check concurrent validity
			$concurrent = new ConcurrentEntity ( );
			//If concurrent not valid,re-route to Login Panel
			if (! $concurrent->Load ( "f_acc_id = '{$_SESSION['accID']}'" )) {
				//$this->_redirector->gotoUrl ( '/Login' );
				$util->redirect ( "/login/general", true );
			} else {
				//Concurrent is valid,start loading ECM Main Console
				$account = new AccountEntity ( );
				$role = new RoleEntity ( );
				$org = new OrganizeEntity ( );
				
				$account->Load ( "f_acc_id = '{$_SESSION['accID']}'" );
				$role->Load ( "f_role_id = '{$_SESSION['roleID']}'" );
				$org->Load ( "f_org_id = '{$role->f_org_id}'" );
				
				//Assign Data to User Information Panel
				if ($org->f_org_type == 1) {
					$orgMain = new OrganizeEntity ( );
					$orgMain->Load ( "f_org_id = '{$org->f_org_pid}'" );
					$this->ECMView->assign ( 'orgname', $orgMain->f_org_name );
					$this->ECMView->assign ( 'orggroup', $org->f_org_name );
				} else {
					$this->ECMView->assign ( 'orgname', $org->f_org_name );
					$this->ECMView->assign ( 'orggroup', '' );
				}
				$this->ECMView->assign ( 'fullname', $account->f_name . " " . $account->f_last_name );
				$this->ECMView->assign ( 'rolename', $role->f_role_name );
				$this->ECMView->assign ( 'workingYear', $_SESSION ['workingYear'] + 543 );

				//var_dump($_GET);
				if($config['portletLayoutMode'] == 'DMS') {
					$this->ECMView->assign ( 'PLM', "?PLM=DMS" );
				} else {
					$this->ECMView->assign ( 'PLM', "" );
				}
				
				//define console cache ,seperated by account
				$cacheID = "console_" . $account->f_acc_id;
				
				//if cache does not exists,generate it and save it to cache
				if (! ($output = $cache->load ( $cacheID ))) {
					Logger::log ( 0, 0, "Cache does not exists [$cacheID]", true, false );
					$output = $this->ECMView->render ( 'console.phtml' );
					if (! $config ['disableZendCache']) {
						$cache->save ( $output, $cacheID );
					}
				} else {
					Logger::log ( 0, 0, "Cache hit [$cacheID]", true, false );
				}
				
				Logger::log ( 0, 0, "User [{$sessionMgr->getCurrentUserLogin()}] has open a main console", true, false );
				echo $output;
			}
		}
	}
}  
