<?php
	namespace PhalApi\Model;
	
	use function PhalApi\Helper\DI;
	
	/**
	 * ModelQuery 查询对象(值对象)
	 *
	 * - 我们强烈建议应将此继承类的实例当作值对象处理，虽然我们提供了便利的结构化获取
	 * - 如需要拷贝值对象，可以结合使用构造函数和toArray()
	 *
	 * @package     PhalApi\Model
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-02-22
	 */
	class ModelQuery {
		
		/**
		 * @var boolean $readCache 是否读取缓存
		 */
		public $readCache = true;
		
		/**
		 * @var boolean $writeCache 是否写入缓存
		 */
		public $writeCache = true;
		
		/**
		 * @var string/int ID
		 */
		public $id;
		
		/**
		 * @var int $timestamp 时间戳
		 */
		public $timestamp;
		
		public function __construct( array $queryArr = [] ) {
			$this->timestamp = $_SERVER['REQUEST_TIME'];
			
			if ( DI()->debug ) {
				$this->readCache  = false;
				$this->writeCache = false;
			}
			
			foreach ( $queryArr as $key => $value ) {
				$this->$key = $value;
			}
		}
		
		public function __get( $name ) {
			if ( isset( $this->$name ) ) {
				return $this->$name;
			}
			
			return null;
		}
		
		public function __set( $name, $value ) {
			$this->$name = $value;
		}
		
		public function toArray() {
			return get_object_vars( $this );
		}
	}
