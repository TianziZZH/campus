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
        'school' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'userid' => '学号不能为空',
        'password' => '密码不能为空',
        'school' => '学校选择不能为空',
    ];
}