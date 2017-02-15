<?php
	namespace PhalApi\Tests\Filter;
	
	use PhalApi\Filter\SimpleMD5IFilter;
	use PhalApi\Request;
	use PhalApi\Tests\Mock\Api\ApiImpl;
	use PHPUnit\Framework\TestCase;
	use function PhalApi\Helper\DI;
	
	/**
	 * PhpUnderControl_PhalApiFilterSimpleMD5_Test
	 *
	 * 针对 ../../PhalApi/Filter/SimpleMD5.php PhalApi_Filter_SimpleMD5 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20151023
	 */
	class SimpleMD5FilterTest extends TestCase {
		public $phalApiFilterSimpleMD5;
		
		/**
		 * @group testCheck
		 */
		public function testCheck() {
			$rs = $this->phalApiFilterSimpleMD5->check();
		}
		
		/**
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testCheckException() {
			$data         = [
				'service' => 'PhalApi_Api_Impl.Add',
				'left'    => 1,
				'right'   => 1,
			];
			DI()->request = new Request( $data );
			
			$api = new PhalApi_Api_Impl();
			$api->init();
		}
		
		public function testCheckWithRightSign() {
			$data         = [
				'service' => 'PhalApi_Api_Impl.Add',
				'left'    => 1,
				'right'   => 1,
				'sign'    => 'd5c2ea888a6390de5210b9496a1b787a',
			];
			DI()->request = new Request( $data );
			
			$api = new ApiImpl();
			$api->init();
			$rs = $api->add();
			
			$this->assertEquals( 2, $rs );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiFilterSimpleMD5 = new SimpleMD5IFilter();
			DI()->filter                  = 'PhalApi_Filter_SimpleMD5';
		}
		
		protected function tearDown() {
			DI()->filter = null;
		}
	}
