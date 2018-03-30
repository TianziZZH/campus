<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 9:13
 */

namespace app\api\model;


use app\api\service\BaseService;

class Information extends BaseModel
{
    public function img()
    {
        return $this->belongsTo('Image','headimg','id');
    }

    public static function show($school,$page=1,$size=15)
    {
        BaseService::judgeschool($school);
        
        $information = self::with('img')
                            ->where('school','=',$school)
                            ->order('id desc')
                            ->paginate($size,true,['page' => $page]);

        return $information;
    }
    
    public static function showByID($id)
    {
        $information = self::with('img')
                            ->where('id','=',$id)
                            ->find();
        
        return $information;
    }
}