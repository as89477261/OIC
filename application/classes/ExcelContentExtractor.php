<?php

class ExcelContentExtractor implements IContentExtract {
	
	function getContents($filePath) {
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$file = new Java ( "java.io.File", realpath ( $filePath ) );
		$inputStream = new Java ( "java.io.FileInputStream", $file );
		$POIExcelWorkbook = new Java ( "org.apache.poi.hssf.usermodel.HSSFWorkbook", $inputStream );
		//$wordExtractor = new Java("org.apache.poi.hwpf.extractor.WordExtractor",$POIWordDocument);
		$contents = "";

		$totalSheet = java_cast ( $POIExcelWorkbook->getNumberOfSheets (), "integer" );
		//echo "total sheet [$totalSheet]<br/>";
		//Logger::debug("total sheet [$totalSheet]");
		for($numSheets = 0; $numSheets < $totalSheet; $numSheets ++) {
			$excelSheet = $POIExcelWorkbook->getSheetAt ( $numSheets );
			$firstRowCount = java_cast ( $excelSheet->getFirstRowNum (), "integer" );
			$lastRowCount = java_cast ( $excelSheet->getLastRowNum (), "integer" );
			$totalRow = $lastRowCount + 1;
			//echo "total row[$lastRowCount]<br/>";
			/Logger::debug("total row[$lastRowCount]");
			if ($firstRowCount == $lastRowCount) {
				//echo "no row in sheet";
			} else {
				for($rowNum = 0; $rowNum < $totalRow; $rowNum ++) {
					$row = $excelSheet->getRow ( $rowNum );
					try {
						$colCount = java_cast ( $row->getLastCellNum (), "integer" );
						$totalCol = $colCount;
						//echo "total Column [$colCount]<br/>";
						//Logger::debug("total Column[$colCount]");
						for($cellNum = 0; $cellNum < $totalCol; $cellNum ++) {
							//Logger::debug("x");
							$cell = $row->getCell ( $cellNum );
							//Logger::debug("cell [$cellNum] type : " . $cell->getCellType ());
							//echo "cell [$cellNum] type : " . $cell->getCellType () . "<br/>";
							switch (java_cast ( $cell->getCellType (), "integer" )) {
								case 0 :
									//Logger::debug("number");
									//echo "numeric cell <br/>";
									$contents .= (string)$cell->getNumericCellValue ();
									break;
								case 1 :
									//Logger::debug("string");
									//echo "string cell<br/>";
									$contents .= $cell->getStringCellValue ();
									break;
								default :
									//Logger::debug("other");
									//echo "xxxx<br/>";
									$contents .= "";
									break;
							}
						}
					} catch ( Exception $e ) {
						///echo $e->getMessage ();
						//echo "Exception <br/>";
					} catch ( JavaException $ex ) {
						//echo $ex->getCause ();
					}
				
				}
			}
		}
		
		$inputStream->Close ();
		Logger::debug($contents);
		return $contents;
	}
}

