<?php
/**
 * โปรแกรมจัดการ Concurrent
 * 
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */

class ConcurrentManagerController extends ECMController  {
	/**
	 * หน้าจอแสดงโปรแกรมจัดการ Concurrent
	 *
	 */
	public function getUiAction() {
		global $config;
		global $store;
		
		checkSessionPortlet();
		
		$concurrentStore = $store->getDataStore('concurrent');
		
		/* prepare DIV For UI */
		echo "<div id=\"concurrentUIToolbarDiv\" display=\"inline\"></div>";
		echo "<div id=\"concurrentUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		$concurrentStore
		
		//accountTypeStore.load();
		
		
	    //accountStore.setDefaultSort('id', 'asc');
	
	    // pluggable renders
	    
	    var concurrentColumnModel = new Ext.grid.ColumnModel([{
	           id: 'id', 
	           header: \"Name\",
	           dataIndex: 'name',
	           width: 420
	        },{
	           header: \"IP Address\",
	           dataIndex: 'ipaddress',
	           width: 70,
	           align: 'right'
	        },{
	           header: \"First Access\",
	           dataIndex: 'firstaccess',
	           width: 150
		    },{
		       header: \"Last Access\",
		       dataIndex: 'lastaccess',
		       width: 150
		    }
		]);
	
	    concurrentColumnModel.defaultSortable = true;
        Ext.ux.grid.filter.StringFilter.prototype.icon = 'images/find.png';   
        var concurrentFilters = new Ext.ux.grid.GridFilters({filters:[
            {type: 'string',  dataIndex: 'name'}
        ]});
	
	    var gridConcurrent = new Ext.grid.GridPanel({
	        //el:'topic-grid',
	        //
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
	        //autoWidth: true,
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: concurrentStore,
	        
	        tbar: new Ext.Toolbar({
				id: 'adminConcurrentToolbar',
				height: 25				
			}),
			cm: concurrentColumnModel,
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
	        loadMask: true,
	        renderTo: 'concurrentUIDiv',
	        viewConfig: {
	            forceFit:true,
	            enableRowBody:true,
	            showPreview: false
	        },
            plugins: concurrentFilters, 
	        bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: concurrentStore,
                plugins: concurrentFilters, 
	            displayInfo: true,
	            displayMsg: 'Displaying concurrent {0} - {1} of {2}',
	            emptyMsg: \"No concurrent to display\"
	            
	        })
	    });
        
    	var tbConcurrent = Ext.getCmp('adminConcurrentToolbar');
		
	 	tbConcurrent.add({
        	id: 'btnDeleteConcurrent',
            text:'Delete Concurrent',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Refresh View',
            iconCls: 'refreshIcon',
            handler: function(){
            	//accountStore.load({params:{start:0, limit:25}});
            	concurrentStore.reload();
			}
        });

	    // render it
	    gridConcurrent.render();
	
	    // trigger the data store load
	    concurrentStore.load({params:{start:0, limit:25}});
	    gridConcurrent.on({
			'rowclick' : function() {
				Ext.getCmp('btnDeleteConcurrent').enable();
			},
			scope: this
		});
		
		function deleteSelectedConcurrent(btn) {
			if(btn == 'yes') {
				Ext.Ajax.request({
	    				url: '/{$config ['appName']}/concurrent-manager/delete-concurrent',
	    				method: 'POST',
	    				success: deleteConcurrentSuccess,
	    				failure: deleteConcurrentFailed,
	    				params: { id: gridConcurrent.getSelectionModel().getSelected().get('id') }
	    		});
			}
		}
		
		function deleteConcurrentSuccess() {
			Ext.MessageBox.hide();
			
			//accountStore.load({params:{start:0, limit:25}});
            concurrentFilters.clearFilters();
			concurrentStore.reload();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Account Deleted!',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.INFO
	    	});
		}
		
		function deleteConcurrentFailed() {
			Ext.MessageBox.hide();
			
			concurrentStore.load();
			
			Ext.MessageBox.show({
	    		title: 'Account Manager',
	    		msg: 'Failed to delete Account!',
	    		buttons: Ext.MessageBox.OK,
	    		//animEl: Ext.getCmp('btnAccountAdd').getEl(),
	    		icon: Ext.MessageBox.ERROR
	    	});
		}
	
	   
	    
    
		</script>";
	}
	
	/**
	 * action /delete-concurrent/ ลบ Concurrent
	 *
	 */
	function deleteConcurrentAction() {
		$id = $_POST['id'];
		//include_once('Concurrent.Entity.php');
		$concurrent = new ConcurrentEntity();
		$concurrent->Load("f_acc_id = '{$id}'");
		$concurrent->Delete();
	}

}
