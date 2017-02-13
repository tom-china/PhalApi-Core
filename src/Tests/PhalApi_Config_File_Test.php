<?php
/**
 * PhpUnderControl_PhalApiConfigFile_Test
 *
 * 针对 ../PhalApi/Config/File.php PhalApi_Config_File 类的PHPUnit单元测试
 *
 * @author: dogstar 20141004
 */

require_once __DIR__ . '/test_env.php';

if (!class_exists('PhalApi_Config_File')) {
    require __DIR__ . '/../PhalApi/Config/File.php';
}

class PhpUnderControl_PhalApiConfigFile_Test extends PHPUnit_Framework_TestCase
{
    public $coreConfigFile;

    protected function setUp()
    {
        parent::setUp();

        $this->coreConfigFile = DI()->config;
    }

    protected function tearDown()
    {
    }

    public function testConstruct()
    {
        $config = new PhalApi_Config_File(__DIR__ . '/Config');
    }

    /**
     * @group testGet
     */ 
    public function testGetDefault()
    {
        $key = 'sys.noThisKey';
        $default = 2014;

        $rs = $this->coreConfigFile->get($key, $default);

        $this->assertSame($default, $rs);
    }

    public function testGetNormal()
    {
        $key = 'sys.debug';

        $rs = $this->coreConfigFile->get($key);

        $this->assertFalse($rs);
    }

    public function testGetAll()
    {
        $key = 'dbs';

        $rs = $this->coreConfigFile->get($key);

        $this->assertTrue(is_array($rs));
    }
}
