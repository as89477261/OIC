<?php /*
$fg = new CFormGenerator();
$fg->readProfileFromFile(dirname(__FILE__) . '/' . '../forms/frmMember.ini');
$result = $fg->compile(dirname(__FILE__) . '/' . '../forms/frmMember.ini');
print_r($result);
*/

define('FORMGEN_PROPS', 'Properties');
define('FORMGEN_CHOICES', 'Choices');
define('FORMGEN_CHOICE_VALUE', 'Value');
define('FORMGEN_CHOICE_TEXT', 'Text');
define('FORMGEN_SELECTED', 'selected');
define('FORMGEN_CHECKED', 'checked');

require_once('CFormProfileReader.php');
require_once('CFormInput.php');
require_once('CFormInputHtml.php');
require_once('CFormInputChoiceManager.php');

class CFormGenerator // extends CApplicationLogic
{
    var $conf_filename = 'conf_form_generator.ini';
    var $conf;
    var $read_config = false;
    var $readers = array();
    var $profiles = array();
    var $output_type = 'basic';
    var $input_types = array();
    var $choice_mgrs = array();
    var $common_props;
    var $form_directory;
    var $INPUT_KEY = 'FormInputs';
    var $compiling = false;

    function CFormGenerator($arg_formdir = '.', $output_type = '')
    {
        $this->form_directory = $arg_formdir;
        if ($output_type != '')
            $this->setOutputType($output_type);
//        $this->readConfig();
    }

    function setOutputType($output_type)
    {
        $this->output_type = $output_type;
        $this->readConfig();
    }

    function readConfig()
    {
        if ($this->read_config)
            return;
        $this->conf = parse_ini_file(dirname(__FILE__) . '/' . $this->conf_filename, true);

        $profile_readers = $this->conf['ProfileReader'];
        foreach ($profile_readers as $ext => $class)
        {
            if (class_exists($class))
                $this->registerReader($ext, new $class());
        }

        $output_type = $this->conf['OutputType'][$this->output_type];
        if (! array_key_exists($output_type, $this->conf))
        {
            echo 'Form Generator Error: Output type not found ' . $output_type;
            exit;
        }

        $itypes = $this->conf[$output_type];
        foreach ($itypes as $type => $class)
        {
            if (class_exists($class))
                $this->registerInputType($type, new $class($this));
//                $this->input_types[$type] = array('Obj' => new $class($this));
        }

        $ichoices = $this->conf['FormInputChoice'];
        foreach ($ichoices as $type => $class)
        {
            if (class_exists($class))
                $this->registerInputChoiceManager($type, new $class($this));
//                $this->choice_types[$type] = array('Obj' => new $class($this));
        }
//        print_r($this->input_types[$type]);
        $this->read_config = true;
    }

    function registerReader($ext, $obj)
    {
        $this->readers[$ext] = array('Obj' => $obj, 'Ext' => $ext);
    }

    function registerInputType($type, $obj_itype)
    {
         $this->input_types[$type] = array('Obj' => $obj_itype);
    }

    function registerInputChoiceManager($type, $obj_ctype)
    {
        $this->choice_mgrs[$type] = array('Obj' => $obj_ctype);
    }

    function findReaderExtension($ext)
    {
        $preader = null;
        foreach ($this->readers as $preader)
            if ($preader['Ext'] == $ext)
                return $preader['Obj'];
        if (!is_object($preader))
            return $this->readers[$this->conf['Main']['DefaultReader']]['Obj'];
    }

    function readProfileFromFile($filename, $filetype = '')
    {
        if (array_key_exists($filename, $this->profiles))
            return $this->profiles[$filename];

        if ($filetype == '')
        {
            $ext = substr($filename, strrpos($filename, '.'));
            $preader = $this->findReaderExtension($ext);
        }
        else
            $preader = $this->readers[$filetype]['Obj'];

        $this->profiles[$filename] = $preader->readProfile($this->form_directory . $filename);
        return $this->profiles[$filename];
    }

    function setProfile($code, &$profile_data)
    {
        $this->profiles[$code] = $profile_data;
    }

    function setOutputValue(&$arg_output)
    {
        $this->arg_output =& $arg_output;
    }

    function findValue($val_name)
    {
        $name_list = explode('.', $val_name);
        $name = array_shift($name_list);
        $p_val =& $this->arg_output;
        while (is_array($p_val) && array_key_exists($name, $p_val))
        {
            $p_val =& $p_val[$name];
            $name = array_shift($name_list);
        }
        if ((count($name_list) > 0) || (is_array($p_val)))
            return false;
        else
            return $p_val;
    }

    function getMainProperties()
    {
        if ($this->compiling)
            return $this->common_props;
        else
            return array();
    }

    function compile($key)
    {
        $this->compiling = true;
        if (! $this->read_config)
            $this->readConfig();
        $result = array();
        $profile =& $this->profiles[$key];
        if (array_key_exists('Main', $profile))
        {
            $this->common_props = $this->input_types['default']['Obj']->extractProperties($profile['Main']['CommonProps']);
            $form = $profile['Main'];
//           $profile = $this->getProfileFromFile($this->form_directory . '/' . $filename, $filetype);
            $inputs = $form[$this->INPUT_KEY];
            $inputs = explode(',', $inputs);
            foreach ($inputs as $name)
            {
                $ui = $profile[$name];
                $ui['DataId'] = $name;
                if (! array_key_exists($ui['UiType'], $this->input_types))
                    echo 'Ui Type: ' . $ui['UiType'] . ' of ' . $ui['DataId'] . ' not found';
                else
                    $result[$name] = $this->input_types[$ui['UiType']]['Obj']->getFormInput($ui);
            }
            $form[$this->INPUT_KEY] = $result;
        }
        else
        {
            echo 'Form profile is invalid';
            $form = array();
        }
        $this->compiling = false;
        return $form;
    }

    function findChoiceManager($type)
    {
        if (array_key_exists($type, $this->choice_mgrs))
            return $this->choice_mgrs[$type]['Obj'];
        else
            return null;
    }

}

?>
