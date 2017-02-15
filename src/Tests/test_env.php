<?php
//	/**
//	 * 接口统一入口
//	 * @author: dogstar 2014-10-04
//	 */
//	use PhalApi\Api;
//	use PhalApi\Config\FileConfig;
//	use PhalApi\Crypt;
//	use PhalApi\Crypt\MultiMcrypt;
//	use PhalApi\Db\ORM;
//	use PhalApi\Filter;
//	use PhalApi\Logger;
//	use PhalApi\Logger\ExplorerLogger;
//	use PhalApi\Request;
//	use PhalApi\Response\JsonResponse;
//	use PhalApi\Response\JsonpResponse;
//	use PhalApi\Translator;
//	use function PhalApi\Helper\DI;
//
//	/** ---------------- 根目录定义，自动加载 ---------------- **/
//
//	defined( 'API_ROOT' ) || define( 'API_ROOT', __DIR__ );
//
//	date_default_timezone_set( 'Asia/Shanghai' );
//
//	Translator::setLanguage( 'zh_cn' );
//
//	/** ---------------- 注册&初始化服务组件 ---------------- **/
//
//	DI()->config = new FileConfig( __DIR__ . '/Config' );
//
//	DI()->request = new Request();
//
//	DI()->logger = new ExplorerLogger(
//		Logger::LOG_LEVEL_DEBUG | Logger::LOG_LEVEL_INFO | Logger::LOG_LEVEL_ERROR );
//
//	DI()->notorm = function () {
//		$notorm = new ORM( DI()->config->get( 'dbs' ), true );
//
//		return $notorm;
//	};
//
//	DI()->cache = function () {
//		//$mc = new PhalApi_Cache_Memcached(DI()->config->get('sys.mc'));
//		$mc = new Memcached_Mock();
//
//		return $mc;
//	};
//
////加密，测试情况下为防止本地环境没有mcrypt模块 这里作了替身
//	DI()->crypt = function () {
//		//return new Crypt_Mock();
//		return new MultiMcrypt( DI()->config->get( 'sys.crypt.mcrypt_iv' ) );
//	};
//
