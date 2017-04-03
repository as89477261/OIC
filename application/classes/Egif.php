<?php

class Egif {
	
	public function __construct() {
		$this->orgArray = array(
			'สำนักงานนโยบายและแผนทรัพยากรธรรมชาติและสวล.',
			'สำนักงานมาตรฐานหน่วยงานราชการ',
			'สำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย'
		);
		
		$this->orgUDDIArray = array(
			'สำนักงานนโยบายและแผนทรัพยากรธรรมชาติและสวล.'=>'www.onep.go.th',
			'สำนักงานมาตรฐานหน่วยงานราชการ'=>'www.govt.go.th',
			'สำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย'=>'www.oic.or.th'
		);
	}
	
	public function isEGIFOrganize($org) {
		if(in_array($org,$this->orgArray)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getEgifGatewayHost($org) {
		return '127.0.0.1';
		return $this->orgUDDIArray[$org];
	}
}

