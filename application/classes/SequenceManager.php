<?php
/**
 * Class SequenceManager
 * @author Mahasak Pijittum
 * @version 1.0
 * @package classes
 * @category System 
 */
class SequenceManager {
	/**
	 * Class Constructor
	 *
	 */
	public function __construct() {
		//require_once 'Sequence.Entity.php';
	}
	
	/**
	 * สร้าง Sequence
	 *
	 * @param string $name
	 * @param string $format
	 * @param string $type
	 * @param int $value
	 * @param int $step
	 * @param int $module
	 */
	public function create($name, $format = '{seq}', $type = 'I', $value = 0, $step = 1, $module = 0) {
		global $conn;
		$conn->StartTrans ();
		ADODB_Active_Record::SetDatabaseAdapter($conn);
		$sequenceEntity = new SequenceEntity ( );
		$sequenceEntity->f_seq_name = $name;
		$sequenceEntity->f_seq_value = $value;
		$sequenceEntity->f_seq_format = $format;
		$sequenceEntity->f_seq_last_gen = 0;
		$sequenceEntity->f_seq_module = $module;
		$sequenceEntity->f_seq_step = $step;
		$sequenceEntity->f_seq_type = $type;
		$sequenceEntity->Save ();
		$conn->CompleteTrans ();
	}
	
	/**
	 * ตรวจว่ามี Sequence หรือไม่
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function isExists($name) {
		global $conn;
		$sqlCheckSeqExists = "select count(*) as count_exists from tbl_sequence where f_seq_name = '$name'";
		//Logger::debug($sqlCheckSeqExists);
		//die();
		$rsCheckSeqExists = $conn->Execute ( $sqlCheckSeqExists );
		$rowCheckSeqExists = $rsCheckSeqExists->FetchNextObject ();
		if ($rowCheckSeqExists->COUNT_EXISTS > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Parse Sequence ตาม Format ใน Config
	 *
	 * @param string $format
	 * @param int $sequenceValue
	 * @return string
	 */
	public function parseSequence($format, $sequenceValue) {
		$sequenceResult = $format;
		
		$padConfig = true;
		$pad = 4;
		
		$seqpad =  str_pad($sequenceValue,$pad,0,STR_PAD_LEFT);
		
		$sequenceResult = str_replace ( '{seq}', $sequenceValue, $sequenceResult );
		$sequenceResult = str_replace ( '{seqpad}', $seqpad, $sequenceResult );
		$sequenceResult = str_replace ( '{year}', date('Y')+543, $sequenceResult );
		$sequenceResult = str_replace ( '{month}', date('m'), $sequenceResult );
		$sequenceResult = str_replace ( '{day}', date('d'), $sequenceResult );
		return $sequenceResult;
	}
	
	/**
	 * ดึงค่าสุดท้ายของ Sequence
	 *
	 * @param string $name
	 * @return int
	 */
	public function getLast($name) {
		$sequenceEntity = new SequenceEntity ( );
		$sequenceEntity->Load ( "f_seq_name = '{$name}'" );
		return $this->parseSequence ( $sequenceEntity->f_seq_format, $sequenceEntity->f_seq_value );
	}
	
	/**
	 * run sequence ของ sequence
	 *
	 * @param string $name
	 * @return int
	 */
	public function get($name) {
		global $conn;
        logger::debug($name);

        //Start smart transaction
        $conn->StartTrans ();              
        
        //Lock Record using Select For UPDATE
        $conn->RowLock("tbl_sequence","f_seq_name = '{$name}'");

        //Update Sequence
        $sqlUpdate = "update tbl_sequence set f_seq_value = f_seq_value+1 where f_seq_name = '{$name}'";
        $conn->Execute($sqlUpdate);

        //Load Sequence
		$sequenceEntity = new SequenceEntity ( );
        $sequenceEntity->Load ( "f_seq_name = '{$name}'" );

        //Commit smart transaction
        $conn->CompleteTrans ();  
        
        //Parse sequence
        switch ( $sequenceEntity->f_seq_type ) {
            case 'I' :
				logger::debug($this->parseSequence ( $sequenceEntity->f_seq_format, $sequenceEntity->f_seq_value ));
                return $this->parseSequence ( $sequenceEntity->f_seq_format, $sequenceEntity->f_seq_value );
            break;
            
            default :
                return 0;
            break;
        }
        
		/*
        switch ( $sequenceEntity->f_seq_type) {
			case 'I' :
				$sequenceEntity->f_seq_value = $sequenceEntity->f_seq_value + $sequenceEntity->f_seq_step;
                
				$sequenceEntity->Save ();
				$conn->CompleteTrans ();
				return $this->parseSequence ( $sequenceEntity->f_seq_format, $sequenceEntity->f_seq_value );
			break;
			
			default :
                $conn->CompleteTrans ();
				return 0;
			break;
		}
	    */
	}
	
	/**
	 * ตั้งค่า Sequence
	 *
	 * @param string $name
	 * @param int $value
	 */
	public function set($name,$value) {
		$sequenceEntity = new SequenceEntity ( );
		$sequenceEntity->Load ( "f_seq_name = '{$name}'" );
		$sequenceEntity->f_seq_value = $value;
		$sequenceEntity->Update();
	}
	
	/**
	 * รีเซ็ต sequence
	 *
	 * @param string $name
	 */
	public function reset($name) {
		$sequenceEntity = new SequenceEntity ( );
		$sequenceEntity->Load ( "f_seq_name = '{$name}'" );
		$sequenceEntity->f_seq_value = 0;
		$sequenceEntity->Update();
	}
	
	/**
	 * ลบ Sequence
	 *
	 */
	public function delete() {
        
	}
}
