<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\LectModel;
use app\api\model\Category;


class Lect extends Controller
{
    public function index(){
        $data = LectModel::leftjoin('course_category','course_lect.cate_id=course_category.cate_id')
            ->where('course_lect.is_del',1)
            ->select();
        return json_encode($data,true);
    }
    public function teacherPage(Request $request){
        $lect_id = $request->post('lect_id');
        $data = LectModel::leftjoin('course_category','course_lect.cate_id=course_category.cate_id')
            ->where('course_lect.is_del',1)
            ->where('lect_id',$lect_id)
            ->find();
        return json_encode($data,true);
    }
}
