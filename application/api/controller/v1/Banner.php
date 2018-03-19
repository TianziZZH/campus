<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/2/14
 * Time: 16:11
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\BannerMissException;

class Banner extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope'
    ];

    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $banner = BannerModel::getBannerByID($id);

        if(!$banner)
        {
            throw new BannerMissException();
        }

        return $banner;
    }
}