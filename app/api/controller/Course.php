<?php
namespace app\api\controller;
use think\cache\driver\Redis;
use think\Request;
use app\api\model\course\CourseModel;
use app\api\model\course\CourseCate;
class Course
{
    /**
     * 课程展示
     * @param Request $request
     */
    public function index(Request $request){
        $redis=new Redis();
        $course=new CourseModel();
        $getShow=$redis->get('api_course');
        $arr=[];
        $cate_id=$request->post('cate_id');
        if($getShow){
            $show=json_decode($getShow,true);
            if(!empty($cate_id)){
                foreach ($show as $k=>$v) {
                    if($v['cate_id']==$cate_id){
                        $arr[]=$v;
                    }
                }
                if($arr){
                    echo json_encode(['code'=>200,'msg'=>'OK','data'=>$arr]);
                    die;
                }
            }
        }else{
            $where=[];
            $where[]=['is_del','=','1'];
            if($cate_id){
                $where[]=['cate_id','=',$cate_id];
            }
            $show=$course->where($where)->select()->toArray();
            $json=json_encode($show);
            $redis->setex('api_course',86400,$json);
        }
        echo json_encode(['code'=>200,'msg'=>'OK','data'=>$show]);
        die;
    }
    /**
     * 课程分类
     */

    public function cate(){
        $redis=new Redis();
        $getCate=$redis->get('api_cate');
        if($getCate){
            $arr=json_decode($getCate);
        }else{
            $cate=new CourseCate();
            $where=[];
            $where[]=['is_del','=','1'];
            $show=$cate->where($where)->select()->toArray();
            $arr=$this->infiniteCate($show);
            $redis->setex('api_cate',86400,json_encode($arr));
        }
        echo json_encode(['code'=>200,'msg'=>'OK','data'=>$arr]);
        die;
    }
    public function infiniteCate($show,$pid=0){
        $arr=[];
        foreach ($show as $k=>$v) {
            if($v['p_id']==$pid){
                $arr[$k]=$v;
                $arr[$k]['cate'] = $this->infiniteCate($show,$v["cate_id"]);
            }
        }
        return $arr;
    }
}