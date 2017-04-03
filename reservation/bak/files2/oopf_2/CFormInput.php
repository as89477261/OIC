<?php 
require_once('utils_framework.php');
require_once('CFormGenerator.php');

class CFormInput
{
    var $map_props = array('InputId' => array('name', 'id'), 'Default' => 'value', 'MaxLength' => 'maxlength');
    var $choice_support = false;

    function CFormInput($parent)
    {
        $this->parent = $parent;
    }

    function getFormInput(&$ui)
    {
        //
        $result['UiType'] = $ui['UiType'];
        $result['Label'] = $ui['Label'];
        $this->setDefaultValue($ui);
        $result[FORMGEN_PROPS] = $this->getProperties($ui);
        if ($this->choice_support)
            $result[FORMGEN_CHOICES] = $this->getChoices($ui);
        return $result;
    }

    function generateProperty($name, $value)
    {
        return array('Name' => $name, 'Value' => $value);
    }

    function extractProperties($str)
    {
        $result = array();
        parse_str($str, $props);
        foreach ($props as $name => $value)
            $result[$name] = $this->generateProperty($name, $value);
        return $result;
    }

    function getProperties(&$ui)
    {
        $props = $this->extractProperties($ui['UiProps']) + $this->parent->getMainProperties();
        foreach ($this->map_props as $src => $dest)
        {
            if (array_key_exists($src, $ui))
            {
                if (! is_array($dest))
                    $dest = array($dest);
                foreach ($dest as $d)
                    $props[$d] = $this->generateProperty($d, $ui[$src]);
            }
        }
        return $props;
    }

    function getChoices(&$ui)
    {
        $cm = $this->parent->findChoiceManager($ui['ChoiceType']);
        $choices = $cm->getChoices($ui[FORMGEN_CHOICES]);
        if ($ui['ChoiceBlank'])
        {
            $blank = array(FORMGEN_CHOICE_VALUE => '', FORMGEN_CHOICE_TEXT => $ui['BlankText']);
            $choices = mergeList(array($blank), $choices);
        }
        $defaults = explode(',', $ui['Default']);
        selectList($choices, $defaults);
        return $choices;
    }

    function setDefaultValue(&$ui)
    {
        $value = $this->parent->findValue($ui['DataId']);
        if (($value !== false) && ($value !== null))
            $ui['Default'] = $value;
    }

}

class CfiText extends CFormInput
{
}

class CfiPassword extends CFormInput
{
}

class CfiHidden extends CFormInput
{
}

class CfiFileUpload extends CFormInput
{
}

class CfiRadio extends CFormInput
{
    var $map_props = array('InputId' => array('name', 'id'));
    var $choice_support = true;
}

class CfiSelect extends CFormInput
{
    var $map_props = array('InputId' => array('name', 'id'));
    var $choice_support = true;
}

class CfiCheckBox extends CFormInput
{
    var $map_props = array('InputId' => array('name', 'id'));
    var $choice_support = true;
}

class CfiTextArea extends CFormInput
{
    var $map_props = array('InputId' => array('name', 'id'));

    function getFormInput($ui)
    {
        $result = parent::getFormInput($ui);
        $result['Value'] = $ui['Default'];
        return $result;
    }
}

?>