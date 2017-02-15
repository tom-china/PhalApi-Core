<?php
	
	
	namespace PhalApi\Tests\Translator;
	
	
	use PhalApi\Translator;
	
	class TranslatorMock extends Translator {
		
		public static function setLanguageNameSimple( $lan ) {
			Translator::$message = null;
		}
	}