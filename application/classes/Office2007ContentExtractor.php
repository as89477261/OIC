<?php


class Office2007ContentExtractor implements IContentExtract {
	
	function getContents($filePath) {
		global $config;
		$tmpPath = $config ['appTempPath'];
		
		$errorReport = ini_get ( 'error_reporting' );
		error_reporting ( 0 );
		
		$filename = basename ( $filePath );
		list ( $filebasename, $ext ) = explode ( '.', $filename );
		$uniqueFilename = uniqid ( 'docx_' );
		$contents = "";
		Logger::debug ("$tmpPath/$uniqueFilename.zip");
		if (! copy ( $filePath, "$tmpPath/$uniqueFilename.zip" )) {
			Logger::debug("Unable to create Temp File");
		} else {
			Logger::debug("Temp File Created");
			$zip = zip_open ( "$tmpPath/$uniqueFilename.zip" );
			if ($zip) {
				while ( $zip_entry = zip_read ( $zip ) ) {
					if (zip_entry_open ( $zip, $zip_entry, "r" )) {
						$buf = strip_tags ( zip_entry_read ( $zip_entry, zip_entry_filesize ( $zip_entry ) ) );
						$contents .= $buf;
						zip_entry_close ( $zip_entry );
					}
				}
				zip_close ( $zip );
			}
			unlink ( "$tmpPath/$uniqueFilename.zip" );
		}
		error_reporting ( $errorReport );
		return $contents;
	}
}
