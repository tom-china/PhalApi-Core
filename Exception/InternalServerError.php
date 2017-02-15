<?php
	namespace PhalApi\Exception;
	use PhalApi\Translator\Translator;
	
	/**
	 * InternalServerError 服务器运行异常错误
	 *
	 * @package     PhalApi\Exception
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-02-05
	 */
	class InternalServerError extends BaseException {
		
		public function __construct( $message, $code = 0 ) {
			parent::__construct(
				Translator::get( 'Interal Server Error: {message}', [ 'message' => $message ] ), 500 + $code
			);
		}
	}
