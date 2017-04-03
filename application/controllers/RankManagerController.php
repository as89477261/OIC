<?php
/**
 * โปรแกรมจัดการระดับขั้น
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */
class RankManagerController extends Zend_Controller_Action {
	/**
	 * สร้างฟอร์มสร้างระดับขั้น
	 *
	 * @return string
	 */	
	private function getRankAdd() {
		global $config;
		global $lang;
		
		$js = "var rankAddForm = new Ext.form.FormPanel({
			id: 'rankAddForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			url:'save-form.php',
			defaultType: 'textfield',
			monitorValid:true,

			items: [{
				fieldLabel: '{$lang['rank']['name']}',
				allowBlank: false,
				name: 'rankName',
				width: 200
			},new Ext.form.ComboBox({
				allowBlank: false,
				name: 'rankCreateSequence',
				fieldLabel: '{$lang['rank']['seq']}',
				store: createSequeceStore,
				displayField:'name',
				valueField: 'value',
				typeAhead: false,
				mode: 'local',
				triggerAction: 'all',
				value: 1,
				emptyText:'Select Create Sequence',
				selectOnFocus:true,
				width: 200
			}),new Ext.form.ComboBox({
				name: 'rankCreateStack',
				fieldLabel: '{$lang['rank']['stack']}',
				store: rankStoreSelect,
				displayField:'name',
				valueField: 'id',
				typeAhead: true,
				mode: 'local',
				triggerAction: 'all',
				emptyText:'Select Rank Stack',
				selectOnFocus:true
			}),new Ext.form.ComboBox({
				name: 'rankStatus',
				allowBlank: false,
				fieldLabel: '{$lang['rank']['status']}',
				store: createStatusStore,
				displayField:'name',
				valueField: 'value',
				typeAhead: true,
				mode: 'local',
				triggerAction: 'all',
				emptyText:'Select Rank Status',
				value: 0,
				selectOnFocus:true
			}),{
				name: 'rankDescription',
				xtype: 'textarea',
				fieldLabel: '{$lang['common']['desc']}',
				hideLabel: false,
				width: '200',
				height: '100'
			}],
			buttons: [{
				id: 'btnSaveRank',
				text: '{$lang['common']['save']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
					
	    			rankAddWindow.hide();
	    			
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
	    				url: '/{$config ['appName']}/rank-manager/add-rank',
	    				method: 'POST',
	    				success: rankAddSuccess,
	    				failure: rankAddFailed,
	    				form: Ext.getCmp('rankAddForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelSaveRank',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					rankAddWindow.hide();
				}
			}]
		});";
		
		$js .= "var rankAddWindow = new Ext.Window({
			id: 'rankAddWindow',
			title: '{$lang['rank']['add']}',
			width: 375,
			height: 300,
			minWidth: 375,
			modal: true,
			minHeight: 300,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: rankAddForm,
			closable: false,
			resizable: false
		});
		
		";
		
		$js .= "function rankAddSuccess() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function rankAddFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank deleted successfully */
		$js .= "function deleteRankSuccess() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank undeleted or deleted unsuccessfully */
		$js .= "function deleteRankFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank Moved-Up successfully */
		$js .= "function moveRankUpSuccess() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank Moved-Up unsuccessfully */
		$js .= "function moveRankUpFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank Moved-Down successfully */
		$js .= "function moveRankDownSuccess() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank Moved-Down unsuccessfully */
		$js .= "function moveRankDownFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		/* Rank toggled successfully */
		$js .= "function toggleRankSuccess() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		/* Rank toggled unsuccessfully */
		$js .= "function toggleRankFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		$js .= "function deleteSelectedRank(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/rank-manager/delete-rank',
	    				method: 'POST',
	    				success: deleteRankSuccess,
	    				failure: deleteRankFailed,
	    				params: { id: gridRankAdmin.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function moveUpSelectedRank(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/rank-manager/move-rank-up',
	    				method: 'POST',
	    				success: moveRankUpSuccess,
	    				failure: moveRankUpFailed,
	    				params: { id: gridRankAdmin.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function moveDownSelectedRank(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/rank-manager/move-rank-down',
	    				method: 'POST',
	    				success: moveRankDownSuccess,
	    				failure: moveRankDownFailed,
	    				params: { id: gridRankAdmin.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		$js .= "function toggleRankStatus(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/rank-manager/toggle-rank',
	    				method: 'POST',
	    				success: toggleRankSuccess,
	    				failure: toggleRankFailed,
	    				params: { id: gridRankAdmin.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}";
		
		return $js;
	}
	
	/**
	 * สร้างฟอร์มแก้ไขระดับขั้น
	 *
	 * @return string
	 */
	private function getRankModify() {
		global $config;
		global $lang;
		
		$js = "var rankModifyForm = new Ext.form.FormPanel({
			id: 'rankModifyForm',
			baseCls: 'x-plain',
			labelWidth: 100,
			url:'save-form.php',
			defaultType: 'textfield',

			items: [{
				id: 'rankModID',
				fieldLabel: 'Rank ID',
				name: 'rankModID',
				inputType: 'hidden',
				monitorValid: true,
				width: 200
			},{
				id: 'rankModName',
				fieldLabel: '{$lang['rank']['name']}',
				name: 'rankModName',
				monitorValid: true,
				width: 200
			},{
				id: 'rankModDescription',
				name: 'rankModDescription',
				xtype: 'textarea',
				fieldLabel: '{$lang['common']['desc']}',
				hideLabel: false,
				width: '200',
				height: '100'
			}],
			buttons: [{
				id: 'btnUpdateRank',
				text: '{$lang['common']['modify']}',
				formBind: true,
				iconCls: 'saveIcon',
				handler: function() {
					
	    			rankModifyWindow.hide();
	    			
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
	    				url: '/{$config ['appName']}/rank-manager/modify-rank',
	    				method: 'POST',
	    				success: rankModifySuccess,
	    				failure: rankModifyFailed,
	    				form: Ext.getCmp('rankModifyForm').getForm().getEl()
	    			});
	    		}
			},{
				id: 'btnCancelUpdateRank',
				text: '{$lang['common']['cancel']}',
				iconCls: 'cancelIcon',
				handler: function() {
					rankModifyWindow.hide();
				}
			}]
		});";
		
		$js .= "var rankModifyWindow = new Ext.Window({
			id: 'rankModifyWindow',
			title: '{$lang['rank']['edit']}',
			width: 375,
			height: 250,
			minWidth: 375,
			minHeight: 250,
			modal: true,
			resizable: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: rankModifyForm,
			closable: false
		});
		
		";
		
		$js .= "function rankModifySuccess() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['success']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}";
		
		$js .= "function rankModifyFailed() {
			Ext.MessageBox.hide();
			
			rankStore.load();
			rankStoreSelect.load();
			
			Ext.MessageBox.show({
	    		title: '{$lang['rank']['manager']}',
	    		msg: '{$lang['common']['failed']}',
	    		buttons: Ext.MessageBox.OK,
	    		animEl: Ext.getCmp('btnRankAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}";
		
		return $js;
	}
	/**
	 * action /get-ui แสดงหน้าจอโปรแกรมจัดการระดับขั้น
	 *
	 */
	public function getUiAction() {
		global $store;
		global $lang;
		checkSessionPortlet();
		
		$rankDataStore = $store->getDataStore ( 'rank' );
		$rankDataStoreSelect = $store->getDataStore ( 'rank', 'rankStoreSelect' );
		$createSeq = $store->getDataStore ( 'createSequence' );
		$createStatus = $store->getDataStore ( 'createStatus' );
		$rankAddForm = $this->getRankAdd ();
		$rankModifyForm = $this->getRankModify ();
		
		/* prepare DIV For UI */
		echo "<div id=\"rankUIToolbarDiv\" display=\"inline\"></div>";
		echo "<div id=\"rankUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		var cmRank = new Ext.grid.ColumnModel([{
			   id: 'id',
	           header: \"{$lang['rank']['name']}\",
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
	           width: 95,
	           renderer: renderStatus
		    }
		]);
		
		/* Remote Data Store for Ranks */
		$rankDataStore
		$rankDataStoreSelect
		$createSeq
		$createStatus
		rankStore.setDefaultSort('id', 'desc');
		
		
		$rankAddForm
		$rankModifyForm
		
		var rankUIAdminToolbar = new Ext.Toolbar({height: 30});
				
		var gridRankAdmin = new Ext.grid.GridPanel({
			id: 'gridRank',
			store: rankStore,
			tbar: new Ext.Toolbar({
					id: 'adminRankToolbar',
					height: 25				
				}),
			autoExpandMax: true,
			cm: cmRank,
			viewConfig: {
				forceFit: true
			},
			loadMask: true,
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
			frame: false,
			iconCls:'icon-grid',
			renderTo:'rankUIDiv'
		});
		
		var tbRank = Ext.getCmp('adminRankToolbar');
		
	 	tbRank.add({
	 		id: 'btnRankAdd',
            text:'" . $lang ['common'] ['create'] . "',
            iconCls: 'addIcon',
            handler: function() {
            	rankAddForm.getForm().reset();
				rankAddWindow.show();
			}
        },{
        	id: 'btnModifyRank',
            text:'" . $lang ['common'] ['modify'] . "',
            iconCls: 'editIcon',
            disabled: true, 
            handler: function() {
            	rankModifyWindow.show();
            	rankModifyForm.getForm().setValues([
            		{id:'rankModID',value: gridRankAdmin.getSelectionModel().getSelected().get('id')},
					{id:'rankModName',value: gridRankAdmin.getSelectionModel().getSelected().get('name')},
					{id:'rankModDescription',value: gridRankAdmin.getSelectionModel().getSelected().get('description')}
            	]);
			}
        },{
        	id: 'btnDeleteRank',
            text:'" . $lang ['common'] ['delete'] . "',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['rank']['delete']} [ '+gridRankAdmin.getSelectionModel().getSelected().get('name')+']?', deleteSelectedRank);
			}
        },{
        	id: 'btnMoveRankUp',
            text:'" . $lang ['common'] ['moveUp'] . "',
            iconCls: 'upIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['common']['moveUp']} [ '+gridRankAdmin.getSelectionModel().getSelected().get('name')+']?', moveUpSelectedRank);
			}
        },{
        	id: 'btnMoveRankDown',
            text:'" . $lang ['common'] ['moveDown'] . "',
            iconCls: 'downIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['common']['moveDown']} [ '+gridRankAdmin.getSelectionModel().getSelected().get('name')+']?', moveDownSelectedRank);
			}
        },{
        	id: 'btnToggleRankStatus',
            text:'" . $lang ['common'] ['toggle'] . "',
            iconCls: 'toggleIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['common']['toggle']} [ '+gridRankAdmin.getSelectionModel().getSelected().get('name')+']?', toggleRankStatus);
			}
        },{
            text:'" . $lang ['common'] ['refresh'] . "',
            iconCls: 'refreshIcon',
            handler: function(){
            	rankStore.load();
            	rankStoreSelect.load();
			}
        });
        
        
		gridRankAdmin.on({
		'rowclick' : function() {
			Ext.getCmp('btnModifyRank').enable();
			Ext.getCmp('btnDeleteRank').enable();
			Ext.getCmp('btnMoveRankUp').enable();
			Ext.getCmp('btnMoveRankDown').enable();
			Ext.getCmp('btnToggleRankStatus').enable();
		},
		scope: this
		});
		
		gridRankAdmin.render();
		
		rankStore.load();
		rankStoreSelect.load();
		
		</script>";
	}
	/**
	 * action /add-rank สร้างระดับขั้น
	 *
	 */
	public function addRankAction() {
		global $conn;
		global $sequence;
		
		$array_post_index = array ('rankName' => FILTER_SANITIZE_STRING, 'rankCreateSequence' => FILTER_SANITIZE_STRING, 'rankCreateStack' => FILTER_SANITIZE_STRING, 'rankStatus' => FILTER_SANITIZE_STRING, 'rankDescription' => FILTER_SANITIZE_STRING );
		$postData = filter_input_array ( INPUT_POST, $array_post_index );
		
		if (! $sequence->isExists ( 'rankID' )) {
			$sequence->create ( 'rankID' );
		}
		
		if ($postData ['rankCreateSequence'] == 'Before') {
			if (trim ( $postData ['rankCreateStack'] ) != 'Select Rank Stack') {
				$postData ['rankCreateSequence'] = 'Before';
			
			} else {
				$postData ['rankCreateSequence'] = 'Top';
			}
		}
		
		if ($postData ['rankCreateSequence'] == 'After') {
			if (trim ( $postData ['rankCreateStack'] ) != 'Select Rank Stack') {
				$postData ['rankCreateSequence'] = 'After';
			} else {
				$postData ['rankCreateSequence'] = 'Bottom';
			}
		}
		
		switch ($postData ['rankCreateSequence']) {
			case 'Before' :
				$rankEntity = new RankEntity ( );
				$rankStack = UTFDecode( $postData ['rankCreateStack'] );
				$rankEntity->Load ( "f_rank_name = '{$rankStack}'" );
				$rankStackLevel = $rankEntity->f_rank_level;
				$sqlUpdateRank = "update tbl_rank set f_rank_level = f_rank_level +1 where f_rank_level >= {$rankStackLevel}";
				$conn->Execute ( $sqlUpdateRank );
				
				$rankEntity = new RankEntity ( );
				$rankEntity->f_rank_id = $sequence->get ( 'rankID' );
				$rankEntity->f_rank_name = UTFDecode( $postData ['rankName'] );
				$rankEntity->f_rank_level = $rankStackLevel;
				$rankEntity->f_rank_desc = UTFDecode( $postData ['rankDescription'] );
				$rankEntity->f_rank_status = 1;
				
				$conn->StartTrans ();
				$rankEntity->Save ();
				$conn->CompleteTrans ();
				break;
			
			case 'Top' :
				$sqlUpdateRank = "update tbl_rank set f_rank_level = f_rank_level +1";
				$conn->Execute ( $sqlUpdateRank );
				
				$rankEntity = new RankEntity ( );
				$rankEntity->f_rank_id = $sequence->get ( 'rankID' );
				$rankEntity->f_rank_name = UTFDecode( $postData ['rankName'] );
				$rankEntity->f_rank_level = 1;
				$rankEntity->f_rank_desc = UTFDecode( $postData ['rankDescription'] );
				$rankEntity->f_rank_status = 1;
				
				$conn->StartTrans ();
				$rankEntity->Save ();
				$conn->CompleteTrans ();
				break;
			
			case 'After' :
				$rankEntity = new RankEntity ( );
				$rankStack = UTFDecode( $postData ['rankCreateStack'] );
				$rankEntity->Load ( "f_rank_name = '{$rankStack}'" );
				$rankStackLevel = $rankEntity->f_rank_level + 1;
				$sqlUpdateRank = "update tbl_rank set f_rank_level = f_rank_level +1 where f_rank_level >= {$rankStackLevel}";
				$conn->Execute ( $sqlUpdateRank );
				
				$rankEntity = new RankEntity ( );
				$rankEntity->f_rank_id = $sequence->get ( 'rankID' );
				$rankEntity->f_rank_name = UTFDecode( $postData ['rankName'] );
				$rankEntity->f_rank_level = $rankStackLevel;
				$rankEntity->f_rank_desc = UTFDecode( $postData ['rankDescription'] );
				$rankEntity->f_rank_status = 1;
				
				$conn->StartTrans ();
				$rankEntity->Save ();
				$conn->CompleteTrans ();
				break;
			
			case 'Bottom' :
				$sqlGetMaxLevel = "select max(f_rank_level)+1 as max_level from tbl_rank";
				$rsGetMaxLevel = $conn->Execute ( $sqlGetMaxLevel );
				$maxLevel = $rsGetMaxLevel->FetchNextObject ();
				
				$rankEntity = new RankEntity ( );
				$rankEntity->f_rank_id = $sequence->get ( 'rankID' );
				$rankEntity->f_rank_name = UTFDecode( $postData ['rankName'] );
				$rankEntity->f_rank_level = $maxLevel->MAX_LEVEL;
				$rankEntity->f_rank_desc = UTFDecode( $postData ['rankDescription'] );
				$rankEntity->f_rank_status = 1;
				
				$conn->StartTrans ();
				$rankEntity->Save ();
				$conn->CompleteTrans ();
				break;
		}
	}
	/**
	 * action /delete-rank ลบระดับขั้น
	 *
	 */
	public function deleteRankAction() {
		global $conn;
		
		$rankID = $_POST ['id'];
		
		$rankEntity = new RankEntity ( );
		$rankEntity->Load ( "f_rank_id = '{$rankID}'" );
		$rankLevel = $rankEntity->f_rank_level;
		
		$sqlUpdateRemainRanks = "update tbl_rank set f_rank_level = f_rank_level-1 where f_rank_level > {$rankLevel}";
		$conn->StartTrans ();
		$rankEntity->Delete ();
		$conn->CompleteTrans ();
		unset ( $rankEntity );
		
		$conn->StartTrans ();
		$conn->Execute ( $sqlUpdateRemainRanks );
		$conn->CompleteTrans ();
	}
	/**
	 * action /move-rank-up เลื่อนระดับขั้นขึ้น
	 *
	 */
	public function moveRankUpAction() {
		global $conn;
		$rankID = $_POST ['id'];
		
		$rankEntity = new RankEntity ( );
		$rankEntity->Load ( "f_rank_id = '{$rankID}'" );
		$selfRankLevel = $rankEntity->f_rank_level;
		if ($selfRankLevel > 1) {
			$upperRankLevel = $rankEntity->f_rank_level - 1;
			$rankEntity2 = new RankEntity ( );
			$rankEntity2->Load ( "f_rank_level = '{$upperRankLevel}'" );
			
			$rankEntity->f_rank_level = $upperRankLevel;
			$rankEntity2->f_rank_level = $selfRankLevel;
			
			$conn->StartTrans ();
			$rankEntity->Update ();
			$rankEntity2->Update ();
			$conn->CompleteTrans ();
		}
	}
	/**
	 * action /move-rank-down ลดระดับขั้นลง
	 *
	 */
	public function moveRankDownAction() {
		global $conn;
		$rankID = $_POST ['id'];
		
		$sqlGetMaxLevel = "select max(f_rank_level) as max_level from tbl_rank";
		$rsGetMaxLevel = $conn->Execute ( $sqlGetMaxLevel );
		$maxLevel = $rsGetMaxLevel->FetchNextObject ();
		
		$rankEntity = new RankEntity ( );
		$rankEntity->Load ( "f_rank_id = '{$rankID}'" );
		$selfRankLevel = $rankEntity->f_rank_level;
		if ($maxLevel->MAX_LEVEL > $selfRankLevel) {
			$lowerRankLevel = $rankEntity->f_rank_level + 1;
			$rankEntity2 = new RankEntity ( );
			$rankEntity2->Load ( "f_rank_level = '{$lowerRankLevel}'" );
			
			$rankEntity->f_rank_level = $lowerRankLevel;
			$rankEntity2->f_rank_level = $selfRankLevel;
			
			$conn->StartTrans ();
			$rankEntity->Update ();
			$rankEntity2->Update ();
			$conn->CompleteTrans ();
		}
	}
	/**
	 * action /toggle-rank แก้ไขสถานะระดับขั้น
	 *
	 */
	public function toggleRankAction() {
		global $conn;
		$rankID = $_POST ['id'];
		
		$rankEntity = new RankEntity ( );
		$rankEntity->Load ( "f_rank_id = '{$rankID}'" );
		if ($rankEntity->f_rank_status == 1) {
			$rankEntity->f_rank_status = 0;
		} else {
			$rankEntity->f_rank_status = 1;
		}
		$conn->StartTrans ();
		$rankEntity->Update ();
		$conn->CompleteTrans ();
	}
	/**
	 * action /modify-rank แก้ไขระดับขั้น
	 *
	 */
	public function modifyRankAction() {
		global $conn;
		$rankID = $_POST ['rankModID'];
		$rankName = UTFDecode( $_POST ['rankModName'] );
		$rankDescription = UTFDecode( $_POST ['rankModDescription'] );
		
		$rankEntity = new RankEntity ( );
		$rankEntity->Load ( "f_rank_id = '{$rankID}'" );
		$rankEntity->f_rank_name = $rankName;
		$rankEntity->f_rank_desc = $rankDescription;
		
		$conn->StartTrans ();
		$rankEntity->Update ();
		$conn->CompleteTrans ();
	}
}
