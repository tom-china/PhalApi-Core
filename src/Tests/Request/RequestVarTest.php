<?php
	namespace PhalApi\Tests\Request;
	
	use PhalApi\Request\RequestVar;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiRequestVar_Test
	 *
	 * 针对 ../../PhalApi/Request/Var.php PhalApi_Request_Var 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20141012
	 */
	class RequestVarTest extends TestCase {
		public $coreRequestVar;
		
		/**
		 * @group testFormat
		 */
		public function testFormat() {
			$varName = 'testKey';
			$rule    = [ 'type' => 'int', 'default' => '2014' ];
			$params  = [];
			
			$rs = RequestVar::format( $varName, $rule, $params );
			
			$this->assertSame( 2014, $rs );
		}
		
		/**
		 * @group testFormatString
		 */
		public function testFormatString() {
			$rs = RequestVar::format(
				'testKey', [ 'name' => 'testKey' ], [ 'testKey' => 2014 ] );
			
			$this->assertSame( '2014', $rs );
		}
		
		/**
		 * @group testFormatStringMinMax
		 */
		public function testFormatStringMinMax() {
			$rs = RequestVar::format(
				'testKey', [ 'name'   => 'testKey',
				             "max"    => 9,
				             'min'    => 9,
				             "format" => 'utf8',
			], [ 'testKey' => 'PhalApi测试' ] );
			
			$this->assertSame( 'PhalApi测试', $rs );
		}
		
		/**
		 * @group testFormatStringMinMax
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatStringExceptionMinMax() {
			$rs = RequestVar::format(
				'testKey', [ 'name'   => 'testKey',
				             "max"    => 8,
				             'min'    => 8,
				             "format" => 'utf8',
			], [ 'testKey' => 'PhalApi测试' ] );
			
		}
		
		/**
		 * @group testFormatString
		 * @expectedException \PhalApi\Exception\InternalServerError
		 */
		public function testFormatStringWithRuleExceptionMinGtMax() {
			$rs = RequestVar::format(
				'testKey', [ 'name' => 'testKey', 'min' => 9, 'max' => 5 ], [ 'testKey' => '2014' ] );
		}
		
		/**
		 * @group testFormatString
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatStringWithParamExceptionLtMin() {
			$rs = RequestVar::format(
				'testKey', [ 'name' => 'testKey', 'min' => 8 ], [ 'testKey' => 2014 ] );
		}
		
		/**
		 * @group testFormatString
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatStringWithParamExceptionGtMax() {
			$value = '2014';
			$rule  = [ 'name' => 'testKey', 'max' => 2, ];
			
			$rs = RequestVar::format(
				'testKey', [ 'name' => 'testKey', 'max' => 2 ], [ 'testKey' => 2014 ] );
		}
		
		/**
		 * @group testFormatInt
		 */
		public function testFormatInt() {
			$rs = RequestVar::format(
				'testKey', [ 'name' => 'testKey', 'type' => 'int' ], [ 'testKey' => 2014 ] );
			
			$this->assertSame( 2014, $rs );
		}
		
		/**
		 * @group testFormatFloat
		 */
		public function testFormatFloat() {
			$rs = RequestVar::format(
				'testKey', [ 'name' => 'testKey', 'type' => 'float' ], [ 'testKey' => '3.14' ] );
			
			$this->assertSame( 3.14, $rs );
		}
		
		/**
		 * @dataProvider provideDataForFormatBoolean
		 * @group        testFormatBoolean
		 */
		public function testFormatBoolean( $oriValue, $expValue ) {
			$rs = RequestVar::format(
				'testKey', [ 'name' => 'testKey', 'type' => 'boolean' ], [ 'testKey' => $oriValue ] );
			
			$this->assertSame( $expValue, $rs );
		}
		
		public function provideDataForFormatBoolean() {
			return [
				[ 'on', true ],
				[ 'yes', true ],
				[ 'true', true ],
				[ 'success', true ],
				[ 'false', false ],
				[ '1', true ],
			];
		}
		
		/**
		 * @group testFormatDate
		 */
		public function testFormatDate() {
			$rs = RequestVar::format(
				'testKey', [ 'name'   => 'testKey',
				             'type'   => 'date',
				             'format' => 'timestamp',
			], [ 'testKey' => '2014-10-01 12:00:00' ] );
			
			$this->assertTrue( is_numeric( $rs ) );
			$this->assertSame( 1412136000, $rs );
		}
		
		/**
		 * @group testFormatDate
		 */
		public function testFormatDateIllegal() {
			$rs = RequestVar::format(
				'testKey', [ 'name'   => 'testKey',
				             'type'   => 'date',
				             'format' => 'timestamp',
			], [ 'testKey' => '2014-99-99 XX:XX:XX' ] );
			$this->assertEquals( 0, $rs );
		}
		
		/**
		 * @group testFormatDate
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatDateRange() {
			$rs = RequestVar::format(
				'testKey', [ 'name'   => 'testKey',
				             'type'   => 'date',
				             'format' => 'timestamp',
				             'max'    => 100,
			], [ 'testKey' => '2014-10-01 12:00:00' ] );
		}
		
		/**
		 * @group testFormatArray
		 */
		public function testFormatArrayWithJson() {
			$arr = [ 'age' => 100, 'sex' => 'male' ];
			
			$rs = RequestVar::format(
				'testKey',
				[ 'name' => 'testKey', 'type' => 'array', 'format' => 'json' ],
				[ 'testKey' => json_encode( $arr ) ]
			);
			
			$this->assertSame( $arr, $rs );
		}
		
		public function testFormatArrayWithExplode() {
			$rs = RequestVar::format(
				'testKey',
				[ 'name' => 'testKey', 'type' => 'array', 'format' => 'explode', 'separator' => '|' ],
				[ 'testKey' => '1|2|3|4|5' ]
			);
			
			$this->assertEquals( [ 1, 2, 3, 4, 5 ], $rs );
		}
		
		public function testFormatArrayDefault() {
			$rs = RequestVar::format(
				'testKey',
				[ 'name' => 'testKey', 'type' => 'array' ],
				[ 'testKey' => 'phalapi' ]
			);
			
			$this->assertEquals( [ 'phalapi' ], $rs );
		}
		
		/**
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatArrayRange() {
			$rs = RequestVar::format(
				'testKey',
				[ 'name' => 'testKey', 'type' => 'array', 'format' => 'explode', 'separator' => '|', 'max' => 3 ],
				[ 'testKey' => '1|2|3|4|5' ]
			);
		}
		
		/**
		 * @group testFile
		 */
		public function testFormatFile() {
			$_FILES['aFile'] = [ 'name'     => 'aHa~',
			                     'type'     => 'image/jpeg',
			                     'size'     => 100,
			                     'tmp_name' => '/tmp/123456',
			                     'error'    => 0,
			];
			
			$rule = [ 'name'    => 'aFile',
			          'range'   => [ 'image/jpeg' ],
			          'min'     => 50,
			          'max'     => 1024,
			          'require' => true,
			          'default' => [],
			          'type'    => 'file',
			];
			
			$rs = RequestVar::format( 'aFile', $rule, [] );
			
			$this->assertEquals( $_FILES['aFile'], $rs );
		}
		
		/**
		 * @group testFile
		 */
		public function testFormatFileInsensiveCase() {
			$_FILES['aFile'] = [ 'name'     => 'aHa~',
			                     'type'     => 'image/jpeg',
			                     'size'     => 100,
			                     'tmp_name' => '/tmp/123456',
			                     'error'    => 0,
			];
			
			$rule = [ 'name'    => 'aFile',
			          'range'   => [ 'image/JPEG' ],
			          'min'     => 50,
			          'max'     => 1024,
			          'require' => true,
			          'default' => [],
			          'type'    => 'file',
			];
			
			$rs = RequestVar::format( 'aFile', $rule, [] );
			
			$this->assertEquals( $_FILES['aFile'], $rs );
		}
		
		/**
		 * @group testFile
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatFileButTooLarge() {
			$_FILES['aFile'] = [ 'name'     => 'aHa~',
			                     'type'     => 'image/jpeg',
			                     'size'     => 9999,
			                     'tmp_name' => '/tmp/123456',
			                     'error'    => 0,
			];
			
			$rule = [ 'name'    => 'aFile',
			          'range'   => [ 'image/jpeg' ],
			          'min'     => 50,
			          'max'     => 1024,
			          'require' => true,
			          'default' => [],
			          'type'    => 'file',
			];
			
			$rs = RequestVar::format( 'aFile', $rule, [] );
		}
		
		/**
		 * @group testFile
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatFileButWrongType() {
			$_FILES['aFile'] = [ 'name'     => 'aHa~',
			                     'type'     => 'image/png',
			                     'size'     => 100,
			                     'tmp_name' => '/tmp/123456',
			                     'error'    => 0,
			];
			
			$rule = [ 'name'    => 'aFile',
			          'range'   => [ 'image/jpeg' ],
			          'min'     => 50,
			          'max'     => 1024,
			          'require' => true,
			          'default' => [],
			          'type'    => 'file',
			];
			
			$rs = RequestVar::format( 'aFile', $rule, [] );
		}
		
		/**
		 * @group testFile
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatFileButError() {
			$_FILES['aFile'] = [ 'name'     => 'aHa~',
			                     'type'     => 'image/png',
			                     'size'     => 100,
			                     'tmp_name' => '/tmp/123456',
			                     'error'    => 2,
			];
			
			$rule = [ 'name'    => 'aFile',
			          'range'   => [ 'image/jpeg' ],
			          'min'     => 50,
			          'max'     => 1024,
			          'require' => true,
			          'default' => [],
			          'type'    => 'file',
			];
			
			$rs = RequestVar::format( 'aFile', $rule, [] );
		}
		
		/**
		 * @group testFile
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatFileEmptyButRequire() {
			$rule = [ 'name' => 'aFile', 'type' => 'file' ];
			
			$rs = RequestVar::format( 'aFile', $rule, [] );
			$this->assertEquals( null, $rs );
		}
		
		/**
		 * $group testFile
		 */
		public function testFormatFileEmptyWithDefualt() {
			$default         = [ 'name' => 'test.txt', 'type' => 'txt', 'tmp_name' => '/tmp/test.txt' ];
			$rule            = [ 'name' => 'aFile', 'default' => $default, 'type' => 'file' ];
			$_FILES['aFile'] = null;
			
			$rs = RequestVar::format( 'aFile', $rule, [] );
			$this->assertEquals( $default, $rs );
		}
		
		/**
		 * @group testFormatEnum
		 */
		public function testFormatEnum() {
			$rs = RequestVar::format(
				'testKey', [ 'range' => [ 'ios', 'android' ], 'type' => 'enum' ], [ 'testKey' => 'ios' ] );
			
			$this->assertSame( 'ios', $rs );
		}
		
		/**
		 * @group testFormatEnum
		 * @expectedException \PhalApi\Exception\InternalServerError
		 */
		public function testFormatEnumWithRuleException() {
			$rs = RequestVar::format(
				'testKey', [ 'type' => 'enum', 'name' => 'testKey' ], [ 'testKey' => 'ios' ] );
		}
		
		/**
		 * @group testFormatEnum
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testFormatEnumWithParamException() {
			$rs = RequestVar::format(
				'testKey', [ 'type'  => 'enum',
				             'name'  => 'testKey',
				             'range' => [ 'ios', 'android' ],
			], [ 'testKey' => 'pc' ] );
		}
		
		public function testFormatAllTypes() {
			$params = [
				'floatVal'   => '1.0',
				'booleanVal' => '1',
				'dateVal'    => '2015-02-05 00:00:00',
				'arrayVal'   => 'a,b,c',
				'enumVal'    => 'male',
			];
			
			$rule = [ 'name' => 'floatVal', 'type' => 'float' ];
			$rs   = RequestVar::format( 'floatVal', $rule, $params );
			$this->assertSame( 1.0, $rs );
			
			$rule = [ 'name' => 'booleanVal', 'type' => 'boolean' ];
			$rs   = RequestVar::format( 'booleanVal', $rule, $params );
			$this->assertSame( true, $rs );
			
			$rule = [ 'name' => 'dateVal', 'type' => 'date', 'format' => 'timestamp' ];
			$rs   = RequestVar::format( 'dateVal', $rule, $params );
			$this->assertSame( 1423065600, $rs );
			
			$rule = [ 'name' => 'arrayVal', 'type' => 'array', 'format' => 'explode' ];
			$rs   = RequestVar::format( 'arrayVal', $rule, $params );
			$this->assertSame( [ 'a', 'b', 'c' ], $rs );
			
			$rule = [ 'name' => 'enumVal', 'type' => 'enum', 'range' => [ 'female', 'male' ] ];
			$rs   = RequestVar::format( 'enumVal', $rule, $params );
			$this->assertSame( 'male', $rs );
			
			$rule = [ 'name' => 'noThisKey' ];
			$rs   = RequestVar::format( 'noThisKey', $rule, $params );
			$this->assertSame( null, $rs );
			
			$rule = [ 'name' => 'noThisKey', 'type' => 'noThisType' ];
			$rs   = RequestVar::format( 'noThisKey', $rule, $params );
			$this->assertSame( null, $rs );
			
			$_FILES['aFile'] = [ 'name'     => 'aHa~',
			                     'type'     => 'image/jpeg',
			                     'size'     => 100,
			                     'tmp_name' => '/tmp/123456',
			                     'error'    => 0,
			];
			$rule            = [ 'name'    => 'aFile',
			                     'range'   => [ 'image/jpeg' ],
			                     'min'     => 50,
			                     'max'     => 1024,
			                     'require' => true,
			                     'default' => [],
			                     'type'    => 'file',
			];
			$rs              = RequestVar::format( 'aFile', $rule, $params );
			$this->assertNotEmpty( $rs );
		}
		
		/**
		 * @expectedException \PhalApi\Exception\InternalServerError
		 */
		public function testGetEnumWithEmptyRange() {
			RequestVar::format( 'key', [ 'name'  => 'key',
			                                      'type'  => 'enum',
			                                      'range' => [],
			], [ 'key' => 'aHa~' ] );
		}
		
		public function testStringWithRegxRight() {
			//very simple mobile phone
			$rule = [ 'name' => 'key', 'type' => 'string', 'regex' => '/^[0-9]{11}/' ];
			RequestVar::format( 'testKey', $rule, [ 'testKey' => '13800138000' ] );
		}
		
		/**
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testStringWithRegxWrong() {
			$rule = [ 'name' => 'key', 'type' => 'string', 'regex' => '/^[0-9]{11}/' ];
			RequestVar::format( 'key', $rule, [ 'key' => 'no a number' ] );
		}
		
		public function testFormatCallable() {
			$rs = RequestVar::format(
				'testKey',
				[ 'name' => 'testKey', 'type' => 'callable', 'callback' => [ 'PhalApi_Request_Var_MyCallback', 'go' ] ],
				[ 'testKey' => 1 ]
			);
			
			$this->assertSame( 2, $rs );
		}
		
		/**
		 * @expectedException \PhalApi\Exception\InternalServerError
		 */
		public function testFormatCallableButWroing() {
			$rs = RequestVar::format(
				'testKey',
				[ 'name' => 'testKey', 'type' => 'callable', 'callback' => 'xxx' ],
				[ 'testKey' => 1 ]
			);
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->coreRequestVar = new RequestVar();
		}
		
		protected function tearDown() {
		}
	}