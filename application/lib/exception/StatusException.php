<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 19:53
 */

namespace app\lib\exception;


class StatusException extends BaseException
{
    public $code = 401;
    public $msg = '账号处于限制状态，无法进行操作';
    public $errorCode = 30000;
}