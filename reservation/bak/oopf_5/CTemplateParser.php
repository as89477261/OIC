<?

define('MACRO_KEYWORD_BEGIN', 'BEGIN');
define('MACRO_KEYWORD_END', 'END');

define('TYPE_MACRO', 'MACRO');
define('TYPE_SUBMACRO', 'SUBMACRO');

// number, date, null, count_rows, base href, dropdown

class CTemplateParser
{

    var $macro_pattern = '/<\?\s(\w+)\(([=\-\'\.\/\[\]\,%\$\w\d]*)\)\s\?>/Us';
//    var $value_pattern = '/\{(\w(\w|\.\w+)*)(\,(\w+)(\((.+)\))?)?\}/Ue';      // please note that this regex is greedy so you can put '}' or ')' (and many other chars) into a block of {xxx} with no problem, and, therefore, we can't use 2 functions at a time because it will be detected as 1 function with longer parameter
    var $value_pattern = '/\{(([\$\.\-\[\]\w]|\/[\.\-\[\]\w]+)*)(\,([\|\w]+)(\((.+)\))?)?\}/Ue';      // and the new thing is we can use blank as name of value too, this is for using template function without value as a parameter
                                                                                // support for using a value to point to another value which is the real one to display
//     var $value_pattern = '/\{(\w+)\}/e';
//     var $simplevalue_pattern = '/\{([\.\w]+)\}/e';
    var $quiet_mode = false;
    var $clean_mode = false;
    var $html_mode = true;

    var $root_directory;
    var $content;
    var $registered_macro = array();
    var $registered_function = array();
    var $error_stack = array();

    var $enable_cache = false;
    var $cache_hit = false;
    var $cache_path;

    function CTemplateParser($arg_rootdir = ".")
    {
        if ($arg_rootdir[strlen($arg_rootdir)-1] != '/')        // auto-detect and auto-fill '/' at the last character
            $arg_rootdir .= '/';
        $this->root_directory = $arg_rootdir;
    }

    function registerMacro($macro_name, &$macro_obj)
    {
        if (!is_object($macro_obj))
            $this->alert("Function registered for $func_name is not an object");
        else
        {
            $macro_obj->macro_key = $macro_name;
            $this->registered_macro[$macro_name] = &$macro_obj;
        }
    }

    function registerFunction($func_name, &$func_obj)
    {
        if (!is_object($func_obj))
            $this->alert("Function registered for $func_name is not an object");
        elseif (!method_exists($func_obj, 'evaluate'))
            $this->alert("Method 'evaluate' of handling object for Function $func_name is not found");
        else
            $this->registered_function[$func_name] = &$func_obj;
    }

    // $func can be either another function name or an object
    function registerFunctionAlias($func_alias, $func, $func_param)
    {
        if (is_object($func))
            $this->registered_function[$func_alias] = array($func, $func_param);
        elseif (!is_string($func) && !preg_match('/^\w+$/', $func))
            $this->alert("Referencing name '$func' is invalid");
        elseif ($func_alias == $func)
            $this->alert("Function alias '$func_alias' is as same as referencing name '$func'");
        else
        {
            $func =& $this->registered_function[$func];
            while (!is_object($func) && is_array($func))
            {
                $func_param = $func[1];
                $func =& $this->registered_function[$func[0]];
            }
            if (is_object($func))
                $this->registered_function[$func_alias] = array($func, $func_param);
            elseif (!is_array($func_obj))
                $this->alert("CTemplateParser alias registration fault");
        }
    }

    function alert($text)
    {
        $this->error_stack[] = 'Template Parser Alert: ' . $text;
        if ($this->quiet_mode) return;
        else echo "\n" . $text . "\n";
    }

    function printError($delim = "\n")
    {
        echo implode($delim, $this->error_stack);
    }

    function getError()
    {
        return $this->error_stack;
    }

    function resetError()
    {
        $this->error_stack = array();
    }

    function tokenize($template)
    {
        $dummy = preg_split($this->macro_pattern, $template, -1, PREG_SPLIT_DELIM_CAPTURE);
        $is_macro = false;
        while (list(, $val) = each($dummy))
        {
            if (!$is_macro)
            {
                if ($val !== '')					// save for character '0' will be evaluated like 'false'
                    $tokens[] = $val;
                $is_macro = true;
            }
            else
            {
                $macro_val = array($val);
                list(, $val) = each($dummy);
                $macro_val[] = $val;
                $tokens[] = $macro_val;
                $is_macro = false;
            }
        }
        return $tokens;
    }

    function parse($template, &$data)
    {
        $tokens =& $this->tokenize($template);
//        print_r($tokens); exit;
        $i = 0;
        $this->content = $this->precompile($i, $tokens);
//        print_r($this->content);
        $result = $this->compile($this->content, $data);
//        if ($this->clean_mode)
//            $result = preg_replace($this->value_pattern, '', $result);
        return $result;
    }

    function readTemplate($file_name, $alert = true)
    {
//         $full_name = $this->root_directory . $file_name;
//         if (!file_exists($full_name))
//         {
// 	        if ($alert)
// 	            $this->alert('File not found - ' . $full_name);
//             return false;
//         }
// 		$template = file_get_contents($full_name);
//         return $template;
        $full_name = $this->root_directory . $file_name;
	    if (file_exists($full_name) && (is_file($full_name)))
	    {
		    if ($this->enable_cache)
		    {
				$cache_dir = $this->cache_path . dirname($file_name);
				if (! (file_exists($cache_dir) && is_dir($cache_dir)))
					mkdir($cache_dir, 0770, true);
				$cache_file_name = $this->cache_path . $file_name . '.cache';
				if (file_exists($cache_file_name) && ((filemtime($cache_file_name) - filemtime($full_name)) > 0))
				{
					$this->cache_hit = true;
					$content = unserialize(file_get_contents($cache_file_name));
				}
				else
					$this->cache_hit = $content = false;
			}
			else
				$this->cache_hit = $content = false;
	    }
	    else
	    {
	        if ($alert) $this->alert('File not found - ' . $full_name);
	        if (! $this->quiet_mode) print_r($data);
	        return false;
        }

        if (! is_array($content))
        {
			$template = file_get_contents($full_name);
			if (trim($template) == '')
	            return $template;
	        $tokens =& $this->tokenize($template);
	        $i = 0;
	        $content = $this->precompile($i, $tokens);
	        if ($this->enable_cache)
	        	file_put_contents($cache_file_name, serialize($content));
    	}
    	return $content;
    }

    function enableCache($cache_path)
    {
	    if ($cache_path != '')
	    {
		    $maked = false;
		    if (! file_exists($cache_path))
		    	$maked = mkdir($cache_path, 0770, true);
		    if ($maked || (is_dir($cache_path) && is_writable($cache_path)))
		    {
			    $this->enable_cache = true;
			    $this->cache_path = $cache_path . ($cache_path[strlen($cache_path) - 1] != '/' ? '/' : '');
			    return true;
		    }
	    }
	    return false;
    }

    function parseFile($file_name, &$data)
    {
		$this->content = $this->readTemplate($file_name);
        return $this->compile($this->content, $data);
    }

    function reParse(&$data)
    {
        $result = $this->compile($this->content, $data);
//         if ($this->clean_mode)
//             $result = preg_replace($this->value_pattern, '', $result);
        return $result;
    }

    function precompile(&$index, &$tokens, $macro_params = array(), $in_macro = '')
    {
        $result = array();
        $key = $index;
        while (array_key_exists($key, $tokens))
        {
            $token = $tokens[$key];
            if (is_array($token))
            {
                list($name, $params) = $token;
                if ($name == MACRO_KEYWORD_BEGIN)
                {
                    $params = explode(',', $params);
                    $macro = array_shift($params);
                        $key++;
                    if (!array_key_exists($macro, $this->registered_macro))
                    {
                        $this->alert("Unknown macro $macro");
                        continue;
                    }
                    else
                        $result[] = array('TYPE' => TYPE_MACRO, 'NAME' => $macro, 'PARAMS' => $params, 'CONTENT' => $this->precompile($key, $tokens, $params, $macro));
                }
                elseif ($name == MACRO_KEYWORD_END)
                {
                    if (!$in_macro)
                        $this->alert("Unknown end macro " . MACRO_KEYWORD_END . "($params) in macro $in_macro");
                    else
                    {
                        $dummy = explode(',', $params);
                        if (array_shift($dummy) != $in_macro)
                        {
                            $str_params = implode(',', $macro_params);
                            $this->alert("Mismatch end macro " . MACRO_KEYWORD_END . "($params) for macro $in_macro($str_params)");
                        }
                        else
                            break;
                    }
                }
                else
                {
                    if (!$in_macro)
                        $this->alert('Unknow submacro ' . $token[0]);
                    else
                        $result[] = array('TYPE' => TYPE_SUBMACRO, 'NAME' => $token[0], 'PARAMS' => $token[1]);
                }
            }
            else
            {
                $result[] = $token;
            }
            $key++;
        }
        if (($in_macro) && !array_key_exists($key, $tokens))
            $this->alert("Begin macro $in_macro (" . (is_array($macro_params) ? implode(',', $macro_params) : $macro_params) . ') without end. ' );
        $index = $key;
        if (array_key_exists($in_macro, $this->registered_macro))
            $result = $this->registered_macro[$in_macro]->precompile($result, $macro_params);
        return $result;
    }

    function compile(&$content, &$data)
    {
        $result = '';
        if (!is_array($content)) return '';
        foreach ($content as $token)
        {
            if (is_array($token))
                $result .= $this->registered_macro[$token['NAME']]->compile($token['CONTENT'], $token['PARAMS'], $data);
            else
                $result .= $token;
        }
//        $result = preg_replace($this->simplevalue_pattern, '(($data[\'\1\']!==null)?$data[\'\1\']:\'{\1}\')', $result);
        $result = preg_replace($this->value_pattern, '$this->evaluateValue($data, \'$1\',\'$4\',str_replace(\'\"\', \'"\', \'$6\'))', $result);		// need to unescape " which automatically escaped by PHP (cannot use "$6" because there may be $ in the value of $6
        return $result;
    }

    function getValue(&$data, $val_name)			// the $val_name is guaranteed to be scalar, otherwise checking is needed in this function
    {
	    if (! is_string($val_name))
	    {
		    $this->alert('Calling getValue with val_name not string (' + $val_name + ').');
		    return false;
	    }

	    if ($val_name == '')
	    {
		    $this->alert('Calling getValue with blank val_name');
		    return false;
	    }

	    // the $ value pointer can be used through out every (not quite sure) value in macro, submacro, and value. it seemingly could be recursive
        if ($val_name[0] == '$')                           // convert value name for value pointer
        {
            $val_name = substr($val_name, 1);
            $val_name = $this->getValue($data, $val_name);
        }
        $name_list = explode('/', $val_name);
        $sub_data =& $data;
        while (is_array($sub_data) && (! array_key_exists($name_list[0], $sub_data)) && array_key_exists('PARENT', $sub_data))
            $sub_data =& $sub_data['PARENT'];
        if (! is_array($sub_data))
            return false;
        foreach ($name_list as $sub_name)
        {
//            $used_list[] = $sub_name;
            if (is_array($sub_data) && array_key_exists($sub_name, $sub_data))
                $sub_data =& $sub_data[$sub_name];
            else
                return false;
//                $this->alert('Unknown value ' . implode('.', $used_list));
        }
        return $sub_data;
    }

    // $data must not be changed within this function even though it is passed by reference
    function evaluateValue(&$data, $val_name, $func_name, $param)
    {
        if (!$func_name)
        {
            $value = $this->getValue($data, $val_name);
            if ($value === false)
            {
                if ($this->clean_mode)
                    return '';
                else
                    return '{' . $val_name . '}';
            }
            else
            {
	            if (is_array($value) && array_key_exists('Id', $value))		// to support scoopf
	            	$value = $value['Id'];
                if ($this->html_mode)
                {
                    if (($value == '') || (is_scalar($value)))            // scalar does not include blank, null, false
                    	return str_replace(array('{', '}'), array('&#123;', '&#125;'), htmlspecialchars($value, ENT_COMPAT, '', false));
                    else
                        $this->alert("'" . $val_name . "' is not a scalar value. It may be array, object, or resource. (" . serialize($value) . ')');
                }
                else
                    return $value;
            }
        }
        else
        {
            $funcs = explode('|', $func_name);					// support function piping
            $params = explode('|', $param);
            $piped = true;
            foreach ($funcs as $k => $func_name)
            {
	            $param = (array_key_exists($k, $params) ? $params[$k] : '');
	            if (array_key_exists($func_name, $this->registered_function))
	            {
	                $func_obj =& $this->registered_function[$func_name];
	                while (!is_object($func_obj) && is_array($func_obj))
	                {
	                    $param = $func_obj[1];
	                    $func_obj =&  $this->registered_function[$func_obj[0]];     // note that param will be overridden by the deepest param
	                }
	                if (is_object($func_obj))
	                {
	                    $result = $func_obj->evaluate($data, $val_name, $param);
//                    if ($value !== false)
//                        return $func_obj->evaluate($value, $param); // ***
//                    else
//                        return '{' . $val_name . '}';
	                }
	                elseif (!is_array($func_obj))
	                    $this->alert("CTemplateParser protection fault");
	            }
	            else
	                $this->alert("Unknown function $func_name");

                if ($piped)
                {
	                if ($k > 0) unset($data[$val_name]);		// $val_name from the previous loop
                	$val_name = 'PIPED_' . $k;			// new $val_name
                	$data[$val_name] = $result;
            	}
            }
            if ($piped)
	            unset($data[$val_name]);
            return $result;
        }
    }

}

?>
