<?php

namespace app\controller\api\v1;

use app\extends\log\BuildLog;
use support\Log;
use support\Request;

class IndexController
{

    static $count = 0;
    public function index(Request $request)
    {
        return json(['code' => 0, 'msg' => 'hello index']);
    }

    public function view(Request $request)
    {
        return view('index/view', ['name' => 'webman']);
    }
    public function ping(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ping']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

    public function testException(Request $request){

        try {
            $a=1;$b=0;
            $c=$a/$b;
        }catch (\Throwable $e){
            BuildLog::channel('other')->appendLog(true)->addException($e)->error($e->getMessage(),['test'=>'我是追加数据']);
        }


        return json(['code' => 0, 'msg' => 'ok']); //      5  0 13
    }

    public function testException1(Request $request){
//        BuildLog::channel('systemException')->test(13)->error('aaa',['test1'=>'test1']);
//        BuildLog::channel('dailyCheck')->test(13)->error('aaa',['test2'=>'test2']);



        BuildLog::channel('other')->test(2)->error('aaa');
        return json(['code' => 0, 'msg' => 'ok']); //      5  0 13
    }



}
