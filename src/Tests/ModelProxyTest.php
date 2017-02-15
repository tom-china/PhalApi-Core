<?php
	namespace PhalApi\Tests;
	
	use PhalApi\Model\ModelProxy;
	use PhalApi\Model\ModelQuery;
	use PhalApi\Tests\Mock\ModelProxyMock;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiModelProxy_Test
	 *
	 * 针对 ../PhalApi/ModelProxy.php PhalApi_ModelProxy 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150226
	 */
	class ModelProxyTest extends TestCase {
		/**
		 * @var $phalApiModelProxy ModelProxy
		 */
		public $phalApiModelProxy;
		
		/**
		 * @group testGetData
		 */
		public function testGetData() {
			$query     = new ModelQuery();
			$query->id = 1;
			
			$rs = $this->phalApiModelProxy->getData( $query );
		}
		
		public function testGetDataWithNoCache() {
			$query             = new ModelQuery();
			$query->id         = 1;
			$query->readCache  = false;
			$query->writeCache = false;
			
			$rs = $this->phalApiModelProxy->getData( $query );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiModelProxy = new ModelProxyMock();
		}
		
		protected function tearDown() {
		}
	}
	