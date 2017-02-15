<?php
	namespace PhalApi\Cache;
	use PhalApi\ICache;
	
	/**
	 * Memcache MC缓存
	 *
	 * - 使用序列化对需要存储的值进行转换，以提高速度
	 * - 默认不使用zlib对值压缩
	 * - 请尽量使用Memcached扩展
	 *
	 * @package     PhalApi\Cache
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      PhpStorm George <plzhuangyuan@163.com> 15/5/6 下午8:53
	 */
	
	class MemcacheICache implements ICache {
		
		protected $memcache = null;
		
		protected $prefix;
		
		/**
		 * @param string $config ['host'] Memcache域名
		 * @param int    $config ['port'] Memcache端口
		 * @param string $config ['prefix'] Memcache key prefix
		 */
		public function __construct( $config ) {
			$this->memcache = $this->createMemcache();
			$this->memcache->addServer( $config['host'], $config['port'] );
			$this->prefix = isset( $config['prefix'] ) ? $config['prefix'] : 'phalapi_';
		}
		
		/**
		 * 获取MC实例，以便提供桩入口
		 * @return \Memcache
		 */
		protected function createMemcache() {
			return new \Memcache();
		}
		
		public function set( $key, $value, $expire = 600 ) {
			$this->memcache->set( $this->formatKey( $key ), @serialize( $value ), 0, $expire );
		}
		
		protected function formatKey( $key ) {
			return $this->prefix . $key;
		}
		
		public function get( $key ) {
			$value = $this->memcache->get( $this->formatKey( $key ) );
			
			return $value !== false ? @unserialize( $value ) : null;
		}
		
		public function delete( $key ) {
			return $this->memcache->delete( $this->formatKey( $key ) );
		}
	}
