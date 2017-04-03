<?php
/**
 * Portlet : หนังสือเวียนภายนอก
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class CircBookExternalPortlet {
	
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
		$DIVName = "ReceivedExternalUIDIV";
		$programID = "RE_0";
		$gridName = 'gridReceivedExternalItem';
		$filterMenuName = 'REFilteredMenu';
		
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
		
	    $orgID = $sessionMgr->getCurrentOrgID();
	    $regBookID = 0;
	    $storeName = "REStore_{$regBookID}";
	    $REStore = $dfStore->getREStore($regBookID,$storeName);
		$accountStore = $store->getDataStore('account');
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		//Received Internal Data Store
		{$REStore}
		
		{$storeName}.setDefaultSort('recvNo', 'desc');
		{$storeName}.load({params:{start:0, limit:25}});
		
	    var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"{$lang['df']['recvNo']}\",
	           dataIndex: 'recvNo',
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
            iconCls: 'bmenu',
            disabled: false,
            handler: function() {
            	filterReceiveRecordWindow.show();
            	Ext.getCmp('filterRecvType').setValue('RecvExt');
			}
        },{
	 		id: 'btnPreviewDocument_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function() {
            	viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
            	//accountAddForm.getForm().reset();
				//accountAddWindow.show();
			}
        },{
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
            	Ext.MessageBox.confirm('Confirm', 'บันทึก [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] เป็นรายการติดตาม?', addToTrackSE);
			}
        },{
        	id: 'btnForward_{$programID}',
            text:'{$lang['df']['forward']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	Cookies.set('fwD',{$gridName}.getSelectionModel().getSelected().get('recvID'));
            	Cookies.set('rc','forwardTransTo');
                Cookies.set('rcH','forwardTransToHidden');
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
        }*/,{
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
            	Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJobSE);
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
        },{
        	id: 'btnSaveCommand_{$programID}',
            text:'{$lang['df']['saveCommand']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	id: 'btnSaveCommand_{$programID}',
            text:'{$lang['df']['excelExport']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//Ext.MessageBox.confirm('Confirm', 'Delete Account [ '+{$gridName}.getSelectionModel().getSelected().get('name')+']?', deleteSelectedAccount);
			}
        },{
        	id: 'btnSaveCommand_{$programID}',
            text:'{$lang['df']['closeJob']}',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
			}
        },{
            text:'{$lang['df']['fetch']}',
            iconCls: 'bmenu',
            handler: function(){
            	Ext.getCmp('btnPreviewDocument_{$programID}').disable();
				Ext.getCmp('btnTrack_{$programID}').disable();
				Ext.getCmp('btnSaveToTrack_{$programID}').disable();
				Ext.getCmp('btnForward_{$programID}').disable();
				
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
	
	    // trigger the data store load
	    //accountStore.load({params:{start:0, limit:25}});
	    
	    
		{$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    
	{$gridName}.on('rowdblclick',function() {
			Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
			viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('recvID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
	    	//alert('dbl click');
		}
		,{$gridName});
		
	{$gridName}.on({
			'rowclick' : function() {
				Ext.getCmp('btnPreviewDocument_{$programID}').enable();
				Ext.getCmp('btnTrack_{$programID}').enable();
				Ext.getCmp('btnSaveToTrack_{$programID}').enable();
				Ext.getCmp('btnForward_{$programID}').enable();
				
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
	    
	    function addToTrackSE(btn) {
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
		
		function holdJobSE(btn) {
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