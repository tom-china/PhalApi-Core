<?php
	
	
	namespace PhalApi\Tests\Mock\Crypt;
	
	
	use PhalApi\Crypt;
	
	class CookieCryptMock implements Crypt {
		
		public function encrypt( $data, $key ) {
			return base64_encode( $data );
		}
		
		public function decrypt( $data, $key ) {
			return base64_decode( $data );
		}
	}