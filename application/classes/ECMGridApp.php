<?php
/**
 * Class สำหรับสร้าง Grid Application
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package classes
 * @category System
 */
class ECMGridApp {
	/**
	 * ชื่อกริด
	 *
	 * @var string
	 */
	private $gridName;
	/**
	 * ชื่อ Column Model
	 *
	 * @var string
	 */
	private $cmName;
	/**
	 * ชื่อ Selection Model
	 *
	 * @var string
	 */
	private $sm;
	/**
	 * Column Property 
	 *
	 * @var array
	 */
	private $colProperties;
	
	/**
	 * กำหนดข้อมูลเบื้องต้นของ Grid Application
	 *
	 * @param string $name
	 */
	public function __construct($name) {
		$this->gridName = $name;
		$this->cmName = "";
		$this->colProperties = Array();
		$this->sm = 'singleSelect';
	}
	
	/**
	 * กำหนดคอลัมน์ของ Grid Application
	 *
	 * @param string $columnModelName
	 * @param array $columns
	 */
	public function setColumns($columnModelName ,$columns) {
		$this->cmName = $columnModelName;
		foreach ( $columns as $column ) {
			$colProperty = "";
			if (array_key_exists ( 'header', $column )) {
				$colProperty .= "header: '{$column['header']}'";
			}
			
			if (array_key_exists ( 'dataIndex', $column )) {
				if ($colProperty != "") {
					$colProperty .= ",";
				}
				$colProperty .= "dataIndex: '{$column['dataIndex']}'";
			}
			
			if (array_key_exists ( 'width', $column )) {
				if ($colProperty != "") {
					$colProperty .= ",";
				}
				$colProperty .= "width: {$column['width']}";
			}
			
			if (array_key_exists ( 'align', $column )) {
				if ($colProperty != "") {
					$colProperty .= ",";
				}
				$colProperty .= "align: '{$column['align']}'";
			}
			
			if (array_key_exists ( 'renderer', $column )) {
				if ($colProperty != "") {
					$colProperty .= ",";
				}
				$colProperty .= "renderer: {$column['renderer']}";
			}
			$this->colProperties[] = "{".$colProperty."}";
		}
	}
	
	/**
	 * ดึงข้อมูล Column Model
	 *
	 * @param boolean $sortable
	 * @return string
	 */
	public function getColumnModel($sortable=true) {
		$columnConfig = join(",",$this->colProperties);
		$cmConfig =  "var {$this->cmName} = new Ext.grid.ColumnModel([{$columnConfig}]);";
		if($sortable) {
			$cmConfig .= "{$this->cmName}.defaultSortable = true;";
		} else {
			$cmConfig .= "{$this->cmName}.defaultSortable = false;";
		}
		
		return $cmConfig;
	}
	
	/**
	 * กำหนด Selection Model
	 *
	 * @param string $model
	 */
	public function setSelectionModel($model = "singleSelect") {
		if($model == 'singleSelect') {
			$this->sm = 'singleSelect';
		}
	}
	
	/**
	 * ดึงข้อมูล Selection Model
	 *
	 * @return string
	 */
	public function getSeletionModel() {
		switch($this->sm) {
			case 'singleSelect':
			default :
				return "new Ext.grid.RowSelectionModel({singleSelect: true})";
				break;
		}	
	}
	
	/**
	 * สร้าง Grid Application 
	 *
	 * @param string $renderDIV
	 * @param string $storeName
	 * @param string $tbar
	 * @param string $bbar
	 * @param string $dataText
	 * @param string $fromText
	 * @param string $emptyText
	 * @param string $parentContainerID
	 * @return string
	 */
	public function getGrid($renderDIV,$storeName,$tbar=true,$bbar=true,$dataText="Data Paging :",$fromText="from",$emptyText="No Data",$parentContainerID='tpAdmin') {
		$gridName = $this->gridName;
		if($tbar) {
			$tbarConfig = "tbar: new Ext.Toolbar({
				id: '{$gridName}_Toolbar',
				height: 25				
			}),";
		}
		
		if($bbar) {
			$bbarConfig = ",bbar: new Ext.PagingToolbar({
	        	id: '{$gridName}_pagingToolbar'
	            ,pageSize: 25
	            ,store: {$storeName}
	            ,displayInfo: true
	            ,displayMsg: '{$dataText} {0} - {1} {$fromText} {2}'
	            ,emptyMsg: \"{$emptyText}\"
	            /*,items:[
	                '-', {
	                pressed: false,
	                enableToggle: true,
	                text: 'xxx',
	                cls: 'x-btn-text-icon details',
					toggleHandler: toggle{$gridName}Details
	            }]*/
	        })";
		}
		
		$gridConfig = "var {$gridName} = new Ext.grid.GridPanel({
	        width: Ext.getCmp('{$parentContainerID}').getInnerWidth(),
			height: Ext.getCmp('{$parentContainerID}').getInnerHeight(),
	        store: {$storeName},
	        {$tbarConfig}	        
			cm: {$this->cmName},
	        trackMouseOver:false,
	        sm: {$this->getSeletionModel()},
	        loadMask: true,
	        enableDragDrop : true,
	        renderTo: '{$renderDIV}',
	        viewConfig: {
	            forceFit:true,
	            enableRowBody:true,
	            showPreview: false,
	            getRowClass : function(record, rowIndex, p, store){
	                if(this.showPreview){
	                    p.body = '<p>No detail defined</p>';
	                    return 'x-grid3-row-expanded';
	                }
	                return 'x-grid3-row-collapsed';
	            }
	        }
	        {$bbarConfig}	        
	    });";
	    
	    return $gridConfig;
	}
	
	/**
	 * เพิ่ม Top Bar สำหรับ Grid Application
	 *
	 * @param string $id
	 * @param string $text
	 * @param string $handler
	 * @param string $disabled
	 * @param string $iconClass
	 * @return string
	 */
	public function addTBarAction($id,$text,$handler=false,$disabled=false,$iconClass='bmenu') {
			if($disabled) {
				$disableButton = "disabled: true,";
			} else {
				$disableButton = "";
			}
			return "Ext.getCmp('{$this->gridName}_Toolbar').add(
			{
	        	id: '{$id}',
	            text:'{$text}',
	            iconCls: '{$iconClass}',
	            {$disableButton}
	            handler: {$handler}
	        }
			);";
	
		
	}
	
	/**
	 * แสดงผล Grid Application
	 *
	 * @return string
	 */
	public function render() {
		return "{$this->gridName}.render();	    
		{$this->gridName}.colModel.renderCellDelegate = renderCell.createDelegate({$this->gridName}.colModel);";
	}
}

