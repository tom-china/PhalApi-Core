<?php
	
	
	namespace Request;
	
	
	class RequestVarMyCallback {
		public static function go( $value, $rule ) {
			return $value + 1;
		}
	}