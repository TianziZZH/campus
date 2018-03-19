<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/11
 * Time: 11:45
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的banner不存在';
    public $errorCode = 40000;
}