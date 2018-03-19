<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/14
 * Time: 14:10
 */

namespace app\api\validate;


class JudgeValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'grade' => 'require|judgeNumeric'
    ];
    
    protected $message = [
        'id' => '任务id不符合要求或者为空',
        'grade' => '分数不符合要求',
    ];
}