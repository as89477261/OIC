<?php
global $config;
global $lang;

$lang ['ECMAppName'] = 'Benefit&copy; ECM&trade; : E-Office Solution';
$lang ['ECMControlCenterAppName'] = 'Benefit&copy; ECM&trade; : Control Center';
$lang ['ECMErrorMessage'] = 'Problem Occur';
$lang ['ECMError'] ['ControllerNotExists'] = 'Module not found';

$lang ['login'] [1] = "Password incorrect.";
$lang ['login'] [2] = "Concurrent exists @";
$lang ['login'] [3] = "No roles mapping";
$lang ['login'] [4] = "User not found";
$lang ['login'] [5] = "Account is Lock";
$lang ['login'] [6] = "Login attempt exceed limits ,account locked.Please contact System administrator.";
$lang ['session'] ['expired'] = "Session Expired.Please Login again<a href=\"{$config['appName']}/Login\">here</a>";

$lang ['label'] ['neverchange'] = "Never";
$lang ['label'] ['orgUnit'] = 'Org.';
$lang ['label'] ['intBookno'] = 'Int. Book No.';
$lang ['label'] ['extBookno'] = 'Ext. Book No.';
$lang ['label'] ['globalRecvExt'] = 'Global Recv Ext. Reg.No.';
$lang ['label'] ['globalSendExt'] = 'Global Send Ext. Reg.No.';
$lang ['label'] ['recvInt'] = 'Receive Int. Reg.No.';
$lang ['label'] ['sendInt'] = 'Send Int. Reg.No.';
$lang ['label'] ['recvExt'] = 'Receive Ext. Reg.No.';
$lang ['label'] ['sendExt'] = 'Send Ext. Reg.No.';
$lang ['label'] ['recvClassified'] = 'Classified Receive Reg.No.';
$lang ['label'] ['sendClassified'] = 'Classified Send Reg.No.';

$lang ['common'] ['logonTo'] = 'Logon to ';
$lang ['common'] ['loginName'] = 'Username';
$lang ['common'] ['password'] = 'Password';
$lang ['common'] ['logon'] = 'Logon';
$lang ['common'] ['remark'] = 'Remark';
$lang ['common'] ['createPolicyGroup'] = 'Create Secure Group';
$lang ['common'] ['modifyPolicyGroup'] = 'Modify Secure Group';
$lang ['common'] ['amount'] = 'Amount';
$lang ['common'] ['item'] = 'item';
$lang ['common'] ['comment'] = 'Remark';
$lang['common']['currentYear'] = 'Current Year';
$lang['common']['currentRole'] = 'Current Role';
$lang['common']['lastChangePwd'] = 'Last Change Password';

$lang ['window'] ['editGlobalRegno'] = 'Modify Global Reg.No.';
$lang ['window'] ['editLocalRegno'] = 'Modify Reg.No.';
$lang ['window'] ['editBookno'] = 'Modify Book No.';

$lang ['context'] ['orgMrg'] ['createOrg'] = 'Create Org.Unit';
$lang ['context'] ['orgMrg'] ['createGroup'] = 'Create Group';
$lang ['context'] ['orgMrg'] ['addRole'] = 'Create Role';
$lang ['context'] ['orgMrg'] ['addUser'] = 'Add user';
$lang ['context'] ['orgMrg'] ['edit'] = 'Modify';
$lang ['context'] ['orgMrg'] ['delete'] = 'Delete';
$lang ['context'] ['orgMrg'] ['setRegNo'] = 'Setup Reg. No.';
$lang ['context'] ['orgMrg'] ['setBookNo'] = 'Setup Book No.';
$lang['context']['orgMrg']['ISO'] = 'ISO Approval';
$lang ['context'] ['orgMrg'] ['refresh'] = 'Refresh';
$lang ['context'] ['orgMrg'] ['property'] = 'Property';

$lang ['portlet1'] ['title'] = 'Document Transaction';
$lang ['portlet1'] ['reload'] = 'Refreshing [' . $lang ['portlet1'] ['title'] . ']';
$lang ['portlet']['1'] ['title'] = 'Document Transaction';
$lang ['portlet']['1'] ['reload'] = 'Refreshing [' . $lang ['portlet']['1'] ['title'] . ']';
$lang ['portlet2'] ['title'] = 'Document Operation';
$lang ['portlet2'] ['reload'] = 'Refreshing [' . $lang ['portlet2'] ['title'] . ']';
$lang ['portlet3'] ['title'] = 'Document Control';
$lang ['portlet3'] ['reload'] = 'Refreshing [' . $lang ['portlet3'] ['title'] . ']';
$lang ['portlet4'] ['title'] = 'Additional Commands';
$lang ['portlet4'] ['reload'] = 'Refreshing [' . $lang ['portlet4'] ['title'] . ']';
$lang ['portlet5'] ['title'] = 'Awaiting Items';
$lang ['portlet5'] ['reload'] = 'Refreshing [' . $lang ['portlet5'] ['title'] . ']';
$lang ['portlet6'] ['title'] = 'Application Portal';
$lang ['portlet6']['reload'] = 'Refreshing [' . $lang ['portlet6']  ['title'] . ']';
$lang ['portlet7'] ['title'] = 'ISO Approval';
$lang ['portlet7']['reload'] = '���ѧ���ê [' . $lang ['portlet6']  ['title'] . ']';

$lang ['application'] ['carReserve'] = 'Car Reserve';
$lang ['application'] ['roomReserve'] = 'Room Reserve';
$lang ['application'] ['AssetSystem'] = 'Assets Application';

$lang ['ECMExplorer'] = 'Toolbox';
$lang ['WorkplaceExplorer'] = 'Work/Documents';
$lang ['OrgChartExplorer'] = 'Organization Chart';

$lang ['roomBooking'] = 'Room Reserve';
$lang ['carBooking'] = 'Car Reserve';
$lang ['meetingNote'] = 'Meeting Notes/Tasks';
$lang ['workAndTask'] = 'Document Flow';
$lang ['workflowTask'] = 'Workflow';
$lang ['DMS'] = 'DMS';
$lang ['KB'] = 'Knowledge Base';

$lang ['common'] ['manage'] = 'Manage';
$lang ['common'] ['create'] = 'Create';
$lang ['common'] ['modify'] = 'Modify';
$lang ['common'] ['delete'] = 'Delete';
$lang ['common'] ['move'] = 'Move';
$lang ['common'] ['update'] = 'Update';
$lang ['common'] ['save'] = 'Save';
$lang ['common'] ['moveUp'] = 'Shift Up';
$lang ['common'] ['moveDown'] = 'Shift Down';
$lang ['common'] ['ok'] = 'OK';
$lang ['common'] ['cancel'] = 'Cancel';
$lang ['common'] ['desc'] = 'Description';
$lang ['common'] ['clear'] = 'Clear';
$lang ['common'] ['confirm'] = 'Confirm';
$lang ['common'] ['toggle'] = 'Toggle';
$lang ['common'] ['refresh'] = 'Refresh';
$lang ['common'] ['undefined'] = 'Undefined';
$lang ['common'] ['changePassword'] = 'Change Password';
$lang ['common'] ['changeRole'] = 'Change Role';
$lang ['common'] ['failed'] = 'Operaion Failed';
$lang ['common'] ['success'] = 'Operation Complete';
$lang ['common'] ['saving'] = 'Committing Changes...';
$lang ['common'] ['savingText'] = 'Saving...';
$lang ['common'] ['processingText'] = 'Processing...';
$lang ['common'] ['enable'] = 'Enabled';
$lang ['common'] ['disable'] = 'Disabled';
$lang ['common'] ['level'] = 'Level';
$lang ['common'] ['status'] = 'Status';
$lang ['common'] ['code'] = 'Code';
$lang ['common'] ['name'] = 'Name';
$lang ['common'] ['error'] = 'Problem Occur';
$lang ['common'] ['image'] = 'Image';
$lang ['common'] ['input'] = 'Input';
$lang ['common'] ['wait'] = 'Wait...';
$lang ['common'] ['default'] = 'Default';
$lang ['common'] ['by'] = 'By';
$lang ['common'] ['orderAssigner'] = 'Order By';
$lang ['common'] ['orderReceiver'] = 'Resposible By';

$lang ['common'] ['speed'] [0] = 'None';
$lang ['common'] ['speed'] [1] = 'Urgent I';
$lang ['common'] ['speed'] [2] = 'Urgent II';
$lang ['common'] ['speed'] [3] = 'Urgent III';

$lang ['common'] ['secret'] [0] = 'None';
$lang ['common'] ['secret'] [1] = 'Classified';
$lang ['common'] ['secret'] [2] = 'Secret';
$lang ['common'] ['secret'] [3] = 'Top Secret';
$lang ['common'] ['ack'] = 'Acknowledge';

$lang ['common'] ['received'] = 'Received';
$lang ['common'] ['unreceived'] = 'Unreceive';

$lang ['common'] ['of'] = 'Of';
$lang ['common'] ['searcing'] = 'Searching...';
$lang ['common'] ['loading'] = 'Loading...';
$lang ['home'] = 'Home';
$lang ['HomeTab'] = 'Benefit&copy; ECM&trade; : Home';

$lang ['action'] ['system'] = 'System';
$lang ['action'] ['tool'] = 'Tool';
$lang ['action'] ['report'] = 'Report';
$lang ['action'] ['addin'] = 'Add On';
$lang ['action'] ['Work'] = $lang ['workAndTask'];
$lang ['action'] ['Document'] = $lang ['DMS'];
$lang ['action'] ['KBase'] = $lang ['KB'];
$lang ['action'] ['WorkflowActivate'] = 'Workflow';
$lang ['action'] ['Calendar'] = 'Calendar';
$lang ['action'] ['personalCalendar'] = 'Personal Calendar';
$lang ['action'] ['rank'] = 'Rank Management';
$lang ['action'] ['position'] = 'Position Master Management';
$lang ['action'] ['account'] = 'Account Management';
$lang ['action'] ['groupPolicy'] = 'Group Policy Management';
$lang ['action'] ['orgChart'] = 'Organization Chart Management';
$lang ['action'] ['masterControl'] = 'Master Data Group';
$lang ['action'] ['masterData'] = 'Master Data';
$lang ['action'] ['concurrent'] = 'Concurrent';
$lang ['action'] ['license'] = 'Install License';
$lang ['action'] ['config'] = 'Configure';
$lang ['action'] ['portlet'] = 'Portlet Management';
$lang ['action'] ['addin'] = 'Add on Managemenr';
$lang ['action'] ['other'] = 'Help';

$lang ['action'] ['globalSearch'] = 'Global Search';
$lang ['action'] ['globalRegbookSearch'] = 'Global Reg.Book Search';
$lang ['action'] ['extDeleteSender'] = 'External Sender Management';
$lang ['action'] ['Form'] = 'Form Management';
$lang ['action'] ['docType'] = 'Document Type Management';
$lang ['action'] ['storage'] = 'Storage Management';
$lang ['action'] ['regNoManage'] = 'Register No. Management';
$lang ['action'] ['workflow'] = 'Workflow Management';
$lang ['action'] ['docRoute'] = 'Document Flow Route Management';
$lang ['action'] ['CDDVD'] = 'CD/DVD Archive';
$lang ['action'] ['reportManager'] = 'Report Management';
$lang ['action'] ['preference'] = 'Preference';

$lang ['action'] ['generalReport'] = 'General Report';
$lang ['action'] ['systemReport'] = 'System Report';
$lang ['action'] ['executiveReport'] = 'Executive Report';

$lang ['action'] ['Works'] = 'Documents';
$lang ['action'] ['registerBook'] = 'Register Books';

$lang ['rank'] ['manager'] = 'Rank Manager';
$lang ['rank'] ['add'] = 'Create Rank';
$lang ['rank'] ['edit'] = 'Modify Rank';
$lang ['rank'] ['delete'] = 'Delete Rank';
$lang ['rank'] ['name'] = 'Rank Name';
$lang ['rank'] ['default'] = 'Level';
$lang ['rank'] ['status'] = 'Status';
$lang ['rank'] ['seq'] = 'Sequence';
$lang ['rank'] ['stack'] = 'Reference';

$lang ['pos'] ['manager'] = 'Position Manager';
$lang ['pos'] ['add'] = 'Create Position';
$lang ['pos'] ['edit'] = 'Modify Position';
$lang ['pos'] ['map'] = 'Rank Mapping';
$lang ['pos'] ['delete'] = 'Delete Position';
$lang ['pos'] ['name'] = 'Position Name';
$lang ['pos'] ['seq'] = $lang ['rank'] ['seq'];
$lang ['pos'] ['stack'] = $lang ['rank'] ['stack'];

$lang ['gp'] ['manager'] = 'Group Policy Manager';
$lang ['gp'] ['add'] = 'Create Policy';
$lang ['gp'] ['edit'] = 'Modify Policy';
$lang ['gp'] ['delete'] = 'Delete Policy';
$lang ['gp'] ['name'] = 'Policy Name';
$lang ['gp'] ['seq'] = $lang ['rank'] ['seq'];
$lang ['gp'] ['stack'] = $lang ['rank'] ['stack'];

$lang ['acc'] ['manager'] = 'Account Manager';
$lang ['acc'] ['add'] = 'Create Account';
$lang ['acc'] ['setpass'] = 'Set Password';
$lang ['acc'] ['delete'] = 'Delete Account';
$lang ['acc'] ['name'] = 'Account Name';
$lang ['acc'] ['type'] = 'Account Type';
$lang ['acc'] ['showDetail'] = 'Show Account Detail';
$lang ['acc'] ['showFrom'] = 'Account no.';
$lang ['acc'] ['login'] = 'Username';
$lang ['acc'] ['password'] = 'Password';
$lang ['acc'] ['newpassword'] = 'New Password';
$lang ['acc'] ['retypeNewPassword'] = 'Retype New Password';
$lang ['acc'] ['oldpassword'] = 'Old Password';
$lang ['acc'] ['retypePassword'] = 'Retype Password';
$lang ['acc'] ['firstname'] = 'Name';
$lang ['acc'] ['midname'] = 'Mid-Name';
$lang ['acc'] ['lastname'] = 'Lastname';
$lang ['acc'] ['email'] = 'E-Mail';
$lang ['acc'] ['tel'] = 'Tel.';
$lang ['acc'] ['fax'] = 'Fax';
$lang ['acc'] ['mobile'] = 'Mobile';

$lang ['common'] ['saved'] = 'Change Commited';

$lang ['org'] ['name'] = 'Org.Unit Name';
$lang ['org'] ['type'] = 'Type';
$lang ['org'] ['ou'] = 'Structure';
$lang ['org'] ['group'] = 'Group';
$lang ['org'] ['role'] = 'Role';
$lang ['org'] ['user'] = 'User';

$lang ['org'] ['addOrgSuccess'] = 'Organization Unit Created';
$lang ['org'] ['addRoleSuccess'] = 'Role Created';
$lang ['org'] ['addMappingSuccess'] = 'User Mapping Saved';
$lang ['org'] ['editOrgSuccess'] = 'Organization Unit Modified';
$lang ['org'] ['editRoleSuccess'] = 'Role Modified';
$lang ['org'] ['deleteOrgSuccess'] = 'Organization Deleted';
$lang ['org'] ['deleteRoleSuccess'] = 'Role Deleted';
$lang ['org'] ['deleteMappingSuccess'] = 'User Mapping Deleted';
$lang ['org'] ['moveStructure'] = 'Structure Move';

$lang ['df'] ['editRegNo'] = 'Reg.No Modification';
$lang ['df'] ['editBookNo'] = 'Red.No. Modify';
$lang ['df'] ['recvType1'] = 'Receive Internal';
$lang ['df'] ['recvType2'] = 'Receive External';
$lang ['df'] ['recvType3'] = 'Receive External Global';
$lang ['df'] ['recvType4'] = 'Receive Classified';
$lang ['df'] ['recvType5'] = 'Receive Circ';

$lang ['df'] ['sendType1'] = 'Send Internal';
$lang ['df'] ['sendType2'] = 'Send External';
$lang ['df'] ['sendType3'] = 'Send External Global';
$lang ['df'] ['sendType4'] = 'Send Classified';
$lang ['df'] ['sendType5'] = 'Send Circ';
$lang ['df'] ['sendType6'] = 'Forward';

$lang ['df'] ['filter'] = 'Filter';
$lang ['df'] ['genBook'] = 'Generate Book';
$lang ['df'] ['replyBook'] = 'Reply';
$lang ['df'] ['auditBook'] = 'Audit';
$lang ['df'] ['recvType'] = 'Receive Type';
$lang ['df'] ['sendType'] = 'Send Type';
$lang ['df'] ['preview'] = 'View';
$lang ['df'] ['view'] = 'View';
$lang ['df'] ['track'] = 'Visual Track';
$lang ['df'] ['saveToTrack'] = 'Save To Track';
$lang ['df'] ['forward'] = 'Forward';
$lang ['df'] ['forwardExt'] = 'Forward Ext.';
$lang ['df'] ['sendEmail'] = 'Send Mail';
$lang ['df'] ['return'] = 'Return';
$lang ['df'] ['pause'] = 'Pause';
$lang ['df'] ['fetch'] = 'Refresh';
$lang ['df'] ['viewlog'] = 'Log';
$lang ['df'] ['abort'] = 'Abort';    
$lang ['df'] ['receiveUser'] = 'Receiver';
$lang ['df'] ['receiveStamp'] = 'Receive Stamp';
$lang ['df'] ['sendUser'] = 'Sender';
$lang ['df'] ['sendStamp'] = 'Send Stamp';
$lang ['df'] ['viewdetail'] = 'View Detail';
$lang ['df'] ['receivedInternal'] = 'Received Internal Document';
$lang ['df'] ['receivedExternal'] = 'Received External Document';
$lang ['df'] ['sendInternal'] = 'Sent Internal';
$lang ['df'] ['sendExternal'] = 'Sent External';
$lang ['df'] ['noDocument'] = 'No Document';
$lang ['df'] ['unreceiveitem'] = 'Unreceive Document';
$lang ['df'] ['noUnreceiveitem'] = 'No Document';
$lang ['df'] ['classified'] = 'Security';
$lang ['df'] ['speed'] = 'Speed';
$lang ['df'] ['recvNo'] = 'Recv.No.';
$lang ['df'] ['sendNo'] = 'Send.No.';
$lang ['df'] ['docNo'] = 'Book No';
$lang ['df'] ['title'] = 'Title';
$lang ['df'] ['date'] = 'Date';
$lang ['df'] ['from'] = 'From';
$lang ['df'] ['to'] = 'To';
$lang ['df'] ['action'] = 'Command/Action';
$lang ['df'] ['accept'] = 'Accept';
$lang ['df'] ['acceptOperation'] = 'Accept';
$lang ['df'] ['sendback'] = 'Sendback';
$lang ['df'] ['callback'] = 'Callback';
$lang ['df'] ['saveCommand'] = 'Save Command/Action';
$lang ['df'] ['search'] = 'Search';
$lang ['df'] ['excelExport'] = 'Excel Report';
$lang ['df'] ['closeJob'] = 'Close Job';
$lang ['df'] ['noBookNo'] = '(N/A)';

$lang ['dms'] ['name'] = 'Name';
$lang ['dms'] ['index'] = 'Index';
$lang ['dms'] ['folder'] = 'Folder';
$lang ['dms'] ['document'] = 'Document';
$lang ['dms'] ['shortcut'] = 'Shortcut';
$lang ['dms'] ['description'] = 'Description';
$lang ['dms'] ['keyword'] = 'Keyword';
$lang ['dms'] ['createStamp'] = 'Create Date';
$lang ['dms'] ['createStampTime'] = 'Create Time';
$lang ['dms'] ['updateStamp'] = 'Modified Date';
$lang ['dms'] ['updateStampTime'] = 'Modified Time';
$lang ['dms'] ['expireStamp'] = 'Expire Date';
$lang ['dms'] ['searchResult'] = 'Search Results: ';
$lang ['dms'] ['location'] = 'Location ';
$lang ['dms'] ['contain'] = 'Contains ';
$lang ['dms'] ['goto'] = '>> ';
$lang ['dms'] ['show'] = 'Show';
$lang ['dms'] ['showIndexDesc'] = $lang ['dms'] ['show'] . $lang ['dms'] ['description'] . $lang ['dms'] ['index'];
$lang ['dms'] ['checkout'] = 'Checkout ';
$lang ['dms'] ['checkin'] = 'Checkin ';
$lang ['dms'] ['failedCheckin'] = 'Unable to Check-in(NOT a Check-out user)';

$lang ['dms'] ['property'] = 'Property';
$lang ['dms'] ['propTabGeneral'] = 'General';
$lang ['dms'] ['propTabSecurity'] = 'Security';
$lang ['dms'] ['propTabVersion'] = 'Version Control';

$lang ['dms'] ['docStatus'] = $lang ['common'] ['status'] . $lang ['dms'] ['document'];
$lang ['dms'] ['workingStatus'] = 'Working';
$lang ['dms'] ['baselineStatus'] = 'Baseline';
$lang ['dms'] ['archiveStatus'] = 'Archive';
$lang ['dms'] ['modifyBy'] = $lang ['common'] ['modify'] . $lang ['common'] ['by'];

$lang ['workflow'] ['incomingJob'] = 'Incoming Job';
$lang ['workflow'] ['outgoingJob'] = 'Outgoing Job';
$lang ['workflow'] ['commitJob'] = 'Finished Job';
$lang ['workflow'] ['draftJob'] = 'Draft';
$lang ['workflow'] ['abortJob'] = 'Aborted Job';

$lang['order']['type'] = '������';
$lang['order']['section'] = '��Ǵ';
$lang['order']['title'] = '����ͧ';
$lang['order']['date'] = 'ŧ�ѹ���';
$lang['order']['signer'] = 'ŧ���';
$lang['order']['role'] = '���˹�';
$lang['order']['remar'] = '�����˵�';

$lang ['workitem'] ['orders'] = 'Orders';
$lang ['workitem'] ['accept'] = 'Accept';
$lang ['workitem'] ['approve'] = 'Approve';
$lang ['workitem'] ['acceptCirc'] = 'Accept as Circ.';
$lang ['workitem'] ['assign'] = 'Assign';
$lang['workitem']['makeCirc'] = 'Make Circ';
$lang ['workitem'] ['report'] = 'Report';
$lang ['workitem'] ['send'] = 'Send';
$lang ['workitem'] ['sendCirc'] = 'Circ.';
$lang ['workitem'] ['receiveDocumentFromInternal'] = 'Received Internal';
$lang ['workitem'] ['receiveDocumentFromExternal'] = 'Received External';
$lang ['workitem'] ['receiveDocumentFromExternalGlobal'] = 'Received External (Global)';
$lang ['workitem'] ['sendDocumentToInternal'] = 'Sent Internal';
$lang ['workitem'] ['sendDocumentToExternal'] = 'Sent External';
$lang ['workitem'] ['sendDocumentToExternal2'] = 'Sent External';
$lang ['workitem'] ['sendDocumentToExternalGlobal'] = 'Sent External (Global)';
$lang ['workitem'] ['circBookInteral'] = 'Circ. Document';
$lang ['workitem'] ['circBookExteral'] = 'Circ. Document(External)';
$lang ['workitem'] ['divRegBook'] = 'Register Book';
$lang ['workitem'] ['globalRegBook'] = 'Global Register Book';

$lang ['workitem'] ['selectInternalReceiver'] = 'Choose Internal Receiver';
$lang ['workitem'] ['selectExternalReceiver'] = 'Choose External Receiver';
$lang ['workitem'] ['approve'] = 'Approve';

$lang ['workitem'] ['unreceivedItem'] = 'Unreceive Document';
$lang ['workitem'] ['orderItem'] = 'Assigned Document';
$lang ['workitem'] ['awaitingItem'] = 'Awaiting Document';
$lang ['workitem'] ['receivedItem'] = 'Received Document';
$lang ['workitem'] ['sendbackItem'] = 'Sendback Document';
$lang ['workitem'] ['callbackItem'] = 'Callback Document';
$lang ['workitem'] ['registerBook'] = 'Register book';
$lang ['workitem'] ['receivedExternalGlobal'] = 'Received External(Global)';
$lang ['workitem'] ['sendExternalGlobal'] = 'Sent External(Global)';
$lang ['workitem'] ['receivedInternal'] = 'Received Internal Register Book';
$lang ['workitem'] ['receivedExternal'] = 'Received External  Register Book';
$lang ['workitem'] ['sendInternal'] = 'Sent Internal Register Book';
$lang ['workitem'] ['sendExternal'] = 'Sent External Register Book';
$lang ['workitem'] ['classifiedInbound'] = 'Classified Received Register Book';
$lang ['workitem'] ['classifiedOutbound'] = 'Classified Sent Register Book';
$lang ['workitem'] ['classifiedRegister'] = 'Classified Register Book';
$lang ['workitem'] ['outgoingItem'] = 'Outgoing Document';
$lang ['workitem'] ['forwardItem'] = 'Forward Document';
$lang ['workitem'] ['committedItem'] = 'Finished Order Received';
$lang ['workitem'] ['completedItem'] = 'Finished Order Assigned';
$lang ['workitem'] ['trackItem'] = 'Track Book';
$lang ['workitem'] ['draftItem'] = 'Draft Document';
$lang ['workitem'] ['abortItem'] = 'Aborted Document';
$lang ['workitem'] ['searchFolder'] = 'Search Folder';
$lang ['workitem'] ['receivedCircBook'] = 'Received Circ. Register Book';
$lang ['workitem'] ['sendCircBook'] = 'Sent Circ. Register Book';
$lang ['master'] ['create'] = "Create Master Type";
$lang ['master'] ['edit'] = "Modify Master Type";
$lang ['master'] ['delete'] = "Delete Master Type";
$lang ['master'] ['typeCode'] = "Code";
$lang ['master'] ['typeName'] = "Name";
$lang ['master'] ['createData'] = "Create Data";
$lang ['master'] ['dataCode'] = "Code";
$lang ['master'] ['dataName'] = "Name";

$lang['manual']['DF'] = 'Document Flow Manual';
$lang['manual']['DMS'] = 'DMS Manual';
$lang['manual']['WF'] = 'Workflow Manual';