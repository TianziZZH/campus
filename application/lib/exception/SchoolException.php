<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/14
 * Time: 15:08
 */

namespace app\lib\exception;


class SchoolException extends BaseException
{
    public $code = 404;
    public $msg = '学校尚未开通';
    public $errorCode = 80000;
}