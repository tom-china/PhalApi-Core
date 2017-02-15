<?php
	namespace PhalApi\Di;
	
	use ArrayAccess;
	use Closure;
	use PhalApi\Exception\InternalServerError;
	use PhalApi\Request\Request;
	use PhalApi\Response\JsonResponse;
	use PhalApi\Translator\Translator;
	
	/**
	 * DI 依赖注入类
	 *
	 *  Dependency Injection 依赖注入容器
	 *
	 * - 调用的方式有：set/get函数、魔法方法setX/getX、类变量$fdi->X、数组$fdi['X]
	 * - 初始化的途径：直接赋值、类名、匿名函数
	 *
	 * <br>使用示例：<br>
	 * ```
	 *       $di = new DI();
	 *
	 *       // 用的方式有：set/get函数  魔法方法setX/getX、类属性$di->X、数组$di['X']
	 *       $di->key = 'value';
	 *       $di['key'] = 'value';
	 *       $di->set('key', 'value');
	 *       $di->setKey('value');
	 *
	 *       echo $di->key;
	 *       echo $di['key'];
	 *       echo $di->get('key');
	 *       echo $di->getKey();
	 *
	 *       // 初始化的途径：直接赋值、类名(会回调onInitialize函数)、匿名函数
	 *       $di->simpleKey = array('value');
	 *       $di->classKey = 'DI';
	 *       $di->closureKey = function () {
	 *            return 'sth heavy ...';
	 *       };
	 * ```
	 *
	 * @property \PhalApi\Request\Request       $request    请求
	 * @property \PhalApi\Response\JsonResponse $response   结果响应
	 * @property \PhalApi\Cache\ICache          $cache      缓存
	 * @property \PhalApi\Crypt\ICrypt          $crypt      加密
	 * @property \PhalApi\Config\IConfig        $config     配置
	 * @property \PhalApi\Logger\Logger         $logger     日记
	 * @property \PhalApi\Db\ORM                $notorm     数据库
	 * @property bool                           $debug      debug
	 *
	 * @package     PhalApi
	 * @link        http://docs.phalconphp.com/en/latest/reference/di.html 实现统一的资源设置、获取与管理，支持延时加载
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2014-01-22
	 */
	class Di implements ArrayAccess {
		
		/**
		 * @var Di $instance 单例
		 */
		protected static $instance = null;
		
		/**
		 * @var array $hitTimes 服务命中的次数
		 */
		protected $hitTimes = [];
		
		/**
		 * @var array 注册的服务池
		 */
		protected $data = [];
		
		final public function __construct() {
			
		}
		
		/**
		 * 获取DI单体实例
		 *
		 * - 1、将进行service级的构造与初始化
		 * - 2、也可以通过new创建，但不能实现service的共享
		 */
		public static function one() {
			if ( self::$instance == null ) {
				self::$instance = new static();
				self::$instance->onConstruct();
			}
			
			return self::$instance;
		}
		
		/**
		 * service级的构造函数
		 *
		 * - 1、可实现一些自定义业务的操作，如内置默认service
		 * - 2、首次创建时将会调用
		 */
		public function onConstruct() {
			//@todo
			$this->request  = Request::class;
			$this->response = JsonResponse::class;
		}
		
		public function onInitialize() {
		}
		
		/**
		 * @param $name
		 * @param $arguments
		 *
		 * @return mixed|\PhalApi\Di\Di
		 * @throws \PhalApi\Exception\InternalServerError
		 */
		
		public function __call( $name, $arguments ) {
			if ( substr( $name, 0, 3 ) == 'set' ) {
				$key = lcfirst( substr( $name, 3 ) );
				
				return $this->set( $key, isset( $arguments[0] ) ? $arguments[0] : null );
			} else if ( substr( $name, 0, 3 ) == 'get' ) {
				$key = lcfirst( substr( $name, 3 ) );
				
				return $this->get( $key, isset( $arguments[0] ) ? $arguments[0] : null );
			} else {
			}
			
			throw new InternalServerError(
				Translator::get( 'Call to undefined method DI::{name}() .', [ 'name' => $name ] )
			);
		}
		
		/**
		 * 统一setter
		 *
		 * - 1、设置保存service的构造原型，延时创建
		 *
		 * @param string $key   service注册名称，要求唯一，区分大小写
		 *
		 * @param mixed  $value service的值，可以是具体的值或实例、类名、匿名函数、数组配置
		 *
		 * @return $this
		 */
		public function set( $key, $value ) {
			$this->resetHit( $key );
			
			$this->data[ $key ] = $value;
			
			return $this;
		}
		
		protected function resetHit( $key ) {
			$this->hitTimes[ $key ] = 0;
		}
		
		/**
		 * 统一getter
		 *
		 * - 1、获取指定service的值，并根据其原型分不同情况创建
		 * - 2、首次创建时，如果service级的构造函数可调用，则调用
		 * - 3、每次获取时，如果非共享且service级的初始化函数可调用，则调用
		 *
		 * @param string $key     service注册名称，要求唯一，区分大小写
		 * @param mixed  $default service不存在时的默认值
		 *
		 * @return mixed 没有此服务时返回NULL
		 */
		public function get( $key, $default = null ) {
			if ( ! isset( $this->data[ $key ] ) ) {
				$this->data[ $key ] = $default;
			}
			
			$this->recordHitTimes( $key );
			
			if ( $this->isFirstHit( $key ) ) {
				$this->data[ $key ] = $this->initService( $this->data[ $key ] );
			}
			
			return $this->data[ $key ];
		}
		
		protected function recordHitTimes( $key ) {
			if ( ! isset( $this->hitTimes[ $key ] ) ) {
				$this->hitTimes[ $key ] = 0;
			}
			
			$this->hitTimes[ $key ] ++;
		}
		
		protected function isFirstHit( $key ) {
			return $this->hitTimes[ $key ] == 1;
		}
		
		/** ------------------ 内部方法 ------------------ */
		
		/***
		 * @param mixed $config
		 *
		 * @return mixed|null|string
		 */
		protected function initService( $config ) {
			$rs = null;
			
			if ( $config instanceOf Closure ) {
				$rs = $config();
			} elseif ( is_string( $config ) && class_exists( $config ) ) {
				$rs = new $config();
				if ( is_callable( [ $rs, 'onInitialize' ] ) ) {
					call_user_func( [ $rs, 'onInitialize' ] );
				}
			} else {
				$rs = $config;
			}
			
			return $rs;
		}
		
		public function __get( $name ) {
			return $this->get( $name, null );
		}
		
		public function __set( $name, $value ) {
			$this->set( $name, $value );
		}
		
		/** ------------------ ArrayAccess（数组式访问）接口 ------------------ */
		
		/**
		 * @param mixed $offset
		 * @param mixed $value
		 */
		
		public function offsetSet( $offset, $value ) {
			$this->set( $offset, $value );
		}
		
		/**
		 * @param mixed $offset
		 *
		 * @return mixed
		 */
		public function offsetGet( $offset ) {
			return $this->get( $offset, null );
		}
		
		/**
		 * @param mixed $offset
		 */
		public function offsetUnset( $offset ) {
			unset( $this->data[ $offset ] );
		}
		
		/**
		 * @param mixed $offset
		 *
		 * @return bool
		 */
		public function offsetExists( $offset ) {
			return isset( $this->data[ $offset ] );
		}
		
		/**
		 * 禁止clone对象
		 */
		public function __clone() {
			@trigger_error( 'Cloning ' . __CLASS__ . ' is not allowed.', E_USER_ERROR );
		}
		
		/**
		 * 禁止反序列化对象
		 */
		public function __wakeup() {
			@trigger_error( 'Unserializing ' . __CLASS__ . ' is not allowed.', E_USER_ERROR );
		}
	}

