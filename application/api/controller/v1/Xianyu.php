<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 19:11
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Xianyu as XianyuModel;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\api\validate\XianyuValidate;
use app\lib\exception\SuccessMessage;
use app\lib\exception\XianyuException;
use app\api\service\Xianyu as XianyuService;

class Xianyu extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope',
        'checkNormalStatus' => ['only' => 'create']
    ];
    
    public function create()
    {
        $validate = new XianyuValidate();
        $validate->goCheck();
        $dataArray = $validate->getDataByRule(input('post.'));
        
        $xianyu = new XianyuService();
        $xianyu->create($dataArray);

        return json(new SuccessMessage(),201);
    }
    
    public function xianyu($page=1,$size=15)
    {

        // 链接image，user表获得所有信息
        (new PagingParameter()) -> goCheck();
        
        $xianyu = XianyuModel::getRecent($page,$size);

        if($xianyu->isEmpty())
        {
            throw new XianyuException();
        }

        return $xianyu;
    }
    
    public function take($id)
    {
        // 可能需要
    }

    public function cancel($id)
    {
        (new IDMustBePositiveInt()) -> goCheck();

        $xianyu = new XianyuService();
        $xianyu->cancel($id);

        return json(new SuccessMessage(),201);
    }
}