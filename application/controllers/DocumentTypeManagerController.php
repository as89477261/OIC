<?php

/**
 * โปรแกรมจัดการประเภทเอกสาร
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Document
 * 
 *
 */

class DocumentTypeManagerController extends ECMController {
	public function getUiAction() {
		global $store;
		
		$doctypeStore = $store->getDataStore ( 'documentType' );
		$doctypeComboStore = $store->getDataStore ( 'documentType', 'documentTypeComboStore' );
		//$rankDataStore = $store->getDataStore ( 'rank' );
		//$rankDataStoreSelect = $store->getDataStore ( 'rank', 'rankStoreSelect' );
		//$createSeq = $store->getDataStore ( 'createSequence' );
		//$createStatus = $store->getDataStore ( 'createStatus' );
		

		//$rankAddForm = $this->getRankAdd ();
		//$rankModifyForm = $this->getRankModify ();
		

		/* prepare DIV For UI */
		echo "<div id=\"doctypeUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		/* Remote Data Store for Ranks */
		$doctypeStore
		$doctypeComboStore
		
		documentTypeStore.setDefaultSort('id', 'desc');
		
		documentTypeComboStore.load();
		
		var gridDocType = new Ext.grid.GridPanel({
			id: 'gridDocType',
			store: documentTypeStore,
			tbar: new Ext.Toolbar({
					id: 'docTypeToolbar',
					height: 25				
				}),
			autoExpandMax: true,
			columns: [
			{id: 'id', header: 'Document Type', width: 200, sortable: false, dataIndex: 'name'},
			{header: 'Description', width: 200, sortable: false, dataIndex: 'desc'},
			{header: 'Organize', width: 150, sortable: false, dataIndex: 'orgid'},
			{header: 'Level', width: 120, sortable: false, dataIndex: 'status'}
			],
			viewConfig: {
				forceFit: true
			},
			loadMask: true,
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
			frame: false,
			iconCls:'icon-grid',
			renderTo:'doctypeUIDiv'
		});
		
		var tbDocType = Ext.getCmp('docTypeToolbar');
		
	 	tbDocType.add({
	 		id: 'btnDocTypeAdd',
            text:'New Document Type',
            iconCls: 'addIcon',
            handler: function() {
            	//documentTypeStore.getForm().reset();
				//rankAddWindow.show();
			}
        },{
        	id: 'btnModifyDocType',
            text:'Modify Document Type',
            iconCls: 'editIcon',
            disabled: true, 
            handler: function() {
            	//rankModifyWindow.show();
            	//rankModifyForm.getForm().setValues([
            	//	{id:'rankModID',value: gridRankAdmin.getSelectionModel().getSelected().get('id')},
				//	{id:'rankModName',value: gridRankAdmin.getSelectionModel().getSelected().get('name')},
				//	{id:'rankModDescription',value: gridRankAdmin.getSelectionModel().getSelected().get('description')}
            	//]);
			}
        },{
        	id: 'btnDeleteDocType',
            text:'Delete Document Type',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Rank [ '+gridRankAdmin.getSelectionModel().getSelected().get('name')+']?', deleteSelectedRank);
			}
        },{
        	id: 'btnToggleDocTypeStatus',
            text:'Disable/Enable Document Type',
            iconCls: 'toggleIcon',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Toggle Rank [ '+gridRankAdmin.getSelectionModel().getSelected().get('name')+'] Status?', toggleRankStatus);
			}
        },{
            text:'Refresh View',
            iconCls: 'refreshIcon',
            handler: function(){
            	documentTypeStore.load();
            	documentTypeComboStore.load();
			}
        });
        
		gridDocType.on({
		'rowclick' : function() {
			Ext.getCmp('btnModifyDocType').enable();
			Ext.getCmp('btnDeleteDocType').enable();
			Ext.getCmp('btnToggleDocTypeStatus').enable();
		},
		scope: this
		});
		gridDocType.render();
		documentTypeStore.load();
		</script>";
	}

}
