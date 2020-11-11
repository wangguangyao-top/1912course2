<?php
namespace app\api\controller;
use app\api\model\user\User;
use think\cache\driver\Redis;
use think\Request;
use app\api\model\user\Code;
class Reg
{
    /**
     * 手机发送验证码
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $user_tel=$request->post('user_tel');
        $ret='/^1(?:3\d|4[4-9]|5[0-35-9]|6[67]|7[013-8]|8\d|9\d)\d{8}$/';
        if(!preg_match($ret,$user_tel)){
            echo json_encode(['code'=>10000,'msg'=>'手机格式不正确']);
            die;
        }
        //第一次走数据库第二次走redis
        $redis=new Redis();
        $getTel=$redis->get('code_'.$user_tel);
        if($getTel){
            echo $getTel;
            die;
        }
        $where=[];
        $where[]=['is_del','=',1];
        $where[]=['user_tel','=',$user_tel];
        $user=new User();
        $count=$user::where($where)->count();
        if($count>=1){
             $json=json_encode(['code'=>10022,'msg'=>'手机号已注册']);
             $redis->setex('code_'.$user_tel,86400,$json);
             echo $json;
             die;
        }
        $code=new Code();
        //验证码添加入库并返回
        return $code->verify($user_tel);
    }
    /**
     * 短信接口
     */
//    public function curlCode($user_tel,$code){
//        error_reporting(E_ALL || ~E_NOTICE);
//        $host = "https://feginesms.market.alicloudapi.com";
//        $path = "/codeNotice";
//        $method = "GET";
//        $appcode = "e6f573c2087849ce9e4cd67f6193a6df";//开通服务后 买家中心-查看AppCode
//        $headers = array();
//        array_push($headers, "Authorization:APPCODE " . $appcode);
//        $querys = "param=".$code."&phone=".$user_tel."&sign=175622&skin=1";
//        $bodys = "";
//        $url = $host . $path . "?" . $querys;
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($curl, CURLOPT_FAILONERROR, false);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HEADER, true);
//        if (1 == strpos("$" . $host, "https://")) {
//            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        }
//        $out_put = curl_exec($curl);
//
//        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//
//        list($header, $body) = explode("\r\n\r\n", $out_put, 2);
//        if ($httpCode == 200) {
//                    $arr=['code'=>200,'msg'=>'亲验证码有效期为：5分钟，请尽快验证'];
//                    if($arr['code']==200){
//                    $u_code=$code;
//                    $c_tel=$user_tel;
//                    $add_time=time()+300;
//                    $arr2=['u_code'=>$u_code,'c_tel'=>$c_tel,'add_time'=>$add_time];
//                    $code=new Code();
//                    return $code->add($arr2,$arr);
//                    }
//        } else {
//            if ($httpCode == 400 && strpos($header, "Invalid Param Location") !== false) {
//                echo json_encode(['code'=>10001,'msg'=>'参数错误']);
//                die;
////                print("参数错误");
//            } elseif ($httpCode == 400 && strpos($header, "Invalid AppCode") !== false) {
//                echo json_encode(['code'=>10002,'msg'=>'AppCode错误']);
//                die;
////                print("AppCode错误");
//            } elseif ($httpCode == 400 && strpos($header, "Invalid Url") !== false) {
//                echo json_encode(['code'=>10003,'msg'=>'请求的 Method、Path 或者环境错误']);
//                die;
////                print("请求的 Method、Path 或者环境错误");
//            } elseif ($httpCode == 403 && strpos($header, "Unauthorized") !== false) {
//                echo json_encode(['code'=>10004,'msg'=>'服务未被授权（或URL和Path不正确）']);
//                die;
////                print("服务未被授权（或URL和Path不正确）");
//            } elseif ($httpCode == 403 && strpos($header, "Quota Exhausted") !== false) {
//                echo json_encode(['code'=>10005,'msg'=>'套餐包次数用完']);
//                die;
////                print("套餐包次数用完");
//            } elseif ($httpCode == 500) {
//                echo json_encode(['code'=>10006,'msg'=>'API网关错误']);
//                die;
////                print("API网关错误");
//            } elseif ($httpCode == 0) {
//                echo json_encode(['code'=>10007,'msg'=>'URL错误']);
//                die;
////                print("URL错误");
//            } else {
//                echo json_encode(['code'=>10008,'msg'=>'参数名错误 或 其他错误']);
//                die;
////                print("参数名错误 或 其他错误");
//                print($httpCode);
//                $headers = explode("\r\n", $header);
//                $headList = array();
//                foreach ($headers as $head) {
//                    $value = explode(':', $head);
//                    $headList[$value[0]] = $value[1];
//                }
//                print($headList['x-ca-error-message']);
//            }
//        }
//    }
//测试发送验证码
    /**
     * @param $user_tel
     * @param $code
     * @return array
     * 模拟手机号发送
     */
    public function curlCode($user_tel,$code){
        $arr=['code'=>200,'msg'=>'亲验证码有效期为：5分钟，请尽快验证'];
        if($arr['code']==200){
            $u_code=$code;
            $c_tel=$user_tel;
            $add_time=time()+60;
            $arr2=['u_code'=>$u_code,'c_tel'=>$c_tel,'add_time'=>$add_time];
            $code=new Code();
            return $code->add($arr2,$arr);
        }
    }
    /**
     * @param Request $request
     * 用户注册
     */
    public function userReg(Request $request){
        $data=$request->post();
        $user_name=$data['user_name'];
        $reg_name='/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z\u4e00-\u9fa5]{6,18}$/';
        if(!preg_match($reg_name,$user_name)){
            echo json_encode(['code'=>10013,'msg'=>'用户名由数字，字母，中文（6-18位）组成']);
            die;
        }
        $user_tel=$data['user_tel'];
        $reg_tel='/^1(?:3\d|4[4-9]|5[0-35-9]|6[67]|7[013-8]|8\d|9\d)\d{8}$/';
        if(!preg_match($reg_tel,$user_tel)){
            echo json_encode(['code'=>10014,'msg'=>'手机号格式不正确']);
            die;
        }
        $user_pwd=$data['user_pwd'];
        $reg_pwd='/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/';
        if(!preg_match($reg_pwd,$user_pwd)){
            echo json_encode(['code'=>10015,'msg'=>'密码由数字，字母（8-16位组成）']);
            die;
        }
        $user_pwd2=$data['user_pwd2'];
        if(!preg_match($reg_pwd,$user_pwd2)){
            echo json_encode(['code'=>10016,'msg'=>'密码由数字，字母（8-16位组成）']);
            die;
        }
        $user_code=$data['u_code'];
        $reg_code='/^\d{6}$/';
        if(!preg_match($reg_code,$user_code)){
            echo json_encode(['code'=>10017,'msg'=>'验证码6位数字']);
            die;
        }
        //第一次走数据库第二次走redis,减少数据库连接次数
        $redis=new Redis();
        $getName=$redis->get('code_'.$user_name);
        if($getName){
            echo $getName;
            die;
        }
        $getTel=$redis->get('code_'.$user_tel);
        if($getTel){
            echo $getTel;
            die;
        }
        $user=new User();
        //判断用户名是否注册
        $where2=[];
        $where2[]=['is_del','=',1];
        $where2[]=['user_name','=',$user_name];
        $userCount=$user::where($where2)->count();
        if($userCount>=1){
            $json=json_encode(['code'=>10023,'msg'=>'用户名称已被使用~换个试试']);
            $redis->setex('code_'.$user_name,86400,$json);
            echo $json;
            die;
        }
        //判断手机号是否注册
        $where=[];
        $where[]=['is_del','=',1];
        $where[]=['user_tel','=',$user_tel];
        $count=$user::where($where)->count();
        if($count>=1){
            $json=json_encode(['code'=>10022,'msg'=>'手机号已注册']);
            $redis->setex('code_'.$user_tel,86400,$json);
            echo $json;
            die;
        }
        //注册成功返回
        $code=new Code();
        return $code->userAdd($data);
    }
}
