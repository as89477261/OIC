<?php
/**
 * Portlet : ˹ѧ��͵Դ���
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class TrackItemPortlet {
	
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
		$DIVName = "TrackingItemUIDIV";
		$programID = "TKI_0";
		$gridName = 'gridTrackingItem';
		$filterMenuName = 'TKIFilteredMenu';
		
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
                    popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('transMainID'));
                    //popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('sendID'));
                }
            },{
                id: 'btnSaveToTrack_{$programID}',
                text:'{$lang['df']['saveToTrack']}',
                iconCls: 'bookmarkIcon',
                disabled: true,
                hidden: true,
                handler: function(e) {
                    Ext.MessageBox.confirm('Confirm', '�ѹ�֡ [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ����¡�õԴ���?', addToTrack_{$programID});
                }
            },{
                id: 'btnPause_{$programID}',
                text:'{$lang['df']['pause']}',
                iconCls: 'forbidIcon',
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
                                if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
                                    var holdCaption = '�����Թ����͡���';
                                } else {
                                    var holdCaption = '��ش��Ǩ�ͺ�͡���';
                                }
                                Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJob_{$programID}); 
                            }
                        }
                    });
                    
                    /*
                    if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
                        var holdCaption = '�����Թ����͡���';
                    } else {
                        var holdCaption = '��ش��Ǩ�ͺ�͡���';
                    }
                    Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJob_{$programID});
                    */
                }
            },{
                id: 'btnViewLog_{$programID}',
                text:'{$lang['df']['viewlog']}',
                iconCls: 'historyIcon',
                disabled: true,
                handler: function(e) {
                    popupDocLog({$gridName}.getSelectionModel().getSelected().get('transMainID'));
                    //popupDocLog({$gridName}.getSelectionModel().getSelected().get('sendID'));
                }
            }";             
        $auditMenu .= "$auditSubMenu]}
        );";
		
	    $orgID = $sessionMgr->getCurrentOrgID();
	    $regBookID = 0;
	    $storeName = "TKIStore_{$regBookID}";
	    $TKIStore = $dfStore->getTrackItemStore($storeName);
		$accountStore = $store->getDataStore('account');
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
		
		$callModule = 'TrackItem';		
		$dblClickFunction = "
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
                    Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('sendID'));
                    viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'{$callModule}',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('transMainID'));
                }
            }
        });
                ";
		
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		//Received Internal Data Store
		{$TKIStore}
        
        {$auditMenu}
		
		{$storeName}.setDefaultSort('sendNo', 'desc');
		    
	    var cm_{$programID} = new Ext.grid.ColumnModel([{
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
               renderer: renderDFTitle
	        },{
	           header: \"{$lang['df']['date']}\",
	           dataIndex: 'docDate',
	           width: 95                           
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
            	filterSendRecordWindow.show();
            	Ext.getCmp('filterSendType').setValue('Track');            	
			}
        },{
	 		id: 'btnPreviewDocument_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
            	//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));            	
                {$dblClickFunction}
			}
        },{
            id: 'btnAuditDoc_{$programID}',
            text:'{$lang['df']['auditBook']}',
            iconCls: 'auditIcon',
            menu: {$auditMenuName},
            disabled: true
        },{
            id: 'btnRemoveTrack_{$programID}',
            text: '{$lang ['common'] ['cancel']}',
            iconCls: 'deleteIcon',
            disabled: true,
            handler: function() {
                var unholdCaption = '¡��ԡ�Դ����͡���';  
                Ext.MessageBox.confirm('Confirm', unholdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',removeFromTrack_{$programID}); 
            }
        },{
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
                            Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                            Ext.getCmp('btnTrack_{$programID}').disable();
                            Ext.getCmp('btnRemoveTrack_{$programID}').disable();
                            
                            // Todo: ���Դ Function ��ҹ
                            Ext.getCmp('btnPause_{$programID}').disable();
                            Ext.getCmp('btnViewLog_{$programID}').disable();
                            Ext.getCmp('btnAuditDoc_{$programID}').disable();      
                            
                            {$storeName}.load({params:{start:0, limit:25}});
                        }
                    }
                });
                
			}
        });

	    // render it
		{$gridName}.render();
		
		{$storeName}.load({params:{start:0, limit:25}});
	
		{$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    
	    {$gridName}.on('rowdblclick',function() {
			//Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('sendID'));
			//viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
            {$dblClickFunction}
	    	//alert('dbl click');
		}
		,{$gridName});
		
	    {$gridName}.on({
			'rowclick' : function() {
				Ext.getCmp('btnPreviewDocument_{$programID}').enable();
				Ext.getCmp('btnTrack_{$programID}').enable();
				//Ext.getCmp('btnSaveToTrack_{$programID}').enable();
				//Ext.getCmp('btnForward_{$programID}').enable();
                Ext.getCmp('btnAuditDoc_{$programID}').enable();      
                Ext.getCmp('btnRemoveTrack_{$programID}').enable();      
				
				Ext.getCmp('btnPause_{$programID}').enable();
				Ext.getCmp('btnViewLog_{$programID}').enable();
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
                        	id: {$gridName}.getSelectionModel().getSelected().get('docID') 
						}
                });                
            }	
		}
        
        function removeFromTrack_{$programID}(btn) {
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
                        url: '/{$config ['appName']}/df-action/remove-track',
                        method: 'POST',
                        success: function(o){
                            Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            var responseMsg = '';
                            if(r.success == 1) {
                                responseMsg = '¡��ԡ���º��������';
                            } else {
                                responseMsg = '¡��ԡ�����';
                            }
                            
                            Ext.MessageBox.show({
                                title: '���¡��ԡ��¡�õԴ���',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.INFO
                            });
                            {$storeName}.reload();
                        },
                        params: {
                            mode: 'r', 
                            id: {$gridName}.getSelectionModel().getSelected().get('transMainID') 
                        }
                });                
            }    
        }
		
		function holdJob_{$programID}(btn) {
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
                        	id: {$gridName}.getSelectionModel().getSelected().get('transMainID') 
						}
                });                
            }
		}
	        
		</script>";
	}
}
