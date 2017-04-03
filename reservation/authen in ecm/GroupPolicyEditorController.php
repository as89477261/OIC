<?php
/**
 * โปรแกมแก้ไข Group Policy
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 */

class GroupPolicyEditorController extends ECMController {
	/**
	 * action /index แสดงหน้าจอโปรแกรม
	 *
	 */
	public function indexAction() {
		global $config;
		$gpID = $_POST['gpID'];
		$storeName = "storeGP_".$gpID;
		$gridName = "gridGP".$gpID;
		$dataName = "dataGP".$gpID;
		echo "<script type=\"text/javascript\">
		
		//Ext.onReady(function() {
			// create the data store
		    var {$storeName} = new Ext.data.GroupingStore({
		    	proxy: new Ext.data.ScriptTagProxy({
	                //url: '/{$config ['appName']}/group-policy-editor/read?gpID={$gpID}'
	                url: '/{$config ['appName']}/data-store/policy-property2?policyID={$gpID}'
	            }),
				recordType: Ext.ux.wam.PropertyRecord,
				groupField: 'group',
				sortInfo: {field:'name',direction:'ASC'},
				reader: new Ext.data.JsonReader({
					id: 'id',
					root: 'results',
					fields: [
						'id',
						'name',
						'text',
						'value',
						'group',
						'editor'
					]
				}, Ext.ux.wam.PropertyRecord)
			});
			
			
			
			var {$gridName} = new Ext.ux.wam.PropertyGrid({
				store: {$storeName},
				id: '{$gridName}',
				frame: false,
				loadMask: true,
				tbar: new Ext.Toolbar({
					id: '{$gridName}_Toolbar',
					height: 25				
				}),
				renderTo: 'editor{$gpID}',
				width: Ext.getCmp('tpAdmin').getInnerWidth(),
				height: Ext.getCmp('tpAdmin').getInnerHeight(),
		        //title:'Properties grid',
				view: new Ext.grid.GroupingView({
		            forceFit:true,
					//groupTextTpl: '{group}',
					emptyGroupText: 'No Group',
					enableGroupingMenu: false,
					showGroupName: false,
					getRowClass: function(record) {
						return (record.data['disabled']==true) ? \"x-item-disabled\" : \"\";
					}
				}),
				customEditors: {
        			'Workflow Secret Level': secretEditor,
        			'Saraban Secret Level': secretEditor
				}
			});
			
			Ext.getCmp('{$gridName}_Toolbar').add({
		 		id: '{$gridName}_Commit',
	            text:'Commit changes',
	            iconCls: 'saveIcon',
	            disabled: false,
	            handler: function() {
	            	var modRecords = new Array();
	            	
	            	/*
	           		for each (var sRecord in {$storeName}.getModifiedRecords()) {
	           			if(typeof sRecord == 'object') {
	           				//console.log(typeof sRecord);
	           				//modRecords[sRecord.data.name] = sRecord.data.value;
	           				modRecords.push(sRecord.id+'_'+sRecord.data.value)
					  		console.log('ID:'+sRecord.id+' ,'+sRecord.data.value);
						}					  
					}
					*/
					origModRecord = {$storeName}.getModifiedRecords();
					
					for(var j = 0 ; j < origModRecord.length ;j++) {
						if(typeof origModRecord[j] == 'object') {
							if(origModRecord[j].id != 998 && origModRecord[j].id != 929 && origModRecord[j].id != 947 && origModRecord[j].id != '998' && origModRecord[j].id != '929' && origModRecord[j].id != '947' ) {
		           				if(origModRecord[j].data.value == true) {
		           					modRecords.push(origModRecord[j].id+'_1');
								} else {
									modRecords.push(origModRecord[j].id+'_0');
								}
							} else {
								modRecords.push(origModRecord[j].id+'_'+origModRecord[j].data.value);
							}
					  		//console.log('ID:'+origModRecord[j].id+' ,'+origModRecord[j].data.value);
						}
					}
	            
	            
	            	Ext.Ajax.request({
			            url: '/{$config ['appName']}/group-policy-editor/save',
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
                                title: 'การบันทึก',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.INFO
                            });
			                /*
			                if(r.redirectLogin == 1) {
			                    sessionExpired(); 
			                } else   {
			                    Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('sendID'));
			                    viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'Unreceived',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('sendID'));
			                }*/
			            },
			            params: {
			            	gpID: {$gpID},
			            	record: Ext.util.JSON.encode(modRecords)
						}
			        });
	            	//console.log({$storeName}.getModifiedRecords());
	            	{$storeName}.commitChanges();
				}
	        },{
		 		id: '{$gridName}_Rollback',
	            text:'Rollback changes',
	            iconCls: 'cancelIcon',
	            disabled: false,
	            handler: function() {
	            	{$storeName}. rejectChanges();
				}
	        });
			{$gridName}.render();
			
			
			{$storeName}.load();
		//});
		</script>
		<div id=\"editor{$gpID}\"><div>
		";
	}
	/**
	 * action /save ทำการบันทึก Group Policy
	 *
	 */
	public function saveAction() {
		global $conn;
		global $store;
		
		$record = $_POST['record'];
		$gpID = $_POST['gpID'];
		//Logger::debug("GPID: ".$gpID);
		//Logger::debug("record : ".$record);	
		//Logger::dump("record : ",json_decode($record));	
		//foreach(json_decode($record) as $policyUpdate){
		foreach(json_decode ( stripslashes ( $record ) ) as $policyUpdate){
			Logger::debug($policyUpdate);
			list($policyCode,$policyValue) = explode("_",$policyUpdate);
			
	        $tmpPolicyValue = $policyValue;
			$policyField = $store->getPolicyMapping ( $policyCode, 'id' );
			//Logger::debug ( "Policy ID : {$gpID}" );
			//Logger::debug ( "Policy Value : {$policyValue}" );
			if (! in_array ( $policyCode, array (929, 947 ) )) {
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
	        
	        if (in_array ( (int)$policyCode, array (998 ) )) {   
	        	Logger::debug("quota save : {$tmpPolicyValue}");
	            $policyValue = $tmpPolicyValue;
	        }
			$sql = "update tbl_group_policy set {$policyField} = '$policyValue' where f_gp_id = '{$gpID}'";
			Logger::debug ( $sql );
			$conn->Execute ( $sql );
		}
		$response = array();
		$response['success'] = 1;
		echo json_encode($response);
	}
	
	/**
	 * action /read ทำการอ่าน Group Policy
	 *
	 */
	public function readAction() {
		
	}
}

