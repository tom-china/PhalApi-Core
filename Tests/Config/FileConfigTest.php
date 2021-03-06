<?php
	namespace PhalApi\Tests\Config;
	
	use PhalApi\Cache\FileCache;
	use PhalApi\Config\FileConfig;
	use PHPUnit\Framework\TestCase;
	use function PhalApi\Helper\DI;
	
	/**
	 * PhpUnderControl_PhalApiConfigFile_Test
	 *
	 * 针对 ../PhalApi/Config/File.php PhalApi_Config_File 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20141004
	 */
	class FileConfigTest extends TestCase {
		/**
		 * @var $coreConfigFile FileCache
		 */
		public $coreConfigFile;
		
		public function testConstruct() {
			$config = new FileConfig( __DIR__ . '/Config' );
		}
		
		/**
		 * @group testGet
		 */
		public function testGetDefault() {
			$key     = 'sys.noThisKey';
			$default = 2014;
			
			$rs = $this->coreConfigFile->get( $key, $default );
			
			$this->assertSame( $default, $rs );
		}
		
		public function testGetNormal() {
			$key = 'sys.debug';
			
			$rs = $this->coreConfigFile->get( $key );
			
			$this->assertFalse( $rs );
		}
		
		public function testGetAll() {
			$key = 'dbs';
			
			$rs = $this->coreConfigFile->get( $key );
			
			$this->assertTrue( is_array( $rs ) );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->coreConfigFile = DI()->config;
		}
		
		protected function tearDown() {
		}
	}
