<?php
	namespace PhalApi\Tests\Cache;
	
	use PhalApi\Cache\FileCache;
	use PhalApi\Cache\MultiCache;
	use PhalApi\Cache\NoneCache;
	use PHPUnit\Framework\TestCase;
	
	/**
	 *
	 * 针对 PhalApi\Cache\MultiCache 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150226
	 */
	class MultiCacheTest extends TestCase {
		/**
		 * @var $phalApiCacheMulti MultiCache
		 */
		public $phalApiCacheMulti;
		
		/**
		 * @group testAddCache
		 */
		public function testAddCache() {
			$cache = new NoneCache();
			
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
			
			$this->phalApiCacheMulti = new MultiCache();
			
			$fileCache = new FileCache( [ 'path' => __DIR__ ] );
			
			$this->phalApiCacheMulti->addCache( $fileCache );
		}
		
		protected function tearDown() {
		}
		
	}
