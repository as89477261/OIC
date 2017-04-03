<?php
/**
 * โปรแกรมจัดการประเภทข้อมูลเบื้องต้น
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Master
 *
 */
class MasterControlController extends ECMController {
	/**
	 * สร้างฟอร์มสร้างประเภทมาสเตอร์
	 *
	 * @return string
	 */
	private function getCreateFormJS() {
		global $config;
		global $lang;
		
		$storeName = "masterControlStore";
		
		$js = "
		var frmCreateMasterType = new Ext.form.FormPanel({
			id: 'frmCreateMasterType',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid: true,

			items: [{
				fieldLabel: '{$lang['master'] ['typeCode']}',
				allowBlank: false,
				name: 'newMasterTypeCode',
				width: 250
			},{
				id: 'newMasterTypeName',
				fieldLabel: '{$lang['master'] ['typeName']}',
				allowBlank: false,
				name: 'newMasterTypeName',
				width: 250
			},{
				id: 'newMasterTypeDesc',
				fieldLabel: '{$lang['common'] ['desc']}',
				name: 'newMasterTypeDesc',
				xtype: 'textarea',
				width:250
			}],
			buttons: [{
				text: '{$lang['common']['save']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
	    			windowCreateMasterType.hide();
	    			
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
		    			url: '/{$config ['appName']}/master-control/create',
		    			method: 'POST',
		    			success: function(o) {
		    				Ext.MessageBox.hide();
		    				Ext.getCmp('btnEditMaster').disable();
								Ext.getCmp('btnDeleteMaster').disable();
								Ext.getCmp('btnManageMaster').disable();
								gridMasterTypeAdmin.getSelectionModel().clearSelections();
		    				var r = Ext.decode(o.responseText);
		    				if(r.success == 1) {
		    					
		    					{$storeName}.reload();
							}
						},
		    			form: Ext.getCmp('frmCreateMasterType').getForm().getEl()
		    		});
				}
			},{
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					windowCreateMasterType.hide();
				}
			}]
		});
		";
		
		$js .= "var windowCreateMasterType = new Ext.Window({
			id: 'windowCreateMasterType',
			title: '{$lang['master'] ['create']}',
			width: 400,
			height: 200,
			minWidth: 400,
			minHeight: 200,
			layout: 'fit',
			resizable: false,
			plain:true,
			modal: true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmCreateMasterType,
			closable: false,
			keys: {
				key: Ext.EventObject.ESC,
				fn: function (){
					windowCreateMasterType.hide();
				},
				scope: this
			}
		});
		
		";
		return $js;
	}
	/**
	 * สร้างหน้าจอแก้ไขประเภทมาสเตอร์
	 *
	 * @return string
	 */
	private function getEditFormJS() {
		global $config;
		global $lang;
		$storeName = "masterControlStore";
		$js = "
		var frmEditMasterType = new Ext.form.FormPanel({
			id: 'frmEditMasterType',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid: true,

			items: [{
				fieldLabel: '{$lang['master'] ['typeCode']}',
				allowBlank: false,
				inputType: 'hidden',
				id: 'editMasterTypeOldCode',
				name: 'editMasterTypeOldCode'
			},{
				fieldLabel: '{$lang['master'] ['typeCode']}',
				allowBlank: false,
				name: 'editMasterTypeCode',
				readOnly: true,
				width: 250
			},{
				id: 'editMasterTypeName',
				fieldLabel: '{$lang['master'] ['typeName']}',
				allowBlank: false,
				name: 'editMasterTypeName',
				width: 250
			},{
				id: 'editMasterTypeDesc',
				fieldLabel: '{$lang['common'] ['desc']}',
				name: 'editMasterTypeDesc',
				xtype: 'textarea',
				width:250
			}],
			buttons: [{
				text: '{$lang['common']['save']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
	    			windowEditMasterType.hide();
	    			
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
		    				url: '/{$config ['appName']}/master-control/edit',
		    				method: 'POST',
		    				success: function(o) {
		    					Ext.MessageBox.hide();
		    					Ext.getCmp('btnEditMaster').disable();
								Ext.getCmp('btnDeleteMaster').disable();
								Ext.getCmp('btnManageMaster').disable();
								gridMasterTypeAdmin.getSelectionModel().clearSelections();
								var r = Ext.decode(o.responseText);
								if(r.success == 1) {
										{$storeName}.reload();
								}
		    				},
		    				form: Ext.getCmp('frmEditMasterType').getForm().getEl()
		    			});
	    		}
			},{
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					windowEditMasterType.hide();
				}
			}]
		});
		";
		
		$js .= "var windowEditMasterType = new Ext.Window({
			id: 'windowEditMasterType',
			title: '{$lang['master'] ['edit']}',
			width: 400,
			height: 200,
			minWidth: 400,
			minHeight: 200,
			layout: 'fit',
			resizable: false,
			plain:true,
			modal: true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmEditMasterType,
			closable: false,
			keys: {
				key: Ext.EventObject.ESC,
				fn: function (){
					windowEditMasterType.hide();
				},
				scope: this
			}
		});
		
		";
		return $js;
	}
	
	/**
	 * action /get-ui แสดงหน้าจอจัดการประเภทมาสเตอร์
	 *
	 */
	public function getUiAction() {
		global $config;
		global $store;
		global $lang;
		
		checkSessionPortlet();
		
		$storeName = "masterControlStore";
		$ds = $store->getDataStore('masterType',$storeName);
		/* prepare DIV For UI */
		echo "<div id=\"masterControlUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		{$this->getCreateFormJS()}
		{$this->getEditFormJS()}
		
		{$ds}
		
		var cmMasterControl = new Ext.grid.ColumnModel([{
			   id: 'id',
	           header: \"{$lang['master'] ['typeCode']}\",
	           dataIndex: 'id',
	           sortable: false,
	           width: '5%'
	        },{
	           header: \"{$lang['master'] ['typeName']}\",
	           dataIndex: 'name',
	           sortable: false,
	           width: '35%'
	        },{
	           header: \"{$lang ['common'] ['desc']}\",
	           dataIndex: 'desc',
	           align: 'left',
	           sortable: false,
	           width: '50%'
	      
	        },{
	           header: \"{$lang['common']['status']}\",
	           dataIndex: 'status',
	           width: '10%',
	           renderer: renderLockingStatus
		    }
		]);
		
		var gridMasterTypeAdmin = new Ext.grid.GridPanel({
			id: 'gridMasterType',
			store: {$storeName},
			tbar: new Ext.Toolbar({
					id: 'masterControlToolbar',
					height: 25				
				}),
			autoExpandMax: true,
			cm: cmMasterControl,
			viewConfig: {
				forceFit: true
			},
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
			frame: false,
			iconCls:'icon-grid',
			loadMask: true,
			renderTo:'masterControlUIDiv'
		});
		
		Ext.getCmp('masterControlToolbar').add(
		{
	 		id: 'btnAddNewMaster',
            text:'{$lang ['common'] ['create']}',
            iconCls: 'addIcon',
            handler: function() {
            	frmCreateMasterType.getForm().reset();
            	windowCreateMasterType.show();
			}
        },{
	 		id: 'btnEditMaster',
            text:'{$lang ['common'] ['modify']}',
            iconCls: 'editIcon',
            disabled: true,
            handler: function() {
            	windowEditMasterType.show();
            	frmEditMasterType.getForm().setValues([
            		{id:'editMasterTypeOldCode',value: gridMasterTypeAdmin.getSelectionModel().getSelected().get('id')},
            		{id:'editMasterTypeCode',value: gridMasterTypeAdmin.getSelectionModel().getSelected().get('id')},
					{id:'editMasterTypeName',value: gridMasterTypeAdmin.getSelectionModel().getSelected().get('name')},
					{id:'editMasterTypeDesc',value: gridMasterTypeAdmin.getSelectionModel().getSelected().get('desc')}
            	]);
			}
        },{
	 		id: 'btnDeleteMaster',
            text:'{$lang ['common'] ['delete']}',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function() {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['master']['delete']} [ '+gridMasterTypeAdmin.getSelectionModel().getSelected().get('name')+']?', deleteMasterType);
			}
        },{
	 		id: 'btnManageMaster',
            text:'{$lang ['common'] ['manage']}',
            iconCls: 'toggleIcon',
            disabled: true,
            handler: function() {
            	openMasterData('tabMaster_'+gridMasterTypeAdmin.getSelectionModel().getSelected().get('id'),gridMasterTypeAdmin.getSelectionModel().getSelected().get('name'),gridMasterTypeAdmin.getSelectionModel().getSelected().get('id')); 
			}
        },{
        	text: '|',
        	disabled: true
		},{
	 		id: 'btnRefreshMaster',
            text:'{$lang ['common'] ['refresh']}',
            iconCls: 'refreshIcon',
            disabled: false,
            handler: function() {
            	Ext.getCmp('btnEditMaster').disable();
				Ext.getCmp('btnDeleteMaster').disable();
				Ext.getCmp('btnManageMaster').disable();
				gridMasterTypeAdmin.getSelectionModel().clearSelections();
				{$storeName}.reload();
			}
        }
		);
		
		gridMasterTypeAdmin.on('rowclick',function() {
				Ext.getCmp('btnEditMaster').enable();
				Ext.getCmp('btnManageMaster').enable();
				if(gridMasterTypeAdmin.getSelectionModel().getSelected().get('status')) {
					Ext.getCmp('btnDeleteMaster').enable();
				} else {
					Ext.getCmp('btnDeleteMaster').disable();
				}
		},this);
		
		gridMasterTypeAdmin.on('rowdblclick',function() {
				openMasterData('tabMaster_'+gridMasterTypeAdmin.getSelectionModel().getSelected().get('id'),gridMasterTypeAdmin.getSelectionModel().getSelected().get('name'),gridMasterTypeAdmin.getSelectionModel().getSelected().get('id'));
		},this);
		
		gridMasterTypeAdmin.render();
		{$storeName}.load();
		
		function deleteMasterType (btn) {
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
	    					url: '/{$config ['appName']}/master-control/delete-data',
	    					method: 'POST',
	    					success: function (o) {
								var r= Ext.decode(o.responseText);
								Ext.MessageBox.hide();
								Ext.getCmp('btnEditMaster').disable();
								Ext.getCmp('btnDeleteMaster').disable();
								Ext.getCmp('btnManageMaster').disable();
								gridMasterTypeAdmin.getSelectionModel().clearSelections();
								if(r.success ==1 ){
									{$storeName}.reload();
								}
	    					},
	    					params: { id: gridMasterTypeAdmin.getSelectionModel().getSelected().get('id') }
	    				});
					}
				}
		
		function openMasterData(tabID,tabTitle,masid) {
			var tabMain = Ext.getCmp('tpAdmin');
			if(!tabMain.findById( tabID)) {
				tabMain.add({
					id: tabID,
					title: 'Master Data : ['+tabTitle+']',
					iconCls: 'workflowIcon',
					autoLoad: {
						url: '/{$config ['appName']}/master-data/get-ui'
						,params: {
							id: masid
						}
						, scripts: true
					},
					closable:true
				}).show();
			} else {
				tabMain.findById(tabID).show();
			}
		}
		</script>";
	}
	/**
	 * action /create สร้างประเภทมาสเตอร์
	 *
	 */
	public function createAction(){
		$newMasterTypeCode = $_POST['newMasterTypeCode'];
		$newMasterTypeDesc = $_POST['newMasterTypeDesc'];
		$newMasterTypeName = $_POST['newMasterTypeName'];
		//include_once 'MasterType.Entity.php';
		$result = Array();
		$master = new MasterTypeEntity();
		if(!$master->Load("f_ctl_id = '$newMasterTypeCode'")) {
			$masterNew = New MasterTypeEntity();
			$masterNew->f_ctl_id = $newMasterTypeCode;
			$masterNew->f_mas_name = UTFDecode($newMasterTypeName);
			$masterNew->f_mas_description = UTFDecode($newMasterTypeDesc);
			$masterNew->f_status = 1;
			$masterNew->Save();
			$result['success'] =1;
		} else {
			$result['success'] =0;
		}
		
		echo json_encode($result);
	}
	
	/**
	 * action /edit แก้ไขประเภทมาสเตอร์
	 *
	 */
	public function editAction(){
		$oldMasterTypeCode = $_POST['editMasterTypeOldCode'];
		$newMasterTypeCode = $_POST['editMasterTypeCode'];
		$newMasterTypeDesc = $_POST['editMasterTypeDesc'];
		$newMasterTypeName = $_POST['editMasterTypeName'];
		//include_once 'MasterType.Entity.php';
		$result = Array();
		$master = new MasterTypeEntity();
		if(!$master->Load("f_ctl_id = '$oldMasterTypeCode'")) {
			
			$result['success'] =0;
		} else {
			$master->f_mas_name = UTFDecode($newMasterTypeName);
			$master->f_mas_description = UTFDecode($newMasterTypeDesc);
			$master->Update();
			$result['success'] =1;
		}
		
		
		echo json_encode($result);
	}
	
	/**
	 * action /delete ลบมาสเตอร์
	 *
	 */
	public function deleteDataAction(){
		$id = $_POST['id'];
		//include_once 'MasterType.Entity.php';
		$result = Array();
		$master = new MasterTypeEntity();
		if(!$master->Load("f_ctl_id = '$id'")) {
			
			$result['success'] =0;
		} else {
			$master->Delete();
			$result['success'] =1;
		}
		
		echo json_encode($result);
	}
}
