<?php
/**
 * Class ����Ѻ���ҧ ExtJS Data Store �ͧ�ҹ��ú�ó
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Store Generator Class
 */
class DFStore {
	/**
	 * Store ����Ѻ���٤��ҷ���¹���
	 *
	 * @param string $storeOutName
	 * @return string
	 */
    public function getExecutiveSearchStore($storeOutName = 'OrderStore') {
        global $config;
        $store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-search/executive-search'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'transID',
                fields: [
                    'transID',
                    'docID', 
                    'docNo', 
                    'title',
                    'bookdate',
                    'originalType',
                    'originate',
                    'speed',
                    'secret',
                    'hasAttach',
                    'hold',
                    'isCirc',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'hold',
                    'originType',
					'sendName',
					'recvName'
                ]
            })
            ,groupField: 'originate'     
            ,remoteSort: true
        });";
        return $store;
    }
    
    /**
     * Store ����Ѻ������¡��˹ѧ�����ŧ�Ѻ
     *
     * @param string $storeOutName
     * @return string
     */
	public function getUnreceiveStore($storeOutName = 'UnreceivedStore') {
		global $config;
		
		//$store = "var {$storeOutName} = new Ext.data.Store({
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/unreceive'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'sendType',
			    	'secret', 
			    	'speed',			    	
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
                    'from2',
			    	'to',
			    	'command',
			    	'hasAttach',
			    	'isCirc',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
					'receiveExternalRunning',
                    'hold',
                    'abort',
                    'originType' ,
                    'sendStamp'
			    ]
			})
			,groupField: 'sendType'
			// turn on remote sorting
			,remoteSort: true
		});";
		return $store;
	}
	
	/**
	 * Store ����Ѻ����˹ѧ����觡�Ѻ
	 *
	 * @param string $storeOutName
	 * @return string
	 */
	public function getSendbackStore($storeOutName = 'SendbackStore') {
		global $config;
		
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/sendback'
			}),
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'sendType',
			    	'secret', 
			    	'speed',		
					
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',
			    	'isCirc',
			    	'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'hold',
                    'comment'
			    ]
			})
			,groupField: 'sendType'
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ����˹ѧ��ʹ֧�׹
	 *
	 * @param string $storeOutName
	 * @return string
	 */
	public function getCallbackStore($storeOutName = 'SendbackStore') {
		global $config;
		
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/callback'
			}),
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'sendType',
			    	'secret', 
			    	'speed',			    	
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',
			    	'hasAttach',
			    	'isCirc',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'hold',
                    'comment'
			    ]
			})
			,groupField: 'sendType'
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ����˹ѧ����Ѻ����
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getRIStore($regBookID = 0, $storeOutName = 'RIStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/received-internal?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                    'recvID', 
                    'secret', 
                    'speed',
                    'receiveExternalRunning',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'from2',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'governerApprove',
                    'receivedUser',
                    'receivedStamp',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'isCirc',
                    'abort'
                ]
            })
                    
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ����˹ѧ����Ѻ���¹
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getRCStore($regBookID = 0, $storeOutName = 'RIStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/received-circ?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                    'recvID', 
                    'secret', 
                    'speed',
                    'receiveExternalRunning',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'from2',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'governerApprove',
                    'receivedUser',
                    'receivedStamp',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'isCirc',
                    'abort'
                ]
            })
                    
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ�����Ѻ����¹�Ѻ
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getRSStore($regBookID = 0, $storeOutName = 'RIStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/received-classified?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                    'recvID', 
                    'secret', 
                    'speed',
                    'receiveExternalRunning',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'from2',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'governerApprove',
                    'receivedUser',
                    'receivedStamp',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'isCirc',
                    'abort'
                ]
            })
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ�����Ѻ���㹷���¹�Ѻ
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getCIIStore($regBookID = 0, $storeOutName = 'CIIStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/received-classified-int?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                    'recvID', 
                    'secret', 
                    'speed',
                    'receiveExternalRunning',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'from2',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'governerApprove',
                    'receivedUser',
                    'receivedStamp',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'isCirc',
                    'abort'
                ]
            })
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}

 	/**
	 * Store ����Ѻ�����Ѻ��¹͡����¹�Ѻ
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getCIEStore($regBookID = 0, $storeOutName = 'CIEStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/received-classified-ext?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                    'recvID', 
                    'secret', 
                    'speed',
                    'receiveExternalRunning',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'from2',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'governerApprove',
                    'receivedUser',
                    'receivedStamp',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'isCirc',
                    'abort'
                ]
            })
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}

	/**
	 * Store ����Ѻ�����Ѻ��¹͡
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getREStore($regBookID = 0, $storeOutName = 'REStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/received-external?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'recvID',
			    fields: [
			    	'recvID', 
			    	'secret', 
			    	'speed',
			    	'recvNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'from2',
			    	'to',
			    	'command',
			    	'read',
			    	'forward',
			    	'commit',
			    	'sendback',
			    	'cancel',
			    	'egif',
                    'receivedStamp',
                    'hold',
                    'hasAttach',
                    'governerApprove',
                    'receivedUser',
                    'receivedStamp',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'isCirc',
                    'abort'
			    ]
			})
			        
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ�����Ѻ��¹͡����¹��ҧ
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getRGStore($regBookID = 0, $storeOutName = 'REStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/received-external-global?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'recvID',
			    fields: [
			    	'recvID', 
			    	'secret', 
			    	'speed',
			    	'recvNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',
			    	'read',
			    	'forward',
			    	'commit',
			    	'sendback',
			    	'cancel',
			    	'egif',
                    'hold',
                    'hasAttach',
                    'governerApprove',
                    'receivedUser',
                    'receivedStamp',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'isCirc',
                    'abort'
			    ]
			})
			        
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ����������
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getSIStore($regBookID = 0, $storeOutName = 'SIStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/send-internal?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',
			    	'sendUser',
                    'sendStamp',
                    'receiveUser',
                    'receiveStamp',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'sendStatus',
                    'abort'
			    ]
			})
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
    
	/**
	 * Store ����Ѻ���٤���觻�С��
	 *
	 * @param string $storeOutName
	 * @return string
	 */
    public function getOrderStore($storeOutName = 'OrderStore') {
        global $config;
        $store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/orders'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'sendID',
                fields: [
                    'id',
                    'docno', 
                    'typename', 
                    'catname', 
                    'title',
                    'date',
                    'signuser',
                    'signrole',
                    'orgName',
                    'remark',
                    'hasAttach'
					,'catno'
                ]
            })
            ,groupField: 'catno'  
            // turn on remote sorting
            ,remoteSort: true
        });";
        
        return $store;
    }
	
    /**
     * Store ����Ѻ���������¹
     *
     * @param int $regBookID
     * @param string $storeOutName
     * @return string
     */
	public function getSCStore($regBookID = 0, $storeOutName = 'SCStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/send-circ?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',  
                    'sendUser',
                    'sendStamp',
                    'receiveUser',
                    'receiveStamp',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'sendStatus',
                    'abort'
			    ]
			})
			        
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ�����觷���¹�Ѻ
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getSSStore($regBookID = 0, $storeOutName = 'SIStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/send-classified?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',                      
                    'sendUser',
                    'sendStamp',
                    'receiveUser',
                    'receiveStamp',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'sendStatus',
                    'abort'
			    ]
			})
			        
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ�����觷���¹�Ѻ����
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getCOIStore($regBookID = 0, $storeOutName = 'COIStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/send-classified-int?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',                      
                    'sendUser',
                    'sendStamp',
                    'receiveUser',
                    'receiveStamp',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'sendStatus',
                    'abort'
			    ]
			})
			        
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}

	/**
	 * Store ����Ѻ�����觷���¹�Ѻ��¹͡
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getCOEStore($regBookID = 0, $storeOutName = 'COEStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/send-classified-ext?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',                      
                    'sendUser',
                    'sendStamp',
                    'receiveUser',
                    'receiveStamp',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'sendStatus',
                    'abort'
			    ]
			})
			        
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ��������¹͡
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getSEStore($regBookID = 0, $storeOutName = 'SEStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/send-external?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',                      
                    'sendUser',
                    'sendStamp',
                    'receiveUser',
                    'receiveStamp',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'sendStatus',
                    'abort',
                    'egifRecv'
			    ]
			})
			        
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
    
	/**
	 * Store ����Ѻ���������㹷���¹��ҧ
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
    public function getSGIStore($regBookID = 0, $storeOutName = 'SEStore') {
        global $config;
        $store = "var {$storeOutName} = new Ext.data.Store({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/send-internal-global?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'sendID',
                fields: [
                    'sendID', 
                    'secret', 
                    'speed',
                    'sendNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'to',
                    'command',                      
                    'sendUser',
                    'sendStamp',
                    'receiveUser',
                    'receiveStamp',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'sendStatus',
                    'abort'
                ]
            })
                    
            // turn on remote sorting
            ,remoteSort: true
        });";
        
        return $store;
    }
	
    /**
     * Store ����Ѻ�����觷���¹��ҧ
     *
     * @param int $regBookID
     * @param string $storeOutName
     * @return string
     */
	public function getSGStore($regBookID = 0, $storeOutName = 'SEStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.Store({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/df-data/send-external-global?regBookID={$regBookID}'
			}),
			
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
			    	'to',
			    	'command',                      
                    'sendUser',
                    'sendStamp',
                    'signedUser',
                    'receiveUser',
                    'receiveStamp',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'sendStatus',
                    'abort',
                    'egifRecv'
			    ]
			})
			        
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ�����ʹ��Թ���
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getPersonalReceiveStore($regBookID = 0, $storeOutName = 'personalReceiveStore') {
		global $config;
		
		//$store = "var {$storeOutName} = new Ext.data.Store({
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/personal-received?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                	{name: 'recvID'}, 
                	{name: 'recvType'}, 
                    'secret', 
                    'speed',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'from2',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hasAttach',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'isCirc',
                    'hasReserved',
                    'hold',
                    'returnOwner'
                ]
            })
            ,groupField: 'recvType'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ�������͡
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getOutgoingStore($regBookID = 0, $storeOutName = 'personalOutgoingStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/personal-outgoing?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'sendType',
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
                    'from2',       
			    	'to',
			    	'command',
			    	'hasAttach',
			    	'received',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'hold',
                    'senderOrg',
					'sendStamp'
			    ]
            })
            ,groupField: 'sendType'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ�����觵��
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getForwardStore($regBookID = 0, $storeOutName = 'personalForwardedStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/personal-forward?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
			    totalProperty: 'total',
			    id: 'sendID',
			    fields: [
			    	'sendID', 
			    	'sendType',
			    	'secret', 
			    	'speed',
			    	'sendNo',
			    	'docNo',
			    	'docID',
			    	'title',
			    	'docDate',
			    	'from',
                    'from2',       
			    	'to',
			    	'command',
			    	'hasAttach',
			    	'received',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'hold',
                    'senderOrg',
					'sendStamp'
			    ]
            })
            
            ,groupField: 'sendType'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ���٧ҹ������������
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getPersonalCommittedStore($regBookID = 0, $storeOutName = 'personalCommittedStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/personal-committed?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                    'recvID', 
                    'recvType',
                    'secret', 
                    'speed',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'assigner',
					'closeTime'
                ]
            })
            ,groupField: 'recvType'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ���٧ҹ���Դ����
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getPersonalCompletedStore($regBookID = 0, $storeOutName = 'personalCommittedStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/personal-completed?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                    'recvID', 
                    'recvType',
                    'secret', 
                    'speed',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'receiver',
					'closeTime'
                ]
            })
			,groupField: 'recvType'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ˹ѧ��ͨͧ�Ţ
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getReserveBookStore($regBookID = 0, $storeOutName = 'reserveBookStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/reserve-book?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'sendID',
                fields: [
                    'sendID', 
                    'sendType',
                    'secret', 
                    'speed',
                    'sendNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'receiver',
                    'genIntBookno',
                    'genExtBookno',
					'hasReserved',
					'reserved_id',
					'reserver',
					'ownerExtDocCode',
					'ownerIntDocCode'
                ]
            })
			,groupField: 'sendType'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}	

	/**
	 * Store ����Ѻ����˹ѧ������¹
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getCirBookInternalStore($regBookID = 0, $storeOutName = 'CBIIStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/circbook-internal?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'recvID',
                fields: [
                    'recvID', 
                    'secret', 
                    'speed',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hold',
                    'isCirc',
                    'hasAttach',
                    'governerApprove',
                    'genIntBookno',
                    'genExtBookno',
                    'genExtType',
                    'requestOrder',
                    'requestCommand',
                    'requestAnnounce',
                    'ownerOrgID',
                    'ownerIntDocCode',
                    'ownerExtDocCode',
                    'readstamp'
                ]
            })
			,groupField: 'from'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ���٧ҹ����ͺ�����
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getOrderAssignedStore($regBookID = 0, $storeOutName = 'orderAssignedStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/order-assigned?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'orderID',
                fields: [
                    'orderID', 
                    'recvID',
                    'recvType',
                    'secret', 
                    'speed',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hasAttach',
                    'hold',
                    'receiver'
                ]
            })
            ,groupField: 'recvType'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ���٧ҹ������Ѻ�ͺ����
	 *
	 * @param int $regBookID
	 * @param string $storeOutName
	 * @return string
	 */
	public function getOrderReceivedStore($regBookID = 0, $storeOutName = 'orderReceivedStore') {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/order-received?regBookID={$regBookID}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'orderID',
                fields: [
                    'orderID', 
                    'recvID',
                    'recvType',
                    'secret', 
                    'speed',
                    'recvNo',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'to',
                    'command',
                    'read',
                    'forward',
                    'commit',
                    'sendback',
                    'cancel',
                    'egif',
                    'hasAttach',
                    'hold',
                    'assigner'
                ]
            })
			,groupField: 'recvType'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store ����Ѻ����˹ѧ��͵Դ���
	 *
	 * @param string $storeOutName
	 * @return string
	 */
	public function getTrackItemStore($storeOutName) {
		global $config;
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/df-data/track-item'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'transMainID',
                fields: [
                    'transMainID', 
                    'secret', 
                    'speed',
                    'docNo',
                    'docID',
                    'title',
                    'docDate',
                    'from',
                    'to',
                    'hasAttach',
                    'hold'
                ]
            })
			,groupField: 'secret'
            // turn on remote sorting
            ,remoteSort: true
        });";
		
		return $store;
	}
	
	
}
