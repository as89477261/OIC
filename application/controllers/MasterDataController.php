<?php
/**
 * โปรแกรมจัดการข้อมูลมาสเตอร์
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Master 
 *
 */
class MasterDataController extends ECMController {
	/**
	 * สร้างฟอร์มเพิ่มมาสเตอร์
	 *
	 * @param string $code
	 * @return string
	 */
	private function getCreateFormJS($code) {
		global $config;
		global $lang;
		
		$gridName = "grid_Master_{$code}";
		
		$storeName = "Master_{$code}";
		
		$js = "
		var frmCreateMasterData_{$code} = new Ext.form.FormPanel({
			id: 'frmCreateMasterData_{$code}',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid: true,

			items: [{
				allowBlank: false,
				xtype: 'hidden',
				name: 'newMasterCodeType_{$code}',
				value: '{$code}'
			},{
				fieldLabel: '{$lang['master'] ['dataCode']}',
				allowBlank: false,
				name: 'newMasterCode_{$code}',
				width: 250
			},{
				id: 'newMasterName_{$code}',
				fieldLabel: '{$lang['master'] ['dataName']}',
				allowBlank: false,
				name: 'newMasterName_{$code}',
				width: 250
			},{
				id: 'newMasterDesc_{$code}',
				fieldLabel: '{$lang['common'] ['desc']}',
				name: 'newMasterDesc_{$code}',
				xtype: 'textarea',
				width:250
			}],
			buttons: [{
				text: '{$lang['common']['save']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
	    			windowCreateMaster_{$code}.hide();
	    			
	    			Ext.MessageBox.show({
			           	msg: '{$lang['common']['saving']}',
			           	progressText: '{$lang['common']['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	
			       
		    		Ext.Ajax.request({
		    			url: '/{$config ['appName']}/master-data/create?ctl={$code}',
		    			method: 'POST',
		    			success: function(o) {
		    				Ext.MessageBox.hide();
		    				Ext.getCmp('btnEditMaster_{$code}').disable();
								Ext.getCmp('btnDeleteMaster_{$code}').disable();
								//Ext.getCmp('btnManageMaster_{$code}').disable();
								{$gridName}.getSelectionModel().clearSelections();
		    				var r = Ext.decode(o.responseText);
		    				if(r.success == 1) {
		    					{$storeName}.reload();
							}
						},
		    			form: Ext.getCmp('frmCreateMasterData_{$code}').getForm().getEl()
		    		});
				}
			},{
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					windowCreateMaster_{$code}.hide();
				}
			}]
		});
		";
		
		$js .= "var windowCreateMaster_{$code} = new Ext.Window({
			id: 'windowCreateMaster_{$code}',
			title: '{$lang['master'] ['createData']}',
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
			items: frmCreateMasterData_{$code},
			closable: false,
			keys: {
				key: Ext.EventObject.ESC,
				fn: function (){
					windowCreateMaster_{$code}.hide();
				},
				scope: this
			}
		});
		
		";
		return $js;
	}
	
	/**
	 * สร้างฟอร์มแก้ไขมาสเตอร์
	 *
	 * @param string $code
	 * @return string
	 */
	private function getEditFormJS($code) {
		global $config;
		global $lang;
		
		$gridName = "grid_Master_{$code}";
		
		$storeName = "masterData_{$code}";
		
		$js = "
		var frmEditMaster_{$code} = new Ext.form.FormPanel({
			id: 'frmEditMaster_{$code}',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			monitorValid: true,

			items: [{
				allowBlank: false,
				xtype: 'hidden',
				name: 'editMasterCodeType_{$code}',
				value: '{$code}'
			},{
				fieldLabel: '{$lang['master'] ['dataCode']}',
				allowBlank: false,
				inputType: 'hidden',
				id: 'editMasterOldCode_{$code}',
				name: 'editMasterOldCode_{$code}'
			},{
				fieldLabel: '{$lang['master'] ['dataCode']}',
				allowBlank: false,
				name: 'editMasterCode_{$code}',
				readOnly: true,
				width: 250
			},{
				id: 'editMasterName_{$code}',
				fieldLabel: '{$lang['master'] ['dataName']}',
				allowBlank: false,
				name: 'editMasterName_{$code}',
				width: 250
			},{
				id: 'editMasterDesc_{$code}',
				fieldLabel: '{$lang['common'] ['desc']}',
				name: 'editMasterDesc_{$code}',
				xtype: 'textarea',
				width:250
			}],
			buttons: [{
				text: '{$lang['common']['save']}',
				formBind: true,
				handler: function() {
	    			windowEditMaster_{$code}.hide();
	    			
	    			Ext.MessageBox.show({
			           	msg: '{$lang['common']['saving']}',
			           	progressText: '{$lang['common']['savingText']}',
			           	width:300,
			           	wait:true,
			           	waitConfig: {interval:200},
			           	icon:'ext-mb-download'
			       	});
			       	
		    			Ext.Ajax.request({
		    				url: '/{$config ['appName']}/master-data/edit?ctl={$code}',
		    				method: 'POST',
		    				success: function(o) {
		    					Ext.MessageBox.hide();
		    					Ext.getCmp('btnEditMaster_{$code}').disable();
								Ext.getCmp('btnDeleteMaster_{$code}').disable();
								//Ext.getCmp('btnManageMaster_{$code}').disable();
								{$gridName}.getSelectionModel().clearSelections();
								var r = Ext.decode(o.responseText);
								if(r.success == 1) {
										{$storeName}.reload();
								}
		    				},
		    				form: Ext.getCmp('frmEditMaster_{$code}').getForm().getEl()
		    			});
	    		}
			},{
				text: '{$lang['common']['cancel']}',
				handler: function() {
					windowEditMaster_{$code}.hide();
				}
			}]
		});
		";
		
		$js .= "var windowEditMaster_{$code} = new Ext.Window({
			id: 'windowEditMaster_{$code}',
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
			items: frmEditMaster_{$code},
			closable: false,
			keys: {
				key: Ext.EventObject.ESC,
				fn: function (){
					windowEditMaster_{$code}.hide();
				},
				scope: this
			}
		});
		";
		return $js;
	}
	
	/**
	 * โปรแกรมสร้าง DataStore ของมาสเตอร์
	 *
	 * @param string $storeName
	 * @param string $masterID
	 * @return string
	 */
	public function getMasterStore($storeName, $masterID) {
		global $config;
		$store = "var $storeName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/master-data/get-master?id={$masterID}'
			        }),
			        
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name','value', 'desc', 'status'
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
		
		return $store;
	}
	
	/**
	 * action /create สร้างมาสเตอร์
	 *
	 */
	public function createAction() {
		global $sequence;
		
		//include_once 'Master.Entity.php';
		
		if(!$sequence->isExists('master')) {
			$sequence->create('master');
		} 
		
		$ctlID = $_GET['ctl'];
		
		$newMasterName = $_POST['newMasterName_'.$ctlID];
		$newMasterCode = $_POST['newMasterCode_'.$ctlID];
		$newMasterDesc = $_POST['newMasterDesc_'.$ctlID];
		
		$master = new MasterEntity();
		$master->f_mas_id = $sequence->get('master');
		$master->f_ctl_id = $ctlID;
		$master->f_name = $newMasterName;
		$master->f_description = $newMasterDesc;
		$master->f_value = $newMasterCode;
		$master->f_status = 1;
		$master->Save();
				
		
		$response = Array();
		$response['success'] = 1;
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		echo json_encode($response);
	}
	
	/**
	 * action /edit แก้ไขมาสเตอร์
	 *
	 */
	public function editAction() {
		$response = Array();
		$response['success'] = 1;
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		echo json_encode($response);
	}
	
	/**
	 * action /delete ลบมาสเตอร์
	 *
	 */
	public function deleteAction() {
		$response = Array();
		$response['success'] = 1;
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		echo json_encode($response);
	}
	
	/**
	 * action /toggle แก้ไขสถานะมาสเตอร์
	 *
	 */
	public function toggleAction() {
		$response = Array();
		$response['success'] = 1;
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		echo json_encode($response);
	}
	
	/**
	 * action /get-master ส่งข้อมูลมาสเตอร์
	 *
	 */
	public function getMasterAction() {
		//include_once 'Master.Entity.php';
		
		$master = new MasterEntity();
		$masterCtlID = $_GET['id'];
		$masterRS = $master->Find("f_ctl_id = '{$masterCtlID}'");
		$masters = Array ();
		foreach ($masterRS as $masterData) {
			$masters[] = Array('id'=>$masterData->f_mas_id,
			'name'=>$masterData->f_name,
			'value'=>$masterData->f_value,
			'desc'=>$masterData->f_description,
			'status'=>$masterData->f_status);
		}
		
		
		$data = json_encode ( $masters );
		$count = count($masters);
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /get-ui แสดงหน้าจอจัดการมาสเตอร์
	 *
	 */
	public function getUiAction() {
		global $config;
		global $lang;
		
		checkSessionPortlet();
		
		//require_once 'ECMGridApp.php';
		
		$masterID = $_POST ['id'];
		$storeName = "Master_" . $masterID;
		$gridName = "grid_" . $storeName;
		
		$gridApp = new ECMGridApp ( $gridName );
		
		$columns = Array (array ('header' => $lang ['common'] ['code'], 'width' => '50', 'dataIndex' => 'value' ), array ('header' => $lang ['common'] ['name'], 'width' => 200, 'dataIndex' => 'name' ), array ('header' => $lang ['common'] ['desc'], 'width' => 275, 'dataIndex' => 'desc' ), array ('header' => $lang ['common'] ['status'], 'width' => '50', 'dataIndex' => 'status','renderer'=>'renderStatus' ) );
		
		$gridApp->setColumns ( "cm_MasterData_" . $masterID, $columns );
		
		echo "<div id=\"{$storeName}_DIV\"></div>";
		echo "<script type=\"text/javascript\">";
		echo $this->getCreateFormJS($masterID);
		
		echo $this->getMasterStore ( $storeName, $masterID );
		
		echo $gridApp->getColumnModel ();
		
		echo $gridApp->getGrid ( "{$storeName}_DIV", $storeName );
		echo "{$storeName}.load();";
		echo "function deleteMaster_{$masterID} (btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
                        msg: 'กำลังบันทึกรายการติดตาม...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/add-track',
                        method: 'POST',
                        success: function(o){
                        	Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            var responseMsg = '';
                            if(r.success == 1) {
                            	responseMsg = 'บันทึกเรียบร้อยแล้ว';
							} else {
								responseMsg = 'บันทึกผิดพลาด';
							}
							
                            Ext.MessageBox.show({
                                title: 'การบันทึกรายการติดตาม',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                           		icon: Ext.MessageBox.INFO
							});
                        },
                        params: {
                        	mode: 'r', 
                        	id: {$gridName}.getSelectionModel().getSelected().get('recvID') 
						}
                });
			}
		}";
		$lambdaFnAdd = "function(){windowCreateMaster_{$masterID}.show();}";
		$lambdaFnEdit = "function(){alert('clicked');}";
		$lambdaFnDelete = "function(){Ext.MessageBox.confirm('Confirm', 'ลบข้อมูล [ '+{$gridName}.getSelectionModel().getSelected().get('name')+'] ?', deleteMaster_{$masterID});}";
		$lambdaFnToggle = "function(){alert('clicked');}";
		$lambdaFnRefresh = "function() {
			Ext.getCmp('btnEditMaster_{$masterID}').disable();
			Ext.getCmp('btnDeleteMaster_{$masterID}').disable();
			Ext.getCmp('btnDisabledMaster_{$masterID}').disable();
			{$gridName}.getSelectionModel().clearSelections();
			{$storeName}.reload();
		}";
		echo $gridApp->addTBarAction ( 'btnAddMaster_' . $masterID, $lang ['common'] ['create'], $lambdaFnAdd, false, 'addIcon' );
		echo $gridApp->addTBarAction ( 'btnEditMaster_' . $masterID, $lang ['common'] ['modify'], $lambdaFnEdit, true, 'editIcon' );
		echo $gridApp->addTBarAction ( 'btnDeleteMaster_' . $masterID, $lang ['common'] ['delete'], $lambdaFnDelete, true, 'deleteIcon' );
		//echo $gridApp->addTBarAction ( 'btnUpMaster_' . $masterID, $lang ['common'] ['moveUp'], 'function(){alert(\'clicked\');}', true, 'upIcon' );
		//echo $gridApp->addTBarAction ( 'btnDownMaster_' . $masterID, $lang ['common'] ['moveDown'], 'function(){alert(\'clicked\');}', true, 'downIcon' );
		echo $gridApp->addTBarAction ( 'btnDisabledMaster_' . $masterID, $lang ['common'] ['toggle'], $lambdaFnToggle, true, 'toggleIcon' );
		echo $gridApp->addTBarAction ( 'btnRefreshMaster_' . $masterID, $lang ['common'] ['refresh'],$lambdaFnRefresh, false, 'refreshIcon' );
		
		echo $gridApp->render ();
		
		echo "{$gridName}.on('rowclick',function(){
			Ext.getCmp('btnEditMaster_{$masterID}').enable();
			Ext.getCmp('btnDeleteMaster_{$masterID}').enable();
			Ext.getCmp('btnDisabledMaster_{$masterID}').enable();
		},this);";
		echo "</script>";
	
	}
}
