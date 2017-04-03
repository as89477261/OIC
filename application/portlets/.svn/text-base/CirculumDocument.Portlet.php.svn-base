<?php
//include 'DMSUtil.php';
/**
 * Portlet : ส่วนควบคุมเอกสาร
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class CirculumDocumentPortlet {
	public function colorQuotaUsage($percent) {
		if ($percent > 75) {
			$result = '#FF0000';
		} elseif ($percent > 50) {
			$result = '#FF9900';
		} elseif ($percent > 25) {
			$result = '#FFFF00';
		} else {
			$result = '#76D769';
		}
		return $result;
	}
	function getUI() {
		global $config;
		global $lang;
		
		checkSessionPortlet();
		
		unset($_COOKIE['receiverListField']);
		unset($_COOKIE['receiverListHiddenField']);
		
		$dmsUtil = new DMSUtil();
		
		//เห็นทุกคน Click ได้เฉพาะคนมีสิทธิ์ลงรับ
		$percentQuotaUsage = 24;
		$quotaUsageSize = 0;
		$quotaMaxSize = "50MB";
		$html = "<table width=\"100%\">
			<tr>
				<td>
					<span class=\"portletCommandSpan\" id=\"recyclebinSpan\"><img src=\"/{$config ['appName']}/images/th/RecycleBin.png\"/></span><br/>
					<span>{$lang['common']['amount']} ".$dmsUtil->getRecyclebinCount()." {$lang['common']['item']}</span>
				</td>
				<td>
					<span class=\"portletCommandSpan\" id=\"expireDocumentSpan\"><img src=\"/{$config ['appName']}/images/th/ExpireItems.png\"/></span><br/>
					<span>{$lang['common']['amount']} ".$dmsUtil->getExpireDocumentCount()." {$lang['common']['item']}</span>
				</td>
			</tr>
			<tr>
				<td>
					<span class=\"portletCommandSpan\" id=\"publishedSpan\"><img src=\"/{$config ['appName']}/images/th/Published.png\"/></span><br/>
					<span>{$lang['common']['amount']} 0 {$lang['common']['item']}</span>
				</td>
				<td>
					<span class=\"portletCommandSpan\" id=\"checkoutSpan\"><img src=\"/{$config ['appName']}/images/th/CheckOut.png\"/></span><br/>
					<span>{$lang['common']['amount']} ".$dmsUtil->getCheckoutCount()." {$lang['common']['item']}</span>
				</td>
			</tr>
			<tr>
				<td>
					<!--<span><img src=\"/{$config ['appName']}/images/th/QuotaUsage.png\"/></span><br/>
					<span>{$lang['common']['amount']} 0 {$lang['common']['item']}</span>-->
					<TABLE CELLSPACING=2 CELLPADDING=0 BORDER=0 WIDTH=100% CLASS=smalldefault>					
						<TR>
							<TD ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE WIDTH=35>
								<span class=\"portletCommandSpan\" id=\"quotaUsageSpan\"><IMG SRC=\"/{$config ['appName']}/images/th/QuotaUsage.png\"></span>
							</TD>
							
						</TR>
						<TR>
							<TD>
								<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100% CLASS=smalldefault>
								<TR>
									<TD COLSPAN=2 ></TD>
								</TR>
								<TR>
									<TD COLSPAN=2 ALIGN=RIGHT>
										
									</TD>
								</TR>								
								</TABLE>
							</TD>
						</TR>
						</TABLE>
						<TABLE  CELLSPACING=0 CELLPADDING=0 BORDER=0 CLASS=smalldefault>
										<TR >
											<TD width=\"100\" VALIGN=MIDDLE ALIGN=\"LEFT\" colspan=\"100\">
												<table cellpadding=\"0\" border=\"0\" cellspacing=\"0\" style=\"background-color:white;border: #104a7b 1px solid; padding:1px; padding-right: 0px; padding-left: 0px;\" width=\"100%\">
													<tr>
														<td width=\"100%\">
															<div style=\"height:5px; width:$percentQuotaUsage%; font-size:1px; background-color:" . $this->colorQuotaUsage($percentQuotaUsage) . "\"></div>
														</td>
													</tr>
												</table>												
											</TD>
											<TD>
												&nbsp;$percentQuotaUsage%<SPAN style=\"font-size:8px\">($quotaUsageSize / $quotaMaxSize)</SPAN>
											</TD>
										</TR>
						</TABLE>
				</td>
				<td>
					<span class=\"portletCommandSpan\" id=\"borrowSpan\"><img src=\"/{$config ['appName']}/images/th/Borrow.png\"/></span><br/>
					<span>{$lang['common']['amount']} ".$dmsUtil->getBorrowCount()." {$lang['common']['item']}</span>
				</td>
			</tr>			
		</table>";
		echo $html;
		
		$js = "
				Ext.get('recyclebinSpan').on('click',function() {
	                if(requestCheckSession()) {
	                    var tabMain = Ext.getCmp('tpAdmin');
			    	
			    		tabMain.add({
			            	id: 'tabDMSControl_recyclebin',
							title: 'Recycle Bin',
							iconCls: 'trashIcon',
							closable:true,
							autoLoad: {
								url: '/{$config ['appName']}/portlet/get-portlet-content',
								params: {
										portletClass: 'DMSPortlet',
										portletMethod: 'portletContent',
										keySearch: '%%',
										qMode: 'recyclebin'
								},
								method: 'POST',
								scripts: true
							}
						}).show();
	                } else {
	                    sessionExpired();
	                }
            	},this);
            	
            	Ext.get('expireDocumentSpan').on('click',function() {
	                if(requestCheckSession()) {
	                    var tabMain = Ext.getCmp('tpAdmin');
			    	
			    		tabMain.add({
			            	id: 'tabDMSControl_expireDocument',
							title: '{$lang ['common'] ['expire']}',
							iconCls: 'expireIcon',
							closable:true,
							autoLoad: {
								url: '/{$config ['appName']}/portlet/get-portlet-content',
								params: {
										portletClass: 'DMSPortlet',
										portletMethod: 'portletContent',
										keySearch: '%%',
										qMode: 'expireDocument'
								},
								method: 'POST',
								scripts: true
							}
						}).show();
	                } else {
	                    sessionExpired();
	                }
            	},this);
            	
            	Ext.get('checkoutSpan').on('click',function() {
	                if(requestCheckSession()) {
	                    var tabMain = Ext.getCmp('tpAdmin');
			    	
			    		tabMain.add({
			            	id: 'tabDMSControl_checkout',
							title: '{$lang ['dms'] ['checkout']}',
							iconCls: 'checkoutIcon',
							closable:true,
							autoLoad: {
								url: '/{$config ['appName']}/portlet/get-portlet-content',
								params: {
										portletClass: 'DMSPortlet',
										portletMethod: 'portletContent',
										keySearch: '%%',
										qMode: 'checkout'
								},
								method: 'POST',
								scripts: true
							}
						}).show();
	                } else {
	                    sessionExpired();
	                }
            	},this);
            	
            	Ext.get('borrowSpan').on('click',function() {
					if(requestCheckSession()) {
	                    var tabMain = Ext.getCmp('tpAdmin');
			    	
			    		tabMain.add({
			            	id: 'tabDMSControl_borrow',
							title: '{$lang ['dms'] ['borrow']}',
							//iconCls: 'checkoutIcon',
							closable:true,
							autoLoad: {
								url: '/{$config ['appName']}/portlet/get-portlet-content',
								params: {
										portletClass: 'BorrowPortlet',
										portletMethod: 'getUi'
								},
								method: 'POST',
								scripts: true
							}
						}).show();
	                } else {
	                    sessionExpired();
	                }
            	},this);
        ";
		
		$jsExt = "<script>
        {$js}
        </script>";
        echo $jsExt;
	}
}
