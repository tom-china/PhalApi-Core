<?php
	namespace PhalApi\Tests\Api;
	
	use PhalApi\Api\Api;
	use PhalApi\Request\Request;
	use PhalApi\Tests\Mock\Api\ApiImpl;
	use PHPUnit\Framework\TestCase;
	use function PhalApi\Helper\DI;
	
	/**
	 * PhpUnderControl_PhalApiApi_Test
	 *
	 * 针对 ../PhalApi/Api.php PhalApi_Api 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20141004
	 */
	class ApiTest extends TestCase {
		public $coreApi;
		
		/**
		 * @group testInitialize
		 */
		public function testInitialize() {
			DI()->request = new Request( [ 'service' => 'Default.Index' ] );
			$rs           = $this->coreApi->init();
		}
		
		public function testInitializeWithWrongSign() {
			$data            = [];
			$data['service'] = 'Default.Index';
			
			DI()->request = new Request( $data );
			$rs           = $this->coreApi->init();
		}
		
		public function testInitializeWithRightSign() {
			$data            = [];
			$data['service'] = 'Default.Index';
			
			DI()->request = new Request( $data );
			$rs           = $this->coreApi->init();
			
		}
		
		public function testSetterAndGetter() {
			$this->coreApi->username = 'phalapi';
			$this->assertEquals( 'phalapi', $this->coreApi->username );
		}
		
		/**
		 * @expectedException \PhalApi\Exception\InternalServerError
		 */
		public function testGetUndefinedProperty() {
			$this->coreApi->name = 'PhalApi';
			$rs                  = $this->coreApi->noThisKey;
		}
		
		public function testApiImpl() {
			$data            = [];
			$data['service'] = 'Impl.Add';
			$data['version'] = '1.1.0';
			$data['left']    = '6';
			$data['right']   = '1';
			
			DI()->request = new Request( $data );
			DI()->filter  = '\PhalApi\Tests\Mock\Api\FilterImpl';
			
			$impl = new ApiImpl();
			$impl->init();
			
			$rs = $impl->add();
			$this->assertEquals( 7, $rs );
		}
		
		/**
		 * @expectedException \PhalApi\Exception\InternalServerError
		 */
		public function testIllegalFilter() {
			DI()->filter = 'PhalApi_Filter_Impl_NotFound';
			
			$impl = new ApiImpl();
			$impl->init();
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->coreApi = new Api();
		}
		
		protected function tearDown() {
			DI()->filter = null;
		}
	}
