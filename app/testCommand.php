<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/2
 * Time: 16:43
 */
require_once '../function.php';

use QL\QueryList;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * 测试程序
 * Class testCommand
 */
class testCommand
{
    //运行方法
    //robo run --load-from /path/to/my/other/project

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

    //下面是一些采集小例子
    //querylist参考文档地址:https://doc.querylist.cc

    /**
     * 移除页面头部head区域,乱码终极解决方案
     * 采集出现不可解决的乱码问题的时候,可以尝试调用这个方法来解决乱码问题
     * 注意:当调用这个方法后,无法选择页面中head区域里面的内容
     * robo test1 --load-from TestCommand.php
     * robo test1 --load-from app/TestCommand.php
     */
    public function test1()
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
     * 执行采集规则rules,执行完这个方法后才可以用getData()方法获取到采集数据
     * robo test2 --load-from TestCommand.php
     * robo test2 --load-from app/TestCommand.php
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
     * 静态方法，用于获取QueryList单一实例
     * robo test3 --load-from TestCommand.php
     * robo test3 --load-from app/TestCommand.php
     */
    public function test3()
    {
        $ql = QueryList::getInstance();
        $data = $ql->get('http://www.baidu.com/s?wd=QueryList')->find('h3 a')->texts();

        de($data->all());
    }

    /**
     * 简单测试生成excel文件
     * robo test4 --load-from TestCommand.php
     * robo test4 --load-from app/TestCommand.php
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function test4()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //设置文件名称
        $title = '采集企业信息';
        $sheet->setTitle($title);

        //设置表头名称
        $sheet->setCellValue('A1', '企业名称');
        $sheet->setCellValue('B1', '手机');
        $sheet->setCellValue('C1', '联系人');
        $sheet->setCellValue('D1', '企业分类');
        $sheet->setCellValue('E1', '企业规模');
        $sheet->setCellValue('F1', '地区');

        $url = '../test/1.html';
        $content = file_get_contents($url);

        $ql = QueryList::html($content)
            ->rules([
                'table' => array('table:last','html','-tr:eq(0) -tr:eq(1) -tr:last')
            ])
            ->query()
            ->getData();

        $info = $ql->all();
        $table_info = $info[0]['table'];

        $ql2 = QueryList::html($table_info)
            ->rules([
                'title' => array('td:eq(2)','text','',function($content){
                    $content = str_replace(['[资]','[供]'],'',$content);
                    return $content;
                }),
                'phone' => array('td:eq(4)','text'),
                'contact' => array('td:eq(6)','text'),
                'type' => array('td:eq(7)','text'),
                'scale' => array('td:eq(9)','text'),
                'address' => array('td:eq(10)','text')
            ])
            ->range('tr')
            ->query()
            ->getData();

        $data = $ql2->all();

        foreach($data as $k=>$v)
        {
            $sheet->setCellValue('A'.($k+2), $v['title']);
            $sheet->setCellValue('B'.($k+2), $v['phone']);
            $sheet->setCellValue('C'.($k+2), $v['contact']);
            $sheet->setCellValue('D'.($k+2), $v['type']);
            $sheet->setCellValue('E'.($k+2), $v['scale']);
            $sheet->setCellValue('F'.($k+2), $v['address']);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('../test/'.$title.'.xlsx');
    }
}