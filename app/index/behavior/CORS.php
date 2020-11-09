<?php
namespace app\index\behavior;
use think\facade\Session;
use think\Request;
/**
 * CORS跨域
 * Class CORS
 *
 * @package app\index\behavior
 */
class CORS
{
//    public function appInit(){
//        $origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:'';
//        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE');
//        header('Access-Control-Allow-Headers:Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With');
//    }
    public function appInit(){
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Allow-Headers:*');
        header('Access-Control-Allow-Credentials:false');
        if (request()->isOptions()) {
            sendResponse('',200,'ok');
        }
//        $origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:'';
//        header('Access-Control-Allow-Credentials:true');
//        header('content-type:application/json;charset=utf8');
//        header('Access-Control-Allow-Origin:'.$origin);
//        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE');
//        header('Access-Control-Allow-Headers:Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With');
//        if(request()->isOptions()){
//            exit();
//        }
    }
}
