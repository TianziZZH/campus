<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 14:50
 */

namespace app\api\model;


class Image extends BaseModel
{
    protected $hidden = ['id','from','delete_time','update_time'];

    public function getUrlAttr($vale,$data)
    {
        return $this->prefixImageUrl($vale,$data);
    }
}