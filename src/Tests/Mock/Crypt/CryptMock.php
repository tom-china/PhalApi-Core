<?php
	
	
	namespace PhalApi\Tests\Mock\Crypt;
	
	
	use PhalApi\Crypt;
	
	class CryptMock implements Crypt {
		public function encrypt( $data, $key ) {
			echo __METHOD__ . "($data, $key) ... \n";
			
			return $data;
		}
		
		public function decrypt( $data, $key ) {
			echo __METHOD__ . "($data, $key) ... \n";
			
			return $data;
		}
	}