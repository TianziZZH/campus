<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 11:24
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token extends BaseController
{
    public function getToken($code = '')
    {
        (new TokenGet()) -> goCheck();

        $ut = new UserToken($code);
        $value = $ut->get();

        return [
            'token' => $value['token'],
            'pass' => $value['pass']
        ];

    }
}