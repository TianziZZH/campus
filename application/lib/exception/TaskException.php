<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/30
 * Time: 10:43
 */

namespace app\lib\exception;


class TaskException extends BaseException
{
    public $code = 401;
    public $msg = '任务不存在';
    public $errorCode = 60001;
}