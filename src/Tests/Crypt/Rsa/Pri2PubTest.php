<?php
	namespace PhalApi\Tests\Crypt\Rsa;
	
	use PhalApi\Crypt\Rsa\KeyGenerator;
	use PhalApi\Crypt\Rsa\Pri2Pub;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiCryptRSAPri2Pub_Test
	 *
	 * 针对 ../../../PhalApi/Crypt/RSA/Pri2Pub.php Pri2Pub 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150315
	 */
	class Pri2PubTest extends TestCase {
		public $phalApiCryptRSAPri2Pub;
		
		public function testHere() {
			$keyG   = new KeyGenerator();
			$prikey = $keyG->getPriKey();
			$pubkey = $keyG->getPubkey();
			
			$data = 'something important here ...';
			
			$encryptData = $this->phalApiCryptRSAPri2Pub->encrypt( $data, $prikey );
			
			$decryptData = $this->phalApiCryptRSAPri2Pub->decrypt( $encryptData, $pubkey );
			
			$this->assertEquals( $data, $decryptData );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->phalApiCryptRSAPri2Pub = new Pri2Pub();
		}
		
		protected function tearDown() {
		}
	}
