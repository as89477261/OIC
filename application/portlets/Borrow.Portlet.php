<?php
/**
 * Portlet : หนังสือส่งกลับ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class BorrowPortlet {

public function __construct() {
        //include_once 'DFStore.php';
    }
    
	public function getUi() {
		global $config;
        global $lang;
		global $store;
		
		checkSessionPortlet();
        
        $dmsStore = new DMSStore();
		
		$gridName = 'gridBorrowItem';
		$programID = 'BRI';
		$storeName= 'borrowItemStore';
		
		$borrowStore = $dmsStore->getBorrowItemStore($storeName);
		
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
		
		echo "<div id=\"BorrowItemUI\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		{$borrowStore}
		
		{$storeName}.setDefaultSort('sendID','asc');

        var cmSendbackItem = new Ext.grid.ColumnModel([{
	           header: \"ชื่อเรื่อง\",
	           dataIndex: 'title',
	           width: 100
	        },{
	    	   id: 'id', 
	           header: \"เลขที่\",
	           dataIndex: 'docNo',
	           width: 100
	        },{
	           header: \"ผู้ยืม\",
	           dataIndex: 'borrower',
	           width: 100
	        },{
	           header: \"กำหนดคืน\",
	           dataIndex: 'dueDate',
	           width: 100
	        },{
	        	header: \"รายละเอียด/หน้าเอกสารที่ยืม\",
	           dataIndex: 'detail',
	           width: 100
			}
		]);
	
	    cmSendbackItem.defaultSortable = true;
	
	    var {$gridName} = new Ext.grid.GridPanel({
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: {$storeName},
	        
	        tbar: new Ext.Toolbar({
				id: 'sendbackItemToolbar',
				height: 25				
			}),
			cm: cmSendbackItem,
	        trackMouseOver:false,
	        sm: new Ext.grid.RowSelectionModel({multiSelect:true}),
	        loadMask: true,
	        renderTo: 'BorrowItemUI',
	        view: new Ext.grid.GroupingView({forceFit:true}),
	        bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: {$storeName},
	            displayInfo: true,
	            displayMsg: 'Displaying Unreceived Item(s) {0} - {1} of {2}',
	            emptyMsg: \"No Unreceived Item\"
	        })
	    });
    
    	var tbSendbackItem = Ext.getCmp('sendbackItemToolbar');
		
	 	tbSendbackItem.add({
	 		id: 'btnPreview_{$programID}',
            text:'{$lang['df']['preview']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
				{$dblClickFunction}
			}
        },{
        	id: 'btnBorrowAck',
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
                            Ext.MessageBox.confirm('Confirm', 'รับทราบการคืนเอกสาร [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',ackReturnSelectedDocument);
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
                            Ext.getCmp('btnBorrowAck').disable();
                            {$gridName}.getSelectionModel().clearSelections();
                            {$storeName}.reload();
                        }
                    }
                });
            	
			}
        });

        function ackReturnSelectedDocument(btn) {
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
                        url: '/{$config ['appName']}/df-action/ack-return',
                        method: 'POST',
                        success: function(o){
                                  Ext.MessageBox.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: 'รับทราบการคืนเอกสาร',
                                    msg: 'รับทราบการคืนเรียบร้อยแล้ว',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  {$storeName}.reload();
                            },
                        params: { id: {$gridName}.getSelectionModel().getSelected().get('borrowID') }
                });                
            }
        }

	    // render it
	    {$gridName}.render();
	
	    // trigger the data store load
	    {$storeName}.load({params:{start:0, limit:25}});
	    {$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    {$gridName}.on({
			'rowclick' : function() {
				Ext.getCmp('btnPreview_{$programID}').enable();
				Ext.getCmp('btnBorrowAck').enable(); 
                
                //Cookies.set('sendbackRecordID',{$gridName}.getSelectionModel().getSelected().get('sendID'));				
			},
			scope: this
		});
		
		{$gridName}.on('rowdblclick',function() {
			{$dblClickFunction}
		},this);
	
	    function toggleSendbackDetails(btn, pressed){
	        var view = {$gridName}.getView();
	        view.showPreview = pressed;
	        view.refresh();
	    }
	                                            
		</script>";
	}
}