<?php
	namespace PhalApi\Tests;
	
	use PhalApi\ModelQuery;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiModelQuery_Test
	 *
	 * 针对 ../PhalApi/ModelQuery.php ModelQuery 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150226
	 */
	class ModelQueryTest extends TestCase {
		public $phalApiModelQuery;
		
		public function testMixed() {
			$this->phalApiModelQuery->name = 'dogstar';
			
			$this->assertEquals( 'dogstar', $this->phalApiModelQuery->name );
			
			$this->assertNull( $this->phalApiModelQuery->noThisKey );
			
			$this->assertTrue( $this->phalApiModelQuery->readCache );
			$this->assertTrue( $this->phalApiModelQuery->writeCache );
		}
		
		/**
		 * @group testToArray
		 */
		public function testToArray() {
			$rs = $this->phalApiModelQuery->toArray();
			
			$this->assertTrue( is_array( $rs ) );
			
			$this->assertTrue( $rs['readCache'] );
			$this->assertTrue( $rs['writeCache'] );
		}
		
		public function testConstructFromToArray() {
			$query            = new ModelQuery();
			$query->readCache = false;
			$query->name      = 'phpunit';
			
			$query2 = new ModelQuery( $query->toArray() );
			
			$this->assertEquals( 'phpunit', $query2->name );
			
			$this->assertEquals( $query->toArray(), $query2->toArray() );
			$this->assertEquals( $query, $query2 );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiModelQuery = new ModelQuery();
		}
		
		protected function tearDown() {
		}
	}
