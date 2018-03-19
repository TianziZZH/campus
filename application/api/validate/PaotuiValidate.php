<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 10:19
 */

namespace app\api\validate;


class PaotuiValidate extends BaseValidate
{
    protected $rule = [
        'describe' => 'require',
        'destination' => 'require',
        'price' => 'require|judgeNumeric'
    ];

    protected $message = [
        'describe' => '描述不能为空',
        'destination' => '目的地不能为空',
        'price' => '价格不符合规范'
    ];
}