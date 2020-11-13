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
     *全部资讯
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
     *最热资讯
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
     *精彩活动
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
     *精彩活动详情
     */
    public function actiMinute(){
        $id=$_POST['min_id'];
        if(empty($id)){
            echo  json_encode(['error'=>100003,'msg'=>'参数缺失']);
            die;
        }
            $data=ActivityModel::where('is_del',1)->where('act_id',$id)->find();

        if($data){
            echo  json_encode(['error'=>200,'msg'=>'ok','data'=>$data]);
            die;
        }
        echo  json_encode(['error'=>100001,'msg'=>'NO','data'=>$data]);
        die;
    }


    /**
     *最新课程
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

    /**
     *资讯详情
     */
    public function artDeta(){
        $min_id=$_POST['min_id'];
        $data=MessageModel::where('message_id',$min_id)->where('is_del',1)->find();
        if($data){
            echo  json_encode(['error'=>200,'msg'=>'ok','data'=>$data]);
            die;
        }
        echo  json_encode(['error'=>100001,'msg'=>'NO','data'=>$data]);
        die;
    }

    /**
     *热门资讯详情
     */
    public function artDetaHot(){
        $min_id=$_POST['min_id'];
        $data=MessageModel::where('message_id',$min_id)->where('is_hut',1)->where('is_del',1)->find();
        if($data){
            echo  json_encode(['error'=>200,'msg'=>'ok','data'=>$data]);
            die;
        }
        echo  json_encode(['error'=>100001,'msg'=>'NO','data'=>$data]);
        die;
    }
}