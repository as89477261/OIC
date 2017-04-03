<?php
/**
 * โปรแกมจัดการ Group Policy
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 */


class GroupPolicyManagerController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
	}
	
	/**
	 * สร้างหน้าจอเพิ่ม Group Policy
	 *
	 * @return string
	 */
	public function getAddGroupPolicyForm() {
		global $config;
		global $lang;
		
		$js = "var policyAddForm = new Ext.form.FormPanel({
			id: 'policyAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid: true,

			items: [{
				fieldLabel: '{$lang['gp']['name']}',
				name: 'policyName',
				allowBlank: false,
				width: 200
			},{name: 'policyDescription',
				xtype: 'textarea',
				fieldLabel: '{$lang['common']['desc']}',
				hideLabel: false,
				width: '200',
				height: '100'
			}],
			buttons: [{
				id: 'btnSavePolicy',
				text: '{$lang['common']['save']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
					
	    			policyAddWindow.hide();
	    			
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
	    				url: '/{$config ['appName']}/group-policy-manager/add-policy',
	    				method: 'POST',
	    				success: policyAddSuccess,
	    				failure: policyAddFailed,
	    				form: Ext.getCmp('policyAddForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelSavePolicy',
				iconCls: 'cancelIcon',
				text: '{$lang['common']['cancel']}',
				handler: function() {
					policyAddWindow.hide();
				}
			}]
		});";
		
		$js .= "var policyAddWindow = new Ext.Window({
			id: 'policyAddWindow',
			title: '{$lang['gp']['add']}',
			width: 350,
			height: 250,
			modal: true,
			minWidth: 400,
			minHeight: 250,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: policyAddForm,
			closable: false
		});
		
		";
		
		$js .= "function policyAddSuccess() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: '{$lang['gp']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPolicyAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function policyAddFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: '{$lang['gp']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPolicyAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function deletePolicySuccess() {
			Ext.MessageBox.hide();
			policyStore.load();
			Ext.MessageBox.show({
	    		title: '{$lang['gp']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPolicyAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function deletePolicyFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: '{$lang['gp']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPolicyAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function togglePolicySuccess() {
			Ext.MessageBox.hide();
			policyStore.load();
			Ext.MessageBox.show({
	    		title: '{$lang['gp']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPolicyAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function togglePolicyFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: '{$lang['gp']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPolicyAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function deleteSelectedPolicy(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/group-policy-manager/delete-policy',
	    				method: 'POST',
	    				success: deletePolicySuccess,
	    				failure: deletePolicyFailed,
	    				params: { id: gridPolicy.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function togglePolicyStatus(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/group-policy-manager/toggle-policy',
	    				method: 'POST',
	    				success: togglePolicySuccess,
	    				failure: togglePolicyFailed,
	    				params: { id: gridPolicy.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		return $js;
	}
	
	/**
	 * สร้างหน้าจอแก้ไข Group Policy
	 *
	 * @return string
	 */
	public function getModifyPolicyForm() {
		global $config;
		global $lang;
		
		$js = "var policyModifyForm = new Ext.form.FormPanel({
			id: 'policyModifyForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monotorValid: true,

			items: [{
				id: 'policyModID',
				fieldLabel: 'Policy ID',
				name: 'policyModID',
				inputType: 'hidden'
			},{
				id: 'policyModName',
				fieldLabel: '{$lang['gp']['name']}',
				name: 'policyModName',
				width: 200
			},{
				id: 'policyModDescription',
				name: 'policyModDescription',
				xtype: 'textarea',
				fieldLabel: '{$lang['common']['desc']}',
				hideLabel: false,
				width: '200',
				height: '100'
			}],
			buttons: [{
				id: 'btnUpdatePolicy',
				text: '{$lang['common']['update']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
					
	    			policyModifyWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgUpdateData',
			           	msg: '{$lang['common']['saving']}',
			           	progressText: '{$lang['common']['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       
	    			Ext.Ajax.request({
	    				url: '/{$config ['appName']}/group-policy-manager/modify-policy',
	    				method: 'POST',
	    				success: policyModifySuccess,
	    				failure: policyModifyFailed,
	    				form: Ext.getCmp('policyModifyForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelUpdatePolicy',
				iconCls: 'cancelIcon',
				text: '{$lang['common']['cancel']}',
				handler: function() {
					policyModifyWindow.hide();
				}
			}]
		});";
		
		$js .= "var policyModifyWindow = new Ext.Window({
			id: 'policyModifyWindow',
			title: '{$lang['gp']['manager']}',
			width: 500,
			height: 250,
			modal: true,
			minWidth: 300,
			minHeight: 250,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: policyModifyForm,
			closable: false
		});
		
		";
		
		$js .= "function policyModifySuccess() {
			Ext.MessageBox.hide();
			policyStore.load();
			Ext.MessageBox.show({
	    		title: '{$lang['gp']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnUpdatePolicy').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function policyModifyFailed() {
			Ext.MessageBox.hide();
			
			Ext.MessageBox.show({
	    		title: 'Policy Manager',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnUpdatePolicy').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * action /get-ui หน้าจอแสดง UI ของโปรแกรม Group Policy Manager
	 *
	 */
	public function getUiAction() {
		global $config;
		global $store;
		global $lang;
		
		checkSessionPortlet();
		
		$policyAddFormJS = $this->getAddGroupPolicyForm ();
		$policyModifyFormJS = $this->getModifyPolicyForm ();
		$policyDataStore = $store->getDataStore ( 'policy' );
		$policyComboDataStore = $store->getDataStore ( 'policy', 'policyComboStore' );
		$secretLevelStore = $store->getDataStore ( 'secretLevel' );
		
		/* prepare DIV For UI */
		echo "<div id=\"groupPolicyUIToolbarDiv\" display=\"inline\"></div>";
		echo "<div id=\"groupPolicyUIDiv\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		$policyAddFormJS
		$policyModifyFormJS
		
		$secretLevelStore
		$policyDataStore
		$policyComboDataStore
		
		policyStore.setDefaultSort('id', 'desc');
		policyStore.load();
		policyComboStore.load();
		
		
		function renderPolicyStatus(value, p, record){
			if(record.data.status == 1 ){
				return '{$lang['common']['enable']}';
			} else {
				return '{$lang['common']['disable']}';
			}
		}
		
		var cmPolicy = new Ext.grid.ColumnModel([{
			   id: 'id',
	           header: \"{$lang['gp']['name']}\",
	           dataIndex: 'name',
	           sortable: false,
	           width: 120
	        },{
	           header: \"{$lang['common']['desc']}\",
	           dataIndex: 'description',
	           width: 120,
	           align: 'left',
	           sortable: false
	        },{
	           header: \"{$lang['common']['status']}\",
	           dataIndex: 'status',
	           width: 95,
	           renderer: renderPolicyStatus
		    }
		]);
		
		var gridPolicy = new Ext.grid.GridPanel({
			id: 'gridPolicy',
			store: policyStore,
			tbar: new Ext.Toolbar({
					id: 'adminPolicyToolbar',
					height: 25				
				}),
			autoExpandMax: true,
			cm: cmPolicy,
			viewConfig: {
				forceFit: true
			},
			loadMask: true,
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
			frame: false,
			iconCls:'icon-grid',
			renderTo:'groupPolicyUIDiv'
		});
		
		var tbPolicy = Ext.getCmp('adminPolicyToolbar');
		
	 	tbPolicy.add({
	 		id: 'btnPolicyAdd',
            text:'{$lang['gp']['add']}',
            iconCls: 'addIcon',
            handler: function() {
            	policyAddForm.getForm().reset();
				policyAddWindow.show();
			}
        },{
        	id: 'btnModifyPolicy',
            text:'{$lang['gp']['edit']}',
            iconCls: 'editIcon',
            disabled: true,
			handler: function() {
            	policyModifyWindow.show();
            	policyModifyForm.getForm().setValues([
            		{id:'policyModID',value: gridPolicy.getSelectionModel().getSelected().get('id')},
					{id:'policyModName',value: gridPolicy.getSelectionModel().getSelected().get('name')},
					{id:'policyModDescription',value: gridPolicy.getSelectionModel().getSelected().get('description')}
            	]);
			}
        },{
        	id: 'btnDeletePolicy',
            text:'{$lang['gp']['delete']}',
            iconCls: 'deleteIcon',
            disabled: true,
			handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['gp']['delete']} [ '+gridPolicy.getSelectionModel().getSelected().get('name')+']?', deleteSelectedPolicy);
			}
        },{
        	id: 'btnTogglePolicyStatus',
            text:'{$lang['common']['toggle']}',
            iconCls: 'toggleIcon',
            disabled: true,
			handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['common']['toggle']} [ '+gridPolicy.getSelectionModel().getSelected().get('name')+']?', togglePolicyStatus);
			}
        },{
            text:'{$lang['common']['refresh']}',
            iconCls: 'refreshIcon',
            handler: function() {
            	policyStore.load();
            	policyComboStore.load();
			}
        });
        
        
        gridPolicy.on({
		'rowclick' : function() {
			Ext.getCmp('btnModifyPolicy').enable();
			Ext.getCmp('btnDeletePolicy').enable();
			Ext.getCmp('btnTogglePolicyStatus').enable();
			/*
			Ext.getCmp('ecmPropertyGrid').setTitle('Group Policy Editor');
			Ext.getCmp('ecmPropertyGrid').on('propertychange',function(source,recordId,value,oldValue) {
				Ext.Ajax.request({
	    			url: '/{$config ['appName']}/group-policy-manager/set-policy',
	    			method: 'POST',
	    			params: {
	    				policyID: gridPolicy.getSelectionModel().getSelected().get('id'),
	    				policyProperty: recordId,
	    				policyValue: value
					}
	    		});
			});
			loadPolicyPropertyGrid(gridPolicy.getSelectionModel().getSelected().get('id'));
			*/
		},
		scope: this
		});
		
		
		gridPolicy.on({
		'rowdblclick' : function() {
			var tabMain = Ext.getCmp('tpAdmin');
			//var tabID = 'gpEdit_'+gridPolicy.getSelectionModel().getSelected().get('id');
			var tabID = 'gpEdit_';
			
			if(!tabMain.findById( tabID)) {
				tabMain.add({
					id: tabID,
					title: 'แก้ไขนโยบาย['+gridPolicy.getSelectionModel().getSelected().get('name')+']',
					iconCls: 'workflowIcon',
					autoLoad: {
						url: '/{$config ['appName']}/group-policy-editor/index'
						,params: {
							gpID: gridPolicy.getSelectionModel().getSelected().get('id')
						}
						, scripts: true
					},
					closable:true
				}).show();
			} else {
				tabMain.findById(tabID).show();
			}
		},
		scope: this
		});		
		
		gridPolicy.render();
		policyStore.load();
		
		function loadPolicyPropertyGrid(id) {
			var policyInfoStore = new Ext.data.Store({
				autoLoad: true,
				proxy: new Ext.data.ScriptTagProxy({
					url: '/{$config ['appName']}/data-store/policy-property?policyID='+id
				}),
				
				reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'Name', 'Value','Group'
						]
					}),
					// turn on remote sorting
				remoteSort: true					
			});
			
			policyInfoStore.on('load',function(store,records,options) {
				var Property = Ext.data.Record.create([
					{name: 'name'},
					{name: 'value'},
					{name: 'group'}
				]);
				var tempStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, Property)});
				
				Ext.getCmp('ecmPropertyGrid').setSource(tempStore.data.items);
				for(i=0;i<store.getCount();i++) {
					if(records[i].get('id') == 929 || records[i].get('id') == 947) {
						var rec = new Ext.grid.PropertyRecord({
							id: records[i].get('id'),
							recordId: records[i].get('id'),
					    	name: records[i].get('Name'),
					    	value: records[i].get('Value'),
					    	group: records[i].get('Group')
						},records[i].get('id'));
					} else {
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
					}
					Ext.getCmp('ecmPropertyGrid').store.add(rec);
				}
			});
			
			policyInfoStore.load();
		}
        
		</script>";
	}
	
	/**
	 * action /set-policy ทำการกำหนด Policy Property
	 *
	 */
	public function setPolicyAction() {
		global $conn;
		global $store;
		$policyID = $_POST ['policyID'];
		$policyProperty = $_POST ['policyProperty'];
		$policyValue = $_POST ['policyValue'];
        $tmpPolicyValue = $policyValue;
		$policyField = $store->getPolicyMapping ( $policyProperty, 'id' );
		Logger::debug ( "Policy ID : {$policyProperty}" );
		Logger::debug ( "Policy Value : {$policyValue}" );
		if (! in_array ( $policyProperty, array (929, 947 ) )) {
			if (is_bool ( $policyValue )) {
				if ($policyValue) {
					$policyValue = 1;
				}
				if (!$policyValue) {
					$policyValue = 0;
				}
			} else {
				if ($policyValue == 'true') {
					$policyValue = 1;
				}
				if ($policyValue == 'false') {
					$policyValue = 0;
				}
			}
		}
        
        if (in_array ( (int)$policyProperty, array (998 ) )) {   
            $policyValue = $tmpPolicyValue;
        }
		$sql = "update tbl_group_policy set {$policyField} = '$policyValue' where f_gp_id = '{$policyID}'";
		Logger::debug ( $sql );
		$conn->Execute ( $sql );
	}
	
	/**
	 * action /add-policy ทำการสร้าง Group Policy
	 *
	 */
	public function addPolicyAction() {
		global $conn;
		global $sequence;
		
		$array_post_index = array ('policyName' => FILTER_SANITIZE_STRING, 'policyDescription' => FILTER_SANITIZE_STRING );
		$postData = filter_input_array ( INPUT_POST, $array_post_index );
		
		if (! $sequence->isExists ( 'policyID' )) {
			$sequence->create ( 'policyID' );
		}
		$policyID = $sequence->get ( 'policyID' );
		$policyName = ICONV ( 'UTF-8', 'TIS-620', $postData ['policyName'] );
		$policyDesc = ICONV ( 'UTF-8', 'TIS-620', $postData ['policyDescription'] );
		$sqlInsertPolicy = "insert into tbl_group_policy(f_gp_id,f_gp_name,f_gp_desc,f_gp_status)";
		$sqlInsertPolicy .= " values('{$policyID}','{$policyName}','{$policyDesc}','1')";
		$conn->Execute ( $sqlInsertPolicy );
	}
	
	/**
	 * action /modifu-policy ทำการแก้ไข Group Policy
	 *
	 */
	public function modifyPolicyAction() {
		global $conn;
		
		$array_post_index = array ('policyModID' => FILTER_SANITIZE_STRING, 'policyModName' => FILTER_SANITIZE_STRING, 'policyModDescription' => FILTER_SANITIZE_STRING );
		$postData = filter_input_array ( INPUT_POST, $array_post_index );
		
		$policyName = ICONV ( 'UTF-8', 'TIS-620', $postData ['policyModName'] );
		$policyDesc = ICONV ( 'UTF-8', 'TIS-620', $postData ['policyModDescription'] );
		
		$sqlUpdatePolicy = "update tbl_group_policy set ";
		$sqlUpdatePolicy .= " f_gp_name = '{$policyName}'";
		$sqlUpdatePolicy .= " ,f_gp_desc = '{$policyDesc}'";
		$sqlUpdatePolicy .= " where f_gp_id = '{$postData['policyModID']}'";
		$conn->Execute ( $sqlUpdatePolicy );
	
	}
	
	/**
	 * action /delete-policy ทำการลบ Group Policy
	 *
	 */
	public function deletePolicyAction() {
		global $conn;
		$policyID = $_POST ['id'];
		$sqlDeletePolicy = "delete from tbl_group_policy where f_gp_id = '{$policyID}'";
		$conn->Execute ( $sqlDeletePolicy );
	}
	
	/**
	 * action /toggle-policy ทำการ disable/enable Group Policy
	 *
	 */
	public function togglePolicyAction() {
		global $conn;
		$policyID = $_POST ['id'];
		$sqlGetPolicyStatus = "select f_gp_status from tbl_group_policy where f_gp_id = '{$policyID}'";
		$rsGetPolicy = $conn->Execute ( $sqlGetPolicyStatus );
		$policy = $rsGetPolicy->FetchNextObject ();
		$newStatus = 0;
		if ($policy->F_GP_STATUS == 0) {
			$newStatus = 1;
		} else {
			$newStatus = 0;
		}
		$sqlUpdatePolicy = "update tbl_group_policy set f_gp_status = '$newStatus' where f_gp_id = '{$policyID}'";
		
		$conn->Execute ( $sqlUpdatePolicy );
	}

}
