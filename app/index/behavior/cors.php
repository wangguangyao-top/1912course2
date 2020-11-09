<?php
namespace app\index\behavior;
use think\facade\Session;
use think\Request;
/**
 * CORS跨域
 * Class cors
 *
 * @package app\index\behavior
 */
class cors
{
    public function appInit(){
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
    }
}
