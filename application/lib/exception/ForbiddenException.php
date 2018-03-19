<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 11:18
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10002;
}