<?

require_once('CTemplateParser.php');

class CTemplateManager
{

    var $root_directory;
    var $parsed;                     // to determine the status of parsing
    var $result_text;                // to keep the html result for reuse
    var $output_data;                // to keep the data for parsing
    var $last_file;

    var $parser;

    function CTemplateManager($arg_root_tpldir = '.')
    {
        $this->parser = new CTemplateParser($arg_root_tpldir);
        $config = parse_ini_file(dirname(__FILE__) . '/conf_template_manager.ini', true);        // for compatibility with PHP4-
        if (count($config) > 0)
        {
            $this->parser->clean_mode = $config['Main']['CleanMode'];
            $required_files = explode(',', $config['Main']['IncludedFiles']);
            foreach ($required_files as $file)
                if ($file) include_once($file);
            foreach ($config['TemplateMacro'] as $key => $tm_class)
                if (class_exists($tm_class))
                    $this->parser->registerMacro($key, new $tm_class($this->parser));
            foreach ($config['TemplateFunction'] as $key => $tf_class)
                if (class_exists($tf_class))
                    $this->parser->registerFunction($key, new $tf_class($this->parser));
        }
        $this->root_directory = $arg_root_tpldir;
        $this->resetState();
    }

    function resetState()
    {
        $this->parsed = false;
        $this->result_text = null;
        $this->output_data = null;
    }

    function setHtmlMode($html = true)
    {
        $this->parser->html_mode = $html;
    }

    function setData(&$data)
    {
        if (! is_array($data)) return false;
        $this->resetState();
        $this->output_data = $data;
    }

    function assign($key, $value)
    {
//        $this->resetState();
        $this->output_data[$key] = $value;
    }

    function parseText($text)
    {
        $this->result_text = $this->parser->parse($text, $this->output_data);
        $this->parsed = true;
    }

    function parseFile($file_name)
    {
	    $this->last_file = $file_name;
        $result = $this->parser->parseFile($file_name, $this->output_data);
        if ($result !== false)
        {
	        $this->result_text = $result;
	        $this->parsed = true;
        }
    }

    function reParse()
    {
        $this->parser->reParse($this->output_data);
        $this->parsed = true;
    }

    function fastPrint()
    {
        if ($this->parsed)
            echo $this->result_text;
        else
            echo 'Error: Template "' . $this->last_file . '" has not been parsed.';
    }

    function readResult()
    {
        if ($this->parsed)
            return $this->result_text;
        else
            echo 'Error: Template "' . $this->last_file . '" has not been parsed.';
    }

}

?>
