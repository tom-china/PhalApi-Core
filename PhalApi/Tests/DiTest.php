<?php
	namespace PhalApi\Tests;
	
	use PhalApi\Di;
	use PhalApi\Exception;
	use PhalApi\Tests\Mock\Di\Demo;
	use PhalApi\Tests\Mock\Di\Demo2;
	use PhalApi\Tests\Mock\Di\DiMock;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiDI_Test
	 *
	 * 针对 ../PhalApi/DI.php PhalApi_DI 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20141004
	 */
	class DiTest extends TestCase {
		public $coreDI;
		
		/**
		 * @group testOne
		 */
		public function testOne() {
			$rs = Di::one();
		}
		
		public function testSetterAndGetter() {
			$this->coreDI->set( 'name', 'dogstar' );
			$this->assertEquals( $this->coreDI->get( 'name' ), 'dogstar' );
			
			$arr = [ 1, 5, 7 ];
			$this->coreDI->set( 'nameArr', $arr );
			$this->assertEquals( $this->coreDI->get( 'nameArr' ), $arr );
		}
		
		public function testMagicFunction() {
			$this->coreDI->setName( 'dogstar' );
			$this->assertEquals( $this->coreDI->getName(), 'dogstar' );
			
			$this->assertEquals( $this->coreDI->getNameDefault( '2013' ), '2013' );
			
			$this->assertEquals( $this->coreDI->getNameNull(), null );
			
			$this->coreDI->setNameSetNull();
			$this->assertEquals( $this->coreDI->getNameSetNull(), null );
		}
		
		public function testClassSettterAndGetter() {
			$this->coreDI->name2 = 'dogstar';
			$this->assertEquals( $this->coreDI->name2, 'dogstar' );
			
			$this->coreDI->nameAgain = 'dogstarAgain';
			$this->assertEquals( $this->coreDI->nameAgain, 'dogstarAgain' );
			
			$this->assertNull( $this->coreDI->nameNull );
			
		}
		
		public function testMixed() {
			$this->coreDI->name1 = 'dogstar1';
			$this->assertEquals( $this->coreDI->name1, 'dogstar1' );
			$this->assertEquals( $this->coreDI->getName1( '2013' ), 'dogstar1' );
			$this->assertEquals( $this->coreDI->name1, 'dogstar1' );
			
			$this->coreDI->setName1( 'dogstar2' );
			$this->assertEquals( $this->coreDI->name1, 'dogstar2' );
			$this->assertEquals( $this->coreDI->getName1( '2013' ), 'dogstar2' );
			$this->assertEquals( $this->coreDI->name1, 'dogstar2' );
			
			$this->coreDI->set( 'name1', 'dogstar3' );
			$this->assertEquals( $this->coreDI->name1, 'dogstar3' );
			$this->assertEquals( $this->coreDI->getName1( '2013' ), 'dogstar3' );
			$this->assertEquals( $this->coreDI->name1, 'dogstar3' );
			
		}
		
		public function testAnonymousFunction() {
			$this->coreDI->set( 'name', function () {
				return new Demo( 2014 );
			} );
			
			$this->assertEquals( $this->coreDI->name->mark, 2014 );
			
			$mark = 2015;
			$this->coreDI->set( 'name1', function () use ( $mark ) {
				return new Demo( $mark );
			} );
			$this->assertEquals( $this->coreDI->name1->mark, $mark );
			
			$this->coreDI->name3 = function () {
				return new Demo( 2015 );
			};
			$this->assertEquals( $this->coreDI->getName3()->mark, 2015 );
		}
		
		public function testLazyLoadClass() {
			$this->coreDI->setName( 'Demo2' );
			$this->assertEquals( $this->coreDI->name instanceof Demo2, true );
			$this->assertEquals( $this->coreDI->name->number, 3 );
			$this->assertEquals( $this->coreDI->name->number, 3 );
			$this->coreDI->name->number = 9;
			$this->assertEquals( $this->coreDI->name->number, 9 );
			$this->assertEquals( $this->coreDI->getName()->number, 9 );
		}
		
		public function testArrayIndex() {
			$this->coreDI['name'] = 'dogstar';
			$this->assertEquals( $this->coreDI->name, 'dogstar' );
			
			$this->coreDI[2014] = 'horse';
			$this->assertEquals( $this->coreDI->get2014(), 'horse' );
			$this->assertEquals( $this->coreDI[2014], 'horse' );
			$this->assertEquals( $this->coreDI->get( 2014 ), 'horse' );
		}
		
		/**
		 * hope nobody use DI as bellow
		 */
		public function testException() {
			try {
				$this->coreDI[ [ 1 ] ] = 1;
				$this->fail( 'no way' );
			} catch ( Exception $ex ) {
			}
			
			try {
				$this->coreDI->set( [ 1 ], [ 1 ] );
				$this->fail( 'no way' );
			} catch ( \Exception $ex ) {
			}
			
			try {
				$this->coreDI->get( [ 1 ], [ 1 ] );
				$this->fail( 'no way' );
			} catch ( Exception $ex ) {
			}
		}
		
		public function testSetAgainAndAgain() {
			$this->coreDI['name'] = function () {
				return 'dogstar';
			};
			$this->assertEquals( 'dogstar', $this->coreDI['name'] );
			
			$this->coreDI['name'] = 'dogstar2';
			$this->assertEquals( 'dogstar2', $this->coreDI['name'] );
			
			$this->coreDI['name'] = function () {
				return 'dogstar3';
			};
			$this->assertEquals( 'dogstar3', $this->coreDI['name'] );
			
			$this->coreDI['name'] = 'dogstar4';
			$this->assertEquals( 'dogstar4', $this->coreDI['name'] );
			
			$this->coreDI['name'] = 'dogstar5';
			$this->assertEquals( 'dogstar5', $this->coreDI['name'] );
			
		}
		
		public function testSetAgainAndAgainByProperty() {
			$this->coreDI->name = 'name';
			$this->assertSame( 'name', $this->coreDI->name );
			
			$this->coreDI->name = 'name2';
			$this->assertSame( 'name2', $this->coreDI->name );
			
			$this->coreDI->name = [ 'name3' ];
			$this->assertSame( [ 'name3' ], $this->coreDI->name );
		}
		
		public function testSetAgainAndAgainBySetter() {
			$this->coreDI->set( 'name', 'value' );
			$this->assertSame( 'value', $this->coreDI->name );
			
			$this->coreDI->set( 'name', 'value2' );
			$this->assertSame( 'value2', $this->coreDI->name );
		}
		
		public function testSetAgainAndAgainByMagic() {
			$this->coreDI->setName( 'value' );
			$this->assertSame( 'value', $this->coreDI->name );
			
			$this->coreDI->setName( 'value2' );
			$this->assertSame( 'value2', $this->coreDI->name );
		}
		
		public function testGetAgainAndAgain() {
			$times = 0;
			
			$this->coreDI['name'] = function () use ( &$times ) {
				$times ++;
				
				return 'dogstar';
			};
			
			$this->assertEquals( 'dogstar', $this->coreDI['name'] );
			$this->assertEquals( 'dogstar', $this->coreDI['name'] );
			$this->assertEquals( 'dogstar', $this->coreDI['name'] );
			$this->assertEquals( 'dogstar', $this->coreDI['name'] );
			
			$this->assertEquals( 1, $times );
		}
		
		public function testNumericKey() {
			$this->coreDI[0] = 0;
			$this->coreDI[1] = 10;
			$this->coreDI[2] = 20;
			
			$this->assertSame( 0, $this->coreDI[0] );
			$this->assertSame( 0, $this->coreDI->get( 0 ) );
			$this->assertSame( 0, $this->coreDI->get0() );
			
			$this->assertSame( 10, $this->coreDI[1] );
			$this->assertSame( 20, $this->coreDI->get( 2 ) );
			$this->assertSame( 20, $this->coreDI->get2() );
		}
		
		public function testMultiLevel() {
			$this->coreDI->dogstar       = new Di();
			$this->coreDI->dogstar->name = "dogstar";
			$this->coreDI->dogstar->age  = "?";
			$this->coreDI->dogstar->id   = 1;
			
			$this->assertSame( 'dogstar', $this->coreDI->dogstar->name );
			$this->assertSame( '?', $this->coreDI->dogstar->age );
			$this->assertSame( 1, $this->coreDI->dogstar->id = 1 );
		}
		
		public function testWithClassName() {
			$this->coreDI->key = '\PhalApi\DI';
			$this->assertInstanceOf( '\PhalApi\DI', $this->coreDI->key );
		}
		
		public function testArrayUsage() {
			$this->coreDI[0] = 0;
			$this->coreDI[1] = 1;
			$this->coreDI[2] = 2;
			
			foreach ( $this->coreDI as $key => $value ) {
				$this->assertSame( $key, $value );
			}
		}
		
		/**
		 * @expectedException \Exception
		 */
		public function testIllegalMethod() {
			$this->coreDI->doSomeThingWrong();
		}
		
		public function testMultiSet() {
			$this->coreDI->set( 'key1', 'value1' )
			             ->set( 'key2', 'value2' )
			             ->set( 'key2', 'value2' )
			             ->set( 'key3', 'value3' );
			
			$this->coreDI->setKey4( 'value4' )
			             ->setKey5( 'value5' )
			             ->setkey6( 'value6' );
			
			$this->assertSame( 'value2', $this->coreDI->key2 );
			$this->assertSame( 'value6', $this->coreDI['key6'] );
		}
		
		public function testOneWithInstanceNull() {
			$oldInstance = DiMock::getInstance();
			
			DiMock::setInstance( null );
			$newDI = Di::one();
			
			if ( ! isset( $newDI['tmp'] ) ) {
				$newDI['tmp'] = '2015';
				
				$this->assertEquals( '2015', $newDI['tmp'] );
				unset( $newDI['tmp'] );
			}
			
			$this->assertEquals( null, $newDI['tmp'] );
			
			DiMock::setInstance( $oldInstance );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->coreDI = new Di();
		}
		
		protected function tearDown() {
		}
	}
	