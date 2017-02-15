<?php
	
	
	namespace PhalApi\Tests\Mock\Filter;
	
	
	use PhalApi\Filter;
	
	class AlwaysException implements Filter {
		public function check() {
			throw new \Exception( 'just for test' );
		}
	}