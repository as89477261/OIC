<?

// define('OOPF_SKIP_PERMISSION', true);
define('APP_INI', 'app_reservation.ini');
require_once('../../reservation/oopf_main.php');

if (! array_key_exists(FN_ID, $_REQUEST))
    $fid = 'cc_main';
else
    $fid = $_REQUEST[FN_ID];

oopfProcess($fid, false);
// if (array_key_exists('pid', $_REQUEST))
//     oopfProcess($fid, true, true, 'private');
// else
//     oopfProcess($fid, true);

?>
