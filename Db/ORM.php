<?php
	namespace PhalApi\Db;
	
	use PDO;
	use PDOException;
	use PhalApi\Exception\InternalServerError;
	use PhalApi\NotORM\NotORM;
	use PhalApi\NotORM\StructureConvention;
	use function PhalApi\Helper\DI;
	use PhalApi\Translator\Translator;
	
	/**
	 * NotORM 分布式的DB存储
	 *
	 * 基于NotORM的数据库操作，支持分布式
	 *
	 * - 可定义每个表的存储路由和规则，匹配顺序：
	 *   自定义区间匹配 -> 自定义缺省匹配 -> 默认区间匹配 -> 默认缺省匹配
	 * - 底层依赖NotORM实现数据库的操作
	 *
	 * <br>使用示例：<br>
	 * ```
	 *      //需要提供以下格式的DB配置
	 *      $config = array(
	 *        //可用的DB服务器集群
	 *       'servers' => array(
	 *          'db_demo' => array(
	 *              'host'      => 'localhost',             //数据库域名
	 *              'name'      => 'phalapi',               //数据库名字
	 *              'user'      => 'root',                  //数据库用户名
	 *              'password'  => '',                        //数据库密码
	 *              'port'      => '3306',                    //数据库端口
	 *              'charset'   => 'UTF8',                  //数据库字符集
	 *          ),
	 *       ),
	 *
	 *        //自定义表的存储路由
	 *       'tables' => array(
	 *           '__default__' => array(                                            //默认
	 *               'prefix' => 'tbl_',
	 *               'key' => 'id',
	 *               'map' => array(
	 *                   array('db' => 'db_demo'),                                  //默认缺省
	 *                   array('start' => 0, 'end' => 2, 'db' => 'db_demo'),        //默认区间
	 *               ),
	 *           ),
	 *           'demo' => array(                                                   //自定义
	 *               'prefix' => 'tbl_',
	 *               'key' => 'id',
	 *               'map' => array(
	 *                   array('db' => 'db_demo'),                                  //自定义缺省
	 *                   array('start' => 0, 'end' => 2, 'db' => 'db_demo'),        //定义区间
	 *               ),
	 *           ),
	 *       ),
	 *      );
	 *
	 *      $notorm = new NotORM($config);
	 *
	 *      //根据ID对3取模的映射获取数据
	 *      $rs = $notorm->demo_0->select('*')->where('id', 10)->fetch();
	 *      $rs = $notorm->demo_1->select('*')->where('id', 11)->fetch();
	 * ```
	 *
	 * @property string table_name 数据库表名
	 *
	 * @package     PhalApi\Db
	 * @link        http://www.notorm.com/
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2014-11-22
	 */
	class ORM {
		
		/**
		 * @var $_notorms NotORM NotORM的实例池
		 */
		protected $_notorms = [];
		
		/**
		 * @var PDO $_pdos PDO连接池，统一管理，避免重复连接
		 */
		protected $_pdos = [];
		
		/**
		 * @var array $_configs 数据库配置
		 */
		protected $_configs = [];
		
		/**
		 * @var boolean 是否开启调试模式，调试模式下会输出全部执行的SQL语句和对应消耗的时间
		 */
		protected $debug = false;
		
		/**
		 * @var boolean 是否保持原来数据库结果集中以主键为KEY的返回方式（默认不使用）
		 */
		protected $isKeepPrimaryKeyIndex = false;
		
		/**
		 * @param array   $configs 数据库配置
		 * @param boolean $debug   是否开启调试模式
		 */
		public function __construct( $configs, $debug = false ) {
			$this->_configs = $configs;
			
			$this->debug = $debug;
		}
		
		public function __get( $name ) {
			$notormKey = $this->createNotormKey( $name );
			
			if ( ! isset( $this->_notorms[ $notormKey ] ) ) {
				list( $tableName, $suffix ) = $this->parseName( $name );
				$router = $this->getDBRouter( $tableName, $suffix );
				
				$structure                    = new StructureConvention(
					$router['key'], '%s_id', '%s', $router['prefix'] );
				$this->_notorms[ $notormKey ] = new NotORM( $router['pdo'], $structure );
				
				$this->_notorms[ $notormKey ]->debug                 = $this->debug;
				$this->_notorms[ $notormKey ]->isKeepPrimaryKeyIndex = $this->isKeepPrimaryKeyIndex;
				
				if ( $router['isNoSuffix'] ) {
					$name = $tableName;
				}
			}
			
			return $this->_notorms[ $notormKey ]->$name;
		}
		
		public function __set( $name, $value ) {
			foreach ( $this->_notorms as $key => $notorm ) {
				$notorm->$name = $value;
			}
		}
		
		protected function createNotormKey( $tableName ) {
			return '__' . $tableName . '__';
		}
		
		/**
		 * 解析分布式表名
		 * 表名  + ['_' + 数字后缀]，如：user_0, user_1, ... user_100
		 *
		 * @param string $name
		 *
		 * @return array
		 */
		protected function parseName( $name ) {
			$tableName = $name;
			$suffix    = null;
			
			$pos = strrpos( $name, '_' );
			if ( $pos !== false ) {
				$tableId = substr( $name, $pos + 1 );
				if ( is_numeric( $tableId ) ) {
					$tableName = substr( $name, 0, $pos );
					$suffix    = intval( $tableId );
				}
			}
			
			return [ $tableName, $suffix ];
		}
		
		/**
		 * 获取分布式数据库路由
		 *
		 * @param string $tableName 数据库表名
		 * @param string $suffix    分布式下的表后缀
		 *
		 * @return array 数据库配置
		 * @throws InternalServerError
		 */
		protected function getDBRouter( $tableName, $suffix ) {
			$rs = [ 'prefix' => '', 'key' => '', 'pdo' => null, 'isNoSuffix' => false ];
			
			$defaultMap = ! empty( $this->_configs['tables']['__default__'] )
				? $this->_configs['tables']['__default__'] : [];
			$tableMap   = ! empty( $this->_configs['tables'][ $tableName ] )
				? $this->_configs['tables'][ $tableName ] : $defaultMap;
			
			if ( empty( $tableMap ) ) {
				throw new InternalServerError(
					Translator::get( 'No table map config for {tableName}', [ 'tableName' => $tableName ] )
				);
			}
			
			$dbKey        = null;
			$dbDefaultKey = null;
			if ( ! isset( $tableMap['map'] ) ) {
				$tableMap['map'] = [];
			}
			foreach ( $tableMap['map'] as $map ) {
				$isMatch = false;
				
				if ( ( isset( $map['start'] ) && isset( $map['end'] ) ) ) {
					if ( $suffix !== null && $suffix >= $map['start'] && $suffix <= $map['end'] ) {
						$isMatch = true;
					}
				} else {
					$dbDefaultKey = $map['db'];
					if ( $suffix === null ) {
						$isMatch = true;
					}
				}
				
				if ( $isMatch ) {
					$dbKey = isset( $map['db'] ) ? trim( $map['db'] ) : null;
					break;
				}
			}
			//try to use default map if no perfect match
			if ( $dbKey === null ) {
				$dbKey            = $dbDefaultKey;
				$rs['isNoSuffix'] = true;
			}
			
			if ( $dbKey === null ) {
				throw new InternalServerError(
					Translator::get( 'No db router match for {tableName}', [ 'tableName' => $tableName ] )
				);
			}
			
			$rs['pdo']    = $this->getPdo( $dbKey );
			$rs['prefix'] = isset( $tableMap['prefix'] ) ? trim( $tableMap['prefix'] ) : '';
			$rs['key']    = isset( $tableMap['key'] ) ? trim( $tableMap['key'] ) : 'id';
			
			return $rs;
		}
		
		/**
		 * 获取 PDO连接
		 *
		 * @param string $dbKey 数据库表名唯一KEY
		 *
		 * @return \PDO
		 * @throws \PhalApi\Exception\InternalServerError
		 */
		protected function getPdo( $dbKey ) {
			if ( ! isset( $this->_pdos[ $dbKey ] ) ) {
				$dbCfg = isset( $this->_configs['servers'][ $dbKey ] )
					? $this->_configs['servers'][ $dbKey ] : [];
				
				if ( empty( $dbCfg ) ) {
					throw new InternalServerError(
						Translator::get( 'no such db:{db} in servers', [ 'db' => $dbKey ] ) );
				}
				
				try {
					$this->_pdos[ $dbKey ] = $this->createPDOBy( $dbCfg );
				} catch ( PDOException $ex ) {
					//异常时，接口异常返回，并隐藏数据库帐号信息
					$errorMsg = Translator::get( 'can not connect to database: {db}', [ 'db' => $dbKey ] );
					if ( DI()->debug ) {
						$errorMsg = Translator::get( 'can not connect to database: {db}, code: {code}, cause: {msg}',
							[ 'db' => $dbKey, 'code' => $ex->getCode(), 'msg' => $ex->getMessage() ] );
					}
					throw new InternalServerError( $errorMsg );
				}
			}
			
			return $this->_pdos[ $dbKey ];
		}
		
		/**
		 * 针对MySQL的PDO链接，如果需要采用其他数据库，可重载此函数
		 *
		 * @param array $dbCfg 数据库配置
		 *
		 * @return PDO
		 */
		protected function createPDOBy( array $dbCfg ) {
			$dsn     = sprintf( 'mysql:dbname=%s;host=%s;port=%d',
				$dbCfg['name'],
				isset( $dbCfg['host'] ) ? $dbCfg['host'] : 'localhost',
				isset( $dbCfg['port'] ) ? $dbCfg['port'] : 3306
			);
			$charset = isset( $dbCfg['charset'] ) ? $dbCfg['charset'] : 'UTF8';
			
			$pdo = new PDO(
				$dsn,
				$dbCfg['user'],
				$dbCfg['password']
			);
			$pdo->exec( "SET NAMES '{$charset}'" );
			
			return $pdo;
		}
		
		/**
		 * 断开数据库链接
		 */
		public function disconnect() {
			foreach ( $this->_pdos as $dbKey => $pdo ) {
				$this->_pdos[ $dbKey ] = null;
				unset( $this->_pdos[ $dbKey ] );
			}
			
			foreach ( $this->_notorms as $notormKey => $notorm ) {
				$this->_notorms[ $notormKey ] = null;
				unset( $this->_notorms[ $notormKey ] );
			}
		}
		
		/**
		 * 为历史修改埋单：保持原来数据库结果集中以主键为KEY的返回方式
		 *
		 * - PhalSpi 1.3.1 及以下版本才需要用到此切换动作
		 * - 涉及影响的数据库操作有：fetchAll()/fetchRows()等
		 *
		 * @return $this
		 */
		public function keepPrimaryKeyIndex() {
			$this->isKeepPrimaryKeyIndex = true;
			
			return $this;
		}
		
		/** ------------------ 事务操作 ------------------ **/
		
		/**
		 * 开启数据库事务
		 *
		 * @param string $whichDB 指定数据库标识
		 *
		 * @return bool
		 */
		public function beginTransaction( $whichDB ) {
			$this->getPdo( $whichDB )->beginTransaction();
		}
		
		/**
		 * 提交数据库事务
		 *
		 * @param string $whichDB 指定数据库标识
		 *
		 * @return bool
		 */
		public function commit( $whichDB ) {
			$this->getPdo( $whichDB )->commit();
		}
		
		/**
		 * 回滚数据库事务
		 *
		 * @param string $whichDB 指定数据库标识
		 *
		 * @return bool
		 */
		public function rollback( $whichDB ) {
			$this->getPdo( $whichDB )->rollback();
		}
	}
