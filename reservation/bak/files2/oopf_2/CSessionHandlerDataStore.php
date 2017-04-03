<?php 
require_once('CDataStore.php');
require_once('CSession.php');		// for constants defined

define('SESSCOLNAME_ID', 'session_id');
define('SESSCOLNAME_IP_ADDR', 'ip_address');
define('SESSCOLNAME_INSERTED_TIME', 'inserted_time');
define('SESSCOLNAME_LIFETIME', 'lifetime');
define('SESSCOLNAME_LAST_CHANGED', 'last_changed');
define('SESSCOLNAME_ENDED_TIME', 'ended_time');
define('SESSCOLNAME_INFO', 'information');

class CSessionHandlerDataStore
{
	var $lifetime;
	var $garbage_lifetime = 8640000;		// max time, in seconds, for garbage collector (default 100 days)

	var $dataStore;

	function  __construct(&$pDataStore)
	{
		$this->dataStore = $pDataStore;
		$this->dataStore->datetime_column_list = array('inserted_time', 'last_changed', 'ended_time');
	}

	function open()
	{
		if (is_resource($this->dataStore->db_link))
			return true;
		else
			return false;
	}

	function close()
	{
		if (!is_resource($this->dataStore->db_link))
			return true;
		else
			return $this->dataStore->disconnectDatabase($this->dataStore->db_link);
	}

	function validate($sid)
	{
		list($sess_info) = $this->dataStore->getData(array(SESSCOLNAME_ID => $sid));
		$ip = $sess_info[SESSCOLNAME_IP_ADDR];
		$ips = $this->getIpAddresses();

		if (! in_array($ip, $ips))
			return PEZ_SESSION_NOTFOUND;
		if ($this->dataStore->result_count > 0)
		{
			if ($sess_info[SESSCOLNAME_ENDED_TIME])
				return PEZ_SESSION_CLOSED;
			if ($sess_info[SESSCOLNAME_LIFETIME] != 0)
			{
				$expiry_time = relativeDateTime($sess_info[SESSCOLNAME_LAST_CHANGED], 0, $sess_info[SESSCOLNAME_LIFETIME]);
				$remaining_time = secondsAfter(getCurrentDateTime(), $expiry_time);
				if ($remaining_time < 0)
					return PEZ_SESSION_EXPIRED;
				else
					return PEZ_SESSION_NOERROR;
			}
			else
				return PEZ_SESSION_NOERROR;
		}
		else
			return PEZ_SESSION_NOTFOUND;
	}

	function getIpAddresses()
	{
		if (isset($_SERVER))
		{
			if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'])
				$ips = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
			elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP'])
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

	// for transparent proxy of some ISP which separate path of request between one with and without form input
	function getEffectiveIpAddress()
	{
		list($ip) = $this->getIpAddresses();
		return $ip;
	}

	function read($sid)
	{
		$ip = $this->getEffectiveIpAddress();
		$sess_info = $this->dataStore->getData(array(SESSCOLNAME_ID => $sid, SESSCOLNAME_IP_ADDR => $ip, SESSCOLNAME_ENDED_TIME => null));	   // *** check ended time by itself rather than by db
		if ($this->dataStore->result_count > 0)
		{
			// *** check expiry and return error?
			$sess_info = array_shift($sess_info);
			$data = $sess_info[SESSCOLNAME_INFO];
		}
		else
			$data = '';
		if (!is_scalar($data))
			$data = '';
		return strval($data);			   // must always be string
	}

	function write($sid, $data)
	{
		$ip = $this->getEffectiveIpAddress();
		$where = array(SESSCOLNAME_ID => $sid, SESSCOLNAME_IP_ADDR => $ip);
		$current_time = getCurrentDateTime();
		$sess_info = $this->dataStore->getData($where);

		// lifetime cannot be changed after cookie is created
		if ($this->dataStore->result_count > 0)
			$this->dataStore->update(array(SESSCOLNAME_INFO => $data, SESSCOLNAME_LAST_CHANGED => $current_time), $where);
		else
			$this->dataStore->insert(array(SESSCOLNAME_ID => $sid, SESSCOLNAME_IP_ADDR => $ip, SESSCOLNAME_INSERTED_TIME => $current_time, SESSCOLNAME_LAST_CHANGED => $current_time, SESSCOLNAME_LIFETIME => $this->lifetime, SESSCOLNAME_INFO => $data), $dummy);
		return true;
	}

	function destroy($sid)
	{
		$current_time = getCurrentDateTime();
		$sess_info = $this->dataStore->getData(array(SESSCOLNAME_ID => $sid));
		if ($this->dataStore->result_count > 0)
			return $this->dataStore->update(array(SESSCOLNAME_ENDED_TIME => $current_time), array(SESSCOLNAME_ID => $sid));
		else
			return false;
	}

	function gc($lifetime_max = 0)
	{
		// remove any rows which has been expired for a long time, with or without destroy
		if ($this->garbage_lifetime > 0)
			$lifetime_max = $this->garbage_lifetime;
		$max_datetime = relativeDateTime(getCurrentDateTime(), 0, -$lifetime_max);
		$this->delete(array(SESSCOLNAME_LAST_CHANGED . ' <=' => $max_datetime));
		return true;
	}

}

?>