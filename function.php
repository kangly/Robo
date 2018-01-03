<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/11/8
 * Time: 10:00
 */
require_once 'vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');
ini_set('memory_limit','2048M');

/**
 * 输出
 * @param $str
 */
function de($str){
    print_r($str);
    echo "\n";
}

/**
 * 当前时间
 * @return bool|string
 */
function _time(){
    return date('Y-m-d H:i:s');
}

/**
 * 简单的判断一下参数是否为一个URL链接
 * @param  string  $str
 * @return boolean
 */
function _isURL($str){
    if (preg_match('/^http(s)?:\\/\\/.+/', $str)) {
        return true;
    }
    return false;
}