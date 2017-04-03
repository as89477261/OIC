<?php
/**
 * Class ����Ѻ���ҧ Accordian Interface (�����������)
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category UI
 */
class ECMAccordian {
	/**
	 * ���ҧ Accordian JS
	 *
	 * @return string
	 */
	public static function getAccordianList() {
		global $config;
		global $license;
		global $lang;
        $crackLicense = true;
		
		$accordianJS = "";
		//�к���ú�ó
		if ($license->check ( 'SARABAN' ) || $crackLicense) {
			$accordianJS .= "{
				id: 'DFExplorer',
				autoLoad: {url: '/{$config ['appName']}/docflow-explorer/get-ui', scripts: true},
				title: '" . $lang ['workAndTask'] . "',
				autoScroll:true,
                border:false,
                collapsed: true,
                iconCls:'nav'
			}";
		}
		//�к� Workflow
		if ($license->check ( 'WORKFLOW' )) {
			if (trim ( $accordianJS ) != '') {
				$accordianJS .= ",{
					id: 'WFExplorer',
					autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
					title: '" . $lang ['workflowTask'] . "',
					autoScroll:true,
	                border:false,
	                collapsed: true,
	                iconCls:'nav'
				}";
			} else {
				$accordianJS .= "{
					id: 'WFExplorer',
					autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
					title: '" . $lang ['workflowTask'] . "',
					autoScroll:true,
	                border:false,
	                collapsed: true,
	                iconCls:'nav'
				}";
			}
		}
		//�к� �ͧ��ͧ��Ъ��
		if ($license->check ( 'ROOMBOOKING' )) {
			if (trim ( $accordianJS ) != '') {
				$accordianJS .= ",{
					id: 'RoomBookingExplorer',
					//autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
					html : 'RoomBooking',
					title: '" . $lang ['roomBooking'] . "',
					autoScroll:true,
	                border:false,
	                collapsed: true,
	                iconCls:'nav'
				}";
			} else {
				$accordianJS .= "{
					id: 'RoomBookingExplorer',
					//autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
					html : 'RoomBooking',
					title: '" . $lang ['roomBooking'] . "',
					autoScroll:true,
	                border:false,
	                collapsed: true,
	                iconCls:'nav'
				}";
			}
		}
		
	//�к�  �Ѵ���»�Ъ��
		if ($license->check ( 'MEETING' )) {
			if (trim ( $accordianJS ) != '') {
				$accordianJS .= ",{
					id: 'MeetingNoteExplorer',
					//autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
					html : 'RoomBooking',
					title: '" . $lang ['meetingNote'] . "',
					autoScroll:true,
	                border:false,
	                collapsed: true,
	                iconCls:'nav'
				}";
			} else {
				$accordianJS .= "{
					id: 'MeetingNoteExplorer',
					//autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
					html : 'RoomBooking',
					title: '" . $lang ['meetingNote']  . "',
					autoScroll:true,
	                border:false,
	                collapsed: true,
	                iconCls:'nav'
				}";
			}
		}
		//�к�  �ͧö
		if ($license->check ( 'CARBOOKING' )) {
			if (trim ( $accordianJS ) != '') {
				$accordianJS .= ",{
					id: 'CarBookingExplorer',
					//autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
					html : 'RoomBooking',
					title: '" . $lang ['carBooking'] . "',
					autoScroll:true,
	                border:false,
	                collapsed: true,
	                iconCls:'nav'
				}";
			} else {
				$accordianJS .= "{
					id: 'CarBookingExplorer',
					//autoLoad: {url: '/{$config ['appName']}/workflow-explorer/get-ui', scripts: true},
					html : 'RoomBooking',
					title: '" . $lang ['carBooking'] . "',
					autoScroll:true,
	                border:false,
	                collapsed: true,
	                iconCls:'nav'
				}";
			}
		}
		
		return $accordianJS;
	}
}

