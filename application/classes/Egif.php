<?php

class Egif {
	
	public function __construct() {
		$this->orgArray = array(
			'�ӹѡ�ҹ��º�����Ἱ��Ѿ�ҡø����ҵ�������.',
			'�ӹѡ�ҹ�ҵðҹ˹��§ҹ�Ҫ���',
			'�ӹѡ�ҹ��С�����áӡѺ������������û�Сͺ��áԨ��Сѹ���'
		);
		
		$this->orgUDDIArray = array(
			'�ӹѡ�ҹ��º�����Ἱ��Ѿ�ҡø����ҵ�������.'=>'www.onep.go.th',
			'�ӹѡ�ҹ�ҵðҹ˹��§ҹ�Ҫ���'=>'www.govt.go.th',
			'�ӹѡ�ҹ��С�����áӡѺ������������û�Сͺ��áԨ��Сѹ���'=>'www.oic.or.th'
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

