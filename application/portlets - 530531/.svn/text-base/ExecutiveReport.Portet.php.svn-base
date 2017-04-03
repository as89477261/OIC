<?php
/**
 * Portlet : รายงานผู้บริหาร
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class ExecutiveReportPortlet {
	public function getUI() {
		
		checkSessionPortlet();
		
		$script = "Ext.get('reportSummarySpan').on('click',function() {
                reportSummaryForm.getForm().reset();
                reportSummaryWindow.show();
        },this);
        
        Ext.get('reportRegisterSpan').on('click',function() {
                reportRegisterForm.getForm().reset();
                reportRegisterWindow.show();
        },this);
        
        Ext.get('reportAnnounceSpan').on('click',function() {
                reportAnnounceForm.getForm().reset();
                reportAnnounceWindow.show();
        },this);";
		
		$html = "<ol>
			<li><span id=\"reportSummarySpan\">รายงานสรุปการรับ-ส่งหนังสือ</span></li>
			<li><span id=\"reportRegisterSpan\">รายงานทะเบียนหนังสือ</span></li>
			<li><span id=\"reportAnnounceSpan\">รายงานคำสั่ง/ประกาศ/ระเบียบ/อื่นๆ</span></li>
		</ol>
		<script type=\"text/javascript\">
		{$this->getSummaryReportJS()}
		{$this->getRegisterReportJS()}
		{$this->getAnnounceReportJS()}
		
		{$script}
		</script>		
		";
		echo $html;
	}
	
	private function getSummaryReportJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;
        
        
        $js = "
        
        
        var dateSummaryFrom = new Ext.ux.DateTimeField ({
                fieldLabel: 'จากวันที่',    
                id: 'dateSummaryFrom',
                name: 'dateSummaryFrom',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       	var dateSummaryTo = new Ext.ux.DateTimeField ({
                fieldLabel: 'ถึงวันที่',    
                id: 'dateSummaryTo',
                name: 'dateSummaryTo',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
        
        var reportSummaryForm = new Ext.form.FormPanel({
            id: 'reportSummaryForm',
            baseCls: 'x-plain',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [dateSummaryFrom,dateSummaryTo],
            buttons: [{
                text: 'เรียกดู',
                formBind: true,
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {
                    
                    reportSummaryWindow.hide();
                    /*
                    Ext.MessageBox.show({
                        id: 'dlgSaveData',
                           msg: 'Saving your data, please wait...',
                           progressText: 'Saving...',
                           width:300,
                           wait:true,
                           waitConfig: {interval:200},
                           icon:'ext-mb-download'
                       });
                       Ext.Ajax.request({
                        url: '/{$config ['appName']}/announce/create',
                        method: 'POST',
                        success: function (o) {
                             Ext.MessageBox.hide();
                            Ext.MessageBox.show({                          
                           msg: 'บันทึกรายการเรียบร้อย',        
                           width:300,                    
                           icon:'ext-mb-download'
                       });
                        }, 
                        failure: function(o) {
                        },
                        form: Ext.getCmp('reserveBooknoForm').getForm().getEl()
                    });
                    */
                }
            },{
                text: 'ยกเลิก',
                iconCls: 'rejectIcon',
                handler: function() {
                    reportSummaryWindow.hide();
                }
            }]
        });";
        
        $js .= "var reportSummaryWindow = new Ext.Window({
            id: 'reportSummaryWindow',
            title: 'รายงานสรุปการรับ-ส่งหนังสือ',
            width: 300,
            height: 125,
            minWidth: 300,
            minHeight: 125,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: reportSummaryForm,
            closable: false
        });        
        ";
                               
        return $js;
    }
    
	private function getRegisterReportJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;
        
        $registerTypeStore = $store->getDataStore ( 'registerType' );      
        
        $js = "
        
        {$registerTypeStore}
        
        var dateReportFrom = new Ext.ux.DateTimeField ({
                fieldLabel: 'จากวันที่',    
                id: 'dateReportFrom',
                name: 'dateReportFrom',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       var dateReportTo = new Ext.ux.DateTimeField ({
                fieldLabel: 'ถึงวันที่',    
                id: 'dateReportTo',
                name: 'dateReportTo',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
        
        var reportRegisterForm = new Ext.form.FormPanel({
            id: 'reportRegisterForm',
            baseCls: 'x-plain',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [new Ext.form.LocalComboBox({
                id: 'reportRegisterType',
                name: 'reportRegisterType',
                fieldLabel: 'ประเภททะเบียน',
                hiddenname: 'reportRegisterTypeID',
                store: registerTypeStore,
                displayField: 'name',
                valueField: 'value',
                typeAhead: false,
                value: -1,
                mode: 'local',
                triggerAction: 'all',
                selectOnFocus: true
            }),dateReportFrom,dateReportTo,
			{
                fieldLabel: 'เลขที่ทะเบียน(จาก)',
                id: 'reportRegnoFrom',
                allowBlank: true,
                name: 'reportRegnoFrom',
                width: 50
            },{
                fieldLabel: 'เลขที่ทะเบียน(ถึง)',
                id: 'reportRegnoTo',
                allowBlank: true,
                name: 'reportRegnoTo',
                width: 50
            }],
            buttons: [{
                text: 'เรียกดู',
                formBind: true,
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {
                    reportRegisterWindow.hide();
                    /*
                    Ext.MessageBox.show({
                        id: 'dlgSaveData',
                           msg: 'Saving your data, please wait...',
                           progressText: 'Saving...',
                           width:300,
                           wait:true,
                           waitConfig: {interval:200},
                           icon:'ext-mb-download'
                       });
                       Ext.Ajax.request({
                        url: '/{$config ['appName']}/announce/create',
                        method: 'POST',
                        success: function (o) {
                             Ext.MessageBox.hide();
                            Ext.MessageBox.show({                          
                           msg: 'บันทึกรายการเรียบร้อย',        
                           width:300,                    
                           icon:'ext-mb-download'
                       });
                        }, 
                        failure: function(o) {
                        },
                        form: Ext.getCmp('reserveBooknoForm').getForm().getEl()
                    });
                    */
                }
            },{
                text: 'ยกเลิก',
                iconCls: 'rejectIcon',
                handler: function() {
                    reportRegisterWindow.hide();
                }
            }]
        });";
        
        $js .= "var reportRegisterWindow = new Ext.Window({
            id: 'reportRegisterWindow',
            title: 'รายงานทะเบียนหนังสือ',
            width: 360,
            height: 230,
            minWidth: 360,
            minHeight: 230,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: reportRegisterForm,
            closable: false
        });        
        ";
                               
        return $js;
    }
    
	private function getAnnounceReportJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;
        
        $registerTypeStore = $store->getDataStore ( 'registerType' );      
        
        $js = "
        
        {$registerTypeStore}
        
        var dateAnnounceFrom = new Ext.ux.DateTimeField ({
                fieldLabel: 'จากวันที่',    
                id: 'dateAnnounceFrom',
                name: 'dateAnnounceFrom',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       var dateAnnounceTo = new Ext.ux.DateTimeField ({
                fieldLabel: 'ถึงวันที่',    
                id: 'dateAnnounceTo',
                name: 'dateAnnounceTo',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       var dateReportAnnounceFrom = new Ext.ux.DateTimeField ({
                fieldLabel: 'จากวันที่',    
                id: 'dateReportAnnounceFrom',
                name: 'dateReportAnnounceFrom',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
       var dateReportAnnounceTo = new Ext.ux.DateTimeField ({
                fieldLabel: 'ถึงวันที่',    
                id: 'dateReportAnnounceTo',
                name: 'dateReportAnnounceTo',
                allowBlank: false,  
                emptyText: 'Default',
                width: 100
            });
            
		var resultReportSubTypeTpl = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">ประเภท:{desctype}</td></tr>',
                '</table>',               
            '</div></tpl>'
        );
        
        var reportAnnounceForm = new Ext.form.FormPanel({
            id: 'reportAnnounceForm',
            baseCls: 'x-plain',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [new Ext.form.LocalComboBox({
                id: 'reportAnnouncType',
                name: 'reportAnnouncType',
                fieldLabel: 'ประเภทหลัก',
                hiddenname: 'reportAnnouncType',
                store: announceTypeStore,
                displayField: 'name',
                valueField: 'value',
                typeAhead: false,
                value: -1,
                mode: 'local',
                triggerAction: 'all',
                selectOnFocus: true
            }),new Ext.form.ComboBox({
                store: autocompleteSubTypeStore,
                name: 'reportAnnounSubType',                            
                fieldLabel: 'ประเภทรอง',
                style: autoFieldStyle,
                allowBlank: false,  
                emptyText: 'Default',
                minChars: 2,  
                displayField:'name',
                typeAhead: false,
                loadingText: '{$lang['common']['searcing']}',
                width: 280,
                pageSize:10,
                hideTrigger:true,
                tpl: resultReportSubTypeTpl,
                itemSelector: 'div.search-item'
            }),dateReportAnnounceFrom,dateReportAnnounceTo],
            buttons: [{
                text: 'เรียกดู',
                formBind: true,
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {
                    reportAnnounceWindow.hide();
                    /*
                    Ext.MessageBox.show({
                        id: 'dlgSaveData',
                           msg: 'Saving your data, please wait...',
                           progressText: 'Saving...',
                           width:300,
                           wait:true,
                           waitConfig: {interval:200},
                           icon:'ext-mb-download'
                       });
                       Ext.Ajax.request({
                        url: '/{$config ['appName']}/announce/create',
                        method: 'POST',
                        success: function (o) {
                             Ext.MessageBox.hide();
                            Ext.MessageBox.show({                          
                           msg: 'บันทึกรายการเรียบร้อย',        
                           width:300,                    
                           icon:'ext-mb-download'
                       });
                        }, 
                        failure: function(o) {
                        },
                        form: Ext.getCmp('reserveBooknoForm').getForm().getEl()
                    });
                    */
                }
            },{
                text: 'ยกเลิก',
                iconCls: 'rejectIcon',
                handler: function() {
                    reportAnnounceWindow.hide();
                }
            }]
        });";
        
        $js .= "var reportAnnounceWindow = new Ext.Window({
            id: 'reportAnnounceWindow',
            title: 'รายงานคำสั่ง/ประกาศ/ระเบียบ/อื่นๆ',
            width: 470,
            height: 180,
            minWidth: 470,
            minHeight: 180,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: reportAnnounceForm,
            closable: false
        });     

        Ext.getCmp('reportAnnouncType').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            Cookies.set('edt',dataRecord.data.value);
        },this);
        ";
                               
        return $js;
    }
}