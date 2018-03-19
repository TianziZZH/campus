<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 14:31
 */

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['id','img_id','banner_id','update_time','delete_time','source','extend'];

    public function img()
    {
        return $this->belongsTo('Image','img_id','id');
    }

}