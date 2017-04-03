<?php
/**
 * Portlet : หนังสือรอลงรับ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */

class UnreceiveItemPortlet {

    public function __construct() {
        //include_once 'DFStore.php';
    }

	public function getUI() {
		global $config;
        global $lang;
		global $store;
        if($config['receiveFlowByOrigin']) {
            $receiveFlowByOrigin = "
            if(gridUnreceivedItem.getSelectionModel().getSelected().get('originType') == 2){
                Ext.getCmp('acceptAsTypeInternal').setValue(false);
                Ext.getCmp('acceptAsTypeExternal').setValue(true);
                Ext.getCmp('acceptAsTypeCirc').setValue(false);
            } ";
        } else {
            $receiveFlowByOrigin = "";
        }

		checkSessionPortlet();

        $dfStore = new DFStore();

		$unreceivedStore = $dfStore->getUnreceiveStore('unreceivedStore');
		$rankTypeStore = $store->getDataStore ( 'rank', 'rankComboStore' );
		$accountTypeStore = $store->getDataStore ( 'accountType' );
        //$acceptDocTypeStore = $store->getDataStore('documentTypeList','acceptDocTypeStore');

		$gridName = 'gridUnreceivedItem';
		$programID = 'URI';
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
                    viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('sendID'),{$gridName}.getSelectionModel().getSelected().get('title'),'Unreceived',{$gridName}.getSelectionModel().getSelected().get('docID'),{$gridName}.getSelectionModel().getSelected().get('sendID'));
                }
            }
        });
        ";

        if($config['checkReceiveDuplicate']) {
        	$jsReceive = "
        		Ext.MessageBox.show({
			                    msg: 'กำลังตรวจสอบหนังสือเดิมในทะเบียน...',
			                    progressText: 'กำลังตรวจสอบหนังสือเดิมในทะเบียน...',
			                    width:300,
			                    wait:true,
			                    waitConfig: {interval:200},
			                    icon:'ext-mb-download'
			                });
                        	//Checking for Older Item in RegBook
                       		Ext.Ajax.request({
								url: '/{$config ['appName']}/df-action/check-recv-dup',
								method: 'POST',
								params: {docID: {$gridName}.getSelectionModel().getSelected().get('docID')},
				                success: function(o){
				                	Ext.MessageBox.hide();
				                	var r2 = Ext.decode(o.responseText);
				                	if(r2.duplicate == 1) {
				                		Ext.MessageBox.confirm('พบเรื่องเดิมในทะเบียน', 'ลงรับเลขเดิม [ '+{$gridName}.getSelectionModel().getSelected().get('title')+']?',recvSameRegNo);
				                		saveECMData('rsRegno',r2.regNo);
									} else {
										//Not Duplicate , Then Begin accept
			                            acceptUnreceiveForm.getForm().reset();
			                            acceptUnreceiveWindow.show();
			                            if(gridUnreceivedItem.getSelectionModel().getSelected().get('isCirc') == 1){
			                                Ext.getCmp('acceptAsTypeInternal').setValue(false);
			                                Ext.getCmp('acceptAsTypeExternal').setValue(false);
			                                Ext.getCmp('acceptAsTypeCirc').setValue(true);
			                            } else {
			                                Ext.getCmp('acceptAsTypeInternal').setValue(true);
			                                Ext.getCmp('acceptAsTypeExternal').setValue(false);
			                                Ext.getCmp('acceptAsTypeCirc').setValue(false);
			                            }
			                            //Clear Filter
			                            URIGridFilter.clearFilters();
			                            {$receiveFlowByOrigin}
									}
								}
							});";
        } else {
        	$jsReceive = "
				acceptUnreceiveForm.getForm().reset();
				acceptUnreceiveWindow.show();
				if(gridUnreceivedItem.getSelectionModel().getSelected().get('isCirc') == 1){
					Ext.getCmp('acceptAsTypeInternal').setValue(false);
					Ext.getCmp('acceptAsTypeExternal').setValue(false);
					Ext.getCmp('acceptAsTypeCirc').setValue(true);
				} else {
					Ext.getCmp('acceptAsTypeInternal').setValue(true);
					Ext.getCmp('acceptAsTypeExternal').setValue(false);
					Ext.getCmp('acceptAsTypeCirc').setValue(false);
				}
				//Clear Filter
				URIGridFilter.clearFilters();
				{$receiveFlowByOrigin} ";
        }

		echo "<div id=\"UnreceiveItemUI\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		$unreceivedStore
		$accountTypeStore
		$rankTypeStore

		//accountTypeStore.load();
		rankComboStore.load();
        //acceptDocTypeStore.load();

        unreceivedStore.setDefaultSort('sendID','asc');
	    //accountStore.setDefaultSort('id', 'asc');


	    // Plugin สำหรับ Render Output
		function renderTitle_{$programID}(value, p, record){
			var str = '';
			if(record.data.isCirc == 1 ) {
				//alert('xxxx');
				str = str+ '<img src=\"/{$config['appName']}/images/circ.gif\" />';
			}

			if(record.data.hasAttach == 1 ){
				str = str +'<img src=\"/{$config['appName']}/images/attachment.gif\" />';
			}

			if(str != '') {
				str = str + '&nbsp;'+record.data.title;
				return str;
			}	else {
				return record.data.title;
			}
		}

        function renderUnreceivedSecretColumn(value,p,record) {
            switch(record.data.secret) {
                case 0 :
                    return String.format('{0}','{$lang['common']['secret'][0]}');
                    break;
                case 1 :
                    return String.format('{0}','{$lang['common']['secret'][1]}');
                    break;
                case 2 :
                    return String.format('{0}','{$lang['common']['secret'][2]}');
                    break;
                case 3 :
                    return String.format('{0}','{$lang['common']['secret'][3]}');
                    break;
            }
        }

        function renderUnreceivedSpeedColumn(value,p,record) {
            switch(record.data.speed) {
                case 0 :
                    return String.format('{0}','{$lang['common']['speed'][0]}');
                    break;
                case 1 :
                    return String.format('{0}','{$lang['common']['speed'][1]}');
                    break;
                case 2 :
                    return String.format('{0}','{$lang['common']['speed'][2]}');
                    break;
                case 3 :
                    return String.format('{0}','{$lang['common']['speed'][3]}');
                    break;
            }
        }

		var smc = new Ext.grid.CheckboxSelectionModel({tooltip:'ลงรับหลายเรื่อง'});

	    var cmUnreceivedItem = new Ext.grid.ColumnModel([smc,{
	           header: \"{$lang['df']['sendType']}\",
	           dataIndex: 'sendType',
	           width: 100,
			   renderer: renderSendType
	        },{
	           header: \"เวลาส่ง\",
	           dataIndex: 'sendStamp',
	           width: 100
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
			   header: \"{$lang['df']['receiveExternalRunning']}\",
	           dataIndex: 'receiveExternalRunning',
	           width: 100

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
	           width: 100
		    },{
		       header: \"{$lang['df']['from']}\",
		       dataIndex: 'from',
		       width: 150
		    },{
               header: \"{$lang['df']['from2']}\",
               dataIndex: 'from2',
               width: 150
            },{
		       header: \"{$lang['df']['to']}\",
		       dataIndex: 'to',
		       width: 100
		    }
		]);

	    cmUnreceivedItem.defaultSortable = true;

        Ext.ux.grid.filter.StringFilter.prototype.icon = 'images/find.png';

        var URIGridFilter = new Ext.ux.grid.GridFilters({filters:[
            {type: 'string',  dataIndex: 'title'} ,
            {type: 'string',  dataIndex: 'docNo'}
        ]});

	    var gridUnreceivedItem = new Ext.grid.GridPanel({
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
	        store: unreceivedStore,

	        tbar: new Ext.Toolbar({
				id: 'unreceiveItemToolbar',
				height: 25
			}),
			selMode: smc,
			cm: cmUnreceivedItem,
	        trackMouseOver:false,
	        sm: smc,
	        loadMask: true,
	        renderTo: 'UnreceiveItemUI',
	        view: new Ext.grid.GroupingView({forceFit:true}),
            plugins: URIGridFilter,
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
	            store: unreceivedStore,
                plugins: URIGridFilter,
	            displayInfo: true,
	            displayMsg: 'Displaying Unreceived Item(s) {0} - {1} of {2}',
	            emptyMsg: \"No Unreceived Item\"
	        })
	    });

    	var tbUnreceiveItem = Ext.getCmp('unreceiveItemToolbar');

	 	tbUnreceiveItem.add({
	 		id: 'btnPreview_{$programID}',
            text:'{$lang['df']['preview']}',
            iconCls: 'viewIcon',
            disabled: true,
            handler: function() {
				{$dblClickFunction}
			}
        },{
        	id: 'btnAcceptUnreceive',
            text:'{$lang['df']['accept']}',
            iconCls: 'acceptIcon',
            disabled: true,
            handler: function(e) {
                //Pattern Check Session
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
                        	{$jsReceive}
                        }
                    }
                });
			}
        },{
        	id: 'btnSendBackUnreceive',
            text:'{$lang['df']['sendback']}',
            iconCls: 'sendbackIcon',
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
                            Ext.getCmp('sendbackRefID').setValue(gridUnreceivedItem.getSelectionModel().getSelected().get('sendID'));
                            sendbackWindow.show();
                        }
                    }
                });

			}
        },{
        	id: 'btnAcceptEGIF',
            text:'{$lang['df']['accept']}(TH e-GIF)',
            iconCls: 'acceptIcon',
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
                Ext.MessageBox.confirm('ลงรับ TH e-GIF', 'ลงรับหนังสือเลขที่ [ '+{$gridName}.getSelectionModel().getSelected().get('docNo')+']?',receiveEGIF);
			}
        },{
        	id: 'btnSendBackEGIF',
            text:'{$lang['df']['sendback']}(TH e-GIF)',
            iconCls: 'sendbackIcon',
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
                Ext.MessageBox.confirm('ปฏิเสธการลงรับ TH e-GIF', 'ปฏิเสธการลงรับหนังสือเลขที่ [ '+{$gridName}.getSelectionModel().getSelected().get('docNo')+']?',rejectEGIF);

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
                            Ext.getCmp('btnPreview_{$programID}').disable();
                            Ext.getCmp('btnSendBackUnreceive').disable();
                            Ext.getCmp('btnAcceptUnreceive').disable();
                            gridUnreceivedItem.getSelectionModel().clearSelections();
                            unreceivedStore.reload();
                        }
                    }
                });

			}
        });


        // Custom Grid Renderer
        gridUnreceivedItem.getView().getRowClass= function(record, rowIndex, p, storex) {
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
	    gridUnreceivedItem.render();

	    // trigger the data store load
	    unreceivedStore.load({params:{start:0, limit:25}});
	    gridUnreceivedItem.colModel.renderCellDelegate = renderCell.createDelegate(gridUnreceivedItem.colModel);

	    gridUnreceivedItem.on({
			'rowclick' : function() {
                //alert(gridUnreceivedItem.getSelectionModel().getSelections().get('sendID'));
                //Cookies.set('sendRecordID',gridUnreceivedItem.getSelectionModel().getSelected().get('sendID'));
				var arr = gridUnreceivedItem.getSelectionModel().getSelections();
				var firsttime = true;
				var cmpvalue = 0;
				var counter = arr.length;
				Ext.each(arr, function(r, index){
					if (firsttime) {
						cmpvalue = r.get('sendType');
						firsttime = false;
					}
					if(r.get('sendType') != cmpvalue){
						Ext.MessageBox.show({
							title: 'แจ้งให้ทราบ',
							msg: 'เลือกลงรับเฉพาะหนังสือประเภทเดียวกันเท่านั้น',
							buttons: Ext.MessageBox.OK,
							icon: Ext.MessageBox.ERROR
						});
						gridUnreceivedItem.getSelectionModel().deselectRow(gridUnreceivedItem.getStore().indexOf(r));
						counter--;
					}
					else if ((counter > 1) && ((r.get('hold')==1) || (r.get('abort')==1) || (r.get('sendType')==999)))			// simulate the old logic of disable single-select for some conditions, but this is for multiple-select of accepting letter
					{
						gridUnreceivedItem.getSelectionModel().deselectRow(gridUnreceivedItem.getStore().indexOf(r));
						counter--;
					}
					else
						Cookies.set('sendRecordID' + index, r.get('sendID'));
				});
				if (counter < 1)
				{
					Ext.getCmp('btnSendBackUnreceive').disable();
					Ext.getCmp('btnPreview_{$programID}').disable();
					Ext.getCmp('btnAcceptUnreceive').disable();
				}
				else if (counter == 1)
				{
// 					Ext.getCmp('btnSendBackUnreceive').enable();
					Ext.getCmp('btnPreview_{$programID}').enable();
// 					Ext.getCmp('btnAcceptUnreceive').enable();
	                if(gridUnreceivedItem.getSelectionModel().getSelected().get('hold')==1) {
	                	Ext.getCmp('btnAcceptUnreceive').disable();
	                	Ext.getCmp('btnSendBackUnreceive').disable();
					} else {
						Ext.getCmp('btnAcceptUnreceive').enable();
	                	Ext.getCmp('btnSendBackUnreceive').enable();
					}
	                if(gridUnreceivedItem.getSelectionModel().getSelected().get('abort')==1) {
	                    Ext.getCmp('btnAcceptUnreceive').disable();
	                    Ext.getCmp('btnSendBackUnreceive').disable();
	                }

	                if(gridUnreceivedItem.getSelectionModel().getSelected().get('sendType')==999) {
						Ext.getCmp('btnAcceptEGIF').enable();
						Ext.getCmp('btnSendBackEGIF').enable();
						Ext.getCmp('btnAcceptUnreceive').disable();
						Ext.getCmp('btnSendBackUnreceive').disable();

					} else {
						Ext.getCmp('btnAcceptEGIF').disable();
						Ext.getCmp('btnSendBackEGIF').disable();
					}
				}
				else			// if none or more-than-one selected, disable the SendBack button
				{
					Ext.getCmp('btnSendBackUnreceive').disable();
					Ext.getCmp('btnPreview_{$programID}').disable();
					Ext.getCmp('btnAcceptUnreceive').enable();
				}
				Cookies.set('sendRecordCount', counter);
			},
			scope: this
		});

	    function toggleDetails(btn, pressed){
	        var view = gridUnreceivedItem.getView();
	        view.showPreview = pressed;
	        view.refresh();
	    }

	    function receiveEGIF(btn) {
	    	if(btn=='yes') {
	    		Ext.MessageBox.show({
                    msg: 'ลงรับ TH E-GIF',
                    progressText: 'Processing...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
	    		Ext.Ajax.request({
					url: '/{$config ['appName']}/df-action/receive-egif',
					method: 'POST',
					success: function(o){
						Ext.MessageBox.hide();
						var r = Ext.decode(o.responseText);
						Ext.MessageBox.show({
							title: 'ลงรับภายนอก(TH e-GIF)เรียบร้อย',
							msg: 'ลงรับภายนอกเลขที่: '+r.regNo,
							buttons: Ext.MessageBox.OK,
							icon: Ext.MessageBox.INFO
						});
						unreceivedStore.reload();
					},
					params: {
                        docID: {$gridName}.getSelectionModel().getSelected().get('docID'),
                        sendID: {$gridName}.getSelectionModel().getSelected().get('sendID')
					}
                });
			}
		}

		function rejectEGIF(btn) {
			if(btn=='yes') {
				Ext.Ajax.request({
					url: '/{$config ['appName']}/df-action/reject-egif',
					method: 'POST',
					success: function(o){
						Ext.MessageBox.hide();
						var r = Ext.decode(o.responseText);
						Ext.MessageBox.show({
							title: 'ปฏิเสธการลงรับ',
							msg: 'แจ้งกลับหน่วยงานต้นทางแล้ว',
							buttons: Ext.MessageBox.OK,
							icon: Ext.MessageBox.INFO
						});
						unreceivedStore.reload();
					},
					params: {
                        docID: {$gridName}.getSelectionModel().getSelected().get('docID'),
                        sendID: {$gridName}.getSelectionModel().getSelected().get('sendID')
					}
                });
			}
		}

		function recvSameRegNo(btn) {
            if(btn == 'yes') {
            	Ext.MessageBox.show({
		            msg: 'ลงรับเลขเดิม',
		            progressText: 'Processing...',
		            width:300,
		            wait:true,
		            waitConfig: {interval:200},
		            icon:'ext-mb-download'
		        });

                Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/recv-same-regno',
                        method: 'POST',
                        success: function(o){
                                  Ext.MessageBox.hide();
                                  var r = Ext.decode(o.responseText);
                                  Ext.MessageBox.show({
                                    title: 'ลงรับเลขเดิมเรียบร้อย',
                                    msg: 'ลงรับเลขเดิมเลขที่: '+getECMData('rsRegno'),
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  unreceivedStore.reload();
                            },
                        //failure: deletePolicyFailed,
                        params: {
                        	docID: {$gridName}.getSelectionModel().getSelected().get('docID'),
                        	sendID: {$gridName}.getSelectionModel().getSelected().get('sendID'),
                        	regNo:  getECMData('rsRegno')
						}
                });
            } else {
            	acceptUnreceiveForm.getForm().reset();
		 		acceptUnreceiveWindow.show();
				if(gridUnreceivedItem.getSelectionModel().getSelected().get('isCirc') == 1){
					Ext.getCmp('acceptAsTypeInternal').setValue(false);
					Ext.getCmp('acceptAsTypeExternal').setValue(false);
					Ext.getCmp('acceptAsTypeCirc').setValue(true);
				} else {
					Ext.getCmp('acceptAsTypeInternal').setValue(true);
					Ext.getCmp('acceptAsTypeExternal').setValue(false);
					Ext.getCmp('acceptAsTypeCirc').setValue(false);
				}
				//Clear Filter
				URIGridFilter.clearFilters();
				{$receiveFlowByOrigin}
			}
        }

		gridUnreceivedItem.on('rowdblclick',function() {
			//Cookies.set('docRefID',gridUnreceivedItem.getSelectionModel().getSelected().get('sendID'));
			{$dblClickFunction}
			//viewDocumentCrossModule('viewDOC_'+gridUnreceivedItem.getSelectionModel().getSelected().get('sendID'),gridUnreceivedItem.getSelectionModel().getSelected().get('title'),'RI',gridUnreceivedItem.getSelectionModel().getSelected().get('docID'));
	    	//alert('dbl click');
		},this);



		</script>";
	}
}

