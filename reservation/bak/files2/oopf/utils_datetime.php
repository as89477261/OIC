<?

// mktime from date in framework's date format
function makeTimeDate($arg_date, $arg_days = 0, $arg_months = 0, $arg_years = 0)
{
    if ($arg_date == '')
        return 0;
    list($year, $month, $day) = explode('-', $arg_date);
    return mktime(0, 0, 0, $month + $arg_months, $day + $arg_days, $year + $arg_years);
}

// mktime from date in framework's date-time format
function makeTimeDateTime($arg_datetime, $arg_days = 0, $arg_secs = 0)
{
    if ($arg_datetime == '')
        return 0;
    list($date, $time) = explode(' ', $arg_datetime);
    list($year, $month, $day) = explode('-', $date);
    list($hour, $min, $sec) = explode(':',$time);
    return mktime($hour, $min, $sec + $arg_secs, $month, $day + $arg_days, $year);
}

function relativeDate($arg_date, $num_days)
{
    if ($arg_date == '')
        return '';
    return date(PEZ_FORMAT_DATE, makeTimeDate($arg_date, $num_days));
}

function relativeMonth($arg_date, $num_months)
{
    if ($arg_date == '')
        return '';
    return date(PEZ_FORMAT_DATE, makeTimeDate($arg_date, 0, $num_months, 0));
}

function relativeYear($arg_date, $num_years)
{
    if ($arg_date == '')
        return '';
    return date(PEZ_FORMAT_DATE, makeTimeDate($arg_date, 0, 0, $num_years));
}

function relativeDateTime($arg_datetime, $num_days, $arg_secs = 0)
{
    if ($arg_datetime == '')
        return '';
    return date(PEZ_FORMAT_DATETIME, makeTimeDateTime($arg_datetime, $num_days, $arg_secs));
}


function getYearMonthFromDate($arg_date)
{
    return date(PEZ_FORMAT_YEARMONTH, makeTimeDate($arg_date));
}

function getYearFromDate($arg_date)
{
    if ($arg_date == '')
        return '';
    list($year) = explode('-', $arg_date);
    return $year;
}

function getDayFromDate($arg_date)
{
    if ($arg_date == '')
        return '';
    $d = date('w', makeTimeDate($arg_date));
    return ($d > 0 ? $d : 7);
}

function makeDate($d, $m, $y)
{
    if ($d == '')
        $d = daysOfMonth($m, $y);
    return str_pad($y, 4, '0', STR_PAD_LEFT) . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) . '-' . str_pad($d, 2, '0', STR_PAD_LEFT);
}

function today()
{
    return date(PEZ_FORMAT_DATE);
}

function getCurrentDateTime()
{
    return date(PEZ_FORMAT_DATETIME);
}

function getCurrentYear()
{
    return date('Y');
}

function getDateFromDateTime($arg_datetime)
{
    return date(PEZ_FORMAT_DATE, makeTimeDateTime($arg_datetime));
}

function daysAfter($arg_from_date, $arg_to_date)
{
    $time_stamp1 = makeTimeDate($arg_from_date);
    $time_stamp2 = makeTimeDate($arg_to_date);
    return round(($time_stamp2 - $time_stamp1) / 86400);
}

function secondsAfter($arg_from_datetime, $arg_to_datetime)
{
    $time_stamp1 = makeTimeDateTime($arg_from_datetime);
    $time_stamp2 = makeTimeDateTime($arg_to_datetime);
    return ($time_stamp2 - $time_stamp1);
}

function daysAfterToday($arg_date)
{
    return daysAfter($arg_date, today());
}

function yearsAfter($arg_from_date, $arg_to_date)
{
    return intval(daysAfter($arg_from_date, $arg_to_date) / 365);
}

function yearsAfterToday($arg_from_date)
{
    return intval(daysAfter($arg_from_date, today()) / 365);
}

function daysOfMonth($m, $y)
{
    $eom = array(0,31,28,31,30,31,30,31,31,30,31,30,31);
    $m = intval($m);
    if (($m < 1) && ($m > 12))
        return false;
//    $leapyear = ((($y % 4) == 0) && ((($y % 100) != 0) || (($y % 400) == 0)));
    if (($m == 2) && isLeapYear($y))
        return 29;
    else
        return $eom[$m];

//    system function has too much limit. although the following line is faster, it will not be used.
//    return intval(date("t", makeTimeDate($y."-".$m."-01")));
}

function isLeapYear($y)
{
    return ((($y % 4) == 0) && ((($y % 100) != 0) || (($y % 400) == 0)));
}

// Thai Date Extension
function convertThaiDate($thai_date)
{
    if ($thai_date == '')
        return '';
    list($y, $m, $d) = explode('-', $thai_date);
    return (intval($y) - 543) . '-' . $m . '-' . $d;
}

function convertAllThaiDate(&$arg_input, $date_keys)
{
    foreach ($date_keys as $key)
        if (array_key_exists($key, $arg_input))
            $arg_input[$key] = convertThaiDate($arg_input[$key]);
}

function convertToThaiDate($arg_date)
{
    if ($arg_date == '')
        return '';
    list($y, $m, $d) = explode('-', $arg_date);
    return ($y + 543) . '-' . $m . '-' . $d;
}

?>
