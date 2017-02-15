<?php
	namespace PhalApi\Tests\Util;
	
	use PhalApi\Util\Curl;
	use PHPUnit\Framework\TestCase;
	
	/**
	 *
	 * 针对 PhalApi\Util\Curl 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150415
	 */
	class CurlTest extends TestCase {
		/**
		 * @var $phalApiCUrl Curl
		 */
		public $phalApiCUrl;
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$url       = 'http://phalapi.oschina.mopaas.com/Public/demo/';
			$timeoutMs = 1000;
			
			$rs = $this->phalApiCUrl->get( $url, $timeoutMs );
			
			$this->assertTrue( is_string( $rs ) );
			
		}
		
		/**
		 * @group testPost
		 */
		public function testPost() {
			//@todo
			$url       = 'http://phalapi.oschina.mopaas.com/Public/demo/';
			$data      = [ 'username' => 'phalapi' ];
			$timeoutMs = 1000;
			
			$rs = $this->phalApiCUrl->post( $url, $data, $timeoutMs );
			
			$this->assertTrue( is_string( $rs ) );
			
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiCUrl = new Curl( 3 );
		}
		
		protected function tearDown() {
		}
		
	}
