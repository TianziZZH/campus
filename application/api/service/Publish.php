<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/14
 * Time: 15:43
 */

namespace app\api\service;

use app\api\model\Paotui as PaotuiModel;
use app\api\service\Token as TokenService;
use app\api\model\Xianyu as XianyuModel;
use app\lib\enum\JudgeStatusEnum;
use think\Exception;

class Publish
{
    public function showPulish($page,$size)
    {
        $xianyu = $this->showXianyu();
        $paotui = $this->showPaotui();

        $data['xianyu'] = $xianyu;
        $data['paotui'] = $paotui;

        if((!$data['xianyu']) && (!$data['paotui']))
        {
            throw new Exception([
                'msg' => '没有发布任何内容'
            ]);
        }

        return $data;
    }

    public function showPaotui()
    {
        $currentid = TokenService::getCurrentUid();
        $paotuis = PaotuiModel::fingByProID($currentid);

        foreach ($paotuis as $paotui)
        {
            if($paotui['pro_judge'] == JudgeStatusEnum::unjudge)
            {
                $paotui['ranked'] = JudgeStatusEnum::unjudge - 1;
            }
            else
            {
                $paotui['ranked'] = JudgeStatusEnum::judge - 1;
            }
        }

        return $paotuis;
    }

    public function showXianyu()
    {
        $currentid = TokenService::getCurrentUid();
        $xianyu = XianyuModel::findByProID($currentid);

        return $xianyu;
    }
}