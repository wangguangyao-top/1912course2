<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Course;
use app\api\model\UnoteModel;


class Usernote extends Controller
{
    public function usernote(){
        $usernote = UnoteModel::leftjoin('course_note','course_user_note.note_id=course_note.note_id')
            ->where('is_del',1)
            ->select();
        return json_encode($usernote,true);
    }
}
