<?php
	namespace PhalApi\Cache;
	
	/**
	 * Multi 组合模式下的多级缓存
	 *
	 * - 可以自定义添加多重缓存，注意优先添加高效缓存
	 * - 最终将委托给各级缓存进行数据的读写，其中读取为短路读取
	 *
	 * @package     PhalApi\Cache
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-02-22
	 */
	class MultiCache implements ICache {
		
		protected $caches = [];
		
		public function addCache( ICache $cache ) {
			$this->caches[] = $cache;
		}
		
		public function set( $key, $value, $expire = 600 ) {
			foreach ( $this->caches as $cache ) {
				$cache->set( $key, $value, $expire );
			}
		}
		
		public function get( $key ) {
			foreach ( $this->caches as $cache ) {
				$value = $cache->get( $key );
				if ( $value !== null ) {
					return $value;
				}
			}
			
			return null;
		}
		
		public function delete( $key ) {
			foreach ( $this->caches as $cache ) {
				$cache->delete( $key );
			}
		}
	}
