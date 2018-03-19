<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 21:22
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\UserToken;
use app\api\model\User as UserModel;
use app\api\service\Publish as PulishService;
use app\api\validate\PagingParameter;
use app\api\service\Token as TokenService;
use app\api\model\Paotui as PaotuiModel;
use app\lib\exception\PaotuiException;
use think\Exception;

class User extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope'
    ];

    public function show()
    {
        $uid = UserToken::getCurrentUid();

        $user =UserModel::allinformation($uid);
        
        return $user;
    }

    public function showPublish($page=1,$size=15)
    {
        (new PagingParameter()) -> goCheck();

        $publish = new PulishService();
        $data = $publish->showPulish($page,$size);
        
        return $data;
    }
    
    public function showReceive($page=1,$size=15)
    {
        (new PagingParameter()) -> goCheck();
        
        $currentid = TokenService::getCurrentUid();
        
        $paotui = PaotuiModel::findByRecID($currentid,$page,$size);
        $paotui = PaotuiModel::judged($paotui);
        
        if(!$paotui)
        {
            throw new PaotuiException([
                'msg' => '未接收过任何任务',
                'errorCode' => 50008
            ]);
        }
        
        return $paotui;
    }
}