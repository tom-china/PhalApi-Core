<?php
	
	
	namespace PhalApi\Tests\Api;
	
	
	use PhalApi\Api\Api;
	
	class AlwaysException extends Api {
		public function go() {
			return 'go to BeiJing';
		}
	}