<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/2
 * Time: 14:42
 */
require_once '../function.php';

use QL\QueryList;

class novelCommand
{
    /**
     * 采集小说章节
     */
    public function chapter()
    {
        $url = 'http://mianzhuan.wddsnxn.org/';
        $html = file_get_contents($url);

        //采集规则
        $rules = [
            'title' => ['.booklist span a','text'],
            'link' => ['.booklist span a','href']
        ];

        $data = QueryList::html($html)->rules($rules)->query()->getData();

        de($data->all());
    }

    /**
     * 采集小说章节详情
     */
    public function details()
    {
        $url = 'http://mianzhuan.wddsnxn.org/244.html';
        $html = file_get_contents($url);

        //采集规则
        $rules = [
            'content' => ['#BookText','html','-script']
        ];

        $data = QueryList::html($html)->rules($rules)->query()->getData();

        de($data->all());
    }
}