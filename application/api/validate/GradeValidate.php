<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/3
 * Time: 17:46
 */

namespace app\api\validate;


class GradeValidate extends BaseValidate
{
    protected $rule = [
        'grade' => 'require|isPositiveInteger|between:0,5',
        'taskNo' => 'require'
    ];

    protected $message = [
        'grade' => '分数必须是0-5的整数',
        'taskNo' => '任务号不能为空'
    ];
}