<?php
	
	
	namespace PhalApi\Tests\Mock\Di;
	
	
	class Demo {
		public $hasConstruct = false;
		public $hasInitialized = false;
		
		public $mark = null;
		
		public function __construct( $mark ) {
			//echo "Demo::__construct()\n";
			
			$this->mark = $mark;
		}
		
		public function onConstruct() {
			$this->hasConstruct = true;
			//echo "Demo::onConstruct()\n";
		}
		
		public function onInitialize() {
			$this->hasInitialize = true;
			//echo "Demo:: onInitialize()\n";
		}
	}
