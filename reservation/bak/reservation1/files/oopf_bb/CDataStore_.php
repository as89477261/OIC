<?php define('ORDERBY_ASC','ASC');
define('ORDERBY_DESC','DESC');

require_once('utils_framework.php');

// CDataStore for Oracle, should be refactor with MySql's one later soon

class CDataStore
{
	// default db info, will be reset while instantiating
	var $db_link;
	var $db_host;
	var $db_user;
	var $db_password;
	//
	var $connection_encoding = '';
	var $db_name;
	var $db_table;		  // table name of the data store class. needed to set in every child of this class.
	var $order_by;		  // example: array('columnname'=>ORDERBY_ASC or ORDERBY_DESC,...);
	var $column_list = false;
	var $pkey_column_list = false;
	var $join_table_list = false;   // array ('TableName' => array('SourceKey' => 'DestinationKey'));
	var $join_table = false;		// enable/disable joining description and related data from other tables
	var $default_where = false;	 // where clause mandatory for every query
	//
	var $db_error_no = 0;	   // error from db;
	var $result_count = 0;	  // keep affected rows count of the last call to execSql()
	var $insert_id = null;	  // keep auto_increment_id from the last 'INSERT' statement
	var $fetch_type = OCI_ASSOC;
	var $prefetch = 0;		// use default prefetch

	var $limit_rows = 0;
	var $limit_offset = 0;

	var $list_item, $list_value;
	var $enable_getlist = false;

	var $multilang = false;		 // enable multi-language support by column or not?
	var $multilang_tables = false;
	var $multilang_column;		  // multi-language column name
	var $multilang_lang;			// language id

	var $debug_sql = false;

	// $arg_dbinfo can be array of info or resource of db connection
	function CDataStore($dbinfo, $opt_dbname = '', $opt_table = '')
	{
		// set db name
		if ($opt_dbname != '')
			$this->db_name = $opt_dbname;
		elseif (is_array($dbinfo) && ($dbinfo['DbName']))
			$this->db_name = $dbinfo['DbName'];

		// looking for connection or resource
		if (is_array($dbinfo))
		{
			$this->db_user = $dbinfo['DbUser'];
			$this->db_password = $dbinfo['DbPassword'];
			$this->db_host = $dbinfo['DbHost'];

			// Connecting, selecting database
			if ($this->connection_encoding)
				$this->db_link = oci_connect($this->db_user, $this->db_password, $this->db_host . '/' . $this->db_name, $this->connection_encoding);			// *** need to check format of encoding to be used with oci
			else
				$this->db_link = oci_connect($this->db_user, $this->db_password, $this->db_host . '/' . $this->db_name);
			$this->db_error_no = oci_errno();
			if ($this->db_error_no != 0)
				return;
		}
		elseif (is_resource($dbinfo))
			$this->db_link =& $dbinfo;

		// set table name
		if ($opt_table != '')
			$this->db_table = $opt_table;
		elseif ($dbinfo['DbTable'])
			$this->db_table = $dbinfo['DbTable'];

		// get list of fieldname
	}

	// static function
	function connectDatabase($db_host, $db_user, $db_password, $db_name = '', $db_encoding = '')
	{
		if ($db_encoding)
			$db_link = oci_connect($db_user, $db_password, $db_host . '/' . $db_name, $db_encoding);
		else
			$db_link = oci_connect($db_user, $db_password, $db_host . '/' . $db_name);
		return $db_link;
	}

	// static function
	function disconnectDatabase(&$db_link)
	{
		if (is_resource($db_link))
			return oci_close($db_link);
		else
			return false;
	}

	function setEncoding($std_encoding)
	{
		$this->connection_encoding = $std_encoding;
	}

	function setMultiLanguageSupport($tables, $col_name, $lang)
	{
		if ($tables)
		{
			if (is_array($tables))
				$this->multilang_tables = $tables;
			else
				$this->multilang_tables = array($tables);
			$this->multilang_column = $col_name;
			$this->multilang_lang = $lang;
			$this->multilang = true;
		}
		return $this->multilang;
	}

	function setJoinTable($join_list)
	{
		if (is_array($join_list) && (count($join_list) > 0))
		{
			$this->join_table_list = $join_list;
			$this->join_table = true;
		}
		else
		{
			$this->join_table_list = false;
			$this->join_table = false;
		}
	}

	function setDefaultWhere($arr_where)
	{
		$this->default_where = $this->generateWhere($arr_where);
	}

	function generateWhere($arg_input = array(), $op = 'AND')			// *** revise
	{
		$where = '';
		$table_name = ($this->db_table ? $this->db_table . '.' : '');
		if (count($arg_input) > 0)
		{
			foreach ($arg_input as $field_name => $value)
			{
				if ((substr($field_name, 0, 3) != 'AND') && (substr($field_name, 0, 2) != 'OR') && (!preg_match('/\./', $field_name)))	  // currently, capital case only
					$field_name = ((strpos($field_name, '.') === false) && (strpos($field_name, '(') === false) ? $table_name : '') . $field_name;			// support FUNCTION in $field_name
				if (is_array($value))
				{
					if (preg_match('/\bLIKE\b/i', $field_name))
					{
						// support multiple LIKE
						$each_like = array();
						foreach ($value as $sub_value)
							if (strpos($sub_value, '%') !== false)
								$each_like[] = ' '.$field_name." '".$sub_value."'";
							else
								$each_like[] = ' '.$field_name." '%".$sub_value."%'";
						$each_where = '(' . implode(' OR ', $each_like) . ')';
					}
					elseif (preg_match('/\bREGEXP\b/i', $field_name))			// support on 29/08/2009
					{
						// support multiple REGEXP
						$each_regexp = array();
						foreach ($value as $sub_value)
							$each_regexp[] = ' '.$field_name." '".$sub_value."'";
						$each_where = '(' . implode(' OR ', $each_regexp) . ')';
					}
					elseif (preg_match('/^OR\b/i', $field_name))
						$each_where = '(' . $this->generateWhere($value, 'OR') . ')';
					elseif (preg_match('/^AND\b/i', $field_name))
						$each_where = '(' . $this->generateWhere($value, 'AND') . ')';
					else // support IN, NOT IN
					{
						if (! preg_match('/\bIN\b/i', $field_name))
							$field_name .= ' IN';
						if (in_array(null, $value))
						{
							$value = array_diff($value, array(null));
							$has_null = true;
						}
						$each_where = $field_name . " ('" . implode("','", $value) . "')";
						if (isset($has_null) && $has_null)
						{
							$not = (preg_match('/\bNOT\b/i', $field_name) ? ' NOT' : '');
							$name = substr($field_name, 0, strpos($field_name, $not . ' IN'));
							$each_where = '(' . $each_where . ' OR ' . $name . ' IS' . $not . ' NULL)';
						}
					}
				}
				elseif (is_null($value))
				{
					$p = strpos($field_name, '!=');
					if ($p === false)
						$each_where = ' ' . $field_name . ' IS NULL';
					else
						$each_where = ' ' . substr($field_name, 0, $p) . ' IS NOT NULL';
				}
				else
				{
					if (stripos($field_name, ' LIKE', 2))
					{
						if (strpos($value, '%') !== false)
							$each_where = ' '.$field_name." '".$value."'";
						else
							$each_where = ' '.$field_name." '%".$value."%'";
					}
					elseif (stripos($field_name, ' REGEXP', 2))					// support on 29/08/2009
							$each_where = ' '.$field_name." '".$value."'";
					elseif (preg_match('/[<>=]/i', $field_name))
						$each_where = ' '.$field_name." '".$value."'";
					else
						$each_where = ' '.$field_name." = '".$value."'";
				}
				if ($where == '')
					$where .= $each_where;
				else
					$where .= (' ' . $op . ' ' . $each_where);
			}
		}
		return $where;
	}

	function generateJoinWhere($arg_table_list = '')
	{
		if (is_array($arg_table_list) && (count($arg_table_list) > 0))
		{
			foreach ($arg_table_list as $table)
				$to_join[$table] = $this->join_table_list[$table];
		}
		elseif ($this->join_table)
			$to_join = $this->join_table_list;
		else
			return array(array(), '');

		$froms = array();
		foreach ($to_join as $tablename => $fieldlist)
		{
			$froms[] = $tablename;
			if (is_array($fieldlist))
			{
				foreach ($fieldlist as $source => $destination)
				{
					if (is_int($source) || $source == '')
					{
						if (!is_array($destination))
							$source = $destination;
						elseif (!$destination)
							continue;
					}
					if (!$destination)
					{
						if ($source)
							$destination = $source;
						else
							continue;
					}
					elseif (is_array($destination))	 // cascading join
					{
//						$ds_dummy = new CDataStore(array('DbTable' => $tablename));
						$ds_dummy = new CDataStore('', '', $tablename);
						$ds_dummy->setJoinTable(array($source => $destination));
						if ($this->multilang)
							$ds_dummy->setMultiLanguageSupport($this->multilang_tables, $this->multilang_column, $this->multilang_lang);
						list($from, $join_str) = $ds_dummy->generateJoinWhere();
						$froms = array_merge($froms, $from);
						$where[] = $join_str;
					}
					else
						$where[] = $this->db_table . '.' . $source . ' = ' . $tablename . '.' . $destination;
				}
			}
			elseif ($fieldlist)
				$where[] = $this->db_table . '.' . $fieldlist . ' = ' . $tablename . '.' . $fieldlist;

			// add criteria for multi-language support
			if (($this->multilang) && ($this->multilang_column) && (in_array($tablename, $this->multilang_tables)))
				$where[] = $tablename . '.' . $this->multilang_column . " = '" . $this->multilang_lang . "'";
		}
		return array(array_unique($froms), implode(' AND ', $where));
	}

	function generateOrderBy($arg_orderby = array())
	{
		$order_by = array();
		if (!is_array($arg_orderby))
			return $arg_orderby;
		if (count($arg_orderby) <= 0)
			$arg_orderby = $this->order_by;
		if (count($arg_orderby) > 0)
		{
			foreach ($arg_orderby as $field_name => $sort_type)
				$order_by[] = $field_name . ' ' . $sort_type;
		}
		return implode(',', $order_by);
	}

	function generateCsvList($arg_fields = array(), $add_table_name = false)
	{
		if (is_array($arg_fields))
		{
			foreach ($arg_fields as $key => $value)
			{
				if (is_int($key))
					$col = $value;
				else
					$col = $key;
				if (($add_table_name) && (!preg_match('/\W+/', $col)))
					$col = $this->db_table . '.' . $col;
				if (is_int($key))
					$result[] = $col;
				else
					$result[] = $col . ' AS ' . $value;
			}
			if (empty($result))
				return '';
			return ((count($result) > 0) ? implode(',', $result ) : '');
		}
		elseif (is_scalar($arg_fields))
		{
			if (($add_table_name) && (!preg_match('/\W+/', $arg_fields)))
				$arg_fields = $this->db_table . '.' . $arg_fields;
			return $arg_fields;
		}
		else
			return '';
	}

	function getCount($arg_where = array())		 // group by???
	{
		$where = $this->generateWhere($this->cleanSQL($arg_where));
		$sql = 'SELECT COUNT(*) AS COUNT FROM ' . $this->db_table;
		$sql .= ($where ? ' WHERE ' . $where : '');
		$result = $this->execSql($sql);
		$count = false;
		if ($this->db_error_no == 0)
		{
			if ($line = $this->fetchArray($result))
				$count = $line['COUNT'];
			oci_free_statement($result);
		}
		return $count;
	}

	function getCountGroup($arg_groups, $arg_columns = array(), $arg_where = array())
	{
		if (!is_array($arg_groups))
			$arg_groups = array($arg_groups);
		if (!is_array($arg_columns) && (count($arg_columns) > 0))
			$arg_columns = array($arg_columns);
		else
			$arg_columns = array();
		$arg_columns = array_unique(array_merge($arg_groups, $arg_columns));
//		foreach ($arg_columns as $column)
//			$columns[] = 'COUNT(' . $column . ') As ' . 'CountBy' . $column;
		$columns[] = 'COUNT(*) As Count';
		$where = $this->generateWhere($this->cleanSQL($arg_where));
		$sql = 'SELECT ' . $this->generateCsvList($arg_columns) . ',' . $this->generateCsvList($columns) . ' FROM ' . $this->db_table;
		$sql .= ($where ? ' WHERE ' . $where : '');
		$sql .= ' GROUP BY ' . $this->generateCsvList($arg_groups);
		$result = $this->execSql($sql);
		if ($this->db_error_no == 0)
			return $this->fetchAll($result);
		return false;
	}

//	 function isColumnExists($name)
//	 {
//		 $cols = $this->getasdf();
//		 $names = extractArrayValue($cols, 'Field');
//		 return in_array($name, $names);
//	 }

	function getColumnList()
	{
		if ($this->column_list === false)
		{
			$this->execSql('SHOW COLUMNS FROM ' . $this->asfd);
			if ($this->db_error_no == 0)
			{
				$this->column_list = $this->fetchAll($result);
				foreach ($this->column_list as $column)
					if ($column['Key'] == 'PRI')
						$pkey_result[] = $column;
				$this->pkey_column_list = $pkey_result;
			}
			else
				return false;
		}
		return $this->column_list;
	}

	function getPrimaryKeyList()
	{
		if ($this->pkey_column_list === false)
			$this->getColumnList();
		return $this->pkey_column_list;
	}

	// function getDescription()
	//		  retrieve Description from database for Display Description in HTML
	// $input   array of data to be used as conditions for WHERE clause
	// return   value of field $this->list_item of a retrieved record
/*
	function getDescription($arg_input = array())
	{
		$where = $this->generateWhere($this->cleanSQL($arg_input));
		$sql = 'SELECT '.$this->list_item.' FROM '.$this->db_table;
		$sql .= ($where ? ' WHERE '.$where : '');

		$result = $this->execSql($sql,$autoid);
		$arg_output = false;
		if ($this->db_error_no == 0)
		{
			if ($line = $this->fetchArray($result))
				$arg_output = $line[$this->list_item];
		}
		return $arg_output;
	}
*/
	// function getData()
	//		  retrieves data from database
	// $output  array of structure contains all fields from database
	// $input   array of data to be used as conditions for WHERE clause
	//
	function getData($arg_where = array(), $arg_jointable_list = '', $arg_orderby = array(), $arg_groupby = array())
	{
		return $this->select('*', $arg_where, $arg_jointable_list, $arg_orderby, $arg_groupby);
	}

	function select($arg_fieldlist = '*', $arg_where = array(), $arg_jointable_list = '', $arg_orderby = array(), $arg_groupby = array())
	{
		$where = $this->generateWhere($this->cleanSQL($arg_where));
		list($froms, $join_where) = $this->generateJoinWhere($arg_jointable_list);
		$order_by = $this->generateOrderBy($arg_orderby);
		$group_by = $this->generateCsvList($arg_groupby, false);
		$sql = 'SELECT ' . $this->generateCsvList($arg_fieldlist, true) . ' FROM ' . $this->db_table;
		if (is_array($froms) && (count($froms) > 0))
			$sql .= (', ' . implode(', ', $froms));
		if ($join_where)
			$where = '(' . $join_where . ')' . ($where ? (' AND ' . $where) : '');
		if (($this->default_where) && (! is_array($this->default_where)))
			$where = '(' . $this->default_where . ')' . ($where ? (' AND ' . $where) : '');

		$sql .= ($where ? ' WHERE ' . $where : '') .
				($group_by ? ' GROUP BY ' . $group_by : '') .
				($order_by ? ' ORDER BY ' . $order_by : '');

		if ($this->limit_rows > 0)
		{
			if ($this->limit_offset > 0)
				$limit_where = ('ROW_NO >= ' . $this->limit_offset . ' AND ');
			else
				$limit_where = '';
			$limit_where .= ('ROW_NO <= ' . ($this->limit_offset + $this->limit_rows));
			$sql = 'SELECT * FROM (SELECT OopfToLimit.*, ROWNUM ROW_NO FROM (' . $sql . ') OopfToLimit) WHERE ' . $limit_where;
		}

		$result = $this->execSql($sql);

		if ($this->db_error_no == 0)
			return $this->fetchAll($result);
		return false;
	}

	// function insert(), delete(), update()
	// $input   all value-pair used to generate SQL COMMAND.
	//
	function insert($arg_input)
	{
		foreach ($arg_input as $key => $value)
		{
//			$column_list[] = $key;
			if (is_null($value))
				$value_list[] = 'NULL';
			else
				$value_list[] = "'" . $this->cleanSQL($value) . "'";
		}
		$column_list = array_keys($arg_input);
		$column_list = implode(',', $column_list);
		$value_list = implode(',', $value_list);
		$sqlsyntax = 'INSERT INTO ' . $this->db_table . ' (' . $column_list . ') VALUES (' . $value_list . ')';

		$this->execSql($sqlsyntax);
//		$arg_auto_increment = $this->insert_id;
		return (($this->db_error_no == 0) ? true : false);
	}

	function insertRows($arg_input)		   // for multiple rows insert
	{
		if (is_array($arg_input) && (count($arg_input) > 0))
		foreach ($arg_input as $row)
		{
			$value_list = array();
			foreach ($row as $key => $value)
			{
				if (is_null($value))
					$value_list[] = 'NULL';
				else
					$value_list[] = "'" . $this->cleanSQL($value) . "'";
			}
			$values[] = implode(',', $value_list);
		}
		$column_list = array_keys($row);
		$column_list = implode(',', $column_list);
		$value_list = implode('),(', $values);
		$sqlsyntax = 'INSERT INTO ' . $this->db_table . ' (' . $column_list . ') VALUES (' . $value_list . ')';
		$this->execSql($sqlsyntax);
		return (($this->db_error_no == 0) ? true : false);
	}

	function delete($arg_where)
	{
		$where = $this->generateWhere($this->cleanSQL($arg_where));
		if (($this->default_where) && (! is_array($this->default_where)))
			$where = '(' . $this->default_where . ')' . ($where ? (' AND ' . $where) : '');
		$sqlsyntax = 'DELETE FROM '. $this->db_table . ' WHERE ' . $where;
		//
		$this->execSql($sqlsyntax);
		return (($this->db_error_no == 0) ? true : false);
	}

	function update($arg_input, $arg_where = array())
	{
		$value_list = array();
		foreach ($arg_input as $key => $value)
		{
			$column_list[] = $key;
			if (is_null($value))
				$value_list[] = $key . ' = NULL';
			else
				$value_list[] = $key . " = '" . $this->cleanSQL($value) . "'";
		}
		$value_list = implode(',', $value_list);
/*
		foreach ($arg_input as $key => $value)
		{
			if ($value_list != '')
				$value_list .= ',';
			$value_list .= ' ' . $key . " = '" . $this->cleanSQL($value) . "'";
		}
*/
		$sqlsyntax = 'UPDATE ' . $this->db_table . ' SET ' . $value_list;
		if (count($arg_where) > 0)
			$where = $this->generateWhere($this->cleanSQL($arg_where));
		if (($this->default_where) && (! is_array($this->default_where)))
			$where = '(' . $this->default_where . ')' . ($where ? (' AND ' . $where) : '');
		if ($where != '')
			$sqlsyntax .= ' WHERE ' . $where;

		$this->execSql($sqlsyntax);
		return (($this->db_error_no == 0) ? true : false);
	}

	// free format selection
	function query($arg_sql)
	{
		$result = $this->execSql($arg_sql);
		if ($this->db_error_no == 0)
		{
			if (strtoupper(substr($arg_sql, 0, 6)) == 'SELECT')
				return $this->fetchAll($result);
			else
				return true;
		}
		return false;
	}

	function createTable($table, $detail)
	{
		$col_list = array();
		foreach ($detail['ColumnList'] as $column)
		{
			if (array_key_exists('Size', $column) && ($column['Size'] != ''))
				$size = '(' . $column['Size'] . ')';
			else
				$size = '';
			$col_list[] = $column['ColumnName'] . ' ' . $column['Type'] . $size;
		}
		$sql = 'CREATE TABLE ' . $table . ' (' . implode(', ', $col_list) . ')';
		if (count($detail['PrimaryKey']) > 0)
			$sql .= ' PRIMARY KEY (' . implode(', ', array_unique($detail['PrimaryKey'])) . ')';
		$result = $this->execSql($sql);
		return ($this->db_error_no == 0);
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
		if (! $this->enable_getlist)
			return false;
		$list_rows = $this->getData($arg_where);
		return getList($list_rows, $this->list_value, $this->list_item, $arg_default);
	}

	// function isKeyExistent()
	//		  check if primary key field of table is exists
	// return   boolean
	//
	function isKeyExistent($arg_where)
	{
		if (is_scalar($arg_where))
		{
			$pkey_list = $this->getPrimaryKeyList();
			if (count($pkey_list) == 1)
				$arg_where = array($pkey_list[0] => $arg_where);
			else
				return false;	   // should assume the first column???
		}
		$sql = 'SELECT * FROM ' . $this->db_table . ' WHERE ';
		$sql .= $this->generateWhere($this->cleanSQL($arg_where));
		$result = $this->execSql($sql);
		if ($this->db_error_no == 0)
		{
			$this->fetchArray($result);
			if ($this->result_count > 0)
				return true;
			else
				return false;
		}
	}

	function cleanSql($arg_input)
	{
		if (is_scalar($arg_input))
			return oracle_escape_string($arg_input);
		elseif (is_array($arg_input))
		{
			foreach ($arg_input as $key => $value)
				$arg_input[$key] = $this->cleanSql($value);
			return $arg_input;
		}
		else
			return $arg_input;
	}
/*
	function cleanSql(&$arg_text)
	{
		$return = is_array($arg_text) ? $arg_text : array($arg_text);
		foreach ($return as $key => $value)
		{
			if (is_array($value))
				$new_value = $this->cleanSQL($value);
			elseif (is_null($value))
				$new_value = null;
			else
			{
				$new_value = '';
				$chars = preg_split('//', $value, -1, PREG_SPLIT_NO_EMPTY);
				$count = count($chars);
				for ($c=0; $c<$count; $c++)
				{
					$letter=$chars[$c];
					if (!preg_match("/[a-zA-Z0-9 _%*\-.,:;]/", $letter))
						$new_value .= "\\$letter";
					else
						$new_value .= "$letter";
				}
			}
			$return[$key] = $new_value;
		}
		return is_array($arg_text) ? $return : $return[0];
	}
*/
	function execSql($arg_sql, $committed = CONST_YES)
	{
		if ($this->debug_sql)
		{
			echo $arg_sql;
//			 return true;
		}
		if (! $arg_sql)
		{
			error_log('Blank SQL is passed to execute');
			return false;
		}
		$result = false;
		if ($this->db_error_no == 0)
		{
//			 mysql_select_db($this->db_name, $this->db_link);
			$error = oci_error($this->db_link);
			$this->db_error_no = (is_array($error) ? $error['code'] : 0);
			if ($this->db_error_no == 0)
			{
				$stmt = oci_parse($this->db_link, $arg_sql);
				if ($this->prefetch > 0)
					oci_set_prefetch($stmt, $this->prefetch);			// set prefetch on demand
				$mode = ($committed == CONST_YES ? OCI_COMMIT_ON_SUCCESS : OCI_DEFAULT);
				$result = oci_execute($stmt, $mode);
				if ($result === true)
				{
					$this->db_error_no = 0;
					$result = $stmt;
				}
				else
				{
					$error = oci_error($this->db_link);
					$this->db_error_no = (is_array($error) ? $error['code'] : 0);
				}
				if ($this->db_error_no == 0)
				{
// *** check soon
//					 $this->insert_id = mysql_insert_id($this->db_link);
					 if (strtoupper(substr($arg_sql, 0, 6)) == 'SELECT')
					 	$this->result_count = 0;
					 	// for 'select', it is needed to fetch result to know the count
					 else
						 $this->result_count = oci_num_rows($stmt);
				}
				else
					$this->result_count = 0;
			}
		}
		if ($this->db_error_no > 0)
		{
			{
				error_log('DataStore Error: ' . $this->db_error_no.' = ' . $error['message'] . ' at character ' . $error['offset'] . ' of "' . $error['sqltext'] . '".');
				error_log($arg_sql);
			}
		}
		return $result;
	}

	function commit()
	{
		return oci_commit($this->db_link);
	}

	function rollback()
	{
		return oci_rollback($this->db_link);
	}

	function fetchArray($arg_result)
	{
// 		if (!in_array($this->fetch_type, array(OCI_ASSOC, OCI_NUM, OCI_BOTH)))
// 			$this->fetch_type = OCI_ASSOC;
		$row = oci_fetch_array($arg_result, OCI_FETCHSTATEMENT_BY_ROW | $this->fetch_type);
		if (is_array($row))
			$this->result_count++;
		return $row;
	}

	function fetchObject($arg_result)
	{
		$row = oci_fetch_object($arg_result);
		if (is_array($row))
			$this->result_count++;
		return $row;
	}

	// extend these function in the future

	function fetchAll($arg_result)
	{
// 		$this->fetch_type = OCI_FETCHSTATEMENT_BY_ROW;
		$output = array();
		oci_fetch_all($arg_result, $output, 0, -1, OCI_FETCHSTATEMENT_BY_ROW | $this->fetch_type);
		$this->result_count = oci_num_rows($arg_result);
		oci_free_statement($arg_result);
		return $output;
	}

}

function oracle_escape_string($str)
{
	return str_replace(array('"', "'"), array('""', "''"), $str);
	// need to escape for % and _ in where clause too
}
?>
