<?php
	namespace PhalApi\Tests\Crypt;
	
	use PhalApi\Crypt\Mcrypt;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCryptMcrypt_Test
	 *
	 * 针对 ./../../PhalApi/Crypt/Mcrypt.php Mcrypt 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20141210
	 */
	class McryptTest extends TestCase {
		public $coreCryptMcrypt;
		
		/**
		 * @group testEncrypt
		 */
		public function testEncrypt() {
			$data = 'dogstar test哈哈 ！！~~ ';
			$key  = '2014';
			
			$rs = $this->coreCryptMcrypt->encrypt( $data, $key );
			
			return [ $data, $key, $rs ];
		}
		
		/**
		 * @depends testEncrypt
		 * @group   testDecrypt
		 */
		public function testDecrypt( $rsData ) {
			list( $data, $key, $encryptData ) = $rsData;
			
			$rs = $this->coreCryptMcrypt->decrypt( $encryptData, $key );
			
			$this->assertEquals( $data, $rs );
		}
		
		/**
		 * @dataProvider provideIv
		 */
		public function testWithIV( $iv ) {
			$mcrypt = new Mcrypt( $iv );
			
			$data = 'dogstar';
			$key  = 'phalapi';
			$this->assertEquals( $mcrypt->decrypt( $mcrypt->encrypt( $data, $key ), $key ), $data );
		}
		
		public function provideIv() {
			return [
				[ 12 ],
				[ '12' ],
				[ '12345678' ],
				[ '1234567890' ],
				[ '&632(jnD' ],
			];
		}
		
		/**
		 * @dataProvider provideComplicateData
		 */
		public function testWorkWithMoreComplicateData( $data ) {
			$mcrypt = new Mcrypt( '12345678' );
			$key    = 'phalapi';
			
			$encryptData = $mcrypt->encrypt( $data, $key );
			
			$decryptData = $mcrypt->decrypt( $encryptData, $key );
			
			$this->assertEquals( $data, $decryptData );
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
		
		protected function setUp() {
			parent::setUp();
			
			if ( ! function_exists( 'mcrypt_module_open' ) ) {
				throw new \Exception( 'function mcrypt_module_open() not exists' );
			}
			
			$this->coreCryptMcrypt = new Mcrypt();
		}
		
		protected function tearDown() {
		}
	}
