<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/11
 * Time: 15:59
 */

namespace app\api\service;

use app\api\model\Image as ImageModel;
use app\api\model\School;
use app\lib\enum\SchoolEnum;
use app\lib\exception\LoginException;
use app\lib\exception\SchoolException;

class BaseService
{
    public function isNotEmpty($value,$rule='',$data='',$field='')
    {
        if(empty($value))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function saveImage($imgs)
    {
        $list = [];

        foreach ($imgs as $img)
        {
            $list[] = ['url'=>$img];
        }

        $image = new ImageModel();
        $image->saveAll($list);

        $ids = [];

        foreach ($imgs as $img)
        {
            $id = $image->where('url','=',$img)
                        ->find();

            $ids[] = $id->id;
        }

        return $ids;
    }
    
    public static function judgeschool($school)
    {
        $schools = new School();
        
        $s = $schools->where('id','=',$school)
                        ->find();
        
        if(!$s)
        {
            throw new SchoolException();
        }
        
        if($s->id == 0)
        {
            throw new LoginException([
                'msg' => '游客身份访问',
                'errorCode' => '20002'
            ]);
        }
        
        if($s->status == SchoolEnum::unopen)
        {
            throw new SchoolException();
        }
    }

}