<?php

if (!defined('SUFFIX_ROWS'))
    define('SUFFIX_ROWS', 'Rows');
if (!defined('SUFFIX_ID'))
    define('SUFFIX_ID', 'Id');

class CModel
{

    var $parent;
    var $model_name;
    var $table_name;
    var $primary_key;
    var $name_columns = 'name';
    var $enable_getlist = false;
    var $models = array();
    var $has_many, $has_one;        // composition
    var $belongs_to, $belongs_to_many;                // container
    var $join_models;               // merge all columns of tables by join statement
    var $described_by;              // merge a column of tables by php (result like left join)
    var $join_constants;            // merge constants
    var $order_by;                  // default sorting
    var $convert_composite_name = true;

    // currently, support 2 types of table joining
    // 1. use intermediate (medium) table, and exactly the same key from each table is used together in the medium table
    // 2. put multiple key in the same column and concat with comma. no use of intermediate table

    function CModel($parent)
    {
        $this->parent = $parent;
		$this->setDefaultMembers();
        $this->init();
    }

	private function setDefaultMembers()
	{
		if (! $this->table_name)
			$this->table_name = $this->model_name;
		if (! $this->primary_key)
			$this->primary_key = $this->table_name . SUFFIX_ID;
	}

    function getModel($class)
    {
        if (!array_key_exists($class, $this->models))
        {
            if (class_exists($class))
                $this->models[$class] = new $class($this->parent);
            else
                return false;
        }
        return $this->models[$class];
    }

    function getById($id)
    {
        $result = $this->findById($id);
        if (count($result) > 0)
        {
            $this->mergeComposition($result);
            $this->mergeBelonger($result);
        }
        return $result;
    }

    function getNext($id, $order_by = array(), $arg_where = array(), $lang_id = '')
    {
        $next = $this->findNext($id, $order_by, $arg_where, $lang_id);
        return $this->getById($next[$this->primary_key]);
    }

    function getPrevious($id, $order_by = array(), $arg_where = array(), $lang_id = '')
    {
        $previous = $this->findPrevious($id, $order_by, $arg_where, $lang_id);
        return $this->getById($previous[$this->primary_key]);
    }

    function getAll($ids = '')
    {
        if ($ids == '')
            $ids = extractArrayValue($this->selectAll(), $this->primary_key);
        elseif (!is_array($ids))
            return array();

        $results = $this->findAll(array($this->primary_key => $ids));
        $this->mergeOne($results);
        $this->mergeMany($results);
        $this->mergeBelongerRows($result);
        // must be sorted later
        return $results;
    }

    function generateWhereId($id)
    {
        if (is_array($id))
            return $id;
        else
        {
            if (is_array($this->primary_key))
                return false;
            else
                return array($this->primary_key => $id);
        }
    }

    function findById($id, $lang_id = '')
    {
        if (!$id)
            return false;
        $ds = $this->getDataStore($lang_id);
        $where = $this->generateWhereId($id);
        if ($where === false)
            return false;
        if (is_array($this->join_models))
        {
            $join = $this->getJoin($this->join_models);
            $ds->setJoinTable($join);
        }
        @list($result) = $ds->getData($where);
        if ($result)
            $this->mergeDescription($result);
        return $result;
    }

    function findNext($id, $order_by = array(), $arg_where = array(), $lang_id = '')
    {
        if (!$id)
            return false;
        $all = $this->findOrdered($order_by, $arg_where, $lang_id);
        $found = false;
        foreach ($all as $row)
        {
            if ($found)
                return $row;
            else
            if ($row[$this->primary_key] == $id)
                $found = true;   // next round

        }
        return null;
    }

    function findPrevious($id, $order_by = array(), $arg_where = array(), $lang_id = '')
    {
        if (!$id)
            return false;
        $all = $this->findOrdered($order_by, $arg_where, $lang_id);
        $previous = null;
        foreach ($all as $row)
        {
            if ($row[$this->primary_key] == $id)
                return $previous;
            $previous = $row;   // for next round
        }
        return null;
    }

    function findOrdered($order_by = array(), $arg_where = array(), $lang_id = '')
    {
        if (count($order_by) > 0)
        {
            $dummy_order = $this->order_by;
            $this->order_by = $order_by;
        }
        $all = $this->selectAll($arg_where, $lang_id);
        if (isset($dummy_order))
            $this->order_by = $dummy_order;
        return $all;
    }

    // need optimization for merging description
    function findByNameLike($name, $lang_id = '')
    {
        $ds = $this->getDataStore($lang_id);
        $name = '%' . $name . '%';
        if (is_array($this->name_columns))
        {
            foreach ($this->name_columns as $column)
                $where[$column . ' LIKE'] = $name;
        }
        else
            $where[$this->name_columns . ' LIKE'] = $name;
        if (is_array($this->join_models))
        {
            $join = $this->getJoin($this->join_models);
            $ds->setJoinTable($join);
        }
        $rows = $ds->getData($where, '', $this->order_by);
        $this->mergeDescriptionRows($rows);
        return $rows;
    }

    // alias of findAll
    function find($arg_where = array(), $lang_id = '')
    {
        return $this->findAll($arg_where, $lang_id);
    }

    // alias of findAll
    function findBy($arg_where = array(), $lang_id = '')
    {
        return $this->findAll($arg_where, $lang_id);
    }

    // need optimization for merging description
    function findAll($arg_where = array(), $lang_id = '')
    {
        $ds = $this->getDataStore($lang_id);
        if (is_array($this->join_models))
        {
            $join = $this->getJoin($this->join_models);
            $ds->setJoinTable($join);
        }
        $rows = $ds->getData($arg_where, '', $this->order_by);
        // join description with bulk select from db
        $this->mergeDescriptionRows($rows);
        mergeRowsOrderNumber($rows, 'order_no');
        return $rows;
    }

    function selectById($id, $lang_id = '')
    {
        $where = $this->generateWhereId($id);
        if ($where === false)
            return false;
        $ds = $this->getDataStore($lang_id);
        list($row) = $ds->getData($where);
        return $row;
    }

    function selectAll($arg_where = array(), $lang_id = '')
    {
        $ds = $this->getDataStore($lang_id);
        $rows = $ds->getData($arg_where, '', $this->order_by);
        return $rows;
    }

    function setListValueItem($value, $item)
    {
        $this->list_item = $item;
        $this->list_value = $value;
        if ($value && $item)
            $this->enable_getlist = true;
        else
            $this->enable_getlist = false;
    }

    function getList($arg_default = '', $arg_where = array())
    {
        if (!$this->enable_getlist)
            return false;
        $list_rows = $this->findAll($arg_where);
        return getList($list_rows, $this->list_value, $this->list_item, $arg_default);
    }

    function getJoin($model_relationship)
    {
        if (is_array($model_relationship))
        {
            foreach ($model_relationship as $class => $keys)
            {
                $m = $this->getModel($class);
                if ($m)
                    $join[$m->table_name] = $keys;
            }
        }
        else
            $join = null;
        return $join;
    }

    function mergeDescription(&$row)
    {
//         return $row;
        if ($row != '')
        {
            if (is_array($this->described_by))
            {
                foreach ($this->described_by as $class => $keys)
                {
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;
                    if (count($keys) == 1)
                    {
                        list($fkey, $pkey) = each($keys);
                        if (!is_numeric($fkey))
                        {
                            $keys = array($fkey);
                            if (is_array($pkey))
                                $val_names = $pkey;
                            else
                                $val_names = array($pkey);
                        }
                    }
                    $vals = $model->findById(copyArrayValue($row, $keys));
                    if (is_array($vals) && (count($vals) > 0))
                    {
                        if (isset($val_names))
                            $vals = copyArrayValue($vals, $val_names);
                        $row = array_merge($row, $vals);
                    }
                }
            }

            if (is_array($this->join_constants))
            {
                foreach ($this->join_constants as $const_id => $key)
                    $row[$key . '_desc'] = $this->parent->getConstantDescription($const_id, $row[$key]);
            }
        }
    }

    function mergeDescriptionRows(&$rows, $selected = array())
    {
        if (count($rows) > 0)
        {
            if (is_array($this->described_by))
            {
                foreach ($this->described_by as $class => $keys)
                {
                    if ((count($selected) > 0) && (!in_array($class, $selected)))
                        continue;
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;
                    if (count($keys) == 1)
                    {
                        list($fkey, $val_names) = each($keys);
                        if (is_numeric($fkey))
                            $fkey = $val_names;
                        if (!is_array($val_names))
                            $val_names = array($val_names);
                        $descs = $model->find(array($fkey => extractArrayValue($rows, $fkey)));
                        toDescription($rows, $descs, $fkey, $val_names);
                    }
                    else
                    {
                        // should not happen
                    }
                }
            }

            if (is_array($this->join_constants))
            {
                foreach ($this->join_constants as $const_id => $key)
                {
                    $consts = $this->parent->getConstantSet($const_id);
                    $consts = makeAssocArray($consts, CONST_COLUMN_VALUE);
                    foreach ($rows as $k => $row)
                        if ($row[$key] != '')
                            $rows[$k][$key . '_desc'] = $consts[$row[$key]][CONST_COLUMN_DESC];
                }
            }
        }
    }

    function mergeMany(&$rows, $selected = array())
    {
        if (count($rows) > 0)
        {
            // need to be revised more if many to many
            if (is_array($this->has_many))
            {
                foreach ($this->has_many as $class => $keys)
                {
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;
                    if ((count($selected) > 0) && (!in_array($class, $selected)))
                        continue;
                    if (count($keys) == 1)
                    {
                        if (is_array($keys))
                        {
                            list($pkey, $fkey) = each($keys);
                            if (is_numeric($pkey))
                                $pkey = $fkey;
                        }
                        $where = array($fkey => extractArrayValue($rows, $pkey));
                    }
                    else
                    {
                        $where = extractArrayValues($rows, $keys);
                        $where = prefixRowsKey($where, 'AND_');
                        $where = array('OR_1' => $where);
                    }

                    $comps = $model->findAll($where);

                    $comps_name = $model->model_name . SUFFIX_ROWS;

                    // must merge composition before grouped
//                     $model->mergeOne($comps);
//                     $model->mergeMany($comps);
                    $this->mergeCompositionRows($rows, $comps_name, $comps, $keys, '', true);
//                     $comps = makeCompoundGroupedArray($comps, $keys);
//                     foreach ($rows as $key => $row)
//                         $rows[$key][$comps_name] = $comps[getCompoundKey($row, $keys)];
                }
            }
        }
    }

    function mergeOne(&$rows, $selected = array())
    {
        if ((count($rows) > 0) && (is_array($this->has_one)))
        {
            foreach ($this->has_one as $class => $keys)
            {
                if ((count($selected) > 0) && (!in_array($class, $selected)))
                    continue;
                $model = $this->getModel($class);
                if (!$model)
                    continue;
                if (count($keys) == 1)
                {
                    if (is_array($keys))
                    {
                        list($fkey, $pkey) = each($keys);
                        if (!is_numeric($fkey))
                            $comp_name = $this->getCompositionName($model->model_name, $fkey, $pkey);
                        else
                        {
                            $comp_name = $model->model_name;
                            $fkey = $pkey;
                        }
                        $keys = $pkey;
                    }
                    else
                        $fkey = $keys;
                    $where = array($keys => extractArrayValue($rows, $fkey));
                }
                else  // seems this case never happen
                {
                    $comp_name = $model->model_name;
                    $where = extractArrayValues($rows, $keys);
                    $where = prefixRowsKey($where, 'AND_');
                    $where = array('OR_1' => $where);
                }

                $comps = $model->findAll($where);
                // must merge composition before grouped
//                 $model->mergeOne($comps);
//                 $model->mergeMany($comps);
                $this->mergeCompositionRows($rows, $comp_name, $comps, $keys, $fkey);
//                     $comps = makeCompoundAssocArray($comps, $keys);
//                     foreach ($rows as $key => $row)
//                         $rows[$key][$comp_name] = $comps[getCompoundKey($row, $keys)];
            }
        }
    }

    function mergeCompositionRows(&$rows, $comp_name, &$comps, $keys, $comp_key = '', $mode_many = false)
    {
        if (is_array($keys) && (count($keys) <= 1))
            $keys = array_shift($keys);
        if (count($comps) < 1)
            return;

        if (is_array($keys))
        {
            $comps = ($mode_many ? makeCompoundGroupedArray($comps, $keys) : makeCompoundAssocArray($comps, $keys));
            foreach ($rows as $key => $row)
                $rows[$key][$comp_name] = $comps[getCompoundKey($row, $keys)];
        }
        else
        {
            if ($comp_key == '')
                $comp_key = $keys;
            $comps = ($mode_many ? makeGroupedArray($comps, $keys) : makeAssocArray($comps, $keys));
            foreach ($rows as $key => $row)
                if (array_key_exists($comp_key, $row) && $row[$comp_key])
                    $rows[$key][$comp_name] = $comps[$row[$comp_key]];
        }
    }

    function mergeComposition(&$row)
    {
        if ($row != '')
        {
            // need to be revised more if many to many
            if (is_array($this->has_many))
            {
                foreach ($this->has_many as $class => $keys)
                {
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;
//                     if (array_key_exists($class, $this->many_order_by))
//                         $model->order_by = $this->many_order_by[$class];
                    if (count($keys) == 1)
                    {
                        list($pkey, $fkey) = each($keys);
                        if (is_numeric($pkey))
                            $pkey = $fkey;
                        $keys = array($fkey => $row[$pkey]);
                    }
                    else
                    {
                        $keys = copyArrayValue($row, $keys);
                    }
                    $row[$model->model_name . SUFFIX_ROWS] = ($keys ? $model->findAll($keys) : array());
                }
            }

            if (is_array($this->has_one))
            {
                foreach ($this->has_one as $class => $keys)
                {
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;
                    if (count($keys) == 1)
                    {
                        list($fkey, $pkey) = each($keys);
                        if (!is_numeric($fkey))
                            $cname = $this->getCompositionName($model->model_name, $fkey, $pkey);
                        else
                        {
                            $cname = $model->model_name;
                            $fkey = $pkey;
                        }
                        $keys = @$row[$fkey];
                    }
                    else
                    {
                        $cname = $model->model_name;
                        $keys = copyArrayValue($row, $keys);
                    }
                    $result = ($keys ? $model->findById($keys) : false);
                    if ($result)
                        $row[$cname] = $result;
                }
            }
        }
    }

    function getCompositionName($model_name, $fkey, $pkey)
    {
        if ($this->convert_composite_name)
        {
            $pos = strpos($fkey, $pkey);       // need to be revised to strrpos() when PHP5 is wide-spread enough
            return substr($fkey, 0, $pos) . $model_name;
        }
        else
            return $fkey;
    }

    // result of belonger is different from mergeBelonger() in sub-structure of belonger is not retrieved, only the belonger record is retrieved.
    function mergeBelongerRows(&$rows, $selected = array())
    {
        if (count($rows) > 0)
        {
            if (is_array($this->belongs_to))
            {
                foreach ($this->belongs_to as $class => $keys)
                {
                    if (((count($selected) > 0) && (!in_array($class, $selected))) ||
                            ($class == get_class($this)))
                        continue;
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;
                    if (count($keys) == 1)
                    {
//                         if (is_array($keys))
//                             $keys = array_shift($keys);
//                         $where = array($keys => extractArrayValue($rows, $keys));
                        if (is_array($keys))
                        {
                            list($fkey, $pkey) = each($keys);
                            if (!is_numeric($fkey))
                                $comp_name = $this->getCompositionName($model->model_name, $fkey, $pkey);
                            else
                            {
                                $comp_name = $model->model_name;
                                $fkey = $pkey;
                            }
                            $keys = $pkey;
                        }
                        else
                            $fkey = $keys;
                        $where = array($keys => extractArrayValue($rows, $fkey));
                    }
                    else  // seems this case never happen
                    {
                        $comp_name = $model->model_name;
                        $where = extractArrayValues($rows, $keys);
                        $where = prefixRowsKey($where, 'AND_');
                        $where = array('OR_1' => $where);
                    }

                    $belongers = $model->findAll($where);
//                     $this->mergeCompositionRows($rows, $model->model_name, $belongers, $keys);
                    $this->mergeCompositionRows($rows, $comp_name, $belongers, $keys, $fkey);
                }
            }

            if (is_array($this->belongs_to_many))
            {
                foreach ($this->belongs_to_many as $class => $medium)
                {
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;

                    // check type of mapping key
                    if (is_array($medium))
                    {
                        // find mapping info from the medium table
                        list($class_medium, $keys) = each($medium);                 // currently $keys must be scalar, use 'keys' for future
                        $model_medium = $this->getModel($class_medium);
                        if (!$model_medium)
                            continue;
                        $mapping = $model_medium->find(array($this->primary_key => extractArrayValue($rows, $this->primary_key)));

                        // find data from the final table
                        if (!@$keys)
                            $keys = $model->primary_key;
                        $target_data = $model->find(array($keys => extractArrayValue($mapping, $keys)));
                        $target_data = makeAssocArray($target_data, $keys);

                        // merge data of final tablse to source
                        $mapping = makeGroupedArray($mapping, $this->primary_key);
                        foreach ($rows as $key => $row)
                        {
                            if (array_key_exists($row[$this->primary_key], $mapping))
                                $rows[$key][$model->model_name . SUFFIX_ROWS] = copyArrayValue($target_data, extractArrayValue($mapping[$row[$this->primary_key]], $keys));
                            else
                                $rows[$key][$model->model_name . SUFFIX_ROWS] = array();
                        }
                    }
                    else
                    {
                        foreach ($rows as $key => $row)
                        {
                            // $medium is the col name to be exploded
                            $mapping_vals = (array_key_exists($medium, $row) && is_array($row[$medium])) ? $row[$medium] : explode(',', $row[$medium]);
                            if (count($mapping_vals) > 0)
                            {
                                $keys = $model->primary_key;
                                $rows[$key][$medium . SUFFIX_ROWS] = $model->find(array($keys => $mapping_vals));
// 			                	$rows[$key][$model->model_name . SUFFIX_ROWS] = $model->find(array($keys => $mapping_vals));
                            }
                            else
                                $rows[$key][$medium . SUFFIX_ROWS] = array();
// 		                		$rows[$key][$model->model_name . SUFFIX_ROWS] = array();
                        }
                    }
                }
            }
        }
    }

    function mergeBelonger(&$row)
    {
        if ($row != '')
        {
            if (is_array($this->belongs_to))
            {
                foreach ($this->belongs_to as $class => $keys)
                {
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;
                    if (count($keys) == 1)
                    {
                        list($fkey, $pkey) = each($keys);
                        if (!is_numeric($fkey))
                            $cname = $this->getCompositionName($model->model_name, $fkey, $pkey);
                        else
                        {
                            $cname = $model->model_name;
                            $fkey = $pkey;
                        }
                        $keys = @$row[$fkey];
                    }
                    else
                    {
                        $cname = $model->model_name;
                        $keys = copyArrayValue($row, $keys);
                    }
                    $result = ($keys ? $model->findById($keys) : false);
                    if ($result)
                        $row[$cname] = $result;
//                     $row[$model->model_name] = $model->findById(copyArrayValue($row, $keys));
                }
            }

            if (is_array($this->belongs_to_many))
            {
                foreach ($this->belongs_to_many as $class => $medium)
                {
                    $model = $this->getModel($class);
                    if (!$model)
                        continue;

                    // check type of mapping key
                    if (is_array($medium))
                    {
                        // find mapping info from the medium table
                        list($class_medium, $keys) = each($medium);                 // currently $keys must be scalar, use 'keys' for future
                        $model_medium = $this->getModel($class_medium);
                        if (!$model_medium)
                            continue;
                        $mapping = $model_medium->find(copyArrayValue($row, (array) $this->primary_key));
                        $mapping_vals = extractArrayValue($mapping, $keys);
                        $cname = $model->model_name;
                    }
                    else
                    {
                        // $medium is the col name to be exploded
                        if (is_array(@$row[$medium]))
                            $row[$medium] = implode(',', $row[$medium]);
                        $mapping_vals = explode(',', $row[$medium]);
                        $keys = $model->primary_key;
                        $cname = $medium;
                    }

                    // find data from the final table
                    $row[$cname . SUFFIX_ROWS] = $model->find(array($keys => $mapping_vals));
//                     $row[$model->model_name . SUFFIX_ROWS] = $model->find(array($keys => $mapping_vals));
                }
            }
        }
    }

    function insert($data)
    {
        $ds = $this->getDataStore();
        $result = $ds->insert($data);
        if ($result === true)
        {
            if ($ds->insert_id)
                return $ds->insert_id;
            else
            {
                if (is_array($this->primary_key))
                    $result = copyArrayValue($data, $this->primary_key);
                else
                    $result = $data[$this->primary_key];
            }
        }
        return $result;
    }

    function insertRows($data)
    {
        $ds = $this->getDataStore();
        $result = $ds->insertRows($data);
    }

    function updateById($id, $data)
    {
        if (!is_array($id))
            $where_id = array($this->primary_key => $id);
        else
            $where_id = $id;
        $ds = $this->getDataStore();
        $result = $ds->update($data, $where_id);
        return ($result ? $id : false);
    }

    function update($data)
    {
        if (!is_array($data))
            return false;
        // separate id and data
        $keys = (is_array($this->primary_key) ? $this->primary_key : array($this->primary_key));
        $id = copyArrayValue($data, $keys);
        if (count($id) < 1)
            return false;     // will not update if there is no key to where
		unsetArrayValue($data, $keys);
        $result = $this->updateById($id, $data);
        return $result;
    }

    // delete by id or where-array
    function delete($id)
    {
        if (!is_array($id))
            $id = array($this->primary_key => $id);
        $ds = $this->getDataStore();
        $result = $ds->delete($id);
        return $result;
    }

    function getDataStore($lang_id = '')
    {
        if (!(isset($this->ds) && is_object($this->ds)))
            if (isset($this->table_name))
                $this->ds = $this->parent->getDataStore($this->table_name);
            else
                return false;
        $this->ds->column_case = $this->column_case;
        $this->ds->string_column_list = $this->string_column_list;

        if (($lang_id) && ($this->ds->multilang_lang != $lang_id))
        {
            $ds_new = clone($this->ds);
            $ds_new->multilang_lang = $lang_id;
            $this->parent->getDbLangaugeSupport($ds_new, $lang_id);
            $ds_new->column_case = $this->column_case;
            $ds_new->string_column_list = $this->string_column_list;
            return $ds_new;
        }
        else
            return $this->ds;
    }

	function createTable($detail)
	{
        if (!is_array($detail))
            return false;
        $ds = $this->getDataStore();
        $result = $ds->createTable($detail);
        return $result;

	}

    // abstract method for subclasses
    function init()
    {

    }

}

?>