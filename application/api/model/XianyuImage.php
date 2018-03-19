<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 20:50
 */

namespace app\api\model;


class XianyuImage extends BaseModel
{
    protected $hidden = ['img_id','xianyu_id','delete_time','create_time','id','order'];

    public function img()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}