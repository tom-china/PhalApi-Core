<?php
	namespace PhalApi\Tests\Model;
	
	use PhalApi\Tests\Mock\Db\DefaultTableMock;
	use PhalApi\Tests\Mock\Db\NotORMTmpMock;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiModelNotORM_Test
	 *
	 * 针对 ../../PhalApi/Model/NotORM.php PhalApi_Model_NotORM 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150226
	 */
	class NotORMTest extends TestCase {
		public $phalApiModelNotORM;
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$id     = '1';
			$fields = '*';
			
			$rs = $this->phalApiModelNotORM->get( $id, $fields );
			
			$this->assertNotEmpty( $rs );
			
			$this->assertEquals( 'welcome here', $rs['content'] );
		}
		
		/**
		 * @group testInsert
		 */
		public function testInsert() {
			$data = [ 'id' => 100, 'content' => 'phpunit', 'ext_data' => [ 'year' => 2015 ] ];
			$id   = null;
			
			$rs = $this->phalApiModelNotORM->insert( $data, $id );
			
			$rs = $this->phalApiModelNotORM->get( 100, 'content, ext_data' );
			
			$this->assertEquals( 'phpunit', $rs['content'] );
			$this->assertEquals( [ 'year' => 2015 ], $rs['ext_data'] );
		}
		
		/**
		 * @group   testUpdate
		 * @depends testInsert
		 */
		public function testUpdate() {
			$id   = '100';
			$data = [ 'content' => 'phpunit2', 'ext_data' => [ 'year' => 2020 ] ];
			
			$this->phalApiModelNotORM->update( $id, $data );
			
			$rs = $this->phalApiModelNotORM->get( $id, 'content, ext_data' );
			
			$this->assertEquals( 'phpunit2', $rs['content'] );
			$this->assertEquals( [ 'year' => 2020 ], $rs['ext_data'] );
		}
		
		/**
		 * @group testDelete
		 */
		public function testDelete() {
			$id = '100';
			
			$rs = $this->phalApiModelNotORM->delete( $id );
		}
		
		/**
		 * @dataProvider provideDefaultTableData
		 */
		public function testDefaultTable( $tableName, $tableClass ) {
			$model = new $tableClass();
			$this->assertEquals( $tableName, $model->getTableName( null ) );
		}
		
		public function provideDefaultTableData() {
			return [
				[ 'defaulttable', DefaultTableMock::class ],
			];
		}
		
		protected function setUp() {
			parent::setUp();
			$this->phalApiModelNotORM = new NotORMTmpMock();
		}
		
		protected function tearDown() {
		}
	}

