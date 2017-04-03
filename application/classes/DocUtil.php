<?php

/**
 * Class Document Utility manage/manipulate Instance of forms
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category DMS
 */
class DocUtil {
	/**
	 * ��Ǩ�ͺ������͡���Ṻ�������
	 *
	 * @param int $docID
	 * @return boolean
	 */
	public function hasAttach($docID) {
		global $config;
		global $conn;
		
		$sqlCheckAttach = "select count(*) as attach_count from tbl_doc_page where f_doc_id = '{$docID}'";
		$rsCheckAttach = $conn->CacheExecute ($config['defaultCacheSecs'], $sqlCheckAttach );
		$checkAttach = $rsCheckAttach->FetchNextObject ();
		if ($checkAttach->ATTACH_COUNT > 0) {
			return true;
		} else {
			return false;
		}
	}
    
	/**
	 * ��Ǩ�ͺ��Ҥ�������͡���Ṻ�������
	 *
	 * @param int $docID
	 * @return boolean
	 */
    public function hasAnnounceAttach($docID) {
        global $config;
        global $conn;
        
        $sqlCheckAttach = "select count(*) as attach_count from tbl_announce_page where f_announce_id = '{$docID}'";
        $rsCheckAttach = $conn->CacheExecute ($config['defaultCacheSecs'], $sqlCheckAttach );
        $checkAttach = $rsCheckAttach->FetchNextObject ();
        if ($checkAttach->ATTACH_COUNT > 0) {
            return true;
        } else {
            return false;
        }
    }
	
    /**
     * ��Ǩ�ͺ��������´�ͧ�͡���
     *
     * @param int $docID
     */
	public function getInfo($docID) {
		
	}
	
	/**
	 * �֧���ҡ Storage
	 *
	 * @param int $docID
	 * @param int $pageID
	 */
	public function loadPageFromStorate($docID, $pageID) {
	
	}
	
	/**
	 * �����ŧ Storage
	 *
	 * @param int $docID
	 * @param int $pageID
	 */
	public function putPageToStorate($docID, $pageID) {
		
	}
}