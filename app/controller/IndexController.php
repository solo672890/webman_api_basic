<?php

namespace app\controller;

use app\exception\SmsException;
use app\extends\task\factory\TaskClient;
use app\extends\task\Test1;
use app\extends\task\Test2;
use Monolog\Logger;
use support\Request;

class IndexController
{
    public function index(Request $request)
    {
        return json(['code' => 0, 'msg' => 'hello index']);
    }

    public function view(Request $request)
    {
        return view('index/view', ['name' => 'webman']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

    public function testException(Request $request){
        $post = [1, 2];
        TaskClient::setHandlerClass(Test1::class)->send($post);
        //第二参数可以接收Task::class处理的结果
        TaskClient::setHandlerClass(Test2::class)->send($post,function ($res){
            var_dump($res);
            var_dump('收到结果task2');
        });
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
