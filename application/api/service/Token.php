<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 11:07
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\enum\UserStatusEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\StatusException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;


class Token
{
    public static function generateToken()
    {
        //选取32个字符组成一组随机字符串
        $randChars = getRandChars(32);
        //用三组字符串，进行md5加密
        //当前时间戳
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt 盐
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrentTokenVar()
    {
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);

        if(!$vars)
        {
            throw new TokenException();
        }
        else
        {
            if(!is_array($vars))
            {
                $vars = json_decode($vars, true);
            }

            return $vars;
        }
    }

    public static function getCurrentTokenPull()
    {
        $token = Request::instance()
            ->header('token');
        $vars = Cache::pull($token);

        if(!$vars)
        {
            throw new TokenException();
        }
        else
        {
            if(!is_array($vars))
            {
                $vars = json_decode($vars, true);
            }

            return $vars;
        }
    }

    public static function getCurrentTokenvarDetail($key)
    {
        $vars = self::getCurrentTokenVar();

        if(array_key_exists($key,$vars))
        {
            return $vars[$key];
        }
        else
        {
            throw new Exception('尝试获取的Token并不存在');
        }
    }

    //获取用户的uid
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVarDetail('uid');

        return $uid;
    }

    //获取用户所在学校
    public static function getCurrentSchool()
    {
        $school = self::getCurrentTokenvarDetail('school');

        return $school;
    }


    // 用户和管理员都可以访问的权限
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVarDetail('scope');

        if($scope)
        {
            if($scope>=ScopeEnum::Student){
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    //只有学生能访问的接口
    public static function needStudentScope()
    {
        $scope = self::getCurrentTokenVarDetail('scope');

        if($scope)
        {
            if($scope == ScopeEnum::Student){
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    //只有老师能访问的接口
    public static function needTeacherScope()
    {
        $scope = self::getCurrentTokenVarDetail('scope');

        if($scope)
        {
            if($scope == ScopeEnum::Teacher){
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    //只有用户可以访问的接口权限
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVarDetail('scope');

        if($scope)
        {
            if($scope <= ScopeEnum::Teacher && $scope>=ScopeEnum::Student){
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }

    }

    //封禁警告状态不能访问
    public static function needNormalStatus()
    {
        $status = self::getCurrentTokenvarDetail('status');

        if($status)
        {
            if($status == UserStatusEnum::normal)
            {
                return true;
            }
            else
            {
                throw new StatusException();
            }
        }
        else
        {
            throw new TokenException();
        }
    }

    //封禁状态不能访问
    public static function needNormalWarningSatus()
    {
        $status = self::getCurrentTokenvarDetail('status');

        if($status)
        {
            if($status == UserStatusEnum::closure)
            {
                throw new StatusException();
            }
            else
            {
                return true;
            }
        }
        else
        {
            throw new TokenException();
        }
    }

    public static function isValidOperate($checkedUID)
    {
        if(!$checkedUID){
            throw new Exception('检测uid时必须传入一个被检测的uid');
        }

        $currentOperateUID = self::getCurrentUid();

        if($currentOperateUID == $checkedUID){
            return true;
        }
        else{
            return false;
        }
    }
}