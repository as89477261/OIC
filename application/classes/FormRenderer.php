<?php
/**
 * Class FormRenderer �ӡ���ʴ��ſ����
 * 
 * @author Mahasak Pijittum
 * @version 1.0
 * @package classes
 * @category Form
 */
class FormRenderer {
	/**
	 * ���ҧ Tag Input �ͧ Field Type ��ҧ�
	 *
	 * @param int $id
	 * @param int $type
	 * @param string $name
	 * @return string
	 */
	public function create($id,$type,$name) {
		switch($type) {
			default:
				$tag= "<input type=\"text\" id=\"$id\" name=\"$name\" />";
				break;
		}
		
		return $tag;
	}
}
