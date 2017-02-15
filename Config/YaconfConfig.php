<?php
	namespace PhalApi\Config;
	
	/**
	 * Yaconf Yaconf扩展配置类
	 *
	 * - 通过Yaconf扩展快速获取配置
	 *
	 * 使用示例：
	 * ```
	 * <code>
	 * $config = new Yaconf();
	 *
	 * var_dump($config->get('foo')); //相当于var_dump(Yaconf::get("foo"));
	 *
	 * var_dump($config->has('foo')); //相当于var_dump(Yaconf::has("foo"));
	 * </code>
	 * ```
	 *
	 * @package     PhalApi\Config
	 * @see         PhalApi_Config::get()
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @link        https://github.com/laruence/yaconf
	 * @author      dogstar <chanzonghuang@gmail.com> 2014-10-02
	 */
	class YaconfConfig implements IConfig {
		
		public function get( $key, $default = null ) {
			return \Yaconf::get( $key, $default );
		}
		
		public function has( $key ) {
			return \Yaconf::has( $key );
		}
	}
