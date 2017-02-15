<?php
	
	
	namespace PhalApi\Tests\Mock\Di;
	
	
	class Demo2 extends Demo {
		public $number = 1;
		
		public function __construct() {
			//echo "Demo2::__construct()\n";
		}
		
		public function onConstruct() {
			//echo "Demo2::onConstruct()\n";
			$this->number = 2;
			parent::onConstruct();
		}
		
		public function onInit() {
			$this->onInitialize();
		}
		
		public function onInitialize() {
			//echo "Demo2::onInitialize()\n";
			$this->number = 3;
			parent::onInitialize();
		}
	}
