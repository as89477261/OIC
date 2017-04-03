<?php
/**
 * Portlet : หนังสือดึงคืน
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class CallbackItemPortlet {

public function __construct() {
        //include_once 'DFStore.php';
    }
    
	public function getUI() {
		global $config;
        global $lang;
		global $store;
		checkSessionPortlet();
        
        $dfStore = new DFStore();
		$storeName = 'callbackStore';
		$CallbackStore = $dfStore->getCallbackStore($storeName);
		//$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		//$accountTypeStore = $store->getDataStore ( 'accountType' );
        //$acceptDocTypeStore = $store->getDataStore('documentTypeList','acceptDocTypeStore');
		
		$gridName = 'gridCallbackItem';
		$programID = 'CBI';
		
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
                    viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('sendID'));
                }
            }
        });
                ";
		
		echo "<div id=\"CallbackItemUI\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		$CallbackStore
		
		
		
		{$storeName}.setDefaultSort('sendID','asc');
	
	   	var cmCallbackItem = new Ext.grid.ColumnModel([{
	           header: \"{$lang['df']['sendType']}\",
	           dataIndex: 'sendType',
	           width: 100,
               renderer: renderSendType
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
	           width: 200,
	           align: 'left',
	           renderer: renderDFTitle
	        },{
	           header: \"{$lang['df']['date']}\",
	           dataIndex: 'docDate',
	           width: 150
		    },{
		       header: \"{$lang['df']['from']}\",
		       dataIndex: 'from',
		       width: 150
		    },{
		       header: \"{$lang['df']['to']}\",
		       dataIndex: 'to',
		       width: 150
		    },{
		       header: \"{$lang['common']['comment']}\",
		       dataIndex: 'comment',
		       width: 150
		    }		    
		]);
		
	    cmCallbackItem.defaultSortable = true;
		
	    var gridCallbackItem = new Ext.grid.GridPanel({
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: {$storeName},
	        
	        tbar: new Ext.Toolbar({
				id: 'CallbackItemToolbar',
				height: 25				
			}),
			cm: cmCallbackItem,
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
	        loadMask: true,
	        renderTo: 'CallbackItemUI',
	        view: new Ext.grid.GroupingView({forceFit:true}),
	        bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: {$storeName},
	            displayInfo: true,
	            displayMsg: 'Displaying Unreceived Item(s) {0} - {1} of {2}',
	            emptyMsg: \"No Unreceived Item\"
	        })
	    });
    	
    	var tbCallbackItem = Ext.getCmp('CallbackItemToolbar');
		
	 	tbCallbackItem.add({
	 		id: 'btnSearchDocument_{$programID}',
            text:'{$lang['df']['search']}',
            iconCls: 'searchIcon',
            disabled: false,
            hidden: false,
            handler: function() {
            	filterSendRecordWindow.show();
            	Ext.getCmp('filterSendType').setValue('CallBack');            	
			}
        },{
	 		id: 'btnPreview_{$programID}',
            text:'{$lang['df']['preview']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
				{$dblClickFunction}
			}
        },{
        	id: 'btnCallbackAck',
            text:'{$lang['common']['ack']}',
            iconCls: 'checkmarkIcon',
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
                            Ext.MessageBox.confirm('Confirm', 'รับทราบการส่งกลับ [ '+gridCallbackItem.getSelectionModel().getSelected().get('title')+']?',ackCallbackSelectedDocument);
                        }
                    }
                });
            	
			}
        },{
            id: 'btnChangeReceiver_{$programID}',
            text:'{$lang['df']['changeReceiver']}',
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
                            if(gridCallbackItem.getSelectionModel().getSelected().get('sendType') == 2 || gridCallbackItem.getSelectionModel().getSelected().get('sendType') == 3) {
                                changeReceiverExternalWindow.show();  
                                //On Select
                                Ext.getCmp('changeReceiverExternalForm').getForm().setValues([
                                	{id:'refTransChangeExtID' ,value: gridCallbackItem.getSelectionModel().getSelected().get('sendID')}
								]);
                                Ext.getCmp('changeRecvExt').on('select',function(c,r,i) {         
                                    dataRecord = c.store.getAt(i);
                                    var rec = new ReceiverRecordDataDef({
                                                dataid: dataRecord.data.id,
                                                name: dataRecord.data.name,
                                                description: dataRecord.data.desctype,
                                                typeid: dataRecord.data.typeid
                                                
                                    });
                                    
                                    Ext.getCmp('changeReceiverExternalForm').getForm().setValues([
										{id:'changeRecvExtType' ,value: dataRecord.data.typeid}
									]);
                                    
                                    //Ext.getCmp('changeRecvExt').emptyText = '';
                                    //Ext.getCmp('changeRecvExt').reset();      
                                },this);
                            } else {
                                changeReceiverInternalWindow.show();
                                Ext.getCmp('changeReceiverInternalForm').getForm().setValues([
									{id:'refTransChangeIntID' ,value: gridCallbackItem.getSelectionModel().getSelected().get('sendID')}
								]);
                                //On Select
                                Ext.getCmp('changeRecvInt').on('select',function(c,r,i) {         
                                    dataRecord = c.store.getAt(i);
                                    var rec = new ReceiverRecordDataDef({
                                                dataid: dataRecord.data.id,
                                                name: dataRecord.data.name,
                                                description: dataRecord.data.desctype,
                                                typeid: dataRecord.data.typeid
                                                
                                    });
                                    
                                    Ext.getCmp('changeReceiverInternalForm').getForm().setValues([
                                        {id:'changeRecvType' ,value: dataRecord.data.typeid}
                                    ]);
                                    
                                   //Ext.getCmp('changeRecvInt').emptyText = '';
                                    //Ext.getCmp('changeRecvInt').reset();      
                                },this);
                            }
                        }
                    }
                });
                
            }
        },{
        	id: 'btnForward_{$programID}',
            text:'{$lang['df']['forward']}',
            iconCls: 'forwardIcon',
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
                            Cookies.set('fwD',{$gridName}.getSelectionModel().getSelected().get('sendID'));
                            Cookies.set('rc','forwardTransTo');
                            Cookies.set('rcH','forwardTransToHidden');
                            Cookies.set('rp','{$programID}');
                            forwardDFWindow.show();
                        }
                    }
                });
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
                            gridCallbackItem.getSelectionModel().clearSelections();
                            Ext.getCmp('btnPreview_{$programID}').disable();
                            Ext.getCmp('btnCallbackAck').disable();
                            Ext.getCmp('btnForward_{$programID}').disable();
                            {$storeName}.reload();  
                        }
                    }
                });
            	
			}
        });

        function ackCallbackSelectedDocument(btn) {
            if(btn == 'yes') {
            	Ext.MessageBox.show({
		            msg: 'กำลังดำเนินการ',
		            progressText: 'Processing...',
		            width:300,
		            wait:true,
		            waitConfig: {interval:200},
		            icon:'ext-mb-download'
		        });
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/ack-Callback',
                        method: 'POST',
                        success: function(o){
                                  Ext.MessageBox.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: 'รับทราบการดึงคืนเอกสาร',
                                    msg: 'รับทราบการดึงคืนเรียบร้อยแล้ว',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  {$storeName}.reload();
                            },
                        //failure: deletePolicyFailed,
                        params: { id: gridCallbackItem.getSelectionModel().getSelected().get('sendID') }
                });                
            }
        }

	    // render it
	    gridCallbackItem.render();
		
	    // trigger the data store load
	    {$storeName}.load({params:{start:0, limit:25}});
	    gridCallbackItem.colModel.renderCellDelegate = renderCell.createDelegate(gridCallbackItem.colModel);
	    
	    gridCallbackItem.on({
			'rowclick' : function() {
			
				Ext.getCmp('btnPreview_{$programID}').enable(); 
                Ext.getCmp('btnForward_{$programID}').enable(); 
				Ext.getCmp('btnChangeReceiver_{$programID}').enable(); 
				if(gridCallbackItem.getSelectionModel().getSelected().get('hold') == 1) {
					Ext.getCmp('btnCallbackAck').disable(); 
				} else {
					Ext.getCmp('btnCallbackAck').enable(); 
				}
                
                
                
                Cookies.set('CallbackRecordID',gridCallbackItem.getSelectionModel().getSelected().get('sendID'));				
                
                
			},
			scope: this
		});
		
		gridCallbackItem.on('rowdblclick',function() {
			{$dblClickFunction}
		},this);
		
	    function toggleCallbackDetails(btn, pressed){
	        var view = gridCallbackItem.getView();
	        view.showPreview = pressed;
	        view.refresh();
	    }
	                                            
		</script>";
	}
}