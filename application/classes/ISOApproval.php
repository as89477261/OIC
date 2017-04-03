<?php
/**
 * Class ISOApproval สำหรับการทำงาน Approve งาน ISO
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category DMS
 */
class ISOApproval {
	/**
	 * ขอจำนวนงาน ISO ที่ issued
	 *
	 * @return int
	 */
    public function getIssueCount() {
        return 0;
    }
    
    /**
     * ขอจำนวนงาน ISO ที่รอ DAR Request
     *
     * @return int
     */
    public function getDARRequestCount() {
        return 0;
    }
    
    /**
     * ขอจำนวนงาน ISO ที่รอ QMR Request
     *
     * @return int
     */
    public function getQMRRequestCount() {
        return 0;
    }
    
    /**
     * ขอจำนวนงาน ISO ที่รอ ISO Request
     *
     * @return int
     */
    public function getISORequestCount() {
        return 0;
    }
    
    /**
     * ขอจำนวนงาน ISO ที่รอ Publish Request
     *
     * @return int
     */
    public function getPublishRequestCount() {
        return 0;
    }
    
} 
