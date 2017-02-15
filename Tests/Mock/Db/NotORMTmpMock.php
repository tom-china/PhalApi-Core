<?php
	
	
	namespace Tests\Mock\Db;
	
	
	use PhalApi\Model\NotORM;
	
	class NotORMTmpMock extends NotORM {
		
		protected function getTableName( $id = null ) {
			return 'tmp';
		}
	}