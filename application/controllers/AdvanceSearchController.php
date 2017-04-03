<?php

class AdvanceSearchController extends ECMController {
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'search' );
		
	}
	
	public function searchAction() {
		echo $this->ECMView->render ( 'search.html' );
	
	}
	
	public function searchFormAction() {
	
	}
	
	public function searchFulltextAction() {
	
	}
}

