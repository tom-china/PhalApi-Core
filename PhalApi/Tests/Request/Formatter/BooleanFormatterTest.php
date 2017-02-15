<?php
	namespace PhalApi\Tests\Request\Formatter;
	
	use PhalApi\Request\Formatter\BooleanFormatter;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiRequestFormatterBoolean_Test
	 *
	 * 针对 ../../../PhalApi/Request/Formatter/Boolean.php PhalApi_Request_Formatter_Boolean 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20151107
	 */
	class BooleanFormatterTest extends TestCase {
		public $phalApiRequestFormatterBoolean;
		
		/**
		 * @group testParse
		 */
		public function testParse() {
			$value = 'on';
			$rule  = [];
			
			$rs = $this->phalApiRequestFormatterBoolean->parse( $value, $rule );
			
			$this->assertTrue( $rs );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiRequestFormatterBoolean = new BooleanFormatter();
		}
		
		protected function tearDown() {
		}
		
	}
