<?php 
require_once('CTemplateFunction.php');

class CTemplateFunctionThaiDate extends CTemplateFunction
{
    var $format = array(
                      'SYSTEM' => 'Y-m-d',
                      'BRIEF' => 'd/m/Y',
                      'SHORT' => 'd M Y',
                      'YEAR' => 'Y',
                      'SHORT_NB' => 'd\&\n\b\s\p\;M\&\n\b\s\p\;Y',
                      'LONG' => 'd F Y',
                      'NO_YEAR' => 'd M',
                      'MONTH_YEAR' => 'M Y',
                      'LONG_MONTH_YEAR' => 'F Y',
                      'SHORT_MONTH' => 'M',
//						'LONG_NB' => 'F\&\n\b\s\p\;jS,\&\n\b\s\p\;Y',
						'DATETIME_SHORT' => 'd M Y H:i',
//						'SHORTDATE_FULLTIME' => 'd M Y H:i:s',
//						'FULLDATE' => 'D M d h:i:s Y T'
                      'FULL_MONTH' => 'F'
                  );

    var $month_full = array('', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                                'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
    var $month_short = array('', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');

    var $default = 'SYSTEM';			// default format is used for calendar input, not for presentation

    function setDateFormat($f_name, $f)
    {
        $this->format[$f_name] = $f;
    }

    function evaluate(&$data, $name, $param)
    {
	    $param = explode(',', $param);
	    $val_def = (count($param) > 1 ? $param[1] : '');
    	$param = ($param[0] ? $param[0] : $this->default);

        if ($name == '')
            $val = ($val_def == '' ? date('Y-m-d H:i:s') : $val_def);
        else
            $val = $this->parser->getValue($data, $name);

	    if (strpos($val, ' ') === false)
	    {
	    	$date = $val;
	    	$time = '00:00:00';
    	}
    	else
		    list($date, $time) = split(' ', $val);
        if ($date == '0000-00-00') return '';

        if (is_numeric($date) && in_array($param, array('SHORT_MONTH', 'FULL_MONTH')))
	        switch ($param)
	        {
		        case 'SHORT_MONTH': return $this->month_short[intval($date)];
		        case 'FULL_MONTH': return $this->month_full[intval($date)];
	        }
//         elseif (preg_match('/(^\d{4}\-\d{1,2}\-\d{1,2}$)/', $val))
       elseif ((preg_match('/(^\d{4}\-\d{1,2}\-\d{1,2}$)/', $val)) ||
           (preg_match('/(^\d{4}\-\d{1,2}\-\d{1,2}\ \d{1,2}:\d{1,2}:\d{1,2}$)/', $val)))
        {
            list($param) = split(',', $param);
            if (!$param) $param = 'SYSTEM';
            $replace = $this->compile($date, $time);
            return str_replace(array_keys($replace), array_values($replace), $this->format[$param]);
        }
        else
            return $val;
    }

    function compile($date, $time)
    {
        list($y, $m, $d) = split('-', $date);
        $y += 543;

        $time = split(':', $time);
        switch (count($time))
        {
	        case 1: $hour = array_shift($time); $min = $sec = 0; break;
	        case 2: list($hour, $min) = $time; $sec = 0; break;
	        case 3: list($hour, $min, $sec) = $time; break;
    	}

        $replace_list = array(
                               'd' => $d, 'j' => strval(intval($d)),
                               'm' => $m, 'n' => strval(intval($m)),
                               'M' => $this->month_short[intval($m)],
                               'F' => $this->month_full[intval($m)],
                               'Y' => $y, 'y' => strval(intval($y) % 100),
                               'g' => $hour % 12,
                               'G' => $hour,
                               'h' => str_pad($hour % 12, 2, '0'),
                               'H' => str_pad($hour, 2, '0'),
                               'i' => str_pad($min, 2, '0'),
                               's' => str_pad($sec, 2, '0')
                               );
        return $replace_list;
    }

}

class CTemplateFunctionThaiNumberText extends CTemplateFunction
{

    function evaluate(&$data, $name, $param)
    {
        $val = $this->parser->getValue($data, $name);
        return $this->spellThaiNumber($val);
    }

    function spellThaiNumber($amount)
    {
        $num_map = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
        $digit_map = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');

        $txt = '';

        if ($amount < 0)                        // support for negative
        {
            $neg = true;
            $amount *= -1;
        }
        else
            $neg = false;

        $int = floor($amount);                  // no decimal support
        $amtlen = strlen($int);
        $s = 0; $e = $amtlen % 6;               // recurse million
        do
        {
            if ($txt) $txt .= $digit_map[6];
            $amt = substr($int, $s, $e);
            $len = strlen($amt) - 1;
            for ($i = 0; $i <= $len; $i++)
            {
                $a = $amt[$i];
                $d = $len - $i;
                if ($a == 0)
                {
                    if ($len == 0)
                        return $num_map[$a];
                    else
                        continue;
                }
                if (($d == 0) && ($a == 1) && ($len != 0))
                    $ntxt = 'เอ็ด';
                elseif (($d == 1) && ($a == 2))
                    $ntxt = 'ยี่';
                elseif (($a == 1) && ($d == 1))
                    $ntxt = '';
                else
                    $ntxt = $num_map[$a];
                $txt .= $ntxt . $digit_map[$d];
            }
            $s = $e; $e += 6;
        }
        while ($s != $amtlen);

        return ($neg ? ('ลบ' . $txt) : $txt);
    }

}

class CTemplateFunctionThaiBaht extends CTemplateFunctionThaiNumberText
{
    function evaluate(&$data, $name, $param)
    {
        $amount = $this->parser->getValue($data, $name);
        $amount = abs($amount);                         // negative value will be removed
        $int = floor($amount);
        $dec = (int) round(($amount - $int) * 100);     // decimal will be rounded up to 2 digit
        $txt = $this->spellThaiNumber($int) . 'บาท' . (($dec == 0) ? 'ถ้วน' : $this->spellThaiNumber($dec) . 'สตางค์');
        return $txt;
    }
}

?>