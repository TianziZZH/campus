<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/29
 * Time: 22:26
 */

namespace app\api\validate;


class TestValidate extends BaseValidate
{
    protected $rule = [
      'id' => 'require'
    ];

    protected $message = [
        'id' => 'id不能为空'
    ];
}