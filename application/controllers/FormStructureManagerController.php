<?php
/**
 * โปรแกรมจัดการโครงสร้างฟอร์มเอกสาร
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Form
 */
class FormStructureManagerController extends Zend_Controller_Action {
	/**
	 * action /get-ui แสดงหน้าจอจัดการโครงสร้าง
	 *
	 */
	public function getUiAction() {
		global $config;
		global $store;
		
		checkSessionPortlet();
		
		$formID = $_POST ['formID'];
		$formVersion = $_POST ['formVersion'];
		//echo "Modify form ID [{$formID}] version [{$formVersion}]";
		

		$formStructureStore = $store->getFormStructureStore ( $formID, $formVersion, "form_{$formID}_StructureStore" );
		
		//$rankDataStore = $store->getDataStore ( 'rank' );
		//$rankDataStoreSelect = $store->getDataStore ( 'rank', 'rankStoreSelect' );
		//$createSeq = $store->getDataStore ( 'createSequence' );
		//$createStatus = $store->getDataStore ( 'createStatus' );
		

		//$rankAddForm = $this->getRankAdd ();
		//$rankModifyForm = $this->getRankModify ();
		

		$DivID = "FormStruct_{$formID}";
		
		/* prepare DIV For UI */
		echo "<div id=\"{$DivID}\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		/* Remote Data Store for Ranks */
		$formStructureStore
		//rankStore.load();
		//rankStoreSelect.load();
				
		var gridFormStructure_{$formID} = new Ext.grid.GridPanel({
			id: 'gridFormStructure_{$formID}',
			store: form_{$formID}_StructureStore,
			tbar: new Ext.Toolbar({
					id: 'tbFormStructure_{$formID}',
					height: 25				
				}),
			autoExpandMax: true,
			columns: [
			{id: 'structID', header: 'ID', width: 120, sortable: false, dataIndex: 'structID'},
			{header: 'Name', width: 300, sortable: false, dataIndex: 'structName'},
			{header: 'Type', width: 300, sortable: false, dataIndex: 'structType'},
			{header: 'Group', width: 300, sortable: false, dataIndex: 'structGroup'},
			{header: 'Data Type', width: 300, sortable: false, dataIndex: 'dataType'}
			],
			viewConfig: {
				forceFit: true
			},
			loadMask: true,
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
			frame: false,
			iconCls: 'icon-grid',
			renderTo: '{$DivID}'
		});
		
		var tbFormStructure_{$formID} = Ext.getCmp('tbFormStructure_{$formID}');
		
		tbFormStructure_{$formID}.add({
	 		id: 'btnStructure_{$formID}Add',
            text:'New Structure',
            iconCls: 'addIcon',
            handler: function() {
            	Ext.Ajax.request({
		   			url: '/{$config ['appName']}/form-structure-manager/add-structure',
		   			params: {
		   				formID: '$formID',
		   				formVersion: '$formVersion'
					},
		   			method: 'POST',
		   			success: function(o) {
		   				Ext.MessageBox.show({
						    title: 'Form Structure',
						    msg: 'Structure creation success',
						    buttons: Ext.MessageBox.OK,
						    icon: Ext.MessageBox.INFO
						});
		   				form_{$formID}_StructureStore.reload();
					},
		   			failure: function(o) {
		   				Ext.MessageBox.show({
						    title: 'Form Structure',
						    msg: 'Structure creation failed',
						    buttons: Ext.MessageBox.OK,
						    icon: Ext.MessageBox.INFO
						});
					}
		   			//form: Ext.getCmp('formAddForm').getForm().getEl()
		   		});
			}
        },{
        	id: 'btnDeleteStructure_{$formID}',
            text:'Delete Structure',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('Confirm', 'Delete Structure [ '+gridFormStructure_{$formID}.getSelectionModel().getSelected().get('structName')+']?', deleteSelectedStructure_{$formID});
			}
        },{
        	id: 'btnStructure_{$formID}UpVersion',
            text:'Update Version',
            iconCls: 'upIcon',
            disabled: true, 
            handler: function() {
			}
        },{
            text:'Refresh View',
            iconCls: 'refreshIcon',
            handler: function(){
            	form_{$formID}_StructureStore.reload();
			}
        });
        
        
		gridFormStructure_{$formID}.on({
			'rowclick' : function() {
				Ext.getCmp('btnDeleteStructure_{$formID}').enable();
				Ext.getCmp('ecmPropertyGrid').setTitle('Structure Editor');
				Ext.getCmp('ecmPropertyGrid').on('propertychange',function(source,recordId,value,oldValue) {
					Ext.Ajax.request({
		    			url: '/{$config ['appName']}/form-structure-manager/set-structure-property',
		    			method: 'POST',
		    			params: {
		    				formID: gridFormStructure_{$formID}.getSelectionModel().getSelected().get('formID'),
		    				formVersion: gridFormStructure_{$formID}.getSelectionModel().getSelected().get('formVersion'),
		    				structID: gridFormStructure_{$formID}.getSelectionModel().getSelected().get('structID'),
		    				structProperty: recordId,
		    				structValue: value
						}
						//,
		    			//success: function() {
		    			//	form_{$formID}_StructureStore.load()
						//},
		    			//failure: rankAddFailed,
		    			//form: Ext.getCmp('policyAddForm').getForm().getEl()
	    			});
				});
				loadFormStructure_{$formID}PropertyGrid(gridFormStructure_{$formID}.getSelectionModel().getSelected().get('structID'));
			},
			scope: this
		});
		
		gridFormStructure_{$formID}.render();
		form_{$formID}_StructureStore.load();
		
		function deleteSelectedStructure_{$formID}(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/form-structure-manager/delete-structure',
	    				method: 'POST',
	    				success: function() {
	    					form_{$formID}_StructureStore.load()
						},
	    				//failure: deleteFormFailed,
	    				params: { 
	    					formID: '{$formID}',
	    					formVersion: '{$formVersion}',
	    					structID: gridFormStructure_{$formID}.getSelectionModel().getSelected().get('structID') 
						}
	    		});
			}
		}
		
		
		function loadFormStructure_{$formID}PropertyGrid(id) {
			var structureInfoStore = new Ext.data.Store({
				autoLoad: true,
				proxy: new Ext.data.ScriptTagProxy({
					url: '/{$config ['appName']}/data-store/form-structure-property?formID={$formID}&formVersion={$formVersion}&structID='+id
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
			
			structureInfoStore.on('load',function(store,records,options) {
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
			
			structureInfoStore.load();
		}
		
		</script>";
	}
	
	/**
	 * action /add-structure ทำการเพิ่มโครงสร้าง
	 *
	 */
	public function addStructureAction() {
		global $sequence;
		global $conn;
		//$conn->debug = true;
		$formID = $_POST ['formID'];
		$formVersion = $_POST ['formVersion'];
		
		$formStructureSeq = "formStruct_{$formID}_v{$formVersion}";
		if (! $sequence->isExists ( $formStructureSeq )) {
			$sequence->create ( $formStructureSeq );
		}
		
		//include_once ('FormStructure.Entity.php');
		
		$structID = $sequence->get ( $formStructureSeq );
		$formStructEntity = new FormStructureEntity ( );
		$formStructEntity->f_form_id = $formID;
		$formStructEntity->f_version = $formVersion;
		$formStructEntity->f_struct_id = $structID;
		$formStructEntity->f_struct_name = "Struct_{$structID}";
		$formStructEntity->f_struct_type = 1;
		$formStructEntity->f_struct_group = 0;
		$formStructEntity->f_data_type = 0;
		$formStructEntity->f_use_lookup = 0;
		$formStructEntity->f_lookup = '';
		$formStructEntity->f_struct_param = '';
		$formStructEntity->f_initial_value = '';
		$formStructEntity->f_is_title = 0;
		$formStructEntity->f_is_desc = 0;
		$formStructEntity->f_is_keyword = 0;
		$formStructEntity->f_allow_search = 0;
		$formStructEntity->f_is_doc_no = 0;
		$formStructEntity->f_is_doc_date = 0;
		$formStructEntity->f_is_required = 0;
		$formStructEntity->f_is_colored = 0;
		$formStructEntity->f_color = 0;
		$formStructEntity->f_is_validate = 0;
		$formStructEntity->f_validate_fn = '';
		$formStructEntity->f_is_sender_text = 0;
		$formStructEntity->f_is_receiver_text = 0;
		$formStructEntity->f_bookmark = "Struct_{$structID}";
		
		//$conn->StartTrans ();
		$formStructEntity->Save ();
		//$conn->CompleteTrans ();
		
		$response=Array();
		$response['success'] =1;
		//echo json_encode($response);
		
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '(' . json_encode ( $response ) . ')';
	}
	
	/**
	 * ทำการกำหนด Property ของโครงสร้าง
	 *
	 */
	public function setStructurePropertyAction() {
		global $store;
		
		$formID = $_POST ['formID'];
		$formVersion = $_POST ['formVersion'];
		$structID = $_POST ['structID'];
		$structField = $store->getFormStructureMapping ( $_POST ['structProperty'], 'id' );
		
		$structValue = $_POST ['structValue'];
		
		$propertyEditor = $store->getFormStructurePropertyEditor ();
		//include_once ('FormStructure.Entity.php');
		$formStructure = new FormStructureEntity ( );
		
		$formStructure->Load ( "f_form_id = '{$formID}' and f_version = '{$formVersion}' and f_struct_id = '{$structID}'" );
		
		if ($propertyEditor [$structField] == 'boolean') {
			if ($structValue == 'true') {
				$structValue = 1;
			} else {
				$structValue = 0;
			}
		}
		
		$formStructure->{$structField} = UTFDecode( $structValue );
		
		$formStructure->Update ();
	}
	
	/**
	 * action /delete-structure ทำการลบโครงสร้่าง
	 *
	 */
	public function deleteStructureAction() {
		
		$formID = $_POST ['formID'];
		$formVersion = $_POST ['formVersion'];
		$structID = $_POST ['structID'];
		
		//include_once ('FormStructure.Entity.php');
		$formStructure = new FormStructureEntity ( );
		$formStructure->Load ( "f_form_id = '{$formID}' and f_version = '{$formVersion}' and f_struct_id = '{$structID}'" );
		$formStructure->Delete ();
	}
}
