<?php
/**
 * ������ʴ� DMS Explorer
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DMS
 */
class DMSExplorerController extends ECMController {
	
	/**
	 * redirect � action /get-ui/
	 *
	 */
	public function indexAction() {
		global $util;
		$util->redirect ( '/dms-explorer/get-ui' );
	}
	
	/**
	 * �ӡ������ҧ���������Ѻ˹�Ҩ� ISO Approval
	 *
	 * @return string
	 */
	public function getISOApproveForm() {
		global $config;
		global $lang;
		
		$js = "var isoRequestForm = new Ext.form.FormPanel({
			id: 'isoRequestForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'Document Name',
				id: 'isoDocName',
				allowBlank: false,
				name: 'docName',
				width: 250
			},{
				fieldLabel: 'Document Code',
				id: 'isoDocCode',
				allowBlank: false,
				name: 'isoDocCode',
				width: 250
			},{
				fieldLabel: 'Document Type',
				id: 'isoDocCode',
				allowBlank: false,
				name: 'isoDocCode',
				width: 250
			},{
				fieldLabel: 'Revision',
				allowBlank: false,
				name: 'isoDocRevision',
				width: 100
			},{
				fieldLabel: 'Effective Date',
				allowBlank: true,
				id: 'isoEffectiveDate',
				name: 'isoEffectiveDate',
				width: '100'
			},{
				fieldLabel: 'Request Type',
				allowBlank: true,
				id: 'isoEffectiveDate',
				name: 'isoEffectiveDate',
				width: '100'
			},{
				fieldLabel: 'Objective',
				allowBlank: true,
				id: 'isoObjective',
				name: 'isoObjective',
				xtype: 'textarea',
				height: '100',
				width: '200'
			},{
				fieldLabel: 'Description',
				allowBlank: true,
				id: 'isoDescription',
				name: 'isoDescription',
				xtype: 'textarea',
				height: '100',
				width: '200'
			}]
		});";
		
		$js .= "var isoRequestWindow = new Ext.Window({
			id: 'isoRequestWindow',
			title: 'ISO Approve Request',
			width: 500,
			height: 300,
			minWidth: 300,
			minHeight: 300,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: isoRequestForm,
			closable: false,
			buttons: [{
				text: 'Request Approval',
				iconCls: 'saveIcon',
				handler: function() {
					
	    			isoRequestWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/dms/create-cabinet',
		    			method: 'POST',
		    			success: ISORequestSuccess,
		    			failure: ISORequestFailed,
		    			form: Ext.getCmp('isoRequestForm').getForm().getEl()
		    		});
	    		}
			},{
				text: 'Cancel',
				iconCls: 'rejectIcon',
				handler: function() {
					isoRequestWindow.hide();
				}
			}]
		});
		";
		
		$js .= "function ISORequestSuccess() {
			Ext.MessageBox.hide();
			
			DMSTree.getNodeById(Cookies.get('contextDMSElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: '{$lang ['common'] ['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function ISORequestFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to add Account!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ���ҧ���
	 *
	 * @return string
	 */
	public function getCreateCabinetForm() {
		global $config;
		global $lang;
		$js = "var cabinetAddForm = new Ext.form.FormPanel({
			id: 'cabinetAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'parentType',
				id: 'DMSParentType',
				allowBlank: false,
				name: 'DMSParentType',
				//inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'parentID',
				id: 'DMSParentID',
				allowBlank: false,
				name: 'DMSParentID',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Name',
				allowBlank: false,
				name: 'cabinetName',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'orgDesc',
				fieldLabel: 'Description',
				allowBlank: true,
				name: 'cabinetDesc',
				xtype: 'textarea',
				width: '100',
				height: '100'
			}]
		});";
		
		$js .= "var cabinetAddWindow = new Ext.Window({
			id: 'cabinetAddWindow',
			title: 'Create New Cabinet',
			width: 500,
			height: 300,
			minWidth: 300,
			minHeight: 300,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: cabinetAddForm,
			closable: false,
			buttons: [{
				id: 'btnCreateCabinet',
				text: 'Create Cabinet',
				handler: function() {
					
	    			cabinetAddWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/dms/create-cabinet',
		    			method: 'POST',
		    			success: cabinetAddSuccess,
		    			failure: cabinetAddFailed,
		    			form: Ext.getCmp('cabinetAddForm').getForm().getEl()
		    		});
	    		}
			},{
				id: 'btnCancelCreateCabinet',
				text: 'Cancel',
				handler: function() {
					cabinetAddWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function cabinetAddSuccess() {
			Ext.MessageBox.hide();
			
			
			DMSTree.getNodeById(Cookies.get('contextDMSElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: '{$lang ['common'] ['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function cabinetAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to add Account!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ���ҧ��鹪ѡ
	 *
	 * @return string
	 */
	public function getCreateDrawerForm() {
		global $config;
		global $lang;
		$js = "var drawerAddForm = new Ext.form.FormPanel({
			id: 'drawerAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'parentType',
				id: 'drawerParentType',
				allowBlank: false,
				name: 'DMSParentType',
				//inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'parentID',
				id: 'drawerParentID',
				allowBlank: false,
				name: 'drawerParentID',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'drawerObjectMode',
				id: 'drawerObjectMode',
				allowBlank: false,
				name: 'drawerObjectMode',
				anchor:'100%'  // anchor width by percentage
			
			},{
				fieldLabel: 'Name',
				allowBlank: false,
				name: 'drawerName',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'drawerDesc',
				fieldLabel: 'Description',
				allowBlank: true,
				name: 'drawerDesc',
				xtype: 'textarea',
				width: '100%',
				height: '100'
			}]
		});";
		
		$js .= "var drawerAddWindow = new Ext.Window({
			id: 'drawerAddWindow',
			title: 'Create New Drawer',
			width: 500,
			height: 300,
			minWidth: 300,
			minHeight: 300,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: drawerAddForm,
			closable: false,
			buttons: [{
				id: 'btnCreateDrawer',
				text: 'Create Drawer',
				handler: function() {
					
	    			drawerAddWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/dms/create-drawer',
		    			method: 'POST',
		    			success: drawerAddSuccess,
		    			failure: drawerAddFailed,
		    			form: Ext.getCmp('drawerAddForm').getForm().getEl()
		    		});
	    		}
			},{
				id: 'btnCancelCreateDrawer',
				text: 'Cancel',
				handler: function() {
					drawerAddWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function drawerAddSuccess() {
			Ext.MessageBox.hide();
			
			DMSTree.getNodeById(Cookies.get('contextDMSElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: '{$lang ['common'] ['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function drawerAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to add Account!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ���ҧ���
	 *
	 * @return string
	 */
	public function getCreateFolderForm() {
		global $config;
		global $lang;
		$js = "var folderAddForm = new Ext.form.FormPanel({
			id: 'folderAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'parentType',
				id: 'folderParentType',
				allowBlank: false,
				name: 'folderParentType',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'parentID',
				id: 'folderParentID',
				allowBlank: false,
				name: 'folderParentID',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'folderObjectMode',
				id: 'folderObjectMode',
				allowBlank: false,
				name: 'folderObjectMode',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Name',
				allowBlank: false,
				name: 'folderName',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'folderDesc',
				fieldLabel: 'Description',
				allowBlank: true,
				name: 'folderDesc',
				xtype: 'textarea',
				width: '95%',
				height: '100'
			},{
				id: 'folderKeyword',
				fieldLabel: 'Keyword',
				allowBlank: true,
				name: 'folderKeyword',
				xtype: 'textarea',
				width: '95%',
				height: '100'
			}]
		});";
		
		$js .= "var folderAddWindow = new Ext.Window({
			id: 'folderAddWindow',
			title: 'Create New Folder',
			width: 500,
			height: 300,
			minWidth: 300,
			minHeight: 300,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: folderAddForm,
			closable: false,
			modal: true,
			buttons: [{
				id: 'btnCreateFolder',
				text: 'Create Folder',
				iconCls: 'saveIcon',
				handler: function() {
					
	    			folderAddWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/dms/create-folder',
		    			method: 'POST',
		    			success: folderAddSuccess,
		    			failure: folderAddFailed,
		    			form: Ext.getCmp('folderAddForm').getForm().getEl()
		    		});
			       	
			     
	    		}
			},{
				id: 'btnCancelCreateDrawer',
				text: 'Cancel',
				iconCls: 'cancelIcon',
				handler: function() {
					folderAddWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function folderAddSuccess() {
			Ext.MessageBox.hide();
			
			
			DMSTree.getNodeById(Cookies.get('contextDMSElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: '{$lang ['common'] ['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function folderAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to add Account!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ������
	 *
	 * @return string
	 */
	public function getEditFolderForm() {
		global $config;
		global $lang;
		$js = "var folderEditForm = new Ext.form.FormPanel({
			id: 'folderEditForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'Type',
				id: 'editFolderType',
				allowBlank: false,
				name: 'editFolderType',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'ID',
				id: 'editFolderID',
				allowBlank: false,
				name: 'editFolderID',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'ObjectMode',
				id: 'editFolderObjectMode',
				allowBlank: false,
				name: 'editFolderObjectMode',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			
			},{
				id: 'editFolderName',
				fieldLabel: 'Name',
				allowBlank: false,
				name: 'editFolderName',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'editFolderDesc',
				fieldLabel: 'Description',
				allowBlank: true,
				name: 'editFolderDesc',
				xtype: 'textarea',
				width: '95%',
				height: '90'
			},{
				id: 'editFolderKeyword',
				fieldLabel: 'Keyword',
				allowBlank: true,
				name: 'editFolderKeyword',
				xtype: 'textarea',
				width: '95%',
				height: '90'
			}]
		});";
		
		$js .= "var folderEditWindow = new Ext.Window({
			id: 'folderEditWindow',
			title: '{$lang ['common'] ['modify']}',
			width: 500,
			height: 300,
			minWidth: 300,
			minHeight: 300,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: folderEditForm,
			closable: false,
			modal: true,
			buttons: [{
				id: 'btnEditFolder',
				text: 'Edit Folder',
				iconCls: 'saveIcon',
				handler: function() {
					
	    			folderEditWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/dms/edit-folder',
		    			method: 'POST',
		    			success: folderEditSuccess,
		    			failure: folderEditFailed,
		    			form: Ext.getCmp('folderEditForm').getForm().getEl()
		    		});
			       	
			     
	    		}
			},{
				id: 'btnCancelCreateDrawer',
				text: 'Cancel',
				iconCls: 'cancelIcon',
				handler: function() {
					folderEditWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function folderEditSuccess() {
			Ext.MessageBox.hide();

			DMSTree.getNodeById(Cookies.get('contextDMSElID')).parentNode.reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: '{$lang ['common'] ['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function folderEditFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to add Account!',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹��Ҩ�����Ѻ��˹��ѹ�������
	 *
	 * @return string
	 */
	public function getExpireDocumentForm() {
		global $config;
		global $lang;
		global $store;
		
		$notifierTypeStore = $store->getDataStore ( 'notifierType' );
		
		$js = "
			$notifierTypeStore
			var frmDocExpire = new Ext.form.FormPanel({
			id: 'frmDocExpire',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [new Ext.ux.DateTimeField ({
		      	fieldLabel: '{$lang ['dms'] ['expireStamp']}',    
		       	name: 'dtpDocExpire',
		       	emptyText: '{$lang ['common'] ['default']}',
		      	width: 100
			}),{
				id: 'sendMailTo',
				name: 'sendMailTo',
				fieldLabel: '{$lang ['dms'] ['mailto']}',
				allowBlank: false,
				anchor:'100%'  // anchor width by percentage	
			},{
				id: 'sendMailToHidden',
				name: 'sendMailToHidden',
				xtype:'hidden'
			},new Ext.form.LocalComboBox({
        		//id: 'cboNotifierType',
        		//name: 'cboNotifierType',
        		fieldLabel: '{$lang ['df'] ['sendType']}',
        		hiddenName: 'cboNotifierType',
				store: notifierTypeStore,
				displayField: 'name',
				valueField: 'value',
				value: 2,
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				selectOnFocus: true
			})]
		});";
		
		$js .= "var wndDocExpire = new Ext.Window({
			id: 'wndDocExpire',
			title: '" . $lang ['common'] ['input'] . $lang ['dms'] ['expireStamp'] . "',
			width: 350,
			height: 150,
			minWidth: 350,
			minHeight: 150,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmDocExpire,
			closable: false,
			modal: true,
			buttons: [{
				id: 'btnSaveDocExpire',
				text: '{$lang ['common'] ['save']}',
				iconCls: 'saveIcon',
				handler: function() {
					
	    			wndDocExpire.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: '{$lang ['common'] ['wait']}',
			           	progressText: '{$lang ['common'] ['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/dms-action/input-document-expire',
		    			method: 'POST',
		    			success: docExpireSuccess,
		    			failure: docExpireFailed,
		    			form: Ext.getCmp('frmDocExpire').getForm().getEl()
		    		});
			       	
			     
	    		}
			},{
				id: 'btnCancelDocExpire',
				text: '{$lang ['common'] ['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					wndDocExpire.hide();
				}
			}]
		});
		
		";
		
		$js .= "function docExpireSuccess() {
			Ext.MessageBox.hide();

			DMSTree.getNodeById(Cookies.get('contextDMSElID')).parentNode.reload();
			
			Ext.MessageBox.show({
	    		title: '{$lang ['dms'] ['expireStamp']}',
	    		msg: '{$lang ['common'] ['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function docExpireFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: '{$lang ['dms'] ['expireStamp']}',
	    		msg: '{$lang ['common'] ['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "Ext.getCmp('sendMailTo').on('focus',function() {
					sendInternalListWindow.show();
					Cookies.set('rc','sendMailTo');
					Cookies.set('rcH','sendMailToHidden');
				},this);";
		
		return $js;
	}
	
	public function getSeeAlsoForm() {
		
		$js = "
	        var tempSeeAlso = new Ext.data.Store({reader: new Ext.data.JsonReader({}, SeeAlsoRecordDataDef)});
	        
	        var seeAlsoFormWindow = new Ext.Window({
	            id: 'seeAlsoFormWindow',
	            title: '�ѹ�֡ See Also',
	            width: 300,
	            height: 315,
	            minWidth: 300,
	            minHeight: 315,              
	            layout: 'fit',
	            labelWidth: 95,
	            plain:true,
	            bodyStyle:'padding:5px;',
	            buttonAlign:'center',
	            resizable: true,
	            layout: 'form',
	            items: [
	              		new Ext.form.TextField({
	                    		id: 'seeAlsoRef',
	                    		fieldLabel: '�ѹ�֡ See Also',
	                    		name: 'seeAlsoRef',
	                    		width: 150
							}),
							new Ext.form.Hidden({
	                    		id: 'seeAlsoRefID',
	                    		name: 'seeAlsoRefID'
							}),{
	                    layout: 'column',
	                    labelWidth: 75, 
	                    baseCls: 'x-plain',
	                    items:[{
	                            columnWidth: .95,
	                            labelWidth: 75,  
	                            layout: 'form',           
	                            baseCls: 'x-plain',
	                            items:[
		                            new Ext.grid.GridPanel({
		                                id: 'gridSeeAlso',
		                                tbar: new Ext.Toolbar({
		                                    id: 'SeeAlsoToolbar',
		                                    height: 25                
		                                }),
		                                store: tempSeeAlso,
		                                enableDragDrop: true,
		                                enableDrop: true,
		                                enableDrag: true,
		                                ddGroup : 'treeDD', 
		                                autoExpandMax: true,
		                                columns: [
		                                {id: 'id', header: 'Name', width: 120, sortable: false, dataIndex: 'name'},
		                                {header: 'Description', width: 0, sortable: false, dataIndex: 'description'}
		                                ],
		                                viewConfig: {
		                                    forceFit: true
		                                },
		                                sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
		                                width: 260,
		                                height: 200,
		                                frame: false,
		                                iconCls:'icon-grid'
		                            })]
	                        }]
	                }],
	            buttons: [{
	                id: 'btnConfirmSeeAlso',
	                text: 'Confirm',
	                handler: function() {
	                /*
	                    var sendStringData = '';
	                    var sendNameData = '';    
	                    var CCStringData = '';
	                    var CCNameData = '';
	                    for(i=0;i<tempSendStore.getCount();i++) {
	                        dataTempSend = tempSendStore.getAt(i);
	                        if(sendStringData == '') {
	                            sendStringData = sendStringData + dataTempSend.data.typeid+'_'+dataTempSend.data.dataid;
	                        } else {
	                           sendStringData = sendStringData + ' , '+ dataTempSend.data.typeid+'_'+dataTempSend.data.dataid;
	                        }
	                        if(sendNameData == '') {
	                            sendNameData = sendNameData + dataTempSend.data.name;
	                        } else {
	                           sendNameData = sendNameData + ' , '+ dataTempSend.data.name;
	                        }
	                    }
	                        
	                    for(i=0;i<tempCCStore.getCount();i++) {
	                        dataTempCC = tempCCStore.getAt(i);
	                        if(CCStringData == '') {
	                            CCStringData = CCStringData + dataTempCC.data.typeid+'_'+dataTempCC.data.dataid;
	                        } else {
	                           CCStringData = CCStringData + ' , '+ dataTempCC.data.typeid+'_'+dataTempCC.data.dataid;
	                        }
	                        if(CCNameData == '') {
	                            CCNameData = CCNameData + dataTempCC.data.name;
	                        } else {
	                           CCNameData = CCNameData + ' , '+ dataTempCC.data.name;
	                        }
	                    }
	                 
	                        Ext.getCmp(Cookies.get('rc')).setValue('');
	                        Ext.getCmp(Cookies.get('rcH')).setValue('');
	                    if(sendNameData!='') {                                       
	                        if(CCNameData != '') {
	                            Ext.getCmp(Cookies.get('rc')).setValue( sendNameData +','+  CCNameData);
	                        } else {
	                            Ext.getCmp(Cookies.get('rc')).setValue( sendNameData);
	                        }
	                    } else {
	                        if(CCNameData != '') {
	                            Ext.getCmp(Cookies.get('rc')).setValue( CCNameData);
	                        }
	                    }
	                    
	                    if(sendStringData!='') {                                       
	                        if(CCStringData != '') {
	                            Ext.getCmp(Cookies.get('rcH')).setValue(  sendStringData +','+  CCStringData);
	                        } else {
	                            Ext.getCmp(Cookies.get('rcH')).setValue(  sendStringData);
	                        }
	                    } else {
	                        if(CCStringData != '') {
	                            Ext.getCmp(Cookies.get('rcH')).setValue(  CCStringData);
	                        }
	                    }
	                                                        
	                    //alert(CCNameData);
	                    //alert(CCStringData);
	                    */
	                    seeAlsoFormWindow.hide();
	                }
	            },{
	                id: 'btnHideSeeAlsoWindow',
	                text: 'Cancel',
	                handler: function() {
	                    seeAlsoFormWindow.hide();
	                }
	            }],
	            closable: false
        	});
			
        	
        ";
		
		return $js;
	}
	/**
	 * ���ҧ˹�Ҩ͡������׹�͡���
	 *
	 * @return string
	 */
	public function getBorrowDocumentForm() {
		global $config;
		global $lang;
		
		$js = "
			var resultBorrow = new Ext.XTemplate(
	            '<tpl for=\".\"><div class=\"search-item\">',
	                '<table width=\"90%\">',
	                    '<tr><td><b>{name}</b></td></tr>',
	                    '<tr><td align=\"right\">������:{desctype}</td></tr>',
	                '</table>',               
	            '</div></tpl>'
	        );
	        
	        var autocompleteBorrowerNameOnlyStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/name-only'
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
        
			var frmBorrowDocument = new Ext.form.FormPanel({
			id: 'frmBorrowDocument',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid: true,

			items: [new Ext.ux.DateTimeField ({
		      	fieldLabel: '{$lang ['dms'] ['returnDueStamp']}',    
		       	name: 'dtpDueDate',
		       	allowBlank: false,
		       	emptyText: '{$lang ['common'] ['default']}',
		      	width: 125
			}), new Ext.form.ComboBox ({
				id: 'lookupBorrower',
				store: autocompleteBorrowerNameOnlyStore,
				fieldLabel:  '{$lang ['dms'] ['borrower']}',
				style: autoFieldStyle,
				typeAhead: false,
				width: 200,
				valueField: 'id',
				displayField: 'name',
				loadingText: '{$lang['common']['searching']}',
				hideTrigger: true,
				labelStyle: 'font-weight:bold;color: Red;',
				tpl: resultBorrow,
				minChars: 2,
				shadow: false,
				autoLoad: true,
				mode: 'remote',
				itemSelector: 'div.search-item'
			} ),{
				fieldLabel:  '��������´/˹���͡��÷�����',
				id: 'borrowDetail',
				name: 'borrowDetail',
				xtype: 'textarea',
				width: 200,
				height: 50
				
			}, {
				id: 'lookupBorrowerHidden',
				name: 'lookupBorrowerHidden',
				allowBlank: false,
				xtype:'hidden'
			}],
			buttons: [{
				id: 'btnBorrowDocument',
				//formBind:true,
				disabled: true,
				text: '{$lang ['common'] ['save']}',
				iconCls: 'saveIcon',
				handler: function() {
	    			wndBorrowDocument.hide();
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: '{$lang ['common'] ['wait']}',
			           	progressText: '{$lang ['common'] ['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/dms-action/save-borrow',
		    			method: 'POST',
		    			success: borrowSuccess,
		    			failure: borrowFail,
		    			form: Ext.getCmp('frmBorrowDocument').getForm().getEl()
		    		});
			       	
			     
	    		}
			},{
				text: '{$lang ['common'] ['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					wndBorrowDocument.hide();
				}
			}]
		});";
		
		$js .= "var wndBorrowDocument = new Ext.Window({
			id: 'wndBorrowDocument',
			title: '" . $lang ['dms'] ['borrow'] . "',
			width: 350,
			height: 180,
			minWidth: 350,
			minHeight: 180,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmBorrowDocument,
			closable: false,
			modal: true
		});
		
		
		Ext.getCmp('lookupBorrower').on('select',function(cb,rc,idx) {
			//alert(Ext.getCmp('lookupBorrower').getValue());
			Ext.getCmp('lookupBorrowerHidden').setValue(Ext.getCmp('lookupBorrower').getValue());
			Ext.getCmp('btnBorrowDocument').enable();
			
		},this);
		
		function borrowSuccess() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: '{$lang ['dms'] ['borrow']}',
	    		msg: '{$lang ['common'] ['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.INFO
	    	});
		}
		
		function borrowFail() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: '{$lang ['dms'] ['borrow']}',
	    		msg: '{$lang ['common'] ['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		icon: Ext.MessageBox.ERROR
	    	});
		}
		";
		
		return $js;
	}
	
	/**
	 * ���ҧ˹�Ҩ�����Ѻ�ӡ�� Check In
	 *
	 * @return string
	 */
	public function getCheckinForm() {
		global $config;
		global $lang;
		
		$js = "var frmCheckin = new Ext.form.FormPanel({
			id: 'frmCheckin',
			baseCls: 'x-plain',
			labelWidth: 100,
//			defaultType: 'textfield',
			items: [{
						fieldLabel: 'Type',
						id: 'editFolderType',
						allowBlank: false,
						name: 'editFolderType',
						inputType: 'hidden',
						anchor:'100%'  // anchor width by percentage
					}],
			buttons: [{
				id: 'btnSaveCheckin',
				text: '{$lang ['dms'] ['checkin']}',
				iconCls: 'checkinIcon',
				handler: function() {
					
	    			wndCheckin.hide();
			       	Ext.Ajax.request({
		                url: '/{$config ['appName']}/document/checkin?docID='+ Cookies.get('contextDocumentID'),
		                method: 'GET',
		                success: function(o){
		                	
			                Ext.MessageBox.hide();
			                var r = Ext.decode(o.responseText);
			                var responseMsg = r.message;
							var result;
							
							if(r.success == 1) {
								messageSuccess(null,null);
								DMSTree.getNodeById(Cookies.get('contextDMSElID')).parentNode.reload();
							} else {
								messageFailed(null, responseMsg);
							}
						}
					});
	    		}
			},{
				id: 'btnCancelCheckin',
				text: '{$lang ['common'] ['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					wndCheckin.hide();
				}
			}]
		});";
		
		$js .= "var wndCheckin = new Ext.Window({
			id: 'wndCheckin',
			title: '{$lang ['dms'] ['checkin']}',
			width: 250,
			height: 100,
			minWidth: 250,
			minHeight: 100,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmCheckin,
			closable: false,
			modal: true
		});
		
		";
		
		return $js;
	}
	
	/**
	public function getSelectDocumentForm() {
		global $config;
		//global $conn;
		

		include_once 'Form.Entity.php';
		$defaultForm = new FormEntity ( );
		$defaultForm->Load ( "f_default_form = '1'" );
		$js = "var documentAddForm = new Ext.form.FormPanel({
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
			closable: true,
			modal: true,
			buttons: [{
				id: 'btnCreateDocument',
				text: 'Create Document',
				handler: function() {
					
	    			documentAddWindow.hide();
	    			
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
		});
		
		
		";
		
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
	 */
	
	/**
	 * ˹�Ҩ�����Ѻ�ʴ� Explorer
	 *
	 */
	public function getUiAction() {
		global $config;
		global $lang;
		global $sessionMgr;
		
		checkSessionPortlet ();
		
		//global $store;
		if ($sessionMgr->isDegradeMode ()) {
			$disabledByDegradeModeJS = "
			Ext.getCmp('mnuItemCreateFolder').disable();
			Ext.getCmp('mnuItemCreateDocument').disable();
			Ext.getCmp('mnuItemCreateShortcut').disable();
			Ext.getCmp('mnuItemEditDMSObject').disable();
			Ext.getCmp('mnuItemDeleteDMSObject').disable();
			Ext.getCmp('mnuItemRestoreDMSObject').disable();
			Ext.getCmp('mnuItemEmptyRecyclebinDMSObject').disable();
			Ext.getCmp('mnuItemExpireDMSObject').disable();
			Ext.getCmp('mnuItemCheckoutDMSObject').disable();
			Ext.getCmp('mnuItemCheckinDMSObject').disable();
			Ext.getCmp('mnuItemISOApproval').disable();
			";
		} else {
			$disabledByDegradeModeJS = "";
		}
		
		$createCabinetJS = $this->getCreateCabinetForm ();
		$createDrawerJS = $this->getCreateDrawerForm ();
		$createFolderJS = $this->getCreateFolderForm ();
		$editFolderFormJS = $this->getEditFolderForm ();
		$expireDocumentFormJS = $this->getExpireDocumentForm ();
		$checkinFormJS = $this->getCheckinForm ();
		/* prepare DIV For UI */
		
		echo "<div id=\"DMSExplorerUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		function renderDMSTreeType(value, p, record){
			//alert(record.tt);
			console.log('xxxx');
			console.log(record.tt);
			/*
			if(record.objtype == 0) {
				return '{$lang['org']['ou']}';
			}
			
			if(record.objtype == 1) {
				return '{$lang['org']['group']}';
			}
			
			if(record.objtype == 2) {
				return '{$lang['org']['role']}';
			}
			
			if(record.objtype == 3) {
				return '{$lang['org']['user']}';
			}
			*/
			return 'xxxx';
		}
		
		$createCabinetJS
		$createDrawerJS
		$createFolderJS
		$editFolderFormJS
		$expireDocumentFormJS
		$checkinFormJS
		
		{$this->getISOApproveForm()}
		
		{$this->getBorrowDocumentForm()}
		{$this->getSeeAlsoForm()}
		
		var DMSTreeContext = new Ext.menu.Menu('DMSContext');
		
		DMSTreeContext.add(		
        	//new Ext.menu.Item({id:'mnuItemCreateCabinet', text: 'New Cabinet'}),
        	//new Ext.menu.Item({id:'mnuItemCreateDrawer' ,text: 'New Drawer'}),
        	
        	new Ext.menu.Item({id:'mnuItemCreateFolder' ,text: '{$lang ['common'] ['create']}{$lang ['dms'] ['folder']}', iconCls: 'folderIcon'}),
        	new Ext.menu.Item({id:'mnuItemCreateDocument' ,text: '{$lang ['common'] ['create']}{$lang ['dms'] ['document']}', iconCls: 'documentIcon'}),
        	new Ext.menu.Item({id:'mnuItemCreateShortcut' ,text: '{$lang ['common'] ['create']}{$lang ['dms'] ['shortcut']}', iconCls: 'shortcutIcon'}),
        	
        	//new Ext.menu.Separator(),
        	
        	//new Ext.menu.Item({id: 'mnuItemSearchDMSObject',text: '{$lang ['action'] ['globalSearch']}'}),
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id: 'mnuItemExport',text: 'Export'}),
        	new Ext.menu.Item({id: 'mnuItemSeeAlso',text: '�ѹ�֡ See Also'}),
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id: 'mnuItemPublish',text: '������͡���'}),
        	new Ext.menu.Item({id: 'mnuItemUnpublish',text: '¡��ԡ���������͡���'}),
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id: 'mnuItemBorrow',text: '����͡���'}),
        	//new Ext.menu.Item({id: 'mnuItemReturn',text: '�׹�͡���'}),
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id: 'mnuItemEditDMSObject',text: '{$lang['context']['orgMrg']['edit']}', iconCls: 'editIcon'}),
        	new Ext.menu.Item({id: 'mnuItemDeleteDMSObject',text: '{$lang['context']['orgMrg']['delete']}', iconCls: 'deleteIcon'}),
        	
        	new Ext.menu.Separator(),
        	
			new Ext.menu.Item({id: 'mnuItemRestoreDMSObject',text: '{$lang ['dms'] ['restore']}', iconCls: 'restoreIcon'}),
			new Ext.menu.Item({id: 'mnuItemEmptyRecyclebinDMSObject',text: '{$lang ['dms'] ['emptyRecyclebin']}', iconCls: 'emptyRecyclebinIcon'}),
			        	
        	new Ext.menu.Separator(),
        	
			new Ext.menu.Item({id: 'mnuItemExpireDMSObject',text: '" . $lang ['common'] ['input'] . $lang ['dms'] ['expireStamp'] . "', iconCls: 'expireIcon'}),
        	new Ext.menu.Item({id: 'mnuItemCheckoutDMSObject',text: '{$lang ['dms'] ['checkout']}', iconCls: 'checkoutIcon'}),
        	new Ext.menu.Item({id: 'mnuItemCheckinDMSObject',text: '{$lang ['dms'] ['checkin']}', iconCls: 'checkinIcon'}),
        	new Ext.menu.Separator(),
        	
        	new Ext.menu.Item({id:'mnuItemISOApproval',text: '{$lang['context']['orgMrg']['ISO']}', iconCls: 'checkmarkIcon'}),
        	new Ext.menu.Separator(),
        	
        	new Ext.menu.Item({id:'mnuItemRefreshDMSTree',text: '{$lang['context']['orgMrg']['refresh']}', iconCls: 'refreshIcon'}),
        	
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id: 'mnuItemDMSObjectProperty',text: '{$lang ['context'] ['orgMrg'] ['property']}', iconCls: 'propertyIcon'})
    	);
    	
		    	
		var DMSTreeLoader = new Ext.tree.TreeLoader({
        	dataUrl   : '/{$config ['appName']}/dms-explorer/load-dms-tree',
        	baseParams: {objid: -1},
			uiProviders:{
                'col': Ext.tree.ColumnNodeUI
            }
    	});
    	
    	/*var DMSTree = new Ext.tree.TreePanel({*/
    	var DMSTree = new Ext.tree.ColumnTree({
	        renderTo: 'DMSExplorerUIDiv',
	        useArrows:true,
	        autoScroll: true,
	        animate: true,
	        //frame: false,
	        //bodyBorder: false,
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        enableDD: true,
	        ddGroup: 'treeDD',
	        //containerScroll: true,
	        ///////////////////////////////////////////////////////
	        ddScroll: true,
	        rootVisible: true,
	        id: 'DMSTree',
	        ///////////////////////////////////////////////////////
	        //draggable: true,
	        //selModel: new Ext.tree.MultiSelectionModel(),
	        columns:[{
	            header:'{$lang['dmstree']['name']}',
	            width: 400,
	            dataIndex: 'name'
	        },{
	            header:'{$lang['dmstree']['createCol']}',
	            width: 100,
	            dataIndex: 'cut',
	            renderer: function(value, p, record){
					return record.createCol;
				}
	        },{
	            header:'{$lang['dmstree']['modifyCol']}',
	            width: 100,
	            dataIndex: 'cdt',
				renderer: function(value, p, record){
					return record.modifyCol;
				}
	        },{
	            header:'{$lang['dmstree']['expireCol']}',
	            width: 100,
	            dataIndex: 'mut',
	            renderer: function(value, p, record){
					return record.expireCol;
				}
	        },{
	            header:'{$lang['dmstree']['pagesCol']}',
	            width: 50,
	            dataIndex: 'cdt',
				renderer: function(value, p, record){
					return record.pagesCol;
				}
	        },{
	            header:'{$lang['dmstree']['version']}',
	            width: 50,
	            dataIndex: 'cdt',
				renderer: function(value, p, record){
					return record.versionCol;
				}
	        },{
	            header:'{$lang['dmstree']['rating']}',
	            width: 50,
	            dataIndex: 'cdt',
				renderer: function(value, p, record){
					return record.ratingCol;
				}
	        },{
	            header:'{$lang['dmstree']['type']}',
	            width: 150,
	            dataIndex: 'tt',
	            renderer: function(value, p, record){
	            	//console.log(record.tt)
					return record.tt;
				}
	        }] ,
	        root: new Ext.tree.AsyncTreeNode({
		        text: '{$lang['DMS']}',
		        draggable:false,
		        objid: 0,
		        objtype: 'DMSRoot',
		        id: 'DMSRoot',
		        iconCls: 'DMSHomeFolderIcon',
		        tt: 'DMS Root',
		        cut: '',
		        cdt: '',
		        mut: '',
		        mdt: ''
		    }),
	        loader: DMSTreeLoader
	    });
	
	    // set the root node
	    Cookies.set('tmpSelected','');
	    DMSTreeLoader.on('beforeload', function(treeLoader, node) {
			if(node.attributes.objmode == 'dms') {
				DMSTreeLoader.dataUrl = '/{$config ['appName']}/dms-explorer/load-dms-tree';
				DMSTreeLoader.baseParams.objid = node.attributes.objid;
			} else {
				if(node.attributes.objmode == 'mydoc') {
					DMSTreeLoader.dataUrl = '/{$config ['appName']}/dms-explorer/load-my-document';
					DMSTreeLoader.baseParams.objid = node.attributes.objid;
				} else if (node.attributes.objmode == 'recyclebin') {
					DMSTreeLoader.dataUrl = '/{$config ['appName']}/dms-explorer/load-dms-recycle-bin';
					DMSTreeLoader.baseParams.objid = node.attributes.objid;
				} else {
					DMSTreeLoader.dataUrl = '/{$config ['appName']}/dms-explorer/load-dms-tree';
					DMSTreeLoader.baseParams.objid = node.attributes.objid;
				}
			}
		}, this);
	    
	    // render the tree
	    DMSTree.render();
	    DMSTree.root.expand();
	    
    	DMSTree.on('contextmenu', function(node,e) {
    		//alert(node.attributes.objtype);
    		switch (node.attributes.objtype) {
    			//root
				case 'DMSRoot' :
				case 'MyDocRoot':
					//Ext.getCmp('mnuItemCreateCabinet').enable();
					//Ext.getCmp('mnuItemCreateDrawer').disable();
					Ext.getCmp('mnuItemCreateFolder').enable();
					Ext.getCmp('mnuItemCreateShortcut').disable();
					Ext.getCmp('mnuItemCreateDocument').enable();
					Ext.getCmp('mnuItemEditDMSObject').disable();
					Ext.getCmp('mnuItemDeleteDMSObject').disable();
					Ext.getCmp('mnuItemRestoreDMSObject').disable();
					Ext.getCmp('mnuItemEmptyRecyclebinDMSObject').disable();
					Ext.getCmp('mnuItemDMSObjectProperty').disable();
					Ext.getCmp('mnuItemExpireDMSObject').disable();
					Ext.getCmp('mnuItemExpireDMSObject').disable();
					Ext.getCmp('mnuItemCheckoutDMSObject').disable();
					Ext.getCmp('mnuItemCheckinDMSObject').disable();
					Ext.getCmp('mnuItemISOApproval').disable();
					
					Ext.getCmp('mnuItemSeeAlso').disable();
					
					Ext.getCmp('mnuItemBorrow').disable();
					Ext.getCmp('mnuItemPublish').disable();
					Ext.getCmp('mnuItemUnpublish').disable();
				break;
				// recyclebin
				case 'recyclebin':
					Ext.getCmp('mnuItemCreateFolder').disable();
					Ext.getCmp('mnuItemCreateDocument').disable();
					Ext.getCmp('mnuItemCreateShortcut').disable();
					Ext.getCmp('mnuItemEditDMSObject').disable();
					Ext.getCmp('mnuItemDeleteDMSObject').disable();
					Ext.getCmp('mnuItemDMSObjectProperty').disable();
					Ext.getCmp('mnuItemExpireDMSObject').disable();
					Ext.getCmp('mnuItemCheckoutDMSObject').disable();
					Ext.getCmp('mnuItemCheckinDMSObject').disable();
					Ext.getCmp('mnuItemISOApproval').disable();
					
					Ext.getCmp('mnuItemSeeAlso').disable();
					
					Ext.getCmp('mnuItemBorrow').disable();
					Ext.getCmp('mnuItemPublish').disable();
					Ext.getCmp('mnuItemUnpublish').disable();
				break;
				//Document
				case 0:
				case '0':
					//Ext.getCmp('mnuItemCreateCabinet').disable();
					//Ext.getCmp('mnuItemCreateDrawer').enable();
					Ext.getCmp('mnuItemCreateFolder').enable();
					Ext.getCmp('mnuItemCreateDocument').enable();
					Ext.getCmp('mnuItemCreateShortcut').enable();
					Ext.getCmp('mnuItemEditDMSObject').enable();
					Ext.getCmp('mnuItemDeleteDMSObject').enable();
					Ext.getCmp('mnuItemDMSObjectProperty').enable();
					Ext.getCmp('mnuItemExpireDMSObject').disable();
					Ext.getCmp('mnuItemCheckoutDMSObject').disable();
					Ext.getCmp('mnuItemCheckinDMSObject').disable();
					Ext.getCmp('mnuItemISOApproval').disable();
					
					Ext.getCmp('mnuItemSeeAlso').disable();
					
					Ext.getCmp('mnuItemBorrow').disable();
					Ext.getCmp('mnuItemPublish').disable();
					Ext.getCmp('mnuItemUnpublish').disable();
				break;
				//Document
				case 1:
				case '1':
					//Ext.getCmp('mnuItemCreateCabinet').disable();
					//Ext.getCmp('mnuItemCreateDrawer').disable();
					Ext.getCmp('mnuItemCreateFolder').disable();
					Ext.getCmp('mnuItemCreateDocument').disable();
					Ext.getCmp('mnuItemCreateShortcut').enable();
					Ext.getCmp('mnuItemEditDMSObject').disable();
					Ext.getCmp('mnuItemDeleteDMSObject').enable();
					Ext.getCmp('mnuItemDMSObjectProperty').enable();
					Ext.getCmp('mnuItemExpireDMSObject').enable();
					Ext.getCmp('mnuItemCheckoutDMSObject').enable();
					Ext.getCmp('mnuItemCheckinDMSObject').enable();
					
					Ext.getCmp('mnuItemSeeAlso').enable();
					
					Ext.getCmp('mnuItemBorrow').enable();
					if(node.attributes.publish == 0 || node.attributes.publish == '0') {
						Ext.getCmp('mnuItemPublish').enable();
						Ext.getCmp('mnuItemUnpublish').disable();
					} else {
						Ext.getCmp('mnuItemPublish').disable();
						Ext.getCmp('mnuItemUnpublish').enable();
					}
					
					// case document was checked-in
					//if (node.attributes.checkout == 0 || node.attributes.checkout == 'null') {
					if (node.attributes.checkout == 'N') {
						Ext.getCmp('mnuItemCheckoutDMSObject').enable();
						Ext.getCmp('mnuItemCheckinDMSObject').disable();
						Ext.getCmp('mnuItemISOApproval').disable();
						Ext.getCmp('mnuItemDeleteDMSObject').enable();
						Ext.getCmp('mnuItemExpireDMSObject').enable();
					} else { // case documnet was checked-out
						Ext.getCmp('mnuItemCheckoutDMSObject').disable();
						Ext.getCmp('mnuItemISOApproval').enable();
						Ext.getCmp('mnuItemDeleteDMSObject').disable();
						Ext.getCmp('mnuItemExpireDMSObject').disable();

						if (node.attributes.checkout == 'Y' && node.attributes.checkoutby == '{$_SESSION['accID']}') {
							Ext.getCmp('mnuItemCheckinDMSObject').enable();
						} else {
							Ext.getCmp('mnuItemCheckinDMSObject').disable();
						}
					}
				break;
				//Shortcut
				case 2:
				case '2':
					//Ext.getCmp('mnuItemCreateCabinet').disable();
					//Ext.getCmp('mnuItemCreateDrawer').enable();
					Ext.getCmp('mnuItemCreateFolder').disable();
					Ext.getCmp('mnuItemCreateDocument').disable();
					Ext.getCmp('mnuItemCreateShortcut').disable();
					Ext.getCmp('mnuItemEditDMSObject').enable();
					Ext.getCmp('mnuItemDeleteDMSObject').enable();
					Ext.getCmp('mnuItemDMSObjectProperty').enable();
					Ext.getCmp('mnuItemExpireDMSObject').disable();
					Ext.getCmp('mnuItemCheckoutDMSObject').disable();
					Ext.getCmp('mnuItemCheckinDMSObject').disable();
					Ext.getCmp('mnuItemISOApproval').disable();
					
					Ext.getCmp('mnuItemSeeAlso').disable();
					
					Ext.getCmp('mnuItemBorrow').disable();
					Ext.getCmp('mnuItemPublish').disable();
					Ext.getCmp('mnuItemUnpublish').disable();
				break;
			}
			
			if (node.attributes.objtype != 'DMSRoot') {
				if (node.parentNode.attributes.objtype == 'recyclebin') {
					Ext.getCmp('mnuItemRestoreDMSObject').enable();
				} else {
					Ext.getCmp('mnuItemRestoreDMSObject').disable();
				}
				
				if (node.attributes.objtype == 'recyclebin') {
					Ext.getCmp('mnuItemEmptyRecyclebinDMSObject').enable();
				} else {
					Ext.getCmp('mnuItemEmptyRecyclebinDMSObject').disable();
				}
			}
			
			// ACL
			// Folder
			if(node.attributes.createFolder == 0) {
				Ext.getCmp('mnuItemCreateFolder').disable();
			}
			if(node.attributes.modifyFolder == 0 && node.attributes.objtype == 0) {
				Ext.getCmp('mnuItemEditDMSObject').disable();
			}
			if(node.attributes.deleteFolder == 0 && node.attributes.objtype == 0) {
				Ext.getCmp('mnuItemDeleteDMSObject').disable();
			}
			// Document
			if(node.attributes.createDoc == 0) {
				Ext.getCmp('mnuItemCreateDocument').disable();
			}
			if(node.attributes.modifyDoc == 0) {
				Ext.getCmp('mnuItemEditDMSObject').disable();
			}
			if(node.attributes.deleteDoc == 0 && node.attributes.objtype == 1) {
				Ext.getCmp('mnuItemDeleteDMSObject').disable();
			}
			// Shortcut
			if(node.attributes.createShortcut == 0) {
				Ext.getCmp('mnuItemCreateShortcut').disable();
			}
			if(node.attributes.modifyShortcut == 0 && node.attributes.objtype == 2) {
				Ext.getCmp('mnuItemEditDMSObject').disable();
			}
			if(node.attributes.deleteShortcut == 0 && node.attributes.objtype == 2) {
				Ext.getCmp('mnuItemDeleteDMSObject').disable();
			}
			
//			alert('objtype:'+node.attributes.objtype);
//			alert('createFolder:'+node.attributes.createFolder);
//			alert('createDoc:'+node.attributes.createDoc);
//			alert('createShortcut:'+node.attributes.createShortcut);
			
			// 
			if(node.attributes.objtype == 'MyDocRoot') {
				//Ext.getCmp('mnuItemCreateCabinet').enable();
			}
			
			Cookies.set('contextDMSElID',node.id);
			
			//Cookies.set('contextDMSObjectID',DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objid);
			//Cookies.set('contextDMSObjectType',DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objtype);
			//��� 3 ��ǹ����繡Ѻ��� create
			Cookies.set('contextDMSObjectID',node.attributes.objid);
			Cookies.set('contextDocumentID',node.attributes.docid);
			Cookies.set('contextDMSObjectType',node.attributes.objtype);
			Cookies.set('contextDMSObjectMode',node.attributes.objmode);
			Cookies.set('contextDMSObjectName',node.attributes.text);
			
			{$disabledByDegradeModeJS}
			
			DMSTreeContext.showAt(e.getXY());
		}, DMSTree);
		/*
		Ext.getCmp('mnuItemCreateCabinet').on('click',function() {
			cabinetAddWindow.show();
			cabinetAddForm.getForm().reset();
			cabinetAddForm.getForm().setValues([
            	{id:'DMSParentType',value: Cookies.get('contextDMSObjectType')},
            	{id:'DMSParentID' ,value: Cookies.get('contextDMSObjectID')}
        	]);
		},this);
		
		Ext.getCmp('mnuItemCreateDrawer').on('click',function() {
			drawerAddWindow.show();
			drawerAddForm.getForm().reset();
			drawerAddForm.getForm().setValues([
            	{id:'drawerParentType',value: Cookies.get('contextDMSObjectType')},
            	{id:'drawerParentID' ,value: Cookies.get('contextDMSObjectID')},
				{id:'drawerObjectMode' ,value: Cookies.get('contextDMSObjectMode')}
        	]);
		},this);
		*/
		Ext.getCmp('mnuItemCreateFolder').on('click',function() {
			folderAddWindow.show();
			folderAddForm.getForm().reset();
			folderAddForm.getForm().setValues([
            	{id:'folderParentType',value: Cookies.get('contextDMSObjectType')},
            	{id:'folderParentID' ,value: Cookies.get('contextDMSObjectID')},
				{id:'folderObjectMode' ,value: Cookies.get('contextDMSObjectMode')}
        	]);
		},this);
		
		Ext.getCmp('mnuItemISOApproval').on('click',function() {
			isoRequestWindow.show();
			isoRequestForm.getForm().reset();
			/*
			folderAddForm.getForm().setValues([
            	{id:'folderParentType',value: Cookies.get('contextDMSObjectType')},
            	{id:'folderParentID' ,value: Cookies.get('contextDMSObjectID')},
				{id:'folderObjectMode' ,value: Cookies.get('contextDMSObjectMode')}
        	]);*/
		},this);
		
		Ext.getCmp('mnuItemCreateDocument').on('click',function() {
			formSelectStore.reload();
			documentAddWindow.show();
			//spot.show(documentAddWindow.getId());
			documentAddForm.getForm().reset();
			/*
			documentAddForm.getForm().setValues([
            	{id:'documentParentType',value: Cookies.get('contextDMSObjectType')},
            	{id:'documentParentID' ,value: Cookies.get('contextDMSObjectID')},
				{id:'documentObjectMode' ,value: Cookies.get('contextDMSObjectMode')}
        	]);
        	*/
        	documentAddForm.getForm().setValues([
            	{id:'documentParentType',value: DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objtype},
            	{id:'documentParentID' ,value: DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objid},
				{id:'documentObjectMode' ,value: DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objmode}
        	]);
        	Cookies.set('cdid',Cookies.get('contextDMSElID'));
		},this);
		
		Ext.getCmp('mnuItemBorrow').on('click',function() {
			wndBorrowDocument.show();
		},this);
		
		
		Ext.getCmp('mnuItemSeeAlso').on('click',function() {
			
			seeAlsoFormWindow.show();
			
			Ext.getCmp('SeeAlsoToolbar').add({
	            id: 'btnClearSeeAlso',
	            text:'Clear',
	            iconCls: 'bmenu',
	            handler: function() {
	                Ext.getCmp('btnDeleteSeeAlso').disable();
	                tempSeeAlso.removeAll();
	            }
	        },{
	            id: 'btnDeleteSeeAlso',
	            text:'Delete',
	            iconCls: 'bmenu',
	            disabled: true,
	            handler: function(e) {
	                tempSeeAlso.remove(tempSeeAlso.getById(Ext.getCmp('gridSeeAlso').getSelectionModel().getSelected().id));
	                Ext.getCmp('btnDeleteSeeAlso').disable();
	            }
	        });
	        
	        Ext.getCmp('gridSeeAlso').on('rowclick',function() {
            	Ext.getCmp('btnDeleteSeeAlso').enable();
        	},this);
			
			Cookies.set('tmpSelected',Cookies.get('contextDMSElID'));
			Ext.getCmp('seeAlsoRefID').setValue(Cookies.get('contextDMSObjectID'));
			Ext.getCmp('seeAlsoRef').setValue(getNameSelected());
			
			var seeAlsoDropZone = new Ext.dd.DropZone(Ext.getCmp('gridSeeAlso').getEl(), {
		        ddGroup: 'treeDD',
		        notifyDrop : function(dd, e, data) {
		        	
            		
            		var rec = new SeeAlsoRecordDataDef({
                        dataid: data.node.attributes.objid,
                        name: data.node.attributes.text,
                        description: data.node.attributes.tt,
                        typeid: '1'
                        
            		});
            		if(tempSeeAlso.find('dataid',data.node.attributes.objid)== -1) {
            			tempSeeAlso.add(rec);
					}
		            return true;
		        },
		        
				notifyOver: function (source,evt,data) {
					return 'x-dd-drop-ok';
				}
		    });
		},this);
		
		
		Ext.getCmp('mnuItemPublish').on('click',function() {
        	Cookies.set('tmpSelected',Cookies.get('contextDMSElID'));
			Ext.MessageBox.confirm('Confirm', '��ͧ���������͡���  '+getNameSelected()+' ������� ?',handlePublishDMS);
		},this);
		
		Ext.getCmp('mnuItemExport').on('click',function() {
        	Ext.MessageBox.show({
				title: '������͡�͡���',
				msg: '<a href=\"/{$config['appName']}/export/export11224128826.zip\">Download</a>',
				buttons: Ext.MessageBox.OK,
				icon: Ext.MessageBox.INFO
			});
		},this);
		
		
		Ext.getCmp('mnuItemUnpublish').on('click',function() {
        	Cookies.set('tmpSelected',Cookies.get('contextDMSElID'));
			Ext.MessageBox.confirm('Confirm', '��ͧ���¡��ԡ������͡���  '+getNameSelected()+' ������� ?',handleUnpublishDMS);
		},this);
		
		Ext.getCmp('mnuItemCreateShortcut').on('click',function() {
			Ext.Ajax.request({
                url: '/{$config ['appName']}/dms/create-shortcut?shortcutName='+Cookies.get('contextDMSObjectName'),
                method: 'POST',
                success: function(o){
                	
	                var r = Ext.decode(o.responseText);
	                var responseMsg = r.message;
					var result;
					
					if(r.success == 1) {
						messageSuccess(null,null);
						DMSTree.getNodeById('DMSRoot').reload();
					} else {
						messageFailed(null, responseMsg);
					}
				}
			});
		},this);
		
		Ext.getCmp('mnuItemExpireDMSObject').on('click',function(obj,e) {
			wndDocExpire.show();
			frmDocExpire.getForm().reset();
			
			Ext.Ajax.request({
                url: '/{$config ['appName']}/dms-action/request-expired-doc?objID='+ Cookies.get('contextDMSObjectID'),
                method: 'POST',
                success: function(o){
                	
	                Ext.MessageBox.hide();
	                var r = Ext.decode(o.responseText);
	                var responseMsg = '';
					var result;
					
					if(r.success == 1) {
						frmDocExpire.getForm().setValues([
							{id:'dtpDocExpire', value: r.f_expire_stamp}
			           	 ]);
						result = true;
					} else {
						frmDocExpire.getForm().setValues([
							{id:'dtpDocExpire', value: '{$lang ['common'] ['error']}'}
			           	 ]);
						result = false;
					}
				}
			});
		});
		
		Ext.getCmp('mnuItemCheckoutDMSObject').on('click',function(obj,e) {
			Ext.Ajax.request({
                url: '/{$config ['appName']}/document/checkout?docID='+ Cookies.get('contextDocumentID'),
                method: 'GET',
                success: function(o){
                	
	                Ext.MessageBox.hide();
	                var r = Ext.decode(o.responseText);
	                var responseMsg = r.message;
					var result;
					
					if(r.success == 1) {
						messageSuccess(null,null);
						DMSTree.getNodeById(Cookies.get('contextDMSElID')).parentNode.reload();
					} else {
						messageFailed(null, responseMsg);
					}
				}
			});
		});
		
		Ext.getCmp('mnuItemCheckinDMSObject').on('click',function(obj,e) {
			
			Ext.Ajax.request({
                url: '/{$config ['appName']}/document/verify-checkin?docID='+ Cookies.get('contextDocumentID'),
                method: 'GET',
                success: function(o){
                	
	                Ext.MessageBox.hide();
	                var r = Ext.decode(o.responseText);
	                var responseMsg = r.message;
					var result;
					
					if(r.success == 1) {
						Ext.Ajax.request({
			                url: '/{$config ['appName']}/document/checkin?docID='+ Cookies.get('contextDocumentID'),
			                method: 'GET',
			                success: function(o){
			                	
				                Ext.MessageBox.hide();
				                var r = Ext.decode(o.responseText);
				                var responseMsg = r.message;
								var result;
								
								if(r.success == 1) {
									messageSuccess(null,null);
									DMSTree.getNodeById(Cookies.get('contextDMSElID')).parentNode.reload();
								} else {
									messageFailed(null, responseMsg);
								}
							}
						});
//						wndCheckin.show();
					} else {
						messageFailed(null, responseMsg);
					}
				}
			});
		});
		
		Ext.getCmp('mnuItemEditDMSObject').on('click',function(obj,e) {
		
			if (DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objtype != '1') {
			
				folderEditWindow.show();
				folderEditForm.getForm().reset();
				folderEditForm.getForm().setValues([
					{id:'editFolderType',value: DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objtype},
	            	{id:'editFolderID' ,value: DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objid},
					{id:'editFolderObjectMode' ,value: DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.objmode},
					{id:'editFolderName' ,value: DMSTree.getNodeById(Cookies.get('contextDMSElID')).attributes.text}
           		 ]);
           		 
           		 Ext.Ajax.request({
           		 	url: '/{$config ['appName']}/dms-action/request-object',
           		 	params: { objID: Cookies.get('contextDMSObjectID')	},
					method: 'POST',
				    success: function(o) {
					    var r = Ext.decode(o.responseText);
					    if(r.success ==1) {
							folderEditForm.getForm().setValues([
								{ id: 'editFolderDesc', value: r.f_description },
								{ id: 'editFolderKeyword', value: r.f_keyword }
							]);
						}
					},
				    failure: function(r,o) {
					}
				 });
			}
		});
		
		Ext.getCmp('mnuItemDeleteDMSObject').on('click',function() {
			//formSelectStore.reload();
			//documentAddWindow.show();
			//documentAddForm.getForm().reset();
			//documentAddForm.getForm().setValues([
            //	{id:'documentParentType',value: Cookies.get('contextDMSObjectType')},
            //	{id:'documentParentID' ,value: Cookies.get('contextDMSObjectID')},
			//	{id:'documentObjectMode' ,value: Cookies.get('contextDMSObjectMode')}
        	//]);
        	
        	Cookies.set('contextDMSMoveToObjectID','recyclebin');
        	Cookies.set('contextDMSMoveToObjectElID','recyclebin');
        	Cookies.set('tmpSelected',Cookies.get('contextDMSElID'));
        	
			if (DMSTree.getNodeById(Cookies.get('contextDMSElID')).parentNode.attributes.objtype == 'recyclebin') {
				Cookies.set('contextDMSMoveToObjectID','');
			}
        	Ext.MessageBox.confirm('Confirm', '{$lang ['common'] ['delete']} '+getNameSelected()+' ?',handleMoveDMS);
		},this);
		
		Ext.getCmp('mnuItemRestoreDMSObject').on('click',function() {
			Cookies.set('contextDMSMoveToObjectID','restore');
			Ext.MessageBox.confirm('Confirm', '{$lang ['dms'] ['restore']} '+getNameSelected()+' ?',handleMoveDMS);		
		},this);
		
		Ext.getCmp('mnuItemEmptyRecyclebinDMSObject').on('click',function() {
			Ext.MessageBox.confirm('Confirm', '{$lang ['common'] ['delete']} '+' ?', emptyRecyclebin);
		},this);
		
		Ext.getCmp('mnuItemRefreshDMSTree').on('click',function() {
			DMSTree.getNodeById(Cookies.get('contextDMSElID')).reload();
		},this);
		
		var dmsPropertyWindow = new Ext.Window({
				id: 'dmsPropertyWindow',
				title: '{$lang ['dms'] ['property']}',
				width: 400,
				height: 530,
				layout: 'fit',
				plain:true,
				modal: true,
				//autoScroll: true, 
				bodyStyle:'padding:5px;',
				buttonAlign:'right',
				autoLoad: {url: '/{$config ['appName']}/dms-property/get-ui', scripts: true},
				closable: false,
				buttons: [{
					hidden: true,
					id: 'btnSave',
					text: '{$lang['common']['save']}',
					iconCls: 'saveIcon',
					formBind: true,
					handler: function() {
						dmsPropertyWindow.hide();
					}
				},{
					id: 'btnCancel',
					text: '{$lang['common']['cancel']}',
					iconCls: 'cancelIcon',
					formBind: true,
					handler: function() {
						dmsPropertyWindow.hide();
					}
				}]
			});
		
		Ext.getCmp('mnuItemDMSObjectProperty').on('click',function() {
			
			if(!Ext.getCmp('tbpProperty')) {
             	dmsPropertyWindow.show();
             	dmsPropertyWindow.center();
			} else {
				/*if (!Ext.getCmp('frmTabGeneral')) {
				
				}*/
				if(Ext.getCmp('frmTabGeneral')) {
					frmTabGeneral.getUpdater().update({url: '/{$config ['appName']}/dms-property/get-tab-general', scripts: true});
				}
				if(Ext.getCmp('frmTabSecurity')) {
					frmTabSecurity.getUpdater().update({url: '/{$config ['appName']}/dms-property/get-tab-security', scripts: true});
				}
				if(Ext.getCmp('frmTabVersion')) {
					frmTabVersion.getUpdater().update({url: '/{$config ['appName']}/dms-property/get-tab-version', scripts: true});
				}
				
				dmsPropertyWindow.show();
				dmsPropertyWindow.center();
			}
		},this);
		
		function getNodeSelected() {
			var vNodeSelected = Cookies.get('tmpSelected');
			
/*		    vNodeSelected = vNodeSelected.toString();		    
			vNodeSelected = vNodeSelected.replace(/\\[Node dms_/g, ''); // case multiselect nodes
			vNodeSelected = vNodeSelected.replace(/\\]/g, '');*/
			
//			vNodeSelected = vNodeSelected.replace(/\\[Node recyclebin_/g, '');
			vNodeSelected = vNodeSelected.replace(/dms_/g, '');
//			alert('after:'+vNodeSelected);
			return vNodeSelected;
		}
		
		function getNameSelected() {
			var vNameSelected = Cookies.get('tmpSelected');
//			alert('vNameSelected:'+vNameSelected);
		    vNameSelected = vNameSelected.toString();
			vNameSelected = vNameSelected.replace(/\\[Node /g, '');
			vNameSelected = vNameSelected.replace(/\\[Node recyclebin_/g, '');
			vNameSelected = vNameSelected.replace(/\\]/g, '');	 
			
			var nameArray = vNameSelected.split(\",\");
			var vNameSelected2 = '';
			var vSeparator = ', ';
			for (var i=0; i<nameArray.length; i++) {
				if (i == (nameArray.length - 1)) { // last piece of array
					vSeparator = '';
				}
//				alert('id:'+nameArray[i]);
//				alert('name:'+DMSTree.getNodeById(nameArray[i]).text);
				vNameSelected2 = vNameSelected2 + DMSTree.getNodeById(nameArray[i]).text + vSeparator;
			}
			
			return vNameSelected2;
		}
		
		function dragDropNodeRemove() {
			var nodeArray = getNodeSelected().split(\",\");
			
			if (nodeArray.length > 1) {
				for (var i=0; i<nodeArray.length; i++) {
//					alert('dms_'+nodeArray[i]);
					DMSTree.getNodeById('dms_'+nodeArray[i]).remove();
				}
			} else {
				DMSTree.getNodeById(Cookies.get('contextDMSElID')).remove();
			}		
		}
		
		function dragDropNodeReload() {
			
			// reload dropped node
			DMSTree.getNodeById(Cookies.get('contextDMSMoveToObjectElID')).reload();
		}
		
		DMSTree.on('click', function(node,e) {
	    			
			//Ext.MessageBox.show({
		    //                    msg: vNodeSelected,
		    //                    width:300
		    //            });			
			
			Cookies.set('contextDMSObjectID', node.attributes.objid);
	    	Cookies.set('contextDMSElID', node.id);
	    	Cookies.set('contextDMSObjectLID', node.attributes.objlid);
			if (node.attributes.objtype != 'DMSRoot') {
				Cookies.set('contextDMSObjectPElID', node.parentNode.id);
			}
			//alert('from objEMSid : ' + Cookies.get('contextDMSObjectID'));
			//alert('from objJSid : ' + Cookies.get('contextDMSElID'));
			//alert('from source text : ' + DMSTree.getNodeById(Cookies.get('contextDMSElID')).text);
			//TODO: Move Event to dblclick + check event + check cookie
	    	
			//var vNodeSelected = DMSTree.getSelectionModel().getSelectedNodes();
			//var vNode = vNodeSelected[0];
			//alert('tmpSelected:'+vNodeSelected);
			
//			Cookies.set('tmpSelected',DMSTree.getSelectionModel().getSelectedNodes());
			Cookies.set('tmpSelected',Cookies.get('contextDMSElID'));
		},this);
    	
		DMSTree.on('startdrag',function(tree,node,e ) {
		
			Cookies.set('contextDMSElID',node.id);
			Cookies.set('contextDMSObjectID',node.attributes.objid);
			
			var nodeArray = getNodeSelected().split(\",\");
			
			if (nodeArray.length <=1) {
				node.select();
//				Cookies.set('tmpSelected',DMSTree.getSelectionModel().getSelectedNodes());
				Cookies.set('tmpSelected',Cookies.get('contextDMSElID'));
			} else {
			
			}
		});
		
		DMSTree.on('dblclick', function(node,e) {
			if(node.attributes.objtype == 1) {
				var docName = '';
				docName = node.attributes.text;
				if (docName.length > 30) {
					docName = node.attributes.text.substring(0,30);
				}
				viewDocumentCrossModule('viewDOC_'+node.attributes.objid, docName, 'DMS', node.attributes.docid,'');
			}
			
			if(node.attributes.objtype == 2) {
				var wdExpandShortcut = new Ext.Window({
					id: 'wdExpandShortcut',
					title: 'Loading...',
					width: 200,
					height: 50,
					layout: 'fit',
					plain:true,
					modal: true,
					bodyStyle:'padding:5px;',
					autoLoad: {url: '/{$config ['appName']}/dms-property/expand-node-by-id', scripts: true},
					closable: false
				});
				
				wdExpandShortcut.show();
				wdExpandShortcut.hide();
			}
		},this);
		
		DMSTree.on('beforenodedrop', function(dropEvent){
			
			Cookies.set('contextDMSMoveToObjectID',dropEvent.target.attributes.objid);
			Cookies.set('contextDMSMoveToObjectElID',dropEvent.target.id);
			
			if (dropEvent.target.attributes.objtype != '1') {
				Ext.MessageBox.confirm('Confirm', '{$lang ['common'] ['move']} '+getNameSelected()+' => '+dropEvent.target.attributes.text+' ?',handleMoveDMS);
			}

			return false;
	    });
		
	    DMSTree.on('nodedragover',function (dropEvent) {
	    	//console.log(dropEvent.target.id);
	    	if (dropEvent.target.attributes.objtype == '1' || dropEvent.target.attributes.objtype == '2') {
	    		return false;
			}
		});
			
		function emptyRecyclebin(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	                url: '/{$config ['appName']}/dms-action/empty-recyclebin',
	                method: 'GET',
	                success: function(o){
	                	
		                Ext.MessageBox.hide();
		                var r = Ext.decode(o.responseText);
		                var responseMsg = r.message;
						var result;
						
						if(r.success == 1) {
							messageSuccess(null,null);
							DMSTree.getNodeById('recyclebin').reload();
						} else {
							messageFailed(null, responseMsg);
						}
					}
				});
			}
		}
		
		function handlePublishDMS(btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
	                        msg: 'Publishing...',
	                        progressText: 'In progress...',
	                        width:300,
	                        wait:true,
	                        waitConfig: {interval:200},
	                        con:'ext-mb-download'
	           	});
	           	
	 			Ext.Ajax.request({
	                        url: '/{$config ['appName']}/dms-action/publish-node',
	                        method: 'POST',
	                        success: function(o){
	                        	Ext.MessageBox.hide();
	                            var r = Ext.decode(o.responseText);
	                            var responseMsg = '';
								var result;
	                            if(r.success == 1) {
	                            	responseMsg = '��������º��������';
									result = true;
								} else {
									responseMsg = '�Դ��ͼԴ��Ҵ';
									result = false;
								}
								
	                            Ext.MessageBox.show({
	                                title: '���������͡���',
	                                msg: responseMsg,
	                                buttons: Ext.MessageBox.OK,
	                           		icon: Ext.MessageBox.INFO
								});
								DMSTree.getNodeById(Cookies.get('contextDMSElID')).parentNode.reload();
								Cookies.set('tmpSelected','');
	                        },
	                        params: {
	                        	objId: getNodeSelected()
							}
				});
			}
		}
		
		function handleUnpublishDMS(btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
	                        msg: 'Unpublishing...',
	                        progressText: 'In progress...',
	                        width:300,
	                        wait:true,
	                        waitConfig: {interval:200},
	                        con:'ext-mb-download'
	           	});
	           	
	 			Ext.Ajax.request({
	                        url: '/{$config ['appName']}/dms-action/unpublish-node',
	                        method: 'POST',
	                        success: function(o){
	                        	Ext.MessageBox.hide();
	                            var r = Ext.decode(o.responseText);
	                            var responseMsg = '';
								var result;
	                            if(r.success == 1) {
	                            	responseMsg = '¡��ԡ��������º��������';
									result = true;
								} else {
									responseMsg = '�Դ��ͼԴ��Ҵ';
									result = false;
								}
								
	                            Ext.MessageBox.show({
	                                title: '¡��ԡ���������͡���',
	                                msg: responseMsg,
	                                buttons: Ext.MessageBox.OK,
	                           		icon: Ext.MessageBox.INFO
								});
								DMSTree.getNodeById(Cookies.get('contextDMSElID')).parentNode.reload();
								Cookies.set('tmpSelected','');
	                        },
	                        params: {
	                        	objId: getNodeSelected()
							}
				});
			}
		}
		
		function handleMoveDMS(btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
	                        msg: 'Saving...',
	                        progressText: 'Saving...',
	                        width:300,
	                        wait:true,
	                        waitConfig: {interval:200},
	                        con:'ext-mb-download'
	                });
	                
	                Ext.Ajax.request({
	                        url: '/{$config ['appName']}/dms-action/move-node',
	                        method: 'POST',
	                        success: function(o){
	                        	Ext.MessageBox.hide();
	                            var r = Ext.decode(o.responseText);
	                            var responseMsg = '';
								var result;
	                            if(r.success == 1) {
	                            	responseMsg = '�ѹ�֡���º��������';
									result = true;
								} else {
									responseMsg = '�ѹ�֡�Դ��Ҵ';
									result = false;
								}
								
	                            Ext.MessageBox.show({
	                                title: '��úѹ�֡',
	                                msg: responseMsg,
	                                buttons: Ext.MessageBox.OK,
	                           		icon: Ext.MessageBox.INFO
								});
								
								// Remove & Reload node
								dragDropNodeRemove();
								dragDropNodeReload();
								Cookies.set('tmpSelected','');
	                        },
	                        params: {
	                        	objIdFrom: getNodeSelected(),
                        		objIdTo: Cookies.get('contextDMSMoveToObjectID')
							}
	                });
	                
					params: {
                        objIdFrom: getNodeSelected();
					}

			} else {
				return false;
			}
		}
    	</script>";
	}

	function getAllow($objID)
	{
		global $conn;
		global $sessionMgr;

		$sql = " SELECT tbl_security_property.f_obj_id
					FROM tbl_security_property INNER JOIN tbl_dms_object 
					ON (tbl_security_property.f_obj_id = tbl_dms_object.f_obj_id)
					WHERE tbl_dms_object.f_obj_id = '{$objID}' ";
		$rs = $conn->Execute($sql);
		$row = $rs->FetchRow();

		if (!$row) {
			return true;
		}

		$sql_basic = "SELECT tbl_security_property.f_obj_id
                                        FROM tbl_security_property,
                                             tbl_secure_group,
                                             tbl_secure_group_member,
											 tbl_dms_object
                                       WHERE tbl_security_property.f_obj_id = tbl_dms_object.f_obj_id
										 AND tbl_dms_object.f_obj_id = '{$objID}'
									     AND tbl_security_property.f_security_context_id = tbl_secure_group.f_secure_id
                                         AND tbl_secure_group.f_secure_id = tbl_secure_group_member.f_secure_id
                                         AND (   (    tbl_secure_group_member.f_member_type = 1
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentAccID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 )
                                              OR (    tbl_secure_group_member.f_member_type = 2
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentRoleID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 )
                                              OR (    tbl_secure_group_member.f_member_type = 3
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentOrgID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 ))";	

		$rs = $conn->Execute($sql_basic);
		$row = $rs->FetchRow();

		if ($row) {
			return true;
		} else {
			return false;
		}
	}

	function getAllAllowedObject()
	{
		$objid = $_POST['objid'];
		global $conn;
		global $sessionMgr;

		if ($objid == '0') {
		$sql_basic = "SELECT tbl_security_property.f_obj_id
                                        FROM tbl_security_property,
                                             tbl_secure_group,
                                             tbl_secure_group_member,
											 tbl_dms_object
                                       WHERE tbl_security_property.f_obj_id = tbl_dms_object.f_obj_id
											 AND tbl_dms_object.f_obj_pid = '0'
									     AND tbl_security_property.f_security_context_id = tbl_secure_group.f_secure_id
                                         AND tbl_secure_group.f_secure_id = tbl_secure_group_member.f_secure_id
                                         AND (   (    tbl_secure_group_member.f_member_type = 1
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentAccID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 )
                                              OR (    tbl_secure_group_member.f_member_type = 2
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentRoleID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 )
                                              OR (    tbl_secure_group_member.f_member_type = 3
                                                  AND tbl_secure_group_member.f_member_id = {$sessionMgr->getCurrentOrgID()}
												  AND tbl_secure_group_member.f_allow = 1
                                                 ))";
		} else {
			$sql_basic = "SELECT f_obj_id
								FROM tbl_dms_object
								WHERE tbl_dms_object.f_obj_pid = '{$objid}' ";
		}
		$rs = $conn->Execute($sql_basic);

		$basic_list = array();
		while ($row = $rs->FetchRow())
			$basic_list[] = $row;
		$basic_ids = $this->extractArrayValue($basic_list, 'F_OBJ_ID');
		
		$allowed_ids = array();
		foreach ($basic_list as $row)
		{
			if ($objid != '0') {
			$allowedID = $this->getAllow($row['F_OBJ_ID']);			
			if ($allowedID == 'TRUE') {
				$allowed_ids[] = $row['F_OBJ_ID'];
			} 
			} else {
			$allowed_ids[] = $row['F_OBJ_ID'];
		}

		}
		return $allowed_ids;
	}

	function splitSelect($sql, $ids, $chunksize = 900)
	{	
		global $conn;
		$result = array();
		if ( !is_array($ids) || count($ids) < 1 ) return $result;
		if ( count($ids) < $chunksize ) $chunksize = count($ids);
		do
		{
			$subids = array_slice( $ids, 0, $chunksize );
			$ids = array_diff( $ids, $subids );
			$result[] = sprintf( $sql, implode( ',', $subids ) );
		} while( count( $ids ) > 0 );
		return (count($result) < 1)? false : 'SELECT %s FROM ('.implode(') UNION ALL (',$result).')';
	}

	function getChildByParent($all_ids, $listsize = 900)
	{
		global $conn;
		global $sessionMgr;
		$result = array();
		if (!is_array($all_ids) || count($all_ids) < 1 ) return $result;
		if ( count($all_ids) < $listsize )
		{
				$listsize = count($all_ids);
		}

		do
		{
			$ids = array_slice($all_ids,0,$listsize);
			$all_ids = array_diff($all_ids,$ids);
			$sql = 'SELECT tbl_dms_object.f_obj_id
					FROM tbl_security_property, tbl_secure_group, tbl_secure_group_member, tbl_dms_object
					WHERE tbl_security_property.f_obj_id = tbl_dms_object.f_obj_id
					AND tbl_dms_object.f_obj_pid in (' . implode(',', $ids) . ')
					AND tbl_security_property.f_security_context_id = tbl_secure_group.f_secure_id
					AND tbl_secure_group.f_secure_id = tbl_secure_group_member.f_secure_id
					AND (   (    tbl_secure_group_member.f_member_type = 1
						AND tbl_secure_group_member.f_member_id = (' . $sessionMgr->getCurrentAccID() . ')
						AND tbl_secure_group_member.f_allow = 1
					)
					OR (    tbl_secure_group_member.f_member_type = 2
						AND tbl_secure_group_member.f_member_id = (' . $sessionMgr->getCurrentRoleID() . ')
						AND tbl_secure_group_member.f_allow = 1
					   )
					OR (    tbl_secure_group_member.f_member_type = 3
						AND tbl_secure_group_member.f_member_id = (' . $sessionMgr->getCurrentOrgID() . ')
						AND tbl_secure_group_member.f_allow = 1
					   )
				   )';

			$rs = $conn->Execute($sql);
			while ($row = $rs->FetchRow())
				$result[] = $row;
			
			/*$sql = 'SELECT distinct tbl_dms_object.f_obj_id
					  FROM tbl_dms_object,tbl_security_property
                      WHERE tbl_dms_object.f_obj_id <> tbl_security_property.f_obj_id AND tbl_dms_object.f_obj_pid IN (' . implode(',', $ids) . ')';*/

			$sql = 'SELECT DISTINCT tbl_dms_object.f_obj_id 
					FROM tbl_dms_object
						WHERE NOT EXISTS (SELECT tbl_security_property.f_obj_id FROM tbl_security_property WHERE tbl_dms_object.f_obj_id = tbl_security_property.f_obj_id)
						AND tbl_dms_object.f_obj_pid IN (' . implode(',', $ids) . ')';
					  
			$rs = $conn->Execute($sql);
			while ($row = $rs->FetchRow())
				$result[] = $row;
		} while(count($all_ids) > 0);
		return $result;
	}

	function extractArrayValue($list, $key)
	{
		$result = array();
		foreach ($list as $row)
			$result[] = $row[$key];
		return $result;
	}
	
	/**
	 * action /load-dms-tree/ �ӡ���觢������ç���ҧ�ͧ tree
	 *
	 */
	public function loadDmsTreeAction() {
		global $conn;
		global $util;
		global $sessionMgr;

		$objid = $_POST ['objid'];
		$nodeName = $_POST ['node'];
		
		$dmsUtil = new DMSUtil ( );

		if ($objid == - 1) {
			$objid = 0;
		}

		$allowed_ids = $this->getAllAllowedObject();
		if ( $allowed_ids ) {
			$allowids = $this->splitSelect( 'SELECT f_obj_id FROM tbl_dms_object WHERE f_obj_id in (%s)', $allowed_ids );
			$where = sprintf($allowids, 'f_obj_id');
		} else {
			$where = '0';
		}
		#logger::debug('where : '.$where);
		$sql = 'SELECT a.*, b.f_checkout_flag, b.f_checkout_user
				  FROM tbl_dms_object a LEFT OUTER JOIN tbl_doc_main b ON a.f_doc_id = b.f_doc_id
                 WHERE a.f_obj_pid = '.$objid.' 
					  AND a.f_obj_id in ('.$where.')
					  AND  a.f_mark_delete = 0
				   ORDER BY a.f_obj_type asc, a.f_name asc';
		logger::debug($sql);

		$rsGetOrg = $conn->Execute ( $sql );
		$node = Array ();
		if ($nodeName == 'DMSRoot') {
			// MyDocument Node
			$nodeTemp = Array ();
			$nodeTemp ['id'] = 'MyDocRoot';
			$nodeTemp ['objid'] = 'MyDocRoot';
			$nodeTemp ['objtype'] = 'MyDocRoot';
			$nodeTemp ['objmode'] = 'mydoc';
			$nodeTemp ['text'] = 'My Documents';
			$nodeTemp ['isroot'] = 0;
			//$nodeTemp ['leaf'] = 'false';
			$nodeTemp ['type'] = 'MyDocRoot';
			$nodeTemp ['cls'] = 'master-task';
			$nodeTemp ['iconCls'] = 'myDocumentIcon';
			$nodeTemp ['uiProvider'] = 'col';
			$nodeTemp ['createCol'] = 'System Index';
			$nodeTemp ['modifyCol'] = 'System Index';
			$nodeTemp ['expireCol'] = 'Never';
			$nodeTemp ['pagesCol'] = '-';
			$nodeTemp ['versionCol'] = '-';
			$nodeTemp ['ratingCol'] = '';
			$nodeTemp ['tt'] = 'My Document';
			$nodeTemp ['publish'] = 0;
			$node [] = $nodeTemp;
			
			// RecycleBin Node
			$nodeTemp = Array ();
			$nodeTemp ['id'] = 'recyclebin';
			$nodeTemp ['objid'] = 'recyclebin';
			$nodeTemp ['objtype'] = 'recyclebin';
			$nodeTemp ['objmode'] = 'recyclebin';
			$nodeTemp ['text'] = 'Recycle Bin';
			$nodeTemp ['isroot'] = 0;
			//$nodeTemp ['leaf'] = 'false';
			$nodeTemp ['type'] = 'MyDocRoot';
			$nodeTemp ['cls'] = 'master-task';
			$nodeTemp ['iconCls'] = 'trashIcon';
			$nodeTemp ['uiProvider'] = 'col';
			$nodeTemp ['createCol'] = 'System Index';
			$nodeTemp ['modifyCol'] = 'System Index';
			$nodeTemp ['expireCol'] = 'Never';
			$nodeTemp ['pagesCol'] = '-';
			$nodeTemp ['versionCol'] = '-';
			$nodeTemp ['ratingCol'] = '';
			$nodeTemp ['tt'] = 'Recycle Bin';
			$nodeTemp ['publish'] = 0;
			$node [] = $nodeTemp;
		}
		
		//var_dump($nodeTemp);
		foreach ( $rsGetOrg as $row ) {
			checkKeyCase ( $row );
			$dmsACL = $dmsUtil->acl ( $row ['f_obj_id'] );
			//Logger::debug($dmsACL);
			if ($dmsACL) { // security checking
				

				$nodeTemp = Array ();
				$nodeTemp ['id'] = 'dms_' . $row ['f_obj_id'];
				$nodeTemp ['objid'] = $row ['f_obj_id'];
				$nodeTemp ['objpid'] = $row ['f_obj_pid'];
				$nodeTemp ['objlid'] = $row ['f_obj_lid'];
				$nodeTemp ['objtype'] = $row ['f_obj_type'];
				$nodeTemp ['text'] = UTFEncode ( $row ['f_name'] );
				$nodeTemp ['objmode'] = 'dms';
				$nodeTemp ['checkout'] = ($row ['f_checkout_flag'] == 1) ? 'Y' : 'N';
				$nodeTemp ['checkoutby'] = $row ['f_checkout_user'];
				$nodeTemp ['docid'] = $row ['f_doc_id'];
				
				// ACL List
				$nodeTemp ['createFolder'] = $dmsACL ['F_DMS_CREATE_FOLDER'];
				$nodeTemp ['modifyFolder'] = $dmsACL ['F_DMS_MODIFY_FOLDER'];
				$nodeTemp ['deleteFolder'] = $dmsACL ['F_DMS_DELETE_FOLDER'];
				
				$nodeTemp ['createDoc'] = $dmsACL ['F_DMS_CREATE_DOC'];
				$nodeTemp ['modifyDoc'] = $dmsACL ['F_DMS_MODIFY_DOC'];
				$nodeTemp ['deleteDoc'] = $dmsACL ['F_DMS_DELETE_DOC'];
				
				$nodeTemp ['createShortcut'] = $dmsACL ['F_DMS_CREATE_SHORTCUT'];
				$nodeTemp ['modifyShortcut'] = $dmsACL ['F_DMS_MODIFY_SHORTCUT'];
				$nodeTemp ['deleteShortcut'] = $dmsACL ['F_DMS_DELETE_SHORTCUT'];
				
				$nodeTemp ['move'] = $dmsACL ['F_DMS_MOVE'];
				// acl
				

				if (array_key_exists ( 'f_org_pid', $row )) {
					if ($row ['f_org_pid'] == - 1) {
						$nodeTemp ['isroot'] = 1;
					} else {
						$nodeTemp ['isroot'] = 0;
					}
				} else {
					$nodeTemp ['isroot'] = 1;
				}
				if ($row ['f_obj_type'] == 1) {
					$nodeTemp ['docid'] = $row ['f_doc_id'];
				} else {
					$nodeTemp ['docid'] = 0;
				}
				//$nodeTemp ['leaf'] = 'false';
				$nodeTemp ['type'] = $row ['f_obj_type'];
				$nodeTemp ['cls'] = 'master-task';
				//$nodeTemp ['leaf'] = 'false';
				switch ($row ['f_obj_type']) {
					case 0 :
						$nodeTemp ['iconCls'] = 'folderIcon'; //'cabinetIcon';
						break;
					case 1 :
						$nodeTemp ['leaf'] = 'true';
						$nodeTemp ['iconCls'] = ($row ['f_checkout_flag'] == 0) ? 'documentIcon' : 'docCheckoutIcon';
						break;
					case 2 :
						$nodeTemp ['leaf'] = 'true';
						$nodeTemp ['iconCls'] = 'shortcutIcon';
						break;
				}
				$nodeTemp ['uiProvider'] = 'col';
				$nodeTemp ['createCol'] = UTFEncode ( $util->getDateString ( $row ['f_created_stamp'], 1, true ) );
				
				if ($row ['f_last_update_stamp'] == 0) {
					$nodeTemp ['modifyCol'] = '-';
				} else {
					$nodeTemp ['modifyCol'] = UTFEncode ( $util->getDateString ( $row ['f_last_update_stamp'], 1, true ) );
				}
				if ($row ['f_expire_stamp'] == 0) {
					$nodeTemp ['expireCol'] = 'Never';
				} else {
					$nodeTemp ['expireCol'] = UTFEncode ( $util->getDateString ( $row ['f_expire_stamp'], 1, true ) );
				}
				
				if ($row ['f_obj_type'] == 1) {
					$nodeTemp ['pagesCol'] = $dmsUtil->getPageCount ( $row ['f_doc_id'] );
					$nodeTemp ['versionCol'] = "1.0.0";//$dmsUtil->getVersionNumber ( $row ['f_doc_id'] );
					$nodeTemp ['ratingCol'] = '';
				} else {
					$nodeTemp ['pagesCol'] = '-';
					$nodeTemp ['versionCol'] = '-';
					$nodeTemp ['ratingCol'] = '';
				}
				if ($row ['f_published'] != 1) {
					$nodeTemp ['publish'] = 0;
				} else {
					$nodeTemp ['iconCls'] = 'publishedIcon'; //'cabinetIcon';
					$nodeTemp ['publish'] = 1;
				}
				if ($row ['f_obj_type'] == 0) {
					$nodeTemp ['tt'] = UTFEncode ( '���' );
				} else {
					$nodeTemp ['tt'] = UTFEncode ( '�͡���' );
				}
				
				$node [] = $nodeTemp;
			}
		}
		//logger::debug($node);
		echo json_encode ( $node );
	}
	
	/**
	 * action /load-my-document/ �ӡ���觢����Ţͧ My Document
	 *
	 */
	public function loadMyDocumentAction() {
		global $conn;
		global $util;
		
		$dmsUtil = new DMSUtil ( );
		$objid = $_POST ['objid'];
		//$nodeName = $_POST ['node'];
		

		if ($objid == 'MyDocRoot') {
			$objid = 0;
		}
		
		$sql = "select * from tbl_dms_object 
				where f_owner_id = '{$_SESSION['accID']}' and f_in_mydoc = 1
				and f_obj_pid ='{$objid}'
				order by f_obj_type asc,f_name asc";
		
		//		Logger::dump('sql',$sql);
		

		//die($sql);
		$rsGetOrg = $conn->Execute ( $sql );
		$node = Array ();
		
		foreach ( $rsGetOrg as $row ) {
			checkKeyCase ( $row );
			$nodeTemp = Array ();
			$nodeTemp ['id'] = 'dms_' . $row ['f_obj_id'];
			$nodeTemp ['objid'] = $row ['f_obj_id'];
			$nodeTemp ['objlid'] = $row ['f_obj_lid'];
			$nodeTemp ['objtype'] = $row ['f_obj_type'];
			$nodeTemp ['text'] = UTFEncode ( $row ['f_name'] );
			$nodeTemp ['objmode'] = 'mydoc';
			$nodeTemp ['checkout'] = ($row ['f_checkout_flag'] == 1) ? 'Y' : 'N';
			$nodeTemp ['docid'] = $row ['f_doc_id'];
			if ($row ['f_myobj_pid'] == - 1) {
				$nodeTemp ['isroot'] = 1;
			} else {
				$nodeTemp ['isroot'] = 0;
			}
			if ($row ['f_obj_type'] == 1) {
				$nodeTemp ['docid'] = $row ['f_doc_id'];
			} else {
				$nodeTemp ['docid'] = 0;
			}
			//$nodeTemp ['leaf'] = 'false';
			$nodeTemp ['type'] = $row ['f_obj_type'];
			$nodeTemp ['cls'] = 'master-task';
			//$nodeTemp ['leaf'] = 'false';
			switch ($row ['f_obj_type']) {
				case 0 :
					$nodeTemp ['iconCls'] = 'folderIcon';
					break;
				case 1 :
					$nodeTemp ['leaf'] = 'true';
					$nodeTemp ['iconCls'] = 'documentIcon';
					break;
				case 2 :
					$nodeTemp ['leaf'] = 'true';
					$nodeTemp ['iconCls'] = 'shortcutIcon';
					break;
			}
			$nodeTemp ['uiProvider'] = 'col';
			
			/*if (! is_null ( $row ['f_created_uid'] )) {
					$account = new AccountEntity ( );
					$account->Load ( "f_acc_id = '{$row['f_created_uid']}'" );
					$createUser = $account->f_name . ' ' . $account->f_last_name;
					unset ( $account );
				} else {
					$createUser = "ECM";
				}*/
			//$nodeTemp ['createCol'] = UTFEncode ( $createUser ) . ',' . UTFEncode ( $util->getDateString ( $row ['f_created_stamp'], 1, true ) );
			$nodeTemp ['createCol'] = UTFEncode ( $util->getDateString ( $row ['f_created_stamp'], 1, true ) );
			
			if ($row ['f_last_update_stamp'] == 0) {
				$nodeTemp ['modifyCol'] = '-';
			} else {
				
				/*if (! is_null ( $row ['f_last_update_uid'] )) {
						$modAccount = new AccountEntity ( );
						$modAccount->Load ( "f_acc_id = '{$row['f_last_update_uid']}'" );
						$modifyUser = $modAccount->f_name . ' ' . $modAccount->f_last_name;
					} else {
						$modifyUser = "ECM";
					}*/
				//$nodeTemp ['modifyCol'] = UTFEncode ( $modifyUser ) . ',' . UTFEncode ( $util->getDateString ( $row ['f_last_update_stamp'], 1, true ) );
				$nodeTemp ['modifyCol'] = UTFEncode ( $util->getDateString ( $row ['f_last_update_stamp'], 1, true ) );
			}
			if ($row ['f_expire_stamp'] == 0) {
				$nodeTemp ['expireCol'] = 'Never';
			} else {
				$nodeTemp ['expireCol'] = UTFEncode ( $util->getDateString ( $row ['f_expire_stamp'], 1, true ) );
			}
			
			if ($row ['f_obj_type'] == 1) {
				$nodeTemp ['pagesCol'] = $dmsUtil->getPageCount ( $row ['f_doc_id'] );
				$nodeTemp ['versionCol'] = $dmsUtil->getVersionNumber ( $row ['f_doc_id'] );
				$nodeTemp ['ratingCol'] = '';
			} else {
				$nodeTemp ['pagesCol'] = '-';
				$nodeTemp ['versionCol'] = '-';
				$nodeTemp ['ratingCol'] = '';
			}
			
			if ($row ['f_obj_type'] == 0) {
				$nodeTemp ['tt'] = UTFEncode ( '���' );
			} else {
				$nodeTemp ['tt'] = UTFEncode ( '�͡���' );
			}
			
			if ($row ['f_published'] != 1) {
				$nodeTemp ['publish'] = 0;
			} else {
				$nodeTemp ['iconCls'] = 'publishedIcon'; //'cabinetIcon';
				$nodeTemp ['publish'] = 1;
			}
			
			$node [] = $nodeTemp;
		}
		
		echo json_encode ( $node );
	}
	
	/**
	 * action /load-dms-recycle-bin/ �ӡ���觢����� Recyclebin
	 *
	 */
	public function loadDmsRecycleBinAction() {
		global $conn;
		global $util;
		
		$dmsUtil = new DMSUtil ( );
		$objid = $_POST ['objid'];
		//$nodeName = $_POST ['node'];
		

		if ($objid == 'recyclebin') {
			$sql = "select * 
						from tbl_dms_object 
						where f_mark_delete = 1 and f_mark_delete_uid = {$_SESSION['accID']}
						order by f_obj_type asc,f_name asc";
		} /* else {
			$sql = "select * 
						from tbl_dms_object 
						where f_pid = '{$objid}' 
						order by f_obj_type asc,f_name asc";
		}*/
		//die($sql);//and f_mark_delete_id = '{$_SESSION['accID']}' 
		Logger::debug ( $sql );
		
		$rsGetOrg = $conn->Execute ( $sql );
		$node = Array ();
		
		foreach ( $rsGetOrg as $row ) {
			checkKeyCase ( $row );
			$nodeTemp = Array ();
			$nodeTemp ['id'] = 'dms_' . $row ['f_obj_id'];
			$nodeTemp ['objid'] = $row ['f_obj_id'];
			$nodeTemp ['objtype'] = $row ['f_obj_type'];
			$nodeTemp ['text'] = UTFEncode ( $row ['f_name'] );
			$nodeTemp ['objmode'] = 'recyclebin';
			$nodeTemp ['deleteDoc'] = 1;
			$nodeTemp ['checkout'] = 'N';
			
			if ($row ['f_obj_pid'] == - 1) {
				$nodeTemp ['isroot'] = 1;
			} else {
				$nodeTemp ['isroot'] = 0;
			}
			//$nodeTemp ['leaf'] = 'false';
			$nodeTemp ['type'] = $row ['f_obj_type'];
			$nodeTemp ['cls'] = 'master-task';
			//$nodeTemp ['leaf'] = 'false';
			switch ($row ['f_obj_type']) {
				case 0 :
					$nodeTemp ['iconCls'] = 'folderIcon';
					break;
				case 1 :
					$nodeTemp ['iconCls'] = 'documentIcon';
					break;
				case 2 :
					$nodeTemp ['iconCls'] = 'shortcutIcon';
					break;
			}
			if ($row ['f_obj_type'] == 1) {
				$nodeTemp ['docid'] = $row ['f_doc_id'];
			} else {
				$nodeTemp ['docid'] = 0;
			}
			$nodeTemp ['uiProvider'] = 'col';
			
			/*if (! is_null ( $row ['f_created_uid'] )) {
					$account = new AccountEntity ( );
					$account->Load ( "f_acc_id = '{$row['f_created_uid']}'" );
					$createUser = $account->f_name . ' ' . $account->f_last_name;
					unset ( $account );
				} else {
					$createUser = "ECM";
				}*/
			//$nodeTemp ['createCol'] = UTFEncode ( $createUser ) . ',' . UTFEncode ( $util->getDateString ( $row ['f_created_stamp'], 1, true ) );
			$nodeTemp ['createCol'] = UTFEncode ( $util->getDateString ( $row ['f_created_stamp'], 1, true ) );
			
			if ($row ['f_last_update_stamp'] == 0) {
				$nodeTemp ['modifyCol'] = '-';
			} else {
				
				/*if (! is_null ( $row ['f_last_update_uid'] )) {
						$modAccount = new AccountEntity ( );
						$modAccount->Load ( "f_acc_id = '{$row['f_last_update_uid']}'" );
						$modifyUser = $modAccount->f_name . ' ' . $modAccount->f_last_name;
					} else {
						$modifyUser = "ECM";
					}*/
				//$nodeTemp ['modifyCol'] = UTFEncode ( $modifyUser ) . ',' . UTFEncode ( $util->getDateString ( $row ['f_last_update_stamp'], 1, true ) );
				$nodeTemp ['modifyCol'] = UTFEncode ( $util->getDateString ( $row ['f_last_update_stamp'], 1, true ) );
			}
			if ($row ['f_expire_stamp'] == 0) {
				$nodeTemp ['expireCol'] = 'Never';
			} else {
				$nodeTemp ['expireCol'] = UTFEncode ( $util->getDateString ( $row ['f_expire_stamp'], 1, true ) );
			}
			
			if ($row ['f_obj_type'] == 1) {
				$nodeTemp ['pagesCol'] = $dmsUtil->getPageCount ( $row ['f_doc_id'] );
				$nodeTemp ['versionCol'] = $dmsUtil->getVersionNumber ( $row ['f_doc_id'] );
				$nodeTemp ['ratingCol'] = '';
			} else {
				$nodeTemp ['pagesCol'] = '-';
				$nodeTemp ['versionCol'] = '-';
				$nodeTemp ['ratingCol'] = '';
			}
			
			if ($row ['f_obj_type'] == 0) {
				$nodeTemp ['tt'] = UTFEncode ( '���' );
			} else {
				$nodeTemp ['tt'] = UTFEncode ( '�͡���' );
			}
			
			if ($row ['f_published'] != 1) {
				$nodeTemp ['publish'] = 0;
			} else {
				$nodeTemp ['iconCls'] = 'publishedIcon'; //'cabinetIcon';
				$nodeTemp ['publish'] = 1;
			}
			$node [] = $nodeTemp;
		}
		
		echo json_encode ( $node );
	}
}