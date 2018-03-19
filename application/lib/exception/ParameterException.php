<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/28
 * Time: 9:27
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}