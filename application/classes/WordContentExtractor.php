<?php

class WordContentExtractor implements IContentExtract {
	function echobig($string, $bufferSize = 8192) {
		// suggest doing a test for Integer & positive bufferSize
		for($chars = strlen ( $string ) - 1, $start = 0; $start <= $chars; $start += $bufferSize) {
			echo substr ( $string, $start, $buffer_size );
		}
	}
	
	function getContents($filePath) {
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$file = new Java ( "java.io.File", realpath ( $filePath ) );
		Logger::debug ( "Content Extract" . realpath ( $filePath ) );
		$inputStream = new Java ( "java.io.FileInputStream", $file );
		Logger::debug ( 1 );
		$POIWordDocument = new Java ( "org.apache.poi.hwpf.HWPFDocument", $inputStream );
		Logger::debug ( 2 );
		$wordExtractor = new Java ( "org.apache.poi.hwpf.extractor.WordExtractor", $POIWordDocument );
		Logger::debug ( 3 );
		
		$wrapped = wordwrap ( $wordExtractor->getTextFromPieces (), 256, "###" );
		$arrString = explode ( "###", $wrapped );
		//print_r($arrString);
		foreach ( $arrString as $str ) {
			Logger::debug ( $str );
			$contents .=  $str ;
		}
		
		$inputStream->Close ();
		return $contents;
	}
}
