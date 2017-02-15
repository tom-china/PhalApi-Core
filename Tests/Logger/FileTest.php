<?php
	namespace PhalApi\Tests\Logger;
	
	use PhalApi\Logger\FileLogger;
	use PhalApi\Logger\Logger;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiLoggerFile_Test
	 *
	 * 针对 ../../PhalApi/Logger/File.php File 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20141217
	 */
	class FileTest extends TestCase {
		public $coreLoggerFile;
		
		/**
		 * @group testLog
		 */
		public function testLog() {
			$this->coreLoggerFile->log( 'debug', 'debug from log', '' );
			$this->coreLoggerFile->log( 'task', 'something test for task', [ 'from' => 'phpunit' ] );
			
			$this->assertLogExists( 'debug from log' );
			$this->assertLogExists( 'something test for task' );
		}
		
		protected function assertLogExists( $content ) {
			$logFile = implode( DIRECTORY_SEPARATOR, [
				__DIR__ . '/Runtime',
				'log',
				date( 'Ym', time() ),
				date( 'Ymd', time() ) . '.log',
			] );
			
			$this->assertContains( $content, file_get_contents( $logFile ) );
		}
		
		public function testDebug() {
			$this->coreLoggerFile->debug( 'something debug here', [ 'name' => 'phpunit' ] );
			
			$this->coreLoggerFile->debug( "This 
            should not be 
            multi line" );
			
			$this->assertLogExists( 'something debug here' );
			$this->assertLogExists( 'This' );
			$this->assertLogExists( 'should not be \n' );
			$this->assertLogExists( 'multi' );
		}
		
		public function testInfo() {
			$this->coreLoggerFile->info( 'something info here', 'phpunit' );
			$this->coreLoggerFile->info( 'something info here', 2014 );
			$this->coreLoggerFile->info( 'something info here', true );
			
			$this->assertLogExists( 'something info here' );
			$this->assertLogExists( 'phpunit' );
			$this->assertLogExists( '2014' );
			$this->assertLogExists( '1' );
		}
		
		public function testError() {
			$this->coreLoggerFile->error( 'WTF!' );
			
			$this->assertLogExists( 'WTF!' );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$cmd = sprintf( 'rm %s -rf', __DIR__ . '/Runtime' );
			shell_exec( $cmd );
			
			$this->coreLoggerFile = new FileLogger( __DIR__ . '/Runtime',
				Logger::LOG_LEVEL_DEBUG | Logger::LOG_LEVEL_INFO | Logger::LOG_LEVEL_ERROR );
		}
		
		protected function tearDown() {
			$cmd = sprintf( 'rm %s -rf', __DIR__ . '/Runtime' );
			shell_exec( $cmd );
		}
	}
