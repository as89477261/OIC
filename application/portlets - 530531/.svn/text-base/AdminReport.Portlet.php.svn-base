<?php
/**
 * Portlet : รายงานผู้ดูแลระบบ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class AdminReportPortlet {
	public function getUI() {
		
		checkSessionPortlet();
		
		$script = "Ext.get('reportIndexStatus').on('click',function() {
                isrForm.getForm().reset();
                isrWindow.show();
        },this);
        
        Ext.get('reportUserUsage').on('click',function() {
                userUsageForm.getForm().reset();
                userUsageWindow.show();
        },this);
        
        Ext.get('reportAdminUsage').on('click',function() {
                adminUsageForm.getForm().reset();
                adminUsageWindow.show();
        },this);
		
		Ext.get('reportDMSCreateDocument').on('click',function() {
                createDMSForm.getForm().reset();
                createDMSWindow.show();
        },this);
		
		";
		
		$html = "<ol>
			<li><span id=\"reportIndexStatus\" style=\"cursor:pointer;\" >รายงานสถานะดัชนี</span></li>
			<li><span id=\"reportUserUsage\"  style=\"cursor:pointer;\">รายงานการเข้าใช้ระบบของผู้ใช้</span></li>
			<li><span id=\"reportAdminUsage\"  style=\"cursor:pointer;\">รายงานการเข้าใช้ระบบของผู้ดูแลระบบ</span></li>
			<li><span id=\"reportDMSCreateDocument\"  style=\"cursor:pointer;\">รายงานสถิติการสร้างเอกสาร</span></li>
		</ol>
		<script type=\"text/javascript\">
		{$this->getIndexStatusReportJS()}
		{$this->getUserUsageReportJS()}
		{$this->getAdminUsageReportJS()}
		{$this->getDMSCreateReportJS()}
		{$script}
		
		</script>		
		";
		echo $html;
	}
	
	private function getIndexStatusReportJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;
        
        
        $js = "
        
        
        var isrFrom = new Ext.ux.DateTimeField ({
                fieldLabel: 'จากวันที่',    
                id: 'isrFrom',
                name: 'isrFrom',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       	var isrTo = new Ext.ux.DateTimeField ({
                fieldLabel: 'ถึงวันที่',    
                id: 'isrTo',
                name: 'isrTo',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
        
        var isrForm = new Ext.form.FormPanel({
            id: 'isrForm',
			url: '/{$config['appName']}/system-report/dms-stats',
            baseCls: 'x-plain',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [isrFrom,isrTo],
            buttons: [{
                text: 'เรียกดู',
                formBind: true,
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {
                    var form = Ext.getCmp('isrForm');
					form.getForm().getEl().dom.target = '_blank';
					form.getForm().getEl().dom.action = form.url;
					form.getForm().getEl().dom.submit();

                    isrWindow.hide();                    
                }
            },{
                text: 'ยกเลิก',
                iconCls: 'rejectIcon',
                handler: function() {
                    isrWindow.hide();
                }
            }]
        });";
        
        $js .= "var isrWindow = new Ext.Window({
            id: 'isrWindow',
            title: 'รายงานสถานะของดัชนี',
            width: 300,
            height: 125,
            minWidth: 300,
            minHeight: 125,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: isrForm,
            closable: false
        });        
        ";
                               
        return $js;
    }
    
	private function getUserUsageReportJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;
        
        
        $js = "
        
        
        var userUsageFrom = new Ext.ux.DateTimeField ({
                fieldLabel: 'จากวันที่',    
                id: 'userUsageFrom',
                name: 'userUsageFrom',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       	var userUsageTo = new Ext.ux.DateTimeField ({
                fieldLabel: 'ถึงวันที่',    
                id: 'userUsageTo',
                name: 'userUsageTo',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
        
        var userUsageForm = new Ext.form.FormPanel({
            id: 'userUsageForm',
            baseCls: 'x-plain',
            url: '/{$config['appName']}/system-report/user-usage',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [userUsageFrom,userUsageTo],
            buttons: [{
                text: 'เรียกดู',
                formBind: true,
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {
					var form = Ext.getCmp('userUsageForm');
					form.getForm().getEl().dom.target = '_blank';
					form.getForm().getEl().dom.action = form.url;
					form.getForm().getEl().dom.submit();
                
                    userUsageWindow.hide();                    
                }
            },{
                text: 'ยกเลิก',
                iconCls: 'rejectIcon',
                handler: function() {
                    userUsageWindow.hide();
                }
            }]
        });";
        
        $js .= "var userUsageWindow = new Ext.Window({
            id: 'userUsageWindow',
            title: 'รายงานสถิติการเข้าใช้งานของผู้ใช้งาน',
            width: 300,
            height: 125,
            minWidth: 300,
            minHeight: 125,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: userUsageForm,
            closable: false
        });        
        ";
                               
        return $js;
    }

	private function getAdminUsageReportJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;
        
        
        $js = "
        
        
        var adminUsageFrom = new Ext.ux.DateTimeField ({
                fieldLabel: 'จากวันที่',    
                id: 'adminUsageFrom',
                name: 'adminUsageFrom',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       	var adminUsageTo = new Ext.ux.DateTimeField ({
                fieldLabel: 'ถึงวันที่',    
                id: 'adminUsageTo',
                name: 'adminUsageTo',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
        
        var adminUsageForm = new Ext.form.FormPanel({
            id: 'adminUsageForm',
            baseCls: 'x-plain',
            url: '/{$config['appName']}/system-report/admin-usage',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [adminUsageFrom,adminUsageTo],
            buttons: [{
                text: 'เรียกดู',
                formBind: true,
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {
                    var form = Ext.getCmp('adminUsageForm');
					form.getForm().getEl().dom.target = '_blank';
					form.getForm().getEl().dom.action = form.url;
					form.getForm().getEl().dom.submit();
                
                    adminUsageWindow.hide();                    
                }
            },{
                text: 'ยกเลิก',
                iconCls: 'rejectIcon',
                handler: function() {
                    adminUsageWindow.hide();
                }
            }]
        });";
        
        $js .= "var adminUsageWindow = new Ext.Window({
            id: 'adminUsageWindow',
            title: 'รายงานสถิติการเข้าใช้งานของผู้ดูแลระบบ',
            width: 300,
            height: 125,
            minWidth: 300,
            minHeight: 125,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: adminUsageForm,
            closable: false
        });        
        ";
                               
        return $js;
    }

	private function getDMSCreateReportJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;
        
        
        $js = "
        
        
        var createDMSFrom = new Ext.ux.DateTimeField ({
                fieldLabel: 'จากวันที่',    
                id: 'createDMSFrom',
                name: 'createDMSFrom',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       	var createDMSTo = new Ext.ux.DateTimeField ({
                fieldLabel: 'ถึงวันที่',    
                id: 'createDMSTo',
                name: 'createDMSTo',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
        
        var createDMSForm = new Ext.form.FormPanel({
            id: 'createDMSForm',
            baseCls: 'x-plain',
			url: '/{$config['appName']}/system-report/dms-create-stats',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [createDMSFrom,createDMSTo],
            buttons: [{
                text: 'เรียกดู',
                formBind: true,
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {
                    var form = Ext.getCmp('createDMSForm');
					form.getForm().getEl().dom.target = '_blank';
					form.getForm().getEl().dom.action = form.url;
					form.getForm().getEl().dom.submit();
                    createDMSWindow.hide();                    
                }
            },{
                text: 'ยกเลิก',
                iconCls: 'rejectIcon',
                handler: function() {
                    createDMSWindow.hide();
                }
            }]
        });";
        
        $js .= "var createDMSWindow = new Ext.Window({
            id: 'createDMSWindow',
            title: 'รายงานการสร้างเอกสาร',
            width: 300,
            height: 125,
            minWidth: 300,
            minHeight: 125,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: createDMSForm,
            closable: false
        });        
        ";
                               
        return $js;
    }
}