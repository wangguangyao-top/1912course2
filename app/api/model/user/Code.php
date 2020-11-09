<?php

namespace app\api\model\user;

use think\cache\driver\Redis;
use think\Model;
use app\api\model\user\User;
use app\api\controller\Reg;
class Code extends Model
{
    protected $pk = 'code_id';
    protected $table = 'course_code';
    //验证码添加
    public function add($data,$arr){
        $add=Code::insert($data);
        if($add){
            echo json_encode($arr);
            die;
        }
            return ['code'=>10009,'msg'=>'发送失败'];
    }
    //验证手机号是否注册过和防刷
    public function verify($user_tel){
        $user=new User();
        $where=[];
        $where[]=['is_del','=',1];
        $where[]=['user_tel','=',$user_tel];
        $count=$user::where($where)->count();
        if($count>=1){
            $arr=['code'=>'10010','msg'=>'手机号已注册~换个试试'];
            echo json_encode($arr);
            die;
        }
        $where2=[];
        $where2[]=['is_use','=',1];
        $where2[]=['c_tel','=',$user_tel];
        //查询用户之后一次发送的验证码
        $code=Code::where($where2)->order('add_time desc')->find();
        if(!$code){
            return $this->code($user_tel);
        }
        $code=$code->toArray();
        if($code['add_time']>time()){
            $arr=['code'=>'10011','msg'=>'验证码一分钟发送一次~请稍后'];
            echo json_encode($arr);
            die;
        }
        //使用redis防刷
        $redis=new Redis();
        $getTel=$redis->get($user_tel);
        if($getTel>=5){
            $arr=['code'=>'10012','msg'=>'由于你的频繁操作，你已经加入黑名单，24小时后恢复'];
            echo json_encode($arr);
            die;
        }
        if($getTel){
            $redis->incr($user_tel);
        }else{
            $redis->setex($user_tel,86400,1);
        }
        return $this->code($user_tel);
    }
    //发送验证码 因为多次用到它
    public function code($user_tel){
        //发送验证码
        $reg=new Reg();
        $code=mt_rand(100000,999999);
        //发送验证码并添加
        $curl_code=$reg->curlCode($user_tel,$code);
        return $curl_code;
    }
}
