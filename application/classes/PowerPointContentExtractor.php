<?php

class PowerPointContentExtractor implements IContentExtract {
	
	function getContents($filePath) {
		define ( "JAVA_HOSTS", "127.0.0.1:18080" );
		set_time_limit ( 0 );
		loadExternalLib ( 'javaBridge' );
		
		$file = new Java ( "java.io.File", realpath ( $filePath) );
		$inputStream = new Java ( "java.io.FileInputStream", $file );

		$PPTExtractor = new Java ( "org.apache.poi.hslf.extractor.PowerPointExtractor", $inputStream );
		
		$text = $PPTExtractor->getText ();
		//
		$inputStream->Close();
		return $text;
	}
}

