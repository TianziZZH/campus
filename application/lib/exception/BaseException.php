<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 11:13
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    //Http 状态码 404，200
    public $code = 400;

    //错误信息
    public $msg = '参数错误';

    //自定义的错误码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if(!is_array($params)) {
            return;
            //throw new Exception('参数必须是数组');
        }
        if(array_key_exists('code',$params))
        {
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params))
        {
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params))
        {
            $this->errorCode = $params['errorCode'];
        }
    }
}