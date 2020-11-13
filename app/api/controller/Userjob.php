<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Course;
use app\api\model\UjobModel;


class Userjob extends Controller
{
    public function userjob(){
        $userjob = UjobModel::leftjoin('course_job','course_user_job.job_id=course_job.job_id')
            ->where('is_del',1)
            ->select();
        return json_encode($userjob,true);
    }
}
