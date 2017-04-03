<?php 
class Timer
{
	var $time_s, $time_c = 0.0;
	var $max = 100;			// set max diff (in second) for the timer. this value is to subtract from microtime read from system, to improve precision of usec part.

	function start()
	{
		$this->time_s = $this->utime();
	}

	function pause($p_interval = false)
	{
		$now = $this->utime();
		$this->time_c += ($now - $this->time_s);			// accumulative counter and reset starter
		if ($p_interval) echo $this->time_c ."\n";
		$this->time_s = 0.0;
	}

	function resume()
	{
		$this->time_s = $this->utime();
	}

	function stop($p_interval = false)
	{
		$now = $this->utime();
		$this->time_c += ($now - $this->time_s);			// accumulative counter and reset starter
		if ($p_interval) echo $this->time_c ."\n";
		$this->time_s = $this->time_c = 0.0;
	}

	function utime()
	{
	   list($usec, $sec) = explode(" ", microtime());
	   $sec %= $this->max;
	   return ((float)$usec + (float)$sec);
	}
}

?>