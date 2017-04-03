<?php
/**
 * Portlet : หนังสือจองเลข
 * @
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class ReserveBookPortlet {

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
		$DIVName = "ReserveBookUIDIV";
		$programID = "PRB_0";
		$gridName = 'gridReserveBookItem';
		$filterMenuName = 'PRBFilteredMenu';
		
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
        
        $auditMenuName = "auditBookMenu_{$programID}";
        $auditMenu = "
        var {$auditMenuName} = new Ext.menu.Menu({
            id: 'auditBookMainMenu_{$programID}'
            ,items: [
            ";
        
        $auditSubMenu = "{
                id: 'btnTrack_{$programID}',
                text:'{$lang['df']['track']}',
                iconCls: 'infoIcon',
                disabled: false,
                handler: function(e) {
                    popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('sendID'));
                }
            },{
                id: 'btnSaveToTrack_{$programID}',
                text:'{$lang['df']['saveToTrack']}',
				hidden: true,
                iconCls: 'bookmarkIcon',
                disabled: false,
                handler: function(e) {
                    Ext.MessageBox.confirm('Confirm', 'บันทึก [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] เป็นรายการติดตาม?', addToTrack_{$programID});
                }
            },{
                id: 'btnPause_{$programID}',
                text:'{$lang['df']['pause']}',
                iconCls: 'forbidIcon',
                disabled: false,
                handler: function(e) {
                    if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
                        var holdCaption = 'ให้ดำเนินการเอกสาร';
                    } else {
                        var holdCaption = 'หยุดตรวจสอบเอกสาร';
                    }
                    Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJob_{$programID});
                }
            },{
                id: 'btnViewLog_{$programID}',
                text:'{$lang['df']['viewlog']}',
                iconCls: 'historyIcon',
                disabled: false,
                handler: function(e) {
                    popupDocLog({$gridName}.getSelectionModel().getSelected().get('sendID'));
                }
            }";             
        $auditMenu .= "$auditSubMenu]}
        );";
        
		
	    $orgID = $sessionMgr->getCurrentOrgID();
	    $regBookID = 0;
	    $storeName = "PRBStore_{$regBookID}";
	    $RBStore = $dfStore->getReserveBookStore($regBookID,$storeName);
		
		$callModule = 'ReserveBook';		
		$dblClickFunction = "Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('sendID'));
		viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'{$callModule}',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('sendID'));";
		
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		//Received Internal Data Store
		{$RBStore}
        
        {$auditMenu}
		
		{$storeName}.setDefaultSort('sendNo', 'desc');

	    var cm_{$programID} = new Ext.grid.ColumnModel([
			/*{
	           header: \"{$lang['df']['recvType']}\",
	           dataIndex: 'sendType',
	           width: 80
	           ,renderer: renderRecvType
	        },{
	           header: \"{$lang['df']['recvNo']}\",
	           dataIndex: 'sendNo',
	           width: 60
	        },*/{
		       header: \"{$lang['df']['reserved_id']}\",
		       dataIndex: 'reserved_id',
		       width: 60                       
		    },{
               header: \"{$lang['df']['classified']}\",
               dataIndex: 'secret',
               width: 70,
               renderer: renderSecret                        
            },{
               header: \"{$lang['df']['speed']}\",
               dataIndex: 'speed',
               width: 70,
               renderer: renderSpeed                        
            },{
	           header: \"{$lang['df']['docNo']}\",
	           dataIndex: 'docNo',
	           width: 150,
               renderer: renderDFBookno
	        },{
	           header: \"{$lang['df']['title']}\",
	           dataIndex: 'title',
	           width: 180,
	           align: 'left',
               renderer: renderDFTitle
	        },{
	           header: \"{$lang['df']['date']}\",
	           dataIndex: 'docDate',
	           width: 90                           
		    },{
		       header: \"{$lang['df']['from']}\",
		       dataIndex: 'from',
		       width: 150                          
		    },{
		       header: \"{$lang['df']['to']}\",
		       dataIndex: 'to',
		       width: 150                          
		    },{
		       header: \"{$lang['df']['reserver']}\",
		       dataIndex: 'reserver',
		       width: 150                       
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
	        //view: new Ext.grid.GroupingView({forceFit:true}),
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
    	
		tb_{$programID}.add(/*{
	 		id: 'btnSearchDocument_{$programID}',
            text:'{$lang['df']['search']}',
            iconCls: 'searchIcon',
            disabled: false,
            hidden: false,
            handler: function() {
            	filterReceiveRecordWindow.show();
            	Ext.getCmp('filterRecvType').setValue('Completed');
			}
		},*/{
	 		id: 'btnView_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
            	{$dblClickFunction}
			}
        },{
            id: 'btnAuditDoc_{$programID}',
            text:'{$lang['df']['auditBook']}',
            iconCls: 'auditIcon',
            menu: {$auditMenuName},
            disabled: true
        },{
            text:'{$lang['df']['fetch']}',
            iconCls: 'refreshIcon',
            handler: function(){
                {$gridName}.getSelectionModel().clearSelections();
                Ext.getCmp('btnView_{$programID}').disable();
                Ext.getCmp('btnAuditDoc_{$programID}').disable();      
            	{$storeName}.reload();                          
			}
        });

	    // render it
		{$gridName}.render();
		
		{$storeName}.load({params:{start:0, limit:25}});
	
	    // trigger the data store load
	    //accountStore.load({params:{start:0, limit:25}});
	    
	    
		{$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    
	    {$gridName}.on('rowdblclick',function() {
	    	$dblClickFunction
		}
		,{$gridName});
		
	    {$gridName}.on({
			'rowclick' : function() {                           				
                Ext.getCmp('btnView_{$programID}').enable();
                Ext.getCmp('btnAuditDoc_{$programID}').enable();                
			},
			scope: this
		});
		
	    function toggle{$programID}Details(btn, pressed){
	        var view_{$programID} = {$gridName}.getView();
			view_{$programID}.showPreview = pressed;
			view_{$programID}.refresh();
	    }
	    
	    
        function addToTrack_{$programID}(btn) {
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
                            id: {$gridName}.getSelectionModel().getSelected().get('sendID') 
                        }
                });                
            }    
        }
        
        function holdJob_{$programID}(btn) {
            if(btn == 'yes') {
                Ext.MessageBox.show({
                        msg: 'กำลังบันทึกรายการติดตาม...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        con:'ext-mb-download'
                });
                var urlHold = '';
                if({$gridName}.getSelectionModel().getSelected().get('hold') ==1) {
                    urlHold = '/{$config ['appName']}/df-action/unhold';
                } else {
                    urlHold = '/{$config ['appName']}/df-action/hold';
                }
                Ext.Ajax.request({
                        url: urlHold,
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
                            
                            {$storeName}.reload();
                        },
                        params: {
                            id: {$gridName}.getSelectionModel().getSelected().get('sendID') 
                        }
                });                
            }
        }
    
		</script>";
	}
}
