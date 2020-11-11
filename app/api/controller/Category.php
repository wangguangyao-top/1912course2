<?php
namespace app\api\controller;

use think\cache\driver\Redis;
use app\api\controller\Common;
use think\Request;
use app\api\model\user\Category as CategoryModel;

class Category extends Common
{
    /**
     * 课程分类
     * @param Request $request
     */
    public function index(Request $request)
    {
        $CategoryModel = new CategoryModel;

        $where=[
            ['p_id',"=",0],
        ];
        $data = $CategoryModel->where($where)->select();
        $data=json_encode($data,true);
        return $data;
    }

}