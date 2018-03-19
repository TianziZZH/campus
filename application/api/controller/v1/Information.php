<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 8:58
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\api\service\Token as TokenService;
use app\api\model\Information as InformationModel;
use app\lib\exception\InformationException;

class Information extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope'
    ];

    public function show($page=1,$size=15)
    {
        (new PagingParameter()) -> goCheck();

        $school = TokenService::getCurrentSchool();

        $informations = InformationModel::show($school,$page,$size);

        if($informations->isEmpty())
        {
            return [
                'data' => [],
                'current_page' => $informations->getCurrentPage()
            ];
        }
        else
        {
            $data = $informations->hidden(['school','delete_time','content','create_time'])
                                ->toArray();

            return [
                'data' => $data,
                'current_page' => $informations->getCurrentPage()
            ];
        }
    }

    public function showdetail($id)
    {
        (new IDMustBePositiveInt()) -> goCheck();

        $information = InformationModel::showByID($id);

        if(!$information)
        {
            throw new InformationException();
        }

        $data = $information->hidden(['create_time','delete_time','school','id']);

        return $information;
    }
}