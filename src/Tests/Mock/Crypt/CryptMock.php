<?php
	
	
	namespace PhalApi\Tests\Mock\Crypt;
	
	
	use PhalApi\ICrypt;
	
	class ICryptMock implements ICrypt {
		public function encrypt( $data, $key ) {
			echo __METHOD__ . "($data, $key) ... \n";
			
			return $data;
		}
		
		public function decrypt( $data, $key ) {
			echo __METHOD__ . "($data, $key) ... \n";
			
			return $data;
		}
	}