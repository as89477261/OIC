<?php
/**
 * Portlet : โปรแกรม PMIS (PDMO)
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class OICApplicationPortlet {
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
        function popupOldApp2(name,url){
            var winname   = 'oldApp'+name;
            src = url;
            var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
            'scrollbars=yes,resizable=no,copyhistory=no,height='+screen.height+',width='+screen.width;
            winObj = window.open(src,winname,winconfig);
            winObj.opener = self;
            winObj.focus();
        }

        function openPortletToMainTab(tabID,tabTitle,paramPortletClass,paramPortletMethod) {
            var tpAdminForAwating = Ext.getCmp('tpAdmin');
                    
            if(!tpAdminForAwating.findById( tabID)) {
                tpAdminForAwating.add({
                    id: tabID,
                    title: tabTitle,
                    iconCls: 'workflowIcon',
                    autoLoad: {
                        url: '/{$config ['appName']}/Portlet/get-portlet-content', 
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
				document.getElementById('misFrame').src=\"about:blank\";
				//parent.frames['misFrame'].window.location.reload(); 
				//document.getElementById('misFrame').contentDocument.location.reload(true);
            }
        }
        </script>";
		
		//เห็นทุกคน Click ได้เฉพาะคนมีสิทธิ์ลงรับ
		$html = "{$script}
        <table width=\"100%\">
			<!--
			<tr>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_outgoingMainFolder1','{$lang['application']['carReserve']}','ApplicationPortlet','assetSystem');\">
					<span><img src=\"/{$config ['appName']}/images/th/CarReserve.png\"/></span><br/>
					<span>&nbsp;</span>
				</td>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_outgoingMainFolder2','{$lang['application']['roomReserve']}','ApplicationPortlet','assetSystem');\">
					<span><img src=\"/{$config ['appName']}/images/th/RoomReserve.png\"/></span><br/>
				</td>
			</tr>
			-->
			<tr>
				<!--<td style=\"cursor: pointer;\" onclick=\"popupOldApp2('tab_outgoingMainFolde3r','/ECMDev/Portlet/get-portlet-content/?portletClass=OICApplicationPortlet&portletMethod=assetSystem');\">-->
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_outgoingMainFolder3','PMIS : Backoffice','OICApplicationPortlet','assetSystem');\">
					<span><img src=\"/{$config ['appName']}/images/th/Backoffice.png\"/></span><br/>
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
		$html = "<iframe id=\"\" src=\"{$config['applicationURL']['roomReserve']}?loginName={$account->f_login_name}\" height=\"100%\" width=\"100%\"></iframe>";
		echo $html;
	}
	
	public function assetSystem() {
		global $config;
		global $sessionMgr;
		
		$loginName = $sessionMgr->getCurrentAccID();
		$account = new AccountEntity();
		$account->Load("f_acc_id = '{$loginName}'");
		$url="http://backoffice.oic.or.th/pmis/ECMLogin.php";
		$html = "<iframe id=\"misFrame\" src=\"{$url}?loginName={$account->f_login_name}\" height=\"100%\" width=\"100%\"></iframe>";
		//ob_end_clean();
		//header("Location: {$url}?loginName={$account->f_login_name}");
		echo $html;
	}
}
