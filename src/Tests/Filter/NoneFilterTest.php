<?php
	namespace PhalApi\Tests\Filter;
	
	use PhalApi\Filter\NoneIFilter;
	use PhalApi\Tests\Api\AlwaysException;
	use PHPUnit\Framework\TestCase;
	use function PhalApi\Helper\DI;
	
	/**
	 * PhpUnderControl_PhalApiFilterNone_Test
	 *
	 * 针对 ../../PhalApi/Filter/None.php PhalApi_Filter_None 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20151023
	 */
	class NoneFilterTest extends TestCase {
		public $phalApiFilterNone;
		
		/**
		 * @group testCheck
		 */
		public function testCheck() {
			$rs = $this->phalApiFilterNone->check();
		}
		
		/**
		 * @expectedException \PhalApi\Exception
		 */
		public function testApiWithCheckException() {
			DI()->filter = '\PhalApi\Tests\Mock\Filter\AlwaysException';
			$api         = new AlwaysException();
			$api->init();
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiFilterNone = new NoneIFilter();
		}
		
		protected function tearDown() {
		}
	}
