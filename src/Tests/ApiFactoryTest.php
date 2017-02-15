<?php
	namespace PhalApi\Tests;
	
	use PhalApi\Api;
	use PhalApi\ApiFactory;
	use PhalApi\Request;
	use PHPUnit\Framework\TestCase;
	use function PhalApi\Helper\DI;
	
	/**
	 *
	 * 针对 ../../PhalApi/ApiFactory.php ApiFactory 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20141002
	 */
	class ApiFactoryTest extends TestCase {
		public $coreApiFactory;
		
		/**
		 * @group testGenerateService
		 */
		public function testGenerateService() {
			$rs = ApiFactory::generateService();
			
			$this->assertNotNull( $rs );
			$this->assertInstanceOf( 'PhalApi_Api', $rs );
		}
		
		public function testGenerateNormalClientService() {
			$data['service'] = 'Default.Index';
			$data['sign']    = '1ec92737c7c287c7249e0adef566544a';
			
			DI()->request = new Request( $data );
			$rs           = ApiFactory::generateService();
			
			$this->assertNotNull( $rs );
			$this->assertInstanceOf( 'PhalApi_Api', $rs );
			$this->assertInstanceOf( 'Api_Default', $rs );
		}
		
		/**
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testGenerateIllegalApiService() {
			$data['service'] = 'NoThisService.Index';
			DI()->request    = new Request( $data );
			$rs              = ApiFactory::generateService();
		}
		
		/**
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testGenerateIllegalActionService() {
			$data['service'] = 'Default.noThisFunction';
			DI()->request    = new Request( $data );
			$rs              = ApiFactory::generateService();
		}
		
		/**
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testIllegalServiceName() {
			$data['service'] = 'Default';
			DI()->request    = new Request( $data );
			$rs              = ApiFactory::generateService();
		}
		
		/**
		 * @expectedException \PhalApi\Exception\InternalServerError
		 */
		public function testNotPhalApiSubclass() {
			$data['service'] = 'Crazy.What';
			DI()->request    = new Request( $data );
			$rs              = ApiFactory::generateService();
		}
		
		protected function setUp() {
			parent::setUp();
		}
		
		protected function tearDown() {
		}
	}
	