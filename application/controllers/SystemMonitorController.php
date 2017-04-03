<?php

class SystemMonitorController extends ECMController {
/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'system' );
	}
	
	/**
	 * 
	 */
	public function indexAction() {
		$output = $this->ECMView->render ( 'monitor.phtml' );
		echo $output;
	}
	
	/**
	 * action /concurrent-data-config สร้าง Config สำหรับ Monitor Chart
	 *
	 */
	public function concurrentDataConfigAction() {
		global $config;
		if($config ['concurrent'] != 'U' ) {
			$concurrentUCL = (int)$config ['concurrent'];
			if($concurrentUCL < 10) {
				$divLine = 4;
			} else {
				$divLine = 9;
			}
		} else {
			$concurrentUCL = 100;
			$divLine = 9;
		}
		$xmlConfig = '<chart bgColor="000000" bgAlpha="100" canvasBorderThickness="1" canvasBorderColor="008040" canvasBgColor="000000" yAxisMaxValue="'.$concurrentUCL.'" decimals="0" numdivlines="'.$divLine.'" numVDivLines="28" numDisplaySets="30" divLineColor="008040" vDivLineColor="008040" divLineAlpha="100" chartLeftMargin="10" baseFontColor="00dd00" showRealTimeValue="0" dataStreamURL="/'.$config['appName'].'/system-monitor/concurrent-data" refreshInterval="5" numberSuffix=" cons" labelDisplay="rotate" slantLabels="1" toolTipBgColor="000000" toolTipBorderColor="008040" baseFontSize="11" showAlternateHGridColor="0" legendBgColor="000000" legendBorderColor="008040" legendPadding="35" showLabels="1">';
		$xmlConfig .= '<categories><category label="Start"/></categories>';
		$xmlConfig .= '<dataset color="00dd00" seriesName="User" showValues="0" alpha="100" anchorAlpha="0" lineThickness="2"><set value="0"/></dataset>';
		$xmlConfig .= '<dataset color="ff5904" seriesName="Admin" showValues="0" alpha="100" anchorAlpha="0" lineThickness="2"><set value="0"/></dataset>';
		$xmlConfig .= '<dataset color="0033FF" seriesName="Total" showValues="0" alpha="100" anchorAlpha="0" lineThickness="2"><set value="0"/></dataset>';
		$xmlConfig .= '</chart>';
		header('Content-Type: text/xml');
		echo $xmlConfig;
	}
	
	/**
	 * action /concurrent-data ส่งคืนค่าของ Chart
	 *
	 */
	public function concurrentDataAction() {
		$label = date('H:i:s');
		$systemMonitor = new SystemMonitor();
		$userCons = $systemMonitor->getCurrentConcurrentsCount();
		$adminCons = $systemMonitor->getCurrentAdminConcurrentsCount();
		$totalCons = (int)$userCons+(int)$adminCons;
		header('Content-Type: text/plain');
		echo "&label={$label}&value={$userCons}|{$adminCons}|{$totalCons}";
	}
}
