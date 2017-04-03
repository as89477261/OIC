<?php
/**
 * Portlet : เอกสารเข้าออก
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class AwaitingDocumentPortlet {
    public function __construct() {
        //include_once("DFTransaction.php");
    }
	public function getUI() {
        global $lang;
		global $config;
		global $policy;
		global $sessionMgr;
		
		checkSessionPortlet();
		
		$sessionMgr->checkConcurrent();
        $dfTrans = new DFTransaction();
        
        
        
        
        $scriptDetail = "";
        if($policy->canReceiveInternal()) {
        	$unreceiveSPAN = "<span class=\"portletCommandSpan\" id=\"unreceivedSPAN\"><img src=\"/{$config ['appName']}/images/th/AwaitReceive.png\"/></span>";
        	$scriptDetail .= "Ext.get('unreceivedSPAN').on('click',function() {
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                
                \$j.ajax({
                	type: 'POST',
					url: '/{$config ['appName']}/session.php',
					dataType: 'json',
					success: function(o){
                        Ext.MessageBox.hide();
                        if(o.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            openPortletToMainTab('tab_unreceivedMainFolder','{$lang['workitem']['unreceivedItem']}','UnreceiveItemPortlet','getUI');
                        }
                    }
				});
			},this);";
        } else {
        	$unreceiveSPAN = "<span><img src=\"/{$config ['appName']}/images/th/AwaitReceiveDisabled.png\"/></span>";
        }
        
        if($policy->canSendInternal() || $policy->canSendExternal() || $policy->canSendExternalGlobal()) {
        	$sendbackSPAN = "<span class=\"portletCommandSpan\" id=\"sendbackSPAN\"><img src=\"/{$config ['appName']}/images/th/sendBack.png\"/></span>";
        	$scriptDetail .= "Ext.get('sendbackSPAN').on('click',function() {
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                \$j.ajax({
                	type: 'POST',
					url: '/{$config ['appName']}/session.php',
					dataType: 'json',
					success: function(o){
                        Ext.MessageBox.hide();
                        if(o.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            openPortletToMainTab('tab_sendbackMainFolder','{$lang['workitem']['sendbackItem']}','SendbackItemPortlet','getUI');
                        }
                    }
				});
			},this);";
			
			$sentSPAN = "<span class=\"portletCommandSpan\" id=\"sentSPAN\"><img src=\"/{$config ['appName']}/images/th/SentItems.png\"/></span>";
			$scriptDetail .= "Ext.get('sentSPAN').on('click',function() {
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                \$j.ajax({
                	type: 'POST',
					url: '/{$config ['appName']}/session.php',
					dataType: 'json',
					success: function(o){
                        Ext.MessageBox.hide();
                        if(o.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            openPortletToMainTab('tab_outgoingMainFolder','{$lang['workitem']['outgoingItem']}','OutgoingItemPortlet','getUI');
                        }
                    }
				});
			},this);";
			
			$forwardSPAN = "<span class=\"portletCommandSpan\" id=\"forwardSPAN\"><img src=\"/{$config ['appName']}/images/th/ForwardItems.png\"/></span>";
			$scriptDetail .= "Ext.get('forwardSPAN').on('click',function() {
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                \$j.ajax({
                	type: 'POST',
					url: '/{$config ['appName']}/session.php',
					dataType: 'json',
					success: function(o){
                        Ext.MessageBox.hide();
                        if(o.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            openPortletToMainTab('tab_forwardMainFolder','{$lang['workitem']['forwardItem']}','ForwardItemPortlet','getUI');
                        }
                    }
				});
			},this);";
			$callbackSPAN = "<span class=\"portletCommandSpan\" id=\"callbackSPAN\"><img src=\"/{$config ['appName']}/images/th/callBack.png\"/></span>";
			$scriptDetail .= "Ext.get('callbackSPAN').on('click',function() {
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                \$j.ajax({
                	type: 'POST',
					url: '/{$config ['appName']}/session.php',
					dataType: 'json',
					success: function(o){
                        Ext.MessageBox.hide();
                        if(o.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            openPortletToMainTab('tab_circBookExternalMainFolder','{$lang['workitem']['callbackItem']}','CallbackItemPortlet','getUI');
                        }
                    }
				});
			},this);";
        } else {
        	$sendbackSPAN = "<span><img src=\"/{$config ['appName']}/images/th/sendBackDisabled.png\"/></span>";
        	$sentSPAN = "<span><img src=\"/{$config ['appName']}/images/th/SentItemsDisabled.png\"/></span>";
        	$forwardSPAN = "<span><img src=\"/{$config ['appName']}/images/th/ForwardItemsDisabled.png\"/></span>";
        	$callbackSPAN = "<span><img src=\"/{$config ['appName']}/images/th/callBackDisabled.png\"/></span>";
        }
        
        $script = "<script type=\"text/javascript\">
        
        
        {$scriptDetail}
        </script>";
        
		//เห็นทุกคน Click ได้เฉพาะคนมีสิทธิ์ลงรับ
		$html = "{$script}
        <table width=\"100%\">
			<tr>
				<td>
					{$unreceiveSPAN}<br/>
					<span>{$lang['common']['amount']} {$dfTrans->getUnreceivedItemCount()} {$lang['common']['item']}</span>
				</td>
				<td>
					{$sendbackSPAN}<br/>
					<span>{$lang['common']['amount']} {$dfTrans->getSendbackItemCount()} {$lang['common']['item']}</span>
				</td>
			</tr>
			<tr>
				<td>
					{$sentSPAN}<br/>
					<span>{$lang['common']['amount']} {$dfTrans->getPersonalOutgoingCount(0)} {$lang['common']['item']}</span>
				</td>
				<td>
					{$forwardSPAN}<br/>
					<span>{$lang['common']['amount']} {$dfTrans->getPersonalForwardedCount(0)} {$lang['common']['item']}</span>
				</td>
			</tr>
			<tr>
				<td style=\"cursor: pointer;\" id=\"CommandCircBook\">
					<span><img src=\"/{$config ['appName']}/images/th/CircBook.png\"/></span><br/>
					<span>{$lang['common']['amount']} {$dfTrans->getPersonalCircBookInternalCount()} {$lang['common']['item']}</span>
				</td>
				<td >
					{$callbackSPAN}<br/>
					<span>{$lang['common']['amount']} {$dfTrans->getCallBackCount()} {$lang['common']['item']}</span>
				</td>
			</tr>			
		</table>
		<script type=\"text/javascipt\">
		Ext.get('CommandCircBook').on('click',function() {
            Ext.MessageBox.show({
                msg: 'Checking Session',
                progressText: 'Processing...',
                width:300,
                wait:true,
                waitConfig: {interval:200},
                icon:'ext-mb-download'
            });
            \$j.ajax({
            	type: 'POST',
				url: '/{$config ['appName']}/session.php',
				dataType: 'json',
				success: function(o){
                Ext.MessageBox.hide();
                    if(o.redirectLogin == 1) {
                       sessionExpired(); 
                    } else   {
                       openPortletToMainTab('tab_circBookInternalMainFolder','{$lang['workitem']['circBookInteral']}','CircBookInternalPortlet','getUI');
                    }
                }
			});
		},this);
		</script>
		";
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
