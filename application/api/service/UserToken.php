<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 17:27
 */

namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\enum\SchoolEnum;
use app\lib\enum\ScopeEnum;
use app\lib\enum\UserStatusEnum;
use app\lib\exception\LoginException;
use app\lib\exception\TokenException;
use app\lib\exception\WxChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
            $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxresult = json_decode($result,true);

        if(empty($wxresult))
        {
            throw new Exception('获取seesion_key及openid时错误，微信内部异常');
        }
        else
        {
            $loginFail = array_key_exists('errcode',$wxresult);
            if($loginFail)
            {
                $this->processLoginError($wxresult);
            }
            else
            {
                return $this->grantToken($wxresult);
            }
        }
    }

    private function grantToken($wxresult)
    {
        $openid = $wxresult['openid'];

        $user = UserModel::getByOpenID($openid);

        if($user)
        {
            $uid = $user->id;
            $status = $user->status;
            $scope = $user->scope;
            $school = $user->school;
        }
        else
        {
            $uid = $this->newUser($openid);
            $scope = ScopeEnum::Visitor;
            $status = UserStatusEnum::normal;
            $school = SchoolEnum::unopen;
        }

        $cachedValue = $this->prepareCachedValue($wxresult,$uid,$scope,$status,$school);

        $token = $this->saveToCache($cachedValue);

        $pass = true;
        if($scope == ScopeEnum::Visitor)
        {
            $pass = false;
        }

        $value['token'] = $token;
        $value['pass'] = $pass;

        return $value;
    }

    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid,
            'scope' => ScopeEnum::Visitor,
            'status' => UserStatusEnum::normal,
            'school' => SchoolEnum::unopen
        ]);

        return $user->id;
    }

    private function prepareCachedValue($wxresult,$uid,$scope,$status,$school)
    {
        $cachedValue = $wxresult;
        $cachedValue['uid'] = $uid;
        //权限标记
        $cachedValue['scope'] = $scope;
        //状态标志
        $cachedValue['status'] = $status;
        //学校
        $cachedValue['school'] = $school;

        return $cachedValue;
    }

    private function saveToCache($cacheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in');

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

    private function processLoginError($wxresult)
    {
        throw new WxChatException([
            'msg' => $wxresult['errmsg'],
            'errorcode' => $wxresult['errcode']
        ]);
    }

    private function pythonLogin($userid,$password,$school,$uid)
    {
        // python爬虫
        $command = exec("E:\Program\StudyTool\Python\Python3\python3.exe E:\Study\Python\Learn\LearnPython\phptest.py $userid $password $school $uid", $Array, $ret);

        if($ret == 1){
            throw new LoginException([
                'msg' => '登入超时',
                'errorCode' => '20000'
            ]);
        }

        $status = $Array[0];

        if($status == ScopeEnum::Visitor)
        {
            throw new LoginException();
        }

        $user = UserModel::getByUid($uid);
        $user->save([
            'scope' => $status,
        ]);

        return $status;
    }

    private function pythonLoginError()
    {
        throw new LoginException([
            'msg' => '现在是游客身份无法正常访问，请完成学校选择',
            'errorCode' => '20002'
        ]);
    }

}