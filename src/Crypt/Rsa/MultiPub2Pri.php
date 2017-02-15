<?php
	namespace PhalApi\Crypt\Rsa;
	
	/**
	 * MultiPub2Pri 超长RSA加密
	 *
	 * RSA - 公钥加密，私钥解密 - 超长字符串的应对方案
	 *
	 * @package     PhalApi\Crypt\Rsa
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-03-15
	 */
	
	class MultiPub2Pri extends MultiBase {
		
		protected $pub2pri;
		
		public function __construct() {
			$this->pub2pri = new Pub2Pri();
			
			parent::__construct();
		}
		
		protected function doEncrypt( $toCryptPie, $pubkey ) {
			return $this->pub2pri->encrypt( $toCryptPie, $pubkey );
		}
		
		protected function doDecrypt( $encryptPie, $prikey ) {
			return $this->pub2pri->decrypt( $encryptPie, $prikey );
		}
	}
