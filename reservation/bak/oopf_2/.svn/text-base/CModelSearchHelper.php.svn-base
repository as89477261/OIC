<?php 

require_once('CModel.php');

class CModelSearchHelper
{

/*
    var $fields_map = array('field1' => array('db_field1', 'db_field2', 'db_field3', '...'),
                            'field2' => 'calculating_method_name');
    var $mandatory_fields = array('field4', 'field5', 'field6', '...');
    var $optional_fields = array('field7', 'field8', 'field9', '...');
    var $default_fields = array('field10', 'field11', 'field12', '...');
    var $additional_fields = array('field10', 'field11', 'field12', '...');        // not in db, added by model or preprocessor
    var $fields_numeric = array('field8', 'field11');
    var $fields_sorting = array('field1' => SORT_ASC, 'field5' => SORT_DESC);   // order is significant
    var $fields_description = array(
            'field1' => 'field1_desc',
            'field2' => 'field2_desc',
            'field3' => 'field3_desc'
        );
*/
    var $result_name = 'result_rows';
    var $rpp = 20;
    var $sort_result = false;
    var $sort_prefix = 'Sort';

    function CModelSearchHelper($model)
    {
        $this->model = $model;
        $this->app = $this->model->parent;
        $this->result_name = $model->model_name . SUFFIX_ROWS;
    }

//     function getFieldList($arg_input = array())
//     {
//         if (array_key_exists('Fields', $arg_input))
//             $default_fields = $arg_input['Fields'];
//         else
//             $default_fields = $this->default_fields;

//         $field_list = copyArrayValue($this->fields_description, $this->optional_fields);
//         foreach ($field_list as $key => $desc)
//             $fields[] = array('FieldName' => $key, 'FieldDesc' => $desc);
//         $fields = getList($fields, 'FieldName', 'FieldDesc', $default_fields);
//         return $fields;
//     }

    // prepare data for search criteria
    // return array for arg_output
    function getForm($arg_input = array())
    {
    }

    // convert criteria to where-array for db-datastore
    // return array where-array
    function generateWhere($criteria)
    {
    }

    // map non-db-column to db-column, if possible
    // return new column name
    function getSortingColumn($additional_column)
    {
        return $additional_column;
    }

    function search($criteria, $page = 0, $fields = array())
    {
//         if (!is_array($fields) || (count($fields) < 1))
// //            $fields = $this->default_fields;
//             $fields = array();
//         $fields = array_merge($this->mandatory_fields, $fields);

//         // convert fields to db's fields
//         $map_list = array_keys($this->fields_map);
//         foreach ($fields as $name)
//         {
//             if (in_array($name, $map_list))
//             {
//                 $map = $this->fields_map[$name];
//                 if (!is_array($map))
//                     $map = array($map);
//                 foreach($map as $value)
//                 {
//                     $map_fields[$name][] = $value;
//                     if (! method_exists($this, $value))
//                         $db_fields[] = $value;
//                 }
//             }
//             else
//                 $db_fields[] = $name;
//         }

        // get data from db
        $ds =& $this->model->getDataStore();
        $where = $this->generateWhere($criteria);

        $sort_later = array();
        if ($this->sort_result)
        {
            if (! is_array($this->fields_numeric))
                $this->fields_numeric = array();
            if (! is_array($this->additional_fields))
                $this->additional_fields = array();

            foreach ($this->fields_sorting as $name => $flag)
            {
                if ($flag != CONST_DESC)
                    $sort_flag = CONST_ASC;                      // to protect unknown flag
                else
                    $sort_flag = CONST_DESC;
                $name = $this->getSortingColumn($name);        // mapping to db column, if possible
                if (in_array($name, $this->additional_fields))
                {
                    if (in_array($name, $this->fields_numeric))
                        $type = SORT_NUMERIC;
                    else
                        $type = SORT_STRING;
                    $sort_later[] = array($name, $type, $sort_flag);
                }
                else
                    $this->model->order_by[$name] = $sort_flag;
                $sort_info[$this->sort_prefix . $name] = $sort_flag;         // to merge with output
            }
        }

//         if (!$this->sort_result && $page > 0)
        if ((count($sort_later) == 0) && ($page > 0))
        {
            $result_count = $ds->getCount($where);
            $ds->limit_offset = (($page - 1) * $this->rpp);
            $ds->limit_rows = $this->rpp;
        }
        $data = $this->model->find($where);
        if ((count($sort_later) != 0) || ($page <= 0))
            $result_count = count($data);

         if (is_array($data))
             $this->preprocessData($data, $fields);
//         if (is_array($map_fields))
//         {
//             foreach ($data as $key => $row)
//             {
//                 foreach ($map_fields as $name => $map)
//                 {
//                     // $map will always be array (from the process above)
//                     $values = array();
//                     foreach($map as $fn)
//                     {
//                         if (method_exists($this, $fn))
//                             $values[$fn] = $this->$fn($row);
//                         else
//                         {
//                             $values[$fn] = $row[$fn];
//                             unset($data[$key][$fn]);
//                         }
//                     }
//                     $data[$key][$name] = $this->mergeFieldMap($values, $name);
//                 }
//             }
//         }

//         if ($this->sort_result)
//         {
//             $result_count = count($data);
//             // prepare $sort_by;
//             if (! is_array($this->fields_numeric))
//                 $this->fields_numeric = array();
//             foreach ($this->fields_sorting as $name => $flag)
//             {
//                 if (in_array($name, $this->fields_numeric))
//                     $type = SORT_NUMERIC;
//                 else
//                     $type = SORT_STRING;
//                 if ($flag != CONST_DESC)
//                     $sort_flag = SORT_ASC;                      // to protect unknown flag
//                 else
//                     $sort_flag = SORT_DESC;
//                 $sort_by[] = array($name, $type, $sort_flag);
//                 $sort_info[$this->sort_prefix . $name] = $sort_flag;         // to merge with output
//             }

            // sort
            if (count($sort_later) > 0)
            {
                $sorter = new CSorter($sort_later);
                $data = $sorter->sortRows($data);
                if ($page > 0)
                    $data = array_slice($data, (($page - 1) * $this->rpp), $this->rpp);
            }

//         }
//         elseif ($page == 0)
//             $result_count = count($data);
//        print_r($data);
//print_r($sort_info);

        // prepare output
        $output = array($this->result_name => $data);
        if (isset($sort_info) && is_array($sort_info))
            $output = array_merge($output, $sort_info);
        $output['result_count'] = $result_count;
        if ($page > 0)
            $output = array_merge($output, $this->getPageNavigationValue($result_count, $page));
        return $output;
    }

    // preprocess data returned from db-datastore
    function preprocessData(&$data, $fields)
    {
    }

    // override this function to define how to map field
    function mergeFieldMap($data, $map_name = '')
    {
        return implode(' ', $data);
    }

    function getPageNavigationValue($rows_count, $current_page)
    {
        $rpp = $this->rpp;
        if ($rpp == 0)
            $rpp = 10;

        if ($rows_count <= 1)
            $page_all = 1;
        else
            $page_all = ceil($rows_count / $rpp);

        if ($current_page <= 1)
            $page_at = 1;
        elseif ($current_page >= $page_all)
            $page_at = $page_all;
        else
            $page_at = $current_page;

        if ($page_at > 1)
        {
            $result['PreviousPageAt'] = $page_at - 1;
            $result['EnablePrevious'] = CONST_YES;
        }
        else
        {
            $result['PreviousPageAt'] = 1;
            $result['EnablePrevious'] = CONST_NO;
        }

        if ($page_at < $page_all)
        {
            $result['NextPageAt'] = $page_at + 1;
            $result['EnableNext'] = CONST_YES;
        }
        else
        {
            $result['NextPageAt'] = $page_all;
            $result['EnableNext'] = CONST_NO;
        }

        $result['PageAll'] = $page_all;
        $result['PageAt'] = $page_at;

        return $result;
    }

    function adjustCriteriaOutput($criteria)
    {
        foreach ($criteria as $key => $val)
        {
            if (is_array($val))
                $criteria[$key] = array('IsMultiple' => CONST_YES, 'Key' => $key, 'Values' => $val);
            else
                $criteria[$key] = array('Key' => $key, 'Value' => $val);
        }
        return $criteria;
    }

}

?>
