<?php
	//	namespace PhalApi\Tests\Helper;
	//	use PhalApi\Api;
	//	use PhalApi\Helper\ApiDesc;
	//	use function PhalApi\Helper\DI;
	//	use PhalApi\Request;
	//	use PHPUnit\Framework\TestCase;
	///**
	// * PhpUnderControl_PhalApiHelperApiDesc_Test
	// *
	// * 针对 ../../PhalApi/Helper/ApiDesc.php PhalApi_Helper_ApiDesc 类的PHPUnit单元测试
	// *
	// * @author: dogstar 20150530
	// */
	//
	//DI()->loader->addDirs(__DIR__ . '/../../../Demo');
	//
	//class ApiDescTest extends TestCase
	//{
	//    public $phalApiHelperApiDesc;
	//
	//    protected function setUp()
	//    {
	//        parent::setUp();
	//
	//        $this->phalApiHelperApiDesc = new ApiDesc();
	//    }
	//
	//    protected function tearDown()
	//    {
	//    }
	//
	//
	//    /**
	//     * @group testRender
	//     */
	//    public function testRenderDefault()
	//    {
	//        DI()->request = new Request(array());
	//        $rs = $this->phalApiHelperApiDesc->render();
	//
	//        $this->expectOutputRegex("/Default.Index/");
	//    }
	//
	//    public function testRenderError()
	//    {
	//        DI()->request = new Request(array('service' => 'NoThisClass.NoThisMethod'));
	//        $rs = $this->phalApiHelperApiDesc->render();
	//
	//        $this->expectOutputRegex("/NoThisClass.NoThisMethod/");
	//    }
	//
	//    public function testRenderNormal()
	//    {
	//        DI()->request = new Request(array('service' => 'Helper_User_Mock.GetBaseInfo'));
	//        $rs = $this->phalApiHelperApiDesc->render();
	//
	//        $this->expectOutputRegex("/Helper_User_Mock.GetBaseInfo/");
	//    }
	//}
	//
	//class Api_Helper_User_Mock extends Api {
	//
	//    /**
	//     * @param int user_id ID
	//     * @return int code sth...
	//     */
	//    public function getBaseInfo() {
	//    }
	//}
