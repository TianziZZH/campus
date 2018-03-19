<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2017/11/30
 * Time: 10:37
 */

namespace app\api\service;

use app\api\model\Task as TaskModel;
use app\lib\enum\TaskStatusEnum;
use app\lib\exception\TaskException;
use app\api\service\Token as TokenService;

class Task
{
    public function JudgeTask($taskno)
    {
        $task = TaskModel::getByTaskNo($taskno);

        if(!$task)
        {
            throw new TaskException();
        }

        return $task;
    }

    public function GetTaskStatus($task)
    {
        return $task->status;
    }

    public function TakeTask($taskno)
    {
        $task = $this->JudgeTask($taskno);
        $status = $this->GetTaskStatus($task);

        if($status != TaskStatusEnum::released)
        {
            throw new TaskException([
                'msg' => '任务已被接走或删除',
                'errorCode' => '60002'
            ]);
        }

        $uid = TokenService::getCurrentUid();

        if($task->promulgator == $uid)
        {
            throw new TaskException([
                'msg' => '自己不能领取自己发布的任务',
                'errorCode' => '60004'
            ]);
        }

        $task->save(['receiver'=>$uid,'status'=>TaskStatusEnum::received]);

        return $task->status;
    }

    public function Pro_Judge($grade,$taskno)
    {
        $task = $this->JudgeTask($taskno);
        $status = $this->GetTaskStatus($task);

        if($status != TaskStatusEnum::finished)
        {
            throw new TaskException([
               'msg' => '任务尚未成无法评价',
               'errorCode' => '60003'
            ]);
        }
        else
        {
            $receiver_no = $task->receiver;
            // 利用某算法改变用户评分
            return $receiver_no;
        }
    }

    public function Rec_Judge($grade,$taskno)
    {
        $task = $this->JudgeTask($taskno);
        $status = $this->GetTaskStatus($task);

        if($status != TaskStatusEnum::finished)
        {
            throw new TaskException([
                'msg' => '任务尚未成无法评价',
                'errorCode' => '60003'
            ]);
        }
        else
        {
            $promulgator_no = $task->promulgator;
            // 利用某算法改变用户评分
            return $promulgator_no;
        }
    }

}