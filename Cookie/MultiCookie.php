<?php
	namespace PhalApi\Cookie;
	
	use PhalApi\Crypt\ICrypt;
	use function PhalApi\Helper\DI;
	
	/**
	 * Multi 多级COOKIE
	 *
	 * - 使用crypt进行加解密
	 * - 带记忆功能，即设置后此时能获取
	 *
	 * @package PhalApi\Cookie
	 * @license http://www.phalapi.net/license GPL 协议
	 * @link    http://www.phalapi.net/
	 * @author  dogstar <chanzonghuang@gmail.com> 2015-04-11
	 */
	class MultiCookie extends Cookie {
		
		/**
		 * @internal  $config ['crypt'] 加密的服务，如果未设置，默认取DI()->crypt，须实现PhalApi_Crypt接口
		 * @internal  $config ['key'] $config['crypt']用的密钥，未设置时有一个md5串
		 *
		 * @param array $config
		 */
		public function __construct( array $config = [] ) {
			parent::__construct( $config );
			
			$this->config['crypt'] = $config['crypt'] ?? DI()->crypt;
			
			if ( isset( $config['crypt'] ) && $config['crypt'] instanceof ICrypt ) {
				$this->config['key'] = $config['key'] ?? 'debcf37743b7c835ba367548f07aadc3';
			} else {
				$this->config['crypt'] = null;
			}
		}
		
		/**
		 * 解密获取COOKIE
		 * @see PhalApi_Cookie::get()
		 *
		 * @param null $name
		 *
		 * @return string
		 */
		public function get( $name = null ) {
			$rs = parent::get( $name );
			
			if ( ! isset( $this->config['crypt'] ) ) {
				return $rs;
			}
			
			if ( is_array( $rs ) ) {
				foreach ( $rs as &$valueRef ) {
					$this->config['crypt']->decrypt( $valueRef, $this->config['key'] );
				}
			} else if ( $rs !== null ) {
				$rs = $this->config['crypt']->decrypt( $rs, $this->config['key'] );
			}
			
			return $rs;
		}
		
		/**
		 * 加密设置COOKIE&记忆功能
		 * @see PhalApi_Cookie::set()
		 *
		 * @param string     $name
		 * @param int|string $value
		 * @param null       $expire
		 *
		 * @return bool
		 */
		public function set( $name, $value, $expire = null ) {
			if ( isset( $this->config['crypt'] ) ) {
				$value = $this->config['crypt']->encrypt( $value, $this->config['key'] );
			}
			
			$_COOKIE[ $name ] = $value;
			if ( $expire < $_SERVER['REQUEST_TIME'] ) {
				unset( $_COOKIE[ $name ] );
			}
			
			return parent::set( $name, $value, $expire );
		}
	}
