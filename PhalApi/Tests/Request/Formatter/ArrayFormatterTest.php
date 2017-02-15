<?php
	namespace PhalApi\Tests\Request\Formatter;
	
	use PhalApi\Request\Formatter\ArrayFormatter;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiRequestFormatterArray_Test
	 *
	 * 针对 ../../../PhalApi/Request/Formatter/Array.php PhalApi_Request_Formatter_Array 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20151107
	 */
	class ArrayFormatterTest extends TestCase {
		public $phalApiRequestFormatterArray;
		
		/**
		 * @group testParse
		 */
		public function testParse() {
			$value = '1|2|3|4|5';
			$rule  = [ 'name' => 'testKey', 'type' => 'array', 'format' => 'explode', 'separator' => '|' ];
			
			$rs = $this->phalApiRequestFormatterArray->parse( $value, $rule );
			
			$this->assertTrue( is_array( $rs ) );
			$this->assertEquals( [ 1, 2, 3, 4, 5 ], $rs );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiRequestFormatterArray = new ArrayFormatter();
		}
		
		protected function tearDown() {
		}
		
	}
