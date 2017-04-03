<?

// define('OOPF_SKIP_PERMISSION', true);
define('APP_INI', 'app_reservation.ini');
require_once('../../reservation/oopf_main.php');

date_default_timezone_set("Asia/Bangkok");

if (! array_key_exists(FN_ID, $_REQUEST))
    $fid = 'booking-ui';
else
    $fid = $_REQUEST[FN_ID];

oopfProcess($fid, false);
// if (array_key_exists('pid', $_REQUEST))
//     oopfProcess($fid, true, true, 'private');
// else
//     oopfProcess($fid, true);

?>
