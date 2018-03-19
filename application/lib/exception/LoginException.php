<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/8
 * Time: 11:46
 */

namespace app\lib\exception;


class LoginException extends BaseException
{
    public $code = 400;
    public $msg = '账号密码错误，请输入正确的账号密码';
    public $errorCode = 20001;
}