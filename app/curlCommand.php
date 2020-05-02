<?php
require_once '../base.php';

use Base\Base;
use QL\QueryList;
use GuzzleHttp\Client;

class curlCommand
{
    public function run()
    {
        $client = new Client();
        $res = $client->request('GET', 'http://www.baidu.com/s?wd=QueryList');
        $html = $res->getBody();

        $ql = QueryList::rules([
            'title'=>array('h3','text'),
            'link'=>array('h3>a','href')
        ]);

        $data = $ql->setHtml($html)->removeHead()->query()->getData();
    }
}