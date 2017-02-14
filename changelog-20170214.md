* 基于release v1.3.5
* PhalApi\Request\Var重构为PhalApi\Request\RequestVar(关键字冲突)
* PhalApi\Request\Formatter下的类名统一重构为XXFormatter(XX为原文件名)
* PhalApi\Response\JsonP类重构为Jsonp
* PhalApi\CUrl类重构为Curl
* PhalApi\Crypt\RSA重构为PhalApi\Crypt\Rsa
* 完善了一些注释
* dirname(__FILE__)替换为__DIR__
* 短数组语法
* 代码格式化
* 先移除代码生成器部分
* 移除NotORM类相关，改用composer引入
* 移除Loader类及单元测试，改用composer进行autoload
* 辅助函数DI()、SL( $language )、T( $msg, $params = [] )重构为PhalApi\Helper，使用PSR-4引入(use function PhalApi\Helper\DI;)
* PhalApi\Db\NotORM重构为ORM(同时DB也改名为Db)