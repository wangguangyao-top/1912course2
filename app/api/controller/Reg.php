<?php
namespace app\api\controller;
use think\Request;
use app\api\model\user\Code;
class Reg
{
    public function index(Request $request)
    {
        $user_tel=$request->post('user_tel');
        $ret='/^1(?:3\d|4[4-9]|5[0-35-9]|6[67]|7[013-8]|8\d|9\d)\d{8}$/';
        if(!preg_match($ret,$user_tel)){
            return json_encode(['code'=>10000,'msg'=>'手机格式不正确']);
        }
        $code=new Code();
        return $code->verify($user_tel);
    }
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
    //手机号发送
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
}
