<?php

class FileReplicate {
	private $replicateMember;
	public function getInstance() {
		static $instance;
		if(!isset($instance)) {
			$instance = new FileReplicate();
		}
		return $instance;
	}
	
	public function __construct() {
		global $config;
		$this->replicateMember = $config['clusterMember'];
	}
	
	public function replicate($fileName,$fileContent,$location='application/temp') {
		
	}
}