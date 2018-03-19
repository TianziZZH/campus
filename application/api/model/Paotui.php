<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 10:07
 */

namespace app\api\model;

use app\api\service\BaseService;
use app\api\service\Token as TokenService;
use app\lib\enum\TaskStatusEnum;
use think\Db;

class Paotui extends BaseModel
{
    protected $hidden = ['pro_id','rec_id','school','status','create_time','delete_time','update_time'];
    protected $autoWriteTimestamp = true;

    public function user()
    {
        return $this->belongsTo('User','pro_id','id');
    }

    public static function show($page=1,$size=15)
    {
        $school = TokenService::getCurrentSchool();
        
        BaseService::judgeschool($school);

        $paotui = self::where('school','=',$school)
                        ->where('status','=',TaskStatusEnum::released)
                        ->with(['user','user.school'])
                        ->order('create_time desc')
                        ->paginate($size,true,['page' => $page]);

        return $paotui;
    }

    public static function findById($id)
    {
        $paotui = self::findBy('id',$id)
                        ->find();

        return $paotui;
    }

    public static function findBy($field,$data,$op='=')
    {
        $paotui = self::where($field,$op,$data);

        return $paotui;
    }

    public static function fingByProID($id)
    {
        $paotui = self::findBy('pro_id',$id)
                        ->order('create_time desc')
                        ->limit(15)
                        ->select();

        return $paotui;
    }

    public static function findByRecID($id,$page=1,$size=15)
    {
        $paotui = self::findBy('rec_id',$id)
                        ->order('create_time desc')
                        ->paginate($size,true,['page' => $page]);
        
        return $paotui;
    }


}