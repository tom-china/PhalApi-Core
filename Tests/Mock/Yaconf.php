<?php
	
	
	namespace PhalApi\Tests\Mock;
	
	
	if ( ! class_exists( 'Yaconf', false ) ) {
		class Yaconf {
			public static function __callStatic( $method, $params ) {
				echo "Yaconf::$method()...\n";
			}
		}
	}