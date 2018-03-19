<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/24
 * Time: 11:02
 */

namespace app\api\controller;

use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }

    public function checkStudentScope()
    {
        TokenService::needStudentScope();
    }

    public function checkTeacherScope()
    {
        TokenService::needTeacherScope();
    }

    public function checkNormalStatus()
    {
        TokenService::needNormalStatus();
    }

    public function checkNormalWarningStatus()
    {
        TokenService::needNormalWarningSatus();
    }
}