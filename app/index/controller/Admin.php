<?php
namespace app\index\controller;

class Admin
{
    public function index()
    {
        return '2，赵';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
    public function abc(){
        echo "老吴又来了";
        return view('index@Admin/index');
    }
}
