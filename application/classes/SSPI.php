<?php

Class SSPI {
	private $domain;
	private $username;
	private $noDomain;
	
	public function SSPI() {
		//var_dump($_SERVER);
		$this->noDomain = false;
		$cred = explode('\\',$_SERVER['REMOTE_USER']);
		if (count($cred) == 1) {
			array_unshift($cred, "(no domain info - perhaps SSPIOmitDomain is On)");
			$this->noDomain = true;
		} else {
			//echo "xxxx";
		}
		//list($domain, $user) = $cred;
		//die($domain);

		list($this->domain, $this->username) = $cred;
		
	}

	public function noDomain() {
		return $this->noDomain;
	}

	public function getUsername() {
		return $this->username;
	}
	
	public function getDomain() {
		return $this->domain;
	}
	
	public static function getInstance() {
		static $instance;

		if(!isset($instance)) {
			$instance = new SSPI();
		}

		return $instance;
	}
	
}