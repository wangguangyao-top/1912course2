<?php
namespace app\api\controller;
use think\cache\driver\Redis;
use think\Request;
use app\api\model\course\CourseModel;
use app\api\model\course\CourseCate;
use app\api\model\course\CounrseLect;
use app\api\model\course\CourseCatalog;
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
    /**
     * 无限极分类
     */
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
    public function courseInfo(Request $request){
        $course_id=$request->post('course_id');
        if(!$course_id){
            echo json_encode(['code'=>1000,'msg'=>'缺少参数']);
            die;
        }
        $redis=new Redis();
        $redis_one=$redis->get('course_course'.$course_id);
        if($redis_one){
            $one=json_decode($redis_one,true);
            $one['course_image']=trim($one['course_image'],',');
            $lectFind=$redis->get('course_lect'.$course_id);
            $cateShow=$redis->get('course_cate'.$course_id);
            $cateLog=$redis->get('course_cateLog'.$course_id);
            if($lectFind){
                $lectFind=json_decode($lectFind,true);
            }else{
                $lectFind=[];
            }
            if($cateShow) {
                $cateShow = json_decode($cateShow, true);
            }else{
                $cateShow=[];
            }
            if($cateLog) {
                $cateLogFind = json_decode($cateLog, true);
            }else{
                $cateLogFind=[];
            }
        }else{
            $course=new CourseModel();
            $where=[];
            $where[]=['is_del','=',1];
            $where[]=['course_id','=',$course_id];
            $one=$course->where($where)->find();
            if($one){
                $json=json_encode($one);
            }else{
                echo json_encode(['code'=>1000,'msg'=>'非法操作']);
                die;
            }
            $one['course_image']=trim($one['course_image'],',');
            $where1=[];
            $where1[]=['is_del','=',1];
            $where1[]=['lect_id','=',$one['lect_id']];
            $lect=new CounrseLect();
            $lectFind=$lect->where($where1)->find();
            if($lectFind){
                $json2=json_encode($lectFind);
            }
            $cate=new CourseCate();
            $where2=[];
            $where2[]=['is_del','=',1];
            $where2[]=['cate_id','=',$lectFind['cate_id']];
            $cateShow=$cate->where($where2)->find();
            if($cateShow){
                $json3=json_encode($cateShow);
            }
            $where3=[];
            $where3[]=['is_del','=',1];
            $where3[]=['catalog_id','=',$one['catalog_id']];
            $cateLog=new CourseCatalog();
            $cateLogFind=$cateLog->where($where3)->find();
            if($cateLogFind){
                $json4=json_encode($cateLogFind);
            }
            $redis->setex('course_course'.$one['course_id'],86400,$json);
            $redis->setex('course_lect'.$one['course_id'],86400,$json2);
            $redis->setex('course_cate'.$one['course_id'],86400,$json3);
            $redis->setex('course_cateLog'.$one['course_id'],86400,$json4);
        }
        echo json_encode(['code'=>200,'msg'=>'OK','data'=>['course'=>$one,'lect'=>$lectFind,'cate'=>$cateShow,'cateLog'=>$cateLogFind]]);
        die;


    }
}