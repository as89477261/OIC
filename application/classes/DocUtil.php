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
	 * ตรวจสอบว่ามีเอกสารแนบหรือไม่
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
	 * ตรวจสอบว่าคำสั่งมีเอกสารแนบหรือไม่
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
     * ตรวจสอบรายละเอียดของเอกสาร
     *
     * @param int $docID
     */
	public function getInfo($docID) {
		
	}
	
	/**
	 * ดึงไฟล์จาก Storage
	 *
	 * @param int $docID
	 * @param int $pageID
	 */
	public function loadPageFromStorate($docID, $pageID) {
	
	}
	
	/**
	 * เก็บไฟล์ลง Storage
	 *
	 * @param int $docID
	 * @param int $pageID
	 */
	public function putPageToStorate($docID, $pageID) {
		
	}
}