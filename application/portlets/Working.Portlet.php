<?php
/**
 * Portlet : เอกสารที่รอดำเนินการ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class WorkingPortlet {
public function __construct() {
        //include_once("DFTransaction.php");
    }
	public function getUI() {
        global $lang;
		global $config;
		global $sessionMgr;
		global $license;
				
		checkSessionPortlet();
        
        $jsOperation = "";   
        $scriptDetail = "";
		
		$jsOperation .= "
        Ext.get('trackFolderSPAN').on('click',function() { 
            Ext.MessageBox.show({
                msg: 'Checking Session',
                progressText: 'Processing...',
                width:300,
                wait:true,
                waitConfig: {interval:200},
                icon:'ext-mb-download'
            });
            Ext.Ajax.request({
                url: '/{$config ['appName']}/session.php',
                method: 'POST',
                success: function(o){
                    Ext.MessageBox.hide();
                    var r = Ext.decode(o.responseText);
                    if(r.redirectLogin == 1) {
                        sessionExpired(); 
                    } else   {
                        openPortletToMainTab('tab_trackMainFolder','{$lang ['workitem'] ['trackItem']}','TrackItemPortlet','getUI');
                    }
                }
            });
        },this); 
        
        Ext.get('orderAssignSPAN').on('click',function() { 
            Ext.MessageBox.show({
                msg: 'Checking Session',
                progressText: 'Processing...',
                width:300,
                wait:true,
                waitConfig: {interval:200},
                icon:'ext-mb-download'
            });
            Ext.Ajax.request({
                url: '/{$config ['appName']}/session.php',
                method: 'POST',
                success: function(o){
                    Ext.MessageBox.hide();
                    var r = Ext.decode(o.responseText);
                    if(r.redirectLogin == 1) {
                        sessionExpired(); 
                    } else   {
                        openPortletToMainTab('tab_orderAssignMainFolder','{$lang ['workitem'] ['orderItem']}','OrderAssignedPortlet','getUI');
                    }
                }
            });
        },this); 
        
        Ext.get('orderReceivedSPAN').on('click',function() { 
            Ext.MessageBox.show({
                msg: 'Checking Session',
                progressText: 'Processing...',
                width:300,
                wait:true,
                waitConfig: {interval:200},
                icon:'ext-mb-download'
            });
            Ext.Ajax.request({
                url: '/{$config ['appName']}/session.php',
                method: 'POST',
                success: function(o){
                    Ext.MessageBox.hide();
                    var r = Ext.decode(o.responseText);
                    if(r.redirectLogin == 1) {
                        sessionExpired(); 
                    } else   {
                        openPortletToMainTab('tab_orderReceivedMainFolder','{$lang ['workitem'] ['receivedItem']}','OrderReceivedPortlet','getUI');
                    }
                }
            });
        },this); 
        
        Ext.get('orderCompleteSPAN').on('click',function() { 
            Ext.MessageBox.show({
                msg: 'Checking Session',
                progressText: 'Processing...',
                width:300,
                wait:true,
                waitConfig: {interval:200},
                icon:'ext-mb-download'
            });
            Ext.Ajax.request({
                url: '/{$config ['appName']}/session.php',
                method: 'POST',
                success: function(o){
                    Ext.MessageBox.hide();
                    var r = Ext.decode(o.responseText);
                    if(r.redirectLogin == 1) {
                        sessionExpired(); 
                    } else   {
                        openPortletToMainTab('tab_completedMainFolder','{$lang ['workitem'] ['completedItem']}','CompletedItemPortlet','getUI');
                    }
                }
            });
        },this); 
        
        Ext.get('orderComplete2SPAN').on('click',function() { 
            Ext.MessageBox.show({
                msg: 'Checking Session',
                progressText: 'Processing...',
                width:300,
                wait:true,
                waitConfig: {interval:200},
                icon:'ext-mb-download'
            });
            Ext.Ajax.request({
                url: '/{$config ['appName']}/session.php',
                method: 'POST',
                success: function(o){
                    Ext.MessageBox.hide();
                    var r = Ext.decode(o.responseText);
                    if(r.redirectLogin == 1) {
                        sessionExpired(); 
                    } else   {
                        openPortletToMainTab('tab_committedMainFolder','{$lang ['workitem'] ['committedItem']}','CommittedItemPortlet','getUI');
                    }
                }
            });
        },this); 
        
        ";
        
		if($sessionMgr->isGoverner()) {
            $jsOperation .= "
            Ext.get('awaitingSPAN').on('click',function() { 
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/session.php',
                    method: 'POST',
                    success: function(o){
                        Ext.MessageBox.hide();
                        var r = Ext.decode(o.responseText);
                        if(r.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            openPortletToMainTab('tab_receivedMainFolder','{$lang ['workitem'] ['awaitingItem']}','ReceivedItemPortlet','getUI');  
                        }
                    }
                });
            },this); 
            ";
			$awaitingSPAN = "<span class=\"portletCommandSpan\" id=\"awaitingSPAN\" ><img src=\"/{$config ['appName']}/images/th/awaiting2.png\"/></span><br/>";
			//$scriptDetail .="";
		} else {
			$awaitingSPAN = "<span><img src=\"/{$config ['appName']}/images/th/awaiting2Disabled.png\"/></span><br/>";
		}
		
		if($sessionMgr->getCurrentOrgID() == 374) {
            $jsOperation .= "
            Ext.get('reserveBookSPAN').on('click',function() { 
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/session.php',
                    method: 'POST',
                    success: function(o){
                        Ext.MessageBox.hide();
                        var r = Ext.decode(o.responseText);
                        if(r.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            openPortletToMainTab('tab_reserveBookFolder','{$lang ['workitem'] ['reserveBook']}','ReserveBookPortlet','getUI');
                        }
                    }
                });
            },this); 
            ";
			$reserveBookSPAN = "<span  class=\"portletCommandSpan\"  id=\"reserveBookSPAN\"><img src=\"/{$config ['appName']}/images/th/ReserveBook.png\"/></span><br/>";
		} else {
			$reserveBookSPAN = "<span><img src=\"/{$config ['appName']}/images/th/ReserveBookDisabled.png\"/></span><br/>";
		}
		
		$sessionMgr->checkConcurrent();
        $dfTrans = new DFTransaction();
        
        $script = "<script type=\"text/javascript\">
        {$scriptDetail}
        
        {$jsOperation}
        
        </script>";
        
		$html = "{$script}
        <table width=\"100%\">
			<tr>
				<td>
					{$awaitingSPAN}
					<span>{$lang['common']['amount']} {$dfTrans->getPersonalReceivedCount()} {$lang['common']['item']}</span>
				</td>
				<td>
					<span  class=\"portletCommandSpan\"  id=\"orderAssignSPAN\"><img src=\"/{$config ['appName']}/images/th/OrderItem.png\"/></span><br/>
					<span>{$lang['common']['amount']} {$dfTrans->getOrderAssignedCount()} {$lang['common']['item']}</span>
				</td>
				<td>
					<span  class=\"portletCommandSpan\"   id=\"orderReceivedSPAN\"><img src=\"/{$config ['appName']}/images/th/AssignedItem.png\"/></span><br/>
					<span>{$lang['common']['amount']} {$dfTrans->getOrderReceivedCount()} {$lang['common']['item']}</span>
				</td>
			</tr>
			<tr>
				<td>
					{$reserveBookSPAN}
					<span>{$lang['common']['amount']} {$dfTrans->getReserveBookItem()} {$lang['common']['item']}</span>
				</td>
				<td >
					<span  class=\"portletCommandSpan\"   id=\"orderComplete2SPAN\"><img src=\"/{$config ['appName']}/images/th/ClosedItem.png\"/></span><br/>
					<span>{$lang['common']['amount']} {$dfTrans->getCommittedItem()} {$lang['common']['item']}</span>
				</td>
				<td>
                    <span  class=\"portletCommandSpan\"   id=\"orderCompleteSPAN\"><img src=\"/{$config ['appName']}/images/th/ClosedWork.png\"/></span><br/>
                    <span>{$lang['common']['amount']} {$dfTrans->getCompletedItem()} {$lang['common']['item']}</span>
                </td>                  
			</tr>
			<tr> 
				<td >
					<span  class=\"portletCommandSpan\"  id=\"trackFolderSPAN\"><img src=\"/{$config ['appName']}/images/th/FollowUp.png\"/></span><br/>
					<span>{$lang['common']['amount']} {$dfTrans->getTrackCount()} {$lang['common']['item']}</span>
				</td>
			</tr>			
		</table>";
		echo $html;
		/*
		echo "<span class=\"portletCommandSpan\">เอกสารรอลงรับ 0 {$lang['common']['item']}</span><br/>";
		//เห็นต่างๆกันไปตาม{$lang['common']['item']}เอกสารที่ได้รับมอบให้ดำเนินการ
		echo "<span class=\"portletCommandSpan\">เอกสารรอดำเนินการ 0 {$lang['common']['item']}</span><br>";
		//เห็นต่างๆกันไปตาม{$lang['common']['item']}เอกสารที่ส่งออก
		echo "<span class=\"portletCommandSpan\">เอกสารส่งออก กำลังดำเนินการ 0 {$lang['common']['item']}(ทั้งหมด 0 {$lang['common']['item']})</span><br>";
		//เห็นทุกคนแต่ Click ได้เฉพาะคนที่มีสิทธิ์ลงรับ (ไป Click Acknowledge หรือ รับทราบ)
		echo "<span class=\"portletCommandSpan\">เอกสารถูกส่งกลับ/ตีกลับ 0 {$lang['common']['item']}</span><br>";
		
		echo "<span class=\"portletCommandSpan\">หนังสือเวียนภายใน {$lang['common']['amount']} 0 {$lang['common']['item']}(ทั้งหมด 0 {$lang['common']['item']})</span><br/>";
		echo "<span class=\"portletCommandSpan\">หนังสือเวียนภายนอก {$lang['common']['amount']} 0 {$lang['common']['item']}(ทั้งหมด 0 {$lang['common']['item']})</span><br/>";
		echo "<span class=\"portletCommandSpan\">คำสั่ง {$lang['common']['amount']} 0 {$lang['common']['item']}(ทั้งหมด 0 {$lang['common']['item']})</span><br/>";
		echo "<span class=\"portletCommandSpan\">ประกาศ {$lang['common']['amount']} 0 {$lang['common']['item']}(ทั้งหมด 0 {$lang['common']['item']})</span><br/>";
		*/
	}
}