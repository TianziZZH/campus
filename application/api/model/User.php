<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 17:22
 */

namespace app\api\model;


class User extends BaseModel
{
    protected $hidden = ['id','openid','extend','study_id','study_password','from','create_time','status','scope','judgeNum'];

    public function getHeadImgAttr($value,$data)
    {
        return $this->prefixImageUrl($value,$data);
    }

    public function school()
    {
        return $this->belongsTo('School','school','id');
    }

    public static function getByOpenID($openid)
    {
        $user = self::where('openid','=',$openid)
                    ->find();

        return $user;
    }

    public static function getByUid($uid)
    {
        $user = self::where('id','=',$uid)
                    ->find();

        return $user;
    }

    public static function allinformation($uid)
    {
        $user = self::with('school')
                    ->where('id','=',$uid)
                    ->find();
        return $user;
    }
}