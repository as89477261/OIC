<?php
/**
 * ������ʴ� Visual Track
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 *
 */
class VisualTrackController extends ECMController {
	/**
	 * �ӡ�����ҧ SQL ����Ѻ����������� Track
	 *
	 * @param string $transType
	 * @param string $transID
	 */
	public function getTrackSQL($transType, $transID) {
		$sql = "select 
						f_send_trans_main_id as f_trans_main_id,
						f_send_trans_main_seq as f_trans_main_seq,
						f_sender_org_id,
						f_sender_role_id,
						f_sender_uid,
						f_receiver_org_id,
						f_receiver_role_id,
						f_receiver_uid,
						f_recv_stamp as f_timestamp
						from 
						tbl_trans_df_send
						where
						f_send_trans_main_id = '{$transMainID}'
						UNION ALL
						select
						a.f_recv_trans_main_id as f_trans_main_id,
						a.f_recv_trans_main_seq as f_trans_main_seq,
						b.f_sender_org_id,
						b.f_sender_role_id,
						b.f_sender_uid,
						a.f_receiver_org_id,
						a.f_receiver_role_id,
						a.f_receiver_uid,
						b.f_send_stamp as f_timestamp
						from 
						tbl_trans_df_recv a,tbl_trans_df_send b
						where 
						a.f_send_id = b.f_send_trans_main_id 
						and a.f_send_seq = b.f_send_trans_main_seq
						and a.f_recv_trans_main_id = '{$transMainID}'
						";
	}
	
	/**
	 * action /view �ӡ���ʴ� Visual Track
	 *
	 */
	public function viewAction() {
		global $conn;
		global $config;
		
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Organize.Entity.php';
		//include_once 'DocMain.Entity.php';
		//include_once 'TransMasterDf.Entity.php';
		//include_once 'TransDfRecv.Entity.php';
		
		$transType = $_GET ['transType'];
		$transID = $_GET ['transID'];
		$title = "�Դ����͡���";
		list ( $transMainID, $transSeq, $transSubID ) = explode ( "_", $transID );
		$transMain = new TransMasterDfEntity ( );
		if (! $transMain->Load ( "f_trans_main_id = '{$transMainID}'" )) {
			$bodyHeading = "Unable to track Document Transaction";
		} else {
			$bodyHeading = "Ἱ�Ҿ�Դ�����÷ӧҹ<br/>";
			$getFirstTrans = false;
			if(substr($transMain->f_start_type,0,1) == 'R') {
				$getFirstTrans = true;
				$firstTrans= "";
				$transRecv = new TransDfRecvEntity ( );
						
				$transRecv->Load ( "f_recv_Trans_main_id = '{$transMainID}' and f_recv_trans_main_seq = 1 and f_recv_id = 1");
				$firstTrans .= "ŧ�Ѻ�Ţ��� " . date ( "d/m/Y H:i:s", $transRecv->f_recv_stamp )." (��� : ".$transRecv->f_recv_reg_no.")";
				
			}
			
			$docMain = new DocMainEntity ( );
			if (! $docMain->Load ( "f_doc_id = '{$transMain->f_doc_id}'" )) {
				$bodyHeading .= "�͡��� [{$transMain->f_doc_id}] not found.<br/>{$firstTrans }<br/>";
			} else {
				$bodyHeading .= "�͡��� [{$docMain->f_doc_no}]<br/>{$firstTrans }<br/>";
				$sql = "select 
						f_send_trans_main_id as f_trans_main_id,
						f_send_trans_main_seq as f_trans_main_seq,
                        f_send_id,
                        f_send_seq,
						f_sender_org_id,
						f_sender_role_id,
						f_send_stamp,
						f_sender_uid,
						f_receiver_org_id,
						f_receiver_role_id,
						f_receiver_uid,
						f_received,
						f_callback,
						f_sendback
						from 
						tbl_trans_df_send
						where
						f_send_trans_main_id = '{$transMainID}'		
						order by 
						f_send_id asc
						,f_send_seq asc
						,f_send_stamp asc			
						";
				//$conn->debug = true;
				$rsTrack = $conn->Execute ( $sql );
				$text = "";
				while ( $tmp = $rsTrack->FetchNextObject () ) {
					$senderEntity = new AccountEntity ( );
					$senderRole = new RoleEntity ( );
					
					if ($tmp->F_SENDER_ROLE_ID != 0 && ! is_null ( $tmp->F_SENDER_ROLE_ID )) {
						$senderRole->Load ( "f_role_id = {$tmp->F_SENDER_ROLE_ID}" );
					}
					$senderEntity->Load ( "f_acc_id = '{$tmp->F_SENDER_UID}'" );
					
					$receiverEntity = new AccountEntity ( );
					
					$receiverRole = new RoleEntity ( );
					if ($tmp->F_RECEIVER_ROLE_ID != 0 && ! is_null ( $tmp->F_RECEIVER_ROLE_ID )) {
						$receiverRole->Load ( "f_role_id = {$tmp->F_RECEIVER_ROLE_ID}" );
					}
					$receiverEntity->Load ( "f_acc_id = '{$tmp->F_RECEIVER_UID}'" );
					$receiverOrgEntity = new OrganizeEntity ( );
					$receiverOrgEntity->Load ( "f_org_id = '{$tmp->F_RECEIVER_ORG_ID}'" );
					$senderOrgEntity = new OrganizeEntity ( );
					$senderOrgEntity->Load ( "f_org_id = '{$tmp->F_SENDER_ORG_ID}'" );
					
					if ($tmp->F_SENDER_UID == 0) {
						$senderName = $senderRole->f_role_name;
					} else {
						$senderName = $senderEntity->f_name . " " . $senderEntity->f_last_name;
					
					}
					$senderOrg = $senderOrgEntity->f_org_name;
					$senderDesc = "�� " . date ( 'd/m/Y H:i:s', $tmp->F_SEND_STAMP );
					if ($tmp->F_RECEIVER_UID == 0) {
						$receiverName = $receiverRole->f_role_name;
					} else {
						$receiverName = $receiverEntity->f_name . " " . $receiverEntity->f_last_name;
					}
					
					if ($tmp->F_SENDER_ORG_ID == 0) {
						$senderName = '�ҡ��¹͡';
						$senderOrg = '�ҡ��¹͡';
						$senderDesc = '�觨ҡ��¹͡';
					}
					$receiverOrg = $receiverOrgEntity->f_org_name;
					
					if ($tmp->F_RECEIVED == 1 && ($tmp->F_CALLBACK != 1 && $tmp->F_SENDBACK != 1)) {
						$transRecv = new TransDfRecvEntity ( );
						
						$transRecv->Load ( "f_send_id = '{$transMainID}' and f_send_seq = '{$tmp->F_TRANS_MAIN_SEQ}'" );
						//$receiverDesc = "ŧ�Ѻ���� " . date ( "d/m/Y H:i:s", $transRecv->f_recv_stamp );
						$receiverDesc = "ŧ�Ѻ���� " . date ( "d/m/Y H:i:s", $transRecv->f_recv_stamp )." (��� : ".$transRecv->f_recv_reg_no.")";
					} else {
						$receiverDesc = "�ѧ���ŧ�Ѻ";
						if ($tmp->F_CALLBACK == 1) {
							$receiverDesc = "�֧�׹";
						}
						if ($tmp->F_SENDBACK == 1) {
							$receiverDesc = "�觡�Ѻ";
						}
					}
					$text .= "{$senderOrg}|{$senderName},{$senderDesc}>{$receiverOrg}|{$receiverName},{$receiverDesc}\r\n";
				}
				$filename = "{$config ['appTempPath']}/track/src/" . uniqid ( 'track_' ) . ".txt";
				$fp = fopen ( $filename, 'w+' );
				fwrite ( $fp, $text );
				fclose ( $fp );
				//header("Location: trackPic.php?filename=$filename");
			}
		}
		if (strlen ( trim ( $text ) ) == 0) {
			$output = "�ѧ����Դ Transaction ��к�(ŧ�Ѻ�Ҥ����á)";
		} else {
			if($config['NATEnabled']) {
				$serverIndex = $_SERVER['SERVER_NAME'];
				if(array_key_exists($serverIndex,$config['NATMapping'])){
					$TrackServer = $config['NATMapping'][$serverIndex];
					//$TrackServer = $_SERVER['SERVER_NAME'];
				} else{
					$TrackServer = $_SERVER['SERVER_NAME'];
				}
			} else {
				$TrackServer = $_SERVER['SERVER_NAME'];
			}
			$output = "<img src=\"http://{$TrackServer}/cgi-bin/track.exe?param={$filename}\" />";
			
		}
		$trackOutput = $html = "<html>
			<head>
				<title>{$title} : {$docMain->f_doc_no}</title>
				<script>
				function printWindow(){
					browserVersion = parseInt(navigator.appVersion)
					if (browserVersion >= 4) window.print()
				}
				
				function printWindow2() {
					if (navigator.appName == \"Microsoft Internet Explorer\")
					{ 
					alert('IE');
					     var PrintCommand = '<object ID=\"PrintCommandObject\" WIDTH=0 HEIGHT=0	CLASSID=\"CLSID:8856F961-340A-11D0-A96B-00C04FD705A2\"></object>';
					     document.body.insertAdjacentHTML('beforeEnd', PrintCommand); 
					     PrintCommandObject.ExecWB(6, -1); 
					     PrintCommandObject.outerHTML = \"\"; 
					} 
					else { 
					window.print();
					}
				}
				
				</script>
				<style type=\"text/css\" media=\"print\">
				#header,#footer {
				display:none;
				}
				</style>
				
			</head>
			<body onload=\"window.moveTo(0,0);window.resizeTo(screen.availWidth,screen.availHeight);\">{$bodyHeading}
			<hr/>
			<center>{$output}</center>
			<hr/>
			<a href=\"#\" onclick=\"printWindow();\">Print</a>
			</body>
		</html>";
		echo $html;
	
	}
}

