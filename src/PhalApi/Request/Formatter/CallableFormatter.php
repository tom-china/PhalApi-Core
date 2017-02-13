<?php
	namespace PhalApi\Request\Formatter;
	
	use PhalApi\Exception\InternalServerError;
	use PhalApi\Request\Formatter;
	
	/**
	 * CallableFormatter 格式化回调类型
	 *
	 * @package     PhalApi\Request\Formatter
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-11-07
	 */
	class CallableFormatter extends Base implements Formatter {
		
		/**
		 * 对回调类型进行格式化
		 *
		 * @param mixed $value 变量值
		 * @param array $rule  array('callback' => '回调函数', 'params' => '第三个参数')
		 *
		 * @return bool /string 格式化后的变量
		 *
		 * @throws \PhalApi\Exception\InternalServerError
		 */
		public function parse( $value, $rule ) {
			if ( ! isset( $rule['callback'] ) || ! is_callable( $rule['callback'] ) ) {
				throw new InternalServerError(
					T( 'invalid callback for rule: {name}', [ 'name' => $rule['name'] ] )
				);
			}
			
			if ( isset( $rule['params'] ) ) {
				return call_user_func( $rule['callback'], $value, $rule, $rule['params'] );
			} else {
				return call_user_func( $rule['callback'], $value, $rule );
			}
		}
	}
