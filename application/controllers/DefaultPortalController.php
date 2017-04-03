<?php
/**
 * โปรแกรม Default Portlet
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Control Center
 */
class DefaultPortalController extends ECMController {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'default' );
	}
	
	/**
	 * สร้าง Portlet ของ Default Portal
	 *
	 * @return string
	 */
	private function getPortletJS() {                                  
		global $config;
		global $lang;

		if(array_key_exists('PLM',$_GET)) {
			$config['portletLayoutMode'] = $_GET['PLM'];
			$config ['autoExpandExplorer'] = true;
			$config ['activeExplorerTab'] = 'DMS';
		} else {
			$config['portletLayoutMode'] = 'DF';
		}
		
		if($config['portletLayoutMode'] == 'DMS') {
			$collapsed = "collapsed: true,";
		} else {
			$collapsed = "collapsed: false,";
		}

		$js = "
			ECMStore = new Persist.Store('ECMStore', {
					swf_path: '/{$config['appName']}/js/persist-js/persist.swf'
			});
				
			function saveECMData(key,value) {
				ECMStore.set(key, value);
			}
				
			function getECMData(key) {
				var v='';
				ECMStore.get(key, function(ok, val) {
				    if (ok) {
				        v= val;
					} else {
						v= '';
					}
				});
				return v;
			}
			
		var portlet1Tools = [{
			id:'refresh',
			handler: function(){
				var portelt1Updater = Ext.getCmp('defaultPortlet1').getUpdater();
				portelt1Updater.update({
					url: '/{$config ['appName']}/portlet/get-portlet-content', 
					params: 'portletClass=AwaitingDocumentPortlet&portletMethod=getUI' ,
					scripts: true
				});
			}
		}];
		
		var portlet2Tools = [/*{
			id:'refreshPortal2',
			handler: function(){
			}
		}*/];
		
		var portlet3Tools = [{
			id:'refresh',
			handler: function(){
				var portelt3Updater = Ext.getCmp('defaultPortlet3').getUpdater();
				portelt3Updater.update({
					url: '/{$config ['appName']}/portlet/get-portlet-content', 
					params: 'portletClass=CirculumDocumentPortlet&portletMethod=getUI' ,
					scripts: true					
				});
			}
		}];
		
		var portlet4Tools = [{
			id:'refresh',
			handler: function(){
				var portelt4Updater = Ext.getCmp('defaultPortlet4').getUpdater();
				portelt4Updater.update({
					url: '/{$config ['appName']}/portlet/get-portlet-content', 
					params: 'portletClass=ExtraCommandPortlet&portletMethod=getUI' ,
					scripts: true					
				});
			}
		}];
		
		var portlet5Tools = [{
			id:'refresh',
			handler: function(){
				var portelt5Updater = Ext.getCmp('defaultPortlet5').getUpdater();
				portelt5Updater.update({
					url: '/{$config ['appName']}/portlet/get-portlet-content', 
					params: 'portletClass=WorkingPortlet&portletMethod=getUI' ,
					scripts: true					
				});
			}
		}];
		
		var portlet6Tools = [{
			id:'refresh',
			handler: function(){
				var portelt6Updater = Ext.getCmp('defaultPortlet6').getUpdater();
				portelt6Updater.update({
					url: '/{$config ['appName']}/portlet/get-portlet-content', 
					params: 'portletClass=OICApplicationPortlet&portletMethod=getUI' ,
					scripts: true					
				});
			}
		}];
		
		var portlet7Tools = [{
			id:'refresh',
			handler: function(){
				var portelt7Updater = Ext.getCmp('defaultPortlet7').getUpdater();
				portelt7Updater.update({
					url: '/{$config ['appName']}/portlet/get-portlet-content', 
					params: 'portletClass=ISOPortlet&portletMethod=getUI' ,
					scripts: true					
				});
			}
		}];
		
		var portlet8Tools = [{
			id:'refresh',
			handler: function(){
				var portelt8Updater = Ext.getCmp('defaultPortlet8').getUpdater();
				portelt8Updater.update({
					url: '/{$config ['appName']}/portlet/get-portlet-content', 
					params: 'portletClass=SearchLoanRequest&portletMethod=getUI' ,
					scripts: true					
				});
			}
		}];
		
		var adminSubPanel1 = new Ext.ux.Portlet({
			id: 'defaultPortlet1',
			title: '" . $lang ['portlet']['1'] ['title'] . "',
			tools: portlet1Tools,
			width: 225,
			height: 225,
			collapsible: true,
			stateful: false,
			{$collapsed}
			autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=AwaitingDocumentPortlet&portletMethod=getUI' ,scripts: true}
		});";
		
		$js .= "var adminSubPanel2 = new Ext.ux.Portlet({
			id: 'defaultPortlet2',
			title: '" . $lang ['portlet2'] ['title'] . "',
			tools: portlet2Tools,
			width: 225,
			height: 225,
			collapsible: true,
			stateful: false,
			{$collapsed}
			autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=DocumentOperationPortlet&portletMethod=getUI' ,scripts: true}
		});";
		
		$js .= "var adminSubPanel3 = new Ext.ux.Portlet({
			id: 'defaultPortlet3',
			title: '" . $lang ['portlet3'] ['title'] . "',
			tools: portlet3Tools,
			width: 225,
			height: 225,
			collapsible: true,
			stateful: false,
			autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=CirculumDocumentPortlet&portletMethod=getUI' ,scripts: true}
		});";
		
		$js .= "var adminSubPanel4 = new Ext.ux.Portlet({
			id: 'defaultPortlet4',
			title: '" . $lang ['portlet4'] ['title'] . "',
			tools: portlet4Tools,
			width: 225,
			height: 225,
			collapsible: true,
			stateful: false,
			autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=ExtraCommandPortlet&portletMethod=getUI' ,scripts: true}
		});";
		
		$js .= "var adminSubPanel5 = new Ext.ux.Portlet({
			id: 'defaultPortlet5',
			title: '" . $lang ['portlet5'] ['title'] . "',
			tools: portlet5Tools,
			width: 225,
			height: 225,
			collapsible: true,
			stateful: false,
			{$collapsed}
			autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=WorkingPortlet&portletMethod=getUI',scripts: true}
		});";
		
		$js .= "var adminSubPanel6 = new Ext.ux.Portlet({
			id: 'defaultPortlet6',
			title: '" . $lang ['portlet6'] ['title'] . "',
			tools: portlet6Tools,
			width: 225,
			height: 225,
			collapsible: true,
			stateful: false,
			{$collapsed}
			autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=OICApplicationPortlet&portletMethod=getUI',scripts: true}
		});";
        
        $js .= "var adminSubPanel7 = new Ext.ux.Portlet({
            id: 'defaultPortlet7',
            title: '" . $lang ['portlet7'] ['title'] . "',
            tools: portlet7Tools,
            width: 225,
            height: 225,
            collapsible: true,
            stateful: false,
			{$collapsed}
            autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=ISOPortlet&portletMethod=getUI',scripts: true}
        });";
        
        $js .= "var adminSubPanel8 = new Ext.ux.Portlet({
            id: 'defaultPortlet8',
            title: '" . $lang ['portlet8'] ['title'] . "',
            tools: portlet8Tools,
            width: 225,
            height: 225,
            collapsible: true,
            stateful: false,
            autoLoad: {url: '/{$config ['appName']}/portlet/get-portlet-content', params: 'portletClass=SearchLoanRequest&portletMethod=getUI',scripts: true}
        });";
		
		return $js;
	}
	
	/**
	 * จัดเรียง Portlet ตาม Column
	 *
	 * @param int $col
	 * @param string $colLayout
	 * @return string
	 */
	function getPortletListByColumn($col, $colLayout) {
		global $config;
		
		if(array_key_exists('PLM',$_GET)) {
			$config['portletLayoutMode'] = $_GET['PLM'];
			$config ['autoExpandExplorer'] = true;
			$config ['activeExplorerTab'] = 'DMS';
		} else {
			$config['portletLayoutMode'] = 'DF';
		}

		$portletList="";

		if($config['portletLayoutMode'] == 'DMS') {
			switch ($colLayout) {
				case 3 :
					if ($col == 1) {
						if ($config ['portlet'] [8] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel8";
							} else {
								$portletList .= ",adminSubPanel8";
							}
						} else {
							$portletList .= "";
						}
						
						return $portletList."adminSubPanel1,adminSubPanel2";
					}
					if ($col == 2) {
						return "adminSubPanel5,adminSubPanel4";
					}
					
					if ($col == 3) {
						$portletList = "";
						
						if ($config ['portlet'] [3] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel3";
							} else {
								$portletList .= ",adminSubPanel3";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [6] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel6";
							} else {
								$portletList .= ",adminSubPanel6";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [7] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel7";
							} else {
								$portletList .= ",adminSubPanel7";
							}
						} else {
							$portletList .= "";
						}
						return $portletList;
					}
					break;
				case 2 :
				default :
					if ($col == 1) {
						if ($config ['portlet'] [8] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel8";
							} else {
								$portletList .= ",adminSubPanel8";
							}
						} else {
							$portletList .= "";
						}

						if ($config ['portlet'] [3] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel3";
							} else {
								$portletList .= ",adminSubPanel3";
							}
						} else {
							$portletList .= "";
						}

						return $portletList;
						/*
						if ($config ['portlet'] [3] ['enable']) {
							return "adminSubPanel1,adminSubPanel2,adminSubPanel3";
						} else {
							return "adminSubPanel1,adminSubPanel2";
						}
						*/
					}
					if ($col == 2) {
						if ($config ['portlet'] [4] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel4";
							} else {
								$portletList .= ",adminSubPanel4";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [1] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel1";
							} else {
								$portletList .= ",adminSubPanel1";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [2] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel2";
							} else {
								$portletList .= ",adminSubPanel2";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [5] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel5";
							} else {
								$portletList .= ",adminSubPanel5";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [6] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel6";
							} else {
								$portletList .= ",adminSubPanel6";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [7] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel7";
							} else {
								$portletList .= ",adminSubPanel7";
							}
						} else {
							$portletList .= "";
						}
						
						/*
						
						if ($config ['portlet'] [7] ['enable']) {
							return "adminSubPanel5,adminSubPanel4,adminSubPanel7";
						} else {
							return "adminSubPanel5,adminSubPanel4";
						}
						
						if ($config ['portlet'] [6] ['enable']) {
							return "adminSubPanel5,adminSubPanel4,adminSubPanel6";
						} else {
							return "adminSubPanel5,adminSubPanel4";
						}
						*/
						return $portletList;
					}
					if ($col == 3) {
						return "";
					}
					break;
			}

		} else {
			switch ($colLayout) {
				case 3 :
					if ($col == 1) {
						if ($config ['portlet'] [8] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel8";
							} else {
								$portletList .= ",adminSubPanel8";
							}
						} else {
							$portletList .= "";
						}

						return $portletList."adminSubPanel1,adminSubPanel2";
					}
					if ($col == 2) {
						return "adminSubPanel5,adminSubPanel4";
					}

					if ($col == 3) {
						$portletList = "";
						
						if ($config ['portlet'] [3] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel3";
							} else {
								$portletList .= ",adminSubPanel3";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [6] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel6";
							} else {
								$portletList .= ",adminSubPanel6";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [7] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel7";
							} else {
								$portletList .= ",adminSubPanel7";
							}
						} else {
							$portletList .= "";
						}

						
						return $portletList;
					}
					break;
				case 2 :
				default :
					if ($col == 1) {
						
						
						if ($config ['portlet'] [1] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel1";
							} else {
								$portletList .= ",adminSubPanel1";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [2] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel2";
							} else {
								$portletList .= ",adminSubPanel2";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [3] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel3";
							} else {
								$portletList .= ",adminSubPanel3";
							}
						} else {
							$portletList .= "";
						}
						return $portletList;
						/*
						if ($config ['portlet'] [3] ['enable']) {
							return "adminSubPanel1,adminSubPanel2,adminSubPanel3";
						} else {
							return "adminSubPanel1,adminSubPanel2";
						}
						*/
					}
					if ($col == 2) {
						if ($config ['portlet'] [6] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel6";
							} else {
								$portletList .= ",adminSubPanel6";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [5] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel5";
							} else {
								$portletList .= ",adminSubPanel5";
							}
						} else {
							$portletList .= "";
						}
						
						if ($config ['portlet'] [4] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel4";
							} else {
								$portletList .= ",adminSubPanel4";
							}
						} else {
							$portletList .= "";
						}
						
						
						
						if ($config ['portlet'] [7] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel7";
							} else {
								$portletList .= ",adminSubPanel7";
							}
						} else {
							$portletList .= "";
						}

						if ($config ['portlet'] [8] ['enable']) {
							if ($portletList == "") {
								$portletList = "adminSubPanel8";
							} else {
								$portletList .= ",adminSubPanel8";
							}
						} else {
							$portletList .= "";
						}
						
						/*
						
						if ($config ['portlet'] [7] ['enable']) {
							return "adminSubPanel5,adminSubPanel4,adminSubPanel7";
						} else {
							return "adminSubPanel5,adminSubPanel4";
						}
						
						if ($config ['portlet'] [6] ['enable']) {
							return "adminSubPanel5,adminSubPanel4,adminSubPanel6";
						} else {
							return "adminSubPanel5,adminSubPanel4";
						}
						*/
						return $portletList;
					}
					if ($col == 3) {
						return "";
					}
					break;
			}

		}
		return "";
	}
	
	/**
	 * สร้าง Column Layout ของ Portlet
	 *
	 * @param string $layout
	 * @return string
	 */
	function getPortletColumnLayout($layout) {
		switch ($layout) {
			case '2col' :
				$js = "new Ext.ux.PortalColumn({
					columnWidth:.45,
					stateful: false,
					border: false,
					style:'padding:10px 0 10px 10px',
					items:[" . $this->getPortletListByColumn ( '1', 2 ) . "]
				}),
				new Ext.ux.PortalColumn({
					columnWidth:.45,
					stateful: false,
					border: false,
					style:'padding:10px 0 10px 10px',
					items:[" . $this->getPortletListByColumn ( '2', 2 ) . "]
				})";
				break;
			case '3col' :
			default :
				$js = "new Ext.ux.PortalColumn({
					columnWidth:.32,
					stateful: false,
					border: false,
					style:'padding:10px 0 10px 10px',
					items:[" . $this->getPortletListByColumn ( '1', 3 ) . "]
				}),
				new Ext.ux.PortalColumn({
					columnWidth:.32,
					stateful: false,
					border: false,
					style:'padding:10px 0 10px 10px',
					items:[" . $this->getPortletListByColumn ( '2', 3 ) . "]
				}),
				new Ext.ux.PortalColumn({
					columnWidth:.32,
					stateful: false,
					border: false,
					style:'padding:10px 0 10px 10px',
					items:[" . $this->getPortletListByColumn ( '3', 3 ) . "]
				})";
				break;
		}
		return $js;
	}
	
	/**
	 * action /get-ui/ แสดงผลโปรแกรม Default Portlet
	 *
	 */
	public function getUiAction() {
		global $config;
		
		checkSessionPortlet();
		
		
		echo "<div id=\"defaultPortalDiv\"></div>";
		echo "<script>";
		echo $this->getPortletJS ();
		
		echo "var defaultPortal = new Ext.ux.Portal({
				xtype:'portal',
				width: '95%',
				//baseCls: 'x-portal',
				 //region:'center',    
				 margins:'35 5 5 0',    
				 autoScroll: 'auto',
				 monitorResize: true,
				width: Ext.getCmp('tpAdminHome').getInnerWidth(),
				height: Ext.getCmp('tpAdminHome').getInnerHeight(),
				border: true,
				//frame: true,
				//height: '100%',
				id: 'tpDefaultPortal',
				renderTo: 'defaultPortalDiv',
				//autoScroll: true,
				layout: 'column',
				stateful: false,
				iconCls: 'homeIcon',
			items:[{$this->getPortletColumnLayout ( $config ['portlet'] ['style'] )}]
		});";
		/*
		 * 
		Ext.getCmp('ECMExplorer').on('expand',function(p) {
			if(Ext.getCmp('tpAdmin').getActiveTab().getId()=='tpAdminHome') {
				var tabToUpdate = Ext.getCmp('tpAdmin').getActiveTab();
				var tabUpdater = tabToUpdate.getUpdater().refresh();
			}
		},this);
		
		Ext.getCmp('ECMExplorer').on('collapse',function(p) {
			if(Ext.getCmp('tpAdmin').getActiveTab().getId()=='tpAdminHome') {
				var tabToUpdate = Ext.getCmp('tpAdmin').getActiveTab();
				var tabUpdater = tabToUpdate.getUpdater().refresh();
			}
		},this);
		
		Ext.getCmp('ECMProperty').on('expand',function(p) {
			if(Ext.getCmp('tpAdmin').getActiveTab().getId()=='tpAdminHome') {
				var tabToUpdate = Ext.getCmp('tpAdmin').getActiveTab();
				var tabUpdater = tabToUpdate.getUpdater().refresh();
			}
		},this);
		
		Ext.getCmp('ECMProperty').on('collapse',function(p) {
			if(Ext.getCmp('tpAdmin').getActiveTab().getId()=='tpAdminHome') {
				var tabToUpdate = Ext.getCmp('tpAdmin').getActiveTab();
				var tabUpdater = tabToUpdate.getUpdater().refresh();
			}
		},this);
		*/
		echo "defaultPortal.render();
		</script>";
	}
}
