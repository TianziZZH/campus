<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 11:05
 */

namespace app\lib\exception;


class PaotuiException extends BaseException
{
    public $code = 404;
    public $msg = '未找到任务或已取消';
    public $errorCode = 50000;
}