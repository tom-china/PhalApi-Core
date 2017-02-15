<?php
	
	
	namespace PhalApi\Tests\Mock\Api;
	
	
	use PhalApi\Api\Api;
	
	class ApiAnotherImpl extends Api {
		
		public function doSth() {
			return 'hello wolrd!';
		}
		
		public function makeSomeTrouble() {
			throw new \Exception( 'as u can see, i mean to make some trouble' );
		}
	}