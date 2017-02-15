<?php
	namespace PhalApi\Tests;
	
	use PhalApi\Tests\Translator\TranslatorMock;
	use PhalApi\Translator;
	use PHPUnit\Framework\TestCase;
	
	/**
	 * PhpUnderControl_PhalApiTranslator_Test
	 *
	 * 针对 ../PhalApi/Translator.php Translator 类的PHPUnit单元测试
	 *
	 * @author: dogstar 20150201
	 */
	class TranslatorTest extends TestCase {
		public $coreTranslator;
		
		/**
		 * @group testGet
		 */
		public function testGet() {
			Translator::setLanguage( 'zh_cn' );
			
			$this->assertEquals( '用户不存在', Translator::get( 'user not exists' ) );
			
			$this->assertEquals( 'PHPUnit您好，欢迎使用PhalApi！', Translator::get( 'Hello {name}, Welcome to use PhalApi!', [ 'name' => 'PHPUnit' ] ) );
			
			$this->assertEquals( 'PhalApi 我爱你', Translator::get( '{0} I love you', [ 'PhalApi' ] ) );
			$this->assertEquals( 'PhalApi 我爱你因为no reasons', Translator::get( '{0} I love you because {1}', [
				'PhalApi',
				'no reasons',
			] ) );
		}
		
		/**
		 * @group testSetLanguage
		 */
		public function testSetLanguage() {
			$language = 'en';
			
			$rs = Translator::setLanguage( $language );
		}
		
		/**
		 * @group testFormatVar
		 */
		public function testFormatVar() {
			$name = 'abc';
			
			$rs = Translator::formatVar( $name );
			
			$this->assertEquals( '{abc}', $rs );
		}
		
		public function testAddMessage() {
			Translator::setLanguage( 'zh_cn' );
			Translator::addMessage( __DIR__ . '/Data' );
			
			$this->assertEquals( 'this is a good way', Translator::get( 'test' ) );
		}
		
		public function testGetWithNoLanguageSet() {
			TranslatorMock::setLanguageNameSimple( null );
			
			$rs = Translator::get( 'test' );
			
			Translator::setLanguage( 'zh_cn' );
		}
		
		protected function setUp() {
			parent::setUp();
			
			$this->coreTranslator = new Translator();
		}
		
		protected function tearDown() {
		}
	}
	