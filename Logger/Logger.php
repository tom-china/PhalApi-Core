<?php
	namespace PhalApi\Logger;
	
	/**
	 * Logger 日志抽象类
	 *
	 * - 对系统的各种情况进行纪录，具体存储媒介由实现类定义
	 * - 日志分类型，不分优先级，多种类型可按并组合
	 *
	 * <br>接口实现示例：<br>
	 * ```
	 *      class PhalApi_Logger_Mock extends Logger {
	 *          public function log($type, $msg, $data) {
	 *              //nothing to do here ...
	 *          }
	 *      }
	 *
	 *      //保存全部类型的日志
	 *      $logger = new PhalApi_Logger_Mock(
	 *          PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);
	 *
	 *      //开发调试使用，且带更多信息
	 *      $logger->debug('this is bebug test', array('name' => 'mock', 'ver' => '1.0.0'));
	 *
	 *      //业务场景使用
	 *      $logger->info('this is info test', 'and more detail here ...');
	 *
	 *      //一些不该发生的事情
	 *      $logger->error('this is error test');
	 * ```
	 *
	 * @package PhalApi
	 * @link    http://www.php-fig.org/psr/psr-3/ Logger Interface
	 * @license http://www.phalapi.net/license GPL 协议
	 * @link    http://www.phalapi.net/
	 * @author  dogstar <chanzonghuang@gmail.com> 2014-10-02
	 */
	
	abstract class Logger {
		
		/**
		 * @var int LOG_LEVEL_DEBUG 调试级别
		 */
		const LOG_LEVEL_DEBUG = 1;
		/**
		 * @var int LOG_LEVEL_INFO 产品级别
		 */
		const LOG_LEVEL_INFO = 2;
		/**
		 * @var int LOG_LEVEL_ERROR 错误级别
		 */
		const LOG_LEVEL_ERROR = 4;
		/**
		 * @var int $logLevel 多个日志级别
		 */
		protected $logLevel = 0;
		
		public function __construct( $level ) {
			$this->logLevel = $level;
		}
		
		/**
		 * 应用产品级日志
		 *
		 * @param string $msg   日志关键描述
		 * @param        string /array $data 场景上下文信息
		 *
		 * @return void
		 */
		public function info( $msg, $data = null ) {
			if ( ! $this->isAllowToLog( self::LOG_LEVEL_INFO ) ) {
				return;
			}
			
			$this->log( 'info', $msg, $data );
		}
		
		/**
		 * 是否允许写入日志，或运算
		 *
		 * @param int $logLevel
		 *
		 * @return boolean
		 */
		protected function isAllowToLog( $logLevel ) {
			return ( ( $this->logLevel & $logLevel ) != 0 ) ? true : false;
		}
		
		/**
		 * 日志纪录
		 *
		 * 可根据不同需要，将日志写入不同的媒介
		 *
		 * @param string $type  日志类型，如：info/debug/error, etc
		 * @param string $msg   日志关键描述
		 * @param        string /array $data 场景上下文信息
		 *
		 * @return void
		 */
		abstract public function log( $type, $msg, $data );
		
		/**
		 * 开发调试级日志
		 *
		 * @param string              $msg  日志关键描述
		 * @param        string|array $data 场景上下文信息
		 *
		 * @return void
		 */
		public function debug( $msg, $data = null ) {
			if ( ! $this->isAllowToLog( self::LOG_LEVEL_DEBUG ) ) {
				return;
			}
			
			$this->log( 'debug', $msg, $data );
		}
		
		/**
		 * 系统错误级日志
		 *
		 * @param string $msg   日志关键描述
		 * @param        string /array $data 场景上下文信息
		 *
		 * @return NULL
		 */
		public function error( $msg, $data = null ) {
			if ( ! $this->isAllowToLog( self::LOG_LEVEL_ERROR ) ) {
				return;
			}
			
			$this->log( 'error', $msg, $data );
		}
	}
