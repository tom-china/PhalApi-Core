<?php
	namespace PhalApi\Exception;
	use PhalApi\Translator\Translator;
	
	/**
	 * BadRequest 客户端非法请求
	 *
	 * 客户端非法请求
	 *
	 * @package     PhalApi\Exception
	 * @license     http://www.phalapi.net/license GPL 协议
	 * @link        http://www.phalapi.net/
	 * @author      dogstar <chanzonghuang@gmail.com> 2015-02-05
	 */
	class BadRequest extends BaseException {
		
		public function __construct( $message, $code = 0 ) {
			parent::__construct(
				Translator::get( 'Bad Request: {message}', [ 'message' => $message ] ), 400 + $code
			);
		}
	}
