<?php
/**
 * Portlet : ทะเบียนงาน ISO
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class ISOPortlet {
    public function __construct() {
        //include_once('ISOApproval.php');
    }
	public function getUI() {
        global $lang;
		global $config;
		global $sessionMgr;
		
		checkSessionPortlet();
		
		$sessionMgr->checkConcurrent();
        $ISOApproval = new ISOApproval();
        
        
        $script = "<script type=\"text/javascript\">
        </script>";
        
		$html = "{$script}
        <table width=\"100%\">
			<tr>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_receivedMainFolder','{$lang ['workitem'] ['awaitingItem']}','ReceivedItemPortlet','getUI');\">
					<span><img src=\"/{$config ['appName']}/images/th/ISOIssue.png\"/></span><br/>
					<span>จำนวน {$ISOApproval->getIssueCount()} รายการ</span>
				</td>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_trackMainFolder','{$lang ['workitem'] ['trackItem']}','TrackItemPortlet','getUI');\">
					<span><img src=\"/{$config ['appName']}/images/th/DARRequest.png\"/></span><br/>
					<span>จำนวน {$ISOApproval->getDARRequestCount()} รายการ</span>
				</td>				
			</tr>
			<tr>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_orderAssignMainFolder','{$lang ['workitem'] ['orderItem']}','OrderAssignedPortlet','getUI');\">
					<span><img src=\"/{$config ['appName']}/images/th/QMRRequest.png\"/></span><br/>
					<span>จำนวน {$ISOApproval->getQMRRequestCount()} รายการ</span>
				</td>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_orderReceivedMainFolder','{$lang ['workitem'] ['receivedItem']}','OrderReceivedPortlet','getUI');\">
					<span><img src=\"/{$config ['appName']}/images/th/ISORequest.png\"/></span><br/>
					<span>จำนวน {$ISOApproval->getISORequestCount()} รายการ</span>
				</td>
			</tr>
			<tr>
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_completedMainFolder','{$lang ['workitem'] ['completedItem']}','CompletedItemPortlet','getUI');\">
                    <span><img src=\"/{$config ['appName']}/images/th/PublishPending.png\"/></span><br/>
                    <span>จำนวน {$ISOApproval->getPublishRequestCount()} รายการ</span>
                </td>
                <td></td>
                <!--
				<td style=\"cursor: pointer;\" onclick=\"openPortletToMainTab('tab_committedMainFolder','{$lang ['workitem'] ['committedItem']}','CommittedItemPortlet','getUI');\">
					<span><img src=\"/{$config ['appName']}/images/th/ClosedItem.png\"/></span><br/>
					<span>จำนวน 0 รายการ</span>
				</td>
                -->
			</tr>			
		</table>";
		echo $html;
	}
}