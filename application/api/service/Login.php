<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 19:13
 */

namespace app\api\service;

use app\api\service\Token as TokenService;
use app\lib\enum\ScopeEnum;
use app\lib\exception\LoginException;
use app\lib\exception\TokenException;
use app\api\model\User as UserModel;

class Login extends BaseService
{
    public static function login($dataArray)
    {
        $uid = TokenService::getCurrentUid();
        $userid = $dataArray['userid'];
        $password = $dataArray['password'];
        $school = $dataArray['school'];

        self::judgeschool($school);

        $command = exec("E:\Program\StudyTool\Python\Python3\python3.exe E:\Study\Python\Learn\LearnPython\phptest.py $userid $password $school $uid", $Array, $ret);

        if($ret == 1){
            throw new LoginException([
                'msg' => '登入超时',
                'errorCode' => '20000'
            ]);
        }

        $scope = $Array[0];

        if($scope == ScopeEnum::Visitor)
        {
            throw new LoginException();
        }

        //将状态写入user数据库
        $user = UserModel::getByUid($uid);
        $user->save([
            'scope' => $scope,
            'school' => $school,
            'nickname' => $dataArray['nickname'],
            'headimg' => $dataArray['avatar']
        ],['id'=>$uid]);

        return self::changCacheScope($scope,$school);
    }

    public static function changCacheScope($scope,$school)
    {
        $user = TokenService::getCurrentTokenPull();

        $value = [];

        $key = TokenService::generateToken();
        $expire_in = $user['expires_in'];
        $value['session_key'] = $user['session_key'];
        $value['expires_in'] = $expire_in;
        $value['openid'] = $user['openid'];
        $value['uid'] = $user['uid'];
        $value['scope'] = $scope;
        $value['status'] = $user['status'];
        $value['school'] = $school;

        $request = cache($key,$value,$expire_in);

        if(!$request)
        {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorcode' => 10005
            ]);
        }

        return $key;
    }
}