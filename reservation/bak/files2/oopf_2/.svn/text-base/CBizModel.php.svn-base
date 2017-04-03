<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CBizModel
{
	var $parent;
	var $models = array();

    function __construct($parent)
    {
        $this->parent = $parent;
        $this->init();
    }

    function getDataModel($class)
    {
        if (!array_key_exists($class, $this->models))
        {
            if (class_exists($class))
			{
				if (! is_object($this->models[$class]))
					$this->models[$class] = new $class($this->parent);
			}
            else
                return false;
        }
        return $this->models[$class];
    }

	function init()
	{
	}
}
?>
