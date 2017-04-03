<?

class CTemplateMacro
{

    var $parser;
    var $macro_key;
    var $submacro_list = array();

    function CTemplateMacro(&$parser)
    {
        $this->parser = &$parser;
    }

    function filterSubMacro(&$content)
    {
        $result = array();
        foreach ($content as $token)
        {
            if (is_array($token) && ($token['TYPE'] == TYPE_SUBMACRO))
            {
                if (in_array($token['NAME'], $this->submacro_list))
                    $result[] = $token;
                else
                    $this->parser->alert('Unknown submacro ' . $token['NAME'] . ' of macro ' . $this->macro_key);
            }
            else
                $result[] = $token;
        }
        return $result;
    }

    function precompile(&$content, &$params)
    {
        return $this->filterSubMacro($content);
    }

    function compile(&$content, &$params, &$data)
    {
        foreach ($content as $token)
            $result = (is_array($token) ? '' : $token);
        return $result;
    }

}

class CTemplateMacroSelect extends CTemplateMacroForeach
{
    function compile(&$content, &$params, &$input)
    {
        $data = $this->parser->getValue($input, $params[0]);
        if ($data === false)
        {
            $this->parser->alert('There is no data input ' . $params[0] . ' for macro ' . $this->macro_key);
            return;
        }
        if (!is_array($data))
        {
            $this->parser->alert('Data for macro ' . $this->macro_key . ' is not array');
            return;
        }
        $result = '';
        $this->prepareInput($data, $input);
        $key_name = $params[1];		// optimized for speed
        foreach ($data as $key => $row)
        {
            $compare = $this->parser->getValue($input, $params[2]);
            if (array_key_exists($params[1], $row) && ($compare == $row[$key_name]))
                $result .= $this->parser->compile($content, $row);
        }
        return $result;
    }
}

class CTemplateMacroForeach extends CTemplateMacro
{
	var $submacro_list = array('RECURSE');

    function prepareInput(&$data, &$input)
    {
	    $i = 0;
        foreach ($data as $key => $row)
        {
            if (is_array($row))
				$row['ARRAY_VALUES'] = $row;
			else
                $row = array('ARRAY_VALUE' => $row);
            $row['ARRAY_KEY'] = $key;
            $row['ROW_ORDER'] = $i++;
            $row['PARENT'] = &$input;
            $data[$key] = $row;
        }
    }

    // support multiple params to do multiple-foreach using the same content block (added on 07/01/2010)
    function compile(&$content, &$params, &$input)
    {
	    foreach ($params as $param)
	    {
	        $data = $this->parser->getValue($input, $param);

	        if ($data === false)
	        {
	            $this->parser->alert('There is no data input ' . $param . ' for macro ' . $this->macro_key);
	            return;
	        }
	        if (!is_array($data))
	        {
	            $this->parser->alert('Data ' . $param . ' for macro ' . $this->macro_key . ' is not array');
	            return;
	        }
	        $result = '';
	        $this->prepareInput($data, $input);
	        foreach ($data as $key => $row)
	        {
		        $dup_content = $content;
		        foreach ($content as $kkk => $token)
		        {
		            if (is_array($token) && ($token['TYPE'] == TYPE_SUBMACRO))
		            {
			            list($data_name) = explode(',', $token['PARAMS']);	// currently use only 1 param
// 						if ($token['NAME'] == 'RECURSE')		// enable this line or change to switch() if there are other submacro
						if (array_key_exists($data_name, $row))
						{
				            $row['_Recurse_' . $data_name] = $this->compile($content, $params, $row);		// recurse by changing only input to the children, the content and params is the same as parent.
				            $dup_content[$kkk] = '{_Recurse_' . $data_name . ',SRC}';		// change from submacro to simple value, ** should edit SRC function to some base thing.
			            }
			            else
			            	unset($dup_content[$kkk]);
		            }
	            }
	            $result .= $this->parser->compile($dup_content, $row);
	        }
/*
	        if (((! $data) && ($data !== 0)) || (is_array($data) && count($data) <= 0))		// if data is blank or blank array
	        	return;
	        if (! is_array($data))		// if data has value but not array, return error
	        {
	            $this->parser->alert('Data ' . $params[0] . ' for macro ' . $this->macro_key . ' is not array');
	            return;
	        }

	        $result = '';
	        $this->prepareInput($data, $input);
	        foreach ($data as $key => $row)
	            $result .= $this->parser->compile($content, $row);
*/
		}
        return $result;
    }

}

/*
<? BEGIN(CASE,val_name) ?>
<? CASE(val1,val2,val3,...) ?> valn can be any string, integer, float value, '%NULL' for null, blank for '', or in format '%n' for the case of result of (value % num_of_cases) == n
<? CASE(%DEFAULT) > default case cannot combine with any other cases
*/
class CTemplateMacroCase extends CTemplateMacro
{

    function precompile(&$content, &$params)
    {
    //print_r($content);
        $result = array();
        $case_default = array();
        $current = &$result;
        $cases = null;
        $current_case = null;
        $cases_count = 0;
        foreach ($content as $token)
        {
            if (is_array($token) && ($token['TYPE'] == TYPE_SUBMACRO))
            {
                switch ($token['NAME'])
                {
                    case 'CASE':
                        if ($current_case)
                            $cases[] = $current_case;
                        if ($token['PARAMS'] == '%DEFAULT')
                        {
                            $case_default = array();
                            $current = &$case_default;
                            $cases_count++;
                            $current_case = null;
                        }
                        else
                        {
                            $current_case = array('PARAMS' => explode(',', $token['PARAMS']));
                            $cases_count += count($current_case['PARAMS']);
                            $current_case['CONTENT'] = array();
                            $current = &$current_case['CONTENT'];
                        }
                        break;
                    default:
                        $this->parser->alert('Unknown submacro ' . $token['NAME'] . ' of macro ' . $this->macro_key);
                        break;
                }
            }
            else
            {
                $current[] = $token;
            }
        }
        if ($current_case)
            $cases[] = $current_case;
        $result[] = array('TYPE' => TYPE_SUBMACRO, 'NAME' => 'CASE', 'DEFAULT' => $case_default, 'CASES' => $cases, 'CASES_COUNT' => $cases_count);
        return $result;
    }

    function choose(&$cases, &$params, &$input)
    {
	    if (! is_array($cases)) return null;
        if (count($params) == 0)
            $value = $input['ROW_ORDER'];
        else
        {
            $data = &$input;
//            while (!array_key_exists($params[0], $data) && array_key_exists('PARENT', $data))
//                $data = &$input['PARENT'];
//            $value = $data[$params[0]];
            $value = $this->parser->getValue($data, $params[0]);
        }
        $count = $this->cases_count;
        foreach ($cases as $case)
        {
            foreach ($case['PARAMS'] as $case_val)
            {
				if ($case_val == '')
				{
					$this->parser->alert('Blank case is not allowed in macro ' . $this->macro_key . ' (all casess = ' . implode(',', $case['PARAMS']) . ')');
					continue;
				}
                if ($case_val[0] == '%')
                {
                    $case_val = substr($case_val, 1);
                    switch($case_val)
                    {
                        case 'NULL':
                            if ($value === null)
                                return $case['CONTENT'];
                            break;
                        case 'BLANK':
                            if ((is_array($value) && (count($value) <= 0)) ||
                                ($value == ''))
                                return $case['CONTENT'];
                            break;
                        default:
                            if (!is_int($value))
                                $value = 0;
                            if (intval($value % $count) == intval($case_val))
                                return $case['CONTENT'];
                            break;
                    }
                }
                elseif ($case_val[0] == '$')
                {
	                $case_val = $this->parser->getValue($data, substr($case_val, 1));
                    if (($value === $case_val) || (($case_val != '') && ($value == $case_val)))
                        return $case['CONTENT'];
                }
                else
                {
                    if (($value === $case_val) || (($case_val != '') && ($value == $case_val)))
                        return $case['CONTENT'];
                }
            }
        }
        return null;
    }

    function compile(&$content, &$params, &$input)
    {
        $selected_content = array();
        foreach ($content as $token)
        {
            if (is_array($token) && ($token['TYPE'] == TYPE_SUBMACRO))
            {
                switch ($token['NAME'])
                {
                    case 'CASE':
                        $this->cases_count = $token['CASES_COUNT'];
                        $case_content = $this->choose($token['CASES'], $params, $input);
                        if ($case_content === null)
                        {
                            if ($token['DEFAULT'])
                                $selected_content = array_merge($selected_content, $token['DEFAULT']);
                        }
                        else
                            $selected_content = array_merge($selected_content, $case_content);
                        break;
                    default:
                        $this->parser->alert('Unknown submacro ' . $token['NAME'] . ' of macro ' . $this->macro_key);
                        break;
                }
            }
            else
            {
                $selected_content[] = $token;
            }
        }
        return $this->parser->compile($selected_content, $input);
    }

}

class CTemplateMacroInclude extends CTemplateMacro
{

    function precompile(&$content, &$params)
    {
//         $template = $this->parser->readTemplate($params[0], (@$params[1] != CONST_NO));
//         if (!$template) return '';
//         $tokens = & $this->parser->tokenize($template);
//         $i = 0;
//         $content = $this->parser->precompile($i, $tokens);
// 		return array();
        return $content;
    }

    function compile(&$content, &$params, &$input)
    {
        $content = $this->parser->readTemplate($params[0], (@$params[1] != CONST_NO));
        $result = $this->parser->compile($content, $input);
        return $result;
    }

}

class CTemplateMacroCaseMatchOne extends CTemplateMacroCase
{

    function choose(&$cases, &$params, &$input)
    {
        $result = null;
        while (($result === null) && (list(, $val_name) = each($params)))
        {
	        // check if the value of val_name is array, then loop inside the array value
	        $data = $this->parser->getValue($input, $val_name);
	        if (is_array($data))
	        {
		        foreach ($data as $key => $v)
		        {
		        	$dummy = array($val_name . '/' . $key);
		        	$result = parent::choose($cases, $dummy, $input);
		        	if ($result) break;		// break when found, otherwise the next result (probably not-found) will overwrite
	        	}
	        }
	        else
	        {
	            $dummy = array($val_name);
	            $result = parent::choose($cases, $dummy, $input);
        	}
        }
        if (!$result)
            return null;
        else
            return $result;
    }

}

class CTemplateMacroCaseBlank extends CTemplateMacro
{
    var $submacro_list = array('ELSE');

    function precompile(&$content, &$params)
    {
	    $result = array('CASE_BLANK' => array(), 'CASE_ELSE' => array());
        $all = $this->filterSubMacro($content);
        $p =& $result['CASE_BLANK'];
        foreach ($all as $token)
        {
            if (is_array($token) && ($token['TYPE'] == TYPE_SUBMACRO) && ($token['NAME'] == 'ELSE'))
            	$p =& $result['CASE_ELSE'];
        	else
        		$p[] = $token;
        }
        return $result;
    }

    function compile(&$content, &$params, &$input)
    {
        $value = $this->parser->getValue($input, $params[0]);
        if ((is_array($value) && (count($value) <= 0)) || ((! $value) && ($value !== '0')))
    		return (count($content['CASE_BLANK']) > 0) ? $this->parser->compile($content['CASE_BLANK'], $input) : '';
    	elseif (count($content['CASE_ELSE']) > 0)
        	return $this->parser->compile($content['CASE_ELSE'], $input);
//         {
// 	        $case_content = array($content[0]);
// 	        return $this->parser->compile($case_content, $input);
//         }
//         elseif (count($content) > 2)		// automatically assume the 2nd token is submacro 'ELSE' declaration and the 3rd token is content for 'ELSE'
//         {
// 	        $case_content = array($content[2]);
//         	return $this->parser->compile($case_content, $input);
//     	}
    	else
            return '';
    }
}

// don't need to support else because the primer function can be used instead
// support multiple params to test if one of them is not blank (added on 07/01/2010)
class CTemplateMacroCaseNotBlank extends CTemplateMacro
{
    function compile(&$content, &$params, &$input)
    {
	    foreach ($params as $param)
	    {
	        $value = $this->parser->getValue($input, $param);
	        if ((is_array($value) && (count($value) <= 0)) || ((! $value) && ($value !== '0')))
	            continue;
	        else
		        return $this->parser->compile($content, $input);
        }
	    return '';
    }
}

class CTemplateMacroWith extends CTemplateMacro
{
	var $submacro_list = array('RECURSE');

    function compile(&$content, &$params, &$input)
    {
        $data = $this->parser->getValue($input, $params[0]);
		if (! is_array($data))
			return null;
        $data['PARENT'] =& $input;
        $dup_content = $content;

        foreach ($content as $kkk => $token)
        {
            if (is_array($token) && ($token['TYPE'] == TYPE_SUBMACRO))
            {
	            list($data_name) = explode(',', $token['PARAMS']);	// currently use only 1 param
// 		            if ($token['NAME'] == 'RECURSE')		// enable this line or change to switch() if there are other submacro
				if (array_key_exists($data_name, $data) && is_array($data))
				{
		            $data['_Recurse_' . $data_name] = $this->compile($content, $params, $data);		// recurse by changing only input to the children, the content and params is the same as parent.
		            $dup_content[$kkk] = '{_Recurse_' . $data_name . '}';		// change from submacro to simple value
	            }
	            else
	            	unset($dup_content[$kkk]);
            }
        }
        $result = $this->parser->compile($dup_content, $data);

        return $result;
    }
}

class CTemplateMacroWithFirstOf extends CTemplateMacro
{
    function compile(&$content, &$params, &$input)
    {
        $data = $this->parser->getValue($input, $params[0]);
        if (((! $data) && ($data !== 0)) || (is_array($data) && count($data) <= 0))		// if data is blank or blank array
        	return null;
        if (! is_array($data))		// if data has value but not array, return error
        {
            $this->parser->alert('Data ' . $params[0] . ' for macro ' . $this->macro_key . ' is not array');
            return;
        }

        reset($data);
        list($key, $row) = each($data);
        $row['PARENT'] =& $input;
        $row['ARRAY_KEY'] = $key;
        $row['ROW_ORDER'] = 0;
        $result = $this->parser->compile($content, $row);
        return $result;
    }
}

class CTemplateMacroPick extends CTemplateMacro
{
    function compile(&$content, &$params, &$input)
    {
	    $new_name = array_shift($params);
		foreach ($params as $param)
		{
	        $dummy = $this->parser->getValue($input, $param);
	        if ($dummy) break;
        }
		if ((! $dummy) || (count($dummy) < 1))
			return '';

		$data[$new_name] =& $dummy;
        $data['PARENT'] =& $input;
        $result = $this->parser->compile($content, $data);
        return $result;
    }
}

class CTemplateMacroLoop extends CTemplateMacro
{
    function compile(&$content, &$params, &$input)
    {
	    $count = $params[0];
	    if (! is_numeric($count))
	        $count = $this->parser->getValue($input, $count);
        $count = intval($count);
        $result = ''; $i = 0;
        for (; $count > 0; $count--)
        {
	        $input['LOOP_COUNT'] = ++$i;
        	$result .= $this->parser->compile($content, $input);
    	}
        return $result;
    }
}

class CTemplateMacroEnumRow extends CTemplateMacro
{
    function compile(&$content, &$params, &$input)
    {
	    if (count($params) > 0)
	    {
		    $result = '';
	    	foreach ($params as $param)
	    	{
		        $data = $this->parser->getValue($input, $param);
		        $data['PARENT'] =& $input;
		        $result .= $this->enumerate($content, $data);
	        }
        }
        else
        	$result = $this->enumerate($content, $input);
        return $result;
    }

    function enumerate(&$content, &$input)
    {
	    $result = '';
		if (! is_array($input))
			return null;

		foreach ($input as $k => $v)
		{
			if ($k == 'PARENT') continue;		// skip 'PARENT' as it is not a real item in row
			$data = array('ROW_KEY' => $k, 'ROW_VALUE' => $v, 'PARENT' => & $input['PARENT']);
            $result .= $this->parser->compile($content, $data);
        }
        return $result;
    }
}

?>