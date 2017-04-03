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
	 * เชื่อมต่อกับ Storage Server
	 *
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $name
	 */
	public function connect($host,$user,$pass,$name);
	/**
	 * สร้าง Folder/Collection
	 *
	 * @param string $collection
	 */
	public function newCollection($collection);
	/**
	 * บันทึกไฟล์
	 *
	 * @param string $collection
	 * @param string $filename
	 * @param string $filecontent
	 */
	public function addFile($collection,$filename,$filecontent);
	/**
	 * ลบไฟล์
	 *
	 * @param string $collection
	 * @param string $filename
	 */
	public function deleteFile($collection,$filename);
	/**
	 * เรียกไฟล์
	 *
	 * @param string $collection
	 * @param string $filename
	 */
	public function getFile($collection,$filename);
}
