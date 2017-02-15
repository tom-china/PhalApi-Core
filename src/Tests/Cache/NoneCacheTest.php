<?php
	namespace PhalApi\Tests\Cache;
	
	use PhalApi\Cache\NoneICache;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCacheNone_Test
	 *
	 * 针对 ../../PhalApi/Cache/None.php None 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150226
	 */
	class NoneCacheTest extends TestCase {
		public $phalApiCacheNone;
		
		/**
		 * @group testSet
		 */
		public function testSet() {
			$key    = 'aKey';
			$value  = 'aValue';
			$expire = '100';
			
			$rs = $this->phalApiCacheNone->set( $key, $value, $expire );
		}
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$key = 'aKey';
			
			$rs = $this->phalApiCacheNone->get( $key );
			
			$this->assertNull( $rs );
		}
		
		/**
		 * @group testDelete
		 */
		public function testDelete() {
			$key = 'aKey';
			
			$rs = $this->phalApiCacheNone->delete( $key );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiCacheNone = new NoneICache();
		}
		
		protected function tearDown() {
		}
		
	}
