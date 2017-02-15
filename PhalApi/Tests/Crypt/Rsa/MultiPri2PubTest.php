<?php
	namespace PhalApi\Tests\Crypt\Rsa;
	
	use PhalApi\Crypt\Rsa\KeyGenerator;
	use PhalApi\Crypt\Rsa\MultiPri2Pub;
	use PHPUnit\Framework\TestCase;
	use function PhalApi\Helper\DI;
	
	/**
	 * PhpUnderControl_PhalApiCryptRSAMultiPri2Pub_Test
	 *
	 * 针对 ../../../PhalApi/Crypt/RSA/MultiPri2Pub.php MultiPri2Pub 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150314
	 */
	class MultiPri2PubTest extends TestCase {
		public $phalApiCryptRSAMultiPri2Pub;
		
		public $privkey;
		
		public $pubkey;
		
		/**
		 * @group testEncrypt
		 */
		public function testEncrypt() {
			$data = 'something important here ...';
			$key  = $this->privkey;
			
			$rs = $this->phalApiCryptRSAMultiPri2Pub->encrypt( $data, $key );
			
			$this->assertNotEmpty( $rs );
			
			return $rs;
		}
		
		/**
		 * @group testDecrypt
		 */
		public function testDecrypt() {
			//we need to encrypt the data again, since pubkey is different every time
			$data = $this->phalApiCryptRSAMultiPri2Pub->encrypt( 'something important here ...', $this->privkey );
			
			$key = $this->pubkey;
			
			$rs = $this->phalApiCryptRSAMultiPri2Pub->decrypt( $data, $key );
			
			$this->assertEquals( 'something important here ...', $rs );
		}
		
		/**
		 * demo
		 */
		public function testDecryptAfterEncrypt() {
			$keyG    = new KeyGenerator();
			$privkey = $keyG->getPriKey();
			$pubkey  = $keyG->getPubKey();
			
			DI()->crypt = new MultiPri2Pub();
			
			$data = 'AHA! I have $2.22 dollars!';
			
			$encryptData = DI()->crypt->encrypt( $data, $privkey );
			
			$decryptData = DI()->crypt->decrypt( $encryptData, $pubkey );
			
			$this->assertEquals( $data, $decryptData );
		}
		
		/**
		 * @dataProvider provideComplicateData
		 */
		public function testWorkWithMoreComplicateData( $data ) {
			$encryptData = $this->phalApiCryptRSAMultiPri2Pub->encrypt( $data, $this->privkey );
			
			$decryptData = $this->phalApiCryptRSAMultiPri2Pub->decrypt( $encryptData, $this->pubkey );
			$this->assertNotNull( $decryptData );
			$this->assertEquals( $data, $decryptData );
			
			$wrongDecryptData = $this->phalApiCryptRSAMultiPri2Pub->decrypt( $encryptData, 'whatever' );
			$this->assertNotSame( $data, $wrongDecryptData );
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
				[ json_encode( [ 'name' => 'dogstar', 'ext' => '来点中文行不行？' ] ) ],
				[ 'something important here ...' ],
				[ str_repeat( 'something long long here ...', 130 ) ],
			];
		}
		
		protected function setUp() {
			parent::setUp();
			
			/**
			 * $res = openssl_pkey_new();
			 * openssl_pkey_export($res, $privkey);
			 * $this->privkey = $privkey;
			 *
			 * $pubkey = openssl_pkey_get_details($res);
			 * $this->pubkey = $pubkey["key"];
			 */
			
			$keyG          = new KeyGenerator();
			$this->privkey = $keyG->getPriKey();
			$this->pubkey  = $keyG->getPubKey();
			
			$this->phalApiCryptRSAMultiPri2Pub = new MultiPri2Pub();
		}
		
		protected function tearDown() {
		}
		
	}
