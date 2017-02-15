<?php
	namespace PhalApi\Tests\Request\Formatter;
	
	use PhalApi\Request\Formatter\FileFormatter;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiRequestFormatterFile_Test
	 *
	 * 针对 ../../../PhalApi/Request/Formatter/File.php PhalApi_Request_Formatter_File 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20160101
	 */
	class FileFormatterTest extends TestCase {
		public $phalApiRequestFormatterFile;
		
		/**
		 * @group testParse
		 */
		public function testParse() {
			$value = [];
			
			$_FILES['aFile'] = [ 'name'     => 'aHa~',
			                     'type'     => 'image/jpeg',
			                     'size'     => 100,
			                     'tmp_name' => '/tmp/123456',
			                     'error'    => 0,
			];
			
			$rule = [ 'name'    => 'aFile',
			          'range'   => [ 'image/jpeg' ],
			          'min'     => 50,
			          'max'     => 1024,
			          'require' => true,
			          'default' => [],
			          'type'    => 'file',
			];
			
			$rs = $this->phalApiRequestFormatterFile->parse( $value, $rule );
		}
		
		/**
		 * @dataProvider provideFileForSuffix
		 */
		public function testSuffixSingleInArray( $fileIndex, $fileData ) {
			$_FILES[ $fileIndex ] = $fileData;
			$value                = [];
			
			$rule = [
				'name'    => $fileIndex,
				'require' => true,
				'default' => [],
				'ext'     => [ 'txt' ],
				'type'    => 'file',
			];
			$rs   = $this->phalApiRequestFormatterFile->parse( $value, $rule );
			$this->assertEquals( $fileData, $rs );
		}
		
		/**
		 * @dataProvider provideFileForSuffix
		 */
		public function testSuffixSingleInString( $fileIndex, $fileData ) {
			$_FILES[ $fileIndex ] = $fileData;
			$value                = [];
			
			$rule = [
				'name'    => $fileIndex,
				'require' => true,
				'default' => [],
				'ext'     => 'txt',
				'type'    => 'file',
			];
			$rs   = $this->phalApiRequestFormatterFile->parse( $value, $rule );
			$this->assertEquals( $fileData, $rs );
		}
		
		/**
		 * @dataProvider provideFileForSuffix
		 */
		public function testSuffixMultiInArray( $fileIndex, $fileData ) {
			$_FILES[ $fileIndex ] = $fileData;
			$value                = [];
			
			$rule = [
				'name'    => $fileIndex,
				'require' => true,
				'default' => [],
				'ext'     => [ 'TXT', 'dat', 'bak' ],
				'type'    => 'file',
			];
			$rs   = $this->phalApiRequestFormatterFile->parse( $value, $rule );
			$this->assertEquals( $fileData, $rs );
		}
		
		/**
		 * @dataProvider provideFileForSuffix
		 */
		public function testSuffixSingleInMulti( $fileIndex, $fileData ) {
			$_FILES[ $fileIndex ] = $fileData;
			$value                = [];
			
			$rule = [
				'name'    => $fileIndex,
				'require' => true,
				'default' => [],
				'ext'     => 'DAT, txt, baK',
				'type'    => 'file',
			];
			$rs   = $this->phalApiRequestFormatterFile->parse( $value, $rule );
			$this->assertEquals( $fileData, $rs );
		}
		
		/**
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testSuffixForSpecialBug() {
			// no ext
			$aFile           = [
				'name'     => '2016',
				'type'     => 'application/text',
				'size'     => 100,
				'tmp_name' => '/tmp/123456',
				'error'    => 0,
			];
			$_FILES['aFile'] = $aFile;
			$value           = [];
			
			$rule = [
				'name'    => 'aFile',
				'require' => true,
				'default' => [],
				'ext'     => 'txt, DAT, baK,', //小心最后的逗号
				'type'    => 'file',
			];
			$rs   = $this->phalApiRequestFormatterFile->parse( $value, $rule );
		}
		
		/**
		 * @dataProvider provideFileForSuffix
		 * @expectedException \PhalApi\Exception\BadRequest
		 */
		public function testSuffixMultiInArrayAndExcpetion( $fileIndex, $fileData ) {
			$_FILES[ $fileIndex ] = $fileData;
			$value                = [];
			
			$rule = [
				'name'    => $fileIndex,
				'require' => true,
				'default' => [],
				'ext'     => [ 'XML', 'HTML' ],
				'type'    => 'file',
			];
			$rs   = $this->phalApiRequestFormatterFile->parse( $value, $rule );
		}
		
		public function provideFileForSuffix() {
			// one ext
			$bFile = [
				'name'     => '2016.txt',
				'type'     => 'application/text',
				'size'     => 100,
				'tmp_name' => '/tmp/123456',
				'error'    => 0,
			];
			// tow ext
			$cFile = [
				'name'     => '2016.log.txt',
				'type'     => 'application/text',
				'size'     => 100,
				'tmp_name' => '/tmp/123456',
				'error'    => 0,
			];
			
			return [
				[ 'bFile', $bFile ],
				[ 'cFile', $cFile ],
			];
		}
		
		public function testParseNotRequire() {
			$value = [];
			
			$rule = [
				'name'    => 'maybeFile',
				'require' => false,
				'type'    => 'file',
			];
			$rs   = $this->phalApiRequestFormatterFile->parse( $value, $rule );
			$this->assertNull( $rs );
		}
		
		public function testParseNotRequireButUpload() {
			$_FILES['maybeFile'] = [
				'name'     => '2016.log.txt',
				'type'     => 'application/text',
				'size'     => 100,
				'tmp_name' => '/tmp/123456',
				'error'    => 0,
			];
			$value               = [];
			
			$rule = [
				'name'    => 'maybeFile',
				'require' => false,
				'type'    => 'file',
			];
			$rs   = $this->phalApiRequestFormatterFile->parse( $value, $rule );
			$this->assertNotNull( $rs );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiRequestFormatterFile = new FileFormatter();
		}
		
		protected function tearDown() {
		}
	}
