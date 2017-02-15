<?php
	
	
	namespace PhalApi\Tests\Mock\Crypt;
	
	
	use PhalApi\Crypt\ICrypt;
	
	class CookieCryptMock implements ICrypt {
		
		public function encrypt( $data, $key ) {
			return base64_encode( $data );
		}
		
		public function decrypt( $data, $key ) {
			return base64_decode( $data );
		}
	}