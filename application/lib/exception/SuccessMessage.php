<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/8
 * Time: 21:46
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;
}