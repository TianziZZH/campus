<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 9:36
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\JudgeValidate;
use app\api\validate\PagingParameter;
use app\api\service\Token as TokenService;
use app\api\model\Paotui as PaotuiModel;
use app\api\validate\PaotuiValidate;
use app\api\service\Paotui as PaotuiService;
use app\lib\exception\SuccessMessage;

class Paotui extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope',
        'checkNormalStatus' => ['only' => 'create'],
        'checkNormalWarningStatus' => ['only' => 'create,take']
    ];

    public function paotui($page=1,$size=20)
    {
        (new PagingParameter()) -> goCheck();

        $paotui = PaotuiModel::show($page,$size);

        if($paotui->isEmpty())
        {
            return [
                'data' => [],
                'current_page' => $paotui->getCurrentPage()
            ];
        }
        else
        {
            $data = $paotui->toArray();

            return [
                'data' => $data,
                'current_page' => $paotui->getCurrentPage()
            ];
        }
    }

    public function create()
    {
        //权限控制！！！！！！
        $validate = new PaotuiValidate();
        $validate -> goCheck();
        $dataArray = $validate->getDataByRule(input('post.'));

        $paotui = new PaotuiService();
        $paotui->create($dataArray);

        return json(new SuccessMessage(),201);
    }

    public function take($id)
    {
        (new IDMustBePositiveInt()) -> goCheck();

        $paotui = new PaotuiService();
        $paotui->take($id);

        return json(new SuccessMessage(),201);
    }

    public function cancel($id)
    {
        (new IDMustBePositiveInt()) -> goCheck();

        $paotui = new PaotuiService();
        $paotui->cancel($id);

        return json(new SuccessMessage(),201);
    }

    public function finish($id)
    {
        (new IDMustBePositiveInt()) -> goCheck();

        $paotui = new PaotuiService();
        $paotui->finish($id);

        return json(new SuccessMessage(),201);
    }

    public function delete($id)
    {
        (new IDMustBePositiveInt()) -> goCheck();

        $paotui = new PaotuiService();
        $paotui->delete($id);

        return json(new SuccessMessage(),201);
    }

    public function judge()
    {
        $validate = new JudgeValidate();
        $validate->goCheck();
        $dataArray = $validate->getDataByRule(input('post.'));

        $paotui = new PaotuiService();
        $paotui->judge($dataArray);

        return json(new SuccessMessage(),201);
    }
}