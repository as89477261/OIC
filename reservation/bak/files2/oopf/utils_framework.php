<?

function is_hash($var)
{
    // written by dev-nullØchristophe*vg
    // taken from http://www.php.net/manual/en/function.array-merge-recursive.php
    if (is_array($var)) {
        $keys = array_keys($var);
        $all_num = true;
        for ($i = 0; $i < count($keys); $i++) {
            if (is_string($keys[$i])) {
                return true;
            }
        }
    }
    return false;
}

// this function will remove the duplicated array with numeric key (using array_unique)
// seems weird. not sure which purpose will match this function
function array_join_merge($arr1, $arr2)
{
    if(is_array($arr1) and is_array($arr2))
    {
        // the same -> merge
        $new_array = array();

        if(is_hash($arr1) and is_hash($arr2))
        {
            // hashes -> merge based on keys
            $keys = array_merge(array_keys($arr1), array_keys($arr2));
            foreach($keys as $key)
            {
				if (!array_key_exists($key,$arr1)) {
				    $new_array[$key] = $arr2[$key];
				}
				elseif (!array_key_exists($key,$arr2)) {
				    $new_array[$key] = $arr1[$key];
				}else{
	                $new_array[$key] = array_join_merge($arr1[$key], $arr2[$key]);
                }
            }
        }
        else
        {
            // two real arrays -> merge
            $new_array = array_reverse(array_unique(array_reverse(array_merge($arr1,$arr2))));
        }
        return $new_array;
    }
    else
    {
        // not the same ... take new one if defined, else the old one stays
        return ($arr2 !== null) ? $arr2 : $arr1;
    }
}

function array_join_merge_recursive($arr1, $arr2, $unset_if_null = false, $overwrite_if_scalar_w_array = false)
{
	$trick = false;
    if(is_array($arr1) and is_array($arr2))
    {
        foreach ($arr1 as $key => $value)
        {
            if (array_key_exists($key, $arr2))
                if (is_array($value))
                {
                    if (is_array($arr2[$key]))
                        $arr1[$key] = array_join_merge_recursive($arr1[$key], $arr2[$key], $unset_if_null, $overwrite_if_scalar_w_array);
                    elseif ($unset_if_null && ($arr2[$key] === null))
						unset($arr1[$key]);
                	elseif ($overwrite_if_scalar_w_array)
	                    $arr1[$key] = $arr2[$key];
                    else
                        $arr1[$key][] = $arr2[$key];
                }
                else
                    $arr1[$key] = $arr2[$key];
        }
        foreach ($arr2 as $key => $value)
            if (!array_key_exists($key, $arr1) && ($arr2[$key] !== null))
                $arr1[$key] = $value;
    }
    return $arr1;
}

function array_compare($arr1, $arr2, $arg_keys)
{
    foreach ($arg_keys as $key)
    {
        if ($arr1[$key] != $arr2[$key])
            return false;
    }
    return true;
}

function array_kmerge($arr1, &$arr2)
{
	foreach ($arr2 as $key => $val)
		$arr1[$key] = $val;
	return $arr1;
}

function mergeListSelected(&$rows, $selected, $key_name)       // selected can be scalar or array
{
    $result = false;                // return true if there is some item selected
    if (is_array($selected))        // separate for speed optimization
    {
        foreach ($rows as $ind => $row)
        {
            $val = $row[$key_name];
            if (in_array($val, $selected))
            {
                $rows[$ind][WI_SELECTED] = WI_SELECTED;
                $rows[$ind][WI_CHECKED] = WI_CHECKED;
                $result = true;
            }
            else
            {
                $rows[$ind][WI_SELECTED] = '';
                $rows[$ind][WI_CHECKED] = '';
            }
        }
    }
    else
    {
        foreach ($rows as $ind => $row)
        {
            $val = $row[$key_name];
            if ($val == $selected)
            {
                $rows[$ind][WI_SELECTED] = WI_SELECTED;
                $rows[$ind][WI_CHECKED] = WI_CHECKED;
                $result = true;
            }
            else
            {
                $rows[$ind][WI_SELECTED] = '';
                $rows[$ind][WI_CHECKED] = '';
            }
        }
    }
    return $result;
}

// if there are many default such as for multi-select drop-down or checkbox, use array for $arg_default
// to select all items, use '*' as $arg_default
function getList($arg_rows, $arg_valkeys, $arg_itemkeys, $arg_default = '', $enable_blank = false, $blank_text = '')
{
    $result = array();
    if (is_array($arg_valkeys))
        $rows = makeCompoundAssocArray($arg_rows, $arg_valkeys);
    elseif (is_scalar($arg_valkeys))
        $rows = makeAssocArray($arg_rows, $arg_valkeys);
    foreach ($rows as $key => $row)
    {
        $row[WI_LIST_VALUE] = $key;
        $row[WI_LIST_TEXT] = @$row[$arg_itemkeys];
        if ($key == $arg_default || $arg_default == '*' || (is_array($arg_default) && in_array($key, $arg_default)))
        {
            $row[WI_SELECTED] = WI_SELECTED;
            $row[WI_CHECKED] = WI_CHECKED;
        }
        else
        {
            $row[WI_SELECTED] = '';
            $row[WI_CHECKED] = '';
        }
        $result[] = $row;
    }
    if ($enable_blank)
        array_unshift($result, array(WI_LIST_VALUE => '', WI_LIST_TEXT => $blank_text, WI_SELECTED => $blank_selected, WI_SELECTED => (($arg_default == '') ? WI_SELECTED : ''), WI_CHECKED => (($arg_default == '') ? WI_CHECKED : '')));
    return $result;
}

// there should be a unified function to find and select item in list

function selectList(&$list, $arg_default = '')
{
    $is_checked = false;
    foreach ($list as $key => $row)
    {
        $val = (array_key_exists(WI_LIST_VALUE, $row) ? $row[WI_LIST_VALUE] : '');
        if ($val == $arg_default || (is_array($arg_default) && in_array($val, $arg_default)))
        {
            $row[WI_SELECTED] = WI_SELECTED;
            $row[WI_CHECKED] = WI_CHECKED;
            $is_checked = true;
        }
        else
        {
            $row[WI_SELECTED] = '';
            $row[WI_CHECKED] = '';
        }
        $list[$key] = $row;
    }
    return $is_checked;
}

function selectItem(&$row)
{
    $row[WI_SELECTED] = WI_SELECTED;
    $row[WI_CHECKED] = WI_CHECKED;
}

function isSelected(&$row)
{
	return ($row[WI_SELECTED] == WI_SELECTED);
}

function mergeListBlankItem(&$list, $arg_default = '', $blank_text = '')
{
    array_unshift($list, array(WI_LIST_VALUE => '', WI_LIST_TEXT => $blank_text, WI_SELECTED => (($arg_default == '') ? WI_SELECTED : ''), WI_CHECKED => (($arg_default == '') ? WI_CHECKED : '')));
}

function mergeList($list1, $list2, $arg_default = '')
{
    $result = array_merge($list1, $list2);
    selectList($result, $arg_default);
    return $result;
}

function mergeOtherList(&$other, $list, $other_list, $arg_default = '', $lang_id = '')
{
    $list = array_merge($list, $other_list);
    $other_index = count($list) - 1;
    $is_checked = selectList($list, $arg_default);

/*
    $is_checked = false;
    foreach ($list as $key => $row)
    {
        $val = $row[WI_LIST_VALUE];
        if ($val == $arg_default || (is_array($arg_default) && in_array($key, $arg_default)))
        {
            $row[$key][WI_SELECTED] = WI_SELECTED;
            $row[$key][WI_CHECKED] = WI_CHECKED;
            $is_checked = true;
            $other = '';
        }
        else
        {
            $row[$key][WI_SELECTED] = '';
            $row[$key][WI_CHECKED] = '';
        }
    }
*/
    if ($is_checked)
        $other = '';
    if ((!is_array($arg_default)) && (!$is_checked))
    {
        $list[$other_index][WI_SELECTED] = WI_SELECTED;
        $list[$other_index][WI_CHECKED] = WI_CHECKED;
        $other = $arg_default;
    }
    return $list;
}

function getNumberList($arg_end_number, $arg_default = 1, $arg_start_number = 1, $offset_text = 0, $arg_step = 1)
{
    $list = array();
    while ($arg_start_number <= $arg_end_number)
    {
        $selected = ($arg_start_number == $arg_default);
        $list[] = array(WI_LIST_VALUE => $arg_start_number, WI_LIST_TEXT => $arg_start_number + $offset_text,
                        WI_SELECTED => ($selected ? WI_SELECTED : ''), WI_CHECKED => ($selected ? WI_CHECKED : ''));
        $arg_start_number += $arg_step;
    }
    return $list;
}

function getDayList($arg_default = '01')
{
    $list = getNumberList(31);
    foreach ($list as $k => $item)
    {
        $list[$k][WI_LIST_VALUE] = str_pad($item[WI_LIST_VALUE], 2, '0', STR_PAD_LEFT);
        $list[$k][WI_LIST_TEXT] = str_pad($item[WI_LIST_TEXT], 2, '0', STR_PAD_LEFT);
    }
    selectList($list, $arg_default);
    return $list;
}

function getYearList($arg_start_year, $arg_end_year, $arg_default = '2003', $offset_text = 0)
{
    return getNumberList($arg_end_year, $arg_default, $arg_start_year, $offset_text);
}

function getMonthList($monthname_list, $arg_default = '01')
{
    array_unshift($monthname_list, '');
    for ($i = 1; $i <= 12; $i++)
        $list_rows[] = array(WI_LIST_VALUE => (str_pad($i, 2, '0', STR_PAD_LEFT)), WI_LIST_TEXT => $monthname_list[$i]);
    selectList($list_rows, $arg_default);
    return $list_rows;
}

function getMonthYearList($arg_start_month, $arg_start_year, $monthname_list, $arg_default = '2004-01', $num_month = 12)
{
    $y = $arg_start_year;
    array_unshift($monthname_list, $monthname_list[11]);        // compensate the first slot of array to 'December'
    for ($i = 0; $i < $num_month; $i++)
    {
        $m = intval(($arg_start_month + $i) % 12);
        if ($m == 0) $m = 12;
        $list_rows[] = array(WI_LIST_VALUE => ($y . '-' . str_pad($m, 2, '0', STR_PAD_LEFT)),
                             WI_LIST_TEXT => $monthname_list[$m] . ' ' . $y);
        if ($m == 12)
            $y++;
    }
    selectList($list_rows, $arg_default);
    return $list_rows;
}

function selectWholeList(&$rows)
{
    foreach ($rows as $ind => $row)
    {
        $rows[$ind][WI_SELECTED] = WI_SELECTED;
        $rows[$ind][WI_CHECKED] = WI_CHECKED;
    }
}


function swap(&$val1, &$val2)
{
    $dummy = $val1;
    $val1 = $val2;
    $val2 = $dummy;
}

function convertBlankToValue(&$arg_input, $arg_keys, $val = null)
{
    foreach ($arg_keys as $key)
        if ($arg_input[$key] == '')
            $arg_input[$key] = $val;
}

function mergeRowsOrderNumber(&$rows, $col = 'OrderNumber', $i = 1)
{
    // $i is starting order number
    if (is_array($rows))
        foreach ($rows as $key => $row)
            $rows[$key][$col] = $i++;
    return $rows;     // should not return result array here
}

function prefixRowsKey(&$rows, $prefix)
{
    foreach ($rows as $key => $row)
        $result[$prefix . $key] = $row;
    return $result;
}

function extractArrayValue(&$arg_input, $arg_key)
{
    $arg_output = array();
    if (is_array($arg_input))
        foreach ($arg_input as $row)
            $arg_output[] = $row[$arg_key];
    return $arg_output;
}

function extractArrayValues(&$arg_input, $arg_keys)
{
    $arg_output = array();
    if (is_array($arg_input))
        foreach ($arg_input as $row)
            $arg_output[] = copyArrayValue($row, $arg_keys);
    return $arg_output;
}

function copyArrayValue(&$arg_input, $arg_keys, $arg_merge_array = array())
{
    if (is_array($arg_merge_array))
        $arg_output = &$arg_merge_array;
    foreach ($arg_keys as $key)
        if (array_key_exists($key, $arg_input))
            $arg_output[$key] = $arg_input[$key];
    return $arg_output;
}

function copyArrayValueNotBlank(&$arg_input, $arg_keys, $arg_merge_array = array())
{
    if (is_array($arg_merge_array))
        $arg_output = &$arg_merge_array;
    foreach ($arg_keys as $key)
        if (array_key_exists($key, $arg_input) && ($arg_input[$key]))
            $arg_output[$key] = $arg_input[$key];
    return $arg_output;
}

function copyRowsValue(&$arg_rows, $arg_keys, $arg_merge_array = array())
{
    $arg_output = array();
    if (is_array($arg_rows))
	foreach ($arg_rows as $key => $row)
		$arg_output[$key] = copyArrayValue($row, $arg_keys, $arg_merge_array);
    return $arg_output;
}

function mapRowsValue(&$arg_input, $arg_keys, $arg_merge_array = array())
{
    foreach ($arg_input as $key => $row)
    {
        if (count($arg_merge_array))
            $arg_input[$key] = array_merge($row, $arg_merge_array);
        foreach ($arg_keys as $k => $v)
            $arg_input[$key][$k] = $row[$v];
    }
}

function transposeArray($arg_columns)
{
    foreach ($arg_columns as $key => $arr_value)
        foreach ($arr_value as $ind => $value)
            $result[$ind][$key] = $value;
    return $result;
}

// function makeAssocArray()
//          make an key-indexed (associative) array from a number-indexed array by using a value field in array
// $input   array of data to be used
// $arg_key  key of input name which its data will be used as key
// return   result array
function makeAssocArray($arg_input, $arg_key, $with_blank_key = false)
{
    $result = array();
    if (is_array($arg_input))
        foreach ($arg_input as $val)
        	if (is_array($val))
        	{
	        	if (array_key_exists($arg_key, $val))
		            $result[$val[$arg_key]] = $val;
	            elseif ($with_blank_key)
	            	$result[''] = $val;
        	}
    return $result;
}

// function makeCompoundAssocArray()
//          just a variant of makeAssocArray() for used with a compound-key array
function makeCompoundAssocArray($arg_input, $arg_keys)
{
    $result = array();
    if (is_array($arg_input))
    {
        foreach ($arg_input as $val)
        {
            $key = getCompoundKey($val, $arg_keys);
            $result[$key] = $val;
        }
    }
    return $result;
}

// mostly like makeAssocArray() but this is for an array which $arg_key is not primary key
// this function will return tree-like array grouped by value of field $arg_key
function makeGroupedArray($arg_input, $arg_key, $with_blank_key = false)
{
    $result = array();
    if (is_array($arg_input))
        foreach ($arg_input as $val)
        	if (array_key_exists($arg_key, $val))
	            $result[$val[$arg_key]][] = $val;
            elseif ($with_blank_key)
            	$result[''][] = $val;
    return $result;
}

// function makeCompoundGroupedArray()
// input    array(0 => array(), ...);
// return   array($keys => array(0 => array(), ...));
function makeCompoundGroupedArray($arg_input, $arg_keys)
{
    if (count($arg_keys) == 1)
    {
        if (is_array($arg_keys))
            $arg_keys = array_shift($arg_keys);
        return makeGroupedArray($arg_input, $arg_keys);
    }
    $result = array();
    if (is_array($arg_input))
    {
        foreach ($arg_input as $val)
        {
            $key = getCompoundKey($val, $arg_keys);
            $result[$key][] = $val;
        }
    }
    return $result;
}

function getCompoundKey($arg_row, $arg_keys)
{
    foreach ($arg_keys as $akey)
        $key[] = $arg_row[$akey];
    return implode('_', $key);
}

function compareRowsValue(&$arg_rows, $key1, $key2, $key_result = 'Compare')
{
    foreach ($arg_rows as $key => $row)
    {
        if ($row[$key1] > $row[$key2])
            $arg_rows[$key][$key_result] = 'g';
        elseif ($row[$key1] < $row[$key2])
            $arg_rows[$key][$key_result] = 'l';
        else
            $arg_rows[$key][$key_result] = 'e';
    }
}

define('MERGEDESC_TYPE_SIMPLE', 'S');
define('MERGEDESC_TYPE_PREFIX', 'P');
define('MERGEDESC_TYPE_NODE', 'N');
function mergeDescription(&$arg_data_rows, $arg_desc_rows, $arg_keys, $arg_fields = '*', $merge_type = MERGEDESC_TYPE_SIMPLE, $merge_attr = 'Merged')
{
    if (count($arg_keys) < 1)
        return array();

    if (is_array($arg_keys))
    {
        foreach ($arg_keys as $data_key => $desc_key)
        {
            if (is_integer($data_key))
                $data_keys[] = $desc_key;
            else
                $data_keys[] = $data_key;
            $desc_keys[] = $desc_key;
        }
    }
    else
    {
        $data_keys[] = $desc_keys[] = $arg_keys;
    }

    if (count($desc_keys) == 1)
        $desc_rows = makeAssocArray($arg_desc_rows, $desc_keys[0]);
    else
        $desc_rows = makeCompoundAssocArray($arg_desc_rows, $desc_keys);

    if ($arg_fields == '*')
    {
        foreach ($arg_data_rows as $ind => $row)
        {
            $desc = $desc_rows[getCompoundKey($row, $data_keys)];
            switch ($merge_type)
            {
                case MERGEDESC_TYPE_PREFIX:
                    foreach ($desc as $name => $value)
                        $row[$merge_attr . $name] = $value;
                    break;
                case MERGEDESC_TYPE_SIMPLE:
                    $row = array_merge($row, $desc);
                    break;
                case MERGEDESC_TYPE_NODE:
                    $row[$merge_attr] = $desc;
                    break;
            }
            $arg_data_rows[$ind] = $row;
        }
    }
    else
    {
        if (is_array($arg_fields))
        {
            foreach ($arg_fields as $key => $value)
            {
                if (is_integer($key))
                {
                    $arg_fields[$value] = $value;
                    unset($arg_fields[$key]);
                }
            }
        }
        else
        {
            $key = $arg_fields;
            $arg_fields = array();
            $arg_fields[$key] = $key;
        }
        foreach ($arg_data_rows as $ind => $row)
        {
            $desc = $desc_rows[getCompoundKey($row, $data_keys)];
            switch ($merge_type)
            {
                case MERGEDESC_TYPE_PREFIX:
                    foreach ($arg_fields as $name => $alias)
                        $arg_fields[$name] = $merge_attr . $alias;
                    // no break;
                case MERGEDESC_TYPE_SIMPLE:
                    foreach ($arg_fields as $name => $alias)
                        $row[$alias] = $desc[$name];
                    break;
                case MERGEDESC_TYPE_NODE:
                    foreach ($arg_fields as $name => $alias)
                        $dummy[$alias] = $desc[$name];
                    $row[$merge_attr] = $dummy;
                    break;
            }
            $arg_data_rows[$ind] = $row;
        }
    }

    return $arg_data_rows;
}

function toDescription(&$arg_data_rows, $arg_desc_rows, $arg_keys, $arg_fields)
{
    if (count($arg_keys) < 1)
        return array();

    if (is_array($arg_keys))
    {
        foreach ($arg_keys as $data_key => $desc_key)
        {
            if (is_integer($data_key))
                $data_keys[] = $desc_key;
            else
                $data_keys[] = $data_key;
            $desc_keys[] = $desc_key;
        }
    }
    else
    {
        $data_keys[] = $desc_keys[] = $arg_keys;
    }

    if (count($desc_keys) == 1)
        $desc_rows = makeAssocArray($arg_desc_rows, $desc_keys[0]);
    else
        $desc_rows = makeCompoundAssocArray($arg_desc_rows, $desc_keys);
    foreach ($arg_fields as $key => $value)
    {
        if (is_integer($key))
        {
            $arg_fields[$value] = $value;
            unset($arg_fields[$key]);
        }
    }
    foreach ($arg_data_rows as $ind => $row)
    {
        if (! ($ckey = getCompoundKey($row, $data_keys)))
            continue;
        $desc = $desc_rows[$ckey];
        foreach ($arg_fields as $name => $alias)
            $row[$alias] = $desc[$name];
        $arg_data_rows[$ind] = $row;
    }
    return $arg_data_rows;
}

/*
function mergeDescription(&$arg_data_rows, $arg_desc_rows, $arg_desc_key, $arg_desc_column, $arg_data_key = '', $arg_data_alias = '')
{
    if ($arg_data_key == '') $arg_data_key = $arg_desc_key;
    if ($arg_data_alias == '') $arg_data_key = $arg_desc_column;

    $desc_rows = makeAssocArray($arg_desc_rows, $arg_desc_key);
    foreach ($arg_data_rows as $ind => $row)
        $arg_data_rows[$ind][$arg_data_alias] = $desc_rows[$row[$arg_data_key]][$arg_desc_column];
}
*/

// $card_number must be without last digit which is intended to be calculated here


function cleanSlashes($arg_values)
{
    if (is_scalar($arg_values))
        return stripslashes($arg_values);
    elseif (is_array($arg_values))
    {
        foreach ($arg_values as $key => $value)
            $arg_values[$key] = cleanSlashes($value);
        return $arg_values;
    }
    else
        return $arg_values;
}

function splitLinesToRows($text)
{
    return preg_split('/\r\n|\n\r|\n/', $text);
}

function mergeRowNumber(&$rows, $name = 'OrderNumber')
{
    $i = 1;
    foreach ($rows as $k => $row_value)
    {
        $rows[$k][$name] = $i;
        $i++;
    }
}

function fillDefaultRows(&$rows, $max, $default_row = array())
{
    for ($i = count($rows); $i < $max; $i++)
        $rows[] = $default_row;
}

function calculateChecksumMod10($card_number)
{
    $card_number = strrev($card_number . '0');
    $checksum = 0;
    for ($i = strlen($card_number) - 1; $i >= 0; $i--)
    {
        if (($i % 2) == 1)
        {
            $asum = intval($card_number[$i]) * 2;
            if ($asum > 9)
                $asum -= 9;      // split digits and add together = mod 10 and plus 1 = subtract 9
            $checksum += $asum;
        }
        else
            $checksum += intval($card_number[$i]);
    }
    return ((10 - ($checksum % 10)) % 10);
}

// Example: $CSorter->sortby = array ( array("FieldName1", SORT_STRING, SORT_ASC),
//                                     array("FieldName1", SORT_NUMERIC, SORT_DESC) );
//          $CSorter->sortRows($rows_data, $sortby);
class CSorter
{
    var $sort_by;
    var $merge_row_number = false;

    function CSorter($sortby = '')
    {
        $this->setSortBy($sortby);
    }

    function setSortBy($sortby)
    {
        if (is_array($sortby) && (count($sortby) > 0))
            $this->sort_by = $sortby;
    }

    function sortRows($ar_data, $sortby = '')
    {
        if (is_array($ar_data) && is_array($this->sort_by) && (count($this->sort_by) > 0))
            usort($ar_data, array(&$this, 'callbackSortRows'));
        if ($this->merge_row_number)
            $ar_data = mergeRowNumber($ar_data);
        return $ar_data;
    }

    function callbackSortRows($a, $b)
    {
        if (is_array($this->sort_by) && (count($this->sort_by) > 0))
        {
            foreach ($this->sort_by as $option)
            {
                $val_a = @$a[$option[0]];
                $val_b = @$b[$option[0]];

                // in all type of sorting, null will be considered less than '' or 0
                if ($val_a === null)
                {
                	if ($val_b === null)
                		$result = 0;
            		else
            			$result = -1;			// a == null;
    			}
    			else
    				$result = 1;			// b == null;

                switch ($option[1])
                {
                    case SORT_REGULAR:
                        $result = (($val_a > $val_b) ? 1 : (($val_a == $val_b) ? 0 : -1));
                        break;
                    case SORT_STRING:
                        $result = strcasecmp($val_a, $val_b);
                        break;
                    case SORT_NUMERIC:
                        $val_a = (float)($val_a);
                        $val_b = (float)($val_b);
                        $result = (($val_a > $val_b) ? 1 : (($val_a == $val_b) ? 0 : -1));
                        break;
                }
                if ($result != 0)
                {
                    if ($option[2] != SORT_DESC)
                        return $result;
                    else
                        return (-$result);
                }
            }
        }
        return 0;
    }

}

// if $keys is array, result will be array of the same keys, otherwise result is the summation
function sumArrayValue(&$acct_rows, $keys)
{
    if (!is_array($keys))
    {
        $keys = array($keys);
        $is_karray = false;
    }
    else
        $is_karray = true;
    $sum = array();
    foreach ($keys as $key)
        $sum[$key] = 0;
    foreach ($acct_rows as $row)
        foreach ($keys as $key)
            $sum[$key] += $row[$key];
    if ($is_karray)
        return $sum;
    else
        return $sum[array_shift($keys)];
}

function sumArrayValueDistinct(&$acct_rows, $keys, $distinct_key)
{
    $sum = array();
    foreach ($acct_rows as $row)
        foreach ($keys as $key)
            $sum[$row[$distinct_key]][$key] += $row[$key];
    foreach ($sum as $distinct_val => $row)
    {
	    $sum[$distinct_val][$distinct_key] = $distinct_val;
        foreach ($keys as $key)
        	if (! array_key_exists($key, $row))
			    $sum[$distinct_val][$key] = 0;
    }
    return $sum;
}
// $sums has the same structure as that returned from sumArrayValue()
function calculatePercentageArrayValue(&$acct_rows, $keys, $row_id = 'Id', $sums = array())
{
    if (!is_array($keys))
    {
        $keys = array($keys);
        $is_karray = false;
    }
    else
        $is_karray = true;

    if ((! is_array($sums)) && ($sums == 0))		// divide by zero
    	return false;
    if (is_array($sums) && (count($sums) < 1))
    	$sums = sumArrayValue($acct_rows, $keys);
	if (! $is_karray)
		$sums = array($keys[0] => $sums);

    $percentages = array();
    foreach ($acct_rows as $row)
        foreach ($keys as $key)
        {
	        assert($sums[$key] != 0);
            $percentages[$row[$row_id]][$key] = (@$row[$key] * 100 / $sums[$key]);
        }

    return $percentages;
}

function unsetArrayValue(&$arg_input, $arg_keys)
{
    foreach ($arg_keys as $key)
        unset($arg_input[$key]);
}

function unsetRowsValue(&$arg_input, $arg_keys)
{
    foreach ($arg_input as $ind => $row)
    {
        foreach ($arg_keys as $key)
            unset($arg_input[$ind][$key]);
    }
}

	function getIpAddresses()
	{
		if (isset($_SERVER))
		{
			if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && ($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ips = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
			elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && ($_SERVER['HTTP_CLIENT_IP']))
				$ips = array($_SERVER['HTTP_CLIENT_IP']);
			else
				$ips = array($_SERVER['REMOTE_ADDR']);
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
				$ips = split('[, ]', getenv('HTTP_X_FORWARDED_FOR'));
			elseif (getenv('HTTP_CLIENT_IP'))
				$ips = array(getenv('HTTP_CLIENT_IP'));
			else
				$ips = array(getenv('REMOTE_ADDR'));
		}

		return $ips;
	}


//*** Have not check or test, just copy from someone
function groupBy($arg_rows, $arg_group_by, $arg_group_detail = array(), $arg_sum = array(),
    $arg_distinct = array(), $arg_concat = array())
{
    $result = array();
    $rows = makeCompoundGroupedArray($arg_rows, $arg_group_by);

    foreach ($rows as $key => $row)
    {
        foreach ($row as $value)
        {
            foreach ($arg_group_by as $group_by)
                $result[$key][$group_by] = $value[$group_by];
            foreach ($arg_sum as $column)
            {
                $result[$key]['Sum_' . $column] += $value[$column];
                $result[$key]['GroupCount'] += 1;
            }
            foreach ($arg_distinct as $distinct)
            {
                $result[$key]['Distinct_' . $distinct][$value[$distinct]] =
                    array($distinct => $value[$distinct]);
            }
            foreach ($arg_concat as $concat)
            {
                $concat_key = copyArrayValue($value, $arg_distinct);
                $result[$key]['Concat_' . $concat][implode('_', $concat_key)] = array($concat => $value[$concat]);
            }

            $result[$key] = copyArrayValue($value, $arg_group_detail, $result[$key]);
        }
    }

    return $result;
}

?>
