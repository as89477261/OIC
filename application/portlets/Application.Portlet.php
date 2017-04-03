<?php
/**
 * Portlet : โปรแกรม PMIS (PDMO)
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class ApplicationPortlet {
	public function __construct() {
		//include_once 'Account.Entity.php';	
	}
	
	public function getUI() {
		global $lang;
		global $config;
		global $sessionMgr;
		
		checkSessionPortlet();
		
		$sessionMgr->checkConcurrent ();
		$dfTrans = new DFTransaction ( );
		
		$script = "<script type=\"text/javascript\">
        
        function openPortletToMainTab(tabID,tabTitle,paramPortletClass,paramPortletMethod) {
            var tpAdminForAwating = Ext.getCmp('tpAdmin');
                    
            if(!tpAdminForAwating.findById( tabID)) {
                tpAdminForAwating.add({
                    id: tabID,
                    title: tabTitle,
                    iconCls: 'workflowIcon',
                    autoLoad: {
                        url: '/{$config ['appName']}/portlet/get-portlet-content', 
                        params: {
                            portletClass: paramPortletClass,
                            portletMethod: paramPortletMethod
                        },
                        scripts: true
                    },
                    closable:true
                }).show();
            } else {
                tpAdminForAwating.findById(tabID).show();
            }
        }
        </script>";
		
		//เห็นทุกคน Click ได้เฉพาะคนมีสิทธิ์ลงรับ
		$html = "{$script}
        <table width=\"100%\">
			<tr>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_unreceivedMainFolder','{$lang['application']['carReserve']}','ApplicationPortlet','carReserve');\">
					<span><img src=\"/{$config ['appName']}/images/th/CarReserve.png\"/></span><br/>
					<span>&nbsp;</span>
				</td>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_receivedMainFolder','{$lang['application']['roomReserve']}','ApplicationPortlet','roomReserve');\">
					<span><img src=\"/{$config ['appName']}/images/th/RoomReserve.png\"/></span><br/>
				</td>
			</tr>
			<tr>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_outgoingMainFolder','{$lang['application']['AssetSystem']}','ApplicationPortlet','assetSystem');\">
					<span><img src=\"/{$config ['appName']}/images/th/AssetSystem.png\"/></span><br/>
					<span>&nbsp;</span>
				</td>
				<td style=\"cursor: pointer;\" >
					
				</td>
			</tr>
			<tr>
				<td style=\"cursor: pointer;\" >
				</td>
				<td style=\"cursor: pointer;\" >
				</td>
			</tr>			
		</table>";
		echo $html;
	}
	
	public function carReserve() {
		global $config;
		global $sessionMgr;
		
		$loginName = $sessionMgr->getCurrentAccID();
		$account = new AccountEntity();
		$account->Load("f_acc_id = '{$loginName}'");
		
		$html = "<iframe src=\"{$config['applicationURL']['carReserve']}?loginName={$account->f_login_name}\" height=\"100%\" width=\"100%\"></iframe>";
		echo $html;
	}
	
	public function roomReserve() {
		global $config;
		global $sessionMgr;
		
		$loginName = $sessionMgr->getCurrentAccID();
		$account = new AccountEntity();
		$account->Load("f_acc_id = '{$loginName}'");
		$html = "<iframe src=\"{$config['applicationURL']['roomReserve']}?loginName={$account->f_login_name}\" height=\"100%\" width=\"100%\"></iframe>";
		echo $html;
	}
	
	public function assetSystem() {
		global $config;
		global $sessionMgr;
		
		$loginName = $sessionMgr->getCurrentAccID();
		$account = new AccountEntity();
		$account->Load("f_acc_id = '{$loginName}'");
		$html = "<iframe src=\"{$config['applicationURL']['assetSystem']}?loginName={$account->f_login_name}\" height=\"100%\" width=\"100%\"></iframe>";
		echo $html;
	}
}
