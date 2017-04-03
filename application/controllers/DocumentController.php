<?php

/**
 * โปรแกรมจัดการระบเอกสาร
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Document
 */

class DocumentController extends ECMController {
	
	/**
	 * action /create สร้างเอกสาร
	 *
	 */
	
	public function createAction() {
		global $config;
		global $sequence;
		global $store;
		global $sessionMgr;
		
		checkSessionPortlet ();
		
		$formPath = $config ['formPath'];
		$formID = $_POST ['newDocFormHiddenID'];
		$parentID = $_POST ['parentID'];
		$parentMode = $_POST ['parentMode'];
		$formDesign = "{$formPath}designedForm_{$formID}.html";
		if (file_exists ( $formDesign )) {
			$formContent = file_get_contents ( $formDesign );
		} else {
			$formContent = "<h1><font color=\"RED\">No Form Designed Template</font></h1>";
		}
		
		//$formHTML = str_replace("\r\n","",$formHTML);
		

		$scanParam = 1;
		
		if (! $sequence->isExists ( 'docTempID' )) {
			$sequence->create ( 'docTempID' );
		}
		
		$docTempID = $sequence->get ( 'docTempID' );
		$attachTempName = "attachTempStore_{$docTempID}";
		$attachTempStore = $store->getAttachTempStore ( $docTempID, $attachTempName );
		$formName = "instance_{$docTempID}";
		$formHTML = "<form id=\"{$formName}\" name=\"{$formName}\">$formContent</form>";
		$formHTML = str_replace ( "\r\n", "", $formHTML );
		
		echo "<table width=\"100%\">
			<tr>
				<td width=\"75%\">
					<div id=\"documentCreateUIDiv_{$docTempID}\" display=\"inline\"></div>
				</td>
				<td>
					<div id=\"documentThumbnailUIDiv_{$docTempID}\" display=\"inline\"></div>
				</td>
			</tr>
		</table>";
		
		echo "<script>
		$attachTempStore
		$attachTempName.load({params:{start:0, limit:8}});
		
		var frmUpload_{$docTempID} = new Ext.form.FormPanel({
	        //autoCreate: {
	        //    tag: 'form',
	        //    id : \"frmUpload\",
	        //    method : 'POST',
	        //   // enctype : \"multipart/form-data\"
	        //},
	        id : \"frmUpload_{$docTempID}\",
	        method : 'POST',
	        //enctype : \"multipart/form-data\",
			items: [new Ext.form.TextField({
				//inputType: 'TextField',
				inputType: 'Hidden',
				value: {$docTempID},
				hideLabel: true,
				name: 'docTempID'
			}),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    })]
	    });
	    
	    var wndAttach_{$docTempID} = new Ext.Window({
	    	//baseCls: 'x-plain',
			id: 'wndAttach_{$docTempID}',
			title: 'Attach File Temp: {$docTempID}',
			width: 265,
			height: 210,
			minWidth: 265,
			minHeight: 210,
			resizable: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmUpload_{$docTempID},
			closable: false,
			modal: true,
			buttons: [{
				text: 'Add Attachment',
				handler: function() {
	    			wndAttach_{$docTempID}.hide();
					var myEl = frmUpload_{$docTempID}.getEl().dom;
					//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
     				frmUpload_{$docTempID}.getForm().getEl().dom.setAttribute('enctype','multipart/form-data');
     				//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-attach-temp',
		    			method: 'POST',
		    			enctype: 'multipart/form-data',
		    			success: function() {
		    				$attachTempName.reload();
							frmUpload_{$docTempID}.getForm().reset();
						},
		    			//failure: documentAddFailed,
		    			form: frmUpload_{$docTempID}.getForm().getEl()
		    		});
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndAttach_{$docTempID}.hide();
				}
			}]
		});
	    /*
	    var wndAttach_{$docTempID} = new Ext.Window({
			id: 'wndAttach_{$docTempID}',
			title: 'Attach YEEPPY File $docTempID',
			width: 300,
			height: 210,
			minWidth: 300,
			minHeight: 210,
			resizable: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmUpload_{$docTempID},
			closable: false,
			modal: true,
			buttons: [{
				text: 'Add Attachment',
				handler: function() {					
	    			wndAttach_{$docTempID}.hide();
        			
					var myEl = frmUpload_{$docTempID}.getEl().dom;
					//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
     				frmUpload_{$docTempID}.getForm().getEl().dom.setAttribute('enctype','multipart/form-data');
     				//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-attach-temp',
		    			method: 'POST',
		    			enctype: 'multipart/form-data',
		    			success: function() {
		    				$attachTempName.reload();
						},
		    			//failure: documentAddFailed,
		    			form: frmUpload_{$docTempID}.getForm().getEl()
		    		});
			     
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndAttach_{$docTempID}.hide();
				}
			}]
		});
		*/
		var wndScan_{$docTempID} = new Ext.Window({
			id: 'wndScan_{$docTempID}',
			title: 'Scan $docTempID',
			width: 195,
			height: 155,
			minWidth: 195,
			minHeight: 155,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			html: '<OBJECT classid=\"clsid:72A51A5B-C447-47A2-92CC-21F1F3C19246\" codebase=\"TwnProj1.ocx#version=1,0,0,0\" border=0 width=167 height=73 align=center hspace=0 vspace=0>	<PARAM NAME=\"Filename\"	VALUE=\"C:/Program Files/CDMSPlugins/TwainFTP.exe\">	<PARAM NAME=\"Parameter\" VALUE=\"{$config['ftp']['host']}|{$config['ftp']['port']}|{$config['ftp']['username']}|{$config['ftp']['password']}|{$_SESSION['accID']}|{$scanParam}\">Plug-in is not Install before use.</OBJECT>',
			//items: frmUpload_{$docTempID},
			closable: false,
			modal: true,
			buttons: [{
				id: 'btnSaveScan_{$docTempID}',
				text: 'Commit',
				handler: function() {
					
	    			wndScan_{$docTempID}.hide();
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-scan-temp',
		    			method: 'POST',
		    			enctype: 'multipart/form-data',
		    			success: function() {
		    				Ext.getCmp('btnSaveScan_{$docTempID}').disable();
						},
		    			//failure: documentAddFailed,
		    			//form: frmUpload_{$docTempID}.getForm().getEl()
		    			params: {
		    				docTempID: {$docTempID}
						}
		    		});
		    		
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndScan_{$docTempID}.hide();
				}
			}]
		});
    
    
		var documentCreator_{$docTempID} = new Ext.Panel({
		   	tbar: new Ext.Toolbar({
				id: 'documentCreateToolbar_{$docTempID}',
				height: 25				
			}),
			autoScroll : true,
		   	margins: '5 0 0 5',
		   	cmargins: '5 5 0 5',
		   	minSize: 100,
		   	maxSize: 300,
		   	//layout: 'fit',
		   	height: Ext.getCmp('tpAdmin').getInnerHeight(),
		   	renderTo: 'documentCreateUIDiv_{$docTempID}',
		   	html: '{$formHTML}'
		});
		
		var documentAttach_{$docTempID} = new Ext.Panel({
		   	tbar: new Ext.Toolbar({
				id: 'attachmentToolbar_{$docTempID}',
				height: 25				
			}),
			autoScroll : true,
			cls: 'images-view',
			items: new Ext.DataView({
				
				singleSelect: true,
	            store: $attachTempName,
	            tpl: new Ext.XTemplate(
					'<tpl for=\".\">',
			            '<center><div class=\"thumb-wrap\" id=\"{name}\">',
					    '<div class=\"thumb\"><img src=\"{url}\" title=\"{name}\"></div>',
					    '<span class=\"x-editable\">{shortName}</span></div></center>',
			        '</tpl>',
			        '<div class=\"x-clear\"></div>'
				),
	            autoHeight:true,
	            multiSelect: true,
	            overClass:'x-view-over',
	            itemSelector:'div.thumb-wrap',
	            emptyText: 'No attachments',
	            listeners: {
                    'click' : function (thisObj,idx,node,evt) {
                        alert('Doc ID : '+thisObj.getRecord(node).get('docid') +' Page ID : '+thisObj.getRecord(node).get('pageid'));        
                    },
					'dblclick' : function(thisObj,idx,node,evt) {
						popupTempAttachviewer(thisObj.getRecord(node).get('docid'),thisObj.getRecord(node).get('pageid'));
					}
				},
				
	
	            //plugins: [
	            //    new Ext.DataView.DragSelector(),
	            //    new Ext.DataView.LabelEditor({dataIndex: 'name'})
	            //],
	
	            prepareData: function(data){
	                data.shortName = Ext.util.Format.ellipsis(data.name, 16);
	                data.sizeString = Ext.util.Format.fileSize(data.size);
	                //data.dateString = data.lastmod.format(\"m/d/Y g:i a\");
	                data.dateString = \"\";
	                return data;
	            }
	        }),
			bbar: new Ext.PagingToolbar({
				id: 'attachmentToolbar2_{$docTempID}',
				height: 25,	
	            pageSize: 8,
	            store: $attachTempName,
	            displayInfo: false
	        }),
		    margins: '5 0 0 5',
		    cmargins: '5 5 0 5',
		    minSize: 100,
		    maxSize: 300,
		   	layout: 'fit',
		   	height: Ext.getCmp('tpAdmin').getInnerHeight(),
		   	renderTo: 'documentThumbnailUIDiv_{$docTempID}'
		});
		
		var attachmentToolbar_{$docTempID} = Ext.getCmp('attachmentToolbar_{$docTempID}');
		
		attachmentToolbar_{$docTempID}.add({
            text:'Attach',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
            	wndAttach_{$docTempID}.show();
            	//Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Scan',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
				wndScan_{$docTempID}.show();
            	//Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Delete',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//E/xt.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Refresh',
            iconCls: 'bmenu',
            handler: function(){
            	//accountStore.load({params:{start:0, limit:8}});
            	$attachTempName.reload();
			}
        });
        
        var documentCreateToolbar_{$docTempID} = Ext.getCmp('documentCreateToolbar_{$docTempID}');
        documentCreateToolbar_{$docTempID}.add(
		{
            text:'Save',
            id: 'btnSaveInstance_{$docTempID}',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
            	Ext.Ajax.request({
		    		url: '/{$config ['appName']}/document/create-document',
		    		method: 'POST',
		    		//enctype: 'multipart/form-data',
		    		success: function() {
		    				Ext.getCmp('btnSaveInstance_{$docTempID}').disable();
		    				closeCurrentTab();
		    				DMSTree.getNodeById(Cookies.get('cdid')).reload();
					},
		    		//success: function() {
		    		//	//$attachTempName.reload();
					//},
		    		//failure: documentAddFailed,
		    		form: Ext.get('instance_{$docTempID}'),
		    		params: { 
		    			formID: {$formID}
		    			,tempID: {$docTempID}
		    			,parentID: '{$parentID}'
						,parentType: '{$parentType}'
						,parentMode: '{$parentMode}'
					}
		    	});
			}
        },{
            text:'Cancel',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
				//wndScan_{$docTempID}.show();
            	//Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        }
        );
		documentCreator_{$docTempID}.body = '$formHTML';
		 
		documentCreator_{$docTempID}.render();
		documentAttach_{$docTempID}.render();
		</script>";
	}
	
	/**
	 * action /load-revision ทำการ Load revision ของเอกสาร
	 *
	 */
	public function loadRevisionAction() {
		global $config;
		global $lang;
		
		$docID = $_POST ['id'];
		$docRev = $_POST ['rev'];
		
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		if ($docRev == 0) {
			$docValueFinder = new DocValueEntity ( );
			$docValues = $docValueFinder->Find ( "f_doc_id = '{$docID}'" );
		
		} else {
			$docValueFinder = new DocHistValueEntity ( );
			$docValues = $docValueFinder->Find ( "f_doc_id = '{$docID}' and f_revision = '{$docRev}'" );
		
		}
		
		$docVals = Array ();
		foreach ( $docValues as $docValue ) {
			$struct = new FormStructureEntity ( );
			$struct->Load ( "f_form_id = '{$docMain->f_form_id}' and f_struct_id = '{$docValue->f_struct_id}'" );
			//$struct->f_form_id
			//$struct->f_struct_id
			//$struct->f_struct_name
			$docVals [] = Array ('struct' => $struct->f_struct_name, 'val' => UTFEncode ( $docValue->f_value ) );
		}
		//echo json_encode($docVals);
		

		$count = count ( $docVals );
		$data = json_encode ( $docVals );
		$cb = isset ( $_GET ['callback'] ) ? $_GET ['callback'] : '';
		print $cb . '({"total":"' . $count . '","results":' . $data . '})';
	}
	
	/**
	 * action /view ทำการแสดงเอกสาร (น่าจะไม่มีการใช้งานแล้ว)
	 *
	 */
	public function viewAction() {
		
		global $config;
		//global $sequence;
		global $store;
		
		checkSessionPortlet ();
		
		$docID = $_POST ['docID'];
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		$formPath = $config ['formPath'];
		$formID = $docMain->f_form_id;
		$formDesign = "{$formPath}designedForm_{$formID}.html";
		$formContent = file_get_contents ( $formDesign );
		$formName = "viewinstance_{$docID}";
		
		$docData = new DocValueEntity ( );
		$docDataArray = $docData->Find ( "f_doc_id = '{$docID}' " );
		$fillFormJS = '';
		foreach ( $docDataArray as $docValue ) {
			$formStruct = new FormStructureEntity ( );
			$fldValue = addslashes ( $docValue->f_value );
			$formStruct->Load ( "f_form_id = '{$docMain->f_form_id}' and f_struct_id = '{$docValue->f_struct_id}'" );
			//$fillFormJS .= "Ext.get(\"{$formStruct->f_struct_name}\").set(\"value\",\"{$fldValue}\");";
			$fillFormJS .= "document.forms.{$formName}.{$formStruct->f_struct_name}.value = \"{$fldValue}\";";
			unset ( $formStruct );
		}
		
		//$formHTML = str_replace("\r\n","",$formHTML);

		$scanParam = 1;
		
		$attachName = "attachment{$docID}Store";
		$attachStore = $store->getAttachStore ( $docID, $attachName );
		//$receiverGrid = "<table><tr><td><div id=\"divSendToList{$docID}\"></div></td><td><div id=\"divSendCCList{$docID}\"></div></td></tr></table>";
		//$formHTML = "$receiverGrid<form id=\"{$formName}\" name=\"{$formName}\">$formContent</form>";
		$formHTML = "<form id=\"{$formName}\" name=\"{$formName}\">$formContent</form>";
		$formHTML = str_replace ( "\r\n", "", $formHTML );
		
		echo "<table width=\"100%\">
			<tr>
				<td width=\"75%\">
					<div id=\"documentViewUIDiv_{$docID}\" display=\"inline\"></div>
				</td>
				<td>
					<div id=\"documentViewThumbnailUIDiv_{$docID}\" display=\"inline\"></div>
				</td>
			</tr>
		</table>";
		
		echo "<script>
		$attachStore
		$attachName.load({params:{start:0, limit:8}});
		
		var frmUploadMain_{$docID} = new Ext.form.FormPanel({
	        //autoCreate: {
	        //    tag: 'form',
	        //    id : \"frmUpload\",
	        //    method : 'POST',
	        //   // enctype : \"multipart/form-data\"
	        //},
	        baseCls: 'x-plain',
	        id : \"frmUploadMain_{$docID}\",
	        method : 'POST',
	        //enctype : \"multipart/form-data\",
			items: [new Ext.form.TextField({
				//inputType: 'TextField',
				//inputType: 'Hidden',
				value: {$docID},
				hideLabel: true,
				name: 'docID'
			}),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    })]
	    });
	    
	    var wndAttachMain_{$docID} = new Ext.Window({
	    	baseCls: 'x-plain',
			id: 'wndAttachMain_{$docID}',
			title: 'Attach File {$docID}',
			width: 265,
			height: 210,
			minWidth: 265,
			minHeight: 210,
			resizable: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmUploadMain_{$docID},
			closable: false,
			modal: true,
			buttons: [{
				text: 'Add Attachment',
				handler: function() {
	    			wndAttachMain_{$docID}.hide();
					var myEl = frmUploadMain_{$docID}.getEl().dom;
					//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
     				frmUploadMain_{$docID}.getForm().getEl().dom.setAttribute('enctype','multipart/form-data');
     				//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-attach-temp',
		    			method: 'POST',
		    			enctype: 'multipart/form-data',
		    			success: function() {
		    				$attachName.reload();
						},
		    			//failure: documentAddFailed,
		    			form: frmUploadMain_{$docID}.getForm().getEl()
		    		});
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndAttachMain_{$docID}.hide();
				}
			}]
		});
		
		var wndScanMain_{$docID} = new Ext.Window({
			id: 'wndScanMain_{$docID}',
			title: 'Scan {$docID}',
			width: 195,
			height: 155,
			minWidth: 195,
			minHeight: 155,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			html: '<OBJECT classid=\"clsid:72A51A5B-C447-47A2-92CC-21F1F3C19246\" codebase=\"TwnProj1.ocx#version=1,0,0,0\" border=0 width=167 height=73 align=center hspace=0 vspace=0>	<PARAM NAME=\"Filename\"	VALUE=\"C:/Program Files/CDMSPlugins/TwainFTP.exe\">	<PARAM NAME=\"Parameter\" VALUE=\"{$config['ftp']['host']}|{$config['ftp']['port']}|{$config['ftp']['username']}|{$config['ftp']['password']}|{$_SESSION['accID']}|{$scanParam}\">Plug-in is not Install before use.</OBJECT>',
			//items: frmUpload_{$docID},
			closable: false,
			modal: true,
			buttons: [{
				id: 'btnSaveScanMain_{$docID}',
				text: 'Commit',
				handler: function() {
					
	    			wndScanMain_{$docID}.hide();
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-scan-temp',
		    			method: 'POST',
		    			enctype: 'multipart/form-data',
		    			success: function() {
		    				Ext.getCmp('btnSaveScanMain_{$docID}').disable();
						},
		    			//failure: documentAddFailed,
		    			//form: frmUpload_{$docID}.getForm().getEl()
		    			params: {
		    				docTempID: {$docID}
						}
		    		});
		    		
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndScanMain_{$docID}.hide();
				}
			}]
		});
		
		
		var ReceiverRecord{$docID} = Ext.data.Record.create([
		    {name: 'name'},
		    {name: 'description'},
		    {name: 'type'},
		    {name: 'value'}
		]);
		
		var Property{$docID} = Ext.data.Record.create([
					{name: 'name'},
					{name: 'description'},
					{name: 'type'},
					{name: 'value'}
		]);
		
		var tempStore{$docID} = new Ext.data.Store({reader: new Ext.data.JsonReader({}, ReceiverRecord{$docID})});
		var tempStore2{$docID} = new Ext.data.Store({reader: new Ext.data.JsonReader({}, ReceiverRecord{$docID})});
		
		var frmSendList_{$docID} = new Ext.form.FormPanel({
	        id : \"frmSendList_{$docID}\",
	        method : 'POST',
			items: [new Ext.grid.GridPanel({
				id: 'gridTo{$docID}',
				store: tempStore{$docID},
				enableDragDrop: true,
				enableDrop: true,
				enableDrag: true,
				ddGroup : 'TreeDD', 
				autoExpandMax: true,
				columns: [
				{id: 'id', header: 'Name', width: 120, sortable: false, dataIndex: 'name'},
				{header: 'Description', width: 0, sortable: false, dataIndex: 'description'},
				{header: 'Level', width: 0, sortable: false, dataIndex: 'level'},
				{header: 'Status', width: 0, sortable: false, dataIndex: 'status'}
				],
				viewConfig: {
					forceFit: true
				},
				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
				width: 500,
				height: 200,
				frame: false,
				iconCls:'icon-grid'
			})]
		});
		
		//form for save contact list
		var frmSaveContactList_{$docID} = new Ext.form.FormPanel({
	        id: \"frmSaveContactList_{$docID}\",
			baseCls: 'x-plain',
			labelWidth: 75,
			defaultType: 'textfield',
	        method: 'POST',
			items: [{
				fieldLabel: 'List name',
				name: 'contactListName',
				anchor:'100%'  // anchor width by percentage
			},{
				fieldLabel: 'Public',
				name: 'publicContactList',
				xtype: 'checkbox'
			}]
		});
		
		//form for load/delete contact list data
		var frmLoadContactList_{$docID} = new Ext.form.FormPanel({
	        id: \"frmLoadContactList_{$docID}\",
			baseCls: 'x-plain',
			labelWidth: 75,
			defaultType: 'textfield',
	        method: 'POST',
			items: [{
				fieldLabel: 'List name',
				name: 'contactListName',
				anchor:'100%'  // anchor width by percentage
			}]
		});
		
		//dialog for save contact list
		var wndSaveContactList_{$docID} = new Ext.Window({
			id: 'wndSaveContactList_{$docID}',
			title: 'Save Contact List{$docID}',
			width: 250,
			height: 125,
			minWidth: 250,
			minHeight: 125,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmSaveContactList_{$docID},
			closable: false,
			modal: true,
			buttons: [{
				text: 'Save',
				handler: function() {
	    			
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndSaveContactList_{$docID}.hide();
				}
			}]
		});
		
		//dialog for load/delete contact list
		var wndLoadContactList_{$docID} = new Ext.Window({
			id: 'wndLoadContactList_{$docID}',
			title: 'Load Contact List{$docID}',
			width: 275,
			height: 100,
			minWidth: 275,
			minHeight: 100,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmLoadContactList_{$docID},
			closable: false,
			modal: true,
			buttons: [{
				text: 'Load',
				handler: function() {
	    			
	    		}
			},{
				text: 'Delete',
				handler: function() {
	    			
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndLoadContactList_{$docID}.hide();
				}
			}]
		});
		
		
		var wndSendList_{$docID} = new Ext.Window({
			id: 'wndSendList_{$docID}',
			title: 'Send Document {$docID}',
			width: 532,
			height: 282,
			minWidth: 532,
			minHeight: 282,
			resizable: false,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			html: '<table><tr><td><div id=\"divSendToList{$docID}\"></div></td><td><div id=\"divSendCCList{$docID}\"></div></td></tr></table>',
			closable: false,
			modal: true,
			buttons: [{
				text: 'Confirm',
				handler: function() {
	    			
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndSendList_{$docID}.hide();
				}
			},{
				text: 'Save Contact List',
				handler: function() {
					wndSaveContactList_{$docID}.show();
				}
			},{
				text: 'Load Contact List',
				handler: function() {
					wndLoadContactList_{$docID}.show();
				}
			}]
		});
		
		wndSendList_{$docID}.show();
		wndSendList_{$docID}.hide();
		
    
    
		var documentViewer_{$docID} = new Ext.Panel({
		   	tbar: new Ext.Toolbar({
				id: 'documentViewerToolbar_{$docID}',
				height: 25				
			}),
			autoScroll : true,
		   	margins: '5 0 0 5',
		   	cmargins: '5 5 0 5',
		   	minSize: 100,
		   	maxSize: 300,
		   	//layout: 'fit',
		   	height: Ext.getCmp('tpAdmin').getInnerHeight(),
		   	renderTo: 'documentViewUIDiv_{$docID}',
		   	html: '{$formHTML}'
		});
		
		var documentAttachMain_{$docID} = new Ext.Panel({
		   	tbar: new Ext.Toolbar({
				id: 'attachmentToolbarMain_{$docID}',
				height: 25				
			}),
			autoScroll : true,
			cls: 'images-view',
			items: new Ext.DataView({
				//singleSelect: true,
	            store: $attachName,
	            tpl: new Ext.XTemplate(
					'<tpl for=\".\">',
			            '<center><div class=\"thumb-wrap\" id=\"{name}\">',
					    '<div class=\"thumb\"><img src=\"{url}\" title=\"{name}\"></div>',
					    '<span class=\"x-editable\">{shortName}</span></div></center>',
			        '</tpl>',
			        '<div class=\"x-clear\"></div>'
				),
	            autoHeight:true,
	            multiSelect: true,
	            overClass:'x-view-over',
	            itemSelector:'div.thumb-wrap',
	            emptyText: 'No attachments',
	
	            //plugins: [
	            //    new Ext.DataView.DragSelector(),
	            //    new Ext.DataView.LabelEditor({dataIndex: 'name'})
	            //],
	
	            prepareData: function(data){
	                data.shortName = Ext.util.Format.ellipsis(data.name, 16);
	                data.sizeString = Ext.util.Format.fileSize(data.size);
	                data.dateString = data.lastmod.format(\"m/d/Y g:i a\");
	                return data;
	            }
	        }),
			bbar: new Ext.PagingToolbar({
				id: 'attachmentToolbarMain2_{$docID}',
				height: 25,	
	            pageSize: 8,
	            store: $attachName,
	            displayInfo: false
	        }),
		    margins: '5 0 0 5',
		    cmargins: '5 5 0 5',
		    minSize: 100,
		    maxSize: 300,
		   	layout: 'fit',
		   	height: Ext.getCmp('tpAdmin').getInnerHeight(),
		   	renderTo: 'documentViewThumbnailUIDiv_{$docID}'
		});
		
		var attachmentToolbarMain_{$docID} = Ext.getCmp('attachmentToolbarMain_{$docID}');
		
		attachmentToolbarMain_{$docID}.add({
            text:'Attach',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
            	wndAttachMain_{$docID}.show();
            	//Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Scan',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
				wndScanMain_{$docID}.show();
            	//Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Delete',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            	//E/xt.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Refresh',
            iconCls: 'bmenu',
            handler: function(){
            	//accountStore.load({params:{start:0, limit:8}});
            	$attachName.reload();
			}
        });
        
        var documentViewerToolbar_{$docID} = Ext.getCmp('documentViewerToolbar_{$docID}');
        documentViewerToolbar_{$docID}.add(
		{
            text:'Send',
            id: 'btnSaveInstanceMain_{$docID}',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
				wndSendList_{$docID}.show();
			}
        },{
            text:'Save',
            id: 'btnSaveInstanceMain_{$docID}',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
            	Ext.Ajax.request({
		    		url: '/{$config ['appName']}/document/create-document',
		    		method: 'POST',
		    		//enctype: 'multipart/form-data',
		    		success: function() {
		    				Ext.getCmp('btnSaveInstanceMain_{$docID}').disable();
					},
		    		//success: function() {
		    		//	//$attachName.reload();
					//},
		    		//failure: documentAddFailed,
		    		form: Ext.get('viewinstance_{$docID}'),
		    		params: { 
		    			formID: {$formID}
		    			,tempID: {$docID}
					}
		    	});
			}
        },{
            text:'Print',
            id: 'btnPrintWord_{$docID}',
            iconCls: 'bmenu'
            handler: function(e) {
				//wndSendList_{$docID}.show();
			}
        },{
            text:'Cancel',
            iconCls: 'bmenu',
            disabled: false,
            handler: function(e) {
				//wndScan_{$docID}.show();
            	//Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        }
        );
		documentViewer_{$docID}.body = '$formHTML';
		 
		documentViewer_{$docID}.render();
		documentAttachMain_{$docID}.render();
		
		var gridSendTo{$docID} = new Ext.grid.GridPanel({
			id: 'gridSendTo{$docID}',
			title: 'Send List',
			store: tempStore{$docID},
			enableDragDrop: true,
			enableDrop: true,
			enableDrag: true,
			ddGroup : 'TreeDD', 
			autoExpandMax: true,
			columns: [
				{id: 'id', header: 'Name', width: 120, sortable: false, dataIndex: 'name'}
				,{header: 'Description', width: 120, sortable: false, dataIndex: 'description'}
				//,{header: 'Level', width: 0, sortable: false, dataIndex: 'level'}
				//,{header: 'Status', width: 0, sortable: false, dataIndex: 'status'}
			],
			viewConfig: {
				forceFit: true
			},
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: 250,
			height: 200,
			frame: false,
			iconCls:'icon-grid',
			renderTo: 'divSendToList{$docID}'
		});

		var gridSendCC{$docID} = new Ext.grid.GridPanel({
			id: 'gridSendCC{$docID}',
			title: 'Carbon Copy List',
			store: tempStore2{$docID},
			enableDragDrop: true,
			enableDrop: true,
			enableDrag: true,
			ddGroup : 'TreeDD', 
			autoExpandMax: true,
			columns: [
				{id: 'id', header: 'Name', width: 120, sortable: false, dataIndex: 'name'}
				,{header: 'Description', width: 120, sortable: false, dataIndex: 'description'}
				//,{header: 'Level', width: 0, sortable: false, dataIndex: 'level'}
				//,{header: 'Status', width: 0, sortable: false, dataIndex: 'status'}
			],
			viewConfig: {
				forceFit: true
			},
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			width: 250,
			height: 200,
			frame: false,
			iconCls:'icon-grid',
			renderTo: 'divSendCCList{$docID}'
		});

		var gridTo{$docID} = Ext.getCmp('gridSendTo{$docID}');
		
		
				
        var ddropTarget = new Ext.dd.DropTarget(gridTo{$docID}.getEl(), {
			ddGroup: 'TreeDD',
			copy:false,
			notifyDrop : function(dd, e, data)
			{
				if(data.node.attributes.treetype == 'orgchart') {
					var rec = new ReceiverRecord{$docID}({
						name: data.node.attributes.text,
						description: data.node.attributes.type,
						type: data.node.attributes.type,
						value: data.node.attributes.objid
					});
					tempStore{$docID}.add(rec);
				}
			}
		});

		var gridCC{$docID} = Ext.getCmp('gridSendCC{$docID}');
		
		
		        
        var ddropCCTarget = new Ext.dd.DropTarget(gridCC{$docID}.getEl(), {
			ddGroup: 'TreeDD',
			copy:false,
			notifyDrop : function(dd, e, data)
			{
				if(data.node.attributes.treetype == 'orgchart') {
					var rec = new ReceiverRecord{$docID}({
						name: data.node.attributes.text,
						description: data.node.attributes.type,
						type: data.node.attributes.type,
						value: data.node.attributes.objid
					});
					tempStore2{$docID}.add(rec);
				}
			}
		});
		
		var SendListContext{$docID} = new Ext.menu.Menu('SendListContext{$docID}');
		SendListContext{$docID}.add(
        	new Ext.menu.Item({id:'mnuItemRemoveReceiver', text: 'Remove'})
		);
		
		var SendCCListContext{$docID} = new Ext.menu.Menu('SendCCListContext{$docID}');
		SendCCListContext{$docID}.add(
        	new Ext.menu.Item({id:'mnuItemRemoverCCReceiver', text: 'Remove'})
		);
		
		gridTo{$docID}.on('rowcontextmenu', function(grid,rowIndex,e) {
			e.stopEvent();
			gridTo{$docID}.getSelectionModel().selectRow(rowIndex);
			var selectionModel = gridTo{$docID}.getSelectionModel();
            var record = selectionModel.getSelected();
			Cookies.set('ReceiverContext{$docID}',record.id);
			SendListContext{$docID}.showAt(e.getXY());
		}, gridTo{$docID});
		
		gridCC{$docID}.on('rowcontextmenu', function(grid,rowIndex,e) {
			e.stopEvent();
			gridCC{$docID}.getSelectionModel().selectRow(rowIndex);
			var selectionModel = gridCC{$docID}.getSelectionModel();
            var record = selectionModel.getSelected();
			Cookies.set('ReceiverCCContext{$docID}',record.id);
			SendCCListContext{$docID}.showAt(e.getXY());
		}, gridCC{$docID});
		
		Ext.getCmp('mnuItemRemoveReceiver').on('click',function() {
			var idx = Cookies.get('ReceiverContext{$docID}');
			tempStore{$docID}.remove(tempStore{$docID}.getById(idx)); 
		},gridTo{$docID});
		
		Ext.getCmp('mnuItemRemoverCCReceiver').on('click',function() {
			var idx = Cookies.get('ReceiverCCContext{$docID}');
			tempStore2{$docID}.remove(tempStore2{$docID}.getById(idx)); 
		},gridCC{$docID});
		
		</script>";
		echo "<script>$fillFormJS</script>";
	}
	
	/**
	 * action /add-attach-temp บันทึกเอกสารแนบ Temp
	 *
	 */
	public function addAttachTempAction() {
		global $config;
		global $sequence;
		global $util;
		
		$dav = new DAVStorage ( );
		$dav->connectToDefault ();
		
		$userTempPath = $config ['tempPath'] . "/{$_SESSION['accID']}";
		
		if (array_key_exists ( 'docTempID', $_POST )) {
			$tempID = $_POST ['docTempID'];
		}
		
		$storage = new StorageEntity ( );
		$storage->Load ( "f_default = '1'" );
		
		$sequencePageNo = 'docPageTempID_' . $tempID;
		
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		//$fp = fopen ( "d:/AJAXPOST.txt", "w+" );
		//fwrite ( $fp, $tempID . "\r\n" );
		echo $tempID;
		var_dump ( $_FILES );
		global $conn;
		$conn->debug = true;
		
		$i = 0;
		foreach ( $_FILES ['upload_file'] ['name'] as $uploadFilename ) {
			if (strlen ( trim ( $uploadFilename ) ) > 0) {
				//fwrite ( $fp, serialize ( $uploadFilename ) . "\r\n" );
				if (is_uploaded_file ( $_FILES ['upload_file'] ['tmp_name'] [$i] )) {
					echo "found uploaded file \r\n";
					//fwrite ( $fp, serialize ( $uploadFilename ) . " is a uploaded file\r\n" );
					$extension = getFileExtension ( basename ( $uploadFilename ) );
					$sysFilename = uniqid ( 'file_' );
					//fwrite ( $fp, "$sysFilename.$extension\r\n" );
					$DocPage = new DocPageTempEntity ( );
					
					$DocPage->f_doc_id = $tempID;
					$DocPage->f_page_id = $sequence->get ( $sequencePageNo );
					$DocPage->f_mime_type = $util->getMimeType ( $extension );
					$DocPage->f_orig_file_name = $uploadFilename;
					$DocPage->f_sys_file_name = $sysFilename;
					$DocPage->f_extension = $extension;
					$DocPage->f_file_size = filesize ( $_FILES ['upload_file'] ['tmp_name'] [$i] );
					if (! $DocPage->Save ()) {
						die ( "unable to save attached temp" );
					} else {
						echo "uploaded";
					}
					copy ( $_FILES ['upload_file'] ['tmp_name'] [$i], "{$userTempPath}/create/{$sysFilename}.{$extension}" );
					$dav->save ( 'DF', "{$sysFilename}.{$extension}", file_get_contents ( $_FILES ['upload_file'] ['tmp_name'] [$i] ) );
					unset ( $DocPage );
				
				}
			}
			$i ++;
		}
		
		die ( 'zzzz' );
		///fclose ( $fp );
	}
	
	/**
	 * action /detelte-attach ลบเอกสารแนบ
	 *
	 */
	public function deleteAttachAction() {
		$docID = $_POST ['docID'];
		$pageID = $_POST ['page'];
		$docPage = new DocPageEntity ( );
		$docPage->Load ( "f_doc_id = '{$docID}' and f_page_id = '$pageID'" );
		$docPage->Delete ();
		$response = Array ();
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	/**
	 * action /delete-announce-attach ลบเอกสารแนบของคำสั่งประกาศ
	 *
	 */
	public function deleteAnnounceAttachAction() {
		$docID = $_POST ['docID'];
		$pageID = $_POST ['page'];
		$docPage = new AnnouncePageEntity ( );
		$docPage->Load ( "f_announce_id = '{$docID}' and f_page_id = '$pageID'" );
		$docPage->Delete ();
		$response = Array ();
		$response ['success'] = 1;
		echo json_encode ( $response );
	}
	
	/**
	 * action /add-attach-cross ทำการแนบเอกสาร(ใช้งานรวมกันทั้งหมด)
	 *
	 */
	public function addAttachCrossAction() {
		global $config;
		global $sequence;
		global $util;
		global $sessionMgr;
		
		set_time_limit ( 0 );

		Logger::debug('Add Attachment');
		if (array_key_exists ( 'code', $_GET )) {
			list ( $docID, $userID ) = explode ( "_", $_GET ['code'] );
			$userTempPath = $config ['tempPath'] . "$userID";
		} else {
			if (array_key_exists ( 'docID', $_POST )) {
				$docID = $_POST ['docID'];
			}
			$userTempPath = $config ['tempPath'] . "{$_SESSION['accID']}";
		
		}
		Logger::debug ( "Method:" . $_POST ['attachMethod'] );
		Logger::debug ( "Method:" . $_POST ['attachMethodID'] );
		$mode = $_POST ['attachMethod'];
		$update = $_POST ['attachMethod'];
		$pageStart = $_POST ['pageStart'];
		$optionalLog = "";
		if (strtolower ( $mode ) != 'append') {
			$optionalLog = ", Start at page {$pageStart}";
		}
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		$currentPerson = new AccountEntity ( );
		$currentPerson->Load ( "f_acc_id = '{$sessionMgr->getCurrentAccID()}'" );
		$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} is Attach($mode Page) document ID [{$docID}],Title[{$docMain->f_title}]{$optionalLog}";
		Logger::log ( 4, $docID, $logMessage, true, false );
		
		//include_once 'Storage.Entity.php';
		//include_once 'DocPage.Entity.php';
		

		$storage = new StorageEntity ( );
		$storage->Load ( "f_default = '1'" );
		
		$sequencePageNo = 'docID_' . $docID;
		$sequencePageID = 'pageDoc_' . $docID;
		
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		if (! $sequence->isExists ( $sequencePageID )) {
			$sequence->create ( $sequencePageID );
		}
		
		if (! file_exists ( "{$config ['storageTempPath']}/{$docID}" )) {
			mkdir ( "{$config ['storageTempPath']}/{$docID}", 777 );
		}
		
		$i = 0;
		//var_dump($_FILES);
		//include_once 'DAVStorage.php';
		$dav = new DAVStorage ( );
		//$dav->connectToDefault();
		

		// error ตรงนี้แหละ
		foreach ( $_FILES ['upload_file'] ['name'] as $uploadFilename ) {
			Logger::debug ( $uploadFilename );
			
			//Logger::dump ( "upload", $_FILES );
			if (strlen ( trim ( $uploadFilename ) ) > 0) {
				
				if (is_uploaded_file ( $_FILES ['upload_file'] ['tmp_name'] [$i] )) {
					Logger::debug ( $uploadFilename . "#2" );
					Logger::debug ( "add attach {$uploadFilename}" );
					Logger::debug ( "add attach {$_FILES ['upload_file'] ['tmp_name'] [$i]}" );
					Logger::debug ( "filesize " . filesize ( $_FILES ['upload_file'] ['tmp_name'] [$i] ) );
					
					$extension = getFileExtension ( basename ( $uploadFilename ) );
					$sysFilename = uniqid ( 'file_' );
					$DocPage = new DocPageEntity ( );
					$DocPage->f_doc_id = $docID;
					$DocPage->f_page_id = $sequence->get ( $sequencePageID );
					$DocPage->f_page_no = $sequence->get ( $sequencePageNo );
					$DocPage->f_major_version = 1;
					$DocPage->f_minor_version = 0;
					$DocPage->f_branch_version = 0;
					$dav->connectToDefault ();
					$DocPage->f_st_id = $dav->getStorageID ();
					$DocPage->f_mime_type = $util->getMimeType ( $extension );
					$DocPage->f_orig_file_name = $uploadFilename;
					$DocPage->f_sys_file_name = $sysFilename;
					$DocPage->f_extension = $extension;
					$DocPage->f_file_size = filesize ( $_FILES ['upload_file'] ['tmp_name'] [$i] );
					$DocPage->f_moved_to_storage = 1;
					$DocPage->f_fulltext_indexed = 0;
					$DocPage->f_deleted = 0;
					$DocPage->f_delete_user = 0;
					$DocPage->f_create_stamp = time ();
					$DocPage->f_create_uid = $sessionMgr->getCurrentAccID ();
					$DocPage->f_create_role_id = $sessionMgr->getCurrentRoleID ();
					$DocPage->f_create_org_id = $sessionMgr->getCurrentOrgID ();
					
					$DocPage->Save ();
					copy ( $_FILES ['upload_file'] ['tmp_name'] [$i], "{$userTempPath}/create/{$sysFilename}.{$extension}" );
					copy ( $_FILES ['upload_file'] ['tmp_name'] [$i], "{$config ['storageTempPath']}/{$docID}/{$sysFilename}.{$extension}" );
					
					$fulltextSupport = false;

					//$dav->disconnect();   
					if($fulltextSupport) {
					switch (strtolower ( $extension )) {
						case 'pdf' :
							$fulltextSupport = false;
							$contentExtracter = new PDFContentExtractor ( );
							break;
						case 'doc' :
							$fulltextSupport = false;
							$contentExtracter = new WordContentExtractor ( );
							break;
						case 'xls' :
							$fulltextSupport = false;
							$contentExtracter = new ExcelContentExtractor ( );
							break;
						case 'ppt' :
							$fulltextSupport = false;
							$contentExtracter = new PowerPointContentExtractor();
							break;
						case 'docx':
						case 'pptx':
						case 'xlsx':
							$fulltextSupport = false;
							$contentExtracter = new Office2007ContentExtractor();
							break;
						case 'html':
						case 'htm':
						case 'cvs':
						case 'txt':
							$fulltextSupport = false;
							$contentExtracter = new PowerPointContentExtractor();
							break;
						default:
							$fulltextSupport = false;
							break;
					}
					}
					if ($fulltextSupport) {
						$fulltextContent = $contentExtracter->getContents ( "{$config ['storageTempPath']}/{$docID}/{$sysFilename}.{$extension}" );
						$fp = fopen ( "d:/testFulltext.txt", "w+" );
						fwrite ( $fp, UTFDecode ( $fulltextContent ) );
						fclose ( $fp );
					}
					if ($dav->save ( 'DF', "{$sysFilename}.{$extension}", file_get_contents ( "{$config ['storageTempPath']}/{$docID}/{$sysFilename}.{$extension}" ) )) {
					
					} else {
						//do something
					}
					$dav->disconnect ();
					unset ( $DocPage );
					$i ++;
				}
			}
		}
		
		//$dav->disconnect();   
		$response = Array ('success' => 1 );
		echo json_encode ( $response );
		ob_end_flush ();
		die ();
	}
	
	/**
	 * action /add-announce-attach ทำการแนบเอกสารแนบของคำสั่งประกาศ
	 *
	 */
	public function addAnnounceAttachAction() {
		global $config;
		global $sequence;
		global $util;
		global $sessionMgr;
		
		set_time_limit ( 0 );
		
		if (array_key_exists ( 'code', $_GET )) {
			list ( $docID, $userID ) = explode ( "_", $_GET ['code'] );
			$userTempPath = $config ['tempPath'] . "$userID";
		} else {
			if (array_key_exists ( 'docID', $_POST )) {
				$docID = $_POST ['docID'];
			}
			$userTempPath = $config ['tempPath'] . "{$_SESSION['accID']}";
		
		}
		
		//include_once 'Storage.Entity.php';
		//include_once 'DocPage.Entity.php';
		//include_once 'AnnouncePage.Entity.php';
		

		$storage = new StorageEntity ( );
		$storage->Load ( "f_default = '1'" );
		
		$sequencePageNo = 'announceID_' . $docID;
		$sequencePageID = 'pageAnnounce_' . $docID;
		
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		if (! $sequence->isExists ( $sequencePageID )) {
			$sequence->create ( $sequencePageID );
		}
		
		if (! file_exists ( "{$config ['storageTempPath']}/{$docID}" )) {
			mkdir ( "{$config ['storageTempPath']}/{$docID}", 777 );
		}
		
		$i = 0;
		//var_dump($_FILES);
		//include_once 'DAVStorage.php';
		$dav = new DAVStorage ( );
		//$dav->connectToDefault();
		

		// error ตรงนี้แหละ
		foreach ( $_FILES ['upload_file'] ['name'] as $uploadFilename ) {
			Logger::debug ( $uploadFilename );
			
			Logger::dump ( "upload", $_FILES );
			if (strlen ( trim ( $uploadFilename ) ) > 0) {
				
				if (is_uploaded_file ( $_FILES ['upload_file'] ['tmp_name'] [$i] )) {
					Logger::debug ( $uploadFilename . "#2" );
					Logger::debug ( "add attach {$uploadFilename}" );
					Logger::debug ( "add attach {$_FILES ['upload_file'] ['tmp_name'] [$i]}" );
					Logger::debug ( "filesize " . filesize ( $_FILES ['upload_file'] ['tmp_name'] [$i] ) );
					
					$extension = getFileExtension ( basename ( $uploadFilename ) );
					$sysFilename = uniqid ( 'file_' );
					$DocPage = new AnnouncePageEntity ( );
					$DocPage->f_announce_id = $docID;
					$DocPage->f_page_id = $sequence->get ( $sequencePageID );
					$DocPage->f_page_no = $sequence->get ( $sequencePageNo );
					$DocPage->f_major_version = 1;
					$DocPage->f_minor_version = 0;
					$DocPage->f_branch_version = 0;
					$dav->connectToDefault ();
					$DocPage->f_st_id = $dav->getStorageID ();
					$DocPage->f_mime_type = $util->getMimeType ( $extension );
					$DocPage->f_orig_file_name = $uploadFilename;
					$DocPage->f_sys_file_name = $sysFilename;
					$DocPage->f_extension = $extension;
					$DocPage->f_file_size = filesize ( $_FILES ['upload_file'] ['tmp_name'] [$i] );
					$DocPage->f_moved_to_storage = 1;
					$DocPage->f_fulltext_indexed = 0;
					$DocPage->f_deleted = 0;
					$DocPage->f_delete_user = 0;
					$DocPage->f_create_stamp = time ();
					$DocPage->f_create_uid = $sessionMgr->getCurrentAccID ();
					$DocPage->f_create_role_id = $sessionMgr->getCurrentRoleID ();
					$DocPage->f_create_org_id = $sessionMgr->getCurrentOrgID ();
					
					$DocPage->Save ();
					copy ( $_FILES ['upload_file'] ['tmp_name'] [$i], "{$userTempPath}/create/{$sysFilename}.{$extension}" );
					copy ( $_FILES ['upload_file'] ['tmp_name'] [$i], "{$config ['storageTempPath']}/{$docID}/{$sysFilename}.{$extension}" );
					
					if ($dav->save ( 'DF', "{$sysFilename}.{$extension}", file_get_contents ( "{$config ['storageTempPath']}/{$docID}/{$sysFilename}.{$extension}" ) )) {
					
					} else {
						//do something
					}
					$dav->disconnect ();
					unset ( $DocPage );
					$i ++;
				}
			}
		}
		
		//$dav->disconnect();   
		$response = Array ('success' => 1 );
		echo json_encode ( $response );
		ob_end_flush ();
		die ();
	}
	
	/**
	 * action /add-scan-http ทำการแสกนร่วมกับ HTTP Scan
	 *
	 */
	public function addScanHttpAction() {
		global $config;
		global $sequence;
		global $sessionMgr;
		global $util;
		
		set_time_limit ( 0 );
		//include_once 'DAVStorage.php';
		$dav = new DAVStorage ( );
		$dav->connectToDefault ();
		
		if (array_key_exists ( 'code', $_GET )) {
			list ( $docID, $userID ) = explode ( "_", $_GET ['code'] );
			$userTempPath = $config ['tempPath'] . "$userID";
		} else {
			if (array_key_exists ( 'docID', $_POST )) {
				$docID = $_POST ['docID'];
			}
			$userTempPath = $config ['tempPath'] . "{$_SESSION['accID']}";
		
		}
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		$currentPerson = new AccountEntity ( );
		$currentPerson->Load ( "f_acc_id = '{$sessionMgr->getCurrentAccID()}'" );
		
		switch ($_COOKIE ['smode']) {
			case 1 :
				$mode = "Append";
				$optionalLog = "";
				break;
			case 2 :
				$mode = "Replace";
				$optionalLog = ",Start at Page {$_COOKIE['spage']}";
				break;
			case 3 :
				$mode = "Insert";
				$optionalLog = ",Start at Page {$_COOKIE['spage']}";
				break;
		}
		
		$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} is SCAN($mode Page) document ID [{$docID}],Title[{$docMain->f_title}]{$optionalLog}";
		Logger::log ( 4, $docID, $logMessage, true, false );
		//include_once 'Storage.Entity.php';
		//include_once 'DocPage.Entity.php';
		

		$storage = new StorageEntity ( );
		$storage->Load ( "f_default = '1'" );
		
		$sequencePageNo = 'docID_' . $docID;
		$sequencePageID = 'pageDoc_' . $docID;
		
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		if (! $sequence->isExists ( $sequencePageID )) {
			$sequence->create ( $sequencePageID );
		}
		
		if (! file_exists ( "{$config ['storageTempPath']}/{$docID}" )) {
			mkdir ( "{$config ['storageTempPath']}/{$docID}", 777 );
		}
		
		$i = 0;
		//TWAIN HTTP
		foreach ( $_FILES ['userfile'] ['name'] as $uploadFilename ) {
			if (strlen ( trim ( $uploadFilename ) ) > 0) {
				if (is_uploaded_file ( $_FILES ['userfile'] ['tmp_name'] [$i] )) {
					$extension = getFileExtension ( basename ( $uploadFilename ) );
					$sysFilename = uniqid ( 'file_' );
					$DocPage = new DocPageEntity ( );
					$DocPage->f_doc_id = $docID;
					$DocPage->f_page_id = $sequence->get ( $sequencePageID );
					$DocPage->f_page_no = $sequence->get ( $sequencePageNo );
					$DocPage->f_major_version = 1;
					$DocPage->f_minor_version = 0;
					$DocPage->f_branch_version = 0;
					$DocPage->f_st_id = $dav->getStorageID ();
					$DocPage->f_mime_type = $util->getMimeType ( $extension );
					$DocPage->f_orig_file_name = $uploadFilename;
					$DocPage->f_sys_file_name = $sysFilename;
					$DocPage->f_extension = $extension;
					$DocPage->f_file_size = filesize ( $_FILES ['userfile'] ['tmp_name'] [$i] );
					$DocPage->f_moved_to_storage = 1;
					$DocPage->f_fulltext_indexed = 0;
					$DocPage->f_deleted = 0;
					$DocPage->f_delete_user = 0;
					$DocPage->f_create_stamp = time ();
					$DocPage->f_create_uid = $sessionMgr->getCurrentAccID ();
					$DocPage->f_create_role_id = $sessionMgr->getCurrentRoleID ();
					$DocPage->f_create_org_id = $sessionMgr->getCurrentOrgID ();
					$DocPage->Save ();
					copy ( $_FILES ['userfile'] ['tmp_name'] [$i], "{$userTempPath}/create/{$sysFilename}.{$extension}" );
					copy ( $_FILES ['userfile'] ['tmp_name'] [$i], "{$config ['storageTempPath']}/{$docID}/{$sysFilename}.{$extension}" );
					$dav->save ( 'DF', "{$sysFilename}.{$extension}", file_get_contents ( $_FILES ['userfile'] ['tmp_name'] [$i] ) );
					unset ( $DocPage );
				}
			}
			$i ++;
		}
		$response = Array ('success' => 1 );
		//echo json_encode ( $response );
		echo "OK";
		///fclose ( $fp );
	}
	
	/**
	 * action /add-scan-announce ทำการแนบเอกสารแสกนสำหรับคำสั่ง/ประกาศ
	 *
	 */
	public function addScanAnnounceAction() {
		global $config;
		global $sequence;
		global $util;
		
		set_time_limit ( 0 );
		//include_once 'DAVStorage.php';
		$dav = new DAVStorage ( );
		$dav->connectToDefault ();
		
		if (array_key_exists ( 'code', $_GET )) {
			list ( $docID, $userID ) = explode ( "_", $_GET ['code'] );
			$userTempPath = $config ['tempPath'] . "$userID";
		} else {
			if (array_key_exists ( 'docID', $_POST )) {
				$docID = $_POST ['docID'];
			}
			$userTempPath = $config ['tempPath'] . "{$_SESSION['accID']}";
		
		}
		
		//include_once 'Storage.Entity.php';
		//include_once 'AnnouncePage.Entity.php';
		

		$storage = new StorageEntity ( );
		$storage->Load ( "f_default = '1'" );
		
		$sequencePageNo = 'docID_' . $docID;
		$sequencePageID = 'pageDoc_' . $docID;
		
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		if (! $sequence->isExists ( $sequencePageID )) {
			$sequence->create ( $sequencePageID );
		}
		
		if (! file_exists ( "{$config ['storageTempPath']}/{$docID}" )) {
			mkdir ( "{$config ['storageTempPath']}/{$docID}", 777 );
		}
		
		$i = 0;
		//TWAIN HTTP
		foreach ( $_FILES ['userfile'] ['name'] as $uploadFilename ) {
			if (strlen ( trim ( $uploadFilename ) ) > 0) {
				if (is_uploaded_file ( $_FILES ['userfile'] ['tmp_name'] [$i] )) {
					$extension = getFileExtension ( basename ( $uploadFilename ) );
					$sysFilename = uniqid ( 'file_' );
					$DocPage = new AnnouncePageEntity ( );
					$DocPage->f_announce_id = $docID;
					$DocPage->f_page_id = $sequence->get ( $sequencePageID );
					$DocPage->f_page_no = $sequence->get ( $sequencePageNo );
					$DocPage->f_major_version = 1;
					$DocPage->f_minor_version = 0;
					$DocPage->f_branch_version = 0;
					$DocPage->f_st_id = $dav->getStorageID ();
					$DocPage->f_mime_type = $util->getMimeType ( $extension );
					$DocPage->f_orig_file_name = $uploadFilename;
					$DocPage->f_sys_file_name = $sysFilename;
					$DocPage->f_extension = $extension;
					$DocPage->f_file_size = filesize ( $_FILES ['userfile'] ['tmp_name'] [$i] );
					$DocPage->f_moved_to_storage = 1;
					$DocPage->f_fulltext_indexed = 0;
					$DocPage->f_deleted = 0;
					$DocPage->f_delete_user = 0;
					$DocPage->Save ();
					copy ( $_FILES ['userfile'] ['tmp_name'] [$i], "{$userTempPath}/create/{$sysFilename}.{$extension}" );
					copy ( $_FILES ['userfile'] ['tmp_name'] [$i], "{$config ['storageTempPath']}/{$docID}/{$sysFilename}.{$extension}" );
					$dav->save ( 'DF', "{$sysFilename}.{$extension}", file_get_contents ( $_FILES ['userfile'] ['tmp_name'] [$i] ) );
					unset ( $DocPage );
				}
			}
			$i ++;
		}
		$response = Array ('success' => 1 );
		//echo json_encode ( $response );
		echo "OK";
		///fclose ( $fp );
	}
	
	/**
	 * action /add-scan-temp โปรแกรมแนบเอกสารแสกน (น่าจะไม่มีการใช้งาน)
	 *
	 */
	public function addScanTempAction() {
		global $config;
		global $sequence;
		
		//include_once 'DAVStorage.php';
		$dav = new DAVStorage ( );
		$dav->connectToDefault ();
		
		$userTempPath = $config ['tempPath'] . "/{$_SESSION['accID']}";
		$userScanPath = $config ['scanPath'] . "{$_SESSION['accID']}";
		
		//include_once 'Storage.Entity.php';
		//include_once 'DocPageTemp.Entity.php';
		

		if (array_key_exists ( 'docTempID', $_POST )) {
			$tempID = $_POST ['docTempID'];
		}
		$storage = new StorageEntity ( );
		$storage->Load ( "f_default = '1'" );
		
		$sequencePageNo = 'docPageTempID_' . $tempID;
		
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		if (is_dir ( $userScanPath )) {
			$dh = opendir ( $userScanPath );
			if ($dh) {
				while ( ($file = readdir ( $dh )) !== false ) {
					if (! in_array ( $file, array ('.', '..' ) )) {
						
						$extension = getFileExtension ( basename ( $file ) );
						$sysFilename = uniqid ( 'file_' );
						
						$DocPage = new DocPageTempEntity ( );
						$DocPage->f_doc_id = $tempID;
						$DocPage->f_page_no = $sequence->get ( $sequencePageNo );
						$DocPage->f_st_id = $storage->f_st_id;
						$DocPage->f_mime_type = getMimeType ( $extension );
						$DocPage->f_orig_file_name = $file;
						$DocPage->f_sys_file_name = $sysFilename;
						$DocPage->f_extension = $extension;
						$DocPage->f_file_size = filesize ( $userScanPath . "/" . $file );
						$DocPage->f_moved_to_storage = 0;
						$DocPage->f_fulltext_indexed = 0;
						$DocPage->f_deleted = 0;
						$DocPage->f_delete_user = 0;
						$DocPage->Save ();
						copy ( $userScanPath . "/" . $file, "{$userTempPath}/create/{$sysFilename}.{$extension}" );
						$dav->save ( 'DF', "{$sysFilename}.{$extension}", file_get_contents ( $userScanPath . "/" . $file ) );
						@unlink ( "{$userScanPath}/{$file}" );
					}
				}
				closedir ( $dh );
			}
		}
	
	}
	
	/**
	 * action /add-scan-cross ทำการสแกนแนบ(น่าจะไม่มีการใช้งานแล้ว)
	 *
	 */
	public function addScanCrossAction() {
		global $config;
		global $sequence;
		
		set_time_limit ( 0 );
		//include_once 'DAVStorage.php';
		$dav = new DAVStorage ( );
		$dav->connectToDefault ();
		
		$userTempPath = $config ['tempPath'] . "/{$_SESSION['accID']}";
		$userScanPath = $config ['scanPath'] . "{$_SESSION['accID']}";
		
		//include_once 'Storage.Entity.php';
		//include_once 'DocPageTemp.Entity.php';
		//include_once 'Storage.Entity.php';
		//include_once 'DocPage.Entity.php';
		

		if (array_key_exists ( 'docID', $_POST )) {
			$docID = $_POST ['docID'];
		}
		$storage = new StorageEntity ( );
		$storage->Load ( "f_default = '1'" );
		
		$sequencePageNo = 'docID_' . $docID;
		$sequencePageID = 'pageDoc_' . $docID;
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		if (! $sequence->isExists ( $sequencePageID )) {
			$sequence->create ( $sequencePageID );
		}
		if (! file_exists ( "{$config ['storageTempPath']}/{$docID}" )) {
			mkdir ( "{$config ['storageTempPath']}/{$docID}", 777 );
		}
		if (is_dir ( $userScanPath )) {
			$dh = opendir ( $userScanPath );
			if ($dh) {
				while ( ($file = readdir ( $dh )) !== false ) {
					if (! in_array ( $file, array ('.', '..' ) )) {
						
						$extension = getFileExtension ( basename ( $file ) );
						$sysFilename = uniqid ( 'file_' );
						//echo "$file\r\n";
						//echo "$sysFilename\r\n";
						

						$DocPage = new DocPageEntity ( );
						$DocPage->f_doc_id = $docID;
						$DocPage->f_page_id = $sequence->get ( $sequencePageID );
						$DocPage->f_page_no = $sequence->get ( $sequencePageNo );
						$DocPage->f_major_version = 1;
						$DocPage->f_minor_version = 0;
						$DocPage->f_branch_version = 0;
						$DocPage->f_st_id = $storage->f_st_id;
						$DocPage->f_mime_type = getMimeType ( $extension );
						$DocPage->f_orig_file_name = $file;
						$DocPage->f_sys_file_name = $sysFilename;
						$DocPage->f_extension = $extension;
						$DocPage->f_file_size = filesize ( $userScanPath . "/" . $file );
						$DocPage->f_moved_to_storage = 1;
						$DocPage->f_fulltext_indexed = 0;
						$DocPage->f_deleted = 0;
						$DocPage->f_delete_user = 0;
						$DocPage->Save ();
						copy ( $userScanPath . "/" . $file, "{$config ['storageTempPath']}/{$docID}/{$sysFilename}.{$extension}" );
						@unlink ( "{$userScanPath}/{$file}" );
					}
				}
				closedir ( $dh );
			}
		}
		
		$response = Array ('success' => 1 );
		echo json_encode ( $response );
	}
	
	/**
	 * action /create-document ทำการสร้างเอกสาร
	 *
	 */
	public function createDocumentAction() {
		global $config;
		global $sequence;
		global $sessionMgr;
		
		//include_once 'Form.Entity.php';
		//include_once 'FormStructure.Entity.php';
		//include_once 'DocMain.Entity.php';
		//include_once 'DocPage.Entity.php';
		//include_once 'DocPageTemp.Entity.php';
		//include_once 'DocValue.Entity.php';
		//include_once 'DmsMyObject.Entity.php';
		//include_once 'DmsObject.Entity.php';
		

		//include_once 'DAVStorage.php';
		$dav = new DAVStorage ( );
		$dav->connectToDefault ();
		
		if (! $sequence->isExists ( 'docID' )) {
			$sequence->create ( 'docID' );
		}
		$formID = $_POST ['formID'];
		$tempID = $_POST ['tempID'];
		$parentID = $_POST ['parentID'];
		//$parentType = $_POST ['parentType'];
		$parentMode = $_POST ['parentMode'];
		
		$form = new FormEntity ( );
		$form->Load ( "f_form_id = '$formID'" );
		$formStructures = new FormStructureEntity ( );
		$structureArray = & $formStructures->Find ( "f_form_id = '$formID'" );
		$inputFilter = Array ();
		
		$documentID = $sequence->get ( 'docID' );
		
		foreach ( $structureArray as $structure ) {
			$inputFilter [$structure->f_struct_name] = FILTER_SANITIZE_STRING;
			if ($structure->f_is_title == 1) {
				$titleStructure = $structure->f_struct_name;
			}
			if ($structure->f_is_desc == 1) {
				$descStructure = $structure->f_struct_name;
			}
			if ($structure->f_is_keyword == 1) {
				$keywordStructure = $structure->f_struct_name;
			}
			//TODO: Not Implement Doc.Date Structure/Doc.No. Structure Yet
		/*
			if ($structure->f_is_doc_date == 1) {
				$docDateStructure = $structure->f_struct_name;
			}
			if ($structure->f_is_doc_no == 1) {
				$docNoStructure = $structure->f_struct_name;
			}
			*/
		}
		
		$formData = filter_input_array ( INPUT_POST, $inputFilter );
		$stampNow = time ();
		/* Create Main Document */
		$docMain = new DocMainEntity ( );
		$docMain->f_doc_id = $documentID;
		$docMain->f_form_id = $formID;
		$docMain->f_title = UTFDecode ( $formData [$titleStructure] );
		if (array_key_exists ( $descStructure, $formData )) {
			$docMain->f_description = UTFDecode ( $formData [$descStructure] );
		} else {
			$docMain->f_description = '';
		}
		$docMain->f_doc_no = 'unassigned';
		$docMain->f_doc_date = date ( 'd/m/Y' );
		$docMain->f_doc_realdate = $stampNow;
		$docMain->f_doc_revision = 1;
		$docMain->f_doc_stamp = $stampNow;
		$docMain->f_flowed = 0;
		$docMain->f_flow_type = 0;
		$docMain->f_create_stamp = $stampNow;
		//$docMain->f_create_user = $_SESSION ['accID'];
		$docMain->f_create_uid = $sessionMgr->getCurrentAccID ();
		$docMain->f_last_update_stamp = $stampNow;
		$docMain->f_last_update_user = $sessionMgr->getCurrentAccID ();
		$docMain->f_mark_delete = 0;
		$docMain->f_mark_delete_user = 0;
		$docMain->f_delete = 0;
		$docMain->f_delete_user = 0;
		$docMain->f_orphaned = 0;
		$docMain->f_status = 1;
		$docMain->f_checkout = 0;
		$docMain->f_checkout_user = 0;
		$docMain->Save ();
		
		// Save Document Field
		foreach ( $structureArray as $structure ) {
			$docValue = new DocValueEntity ( );
			$docValue->f_doc_id = $documentID;
			$docValue->f_struct_id = $structure->f_struct_id;
			$docValue->f_value = UTFDecode ( $formData [$structure->f_struct_name] );
			$docValue->Save ();
			unset ( $docValue );
		}
		
		//Move Attachment to Storage
		//TODO: Move Attachment to Storage
		//include_once 'Storage.Entity.php';
		$defaultStorage = new StorageEntity ( );
		$defaultStorage->Load ( "f_default = '1'" );
		
		if ($defaultStorage->f_st_type == 0) {
			//include_once 'DAVStorage.php';
			$storage = new DAVStorage ( );
			$storage->connectToDefault ();
		}
		
		$uniqueFileID = uniqid ();
		$collectionName = substr ( $uniqueFileID, 0, 3 );
		$storage->connect ( $defaultStorage->f_st_server, $defaultStorage->f_st_uid, $defaultStorage->f_st_pwd, $defaultStorage->f_st_path );
		//$storage->newCollection ( $collectionName );
		global $conn;
		//$conn->debug = true;
		$docPageTemps = new DocPageTempEntity ( );
		$pageTempArray = & $docPageTemps->Find ( "f_doc_id = '$tempID'" );
		
		$sequencePageNo = 'docID_' . $documentID;
		$sequencePageID = "pageDoc_{$documentID}";
		
		if (! $sequence->isExists ( $sequencePageNo )) {
			$sequence->create ( $sequencePageNo );
		}
		
		if (! $sequence->isExists ( $sequencePageID )) {
			$sequence->create ( $sequencePageID );
		}
		
		if (! file_exists ( "{$config ['storageTempPath']}/{$documentID}" )) {
			mkdir ( "{$config ['storageTempPath']}/{$documentID}", 777 );
		}
		foreach ( $pageTempArray as $pageTemp ) {
			//TODO:ย้าย Page ไป Storage เปลี่ยนเป็นใช้ Batch
			//$storage->addFile ( $collectionName, $pageTemp->f_orig_file_name, file_get_contents ( $tempFilePath ) );
			$docPage = new DocPageEntity ( );
			$docPage->f_doc_id = $documentID;
			$docPage->f_page_id = $sequence->get ( $sequencePageID );
			echo "pageDoc_{$documentID}";
			//TODO: ดูว่าจะใช้ Page no อีกหรือไม่อีกที
			$docPage->f_page_no = $sequence->get ( $sequencePageNo );
			$docPage->f_major_version = 1;
			$docPage->f_minor_version = 0;
			$docPage->f_branch_version = 0;
			$docPage->f_st_id = $defaultStorage->f_st_id;
			$docPage->f_mime_type = $pageTemp->f_mime_type;
			$docPage->f_orig_file_name = $pageTemp->f_orig_file_name;
			$docPage->f_sys_file_name = $pageTemp->f_sys_file_name;
			$docPage->f_extension = $pageTemp->f_extension;
			$docPage->f_file_size = $pageTemp->f_file_size;
			//TODO: Fix Page CheckSUM
			$docPage->f_page_checksum = 0;
			
			$docPage->f_moved_to_storage = 1;
			$docPage->f_fulltext_indexed = 0;
			
			//TODO: Check Create LOG
			$docPage->f_create_stamp = time ();
			$docPage->f_create_uid = 0;
			$docPage->f_create_role_id = 0;
			$docPage->f_create_org_id = 0;
			
			$docPage->f_modified_stamp = 0;
			$docPage->f_modified_uid = 0;
			$docPage->f_modified_role_id = 0;
			$docPage->f_modified_org_id = 0;
			
			$docPage->f_deleted = 0;
			$docPage->f_delete_uid = 0;
			$docPage->f_delete_role_id = 0;
			$docPage->f_delete_org_id = 0;
			
			$docPage->f_active = 1;
			
			$docPage->Save ();
			
			$tempFilePath = $config ['tempPath'] . "/{$_SESSION['accID']}/create/{$pageTemp->f_sys_file_name}.{$pageTemp->f_extension}";
			$tagetFilePath = "{$config ['storageTempPath']}{$documentID}/{$docPage->f_sys_file_name}.{$docPage->f_extension}";
			echo "Move {$tempFilePath} To {$tagetFilePath}";
			copy ( $tempFilePath, $tagetFilePath );
			$storage->save ( "DF", "{$pageTemp->f_sys_file_name}.{$pageTemp->f_extension}", file_get_contents ( $tempFilePath ) );
			@unlink ( $tempFilePath );
			unset ( $docPage );
		}
		
		if ($parentMode == 'mydoc') {
			// Create in My Document
			

			if (! $sequence->isExists ( 'myDocID' )) {
				$sequence->create ( 'myDocID' );
			}
			
			if (! $sequence->isExists ( 'DMSID' )) {
				$sequence->create ( 'DMSID' );
			}
			
			$node = new DmsObjectEntity ( );
			$node->f_obj_id = $sequence->get ( 'DMSID' );
			if ($parentID == 'MyDocRoot') {
				$parentNode = 0;
			} else {
				$parentNode = $parentID;
			}
			$node->f_obj_pid = $parentNode;
			$node->f_obj_lid = 0;
			$node->f_obj_type = 1;
			$node->f_obj_level = 0;
			$node->f_owner_id = $_SESSION ['accID'];
			$node->f_name = UTFDecode ( $formData [$titleStructure] );
			$node->f_description = UTFDecode ( $formData [$descStructure] );
			$node->f_keyword = UTFDecode ( $formData [$keywordStructure] );
			/*
			$node->f_location = '';
			$node->f_doc_id = $docMain->f_doc_id;
			$node->f_created_stamp = $stampNow;
			$node->f_mark_delete = 0;
			$node->f_delete = 0;
			$node->f_last_update_stamp = 0;
			$node->f_published = 0;
			$node->f_status = 1;
			$node->f_is_expired = 0;
			$node->f_expire_stamp = 0;
			*/
			$node->f_location = '';
			$node->f_doc_id = $docMain->f_doc_id;
			$node->f_created_user = $_SESSION ['accID'];
			$node->f_created_stamp = $stampNow;
			$node->f_mark_delete = 0;
			$node->f_mark_delete_user = 0;
			$node->f_delete = 0;
			$node->f_delete_user = 0;
			$node->f_last_update_stamp = 0;
			$node->f_last_update_user = 0;
			$node->f_locked = 0;
			$node->f_password = '';
			$node->f_checkout = 0;
			$node->f_checkout_user = 0;
			$node->f_published = 0;
			$node->f_publish_user = 0;
			$node->f_owner_type = 3;
			$node->f_owner_id = $_SESSION ['accID'];
			$node->f_override = 0;
			$node->f_borrowed = 0;
			$node->f_orphaned = 0;
			$node->f_status = 1;
			$node->f_expire_stamp = 0;
			$node->f_in_mydoc = 1;
			$node->Save ();
		
		} else {
			// Create in DMS System
			if (! $sequence->isExists ( 'DMSID' )) {
				$sequence->create ( 'DMSID' );
			}
			
			$node = new DmsObjectEntity ( );
			$node->f_obj_id = $sequence->get ( 'DMSID' );
			if ($parentID == 'MyDocRoot') {
				$parentNode = 0;
			} else {
				$parentNode = $parentID;
			}
			$node->f_obj_pid = $parentNode;
			$node->f_obj_lid = 0;
			$node->f_obj_type = 1;
			$node->f_obj_level = 0;
			$node->f_name = UTFDecode ( $formData [$titleStructure] );
			$node->f_description = UTFDecode ( $formData [$descStructure] );
			$node->f_keyword = UTFDecode ( $formData[$keywordStructure] );
			$node->f_location = '';
			$node->f_doc_id = $docMain->f_doc_id;
			$node->f_created_user = $_SESSION ['accID'];
			$node->f_created_stamp = $stampNow;
			$node->f_mark_delete = 0;
			$node->f_mark_delete_user = 0;
			$node->f_delete = 0;
			$node->f_delete_user = 0;
			$node->f_last_update_stamp = 0;
			$node->f_last_update_user = 0;
			$node->f_locked = 0;
			$node->f_password = '';
			$node->f_checkout = 0;
			$node->f_checkout_user = 0;
			$node->f_published = 0;
			$node->f_publish_user = 0;
			$node->f_owner_type = 3;
			$node->f_owner_id = $_SESSION ['accID'];
			$node->f_override = 0;
			$node->f_borrowed = 0;
			$node->f_orphaned = 0;
			$node->f_status = 1;
			$node->f_expire_stamp = 0;
			$node->f_in_mydoc = 0;
			try {
				$node->Save ();
			} catch ( Exception $e ) {
				echo $e->getMessage ();
			}
			//$fp = fopen('d:/log.log','w+');
			//fwrite($fp,serialize($node));
			//fclose($fp);
			unset ( $node );
		}
	}
	
	/**
	 * action /edit-document ทำการแก้ไขเอกสาร
	 *
	 */
	public function editDocumentAction() {
		global $conn;
		global $config;
		global $sequence;
		global $sessionMgr;
		global $util;
		
		//include_once 'Account.Entity.php';
		//include_once 'Form.Entity.php';
		//include_once 'FormStructure.Entity.php';
		//include_once 'DocMain.Entity.php';
		//include_once 'DocPage.Entity.php';
		//include_once 'DocPageTemp.Entity.php';
		//include_once 'DocHistValue.Entity.php';
		//include_once 'DocValue.Entity.php';
		//include_once 'DmsObject.Entity.php';
		//include_once 'DmsMyObject.Entity.php';

		if (! $sequence->isExists ( 'docID' )) {
			$sequence->create ( 'docID' );
		}
		
		$formID = $_POST ['formID'];
		$instanceID = $_POST ['instanceID'];
		$documentID = $_POST ['instanceID'];
		
		$form = new FormEntity ( );
		$form->Load ( "f_form_id = '$formID'" );
		$formStructures = new FormStructureEntity ( );
		$structureArray = & $formStructures->Find ( "f_form_id = '$formID'" );
		$inputFilter = Array ();
		
		$titleStructure = "";
		$descStructure = "";
		$docNoStructure = "";
		$docDateStructure = "";
		
		foreach ( $structureArray as $structure ) {
			$inputFilter [$structure->f_struct_name] = FILTER_SANITIZE_STRING;
			if ($structure->f_is_title == 1) {
				$titleStructure = $structure->f_struct_name;
			}
			
			if ($structure->f_is_desc == 1) {
				$descStructure = $structure->f_struct_name;
			}
			
			if ($structure->f_is_keyword == 1) {
				$keywordStructure = $structure->f_struct_name;
			}
			
			if ($structure->f_is_doc_no == 1) {
				$docNoStructure = $structure->f_struct_name;
			}
			
			if ($structure->f_is_doc_date == 1) {
				$docDateStructure = $structure->f_struct_name;
			}
		
		}

		$formData = filter_input_array ( INPUT_POST, $inputFilter );
		$stampNow = time ();
		/* Create Main Document */
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$documentID}'" );
		
		if ($titleStructure != "" && array_key_exists ( $titleStructure, $formData )) {
			$docMain->f_title = UTFDecode ( $formData [$titleStructure] );
		} /* else {
			$docMain->f_title = "";
		}*/
		
		if ($descStructure != "" && array_key_exists ( $descStructure, $formData )) {
			$docMain->f_description = UTFDecode ( $formData [$descStructure] );
		} /* else {
			$docMain->f_description = '';
		}*/
		
		if ($docNoStructure != "" && array_key_exists ( $docNoStructure, $formData )) {
			$docMain->f_doc_no = UTFDecode ( $formData [$docNoStructure] );
		} /* else {
			$docMain->f_description = '';
		}*/
		
		if ($docDateStructure != "" && array_key_exists ( $docDateStructure, $formData )) {
			$docMain->f_doc_date = UTFDecode ( $formData [$docDateStructure] );
			$docMain->f_doc_realdate = $util->dateToStamp( $formData [$docDateStructure] );
		} /* else {
			$docMain->f_description = '';
		}*/
		
		//$docMain->f_doc_date = date ( 'd/m/Y' );
		//$docMain->f_doc_date = date ( 'd/m/Y' );
		//$docMain->f_doc_stamp = $stampNow;
		//$docMain->f_flowed = 0;
		//$docMain->f_flow_type = 0;
		$docMain->f_last_update_stamp = $stampNow;
		$oldRevision = $docMain->f_doc_revision;
		//$docMain->f_doc_revision  = (int)$docMain->f_doc_revision + 1;
		//var_dump($docMain);
		//die( $docMain->f_doc_revision);
		$docMain->f_last_update_user = $sessionMgr->getCurrentAccID ();
		$conn->BeginTrans ();
		//$docMain->Update();
		$docMain->f_doc_revision += 1;
		$docMain->Save ();
		$conn->CommitTrans ();
		//บันทึกการแก้ไขเอกสารลงใน audit log
		$currentPerson = $sessionMgr->getCurrentAccountEntity ();
		$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} is edit document ID [{$documentID}],Title[{$docMain->f_title}]";
		Logger::log ( 4, $documentID, $logMessage, true, false, 2 );
		//include_once 'DocRevision.Entity.php';
		$docRevision = new DocRevisionEntity ( );
		$docRevision->f_doc_id = $documentID;
		$docRevision->f_revision = $docMain->f_doc_revision;
		$docRevision->f_uid = $sessionMgr->getCurrentAccID ();
		$docRevision->f_timestamp = time ();
		$docRevision->Save ();
		
		//$conn->debug = true;
		//$sqlUpdateRevision = "update tbl_doc_main set f_doc_revision = f_doc_revision + 1 where f_doc_id = '{$documentID}'";
		//$conn->Execute($sqlUpdateRevision);
		//die();
		
		$transMaster = new TransMasterDfEntity( );
		$transMaster->Load( "f_doc_id = '{$documentID}'" );

		$transRecv = new TransDfRecvEntity( );
		$transRecv->Load( "f_recv_trans_main_id = '".$transMaster->f_trans_main_id."'" );

		$transExt = new DFTransaction( );

		// Save Document Field
		foreach ( $structureArray as $structure ) {
			$docHistValue = new DocHistValueEntity ( );
			$docValue = new DocValueEntity ( );
			$docValue->Load ( "f_doc_id = '{$documentID}' and f_struct_id = '{$structure->f_struct_id}'" );
			$docHistValue->f_doc_id = $documentID;
			$docHistValue->f_revision = $oldRevision;
			$docHistValue->f_struct_id = $structure->f_struct_id;
			$docHistValue->f_value = $docValue->f_value;
			$docHistValue->Save ();
			
			$oldName = $docValue->f_value;

			//$docValue->f_doc_id = $documentID;           
			//$docValue->f_struct_id = $structure->f_struct_id;
			#$docValue->f_value = UTFDecode (  $formData [$structure->f_struct_name] ); 
			$docValue->f_value = str_replace('&#34;','"',htmlspecialchars_decode(UTFDecode (  $formData [$structure->f_struct_name] ), ENT_QUOTES));
			if( $structure->f_struct_name == 'atc_sender' && $docValue->f_value != ''){
				$transRecv->f_send_fullname = $docValue->f_value;
				$transExt->externalOrg( $docValue->f_value,$oldName );
				$transRecv->Update( );
			}

			$docValue->Update ();
			unset ( $docValue );
		}
		
		$result = Array ();
		$result ['success'] = 1;
		$result ['refresh'] = 0;
		/*
		if (array_key_exists ( 'contextDMSObjectMode', $_COOKIE )) {
			$result ['refresh'] = 1;
			$result ['id'] = $DMSElID;
			$DMSObjectID = $_COOKIE ['contextDMSObjectID'];
			$DMSElID = $_COOKIE ['contextDMSElID'];
			$dmsMode = $_COOKIE ['contextDMSObjectMode'];
			if ($dmsMode == 'dms') {
				$objDMS = new DmsObjectEntity ( );
				$objDMS->Load ( "f_obj_id = '{$DMSObjectID}'" );
			} else {
				$objDMS = new DmsObjectEntity ( );
				$objDMS->Load ( "f_obj_id = '{$DMSObjectID}'" );
			}
			
			$objDMS->f_name = $docMain->f_title;
			$objDMS->f_description = $docMain->f_description;
			$objDMS->f_keyword = UTFDecode ( $formData [$keywordStructure ]);
			$objDMS->Update ();
			Logger::debug('Modify DMS Object');
		} else {
			Logger::debug('not Modify DMS Object');
		}
		*/
		/****************************************/
		$keywordUpdate = UTFDecode ( $formData [$keywordStructure ]);
		$sqlUpdateDMSTree = "update tbl_dms_object set f_name = '{$docMain->f_title}' ,f_description = '{$docMain->f_description}',f_keyword = '{$keywordUpdate}' where f_doc_id = '{$docMain->f_doc_id}'";
		$conn->Execute($sqlUpdateDMSTree);
		Logger::debug($sqlUpdateDMSTree );
		
		echo json_encode ( $result );
	}
	
	/**
	 * action /view-cross-module ทำการแสดงเอกสารใช้ร่วมกันทุกระบบ
	 *
	 */
	public function viewCrossModuleAction() {
		global $config;
		//global $sequence;
		global $store;
		global $lang;
		global $util;
		global $sessionMgr;
		global $policy;
		
		checkSessionPortlet ();
		
		//ob_start();
	
		//include_once 'DocMain.Entity.php';
		//include_once 'DocValue.Entity.php';
		//include_once 'Form.Entity.php';
		//include_once 'FormStructure.Entity.php';
		//include_once 'Command.Entity.php';
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Organize.Entity.php';
		//{$config ['appName']}/document/add-attach-cross
		

		global $conn;
		
		$_COOKIE ['smode'] = 1;
		$_COOKIE ['supdate'] = 1;
		$_COOKIE ['spage'] = 0;
		
		$callModuleType = $_POST ['callModuleType'];
		$docID = $_POST ['docID'];
		$docRefTransMode = $_POST ['callModuleType'];
		$docRefTransID = $_POST ['docRefTransID'];
		$code = "{$docID}_{$sessionMgr->getCurrentAccID()}";
		if($config ['clusterMode']) {
			$postURL = 'http://' . $config ['clusterName'] . '/' . $config ['appName'] . '/document/add-scan-http?code=' . $code;
		} else {
			$postURL = 'http://' . $_SERVER ['HTTP_HOST'] . '/' . $config ['appName'] . '/document/add-scan-http?code=' . $code;
		}
		$scanOption = "Method:<select id=\"scanMethod\"><option value=\"1\">Append</option><option value=\"2\">Replace</option><option value=\"3\">Insert</option></select><br/>Update:<select id=\"scanUpdate\"><option value=\"0\">None</option><option value=\"1\">Major</option><option value=\"2\">Minor</option><option value=\"3\">Branch</option></select><br/>Page Start:<input type=\"\text\" id=\"scanPage\" style=\"width: 2em;\"><br/>";
		if ($config ['clusterMode']) {
			$scanButton = $scanOption . '<img src="http://' . $config ['clusterName'] . '/cgi-bin/cgiparam.exe?w=165&h=1&p=scanbutton.exe|' . $postURL . '"><br><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="165" height="70" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="http://127.0.0.1/aaa.swf" /><param name="loop" value="false" />    <param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="http://127.0.0.1/aaa.swf" loop="false" menu="false" quality="high" bgcolor="#eeeeee" width="200" height="70" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" /> ';
		} else {
			$scanButton = $scanOption . '<img src="http://' . $_SERVER ['HTTP_HOST'] . '/cgi-bin/cgiparam.exe?w=165&h=1&p=scanbutton.exe|' . $postURL . '"><br><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="165" height="70" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="http://127.0.0.1/aaa.swf" /><param name="loop" value="false" />    <param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="http://127.0.0.1/aaa.swf" loop="false" menu="false" quality="high" bgcolor="#eeeeee" width="200" height="70" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" /> ';
		}
		
		if (array_key_exists ( 'contextDMSElID', $_COOKIE )) {
			$contentElId = $_COOKIE ['contextDMSElID'];
		} else {
			$contentElId = '';
		}
		
		//Module สำหรับงานระบบ Document Flow
		$DFTransModule = array ('Awaiting' );
		
		if (in_array ( $callModuleType, $DFTransModule )) {
			//TODO: Not Implement
		}
		
		if ($sessionMgr->isGoverner () && $callModuleType == 'Awaiting') {
			$approveBtnDisabled = 'false';
		} else {
			$approveBtnDisabled = 'true';
		}
		
		$docRevisionSQL = "select distinct f_revision from tbl_doc_hist_value where f_doc_id = '{$docID}'";
		$rsRevision = $conn->Execute ( $docRevisionSQL );
		
		$revisionMenuName = 'DocRevision_' . $docID;
		
		//$revisionMenuName = "auditBookMenu_{$programID}";
		

		$clearCheckFunction = "Ext.getCmp('btnRevisionCurrent_{$docID}').checked = false;
        Ext.getCmp('btnRevisionCurrent_{$docID}'). setChecked(0);";
		$revisionMenu = "
        var {$revisionMenuName} = new Ext.menu.Menu({id: 'DocRevisionMain_{$docID}',
            items: [
            ";
		
		$revisionItem = "{
                id: 'btnRevisionCurrent_{$docID}',
                text:'Current Revision',
                //iconCls: 'infoIcon',
                //disabled: true,
                type: 'checkitem',
                checked: true  ,
                value: '0',       
                handler: function(e) {
                    clearRevision_{$docID}();
                    loadRevision('Current');     
                }
            },'-'";
		foreach ( $rsRevision as $row ) {
			checkKeyCase ( $row );
			$clearCheckFunction .= "\r\nExt.getCmp('btnRevision{$row['f_revision']}_{$docID}').checked = false;
            Ext.getCmp('btnRevision{$row['f_revision']}_{$docID}').setChecked(0);";
			if ($revisionItem != '') {
				$revisionItem .= ",{
                id: 'btnRevision{$row['f_revision']}_{$docID}',
                text:'Revision - {$row['f_revision']}',
                //iconCls: 'infoIcon',
                //disabled: true,
                type: 'checkitem',
                //value: '{$row['f_revision']}',
                checked: false  ,
                handler: function(e) {
                    clearRevision_{$docID}();
                    loadRevision('{$row['f_revision']}');     
                }
            }";
			} else {
				$revisionItem .= "{
                id: 'btnRevision{$row['f_revision']}_{$docID}',
                text:'Revision - {$row['f_revision']}',   
                //iconCls: 'infoIcon',
                //disabled: true,
                checked: false  ,
                type: 'checkitem',
               // value: '{$row['f_revision']}',       
                handler: function(e) {
                    clearRevision();
                    loadRevision('{$row['f_revision']}');
                }
            }";
			}
		}
		
		$revisionMenu .= "$revisionItem]});";
		
		if(!$config['enablePrintMasterDoc']){
			$hidePrintMaster = 'true';
		} else {
			$hidePrintMaster = 'false';
		}
		
		$assignBtnDisabled = 'false';
		$reportBtnDisable = 'false';
		$forwardButtonDisabled = 'true';
		$editButtonDisabled = 'true';
		$editButtonHide = 'true';
		
		$hideSearchButton = 'true';
		
		$hideAssignButton = 'true';
		$hideReportButton = 'false';
		$hideForwardButton = 'true';
		$hideApproveButton = 'false';
		
		$hideAttachButton = 'false';
		$hideScanButton = 'false';
		$hideDeleteButton = 'enable';
		$hideRefreshButton = 'false';
		
		$showAttachedCommand = true;
		$showJobReport = true;
		if ($callModuleType == 'CompletedItem' || $callModuleType == 'SGR') {
			$showJobReport = true;
		}
		
		if ($callModuleType == 'RI' || $callModuleType == 'ReceivedInternal' || $callModuleType == 'ReceivedExternal' || $callModuleType == 'ReceivedExternalGlobal') {
			list ( $mainID, $mainSeq, $tID ) = explode ( "_", $docRefTransID );
			if ($mainSeq == 1 && $tID == 1) {
				$editButtonDisabled = 'false';
				$editButtonHide = 'false';
			} else {
				$editButtonDisabled = 'true';
				$editButtonHide = 'true';
			}
		}
		
		$orderID = 0;
		if ($callModuleType == 'OrderReceived') {
			$assignBtnDisabled = 'true';
			$reportBtnDisable = 'false';
			
			if (array_key_exists ( 'docExtendParam', $_POST )) {
				if ($_POST ['docExtendParam'] != '' || ! is_null ( $_POST ['docExtendParam'] )) {
					$orderID = $_POST ['docExtendParam'];
				}
			}
		} else {
			$assignBtnDisabled = 'false';
			$reportBtnDisable = 'true';
			$hideReportButton = 'true';
		}
		
		//ซ่อนไปก่อน
		$hideAssignButton = 'true';
		$hideSearchButton = 'true';
		$hideApproveButton = 'true';
		$uniqueIdentifier = uniqid ();
		$moduleID = 'VC' . $uniqueIdentifier;
		
		//TODO: Check สิทธิ์แก้ไขเอกสารตาม Module
		if ($callModuleType == 'DMS' || $callModuleType == 'Search') {
			if (!$policy->canAttachDMS ()) {
				$hideAttachButton = 'true';
				if (!$policy->canScanDMS ()) {	
					$hideScanButton = 'true';
				}
			}
			$editButtonDisabled = 'false';
			$editButtonHide = 'false';
			$hideAssignButton = 'true';
			$hideReportButton = 'true';
			$hideForwardButton = 'true';
			$hideApproveButton = 'true';
			$hideSearchButton = 'false';
			$disableDeleteAttachJS = "Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').disable();";
		} elseif (!$policy->canAttach ()) {
			$hideAttachButton = 'true';
			$hideScanButton = 'true';
		}

		if ($docRefTransID != '') {
			if (ereg ( "_", $docRefTransID )) {
				$linkTransMode = true;
				list ( $transMainID, $transMainSeq, $transID ) = split ( "_", $docRefTransID );
			} else {
				$linkTransMode = false;
				$refID = $docRefTransID;
			}
		} else {
			$transMainID = 0;
			$transMainSeq = 0;
			$transID = 0;
			$linkTransMode = false;
		}
		
		//Record Read Attempt
		if ($callModuleType == 'CI') {
			//include_once 'ReadCirc.Entity.php';
			$readCirc = new ReadCircEntity ( );
			if ($readCirc->Load ( "f_trans_main_id = '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' and f_trans_recv_id = '{$transID}' and f_acc_id = '{$sessionMgr->getCurrentAccID()}' and f_role_id = '{$sessionMgr->getCurrentRoleID()}' " )) {
				if ($readCirc->f_read_stamp == 0) {
					$readCirc->f_read_stamp = time ();
					$readCirc->Update ();
				}
			}
		}
		
		/*
		 * ปิดการทำงานของ Mode แก้ไข และ แนบถ้าอยู่ใน Degrade Mode
		 */
		if ($sessionMgr->isDegradeMode ()) {
			$editButtonDisabled = 'true';
			$hideAttachButton = 'true';
			$hideScanButton = 'true';
			$hideDeleteButton = 'disable';
		}
		$editButtonDisabled = 'false';
		
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		$formPath = $config ['formPath'];
		$formID = $docMain->f_form_id;

		//create user ID สำหรับใช้เช็คในการเพิ่มลบเอกสารแนบ
		$idAttachment = $docMain->f_create_uid;

		//สำหรับทะเบียนกลางรับเข้า ให้ใช้ฟอร์ม 1Ex
		if($_POST['callModuleType'] == 'ReceivedExternalGlobal'){
			$formDesign = "{$formPath}designedForm_1Ex.html";
		}else{
			$formDesign = "{$formPath}designedForm_{$formID}.html";
		}

		$formContent = file_get_contents ( $formDesign );
		$formName = "viewinstance_{$docID}_{$uniqueIdentifier}";
		
		//บันทึกการเรียกดูลงเก็บไว้ใน Audit Log
		$currentPerson = new AccountEntity ( );
		$currentPerson->Load ( "f_acc_id = '{$sessionMgr->getCurrentAccID()}'" );
		$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} is Open document ID [{$docID}],Title[{$docMain->f_title}]";
		Logger::log ( 4, $docID, $logMessage, true, false );
		
		//		Logger::dump('checkout', $docMain->f_checkout_flag);
		//		Logger::dump('session',$_SESSION);
		if ($callModuleType == 'DMS' && ($docMain->f_checkout_flag == 1 && ($docMain->f_checkout_user != $_SESSION ['accID']))) {
			$editButtonDisabled = 'true';
			$editButtonHide = 'true';
			$hideAttachButton = 'true';
			$hideScanButton = 'true';
			$hideDeleteButton = 'disable';
			$hideRefreshButton = 'true';
		}

		/*เช็คเจ้าของเอกสาร
		if ($idAttachment != $sessionMgr->getCurrentAccID()) {
			$hideAttachButton = 'true';
			$hideScanButton = 'true';
		}*/
		
		$docData = new DocValueEntity ( );
		$docDataArray = $docData->Find ( "f_doc_id = '{$docID}' " );
		$fillFormJS = '';
		foreach ( $docDataArray as $docValue ) {
			$formStruct = new FormStructureEntity ( );
			$fldValue = addslashes ( $docValue->f_value );
			$fldValue = str_replace ( "\r", "\\r", $fldValue );
			$fldValue = str_replace ( chr ( 13 ), "\\r", $fldValue );
			$fldValue = str_replace ( "\n", "\\n", $fldValue );
			$fldValue = str_replace ( chr ( 10 ), "\\n", $fldValue );
			$formStruct->Load ( "f_form_id = '{$docMain->f_form_id}' and f_struct_id = '{$docValue->f_struct_id}'" );
			if (($formStruct->f_is_doc_no == 1) || ($formStruct->f_is_doc_date == 1)) {
				if ($docMain->f_create_uid != $sessionMgr->getCurrentAccID ()) {
					$fillFormJS .= "document.forms.{$formName}.{$formStruct->f_struct_name}.readOnly = true;";
					$fillFormJS .= "document.forms.{$formName}.{$formStruct->f_struct_name}.style.backgroundColor = '#dedede';";
				}
			}
			$fillFormJS .= "document.forms.{$formName}.{$formStruct->f_struct_name}.value = \"{$fldValue}\";";
			unset ( $formStruct );
		}

		$scanParam = 1;
		
		$attachName = "attachment{$docID}Store";
		$attachStore = $store->getAttachStore ( $docID, $attachName );
		//$receiverGrid = "<table><tr><td><div id=\"divSendToList{$docID}\"></div></td><td><div id=\"divSendCCList{$docID}\"></div></td></tr></table>";
		//$formHTML = "$receiverGrid<form id=\"{$formName}\" name=\"{$formName}\">$formContent</form>";
		//$ownerID = $docMain->f_create_uid;
		Logger::dump ( "DocMain", $docMain );
		$editCommandScript = "";
		$formHTML = "<form id=\"{$formName}\" name=\"{$formName}\">$formContent</form>";
		if ($showAttachedCommand) {
			$commandFinder = new CommandEntity ( );
			$commands = $commandFinder->Find ( "f_trans_main_id = '{$transMainID}' order by f_timestamp asc" );
			//Logger::dump('>>'.$transMainID);
			if (count ( $commands ) > 0) {
				$formHTML .= "<hr/>";
				foreach ( $commands as $command ) {
					if($command->f_acc_id == $sessionMgr->getCurrentAccID()) {
//						$recvGridName = 'gridReceivedItem';
						$editCommandScript .= "
						function editCommand_{$command->f_cmd_id}() {
							Ext.getCmp('editCommandTransType').setValue('RI');
							recvId = '".$docRefTransID."';
								//alert(recvId);
							Ext.getCmp('editCommandTransCode').setValue(recvId);
//                            Ext.getCmp('editCommandTransCode').setValue({$recvGridName}.getSelectionModel().getSelected().get('recvID'));
                            Ext.getCmp('editCommandID').setValue('{$command->f_cmd_id}');
							editCommandWindow.show();
						}
						";
						
						$editCommandLink = "<tr><td></td><td><a onclick=\"editCommand_{$command->f_cmd_id}()\" style=\"cursor: pointer;\">คลิกเพื่อแก้ไขคำสั่งการ</a></td></tr>";
					} else {
						$editCommandLink = "";
					}
					$role = new RoleEntity ( );
					$org = new OrganizeEntity ( );
					$cmdPerson = new AccountEntity ( );
					$orgCommand = new OrganizeEntity ( );
					Logger::debug($role);
					$role->Load ( "f_role_id = '{$command->f_cmd_role_id}'" );
					$org->Load ( "f_org_id = '{$command->f_cmd_org_id}'" );
					//$orgCommand->Load ( "f_org_id = {$role->f_org_id}" );
					$orgCommand->Load ( "f_org_id = '{$command->f_cmd_org_id}'" );
					$cmdPerson->Load ( "f_acc_id = '{$command->f_acc_id}'" );
					$command->f_command_text = addslashes($command->f_command_text);
					$role-> f_role_name = addslashes($role-> f_role_name);
					$cmdPerson->f_name = addslashes($cmdPerson->f_name);
					$cmdPerson->f_last_name = addslashes($cmdPerson->f_last_name);
					$formHTML .= "<table>
					
						<tr>
							<td bgcolor=\"#DEDEDE\">คำสั่งการ</td>
							<td>{$command->f_command_text}</td>
						</tr>
						<tr>
							<td bgcolor=\"#DEDEDE\">ผู้สั่งการ</td>
							<td>{$role-> f_role_name} ({$orgCommand->f_org_name})</td>
						</tr>
						<tr>
							<td bgcolor=\"#DEDEDE\">ผู้บันทึกสั่งการ</td>
							<td>{$cmdPerson->f_name} {$cmdPerson->f_last_name}</td>
						</tr>
						<tr>
							<td bgcolor=\"#DEDEDE\">วัน/เวลา</td>
							<td>{$util->getDateString($command->f_timestamp)},{$util->getTimeString($command->f_timestamp)}</td>
						</tr>
						{$editCommandLink}
					</table><br/>";
				}
				$formHTML .= "";
			}
		}
		
		if ($showJobReport) {
			
			//include_once "Order.Entity.php";
			$orderFinder = new OrderEntity ( );
			$orders = $orderFinder->Find ( "f_trans_main_id = '{$transMainID}'" );
			if (count ( $orders ) > 0) {
				$formHTML .= "<br/>ผลการมอบหมายงาน<hr/>";
			}
			foreach ( $orders as $order ) {
				$assignOrg = new OrganizeEntity ( );
				$assignPerson = new AccountEntity ( );
				
				$receivePerson = new AccountEntity ( );
				
				$assignOrg->Load ( "f_org_id = '{$order->f_org_id}'" );
				$assignPerson->Load ( "f_acc_id = '{$order->f_assign_uid}'" );
				$receivePerson->Load ( "f_acc_id = '{$order->f_received_uid}'" );
				if ($order->f_complete == 1) {
					$jobStatus = nl2br ( $order->f_report_text );
					if ( $order->f_close_timestamp != 0 ) {
						$closeTime = "                  
						<tr>
							<td bgcolor=\"#DEDEDE\">วัน/เวลา</td>
							<td>{$util->getDateString($order->f_close_timestamp)},{$util->getTimeString($order->f_close_timestamp)}</td>
						</tr> ";
					}
				} else {
					$jobStatus = "<font color=\"Red\">ระหว่างดำเนินการ</font>";
				}
				
				$formHTML .= "<table>
                    <tr>
                        <td bgcolor=\"#DEDEDE\">หน่วยงาน</td>
                        <td>{$assignOrg->f_org_name}</td>
                    </tr>
                    <tr>
                        <td bgcolor=\"#DEDEDE\">ผู้มอบหมาย</td>
                        <td>{$assignPerson-> f_name} {$assignPerson-> f_last_name}</td>
                    </tr>   
                    <tr>
                        <td bgcolor=\"#DEDEDE\">ผู้ดำเนินการ</td>
                        <td>{$receivePerson->f_name} {$receivePerson->f_last_name}</td>
                    </tr>
                    <tr>
                        <td bgcolor=\"#DEDEDE\">สถานะ/ผลการทำงาน</td>
                        <td>{$jobStatus}</td>
                    </tr>
					{$closeTime}
                </table><br/>";
			}
		}
		
		$searchMode = (array_key_exists ( 'searchMode', $_POST )) ? $_POST ['searchMode'] : 'DMSPortlet';
		
		$formHTML = str_replace ( "\r\n", "", $formHTML );
		$formHTML = str_replace ( "\t", "\\t", $formHTML );
		$formHTML = str_replace ( "\r", "\\r", $formHTML );
		$formHTML = str_replace ( chr ( 13 ), "\\r", $formHTML );
		$formHTML = str_replace ( "\n", "\\n", $formHTML );
		$formHTML = str_replace ( chr ( 10 ), "\\n", $formHTML );
		
		echo "<table width=\"100%\">
			<tr>
				<td width=\"75%\">
					<div id=\"documentViewUIDiv_{$moduleID}_{$docID}\" display=\"inline\"></div>
				</td>
				<td>
					<div id=\"documentViewThumbnailUIDiv_{$moduleID}_{$docID}\" display=\"inline\"></div>
				</td>
			</tr>
		</table>";
		
		echo "<script type=\"text/javascript\">
		$editCommandScript
		$attachStore
		$attachName.load({params:{start:0, limit:{$config['thumbnailPerPage']}}});
        {$revisionMenu}
        
        
        var autocompleteRevision{$moduleID}_{$docID} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/document-revision?id={$docID}'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'value'},                              
                {name: 'typeid'},
                {name: 'desc'}
            ])
        });     
        
        var autocompleteVersion{$moduleID}_{$docID} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/document-revision?id={$docID}'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'value'},                              
                {name: 'typeid'},
                {name: 'desc'}
            ])
        });                                     
        
         var resultRevision = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-revision\">',
                '<table width=\"90%\">',
                    '<tr><td><b><font size=\'-2\'>{name}</font></b></td></tr>',      
                    '<tr><td><font size=\'-2\'>By:{desc}</font></td></tr>',                 
                '</table>',               
            '</div></tpl>'
        );
        
        var resultVersion = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-version\">',
                '<table width=\"90%\">',
                    '<tr><td><b><font size=\'-2\'>{name}</font></b></td></tr>',      
                    '<tr><td><font size=\'-2\'>By:{desc}</font></td></tr>',                 
                '</table>',               
            '</div></tpl>'
        );
        
        function clearRevision_{$docID}() {
            {$clearCheckFunction}
        }
        
        function loadRevision(rev) {
            //alert(rev);
        }
        
		
		var frmUploadMain_{$moduleID}_{$docID} = new Ext.form.FormPanel({
	        id : 'frmUploadMain_{$moduleID}_{$docID}',
	        method : 'POST',
            fileUpload: true,
	        enctype : 'multipart/form-data',     
            defaults: {
                msgTarget: 'side'
            },
            labelWidth: 75,
			items: [new Ext.form.Hidden({
				//inputType: 'TextField',
				//inputType: 'Hidden',
                id: 'upload_{$moduleID}_{$docID}',
				value: {$docID},
				hideLabel: true,
				name: 'docID'
			}),new Ext.form.ComboBox({
		        store: methodStore,
		        displayField:'name',
		        fieldLabel: 'Method',
		        typeAhead: true,
		        valueField: 'id',
		        mode: 'local',
		        forceSelection: true,
		        triggerAction: 'all',
		        value: 1,
		        name: 'attachMethod',
		        hiddenName: 'attachMethodID',
		        selectOnFocus:true
		    }),new Ext.form.ComboBox({
		        store: revisionMethodStore,
		        displayField:'name',
		        fieldLabel: 'Update',
		        name: 'attachUpdate',
		        hiddenName: 'attachUpdateID',
		        typeAhead: true,
		        valueField: 'id',
		        mode: 'local',
		        forceSelection: true,
		        triggerAction: 'all',
		        value: 0,
		        selectOnFocus:true
		    }),new Ext.form.TextField({
		        fieldLabel: 'Page Start',
		        name: 'pageStart'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    })]
	    });
	    
	    var wndAttachMain_{$moduleID}_{$docID} = new Ext.Window({
			id: 'wndAttachMain_{$moduleID}_{$docID}',
			title: 'Attach File',
			width: 275,
			height: 270,
			minWidth: 275,
			minHeight: 270,
			resizable: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmUploadMain_{$moduleID}_{$docID},
			closable: false,
			modal: true,
			buttons: [{
				text: 'Add Attachment',
				handler: function() {
	    			wndAttachMain_{$moduleID}_{$docID}.hide();
					var myEl = frmUploadMain_{$moduleID}_{$docID}.getEl().dom;
					//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
     				frmUploadMain_{$moduleID}_{$docID}.getForm().getEl().dom.setAttribute('enctype','multipart/form-data');
     				//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
     				Ext.MessageBox.show({
                        msg: 'กำลังบันทึกเอกสารแนบ...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                	});
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-attach-cross',
		    			method: 'POST',
                        timeout: {$config['AJAXTimeout']},
		    			enctype: 'multipart/form-data',
		    			success: function(o) {
		    				Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            var responseMsg = '';
                            if(r.success == 1) {
                            	responseMsg = 'บันทึกเรียบร้อยแล้ว';
							} else {
								responseMsg = 'บันทึกผิดพลาด';
							}
							
                            Ext.MessageBox.show({
                                title: 'การบันทึกเอกสารแนบ',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                           		icon: Ext.MessageBox.INFO
							});
		    				{$attachName}.reload();
                            Ext.getCmp('frmUploadMain_{$moduleID}_{$docID}').getForm().getEl().dom.reset(); 
                            Ext.getCmp('frmUploadMain_{$moduleID}_{$docID}').getForm().setValues([
                                {id:'upload_{$moduleID}_{$docID}',value: '{$docID}'}
                            ]);
						},
		    			//failure: documentAddFailed,
		    			form: frmUploadMain_{$moduleID}_{$docID}.getForm().getEl()
		    		});
	    		}
			},{
				text: 'Cancel',
				handler: function() {
                    Ext.getCmp('frmUploadMain_{$moduleID}_{$docID}').getForm().getEl().dom.reset(); 
                    Ext.getCmp('frmUploadMain_{$moduleID}_{$docID}').getForm().setValues([
                        {id:'upload_{$moduleID}_{$docID}',value: '{$docID}'}
                    ]);
					wndAttachMain_{$moduleID}_{$docID}.hide();
				}
			}]
		});
		
		
		
									
		var wndScanMain_{$moduleID}_{$docID} = new Ext.Window({
			id: 'wndScanMain_{$moduleID}_{$docID}',
			title: 'Scan',
			width: 250,
			height: 250,
			minWidth: 250,
			minHeight: 250,
			resizable: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			//html: '<OBJECT classid=\"clsid:72A51A5B-C447-47A2-92CC-21F1F3C19246\" codebase=\"TwnProj1.ocx#version=1,0,0,0\" border=0 width=167 height=73 align=center hspace=0 vspace=0>	<PARAM NAME=\"Filename\"	VALUE=\"C:/Program Files/CDMSPlugins/TwainFTP.exe\">	<PARAM NAME=\"Parameter\" VALUE=\"{$config['ftp']['host']}|{$config['ftp']['port']}|{$config['ftp']['username']}|{$config['ftp']['password']}|{$_SESSION['accID']}|{$scanParam}\">Plug-in is not Install before use.</OBJECT>',
			html: '{$scanButton}',
			//items: frmUpload_{$docID},
			closable: false,
			modal: true,
			buttons: [{
				id: 'btnSaveScanMain_{$moduleID}_{$docID}',
				text: 'Commit',
				hidden: true,
				handler: function() {
					
	    			wndScanMain_{$moduleID}_{$docID}.hide();
	    			Ext.MessageBox.show({
                        msg: 'กำลังบันทึกเอกสารสแกน...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                	});
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-scan-cross',
		    			method: 'POST',
		    			enctype: 'multipart/form-data',
		    			success: function(o) {
		    				Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            var responseMsg = '';
                            if(r.success == 1) {
                            	responseMsg = 'บันทึกเรียบร้อยแล้ว';
							} else {
								responseMsg = 'บันทึกผิดพลาด';
							}
							
                            Ext.MessageBox.show({
                                title: 'การบันทึกเอกสารแนบ',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                           		icon: Ext.MessageBox.INFO
							});
		    				//Ext.getCmp('btnSaveScanMain_{$moduleID}_{$docID}').disable();
						},
		    			//failure: documentAddFailed,
		    			//form: frmUpload_{$docID}.getForm().getEl()
		    			params: {
		    				docID: {$docID}
						}
		    		});
		    		
	    		}
			},{
				text: 'Close',
				handler: function() {
					wndScanMain_{$moduleID}_{$docID}.hide();
				}
			}]
		});
		
		
		var documentViewer_{$moduleID}_{$docID} = new Ext.Panel({
		   	tbar: new Ext.Toolbar({
				id: 'documentViewerToolbar_{$moduleID}_{$docID}',
				height: 25				
			}),
			autoScroll : true,
		   	margins: '5 0 0 5',
		   	cmargins: '5 5 0 5',
		   	minSize: 100,
		   	maxSize: 300,
		   	//layout: 'fit',
		   	height: Ext.getCmp('tpAdmin').getInnerHeight(),
		   	renderTo: 'documentViewUIDiv_{$moduleID}_{$docID}',
		   	html: '{$formHTML}'
		});
		
		var documentAttachMain_{$moduleID}_{$docID} = new Ext.Panel({
		   	tbar: new Ext.Toolbar({
				id: 'attachmentToolbarMain_{$moduleID}_{$docID}',
				height: 25				
			}),
			autoScroll : true,
			cls: 'images-view',
			items: new Ext.DataView({
				//singleSelect: true,
	            store: {$attachName},
	            tpl: new Ext.XTemplate(
					'<tpl for=\".\">',
			            '<center><div class=\"thumb-wrap\" id=\"{name}\">',
					    '<div class=\"thumb\"><img src=\"{url}\" title=\"{name}\"><span class=\"x-editable\">By: {owner}<br/>Date: {date}<br/>Size: {sizeStr}<br/>Version: {version}</span></div>',
					    '<span class=\"x-editable\">{name}</span></div></center>',
			        '</tpl>',
			        '<div class=\"x-clear\"></div>'
				),
	            autoHeight:true,
                //loadMask: true,
	            multiSelect: true,
	            overClass:'x-view-over',
	            itemSelector:'div.thumb-wrap',
	            emptyText: 'No attachments',
	            listeners: {
                    'selectionchange' :  function (thisObj, selections ) { 
                        if(thisObj.getSelectionCount() == 1) {
							Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').{$hideDeleteButton}(); 
                        } 
						/*
                        if(thisObj.getSelectionCount() > 1) {
                            Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').disable(); 
                            Cookies.set('vdid',0);
                            Cookies.set('vpid',0);
                            Cookies.set('vpt','');
                        }
                        if(thisObj.getSelectionCount() ==0) { 
                             Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').disable(); 
                             Cookies.set('vdid',0);
                             Cookies.set('vpid',0);
                             Cookies.set('vpt','');
                        }
                        {$disableDeleteAttachJS}
						*/
                    },
                    'click' : function (thisObj,idx,node,evt) {
						  if (!Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').disabled) {
							if ('{$callModuleType}' == 'DMS' || '{$callModuleType}' == 'Search') {
								Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').enable()
							} else {
								if (thisObj.getRecord(node).get('createuid') == {$sessionMgr->getCurrentAccID()}) {
								//if ($idAttachment == {$sessionMgr->getCurrentAccID()}) {
								Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').enable()
							 } else {
								Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').disable()
							 }
						  }
						  }
						 Cookies.set('vdid',thisObj.getRecord(node).get('docid'));
                         Cookies.set('vpid',thisObj.getRecord(node).get('pageid'));
                         Cookies.set('vpt',thisObj.getRecord(node).get('name'));
                    },
					'dblclick' : function(thisObj,idx,node,evt) {
						popupAttachviewer(thisObj.getRecord(node).get('docid'),thisObj.getRecord(node).get('pageid'));
					}
				},
	
	            //plugins: [
	            //    new Ext.DataView.DragSelector(),
	            //    new Ext.DataView.LabelEditor({dataIndex: 'name'})
	            //],
	
	            prepareData: function(data){
	                data.shortName = Ext.util.Format.ellipsis(data.name, 16);
	                data.sizeString = Ext.util.Format.fileSize(data.size);
	                data.dateString = data.lastmod.format(\"m/d/Y g:i a\");
	                return data;
	            }
	        }),
			bbar: new Ext.PagingToolbar({
				id: 'attachmentToolbarMain2_{$moduleID}_{$docID}',
				height: 25,	
	            pageSize: {$config['thumbnailPerPage']},
	            store: {$attachName},
	            displayInfo: false
	        }),
		    margins: '5 0 0 5',
		    cmargins: '5 5 0 5',
		    minSize: 100,
		    maxSize: 300,
		   	layout: 'fit',
		   	height: Ext.getCmp('tpAdmin').getInnerHeight(),
		   	renderTo: 'documentViewThumbnailUIDiv_{$moduleID}_{$docID}'
		});
		
		var attachmentToolbarMain_{$moduleID}_{$docID} = Ext.getCmp('attachmentToolbarMain_{$moduleID}_{$docID}');
		
		attachmentToolbarMain_{$moduleID}_{$docID}.add({
            text:'Attach',
            iconCls: 'addIcon',
            disabled: {$hideAttachButton},
            handler: function(e) {
            	wndAttachMain_{$moduleID}_{$docID}.show();
			}
        },{
            text:'Scan',
            iconCls: 'addIcon',
            disabled: {$hideScanButton},
            handler: function(e) {
				wndScanMain_{$moduleID}_{$docID}.show();
				Ext.get('scanMethod').on('change',function() {
					//alert(Ext.get('scanMethod').getValue());
					Cookies.set('smethod',Ext.get('scanMethod').getValue());
				},this);
				Ext.get('scanUpdate').on('change',function() {
					//alert(Ext.get('scanUpdate').getValue());
					Cookies.set('supdate',Ext.get('scanUpdate').getValue());
				},this);
				Ext.get('scanPage').on('change',function() {
					//alert(Ext.get('scanPage').getValue());
					Cookies.set('spage',Ext.get('scanUpdate').getValue());
				},this);
            	//Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Delete',
            id: 'btnDeletePage_{$moduleID}_{$docID}',
            iconCls: 'deleteIcon',   
            disabled: true,
            handler: function(e) {
                Ext.MessageBox.confirm('{$lang['common']['confirm']}', 'ลบเอกสารแนบ[ '+Cookies.get('vpt')+']?', deleteSelectedPage);
			}
        },{
            text:'Refresh',
            iconCls: 'refreshIcon',
            disabled: {$hideRefreshButton},
            handler: function(){
            	//accountStore.load({params:{start:0, limit:8}});
            	{$attachName}.reload();
			}
        });
        
        function deleteSelectedPage(btn) {
            if(btn == 'yes') {
                //alert('deleted');
                Ext.MessageBox.show({
                    msg: 'Delete Attachment',
                    progressText: '{$lang['common']['processingText']}',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/document/delete-attach',
                    method: 'POST',
                    success: function() {
                        {$attachName}.reload() 
                        Ext.MessageBox.hide();
                    },
                    failure: function() {
                        Ext.MessageBox.hide();
                        {$attachName}.reload()   
                    },
                    params: {
                        'docID' : Cookies.get('vdid'),
                        'page': Cookies.get('vpid')
                    }
                });
            }
        }
        
        //Main Toolbar
        var documentViewerToolbar_{$moduleID}_{$docID} = Ext.getCmp('documentViewerToolbar_{$moduleID}_{$docID}');
        documentViewerToolbar_{$moduleID}_{$docID}.add({
	        	id: 'btnViewLog_{$moduleID}_{$docID}',
	            text:'{$lang['df']['viewlog']}',
	            iconCls: 'historyIcon',
	            disabled: false,
	            handler: function(e) {
	            	popupDocLog2('{$docID}');
				}
	   	},{
            text:'{$lang ['workitem'] ['assign']}',
            id: 'btnAssign_{$moduleID}_{$docID}',
            iconCls: 'assignIcon',
            disabled: {$assignBtnDisabled},
            hidden: {$hideAssignButton},
            handler: function(e) {
                assignOrderWindow.show();
                assignOrderForm.getForm().reset();
                assignOrderForm.getForm().setValues(
                [
                    {id: 'orderRecieverRefID',value: '{$docRefTransID}'}
                ]
                );
            }
            
        },{
            text:'{$lang ['workitem'] ['report']}',
            id: 'btnReport_{$moduleID}_{$docID}',
            iconCls: 'reportIcon',
            disabled: {$reportBtnDisable},
            hidden: {$hideReportButton},
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
                            reportOrderWindow.show();
                            reportOrderForm.getForm().reset();
                            reportOrderForm.getForm().setValues(
                            [
                                {id: 'reportOrderRefID',value: '{$orderID}'}
                            ]
                            ); 
                        }
                    }
                });
                
            }
        },{
            text:'{$lang ['workitem'] ['approve']}',
            id: 'btnApprove_{$moduleID}_{$docID}',
            hidden: {$hideApproveButton},
            iconCls: 'approveIcon',
            disabled: {$approveBtnDisabled},
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
                            Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['workitem']['approve']} ?', approveTransaction);
                        }
                    }
                });
            	
			}
        },{
            text:'Send',
            id: 'btnSendForward_{$moduleID}_{$docID}',
            iconCls: 'bmenu',
            hidden: {$hideForwardButton},
            disabled: {$forwardButtonDisabled},
            handler: function(e) {
            	forwardDFWindow.show();
				//wndSendList_{$docID}.show();
			}
        },{
            text:'พิมพ์ต้นฉบับ',
            id: 'btnPrintWord_{$docID}',
            hidden: {$hidePrintMaster},
            iconCls: 'wordIcon',
            handler: function(e) {
				//wndSendList_{$docID}.show();
				popupPrintJasper('{$docID}','word');
			}
        },{
            text:'ค้นหา',
            id: 'btnSearch_{$moduleID}_{$docID}',
            iconCls: 'searchIcon',
            hidden: {$hideSearchButton},
            handler: function(e) {
            	var tabMain = Ext.getCmp('tpAdmin');
		    	
		    	tabMain.add({
	            	id: 'tabSearch_{$formName}',//+Ext.getCmp('txtSearch').getValue(),
					title: '{$lang ['dms'] ['searchResult']}_{$formName}',//+'\"'+Ext.getCmp('txtSearch').getValue()+'\"',
					iconCls: 'searchIcon',
					closable:true,
					autoLoad: {
						url: '/{$config ['appName']}/portlet/get-portlet-content',
						method: 'POST',
						//form: Ext.get('viewinstance_{$docID}_{$uniqueIdentifier}'),
						form: document.getElementById('{$formName}'),
						params: {
								portletClass: '{$searchMode}',
								portletMethod: 'portletContent',
								keySearch: Url.encode('no'),
								formID: '{$formID}'
						},
						scripts: true
					}
				}).show();
			}
        },{
            text:'บันทึก',
            id: 'btnSaveInstanceMain_{$moduleID}_{$docID}',
            iconCls: 'saveIcon',
            disabled: {$editButtonDisabled},
            hidden: {$editButtonHide},  
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
                            Ext.MessageBox.show({
                                msg: 'กำลังแก้ไขเอกสาร...',
                                progressText: 'Saving...',
                                width:300,
                                wait:true,
                                waitConfig: {interval:200},
                                icon:'ext-mb-download'
                            });
                            Ext.Ajax.request({
                                url: '/{$config ['appName']}/document/edit-document',
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
                                        if(r.refresh == 1) {
                                            DMSTree.getNodeById(r.id).parentNode.reload();
                                        }
                                        Ext.MessageBox.show({
                                            title: 'แก้ไขเอกสาร',
                                            msg: 'แก้ไขเรียบร้อยแล้ว',
                                            buttons: Ext.MessageBox.OK,
                                               icon: Ext.MessageBox.INFO
                                        });
                                },
                                form: Ext.get('viewinstance_{$docID}_{$uniqueIdentifier}'),
                                params: { 
                                    formID: {$formID}
                                    ,instanceID: {$docID}
                                }
                            });
                        }
                    }
                });
            	
			}
        },'-',
        new Ext.form.ComboBox({
            store: autocompleteRevision{$moduleID}_{$docID},
            //fieldLabel: 'บันทึกรายชื่อ',
            displayField:'name',
            //style: autoFieldStyle,
            typeAhead: false,
            emptyText: 'Revision',
            tabIndex: 1,
            loadingText: '{$lang['common']['searching']}',
            width: 130,
            //pageSize:10,
            hideTrigger: false ,
            id: 'revisionRetriever{$moduleID}_{$docID}',                         
            name: 'revisionRetriever{$moduleID}_{$docID}',
            tpl: resultRevision,
            //lazyInit: true,
            //lazyRender: true,
            minChars: 0,
            shadow: false,
            autoLoad: true,
            mode: 'remote',
            itemSelector: 'div.search-revision'
                                
        }),'-',new Ext.form.ComboBox({
            hidden: true,
            store: autocompleteVersion{$moduleID}_{$docID},
            //fieldLabel: 'บันทึกรายชื่อ',
            displayField:'name',
            //style: autoFieldStyle,
            typeAhead: false,
            emptyText: 'Version',
            tabIndex: 1,
            loadingText: '{$lang['common']['searching']}',
            width: 130,
            //pageSize:10,
            hideTrigger: false ,
            id: 'versionRetriever{$moduleID}_{$docID}',                         
            name: 'versionRetriever{$moduleID}_{$docID}',
            tpl: resultVersion,
            //lazyInit: true,
            //lazyRender: true,
            minChars: 0,
            shadow: false,
            autoLoad: true,
            mode: 'remote',
            itemSelector: 'div.search-version'
                                
        })
        );
		documentViewer_{$moduleID}_{$docID}.body = '$formHTML';
		 
		documentViewer_{$moduleID}_{$docID}.render();
		documentAttachMain_{$moduleID}_{$docID}.render(); ";
		
		if($callModuleType == 'ReceivedExternalGlobal'){
			echo "
			$(document).ready(function(){
				$('#atc_sender').autocomplete('/{$config ['appName']}/auto-complete/jquery-contact-list/',{
					//mustMatch: true,
					autoFill: false,
					matchContains: true
				});
			});	";
		}/*else{
			echo "
			$(document).ready(function(){
				$('#atc_sender').autocomplete('');
			});	";
		}*/
		echo "
		function approveTransaction(btn) {
		if(btn=='yes') {
			Ext.Ajax.request({
					url: '/{$config ['appName']}/df-action/approve-recv-trans',
				   	method: 'POST',
				    success: function(o){
				    	Ext.MessageBox.hide();
						var r = Ext.decode(o.responseText);
						var regNo = '';
						var sendDate = '';
                        
						Ext.MessageBox.show({
						    title: 'การอนุมัติ',
						    msg: 'การอนุมัติเรียบร้อย',
						    buttons: Ext.MessageBox.OK,
						    icon: Ext.MessageBox.INFO
						});
						Ext.getCmp('forwardDFForm').getForm().reset();
					},
				    failure: function(r,o) {
				    	Ext.MessageBox.hide();
						Ext.MessageBox.show({
							title: 'Problem',
						    msg: 'ไม่สามารถทำการอนุมัติได้',
						    buttons: Ext.MessageBox.OK,
						    icon: Ext.MessageBox.ERROR
						});
					},
					params: { docID: '{$docID}' ,transCode: '{$docRefTransID}'}
				});
			}
		}
        
        Ext.getCmp('revisionRetriever{$moduleID}_{$docID}').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            //Cookies.set('drv',dataRecord.data.value);
            Ext.Ajax.request({
                url: '/{$config ['appName']}/document/load-revision',
                method: 'POST',
                success: function(o){
                      Ext.MessageBox.hide();
                      var r = Ext.decode(o.responseText);
                      //alert(r.length);
                      //alert(r.results);     
                      for(i = 0 ;i<r.results.length;i++) {
                            //alert(r.results[i].struct);
                            //alert(r.results[i].val);
                           // var idField = r.results[i].struct;
                            
                            var statement = 'document.forms.{$formName}.'+r.results[i].struct+'.value=\''+r.results[i].val+'\';'; 
                            //alert(el);                          
                            eval(statement);
                      }          
                                        
                      if(dataRecord.data.value == 0) {
                            Ext.MessageBox.show({
                                title: 'Load Revision',
                                msg: 'Load current revision completed',
                                buttons: Ext.MessageBox.OK,
                                icon: Ext.MessageBox.INFO
                            });
                        } else {
                            Ext.MessageBox.show({
                                title: 'Load Revision',
                                msg: 'Load revision '+dataRecord.data.value+' completed',
                                buttons: Ext.MessageBox.OK,
                                icon: Ext.MessageBox.INFO
                            });
                        }   
                    Ext.getCmp('receiveInternalForm').getForm().reset();
                },
                failure: function(r,o) {
                    
                    
                    Ext.MessageBox.hide();

                    Ext.MessageBox.show({
                        title: 'Problem',
                        msg: 'Unalble to load rivision',
                        buttons: Ext.MessageBox.OK,
                        //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                        icon: Ext.MessageBox.ERROR
                    });
                },
                params: {id: '{$docID}','rev': dataRecord.data.value}
                //form: Ext.getCmp('receiveInternalForm').getForm().getEl()
            });
            //alert(Cookies.get('drv'));
        },this);
				
		</script>";
		echo "<script>$fillFormJS</script>";
	}
	
	/**
	 * action /view-announce ทำการแสดง คำสั่ง/ประกาศ
	 *
	 */
	public function viewAnnounceAction() {
		global $config;
		//global $sequence;
		global $store;
		global $lang;
		global $util;
		global $sessionMgr;
		
		checkSessionPortlet ();
		
		//include_once 'Announce.Entity.php';
		//include_once 'AnnounceCategory.Entity.php';
		//include_once 'DocMain.Entity.php';
		//include_once 'DocValue.Entity.php';
		//include_once 'Form.Entity.php';
		//include_once 'FormStructure.Entity.php';
		//include_once 'Command.Entity.php';
		//include_once 'Account.Entity.php';
		//include_once 'Role.Entity.php';
		//include_once 'Organize.Entity.php';
		

		global $conn;
		
		$callModuleType = $_POST ['callModuleType'];
		$docID = $_POST ['docID'];
		$docRefTransMode = $_POST ['callModuleType'];
		$docRefTransID = $_POST ['docRefTransID'];
		$code = "{$docID}_{$sessionMgr->getCurrentAccID()}";
		$postURL = 'http://' . $_SERVER ['HTTP_HOST'] . '/' . $config ['appName'] . '/document/add-scan-announce?code=' . $code;
		if ($config ['clusterMode']) {
			$scanButton = '<img src="http://' . $config ['clusterName'] . '/cgi-bin/cgiparam.exe?w=165&h=1&p=scanbutton.exe|' . $postURL . '"><br><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="165" height="70" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="http://127.0.0.1/aaa.swf" /><param name="loop" value="false" />    <param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="http://127.0.0.1/aaa.swf" loop="false" menu="false" quality="high" bgcolor="#eeeeee" width="200" height="70" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" /> ';
		} else {
			$scanButton = '<img src="http://' . $_SERVER ['HTTP_HOST'] . '/cgi-bin/cgiparam.exe?w=165&h=1&p=scanbutton.exe|' . $postURL . '"><br><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="165" height="70" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="http://127.0.0.1/aaa.swf" /><param name="loop" value="false" />    <param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="http://127.0.0.1/aaa.swf" loop="false" menu="false" quality="high" bgcolor="#eeeeee" width="200" height="70" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" /> ';
		}
		
		if (array_key_exists ( 'contextDMSElID', $_COOKIE )) {
			$contentElId = $_COOKIE ['contextDMSElID'];
		} else {
			$contentElId = '';
		}
		
		//Module สำหรับงานระบบ Document Flow
		$DFTransModule = array ('Awaiting' );
		
		if (in_array ( $callModuleType, $DFTransModule )) {
			//TODO: Not Implement
		}
		
		if ($sessionMgr->isGoverner () && $callModuleType == 'Awaiting') {
			$approveBtnDisabled = 'false';
		} else {
			$approveBtnDisabled = 'true';
		}
		
		$docRevisionSQL = "select distinct f_revision from tbl_doc_hist_value where f_doc_id = '{$docID}'";
		$rsRevision = $conn->Execute ( $docRevisionSQL );
		
		$revisionMenuName = 'DocRevision_' . $docID;
		
		$clearCheckFunction = "Ext.getCmp('btnRevisionCurrent_{$docID}').checked = false;
        Ext.getCmp('btnRevisionCurrent_{$docID}'). setChecked(0);";
		$revisionMenu = "
        var {$revisionMenuName} = new Ext.menu.Menu({id: 'DocRevisionMain_{$docID}',
            items: [
            ";
		
		$revisionItem = "{
                id: 'btnRevisionCurrent_{$docID}',
                text:'Current Revision',
                //iconCls: 'infoIcon',
                //disabled: true,
                type: 'checkitem',
                checked: true  ,
                value: '0',       
                handler: function(e) {
                    clearRevision_{$docID}();
                    loadRevision('Current');     
                }
            },'-'";
		foreach ( $rsRevision as $row ) {
			checkKeyCase ( $row );
			$clearCheckFunction .= "\r\nExt.getCmp('btnRevision{$row['f_revision']}_{$docID}').checked = false;
            Ext.getCmp('btnRevision{$row['f_revision']}_{$docID}').setChecked(0);";
			if ($revisionItem != '') {
				$revisionItem .= ",{
                id: 'btnRevision{$row['f_revision']}_{$docID}',
                text:'Revision - {$row['f_revision']}',
                //iconCls: 'infoIcon',
                //disabled: true,
                type: 'checkitem',
                //value: '{$row['f_revision']}',
                checked: false  ,
                handler: function(e) {
                    clearRevision_{$docID}();
                    loadRevision('{$row['f_revision']}');     
                }
            }";
			} else {
				$revisionItem .= "{
                id: 'btnRevision{$row['f_revision']}_{$docID}',
                text:'Revision - {$row['f_revision']}',   
                //iconCls: 'infoIcon',
                //disabled: true,
                checked: false  ,
                type: 'checkitem',
               // value: '{$row['f_revision']}',       
                handler: function(e) {
                    clearRevision();
                    loadRevision('{$row['f_revision']}');
                }
            }";
			}
		}
		
		$revisionMenu .= "$revisionItem]});";
		
		$assignBtnDisabled = 'false';
		$reportBtnDisable = 'false';
		$forwardButtonDisabled = 'true';
		$editButtonDisabled = 'true';
		$editButtonHide = 'true';
		
		$hideSearchButton = 'true';
		
		$hideAssignButton = 'true';
		$hideReportButton = 'false';
		$hideForwardButton = 'true';
		$hideApproveButton = 'false';
		
		$hideAttachButton = 'false';
		$hideScanButton = 'false';
		$hideDeleteButton = 'false';
		$hideRefreshButton = 'false';
		
		$showAttachedCommand = true;
		$showJobReport = true;
		if ($callModuleType == 'CompletedItem' || $callModuleType == 'SGR') {
			$showJobReport = true;
		}
		
		if ($callModuleType == 'RI' || $callModuleType == 'ReceivedInternal' || $callModuleType == 'ReceivedExternal' || $callModuleType == 'ReceivedExternalGlobal') {
			list ( $mainID, $mainSeq, $tID ) = explode ( "_", $docRefTransID );
			if ($mainSeq == 1 && $tID == 1) {
				$editButtonDisabled = 'false';
				$editButtonHide = 'false';
			} else {
				$editButtonDisabled = 'true';
				$editButtonHide = 'true';
			}
		}
		
		$orderID = 0;
		if ($callModuleType == 'OrderReceived') {
			$assignBtnDisabled = 'true';
			$reportBtnDisable = 'false';
			
			if (array_key_exists ( 'docExtendParam', $_POST )) {
				if ($_POST ['docExtendParam'] != '' || ! is_null ( $_POST ['docExtendParam'] )) {
					$orderID = $_POST ['docExtendParam'];
				}
			}
		} else {
			$assignBtnDisabled = 'false';
			$reportBtnDisable = 'true';
			$hideReportButton = 'true';
		}
		
		//ซ่อนไปก่อน
		$hideAssignButton = 'true';
		$hideSearchButton = 'true';
		$hideApproveButton = 'true';
		
		//TODO: Check สิทธิ์แก้ไขเอกสารตาม Module
		if ($callModuleType == 'DMS' || $callModuleType == 'Search') {
			$editButtonDisabled = 'false';
			$editButtonHide = 'false';
			$hideAssignButton = 'true';
			$hideReportButton = 'true';
			$hideForwardButton = 'true';
			$hideApproveButton = 'true';
			$hideSearchButton = 'false';
		}
		
		$uniqueIdentifier = uniqid ();
		$moduleID = 'VC' . $uniqueIdentifier;
		
		if ($docRefTransID != '') {
			if (ereg ( "_", $docRefTransID )) {
				$linkTransMode = true;
				list ( $transMainID, $transMainSeq, $transID ) = split ( "_", $docRefTransID );
			} else {
				$linkTransMode = false;
				$refID = $docRefTransID;
			}
		} else {
			$transMainID = 0;
			$transMainSeq = 0;
			$transID = 0;
			$linkTransMode = false;
		}
		
		//Record Read Attempt
		if ($callModuleType == 'CI') {
			//include_once 'ReadCirc.Entity.php';
			$readCirc = new ReadCircEntity ( );
			$readCirc->Load ( "f_trans_main_id = '{$transMainID}' and f_trans_main_seq = '{$transMainSeq}' and f_trans_recv_id = '{$transID}' and f_acc_id = '{$sessionMgr->getCurrentAccID()}' and f_role_id = '{$sessionMgr->getCurrentRoleID()}' " );
			if ($readCirc->f_read_stamp == 0) {
				$readCirc->f_read_stamp = time ();
				$readCirc->Update ();
			}
		}
		
		$announce = new AnnounceEntity ( );
		$announce->Load ( "f_announce_id = '{$docID}'" );
		$announceCat = new AnnounceCategoryEntity ( );
		$announceCat->Load ( "f_announce_cat_id = '{$announce->f_announce_category}'" );
		$signUser = new AccountEntity ( );
		if (! $signUser->Load ( "f_acc_id = '{$announce->f_sign_uid}'" )) {
			$signUserName = "";
		} else {
			$signUserName = "{$signUser->f_name} {$signUser->f_last_name}";
		}
		$signRole = new RoleEntity ( );
		if (! $signRole->Load ( "f_role_id = '{$announce->f_sign_role}'" )) {
			$signRoleName = "";
		} else {
			$signRoleName = $signRole->f_role_name;
		}
		
		$docMain = new DocMainEntity ( );
		$docMain->Load ( "f_doc_id = '{$docID}'" );
		$formPath = $config ['formPath'];
		$formID = 1;
		$formDesign = "{$formPath}/announce_Standard.html";
		$formContent = file_get_contents ( $formDesign );
		$formName = "viewAnnnounce_{$docID}_{$uniqueIdentifier}";
		$editButtonDisabled = 'false';
		$editButtonHide = 'false';
		//บันทึกการเรียกดูลงเก็บไว้ใน Audit Log
		$currentPerson = new AccountEntity ( );
		$currentPerson->Load ( "f_acc_id = '{$sessionMgr->getCurrentAccID()}'" );
		$logMessage = "{$currentPerson->f_name} {$currentPerson->f_last_name} is Open Announce ID [{$docID}],Title[{$docMain->f_title}]";
		Logger::log ( 4, $docID, $logMessage, true, false );
		
		//		Logger::dump('checkout', $docMain->f_checkout_flag);
		//		Logger::dump('session',$_SESSION);
		if ($callModuleType == 'DMS' && ($docMain->f_checkout_flag == 1 && ($docMain->f_checkout_user != $_SESSION ['accID']))) {
			$editButtonDisabled = 'true';
			$editButtonHide = 'true';
			$hideAttachButton = 'true';
			$hideScanButton = 'true';
			$hideDeleteButton = 'true';
			$hideRefreshButton = 'true';
		}
		
		$docData = new DocValueEntity ( );
		$docDataArray = $docData->Find ( "f_doc_id = '{$docID}' " );
		$fillFormJS = '';
		/*
		foreach ( $docDataArray as $docValue ) {
			$formStruct = new FormStructureEntity ( );
			$fldValue = $docValue->f_value;
			$fldValue = str_replace("\r","\\r",$fldValue);
            $fldValue = str_replace(chr(13),"\\r",$fldValue);  
			$fldValue = str_replace("\n","\\n",$fldValue);
            $fldValue = str_replace(chr(10),"\\n",$fldValue);  
			$formStruct->Load ( "f_form_id = '{$docMain->f_form_id}' and f_struct_id = '{$docValue->f_struct_id}'" );
            if(($formStruct->f_is_doc_no == 1) || ($formStruct->f_is_doc_date ==1) ) {
                $fillFormJS .= "document.forms.{$formName}.{$formStruct->f_struct_name}.readOnly = true;";  
                $fillFormJS .= "document.forms.{$formName}.{$formStruct->f_struct_name}.style.backgroundColor = '#dedede';";
                //$fillFormJS .= "document.forms.{$formName}.{$formStruct->f_struct_name}.setAttribute('bgColor','#dedede');";  
            }
			//$fillFormJS .= "Ext.get(\"{$formStruct->f_struct_name}\").set(\"value\",\"{$fldValue}\");";
			$fillFormJS .= "document.forms.{$formName}.{$formStruct->f_struct_name}.value = \"{$fldValue}\";";
			unset ( $formStruct );
		}
        */
		if ($announce->f_announce_type != 1) {
			$fillFormJS .= "document.getElementById('yearRow').style.display = 'none';";
		}
		switch ($announce->f_announce_type) {
			case 0 :
				$fillFormJS .= "document.forms.{$formName}.announceType.value = \"คำสั่ง\";";
				$announceTypeName = "คำสั่ง";
				break;
			case 1 :
				$fillFormJS .= "document.forms.{$formName}.announceType.value = \"ระเบียบ\";";
				$announceTypeName = "ระเบียบ";
				break;
			case 2 :
				$fillFormJS .= "document.forms.{$formName}.announceType.value = \"ประกาศ\";";
				$announceTypeName = "ประกาศ";
				break;
			case 3 :
				$fillFormJS .= "document.forms.{$formName}.announceType.value = \"ข้อบังคับ\";";
				$announceTypeName = "ข้อบังคับ";
				break;
			case 4 :
				$fillFormJS .= "document.forms.{$formName}.announceType.value = \"อื่นๆ\";";
				$announceTypeName = "อื่นๆ";
				break;
		}
		$fillFormJS .= "document.forms.{$formName}.announceID.value = \"{$announce->f_announce_id}\";";
		$fillFormJS .= "document.forms.{$formName}.announceCatName.value = \"{$announceCat->f_name}\";";
		$fillFormJS .= "document.forms.{$formName}.announceCatID.value = \"{$announce->f_announce_category}\";";
		$fillFormJS .= "document.forms.{$formName}.announceOrgName.value = \"{$announce->f_announce_org_name}\";";
		$fillFormJS .= "document.forms.{$formName}.announceOrgID.value = \"{$announce->f_announce_org_id}\";";
		$fillFormJS .= "document.forms.{$formName}.announceYear.value = \"{$announce->f_year}\";";
		$fillFormJS .= "document.forms.{$formName}.announceNo.value = \"{$announce->f_announce_no}/{$announce->f_year}\";";
		$fillFormJS .= "document.forms.{$formName}.announceDate.value = \"{$announce->f_announce_date}\";";
		$announceTitle = addslashes ( $announce->f_title );
		
		$fillFormJS .= "document.forms.{$formName}.announceTitle.value = \"{$announceTitle}\";";
		
		$announceDetail = addslashes ( $announce->f_detail );
		$announceDetail = str_replace ( "\r", "\\r", $announceDetail );
		$announceDetail = str_replace ( chr ( 13 ), "\\r", $announceDetail );
		$announceDetail = str_replace ( "\n", "\\n", $announceDetail );
		$announceDetail = str_replace ( chr ( 10 ), "\\n", $announceDetail );
		
		// Modify by SLC045
		// add create user cat modify announce 
		$editButtonAnnounceDisabled = 'true';
		$userAccID = $sessionMgr->getCurrentAccID();
		if( $userAccID == $announce->f_announce_user ) {
			$editButtonAnnounceDisabled = 'false';
		}
		
		$fillFormJS .= "document.forms.{$formName}.announceDetail.value = \"{$announceDetail}\";";
		$fillFormJS .= "document.forms.{$formName}.announceSignName.value = \"{$signUserName}\";";
		$fillFormJS .= "document.forms.{$formName}.announceSignRole.value = \"{$signRoleName}\";";
		$fillFormJS .= "document.forms.{$formName}.announceRemark.value = \"{$announce->f_remark}\";";
		
		$scanParam = 1;
		
		$attachName = "attachment{$docID}Store";
		$attachStore = $store->getAnnounceAttachStore ( $docID, $attachName );
		//$receiverGrid = "<table><tr><td><div id=\"divSendToList{$docID}\"></div></td><td><div id=\"divSendCCList{$docID}\"></div></td></tr></table>";
		//$formHTML = "$receiverGrid<form id=\"{$formName}\" name=\"{$formName}\">$formContent</form>";
		$formContent = addslashes($formContent);
		$formHTML = "<form id=\"{$formName}\" name=\"{$formName}\">$formContent</form>";
		if ($showAttachedCommand) {
			$commandFinder = new CommandEntity ( );
			$commands = $commandFinder->Find ( "f_trans_main_id = '{$transMainID}' order by f_timestamp asc" );
			if (count ( $commands ) > 0) {
				$formHTML .= "<hr/>";
				foreach ( $commands as $command ) {
					$role = new RoleEntity ( );
					$org = new OrganizeEntity ( );
					$cmdPerson = new AccountEntity ( );
					
					$role->Load ( "f_role_id = '{$command->f_cmd_role_id}'" );
					$org->Load ( "f_org_id = '{$command->f_cmd_org_id}'" );
					$cmdPerson->Load ( "f_acc_id = '{$command->f_acc_id}'" );
					$formHTML .= "<table>
						<tr>
							<td bgcolor=\"#DEDEDE\">คำสั่งการ</td>
							<td>{$command->f_command_text}</td>
						</tr>
						<tr>
							<td bgcolor=\"#DEDEDE\">ผู้สั่งการ</td>
							<td>{$role-> f_role_name}</td>
						</tr>
						<tr>
							<td bgcolor=\"#DEDEDE\">ผู้บันทึกสั่งการ</td>
							<td>{$cmdPerson->f_name} {$cmdPerson->f_last_name}</td>
						</tr>
						<tr>
							<td bgcolor=\"#DEDEDE\">วัน/เวลา</td>
							<td>{$util->getDateString($command->f_timestamp)},{$util->getTimeString($command->f_timestamp)}</td>
						</tr>
					</table><br/>";
				}
				$formHTML .= "";
			}
		}
		
		$searchMode = (array_key_exists ( 'searchMode', $_POST )) ? $_POST ['searchMode'] : 'DMSPortlet';
		
		$formHTML = str_replace ( "\r\n", "", $formHTML );
		$formHTML = str_replace ( "\t", "\\t", $formHTML );
		$formHTML = str_replace ( "\r", "\\r", $formHTML );
		$formHTML = str_replace ( chr ( 13 ), "\\r", $formHTML );
		$formHTML = str_replace ( "\n", "\\n", $formHTML );
		$formHTML = str_replace ( chr ( 10 ), "\\n", $formHTML );
		
		/*
			*
		*/
		echo "<table width=\"100%\">
			<tr>
				<td width=\"75%\">
					<div id=\"documentViewUIDiv_{$moduleID}_{$docID}\" display=\"inline\"></div>
				</td>
				<td>
					<div id=\"documentViewThumbnailUIDiv_{$moduleID}_{$docID}\" display=\"inline\"></div>
				</td>
			</tr>
		</table>";
		
		echo "<script type=\"text/javascript\">
		
		
		{$attachStore}
		{$attachName}.load({params:{start:0, limit:{$config['thumbnailPerPage']}}});
        {$revisionMenu}

        var autocompleteRevision{$moduleID}_{$docID} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/document-revision?id={$docID}'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'value'},                              
                {name: 'typeid'},
                {name: 'desc'}
            ])
        });     
        
        var autocompleteVersion{$moduleID}_{$docID} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/document-revision?id={$docID}'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'value'},                              
                {name: 'typeid'},
                {name: 'desc'}
            ])
        });                                     
        
         var resultRevision = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-revision\">',
                '<table width=\"90%\">',
                    '<tr><td><b><font size=\'-2\'>{name}</font></b></td></tr>',      
                    '<tr><td><font size=\'-2\'>By:{desc}</font></td></tr>',                 
                '</table>',               
            '</div></tpl>'
        );
        
        var resultVersion = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-version\">',
                '<table width=\"90%\">',
                    '<tr><td><b><font size=\'-2\'>{name}</font></b></td></tr>',      
                    '<tr><td><font size=\'-2\'>By:{desc}</font></td></tr>',                 
                '</table>',               
            '</div></tpl>'
        );
        
        function clearRevision_{$docID}() {
            {$clearCheckFunction}
        }
        
        function loadRevision(rev) {
            //alert(rev);
        }
        
        
		
		var frmUploadMain_{$moduleID}_{$docID} = new Ext.form.FormPanel({
	        id : \"frmUploadMain_{$moduleID}_{$docID}\",
	        method : 'POST',
	        enctype : \"multipart/form-data\",
			items: [new Ext.form.Hidden({
				//inputType: 'TextField',
				//inputType: 'Hidden',
                id: 'upload_{$moduleID}_{$docID}',
				value: {$docID},
				hideLabel: true,
				name: 'docID'
			}),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    }),new Ext.form.TextField({
		        allowBlank: false,
		        inputType: 'file',
		        name: 'upload_file[]',
		        hideLabel: true,
		        blankText: 'Please choose a file'
		    })]
	    });
	    
	    var wndAttachMain_{$moduleID}_{$docID} = new Ext.Window({
			id: 'wndAttachMain_{$moduleID}_{$docID}',
			title: 'Attach File',
			width: 265,
			height: 200,
			minWidth: 265,
			minHeight: 200,
			resizable: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: frmUploadMain_{$moduleID}_{$docID},
			closable: false,
			modal: true,
			buttons: [{
				text: 'Add Attachment',
				handler: function() {
	    			wndAttachMain_{$moduleID}_{$docID}.hide();
					var myEl = frmUploadMain_{$moduleID}_{$docID}.getEl().dom;
					//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
     				frmUploadMain_{$moduleID}_{$docID}.getForm().getEl().dom.setAttribute('enctype','multipart/form-data');
     				//alert(frmUpload.getForm().getEl().dom.getAttribute('enctype'));
     				Ext.MessageBox.show({
                        msg: 'กำลังบันทึกเอกสารแนบ...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                	});
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-announce-attach',
		    			method: 'POST',
                        timeout: {$config['AJAXTimeout']},
		    			enctype: 'multipart/form-data',
		    			success: function(o) {
		    				Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            var responseMsg = '';
                            if(r.success == 1) {
                            	responseMsg = 'บันทึกเรียบร้อยแล้ว';
							} else {
								responseMsg = 'บันทึกผิดพลาด';
							}
							
                            Ext.MessageBox.show({
                                title: 'การบันทึกเอกสารแนบ',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                           		icon: Ext.MessageBox.INFO
							});
		    				$attachName.reload();
							//frmUploadMain_{$moduleID}_{$docID}.getForm().reset();
                            Ext.getCmp('frmUploadMain_{$moduleID}_{$docID}').getForm().getEl().dom.reset(); 
                            Ext.getCmp('frmUploadMain_{$moduleID}_{$docID}').getForm().setValues([
                                {id:'upload_{$moduleID}_{$docID}',value: '{$docID}'}
                            ]);
						},
		    			//failure: documentAddFailed,
		    			form: frmUploadMain_{$moduleID}_{$docID}.getForm().getEl()
		    		});
	    		}
			},{
				text: 'Cancel',
				handler: function() {
					wndAttachMain_{$moduleID}_{$docID}.hide();
                    Ext.getCmp('frmUploadMain_{$moduleID}_{$docID}').getForm().getEl().dom.reset(); 
                    Ext.getCmp('frmUploadMain_{$moduleID}_{$docID}').getForm().setValues([
                        {id:'upload_{$moduleID}_{$docID}',value: '{$docID}'}
                    ]);
				}
			}]
		});
		
		
		
									
		var wndScanMain_{$moduleID}_{$docID} = new Ext.Window({
			id: 'wndScanMain_{$moduleID}_{$docID}',
			title: 'Scan',
			width: 200,
			height: 200,
			minWidth: 200,
			minHeight: 200,
			resizable: true,
			layout: 'fit',
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			//html: '<OBJECT classid=\"clsid:72A51A5B-C447-47A2-92CC-21F1F3C19246\" codebase=\"TwnProj1.ocx#version=1,0,0,0\" border=0 width=167 height=73 align=center hspace=0 vspace=0>	<PARAM NAME=\"Filename\"	VALUE=\"C:/Program Files/CDMSPlugins/TwainFTP.exe\">	<PARAM NAME=\"Parameter\" VALUE=\"{$config['ftp']['host']}|{$config['ftp']['port']}|{$config['ftp']['username']}|{$config['ftp']['password']}|{$_SESSION['accID']}|{$scanParam}\">Plug-in is not Install before use.</OBJECT>',
			html: '{$scanButton}',
			//items: frmUpload_{$docID},
			closable: false,
			modal: true,
			buttons: [{
				id: 'btnSaveScanMain_{$moduleID}_{$docID}',
				text: 'Commit',
				hidden: true,
				handler: function() {
					
	    			wndScanMain_{$moduleID}_{$docID}.hide();
	    			Ext.MessageBox.show({
                        msg: 'กำลังบันทึกเอกสารสแกน...',
                        progressText: 'Saving...',
                        width:300,
                        wait:true,
                        waitConfig: {interval:200},
                        icon:'ext-mb-download'
                	});
			       	Ext.Ajax.request({
		    			url: '/{$config ['appName']}/document/add-scan-announce',
		    			method: 'POST',
		    			enctype: 'multipart/form-data',
		    			success: function(o) {
		    				Ext.MessageBox.hide();
                            var r = Ext.decode(o.responseText);
                            var responseMsg = '';
                            if(r.success == 1) {
                            	responseMsg = 'บันทึกเรียบร้อยแล้ว';
							} else {
								responseMsg = 'บันทึกผิดพลาด';
							}
							
                            Ext.MessageBox.show({
                                title: 'กำลังบันทึกเอกสารสแกน',
                                msg: responseMsg,
                                buttons: Ext.MessageBox.OK,
                           		icon: Ext.MessageBox.INFO
							});
		    				//Ext.getCmp('btnSaveScanMain_{$moduleID}_{$docID}').disable();
						},
		    			//failure: documentAddFailed,
		    			//form: frmUpload_{$docID}.getForm().getEl()
		    			params: {
		    				docID: {$docID}
						}
		    		});
		    		
	    		}
			},{
				text: 'Close',
				handler: function() {
					wndScanMain_{$moduleID}_{$docID}.hide();
				}
			}]
		});
		
		
		var documentViewer_{$moduleID}_{$docID} = new Ext.Panel({
		   	tbar: new Ext.Toolbar({
				id: 'documentViewerToolbar_{$moduleID}_{$docID}',
				height: 25				
			}),
			autoScroll : true,
		   	margins: '5 0 0 5',
		   	cmargins: '5 5 0 5',
		   	minSize: 100,
		   	maxSize: 300,
		   	//layout: 'fit',
		   	height: Ext.getCmp('tpAdmin').getInnerHeight(),
		   	renderTo: 'documentViewUIDiv_{$moduleID}_{$docID}',
		   	html: '{$formHTML}'
		});
		
		var documentAttachMain_{$moduleID}_{$docID} = new Ext.Panel({
		   	tbar: new Ext.Toolbar({
				id: 'attachmentToolbarMain_{$moduleID}_{$docID}',
				height: 25				
			}),
			autoScroll : true,
			cls: 'images-view',
			items: new Ext.DataView({
				singleSelect: true,
	            store: {$attachName},
	            tpl: new Ext.XTemplate(
					'<tpl for=\".\">',
			           	'<center><div class=\"thumb-wrap\" id=\"{name}\">',
					    '<div class=\"thumb\"><img src=\"{url}\" title=\"{name}\"><span class=\"x-editable\">By: {owner}<br/>Date: {date}<br/>Size: {sizeStr}<br/>Version: {version}</span></div>',
					    '<span class=\"x-editable\">{name}</span></div></center>',
			        '</tpl>',
			        '<div class=\"x-clear\"></div>'
				),
	            autoHeight:true,
                //loadMask: true,
	            multiSelect: true,
	            overClass:'x-view-over',
	            itemSelector:'div.thumb-wrap',
	            emptyText: 'No attachments',
	            listeners: {
                    'selectionchange' :  function (thisObj, selections )  {
                        if(thisObj.getSelectionCount() == 1) {
                            Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').enable(); 
                        } 
                        if(thisObj.getSelectionCount() > 1) {
                            Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').disable(); 
                            Cookies.set('vdid',0);
                            Cookies.set('vpid',0);
                            Cookies.set('vpt','');
                        }
                        if(thisObj.getSelectionCount() ==0) { 
                             Ext.getCmp('btnDeletePage_{$moduleID}_{$docID}').disable(); 
                             Cookies.set('vdid',0);
                             Cookies.set('vpid',0);
                             Cookies.set('vpt','');
                        }
                    },
                    'click' : function (thisObj,idx,node,evt) {
                         Cookies.set('vdid',thisObj.getRecord(node).get('docid'));
                         Cookies.set('vpid',thisObj.getRecord(node).get('pageid'));
                         Cookies.set('vpt',thisObj.getRecord(node).get('name'));
                    },
					'dblclick' : function(thisObj,idx,node,evt) {
						popupAnnounceviewer(thisObj.getRecord(node).get('docid'),thisObj.getRecord(node).get('pageid'));
					}
				},
	
	            //plugins: [
	            //    new Ext.DataView.DragSelector(),
	            //    new Ext.DataView.LabelEditor({dataIndex: 'name'})
	            //],
	
	            prepareData: function(data){
	                data.shortName = Ext.util.Format.ellipsis(data.name, 16);
	                data.sizeString = Ext.util.Format.fileSize(data.size);
	                data.dateString = data.lastmod.format(\"m/d/Y g:i a\");
	                return data;
	            }
	        }),
			bbar: new Ext.PagingToolbar({
				id: 'attachmentToolbarMain2_{$moduleID}_{$docID}',
				height: 25,	
	            pageSize: {$config['thumbnailPerPage']},
	            store: {$attachName},
	            displayInfo: false
	        }),
		    margins: '5 0 0 5',
		    cmargins: '5 5 0 5',
		    minSize: 100,
		    maxSize: 300,
		   	layout: 'fit',
		   	height: Ext.getCmp('tpAdmin').getInnerHeight(),
		   	renderTo: 'documentViewThumbnailUIDiv_{$moduleID}_{$docID}'
		});
		
		var attachmentToolbarMain_{$moduleID}_{$docID} = Ext.getCmp('attachmentToolbarMain_{$moduleID}_{$docID}');
		
		attachmentToolbarMain_{$moduleID}_{$docID}.add({
            text:'Attach',
            iconCls: 'addIcon',
            disabled: {$hideAttachButton},
            handler: function(e) {
            	wndAttachMain_{$moduleID}_{$docID}.show();
			}
        },{
            text:'Scan',
            iconCls: 'addIcon',
            disabled: {$hideScanButton},
            handler: function(e) {
				wndScanMain_{$moduleID}_{$docID}.show();
            	//Ext.MessageBox.confirm('Confirm', 'Delete Concurrent [ '+gridConcurrent.getSelectionModel().getSelected().get('name')+']?', deleteSelectedConcurrent);
			}
        },{
            text:'Delete',
            id: 'btnDeletePage_{$moduleID}_{$docID}',
            iconCls: 'deleteIcon',   
            disabled: true,
            handler: function(e) {
                Ext.MessageBox.confirm('{$lang['common']['confirm']}', 'ลบเอกสารแนบ[ '+Cookies.get('vpt')+']?', deleteSelectedAnnouncePage);
			}
        },{
            text:'Refresh',
            iconCls: 'refreshIcon',
            disabled: {$hideRefreshButton},
            handler: function(){
            	//accountStore.load({params:{start:0, limit:8}});
            	{$attachName}.reload();
			}
        });
        
        function deleteSelectedAnnouncePage(btn) {
            if(btn == 'yes') {
                //alert('deleted');
                Ext.MessageBox.show({
                    msg: 'Delete Attachment',
                    progressText: '{$lang['common']['processing']}',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/document/delete-announce-attach',
                    method: 'POST',
                    success: function() {
                        {$attachName}.reload() 
                        Ext.MessageBox.hide();
                    },
                    failure: function() {
                        Ext.MessageBox.hide();
                        {$attachName}.reload()   
                    },
                    params: {
                        'docID' : Cookies.get('vdid'),
                        'page': Cookies.get('vpid')
                    }
                });
            }
        }
        
        //Main Toolbar
        var documentViewerToolbar_{$moduleID}_{$docID} = Ext.getCmp('documentViewerToolbar_{$moduleID}_{$docID}');
        documentViewerToolbar_{$moduleID}_{$docID}.add({
            text:'{$lang ['workitem'] ['assign']}',
            id: 'btnAssign_{$moduleID}_{$docID}',
            iconCls: 'assignIcon',
            disabled: {$assignBtnDisabled},
            hidden: {$hideAssignButton},
            handler: function(e) {
                assignOrderWindow.show();
                assignOrderForm.getForm().reset();
                assignOrderForm.getForm().setValues(
                [
                    {id: 'orderRecieverRefID',value: '{$docRefTransID}'}
                ]
                );
            }
            
        },{
            text:'{$lang ['workitem'] ['report']}',
            id: 'btnReport_{$moduleID}_{$docID}',
            iconCls: 'reportIcon',
            disabled: {$reportBtnDisable},
            hidden: {$hideReportButton},
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
                            reportOrderWindow.show();
                            reportOrderForm.getForm().reset();
                            reportOrderForm.getForm().setValues(
                            [
                                {id: 'reportOrderRefID',value: '{$orderID}'}
                            ]
                            ); 
                        }
                    }
                });
                
            }
        },{
            text:'{$lang ['workitem'] ['approve']}',
            id: 'btnApprove_{$moduleID}_{$docID}',
            hidden: {$hideApproveButton},
            iconCls: 'approveIcon',
            disabled: {$approveBtnDisabled},
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
                            Ext.MessageBox.confirm('{$lang['common']['confirm']}', '{$lang['workitem']['approve']} ?', approveTransaction);
                        }
                    }
                });
            	
			}
        },{
            text:'Send',
            id: 'btnSendForward_{$moduleID}_{$docID}',
            iconCls: 'bmenu',
            hidden: {$hideForwardButton},
            disabled: {$forwardButtonDisabled},
            handler: function(e) {
            	forwardDFWindow.show();
				//wndSendList_{$docID}.show();
			}
        },{
            text:'Search',
            id: 'btnSearch_{$moduleID}_{$docID}',
            iconCls: 'searchIcon',
            hidden: {$hideSearchButton},
            handler: function(e) {
            	var tabMain = Ext.getCmp('tpAdmin');
		    	
		    	tabMain.add({
	            	id: 'tabSearch_{$formName}',//+Ext.getCmp('txtSearch').getValue(),
					title: '{$lang ['dms'] ['searchResult']}_{$formName}',//+'\"'+Ext.getCmp('txtSearch').getValue()+'\"',
					iconCls: 'searchIcon',
					closable:true,
					autoLoad: {
						url: '/{$config ['appName']}/portlet/get-portlet-content',
						method: 'POST',
						//form: Ext.get('viewinstance_{$docID}_{$uniqueIdentifier}'),
						form: document.getElementById('{$formName}'),
						params: {
								portletClass: '{$searchMode}',
								portletMethod: 'portletContent',
								keySearch: Url.encode('no'),
								formID: '{$formID}'
						},
						scripts: true
					}
				}).show();
			}
        },
/*		{
            text:'Save',
            id: 'btnSaveInstanceMain_{$moduleID}_{$docID}',
            iconCls: 'saveIcon',
            disabled: {$editButtonDisabled},
            hidden: {$editButtonHide},
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
                            Ext.MessageBox.show({
                                msg: 'กำลังแก้ไขเอกสาร...',
                                progressText: 'Saving...',
                                width:300,
                                wait:true,
                                waitConfig: {interval:200},
                                icon:'ext-mb-download'
                            });
                            Ext.Ajax.request({
                                url: '/{$config ['appName']}/announce/edit',
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
                                        if(r.refresh == 1) {
                                            DMSTree.getNodeById(r.id).parentNode.reload();
                                        }
                                        Ext.MessageBox.show({
                                            title: 'แก้ไขเอกสาร',
                                            msg: 'แก้ไขเรียบร้อยแล้ว',
                                            buttons: Ext.MessageBox.OK,
                                               icon: Ext.MessageBox.INFO
                                        });
                                },
                                form: Ext.get('viewAnnnounce_{$docID}_{$uniqueIdentifier}'),
                                params: { 
                                    formID: {$formID}
                                    ,instanceID: {$docID}
                                }
                            });
                        }
                    }
                });
            	
			}
        },*/
		'-',
        new Ext.form.ComboBox({
            store: autocompleteRevision{$moduleID}_{$docID},
            //fieldLabel: 'บันทึกรายชื่อ',
            displayField:'name',
            //style: autoFieldStyle,
            typeAhead: false,
            emptyText: 'Revision',
            tabIndex: 1,
            loadingText: '{$lang['common']['searching']}',
            width: 130,
            //pageSize:10,
            hideTrigger: false ,
            id: 'revisionRetriever{$moduleID}_{$docID}',                         
            name: 'revisionRetriever{$moduleID}_{$docID}',
            tpl: resultRevision,
            //lazyInit: true,
            //lazyRender: true,
            minChars: 0,
            shadow: false,
            autoLoad: true,
            mode: 'remote',
            itemSelector: 'div.search-revision'
                                
        }),'-',new Ext.form.ComboBox({
            hidden: true,
            store: autocompleteVersion{$moduleID}_{$docID},
            //fieldLabel: 'บันทึกรายชื่อ',
            displayField:'name',
            //style: autoFieldStyle,
            typeAhead: false,
            emptyText: 'Version',
            tabIndex: 1,
            loadingText: '{$lang['common']['searching']}',
            width: 130,
            //pageSize:10,
            hideTrigger: false ,
            id: 'versionRetriever{$moduleID}_{$docID}',                         
            name: 'versionRetriever{$moduleID}_{$docID}',
            tpl: resultVersion,
            //lazyInit: true,
            //lazyRender: true,
            minChars: 0,
            shadow: false,
            autoLoad: true,
            mode: 'remote',
            itemSelector: 'div.search-version'
                                
        }),{
			text:'แก้ไข',
            id: 'btnModifyInstanceMain_{$moduleID}_{$docID}',
            iconCls: 'editIcon',
            disabled: {$editButtonAnnounceDisabled},
            hidden: {$editButtonHide},  
            handler: function(e) {
				announceFormWindow.show();
                createAnnounceForm.getForm().reset();
				
				createAnnounceForm.getForm().setValues(
				[
					{
						id: 'announceNoA',
						name: 'announceNoA',
						value: '{$announce->f_announce_no}/{$announce->f_year}'
					},{
						id: 'extraOrgNameA',
						name: 'extraOrgNameA',
						value: '{$announce->f_announce_org_name}'
					},{
						id: 'extraOrgIDA',
						name: 'extraOrgIDA',
						value: '{$announce->f_announce_org_id}'
					},{
						id: 'extraDocTypeA',
						name: 'extraDocTypeA',
						hiddenname: 'extraDocTypeA',
						value: '{$announceTypeName}'
					},{
						id: 'extraDocSubTypeA',      
						name: 'extraDocSubTypeA',                            
						value:'{$announceCat->f_name}'
					},{
						id: 'extraDocTitleA',    
						name: 'extraDocTitleA',    
						value: '{$announceTitle}'
						
					},{
						id: 'extraDocYearA',
						name: 'extraDocYearA',
						value: '{$announce->f_year}'
					},{
						id : 'extraDocDescA',
						name: 'extraDocDescA',
						value:'{$announceDetail}'
					},{
						id: 'extraDocDateA',
						name: 'extraDocDateA',
						value: '{$announce->f_announce_date}'
					},{
						name: 'extraDocSignUserA',                            
						id: 'extraDocSignUserIDA',                            
						hiddenName: 'extraDocSignUserA',
						value:'{$signUserName}'
					},{
						name: 'extraDocSignRoleA',                            
						id: 'extraDocSignRoleIDA',                            
						hiddenName: 'extraDocSignRoleA',
						value:'{$signRoleName}'
					},{
						id:'extraDocRemarkA',
						name:'extraDocRemarkA',
						value: '{$announce->f_remark}'
					},{// no use 
						id: 'extraDocTypeAHidden',
						name: 'extraDocTypeAHidden',
						value:{$announce->f_announce_category}
					},{
						id: 'extraDocSignUserAHidden',
						name: 'extraDocSignUserAHidden',
						value:'{$announce->f_sign_uid}'
					},{
						id: 'extraDocSignRoleAHidden',
						name: 'extraDocSignRoleAHidden',
						value:'{$announce->f_sign_role}'
					},{
						id: 'instanceIDA',
						name: 'instanceIDA',
						value: '{$announce->f_announce_id}'
					},{
						id : 'formIDA',
						name : 'formIDA',
						value: '{$formID}'
					}
				]);

			}
		},'-'
        );
		documentViewer_{$moduleID}_{$docID}.body = '$formHTML';
		 
		documentViewer_{$moduleID}_{$docID}.render();
		documentAttachMain_{$moduleID}_{$docID}.render();
		
		function approveTransaction(btn) {
		if(btn=='yes') {
			Ext.Ajax.request({
					url: '/{$config ['appName']}/df-action/approve-recv-trans',
				   	method: 'POST',
				    success: function(o){
				    	Ext.MessageBox.hide();
						var r = Ext.decode(o.responseText);
						var regNo = '';
						var sendDate = '';
                        
						Ext.MessageBox.show({
						    title: 'การอนุมัติ',
						    msg: 'การอนุมัติเรียบร้อย',
						    buttons: Ext.MessageBox.OK,
						    icon: Ext.MessageBox.INFO
						});
						Ext.getCmp('forwardDFForm').getForm().reset();
					},
				    failure: function(r,o) {
				    	Ext.MessageBox.hide();
						Ext.MessageBox.show({
							title: 'Problem',
						    msg: 'ไม่สามารถทำการอนุมัติได้',
						    buttons: Ext.MessageBox.OK,
						    icon: Ext.MessageBox.ERROR
						});
					},
					params: { docID: '{$docID}' ,transCode: '{$docRefTransID}'}
				});
			}
		}
        
        Ext.getCmp('revisionRetriever{$moduleID}_{$docID}').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            //Cookies.set('drv',dataRecord.data.value);
            Ext.Ajax.request({
                url: '/{$config ['appName']}/document/load-revision',
                method: 'POST',
                success: function(o){
                      Ext.MessageBox.hide();
                      var r = Ext.decode(o.responseText);
                      //alert(r.length);
                      //alert(r.results);     
                      for(i = 0 ;i<r.results.length;i++) {
                            //alert(r.results[i].struct);
                            //alert(r.results[i].val);
                           // var idField = r.results[i].struct;
                            
                            var statement = 'document.forms.{$formName}.'+r.results[i].struct+'.value=\''+r.results[i].val+'\';'; 
                            //alert(el);                          
                            eval(statement);
                      }          
                                        
                      if(dataRecord.data.value == 0) {
                            Ext.MessageBox.show({
                                title: 'Load Revision',
                                msg: 'Load current revision completed',
                                buttons: Ext.MessageBox.OK,
                                icon: Ext.MessageBox.INFO
                            });
                        } else {
                            Ext.MessageBox.show({
                                title: 'Load Revision',
                                msg: 'Load revision '+dataRecord.data.value+' completed',
                                buttons: Ext.MessageBox.OK,
                                icon: Ext.MessageBox.INFO
                            });
                        }   
                    Ext.getCmp('receiveInternalForm').getForm().reset();
                },
                failure: function(r,o) {
                    
                    
                    Ext.MessageBox.hide();

                    Ext.MessageBox.show({
                        title: 'Problem',
                        msg: 'Unalble to load rivision',
                        buttons: Ext.MessageBox.OK,
                        //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                        icon: Ext.MessageBox.ERROR
                    });
                },
                params: {id: '{$docID}','rev': dataRecord.data.value}
                //form: Ext.getCmp('receiveInternalForm').getForm().getEl()
            });
            //alert(Cookies.get('drv'));
        },this);

		</script>";
		if ($_POST ['callModuleType'] == 'DMS' && $_POST ['searchMode'] == 'SearchLoanRequest') {
			$postJS = "document.forms.{$formName}.reset();";
		} else {
			$postJS = '';
		}
		echo "<script>{$fillFormJS}{$postJS}</script>";
	}
	
	/**
	 * action /view-log ทำการแสดง Log ของเอกสาร
	 *
	 */
	public function viewLogAction() {
		global $config;
		global $lang;
		global $util;
		
		//include_once 'LogAudit.Entity.php';
		//include_once 'TransMasterDf.Entity.php';
		//include_once 'DocMain.Entity.php';
		

		#global $conn;
		#$conn->debug = true;
		if (array_key_exists ( 'transID', $_GET )) {
			
			list ( $transMainID, $transMainSeq, $transID ) = explode ( "_", $_GET ['transID'] );
			
			$transMaster = new TransMasterDfEntity ( );
			$transMaster->Load ( "f_trans_main_id='{$transMainID}'" );
			$docID = $transMaster->f_doc_id;
		} else {
			if (array_key_exists ( 'docID', $_GET )) {
				$docID = $_GET ['docID'];
			}
		}
		
		$logAuditLoader = new LogAuditEntity ( );
		$logAudits = $logAuditLoader->Find ( "f_object_type = 4 and f_object_id = '{$docID}' order by f_timestamp asc" );
		
		$document = new DocMainEntity ( );
		$document->Load ( "f_doc_id = '{$docID}'" );
		
		$contents = "<table class=\"personalInfoBar\">
            <tr bgcolor=\"#000000\" >
                <td><font color=\"#FFFFFF\">วัน/เวลา</font></td>
                <td><font color=\"#FFFFFF\">กิจกรรม</font></td>
                <td><font color=\"#FFFFFF\">Log Message</font></td>
                <td><font color=\"#FFFFFF\">ผู้กระทำ</font></td>
            </tr>
        ";
		$i = 0;
		foreach ( $logAudits as $logAudit ) {
			if ($i == 0) {
				$css = "bgcolor=\"#FFFFFF\"";
				$i = 1;
			} else {
				$css = "bgcolor=\"#DEDEDE\"";
				$i = 0;
			}
			switch ($logAudit->f_activity_type) {
				case AuditLog::ACTIVITY_CREATE :
					$activity = 'Create';
					break;
				case AuditLog::ACTIVITY_MODIFY :
					$activity = 'Modify';
					break;
				case AuditLog::ACTIVITY_DELETE :
					$activity = 'Delete';
					break;
				case AuditLog::ACTIVITY_AUDIT :
					$activity = 'Audit';
					break;
				case AuditLog::ACTIVITY_ACCESS :
				default :
					$activity = 'Access';
					break;
			}
			$account = new AccountEntity ( );
			$account->Load ( "f_acc_id = '{$logAudit->f_acc_id}'" );
			$contents .= "<tr {$css}>
                <td>{$util->getDateString($logAudit->f_timestamp)},{$util->getTimeString($logAudit->f_timestamp)}</td>
                <td>{$activity}</td>
                <td>{$logAudit->f_message}</td>
                <td>{$account->f_name} {$account->f_last_name}</td>
            </tr>";
		}
		$contents .= "<table>";
		$header = "ประวัติการใช้งานเอกสาร<br/> [{$document->f_title}]";
		$html = "<html>
			<head>
                <!--<link href=\"/{$config['appName']}/css/ecmCoreCSS.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" >-->
				<title>ดูประวัติเอกสาร [{$document->f_title}]</title>
			</head>
			<body>
            <center><h1>{$header}</h1></center>
            {$contents}
            <center>
                <input type=\"button\" value=\"Close\" onclick=\"top.window.close();\"/>                   
            </center>      
            </body>            
		</html>";
		echo $html;
	}
	
	/**
	 * action /checkout ทำการ checkout
	 *
	 */
	public function checkoutAction() {
		
		//include_once 'DocMain.Entity.php';
		

		$docMain = new DocMainEntity ( );
		$arrObjId = explode ( ',', $_GET ['docID'] );
		foreach ( $arrObjId as $vObjId ) {
			try {
				$docMain->Load ( "f_doc_id = '{$vObjId}'" );
				$docMain->f_checkout_flag = 1;
				$docMain->f_checkout_user = $_SESSION ['accID'];
				$docMain->Save ();
				$result = array ('success' => 1 );
			} catch ( Exception $e ) {
				$result = array ('success' => 0, 'message' => $e->getMessage () );
			}
		}
		
		echo json_encode ( $result );
	}
	
	/**
	 * action /checkin ทำการ checkin เอกสาร
	 *
	 */
	public function checkinAction() {
		
		//include_once 'DocMain.Entity.php';
		

		$docMain = new DocMainEntity ( );
		$arrObjId = explode ( ',', $_GET ['docID'] );
		foreach ( $arrObjId as $vObjId ) {
			try {
				$docMain->Load ( "f_doc_id = '{$vObjId}'" );
				$docMain->f_checkout_flag = 0;
				if(is_null($docMain->f_version)) {
					$docMain->f_version = '1.0.0';
				} 
				list($major,$minor,$branch) = explode(".",$docMain->f_version);
				$major += 1;
				$minor = 0;
				$branch = 0;
				$docMain->f_version = "{$major}.{$minor}.{$branch}";
				
				$docMain->f_checkout_user = '';
				$docMain->Save ();
				$result = array ('success' => 1 );
			} catch ( Exception $e ) {
				$result = array ('success' => 0, 'message' => $e->getMessage () );
				Logger::dump ( __FILE__ . __LINE__, $e->getMessage () );
			}
		}
		
		echo json_encode ( $result );
	}
	
	/**
	 * action /verify-checkin ทำการตรวจสอบการ checkin
	 *
	 */
	public function verifyCheckinAction() {
		global $lang;
		
		//include_once 'DocMain.Entity.php';
		

		$docID = $_GET ['docID'];
		$docMain = new DocMainEntity ( );
		
		try {
			$docMain->Load ( "f_doc_id = '{$docID}'" );
			Logger::dump ( 'var', 'check-out user: ' . $docMain->f_checkout_user . ' |accountID: ' . $_SESSION ['accID'] );
			if ($docMain->f_checkout_user == $_SESSION ['accID']) {
				$result = array ('success' => 1 );
			} else {
				$result = array ('success' => 0, 'message' => UTFEncode ( $lang ['dms'] ['failedCheckin'] ) );
			}
		} catch ( Exception $e ) {
			$result = array ('success' => 0, 'message' => $e->getMessage () );
		}
		
		echo json_encode ( $result );
	}
}

