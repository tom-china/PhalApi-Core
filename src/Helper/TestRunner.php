<?php
	namespace PhalApi\Helper;
	
	use PhalApi\ApiFactory;
	use PhalApi\Exception;
	use PhalApi\Request;
	
	/**
	 * TestRunner - 快速接口执行 - 辅助类
	 *
	 * - 使用示例：
	 * ```
	 * public function testWhatever() {
	 *        //Step 1. 构建请求URL
	 *        $url = 'service=Default.Index&username=dogstar';
	 *
	 *        //Step 2. 执行请求
	 *        $rs = TestRunner::go($url);
	 *
	 *        //Step 3. 验证
	 *        $this->assertNotEmpty($rs);
	 *        $this->assertArrayHasKey('code', $rs);
	 *        $this->assertArrayHasKey('msg', $rs);
	 * }
	 * ```
	 *
	 * @package     PhalApi\Helper
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-05-30
	 */
	class TestRunner {
		
		/**
		 * @param string $url 请求的链接
		 * @param array  $params
		 *
		 * @return array 接口的返回结果
		 * @throws \PhalApi\Exception
		 * @internal param array $param 额外POST的数据
		 *
		 */
		public static function go( $url, $params = [] ) {
			parse_str( $url, $urlParams );
			$params = array_merge( $urlParams, $params );
			
			if ( ! isset( $params['service'] ) ) {
				throw new Exception( 'miss service in url' );
			}
			DI()->request = new Request( $params );
			
			$apiObj = ApiFactory::generateService( true );
			list( $api, $action ) = explode( '.', $urlParams['service'] );
			
			$rs = $apiObj->$action();
			
			/**
			 * $this->assertNotEmpty($rs);
			 * $this->assertArrayHasKey('code', $rs);
			 * $this->assertArrayHasKey('msg', $rs);
			 */
			
			return $rs;
		}
	}

