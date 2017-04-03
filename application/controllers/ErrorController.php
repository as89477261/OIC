<?php
/**
 * Error Controller
 * 
 * @create 1/1/2551
 * @update 10/5/2551
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category System
 * 
 *
 */

class ErrorController extends ECMController {
	
	/**
	 * Error Controller Initialization
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'error' );
	}
	
	/**
	 * แสดง Error กรณี Database Error
	 *
	 */
	public function databaseErrorAction() {
		echo $this->ECMView->render ( 'databaseError.phtml' );
	}
	
	/**
	 * Error Controller main action
	 *
	 */
	public function errorAction() {
		global $lang;
		
		$errors = $this->_getParam ( 'error_handler' );
		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :
				// 404 error -- controller or action not found
				
				//$this->getResponse ()->setRawHeader ( 'HTTP/1.1 404 Not Found' );
				// ... get some output to display...
				//$content .= "<h1>404 Page not found!</h1>" . PHP_EOL;
				//$content .= "<p>The page you requested was not found.</p>";
				
				$this->ECMView->assign ( 'errorMessage', $lang['ECMError']['ControllerNotExists'] );
				break;
			default :
				// application error; display error page, but don't change             
				// status code 
				//$content .= "<h1>Error!</h1>" . PHP_EOL;
				//$content .= "<p>An unexpected error occurred with your request. Please try again later.</p>";
				// ...
				

				// Log the exception
				$exception = $errors->exception;
				//$log = new Zend_Log ( new Zend_Log_Writer_Stream ( '/path/to/logs/demo-exceptions_log' ) );
				//$log->debug ( $exception->getMessage () . PHP_EOL . $exception->getTraceAsString () );
				$this->ECMView->assign ( 'errorMessage', 'Program Error'.PHP_EOL.  $exception->getMessage () . PHP_EOL . $exception->getTraceAsString () );
				break;
		}
		echo $this->ECMView->render ( 'error.phtml' );
	}
}
