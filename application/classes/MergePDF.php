<?php
class MergePDF {
	public $basePath;
	public $cmdOption;
	public function __construct($basePath) {
		$this->basePath = $basePath;
	}

	public function mergeFile($files,$outputName) {
		$this->cmdOption = "";
		foreach($files as $file) {
			$this->cmdOption = $this->cmdOption . " {$this->basePath}{$file}";
		}
		$outputFullpath = $this->basePath.$outputName;
		$execCmd = "pdftk {$this->cmdOption} output {$outputFullpath}";
		system($execCmd); //run the command 
		sleep(3);
		while(!file_exists($outputFullpath) && !is_file($outputFullpath) && !(filesize($outputFullpath) > 0)) {
			sleep(1);
		}

		$filesize = filesize ( $outputFullpath );
		$mimetype = 'application/pdf';
		ob_end_clean();
			
			// Start sending headers 
		header ( "Pragma: public" ); // required 
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Cache-Control: private", false ); // required for certain browsers 
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Content-Type: " . $mimetype );
		header ( "Content-Length: " . $filesize );
		header ( "Content-Disposition: attachment; filename=\"" . $outputName . "\";" );
		// Send data 
		echo file_get_contents($outputFullpath);
	}
}