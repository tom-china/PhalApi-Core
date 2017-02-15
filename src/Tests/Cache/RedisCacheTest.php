<?php
	namespace PhalApi\Tests\Cache;
	
	use PhalApi\Cache\RedisICache;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiRedis_Test
	 *
	 * 针对 ../../PhalApi/Cache/Redis.php PhalApi_Redis 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150516
	 */
	class RedisCacheTest extends TestCase {
		public $phalApiRedis;
		
		/**
		 * @group testSet
		 */
		public function testSet() {
			$key    = 'testSetKey';
			$value  = 'phalapi';
			$expire = 2;
			
			$rs = $this->phalApiRedis->set( $key, $value, $expire );
		}
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$key = 'testSetKey';
			
			$rs = $this->phalApiRedis->get( $key );
			
			$this->assertEquals( 'phalapi', $rs );
			
			sleep( 3 );
			
			$this->assertSame( null, $this->phalApiRedis->get( $key ) );
		}
		
		/**
		 * @group testDelete
		 */
		public function testDelete() {
			$key = 'testDeleteKey';
			
			$this->phalApiRedis->set( $key, 'net', 3 );
			
			$this->assertNotEmpty( $this->phalApiRedis->get( $key ) );
			
			$rs = $this->phalApiRedis->delete( $key );
			
			$this->assertEmpty( $this->phalApiRedis->get( $key ) );
		}
		
		/**
		 * @group testSetnx
		 */
		public function testSetnx() {
			$key   = 'testSetnxKey';
			$value = [ 'name' => 'phalapi' ];
			
			$rs = $this->phalApiRedis->setnx( $key, $value );
			
			$this->assertEquals( [ 'name' => 'phalapi' ], $this->phalApiRedis->get( $key ) );
		}
		
		/**
		 * @group testLPush
		 */
		public function testLPush() {
			$key = 'testLPushKey';
			
			$this->phalApiRedis->delete( $key );
			
			$rs = $this->phalApiRedis->lPush( $key, 1 );
			$this->assertEquals( 1, $rs );
			$rs = $this->phalApiRedis->lPush( $key, 2 );
			$this->assertEquals( 2, $rs );
			$rs = $this->phalApiRedis->lPush( $key, 3 );
			$this->assertEquals( 3, $rs );
		}
		
		/**
		 * @group testRPush
		 */
		public function testRPush() {
			$key = 'testRPushKey';
			
			$this->phalApiRedis->delete( $key );
			
			$rs = $this->phalApiRedis->rPush( $key, 1 );
			$this->assertEquals( 1, $rs );
			$rs = $this->phalApiRedis->rPush( $key, 'haha~' );
			$this->assertEquals( 2, $rs );
			$rs = $this->phalApiRedis->rPush( $key, true );
			$this->assertEquals( 3, $rs );
			$rs = $this->phalApiRedis->rPush( $key, [ 'name' => 'phalapi' ] );
			$this->assertEquals( 4, $rs );
		}
		
		/**
		 * @group testLPop
		 */
		public function testLPop() {
			$key = 'testLPushKey';
			
			$rs = $this->phalApiRedis->lPop( $key );
			$this->assertEquals( 3, $rs );
			$rs = $this->phalApiRedis->lPop( $key );
			$this->assertEquals( 2, $rs );
			$rs = $this->phalApiRedis->lPop( $key );
			$this->assertEquals( 1, $rs );
			
			$rs = $this->phalApiRedis->lPop( $key );
			$this->assertEquals( null, $rs );
		}
		
		/**
		 * @group testRPop
		 */
		public function testRPop() {
			$key = 'testRPushKey';
			
			$rs = $this->phalApiRedis->rPop( $key );
			$this->assertSame( [ 'name' => 'phalapi' ], $rs );
			$rs = $this->phalApiRedis->rPop( $key );
			$this->assertSame( true, $rs );
			$rs = $this->phalApiRedis->rPop( $key );
			$this->assertSame( 'haha~', $rs );
			$rs = $this->phalApiRedis->rPop( $key );
			$this->assertSame( 1, $rs );
			
			$rs = $this->phalApiRedis->rPop( $key );
			$this->assertSame( null, $rs );
		}
		
		public function testPushAndPop() {
			$key = 'testPushAndPopKey';
			
			$this->phalApiRedis->delete( $key );
			
			$this->phalApiRedis->rPush( $key, 'www' );
			$this->phalApiRedis->rPush( $key, 'phalapi' );
			$this->phalApiRedis->rPush( $key, 'net' );
			
			$this->assertEquals( 'www', $this->phalApiRedis->lPop( $key ) );
			$this->assertEquals( 'phalapi', $this->phalApiRedis->lPop( $key ) );
			$this->assertEquals( 'net', $this->phalApiRedis->lPop( $key ) );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$config             = [ 'host' => '127.0.0.1', 'port' => 6379 ];
			$this->phalApiRedis = new RedisICache( $config );
		}
		
		protected function tearDown() {
		}
	}
