<?php
	namespace PhalApi\Tests\Cookie;
	
	use PhalApi\Cookie\MultiCookie;
	use PhalApi\Crypt;
	use PhalApi\Tests\Mock\Crypt\CookieCryptMock;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCookieMulti_Test
	 *
	 * 针对 ../../PhalApi/Cookie/Multi.php Multi 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150411
	 */
	class MultiCookieTest extends TestCase {
		public $phalApiCookieMulti;
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$name = null;
			
			$rs = $this->phalApiCookieMulti->get( $name );
			
			$this->assertTrue( is_array( $rs ) );
			
		}
		
		/**
		 * @group testSet
		 */
		public function testSet() {
			$name   = 'aEKey';
			$value  = '2015';
			$expire = $_SERVER['REQUEST_TIME'] + 10;
			
			$rs = @$this->phalApiCookieMulti->set( $name, $value, $expire );
			
			//remember
			$this->assertEquals( $value, $this->phalApiCookieMulti->get( $name ) );
		}
		
		/**
		 * @group testDelete
		 */
		public function testDelete() {
			$name   = 'aEKey';
			$value  = '2015';
			$expire = $_SERVER['REQUEST_TIME'] + 10;
			
			$rs = @$this->phalApiCookieMulti->set( $name, $value, $expire );
			
			$this->assertNotEmpty( $this->phalApiCookieMulti->get( $name ) );
			
			$rs = @$this->phalApiCookieMulti->delete( $name );
			
			$this->assertNull( $this->phalApiCookieMulti->get( $name ) );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$config                   = [ 'crypt' => new CookieCryptMock(), 'key' => 'aha~' ];
			$this->phalApiCookieMulti = new MultiCookie( $config );
		}
		
		protected function tearDown() {
		}
		
	}
	
