<?php
/**
 * Portlet : หนังสือรับภายนอกทะเบียนกลาง
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */


class GlobalReceiveExternalPortlet {
	
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
		$DIVName = "GlobalReceivedExternalUIDIV";
		$programID = "GRE_0";
		$gridName = 'gridGlobalReceivedExternalItem';
		$filterMenuName = 'GREFilteredMenu';
		
		$filterMenu = "
		var {$filterMenuName} = new Ext.menu.Menu({
        	id: 'mainMenu_{$programID}'
        	,items: [
        	";
		
		$filterItems = "{
				id: '-1'
				,text: '{$lang['common']['undefined']}'
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
	            	popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('recvID'));
				}
	        },{
	        	id: 'btnSaveToTrack_{$programID}',
	            text:'{$lang['df']['saveToTrack']}',
	            iconCls: 'bookmarkIcon',
	            disabled: false,
	            handler: function(e) {
	            	Ext.MessageBox.confirm('Confirm', 'บันทึก [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] เป็นรายการติดตาม?', addToTrackSG);
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
	            	Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJobSG);
				}
	        },{
	        	id: 'btnViewLog_{$programID}',
	            text:'{$lang['df']['viewlog']}',
	            iconCls: 'historyIcon',
	            disabled: false,
	            handler: function(e) {
	            	popupDocLog({$gridName}.getSelectionModel().getSelected().get('recvID'));
				}
	        },{
                id: 'btnAbort_{$programID}',
                text:'{$lang['df']['abort']}',
                iconCls: 'abortIcon',
                disabled: false,
                handler: function(e) {
                    Ext.MessageBox.confirm('Abort Confirm', 'ยกเลิก Transaction ของหนังสือ  [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ทั้งหมด?',abortJob_{$programID});
                }
            }";	         
		$auditMenu .= "$auditSubMenu]}
	    );";
		
	    $orgID = $sessionMgr->getCurrentOrgID();
	    $regBookID = 0;
	    $storeName = "GREStore_{$regBookID}";
	    $REStore = $dfStore->getRGStore($regBookID,$storeName);
		$accountStore = $store->getDataStore('account');
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
		
		$callModule = 'ReceivedExternalGlobal';
		if($sessionMgr->isGoverner()) {
			$dblClickFunction = "Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
			//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RG',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'));
			viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'{$callModule}',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'));";
			
		} else {
			$dblClickFunction = "if({$gridName}.getSelectionModel().getSelected().get('governerApprove')==1) {
				Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
				//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RG',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'));
				viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'{$callModule}',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'));
			} else {
				alert('เอกสารยังไม่อนุมัติ');
			}";
		}
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		//Received Internal Data Store
		{$REStore}
		
		{$auditMenu}
		
		{$storeName}.setDefaultSort('recvNo', 'desc');
		
	    var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"{$lang['df']['recvNo']}\",
	           dataIndex: 'recvNo',
	           width: 50
	        },{
	           header: \"{$lang['df']['classified']}\",
	           dataIndex: 'secret',
	           width: 50,
	           hidden: true,
	           renderer: renderSecret
	        },{
	           header: \"{$lang['df']['speed']}\",
	           dataIndex: 'speed',
	           width: 50,
	           hidden: true,
	           renderer: renderSpeed
	        },{
	           header: \"{$lang['df']['docNo']}\",
	           dataIndex: 'docNo',
	           width: 100,
	           renderer: renderDFBookno
	        },{
	           header: \"{$lang['df']['date']}\",
	           dataIndex: 'docDate',
	           width: 95
		    },{
		       header: \"{$lang['df']['from']}\",
		       dataIndex: 'from',
		       width: 100
		    },{
		       header: \"{$lang['df']['to']}\",
		       dataIndex: 'to',
		       width: 100
		    },{
	           header: \"{$lang['df']['title']}\",
	           dataIndex: 'titletitle',
	           width: 130,
	           align: 'left',
	           renderer: renderDFTitle
	        },{
		       header: \"{$lang['df']['action']}\",
		       dataIndex: 'command',
		       width: 150
		    },{
		       header: \"{$lang['df']['receiveUser']}\",
		       dataIndex: 'receivedUser',
		       width: 95
		    },{
		       header: \"{$lang['df']['receiveStamp']}\",
		       dataIndex: 'receivedStamp',
		       width: 95
		    }
		]);
	
	    cm_{$programID}.defaultSortable = true;
        
        Ext.ux.grid.filter.StringFilter.prototype.icon = 'images/find.png';   
        
        var URIGRE = new Ext.ux.grid.GridFilters({filters:[
            {type: 'string',  dataIndex: 'from'} 
        ]});
	
	    var {$gridName} = new Ext.grid.GridPanel({
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
            plugins: URIGRE,
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
	        },
            */
	        bbar: new Ext.PagingToolbar({
	        	id: 'pagingToolbar_{$programID}',
	            pageSize: 25,
	            store: {$storeName},
	            displayInfo: true,
                plugins: URIGRE,     
	            displayMsg: '{$lang['df']['receivedExternal']} {0} - {1} {$lang['common']['of']} {2}',
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
            handler: function() {
            	filterReceiveRecordWindow.show();
            	Ext.getCmp('filterRecvType').setValue('RecvExtGlobal');
			}
        },{
	 		id: 'btnPreviewDocument_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
            	{$dblClickFunction}
            	//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
            	//accountAddForm.getForm().reset();
				//accountAddWindow.show();
			}
        },{
			id: 'btnAuditDoc_{$programID}',
            text:'{$lang['df']['auditBook']}',
            iconCls: 'auditIcon',
            menu: {$auditMenuName},
            disabled: true
        },/*{
        	id: 'btnTrack_{$programID}',
            text:'{$lang['df']['track']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('recvID'));
            	//setPasswordForm.getForm().reset();
				//setPasswordWindow.show();
			}
        },{
        	id: 'btnSaveToTrack_{$programID}',
            text:'{$lang['df']['saveToTrack']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('Confirm', 'บันทึก [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] เป็นรายการติดตาม?', addToTrackSG);
			}
        },*/{
        	id: 'btnSaveCommand_{$programID}',
            text:'{$lang['df']['saveCommand']}',
            iconCls: 'commandIcon',
            disabled: true,
            handler: function(e) {
            	Ext.getCmp('commandTransType').setValue('RG');
            	Ext.getCmp('commandTransCode').setValue({$gridName}.getSelectionModel().getSelected().get('recvID'));
            	addCommandWindow.show();
			}
        },{
        	id: 'btnForward_{$programID}',
            text:'{$lang['df']['forward']}',
            iconCls: 'forwardIcon',
            disabled: true,
            handler: function(e) {
            	Cookies.set('fwD',{$gridName}.getSelectionModel().getSelected().get('recvID'));
            	Cookies.set('rc','forwardTransTo');
                Cookies.set('rcH','forwardTransToHidden');
                Cookies.set('rp','{$programID}');
            	forwardDFWindow.show();
			}
        }
        // Todo: รอเปิด Function ใช้งาน
        /*,{
        	id: 'btnReturn_{$programID}',
            text:'{$lang['df']['return']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	id: 'btnSendEmail_{$programID}',
            text:'{$lang['df']['sendEmail']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        }*/ /*,{
        	id: 'btnPause_{$programID}',
            text:'{$lang['df']['pause']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
            		var holdCaption = 'ให้ดำเนินการเอกสาร';
				} else {
					var holdCaption = 'หยุดตรวจสอบเอกสาร';
				}
            	Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJobSG);
			}
        },{
        	id: 'btnViewLog_{$programID}',
            text:'{$lang['df']['viewlog']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	popupDocLog({$gridName}.getSelectionModel().getSelected().get('recvID'));
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        }*/,{
        	id: 'btnExportCommand_{$programID}',
            text:'{$lang['df']['excelExport']}',
            iconCls: 'reportIcon',
            disabled: false,
            handler: function(e) {
            	var pageData_{$programID} = Ext.getCmp('pagingToolbar_{$programID}').getPageData();
            	popupExcelReport('RG',pageData_{$programID}.activePage);
			}
        },{
        	id: 'btnExportCommand2_{$programID}',
            text:'รายงานเซ็นรับทะเบียนกลาง',
            iconCls: 'reportIcon',
            disabled: false,
            handler: function(e) {
            	//var pageData_{$programID} = Ext.getCmp('pagingToolbar_{$programID}').getPageData();
            	//popupExcelReport('RG',pageData_{$programID}.activePage);
            	filterReceiveRecordWindow2.show();
			}
        },{
        	id: 'btnCloseJobCommand_{$programID}',
            text:'{$lang['df']['closeJob']}',
            iconCls: 'bmenu',
            hidden: true,
            disabled: true,
            handler: function(e) {
			}
        },{
            text:'{$lang['df']['fetch']}',
            iconCls: 'refreshIcon',
            handler: function(){
            	{$gridName}.getSelectionModel().clearSelections();
            	Ext.getCmp('btnPreviewDocument_{$programID}').disable();
            	Ext.getCmp('btnAuditDoc_{$programID}').disable();
				Ext.getCmp('btnForward_{$programID}').disable();
				Ext.getCmp('btnSaveCommand_{$programID}').disable();
				// Todo: รอเปิด Function ใช้งาน
				//Ext.getCmp('btnSendEmail_{$programID}').disable();				
				//Ext.getCmp('btnReturn_{$programID}').disable();
            	{$storeName}.load({params:{start:0, limit:25}});
			}
        });
        
		// Custom Grid Renderer
        {$gridName}.getView().getRowClass= function(record, rowIndex, p, storex) {
			if(this.showPreview){
                p.body = '<p>No detail defined</p>';
                if(record.data.abort == 1) {
                    return 'aborted-row';
                }
                if (record.data.governerApprove == 0) {
                        return 'unapproved-row';
                } else {
                    return 'x-grid3-row-expanded';
                }
                
                
            }
            if(record.data.abort == 1) {
                return 'aborted-row';
            }
            if (record.data.governerApprove == 0) {
                return 'unapproved-row';
            } else {
                return 'x-grid3-row-collapsed';
            }
		}

	    // render it
		{$gridName}.render();
	
	    {$storeName}.load({params:{start:0, limit:25}});
	    
		{$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    
		{$gridName}.on('rowdblclick',function() {
			{$dblClickFunction}
			//Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
			//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
	    	//alert('dbl click');
		}
		,{$gridName});
		
		{$gridName}.on({
			'rowclick' : function() {
				
				Ext.getCmp('btnPreviewDocument_{$programID}').enable();
				Ext.getCmp('btnAuditDoc_{$programID}').enable();
				Ext.getCmp('btnTrack_{$programID}').enable();
				Ext.getCmp('btnSaveToTrack_{$programID}').enable();
				Ext.getCmp('btnForward_{$programID}').enable();
				Ext.getCmp('btnSaveCommand_{$programID}').enable();
				
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
	    
	    function addToTrackSG(btn) {
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
		
		function holdJobSG(btn) {
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
							{$gridName}.getSelectionModel().clearSelections();
			            	Ext.getCmp('btnPreviewDocument_{$programID}').disable();
			            	Ext.getCmp('btnAuditDoc_{$programID}').disable();
							Ext.getCmp('btnForward_{$programID}').disable();
							Ext.getCmp('btnSaveCommand_{$programID}').disable();
							{$storeName}.reload();
                        },
                        params: {
                        	id: {$gridName}.getSelectionModel().getSelected().get('recvID') 
						}
                });                
            }
		}
        
        function abortJob_{$programID}(btn) {
            if(btn == 'yes') {
                Ext.MessageBox.show({
                        msg: 'กำลังทำการยกเลิก...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        con:'ext-mb-download'
                });
                var urlAbort = '/{$config ['appName']}/df-action/abort';
                
                Ext.Ajax.request({
                        url: urlAbort,
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
                                title: 'การยกเลิกรายการ',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.INFO
                            });
                            
                            {$gridName}.getSelectionModel().clearSelections();
                            Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                            Ext.getCmp('btnAuditDoc_{$programID}').disable();    
                            Ext.getCmp('btnForward_{$programID}').disable();     
                                
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