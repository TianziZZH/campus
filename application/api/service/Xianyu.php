<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 15:26
 */

namespace app\api\service;

use app\api\service\Token as TokenService;
use app\lib\enum\XianyuEnum;
use app\lib\exception\XianyuException;
use think\Db;
use app\api\model\Xianyu as XianyuModel;
use think\Exception;
use app\api\model\XianyuImage as XianyuImageModel;

class Xianyu extends BaseService
{
    public function create($dataArray)
    {

        //先存储图片
        $currentuser = TokenService::getCurrentTokenVar();
        
        self::judgeschool($currentuser['school']);

        Db::startTrans();

        try
        {
            //存储图片
            $imgs = uploadimg('xianyu/');
            $ids = [];
            if($this->isNotEmpty($imgs))
            {
                //先将图片存入image表，获取他们的id
                $ids = $this->saveImage($imgs);
            }

            $xianyu = new XianyuModel();
            $xianyu->title = $dataArray['title'];
            $xianyu->summary = $dataArray['summary'];
            $xianyu->detail = $dataArray['detail'];
            $xianyu->price = $dataArray['price'];
            $xianyu->pro_id = $currentuser['uid'];
            $xianyu->school = $currentuser['school'];
            if($this->isNotEmpty($ids))
            {
                $xianyu->headimg = $ids[0];
            }
            else
            {
                $xianyu->headimg = 1;
            }

            $xianyu->save();

            $xianyu_id = $xianyu->id;

            self::savetoXianyuImage($ids,$xianyu_id);

            Db::commit();

            return true;
        }
        catch (Exception $ex)
        {
            Db::rollback();
            return $ex;
        }
    }

    public function take($id)
    {
        $currentuser = TokenService::getCurrentTokenVar();

        $xianyu = XianyuModel::findById($id);


    }

    public function cancel($id)
    {
        $currentuser = TokenService::getCurrentTokenVar();

        $xianyu = XianyuModel::findById($id);

        self::judgeXianyu($xianyu);

        if($xianyu->pro_id != $currentuser['uid'])
        {
            throw new XianyuException([
                'msg' => '必须是发布者才能删除',
                'errorCode' => 40002
            ]);
        }

        if($xianyu->status != XianyuEnum::unexpire)
        {
            throw new XianyuException([
                'msg' => '已取消，无需重复点击',
                'errorCode' => 40003
            ]);
        }

        $xianyu->save(['status'=>XianyuEnum::expire],['id'=>$id]);
    }

    public function judgeXianyu($xianyu)
    {
        if(!$xianyu)
        {
            throw new XianyuException();
        }
    }

    public function judgeStatus($xianyu,$status)
    {
        if($xianyu->status != XianyuEnum::unexpire)
        {
            throw new XianyuException([
                'msg' => '状态错误',
                'errorCode' => 40001
            ]);
        }
    }

    public function savetoXianyuImage($ids,$xianyu_id)
    {
        $list = [];
        $order = 1;
        foreach ($ids as $id)
        {
            $list[] = ['img_id'=>$id,'xianyu_id'=>$xianyu_id,'order'=>$order];
            $order += 1;
        }

        $xianyuImage = new XianyuImageModel();
        $xianyuImage->saveAll($list);
    }

}