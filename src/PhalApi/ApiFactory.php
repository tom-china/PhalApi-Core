<?php
	namespace PhalApi;
	
	use PhalApi\Exception\BadRequest;
	use PhalApi\Exception\InternalServerError;
	use function PhalApi\Helper\DI;
	
	/**
	 * ApiFactory 创建控制器类 工厂方法
	 *
	 * 将创建与使用分离，简化客户调用，负责控制器复杂的创建过程
	 *
	 * ```
	 *      //根据请求(?service=XXX.XXX)生成对应的接口服务，并进行初始化
	 *      $api = ApiFactory::generateService();
	 * ```
	 * @package     PhalApi\Api
	 * @license     http://www.phalapi.net/license GPL 协议 GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2014-10-02
	 */
	class ApiFactory {
		
		/**
		 * 创建服务器
		 * 根据客户端提供的接口服务名称和需要调用的方法进行创建工作，如果创建失败，则抛出相应的自定义异常
		 *
		 * 创建过程主要如下：
		 * - 1、 是否缺少控制器名称和需要调用的方法
		 * - 2、 控制器文件是否存在，并且控制器是否存在
		 * - 3、 方法是否可调用
		 * - 4、 控制器是否初始化成功
		 *
		 * @param bool $isInitialize 是否在创建后进行初始化
		 *
		 * @return \PhalApi\Api 自定义的控制器
		 *
		 * @throws \PhalApi\Exception\BadRequest 非法请求下返回400
		 * @throws \PhalApi\Exception\InternalServerError
		 * @internal param string $_REQUEST ['service'] 接口服务名称，格式：XXX.XXX
		 *
		 * @uses     Api::init()
		 */
		static function generateService( $isInitialize = true ) {
			$service = DI()->request->get( 'service', 'Default.Index' );
			
			$serviceArr = explode( '.', $service );
			
			if ( count( $serviceArr ) < 2 ) {
				throw new BadRequest(
					Translator::get( 'service ({service}) illegal', [ 'service' => $service ] )
				);
			}
			
			list ( $apiClassName, $action ) = $serviceArr;
			$apiClassName = 'Api_' . ucfirst( $apiClassName );
			// $action = lcfirst($action);
			
			if ( ! class_exists( $apiClassName ) ) {
				throw new BadRequest(
					Translator::get( 'no such service as {service}', [ 'service' => $service ] )
				);
			}
			
			$api = new $apiClassName();
			
			if ( ! is_subclass_of( $api, 'Api' ) ) {
				throw new InternalServerError(
					Translator::get( '{class} should be subclass of Api', [ 'class' => $apiClassName ] )
				);
			}
			
			if ( ! method_exists( $api, $action ) || ! is_callable( [ $api, $action ] ) ) {
				throw new BadRequest(
					Translator::get( 'no such service as {service}', [ 'service' => $service ] )
				);
			}
			
			if ( $isInitialize ) {
				$api->init();
			}
			
			return $api;
		}
		
	}
