<?php
/**
 * โปรแกรมจัดการโครงสร้างหน่วยงาน
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
class OrganizeManagerController extends Zend_Controller_Action {
	/**
	 * สร้างฟอร์สำหรับสร้างหน่วยงาน
	 *
	 * @return string
	 */
	public function getAddOUForm() {
		global $lang;
		global $config;
		$js = "var ouAddForm = new Ext.form.FormPanel({
			id: 'ouAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'parentOrgID',
				id: 'parentOrgID',
				allowBlank: false,
				name: 'parentOrgID',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Parent Name',
				id: 'parentOrgName',
				allowBlank: false,
				name: 'parentOrgName',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Name',
				allowBlank: false,
				name: 'orgName',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Ext.Doc.Code.',
				allowBlank: false,
				name: 'extDocCode',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Int.Doc.Code.',
				allowBlank: false,
				name: 'intDocCode',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'orgDesc',
				fieldLabel: 'Description',
				allowBlank: true,
				name: 'orgDesc',
				xtype: 'textarea',
				width: '200',
				height: '100'
			},new Ext.form.LocalComboBox({
				id: 'orgType',
				name: 'orgType',
				allowBlank: false,
				fieldLabel: 'Type',
				store: organizeTypeStore,
				displayField:'name',
				valueField: 'value',
				typeAhead: false,
				triggerAction: 'all',
				value: 0,
				emptyText:'Organize Type',
				selectOnFocus:true
			})]
		});";
		
		$js .= "var ouAddWindow = new Ext.Window({
			id: 'ouAddWindow',
			title: 'Create New Organization Unit/Group',
			width: 500,
			height: 325,
			modal: true,
			minWidth: 300,
			minHeight: 325,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: ouAddForm,
			closable: false,
			buttons: [{
				id: 'btnSaveOU',
				iconCls: 'saveIcon',
				text: 'Save Organization Unit',
				handler: function() {
					
	    			ouAddWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/organize-manager/add-ou',
		    			method: 'POST',
		    			success: orgAddSuccess,
		    			failure: orgAddFailed,
		    			form: Ext.getCmp('ouAddForm').getForm().getEl()
		    		});
			       	
			     
	    		}
			},{
				id: 'btnCancelSaveOU',
				text: 'Cancel',
				iconCls: 'cancelIcon',
				handler: function() {
					ouAddWindow.hide();
				}
			}]
		});
		
		
		
		";
		
		$js .= "function orgAddSuccess() {
			Ext.MessageBox.hide();
			
			orgTree.getNodeById(Cookies.get('contextElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang ['org'] ['addOrgSuccess']}!',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function orgAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang['common']['error']}',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function deleteOUSuccess() {
			Ext.MessageBox.hide();
			orgTree.getNodeById(Cookies.get('contextElID')).parentNode.reload();
			
			Ext.MessageBox.show({
		    	title: 'Organization Structure Manager',
		    	msg: '{$lang ['org'] ['deleteOrgSuccess']}',
		    	buttons: Ext.MessageBox.OK,
		    	//animEl: Ext.getCmp('btnSaveAccount').getEl(),
		    	icon: Ext.MessageBox.INFO
		    });
		}
		
		function deleteOUFailed() {
			Ext.MessageBox.hide();
				
			Ext.MessageBox.show({
		   		title: 'Organization Structure Manager',
		   		msg: '{$lang['common']['error']}',
		   		buttons: Ext.MessageBox.OK,
		   		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
		   		icon: Ext.MessageBox.ERROR
		   	});
		}";
		
		$js .= "function deleteRoleSuccess() {
			Ext.MessageBox.hide();
			orgTree.getNodeById(Cookies.get('contextElID')).parentNode.reload();
			
			Ext.MessageBox.show({
		    	title: 'Organization Structure Manager',
		    	msg: '{$lang ['org'] ['deleteRoleSuccess']}',
		    	buttons: Ext.MessageBox.OK,
		    	//animEl: Ext.getCmp('btnSaveAccount').getEl(),
		    	icon: Ext.MessageBox.INFO
		    });
		}
		
		function deleteRoleFailed() {
			Ext.MessageBox.hide();
				
			Ext.MessageBox.show({
		   		title: 'Organization Structure Manager',
		   		msg: '{$lang['common']['error']}',
		   		buttons: Ext.MessageBox.OK,
		   		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
		   		icon: Ext.MessageBox.ERROR
		   	});
		}";
		
		$js .= "function deleteMappingSuccess() {
			Ext.MessageBox.hide();
			orgTree.getNodeById(Cookies.get('contextElID')).parentNode.reload();
			
			Ext.MessageBox.show({
		    	title: 'Organization Structure Manager',
		    	msg: '{$lang ['org'] ['deleteMappingSuccess']}',
		    	buttons: Ext.MessageBox.OK,
		    	//animEl: Ext.getCmp('btnSaveAccount').getEl(),
		    	icon: Ext.MessageBox.INFO
		    });
		}
		
		function deleteMappingFailed() {
			Ext.MessageBox.hide();
				
			Ext.MessageBox.show({
		   		title: 'Organization Structure Manager',
		   		msg: '{$lang['common']['error']}',
		   		buttons: Ext.MessageBox.OK,
		   		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
		   		icon: Ext.MessageBox.ERROR
		   	});
		}
		
		";
		
		return $js;
	}
	
	/**
	 * สร้างฟอร์มสำหรับแก้ไขหน่วยงาน
	 *
	 * @return string
	 */
	public function getEditOUForm() {
		global $lang;
		global $config;
		$js = "var ouEditForm = new Ext.form.FormPanel({
			id: 'ouEditForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'editOrgID',
				id: 'editOrgID',
				allowBlank: false,
				name: 'editOrgID',
				inputType: 'hidden',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Name',
				allowBlank: false,
				name: 'editOrgName',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Ext.Doc.Code.',
				allowBlank: false,
				name: 'editExtDocCode',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Int.Doc.Code.',
				allowBlank: false,
				name: 'editIntDocCode',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'editOrgDesc',
				fieldLabel: 'Description',
				allowBlank: true,
				name: 'editOrgDesc',
				xtype: 'textarea',
				width: '200',
				height: '100'
			},{
				id: 'editOrgTypeHidden',
				allowBlank: true,
				name: 'editOrgTypeHidden',
				xtype: 'hidden',
				width: '100%',
				height: '100',
				anchor:'100%'  // anchor width by percentage
			},new Ext.form.LocalComboBox({
				id: 'editOrgType',
				name: 'editOrgType',
				allowBlank: false,
				fieldLabel: 'Type',
				store: organizeTypeStore,
				displayField:'name',
				valueField: 'value',
				typeAhead: false,
				triggerAction: 'all',
				value: 0,
				emptyText:'Organize Type',
				selectOnFocus:true
			}),{
                id: 'orgEditFlagAllowIntDocNo',
                fieldLabel: 'Allow Int. Doc No',
                allowBlank: true,
                name: 'orgEditFlagAllowIntDocNo',
                xtype: 'checkbox'
            }]
		});";
		
		$js .= "var ouEditWindow = new Ext.Window({
			id: 'ouEditWindow',
			title: 'Edit Organization Unit/Group',
			width: 500,
			height: 300,
			modal: true,
			minWidth: 300,
			minHeight: 300,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: ouEditForm,
			closable: false,
			buttons: [{
				id: 'btnEditOU',
				iconCls: 'saveIcon',
				text: 'Modify Organization Unit',
				handler: function() {
					
	    			ouEditWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: 'Saving your data, please wait...',
			           	progressText: 'Saving...',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	
			       	ouEditForm.getForm().setValues([
	            		{id:'editOrgTypeHidden',value: Ext.getCmp('editOrgType').getValue()}
	            	]);
	            	
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/organize-manager/modify-ou',
		    			method: 'POST',
		    			success: orgEditSuccess,
		    			failure: orgEditFailed,
		    			form: Ext.getCmp('ouEditForm').getForm().getEl()
		    		});
			       	
			     
	    		}
			},{
				id: 'btnCancelEditOU',
				text: 'Cancel',
				iconCls: 'cancelIcon',
				handler: function() {
					ouEditWindow.hide();
				}
			}]
		});
		
		
		
		";
		
		$js .= "function orgEditSuccess() {
			Ext.MessageBox.hide();
			if(orgTree.getNodeById(Cookies.get('contextElID')).attributes.isroot == 1) {
				//orgTree.getRootNode().reload();
			} else {
				orgTree.getNodeById(Cookies.get('contextElID')).parentNode.reload();
			}
			
			
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang ['org'] ['editOrgSuccess']}',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function orgEditFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang['common']['error']}',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * สร้างหน้าจอสำหรับการสร้างตำแหน่งงาน
	 *
	 * @return string
	 */
	public function getAddRoleForm() {
		global $lang;
		global $config;
		$js = "var roleAddForm = new Ext.form.FormPanel({
			id: 'roleAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'roleOrgID',
				id: 'roleOrgID',
				allowBlank: false,
				name: 'roleOrgID',
				inputType: 'hidden'
			},{
				fieldLabel: 'rolePosID',
				id: 'rolePosID',
				allowBlank: false,
				name: 'rolePosID',
				inputType: 'hidden'
			},{
				fieldLabel: 'rolePolicyID',
				id: 'rolePolicyID',
				allowBlank: false,
				name: 'rolePolicyID',
				inputType: 'hidden'
			},{
				fieldLabel: 'Parent Name',
				id: 'roleOrgName',
				allowBlank: false,
				name: 'roleOrgName',
				width: 200
			},{
				fieldLabel: 'Name',
				allowBlank: false,
				id: 'roleName',
				name: 'roleName',
				xtype: 'textfield',
				width: 200
			},{
				id: 'roleDesc',
				fieldLabel: 'Description',
				allowBlank: true,
				name: 'roleDesc',
				xtype: 'textarea',
				width: '200',
				height: '100'
			},new Ext.form.ComboBox({
				id: 'rolePosition',
				name: 'rolePosition',
				allowBlank: false,
				fieldLabel: 'Position',
				store: positionForRoleStore,
				displayField:'name',
				valueField: 'id',
				typeAhead: false,
				triggerAction: 'all',
				emptyText:'Select Position',
				width: '200',
				selectOnFocus:true
			}),new Ext.form.ComboBox({
				id: 'rolePolicy',
				name: 'rolePolicy',
				allowBlank: false,
				fieldLabel: 'Policy',
				store: policyForRoleStore,
				displayField:'name',
				valueField: 'id',
				typeAhead: false,
				triggerAction: 'all',
				emptyText:'Select Policy',
				width: '200',
				selectOnFocus:true
			}),{
				id: 'roleGoverner',
				fieldLabel: 'Governer',
				allowBlank: true,
				name: 'roleGoverner',
				xtype: 'checkbox'
			},{
				id: 'roleQMR',
				fieldLabel: 'QMR',
				allowBlank: true,
				name: 'roleQMR',
				xtype: 'checkbox'
			},{
				id: 'roleDARApproval',
				fieldLabel: 'DAR Approval',
				allowBlank: true,
				name: 'roleDARApproval',
				xtype: 'checkbox'
			},{
                id: 'roleISOApproval',
                fieldLabel: 'ISO Approval',
                allowBlank: true,
                name: 'roleISOApproval',
                xtype: 'checkbox'
            },{
                id: 'roleDCC',
                fieldLabel: 'DCC',
                allowBlank: true,
                name: 'roleDCC',
                xtype: 'checkbox'
            }]
		});";
		
		$js .= "var roleAddWindow = new Ext.Window({
			id: 'roleAddWindow',
			title: 'Create New Role',
			width: 350,
			height: 410,
			modal: true,
			minWidth: 350,
			minHeight: 410,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: roleAddForm,
			closable: false,
			buttons: [{
				id: 'btnSaveRole',
				text: 'Save Role',
				iconCls: 'saveIcon',
				handler: function() {
					
	    			roleAddWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: 'Saving your data, please wait...',
			           	progressText: 'Saving...',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	
		       		roleAddForm.getForm().setValues([
	            		{id:'rolePosID',value: Ext.getCmp('rolePosition').getValue()},
						{id:'rolePolicyID',value: Ext.getCmp('rolePolicy').getValue()}
	            	]);
		            	
		            	
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/organize-manager/add-role',
		    			method: 'POST',
		    			success: roleAddSuccess,
		    			failure: roleAddFailed,
		    			form: Ext.getCmp('roleAddForm').getForm().getEl()
		    		});
			       	
			     
	    		}
			},{
				id: 'btnCancelSaveRole',
				text: 'Cancel',
				iconCls: 'cancelIcon',
				handler: function() {
					roleAddWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function roleAddSuccess() {
			Ext.MessageBox.hide();
			
			orgTree.getNodeById(Cookies.get('contextElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang ['org'] ['addRoleSuccess']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnSaveRole').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function roleAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang['common']['error']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnSaveRole').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * สร้างหน้าจอสำหรับแก้ไขตำแหน่ง
	 *
	 * @return string
	 */
	public function getEditRoleForm() {
		global $lang;
		global $config;
		$js = "var roleEditForm = new Ext.form.FormPanel({
			id: 'roleEditForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				fieldLabel: 'roleEditOrgID',
				id: 'roleEditOrgID',
				allowBlank: false,
				name: 'roleEditOrgID',
				inputType: 'hidden'
			},{
				id: 'roleEditName',
				fieldLabel: 'Name',
				allowBlank: false,
				name: 'roleEditName',
				width: '200'
			},{
				id: 'roleEditDesc',
				fieldLabel: 'Description',
				allowBlank: true,
				name: 'roleEditDesc',
				xtype: 'textarea',
				width: '200',
				height: '100'
			},new Ext.form.ComboBox({
				id: 'roleEditPolicy',
				name: 'roleEditPolicy',
				allowBlank: false,
				fieldLabel: 'Policy',
				store: policyForRoleStore,
				displayField:'name',
				hiddenName: 'roleEditPolicyID',
				valueField: 'id',
				typeAhead: false,
				triggerAction: 'all',
				emptyText:'Select Policy',
				width: 200,
				selectOnFocus:true
			}),{
				id: 'roleEditGoverner',
				fieldLabel: 'Governer',
				allowBlank: true,
				name: 'roleEditGoverner',
				xtype: 'checkbox'
			},{
				id: 'roleEditQMR',
				fieldLabel: 'QMR',
				allowBlank: true,
				name: 'roleEditQMR',
				xtype: 'checkbox'
			},{
				id: 'roleEditDARApproval',
				fieldLabel: 'DAR Approval',
				allowBlank: true,
				name: 'roleEditDARApproval',
				xtype: 'checkbox'
			},{
                id: 'roleEditISOApproval',
                fieldLabel: 'ISO Approval',
                allowBlank: true,
                name: 'roleEditISOApproval',
                xtype: 'checkbox'
            },{
                id: 'roleEditDCC',
                fieldLabel: 'DCC',
                allowBlank: true,
                name: 'roleEditDCC',
                xtype: 'checkbox'
            },{
                id: 'roleEditUnlimit',
                fieldLabel: 'Unlimit Lookup',
                allowBlank: true,
                name: 'roleEditUnlimit',
                xtype: 'checkbox'
            },{
                id: 'roleEditCommander',
                fieldLabel: 'Commander',
                allowBlank: true,
                name: 'roleEditCommander',
                xtype: 'checkbox'
            }]
		});";
		
		$js .= "var roleEditWindow = new Ext.Window({
			id: 'roleEditWindow',
			title: 'Edit Role',
			width: 350,
			height: 415,
			modal: true,
			minWidth: 350,
			minHeight: 415,
			layout: 'fit',
			plain:true,
			resizablr: false,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: roleEditForm,
			closable: false,
			buttons: [{
				id: 'btnEditRole',
				text: 'Edit Role',
				iconCls: 'saveIcon',
				handler: function() {
	    			roleEditWindow.hide();
	    			Ext.MessageBox.show({
	    				id: 'dlgSaveData',
			           	msg: 'Saving your data, please wait...',
			           	progressText: 'Saving...',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	
		       		roleEditForm.getForm().setValues([
	            		{id:'rolePosID',value: Ext.getCmp('rolePosition').getValue()},
						{id:'rolePolicyID',value: Ext.getCmp('rolePolicy').getValue()}
	            	]);
		            	
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/organize-manager/edit-role',
		    			method: 'POST',
		    			success: roleEditSuccess,
		    			failure: roleEditFailed,
		    			form: Ext.getCmp('roleEditForm').getForm().getEl()
		    		});
	    		}
			},{
				id: 'btnCancelEditRole',
				text: 'Cancel',
				iconCls: 'cancelIcon',
				handler: function() {
					roleEditWindow.hide();
				}
			}]
		});
		";
		
		$js .= "function roleEditSuccess() {
			Ext.MessageBox.hide();
			if(orgTree.getNodeById(Cookies.get('contextElID')).attributes.isroot == 1) {
				//orgTree.getRootNode().reload();
			} else {
				orgTree.getNodeById(Cookies.get('contextElID')).parentNode.reload();
			}
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang ['org'] ['editRoleSuccess']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnSaveRole').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function roleEditFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang['common']['error']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnSaveRole').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * สร้างหน้าจอสำหรับกำหนดบุคคลลงตำแหน่ง
	 *
	 * @return string
	 */
	public function getMapUserForm() {
		global $config;
		global $lang;
		
		$js = "var userMappingWindow = new Ext.Window({
			id: 'userMappingWindow',
			title: 'Mapping Position',
			width: 500,
			height: 250,
			modal: true,
			minWidth: 300,
			minHeight: 250,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			autoLoad: {
				url: '/{$config ['appName']}/organize-manager/get-mapping-user-detail',
				scripts: true
			},
			closable: false,
			buttons: [{
				id: 'btnMappingUser',
				text: 'Map User',
				iconCls: 'saveIcon',
				handler: function() {
					
	    			userMappingWindow.hide();
	    			Ext.MessageBox.show({
	    				id: 'dlgMapPosData',
			           	msg: 'Updating your data, please wait...',
			           	progressText: 'Updating...',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
					var selectedRows = userMappingGrid.getSelectionModel().getSelections();
					var selectedID = '';
					for(i=0;i < selectedRows.length;i++) {
						if(selectedID != '') {
							selectedID = selectedID + '|';
						}
						selectedID = selectedID +selectedRows[i].get('id');
					}
	    			Ext.Ajax.request({
	    				url: '/{$config ['appName']}/organize-manager/map-role',
	    				params: {
	    					selectedID: selectedID,
	    					roleID: Cookies.get('contextObjectID')
						},
	    				method: 'POST',
	    				success: roleMappingSuccess,
	    				failure: roleMappingFailed
	    			});
	    		}
			},{
				id: 'btnCancelMappingUser',
				text: 'Cancel',
				iconCls: 'cancelIcon',
				handler: function() {
					userMappingWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function roleMappingSuccess() {
			Ext.MessageBox.hide();
			
			orgTree.getNodeById(Cookies.get('contextElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang ['org'] ['addMappingSuccess']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnMappingUser').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function roleMappingFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang['common']['error']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnCancelMappingUser').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * สร้างแบบฟอร์มสำหรับแก้ไขเลขทะเบียนกลาง
	 *
	 * @return string
	 */
	public function getEditGlobalRegNoForm() {
		global $config;
		global $lang;
		
		$js = "var editGlobalRegNoForm = new Ext.form.FormPanel({
			id: 'editGlobalRegNoForm',
			baseCls: 'x-plain',
			labelWidth: 150,
			defaultType: 'textfield',
			monitorValid:true,

			items: [{
				fieldLabel: '{$lang['label']['orgUnit']}',
				id: 'editGlobalOrgName',
				name: 'editGlobalOrgName',
				readOnly: false,
				width: 200
			},{
				inputType: 'hidden',
				fieldLabel: 'id',
				id: 'editGlobalOrgID',
				name: 'editGlobalOrgID'
			},{
				fieldLabel: '{$lang['label']['globalRecvExt']}',
				id: 'recvExternalRegNo',
				allowBlank: false,
				name: 'recvExternalRegNo'
			},{
				fieldLabel: '{$lang['label']['globalSendExt']}',
				id: 'sendExternalRegNo',
				allowBlank: false,
				name: 'sendExternalRegNo'
			},{
                fieldLabel: '{$lang['label']['globalSendInt']}',
                id: 'sendInternalRegNo',
                allowBlank: false,
                name: 'sendInternalRegNo'
            }],
			buttons: [{
				formBind:true,
				id: 'btnSaveGlobalRegNo',
				text: '{$lang['common']['modify']}',
				iconCls: 'saveIcon',
				handler: function() {
	    			editGlobalRegNoWindow.hide();
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
		    			url: '/{$config ['appName']}/df-action/set-global-regno',
		    			method: 'POST',
		    			form: Ext.getCmp('editGlobalRegNoForm').getForm().getEl(),
						success: function(o){
			    			Ext.MessageBox.hide();
							var r = Ext.decode(o.responseText);
							if(r.success ==1){
								Ext.MessageBox.show({
								    title: '{$lang['df']['editRegNo']}',
								    msg: '{$lang['common']['saved']}',
								    buttons: Ext.MessageBox.OK,
								    icon: Ext.MessageBox.INFO
								});
							}
						},
			    		failure: function(r,o) {
						}		    			
		    		});
	    		}
			},{
				id: 'btnCancelSaveGlobalRegNo',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					editGlobalRegNoWindow.hide();
				}
			}]
		});";
		
		$js .= "var editGlobalRegNoWindow = new Ext.Window({
			id: 'editGlobalRegNoWindow',
			title: '{$lang['window']['editGlobalRegno']}',
			width: 385,
			height: 175,
			modal: true,
			minWidth: 385,
			minHeight: 175,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: editGlobalRegNoForm,
			closable: false
			
		});";
		/*
		$js .= "function orgAddSuccess() {
			Ext.MessageBox.hide();
			
			orgTree.getNodeById(Cookies.get('contextElID')).reload();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: 'Account Added!',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function orgAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Organization Structure Manager',
	    		msg: '{$lang['common']['error']}',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";*/
		return $js;
	}
	
	/**
	 * สร้างฟอร์มสำหรับแก้ไขเลขทะเบียนหน่วยงาน
	 *
	 * @return string
	 */
	public function getEditLobalRegNoForm() {
		global $config;
		global $lang;
		global $sessionMgr;
		
		$js = "
			var editLocalRegNoForm = new Ext.form.FormPanel({
			id: 'editLocalRegNoForm',
			baseCls: 'x-plain',
			labelWidth: 150,
			defaultType: 'textfield',
			monitorValid:true,

			items: [{
				fieldLabel: '{$lang['label']['orgUnit']}',
				id: 'editLocalOrgName',
				name: 'editLocalOrgName',
				readOnly: false,
				width: 200
			},{
				inputType: 'hidden',
				fieldLabel: 'id',
				id: 'editLocalOrgID',
				name: 'editLocalOrgID'
			},{
				fieldLabel: '{$lang ['label'] ['recvInt']}',
				id: 'recvInternalLocal',
				allowBlank: false,
				name: 'recvInternalLocal'
			},{
				fieldLabel: '{$lang ['label'] ['sendInt']}',
				id: 'sendInternalLocal',
				allowBlank: false,
				name: 'sendInternalLocal'
			},{
				fieldLabel: '{$lang ['label'] ['recvExt']}',
				id: 'recvExternalLocal',
				allowBlank: false,
				name: 'recvExternalLocal'
			},{
				fieldLabel: '{$lang ['label'] ['sendExt']}',
				id: 'sendExternalLocal',
				allowBlank: false,
				name: 'sendExternalLocal'
			},{
				fieldLabel: '{$lang ['label'] ['recvClassifiedInt']}',
				id: 'recvClassifiedInt',
				allowBlank: false,
				name: 'recvClassifiedInt'
			},{
				fieldLabel: '{$lang ['label'] ['recvClassifiedExt']}',
				id: 'recvClassifiedExt',
				allowBlank: false,
				name: 'recvClassifiedExt'
			},{
				fieldLabel: '{$lang ['label'] ['sendClassifiedInt']}',
				id: 'sendClassifiedInt',
				allowBlank: false,
				name: 'sendClassifiedInt'
			},{
				fieldLabel: '{$lang ['label'] ['sendClassifiedExt']}',
				id: 'sendClassifiedExt',
				allowBlank: false,
				name: 'sendClassifiedExt'
			},{
                fieldLabel: '{$lang ['label'] ['recvCirc']}',
                id: 'recvCirc',
                allowBlank: false,
                name: 'recvCirc'
            },{
                fieldLabel: '{$lang ['label'] ['sendCirc']}',
                id: 'sendCirc',
                allowBlank: false,
                name: 'sendCirc'
            }],
			buttons: [{
				formBind:true,
				id: 'btnSaveLocalRegNo',
				iconCls: 'saveIcon',
				text: '{$lang['common']['modify']}',
				handler: function() {
	    			editLocalRegNoWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/df-action/set-regno',
		    			method: 'POST',
		    			form: Ext.getCmp('editLocalRegNoForm').getForm().getEl(),
						success: function(o){
			    			Ext.MessageBox.hide();
							var r = Ext.decode(o.responseText);
							if(r.success ==1){
								Ext.MessageBox.show({
								    title: '{$lang['df']['editRegNo']}',
								    msg: '{$lang['common']['saved']}',
								    buttons: Ext.MessageBox.OK,
								    icon: Ext.MessageBox.INFO
								});
							}
						},
			    		failure: function(r,o) {
						}		    			
		    		});
	    		}
			},{
				id: 'btnCancelSaveLocalRegNo',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					editLocalRegNoWindow.hide();
				}
			}]
		});";
		
		$js .= "var editLocalRegNoWindow = new Ext.Window({
			id: 'editLocalRegNoWindow',
			title: '{$lang['window']['editLocalRegno']}',
			width: 385,
			height: 305,
			modal: true,
			minWidth: 385,
			minHeight: 305,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: editLocalRegNoForm,
			closable: false
		});";
			
		$js .= "
			var editLocalRegNoFormSaraban = new Ext.form.FormPanel({
			id: 'editLocalRegNoFormSaraban',
			baseCls: 'x-plain',
			labelWidth: 150,
			defaultType: 'textfield',
			monitorValid:true,

			items: [{
				fieldLabel: '{$lang['label']['orgUnit']}',
				id: 'editLocalOrgNameSaraban',
				name: 'editLocalOrgNameSaraban',
				readOnly: false,
				width: 200
			},{
				inputType: 'hidden',
				fieldLabel: 'id',
				id: 'editLocalOrgIDSaraban',
				name: 'editLocalOrgIDSaraban'
			},{
				fieldLabel: '{$lang ['label'] ['recvInt']}',
				id: 'recvInternalLocalSaraban',
				allowBlank: false,
				name: 'recvInternalLocalSaraban'
			},{
				fieldLabel: '{$lang ['label'] ['sendInt']}',
				id: 'sendInternalLocalSaraban',
				allowBlank: false,
				name: 'sendInternalLocalSaraban'
			},{
				fieldLabel: '{$lang ['label'] ['recvExt']}',
				id: 'recvExternalLocalSaraban',
				allowBlank: false,
				name: 'recvExternalLocalSaraban'
			},{
				fieldLabel: '{$lang ['label'] ['sendExt']}',
				id: 'sendExternalLocalSaraban',
				allowBlank: false,
				name: 'sendExternalLocalSaraban'
			},{
				fieldLabel: '{$lang ['label'] ['recvClassified']}',
				id: 'recvClassifiedSaraban',
				allowBlank: false,
				name: 'recvClassifiedSaraban'
			},{
				fieldLabel: '{$lang ['label'] ['sendClassified']}',
				id: 'sendClassifiedSaraban',
				allowBlank: false,
				name: 'sendClassifiedSaraban'
			},{
                fieldLabel: '{$lang ['label'] ['recvCirc']}',
                id: 'recvCircSaraban',
                allowBlank: false,
                name: 'recvCircSaraban'
            },{
                fieldLabel: '{$lang ['label'] ['sendCirc']}',
                id: 'sendCircSaraban',
                allowBlank: false,
                name: 'sendCircSaraban'
            }],
			buttons: [{
				formBind:true,
				id: 'btnSaveLocalRegNo',
				iconCls: 'saveIcon',
				text: '{$lang['common']['modify']}',
				handler: function() {
	    			editLocalRegNoWindowSaraban.hide();
			
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
		    			url: '/{$config ['appName']}/df-action/set-regno',
		    			method: 'POST',
		    			form: Ext.getCmp('editLocalRegNoFormSaraban').getForm().getEl(),
						success: function(o){
			Ext.MessageBox.hide();
							var r = Ext.decode(o.responseText);
							if(r.success ==1){
			Ext.MessageBox.show({
								    title: '{$lang['df']['editRegNo']}',
								    msg: '{$lang['common']['saved']}',
	    		buttons: Ext.MessageBox.OK,
								    icon: Ext.MessageBox.INFO
								});
							}
						},
			    		failure: function(r,o) {
						}		    			
	    	});
	    		}
			},{
				id: 'btnCancelSaveLocalRegNo',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					editLocalRegNoWindowSaraban.hide();
				}
			}]
		});";

		$js .= "var editLocalRegNoWindowSaraban = new Ext.Window({
			id: 'editLocalRegNoWindowSaraban',
			title: '{$lang['window']['editLocalRegno']}',
			width: 385,
			height: 305,
			modal: true,
			minWidth: 385,
			minHeight: 305,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: editLocalRegNoFormSaraban,
			closable: false
		});";
		return $js;
	}
	
	/**
	 * สร้างแบบฟอร์มสำหรับแก้ไขเลขที่หนังสือ
	 *
	 * @return string
	 */
	public function getEditBookNoJS() {
		global $config;
		global $lang;
		
		$js = "var editBookNoForm = new Ext.form.FormPanel({
			id: 'editBookNoForm',
			baseCls: 'x-plain',
			labelWidth: 150,
			defaultType: 'textfield',
			monitorValid:true,

			items: [{
				fieldLabel: '{$lang['label']['orgUnit']}',
				id: 'editBookNoOrgName',
				name: 'editBookNoOrgName',
				readOnly: false,
				width: 200
			},{
				inputType: 'hidden',
				fieldLabel: 'id',
				id: 'editBookNoOrgID',
				name: 'editBookNoOrgID'
			},{
				fieldLabel: '{$lang ['label'] ['intBookno']}',
				id: 'intBookNo',
				allowBlank: false,
				name: 'intBookNo'
			},{
				fieldLabel: '{$lang ['label'] ['extBookno']}',
				id: 'extBookNo',
				allowBlank: false,
				name: 'extBookNo'
			},{
                fieldLabel: '{$lang ['label'] ['circIntBookno']}',
                id: 'circIntBookNo',
                allowBlank: false,
                name: 'circIntBookNo'
            }],
			buttons: [{
				formBind:true,
				id: 'btnSaveBookNo',
				text: '{$lang['common']['modify']}',
				iconCls: 'saveIcon',
				handler: function() {
	    			editBookNoWindow.hide();
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
		    			url: '/{$config ['appName']}/df-action/set-bookno',
		    			method: 'POST',
		    			form: Ext.getCmp('editBookNoForm').getForm().getEl(),
						success: function(o){
			    			Ext.MessageBox.hide();
							var r = Ext.decode(o.responseText);
							if(r.success ==1){
								Ext.MessageBox.show({
								    title: '{$lang['df']['editBookNo']}',
								    msg: '{$lang['common']['saved']}',
								    buttons: Ext.MessageBox.OK,
								    icon: Ext.MessageBox.INFO
								});
							}
						},
			    		failure: function(r,o) {
						}		    			
		    		});
	    		}
			},{
				id: 'btnCancelSaveBookNo',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					editBookNoWindow.hide();
				}
			}]
		});";
		
		$js .= "var editBookNoWindow = new Ext.Window({
			id: 'editBookNoWindow',
			title: '{$lang ['window'] ['editBookno']}',
			width: 385,
			height: 185,
			modal: true,
			minWidth: 385,
			minHeight: 185,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: editBookNoForm,
			closable: false
			
		});";
		
		return $js;
	}
	
	/**
	 * สร้างฟอร์มพาเนลสำหรับการกำหนด User Mapping
	 *
	 */
	public function getMappingUserDetailAction() {
		global $config;
		global $conn;
		global $lang;
		
		//checkSessionPortlet();
		

		echo "<div id=\"mapRoleDiv\"></div>";
		$roleID = $_COOKIE ['contextObjectID'];
		$sql = "select e.* from tbl_role a,tbl_position_master b,tbl_mapping c,tbl_rank d ,tbl_account e";
		$sql .= " where a.f_role_id = '$roleID'";
		$sql .= " and b.f_pos_id = a.f_pos_id";
		$sql .= " and c.f_master_id = b.f_pos_id";
		$sql .= " and c.f_map_class = 'pos2rank'";
		$sql .= " and d.f_rank_id = c.f_slave_id and e.f_rank_id = d.f_rank_id";
		
		$rs = $conn->Execute ( $sql );
		$data = "";
		foreach ( $rs as $row ) {
			checkKeyCase ( $row );
			if ($data != '') {
				$data .= ",";
			}
			$data .= "['{$row['f_acc_id']}','{$row['f_name']}','{$row['f_rank_id']}','{$row['f_status']}']";
		}
		
		$accountAvailStore = "var accountAvailStore = new Ext.data.Store({
		    proxy: new Ext.data.ScriptTagProxy({
		        url: '/{$config ['appName']}/data-store/account-avail?roleID={$roleID}'
		    }),
		
		    // create reader that reads the Topic records
		    reader: new Ext.data.JsonReader({
		        root: 'results',
		        totalProperty: 'total',
		        id: 'id',
		        fields: ['id','name', 'rank','status']
		    }),
		    // turn on remote sorting
		    remoteSort: true
		});";
		/*
		$accountAvailStore = "var accountAvailStore = new Ext.data.SimpleStore({
			fields: ['id','name', 'rank','status'],
			id: 'id',
			data :  [{$data}]
		});";
		*/
		
		$js = "<script type=\"text/javascript\">
		$accountAvailStore
		
		accountAvailStore.load();
		Ext.ux.grid.filter.StringFilter.prototype.icon = 'images/find.png';
		var filters = new Ext.ux.grid.GridFilters({filters:[
			{type: 'string',  dataIndex: 'name'}
		]});
				
		var userMappingGrid = new Ext.grid.GridPanel({
			id: 'userMappingGrid',
			store: accountAvailStore,
			autoExpandMax: true,
			columns: [
			{id: 'id', header: 'Name', width: 300, sortable: false, dataIndex: 'name'},
			{header: 'Rank', width: 150, sortable: false, dataIndex: 'rank'},
			{header: 'Status', width: 120, sortable: false, dataIndex: 'status'}
			],
			viewConfig: {
				forceFit: true
			},
			plugins: filters,
			sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
			width: 475,
			height: 200,
			frame: false,
			iconCls:'icon-grid'
		});
		
		var userMappingForm = new Ext.form.FormPanel({
			id: 'userMappingForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			items: userMappingGrid,
			renderTo: 'mapRoleDiv'
		});
		
		
		</script>";
		echo $js;
	}
	
	/**
	 * action /get-ui แสดงหน้าจอจัดการหน่วยงาน
	 *
	 */
	public function getUiAction() {
		global $config;
		global $store;
		global $lang;
		
		checkSessionPortlet ();
		
		$orgTypeStore = $store->getDataStore ( 'organizeType' );
		$positionStore = $store->getDataStore ( 'position', 'positionForRoleStore' );
		$policyStore = $store->getDataStore ( 'policy', 'policyForRoleStore' );
		$addOUFormJS = $this->getAddOUForm ();
		$editOUFormJS = $this->getEditOUForm ();
		$roleAddFormJS = $this->getAddRoleForm ();
		$roleEditFormJS = $this->getEditRoleForm ();
		$mapUserFormJS = $this->getMapUserForm ();
		$editGlobalRegNoJS = $this->getEditGlobalRegNoForm ();
		$editLobalRegNoJS = $this->getEditLobalRegNoForm ();
		$editBookNoJS = $this->getEditBookNoJS ();
		
		/* prepare DIV For UI */
		echo "<div id=\"organizeUIToolbarDiv\" display=\"inline\"></div>";
		echo "<div id=\"organizeUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		
		function renderOUTypeStatus(value, p, record){
			//alert(record.data.type);
			//return 'xxx';
			if(record.orgtype == 0) {
				return '{$lang['org']['ou']}';
			}
			
			if(record.orgtype == 1) {
				return '{$lang['org']['group']}';
			}
			
			if(record.orgtype == 2) {
				return '{$lang['org']['role']}';
			}
			
			if(record.orgtype == 3) {
				return '{$lang['org']['user']}';
			}
			switch(record.type) {
				case '0' :
					return '{$lang['org']['ou']}';
				break;
				case '1' :
					return '{$lang['org']['group']}';
				break;
				case '2' :
					return '{$lang['org']['role']}';
				break;
				case '3' :
					return '{$lang['org']['user']}';
				break;
			}
		}
		$orgTypeStore
		$positionStore
		$policyStore
		
		positionForRoleStore.load();
		policyForRoleStore.load();
		var orgTreeLoader = new Ext.tree.TreeLoader({
        	dataUrl   : '/{$config ['appName']}/data-store/load-organize',
        	baseParams: {objid: -1},
        	uiProviders:{
                'col': Ext.tree.ColumnNodeUI
            }
    	});
    	$addOUFormJS
    	$editOUFormJS
    	$roleAddFormJS
    	$roleEditFormJS    	
    	$mapUserFormJS
    	$editGlobalRegNoJS
    	$editLobalRegNoJS
    	$editBookNoJS
    	
    	var orgTreeContext = new Ext.menu.Menu('mainContext');
    	orgTreeContext.add(
        	new Ext.menu.Item({id:'mnuItemCreateOrganize', text: '{$lang['context']['orgMrg']['createOrg']}', iconCls: 'mainOrgIcon'}),
        	new Ext.menu.Item({id:'mnuItemCreateGroup' ,text: '{$lang['context']['orgMrg']['createGroup']}', iconCls: 'subOrgIcon'}),
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id:'mnuItemAddRole' ,text: '{$lang['context']['orgMrg']['addRole']}', iconCls: 'roleIcon'}),
        	new Ext.menu.Item({id:'mnuItemAddUser', text: '{$lang['context']['orgMrg']['addUser']}', iconCls: 'userIcon'}),
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id: 'mnuItemEdit',text: '{$lang['context']['orgMrg']['edit']}', iconCls: 'editIcon'}),
        	new Ext.menu.Item({id: 'mnuItemDelete',text: '{$lang['context']['orgMrg']['delete']}', iconCls: 'deleteIcon'}),
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id: 'mnuItemSetRegNo',text: '{$lang['context']['orgMrg']['setRegNo']}', iconCls: 'editRegNoIcon'}),
        	new Ext.menu.Item({id: 'mnuItemSetBookNo',text: '{$lang['context']['orgMrg']['setBookNo']}', iconCls: 'editRegNoIcon'}),
        	new Ext.menu.Separator(),
        	new Ext.menu.Item({id:'mnuItemRefresh',text: '{$lang['context']['orgMrg']['refresh']}', iconCls: 'refreshIcon'})
    	);
    
		var orgTree = new Ext.tree.ColumnTree({
        renderTo: 'organizeUIDiv',
        //autoWidth: true,
        ddScroll: true,
        width: Ext.getCmp('tpAdmin').getInnerWidth(),
        height: Ext.getCmp('tpAdmin').getInnerHeight(),
        rootVisible:false,
        autoScroll:true,
        enableDD:true,
        mask: true,
        id: 'orgTree',
            
        
        columns:[{
            header:'{$lang['org']['name']}',
            width:550,
            dataIndex:'name'
        },{
            header:'{$lang['common']['desc']}',
            width:400,
            dataIndex:'description'
        },{
            header:'{$lang['org']['type']}',
            width:100,
            dataIndex:'type'
            ,renderer: renderOUTypeStatus
        }],

        loader: orgTreeLoader,

        root: new Ext.tree.AsyncTreeNode({
            text:'Organization Unit'
        })
    });
    
	orgTreeLoader.on('beforeload', function(treeLoader, node) {
		if(node.attributes.type == 2) {
			treeLoader.dataUrl = '/{$config ['appName']}/data-store/load-user-in-role';
		} else {
			treeLoader.dataUrl = '/{$config ['appName']}/data-store/load-organize';
		}
		treeLoader.baseParams.objid = node.attributes.objid;
		//orgTree.mask();
	}, this);
	
	orgTreeLoader.on('load', function(t,node,res) {
		//orgTree.unmask();
	}, this);
	    
		
	Ext.getCmp('mnuItemCreateOrganize').on('click',function(obj,e) {
		ouAddForm.getForm().reset();
		ouAddWindow.show();
		ouAddForm.getForm().setValues([
            		{id:'orgType',value: 0},
            		{id:'parentOrgName',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text},
            		{id:'parentOrgID' ,value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
        ]);
	});
	
	Ext.getCmp('mnuItemCreateGroup').on('click',function(obj,e) {
		ouAddForm.getForm().reset();
		ouAddWindow.show();
		ouAddForm.getForm().setValues([
            		{id:'orgType',value: 1},
            		{id:'parentOrgName',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text},
            		{id:'parentOrgID' ,value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
        ]);
	});
	
	Ext.getCmp('mnuItemAddRole').on('click',function(obj,e) {
		roleAddForm.getForm().reset();
		roleAddWindow.show();
		roleAddForm.getForm().setValues([
            		{id:'roleOrgName',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text},
            		{id:'roleOrgID' ,value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
        ]);
	});
	
	// BOOKMARK: Set Register No 
	Ext.getCmp('mnuItemSetRegNo').on('click',function(obj,e) {		
		if(orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid == 0) {
			orgTreeContext.hide();
			editGlobalRegNoWindow.show();
			editGlobalRegNoForm.getForm().reset();
			editGlobalRegNoForm.getForm().setValues(
			[
				{id: 'editGlobalOrgName',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text}
				,{id: 'editGlobalOrgID',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
			]
			);
			Ext.Ajax.request({
		    	url: '/{$config ['appName']}/df-action/request-global-regno',
		    	method: 'POST',
		    	success: function(o){
			    	Ext.MessageBox.hide();
					var r = Ext.decode(o.responseText);
					editGlobalRegNoForm.getForm().setValues(
					[
						{id: 'recvExternalRegNo',value: r.recv}
						,{id: 'sendExternalRegNo',value: r.send}
                        ,{id: 'sendInternalRegNo',value: r.send2}   
					]
					);
				},
			    failure: function(r,o) {
				}
		    });
			
		} else if (orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid == 298) {

			orgTreeContext.hide();
			editLocalRegNoWindowSaraban.show();
			editLocalRegNoFormSaraban.getForm().reset();
			editLocalRegNoFormSaraban.getForm().setValues(
			[
				{id: 'editLocalOrgNameSaraban',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text}
				,{id: 'editLocalOrgIDSaraban',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
			]
			);
			Ext.Ajax.request({
		    	url: '/{$config ['appName']}/df-action/request-regno',
		    	method: 'POST',
		    	success: function(o){
			    	Ext.MessageBox.hide();
					var r = Ext.decode(o.responseText);
					editLocalRegNoFormSaraban.getForm().setValues(
					[
						{id: 'recvInternalLocalSaraban',value: r.recvInt}
						,{id: 'recvExternalLocalSaraban',value: r.recvExt}
						,{id: 'recvClassifiedSaraban',value: r.recvClass}
						,{id: 'recvClassifiedIntSaraban',value: r.recvClassInt}
						,{id: 'recvClassifiedExtSaraban',value: r.recvClassExt}
                        ,{id: 'recvCircSaraban',value: r.recvCirc}                      
						,{id: 'sendInternalLocalSaraban',value: r.sendInt}
						,{id: 'sendExternalLocalSaraban',value: r.sendExt}
						,{id: 'sendClassifiedSaraban',value: r.sendClass}    
						,{id: 'sendClassifiedIntSaraban',value: r.sendClassInt} 
						,{id: 'sendClassifiedExtSaraban',value: r.sendClassExt} 
                        ,{id: 'sendCircSaraban',value: r.sendCirc}
					]
					);
				},
			    failure: function(r,o) {
				},
				params: {orgID: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
		    });
		} else {
			orgTreeContext.hide();
			editLocalRegNoWindow.show();
			editLocalRegNoForm.getForm().reset();
			editLocalRegNoForm.getForm().setValues(
			[
				{id: 'editLocalOrgName',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text}
				,{id: 'editLocalOrgID',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
			]
			);
			Ext.Ajax.request({
		    	url: '/{$config ['appName']}/df-action/request-regno',
		    	method: 'POST',
		    	success: function(o){
			    	Ext.MessageBox.hide();
					var r = Ext.decode(o.responseText);
					editLocalRegNoForm.getForm().setValues(
					[
						{id: 'recvInternalLocal',value: r.recvInt}
						,{id: 'recvExternalLocal',value: r.recvExt}
						,{id: 'recvClassified',value: r.recvClass}
						,{id: 'recvClassifiedInt',value: r.recvClassInt}
						,{id: 'recvClassifiedExt',value: r.recvClassExt}
                        ,{id: 'recvCirc',value: r.recvCirc}
						,{id: 'sendInternalLocal',value: r.sendInt}
						,{id: 'sendExternalLocal',value: r.sendExt}
						,{id: 'sendClassified',value: r.sendClass}                        
						,{id: 'sendClassifiedInt',value: r.sendClassInt} 
						,{id: 'sendClassifiedExt',value: r.sendClassExt} 
                        ,{id: 'sendCirc',value: r.sendCirc}
					]
					);
				},
			    failure: function(r,o) {
				},
				params: {orgID: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
		    });
		}
	});
	
	Ext.getCmp('mnuItemSetBookNo').on('click',function(obj,e) {		
		orgTreeContext.hide();
		editBookNoForm.getForm().reset();
		editBookNoWindow.show();
		editBookNoForm.getForm().setValues(
			[
				{id: 'editBookNoOrgName',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text}
				,{id: 'editBookNoOrgID',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
			]
		);
		
		Ext.Ajax.request({
		   	url: '/{$config ['appName']}/df-action/request-bookno',
		   	method: 'POST',
		   	success: function(o){
		    	Ext.MessageBox.hide();
				var r = Ext.decode(o.responseText);
				editBookNoForm.getForm().setValues(
				[
					{id: 'intBookNo',value: r.intBookNo}
					,{id: 'extBookNo',value: r.extBookNo}
                    ,{id: 'circIntBookNo',value: r.circIntBookNo}
				]
				);
			},
		    failure: function(r,o) {
			},
			params: {orgID: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid}
		});
	});	
	
	
	Ext.getCmp('mnuItemAddUser').on('click',function(obj,e) {
		
		if(!Ext.getCmp('userMappingForm')) {
         	userMappingWindow.show();
		} else {
			userMappingForm.getUpdater().update({
				url: '/{$config ['appName']}/organize-manager/get-mapping-user-detail'
				, scripts: true
			});
			userMappingWindow.show();
		}
	});
	
	function deleteOUAction(btn) {
		if(btn == 'yes') {
			Ext.Ajax.request({
	    			url: '/{$config ['appName']}/organize-manager/delete-ou',
	    			method: 'POST',
	    			success: deleteOUSuccess,
	    			failure: deleteOUFailed,
	    			params: { 
	    				ouID: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid
					}
	    	});
		}
	}
	
	function deleteRoleAction(btn) {
		if(btn == 'yes') {
			Ext.Ajax.request({
	    			url: '/{$config ['appName']}/organize-manager/delete-role',
	    			method: 'POST',
	    			success: deleteRoleSuccess,
	    			failure: deleteRoleFailed,
	    			params: { 
	    				roleID: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid
					}
	    	});
		}
	}
	
	
	
	function deleteMappingAction(btn) {
		if(btn == 'yes') {
			Ext.Ajax.request({
	    			url: '/{$config ['appName']}/organize-manager/delete-mapping',
	    			method: 'POST',
	    			success: deleteMappingSuccess,
	    			failure: deleteMappingFailed,
	    			params: { 
	    				roleID: orgTree.getNodeById(Cookies.get('contextElID')).parentNode.attributes.objid,
	    				accID: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid
					}
	    	});
		}
	}
	
	Ext.getCmp('mnuItemDelete').on('click',function(obj,e) {
		switch(orgTree.getNodeById(Cookies.get('contextElID')).attributes.type) {
			case 0:
			case 1:
				Ext.MessageBox.confirm(
					'Confirm', 
					'Delete Organization Unit [ '+orgTree.getNodeById(Cookies.get('contextElID')).attributes.text+']?', deleteOUAction
				);
			break;
			case 2:
				Ext.MessageBox.confirm(
					'Confirm', 
					'Delete Role [ '+orgTree.getNodeById(Cookies.get('contextElID')).attributes.text+']?', deleteRoleAction
				);
			break;
			case 3:
				Ext.MessageBox.confirm(
					'Confirm', 
					'Delete Mapping [ '+orgTree.getNodeById(Cookies.get('contextElID')).attributes.text+']?', deleteMappingAction
				);
			break;
		}
	});
	
	Ext.getCmp('mnuItemEdit').on('click',function(obj,e) {
		switch(orgTree.getNodeById(Cookies.get('contextElID')).attributes.type) {
			case 0:				
			case 1:
				ouEditWindow.show();
				ouEditForm.getForm().setValues([
	            		{id:'editOrgID',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid},
	            		{id:'editOrgName',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text},
	            		{id:'editOrgDesc',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.description},
	            		{id:'editIntDocCode',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.intDocCode},
	            		{id:'editExtDocCode',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.extDocCode},
						{id:'editOrgType',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.type}
	            ]);
                
                if (orgTree.getNodeById(Cookies.get('contextElID')).attributes.allowInt == 1) {
                    Ext.getCmp('orgEditFlagAllowIntDocNo').setValue(1);
                } else {
                    Ext.getCmp('orgEditFlagAllowIntDocNo').setValue(0);
                }
                
			break;
			case 2:
				roleEditWindow.show();
				roleEditForm.getForm().setValues([
	            		{id:'roleEditOrgID',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid},
	            		{id:'roleEditName',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.text},
	            		{id:'roleEditDesc',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.description},
	            		{id:'roleEditPolicy',value: orgTree.getNodeById(Cookies.get('contextElID')).attributes.policyID}
						
	            ]);
	            if (orgTree.getNodeById(Cookies.get('contextElID')).attributes.governer == 1) {
	            	Ext.getCmp('roleEditGoverner').setValue(1);
				} else {
					Ext.getCmp('roleEditGoverner').setValue(0);
				}
                
                if (orgTree.getNodeById(Cookies.get('contextElID')).attributes.unlimit == 1) {
                    Ext.getCmp('roleEditUnlimit').setValue(1);
                } else {
                    Ext.getCmp('roleEditUnlimit').setValue(0);
                }

				if (orgTree.getNodeById(Cookies.get('contextElID')).attributes.commander == 1) {
                    Ext.getCmp('roleEditCommander').setValue(1);
                } else {
                    Ext.getCmp('roleEditCommander').setValue(0);
                }
			break;
			case 3:
				Ext.MessageBox.confirm(
					'Confirm', 
					'Delete Mapping [ '+orgTree.getNodeById(Cookies.get('contextElID')).attributes.text+']?', deleteMappingAction
				);
			break;
		}
	});
	
	
	
	
	Ext.getCmp('mnuItemRefresh').on('click',function(obj,e) {
		orgTree.getNodeById(Cookies.get('contextElID')).reload();
	});
	
	orgTree.on('contextmenu', function(node,e) {
		if(node.attributes.type > 0) {
			Ext.getCmp('mnuItemCreateOrganize').disable();
			Ext.getCmp('mnuItemCreateGroup').disable();
			Ext.getCmp('mnuItemSetRegNo').disable();
			Ext.getCmp('mnuItemSetBookNo').disable();
		}else {
			Ext.getCmp('mnuItemCreateOrganize').enable();
			Ext.getCmp('mnuItemCreateGroup').enable();
			Ext.getCmp('mnuItemSetRegNo').enable();
			Ext.getCmp('mnuItemSetBookNo').enable();
		}
		
		if(node.attributes.type >= 1) {
			Ext.getCmp('mnuItemCreateGroup').disable();
			Ext.getCmp('mnuItemAddRole').disable();
		}else {
			Ext.getCmp('mnuItemCreateGroup').enable();
			Ext.getCmp('mnuItemAddRole').enable();
		}
		
		if(node.attributes.type >=2) {
			Ext.getCmp('mnuItemAddRole').disable();
		}else {
			Ext.getCmp('mnuItemAddRole').enable();
		}
		
		if(node.attributes.type != 2 ) {
			Ext.getCmp('mnuItemAddUser').disable();
		}else {
			Ext.getCmp('mnuItemAddUser').enable();
		}
		if(node.attributes.type == 3 ) {
			Ext.getCmp('mnuItemEdit').disable();
			Ext.getCmp('mnuItemRefresh').disable();
		} else {
			Ext.getCmp('mnuItemEdit').enable();
			Ext.getCmp('mnuItemRefresh').enable();
		}
		
		Cookies.set('contextElID',node.id);
		Cookies.set('contextObjectID',orgTree.getNodeById(Cookies.get('contextElID')).attributes.objid);
		
		orgTreeContext.showAt(e.getXY());
	}, orgTree);
	
	//Start Dragging a tree node
	orgTree.on('startdrag',function(tree,node,e ) {
		Cookies.set('ouel',node.id);
		Cookies.set('ouid',node.attributes.objid);
		Cookies.set('out',node.attributes.text);
		Cookies.set('outy',node.attributes.type);
		Cookies.set('oup',node.parentNode.id);
		Cookies.set('oupty',node.parentNode.attributes.type);
	});
	
    //Check node drop condition
	orgTree.on('nodedragover',function (dropEvent) {
		if(dropEvent.target.attributes.type > Cookies.get('outy')) {
			return false;
		}
		
		if(dropEvent.target.id == Cookies.get('oup')) {
			return false;
		}
		
		if(dropEvent.target.attributes.type ==3) {
			return false;
		}
		if(dropEvent.target.attributes.type == 2) {
			
			if(Cookies.get('outy') <= 2) {
				return false;
			}
		
			if(!dropEvent.target.isExpanded()) {
				dropEvent.target.expand(true,true,function() {
					dropEvent.target.eachChild(function(node) {
						if(node.attributes.objid == Cookies.get('ouid')) {
							dropEvent.cancel = true;
						}
					});
				});
			} else {
				dropEvent.target.eachChild(function(node) {
					if(node.attributes.objid == Cookies.get('ouid')) {
						dropEvent.cancel = true;
					}
				});
			}
		}
	});
	
    orgTree.on('beforenodedrop', function(dropEvent){
		//alert('source : ' + Cookies.get('out'));
		//alert('source id : ' + Cookies.get('ouid'));
		
		//alert('target: ' + dropEvent.target.attributes.text);
		//alert('target ID: ' + dropEvent.target.attributes.objid);
			
		Cookies.set('ouid2',dropEvent.target.attributes.objid);
		Cookies.set('ouel2',dropEvent.target.id);
		Cookies.set('outy2',dropEvent.target.attributes.type);
			
		Ext.MessageBox.confirm('Confirm', 'Move to '+dropEvent.target.attributes.text+' ?',handleMoveOrg);

		return false;
    });
    
    function handleMoveOrg(btn) {
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
			url: '/{$config ['appName']}/organize-manager/move-node',
			method: 'POST',
			success: function(o){
				Ext.MessageBox.hide();
				var r = Ext.decode(o.responseText);
				var responseMsg = '';
				var result;
				if(r.success == 1) {
					responseMsg = '{$lang['common']['saved']}';
					result = true;
				} else {
					responseMsg = '{$lang['common']['error']}';
					result = false;
				}
									
				Ext.MessageBox.show({
					title: '{$lang ['org'] ['moveStructure']}',
					msg: responseMsg,
					buttons: Ext.MessageBox.OK,
					icon: Ext.MessageBox.INFO
				});
				orgTree.getNodeById(Cookies.get('ouel')).remove();
				orgTree.getNodeById(Cookies.get('ouel2')).reload();
			},
			params: {
				objIdFrom: Cookies.get('ouid')
				,objIdFromType: Cookies.get('outy')
				,objIDFromParent: Cookies.get('oup')
				,objIDFromParentType: Cookies.get('oupty')
				,objIdTo: Cookies.get('ouid2')
				,objIdToType: Cookies.get('outy2')
			}
		});
		                
		}
	}

    orgTree.render();
    </script>";
		//TODO: Drag Drop Move Implementation
	}
	
	/**
	 * action /add-ou สร้างหน่วยงาน
	 *
	 */
	public function addOuAction() {
		//global $conn;
		global $lang;
		global $sequence;
		if (! $sequence->isExists ( 'orgID' )) {
			$sequence->create ( 'orgID' );
		}
		
		$parentOrgID = $_POST ['parentOrgID'];
		$orgName = $_POST ['orgName'];
		$orgDesc = $_POST ['orgDesc'];
		$orgType = $_POST ['orgType'];
		if ($orgType == 'Organization Unit') {
			$orgType = 0;
		} else {
			$orgType = 1;
		}
		include_once ('Organize.Entity.php');
		$ouEntity = new OrganizeEntity ( );
		$orgID = $sequence->get ( 'orgID' );
		$ouEntity->f_org_id = $orgID;
		$ouEntity->f_org_name = $orgName;
		$ouEntity->f_org_desc = $orgDesc;
		$ouEntity->f_org_pid = $parentOrgID;
		$ouEntity->f_org_type = $orgType;
		$ouEntity->f_org_status = 1;
		$ouEntity->f_is_root = 0;
		$ouEntity->f_ext_code = UTFDecode ( $_POST ['extDocCode'] );
		$ouEntity->f_int_code = UTFDecode ( $_POST ['intDocCode'] );
		$ouEntity->Save ();
		//echo $ouEntity->f_org_id . '=>' . $ouEntity->f_org_name . '=>' . $ouEntity->f_org_desc . '=>' . $ouEntity->f_org_pid . '=>' . $ouEntity->f_org_type  . '=>' . $ouEntity->f_ext_code  . '=>' . $ouEntity->f_int_code;

		return $orgID;
	}
	
	/**
	 * action /add-role สร้างตำแหน่ง
	 *
	 */
	public function addRoleAction() {
		global $lang;
		global $sequence;
		global $config; 

		if (! $sequence->isExists ( 'roleID' )) {
			$sequence->create ( 'roleID' );
		}

		$roleOrgID = $_POST ['roleOrgID'];
		$rolePosID = $_POST ['rolePosID'];
		$rolePolicyID = $_POST ['rolePolicyID'];
		$roleName = UTFDecode ( $_POST ['roleName'] );
		$roleDesc = UTFDecode ( $_POST ['roleDesc'] );
		$roleGoverner = $_POST ['roleGoverner'];
		if ($roleGoverner == 'on') {
			$roleGoverner = 1;
		} else {
			$roleGoverner = 0;
		}
		
		if (! $config ['multipleGoverner']) {
			if ($roleGoverner == 1) {
				global $conn;
				$sqlUpdateRoleGoverner = "update tbl_role set f_is_governer = 0 where f_org_id = '{$roleOrgID}'";
				$conn->Execute ( $sqlUpdateRoleGoverner );
			}
		}

		//include_once ('Role.Entity.php');
		$roleEntity = new RoleEntity ( );
		$roleID = $sequence->get ( 'roleID' );
		$roleEntity->f_role_id = $roleID;
		//$roleEntity->f_role_name = UTFDecode ( $roleName );
		//$roleEntity->f_role_desc = UTFDecode ( $roleDesc );
		$roleEntity->f_role_name = $roleName;
		$roleEntity->f_role_desc = $roleDesc;
		$roleEntity->f_role_status = 1;
		$roleEntity->f_org_id = $roleOrgID;
		$roleEntity->f_pos_id = $rolePosID;
		$roleEntity->f_gp_id = $rolePolicyID;
		$roleEntity->f_is_governer = $roleGoverner;
		$roleEntity->Save ();

		return $roleID;

	}
	
	/**
	 * action /edit-role แก้ไขตำแหน่ง
	 *
	 */
	public function editRoleAction() {
		global $config;
		
		$id = $_POST ['roleEditOrgID'];
		$name = UTFDecode ( $_POST ['roleEditName'] );
		$desc = UTFDecode ( $_POST ['roleEditDesc'] );
		$policyID = $_POST ['roleEditPolicyID'];
		$roleGoverner = $_POST ['roleEditGoverner'];
		if ($roleGoverner == 'on') {
			$roleGoverner = 1;
		} else {
			$roleGoverner = 0;
		}
		
		$roleUnlimit = $_POST ['roleEditUnlimit'];
		if ($roleUnlimit == 'on') {
			$roleUnlimit = 1;
		} else {
			$roleUnlimit = 0;
		}

		$roleEditCommander = $_POST ['roleEditCommander'];
		if ($roleEditCommander == 'on') {
			$roleEditCommander = 1;
		} else {
			$roleEditCommander = 0;
		}
		
		//include_once ('Role.Entity.php');
		$roleEntity = new RoleEntity ( );
		$roleEntity->Load ( " f_role_id = '{$id}'" );
		$roleEntity->f_role_name = $name;
		$roleEntity->f_role_desc = $desc;
		$roleEntity->f_gp_id = $policyID;
		$roleEntity->f_is_governer = $roleGoverner;
		$roleEntity->f_unlimit_lookup = $roleUnlimit;
		$roleEntity->f_is_commander = $roleEditCommander;
		if (! $config ['multipleGoverner']) {
			if ($roleGoverner == 1) {
				global $conn;
				$sqlUpdateRoleGoverner = "update tbl_role set f_is_governer = 0 where f_org_id = '{$roleEntity->f_org_id}'";
				$conn->Execute ( $sqlUpdateRoleGoverner );
			}
		}
		$roleEntity->Update ();
	}
	
	/**
	 * action /map-role กำหนดคนลงตำแหน่ง
	 *
	 */
	public function mapRoleAction() {
		global $conn;
		$userSelected = $_POST ['selectedID'];
		$roleID = $_POST ['roleID'];
		$selectedUserArray = explode ( "|", $userSelected );
		foreach ( $selectedUserArray as $userID ) {
			$sql = "select count(f_acc_id) as count_default from tbl_passport where f_acc_id = '$userID' and f_default_role = 1";
			$rsCheckDefault = $conn->Execute ( $sql );
			$tmpCheckDefault = $rsCheckDefault->FetchNextObject ();
			if ($tmpCheckDefault->COUNT_DEFAULT > 0) {
				$defaultStatus = 0;
			} else {
				$defaultStatus = 1;
			}
			$sqlMapping = "insert into tbl_passport(f_role_id,f_acc_id,f_default_role) ";
			$sqlMapping .= " values('{$roleID}','{$userID}','$defaultStatus')";
			$conn->Execute ( $sqlMapping );
		}
	}
	
	/**
	 * action /delete-ou ลบหน่วยงาน
	 *
	 */
	public function deleteOuAction() {
		global $conn;
		$ouID = $_POST ['ouID'];
		$sql = "delete from tbl_organize where f_org_id = '$ouID'";
		$conn->Execute ( $sql );
	}
	
	/**
	 * action /delete-role ลบตำแหน่ง
	 *
	 */
	public function deleteRoleAction() {
		global $conn;
		$roleID = $_POST ['roleID'];
		$sql = "delete from tbl_role where f_role_id = '$roleID'";
		$conn->Execute ( $sql );
	}
	
	/**
	 * action /delete-mapping ลบคนออกจากตำแหน่ง
	 *
	 */
	public function deleteMappingAction() {
		global $conn;
		$roleID = $_POST ['roleID'];
		$accID = $_POST ['accID'];
		$sql = "Delete from tbl_passport where f_role_id = '{$roleID}' and f_acc_id = '{$accID}' ";
		$conn->Execute ( $sql );
	}
	
	/**
	 * action /modify-ou แก้ไขหน่วยงาน
	 *
	 */
	public function modifyOuAction() {
		global $conn;
		$orgID = $_POST ['editOrgID'];
		$orgName = UTFDecode ( $_POST ['editOrgName'] );
		$orgDesc = UTFDecode ( $_POST ['editOrgDesc'] );
		$intCode = UTFDecode ( $_POST ['editIntDocCode'] );
		$extCode = UTFDecode ( $_POST ['editExtDocCode'] );
		
		$allowInt = $_POST ['orgEditFlagAllowIntDocNo'];
		if ($allowInt == 'on') {
			$allowInt = 1;
		} else {
			$allowInt = 0;
		}
		
		$orgType = $_POST ['editOrgTypeHidden'];
		$sql = "update tbl_organize set f_ext_code='{$extCode}',f_int_code='{$intCode}', f_org_name = '$orgName' , f_org_desc = '$orgDesc' , f_allow_int_doc_no = $allowInt where f_org_id = '$orgID'";
		//$conn->debug = true;
		$conn->Execute ( $sql );
	
	}
	
	/**
	 * action /move-node ย้าย Node
	 *
	 */
	public function moveNodeAction() {
		$ouFromID = $_POST ['objIdFrom'];
		$ouFromType = $_POST ['objIdFromType'];
		$ouToID = $_POST ['objIdTo'];
		$ouToType = $_POST ['objIdToType'];
		$ouFromParentID = $_POST ['objIDFromParent'];
		$ouFromParentType = $_POST ['objIDFromParentType'];
		
		//if($ou)
		$success = Array ('success' => 1 );
		echo json_encode ( $success );
	}

	/**
	 *
	 * Created Date November 30, 2010
	 * Created By rasa
	 * action /add-organize-from-hr ทำการเพิ่มหน่วยงานจาก HR
	 *
	 */
	public function addOrganizeFromHrAction() {
		global $conn;
		$status = 1;

		if ($this->seedCheck($_REQUEST['sd'])) {
			if (!isset($_REQUEST['f_org_name'])
					//|| !isset($_REQUEST['f_org_desc'])
					//|| !isset($_REQUEST['f_ext_code'])
					//|| !isset($_REQUEST['f_int_code'])
					|| !isset($_REQUEST['f_org_pid'])
					|| !isset($_REQUEST['f_org_id'])) {
					$status = -1;
			}

			if ($status == 1) {
				$count_id = $this->checkMatchOrganizeAction($_REQUEST['f_org_id']);
				if ($count_id > 0) {
					$orgID	= $this->getOrganizeEcmAction($_REQUEST['f_org_id']);
					$orgPID	= $this->getOrganizeEcmAction($_REQUEST['f_org_pid']);
					if ($orgPID== '') {
						$orgPID	= 0;
					}

					if ($status ==1) {
						$orgName	= $_REQUEST['f_org_name'];
						$sql			= "update tbl_organize set f_org_name = '$orgName' , f_org_pid = '$orgPID'  where f_org_id = '$orgID'";
						$rs = $conn->Execute ( $sql );
						if (!$rs) {
							$status = 0;
						}
					}
				} else {
					$_POST['orgName']		= $_REQUEST['f_org_name'];
					$_POST['orgDesc']		= $_REQUEST['f_org_desc'];
					$_POST['extDocCode']	= $_REQUEST['f_ext_code'];
					$_POST['intDocCode']	= $_REQUEST['f_int_code'];
					$_POST ['orgType']		= 0;
					
					if ($_REQUEST['f_org_pid'] != 0) {
						$_POST['parentOrgID'] = $this->getOrganizeEcmAction($_REQUEST['f_org_pid']);
					} else {
						$_POST['parentOrgID'] = $_REQUEST['f_org_pid'];
					}

					if ($_POST['parentOrgID'] == '') {
						$_POST['parentOrgID'] = 0;
					}
				
					$ECMOrgID = $this->addOuAction();
					$HROrgID = $_REQUEST['f_org_id'];

					$sql = 'insert into tbl_match_organize (hr_org_id, ecm_org_id) values (\'' . $HROrgID . '\', ' . $ECMOrgID . ')';
					$rs = $conn->Execute ($sql);

					if (!$rs) {
						$status = -1;
					}
				}
			}
		} else {
			$status = -2;
		}
		echo $status;
		Logger:: SyncHRLog(1, $_REQUEST, $status);
	}

	/**
	 *
	 * Created Date November 30, 2010
	 * Created By rasa
	 * action /edit-organize-from-hr ทำการแก้ไขหน่วยงาน
	 *
	 */
	 public function editOrganizeFromHrAction() {
		global $conn;
		$status = 1;
		
		if ($this->seedCheck($_REQUEST['sd'])) {
			if (!isset($_REQUEST['f_org_name'])
					//|| !isset($_REQUEST['f_ext_code'])
					//|| !isset($_REQUEST['f_int_code'])
					//|| !isset($_REQUEST['f_org_desc'])
					//|| !isset($_REQUEST['f_org_pid'])
					|| !isset($_REQUEST['f_org_id'])) {
				$status = -1;
			}
			$desc			= $_REQUEST['f_org_desc'];
			$extCode	= $_REQUEST['f_ext_code'];
			$intCode		= $_REQUEST['f_int_code'];
			$name		= $_REQUEST['f_org_name'];
			$org_id		= $this->getOrganizeEcmAction($_REQUEST['f_org_id']);
			if ($org_id == '') {
				$status = 0;
			}

			$org_pid = $this->getOrganizeEcmAction($_REQUEST['f_org_pid']);
			if ($org_pid == '') {
				$org_pid = 0;
			}

			if ($status == 1) {
				$ouEntity = new OrganizeEntity ( );
				$ouEntity->Load ( " f_org_id = '{$org_id}'" );
				$ouEntity->f_org_pid		= $org_pid;
				$ouEntity->f_org_desc	= $desc;
				$ouEntity->f_ext_code	= $extCode;
				$ouEntity->f_int_code	= $intCode;
				$ouEntity->f_org_name = $name;

				$ouEntity->Update ();
			}

		} else {
			$status = -2;
		}
		echo $status;
		Logger:: SyncHRLog(2, $_REQUEST, $status);
		
	}

	/**
	 * action /edit-account-from-hr ทำการแก้ไขตำแหน่ง
	 *
	 */
	 public function editRoleFromHrAction() {
		global $conn;
		$status = 1;

		if ($this->seedCheck($_REQUEST['sd'])) {
			if (!isset($_REQUEST['f_role_name'])
					&& !isset($_REQUEST['f_role_desc'])
					&& !isset($_REQUEST['f_role_id'])) {
				$status = -1;
			}
			$name	= $_REQUEST['f_role_name'];
			$desc		= $_REQUEST['f_role_desc'];
			$arrRole	= $this->getRoleEcmAction($_REQUEST['f_role_id']);
			if (empty($arrRole)) {
				$status = 0;
			}

			if ($status == 1) {
				foreach ($arrRole as $value) {
					$roleID		= $value['ECM_ROLE_ID'];
					$roleEntity	= new RoleEntity ( );
					$roleEntity->Load ( " f_role_id = '{$roleID}'" );
					$roleEntity->f_role_name	= $name;
					$roleEntity->f_role_desc		= $desc;
					if (! $config ['multipleGoverner']) {
						if ($roleGoverner == 1) {
							global $conn;
							$sqlUpdateRoleGoverner = "update tbl_role set f_is_governer = 0 where f_org_id = '{$roleEntity->f_org_id}'";
							$conn->Execute ( $sqlUpdateRoleGoverner );
						}
					}
					$roleEntity->Update ();
				}
			}
		} else {
			$status = -2;
		}
		echo $status;
		Logger:: SyncHRLog(4, $_REQUEST, $status);
	}

	/**
	 * action /move-organize-from-hr ทำการย้ายหน่วยงาน
	 *
	 */
	 public function moveOrganizeFromHrAction() {
		global $conn;

		if (isset($_REQUEST['f_org_id']) && isset($_REQUEST['f_org_pid'])) {

			$org_id	= $this->getOrganizeEcmAction($_REQUEST['f_org_id']);
			$org_pid = $this->getOrganizeEcmAction($_REQUEST['f_org_pid']);

			$ouEntity = new OrganizeEntity ( );
			$ouEntity->Load ( " f_org_id = '{$org_id}'" );
			$ouEntity->f_org_pid = $org_pid;

			$ouEntity->Update ();
		}
	}

	/**
	 * action /move-role-from-hr ทำการย้ายตำแหน่ง
	 *
	 */
	 /*public function moveRoleFromHrAction() {
		global $conn;

		if (!isset($_REQUEST['f_org_id']) && !isset($_REQUEST['f_role_id'])) {
			$status = "Paramiter is enough.";
			echo $status;
			Logger:: SyncHRLog(5, $_REQUEST, $status);
			return false;
		}

			$org_id = $this->getOrganizeEcmAction($_REQUEST['f_org_id']);
			if ($org_id == '') {
				$status = "Not found this organize in ECM.";
				echo $status;
				Logger:: SyncHRLog(5, $_REQUEST, $status);
				return false;
			}
			$role_id = $this->getRoleEcmAction($_REQUEST['f_role_id']);
			if ($role_id == '') {
				$status = "Not found this role in ECM.";
				echo $status;
				Logger:: SyncHRLog(5, $_REQUEST, $status);
				return false;
			}

			//include_once ('Role.Entity.php');
			$roleEntity = new RoleEntity ( );
			$roleEntity->Load ( " f_role_id = '{$role_id}'" );
			$roleEntity->f_org_id = $org_id;

			$roleEntity->Update ();
			$status = "Move role is success.";
			echo $status;
			Logger:: SyncHRLog(5, $_REQUEST, $status);
		
	}*/

	public function getRoleEcmAction($roleId) {
		global $conn;
		$ECMRole = array();

		$sql	= "select ecm_role_id from tbl_match_role where hr_role_id='" . $roleId . "'";
		$role	= $conn->Execute ( $sql );
		while ($tmp = $role->FetchRow()) {
			$ECMRole[] = $tmp;
		}
		return $ECMRole;
	}

	public function getOrganizeEcmAction($organizeId) {
		global $conn;

		$sql		= "select ecm_org_id from tbl_match_organize where hr_org_id='" . $organizeId . "'";
		$rs		= $conn->Execute ( $sql );
		$tmp		= $rs->FetchRow();
		$ECMOrganize	= $tmp['ECM_ORG_ID'];
		
		return $ECMOrganize;
	}

	public function checkMatchOrganizeAction($organizeId) {
		global $conn;

		$sql			= "select count(*) as count_exp from tbl_match_organize where hr_org_id='" . $organizeId . "'";
		$rsCount		= $conn->Execute ( $sql );
		$tmpCount	= $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	public function checkMatchRoleAction($roleId) {
		global $conn;

		$sql			= "select count(*) as count_exp from tbl_match_role where hr_role_id='" . $roleId . "'";
		$rsCount		= $conn->Execute ( $sql );
		$tmpCount	= $rsCount->FetchNextObject ();
		return $tmpCount->COUNT_EXP;
	}

	public function seedCheck( $seed_str )
	{
		$salt = 'SLC*(-_-)*';
		return (substr(md5($salt.date('Ymd')), 0, 16) === $seed_str);
	}

	/*public function loadDataOrgAction () {

		$type = 'oci8';
		$host = false;
		$uid = 'OIC54';
		$pwd = 'OIC54';
		$database = 'pmis';

		$pmis = NewADOConnection($type);
		$pmis->PConnect ( $host, $uid, $pwd, $database );
		if(!$pmis){
			echo 'false';
			return false;
		}

		$sql = "select * from appv_sync_section order by f_org_id";
		$pmis->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $pmis->getAll($sql);
		//print_r($rs);die();
		foreach($rs as $value){

			echo $value['F_ORG_ID'] . '->'.$HRParentId = $this->findParentOrgCode ($value['F_ORG_ID']);
			if ($value['F_ORG_ID'] == 1) {
				continue;
			}elseif ($HRParentId == '00') {
				$HRParentId = 0;
			}
			echo ' = ' .$HRParentId.'<br>';

			$_REQUEST['f_org_name']		= $value['F_ORG_NAME'];
			$_REQUEST['f_org_desc']		= $value['F_ORG_DESC'];
			$_REQUEST['f_ext_code']		= $value['F_EXT_CODE'];
			$_REQUEST['f_int_code']		= $value['F_INT_CODE'];
			$_REQUEST['f_org_pid']			= $HRParentId;
			$_REQUEST['f_org_id']			= $value['F_ORG_ID'];
			//$this->addOrganizeFromHrAction();
			//print_r($_REQUEST);die();
		}
		echo 'success';
	}*/

	function loadCodeOrgAction () {
		global $conn;

		$wer = file_get_contents('d:/test.csv');
		$tmp = explode("\n", $wer);
		$data = array();
		foreach ($tmp as $value) {
						
			$data = explode(',', trim($value));
			if ($data[0] == 1) {
				continue;
			}
			$HRParentId = $this->findParentOrgCode ($data[0]);
			if ($data[0] == 1) {
				continue;
			}elseif ($HRParentId == '00') {
				$HRParentId = 0;
			}

			$allowInt = (strlen($data[2])>0) ? 1 : 0;
			
			if ($allowInt == 0) {

				$orgID	= $this->getOrganizeEcmAction($data[0]);

				$HRParentId = substr($HRParentId, 0, strlen($HRParentId) - 1);
				//echo $HRParentId.'<br>';

				$sql = "update tbl_organize set f_int_code = '" . $HRParentId . "', f_ext_code = '" . $HRParentId . "', f_allow_int_doc_no = 0 where f_org_id = " . $orgID;
				//$rs = $conn->Execute($sql);
			}
		}
	}

    function countZerolTail( $str )
	{
		$result = 0;
		for ( $i=strlen( $str )-1; $i>=0; $i--)
		{
			if ( $str[$i] == '0' ) 
				$result++;
			else
				return $result;
		}
		return $result;
	}

	function findParentOrgCode ($orgId) {
		$orgId = strval($orgId);
		$zeroTail = $this->countZerolTail( $orgId );
		$result = substr( $orgId, 0, (strlen($orgId) - $zeroTail) - 1);
		$result = $result.str_repeat('0',$zeroTail+1 );

		return $result;
	}
}