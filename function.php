<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/11/8
 * Time: 10:00
 */

/**
 * 简单格式化输出
 * @param $str
 */
function de($str){
    print_r($str);
    echo "\n";
}

/**
 * 当前时间(年-月-日 时:分:秒)
 * @return bool|string
 */
function _time(){
    return date('Y-m-d H:i:s');
}

/**
 * 当前时间(年-月-日)
 * @return bool|string
 */
function _day(){
    return date('Y-m-d');
}

/**
 * 简单判断一下参数是否为一个URL链接
 * @param  string  $str
 * @return boolean
 */
function _isURL($str){
    if (preg_match('/^http(s)?:\\/\\/.+/', $str)) {
        return true;
    }
    return false;
}