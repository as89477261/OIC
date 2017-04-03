<?php
/**
 * โปรแกรมทำการค้นหาทะเบียนรวม
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 */
class GlobalSearchController extends ECMController {
	/**
	 * action /get-ui แสดงหน้าจแโปรแกรมค้นหาทะเบียนรวม
	 *
	 */
	public function getAdvanceUiAction() {
		global $lang;
		global $config;
		
		checkSessionPortlet();
		
		echo "<div id=\"divAdvanceSearch\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
				var bgstyle = {
					\"background-image\": \"url(images/icons/icon_view.png)\",
					\"background-repeat\": \"no-repeat\",
					\"background-position\": \"right\"
				};
				
				
				var tempSendExternalStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, ReceiverRecordDataDef)});
				
				var frmSearch = new Ext.FormPanel({
		        //labelWidth: 1, // label settings here cascade unless overridden
		        //title: '{$lang ['action'] ['globalSearch']}',
		        defaultType: 'textfield',
		        bodyStyle: 'padding:5px 5px 0',
		        buttonAlign: 'left',
		        frame: false,
		        border: 0,
		        //baseCls: 'x-plain',
		        baseCls: 'x-plain',
		
		        items: [new Ext.grid.GridPanel({
                    id: 'gridSearchKeyword',
                    tbar: ['Keyword: ',
                    	{
							id: 'newSearchKeyword',
							xtype: 'textfield',
							width: '150'
                    	},new Ext.form.ComboBox({
                    		width: 100,
					        tpl: '<tpl for=\".\"><div class=\"x-combo-list-item\">{name}</div></tpl>',
					        store: new Ext.data.SimpleStore({
                    			fields: ['name', 'value'],
                    			id: 'value',
                    			data :  [
									['and', 0],
									['or', 1],
									['not', 2]
                    			]
                			}),
					        valeField: 'value',
					        displayField:'name',
					        typeAhead: true,
					        forceSelection: true,
					        mode: 'local',
					        triggerAction: 'all',
					        selectOnFocus:true
					    }),
                    	{
							id: 'addSearchKeyword',
							iconCls: 'addIcon',
							text: 'Add'
                    	},'-',{
				            text:'Type',
				            ///iconCls: 'bmenu',  // <-- icon
				            menu: new Ext.menu.Menu({
						        id: 'mnuSearchType',
						        items: [
						            {
						                text: 'Index(Folder)',
						                checked: true
						            },
						            {
						                text: 'Document',
						                checked: true
						            },
						            {
						                text: 'Shortcut',
						                checked: true
						            }
						       	]
							})
				        },{
				            text:'Source',
				            ///iconCls: 'bmenu',  // <-- icon
				            menu: new Ext.menu.Menu({
						        id: 'mnuSearchIndex',
						        items: [
						            {
						                text: 'Name',
						                checked: true
						            },
						            {
						                text: 'Detail',
						                checked: true
						            },
						            {
						                text: 'Keyword',
						                checked: true
						            },
						            {
						                text: 'Page',
						                checked: true
						            },
						            {
						                text: 'Source',
						                checked: true
						            },
						            {
						                text: 'Location',
						                checked: true
						            },
						            {
						                text: 'Date',
						                checked: true
						            }
						       	]
							})
				        }, {
			                text: 'Sort By',
			                menu: {        // <-- submenu by nested config object
			                    items: [
			                        {
			                            text: 'Index',
			                            checked: true,
			                            group: 'sortBy'
			                        }, {
			                            text: 'Date',
			                            checked: false,
			                            group: 'sortBy'
			                        }
			                    ]
			                }
			            }, {
			                text: 'Sort',
			                menu: {        // <-- submenu by nested config object
			                    items: [
			                        {
			                            text: 'Ascending',
			                            checked: true,
			                            group: 'sortType'
			                        }, {
			                            text: 'Descending',
			                            checked: false,
			                            group: 'sortType'
			                        }
			                    ]
			                }
			            }
					],
					bbar: [
						{
							id: 'saveKeyword',
							text: 'Save Keyword',
							iconCls: 'saveIcon'
						},'-',{
							id: 'loadKeyword',
							xtype: 'textfield'
						},
						{
							iconCls: 'addIcon',
							text: 'Load Keyword',
							id: 'btnLoadKeyword'
						},
						{
							iconCls: 'deleteIcon',
							text: 'Delete Keyword',
							id: 'btnDeleteKeyword'
						}
					],
                    store: tempSendExternalStore,
                    ddGroup : 'TreeDD', 
                    autoExpandMax: true,
                    columns: [
                    	{id: 'id', header: 'And/Or', width: 40, sortable: false, dataIndex: 'name'},
                    	{header: 'Keyword', width: 120, sortable: false, dataIndex: 'description'}
                    ],
                    viewConfig: {
                        forceFit: true
                    },
                    sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
                    width: 600,
                    height: 200,
                    frame: false,
                    iconCls:'icon-grid'
                }),{
					fieldLabel: 'ขอบเขตการค้นหา',
					id: 'searchLockLocation',
					readOnly: true,
					editable: false,
					width: 200
                },new Ext.ux.DateTimeField ({
	                fieldLabel: 'จากวันที่',    
	                id: 'searchDocDateFrom',
	                name: 'searchDocDateFrom',
	                allowBlank: true,  
	                readOnly: true,
	                width: 100
            	}),new Ext.ux.DateTimeField ({
	                fieldLabel: 'ถึงวันที่',    
	                id: 'searchDocDateTo',
	                name: 'searchDocDateTo',
	                allowBlank: true,  
	                readOnly: true,
	                width: 100
            	}),new Ext.ux.DateTimeField ({
	                fieldLabel: 'ลงวันที่',    
	                id: 'searchDocDate',
	                name: 'searchDocDate',
	                allowBlank: true,  
	                readOnly: true,
	                width: 100
            	})
                
                ],
		        buttons: [{
		        	id: 'btnSearch',
		            text: '{$lang ['action'] ['globalSearch']}'
		        },{
		        	id: 'btnClear',
		            text: '{$lang ['common'] ['clear']}'
		        }]
		    });
		    
		 	frmSearch.render(document.getElementById('divAdvanceSearch'));
		    //Ext.get('txtSearch').applyStyles(bgstyle);
		    
		    Ext.getCmp('btnSearch').on('click',function() {
		    	var tabMain = Ext.getCmp('tpAdmin');
		    	
		    	tabMain.add({
	            	id: 'tabSearch_'+Ext.getCmp('txtSearch').getValue(),
					title: '{$lang ['dms'] ['searchResult']}'+'\"'+Ext.getCmp('txtSearch').getValue()+'\"',
					iconCls: 'searchIcon',
					closable:true,
					autoLoad: {
						url: '/{$config ['appName']}/portlet/get-portlet-content',
						params: {
								portletClass: 'DMSPortlet',
								portletMethod: 'portletContent',
								qMode: 'normal'
								//,keySearch: Url.encode(Ext.getCmp('txtSearch').getValue())
						},
						method: 'POST',
						scripts: true
					}
				}).show();
		    },this);
		    
		    Ext.getCmp('btnClear').on('click',function() {
		    	frmSearch.getForm().reset();
			});
		</script>";
	}
	
	/**
	 * action /get-ui แสดงหน้าจแโปรแกรมค้นหาทะเบียนรวม
	 *
	 */
	public function getUiAction() {
		global $lang;
		global $config;
		
		checkSessionPortlet();
		
		echo "<div id=\"divSearch\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
				var bgstyle = {
					\"background-image\": \"url(images/icons/icon_view.png)\",
					\"background-repeat\": \"no-repeat\",
					\"background-position\": \"right\"
				};
				
				var frmSearch = new Ext.FormPanel({
		        //labelWidth: 1, // label settings here cascade unless overridden
		        title: '{$lang ['action'] ['globalSearch']}',
		        defaultType: 'textfield',
		        bodyStyle: 'padding:5px 5px 0',
		        buttonAlign: 'left',
		
		        items: [{
		        	fieldLabel: 'ค้นหา',
		            allowBlank:false,
		            id: 'txtSearch',
		            name: 'search',
		            width: 300,
		            //anchor:'100%',
		            labelSeparator: ''
		        }],
		
		        buttons: [{
		        	id: 'btnSearch',
		            text: '{$lang ['action'] ['globalSearch']}'
		        },{
		        	id: 'btnClear',
		            text: '{$lang ['common'] ['clear']}'
		        }]
		    });
		    
		 	frmSearch.render(document.getElementById('divSearch'));
		    Ext.get('txtSearch').applyStyles(bgstyle);
		    
		    Ext.getCmp('btnSearch').on('click',function() {
		    	var tabMain = Ext.getCmp('tpAdmin');
		    	
		    	tabMain.add({
	            	id: 'tabSearch_'+Ext.getCmp('txtSearch').getValue(),
					title: '{$lang ['dms'] ['searchResult']}'+'\"'+Ext.getCmp('txtSearch').getValue()+'\"',
					iconCls: 'searchIcon',
					closable:true,
					autoLoad: {
						url: '/{$config ['appName']}/portlet/get-portlet-content',
						params: {
								portletClass: 'DMSPortlet',
								portletMethod: 'portletContent',
								qMode: 'normal',
								document: 'true',
								name: 'true',
								description: 'true',
								keyword: 'true',
								keySearch: Url.encode(Ext.getCmp('txtSearch').getValue())
						},
						method: 'POST',
						scripts: true
					}
				}).show();
		    },this);
		    
		    Ext.getCmp('btnClear').on('click',function() {
		    	frmSearch.getForm().reset();
		    	//Ext.getCmp('txtSearch').setValue('');
			});
		</script>";
	}
}
