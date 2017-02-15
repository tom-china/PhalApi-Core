<?php
	
	
	namespace PhalApi\Tests\Mock\Api;
	
	
	use PhalApi\Api;
	
	class ApiImpl extends Api {
		
		public function getRules() {
			return [
				'*'   => [
					'version' => [ 'name' => 'version' ],
				],
				'add' => [
					'left'  => [ 'name' => 'left', 'type' => 'int' ],
					'right' => [ 'name' => 'right', 'type' => 'int' ],
				],
			];
		}
		
		public function add() {
			return $this->left + $this->right;
		}
	}