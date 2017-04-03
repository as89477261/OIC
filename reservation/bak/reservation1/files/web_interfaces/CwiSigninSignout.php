<?

require_once('CWebInterface.php');
require_once('MUser.php');

class CwiSigninSignout extends CWebInterface
{

    var $function_list = array (
             'sign_in' => array ( FN_ID => 'sign_in', FN_NAME => 'signIn', FN_DESC => 'Sign In'),
             'sign_out' => array ( FN_ID => 'sign_out', FN_NAME => 'signOut', FN_DESC => 'Sign Out')
                               );

    function signIn(&$arg_output, &$arg_template, $arg_input)
    {
        if (! (array_key_exists('boLogin', $arg_input) &&
               array_key_exists('boPwd', $arg_input)))
        {
$ds = $this->getDataStore('tbl_account');
$ds->limit_offset = 20;
$ds->limit_rows = 20;
$data = $ds->getData();
// print_r($this->function_list);
$arg_output['AccountRows'] = $this->function_list;

            $arg_template = 'PageLogin';
            return RESULTTYPE_LAYOUT;
        }
        else
        {
            $m_user = new MUser($this);
            list($user) = $m_user->find(array('login' => $arg_input['boLogin']));
            if (! $user)
            {
                $this->mergeErrorMessages($arg_output, array(array('Login/msgUserNotFound', $arg_input['boLogin'])));
                $arg_template = 'PageLogin';
                return RESULTTYPE_LAYOUT;
            }
//            $authen = true;      // for testing
            $authen = ($user['passwd'] == $m_user->encryptPassword($arg_input['boPwd']));
            if (! $authen)
            {
                $this->mergeErrorMessages($arg_output, array(array('Login/msgWrongPassword', $arg_input['boLogin'])));
                $arg_template = 'PageLogin';
                return RESULTTYPE_LAYOUT;
            }

            // load user's permission
            $user = $m_user->getById($user['user_id']);
            $m_userperm = new MUserPermission($this);
            $perms = $m_userperm->findById($user['user_id']);
            if (is_array($user['group_rows']) && (count($user['group_rows']) > 0))
            {
                $m_groupperm = new MGroupPermission($this);
                $gperms = $m_groupperm->findById(extractArrayValue($user['group_rows'], 'group_id'));
                $perms = array_merge($perms, $gperms);
            }

            foreach ($perms as $row)
                $permission[$row['function_id']] = $row['allowed'];
            unset($user['permission_rows']);
// we can merge temporal permission or permission depending on other data state over here.
            // create login session
            $this->startSession();
            $this->setUserInfo($user); // Value('UserInfo', $user);
            $this->setPermission($permission); // Value('Permission', $permission);
            if ($permission['job_list'] == CONST_YES)
                $arg_output['HttpHeader'][] = 'Location: ' . $this->app_info['PathInfo']['AppHref'] . 'main.php?fid=job_list&pid=' . date('YmdHis');
            elseif ($permission['const_list'] == CONST_YES)
                $arg_output['HttpHeader'][] = 'Location: ' . $this->app_info['PathInfo']['AppHref'] . 'main.php?fid=const_list';
            else
                $arg_output['HttpHeader'][] = 'Location: ' . $this->app_info['PathInfo']['AppHref'] . 'main.php';
            return RESULTTYPE_HTTPHDR;
        }
    }

    function signOut(&$arg_output, &$arg_template, $arg_input)
    {
        if ($this->session->active)
        {
            $user = $this->session->getValue('UserInfo');
            $this->destroySession();
        }
        $arg_output['HttpHeader'][] = 'Location: ' . $this->app_info['PathInfo']['AppHref'] . 'signin.php';
        return RESULTTYPE_HTTPHDR;
    }


}

?>
