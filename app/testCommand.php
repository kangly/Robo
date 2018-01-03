<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/2
 * Time: 16:43
 */
require_once '../function.php';

use QL\QueryList;

class testCommand
{
    /**
     * example
     * robo run --load-from /path/to/my/other/project
     *
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
     * 下面是一些采集小例子
     * 参考文档:https://doc.querylist.cc/
     */
    public function test1()
    {
        //采集目标
        $html = <<<STR
<div id="one">
    <div class="two">
        <a href="http://querylist.cc">QueryList官网</a>
        <img src="http://querylist.com/1.jpg" alt="这是图片">
        <img src="http://querylist.com/2.jpg" alt="这是图片2">
    </div>
    <span>其它的<b>一些</b>文本</span>
</div>
STR;

        //采集规则
        $rules = array(
            //采集id为one这个元素里面的纯文本内容
            'text' => array('#one','text'),
            //采集class为two下面的超链接的链接
            'link' => array('.two>a','href'),
            //采集class为two下面的第二张图片的链接
            'img' => array('.two>img:eq(1)','src'),
            //采集span标签中的HTML内容
            'other' => array('span','html')
        );

        // 过程:设置HTML=>设置采集规则=>执行采集=>获取采集结果数据
        $data = QueryList::html($html)->rules($rules)->query()->getData();
        //打印结果
        de($data->all());
    }

    /**
     * 设置待采集的html源码，等价于setHtml($html)
     */
    public function test2()
    {
        $html = file_get_contents('https://querylist.cc/');
        $ql = QueryList::html($html);
        //$ql->setHtml($html);
        $html = $ql->getHtml();
    }

    /**
     * 获取设置的待采集的html源码
     */
    public function test3()
    {
        $html = <<<STR
<div class="two">
        <a href="http://querylist.cc">QueryList官网</a>
        <img src="http://querylist.com/1.jpg" alt="这是图片">
        <img src="http://querylist.com/2.jpg" alt="这是图片2">
    </div>
STR;
        $ql = QueryList::html($html);
        $html = $ql->getHtml();

        echo $html;
    }

    /**
     * 设置采集规则
     */
    public function test4()
    {
        //采集规则
        $rules = array(
            '规则名' => array('jQuery选择器','要采集的属性',["标签过滤列表"],["回调函数"]),
            '规则名2' => array('jQuery选择器','要采集的属性',["标签过滤列表"],["回调函数"]),
            # ..........
        );
        //注:方括号括起来的参数可选


        $html=<<<STR
<div class="content">
    <div>
        <a href="https://querylist.cc/1.html">这是链接一</a>
        <span>这是文字一</span>
    </div>
    <div>
        <a href="https://querylist.cc/2.html">这是链接二</a>
        <span>这是文字二</span>
    </div>
    <div>
        <a href="https://querylist.cc/1.html">这是链接三</a>
        <span>这是文字三</span>
    </div>
</div>
STR;

        //采集规则
        $rules = [
            //采集a标签的href属性
            'link' => ['a','href'],
            //采集a标签的text文本
            'link_text' => ['a','text'],
            //采集span标签的text文本
            'txt' => ['span','text']
        ];

        $ql = QueryList::html($html)->rules($rules)->query();
        $data = $ql->getData();

        de($data->all());
    }

    /**
     * 移除页面头部head区域,乱码终极解决方案，
     * 采集出现不可解决的乱码问题的时候,可以尝试调用这个方法来解决乱码问题
     * 注意:当调用这个方法后，无法选择页面中head区域里面的内容。
     */
    public function test5()
    {
        $html = file_get_contents('http://www.baidu.com/s?wd=QueryList');

        $ql = QueryList::rules([
            'title'=>array('h3','text'),
            'link'=>array('h3>a','href')
        ]);

        $data = $ql->setHtml($html)->removeHead()->query()->getData();

        de($data);
    }

    /**
     * 执行采集规则rules，执行完这个方法后才可以用getData()方法获取到采集数据
     */
    public function test6()
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
     * 获取采集结果数据
     */
    public function test7()
    {
        $html =<<<STR
    <div class="xx">
        <img data-src="/path/to/1.jpg" alt="">
    </div>
    <div class="xx">
        <img data-src="/path/to/2.jpg" alt="">
    </div>
    <div class="xx">
        <img data-src="/path/to/3.jpg" alt="">
    </div>
STR;
        $baseUrl = 'http://xxxx.com';
        $data = QueryList::html($html)->rules(array(
            'image' => array('.xx>img','data-src')
        ))->query()->getData(function($item) use($baseUrl){
            return $baseUrl.$item['image'];
        });

        de($data->all());
    }

    /**
     * 静态方法，用于获取QueryList单一实例
     */
    public function test8()
    {
        $ql = QueryList::getInstance();
        $data = $ql->get('http://www.baidu.com/s?wd=QueryList')->find('h3 a')->texts();

        de($data->all());
    }
}