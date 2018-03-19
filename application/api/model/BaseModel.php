<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 14:53
 */

namespace app\api\model;


use app\lib\enum\ImageFromEnum;
use think\Model;

class BaseModel extends Model
{
    protected function prefixImageUrl($value,$data)
    {
        $finalUrl = $value;

        if($data['from'] == ImageFromEnum::LocalServer){
            $finalUrl = config('setting.img_prefix').$value;
        }

        return $finalUrl;
    }
}