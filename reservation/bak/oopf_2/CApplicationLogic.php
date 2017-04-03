<?php 

require_once('CDataStore.php');
define('USERINFO', 'UserInfo');
define('PERMISSION', 'Permissions');
define('MSGSET_ERR', 'errmsgs');
define('MSGSET_RESULT', 'resmsgs');
//require_once('CValidator.php');

class CApplicationLogic
{
    var $session, $env;
    var $db_info, $db_link, $user_info, $permission, $app_info;
    var $lang_by = 'table';       // default language support is by table name suffix
    var $errmsgs, $parse_errmsgs_ini = false;

    function CApplicationLogic(&$environment)
    {
        $this->env =& $environment;
        $this->db_info =& $this->env['DbInfo'];
        if (array_key_exists('DbLink', $this->env) && is_resource($this->env['DbLink']))
	        $this->db_link =& $this->env['DbLink'];
        if (array_key_exists('DsClass', $this->env))
	        $this->ds_class =& $this->env['DsClass'];
        $this->app_info =& $this->env['AppInfo'];
        $this->lang_by = $this->app_info['MultiLanguage']['DbLangBy'];

        // set default db name for session and constant if not exists
        if (! $this->app_info['Session']['SessionDbName'])
            $this->app_info['Session']['SessionDbName'] = $this->db_info['DbName'];
        if (! $this->app_info['Settings']['ConstantDbName'])
            $this->app_info['Settings']['ConstantDbName'] = $this->db_info['DbName'];

        $session =& $this->env['Session'];
        if (is_object($session) && is_a($session, 'CSession'))
            $this->setSession($session);

        $this->init();
    }

    // for initialization if needed
    function init()
    {
    }

    function &createSession($cache_limiter = 'nocache')
    {
        if (! $this->app_info['Session']['Enable'])
            return null;
        $session = new CSession($this->app_info['Session']['SessionCookieName']);
        $session->module = $this->app_info['Session']['Module'];
        if (! is_resource($this->db_link))
        	$this->connectDatabase();

		$dataStore = $this->getDataStore($this->app_info['Session']['SessionTableName'], $this->app_info['Session']['SessionDbName']);
        $session->session_handler = new CSessionHandlerDataStore($dataStore);
        $session->cookie_path = $this->app_info['Session']['SessionCookiePath'];
        $session->cache_limiter = $cache_limiter;
        return $session;
    }

    function startSession($cache_limiter = 'nocache')
    {
        if (!is_object($this->session))
        {
            $session =& $this->createSession($cache_limiter);
            if (! is_object($session))
                return false;
            elseif ($session->found())
                $session->unsetCookie();
        }
        else
            $session =& $this->session;

        if ($session->active)
            return true;
        else
        {
            $session->lifetime = intval($this->app_info['Settings']['SessionLifetime']);
            $result = $session->start();
            $this->setSession($session);
            return $result;
        }
    }

    function resumeSession($cache_limiter = 'nocache')
    {
        if (!is_object($this->session))
        {
            $session =& $this->createSession($cache_limiter);
            $this->setSession($session);
        }
        else
            $session =& $this->session;

        if ($session->active)
            return true;
        else
        {
            $result = $session->resume();
            $this->setSession($session);
            if ($result == PEZ_SESSION_NOERROR)
            {
                return true;
            }
            else
                return false;
        }
    }

    function destroySession()
    {
        // any session destruction process
        if (is_object($this->session))
            $this->session->destroy();
        $this->session = null;
    }

    function setSession(&$session)
    {
        $this->env['Session'] =& $session;
        $this->session =& $session;
        $sess_data =& $session->getSessionData();
        if (is_array($sess_data))
        {
            if (array_key_exists(USERINFO, $sess_data))
                $this->setUserInfo($sess_data[USERINFO]);
            if (array_key_exists(PERMISSION, $sess_data))
                $this->setPermission($sess_data[PERMISSION]);
        }
    }

    function setUserInfo(&$user_info)
    {
        $this->user_info =& $user_info;
        $this->session->setValue(USERINFO, $user_info);
    }

    function getUserInfo()
    {
        return $this->session->getValue(USERINFO);
    }

    // permission for this application framework is just easy array (fid => 'Y' or 'N', ...);
    function setPermission(&$permission)
    {
        $this->permission =& $permission;
        $this->session->setValue(PERMISSION, $permission);
    }

    function getPermission()
    {
        return $this->session->getValue(PERMISSION);
    }

    function connectDatabase()
    {
	    $ds_class = $this->ds_class;
	    $ds = new $ds_class($this->db_info);
		$db_link = $ds->connect();
		if ($db_link === false)
		    oopfFatalError('Unable to connect database on ' . $this->db_info['DbHost'] . ' with user ' . $this->db_info['DbUser']);       // show host, user, pwd too.
	    if (is_resource($db_link))
	    	$this->db_link = $db_link;
    }

    function getDataStore($db_tablename, $db_name = '')
    {
        if (!$db_name)
            $db_name = $this->db_info['DbName'];
        if (! is_resource($this->db_link))
        	$this->connectDatabase();
    	$ds_class = $this->ds_class;
        $ds = new $ds_class($this->db_link, $db_name, $db_tablename);
        $ds->setEncoding($this->app_info['MultiLanguage']['DbEncoding']);
        $this->getDbLangaugeSupport($ds);
        return $ds;
    }

    // never been used and should use model instead
    function newDataStore($ds_classname, $db_name = '')
    {
        if (!$db_name)
            $db_name = $this->db_info['DbName'];
        if (! is_resource($this->db_link))
        	$this->connectDatabase();
        $ds = new $ds_classname($this->db_link, $db_name);
        $ds->setEncoding($this->app_info['MultiLanguage']['DbEncoding']);
        $this->getDbLangaugeSupport($ds);
        return $ds;
    }

    // may need to change or adjust more
    function getDbLangaugeSupport(&$ds, $lang_id = '')
    {
        $conf_multilang =& $this->app_info['MultiLanguage'];
        if (! $conf_multilang['Enable'])
            return ;
        // find valid and supported $lang_id, if found, go on
        if (! $lang_id)
            $lang_id = $this->getCurrentLanguage();

        // if lang by column, set to ds for cascading join table
        if ($this->lang_by == 'column')
            $ds->setMultiLanguageSupport($conf_multilang['Tables'], $conf_multilang['LangColumn'], $lang_id);

        // then set to current ds
        if (is_array($this->app_info['MultiLanguage']['Tables']) && (! in_array($ds->db_table, $this->app_info['MultiLanguage']['Tables'])))
            return ;
        switch ($this->lang_by)
        {
            case 'db':
                $ds->db_name = $ds->db_name . '_' . $lang_id;
                break;
            case 'table':
                $ds->db_table = $ds->db_table . '_' . $lang_id;
                break;
            case 'column':
                $ds->setDefaultWhere(array($conf_multilang['LangColumn'] => $lang_id));
                break;
            default:
                break;
        }
    }

    function getDataStoreConstant()
    {
        if (! is_resource($this->db_link))
        	$this->connectDatabase();
    	$ds_class = $this->ds_class;
        $ds = new $ds_class($this->db_link, $this->app_info['Settings']['ConstantDbName'], $this->app_info['Settings']['ConstantTableName']);
        $ds->setEncoding($this->app_info['MultiLanguage']['DbEncoding']);
        return $ds;
    }

    function getConstantSet($const_id, $lang_id = '')
    {
        if (! $lang_id)
            $lang_id = $this->getCurrentLanguage();
        $const_ds = $this->getDataStoreConstant();
        $const_ds->order_by = array(CONST_COLUMN_ORDER => ORDERBY_ASC);
        $const_list = $const_ds->getData(array(CONST_COLUMN_ID => $const_id, CONST_COLUMN_LANG => $lang_id));
        return $const_list;
    }

    function getConstantList($const_id, $selected = '', $lang_id = '')
    {
        $const_set = $this->getConstantSet($const_id, $lang_id);
        return getList($const_set, CONST_COLUMN_VALUE, CONST_COLUMN_DESC, $selected);
    }

    function getConstantDescription($const_id, $const_value, $lang_id = '')
    {
        if (!$lang_id)
            $lang_id = $this->getCurrentLanguage();
        $const_ds = $this->getDataStoreConstant();
        list($item) = $const_ds->getData(array(CONST_COLUMN_ID => $const_id, CONST_COLUMN_VALUE => $const_value, CONST_COLUMN_LANG => $lang_id));
        return $item[CONST_COLUMN_DESC];
    }

    function getLanguageList($domain = '', $selected = '', $lang_id = '')
    {
        if ((!$domain) && ($lang_id))
            return $this->getConstantList(CONST_ID_LANGUAGE, $selected, $lang_id);
        else
        {
            $ds = $this->getDataStoreConstant();
            $ds->default_where = CONST_COLUMN_VALUE . ' = ' . CONST_COLUMN_LANG;        // get each language by its text
            $ds->setListValueItem(CONST_COLUMN_VALUE, CONST_COLUMN_DESC);
            return $ds->getList($selected, array(CONST_COLUMN_ID => CONST_ID_LANGUAGE, CONST_COLUMN_VALUE => $domain));
        }
    }

    function getSupportedLanguageList($selected = '', $lang_id = '')
    {
        return $this->getLanguageList(explode(',', $this->app_info['MultiLanguage']['SupportedLang']), $selected, $lang_id);
    }

    function prepareMultiLanguageSupport()
    {
        $conf_multilang =& $this->app_info['MultiLanguage'];
        $lang_id = @$conf_multilang['CurrentLang'];
        if ($lang_id == '')
        {
            if ($conf_multilang['Enable'])
            {
                $lang_id = $this->chooseLanguage();
                $conf_multilang['Tables'] = explode(',', $conf_multilang['Tables']);
            }
            else
                $lang_id = $conf_multilang['DefaultLang'];

            $conf_multilang['CurrentLang'] = $lang_id;                    //save to somewhere
            if (isset($session) && is_object($session) && $session->active)
                $this->session->setValue(LANG_ID, $lang_id);
        }
        $this->lang_by = $conf_multilang['DbLangBy'];
    }

    function chooseLanguage()
    {
        $session =& $this->session;
        $conf_multilang =& $this->app_info['MultiLanguage'];

        if (is_object($session) && $session->active)
            $lang_id = $session->getValue(LANG_ID);            //it's possible that the value is not found in session or it's blank

        if ($lang_id == '')
        {
            if ($conf_multilang['AutoSelect'])
                $lang_id = $this->detectLanguage(explode(',', $conf_multilang['SupportedLang']));
        }

        if ($lang_id == '')
            $lang_id = $conf_multilang['DefaultLang'];

        return $lang_id;
    }

    function detectLanguage($support)
    {
        return '';
    }

    function getCurrentLanguage()
    {
//        if (is_object($this->session))
//            $lang_id = $this->session->getValue(LANG_ID);
        $lang_id = $this->app_info['MultiLanguage']['CurrentLang'];
        if (! $lang_id)
            $lang_id = $this->app_info['MultiLanguage']['DefaultLang'];
        return $lang_id;
    }

    function getRegisteredFunctions()
    {
        $funcs = $this->app_info['Functions'];
        $objs = array();
        $func_list = array();
        foreach ($funcs as $func => $class)
        {
            include_once($class . '.php');
            if (!is_object($objs[$class]))
                $objs[$class] = new $class($this->env);
            if (array_key_exists($func, $objs[$class]->function_list))
                $func_list[$func] = $objs[$class]->function_list[$func];
        }
        return $func_list;
    }

    function getTemplateDirLangaugeSupport()
    {
        $tpl_langby = $this->app_info['MultiLanguage']['TemplateLangBy'];
        if ($tpl_langby == 'folder')
            return $this->app_info['PathInfo']['TemplateDir'] . $this->getCurrentLanguage() .'/';
        else
            return $this->app_info['PathInfo']['TemplateDir'];
    }

    function mergeDefaultOutput(&$output)
    {
        $output['lang'] = $this->getCurrentLanguage();
        if (is_array($this->permission))
            $output = array_merge($output, $this->permission);
        $output[USERINFO] = (array)$this->user_info;
    }

    function mergeLangString(&$output)
    {
        $str = $this->parseLangIniFile('strings');
        $output = array_merge($output, $str);
    }

    function parseLangIniFile($filename)
    {
        $str_dir = $this->app_info['PathInfo']['StringDir'];
        $str_langby = $this->app_info['MultiLanguage']['StringLangBy'];
        switch ($str_langby)
        {
            case 'file':
                $filename = $str_dir . $filename . '_' . $this->getCurrentLanguage() . '.ini';
                break;
            case 'folder':
                $str_dir .= ($this->getCurrentLanguage() . '/');
                // no break;
            case false:
            default:
                $filename = $str_dir . $filename . '.ini';
        }
        if (file_exists($filename))
            $str = parse_ini_file($filename, true);
        else
        {
            echo 'File not found - ' . $filename;
            return array();
        }
        if (array_key_exists('IncludeFiles', $str))
        {
            $files = explode(',', $str['IncludeFiles']);
            foreach ($files as $file)
                $str = array_merge($str, $this->parseLangIniFile($file));
            unset ($str['IncludeFiles']);
        }
        return $str;
    }

    function insertLog($note = '', $info = array())
    {
        $ds_log = $this->getDataStore($this->app_info['Settings']['LogTableName']);
        $log_data = array('DevNote' => $note, 'Info' => serialize($info), 'Input' => serialize($_REQUEST), 'SessionData' => serialize($_SESSION), 'Timestamp' => getCurrentDateTime());
        $result = $ds_log->insert($log_data);
        return ($result ? $ds_log->insert_id : false);
    }

    function insertLogUserNote($log_id, $note)
    {
        $ds_log = $this->getDataStore($this->app_info['Settings']['LogTableName']);
        return $ds_log->update(array('UserNote' => $note), array('LogId' => $log_id));
    }

    function findMessage($msg, $msg_set_name)
    {
        // check if the ini file of error messages has been parsed or not
        if (! isset($this->$msg_set_name))
            $this->$msg_set_name = $this->parseLangIniFile($msg_set_name);

        if (is_array($msg))                                     // if it is message with variables
            $val_name = array_shift($msg);                      //     bring out the string name
        else                                                    // else
            $val_name = $msg;                                   //     copy its name
        $name_list = explode('/', $val_name);
        $name = array_shift($name_list);
        $p_val =& $this->$msg_set_name;

        while (is_array($p_val) && array_key_exists($name, $p_val))
        {
            $p_val =& $p_val[$name];
            $name = array_shift($name_list);
        }
        if ((count($name_list) > 0) || (is_array($p_val)))      // if the message code is not found
            return $msg;                                        // use the message code itself
        else
        {
            if (is_array($msg) && (count($msg) > 0))
                return vsprintf($p_val, $msg);                  // if it is message with variables, replaces them
            else
                return $p_val;
        }
    }

    function findResultMessage($msg)
    {
        return $this->findMessage($msg, MSGSET_RESULT);
    }

    function findErrorMessage($msg)
    {
        return $this->findMessage($msg, MSGSET_ERR);
    }

    function mergeResultMessages(&$arg_output, $msgs)
    {
        if (count($msgs) == 0)
            return false;
        elseif (is_array($msgs))
        {
            foreach ($msgs as $key => $msg)
            {
                $msg = $this->findResultMessage($msg);
                if (!is_int($key))
                    $arg_output[$key.RESMSG_KEYWORD] = $msg;
                $arg_output[RESMSG_KEYWORD.'Rows'][][RESMSG_KEYWORD] = $msg;
            }
        }
        else
            $arg_output[RESMSG_KEYWORD.'Rows'][][RESMSG_KEYWORD] = $this->findResultMessage($msgs);
        return true;
    }

    function mergeErrorMessages(&$arg_output, $errmsgs)
    {
        if (count($errmsgs) == 0)
            return false;
        elseif (is_array($errmsgs))
        {
            foreach ($errmsgs as $key => $msg)
            {
                $msg = $this->findErrorMessage($msg);
                if (!is_int($key))
                    $arg_output[$key.ERRMSG_KEYWORD] = $msg;
                $arg_output[ERRMSG_KEYWORD.'Rows'][][ERRMSG_KEYWORD] = $msg;
            }
        }
        else
            $arg_output[ERRMSG_KEYWORD.'Rows'][][ERRMSG_KEYWORD] = $this->findErrorMessage($errmsgs);
        if (count($arg_output[ERRMSG_KEYWORD.'Rows']) > 0)
            $arg_output['ErrorOccurred'] = CONST_YES;
        return true;
    }

    function mergeValidationErrorMessages(&$arg_output, $arg_vld_result, $arg_vrules, $arg_errmsg_mapping = array(), $merge_main_msgs = false)
    {
        if (count($arg_errmsg_mapping) == 0 || count($arg_vld_result) == 0)
            return false;
/*
        $err_msgs_obj = new CErrorMessages($this->user_info, $this->app_info);
        foreach ($arg_vld_result as $key => $value)
        {
            if ($arg_vrules[$key][VR_TYPE] == VR_ROWS)
            {
                if (is_array($arg_errmsg_mapping[$key][$key]))
                    $errmsgs = array_merge($errmsgs, array_values($arg_errmsg_mapping[$key][$key]));       // tricky by Sup :P
            }
            else
                $errmsgs[] = $arg_errmsg_mapping[$key][$value];
        }
        $errmsgs = $err_msgs_obj->getErrorMessages(array('ErrorCode' => $errmsgs));
*/
        foreach ($arg_vld_result as $key => $value)
        {
            if ($arg_vrules[$key][VR_TYPE] == VR_ROWS)
            {
                $err_code = $arg_errmsg_mapping[$key][$key][$value[$key]];
                if ($err_code)
                {
                    $arg_output[$key.ERRMSG_KEYWORD] = $err_code;
                    if ($merge_main_msgs)
                        $arg_output[ERRMSG_KEYWORD.'Rows'][][ERRMSG_KEYWORD] = $err_code;
                }
                unset($value[$key]);
//                $arg_output[$key.'ErrMsg'] = $errmsgs[$arg_errmsg_mapping[$key][$key]];
                $vrules = $arg_vrules[$key][VR_ROWSRULES];
                foreach ($value as $row_order => $row)
                    $arg_output[$key][$row_order] = $this->mergeValidationErrorMessages($arg_output[$key][$row_order], $row, $vrules, $arg_errmsg_mapping[$key]);
            }
            else
            {
                $err_code = $arg_errmsg_mapping[$key][$value];
                if ($err_code)
                {
                    $arg_output[$key.ERRMSG_KEYWORD] = $err_code;
                    if ($merge_main_msgs)
                        $arg_output[ERRMSG_KEYWORD.'Rows'][][ERRMSG_KEYWORD] = $err_code;
                }
            }
        }
        if (count($arg_output[ERRMSG_KEYWORD.'Rows']) > 0)
            $arg_output['ErrorOccurred'] = CONST_YES;
        return true;
    }

}

?>
