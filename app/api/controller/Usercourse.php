<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Course;
use app\api\model\UcourseModel;


class Usercourse extends Controller
{
    public function usercourse(){
        $usercourse = UcourseModel::leftjoin('course_course','course_user_course.course_id=course_course.course_id')
                                    ->where('is_del',1)
                                    ->select();
        return json_encode($usercourse,true);
    }
}
