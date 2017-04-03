<?php


class PDFContentExtractor implements IContentExtract {
	
	function getContents($filePath) {
		global $config;
		$tmpPath = $config ['storageTempPath']."/".uniqid("ftx_").".txt";
		$execCmd = "pdftotext {$filePath} {$tmpPath}";
		Logger::debug($execCmd);
		exec($execCmd,$contents); //run the command
		sleep(2);
		return file_get_contents($tmpPath);
		//return $contents;
	}
}

