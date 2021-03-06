<?php
/**
 * Portlet : ˹ѧ����ʹ��Թ���
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class ReceivedItemPortlet {
	
	public function __construct() {
		//include_once 'DFStore.php';                        
		//include_once 'Organize.Entity.php';           		
	}
	
	public function getSelectReservedNoJS($orgName) {
		global $config;
		global $lang;
		global $store;
		global $sessionMgr;
		$gridName = 'gridReceivedItem';
		
		$reserveStore = $store->getDataStore ( 'reserveNo' );
		$js = "
		var resultReserveTpl = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"reserve-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>�Ţ���: {bookno},�Ţ����¹��: {regno} </b></td></tr>',
                    '<tr><td align=\"right\">���ͧ: {info}</td></tr>',
                '</table>',               
            '</div></tpl>'
        );
        
		{$reserveStore}
		
		var selectReserveForm = new Ext.form.FormPanel({
			id: 'selectReserveForm',
			baseCls: 'x-plain',
			labelWidth: 50,
			defaultType: 'textfield',
			layout: 'form',
			items: [new Ext.form.ComboBox({
				id: 'reserveID',
				name: 'reserveID',
				width: 250,
				hiddenName: 'hiddenReserveID',
				allowBlank: false,
				fieldLabel: '�Ţ�ͧ',
				store: reserveNoStore,
				displayField: 'reserveTxt',
				valueField: 'id',
				tpl: resultReserveTpl,
				itemSelector: 'div.reserve-item',
				typeAhead: false,
				value: '',
				triggerAction: 'all',
				emptyText:'Choose ...',
				selectOnFocus: true
			})]
		});
		
		
		var selectReserveWindow = new Ext.Window({
			id: 'selectReserveWindow',
			title: '���Ţ�ͧ˹��§ҹ({$orgName})',
			width: 350,
			height: 100,
			minWidth: 350,
			minHeight: 100,
			resizable: false,
			layout: 'fit',
			modal: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: selectReserveForm,
			closable: false,
			buttons: [{
                    id: 'btnChooseReserve',
                    formBind: true,
                    text: '���Ţ�ͧ',
                    handler: function() {
                    	saveECMData('modeGen2',1);
						saveECMData('refTransID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
						saveECMData('refOrgID','{$sessionMgr->getCurrentOrgID()}');
						saveECMData('refOrgDocCode',{$gridName}.getSelectionModel().getSelected().get('ownerExtDocCode'));
						saveECMData('refBookNo',{$gridName}.getSelectionModel().getSelected().get('docNo'));
						saveECMData('refDocID',{$gridName}.getSelectionModel().getSelected().get('docID'));
						saveECMData('refTitle',{$gridName}.getSelectionModel().getSelected().get('title'));
						saveECMData('refReserve',Ext.getCmp('reserveID').getValue());
						
						selectReserveForm.getForm().reset();
						selectReserveWindow.hide();
						sendExternalWindow.show();
						
						sendExternalForm.getForm().setValues([
				            {id:'sendExtRefTransID',value: getECMData('refTransID')},
							{id:'sendExtRefOrgID',value:  getECMData('refOrgID')},
							{id:'sendExtRefOrgDocCode',value:  getECMData('refOrgDocCode')},
							{id:'sendExtRefDocID',value:  getECMData('refDocID')},
							{id:'sendExtRefer',value:  getECMData('refBookNo')},
							{id:'sendExtTitle',value:  getECMData('refTitle')},
							{id:'sendExtRefBookno',value:  getECMData('refBookNo')},
							{id:'sendExtUseReserveID',value:  getECMData('refReserve')},
							{id:'sendExtDocNo',value:  getECMData('refOrgDocCode')+'/'}					
						]);       
						
	 					if(getECMData('refReserve')!=0) {
		                	sendExternalForm.getForm().setValues([
								{id:'sendExtDocNo',value:  getECMData('refOrgDocCode')+'/'+getECMData('reserveBookno')},
								{id:'sendExtSendNo',value:  getECMData('reserveRegno')}
		                	]);
						}
						
						Ext.get('sendExtSendTo').on('focus',function() {
							sendExternalListWindow.show();
							Cookies.set('rc','sendExtSendTo');
							Cookies.set('rcH','sendExtSendToHidden');
							Ext.getCmp('SendExternalToSelector').focus('',10);
						},this);
                    	
                    }
                },{
					id: 'btnChooseReserveReload',
					text: 'Reload',
					handler: function() {
						Ext.getCmp('reserveID').store.reload();
						Ext.getCmp('reserveID').expand();
					}
				},{
					id: 'btnChooseReserveCancel',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						selectReserveWindow.hide();
					}
				}]
		});
		
		Ext.getCmp('reserveID').on('select',function(cb,rec,idx) {
			saveECMData('reserveBookno',rec.data.bookno);
			saveECMData('reserveRegno',rec.data.regno);
		},this);
		";
		
		return $js;
	}
	
	public function getSelectReservedGlobalNoJS() {
		global $config;
		global $lang;
		global $store;
		global $sessionMgr;
		$gridName = 'gridReceivedItem';
		
		$reserveStore = $store->getDataStore ( 'reserveGlobalNo' );
		$js = "
		var resultReserveGlobalTpl = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"reserve2-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>�Ţ���: {bookno},�Ţ����¹��: {regno} </b></td></tr>',
                    '<tr><td align=\"right\">���ͧ: {info}</td></tr>',
                '</table>',               
            '</div></tpl>'
        );
        
		{$reserveStore}
		
		var selectReserveGlobalForm = new Ext.form.FormPanel({
			id: 'selectReserveGlobalForm',
			baseCls: 'x-plain',
			labelWidth: 50,
			defaultType: 'textfield',
			layout: 'form',
			items: [new Ext.form.ComboBox({
				id: 'reserveGlobalID',
				name: 'reserveGlobalID',
				width: 250,
				hiddenName: 'hiddenReserveGlobalID',
				allowBlank: false,
				fieldLabel: '�Ţ�ͧ',
				store: reserveGlobalNoStore,
				displayField: 'reserveTxt',
				valueField: 'id',
				value: '',
				tpl: resultReserveGlobalTpl,
				itemSelector: 'div.reserve2-item',
				typeAhead: false,
				triggerAction: 'all',
				emptyText:'Choose ...',
				selectOnFocus:true
			})]
		});
		
		var selectReserveGlobalWindow = new Ext.Window({
			id: 'selectReserveGlobalWindow',
			title: '���Ţ�ͧ����¹��ҧ',
			width: 350,
			height: 100,
			minWidth: 350,
			minHeight: 100,
			resizable: false,
			layout: 'fit',
			modal: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: selectReserveGlobalForm,
			closable: false,
			buttons: [{
                    id: 'btnChooseReserveGlobal',
                    formBind: true,
                    text: '���Ţ�ͧ',
                    handler: function() {
                    	saveECMData('modeGen2',1);
		                saveECMData('refTransID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
		                saveECMData('refOrgID','{$sessionMgr->getCurrentOrgID()}');
		                saveECMData('refOrgDocCode',{$gridName}.getSelectionModel().getSelected().get('ownerExtDocCode'));
		                saveECMData('refBookNo',{$gridName}.getSelectionModel().getSelected().get('docNo'));
		                saveECMData('refDocID',{$gridName}.getSelectionModel().getSelected().get('docID'));
		                saveECMData('refTitle',{$gridName}.getSelectionModel().getSelected().get('title'));
		                saveECMData('refReserve',Ext.getCmp('reserveGlobalID').getValue());
		                
		                selectReserveGlobalForm.getForm().reset();
		                selectReserveGlobalWindow.hide();
		                sendExternalGlobalWindow.show();
		                
		                sendExternalGlobalForm.getForm().setValues([
		                    {id:'sendExtGlobalRefTransID',value: getECMData('refTransID')},
		                    {id:'sendExtGlobalRefOrgID',value:  getECMData('refOrgID')},
		                    {id:'sendExtGlobalRefOrgDocCode',value:  getECMData('refOrgDocCode')},
		                    {id:'sendExtGlobalRefDocID',value:  getECMData('refDocID')},
		                    {id:'sendExtGlobalRefer',value:  getECMData('refBookNo')},
		                    {id:'sendExtGlobalTitle',value:  getECMData('refTitle')},
		                    {id:'sendExtGlobalRefBookno',value:  getECMData('refBookNo')},
		                    {id:'sendExtGlobalUseReserveID',value:  getECMData('refReserve')},
		                    {id:'sendExtGlobalDocNo',value:  getECMData('refOrgDocCode')+'/'}                    
		                ]);
		                
		                if(getECMData('refReserve')!=0) {
		                	sendExternalGlobalForm.getForm().setValues([
								{id:'sendExtGlobalDocNo',value:  getECMData('refOrgDocCode')+'/'+getECMData('reserveBooknoGlobal')},
								{id:'sendExtGlobalSendNo',value:  getECMData('reserveRegnoGlobal')}
		                	]);
						}
		                
		                Ext.get('sendExtGlobalSendTo').on('focus',function() {
		                    sendExternalListWindow.show();
		                    Cookies.set('rc','sendExtGlobalSendTo');
		                    Cookies.set('rcH','sendExtGlobalSendToHidden');
		                    Ext.getCmp('SendExternalToSelector').focus('',10);
		                },this);
                    }
                },{
					id: 'btnChooseReserveGlobalReload',
					text: 'Reload',
					handler: function() {
						Ext.getCmp('reserveGlobalID').store.reload();
						Ext.getCmp('reserveGlobalID').expand();
					}
				},{
					id: 'btnChooseReserveGlobalCancel',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						selectReserveGlobalWindow.hide();
					}
				}]
		});
		
		Ext.getCmp('reserveGlobalID').on('select',function(cb,rec,idx) {
			saveECMData('reserveBooknoGlobal',rec.data.bookno);
			saveECMData('reserveRegnoGlobal',rec.data.regno);
		},this);
		";
		
		return $js;
	}
	
	public function getUI() {
		global $config;
		global $conn;
		global $store;
		global $lang;
		global $sessionMgr;
		global $policy;
		
		checkSessionPortlet();
		
		if($policy->canSendExternal() ){
			$disableSendExternalCommand = 'false';
			$hideSendExternalCommand = 'false';
		} else {
			$disableSendExternalCommand = 'true';
			$hideSendExternalCommand = 'true';
		}
		
		if($policy->canSendExternalGlobal() ){
			$disableSendEGIFCommand = 'false';
			$hideSendEGIFCommand = 'false';
		} else {
			$disableSendEGIFCommand = 'true';
			$hideSendEGIFCommand = 'true';
		}
        
		if($policy->canReserveBookno()) {
			$hideReserveBookNo = 'false';
		} else {
			$hideReserveBookNo = 'true';
		}
        
        if(!$policy->canSendExternalGlobal() && !$policy->canSendExternal()) {
            $seperator0Menu = "";
        } else {
            $seperator0Menu = "'-',";
        }
		
		if(!$policy->canSendExternalGlobal() && !$policy->canSendExternal()) {
			$seperator1Menu = "";
		} else {
			$seperator1Menu = "'-',";
		}
		
		if($policy->canSendInternal() ){
			$disableSendInternalCommand = 'false';
			$hideSendInternalCommand = 'false';
		} else {
			$disableSendInternalCommand = 'true';
			$hideSendInternalCommand = 'true';
		}
		
		
		if($policy->isSarabanMaster()){
			$disableOtherCommand = 'false';
			$hideOtherCommand = 'false';
			$seperator2Menu = "'-',";
			$seperator3Menu = "'-',";
            $hideSendInternalReplaceCommand = 'false';
		} else {
			$disableOtherCommand = 'true';
			$hideOtherCommand = 'true';
			$seperator2Menu = "";
			$seperator3Menu = "'-',";
            $hideSendInternalReplaceCommand = 'true';
		}
        
        
        
		if($hideSendInternalCommand === 'true' && $hideSendExternalCommand === 'true' && $hideSendEGIFCommand === 'true' && $hideOtherCommand === 'true') {
				Logger::debug("SendInt : $hideSendInternalCommand");
				Logger::debug("SendExt : $hideSendExternalCommand");
				Logger::debug("SendEGIF : $hideSendEGIFCommand");
				Logger::debug("Other : $hideOtherCommand");
				$hideGenBookMenu = 'true'; 
			} else {
				$hideGenBookMenu = 'false';
			}
			
		
		$dfStore = new DFStore ( );
		$orgEntity = new OrganizeEntity();
		$orgEntity->Load("f_org_id = '{$sessionMgr->getCurrentOrgID()}'");
		
		$sqlGetFilter = "select * from tbl_doc_type order by f_doctype_id";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetFilter );
		$DIVName = "ReceivedItemUIDIV";
		$programID = "PRI_0";
		$gridName = 'gridReceivedItem';
		$filterMenuName = 'PRIFilteredMenu';
		
		$filterMenu = "
		var {$filterMenuName} = new Ext.menu.Menu({
        	id: 'mainMenu_{$programID}'
        	,items: [
        	";
		
		$filterItems = "{
				id: '-1',
				text: '{$lang['common']['undefined']}'
				,checked: true
			}";
		
		foreach ( $rs as $row ) {
			checkKeyCase ( $row );
			$filterItems .= ",{
				id: '{$row['f_doctype_id']}',
				text: '{$row['f_name']}'
				,checked: true
			}";
		}
		
		$filterMenu .= "$filterItems]}
	    );";
	    
	    $genBookMenuName = "genBookMenu_{$programID}";
	    $genBookMenu = "
		var {$genBookMenuName} = new Ext.menu.Menu({
        	id: 'genBookMainMenu_{$programID}'
        	,items: [
        	";
		
		$genBookSubMenu = "{
				id: 'genBook_{$programID}_0',
				text: '�͡�Ţ˹ѧ�������(˹��§ҹ)',
				disabled: 'false',
				handler: function() {
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
                                Ext.MessageBox.confirm('Confirm', '�͡�Ţ˹ѧ��������Ţ�ͧ˹��§ҹ�Ѩ�غѹ [{$orgEntity->f_org_name}] ?', genIntBookno_{$programID});
                            }
                        }
                    });
					
				},
				hidden: {$hideSendInternalCommand}
			} , {
				id: 'genBook_{$programID}_1',
				text: '�͡�Ţ˹ѧ�������(������ͧ)',
				disabled: 'false',
				handler: function() {
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
                                Ext.MessageBox.confirm('Confirm', '�͡�Ţ˹ѧ��������Ţ�ҡ˹��§ҹ�鹷ҧ [ '+{$gridName}.getSelectionModel().getSelected().get('from')+'] ?', genIntBooknoSender_{$programID});
                            }
                        }
                    });
					
				},
				//hidden: {$hideSendInternalCommand}
                hidden: true
			},{
                id: 'genBookReplace_{$programID}_1',
                text: '�͡˹ѧ�������᷹������ͧ',
                //disabled: 'false',
                handler: function() {
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
                                Ext.MessageBox.confirm('Confirm', '�͡�Ţ˹ѧ��������Ţ�ҡ��ǹ��ҧ ?', genGlobalIntBooknoSender_{$programID});
                            }
                        }
                    });
                    
                }
                ,hidden: {$hideSendInternalReplaceCommand}        
                
            },{
                id: 'genBookCirc_{$programID}_1',
                text: '�͡˹ѧ������¹����',
                //disabled: 'false',
                handler: function() {
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
                                Ext.MessageBox.confirm('Confirm', '�͡�Ţ���¹ ?', genInternalCircBookno_{$programID});
                            }
                        }
                    });
                    
                }
                ,hidden: {$hideSendInternalReplaceCommand}        
                
            },{$seperator1Menu}{
				id: 'genBook_{$programID}_2',
				text: '�͡�Ţ˹ѧ�����¹͡(˹��§ҹ)',
				disabled: 'false',
				handler: function() {
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
                                Ext.MessageBox.confirm('Confirm', '�͡˹ѧ�����¹͡�����Ţ�ͧ˹��§ҹ�Ѩ�غѹ [{$orgEntity->f_org_name}] ?', genExtBook_{$programID});
                            }
                        }
                    });
					
				},
				hidden: {$hideSendExternalCommand}
			},{
				id: 'genBook_{$programID}_3',
				text: '�͡�Ţ˹ѧ�����¹͡(������ͧ)',
				disabled: 'false',               
				handler: function() {
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
                                Ext.MessageBox.confirm('Confirm', '�͡˹ѧ�����¹͡�����Ţ�ҡ˹��§ҹ�鹷ҧ [ '+{$gridName}.getSelectionModel().getSelected().get('from')+'] ?', genExtBookSender_{$programID});
                            }
                        }
                    });
					
				},
				hidden: true
			},{
				id: 'genBook_{$programID}_4',
				text: '�͡�Ţ˹ѧ�����¹͡(����¹��ҧ)',
				disabled: 'false',
				handler: function() {
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
                                Ext.MessageBox.confirm('Confirm', '�͡˹ѧ�����¹͡�������¹��ҧ ?', genExtBookGlobal_{$programID});
                            }
                        }
                    });
					
				},
				hidden: {$hideSendEGIFCommand}
			},{$seperator3Menu}{
				id: 'genBook_{$programID}_8',
				text: '���Ţ�ͧ(˹��§ҹ)',
				disabled: 'false',
				handler: function() {
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
                            	selectReserveWindow.show();
                            	Ext.getCmp('reserveID').store.reload();
                                //Ext.MessageBox.confirm('Confirm', '���Ţ�ͧ�ͧ˹��§ҹ�Ѩ�غѹ[{$orgEntity->f_org_name}] ?', genExtBook_{$programID});
                            }
                        }
                    });
					
				},
				hidden: {$hideSendExternalCommand}
			},{
				id: 'genBook_{$programID}_9',
				text: '���Ţ�ͧ(����¹��ҧ)',
				disabled: 'false',
				handler: function() {
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
                                //Ext.MessageBox.confirm('Confirm', '���Ţ�ͧ����¹��ҧ ?', genExtBookGlobal_{$programID});
                                selectReserveGlobalWindow.show();
                                Ext.getCmp('reserveGlobalID').store.reload();
                            }
                        }
                    });
					
				},
				hidden: {$hideSendEGIFCommand}
			},{
				id: 'genBook_{$programID}_10',
				text: '�ͧ�Ţ˹ѧ���(����¹��ҧ)',
				disabled: 'false',
				handler: function() {
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
                                Ext.MessageBox.confirm('Confirm', '�ͧ�Ţ˹ѧ���(����¹��ҧ) ?', reserveExtGlobal_{$programID});
                            }
                        }
                    });
					
				},
				hidden: {$hideReserveBookNo}
			},{$seperator2Menu}{
				id: 'genBook_{$programID}_5',
				text: '�͡�����/����º/��С��',
				disabled: {$disableOtherCommand},
				hidden: {$hideOtherCommand},
                handler: function() {
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
								
								
                                createExtraBookWindow.show();
                                createExtraBookWindow.focus();
                                createExtraBookForm.getForm().reset();
                                //alert({$gridName}.getSelectionModel().getSelected().get('title'));
                                //Ext.getCmp('extraDocTitle').setValue({$gridName}.getSelectionModel().getSelected().get('ownerOrgID'));   
                                //alert({$gridName}.getSelectionModel().getSelected().get('extraOrgID'));
                                
                                //Ext.getCmp('extraOrgID').setValue({$gridName}.getSelectionModel().getSelected().get('extraOrgID'));   
                                //Ext.getCmp('extraOrgName').setRawValue({$gridName}.getSelectionModel().getSelected().get('extraOrgID'));
                                
                                
                                
								Ext.getCmp('extraOrgName').setRawValue('Searching...');
                                Ext.Ajax.request({
                                    url: '/{$config ['appName']}/auto-complete/load-org-by-id',
                                    method: 'POST',
                                    success: function(o){
                                        var r = Ext.decode(o.responseText);
                                        Ext.getCmp('extraOrgName').setRawValue(r.orgName);
                                    },
                                    params: {
                                        id: {$gridName}.getSelectionModel().getSelected().get('ownerOrgID')
                                    }
                                });
            
                                Ext.getCmp('extraDocTitle').setValue({$gridName}.getSelectionModel().getSelected().get('title'));   
                                
								//�׹������ͧ   
								if({$gridName}.getSelectionModel().getSelected().get('returnOwner') == 0 || {$gridName}.getSelectionModel().getSelected().get('returnOwner') == '0') {
									Ext.MessageBox.confirm('{$lang['common']['confirm']}', '�׹˹ѧ��͵�����ͧ ?', returnTransaction_{$programID});
								}
                                
                            }
                        }
                    });
                    
                }
			}/*,{
				id: 'genBook_{$programID}_6',
				text: '�͡�����',
				disabled: {$disableOtherCommand},
				hidden: {$hideOtherCommand}
			},{
				id: 'genBook_{$programID}_7',
				text: '�͡��С��',
				disabled: {$disableOtherCommand},
				hidden: {$hideOtherCommand}
			}*/";	         
		$genBookMenu .= "$genBookSubMenu]}
	    );";
	    
	    $auditMenuName = "auditBookMenu_{$programID}";
	    $auditMenu = "
		var {$auditMenuName} = new Ext.menu.Menu({
        	id: 'auditBookMainMenu_{$programID}'
        	,items: [
        	";
		
		$auditSubMenu = "{
	        	id: 'btnTrack_{$programID}',
	            text:'{$lang['df']['track']}',
	            iconCls: 'infoIcon',
	            disabled: true,
	            handler: function(e) {
	            	popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('recvID'));
				}
	        },{
	        	id: 'btnSaveToTrack_{$programID}',
	            text:'{$lang['df']['saveToTrack']}',
	            iconCls: 'bookmarkIcon',
	            disabled: true,
	            handler: function(e) {
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
                                Ext.MessageBox.confirm('Confirm', '�ѹ�֡ [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ����¡�õԴ���?', addToTrack_{$programID});
                            }
                        }
                    });
	            	
				}
	        },{
	        	id: 'btnPause_{$programID}',
	            text:'{$lang['df']['pause']}',
	            iconCls: 'forbidIcon',
	            disabled: true,
	            handler: function(e) {
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
                                if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
                                    var holdCaption = '�����Թ����͡���';
                                } else {
                                    var holdCaption = '��ش��Ǩ�ͺ�͡���';
                                }
                                Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJob_{$programID});
                            }
                        }
                    });
	            	
				}
	        },{
	        	id: 'btnViewLog_{$programID}',
	            text:'{$lang['df']['viewlog']}',
	            iconCls: 'historyIcon',
	            disabled: true,
	            handler: function(e) {
	            	popupDocLog({$gridName}.getSelectionModel().getSelected().get('recvID'));
				}
	        }";	         
		$auditMenu .= "$auditSubMenu]}
	    );";
	    
	   	$replyBookMenuName = "replyBookMenu_{$programID}";
	    $replyBookMenu = "
		var {$replyBookMenuName} = new Ext.menu.Menu({
        	id: 'replyBookMainMenu_{$programID}'
        	,items: [
        	";
		
		$replyBookSubMenu = "{
				id: 'replyBook_{$programID}_0',
				text: '�͡˹ѧ�������(˹��§ҹ)'
			},{
				id: 'replyBook_{$programID}_1',
				text: '�͡˹ѧ�����¹͡(˹��§ҹ)'
			},{
				id: 'replyBook_{$programID}_2',
				text: '�͡˹ѧ�����¹͡(��ǹ��ҧ)'
			}";	         
		$replyBookMenu .= "$replyBookSubMenu]}
	    );";
		
		$orgID = $sessionMgr->getCurrentOrgID ();
		$regBookID = 0;
		$storeName = "PRIStore_{$regBookID}";
		$RIStore = $dfStore->getPersonalReceiveStore ( $regBookID, $storeName );
		$accountStore = $store->getDataStore ( 'account' );
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
		
		
		
		//CallModule : Awaiting
		$callModule = 'Awaiting';
		$dblClickFunction = "
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
                    Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                    viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'{$callModule}',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'));
                }
            }
        });";
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
        if($config ['genIntCircDocNoUseOwner']) {
            $flagRefDocNo = ",{id:'sendIntDocNo',value:  getECMData('refOrgDocCode')+'/'}";
        } else {
            $flagRefDocNo = "";
        }
		echo "<script type=\"text/javascript\">
		//Received Internal Data Store
		{$RIStore}
		
		{$genBookMenu}
		{$replyBookMenu}
		{$auditMenu}
		
		{$storeName}.setDefaultSort('recvNo', 'desc');
		
		{$this->getSelectReservedNoJS($orgEntity->f_org_name)}
		{$this->getSelectReservedGlobalNoJS()}
		
		function reserveExtGlobal_{$programID} (btn) {
			if(btn == 'yes') { 
				Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/reserve-ext-global',
                        method: 'POST',
                        success: function(o){
							Ext.MessageBox.hide();
							var r = Ext.decode(o.responseText);
                         	Ext.MessageBox.show({
                                title: '�ͧ�Ţ���˹ѧ���',
								msg: '���Ţ��� :'+r.bookNoR,
								buttons: Ext.MessageBox.OK,
								icon: Ext.MessageBox.INFO
							});
							{$gridName}.getSelectionModel().clearSelections();
                            Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                            Ext.getCmp('btnTrack_{$programID}').disable();
                            Ext.getCmp('btnSaveToTrack_{$programID}').disable();
                            Ext.getCmp('btnForward_{$programID}').disable();
                            Ext.getCmp('btnSaveCommand_{$programID}').disable();
                            Ext.getCmp('btnMainMenuGenDoc_{$programID}').disable();
                            Ext.getCmp('btnAssign_{$programID}').disable();
                            Ext.getCmp('btnApprove_{$programID}').disable();
                            Ext.getCmp('btnAuditDoc_{$programID}').disable();
                            // Todo: ���Դ Function ��ҹ
                            //Ext.getCmp('btnSendEmail_{$programID}').disable();                
                            //Ext.getCmp('btnReturn_{$programID}').disable();
                            Ext.getCmp('btnPause_{$programID}').disable();
                            Ext.getCmp('btnViewLog_{$programID}').disable();
                            Ext.getCmp('genBook_{$programID}_10').disable();
							{$storeName}.reload();
						},
                        params: { 
                        	docID: {$gridName}.getSelectionModel().getSelected().get('docID')
						}
                });
			}
		}	
        
        function genInternalCircBookno_{$programID} (btn) {
             if(btn == 'yes') { 
                saveECMData('modeGen2',1);
                saveECMData('refTransID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                saveECMData('refOrgID','{$sessionMgr->getCurrentOrgID()}');
                saveECMData('refOrgDocCode',{$gridName}.getSelectionModel().getSelected().get('ownerExtDocCode'));
                saveECMData('refBookNo',{$gridName}.getSelectionModel().getSelected().get('docNo'));
                saveECMData('refDocID',{$gridName}.getSelectionModel().getSelected().get('docID'));
                saveECMData('refTitle',{$gridName}.getSelectionModel().getSelected().get('title'));
                sendInternalWindow.show();
                sendInternalForm.getForm().setValues([
                    //{id:'sendIntRefTransID',value: getECMData('refTransID')},
                    //{id:'sendIntRefOrgID',value:  getECMData('refOrgID')},
                    //{id:'sendIntRefOrgDocCode',value:  getECMData('refOrgDocCode')},
                    //{id:'sendIntRefDocID',value:  getECMData('refDocID')},
                    {id:'sendIntType',value:  '�����¹����'},
                    {id:'sendIntRefer',value:  getECMData('refBookNo')},
                    {id:'sendIntTitle',value:  getECMData('refTitle')}
                    //{id:'sendIntRefBookno',value:  getECMData('refBookNo')},
                    {$flagRefDocNo}                    
                ]);
                Ext.get('sendIntSendTo').on('focus',function() {
                    sendInternalListWindow.show();
                    Cookies.set('rc','sendIntSendTo');
                    Cookies.set('rcH','sendIntSendToHidden');
                    Ext.getCmp('SendToSelector').focus('',10);
                },this);
                Ext.getCmp('btnSendInternalDoc').hide();
             }
        }
        
        
        function genGlobalIntBooknoSender_{$programID} (btn) {
            if(btn == 'yes') {
                if(btn == 'yes') {
                    saveECMData('modeGen2',1);
                    saveECMData('refTransID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                    saveECMData('refOrgID','{$sessionMgr->getCurrentOrgID()}');
                    saveECMData('refOrgDocCode',{$gridName}.getSelectionModel().getSelected().get('ownerExtDocCode'));
                    saveECMData('refBookNo',{$gridName}.getSelectionModel().getSelected().get('docNo'));
                    saveECMData('refDocID',{$gridName}.getSelectionModel().getSelected().get('docID'));
                    saveECMData('refTitle',{$gridName}.getSelectionModel().getSelected().get('title'));
                    sendInternalGlobalWindow.show();
                    Ext.get('sendIntGlobalSendTo').on('focus',function() {
                        sendInternalListWindow.show();
                        Cookies.set('rc','sendIntGlobalSendTo');
                        Cookies.set('rcH','sendIntGlobalSendToHidden');
                        sendInternalListWindow.show();
                    },this);
                    sendInternalGlobalForm.getForm().setValues([
                        {id:'sendIntGlobalRefTransID',value: getECMData('refTransID')},
                        {id:'sendIntGlobalRefOrgID',value:  getECMData('refOrgID')},
                        {id:'sendIntGlobalRefOrgDocCode',value:  getECMData('refOrgDocCode')},
                        {id:'sendIntGlobalRefDocID',value:  getECMData('refDocID')},
                        {id:'sendIntGlobalRefer',value:  getECMData('refBookNo')},
                        {id:'sendIntGlobalTitle',value:  getECMData('refTitle')},
                        {id:'sendIntGlobalRefBookno',value:  getECMData('refBookNo')},
                        {id:'sendIntGlobalDocNo',value:  getECMData('refOrgDocCode')+'/'}                    
                    ]);                                                                                                          
                }               
            }
        }
        
        
		function genIntBookno_{$programID} (btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
	    			id: 'dlgSaveData',
					msg: '{$lang['common']['saving']}',
					progressText: '{$lang['common']['savingText']}',
					width:300,
					wait:true,
					waitConfig: {interval:200},
					icon:'ext-mb-download'
				});
			       	
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/gen-bookno-int',
                        method: 'POST',
                        success: function(o){
							Ext.MessageBox.hide();
							var r = Ext.decode(o.responseText);
                         	Ext.MessageBox.show({
                                title: '�͡�Ţ˹ѧ�������',
								msg: r.message+' �Ţ��� :'+r.bookno,
								buttons: Ext.MessageBox.OK,
								icon: Ext.MessageBox.INFO
							});
							{$storeName}.reload();
						},
                        params: { 
                        	id: {$gridName}.getSelectionModel().getSelected().get('recvID'),
                        	type: 2
						}
                });                
            }
		}
		
		function genIntBooknoSender_{$programID} (btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
	    			id: 'dlgSaveData',
					msg: '{$lang['common']['saving']}',
					progressText: '{$lang['common']['savingText']}',
					width:300,
					wait:true,
					waitConfig: {interval:200},
					icon:'ext-mb-download'
				});
			       	
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/gen-bookno-int',
                        method: 'POST',
                        success: function(o){
							Ext.MessageBox.hide();
							var r = Ext.decode(o.responseText);
                         	Ext.MessageBox.show({
                                title: '�͡�Ţ˹ѧ�������',
								msg: r.message+' �Ţ��� :'+r.bookno,
								buttons: Ext.MessageBox.OK,
								icon: Ext.MessageBox.INFO
							});
							{$storeName}.reload();
						},
                        params: { 
                        	id: {$gridName}.getSelectionModel().getSelected().get('recvID'),
                        	type: 1
						}
                });                
            }
		}
		
		function genExtBook_{$programID}(btn) {
			if(btn == 'yes') {
                saveECMData('modeGen2',1);
				saveECMData('refTransID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
				saveECMData('refOrgID','{$sessionMgr->getCurrentOrgID()}');
				saveECMData('refOrgDocCode',{$gridName}.getSelectionModel().getSelected().get('ownerExtDocCode'));
				saveECMData('refBookNo',{$gridName}.getSelectionModel().getSelected().get('docNo'));
				saveECMData('refDocID',{$gridName}.getSelectionModel().getSelected().get('docID'));
				saveECMData('refTitle',{$gridName}.getSelectionModel().getSelected().get('title'));
				sendExternalWindow.show();
				sendExternalForm.getForm().setValues([
		            {id:'sendExtRefTransID',value: getECMData('refTransID')},
					{id:'sendExtRefOrgID',value:  getECMData('refOrgID')},
					{id:'sendExtRefOrgDocCode',value:  getECMData('refOrgDocCode')},
					{id:'sendExtRefDocID',value:  getECMData('refDocID')},
					{id:'sendExtRefer',value:  getECMData('refBookNo')},
					{id:'sendExtTitle',value:  getECMData('refTitle')},
					{id:'sendExtRefBookno',value:  getECMData('refBookNo')},
					{id:'sendExtDocNo',value:  getECMData('refOrgDocCode')+'/'}					
				]);       

				Ext.get('sendExtSendTo').on('focus',function() {
					sendExternalListWindow.show();
					Cookies.set('rc','sendExtSendTo');
					Cookies.set('rcH','sendExtSendToHidden');
					Ext.getCmp('SendExternalToSelector').focus('',10);
				},this);
			}
		}
		
		function genExtBookSender_{$programID}(btn) {
			if (btn == 'yes') {
                saveECMData('modeGen2',1);
				saveECMData('refTransID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
				saveECMData('refOrgID',{$gridName}.getSelectionModel().getSelected().get('ownerOrgID'));
				saveECMData('refOrgDocCode',{$gridName}.getSelectionModel().getSelected().get('ownerExtDocCode'));
				saveECMData('refBookNo',{$gridName}.getSelectionModel().getSelected().get('docNo'));
				saveECMData('refDocID',{$gridName}.getSelectionModel().getSelected().get('docID'));
				saveECMData('refTitle',{$gridName}.getSelectionModel().getSelected().get('title'));
				sendExternalWindow.show();
				sendExternalForm.getForm().setValues([
		            {id:'sendExtRefTransID',value: getECMData('refTransID')},
					{id:'sendExtRefOrgID',value:  getECMData('refOrgID')},
					{id:'sendExtRefOrgDocCode',value:  getECMData('refOrgDocCode')},
					{id:'sendExtRefDocID',value:  getECMData('refDocID')},
					{id:'sendExtRefer',value:  getECMData('refBookNo')},
					{id:'sendExtTitle',value:  getECMData('refTitle')},
					{id:'sendExtRefBookno',value:  getECMData('refBookNo')},
					{id:'sendExtDocNo',value:  getECMData('refOrgDocCode')+'/'}					
				]);
			}
		}
		
		function genExtBookGlobal_{$programID}(btn) { 
			if(btn == 'yes') {
                saveECMData('modeGen2',1);
                saveECMData('refTransID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                saveECMData('refOrgID','{$sessionMgr->getCurrentOrgID()}');
                saveECMData('refOrgDocCode',{$gridName}.getSelectionModel().getSelected().get('ownerExtDocCode'));
                saveECMData('refBookNo',{$gridName}.getSelectionModel().getSelected().get('docNo'));
                saveECMData('refDocID',{$gridName}.getSelectionModel().getSelected().get('docID'));
                saveECMData('refTitle',{$gridName}.getSelectionModel().getSelected().get('title'));
                sendExternalGlobalWindow.show();

				//�׹������ͧ
				if({$gridName}.getSelectionModel().getSelected().get('returnOwner') == 0 || {$gridName}.getSelectionModel().getSelected().get('returnOwner') == '0') {
					//saveECMData('returnOwner',1);
					Ext.MessageBox.confirm('{$lang['common']['confirm']}', '�׹˹ѧ��͵�����ͧ ?', returnTransaction_{$programID});
				}
                
                sendExternalGlobalForm.getForm().setValues([
                    {id:'sendExtGlobalRefTransID',value: getECMData('refTransID')},
                    {id:'sendExtGlobalRefOrgID',value:  getECMData('refOrgID')},
                    {id:'sendExtGlobalRefOrgDocCode',value:  getECMData('refOrgDocCode')},
                    {id:'sendExtGlobalRefDocID',value:  getECMData('refDocID')},
                    {id:'sendExtGlobalRefer',value:  getECMData('refBookNo')},
                    {id:'sendExtGlobalTitle',value:  getECMData('refTitle')},
                    {id:'sendExtGlobalRefBookno',value:  getECMData('refBookNo')},
                    {id:'sendExtGlobalDocNo',value:  getECMData('refOrgDocCode')+'/'}                    
                ]);
                
                Ext.get('sendExtGlobalSendTo').on('focus',function() {
                    sendExternalListWindow.show();
                    Cookies.set('rc','sendExtGlobalSendTo');
                    Cookies.set('rcH','sendExtGlobalSendToHidden');
                    Ext.getCmp('SendExternalToSelector').focus('',10);
                },this);
            }
		}
		
		function approveTransaction_{$programID}(btn) {
			if(btn=='yes') {
				Ext.Ajax.request({
					url: '/{$config ['appName']}/df-action/approve-recv-trans',
				   	method: 'POST',
				    success: function(o){
                                                
                                                               
				    	Ext.MessageBox.hide();
                        
                        
                        
						var r = Ext.decode(o.responseText);
						var regNo = '';
						var sendDate = '';
                            Ext.MessageBox.show({
                                title: '���͹��ѵ�',
                                msg: '���͹��ѵ����º����',
                                buttons: Ext.MessageBox.OK,
                                icon: Ext.MessageBox.INFO
                            });
                            {$gridName}.getSelectionModel().clearSelections();
                            Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                            Ext.getCmp('btnTrack_{$programID}').disable();
                            Ext.getCmp('btnSaveToTrack_{$programID}').disable();
                            Ext.getCmp('btnForward_{$programID}').disable();
                            Ext.getCmp('btnSaveCommand_{$programID}').disable();
                            Ext.getCmp('btnMainMenuGenDoc_{$programID}').disable();
                            Ext.getCmp('btnAssign_{$programID}').disable();
                            Ext.getCmp('btnApprove_{$programID}').disable();
                            Ext.getCmp('btnAuditDoc_{$programID}').disable();
                            Ext.getCmp('btnPause_{$programID}').disable();
                            Ext.getCmp('btnViewLog_{$programID}').disable();
                            AwaitingGridFilter.clearFilters();   
                            {$storeName}.reload();
                        
						
					},
				    failure: function(r,o) {
				    	Ext.MessageBox.hide();
						Ext.MessageBox.show({
							title: '���͹��ѵ�',
						    msg: '�������ö�ӡ��͹��ѵ���',
						    buttons: Ext.MessageBox.OK,
						    icon: Ext.MessageBox.ERROR
						});
					},
					params: { docID: {$gridName}.getSelectionModel().getSelected().get('docID') ,transCode: {$gridName}.getSelectionModel().getSelected().get('recvID')}
				});
			}
		}
        
        function returnTransaction_{$programID}(btn) {
            if(btn=='yes') {
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/df-action/return-transaction',
                       method: 'POST',
                    success: function(o){
                                                                                       
                        Ext.MessageBox.hide();
                        var r = Ext.decode(o.responseText);
                        var regNo = '';
                        var sendDate = '';
                        Ext.MessageBox.show({
                            title: '��ä׹������ͧ',
                            msg: '���Թ������º����',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.INFO
                        });
                        //{$gridName}.getSelectionModel().clearSelections();
                        Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                        Ext.getCmp('btnTrack_{$programID}').disable();
                        Ext.getCmp('btnSaveToTrack_{$programID}').disable();
                        Ext.getCmp('btnForward_{$programID}').disable();
                        Ext.getCmp('btnSaveCommand_{$programID}').disable();
                        Ext.getCmp('btnMainMenuGenDoc_{$programID}').disable();
                        Ext.getCmp('btnAssign_{$programID}').disable();
                        Ext.getCmp('btnApprove_{$programID}').disable();
                        Ext.getCmp('btnAuditDoc_{$programID}').disable();
                        Ext.getCmp('btnPause_{$programID}').disable();
                        Ext.getCmp('btnViewLog_{$programID}').disable();
                        {$storeName}.reload();
                    },
                    failure: function(r,o) {
                        Ext.MessageBox.hide();
                        Ext.MessageBox.show({
                            title: '��ä׹������ͧ',
                            msg: '���Թ������º����',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.ERROR
                        });
                    },
                    params: { 
                        docID: {$gridName}.getSelectionModel().getSelected().get('docID') ,
                        transCode: {$gridName}.getSelectionModel().getSelected().get('recvID')
                    }
                });
            }/* else {
                Ext.MessageBox.show({
                    title: '��ä׹������ͧ',
                    msg: '���Թ������º����',
                    buttons: Ext.MessageBox.OK,
                    icon: Ext.MessageBox.INFO
                });
                {$gridName}.getSelectionModel().clearSelections();
                Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                Ext.getCmp('btnTrack_{$programID}').disable();
                Ext.getCmp('btnSaveToTrack_{$programID}').disable();
                Ext.getCmp('btnForward_{$programID}').disable();
                Ext.getCmp('btnSaveCommand_{$programID}').disable();
                Ext.getCmp('btnMainMenuGenDoc_{$programID}').disable();
                Ext.getCmp('btnAssign_{$programID}').disable();
                Ext.getCmp('btnApprove_{$programID}').disable();
                Ext.getCmp('btnAuditDoc_{$programID}').disable();
                Ext.getCmp('btnPause_{$programID}').disable();
                Ext.getCmp('btnViewLog_{$programID}').disable();
                {$storeName}.reload();
            }*/
        }
		
	    var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"{$lang ['df'] ['recvType'] }\",
	           dataIndex: 'recvType',
	           width: 100,
	           renderer: renderRecvType
	        },{
	           header: \"{$lang['df']['recvNo']}\",
	           dataIndex: 'recvNo',
	           width: 50
	        },{
	           header: \"{$lang['df']['classified']}\",
	           dataIndex: 'secret',
	           width: 75,
	           renderer: renderSecret
	        },{
	           header: \"{$lang['df']['speed']}\",
	           dataIndex: 'speed',
	           width: 75,
	           renderer: renderSpeed
	        },{
	           header: \"{$lang['df']['docNo']}\",
	           dataIndex: 'docNo',
	           width: 100,
	           renderer: renderDFBookno
	        },{
	           header: \"{$lang['df']['title']}\",
	           dataIndex: 'title',
	           width: 130,
	           align: 'left',
	           renderer: renderDFTitle
	        },{
	           header: \"{$lang['df']['date']}\",
	           dataIndex: 'docDate',
	           width: 95
		    },{
		       header: \"{$lang['df']['from']}\",
		       dataIndex: 'from',
		       width: 150
		    },{
               header: \"{$lang['df']['from2']}\",
               dataIndex: 'from2',
               width: 150
            },{
		       header: \"{$lang['df']['to']}\",
		       dataIndex: 'to',
		       width: 150
		    },{
		       header: \"{$lang['df']['action']}\",
		       dataIndex: 'command',
		       width: 150
		    }
		]);
	
	    cm_{$programID}.defaultSortable = true;
        
        Ext.ux.grid.filter.StringFilter.prototype.icon = 'images/find.png'; 
        var AwaitingGridFilter = new Ext.ux.grid.GridFilters({filters:[
            {type: 'string',  dataIndex: 'title'} ,
            {type: 'string',  dataIndex: 'docNo'} 
        ]});
	
	    var {$gridName} = new Ext.grid.GridPanel({
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: {$storeName},
	        tbar: new Ext.Toolbar({
				id: '{$programID}_Toolbar',
				height: 25				
			}),
			cm: cm_{$programID},
	        trackMouseOver: false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect: false}),
	        loadMask: true,
	        //enableDragDrop : true,
	        renderTo: '{$DIVName}',
            plugins: AwaitingGridFilter,
	        view: new Ext.grid.GroupingView({forceFit:true}),
	        bbar: new Ext.PagingToolbar({
	            pageSize: 25,
                plugins: AwaitingGridFilter,
	            store: {$storeName},
	            displayInfo: true,
	            displayMsg: '{$lang['df']['receivedInternal']} {0} - {1} {$lang['common']['of']} {2}',
	            emptyMsg: \"{$lang['df']['noDocument']}\",
	            items:[
	                '-', {
	                pressed: false,
	                enableToggle: true,
	                text: '{$lang['df']['viewdetail']}',
	                cls: 'x-btn-text-icon details',
					toggleHandler: toggle{$programID}Details
	            }]
	        })
	    });
	    
    	$filterMenu
    	var tb_{$programID} = Ext.getCmp('{$programID}_Toolbar');
    	
		tb_{$programID}.add({
	 		id: 'btnSearchDocument_{$programID}',
            text:'{$lang['df']['search']}',
            iconCls: 'searchIcon',
            hidden: true,
            disabled: false,
            handler: function() {
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
                            filterReceiveRecordWindow.show();
                            Ext.getCmp('filterRecvType').setValue('RecvInt');
                        }
                    }
                });
            	
			}
        },{
	 		id: 'btnPreviewDocument_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
            	//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),            	'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
            	{$dblClickFunction}
			}
        },{
			id: 'btnAuditDoc_{$programID}',
            text:'{$lang['df']['auditBook']}',
            iconCls: 'auditIcon',
            menu: {$auditMenuName},
            disabled: true
        },{
        	id: 'btnSaveCommand_{$programID}',
            text:'{$lang['df']['saveCommand']}',
            iconCls: 'commandIcon',
            disabled: true,
            handler: function(e) {
            	//FIXME: ��䢡����觡��
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
                            Ext.getCmp('commandTransType').setValue('RI');
                            Ext.getCmp('commandTransCode').setValue({$gridName}.getSelectionModel().getSelected().get('recvID'));
                            addCommandWindow.show();
                        }
                    }
                });
            	
			}
        },{
        	id: 'btnAssign_{$programID}',
            text:'{$lang['workitem']['assign']}',
            iconCls: 'assignIcon',
            disabled: true,
            handler: function(e) {
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
                            assignOrderWindow.show();
                            assignOrderForm.getForm().reset();
                            assignOrderForm.getForm().setValues(
                            [
                                {id: 'orderRecieverRefID',value: {$gridName}.getSelectionModel().getSelected().get('recvID')}
                            ]
                            );
                        }
                    }
                });
            	
			}
        },{
        	id: 'btnMakeCirc_{$programID}',
            text:'{$lang['workitem']['makeCirc']}',
            iconCls: 'circIcon',
            disabled: true,
            hidden: true,
            handler: function(e) {
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
                            //receiveInternalWindow.show();  
                        }
                    }
                });
            	
			}
        },{
        	id: 'btnForward_{$programID}',
            text:'{$lang['df']['forward']}',
            iconCls: 'forwardIcon',
            disabled: true,
            handler: function(e) {
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
                            Cookies.set('fwD',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                            Cookies.set('rc','forwardTransTo');
                            Cookies.set('rcH','forwardTransToHidden');
                            Cookies.set('rp','{$programID}');
                            forwardDFWindow.show();  
							Ext.getCmp('SendToSelector').focus('',10);
                        }
                    }
                });
            	
			}
        },{
			id: 'btnMainMenuGenDoc_{$programID}',
            text:'{$lang['df']['genBook']}',
            iconCls: 'genbookIcon',
            menu: {$genBookMenuName},
            disabled: true,
            hidden: {$hideGenBookMenu}
        },{
			id: 'btnMainReplyDoc_{$programID}',
            text:'{$lang['df']['replyBook']}',
            iconCls: 'replyIcon',
            menu: {$replyBookMenuName},
            disabled: true,
            hidden: true
        }
        // Todo: ���Դ Function ��ҹ
        /*,{
        	id: 'btnReturn_{$programID}',
            text:'{$lang['df']['return']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	id: 'btnSendEmail_{$programID}',
            text:'{$lang['df']['sendEmail']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        }*/ ,{
        	id: 'btnApprove_{$programID}',
            text:'{$lang['workitem']['approve']}',
            iconCls: 'approveIcon',
            disabled: true,
            handler: function(e) {
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
                            Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['workitem']['approve']} ?', approveTransaction_{$programID});
                        }
                    }
                });
                
			}
        },{
        	id: 'btnCloseJob_{$programID}',
            text:'{$lang['df']['closeJob']}',
            iconCls: 'closeJobIcon',
            disabled: true,
            hidden: true,
            handler: function(e) {
			}
        },{
        	id: 'btnExportReport_{$programID}',
            text:'{$lang['df']['excelExport']}',
            iconCls: 'excelIcon',
            disabled: true,
            hidden: true,
            handler: function(e) {
			}
        },{
            text:'{$lang['df']['fetch']}',
            iconCls: 'refreshIcon',
            handler: function(){
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
                            //Ext.getCmp('btnSearchDocument_{$programID}').disable();
                            {$gridName}.getSelectionModel().clearSelections();
                            Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                            Ext.getCmp('btnTrack_{$programID}').disable();
                            Ext.getCmp('btnSaveToTrack_{$programID}').disable();
                            Ext.getCmp('btnForward_{$programID}').disable();
                            Ext.getCmp('btnSaveCommand_{$programID}').disable();
                            Ext.getCmp('btnMainMenuGenDoc_{$programID}').disable();
                            Ext.getCmp('btnAssign_{$programID}').disable();
                            Ext.getCmp('btnApprove_{$programID}').disable();
                            Ext.getCmp('btnAuditDoc_{$programID}').disable();
                            // Todo: ���Դ Function ��ҹ
                            //Ext.getCmp('btnSendEmail_{$programID}').disable();                
                            //Ext.getCmp('btnReturn_{$programID}').disable();
                            Ext.getCmp('btnPause_{$programID}').disable();
                            Ext.getCmp('btnViewLog_{$programID}').disable();
                            Ext.getCmp('genBook_{$programID}_10').disable();
                            {$storeName}.reload();
                            //accountStore.reload();
                        }
                    }
                });
            	
			}
        });

	    // render it
		{$gridName}.render();
		
		{$storeName}.load({params:{start:0, limit:25}});
	    // trigger the data store load
	    //accountStore.load({params:{start:0, limit:25}});
	    
	    
		{$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    
		{$gridName}.on('rowdblclick',function() {
			Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
			//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
			{$dblClickFunction}
	    	//alert('dbl click');
		}
		,{$gridName});
		
	{$gridName}.on({
			'rowclick' : function() {
				Ext.getCmp('btnSearchDocument_{$programID}').enable();
				Ext.getCmp('btnPreviewDocument_{$programID}').enable();
				Ext.getCmp('btnTrack_{$programID}').enable();
				Ext.getCmp('btnSaveToTrack_{$programID}').enable();
				Ext.getCmp('btnForward_{$programID}').enable();
				Ext.getCmp('btnSaveCommand_{$programID}').enable();
				Ext.getCmp('btnAssign_{$programID}').enable();
				Ext.getCmp('btnApprove_{$programID}').enable();
				Ext.getCmp('btnAuditDoc_{$programID}').enable();
				Ext.getCmp('genBook_{$programID}_10').disable();
				
				// Todo: ���Դ Function ��ҹ
				//Ext.getCmp('btnSendEmail_{$programID}').enable();
				//Ext.getCmp('btnReturn_{$programID}').enable();
				Ext.getCmp('btnPause_{$programID}').enable();
				Ext.getCmp('btnViewLog_{$programID}').enable();
				Ext.getCmp('btnMainMenuGenDoc_{$programID}').enable();
				
				if({$gridName}.getSelectionModel().getSelected().get('genIntBookno')==0) {
					Ext.getCmp('genBook_{$programID}_0').enable();
					Ext.getCmp('genBook_{$programID}_1').enable();
				} else {
					Ext.getCmp('genBook_{$programID}_0').disable();
					Ext.getCmp('genBook_{$programID}_1').disable();
				}
				
				if({$gridName}.getSelectionModel().getSelected().get('genExtBookno')==0) {
					Ext.getCmp('genBook_{$programID}_2').enable();
					Ext.getCmp('genBook_{$programID}_3').enable();
					Ext.getCmp('genBook_{$programID}_4').enable();
					Ext.getCmp('genBook_{$programID}_8').enable();
					Ext.getCmp('genBook_{$programID}_9').enable();
				} else {
					Ext.getCmp('genBook_{$programID}_2').disable();
					Ext.getCmp('genBook_{$programID}_3').disable();
					Ext.getCmp('genBook_{$programID}_4').disable();
					Ext.getCmp('genBook_{$programID}_8').disable();
					Ext.getCmp('genBook_{$programID}_9').disable();
				}
				
				if({$gridName}.getSelectionModel().getSelected().get('requestOrder')==1) {
					Ext.getCmp('genBook_{$programID}_5').enable();
				} else {
					Ext.getCmp('genBook_{$programID}_5').disable();
				}
				
				
				if({$gridName}.getSelectionModel().getSelected().get('hasReserved')==0) {
					if({$gridName}.getSelectionModel().getSelected().get('genExtBookno')==0) {
						Ext.getCmp('genBook_{$programID}_10').enable();
					} else {
						Ext.getCmp('genBook_{$programID}_10').disable();
					}
				} else {
					Ext.getCmp('genBook_{$programID}_10').disable();
				}
				
				
                /*
                if({$gridName}.getSelectionModel().getSelected().get('requestCommand')==1) {
					Ext.getCmp('genBook_{$programID}_6').enable();
				} else {
					Ext.getCmp('genBook_{$programID}_6').disable();
				}
				
				if({$gridName}.getSelectionModel().getSelected().get('requestAnnounce')==1) {
					Ext.getCmp('genBook_{$programID}_7').enable();
				} else {
					Ext.getCmp('genBook_{$programID}_7').disable();
				}
                */
				
				if({$gridName}.getSelectionModel().getSelected().get('hold')==1) {
					Ext.getCmp('btnForward_{$programID}').disable();
					Ext.getCmp('btnSaveCommand_{$programID}').disable();
					Ext.getCmp('btnAssign_{$programID}').disable();
					Ext.getCmp('btnApprove_{$programID}').disable();
					Ext.getCmp('btnMainMenuGenDoc_{$programID}').disable();
				}		
			},
			scope: this
		});
		
	    function toggle{$programID}Details(btn, pressed){
	        var view_{$programID} = {$gridName}.getView();
			view_{$programID}.showPreview = pressed;
			view_{$programID}.refresh();
	    }
	    
	    function addToTrack_{$programID}(btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
                        msg: '���ѧ�ѹ�֡��¡�õԴ���...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/add-track',
                        method: 'POST',
                        success: function(o){
                        	Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            var responseMsg = '';
                            if(r.success == 1) {
                            	responseMsg = '�ѹ�֡���º��������';
							} else {
								responseMsg = '�ѹ�֡�Դ��Ҵ';
							}
							
                            Ext.MessageBox.show({
                                title: '��úѹ�֡��¡�õԴ���',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                           		icon: Ext.MessageBox.INFO
							});
                        },
                        params: {
                        	mode: 'r', 
                        	id: {$gridName}.getSelectionModel().getSelected().get('recvID') 
						}
                });                
            }	
		}
		
		function holdJob_{$programID}(btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
                        msg: '���ѧ�ѹ�֡��¡�õԴ���...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        con:'ext-mb-download'
                });
                var urlHold = '';
                if({$gridName}.getSelectionModel().getSelected().get('hold') ==1) {
                	urlHold = '/{$config ['appName']}/df-action/unhold';
				} else {
					urlHold = '/{$config ['appName']}/df-action/hold';
				}
                Ext.Ajax.request({
                        url: urlHold,
                        method: 'POST',
                        success: function(o){
                        	Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            var responseMsg = '';
                            if(r.success == 1) {
                            	responseMsg = '�ѹ�֡���º��������';
							} else {
								responseMsg = '�ѹ�֡�Դ��Ҵ';
							}
							
                            Ext.MessageBox.show({
                                title: '��úѹ�֡��¡�õԴ���',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                           		icon: Ext.MessageBox.INFO
							});
							
							{$storeName}.reload();
                        },
                        params: {
                        	id: {$gridName}.getSelectionModel().getSelected().get('recvID') 
						}
                });                
            }
		}
	    
    
		</script>";
	}
}
