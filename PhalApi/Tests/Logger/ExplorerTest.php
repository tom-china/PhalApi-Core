<?php
	namespace PhalApi\Tests\Logger;
	
	use PhalApi\Logger;
	use PhalApi\Logger\ExplorerLogger;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiLoggerExplorer_Test
	 *
	 * 针对 ../test_file_for_loader.php Explorer 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150205
	 */
	class ExplorerTest extends TestCase {
		public $phalApiLoggerExplorer;
		
		/**
		 * @group testLog
		 */
		public function testLog() {
			$type = 'test';
			$msg  = 'this is a test msg';
			$data = [ 'from' => 'testLog' ];
			
			$this->phalApiLoggerExplorer->log( $type, $msg, $data );
			
			$this->expectOutputRegex( '/TEST|this is a test msg|{"from":"testLog"}/' );
		}
		
		public function testLogButNoShow() {
			$logger = new ExplorerLogger( 0 );
			
			$logger->info( 'no info' );
			$logger->debug( 'no debug' );
			$logger->error( 'no error' );
			
			$this->expectOutputString( '' );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiLoggerExplorer = new ExplorerLogger(
				Logger::LOG_LEVEL_DEBUG | Logger::LOG_LEVEL_INFO | Logger::LOG_LEVEL_ERROR );
		}
		
		protected function tearDown() {
		}
	}
