<?php
/**
 * Created by PhpStorm.
 * User: 30222
 * Date: 2018/3/3
 * Time: 20:35
 */

namespace app\lib\enum;


class TaskStatusEnum
{
    // 未发布
    const unreleased = 0;

    // 发布
    const released = 1;

    // 被接
    const received = 2;

    // 完成
    const finished = 3;

    // 结单
    const payed = 4;

    // 删除
    const deleted = 5;

    //取消
    const cancel = 6;
}