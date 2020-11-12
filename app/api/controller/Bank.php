<?php

namespace app\api\controller;

use think\Request;
use app\api\controller\Common;
use app\api\model\BankModel;
use app\api\model\ClassificationModel;

class Bank extends Common
{

    public function index()
    {
        //题库分类
        $class=ClassificationModel::where(["is_del"=>1])->select();
//        $bank=json_encode($bank,true);
        //题库
        $bank=BankModel::
        leftjoin("course_question_classification","course_question_bank.cla_id=course_question_classification.cla_id")
            ->where(["course_question_bank.is_del"=>1])
        ->select();
//        $class=json_encode($class,true);
        $data=[
            "class"=>$class,
            "bank"=>$bank,
        ];
        $data=json_encode($data,true);
        return $data;

    }


}
