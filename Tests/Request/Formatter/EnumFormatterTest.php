<?php
	namespace PhalApi\Tests\Request\Formatter;
	
	use PhalApi\Request\Formatter\EnumFormatter;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiRequestFormatterEnum_Test
	 *
	 * 针对 ../../../PhalApi/Request/Formatter/Enum.php PhalApi_Request_Formatter_Enum 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20151107
	 */
	class EnumFormatterTest extends TestCase {
		public $phalApiRequestFormatterEnum;
		
		/**
		 * @group testParse
		 */
		public function testParse() {
			$value = 'ios';
			$rule  = [ 'range' => [ 'ios', 'android' ] ];
			
			$rs = $this->phalApiRequestFormatterEnum->parse( $value, $rule );
			
			$this->assertEquals( 'ios', $rs );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiRequestFormatterEnum = new EnumFormatter();
		}
		
		protected function tearDown() {
		}
		
	}
