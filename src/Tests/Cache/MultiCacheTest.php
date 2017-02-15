<?php
	namespace PhalApi\Tests\Cache;
	
	use PhalApi\Cache\FileICache;
	use PhalApi\Cache\MultiICache;
	use PhalApi\Cache\NoneICache;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCacheMulti_Test
	 *
	 * 针对 ../../PhalApi/Cache/Multi.php PhalApi_Cache_Multi 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150226
	 */
	class MultiCacheTest extends TestCase {
		public $phalApiCacheMulti;
		
		/**
		 * @group testAddCache
		 */
		public function testAddCache() {
			$cache = new NoneICache();
			
			$rs = $this->phalApiCacheMulti->addCache( $cache );
		}
		
		/**
		 * @group testSet
		 */
		public function testSet() {
			$key    = 'multiKey';
			$value  = 'haha~';
			$expire = '100';
			
			$rs = $this->phalApiCacheMulti->set( $key, $value, $expire );
		}
		
		/**
		 * @group   testGet
		 * @depends testSet
		 */
		public function testGet() {
			$key = 'multiKey';
			
			$rs = $this->phalApiCacheMulti->get( $key );
			
			$this->assertSame( 'haha~', $rs );
		}
		
		/**
		 * @group testDelete
		 */
		public function testDelete() {
			$key = 'multiKey';
			
			$rs = $this->phalApiCacheMulti->delete( $key );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiCacheMulti = new MultiICache();
			
			$fileCache = new FileICache( [ 'path' => __DIR__ ] );
			
			$this->phalApiCacheMulti->addCache( $fileCache );
		}
		
		protected function tearDown() {
		}
		
	}
