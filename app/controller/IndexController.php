<?php

namespace app\controller;

use app\exception\SmsException;
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
//        $a=1;
//        $b=0;
//        $c=$a/$b;
        throw new SmsException(statusCode: 500);
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
