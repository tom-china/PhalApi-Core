<?php
	namespace PhalApi\Tests;
	
	use PhalApi\PhalApi;
	use PhalApi\Request\Request;
	use PhalApi\Tests\Mock\Response\JsonpResponseMock;
	use PHPUnit\Framework\TestCase;
	use function PhalApi\Helper\DI;
	
	/**
	 *
	 * 针对 PhalApi\PhalApi 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150209
	 */
	class PhalApiTest extends TestCase {
		/**
		 * @var $phalApi PhalApi
		 */
		public $phalApi;
		
		/**
		 * @group testResponse
		 */
		public function testResponseWithJsonMock() {
			DI()->response = 'JsonMock';
			
			$rs = $this->phalApi->response();
			
			$rs->output();
			
			$this->expectOutputString( '{"ret":200,"data":"hello wolrd!","msg":""}' );
		}
		
		/**
		 * @group testResponse
		 */
		public function testResponseWithJsonPMock() {
			DI()->response = new JsonpResponseMock( 'test' );
			
			$rs = $this->phalApi->response();
			
			$rs->output();
			
			$this->expectOutputString( 'test({"ret":200,"data":"hello wolrd!","msg":""})' );
		}
		
		/**
		 * @group testResponse
		 */
		public function testResponseWithExplorer() {
			DI()->response = '\\PhalApi\\Response\\ExplorerResponse';
			
			$rs = $this->phalApi->response();
			
			$rs->output();
			
			$expRs = [
				'ret'  => 200,
				'data' => 'hello wolrd!',
				'msg'  => '',
			];
			
			$this->assertEquals( $expRs, $rs->getResult() );
		}
		
		public function testResponseWithBadRequest() {
			$data = [
				'service' => 'AnotherImpl',
			];
			
			DI()->request  = new Request( $data );
			DI()->response = 'JsonMockResponse';
			
			$phalApi = new PhalApi();
			
			$rs = $phalApi->response();
			
			$rs->output();
			
			$this->expectOutputRegex( '/"ret":400/' );
		}
		
		/**
		 * @expectedException \Exception
		 */
		public function testResponseWithException() {
			$data = [
				'service' => 'AnotherImpl.MakeSomeTrouble',
			];
			
			DI()->request = new Request( $data );
			
			$rs = $this->phalApi->response();
		}
		
		protected function setUp() {
			parent::setUp();
			
			$data = [
				'service' => 'AnotherImpl.doSth',
			];
			
			DI()->request = new Request( $data );
			
			$this->phalApi = new PhalApi();
		}
		
		protected function tearDown() {
			DI()->response = 'PhalApi_Response_Json';
		}
	}
	
