<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/10/19
 * Time: 17:53
 */
namespace Base;

//设置默认数据
date_default_timezone_set('Asia/Shanghai');
ini_set('memory_limit','2048M');

require_once 'vendor/autoload.php';
require_once 'function.php';
use Medoo\Medoo;
use PDO;

/**
 * 继承Medoo类的功能
 * 需要调用Base基本类来实现功能
 * Class Base
 * @package Base
 */
class Base extends Medoo
{
    /**
     * 调用父类的析构函数
     * Base constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'database_type' => 'mysql',              //sql类型
            'database_name' => 'test',               //数据库名称
            'server' => '127.0.0.1',                 //数据库地址
            'username' => 'root',                    //数据库用户名
            'password' => '123456',                  //数据库密码
            // 可选参数
            'port' => 3306,                          //数据库端口
            'charset' => 'utf8',                     //默认数据库编码
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL, //列名按照原始的方式
                PDO::ATTR_PERSISTENT => true         //默认不是长连接，如果需要数据库长连接，需要最后加一个参数,变成这样：array(PDO::ATTR_PERSISTENT => true)
            ]
        ]);
    }
}