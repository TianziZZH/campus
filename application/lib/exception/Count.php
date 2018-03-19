<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 20:20
 */

namespace app\lib\exception;


use app\api\validate\BaseValidate;

class Count extends BaseValidate
{
    protected $message = [
        'count' => 'count必须时1到15之间的整数'
    ];

    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15'
    ];
}