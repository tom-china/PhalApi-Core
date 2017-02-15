<?php
	namespace PhalApi\Tests\Cache;
	
	use PhalApi\Cache\MemcacheICache;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCacheMemcache_Test
	 *
	 * 针对 ../../PhalApi/Cache/Memcache.php Memcache 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150507
	 */
	class MemcacheCacheTest extends TestCase {
		public $phalApiCacheMemcache;
		
		/**
		 * @group testSet
		 */
		public function testSet() {
			$key    = 'key-2015-05-07';
			$value  = 'phalapi';
			$expire = 60;
			
			$this->phalApiCacheMemcache->set( $key, $value, $expire );
			
			$this->assertEquals( 'phalapi', $this->phalApiCacheMemcache->get( $key ) );
		}
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$key = 'no-this-key';
			
			$rs = $this->phalApiCacheMemcache->get( $key );
			
			$this->assertSame( null, $rs );
		}
		
		/**
		 * @group testDelete
		 */
		public function testDelete() {
			$key = 'key-2015-05-07';
			
			$this->assertNotNull( $this->phalApiCacheMemcache->get( $key ) );
			
			$this->phalApiCacheMemcache->delete( $key );
			
			$this->assertNull( $this->phalApiCacheMemcache->get( $key ) );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$config                     = [ 'host' => '127.0.0.1', 'port' => '11211' ];
			$this->phalApiCacheMemcache = new MemcacheICache( $config );
		}
		
		protected function tearDown() {
		}
		
	}
