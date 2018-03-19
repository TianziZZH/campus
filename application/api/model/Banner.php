<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/9
 * Time: 14:24
 */

namespace app\api\model;

use app\api\service\BaseService;
use app\api\service\Token as TokenService;

class Banner extends BaseModel
{
    protected $hidden = ['update_time','delete_time'];

    public function items()
    {
        return $this->hasMany('BannerItem','banner_id','id');
    }

    public static function getBannerByID($id)
    {

        $banner = self::with([
            'items' => function($query){
                $school = TokenService::getCurrentSchool();
                BaseService::judgeschool($school);
                $query->with(['img'])
                    ->where('source','=',$school);
            }
        ])
            ->find($id);


        return $banner;
    }
}