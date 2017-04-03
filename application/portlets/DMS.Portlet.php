<?php
/**
 * Portlet : ผลการค้นหา(จัดเก็บ)
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
//include_once ('DMSUtil.php');
class DMSPortlet {
	
	public function init() {
		//include_once 'DFStore.php';
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
		
		Logger::dump('POST',$_POST);
		
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

		$RIStore = $dmsStore->getSearchResultDMS($keySearch, $storeName, $extraParamPost, $_POST['qMode'], $_POST);
		
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
		{$storeName}.setDefaultSort('f_name', 'asc');
		
		
		var summary = new Ext.grid.GroupSummary(); 
		
		var cm_{$programID} = new Ext.grid.ColumnModel([{
	           header: \"obj_id\",
	           dataIndex: 'f_obj_id',
	           width: 30,
	           hidden: true,
	           hideable: false
	        },{
	           header: \"doc_id\",
	           dataIndex: 'f_doc_id',
	           width: 30,
	           hidden: true,
	           hideable: false
	        },{
	           header: \"{$lang ['common'] ['image']}\",
	           dataIndex: 'f_obj_type_image',
	           width: 6
	        },{
	           header: \"{$lang ['dms'] ['name']} / {$lang ['dms'] ['description']} / {$lang ['dms'] ['keyword']}\",
	           dataIndex: 'f_name',
	           sortable: true,
	           width: 60,
	           summaryType: 'count',
				summaryRenderer: function(rowCount, params, data){
                    return ((rowCount === 0 || rowCount > 1) ? '(<b>' + rowCount +' {$lang ['dms'] ['index']}</b>)' : '(<b>1 {$lang ['dms'] ['index']}</b>)</b>');
                }
	        },{
	           header: \"{$lang ['dms'] ['location']}\",
	           dataIndex: 'f_location',
	           sortable: true
	        },{
	           header: \"{$lang ['org'] ['type']}\",
	           dataIndex: 'f_obj_type',
	           width: 20
	        },{
	           header: \"{$lang ['dms'] ['createStamp']}\",
	           dataIndex: 'f_created_date',
	           sortable: true,
	           width: 25
	        },{
	           header: \"{$lang ['dms'] ['createStampTime']}\",
	           dataIndex: 'f_created_time',
	           sortable: true,
	           width: 25
	        },{
	           header: \"{$lang ['dms'] ['updateStamp']}\",
	           dataIndex: 'f_last_update_date',
	           sortable: true,
	           width: 25
	        },{
	           header: \"{$lang ['dms'] ['updateStampTime']}\",
	           dataIndex: 'f_last_update_time',
	           sortable: true,
	           width: 25
	        },{
	           header: \"{$lang ['dms'] ['expireStamp']}\",
	           dataIndex: 'f_expire_date',
	           sortable: true,
	           width: 25
	        },{
	           header: \"{$lang ['dms'] ['expireStampTime']}\",
	           dataIndex: 'f_expire_time',
	           sortable: true,
	           width: 25
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
	                    p.body = '<p><b><font color=\"navy\">{$lang ['dms'] ['description']}: </font></b>'+record.data.f_description+'</p><p><b><font color=\"navy\">{$lang ['dms'] ['keyword']}: </font></b>'+record.data.f_keyword+'</p>';
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
        },{
            text:'Print',
            //iconCls: 'refreshIcon',
            handler: function(){
            	printWindow();
			}
        });
	    
	    {$gridName}.render();
	    {$storeName}.load({params:{start:0, limit:25}});
	    
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
			if ({$gridName}.getSelectionModel().getSelected().get('f_obj_type') == '{$lang ['dms'] ['document']}') {
				viewDocumentCrossModule('viewDOC_'+{$gridName}.getSelectionModel().getSelected().get('f_doc_id'),{$gridName}.getSelectionModel().getSelected().get('f_name'),'Search',{$gridName}.getSelectionModel().getSelected().get('f_doc_id'));
			}
		}
		,{$gridName});
		</script>";		
	}
}
