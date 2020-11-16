<?php
namespace app\api\controller;
use think\cache\driver\Redis;
use think\Request;
use app\api\model\course\CourseModel;
use app\api\model\course\CourseCate;
use app\api\model\course\CounrseLect;
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
        $redis=new Redis();
        $redis_one=$redis->get('course_course'.$course_id);
        if($redis_one){
            $one=json_decode($redis_one,true);
            $lectFind=$redis->get('course_lect'.$course_id);
            $cateShow=$redis->get('course_cate'.$course_id);
            if($lectFind) $lectFind=json_decode($lectFind,true);
            if($cateShow) $cateShow=json_decode($cateShow,true);
        }else{
            $course=new CourseModel();
            $where=[];
            $where[]=['is_del','=',1];
            $where[]=['course_id','=',$course_id];
            $one=$course->where($where)->find();
            if(!$one){
                echo json_encode(['code'=>1000,'msg'=>'缺少参数']);
                die;
            }
            $where1=[];
            $where1[]=['is_del','=',1];
            $where1[]=['lect_id','=',$one['lect_id']];
            $lect=new CounrseLect();
            $lectFind=$lect->where($where1)->find();
            $cate=new CourseCate();
            $where2=[];
            $where2[]=['is_del','=',1];
            $where2[]=['cate_id','=',$lectFind['cate_id']];
            $cateShow=$cate->where($where2)->find();
            $one['course_image']=trim($one['course_image'],',');
            $json=json_encode($one);
            $json2=json_encode($lectFind);
            $json3=json_encode($cateShow);
            $redis->setex('course_course'.$one['course_id'],86400,$json);
            $redis->setex('course_lect'.$one['course_id'],86400,$json2);
            $redis->setex('course_cate'.$one['course_id'],86400,$json3);
        }
        echo json_encode(['code'=>200,'msg'=>'OK','data'=>['course'=>$one,'lect'=>$lectFind,'cate'=>$cateShow]]);
        die;


    }
}