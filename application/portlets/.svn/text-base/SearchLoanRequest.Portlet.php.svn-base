<?php
/**
 * Portlet : ค้นหา Loan
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
include_once ('DMSUtil.php');
class SearchLoanRequest {
	
	public function init() {
		//include_once 'DFStore.php';
	}
	
	public function getUI() {
		global $lang;
		global $config;
		
		$js = "Ext.get('searhLoanSPAN').on('click',function() {
	                if(requestCheckSession()) {
	                    var tabMain = Ext.getCmp('tpAdmin');
			    	
			    		tabMain.add({
			            	id: 'tabDMSControl_expireDocument',
							title: 'ค้นหาสัญญาเงินกู้',
							iconCls: 'searchIcon',
							closable:true,
							autoLoad: {
								url: '/{$config ['appName']}/document/view-cross-module',
								params: {
										callModuleType: 'DMS',
										docID: '545',
										searchMode: 'SearchLoanRequest'
								},
								method: 'POST',
								scripts: true
							}
						}).show();
	                } else {
	                    sessionExpired();
	                }
            	},this)";
		echo  "<span class=\"portletCommandSpan\" id=\"searhLoanSPAN\"><img src=\"/{$config ['appName']}/images/th/LoanRequest.png\"/></span>";
		
		echo "<script>$js</script>";
	}
	
	public function portletContent() {
		global $config;
		global $conn;
		global $lang;
		
		checkSessionPortlet();
		
		$dmsStore = new DMSStore ( );
		//print_r($_POST);
		
		$keySearch = $_POST['keySearch'];
		$id = md5($keySearch);
		$storeName = "RIStore_{$id}";
		$gridName = 'gpSearchResult_' . $id;
		$DIVName = "divSearchResultDMS_" . $id;
		$programID = 'searchResultDMS_' . $id;
		
//		Logger::dump('POST',$_POST);
		
		$extraParam = Array();
		foreach($_POST as $key=>$value) {
			if(strtolower(substr($key,0,6)) == 'struct' || strtolower(substr($key,0,6)) == 'formid') {
				$extraParam[$key] = $value;
			}
		}
		if(count($extraParam)>1) {
			$extraParamPost = serialize($extraParam);
		} else {
			$extraParamPost = "";
		}
//		logger::dump('serialize param', $extraParamPost);
		
		$RIStore = $dmsStore->getSearchLoanRequest($keySearch, $storeName,$extraParamPost,'searchLoanRequest');
		
		switch ($_POST['qMode']) {
			case 'recyclebin':
				$btnRestore = "{
								id: 'btnRestore_{$programID}',
								text:'{$lang ['dms'] ['restore']}',
					            iconCls: 'restoreIcon',
					            disabled: true,
					            handler: function(){
					            
					            	Ext.Ajax.request({
						                url: '/{$config ['appName']}/dms-action/move-node',
						                method: 'POST',
						                success: function(o){
						                	
							                Ext.MessageBox.hide();
							                var r = Ext.decode(o.responseText);
							                var responseMsg = r.message;
											var result;
											
											if(r.success == 1) {
												messageSuccess(null,null);
												{$storeName}.reload();
											} else {
												messageFailed(null, responseMsg);
											}
										},
										params: {
				                        	objIdFrom: getRowSelected('objID'),
			                        		objIdTo: 'restore'
										}
									});
								}},";
				$btnDelete = "{
								id: 'btnDelete_{$programID}',
								text:'{$lang ['common'] ['delete']}',
					            iconCls: 'deleteIcon',
					            disabled: true,
					            handler: function(){
					            
					            	Ext.Ajax.request({
						                url: '/{$config ['appName']}/dms-action/move-node',
						                method: 'POST',
						                success: function(o){
						                	
							                Ext.MessageBox.hide();
							                var r = Ext.decode(o.responseText);
							                var responseMsg = r.message;
											var result;
											
											if(r.success == 1) {
												messageSuccess(null,null);
												{$storeName}.reload();
											} else {
												messageFailed(null, responseMsg);
											}
										},
										params: {
				                        	objIdFrom: getRowSelected('objID'),
			                        		objIdTo: ''
										}
									});
								}},";
				break;
			case 'expireDocument':
				$btnRestore = "{
								id: 'btnRestore_{$programID}',
								text:'{$lang ['dms'] ['restore']}',
					            iconCls: 'restoreIcon',
					            disabled: true,
					            handler: function(){
					            
					            	Ext.Ajax.request({
						                url: '/{$config ['appName']}/dms-action/restore-document-expire',
						                method: 'GET',
						                success: function(o){
						                	
							                Ext.MessageBox.hide();
							                var r = Ext.decode(o.responseText);
							                var responseMsg = r.message;
											var result;
											
											if(r.success == 1) {
												messageSuccess(null,null);
												{$storeName}.reload();
											} else {
												messageFailed(null, responseMsg);
											}
										},
										params: {
				                        	objID: getRowSelected('objID')
										}
									});
								}},";
				$btnDelete = "{
								id: 'btnDelete_{$programID}',
								text:'{$lang ['common'] ['delete']}',
					            iconCls: 'deleteIcon',
					            disabled: true,
					            handler: function(){
					            
					            	Ext.Ajax.request({
						                url: '/{$config ['appName']}/dms-action/move-node',
						                method: 'POST',
						                success: function(o){
						                	
							                Ext.MessageBox.hide();
							                var r = Ext.decode(o.responseText);
							                var responseMsg = r.message;
											var result;
											
											if(r.success == 1) {
												messageSuccess(null,null);
												{$storeName}.reload();
											} else {
												messageFailed(null, responseMsg);
											}
										},
										params: {
				                        	objIdFrom: getRowSelected('objID'),
			                        		objIdTo: 'recyclebin'
										}
									});
								}},";
				break;
			case 'checkout':
				$btnCheckin = "{
								id: 'btnCheckin_{$programID}',
								text:'{$lang ['dms'] ['checkin']}',
					            iconCls: 'checkinIcon',
					            disabled: true,
					            handler: function(){
					            
					            	Ext.Ajax.request({
						                url: '/{$config ['appName']}/document/checkin',
						                method: 'GET',
						                success: function(o){
						                	
							                Ext.MessageBox.hide();
							                var r = Ext.decode(o.responseText);
							                var responseMsg = r.message;
											var result;
											
											if(r.success == 1) {
												messageSuccess(null,null);
												{$storeName}.reload();
											} else {
												messageFailed(null, responseMsg);
											}
										},
										params: {
				                        	docID: getRowSelected('docID')
										}
									});
								}},";
				break;
		}
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		
		function getRowSelected(vType) {
			var vSeparator = ',';
			var vRowSelected = '';
			var rowSelected = $gridName.getSelectionModel().getSelections();
							
			for( var i=0; i < rowSelected.length; i++ ) {
				if (i == (rowSelected.length - 1)) { // last piece of array
					vSeparator = '';
				}
				var row = rowSelected[i];
				switch (vType) {
					case 'objID':
						vRowSelected = vRowSelected + row.get('f_obj_id') + vSeparator;
						break;
					case 'docID':
						vRowSelected = vRowSelected + row.get('f_doc_id') + vSeparator;
						break;
				}
			}
			return vRowSelected;
		}
		
		$RIStore		
		{$storeName}.setDefaultSort('f_struct1', 'asc');
		{$storeName}.load({params:{start:0, limit:25}});
		
		var summary = new Ext.grid.GroupSummary(); 
		
		var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"f_doc_id\",
	           dataIndex: 'f_doc_id',
	           width: 30,
	           hidden: true,
	           hideable: false
	        },{
	           header: \"f_form_id\",
	           dataIndex: 'f_form_id',
	           width: 30,
	           hidden: true,
	           hideable: false
	        },{
	           header: \"Loankey\",
	           dataIndex: 'f_struct1',
	           width: 20
	        },{
	           header: \"ชื่อโครงการ\",
	           dataIndex: 'f_struct2',
	           width: 50
	        },{
	           header: \"วันที่ลงนาม\",
	           dataIndex: 'f_struct3',
	           width: 20
	        },{
	           header: \"วงเงินกู้\",
	           dataIndex: 'f_struct4',
	           width: 30
	        },{
	           header: \"สกุลเงิน\",
	           dataIndex: 'f_struct5',
	           width: 20
	        },{
	           header: \"Donor Reference\",
	           dataIndex: 'f_struct6',
	           width: 30
	        },{
	           header: \"หมายเหตุ\",
	           dataIndex: 'f_struct7',
	           width: 30
	        }
		]);
		
		var {$gridName} = new Ext.grid.GridPanel({
	        width: Ext.getCmp('tpAdmin').getInnerWidth(),
	        //autoWidth: true,
			height: Ext.getCmp('tpAdmin').getInnerHeight(),
			loadMask: true,
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
	        plugins: summary,
	        view: new Ext.grid.GroupingView({
	        	forceFit:true,
	        	enableRowBody:true,
	        	showPreview: false,
				getRowClass : function(record, rowIndex, p, store){
	                if(this.showPreview){
	                    p.body = '<p><b><font color=\"navy\">{$lang ['dms'] ['description']}: </font></b>'+record.data.f_struct_id+'</p><p><b><font color=\"navy\">{$lang ['dms'] ['keyword']}: </font></b>'+record.data.f_value+'</p>';
	                    return 'x-grid3-row-expanded';
	                }
	                return 'x-grid3-row-collapsed';
	            }
			}),
	        
	        bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: {$storeName},
	            displayInfo: true,
	            displayMsg: '{$lang ['dms'] ['index']} {0} - {1} {$lang['common']['of']} {2}',
	            emptyMsg: \"{$lang['df']['noDocument']}\",
	            items:[
	                '-', {
	                pressed: false,
	                enableToggle: true,
	                text: '{$lang ['dms'] ['showIndexDesc']}',
	                cls: 'x-btn-text-icon details',
					toggleHandler: toggle{$programID}Details
	            }]
	        })
	    });
	    
		function toggle{$programID}Details(btn, pressed){
	        var view = {$gridName}.getView();
	        view.showPreview = pressed;
	        view.refresh();
	    }
	    
	    var tb_{$programID} = Ext.getCmp('{$programID}_Toolbar');
    	
		tb_{$programID}.add({$btnRestore}{$btnDelete}{$btnCheckin}{
            text:'{$lang['df']['fetch']}',
            iconCls: 'refreshIcon',
            handler: function(){
            	{$storeName}.reload();
			}
        });
	    
	    {$gridName}.render();
	    {$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
	    {$gridName}.on({'rowclick' : function() {
	    	if ({$gridName}.getSelectionModel().getCount() > 0) {
	    		if (Ext.getCmp('btnRestore_{$programID}')) {
                	Ext.getCmp('btnRestore_{$programID}').enable();
				}
				if (Ext.getCmp('btnDelete_{$programID}')) {
					Ext.getCmp('btnDelete_{$programID}').enable();   
				}
				if (Ext.getCmp('btnCheckin_{$programID}')) {
					Ext.getCmp('btnCheckin_{$programID}').enable();
				}
			} else {
				if (Ext.getCmp('btnRestore_{$programID}')) {
					Ext.getCmp('btnRestore_{$programID}').disable();
				}
				if (Ext.getCmp('btnDelete_{$programID}')) {
					Ext.getCmp('btnDelete_{$programID}').disable();
				}
				if (Ext.getCmp('btnCheckin_{$programID}')) {
					Ext.getCmp('btnCheckin_{$programID}').disable();
				}
			}
		},scope: this});
	    
	    {$gridName}.on('rowdblclick',function() {
//			Cookies.set('docRefID',{$gridName}.getSelectionModel().getSelected().get('recvID'));
//			alert({$gridName}.getSelectionModel().getSelected().get('f_obj_type'));
			//if ({$gridName}.getSelectionModel().getSelected().get('f_obj_type') == '{$lang ['dms'] ['document']}') {
				viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('f_doc_id'),{$gridName}.getSelectionModel().getSelected().get('f_name'),'Search',{$gridName}.getSelectionModel().getSelected().get('f_doc_id'));
			//}
		}
		,{$gridName});
		</script>";		
	}
}

?>
