<?php
/**
 * Portlet : หนังสือส่งกลับ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class SendbackItemPortlet {

public function __construct() {
        //include_once 'DFStore.php';
    }
    
	public function getUI() {
		global $config;
        global $lang;
		global $store;
		
		checkSessionPortlet();
        
        $dfStore = new DFStore();
		
		$sendbackStore = $dfStore->getSendbackStore('sendbackStore');
		//$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		//$accountTypeStore = $store->getDataStore ( 'accountType' );
        //$acceptDocTypeStore = $store->getDataStore('documentTypeList','acceptDocTypeStore');
		
		$gridName = 'gridSendbackItem';
		$programID = 'SBI';
		
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
		
		echo "<div id=\"SenbackItemUI\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		$sendbackStore
		
        sendbackStore.setDefaultSort('sendID','asc');

        var cmSendbackItem = new Ext.grid.ColumnModel([{
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
	
	    cmSendbackItem.defaultSortable = true;
	
	    var gridSendbackItem = new Ext.grid.GridPanel({
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: sendbackStore,
	        
	        tbar: new Ext.Toolbar({
				id: 'sendbackItemToolbar',
				height: 25				
			}),
			cm: cmSendbackItem,
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
	        loadMask: true,
	        renderTo: 'SenbackItemUI',
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
	            store: sendbackStore,
	            displayInfo: true,
	            displayMsg: 'Displaying Unreceived Item(s) {0} - {1} of {2}',
	            emptyMsg: \"No Unreceived Item\"
	        })
	    });
    
    	var tbSendbackItem = Ext.getCmp('sendbackItemToolbar');
		
	 	tbSendbackItem.add({
	 		id: 'btnSearchDocument_{$programID}',
            text:'{$lang['df']['search']}',
            iconCls: 'searchIcon',
            disabled: false,
            hidden: false,
            handler: function() {
            	filterSendRecordWindow.show();
            	Ext.getCmp('filterSendType').setValue('SendBack');            	
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
        	id: 'btnSendbackAck',
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
                            Ext.MessageBox.confirm('Confirm', 'รับทราบการส่งกลับ [ '+gridSendbackItem.getSelectionModel().getSelected().get('title')+']?',ackSendbackSelectedDocument);
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
                            if(gridSendbackItem.getSelectionModel().getSelected().get('sendType') == 2 || gridSendbackItem.getSelectionModel().getSelected().get('sendType') == 3) {
                                changeReceiverExternalWindow.show();  
                                //On Select
                                Ext.getCmp('changeRecvExt').on('select',function(c,r,i) {         
                                    dataRecord = c.store.getAt(i);
                                    var rec = new ReceiverRecordDataDef({
                                                dataid: dataRecord.data.id,
                                                name: dataRecord.data.name,
                                                description: dataRecord.data.desctype,
                                                typeid: dataRecord.data.typeid
                                    });
                                    
                                    Ext.getCmp('changeReceiverExternalForm').getForm().setValues([
                                        {id:'refTransChangeExtID' ,value: gridSendbackItem.getSelectionModel().getSelected().get('sendID')},
                                        {id:'changeRecvExtType' ,value: dataRecord.data.typeid}
                                    ]);
                                    
                                    //Ext.getCmp('changeRecvExt').emptyText = '';
                                    //Ext.getCmp('changeRecvExt').reset();      
                                },this);
                            } else {
                                changeReceiverInternalWindow.show();
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
                                        {id:'refTransChangeIntID' ,value: gridSendbackItem.getSelectionModel().getSelected().get('sendID')},
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
                            //accountStore.load({params:{start:0, limit:25}});
                            Ext.getCmp('btnPreview_{$programID}').disable();
                            Ext.getCmp('btnForward_{$programID}').disable();
                            Ext.getCmp('btnSendbackAck').disable();
                            {$gridName}.getSelectionModel().clearSelections();
                            sendbackStore.reload();
                        }
                    }
                });
            	
			}
        });

        function ackSendbackSelectedDocument(btn) {
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
                        url: '/{$config ['appName']}/df-action/ack-sendback',
                        method: 'POST',
                        success: function(o){
                                  Ext.MessageBox.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: 'รับทราบการส่งกลับเอกสาร',
                                    msg: 'รับทราบการส่งกลับเรียบร้อยแล้ว',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  sendbackStore.reload();
                            },
                        //failure: deletePolicyFailed,
                        params: { id: gridSendbackItem.getSelectionModel().getSelected().get('sendID') }
                });                
            }
        }

	    // render it
	    gridSendbackItem.render();
	
	    // trigger the data store load
	    sendbackStore.load({params:{start:0, limit:25}});
	    gridSendbackItem.colModel.renderCellDelegate = renderCell.createDelegate(gridSendbackItem.colModel);
	    
	    gridSendbackItem.on({
			'rowclick' : function() {
				Ext.getCmp('btnPreview_{$programID}').enable();
                Ext.getCmp('btnForward_{$programID}').enable();
				Ext.getCmp('btnChangeReceiver_{$programID}').enable();
				if( gridSendbackItem.getSelectionModel().getSelected().get('hold')==1) {
					Ext.getCmp('btnSendbackAck').disable(); 
				} else {
					Ext.getCmp('btnSendbackAck').enable(); 
				}
                
                Cookies.set('sendbackRecordID',gridSendbackItem.getSelectionModel().getSelected().get('sendID'));				
			},
			scope: this
		});
		
		gridSendbackItem.on('rowdblclick',function() {
			{$dblClickFunction}
		},this);
	
	    function toggleSendbackDetails(btn, pressed){
	        var view = gridSendbackItem.getView();
	        view.showPreview = pressed;
	        view.refresh();
	    }
	                                            
		</script>";
	}
}