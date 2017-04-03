<?php
/**
 * Class LoginController
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 *
 */

class LoginController extends ECMController {
	
	/**
	 * ZF Initialization method
	 * loading a required Class for Login/Logout operation
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'login' );
		
	//include_once 'Account.Entity.php';
	//include_once 'Concurrent.Entity.php';
	

	//include_once 'AuthLog.php';
	

	//include_once 'AuthenLDAP.php';
	//include_once 'AuthenSQL.php';
	}
	
	/**
	 * Single Sign On
	 *
	 */
	public function ssoAction() {
		global $util;
		global $config;
		global $requestTimestamp;
		
		//var_dump($_SESSION);
		//die();
		//die('xxx');
		//cleanup session
		//get account by login name
		$account = new AccountEntity ( );
		
		//if unable to load account entity,the login name is invalid,log and route to login panel again
		if (! $account->Load ( "f_login_name = '{$_SESSION['ssoUser']}'" )) {
			Logger::log ( 0, 0, "SSO Module Trying login by non-existence credential [{$_SESSION['ssoUser']}] from IP:{$util->getIPAddress()}", true, true );
			$util->redirect ( "/login/general?reason=4", true );
			//$this->_redirector->gotoUrl ( '/login/general?reason=4' );
		} else {
			Logger::log ( 0, 0, "SSO Module logon successfully credential [{$_SESSION['ssoUser']}] from IP:{$util->getIPAddress()}", true, true );
			
			$auth = new AuthenSSO ( );
			
			//get authentication result
			$authResult = $auth->authenticate ( $_SESSION ['ssoUser'], '', '' );
			
			//get login timestamp from global request timestamp
			$loginTime = $requestTimestamp;
			
			//create Authentication Log instance
			$authLog = new AuthLog ( );
			
			$authLog->login ( $account->f_acc_id, $loginTime, 1 );
			$account->f_tries = 0;
			$account->Update ();
			
			//broadcast login activity to XMPP system
			if ($config ['notify'] ['xmppWelcome']) {
				$util->broadcastJabberMessage ( "{$account->f_name} {$account->f_last_name} �������к�" );
			}
			Logger::log ( 0, 0, "User [{$account->f_login_name}] is successfully logged in from IP: [{$util->getIPAddress()}]", true, false );
			
			//check Protocol and route to default application console
			//if ($_SERVER ['SERVER_PORT'] == 443) {
			$util->redirect ( "/{$config['defaultAppConsole']}" );
			//} else {
		//	$util->redirect ( "/{$config['defaultAppConsole']}" );
		//}
		}
	}
	/**
	 * action /process �ӡ�û����żš�� Login
	 *
	 */
	public function processAction() {
		global $requestTimestamp;
		global $config;
		global $util;
		
		if (array_key_exists ( 'fldSalt', $_POST )) {
			//TODO: should filter a $_POST data injected from login panel first,for security stuff!!!
			$fldSalt = $_POST ['fldSalt'];
			$fldUid = $_POST ['fldUid'];
			$fldPwd = $_POST ['fldPwd'];
			
			$account = new AccountEntity ( );
			
			//if unable to load account entity,the login name is invalid,log and route to login panel again
			if (! $account->Load ( "f_login_name = '{$fldUid}'" )) {
				Logger::log ( 0, 0, "Trying login by non-existence credential [$fldUid] from IP:{$util->getIPAddress()}", true, true );
				$util->redirect ( "/login/general?reason=4", true );
			} else {
				//if the account is bind to AD/LDAP create $auth as LDAP authenticatin client
				if ($account->f_ldap_bind) {
					$auth = new AuthenLDAP ( );
				} else {
					//for SQL authentication process create $auth as SQL authentiction client
					$auth = new AuthenSQL ( );
					//if not use CHAPS login system,hash a provide password with md5() to match the database encrypted field
					if (! $config ['useCHAPSLogin']) {
						$fldPwd = md5 ( $fldPwd );
					}
				}
				
				//get authentication result
	//			echo '<!--'.$fldPwd.'    '.$fldSalt.'-->';exit();
				$authResult = $auth->authenticate ( $fldUid, $fldPwd, $fldSalt );
				//var_dump($authResult);
				//var_dump($auth);
				//die();
				//get login timestamp from global request timestamp
				$loginTime = $requestTimestamp;
				
				//create Authentication Log instance
				$authLog = new AuthLog ( );
				
				//if successfully login
				if ($authResult ['success']) {
					//if account is locked?
					if ($account->f_tries >= $config ['tries']) {
						//Locked,do authentication log,error log and route to login panel
						$authLog->login ( $account->f_acc_id, $loginTime, 0 );
						$_SESSION ['loggedIn'] = false;
						Logger::log ( 0, 0, "User [{$fldUid}] is successfully login but account is already locked down from IP:{$util->getIPAddress()}", true, true );
						$util->redirect ( "/login/general?reason=5", true );
						//$this->_redirector->gotoUrl ( '/login/general?reason=' . 5 );
					} else {
						//Not locked,do authentication log,common log,route to application console
						$authLog->login ( $account->f_acc_id, $loginTime, 1 );
						//reset login attempt
						$account->f_tries = 0;
						$account->Update ();
						//broadcast login activity to XMPP system
						if ($config ['notify'] ['xmppWelcome']) {
							$util->broadcastJabberMessage ( "{$account->f_name} {$account->f_last_name} �������к�" );
						}
						Logger::log ( 0, 0, "User [{$account->f_login_name}] is successfully logged in from IP: [{$util->getIPAddress()}]", true, false );
						//check Protocol and route to default application console
						if ($_SERVER ['SERVER_PORT'] == 443) {
							//header ( "Location: http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/{$config['defaultAppConsole']}" );
							//$util->redirect ( "/login/general" );
							$this->showProcessLogin ();
						} else {
							//$util->redirect ( "/{$config['defaultAppConsole']}" );
							if(isset($_GET['goto']) && $_GET['goto'] == 'TransactionRoom'){
								$this->_redirector->gotoUrl ( 'https://backoffice.oic.or.th/workflow/popup.php?action=intFunction&module=TransactionRoom' );
							}else{
								$this->showProcessLogin ();
							}
							//$this->_redirector->gotoUrl ( "/{$config['defaultAppConsole']}" );
						}
					}
				} else {
					//Unsuccessful login
					if ($authResult ['param'] != 2 && $authResult ['param'] !=3 && $authResult ['param'] !=7 ) {
						//Update failed attempt
						$account->f_tries += 1;
						$account->Update ();
					}
					
					//add to authentiction log
					$authLog->login ( $account->f_acc_id, $loginTime, 0 );
					
					//clear login flag
					$_SESSION ['loggedIn'] = false;
					
					//Switch by authentication result and log to error log
					switch ($authResult ['param']) {
						case 1 :
							//wrong password
							Logger::log ( 0, 0, "User [{$account->f_login_name}] is provide a wrong password from IP: [{$util->getIPAddress()}]", true, true );
							break;
						case 2 :
							//concurrent exists
							Logger::log ( 0, 0, "User [{$account->f_login_name}] is already logged in from IP: [{$util->getIPAddress()}]", true, true );
							break;
						case 3 :
							//No roles exists
							Logger::log ( 0, 0, "User [{$account->f_login_name}] has no role(s) but successfully logged in from IP: [{$util->getIPAddress()}]", true, true );
							break;
						case 4 :
							//No account exists
							Logger::log ( 0, 0, "Trying login by non-existence credential [$fldUid] from IP:{$util->getIPAddress()}", true, true );
							break;
						case 5 :
							//Account Locked
							Logger::log ( 0, 0, "User [{$account->f_login_name}] is locked down from IP: [{$util->getIPAddress()}]", true, true );
							break;
						case 6 :
							//Account Locked #2
							Logger::log ( 0, 0, "User [{$account->f_login_name}] is provide a wrog passed word and exceed limit ,account lock down from IP: [{$util->getIPAddress()}]", true, true );
							break;
					}
					
					//If tries > maximum configured tries
					if ($account->f_tries >= $config ['tries']) {
						//log to error log & redirect to login panel
						Logger::log ( 0, 0, "User [{$account->f_login_name}] is provide a wrog passed word and exceed limit ,account lock down from IP: [{$util->getIPAddress()}]", true, true );
						if ($authResult ['param'] == 6) {
							$util->redirect ( "/login/general?reason=6", true );
							//$this->_redirector->gotoUrl ( '/login/general?reason=' . 6 );
						} else {
							$util->redirect ( "/login/general?reason=" . $authResult ['param'] . "&ip=" . $authResult ['ip'], true );
							//$this->_redirector->gotoUrl ( '/login/general?reason=' . $authResult ['param'] . "&ip=" . $authResult ['ip'] );
						}
					} else {
						//redirect to login panel
						if ($authResult ['param'] != 2) {
							$util->redirect ( '/login/general?reason=' . $authResult ['param'], true );
							//$this->_redirector->gotoUrl ( '/login/general?reason=' . $authResult ['param'] );
						} else {
							$util->redirect ( '/login/general?reason=' . $authResult ['param'] . "&ip=" . $authResult ['ip'], true );
							//$this->_redirector->gotoUrl ( '/login/general?reason=' . $authResult ['param'] . "&ip=" . $authResult ['ip'] );
						}
					}
				}
			}
		}
	}
	
	/**
	 * Logout action
	 * perform an logout action & writing log to common log/error log
	 */
	public function logoutAction() {
		global $sessionMgr;
		global $util;
		global $requestTimestamp;
		if(isset($_GET['goto']) && $_GET['goto'] == 'TransactionRoom'){
			$backTo = "?goto=TransactionRoom";
		}

		//Clear Temporary path of logged-out user
		$accID = $sessionMgr->getCurrentAccID ();
		clearTempPath ( $accID );
		
		//inherit required objects (Concurrent,Authentication Log,Account)
		$concurrent = new ConcurrentEntity ( );
		$authLog = new AuthLog ( );
		$account = new AccountEntity ( );
		
		//get session id
		$sessionID = session_id ();
		
		//get time from default request timestamp
		$time = $requestTimestamp;
		
		//if unable to load account
		if (! $account->Load ( "f_acc_id = '{$sessionMgr->getCurrentAccID()}'" )) {
			//error loggin as concurrent bug
			$msg = "Invalid Concurrent/Concurrent Bug ???";
			Logger::log ( 0, 0, $msg );
			
			$this->showProcessLogout ($backTo);
			die ();
			$util->redirect ( "/login/general", true );
			//$this->_redirector->gotoUrl ( '/login/general' );
		} else {
			//save logout activity to authlog
			$authLog->logout ( $sessionMgr->getCurrentAccID (), $time );
			
			//save logout activity to common log
			Logger::log ( 0, 0, "User [{$account->f_login_name}] is successfully logged out from IP: [{$util->getIPAddress()}]", 1, 0 );
			
			//if unable to load concurrent assume as already logout or session timeout
			if (! $concurrent->Load ( "f_session_id = '{$sessionID}'" )) {
				//clear login flag in session
				$_SESSION ['loggedIn'] = false;
				
				$this->showProcessLogout ($backTo);
				die ();
				//route to login page
				$util->redirect ( "/login/general", true );
				//$this->_redirector->gotoUrl ( '/login/general' );
			} else {
				//clear login flag in session
				$_SESSION ['loggedIn'] = false;
				
				//delete concurrent
				$concurrent->Delete ();
				
				$this->showProcessLogout ($backTo);
				die ();
				//route to login page
				$util->redirect ( "/login/general", true );
				//$this->_redirector->gotoUrl ( '/login/general' );
			}
		}
	}
	
	/**
	 * re-route an default action to general action
	 *
	 */
	public function indexAction() {
		global $util;
		$util->redirect ( '/login/general', true );
		//$this->_redirector->gotoUrl (  );
	}
	
	/**
	 * �ʴ�˹�Ҩ� Login
	 *
	 */
	public function showLogin() {
		global $cache;
		global $config;
		global $license;
		global $lang;
		global $util;
		
		if ($license->noLicense ()) {
			$util->redirect ( "/NoLicense" );
			//$this->_redirector->gotoUrl ( '/NoLicense' );
		}
		
		if (array_key_exists ( 'reason', $_GET )) {
			$reason = $_GET ['reason'];
		} else {
			$reason = 0;
		}
		
		//assign login template variable split by reason
		switch ($reason) {
			case 1 :
				$this->ECMView->assign ( 'loginMessage', $lang ['login'] [1] );
				break;
			case 2 :
				$ip = $_GET ['ip'];
				$this->ECMView->assign ( 'loginMessage', $lang ['login'] [2] . " IP Address [{$ip}]" );
				break;
			case 3 :
				$this->ECMView->assign ( 'loginMessage', $lang ['login'] [3] );
				break;
			case 4 :
				$this->ECMView->assign ( 'loginMessage', $lang ['login'] [4] );
				break;
			case 5 :
				$this->ECMView->assign ( 'loginMessage', $lang ['login'] [5] );
				break;
			case 6 :
				$this->ECMView->assign ( 'loginMessage', $lang ['login'] [6] );
				break;
			case 7 :
				$this->ECMView->assign ( 'loginMessage', $lang ['login'] [7] );
				break;
			default :
				$this->ECMView->assign ( 'loginMessage', '' );
				break;
		}
		
		//select template & generate Cache ID for screen caching
		if ($reason == 2) {
			$ipforCacheID = str_replace ( ".", "_", $ip );
		}
		$this->ECMView->assign ( 'bg', $config ['bg'] );
		
		if ($config ['LDAPIntegrated']) {
			//LDAP/AD Login Case
			if ($reason != 2) {
				$cacheID = "LDAPLoginHTML_" . $config ['bg'] . "_" . $reason;
			} else {
				$cacheID = "LDAPLoginHTML_" . $config ['bg'] . "_" . $reason . "_" . $ipforCacheID;
			}
			$templateName = $config ['logonScreen'] . '.phtml';
		} else {
			//SQL Login Case
			if ($reason != 2) {
				$cacheID = "SQLLoginHTML_" . $config ['bg'] . "_" . $reason;
			} else {
				$cacheID = "SQLLoginHTML_" . $config ['bg'] . "_" . $reason . "_" . $ipforCacheID;
			}
			$templateName = $config ['logonScreen'] . '.phtml';
		}
		
		//If no cache exists
		if (! ($output = $cache->load ( $cacheID ))) {
			//No cache exists,generate output first and cache it
			Logger::log ( 0, 0, "Cache does not exists [{$cacheID}]", true, false );
			$output = $this->ECMView->render ( $templateName );
			if (! $config ['disableZendCache']) {
				$cache->save ( $output, $cacheID );
			}
		} else {
			//Cache hit
			Logger::log ( 0, 0, "Cache hit [{$cacheID}]", true, false );
		}
		echo $output;
	}
	
	/**
	 * �ʴ�˹�Ҩ� Logout Process
	 *
	 */
	public function showProcessLogout($backTo) {
		$this->ECMView->assign ( 'backTo', $backTo );
		$output = $this->ECMView->render ( "logoutProcess.phtml" );
		echo $output;
		die ();
	}
	
	/**
	 * �ʴ�˹�Ҩ� Login Process
	 *
	 */
	public function showProcessLogin() {
		
		$output = $this->ECMView->render ( "loginProcess.phtml" );
		echo $output;
		die ();
	}
	
	/**
	 * General action provide a login screen to users
	 * has 2 login screen type
	 * 1 - SQL Login (Password encryption with CHAPS Technique ,SSL is not required)
	 * 2 - LDAP/AD Login (No Password encryption,SSL is REQUIRED)
	 */
	public function generalAction() {
		global $config;
		global $util;
		
		//if not on SSL port redirect to SSL port (Mode AD ������ա�� Encrypt Password)
		if ($_SERVER ['SERVER_PORT'] != 443) {
			$this->_helper->sslSwitch ();
			exit ();
		}
		
		$concurrent = new ConcurrentEntity ( );
		/**
		 * if already logged in/session has login flag
		 * check the concurrent first and perform a session clear if not valid
		 * or by pass if concurrent is valid
		 */
		if (array_key_exists ( 'loggedIn', $_SESSION )) {
			if ($_SESSION ['loggedIn'] && array_key_exists ( 'accID', $_SESSION )) {
				if (! $concurrent->Load ( "f_acc_id = '{$_SESSION['accID']}'" )) {
					$_SESSION ['loggedIn'] = false;
					unset ( $_SESSION ['accID'] );
				} else {
					$account = new AccountEntity ( );
					$account->Load ( "f_acc_id = {$_SESSION['accID']}" );
					Logger::log ( 0, 0, "User [{$account->f_login_name}] by-pass login from IP: {$util->getIPAddress()}", true, false );
					$output = $this->ECMView->render ( "loginProcess.phtml" );
					echo $output;
					die ();
				}
			}
		}
		
		$this->showLogin ();
	}
}