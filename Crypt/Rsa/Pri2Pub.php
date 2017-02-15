<?php
	namespace PhalApi\Crypt\Rsa;
	
	use PhalApi\ICrypt;
	
	/**
	 * Pri2Pub RSA原始加密
	 *
	 * RSA - 私钥加密，公钥解密
	 *
	 * @package     PhalApi\Crypt\Rsa
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-03-14
	 */
	class Pri2Pub implements ICrypt {
		
		public function encrypt( $data, $prikey ) {
			$rs = '';
			
			if ( @openssl_private_encrypt( $data, $rs, $prikey ) === false ) {
				return null;
			}
			
			return $rs;
		}
		
		public function decrypt( $data, $pubkey ) {
			$rs = '';
			
			if ( @openssl_public_decrypt( $data, $rs, $pubkey ) === false ) {
				return null;
			}
			
			return $rs;
		}
	}
