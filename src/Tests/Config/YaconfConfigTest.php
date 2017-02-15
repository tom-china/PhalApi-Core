<?php
	namespace PhalApi\Tests\Config;
	
	use PhalApi\Config\YaconfIConfig;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiConfigYaconf_Test
	 *
	 * 针对 ../../PhalApi/Config/Yaconf.php PhalApi_Config_Yaconf 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20151109
	 */
	class YaconfConfigTest extends TestCase {
		public $phalApiConfigYaconf;
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			$key     = 'test.name';
			$default = null;
			
			$rs = $this->phalApiConfigYaconf->get( $key, $default );
			
			$this->assertEquals( 'PhalApi', $rs );
		}
		
		/**
		 * @group testHas
		 */
		public function testHas() {
			$key = 'test.version';
			
			$rs = $this->phalApiConfigYaconf->has( $key );
			
			$this->assertTrue( $rs );
		}
		
		protected function setUp() {
			parent::setUp();
			
			/**
			 * vim ./test.ini
			 *
			 * name="PhalApi"
			 * version="1.3.1"
			 */
			
			$this->phalApiConfigYaconf = new YaconfIConfig();
		}
		
		protected function tearDown() {
		}
		
	}
