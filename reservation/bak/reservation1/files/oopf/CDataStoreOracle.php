<?
require_once('CDataStore.php');

// to-do: get insert id back for insert statement

class CDataStoreOracle extends CDataStore
{
	var $fetch_type = OCI_ASSOC;
	var $prefetch = 0;		// use default prefetch
	var $column_case = 0;	// default is all uppercase (depending on OCI or other components), set to 1 to convert to all lowercase (can change to 0, 1, -1 to support specified casing)
	var $string_column_list = false;		// distinguish string columns to quote them in SQL
	var $count_key = 'COUNT';

	// static function
	function connectDatabase($db_host, $db_user, $db_password, $db_name = '', $db_encoding = '')
	{
		if ($db_encoding)
			$db_link = oci_connect($db_user, $db_password, 'localhost/' . $db_name, $db_encoding);
		else
			$db_link = oci_connect($db_user, $db_password, 'localhost/' . $db_name);

		$error = oci_error();
		$this->db_error_no = (is_array($error) ? $error['code'] : 0);
		if ($this->db_error_no != 0)
			return false;
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

	function quoteValue($real_col_name, $value)
	{
		if (is_array($this->string_column_list) && in_array($real_col_name, $this->string_column_list))
			return "'" . $value . "'";
		else
			return $value;
	}

	function quoteValues($real_col_name, $values)
	{
		if (is_array($this->string_column_list) && in_array($real_col_name, $this->string_column_list))
			return "'" . implode("','", $values) . "'";
		else
			return implode(',', $values);
	}

	function mergeSqlLimit(&$sql)
	{
		if ($this->limit_offset > 0)
			$limit_where = ('OOPF_ROW_NO >= ' . $this->limit_offset . ' AND ');
		else
			$limit_where = '';
		$limit_where .= ('OOPF_ROW_NO <= ' . ($this->limit_offset + $this->limit_rows));
		$sql = 'SELECT * FROM (SELECT OopfToLimit.*, ROWNUM OOPF_ROW_NO FROM (' . $sql . ') OopfToLimit) WHERE ' . $limit_where;
	}

	function generateSqlInsertMultipleRows($column_list, $values)
	{
		$column_list = implode(',', $column_list);
		$values_str = 'SELECT ' . implode(' FROM DUAL UNION ALL SELECT ', $values) . ' FROM DUAL';
		return 'INSERT INTO ' . $this->db_table . ' (' . $column_list . ') ' . $values_str;
	}

	function escapeString($arg_str)
	{
		return oracle_escape_string($arg_str);
	}

	function execute($arg_sql, $committed = CONST_YES)
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
		{
			error_log('DataStore Error: ' . $this->db_error_no.' = ' . $error['message'] . ' at character ' . $error['offset'] . ' of "' . $error['sqltext'] . '".');
			error_log($arg_sql);
			$this->result_count = 0;
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

	function fetchArray($arg_result, $free_after_used = false)
	{
// 		if (!in_array($this->fetch_type, array(OCI_ASSOC, OCI_NUM, OCI_BOTH)))
// 			$this->fetch_type = OCI_ASSOC;
		$row = oci_fetch_array($arg_result, OCI_FETCHSTATEMENT_BY_ROW | $this->fetch_type);
		if (is_array($row))
			$this->result_count++;
		if ($this->column_case != 0)
			$row = array_change_key_case($row, CASE_LOWER);
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
		if ($this->column_case != 0)
			foreach ($output as &$row)
				$row = array_change_key_case($row, CASE_LOWER);
		return $output;
	}

}

function oracle_escape_string($str)
{
	return str_replace(array('"', "'"), array('""', "''"), $str);
	// need to escape for % and _ in where clause too
}
?>
