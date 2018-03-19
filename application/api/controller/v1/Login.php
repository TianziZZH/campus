<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/7
 * Time: 19:59
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Login as LoginService;
use app\api\validate\LoginValidate;

class Login extends BaseController
{

    public function userLogin()
    {
        $validate = (new LoginValidate());
        $validate->goCheck();
        $dataArray = $validate->getDataByRule(input('post.'));

        $token = LoginService::login($dataArray);

        return [
            'token' => $token
        ];
    }

    public function login()
    {
        $token = LoginService::changCacheScope(2);

        return [
            'token' => $token,
        ];
    }
}
