<?php
/**
 * Portlet : ระบบจัดเ็ก็บ???
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
//include_once ('DMSUtil.php');
class DmsControlCenter {
	
	public function init() {
		//include_once 'DFStore.php';
	}
	
	public function getUI() {
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
		$RIStore = $dmsStore->getSearchResultDMS($keySearch, $storeName,$extraParamPost);
		
		echo "<div id=\"{$DIVName}\" display=\"inline\"></div>";
		echo "<script type=\"text/javascript\">
		
		$RIStore		
		{$storeName}.setDefaultSort('f_name', 'asc');
		{$storeName}.load({params:{start:0, limit:25}});
		
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
	           width: 20
	        },{
	           header: \"{$lang ['dms'] ['createStampTime']}\",
	           dataIndex: 'f_created_time',
	           sortable: true,
	           width: 20
	        },{
	           header: \"{$lang ['dms'] ['updateStamp']}\",
	           dataIndex: 'f_last_update_date',
	           sortable: true,
	           width: 20
	        },{
	           header: \"{$lang ['dms'] ['updateStampTime']}\",
	           dataIndex: 'f_last_update_time',
	           sortable: true,
	           width: 20
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
    	
		tb_{$programID}.add({
            text:'{$lang['df']['fetch']}',
            iconCls: 'refreshIcon',
            handler: function(){
            	{$storeName}.reload();
			}
        });
	    
	    {$gridName}.render();
	    {$gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$gridName}.colModel);
	    
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

?>
