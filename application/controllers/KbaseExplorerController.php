<?php
/**
 * หน้าจอแสดง Knowledge ฺBase Explorer 
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category KBase
 */

class KBaseExplorerController extends ECMController {
	
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
	}
	
	/**
	 * redirect ไป /get-ui
	 *
	 */
	public function indexAction() {
		$this->_redirector->gotoUrl ( '/kbase-explorer/get-ui' );
	}
	
	/**
	 * action /get-ui แสดงหน้าจอ Explorer
	 *
	 */
	public function getUiAction() {
		global $config;
		global $lang;
		
		/* prepare DIV For UI */
		echo "<div id=\"KBExplorerUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		    	
		var KBTreeLoader = new Ext.tree.TreeLoader({
        	dataUrl   : '/{$config ['appName']}/kbase-explorer/load-kb-tree',
        	baseParams: {objid: -1}
    	});
    	
    	var KBTree = new Ext.tree.TreePanel({
	        renderTo: 'KBExplorerUIDiv',
	        useArrows:true,
	        autoScroll:true,
	        animate:true,
	        frame: false,
	        bodyBorder: false,
	        enableDD:true,
	        containerScroll: true,
	        root:  new Ext.tree.AsyncTreeNode({
		        text: '{$lang['KB']}',
		        draggable:false,
		        objid: 0,
		        id: 'KBRoot',
		        iconCls: 'KBHomeFolderIcon'
		    }),
	        loader: KBTreeLoader
	    });
	
	    // set the root node
	    KBTreeLoader.on('beforeload', function(treeLoader, node) {
			KBTreeLoader.dataUrl = '/{$config ['appName']}/kbase-explorer/load-kb-tree';
			KBTreeLoader.baseParams.objid = node.attributes.objid;
		}, this);
	    
	    // render the tree
	    KBTree.render();
	    
	    KBTree.root.expand();
	    
    	</script>";
	}
	
	/**
	 * action /load-kb-tree ทำการส่งข้อมูลโครงสร้าง KBase
	 *
	 */
	public function loadKbTreeAction() {
		global $conn;
		$objid = $_POST ['objid'];
		
		if ($objid == - 1) {
			$sql = "select * from tbl_kb_object where f_kb_pid = 0 ";
		} else {
			$sql = "select * from tbl_kb_object where f_kb_pid = '{$objid}' ";
		}
		
		//echo $sql;
		//die();
		$rsGetOrg = $conn->Execute ( $sql );
		$node = Array ();
		foreach ( $rsGetOrg as $row ) {
			checkKeyCase($row);
			$nodeTemp = Array ();
			$nodeTemp ['id'] = $row ['f_kb_id'];
			$nodeTemp ['objid'] = $row ['f_kb_id'];
			$nodeTemp ['objtype'] = $row ['f_kb_type'];
			$nodeTemp ['text'] = UTFEncode( $row ['f_name'] );
			if ($row ['f_kb_pid'] == - 1) {
				$nodeTemp ['isroot'] = 1;
			} else {
				$nodeTemp ['isroot'] = 0;
			}
			//$nodeTemp ['leaf'] = 'false';
			$nodeTemp ['type'] = $row ['f_kb_type'];
			$nodeTemp ['cls'] = 'master-task';
			//$nodeTemp ['leaf'] = 'false';
			switch ($row ['f_kb_type']) {
				case 1 :
					$nodeTemp ['iconCls'] = 'KBCategoryIcon';
					break;
				case 2 :
					$nodeTemp ['iconCls'] = 'knowledgeIcon';
					$nodeTemp ['leaf'] = 'false';
					break;
			}
			
			$node [] = $nodeTemp;
		}
		
		echo json_encode ( $node );
	}

}
