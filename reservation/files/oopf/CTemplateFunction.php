<?

class CTemplateFunction
{
    var $parser;

    function CTemplateFunction($parser = '')
    {
        $this->parser =& $parser;
    }

    function getValueIfVar(&$data, &$val_var)
    {
		if ($val_var && ($val_var{0} == '$'))
        	$val_var = $this->parser->getValue($data, substr($val_var,1));
    }

}

class CTemplateFunctionLangNumber extends CTemplateFunction
{
    var $nums = array('src' => array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
    				  'th' => array('๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'));

    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        list($lang) = split(',', $param);
        if ($lang{0} == '$')
        	$lang = $this->parser->getValue($data, substr($lang,1));
        return array_key_exists($lang, $this->nums) ? str_replace($this->nums['src'], $this->nums['th'], $val) : $val;
    }
}

class CTemplateFunctionNumber extends CTemplateFunction		// remove lang support when oopf support template function pipelining
{

	function switchParam($p)
	{
		switch($p) {
			case '':			// if nothing specified, then it should be ','
			case CONST_YES:  return ',';
			case CONST_NO: return '';
			default: return $p;
		}
	}

    function evaluate(&$data, $name, $param)
    {
	    $val = $this->parser->getValue($data, $name);
	    if (! is_numeric($val)) return $val;
        if ($param == '')
        {
            $decimal = 2; $thousand = ','; $period = '.';
        }
        else
        {
            $p = explode(',', $param);
            $decimal = $p[0];
            if (count($p) > 1)
                $thousand = $this->switchParam($p[1]);
            else
                $thousand = ',';
            if ($decimal == 0)
            	$period = '';
            elseif (count($p) > 2)
                $period = $this->switchParam($p[2]);
            else
                $period = '.';
        }

    	return number_format($val, $decimal, $period, $thousand);
    }
}

class CTemplateFunctionSpace2Nbsp extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        return strtr($val, array(' ' => '&nbsp;'));
    }
}

class CTemplateFunctionDump extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        return print_r($val);
    }
}

class CTemplateFunctionNoop extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        return '{' . $param . '}';
    }
}

class CTemplateFunctionSource extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        return $this->parser->getValue($data, $name);
    }
}

class CTemplateFunctionNl2Br extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        $indent = $param;
        return $indent . implode('<br />' . $indent, preg_split('/\r\n|\n\r|\n/', $val));
//        return $indent . implode('<br>' . $indent, preg_split('/\r(?!\r)|\n(?!\n)|\n\r|\r\n]/', $val));
    }
}

class CTemplateFunctionCount extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        if ($val === false)
            $val = null;
        return eval('return ' . count($val) . $param . ';');
    }
}

class CTemplateFunctionCountDividing extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        if ($val === false)
            $val = null;
        if (! $param)
            $param = 100;
        if (count($val) == 0)            // prevent dividing by zero
            return $param;
        return eval('return intval(' . $param . '/' . count($val) . ');');
    }
}

class CTemplateFunctionEmailFormatting extends CTemplateFunction
{
	var $sign_search = array('@', '.');
    var $patterns = array (
                      'ANTISPAM_1' => 'toAntiSpam',
                      'FANCY' => 'toFancy'
                      );
	var $default = 'ANTISPAM_1';

    function isValidFormatted($val)
    {
	    return (preg_match('/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD', $val));
//         return (preg_match('/^(([\w-]+\.)+[\w-]+|([a-zA-Z]{1}|[\w-]{2,}))@((([0-1]?[0-9]{1,2}|25[0-5]|2[0-4][0-9])\.([0-1]?[0-9]{1,2}|25[0-5]|2[0-4][0-9])\.([0-1]?[0-9]{1,2}|25[0-5]|2[0-4][0-9])\.([0-1]?[0-9]{1,2}|25[0-5]|2[0-4][0-9])){1}|([a-zA-Z]+[\w-]+\.)+[a-zA-Z]{2,4})$/', $val));
    }

    function toAntiSpam($pattern, $email_src)
    {
	    $repl = array('(a)', ',');
	    $email_dst = str_replace($this->sign_search, $repl, $email_src);
	    return $email_dst;
	    //can be varied by switching by the pattern specified.
    }

    function evaluate(&$data, $name, $param)
    {
	    $val = $this->parser->getValue($data, $name);
        if ($this->isValidFormatted($val))
        {
            if (($param == '') || (! array_key_exists($param, $this->patterns)))
            	$param = $this->default;
	        $func = $this->patterns[$param];
	        return $this->$func($param, $val);
        }
        else
            return 'wrong_e-mail_'.$val;
    }
}

class CTemplateFunctionDate extends CTemplateFunction
{
    var $format = array (
                      'SHORT' => 'd M Y',
                      'YEAR' => 'Y',
                      'SHORT_NB' => 'd\&\n\b\s\p\;M\&\n\b\s\p\;Y',
                      'LONG' => 'F jS, Y',
                      'LONG_NB' => 'F\&\n\b\s\p\;jS,\&\n\b\s\p\;Y',
                      'DATETIME_SHORT' => 'd M Y H:i',
                      'SHORTDATE_FULLTIME' => 'd M Y H:i:s',
                      'FULLDATETIME' => 'D M d H:i:s Y T',
                      'NO_YEAR' => 'd M',
                      'MONTHYEAR' => 'F Y',
                      'DATENUM' => 'd',
                      'DATEORDINAL' => 'dS',
                      'SHORT_MONTH' => 'M',
                      'FULL_MONTH' => 'F'
                  );
    var $default = 'SHORT';

    function setFormat($f_name, $f)
    {
        $this->format[$f_name] = $f;
    }

    function isValidFormatted($val)
    {
        return ((preg_match('/(^\d{4}\-\d{1,2}\-\d{1,2}$)/', $val)) || (preg_match('/(^\d{4}\-\d{1,2}\-\d{1,2}\ \d{1,2}:\d{1,2}:\d{1,2}$)/', $val)));
    }

    function evaluate(&$data, $name, $param)
    {
	    $param = explode(',', $param);
	    $val_def = (count($param) > 1 ? $param[1] : '');
    	$param = ($param[0] ? $param[0] : $this->default);

        if ($name == '')
        {
        	if ($val_def == '')
	            return date($this->format[$param]);
            else
            	$val = $val_def;
    	}
    	else
	        $val = $this->parser->getValue($data, $name);
        if (is_numeric($val) && in_array($param, array('SHORT_MONTH', 'FULL_MONTH')))
	        return date($this->format[$param], mktime(0, 0, 0, $val, 10));
        elseif ($this->isValidFormatted($val))
        {
            list($param) = explode(',', $param);
            return date($this->format[$param], strtotime($val));
        }
        else
            return $val;
    }
}

class CTemplateFunctionTime extends CTemplateFunctionDate
{
    var $format = array (
                      'FULL' => 'H:i:s',
                      'SHORT' => 'H:i',
                      'FULL12' => 'h:i:sa',
                      'SHORT12' => 'h:ia'
                  );

    function isValidFormatted($val)
    {
        return ((preg_match('/(^\d{1,2}(:\d{1,2}){0,2}\s*(am|pm)?$)/i', $val)) || parent::isValidFormatted($val));
    }
}

class CTemplateFunctionBaseHref extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        return '<base href="' . $val . '" />';
    }
}

class CTemplateFunctionIf extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        $params = explode(',', $param);
		$this->getValueIfVar($data, $params[0]);
        if (count($params) < 2)
            return '';
        else
    		$this->getValueIfVar($data, $params[1]);
// 		if ($params[1] && ($params[1]{0} == '$'))
//         	$params[1] = $this->parser->getValue($data, substr($params[1],1));
        if (count($params) < 3)
            $params[2] = $val;
        else
    		$this->getValueIfVar($data, $params[2]);
//         if ($params[2] && ($params[2]{0} == '$'))
//         	$params[2] = $this->parser->getValue($data, substr($params[2],1));

        return (($val == $params[0]) ? $params[1] : $params[2]);
    }
}

class CTemplateFunctionIfInArray extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val_array = $this->parser->getValue($data, $name);
        if (! is_array($val_array))
            $val_array = (strpos($val_array, ',') !== false ? explode(',', $val_array) : array($val_array));
        $params = explode(',', $param);
		$this->getValueIfVar($data, $params[0]);
        if (count($params) < 2)
            return '';
        else
			$this->getValueIfVar($data, $params[1]);
        if (count($params) < 3)
            $params[2] = '';
        else
			$this->getValueIfVar($data, $params[2]);
        return ((in_array($params[0], $val_array)) ? $params[1] : $params[2]);
    }
}

// Example: {Var0,INDEXBY(Var1/Var2/Var3)}
class CTemplateFunctionIndexBy extends CTemplateFunction
{

    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        if (is_array($val))
        {
            $index = $this->parser->getValue($data, $param);
            if (($index !== false) && (array_key_exists($index, $val)))
                return $val[$index];
            else
                return '';
        }
        else
            return '';
    }
}

// Example: {Var1,INDEX(Val1/Val2/Val3)}
class CTemplateFunctionIndex extends CTemplateFunction
{

    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        if (is_array($val))
        {
	        $index = explode('/', $param);
        	$i = 0; $c = count($index); $w =& $val;
	        while (($i < $c) && array_key_exists($index[$i], $w))
	        	$w =& $w[$index[$i++]];
        	return $w;			// return something no matter found or not
        }
        else
            return '';
    }
}

class CTemplateFunctionHtmlEncode extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        return htmlentities($val);
    }
}

// support selected item, added on 03 Oct 2009
// support tree key and level, added on 06 Nov 2009
class CTemplateFunctionOptions extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $list = $this->parser->getValue($data, $name);
        $result = '';
        @list($selected, $enable_blank, $blank_text, $tree_key, $tree_prefix) = explode(',', $param);
        if ($selected)
	        $selected = strval($this->parser->getValue($data, $selected));
        if ($enable_blank == CONST_YES)
            $result .= '<option value="">' . $blank_text . '</option>';
        if (is_array($list))
            foreach ($list as $item)
            {
	            if ($selected == $item[WI_LIST_VALUE]) $item[WI_SELECTED] = WI_SELECTED;
	            $prefix = (array_key_exists($tree_key, $item) && $tree_prefix) ? str_repeat($tree_prefix, intval($item[$tree_key])) : '';
                $result .= '<option value="'. $item[WI_LIST_VALUE] . '" ' . @$item[WI_SELECTED] . '>' . $prefix . htmlspecialchars($item[WI_LIST_TEXT]) . '</option>';
            }
        return $result;
    }
}

class CTemplateFunctionJsQuote extends CTemplateFunction
{
	var $subj = array("'" => array('\\', "'"), '"' => array('\\', '"'));
	var $rep = array("'" => array('\\\\', "\\'"), '"' => array('\\\\', '\\"'));
    // use this template function when output is to be within quote of javascript.
    // you can specify type of quote to be replace. default is single-quote.
    function evaluate(&$data, $name, $param)
    {
        list($param) = explode(',', $param);
		if (($param != "'") && ($param != '"'))
            $param = "'";
        $rep =& $this->rep[$param];
        $sub =& $this->subj[$param];
        $val = $this->parser->getValue($data, $name);
        $val = str_replace($sub, $rep, $val);
        return $val;
    }
}

class CTemplateFunctionGetPid extends CTemplateFunction
{
	var $pid = '';

    function evaluate(&$data, $name, $param)
    {
	    if ($this->pid == '')			// if not exists, generate new one.
	    {
	        list($usec, $sec) = explode(' ', microtime());
	        $this->pid = date('YmdHis') . $usec . $sec;
        }
        return $this->pid;
    }
}

class CTemplateFunctionNumber2LoopText extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = intval($this->parser->getValue($data, $name));
        $result = '';
        for (;$val > 0; $val--)
            $result .= $param;
        return $result;
    }
}

class CTemplateFunctionImplode extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $result = '';
        $val = $this->parser->getValue($data, $name);
        if (is_array($val))
        {
            if ($param)
            {
                $params = explode(',', $param);
                // if no delimiter defined, comma is used. to use comma, define no delimiter.
                $key = array_shift($params);
                if (count($params) > 0)
                    $delimiter = array_shift($params);
                else
                    $delimiter = ', ';

                if ($key)
                    $val = extractArrayValue($val, $key);
            }
            $result = implode($delimiter, $val);
        }
        return $result;
    }
}

class CTemplateFunctionArrayToText extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $result = ''; $eq = '='; $ln = ';'; $imp = '';		// equal sign, end of line, implode glue
        $val = $this->parser->getValue($data, $name);
        if (is_array($val))
        {
            $p = ($param != '' ? explode(',', $param) : null);
            if (count($p) > 0) $eq = $p[0];
            if (count($p) > 1) $ln = $p[1];
            if (count($p) > 2) $imp = $p[2];
            foreach ($val as $k => $v)
            	$result[] = ($k . $eq . $v . $ln);
            $result = implode($imp, $result);
        }
        return $result;
    }
}

class CTemplateFunctionCutText extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
	    list($len, $suffix) = explode(',', $param);
	    $len = intval($len);
	    $val = $this->parser->getValue($data, $name);

	    if (function_exists('mb_substr'))
	    {
		    if (mb_strlen($val) <= $len) return $val;
	    	$val = mb_substr($val, 0, $len);
    	}
    	else
    	{
		    if (strlen($val) <= $len) return $val;
		    $ind = $count = 0;
		    $len_val = strlen($val);
			while (($len_val > $ind) && ($count < $len))
			{
				$chr = ord($val[$ind]);
				if (($chr & 248) == 240)				// check number of bytes of UTF-8 character
					$ind += 4;
				elseif (($chr & 240) == 224)
					$ind += 3;
				elseif (($chr & 224) == 192)
					$ind += 2;
				else
					$ind += 1;
				$count++;
			}
			$val = substr($val, 0, $ind);		// cut off
		}
		if (isset($suffix) && $suffix)
			$val = $val . $suffix;
		return $val;
    }
}
/*
class CTemplateFunctionEscape extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        return str_replace(array('{', '}'), array('&#123;', '&#125;'), $val);
    }
}
*/
class CTemplateFunctionSysVar extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
//         $val = $this->parser->getValue($data, $name);
		if (array_key_exists($param, $_SERVER))
	        return $_SERVER[$param];
    }
}

class CTemplateFunctionMod extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
	    $params = explode(',', $param);
	    if ((count($params) > 0) && (is_array($params)))
	    {
	        $val = intval($this->parser->getValue($data, $name));
	        return $params[$val % count($params)];
        }
        else
        	return '';
    }
}

class CTemplateFunctionCsvQuote extends CTemplateFunction
{
    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        $val = str_replace('"', '""', $val);
        return $val;
    }
}

?>