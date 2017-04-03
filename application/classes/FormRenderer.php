<?php
/**
 * Class FormRenderer ·Ó¡ÒÃáÊ´§¼Å¿ÍÃìÁ
 * 
 * @author Mahasak Pijittum
 * @version 1.0
 * @package classes
 * @category Form
 */
class FormRenderer {
	/**
	 * ÊÃéÒ§ Tag Input ¢Í§ Field Type µèÒ§æ
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
