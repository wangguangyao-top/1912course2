<?php

namespace app\api\controller;

use think\Request;
use app\api\controller\Common;
use app\api\model\BankModel;
use app\api\model\ClassificationModel;

class Bank extends Common
{

    public function index()
    {
        $url="http://www.tp5.com/index/bank/index";
        $bank=ClassificationModel::select();
        $bank=json_encode($bank,true);
        print_r($bank);
        $this->http_post($url,$bank);die;

    }


}
