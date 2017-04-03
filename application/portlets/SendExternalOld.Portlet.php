<?php

/**
 * Portlet : หนังสือส่งภายนอก(ยกเลิก)
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class SendExternalPortlet {
	
	public function __construct() {
		//include_once 'DFStore.php';
	}
	
	public function getUI() {
		global $config;
		global $conn;
		global $store;
		global $lang;
		global $sessionMgr;
		
		checkSessionPortlet();
		
		$dfStore = new DFStore();
		
		$sqlGetFilter = "select * from tbl_doc_type order by f_doctype_id";
		$rs = $conn->CacheExecute($config ['defaultCacheSecs'],$sqlGetFilter);
		$DIVName = "SendExternalUIDIV";
		$programID = "SE_0";
		$gridName = 'gridSendExternalItem';
		$filterMenuName = 'SEFilteredMenu';
		
		$filterMenu = "
		var {$filterMenuName} = new Ext.menu.Menu({
        	id: 'mainMenu_{$programID}'
        	,items: [
        	";
		
		$filterItems = "{
				id: '-1',
				text: '{$lang['common']['undefined']}'
				,checked: true
			}";
		
		foreach($rs as $row) {
			checkKeyCase($row);
			$filterItems .= ",{
				id: '{$row['f_doctype_id']}',
				text: '{$row['f_name']}'
				,checked: true
			}";
		}
	         
		$filterMenu .= "$filterItems]}
	    );";
		
	    $orgID = $sessionMgr->getCurrentOrgID();
	    $regBookID = 0;
	    $storeName = "SEStore_{$regBookID}";
	    $RIStore = $dfStore->getSEStore($regBookID,$storeName);
		$accountStore = $store->getDataStore('account');
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		//Received Internal Data Store
		{$RIStore}
		
		{$storeName}.setDefaultSort('sendNo', 'desc');
	
	   

	    var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"{$lang['df']['recvNo']}\",
	           dataIndex: 'sendNo',
	           width: 50
	        },{
	           header: \"{$lang['df']['docNo']}\",
	           dataIndex: 'docNo',
	           width: 100
	        },{
	           header: \"{$lang['df']['title']}\",
	           dataIndex: 'title',
	           width: 130,
	           align: 'left'
	        },{
	           header: \"{$lang['df']['date']}\",
	           dataIndex: 'docDate',
	           width: 95
	           //,renderer: renderAccountType
		    },{
		       header: \"{$lang['df']['from']}\",
		       dataIndex: 'from',
		       width: 150
		       //,renderer: renderAccountStatus
		    },{
		       header: \"{$lang['df']['to']}\",
		       dataIndex: 'to',
		       width: 150
		       //,renderer: renderAccountStatus
		    },{
		       header: \"{$lang['df']['action']}\",
		       dataIndex: 'command',
		       width: 150
		       //,renderer: renderAccountStatus
		    }
		    
		]);
	
	    cm_{$programID}.defaultSortable = true;
	
	    var {$gridName} = new Ext.grid.GridPanel({
	        //el:'topic-grid',
	        //
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
	        //autoWidth: true,
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: {$storeName},
	        
	        tbar: new Ext.Toolbar({
				id: '{$programID}_Toolbar',
				height: 25				
			}),
			cm: cm_{$programID},
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
	        loadMask: true,
	        enableDragDrop : true,
	        renderTo: '{$DIVName}',
	        viewConfig: {
	            forceFit:true,
	            enableRowBody:true,
	            showPreview: false,
	            getRowClass : function(record, rowIndex, p, store){
	                if(this.showPreview){
	                    p.body = '<p> Login : '+record.data.login+'</p>';
	                    return 'x-grid3-row-expanded';
	                }
	                return 'x-grid3-row-collapsed';
	            }
	        },
	        bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: {$storeName},
	            displayInfo: true,
	            displayMsg: '{$lang['df']['receivedInternal']} {0} - {1} {$lang['common']['of']} {2}',
	            emptyMsg: \"{$lang['df']['noDocument']}\",
	            items:[
	                '-', {
	                pressed: false,
	                enableToggle: true,
	                text: '{$lang['df']['viewdetail']}',
	                cls: 'x-btn-text-icon details',
					toggleHandler: toggle{$programID}Details
	            }]
	        })
	    });
    	$filterMenu
    	var tb_{$programID} = Ext.getCmp('{$programID}_Toolbar');
    	
		tb_{$programID}.add({
            text:'{$lang['df']['filter']}',
            iconCls: 'bmenu',  // <-- icon
            menu: {$filterMenuName}  // assign menu by instance
        },{
	 		//id: 'btnPreviewDocument',
            text:'{$lang['df']['view']}',
            iconCls: 'bmenu',
            handler: function() {
            	//accountAddForm.getForm().reset();
				//accountAddWindow.show();
			}
        },{
        	//id: 'btnAccept',
            text:'{$lang['df']['track']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//setPasswordForm.getForm().reset();
				//setPasswordWindow.show();
			}
        },{
        	//id: 'btnSendBack',
            text:'{$lang['df']['saveToTrack']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	//id: 'btnSendBack',
            text:'{$lang['df']['forward']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	//id: 'btnSendBack',
            text:'{$lang['df']['return']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	//id: 'btnSendBack',
            text:'{$lang['df']['pause']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	//id: 'btnSendBack',
            text:'{$lang['df']['viewlog']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
            text:'{$lang['df']['fetch']}',
            iconCls: 'bmenu',
            handler: function(){
            	{$storeName}.load({params:{start:0, limit:25}});
            	//accountStore.reload();
			}
        });

	    // render it
		{$gridName}.render();
	
	    // trigger the data store load
	    //accountStore.load({params:{start:0, limit:25}});
	    {$storeName}.load({params:{start:0, limit:25}});
	    
		{$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    
	{$gridName}.on('rowdblclick',function() {
	    	alert('dbl click');
		}
		,{$gridName});
		
	{$gridName}.on({
			'rowclick' : function() {
				/*
				Ext.getCmp('btnSetPassword').enable();
				Ext.getCmp('btnDeleteAccount').enable();
				Ext.getCmp('btnToggleAccountStatus').enable();
				Ext.getCmp('ecmPropertyGrid').setTitle('Account Editor');
				Ext.getCmp('ecmPropertyGrid').on('propertychange',function(source,recordId,value,oldValue) {
					Ext.Ajax.request({
		    			url: '/{$config ['appName']}/account-manager/set-account-property',
		    			method: 'POST',
		    			params: {
		    				accountID: {$gridName}.getSelectionModel().getSelected().get('id'),
		    				accountProperty: recordId,
		    				accountValue: value
						}//,
		    			//success: function() {
		    			//	accountStore.reload();
						//},
		    			//failure: rankAddFailed,
		    			//form: Ext.getCmp('policyAddForm').getForm().getEl()
	    			});
				});
				
				
				loadAccountPropertyGrid({$gridName}.getSelectionModel().getSelected().get('id'));
				*/
			},
			scope: this
		});
		
	    function toggle{$programID}Details(btn, pressed){
	        var view_{$programID} = {$gridName}.getView();
			view_{$programID}.showPreview = pressed;
			view_{$programID}.refresh();
	    }
	    /*
		function loadAccountPropertyGrid(id) {
			var accountInfoStore = new Ext.data.Store({
				autoLoad: true,
				proxy: new Ext.data.ScriptTagProxy({
					url: '/{$config ['appName']}/data-store/account-property?accountID='+id
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
			
			accountInfoStore.on('load',function(store,records,options) {
				var Property = Ext.data.Record.create([
					{name: 'name'},
					{name: 'value'},
					{name: 'group'},
					{name: 'editor'}
				]);
				var tempStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, Property)});
				
				var tempStore = new Ext.data.GroupingStore({
					groupField: 'group',
					sortInfo: {field:'name',direction:'ASC'},
					reader: new Ext.data.JsonReader({}, Property)
				});
	
				//alert(Ext.getCmp('ecmPropertyGrid').getColumnModel().getColumnWidth(0));
				Ext.getCmp('ecmPropertyGrid').getColumnModel().setColumnWidth(0,100);
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
			
			accountInfoStore.load();
		}
		*/
	    
    
		</script>";
	}
}