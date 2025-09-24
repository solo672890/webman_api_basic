<?php

namespace app\controller\api\v1;

use app\exception\LimiterException;
use app\extends\log\BuildLog;
use support\Log;
use support\Redis;
use support\Request;
use Webman\RateLimiter\Limiter;

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

//        var_dump($request->session()->getId());
//        var_dump($request->header('user-agent', ''));
//        executionTime(function () use ($request){
//            for($i=0;$i<100000;$i++){
//                $s=md5($request->session()->getId().$request->header('user-agent', '').$request->getRealIp());
//                BuildLog::channel('other')->info($s);
//                Redis::get($s);
//                Redis::set($s, $s);
//            }
//        });


        if($request->userId){
//            Limiter::check($request->userId, 6, 2, function () {
//                throw new LimiterException('请求频繁');
//            });
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
