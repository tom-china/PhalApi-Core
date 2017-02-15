<?php
	
	
	namespace PhalApi\Tests\Mock\Di;
	
	
	
	use PhalApi\Di\Di;
	
	class DiMock extends Di {
		
		public static function getInstance() {
			return Di::$instance;
		}
		
		public static function setInstance( $instance ) {
			Di::$instance = $instance;
		}
	}