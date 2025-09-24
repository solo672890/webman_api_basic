<?php

namespace app\middleware;

use app\exception\LimiterException;
use app\exception\UnauthorizedHttpException;
use app\extends\jwt\JwtToken;
use app\extends\jwt\JwtTokenException;
use app\extends\log\BuildLog;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;
use Webman\RateLimiter\Limiter;

/**
 * @notes
 * @author ruby
 * 2025/4/20 12:30
 */
class LimiterMiddleware implements MiddlewareInterface {
    public function process(Request $request, callable $handler): Response {
        $request->userId=6;//模拟登录用户访问

        if($request->userId){
            $this->limiterExceptionHandler($request->userId, $request->header('user-agent',''));
            return $handler($request);
        }
        if( $request->sessionId()){
            $this->limiterExceptionHandler($request->sessionId(), $request->header('user-agent',''));
            return $handler($request);
        }
        //开放性产品并非特别适用这个方法,比如抖音快手,在公共场合(火车站)使用公共wifi
        // 但是,游客,手动关闭cookie,请求头又一致的情况不多见,应该由专门的模块对该类型进行日志分析,必要时根据情况,强制登录或是ip封印或是人机校验

        //如果是大产品,比如抖音,应该将用户访问的服务器和游客访问的服务器分开,避免游客(可能是破坏分子)的行为影响到正常用户,
        //如果游客行为疑问很多,此时不能ip封印,容易误杀,建议强制登录或是人机校验,然后对此ip上的用户和游客增加风险标签
        //再给一种比较柔和的方案,对称加密参数,参数里包含用户指纹.根据指纹的情况决定是否 封禁该设备

        //如果是非开放产品,比如核心功能是交易之类,但产品需求也要保持游客访问,则这个方法就非常适用.

        //总结,对这类匿名游客的方法:   1.增加风险评估系统(根据限流日志评估) 2.根据风险等级决定是否 封禁IP,强制登录,人机校验,封禁设备
        $header=$request->header('user-agent', '');
        $customID=$header.$request->getRealIp().$request->header('"content-length','');
        $this->limiterExceptionHandler(md5($customID), $header);

        return $handler($request);
    }


    protected function limiterExceptionHandler(string $key,string $header) {
        Limiter::check($key, 7,3, function ()use($header) {
            BuildLog::channel('limiterException')->warning($header);
            throw new LimiterException('请求频繁');
        });
    }
}
