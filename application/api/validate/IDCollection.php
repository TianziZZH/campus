<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 10:41
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => 'ids参数必须是以逗号分隔的多个正整数'
    ];

    protected function checkIDs($value)
    {
        $value = explode(',',$value);
        if(empty($value)){
            return false;
        }

        foreach ($value as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }

        return true;
    }
}