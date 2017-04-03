<?

define('SUFFIX_ROWS', '_rows');
define('PATH_SPLITTER', oopfGetPathSplitter());
//header("Content-Type: text/html; charset=tis-620");

// merge app_xxx.ini filename into $app_ini before include this file
$cur_dir = dirname(__FILE__);
$ini = oopfReadAppConfig($cur_dir . '/app_config.ini');
if ($ini === false)
    oopfFatalError('System configuration file is disappeared.');
$site_ini = oopfReadAppConfig($cur_dir . '/site_config.ini');
if ($site_ini !== false)
    $ini = array_merge_recursive($ini, $site_ini);

// do ini_set()
if ($ini['Settings']['DebugMode'])
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
else
    ini_set('error_reporting', ~E_ALL);

foreach ($ini['PhpIniSet'] as $key => $value)
    ini_set($key, $value);

// build path info
$conf_path = $ini['PathInfo'];
if (trim($conf_path['HomeDir'] == ''))
    $conf_path['HomeDir'] = $cur_dir;
$tpl_dir = $conf_path['FilesDir'] . '/' . $conf_path['TemplateDir'] . '/';
if (trim($conf_path['AppHomeDir']) == '')
    $conf_path['AppHomeDir'] = $conf_path['HomeDir'];
$app_dir = $conf_path['AppHomeDir'] . $conf_path['AppDir'] . '/';
$site_dir = $conf_path['HomeDir'] . $conf_path['AppDir'] . '/';

// do ini_set(include_path)
$include_dir = explode(':', $conf_path['IncludeDirs']);        // fixed splitter for this framework
foreach ($include_dir as $dir)
    $include_path[] = $app_dir . $conf_path['FilesDir'] . '/' . $dir;
$include_path = implode(PATH_SPLITTER, $include_path);
ini_set('include_path', ini_get('include_path') . PATH_SPLITTER . $include_path);

require_once('oopf.php');

if (defined('APP_INI'))
{
    $app_ini = oopfReadAppConfig(APP_INI);
    if ($app_ini !== false)
        $ini = array_join_merge_recursive($ini, $app_ini);
    $conf_path = array_merge($conf_path, oopfBuildPathInfo($app_ini['PathInfo'], $tpl_dir));
}

$conf_path['FormDir'] = $app_dir . $conf_path['FilesDir'] . '/' . $conf_path['FormDir'] . '/';
$conf_path['StringDir'] = $site_dir . $conf_path['FilesDir'] . '/' . $conf_path['StringDir'] . '/';
$conf_path['TemplateDir'] = $site_dir . $tpl_dir;

$app_info['PathInfo'] = $conf_path;

// merge url
$urls = $ini['Urls'];
if (is_array($urls))
{
    foreach ($urls as $name => $filename)
        $urls[$name] = $conf_path['BaseHref'] . $filename;
}
else
    $urls = array();
// $urls['CurrentUrl'] = $conf_path['BaseHref'] . basename($_SERVER['PHP_SELF']);
$app_info['Urls'] = $urls;

$config_db = $ini['DbInfo'];
//$app_info['Settings'] = $ini['Settings'];
//$app_info['Preferences'] = $ini['Preferences'];

$app_info['Functions'] = oopfBuildFunctionList($ini['AppInterfaces']);
$ini['Session']['GuestPermission'] = oopfBuildGuestPermission($ini['Session']['GuestPermission']);

$app_info = copyArrayValue($ini, array('Settings', 'Preferences', 'MultiLanguage', 'Session', 'SystemError'), $app_info);

require_once('constants_tables.php');
require_once('constants_application.php');

$env = array(   'DsClass' => oopfGetDataStoreFactory($config_db, $app_info),
                'DbInfo' => &$config_db,
                'AppInfo' => &$app_info );

// this function is intened to be a factory but oopf now is not a class so this will return the class name rather than a factory object
// then an app logic will use that name to create a datastore object
function oopfGetDataStoreFactory(&$config_db, &$app_info)
{
	$ds_class_map = array('oracle' => 'CDataStoreOracle', 'mssql' => 'CDataStoreMsSql', 'mysql' => 'CDataStoreMySql');
	if ((! $config_db['DbEncoding']) && $app_info['MultiLanguage']['DbEncoding'])
		$config_db['DbEncoding'] = $app_info['MultiLanguage']['DbEncoding'];

	if (array_key_exists($config_db['DbType'], $ds_class_map))
		return $ds_class_map[$config_db['DbType']];
	else
	    oopfFatalError('Unknow Database Type: ' . $config_db['DbType']);       // show host, user, pwd too.
}

function oopfBuildGuestPermission($str)
{
    $fids = explode(',', $str);
    $result = array();
    foreach ($fids as $fid)
        $result[$fid] = CONST_YES;
    return $result;
}

// functions declaration

if (!function_exists('is_a')) {
   function is_a($class, $match)
   {
       if (empty($class)) {
           return false;
       }
       $class = is_object($class) ? get_class($class) : $class;
       if (strtolower($class) == strtolower($match)) {
           return true;
       }
       return is_a(get_parent_class($class), $match);
   }
}

function oopfBuildFunctionList($function_list)
{
    // map function and class
    if (is_array($function_list) && (count($function_list) > 0))
    {
        foreach ($function_list as $classname => $fidlist)
        {
            $fids = explode(',', $fidlist);
            foreach ($fids as $fid)
                $fmap[$fid] = $classname;
        }
    }
    else
        $fmap = array();
    return $fmap;
}

function oopfBuildPathInfo($conf_path, $tpl_dir)
{
    // BaseHref needs some modification
    if ($conf_path['BaseHrefTemplate'])
        $conf_path['BaseHref'] = $conf_path['RootHref'] . $conf_path['BasePath'] . '/' . $tpl_dir; // . $ini['MultiLanguage']['DefaultLang'] . '/';
    else
        $conf_path['BaseHref'] = $conf_path['RootHref'] . $conf_path['BasePath'] . '/';
    $conf_path['AppHref'] = $conf_path['RootHref'] . $conf_path['ScriptPath'] . '/';
    return $conf_path;
}

function oopfReadAppConfig($fullpath)
{
    if (file_exists($fullpath))
        return parse_ini_file($fullpath, true);
    else
        return false;
}

function oopfGetPathSplitter()
{
    if ((isset($_SERVER['SERVER_SOFTWARE']) &&
            ((stripos($_SERVER['SERVER_SOFTWARE'], 'win') !== false) ||
            (stripos($_SERVER['SERVER_SOFTWARE'], 'microsoft') !== false))
        ) ||
        (isset($_SERVER['OS']) && (stripos($_SERVER['OS'], 'win') !== false)))
        return ';';
    else
        return ':';
}

// process function
function oopfGetWebInterface($fid, &$env)
{
    $fmap =& $env['AppInfo']['Functions'];
    if (array_key_exists($fid, $fmap))
        $class = $fmap[$fid];
    else
        oopfFatalError('Function ' . $fid . ' not found');
    include_once($class . '.php');
    if (class_exists($class))
        $app = new $class($env);
    else
        oopfFatalError('Class ' . $class . ' not found');
    return $app;
}

function oopfProcess($fid, $session = false, $resumed = true, $cache_limiter = 'nocache')
{
    global $env;

    if (get_magic_quotes_gpc())
        $_REQUEST = cleanSlashes($_REQUEST);

    if (! $fid)
        oopfFatalError('Function id is not defined.');

    $app = oopfGetWebInterface($fid, $env);
    if (is_a($app, 'CApplicationLogic'))            // this block should be moved into CAppLogic
    {
        if ($session === true)
        {
            $result = $app->resumeSession($cache_limiter);
            if (! $result)
            {
                if ($resumed === true)
                {
                    $app->destroySession();
                    oopfProcessSystemError($app, $env['AppInfo']['SystemError']['NoSession']);
                }
                else
                {
                    $result = $app->startSession($cache_limiter);
                    if (!$result)
                        oopfAppError($app, 'Main/msgStartSessionFailed');
                }
            }

            if ($result)
                $session =& $app->session;
        }
        elseif (isset($session) && is_object($session))
            $app->setSession($session);             // this is duplicated with $env['Session'];, try resolving it.
        elseif ($session !== false)
            oopfFatalError('System configuration is incorrect.');

        $app->prepareMultiLanguageSupport();

        if (is_object($session) && $session->active)
            $permission = $app->getPermission();
        elseif ($session === false)               // if session = false, the front-end is openned to the function.
            $permission = $env['AppInfo']['Session']['GuestPermission'];
        else
            $permission = array();
        if (defined('OOPF_SKIP_PERMISSION') || ($permission[$fid] == CONST_YES))
        {
            $input =& $_REQUEST;
            $result = $app->process($fid, $input);
        }
        else
            oopfAppError($app, 'Main/msgNoPermission');
    }
    else
    {
        oopfFatalError('Class ' . get_class($app) . ' is not a CApplicationLogic');
    }
    flush();
    ob_flush();
}

function oopfProcessSystemError($app, $conf)
{
    global $env;

    list($method, $param) = explode(',', $conf);
    switch ($method)
    {
        case 'location':
            header('Location: ' . $env['AppInfo']['Urls'][$param]);
            break;
        case 'function':
            oopfProcess($param);
            break;
        case 'errmsg':
            oopfAppError($app, $param);
            break;
    }
    exit;
}

function oopfAppError($app, $msg)
{
    $output = array();
    $app->mergeDefaultOutput($output);
    $app->mergeLangString($output);
    $app->mergeErrorMessages($output, $msg);
    if (isset($app->env['AppInfo']['SystemError']['DefaultTemplate']))
    {
        list($result, $template) = explode(',', $app->env['AppInfo']['SystemError']['DefaultTemplate']);
        $result = $app->processResult($output, $template, $result);
    }
    else
        oopfFatalError(implode('<br>', extractArrayValue($output[ERRMSG_KEYWORD . 'Rows'], ERRMSG_KEYWORD)));
}

function oopfFatalError($string)
{
    die('OOPF Fatal Error: ' .$string);
}

?>
