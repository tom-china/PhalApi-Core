<?php
	namespace PhalApi\Tests\Cache;
	
	use PhalApi\Cache\FileCache;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCacheFile_Test
	 *
	 * 针对 ../../PhalApi/Cache/File.php PhalApi_Cache_File 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150226
	 */
	class FileCacheTest extends TestCase {
		public $phalApiCacheFile;
		
		/**
		 * @group testSet
		 */
		public function testSet() {
			$key    = 'aYearKey';
			$value  = 2015;
			$expire = '200';
			
			$rs = $this->phalApiCacheFile->set( $key, $value, $expire );
		}
		
		/**
		 * @group   testGet
		 * @depends testSet
		 */
		public function testGet() {
			$key = 'aYearKey';
			
			$rs = $this->phalApiCacheFile->get( $key );
			
			$this->assertSame( 2015, $rs );
		}
		
		/**
		 * @group   testDelete
		 * @depends testSet
		 */
		public function testDelete() {
			$key = 'aYearKey';
			
			$this->phalApiCacheFile->delete( $key );
			
			$rs = $this->phalApiCacheFile->get( $key );
			
			$this->assertSame( null, $rs );
		}
		
		public function testGetAfterSet() {
			$key = 'anotherKey';
			
			$value = [ 'name' => 'dogstar' ];
			
			$this->phalApiCacheFile->set( $key, $value );
			
			$this->assertSame( $value, $this->phalApiCacheFile->get( $key ) );
		}
		
		public function testExpire() {
			$key    = 'tmpKey';
			$value  = 'somethinghere~';
			$expire = 2;
			
			$this->phalApiCacheFile->set( $key, $value, $expire );
			
			$this->assertSame( $value, $this->phalApiCacheFile->get( $key ) );
			
			sleep( 3 );
			
			$this->assertSame( null, $this->phalApiCacheFile->get( $key ) );
		}
		
		protected function setUp() {
			parent::setUp();
			
			@unlink( __DIR__ . '/cache' );
			
			$config['path']         = __DIR__;
			$this->phalApiCacheFile = new FileCache( $config );
		}
		
		protected function tearDown() {
		}
		
	}
