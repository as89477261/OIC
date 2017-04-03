<?
require_once('CDataStore.php');

class CDataStoreMySql extends CDataStore
{
	var $fetch_type = MYSQL_ASSOC;
	var $count_key = 'Count';

	// static function
	function connectDatabase($db_host, $db_user, $db_password, $db_name = '', $db_encoding = '')
	{
		$db_link = mysql_connect($db_host, $db_user, $db_password);
		$this->db_error_no = mysql_errno();
		if ($this->db_error_no != 0)
			return false;
		return $db_link;
	}

	// static function
	function disconnectDatabase(&$db_link)
	{
		if (is_resource($db_link))
			return mysql_close($db_link);
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
		$sql .= ' LIMIT ';
		if ($this->limit_offset > 0)
			$sql .= ($this->limit_offset . ',' . $this->limit_rows);
		else
			$sql .= $this->limit_rows;
	}

	function generateSqlInsertMultipleRows($column_list, $values)
	{
		$column_list = implode(',', $column_list);
		$values_str = implode('),(', $values);
		return 'INSERT INTO ' . $this->db_table . ' (' . $column_list . ') VALUES (' . $values_str . ')';
	}

	function escapeString($arg_str)
	{
		return mysql_escape_string($arg_str);
	}

	function execute($arg_sql, $committed = CONST_YES)
	{
		mysql_select_db($this->db_name, $this->db_link);
		if ($this->connection_encoding)
			mysql_query('SET NAMES ' . $this->connection_encoding, $this->db_link);
		$this->db_error_no = mysql_errno($this->db_link);
		if ($this->db_error_no == 0)
		{
			$result = mysql_query($arg_sql, $this->db_link);
			$this->db_error_no = mysql_errno($this->db_link);
			if ($this->db_error_no == 0)
			{
				$this->insert_id = mysql_insert_id($this->db_link);
				if (strtoupper(substr($arg_sql, 0, 6)) == 'SELECT')
					$this->result_count = mysql_num_rows($result);
				else
					$this->result_count = mysql_affected_rows($this->db_link);
			}
			else
			{
				error_log('DataStore Error: ' . $this->db_error_no . ' = ' . mysql_error($this->db_link));
				error_log($arg_opt_str);
				$this->result_count = 0;
			}
		}
		return $result;
	}

	function fetchArray($arg_result)
	{
		if (!in_array($this->fetch_type, array(MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH)))
			$this->fetch_type = MYSQL_ASSOC;
		return mysql_fetch_array($arg_result, $this->fetch_type);
	}

	function fetchObject($arg_result)
	{
		return mysql_fetch_object($arg_result);
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
?>
