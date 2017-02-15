<?php
	namespace PhalApi\Crypt\Rsa;
	
	use PhalApi\Crypt;
	
	/**
	 * Pub2Pri 原始RSA加密
	 *
	 * RSA - 公钥加密，私钥解密
	 *
	 * @package     PhalApi\Crypt\Rsa
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-03-15
	 */
	class Pub2Pri implements Crypt {
		
		public function encrypt( $data, $pubkey ) {
			$rs = '';
			
			if ( @openssl_public_encrypt( $data, $rs, $pubkey ) === false ) {
				return null;
			}
			
			return $rs;
		}
		
		public function decrypt( $data, $prikey ) {
			$rs = '';
			
			if ( @openssl_private_decrypt( $data, $rs, $prikey ) === false ) {
				return null;
			}
			
			return $rs;
		}
	}
