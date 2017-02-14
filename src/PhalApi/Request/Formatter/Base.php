<?php
	namespace PhalApi\Request\Formatter;
	
	use PhalApi\Exception\BadRequest;
	use PhalApi\Exception\InternalServerError;
	use PhalApi\Translator;
	
	/**
	 * Base 公共基类
	 *
	 * - 提供基本的公共功能，便于子类重用
	 *
	 * @package     PhalApi\Request
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-11-07
	 */
	class Base {
		
		/**
		 * 根据范围进行控制
		 */
		protected function filterByRange( $value, $rule ) {
			$this->filterRangeMinLessThanOrEqualsMax( $rule );
			
			$this->filterRangeCheckMin( $value, $rule );
			
			$this->filterRangeCheckMax( $value, $rule );
			
			return $value;
		}
		
		protected function filterRangeMinLessThanOrEqualsMax( $rule ) {
			if ( isset( $rule['min'] ) && isset( $rule['max'] ) && $rule['min'] > $rule['max'] ) {
				throw new InternalServerError(
					Translator::get( 'min should <= max, but now {name} min = {min} and max = {max}',
						[ 'name' => $rule['name'], 'min' => $rule['min'], 'max' => $rule['max'] ] )
				);
			}
		}
		
		protected function filterRangeCheckMin( $value, $rule ) {
			if ( isset( $rule['min'] ) && $value < $rule['min'] ) {
				throw new BadRequest(
					Translator::get( '{name} should >= {min}, but now {name} = {value}',
						[ 'name' => $rule['name'], 'min' => $rule['min'], 'value' => $value ] )
				);
			}
		}
		
		protected function filterRangeCheckMax( $value, $rule ) {
			if ( isset( $rule['max'] ) && $value > $rule['max'] ) {
				throw new BadRequest(
					Translator::get( '{name} should <= {max}, but now {name} = {value}',
						[ 'name' => $rule['name'], 'max' => $rule['max'], 'value' => $value ] )
				);
			}
		}
		
		/**
		 * 格式化枚举类型
		 *
		 * @param string $value 变量值
		 * @param array  $rule  array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
		 *
		 * @throws BadRequest
		 */
		protected function formatEnumValue( $value, $rule ) {
			if ( ! in_array( $value, $rule['range'] ) ) {
				throw new BadRequest(
					Translator::get( '{name} should be in {range}, but now {name} = {value}',
						[ 'name' => $rule['name'], 'range' => implode( '/', $rule['range'] ), 'value' => $value ] )
				);
			}
		}
	}
