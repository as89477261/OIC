<?php

/**
 * Class �ѹ�֡ Audit Log
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Logger 
 */
class AuditLog {
	/**
	 * ���ʤ�Ҥ���� �к� ECM
	 *
	 */
	const ECMSYSTEM = 0;
	/**
	 * ���ʤ�Ҥ���� ���
	 *
	 */
	const CABINET = 1;
	/**
	 * ���ʤ�Ҥ���� ��鹪ѡ
	 *
	 */
	const DRAWER = 2;
	/**
	 * ���ʤ�Ҥ���� ���
	 *
	 */
	const FOLDER = 3;
	/**
	 * ���ʤ�Ҥ���� �͡���
	 *
	 */
	const DOCUMENT = 4;
	/**
	 * ���ʤ�Ҥ���� ��������¹
	 *
	 */
	const REGBOOK = 5;
	/**
	 * ���ʤ�Ҥ���� Transaction �ҹ��ú�ó
	 *
	 */
	const DOCFLOWTRANS = 6;
	/**
	 * ���ʤ�Ҥ���� Transaction �ҹ Workflow
	 *
	 */
	const WORKFLOWTRANS = 7;
    
	/**
	 * ���ʤ�Ҥ���� �Ԩ������� Access Object
	 *
	 */
    const ACTIVITY_ACCESS = 0;
    /**
     * ���ʤ�Ҥ���� �Ԩ������� Create Object
     *
     */
    const ACTIVITY_CREATE = 1;
    /**
     * ���ʤ�Ҥ���� �Ԩ������� Modify Object
     *
     */
    const ACTIVITY_MODIFY = 2;
    /**
     * ���ʤ�Ҥ���� �Ԩ������� Delete Object
     *
     */
    const ACTIVITY_DELETE = 3;
    /**
     * ���ʤ�Ҥ���� �Ԩ������� Audit Object
     *
     */
    const ACTIVITY_AUDIT = 4;
    /**
     * ���ʤ�Ҥ���� �Ԩ���� ����
     *
     */
    const ACTIVITY_OTHER = 5;
    /**
     * ���ʤ�Ҥ���� �Ԩ������÷� Permanent Delete Object
     *
     */
    const ACTIVITY_DELETE_PERMANENT = 6;
    /**
     * ���ʤ�Ҥ���� �Ԩ������� Move Object � My Document
     *
     */
    const ACTIVITY_MOVE_TO_MYDOC = 7;
    /**
     * ���ʤ�Ҥ���� �Ԩ������� Move Object � DMS 
     *
     */
    const ACTIVITY_MOVE_TO_DMS = 8;
    /**
     * ���ʤ�Ҥ���� �Ԩ������� Move Object
     *
     */
    const ACTIVITY_MOVE = 9;
    /**
     * ���ʤ�Ҥ���� �Ԩ������� Restore Object
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
	 * �ѹ�֡ Audit Log
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
