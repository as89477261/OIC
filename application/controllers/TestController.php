<?php
                                                        
/**
 * Test
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Test
 *
 */
class TestController extends ECMController {
	public function init() {
		
	}
	
	public function replicationAction() {
		
	}
	
	public function saveDavAction () {
		
		$dav = getDefaultStorage();                    
		
		if(!$dav->save('777/CCC','test32.jpg',file_get_contents('d:/test.jpg'))) {
			echo "save failed";
		} else {
            echo "save success";
        }
	}
	
	public function viewDavAction() {
		$dav = getDefaultStorage();  
		$content = $dav->load('777','test32.jpg');
		header('Content-Type: image/jpeg');
		echo $content;
	}
	
	public function deleteDavAction() {
		$dav = getDefaultStorage();  
		$dav->delete('777','test32.jpg');
	}
	
	public function propertyDavAction() {
		$dav = getDefaultStorage();  
		$xmlProperty = $dav->property('777','test32.jpg');
		echo $xmlProperty;
	}
}
