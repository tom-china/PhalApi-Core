<?php
	namespace PhalApi\Tests;
	
	use PhalApi\Cookie;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCookie_Test
	 *
	 * 针对 ../../../PhalApi/PhalApi/Cookie.php Cookie 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150411
	 */
	class CookieTest extends TestCase {
		public $phalApiCookie;
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$key = null;
			
			$rs = $this->phalApiCookie->get( $key );
			
			$this->assertTrue( is_array( $rs ) );
			
			$this->assertNull( $this->phalApiCookie->get( 'noThisKey' ) );
			
			$_COOKIE['aKey'] = 'phalapi';
			$key             = 'aKey';
			$this->assertEquals( 'phalapi', $this->phalApiCookie->get( $key ) );
		}
		
		/**
		 * @group testSet
		 */
		public function testSet() {
			$key   = 'bKey';
			$value = '2015';
			
			$rs = @$this->phalApiCookie->set( $key, $value );
			
			//should not get in this time, but next time
			$this->assertNull( $this->phalApiCookie->get( $key ) );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiCookie = new Cookie();
		}
		
		protected function tearDown() {
		}
		
	}
