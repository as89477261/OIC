<?php
/**
 * Class สำหรับสร้าง Store ของระบบ DMS
 * @author Arthit Boonyakiet
 * @version 1.0.0
 * @package classes
 * @category Store Generator Class
 */
class DMSStore {
	/**
	 * Store สำหรับการค้นหา DMS
	 *
	 * @param string $keySearch
	 * @param string $storeName
	 * @param string $formParam
	 * @param string $qMode
	 * @return string
	 */
	public function getSearchResultDMS($keySearch, $storeName = 'searchResultDMSStore',$formParam='',$qMode='normal', $params = '') {
		global $config;

		if($formParam != '') {
			$formParam = "&formParam={$formParam}";
		} else {
			$formParam = "";
		}

        if (!empty($params)) {
			$params = http_build_query($params);
		}
        
		$store = "var {$storeName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/dms-data/search-result?{$params}&keySearch={$keySearch}{$formParam}&qMode={$qMode}'
            , timeout: 300000}),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id',
                fields: [
                	'f_obj_id',
                	'f_doc_id',
                    'f_name', 
                    'f_description',
                    'f_keyword',
                    'f_location', 
                    'f_obj_type_image', 
                    'f_obj_type', 
                    'f_created_date',
                    'f_created_time',
                    'f_last_update_date',
                    'f_last_update_time',
                    'f_expire_date',
                    'f_expire_time'
                ]
            })
            ,groupField: 'f_obj_type'        
            // turn on remote sorting
            //,remoteSort: true
        });";
		
		return $store;
	}
	
	/**
	 * Store สำหรับการค้นหา DMS ด้วย Loan Key (PDMO Project)
	 *
	 * @param string $keySearch
	 * @param string $storeName
	 * @param string $formParam
	 * @param string $qMode
	 * @return string
	 */
	public function getSearchLoanRequest($keySearch, $storeName = 'searchResultDMSStore',$formParam='',$qMode='normal') {
		global $config;
		if($formParam != '') {
			$formParam = "&formParam={$formParam}";
		} else {
			$formParam = "";
		}
		$store = "var {$storeName} = new Ext.data.GroupingStore({
            proxy: new Ext.data.ScriptTagProxy({
                url: '/{$config ['appName']}/dms-data/search-loan-request?keySearch={$keySearch}{$formParam}&qMode={$qMode}'
            }),
            
            // create reader that reads the Topic records
            reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id',
                fields: [
                	'f_doc_id',
                	'f_form_id',
                	'f_struct1',
                    'f_struct2',
                    'f_struct3',
                    'f_struct4',
                    'f_struct5',
                    'f_struct6',
                    'f_struct7'
                ]
            })
            //,groupField: 'f_doc_id'        
            // turn on remote sorting
            //,remoteSort: true
        });";
		
		return $store;
	}
	
	public function getBorrowItemStore($storeOutName = 'BorrowItemStore') {
		global $config;
		
		$store = "var {$storeOutName} = new Ext.data.GroupingStore({
			proxy: new Ext.data.ScriptTagProxy({
				url: '/{$config ['appName']}/dms-data/borrow'
			}),
			// create reader that reads the Topic records
			reader: new Ext.data.JsonReader({
				root: 'results',
			    totalProperty: 'total',
			    id: 'borrowID',
			    fields: [
			    	'borrowID', 
			    	'docID', 
			    	'docNo',
			    	'title',
			    	'borrower', 
			    	'dueDate',
			    	'detail'
			    ]
			})
			,groupField: 'borrower'
			// turn on remote sorting
			,remoteSort: true
		});";
		
		return $store;
	}
}
