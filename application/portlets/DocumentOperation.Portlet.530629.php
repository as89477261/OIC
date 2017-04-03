<?php
/**
 * Portlet : ���Թ����Ѻ���͡���
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class DocumentOperationPortlet {
    function getUI() {
        global $store;
        global $config;
        global $policy;

        checkSessionPortlet();

        $jsDocumentOperationUI = "";

        if ($policy->canReceiveInternal ()) {
            $jsDocumentOperationUI .= "Ext.get('acceptInternalSpan').on('click',function() {
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
                        //alert(r.redirectLogin);
                        //return checkSession2(r);
                        if(r.redirectLogin == 1) {
                            sessionExpired();
                        } else   {
                            receiveInternalWindow.show();
                        }
                    }
                });
            },this);";
            $receiveInternalSpan = "<span class=\"portletCommandSpan\" id=\"acceptInternalSpan\"><img src=\"/{$config ['appName']}/images/th/ReceiveInternal.png\" /></span>";
        } else {
            $receiveInternalSpan = "<span><img src=\"/{$config ['appName']}/images/th/ReceiveInternalDisabled.png\" /></span>";
        }

        if ($policy->canSendInternal ()) {
            $jsDocumentOperationUI .= "
            Ext.get('sendInternalSpan').on('click',function() {
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
                        //alert(r.redirectLogin);
                        //return checkSession2(r);
                        if(r.redirectLogin == 1) {
                            sessionExpired();
                        } else   {
                            sendInternalForm.getForm().reset();
                            sendInternalWindow.show();
                            Ext.getCmp('btnSendInternalDoc').show();
                            Ext.get('sendIntSendTo').on('focus',function() {
                                sendInternalListWindow.show();
                                Cookies.set('rc','sendIntSendTo');
                                Cookies.set('rcH','sendIntSendToHidden');
                                Ext.getCmp('SendToSelector').focus('',10);
                            },this);
                        }
                    }
                });
            },this);

            ";
            $sendInternalSpan = "<span class=\"portletCommandSpan\" id=\"sendInternalSpan\"><img src=\"/{$config ['appName']}/images/th/sendInternal.png\" /></span>";
        } else {
            $sendInternalSpan = "<span><img src=\"/{$config ['appName']}/images/th/sendInternalDisabled.png\" /></span>";
        }

        if ($policy->canReceiveExternal ()) {
            $jsDocumentOperationUI .= "Ext.get('acceptExternalSpan').on('click',function() {
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
                        //alert(r.redirectLogin);
                        //return checkSession2(r);
                        if(r.redirectLogin == 1) {
                            sessionExpired();
                        } else   {
                            receiveExternalForm.getForm().reset();
                            receiveExternalWindow.show();     ;

                            Ext.get('recvExtForwardTo').on('focus',function() {
                                sendInternalListWindow.show();
                                Cookies.set('rc','recvExtForwardTo');
                                Cookies.set('rcH','recvExtForwardToHidden');
                                Ext.getCmp('SendToSelector').focus('',10);

                            },this);
                        }
                    }
                });

            },this);";
            $receiveExternalSpan = "<span class=\"portletCommandSpan\" id=\"acceptExternalSpan\"><img src=\"/{$config ['appName']}/images/th/ReceiveExternal.png\" /></span>";
        } else {
            $receiveExternalSpan = "<span><img src=\"/{$config ['appName']}/images/th/ReceiveExternalDisabled.png\" /></span>";
        }

        if ($policy->canSendExternal ()) {
            $jsDocumentOperationUI .= "Ext.get('sendExternalSpan').on('click',function() {
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
                        //alert(r.redirectLogin);
                        //return checkSession2(r);
                        if(r.redirectLogin == 1) {
                            sessionExpired();
                        } else   {
                            sendExternalForm.getForm().reset();
                            sendExternalWindow.show();

                             Ext.get('sendExtSendTo').on('focus',function() {
                                sendExternalListWindow.show();
                                Cookies.set('rc','sendExtSendTo');
                                Cookies.set('rcH','sendExtSendToHidden');
                                Ext.getCmp('SendExternalToSelector').focus('',10);
                            },this);
                        }
                    }
                });
            },this);";
            $sendExternalSpan = "<span class=\"portletCommandSpan\" id=\"sendExternalSpan\"><img src=\"/{$config ['appName']}/images/th/sendExternal.png\" /></span>";
        } else {
            $sendExternalSpan = "<span><img src=\"/{$config ['appName']}/images/th/sendExternalDisabled.png\" /></span>";
        }

        if ($policy->canReceiveExternalGlobal ()) {
            $jsDocumentOperationUI .= "Ext.get('receiveExternalGlobalSpan').on('click',function() {
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
                        //alert(r.redirectLogin);
                        //return checkSession2(r);
                        if(r.redirectLogin == 1) {
                            sessionExpired();
                        } else   {
                            receiveExternalGlobalForm.getForm().reset();
                            receiveExternalGlobalWindow.show();

                            Ext.get('recvExtGlobalForwardTo').on('focus',function() {
                                sendInternalListWindow.show();
                                Cookies.set('rc','recvExtGlobalForwardTo');
                                Cookies.set('rcH','recvExtGlobalForwardToHidden');
                                Ext.getCmp('SendToSelector').focus('',10);
                            },this);
                        }
                    }
                });
            },this);";
            $receiveExternalGlobalSPAN = "<span class=\"portletCommandSpan\" id=\"receiveExternalGlobalSpan\"><img src=\"/{$config ['appName']}/images/th/receiveExternalGlobal.png\" /></span>";
        } else {
            $receiveExternalGlobalSPAN = "<span><img src=\"/{$config ['appName']}/images/th/receiveExternalGlobalDisabled.png\" /></span>";
        }
        if ($policy->canSendExternalGlobal ()) {
            $jsDocumentOperationUI .= "Ext.get('sendExternalGlobalSpan').on('click',function() {
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
                        //alert(r.redirectLogin);
                        //return checkSession2(r);
                        if(r.redirectLogin == 1) {
                            sessionExpired();
                        } else   {
                            sendExternalGlobalForm.getForm().reset();
                            sendExternalGlobalWindow.show();

                            Ext.get('sendExtGlobalSendTo').on('focus',function() {
                                sendExternalListWindow.show();
                                Cookies.set('rc','sendExtGlobalSendTo');
                                Cookies.set('rcH','sendExtGlobalSendToHidden');
                                Ext.getCmp('SendExternalToSelector').focus('',10);
                            },this);
                        }
                    }
                });
            },this);";
            $sendExternalGlobalSPAN = "<span class=\"portletCommandSpan\" id=\"sendExternalGlobalSpan\"><img src=\"/{$config ['appName']}/images/th/sendExternalGlobal.png\" /></span>";
        } else {
            $sendExternalGlobalSPAN = "<span ><img src=\"/{$config ['appName']}/images/th/sendExternalGlobalDisabled.png\" /></span>";
        }

        if($policy->isSarabanMaster()) {
            $jsDocumentOperationUI .= "Ext.get('extraBookSpan').on('click',function() {
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
                        //alert(r.redirectLogin);
                        //return checkSession2(r);
                        if(r.redirectLogin == 1) {
                            sessionExpired();
                        } else   {
                            createExtraBookForm.getForm().reset();
                            createExtraBookWindow.show();
                        }
                    }
                });
            },this);";
            $extraBookSPAN = "<span class=\"portletCommandSpan\" id=\"extraBookSpan\"><img src=\"/{$config ['appName']}/images/th/CreateExtra.png\" /></span>";
        } else {
            $extraBookSPAN = "<span><img src=\"/{$config ['appName']}/images/th/CreateExtraDisabled.png\" /></span>";
        }



        if($policy->canSendExternalGlobal()) {
            //$reserveBookSpan = "<img src=\"/{$config ['appName']}/images/th/ReserveBookNoExt.png\" />";
             $jsDocumentOperationUI .= "Ext.get('sendInternalGlobalSPAN').on('click',function() {
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
                        //alert(r.redirectLogin);
                        //return checkSession2(r);
                        if(r.redirectLogin == 1) {
                            sessionExpired();
                        } else   {
                             sendInternalGlobalForm.getForm().reset();
                            sendInternalGlobalWindow.show();
                            Ext.get('sendIntGlobalSendTo').on('focus',function() {
                                sendInternalListWindow.show();
                                Cookies.set('rc','sendIntGlobalSendTo');
                                Cookies.set('rcH','sendIntGlobalSendToHidden');
                                Ext.getCmp('SendToSelector').focus('',10);
                            },this);
                        }
                    }
                });
            },this);";
            $sendInternalGlobalSPAN = "<span class=\"portletCommandSpan\" id=\"sendInternalGlobalSPAN\"><img src=\"/{$config ['appName']}/images/th/sendInternalGlobal.png\" /></span>";
        } else {
            $sendInternalGlobalSPAN = "<span><img src=\"/{$config ['appName']}/images/th/sendInternalGlobalDisabled.png\" /></span>";
        }



        $documentTypeStore = $store->getDataStore ( 'documentTypeList', 'doctypeListStore' );
        $receiveTypeStore = $store->getDataStore ( 'receiveTypeList', 'receiveTypeListStore' );
        $purposeSarabanStore = $store->getDataStore ( 'purposeSaraban', 'purposeSarabanStore' );
        $regbookInternalStore = $store->getDataStore ( 'registerBookReceiveInternal', 'registerBookReceiveInternalListStore' );
        $regbookExternalStore = $store->getDataStore ( 'registerBookReceiveExternal', 'registerBookReceiveExternalListStore' );

        $formSarabanStore = $store->getDataStore ( 'formListSaraban', 'formListSarabanStore' );

        //$secretLevelStore = $store->getDataStore ( 'secretLevel' ,'secretLevelReceiveStore');
        //$secretLevelStore = $store->getDataStore ( 'secretLevel' ,'secretLevelReceiveStore');
        $js = "<script type-\"text/javascript\">

        {$documentTypeStore}
        {$receiveTypeStore}
        {$purposeSarabanStore}
        {$regbookInternalStore}
        {$regbookExternalStore}
        {$formSarabanStore}

        doctypeListStore.load();
        receiveTypeListStore.load();
        purposeSarabanStore.load();
        registerBookReceiveInternalListStore.load()
        registerBookReceiveExternalListStore.load();
        formListSarabanStore.load();


        Cookies.set('txs',1);
        Cookies.set('txr',1);


        {$this->getExtraBookJS()}
        {$this->getReserveBookJS()}
        </script>";

        echo $js;
        echo $this->receiveInternalJS ();
        echo $this->receiveExternalJS ();
        echo $this->sendInternalJS ();
        echo $this->sendExternalJS ();
        echo $this->receiveExternalGlobalJS ();
        echo $this->sendExternalGlobalJS ();
        echo $this->sendInternalGlobalJS();
        echo $this->sentInternalListWindowJS ();
        echo $this->sentExternalListWindowJS ();


        $html = "<table width=\"100%\">
            <tr>
                <td>
                {$receiveInternalSpan}
                </td>
                <td>
                {$sendInternalSpan}

                </td>
            </tr>
            <tr>
                <td>
                {$receiveExternalSpan}
                </td>
                <td>
                {$sendExternalSpan}
                </td>
            </tr>
            <!--<tr>
                <td colspan=\"2\">
                    <hr/>
                </td>
            </tr>-->
            <tr>
                <td>
                    {$receiveExternalGlobalSPAN}
                </td>
                <td>
                    {$sendExternalGlobalSPAN}
                </td>
            </tr>
            <tr>
                <td>
                    {$extraBookSPAN}
                </td>
                <td>
                    {$sendInternalGlobalSPAN}
                </td>
            </tr>
        </table>";
        echo $html;

        /*
        //�ʴ�����Է���
        echo "";
        echo "<br/>";
        echo "";
        echo "</br/>";
        echo "<span class=\"portletCommandSpan\">�ͧ�Ţ����¹��(����)</span><br/>";
        echo "<span class=\"portletCommandSpan\">�ͧ�Ţ����¹��(��¹͡)</span><br/>";
        //����Ѻ��·���¹�Ѻ��ҹ��
        //echo "<span class=\"portletCommandSpan\">ŧ�Ѻ�͡����Ѻ</span><br/>";
        //echo "<span class=\"portletCommandSpan\">���͡����Ѻ</span><br/>";
        */

        $jsFinal = "<script>
        {$jsDocumentOperationUI}
        </script>";
        echo $jsFinal;
    }

    private function receiveInternalJS() {
        global $config;
        global $lang;

        if($config ['disableOverrideRecvDateTime']) {
            $recvDateFlag = "readOnly: true,";
            $recvTimeFlag = "readOnly: true,
            hideTrigger: true,
            editable: true,
            typeAhead: false,";
        } else {
            $recvDateFlag = "readOnly: false,";
            $recvTimeFlag = "readOnly: false,";
        }

        $js = "<script type=\"text/javascript\">
        var receiveInternalForm = new Ext.form.FormPanel({
            id: 'receiveInternalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,

            items: [
                {
                    xtype:'fieldset',
                    title: '��������´�͡���',
                    autoHeight:true,
                    collapsible: false,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        columnWidth: .6,
                        layout: 'form',
                        baseCls: 'x-plain',
                        items:[{
                            xtype:'textfield',
                               fieldLabel: '���������ŧ�Ѻ',
                            allowBlank: false,
                            name: 'recvIntType',
                            value: 'ŧ�Ѻ����',
                            readOnly: true
                        },{
                            xtype:'textfield',
                               fieldLabel: '�Ţ����͡���',
                               emptyText : '{$lang['df']['noBookNo']}',
                               //allowBlank: false,
                               //labelStyle: 'font-weight:bold;color: Red;',
                            name: 'recvIntDocNo'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: 'ŧ�ѹ���',
                            name: 'recvIntDocDate',
                            readOnly: true,
                            emptyText: 'Default',
                            width: 100
                        }),{
                            xtype:'textfield',
                               fieldLabel: '��ҧ�֧',
                            name: 'recvIntRefer',
                            width: 280

                        },{
                            xtype:'textfield',
                            fieldLabel: '��觷�����Ҵ���',
                            name: 'recvIntAttachment',
                            width: 280
                        },new Ext.form.ComboBox({
                            store: autocompleteReceiverTextStore,
                            fieldLabel: '�ҡ',
                            displayField:'name',
                            style: autoFieldStyle,
                            typeAhead: false,
                            emptyText: 'Default',
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            hideTrigger: true,
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'recvIntSendFrom',
                            tpl: resultTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-item'
                        }),new Ext.form.ComboBox({
                            store: autocompleteReceiverTextStore,
                            fieldLabel: '���¹',
                            displayField:'name',
                            typeAhead: false,
                            style: autoFieldStyle,
                            emptyText: 'Default',
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            hideTrigger: true,
                            name: 'recvIntSendTo',
                            tpl: resultTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-item'
                        })/*{
                            xtype:'textfield',
                               fieldLabel: '�֧',
                               emptyText: 'Default',
                            name: 'recvIntSendTo',
                            width: 280
                        }*/
                        ,{
                            xtype:'textarea',
                               fieldLabel: '����ͧ',
                            allowBlank: false,
                            name: 'recvIntTitle',
                            labelStyle: 'font-weight:bold;color: Red;',
                            width: 280,
                            height: 65
                        },{
                            xtype:'textarea',
                               fieldLabel: '��������´',
                            name: 'recvIntDesc',
                            width: 280,
                            height: 65
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth: .4,
                        layout: 'form',
                        items:[{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����¹�Ѻ',
                            emptyText: 'Default',
                            name: 'recvIntRecvNo'
                        },new Ext.form.ComboBox({
                            name: 'recvIntRegBookID',
                            fieldLabel: '����¹�Ѻ����',
                            xtype:'combo',
                            store: registerBookReceiveInternalListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvIntRegBookID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ���ŧ�Ѻ',
                            emptyText: 'Default',
                            name: 'recvIntRecvDate',
                            {$recvDateFlag}
                            width: 100
                        }),new Ext.form.TimeField({
                            fieldLabel: '���ҷ��ŧ�Ѻ',
                            emptyText: 'Default',
                            name: 'recvIntRecvTime',
                            {$recvTimeFlag}
                            format: 'H:i',
                            width: 100
                        }),    new Ext.form.LocalComboBox({
                            store: speedLevelStore,
                            displayField: 'name',
                            name: 'recvIntSpeedLevel',
                            valueField: 'value',
                            hiddenName: 'recvIntSpeedLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤�������',
                            value: 0,
                            mode: 'local',
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),    new Ext.form.LocalComboBox({
                            store: secretLevelStore,
                            displayField: 'name',
                            name: 'recvIntSecretLevel',
                            valueField: 'value',
                            hiddenName: 'recvIntSecretLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤����Ѻ',
                            value: 0,
                            mode: 'local',
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'recvIntDocType',
                            fieldLabel: '�������͡���',
                            store: doctypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvIntDocType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'recvIntDeliverType',
                            fieldLabel: '�Ըա�ù����͡���',
                            store: receiveTypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvIntDeliverType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'recvIntFormID',
                            allowBlank: false,
                            fieldLabel: 'Ẻ�����',
                            store: formListSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvIntFormID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        })]
                    }
                    ]

                },{
                    xtype:'fieldset',
                    id: 'recvIntFieldGroup',
                    title: '�ѹ�֡���ʹ��Թ���',
                    autoHeight:true,
                    collapsible: true,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        baseCls: 'x-plain',
                        columnWidth:.6,
                        layout: 'form',
                        items:[new Ext.form.ComboBox({
                            name: 'recvIntPurpose',
                            fieldLabel: '�ѵ�ػ��ʧ��',
                            store: purposeSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvIntPurpose',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 280
                        }),{
                            xtype:'textfield',
                            fieldLabel: '�����¹/���ʹ�',
                            name: 'recvIntAttend',
                            width: 280
                        },{
                            xtype:'textfield',
                            fieldLabel: '������͡���',
                            name: 'recvIntLocation',
                            width: 280
                        },{
                            xtype: 'textarea',
                            fieldLabel: '�����˵�',
                            name: 'recvIntRemark',
                            width: 280,
                            height: 50
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth:.4,
                        layout: 'form',
                        items:[new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��������͡���',
                            emptyText: 'Default',
                            name: 'recvIntDocExpireDate',
                            width: 100
                        }),{
                            xtype: 'checkbox',
                            fieldLabel: '�Դ�����',
                            name: 'recvIntFollowUp'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��˹�����',
                            name: 'recvIntDocDeadLine',
                            width: 100
                        })
                        ]
                    }
                    ]
                }],
                buttons: [{
                    //id: 'btnAccept',
                    text: '{$lang['workitem']['accept']}',
                    formBind:true,
                    handler: function() {
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
                                        id: 'dlgReceiveInternal',
                                        msg: '���ѧŧ�Ѻ��س����ѡ����...',
                                        progressText: '�ѹ�֡������...',
                                        width:300,
                                        wait:true,
                                        waitConfig: {interval:200},
                                        icon:'ext-mb-download'
                                    });

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/receive-internal',
                                        method: 'POST',
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);

                                             checkSession(r);


                                            if(r.success ==1) {
                                                Ext.MessageBox.show({
                                                    title: '���ŧ�Ѻ����',
                                                    msg: 'ŧ�Ѻ���º��������<br/>�Ѻ�����Ţ���'+r.regNo +'<br/>' + 'ŧ�Ѻ�ѹ��� '+r.recvDate+',����'+r.recvTime,
                                                    buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
													fn: function(id,str) {
	                                                	if(id=='yes') {
	                                                    	receiveInternalWindow.hide();
	                                                    	Cookies.set('docRefID',r.transID);
															//saveECMData('_pw','ri');
															viewDocumentCrossModule('viewDOC_'+r.transID,r.title,'ReceiveInternal',r.docID,r.transID);
	    												}
	    											},
                                                    icon: Ext.MessageBox.INFO
                                                });
                                                Ext.getCmp('receiveInternalForm').getForm().reset();
                                              } else {
                                                if(r.duplicate == 1) {
                                                    Ext.MessageBox.show({
                                                        title: '���ŧ�Ѻ����',
                                                        msg: 'ŧ�Ѻ�����<br/>˹ѧ������Ѻ���������',
                                                        buttons: Ext.MessageBox.OK,
                                                        icon: Ext.MessageBox.INFO
                                                     });
                                                   //Ext.getCmp('receiveExternalGlobalForm').getForm().reset();
                                                   //clearInternalSendToSelections()
                                                }
                                              }
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();

                                            Ext.MessageBox.show({
                                                title: '���ŧ�Ѻ����',
                                                msg: '�������öŧ�Ѻ��',
                                                buttons: Ext.MessageBox.OK,
                                                //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                        form: Ext.getCmp('receiveInternalForm').getForm().getEl()
                                    });
                                }
                            }
                        });

                    }
                },{
                    //id: 'btnAccept',
                    text: '{$lang['workitem']['acceptCirc']}',
                    formBind:true,
                    hidden: true,
                    handler: function() {
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
                                        id: 'dlgReceiveInternal',
                                        msg: '���ѧŧ�Ѻ��س����ѡ����...',
                                        progressText: '�ѹ�֡������...',
                                        width:300,
                                        wait:true,
                                        waitConfig: {interval:200},
                                        icon:'ext-mb-download'
                                    });

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/receive-internal',
                                        method: 'POST',
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              Ext.MessageBox.show({
                                                title: '���ŧ�Ѻ����',
                                                msg: 'ŧ�Ѻ���º��������<br/>�Ѻ�����Ţ���'+r.regNo +'<br/>' + 'ŧ�Ѻ�ѹ��� '+r.recvDate+',����'+r.recvTime,
                                                buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
													fn: function(id,str) {
	                                                	if(id=='yes') {
	                                                    	receiveInternalWindow.hide();
															//saveECMData('_pw','ri');
	                                                    	Cookies.set('docRefID',r.transID);
															viewDocumentCrossModule('viewDOC_'+r.transID,r.title,'ReceiveInternal',r.docID,r.transID);
	    												}
	    											},
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('receiveInternalForm').getForm().reset();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();

                                            Ext.MessageBox.show({
                                                title: '���ŧ�Ѻ����',
                                                msg: '�������öŧ�Ѻ��',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                        form: Ext.getCmp('receiveInternalForm').getForm().getEl()
                                    });
                                }
                            }
                        });

                    }
                },{
                    //id: 'btnCloseAcceptWindow',
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        Ext.getCmp('receiveInternalWindow').hide();
                    }
                }]
        });



        function receiveInternalSuccess(response ,option) {
            Ext.MessageBox.hide();

            //formStore.reload();
            //alert(response.Text);
            Ext.MessageBox.show({
                title: '���ŧ�Ѻ����',
                msg: 'ŧ�Ѻ���º��������',
                buttons: Ext.MessageBox.OK,
                //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                icon: Ext.MessageBox.INFO
            });
        }

        function receiveInternalFailed() {
            Ext.MessageBox.hide();

            Ext.MessageBox.show({
                title: '���ŧ�Ѻ����',
                msg: '�������öŧ�Ѻ��',
                buttons: Ext.MessageBox.OK,
                //animEl: Ext.getCmp('btnSaveAccount').getEl(),
                icon: Ext.MessageBox.ERROR
            });
        }

        var receiveInternalWindow = new Ext.Window({
            id: 'receiveInternalWindow',
            title: '{$lang['workitem']['receiveDocumentFromInternal']}',
            width: 700,
            height: 615,
            minWidth: 700,
            minHeight: 615,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: true,
            items: receiveInternalForm,
            closable: false,
            keys: {
                key: Ext.EventObject.ESC,
                fn: function (){
                    receiveInternalForm.getForm().reset();
                    Ext.getCmp('receiveInternalWindow').hide();
                },
                scope: this
            }

        });

        Ext.getCmp('recvIntFieldGroup').on('beforeexpand',function(p,a){
            receiveInternalWindow.setHeight(615);
            receiveInternalWindow.center();
        });

        Ext.getCmp('recvIntFieldGroup').on('beforecollapse',function(p,a){
            receiveInternalWindow.setHeight(460);
            receiveInternalWindow.center();
        });
           </script>";

        return $js;
    }

    private function sendInternalJS() {
        global $config;
        global $lang;
        global $sessionMgr;

        if($sessionMgr->isSarabanMaster()) {
            $readOnlyDocno = "readOnly: false,";
        } else {
            $readOnlyDocno = "readOnly: true,";
        }
        if($config ['disableOverrideDocNo']) {
            $readOnlyDocno = "readOnly: true,";
        } else {
			$readOnlyDocno = "readOnly: false,";
		}

        if($config ['disableOverrideSendDateTime']) {
            $sendDateFlag = "readOnly: true,";
            $sendTimeFlag = "readOnly: true,
            hideTrigger: true,
            editable: true,
            typeAhead: false,";
        } else {
            $sendDateFlag = "readOnly: false,";
            $sendTimeFlag = "readOnly: false,";
        }

        include_once 'Organize.Entity.php';
        $orgID = $sessionMgr->getCurrentOrgID ();
        $org = new OrganizeEntity ( );
        if (! $org->Load ( "f_org_id = '{$orgID}'" )) {
            $docCode = '';
        } else {
            $docCode = $org->f_int_code;
        }

        if($org->f_allow_int_doc_no != 1) {
            $disableGenInt = 'true';
            $checkedGenInt = 'false';
        } else {
            $disableGenInt = 'false';
            $checkedGenInt = 'true';
        }

        $js = "<script type=\"text/javascript\">
        var sendInternalForm = new Ext.form.FormPanel({
            id: 'sendInternalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,
            items: [{
                    xtype:'fieldset',
                    title: '��������´�͡���',
                    autoHeight:true,
                    collapsible: false,
                    collapsed: false,
                    layout: 'column',
                    items: [{
                        columnWidth: .6,
                        layout: 'form',
                        baseCls: 'x-plain',
                        items:[{
                            xtype:'textfield',
                               fieldLabel: '�����������',
                            allowBlank: false,
                            name: 'sendIntType',
                            value: '������',
                            readOnly: true
                        },{
                            xtype:'checkbox',
                               fieldLabel: '�͡�Ţ',
                            allowBlank: true,
                            checked: {$checkedGenInt},
                            readOnly: {$disableGenInt},
                            id: 'genIntDocNo',
                            name: 'genIntDocNo'
                        },{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����͡���',
                            allowBlank: true,
                            value: '{$docCode}/',
                            {$readOnlyDocno}
                            name: 'sendIntDocNo'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: 'ŧ�ѹ���',
							id: 'sendIntDocDate',
                            name: 'sendIntDocDate',
                            readOnly: true,
                            emptyText: 'Default',
                            width: 100
                        }),{
                            xtype:'textfield',
                               fieldLabel: '��ҧ�֧',
                            name: 'sendIntRefer',
                            width: 280
                        },{
                            xtype:'textfield',
                               fieldLabel: '��觷�����Ҵ���',
                            name: 'sendIntAttachment',
                            width: 280
                        },new Ext.form.ComboBox({
                            store: autocompleteSenderTextStore,
							id: 'sendIntSendFrom',
                            name: 'sendIntSendFrom',
                            fieldLabel: '�ҡ',
                            style: autoFieldStyle,
                            emptyText: 'Default',
                            minChars: 2,
                            displayField:'name',
                            typeAhead: false,
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            pageSize:10,
                            hideTrigger:true,
                            tpl: resultTpl,
                            itemSelector: 'div.search-item'
                        }),{
                            id: 'sendIntAttend',
                            xtype:'textarea',
                            fieldLabel: '���¹',
                            //style: popupFieldStyle,
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'sendIntAttend',
                            width: 280,
                            height: 30
                        },{
                            id: 'sendIntSendTo',
                            xtype:'textfield',
                            fieldLabel: '�觶֧',
                            style: popupFieldStyle,
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'sendIntSendTo',
                            width: 280
                        },{
                            id: 'sendIntSendToHidden',
                            xtype:'hidden',
                            name: 'sendIntSendToHidden'
                        },{
                            xtype:'textarea',
                               fieldLabel: '����ͧ',
                               labelStyle: 'font-weight:bold;color: Red;',
                            allowBlank: false,
                            name: 'sendIntTitle',
                            width: 280,
                            height: 50
                        },{
                            xtype:'textarea',
                               fieldLabel: '��������´',
                            name: 'sendIntDesc',
                            width: 280,
                            height: 50
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth: .4,
                        layout: 'form',
                        items:[{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����¹��',
                            emptyText: 'Default',
                            name: 'sendIntSendNo'
                        },new Ext.form.ComboBox({
                            name: 'sendIntRegBookNo',
                            allowBlank: false,
                            fieldLabel: '����¹������',
                            store: registerBookReceiveInternalListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntRegBookID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ������͡',
                            name: 'sendIntSendDate',
                            {$sendDateFlag}
                            emptyText: 'Default',
                            width: 100
                        }),new Ext.form.TimeField({
                            fieldLabel: '���ҷ�����͡',
                            name: 'sendIntSendTime',
                            emptyText: 'Default',
                            {$sendTimeFlag}
                            format: 'H:i',
                            width: 100
                        }),    new Ext.form.LocalComboBox({
                            store: speedLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'sendIntSpeedLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤�������',
                            mode: 'local',
                            value: 0,
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),    new Ext.form.LocalComboBox({
                            store: secretLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'sendIntSecretLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤����Ѻ',
                            mode: 'local',
                            value: 0,
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendIntDocType',
                            allowBlank: false,
                            fieldLabel: '�������͡���',
                            store: doctypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntDocType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendIntDeliverType',
                            allowBlank: false,
                            fieldLabel: '�Ըա�ù����͡���',
                            store: receiveTypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntDeliverType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendIntFormID',
                            allowBlank: false,
                            fieldLabel: 'Ẻ�����',
                            store: formListSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntFormID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),{
                            xtype:'checkbox',
                            fieldLabel: '���Ţ��¹͡',
                            allowBlank: true,
                            checked: false,
                            id: 'requestExtDocNo',
                            name: 'requestExtDocNo'
                        }/*,{
                            xtype:'checkbox',
                            fieldLabel: '�ͧ�Ţ',
                            allowBlank: true,
                            checked: false,
                            disabled: true,
                            id: 'reserveExt',
                            name: 'reserveExt'
                        }*/,{
                            xtype:'checkbox',
                            fieldLabel: '�ͧ�Ţ',
                            allowBlank: true,
                            checked: false,
                            disabled: true,
                            id: 'reserveExtGlobal',
                            name: 'reserveExtGlobal'
                        },{
                            xtype:'checkbox',
                               fieldLabel: '����º/�����/����',
                            allowBlank: true,
                            checked: false,
                            name: 'requestOther'
                        },{
                            xtype:'checkbox',
                               fieldLabel: '���͡����º',
                               hideLabel: true,
                               hidden: true,
                            allowBlank: true,
                            checked: false,
                            name: 'requestOrder'
                        },{
                            xtype:'checkbox',
                               fieldLabel: '���͡�����',
                               hideLabel: true,
                               hidden: true,
                            allowBlank: true,
                            checked: false,
                            name: 'requestCommand'
                        },{
                            xtype:'checkbox',
                               fieldLabel: '���͡��С��',
                               hideLabel: true,
                               hidden: true,
                            allowBlank: true,
                            checked: false,
                            name: 'requestAnnounce'
                        }]
                    }]
                },{
                    xtype:'fieldset',
                    id: 'sendIntFieldGroup',
                    title: '�ѹ�֡���ʹ��Թ���',
                    autoHeight:true,
                    collapsible: true,
                    collapsed: false,
                    layout: 'column',
                    items: [{
                        baseCls: 'x-plain',
                        columnWidth:.6,
                        layout: 'form',
                        items:[new Ext.form.ComboBox({
                            name: 'sendIntPurpose',
                            fieldLabel: '�ѵ�ػ��ʧ��',
                            store: purposeSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntPurpose',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            listWidth: 260,
                            width: 260
                        }),new Ext.form.ComboBox({
                            store: autoCompleteNameWithRole,
                            fieldLabel: 'ŧ�����',
                            displayField:'name',
                            style: autoFieldStyle,
                            typeAhead: false,
                            emptyText: 'Default',
                            loadingText: '{$lang['common']['searcing']}',
                            listWidth: 350,
                            width: 280,
                            hideTrigger: true,
                            allowBlank: true,
                            id: 'sendIntSignBy',
                            name: 'sendIntSignBy',
                            valueField: 'id',
							hiddenName: 'sendIntSignByID',
                            tpl: nameRoleLookupTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-name-role'
                        })/*{
                            xtype:'textfield',
                            fieldLabel: 'ŧ�����',
                            name: 'sendIntSignBy',
                            width: 280
                        }*/,{
                            xtype:'textfield',
                            fieldLabel: '������͡���',
                            name: 'sendIntLocation',
                            width: 280
                        },{
                            xtype: 'textarea',
                            fieldLabel: '�����˵�',
                            name: 'sendIntRemark',
                            width: 280,
                            height: 50
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth:.4,
                        layout: 'form',
                        items:[new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��������͡���',
                            name: 'sendIntDocExpireDate',
                            emptyText: 'Default',
                            width: 100
                        }),{
                            xtype: 'checkbox',
                            fieldLabel: '�Դ�����',
                            name: 'sendIntFollowUp'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��˹�����',
                            name: 'sendIntDocDeadline',
                            width: 100
                        })
                        ]
                    }
                    ]
                }],
                buttons: [{
                    id: 'btnSendInternalDoc',
                    formBind: true,
                    text: '{$lang['workitem']['send']}',
                    handler: function() {
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
                                    //receiveExternalForm.getForm().reset();
                                    Ext.MessageBox.confirm('Confirm', '���͡���� ?', function(btn) {
                                	if(btn == 'yes') {
                                    Ext.MessageBox.show({
                                          msg: '���ѧ���͡��س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });

                                    tempSendStore.removeAll();
                                    tempCCStore.removeAll();

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/send-internal?isCirc=0',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']},
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              checkSession(r);
                                              var regNo = '';
                                              var sendDate = '';
                                              var reserveMsg = '';
                                                  for(i=0; i < r.length ; i++) {
                                                      if(regNo == '') {
                                                          regNo = r[i].regNo;
                                                    } else {
                                                        regNo = regNo +','+r[i].regNo;
                                                    }
                                                }
                                                if(r.reserve == 1) {
                                                	reserveMsg = '<br/>�ͧ�Ţ��� '+r.bookNoR;
    											}
                                                //alert(regNo);
                                                docNo = r.docno;
                                                regNo = r.regno;
                                                sendDate = r[0].recvDate;
                                                sendTime = r[0].recvTime;
                                                Ext.MessageBox.show({
                                                    title: '���������',
                                                    msg: '�����º��������<br/>˹ѧ����Ţ��� : '+docNo+'<br/>' + '���ѹ��� :  '+sendDate+',���� : '+sendTime+reserveMsg,
                                                    buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
                                                    fn: function(id,str) {
                                                    	if(id=='yes') {
                                                    		sendInternalWindow.hide();
															//saveECMData('_pw','si');
                                                    		Cookies.set('docRefID',r[0].transID);
															viewDocumentCrossModule('viewDOC_'+r[0].transID,r.title,'SendInternal',r.docID,r[0].transID);
    													}
    												},
                                                    icon: Ext.MessageBox.INFO
                                                });
                                            Ext.getCmp('sendInternalForm').getForm().reset();
											clearInternalSendToSelections();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '���������',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                           form: Ext.getCmp('sendInternalForm').getForm().getEl()
                                       });
								    }
								    });
                                }
                            }
                        });

                    }
                },{
                    //id: 'btnAccept',
                    formBind: true,
                    text: '{$lang['workitem']['sendCirc']}',
                    handler: function() {
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
                                    //receiveExternalForm.getForm().reset();
                                    Ext.MessageBox.confirm('Confirm', '�����¹���� ?', function(btn) {
                                	if(btn == 'yes') {
                                    Ext.MessageBox.show({
                                          msg: '���ѧ���͡��س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });

                                    tempSendStore.removeAll();
                                    tempCCStore.removeAll();

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/send-internal?isCirc=1',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']},
                                        success: function(o){
                                            Ext.MessageBox.hide();
                                            var r = Ext.decode(o.responseText);
                                            var regNo = '';
                                            var sendDate = '';
                                            var reserveMsg = '';
                                            for(i=0; i < r.length ; i++) {
                                                if(regNo == '') {
                                                    regNo = r[i].regNo;
                                                } else {
                                                    regNo = regNo +','+r[i].regNo;
                                                }
                                            }
    										if(r.reserve == 1) {
                                                reserveMsg = '<br/>�ͧ�Ţ��� '+r.bookNoR;
    										}
                                            docNo = r.docno;
                                            regNo = r.regno;
                                            sendDate = r[0].recvDate;
											sendTime = r[0].recvTime;
                                           	Ext.MessageBox.show({
												title: '��������¹����',
                                                msg: '�����º��������<br/>˹ѧ����Ţ��� : '+docNo+'<br/>' + '���ѹ��� '+sendDate+',����'+sendTime+reserveMsg,
                                                buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
                                                fn: function(id,str) {
                                                    if(id=='yes') {
                                                    	sendInternalWindow.hide();
														//saveECMData('_pw','si');
                                                    	Cookies.set('docRefID',r[0].transID);
														viewDocumentCrossModule('viewDOC_'+r[0].transID,r.title,'SendInternal',r.docID,r[0].transID);
    												}
    											},
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('sendInternalForm').getForm().reset();
											clearExternalSendToSelections();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '��������¹����',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                           form: Ext.getCmp('sendInternalForm').getForm().getEl()
                                       });
								    }
								    });
                                }
                            }
                        });

                    }
                },{
                    id: 'btnCloseAcceptWindow',
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        sendInternalWindow.hide();
                    }
                }]
        });

        var sendInternalWindow = new Ext.Window({
            id: 'sendInternalWindow',
            title: '{$lang['workitem']['sendDocumentToInternal']}',
            width: 700,
            height: 615,
            minWidth: 700,
            minHeight: 615,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: sendInternalForm,
            closable: false,
            keys: {
                key: Ext.EventObject.ESC,
                fn: function (){
                    sendInternalForm.getForm().reset();
                    Ext.getCmp('sendInternalWindow').hide();
                },
                scope: this
            }
        });

        Ext.getCmp('sendIntFieldGroup').on('beforeexpand',function(p,a){
            sendInternalWindow.setHeight(635);
            sendInternalWindow.center();
        });

        Ext.getCmp('requestExtDocNo').on('check',function(el,val){
        	if(val) {
        		//Ext.getCmp('reserveExt').enable();
        		Ext.getCmp('reserveExtGlobal').enable();
    		} else {
	    		//Ext.getCmp('reserveExt').setValue(false);
        		Ext.getCmp('reserveExtGlobal').setValue(false);
    			//Ext.getCmp('reserveExt').disable();
        		Ext.getCmp('reserveExtGlobal').disable();
    		}
        });

        /*
        Ext.getCmp('reserveExt').on('check',function(el,val){
        	if(val) {
        		Ext.getCmp('reserveExtGlobal').setValue(false);
    		}
        });
        */

        Ext.getCmp('reserveExtGlobal').on('check',function(el,val){
        	if(val) {
        		Ext.getCmp('reserveExt').setValue(false);
    		}
        });



        Ext.getCmp('sendIntFieldGroup').on('beforecollapse',function(p,a){
            sendInternalWindow.setHeight(485);
            sendInternalWindow.center();
        });

        </script>";

        return $js;
    }

    private function sendInternalGlobalJS() {
        global $config;
        global $lang;
        global $sessionMgr;

        if($sessionMgr->isSarabanMaster()) {
            $readOnlyDocno = "readOnly: false,";
        } else {
            $readOnlyDocno = "readOnly: true,";
        }

        if($config ['disableOverrideDocNo']) {
            $readOnlyDocno = "readOnly: true,";
        } else {
			$readOnlyDocno = "readOnly: false,";
		}

        if($config ['disableOverrideSendDateTime']) {
            $sendDateFlag = "readOnly: true,";
            $sendTimeFlag = "readOnly: true,
            hideTrigger: true,
            editable: true,
            typeAhead: false,";
        } else {
            $sendDateFlag = "readOnly: false,";
            $sendTimeFlag = "readOnly: false,";
        }

        include_once 'Organize.Entity.php';
        $orgID = $sessionMgr->getCurrentOrgID ();
        $org = new OrganizeEntity ( );
        if (! $org->Load ( "f_org_id = '{$orgID}'" )) {
            $docCode = '';
        } else {
            $docCode = $org->f_int_code;
        }

        $js = "<script type=\"text/javascript\">
        var sendInternalGlobalForm = new Ext.form.FormPanel({
            id: 'sendInternalGlobalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,
            items: [{
                    xtype:'fieldset',
                    title: '��������´�͡���',
                    autoHeight:true,
                    collapsible: false,
                    collapsed: false,
                    layout: 'column',
                    items: [{
                        columnWidth: .6,
                        layout: 'form',
                        baseCls: 'x-plain',
                        items:[{
                            xtype:'textfield',
                            fieldLabel: '�����������',
                            allowBlank: false,
                            name: 'sendIntGlobalType',
                            value: '������(��ǹ��ҧ)',
                            readOnly: true
                        },{
                            xtype: 'hidden',
                            name: 'sendIntGlobalRefTransID',
                            id: 'sendIntGlobalRefTransID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendIntGlobalRefOrgID',
                            id: 'sendIntGlobalRefOrgID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendIntGlobalRefOrgDocCode',
                            id: 'sendIntGlobalRefOrgDocCode',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendIntGlobalRefDocID',
                            id: 'sendIntGlobalRefDocID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendIntGlobalRefBookno',
                            id: 'sendIntGlobalRefBookno',
                            value: 0
                        },{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����͡���',
                            allowBlank: true,
                            value: '{$docCode}/',
                            {$readOnlyDocno}
                            name: 'sendIntGlobalDocNo'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: 'ŧ�ѹ���',
                            name: 'sendIntGlobalDocDate',
                            readOnly: true,
                            emptyText: 'Default',
                            width: 100
                        }),{
                            xtype:'textfield',
                               fieldLabel: '��ҧ�֧',
                            name: 'sendIntGlobalRefer',
                            width: 280
                        },{
                            xtype:'textfield',
                               fieldLabel: '��觷�����Ҵ���',
                            name: 'sendIntGlobalAttachment',
                            width: 280
                        },new Ext.form.ComboBox({
                            store: autocompleteSenderTextStore,
							id: 'sendIntGlobalSendFrom',
                            name: 'sendIntGlobalSendFrom',
                            fieldLabel: '�ҡ',
                            style: autoFieldStyle,
                            emptyText: 'Default',
                            minChars: 2,
                            displayField:'name',
                            typeAhead: false,
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            pageSize:10,
                            hideTrigger:true,
                            tpl: resultTpl,
                            itemSelector: 'div.search-item'
                        }),{
                            id: 'sendIntGlobalSendTo',
                            xtype:'textfield',
                            fieldLabel: '���¹',
                            style: popupFieldStyle,
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'sendIntGlobalSendTo',
                            width: 280
                        },{
                            id: 'sendIntGlobalSendToHidden',
                            xtype:'hidden',
                            name: 'sendIntGlobalSendToHidden'
                        },{
                            xtype:'textarea',
                               fieldLabel: '����ͧ',
                               labelStyle: 'font-weight:bold;color: Red;',
                            allowBlank: false,
                            name: 'sendIntGlobalTitle',
                            width: 280,
                            height: 65
                        },{
                            xtype:'textarea',
                               fieldLabel: '��������´',
                            name: 'sendIntGlobalDesc',
                            width: 280,
                            height: 65
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth: .4,
                        layout: 'form',
                        items:[{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����¹��',
                            emptyText: 'Default',
                            name: 'sendIntGlobalSendNo'
                        },new Ext.form.ComboBox({
                            name: 'sendIntGlobalRegBookNo',
                            allowBlank: false,
                            fieldLabel: '����¹������',
                            store: registerBookReceiveInternalListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntGlobalRegBookID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ������͡',
                            name: 'sendIntGlobalSendDate',
                            {$sendDateFlag}
                            emptyText: 'Default',
                            width: 100
                        }),new Ext.form.TimeField({
                            fieldLabel: '���ҷ�����͡',
                            name: 'sendIntGlobalSendTime',
                            emptyText: 'Default',
                            {$sendTimeFlag}
                            format: 'H:i',
                            width: 100
                        }),    new Ext.form.LocalComboBox({
                            store: speedLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'sendIntGlobalSpeedLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤�������',
                            mode: 'local',
                            value: 0,
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),    new Ext.form.LocalComboBox({
                            store: secretLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'sendIntGlobalSecretLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤����Ѻ',
                            mode: 'local',
                            value: 0,
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendIntGlobalDocType',
                            allowBlank: false,
                            fieldLabel: '�������͡���',
                            store: doctypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntGlobalDocType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendIntGlobalDeliverType',
                            allowBlank: false,
                            fieldLabel: '�Ըա�ù����͡���',
                            store: receiveTypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntGlobalDeliverType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendIntGlobalFormID',
                            allowBlank: false,
                            fieldLabel: 'Ẻ�����',
                            store: formListSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntGlobalFormID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        })]
                    }]
                },{
                    xtype:'fieldset',
                    id: 'sendIntGlobalFieldGroup',
                    title: '�ѹ�֡���ʹ��Թ���',
                    autoHeight:true,
                    collapsible: true,
                    collapsed: false,
                    layout: 'column',
                    items: [{
                        baseCls: 'x-plain',
                        columnWidth:.6,
                        layout: 'form',
                        items:[new Ext.form.ComboBox({
                            name: 'sendIntGlobalPurpose',
                            fieldLabel: '�ѵ�ػ��ʧ��',
                            store: purposeSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendIntGlobalPurpose',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            listWidth: 260,
                            width: 260
                        }),new Ext.form.ComboBox({
                            store: autoCompleteNameWithRole,
                            fieldLabel: 'ŧ�����',
                            displayField:'name',
                            style: autoFieldStyle,
                            typeAhead: false,
                            emptyText: 'Default',
                            loadingText: '{$lang['common']['searcing']}',
                            listWidth: 350,
                            width: 280,
                            hideTrigger: true,
                            allowBlank: true,
                            id: 'sendIntGlobalSignBy',
                            name: 'sendIntGlobalSignBy',
                            valueField: 'id',
							hiddenName: 'sendIntGlobalSignByID',
                            tpl: nameRoleLookupTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-name-role'
                        })/*{
                            xtype:'textfield',
                            fieldLabel: 'ŧ�����',
                            name: 'sendIntGlobalSignBy',
                            width: 280
                        }*/,{
                            xtype:'textfield',
                            fieldLabel: '������͡���',
                            name: 'sendIntGlobalLocation',
                            width: 280
                        },{
                            xtype: 'textarea',
                            fieldLabel: '�����˵�',
                            name: 'sendIntGlobalRemark',
                            width: 280,
                            height: 50
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth:.4,
                        layout: 'form',
                        items:[new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��������͡���',
                            name: 'sendIntGlobalDocExpireDate',
                            emptyText: 'Default',
                            width: 100
                        }),{
                            xtype: 'checkbox',
                            fieldLabel: '�Դ�����',
                            name: 'sendIntGlobalFollowUp'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��˹�����',
                            name: 'sendIntGlobalDocDeadline',
                            width: 100
                        })
                        ]
                    }
                    ]
                }],
                buttons: [{
                    //id: 'btnSend',
                    formBind: true,
                    text: '{$lang['workitem']['send']}',
                    handler: function() {
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
                                    //receiveExternalForm.getForm().reset();
                                    Ext.MessageBox.confirm('Confirm', '���͡���㹷���¹��ҧ ?', function(btn) {
                                	if(btn == 'yes') {

                                    Ext.MessageBox.show({
                                          msg: '���ѧ���͡��س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });

                                    tempSendStore.removeAll();
                                    tempCCStore.removeAll();

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/send-internal-global?isCirc=0',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']},
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              checkSession(r);
                                              var regNo = '';
                                              var sendDate = '';
                                                  for(i=0; i < r.length ; i++) {
                                                      if(regNo == '') {
                                                          regNo = r[i].regNo;
                                                    } else {
                                                        regNo = regNo +','+r[i].regNo;
                                                    }
                                                }
                                                sendDate = r[0].recvDate;
                                                sendTime = r[0].recvTime;
                                              Ext.MessageBox.show({
                                              	title: '���������(����¹��ҧ)',
                                                msg: '�����º��������<br/>˹ѧ����Ţ��� : '+r.docno+'<br/>' + '���ѹ���  : '+sendDate+',���� : '+sendTime,
                                                buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
												fn: function(id,str) {
                                                	if(id=='yes') {
                                                    	sendInternalGlobalWindow.hide();
														//saveECMData('_pw','sig');
                                                    	Cookies.set('docRefID',r[0].transID);
														viewDocumentCrossModule('viewDOC_'+r[0].transID,r.title,'SendInternalGlobal',r.docID,r[0].transID);
    												}
    											},
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('sendInternalGlobalForm').getForm().reset();
											clearExternalSendToSelections();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '���������(����¹)',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                           form: Ext.getCmp('sendInternalGlobalForm').getForm().getEl()
                                       });
								    }
								    });
                                }
                            }
                        });

                    }
                },{
                    //id: 'btnAccept',
                    formBind: true,
                    text: '{$lang['workitem']['sendCirc']}',
                    handler: function() {
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
                                    //receiveExternalForm.getForm().reset();
                                    Ext.MessageBox.confirm('Confirm', '���͡���㹷���¹��ҧ ?', function(btn) {
                                	if(btn == 'yes') {
                                    Ext.MessageBox.show({
                                          msg: '���ѧ���͡��س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });

                                    tempSendStore.removeAll();
                                    tempCCStore.removeAll();

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/send-internal-global?isCirc=1',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']},
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              var regNo = '';
                                              var sendDate = '';
                                                  for(i=0; i < r.length ; i++) {
                                                      if(regNo == '') {
                                                          regNo = r[i].regNo;
                                                    } else {
                                                        regNo = regNo +','+r[i].regNo;
                                                    }
                                                }
                                                sendDate = r[0].recvDate;
                                                sendTime = r[0].recvTime;
                                              Ext.MessageBox.show({
                                                title: '��������¹����(����¹��ҧ)',
                                                msg: '�����º��������<br/>˹ѧ����Ţ��� : '+r.docno+'<br/>' + '���ѹ���  : '+sendDate+',���� : '+sendTime,
                                                buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
												fn: function(id,str) {
                                                	if(id=='yes') {
                                                    	sendInternalGlobalWindow.hide();
														//saveECMData('_pw','sig');
                                                    	Cookies.set('docRefID',r[0].transID);
														viewDocumentCrossModule('viewDOC_'+r[0].transID,r.title,'SendInternalGlobal',r.docID,r[0].transID);
    												}
    											},
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('sendInternalGlobalForm').getForm().reset();
											clearExternalSendToSelections();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '��������¹����(����¹��ҧ)',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                           form: Ext.getCmp('sendInternalGlobalForm').getForm().getEl()
                                       });
								    }
								    });
                                }
                            }
                        });

                    }
                },{
                    id: 'btnCloseAcceptWindow',
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        sendInternalGlobalWindow.hide();
                    }
                }]
        });

        var sendInternalGlobalWindow = new Ext.Window({
            id: 'sendInternalGlobalWindow',
            title: '{$lang['workitem']['sendDocumentToInternal']}',
            width: 700,
            height: 615,
            minWidth: 700,
            minHeight: 615,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: sendInternalGlobalForm,
            closable: false,
            keys: {
                key: Ext.EventObject.ESC,
                fn: function (){
                    sendInternalGlobalForm.getForm().reset();
                    Ext.getCmp('sendInternalGlobalWindow').hide();
                },
                scope: this
            }
        });

        Ext.getCmp('sendIntGlobalFieldGroup').on('beforeexpand',function(p,a){
            sendInternalGlobalWindow.setHeight(635);
            sendInternalGlobalWindow.center();
        });

        Ext.getCmp('sendIntGlobalFieldGroup').on('beforecollapse',function(p,a){
            sendInternalGlobalWindow.setHeight(485);
            sendInternalGlobalWindow.center();
        });

        </script>";

        return $js;
    }

    private function receiveExternalJS() {
        global $lang;
        global $config;

        if($config ['disableOverrideRecvDateTime']) {
            $recvDateFlag = "readOnly: true,";
            $recvTimeFlag = "readOnly: true,
            hideTrigger: true,
            editable: true,
            typeAhead: false,";
        } else {
            $recvDateFlag = "readOnly: false,";
            $recvTimeFlag = "readOnly: false,";
        }

        $js = "<script type=\"text/javascript\">
        var receiveExternalForm = new Ext.form.FormPanel({
            id: 'receiveExternalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,

            items: [
                {
                     xtype:'fieldset',
                    title: '��������´�͡���',
                    autoHeight:true,
                    collapsible: false,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        columnWidth: .6,
                        layout: 'form',
                        baseCls: 'x-plain',
                        items:[{
                            xtype:'textfield',
                               fieldLabel: '���������ŧ�Ѻ',
                            allowBlank: false,
                            name: 'recvType',
                            value: 'ŧ�Ѻ��¹͡',
                            readOnly: true
                        },{
                            xtype:'textfield',
                               fieldLabel: '�Ţ����͡���',
                            //allowBlank: false,
                            //labelStyle: 'font-weight:bold;color: Red;',
                            emptyText : '{$lang['df']['noBookNo']}',
                            name: 'recvExtDocNo'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: 'ŧ�ѹ���',
                            readOnly: true,
                            emptyText: 'Default',
                            name: 'recvExtDocDate',
                            width: 100
                        }),{
                            xtype:'textfield',
                               fieldLabel: '��ҧ�֧',
                            name: 'recvExtRefer',
                            width: 280
                        },{
                            xtype:'textfield',
                               fieldLabel: '��觷�����Ҵ���',
                            name: 'recvExtAttachment',
                            width: 280
                        },/*{
                            xtype:'textfield',
                               fieldLabel: '�ҡ',
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'recvExtSendFrom',
                            width: 280
                        }*/new Ext.form.ComboBox({
                            store: autocompleteSenderExternalStore,
                            fieldLabel: '�ҡ',
                            displayField:'name',
                            typeAhead: false,
                            style: autoFieldStyle,
                            emptyText: 'Auto Complete Field',
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            //pageSize:2,
                            hideTrigger:true,
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'recvExtSendFrom',
                            tpl: resultTpl,
                            //lazyInit: true,
                            //lazyRender: true,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-item'
                        }),/*{
                            xtype:'textfield',
                               fieldLabel: '���¹',
                               emptyText: 'Default',
                            name: 'recvExtSendTo',
                            width: 280
                        }*/new Ext.form.ComboBox({
                            store: autocompleteDeptReceiverTextStore,
                            fieldLabel: '���¹',
                            displayField:'name',
                            typeAhead: false,
                            style: autoFieldStyle,
                            emptyText: 'Default',
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            //pageSize:2,
                            hideTrigger:true,
                            name: 'recvExtSendTo',
                            tpl: resultTpl,
                            //lazyInit: true,
                            //lazyRender: true,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-item'
                        }),{
                            xtype:'textarea',
                               fieldLabel: '����ͧ',
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'recvExtTitle',
                            width: 280,
                            height: 65
                        },{
                            xtype:'textarea',
                               fieldLabel: '��������´',
                            name: 'recvExtDesc',
                            width: 280,
                            height: 65
                        }]

                    },{
                        baseCls: 'x-plain',
                        columnWidth: .4,
                        layout: 'form',
                        items:[{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����¹�Ѻ',
                            emptyText: 'Default',
                            name: 'recvExtRecvNo',
                            editable: false
                        },new Ext.form.ComboBox({
                            name: 'recvExtRegBookID',
                            fieldLabel: '����¹�Ѻ��¹͡',
                            store: registerBookReceiveExternalListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtRegBookID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ���ŧ�Ѻ',
                            name: 'recvExtRecvDate',
                            {$recvDateFlag}
                            emptyText: 'Default',
                            width: 100
                        }),new Ext.form.TimeField({
                            name: 'recvExtRecvTime',
                            fieldLabel: '���ҷ��ŧ�Ѻ',
                            {$recvTimeFlag}
                            emptyText: 'Default',
                            format: 'H:i',
                            width: 100
                        }),new Ext.form.LocalComboBox({
                            name: 'recvExtSpeedLevel',
                            store: speedLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'recvExtSpeedLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤�������',
                            mode: 'local',
                            triggerAction: 'all',
                            selectOnFocus: true,
                            value: 0,
                            width: 100
                        }),new Ext.form.LocalComboBox({
                            name: 'recvExtSecretLevel',
                            store: secretLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'recvExtSecretLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤����Ѻ',
                            mode: 'local',
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'recvExtDocType',
                            allowBlank: false,
                            fieldLabel: '�������͡���',
                            store: doctypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtDocType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            id: 'recvExtDeliverTyperRef',
                            name: 'recvExtDeliverType',
                            allowBlank: false,
                            fieldLabel: '�Ըա�ù����͡���',
                            store: receiveTypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtDeliverType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'recvExtForm',
                            allowBlank: false,
                            fieldLabel: 'Ẻ�����',
                            store: formListSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtFormID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),{
                            id: 'recvExtForwardTo',
                            xtype:'textfield',
                            fieldLabel: '�觵��',
                            allowBlank: true,
                            style: popupFieldStyle,
                            name: 'recvExtForwardTo',
                            width: 150,
                            height: 50
                        },{
                            id: 'recvExtForwardToHidden',
                            xtype:'hidden',
                            name: 'recvExtForwardToHidden'
                        }]
                    }
                    ]
                },{
                    xtype:'fieldset',
                    title: '�ѹ�֡���ʹ��Թ���',
                    id: 'recvExtFieldGroup',
                    title: '�ѹ�֡���ʹ��Թ���',
                    autoHeight:true,
                    collapsible: true,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        baseCls: 'x-plain',
                        columnWidth:.6,
                        layout: 'form',
                        items:[new Ext.form.ComboBox({
                            name: 'recvExtPurpose',
                            fieldLabel: '�ѵ�ػ��ʧ��',
                            store: purposeSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtPurpose',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 280
                        }),{
                            xtype:'textfield',
                            fieldLabel: '�����¹/���ʹ�',
                            name: 'recvExtAttend',
                            width: 280
                        },{
                            xtype:'textfield',
                            fieldLabel: '������͡���',
                            name: 'recvExtLocation',
                            width: 280
                        },{
                            xtype: 'textarea',
                            fieldLabel: '�����˵�',
                            name: 'recvExtRemark',
                            width: 280,
                            height: 50
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth:.4,
                        layout: 'form',
                        items:[new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��������͡���',
                            name: 'recvExtDocFollowUp',
                            emptyText: 'Default',
                            width: 100
                        }),{
                            xtype: 'checkbox',
                            fieldLabel: '�Դ�����',
                            name: 'recvExtTrack'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��˹�����',
                            name: 'recvExtDocDeadline',
                            width: 100
                        })
                        ]
                    }
                    ]
                }],
            buttons: [{
                formBind:true,
                text: '{$lang['workitem']['accept']}',
                handler: function() {
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
                                //receiveExternalForm.getForm().reset();
                                Ext.MessageBox.show({
                                      msg: '���ѧŧ�Ѻ��س����ѡ����...',
                                       progressText: '�ѹ�֡������...',
                                      width:300,
                                       wait:true,
                                      waitConfig: {interval:200},
                                       icon:'ext-mb-download'
                                });

                                Ext.Ajax.request({
                                    url: '/{$config ['appName']}/df-action/receive-external',
                                    method: 'POST',
                                    success: function(o){
                                          Ext.MessageBox.hide();
                                          var r = Ext.decode(o.responseText);

                                          checkSession(r);

                                          if(r.success ==1) {
                                              Ext.MessageBox.show({
                                                title: '���ŧ�Ѻ��¹͡',
                                                msg: 'ŧ�Ѻ���º��������<br/>�Ѻ��¹͡�Ţ���'+r.regNo +'<br/>' + 'ŧ�Ѻ�ѹ��� '+r.recvDate+',����'+r.recvTime,
                                                buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
												fn: function(id,str) {
	                                                if(id=='yes') {
	                                                    receiveExternalWindow.hide();
														//saveECMData('_pw','re');
	                                                    Cookies.set('docRefID',r.transID);
														viewDocumentCrossModule('viewDOC_'+r.transID,r.title,'ReceiveExternal',r.docID,r.transID);
	    											}
	    										},
                                                icon: Ext.MessageBox.INFO
                                              });
                                              Ext.getCmp('receiveExternalForm').getForm().reset();
                                              clearInternalSendToSelections();
                                          } else {
                                            if(r.duplicate == 1) {
                                                Ext.MessageBox.show({
                                                    title: '���ŧ�Ѻ��¹͡',
                                                    msg: 'ŧ�Ѻ�����<br/>˹ѧ������Ѻ���������',
                                                    buttons: Ext.MessageBox.OK,
                                                    icon: Ext.MessageBox.INFO
                                                 });
                                                //Ext.getCmp('receiveExternalForm').getForm().reset();
                                                //clearInternalSendToSelections();
                                            }
                                          }
                                    },
                                    failure: function(r,o) {
                                        Ext.MessageBox.hide();
                                        Ext.MessageBox.show({
                                            title: '���ŧ�Ѻ��¹͡',
                                            msg: '�������öŧ�Ѻ��',
                                            buttons: Ext.MessageBox.OK,
                                            icon: Ext.MessageBox.ERROR
                                        });
                                    },
                                       form: Ext.getCmp('receiveExternalForm').getForm().getEl()
                                });
                            }
                        }
                    });

                }
            },{
                text: '{$lang['common']['cancel']}',
                handler: function() {
                    receiveExternalWindow.hide();
                }
            }]
        });

        var receiveExternalWindow = new Ext.Window({
            id: 'receiveExternalWindow',
            title: '{$lang['workitem']['receiveDocumentFromExternal']}',
            width: 700,
            height: 615,
            minWidth: 700,
            minHeight: 615,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: receiveExternalForm,
            closable: false,
            keys: {
                key: Ext.EventObject.ESC,
                fn: function (){
                    receiveExternalForm.getForm().reset();
                    Ext.getCmp('receiveExternalWindow').hide();
                },
                scope: this
            }
        });

        Ext.getCmp('recvExtFieldGroup').on('beforeexpand',function(p,a){
            receiveExternalWindow.setHeight(615);
            receiveExternalWindow.center();
        });

        Ext.getCmp('recvExtFieldGroup').on('beforecollapse',function(p,a){
            receiveExternalWindow.setHeight(460);
            receiveExternalWindow.center();
        });

        var externalReceivingType = Ext.getCmp('recvExtDeliverTyperRef');
        //var sendExtSelector = Ext.getCmp('sendExtSendTo');

        externalReceivingType.on('select',function(c,r,i) {
            externalReceivingType.suspendEvents();
            dataRecord = c.store.getAt(i);
            Cookies.set('txs',dataRecord.data.id);
            externalReceivingType.resumeEvents() ;
        },externalReceivingType);

        //sendExtSelector.on('select',function(c,r,i) {
        //    if(Cookies.get('typeOfExternalReceiver')==4) {
        //        dataRecord = c.store.getAt(i);
        //        alert(dataRecord.data.id);
        //        alert(dataRecord.data.name);
        //    }
        //
        //},sendExtSelector);

        </script>";

        return $js;
    }

    private function sendExternalJS() {
        global $lang;
        global $config;
        global $sessionMgr;

        if($sessionMgr->isSarabanMaster()) {
            $readOnlyDocno = "readOnly: false,";
        } else {
            $readOnlyDocno = "readOnly: true,";
        }
        if($config ['disableOverrideDocNo']) {
            $readOnlyDocno = "readOnly: true,";
        } else {
			$readOnlyDocno = "readOnly: false,";
		}

        if($config ['disableOverrideSendDateTime']) {
            $sendDateFlag = "readOnly: true,";
            $sendTimeFlag = "readOnly: true,
            hideTrigger: true,
            editable: true,
            typeAhead: false,";
        } else {
            $sendDateFlag = "readOnly: false,";
            $sendTimeFlag = "readOnly: false,";
        }

        include_once 'Organize.Entity.php';
        $orgID = $sessionMgr->getCurrentOrgID ();
        $org = new OrganizeEntity ( );
        if (! $org->Load ( "f_org_id = '{$orgID}'" )) {
            $docCode = '';
        } else {
            $docCode = $org->f_ext_code;
        }

        $js = "<script type=\"text/javascript\">
        var sendExternalForm = new Ext.form.FormPanel({
            id: 'sendExternalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,

            items: [
                {
                    xtype:'fieldset',
                    title: '��������´�͡���',
                    autoHeight:true,
                    collapsible: false,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        columnWidth: .6,
                        layout: 'form',
                        baseCls: 'x-plain',
                        items:[{
                            xtype:'textfield',
                               fieldLabel: '�����������',
                            allowBlank: false,
                            name: 'sendExtSendType',
                            value: '����¹͡',
                            readOnly: true
                        },{
                            xtype: 'hidden',
                            name: 'sendExtRefTransID',
                            id: 'sendExtRefTransID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtRefOrgID',
                            id: 'sendExtRefOrgID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtRefOrgDocCode',
                            id: 'sendExtRefOrgDocCode',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtRefDocID',
                            id: 'sendExtRefDocID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtRefBookno',
                            id: 'sendExtRefBookno',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtUseReserveID',
                            id: 'sendExtUseReserveID',
                            value: 0
                        },{
                            xtype:'textfield',
                               fieldLabel: '�Ţ����͡���',
                            //allowBlank: false,
                            value: '{$docCode}/',
                            {$readOnlyDocno}
                            //labelStyle: 'font-weight:bold;color: Red;',
                            id: 'sendExtDocNo',
                            name: 'sendExtDocNo'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: 'ŧ�ѹ���',
                            emptyText: 'Default',
                            readOnly: true,

                            name: 'sendExtDocDate',
                            width: 100
                        }),{
                            xtype:'textfield',
                               fieldLabel: '��ҧ�֧',
                               id: 'sendExtRefer',
                            name: 'sendExtRefer',
                            width: 280
                        },{
                            xtype:'textfield',
                               fieldLabel: '��觷�����Ҵ���',
                            name: 'sendExtAttachment',
                            width: 280
                        },/*{
                            xtype:'textfield',
                               fieldLabel: '�ҡ',
                               emptyText: 'Default',
                            name: 'sendExtSendFrom',
                            width: 280
                        }*/new Ext.form.ComboBox({
                            store: autocompleteSenderTextStore,
                            name: 'sendExtSendFrom',
                            fieldLabel: '�ҡ',
                            style: autoFieldStyle,
                            emptyText: 'Default',
                            minChars: 2,
                            displayField:'name',
                            typeAhead: false,
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            pageSize:10,
                            hideTrigger:true,
                            tpl: resultTpl,
                            itemSelector: 'div.search-item'
                        }),{
                            xtype:'textfield',
                            fieldLabel: '���¹',
                            allowBlank: false,
                            //readOnly: true,
                            labelStyle: 'font-weight:bold;color: Red;',
                            id: 'sendExtSendTo',
                            name: 'sendExtSendTo',
                            width: 280
                        }/*new Ext.form.ComboBox({
                            id: 'sendExtSendTo',
                            store: autocompleteReceiverExternalStore,
                            name: 'sendExtSendTo',
                            style: autoFieldStyle,
                            fieldLabel: '���¹',
                            emptyText: 'Default',
                            minChars: 2,
                            displayField:'name',
                            typeAhead: false,
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            pageSize:10,
                            hideTrigger:true,
                            tpl: resultDepartmentTpl,
                            labelStyle: 'font-weight:bold;color: Red;',
                            allowBlank: false,
                            itemSelector: 'div.search-item'
                        })*/,{
                            xtype:'hidden',
                            id: 'sendExtSendToHidden',
                            name: 'sendExtSendToHidden'
                        },{
                            xtype:'textarea',
                               fieldLabel: '����ͧ',
                               labelStyle: 'font-weight:bold;color: Red;',
                            allowBlank: false,
                            name: 'sendExtTitle',
                            id: 'sendExtTitle',
                            width: 280,
                            height: 65
                        },{
                            xtype:'textarea',
                               fieldLabel: '��������´',
                            name: 'sendExtDesc',
                            width: 280,
                            height: 65
                        }]

                    },{
                        baseCls: 'x-plain',
                        columnWidth: .4,
                        layout: 'form',
                        items:[{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����¹��',
                            emptyText: 'Default',
                            id: 'sendExtSendNo',
                            name: 'sendExtSendNo'
                        },new Ext.form.ComboBox({
                            name: 'sendExtRegBookID',
                            allowBlank: false,
                            fieldLabel: '����¹����¹͡',
                            store: registerBookReceiveExternalListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtRegBookID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ������͡',
                            name: 'sendExtSendDate',
                            emptyText: 'Default',
                            {$sendDateFlag}
                            width: 100
                        }),new Ext.form.TimeField({
                            fieldLabel: '���ҷ�����͡',
                            name: 'sendExtSendTime',
                            emptyText: 'Default',
                            {$sendTimeFlag}
                            format: 'H:i',
                            width: 100
                        }),new Ext.form.LocalComboBox({
                            store: speedLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'sendExtSpeedLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤�������',
                            mode: 'local',
                            value: 0,
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.LocalComboBox({
                            store: secretLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'sendExtSecretLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤����Ѻ',
                            mode: 'local',
                            value: 0,
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendExtDocType',
                            allowBlank: false,
                            fieldLabel: '�������͡���',
                            store: doctypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtDocType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            id: 'sendExtDeliverTypeRef',
                            name: 'sendExtDeliverType',
                            allowBlank: false,
                            fieldLabel: '�Ըա�ù����͡���',
                            store: receiveTypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtDeliverType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendExtFormID',
                            allowBlank: false,
                            fieldLabel: 'Ẻ�����',
                            store: formListSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtFormID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        })]
                    }
                    ]
                },{
                    xtype:'fieldset',
                    id: 'sendExtFieldGroup',
                    title: '�ѹ�֡���ʹ��Թ���',
                    autoHeight:true,
                    collapsible: true,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        baseCls: 'x-plain',
                        columnWidth:.6,
                        layout: 'form',
                        items:[new Ext.form.ComboBox({
                            name: 'sendExtPurpose',
                            fieldLabel: '�ѵ�ػ��ʧ��',
                            store: purposeSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtPurpose',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            listWidth: 260,
                            width: 260
                        }),new Ext.form.ComboBox({
                            store: autoCompleteNameWithRole,
                            fieldLabel: 'ŧ�����',
                            displayField:'name',
                            style: autoFieldStyle,
                            typeAhead: false,
                            emptyText: 'Default',
                            loadingText: '{$lang['common']['searcing']}',
                            listWidth: 350,
                            width: 280,
                            hideTrigger: true,
                            allowBlank: true,
                            id: 'sendExtSignBy',
                            name: 'sendExtSignBy',
                            valueField: 'id',
							hiddenName: 'sendExtSignByID',
                            tpl: nameRoleLookupTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-name-role'
                        })/*{
                            xtype:'textfield',
                            fieldLabel: 'ŧ�����',
                            name: 'sendExtSignBy',
                            width: 280
                        }*/,{
                            xtype:'textfield',
                            fieldLabel: '������͡���',
                            name: 'sendExtLocation',
                            width: 280
                        },{
                            xtype: 'textarea',
                            fieldLabel: '�����˵�',
                            name: 'sendExtRemark',
                            width: 280,
                            height: 50
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth:.4,
                        layout: 'form',
                        items:[new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��������͡���',
                            emptyText: 'Default',
                            name: 'sendExtDocExpireDate',
                            width: 100
                        }),{
                            xtype: 'checkbox',
                            fieldLabel: '�Դ�����',
                            name: 'sendExtFollowUp'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��˹�����',
                            name: 'sendExtDocDeadline',
                            width: 100
                        })
                        ]
                    }
                    ]
                }],
                buttons: [{
                    formBind: true,
                    text: '{$lang['workitem']['send']}',
                    handler: function() {
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
                                 Ext.MessageBox.confirm('Confirm', '���͡��¹͡ ?', function(btn) {
                                	if(btn == 'yes') {
                                    Ext.MessageBox.show({
                                        id: 'dlgReceiveInternal',
                                          msg: '���ѧ���͡��س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/send-external?isCirc=0',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']},
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              checkSession(r);

                                              Ext.MessageBox.show({
                                                title: '�������¹͡',
                                                msg: '�����º��������<br/>˹ѧ����Ţ��� : '+r.docno+'<br/>' + '���ѹ���  : '+r.recvDate+',���� : '+r.recvTime,
                                                buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
												fn: function(id,str) {
                                                	if(id=='yes') {
                                                    	sendExternalWindow.hide();
														//saveECMData('_pw','se');
                                                    	Cookies.set('docRefID',r.transID);
														viewDocumentCrossModule('viewDOC_'+r.transID,r.title,'SendExternal',r.docID,r.transID);
    												}
    											},
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('sendExternalForm').getForm().reset();
                                            if(getECMData('modeGen2')==1) {
                                                saveECMData('modeGen2',0);
                                                sendExternalWindow.hide();
                                            }
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '�������¹͡',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                           form: Ext.getCmp('sendExternalForm').getForm().getEl()
                                       });
								    }
								    });
                                }
                            }
                        });

                    }
                },{
                    formBind: true,
                    text: '{$lang['workitem']['sendCirc']}',
                    handler: function() {
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
                                    //sendExternalForm.getForm().reset();
                                    Ext.MessageBox.confirm('Confirm', '���͡��¹͡ ?', function(btn) {
                                	if(btn == 'yes') {
                                    Ext.MessageBox.show({
                                        id: 'dlgReceiveInternal',
                                          msg: '���ѧ���͡��س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/send-external?isCirc=1',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']},
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              Ext.MessageBox.show({
                                                title: '��������¹��¹͡',
                                                msg: '�����º��������<br/>˹ѧ����Ţ��� : '+r.docno+'<br/>' + '���ѹ���  : '+r.recvDate+',���� : '+r.recvTime,
                                                buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
												fn: function(id,str) {
                                                	if(id=='yes') {
                                                    	sendExternalWindow.hide();
														//saveECMData('_pw','se');
                                                    	Cookies.set('docRefID',r.transID);
														viewDocumentCrossModule('viewDOC_'+r.transID,r.title,'SendExternal',r.docID,r.transID);
    												}
    											},
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('sendExternalForm').getForm().reset();
											clearExternalSendToSelections();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '��������¹��¹͡',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                           form: Ext.getCmp('sendExternalForm').getForm().getEl()
                                       });
								    }
								    });
                                }
                            }
                        });

                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        sendExternalWindow.hide();
                    }
                }]
        });



        var externalSendingType = Ext.getCmp('sendExtDeliverTypeRef');
        var sendExtSelector = Ext.getCmp('sendExtSendTo');

        externalSendingType.on('select',function(c,r,i) {
            externalSendingType.suspendEvents();
            dataRecord = c.store.getAt(i);
            Cookies.set('txr',dataRecord.data.id);
            externalSendingType.resumeEvents() ;
        },externalSendingType);

        sendExtSelector.on('select',function(c,r,i) {
            if(Cookies.get('txr')==4) {
                dataRecord = c.store.getAt(i);
            }
        },sendExtSelector);

        var sendExternalWindow = new Ext.Window({
            id: 'sendExternalWindow',
            title: '{$lang['workitem']['sendDocumentToExternal']}',
            width: 700,
            modal: true,
            height: 615,
            minWidth: 700,
            minHeight: 615,
            layout: 'fit',
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: sendExternalForm,
            closable: false,
            keys: {
                key: Ext.EventObject.ESC,
                fn: function (){
                    sendExternalForm.getForm().reset();
                    Ext.getCmp('sendExternalWindow').hide();
                },
                scope: this
            }
        });

        Ext.getCmp('sendExtFieldGroup').on('beforeexpand',function(p,a){
            sendExternalWindow.setHeight(615);
            sendExternalWindow.center();
        });

        Ext.getCmp('sendExtFieldGroup').on('beforecollapse',function(p,a){
            sendExternalWindow.setHeight(460);
            sendExternalWindow.center();
        });

        </script>";

        return $js;
    }

    private function receiveExternalGlobalJS() {
        global $lang;
        global $config;

        if($config ['disableOverrideRecvDateTime']) {
            $recvDateFlag = "readOnly: true,";
            $recvTimeFlag = "readOnly: true,
            hideTrigger: true,
            editable: true,
            typeAhead: false,";
        } else {
            $recvDateFlag = "readOnly: false,";
            $recvTimeFlag = "readOnly: false,";
        }

        $js = "<script type=\"text/javascript\">
        var receiveExternalGlobalForm = new Ext.form.FormPanel({
            id: 'receiveExternalGlobalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,
            items: [
                {
                    xtype:'fieldset',
                    title: '��������´�͡���',
                    autoHeight:true,
                    collapsible: false,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        columnWidth: .6,
                        layout: 'form',
                        baseCls: 'x-plain',
                        items:[{
                            xtype:'textfield',
                               fieldLabel: '���������ŧ�Ѻ',
                            allowBlank: false,
                            name: 'recvGlobalType',
                            value: 'ŧ�Ѻ����¹��ҧ',
                            readOnly: true
                        },{
                            xtype:'textfield',
                               fieldLabel: '�Ţ����͡���',
                            //allowBlank: false,
                            //labelStyle: 'font-weight:bold;color: Red;',
                            emptyText : '{$lang['df']['noBookNo']}',
                            name: 'recvExtGlobalDocNo'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: 'ŧ�ѹ���',
                            emptyText: 'Default',
                            readOnly: true,
                            name: 'recvExtGlobalDocDate',
                            width: 100
                        }),{
                            xtype:'textfield',
                               fieldLabel: '��ҧ�֧',
                            name: 'recvExtGlobalRefer',
                            width: 280
                        },{
                            xtype:'textfield',
                               fieldLabel: '��觷�����Ҵ���',
                            name: 'recvExtGlobalAttachment',
                            width: 280
                        },new Ext.form.ComboBox({
                            store: autocompleteSenderExternalStore,
                            fieldLabel: '�ҡ',
                            displayField:'name',
                            typeAhead: false,
                            style: autoFieldStyle,
                            emptyText: 'Auto Complete Field',
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            hideTrigger:true,
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'recvExtGlobalSendFrom',
                            tpl: resultTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-item'
                        }),new Ext.form.ComboBox({
                            store: autocompleteDeptReceiverTextStore,
                            fieldLabel: '���¹',
                            displayField:'name',
                            typeAhead: false,
                            style: autoFieldStyle,
                            emptyText: 'Default',
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            hideTrigger:true,
                            name: 'recvExtGlobalSendTo',
                            tpl: resultTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-item'
                        }),{
                            xtype:'textarea',
                               fieldLabel: '����ͧ',
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'recvExtGlobalTitle',
                            width: 280,
                            height: 65
                        },{
                            xtype:'textarea',
                               fieldLabel: '��������´',
                            name: 'recvExtGlobalDesc',
                            width: 280,
                            height: 65
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth: .4,
                        layout: 'form',
                        items:[{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����¹�Ѻ',
                            emptyText: 'Default',
                            name: 'recvExtGlobalRecvNo'
                        },new Ext.form.ComboBox({
                            name: 'recvExtGlobalRegBookID',
                            fieldLabel: '����¹�Ѻ��¹͡',
                            store: registerBookReceiveExternalListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtGlobalRegBookID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ���ŧ�Ѻ',
                            name: 'recvExtGlobalRecvDate',
                            emptyText: 'Default',
                            {$recvDateFlag}
                            width: 100
                        }),new Ext.form.TimeField({
                            name: 'recvExtGlobalRecvTime',
                            fieldLabel: '���ҷ��ŧ�Ѻ',
                            emptyText: 'Default',
                            format: 'H:i',
                            {$recvTimeFlag}
                            width: 100
                        }),new Ext.form.LocalComboBox({
                            name: 'recvExtGlobalSpeedLevel',
                            store: speedLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'recvExtGlobalSpeedLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤�������',
                            mode: 'local',
                            triggerAction: 'all',
                            selectOnFocus: true,
                            value: 0,
                            width: 100
                        }),new Ext.form.LocalComboBox({
                            name: 'recvExtGlobalSecretLevel',
                            store: secretLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'recvExtGlobalSecretLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤����Ѻ',
                            mode: 'local',
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'recvExtGlobalDocType',
                            allowBlank: false,
                            fieldLabel: '�������͡���',
                            store: doctypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtGlobalDocType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            id: 'recvExtGlobalDeliverTyperRef',
                            name: 'recvExtGlobalDeliverType',
                            allowBlank: false,
                            fieldLabel: '�Ըա�ù����͡���',
                            store: receiveTypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtGlobalDeliverType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'recvExtGlobalForm',
                            allowBlank: false,
                            fieldLabel: 'Ẻ�����',
                            store: formListSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'recvExtGlobalFormID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),{
                            id: 'recvExtGlobalForwardTo',
                            xtype:'textarea',
                            style: popupFieldStyle,
                            fieldLabel: '�觵��',
                            allowBlank: true,
                            name: 'recvExtGlobalForwardTo',
                            width: 150,
                            height: 50
                        },{
                            id: 'recvExtGlobalForwardToHidden',
                            xtype:'hidden',
                            name: 'recvExtGlobalForwardToHidden'
                        }]
                    }
                    ]
                },{
                    xtype:'fieldset',
                    id: 'recvExtGlobalFieldGroup',
                    title: '�ѹ�֡���ʹ��Թ���',
                    autoHeight:true,
                    collapsible: true,
                    collapsed: false,
                    layout: 'column',
                    items: [ {
                        baseCls: 'x-plain',
                        columnWidth:.6,
                        layout: 'form',
                        labelWidth: 100,
                        labelAlign: 'left',
                        items:[
                            new Ext.form.ComboBox({
                                name: 'recvExtGlobalPurpose',
                                fieldLabel: '�ѵ�ػ��ʧ��',
                                store: purposeSarabanStore,
                                displayField:'name',
                                valueField: 'id',
                                hiddenName: 'recvExtGlobalPurpose',
                                typeAhead: false,
                                triggerAction: 'all',
                                value: 1,
                                selectOnFocus:true,
                                width: 280
                            }),{
                                xtype:'textfield',
                                fieldLabel: '�����¹/���ʹ�',
                                name: 'recvExtGlobalAttend',
                                width: 280
                            },{
                                xtype:'textfield',
                                fieldLabel: '������͡���',
                                name: 'recvExtGlobalLocation',
                                width: 280
                            },{
                                xtype: 'textarea',
                                fieldLabel: '�����˵�',
                                name: 'recvExtGlobalRemark',
                                width: 280,
                                height: 50
                            }
                            ]
                        },{
                        baseCls: 'x-plain',
                        columnWidth:.4,
                        labelWidth: 100,
                        labelAlign: 'left',
                        layout: 'form',
                        items:[
                                new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��������͡���',
                            name: 'recvExtGlobalDocFollowUp',
                            emptyText: 'Default',
                            width: 100
                        }),{
                            xtype: 'checkbox',
                            fieldLabel: '�Դ�����',
                            name: 'recvExtGlobalTrack'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��˹�����',
                            name: 'recvExtGlobalDocDeadline',
                            width: 100
                        })]
                       }]
                }],
            buttons: [{
                formBind:true,
                text: '{$lang['workitem']['accept']}',
                handler: function() {
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
                                      msg: '���ѧŧ�Ѻ��س����ѡ����...',
                                       progressText: '�ѹ�֡������...',
                                      width:300,
                                       wait:true,
                                      waitConfig: {interval:200},
                                       icon:'ext-mb-download'
                                });

                                Ext.Ajax.request({
                                    url: '/{$config ['appName']}/df-action/receive-external-global',
                                    method: 'POST',
                                    success: function(o){
                                          Ext.MessageBox.hide();
                                          var r = Ext.decode(o.responseText);

                                          checkSession(r);

                                        if(r.success ==1) {
                                             Ext.MessageBox.show({
                                                title: '���ŧ�Ѻ��¹͡',
                                                msg: 'ŧ�Ѻ���º��������<br/>�Ѻ��¹͡�Ţ���'+r.regNo +'<br/>' + 'ŧ�Ѻ�ѹ��� '+r.recvDate+',����'+r.recvTime,
                                                buttons: {yes:'Ṻ�͡���' ,no:'��ŧ'},
												fn: function(id,str) {
	                                                if(id=='yes') {
	                                                    receiveExternalGlobalWindow.hide();
														saveECMData('_pw','reg');
	                                                    Cookies.set('docRefID',r.transID);
														viewDocumentCrossModule('viewDOC_'+r.transID,r.title,'ReceiveExternalGlobal',r.docID,r.transID);
	    											}
	    										},
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('receiveExternalGlobalForm').getForm().reset();
                                            clearInternalSendToSelections();
                                          } else {
                                            if(r.duplicate == 1) {
                                                Ext.MessageBox.show({
                                                    title: '���ŧ�Ѻ��¹͡',
                                                    msg: 'ŧ�Ѻ�����<br/>˹ѧ������Ѻ���������',
                                                    buttons: Ext.MessageBox.OK,
                                                    icon: Ext.MessageBox.INFO
                                                 });
                                               //Ext.getCmp('receiveExternalGlobalForm').getForm().reset();
                                               //clearInternalSendToSelections()
                                            }
                                          }
                                    },
                                    failure: function(r,o) {
                                        Ext.MessageBox.hide();
                                        Ext.MessageBox.show({
                                            title: '���ŧ�Ѻ��¹͡',
                                            msg: '�������öŧ�Ѻ��',
                                            buttons: Ext.MessageBox.OK,
                                            icon: Ext.MessageBox.ERROR
                                        });
                                    },
                                       form: Ext.getCmp('receiveExternalGlobalForm').getForm().getEl()
                                   });
                            }
                        }
                    });

                }
            },{
                text: '{$lang['common']['cancel']}',
                handler: function() {
                    receiveExternalGlobalWindow.hide();
                }
            }]
        });

        var receiveExternalGlobalWindow = new Ext.Window({
            id: 'receiveExternalGlobalWindow',
            title: '{$lang['workitem']['receiveDocumentFromExternalGlobal']}',
            width: 700,
            height: 615,
            minWidth: 700,
            minHeight: 615,
            layout: 'fit',
            plain:true,
            autoScroll: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            modal: true,
            items: receiveExternalGlobalForm,
            closable: false,
            keys: {
                key: Ext.EventObject.ESC,
                fn: function (){
                    receiveExternalGlobalForm.getForm().reset();
                    Ext.getCmp('receiveExternalGlobalWindow').hide();
                },
                scope: this
            }
        });

        Ext.getCmp('recvExtGlobalFieldGroup').on('beforeexpand',function(p,a){
            receiveExternalGlobalWindow.setHeight(615);
            receiveExternalGlobalWindow.center();
        });

        Ext.getCmp('recvExtGlobalFieldGroup').on('beforecollapse',function(p,a){
            receiveExternalGlobalWindow.setHeight(460);
            receiveExternalGlobalWindow.center();
        });

        var externalGlobalReceivingType = Ext.getCmp('recvExtGlobalDeliverTyperRef');

        externalGlobalReceivingType.on('select',function(c,r,i) {
            externalGlobalReceivingType.suspendEvents();
            dataRecord = c.store.getAt(i);
            Cookies.set('txs',dataRecord.data.id);
            externalGlobalReceivingType.resumeEvents() ;
        },externalGlobalReceivingType);

        </script>";

        return $js;
    }

    private function sendExternalGlobalJS() {
        global $lang;
        global $config;
        global $sessionMgr;

        if($sessionMgr->isSarabanMaster()) {
            $readOnlyDocno = "readOnly: false,";
        } else {
            $readOnlyDocno = "readOnly: true,";
        }
        if($config ['disableOverrideDocNo']) {
            $readOnlyDocno = "readOnly: true,";
        } else {
			$readOnlyDocno = "readOnly: false,";
		}

        if($config ['disableOverrideSendDateTime']) {
            $sendDateFlag = "readOnly: true,";
            $sendTimeFlag = "readOnly: true,
            hideTrigger: true,
            editable: true,
            typeAhead: false,";
        } else {
            $sendDateFlag = "readOnly: false,";
            $sendTimeFlag = "readOnly: false,";
        }

        include_once 'Organize.Entity.php';
        $orgID = $sessionMgr->getCurrentOrgID ();
        $org = new OrganizeEntity ( );
        if (! $org->Load ( "f_org_id = '{$orgID}'" )) {
            $docCode = '';
        } else {
            $docCode = $org->f_ext_code;
        }

        $js = "<script type=\"text/javascript\">
        var sendExternalGlobalForm = new Ext.form.FormPanel({
            id: 'sendExternalGlobalForm',
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'left',
            layout:'form',
            monitorValid:true,
            items: [
                {
                    xtype:'fieldset',
                    title: '��������´�͡���',
                    autoHeight:true,
                    collapsible: false,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        columnWidth: .6,
                        layout: 'form',
                        baseCls: 'x-plain',
                        items:[{
                            xtype:'textfield',
                               fieldLabel: '�����������',
                            allowBlank: false,
                            name: 'sendExtGlobalSendType',
                            value: '���͡����¹��ҧ',
                            readOnly: true
                        },{
                            xtype: 'hidden',
                            name: 'sendExtGlobalRefTransID',
                            id: 'sendExtGlobalRefTransID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtGlobalRefOrgID',
                            id: 'sendExtGlobalRefOrgID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtGlobalRefOrgDocCode',
                            id: 'sendExtGlobalRefOrgDocCode',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtGlobalRefDocID',
                            id: 'sendExtGlobalRefDocID',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtGlobalRefBookno',
                            id: 'sendExtGlobalRefBookno',
                            value: 0
                        },{
                            xtype: 'hidden',
                            name: 'sendExtGlobalUseReserveID',
                            id: 'sendExtGlobalUseReserveID',
                            value: 0
                        },{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����͡���',
                            value: '{$docCode}/',
                            {$readOnlyDocno}
                            //labelStyle: 'font-weight:bold;color: Red;',
                            name: 'sendExtGlobalDocNo'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: 'ŧ�ѹ���',
                            readOnly: true,
                            emptyText: 'Default',
                            name: 'sendExtGlobalDocDate',
                            width: 100
                        }),{
                            xtype:'textfield',
                               fieldLabel: '��ҧ�֧',
                            name: 'sendExtGlobalRefer',
                            width: 280
                        },{
                            xtype:'textfield',
                               fieldLabel: '��觷�����Ҵ���',
                            name: 'sendExtGlobalAttachment',
                            width: 280
                        },new Ext.form.ComboBox({
							store: autocompleteSenderTextStore,
							id: 'sendExtGlobalSendFrom',
                            name: 'sendExtGlobalSendFrom',
                            fieldLabel: '�ҡ',
                            style: autoFieldStyle,
                            emptyText: 'Default',
                            minChars: 2,
                            displayField:'name',
                            typeAhead: false,
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            pageSize:10,
                            hideTrigger:true,
                            tpl: resultTpl,
                            itemSelector: 'div.search-item'
                        }),{
                            xtype:'textfield',
                            fieldLabel: '���¹',
                            id: 'sendExtGlobalSendTo',
                            style: popupFieldStyle,
                            allowBlank: false,
                            labelStyle: 'font-weight:bold;color: Red;',
                            name: 'sendExtGlobalSendTo',
                            width: 280
                        },/*new Ext.form.ComboBox({
                            id: 'sendExtGlobalSendTo',
                            store: autocompleteReceiverExternalStore,
                            name: 'sendExtGlobalSendTo',
                            fieldLabel: '���¹',
                            style: autoFieldStyle,
                            emptyText: 'Default',
                            minChars: 2,
                            displayField:'name',
                            typeAhead: false,
                            loadingText: '{$lang['common']['searcing']}',
                            width: 280,
                            pageSize:10,
                            hideTrigger:true,
                            tpl: resultDepartmentTpl,
                            labelStyle: 'font-weight:bold;color: Red;',
                            allowBlank: false,
                            itemSelector: 'div.search-item'
                        }),*/{
                            xtype:'hidden',
                            id: 'sendExtGlobalSendToHidden',
                            name: 'sendExtGlobalSendToHidden'
                        },{
                            xtype:'textarea',
                               fieldLabel: '����ͧ',
                               labelStyle: 'font-weight:bold;color: Red;',
                            allowBlank: false,
                            name: 'sendExtGlobalTitle',
                            width: 280,
                            height: 65
                        },{
                            xtype:'textarea',
                               fieldLabel: '��������´',
                            name: 'sendExtGlobalDesc',
                            width: 280,
                            height: 65
                        }]

                    },{
                        baseCls: 'x-plain',
                        columnWidth: .4,
                        layout: 'form',
                        items:[{
                            xtype:'textfield',
                            fieldLabel: '�Ţ����¹��',
                            emptyText: 'Default',
                            name: 'sendExtGlobalSendNo'
                        },new Ext.form.ComboBox({
                            name: 'sendExtGlobalRegBookID',
                            allowBlank: false,
                            fieldLabel: '����¹����¹͡',
                            store: registerBookReceiveExternalListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtGlobalRegBookID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 0,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ������͡',
                            name: 'sendExtGlobalSendDate',
                            emptyText: 'Default',
                            {$sendDateFlag}
                            width: 100
                        }),new Ext.form.TimeField({
                            fieldLabel: '���ҷ�����͡',
                            name: 'sendExtGlobalSendTime',
                            emptyText: 'Default',
                            {$sendTimeFlag}
                            format: 'H:i',
                            width: 100
                        }),new Ext.form.LocalComboBox({
                            store: speedLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'sendExtGlobalSpeedLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤�������',
                            mode: 'local',
                            value: 0,
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.LocalComboBox({
                            store: secretLevelStore,
                            displayField: 'name',
                            valueField: 'value',
                            hiddenName: 'sendExtGlobalSecretLevel',
                            typeAhead: false,
                            fieldLabel: '��鹤����Ѻ',
                            mode: 'local',
                            value: 0,
                            triggerAction: 'all',
                            selectOnFocus: true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendExtGlobalDocType',
                            allowBlank: false,
                            fieldLabel: '�������͡���',
                            store: doctypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtGlobalDocType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            id: 'sendExtGlobalDeliverTypeRef',
                            name: 'sendExtGlobalDeliverType',
                            allowBlank: false,
                            fieldLabel: '�Ըա�ù����͡���',
                            store: receiveTypeListStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtGlobalDeliverType',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        }),new Ext.form.ComboBox({
                            name: 'sendExtGlobalFormID',
                            allowBlank: false,
                            fieldLabel: 'Ẻ�����',
                            store: formListSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtGlobalFormID',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            width: 100
                        })]
                    }
                    ]
                },{
                    xtype:'fieldset',
                    id: 'sendExtGlobalFieldGroup',
                    title: '�ѹ�֡���ʹ��Թ���',
                    autoHeight:true,
                    collapsible: true,
                    collapsed: false,
                    layout: 'column',
                    items: [
                    {
                        baseCls: 'x-plain',
                        columnWidth:.6,
                        layout: 'form',
                        items:[new Ext.form.ComboBox({
                        	id: 'sendExtGlobalPurpose',
                            name: 'sendExtGlobalPurpose',
                            fieldLabel: '�ѵ�ػ��ʧ��',
                            store: purposeSarabanStore,
                            displayField:'name',
                            valueField: 'id',
                            hiddenName: 'sendExtGlobalPurpose',
                            typeAhead: false,
                            triggerAction: 'all',
                            value: 1,
                            selectOnFocus:true,
                            listWidth: 260,
                            width: 260
                        }),new Ext.form.ComboBox({
                            store: autoCompleteNameWithRole,
                            fieldLabel: 'ŧ�����',
                            displayField:'name',
                            style: autoFieldStyle,
                            typeAhead: false,
                            emptyText: 'Default',
                            loadingText: '{$lang['common']['searcing']}',
                            listWidth: 350,
                            width: 280,
                            hideTrigger: true,
                            allowBlank: true,
                            id: 'sendExtGlobalSignBy',
                            name: 'sendExtGlobalSignBy',
                            valueField: 'id',
							hiddenName: 'sendExtGlobalSignByID',
                            tpl: nameRoleLookupTpl,
                            minChars: 2,
                            shadow: false,
                            autoLoad: true,
                            mode: 'remote',
                            itemSelector: 'div.search-name-role'
                        })/*{
                            xtype:'textfield',
                            fieldLabel: 'ŧ�����',
                            name: 'sendExtGlobalSignBy',
                            width: 280
                        }*/,{
                            xtype:'textfield',
                            fieldLabel: '������͡���',
                            name: 'sendExtGlobalLocation',
                            width: 280
                        },{
                            xtype: 'textarea',
                            fieldLabel: '�����˵�',
                            name: 'sendExtGlobalRemark',
                            width: 280,
                            height: 50
                        }]
                    },{
                        baseCls: 'x-plain',
                        columnWidth:.4,
                        layout: 'form',
                        items:[new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��������͡���',
                            emptyText: 'Default',
                            name: 'sendExtGlobalDocExpireDate',
                            width: 100
                        }),{
                            xtype: 'checkbox',
                            fieldLabel: '�Դ�����',
                            name: 'sendExtGlobalFollowUp'
                        },new Ext.ux.DateTimeField ({
                            fieldLabel: '�ѹ��˹�����',
                            name: 'sendExtGlobalDocDeadline',
                            width: 100
                        })
                        ]
                    }
                    ]
                }],
                buttons: [{
                    formBind: true,
                    text: '{$lang['workitem']['send']}',
                    handler: function() {
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

                                	Ext.MessageBox.confirm('Confirm', '���͡��¹͡����¹��ҧ ?', function(btn) {
                                		if(btn == 'yes') {
	                                		Ext.MessageBox.show({
		                                        id: 'dlgReceiveInternal',
		                                          msg: '���ѧ���͡��س����ѡ����...',
		                                           progressText: '�ѹ�֡������...',
		                                          width:300,
		                                           wait:true,
		                                          waitConfig: {interval:200},
		                                           icon:'ext-mb-download'
		                                    });

		                                    Ext.Ajax.request({
		                                        url: '/{$config ['appName']}/df-action/send-external-global?isCirc=0',
		                                        method: 'POST',
		                                        timeout: {$config['sendTimeout']},
		                                        success: function(o){
		                                              Ext.MessageBox.hide();
		                                              var r = Ext.decode(o.responseText);
		                                              checkSession(r);

		                                              Ext.MessageBox.show({
		                                                title: '�������¹͡(����¹��ҧ)',
		                                                msg: '�����º��������<br/>˹ѧ����Ţ��� : '+r.docno+'<br/>' + '���ѹ���  : '+r.recvDate+',���� : '+r.recvTime,
		                                                buttons: {yes:'Ṻ�͡���' ,no:'�Դ'},
														fn: function(id,str) {
		                                                	if(id=='yes') {
		                                                    	sendExternalGlobalWindow.hide();
																//saveECMData('_pw','seg');
		                                                    	Cookies.set('docRefID',r.transID);
																viewDocumentCrossModule('viewDOC_'+r.transID,r.title,'SendExternalGlobal',r.docID,r.transID);
		    												}
		    											},
		                                                icon: Ext.MessageBox.INFO
		                                            });
		                                            Ext.getCmp('sendExternalGlobalForm').getForm().reset();
													clearExternalSendToSelections();
		                                            if(getECMData('modeGen2')==1) {
		                                                saveECMData('modeGen2',0);
		                                                sendExternalGlobalWindow.hide();
		                                            }
		                                        },
		                                        failure: function(r,o) {
		                                            Ext.MessageBox.hide();
		                                            Ext.MessageBox.show({
		                                                title: '�������¹͡(����¹��ҧ)',
		                                                msg: '�������ö����',
		                                                buttons: Ext.MessageBox.OK,
		                                                icon: Ext.MessageBox.ERROR
		                                            });
		                                        },
		                                           form: Ext.getCmp('sendExternalGlobalForm').getForm().getEl()
		                                       });
    									}
    								}
                                	);

                                }
                            }
                        });

                    }
                },{
                    formBind: true,
                    text: '{$lang['workitem']['sendCirc']}',
                    handler: function() {
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

                                Ext.MessageBox.confirm('Confirm', '���͡��¹͡����¹��ҧ ?', function(btn) {
                                	if(btn == 'yes') {
                                    Ext.MessageBox.show({
                                        id: 'dlgReceiveInternal',
                                          msg: '���ѧ���͡��س����ѡ����...',
                                           progressText: '�ѹ�֡������...',
                                          width:300,
                                           wait:true,
                                          waitConfig: {interval:200},
                                           icon:'ext-mb-download'
                                    });

                                    Ext.Ajax.request({
                                        url: '/{$config ['appName']}/df-action/send-external-global?isCirc=1',
                                        method: 'POST',
                                        timeout: {$config['sendTimeout']},
                                        success: function(o){
                                              Ext.MessageBox.hide();
                                              var r = Ext.decode(o.responseText);
                                              Ext.MessageBox.show({
                                                title: '��������¹��¹͡(����¹��ҧ)',
                                                msg: '�����º��������<br/>˹ѧ����Ţ��� : '+r.docno+'<br/>' + '���ѹ���  : '+r.recvDate+',���� : '+r.recvTime,
                                                buttons: {yes:'Ṻ�͡���' ,no:'�Դ'},
												fn: function(id,str) {
                                                	if(id=='yes') {
                                                    	sendExternalGlobalWindow.hide();
                                                    	Cookies.set('docRefID',r.transID);
														//saveECMData('_pw','seg');
														viewDocumentCrossModule('viewDOC_'+r.transID,r.title,'SendExternalGlobal',r.docID,r.transID);
    												}
    											},
                                                icon: Ext.MessageBox.INFO
                                            });
                                            Ext.getCmp('sendExternalGlobalForm').getForm().reset();
											clearExternalSendToSelections();
                                        },
                                        failure: function(r,o) {
                                            Ext.MessageBox.hide();
                                            Ext.MessageBox.show({
                                                title: '��������¹��¹͡(����¹��ҧ)',
                                                msg: '�������ö����',
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.ERROR
                                            });
                                        },
                                           form: Ext.getCmp('sendExternalGlobalForm').getForm().getEl()
                                       });
    								}
    								});
                                }

                            }
                        });

                    }
                },{
                    text: '{$lang['common']['cancel']}',
                    handler: function() {
                        sendExternalGlobalWindow.hide();
                    }
                }]
        });

        var externalGlobalSendingType = Ext.getCmp('sendExtGlobalDeliverTypeRef');
        var sendExtGlobalSelector = Ext.getCmp('sendExtGlobalSendTo');

        externalGlobalSendingType.on('select',function(c,r,i) {
            externalGlobalSendingType.suspendEvents();
            dataRecord = c.store.getAt(i);
            Cookies.set('txr',dataRecord.data.id);
            externalGlobalSendingType.resumeEvents() ;
        },externalGlobalSendingType);

        sendExtGlobalSelector.on('select',function(c,r,i) {
            if(Cookies.get('txr')==4) {
                dataRecord = c.store.getAt(i);
            }
        },sendExtGlobalSelector);

        var sendExternalGlobalWindow = new Ext.Window({
            id: 'sendExternalGlobalWindow',
            title: '{$lang['workitem']['sendDocumentToExternalGlobal']}',
            width: 700,
            modal: true,
            height: 615,
            minWidth: 700,
            minHeight: 615,
            layout: 'fit',
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: false,
            items: sendExternalGlobalForm,
            closable: false,
            keys: {
                key: Ext.EventObject.ESC,
                fn: function (){
                    sendExternalGlobalForm.getForm().reset();
                    Ext.getCmp('sendExternalGlobalWindow').hide();
                },
                scope: this
            }
        });

        Ext.getCmp('sendExtGlobalFieldGroup').on('beforeexpand',function(p,a){
            sendExternalGlobalWindow.setHeight(615);
            sendExternalGlobalWindow.center();
        });

        Ext.getCmp('sendExtGlobalFieldGroup').on('beforecollapse',function(p,a){
            sendExternalGlobalWindow.setHeight(460);
            sendExternalGlobalWindow.center();
        });

        </script>";

        return $js;
    }

    private function getExtraBookJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;

        $announceType = $store->getDataStore ( 'announceType' );
        $announceSubType = $store->getDataStore ( 'announceSubType' );
        $thisYear = $sessionMgr->getCurrentYear()+543;

        $js = "
        {$announceType}

        var resultSubTypeTpl = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">������:{desctype}</td></tr>',
                '</table>',
            '</div></tpl>'
        );

        var resultNameOnlyTpl = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">������:{desctype}</td></tr>',
                '</table>',
            '</div></tpl>'
        );

        var resultRoleOnlyTpl = new Ext.XTemplate(
            '<tpl for=\".\"><div class=\"search-item\">',
                '<table width=\"90%\">',
                    '<tr><td><b>{name}</b></td></tr>',
                    '<tr><td align=\"right\">������:{desctype}</td></tr>',
                '</table>',
            '</div></tpl>'
        );

        var autocompleteSubTypeStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/announce-sub-type'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}
            ])
        });

        var autocompleteNameOnlyStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/name-only'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}
            ])
        });

        var autocompleteRoleOnlyStore = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/auto-complete/role-only'
            }),
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
            }, [
                {name: 'name'},
                {name: 'id'},
                {name: 'typeid'},
                {name: 'desctype'}
            ])
        });

        var dateFieldOpt = new Ext.ux.DateTimeField ({
                fieldLabel: 'ŧ�ѹ���',
                id: 'extraDocDate',
                name: 'extraDocDate',
                allowBlank: false,
                readOnly: true,
                labelStyle: 'font-weight:bold;color: Red;',
                width: 100
            });

        var createExtraBookForm = new Ext.form.FormPanel({
            id: 'createExtraBookForm',
            baseCls: 'x-plain',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [{
                fieldLabel: '�Ţ���',
                id: 'extraDocNo',
                readOnly: true,
                name: 'extraDocNo',
                emptyText: 'Auto',
                width: 100
            },new Ext.form.ComboBox({
                store: autocompleteOrganizeStore,
                fieldLabel: '˹��§ҹ',
                valueField: 'id',
                displayField: 'name',
                style: autoFieldStyle,
                typeAhead: true,
                loadingText: '{$lang['common']['searcing']}',
                width: 280,
                hideTrigger: false,
                triggerAction : 'all',
                allowBlank: false,
                labelStyle: 'font-weight:bold;color: Red;',
                id: 'extraOrgName',
                name: 'extraOrgName',
                tpl: resultTpl,
                minChars: 2,
                shadow: false,
                autoLoad: true,
                mode: 'remote',
                itemSelector: 'div.search-item'
            }),{
                fieldLabel: '˹��§ҹ',
                id: 'extraOrgID',
                allowBlank: false,
                name: 'extraOrgID',
                xtype: 'hidden',
                width: 280
            },new Ext.form.LocalComboBox({
                id: 'extraDocType',
                name: 'extraDocType',
                fieldLabel: '��������ѡ',
                hiddenname: 'extraDocType',
                store: announceTypeStore,
                displayField: 'name',
                valueField: 'value',
                labelStyle: 'font-weight:bold;color: Red;',
                typeAhead: false,
                value: -1,
                mode: 'local',
                triggerAction: 'all',
                selectOnFocus: true
            }),new Ext.form.ComboBox({
                store: autocompleteSubTypeStore,
                name: 'extraDocSubType',
                fieldLabel: '���ͤ����/��С��/����',
                labelStyle: 'font-weight:bold;color: Red;',
                style: autoFieldStyle,
                allowBlank: false,
                emptyText: 'Search or Create new type...',
                minChars: 2,
                displayField:'name',
                typeAhead: false,
                loadingText: '{$lang['common']['searcing']}',
                width: 280,
                pageSize:10,
                hideTrigger: true,
                tpl: resultSubTypeTpl,
                itemSelector: 'div.search-item'
            }),{
                fieldLabel: '����ͧ',
                allowBlank: false,
                id: 'extraDocTitle',
                name: 'extraDocTitle',
                xtype: 'textarea',
                labelStyle: 'font-weight:bold;color: Red;',
                height: '50',
                width: '250'
            },{
                fieldLabel: '�.�.',
                id: 'extraDocYear',
                allowBlank: true,
                hideMode: 'offsets',

                hideParent: true,
                hidden: true,
                name: 'extraDocYear',
                value: '{$thisYear}',
                width: 50
            },{
                fieldLabel: '��������´',
                allowBlank: true,
                name: 'extraDocDesc',
                xtype: 'textarea',
                height: '100',
                width: '250'
            },dateFieldOpt,new Ext.form.ComboBox({
                store: autocompleteNameOnlyStore,
                name: 'extraDocSignUser',
                id: 'extraDocSignUserID',
                fieldLabel: 'ŧ�����',
                hiddenName: 'extraDocSignUser',
                style: autoFieldStyle,
                allowBlank: false,
                minChars: 2,
                displayField:'name',
                typeAhead: false,
                loadingText: '{$lang['common']['searcing']}',
                labelStyle: 'font-weight:bold;color: Red;',
                width: 280,
                pageSize:10,
                hideTrigger:true,
                tpl: resultNameOnlyTpl,
                itemSelector: 'div.search-item'
            }),new Ext.form.ComboBox({
                store: autocompleteRoleOnlyStore,
                name: 'extraDocSignRole',
                id: 'extraDocSignRoleID',
                fieldLabel: '���˹�',
                hiddenName: 'extraDocSignRole',
                labelStyle: 'font-weight:bold;color: Red;',
                style: autoFieldStyle,
                allowBlank: false,
                minChars: 2,
                displayField:'name',
                typeAhead: false,
                loadingText: '{$lang['common']['searcing']}',
                width: 280,
                pageSize:10,
                hideTrigger:true,
                tpl: resultRoleOnlyTpl,
                itemSelector: 'div.search-item'
            }),{
                id: 'extraDocTypeHidden',
                xtype:'hidden',
                allowBlank: false,
                name: 'extraDocTypeHidden'
            },{
                id: 'extraDocSignUserHidden',
                xtype:'hidden',
                allowBlank: false,
                name: 'extraDocSignUserHidden'
            },{
                id: 'extraDocSignRoleHidden',
                xtype:'hidden',
                allowBlank: false,
                name: 'extraDocSignRoleHidden'
            },{
                fieldLabel: '�����˵�',
                allowBlank: true,
                name: 'extraDocRemark',
                xtype: 'textarea',
                height: '50',
                width: '250'
            }],
            buttons: [{
                text: '�ѹ�֡',
                formBind: true,
                id: 'saveExtraDoc',
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {

                    //if(Ext.getCmp('extraDocSignRoleHidden').getValue() == '' ||
                    createExtraBookWindow.hide();

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
                            var r = Ext.decode(o.responseText);
                            Ext.MessageBox.hide();
                            Ext.MessageBox.show({
                                msg: '�ѹ�֡��¡�����º����'+'<br/>�Ţ���:'+r.announceNo+'<br/>������:'+r.announceTypeName,
                                //buttons: Ext.MessageBox.OK,
                                buttons: {
                                	yes:'Ṻ�͡���' ,no:'��ŧ'
    							},
								fn: function(id,str) {
									if(id=='yes') {
                                    	createExtraBookWindow.hide();
										//saveECMData('_pw','cmd');
										Cookies.set('docRefID',r.announceID);
										viewAnnounce('viewDOC_'+r.announceID,r.title,'CMD',+r.announceID,+r.announceID);
    								}
    							},
                                width:300,
                                icon: Ext.MessageBox.INFO
                            });

                        },
                        failure: function(o) {
                        },
                        form: Ext.getCmp('createExtraBookForm').getForm().getEl()
                    });
                }
            },{
                text: '¡��ԡ',
                iconCls: 'rejectIcon',
                handler: function() {
                    createExtraBookWindow.hide();
                }
            }]
        });";

        $js .= "var createExtraBookWindow = new Ext.Window({
            id: 'createExtraBookWindow',
            title: '�����/��С��/˹ѧ�������',
            width: 500,
            height: 520,
            minWidth: 500,
            minHeight: 520,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: createExtraBookForm,
            closable: false
        });




        Ext.getCmp('extraOrgName').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            Ext.getCmp('extraOrgID').setValue(dataRecord.data.id);
        },this);

        Ext.getCmp('extraDocType').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            if(dataRecord.data.value == -1) {
                Ext.getCmp('saveExtraDoc').disable();
            } else {
                Ext.getCmp('saveExtraDoc').enable();
            }
            if(dataRecord.data.value == 1) {
                Ext.getCmp('extraDocYear').show();
                Ext.getCmp('extraDocYear').hideLabel = false;
            } else {
                Ext.getCmp('extraDocYear').hide();
                Ext.getCmp('extraDocYear').hideLabel = true;
            }
            Ext.getCmp('extraDocTypeHidden').setValue(dataRecord.data.id);
            Cookies.set('edt',dataRecord.data.value);
        },this);

         Ext.getCmp('extraDocSignUserID').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            Ext.getCmp('extraDocSignUserHidden').setValue(dataRecord.data.id);
            Ext.getCmp('extraDocSignRoleID').setValue('Loading...');
            Ext.Ajax.request({
                url: '/{$config ['appName']}/auto-complete/load-user-by-id',
                method: 'POST',
                success: function(o){
                    Ext.MessageBox.hide();
                    var r = Ext.decode(o.responseText);
                    Ext.getCmp('extraDocSignRoleID').setValue(r.roleName);
                    Ext.getCmp('extraDocSignRoleHidden').setValue(r.roleID);
                },
                params: {
                    id: dataRecord.data.id
                }
            });
        },this);

        Ext.getCmp('extraDocSignRoleID').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            //alert(dataRecord.data.id);
            Ext.getCmp('extraDocSignRoleHidden').setValue(dataRecord.data.id);
        },this);
        ";

        return $js;
    }

    private function getReserveBookJS() {
        global $config;
        global $lang;
        global $store;
        global $sessionMgr;

        $reserverEnabledTypeStore = $store->getDataStore ( 'reserveEnableType' );
        $js = "

        {$reserverEnabledTypeStore}

        var dateReserved = new Ext.ux.DateTimeField ({
                fieldLabel: 'ŧ�ѹ���',
                id: 'dateReserved',
                name: 'dateReserved',
                allowBlank: false,
                emptyText: 'Default',
                width: 100
            });

        var reserveBooknoForm = new Ext.form.FormPanel({
            id: 'reserveBooknoForm',
            baseCls: 'x-plain',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,

            items: [new Ext.form.LocalComboBox({
                id: 'reserveRegBookType',
                allowBlank: false,
                fieldLabel: '����������¹',
                hiddenId: 'hiddenReserveRegBookType',
                hiddenName: 'hiddenReserveRegBookType',
                store: reserveEnableTypeStore,
                displayField: 'name',
                valueField: 'value',
                typeAhead: false,
               	emptyText: 'Please Select...',
                mode: 'local',
                triggerAction: 'all',
                selectOnFocus: true
            }),{
                fieldLabel: '�ӹǹ�Ţ���ͧ',
                id: 'reserveAmountNo',
                allowBlank: false,
                name: 'reserveAmountNo',
                width: 100
            },dateReserved,new Ext.form.ComboBox({
                store: autocompleteNameOnlyStore,
                name: 'extraDocSignUser',
                id: 'extraDocSignUserID',
                fieldLabel: '���ͧ',
                hiddenName: 'extraDocSignUser',
                style: autoFieldStyle,
                allowBlank: false,
                emptyText: 'Default',
                minChars: 2,
                displayField:'name',
                valueField: 'id',
                typeAhead: false,
                loadingText: '{$lang['common']['searcing']}',
                width: 200,
                pageSize:10,
                hideTrigger:true,
                tpl: resultNameOnlyTpl,
                itemSelector: 'div.search-item'
            }),{
                fieldLabel: '�����˵�',
                allowBlank: true,
                name: 'extraDocRemark',
                xtype: 'textarea',
                height: '50',
                width: '250'
            }],
            buttons: [{
                text: '�ѹ�֡',
                formBind: true,
                id: 'saveExtraDoc',
                iconCls: 'saveIcon',
                disabled: true,
                handler: function() {

                    reserveBooknoWindow.hide();
                    Ext.MessageBox.show({
                        id: 'dlgSaveData',
                           msg: 'Processing...',
                           progressText: 'Saving...',
                           width:300,
                           wait:true,
                           waitConfig: {interval:200},
                           icon:'ext-mb-download'
                       });
                       Ext.Ajax.request({
                        url: '/{$config ['appName']}/df-action/reserve-request',
                        method: 'POST',
                        success: function (o) {
                        	var r = Ext.decode(o.responseText);
                           	Ext.MessageBox.hide();
                           	Ext.MessageBox.show({
                           		msg: '�ͧ�Ţ����<br/>���Ţ��� '+r.bookNo,
                           		width:300,
                           		icon:'ext-mb-download'
                       		});
                        },
                        failure: function(o) {
                        },
                        form: Ext.getCmp('reserveBooknoForm').getForm().getEl()
                    });
                }
            },{
                text: '¡��ԡ',
                iconCls: 'rejectIcon',
                handler: function() {
                    reserveBooknoWindow.hide();
                }
            }]
        });";

        $js .= "var reserveBooknoWindow = new Ext.Window({
            id: 'reserveBooknoWindow',
            title: '�ͧ�Ţ����¹',
            width: 460,
            height: 250,
            minWidth: 460,
            minHeight: 250,
            layout: 'fit',
            plain:true,
            modal: true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            items: reserveBooknoForm,
            closable: false
        });

        Ext.getCmp('extraDocType').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            if(dataRecord.data.value == -1) {
                Ext.getCmp('saveExtraDoc').disable();
            } else {
                Ext.getCmp('saveExtraDoc').enable();
            }
            if(dataRecord.data.value == 1) {
                Ext.getCmp('extraDocYear').show();
                Ext.getCmp('extraDocYear').hideLabel = false;
            } else {
                Ext.getCmp('extraDocYear').hide();
                Ext.getCmp('extraDocYear').hideLabel = true;
            }
            Ext.getCmp('extraDocTypeHidden').setValue(dataRecord.data.id);
            Cookies.set('edt',dataRecord.data.value);
        },this);

         Ext.getCmp('extraDocSignUserID').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            //alert(dataRecord.data.id);
            Ext.getCmp('extraDocSignUserHidden').setValue(dataRecord.data.id);
        },this);

        Ext.getCmp('extraDocSignRoleID').on('select',function(c,record,i) {
            dataRecord = c.store.getAt(i);
            //alert(dataRecord.data.id);
            Ext.getCmp('extraDocSignRoleHidden').setValue(dataRecord.data.id);
        },this);
        ";

        return $js;
    }

    private function sentInternalListWindowJS() {

        global $config;
        global $lang;

        $js = "<script type=\"text/JavaScript\">



        var tempSendStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, ReceiverRecordDataDef)});
        var tempCCStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, ReceiverRecordDataDef)});

        var sendInternalListWindow = new Ext.Window({
            id: 'sendInternalListWindow',
            title: '{$lang['workitem']['selectInternalReceiver']}',
            width: 300,
            height: 355,
            minWidth: 300,
            minHeight: 355,
            layout: 'fit',
            labelWidth: 75,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: true,
            //items: sendExternalForm,
            layout: 'form',
            items: [
               new Ext.form.ComboBox({
                                store: autocompleteContactListStore,
                                fieldLabel: '�ѹ�֡��ª���',
                                displayField:'name',
                                style: autoFieldStyle,
                                typeAhead: false,
                                emptyText: 'Contact List...',
                                tabIndex: 1,
                                loadingText: '{$lang['common']['searcing']}',
                                width: 180,
                                //pageSize:10,
                                hideTrigger:true,
                                id: 'contactListAutoComplete',
                                name: 'contactListAutoComplete',
                                tpl: resultContactList,
                                //lazyInit: true,
                                //lazyRender: true,
                                minChars: 2,
                                shadow: false,
                                autoLoad: true,
                                mode: 'remote',
                                itemSelector: 'div.search-contact'

                   }),{
                    layout: 'column',
                    labelWidth: 75,
                    baseCls: 'x-plain',
                    items:[
                        {
                            columnWidth: .95,
                            labelWidth: 75,
                            layout: 'form',
                            baseCls: 'x-plain',
                            items:[new Ext.form.ComboBox({
                                store: autocompleteReceiverTextStore,
                                fieldLabel: '�֧',
                                displayField:'name',
                                typeAhead: false,
                                emptyText: 'Default',
                                style: autoFieldStyle,
                                tabIndex: 1,
                                loadingText: '{$lang['common']['searcing']}',
                                width: 180,
                                //pageSize:10,
                                hideTrigger:true,
                                id: 'SendToSelector',
                                name: 'SendToSelector',
                                tpl: resultTpl,
                                //lazyInit: true,
                                //lazyRender: true,
                                minChars: 2,
                                shadow: false,
                                autoLoad: true,
                                mode: 'remote',
                                itemSelector: 'div.search-item'
                            }),new Ext.grid.GridPanel({
                                id: 'gridSendTo',
                                tbar: new Ext.Toolbar({
                                    id: 'SendToToolbar',
                                    height: 25
                                }),
                                store: tempSendStore,
                                enableDragDrop: true,
                                enableDrop: true,
                                enableDrag: true,
                                ddGroup : 'TreeDD',
                                autoExpandMax: true,
                                columns: [
                                {id: 'id', header: 'Name', width: 120, sortable: false, dataIndex: 'name'},
                                {header: 'Description', width: 0, sortable: false, dataIndex: 'description'}
                                //{header: 'Level', width: 0, sortable: false, dataIndex: 'level'},
                                //{header: 'Status', width: 0, sortable: false, dataIndex: 'status'}
                                ],
                                viewConfig: {
                                    forceFit: true
                                },
                                sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
                                width: 260,
                                height: 200,
                                frame: false,
                                iconCls:'icon-grid'
                            })]
                        }]
                }],
            buttons: [{
                id: 'btnConfirm',
                text: 'Confirm',
                handler: function() {
                    var sendStringData = '';
                    var sendNameData = '';
                    var CCStringData = '';
                    var CCNameData = '';
                    for(i=0;i<tempSendStore.getCount();i++) {
                        dataTempSend = tempSendStore.getAt(i);
                        if(sendStringData == '') {
                            sendStringData = sendStringData + dataTempSend.data.typeid+'_'+dataTempSend.data.dataid;
                        } else {
                           sendStringData = sendStringData + ' , '+ dataTempSend.data.typeid+'_'+dataTempSend.data.dataid;
                        }
                        if(sendNameData == '') {
                            sendNameData = sendNameData + dataTempSend.data.name;
                        } else {
                           sendNameData = sendNameData + ' , '+ dataTempSend.data.name;
                        }
                    }

                    for(i=0;i<tempCCStore.getCount();i++) {
                        dataTempCC = tempCCStore.getAt(i);
                        if(CCStringData == '') {
                            CCStringData = CCStringData + dataTempCC.data.typeid+'_'+dataTempCC.data.dataid;
                        } else {
                           CCStringData = CCStringData + ' , '+ dataTempCC.data.typeid+'_'+dataTempCC.data.dataid;
                        }
                        if(CCNameData == '') {
                            CCNameData = CCNameData + dataTempCC.data.name;
                        } else {
                           CCNameData = CCNameData + ' , '+ dataTempCC.data.name;
                        }
                    }


                   // var reveiverListField = Cookies.get('reveiverListField');
                   // var receiverListHiddenField = Cookies.get('receiverListHiddenField');

                    //alert(Cookies.get('rc'));
                    //alert(Cookies.get('rcH'));
                    //Cookies.set('rcvr','forwardTransTo');
                        //Cookies.set('rcvrH','forwardTransToHidden');
                        Ext.getCmp(Cookies.get('rc')).setValue('');
                        Ext.getCmp(Cookies.get('rcH')).setValue('');
                    if(sendNameData!='') {
                        if(CCNameData != '') {
                            Ext.getCmp(Cookies.get('rc')).setValue( sendNameData +','+  CCNameData);
                        } else {
                            Ext.getCmp(Cookies.get('rc')).setValue( sendNameData);
                        }
                    } else {
                        if(CCNameData != '') {
                            Ext.getCmp(Cookies.get('rc')).setValue( CCNameData);
                        }
                    }

                    if(sendStringData!='') {
                        if(CCStringData != '') {
                            Ext.getCmp(Cookies.get('rcH')).setValue(  sendStringData +','+  CCStringData);
                        } else {
                            Ext.getCmp(Cookies.get('rcH')).setValue(  sendStringData);
                        }
                    } else {
                        if(CCStringData != '') {
                            Ext.getCmp(Cookies.get('rcH')).setValue(  CCStringData);
                        }
                    }

                    //alert(CCNameData);
                    //alert(CCStringData);
                    sendInternalListWindow.hide();
                }
            },{
                id: 'btnSaveContactList',
                text: 'Save List',
                handler: function() {
                    //alert(Ext.util.JSON.encode(tempSendStore.data));
                    //alert(Ext.util.JSON.encode(tempCCStore.data));
                    Ext.MessageBox.prompt('�ѹ�֡�� Contact List', '���� Contact List', saveContactList);

                }
            },{
                id: 'btnHideSendWindow',
                text: 'Cancel',
                handler: function() {
                    sendInternalListWindow.hide();
                }
            }],
            closable: false
        });

        sendInternalListWindow.on('show',function(t) {
            Ext.getCmp('SendToSelector').focus('', 10);
			Ext.getCmp('gridSendTo').store.removeAll();
        },this);

        function saveContactList(btn, text) {
            if(btn == 'ok') {
                Ext.MessageBox.show({
                    msg: '���ѧ�ѹ�֡��ª��� ���ѡ����...',
                    progressText: '�ѹ�֡������...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/contact-list/save',
                    method: 'POST',
                    success: function(o){
                        Ext.MessageBox.hide();
                        var r = Ext.decode(o.responseText);
                        Ext.MessageBox.show({
                            title: '��úѹ�֡��ª���',
                            msg: '�ѹ�֡���º��������',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.INFO
                        });
                    },
                    params: {
                        listName: text,
                        sendTo: storeToJSON(tempSendStore),
                        sendCC: storeToJSON(tempCCStore)
                    }
                });
            }
        }

        function clearInternalSendToSelections() {
            Ext.getCmp('SendToSelector').clearValue();
            tempSendStore.removeAll();
        }

		function clearExternalSendToSelections() {
            Ext.getCmp('SendExternalToSelector').clearValue();
            tempSendExternalStore.removeAll();
        }

        sendInternalListWindow.show();
        sendInternalListWindow.hide();

        Ext.getCmp('gridSendTo').on('rowclick',function() {
            Ext.getCmp('btnDeleteSendTo').enable();
        },this);

        var SendToToolbar = Ext.getCmp('SendToToolbar');
        //var CCToToolbar = Ext.getCmp('CCToToolbar');

        var SendToSelector = Ext.getCmp('SendToSelector');
        //var CCToSelector = Ext.getCmp('CCToSelector');

        SendToSelector.on('select',function(c,r,i) {
            dataRecord = c.store.getAt(i);
            var rec = new ReceiverRecordDataDef({
                        dataid: dataRecord.data.id,
                        name: dataRecord.data.name,
                        description: dataRecord.data.desctype,
                        typeid: dataRecord.data.typeid

            });
            tempSendStore.add(rec);
            SendToSelector.emptyText = '';
            SendToSelector.reset();
        },this);

        /*
         CCToSelector.on('select',function(c,r,i) {
            dataRecord = c.store.getAt(i);
            var rec = new ReceiverRecordDataDef({
                        dataid: dataRecord.data.id,
                        name: dataRecord.data.name,
                        description: dataRecord.data.desctype,
                        typeid: dataRecord.data.typeid

            });
            tempCCStore.add(rec);
            CCToSelector.emptyText = '';
            CCToSelector.reset();
        },this);
        */

        var contactListAutoComplete = Ext.getCmp('contactListAutoComplete');
        contactListAutoComplete.on('select',function(c,r,i) {
            dataRecord = c.store.getAt(i);
            //alert(dataRecord.data.tolist.length);
            for (var i in dataRecord.data.tolist) {
                if (typeof dataRecord.data.tolist[i] == 'object')
                {

                    tempSendStore.add(new ReceiverRecordDataDef(dataRecord.data.tolist[i]));

                    //tempSendStore.insert(i, new ReceiverRecordDataDef(dataRecord.data.tolist[i]));
                }
            }

            //for (var i in dataRecord.data.cclist) {
           //     if (typeof dataRecord.data.cclist[i] == 'object')
           //     {
           //         //tempCCStore.insert(i, new ReceiverRecordDataDef(dataRecord.data.cclist[i]));
           //     }
           // }
           // Ext.getCmp('gridSendTo').store = tempSendStore;
            //Ext.getCmp('gridSendTo').store.reload();
            //tempSendStore.data =  dataRecord.data.tolist;
            //tempCCStore.data =  dataRecord.data.cclist;
            //alert(dataRecord.data.tolist);
        },this);

        SendToToolbar.add({
            id: 'btnClearSendTo',
            text:'Clear',
            iconCls: 'bmenu',
            handler: function() {
                Ext.getCmp('btnDeleteSendTo').disable();
                tempSendStore.removeAll();
            }
        },{
            id: 'btnDeleteSendTo',
            text:'Delete',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
                tempSendStore.remove(tempSendStore.getById(Ext.getCmp('gridSendTo').getSelectionModel().getSelected().id));
                Ext.getCmp('btnDeleteSendTo').disable();
            }
        });

        /*
        CCToToolbar.add({
            id: 'btnClearCCTo',
            text:'Clear',
            iconCls: 'bmenu',
            handler: function() {
                tempCCStore.removeAll();
                //accountAddForm.getForm().reset();
                //accountAddWindow.show();
            }
        },{
            id: 'btnDeleteCCTo',
            text:'Delete',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
                //setPasswordForm.getForm().reset();
                //setPasswordWindow.show();
            }
        });
        */
        </script>";
        return $js;
    }

    private function sentExternalListWindowJS() {

        global $config;
        global $lang;

        $js = "<script type=\"text/JavaScript\">

        var tempSendExternalStore = new Ext.data.Store({reader: new Ext.data.JsonReader({}, ReceiverRecordDataDef)});

        var sendExternalListWindow = new Ext.Window({
            id: 'sendExternalListWindow',
            title: '{$lang['workitem']['selectExternalReceiver']}',
            width: 400,
            height: 355,
            minWidth: 400,
            minHeight: 355,
            layout: 'fit',
            labelWidth: 75,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            resizable: true,
            layout: 'form',
            items: [
                new Ext.form.ComboBox({
                    store: autocompleteExternalContactListStore,
                    fieldLabel: '�ѹ�֡��ª���',
                    displayField:'name',
                    typeAhead: false,
                    emptyText: 'Contact List...',
                    tabIndex: 1,
                    loadingText: '{$lang['common']['searcing']}',
                    width: 180,
                    hideTrigger:true,
                    id: 'contactListExternalAutoComplete',
                    name: 'contactListExternalAutoComplete',
                    tpl: resultContactList,
                    minChars: 2,
                    shadow: false,
                    autoLoad: true,
                    mode: 'remote',
                    width: 250,
                    itemSelector: 'div.search-contact'

               }),new Ext.form.ComboBox({
                    store: autocompleteReceiverExternalAllStore,
                    fieldLabel: '�֧',
                    displayField:'name',
                    typeAhead: false,
                    emptyText: 'Default',
                    tabIndex: 1,
                    loadingText: '{$lang['common']['searcing']}',
                    width: 180,
                    hideTrigger:true,
                    id: 'SendExternalToSelector',
                    name: 'SendExternalToSelector',
                    tpl: resultTpl,
                    minChars: 2,
                    shadow: false,
                    autoLoad: true,
                    mode: 'remote',
                    width: 250,
                    itemSelector: 'div.search-item'

                }),new Ext.grid.GridPanel({
                    id: 'gridSendExternalTo',
                    tbar: new Ext.Toolbar({
                        id: 'SendExternalToToolbar',
                        height: 25
                    }),
                    store: tempSendExternalStore,
                    //enableDragDrop: true,
                    //enableDrop: true,
                    //enableDrag: true,
                    ddGroup : 'TreeDD',
                    autoExpandMax: true,
                    columns: [
                    {id: 'id', header: 'Name', width: 120, sortable: false, dataIndex: 'name'},
                    {header: 'Description', width: 0, sortable: false, dataIndex: 'description'}
                    ],
                    viewConfig: {
                        forceFit: true
                    },
                    sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
                    width: 375,
                    height: 200,
                    frame: false,
                    iconCls:'icon-grid'
                })],
            buttons: [{
                id: 'btnConfirmExternal',
                text: 'Confirm',
                handler: function() {
                    var sendExternalStringData = '';
                    var sendExternalNameData = '';
                    for(i=0;i<tempSendExternalStore.getCount();i++) {
                        dataTempSend = tempSendExternalStore.getAt(i);
                        if(sendExternalStringData == '') {
                            sendExternalStringData = sendExternalStringData + dataTempSend.data.typeid+'_'+dataTempSend.data.dataid;
                        } else {
                           sendExternalStringData = sendExternalStringData + ', '+ dataTempSend.data.typeid+'_'+dataTempSend.data.dataid;
                        }
                        if(sendExternalNameData == '') {
                            sendExternalNameData = sendExternalNameData + dataTempSend.data.name;
                        } else {
                           sendExternalNameData = sendExternalNameData + ', '+ dataTempSend.data.name;
                        }
                    }

                    Ext.getCmp(Cookies.get('rc')).setValue( sendExternalNameData);

                    Ext.getCmp(Cookies.get('rcH')).setValue( sendExternalStringData);

                    sendExternalListWindow.hide();
                }
            },{
                id: 'btnSaveExternalContactList',
                text: 'Save List',
                disabled: false,
                handler: function() {
                    Ext.MessageBox.prompt('�ѹ�֡�� Contact List', '���� Contact List', saveExternalContactList);

                }
            },{
                id: 'btnHideSendExternalWindow',
                text: 'Cancel',
                handler: function() {
                    sendExternalListWindow.hide();
                }
            }],
            closable: false
        });


        function saveExternalContactList(btn, text) {
            if(btn == 'ok') {
                Ext.MessageBox.show({
                    msg: '���ѧ�ѹ�֡��ª��� ���ѡ����...',
                    progressText: '�ѹ�֡������...',
                    width:300,
                    wait:true,
                    waitConfig: {interval:200},
                    icon:'ext-mb-download'
                });
                Ext.Ajax.request({
                    url: '/{$config ['appName']}/contact-list/save-external',
                    method: 'POST',
                    success: function(o){
                        Ext.MessageBox.hide();
                        var r = Ext.decode(o.responseText);
                        Ext.MessageBox.show({
                            title: '��úѹ�֡��ª���',
                            msg: '�ѹ�֡���º��������',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.INFO
                        });
                    },
                    params: {
                        listName: text,
                        sendTo: storeToJSON(tempSendExternalStore)
                    }
                });
            }
        }


        sendExternalListWindow.show();
        sendExternalListWindow.hide();


        var SendExternalToToolbar = Ext.getCmp('SendExternalToToolbar');

        var SendExternalToSelector = Ext.getCmp('SendExternalToSelector');


        SendExternalToSelector.on('select',function(c,r,i) {
            dataRecord = c.store.getAt(i);
            var rec = new ReceiverRecordDataDef({
                        dataid: dataRecord.data.id,
                        name: dataRecord.data.name,
                        description: dataRecord.data.desctype,
                        typeid: dataRecord.data.typeid

            });
            tempSendExternalStore.add(rec);
            SendExternalToSelector.emptyText = '';
            SendExternalToSelector.reset();
        },this);

        var contactListExternalAutoComplete = Ext.getCmp('contactListExternalAutoComplete');
        contactListExternalAutoComplete.on('select',function(c,r,i) {
            dataRecord = c.store.getAt(i);
            for (var i in dataRecord.data.tolist) {
                if (typeof dataRecord.data.tolist[i] == 'object')
                {
                    tempSendExternalStore.insert(i, new ReceiverRecordDataDef(dataRecord.data.tolist[i]));
                }
            }
        },this);

        SendExternalToToolbar.add({
            id: 'btnAddSendExternalTo',
            text:'Add',
            iconCls: 'bmenu',
            handler: function() {
                var rec = new ReceiverRecordDataDef({
                    dataid: 999999999,
                    name: SendExternalToSelector.getRawValue(),
                    description: 'External',
                    typeid: '999'
                });
                tempSendExternalStore.add(rec);
                SendExternalToSelector.clearValue();
            }
        },{
            id: 'btnClearSendExternalTo',
            text:'Clear',
            iconCls: 'bmenu',
            handler: function() {
                tempSendExternalStore.removeAll();
            }
        },{
            id: 'btnDeleteSendExternalTo',
            text:'Delete',
            iconCls: 'bmenu',
            disabled: true,
            handler: function(e) {
            }
        });

		/* gen form announce */
		var createAnnounceForm = new Ext.form.FormPanel({
            id: 'createAnnounceForm',
            baseCls: 'x-plain',
            labelWidth: 150,
            defaultType: 'textfield',
            monitorValid: true,
            items: [{
                fieldLabel: '�Ţ���',
                id: 'announceNoA',
                readOnly: true,
                name: 'announceNoA',
//				value: '{$announce->f_announce_no}/{$announce->f_year}',
                width: 100
            },{
                fieldLabel: '˹��§ҹ',
                id: 'extraOrgNameA',
                allowBlank: false,
                name: 'extraOrgNameA',
//				value: '{$announce->f_announce_org_name}',
                width: 280
            },{
                fieldLabel: '˹��§ҹ',
                id: 'extraOrgIDA',
                allowBlank: false,
                name: 'extraOrgIDA',
//				value: '{$announce->f_announce_org_id}',
                xtype: 'hidden',
                width: 280
            },{
                id: 'extraDocTypeA',
                name: 'extraDocTypeA',
                fieldLabel: '��������ѡ',
                hiddenname: 'extraDocType',
//                store: announceTypeStore,
//                displayField: 'name',
//                valueField: 'value',
//                labelStyle: 'font-weight:bold;color: Red;',
//                typeAhead: false,
//                value: -1,
//                mode: 'local',
//                triggerAction: 'all',
//                selectOnFocus: true,
//				value: '{$announceTypeName}',
				readOnly: true
            },{
				id: 'extraDocSubTypeA',
                name: 'extraDocSubTypeA',
                fieldLabel: '���ͤ����/��С��/����',
//                labelStyle: 'font-weight:bold;color: Red;',
//                style: autoFieldStyle,
                allowBlank: false,
				readOnly : true,
                loadingText: '{$lang['common']['searcing']}',
//				value:'{$announceCat->f_name}',
                width: 300
            },{
                fieldLabel: '����ͧ',
                allowBlank: false,
                id: 'extraDocTitleA',
                name: 'extraDocTitleA',
                xtype: 'textarea',
                labelStyle: 'font-weight:bold;color: Red;',
//				value: '{$announceTitle}',
                height: '50',
                width: '250'


            },{
                fieldLabel: '�.�.',
                id: 'extraDocYearA',
                xtype: 'hidden',
                name: 'extraDocYearA',
//                value: '{$announce->f_year}',
                width: 50
            },{
                fieldLabel: '��������´',
                allowBlank: true,
				id: 'extraDocDescA',
                name: 'extraDocDescA',
//				value:'{$announceDetail}',
                xtype: 'textarea',
                height: '100',
                width: '250'
            },
				new Ext.ux.DateTimeField ({
					fieldLabel: 'ŧ�ѹ���',
					id: 'extraDocDateA',
					name: 'extraDocDateA',
//					value: '{$announce->f_announce_date}',
					allowBlank: false,
					readOnly: true,
					labelStyle: 'font-weight:bold;color: Red;',
					width: 100

			}),
				new Ext.form.ComboBox({
                store: autocompleteNameOnlyStore,
                name: 'extraDocSignUserA',
                id: 'extraDocSignUserIDA',
                fieldLabel: 'ŧ�����',
                hiddenName: 'extraDocSignUserA',
                style: autoFieldStyle,
                allowBlank: false,
                minChars: 2,
                displayField:'name',
                typeAhead: false,
                loadingText: '{$lang['common']['searcing']}',
                labelStyle: 'font-weight:bold;color: Red;',
                width: 280,
                pageSize:10,
                hideTrigger:true,
                tpl: resultNameOnlyTpl,
//				value:'{$signUserName}',
                itemSelector: 'div.search-item'

            }),new Ext.form.ComboBox({
                store: autocompleteRoleOnlyStore,
                name: 'extraDocSignRoleA',
                id: 'extraDocSignRoleIDA',
                fieldLabel: '���˹�',
                hiddenName: 'extraDocSignRoleA',
                labelStyle: 'font-weight:bold;color: Red;',
                style: autoFieldStyle,
                allowBlank: false,
                minChars: 2,
                displayField:'name',
                typeAhead: false,
                loadingText: '{$lang['common']['searcing']}',
                width: 280,
                pageSize:10,
                hideTrigger:true,
                tpl: resultRoleOnlyTpl,
//				value:'{$signRoleName}',
                itemSelector: 'div.search-item'

            }),{
                fieldLabel: '�����˵�',
                allowBlank: true,
				id: 'extraDocRemarkA',
                name: 'extraDocRemarkA',
				value: '{$announce->f_remark}',
                xtype: 'textarea',
                height: '50',
                width: '250'
            },{// no use
                id: 'extraDocTypeAHidden',
                xtype:'hidden',
                allowBlank: false,
//				value:{$announce->f_announce_category},
                name: 'extraDocTypeAHidden'
            },{
                id: 'extraDocSignUserAHidden',
                xtype:'hidden',
                allowBlank: false,
//				value:'{$announce->f_sign_uid}',
                name: 'extraDocSignUserAHidden'
            },{
                id: 'extraDocSignRoleAHidden',
                xtype:'hidden',
                allowBlank: false,
//				value:'{$announce->f_sign_role}',
                name: 'extraDocSignRoleAHidden'
            },{
                id: 'instanceIDA',
                name: 'instanceIDA',
//				value: '{$announce->f_announce_id}',
                xtype: 'hidden'

			}],
            buttons: [{
                text: '�ѹ�֡',
                formBind: true,
                id: 'saveExtraDocA',
                iconCls: 'saveIcon',
                disabled: true,
                handler: function(e) {

                    //if(Ext.getCmp('extraDocSignRoleHidden').getValue() == '' ||
					announceFormWindow.hide();
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
									msg: '���ѧ����͡���...',
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
												responseMsg = '�ѹ�֡���º��������';
											} else {
												responseMsg = '�ѹ�֡�Դ��Ҵ';
											}
											if(r.refresh == 1) {
												DMSTree.getNodeById(r.id).parentNode.reload();
											}
											Ext.MessageBox.show({
												title: '����͡���',
												msg: '������º��������',
												buttons: Ext.MessageBox.OK,
												   icon: Ext.MessageBox.INFO
											});
									},
									form: Ext.getCmp('createAnnounceForm').getForm().getEl(),
									params: {
//										formIDA: {$formID}
//										,instanceIDA: {$docID}
									}
								});
							}
						}
					});

                }
            },{
                text: '¡��ԡ',
                iconCls: 'rejectIcon',
                handler: function() {
                    announceFormWindow.hide();
                }
            }]
        });

		 var announceFormWindow = new Ext.Window({
			id: 'announceFormWindow',
			title: '�����/��С��/˹ѧ�������',
			width: 500,
			height: 520,
			minWidth: 500,
			minHeight: 520,
			layout: 'fit',
			plain:true,
			modal: true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			items: createAnnounceForm,
			closable: false
		});

        /* End Anncounce Form*/

        </script>";
        return $js;
    }
}
