<?php
/**
 * โปรแกรมแสดง Workflow Explorer
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Workflow
 *
 */

class WorkflowExplorerController extends ECMController {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->_redirector = $this->_helper->getHelper ( 'Redirector' );
	}
	
	/**
	 * redirect
	 *
	 */
	public function indexAction() {
		$this->_redirector->gotoUrl ( '/docflow-explorer/get-ui' );
	}
	
	/**
	 * action /get-ui แสดง Workflow Explorer
	 *
	 */
	public function getUiAction() {
		global $config;
		global $lang;
		
		checkSessionPortlet ();
		//global $store;
		

		/* prepare DIV For UI */
		echo "<div id=\"WFExplorerUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		    	
		var WFTreeLoader = new Ext.tree.TreeLoader({
        	dataUrl   : '/{$config ['appName']}/workflow-explorer/load-workflow-category',
        	baseParams: {objid: -1}
    	});
    	
    	var WFTree = new Ext.tree.TreePanel({
	        renderTo: 'WFExplorerUIDiv',
	        useArrows:true,
	        autoScroll:true,
	        animate:true,
	        frame: false,
	        bodyBorder: false,
	        enableDD:true,
	        containerScroll: true,
	        style: 'font-size: 14px;',
	        
	        root:  new Ext.tree.AsyncTreeNode({
		        text: '{$lang ['workflowTask']}',
		        draggable:false,
		        objid: 0,
		        objtype: 'WFRoot',
		        id: 'WFRoot',
		        iconCls: 'workHomeFolderIcon'
		    }),
	        loader: WFTreeLoader
	    });
	    
	    WFTree.on('click',function(node,eveny) {
	    	if(node.attributes.objtype != 'registerBookMainFolder' && node.attributes.objtype != 'WFRoot') {
	    		switch(node.attributes.objtype) {
					case 'incomingJob':
						var tabTitle = '{$lang ['workflow']['incomingJob']}';
		    			var paramPortletClass = 'WorkflowInboxPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					case 'outgoingJob':
						var tabTitle = '{$lang ['workflow']['outgoingJob']}';
		    			var paramPortletClass = 'WorkflowOutboxPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					case 'commitJob':
		    			var tabTitle = '{$lang ['workflow']['commitJob']}';
		    			var paramPortletClass = 'WorkflowCommitboxPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'draftJob':
		    			var tabTitle = '{$lang ['workflow']['draftJob']}';
		    			var paramPortletClass = 'WorkflowDraftboxPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'abortJob':
		    			var tabTitle = '{$lang ['workflow']['abortJob']}';
		    			var paramPortletClass = 'WorkflowAbortboxPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
				}
				
				var tabID = 'tab_'+node.attributes.objtype;
		    	var tabMain = Ext.getCmp('tpAdmin');
		    	
				if(!tabMain.findById( tabID)) {
					tabMain.add({
						id: tabID,
						title: tabTitle,
						iconCls: 'workflowIcon',
						autoLoad: {
							url: '/{$config ['appName']}/portlet/get-portlet-content', 
							params: {
								portletClass: paramPortletClass,
								portletMethod: paramPortletMethod
							},
							scripts: true
						},
						closable:true
					}).show();
				} else {
					tabMain.findById(tabID).show();
				}
				//Ext.ECM.msg('" . $lang ['ECMAppName'] . "','Entering \"'+tabTitle+'\".');
			}
		},this);
	
	    // render the tree
	    WFTree.render();
	    WFTree.root.expand();
	    //Ext.getCmp('RegisterBook').expand();
		
    	</script>";
	}
	
	/**
	 * action /load-workflow-category ทำการส่งข้อมูล Workflow
	 *
	 */
	function loadWorkflowCategoryAction() {
		global $lang;
		
		$node = Array ();
		$node = Array (Array ("id" => "IncomingJob", "objid" => "1", "objtype" => "incomingJob", "text" => UTFEncode ( $lang ['workflow'] ['incomingJob'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "unreceiveIcon" ), Array ("id" => "outgoingJob", "objid" => "2", "objtype" => "outgoingJob", "text" => UTFEncode ( $lang ['workflow'] ['outgoingJob'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "receiveIcon" ), Array ("id" => "commitJob", "objid" => "3", "objtype" => "commitJob", "text" => UTFEncode ( $lang ['workflow'] ['commitJob'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "receiveIcon" ), Array ("id" => "draftJob", "objid" => "4", "objtype" => "draftJob", "text" => UTFEncode ( $lang ['workflow'] ['draftJob'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "receiveIcon" ), Array ("id" => "abortJob", "objid" => "5", "objtype" => "abortJob", "text" => UTFEncode ( $lang ['workflow'] ['abortJob'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "receiveIcon" ) );
		
		echo json_encode ( $node );
	}
}
