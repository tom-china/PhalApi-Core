<?php
	
	
	namespace PhalApi\Tests\Mock\Cache;
	
	
	class MemcachedMock {
		public $data = [];
		
		public function __call( $method, $params ) {
			echo 'Memcached::' . $method . '() with: ', json_encode( $params ), " ... \n";
		}
		
		public function get( $key ) {
			echo "Memcached::get($key) ... \n";
			
			return isset( $this->data[ $key ] ) ? $this->data[ $key ] : null;
		}
		
		public function set( $key, $value, $expire ) {
			echo "Memcached::get($key, ", json_encode( $value ), ", $expire) ... \n";
			$this->data[ $key ] = $value;
		}
		
		public function delete( $key ) {
			unset( $this->data[ $key ] );
		}
	}
