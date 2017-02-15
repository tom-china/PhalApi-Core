<?php
	namespace PhalApi\Tests\Request\Formatter;
	
	use PhalApi\Request\Formatter\CallableFormatter;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiRequestFormatterCallable_Test
	 *
	 * 针对 ../../../PhalApi/Request/Formatter/Callable.php PhalApi_Request_Formatter_Callable 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20151107
	 */
	class CallableFormatterTest extends TestCase {
		public $phalApiRequestFormatterCallable;
		
		/**
		 * @group testParse
		 */
		public function testParse() {
			$value = '1';
			$rule  = [ 'callback' => 'callbackForFormatterTest', 'params' => '11.11', 'name' => 'aKey' ];
			
			$rs = $this->phalApiRequestFormatterCallable->parse( $value, $rule );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiRequestFormatterCallable = new CallableFormatter();
		}
		
		protected function tearDown() {
		}
		
	}
	
	function callbackForFormatterTest( $value, $rule, $params ) {
		echo "got you!";
		//var_dump($value, $rule, $params);
	}
