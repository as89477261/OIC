<?

define('LEADING_SKIPPED_CHAR', 'เแโใไ“”\'\\"');
// should rewrite the below functions to support easier by auto-mbstring functions

class Alphabetizer
{
	function __construct()
	{
		$this->all_ranges = array();
		$this->all_ranges[1] = array(array('A', 'Z'), array('a', 'z'), array('0', '9'));		// for 1 byte comparison
		$this->all_ranges[3] = array(array('ก', 'ฮ'), array('๐', '๙'));		// for 3 bytes comparison
	}
	// alphabetize in UTF-8 mode. now support En, Th
	function getAlphabetizedChar($word)
	{
		$i = 0; $len = mb_strlen($word);
		while ($i < $len)
		{
			$char = mb_substr($word, $i, 1);
			$ranges =& $this->all_ranges[strlen($char)];
			if (is_array($ranges))
				foreach ($ranges as $range)
				{
					list($min, $max) = $range;
					if ((strcmp($char, $min) >= 0) && (strcmp($char, $max) <= 0))
						return strtoupper($char);
				}
			$i++;
		}
		return false;
	}
}

function strbegin($str, $needle)
{
	return (strpos($str, $needle) === 0);
}

function mb_strbegin($str, $needle)
{
	return (mb_strpos($str, $needle) === 0);
}

function stribegin($str, $needle)
{
	return (stripos($str, $needle) === 0);
}

function strend($str, $needle)
{
	$pos = strlen($str) - strlen($needle);
	// $neelde is longer than $str, return false.
	return ($pos >= 0) || (strrpos($str, $needle) === $pos);
}

function mb_strend($str, $needle)
{
	$pos = mb_strlen($str) - mb_strlen($needle);
	// $neelde is longer than $str, return false.
	return ($pos >= 0) || (mb_strrpos($str, $needle) === $pos);
}

function striend($str, $needle)
{
	$pos = strlen($str) - strlen($needle);
	// $neelde is longer than $str, return false.
	return ($pos >= 0) || (strripos($str, $needle) === $pos);
}

function strofchar($str, $char)
{
	return preg_match('/^' . $char . '|^[' . LEADING_SKIPPED_CHAR . ']+' . $char .'/i', $str);
}

function mb_strofchar($str, $char)
{
	return mb_ereg_match('/^' . $char . '|^[' . LEADING_SKIPPED_CHAR . ']+' . $char .'/i', $str);
}

function generatePrefixedConnectingNumber($prefixed_nos, $separator = '/')
{
	$prefixed_nos = array_unique($prefixed_nos);
	foreach ($prefixed_nos as $pno)
	{
		list($prefix, $no) = explode($separator, $pno);
		$prefixeds[$prefix][] = $no;
	}

	foreach ($prefixeds as $prefix => &$nos)
	{
		$cnos = generateConnectingNumber($nos);
		foreach ($cnos as $cno)
			$result[] = $prefix . $separator . $cno;
	}

	return $result;
}

// the number in the list must not contains alphabet
function generateConnectingNumber($nos)
{
	$nos = array_unique($nos);
	sort($nos);

	$end = $start = array_shift($nos);
	foreach ($nos as $no)
	{
		if ($no == ($end + 1))
			$end = $no;
		else
		{
			$set[] = ($end == $start ? $end : connectNumber($start, $end));
			$end = $start = $no;
		}
	}
	$set[] = ($end == $start ? $end : connectNumber($start, $end));
	return $set;
}

// find the position of different character and split to connect
function connectNumber($start, $end, $connector = ' - ')
{
	$i = 0;
	$len = min(strlen($start), strlen($end));
	for ($i = 0; $i < $len; $i++)
	{
		if ($start{$i} != $end{$i})
			break;
	}
	return ($start . $connector . substr($end, $i));
}

?>