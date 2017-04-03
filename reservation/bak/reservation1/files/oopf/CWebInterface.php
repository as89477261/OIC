<?

require_once('constants_wi.php');
require_once('CAppInterface.php');
require_once('CTemplateManager.php');
//require_once('CFormGenerator.php');

class CWebInterface extends CAppInterface
{

    var $conf_filename = 'conf_web_interface.ini';
    var $conf;
    var $function_list = array ();
    var $valid_resulttype = array(RESULTTYPE_LAYOUT, RESULTTYPE_HTMLTPL, RESULTTYPE_HTTPHDR, RESULTTYPE_HTMLSRC);
    var $default_error_result = array();        //edit later to conform to normal result, can be set by each WI

    function getFunctions()
    {
        return $this->function_list;
    }

    // function isValidFunction()
    //          protected member method for validating function id being called
    // $function_id id of function intended to process
    // return   boolean
    function isValidFunction($arg_function_id)
    {
        return array_key_exists($arg_function_id, $this->function_list);
    }

    function detectLanguage($support = array())
    {
        $client_accept = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $client_accept = split('[,;]', $client_accept);

        foreach ($client_accept as $lang)
        {
            if (strpos($lang, '=') !== false)       //it's priority string, not lang id
                continue;
            if (in_array($lang, $support))
                return $lang;
        }
        return '';
    }

    // function process()
    // $output  array of output which can be manipulated by TemplateManager
    // $template_filename
    //          file name of template to be used for showing result.
    // $function_id id of function to process the input
    // $input   array of input
    //
    function process($arg_function_id, $arg_input)
    {
        if (! $this->isValidFunction($arg_function_id))
        {
            $this->mergeErrorMessages($output, 'System configuration is incorrect. Please inform the system administrator.');
            list($result, $template) = explode(',', $this->env['AppInfo']['PathInfo']['SystemErrorTemplate']);
            $result = $this->processResult($output, $template, $result);
        }
        $function_name = $this->function_list[$arg_function_id][FN_NAME];
        $result = $this->$function_name($arg_output, $arg_template, $arg_input);
        if (!is_array($arg_output))
            $arg_output = array();
        $this->mergeDefaultOutput($arg_output);
        $this->mergeLangString($arg_output);
        $result = $this->processResult($arg_output, $arg_template, $result);
        return $result;
    }

    function processResult(&$arg_output, &$arg_template, $arg_result)
    {
        if (! in_array($arg_result, $this->valid_resulttype))
        {
            $this->error_code = ERRORCODE_UNKNOWNRESULTTYPE;
            return false;
        }
        $conf = parse_ini_file(dirname(__FILE__) . '/' . $this->conf_filename, true);
        if (count($conf) > 0)
        {
            $required_files = explode(',', $conf['Main']['IncludedFiles']);
            foreach ($required_files as $file)
                if ($file) include_once($file);
        }

        if (is_array($conf['ResultProcessor']))
        {
            $class = $conf['ResultProcessor'][$arg_result];
            if (class_exists($class))
            {
                $rp = new $class($this);
                if ($rp->isValidResult($arg_output, $arg_template))
                    $result = $rp->process($arg_output, $arg_template);
                else
                    $result = false;
                $this->error_code = $rp->error_code;
                return $result;
            }
            else
            {
                $this->error_code = ERRORCODE_CLASSNOTFOUND;
                return false;
            }
        }
    }

    function mergeDefaultOutput(&$output)
    {
        parent::mergeDefaultOutput($output);
        if (! array_key_exists('BaseHref', $output))
            $output['BaseHref'] = $this->app_info['PathInfo']['BaseHref'];
        foreach ($this->app_info['Urls'] as $name => $url)
            $output[$name] = $url;
    }

}

class CResultProcessorWebInterface
{
    var $error_code;
    var $parent;

    function CResultProcessorWebInterface($parent)
    {
        $this->parent = $parent;
    }

    function isValidResult(&$arg_output, $arg_template = '')
    {
        return true;
    }
}

class CrpwiTemplate extends CResultProcessorWebInterface
{

    function isValidResult(&$arg_output, $arg_template)
    {
        if (! is_array($arg_output))
        {
            $this->error_code = ERRORCODE_RESULTCONTENTNOTFOUND;
            return false;
        }
        elseif  (! is_string($arg_template))
        {
            $this->error_code = ERRORCODE_RESULTTEMPLATENOTFOUND;
            return false;
        }
        else
            return true;
    }

    function process(&$arg_output, &$arg_template)
    {
        if (! $this->isValidResult($arg_output, $arg_template))
            return false;
        $tpl_dir = $this->parent->getTemplateDirLangaugeSupport();
        $tplmgr = new CTemplateManager($tpl_dir);
        $tplmgr->parser->clean_mode = true;
        $result = $this->parseTemplate($arg_output, $arg_template, $tplmgr);
        echo $result;
        if ($this->parent->app_info['Settings']['DebugMode'])
            $tplmgr->parser->printError('<br>');
        return true;
    }

    function parseTemplate(&$arg_output, &$arg_template, &$tplmgr)
    {
        $tplmgr->parser->quiet_mode = true;
        $tplmgr->setData($arg_output);
        $tplmgr->parseFile($arg_template);
        return $tplmgr->readResult();
    }

}

class CrpwiLayout extends CrpwiTemplate
{
    var $tplmgr;
    var $layouts;
    var $tpl_dir;
    var $form_generator;

    function CrpwiLayout($parent)
    {
        parent::CrpwiTemplate($parent);
        $this->tpl_dir = $this->parent->getTemplateDirLangaugeSupport();
        $this->form_dir = $this->parent->app_info['PathInfo']['FormDir'];
        $this->tplmgr = new CTemplateManager($this->tpl_dir);
        $this->tplmgr->parser->clean_mode = true;
        $this->parseLayoutFile('conf_layout.ini');
    }

    function getFormGenerator()
    {
        if (! is_object($this->form_generator))
        {
            include_once('CFormGenerator.php');
            $fg = new CFormGenerator($this->form_dir);
            $cm_const = new CficmConstant($fg);
            $cm_db = new CficmDbTable($fg);
            $cm_const->setApplicationLogic($this->parent);
            $cm_db->setApplicationLogic($this->parent);
            $fg->registerInputChoiceManager('const', $cm_const);
            $fg->registerInputChoiceManager('table', $cm_db);
            $this->form_generator = $fg;
        }
        return $this->form_generator;
    }

    function parseLayoutFile($filename)
    {
        $tpl_dir = $this->parent->getTemplateDirLangaugeSupport();
        $conf_layout = $tpl_dir . '/' . $filename;
        $layout = parse_ini_file($conf_layout, true);
        $aliases = explode(',', $layout['Main']['Aliases']);                 // get all declared aliases
        unset($layout['Main']['Aliases']);
        foreach ($layout as $name => $page)
        {
            if (! is_array($page))
                $page = array();
            if (in_array($name, $aliases))
                $this->aliases[$name] = $page;
            else
                $this->layouts[$name] = $page;
        }
    }

    function readLayoutTemplate($template_name)
    {
        if (array_key_exists($template_name, $this->layouts))
            return $this->layouts[$template_name];
        else
            return false;
    }

    function isExistentLayout($template_name)
    {
        return array_key_exists($template_name, $this->layouts);
    }

    function getLayoutFromAlias($alias)
    {
        if (array_key_exists($alias, $this->aliases))
            return $this->aliases[$alias];
        else
            return false;
    }

    function isExistentAlias($alias)
    {
        return array_key_exists($alias, $this->aliases);
    }

    function process(&$arg_output, &$arg_template)
    {
        if (! $this->isValidResult($arg_output, $arg_template))
            return false;
        $layout = $this->readLayoutTemplate($arg_template);
        if ($layout === false)
            exit ('Template file: ' . $arg_template . ' not found.');
        echo $this->processLayout($arg_output, $layout);
        if ($this->parent->app_info['Settings']['DebugMode'])
            $this->tplmgr->parser->printError('<br>');
    }

    function mergeLayout(&$page)
    {
        $layout_file = $page['Layout'];
        unset($page['Layout']);
        if ($this->isExistentAlias($layout_file))
        {
            $layout_page = $this->getLayoutFromAlias($layout_file);
            if ($layout_page === false)
                exit ('Layout alias: ' . $layout_file . ' not found.');
            $page = array_merge($layout_page, $page);           // overwrite defined values in predecessor by successor
            $layout_file = $this->mergeLayout($page);
        }
        return $layout_file;
    }

    function processLayout(&$arg_output, $page)
    {
        $layout_file = $this->mergeLayout($page);                       // recursively merge all layouts needed
        foreach ($page as $name => $value)
        {
            if (array_key_exists($name, $arg_output))                   // process the same block only one time. (for duplicated or nested block.)
                continue;
            $block = explode(',', $value);
            $type = array_shift($block);
            switch ($type)
            {
                case 'html':
                    $result[$name] = file_get_contents($this->tpl_dir . array_shift($block));
                    break;
                case 'form':
                    $fg = $this->getFormGenerator();
                    $fg->setOutputType(array_shift($block));
                    $key = array_shift($block);
                    $fg->readProfileFromFile($key);
                    $fg->setOutputValue($arg_output);
                    $form_tpl = array_shift($block);
                    $form = array_merge($arg_output, $fg->compile($key));
//                    print_r($form);
                    $result[$name] = $this->parseTemplate($form, $form_tpl, $this->tplmgr);
                    break;
                case 'class':
                    $class = array_shift($block);
                    $rp = new $class();
                    $result = $rp->process($arg_output);
                    break;
                case 'template':
                    $tpl_filename  = array_shift($block);
                    $result[$name] = $this->parseTemplate($arg_output, $tpl_filename, $this->tplmgr);
                    break;
                case 'page':
                    $dummy = $this->readLayoutTemplate(array_shift($block));
                    $result[$name] = $this->processLayout($arg_output, $dummy);
                    break;
                case 'layout':
                    $dummy = array_shift($block);
                    $dummy = array_merge($page, array('Layout' => $dummy));         // make a new page config with sublayout
                    unset($dummy[$name]);
                    $result[$name] = $this->processLayout($arg_output, $dummy);
                    break;
                default:
                    $arg_output[$name] = $value;
            }
        }
        if (is_array($result) && (count($result) > 0))
            $arg_output = array_merge($arg_output, $result);              // merge result to arg_output and return back
        return $this->parseTemplate($arg_output, $layout_file, $this->tplmgr);
    }
}

class CrpwiHtmlSource extends CResultProcessorWebInterface
{
    var $key_content = 'HttpContent';

    function isValidResult(&$arg_output, $arg_template = '')
    {
        if ((is_array($arg_output) || is_object($arg_output)) &&
            (! array_key_exists($this->key_content, $arg_output)))
        {
            $this->error_code = ERRORCODE_RESULTCONTENTNOTFOUND;
            return false;
        }
        return true;
    }

    function process(&$arg_output, &$arg_template)
    {
        if ($this->isValidResult($arg_output))
        {
            if (is_array($arg_output))
                echo $arg_output[$this->key_content];
            elseif (is_object($arg_output))
                echo ($arg_output->$this->key_content);
            else
                echo $arg_output;
            return true;
        }
        else
            return false;
    }
}

class CrpwiHttpHeader extends CrpwiHtmlSource
{
    var $key_content = 'HttpContent';
    var $key_header = 'HttpHeader';

    function isValidResult(&$arg_output, $arg_template = '')
    {
        if ((is_array($arg_output) || is_object($arg_output)) &&
            (! array_key_exists($this->key_header, $arg_output)))
        {
            $this->error_code = ERRORCODE_HEADERCONTENTNOTFOUND;
            return false;
        }
        return true;
    }

    function process(&$arg_output, &$arg_template)
    {
        if ($this->isValidResult($arg_output))
        {
            if (is_array($arg_output))
            {
                if (array_key_exists($this->key_header, $arg_output))
                    $this->populateHeader($arg_output[$this->key_header]);
                else
                    $this->populateHeader($arg_output);
            }
            elseif (is_object($arg_output))
                $this->populateHeader($arg_output->$this->key_header);
            else
                header($arg_output);

            // then continue processing content
            if (parent::isValidResult($arg_output))
                parent::process($arg_output, $arg_template);
            return true;
        }
        else
            return false;
    }

    function populateHeader($header_rows)
    {
        foreach($header_rows as $header)
            header($header);
    }
}

?>