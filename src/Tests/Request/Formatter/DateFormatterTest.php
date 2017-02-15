<?php
	namespace PhalApi\Tests\Request\Formatter;
	
	use PhalApi\Request\Formatter\DateFormatter;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiRequestFormatterDate_Test
	 *
	 * 针对 ../../../PhalApi/Request/Formatter/Date.php PhalApi_Request_Formatter_Date 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20151107
	 */
	class DateFormatterTest extends TestCase {
		public $phalApiRequestFormatterDate;
		
		/**
		 * @group testParse
		 */
		public function testParse() {
			$value = '2014-10-01 12:00:00';
			$rule  = [ 'name' => 'testKey', 'type' => 'date', 'format' => 'timestamp' ];
			
			$rs = $this->phalApiRequestFormatterDate->parse( $value, $rule );
			
			$this->assertTrue( is_numeric( $rs ) );
			$this->assertSame( 1412136000, $rs );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiRequestFormatterDate = new DateFormatter();
		}
		
		protected function tearDown() {
		}
		
	}
