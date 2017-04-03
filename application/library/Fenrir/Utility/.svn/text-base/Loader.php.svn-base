<?php

class Fenrir_Utility_Loader extends Zend_Loader {
	public static function loadClass($class, $dirs = null) {
		global $config;
		
		if (is_null ( $dirs ) || $dirs == '') {
			parent::loadClass ( $class, array ($config ['classPath'], $config ['appPath'] . 'application/library', $config ['ZendFrameworkPath'] ) );
		} else {
			$tmpDirs = array ($config ['classPath'], $config ['appPath'] . 'application/library', $config ['ZendFrameworkPath'] );
			if (is_array ( $dirs )) {
				$dirs = array_merge ( $dirs, $tmpDirs );
			} else {
				//$tmpDirs = array(_APP_LIB_PATH_, _APP_LIB_ZF_PATH_);
				$dirs = array_push ( $tmpDirs, $dirs );
			}
			parent::loadClass ( $class, $dirs );
		}
	}
	
	public static function loadPortlet($class, $dirs = null) {
		if (class_exists ( $class, false ) || interface_exists ( $class, false )) {
			return;
		}
		
		if ((null !== $dirs) && ! is_string ( $dirs ) && ! is_array ( $dirs )) {
			require_once 'Zend/Exception.php';
			throw new Zend_Exception ( 'Directory argument must be a string or an array' );
		}
		
		
		// autodiscover the path from the class name
		$className = ereg_replace('Portlet','',$class);
		$file = $className . '.Portlet.php';
		if (! empty ( $dirs )) {
			// use the autodiscovered path
			$dirPath = dirname ( $file );
			if (is_string ( $dirs )) {
				$dirs = explode ( PATH_SEPARATOR, $dirs );
			}
			foreach ( $dirs as $key => $dir ) {
				if ($dir == '.') {
					$dirs [$key] = $dirPath;
				} else {
					$dir = rtrim ( $dir, '\\/' );
					$dirs [$key] = $dir . DIRECTORY_SEPARATOR . $dirPath;
				}
			}
			$file = basename ( $file );
			parent::loadFile ( $file, $dirs, true );
		} else {
			parent::_securityCheck ( $file );
			include_once $file;
		}
		
		if (! class_exists ( $class, false ) && ! interface_exists ( $class, false )) {
			require_once 'Zend/Exception.php';
			throw new Zend_Exception ( "File \"$file\" does not exist or class \"$class\" was not found in the file" );
		}
	}
	
	public static function loadEntity($class, $dirs = null) {
		if (class_exists ( $class, false ) || interface_exists ( $class, false )) {
			return;
		}
		
		if ((null !== $dirs) && ! is_string ( $dirs ) && ! is_array ( $dirs )) {
			require_once 'Zend/Exception.php';
			throw new Zend_Exception ( 'Directory argument must be a string or an array' );
		}
		
		// autodiscover the path from the class name
		$className = ereg_replace('Entity','',$class);
		$file = $className . '.Entity.php';
		if (! empty ( $dirs )) {
			// use the autodiscovered path
			$dirPath = dirname ( $file );
			if (is_string ( $dirs )) {
				$dirs = explode ( PATH_SEPARATOR, $dirs );
			}
			foreach ( $dirs as $key => $dir ) {
				if ($dir == '.') {
					$dirs [$key] = $dirPath;
				} else {
					$dir = rtrim ( $dir, '\\/' );
					$dirs [$key] = $dir . DIRECTORY_SEPARATOR . $dirPath;
				}
			}
			$file = basename ( $file );
			parent::loadFile ( $file, $dirs, true );
		} else {
			parent::_securityCheck ( $file );
			include_once $file;
		}
		
		if (! class_exists ( $class, false ) && ! interface_exists ( $class, false )) {
			require_once 'Zend/Exception.php';
			throw new Zend_Exception ( "File \"$file\" does not exist or class \"$class\" was not found in the file" );
		}
	}
	
	public static function autoload($class) {
		global $config;
		try {
			if (ereg ( 'Portlet', $class )) {
				self::loadPortlet($class,$config ['portletPath']);
				return $class;
			} elseif (ereg ( 'Entity', $class )){
				self::loadEntity($class,$config ['entityPath']);
			} else {
				self::loadClass ( $class );
				return $class;
			}
		} catch ( Exception $e ) {
			if (_APP_ENV_ != 'production') {
				echo $e->getTraceAsString ();
			}
			return false;
		}
		//just for help Eclipse not warning
		return false;
	}
}
