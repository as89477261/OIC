<?php
/**
 * Interface IStorage
 * 
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category System
 */
Interface IStorage {
	/**
	 * �������͡Ѻ Storage Server
	 *
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $name
	 */
	public function connect($host,$user,$pass,$name);
	/**
	 * ���ҧ Folder/Collection
	 *
	 * @param string $collection
	 */
	public function newCollection($collection);
	/**
	 * �ѹ�֡���
	 *
	 * @param string $collection
	 * @param string $filename
	 * @param string $filecontent
	 */
	public function addFile($collection,$filename,$filecontent);
	/**
	 * ź���
	 *
	 * @param string $collection
	 * @param string $filename
	 */
	public function deleteFile($collection,$filename);
	/**
	 * ���¡���
	 *
	 * @param string $collection
	 * @param string $filename
	 */
	public function getFile($collection,$filename);
}
