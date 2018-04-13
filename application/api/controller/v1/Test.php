<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/29
 * Time: 23:15
 */

namespace app\api\controller\v1;

use app\api\model\School;
use app\api\validate\IDMustBePositiveInt;

class Test
{
    public function Test()
    {
        $img = uploadimg('xianyu/');

        echo 'Success';
    }

}