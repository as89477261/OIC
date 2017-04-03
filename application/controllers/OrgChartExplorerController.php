<?php
/**
 * โปรแกรมแสดงโครงสร้างหน่วยงาน	 แบบ Explorer
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 * 
 *
 */

class OrgChartExplorerController extends Zend_Controller_Action {
	/**
	 * action /get-ui สำหรับแสดง Explorer
	 *
	 */
	public function getUiAction() {
		global $config;
        global $lang;
		
		checkSessionPortlet();
		//global $store;

		//include_once ('Organize.Entity.php');
		
		$orgUnit = new OrganizeEntity ( );
		$orgUnit->Load ( "f_org_pid = '-1'" );
		
		/* prepare DIV For UI */
		echo "<div id=\"OrgExplorerUIDiv\" display=\"inline\"></div>";
		
		echo "<script type=\"text/javascript\">
		    	
		var OrgChartTreeLoader = new Ext.tree.TreeLoader({
        	dataUrl   : '/{$config ['appName']}/org-chart-explorer/load-org-tree',
        	baseParams: {objid: -1}
    	});

    	var OrgChartTree = new Ext.tree.TreePanel({
	        renderTo: 'OrgExplorerUIDiv',
	        useArrows:true,
	        autoScroll:true,
	        animate:true,
	        frame: false,
	        bodyBorder: false,
	        enableDD:true,
			split:true,
			width: '100%',
			height: Ext.getCmp('OrgExplorer').getInnerHeight(),
			ddGroup : 'TreeDD',
			dropConfig: {appendOnly:true},
	        containerScroll: true,
	        root:  new Ext.tree.AsyncTreeNode({
		        text: '{$orgUnit->f_org_name}',
		        draggable:false,
		        objid: {$orgUnit->f_org_id},
		        id: 'OrgChartRoot',
		        iconCls: 'homeIcon'
		    }),
	        loader: OrgChartTreeLoader
	    });
	
	    // set the root node
	   	// OrgChartTreeLoader.on('beforeload', function(treeLoader, node) {
		//	OrgChartTreeLoader.dataUrl = '/{$config ['appName']}/org-chart-explorer/load-org-tree';
		//	OrgChartTreeLoader.baseParams.objid = node.attributes.objid;
		//}, this);
	    
		
		OrgChartTreeLoader.on('beforeload', function(treeLoader, node) {
			if(node.attributes.type == 2) {
				OrgChartTreeLoader.dataUrl = '/{$config ['appName']}/org-chart-explorer/load-user-in-role';
			} else {
				OrgChartTreeLoader.dataUrl = '/{$config ['appName']}/org-chart-explorer/load-org-tree';
			}
			treeLoader.baseParams.objid = node.attributes.objid;
		}, this);
	
	
	    // render the tree
	    OrgChartTree.render();
        
        var orgChartContext = new Ext.menu.Menu('OrgChartContext');
        orgChartContext.add(
            new Ext.menu.Item({id:'mnuItemStatistics', text: 'สถิติงานหนังสือ', iconCls: 'mainOrgIcon'}),            
            new Ext.menu.Separator(),
            new Ext.menu.Item({id:'mnuItemRefreshOrgChart',text: '{$lang['context']['orgMrg']['refresh']}', iconCls: 'refreshIcon'})
        );
        
        OrgChartTree.on('contextmenu', function(node,e) {   
            if(node.attributes.type >= 1) {
                Ext.getCmp('mnuItemStatistics').disable();
            } else {
                Ext.getCmp('mnuItemStatistics').enable();
            }
            Cookies.set('ctOCT',node.id);         
            orgChartContext.showAt(e.getXY());
        }, OrgChartTree);
        
        
        {$this->getStatisticsJS()}   
        
        Ext.getCmp('mnuItemStatistics').on('click',function(obj,e) {
            orgChartStatsForm.getForm().reset();
            orgChartStatsWindow.show();
            orgChartStatsForm.getForm().setValues(
            [
                {id: 'localOrgName',value: OrgChartTree.getNodeById(Cookies.get('ctOCT')).attributes.text}     
            ]
            );            
            Ext.Ajax.request({
                url: '/{$config ['appName']}/df-action/request-stats',
                method: 'POST',
                success: function(o){
                    Ext.MessageBox.hide();
                    var r = Ext.decode(o.responseText);
                    orgChartStatsForm.getForm().setValues(
                    [
                        {id: 'noRecvInt',value: r.recvInt}
                        ,{id: 'noSendInt',value: r.sendInt}
                        ,{id: 'noRecvExt',value: r.recvExt}
                        ,{id: 'noSendExt',value: r.sendExt}
                        ,{id: 'noRecvClassified',value: r.recvClass}
                        ,{id: 'noSendClassified',value: r.sendClass}
                        ,{id: 'noRecvCirc',value: r.recvCirc}
                        ,{id: 'noSendCirc',value: r.sendCirc}
                        ,{id: 'noUnreceive',value: r.unreceive}
                        ,{id: 'noSendback',value: r.sendback}
                        ,{id: 'noCallback',value: r.callback}
                        ,{id: 'noWaitCommand',value: r.waitCommand}
                        ,{id: 'noWorking',value: r.working}
                        ,{id: 'noComplete',value: r.complete}
                    ]
                    );     
                },
                failure: function(r,o) {
                },
                params: {orgID: OrgChartTree.getNodeById(Cookies.get('ctOCT')).attributes.objid}
            });           
        });
        
        
        Ext.getCmp('mnuItemRefreshOrgChart').on('click',function(obj,e) {
            OrgChartTree.getNodeById(Cookies.get('ctOCT')).reload();
        });
	    
	    //DMSRoot.expand();
	    //DMSRoot.expand(false, /*no anim*/ false);
    	//alert(DMSRoot);
		
    	</script>";
	}
    
	/**
	 * สร้างแบบฟอร์มสำหรับแสดง Detail ของหน่วยงาน
	 *
	 * @return string
	 */
    public function getStatisticsJS() {
        global $config;
        global $lang;
        
        $js = "var orgChartStatsForm = new Ext.form.FormPanel({
            id: 'orgChartStatsForm',
            baseCls: 'x-plain',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid:true,

            items: [{
                fieldLabel: '{$lang['label']['orgUnit']}',
                id: 'localOrgName',
                name: 'localOrgName',
                readOnly: false,
                width: 200
            },{
                fieldLabel: 'รายการรับภายใน',
                id: 'noRecvInt',
                allowBlank: false,
                name: 'noRecvInt'
            },{
                fieldLabel: 'รายการส่งภายใน',
                id: 'noSendInt',
                allowBlank: false,
                name: 'noSendInt'
            },{
                fieldLabel: 'รายการรับภายนอก',
                id: 'noRecvExt',
                allowBlank: false,
                name: 'noRecvExt'
            },{
                fieldLabel: 'รายการส่งภายนอก',
                id: 'noSendExt',
                allowBlank: false,
                name: 'noSendExt'
            },{
                fieldLabel: 'รายการรับ(ทะเบียนลับ)',
                id: 'noRecvClassified',
                allowBlank: false,
                name: 'noRecvClassified'
            },{
                fieldLabel: 'รายการส่ง(ทะเบียนลับ)',
                id: 'noSendClassified',
                allowBlank: false,
                name: 'noSendClassified'
            },{
                fieldLabel: 'รายการรับเวียน',
                id: 'noRecvCirc',
                allowBlank: false,
                name: 'noRecvCirc'
            },{
                fieldLabel: 'รายการส่งเวียน',
                id: 'noSendCirc',
                allowBlank: false,
                name: 'noSendCirc'
            },{
                fieldLabel: 'รายการรอลงรับ',
                id: 'noUnreceive',
                allowBlank: false,
                name: 'noUnreceive'
            },{
                fieldLabel: 'รายการส่งกลับ',
                id: 'noSendback',
                allowBlank: false,
                name: 'noSendback'
            },{
                fieldLabel: 'รายการดึงคืน',
                id: 'noCallback',
                allowBlank: false,
                name: 'noCallback'
            },{
                fieldLabel: 'หนังสือรอสั่งการ',
                id: 'noWaitCommand',
                allowBlank: false,
                name: 'noWaitCommand'
            },{
                fieldLabel: 'หนังสือกำลังดำเนินการ',
                id: 'noWorking',
                allowBlank: false,
                name: 'noWorking'
            },{
                fieldLabel: 'หนังสือดำเนินการเสร็จสิ้น',
                id: 'noComplete',
                allowBlank: false,
                name: 'noComplete'
            }],
            buttons: [{
                //id: 'btnCancelSaveLocalRegNo',
                text: 'Close',
                iconCls: 'cancelIcon',
                handler: function() {
                    orgChartStatsWindow.hide();
                }
            }]
        });";
        
        $js .= "var orgChartStatsWindow = new Ext.Window({
            id: 'orgChartStatsWindow',
            title: 'สถิติงานหนังสือ',
            width: 385,
            height: 480,
            modal: true,
            minWidth: 385,
            minHeight: 480,
            layout: 'fit',
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: orgChartStatsForm,
            closable: false
        });";
        /*
        $js .= "function orgAddSuccess() {
            Ext.MessageBox.hide();
            
            orgTree.getNodeById(Cookies.get('contextElID')).reload();
            
            Ext.MessageBox.show({
                title: 'Organization Structure Manager',
                msg: 'Account Added!',
                buttons: Ext.MessageBox.OK,
                //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                icon: Ext.MessageBox.INFO
            });
        }";
        
        $js .= "function orgAddFailed() {
            Ext.MessageBox.hide();
            
            Ext.MessageBox.show({
                title: 'Organization Structure Manager',
                msg: '{$lang['common']['error']}',
                buttons: Ext.MessageBox.OK,
                //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                icon: Ext.MessageBox.ERROR
            });
        }";*/
        return $js;
    }
	
    /**
     * action /load-org-tree1 (ยกเลิก???)
     *
     */
	public function loadOrgTree1Action() {
		global $conn;
		$objid = $_POST ['objid'];
		
		if ($objid == - 1) {
			$sql = "select * from tbl_organize where f_org_id = 0 ";
		} else {
			$sql = "select * from tbl_organize where f_org_pid = '{$objid}' ";
		}
		$rsGetOrg = $conn->Execute ( $sql );
		$node = Array ();
		foreach ( $rsGetOrg as $row ) {
			checkKeyCase($row);
			$nodeTemp = Array ();
			$nodeTemp ['id'] = $row ['f_org_id'];
			$nodeTemp ['objid'] = $row ['f_org_id'];
			$nodeTemp ['text'] = UTFEncode( $row ['f_org_name'] );
			if ($row ['f_org_pid'] == - 1) {
				$nodeTemp ['isroot'] = 1;
			} else {
				$nodeTemp ['isroot'] = 0;
			}
			//$nodeTemp ['leaf'] = 'false';
			$nodeTemp ['type'] = $row ['f_org_type'];
			$nodeTemp ['cls'] = 'master-task';
			if ($row ['f_org_type'] == 0) {
				$nodeTemp ['iconCls'] = 'mainOrgIcon';
			}
			
			if ($row ['f_org_type'] == 1) {
				$nodeTemp ['iconCls'] = 'subOrgIcon';
			}
			
			$node [] = $nodeTemp;
		}
		
		echo json_encode ( $node );
	}
	
	/**
	 * action /load-org-tree ทำการส่งข้อมูลโครงสร้างหน่วยงาน
	 *
	 */
	public function loadOrgTreeAction() {
		global $conn;
		$objid = $_POST ['objid'];    
		$node = Array (); 
		
        
		$checkLeaf = "select count(f_role_id) as role_count from tbl_role where f_org_id = '{$objid}'";
		$rsCheckLeaf = $conn->Execute ( $checkLeaf );
		$checkLeaf = $rsCheckLeaf->FetchNextObject ();
		if ($checkLeaf->ROLE_COUNT > 0) {
            $sqlGetRoles = "select a.* from tbl_role a,tbl_position_master b where a.f_org_id = '{$objid}' and a.f_pos_id = b.f_pos_id order by b.f_pos_level asc";
			//$sqlGetRoles = "select * from tbl_role where f_org_id = '{$objid}'";
			$rsGetRoles = $conn->Execute ( $sqlGetRoles );
			
			foreach ( $rsGetRoles as $role ) {
				checkKeyCase($role);
				$tmpRole = Array ();
				$tmpRole ['id'] = "r_" . $role ['f_role_id'];
				$tmpRole ['objid'] = $role ['f_role_id'];
				$tmpRole ['text'] = UTFEncode( $role ['f_role_name'] );
				//$tmpRole ['description'] = UTFEncode( $role ['f_role_desc'] );
				$tmpRole ['type'] = 2;
				$tmpRole ['treetype'] = 'orgchart';
				//$tmpRole ['uiProvider'] = 'col';
				$tmpRole ['cls'] = 'master-task';
				$tmpRole ['iconCls'] = 'roleIcon';
				$tmpRole ['isroot'] = 0;
				
				$node [] = $tmpRole;
			}
		}        
        
        if ($objid == - 1) {
            $sql = "select * from tbl_organize where f_org_id = 0 ";
        } else {
            $sql = "select * from tbl_organize where f_org_pid = '{$objid}' ";
        }
        $rsGetOrg = $conn->Execute ( $sql );
        
        foreach ( $rsGetOrg as $row ) {
            checkKeyCase($row);
            $nodeTemp = Array ();
            $nodeTemp ['id'] = "o_" . $row ['f_org_id'];
            $nodeTemp ['objid'] = $row ['f_org_id'];
            $nodeTemp ['text'] = UTFEncode( $row ['f_org_name'] );
            //$nodeTemp ['description'] = UTFEncode( $row ['f_org_desc'] );
            if ($row ['f_org_pid'] == - 1) {
                $nodeTemp ['isroot'] = 1;
            } else {
                $nodeTemp ['isroot'] = 0;
            }
            $nodeTemp ['type'] = $row ['f_org_type'];
            $nodeTemp ['treetype'] = 'orgchart';
            //$nodeTemp ['uiProvider'] = 'col';
            $nodeTemp ['cls'] = 'master-task';
            if ($row ['f_org_type'] == 0) {
                $nodeTemp ['iconCls'] = 'mainOrgIcon';
            }
            
            if ($row ['f_org_type'] == 1) {
                $nodeTemp ['iconCls'] = 'subOrgIcon';
            }
            
            $node [] = $nodeTemp;
        }   
		echo json_encode ( $node );
	}
	
	/**
	 * action /load-user-in-role ทำการส่งข้อมูลผู้ใช้ในตำแหน่ง
	 *
	 */
	public function loadUserInRoleAction() {
		global $conn;
		$objid = $_POST ['objid'];
		$sql = "select a.f_role_id,b.f_acc_id,b.f_name,b.f_last_name from tbl_passport a ,tbl_account b where a.f_acc_id = b.f_acc_id and a.f_role_id = '{$objid}'";
		$rsGetOrg = $conn->Execute ( $sql );
		$node = Array ();
		foreach ( $rsGetOrg as $row ) {
			checkKeyCase($row);
			$nodeTemp = Array ();
			$nodeTemp ['id'] = "u_" . $row ['f_acc_id'];
			$nodeTemp ['objid'] = $row ['f_acc_id'];
			$nodeTemp ['text'] = UTFEncode( $row ['f_name'] . " " . $row ['f_last_name'] );
			//$nodeTemp ['description'] = "";
			$nodeTemp ['type'] = 3;
			//$nodeTemp ['uiProvider'] = 'col';
			$nodeTemp ['treetype'] = 'orgchart';
			$nodeTemp ['cls'] = 'master-task';
			$nodeTemp ['isroot'] = 0;
			$nodeTemp ['iconCls'] = 'userIcon';
			$nodeTemp ['leaf'] = 'true';
			$node [] = $nodeTemp;
		}
		echo json_encode ( $node );
	}
}
