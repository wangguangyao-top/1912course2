<?php
namespace app\api\controller;

use think\cache\driver\Redis;
use app\api\controller\Common;
use think\Request;
use app\api\model\user\Category as CategoryModel;
use app\api\model\user\Course as CourseModel;

class Category extends Common
{
    /**
     * 课程分类
     * @param Request $request
     */
    public function index(Request $request)
    {
        $CategoryModel = new CategoryModel;

        $where=[
            ['p_id',"=",0],
            ['is_del','=',1],
        ];
        $data = $CategoryModel->where($where)->select();
        $data=json_encode($data,true);
        return $data;
    }
    //课程分类子级
    public function index1()
    {
        $request=Request();
        $info = CourseModel::leftjoin("course_category","course_course.cate_id=course_category.cate_id")->limit(0,4)
        ->select();
        $info=json_encode($info,true);
        return $info;
    }
   //精品课程
    public function cate(Request $request){
        $cate_id=$request->cate_id;
        $where=[];
        $where[]=['is_del','=',1];
        $where[]=['cate_id','=',$cate_id];
        $course=new CourseModel();
        $show=$course->where($where)->select();
        $info=json_encode($show);
        return $info;
    }
    //首页加载更多
    public function add(Request $request){
        $cate_id=$request->cate_id;
        $number=$request->number??0;
        $one=0+$number;
        $end=4+$number;
        $where = [];
        $where[] = ['is_del','=',1];
        if($cate_id){
            $where[] = ['cate_id','=',$cate_id];
        }
        $count=CourseModel::where($where)->count();
        if($one>=$count){
            $res = json_encode(1000);
            return $res;
        }
        $res = CourseModel::where($where)->limit($one,$end)->select();
        $res = json_encode($res);
        return $res;
    }
}