<?php
/**
 * Class StoreManager
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0
 * 
 *
 */

class StoreManager {
	public function getDataStore($storeName, $storeOutName = 'default') {
		global $config;
		global $lang;
		global $sessionMgr;
		
		$store = '';
		switch ($storeName) {
			case 'reserveNo' :
				if ($storeOutName == 'default') {
					$storeOutName = 'reserveNoStore';
				}
				$accID = $sessionMgr->getCurrentAccID ();
				
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/df-data/reserve-no?orgID={$sessionMgr->getCurrentOrgID()}'
					}),					
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'bookno','regno','reserveTxt','info','remark'
						]
					}),
					remoteSort: false
				});
				";
				break;
			case 'reserveGlobalNo' :
				if ($storeOutName == 'default') {
					$storeOutName = 'reserveGlobalNoStore';
				}
				$accID = $sessionMgr->getCurrentAccID ();
				
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/df-data/reserve-global-no?orgID={$sessionMgr->getCurrentOrgID()}'
					}),					
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'bookno','regno','reserveTxt','info','remark'
						]
					}),
					remoteSort: false
				});
				";
				break;
			
			case 'reserveEnableType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'reserveEnableTypeStore';
				}
				$store = "var {$storeOutName} = new Ext.data.SimpleStore({
                    fields: ['name', 'value'],
                    id: 'value',
                    data :  [
						
						['����¹����¹͡', 'SE'],
						['����¹����¹͡��ǹ��ҧ','SG']
                    ]
                });
                ";
				break;
			
			case 'registerType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'registerTypeStore';
				}
				$store = "var {$storeOutName} = new Ext.data.SimpleStore({
                    fields: ['name', 'value'],
                    id: 'value',
                    data :  [
						['���͡������',-1],
						['����¹�Ѻ����', 'RI'],
						['����¹�Ѻ���¹', 'RC'],
						['����¹�Ѻ(�Ѻ)', 'RS'],
						['����¹�Ѻ��¹͡', 'RE'],
						['����¹�Ѻ��¹͡��ǹ��ҧ', 'RG'],
						['����¹������', 'SI'],
						['��§ҹ���Ѻ˹ѧ���', 'SIGN'],
						['����¹�觵������', 'SF'],
						['����¹�����¹', 'SC'],
						['����¹��(�Ѻ)', 'SS'],
						['����¹����¹͡', 'SE'],
						['����¹����¹͡��ǹ��ҧ','SG']
                    ]
                });
                ";
				break;
			
			case 'announceType' :
                /*
                if ($storeOutName == 'default') {
                    $storeOutName = 'announceTypeStore';
                }
                $store = "var $storeOutName = new Ext.data.Store({
                    proxy: new Ext.data.ScriptTagProxy({
                        url: '/{$config ['appName']}/data-store/announce-type'
                    }),
                    
                    // create reader that reads the Topic records
                    reader: new Ext.data.JsonReader({
                        root: 'results',
                        totalProperty: 'total',
                        id: 'id',
                        fields: [
                            'id', 'name', 'desc', 'status'
                        ]
                    }),
                    
                    // turn on remote sorting
                    remoteSort: true
               });";
               */
               
               if ($storeOutName == 'default') {
					$storeOutName = 'announceTypeStore';
				}
				$store = "var {$storeOutName} = new Ext.data.SimpleStore({
                    fields: ['name', 'value'],
                    id: 'value',
                    data :  [
                                                 ['���͡������',-1],
                                                 ['�����', 0],
                                                ['����º', 1],
                                                ['��С��', 2],
                                                ['��ͺѧ�Ѻ', 3],
                                                ['����',4]
                    ]
                });
                ";
				break;
			
			case 'masterType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'masterTypeStore';
				}
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/master-type'
			        }),
			        
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name', 'desc', 'status'
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			
			case 'documentType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'documentTypeStore';
				}
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/document-type'
			        }),
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'global', 'orgid','name','desc','status'
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			case 'storage' :
				if ($storeOutName == 'default') {
					$storeOutName = 'storageStore';
				}
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/storage'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name', 'type','server','port', 'path','uid','pwd','limit','size','status','default'
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			
			case 'sendExternalCategory' :
				if ($storeOutName == 'default') {
					$storeOutName = 'sendExternalCategoryStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['{$lang['workitem']['divRegBook']}', '0'],
					['{$lang['workitem']['globalRegBook']}', '1']
					]
				});
				";
				break;
			
			case 'storageType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'storageTypeStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['WebDAV', '0']
					//,['Database', '1']
					]
				});
				";
				break;
			
			case 'dataType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'dataTypeStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['Alphanumeric', '0'],
					['Numeric', '1'],
					['Boolean', '2'],
					['Date', '3']
					]
				});
				";
				break;
			
			case 'structureType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'structureTypeStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['Hidden', '0'],
					['Text', '1'],
					['Textarea', '2'],
					['List', '3'],
					['Checkbox', '4'],
					['Radio', '5'],
					['Date Picker', '6'],
					['Doc. Reference', '7'],
					['Table', '8'],
					['Picture', '9'],
					['Signature', '10'],
					['Upload', '11'],
					['Comment', '12']
					]
				});
				";
				break;
			
			case 'form' :
				if ($storeOutName == 'default') {
					$storeOutName = 'formStore';
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/form'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name', 'description','version', 'status','allowwf','allowdms','allowdf','allowkb','allowcomment','allowattach'
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			
			case 'formCombo' :
				if ($storeOutName == 'default') {
					$storeOutName = 'formComboStore';
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/form-no-limit'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name', 'description','version', 'status','allowwf','allowdms','allowdf','allowkb','allowcomment','allowattach'
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			
			case 'formListSaraban' :
				if ($storeOutName == 'default') {
					$storeOutName = 'formListSarabanStore';
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/form-list-saraban'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name',
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			
			case 'formListDMS' :
				if ($storeOutName == 'default') {
					$storeOutName = 'formListDMSStore';
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/form-list-dms'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name',
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			
			case 'concurrent' :
				if ($storeOutName == 'default') {
					$storeOutName = 'concurrentStore';
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/concurrent'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name', 'ipaddress', 'firstaccess','lastaccess'
			            ]
			        }),
			        // turn on remote sorting
			        remoteSort: true
			   });";
				
				break;
			
			case 'account' :
				if ($storeOutName == 'default') {
					$storeOutName = 'accountStore';
				}
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/account'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name', 'login', 'status','rank','type'
			            ]
			        }),
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			
			case 'accountAvailableForPosition' :
				if ($storeOutName == 'default') {
					$storeOutName = 'accountAvailableStore';
				}
				$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/account-available-for-role'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'id',
			            fields: [
			                'id', 'name','status','rank','type'
			            ]
			        }),
			        // turn on remote sorting
			        remoteSort: true
			   });";
				break;
			
			case 'rank' :
				if ($storeOutName == 'default') {
					$storeOutName = 'rankStore';
				}
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/rank'
					}),
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name', 'description','level', 'status'
						]
					}),
					// turn on remote sorting
					remoteSort: true
				});
				";
				break;
			
			case 'organizeType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'organizeTypeStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['Organization Unit', '0'],
					['Group', '1']
					]
				});
				";
				break;
			
			case 'accountType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'accountTypeStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['End User', '0'],
					['Power User', '1'],
					['Local Administrator', '2'],
					['Administrator', '3'],
					['Developer', '4']
					]
				});
				";
				break;
			
			case 'createSequence' :
				if ($storeOutName == 'default') {
					$storeOutName = 'createSequeceStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['Top', '1'],
					['Before', '2'],
					['After', '3'],
					['Bottom', '4']
					]
				});
				";
				break;
			
			case 'notifierType' :
				if ($storeOutName == 'default') {
					$storeOutName = 'notifierTypeStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['SMS', '1'],
					['E-Mail', '2'],
					['Jabber', '3']
					]
				});
				";
				break;
			
			case 'createStatus' :
				if ($storeOutName == 'default') {
					$storeOutName = 'createStatusStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['Enabled', '0'],
					['Disabled', '1']
					]
				});
				";
				break;
			
			case 'secretLevel' :
				if ($storeOutName == 'default') {
					$storeOutName = 'secretLevelStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['{$lang['common']['secret'][0]}', 0],
					['{$lang['common']['secret'][1]}', 1],
					['{$lang['common']['secret'][2]}', 2],
					['{$lang['common']['secret'][3]}', 3]
					]
				});
				";
				break;
			
			case 'speedLevel' :
				if ($storeOutName == 'default') {
					$storeOutName = 'speedLevelStore';
				}
				$store = "var $storeOutName = new Ext.data.SimpleStore({
					fields: ['name', 'value'],
					id: 'value',
					data :  [
					['{$lang['common']['speed'][0]}', 0],
					['{$lang['common']['speed'][1]}', 1],
					['{$lang['common']['speed'][2]}', 2],
					['{$lang['common']['speed'][3]}', 3]
					]
				});
				";
				break;
			
			case 'position' :
				if ($storeOutName == 'default') {
					$storeOutName = 'positionStore';
				}
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/position'
					}),
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name', 'description','level', 'status'
						]
					}),
					// turn on remote sorting
					remoteSort: true
				});
				";
				break;
			
			case 'policy' :
				if ($storeOutName == 'default') {
					$storeOutName = 'policyStore';
				}
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/policy'
					}),
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name', 'description', 'status'
						]
					}),
					// turn on remote sorting
					remoteSort: true
				});
				";
				break;
			
			case 'policyProperty' :
				
				if ($storeOutName == 'default') {
					$storeOutName = 'policyPropertyStore';
				}
				$store = "var quickInfoStore = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({url:'foo.php'}),
					reader: new Ext.data.JsonReader({
					root: 'result',
					id: 'id',
					totalProperty: 'total',
					fields: ['Property','Value']
					}
				});
					var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/policy'
					}),
					//create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name', 'description', 'status'
						]
					}),
					//turn on remote sorting
					remoteSort: true
				});
				";
				break;
			
			case 'documentTypeList' :
				if ($storeOutName == 'default') {
					$storeOutName = 'documentTypeListStore';
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
					//autoLoad: false,
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/document-type-list'
					}),					
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name'
						]
					}),
					remoteSort: false
				});
				";
				break;
			
			case 'receiveTypeList' :
				if ($storeOutName == 'default') {
					$storeOutName = 'sendTypeListStore';
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/receive-type-list'
					}),					
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name'
						]
					}),
					remoteSort: false
				});
				";
				break;
			
			case 'purposeSaraban' :
				if ($storeOutName == 'default') {
					$storeOutName = 'purposeSarabanStore';
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/purpose-saraban'
					}),					
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name'
						]
					}),
					remoteSort: false
				});
				";
				break;
			
			case 'registerBookReceiveInternal' :
				if ($storeOutName == 'default') {
					$storeOutName = 'registerBookReceiveInternalStore';
				}
				$roleID = $_SESSION ['roleID'];
				include_once 'Role.Entity.php';
				$role = new RoleEntity ( );
				if (! $role->Load ( "f_role_id = '$roleID'" )) {
					return "";
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/register-book?type=1&orgID={$role->f_org_id}'
					}),					
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name'
						]
					}),
					remoteSort: false
				});
				";
				break;
			
			case 'registerBookReceiveExternal' :
				if ($storeOutName == 'default') {
					$storeOutName = 'registerBookReceiveExternalStore';
				}
				$roleID = $_SESSION ['roleID'];
				include_once 'Role.Entity.php';
				$role = new RoleEntity ( );
				if (! $role->Load ( "f_role_id = '$roleID'" )) {
					return "";
				}
				
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/register-book?type=3&orgID={$role->f_org_id}'
					}),					
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name'
						]
					}),
					remoteSort: false
				});
				";
				break;
			
			case 'roleAvailable' :
				if ($storeOutName == 'default') {
					$storeOutName = 'roleAvailable';
				}
				$accID = $sessionMgr->getCurrentAccID ();
				
				$store = "var $storeOutName = new Ext.data.Store({
					proxy: new Ext.data.ScriptTagProxy({
						url: '/{$config ['appName']}/data-store/role-available?accID={$accID}'
					}),					
					// create reader that reads the Topic records
					reader: new Ext.data.JsonReader({
						root: 'results',
						totalProperty: 'total',
						id: 'id',
						fields: [
						'id', 'name', 'orgName'
						]
					}),
					remoteSort: false
				});
				";
				break;
		}
		return $store;
	}
	
	public function getPolicyMapping($mapping, $type = 'field') {
		if ($type == 'field') {
			switch ($mapping) {
				case 'f_fm_master' :
					return 901;
					break;
				case 'f_fm_create' :
					return 902;
					break;
				case 'f_fm_modify' :
					return 903;
					break;
				case 'f_fm_design' :
					return 904;
					break;
				case 'f_fm_delete' :
					return 905;
					break;
				case 'f_fm_disable' :
					return 906;
					break;
				case 'f_fm_script' :
					return 907;
					break;
				case 'f_fm_import' :
					return 908;
					break;
				case 'f_fm_export' :
					return 909;
					break;
				case 'f_fm_copy' :
					return 910;
					break;
				case 'f_wf_access' :
					return 911;
					break;
				case 'f_wf_master' :
					return 912;
					break;
				case 'f_wf_create' :
					return 913;
					break;
				case 'f_wf_modify' :
					return 914;
					break;
				case 'f_wf_delete' :
					return 915;
					break;
				case 'f_wf_disable' :
					return 916;
					break;
				case 'f_wf_monitor' :
					return 917;
					break;
				case 'f_wf_deploy' :
					return 918;
					break;
				case 'f_wf_script' :
					return 919;
					break;
				case 'f_wf_import' :
					return 920;
					break;
				case 'f_wf_export' :
					return 921;
					break;
				case 'f_wf_copy' :
					return 922;
					break;
				case 'f_wf_approve' :
					return 923;
					break;
				case 'f_wf_agree' :
					return 924;
					break;
				case 'f_wf_comment' :
					return 925;
					break;
				case 'f_wf_attach' :
					return 926;
					break;
				case 'f_wf_pause' :
					return 927;
					break;
				case 'f_wf_abort' :
					return 928;
					break;
				case 'f_wf_secret_lvl' :
					return 929;
					break;
				case 'f_sb_access' :
					return 930;
					break;
				case 'f_sb_master' :
					return 931;
					break;
				case 'f_sb_create' :
					return 932;
					break;
				case 'f_sb_modify' :
					return 933;
					break;
				case 'f_sb_delete' :
					return 934;
					break;
				case 'f_sb_draft' :
					return 935;
					break;
				case 'f_sb_pause' :
					return 936;
					break;
				case 'f_sb_abort' :
					return 937;
					break;
				case 'f_sb_sign' :
					return 938;
					break;
				case 'f_sb_approve' :
					return 939;
					break;
				case 'f_sb_agree' :
					return 940;
					break;
				case 'f_sb_send_int' :
					return 941;
					break;
				case 'f_sb_send_ext' :
					return 942;
					break;
				case 'f_sb_send_egif' :
					return 943;
					break;
				case 'f_sb_recv_int' :
					return 944;
					break;
				case 'f_sb_recv_ext' :
					return 945;
					break;
				case 'f_sb_recv_egif' :
					return 946;
					break;
				case 'f_sb_secret_lvl' :
					return 947;
					break;
				case 'f_sb_comment' :
					return 948;
					break;
				case 'f_sb_attach' :
					return 949;
					break;
				case 'f_sb_doctype' :
					return 950;
					break;
				case 'f_sb_sub_doctype' :
					return 951;
					break;
				case 'f_sb_secret_agent' :
					return 952;
					break;
				case 'f_dms_access' :
					return 953;
					break;
				case 'f_dms_create_folder' :
					return 954;
					break;
				case 'f_dms_modify_folder' :
					return 955;
					break;
				case 'f_dms_delete_folder' :
					return 956;
					break;
				case 'f_dms_create_doc' :
					return 957;
					break;
				case 'f_dms_modify_doc' :
					return 958;
					break;
				case 'f_dms_delete_doc' :
					return 959;
					break;
				case 'f_dms_create_shortcut' :
					return 960;
					break;
				case 'f_dms_modify_shortcut' :
					return 961;
					break;
				case 'f_dms_delete_shortcut' :
					return 962;
					break;
				case 'f_dms_move' :
					return 963;
					break;
				case 'f_dms_share' :
					return 964;
					break;
				case 'f_dms_export' :
					return 965;
					break;
				case 'f_dms_grant' :
					return 966;
					break;
				case 'f_dms_scan' :
					return 967;
					break;
				case 'f_dms_attach' :
					return 968;
					break;
				case 'f_dms_print' :
					return 969;
					break;
				case 'f_dms_annotate' :
					return 970;
					break;
				case 'f_dms_create_folder_loc' :
					return 971;
					break;
				case 'f_dms_modify_folder_loc' :
					return 972;
					break;
				case 'f_dms_delete_folder_loc' :
					return 973;
					break;
				case 'f_dms_view_loc' :
					return 974;
					break;
				case 'f_dms_create_doc_loc' :
					return 975;
					break;
				case 'f_dms_modify_doc_loc' :
					return 976;
					break;
				case 'f_dms_delete_doc_loc' :
					return 977;
					break;
				case 'f_dms_create_shortcut_loc' :
					return 978;
					break;
				case 'f_dms_modify_shortcut_loc' :
					return 979;
					break;
				case 'f_dms_delete_shortcut_loc' :
					return 980;
					break;
				case 'f_dms_move_loc' :
					return 981;
					break;
				case 'f_dms_share_loc' :
					return 982;
					break;
				case 'f_dms_export_loc' :
					return 983;
					break;
				case 'f_dms_grant_loc' :
					return 984;
					break;
				case 'f_dms_scan_loc' :
					return 985;
					break;
				case 'f_dms_attach_loc' :
					return 986;
					break;
				case 'f_dms_print_loc' :
					return 987;
					break;
				case 'f_dms_annotate_loc' :
					return 988;
					break;
				
				case 'f_km_access' :
					return 989;
					break;
				case 'f_km_create' :
					return 990;
					break;
				case 'f_km_read' :
					return 991;
					break;
				case 'f_km_update' :
					return 992;
					break;
				case 'f_km_delete' :
					return 993;
					break;
				case 'f_km_move' :
					return 994;
					break;
				case 'f_km_create_cat' :
					return 995;
					break;
				case 'f_km_update_cat' :
					return 996;
					break;
				case 'f_km_delete_cat' :
					return 997;
					break;
				case 'f_quota' :
					return 998;
					break;
				case 'f_sb_send_int_global' :
					return 999;
					break;
				case 'f_sb_command' :
					return 1000;
					break;
				case 'f_dms_master' :
					return 1001;
					break;
				case 'f_reserve_book_no' :
					return 1002;
					break;
				case 'f_sb_flag' :
					return 1003;
					break;
				case 'f_sb_global_search' :
					return 1004;
					break;
				case 'f_rc_access' :
					return 1005;
					break;
				case 'f_rc_admin' :
					return 1006;
					break;
				case 'f_room_access' :
					return 1007;
					break;
				case 'f_room_admin' :
					return 1008;
					break;
			}
		} else {
			switch (( int ) $mapping) {
				case 901 :
					return 'f_fm_master';
					break;
				case 902 :
					return 'f_fm_create';
					break;
				case 903 :
					return 'f_fm_modify';
					break;
				case 904 :
					return 'f_fm_design';
					break;
				case 905 :
					return 'f_fm_delete';
					break;
				case 906 :
					return 'f_fm_disable';
					break;
				case 907 :
					return 'f_fm_script';
					break;
				case 908 :
					return 'f_fm_import';
					break;
				case 909 :
					return 'f_fm_export';
					break;
				case 910 :
					return 'f_fm_copy';
					break;
				case 911 :
					return 'f_wf_access';
					break;
				case 912 :
					return 'f_wf_master';
					break;
				case 913 :
					return 'f_wf_create';
					break;
				case 914 :
					return 'f_wf_modify';
					break;
				case 915 :
					return 'f_wf_delete';
					break;
				case 916 :
					return 'f_wf_disable';
					break;
				case 917 :
					return 'f_wf_monitor';
					break;
				case 918 :
					return 'f_wf_deploy';
					break;
				case 919 :
					return 'f_wf_script';
					break;
				case 920 :
					return 'f_wf_import';
					break;
				case 921 :
					return 'f_wf_export';
					break;
				case 922 :
					return 'f_wf_copy';
					break;
				case 923 :
					return 'f_wf_approve';
					break;
				case 924 :
					return 'f_wf_agree';
					break;
				case 925 :
					return 'f_wf_comment';
					break;
				case 926 :
					return 'f_wf_attach';
					break;
				case 927 :
					return 'f_wf_pause';
					break;
				case 928 :
					return 'f_wf_abort';
					break;
				case 929 :
					return 'f_wf_secret_lvl';
					break;
				case 930 :
					return 'f_sb_access';
					break;
				case 931 :
					return 'f_sb_master';
					break;
				case 932 :
					return 'f_sb_create';
					break;
				case 933 :
					return 'f_sb_modify';
					break;
				case 934 :
					return 'f_sb_delete';
					break;
				case 935 :
					return 'f_sb_draft';
					break;
				case 936 :
					return 'f_sb_pause';
					break;
				case 937 :
					return 'f_sb_abort';
					break;
				case 938 :
					return 'f_sb_sign';
					break;
				case 939 :
					return 'f_sb_approve';
					break;
				case 940 :
					return 'f_sb_agree';
					break;
				case 941 :
					return 'f_sb_send_int';
					break;
				case 942 :
					return 'f_sb_send_ext';
					break;
				case 943 :
					return 'f_sb_send_egif';
					break;
				case 944 :
					return 'f_sb_recv_int';
					break;
				case 945 :
					return 'f_sb_recv_ext';
					break;
				case 946 :
					return 'f_sb_recv_egif';
					break;
				case 947 :
					return 'f_sb_secret_lvl';
					break;
				case 948 :
					return 'f_sb_comment';
					break;
				case 949 :
					return 'f_sb_attach';
					break;
				case 950 :
					return 'f_sb_doctype';
					break;
				case 951 :
					return 'f_sb_sub_doctype';
					break;
				case 952 :
					return 'f_sb_secret_agent';
					break;
				case 953 :
					return 'f_dms_access';
					break;
				case 954 :
					return 'f_dms_create_folder';
					break;
				case 955 :
					return 'f_dms_modify_folder';
					break;
				case 956 :
					return 'f_dms_delete_folder';
					break;
				case 957 :
					return 'f_dms_create_doc';
					break;
				case 958 :
					return 'f_dms_modify_doc';
					break;
				case 959 :
					return 'f_dms_delete_doc';
					break;
				case 960 :
					return 'f_dms_create_shortcut';
					break;
				case 961 :
					return 'f_dms_modify_shortcut';
					break;
				case 962 :
					return 'f_dms_delete_shortcut';
					break;
				case 963 :
					return 'f_dms_move';
					break;
				case 964 :
					return 'f_dms_share';
					break;
				case 965 :
					return 'f_dms_export';
					break;
				case 966 :
					return 'f_dms_grant';
					break;
				case 967 :
					return 'f_dms_scan';
					break;
				case 968 :
					return 'f_dms_attach';
					break;
				case 969 :
					return 'f_dms_print';
					break;
				case 970 :
					return 'f_dms_annotate';
					break;
				case 971 :
					return 'f_dms_create_folder_loc';
					break;
				case 972 :
					return 'f_dms_modify_folder_loc';
					break;
				case 973 :
					return 'f_dms_delete_folder_loc';
					break;
				case 974 :
					return 'f_dms_view_loc';
					break;
				case 975 :
					return 'f_dms_create_doc_loc';
					break;
				case 976 :
					return 'f_dms_modify_doc_loc';
					break;
				case 977 :
					return 'f_dms_delete_doc_loc';
					break;
				case 978 :
					return 'f_dms_create_shortcut_loc';
					break;
				case 979 :
					return 'f_dms_modify_shortcut_loc';
					break;
				case 980 :
					return 'f_dms_delete_shortcut_loc';
					break;
				case 981 :
					return 'f_dms_move_loc';
					break;
				case 982 :
					return 'f_dms_share_loc';
					break;
				case 983 :
					return 'f_dms_export_loc';
					break;
				case 984 :
					return 'f_dms_grant_loc';
					break;
				case 985 :
					return 'f_dms_scan_loc';
					break;
				case 986 :
					return 'f_dms_attach_loc';
					break;
				case 987 :
					return 'f_dms_print_loc';
					break;
				case 988 :
					return 'f_dms_annotate_loc';
					break;
				case 989 :
					return 'f_km_access';
					break;
				case 990 :
					return 'f_km_create';
					break;
				case 991 :
					return 'f_km_read';
					break;
				case 992 :
					return 'f_km_update';
					break;
				case 993 :
					return 'f_km_delete';
					break;
				case 994 :
					return 'f_km_move';
					break;
				case 995 :
					return 'f_km_create_cat';
					break;
				case 996 :
					return 'f_km_update_cat';
					break;
				case 997 :
					return 'f_km_delete_cat';
					break;
				case 998 :
					return 'f_quota';
					break;
				
				case 999 :
					return 'f_sb_send_int_global';
					break;
				case 1000 :
					return 'f_sb_command';
					break;
				case 1001 :
					return 'f_dms_master';
					break;
				case 1002 :
					return 'f_reserve_book_no';
					break;
				case 1003 :
					return 'f_sb_flag';
					break;
				case 1004 :
					return 'f_sb_global_search';
					break;
				case 1005 :
					return 'f_rc_access';
					break;
				case 1006 :
					return 'f_rc_admin';
					break;
				case 1007 :
					return 'f_room_access';
					break;
				case 1008 :
					return 'f_room_admin';
					break;
			}
		}
		return 0;
	}
	
	public function getPolicyProperty($lang = 'en') {
		$lang = $_SESSION['appLang'];
		$property = Array ();
		switch ($lang) {
			case 'en' :
				$property ['f_fm_master'] = 'Form Master';
				$property ['f_fm_create'] = 'Create Form';
				$property ['f_fm_modify'] = 'Modify Form';
				$property ['f_fm_design'] = 'Design Form';
				$property ['f_fm_delete'] = 'Delete Form';
				$property ['f_fm_disable'] = 'Disable Form';
				$property ['f_fm_script'] = 'Form Scripting';
				$property ['f_fm_import'] = 'Import Form';
				$property ['f_fm_export'] = 'Export Form';
				$property ['f_fm_copy'] = 'Form Copy';
				$property ['f_wf_access'] = 'Workflow Access';
				$property ['f_wf_master'] = 'Workflow Master';
				$property ['f_wf_create'] = 'Create Workflow';
				$property ['f_wf_modify'] = 'Modify Workflow';
				$property ['f_wf_delete'] = 'Delete Workflow';
				$property ['f_wf_disable'] = 'Disable Workflow';
				$property ['f_wf_monitor'] = 'Monitor Workflow';
				$property ['f_wf_deploy'] = 'Deploy Workflow';
				$property ['f_wf_script'] = 'Workflow Scripting';
				$property ['f_wf_import'] = 'Import Workflow';
				$property ['f_wf_export'] = 'Export Workflow';
				$property ['f_wf_copy'] = 'Copy Workflow';
				$property ['f_wf_approve'] = 'Approve Instance';
				$property ['f_wf_agree'] = 'Agree Instance';
				$property ['f_wf_comment'] = 'Comment Instance';
				$property ['f_wf_attach'] = 'Attach Instance';
				$property ['f_wf_pause'] = 'Pause Instance';
				$property ['f_wf_abort'] = 'Abort Instance';
				$property ['f_wf_secret_lvl'] = 'Workflow Secret Level';
				$property ['f_sb_access'] = 'Saraban Access';
				$property ['f_sb_master'] = 'Saraban Master';
				$property ['f_sb_create'] = 'Create Saraban Job';
				$property ['f_sb_modify'] = 'Modify Saraban Job';
				$property ['f_sb_delete'] = 'Delete Saraban Job';
				$property ['f_sb_draft'] = 'Draft Saraban Job';
				$property ['f_sb_pause'] = 'Pause Saraban Job';
				$property ['f_sb_abort'] = 'Abort Saraban Job';
				$property ['f_sb_sign'] = 'Sign Saraban Job';
				$property ['f_sb_approve'] = 'Approve Saraban Job';
				$property ['f_sb_agree'] = 'Agrre Saraban Job';
				$property ['f_sb_send_int'] = 'Send Internal';
				$property ['f_sb_send_ext'] = 'Send External';
				$property ['f_sb_send_egif'] = 'Send to EGIF';
				$property ['f_sb_recv_int'] = 'Receive Internal';
				$property ['f_sb_recv_ext'] = 'Receive External';
				$property ['f_sb_recv_egif'] = 'Receive From EGIF';
				$property ['f_sb_secret_lvl'] = 'Saraban Secret Level';
				$property ['f_sb_comment'] = 'Comment Saraban Job';
				$property ['f_sb_attach'] = 'Attach Saraban';
				$property ['f_sb_doctype'] = 'Add New Saraban Type';
				$property ['f_sb_sub_doctype'] = 'Add New Saraban Sub-Type';
				$property ['f_sb_secret_agent'] = 'Saraban Secret Agent';
				$property ['f_dms_access'] = 'DMS Access';
				$property ['f_dms_create_folder'] = 'Create Container';
				$property ['f_dms_modify_folder'] = 'Modify Container';
				$property ['f_dms_delete_folder'] = 'Delete Container';
				$property ['f_dms_create_doc'] = 'Create Document';
				$property ['f_dms_modify_doc'] = 'Modify Document';
				$property ['f_dms_delete_doc'] = 'Delete Document';
				$property ['f_dms_create_shortcut'] = 'Create Shortcut';
				$property ['f_dms_modify_shortcut'] = 'Modify Shortcut';
				$property ['f_dms_delete_shortcut'] = 'Delete Shortcut';
				$property ['f_dms_move'] = 'Move DMS Object';
				$property ['f_dms_share'] = 'Share DMS Object';
				$property ['f_dms_export'] = 'Export DMS Object';
				$property ['f_dms_grant'] = 'Grant DMS Object';
				$property ['f_dms_scan'] = 'Scan DMS Object';
				$property ['f_dms_attach'] = 'Attach DMS Object';
				$property ['f_dms_print'] = 'Print DMS Object';
				$property ['f_dms_annotate'] = 'Annotate DMS Object';
				$property ['f_dms_create_folder_loc'] = 'Create Container LOC';
				$property ['f_dms_modify_folder_loc'] = 'Modify Container LOC';
				$property ['f_dms_delete_folder_loc'] = 'Delete Container LOC';
				$property ['f_dms_view_loc'] = 'View LOC DMS Object';
				$property ['f_dms_create_doc_loc'] = 'Create Document LOC';
				$property ['f_dms_modify_doc_loc'] = 'Modify Document LOC';
				$property ['f_dms_delete_doc_loc'] = 'Delete Document LOC';
				$property ['f_dms_create_shortcut_loc'] = 'Create Shortcut LOC';
				$property ['f_dms_modify_shortcut_loc'] = 'Modify Shortcut LOC';
				$property ['f_dms_delete_shortcut_loc'] = 'Delete Shortcut LOC';
				$property ['f_dms_move_loc'] = 'Move DMS Object LOC';
				$property ['f_dms_share_loc'] = 'Share DMS Object LOC';
				$property ['f_dms_export_loc'] = 'Export DMS Object LOC';
				$property ['f_dms_grant_loc'] = 'Grant DMS Object LOC';
				$property ['f_dms_scan_loc'] = 'Scan DMS Object LOC';
				$property ['f_dms_attach_loc'] = 'Attach DMS Object LOC';
				$property ['f_dms_print_loc'] = 'Print DMS Object LOC';
				$property ['f_dms_annotate_loc'] = 'Annotate DMS Object LOC';
				$property ['f_quota'] = 'DMS Quota';
				$property ['f_km_access'] = 'KM Access';
				$property ['f_km_create'] = 'Create Knowledge';
				$property ['f_km_read'] = 'Read Knowledge';
				$property ['f_km_update'] = 'Update Knowledge';
				$property ['f_km_delete'] = 'Delete Knowledge';
				$property ['f_km_move'] = 'Move Knowledge';
				$property ['f_km_create_cat'] = 'Create Knowledge Category';
				$property ['f_km_update_cat'] = 'Modify Knowledge Category';
				$property ['f_km_delete_cat'] = 'Delete Knowledge Category';
				
				$property ['f_sb_send_int_global'] = 'Send Int. Global';
				$property ['f_sb_command'] = 'Create Command';
				$property ['f_dms_master'] = 'DMS Master';
				$property ['f_reserve_book_no'] = 'Reserve Book No.';
				$property ['f_sb_flag'] = 'Change Flag';
				$property ['f_sb_global_search'] = 'Global Search';

				$property ['f_rc_access'] = 'Access Reservation Car';
				$property ['f_rc_admin'] = 'Admin Reservation Car';
				
				$property ['f_room_access'] = 'Access Reservation Room';
				$property ['f_room_admin'] = 'Admin Reservation Room';
				break;
			case 'th' :
				$property ['f_fm_master'] = '**�Է����٧�ش�к������';
				$property ['f_fm_create'] = '���ҧ�����';
				$property ['f_fm_modify'] = '��䢿����';
				$property ['f_fm_design'] = '�͡Ẻ�����';
				$property ['f_fm_delete'] = 'ź�����';
				$property ['f_fm_disable'] = '�Դ��ҹ�����';
				$property ['f_fm_script'] = '��¹�����ʤ�Ի��';
				$property ['f_fm_import'] = '����ҿ����';
				$property ['f_fm_export'] = '���͡�����';
				$property ['f_fm_copy'] = '�Ѵ�͡�����';
				$property ['f_wf_access'] = '*��ҹ�к����������';
				$property ['f_wf_master'] = '**�Է����٧�ش�к����������';
				$property ['f_wf_create'] = '���ҧ���������';
				$property ['f_wf_modify'] = '������������';
				$property ['f_wf_delete'] = 'ź���������';
				$property ['f_wf_disable'] = '�Դ��ҹ���������';
				$property ['f_wf_monitor'] = '��Ǩ�ͺ�Դ������������';
				$property ['f_wf_deploy'] = '�վ������������';
				$property ['f_wf_script'] = '��¹���������ʤ�Ի��';
				$property ['f_wf_import'] = '��������������';
				$property ['f_wf_export'] = '���͡���������';
				$property ['f_wf_copy'] = '�Ѵ�͡���������';
				$property ['f_wf_approve'] = '͹��ѵԧҹ';
				$property ['f_wf_agree'] = '��繪ͺ�ҹ';
				$property ['f_wf_comment'] = '�ѹ�֡��ͷ��§ҹ';
				$property ['f_wf_attach'] = 'Ṻ/�᡹�͡���';
				$property ['f_wf_pause'] = '��ش�ҹ���͵�Ǩ�ͺ';
				$property ['f_wf_abort'] = '¡��ԡ�ҹ';
				$property ['f_wf_secret_lvl'] = '�дѺ��鹤����Ѻ';
				$property ['f_sb_access'] = '*��ҹ�к���ú�ó����硷�͹ԡ��';
				$property ['f_sb_master'] = '**�Է����٧�ش�ҹ��ú�ó';
				$property ['f_sb_create'] = '���ҧ�ҹ����';
				$property ['f_sb_modify'] = '��䢧ҹ';
				$property ['f_sb_delete'] = 'ź�ҹ';
				$property ['f_sb_draft'] = '�ѹ�֡��ҧ';
				$property ['f_sb_pause'] = '��ش�ҹ���͵�Ǩ�ͺ';
				$property ['f_sb_abort'] = '¡��ԡ�ҹ��ú�ó';
				$property ['f_sb_sign'] = 'ŧ����ҹ��ú�ó';
				$property ['f_sb_approve'] = '͹��ѵԧҹ';
				$property ['f_sb_agree'] = '��繪ͺ�ҹ';
				$property ['f_sb_send_int'] = '������';
				$property ['f_sb_send_ext'] = '����¹͡(˹��§ҹ)';
				$property ['f_sb_send_egif'] = '����¹͡(����¹��ҧ)';
				$property ['f_sb_recv_int'] = '�Ѻ����';
				$property ['f_sb_recv_ext'] = '�Ѻ��¹͡(˹��§ҹ)';
				$property ['f_sb_recv_egif'] = '�Ѻ��¹͡(����¹��ҧ)';
				$property ['f_sb_secret_lvl'] = '�дѺ��鹤����Ѻ';
				$property ['f_sb_comment'] = '�ѹ�֡��觡��';
				$property ['f_sb_attach'] = 'Ṻ/�᡹�͡���';
				$property ['f_sb_doctype'] = '�����������͡���';
				$property ['f_sb_sub_doctype'] = '���������������͡���';
				$property ['f_sb_secret_agent'] = '��·���¹�Ѻ';
				$property ['f_dms_access'] = '*��ҹ�к��Ѵ���͡���';
				$property ['f_dms_create_folder'] = '���ҧ���';
				$property ['f_dms_modify_folder'] = '������';
				$property ['f_dms_delete_folder'] = 'ź���';
				$property ['f_dms_create_doc'] = '���ҧ�͡���';
				$property ['f_dms_modify_doc'] = '����͡���';
				$property ['f_dms_delete_doc'] = 'ź�͡���';
				$property ['f_dms_create_shortcut'] = '���ҧ�ҧ�Ѵ';
				$property ['f_dms_modify_shortcut'] = '��䢷ҧ�Ѵ';
				$property ['f_dms_delete_shortcut'] = 'ź�ҧ�Ѵ';
				$property ['f_dms_move'] = '���´Ѫ��';
				$property ['f_dms_share'] = '���Ѫ��';
				$property ['f_dms_export'] = '���͡�Ѫ��';
				$property ['f_dms_grant'] = '��˹��Է���Ѫ��';
				$property ['f_dms_scan'] = '�᡹�͡���';
				$property ['f_dms_attach'] = 'Ṻ�͡���';
				$property ['f_dms_print'] = '������͡���';
				$property ['f_dms_annotate'] = '�ѹ�֡ Annotation';
				$property ['f_dms_create_folder_loc'] = '���ҧ��� LOC';
				$property ['f_dms_modify_folder_loc'] = '������ LOC';
				$property ['f_dms_delete_folder_loc'] = 'ź��� LOC';
				$property ['f_dms_view_loc'] = '*���¡�ٴѪ��';
				$property ['f_dms_create_doc_loc'] = '���ҧ�͡��� LOC';
				$property ['f_dms_modify_doc_loc'] = '����͡��� LOC';
				$property ['f_dms_delete_doc_loc'] = 'ź�͡��� LOC';
				$property ['f_dms_create_shortcut_loc'] = '���ҧ�ҧ�Ѵ LOC';
				$property ['f_dms_modify_shortcut_loc'] = '��䢷ҧ�Ѵ LOC';
				$property ['f_dms_delete_shortcut_loc'] = 'ź�ҧ�Ѵ LOC';
				$property ['f_dms_move_loc'] = '���´Ѫ�� LOC';
				$property ['f_dms_share_loc'] = '���Ѫ�� LOC';
				$property ['f_dms_export_loc'] = '���͡�Ѫ�� LOC';
				$property ['f_dms_grant_loc'] = '��˹��Է���Ѫ�� LOC';
				$property ['f_dms_scan_loc'] = '�᡹�͡��� LOC';
				$property ['f_dms_attach_loc'] = 'Ṻ�͡��� LOC';
				$property ['f_dms_print_loc'] = '������͡��� LOC';
				$property ['f_dms_annotate_loc'] = '�ѹ�֡ Annotation LOC';
				$property ['f_quota'] = '��鹷������ҹ(MB)';
				$property ['f_km_access'] = '*��ҹ��ѧ�������';
				$property ['f_km_create'] = '���ҧ�������';
				$property ['f_km_read'] = '��ҹ�������';
				$property ['f_km_update'] = '��䢤������';
				$property ['f_km_delete'] = 'ź�������';
				$property ['f_km_move'] = '���¤������';
				$property ['f_km_create_cat'] = '���ҧ�������������';
				$property ['f_km_update_cat'] = '��䢻������������';
				$property ['f_km_delete_cat'] = 'ź�������������';
				
				$property ['f_sb_send_int_global'] = '������(����¹��ҧ)';
				$property ['f_sb_command'] = '�ѹ�֡�����';
				$property ['f_dms_master'] = '**�Է����٧�ش�к��Ѵ���͡���';
				$property ['f_reserve_book_no'] = '�ͧ�Ţ';
				$property ['f_sb_flag'] = '����¹�š��â��Ţ˹ѧ���/�����';
				$property ['f_sb_global_search'] = '���ҷ���¹���';

				$property ['f_rc_access'] = '��ҹ�к��ͧö';
				$property ['f_rc_admin'] = '�������к��ͧö';
				
				$property ['f_room_access'] = '��ҹ�к��ͧ��ͧ��Ъ��';
				$property ['f_room_admin'] = '�������к��ͧ��ͧ��Ъ��';
				break;
		}
		return $property;
	}
	
	public function getAccountMapping($mapping, $type = 'field') {
		if ($type == 'field') {
			switch ($mapping) {
				case 'f_acc_id' :
					return 1101;
					break;
				case 'f_login_name' :
					return 1102;
					break;
				case 'f_login_password' :
					return 1103;
					break;
				case 'f_name' :
					return 1104;
					break;
				case 'f_mid_name' :
					return 1105;
					break;
				case 'f_last_name' :
					return 1106;
					break;
				case 'f_rank_id' :
					return 1107;
					break;
				case 'f_tries' :
					return 1108;
					break;
				case 'f_last_change_pwd' :
					return 1109;
					break;
				case 'f_status' :
					return 1110;
					break;
				case 'f_account_type' :
					return 1111;
					break;
				case 'f_email' :
					return 1112;
					break;
				case 'f_tel' :
					return 1113;
					break;
				case 'f_fax' :
					return 1114;
					break;
				case 'f_mobile' :
					return 1115;
					break;
				case 'f_is_expired' :
					return 1116;
					break;
				case 'f_expired' :
					return 1117;
					break;
				case 'f_limit_access' :
					return 1118;
					break;
				case 'f_access_from' :
					return 1119;
					break;
				case 'f_access_to' :
					return 1120;
					break;
				case 'f_ldap_bind' :
					return 1121;
					break;
				case 'f_ldap_cn' :
					return 1122;
					break;
				case 'f_dms_home' :
					return 1123;
					break;
				case 'f_strict_to_home' :
					return 1124;
					break;
				case 'f_force_change_pwd' :
					return 1125;
					break;
				case 'f_force_change_pwd_timestamp' :
					return 1126;
					break;
				case 'f_unable_to_change_pwd' :
					return 1127;
					break;
				case 'f_password_hint_question' :
					return 1128;
					break;
				case 'f_password_hint_answer' :
					return 1129;
					break;
			}
		} else {
			switch ($mapping) {
				case 1101 :
					return 'f_acc_id';
					break;
				case 1102 :
					return 'f_login_name';
					break;
				case 1103 :
					return 'f_login_password';
					break;
				case 1104 :
					return 'f_name';
					break;
				case 1105 :
					return 'f_mid_name';
					break;
				case 1106 :
					return 'f_last_name';
					break;
				case 1107 :
					return 'f_rank_id';
					break;
				case 1108 :
					return 'f_tries';
					break;
				case 1109 :
					return 'f_last_change_pwd';
					break;
				case 1110 :
					return 'f_status';
					break;
				case 1111 :
					return 'f_account_type';
					break;
				case 1112 :
					return 'f_email';
					break;
				case 1113 :
					return 'f_tel';
					break;
				case 1114 :
					return 'f_fax';
					break;
				case 1115 :
					return 'f_mobile';
					break;
				case 1116 :
					return 'f_is_expired';
					break;
				case 1117 :
					return 'f_expired';
					break;
				case 1118 :
					return 'f_limit_access';
					break;
				case 1119 :
					return 'f_access_from';
					break;
				case 1120 :
					return 'f_access_to';
					break;
				case 1121 :
					return 'f_ldap_bind';
					break;
				case 1122 :
					return 'f_ldap_cn';
					break;
				case 1123 :
					return 'f_dms_home';
					break;
				case 1124 :
					return 'f_strict_to_home';
					break;
				case 1125 :
					return 'f_force_change_pwd';
					break;
				case 1126 :
					return 'f_force_change_pwd_timestamp';
					break;
				case 1127 :
					return 'f_unable_to_change_pwd';
					break;
				case 1128 :
					return 'f_password_hint_question';
					break;
				case 1129 :
					return 'f_password_hint_answer';
					break;
			}
		}
		return 0;
	}
	
	public function getAccountPropertyEditor() {
		$editors = Array ();
		$editors ['f_acc_id'] = 'text';
		$editors ['f_login_name'] = 'text';
		$editors ['f_login_password'] = 'text';
		$editors ['f_name'] = 'text';
		$editors ['f_mid_name'] = 'text';
		$editors ['f_last_name'] = 'text';
		$editors ['f_rank_id'] = 'combo_Rank';
		$editors ['f_tries'] = 'text';
		$editors ['f_last_change_pwd'] = 'text';
		$editors ['f_status'] = 'boolean';
		$editors ['f_account_type'] = 'combo_AccountType';
		$editors ['f_email'] = 'text';
		$editors ['f_tel'] = 'text';
		$editors ['f_fax'] = 'text';
		$editors ['f_mobile'] = 'text';
		$editors ['f_is_expired'] = 'boolean';
		$editors ['f_expired'] = 'date';
		$editors ['f_limit_access'] = 'boolean';
		$editors ['f_access_from'] = 'text';
		$editors ['f_access_to'] = 'text';
		$editors ['f_ldap_bind'] = 'boolean';
		$editors ['f_ldap_cn'] = 'text';
		$editors ['f_dms_home'] = 'text';
		$editors ['f_strict_to_home'] = 'boolean';
		$editors ['f_force_change_pwd'] = 'boolean';
		$editors ['f_force_change_pwd_timestamp'] = 'date';
		$editors ['f_unable_to_change_pwd'] = 'boolean';
		$editors ['f_password_hint_question'] = 'text';
		$editors ['f_password_hint_answer'] = 'text';
		return $editors;
	}
	
	public function getAccountProperty($lang = 'en') {
		$property = Array ();
		switch ($lang) {
			case 'en' :
				$property ['f_acc_id'] = 'Account ID';
				$property ['f_login_name'] = 'Login Name';
				$property ['f_login_password'] = 'Password';
				$property ['f_name'] = 'First Name';
				$property ['f_mid_name'] = 'Middle Name';
				$property ['f_last_name'] = 'Last Name';
				$property ['f_rank_id'] = 'Rank';
				$property ['f_tries'] = 'Failed Login Attempt';
				$property ['f_last_change_pwd'] = 'Last Change Password';
				$property ['f_status'] = 'Status';
				$property ['f_account_type'] = 'Account Type';
				$property ['f_email'] = 'E-Mail';
				$property ['f_tel'] = 'Telephone';
				$property ['f_fax'] = 'Fax';
				$property ['f_mobile'] = 'Mobile';
				$property ['f_is_expired'] = 'Is Expired';
				$property ['f_expired'] = 'Expire Date';
				$property ['f_limit_access'] = 'Is Limit Access';
				$property ['f_access_from'] = 'Access From';
				$property ['f_access_to'] = 'Access To';
				$property ['f_ldap_bind'] = 'LDAP Binding';
				$property ['f_ldap_cn'] = 'LDAP Common Name';
				$property ['f_dms_home'] = 'DMS Home Folder';
				$property ['f_strict_to_home'] = 'Lock to home folder';
				$property ['f_force_change_pwd'] = 'Force change password';
				$property ['f_force_change_pwd_timestamp'] = 'Force change timestamp';
				$property ['f_unable_to_change_pwd'] = 'Lock change password';
				$property ['f_password_hint_question'] = 'Password hint';
				$property ['f_password_hint_answer'] = 'Password answer';
				break;
		}
		return $property;
	}
	
	public function getFormStructureMapping($mapping, $type = 'field') {
		if ($type == 'field') {
			switch ($mapping) {
				case 'f_form_id' :
					return 1201;
					break;
				case 'f_form_version' :
					return 1202;
					break;
				case 'f_struct_id' :
					return 1203;
					break;
				case 'f_struct_name' :
					return 1204;
					break;
				case 'f_struct_type' :
					return 1205;
					break;
				case 'f_struct_group' :
					return 1206;
					break;
				case 'f_data_type' :
					return 1207;
					break;
				case 'f_use_lookup' :
					return 1208;
					break;
				case 'f_lookup_id' :
					return 1209;
					break;
				case 'f_struct_param' :
					return 1210;
					break;
				case 'f_initial_value' :
					return 1211;
					break;
				case 'f_is_title' :
					return 1212;
					break;
				case 'f_is_desc' :
					return 1213;
					break;
				case 'f_is_keyword' :
					return 1214;
					break;
				case 'f_allow_search' :
					return 1215;
					break;
				case 'f_is_doc_no' :
					return 1216;
					break;
				case 'f_is_doc_date' :
					return 1217;
					break;
				case 'f_is_required' :
					return 1218;
					break;
				case 'f_is_colored' :
					return 1219;
					break;
				case 'f_color' :
					return 1220;
					break;
				case 'f_is_validate' :
					return 1221;
					break;
				case 'f_validate_fn' :
					return 1222;
					break;
				case 'f_is_sender_text' :
					return 1223;
					break;
				case 'f_is_receiver_text' :
					return 1224;
					break;
				case 'f_bookmark' :
					return 1225;
					break;
				case 'f_is_readonly' :
					return 1226;
					break;
				case 'f_is_hide' :
					return 1227;
					break;
			}
		} else {
			switch ($mapping) {
				case 1201 :
					return 'f_form_id';
					break;
				case 1202 :
					return 'f_form_version';
					break;
				case 1203 :
					return 'f_struct_id';
					break;
				case 1204 :
					return 'f_struct_name';
					break;
				case 1205 :
					return 'f_struct_type';
					break;
				case 1206 :
					return 'f_struct_group';
					break;
				case 1207 :
					return 'f_data_type';
					break;
				case 1208 :
					return 'f_use_lookup';
					break;
				case 1209 :
					return 'f_lookup_id';
					break;
				case 1210 :
					return 'f_struct_param';
					break;
				case 1211 :
					return 'f_initial_value';
					break;
				case 1212 :
					return 'f_is_title';
					break;
				case 1213 :
					return 'f_is_desc';
					break;
				case 1214 :
					return 'f_is_keyword';
					break;
				case 1215 :
					return 'f_allow_search';
					break;
				case 1216 :
					return 'f_is_doc_no';
					break;
				case 1217 :
					return 'f_is_doc_date';
					break;
				case 1218 :
					return 'f_is_required';
					break;
				case 1219 :
					return 'f_is_colored';
					break;
				case 1220 :
					return 'f_color';
					break;
				case 1221 :
					return 'f_is_validate';
					break;
				case 1222 :
					return 'f_validate_fn';
					break;
				case 1223 :
					return 'f_is_sender_text';
					break;
				case 1224 :
					return 'f_is_receiver_text';
					break;
				case 1225 :
					return 'f_bookmark';
					break;
				case 1226 :
					return 'f_is_readonly';
					break;
				case 1227 :
					return 'f_is_hide';
					break;
			
			}
		}
		return 0;
	}
	
	public function getFormStructurePropertyEditor() {
		$editors = Array ();
		$editors ['f_form_id'] = 'text';
		$editors ['f_form_version'] = 'text';
		$editors ['f_struct_id'] = 'text';
		$editors ['f_struct_name'] = 'text';
		$editors ['f_struct_type'] = 'combo_StructureType';
		$editors ['f_struct_group'] = 'text';
		$editors ['f_data_type'] = 'combo_DataType';
		$editors ['f_use_lookup'] = 'boolean';
		$editors ['f_lookup_id'] = 'text';
		$editors ['f_struct_param'] = 'text';
		$editors ['f_initial_value'] = 'text';
		$editors ['f_is_title'] = 'boolean';
		$editors ['f_is_desc'] = 'boolean';
		$editors ['f_is_keyword'] = 'boolean';
		$editors ['f_allow_search'] = 'boolean';
		$editors ['f_is_doc_no'] = 'boolean';
		$editors ['f_is_doc_date'] = 'boolean';
		$editors ['f_is_required'] = 'boolean';
		$editors ['f_is_colored'] = 'boolean';
		$editors ['f_color'] = 'text';
		$editors ['f_is_validate'] = 'boolean';
		$editors ['f_validate_fn'] = 'text';
		$editors ['f_is_sender_text'] = 'boolean';
		$editors ['f_is_receiver_text'] = 'boolean';
		$editors ['f_bookmark'] = 'text';
		$editors ['f_is_readonly'] = 'boolean';
		$editors ['f_is_hide'] = 'boolean';
		
		return $editors;
	}
	
	public function getFormStructureProperty($lang = 'en') {
		$property = Array ();
		switch ($lang) {
			case 'en' :
				$property ['f_form_id'] = 'Form ID';
				$property ['f_form_version'] = 'Form Version';
				$property ['f_struct_id'] = 'Structure ID';
				$property ['f_struct_name'] = 'Structure Name';
				$property ['f_struct_type'] = 'Structure Type';
				$property ['f_struct_group'] = 'Structure Group';
				$property ['f_data_type'] = 'Data Type';
				$property ['f_use_lookup'] = 'Use Lookup';
				$property ['f_lookup_id'] = 'Lookup';
				$property ['f_struct_param'] = 'Parameter';
				$property ['f_initial_value'] = 'Initial value';
				$property ['f_is_title'] = 'Is Title';
				$property ['f_is_desc'] = 'Is Description';
				$property ['f_is_keyword'] = 'Is Keyword';
				$property ['f_allow_search'] = 'Allow Search';
				$property ['f_is_doc_no'] = 'Is Doc.No.';
				$property ['f_is_doc_date'] = 'Is Doc.Date';
				$property ['f_is_required'] = 'Is Require';
				$property ['f_is_colored'] = 'Is Colored';
				$property ['f_color'] = 'Color';
				$property ['f_is_validate'] = 'Is Validate';
				$property ['f_validate_fn'] = 'Validate Function';
				$property ['f_is_sender_text'] = 'Is Sender Text';
				$property ['f_is_receiver_text'] = 'Is Receiver Text';
				$property ['f_bookmark'] = 'Bookmark';
				$property ['f_is_readonly'] = 'Readonly';
				$property ['f_is_hide'] = 'Hidden Field';
				
				break;
		}
		return $property;
	}
	
	public function getPolicyMappingPropertyGroup($property) {
		if ($_SESSION ['appLang'] == 'en') {
			switch ($property) {
				case 'f_fm_master' :
					return 'Form Management';
					break;
				case 'f_fm_create' :
					return 'Form Management';
					break;
				case 'f_fm_modify' :
					return 'Form Management';
					break;
				case 'f_fm_design' :
					return 'Form Management';
					break;
				case 'f_fm_delete' :
					return 'Form Management';
					break;
				case 'f_fm_disable' :
					return 'Form Management';
					break;
				case 'f_fm_script' :
					return 'Form Management';
					break;
				case 'f_fm_import' :
					return 'Form Management';
					break;
				case 'f_fm_export' :
					return 'Form Management';
					break;
				case 'f_fm_copy' :
					return 'Form Management';
					break;
				case 'f_wf_access' :
					return 'Workflow Usage';
					break;
				case 'f_wf_master' :
					return 'Workflow Management';
					break;
				case 'f_wf_create' :
					return 'Workflow Management';
					break;
				case 'f_wf_modify' :
					return 'Workflow Management';
					break;
				case 'f_wf_delete' :
					return 'Workflow Management';
					break;
				case 'f_wf_disable' :
					return 'Workflow Management';
					break;
				case 'f_wf_monitor' :
					return 'Workflow Management';
					break;
				case 'f_wf_deploy' :
					return 'Workflow Management';
					break;
				case 'f_wf_script' :
					return 'Workflow Management';
					break;
				case 'f_wf_import' :
					return 'Workflow Management';
					break;
				case 'f_wf_export' :
					return 'Workflow Management';
					break;
				case 'f_wf_copy' :
					return 'Workflow Management';
					break;
				case 'f_wf_approve' :
					return 'Workflow Usage';
					break;
				case 'f_wf_agree' :
					return 'Workflow Usage';
					break;
				case 'f_wf_comment' :
					return 'Workflow Usage';
					break;
				case 'f_wf_attach' :
					return 'Workflow Usage';
					break;
				case 'f_wf_pause' :
					return 'Workflow Usage';
					break;
				case 'f_wf_abort' :
					return 'Workflow Usage';
					break;
				case 'f_wf_secret_lvl' :
					return 'Workflow Usage';
					break;
				case 'f_sb_access' :
					return 'Saraban Usage';
					break;
				case 'f_sb_master' :
					return 'Saraban Usage';
					break;
				case 'f_sb_create' :
					return 'Saraban Usage';
					break;
				case 'f_sb_modify' :
					return 'Saraban Usage';
					break;
				case 'f_sb_delete' :
					return 'Saraban Usage';
					break;
				case 'f_sb_draft' :
					return 'Saraban Usage';
					break;
				case 'f_sb_pause' :
					return 'Saraban Usage';
					break;
				case 'f_sb_abort' :
					return 'Saraban Usage';
					break;
				case 'f_sb_sign' :
					return 'Saraban Usage';
					break;
				case 'f_sb_approve' :
					return 'Saraban Usage';
					break;
				case 'f_sb_agree' :
					return 'Saraban Usage';
					break;
				case 'f_sb_send_int' :
					return 'Saraban Usage';
					break;
				case 'f_sb_send_ext' :
					return 'Saraban Usage';
					break;
				case 'f_sb_send_egif' :
					return 'Saraban Usage';
					break;
				case 'f_sb_recv_int' :
					return 'Saraban Usage';
					break;
				case 'f_sb_recv_ext' :
					return 'Saraban Usage';
					break;
				case 'f_sb_recv_egif' :
					return 'Saraban Usage';
					break;
				case 'f_sb_secret_lvl' :
					return 'Saraban Usage';
					break;
				case 'f_sb_comment' :
					return 'Saraban Usage';
					break;
				case 'f_sb_attach' :
					return 'Saraban Usage';
					break;
				case 'f_sb_doctype' :
					return 'Saraban Usage';
					break;
				case 'f_sb_send_int_global' :
					return 'Saraban Usage';
					break;
				case 'f_sb_command' :
					return 'Saraban Usage';
					break;
				case 'f_sb_sub_doctype' :
					return 'Saraban Usage';
					break;
				case 'f_sb_secret_agent' :
					return 'Saraban Usage';
					break;
				case 'f_dms_access' :
					return 'Document Management';
					break;
				case 'f_dms_create_folder' :
					return 'Document Management';
					break;
				case 'f_dms_modify_folder' :
					return 'Document Management';
					break;
				case 'f_dms_delete_folder' :
					return 'Document Management';
					break;
				case 'f_dms_create_doc' :
					return 'Document Management';
					break;
				case 'f_dms_modify_doc' :
					return 'Document Management';
					break;
				case 'f_dms_delete_doc' :
					return 'Document Management';
					break;
				case 'f_dms_create_shortcut' :
					return 'Document Management';
					break;
				case 'f_dms_modify_shortcut' :
					return 'Document Management';
					break;
				case 'f_quota' :
					return 'Document Management';
					break;
				case 'f_dms_delete_shortcut' :
					return 'Document Management';
					break;
				case 'f_dms_move' :
					return 'Document Management';
					break;
				case 'f_dms_share' :
					return 'Document Management';
					break;
				case 'f_dms_export' :
					return 'Document Management';
					break;
				case 'f_dms_grant' :
					return 'Document Management';
					break;
				case 'f_dms_scan' :
					return 'Document Management';
					break;
				case 'f_quota' :
					return 'Document Management';
					break;
				case 'f_dms_attach' :
					return 'Document Management';
					break;
				case 'f_dms_master' :
					return 'Document Management';
					break;
				case 'f_dms_print' :
					return 'Document Management';
					break;
				case 'f_dms_annotate' :
					return 'Document Management';
					break;
				case 'f_dms_create_folder_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_modify_folder_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_delete_folder_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_view_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_create_doc_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_modify_doc_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_delete_doc_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_create_shortcut_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_modify_shortcut_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_delete_shortcut_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_move_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_share_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_export_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_grant_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_scan_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_attach_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_print_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_dms_annotate_loc' :
					return 'Document Management(LOC)';
					break;
				case 'f_km_access' :
					return 'Knowledge Base';
					break;
				case 'f_km_create' :
					return 'Knowledge Base';
					break;
				case 'f_km_read' :
					return 'Knowledge Base';
					break;
				case 'f_km_update' :
					return 'Knowledge Base';
					break;
				case 'f_km_delete' :
					return 'Knowledge Base';
					break;
				case 'f_km_move' :
					return 'Knowledge Base';
					break;
				case 'f_km_create_cat' :
					return 'Knowledge Base';
					break;
				case 'f_km_update_cat' :
					return 'Knowledge Base';
					break;
				case 'f_km_delete_cat' :
					return 'Knowledge Base';
					break;
				case 'f_reserve_book_no' :
					return 'Saraban Usage';
					break;
				case 'f_sb_flag' :
					return 'Saraban Usage';
					break;
				case 'f_sb_global_search' :
					return 'Saraban Usage';
					break;
				case 'f_rc_access' :
					return 'Reservation Car';
					break;
				case 'f_rc_admin' :
					return 'Reservation Car';
					break;
				case 'f_room_access' :
					return 'Reservation Room';
					break;
				case 'f_room_admin' :
					return 'Reservation Room';
					break;
			}
		} else {
		switch ($property) {
				case 'f_fm_master' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_create' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_modify' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_design' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_delete' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_disable' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_script' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_import' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_export' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_fm_copy' :
					return '�Ѵ���Ẻ�����';
					break;
				case 'f_wf_access' :
					return '�����ҹ���������';
					break;
				case 'f_wf_master' :
					return '�Ѵ������������';
					break;
				case 'f_wf_create' :
					return '�Ѵ������������';
					break;
				case 'f_wf_modify' :
					return '�Ѵ������������';
					break;
				case 'f_wf_delete' :
					return '�Ѵ������������';
					break;
				case 'f_wf_disable' :
					return '�Ѵ������������';
					break;
				case 'f_wf_monitor' :
					return '�Ѵ������������';
					break;
				case 'f_wf_deploy' :
					return '�Ѵ������������';
					break;
				case 'f_wf_script' :
					return '�Ѵ������������';
					break;
				case 'f_wf_import' :
					return '�Ѵ������������';
					break;
				case 'f_wf_export' :
					return '�Ѵ������������';
					break;
				case 'f_wf_copy' :
					return '�Ѵ������������';
					break;
				case 'f_wf_approve' :
					return '�����ҹ���������';
					break;
				case 'f_wf_agree' :
					return '�����ҹ���������';
					break;
				case 'f_wf_comment' :
					return '�����ҹ���������';
					break;
				case 'f_wf_attach' :
					return '�����ҹ���������';
					break;
				case 'f_wf_pause' :
					return '�����ҹ���������';
					break;
				case 'f_wf_abort' :
					return '�����ҹ���������';
					break;
				case 'f_wf_secret_lvl' :
					return '�����ҹ���������';
					break;
				case 'f_sb_access' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_master' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_create' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_modify' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_delete' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_draft' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_pause' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_abort' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_sign' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_approve' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_agree' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_send_int' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_send_ext' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_send_egif' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_recv_int' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_recv_ext' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_recv_egif' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_secret_lvl' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_comment' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_attach' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_doctype' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_send_int_global' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_command' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_sub_doctype' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_secret_agent' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_dms_access' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_create_folder' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_modify_folder' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_delete_folder' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_create_doc' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_modify_doc' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_delete_doc' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_create_shortcut' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_modify_shortcut' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_quota' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_delete_shortcut' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_move' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_share' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_export' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_grant' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_scan' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_quota' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_attach' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_master' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_print' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_annotate' :
					return '�к��Ѵ���͡���';
					break;
				case 'f_dms_create_folder_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_modify_folder_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_delete_folder_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_view_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_create_doc_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_modify_doc_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_delete_doc_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_create_shortcut_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_modify_shortcut_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_delete_shortcut_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_move_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_share_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_export_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_grant_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_scan_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_attach_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_print_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_dms_annotate_loc' :
					return '�к��Ѵ���͡���(LOC)';
					break;
				case 'f_km_access' :
					return '��ѧ�������';
					break;
				case 'f_km_create' :
					return '��ѧ�������';
					break;
				case 'f_km_read' :
					return '��ѧ�������';
					break;
				case 'f_km_update' :
					return '��ѧ�������';
					break;
				case 'f_km_delete' :
					return '��ѧ�������';
					break;
				case 'f_km_move' :
					return '��ѧ�������';
					break;
				case 'f_km_create_cat' :
					return '��ѧ�������';
					break;
				case 'f_km_update_cat' :
					return '��ѧ�������';
					break;
				case 'f_km_delete_cat' :
					return '��ѧ�������';
					break;
				case 'f_reserve_book_no' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_flag' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_sb_global_search' :
					return '��ú�ó����硷�͹ԡ��';
					break;
				case 'f_rc_access' :
					return '�ͧö';
					break;
				case 'f_rc_admin' :
					return '�ͧö';
					break;
				case 'f_room_access' :
					return '�ͧ��ͧ��Ъ��';
					break;
				case 'f_room_admin' :
					return '�ͧ��ͧ��Ъ��';
					break;
			}
		}
		return 0;
	}
	
	public function getFormStructureStore($formID, $formVersion, $storeOutName = 'default') {
		global $config;
		
		if ($storeOutName == 'default') {
			$storeOutName = 'formStructureStore';
		}
		
		$store = "var $storeOutName = new Ext.data.Store({
			        proxy: new Ext.data.ScriptTagProxy({
			            url: '/{$config ['appName']}/data-store/form-structure?formID={$formID}&formVersion={$formVersion}'
			        }),
				
			        // create reader that reads the Topic records
			        reader: new Ext.data.JsonReader({
			            root: 'results',
			            totalProperty: 'total',
			            id: 'structID',
			            fields: [
			                'formID', 
			                'formVersion', 
			                'structID',
			                'structName', 
			                'structType',
			                'structGroup',
			                'dataType',
			                'useLookup',
			                'lookup',
			                'structParam',
			                'initialValue',
			                'isTitle',
			                'isDesc',
			                'isKeyword',
			                'allowSearch',
			                'isDocNo',
			                'isDocDate',
			                'isRequired',
			                'isColored',
			                'color',
			                'isValidate',
			                'validateFunction',
			                'isSenderText',
			                'isReceiverText'
			            ]
			        }),
			        
			        // turn on remote sorting
			        remoteSort: true
			   });";
		
		return $store;
	}
	
	public function getStorageProperty($lang = 'en') {
		$property = Array ();
		switch ($lang) {
			case 'en' :
				$property ['f_st_id'] = 'Storage ID';
				$property ['f_st_name'] = 'Storage Name';
				$property ['f_st_type'] = 'Storage Type';
				$property ['f_st_server'] = 'Server Name';
				$property ['f_st_port'] = 'Server Port';
				$property ['f_st_path'] = 'Storage Path';
				$property ['f_st_uid'] = 'User ID';
				$property ['f_st_pwd'] = 'Password';
				$property ['f_st_limit'] = 'Limit Size';
				$property ['f_st_size'] = 'Size';
				$property ['f_status'] = 'Storage Status';
				$property ['f_default'] = 'Default Storage';
				break;
		}
		return $property;
	}
	
	public function getStoragePropertyEditor() {
		$editors = Array ();
		$editors ['f_st_id'] = 'text';
		$editors ['f_st_name'] = 'text';
		$editors ['f_st_type'] = 'text';
		$editors ['f_st_server'] = 'text';
		$editors ['f_st_port'] = 'text';
		$editors ['f_st_path'] = 'text';
		$editors ['f_st_uid'] = 'text';
		$editors ['f_st_pwd'] = 'text';
		$editors ['f_st_limit'] = 'boolean';
		$editors ['f_st_size'] = 'text';
		$editors ['f_status'] = 'boolean';
		$editors ['f_default'] = 'boolean';
		return $editors;
	}
	
	public function getStorageMapping($mapping, $type = 'field') {
		if ($type == 'field') {
			switch ($mapping) {
				case 'f_st_id' :
					return 1301;
					break;
				case 'f_st_name' :
					return 1302;
					break;
				case 'f_st_type' :
					return 1303;
					break;
				case 'f_st_server' :
					return 1304;
					break;
				case 'f_st_port' :
					return 1305;
					break;
				case 'f_st_path' :
					return 1306;
					break;
				case 'f_st_uid' :
					return 1307;
					break;
				case 'f_st_pwd' :
					return 1308;
					break;
				case 'f_st_limit' :
					return 1309;
					break;
				case 'f_st_size' :
					return 1310;
					break;
				case 'f_status' :
					return 1311;
					break;
				case 'f_default' :
					return 1312;
					break;
			}
		} else {
			switch ($mapping) {
				case 1301 :
					return 'f_st_id';
					break;
				case 1302 :
					return 'f_st_name';
					break;
				case 1303 :
					return 'f_st_type';
					break;
				case 1304 :
					return 'f_st_server';
					break;
				case 1305 :
					return 'f_st_port';
					break;
				case 1306 :
					return 'f_st_path';
					break;
				case 1307 :
					return 'f_st_uid';
					break;
				case 1308 :
					return 'f_st_pwd';
					break;
				case 1309 :
					return 'f_st_limit';
					break;
				case 1310 :
					return 'f_st_size';
					break;
				case 1311 :
					return 'f_status';
					break;
				case 1312 :
					return 'f_default';
					break;
			}
		}
		return 0;
	}
	
	public function getAttachTempStore($tempID, $storeOutName = 'default') {
		global $config;
		if ($storeOutName == 'default') {
			$storeOutName = 'documentTypeStore';
		}
		$store = "var $storeOutName = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/data-store/attach-temp?tempID={$tempID}'
			}),
			
			reader: new Ext.data.JsonReader({
				root: 'images',
			    totalProperty: 'total',
			    id: 'id',
			    fields: [
			    	'id',
			    	'docid',
			    	'pageid',
			    	'name', 
			    	'url', 
			    	{name:'size', type: 'float'}, 
			    	{name:'lastmod', type:'date', dateFormat:'timestamp'}
			    ]
			    //fields: [
			    //    'id', 'global', 'orgid','name','desc','status'
			    //]
			}),
			        
			// turn on remote sorting
			remoteSort: true
		});";
		return $store;
	}
	
	public function getAttachStore($docID, $storeOutName = 'default') {
		global $config;
		if ($storeOutName == 'default') {
			$storeOutName = "attachment{$docID}Store";
		}
		$store = "var $storeOutName = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/data-store/attach?docID={$docID}'
			}),
			
			reader: new Ext.data.JsonReader({
				root: 'images',
			    totalProperty: 'total',
			    id: 'id',
			    fields: [
			    	'id',
			    	'docid',
			    	'pageid',
			    	'name', 
			    	'url', 
			    	'version',
			    	'sizeStr',
			    	'owner',
			    	'date', 
					'createuid',
			    	{name:'size', type: 'float'}, 
			    	{name:'lastmod', type:'date', dateFormat:'timestamp'}
			    ]
			    //fields: [
			    //    'id', 'global', 'orgid','name','desc','status'
			    //]
			}),
			        
			// turn on remote sorting
			remoteSort: true
		});";
		return $store;
	}
	
	public function getAnnounceAttachStore($docID, $storeOutName = 'default') {
		global $config;
		if ($storeOutName == 'default') {
			$storeOutName = "attachment{$docID}Store";
		}
		$store = "var $storeOutName = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/data-store/announce-attach?docID={$docID}'
            }),
            
            reader: new Ext.data.JsonReader({
                root: 'images',
                totalProperty: 'total',
                id: 'id',
                fields: [
                    'id',
                    'docid',
                    'pageid',
                    'name', 
                    'url', 
                    'version',
			    	'sizeStr',
			    	'owner',
			    	'date', 
                    {name:'size', type: 'float'}, 
                    {name:'lastmod', type:'date', dateFormat:'timestamp'}
                ]
                //fields: [
                //    'id', 'global', 'orgid','name','desc','status'
                //]
            }),
                    
            // turn on remote sorting
            remoteSort: true
        });";
		return $store;
	}
	
	public function getContactListStore($userID, $storeOutName = 'default') {
		global $config;
		if ($storeOutName == 'default') {
			$storeOutName = "contactList_{$userID}_Store";
		}
		$store = "var $storeOutName = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/data-store/contact-lists?userID={$userID}'
			}),
			
			reader: new Ext.data.JsonReader({
				root: 'contact',
			    totalProperty: 'total',
			    id: 'id',
			    fields: [
			    	'id',
			    	'group',
			    	'type',
			    	'name', 
			    	'url', 
			    	{name:'size', type: 'float'}, 
			    	{name:'lastmod', type:'date', dateFormat:'timestamp'}
			    ]
			    //fields: [
			    //    'id', 'global', 'orgid','name','desc','status'
			    //]
			}),
			        
			// turn on remote sorting
			remoteSort: true
		});";
		return $store;
	}
	
	public function getSecureGroupStore($DMSID, $storeOutName = 'default') {
		global $config;
		if ($storeOutName == 'default') {
			$storeOutName = "secureGroupStore";
		}
		$store = "var $storeOutName = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/security-store/get-secure-group?id={$DMSID}'
			}),
			
			reader: new Ext.data.JsonReader({
				root: 'securegroup',
			    totalProperty: 'total',
			    id: 'id',
			    fields: [
			    	'id',
			    	'name', 
			    	'active', 
			    	'inherit', 
			    ]
			}),
			        
			// turn on remote sorting
			remoteSort: true
		});";
		return $store;
	}
	
	public function getSecurePropertyStore($storeOutName = 'default') {
		global $config;
		if ($storeOutName == 'default') {
			$storeOutName = "secureGroupStore";
		}
		$store = "var $storeOutName = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/security-store/get-secure-property?id=0'
			}),
			
			reader: new Ext.data.JsonReader({
				root: 'secureproperty',
			    totalProperty: 'total',
			    id: 'id',
			    fields: [
			    	'id',
			    	'name', 
			    	'value' 
			    ]
			}),
			        
			// turn on remote sorting
			remoteSort: true
		});";
		return $store;
	}
	
	public function getSecureMemberStore($storeOutName = 'default') {
		global $config;
		if ($storeOutName == 'default') {
			$storeOutName = "secureMemberStore";
		}
		$store = "var $storeOutName = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/security-store/get-secure-member'
			}),
			
			reader: new Ext.data.JsonReader({
				root: 'securemember',
			    totalProperty: 'total',
			    id: 'dataid',
			    fields: [
			    	'dataid',
			    	'name', 
			    	'description', 
			    	'typeid', 
			    	'allow' 
			    ]
			}),
			        
			// turn on remote sorting
			remoteSort: true
		});";
		return $store;
	}
}