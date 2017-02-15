<?php
	namespace PhalApi\Tests\Util;
	
	use PhalApi\Util\Tool;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiTool_Test
	 *
	 * 针对 ../PhalApi/Tool.php Tool 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150212
	 */
	class ToolTest extends TestCase {
		public $phalApiTool;
		
		/**
		 * @group testGetClientIp
		 */
		public function testGetClientIp() {
			$rs = Tool::getClientIp();
		}
		
		public function testGetClientIpWithEnvMock() {
			$_SERVER['REMOTE_ADDR'] = '127.0.0.4';
			$this->assertEquals( '127.0.0.4', Tool::getClientIp() );
			
			putenv( 'REMOTE_ADDR=127.0.0.3' );
			$this->assertEquals( '127.0.0.3', Tool::getClientIp() );
			
			putenv( 'HTTP_X_FORWARDED_FOR=127.0.0.2' );
			$this->assertEquals( '127.0.0.2', Tool::getClientIp() );
			
			putenv( 'HTTP_CLIENT_IP=127.0.0.1' );
			$this->assertEquals( '127.0.0.1', Tool::getClientIp() );
		}
		
		/**
		 * @group testCreateRandStr
		 */
		public function testCreateRandStr() {
			$len = '5';
			
			$rs = Tool::createRandStr( $len );
			
			$this->assertEquals( $len, strlen( $rs ) );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiTool = new Tool();
		}
		
		protected function tearDown() {
		}
		
	}
