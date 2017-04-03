<?php

require_once 'application/classes/Logger.php';

/**
 * Logger test case.
 */
class LoggerTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Logger
	 */
	private $Logger;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated LoggerTest::setUp()
		

		$this->Logger = new Logger(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated LoggerTest::tearDown()
		

		$this->Logger = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests Logger::debug()
	 */
	public function testDebug() {
		// TODO Auto-generated LoggerTest::testDebug()
		$this->markTestIncomplete ( "debug test not implemented" );
		
		Logger::debug(/* parameters */);
	
	}
	
	/**
	 * Tests Logger::dump()
	 */
	public function testDump() {
		// TODO Auto-generated LoggerTest::testDump()
		$this->markTestIncomplete ( "dump test not implemented" );
		
		Logger::dump(/* parameters */);
	
	}
	
	/**
	 * Tests Logger::log()
	 */
	public function testLog() {
		// TODO Auto-generated LoggerTest::testLog()
		$this->markTestIncomplete ( "log test not implemented" );
		
		Logger::log(/* parameters */);
	
	}

}

