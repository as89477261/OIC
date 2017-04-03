<?php
/**
 * โปรแกรมจัดการระดับตำแหน่ง
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */
class PositionMasterManagerController extends Zend_Controller_Action {
	/**
	 * สร้างฟอร์มสร้างระดับตำแหน่ง
	 *
	 * @return string
	 */	
	private function getPositionAddForm() {
		global $lang;
		global $config;
		$js = "var positionAddForm = new Ext.form.FormPanel({
			id: 'positionAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid:true,

			items: [{
				fieldLabel: '{$lang['pos']['name']}',
				name: 'positionName',
				width: 200
			},new Ext.form.ComboBox({
				name: 'positionCreateSequence',
				fieldLabel: '{$lang['pos']['seq']}',
				store: posCreateSequence,
				displayField:'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				value: 1,
				emptyText:'Select Create Sequence',
				selectOnFocus:true
			}),new Ext.form.ComboBox({
				name: 'positionCreateStack',
				fieldLabel: '{$lang['pos']['stack']}',
				store: positionComboStore,
				displayField:'name',
				valueField: 'id',
				typeAhead: true,
				mode: 'local',
				triggerAction: 'all',
				emptyText:'Select Position Stack',
				selectOnFocus:true
			}),new Ext.form.ComboBox({
				name: 'positionStatus',
				fieldLabel: '{$lang['common']['status']}',
				store: posCreateStatus,
				displayField:'name',
				valueField: 'value',
				typeAhead: true,
				mode: 'local',
				triggerAction: 'all',
				emptyText:'Select Position Status',
				value: 0,
				selectOnFocus:true
			}),{
				name: 'positionDescription',
				xtype: 'textarea',
				fieldLabel: '{$lang['common']['desc']}',
				hideLabel: false,
				width: '200',
				height: '100'
			}],
			buttons: [{
				id: 'btnSavePosition',
				text: '{$lang['common']['save']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
					
	    			positionAddWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgSavePositionData',
			           	msg: '{$lang['common']['saving']}',
			           	progressText: '{$lang['common']['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       
	    			Ext.Ajax.request({
	    				url: '/{$config ['appName']}/position-master-manager/add-position',
	    				method: 'POST',
	    				success: positionAddSuccess,
	    				failure: positionAddFailed,
	    				form: Ext.getCmp('positionAddForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelSavePosition',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					positionAddWindow.hide();
				}
			}]
		});";
		
		$js .= "var positionAddWindow = new Ext.Window({
			id: 'positionAddWindow',
			title: '{$lang['pos']['add']}',
			width: 500,
			height: 300,
			modal: true,
			minWidth: 300,
			minHeight: 300,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: positionAddForm,
			closable: false
		});
		
		";
		
		$js .= "function positionAddSuccess() {
			Ext.MessageBox.hide();
			
			
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function positionAddFailed() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank deleted successfully */
		$js .= "function deletePositionSuccess() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank undeleted or deleted unsuccessfully */
		$js .= "function deletePositionFailed() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank Moved-Up successfully */
		$js .= "function movePositionUpSuccess() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank Moved-Up unsuccessfully */
		$js .= "function movePositionUpFailed() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank Moved-Down successfully */
		$js .= "function movePositionDownSuccess() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank Moved-Down unsuccessfully */
		$js .= "function movePositionDownFailed() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank toggled successfully */
		$js .= "function togglePositionSuccess() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank toggled unsuccessfully */
		$js .= "function togglePositionFailed() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function deleteSelectedPosition(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/position-master-manager/delete-position',
	    				method: 'POST',
	    				success: deletePositionSuccess,
	    				failure: deletePositionFailed,
	    				params: { id: gridPosition.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function moveUpSelectedPosition(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/position-master-manager/move-position-up',
	    				method: 'POST',
	    				success: movePositionUpSuccess,
	    				failure: movePositionUpFailed,
	    				params: { id: gridPosition.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function moveDownSelectedPosition(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/position-master-manager/move-position-down',
	    				method: 'POST',
	    				success: movePositionDownSuccess,
	    				failure: movePositionDownFailed,
	    				params: { id: gridPosition.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function togglePositionStatus(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/position-master-manager/toggle-position',
	    				method: 'POST',
	    				success: togglePositionSuccess,
	    				failure: togglePositionFailed,
	    				params: { id: gridPosition.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		return $js;
	}
	
	/**
	 * สร้างฟอร์มแก้ไขระดับตำแหน่ง
	 *
	 * @return string
	 */
	private function getPositionMasterModify() {
		global $config;
		global $lang;
		
		$js = "var posModifyForm = new Ext.form.FormPanel({
			id: 'posModifyForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid: true,

			items: [{
				id: 'posModID',
				fieldLabel: 'Position ID',
				name: 'posModID',
				inputType: 'hidden'
			},{
				id: 'posModName',
				fieldLabel: '{$lang['pos']['name']}',
				name: 'posModName',
				width: 200
			},{
				id: 'posModDescription',
				name: 'posModDescription',
				xtype: 'textarea',
				fieldLabel: '{$lang['common']['desc']}',
				hideLabel: false,
				width: '200',
				height: '100'
			}],buttons: [{
				id: 'btnUpdatePosition',
				text: '{$lang['common']['update']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
					
	    			posModifyWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgUpdatePosData',
			           	msg: '{$lang['common']['saving']}',
			           	progressText: '{$lang['common']['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       
	    			Ext.Ajax.request({
	    				url: '/{$config ['appName']}/position-master-manager/modify-position',
	    				method: 'POST',
	    				success: posModifySuccess,
	    				failure: posModifyFailed,
	    				form: Ext.getCmp('posModifyForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelUpdatePosition',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					posModifyWindow.hide();
				}
			}]
		});";
		
		$js .= "var posModifyWindow = new Ext.Window({
			id: 'posModifyWindow',
			title: '{$lang['pos']['edit']}',
			width: 500,
			height: 250,
			minWidth: 300,
			minHeight: 250,
			modal: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: posModifyForm,
			closable: false
			
		});
		
		";
		
		$js .= "function posModifySuccess() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function posModifyFailed() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	
	/**
	 * สร้างฟอร์มทำการ Mapping ระดับตำแหน่งกับระดับขั้น
	 *
	 */
	public function getPositionMasterMappingDetailAction() {
		global $conn;
		
		echo "<div id=\"mapRankDiv\"></div>";
		$sqlRank = "select * from tbl_rank order by f_rank_level asc";
		$rsRank = $conn->Execute ( $sqlRank );
		$items = "[";
		
		$items .= "{id: 'posToMapID',";
		$items .= "name: 'posToMapID',";
		$items .= "inputType: 'hidden',";
		$items .= "value: gridPosition.getSelectionModel().getSelected().get('id'),";
		$items .= "stateful: false,";
		$items .= "width: '95%'}";
		
		foreach ( $rsRank as $rank ) {
			checkKeyCase($rank);
			if ($rank ['f_rank_name'] != '') {
				$items .= ",";
				$sqlCheckMapped = "select count(*) as count_check from tbl_mapping where f_map_class ='pos2rank' and f_master_id='{$_COOKIE['posID']}' and f_slave_id = '{$rank['f_rank_id']}'";
				$rsCheckMapped = $conn->Execute ( $sqlCheckMapped );
				$tmpCheckMapped = $rsCheckMapped->FetchNextObject ();
				if ($tmpCheckMapped->COUNT_CHECK > 0) {
					$checked = 'true';
				} else {
					$checked = 'false';
				}
				
				$rankID = $rank ['f_rank_id'];
				$rankName = $rank ['f_rank_name'];
				$items .= "{id: 'rank_{$rankID}',";
				$items .= "fieldLabel: '{$rankName}',";
				$items .= "name: 'rank_{$rankID}',";
				$items .= "xtype: 'checkbox',";
				$items .= "checked: {$checked},";
				$items .= "stateful: false,";
				$items .= "anchor: '100%'}";
			}
		}
		$items .= "]";
		$js = "<script type=\"text/javascript\">
		var posMappingForm = new Ext.form.FormPanel({
			id: 'posMappingForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			items: {$items},
			renderTo: 'mapRankDiv'
		});
		</script>";
		echo $js;
	}
	
	/**
	 * สร้างฟอร์มทำการ Mapping ระดับตำแหน่งกับระดับขั้น
	 *
	 * @return string
	 */
	private function getPositionMasterMapping() {
		global $config;
		global $lang;
		
		$js = "var posMappingWindow = new Ext.Window({
			id: 'posMappingWindow',
			title: '{$lang['pos']['map']}',
			width: 500,
			height: 250,
			minWidth: 300,
			minHeight: 250,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			autoLoad: {url: '/{$config ['appName']}/position-master-manager/get-position-master-mapping-detail', scripts: true},
			closable: false,
			buttons: [{
				id: 'btnMappingPosition',
				text: '{$lang['pos']['map']}',
				iconCls: 'saveIcon',
				handler: function() {
					
	    			posMappingWindow.hide();
	    			
	    			Ext.MessageBox.show({
	    				id: 'dlgMapPosData',
			           	msg: '{$lang['common']['saving']}',
			           	progressText: '{$lang['common']['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       
	    			Ext.Ajax.request({
	    				url: '/{$config ['appName']}/position-master-manager/map-position',
	    				method: 'POST',
	    				success: posMappingSuccess,
	    				failure: posMappingFailed,
	    				form: Ext.getCmp('posMappingForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelMappingPosition',
				iconCls: 'cancelIcon',
				text: '{$lang['common']['cancel']}',
				handler: function() {
					posMappingWindow.hide();
				}
			}]
		});
		
		";
		
		$js .= "function posMappingSuccess() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function posMappingFailed() {
			Ext.MessageBox.hide();
			
			positionStore.load();
			positionComboStore.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['pos']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnPositionAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		return $js;
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
	
	}
	
	/**
	 * action /get-ui ทำการแสดงหน้าจอจัดการระดับตำแหน่ง
	 *
	 */
	public function getUiAction() {
		global $config;
		global $store;
		global $lang;
		checkSessionPortlet();
		$positionDataStore = $store->getDataStore ( 'position' );
		$positionComboDataStore = $store->getDataStore ( 'position', 'positionComboStore' );
		$createSequence = $store->getDataStore ( 'createSequence', 'posCreateSequence' );
		$createStatus = $store->getDataStore ( 'createStatus', 'posCreateStatus' );
		$positionAddFormJS = $this->getPositionAddForm ();
		$positionModifyFormJS = $this->getPositionMasterModify ();
		$positionMappingForm = $this->getPositionMasterMapping ();
		
		/* prepare DIV For UI */
		echo "<div id=\"positionMasterUIToolbarDiv\" display=\"inline\"></div>";
		echo "<div id=\"positionMasterUIDiv\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		
		function renderPositionStatus(value, p, record){
			if(record.data.status == 1 ){
				return '{$lang['common']['enable']}';
			} else {
				return '{$lang['common']['disable']}';
			}
		}
		
		var cmPosition = new Ext.grid.ColumnModel([{
			   id: 'id',
	           header: \"{$lang['pos']['name']}\",
	           dataIndex: 'name',
	           sortable: false,
	           width: 120
	        },{
	           header: \"{$lang['common']['desc']}\",
	           dataIndex: 'description',
	           sortable: false,
	           width: 300
	        },{
	           header: \"{$lang['common']['level']}\",
	           dataIndex: 'level',
	           width: 120,
	           align: 'left',
	           sortable: false
	        },{
	           header: \"{$lang['common']['status']}\",
	           dataIndex: 'status',
	           width: 120,
	           renderer: renderPositionStatus
		    }
		]);
		
		$createSequence
		$createStatus
		$positionDataStore
		$positionComboDataStore
		
		$positionAddFormJS
		$positionModifyFormJS
		$positionMappingForm
		
		
		positionStore.setDefaultSort('id', 'desc');
		positionStore.load();
		positionComboStore.load();
		
		var gridPosition = new Ext.grid.GridPanel({
			id: 'gridPosition',
			store: positionStore,
			tbar: new Ext.Toolbar({
					id: 'adminPositionToolbar',
					height: 25				
				}),
			autoExpandMax: true,
			cm: cmPosition,
			viewConfig: {
				forceFit: true
			},
			loadMask: true,
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
			frame: false,
			iconCls:'icon-grid',
			renderTo:'positionMasterUIDiv'
		});
		
		var tbPosition = Ext.getCmp('adminPositionToolbar');
		
	 	tbPosition.add({
	 		id: 'btnPositionAdd',
            text:'{$lang['pos']['add']}',
            iconCls: 'addIcon',
            handler: function() {
            	positionAddForm.getForm().reset();
				positionAddWindow.show();
			}
        },{
        	id: 'btnModifyPosition',
            text:'{$lang['pos']['edit']}',
            iconCls: 'editIcon',
            disabled: true,
			handler: function() {
            	posModifyWindow.show();
            	posModifyForm.getForm().setValues([
            		{id:'posModID',value: gridPosition.getSelectionModel().getSelected().get('id')},
					{id:'posModName',value: gridPosition.getSelectionModel().getSelected().get('name')},
					{id:'posModDescription',value: gridPosition.getSelectionModel().getSelected().get('description')}
            	]);
			}
        },{
        	id: 'btnMapPosition',
            text:'{$lang['pos']['map']}',
            iconCls: 'mappingIcon',
            disabled: true,
            handler:  function() {
            	if(!Ext.getCmp('posMappingForm')) {
            	 	posMappingWindow.show();
				} else {
					posMappingForm.getUpdater().update({url: '/{$config ['appName']}/position-master-manager/get-position-master-mapping-detail', scripts: true});
					posMappingWindow.show();
				}
			}
        },{
        	id: 'btnDeletePosition',
            text:'{$lang['pos']['delete']}',
            iconCls: 'deleteIcon',
            disabled: true,
			handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['pos']['delete']} [ '+gridPosition.getSelectionModel().getSelected().get('name')+']?', deleteSelectedPosition);
			}
        },{
        	id: 'btnMovePositionUp',
            text:'{$lang['common']['moveUp']}',
            iconCls: 'upIcon',
            disabled: true,
			handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['common']['moveUp']} [ '+gridPosition.getSelectionModel().getSelected().get('name')+']?', moveUpSelectedPosition);
			}
        },{
        	id: 'btnMovePositionDown',
            text:'{$lang['common']['moveDown']}',
            iconCls: 'downIcon',
            disabled: true,
			handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['common']['moveDown']} [ '+gridPosition.getSelectionModel().getSelected().get('name')+']?', moveDownSelectedPosition);
			}
        },{
        	id: 'btnTogglePositionStatus',
            text:'{$lang['common']['toggle']}',
            iconCls: 'toggleIcon',
            disabled: true,
			handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['common']['toggle']} [ '+gridPosition.getSelectionModel().getSelected().get('name')+']?', togglePositionStatus);
			}
        },{
            text:'Refresh View',
            text:'{$lang['common']['refresh']}',
            iconCls: 'refreshIcon',
            handler: function() {
            	positionStore.load();
            	positionComboStore.load();
			}
        });
        
        gridPosition.on({
		'rowclick' : function() {
			Ext.getCmp('btnModifyPosition').enable();
			Ext.getCmp('btnMapPosition').enable();
			Ext.getCmp('btnDeletePosition').enable();
			Ext.getCmp('btnMovePositionUp').enable();
			Ext.getCmp('btnMovePositionDown').enable();
			Ext.getCmp('btnTogglePositionStatus').enable();
			Cookies.set('posID',gridPosition.getSelectionModel().getSelected().get('id'));
		},
		scope: this
		});
		
		function openMappingWindow() {
			posMappingWindow.show();
		}
        gridPosition.render();
		positionStore.load();
		</script>";
	}
	
	/**
	 * action /add-position สร้างระดับตำแหน่ง
	 *
	 */
	public function addPositionAction() {
		global $conn;
		global $sequence;
		
		$array_post_index = array ('positionName' => FILTER_SANITIZE_STRING, 'positionCreateSequence' => FILTER_SANITIZE_STRING, 'positionCreateStack' => FILTER_SANITIZE_STRING, 'positionStatus' => FILTER_SANITIZE_STRING, 'positionDescription' => FILTER_SANITIZE_STRING );
		$postData = filter_input_array ( INPUT_POST, $array_post_index );
		
		if (! $sequence->isExists ( 'positionID' )) {
			$sequence->create ( 'positionID' );
		}
		
		if ($postData ['positionCreateSequence'] == 'Before') {
			if (trim ( $postData ['positionCreateStack'] ) != 'Select Position Stack') {
				$postData ['positionCreateSequence'] = 'Before';
			
			} else {
				$postData ['positionCreateSequence'] = 'Top';
			}
		}
		
		if ($postData ['positionCreateSequence'] == 'After') {
			if (trim ( $postData ['positionCreateStack'] ) != 'Select Position Stack') {
				$postData ['positionCreateSequence'] = 'After';
			} else {
				$postData ['positionCreateSequence'] = 'Bottom';
			}
		}
		
		switch ($postData ['positionCreateSequence']) {
			case 'Before' :
				$posEntity = new PositionMasterEntity ( );
				$posStack = UTFDecode( $postData ['positionCreateStack'] );
				$posEntity->Load ( "f_pos_name = '{$posStack}'" );
				$posStackLevel = $posEntity->f_pos_level;
				$sqlUpdatePos = "update tbl_position_master set f_pos_level = f_pos_level +1 where f_pos_level >= {$posStackLevel}";
				unset ( $posEntity );
				
				$posEntity = new PositionMasterEntity ( );
				$posEntity->f_pos_id = $sequence->get ( 'positionID' );
				$posEntity->f_pos_name = UTFDecode( $postData ['positionName'] );
				$posEntity->f_pos_level = $posStackLevel;
				$posEntity->f_pos_desc = UTFDecode( $postData ['positionDescription'] );
				$posEntity->f_pos_status = 1;
				
				$conn->StartTrans ();
				$conn->Execute ( $sqlUpdatePos );
				$posEntity->Save ();
				$conn->CompleteTrans ();
				break;
			
			case 'Top' :
				$sqlUpdatePosition = "update tbl_position_master set f_pos_level = f_pos_level +1";
				
				$posEntity = new PositionMasterEntity ( );
				$posEntity->f_pos_id = $sequence->get ( 'positionID' );
				$posEntity->f_pos_name = UTFDecode( $postData ['positionName'] );
				$posEntity->f_pos_level = 1;
				$posEntity->f_pos_desc = UTFDecode( $postData ['positionDescription'] );
				$posEntity->f_pos_status = 1;
				
				$conn->StartTrans ();
				$conn->Execute ( $sqlUpdatePosition );
				$posEntity->Save ();
				$conn->CompleteTrans ();
				break;
			
			case 'After' :
				$posEntity = new PositionMasterEntity ( );
				$posStack = UTFDecode( $postData ['positionCreateStack'] );
				$posEntity->Load ( "f_pos_name = '{$posStack}'" );
				$posStackLevel = $posEntity->f_pos_level + 1;
				$sqlUpdatePos = "update tbl_position_master set f_pos_level = f_pos_level +1 where f_pos_level >= {$posStackLevel}";
				unset ( $posEntity );
				
				$posEntity = new PositionMasterEntity ( );
				$posEntity->f_pos_id = $sequence->get ( 'positionID' );
				$posEntity->f_pos_name = UTFDecode( $postData ['positionName'] );
				$posEntity->f_pos_level = $posStackLevel;
				$posEntity->f_pos_desc = UTFDecode( $postData ['positionDescription'] );
				$posEntity->f_pos_status = 1;
				
				$conn->StartTrans ();
				$conn->Execute ( $sqlUpdatePos );
				$posEntity->Save ();
				$conn->CompleteTrans ();
				break;
			
			case 'Bottom' :
				$sqlGetMaxLevel = "select max(f_pos_level)+1 as max_level from tbl_position_master";
				$rsGetMaxLevel = $conn->Execute ( $sqlGetMaxLevel );
				$maxLevel = $rsGetMaxLevel->FetchNextObject ();
				
				$posEntity = new PositionMasterEntity ( );
				$posEntity->f_pos_id = $sequence->get ( 'positionID' );
				$posEntity->f_pos_name = UTFDecode( $postData ['positionName'] );
				$posEntity->f_pos_level = $maxLevel->MAX_LEVEL;
				$posEntity->f_pos_desc = UTFDecode( $postData ['positionDescription'] );
				$posEntity->f_pos_status = 1;
				
				$conn->StartTrans ();
				$posEntity->Save ();
				$conn->CompleteTrans ();
				break;
		}
	}
	
	/**
	 * แก้ไขระดับตำแหน่ง
	 *
	 */
	public function modifyPositionAction() {
		global $conn;
		$posID = $_POST ['posModID'];
		$posName = UTFDecode( $_POST ['posModName'] );
		$posDescription = UTFDecode( $_POST ['posModDescription'] );
		
		$posEntity = new PositionMasterEntity ( );
		$posEntity->Load ( "f_pos_id = '{$posID}'" );
		$posEntity->f_pos_name = $posName;
		$posEntity->f_pos_desc = $posDescription;
		
		$conn->StartTrans ();
		$posEntity->Update ();
		$conn->CompleteTrans ();
	}
	
	/**
	 * ลบระดับตำแหน่ง
	 *
	 */
	public function deletePositionAction() {
		global $conn;
		
		$posID = $_POST ['id'];
		
		$posEntity = new PositionMasterEntity ( );
		$posEntity->Load ( "f_pos_id = '{$posID}'" );
		$posLevel = $posEntity->f_pos_level;
		
		$sqlUpdateRemainPos = "update tbl_position_master set f_pos_level = f_pos_level-1 where f_pos_level > {$posLevel}";
		$conn->StartTrans ();
		$posEntity->Delete ();
		$conn->Execute ( $sqlUpdateRemainPos );
		$conn->CompleteTrans ();
		unset ( $posEntity );
	}
	
	/**
	 * เลื่อนระดับตำแหน่งขึ้น
	 *
	 */
	public function movePositionUpAction() {
		global $conn;
		$posID = $_POST ['id'];
		
		$posEntity = new PositionMasterEntity ( );
		$posEntity->Load ( "f_pos_id = '{$posID}'" );
		$selfPosLevel = $posEntity->f_pos_level;
		if ($selfPosLevel > 1) {
			$upperPosLevel = $posEntity->f_pos_level - 1;
			$posEntity2 = new PositionMasterEntity ( );
			$posEntity2->Load ( "f_pos_level = '{$upperPosLevel}'" );
			
			$posEntity->f_pos_level = $upperPosLevel;
			$posEntity2->f_pos_level = $selfPosLevel;
			
			$conn->StartTrans ();
			$posEntity->Update ();
			$posEntity2->Update ();
			$conn->CompleteTrans ();
		}
	}
	
	/**
	 * ลดระดับตำแหน่งลง
	 *
	 */
	public function movePositionDownAction() {
		global $conn;
		$posID = $_POST ['id'];
		
		$sqlGetMaxLevel = "select max(f_rank_level) as max_level from tbl_rank";
		$rsGetMaxLevel = $conn->Execute ( $sqlGetMaxLevel );
		$maxLevel = $rsGetMaxLevel->FetchNextObject ();
		
		$posEntity = new PositionMasterEntity ( );
		$posEntity->Load ( "f_pos_id = '{$posID}'" );
		$selfPosLevel = $posEntity->f_pos_level;
		if ($maxLevel->MAX_LEVEL > $selfPosLevel) {
			$lowerRankLevel = $posEntity->f_pos_level + 1;
			$posEntity2 = new PositionMasterEntity ( );
			$posEntity2->Load ( "f_pos_level = '{$lowerRankLevel}'" );
			
			$posEntity->f_pos_level = $lowerRankLevel;
			$posEntity2->f_pos_level = $selfPosLevel;
			
			$conn->StartTrans ();
			$posEntity->Update ();
			$posEntity2->Update ();
			$conn->CompleteTrans ();
		}
	}
	
	/**
	 * แก้ไขสถานะระดับตำแหน่ง
	 *
	 */
	public function togglePositionAction() {
		global $conn;
		$posID = $_POST ['id'];
		
		$posEntity = new PositionMasterEntity ( );
		$posEntity->Load ( "f_pos_id = '{$posID}'" );
		if ($posEntity->f_pos_status == 1) {
			$posEntity->f_pos_status = 0;
		} else {
			$posEntity->f_pos_status = 1;
		}
		$conn->StartTrans ();
		$posEntity->Update ();
		$conn->CompleteTrans ();
	}
	
	/**
	 * กำหนด mapping ระดับตำแหน่ง
	 *
	 */
	public function mapPositionAction() {
		global $conn;
		global $sequence;
		$pos2mapID = $_POST ['posToMapID'];
		$sqlClearMapping = "delete from tbl_mapping where f_map_class = 'pos2rank' and f_master_id = '$pos2mapID'";
		
		if (! $sequence->isExists ( 'mapping' )) {
			$sequence->create ( 'mapping' );
		}
		
		$conn->Execute ( $sqlClearMapping );
		$fp = fopen ( 'd:/Mapping.txt', 'w+' );
		fwrite ( $fp, serialize ( $_POST ) . "\r\n" );
		foreach ( $_POST as $key => $val ) {
			if (substr ( $key, 0, 4 ) == 'rank') {
				if ($val == 'on') {
					$tmpRankID = explode ( "_", $key );
					$mapID = $sequence->get ( 'mapping' );
					$sqlMapping = "insert into tbl_mapping(f_map_id,f_map_class,f_master_id,f_slave_id) ";
					$sqlMapping .= "values('{$mapID}','pos2rank','{$pos2mapID}','{$tmpRankID[1]}')";
					$conn->Execute ( $sqlMapping );
					fwrite ( $fp, "rank: {$tmpRankID[1]}\r\n" );
					fwrite ( $fp, $sqlMapping );
				}
			}
		}
		
		fwrite ( $fp, $sqlClearMapping );
		
		fwrite ( $fp, $pos2mapID );
		fclose ( $fp );
	}

	/**
	 * action /test-function/ สำหรับเปลี่ยนทดสอบการเรียก function
	 *
	 */
	public function testFunctionAction() {
		global $store;
		global $util;
		
		echo 'this is class PositionMasterManagerController';
	}
}
