<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 19:14
 */

namespace app\api\validate;


class XianyuValidate extends BaseValidate
{
    protected $rule = [
        'title' => 'require',
        'summary' => 'require',
        'detail' => 'require',
        'price' => 'require|judgeNumeric',
        'img' => 'JudgeImage'
    ];
    
    protected $message = [
        'title' => '标题不能为空',
        'summary' => '简介不能为空',
        'detail' => '详情不能为空',
        'price' => '价格不符合规范',
        'img' => '图片不符合规范'
    ];

    protected function judgeImage($value,$rule='',$data='',$field='')
    {
        return true;
    }
}