<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 14:09
 */

namespace app\lib\exception;


use think\exception\Handle;
use think\Log;
use think\Request;


//所有的exception都会调用这个函数
class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    public function render(\Exception $e)
    {
        if ($e instanceof BaseException)
        {
            //自己定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }
        else
        {
            //不是自己定义的异常

            if(config('app_debug'))
            {
                //处于开发模式
                return parent::render($e);
            }
            else
            {
                //处于上线模式
                $this->code = 500;
                $this->msg = '服务器内部错误，不告诉用户';
                $this->errorCode = 999;
                $this->recodeErrorLog($e);
            }
        }

        $request = Request::instance();

        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];

        return json($result,$this->code);
    }

    public function recodeErrorLog(\Exception $e)
    {
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);

        Log::record($e->getMessage(),'error');
    }

}