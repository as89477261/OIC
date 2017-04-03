<?php 
// for converting inputs(xxxYear,xxxMonth,xxxDay) from HTML
function getDateFromHtml($arg_prefix, &$arg_input)
{
    return $arg_input[$arg_prefix.'Year'] . '-' . $arg_input[$arg_prefix.'Month'] . '-' . str_pad($arg_input[$arg_prefix.'Day'], 2, '0', STR_PAD_LEFT);
}

function mergeDateArray(&$arg_input, $arg_prefix, $arg_date)
{
    list ($arg_date, $time) = explode(' ', $arg_date);
    list ($year, $month, $day) = explode('-', $arg_date);
    $arg_input[$arg_prefix.'Year'] = $year;
    $arg_input[$arg_prefix.'Month'] = $month;
    $arg_input[$arg_prefix.'Day'] = $day;
    if ($time)
        $arg_input[$arg_prefix.'Time'] = $time;
    return $arg_input;
}

// function extractHtmlArray()
//          retrieve data reading from Html Form and rearrange it into organized array
// $input   array of data to be used (normally come directly from Html Form
// $prefixes array of prefixes of input name to group into the same set of key
// $keys    array of key name of input name separated by '_' and need to be ordered as appears in the input name
//          if not specified or specified less than the actual number of value from input, 'ValN' is assumed
// $user_merge array of (key => value) of user data to merge to each result row
// return   result array
function extractHtmlArray($arg_input, $arg_prefixes, $arg_keys = array(), $arg_user_merge = array())
{
    $assume_keyname = 'Val';
    if ((count($arg_prefixes) == 0) || (count($arg_input) == 0))
        return null;
    $regexp = '/^(' . implode('|', $arg_prefixes) . ')_(.+)/';
    $input_keys = preg_grep($regexp, array_keys($arg_input));
    $done = array ();
    $output = array ();
    foreach ($input_keys as $in_key)
    {
        $row = $arg_user_merge;
        preg_match($regexp, $in_key, $match);
        if ($done[$match[2]]) continue;           // this is useful for multiple prefixes
        $done[$match[2]] = true;
        $out_key_vals = explode('_', $match[2]);
        foreach ($arg_keys as $n => $k)
            $row[$k] = $out_key_vals[$n];
        if (count($out_key_vals) > count($arg_keys))
        {
            $j = count($out_key_vals);
            for ($i = count($arg_keys); $i < $j; $i++)
                $row[$assume_keyname.$i] = $out_key_vals[$i];
        }
        foreach ($arg_prefixes as $k)
            $row[$k] = $arg_input[$k.'_'.$match[2]];
        $output[] = $row;
    }
    return $output;
}

function encodeHtmlSpecialChars($arg_values)
{
    if (is_scalar($arg_values))
        return htmlspecialchars($arg_values, ENT_QUOTES);
    elseif (is_array($arg_values))
    {
        foreach ($arg_values as $key => $value)
            $arg_values[$key] = encodeHtmlSpecialChars($value);
        return $arg_values;
    }
    else
        return $arg_values;
}

?>
