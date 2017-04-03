<?php
/**
 * Class สำหรับบันทึก File ลง DAV Storage
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category Storage Class
 */
class DAVStorage {
	/**
	 * DAV Server connection
	 *
	 * @var resource
	 */
	private $_conn;
	/**
	 * DAV Server Path
	 *
	 * @var string
	 */
	private $_path;
	/**
	 * ID ของ Storage
	 *
	 * @var int
	 */
    private $_stid;
    
    /**
     * ทำการโหลด library NetHTTCLient
     *
     */
    public function __construct() {
    	loadExternalLib('HTTPClient');
    }
    
    /**
     * เชื่อมต่อไปยัง Default Storage
     *
     */
    public function connectToDefault() {
        $storage = new StorageEntity();
        $storage->Load("f_default = 1");
        $this->connect($storage->f_st_server,$storage->f_st_uid,$storage->f_st_pwd,$storage->f_st_path, $storage->f_st_port);
        $this->_stid = $storage->f_st_id;
    }
    
    /**
     * ตัดการเชื่อมต่อ
     *
     */
    public function disconnect() {
        $this->_conn->Disconnect();
    }
    
    /**
     * ขอรหัส Storage
     *
     * @return int
     */
    public function getStorageID() {
        return $this->_stid;
    }
    
    /**
     * ทำการเชื่อมต่อไปยัง Storage
     *
     * @param string $server
     * @param string $uid
     * @param string $pwd
     * @param string $path
     * @param int $port
     */
	function connect($server,$uid,$pwd,$path,$port=80) {
		$this->_conn = new Net_HTTP_Client();
		$this->_conn->setCredentials( $uid, $pwd );
		$this->_conn->connect( $server, $port ) or die( "connect problem ".$server.":".$port );
		$status = $this->_conn->get( "/{$path}/davroot.txt" );
		
		if( $status != 200 ) {
			die( "Problem : " . $this->_conn->getStatusMessage() . "\n" );
			//$this->_conn->disconnect();
		}
		$this->_path = $path;
	}
	
	/**
	 * ขอ Propert ของ File ใน Storage
	 *
	 * @param string $storageName
	 * @param string $fileName
	 * @return mixed
	 */
	function property($storageName, $fileName) {
		$statusFindProps = $this->_conn->PropFind( "/{$this->_path}/{$storageName}/$fileName" );
		if(substr($statusFindProps,0,1) != '2') {
			return false;
		} else {
			$props = $this->_conn->getBody();
			return $props;
		}		
	}
	
	/**
	 * ดึง File Content จาก Storage
	 *
	 * @param string $storageName
	 * @param string $fileName
	 * @return string
	 */
	function load($storageName,$fileName) {
		$status = $this->_conn->get( "/{$this->_path}/{$storageName}/$fileName" );
		
		if( $status != 200 ) {
			return false;
			/*
			die( "Problem : " . $this->_conn->getStatusMessage() . "\n" );
			$this->_conn->disconnect();
			*/
		}
		return $this->_conn->getBody();
	}
	
	/**
	 * ลบ File จาก Storage
	 *
	 * @param string $storageName
	 * @param string $fileName
	 * @return boolean
	 */
	function delete($storageName, $fileName) {
		//$statusDeleteFile = $this->_conn->delete("/{$this->_path}/{$storageName}/$fileName",$fileContent);
		$statusDeleteFile = $this->_conn->delete("/{$this->_path}/{$storageName}/$fileName");
		if(substr($statusDeleteFile,0,1) != '2') {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * สร้าง Folder ใน Storage
	 *
	 * @param string $folderName
	 * @return boolean
	 */
	function mkdir($folderName) {
		$statusCreateFolder = $this->_conn->MkCol ( "/{$this->_path}/{$folderName}" );
		if (substr($statusCreateFolder,0,1) != '2') {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * บันทึก File ลงไปใน Storage
	 *
	 * @param string $storageName
	 * @param string $fileName
	 * @param string $fileContent
	 * @return boolean
	 */
	function save($storageName,$fileName,$fileContent) {
		$statusDir = $this->_conn->PropFind("/{$this->_path}/{$storageName}",1);
		if(substr($statusDir,0,1) == '4') {
			$statusCreateDir = $this->_conn->MkCol( "/{$this->_path}/{$storageName}" );
			if(substr($statusCreateDir,0,1) != '2') {
				return false;
			}	 
		}		
		$statusSaveFile = $this->_conn->put("/{$this->_path}/{$storageName}/$fileName",$fileContent);
		if(substr($statusSaveFile,0,1) != '2') {
			return false;
		} else {
			return true;
		}
	}
}