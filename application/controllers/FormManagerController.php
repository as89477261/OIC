<?php
/**
 * โปรแกรมหลักระบบจัดการฟอร์ม
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Form
 * 
 *
 */
class FormManagerController extends Zend_Controller_Action {
	/**
	 * สร้างหน้าจอสร้างแบบฟอร์ม
	 *
	 * @return string
	 */
	public function getAddFormForm() {
		global $config;
		$js = "var formAddForm = new Ext.form.FormPanel({
			id: 'formAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				id: 'frmName',
				fieldLabel: 'Form Name',
				allowBlank: false,
				name: 'frmName',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'frmDesc',
				name: 'frmDesc',
				xtype: 'textarea',
				fieldLabel: 'Description',
				hideLabel: false,
				width: '100%'
			}, { id: 'frmWorkflow',
				fieldLabel: 'Workflow',
				name: 'frmWorkflow',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmDocFlow',
				fieldLabel: 'DocFlow',
				name: 'frmDocFlow',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmDMS',
				fieldLabel: 'DMS',
				name: 'frmDMS',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmKB',
				fieldLabel: 'KB',
				name: 'frmKB',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmComment',
				fieldLabel: 'Comment',
				name: 'frmComment',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmAttach',
				fieldLabel: 'Attach',
				name: 'frmAttach',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}]
		});";
		
		$js .= "var formAddWindow = new Ext.Window({
			id: 'formAddWindow',
			title: 'Add New Form',
			width: 500,
			height: 325,
			minWidth: 300,
			minHeight: 325,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: formAddForm,
			closable: false,
			buttons: [{
				id: 'btnSaveForm',
				text: 'Save Form',
				handler: function() {
					
	    			formAddWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/form-manager/add-form',
		    			method: 'POST',
		    			success: FormAddSuccess,
		    			failure: FormAddFailed,
		    			form: Ext.getCmp('formAddForm').getForm().getEl()
		    		});
	    		}
			},{
				id: 'btnCancelSaveForm',
				text: 'Cancel',
				handler: function() {
					formAddWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function FormAddSuccess() {
			Ext.MessageBox.hide();
			
			formStore.reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Account Added!',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function FormAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to add Account!',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank deleted successfully */
		$js .= "function deleteFormSuccess() {
			Ext.MessageBox.hide();
			
			//accountStore.load({params:{start:0, limit:25}});
			accountStore.reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Account Deleted!',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank undeleted or deleted unsuccessfully */
		$js .= "function deleteFormFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to delete Account!',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank toggled successfully */
		$js .= "function toggleFormSuccess() {
			Ext.MessageBox.hide();
			
			//accountStore.load({params:{start:0, limit:25}});
			accountStore.reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Account Deleted!',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank toggled unsuccessfully */
		$js .= "function toggleFormFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to delete Account!',
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
	    				success: deleteFormSuccess,
	    				failure: deleteFormFailed,
	    				params: { id: gridAccount.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function toggleFormStatus(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/account-manager/toggle-account',
	    				method: 'POST',
	    				success: toggleFormSuccess,
	    				failure: toggleFormFailed,
	    				params: { id: gridAccount.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		return $js;
	}
	
	/**
	 * สร้างหน้าจอแก้ไขฟอร์ม
	 *
	 * @return string
	 */
	public function getModifyFormForm() {
		global $config;
		$js = "var formModifyForm = new Ext.form.FormPanel({
			id: 'formModifyForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',

			items: [{
				id: 'frmModID',
				inputType: 'hidden',
				fieldLabel: 'Form ID',
				allowBlank: false,
				name: 'frmModID',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'frmModName',
				fieldLabel: 'Form Name',
				allowBlank: false,
				name: 'frmModName',
				anchor:'100%'  // anchor width by percentage
			},{
				id: 'frmModDesc',
				name: 'frmModDesc',
				xtype: 'textarea',
				fieldLabel: 'Description',
				hideLabel: false,
				width: '100%'
			}, { id: 'frmModWorkflow',
				fieldLabel: 'Workflow',
				name: 'frmModWorkflow',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmModDocFlow',
				fieldLabel: 'DocFlow',
				name: 'frmModDocFlow',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmModDMS',
				fieldLabel: 'DMS',
				name: 'frmModDMS',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmModKB',
				fieldLabel: 'KB',
				name: 'frmModKB',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmModComment',
				fieldLabel: 'Comment',
				name: 'frmModComment',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}, { id: 'frmModAttach',
				fieldLabel: 'Attach',
				name: 'frmModAttach',
				xtype: 'checkbox',
				checked: false,
				stateful: false
			}]
		});";
		
		$js .= "var formModifyWindow = new Ext.Window({
			id: 'formModifyWindow',
			title: 'Edit Form',
			width: 500,
			height: 325,
			minWidth: 300,
			minHeight: 325,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: formModifyForm,
			closable: false,
			buttons: [{
				id: 'btnSaveForm',
				text: 'Save Form',
				handler: function() {
					
	    			formModifyWindow.hide();
	    			
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
		    			url: '/{$config ['appName']}/form-manager/edit-form',
		    			method: 'POST',
		    			success: FormModifySuccess,
		    			failure: FormModifyFailed,
		    			form: Ext.getCmp('formModifyForm').getForm().getEl()
		    		});
	    		}
			},{
				id: 'btnCancelSaveForm',
				text: 'Cancel',
				handler: function() {
					formModifyWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function FormModifySuccess() {
			Ext.MessageBox.hide();
			
			formStore.reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Account Added!',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function FormModifyFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to add Account!',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnSaveAccount').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		return $js;
	}
	
	/**
	 * action /get-ui โปรแกรมแสดงหน้าจอจัดการฟอร์ม
	 *
	 */
	public function getUiAction() {
		global $config;
		global $lang;
		global $store;
		
		checkSessionPortlet();
		
		$formAddFormJS = $this->getAddFormForm ();
		$formModifyFormJS = $this->getModifyFormForm ();
		$formStore = $store->getDataStore ( 'form' );
		
		/* prepare DIV For UI */
		echo "<div id=\"formUIToolbarDiv\" display=\"inline\"></div>";
		echo "<div id=\"formUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		$formStore
		
		$formAddFormJS
		$formModifyFormJS
		
		formStore.load();
		
	    //accountStore.setDefaultSort('id', 'asc');
	

	    var cmForm = new Ext.grid.ColumnModel([{
	           id: 'id', 
	           header: \"Name\",
	           dataIndex: 'name',
	           width: 200
	        },{
	           header: \"Description\",
	           dataIndex: 'description',
	           width: 250,
	           align: 'left'
	        },{
	           header: \"Version\",
	           dataIndex: 'version',
	           width: 35,
	           align: 'right'
	        },{
	           header: \"Workflow\",
	           dataIndex: 'allowwf',
	           width: 35,
		       renderer: renderStatus
		    },{
		       header: \"DocFlow\",
		       dataIndex: 'allowdf',
		       width: 35,
		       renderer: renderStatus
		    },{
		       header: \"DMS\",
		       dataIndex: 'allowdms',
		       width: 35,
		       renderer: renderStatus
		    },{
		       header: \"KB\",
		       dataIndex: 'allowkb',
		       width: 35,
		       renderer: renderStatus
		    },{
		       header: \"Comment\",
		       dataIndex: 'allowcomment',
		       width: 35,
		       renderer: renderStatus
		    },{
		       header: \"Attach\",
		       dataIndex: 'allowattach',
		       width: 35,
		       renderer: renderStatus
		    },{
		       header: \"Status\",
		       dataIndex: 'status',
		       width: 35,
		       renderer: renderStatus
		    }
		]);
	
	    cmForm.defaultSortable = true;
	
	    var gridForm = new Ext.grid.GridPanel({
	        //el:'topic-grid',
	        //
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
	        //autoWidth: true,
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: formStore,
	        
	        tbar: new Ext.Toolbar({
				id: 'adminFormToolbar',
				height: 25				
			}),
			cm: cmForm,
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
	        loadMask: true,
	        renderTo: 'formUIDiv',
	        viewConfig: {
	            forceFit:true,
	            enableRowBody:true,
	            showPreview: false
	        },
	        bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: formStore,
	            displayInfo: true,
	            displayMsg: 'Displaying Forms {0} - {1} of {2}',
	            emptyMsg: \"No Forms to display\"
	        })
	    });
    
    	var tbForm = Ext.getCmp('adminFormToolbar');
		
	 	tbForm.add({
	 		id: 'btnFormAdd',
            text:'Create New Form',
            iconCls: 'addIcon',
            handler: function() {
            	formAddForm.getForm().reset();
				formAddWindow.show();
			}
        },{
        	id: 'btnFormModify',
            text:'Modify Selected Form',
            iconCls: 'editIcon',
            disabled: true, 
            handler: function() {
            	formModifyWindow.show();
            	formModifyForm.getForm().setValues([
            		{id:'frmModID',value: gridForm.getSelectionModel().getSelected().get('id')},
					{id:'frmModName',value: gridForm.getSelectionModel().getSelected().get('name')},
					{id:'frmModDesc',value: gridForm.getSelectionModel().getSelected().get('description')},
					{id:'frmModWorkflow',value: gridForm.getSelectionModel().getSelected().get('allowwf')},
					{id:'frmModDocFlow',value: gridForm.getSelectionModel().getSelected().get('allowdf')},
					{id:'frmModDMS',value: gridForm.getSelectionModel().getSelected().get('allowdms')},
					{id:'frmModKB',value: gridForm.getSelectionModel().getSelected().get('allowkb')},
					{id:'frmModAttach',value: gridForm.getSelectionModel().getSelected().get('allowattach')},
					{id:'frmModComment',value: gridForm.getSelectionModel().getSelected().get('allowcomment')}
            	]);
			}
        },{
        	id: 'btnDeleteForm',
            text:'Delete Form',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+gridAccount.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	id: 'btnToggleFormStatus',
            text:'Disable/Enable Form',
            iconCls: 'toggleIcon',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Toggle Account [ '+gridForm.getSelectionModel().getSelected().get('name')+'] Status?', toggleAccountStatus);
			}
        },{
        	id: 'btnFormStructure',
            text: 'Form Structure',
            iconCls: 'structureIcon',
            disabled: true,
			handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	var tabFrmStructID = 'tabFormStructure_' + gridForm.getSelectionModel().getSelected().get('id');
			    	var tabFrmStructTitle = 'Modify Form Structure : ' + gridForm.getSelectionModel().getSelected().get('name');
			    	if(!tabMain.findById( tabFrmStructID)) {
						tabMain.add({
							id: tabFrmStructID,
							title: tabFrmStructTitle,
							iconCls: 'workflowIcon',
							autoLoad: {
								url: '/{$config ['appName']}/form-structure-manager/get-ui', 
								params: {
									formID: gridForm.getSelectionModel().getSelected().get('id'),
									formVersion: gridForm.getSelectionModel().getSelected().get('version')
								},
								scripts: true
							},
							closable:true
						}).show();
					} else {
						tabMain.findById(tabFrmStructID).show();
					}
			}
        },{
        	id: 'btnFormDesigner',
            text:'Form Designer',
            iconCls: 'designIcon',
        	disabled: true, 
            handler: function(){
			    	var tabMain = Ext.getCmp('tpAdmin');
			    	var tabFrmDesgID = 'tabFormDesigner_' + gridForm.getSelectionModel().getSelected().get('id');
			    	var tabFrmDesgTitle = 'Visual Form Designer : ' + gridForm.getSelectionModel().getSelected().get('name');
			    	if(!tabMain.findById( tabFrmDesgID)) {
						tabMain.add({
							id: tabFrmDesgID,
							title: tabFrmDesgTitle,
							iconCls: 'workflowIcon',
							autoLoad: {
								url: '/{$config ['appName']}/form-designer/get-ui', 
								params: {
									formID: gridForm.getSelectionModel().getSelected().get('id'),
									formVersion: gridForm.getSelectionModel().getSelected().get('version')
								},
								scripts: true
							},
							closable:true
						}).show();
					} else {
						tabMain.findById(tabFrmDesgID).show();
					}
			}
        },{
            text:'Refresh View',
            iconCls: 'refreshIcon',
            handler: function(){
            	formStore.reload();
			}
        });

	    // render it
	    gridForm.render();
	
	    // trigger the data store load
	    formStore.load({params:{start:0, limit:25}});
	    //gridForm.colModel.renderCellDelegate = renderCell.createDelegate(gridAccount.colModel);
	    
	    
	    gridForm.on({
			'rowclick' : function() {
				
				Ext.getCmp('btnFormModify').enable();
				Ext.getCmp('btnDeleteForm').enable();
				Ext.getCmp('btnFormDesigner').enable();
				Ext.getCmp('btnToggleFormStatus').enable();
				Ext.getCmp('btnFormStructure').enable();
			},
			scope: this
		});
		
	    function toggleDetails(btn, pressed){
	        var view = gridAccount.getView();
	        view.showPreview = pressed;
	        view.refresh();
	    }
	    
    
		</script>";
	}
	
	/**
	 * action /add-form ทำการสร้างฟอร์ม
	 *
	 */
	public function addFormAction() {
		//global $config;
		global $sequence;
		
		if (! $sequence->isExists ( 'formID' )) {
			$sequence->create ( 'formID' );
		}
		
		//include_once ('Form.Entity.php');
		
		$frmName = $_POST ['frmName'];
		$frmDesc = $_POST ['frmDesc'];
		if (array_key_exists ( 'frmWorkflow', $_POST ) && $_POST ['frmWorkflow'] == 'on') {
			$frmWorkflow = 1;
		} else {
			$frmWorkflow = 0;
		}
		
		if (array_key_exists ( 'frmDocFlow', $_POST ) && $_POST ['frmDocFlow'] == 'on') {
			$frmDocFlow = 1;
		} else {
			$frmDocFlow = 0;
		}
		
		if (array_key_exists ( 'frmDMS', $_POST ) && $_POST ['frmDMS'] == 'on') {
			$frmDMS = 1;
		} else {
			$frmDMS = 0;
		}
		
		if (array_key_exists ( 'frmKB', $_POST ) && $_POST ['frmKB'] == 'on') {
			$frmKB = 1;
		} else {
			$frmKB = 0;
		}
		
		if (array_key_exists ( 'frmComment', $_POST ) && $_POST ['frmComment'] == 'on') {
			$frmComment = 1;
		} else {
			$frmComment = 0;
		}
		
		if (array_key_exists ( 'frmAttach', $_POST ) && $_POST ['frmAttach'] == 'on') {
			$frmAttach = 1;
		} else {
			$frmAttach = 0;
		}
		
		$formEntity = new FormEntity ( );
		$formEntity->f_form_id = $sequence->get ( 'formID' );
		$formEntity->f_status = 1;
		$formEntity->f_version = 1;
		$formEntity->f_form_name = UTFDecode( $frmName );
		$formEntity->f_form_desc = UTFDecode( $frmDesc );
		$formEntity->f_allow_wf = $frmWorkflow;
		$formEntity->f_allow_df = $frmDocFlow;
		$formEntity->f_allow_dms = $frmDMS;
		$formEntity->f_allow_kb = $frmKB;
		$formEntity->f_allow_comment = $frmComment;
		$formEntity->f_allow_attach = $frmAttach;
		$formEntity->Save ();
	}
	
	/**
	 * action /edit-form ทำการแก้ไขฟอร์ม
	 *
	 */
	public function editFormAction() {
		//global $config;
		global $sequence;
		
		if (! $sequence->isExists ( 'formID' )) {
			$sequence->create ( 'formID' );
		}
		
		//include_once ('Form.Entity.php');
		
		$frmID = $_POST ['frmModID'];
		$frmName = $_POST ['frmModName'];
		$frmDesc = $_POST ['frmModDesc'];
		if (array_key_exists ( 'frmModWorkflow', $_POST ) && $_POST ['frmModWorkflow'] == 'on') {
			$frmWorkflow = 1;
		} else {
			$frmWorkflow = 0;
		}
		
		if (array_key_exists ( 'frmModDocFlow', $_POST ) && $_POST ['frmModDocFlow'] == 'on') {
			$frmDocFlow = 1;
		} else {
			$frmDocFlow = 0;
		}
		
		if (array_key_exists ( 'frmModDMS', $_POST ) && $_POST ['frmModDMS'] == 'on') {
			$frmDMS = 1;
		} else {
			$frmDMS = 0;
		}
		
		if (array_key_exists ( 'frmModKB', $_POST ) && $_POST ['frmModKB'] == 'on') {
			$frmKB = 1;
		} else {
			$frmKB = 0;
		}
		
		if (array_key_exists ( 'frmModComment', $_POST ) && $_POST ['frmModComment'] == 'on') {
			$frmComment = 1;
		} else {
			$frmComment = 0;
		}
		
		if (array_key_exists ( 'frmModAttach', $_POST ) && $_POST ['frmModAttach'] == 'on') {
			$frmAttach = 1;
		} else {
			$frmAttach = 0;
		}
		
		$formEntity = new FormEntity ( );
		$formEntity->Load ( "f_form_id = '{$frmID}'" );
		$formEntity->f_form_name = UTFDecode( $frmName );
		$formEntity->f_form_desc = UTFDecode( $frmDesc );
		$formEntity->f_allow_wf = $frmWorkflow;
		$formEntity->f_allow_df = $frmDocFlow;
		$formEntity->f_allow_dms = $frmDMS;
		$formEntity->f_allow_kb = $frmKB;
		$formEntity->f_allow_comment = $frmComment;
		$formEntity->f_allow_attach = $frmAttach;
		$formEntity->Update ();
	}

	/**
	 * action /delete-form ลบฟอร์ม
	 *
	 */
	public function deleteFormAction() {
		$formEntity = new FormEntity ( );
	}
	
}
