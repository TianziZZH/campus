<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/14
 * Time: 19:10
 */

namespace app\lib\exception;


class InformationException extends BaseException
{
    public $code = 404;
    public $msg = '消息id不存在';
    public $errorCode = '90000';
}