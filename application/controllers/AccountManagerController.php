<?php

/**
 * โปรแกรมจัดการบัญชีรายชื่อ
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0
 * @package controller
 * @category Control Center
 * 
 *
 */

class AccountManagerController extends ECMController {
	/**
	 * redirect ไปที่ /get-ui/ action
	 *
	 */
	public function indexAction() {
		$this->_redirector->gotoUrl ( '/account-manager/get-ui' );
	}
	
	/**
	 * method สำหรับสร้างฟอร์มเพิ่มบัญชีรายชื่อ
	 * 
	 * @return string
	 */
	public function getAddAccountForm() {
		global $config;
		global $lang;
		
		$js = "var accountAddForm = new Ext.form.FormPanel({
			id: 'accountAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid: true,

			items: [{
				fieldLabel: '{$lang['acc']['login']}',
				allowBlank: false,
				name: 'accLoginName',
				width: 250
			},{
				id: 'accPassword',
				fieldLabel: '{$lang['acc']['password']}',
				allowBlank: false,
				name: 'accPassword',
				inputType: 'password',
				width: 250
			},{
				id: 'accHiddenRank',
				name: 'accHiddenRank',
				inputType: 'hidden'
			},{
				id: 'accHiddenType',
				name: 'accHiddenType',
				inputType: 'hidden'
			},{
				id: 'accRetypePassword',
				fieldLabel: '{$lang['acc']['retypePassword']}',
				allowBlank: false,
				name: 'accRetypePassword',
				inputType: 'password',
				width: 250
			},{
				fieldLabel: '{$lang['acc']['firstname']}',
				allowBlank: false,
				name: 'accFirstName',
				width: 250
			},{
				fieldLabel: '{$lang['acc']['midname']}',
				name: 'accMiddleName',
				width: 250
			},{
				fieldLabel: '{$lang['acc']['lastname']}',
				allowBlank: false,
				name: 'accLastName',
				width: 250
			},new Ext.form.ComboBox({
				id: 'accRank',
				name: 'accRank',
				allowBlank: false,
				fieldLabel: '{$lang['rank']['default']}',
				store: rankComboStore,
				displayField:'name',
				valueField: 'id',
				typeAhead: false,
				triggerAction: 'all',
				emptyText:'Select Rank',
				selectOnFocus:true
			}),new Ext.form.ComboBox({
				id: 'accType',
				name: 'accType',
				allowBlank: false,
				fieldLabel: '{$lang['acc']['type']}',
				store: accountTypeStore,
				displayField:'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				emptyText:'Select Account Type',
				selectOnFocus:true
			}),{
				fieldLabel: '{$lang['acc']['email']}',
				name: 'accEmail',
				width: 250
			},{
				fieldLabel: '{$lang['acc']['tel']}',
				name: 'accTelephone',
				width: 250
			},{
				fieldLabel: '{$lang['acc']['fax']}',
				name: 'accFax',
				width: 250
			},{
				fieldLabel: '{$lang['acc']['mobile']}',
				name: 'accMobile',
				width: 250
			}],
			buttons: [{
				id: 'btnSaveAccount',
				text: '{$lang['common']['save']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
					
	    			accountAddWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: '{$lang['common']['saving']}',
			           	progressText: '{$lang['common']['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	
			       	if(Ext.getCmp('accPassword').getValue() == Ext.getCmp('accRetypePassword').getValue() ) {
			       		accountAddForm.getForm().setValues([
		            		{id:'accPassword',value: hex_md5(Ext.getCmp('accPassword').getValue())},
							{id:'accRetypePassword',value: hex_md5(Ext.getCmp('accRetypePassword').getValue())},
							{id:'accHiddenRank',value: Ext.getCmp('accRank').getValue()},
							{id:'accHiddenType',value: Ext.getCmp('accType').getValue()}
		            	]);
		    			Ext.Ajax.request({
		    				url: '/{$config ['appName']}/account-manager/add-account',
		    				method: 'POST',
		    				success: accountAddSuccess,
		    				failure: accountAddFailed,
		    				form: Ext.getCmp('accountAddForm').getForm().getEl()
		    			});
	    			}
	    		}
			},{
				id: 'btnCancelSaveAccount',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					accountAddWindow.hide();
				}
			}]
		});";
		
		$js .= "var accountAddWindow = new Ext.Window({
			id: 'accountAddWindow',
			title: '{$lang['acc']['add']}',
			width: 400,
			height: 390,
			modal: true,
			minWidth: 400,
			minHeight: 390,
			layout: 'fit',
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: accountAddForm,
			closable: false
		});
		
		";
		
		$js .= "function accountAddSuccess() {
			Ext.MessageBox.hide();
			accountStore.reload();
			
			Ext.MessageBox.show({
	    		title: '{$lang['acc']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function accountAddFailed() {
			Ext.MessageBox.hide();
			Ext.MessageBox.show({
	    		title: '{$lang['acc']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank deleted successfully */
		$js .= "function deleteAccountSuccess() {
			Ext.MessageBox.hide();
			accountStore.reload();
			
			Ext.MessageBox.show({
	    		title: '{$lang['acc']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank undeleted or deleted unsuccessfully */
		$js .= "function deleteAccountFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['acc']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank toggled successfully */
		$js .= "function toggleAccountSuccess() {
			Ext.MessageBox.hide();
			accountStore.reload();
			
			Ext.MessageBox.show({
	    		title: '{$lang['acc']['manager']}',
	    		msg: '{{$lang['common']['success']}}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank toggled unsuccessfully */
		$js .= "function toggleAccountFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['acc']['manager']}',
	    		msg: '{{$lang['common']['failed']}}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function deleteSelectedAccount(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/account-manager/delete-account',
	    				method: 'POST',
	    				success: deleteAccountSuccess,
	    				failure: deleteAccountFailed,
	    				params: { id: gridAccount.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function toggleAccountStatus(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/account-manager/toggle-account',
	    				method: 'POST',
	    				success: toggleAccountSuccess,
	    				failure: toggleAccountFailed,
	    				params: { id: gridAccount.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		return $js;
	}
	
	/**
	 * method สำหรับสร้างฟอร์มกำหนดรหัสผ่าน
	 *
	 * @return string
	 */
	public function getSetPasswordForm() {
		global $config;
		global $lang;
		
		$js = "var setPasswordForm = new Ext.form.FormPanel({
			id: 'setPasswordForm',
			baseCls: 'x-plain',
			labelWidth: 120,
			defaultType: 'textfield',
			monitorValid: true,

			items: [{
				id: 'accHiddenID',
				//allowBlank: false,
				name: 'accHiddenID',
				inputType: 'hidden'
			},{
				id: 'accNewPassword',
				fieldLabel: '{$lang['acc']['newpassword']}',
				allowBlank: false,
				name: 'accNewPassword',
				inputType: 'password',
				width: 150
			},{
				id: 'accRetypeNewPassword',
				fieldLabel: '{$lang['acc']['retypeNewPassword']}',
				allowBlank: false,
				name: 'accRetypeNewPassword',
				inputType: 'password',
				width: 150
			}],
			buttons: [{
				id: 'btnSavePassword',
				text: '{$lang['acc']['setpass']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
					
	    			setPasswordWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: '{{$lang['common']['saving']}}',
			           	progressText: '{{$lang['common']['savingText']}}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	
			       	if(Ext.getCmp('accNewPassword').getValue() == Ext.getCmp('accRetypeNewPassword').getValue() ) {
			       		setPasswordForm.getForm().setValues([
			       			{id:'accHiddenID',value: gridAccount.getSelectionModel().getSelected().get('id')},
		            		{id:'accNewPassword',value: hex_md5(Ext.getCmp('accNewPassword').getValue())},
							{id:'accRetypeNewPassword',value: hex_md5(Ext.getCmp('accRetypeNewPassword').getValue())}
		            	]);
		    			Ext.Ajax.request({
		    				url: '/{$config ['appName']}/account-manager/set-password',
		    				method: 'POST',
		    				success: setPasswordSuccess,
		    				failure: setPasswordFailed,
		    				form: Ext.getCmp('setPasswordForm').getForm().getEl()
		    			});
	    			}
	    		}
			},{
				id: 'btnCancelSavePassword',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					setPasswordWindow.hide();
				}
			}]
		});";
		
		$js .= "var setPasswordWindow = new Ext.Window({
			id: 'setPasswordWindow',
			title: '{$lang['acc']['setpass']}',
			width: 315,
			height: 125,
			modal: true,
			minWidth: 315,
			minHeight: 125,
			layout: 'fit',
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: setPasswordForm,
			closable: false
		});
		
		";
		$js .= "function setPasswordSuccess() {
			Ext.MessageBox.hide();
			accountStore.reload();
			
			Ext.MessageBox.show({
	    		title: '{$lang['acc']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank toggled unsuccessfully */
		$js .= "function setPasswordFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['acc']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * method สำหรับแสดงผล Grid Application สำหรับโปรแกรมจัดการบัญชีรายชื่อ
	 *
	 */
	public function getUiAction() {
		global $config;
		global $store;
		global $lang;
		
		checkSessionPortlet();
		
		$addAccountFormJS = $this->getAddAccountForm ();
		$setPasswordFormJS = $this->getSetPasswordForm ();
		$accountStore = $store->getDataStore ( 'account' );
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
		
		/* prepare DIV For UI */
		echo "<div id=\"accountUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		$accountStore
		$accountTypeStore
		$rankTypeStore
		
		$addAccountFormJS
		$setPasswordFormJS
		
		rankComboStore.load();
		
	    accountStore.setDefaultSort('id', 'asc');
	
	    // pluggable renders
	    function renderTopic(value, p, record){
	        return String.format(
	                '<b>{0}</b>',record.data.name);
	    }
	    
		function renderRank(value, p, record){
			if(!rankComboStore.getById(record.data.rank) || record.data.rank == 0) {
				return 'Undefined';
			}else {
				return rankComboStore.getById(record.data.rank).get('name');
			}
	    }
	    
		function renderAccountType(value, p, record){
			if(!accountTypeStore.getAt(record.data.type)) {
				return 'Undefined';
			}else {
				return accountTypeStore.getAt(record.data.type).get('name');
			}
	    }
	    
	    function renderAccountStatus(value, p, record){
	    	switch(record.data.status) {
				case 1: return 'Enabled';break;
				case 0: return 'Disabled';break;
				case null:
				default: return 'Unknown';break;
			}
	    }

	    var cm = new Ext.grid.ColumnModel([{
	           id: 'id', 
	           header: \"{$lang['acc']['name']}\",
	           dataIndex: 'name',
	           width: 420,
	           renderer: renderTopic
	        },{
	           header: \"{$lang['rank']['name']}\",
	           dataIndex: 'rank',
	           width: 70,
	           renderer: renderRank
	        },{
	           header: \"{$lang['acc']['type']}\",
	           dataIndex: 'type',
	           width: 150,
	           renderer: renderAccountType
		    },{
		       header: \"{$lang['common']['status']}\",
		       dataIndex: 'status',
		       width: 150,
		       renderer: renderAccountStatus
		    }
		]);
	
	    cm.defaultSortable = true;
	    Ext.ux.grid.filter.StringFilter.prototype.icon = 'images/find.png';   
	    var acountFilters = new Ext.ux.grid.GridFilters({filters:[
			{type: 'string',  dataIndex: 'name'}
		]});
	
	    var gridAccount = new Ext.grid.GridPanel({
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: accountStore,
	        tbar: new Ext.Toolbar({
				id: 'adminAccountToolbar',
				height: 25				
			}),
			cm: cm,
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
	        loadMask: true,
	        renderTo: 'accountUIDiv',
	        viewConfig: {
	            forceFit:true,
	            enableRowBody:true,
	            showPreview: false,
	            getRowClass : function(record, rowIndex, p, store){
	                if(this.showPreview){
	                    p.body = '<p> Login : '+record.data.login+'</p>';
	                    return 'x-grid3-row-expanded';
	                }
	                return 'x-grid3-row-collapsed';
	            }
	        },
	        plugins: acountFilters,
	        bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: accountStore,
	            plugins: acountFilters,
	            displayInfo: true,
	            displayMsg: '{$lang['acc']['showFrom']} {0} - {1} {$lang['common']['of']} {2}',
	            emptyMsg: \"No Accounts to display\",
	            items:[
	                '-', {
	                pressed: false,
	                enableToggle:true,
	                text: '{$lang['acc']['showDetail']}',
	                cls: 'x-btn-text-icon details',
	                toggleHandler: toggleDetails
	            }]
	        })
	    });
    
    	var tbAccount = Ext.getCmp('adminAccountToolbar');
		
	 	tbAccount.add({
	 		id: 'btnAccountAdd',
            text:'{$lang['acc']['add']}',
            iconCls: 'addIcon',
            handler: function() {
            	accountAddForm.getForm().reset();
				accountAddWindow.show();
			}
        },{
        	id: 'btnSetPassword',
            text:'{$lang['acc']['setpass']}',
            iconCls: 'passwordIcon',
            disabled: true,
            handler: function(e) {
            	setPasswordForm.getForm().reset();
				setPasswordWindow.show();
			}
        },{
        	id: 'btnDeleteAccount',
            text:'{$lang['acc']['delete']}',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['acc']['delete']} [ '+gridAccount.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	id: 'btnToggleAccountStatus',
            text:'{$lang['common']['toggle']}',
            iconCls: 'toggleIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['common']['toggle']} [ '+gridAccount.getSelectionModel().getSelected().get('name')+']?', toggleAccountStatus);
			}
        },{
            text:'{$lang['common']['refresh']}',
            iconCls: 'refreshIcon',
            handler: function(){
            	accountStore.reload();
			}
        });

	    // render it
	    gridAccount.render();
	
	    // trigger the data store load
	    accountStore.load({params:{start:0, limit:25}});
	    gridAccount.colModel.renderCellDelegate = renderCell.createDelegate(gridAccount.colModel);
	    
	    gridAccount.on({
			'rowclick' : function() {
				
				Ext.getCmp('btnSetPassword').enable();
				Ext.getCmp('btnDeleteAccount').enable();
				Ext.getCmp('btnToggleAccountStatus').enable();
				Ext.getCmp('ecmPropertyGrid').setTitle('Account Editor');
				Ext.getCmp('ecmPropertyGrid').on('propertychange',function(source,recordId,value,oldValue) {
					/* Send Request to update property */
					Ext.Ajax.request({
		    			url: '/{$config ['appName']}/account-manager/set-account-property',
		    			method: 'POST',
		    			params: {
		    				accountID: gridAccount.getSelectionModel().getSelected().get('id'),
		    				accountProperty: recordId,
		    				accountValue: value
						}
	    			});
				});
				loadAccountPropertyGrid(gridAccount.getSelectionModel().getSelected().get('id'));
			},
			scope: this
			});
	
	    function toggleDetails(btn, pressed){
	        var view = gridAccount.getView();
	        view.showPreview = pressed;
	        view.refresh();
	    }
	    
		function loadAccountPropertyGrid(id) {
			var accountInfoStore = new Ext.data.Store({
				autoLoad: true,
				proxy: new Ext.data.ScriptTagProxy({
					url: '/{$config ['appName']}/data-store/account-property?accountID='+id
				}),
				
				reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'Name', 'Value','Group','EditorType'
						]
					}),
					// turn on remote sorting
				remoteSort: true					
			});
			
			accountInfoStore.on('load',function(store,records,options) {
				var Property = Ext.data.Record.create([
					{name: 'name'},
					{name: 'value'},
					{name: 'group'},
					{name: 'editor'}
				]);
				var tempStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, Property)});
				
				Ext.getCmp('ecmPropertyGrid').getColumnModel().setColumnWidth(0,150);
				Ext.getCmp('ecmPropertyGrid').getColumnModel().setColumnWidth(1,100);
				
				Ext.getCmp('ecmPropertyGrid').setSource(tempStore.data.items);
				for(i=0;i<store.getCount();i++) {
				
					if(records[i].get('EditorType') == 'boolean') {
						if(records[i].get('Value') == 'true') {
							var rec = new Ext.grid.PropertyRecord({
								id: records[i].get('id'),
								recordId: records[i].get('id'),
						    	name: records[i].get('Name'),
						    	value: true,
					    		group: records[i].get('Group')
							},records[i].get('id'));
						} else {
							var rec = new Ext.grid.PropertyRecord({
								id: records[i].get('id'),
								recordId: records[i].get('id'),
						    	name: records[i].get('Name'),
						    	value: false,
					    		group: records[i].get('Group')
							},records[i].get('id'));
						}
					} else {
						var rec = new Ext.grid.PropertyRecord({
							id: records[i].get('id'),
							recordId: records[i].get('id'),
					    	name: records[i].get('Name'),
					    	value: records[i].get('Value'),
					    	group: records[i].get('Group')
						},records[i].get('id'));
					}
					Ext.getCmp('ecmPropertyGrid').store.add(rec);
				}
			});
			
			accountInfoStore.load();
		}
	    
    
		</script>";
	}
	
	/**
	 * action /add-account/ สำหรับสร้างบัญชีรายชื่อ
	 *
	 */
	public function addAccountAction() {
		global $sequence;
		
		//require_once 'Account.Entity.php';
		
		if (! $sequence->isExists ( 'accountID' )) {
			$sequence->create ( 'accountID' );
		}
		
		$accLoginName = UTFDecode( $_POST ['accLoginName'] );
		$accPassword = UTFDecode( $_POST ['accPassword'] );
		$accFirstName = UTFDecode( $_POST ['accFirstName'] );
		$accMiddleName = UTFDecode( $_POST ['accMiddleName'] );
		$accLastName = UTFDecode( $_POST ['accLastName'] );
		$accRank = $_POST ['accHiddenRank'];
		$accType = $_POST ['accHiddenType'];
		$accEmail = $_POST ['accEmail'];
		$accTelephone = $_POST ['accTelephone'];
		$accFax = $_POST ['accFax'];
		$accMobile = $_POST ['accMobile'];
		
	
		$accountEntity = new AccountEntity ( );
		$accountID = $sequence->get ( 'accountID' );
		$accountEntity->f_acc_id = $accountID;
		$accountEntity->f_login_name = $accLoginName;
		$accountEntity->f_login_password = $accPassword;
		$accountEntity->f_name = $accFirstName;
		$accountEntity->f_mid_name = $accMiddleName;
		$accountEntity->f_last_name = $accLastName;
		$accountEntity->f_rank_id = $accRank;
		$accountEntity->f_account_type = $accType;
		$accountEntity->f_email = $accEmail;
		$accountEntity->f_tel = $accTelephone;
		$accountEntity->f_fax = $accFax;
		$accountEntity->f_mobile = $accMobile;
		$accountEntity->f_tries = 0;
		$accountEntity->f_last_change_pwd = 0;
		$accountEntity->f_status = 1;
		$accountEntity->f_is_expired = 0;
		$accountEntity->f_expired = 0;
		$accountEntity->f_limit_access = 0;
		$accountEntity->f_access_from = '00:00';
		$accountEntity->f_access_to = '00:00';
		$accountEntity->f_ldap_bind = 1;
		$accountEntity->f_ldap_cn = '';
		$accountEntity->f_force_change_pwd_timestamp = (int)time() + 31536000; //ขยายเวลาให้อีก 365 วัน
		
		$accountEntity->Save ();

		return $accountID;
	
	}
	
	/**
	 * Created Date September 13, 2010 02:53:36 PM 
	 * Created By DavinciRoni
	 * action /add-account-from-hr/ สำหรับสร้างบัญชีรายชื่อ
	 *
	 */
	public function addAccountFromHrAction() {
		global $sequence;
		
		//require_once 'Account.Entity.php';
		
		if (! $sequence->isExists ( 'accountID' )) {
			$sequence->create ( 'accountID' );
		}
		
		$accLoginName = UTFDecode( $_POST ['accLoginName'] );
		$accPassword = UTFDecode( $_POST ['accPassword'] );
		$accFirstName = UTFDecode( $_POST ['accFirstName'] );
		$accMiddleName = UTFDecode( $_POST ['accMiddleName'] );
		$accLastName = UTFDecode( $_POST ['accLastName'] );
		$accRank = $_POST ['accHiddenRank'];
		$accType = $_POST ['accHiddenType'];
		$accEmail = $_POST ['accEmail'];
		$accTelephone = $_POST ['accTelephone'];
		$accFax = $_POST ['accFax'];
		$accMobile = $_POST ['accMobile'];
		
		$result = true;
		
		try {
		
			$accountEntity = new AccountEntity ( );
			
			$acccountID = $sequence->get ( 'accountID' );
			$accountEntity->f_acc_id = $acccountID;
			$accountEntity->f_login_name = $accLoginName;
			$accountEntity->f_login_password = $accPassword;
			$accountEntity->f_name = $accFirstName;
			$accountEntity->f_mid_name = $accMiddleName;
			$accountEntity->f_last_name = $accLastName;
			$accountEntity->f_rank_id = $accRank;
			$accountEntity->f_account_type = $accType;
			$accountEntity->f_email = $accEmail;
			$accountEntity->f_tel = $accTelephone;
			$accountEntity->f_fax = $accFax;
			$accountEntity->f_mobile = $accMobile;
			$accountEntity->f_tries = 0;
			$accountEntity->f_last_change_pwd = 0;
			$accountEntity->f_status = 1;
			$accountEntity->f_is_expired = 0;
			$accountEntity->f_expired = 0;
			$accountEntity->f_limit_access = 0;
			$accountEntity->f_access_from = '00:00';
			$accountEntity->f_access_to = '00:00';
			$accountEntity->f_ldap_bind = 0;
			$accountEntity->f_ldap_cn = '';
			
			$accountEntity->Save ();
			
		} catch (Exception $e) {
			$result = false;
		}
		if ($result) 
			echo "0";
		else
			echo "-1";
	
	}	
	
	/**
	 * action /delete-account/ สำหรับลบบัญชีรายชื่อ
	 *
	 */
	public function deleteAccountAction() {
		//require_once 'Account.Entity.php';
		
		$accountID = $_POST ['id'];
		$accountEntity = new AccountEntity ( );
		$accountEntity->Load ( "f_acc_id = '{$accountID}'" );
		$accountEntity->Delete ();
	}
	
	/**
	 * action /toggle-account/ สำหรับเปลี่ยนสถานะบัญชีรายชื่อ
	 *
	 */
	public function toggleAccountAction() {
		//require_once 'Account.Entity.php';
		
		$accountID = $_POST ['id'];
		
		$accountEntity = new AccountEntity ( );
		$accountEntity->Load ( "f_acc_id = '{$accountID}'" );
		if ($accountEntity->f_status == 1) {
			$accountEntity->f_status = 0;
		} else {
			$accountEntity->f_status = 1;
		}
		$accountEntity->Save ();
	}
	
	/**
	 *
	 * Created Date : September 13, 2010 02:35:08 PM 
	 * Created By DavinciRoni
	 * Action /toggle-account-by-username/ สำหรับเปลี่ยนสถานะบัญชีรายชื่อ 
	 * Param loginname(String), status(Integer : 0=disabled or 1=enabled)
	 *
	 */
	public function toggleAccountByUsernameAction() {
		//require_once 'Account.Entity.php';
		
		$loginName = ( array_key_exists( 'loginname', $_POST ) ) ? $_POST ['loginname'] : '';
		$status = ( array_key_exists( 'status', $_POST ) ) ? $_POST ['status'] : 0;
		if ( $loginName == '' && $status == 0 )
		{
			$loginName = ( array_key_exists( 'loginname', $_GET ) ) ? $_GET ['loginname'] : '';
			$status = ( array_key_exists( 'status', $_GET ) ) ? $_GET ['status'] : 0;
		}
		
		$result = true;
		try {
			$accountEntity = new AccountEntity ( );
			$accountEntity->Load ( "f_login_name = '{$loginName}'" );
			$accountEntity->f_status = $status;
			$accountEntity->Save ();
		} catch (Exception $e) {
			$result = false;
		}
		if ($result) 
			echo "0";
		else
			echo "-1";
		
	}	
	
	/**
	 * action /set-password/ สำหรับเปลี่ยนรหัสผ่าน
	 *
	 */
	public function setPasswordAction() {
		//require_once 'Account.Entity.php';
		
		$id = $_POST ['accHiddenID'];
		$newPass = $_POST ['accNewPassword'];
		$accountEntity = new AccountEntity ( );
		$accountEntity->Load ( "f_acc_id = '{$id}'" );
		$accountEntity->f_login_password = $newPass;
		$accountEntity->Update ();
	}
	
	/**
	 * action /set-account-property/ สำหรับเปลี่ยนคุณสมบัติของบัญชีรายชื่อ
	 *
	 */
	public function setAccountPropertyAction() {
		global $store;
		global $util;
		
		//require_once 'Account.Entity.php';
		
		$accountID = $_POST ['accountID'];
		$accountProperty = $_POST ['accountProperty'];
		$accountValue = $_POST ['accountValue'];
		
		$propertyEditor = $store->getAccountPropertyEditor ();
		
		$accountEntity = new AccountEntity ( );
		$accountEntity->Load ( "f_acc_id = '{$accountID}'" );
		$accountField = $store->getAccountMapping ( $accountProperty, 'id' );
		if ($propertyEditor [$accountField] == 'boolean') {
			if ($accountValue == 'true') {
				$accountValue = 1;
			} else {
				$accountValue = 0;
			}
		}
		
		if($propertyEditor[$accountField] == 'date') {
			$accountValue = $util->dateToStamp(UTFDecode($accountValue));
			Logger::debug("Converted: ".$accountValue);	
		}
		
		$accountEntity->{$accountField} = UTFDecode( $accountValue );
		$accountEntity->Update ();
		
		$response = array();
		$response['success'] = 1;
		
		echo json_encode($response);
	}

	/**
	 * Created Date November 30, 2010
	 * Created By rasa
	 * action /add-account-hr/ สำหรับสร้างบัญชีรายชื่อ
	 *
	 */
	public function addAccountHrAction() {
		/*$depStatus = $this->addAcc(
				$_REQUEST['sd'], 
				$_REQUEST['f_role_id'], 
				$_REQUEST['f_role_name'], 
				$_REQUEST['f_role_desc'], 
				$_REQUEST['f_acc_id'], 
				$_REQUEST['f_login_password'], 
				$_REQUEST['f_login_name'], 
				$_REQUEST['f_name'], 
				$_REQUEST['f_mid_name'], 
				$_REQUEST['f_last_name'], 
				$_REQUEST['f_email'], 
				$_REQUEST['f_tel'],
				$_REQUEST['f_fax'], 
				$_REQUEST['f_mobile'], 
				$_REQUEST['f_dep_id'], 
				$_REQUEST['f_dep_name'], 
				$_REQUEST['f_dep_desc'], 
				$_REQUEST['f_dep_ext_code'], 
				$_REQUEST['f_dep_int_code']);*/
		$secStatus = $this->addAcc(
				$_REQUEST['sd'], 
				$_REQUEST['f_role_id'], 
				$_REQUEST['f_role_name'], 
				$_REQUEST['f_role_desc'], 
				$_REQUEST['f_acc_id'], 
				$_REQUEST['f_login_password'], 
				$_REQUEST['f_login_name'], 
				$_REQUEST['f_name'], 
				$_REQUEST['f_mid_name'], 
				$_REQUEST['f_last_name'], 
				$_REQUEST['f_email'], 
				$_REQUEST['f_tel'],
				$_REQUEST['f_fax'], 
				$_REQUEST['f_mobile'], 
				$_REQUEST['f_sec_id'], 
				$_REQUEST['f_sec_name'], 
				$_REQUEST['f_sec_desc'], 
				$_REQUEST['f_sec_ext_code'], 
				$_REQUEST['f_sec_int_code']);

		$status = "(dep: $depStatus---sec: $secStatus)";
		echo $status;
		Logger:: SyncHRLog(7, $_REQUEST, $status);
	}

	/**
	 * Created Date November 30, 2010
	 * Created By rasa
	 * action /edit-account-hr/ สำหรับแก้ไขบัญชีรายชื่อ
	 *
	 */
	public function editAccountHrAction() {
		/*$depStatus = $this->addAcc(
				$_REQUEST['sd'], 
				$_REQUEST['f_role_id'], 
				$_REQUEST['f_role_name'], 
				$_REQUEST['f_role_desc'], 
				$_REQUEST['f_acc_id'], 
				$_REQUEST['f_login_password'], 
				$_REQUEST['f_login_name'], 
				$_REQUEST['f_name'], 
				$_REQUEST['f_mid_name'], 
				$_REQUEST['f_last_name'], 
				$_REQUEST['f_email'], 
				$_REQUEST['f_tel'],
				$_REQUEST['f_fax'], 
				$_REQUEST['f_mobile'], 
				$_REQUEST['f_dep_id'], 
				$_REQUEST['f_dep_name'], 
				$_REQUEST['f_dep_desc'], 
				$_REQUEST['f_dep_ext_code'], 
				$_REQUEST['f_dep_int_code']);*/
		$secStatus = $this->addAcc(
				$_REQUEST['sd'], 
				$_REQUEST['f_role_id'], 
				$_REQUEST['f_role_name'], 
				$_REQUEST['f_role_desc'], 
				$_REQUEST['f_acc_id'], 
				$_REQUEST['f_login_password'], 
				$_REQUEST['f_login_name'], 
				$_REQUEST['f_name'], 
				$_REQUEST['f_mid_name'], 
				$_REQUEST['f_last_name'], 
				$_REQUEST['f_email'], 
				$_REQUEST['f_tel'],
				$_REQUEST['f_fax'], 
				$_REQUEST['f_mobile'], 
				$_REQUEST['f_sec_id'], 
				$_REQUEST['f_sec_name'], 
				$_REQUEST['f_sec_desc'], 
				$_REQUEST['f_sec_ext_code'], 
				$_REQUEST['f_sec_int_code']);

		$status = "(dep: $depStatus---sec: $secStatus)";
		echo $status;
		Logger:: SyncHRLog(8, $_REQUEST, $status);
	}

	public function getRoleEcmAction($roleId, $orgId) {
		global $conn;

		$sql = "select ecm_role_id from tbl_match_role where hr_role_id='" . $roleId . "' and hr_org_id='" . $orgId . "'";
		$role = $conn->Execute ( $sql );
		$tmp = $role->FetchRow();
		$ECMRole = $tmp['ECM_ROLE_ID'];
		
		if ($ECMRole == '')
		{
			return false;
		}
		else
		{
			return $ECMRole;
		}
	}

	public function getAccountEcmAction($accountId) {
		global $conn;
		$sql = "select ecm_account_id from tbl_match_account where hr_account_id='" . $accountId . "'";
		$rs = $conn->Execute ( $sql );
		$tmp = $rs->FetchRow();
		$ECMAccount = $tmp['ECM_ACCOUNT_ID'];

		if ($ECMAccount == '')
		{
			return false;
		}
		else
		{
			return $ECMAccount;
		}
	}

	public function checkMatchAccAction($roleId) {
		global $conn;

		$sql = "select count(*) as count_exp from tbl_match_account where hr_account_id='" . $roleId . "'";
		$rsCount = $conn->Execute ( $sql );
		$tmpCount = $rsCount->FetchNextObject ();

		if ($tmpCount->COUNT_EXP == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function checkMatchRoleAction($roleId, $orgid) {
		global $conn;

		$sql = "select count(*) as count_exp from tbl_match_role where hr_role_id='" . $roleId . "' and hr_org_id='" . $orgid . "'";
		$rs = $conn->Execute ( $sql );
		$tmpCount = $rs->FetchNextObject ();

		if ($tmpCount->COUNT_EXP == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function getOrganizeEcmAction($organizeId) {
		global $conn;

		$sql = "select ecm_org_id from tbl_match_organize where hr_org_id='" . $organizeId . "'";
		$rs = $conn->Execute ( $sql );
		$tmp = $rs->FetchRow();
		$ECMOrganize = $tmp['ECM_ORG_ID'];
		
		return $ECMOrganize;
	}

	public function addRoleFromHr ($status, $HR_RoleID, $HR_OrgID, $HR_RoleName = '', $HR_RoleDesc = '') {
		global $sequence;
		global $conn;
		$ECM_RoleID	= '';

		if ($this->checkMatchRoleAction($HR_RoleID, $HR_OrgID)) 
		{
			$ECM_RoleID = $this->getRoleEcmAction($HR_RoleID, $HR_OrgID);


			if($this->checkRealRoleInEcm ($ECM_RoleID))
			{
				$roleEntity = new RoleEntity ( );
				$roleEntity->load("f_role_id = '{$ECM_RoleID}'");
				$roleEntity->f_role_name	= $HR_RoleName;
				$roleEntity->f_org_id			= $this->getOrganizeEcmAction($HR_OrgID);
				$roleEntity->update ();
			}
			else
			{
				$ECM_PosID		= 19;
				$ECM_PolicyID		= 5;
				$ECM_Name		= $HR_RoleName;
				$ECM_Desc			= $HR_RoleDesc;
				$ECM_Governer	= 0;
				$ECM_OrgID		= $this->getOrganizeEcmAction($HR_OrgID);

				$roleEntity = new RoleEntity ( );
				$roleEntity->f_role_id			= $ECM_RoleID;
				$roleEntity->f_role_name	= $ECM_Name;
				$roleEntity->f_role_desc		= $ECM_Desc;
				$roleEntity->f_role_status	= 1;
				$roleEntity->f_org_id			= $ECM_OrgID;
				$roleEntity->f_pos_id			= $ECM_PosID;
				$roleEntity->f_gp_id			= $ECM_PolicyID;
				$roleEntity->f_is_governer = $ECM_Governer;
				$roleEntity->save ();
			}
			return $ECM_RoleID;
		} else {
			$ECM_RoleID		= $sequence->get ( 'roleID' );
			$ECM_PosID		= 19;
			$ECM_PolicyID		= 5;
			$ECM_Name		= $HR_RoleName;
			$ECM_Desc			= $HR_RoleDesc;
			$ECM_Governer	= 0;

			$ECM_OrgID		= $this->getOrganizeEcmAction($HR_OrgID);
			if ($ECM_OrgID != '') {
				if (! $sequence->isExists ( 'roleID' )) {
					$sequence->create ( 'roleID' );
				}

				$roleEntity						= new RoleEntity ( );
				$roleID							= $sequence->get ( 'roleID' );
				$roleEntity->f_role_id			= $ECM_RoleID;
				$roleEntity->f_role_name	= $ECM_Name;
				$roleEntity->f_role_desc		= $ECM_Desc;
				$roleEntity->f_role_status	= 1;
				$roleEntity->f_org_id			= $ECM_OrgID;
				$roleEntity->f_pos_id			= $ECM_PosID;
				$roleEntity->f_gp_id			= $ECM_PolicyID;
				$roleEntity->f_is_governer = $ECM_Governer;
				$roleEntity->Save ();
				

				$sql = 'insert into tbl_match_role (hr_role_id, ecm_role_id, hr_org_id) values (\'' . $HR_RoleID . '\', ' . $ECM_RoleID .  ', \'' . $HR_OrgID . '\')';
				$rs = $conn->Execute ($sql);
			}
		}
		if ( $ECM_RoleID == 0)
			return false;
		else
			return $ECM_RoleID;
	}

	
	/*public function loadDataAccAction () {

		$type = 'oci8';
		$host = 'DBSRV';
		$uid = 'OIC54';
		$pwd = 'OIC54';
		$database = 'slcdb';

		$pmis = NewADOConnection($type);
		$pmis->PConnect ( $host, $uid, $pwd, $database );
		if(!$pmis){
			echo 'false';
			return false;
		}

		$sql = "select * from appv_sync_personnel where f_acc_id=";
		$pmis->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $pmis->getAll($sql);
		//print_r($rs);

		foreach($rs as $value){
			$_REQUEST['f_role_name']			= $value['F_ROLE_NAME'];
			$_REQUEST['f_org_id']				= $value['F_ORG_ID'];
			$_REQUEST['f_role_id']				= $value['F_ROLE_ID'];
			$_REQUEST['f_acc_id']				= $value['F_ACC_ID'];
			$_REQUEST['f_login_name']		= $value['F_LOGIN_NAME'];
			$_REQUEST['f_name']				= UTFEncode($value['F_NAME']);
			$_REQUEST['f_last_name']			= UTFEncode($value['F_LAST_NAME']);
			$_REQUEST['f_email']				= $value['F_EMAIL'];
			$_REQUEST['f_login_name']		= $value['F_LOGIN_NAME'];//print_r($value);print_r($_REQUEST);die(0);
			$this->addAccountHrAction();
		}
		echo 'success';
	}*/

	public function loadAccFromHrAction () {
		global $conn;

		$input = $_REQUEST;
		$userID = $input['userID'];
		$success = '';
		$description = '';
		$countRow = 0;
		
		if ( $input['check'] == 1) 
		{
			if (!isset($input['userID']) || $input['userID'] == '' ) 
			{
				$description = "กรุณกรอกรหัสผู้ใช้งานใหม่";
			} 
			else 
			{
				$type = 'oci8';
				$host = 'DBSRV';
				$uid = 'OIC54';
				$pwd = 'OIC54';
				$database = 'slcdb';

				$pmis = NewADOConnection($type);
				$pmis->PConnect ( $host, $uid, $pwd, $database );
				if(!$pmis)
				{
					echo 'false';
					return false;
				}

				$sqlHR = "select * from appv_sync_personnel where f_acc_id='" . $userID . "'";
				$pmis->SetFetchMode(ADODB_FETCH_ASSOC);
				$rsHR = $pmis->getAll($sqlHR);

				$sqlMatchECM = "select count(*) as count_ex from tbl_match_account where hr_account_id='" . $userID . "'";
				$conn->SetFetchMode(ADODB_FETCH_ASSOC);
				$rsMatchECM = $conn->getAll($sqlMatchECM);

				$countMatch = $rsMatchECM[0]['COUNT_EX'];

				$status = 0;
				if ($countMatch == 0) 
				{
					foreach($rsHR as $value)
					{
						$status = $this->addAcc ($this->seedGenerate(), $value['F_ROLE_ID'], $value['F_ROLE_NAME'], '', $value['F_ACC_ID'], '', $value['F_LOGIN_NAME'], UTFEncode($value['F_NAME']), '', UTFEncode($value['F_LAST_NAME']), $value['F_EMAIL'], '', '', '',$value['F_ORG_ID'], '', '', '', '') ;
					}

					if ($status == 1) 
					{
						$success = 'ทำการโหลดข้อมูลสำเร็จแล้ว';
					} 
					else 
					{
						$description = "ไม่สามารถโหลดข้อมูลได้ เนื่อจจากข้อมูลไม่ครบถ้วน";
					}
				} 
				else 
				{
					if (!$this->checkRealAccountInEcm($accountID))
					{
						addAccountEcm(
								$acccountID, 
								$rsHR[0]['F_LOGIN_NAME'], 
								$rsHR[0]['F_LOGIN_PASSWORD'], 
								$rsHR[0]['F_NAME'], 
								'', 
								$rsHR[0]['F_LAST_NAME'], 
								33, 
								0, 
								$rsHR[0]['F_EMAIL'], 
								'', 
								'', 
								'');

						$success = 'ทำการโหลดข้อมูลสำเร็จแล้ว';
					}
					else
					{
						$description = "ผู้ใช้งานนี้ถูกโหลดเข้าไปในระบบของ ECM แล้ว";
					}

					$roleId = $this->addRoleFromHr ('add',$rsHR[0]['F_ROLE_ID'], $rsHR[0]['F_ORG_ID'], $rsHR[0]['F_ROLE_NAME'], '');
					if ($roleId == '')
					{
						$success = '';
						$description = "ไม่สามารถโหลดข้อมูลตำแหน่งของพนักงานได้";
					}

				}

			}
		}
			
		$this->setupECMActionController ();
		$this->setECMViewModule ('report');
		
		$this->ECMView->userID = $userID;
		$this->ECMView->description = $description;
		$this->ECMView->countRow = $countRow;
		$this->ECMView->success = $success;
		
		$output = $this->ECMView->render ('viewLoadData.php');

		echo $output;
	}

	function seedGenerate()
	{
		$salt = 'SLC*(-_-)*';
		return substr(md5($salt.date('Ymd')), 0, 16);
	}

	function checkRealRoleInEcm ($roleId)
	{
		global $conn;

		$sql = "select count(*) as count_ex from tbl_role where f_role_id=$roleId";
		$rs = $conn->Execute ( $sql );
		$tmp = $rs->FetchRow();
		$count = $tmp['COUNT_EX'];
		
		if ($count == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function updateAccountEcm($accountID, $firstName, $middleName, $lastName, $login_name, $login_pass)
	{
		$firstName = $firstName;
		$middleName = $middleName;
		$lastName = $lastName;
		$login_name = $login_name;
		$login_pass = md5($login_pass);

		$result = true;
		try
		{
			$accountEntity = new AccountEntity ( );
			$accountEntity->Load ( "f_acc_id = '{$accountID}'" );
			$accountEntity->f_name = $firstName;
			$accountEntity->f_mid_name = $middleName;
			$accountEntity->f_last_name = $lastName;
			$accountEntity->f_login_name = $login_name;
			if ($login_pass != '')
				$accountEntity->f_login_password = $login_pass;
			$accountEntity->Update ();
		}
		catch(exception $e)
		{
			$result = false;
		}

		return $result;
	}

	function checkRealAccountInEcm($accountID)
	{
		global $conn;

		$sql = "select count(*) as count_ex from tbl_account where f_acc_id='" . $accountID . "'";
		$conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $conn->Execute ( $sql );
		$tmp = $rs->FetchRow();
		$count = $tmp['COUNT_EX'];

		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function addAccountEcm(
			$acccountID, 
			$accLoginName, 
			$accPassword, 
			$accFirstName, 
			$accMiddleName, 
			$accLastName, 
			$accRank, 
			$accType, 
			$accEmail, 
			$accTelephone, 
			$accFax, 
			$accMobile)
	{
		$result = true;
		try
		{
			$accountEntity = new AccountEntity ( );
			$accountEntity->f_acc_id = $acccountID;
			$accountEntity->f_login_name = $accLoginName;
			$accountEntity->f_login_password = md5($accPassword);
			$accountEntity->f_name = $accFirstName;
			$accountEntity->f_mid_name = $accMiddleName;
			$accountEntity->f_last_name = $accLastName;
			$accountEntity->f_rank_id = $accRank;
			$accountEntity->f_account_type = $accType;
			$accountEntity->f_email = $accEmail;
			$accountEntity->f_tel = $accTelephone;
			$accountEntity->f_fax = $accFax;
			$accountEntity->f_mobile = $accMobile;
			$accountEntity->f_tries = 0;
			$accountEntity->f_last_change_pwd = 0;
			$accountEntity->f_status = 1;
			$accountEntity->f_is_expired = 0;
			$accountEntity->f_expired = 0;
			$accountEntity->f_limit_access = 0;
			$accountEntity->f_access_from = '00:00';
			$accountEntity->f_access_to = '00:00';
			$accountEntity->f_ldap_bind = 0;
			$accountEntity->f_ldap_cn = '';
			
			$accountEntity->Save ();

		}
		catch(exception $e)
		{
			$result = false;
		}

		return $result;
	}

	function addPassport ($accountId, $newRoleId)
	{
		global $conn;
		try
		{
			$sql_CheckDefault = "select count(f_acc_id) as count_default from tbl_passport where f_acc_id = $accountId and f_default_role = 1";
			$rsCheckDefault		= $conn->Execute ( $sql_CheckDefault );
			$tmpCheckDefault		= $rsCheckDefault->FetchNextObject ();
			if ($tmpCheckDefault->COUNT_DEFAULT > 0) {
				$defaultStatus		= 0;
			} else {
				$defaultStatus		= 1;
			}

			$sql_CheckRow = "select count(f_acc_id) as count_default from tbl_passport where f_acc_id = $accountId and f_role_id = $newRoleId";
			$rsCheckRow		= $conn->Execute ( $sql_CheckRow );
			$tmpCheckRow	= $rsCheckRow->FetchNextObject ();
			if ($tmpCheckRow->COUNT_DEFAULT == 0) {
				$sql = "insert into tbl_passport values($newRoleId, $accountId, $defaultStatus)";
				$rs = $conn->Execute ( $sql );
			} 
		}
		catch (exception $e)
		{
			return false;
		}
		return true;
	}

	public function addAcc (
			$SD, 
			$HR_roleId, 
			$HR_roleName, 
			$HR_roleDesc, 
			$HR_accId, 
			$HR_loginPass, 
			$HR_loginName, 
			$HR_accName, 
			$HR_accMidName, 
			$HR_accLastName, 
			$HR_accEmail, 
			$HR_accTel, 
			$HR_accFax, 
			$HR_accMobile,
			$HR_orgId, 
			$HR_orgName, 
			$HR_orgDesc, 
			$HR_orgExtCode, 
			$HR_orgIntode) 
	{
		global $conn;
		global $sequence;
		global $config; 
		$status = 1;

		/**************************************

		เช็คการส่งมาจาก HR 

		**************************************/
		//if (!$this->seedCheck($SD)) 
		//{
			//$status = -2;
			//return $status;
		//}



		/**************************************
					เช็ค paramiter 
		**************************************/
		if (!isset($HR_roleId)
					|| !isset($HR_roleName)
					|| !isset($HR_accId)
					|| !isset($HR_loginName)
					|| !isset($HR_accName)
					|| !isset($HR_accLastName))
		{ 
			$status = -1;
			return $status;
		}



		/**************************************
					เพิ่มตำแหน่ง
		**************************************/
		$ECMRoleID = $this->addRoleFromHr (
				'add', 
				$HR_roleId, 
				$HR_orgId, 
				$HR_roleName, 
				$HR_roleDesc);
		if ($ECMRoleID == '') {
			$status = 0;
			return $status;
		}

			
			
		/**************************************
					สำหรับการเพิ่มบัญชีรายชื่อ
		**************************************/


		if ($this->checkMatchAccAction($HR_accId)) {

			// find Account id from mapping

			$accountID = $this->getAccountEcmAction( $HR_accId );
			if ($accountID == '') {
				$status = 0;
				return $status;
			}

			//	update account

			if (!$this->updateAccountEcm(
						$accountID,
						$HR_accName, 
						$HR_accMidName, 
						$HR_accLastName, 
						$HR_loginName, 
						$HR_loginPass))
			{
				$status = 0;
				return $status;
			}

			if (!$this->hasPassport ($accountID, $ECMRoleID))
			{
				$sql = "select count(f_acc_id) as count_default from tbl_passport where f_acc_id = '$accountID' and f_default_role = 1";
				$rsCheckDefault		= $conn->Execute ( $sql );
				$tmpCheckDefault		= $rsCheckDefault->FetchNextObject ();
				if ($tmpCheckDefault->COUNT_DEFAULT > 0) {
					$defaultStatus		= 0;
				} 
				else 
				{
					$defaultStatus		= 1;
				}
				$sqlMapping		= "insert into tbl_passport(f_role_id,f_acc_id,f_default_role) ";
				$sqlMapping		.= " values('{$ECMRoleID}','{$accountID}','$defaultStatus')";
				$conn->Execute ( $sqlMapping );
			}
		} 
		else 
		{

			// ทำการ add account
			$_POST['accLoginName']	= $HR_loginName;
			$_POST['accPassword']		= md5($HR_loginPass);
			$_POST['accFirstName']		= $HR_accName;
			$_POST['accMiddleName']	= $HR_accMidName;
			$_POST['accLastName']		= $HR_accLastName;
			$_POST['accHiddenRank']	= 33;
			$_POST['accHiddenType']	= 0;
			$_POST['accEmail']			= $HR_accEmail;
			$_POST['accTelephone']	= $HR_accTel;
			$_POST['accFax']				= $HR_accFax;
			$_POST['accMobile']			= $HR_accMobile;

			$ECMAccountID	= $this->addAccountAction();
			if ($ECMAccountID == '') {
				$status = 0;
				return $status;
			}



			// ทำการ add passport

			$sql = "select count(f_acc_id) as count_default from tbl_passport where f_acc_id = '$ECMAccountID' and f_default_role = 1";
			$rsCheckDefault		= $conn->Execute ( $sql );
			$tmpCheckDefault		= $rsCheckDefault->FetchNextObject ();
			if ($tmpCheckDefault->COUNT_DEFAULT > 0) {
				$defaultStatus		= 0;
			} 
			else 
			{
				$defaultStatus		= 1;
			}
			$sqlMapping		= "insert into tbl_passport(f_role_id,f_acc_id,f_default_role) ";
			$sqlMapping		.= " values('{$ECMRoleID}','{$ECMAccountID}','$defaultStatus')";
			$conn->Execute ( $sqlMapping );


			// ทำการ add mappig 

			$sqlInsert		= 'insert into tbl_match_account (hr_account_id, ecm_account_id) values (\'' . $HR_accId . '\', ' . $ECMAccountID . ')';
			$rs				= $conn->Execute ($sqlInsert);	
		}
	
		return $status;
		
	}

	function hasPassport ($accId, $roleId)
	{
		global $conn;

		$sql = "select count(*) as count_ex from tbl_passport where f_acc_id=$accId and f_role_id=$roleId";
		$rsCheckDefault		= $conn->Execute ( $sql );
		$tmpCheckDefault		= $rsCheckDefault->FetchNextObject ();
		if ($tmpCheckDefault->COUNT_EX > 0) {
			return true;
		} 
		else 
		{
			return false;
		}
	}
}