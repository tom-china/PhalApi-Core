<?php
	
	
	namespace PhalApi\Tests\Mock;
	
	
	
	use PhalApi\Model\ModelProxy;
	
	class ModelProxyMock extends ModelProxy {
		
		protected function doGetData( $query ) {
			return 'heavy data';
		}
		
		protected function getKey( $query ) {
			return 'heavy_data_' . $query->id;
		}
		
		protected function getExpire( $query ) {
			return 10;
		}
	}