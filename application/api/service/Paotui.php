<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/10
 * Time: 10:39
 */

namespace app\api\service;

use app\api\model\Paotui as PaotuiModel;
use app\api\model\User;
use app\api\service\Token as TokenService;
use app\lib\enum\JudgeStatusEnum;
use app\lib\enum\TaskStatusEnum;
use app\lib\exception\PaotuiException;
use think\Db;
use think\Exception;

class Paotui extends BaseService
{
    public function create($dataArray)
    {
        $currentuser = TokenService::getCurrentTokenVar();

        self::judgeschool($currentuser['school']);

        Db::startTrans();
        try
        {
            $paotui = new PaotuiModel();
            $paotui->describe = $dataArray['describe'];
            $paotui->destination = $dataArray['destination'];
            $paotui->price = $dataArray['price'];
            $paotui->pro_id = $currentuser['uid'];
            $paotui->school = $currentuser['school'];

            $paotui->save();

            Db::commit();

            return true;
        }
        catch (Exception $ex)
        {
            Db::rollback();
            throw $ex;
        }
    }

    public function take($id)
    {
        // 判断业务是否可以被领取
        // 判断学校是否符合
        // 需要判断任务是否过期
        $currentuser = TokenService::getCurrentTokenVar();

        $paotui = PaotuiModel::findById($id);

        self::judgePaotui($paotui);
        self::judgeStatus($paotui,TaskStatusEnum::released);

        if($paotui->school != $currentuser['school'])
        {
            throw new PaotuiException([
                'msg' => '只能领取本校任务',
                'errorCode' => 50001
            ]);
        }

        if($currentuser['uid'] == $paotui->pro_id)
        {
            throw new PaotuiException([
                'msg' => '不能接自己发布的任务',
                'errorCode' => 50005
            ]);
        }

        $paotui->update([
            'rec_id' => $currentuser['uid'],
            'status' => TaskStatusEnum::received
        ],['id'=>$id]);
    }

    public function finish($id)
    {
        $currentuser = TokenService::getCurrentTokenVar();

        $paotui = PaotuiModel::findById($id);

        self::judgePaotui($paotui);
        self::judgeStatus($paotui,TaskStatusEnum::received);

        // 判断是否是发布者
        if($paotui->pro_id != $currentuser['uid'])
        {
            throw new PaotuiException([
                'msg' => '只能发布者完成',
                'errorCode' => 50002
            ]);
        }

        $paotui->update([
            'status' => TaskStatusEnum::finished
        ],['id'=>$id]);
    }

    public function cancel($id)
    {
        $currentuser = TokenService::getCurrentTokenVar();
        $paotui = PaotuiModel::findById($id);

        self::judgePaotui($id);

        if($paotui->pro_id != $currentuser['uid'])
        {
            throw new PaotuiException([
                'msg' => '只能取消自己发布的任务',
                'errorCode' => 50007
            ]);
        }

        if($paotui->status < TaskStatusEnum::finished)
        {
            $paotui->update([
                'status' => TaskStatusEnum::cancel
            ],['id'=>$id]);
        }
        else
        {
            throw new PaotuiException([
                'msg' => '只有处于待领取的任务，才能取消',
                'errorCode' => 50006
            ]);
        }


    }

    public function delete($id)
    {
        $currentuser = TokenService::getCurrentTokenVar();
        $paotui = PaotuiModel::findById($id);

        self::judgePaotui($id);

        if($paotui->pro_id != $currentuser['uid'])
        {
            throw new PaotuiException([
                'msg' => '只能发布者删除',
                'errorCode' => 50002
            ]);
        }

        if($paotui->status == TaskStatusEnum::received)
        {
            throw new PaotuiException([
                'msg' => '任务处于接取状态，无法删除，请完成后删除',
                'errorCode' => 50006
            ]);
        }

        $paotui->update([
            'status' => TaskStatusEnum::deleted
        ],['id'=>$id]);
    }

    public function judgePaotui($paotui)
    {
        if(!$paotui)
        {
            throw new PaotuiException();
        }
    }

    public function judgeStatus($paotui,$status,$msg = '未找到任务或已取消',$errorCode=50000)
    {
        if($paotui->status != $status)
        {
            throw new PaotuiException([
                'msg' => $msg,
                'errorCode' => $errorCode
            ]);
        }
    }

    public function judge($dataArray)
    {
        $currentuser = TokenService::getCurrentTokenVar();

        $id = $dataArray['id'];
        $grade = $dataArray['grade'];

        $paotui = PaotuiModel::findById($id);

        self::judgePaotui($paotui);
        self::judgeStatus($paotui,TaskStatusEnum::finished,'只能完成后评价','50003');
        $pro_id = $paotui->pro_id;
        $rec_id = $paotui->rec_id;

        // 如果当前uid是发布者
        if($currentuser['uid'] == $pro_id)
        {
            self::ChangeGage($rec_id,$grade,'rec_judge');
        }
        elseif($currentuser['uid'] == $rec_id)
        {
            self::ChangeGage($pro_id,$grade,'pro_judge');
        }
        else
        {
            throw new PaotuiException([
                'msg' => '跑腿评价第三方错误',
                'errorCode' => '50004'
            ]);
        }
    }

    private function ChangeGage($id,$grade,$field='')
    {
        $user = User::getByUid($id);
        $original_grade = $user->judge;
        $original_judgeNum = $user->judgeNum;

        $g = self::GradeArithmetic($original_grade,$grade,$original_judgeNum);

        $user->save([
            'grade' => $g,
            $field => JudgeStatusEnum::judge,
            'judgeNum' => $original_judgeNum+1],['id'=>$id]);
    }

    private function GradeArithmetic($org_grade,$grade,$judgeNum)
    {
        if($judgeNum<=3)
        {
            $grade = 0.7*$org_grade+0.3*$grade;
        }
        else
        {
            $grade = (3/$judgeNum)*$grade+[($judgeNum-3)/$judgeNum]*$org_grade;
        }
        
        return $grade;
    }
}