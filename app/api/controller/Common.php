<?php

namespace app\api\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    public function http_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//向那个url地址上面发送
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//设置发送http请求时需不需要证书
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置发送成功后要不要输出1 不输出，0输出
        $output = curl_exec($ch);//执行
        curl_close($ch);    //关闭
        return $output;
    }

    public function http_post($url,$data){
        $curl = curl_init(); //初始化
        curl_setopt($curl, CURLOPT_URL, $url);//向那个url地址上面发送
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);//需不需要带证书
        curl_setopt($curl, CURLOPT_POST, 1); //是否是post方式 1是，0不是
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//需不需要输出
        $output = curl_exec($curl);//执行
        curl_close($curl); //关闭
        return $output;
    }
}
