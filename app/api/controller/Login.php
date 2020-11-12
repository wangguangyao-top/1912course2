<?php
namespace app\api\controller;
use app\api\model\user\User;
use think\cache\driver\Redis;
use think\Request;
use app\api\model\user\Code;
class Login
{
    /**
     * 登录
     * @param Request $request
     */
    public function index(Request $request){
        $data=$request->post();
        $user_name=$data['user_name'];
        $user_pwd=$data['user_pwd'];
        $ret_name='/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,18}$/';
        $ret_tel='/^1(?:3\d|4[4-9]|5[0-35-9]|6[67]|7[013-8]|8\d|9\d)\d{8}$/';
        //判断是手机号登录还是用户名登录
        if(preg_match($ret_tel,$user_name)){
            $where=[];
            $where[]=['is_del','=',1];
            $where[]=['user_tel','=',$user_name];
            $this->loginOut($where,$user_pwd,$user_name);
        }else{
            if(!preg_match($ret_name,$user_name)){
                echo json_encode(['code'=>10023,'msg'=>'用户名/手机号格式不正确']);
                die;
            }else{
                $where=[];
                $where[]=['is_del','=',1];
                $where[]=['user_name','=',$user_name];
                $this->loginOut($where,$user_pwd,$user_name);
            }
        }
    }

    /**
     * 执行登录
     */
    public function loginOut($where,$user_pwd,$user_name){
        $redis=new Redis();
        $apiName='api_'.$user_name;
        $getName=$redis->get($apiName);
        if($getName){
            $users=json_decode($getName,true);
        }else{
            $user=new User();
            $users=$user::where($where)->find();
            if(!$users){
                echo json_encode(['code'=>10024,'msg'=>'用户或密码错误']);
                die;
            }
            $users=$users->toArray();
            $users2=json_encode($users);
            $redis->setex($apiName,86400,$users2);
        }
        $str='login_'.$user_name;
        $getUser=$redis->get($str);
        if($getUser>=5){
            echo json_encode(['code'=>10025,'msg'=>'由于你错误次数过多~账号被锁定1小时']);
            die;
        }
        //判断密码是否
        if(!password_verify($user_pwd,$users['user_pwd'])){
            if(!$getUser){
                $redis->setex($str,3600,1);
            }else{
                $redis->incr($str);
            }
            echo json_encode(['code'=>10024,'msg'=>'用户或密码错误']);
            die;
        }
        echo json_encode(['code'=>200,'msg'=>'登录成功']);
        die;
    }
}