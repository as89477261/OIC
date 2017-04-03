<?

require_once('CWebInterface.php');

class CEdocWebInterface extends CWebInterface
{
    function getUploader($subdir_auto = false)
    {
	    $uploader = new COopfUploader($subdir_auto, $this->app_info['Preferences']['RejectExtensions']);
		return $uploader;
	}
}

class COopfUploader
{
	var $path_upload, $uploader, $subdir, $subdir_auto;
	var $error = null;
	var $ext_images = array('jpg', 'jpeg', 'gif', 'png');

	function __construct($subdir_auto = false, $rejects = '')
	{
	    include_once('FileUpload.php');
        $this->uploader = new FileUpload();
        $this->uploader->setRejectExtensions($rejects);
        $this->subdir_auto = $subdir_auto;
	}

	function setUploadPath($path_upload = './')
	{
		if ($this->subdir_auto)
		{
			$this->subdir = date('Y');
			$path_upload .= ('/' . $this->subdir);
		}

        // check and create upload folder if not exists
        if (! (file_exists($path_upload) && is_dir($path_upload)))
        	mkdir($path_upload, 0777, true);
    	if (! file_exists($path_upload))
    		return false;

		$this->path_upload = $path_upload;
		return true;
	}

	// save file in upload path
	function upload($file_inputs)
	{
		if (! is_array($file_inputs))
			$file_inputs = array($file_inputs);

		$result = array();
        foreach ($file_inputs as $file_input)
        {
	        $input_name = str_replace('.', '_', $file_input);
	        if (!(array_key_exists($input_name, $_FILES) && is_array($_FILES[$input_name]) && ($_FILES[$input_name]['name'])))
	        	continue;
	        $file_name = $this->uploader->upload($input_name, $this->path_upload);
        	$result[$file_input]['file_name'] = $file_name;
        	if ($this->subdir_auto)
	        	$result[$file_input]['subdir'] = $this->subdir;
        	if ($file_name === false)
	        	$this->error[$file_input] = $this->uploader->getError();
        	else
				if (in_array(substr($file_name, strrpos($file_name, '.') + 1), $this->ext_images))
				{
					$file_array = $this->uploader->getFile();
					$result[$file_input]['file_type'] = (is_array($file_array['type']) ? array_pop($file_array['type']) : $file_array['type']);
				}
    	}
        return $result;
	}

	function mergeResult(&$data, &$result)
	{
        foreach ($result as $input_name => $info)
        	if ($info['file_name'] !== false)
        	{
	        	$input_name = explode('.', $input_name);
	        	$data_name = $input_name[count($input_name) - 1];
                $data[$data_name] = $info['file_name'];
                $data['file_type'] = $info['file_type'];
                if (array_key_exists('subdir', $info))
	                $data['subdir'] = $info['subdir'];
            }
	}

	function getError()
	{
		return $this->error;
	}
}

?>