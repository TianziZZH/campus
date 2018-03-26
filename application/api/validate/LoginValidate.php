<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/7
 * Time: 20:26
 */

namespace app\api\validate;


class LoginValidate extends BaseValidate
{
    protected $rule = [
        'userid' => 'require',
        'password' => 'require',
        'school' => 'require|isPositiveInteger',
        'nickname' => 'require',
        'avatar' => 'require'
    ];

    protected $message = [
        'userid' => '学号不能为空',
        'password' => '密码不能为空',
        'school' => '学校选择不能为空',
        'nickname' => '微信名不能为空',
        'avatar' => '微信头像不能为空'
    ];
}