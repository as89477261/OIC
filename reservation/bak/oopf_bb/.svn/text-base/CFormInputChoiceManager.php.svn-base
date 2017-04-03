<?php 
class CFormInputChoiceManager
{
    var $parent;

    function __construct($parent)
    {
        $this->parent = $parent;
    }

    function getChoices($params, $selected_value)
    {
        return array();
    }

}

class CficmFixed extends CFormInputChoiceManager
{

    function getChoices($params)
    {
        $params = (string)$params;
        $dummy = explode(',', $params);
        foreach ($dummy as $item)
        {
            list($val, $text) = explode(':', $item);
            if ($text == '')
                $text = $val;
            $list[] = array(FORMGEN_CHOICE_VALUE => $val, FORMGEN_CHOICE_TEXT => $text);
        }
        return $list;
    }

}

class CficmOutputArray extends CFormInputChoiceManager
{

    function getChoices($params)
    {
        $params = (string)$params;
        return $this->parent->arg_output[$params];
    }

}

class CficmOopf extends CFormInputChoiceManager
{

    var $app;

    function setApplicationLogic($app_logic)
    {
        $this->app = $app_logic;
    }

}

class CficmConstant extends CficmOopf
{

    function getChoices($params)
    {
        $const_id = (string)$params;
        $list = $this->app->getConstantList($const_id);
        return $list;
    }

}

class CficmDbTable extends CficmOopf
{

    function getChoices($params)
    {
        $params = (string)$params;
        list($table, $val_key, $text_key) = explode(':', $params);
        $ds = $this->app->getDataStore($table);
        $rows = $ds->getData();
        $list = getList($rows, $val_key, $text_key);
        return $list;
    }

}

?>