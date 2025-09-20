<?php

namespace app\controller;

use app\exception\SmsException;
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
        try {
            $a=1;
            $b=0;
            $c=$a/$b;
        }catch (\Throwable $e){

            writeLog('program error','other',['msg'=>'错误记录'],Logger::WARNING,$e);
        }
        throw new SmsException();
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
