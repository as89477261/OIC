<?php
/**
 * โปรแกรมส่งข้อมูล
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 * 
 *
 */

class StorageManagerController extends Zend_Controller_Action {
	/**
	 * สร้างฟอร์มสร้าง Storage
	 *
	 * @return string
	 */
	private function getAddStorageForm() {
		global $config;
		
		$js = "var storageAddForm = new Ext.form.FormPanel({
			id: 'storageAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'Storage Name',
				name: 'storageName',
				anchor:'100%'  // anchor width by percentage
			},new Ext.form.LocalComboBox({
				name: 'storageType',
				fieldLabel: 'Storage Type',
				store: storageTypeStore,
				displayField:'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				value: 0,
				selectOnFocus:true
			}),{
				fieldLabel: 'Storage Host',
				name: 'storageHost',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Storage Path',
				name: 'storagePath',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'UID',
				name: 'storageUID',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Password',
				name: 'storagePWD',
				inputType: 'password',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Limit',
				name: 'storageLimit',
				align: 'left',
				xtype: 'checkbox'
			},{
				fieldLabel: 'Size',
				name: 'storageSize',
				value: 0,
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Status',
				name: 'storageStatus',
				checked: true,
				xtype: 'checkbox'
			},{
				fieldLabel: 'Default',
				name: 'storageDefault',
				xtype: 'checkbox'
			}]
		});";
		
		$js .= "var storageAddWindow = new Ext.Window({
			id: 'storageAddWindow',
			title: 'Add Storage',
			width: 300,
			height: 325,
			minWidth: 300,
			minHeight: 325,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: storageAddForm,
			closable: false,
			buttons: [{
				id: 'btnAddStorage',
				text: 'Add Storage',
				handler: function() {
					
	    			storageAddWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: 'Saving your data, please wait...',
			           	progressText: 'Saving...',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       
	    			Ext.Ajax.request({
	    				url: '/{$config ['appName']}/storage-manager/add-storage',
	    				method: 'POST',
	    				success: storageAddSuccess,
	    				failure: storageAddFailed,
	    				form: Ext.getCmp('storageAddForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelAddStorage',
				text: 'Cancel',
				handler: function() {
					storageAddWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function storageAddSuccess() {
			Ext.MessageBox.hide();
			
			storageStore.reload();
			
			Ext.MessageBox.show({
	    		title: 'Storage Manager',
	    		msg: 'Storage Added!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function storageAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Storage Manager',
	    		msg: 'Failed to add Storage!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function deleteStorageSuccess() {
			Ext.MessageBox.hide();
			
			storageStore.load();
			
			Ext.MessageBox.show({
	    		title: 'Rank Manager',
	    		msg: 'Rank Deleted!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function deleteStorageFailed() {
			Ext.MessageBox.hide();
			
			storageStore.load();
			
			Ext.MessageBox.show({
	    		title: 'Rank Manager',
	    		msg: 'Failed to delete rank!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function toggleStorageSuccess() {
			Ext.MessageBox.hide();
			
			storageStore.load();
			
			Ext.MessageBox.show({
	    		title: 'Rank Manager',
	    		msg: 'Rank Deleted!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function toggleStorageFailed() {
			Ext.MessageBox.hide();
			
			storageStore.load();
			
			Ext.MessageBox.show({
	    		title: 'Rank Manager',
	    		msg: 'Failed to delete rank!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function setDefaultStorageSuccess() {
			Ext.MessageBox.hide();
			
			storageStore.load();
			
			Ext.MessageBox.show({
	    		title: 'Rank Manager',
	    		msg: 'Rank Deleted!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function setDefaultStorageFailed() {
			Ext.MessageBox.hide();
			
			storageStore.load();
			
			Ext.MessageBox.show({
	    		title: 'Rank Manager',
	    		msg: 'Failed to delete rank!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function deleteSelectedStorage(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/storage-manager/delete-storage',
	    				method: 'POST',
	    				success: deleteStorageSuccess,
	    				failure: deleteStorageFailed,
	    				params: { id: gridStorage.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function setDefaultStorage(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/storage-manager/set-default',
	    				method: 'POST',
	    				success: setDefaultStorageSuccess,
	    				failure: setDefaultStorageFailed,
	    				params: { id: gridStorage.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function toggleStorageStatus(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/storage-manager/toggle-storage',
	    				method: 'POST',
	    				success: toggleStorageSuccess,
	    				failure: toggleStorageFailed,
	    				params: { id: gridStorage.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		return $js;
	}
	
	/**
	 * สร้างฟอร์มแก้ uid/pwd ของ Storage
	 *
	 * @return string
	 */
	private function getChangeCredentialForm() {
		global $config;
		
		$js = "var changeCredentialForm = new Ext.form.FormPanel({
			id: 'changeCredentialForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'Storage ID',
				name: 'storageModID',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'UID',
				name: 'storageModUID',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Password',
				name: 'storageModPWD',
				inputType: 'password',
				anchor:'100%'  // anchor width by percentage
			}]
		});";
		
		$js .= "var changeCredentialWindow = new Ext.Window({
			id: 'changeCredentialWindow',
			title: 'Change Storage Credential',
			width: 300,
			height: 125,
			minWidth: 300,
			minHeight: 125,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: changeCredentialForm,
			closable: false,
			buttons: [{
				id: 'btnAddStorage',
				text: 'Add Storage',
				handler: function() {
					
	    			changeCredentialWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: 'Saving your data, please wait...',
			           	progressText: 'Saving...',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       
	    			Ext.Ajax.request({
	    				url: '/{$config ['appName']}/storage-manager/change-credential',
	    				method: 'POST',
	    				success: credentialChangeSuccess,
	    				failure: credentialChangeFailed,
	    				form: Ext.getCmp('changeCredentialForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelAddStorage',
				text: 'Cancel',
				handler: function() {
					changeCredentialWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function credentialChangeSuccess() {
			Ext.MessageBox.hide();
			
			storageStore.reload();
			
			Ext.MessageBox.show({
	    		title: 'Storage Manager',
	    		msg: 'Storage Added!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function credentialChangeFailed() {
			Ext.MessageBox.hide();
			
			storageStore.load();
			
			Ext.MessageBox.show({
	    		title: 'Storage Manager',
	    		msg: 'Failed to add Storage!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		return $js;
	}
	
	/**
	 * action /get-ui แสดงหน้าจอจัดการ Storage
	 *
	 */
	public function getUiAction() {
		global $config;
		global $store;
		
		checkSessionPortlet();
		
		$storageStore = $store->getDataStore ( 'storage' );
		$storateTypeStore = $store->getDataStore ( 'storageType' );
		
		$addStorageForm = $this->getAddStorageForm ();
		$changeCredentialForm = $this->getChangeCredentialForm ();
		
		/* prepare DIV For UI */
		echo "<div id=\"storageUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		/* Remote Data Store for Ranks */
		$storageStore
		$storateTypeStore
		
		storageStore.load();
		
		$addStorageForm
		$changeCredentialForm
		
		var gridStorage = new Ext.grid.GridPanel({
			id: 'gridStorage',
			store: storageStore,
			tbar: new Ext.Toolbar({
					id: 'adminStorageToolbar',
					height: 25				
				}),
			autoExpandMax: true,
			columns: [
			{id: 'id', header: 'Storage Name', width: 300, sortable: false, dataIndex: 'name'},
			{header: 'Type', width: 120, sortable: false, dataIndex: 'type'},
			{header: 'Server', width: 300, sortable: false, dataIndex: 'server'},
			{header: 'Path/Database', width: 300, sortable: false, dataIndex: 'path'},
			{header: 'User', width: 200, sortable: false, dataIndex: 'uid'},
			{header: 'Password', width: 200, sortable: false, dataIndex: 'pwd'},
			{header: 'Limit', width: 120, sortable: false, dataIndex: 'limit'},
			{header: 'Size', width: 300,align: 'right', sortable: false, dataIndex: 'size'},
			{header: 'Status', width: 120, sortable: false, dataIndex: 'status'},
			{header: 'Default', width: 120, sortable: false, dataIndex: 'default'}
			],
			viewConfig: {
				forceFit: true
			},
			loadMask: true,
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
			frame: false,
			iconCls:'icon-grid',
			renderTo:'storageUIDiv'
		});
		
		var tbStorage = Ext.getCmp('adminStorageToolbar');
		
	 	tbStorage.add({
	 		id: 'btnStorageAdd',
            text:'Add Storage',
            iconCls: 'addIcon',
            handler: function() {
            	storageAddForm.getForm().reset();
				storageAddWindow.show();
			}
        },{
        	id: 'btnChangeCredential',
            text:'Change Credential',
            iconCls: 'passwordIcon',
            disabled: true, 
            handler: function() {
            	changeCredentialWindow.show();
            	changeCredentialForm.getForm().setValues([
            		{id:'storageModID',value: gridStorage.getSelectionModel().getSelected().get('id')},
					{id:'storageModUID',value: ''},
					{id:'storageModPWD',value: ''}
            	]);
			}
        },{
        	id: 'btnDeleteStorage',
            text:'Delete Storage',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('Confirm', 'Delete Storage [ '+gridStorage.getSelectionModel().getSelected().get('name')+']?', deleteSelectedStorage);
			}
        },{
        	id: 'btnToggleStorageStatus',
            text:'Disable/Enable Storage',
            iconCls: 'toggleIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('Confirm', 'Toggle Storage [ '+gridStorage.getSelectionModel().getSelected().get('name')+'] Status?', toggleStorageStatus);
			}
        },{
        	id: 'btnSetDefaultStorage',
            text:'Set as Default',
            iconCls: 'mappingIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('Confirm', 'Set Storage [ '+gridStorage.getSelectionModel().getSelected().get('name')+'] as Default?', setDefaultStorage);
			}
        },{
            text:'Refresh View',
            iconCls: 'refreshIcon',
            handler: function(){
            	storageStore.load();
			}
        });
        
       
		gridStorage.on({
		'rowclick' : function() {
			Ext.getCmp('btnToggleStorageStatus').enable();
			Ext.getCmp('btnDeleteStorage').enable();
			Ext.getCmp('btnChangeCredential').enable();
			Ext.getCmp('btnSetDefaultStorage').enable();
			Ext.getCmp('ecmPropertyGrid').on('propertychange',function(source,recordId,value,oldValue) {
					/* Send Request to update property */
					Ext.Ajax.request({
		    			url: '/{$config ['appName']}/storage-manager/set-storage-property',
		    			method: 'POST',
		    			params: {
		    				storageID: gridStorage.getSelectionModel().getSelected().get('id'),
		    				storageProperty: recordId,
		    				storageValue: value
						}//,
		    			//success: function() {
		    			//	accountStore.reload();
						//},
		    			//failure: rankAddFailed,
		    			//form: Ext.getCmp('policyAddForm').getForm().getEl()
	    			});
				});
			loadStoragePropertyGrid(gridStorage.getSelectionModel().getSelected().get('id'));
		},
		scope: this
		});
		gridStorage.render();
		storageStore.load();
		function loadStoragePropertyGrid(id) {
			var storageInfoStore = new Ext.data.Store({
				autoLoad: true,
				proxy: new Ext.data.ScriptTagProxy({
					url: '/{$config ['appName']}/data-store/storage-property?storageID='+id
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
			
			storageInfoStore.on('load',function(store,records,options) {
				var Property = Ext.data.Record.create([
					{name: 'name'},
					{name: 'value'},
					{name: 'group'},
					{name: 'editor'}
				]);
				var storageTempStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, Property)});
				
	
				Ext.getCmp('ecmPropertyGrid').getColumnModel().setColumnWidth(0,150);
				Ext.getCmp('ecmPropertyGrid').getColumnModel().setColumnWidth(1,100);
				
				Ext.getCmp('ecmPropertyGrid').setSource(storageTempStore.data.items);
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
			
			storageInfoStore.load();
		}
		</script>";
	}
	
	/**
	 * action /add-storage สร้าง Storage
	 *
	 */
	public function addStorageAction() {
		global $sequence;
		
		if (! $sequence->isExists ( 'storageID' )) {
			$sequence->create ( 'storageID' );
		}
		
		//include_once ('Storage.Entity.php');
		
		$storageName = $_POST ['storageName'];
		
		if ($_POST ['storageType'] == 'WebDAV') {
			$storageType = 0;
		} else {
			$storageType = 1;
		}
		$storageHost = $_POST ['storageHost'];
		$storagePath = $_POST ['storagePath'];
		$storageUID = $_POST ['storageUID'];
		$storagePWD = $_POST ['storagePWD'];
		$storageSize = $_POST ['storageSize'];
		if (array_key_exists ( 'storageLimit', $_POST ) && $_POST ['storageLimit'] == 'on') {
			$storageLimit = 1;
		} else {
			$storageLimit = 0;
		}
		
		if (array_key_exists ( 'storageStatus', $_POST ) && $_POST ['storageStatus'] == 'on') {
			$storageStatus = 1;
		} else {
			$storageStatus = 0;
		}
		
		if (array_key_exists ( 'storageDefault', $_POST ) && $_POST ['storageDefault'] == 'on') {
			$storageDefault = 1;
		} else {
			$storageDefault = 0;
		}
		
		$storage = new StorageEntity ( );
		$storage->f_st_id = $sequence->get ( 'storageID' );
		$storage->f_st_name = $storageName;
		$storage->f_st_type = $storageType;
		$storage->f_st_server = $storageHost;
		$storage->f_st_port = 80;
		$storage->f_st_path = $storagePath;
		$storage->f_st_uid = $storageUID;
		$storage->f_st_pwd = $storagePWD;
		$storage->f_st_size = $storageSize;
		$storage->f_st_limit = $storageLimit;
		$storage->f_status = $storageStatus;
		$storage->f_default = $storageDefault;
		
		$storage->Save ();
	}
	
	/**
	 * action /delete-storage ลบ Storage
	 *
	 */
	public function deleteStorageAction() {
		//include_once ('Storage.Entity.php');
		
		$storageID = $_POST ['id'];
		$storage = New StorageEntity ( );
		$storage->Load ( "f_st_id = '{$storageID}'" );
		$storage->Delete ();
	}
	
	/**
	 * action /toggle-storage แก้ไขสถานะ Storage
	 *
	 */
	public function toggleStorageAction() {
		//include_once ('Storage.Entity.php');
		
		$storageID = $_POST ['id'];
		$storage = New StorageEntity ( );
		$storage->Load ( "f_st_id = '{$storageID}'" );
		if ($storage->f_status == 0) {
			$storage->f_status = 1;
		} else {
			$storage->f_status = 0;
		}
		$storage->Update ();
	
	}
	
	/**
	 * action /set-default กำหนด Default Storage
	 *
	 */
	public function setDefaultAction() {
		global $conn;
		
		$storageID = $_POST ['id'];
		
		$sqlUpdate1 = "update tbl_storage set f_default = 0";
		$sqlUpdate2 = "update tbl_storage set f_default = 1 where f_st_id = '{$storageID}'";
		$conn->Execute ( $sqlUpdate1 );
		$conn->Execute ( $sqlUpdate2 );
	
	}
	
	/**
	 * action /change-credential แก้ไข Credential
	 *
	 */
	public function changeCredentialAction() {
		//include_once ('Storage.Entity.php');
		
		$storageID = $_POST ['storageModID'];
		$storageUID = $_POST ['storageModUID'];
		$storagePWD = $_POST ['storageModPWD'];
		
		$storage = New StorageEntity ( );
		$storage->Load ( "f_st_id = '{$storageID}'" );
		$storage->f_st_uid = $storageUID;
		$storage->f_st_pwd = $storagePWD;
		$storage->Update ();
	}
	
	/**
	 * action /set-storage-property แก้ไข Storage Property
	 *
	 */
	public function setStoragePropertyAction() {
		global $store;
		$storageID = $_POST ['storageID'];
		$storageProperty = $_POST ['storageProperty'];
		$storageValue = $_POST ['storageValue'];
		
		$propertyEditor = $store->getStoragePropertyEditor ();
		//include_once ('Storage.Entity.php');
		$storageEntity = new StorageEntity();
		$storageEntity->Load ( "f_st_id = '{$storageID}'" );
		$storageField = $store->getStorageMapping ( $storageProperty, 'id' );
		if ($propertyEditor [$storageField] == 'boolean') {
			if ($storageValue == 'true') {
				$storageValue = 1;
			} else {
				$storageValue = 0;
			}
		}
		
		$storageEntity->{$storageField} = UTFDecode( $storageValue );
		$storageEntity->Update ();
	}

}
