<?php
/**
 * โปรแกรม Property ของ DMS
 *
 */
class DMSPropertyController extends ECMController {
	/**
	 * action /get-ui/ หน้าจอสำหรับแสดง Property ของ DMS Object
	 *
	 */
	public function getUiAction() {
		global $config;
		global $lang;
		
		checkSessionPortlet ();
		
		echo "<div id=\"dvProperty\"></div>";
		$js = "<script type=\"text/javascript\">
		{$this->getCreatePolicyGroupJS()}
        {$this->getModifyPolicyGroupJS()}
        
		var tbpProperty = new Ext.TabPanel({
		id: 'tbpProperty',
        renderTo: 'dvProperty',
        bodyStyle:'padding:5px;',
        activeTab: 0,        
        width: 360,
        height: 430,
        x: 5,
        y: 0,
        plain:true,
		baseCls: 'x-plain',
		defaults:{autoHeight: true},
        defaults:{autoScroll: true},
        items:[{
        		id: 'tbGeneral',
                title: '{$lang ['dms'] ['propTabGeneral']}',
                loadMask: true,
                baseCls: 'x-plain',
                autoLoad: {url: '/{$config ['appName']}/dms-property/get-tab-general', scripts: true}
            },{
            	id: 'tbSecurity',
                title: '{$lang ['dms'] ['propTabSecurity']}',
                loadMask: true,
                baseCls: 'x-plain',
				autoLoad: {url: '/{$config ['appName']}/dms-property/get-tab-security', scripts: true},
				autoScroll: false
            },{
            	id: 'tbVersion',
                title: '{$lang ['dms'] ['propTabVersion']}',
                loadMask: true,
                baseCls: 'x-plain',
				autoLoad: {url: '/{$config ['appName']}/dms-property/get-tab-version', scripts: true},
				autoScroll: false
            }
        ]});
        
        
        
		</script>";
		echo $js;
	}
	
	/**
	 * action /get-tab-general/ สำหรับแสดงข้อมูลใน Tab ทั่วไป
	 *
	 */
	public function getTabGeneralAction() {
		global $lang;
		global $util;
		
		checkSessionPortlet ();
		
		$dms = new DmsObjectEntity ( );
		$dmsUtil = new DMSUtil ( );
		
		try {
			$dms->Load ( "f_obj_id='{$_COOKIE['contextDMSObjectID']}'" );
		} catch ( exceptions $e ) {
			//			Logger::dump('error detail', __FILE__.__LINE__.$e->getMessage ());
		}
		
		//$docMain = new DocMainEntity ( );
		//$docMain->Load ( "f_doc_id = '{$dms->f_doc_id}'" );
		
		if (is_null ( $dms->f_created_uid )) {
			$createUser = "ECM";
		} else {
			
			$createAccount = new AccountEntity ( );
			$createAccount->Load("f_acc_id = '{$dms->f_created_uid}'");
			$createUser = "{$createAccount->f_name} {$createAccount->f_last_name}";
		}
		
		if ($dms->f_last_update_stamp == 0) {
			$lastUpdateUser = "-";
		} else {
			
			if(is_null($dms->f_last_update_uid)) {
				$lastUpdateUser = "-";				
			} else {
				$modifyAccount = new AccountEntity ( );
				$modifyAccount->Load("f_acc_id = '{$dms->f_last_update_uid}'");
				$lastUpdateUser =  "{$modifyAccount->f_name} {$modifyAccount->f_last_name}";
			}
		}
		
		echo "<div id=\"dvTabGeneral\"></div>";
		$js = "<script type=\"text/javascript\">
			var frmTabGeneral = new Ext.form.FormPanel({
			id: 'frmTabGeneral',
			renderTo: 'dvTabGeneral',
			baseCls: 'x-plain',
			labelWidth: 70,
			defaultType: 'textfield',
			items: [{
	            xtype:'fieldset',
	            //checkboxToggle: true,
	            autoHeight: true,
	            defaults: {width: 210},
	            defaultType: 'textfield',
	            collapsed: false,
	            title: '{$lang ['common'] ['desc']}',
	            items :[{
					id: 'txtName',
					fieldLabel: '{$lang ['dms'] ['name']}',
					value: '{$dms->f_name}',
					anchor:'100%'
				},{
					id: 'txtDescription',
					xtype: 'textarea',
					width: '95%',
					height: 50,
					fieldLabel: '{$lang ['dms'] ['description']}',
					value: '{$dms->f_description}'
				},{
					id: 'txtKeyword',
					xtype: 'textarea',
					width: '95%',
					height: 50,
					fieldLabel: '{$lang ['dms'] ['keyword']}',
					value: '{$dms->f_keyword}'
				},{
					id: 'txtLocation',
					fieldLabel: '{$lang ['dms'] ['location']}',
					value: '{$dmsUtil->getIndexLocationName($_COOKIE['contextDMSObjectID'])}',
					anchor:'100%'
				},{
					id: 'txtContain',
					fieldLabel: '{$lang ['dms'] ['contain']}',
					value: '{$dmsUtil->getIndexContain($_COOKIE['contextDMSObjectID'])}',
					anchor:'100%'
				},{
					id: 'txtType',
					fieldLabel: '{$lang ['org'] ['type']}',
					value: '{$dmsUtil->getIndexType($_COOKIE['contextDMSObjectType'])}',
					anchor:'100%'
				}]
			},{
	            xtype:'fieldset',
	            //checkboxToggle:true,
	            autoHeight:true,
	            defaults: {width: 210},
	            defaultType: 'textfield',
	            collapsed: false,
	            title: '{$lang ['df'] ['date']}',
	            items :[{
					id: 'txtCreateStamp',
					fieldLabel: '{$lang ['dms'] ['createStamp']}',
					value: '{$util->getDateString($dms->f_created_stamp)}, {$util->getTimeString($dms->f_created_stamp)} ({$createUser})',
					anchor:'100%'
				},{
					id: 'txtUpdateStamp',
					fieldLabel: '{$lang ['dms'] ['updateStamp']}',
					value: '{$util->getDateString($dms->f_last_update_stamp)}, {$util->getTimeString($dms->f_last_update_stamp)} ({$lastUpdateUser})',
					anchor:'100%'
				},{
					id: 'txtExpireStamp',
					fieldLabel: '{$lang ['dms'] ['expireStamp']}',
					value: '{$util->getDateString($dms->f_expire_stamp)}, {$util->getTimeString($dms->f_expire_stamp)}',
					anchor:'100%'
				}]
			}]
		});		
		</script>";
		echo $js;
	}
	
	/**
	 * action /get-tab-security/ สำหรับแสดงข้อมูลใน Tab ความปลอดภัย
	 *
	 */
	public function getTabSecurityAction() {
		global $config;
		global $store;
		global $lang;
		
		checkSessionPortlet ();
		
		$DMSUtil = new DMSUtil ( );
		
		$storeName = 'securityGroupStore';
		$storeName2 = 'securityPropertyStore';
		$storeName3 = 'securityMember';
		$gridName = 'securityGroupGrid';
		$gridName2 = 'securityGroupPermissionGrid';
		$DMSID = $_COOKIE ['contextDMSObjectID'];
		$acl = $DMSUtil->acl ( $DMSID );
		//		Logger::dump("ACL for {$DMSID}",$acl);
		$storeDef = $store->getSecureGroupStore ( $DMSID, $storeName );
		$propStoreDef = $store->getSecurePropertyStore ( $storeName2 );
		$memberStore = $store->getSecureMemberStore ( $storeName3 );
		
		echo "<div id=\"dvTabSecurity\"></div>";
		$programID = 'propGridGroup';
		$programID2 = 'propGridGroupPermission';
		
		$js = "<script type=\"text/javascript\">
		$storeDef
		$propStoreDef
		$memberStore
		
		$storeName.load();
		$storeName2.load({params:{sid:0,oid:0}});
		$storeName3.on('load',function(t,r,o) {
			for(i=0;i<r.length;i++) {
				dataRecord = t.getAt(i);
				var rec = new ReceiverRecordDataDef({
                        dataid: dataRecord.data.dataid,
                        name: dataRecord.data.name,
                        description: dataRecord.data.description,
                        typeid: dataRecord.data.typeid,
                        allow: dataRecord.data.allow
            	});
            	tempModifySecureGroup.add(rec);   
			}
		},this);
		
		function deleteSecureGroupMapping(btn) {
			if(btn=='yes') {
				Ext.Ajax.request({
					url: '/{$config ['appName']}/secure/drop-secure-mapping',
					method: 'POST',
					success: function(o){
						Ext.MessageBox.hide();
						var r = Ext.decode(o.responseText);
						Ext.MessageBox.show({
						    title: 'Drop secure group mapping',
						    msg: 'Completed',
						    buttons: Ext.MessageBox.OK,
						    icon: Ext.MessageBox.INFO
						});
						$storeName.reload();
						$storeName2.load({params:{sid:0,oid:0}});
						Ext.getCmp('btn{$programID}Delete_1').disable();
						Ext.getCmp('btn{$programID}Edit_1').disable();
					},
					params: {
						sid: {$gridName}.getSelectionModel().getSelected().get('id'),
						id: '{$DMSID}'
					}
				});
			}
		}
		
			var editGroupActiveProperty = new Ext.grid.CheckColumn({
		       header: \"Active\",
		       dataIndex: 'active',
		       width: 55
		    })
		    var editGroupInheritProperty = new Ext.grid.CheckColumn({
		       header: \"Inherit\",
		       dataIndex: 'inherit',
		       hidden: true,
		       width: 55
		    })
		    
		    
		var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"Group\",
	           dataIndex: 'name',
	           width: 195
	        },editGroupActiveProperty,editGroupInheritProperty		    
		]);
	    
		cm_{$programID}.defaultSortable = false;
	    
	    var {$gridName} = new Ext.grid.EditorGridPanel({
	        width: 345,
			height: 165,
			id: '{$gridName}',
			name: '{$gridName}',
	        store: {$storeName},
	        plugins: [editGroupActiveProperty,editGroupInheritProperty],
        	clicksToEdit: 1,
	        tbar: [
	            'Add Existing Group: ', ' ',
	            new Ext.form.ComboBox({
					store: autocompleteSecureGroupStore,
					//fieldLabel: 'จาก',
					displayField:'name',
					typeAhead: false,
					emptyText: 'Default',
					loadingText: '{$lang['common']['searcing']}',
					width: 200,
					hideTrigger: true,
					allowBlank: false,
					//labelStyle: 'font-weight:bold;color: Red;',
					name: 'secureGroupNameSelector',
					id: 'secureGroupNameSelector',
					tpl: resultSecureGroupTpl,
                    //lazyInit: true,
                    //lazyRender: true,
                    minChars: 2,
                    shadow: false,
                    autoLoad: true,
                    mode: 'remote',
					itemSelector: 'div.search-item-secure-group-name'
				})
				/*{
                    xtype: 'textfield',
                    name: 'last2'
	            }*/
	        ],
			cm: cm_{$programID},
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
	        loadMask: true,
	        viewConfig: {forceFit:true},
		    bbar: [{
		 		id: 'btn{$programID}Add_1',
	            text:'Create',
	            iconCls: 'addIcon',
	            handler: function() {
	            	wndCreatePolicyGroup.show();
				}
	        },{
		 		id: 'btn{$programID}Edit_1',
	            text:'Modify',
	            iconCls: 'editIcon',
	            disabled: true,
	            handler: function() {
	            	tempModifySecureGroup.removeAll();
	            	wndModifyPolicyGroup.show();
	            	Ext.getCmp('modifySecureGroupName').setValue({$gridName}.getSelectionModel().getSelected().get('name'));
	            	
	            	{$storeName3}.load({params:{id: {$gridName}.getSelectionModel().getSelected().get('id')}});
				}
	        },{
		 		id: 'btn{$programID}Delete_1',
	            text:'Delete',
	            disabled: true,
	            iconCls: 'deleteIcon',
	            handler: function() {
	            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', 'Drop secure group [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSecureGroupMapping);
				}
	        },'-',{
				id: 'btn{$programID}Apply_1',
	           	 	text:'Apply Change',
	           	 	iconCls: 'saveIcon',
	            	handler: function() {
						var tmpAllRecord = '';
						Ext.each({$gridName}.getStore(). getModifiedRecords(),function(item, index, allItems){
							var tmpActive = '0';
							var tmpInherit= '0';
							if(item.get('active') ==1) tmpActive = '1';
							if(item.get('inherit') ==1) tmpInherit = '1';
							var tmpRecord = item.get('id') + '_'+tmpActive + '_'+tmpInherit;
							if(tmpAllRecord == '') {
								tmpAllRecord = tmpRecord;
							} else {
								tmpAllRecord = tmpAllRecord + ','+tmpRecord;
							} 
							
						},this);
						
						
						Ext.Ajax.request({
							url: '/{$config ['appName']}/secure/update-property',
							method: 'POST',
							success: function(o){
								Ext.MessageBox.hide();
								var r = Ext.decode(o.responseText);
								Ext.MessageBox.show({
								    title: 'การบันทึกรายชื่อ',
								    msg: 'บันทึกเรียบร้อยแล้ว',
								    buttons: Ext.MessageBox.OK,
								    icon: Ext.MessageBox.INFO
								});
								{$gridName}.getStore().commitChanges();
							},
							params: {
								data: tmpAllRecord
							}
						});
					}
			}
	        ]
	    });
	    
	    var editSecurityProperty = new Ext.grid.CheckColumn({
		       header: \"Active\",
		       dataIndex: 'value',
		       width: 55
		    })
		    
	    var cm_{$programID2} = new Ext.grid.ColumnModel([{
	           header: \"Permission\",
	           dataIndex: 'name',
	           width: 270
	        },editSecurityProperty		    
		]);
	    
		cm_{$programID2}.defaultSortable = false;
	    
	    var {$gridName2} = new Ext.grid.EditorGridPanel({
	        width: 345,
			height: 225,
	        store: {$storeName2},
			cm: cm_{$programID2},
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
	        loadMask: true,
	        viewConfig: {forceFit:true},
	        plugins: [editSecurityProperty],
        	clicksToEdit: 1,
	       bbar: [{
		 		id: 'btn{$programID}Save_1',
	            text:'Save',
	            iconCls: 'addIcon',
	            handler: function() {
	            		var tmpAllRecord2 = '';
						Ext.each({$gridName2}.getStore(). getModifiedRecords(),function(item, index, allItems){
							var tmpValue = '0';
							if(item.get('value') ==1) tmpValue = '1';
							var tmpRecord2= item.get('id') + '_'+tmpValue;
							
							if(tmpAllRecord2 == '') {
								tmpAllRecord2 = tmpRecord2;
							} else {
								tmpAllRecord2 = tmpAllRecord2 + ','+tmpRecord2;
							} 
							
						},this);
						
						Ext.Ajax.request({
							url: '/{$config ['appName']}/secure/update-property-detail',
							method: 'POST',
							success: function(o){
								Ext.MessageBox.hide();
								var r = Ext.decode(o.responseText);
								Ext.MessageBox.show({
								    title: 'การบันทึกรายชื่อ',
								    msg: 'บันทึกเรียบร้อยแล้ว',
								    buttons: Ext.MessageBox.OK,
								    icon: Ext.MessageBox.INFO
								});
								{$gridName2}.getStore().commitChanges();
							},
							params: {
								data: tmpAllRecord2,
								oid: Cookies.get('contextDMSObjectID')
							}
						});
						
				}
	        },{
		 		id: 'btn{$programID}Refresh_1',
	            text:'Refresh',
	            iconCls: 'refreshIcon',
	            handler: function() {
				}
	        }
	        ]
	    });
	    
		var frmTabSecurity = new Ext.form.FormPanel({
			id: 'frmTabSecurity',
			width: 350,
			renderTo: 'dvTabSecurity',
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			bodyStyle:'padding:0px;',
			items: [{
	           	xtype: 'panel',
	           	border: false,
	           	layout: 'form',
	          	width: 345,
			   	items: [{$gridName}]
			},{
	           	xtype: 'panel',
	           	border: false,
	           	layout: 'form',
	          	width: 345,
			   	items: [{$gridName2}]
			}]
		});		
		
		Ext.getCmp('secureGroupNameSelector').on('select',function(c,r,i) {         
            dataRecord = c.store.getAt(i);
            var rec = new ReceiverRecordDataDef({
                        id: dataRecord.data.id,
                        name: dataRecord.data.name,
                        status: dataRecord.data.status,
                        inherit: dataRecord.data.inherit
            });
           	$storeName.add(rec);          
            Ext.getCmp('secureGroupNameSelector').emptyText = '';
            Ext.getCmp('secureGroupNameSelector').reset();     

            Ext.Ajax.request({
				url: '/{$config ['appName']}/secure/add-exists-group',
				method: 'POST',
				success: function(o){
					Ext.MessageBox.hide();
					var r = Ext.decode(o.responseText);
					Ext.MessageBox.show({
					    title: 'Add secure group',
					    msg: 'Completed',
					    buttons: Ext.MessageBox.OK,
					    icon: Ext.MessageBox.INFO
					});
					$storeName.reload();
					$storeName2.load({params:{sid:0,oid:0}});
					Ext.getCmp('btn{$programID}Delete_1').disable();
					Ext.getCmp('btn{$programID}Edit_1').disable();
				},
				params: {
					sid: dataRecord.data.id,
					id: '{$DMSID}'
				}
			});
        },this);
		
		Ext.getCmp('{$gridName}').on('rowclick',function() {
			{$gridName2}.getStore().rejectChanges();
			Ext.getCmp('btn{$programID}Delete_1').enable();
			Ext.getCmp('btn{$programID}Edit_1').enable();
			//console.log('ID Load Property :'+Ext.getCmp('$gridName').getSelectionModel().getSelected().get('id'));
        	$storeName2.load({params:{sid: Ext.getCmp('$gridName').getSelectionModel().getSelected().get('id'),oid: Cookies.get('contextDMSObjectID')}});
		},this);
		
        //alert('show'+Cookies.get('contextDMSObjectID'));
		</script>";
		echo $js;
	}
	
	/**
	 * action /get-tab-version/ สำหรับแสดงหน้าจอ Tab Version
	 *
	 */
	public function getTabVersionAction() {
		global $lang;
		//		global $util;
		

		checkSessionPortlet ();
		
		$docMain = new DocMainEntity ( );
		$docClass = new Document ( );
		
		try {
			$docMain->Load ( "f_doc_id='{$_COOKIE['contextDocumentID']}'" );
		} catch ( exceptions $e ) {
			Logger::dump ( 'error detail', __FILE__ . __LINE__ . $e->getMessage () );
		}
		
		//		Logger::dump('doc main', $docMain);
		

		echo "<div id=\"dvTabVersion\"></div>";
		$js = "<script type=\"text/javascript\">
			var frmTabVersion = new Ext.form.FormPanel({
			id: 'frmTabVersion',
			renderTo: 'dvTabVersion',
			baseCls: 'x-plain',
			labelWidth: 70,
			defaultType: 'textfield',
			items: [{
	            xtype:'fieldset',
	            //checkboxToggle: true,
	            autoHeight: true,
	            defaults: {width: 210},
	            defaultType: 'textfield',
	            collapsed: false,
	            title: '{$lang ['dms'] ['propTabVersion']}',
	            items :[{
					id: 'txtStatus',
					fieldLabel: '{$lang ['common'] ['status']}',
					value: '" . $docClass->getDocumentStatus ( $docMain->f_checkout_flag ) . "',
					anchor:'100%'
				},{
					id: 'txtCheckoutBy',
					fieldLabel: '{$lang ['dms'] ['modifyBy']}',
					value: '" . $docClass->getAccountName ( $docMain->f_checkout_user ) . "',
					anchor:'100%'
				},{
					id: 'txtVersion',
					fieldLabel: '{$lang ['dms'] ['version']}',
					value: '" . $docMain->f_version . "',
					anchor:'100%'
				}]
			}]
		});		
		</script>";
		echo $js;
	}
	
	/**
	 * action /expand-node-by-id/ สำหรับทำการ Expand Node ใน DMS Tree
	 *
	 */
	public function expandNodeByIdAction() {
		$scriptName = "expandNodeScript";
		$id = $_COOKIE ['contextDMSObjectLID'];
		$dmsUtil = new DMSUtil ( );
		$arrId = array_reverse ( $dmsUtil->getIndexParent ( $id ) );
		
		$lastIndex = count ( $arrId );
		$indexSelected = $lastIndex - 1;
		//		$scriptExpand = "DMSTree.getNodeById('dms_{$arrId[0]}').expand();";
		for($i = 0; $i < $lastIndex; $i ++) {
			$scriptExpandInner = "DMSTree.getNodeById('dms_{$arrId[$i]}').expand();";
			if ($i == $indexSelected) {
				$scriptExpandInner = '';
				$scriptNodeSelected = "DMSTree.getNodeById('dms_$arrId[$i]').select();";
			}
			
			$scriptExpand .= "
								if (typeof DMSTree.getNodeById('dms_$arrId[$i]') == 'object' && !DMSTree.getNodeById('dms_$arrId[$i]').isExpanded()) {
									$scriptExpandInner
								}
			";
			
			$scriptExpandNode .= "
									function {$scriptName}_{$_COOKIE['contextDMSObjectID']}_{$i}() {																	
										DMSTree.on('expandnode', function(node){
											if (typeof DMSTree.getNodeById('dms_$arrId[$i]') == 'object' && !DMSTree.getNodeById('dms_$arrId[$i]').isExpanded()) {
												$scriptExpandInner
												$scriptNodeSelected
											}
										});
									}
								";
			
			$scriptCall .= "{$scriptName}_{$_COOKIE['contextDMSObjectID']}_{$i}();";
		}
		
		$js = "<script type=\"text/javascript\">
					$scriptExpand
					$scriptExpandNode
					$scriptCall
					if (typeof DMSTree.getNodeById('dms_$arrId[$indexSelected]') == 'object') {
						$scriptNodeSelected
					}
				</script>";
		
		echo $js;
	}
	
	/**
	 * action /expand3-node-by-id/ ทำการ Expand 3 Node
	 *
	 */
	public function expand3NodeByIdAction() {
		
		$id = $_COOKIE ['contextDMSObjectLID'];
		$dmsUtil = new DMSUtil ( );
		$arrId = array_reverse ( $dmsUtil->getIndexParent ( $id ) );
		
		$lastIndex = count ( $arrId );
		$indexSelected = $lastIndex - 1;
		//		$scriptExpand = "DMSTree.getNodeById('dms_{$arrId[0]}').expand();";
		for($i = 0; $i < $lastIndex; $i ++) {
			$scriptExpandInner = "DMSTree.getNodeById('dms_{$arrId[$i]}').expand();";
			if ($i == $indexSelected) {
				$scriptExpandInner = '';
				$scriptNodeSelected = "DMSTree.getNodeById('dms_$arrId[$i]').select();";
			}
			
			$scriptExpand .= "
								if (typeof DMSTree.getNodeById('dms_$arrId[$i]') == 'object' && !DMSTree.getNodeById('dms_$arrId[$i]').isExpanded()) {
									$scriptExpandInner
								}
			";
			
			/*$scriptExpandNode .= "DMSTree.on('expandnode', function(node){
										$scriptExpand2
										$scriptNodeSelected
									});";*/
			$scriptExpandNode .= "								
									DMSTree.on('expandnode', function(node){
										if (typeof DMSTree.getNodeById('dms_$arrId[$i]') == 'object' && !DMSTree.getNodeById('dms_$arrId[$i]').isExpanded()) {
											$scriptExpandInner
											$scriptNodeSelected
										}
									});
								";
		}
		
		$js = "<script type=\"text/javascript\">
					$scriptExpand
					$scriptExpandNode
					if (typeof DMSTree.getNodeById('dms_$arrId[$indexSelected]') == 'object') {
						$scriptNodeSelected
					}
				</script>";
		
		echo $js;
	}
	
	/**
	 * action /expand1-node-by-id/ ทำการ Expand 1 node
	 *
	 */
	public function expand1NodeByIdAction() {
		
		$id = $_COOKIE ['contextDMSObjectLID'];
		$dmsUtil = new DMSUtil ( );
		$arrId = array_reverse ( $dmsUtil->getIndexParent ( $id ) );
		
		$lastIndex = count ( $arrId );
		$scriptExpand = "DMSTree.getNodeById('dms_{$arrId[0]}').expand();";
		for($i = 1; $i < $lastIndex; $i ++) {
			$scriptExpand2 = "DMSTree.getNodeById('dms_{$arrId[$i]}').expand();";
			if ($i == ($lastIndex - 1)) {
				$scriptExpand2 = '';
				$scriptNodeSelected = "DMSTree.getNodeById('dms_$arrId[$i]').select();";
			}
			/*$scriptExpandNode .= "DMSTree.on('expandnode', function(node){
										$scriptExpand2
										$scriptNodeSelected
									});";*/
			$scriptExpandNode .= "DMSTree.on('expandnode', function(node){
								if (!DMSTree.getNodeById('dms_$arrId[$i]').isExpanded()) {
									//alert('expand: {$arrId[$i]}');
									$scriptExpand2
								} else {
									//alert('no: {$arrId[$i]}');
								}
								$scriptNodeSelected
								});";
		}
		
		$js = "<script type=\"text/javascript\">
					$scriptExpand
					$scriptExpandNode
				</script>";
		
		echo $js;
	}
	
	/**
	 * action /expand2-node-by-id/ ทำการ Expand 2 Node
	 *
	 */
	public function expand2NodeByIdAction() {
		
		$id = $_COOKIE ['contextDMSObjectLID'];
		$dmsUtil = new DMSUtil ( );
		$arrId = array_reverse ( $dmsUtil->getIndexParent ( $id ) );
		
		$lastIndex = count ( $arrId );
		$scriptExpand = "DMSTree.childNodes = [];";
		for($i = 0; $i < $lastIndex; $i ++) {
			$scriptExpand .= "DMSTree.childNodes = DMSTree.getNodeById('dms_{$arrId[$i]}');";
		}
		
		$js = "<script type=\"text/javascript\">
					$scriptExpand
					DMSTree.childNodes.expand();
				</script>";
		
		echo $js;
	}
	
	/**
	 * สร้างหน้าจอสำหรับการสร้าง Policy Group
	 *
	 * @return string
	 */
	private function getCreatePolicyGroupJS() {
		global $config;
		global $lang;
		
		$storeName = 'securityGroupStore';
		$storeName2 = 'securityPropertyStore';
		
		$programID = "createPolicyGroup";
		$gridName = "grid{$programID}";
		$DMSID = $_COOKIE ['contextDMSObjectID'];
		
		$js = "
		 var SecureRecordDataDef = Ext.data.Record.create([
            {name: 'dataid'},
            {name: 'name'},
            {name: 'description'},
            {name: 'typeid'},
            {name: 'allow'}
        ]);
        
        var editAllowFlag = new Ext.grid.CheckColumn({
		       header: \"Allow\",
		       dataIndex: 'allow',
		       width: 55
		    })
		    
		    
        var tempCreateSecureGroup = new Ext.data.Store({reader: new Ext.data.JsonReader({}, SecureRecordDataDef)});
        
		var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"Secure Object\",
	           dataIndex: 'name',
	           width: 195
	        },{
	           header: \"Type\",
	           dataIndex: 'description',
	           width: 75,
	           align: 'left'
	        },editAllowFlag  
		]);
		
		/*var {$gridName} = new Ext.grid.GridPanel({*/
		var {$gridName} = new Ext.grid.EditorGridPanel({
	        width: 345,
			height: 165,
			id: '{$gridName}',
	        store: tempCreateSecureGroup,
	        tbar: ['Secure Group Name :',{
				xtype: 'textfield',
				allowBlank: false,
				id: 'secureGroupName',
				name: 'secureGroupName'
			}],
			cm: cm_{$programID},
			plugins: [editAllowFlag],
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
	        loadMask: true,
	        viewConfig: {forceFit:true},
		    bbar: ['ค้นหา',new Ext.form.ComboBox({
					store: autocompleteSecureObjectStore,
					//fieldLabel: 'จาก',
					displayField:'name',
					typeAhead: false,
					emptyText: 'Default',
					loadingText: '{$lang['common']['searcing']}',
					width: 200,
					hideTrigger: true,
					allowBlank: false,
					//labelStyle: 'font-weight:bold;color: Red;',
					name: 'secureGroupSelector',
					id: 'secureGroupSelector',
					tpl: resultSecureObjectTpl,
                    //lazyInit: true,
                    //lazyRender: true,
                    minChars: 2,
                    shadow: false,
                    autoLoad: true,
                    mode: 'remote',
					itemSelector: 'div.search-item-secure-group'
				})/*,{
		 		id: 'btn{$programID}Add_1',
	            text: 'Add',
	            iconCls: 'addIcon',
	            handler: function() {
	            	wndCreatePolicyGroup.show();
				}
	        }, */,'-',{
		 		id: 'btn{$programID}Delete_1',
	            text: 'Delete',
	            iconCls: 'deleteIcon',
	            disabled: true,
	            handler: function() {
	            	tempCreateSecureGroup.remove(tempCreateSecureGroup.getById(Ext.getCmp('{$gridName}').getSelectionModel().getSelected().id));
            		Ext.getCmp('btn{$programID}Delete_1').disable();
				}
	        }
	        ]
	    });
		
		var frmCreatePolicyGroup = new Ext.form.FormPanel({
			id: 'frmCreatePolicyGroup',
			monitorValid:true,
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				fieldLabel: 'ชื่อกลุ่ม',
				xtype: 'hidden',
				name: 'secureGroupNameHidden',
				allowBlank: true
			},{$gridName},{
				xtype: 'hidden',
				fieldLabel: 'ค้นหา',
				name: 'searchMember',
				allowBlank: true
			}],
			buttons: [{
                    formBind: true,
                    text: '{$lang['common']['save']}',
                    handler: function() {
                    	if(Ext.getCmp('secureGroupName').getValue() == '') {
                    		Ext.MessageBox.show({
						    	title: 'แจ้งเตือน',
						    	msg: 'ยังไม่ได้ระบุชื่อกลุ่ม',
						    	buttons: Ext.MessageBox.OK,
						    	icon: Ext.MessageBox.INFO
						    });
						} else {
							var secureIDData = '';
							var secureNameData = '';
							for(i=0;i<tempCreateSecureGroup.getCount();i++) {
		                        dataTempSend = tempCreateSecureGroup.getAt(i);
		                        if(dataTempSend.data.allow || dataTempSend.data.allow==1) {
		                        	var allowFlag = '1';
								} else {
									var allowFlag = '0';
								}
								
		                        if(secureIDData == '') {
		                            secureIDData = secureIDData + dataTempSend.data.typeid+'_'+dataTempSend.data.dataid+'_'+allowFlag;
		                        } else {
		                           secureIDData = secureIDData + ' , '+ dataTempSend.data.typeid+'_'+dataTempSend.data.dataid+'_'+allowFlag;
		                        }
		                        if(secureNameData == '') {
		                            secureNameData = secureNameData + dataTempSend.data.name;
		                        } else {
		                           secureNameData = secureNameData + ' , '+ dataTempSend.data.name;
		                        }
		                    }
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/secure/create-group',
				    			method: 'POST',
				    			success: function(o){
				    				  Ext.MessageBox.hide();
									  var r = Ext.decode(o.responseText);
									  Ext.MessageBox.show({
							    		title: 'การสร้างกลุ่ม Secure Object',
							    		msg: 'การสร้างกลุ่มเรียบร้อยแล้ว',
							    		buttons: Ext.MessageBox.OK,
							    		icon: Ext.MessageBox.INFO
							    	});
							    	wndCreatePolicyGroup.hide();
							    	{$storeName}.reload();
							    	{$storeName2}.reload();
								},
				    			failure: function(r,o) {
				    				Ext.MessageBox.hide();
									Ext.MessageBox.show({
							    		title: 'การสร้างกลุ่ม Secure Object',
							    		msg: 'ไม่สร้างได้',
							    		buttons: Ext.MessageBox.OK,
							    		icon: Ext.MessageBox.ERROR
							    	});
								},
								params: {
									groupName: Ext.getCmp('secureGroupName').getValue(),
									secureID: secureIDData,
									secureName: secureNameData
								}
				    		});
						}
                    }
                },{
					text: '{$lang['common']['cancel']}',
					handler: function() {
						wndCreatePolicyGroup.hide();
					}
				}]
		});
	    
		var wndCreatePolicyGroup = new Ext.Window({
			id: 'wndCreatePolicyGroup',
			title: '{$lang['common']['createPolicyGroup']}',
			width: 375,
			height: 250,
			minWidth: 375,
			minHeight: 250,
			resizable: true,
			modal: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmCreatePolicyGroup,
			closable: false
		});
		
		Ext.getCmp('{$gridName}').on('rowclick',function() {
        	Ext.getCmp('btn{$programID}Delete_1').enable();
		},this);
		
        Ext.getCmp('secureGroupSelector').on('select',function(c,r,i) {         
            dataRecord = c.store.getAt(i);
            var rec = new ReceiverRecordDataDef({
                        dataid: dataRecord.data.id,
                        name: dataRecord.data.name,
                        description: dataRecord.data.desctype,
                        typeid: dataRecord.data.typeid,
                        allow: dataRecord.data.allow
            });
           	tempCreateSecureGroup.add(rec);          
            Ext.getCmp('secureGroupSelector').emptyText = '';
            
            Ext.getCmp('secureGroupSelector').reset();
                  
        },this);
		";
		
		return $js;
	}
	
	/**
	 * สร้างหน้าจอสำหรับการแก้ไข Policy Group
	 *
	 * @return string
	 */
	private function getModifyPolicyGroupJS() {
		global $config;
		global $lang;
		
		$storeName = 'securityGroupStore';
		$storeName2 = 'securityPropertyStore';
		
		$programID = "modifyPolicyGroup";
		$gridParentName = 'securityGroupGrid';
		$gridName = "grid{$programID}";
		$DMSID = $_COOKIE ['contextDMSObjectID'];
		
		$js = "
		 var modifySecureRecordDataDef = Ext.data.Record.create([
            {name: 'dataid'},
            {name: 'name'},
            {name: 'description'},
            {name: 'typeid'},
            {name: 'allow'}
        ]);
        
        var editAllowFlag2 = new Ext.grid.CheckColumn({
		       header: \"Allow\",
		       dataIndex: 'allow',
		       width: 55
		    })
		    
		    
        var tempModifySecureGroup = new Ext.data.Store({reader: new Ext.data.JsonReader({}, modifySecureRecordDataDef)});
        
		var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"Secure Object\",
	           dataIndex: 'name',
	           width: 195
	        },{
	           header: \"Type\",
	           dataIndex: 'description',
	           width: 75,
	           align: 'left'
	        },editAllowFlag2  
		]);
		
		var {$gridName} = new Ext.grid.EditorGridPanel({
	        width: 345,
			height: 165,
			id: '{$gridName}',
	        store: tempModifySecureGroup,
	        tbar: ['Secure Group Name :',{
				xtype: 'textfield',
				allowBlank: false,
				id: 'modifySecureGroupName',
				name: 'modifySecureGroupName'
			}],
			cm: cm_{$programID},
			plugins: [editAllowFlag2],
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
	        loadMask: true,
	        viewConfig: {forceFit:true},
		    bbar: ['ค้นหา',new Ext.form.ComboBox({
					store: autocompleteSecureObjectStore,
					displayField:'name',
					typeAhead: false,
					emptyText: 'Default',
					loadingText: '{$lang['common']['searcing']}',
					width: 200,
					hideTrigger: true,
					allowBlank: false,
					name: 'modifySecureGroupSelector',
					id: 'modifySecureGroupSelector',
					tpl: resultSecureObjectTpl,
                    minChars: 2,
                    shadow: false,
                    autoLoad: true,
                    mode: 'remote',
					itemSelector: 'div.search-item-secure-group'
				}),'-',{
		 		id: 'btn{$programID}Delete_1',
	            text: 'Delete',
	            iconCls: 'deleteIcon',
	            disabled: true,
	            handler: function() {
	            	tempModifySecureGroup.remove(tempModifySecureGroup.getById(Ext.getCmp('{$gridName}').getSelectionModel().getSelected().id));
            		Ext.getCmp('btn{$programID}Delete_1').disable();
				}
	        }
	        ]
	    });
		
		var frmModifyPolicyGroup = new Ext.form.FormPanel({
			id: 'frmModifyPolicyGroup',
			monitorValid:true,
			baseCls: 'x-plain',
			labelWidth: 100,
			defaultType: 'textfield',
			layout: 'form',
			items: [{
				fieldLabel: 'รหัสกลุ่ม',
				xtype: 'hidden',
				name: 'modifySecureGroupIDHidden',
				allowBlank: true
			},{
				fieldLabel: 'ชื่อกลุ่ม',
				xtype: 'hidden',
				name: 'modifySecureGroupNameHidden',
				allowBlank: true
			},{$gridName},{
				xtype: 'hidden',
				fieldLabel: 'ค้นหา',
				name: 'searchEditMember',
				allowBlank: true
			}],
			buttons: [{
                    formBind: true,
                    text: '{$lang['common']['save']}',
                    handler: function() {
                    	if(Ext.getCmp('modifySecureGroupName').getValue() == '') {
                    		Ext.MessageBox.show({
						    	title: 'แจ้งเตือน',
						    	msg: 'ยังไม่ได้ระบุชื่อกลุ่ม',
						    	buttons: Ext.MessageBox.OK,
						    	icon: Ext.MessageBox.INFO
						    });
						} else {
							var secureIDData = '';
							var secureNameData = '';
							for(i=0;i<tempModifySecureGroup.getCount();i++) {
		                        dataTempSend = tempModifySecureGroup.getAt(i);
		                        if(dataTempSend.data.allow || dataTempSend.data.allow==1) {
		                        	var allowFlag = '1';
								} else {
									var allowFlag = '0';
								}
								
		                        if(secureIDData == '') {
		                            secureIDData = secureIDData + dataTempSend.data.typeid+'_'+dataTempSend.data.dataid+'_'+allowFlag;
		                        } else {
		                           secureIDData = secureIDData + ' , '+ dataTempSend.data.typeid+'_'+dataTempSend.data.dataid+'_'+allowFlag;
		                        }
		                        if(secureNameData == '') {
		                            secureNameData = secureNameData + dataTempSend.data.name;
		                        } else {
		                           secureNameData = secureNameData + ' , '+ dataTempSend.data.name;
		                        }
		                    }
							Ext.Ajax.request({
				    			url: '/{$config ['appName']}/secure/modify-group',
				    			method: 'POST',
				    			success: function(o){
				    				  Ext.MessageBox.hide();
									  var r = Ext.decode(o.responseText);
									  Ext.MessageBox.show({
							    		title: 'การแก้ไขกลุ่ม Secure Object',
							    		msg: 'การแก้ไขกลุ่มเรียบร้อยแล้ว',
							    		buttons: Ext.MessageBox.OK,
							    		icon: Ext.MessageBox.INFO
							    	});
							    	wndModifyPolicyGroup.hide();
							    	{$storeName}.reload();
							    	{$storeName2}.reload();
							    	
								},
				    			failure: function(r,o) {
				    				Ext.MessageBox.hide();
									Ext.MessageBox.show({
							    		title: 'การสร้างกลุ่ม Secure Object',
							    		msg: 'ไม่สร้างได้',
							    		buttons: Ext.MessageBox.OK,
							    		icon: Ext.MessageBox.ERROR
							    	});
								},
								params: {
									groupID: {$gridParentName}.getSelectionModel().getSelected().get('id'),
									groupName: {$gridParentName}.getSelectionModel().getSelected().get('name'),
									secureID: secureIDData,
									secureName: secureNameData
								}
				    		});
						}
                    }
                },{
					text: '{$lang['common']['cancel']}',
					handler: function() {
						wndModifyPolicyGroup.hide();
					}
				}]
		});
	    
		var wndModifyPolicyGroup = new Ext.Window({
			id: 'wndModifyPolicyGroup',
			title: '{$lang['common']['modifyPolicyGroup']}',
			width: 375,
			height: 250,
			minWidth: 375,
			minHeight: 250,
			resizable: true,
			modal: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmModifyPolicyGroup,
			closable: false
		});
		
		Ext.getCmp('{$gridName}').on('rowclick',function() {
        	Ext.getCmp('btn{$programID}Delete_1').enable();
		},this);
		
        Ext.getCmp('modifySecureGroupSelector').on('select',function(c,r,i) {         
            dataRecord = c.store.getAt(i);
            var rec = new ReceiverRecordDataDef({
                        dataid: dataRecord.data.id,
                        name: dataRecord.data.name,
                        description: dataRecord.data.desctype,
                        typeid: dataRecord.data.typeid,
                        allow: dataRecord.data.allow
            });
           	tempModifySecureGroup.add(rec);          
            Ext.getCmp('modifySecureGroupSelector').emptyText = '';
            Ext.getCmp('modifySecureGroupSelector').reset();
                  
        },this);
		";
		
		return $js;
	}
}
