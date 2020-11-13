<?php

namespace app\api\controller;

use app\api\controller\Common;
use think\Controller;
use think\Request;
use app\api\model\askarea\AskareaModel;
use app\api\model\askarea\AnswerModel;

class Askarea extends Common
{
    public function lists(){
        $hot=AskareaModel::
        rightjoin("course_answer","course_issue.issue_id=course_answer.issue_id")
            ->order("issue_browse",'desc')
            ->limit(0,5)
            ->select();

        $ask=AskareaModel::
        rightjoin("course_answer","course_issue.issue_id=course_answer.issue_id")
        ->select();

        $data=[
            "hot"=>$hot,
            "ask"=>$ask
        ];

        $data=json_encode($data,true);
        return $data;
    }
}
