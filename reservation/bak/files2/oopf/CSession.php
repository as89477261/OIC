<?

// this session wrapper does not support global variable, all session value must be in $_SESSION

define('PEZ_SESSION_NOERROR', 0);
define('PEZ_SESSION_COOKIENOTFOUND', 1);
define('PEZ_SESSION_NOTFOUND', 2);
define('PEZ_SESSION_EXPIRED', 3);
define('PEZ_SESSION_CLOSED', 4);
define('PEZ_SESSION_INTERNALERROR', 99);

class CSession
{

	var $cookie_name = 'PEZSESSID';
	var $cookie_path;
	var $cookie_domain;
	var $mode = 'cookie';
	var $cache_limiter = 'nocache';
	var $module = 'files';
	var $lifetime = 0;
	var $set_cookie_lifetime = false;
	var $session_handler;
	var $cache_expire = 180;
	var $error_no = 0;
	var $active = false;
//	var $sid;

	function CSession($name = '')
	{
		$this->name($name);
	}

	function found()
	{
 		return (isset($_COOKIE[$this->cookie_name]) && $_COOKIE[$this->cookie_name]);
	}

	function unsetCookie()
	{
		unset($_COOKIE[$this->cookie_name]);
		$ckp = session_get_cookie_params();
		setcookie($this->name(), '', 0, $ckp['path'], $ckp['domain']);
	}

	function start()
	{
		if ($this->active)
			return true;
		$this->setParams();
		$this->setHandler($this->session_handler);
		$this->putHeaders();
		$result = session_start();
//		$this->sid = session_id();	   // to customize session id generating, call it before starting session
		if ($result)
			$this->active = true;

		return $result;
	}

	function resume()
	{
		if (is_array($_COOKIE) && array_key_exists($this->cookie_name, $_COOKIE))
		{
			$sid = $_COOKIE[$this->cookie_name];
			if ($this->module != 'user')
				$this->error_no = PEZ_SESSION_NOERROR;
			elseif (is_object($this->session_handler) && method_exists($this->session_handler, 'validate'))
				$this->error_no = $this->session_handler->validate($sid);
			else
				$this->error_no = PEZ_SESSION_INTERNALERROR;
		}
		else
			$this->error_no = PEZ_SESSION_COOKIENOTFOUND;

		if ($this->error_no == PEZ_SESSION_NOERROR)
		{
			if ($this->start())
				$this->error_no = PEZ_SESSION_NOERROR;
			else
				$this->error_no = PEZ_SESSION_INTERNALERROR;
		}

		return $this->error_no;
	}

	function error($errno = '')
	{
		if (!$errno)
			$errno = $this->error_no;
		switch (intval($errno))
		{
			case PEZ_SESSION_NOERROR: $errtxt = ''; break;
			case PEZ_SESSION_COOKIENOTFOUND: $errtxt = 'Session cookie not found'; break;
			case PEZ_SESSION_NOTFOUND: $errtxt = 'Session information not exists'; break;
			case PEZ_SESSION_EXPIRED: $errtxt = 'Session is expired'; break;
			case PEZ_SESSION_CLOSED: $errtxt = 'Session is closed'; break;
			default: $errtxt = 'Unknown session error number' . intval($errno); break;
		}
		return $errtxt;
	}

	function name($name = '')
	{
		if ($name = (string)$name)
		{
			$this->cookie_name = $name;
			$result = session_name($name);
		}
		else
		{
			$this->cookie_name = $result = session_name();
		}
		return $result;
	}

	// set session.cache_limiter corresponding to $this->allowcache. ****
	function putHeaders()
	{
		switch ($this->cache_limiter)
		{
			case 'passive':
			case 'public':
				session_cache_limiter('public');
				break;
			case 'private':
				session_cache_limiter('private');
//				header('Expires: ' . gmdate('D, d M Y H:i:s T', makeTimeDateTime(today(), 0, $this->cache_expire * 1)));
//				session_cache_expire($this->cache_expire);
				break;
			case 'private_no_expire':
				session_cache_limiter('private_no_expire');
				break;
			default:
				session_cache_limiter('nocache');
				break;
		}
	}

	function setHandler(&$sess_handler)
	{
		if (is_object($sess_handler))
		{
			$this->module = 'user';
			$sess_handler->lifetime = (($this->lifetime > 0) ? $this->lifetime : 0);
		}
		switch ($this->module)
		{
			case 'user' :
				session_module_name('user');
				// set custom session handlers
				session_set_save_handler(
					array(&$sess_handler, 'open'),
					array(&$sess_handler, 'close'),
					array(&$sess_handler, 'read'),
					array(&$sess_handler, 'write'),
					array(&$sess_handler, 'destroy'),
					array(&$sess_handler, 'gc')
				);
				break;
			case 'mm':
				session_module_name('mm');
				break;
			case 'files':
			default:
				if (@$this->save_path)
					session_save_path($this->save_path);
				session_module_name('files');
				break;
		}
	}

	function setParams()
	{
		session_name($this->cookie_name);

		if (!$this->cookie_domain)
			$this->cookie_domain = get_cfg_var('session.cookie_domain');

		if (!$this->cookie_path)
		{
			if (get_cfg_var('session.cookie_path'))
				$this->cookie_path = get_cfg_var('session.cookie_path');
			else
				$this->cookie_path = '/';
		}

		if (($this->set_cookie_lifetime) && ($this->lifetime > 0))
			$lifetime = $this->lifetime; // time() + $this->lifetime;
		else
			$lifetime = 0;

		session_set_cookie_params($lifetime, $this->cookie_path, $this->cookie_domain);
	}

	function getId()
	{
		return session_id();
	}

	function destroy()
	{
		$this->unsetAll();
		clearstatcache();
		$this->unsetCookie();
//		$ckp = session_get_cookie_params();
//		setcookie($this->name(), '', 0, $ckp['path'], $ckp['domain']);
		if ($this->active)
			session_destroy();
	}

	function setValue($name, $value)
	{
		$_SESSION[$name] = $value;
	}

	function getValue($name)
	{
		if (array_key_exists($name, $_SESSION))
			return $_SESSION[$name];
		else
			return null;
	}

	// note that this function return reference
	function &getSessionData()
	{
		return $_SESSION;
	}

	function setValues($input)
	{
		if (is_array($input))
		{
			foreach ($input as $name => $value)
				$this->setValue($name, $value);
		}
		else
			return false;
	}

	function unsetValue($name)
	{
		unset($_SESSION[$name]);
	}

	function unsetAll()
	{
		session_unset();
	}

}

?>