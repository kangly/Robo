<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/2
 * Time: 14:42
 */
require_once '../function.php';

use QL\QueryList;

/**
 * 抓取一本小说
 * Class novelCommand
 */
class novelCommand
{
    //运行方法:
    //robo run --load-from /path/to/my/other/project
    //当前目录:
    //robo chapter --load-from novelCommand
    //上级目录:
    //robo chapter --load-from app/novelCommand

    /**
     * 采集小说章节
     */
    public function chapter()
    {
        $url = 'http://mianzhuan.wddsnxn.org/';
        $html = file_get_contents($url);

        $data = QueryList::html($html)
            ->rules([
                'title' => ['.booklist span a','text'],
                'link' => ['.booklist span a','href']
            ])
            ->query()
            ->getData();

        de($data->all());
    }

    /**
     * 采集小说章节详情
     */
    public function details()
    {
        $url = 'http://mianzhuan.wddsnxn.org/244.html';
        $html = file_get_contents($url);

        $data = QueryList::html($html)
            ->rules([
                'content' => ['#BookText','html','-script']
            ])
            ->query()
            ->getData();

        de($data->all());
    }
}