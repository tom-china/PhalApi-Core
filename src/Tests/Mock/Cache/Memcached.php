<?php
	
	
	namespace PhalApi\Tests\Mock\Cache;
	
	
	if ( ! class_exists( 'Memcached' ) ) {
		class Memcached extends MemcachedMock {
		}
	}
