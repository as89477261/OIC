<?php
/**
 * Portlet : ˹ѧ������¹����
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class CircBookInternalPortlet {
	
	public function __construct() {
		include_once 'DFStore.php';
	}
	
	public function getUI() {
		global $config;
		global $conn;
		global $store;
		global $lang;
		global $sessionMgr;
		
		$dfStore = new DFStore();
		
		checkSessionPortlet();
		
		$sqlGetFilter = "select * from tbl_doc_type order by f_doctype_id";
		$rs = $conn->CacheExecute($config ['defaultCacheSecs'],$sqlGetFilter);
		$DIVName = "CircBookInternalUIDIV";
		$programID = "CBI_0";
		$gridName = 'gridCircBookInternalItem';
		$filterMenuName = 'CBIFilteredMenu';
		
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
	            disabled: true,
	            handler: function(e) {
	            	popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('recvID'));
				}
	        },{
	        	id: 'btnSaveToTrack_{$programID}',
	            text:'{$lang['df']['saveToTrack']}',
				hidden: true,		
	            iconCls: 'bookmarkIcon',
	            disabled: true,
	            handler: function(e) {
                    Ext.MessageBox.show({
                        msg: 'Checking Session',
                        progressText: 'Processing...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                    });
                    Ext.Ajax.request({
                        url: '/{$config ['appName']}/session.php',
                        method: 'POST',
                        success: function(o){
                            Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            if(r.redirectLogin == 1) {
                                sessionExpired(); 
                            } else   {
                                Ext.MessageBox.confirm('Confirm', '�ѹ�֡ [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ����¡�õԴ���?', addToTrack);
                            }
                        }
                    });
	            	
				}
	        },{
	        	id: 'btnPause_{$programID}',
	            text:'{$lang['df']['pause']}',
	            iconCls: 'forbidIcon',
	            disabled: true,
                hidden: true,
	            handler: function(e) {
                    Ext.MessageBox.show({
                        msg: 'Checking Session',
                        progressText: 'Processing...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                    });
                    Ext.Ajax.request({
                        url: '/{$config ['appName']}/session.php',
                        method: 'POST',
                        success: function(o){
                            Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            if(r.redirectLogin == 1) {
                                sessionExpired(); 
                            } else   {
                                if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
                                    var holdCaption = '�����Թ����͡���';
                                } else {
                                    var holdCaption = '��ش��Ǩ�ͺ�͡���';
                                }
                                Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJob); 
                            }
                        }
                    });
		            
				}
	        },{
	        	id: 'btnViewLog_{$programID}',
	            text:'{$lang['df']['viewlog']}',
	            iconCls: 'historyIcon',
	            disabled: true,
	            handler: function(e) {
	            	popupDocLog({$gridName}.getSelectionModel().getSelected().get('recvID'));
				}
	        }";	         
		$auditMenu .= "$auditSubMenu]}
	    );";
		
	    $orgID = $sessionMgr->getCurrentOrgID();
	    $regBookID = 0;
	    $storeName = "CBIStore_{$regBookID}";
	    $RIStore = $dfStore->getCirBookInternalStore($regBookID,$storeName);
		$accountStore = $store->getDataStore('account');
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		//Received Internal Data Store
		{$RIStore}
		
		{$auditMenu}
		
		{$storeName}.setDefaultSort('recvNo', 'desc');
		
	    var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"{$lang['df']['recvNo']}\",
	           dataIndex: 'recvNo',
	           width: 50
	        },{
	    	   id: 'id', 
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
	           renderer: renderDFCircTitle
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
		       header: \"{$lang['df']['action']}\",
		       dataIndex: 'command',
		       width: 150
		    }
		    
		]);
	
	    cm_{$programID}.defaultSortable = true;
	
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
            	Ext.getCmp('filterRecvType').setValue('RecvCirc');
			}
        },{
	 		id: 'btnPreviewDocument_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/session.php',
                    method: 'POST',
                    success: function(o){
                        Ext.MessageBox.hide();
                        var r = Ext.decode(o.responseText);
                        if(r.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                            viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'CI',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'));
                        }
                    }
                });
            	
			}
        },{
			id: 'btnAuditDoc_{$programID}',
            text:'{$lang['df']['auditBook']}',
            iconCls: 'auditIcon',
            menu: {$auditMenuName},
            disabled: true
        }/*,{
        	id: 'btnTrack_{$programID}',
            text:'{$lang['df']['track']}',
            iconCls: 'infoIcon',
            disabled: true,
            handler: function(e) {
            	popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('recvID'));
			}
        },{
        	id: 'btnSaveToTrack_{$programID}',
            text:'{$lang['df']['saveToTrack']}',
            iconCls: 'bookmarkIcon',
            disabled: true,
            handler: function(e) {
            	Ext.MessageBox.confirm('Confirm', '�ѹ�֡ [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ����¡�õԴ���?', addToTrack);
			}
        }*/,{
        	id: 'btnForward_{$programID}',
            text:'{$lang['df']['forward']}',
            iconCls: 'forwardIcon',
            disabled: true,
            handler: function(e) {
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/session.php',
                    method: 'POST',
                    success: function(o){
                        Ext.MessageBox.hide();
                        var r = Ext.decode(o.responseText);
                        if(r.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            Cookies.set('fwD',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                            Cookies.set('rc','forwardTransTo');
                            Cookies.set('rcH','forwardTransToHidden');
                            Cookies.set('rp','{$programID}');
                            forwardDFWindow.show();
                        }
                    }
                });
            	
			}
        }
        // Todo: ���Դ Function ��ҹ
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
            iconCls: 'pauseIcon',
            disabled: true,
            handler: function(e) {
            	if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
            		var holdCaption = '�����Թ����͡���';
				} else {
					var holdCaption = '��ش��Ǩ�ͺ�͡���';
				}
            	Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJob);
			}
        },{
        	id: 'btnViewLog_{$programID}',
            text:'{$lang['df']['viewlog']}',
            iconCls: 'historyIcon',
            disabled: true,
            handler: function(e) {
            	popupDocLog({$gridName}.getSelectionModel().getSelected().get('recvID'));
			}
        }*/ /*,{
        	id: 'btnSaveCommand_{$programID}',
            text:'{$lang['df']['saveCommand']}',
            iconCls: 'commandIcon',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	id: 'btnSaveCommand_{$programID}',
            text:'{$lang['df']['excelExport']}',
            iconCls: 'excelIcon',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	id: 'btnSaveCommand_{$programID}',
            text:'{$lang['df']['closeJob']}',
            iconCls: 'closeJobIcon',
            disabled: true,
            handler: function(e) {
			}
        }*/,{
            text:'{$lang['df']['fetch']}',
            iconCls: 'refreshIcon',
            handler: function(){
                Ext.MessageBox.show({
                    msg: 'Checking Session',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/session.php',
                    method: 'POST',
                    success: function(o){
                        Ext.MessageBox.hide();
                        var r = Ext.decode(o.responseText);
                        if(r.redirectLogin == 1) {
                            sessionExpired(); 
                        } else   {
                            {$gridName}.getSelectionModel().clearSelections();
                            //Ext.getCmp('btnSearchDocument_{$programID}').disable();
                            Ext.getCmp('btnAuditDoc_{$programID}').disable();
                            Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                            Ext.getCmp('btnTrack_{$programID}').disable();
                            Ext.getCmp('btnSaveToTrack_{$programID}').disable();
                            Ext.getCmp('btnForward_{$programID}').disable();
                            
                            // Todo: ���Դ Function ��ҹ
                            //Ext.getCmp('btnSendEmail_{$programID}').disable();                
                            //Ext.getCmp('btnReturn_{$programID}').disable();
                            Ext.getCmp('btnPause_{$programID}').disable();
                            Ext.getCmp('btnViewLog_{$programID}').disable();
                            {$storeName}.load({params:{start:0, limit:25}});
                            //accountStore.reload(); 
                        }
                    }
                });
            	
			}
        });

	    // render it
		{$gridName}.render();
		
		{$storeName}.load({params:{start:0, limit:25}});
	
	    // trigger the data store load
	    //accountStore.load({params:{start:0, limit:25}});
	    
	    
		{$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    
		{$gridName}.on('rowdblclick',function() {
            Ext.MessageBox.show({
                msg: 'Checking Session',
                progressText: 'Processing...',
                width:300,
                wait:true,
                waitConfig: {interval:200},
                icon:'ext-mb-download'
            });
            Ext.Ajax.request({
                url: '/{$config ['appName']}/session.php',
                method: 'POST',
                success: function(o){
                    Ext.MessageBox.hide();
                    var r = Ext.decode(o.responseText);
                    if(r.redirectLogin == 1) {
                        sessionExpired(); 
                    } else   {
                        //Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                        //viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'CI',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'));
                        
                        Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
                        viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'CI',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('recvID'));
        
                    }
                }
            });
			
		}
		,{$gridName});
		
	{$gridName}.on({
			'rowclick' : function() {
				
				Ext.getCmp('btnAuditDoc_{$programID}').enable();
				Ext.getCmp('btnPreviewDocument_{$programID}').enable();
				Ext.getCmp('btnTrack_{$programID}').enable();
				Ext.getCmp('btnSaveToTrack_{$programID}').enable();
				Ext.getCmp('btnForward_{$programID}').enable();
				Ext.getCmp('btnPause_{$programID}').enable();
				Ext.getCmp('btnViewLog_{$programID}').enable();
				
				// Todo: ���Դ Function ��ҹ
				//Ext.getCmp('btnSendEmail_{$programID}').enable();
				//Ext.getCmp('btnReturn_{$programID}').enable();
				
			},
			scope: this
		});
		
	    function toggle{$programID}Details(btn, pressed){
	        var view_{$programID} = {$gridName}.getView();
			view_{$programID}.showPreview = pressed;
			view_{$programID}.refresh();
	    }
	    
	    function addToTrack(btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
                        msg: '���ѧ�ѹ�֡��¡�õԴ���...',
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
                            	responseMsg = '�ѹ�֡���º��������';
							} else {
								responseMsg = '�ѹ�֡�Դ��Ҵ';
							}
							
                            Ext.MessageBox.show({
                                title: '��úѹ�֡��¡�õԴ���',
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
		
		function holdJob(btn) {
			if(btn == 'yes') {
				Ext.MessageBox.show({
                        msg: '���ѧ�ѹ�֡��¡�õԴ���...',
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
                            	responseMsg = '�ѹ�֡���º��������';
							} else {
								responseMsg = '�ѹ�֡�Դ��Ҵ';
							}
							
                            Ext.MessageBox.show({
                                title: '��úѹ�֡��¡�õԴ���',
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
