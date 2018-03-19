<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 19:58
 */

namespace app\api\model;


use app\api\service\BaseService;
use app\lib\enum\XianyuEnum;
use app\api\service\Token as ToeknService;

class Xianyu extends BaseModel
{
    protected $hidden = ['update_time','delete_time','extend','headimg'];
    protected $autoWriteTimestamp = true;

    public function img()
    {
        return $this->belongsTo('Image','headimg','id');
    }
    
    public function items()
    {
        return $this->hasMany('XianyuImage','xianyu_id','id');
    }
    
    public function user()
    {
        return $this->belongsTo('User','pro_id','id');
    }
    
    public static function getRecent($page,$size)
    {
        $school = ToeknService::getCurrentSchool();
        
        BaseService::judgeschool($school);

        $xianyus = self::where('school','=',$school)
                        ->where('status','=',XianyuEnum::unexpire)
                        ->with(['img','items','items.img','user','user.school'])
                        ->order('create_time desc')
                        ->paginate($size,true,['page'=>$page]);
        
        return $xianyus;
    }

    public static function findById($id)
    {
        $xianyu = self::findBy('id',$id)
                        ->find();

        return $xianyu;
    }

    public static function findBy($field,$data,$op='=')
    {
        $xianyu = self::where($field,$op,$data);

        return $xianyu;
    }

    public static function findByProID($id)
    {
        $xianyu = self::findBy('pro_id',$id)
                        ->with(['img','items','items.img'])
                        ->order('create_time desc')
                        ->limit(5)
                        ->select();

        return $xianyu;
    }
}