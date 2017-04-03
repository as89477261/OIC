<?php

/**
 * ��������ҧ Javascript ��ҧ
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 * 
 *
 */

class EcmJsController extends ECMController {
	
	/**
	 * action /check-session �ӡ�õ�Ǩ�ͺ Session
	 *
	 */
	public function checkSessionAction() {
		checkSessionJSON ();
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ�ӡ������¹ Password
	 *
	 * @return string
	 */
	private function getChangePasswordJS() {
		global $sessionMgr;
		global $config;
		global $lang;
		global $util;
		
		$js = "var changePassForm = new Ext.form.FormPanel({
			id: 'changePassForm',
			monitorValid:true,
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				id: 'changePwdAccID',
				allowBlank: false,
				name: 'changePwdAccID',
				inputType: 'hidden',
				value: {$sessionMgr->getCurrentAccID()}
			},{
				id: 'changePwdSalt',
				allowBlank: false,
				name: 'changePwdSalt',
				inputType: 'hidden',
				value: '{$util->getSalt()}'
			},{
				fieldLabel: '���ʼ�ҹ���',
				id: 'oldPassword',
				allowBlank: false,
				name: 'oldPassword',
				inputType: 'password'
			},{
				fieldLabel: '���ʼ�ҹ����',
				id: 'newPassword',
				allowBlank: false,
				name: 'newPassword',
				inputType: 'password'
			},{
				fieldLabel: '������ա����',
				id: 'retypePassword',
				allowBlank: false,
				name: 'retypePassword',
				inputType: 'password'
			}],
			buttons: [{
                    id: 'btnChangePassword',
                    formBind: true,
                    text: '{$lang['common']['changePassword']}',
                    handler: function() {
                    
                    	var elSalt = Ext.getCmp('changePwdSalt');
						var elPassword = Ext.getCmp('oldPassword');
						var elNewPassword = Ext.getCmp('newPassword');
						var elRetypePassword = Ext.getCmp('retypePassword');
						
                    	elPassword.setValue(hex_md5(elSalt.getValue() + hex_md5(elPassword.getValue())));
                    	elNewPassword.setValue(hex_md5(elNewPassword.getValue()));
                    	elRetypePassword.setValue(hex_md5(elRetypePassword.getValue()));
                    	
                    	if(elNewPassword.getValue() != elRetypePassword.getValue()) {
                    		Ext.MessageBox.show({
					    		title: 'Password Manager',
					    		msg: 'New Password Mismatched!',
					    		buttons: Ext.MessageBox.OK,
					    		icon: Ext.MessageBox.INFO
					    	});
					    	//Ext.getCmp('changePassForm').getForm().reset();
						} else {                    
		                    Ext.MessageBox.show({
					           	msg: 'Changing Password, please wait...',
					       	   	progressText: 'Applying...',
					       	    	width:300,
					       	    	wait:true,
					       	    	waitConfig: {interval:200},
					       	   	icon:'ext-mb-download'
					       	});
				       	
	                    	Ext.Ajax.request({
				    			url: '/{$config ['appName']}/ecm-sys/change-password',
				    			method: 'POST',
				    			success: function(o){
									var r = Ext.decode(o.responseText);
		                            var responseMsg = '';
		                            if(r.success == 1) {
		                            	responseMsg = '����¹���ʼ�ҹ���º����';
									} else {
										responseMsg = '�ѹ�֡���ʼ�ҹ��������';
									}
				    				changePassWindow.hide();
				    				Ext.MessageBox.hide();
				    				Ext.MessageBox.show({
							    		title: 'Password Manager',
							    		msg: responseMsg,
							    		buttons: Ext.MessageBox.OK,
							    		icon: Ext.MessageBox.INFO
							    	});
								},
				    			failure: function(r,o) {
				    				Ext.MessageBox.show({
							    		title: 'Password Manager',
							    		msg: 'Change Password Failed!!! Code [500] : Server Connection Problem.',
							    		buttons: Ext.MessageBox.OK,
							    		icon: Ext.MessageBox.INFO
							    	});
								},
				    			form: Ext.getCmp('changePassForm').getForm().getEl()
				    		});
						}
						Ext.getCmp('changePassForm').getForm().reset();
                    }
                },{
					id: 'btnCloseChangepassWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						changePassWindow.hide();
					}
				}]
		});
		
		
		var changePassWindow = new Ext.Window({
			id: 'changePassWindow',
			title: '{$lang['common']['changePassword']}',
			width: 275,
			height: 155,
			minWidth: 275,
			minHeight: 155,
			resizable: false,
			modal: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: changePassForm,
			closable: false
		});";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ����¹���˹�
	 *
	 * @return string
	 */
	private function getChangeRoleJS() {
		global $config;
		global $lang;
		global $store;
		global $sessionMgr;
		
		$roleID = $sessionMgr->getCurrentRoleID ();
		$roleStore = $store->getDataStore ( 'roleAvailable' );
		$js = "
		{$roleStore}
		roleAvailable.load();
		var changeRoleForm = new Ext.form.FormPanel({
			id: 'changeRoleForm',
			baseCls: 'x-plain',
			labelWidth: 50,
			defaultType: 'textfield',
			layout: 'form',
			items: [new Ext.form.ComboBox({
				//id: 'changeRoleID',
				name: 'changeRoleID',
				width: 250,
				hiddenName: 'changeRoleID',
				allowBlank: false,
				fieldLabel: '���˹�',
				store: roleAvailable,
				displayField:'name',
				tpl: roleAvaiTpl,
				valueField: 'id',
				typeAhead: false,
				triggerAction: 'all',
				value: '{$roleID}',
				emptyText:'Choose ...',
				itemSelector: 'div.search-item',
				selectOnFocus:true
			})]
		});
		
		
		var changeRoleWindow = new Ext.Window({
			id: 'changeRoleWindow',
			title: '{$lang['common']['changeRole']}',
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
			items: changeRoleForm,
			closable: false,
			buttons: [{
                    id: 'btnChangeRole',
                    formBind: true,
                    text: '{$lang['common']['changeRole']}',
                    handler: function() {
                    	changeRoleWindow.hide();
                    	Ext.MessageBox.show({
			    			id: 'dlgSaveData',
							msg: 'Changing Role',
							progressText: '{$lang['common']['processingText']}',
							width:300,
							wait:true,
							waitConfig: {interval:200},
							icon:'ext-mb-download'
						});
                        Ext.Ajax.request({
			    			url: '/{$config ['appName']}/ecm-sys/change-role',
			    			method: 'POST',
			    			success: function(o){
			    				//Ext.MessageBox.hide();
			    				top.window.document.location.reload();
							},
			    			failure: function(r,o) {
			    				alert('failed');
							},
			    			form: Ext.getCmp('changeRoleForm').getForm().getEl()
			    		});
                    }
                },{
					id: 'btnCloseChangeRoleWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						changeRoleWindow.hide();
					}
				}]
		});";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ����¹�շӧҹ
	 *
	 * @return string
	 */
	private function getChangeYearJS() {
		global $config;
		global $lang;
		global $store;
		global $sessionMgr;
		$currentYear = $sessionMgr->getCurrentYear () + 543;
		$js = "
		var changeYearForm = new Ext.form.FormPanel({
			id: 'changeYearForm',
			baseCls: 'x-plain',
			labelWidth: 50,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				name: 'txtCurrYear',
				fieldLabel: '�ջѨ�غѹ',
				readOnly: true,
				value: '{$currentYear}'
			},new Ext.ux.DateTimeField ({
				fieldLabel: 'ŧ�ѹ���',    
				name: 'txtChangeYear',
				readOnly: true,
				format: 'Y', 
				emptyText: 'Default',
				width: 100
			})]
		});
		
		
		var changeYearWindow = new Ext.Window({
			id: 'changeYearWindow',
			title: '{$lang['common']['changeYear']}',
			width: 220,
			height: 150,
			minWidth: 220,
			minHeight: 150,
			resizable: false,
			layout: 'fit',
			modal: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: changeYearForm,
			closable: false,
			buttons: [{
                    id: 'btnChangeYear',
                    formBind: true,
                    text: '{$lang['common']['changeYear']}',
                    handler: function() {
                    	changeYearWindow.hide();
                    	Ext.MessageBox.show({
			    			id: 'dlgSaveData',
							msg: 'Changing Year',
							progressText: '{$lang['common']['processingText']}',
							width:300,
							wait:true,
							waitConfig: {interval:200},
							icon:'ext-mb-download'
						});
                        Ext.Ajax.request({
			    			url: '/{$config ['appName']}/ecm-sys/change-year',
			    			method: 'POST',
			    			success: function(o){
			    				//Ext.MessageBox.hide();
			    				top.window.document.location.reload();
							},
			    			failure: function(r,o) {
			    				alert('failed');
							},
			    			form: Ext.getCmp('changeYearForm').getForm().getEl()
			    		});
                    }
                },{
					id: 'btnCloseChangeYearWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						changeYearWindow.hide();
					}
				}]
		});";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ�ӡ�á�ͧ����¹�Ѻ
	 *
	 * @return string
	 */
	private function getFilterReceiveRecordJS() {
		global $config;
		global $lang;
		global $util;

		$defaultDate = $util->getDateString();
		if(date('H')<=12) {
			$startTime = '00:00';
			$stopTime = '12:00';
		} else { 
			$startTime = '12:00';
			$stopTime= '18:30';
		}
		
		$js = "var filterReceiveRecordForm = new Ext.form.FormPanel({
			id: 'filterReceiveRecordForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				fieldLabel: '�Ѻ���',
				id: 'filterRecvNo',
				allowBlank: false,
				name: 'filterRecvNo',
		      	width: 200
			},{
				fieldLabel: '�Ţ���˹ѧ���',
				id: 'filterRecvDocNo',
				allowBlank: false,
				name: 'filterRecvDocNo',
		      	width: 200
			},new Ext.ux.DateTimeField ({
		      	fieldLabel: 'ŧ�ѹ��� (�ҡ)',    
		       	name: 'filterRecvDocDateFrom',
		       	emptyText: 'Default',
				format: 'j/n/Y',
		      	width: 100
			}),new Ext.ux.DateTimeField ({
		      	fieldLabel: 'ŧ�ѹ��� (�֧)',    
		       	name: 'filterRecvDocDateTo',
		       	emptyText: 'Default',
				format: 'j/n/Y',
		      	width: 100
			}),{
				fieldLabel: '����ͧ',
				id: 'filterRecvTitle',
				allowBlank: false,
				name: 'filterRecvTitle',
		      	width: 200
			},{
				fieldLabel: '�ҡ',
				id: 'filterRecvFrom',
				allowBlank: false,
				name: 'filterRecvFrom',
		      	width: 200
			},{
				fieldLabel: '�֧',
				id: 'filterRecvTo',
				allowBlank: false,
				name: 'filterRecvTo',
		      	width: 200
			},
	/*		{
				fieldLabel: '�觵�Ͷ֧',
				id: 'filterRecvForwardTo',
				allowBlank: false,
				name: 'filterRecvForwardTo',
		      	width: 200
			},{
				fieldLabel: '���ŧ�Ѻ',
				id: 'filterReceiver',
				allowBlank: false,
				name: 'filterReceiver',
		      	width: 200
			},new Ext.ux.DateTimeField ({
		      	fieldLabel: '�ҡ�ѹ���',    
		       	name: 'filterRecvDateFrom',
		       	emptyText: 'Default',
		      	width: 100
			}),new Ext.ux.DateTimeField ({
		      	fieldLabel: '�֧�ѹ���',    
		       	name: 'filterRecvDateTo',
		       	emptyText: 'Default',
		      	width: 100
			}),new Ext.form.ComboBox({
	            name: 'filterRecvDocType',
	            fieldLabel: '�������͡���',
	            store: acceptDocTypeStore,
	            displayField:'name',
	            valueField: 'id',
	            hiddenName: 'filterRecvDocType',
	            typeAhead: false,
	            triggerAction: 'all',
	            selectOnFocus:true,
	            emptyText: 'Default',
		      	width: 200
	        }), */
			{
				fieldLabel: '��������á�ͧ',
				id: 'filterRecvType',
				allowBlank: false,
				name: 'filterRecvType',
				inputType: 'hidden'
			}]
		});
		
		var filterReceiveRecordForm2 = new Ext.form.FormPanel({
			id: 'filterReceiveRecordForm2',
			baseCls: 'x-plain',
			url: '/{$config['appName']}/df-report/received-report',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			items: [new Ext.ux.DateTimeField ({
		      	fieldLabel: '�ҡ�ѹ���',    
		       	name: 'filterFromDocDate2',
		       	emptyText: '{$defaultDate}',
		      	width: 100
			}),new Ext.form.TimeField({
				fieldLabel: '����',
				emptyText: '{$startTime}',
				name: 'filterFromTime2',
				format: 'H:i',
				width: 100
			
			}),new Ext.ux.DateTimeField ({
		      	fieldLabel: '�֧�ѹ���',    
		       	name: 'filterToDocDate2',
		       	emptyText: '{$defaultDate}',
		      	width: 100
			}),new Ext.form.TimeField({
				fieldLabel: '����',
				emptyText: '{$stopTime}',
				name: 'filterToTime2',
				format: 'H:i',
				width: 100
			
			}),{
				fieldLabel: '��������á�ͧ',
				id: 'reportType',
				allowBlank: false,
				name: 'reportType',
				inputType: 'hidden'
			},new Ext.form.Hidden({
				name: 'type_id',
				id: 'type_id'
			}),new Ext.form.ComboBox({
				store: autocompleteDeptReceiverTextStore,
				fieldLabel: '���˹�/˹��§ҹ',
				displayField:'name',
				valueField: 'id',
				typeAhead: false,
				style: autoFieldStyle,
				emptyText: 'Default',
				loadingText: '{$lang['common']['searcing']}',
				width: 200,
				//pageSize:2,
				hideTrigger:true,
				name: 'filterOrg',
				hiddenName: 'filterOrgID',
				tpl: resultTpl,
				//lazyInit: true,
				//lazyRender: true,
				minChars: 2,
				shadow: false,
				autoLoad: true,
				mode: 'remote',
				itemSelector: 'div.search-item',
				listeners: {
					'select': function(combo, record){
						Ext.getCmp('type_id').setValue(record.get('typeid'));
					}
				}
			})]
		});
		
		var filterReceiveRecordWindow2 = new Ext.Window({
			id: 'filterReceiveRecordWindow2',
			title: '��§ҹ���Ѻ˹ѧ���',
			width: 350,
			height: 215,
			minWidth: 350,
			minHeight: 215,
			resizable: false,
			layout: 'fit',
			modal: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: filterReceiveRecordForm2,
			closable: false,
			buttons: [{
                    //id: 'btnFilterRecvItem',
                    formBind: true,
                    text: '�ʴ���§ҹ',
                    handler: function() {
                    	var form = Ext.getCmp('filterReceiveRecordForm2');
						form.getForm().getEl().dom.target = '_blank';
						form.getForm().getEl().dom.action = form.url;
						form.getForm().getEl().dom.submit();
	                    filterReceiveRecordWindow2.hide();
						Ext.getCmp('filterReceiveRecordForm2').getForm().reset();
					}
                },{
					//id: 'btnCloseRecvWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						filterReceiveRecordWindow2.hide();
					}
				}]
		});
		
		var filterReceiveRecordWindow = new Ext.Window({
			id: 'filterReceiveRecordWindow',
			title: '{$lang['df']['search']}',
			width: 350,
			height: 280,
			minWidth: 350,
			minHeight: 280,
			resizable: false,
			layout: 'fit',
			modal: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: filterReceiveRecordForm,
			closable: false,
			buttons: [{
                    id: 'btnFilterRecvItem',
                    formBind: true,
                    text: '{$lang['df']['search']}',
                    handler: function() {
                        if(Ext.getCmp('filterRecvType').getValue() == 'RecvInt') {
                        	Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							RIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterRecvType').getValue() == 'RecvExt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Exrernal Store
	    							REStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if (Ext.getCmp('filterRecvType').getValue() == 'RecvSec') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Exrernal Store
	    							CIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if (Ext.getCmp('filterRecvType').getValue() == 'RecvSecInt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Exrernal Store
	    							CIIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if (Ext.getCmp('filterRecvType').getValue() == 'RecvSecExt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Exrernal Store
	    							CIEStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if (Ext.getCmp('filterRecvType').getValue() == 'RecvCirc') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Exrernal Store
	    							CBIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if (Ext.getCmp('filterRecvType').getValue() == 'OrderAssigned') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Exrernal Store
	    							OAStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterRecvType').getValue() == 'OrderReceived') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							ORStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterRecvType').getValue() == 'Completed') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							PCMStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterRecvType').getValue() == 'Committed') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							PCIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						} else {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive External Global Store
	    							GREStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}
                    }
                },{
                    id: 'btnClearFilterRecvItem',
                    formBind: true,
                    text: '{$lang['common']['clear']}',
                    handler: function() {
                    	
                        if(Ext.getCmp('filterRecvType').getValue() == 'RecvInt') {
                        	Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							RIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						} else if(Ext.getCmp('filterRecvType').getValue() == 'RecvExt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							REStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if(Ext.getCmp('filterRecvType').getValue() == 'RecvSec') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							CIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if(Ext.getCmp('filterRecvType').getValue() == 'RecvSecInt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							CIIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if(Ext.getCmp('filterRecvType').getValue() == 'RecvSecExt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							CIEStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}else if(Ext.getCmp('filterRecvType').getValue() == 'RecvCirc') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							CBIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterRecvType').getValue() == 'OrderAssigned' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Send Internal Store
	    							OAStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterRecvType').getValue() == 'OrderReceived' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Send Internal Store
	    							ORStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterRecvType').getValue() == 'Completed' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Send Internal Store
	    							PCMStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterRecvType').getValue() == 'Committed' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Send Internal Store
	    							PCIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						} else {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterReceiveRecordForm').getForm().reset();
				    				filterReceiveRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							GREStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterReceiveRecordForm').getForm().getEl()
				    		});
						}
                    }
                },{
					id: 'btnCloseRecvWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						filterReceiveRecordWindow.hide();
					}
				}]
		});";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ�ӡ�á�ͧ����¹��
	 *
	 * @return string
	 */
	private function getFilterSendRecordJS() {
		global $config;
		global $lang;
		
		$js = "var filterOrderForm = new Ext.form.FormPanel({
			id: 'filterOrderForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				fieldLabel: '�Ţ���',
				id: 'filterOrderSendDocNo',
				allowBlank: false,
				name: 'filterOrderSendDocNo',
	            width: 200
			},{
				fieldLabel: '���ͤ����/��С��',
				id: 'filterSendOrders',
				allowBlank: false,
				name: 'filterSendOrders',
	            width: 200
			},{
				fieldLabel: '����ͧ',
				id: 'filterOrderSendTitle',
				allowBlank: false,
				name: 'filterOrderSendTitle',
	            width: 200
			},new Ext.ux.DateTimeField ({
		      	fieldLabel: 'ŧ�ѹ��� (�ҡ)',    
		       	id: 'filterOrderSendDocDateFrom',
		       	name: 'filterOrderSendDocDateFrom',
		       	emptyText: 'Default',
		      	width: 100
			}),new Ext.ux.DateTimeField ({
		      	fieldLabel: 'ŧ�ѹ��� (�֧)',    
		       	id: 'filterOrderSendDocDateTo',
		       	name: 'filterOrderSendDocDateTo',
		       	emptyText: 'Default',
		      	width: 100
			}),{
				fieldLabel: '��������á�ͧ',
				id: 'filterOrderSendType',
				allowBlank: false,
				name: 'filterOrderSendType',
				inputType: 'hidden'
			},{	
				fieldLabel: '˹��§ҹ����͡',
				id: 'filterOrderSendOrg',
				allowBlank: false,
				name: 'filterOrderSendOrg',
	            width: 200
			}]
		});
		//filterSendDocDate.hide();

		var filterOrderWindow = new Ext.Window({
			id: 'filterOrderWindow',
			title: '{$lang['df']['search']}',
			width: 350,
			height: 250,
			minWidth: 350,
			minHeight: 250,
			resizable: false,
			layout: 'fit',
			modal: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: filterOrderForm,
			closable: false,
			buttons: [{
                    id: 'btnFilterOrderItem',
                    formBind: true,
                    text: '{$lang['df']['search']}',
                    handler: function() {
                        if(Ext.getCmp('filterOrderSendType').getValue() == 'Orders') {
                        	Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterOrderWindow.hide();
	    							OrderStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterOrderForm').getForm().getEl()
				    		});
 						}
					}},{
                    id: 'btnClearFilterOrderItem',
                    formBind: true,
                    text: '{$lang['common']['clear']}',
                    handler: function() {
                        if(Ext.getCmp('filterOrderSendType').getValue() == 'Orders') {
                        	Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				form: Ext.getCmp('filterOrderForm').getForm().reset();
				    				filterOrderWindow.hide();
	    							OrderStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterOrderForm').getForm().getEl()
				    		});
					}
					}},{
					id: 'btnCloseOrderWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						filterOrderWindow.hide();
					}
				}]
		});
	
			var filterSendRecordForm = new Ext.form.FormPanel({
			id: 'filterSendRecordForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				fieldLabel: '�觷��',
				id: 'filterSendNo',
				allowBlank: false,
				name: 'filterSendNo',
	            width: 200
			},{
				fieldLabel: '�Ţ���˹ѧ���',
				id: 'filterSendDocNo',
				allowBlank: false,
				name: 'filterSendDocNo',
	            width: 200
			},
				new Ext.ux.DateTimeField ({
		      	fieldLabel: 'ŧ�ѹ��� (�ҡ)',    
		       	name: 'filterSendDocDateFrom',
		       	emptyText: 'Default',
		      	width: 100
			}),
				new Ext.ux.DateTimeField ({
		      	fieldLabel: 'ŧ�ѹ��� (�֧)',    
		       	name: 'filterSendDocDateTo',
		       	emptyText: 'Default',
		      	width: 100
			}),{
				fieldLabel: '����ͧ',
				id: 'filterSendTitle',
				allowBlank: false,
				name: 'filterSendTitle',
	            width: 200
			},{
				fieldLabel: '�ҡ',
				id: 'filterSendFrom',
				allowBlank: false,
				name: 'filterSendFrom',
	            width: 200
			},{
				fieldLabel: '�֧',
				id: 'filterSendTo',
				allowBlank: false,
				name: 'filterSendTo',
	            width: 200
			},
/*			new Ext.ux.DateTimeField ({
		      	fieldLabel: '�ѹ����� (�ҡ)',    
		       	name: 'filterSendDateFrom',
		       	emptyText: 'Default',
		      	width: 100
			}),new Ext.ux.DateTimeField ({
		      	fieldLabel: '�ѹ����� (�֧)',    
		       	name: 'filterSendDateTo',
		       	emptyText: 'Default',
		      	width: 100
			}), 
				new Ext.form.ComboBox({
	            name: 'filterSendDocType',
	            fieldLabel: '�������͡���',
	            store: acceptDocTypeStore,
	            displayField:'name',
	            valueField: 'id',
	            hiddenName: 'filterSendDocType',
	            typeAhead: false,
	            triggerAction: 'all',
	            selectOnFocus:true,
	            emptyText: 'Default',
	            width: 200
	        }), */
			{
				fieldLabel: '��������á�ͧ',
				id: 'filterSendType',
				allowBlank: false,
				name: 'filterSendType',
				inputType: 'hidden'
			}]
		});

		var filterSendRecordWindow = new Ext.Window({
			id: 'filterSendRecordWindow',
			title: '{$lang['df']['search']}',
			width: 350,
			height: 280,
			minWidth: 350,
			minHeight: 280,
			resizable: false,
			layout: 'fit',
			modal: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: filterSendRecordForm,
			closable: false,
			buttons: [{
                    id: 'btnFilterSendItem',
                    formBind: true,
                    text: '{$lang['df']['search']}',
                    handler: function() {
                        if(Ext.getCmp('filterSendType').getValue() == 'SendInt') {
                        	Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							SIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'Forward') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							FWIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}else if (Ext.getCmp('filterSendType').getValue() == 'SendExt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							SEStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'SendSec') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							COStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'SendSecInt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							COIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'SendSecExt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							COEStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'SendCirc') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							SCStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'SendGlobalInt') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							GSIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'OutGoing') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							OGIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'CallBack') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							callbackStore.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'SendBack') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							sendbackStore.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'Track') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							TKIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'Orders') {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							OrderStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/set-filter',
				    			method: 'POST',
				    			success: function(o){
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							GSEStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}
                    }
                },{
                    id: 'btnClearFilterSendItem',
                    formBind: true,
                    text: '{$lang['common']['clear']}',
                    handler: function() {
                    	
                        if(Ext.getCmp('filterSendType').getValue() == 'SendInt') {
                        	Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Receive Internal Store
	    							SIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}else if (Ext.getCmp('filterSendType').getValue() == 'Forward' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							FWIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else if (Ext.getCmp('filterSendType').getValue() == 'SendExt' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							SEStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'SendSec' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							COStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'SendSecInt' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							COIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'SendSecExt' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							COEStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'SendCirc' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							SCStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'SendGlobalInt' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							GSIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'OutGoing' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							OGIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'CallBack' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							callbackStore.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'SendBack' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							sendbackStore.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'Track' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							TKIStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}  else if (Ext.getCmp('filterSendType').getValue() == 'Orders' ) {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							OrderStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						} else {
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/df-data/clear-filter',
				    			method: 'POST',
				    			success: function(o){
				    				Ext.getCmp('filterSendRecordForm').getForm().reset();
				    				filterSendRecordWindow.hide();
				    				//Reload Send Internal Store
	    							GSEStore_0.reload();
								},
				    			failure: function(r,o) {
								},
				    			form: Ext.getCmp('filterSendRecordForm').getForm().getEl()
				    		});
						}
                    }
                },{
					id: 'btnCloseSendWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						filterSendRecordWindow.hide();
					}
				}]
		});";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ�ӡ�úѹ�֡����觡��
	 *
	 * @return string
	 */
	private function getAddCommandJS() {
		global $config;
		global $lang;
		global $sessionMgr;


		$role = new RoleEntity();
		
		if (!$role->Load("f_org_id = '{$sessionMgr->getCurrentOrgID()}' and  f_is_commander =1")) {
			$roleID = 0;
			$roleText = '';
			$formbind = 'true';
		} else {
			$roleID = $role->f_role_id;
			$roleText = $role->f_role_name;
			$formbind = 'false';
		}
		
		$js = "
		var autoCompleteNameWithRole = new Ext.data.Store({
	        proxy: new Ext.data.ScriptTagProxy({
	        	url: '/{$config ['appName']}/auto-complete/name-with-role/'
	        }),
	        reader: new Ext.data.JsonReader({
	            root: 'results',
	            totalProperty: 'total',
	            id: 'id'
	        }, [
	            {name: 'name'},
	            {name: 'id'},
                {name: 'typeid'},
				{name: 'orgName'},
                {name: 'desctype'}  
	        ])
	    });
	    
	    var nameRoleLookupTpl = new Ext.XTemplate(
	        '<tpl for=\".\"><div class=\"search-name-role\">',
                '<table width=\"95%\">',
                    '<tr><td><b>{name}</b></td></tr>',
					'<tr><td align=\"right\" style=\"color:blue;\">{orgName}</td></tr>',
                    '<!--<tr><td align=\"right\">���˹�:{desctype}</td></tr>-->',
                '</table>',               
	        '</div></tpl>'
	    );
		
		var autoCompleteCommander = new Ext.data.Store({
	        proxy: new Ext.data.ScriptTagProxy({
	        	url: '/{$config ['appName']}/auto-complete/commander'
	        }),
	        reader: new Ext.data.JsonReader({
	            root: 'results',
	            totalProperty: 'total',
	            id: 'id'
	        }, [
	            {name: 'name'},
	            {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}  
	        ])
	    });
	    //autoCompleteCommander.load();
		var commanderLookupTpl = new Ext.XTemplate(
	        '<tpl for=\".\"><div class=\"search-commander\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">������:{desctype}</td></tr>',
                '</table>',               
	        '</div></tpl>'
	    );
	    
		var addCommandForm = new Ext.form.FormPanel({
			id: 'addCommandForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			monitorValid:true,
			items: [{
				fieldLabel: '����觡��',
				id: 'commandText',
				allowBlank: false,
				width: 200,
				height: 75,
				xtype: 'textarea',
				name: 'commandText'
			},new Ext.form.ComboBox({
				store: autoCompleteCommander,
				fieldLabel: '���˹觼����觡��',
				allowBlank: false,
				displayField:'name',
				typeAhead: false,
				tabIndex: 1,
				loadingText: '{$lang['common']['searcing']}',
				width: 180,
				//pageSize:10,
				hideTrigger:true,
				id: 'commanderID',
				valueField: 'id',
				emptyText: '{$roleText}',
				//value: '{$roleID}',
				//hiddenName: 'commanderID',
				hiddenName: 'commanderHiddenID',
				name: 'commanderID',
				tpl: commanderLookupTpl,
				//lazyInit: true,
				//lazyRender: true,
				minChars: 2,
				shadow: false,
				autoLoad: true,
				mode: 'remote',
				itemSelector: 'div.search-commander'
			}),{
				fieldLabel: '������Transaction',
				id: 'commandTransType',
				allowBlank: false,
				name: 'commandTransType',
				inputType: 'hidden'
			},{
				fieldLabel: '���� Transaction',
				id: 'commandTransCode',
				allowBlank: false,
				name: 'commandTransCode',
				inputType: 'hidden'
			}],
			buttons: [{
                    id: 'btnSaveCommand',
                    formBind: $formbind,
                    text: '{$lang['common']['save']}',
                    handler: function() {
						if(Ext.getCmp('commanderID').getValue() == '') {
							//alert(Ext.getCmp('commanderID').getRawValue());
							//alert(Ext.getCmp('commanderID').getValue());

							addCommandForm.getForm().setValues([
									{id:'commanderID',value: {$roleID}}							
							]);
						}
						
                    	Ext.Ajax.request({
				    		url: '/{$config ['appName']}/df-action/save-command',
				    		method: 'POST',
				    		success: function(o){
				    			Ext.getCmp('addCommandForm').getForm().reset();
				    			addCommandWindow.hide();
				    			//Reload Send Internal Store
	    						//SEStore_0.reload();
	    						messageSuccess();
							},
				    		failure: function(r,o) {
				    			messageFailed ();
							},
				    		form: Ext.getCmp('addCommandForm').getForm().getEl()
				    	});
                    }
                },{
                    id: 'btnClearCommandForm',
                    text: '{$lang['common']['clear']}',
                    handler: function() {
                    	Ext.getCmp('addCommandForm').getForm().reset();
                    }
                },{
					id: 'btnCloseCommandWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						addCommandWindow.hide();
					}
				}]
		});
		
		
		var addCommandWindow = new Ext.Window({
			id: 'addCommandWindow',
			title: '{$lang['df']['saveCommand']}',
			width: 350,
			height: 225,
			minWidth: 350,
			modal: true,
			minHeight: 225,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: addCommandForm,
			closable: false
		});
			
			
			
		Ext.getCmp('commanderID').on('select',function(c,r,i) {         
            dataRecord = c.store.getAt(i);
            /*var rec = new ReceiverRecordDataDef({
                        dataid: dataRecord.data.id,
                        name: dataRecord.data.name,
                        description: dataRecord.data.desctype,
                        typeid: dataRecord.data.typeid
                        
            });*/
			//alert(dataRecord.data.name);
			//alert(dataRecord.data.id);
			Ext.getCmp('commanderID').setValue(dataRecord.data.id);
			//Ext.getCmp('commanderID').setRawValue(dataRecord.data.name);
            //tempSendExternalStore.add(rec);          
            //SendExternalToSelector.emptyText = '';
            //SendExternalToSelector.reset();      
        },this);
        ";
		
		$js .= "
		var editCommandForm = new Ext.form.FormPanel({
			id: 'editCommandForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			monitorValid:true,
			items: [{
				fieldLabel: '����觡��',
				id: 'editCommandText',
				allowBlank: false,
				width: 200,
				height: 75,
				xtype: 'textarea',
				name: 'editCommandText'
			},new Ext.form.ComboBox({
				store: autoCompleteCommander,
				fieldLabel: '���˹觼����觡��',
				allowBlank: true,
				displayField:'name',
				typeAhead: false,
				emptyText: '{$roleText}',
				tabIndex: 1,
				loadingText: '{$lang['common']['searcing']}',
				width: 180,
				//pageSize:10,
				hideTrigger:true,
				id: 'commanderID',
				valueField: 'id',
				//hiddenName: 'commanderID',
				hiddenName: 'editCommanderHiddenID',
				name: 'editCommanderID',
				tpl: commanderLookupTpl,
				//lazyInit: true,
				//lazyRender: true,
				minChars: 2,
				shadow: false,
				autoLoad: true,
				mode: 'remote',
				itemSelector: 'div.search-commander'
			}),{
				fieldLabel: 'commandID',
				id: 'editCommandID',
				allowBlank: true,
				name: 'editCommandID',
				inputType: 'hidden'
			},{
				fieldLabel: '������Transaction',
				id: 'editCommandTransType',
				allowBlank: true,
				name: 'editCommandTransType',
				inputType: 'hidden'
			},{
				fieldLabel: '���� Transaction',
				id: 'editCommandTransCode',
				allowBlank: true,
				name: 'editCommandTransCode',
				inputType: 'hidden'
			}],
			buttons: [{
                    id: 'btnSaveEditCommand',
                    formBind: true,
                    text: '{$lang['common']['save']}',
                    handler: function() {
                    	Ext.Ajax.request({
				    		url: '/{$config ['appName']}/df-action/edit-command',
				    		method: 'POST',
				    		success: function(o){
				    			Ext.getCmp('editCommandForm').getForm().reset();
				    			editCommandWindow.hide();
	    						messageSuccess();
							},
				    		failure: function(r,o) {
				    			messageFailed ();
							},
				    		form: Ext.getCmp('editCommandForm').getForm().getEl()
				    	});
                    }
                },{
                    id: 'btnClearEditCommandForm',
                    text: '{$lang['common']['clear']}',
                    handler: function() {
                    	Ext.getCmp('editCommandForm').getForm().reset();
                    }
                },{
					id: 'btnCloseEditCommandWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						editCommandWindow.hide();
					}
				}]
		});
		
		
		var editCommandWindow = new Ext.Window({
			id: 'editCommandWindow',
			title: '{$lang['df']['saveCommand']}',
			width: 350,
			height: 225,
			minWidth: 350,
			modal: true,
			minHeight: 225,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: editCommandForm,
			closable: false
		});";
		
		return $js;
	}
	
	/**
	 * �ӡ���ʴ� Bubble Message
	 *
	 * @param string $title
	 * @param string $message
	 * @return string
	 */
	public static function bubbleMsg($title, $message) {
		global $config;
		global $debugMode;
		if ($config ['showBubbleMsg']) {
			if ($debugMode) {
				return "console.log('$message');Ext.ECM.msg('{$title}','{$message}');";
			} else {
				return "Ext.ECM.msg('{$title}','{$message}');";
			}
		} else {
			if ($debugMode) {
				return "console.log('$message');";
			} else {
				return "";
			}
		
		}
	}
	
	/**
	 * �ӡ�����ҧ����"�к�"
	 *
	 * @param string $mode
	 * @return string
	 */
	private function getAdminActionList($mode = 'js') {
		global $lang;
		global $config;
		global $util;
		global $sessionMgr;
		/**
		 * Administrator Action List
		 * 1 - Rank
		 * 2 - Position Masters
		 * 3 - Group Policies
		 * 4 - Organization
		 * 5 - Accounts
		 * 6 - Concurrent
		 * 7 - License
		 * 8 - Configuration 
		 */
		if ($mode == 'js') {
			$js = "var AdminRankAction = new Ext.Action({
				text: '" . $lang ['action'] ['rank'] . "',
			    handler: function(){
			    	
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabRank')) {
						tabMain.add({
							id: 'tabRank',
							title: '" . $lang ['action'] ['rank'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/rank-manager/get-ui', scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabRank').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var AdminPositionAction = new Ext.Action({
				text: '" . $lang ['action'] ['position'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabPosition')) {
						tabMain.add({
							id: 'tabPosition',
							title: '" . $lang ['action'] ['position'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/position-master-manager/get-ui', scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabPosition').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var AdminGroupPolicyAction = new Ext.Action({
				text: '" . $lang ['action'] ['groupPolicy'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabGroupPolicy')) {
						tabMain.add({
							id: 'tabGroupPolicy',
							title: '" . $lang ['action'] ['groupPolicy'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/group-policy-manager/get-ui', scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabGroupPolicy').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var AdminOrganizationAction = new Ext.Action({
				text: '" . $lang ['action'] ['orgChart'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabOrganization')) {
						tabMain.add({
							id: 'tabOrganization',
							title: '" . $lang ['action'] ['orgChart'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/organize-manager/get-ui',scripts:true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabOrganization').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var AdminAccountAction = new Ext.Action({
				text: '" . $lang ['action'] ['account'] . "',
			    handler: function(){
			    	Ext.getCmp('ECMProperty').expand();
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabAccount')) {
						tabMain.add({
							id: 'tabAccount',
							title: '" . $lang ['action'] ['account'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/account-manager/get-ui',scripts:true},
							closable:true
						}).show();
						
						Ext.getCmp('tabAccount').on('destroy',function(p) {
							Ext.getCmp('ECMProperty').collapse();
						},this);
					} else {
						tabMain.findById('tabAccount').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";

			$js .= "var AdminConcurrentAction = new Ext.Action({
				text: '" . $lang ['action'] ['concurrent'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabConcurrent')) {
						tabMain.add({
							id: 'tabConcurrent',
							title: '" . $lang ['action'] ['concurrent'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/concurrent-manager/get-ui', scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabConcurrent').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			//���ҧ Object �ͧ���٨Ѵ��� Master Data Group
			$js .= "var AdminMasterControlAction = new Ext.Action({
				text: '" . $lang ['action'] ['masterControl'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabMasterControl')) {
						tabMain.add({
							id: 'tabMasterControl',
							title: '" . $lang ['action'] ['masterControl'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/master-control/get-ui', scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabMasterControl').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			return $js;
		} else {
			$actionList = "";
			
			//�������٨Ѵ����дѺ���
			if ($actionList != '') {
				$actionList .= ",AdminRankAction";
			} else {
				$actionList = "AdminRankAction";
			}
			
			//�������٨Ѵ����ç���ҧ���˹�
			if ($actionList != '') {
				$actionList .= ",AdminPositionAction";
			} else {
				$actionList = "AdminPositionAction";
			}
			
			//�������٨Ѵ����Է���
			if ($actionList != '') {
				$actionList .= ",AdminGroupPolicyAction";
			} else {
				$actionList = "AdminGroupPolicyAction";
			}
			
			//�������٨Ѵ��úѭ����ª���
			if ($actionList != '') {
				$actionList .= ",AdminAccountAction";
			} else {
				$actionList = "AdminAccountAction";
			}
			
			//�������٨Ѵ����ç���ҧ˹��§ҹ
			if ($actionList != '') {
				$actionList .= ",AdminOrganizationAction";
			} else {
				$actionList = "AdminOrganizationAction";
			}
			
			//�������٨Ѵ��� Master Data Group
			if ($actionList != '') {
				$actionList .= ",AdminMasterControlAction";
			} else {
				$actionList = "AdminMasterControlAction";
			}
			
			//�������٨Ѵ��� Concurrent
			if ($actionList != '') {
				$actionList .= ",AdminConcurrentAction";
			} else {
				$actionList = "AdminConcurrentAction";
			}
			
			return $actionList;
		}
	}
	
	/**
	 * �ӡ�����ҧ����"����ͧ���"
	 *
	 * @param string $mode
	 * @return string
	 */
	private function getToolsActionList($mode = 'js') {
		global $lang;
		global $config;
		global $util;
		global $sessionMgr;
		global $availableTheme;
		global $policy;
		global $DFStore;
		//var_dump($policy);
		/**
		 * Tools action list
		 * 1 - Form Manager
		 * 2 - Workflow Manager
		 * 3 - Docflow Route Manager
		 * 4 - CD/DVD Archive Manager
		 */
		if ($policy->canUseGlobalSearch ()) {
			$disableExecutiveSearch = 'false';
		} else {
			$disableExecutiveSearch = 'true';
		}
		
		if ($config ['disableWorkflow']) {
			$disableWorkflow = 'true';
		} else {
			$disableWorkflow = 'false';
		}
		
		if ($config ['disableSarabanRoute']) {
			$disableSarabanRoute = 'true';
		} else {
			$disableSarabanRoute = 'false';
		}
		
		if ($config ['disableArChiver']) {
			$disableArchiver = 'true';
		} else {
			$disableArchiver = 'false';
		}
		
		if ($mode == 'js') {
			$js = "";
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				$js .= "var ToolsFormMgrAction = new Ext.Action({
				text: '" . $lang ['action'] ['Form'] . "',
			    handler: function(){
			    	Ext.getCmp('ECMProperty').expand();
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabFormMgr')) {
						tabMain.add({
							id: 'tabFormMgr',
							title: '" . $lang ['action'] ['Form'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/form-manager/get-ui', scripts: true},
							closable:true
						}).show();
						
						Ext.getCmp('tabFormMgr').on('destroy',function(p) {
							Ext.getCmp('ECMProperty').collapse();
						},this);
					} else {
						tabMain.findById('tabFormMgr').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			}
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				$js .= "var ToolsDocTypeMgrAction = new Ext.Action({
				text: '" . $lang ['action'] ['docType'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabDocTypeMgr')) {
						tabMain.add({
							id: 'tabDocTypeMgr',
							title: '" . $lang ['action'] ['docType'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/document-type-manager/get-ui', scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabDocTypeMgr').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			}
			
			if ($sessionMgr->getCurrentAccountType () == 3) {
				$js .= "var ToolsStorageMgrAction = new Ext.Action({
				text: '" . $lang ['action'] ['storage'] . "',
			    handler: function(){
			    	Ext.getCmp('ECMProperty').expand();
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabStorageMgr')) {
						tabMain.add({
							id: 'tabStorageMgr',
							title: '" . $lang ['action'] ['storage'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/storage-manager/get-ui', scripts: true},
							closable:true
						}).show();
						
						Ext.getCmp('tabStorageMgr').on('destroy',function(p) {
							Ext.getCmp('ECMProperty').collapse();
						},this);
					} else {
						tabMain.findById('tabStorageMgr').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			}
			
			$js .= "var orgChartExplorer = new Ext.Action({
				text: '" . $lang ['OrgChartExplorer'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tblOrgChart')) {
						tabMain.add({
							//html:'<p>Disabled</p>',
                            id: 'OrgExplorer',
                            autoLoad: {url: '/{$config ['appName']}/org-chart-explorer/get-ui', scripts: true},
                            title: '" . $lang ['OrgChartExplorer'] . "',
                            autoScroll:true,
                            width: '100%',
                            height: '100%',
							closable:true
						}).show();						
					} else {
						tabMain.findById('tblOrgChart').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			/*
			if ($sessionMgr->getCurrentAccountType () >= 2) {
				$js .= "var ToolsRegNoMgrAction = new Ext.Action({
				text: '" . $lang ['action'] ['regNoManage'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabWorkflowMgr')) {
						tabMain.add({
							id: 'tabRegNoMgr',
							title: '" . $lang ['action'] ['regNoManage'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=Portlet1&portletMethod=portletContent'},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabWorkflowMgr').show();
					}
					{$util->bubbleMsg($lang ['ECMAppName'],'Activating Workflow Manager.')}
			    },
			    iconCls: 'workflowIcon'
			});";
			}*/
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				$js .= "var ToolsWorkflowMgrAction = new Ext.Action({
				text: '" . $lang ['action'] ['workflow'] . "',
				disabled: {$disableWorkflow},
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabWorkflowMgr')) {
						tabMain.add({
							id: 'tabWorkflowMgr',
							title: '" . $lang ['action'] ['workflow'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=DMSPortlet&portletMethod=portletContent'},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabWorkflowMgr').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			}
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				$js .= "var ToolsDocflowRouteMgrAction = new Ext.Action({
				disabled: {$disableSarabanRoute},
				text: '" . $lang ['action'] ['docRoute'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabDocflowRouteMgr')) {
						tabMain.add({
							id: 'tabDocflowRouteMgr',
							title: '" . $lang ['action'] ['docRoute'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=DMSPortlet&portletMethod=portletContent'},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabDocflowRouteMgr').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			}
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				
				$js .= "var ToolsCDMgrAction = new Ext.Action({
				disabled: {$disableArchiver},
				text: '" . $lang ['action'] ['CDDVD'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabCDDVD')) {
						tabMain.add({
							id: 'tabCDDVD',
							title: '" . $lang ['action'] ['CDDVD'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=DMSPortlet&portletMethod=portletContent'},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabCDDVD').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			}
			
			/*
			tCurrentAccountType () == 3) {
				$js .= "var ToolsReportManager = new Ext.Action({
				text: '" . $lang ['action'] ['reportManager'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabReportMgr')) {
						tabMain.add({
							id: 'tabReportMgr',
							title: '" . $lang ['action'] ['reportManager'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=Portlet1&portletMethod=portletContent'},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabReportMgr').show();
					}
					{$util->bubbleMsg($lang ['ECMAppName'],'Activating Report Manager.')}
			    },
			    iconCls: 'workflowIcon'
			});";
			}
			*/
			
			if ($policy->canUseGlobalSearch ()) {
				$js .= "var ToolsGlobalSearchAction = new Ext.Action({
				    text: '" . $lang ['action'] ['globalRegbookSearch'] . "',
			        handler: function(){
			    	    var tabMain = Ext.getCmp('tpAdmin');
			    	    if(!tabMain.findById( 'tabGlobalSearch')) {
						    tabMain.add({
							    id: 'tabGlobalSearch',
							    title: '" . $lang ['action'] ['globalRegbookSearch'] . "',
							    iconCls: 'workflowIcon',
							    autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=SearchGlobalRegBookPortlet&portletMethod=getUI',scripts: true},
							    closable:true
						    }).show();
					    } else {
						    tabMain.findById('tabGlobalSearch').show();
					    }
			        },
			        iconCls: 'workflowIcon'
			    });";
			}
			
			$js .= "var DeleteExtSenderForm = new Ext.form.FormPanel({
				id: 'DeleteExtSenderForm',
				monitorValid:true,
				baseCls: 'x-plain',
				layout: 'form',
				items: [new Ext.form.ComboBox({
							name: 'extSenderTitle',
							id: 'extSenderTitle',
                            store: autocompleteSenderExternalListStore,
                            fieldLabel: '��ª��ͼ������¹͡',
                            displayField:'name',
                            typeAhead: false,
                            style: autoFieldStyle,
                            emptyText: 'Auto Complete Field',
                            loadingText: '{$lang['common']['searcing']}',
                            width: 250,
                            hideTrigger:true,
                            allowBlank: false,
//                            labelStyle: 'font-weight:bold;color: Red;',
                            tpl: resultTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-item'
                        }),{
							xtype: 'textfield',
							name: 'extSenderStatus',
							id: 'extSenderStatus',
							allowBlank: true,
							fieldLabel: '����ʴ��ŻѨ�غѹ',
							displayField:'name',
							valueField: 'id',
							typeAhead: false,
							triggerAction: 'all',
							value: '',
							selectOnFocus:true,
							width: 250,
							readOnly: true
						},{
							xtype: 'hidden',
							name: 'extSenderID',
							id: 'extSenderID'
						}
						]
					});

				var DeleteExtSenderWindow = new Ext.Window({
					title: '{$lang ['action'] ['extDeleteSender']}',
					width: 400,
					height: 125,
					layout: 'form',
					resizable: false,
					plain:true,
					bodyStyle:'padding:5px;',
					buttonAlign:'center',
					closable: false,
					modal: true,
					items: DeleteExtSenderForm,
					buttons: [{
						text: '��Ǩ�ͺʶҹ�',
						handler: function(){
                        	Ext.Ajax.request({
								form: Ext.getCmp('DeleteExtSenderForm').getForm().getEl(),
				    			url: '/{$config ['appName']}/df-action/get-external-sender',
				    			method: 'POST',
				    			success: function(o){
									var r = Ext.decode(o.responseText);
									Ext.getCmp('extSenderID').setValue(r.extsenderid);
									if(r.extstatus==1){
										Ext.getCmp('extSenderStatus').setValue('�ʴ�');
									}else{
										Ext.getCmp('extSenderStatus').setValue('����ʴ�');
									}
								},
				    			failure: function(r,o) {
								}
				    		});
						}
					},{
						text: '����¹ʶҹ�',
						handler: function() {
                        	Ext.Ajax.request({
								form: Ext.getCmp('DeleteExtSenderForm').getForm().getEl(),
				    			url: '/{$config ['appName']}/df-action/change-flag-ext-sender',
				    			method: 'POST',
				    			success: function(o){
									var r = Ext.decode(o.responseText);
									if(r.status==1){
										Ext.getCmp('extSenderStatus').setValue('�ʴ�');
									}else{
										Ext.getCmp('extSenderStatus').setValue('����ʴ�');
									}
								},
				    			failure: function(r,o) {
									Ext.MessageBox.show({
										title: '����͹',
										msg: '��Ѻ��اʶҹ���������',
										buttons: Ext.MessageBox.OK,
										icon: Ext.MessageBox.ERROR
									});
								}
				    		});
							autocompleteSenderExternalStore.reload();
					}
				},
/*				{
					text: 'ź',
					handler: function(){
                        	Ext.Ajax.request({
								form: Ext.getCmp('DeleteExtSenderForm').getForm().getEl(),
				    			url: '/{$config ['appName']}/df-action/delete-ext-sender',
				    			method: 'POST',
				    			success: function(o){
									var r = Ext.decode(o.responseText);
									if(r.success==1){
										Ext.MessageBox.show({
											title: '����͹',
											msg: 'ź���������º��������',
											buttons: Ext.MessageBox.OK,
											icon: Ext.MessageBox.INFO
										});
									}else{
										Ext.MessageBox.show({
											title: '����͹',
											msg: '��سҵ�Ǩ�ͺʶҹС�͹ź',
											buttons: Ext.MessageBox.OK,
											icon: Ext.MessageBox.ERROR
										});
									}
									form: Ext.getCmp('DeleteExtSenderForm').getForm().reset()
								},
				    			failure: function(r,o) {
									Ext.MessageBox.show({
										title: '����͹',
										msg: '���ź��������������',
										buttons: Ext.MessageBox.OK,
										icon: Ext.MessageBox.ERROR
									});
								}
				    		});
							autocompleteSenderExternalStore.reload();
					}
				}, */
				{
					text: '��ŧ',
					handler: function(){
						form: Ext.getCmp('DeleteExtSenderForm').getForm().reset(),
						DeleteExtSenderWindow.hide();
					}
				}]

				});

				var DeleteExtSenderAction = new Ext.Action({
				text: '".$lang['action']['extDeleteSender']."',
				handler: function(){
					DeleteExtSenderWindow.show();
				},
			    iconCls: 'workflowIcon'
				});";

			$js .= "var ToolsDMSSearchAction = new Ext.Action({
				text: '" . $lang ['action'] ['globalSearch'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabDMSSearch')) {
						tabMain.add({
							id: 'SearchUI',
                            autoLoad: {url: '/{$config ['appName']}/global-search/get-ui', scripts: true},
                            title: '" . $lang ['action'] ['globalSearch'] . "',
                            autoScroll:true,
                            width: '100%',
                            height: '100%',
							closable:true
					  	}).show();
					} else {
					  	tabMain.findById('tabDMSSearch').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var ToolsDMSAdvanceSearchAction = new Ext.Action({
				text: 'Advance Search',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabDMSAdvanceSearch')) {
						tabMain.add({
							id: 'AdvanceSearchUI',
                            autoLoad: {url: '/{$config ['appName']}/advance-search/search/', scripts: true},
                            title: 'Advance Search',
                            autoScroll:true,
                            width: '100%',
                            height: '100%',
							closable:true
					  	}).show();
					} else {
					  	tabMain.findById('tabDMSAdvanceSearch').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var docflowExplorer = new Ext.Action({
				text: '" . $lang ['workAndTask'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'DFExplorer')) {
						tabMain.add({
							id: 'DFExplorer',
							autoLoad: {url: '/{$config ['appName']}/docflow-explorer/get-ui', scripts: true},
							title: '" . $lang ['workAndTask'] . "',
							autoScroll:true,
			                border:false,
			                //collapsed: true,
			                iconCls:'nav',
							closable:true
					  	}).show();
					} else {
					  	tabMain.findById('DFExplorer').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			switch ($_COOKIE ['ECMIcon']) {
				case 1 :
					$checkedNoIcon = 'false';
					$checkedIcon1 = 'true';
					$checkedIcon2 = 'false';
					break;
				case 2 :
					$checkedNoIcon = 'false';
					$checkedIcon1 = 'false';
					$checkedIcon2 = 'true';
					break;
				default :
					$checkedNoIcon = 'true';
					$checkedIcon1 = 'false';
					$checkedIcon2 = 'false';
					break;
			}
			
			$availableIconList = "{
				text: 'No Icon',
			    group: 'Iconset',
			    checked: {$checkedNoIcon},
				handler: function() {
					Ext.MessageBox.show({
						msg: 'Changing Theme',
						progressText: '{$lang['common']['processingText']}',
						width:300,
						wait:true,
						waitConfig: {interval:200},
						icon:'ext-mb-download'
					});
			        top.window.location = '/{$config['appName']}/Index?ECMIcon=0';
			 	}
			},{
				text: 'Set 1',
			    group: 'Iconset',
			    checked: {$checkedIcon1},
				handler: function() {
					Ext.MessageBox.show({
						msg: 'Changing Theme',
						progressText: '{$lang['common']['processingText']}',
						width:300,
						wait:true,
						waitConfig: {interval:200},
						icon:'ext-mb-download'
					});
			        top.window.location = '/{$config['appName']}/Index?ECMIcon=1';
			 	}
			},{
				text: 'Set 2',
			    group: 'Iconset',
			    checked: {$checkedIcon2},
				handler: function() {
					Ext.MessageBox.show({
						msg: 'Changing Theme',
						progressText: '{$lang['common']['processingText']}',
						width:300,
						wait:true,
						waitConfig: {interval:200},
						icon:'ext-mb-download'
					});
			        top.window.location = '/{$config['appName']}/Index?ECMIcon=2';
			 	}
			}";
			
			$availableThemeList = "";
			foreach ( $availableTheme as $theme ) {
				if ($config ['theme'] == $theme) {
					$checkedTheme = 'true';
				} else {
					$checkedTheme = 'false';
				}
				if ($availableThemeList == "") {
					$availableThemeList = "{
			                            text: '$theme',
			                            group: 'theme',
			                            checked: {$checkedTheme},
										handler: function() {
											Ext.MessageBox.show({
								    			id: 'dlgSaveData',
												msg: 'Changing Theme',
												progressText: '{$lang['common']['processingText']}',
												width:300,
												wait:true,
												waitConfig: {interval:200},
												icon:'ext-mb-download'
											});
			                            	top.window.location = '/{$config['appName']}/Index?ECMTheme={$theme}';
			                            }
			                        }";
				} else {
					$availableThemeList .= ",{
			                            text: '$theme',
			                            group: 'theme',
			                            checked: {$checkedTheme},
										handler: function() {
											Ext.MessageBox.show({
								    			id: 'dlgSaveData',
												msg: 'Changing Theme',
												progressText: '{$lang['common']['processingText']}',
												width:300,
												wait:true,
												waitConfig: {interval:200},
												icon:'ext-mb-download'
											});
			                            	top.window.location = '/{$config['appName']}/Index?ECMTheme={$theme}';
			                            }
			                        }";
				}
			}
			if (array_key_exists ( 'appLang', $_COOKIE )) {
				if ($_COOKIE ['appLang'] == 'th') {
					$thChecked = "true";
					$enChecked = "false";
				} else {
					$thChecked = "false";
					$enChecked = "true";
				}
			} else {
				$_COOKIE ['appLang'] = 'th';
				$thChecked = "true";
				$enChecked = "false";
			}
			$js .= "var PreferenceAction = new Ext.Action({
				text: '" . $lang ['action'] ['preference'] . "',
				menu: { items: [{
                            text: 'Speed & Security Icon Set',
                            menu: { items: [
			                        $availableIconList
							]}
                        },{
                            text: 'Theme',
                            menu: { items: [
			                        $availableThemeList
							]}
                        }, {
                            text: 'Language',
                            menu: { items: [
			                        {
			                            text: 'Thai',
			                            group: 'language',
			                            checked: {$thChecked},
			                            handler: function() {
			                            	Ext.MessageBox.show({
								    			id: 'dlgSaveData',
												msg: 'Changing Language',
												progressText: '{$lang['common']['processingText']}',
												width:300,
												wait:true,
												waitConfig: {interval:200},
												icon:'ext-mb-download'
											});
			                            	top.window.location = '/{$config['appName']}/Index?appLang=th';
			                            }
			                        }, {
			                            text: 'English',
			                            group: 'language',
			                            checked: {$enChecked},
			                            handler: function() {
			                            	Ext.MessageBox.show({
								    			id: 'dlgSaveData',
												msg: 'Changing Language',
												progressText: '{$lang['common']['processingText']}',
												width:300,
												wait:true,
												waitConfig: {interval:200},
												icon:'ext-mb-download'
											});
			                            	top.window.location = '/{$config['appName']}/Index?appLang=en';
			                            }
			                        }
							]}
                        }
				]},
			    iconCls: 'workflowIcon'
			});";
			
			return $js;
		} else {
			$actionList = "";
			
			if ($policy->canUseGlobalSearch ()) {
				if ($actionList != '') {
					$actionList .= ",ToolsGlobalSearchAction";
				} else {
					$actionList = "ToolsGlobalSearchAction";
				}
			}
			
			//if ($sessionMgr->isSarabanMaster ()) {
			if ($actionList != '') {
				$actionList .= ",ToolsDMSSearchAction";
			} else {
				$actionList = "ToolsDMSSearchAction";
			}
			//}
			

			if ($actionList != '') {
				$actionList .= ",ToolsDMSAdvanceSearchAction";
			} else {
				$actionList = "ToolsDMSAdvanceSearchAction";
			}
			
			//if ($sessionMgr->isSarabanMaster ()) {
			if ($actionList != '') {
				$actionList .= ",docflowExplorer";
			} else {
				$actionList = "docflowExplorer";
			}
			//}
			
			//�������٨Ѵ��� AutoComplete External Sender
			if($sessionMgr->isSG()){
				if ($actionList != '') {
					$actionList .= ",'-',DeleteExtSenderAction";
				} else {
					$actionList = "DeleteExtSenderAction";
				}
			}

			if ($sessionMgr->getCurrentAccountType () >= 1) {
				if ($actionList != '') {
					$actionList .= ",'-',ToolsFormMgrAction";
				} else {
					$actionList = "ToolsFormMgrAction";
				}
			} else {
				if ($actionList != '') {
					$actionList .= ",'-'";
				}
			}
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				if ($actionList != '') {
					$actionList .= ",ToolsDocTypeMgrAction";
				} else {
					$actionList = "ToolsDocTypeMgrAction";
				}
			}
			
			if ($sessionMgr->getCurrentAccountType () == 3) {
				if ($actionList != '') {
					$actionList .= ",ToolsStorageMgrAction";
				} else {
					$actionList = "ToolsStorageMgrAction";
				}
			}
			
			/*
			if ($sessionMgr->getCurrentAccountType () >= 2) {
				if ($actionList != '') {
					$actionList .= ",ToolsRegNoMgrAction";
				} else {
					$actionList = "ToolsRegNoMgrAction";
				}
			}*/
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				if ($actionList != '') {
					$actionList .= ",ToolsWorkflowMgrAction";
				} else {
					$actionList = "ToolsWorkflowMgrAction";
				}
			}
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				if ($actionList != '') {
					$actionList .= ",ToolsDocflowRouteMgrAction";
				} else {
					$actionList = "ToolsDocflowRouteMgrAction";
				}
			}
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				if ($actionList != '') {
					$actionList .= ",orgChartExplorer";
				} else {
					$actionList = "orgChartExplorer";
				}
			}
			
			if ($sessionMgr->getCurrentAccountType () >= 1) {
				if ($actionList != '') {
					$actionList .= ",ToolsCDMgrAction";
				} else {
					$actionList = "ToolsCDMgrAction";
				}
			}
			
			/*
			if ($sessionMgr->getCurrentAccountType () == 3) {
				if ($actionList != '') {
					$actionList .= ",ToolsReportManager";
				} else {
					$actionList = "ToolsReportManager";
				}
			}
			*/
			
			if ($actionList != '') {
				$actionList .= ",PreferenceAction";
			} else {
				$actionList = "PreferenceAction";
			}
			return $actionList;
		}
	}
	
	/**
	 * ���ҧ������¡�� Workflow
	 *
	 * @param string $mode
	 * @return string
	 */
	private function getWorkflowActionList($mode = 'js') {
		global $lang;
		//global $config;
		//global $util;
		

		/**
		 * Tools action list
		 * 1 - Form Manager
		 * 2 - Workflow Manager
		 * 3 - Docflow Route Manager
		 * 4 - CD/DVD Archive Manager
		 */
		if ($mode == 'js') {
			$js = "";
			$js .= "var workflowSample = new Ext.Action({
					text: '���',
			    	handler: function(){
			    		openPortletToTab('tab_receiveExtGlobalMainFolder','{$lang['workitem']['receivedExternalGlobal']}','GlobalReceiveExternalPortlet','getUI');
			    	},
			    	iconCls: 'workflowIcon'
				});";
			return $js;
		} else {
			$actionList = "";
			if ($actionList != '') {
				$actionList .= ",workflowSample";
			} else {
				$actionList = "workflowSample";
			}
			return $actionList;
		}
	}
	
	/**
	 * ���ҧ���ٷ���¹˹ѧ���
	 *
	 * @param string $mode
	 * @return string
	 */
	private function getWorksActionList($mode = 'js') {
		global $lang;
		//global $config;
		//global $util;
		global $policy;
		global $sessionMgr;
		
		/**
		 * Tools action list
		 * 1 - Form Manager
		 * 2 - Workflow Manager
		 * 3 - Docflow Route Manager
		 * 4 - CD/DVD Archive Manager
		 */
		if ($mode == 'js') {
			$js = "";
			if ($policy->canReceiveExternalGlobal ()) {
				$js .= "var regBookRecvExtGlobal = new Ext.Action({
					text: '" . $lang ['workitem'] ['receivedExternalGlobal'] . "',
			    	handler: function(){
			    		openPortletToTab('tab_receiveExtGlobalMainFolder','{$lang['workitem']['receivedExternalGlobal']}','GlobalReceiveExternalPortlet','getUI');
			    	},
			    	iconCls: 'workflowIcon'
				});";
			}
			
			if ($policy->canSendExternalGlobal ()) {
				$js .= "var regBookSendExtGlobal = new Ext.Action({
					text: '" . $lang ['workitem'] ['sendExternalGlobal'] . "',
			    	handler: function(){
			    		openPortletToTab('tab_sendExtGlobalMainFolder','{$lang['workitem']['sendExternalGlobal']}','GlobalSendExternalPortlet','getUI');
			    	},
			    	iconCls: 'workflowIcon'
				});";
			}
			
			if ($policy->canSendExternalGlobal ()) {
				$js .= "var regBookSendIntGlobal = new Ext.Action({
                    text: '" . $lang ['workitem'] ['sendInternalGlobal'] . "',
                    handler: function(){
                        openPortletToTab('tab_sendIntGlobalMainFolder','{$lang['workitem']['sendInternalGlobal']}','GlobalSendInternalPortlet','getUI');
                    },
                    iconCls: 'workflowIcon'
                });";
			}
			
			if ($policy->isSecretAgent () && $policy->canAccessClassifiedRegister ()) {
				if ($sessionMgr->getCurrentOrgID() == 374) {
				$js .= "var regBookClassifiedInbound = new Ext.Action({
					text: '" . $lang ['workitem'] ['classifiedInbound'] . "',
			    	handler: function(){
			    		openPortletToTab('tab_ClassifiedInboundMainFolder','{$lang['workitem']['classifiedInbound']}','ClassifiedInboundPortlet','getUI');
			    	},
			    	iconCls: 'workflowIcon'
				});";
				
				$js .= "var regBookClassifiedOutbound = new Ext.Action({
					text: '" . $lang ['workitem'] ['classifiedOutbound'] . "',
			    	handler: function(){
			    		openPortletToTab('tab_ClassifiedOutboundMainFolder','{$lang['workitem']['classifiedOutbound']}','ClassifiedOutboundPortlet','getUI');
			    	},
			    	iconCls: 'workflowIcon'
				});";
				} else {
					$js .= "var regBookClassifiedInboundInt = new Ext.Action({
						text: '" . $lang ['workitem'] ['classifiedInboundInt'] . "',
						handler: function(){
							openPortletToTab('tab_ClassifiedInboundIntMainFolder','{$lang['workitem']['classifiedInboundInt']}','ClassifiedInboundPortletInt','getUI');
						},
						iconCls: 'workflowIcon'
					});";

					$js .= "var regBookClassifiedInboundExt = new Ext.Action({
						text: '" . $lang ['workitem'] ['classifiedInboundExt'] . "',
						handler: function(){
							openPortletToTab('tab_ClassifiedInboundExtMainFolder','{$lang['workitem']['classifiedInboundExt']}','ClassifiedInboundPortletExt','getUI');
						},
						iconCls: 'workflowIcon'
					});";
					
					$js .= "var regBookClassifiedOutboundInt = new Ext.Action({
						text: '" . $lang ['workitem'] ['classifiedOutboundInt'] . "',
						handler: function(){
							openPortletToTab('tab_ClassifiedOutboundIntMainFolder','{$lang['workitem']['classifiedOutboundInt']}','ClassifiedOutboundPortletInt','getUI');
						},
						iconCls: 'workflowIcon'
					});";

					$js .= "var regBookClassifiedOutboundExt = new Ext.Action({
						text: '" . $lang ['workitem'] ['classifiedOutboundExt'] . "',
						handler: function(){
							openPortletToTab('tab_ClassifiedOutboundExtMainFolder','{$lang['workitem']['classifiedOutboundExt']}','ClassifiedOutboundPortletExt','getUI');
						},
						iconCls: 'workflowIcon'
					});";
				}
			}
			
			/*
			if ($policy->canAccessClassifiedRegister ()) {
				$js .= "var regBookClassifiedRegister = new Ext.Action({
					text: '" . $lang ['workitem'] ['classifiedRegister'] . "',
			    	handler: function(){
			    		openPortletToTab('tab_ClassifiedRegisterMainFolder','{$lang['workitem']['classifiedRegister']}','ClassifiedRegisterPortlet','getUI');
			    	},
			    	iconCls: 'workflowIcon'
				});";
			}
            */
			
			$js .= "var regBookRecvInt = new Ext.Action({
				text: '" . $lang ['workitem'] ['receivedInternal'] . "',
			    handler: function(){
			    	openPortletToTab('tab_receiveIntMainFolder','{$lang['workitem']['receivedInternal']}','ReceivedInternalPortlet','getUI');
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var regBookRecvCircInt = new Ext.Action({
				text: '" . $lang ['workitem'] ['receivedCircBook'] . "',
			    handler: function(){
			    	openPortletToTab('tab_receiveCircMainFolder','{$lang['workitem']['receivedCircBook']}','ReceivedCircPortlet','getUI');
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var regBookRecvExt = new Ext.Action({
				text: '" . $lang ['workitem'] ['receivedExternal'] . "',
			    handler: function(){
			    	openPortletToTab('tab_receiveExtMainFolder','{$lang['workitem']['receivedExternal']}','ReceivedExternalPortlet','getUI');
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var regBookSendInt = new Ext.Action({
				text: '" . $lang ['workitem'] ['sendInternal'] . "',
			    handler: function(){
			    	openPortletToTab('tab_sendIntMainFolder','{$lang['workitem']['sendInternal']}','SendInternalPortlet','getUI');
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var regBookSendCircInt = new Ext.Action({
				text: '" . $lang ['workitem'] ['sendCircBook'] . "',
			    handler: function(){
			    	openPortletToTab('tab_sendCircMainFolder','{$lang['workitem']['sendCircBook']}','SendCircPortlet','getUI');
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var regBookSendExt = new Ext.Action({
				text: '" . $lang ['workitem'] ['sendExternal'] . "',
			    handler: function(){
			    	openPortletToTab('tab_sendExtMainFolder','{$lang['workitem']['sendExternal']}','SendExternalPortlet','getUI');
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var ordersRegister = new Ext.Action({
                text: '" . $lang ['workitem'] ['orders'] . "',
                handler: function(){
                    openPortletToTab('tab_OrderFolder','{$lang['workitem']['orders']}','OrderPortlet','getUI');
                },
                iconCls: 'workflowIcon'
            });";
			
			return $js;
		} else {
			$actionList = "";
			if ($policy->canReceiveExternalGlobal ()) {
				if ($actionList != '') {
					$actionList .= ",regBookRecvExtGlobal";
				} else {
					$actionList = "regBookRecvExtGlobal";
				}
			}
			
			if ($policy->canSendExternalGlobal ()) {
				if ($actionList != '') {
					$actionList .= ",regBookSendExtGlobal";
				} else {
					$actionList = "regBookSendExtGlobal";
				}
			}
			
			if ($policy->canSendExternalGlobal ()) {
				if ($actionList != '') {
					$actionList .= ",regBookSendIntGlobal";
				} else {
					$actionList = "regBookSendIntGlobal";
				}
			}
			
			if ($policy->isSecretAgent () && $policy->canAccessClassifiedRegister ()) {
				if ($sessionMgr->getCurrentOrgID() == 374) {
				if ($actionList != '') {
					$actionList .= ",'-',regBookClassifiedInbound";
				} else {
					$actionList = "regBookClassifiedInbound";
				}
				if ($actionList != '') {
					$actionList .= ",regBookClassifiedOutbound";
				} else {
					$actionList = "regBookClassifiedOutbound";
				}
				} else {
					if ($actionList != '') {
						$actionList .= ",'-',regBookClassifiedInboundInt";
					} else {
						$actionList = "regBookClassifiedInboundInt";
					}
					if ($actionList != '') {
						$actionList .= ",regBookClassifiedInboundExt";
					} else {
						$actionList = "regBookClassifiedInboundExt";
					}
					if ($actionList != '') {
						$actionList .= ",regBookClassifiedOutboundInt";
					} else {
						$actionList = "regBookClassifiedOutboundInt";
					}
					if ($actionList != '') {
						$actionList .= ",regBookClassifiedOutboundExt";
					} else {
						$actionList = "regBookClassifiedOutboundExt";
					}
				}
			}
			
			/*
			if ($policy->canAccessClassifiedRegister ()) {
				if ($actionList != '') {
					$actionList .= ",'-',regBookClassifiedRegister";
				} else {
					$actionList = "regBookClassifiedRegister";
				}
			}
            */
			
			if ($actionList != '') {
				$actionList .= ",'-',regBookRecvInt,regBookRecvCircInt";
			} else {
				$actionList = "regBookRecvInt,regBookRecvCircInt";
			}
			
			if ($actionList != '') {
				$actionList .= ",regBookRecvExt";
			} else {
				$actionList = "regBookRecvExt";
			}
			
			if ($actionList != '') {
				$actionList .= ",regBookSendInt,regBookSendCircInt";
			} else {
				$actionList = "regBookSendInt,regBookSendCircInt";
			}
			
			if ($actionList != '') {
				$actionList .= ",regBookSendExt";
			} else {
				$actionList = "regBookSendExt";
			}
			
			if ($actionList != '') {
				$actionList .= ",'-',ordersRegister";
			} else {
				$actionList = "ordersRegister";
			}
			
			return $actionList;
		}
	}
	
	/**
	 * ���ҧ��������
	 *
	 * @param string $mode
	 */
	private function getModulesActionList($mode = 'js') {
		global $lang;
		global $config;
		global $util;
		global $sessionMgr;
		global $policy;
		$dmsPolicy = $policy->getCurrentDMSPolicy();
		//echo "<HR>sss".$policy->isSG();


		if ($config ['disableCalendar']) {
			$disableCalendar = 'true';
		} else {
			$disableCalendar = 'false';
		}
		
		if ($config ['disableWorkflowUser']) {
			$disableWorkflowUserList = 'true';
		} else {
			$disableWorkflowUserList = 'false';
		}
		
		/**
		 * Module Action List
		 * 1 - Workflow
		 * 2 - Document Flow
		 * 3 - DMS
		 * 4 - Knowledge Base
		 * 5 - Calendar
		 */
		$js = "";
		if ($mode == 'js') {
			$js .= "var HomeAction = new Ext.Action({
				text: '" . $lang ['home'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	tabMain.findById('tpAdminHome').show();
			    },
			    iconCls: 'homeIcon'
			});";
			if (! $config ['module'] ['Docimage']) {
				$hiddenDMS = 'true';
			} else {
				$hiddenDMS = 'false';
			}

			
			if($dmsPolicy['F_DMS_ACCESS'] == '0'){
				$hiddenDMS = 'true';
			}
			//var_dump($dmsPolicy);
			
				$js .= "var DMSAction = new Ext.Action({
					text: '" . $lang ['DMS'] . "',
					handler: function(){
						var tabMain = Ext.getCmp('tpAdmin');
						if(!tabMain.findById( 'tpDMS')) {
							tabMain.add({
								id: 'tpDMS',
								title: '" . $lang ['DMS'] . "',
								iconCls: 'calendarIcon',
								autoScroll: true,
								autoLoad: {url: '/{$config ['appName']}/dms-explorer/get-ui',scripts: true},
								closable:true
							}).show();
						} else {
							tabMain.findById('tpDMS').show();
						}
					},
					hidden: {$hiddenDMS},
					iconCls: 'homeIcon'
				});";

			
			if (! $config ['module'] ['KBase']) {
				$hiddenKBase = "true";
			} else {
				$hiddenKBase = "false";
			}
			$js .= "var KBAction = new Ext.Action({
				text: '" . $lang ['KB'] . "',
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tpKBase')) {
						tabMain.add({
							id: 'tpKBase',
							title: '" . $lang ['KB'] . "',
							iconCls: 'calendarIcon',
							autoScroll: true,
							autoLoad: {url: '/{$config ['appName']}/kbase-explorer/get-ui',scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tpKBase').show();
					}
			    },
			    hidden: {$hiddenKBase},
			    iconCls: 'homeIcon'
			});";
			
			if (! $config ['module'] ['Meeting']) {
				$hiddenMeeting = 'true';
			} else {
				$hiddenMeeting = 'false';
			}
			if (!$policy->isSG ()) {
				$hiddenMeeting = 'true';
			}

			$js .= "var MeetingActivateAction = new Ext.Action({
                text: '" . $lang ['action'] ['MeetingActivate'] . "',
                disabled: false,
                handler: function(){
                    popupOldApp('meeting','/workflow/popup.php?module=MeetingApp&action=redirect') ;
                },
                hidden: {$hiddenMeeting},
                iconCls: 'workflowIcon'
            });";
			
			if (! $config ['module'] ['RoomBooking']) {
				$hiddenMRBS = 'true';
			} else {
				$hiddenMRBS = 'false';
			}
			$js .= "var BookingActivateAction = new Ext.Action({
                text: '" . $lang ['action'] ['BookingActivate'] . "',
                disabled: false,
                handler: function(){
                    popupOldApp('mrbs2','/workflow/popup.php?action=intFunction&module=TransactionRoom') ;      
                },
                hidden: true,
                iconCls: 'workflowIcon'
            });";
			
			
			if (!$policy->canRoomAccess ()) {
				$hiddenRoom = 'true';
			}else{
				$hiddenRoom = 'false';
			}
			$js .= "var BookingActivateAction2 = new Ext.Action({
                text: '�ͧ��ͧ',
                disabled: false,
                handler: function(){
                    popupOldApp('room','room/index/') ;      
                },
                hidden: {$hiddenRoom},
                iconCls: 'workflowIcon'
            });";

			$js .= "var CarActivateAction = new Ext.Action({
                text: '�ͧö',
                disabled: false,
                handler: function(){
                    popupOldApp('mrbs2','reservation/reservation.php') ;      
                },
                hidden: {$hiddenMRBS},
                iconCls: 'workflowIcon'
            });";
			
			if (! $config ['module'] ['Workflow']) {
				$hiddenWorkflow = 'true';
			} else {
				$hiddenWorkflow = 'false';
			}
			$js .= "var WorkflowActivateAction = new Ext.Action({
				text: '" . $lang ['action'] ['WorkflowActivate'] . "',
				disabled: false,
			    handler: function(){
                    popupOldApp('workflow','/workflow/popup.php?module=Document&action=Inbox     ') ;      
			    	//var tabMain = Ext.getCmp('tpAdmin');
			    	//if(!tabMain.findById( 'tabWorkflowUserList')) {
					//	tabMain.add({
					//		id: 'tabWorkflowUserList',
					//		title: '" . $lang ['action'] ['WorkflowActivate'] . "',
					//		iconCls: 'workflowIcon',
					//		autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=WorkflowOldPortlet&portletMethod=getUI',scripts: true},
					//		closable:true
					//	}).show();
					//} else {
					//	tabMain.findById('tabWorkflowUserList').show();
					//}
                    
                    /*
					var winname   = 'windialogMainWorkflow';
					
					var LeftPosition = 0;
					var TopPosition = 0;
					var width=1024;
					var height = 700
					var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
					'scrollbars=yes,resizable=no,copyhistory=no,width='+width+',height='+height+',top='+TopPosition+',left='+LeftPosition;
					src = '../../workflow/index.php?module=AutoLogin&action=Login&username={$sessionMgr->getCurrentUserLogin()}&hideTopBand=1&moduleToGo=Document&actionToGo=Inbox';
					winObj = window.open(src,winname,winconfig);
					winObj.focus();
                    */
			    },
			    hidden: {$hiddenWorkflow},
			    iconCls: 'workflowIcon'
			});";
			
			//Real Workflow App
			//$js .= $this->getWorkflowActionList('js');
			

			if (! $config ['disableCalendar']) {
				$hiddenCalender = 'true';
			} else {
				$hiddenCalender = 'false';
			}
			$js .= "var CalendarAction = new Ext.Action({
				text: '" . $lang ['action'] ['personalCalendar'] . "',
				disabled: {$disableCalendar},
				hidden: {$hiddenCalender},
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabCalendar')) {
						tabMain.add({
							id: 'tabCalendar',
							title: '" . $lang ['action'] ['personalCalendar'] . "',
							iconCls: 'calendarIcon',
							autoScroll: true,
							autoLoad: {url: '/{$config ['appName']}/personal-calendar/month',scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabCalendar').show();
					}
			    },
			    iconCls: 'calendarIcon'
			});";
			return $js;
		} else {
			$actionList = "";
			
			if ($actionList != '') {
				$actionList .= ",DMSAction";
			} else {
				$actionList = "DMSAction";
			}
			
			if ($actionList != '') {
				$actionList .= ",KBAction";
			} else {
				$actionList = "KBAction";
			}
			
			if ($actionList != '') {
				$actionList .= ",MeetingActivateAction";
			} else {
				$actionList = "MeetingActivateAction";
			}
			
			if ($actionList != '') {
				$actionList .= ",BookingActivateAction";
			} else {
				$actionList = "BookingActivateAction";
			}
			
			if ($actionList != '') {
				$actionList .= ",BookingActivateAction2";
			} else {
				$actionList = "BookingActivateAction2";
			}

			if ($actionList != '') {
				$actionList .= ",CarActivateAction";
			}
			else {
				$actionList = "CarActivateAction";
			}
			/*
			if ($actionList != '') {
				$actionList .= ",WorkflowFormActivateAction";
			} else {
				$actionList = "WorkflowFormActivateAction";
			}
			
			if ($actionList != '') {
				$actionList .= ",WorkflowDesignerActivateAction";
			} else {
				$actionList = "WorkflowDesignerActivateAction";
			}
			*/
			if ($actionList != '') {
				$actionList .= ",WorkflowActivateAction";
			} else {
				$actionList = "WorkflowActivateAction";
			}
			if ($actionList != '') {
				$actionList .= ",CalendarAction";
			} else {
				$actionList = "CalendarAction";
			}
			/*
			if ($actionList != '') {
				$actionList .= ",DMSAction";
			} else {
				$actionList = "DMSAction";
			}
			
			if ($actionList != '') {
				$actionList .= ",KBAction";
			} else {
				$actionList = "KBAction";
			}*/
			//Real Workflow App
			/*
			if ($actionList != '') {
				$actionList .= ",{
						text: '" . $lang ['action'] ['WorkflowActivate'] . "',
						menu: [" . $this->getWorkflowActionList ( 'list' ) . "],
						iconCls: 'workflowIcon'
					},CalendarAction";
			} else {
				$actionList = "{
						text: '" .  $lang ['action'] ['WorkflowActivate'] . "',
						menu: [" . $this->getWorkflowActionList ( 'list' ) . "],
						iconCls: 'workflowIcon'
					},CalendarAction";
			}
			*/
			return $actionList;
		}
	}
	
	/**
	 * ���ҧ������§ҹ
	 *
	 * @param string $mode
	 * @return string
	 */
	private function getReportActionList($mode = 'js') {
		global $config;
		global $lang;
		global $util;
		global $sessionMgr;
		
		/**
		 * Report Action List Contains 3 Type of reports
		 * 1 - General Reports
		 * 2 - System Reports
		 * 3 - Executive Reports
		 */
		
		if ($mode == 'js') {
			if ($config ['disableSarabanReport']) {
				$hiddenSarabanReport = 'true';
			} else {
				$hiddenSarabanReport = 'false';
			}
			$js = "var GeneralReportAction = new Ext.Action({
				text: '" . $lang ['action'] ['generalReport'] . "',
				hidden: {$hiddenSarabanReport},
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabGeneralReport')) {
						tabMain.add({
							id: 'tabGeneralReport',
							title: '" . $lang ['action'] ['generalReport'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=SarabanReportPortlet&portletMethod=getUI', scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabGeneralReport').show();
					}
			    },
			    iconCls: 'workflowIcon'
			});";
			
			if ($config ['disableAdminReport']) {
				$hiddenAdminReport = 'true';
			} else {
				$hiddenAdminReport = 'false';
			}
			$js .= "var SystemReportAction = new Ext.Action({
				text: '" . $lang ['action'] ['systemReport'] . "',
				hidden: {$hiddenAdminReport},
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabSystemReport')) {
						tabMain.add({
							id: 'tabSystemReport',
							title: '" . $lang ['action'] ['systemReport'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=AdminReportPortlet&portletMethod=getUI',scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabSystemReport').show();
					}
			    },
			    iconCls: 'documentFlowIcon'
			});";
			if ($config ['disableExecutiveReport']) {
				$hiddenExecutiveReport = 'true';
			} else {
				$hiddenExecutiveReport = 'false';
			}
			$js .= "var ExecutiveReportAction = new Ext.Action({
				text: '" . $lang ['action'] ['executiveReport'] . "',
				disabled: true,
				hidden: {$hiddenExecutiveReport},
			    handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	if(!tabMain.findById( 'tabExecReport')) {
						tabMain.add({
							id: 'tabExecReport',
							title: '" . $lang ['action'] ['executiveReport'] . "',
							iconCls: 'workflowIcon',
							autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=ExecutiveReportPortlet&portletMethod=getUI',scripts: true},
							closable:true
						}).show();
					} else {
						tabMain.findById('tabExecReport').show();
					}
			    },
			    iconCls: 'documentImageIcon'
			});";
			
			return $js;
		} else {
			$actionList = "";
			if ($actionList != '') {
				$actionList .= ",GeneralReportAction";
			} else {
				$actionList = "GeneralReportAction";
			}
			
			if ($actionList != '') {
				$actionList .= ",SystemReportAction";
			} else {
				$actionList = "SystemReportAction";
			}
			
			if ($actionList != '') {
				$actionList .= ",ExecutiveReportAction";
			} else {
				$actionList = "ExecutiveReportAction";
			}
			
			return $actionList;
		}
	
	}
	
	/**
	 * ���ҧ��������
	 *
	 * @param string $mode
	 * @return string
	 */
	private function getOtherActionList($mode = 'js') {
		global $config;
		global $lang;
		global $util;
		global $sessionMgr;
		global $policy;
		
		$manualHTML = "";
		
		if ($config ['manual'] ['DFEnable']) {
			if ($policy->isSG ()) {
				$manualHTML .= "<a target=\"_blank\" href=\"{$config['manual']['DF']}\">{$lang['manual']['DF']}</a><br/>";
			}
		}
		
		if ($config ['manual'] ['DMSEnable']) {
			$manualHTML .= "<a target=\"_blank\" href=\"{$config['manual']['DMS']}\">{$lang['manual']['DMS']}</a><br/>";
		}
		
		if ($config ['manual'] ['WFEnable']) {
			$manualHTML .= "<a target=\"_blank\" href=\"{$config['manual']['WF']}\">{$lang['manual']['WF']}</a><br/>";
		}
		
			$manualHTML .= "<a target=\"_blank\" href=\"http://backoffice.oic.or.th/manual/changePW.pdf\">�������¹���ʼ�ҹ</a><br/>";
			$manualHTML .= "<a target=\"_blank\" href=\"http://backoffice.oic.or.th/manual/Meeting.pdf\">�����͡�������ҹ�к��ͧ��ͧ��Ъ��</a><br/>";
			

		if ($config ['manual'] ['disable']) {
			$disableManual = 'true';
		} else {
			$disableManual = 'false';
		}
		if ($mode == 'js') {
			$js = "var mnuManual = new Ext.Action({
				text: 'Manaul',
				hidden: {$disableManual},
			    handler: function(){
			    	var manualBox = new Ext.Window({
						id: 'manualBox',
						title: 'Manual',
						width: 275,
						height: 155,
						minWidth: 275,
						minHeight: 155,
						resizable: false,
						modal: true,
						layout: 'fit',
						plain:true,
						bodyStyle:'padding:5px;',
						buttonAlign:'center',
						html: '<center><img src= \"/{$config['appName']}/images/logo/{$config['logo']}.gif\"/><br/><br/>{$manualHTML}</center>',
						closable: true
					});
					manualBox.show();
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "var mnuDownload = new Ext.Action({
				text: 'Download',
				handler: function(){
			    	var downloadBox = new Ext.Window({
						id: 'downloadBox',
						title: 'Download',
						width: 275,
						height: 155,
						minWidth: 275,
						minHeight: 155,
						resizable: false,
						modal: true,
						layout: 'fit',
						plain:true,
						bodyStyle:'padding:5px;',
						buttonAlign:'center',
						html: '<center><a href= \"/{$config['appName']}/CDMSPlugins.exe\">CDMS Plugin</a></center>',
						closable: true
					});
					downloadBox.show();
			    },
			    iconCls: 'workflowIcon'
			});";
			
			$js .= "
				var mnuAbout = new Ext.Action({
				text: 'About',
			    handler: function(){
					    	
				    var aboutBox = new Ext.Window({
						id: 'aboutBox',
						title: 'About',
						width: 275,
						height: 165,
						minWidth: 275,
						minHeight: 165,
						resizable: false,
						modal: true,
						layout: 'fit',
						plain:true,
						bodyStyle:'padding:5px;',
						buttonAlign:'center',
						html: '<center><img src= \"/{$config['appName']}/images/logo/{$config['logo']}.gif\"/><br/><br/>" . _APP_NAME_ . " version " . _APP_VERSION_ . "<br/>Codename : " . _CODE_NAME_ . "<br/><br/>URL: <a href=\"" . _WWW_LINK_ . "\">" . _WWW_LINK_ . "</a><br/>E-mail: " . _SUPPORT_EMAIL_ . "<br/>Tel.: " . _SUPPORT_TEL_ . "</center>',
						closable: true
					});
					aboutBox.show();
			    },
			    iconCls: 'documentFlowIcon'
			});";
			
			return $js;
		} else {
			$actionList = "";
			if ($actionList != '') {
				$actionList .= ",mnuManual";
			} else {
				$actionList = "mnuManual";
			}
			
			if ($actionList != '') {
				$actionList .= ",mnuAbout";
			} else {
				$actionList = "mnuAbout";
			}
			if ($actionList != '') {
				$actionList .= ",mnuDownload";
			} else {
				$actionList = "mnuDownload";
			}
			
			return $actionList;
		}
	}
	
	/**
	 * ���ҧ�������͡Ẻ�����
	 *
	 * @return string
	 */
	public function getSelectDocumentForm() {
		global $config;
		global $store;
		//global $conn;
		

		$formStore = $store->getDataStore ( 'formListDMS', 'formSelectStore' );
		
		include_once 'Form.Entity.php';
		$defaultForm = new FormEntity ( );
		$defaultForm->Load ( "f_default_form_dms = '1'" );
		
		$js = "
		
		$formStore
		
		formSelectStore.load();
		
		var documentAddForm = new Ext.form.FormPanel({
			id: 'documentAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'parentType',
				id: 'documentParentType',
				allowBlank: false,
				name: 'documentParentType',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'parentID',
				id: 'documentParentID',
				allowBlank: false,
				name: 'documentParentID',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'documentObjectMode',
				id: 'documentObjectMode',
				allowBlank: false,
				name: 'documentObjectMode',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			
			},{
				fieldLabel: 'Name',
				allowBlank: false,
				name: 'documentName',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'newDocFormHiddenID',
				fieldLabel: 'newDocFormHiddenID',
				allowBlank: true,
				name: 'newDocFormHiddenID',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},new Ext.form.ComboBox({
				id: 'newDocFormID',
				name: 'newDocFormID',
				allowBlank: false,
				fieldLabel: 'Form',
				store: formSelectStore,
				displayField:'name',
				valueField: 'id',
				typeAhead: false,
				value: {$defaultForm->f_form_id},
				triggerAction: 'all',
				emptyText:'Select Form ...',
				selectOnFocus:true
			})]
		});";
		
		$js .= "var documentAddWindow = new Ext.Window({
			id: 'documentAddWindow',
			title: 'Create New Document',
			width: 300,
			height: 100,
			minWidth: 300,
			minHeight: 100,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: documentAddForm,
			closable: false,
			modal: true,
			buttons: [{
				id: 'btnCreateDocument',
				text: 'Create Document',
				handler: function() {
					
	    			documentAddWindow.hide();
	    			
	    			//Ext.MessageBox.show({
	    			//	id: 'dlgSaveData',
			        //   	msg: 'Saving your data, please wait...',
			       	//   	progressText: 'Saving...',
			       	//    	width:300,
			       	//    	wait:true,
			       	/////    	waitConfig: {interval:200},
			       	//    	icon:'ext-mb-download'
			       	//	});
			       	
			       	documentAddForm.getForm().setValues([
            			//{id:'newDocFormID',value: Ext.getCmp('newDocFormID').getEl().id},
						{id:'newDocFormHiddenID',value: Ext.getCmp('newDocFormID').getValue()}
        			]);
        			
					var tabMain = Ext.getCmp('tpAdmin');
					var tabName = 'tabCreateDocument'+Ext.getCmp('newDocFormID').getValue();
					tabMain.add({
						//Autogen ID 
						title: 'Create Document',
						iconCls: 'workflowIcon',
						autoLoad: {
							url: '/{$config ['appName']}/document/create'
							,params: {
								newDocFormHiddenID: Ext.getCmp('newDocFormID').getValue()
								,parentID: Cookies.get('contextDMSObjectID')
								,parentType: Cookies.get('contextDMSObjectType')
								,parentMode: Cookies.get('contextDMSObjectMode')
							}
							, scripts: true
						},
						closable:true
					}).show();
					
			       	//Ext.Ajax.request({
		    		//	url: '/{$config ['appName']}/dms/create-document-form',
		    		//	method: 'POST',
		    		//	success: documentAddSuccess,
		    		//	failure: documentAddFailed,
		    		//	form: Ext.getCmp('documentAddForm').getForm().getEl()
		    		//});
			     
	    		}
			},{
				id: 'btnCancelCreateDocument',
				text: 'Cancel',
				handler: function() {
					documentAddWindow.hide();
				}
			}]
		});";
		
		$js .= "function documentAddSuccess() {
			Ext.MessageBox.hide();
			
			//DMSTree.getNodeById(Cookies.get('contextDMSElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Account Added!',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function documentAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to add Account!',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * ���ҧ������֧�׹�͡���
	 *
	 * @return string
	 */
	public function getCallbackJS() {
		global $config;
		global $lang;
		
		$js = " var callbackForm = new Ext.form.FormPanel({
            id: 'callbackForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout: 'form',
            monitorValid:true,

            items: [{
            	xtype: 'hidden',
				fieldLabel : 'xxx',
				allowBlank: false,
				id: 'callbackRefID',
				name: 'callbackRefID'
            },{
               	xtype: 'textarea',
               	fieldLabel: '{$lang['common']['remark']}',
				allowBlank: false,
				name: 'callbackText',
				id: 'callbackText',
				width: 275,
				height: 190
            }],
            
            buttons: [{
                    text: '{$lang['df']['callback']}',
                    formBind:true,
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
                                    Ext.MessageBox.show({
                                        msg: '���ѧ{$lang['df']['callback']}��س����ѡ����...',
                                        progressText: '�ѹ�֡������...',
                                        width:300,
                                        wait:true,
                                        waitConfig: {interval:200},
                                        icon:'ext-mb-download'
                                    });
                                    
                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/callback-document',
                                        method: 'POST',
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              callbackWindow.hide();
                                              var r = Ext.decode(o.responseText);
                                              Ext.MessageBox.show({
                                                title: '���{$lang['df']['callback']}',
                                                msg: '���{$lang['df']['callback']}���º��������',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('callbackForm').getForm().reset();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            callbackWindow.hide();
                                            Ext.MessageBox.show({
                                                 title: '���{$lang['df']['callback']}', 
                                                msg: '�������ö{$lang['df']['callback']}',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                        form: Ext.getCmp('callbackForm').getForm().getEl()
                                    }); 
                                }
                            }
                        });
                        
                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        callbackWindow.hide();
                    }
                }]
        });
        
        var callbackWindow = new Ext.Window({
            id: 'callbackWindow',
            title: '{$lang ['df'] ['callback']}',
            width: 410,
            height: 275,
            minWidth: 410,
            minHeight: 275,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: true,
            items: callbackForm,
            closable: false         
        });";
		return $js;
	}
	
	/**
	 * ���ҧẺ������觡�Ѻ
	 *
	 * @return string
	 */
	public function getSendbackJS() {
		global $config;
		global $lang;
		
		$js = " var sendbackForm = new Ext.form.FormPanel({
            id: 'sendbackForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout: 'form',
            monitorValid:true,

            items: [{
            	xtype: 'hidden',
            	fieldLabel: 'xxx',
				id: 'sendbackRefID',
				allowBlank: false,
				name: 'sendbackRefID'
            },{
               	xtype: 'textarea',
               	fieldLabel: '{$lang['common']['remark']}',
				allowBlank: false,
				name: 'sendbackText',
				id: 'sendbackText',
				width: 275,
				height: 190
            }],
            
            buttons: [{
                    text: '{$lang['df']['sendback']}',
                    formBind:true,
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
                                    Ext.MessageBox.show({
                                        msg: '���ѧ{$lang['df']['sendback']}�ҹ��س����ѡ����...',
                                        progressText: '�ѹ�֡������...',
                                        width:300,
                                        wait:true,
                                        waitConfig: {interval:200},
                                        icon:'ext-mb-download'
                                    });
                                    
                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/sendback-document',
                                        method: 'POST',
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              sendbackWindow.hide();
                                              var r = Ext.decode(o.responseText);
                                              Ext.MessageBox.show({
                                                title: '���{$lang['df']['sendback']}�ҹ',
                                                msg: '���{$lang['df']['sendback']}�ҹ���º��������',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('sendbackForm').getForm().reset();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            sendbackWindow.hide();
                                            Ext.MessageBox.show({
                                              title: '���{$lang['df']['sendback']}�ҹ',       
                                                msg: '�������ö{$lang['df']['sendback']}�ҹ',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                        form: Ext.getCmp('sendbackForm').getForm().getEl()
                                    });  
                                }
                            }
                        });
                        
                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        sendbackWindow.hide();
                    }
                }]
        });
        
        var sendbackWindow = new Ext.Window({
            id: 'sendbackWindow',
            title: '{$lang ['df'] ['sendback']}',
            width: 410,
            height: 275,
            minWidth: 410,
            minHeight: 275,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: true,
            items: sendbackForm,
            closable: false         
        });";
		return $js;
	}
	
	/**
	 * ���ҧ������ͺ���§ҹ
	 *
	 * @return string
	 */
	private function getAssignOrderJS() {
		global $config;
		global $lang;
		
		$js = "var assignOrderForm = new Ext.form.FormPanel({
            id: 'assignOrderForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,

            items: [{
                            xtype: 'hidden',
                            fieldLabel: '�����͡���',
                            allowBlank: false,
                            name: 'orderRecieverRefID',
                            readOnly: true
            },/*{
                            xtype:'textfield',
                            fieldLabel: '�ʹ�/�ͺ����',
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'orderReciever',
                            id: 'orderReciever'
            }*/new Ext.form.ComboBox({
                            id: 'orderReciever',
                            store: autocompleteReceiverInDeptStore,
                            name: 'orderReciever',                            
                            hiddenName: 'orderRecieverID',
                            fieldLabel: '�ͺ����/�ʹ�',
                            emptyText: 'Default',
                            minChars: 2,  
                            displayField:'name',
                            valueField: 'id',
                            typeAhead: false,
                            loadingText: '{$lang['common']['searcing']}',
                            width: 200,
                            pageSize:10,
                            hideTrigger:true,
                            tpl: resultAssignTpl,
                            labelStyle: 'font-weight:bold;color: Red;',
                            allowBlank: false,
                            itemSelector: 'div.search-item'
                        })],
            buttons: [{
                    text: '{$lang['common']['ok']}',
                    formBind:true,
                    handler: function() {
                        Ext.MessageBox.show({
                            id: 'dlgOrder',
                            msg: '���ѧ�ʹ�/�ͺ���§ҹ��س����ѡ����...',
                            progressText: '�ѹ�֡������...',
                            width:300,
                            wait:true,
                            waitConfig: {interval:200},
                            icon:'ext-mb-download'
                        });
                        
                        Ext.Ajax.request({
                            url: '/{$config ['appName']}/df-action/assign-order',
                            method: 'POST',
                            success: function(o){
                                  Ext.MessageBox.hide();
                                  assignOrderWindow.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: '����ʹ�/�ͺ���§ҹ',
                                    msg: '����ʹ�/�ͺ���§ҹ���º��������',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                });
                                Ext.getCmp('receiveInternalForm').getForm().reset();
                            },
                            failure: function(r,o) {
                                Ext.MessageBox.hide();
                                assignOrderWindow.hide();
                                Ext.MessageBox.show({
                                    title: '����ʹ�/�ͺ���§ҹ',
                                    msg: '�������ö�ʹ�/�ͺ���§ҹ',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                            },
                            form: Ext.getCmp('assignOrderForm').getForm().getEl()
                        });
                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        assignOrderWindow.hide();
                    }
                }]
        });

        var assignOrderWindow = new Ext.Window({
            id: 'assignOrderWindow',
            title: '{$lang ['workitem'] ['assign']}',
            width: 335,
            height: 125,
            minWidth: 335,
            minHeight: 125,
            layout: 'fit',
            modal: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: assignOrderForm,
            closable: false         
        });
        
        
        var reAssignOrderForm = new Ext.form.FormPanel({
            id: 'reAssignOrderForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,

            items: [{
				xtype: 'hidden',
				fieldLabel: '�����͡���',
				allowBlank: false,
				name: 'orderRecieverRefID2',
				readOnly: true
            },{
				xtype: 'hidden',
				fieldLabel: '����Order',
				allowBlank: false,
				name: 'orderID',
				readOnly: true
            },new Ext.form.ComboBox({
                            id: 'orderReciever2',
                            store: autocompleteReceiverInDeptStore,
                            name: 'orderReciever2',                            
                            hiddenName: 'orderRecieverID2',
                            fieldLabel: '�ͺ����/�ʹ�',
                            emptyText: 'Default',
                            minChars: 2,  
                            displayField:'name',
                            valueField: 'id',
                            typeAhead: false,
                            loadingText: '{$lang['common']['searcing']}',
                            width: 200,
                            pageSize:10,
                            hideTrigger:true,
                            tpl: resultAssignTpl,
                            labelStyle: 'font-weight:bold;color: Red;',
                            allowBlank: false,
                            itemSelector: 'div.search-item'
                        })],
            buttons: [{
                    text: '{$lang['common']['ok']}',
                    formBind:true,
                    handler: function() {
                        Ext.MessageBox.show({
                            id: 'dlgOrder',
                            msg: '���ѧ�ʹ�/�ͺ���§ҹ��س����ѡ����...',
                            progressText: '�ѹ�֡������...',
                            width:300,
                            wait:true,
                            waitConfig: {interval:200},
                            icon:'ext-mb-download'
                        });
                        
                        Ext.Ajax.request({
                            url: '/{$config ['appName']}/df-action/reassign-order',
                            method: 'POST',
                            success: function(o){
                                  Ext.MessageBox.hide();
                                  reAssignOrderWindow.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: '����ʹ�/�ͺ���§ҹ',
                                    msg: '����ʹ�/�ͺ���§ҹ���º��������',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                });
                                
	    						OAStore_0.reload();
                                Ext.getCmp('reAssignOrderForm').getForm().reset();
                            },
                            failure: function(r,o) {
                                Ext.MessageBox.hide();
                                reAssignOrderWindow.hide();
                                Ext.MessageBox.show({
                                    title: '����ʹ�/�ͺ���§ҹ',
                                    msg: '�������ö�ʹ�/�ͺ���§ҹ',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                            },
                            form: Ext.getCmp('reAssignOrderForm').getForm().getEl()
                        });
                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        reAssignOrderWindow.hide();
                    }
                }]
        });

        var reAssignOrderWindow = new Ext.Window({
            id: 'reAssignOrderWindow',
            title: '���{$lang ['workitem'] ['assign']}',
            width: 335,
            height: 125,
            minWidth: 335,
            minHeight: 125,
            layout: 'fit',
            modal: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: reAssignOrderForm,
            closable: false         
        });";
		return $js;
	}
	
	/**
	 * ���ҧ AutoComplete Control
	 *
	 * @return string
	 */
	private function getAutoCompleteJS() {
		global $config;
		
		$js = "
        
        
        var resultTpl = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
					'<tr><td align=\"right\"><font color=\"#000099\">{orgName}</font></td></tr>',
                    '<tr><td align=\"right\">������:{desctype}</td></tr>',
                '</table>',               
            '</div></tpl>'
        );
        
        var resultDepartmentTpl = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">�ѧ�Ѵ:{desctype}</td></tr>',
                '</table>',               
            '</div></tpl>'
        );
        
        var resultContactList = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-contact\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">������: Contact List</td></tr>',
                '</table>',               
            '</div></tpl>'
        );
        
        resultTpl.compile();
        resultDepartmentTpl.compile();
        resultContactList.compile();
        
        var autocompleteDeptReceiverTextStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/receiver-text-unlimit'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
				{name: 'orgName'},
                {name: 'typeid'},
                {name: 'desctype'}  
            ])
        });
        
        var autocompleteReceiverTextStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/receiver-text'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'},
				{name: 'orgName'}
            ])
        });        
        
        var autocompleteSenderTextStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/sender-text'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}  
            ])
        });       
        
        
        var autocompleteSenderExternalStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/sender-external'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name', mapping: 'name'},
                {name: 'id', mapping: 'id'},
                {name: 'desctype', mapping: 'desctype'},
                
            ])
        });

        var autocompleteSenderExternalListStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/sender-external-list'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name', mapping: 'name'},
                {name: 'id', mapping: 'id'},
                {name: 'desctype', mapping: 'desctype'},
                
            ])
        });

        var autocompleteSenderExternalFilterStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/sender-external-filter'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name', mapping: 'name'},
                {name: 'id', mapping: 'id'},
                {name: 'desctype', mapping: 'desctype'},
                
            ])
        });
        
        var autocompleteReceiverExternalStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/receiver-external'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}  
            ])
        });
        
        var autocompleteReceiverExternalAllStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/receiver-external-all'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}  
            ])
        });
        
        var autocompleteContactListStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/contact-list'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'desctype'},
                {name: 'tolist'},
                {name: 'cclist'}  
            ])
        });
        
        var autocompleteExternalContactListStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/external-contact-list'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'desctype'},
                {name: 'tolist'},
                {name: 'cclist'}  
            ])
        });
        
        var autocompleteOrganizeStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/organize-only'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}  
            ])
        });
        
        
        //autocompleteReceiverExternalStore.load(); 
        //autocompleteReceiverTextStore.load();
        //autocompleteSenderExternalStore.load();
        //autocompleteContactListStore.load();
        //autocompleteOrganizeStore.load();";
		
		return $js;
	}
	
	/**
	 * ���ҧ Record Definition �ͧ��¡�ü���Ѻ
	 *
	 * @return unknown
	 */
	private function getRecordDefinitionJS() {
		$js = " var ReceiverRecordDataDef = Ext.data.Record.create([
            {name: 'dataid'},
            {name: 'name'},
            {name: 'description'},
            {name: 'typeid'}
        ]);
        
         var SeeAlsoRecordDataDef = Ext.data.Record.create([
            {name: 'dataid'},
            {name: 'name'},
            {name: 'description'},
            {name: 'typeid'}
        ]);";
		
		return $js;
	}
	
	/**
	 * ���ҧ���������¹����Ѻ
	 *
	 * @return string
	 */
	private function getChangeReceiverJS() {
		global $config;
		global $lang;
		
		$js = "var changeReceiverInternalForm = new Ext.form.FormPanel({
            id: 'changeReceiverInternalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,

            items: [{
                xtype: 'hidden',
                fieldLabel: '�����͡���',
                id: 'refTransChangeIntID',
                name: 'refTransChangeIntID'
            },{
                xtype: 'hidden',
                fieldLabel: '�����͡���',
                id: 'changeRecvType',
                name: 'changeRecvType'
            },new Ext.form.ComboBox({
                store: autocompleteReceiverTextStore,
                fieldLabel: '{$lang['df']['changeReceiver']}',
                displayField:'name',
                typeAhead: false,
                emptyText: 'Default',
                style: autoFieldStyle,
                tabIndex: 1,
                loadingText: '{$lang['common']['searcing']}',
                width: 180,
                hideTrigger:true,     
                id: 'changeRecvInt',                          
                name: 'changeRecvInt',
                hiddenName: 'changeRecvIntID',   
                valueField: 'id',    
                tpl: resultTpl,
                minChars: 2,
                shadow: false,
                autoLoad: true,
                mode: 'remote',
                itemSelector: 'div.search-item'
            })],
            buttons: [{
                    text: '{$lang['common']['ok']}',
                    formBind:true,
                    handler: function() {
                        Ext.MessageBox.show({
                            msg: '���ѧ...',
                            progressText: '�ѹ�֡������...',
                            width:300,
                            wait:true,
                            waitConfig: {interval:200},
                            icon:'ext-mb-download'
                        });
                        
                        Ext.Ajax.request({
                            url: '/{$config ['appName']}/df-action/change-receiver',
                            method: 'POST',
                            success: function(o){
                                  Ext.MessageBox.hide();
                                  changeReceiverInternalWindow.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: '�������¹����Ѻ',
                                    msg: '���Թ������º��������',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                });
                                Ext.getCmp('changeReceiverInternalForm').getForm().reset();
                            },
                            failure: function(r,o) {
                                Ext.MessageBox.hide();
                                changeReceiverInternalWindow.hide();
                                Ext.MessageBox.show({
                                    title: '�������¹����Ѻ',
                                    msg: '�������ö���Թ�����',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                            },
                            form: Ext.getCmp('changeReceiverInternalForm').getForm().getEl()
                        });
                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        changeReceiverInternalWindow.hide();
                    }
                }]
        });

        var changeReceiverInternalWindow = new Ext.Window({
            id: 'changeReceiverInternalWindow',
            title: '{$lang ['df'] ['changeReceiver']}({$lang ['common'] ['internal']})',
            width: 335,
            height: 125,
            minWidth: 335,
            minHeight: 125,
            layout: 'fit',
            modal: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: changeReceiverInternalForm,
            closable: false         
        });
        
        ";
		
		$js .= "var changeReceiverExternalForm = new Ext.form.FormPanel({
            id: 'changeReceiverExternalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,
                        
            items: [{
                xtype: 'hidden',
                fieldLabel: '�����͡���',
                id: 'refTransChangeExtID',
                name: 'refTransChangeExtID'
            },{
                xtype: 'hidden',
                fieldLabel: '�����͡���',
                id: 'changeRecvExtType',
                name: 'changeRecvExtType'
            },new Ext.form.ComboBox({
                store: autocompleteReceiverExternalStore,
                fieldLabel: '{$lang['df']['changeReceiver']}',
                displayField:'name',
                typeAhead: false,
                emptyText: 'Default',
                style: autoFieldStyle,
                tabIndex: 1,
                loadingText: '{$lang['common']['searcing']}',
                width: 180,
                hideTrigger:true,     
                id: 'changeRecvExt',                          
                name: 'changeRecvExt',
                hiddenName: 'changeRecvExtID',   
                valueField: 'id',    
                tpl: resultTpl,
                minChars: 2,
                shadow: false,
                autoLoad: true,
                mode: 'remote',
                itemSelector: 'div.search-item'
            })],
            buttons: [{
                    text: '{$lang['common']['ok']}',
                    formBind:true,
                    handler: function() {
                        Ext.MessageBox.show({
                            msg: '���ѧ...',
                            progressText: '�ѹ�֡������...',
                            width:300,
                            wait:true,
                            waitConfig: {interval:200},
                            icon:'ext-mb-download'
                        });
                        Ext.Ajax.request({
                            url: '/{$config ['appName']}/df-action/change-receiver-external',
                            method: 'POST',
                            success: function(o){
                                  Ext.MessageBox.hide();
                                  changeReceiverExternalWindow.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: '�������¹����Ѻ',
                                    msg: '���Թ������º��������',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                });
                                Ext.getCmp('changeReceiverExternalForm').getForm().reset();
                            },
                            failure: function(r,o) {
                                Ext.MessageBox.hide();
                                changeReceiverExternalWindow.hide();
                                Ext.MessageBox.show({
                                    title: '�������¹����Ѻ',
                                    msg: '�������ö���Թ�����',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                            },
                            params: {
                            	textParam: Ext.getCmp('changeRecvExt').getRawValue()
							},
                            form: Ext.getCmp('changeReceiverExternalForm').getForm().getEl()
                        });
                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        changeReceiverExternalWindow.hide();
                    }
                }]
        });

        var changeReceiverExternalWindow = new Ext.Window({
            id: 'changeReceiverExternalWindow',
            title: '{$lang ['df'] ['changeReceiver']}({$lang ['common'] ['external']})',
            width: 335,
            height: 125,
            minWidth: 335,
            minHeight: 125,
            layout: 'fit',
            modal: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: changeReceiverExternalForm,
            closable: false         
        });
        
        Ext.getCmp('changeRecvExt').on('select',function(c,r,i) {
            alert(Ext.getCmp('changeRecvExt').getRawValue());    
        },this);";
		return $js;
	}
	
	/**
	 * ���ҧ������§ҹ�š�Ѻ
	 *
	 * @return string
	 */
	private function getReportOrderJS() {
		global $config;
		global $lang;
		
		$js = " var reportOrderForm = new Ext.form.FormPanel({
            id: 'reportOrderForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout: 'form',
            monitorValid:true,

            items: [{
				xtype: 'hidden',
				fieldLabel: '�����͡���',
				allowBlank: false,
				name: 'reportOrderRefID',
				readOnly: true
            },{
               	xtype: 'textarea',
               	fieldLabel: '��§ҹ',
				allowBlank: false,
				name: 'reportText',
				id: 'reportText',
				width: 275,
				height: 190
            }],
            
            buttons: [{
                    text: '{$lang['common']['ok']}',
                    formBind:true,
                    handler: function() {
                        Ext.MessageBox.show({
                            msg: '���ѧ�ѹ�֡��§ҹ��س����ѡ����...',
                            progressText: '�ѹ�֡������...',
                            width:300,
                            wait:true,
                            waitConfig: {interval:200},
                            icon:'ext-mb-download'
                        });
                        
                        Ext.Ajax.request({
                            url: '/{$config ['appName']}/df-action/report-order',
                            method: 'POST',
                            success: function(o){
                                  Ext.MessageBox.hide();
                                  reportOrderWindow.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: '��úѹ�֡��§ҹ',
                                    msg: '��úѹ�֡��§ҹ���º��������',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                });
                                Ext.getCmp('reportOrderForm').getForm().reset();
                            },
                            failure: function(r,o) {
                                Ext.MessageBox.hide();
                                reportOrderWindow.hide();
                                Ext.MessageBox.show({
                                   title: '��úѹ�֡��§ҹ',   
                                    msg: '�������ö�ѹ�֡��§ҹ',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                            },
                            form: Ext.getCmp('reportOrderForm').getForm().getEl()
                        });
                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        reportOrderWindow.hide();
                    }
                }]
        });
        
        var reportOrderWindow = new Ext.Window({
            id: 'reportOrderWindow',
            title: '{$lang ['workitem'] ['report']}',
            width: 410,
            height: 275,
            minWidth: 410,
            minHeight: 275,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: true,
            items: reportOrderForm,
            closable: false         
        });";
		return $js;
	}
	
	/**
	 * ���ҧ utility js ��ҧ�
	 *
	 * @return string
	 */
	private function getUtilityJS() {
		global $config;
		global $lang;
		global $debugMode;
		
		if ($debugMode) {
			$jsDebugFn = "function jsDebug (txt) {
                console.log(txt);
            }";
		} else {
			$jsDebugFn = "function jsDebug (txt) {
                
            }";
		}
		
		$js = "
        
        {$jsDebugFn}
        
        var methodStore = [
        	['1', 'Append'],
        	['2', 'Replace'],
        	['3', 'Insert']
        ];
        
        var revisionMethodData = [
        	['0', 'None'],
        	['1', 'Major'],
        	['2', 'Minor'],
        	['3', 'Branch']
        ];
        
        var methodStore = new Ext.data.SimpleStore({
	        fields: ['id', 'name'],
	        data : methodStore
	    });
	    
        var revisionMethodStore = new Ext.data.SimpleStore({
	        fields: ['id', 'name'],
	        data : revisionMethodData
	    });
        
		function commonWaitDialog() {
			Ext.MessageBox.show({
				msg: '���ѧ���Թ���',
		        progressText: 'Processing...',
		        width:300,
		        wait:true,
				waitConfig: {interval:200},
				icon:'ext-mb-download'
			});
		}  

		function customWaitDialog(msgText,psText) {
			Ext.MessageBox.show({
				msg: msgText,
		        progressText: psText,
		        width:300,
		        wait:true,
				waitConfig: {interval:200},
				icon:'ext-mb-download'
			});
		}
        
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
        
        String.prototype.stripHTML = function()
		{
		        // What a tag looks like
		        var matchTag = /<(?:.|\s)*?>/g;
		        // Replace the tag
		        return this.replace(matchTag, \"\");
		};
        
        function printpr() { 
			var OLECMDID = 7; 
			/* OLECMDID values: 
			* 6 - print 
			* 7 - print preview 
			* 1 - open window 
			* 4 - Save As 
			*/
			var PROMPT = 1; // 2 DONTPROMPTUSER 
			var WebBrowser = '<OBJECT ID=\"WebBrowser1\" WIDTH=0 HEIGHT=0 CLASSID=\"CLSID:8856F961-340A-11D0-A96B-00C04FD705A2\"></OBJECT>'; 
			document.body.insertAdjacentHTML('beforeEnd', WebBrowser); 
			WebBrowser1.ExecWB(OLECMDID, PROMPT); 
			WebBrowser1.outerHTML = \"\"; 
		} 
		
		function printWindow(){
			browserVersion = parseInt(navigator.appVersion)
			if (browserVersion >= 4) window.print()
		}
        
        function requestCheckSession() {
            return true;
            Ext.Ajax.request({
                url: '/{$config ['appName']}/session.php',
                method: 'POST',
                success: function(o){
                    var r = Ext.decode(o.responseText);
                    return checkSession2(r);  
                },
                failure: function(r,o) {
                    return false;
                }
            });            
        }
        
        function checkSession2(response) {
            return true;
            if(response.redirectLogin == 1) {
                return false;
            } else {
                return true;
            }
        }
        
		function checkSession(response) {
			if(response.redirectLogin == 1) {
                Ext.MessageBox.alert('Session Expired', 'Your Session is Expired.'); 
                /*
				Ext.MessageBox.show({
					msg: 'Session Expired',
					progressText: 'Force logging out',
					width:300,
					wait:true,
					waitConfig: {interval:50},
					icon:'ext-mb-download'
				});
                */
				top.window.document.location.reload();
			}
		}
        
        function doRedirectLogout() {
            top.window.document.location.href ='/{$config['appName']}/login/logout';
        }
		
		function sessionExpired() {
            Ext.MessageBox.hide();
            //Ext.MessageBox.alert('Session Expired', 'Your Session is Expired.');
			Ext.MessageBox.show({
				msg: '{$lang ['session'] ['expirePrompt']}',
				progressText: 'Force logging out',
				width:300,
				wait:true,
				waitConfig: {interval:50},
				icon:'ext-mb-download'
			});
            var delaytask = new Ext.util.DelayedTask();
            delaytask.delay( 1500, doRedirectLogout);
			
		}
        
        

    	function closeCurrentTab() {
    		Ext.getCmp('tpAdmin').remove(Cookies.get('at'));
    	}    
	    
		var Url = {
		
		    encode : function (string) {
		        return escape(this._utf8_encode(string));
		    },
		
		    decode : function (string) {
		        return this._utf8_decode(unescape(string));
		    },
		
		    _utf8_encode : function (string) {
		        string = string.replace(/\\r\\n/g,\"\\n\");
		        var utftext = \"\";
		
		        for (var n = 0; n < string.length; n++) {
		
		            var c = string.charCodeAt(n);
		
		            if (c < 128) {
		                utftext += String.fromCharCode(c);
		            }
		            else if((c > 127) && (c < 2048)) {
		                utftext += String.fromCharCode((c >> 6) | 192);
		                utftext += String.fromCharCode((c & 63) | 128);
		            }
		            else {
		                utftext += String.fromCharCode((c >> 12) | 224);
		                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
		                utftext += String.fromCharCode((c & 63) | 128);
		            }
		
		        }
		
		        return utftext;
		    },
		
		    // private method for UTF-8 decoding
		    _utf8_decode : function (utftext) {
		        var string = \"\";
		        var i = 0;
		        var c = c1 = c2 = 0;
		
		        while ( i < utftext.length ) {
		
		            c = utftext.charCodeAt(i);
		
		            if (c < 128) {
		                string += String.fromCharCode(c);
		                i++;
		            }
		            else if((c > 191) && (c < 224)) {
		                c2 = utftext.charCodeAt(i+1);
		                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
		                i += 2;
		            }
		            else {
		                c2 = utftext.charCodeAt(i+1);
		                c3 = utftext.charCodeAt(i+2);
		                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
		                i += 3;
		            }
		
		        }
		
		        return string;
		    }
		
		}

		function showCalendar(id, format, showsTime, showsOtherMonths) {
		  var el = document.getElementById(id);
		  if (_dynarch_popupCalendar != null) {
			// we already have some calendar created
			_dynarch_popupCalendar.hide();                 // so we hide it first.
		  } else {
			// first-time call, create the calendar.
			var cal = new Calendar(1, null, selected, closeHandler);
			// uncomment the following line to hide the week numbers
			// cal.weekNumbers = false;
			if (typeof showsTime == \"string\") {
			  cal.showsTime = true;
			  cal.time24 = (showsTime == \"24\");
			}
			if (showsOtherMonths) {
			  cal.showsOtherMonths = true;
			}
			_dynarch_popupCalendar = cal;                  // remember it in the global var
			cal.setRange(1900, 2070);        // min/max year allowed.
			cal.create();
		  }
		  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
		  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
		  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use

		  // the reference element that we pass to showAtElement is the button that
		  // triggers the calendar.  In this example we align the calendar bottom-right
		  // to the button.
		  _dynarch_popupCalendar.showAtElement(el.nextSibling, \"Br\");        // show the calendar

		  return false;
		}
				
    	
    	";
		
		return $js;
	}
	
	/**
	 * ���ҧ JS ����Ѻ Main Console
	 *
	 */
	public function mainJsAction() {
		
		//include_once 'ECMAccordian.php';
		

		global $config;
		global $lang;
		global $store;
		global $sessionMgr;
		global $license;
		global $backendOptions;
		global $policy;

		
		if ($sessionMgr->isDegradeMode ()) {
			$disableToolsMainMenu = 'true';
			$disabledRegisterMainMenu = 'true';
		} else {
			$disableToolsMainMenu = 'false';
			$disabledRegisterMainMenu = 'false';
		}
			if (!$policy->isSG ()) {
				$hiddenSB = 'true';
			}
			else{
				$hiddenSB = 'false';
			}
		//var_dump($_GET);
		

		if ($config ['portletLayoutMode'] == 'DMS') {
			$portalURL = "/{$config ['appName']}/default-portal/get-ui?PLM=DMS";
			$config ['autoExpandExplorer'] = true;
			$config ['activeExplorerTab'] = 'DMS';
			setcookie ( 'axe', 1, 0, '/' );
			setcookie ( 'atet', 1, 0, '/' );
			$specialScript = "Ext.getCmp('ECMExplorer').expand();
				if(typeof Ext.getCmp('DMSexplorer') == 'object') {
					Ext.getCmp('DMSexplorer').show();
				}
			";
		} else {
			$portalURL = "/{$config ['appName']}/default-portal/get-ui";
			$specialScript = "";
		}
		
		if ($config ['disableReport']) {
			$disableReport = 'true';
		} else {
			$disableReport = 'false';
		}
		
		$explorerJS = "";
		$crackLicenseMode = true;
		
		/*
		if (! $license->noWorksLicense () || $crackLicenseMode) {
			$accordianJS = ECMAccordian::getAccordianList ();
			$explorerJS .= "{
				id: 'MainTaskAccordian',
				//autoLoad: {url: '/{$config ['appName']}/docflow-explorer/get-ui', scripts: true},
				title: '" . $lang ['action'] ['Works'] . "',
				//autoScroll:true,
				//title:'West',
                split:true,
                width: 200,
                minSize: 175,
                maxSize: 400,
                collapsible: true,
                margins:'0 0 0 5',
                layout:'accordion',
                layoutConfig:{
                    animate: false
                },
                items: [{$accordianJS}]
			}";
		}
		*/
		
		/*
		if ($license->check ( 'DOCUMENT' ) || $crackLicenseMode) {
			if (trim ( $explorerJS ) != '') {
				$explorerJS .= ",{
					id: 'DMSexplorer',
					autoLoad: {url: '/{$config ['appName']}/dms-explorer/get-ui', scripts: true},
					title: '" . $lang ['DMS'] . "',
					autoScroll:true
				}";
			} else {
				$explorerJS .= "{
					id: 'DMSexplorer',
					autoLoad: {url: '/{$config ['appName']}/dms-explorer/get-ui', scripts: true},
					title: '" . $lang ['DMS'] . "',
					autoScroll:true
				}";
			
			}
		}
		*/
		
		/*
		if ($license->check ( 'KBASE' ) || $crackLicenseMode) {
			if (trim ( $explorerJS ) != '') {
				$explorerJS .= ",{
					id: 'KBExplorer',
					autoLoad: {url: '/{$config ['appName']}/kbase-explorer/get-ui', scripts: true},
					title: '" . $lang ['KB'] . "',
					autoScroll:true
				}";
			} else {
				$explorerJS .= "{
					id: 'KBExplorer',
					autoLoad: {url: '/{$config ['appName']}/kbase-explorer/get-ui', scripts: true},
					title: '" . $lang ['KB'] . "',
					autoScroll:true
				}";
			}
		}
		*/
		
		$jsForm = $this->getSelectDocumentForm ();
		$acceptDocTypeStore = $store->getDataStore ( 'documentTypeList', 'acceptDocTypeStore' );
		$mainJS = "         
		          
		jQuery.noConflict();
		var \$j = jQuery;
		//Ext.Ajax.defaultHeaders = {'Content-Type' : 'text/html; charset=tis-620'}; 
		//Ext.lib.Ajax.setDefaultPostHeader(false);
        
        {$this->getAutoCompleteJS()}
		
        {$acceptDocTypeStore}
        acceptDocTypeStore.load();
		{$jsForm}
		
		{$this->getCallbackJS()}
		{$this->getSendbackJS()}
        
		var autoFieldStyle = {
					\"background-image\": \"url(images/icons/element_view.png)\",
					\"background-repeat\": \"no-repeat\",
					\"background-position\": \"right\"
		};
		
		var popupFieldStyle = {
					\"background-image\": \"url(images/icons/element_edit.png)\",
					\"background-repeat\": \"no-repeat\",
					\"background-position\": \"right\"
		};
        
        function checkSession(r) {
            if(r.sessionExpire) {
                top.window.document.location = '/{$config['appName']}/login/logout';
            }
        }
		
		function storeToJSON(store){
	        var j = '[';
	        store.each(function(r){
	            j += Ext.util.JSON.encode(r.data) + ',';
	        });
	        j = j.substring(0, j.length - 1) + ']';
	        return j;
	    }
        
        function popupOldApp(name,url){
            var winname   = 'oldApp'+name;
            src = url;
            var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
            'scrollbars=yes,resizable=no,copyhistory=no,height='+screen.height+',width='+screen.width;
            winObj = window.open(src,winname,winconfig);
            winObj.opener = self;
            winObj.focus();
        }
		
		function popupTrack(transType,transID){
			var winname   = 'trackingWin_'+transType+'_'+transID;
			src = '/{$config['appName']}/visual-track/view?transType='+transType+'&transID='+transID;
			var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
			'scrollbars=yes,resizable=no,copyhistory=no,width=650,height=550';
			winObj = window.open(src,winname,winconfig);
			winObj.opener = self;
			winObj.focus();
		}
		
		function popupExcelReport(mode,page){
			var winname   = 'excelReport'+mode;
			switch(mode) {
				case 'RG':
                    src = '/{$config['appName']}/df-report/receive-external-global?page='+page;
                    break;
                case 'RC':
                    src = '/{$config['appName']}/df-report/receive-circ?page='+page;
                    break;
				case 'RI':
					src = '/{$config['appName']}/df-report/receive-internal?page='+page;
					break;
				case 'RE':
					src = '/{$config['appName']}/df-report/receive-external?page='+page;
					break;
				case 'SG':
                    src = '/{$config['appName']}/df-report/send-external-global?page='+page;
                    break;
                case 'SGI':
                    src = '/{$config['appName']}/df-report/send-internal-global?page='+page;
                    break;
                case 'SC':
                    src = '/{$config['appName']}/df-report/send-circ?page='+page;
                    break;
				case 'SI':
					src = '/{$config['appName']}/df-report/send-internal?page='+page;
					break;
				case 'SE':
					src = '/{$config['appName']}/df-report/send-external?page='+page;
					break;
				case 'FW':
					src = '/{$config['appName']}/df-report/forward?page='+page;
					break;
				case 'RS':
					src = '/{$config['appName']}/df-report/receive-secret?page='+page;
					break;
				case 'RSI':
					src = '/{$config['appName']}/df-report/receive-secret-int?page='+page;
					break;
				case 'RSE':
					src = '/{$config['appName']}/df-report/receive-secret-ext?page='+page;
					break;
				case 'SS':
					src = '/{$config['appName']}/df-report/send-secret?page='+page;
					break;
				case 'SSI':
					src = '/{$config['appName']}/df-report/send-secret-int?page='+page;
					break;
				case 'SSE':
					src = '/{$config['appName']}/df-report/send-secret-ext?page='+page;
					break;
				default:
					src = '';
					break;
			}
			if(src != '') {
				var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
				'scrollbars=yes,resizable=no,copyhistory=no,width=650,height=550';
				winObj = window.open(src,winname,winconfig);
				winObj.opener = self;
				winObj.focus();
			}
		}
		
		function openPortletToTab(tabID,tabTitle,paramPortletClass,paramPortletMethod) {
		    var tabMain = Ext.getCmp('tpAdmin');
		    tabTitle = tabTitle.stripHTML();
			if(!tabMain.findById( tabID)) {
				tabMain.add({
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
				tabMain.findById(tabID).show();
			}
		}
		
		function popupDocLog(transID){
			var winname   = 'logWin_'+transID;
			src = '/{$config['appName']}/document/view-log?transID='+transID;
			var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
			'scrollbars=yes,resizable=yes,copyhistory=no,width=650,height=600';
			//winObj = window.open(src,winname);
            window.open(src,winname); 
			//winObj.opener = self;
			winObj.focus();
		}
		
		function popupDocLog2(docID){
			var winname   = 'logWin2_'+docID;
			src = '/{$config['appName']}/document/view-log?docID='+docID;
			var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
			'scrollbars=yes,resizable=yes,copyhistory=no,width=650,height=600';
			//winObj = window.open(src,winname);
            window.open(src,winname); 
			//winObj.opener = self;
			winObj.focus();
		}
		
		function popupPrintJasper(docID,type){
			var winname   = 'JasperWin_'+docID+type;
			src = '/{$config['appName']}/jasper/print?docID='+docID+'&type='+type;
			var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
			'scrollbars=yes,resizable=yes,copyhistory=no,width=200,height=200';
			//winObj = window.open(src,winname);
            window.open(src,winname,winconfig); 
			//winObj.opener = self;
			winObj.focus();
		}
        
        function popupAnnounceviewer(docID,pageID){
            var winname   = 'viewAttach_'+docID;
            src = '/{$config['appName']}/viewer/announce?docID='+docID+'&pageID='+pageID;
            var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
            'scrollbars=yes,resizable=no,copyhistory=no,width=650,height=550';
            winObj = window.open(src,winname,winconfig);
            winObj.opener = self;
            winObj.focus();
        }
		
		function popupAttachviewer(docID,pageID){
			var winname   = 'viewAttach_'+docID;
			src = '/{$config['appName']}/viewer/default?docID='+docID+'&pageID='+pageID;
			var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
			'scrollbars=yes,resizable=no,copyhistory=no,width=1010,height=830';
			winObj = window.open(src,winname,winconfig);
			winObj.opener = self;
			winObj.focus();
		}
		
		function popupTempAttachviewer(docID,pageID){
			var winname   = 'viewAttach_'+docID;
			src = '/{$config['appName']}/viewer/temp?docID='+docID+'&pageID='+pageID;
			var winconfig = 'toolbar=no,location=no,directories=no,status=yes,menubar=no,'+
			'scrollbars=yes,resizable=no,copyhistory=no,width=650,height=550';
			winObj = window.open(src,winname,winconfig);
			winObj.opener = self;
			winObj.focus();
		}
		
		function viewAnnounce(tabID,tabTitle,moduleType,docRefID,refTransID,extendParam) {
			var tabMain = Ext.getCmp('tpAdmin');
			if(!tabMain.findById( tabID)) {
				tabMain.add({
					id: tabID,
					title: '�٤����/��С��['+tabTitle+']',
					iconCls: 'workflowIcon',
					autoLoad: {
						url: '/{$config ['appName']}/document/view-announce'
						,params: {
							callModuleType: moduleType
							,docID: docRefID
							,docRefTransID: refTransID
                            ,docExtendParam: extendParam
						}
						, scripts: true
					},
					closable:true
				}).show();
			} else {
				tabMain.findById(tabID).show();
			}
			var previousWindowMode = getECMData('_pw');
			if(previousWindowMode != '') {				
				//createExtraBookWindow
				if(previousWindowMode == 'cmd') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('createExtraBookWindow').show();			
					},this);
				}
				saveECMData('_pw','');
			}
		}
		
		function viewDocumentCrossModule(tabID,tabTitle,moduleType,docRefID,refTransID,extendParam) {
			var tabMain = Ext.getCmp('tpAdmin');
			tabTitle = tabTitle.stripHTML();
			if(!tabMain.findById( tabID)) {
				tabMain.add({
					id: tabID,
					title: '���͡���['+tabTitle+']',
					iconCls: 'workflowIcon',
					autoLoad: {
						url: '/{$config ['appName']}/document/view-cross-module'
						,params: {
							callModuleType: moduleType
							,docID: docRefID
							,docRefTransID: refTransID
                            ,docExtendParam: extendParam
						}
						, scripts: true
					},
					closable:true
				}).show();
			} else {
				tabMain.findById(tabID).show();
			}
			var previousWindowMode = getECMData('_pw');
			if(previousWindowMode != '') {				
				//RI
				if(previousWindowMode == 'ri') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('receiveInternalWindow').show();			
					},this);
				}

				//SI
				if(previousWindowMode == 'si') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('sendInternalWindow').show();			
					},this);
				}

				//sendInternalGlobalWindow
				if(previousWindowMode == 'sig') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('sendInternalGlobalWindow').show();			
					},this);
				}

				//receiveExternalWindow
				if(previousWindowMode == 're') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('receiveExternalWindow').show();			
					},this);
				}

				//sendExternalWindow
				if(previousWindowMode == 'se') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('sendExternalWindow').show();			
					},this);
				}

				//receiveExternalGlobalWindow
				if(previousWindowMode == 'reg') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('receiveExternalGlobalWindow').show();			
					},this);
				}

				//sendExternalGlobalWindow
				if(previousWindowMode == 'seg') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('sendExternalGlobalWindow').show();			
					},this);
				}

				//createExtraBookWindow
				if(previousWindowMode == 'cmd') {
					Ext.getCmp(tabID).on('destroy',function(p) {
						Ext.getCmp('createExtraBookWindow').show();			
					},this);
				}
				saveECMData('_pw','');
			}
		}
		
		var resultAssignTpl = new Ext.XTemplate(
	        '<tpl for=\".\"><div class=\"search-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">������:{desctype}</td></tr>',
                '</table>',               
	        '</div></tpl>'
	    );

		var roleAvaiTpl = new Ext.XTemplate(
	        '<tpl for=\".\"><div class=\"search-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\" style=\"font-size:11px;color:blue;\">˹��§ҹ:{orgName}</td></tr>',
                '</table>',               
	        '</div></tpl>'
	    );
	    
		var autocompleteReceiverInDeptStore = new Ext.data.Store({
	        proxy: new Ext.data.ScriptTagProxy({
	        	url: '/{$config ['appName']}/auto-complete/receiver-in-dept'
	        }),
	        reader: new Ext.data.JsonReader({
	            root: 'results',
	            totalProperty: 'total',
	            id: 'id'
	        }, [
	            {name: 'name'},
	            {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}  
	        ])
	    });
	    
	    var resultSecureGroupTpl = new Ext.XTemplate(
	        '<tpl for=\".\"><div class=\"search-item-secure-group-name\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">ʶҹ�:{status}</td></tr>',
                '</table>',
	        '</div></tpl>'
	    );
        
	    var autocompleteSecureGroupStore = new Ext.data.Store({
	        proxy: new Ext.data.ScriptTagProxy({
	        	url: '/{$config ['appName']}/auto-complete/secure-group'
	        }),
	        reader: new Ext.data.JsonReader({
	            root: 'results',
	            totalProperty: 'total',
	            id: 'id'
	        }, [
	            {name: 'name'},
	            {name: 'id'},
                {name: 'ownerid'},
				{name: 'status'}
	        ])
	    });	
	    
	    var resultSecureObjectTpl = new Ext.XTemplate(
	        '<tpl for=\".\"><div class=\"search-item-secure-group\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">������:{desctype}</td></tr>',
                '</table>',               
	        '</div></tpl>'
	    );
	    
	    var autocompleteSecureObjectStore = new Ext.data.Store({
	        proxy: new Ext.data.ScriptTagProxy({
	        	url: '/{$config ['appName']}/auto-complete/secure-object'
	        }),
	        reader: new Ext.data.JsonReader({
	            root: 'results',
	            totalProperty: 'total',
	            id: 'id'
	        }, [
	            {name: 'name'},
	            {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'},
                {name: 'allow'}    
	        ])
	    });	
        
        //autocompleteReceiverInDeptStore.load();
		//autocompleteSecureObjectStore.load();
		//autocompleteSecureGroupStore.load();
		
        {$this->getAssignOrderJS()}
        {$this->getRecordDefinitionJS()}
        {$this->getChangeReceiverJS()}
        {$this->getReportOrderJS()}
		";
		
		$secretLevelStore = $store->getDataStore ( 'secretLevel' );
		$sendExternalCategory = $store->getDataStore ( 'sendExternalCategory' );
		$speedLevelStore = $store->getDataStore ( 'speedLevel' );
		$accountTypeStore = $store->getDataStore ( 'accountType', 'accountEditorStore' );
		$rankEditorStore = $store->getDataStore ( 'rank', 'rankEditorStore' );
		$structureTypeStore = $store->getDataStore ( 'structureType', 'structureTypeEditorStore' );
		$dataTypeStore = $store->getDataStore ( 'dataType', 'dataTypeEditorStore' );
		
		//get Administrator Action List
		if ($sessionMgr->getCurrentAccountType () >= 2) {
			$mainJS .= $this->getAdminActionList ( 'js' );
			$systemMenu = ",{
						text: '" . $lang ['action'] ['system'] . "',
						menu: [" . $this->getAdminActionList ( 'list' ) . "],
						iconCls: 'systemIcon'
			},";
		} else {
			$systemMenu = ",";
		}
		
		//get Tools Action List
		$mainJS .= $this->getToolsActionList ( 'js' );
		
		//get Work Action List
		$mainJS .= $this->getWorksActionList ( 'js' );
		
		//get Modules Action List
		$mainJS .= $this->getModulesActionList ( 'js' );
		
		//get Reports Action List
		$mainJS .= $this->getReportActionList ( 'js' );
		
		//get Other Action List
		$mainJS .= $this->getOtherActionList ( 'js' );
		
		//get All Available Portlet
		//$mainJS .= $this->getPortletJS ();
		

		// Administrator Tabs
		$mainJS .= "Ext.form.LocalComboBox = Ext.extend(Ext.form.ComboBox, {
		    mode:'local',
		    typeAhead:false,
		    triggerAction:'all',
		    selectOnFocus: true,
		    forceSelection: false,
		    setValue: function(v){
		        this.store.clearFilter();
		        //alert('select '+v);
		        Ext.form.LocalComboBox.superclass.setValue.call(this, v);
		    }
		});
		$secretLevelStore
		$speedLevelStore
		$rankEditorStore
		$accountTypeStore
		$structureTypeStore
		$dataTypeStore
		$sendExternalCategory
        
        //Grid Filter Icon
        //Ext.ux.grid.filter.StringFilter.prototype.icon = 'images/find.png';   
        
        //Dummy Toolbar for fakeing to fix IE7 Bug
        var dummyTB = new Ext.PagingToolbar({
            pageSize: 25,
            store: accountEditorStore,
            displayInfo: true
        });
		
		var secretEditor = new Ext.grid.GridEditor(
			new Ext.form.LocalComboBox({
        		id: 'secretCombo',
				store: secretLevelStore,
				displayField: 'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true
			})
		);
		
		function secretRenderer(secretLVL) {
			switch(secretLVL) {
				case 0:
					return '{$lang['common']['secret'][0]}';
					break;
				case 1:
					return '{$lang['common']['secret'][1]}';
					break;
				case 2:
					return '{$lang['common']['secret'][2]}';
					break;
				case 3:
					return '{$lang['common']['secret'][3]}';
					break;
			}
		}
		var speedEditor = new Ext.grid.GridEditor(
			new Ext.form.LocalComboBox({
        		id: 'speedCombo',
				store: speedLevelStore,
				displayField: 'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true
			})
		);
		
		var rankEditorCombo = new Ext.grid.GridEditor(
			new Ext.form.ComboBox({
        		id: 'rankEditorCombo',
				store: rankEditorStore,
				displayField: 'name',
				valueField: 'id',
				typeAhead: false,
				//mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true
			})
		);
		
		var accountTypeEditor = new Ext.grid.GridEditor(
			new Ext.form.LocalComboBox({
        		id: 'accountTypeCombo',
				store: accountEditorStore,
				displayField: 'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true
			})
		);
        
		var textFieldEditor = new Ext.grid.GridEditor(
            new Ext.form.TextField({
                id: 'textFieldEditor',               
                displayField: 'name',
                valueField: 'value'
            })
        );
		
        var structureTypeEditor = new Ext.grid.GridEditor(
			new Ext.form.LocalComboBox({
        		id: 'structureTypeCombo',
				store: structureTypeEditorStore,
				displayField: 'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true
			})
		);
		
		var dataTypeEditor = new Ext.grid.GridEditor(
			new Ext.form.LocalComboBox({
        		id: 'dataTypeCombo',
				store: dataTypeEditorStore,
				displayField: 'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true
			})
		);
		
		
		
		var sendExtTypeCombo = new Ext.form.LocalComboBox({
        	id: 'sendExtTypeCombo',
        	fieldLabel: '{$lang['workitem']['sendDocumentToExternal2']}',
			store: sendExternalCategoryStore,
			displayField: 'name',
			valueField: 'value',
			typeAhead: false,
			value: 0,
			mode: 'local',
			triggerAction: 'all',
			selectOnFocus: true
		});
		
		var timeFieldEditor = new Ext.grid.GridEditor(
			new Ext.form.TimeField({
				fieldLabel: 'Time',
				emptyText: 'Default',
				name: 'timeFieldEditor',
				format: 'H:i',
				width: 100
			})
		);
		
		var dateFieldEditor = new Ext.grid.GridEditor(
			new Ext.ux.DateTimeField ({
				fieldLabel: 'Date',    
				id: 'dateFieldEditor',
				name: 'dateFieldEditor',
				emptyText: 'Default',
				width: 100
			})
		);
		
		{$this->getRendererFunction()}
							
		var rendererFunc = function(value) {
			alert(value);
			return secretLevelStore.getById(value).get('name');
		}
										
		function renderCell(value, metadata, record, rowIndex, colIndex, store) {
			if (record.get('name') == 'Saraban Secret Level' || record.get('name') == 'Workflow Secret Level') {
				return secretLevelStore.getAt(record.get('value')).get('name');
			}
			
			if(record.get('name') == 'Account Type') {
				if(!accountEditorStore.getAt(record.get('value'))) {
					return 'Undefined';
				}else {
					return accountEditorStore.getAt(record.get('value')).get('name');
				}
			}
			
			//dateFieldEditor
			if(record.get('name') == 'Structure Type') {
			
				if(!structureTypeEditorStore.getAt(record.get('value'))) {
					return 'Undefined';
				}else {
					return structureTypeEditorStore.getAt(record.get('value')).get('name');
				}
			}
			
			if(record.get('name') == 'Data Type') {
			
				if(!dataTypeEditorStore.getAt(record.get('value'))) {
					return 'Undefined';
				}else {
					return dataTypeEditorStore.getAt(record.get('value')).get('name');
				}
			}
			
			if(record.get('name') == 'Rank') {
				if(!rankEditorStore.getById(record.get('value'))) {
					return 'Undefined';
				}else {
					return rankEditorStore.getById(record.get('value')).get('name');
				}
			}
			
			return Ext.grid.PropertyColumnModel.prototype.renderCell.apply(this, arguments);
		}
		
		var tpAdmin = new Ext.TabPanel({
			id: 'tpAdmin',
			//baseCls: 'x-tab-panel',
			baseCls: 'x-plain',
			frame: false,
			border: true,
			plain: true,
			bbar: [
					HomeAction
					{$systemMenu}
					{
						text: '" . $lang ['action'] ['tool'] . "',
						menu: [" . $this->getToolsActionList ( 'list' ) . "],
						disabled: {$disableToolsMainMenu},
						hidden: {$hiddenSB},
						iconCls: 'toolsIcon'
					},
					{
						text: '" . $lang ['action'] ['registerBook'] . "',
						menu: [" . $this->getWorksActionList ( 'list' ) . "],
						disabled: {$disabledRegisterMainMenu},
						hidden: {$hiddenSB},
						iconCls: 'workflowIcon'
					},
					" . $this->getModulesActionList ( 'list' ) . ",
		            {
		                text: '" . $lang ['action'] ['report'] . "',
		                menu: [" . $this->getReportActionList ( 'list' ) . "],
						hidden: {$hiddenSB},
		                iconCls: 'reportIcon',
		                disabled: {$disableReport}
		            },{
		            	text: '" . $lang ['action'] ['other'] . "',
		                menu: [" . $this->getOtherActionList ( 'list' ) . "],
		                iconCls: 'addonIcon'
		            }
		        ],
			region: 'center',
			deferredRender: false,
			enableTabScroll: true,
			activeTab: 0,
			items:[{
				//xtype:'portal',
				id: 'tpAdminHome',
				//contentEl: 'adminHome',
				title: '" . $lang ['HomeTab'] . "',
				autoScroll:true,
				layout: 'column',
				iconCls: 'homeIcon',
				baseCls: 'x-plain',
				stateful: false,
				autoLoad: {url: '{$portalURL}', scripts: true}
			}]
		});";
		
		$mainJS .= "
			
			
			
			
		
		Ext.onReady(function(){
			{$this->getDegradeModeJS()}
		
			Ext.useShims = true;
			Ext.state.Manager.setProvider(new Ext.ux.state.PersistStateProvider());			
			//Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
			
			var viewport = new Ext.Viewport({
				layout:'border',
				items:[
				new Ext.BoxComponent({
					region:'north',
					el: 'north',
					height:45
				}),
				tpAdmin,{ 
					region:'east',
                    id:'ECMProperty',
                    title: 'Property Editor',
                    split: true,
                    width: 275,
                    //autoWidth: true,
                    loadMask: true,
                    minSize: 275,
                    maxSize: 400,
                    collapsible: true,
                    collapseMode: 'mini',
                    margins:'0 0 0 5',
                    layout:'accordion',
                    layoutConfig:{
                        animate:true
                    },
                    
                    items: [ new Ext.grid.PropertyGrid({
                    	id: 'ecmPropertyGrid', 
	                	title: 'No Property',
	                	width: 275,
	                	 loadMask: true,
	                	 //view: new Ext.grid.GroupingView({forceFit:true}),
	                	customEditors: {
        					'Workflow Secret Level': secretEditor,
        					'Saraban Secret Level': secretEditor,
        					'Rank': rankEditorCombo,
        					'Account Type' : accountTypeEditor,
        					'Expire Date' : dateFieldEditor,
        					'Access From' : timeFieldEditor,
        					'Access To' : timeFieldEditor,
        					'Force change timestamp' : dateFieldEditor,
        					'Structure Type' : structureTypeEditor,
        					'Data Type' : dataTypeEditor,
                            'DMS Quota' : textFieldEditor
    					},
    					source: {\"(name)\": \"No Activity\"},
    					columns: [
						{header: 'Property', width: 150, sortable: false},
						{header: 'Value', width: 100, sortable: false}
						],
    					cm: new Ext.grid.ColumnModel([
							{
								header: 'Property',
								width: '150',
								fixed: true
							},{
								header: 'Value',
								width: '100'
							}
						])
                	}) ]
                }/*,{ 
					region:'west',
					id: 'ECMExplorer',
                    title: '" . $lang ['ECMExplorer'] . "',
                    collapsible: true,
                    split: true,
                    //height: 100,
                    width: 265,
                    minSize: 265,
                    maxSize: 400,
                    layout:'fit',
                    margins:'0 5 0 0',
                    items: [new Ext.TabPanel({
                    		id: 'tabExplorerMain',
                            border:false,
                            rowHeight: .85,
                            activeTab:0,
                            tabPosition:'bottom',
                            items:[{
                                //html:'<p>Disabled</p>',
                                id: 'ECMWorkplaceExplorer',
                                layout:'fit',
                                //autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
                                items: new Ext.TabPanel({
		                    		id: 'tabExplorer',
		                            border:false,
		                            activeTab:0,
		                            tabPosition:'top',
		                            items:[{$explorerJS}],
		                            title: 'Workplace Explorer',
                                	autoScroll:true}),
                                title: '" . $lang ['WorkplaceExplorer'] . "',
                                autoScroll:true
                            }]
                        })]
                }*/
				]
			});		

			Ext.getCmp('ECMProperty').collapse();
			/*
			if(Cookies.get('axe') ==1 ) {
				Ext.getCmp('ECMExplorer').expand();
				if(Cookies.get('atet') ==1) {
					if(typeof Ext.getCmp('DMSexplorer') == 'object') {
						Ext.getCmp('DMSexplorer').show();
					}
				}
			} else {
				Ext.getCmp('ECMExplorer').collapse();
			}
			*/
			{$specialScript}
			
			Ext.getCmp('ecmPropertyGrid').getColumnModel().setColumnWidth(0,150);
			Ext.getCmp('ecmPropertyGrid').getColumnModel().setColumnWidth(1,100);
			
			var ecmPropGrid = Ext.getCmp('ecmPropertyGrid');
			tpAdmin.on({
				tabchange: function() {
					Cookies.set('at',tpAdmin.getActiveTab().getId());
					resetPropertyGrid();
				}
			}
			);
			
			function resetPropertyGrid() {
				var ecmPropGrid = Ext.getCmp('ecmPropertyGrid');
				var emptyProperty = Ext.data.Record.create([
					{name: 'name'},
					{name: 'value'},
					{name: 'group'},
					{name: 'editor'}
				]);
				var emptyStore = new Ext.data.GroupingStore({reader: new Ext.data.JsonReader({}, emptyProperty),groupField: 'group'});
				//var emptyStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, emptyProperty)});
				ecmPropGrid.setSource(emptyStore.data.items);
				ecmPropGrid.setTitle('No Property');
			}
			
			ecmPropGrid.colModel.renderCellDelegate = renderCell.createDelegate(ecmPropGrid.colModel);
			resetPropertyGrid();
			
		});
		
		
		var forwardDFForm = new Ext.form.FormPanel({
			id: 'forwardDFForm',
			baseCls: 'x-plain',
			labelWidth: 50,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				fieldLabel: '�觶֧',
				id: 'forwardTransTo',
				allowBlank: false,
				grow: false,
				preventScrollbars: false,
				overflow: true,
				name: 'forwardTransTo',
				inputType: 'textarea',
				width: '250',
				height: '100'
			},{
				fieldLabel: 'parentID',
				id: 'forwardTransToHidden',
				name: 'forwardTransToHidden',
				inputType: 'hidden'
			}]
		});
		
		
		var forwardDFWindow = new Ext.Window({
			id: 'forwardDFWindow',
			title: '{$lang['df']['forward']}',
			width: 350,
			height: 190,
			modal: true,
			minWidth: 350,
			minHeight: 190,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: forwardDFForm,
			closable: false,
			buttons: [{
                    id: 'btnForwardSend',
                    formBind: true,
                    text: '{$lang['workitem']['send']}',
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
                            timeout: {$config['sendTimeout']},   
                            success: function(o){
                                Ext.MessageBox.hide();
                                var r = Ext.decode(o.responseText);
                                if(r.redirectLogin == 1) {
                                    sessionExpired(); 
                                } else   {
                                    Ext.MessageBox.show({
                                          msg: '���ѧ�觵�͡�س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });
                                           
                                    tempSendStore.removeAll();
                                    tempCCStore.removeAll();
                                    
                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/forward-transaction?isCirc=0',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']},    
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              var regNo = '';
                                              var sendDate = '';
                                                  for(i=0; i < r.length ; i++) {
                                                      if(regNo == '') {
                                                          regNo = r[i].regNo;
                                                    } else {
                                                        regNo = regNo +','+r[i].regNo;
                                                    }                                
                                                }
                                                sendDate = r[0].recvDate;
                                                sendTime = r[0].recvTime; 
                                              Ext.MessageBox.show({
                                                title: '���������',
                                                //msg: '�����º��������<br/>������˹ѧ����Ţ��� '+r.docno +'<br/>' + '���ѹ��� '+sendDate+',����'+sendTime,
                                                 msg: '�����º��������<br/>' + '���ѹ��� '+sendDate+',����'+sendTime,
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('forwardDFForm').getForm().reset();
                                            forwardDFWindow.hide();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '���������',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                            forwardDFWindow.hide();
                                        },
                                           form: Ext.getCmp('forwardDFForm').getForm().getEl()
                                       }); 
                                }
                            }
                        });
						
                    }
                },{
                    id: 'btnForwardSendCirc',
                    formBind: true,
                    text: '{$lang['workitem']['sendCirc']}',
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
                            timeout: {$config['sendTimeout']},   
                            success: function(o){
                                Ext.MessageBox.hide();
                                var r = Ext.decode(o.responseText);
                                if(r.redirectLogin == 1) {
                                    sessionExpired(); 
                                } else   {
                                    Ext.MessageBox.show({
                                          msg: '���ѧ�觵�͡�س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });
                                           
                                    tempSendStore.removeAll();
                                    tempCCStore.removeAll();
                                    
                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/forward-transaction?isCirc=1',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']}, 
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              var regNo = '';
                                              var sendDate = '';
                                                  for(i=0; i < r.length ; i++) {
                                                      if(regNo == '') {
                                                          regNo = r[i].regNo;
                                                    } else {
                                                        regNo = regNo +','+r[i].regNo;
                                                    }                                
                                                }
                                                sendDate = r[0].recvDate;
                                                sendTime = r[0].recvTime; 
                                              Ext.MessageBox.show({
                                                title: '���������',
                                                msg: '�����º��������<br/>�������Ţ���'+regNo +'<br/>' + '���ѹ��� '+sendDate+',����'+sendTime,
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('forwardDFForm').getForm().reset();
                                            forwardDFWindow.hide();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '���������',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                            forwardDFWindow.hide();
                                        },
                                           form: Ext.getCmp('forwardDFForm').getForm().getEl()
                                       });  
                                }
                            }
                        });
						
                    }
                },{
					id: 'btnCloseForwardWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						Ext.getCmp('forwardDFForm').getForm().reset();
						forwardDFWindow.hide();
					}
				}]
		});
		
		var forwardExtDFForm = new Ext.form.FormPanel({
			id: 'forwardExtDFForm',
			baseCls: 'x-plain',
			labelWidth: 70,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				fieldLabel: '�觶֧',
				id: 'forwardExternalTransTo',
				allowBlank: false,
				name: 'forwardExternalTransTo',
				inputType: 'textarea',
				width: '250',
				height: '100'
			},sendExtTypeCombo,{
				fieldLabel: 'parentID',
				id: 'forwardExternalTransToHidden',
				name: 'forwardExternalTransToHidden',
				inputType: 'hidden'
			}]
		});
		
		var forwardExtDFWindow = new Ext.Window({
			id: 'forwardExtDFWindow',
			title: '{$lang['df']['forwardExt']}',
			width: 375,
			height: 225,
			minWidth: 375,
			minHeight: 225,
			modal: true,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: forwardExtDFForm,
			closable: false,
			buttons: [{
                    id: 'btnForwardExtSend',
                    formBind: true,
                    text: '{$lang['workitem']['send']}',
                    handler: function() {
						Ext.MessageBox.show({
					      	msg: '���ѧ�觵�͡�س����ѡ����...',
					       	progressText: '�ѹ�֡������...',
					      	width:300,
					       	wait:true,
					      	waitConfig: {interval:200},
					       	icon:'ext-mb-download'
					    });
					       	
					    tempSendStore.removeAll();
					    tempCCStore.removeAll();
					    
				    	Ext.Ajax.request({
				    		url: '/{$config ['appName']}/df-action/forward-transaction?isCirc=0',
				    		method: 'POST',
				    		success: function(o){
				    			  Ext.MessageBox.hide();
								  var r = Ext.decode(o.responseText);
								  var regNo = '';
								  var sendDate = '';
								  	for(i=0; i < r.length ; i++) {
								  		if(regNo == '') {
								  			regNo = r[i].regNo;
										} else {
											regNo = regNo +','+r[i].regNo;
										}								
									}
									sendDate = r[0].recvDate;
									sendTime = r[0].recvTime; 
								  Ext.MessageBox.show({
						    		title: '���������',
						    		msg: '�����º��������<br/>�������Ţ���'+regNo +'<br/>' + '���ѹ��� '+sendDate+',����'+sendTime,
						    		buttons: Ext.MessageBox.OK,
						    		icon: Ext.MessageBox.INFO
						    	});
								Ext.getCmp('forwardExtDFForm').getForm().reset();
								forwardExtDFWindow.hide();
							},
				    		failure: function(r,o) {
				    			Ext.MessageBox.hide();
								Ext.MessageBox.show({
						    		title: '���������',
						    		msg: '�������ö����',
						    		buttons: Ext.MessageBox.OK,
						    		icon: Ext.MessageBox.ERROR
						    	});
						    	forwardExtDFWindow.hide();
							},
				   			form: Ext.getCmp('forwardExtDFForm').getForm().getEl()
				   		});
                    }
                },{
                    id: 'btnForwardExtSendCirc',
                    formBind: true,
                    text: '{$lang['workitem']['sendCirc']}',
					handler: function() {
						Ext.MessageBox.show({
					      	msg: '���ѧ�觵�͡�س����ѡ����...',
					       	progressText: '�ѹ�֡������...',
					      	width:300,
					       	wait:true,
					      	waitConfig: {interval:200},
					       	icon:'ext-mb-download'
					    });
					       	
					    tempSendStore.removeAll();
					    tempCCStore.removeAll();
					    
				    	Ext.Ajax.request({
				    		url: '/{$config ['appName']}/df-action/forward-transaction?isCirc=1',
				    		method: 'POST',
				    		success: function(o){
				    			  Ext.MessageBox.hide();
								  var r = Ext.decode(o.responseText);
								  var regNo = '';
								  var sendDate = '';
								  	for(i=0; i < r.length ; i++) {
								  		if(regNo == '') {
								  			regNo = r[i].regNo;
										} else {
											regNo = regNo +','+r[i].regNo;
										}								
									}
									sendDate = r[0].recvDate;
									sendTime = r[0].recvTime; 
								  Ext.MessageBox.show({
						    		title: '���������',
						    		msg: '�����º��������<br/>�������Ţ���'+regNo +'<br/>' + '���ѹ��� '+sendDate+',����'+sendTime,
						    		buttons: Ext.MessageBox.OK,
						    		icon: Ext.MessageBox.INFO
						    	});
								Ext.getCmp('forwardExtDFForm').getForm().reset();
								forwardExtDFWindow.hide();
							},
				    		failure: function(r,o) {
				    			Ext.MessageBox.hide();
								Ext.MessageBox.show({
						    		title: '���������',
						    		msg: '�������ö����',
						    		buttons: Ext.MessageBox.OK,
						    		icon: Ext.MessageBox.ERROR
						    	});
						    	forwardExtDFWindow.hide();
							},
				   			form: Ext.getCmp('forwardExtDFForm').getForm().getEl()
				   		});
                    }
                },{
					id: 'btnCloseForwardWindow',
					text: '{$lang['common']['cancel']}',
					handler: function() {
						Ext.getCmp('forwardExtDFForm').getForm().reset();
						forwardExtDFWindow.hide();
					}
				}]
		});
		
		
		
		Ext.getCmp('forwardTransTo').on('focus',function() {
                sendInternalListWindow.show();
                Cookies.set('rc','forwardTransTo');
                Cookies.set('rcH','forwardTransToHidden');
       	},this);
       	
       	Ext.getCmp('forwardExternalTransTo').on('focus',function() {
                sendExternalListWindow.show();
                Cookies.set('rc','forwardExternalTransTo');
                Cookies.set('rcH','forwardExternalTransToHidden');
       	},this);
        
        Ext.getCmp('forwardDFWindow').on('show',function() {
            Ext.getCmp('forwardTransTo').focus();
        },this);
       	
       	{$this->getChangePasswordJS()}
		{$this->getChangeRoleJS()}
		{$this->getChangeYearJS()}
		{$this->getFilterReceiveRecordJS()}
		{$this->getFilterSendRecordJS()}
		{$this->getAddCommandJS()}
		
		{$this->getUtilityJS()}
		
		//Global Shortcut keys
		var globalKeymap = new Ext.KeyMap(Ext.getBody(), [{
		    key: 'q',
		    alt: true,
		    fn: function () {
		    	logout();
			},
		    scope: this
		},{
		    key: Ext.EventObject.HOME,
		    ctrl: true,
		    fn: function () {
		    	tabMain = Ext.getCmp('tpAdmin').findById('tpAdminHome').show();
			},
		    scope: this
		}]);
		
		globalKeymap.enable();
		
		
		
	    function showMessage(type, title, msg) {

			switch (type) {
				case 'info':
					Ext.MessageBox.show({
			    		title: title,
			    		msg: msg,
			    		buttons: Ext.MessageBox.OK,
			    		icon: Ext.MessageBox.INFO
			    	});
					break;
				case 'warning':
					Ext.MessageBox.show({
			    		title: title,
			    		msg: msg,
			    		//buttons: Ext.MessageBox.OK,
			    		icon: Ext.MessageBox.WARNING
			    	});
					break;
				case 'error':
					Ext.MessageBox.show({
			    		title: title,
			    		msg: msg,
			    		buttons: Ext.MessageBox.OK,
			    		icon: Ext.MessageBox.ERROR
			    	});
					break;
			}
		}
			
		function messageSuccess(title, msg) {
			var title = (title == null) ? '{$lang ['common'] ['save']}' : title;
			var msg = (msg == null) ? '{$lang ['common'] ['success']}' : msg;
			
			showMessage('info', title, msg);
		}
		
		function messageFailed(title, msg) {
			var title = (title == null) ? '{$lang ['common'] ['save']}' : title;
			var msg = (msg == null) ? '{$lang ['common'] ['success']}' : msg;
			
			showMessage('error', title, msg);
		}	
	    
	    {$this->getAcceptFormJS()}
	    
	    rankEditorStore.load();
		";
		//include_once 'JSMin.php';
		/**************************/
		if ($config ['zendCacheOnMemcache']) {
			if ($config ['memcacheInterface']) {
				$backendName = "Memcached";
				$backendOptions = array ('servers' => array (array ('host' => 'localhost', 'port' => 11211, 'persistent' => true ) ) );
			} else {
				$backendName = "File";
				$backendOptions = array ('cache_dir' => $config ['zendCachePath'] );
			}
		} else {
			$backendName = "File";
			$backendOptions = array ('cache_dir' => $config ['zendCachePath'] );
		}
		/**************************/
		$consoleCacheFrontendOptions = array ('lifetime' => 600, 'automatic_serialization' => true );
		$consoleCache = Zend_Cache::factory ( 'Core', $backendName, $consoleCacheFrontendOptions, $backendOptions );
		$cacheID = "consoleJS_" . $sessionMgr->getCurrentAccID () . '_' . $sessionMgr->getCurrentRoleID ();
		if (! ($mainJSCache = $consoleCache->load ( $cacheID ))) {
			//$mainJS = $this->ECMView->render ( $templateName );
			$mainJSCache = $mainJS;
			if (! $config ['disableZendCache']) {
				
				$consoleCache->save ( $mainJS, $cacheID );
			}
		}
		// HTTP/1.1
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( 'Cache-Control: max-age=28800' );
		// Date in the past 
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		echo JSMin::minify ( $mainJSCache );
		//echo $mainJS;
	}
	
	private function getDegradeModeJS() {
		global $lang;
		$js = "";
		if ($_SESSION ['degradeMode']) {
			$js = "Ext.MessageBox.show({
	    		title: 'Notification',
	    		msg: '{$lang['degradeMode']}',
	    		buttons: Ext.MessageBox.OK,
	    		closable: false,
	    		icon: Ext.MessageBox.INFO
	    	});";
		
		}
		return $js;
	}
	/**
	 * ���ҧ�����ŧ�Ѻ
	 *
	 * @return string
	 */
	private function getAcceptFormJS() {
		global $config;
		global $lang;
		
		$js = "/*var comboAcceptAsType = new Ext.form.ComboBox({
            name: 'acceptingAsType',
            fieldLabel: '�������͡���',
            store: rankComboStore,
            displayField:'name',
            valueField: 'id',
            hiddenName: 'acceptingAsType',
            typeAhead: false,
            triggerAction: 'all',
            value: 3,
            selectOnFocus:true,
            width: 100
        });*/
        
        var comboAcceptDoctype = new Ext.form.ComboBox({
            name: 'acceptingDoctype',
            fieldLabel: '�������͡���',
            store: acceptDocTypeStore,
            displayField:'name',
            valueField: 'id',
            hiddenName: 'acceptingDoctype',
            typeAhead: false,
            triggerAction: 'all',
            value: 1,
            selectOnFocus:true,
            width: 100
        });
        
        var acceptOption1 = new Ext.form.Checkbox({
            // hideLabel: true,
            fieldLabel:'������ŧ�Ѻ',
            boxLabel:'ŧ�Ѻ����',
            name:'acceptAsType',
            id:'acceptAsTypeInternal',
            checked: true,
            inputValue: 1,
            onClick: function(cb,value) {
            	Ext.getCmp('acceptAsTypeInternal').setValue(true);
                
            	if(Ext.getCmp('acceptAsTypeExternal').getValue()) {
                    Ext.getCmp('acceptAsTypeExternal').setValue(false);
                }
                
				if(Ext.getCmp('acceptAsTypeCirc').getValue()) {
                    Ext.getCmp('acceptAsTypeCirc').setValue(false);
                }
            }
        });
        
        var acceptOption2 = new Ext.form.Checkbox({
            //hideLabel: true,
            fieldLabel:'������ŧ�Ѻ',
            boxLabel:'ŧ�Ѻ��¹͡',
            name:'acceptAsType',
            id:'acceptAsTypeExternal',
            inputValue: 2,
            onClick: function(cb,value) {
            	Ext.getCmp('acceptAsTypeExternal').setValue(true);
                if(Ext.getCmp('acceptAsTypeExternal').getValue()) {
                    Ext.getCmp('acceptAsTypeInternal').setValue(false);
                }
                
				if(Ext.getCmp('acceptAsTypeCirc').getValue()) {
                    Ext.getCmp('acceptAsTypeCirc').setValue(false);
                }
            } 
        });
        
        var acceptOption3 = new Ext.form.Checkbox({
            //hideLabel: true,
            fieldLabel:'������ŧ�Ѻ',
            boxLabel:'ŧ�Ѻ���¹',
            name:'acceptAsType',
            id:'acceptAsTypeCirc',
            inputValue: 5,
            onClick: function(cb,value) {
            	Ext.getCmp('acceptAsTypeCirc').setValue(true);
                if(Ext.getCmp('acceptAsTypeInternal').getValue()) {
                    Ext.getCmp('acceptAsTypeInternal').setValue(false);
                }
				if(Ext.getCmp('acceptAsTypeExternal').getValue()) {
                    Ext.getCmp('acceptAsTypeExternal').setValue(false);
                }
            } 
        });

            
        
        var acceptUnreceiveForm = new Ext.form.FormPanel({
            id: 'acceptUnreceiveForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            defaultType: 'textfield',

            items: [acceptOption1,acceptOption2,acceptOption3,comboAcceptDoctype,
            {
            	xtype:'fieldset',
		        id: 'forwardAfterAccept',
		        title: '�觵��',
		        autoHeight:true,
		        collapsible: true,
		        collapsed: true,
		        layout: 'column',
		        items: [	{
                   	id: 'acceptForwardTo',
                   	xtype:'textfield',
					fieldLabel: '�觵��',                            
					allowBlank: true, 
					style: popupFieldStyle,
					name: 'acceptForwardTo',
					width: 200,
					height: 50,
					listeners: {
                    	'focus' : {
                    		fn: function() {
                    			sendInternalListWindow.show();
				                Cookies.set('rc','acceptForwardTo');
				                Cookies.set('rcH','acceptForwardToHidden');
							}
						}
					}
				},{
					id: 'acceptForwardToHidden',
					xtype:'hidden',                             
					name: 'acceptForwardToHidden'
				}],
				listeners: {
                    'beforeexpand' : {
                    	fn: function() {
                    		Ext.getCmp('acceptUnreceiveWindow').setHeight(275);
							Ext.getCmp('acceptUnreceiveWindow').center();
						}
					},
					'beforecollapse' : {
                    	fn: function() {
                    		Ext.getCmp('acceptUnreceiveWindow').setHeight(195);
							Ext.getCmp('acceptUnreceiveWindow').center();
						}
					}
				}
		}],
            buttons: [{
                id: 'btnConfirmAccept',
                text: '{$lang['df']['accept']}',
                handler: function() {
                    acceptUnreceiveWindow.hide();
                    Ext.MessageBox.show({
                        id: 'dlgAcceptDocumentData',
                        msg: '���ѧ�ѹ�֡ŧ�Ѻ...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        con:'ext-mb-download'
                    });
                    Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/accept-document',
                            method: 'POST',
                            //success: acceptDocumentSuccess,
                            success: function(o){
                                  Ext.MessageBox.hide();
                                  var r = Ext.decode(o.responseText);
                           		if(r.success ==1) {
	                                  	if(r.recvType == 1) {
	                                  		var msgReceive = '{$lang['df']['recvType1']}';
									  	}
										if(r.recvType == 2) {
	                                  		var msgReceive = '{$lang['df']['recvType2']}';
									  	}
										if(r.recvType == 3) {
	                                  		var msgReceive = '{$lang['df']['recvType3']}';
									  	}
										if(r.recvType == 4) {
	                                  		var msgReceive = '{$lang['df']['recvType4']}';
									  	}
										if(r.recvType == 5) {
	                                  		var msgReceive = '{$lang['df']['recvType5']}';
									  	}
										if(r.recvType == 6) {
	                                  		var msgReceive = '{$lang['df']['recvType6']}';
									  	}
										if(r.recvType == 7) {
	                                  		var msgReceive = '{$lang['df']['recvType7']}';
									  	}
									  	if(r.regNo != '') {
									  		var msgRegNo = ' <br/>�Ţ�Ѻ��� :'+r.regNo;
										} else {
											var msgRegNo = '';
										}
									if(r.multi==1){	
	                                  Ext.MessageBox.show({
	                                    title: '���ŧ�Ѻ������',
	                                    msg: 'ŧ�Ѻ���º��������<br/>������ : '+msgReceive+' <br/>ŧ�ѺẺ�������� <br/>' + '�Ѻ�ѹ��� '+r.recvDate+',����'+r.recvTime,
	                                    buttons: Ext.MessageBox.OK,
	                                    icon: Ext.MessageBox.INFO
	                                  });
									}else{
	                                  Ext.MessageBox.show({
	                                    title: '���ŧ�Ѻ������',
	                                    msg: 'ŧ�Ѻ���º��������<br/>������ : '+msgReceive+' '+msgRegNo +'<br/>' + '�Ѻ�ѹ��� '+r.recvDate+',����'+r.recvTime,
	                                    buttons: Ext.MessageBox.OK,
	                                    icon: Ext.MessageBox.INFO
	                                  });
									}
	                                  unreceivedStore.reload();
	                                  clearInternalSendToSelections();
								} else {
									var msgReceive = r.message;
									Ext.MessageBox.show({
	                                    title: '���ŧ�Ѻ������',
	                                    msg: '�������öŧ�Ѻ��<br/>'+msgReceive,
	                                    buttons: Ext.MessageBox.OK,
	                                    icon: Ext.MessageBox.INFO
	                                  });
	                                  unreceivedStore.reload();
								}
                            },
                            failure: acceptDocumentFailed,
                            form: acceptUnreceiveForm.getForm().getEl()
                    });
                }
            },{
                id: 'btnCancelAccept',
                text: '{$lang['common']['cancel']}',
                handler: function() {
                    acceptUnreceiveForm.getForm().reset();
                    acceptUnreceiveWindow.hide();
                }
            }]
        });
        
        var acceptUnreceiveWindow = new Ext.Window({
            id: 'acceptUnreceiveWindow',
            title: '{$lang['df']['acceptOperation']}',
            width: 245,
            height: 195,
            modal: true,
            minWidth: 245,
            minHeight: 195,
            layout: 'fit',
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: true,
            items: acceptUnreceiveForm,
            closable: false
        });
        
        function sendbackSelectedDocument(btn) {
            if(btn == 'yes') {
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/sendback-document',
                        method: 'POST',
                        success: function(o){
                                  Ext.MessageBox.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: '����觡�Ѻ�͡���',
                                   	//msg: '�觡�Ѻ���º��������<br/>ŧ�Ѻ�����Ţ��� '+r.regNo +'<br/>' + '�Ѻ�ѹ��� '+r.recvDate+',����'+r.recvTime,
                                    msg: '�觡�Ѻ���º��������',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  unreceivedStore.reload();
                            },
                        //failure: deletePolicyFailed,
                        params: { id: gridUnreceivedItem.getSelectionModel().getSelected().get('sendID') }
                });                
            }
        }
        
        function acceptDocumentSuccess() {
            Ext.MessageBox.hide();
            
            //accountStore.load({params:{start:0, limit:25}});
            unreceivedStore.reload();
            
            Ext.MessageBox.show({
                title: '{$lang['df']['acceptOperation']}',
                msg: 'Account Added!',
                buttons: Ext.MessageBox.OK,
                //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                icon: Ext.MessageBox.INFO
            });
        }
        
        function acceptDocumentFailed() {
            Ext.MessageBox.hide();
            
            Ext.MessageBox.show({
                title: '{$lang['df']['acceptOperation']}',
                msg: 'Failed to add Account!',
                buttons: Ext.MessageBox.OK,
                //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                icon: Ext.MessageBox.ERROR
            });
        }";
		
		return $js;
	}
	
	/**
	 * ���ҧ Render function ����Ѻ Render �Ţͧ Grid
	 *
	 * @return string
	 */
	private function getRendererFunction() {
		global $lang;
		global $config;
		if ($_COOKIE ['ECMIcon'] != 0) {
			$jsSpeedPic = "<img src=\"/{$config['appName']}/images/speed/{$_COOKIE['ECMIcon']}/{1}.gif\" />";
			$jsSecretPic = "<img src=\"/{$config['appName']}/images/secret/{$_COOKIE['ECMIcon']}/{1}.gif\"  />";
		} else {
			$jsSpeedPic = "{0}";
			$jsSecretPic = "{0}";
		}
		$js = "
		function renderStatus(value, p, record){
	    	switch(record.data.status) {
				case 1: return '{$lang['common']['enable']}';break;
				case 0: return '{$lang['common']['disable']}';break;
				case null:
				default: return 'Unknown';break;
			}
			/*
			if(record.data.status == 1 ){
				return '{$lang['common']['enable']}1';
			} else {
				return '{$lang['common']['disable']}2';
			}
			*/
	    }
	    
		function renderLockingStatus(value, p, record){
	    	switch(record.data.status) {
				case 1: return 'Unlocked';break;
				case 0: return 'Locked';break;
				case null:
				default: return 'Unknown';break;
			}
	    }
	    
		function renderSecret(value,p,record) {
			
	        switch(record.data.secret) {
                case '0' :
	            case 0 :
	                return String.format('$jsSecretPic','{$lang['common']['secret'][0]}',record.data.secret);
	                break;
	            case '1' :  
                case 1 :                
	                return String.format('$jsSecretPic','{$lang['common']['secret'][1]}',record.data.secret);
	                break;
                case '2' :
	            case 2 :
	                return String.format('$jsSecretPic','{$lang['common']['secret'][2]}',record.data.secret);
	                break;
                case '3' :
	            case 3 :
	                return String.format('$jsSecretPic','{$lang['common']['secret'][3]}',record.data.secret);
	                break;
	        }
	    }
	       
	    function renderSpeed(value,p,record) {
	        switch(record.data.speed) {
                case '0' :
	            case 0 :
	                return String.format('$jsSpeedPic','{$lang['common']['speed'][0]}',record.data.speed);
	                break;
                case '1' :
	            case 1 :
	                return String.format('$jsSpeedPic','{$lang['common']['speed'][1]}',record.data.speed);
	                break;
                case '2' :
	            case 2 :
	                return String.format('$jsSpeedPic','{$lang['common']['speed'][2]}',record.data.speed);
	                break;
                case '3' :
	            case 3 :
	                return String.format('$jsSpeedPic','{$lang['common']['speed'][3]}',record.data.speed);
	                break;
	        }
	    }
	    
		function renderRecvType(value, p, record){
			if(record.data.recvType == 1 ){
				return '{$lang['df']['recvType1']}';
			}
			if(record.data.recvType == 2 ){
				return '{$lang['df']['recvType2']}';
			}
			if(record.data.recvType == 3 ){
				return '{$lang['df']['recvType3']}';
			}
			if(record.data.recvType == 4 ){
				return '{$lang['df']['recvType4']}';
			}
			if(record.data.recvType == 5){
				return '{$lang['df']['recvType5']}';
			} 
			if(record.data.recvType ==6){
				return '{$lang['df']['recvType6']}';
			} 
			if(record.data.recvType == 7){
				return '{$lang['df']['recvType7']}';
			} 
		}
		
		function renderSendType(value, p, record){
			if(record.data.sendType == 1 ){
				return '{$lang['df']['sendType1']}';
			}
			if(record.data.sendType == 2 ){
				return '{$lang['df']['sendType2']}';
			}
			if(record.data.sendType == 3 ){
				return '{$lang['df']['sendType3']}';
			}
			if(record.data.sendType == 4 ){
				return '{$lang['df']['sendType4']}';
			}
			if(record.data.sendType == 5){
				return '{$lang['df']['sendType5']}';
			} 
			if(record.data.sendType == 6){
				return '{$lang['df']['sendType6']}';
			} 
            if(record.data.sendType == 7){
                return '{$lang['df']['sendType7']}';
            } 
			if(record.data.sendType == 10){
                return '{$lang['df']['sendType10']}';
            } 
			if(record.data.sendType == 9){
                return '{$lang['df']['sendType9']}';
            } 
			if(record.data.sendType == 999){
                return 'TH e-GIF';
            }
			if(record.data.sendType == 6.1){
                return '�觵��(�Ѻ����)';
            }
			if(record.data.sendType == 6.2){
                return '�觵��(�Ѻ��¹͡)';
            }
		}
		
		function renderDFBookno(value, p, record){
			var imagePrefix='';
			if(record.data.genIntBookno == 0 ){
				imagePrefix ='<img src=\"/{$config['appName']}/images/icons/star.gif\" />';
			} else {
				imagePrefix ='';
			}
            
            if(record.data.abort == 1) {
                var effectBegin = '<b><font color=\"Red\"><strike>';
                var effectEnd = '<strike></font></b>';
            } else {
                var effectBegin = '';
                var effectEnd = '';
            }
            
			if(record.data.genExtBookno == 0 ){
				imagePrefix = imagePrefix + '<img src=\"/{$config['appName']}/images/icons/star2.gif\" />';
			} else {
				imagePrefix = imagePrefix + '';
			}
			
			if(record.data.hasReserved == 1) {
				imagePrefix = imagePrefix + '<img src=\"/{$config['appName']}/images/icons/red_star.png\" />';
			} else {
				imagePrefix = imagePrefix + '';
			}
            
            if(record.data.requestOrder == 1 ){
                imagePrefix =imagePrefix +'<img src=\"/{$config['appName']}/images/icons/version_modified.jpg\" />';
            } else {
                imagePrefix =imagePrefix +'';
            }
			return imagePrefix + effectBegin + record.data.docNo + effectEnd;
		}
	    
		function renderDFTitle(value, p, record){
			if(record.data.isCirc == 1 ){ 
				var circPic = '<img src=\"/{$config['appName']}/images/circ.gif\" />';
			} else {
				var circPic = '';
			}
			var effectBegin = '';
			var effectEnd = '';
				
			if(record.data.hold == 1) {
				var holdPic = '<img src=\"/{$config['appName']}/images/icons/forbid.gif\" />';
				var effectBegin = '<b><font color=\"Red\">';
				var effectEnd = '</font></b>';
			} else {
				var holdPic = '';
				var effectBegin = '';
				var effectEnd = '';
			}
            
            if(record.data.abort == 1) {
                var effectBegin = '<b><font color=\"Red\"><strike>';
                var effectEnd = '<strike></font></b>';
            } else {
                var holdPic = '';
                var effectBegin = '';
                var effectEnd = '';
            }
			
			if(record.data.hasAttach == 1 ){
				return holdPic+circPic+'<img src=\"/{$config['appName']}/images/attachment.gif\" />&nbsp;'+effectBegin+record.data.title+effectEnd;
			} else {
				return holdPic+circPic+effectBegin+record.data.title+effectEnd;
			}
		}
        
        function renderDFCircTitle(value, p, record){
        
            if(record.data.readstamp == 0 ){ 
                var readCircPic = '<img src=\"/{$config['appName']}/images/icons/new2.gif\" />';                
            } else {
                var readCircPic = '';                                                           
            }
        
            if(record.data.isCirc == 1 ){ 
                var circPic = '<img src=\"/{$config['appName']}/images/circ.gif\" />';
            } else {
                var circPic = '';
            }
            var effectBegin = '';
            var effectEnd = '';
                
            if(record.data.hold == 1) {
                var holdPic = '<img src=\"/{$config['appName']}/images/icons/forbid.gif\" />';
                var effectBegin = '<b><font color=\"Red\">';
                var effectEnd = '</font></b>';
            } else {
                var holdPic = '';
                var effectBegin = '';
                var effectEnd = '';
            }
            
            if(record.data.hasAttach == 1 ){
                return readCircPic+holdPic+circPic+'<img src=\"/{$config['appName']}/images/attachment.gif\" />&nbsp;'+effectBegin+record.data.title+effectEnd;
            } else {
                return readCircPic+holdPic+circPic+effectBegin+record.data.title+effectEnd;
            }
        }
		
		function renderReceived(value, p, record){
			if(record.data.received == 1 ){
				return '{$lang ['common'] ['received']}';
			} else {
				if(record.data.received == 2 ){
					return '����ʸ����Ѻ';
				} else {
					return '{$lang ['common'] ['unreceived']}';
				}				
			}
		}
	    ";
		return $js;
	}

}
