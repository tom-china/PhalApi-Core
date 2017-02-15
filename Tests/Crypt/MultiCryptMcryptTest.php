<?php
	namespace PhalApi\Tests\Crypt;
	
	use PhalApi\Crypt\MultiMcrypt;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiMultiCryptMcrypt_Test
	 *
	 * 针对 ../../PhalApi/Crypt/MultiMcrypt.php MultiMcrypt 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20141211
	 */
	class MultiCryptMcryptTest extends TestCase {
		public $coreMultiCryptMcrypt;
		
		/**
		 * @group testEncrypt
		 */
		public function testEncrypt() {
			$data = 'haha~';
			$key  = '123';
			
			$rs = $this->coreMultiCryptMcrypt->encrypt( $data, $key );
		}
		
		/**
		 * @group testDecrypt
		 */
		public function testDecrypt() {
			$data = 'haha~';
			$key  = '123';
			
			$rs = $this->coreMultiCryptMcrypt->decrypt( $data, $key );
		}
		
		public function testMixed() {
			$data = 'haha!哈哈！';
			$key  = md5( '123' );
			
			$encryptData = $this->coreMultiCryptMcrypt->encrypt( $data, $key );
			
			$decryptData = $this->coreMultiCryptMcrypt->decrypt( $encryptData, $key );
			
			$this->assertEquals( $data, $decryptData );
		}
		
		/**
		 * @dataProvider provideComplicateData
		 */
		public function testWorkWithMoreComplicateData( $data ) {
			$key = 'phalapi';
			
			$encryptData = $this->coreMultiCryptMcrypt->encrypt( $data, $key );
			
			$decryptData = $this->coreMultiCryptMcrypt->decrypt( $encryptData, $key );
			
			$this->assertSame( $data, $decryptData );
		}
		
		public function provideComplicateData() {
			return [
				[ '' ],
				[ ' ' ],
				[ '0' ],
				[ 0 ],
				[ 1 ],
				[ '12#d_' ],
				[ 12345678 ],
				[ '来点中文行不行？' ],
				[ '843435Jhe*&混合' ],
			];
		}
		
		/**
		 * 当无法对称解密时，返回原数据
		 */
		public function testIllegalData() {
			$encryptData = '';
			
			$decryptData = $this->coreMultiCryptMcrypt->decrypt( $encryptData, 'whatever' );
			
			$this->assertEquals( $encryptData, $decryptData );
		}
		
		protected function setUp() {
			parent::setUp();
			
			if ( ! function_exists( 'mcrypt_module_open' ) ) {
				throw new \Exception( 'function mcrypt_module_open() not exists' );
			}
			
			$this->coreMultiCryptMcrypt = new MultiMcrypt( '12345678' );
		}
		
		protected function tearDown() {
		}
	}
