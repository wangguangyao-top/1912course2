<?php
namespace app\api\controller;

use think\cache\driver\Redis;
use app\api\model\message\MessageModel;
use app\api\model\message\ActivityModel;
use app\api\model\CourseModel;
use think\Request;


class Article
{
    /**
     *
     * @param Request $request
     */
    public function articleIndex(Request $request){
        $data=MessageModel::where('is_del',1)->select();
        if($data){
            echo  json_encode(['error'=>200,'msg'=>'ok','data'=>$data]);
            die;
        }
        echo  json_encode(['error'=>100001,'msg'=>'NO','data'=>$data]);
        die;
    }

    /**
     *
     */
    public function articleHot(){
        $data=MessageModel::where('is_del',1)->where('is_hut',1)->select();
        if($data){
            echo  json_encode(['error'=>200,'msg'=>'ok','data'=>$data]);
            die;
        }
        echo  json_encode(['error'=>100001,'msg'=>'NO','data'=>$data]);
        die;
    }

    /**
     *
     */
    public function activities(){
        $data=ActivityModel::where('is_del',1)->select();
        if($data){
            echo  json_encode(['error'=>200,'msg'=>'ok','data'=>$data]);
            die;
        }
        echo  json_encode(['error'=>100001,'msg'=>'NO','data'=>$data]);
        die;
    }

    /**
     *
     */
    public function courseNew(){
        $data=CourseModel::where('is_del',1)->order('course_id desc')->select();
        if($data){
            echo  json_encode(['error'=>200,'msg'=>'ok','data'=>$data]);
            die;
        }
        echo  json_encode(['error'=>100001,'msg'=>'NO','data'=>$data]);
        die;
    }
}