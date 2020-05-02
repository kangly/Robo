<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/2
 * Time: 16:43
 */
require_once '../base.php';

use Base\Base;
use QL\QueryList;
use GuzzleHttp\Client;

/**
 * 测试(例子)
 * 运行方法: robo run --load-from /path/to/my/other/project
 *
 * Robo:https://robo.li
 * Medoo:https://medoo.lvtao.net/
 * QueryList:https://doc.querylist.cc
 * GuzzleHttp:https://guzzle-cn.readthedocs.io
 * PhpSpreadsheet:https://phpspreadsheet.readthedocs.io
 *
 * Class testCommand
 */
class testCommand
{
    /**
     * 在当前目录,hello是方法,worlds是参数(参数可为空)
     * robo hello worlds --load-from TestCommand.php
     *
     * 在上级目录,hello是方法,worlds是参数(参数可为空)
     * robo hello worlds --load-from app/TestCommand.php
     * @param string $world
     */
    public function hello($world = 'world')
    {
        de('Hello '.$world);
    }

    /**
     * robo sql --load-from TestCommand.php
     * 使用Medoo,需要创建对应sql
     * 参考:https://medoo.lvtao.net/
     */
    public function sql()
    {
        $database = new Base();

        $data = $database->select("admin", [
            "username",
            "nickname"
        ], [
            "id" => 1
        ]);

        de($data);
    }

    /**
     * robo test1 --load-from TestCommand.php
     * robo test1 --load-from app/TestCommand.php
     * 移除页面头部head区域,乱码终极解决方案
     * 采集出现不可解决的乱码问题的时候,可以尝试调用这个方法来解决乱码问题
     * 注意:当调用这个方法后,无法选择页面中head区域里面的内容
     */
    public function test1()
    {
        //$html = file_get_contents('http://www.baidu.com/s?wd=QueryList');

        //使用GuzzleHttp
        $client = new Client();
        $res = $client->request('GET', 'http://www.baidu.com/s?wd=QueryList');
        $html = $res->getBody();

        $ql = QueryList::rules([
            'title'=>array('h3','text'),
            'link'=>array('h3>a','href')
        ]);

        $data = $ql->setHtml($html)->removeHead()->query()->getData();

        de($data);
    }

    /**
     * robo test2 --load-from TestCommand.php
     * robo test2 --load-from app/TestCommand.php
     * 执行采集规则rules,执行完这个方法后才可以用getData()方法获取到采集数据
     */
    public function test2()
    {
        $ql = QueryList::get('http://www.baidu.com/s?wd=QueryList')->rules([
            'title'=>array('h3','text'),
            'link'=>array('h3>a','href')
        ]);

        $data = $ql->query(function($item){
            $item['title'] = $item['title'].' - other string...';
            return $item;
        })->getData();

        de($data->all());
    }

    /**
     * robo test3 --load-from TestCommand.php
     * robo test3 --load-from app/TestCommand.php
     * 静态方法，用于获取QueryList单一实例
     */
    public function test3()
    {
        $ql = QueryList::getInstance();
        $data = $ql->get('http://www.baidu.com/s?wd=QueryList')->find('h3 a')->texts();

        de($data->all());
    }
}