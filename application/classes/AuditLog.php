<?php

/**
 * Class ºÑ¹·Ö¡ Audit Log
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Logger 
 */
class AuditLog {
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè ÃĞºº ECM
	 *
	 */
	const ECMSYSTEM = 0;
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè µÙé
	 *
	 */
	const CABINET = 1;
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè ÅÔé¹ªÑ¡
	 *
	 */
	const DRAWER = 2;
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè á¿éÁ
	 *
	 */
	const FOLDER = 3;
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè àÍ¡ÊÒÃ
	 *
	 */
	const DOCUMENT = 4;
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè àÅèÁ·ĞàºÕÂ¹
	 *
	 */
	const REGBOOK = 5;
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè Transaction §Ò¹ÊÒÃºÃÃ³
	 *
	 */
	const DOCFLOWTRANS = 6;
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè Transaction §Ò¹ Workflow
	 *
	 */
	const WORKFLOWTRANS = 7;
    
	/**
	 * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Access Object
	 *
	 */
    const ACTIVITY_ACCESS = 0;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Create Object
     *
     */
    const ACTIVITY_CREATE = 1;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Modify Object
     *
     */
    const ACTIVITY_MODIFY = 2;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Delete Object
     *
     */
    const ACTIVITY_DELETE = 3;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Audit Object
     *
     */
    const ACTIVITY_AUDIT = 4;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ Í×è¹æ
     *
     */
    const ACTIVITY_OTHER = 5;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ·Ó Permanent Delete Object
     *
     */
    const ACTIVITY_DELETE_PERMANENT = 6;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Move Object ä» My Document
     *
     */
    const ACTIVITY_MOVE_TO_MYDOC = 7;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Move Object ä» DMS 
     *
     */
    const ACTIVITY_MOVE_TO_DMS = 8;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Move Object
     *
     */
    const ACTIVITY_MOVE = 9;
    /**
     * ÃËÑÊ¤èÒ¤§·Õè ¡Ô¨¡ÃÃÁ¡ÒÃ Restore Object
     *
     */
    const ACTIVITY_MOVE_RESTORE = 10;
	
    /**
     * Class Constructor
     *
     */
	public function __construct() {
		global $sequence;
		if(!$sequence->isExists('auditLogID')) {
			$sequence->create('auditLogID');
		}
	}
	
	/**
	 * ºÑ¹·Ö¡ Audit Log
	 *
	 * @param int $objType
	 * @param int $objID
	 * @param string $message
	 * @param int $type
	 */
	public function log($objType,$objID,$message = '',$type=0) {
		global $sessionMgr;
		global $sequence;
		global $util;

		/**
		 * object type
		 * 1 = Cabinet
		 * 2 = Drawer
		 * 3 = Folder
		 * 4 = Document
		 * 5 = Register Book
		 * 6 = DocFlow Transaction
		 * 7 = Workflow Transaction
		 */
		$log = new LogAuditEntity();
		$auditLogID = $sequence->get('auditLogID');
		$log->f_audit_log_id = $auditLogID;
		$log->f_acc_id = $sessionMgr->getCurrentAccID();
		$log->f_object_id = $objID;
		$log->f_object_type = $objType;
		$log->f_message = $message;
		$log->f_timestamp = time();
		$log->f_ip_address = $util->getIPAddress();
		$log->f_archived = 0;
        $log->f_activity_type = $type;
		$log->Save();
	}
}
