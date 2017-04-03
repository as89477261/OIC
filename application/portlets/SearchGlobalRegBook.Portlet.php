<?php
/**
 * Portlet : ค้นหาทะเบียนรวม
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class SearchGlobalRegBookPortlet {
    
    public function __construct() {
        //include_once 'DFStore.php';
    }
    
    public function getUI() {
        global $config;
        global $conn;
        global $store;
        global $policy;
        global $lang;
        global $sessionMgr;
        
        $dfStore = new DFStore();
        
        checkSessionPortlet();
        
        $DIVName = "SearchGlobalRegBookUIDIV";
        $programID = "SGR_0";
        $gridName = 'gridSearchGlobalRegBook';
        /*
        $filterMenuName = 'SGRFilteredMenu';
        
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
        
        */
        
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
                    popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('transID'));
                }
            },{
                id: 'btnSaveToTrack_{$programID}',
                text:'{$lang['df']['saveToTrack']}',
				hidden: true,
                iconCls: 'bookmarkIcon',
                disabled: false,
                hidden: true,
                handler: function(e) {
                    Ext.MessageBox.confirm('Confirm', 'บันทึก [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] เป็นรายการติดตาม?', addToTrack_{$programID});
                }
            },{
                id: 'btnPause_{$programID}',
                text:'{$lang['df']['pause']}',
                iconCls: 'forbidIcon',
                disabled: false,
                hidden: true,
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
                    popupDocLog({$gridName}.getSelectionModel().getSelected().get('transID'));
                }
            }";             
        $auditMenu .= "$auditSubMenu]}
        );";
        
        //if((int)$sessionMgr->getCurrentAccountEntity()->f_account_type >= 3) {
        if($policy->canChangeFlag()) {
            $flagButton = ",{
                id: 'btnFlagInternal_{$programID}',
                text:'Flag Bookno Internal',
                iconCls: 'addIcon',
                disabled: true,
                handler: function() {
                    Ext.MessageBox.confirm('Confirm', 'บันทึกให้ออกเลขภายในหนังสือ [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ?', toggleInternalFlag_{$programID});    
                }
            },{
                id: 'btnFlagExternal_{$programID}',
                text:'Flag Bookno External',
                iconCls: 'addIcon',
                disabled: true,
                handler: function() {
                    Ext.MessageBox.confirm('Confirm', 'บันทึกให้ออกเลขภายนอก [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ?', toggleExternalFlag_{$programID});
                }
            },{
                id: 'btnFlagCommand_{$programID}',
                text:'Flag Command',
                iconCls: 'addIcon',
                disabled: true,
                handler: function() {
                    Ext.MessageBox.confirm('Confirm', 'บันทึกให้ออกคำสั่ง [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ?', toggleCommandFlag_{$programID});
                }
            }";
        } else {
            $flagButton = "";
        }
        
        $orgID = $sessionMgr->getCurrentOrgID();
        $regBookID = 0;
        $storeName = "SGRStore_{$regBookID}";
        $RIStore = $dfStore->getExecutiveSearchStore($storeName);
        //$accountStore = $store->getDataStore('account');
        //$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
        //$accountTypeStore = $store->getDataStore ( 'accountType' );
        
        $callModule = "SGR";
        
        $dblClickFunction = "Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('transID'));
        viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('transID'),{$gridName}.getSelectionModel().getSelected().get('title'),'{$callModule}',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('transID'));";
            
        
        
        echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
        echo "<script type=\"text/javascript\">
        //Received Internal Data Store
        {$RIStore}
        
        {$auditMenu}
        
        {$storeName}.setDefaultSort('sendNo', 'desc');
        {$storeName}.load({params:{start:0, limit:25}});
        
        var cm_{$programID} = new Ext.grid.ColumnModel([{
               header: \"Trans.ID\",
               dataIndex: 'transID',
               hidden: true,
               width: 50
            },{
               header: \"{$lang['df']['classified']}\",
               dataIndex: 'secret',
               width: 50,
               //hidden: true,
               renderer: renderSecret
            },{
               header: \"{$lang['df']['speed']}\",
               dataIndex: 'speed',
               width: 50,
               //hidden: true,
               renderer: renderSpeed
            },{
               header: \"{$lang['df']['docNo']}\",
               dataIndex: 'docNo',
               width: 100,
               renderer: renderDFBookno
            },{
               header: \"{$lang['df']['date']}\",
               dataIndex: 'bookdate',
               width: 95
            },{
               header: \"{$lang['df']['title']}\",
               dataIndex: 'title',
               width: 130,
               align: 'left',
               renderer: renderDFTitle
            },{
               header: \"ประเภทเรื่อง\",
               dataIndex: 'originalType',
               width: 150
            },{
				header: \"ผู้ส่ง\",
				dataIndex: 'sendName',
				width: 100
			},{
				header: \"ผู้รับ\",
				dataIndex: 'recvName',
				width: 100
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
            }/*,
            bbar: new Ext.PagingToolbar({
                id: 'pagingToolbar_{$programID}',
                pageSize: 25,
                //params: {query: Ext.getCmp('globalSearchKeyword').getValue()},
                //filter: {query: Ext.getCmp('globalSearchKeyword').getValue()},
                store: {$storeName},
                displayInfo: true,
                displayMsg: '{$lang['df']['receivedInternal']} {0} - {1} {$lang['common']['of']} {2}',
                emptyMsg: \"{$lang['df']['noDocument']}\"                
            })*/
        });

        var tb_{$programID} = Ext.getCmp('{$programID}_Toolbar');
        
        tb_{$programID}.add('ค้นหาทะเบียนรวม'
        ,{
            xtype: 'textfield',
            width: 225,
            id: 'globalSearchKeyword'
        },{
             id: 'btnSearchDocument_{$programID}',
            text:'{$lang['df']['search']}',
            iconCls: 'searchIcon',
            disabled: false,
            handler: function() {
                //filterSendRecordWindow.show();
                //alert(Ext.getCmp('globalSearchKeyword').getValue());
                {$storeName}.load({
                    params:{start:0, limit:25,query: Ext.getCmp('globalSearchKeyword').getValue()}
                });   
                //Ext.getCmp('filterSendType').setValue('SendExt');
            }
        },'-',{
             id: 'btnPreviewDocument_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
                {$dblClickFunction}
                //viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
                //accountAddForm.getForm().reset();
                //accountAddWindow.show();
            }
        },{
            id: 'btnAuditDoc_{$programID}',
            text:'{$lang['df']['auditBook']}',
            iconCls: 'auditIcon',
            menu: {$auditMenuName},
            disabled: true
        }{$flagButton});
        
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
    
        // trigger the data store load
        //accountStore.load({params:{start:0, limit:25}});
        
        
        {$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
        
        
        {$gridName}.on('rowdblclick',function() {
            {$dblClickFunction}
            //Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('transID'));
            //viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('transID'),{$gridName}.getSelectionModel().getSelected().get('title'),'RI',{$gridName}.getSelectionModel().getSelected().get('docID'));
            //alert('dbl click');
        }
        ,{$gridName});
        
        {$gridName}.on({
            'rowclick' : function() {
                Ext.getCmp('btnPreviewDocument_{$programID}').enable();
                Ext.getCmp('btnAuditDoc_{$programID}').enable();  
                Ext.getCmp('btnFlagInternal_{$programID}').enable();
                Ext.getCmp('btnFlagExternal_{$programID}').enable();
                Ext.getCmp('btnFlagCommand_{$programID}').enable();
                       
                //Ext.getCmp('btnForward_{$programID}').enable();       
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
                            id: {$gridName}.getSelectionModel().getSelected().get('transID') 
                        }
                });                
            }    
        }
        
        function toggleInternalFlag_{$programID}(btn) {
            if(btn == 'yes') {
                Ext.MessageBox.show({
                        msg: 'Changing Flag...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/change-flag-internal',
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
                                title: 'Changing Flag',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.INFO
                            });
                            {$gridName}.getSelectionModel().clearSelections();
                            Ext.getCmp('btnFlagInternal_{$programID}').disable();
                            Ext.getCmp('btnFlagExternal_{$programID}').disable();
                            Ext.getCmp('btnFlagCommand_{$programID}').disable();
                            
                            {$storeName}.reload();
                        },
                        params: {
                            mode: 'r', 
                            id: {$gridName}.getSelectionModel().getSelected().get('docID') 
                        }
                });                
            }    
        }
        
        function toggleExternalFlag_{$programID}(btn) {
            if(btn == 'yes') {
                Ext.MessageBox.show({
                        msg: 'Changing Flag...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/change-flag-external',
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
                                title: 'Changing Flag',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.INFO
                            });
                            {$gridName}.getSelectionModel().clearSelections();
                            Ext.getCmp('btnFlagInternal_{$programID}').disable();
                            Ext.getCmp('btnFlagExternal_{$programID}').disable();
                            Ext.getCmp('btnFlagCommand_{$programID}').disable();
                            
                            {$storeName}.reload();
                        },
                        params: {
                            mode: 'r', 
                            id: {$gridName}.getSelectionModel().getSelected().get('docID') 
                        }
                });                
            }    
        }
    	
        function toggleCommandFlag_{$programID}(btn) {
            if(btn == 'yes') {
                Ext.MessageBox.show({
                        msg: 'Changing Flag...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/change-flag-command',
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
                                title: 'Changing Flag',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.INFO
                            });
                            {$gridName}.getSelectionModel().clearSelections();
                            Ext.getCmp('btnFlagInternal_{$programID}').disable();
                            Ext.getCmp('btnFlagExternal_{$programID}').disable();
                            Ext.getCmp('btnFlagCommand_{$programID}').disable();
                            
                            {$storeName}.reload();
                        },
                        params: {
                            mode: 'r', 
                            id: {$gridName}.getSelectionModel().getSelected().get('docID') 
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
                            
                            {$gridName}.getSelectionModel().clearSelections();
                            Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                            Ext.getCmp('btnAuditDoc_{$programID}').disable();      
                            //Ext.getCmp('btnForward_{$programID}').disable();
                                                                                       
                            {$storeName}.reload(); 
                        },
                        params: {
                            id: {$gridName}.getSelectionModel().getSelected().get('transID') 
                        }
                });                
            }
        }
            
        </script>";
    }
}

