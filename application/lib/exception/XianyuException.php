<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 20:47
 */

namespace app\lib\exception;


class XianyuException extends BaseException
{
    public $code = 404;
    public $msg = '指定任务不存在';
    public $errorCode = 40000;
}