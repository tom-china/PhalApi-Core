<?php
	namespace PhalApi\Helper;
	
	/**
	 * 考虑再三，出于人性化关怀，提供要些快速的函数和方法
	 *
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2014-12-17
	 */
	use PhalApi\Di\Di;
	use PhalApi\Translator\Translator;
	
	/**
	 * 获取DI
	 * 相当于DI::one()
	 * @return Di
	 */
	function DI() {
		return Di::one();
	}
	
	/**
	 * 设定语言，SL为setLanguage的简写
	 *
	 * @param string $language 翻译包的目录名
	 */
	function SL( $language ) {
		Translator::setLanguage( $language );
	}
	
	/**
	 * 快速翻译
	 *
	 * @param string $msg    待翻译的内容
	 * @param array  $params 动态参数
	 *
	 * @return string
	 */
	function T( $msg, array $params = [] ) {
		return Translator::get( $msg, $params );
	}
