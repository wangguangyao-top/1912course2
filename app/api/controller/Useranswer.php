<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\UanswerModel;


class Useranswer extends Controller
{
    public function Useranswer(){
        $useranswer = UanswerModel::leftjoin('course_answer','course_user_answer.answer_id=course_answer.answer_id')
            ->where('is_del',1)
            ->select();
        return json_encode($useranswer,true);
    }
}
