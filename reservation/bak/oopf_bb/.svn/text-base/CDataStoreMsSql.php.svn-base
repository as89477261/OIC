<?php require_once('CDataStore.php');

// to-do: get insert id back for insert statement, confirm if the utf-8 columns work!

class CDataStoreMsSql extends CDataStore
{
	var $fetch_type = MSSQL_ASSOC;
	var $count_key = 'Count';
        var $create_sql = 'CREATE TABLE %s ( %s , CONSTRAINT PK_%1$s PRIMARY KEY ( %s ))';
        var $data_type = array(DT_VARCHAR => 'nvarchar', DT_TEXT => 'text', DT_INTEGER => 'int',
                                        DT_DECIMAL => 'decimal', DT_DATE => 'datetime', DT_DATETIME => 'datetime',
                                        DT_BOOLEAN => 'char(1)');
//        var $bind_attr_field = array('title'=>'attr_title',
//                                                'type'=>'attr_type',
//                                                'size'=>'attr_size',
//                                                'precision'=>'attr_precision',
//                                                'scale'=>'attr_scale',
//                                                'default'=>'attr_default',
//                                                'primary_key'=>'attr_primary_key');

	// static function
	function connectDatabase($db_host, $db_user, $db_password, $db_name = '', $db_encoding = '')
	{
		$db_link = mssql_connect($db_host, $db_user, $db_password);
		if ($result === false)
			$this->db_error_no = 1;			// no error code for mssql in php
		if ($this->db_error_no != 0)
			return false;
		return $db_link;
	}

	// static function
	function disconnectDatabase(&$db_link)
	{
		if (is_resource($db_link))
			return mssql_close($db_link);
		else
			return false;
	}

	function quoteValue($real_col_name, $value)
	{
		return "'" . $value . "'";
	}

	function quoteValues($real_col_name, $values)
	{
		return "'" . implode("','", $values) . "'";
	}

	function mergeSqlLimit(&$sql)
	{
		if ($this->limit_offset > 0)
			$limit_where = ('OOPF_ROW_NO >= ' . $this->limit_offset . ' AND ');
		else
			$limit_where = '';
		$limit_where .= ('OOPF_ROW_NO <= ' . ($this->limit_offset + $this->limit_rows));
		$sql = 'SELECT * FROM (SELECT OopfToLimit.*, ROW_NUMBER() AS OOPF_ROW_NO FROM (' . $sql . ') OopfToLimit) WHERE ' . $limit_where;
	}

	function generateSqlInsertMultipleRows($column_list, $values)
	{
		return 'INSERT INTO ' . $this->db_table . ' (' . implode(',', $column_list) . ') SELECT ' . implode(' UNION ALL SELECT ', $values);
	}

	function escapeString($arg_str)
	{
		return mssql_escape_string($arg_str);
	}

	function execute($arg_sql, $committed = CONST_YES)
	{
		mssql_select_db($this->db_name, $this->db_link);
// 			if ($this->connection_encoding)
// 				mysql_query('SET NAMES ' . $this->connection_encoding, $this->db_link);
// 			$this->db_error_no = mysql_errno($this->db_link);
// 			if ($this->db_error_no == 0)
		{
			if (strtoupper(substr($arg_sql, 0, 6)) == 'INSERT')
			{
				$arg_sql = $arg_sql . 'SELECT LAST_INSERT_ID=@@IDENTITY';
			}
			$result = mssql_query($arg_sql, $this->db_link);
			if ($result === false)
				$this->db_error_no = 1;			// no error code for mssql in php
			if ($this->db_error_no == 0)
			{
//				$this->insert_id = mysql_insert_id($this->db_link);
				switch (strtoupper(substr($arg_sql, 0, 6)))
				{
					case 'SELECT':
						$this->result_count = mssql_num_rows($result);
						break;
					case 'INSERT':
						$r = mssql_fetch_assoc($result);
						$this->insert_id = $r['LAST_INSERT_ID'];
						break;
					default:
						$this->result_count = mssql_rows_affected($this->db_link);
						break;
				}
			}
			else
			{
				error_log('DataStore Error: ' . $this->db_error_no . ' = ' . mssql_get_last_message($this->db_link));
				error_log($arg_opt_str);
				$this->result_count = 0;
			}
		}
		return $result;
	}

	function fetchArray($arg_result)
	{
		if (!in_array($this->fetch_type, array(MSSQL_ASSOC, MSSQL_NUM, MSSQL_BOTH)))
			$this->fetch_type = MSSQL_ASSOC;
		return mssql_fetch_array($arg_result, $this->fetch_type);
	}

	function fetchObject($arg_result)
	{
		return mssql_fetch_object($arg_result);
	}

	// extend these function in the future

	function fetchAll($arg_result)
	{
		$output = array();
		while ($line = $this->fetchArray($arg_result))
			$output[] = $line;
		return $output;
	}

}

function mssql_escape_string($str)
{
	return str_replace(array('"', "'"), array('""', "''"), $str);
	// need to escape for % and _ in where clause too
}
?>