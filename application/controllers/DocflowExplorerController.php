<?php
/**
 * โปรแกรมแสดงหน้าจอ Explorer ของระบบสารบรรณ
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category DocFlow
 */
class DocflowExplorerController extends Zend_Controller_Action {
	
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->_redirector = $this->_helper->getHelper ( 'Redirector' );
	}
	
	/**
	 * action /index/ ทำการ redirect ไปที่ action /get-ui
	 *
	 */
	public function indexAction() {
		global $util;
		$util->redirect('/docflow-explorer/get-ui' );
		//$this->_redirector->gotoUrl ( '/docflow-explorer/get-ui' );
	}
	
	/**
	 * ทำการแสดง Explorer
	 *
	 */
	public function getUiAction() {
		global $config;
		global $lang;
		//global $store;
		checkSessionPortlet();

		/* prepare DIV For UI */
		echo "<div id=\"DFExplorerUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		    	
		var DFTreeLoader = new Ext.tree.TreeLoader({
        	dataUrl   : '/{$config ['appName']}/docflow-explorer/load-workflow-category',
        	baseParams: {objid: -1}
    	});
    	
    	var DFTree = new Ext.tree.TreePanel({
	        renderTo: 'DFExplorerUIDiv',
	        useArrows:true,
	        autoScroll:true,
	        animate:true,
	        frame: false,
	        bodyBorder: false,
	        enableDD:true,
	        containerScroll: true,
	        style: 'font-size: 14px;',
	        
	        root:  new Ext.tree.AsyncTreeNode({
		        text: '{$lang['workAndTask']}',
		        draggable:false,
		        objid: 0,
		        objtype: 'WFRoot',
		        id: 'WFRoot',
		        iconCls: 'workHomeFolderIcon'
		    }),
	        loader: DFTreeLoader
	    });
	    
	    DFTree.on('click',function(node,eveny) {
	    	if(node.attributes.objtype != 'registerBookMainFolder' && node.attributes.objtype != 'WFRoot') {
	    		switch(node.attributes.objtype) {
					case 'receiveExtGlobalMainFolder':
						var tabTitle = '{$lang['workitem']['receivedExternalGlobal']}';
		    			var paramPortletClass = 'GlobalReceiveExternalPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					case 'sendExtGlobalMainFolder':
						var tabTitle = '{$lang['workitem']['sendExternalGlobal']}';
		    			var paramPortletClass = 'GlobalSendExternalPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					case 'sendbackMainFolder':
		    			var tabTitle = '{$lang['workitem']['sendbackItem']}';
		    			var paramPortletClass = 'SendbackItemPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'orderAssignMainFolder':
		    			var tabTitle = '{$lang['workitem']['orderItem']}';
		    			var paramPortletClass = 'orderAssignedPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'orderReceivedMainFolder':
		    			var tabTitle = '{$lang['workitem']['receivedItem']}';
		    			var paramPortletClass = 'orderReceivedPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'unreceivedMainFolder':
		    			var tabTitle = '{$lang['workitem']['unreceivedItem']}';
		    			var paramPortletClass = 'UnreceiveItemPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'receivedMainFolder':
		    			var tabTitle = '{$lang['workitem']['awaitingItem']}';
		    			var paramPortletClass = 'ReceivedItemPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'receiveIntMainFolder':
		    			var tabTitle = '{$lang['workitem']['receivedInternal']}';
		    			var paramPortletClass = 'ReceivedInternalPorlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'receiveExtMainFolder':
		    			var tabTitle = '{$lang['workitem']['receivedExternal']}';
		    			var paramPortletClass = 'ReceiveExternalPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'sendIntMainFolder':
		    			var tabTitle = '{$lang['workitem']['sendInternal']}';
		    			var paramPortletClass = 'SendInternalPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'sendExtMainFolder':
		    			var tabTitle = '{$lang['workitem']['sendExternal']}';
		    			var paramPortletClass = 'SendExternalPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'ClassifiedInboundMainFolder':
		    			var tabTitle = '{$lang['workitem']['classifiedInbound']}';
		    			var paramPortletClass = 'ClassifiedInboundPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'ClassifiedOutboundMainFolder':
		    			var tabTitle = '{$lang['workitem']['classifiedOutbound']}';
		    			var paramPortletClass = 'ClassifiedOutboundPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'ClassifiedRegisterMainFolder':
		    			var tabTitle = '{$lang['workitem']['classifiedRegister']}';
		    			var paramPortletClass = 'ClassifiedRegisterPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'outgoingMainFolder':
		    			var tabTitle = '{$lang['workitem']['outgoingItem']}';
		    			var paramPortletClass = 'OutgoingItemPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'committedMainFolder':
		    			var tabTitle = '{$lang['workitem']['committedItem']}';
		    			var paramPortletClass = 'CommittedItemPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'trackMainFolder':
		    			var tabTitle = '{$lang['workitem']['trackItem']}';
		    			var paramPortletClass = 'TrackItemPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'forwardMainFolder':
		    			var tabTitle = '{$lang['workitem']['forwardItem']}';
		    			var paramPortletClass = 'ForwardItemPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'abortMainFolder':
		    			var tabTitle = '{$lang['workitem']['abortItem']}';
		    			var paramPortletClass = 'AbortItemPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'searchMainFolder':
		    			var tabTitle = '{$lang['workitem']['searchFolder']}';
		    			var paramPortletClass = 'SearchFolderPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'receiveCircMainFolder':
		    			var tabTitle = '{$lang['workitem']['receivedCircBook']}';
		    			var paramPortletClass = 'ReceivedCircPortlet';
		    			var paramPortletMethod = 'getUI';
					break;
					
					case 'sendCircMainFolder':
		    			var tabTitle = '{$lang['workitem']['sendCircBook']}';
		    			var paramPortletClass = 'SendCircPortlet';
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
	
	    // dynamically set the tree loader
	    DFTreeLoader.on('beforeload', function(treeLoader, node) {
	    	switch(node.id) {
				/* Static Folder Loading */
				case 'RegisterBook' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-register-book';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
					
				case 'WFRoot' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-workflow-category';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
					
				/* Dynamic Folder Loading */
				case 'ReceivedExternalGlobal':
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-recv-extglobal';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'SenddExternalGlobal' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-send-extglobal';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'UnreceivedItem' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-unreceived-folder';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'ReceivedItem' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-received-folder';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'ReceivedInternal' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-received-internal';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'ReceiveExternal' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-received-external';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'SendInternal' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-send-internal';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'SendExternal' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-send-external';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'ClassifiedInbound' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-classified-inbound';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'ClassifiedOutbound' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-classified-outbound';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'OutgoingItem' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-outgoing-folder';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'CommittedItem' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-committed-folder';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'TrackedItem' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-track-folder';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'DraftedItem' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-draft-folder';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'AbortedItem' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-abort-folder';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;
				case 'SearchFolder' :
					DFTreeLoader.dataUrl = '/{$config ['appName']}/docflow-explorer/load-search-folder';
					DFTreeLoader.baseParams.objid = node.attributes.objid;
					break;					
			}
		}, this);
	    
	    // render the tree
	    DFTree.render();
	    DFTree.root.expand();
	    //Ext.getCmp('RegisterBook').expand();
		
    	</script>";
	}
	
	/**
	 * ทำการ Load ประเภทงานสารบรรณ
	 *
	 */
	function loadWorkflowCategoryAction() {
		global $lang;
		global $policy;
		global $sessionMgr;
		
		$node = Array ();
		if ($policy->canReceiveInternal ()) {
			$node[] = Array ("id" => "UnreceivedItem", "objid" => "1", "objtype" => "unreceivedMainFolder", "text" => UTFEncode( $lang ['workitem'] ['unreceivedItem'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "unreceiveIcon" );
		}
		if($policy->canSendInternal() || $policy->canSendExternal() || $policy->canSendExternalGlobal()) {
			$node[] = Array ("id" => "SendbackItem", "objid" => "10", "objtype" => "sendbackMainFolder", "text" => UTFEncode( $lang ['workitem'] ['sendbackItem'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "receiveIcon" );
		}
		
		if($sessionMgr->isGoverner()) {
			$node[] = Array ("id" => "AwaitingItem", "objid" => "12", "objtype" => "receivedMainFolder", "text" => UTFEncode( $lang ['workitem'] ['awaitingItem'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "receiveIcon" );
		} 
		$node[] = Array ("id" => "OrderItem", "objid" => "13", "objtype" => "orderAssignMainFolder", "text" => UTFEncode( $lang ['workitem'] ['orderItem'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "receiveIcon" );
		
		$node[] = Array ("id" => "ReceivedItem", "objid" => "2", "objtype" => "orderReceivedMainFolder", "text" => UTFEncode( $lang ['workitem'] ['receivedItem'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "receiveIcon" );
		$node [] = Array ("id" => "CommittedItem", "objid" => "5", "objtype" => "committedMainFolder", "text" => UTFEncode( $lang ['workitem'] ['committedItem'] ), "draggable" => "false", "iconCls" => "commitFolderIcon", "leaf" => "true" );
		$node [] = Array ("id" => "TrackedItem", "objid" => "6", "objtype" => "trackMainFolder", "text" => UTFEncode( $lang ['workitem'] ['trackItem'] ), "draggable" => "false", "iconCls" => "trackFolderIcon", "leaf" => "true" );
		if($policy->canSendInternal() || $policy->canSendExternal() || $policy->canSendExternalGlobal()) {
			$node [] = Array ("id" => "OutgoingItem", "objid" => "4", "objtype" => "outgoingMainFolder", "text" => UTFEncode( $lang ['workitem'] ['outgoingItem'] ), "draggable" => "false", "iconCls" => "sentFolderIcon", "leaf" => "true" );
		}
		if($policy->canSendInternal() || $policy->canSendExternal() || $policy->canSendExternalGlobal()) {
			$node [] = Array ("id" => "ForwardedItem", "objid" => "11", "objtype" => "forwardMainFolder", "text" => UTFEncode( $lang ['workitem'] ['forwardItem'] ), "draggable" => "false", "iconCls" => "sentFolderIcon" , "leaf" => "true");
		}
		if ($policy->canReceiveExternalGlobal ()) {
			$node [] = Array ("id" => "ReceivedExternalGlobal", "objid" => "3_7", "objtype" => "receiveExtGlobalMainFolder", "text" => UTFEncode( $lang ['workitem'] ['receivedExternalGlobal'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "registerBookFolderIcon" );
		}
		
		if ($policy->canSendExternalGlobal ()) {
			$node [] = Array ("id" => "SendExternalGlobal", "objid" => "3_8", "objtype" => "sendExtGlobalMainFolder", "text" => UTFEncode( $lang ['workitem'] ['sendExternalGlobal'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "registerBookFolderIcon" );
		}
		
		if ($policy->isSecretAgent () && $policy->canAccessClassifiedRegister()) {
			$node [] = Array ("id" => "ClassifiedInbound", "objid" => "3_5", "objtype" => "ClassifiedInboundMainFolder", "text" => UTFEncode( $lang ['workitem'] ['classifiedInbound'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "registerBookFolderIcon" );
			$node [] = Array ("id" => "ClassifiedOutbound", "objid" => "3_6", "objtype" => "ClassifiedOutboundMainFolder", "text" => UTFEncode( $lang ['workitem'] ['classifiedOutbound'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "registerBookFolderIcon" );
		}
		
		/*
		if ($policy->canAccessClassifiedRegister ()) {
			$node [] = Array ("id" => "ClassifiedRegister", "objid" => "3_9", "objtype" => "ClassifiedRegisterMainFolder", "text" => UTFEncode( $lang ['workitem'] ['classifiedRegister'] ), "draggable" => "false", "leaf" => "true", "iconCls" => "registerBookFolderIcon" );
		}
		*/
		$node [] = Array ("id" => "RegisterBook", "objid" => "3", "objtype" => "registerBookMainFolder", "text" => UTFEncode( $lang ['workitem'] ['registerBook'] ), "draggable" => "false", "iconCls" => "registerBookFolderIcon" );
		
		
		
		/* TODO: To be perfectioning  */
		/*,
			Array(
				"id"=>"DraftedItem",
				"objid"=>"7",
				"objtype"=>"draftMainFolder",
				"text"=>UTFEncode($lang['workitem']['draftItem']),
				"draggable"=>"false",
				"iconCls"=>"draftFolderIcon"
			),
			Array(
				"id"=>"AbortedItem",
				"objid"=>"8",
				"objtype"=>"abortMainFolder",
				"text"=>UTFEncode($lang['workitem']['abortItem']),
				"draggable"=>"false",
				"iconCls"=>"abortFolderIcon"
			),
			Array(
				"id"=>"SearchFolder",
				"objid"=>"9",
				"objtype"=>"searchMainFolder",
				"text"=>UTFEncode($lang['workitem']['searchFolder']),
				"draggable"=>"false",
				"iconCls"=>"searchFolderIcon"
			)*/
		//,Array(
		//	"id"=>"DeletedItem",
		//	"objid"=>"10",
		//	"text"=>"Deleted Item",
		//	"draggable"=>"false",
		//	"iconCls"=>"trashIcon"
		//)
		echo json_encode ( $node );
	}
	
	/**
	 * ทำการโหลดเล่มทะเบียน
	 *
	 */
	function loadRegisterBookAction() {
		global $lang;
		$node = Array ();
		$node = Array (
			Array ("id" => "ReceivedInternal", "objid" => "3_1", "objtype" => "receiveIntMainFolder", "text" => UTFEncode( $lang ['workitem'] ['receivedInternal'] ), "draggable" => "false", "iconCls" => "registerBookFolderIcon" ),
			Array ("id" => "ReceivedCirc", "objid" => "3_5", "objtype" => "receiveCircMainFolder", "text" => UTFEncode( $lang ['workitem'] ['receivedCircBook'] ), "draggable" => "false", "iconCls" => "registerBookFolderIcon" ), 
			Array ("id" => "ReceiveExternal", "objid" => "3_2", "objtype" => "receiveExtMainFolder", "text" => UTFEncode( $lang ['workitem'] ['receivedExternal'] ), "draggable" => "false", "iconCls" => "registerBookFolderIcon" ), 			 
			
			Array ("id" => "SendInternal", "objid" => "3_3", "objtype" => "sendIntMainFolder", "text" => UTFEncode( $lang ['workitem'] ['sendInternal'] ), "draggable" => "false", "iconCls" => "registerBookFolderIcon" ),
			Array ("id" => "SendCirc", "objid" => "3_6", "objtype" => "sendCircMainFolder", "text" => UTFEncode( $lang ['workitem'] ['sendCircBook'] ), "draggable" => "false", "iconCls" => "registerBookFolderIcon" ), 
			Array ("id" => "SendExternal", "objid" => "3_4", "objtype" => "sendExtMainFolder", "text" => UTFEncode( $lang ['workitem'] ['sendExternal'] ), "draggable" => "false", "iconCls" => "registerBookFolderIcon" ) 
			);
		
		echo json_encode ( $node );
	}
	
	
	/*
	function loadUnreceivedFolderAction() {
		//TODO: Not Implemented Load Unreceived Folder Yet
	}
	
	function loadReceivedFolderAction() {
		//TODO: Not Implemented Load Received Folder Yet
	}
	
	function loadReceivedInternalAction() {
		//TODO: Not Implemented Load Received Internal Yet
	}
	
	function loadReceivedExternalAction() {
		//TODO: Not Implemented Load Received External Yet
	}
	
	function loadSendInternalAction() {
		//TODO: Not Implemented Load Sent Internal Yet
	}
	
	function loadSendExternalAction() {
		//TODO: Not Implemented Load Sent External Yet
	}
	
	function loadClassifiedInboundAction() {
		//TODO: Not Implemented Classified Inbound yet
	}
	
	function loadClassifiedOutboundAction() {
		//TODO: Not Implemented Classified Outbound Yet
	}
	
	function loadOutgoingFolderAction() {
		//TODO: Not Implemented Outgoing Folder Yet
	}
	
	function loadCommittedFolderAction() {
		//TODO: Not Implemented Committed Folder Yet
	}
	
	function loadTrackFolderAction() {
		//TODO: Not Implemented Tracked Folder Yet
	

	}
	
	function loadDraftFolderAction() {
		//TODO: Not Implemented Draft Folder Yet
	}
	
	function loadAbortFolderAction() {
		//TODO: Not Implemented Aborted Folder Yet
	}
	
	function loadSearchFolderAction() {
		//TODO: Not Implemented Search Folder Yet
	}
	*/

}
