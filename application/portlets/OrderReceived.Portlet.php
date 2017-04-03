<?php
/**
 * Portlet : งานที่ได้รับมอบหมาย
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class OrderReceivedPortlet {
	
	public function __construct() {
		//include_once 'DFStore.php';
	}
	
	public function getUI() {
		global $config;
		global $conn;
		global $store;
		global $lang;
		global $sessionMgr;
		global $audit;
		
		checkSessionPortlet();
		
		$dfStore = new DFStore ( );
		
		$sqlGetFilter = "select * from tbl_doc_type order by f_doctype_id";
		$rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetFilter );
		$DIVName = "OrderReceivedUIDIV";
		$programID = "OR_0";
		$gridName = 'gridOrderReceivedItem';
		$filterMenuName = 'ORFilteredMenu';
		
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
		
		foreach ( $rs as $row ) {
			checkKeyCase ( $row );
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
                disabled: true,
                handler: function(e) {
                    popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                    //popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                }
            },{
                id: 'btnSaveToTrack_{$programID}',
                text:'{$lang['df']['saveToTrack']}',
				hidden: true,
                iconCls: 'bookmarkIcon',
                disabled: true,
                handler: function(e) {
                    Ext.MessageBox.confirm('Confirm', 'บันทึก [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] เป็นรายการติดตาม?', addToTrack_{$programID});
                    //Ext.MessageBox.confirm('Confirm', 'บันทึก [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] เป็นรายการติดตาม?', addToTrack_{$programID});
                }
            },{
                id: 'btnPause_{$programID}',
                text:'{$lang['df']['pause']}',
                iconCls: 'forbidIcon',
                disabled: true,
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
                disabled: true,
                handler: function(e) {
                    popupDocLog({$gridName}.getSelectionModel().getSelected().get('recvID'));
                    //popupDocLog({$gridName}.getSelectionModel().getSelected().get('recvID'));
                }
            }";             
        $auditMenu .= "$auditSubMenu]}
        );";
		
		$orgID = $sessionMgr->getCurrentOrgID ();
		$regBookID = 0;
		$storeName = "ORStore_{$regBookID}";
		$RIStore = $dfStore->getOrderReceivedStore ( $regBookID, $storeName );
		$accountStore = $store->getDataStore ( 'account' );
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
		
		$callModule = 'OrderReceived';
		$dblClickFunction = "Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
		viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'{$callModule}',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('orderID'));";
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		//Received Internal Data Store
		{$RIStore}                       
        
        {$auditMenu}
		
		{$storeName}.setDefaultSort('sendNo', 'desc');
		
	    var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"{$lang['df']['sendType']}\",
	           dataIndex: 'recvType',
	           width: 100
	           ,renderer: renderRecvType
	        },{
	           header: \"{$lang['df']['recvNo']}\",
	           dataIndex: 'recvNo',
	           width: 50
	        },{
               header: \"{$lang['df']['classified']}\",
               dataIndex: 'secret',
               width: 100,
               renderer: renderSecret                        
            },{
               header: \"{$lang['df']['speed']}\",
               dataIndex: 'speed',
               width: 100,
               renderer: renderSpeed                        
            },{
	           header: \"{$lang['df']['docNo']}\",
	           dataIndex: 'docNo',
	           width: 100,
               renderer: renderDFBookno                        
	        },{
	           header: \"{$lang['df']['title']}\",
	           dataIndex: 'title',
	           width: 130,
	           align: 'left',
               renderer: renderDFTitle
	        },{
	           header: \"{$lang['df']['date']}\",
	           dataIndex: 'docDate',
	           width: 95
		    },{
		       header: \"{$lang['df']['from']}\",
		       dataIndex: 'from',
		       width: 150
		    },{
		       header: \"{$lang['df']['to']}\",
		       dataIndex: 'to',
		       width: 150
		    },{
               header: \"{$lang['common']['orderAssigner']}\",
               dataIndex: 'assigner',
               width: 150
            },{
		       header: \"{$lang['df']['action']}\",
		       dataIndex: 'command',
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
	        view: new Ext.grid.GroupingView({forceFit:true}),
	        /*
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
	        },*/
	        bbar: new Ext.PagingToolbar({
	        	id: 'pagingToolbar_{$programID}',
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
	 		id: 'btnSearchDocument_{$programID}',
            text:'{$lang['df']['search']}',
            iconCls: 'searchIcon',
            disabled: false,
            hidden: false,
            handler: function() {
            	filterReceiveRecordWindow.show();
            	Ext.getCmp('filterRecvType').setValue('OrderReceived');
			}
        },{
	 		id: 'btnPreviewDocument_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
            	//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
            	$dblClickFunction
            	//accountAddForm.getForm().reset();
				//accountAddWindow.show();
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
            	Ext.getCmp('btnPreviewDocument_{$programID}').disable();
				Ext.getCmp('btnTrack_{$programID}').disable();
				Ext.getCmp('btnSaveToTrack_{$programID}').disable();
				//Ext.getCmp('btnForward_{$programID}').disable();
                Ext.getCmp('btnAuditDoc_{$programID}').disable();   
				
				// Todo: รอเปิด Function ใช้งาน
				//Ext.getCmp('btnSendEmail_{$programID}').disable();				
				//Ext.getCmp('btnReturn_{$programID}').disable();
				Ext.getCmp('btnPause_{$programID}').disable();
				Ext.getCmp('btnViewLog_{$programID}').disable();
            	{$storeName}.load({params:{start:0, limit:25}});
            	//accountStore.reload();
			}
        });

	    // render it
		{$gridName}.render();
		
		{$storeName}.load({params:{start:0, limit:25}});
	
	    // trigger the data store load
	    //accountStore.load({params:{start:0, limit:25}});
	    
	    
		{$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    
	{$gridName}.on('rowdblclick',function() {
			Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('sendID'));
			//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
	    	//alert('dbl click');
	    	$dblClickFunction
		}
		,{$gridName});
		
	{$gridName}.on({
			'rowclick' : function() {
				Ext.getCmp('btnPreviewDocument_{$programID}').enable();
				Ext.getCmp('btnTrack_{$programID}').enable();
				Ext.getCmp('btnSaveToTrack_{$programID}').enable();
				//Ext.getCmp('btnForward_{$programID}').enable();
                Ext.getCmp('btnAuditDoc_{$programID}').enable();     
                
                
				
				// Todo: รอเปิด Function ใช้งาน
				//Ext.getCmp('btnSendEmail_{$programID}').enable();
				//Ext.getCmp('btnReturn_{$programID}').enable();
				Ext.getCmp('btnPause_{$programID}').enable();
				Ext.getCmp('btnViewLog_{$programID}').enable();
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
                        	id: {$gridName}.getSelectionModel().getSelected().get('recvID') 
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
                        	id: {$gridName}.getSelectionModel().getSelected().get('recvID') 
						}
                });                
            }
		}
	        
		</script>";
	}
}
