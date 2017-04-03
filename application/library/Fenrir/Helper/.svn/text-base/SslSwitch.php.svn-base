<?php
class Fenrir_Helper_SslSwitch extends Zend_Controller_Action_Helper_Abstract {
    public function direct() {
        if(!array_key_exists('HTTPS',$_SERVER)) {
            $this->doSwitch();    
        } else {
            if(!isset($_SERVER['HTTPS']) && !$_SERVER['HTTPS']) {
                $this->doSwitch();
            }
        }
    }
    
    private function doSwitch() {
        $request    = $this->getRequest();
        $url        = 'https://'. $_SERVER['HTTP_HOST']. $request->getRequestUri();
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->gotoUrl($url);
    }
}