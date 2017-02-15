<?php
	namespace PhalApi\Tests;
	
	use PhalApi\Curl;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCUrl_Test
	 *
	 * 针对 ../PhalApi/CUrl.php Curl 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150415
	 */
	class CurlTest extends TestCase {
		public $phalApiCUrl;
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$url       = 'http://phalapi.oschina.mopaas.com/Public/demo/';
			$timeoutMs = 1000;
			
			$rs = $this->phalApiCUrl->get( $url, $timeoutMs );
			//var_dump($rs);
			
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
