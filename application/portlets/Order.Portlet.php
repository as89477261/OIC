<?php
/**
 * Portlet : �����/��С��
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class OrderPortlet {
    
    public function __construct() {
        //include_once 'DFStore.php';
    }
    
    public function getUI() {
        global $config;
        global $conn;
        global $store;
        global $lang;
        global $sessionMgr;
        
        $dfStore = new DFStore ( );
        
        $sqlGetFilter = "select * from tbl_doc_type order by f_doctype_id";
        $rs = $conn->CacheExecute ( $config ['defaultCacheSecs'], $sqlGetFilter );
        $DIVName = "OrdersUIDIV";
        $programID = "ORD_0";
        $gridName = 'gridOrders';
        $filterMenuName = 'ORDFilteredMenu';
        
        $dblClickFunction = "Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('sendID'));
        viewAnnounce('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('id'),{$gridName}.getSelectionModel().getSelected().get('title'),'CMD',{$gridName}.getSelectionModel().getSelected().get('id'),{$gridName}.getSelectionModel().getSelected().get('id'));";
        
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
                    popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('sendID'));
                }
            },{
                id: 'btnSaveToTrack_{$programID}',
                text:'{$lang['df']['saveToTrack']}',
				hidden: true,
                iconCls: 'bookmarkIcon',
                disabled: true,
                handler: function(e) {
                    Ext.MessageBox.confirm('Confirm', '�ѹ�֡ [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ����¡�õԴ���?', addToTrack_{$programID});
                }
            },{
                id: 'btnPause_{$programID}',
                text:'{$lang['df']['pause']}',
                iconCls: 'forbidIcon',
                disabled: true,
                handler: function(e) {
                    if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
                        var holdCaption = '�����Թ����͡���';
                    } else {
                        var holdCaption = '��ش��Ǩ�ͺ�͡���';
                    }
                    Ext.MessageBox.confirm('Confirm', holdCaption+' [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',holdJob_{$programID});
                }
            },{
                id: 'btnViewLog_{$programID}',
                text:'{$lang['df']['viewlog']}',
                iconCls: 'historyIcon',
                disabled: true,
                handler: function(e) {
                    popupDocLog({$gridName}.getSelectionModel().getSelected().get('sendID'));
                }
            }";             
        $auditMenu .= "$auditSubMenu]}
        );";
        
        $orgID = $sessionMgr->getCurrentOrgID ();
        $regBookID = 0;
        $storeName = "OrderStore_{$regBookID}";
        $RIStore = $dfStore->getOrderStore ( $storeName );
        $accountStore = $store->getDataStore ( 'account' );
        $rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
        $accountTypeStore = $store->getDataStore ( 'accountType' );
        
        echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
        echo "<script type=\"text/javascript\">
        //Received Internal Data Store
        {$RIStore}
        
        {$auditMenu}
        
        {$storeName}.setDefaultSort('catno', 'desc');
        
        function renderAnnounceTitle(value, p, record){
            if(record.data.hasAttach == 1 ){
                return '<img src=\"/{$config['appName']}/images/attachment.gif\" />&nbsp;'+record.data.title;
            } else {
                return record.data.title;
            }
        }
        
        var cm_{$programID} = new Ext.grid.ColumnModel([{
               header: \"{$lang['order']['no']}\",
               dataIndex: 'docno',
               width: 75,
               align: 'left'
            },{
               header: \"{$lang['order']['type']}\",
               dataIndex: 'typename',
               width: 75,
               align: 'left'
            },{
               id: 'id', 
               header: \"{$lang['order']['section']}\",
               dataIndex: 'catname',
               width: 100
            },{
               header: \"{$lang['order']['title']}\",
               dataIndex: 'title',
               width: 150,
               renderer: renderAnnounceTitle
            },{
               header: \"{$lang['order']['date']}\",
               dataIndex: 'date',
               width: 100
            },{
               header: \"{$lang['order']['signer']}\",
               dataIndex: 'signuser',
               width: 130,
               align: 'left'
            },{
               header: \"{$lang['order']['role']}\",
               dataIndex: 'signrole',
               width: 95
            },{
               header: \"{$lang['order']['orgName']}\",
               dataIndex: 'orgName',
               width: 150
            },{
               header: \"{$lang['order']['remark']}\",
               dataIndex: 'remark',
               hidden: true,
               width: 150
            },{
				header:\"{$lang['order']['section']}\",
				dataIndex:'catno',
				hidden:true
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
            	filterOrderWindow.show();
            	Ext.getCmp('filterOrderSendType').setValue('Orders');                                
            }
        },{
             id: 'btnPreviewDocument_{$programID}',
            text:'{$lang['df']['view']}',
            iconCls: 'viewIcon',
            disabled: false,
            hidden: false,
            handler: function() {
                {$dblClickFunction}
            }
        },{
            id: 'btnAuditDoc_{$programID}',
            text:'{$lang['df']['auditBook']}',
            iconCls: 'auditIcon',
            hidden: true,
            menu: {$auditMenuName},
            disabled: true
        },{
             id: 'btnCallbackDocument_{$programID}',
            text:'{$lang['df']['callback']}',
            iconCls: 'sendbackIcon',
            hidden: true,
            disabled: true,
            handler: function() {
                Ext.getCmp('callbackRefID').setValue({$gridName}.getSelectionModel().getSelected().get('sendID'));
                callbackWindow.show();
            }
        }/*,{
            id: 'btnTrack_{$programID}',
            text:'{$lang['df']['track']}',
            iconCls: 'infoIcon',
            disabled: true,
            handler: function() {
                popupTrack('SI',{$gridName}.getSelectionModel().getSelected().get('sendID'));
            }
        },{
            id: 'btnSaveToTrack_{$programID}',
            text:'{$lang['df']['saveToTrack']}',
            iconCls: 'bookmarkIcon',
            disabled: true,
            handler: function(e) {
                Ext.MessageBox.confirm('Confirm', '�ѹ�֡ [ '+{$gridName}.getSelectionModel().getSelected().get('title')+'] ����¡�õԴ���?', addToTrack_{$programID});
            }
        }*/,{
            id: 'btnForward_{$programID}',
            text:'{$lang['df']['forward']}',
            iconCls: 'forwardIcon',
            hidden: true,
            disabled: true,
            handler: function(e) {
                Cookies.set('fwD',{$gridName}.getSelectionModel().getSelected().get('sendID'));
                Cookies.set('rc','forwardTransTo');
                Cookies.set('rcH','forwardTransToHidden');
                Cookies.set('rp','{$programID}');
                forwardDFWindow.show();
            }
        },{
            text:'{$lang['df']['fetch']}',
            iconCls: 'refreshIcon',
            handler: function(){
                {$gridName}.getSelectionModel().clearSelections();
                Ext.getCmp('btnAuditDoc_{$programID}').disable();
                Ext.getCmp('btnPreviewDocument_{$programID}').disable();
                Ext.getCmp('btnTrack_{$programID}').disable();
                Ext.getCmp('btnSaveToTrack_{$programID}').disable();
                Ext.getCmp('btnForward_{$programID}').disable();
                Ext.getCmp('btnCallbackDocument_{$programID}').disable();
                
                // Todo: ���Դ Function ��ҹ
                //Ext.getCmp('btnSendEmail_{$programID}').disable();                
                //Ext.getCmp('btnReturn_{$programID}').disable();
                Ext.getCmp('btnPause_{$programID}').disable();
                Ext.getCmp('btnViewLog_{$programID}').disable();
                {$storeName}.reload();
            }
        });

        // render it
        {$gridName}.render();
        
        {$storeName}.load({params:{start:0, limit:25}});
    
        {$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
        
        {$gridName}.on('rowdblclick',function() {
            Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('id'));
            {$dblClickFunction}
        }
        ,{$gridName});
        
        {$gridName}.on({
            'rowclick' : function() {
                Ext.getCmp('btnAuditDoc_{$programID}').enable();
                Ext.getCmp('btnPreviewDocument_{$programID}').enable();
                if({$gridName}.getSelectionModel().getSelected().get('received') ==0) {
                    Ext.getCmp('btnCallbackDocument_{$programID}').enable();
                } else {
                    Ext.getCmp('btnCallbackDocument_{$programID}').disable();
                } 
                
                if({$gridName}.getSelectionModel().getSelected().get('hold') == 1) {
                    Ext.getCmp('btnCallbackDocument_{$programID}').disable();
                    Ext.getCmp('btnForward_{$programID}').disable();
                } else {
                    Ext.getCmp('btnForward_{$programID}').enable();
                }
                
                Ext.getCmp('btnTrack_{$programID}').enable();
                Ext.getCmp('btnSaveToTrack_{$programID}').enable();
                
                // Todo: ���Դ Function ��ҹ
                //Ext.getCmp('btnSendEmail_{$programID}').enable();
                //Ext.getCmp('btnReturn_{$programID}').enable();
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
                            id: {$gridName}.getSelectionModel().getSelected().get('sendID') 
                        }
                });                
            }    
        }
        
        function callbackItem_{$programID}(btn){
            if(btn == 'yes') {
                Ext.MessageBox.show({
                        msg: '���ѧ�֧�׹��¡����...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/callback-item',
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
                            id: {$gridName}.getSelectionModel().getSelected().get('sendID') 
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
                            id: {$gridName}.getSelectionModel().getSelected().get('sendID') 
                        }
                });                
            }
        }
            
        </script>";
    }
}
