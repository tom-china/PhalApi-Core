<?php
	
	
	namespace PhalApi\Tests\Mock\Crypt;
	
	
	use PhalApi\Crypt\ICrypt;
	
	class CryptMock implements ICrypt {
		public function encrypt( $data, $key ) {
			echo __METHOD__ . "($data, $key) ... \n";
			
			return $data;
		}
		
		public function decrypt( $data, $key ) {
			echo __METHOD__ . "($data, $key) ... \n";
			
			return $data;
		}
	}