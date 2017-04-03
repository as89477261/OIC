<?

require_once('CDataStoreOracle.php');
require_once('CSession.php');		// for constants defined

class CSessionHandlerDataStore extends CDataStoreOracle
{
	var $lifetime;
	var $garbage_lifetime = 5184000;		// max time, in seconds, for garbage collector (default 60 days)

	function open()
	{
		if (is_resource($this->db_link))
			return true;
		else
			return false;
	}

	function close()
	{
		if (!is_resource($this->db_link))
			return true;
		else
			return $this->disconnectDatabase($this->db_link);
	}

	function validate($sid)
	{
		list($sess_info) = $this->getData(array('SessionId' => $sid));
		$ip = $sess_info['IPAddress'];
		$ips = $this->getIpAddresses();

		if (! in_array($ip, $ips))
			return PEZ_SESSION_NOTFOUND;
		if ($this->result_count > 0)
		{
			if ($sess_info['EndedTime'])
				return PEZ_SESSION_CLOSED;
			if ($sess_info['Lifetime'] != 0)
			{
				$expiry_time = relativeDateTime($sess_info['LastChanged'], 0, $sess_info['Lifetime']);
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
		$sess_info = $this->getData(array('SessionId' => $sid, 'IPAddress' => $ip, 'EndedTime' => null));	   // *** check ended time by itself rather than by db
		if ($this->result_count > 0)
		{
			// *** check expiry and return error?
			$sess_info = array_shift($sess_info);
			$data = $sess_info['Information'];
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
		$where = array('SessionId' => $sid, 'IPAddress' => $ip);
		$current_time = getCurrentDateTime();
		$sess_info = $this->getData($where);
		// lifetime cannot be changed after cookie is created
		if ($this->result_count > 0)
			$this->update(array('Information' => $data, 'LastChanged' => $current_time), $where);
		else
			$this->insert(array('SessionId' => $sid, 'IPAddress' => $ip, 'InsertedTime' => $current_time, 'LastChanged' => $current_time, 'Lifetime' => $this->lifetime, 'Information' => $data), $dummy);
		return true;
	}

	function destroy($sid)
	{
		$current_time = getCurrentDateTime();
		$sess_info = $this->getData(array('SessionId' => $sid));
		if ($this->result_count > 0)
			return $this->update(array('EndedTime' => $current_time), array('SessionId' => $sid));
		else
			return false;
	}

	function gc($lifetime_max = 0)
	{
		// remove any rows which has been expired for a long time, with or without destroy
		if ($this->garbage_lifetime > 0)
			$lifetime_max = $this->garbage_lifetime;
		$max_datetime = relativeDateTime(getCurrentDateTime(), 0, -$lifetime_max);
		$this->delete(array('LastChanged <=' => $max_datetime));
		return true;
	}

}

?>