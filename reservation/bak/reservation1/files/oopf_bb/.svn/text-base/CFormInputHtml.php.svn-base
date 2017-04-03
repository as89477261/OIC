<?php 
require_once('CFormInput.php');

class CFormInputHtml extends CFormInput
{
    var $tag_str = 'input';
    var $tag_content = false;
    var $map_props = array('UiType' => 'type', 'InputId' => array('name', 'id'), 'Default' => 'value', 'MaxLength' => 'maxlength');

    function generateProperty($name, $value)
    {
        return (string) ($name . '="' . $value . '"');
    }

    function getFormInput(&$ui)
    {
        $result = parent::getFormInput($ui);
        if ($this->tag_content)
            $value = (string) $ui['Default'];
        if ($this->choice_support)
        {
            $result['HtmlSrc'] = $this->generateHtml($result);
        }
        else
        {
            $result['HtmlSrc'] = $this->tag($this->tag_str, implode(' ', $result[FORMGEN_PROPS]), $value);
        }
        $result = copyArrayValue($result, array('Label', 'HtmlSrc'));
        return $result;
    }

    function generateHtml(&$ui)
    {
        return '';
    }

    function tag($key, $attrib, $value = null)
    {
        if ($value !== null)
            return '<' . $key . ' ' . $attrib . '>' . $value . '</' . $key . '>';
        else
            return '<' . $key . ' ' . $attrib . ' />';
    }

}

class CfihText extends CFormInputHtml
{
}

class CfihPassword extends CFormInputHtml
{
}

class CfihHidden extends CFormInputHtml
{
}

class CfihFileUpload extends CFormInputHtml
{
}

class CfihRadio extends CFormInputHtml
{
    var $map_props = array('UiType' => 'type', 'InputId' => array('name', 'id'));
    var $choice_support = true;

    function generateHtml(&$result)
    {
        foreach ($result[FORMGEN_CHOICES] as $choice)
        {
            $result[FORMGEN_PROPS]['value'] = ('value="' . $choice[FORMGEN_CHOICE_VALUE] . '"');
            $tag[] = $this->tag($this->tag_str, implode(' ', $result[FORMGEN_PROPS]) . ' ' . $choice[FORMGEN_CHECKED]) . $choice[FORMGEN_CHOICE_TEXT];
        }
        return implode('', $tag);
    }

}

class CfihCheckBox extends CfihRadio
{
}

class CfihSelect extends CFormInputHtml
{
    var $tag_str = 'select';
    var $tag_content = true;
    var $map_props = array('InputId' => array('name', 'id'));
    var $choice_support = true;

    function generateHtml(&$result)
    {
        $tag = array();
        foreach ($result[FORMGEN_CHOICES] as $choice)
        {
            $tag[] = $this->tag('option', 'value="' . $choice[FORMGEN_CHOICE_VALUE] . '" ' . $choice[FORMGEN_SELECTED], $choice[FORMGEN_CHOICE_TEXT]);
        }
        $dummy = $this->tag($this->tag_str, implode(' ', $result[FORMGEN_PROPS]), implode('', $tag));
        return $dummy;
    }
}

class CfihTextArea extends CFormInputHtml
{
    var $tag_str = 'textarea';
    var $tag_content = true;
    var $map_props = array('InputId' => array('name', 'id'));
}

?>