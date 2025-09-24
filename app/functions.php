<?php
/**
 * Here is your custom functions.
 */

use support\Log;
use support\Response;

function redis_queue_send($redis, $queue, $data, $delay = 0) {
    $queue_waiting = '{redis-queue}-waiting';
    $queue_delay = '{redis-queue}-delayed';
    $now = time();
    $package_str = json_encode([
        'id'       => rand(),
        'time'     => $now,
        'delay'    => $delay,
        'attempts' => 0,
        'queue'    => $queue,
        'data'     => $data
    ]);
    if ($delay) {
        return $redis->zAdd($queue_delay, $now + $delay, $package_str);
    }
    return $redis->lPush($queue_waiting.$queue, $package_str);
}



function rJson(string $msg = 'success',int $code = 1,array|object $data = []): Response {

    return json(['code' => $code, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
}


if (!function_exists('makeRandInt')) {
    function makeRandInt($length) {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, 9);
            $code .= $rand;
        }
        return $code;
    }
}

if (!function_exists('curlPost')) {
    function curlPost($url, $data, $headers = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

/**
 * 校验md5秘钥
 */
if (!function_exists('checkMD5Sign')) {
    function checkMD5Sign($params, $key) {
        ksort($params);
        $signPars = "";
        foreach ($params as $k => $v) {
            if ("sign" != $k && $v != '') {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $sign = md5(substr($signPars, 0, -1) . "&key=" . $key);
        return $sign === $params['sign'] ? true : false;
    }
}


if (!function_exists('debugFn')) {
    function debugFn(string $msg = 'demo') :bool {
        if(is_string($msg)){
            echo sprintf("\033[1;36m%s\033[0m", $msg);
            echo "\n----------------------------------------------------\n";
        }else{
            var_dump($msg);
        }
        return true;
    }
}

if (!function_exists('successFn')) {
    function successFn(string $msg = 'demo'):bool {
        if(is_string($msg)){
            echo sprintf("\033[1;32m%s\033[0m", $msg);
            echo "\n----------------------------------------------------\n";
        }else{
            var_dump($msg);
        }
        return true;
    }

}
if (!function_exists('errorFn')){
    function errorFn(mixed $msg = 'demo') :bool {
        if(is_string($msg)){
            echo sprintf("\033[1;31m%s\033[0m", $msg);
            echo "\n----------------------------------------------------\n";
        }else{
            var_dump($msg);
        }

        return false;
    }
}

/**
 * @notes 随机生成邀请码
 * @param $length
 * @return string
 * @author Tab
 * @date 2021/7/26 11:17
 */
function generate_code($length = 7)
{
    // 去除字母IO数字012
    $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789';
    // 随机起始索引
    $start = mt_rand(0, strlen($letters) - $length);
    // 打乱字符串
    $shuffleStr = str_shuffle($letters);
    // 截取字符
    $randomStr = substr($shuffleStr, $start, $length);
    // 判断是否已被使用
    $res =\app\model\User::where('inviteCode', $randomStr)->select('inviteCode')->first();
    if(empty($res)) {
        return $randomStr;
    }
    generate_code($length);
}


function executionTime(Closure $fn):void{
    $start_time = microtime(true);
    $startMemory = memory_get_usage();
    $fn();
    debugFn(sprintf("耗时： %f秒<br>", round(microtime(true)-$start_time,3)));
    debugFn(sprintf("内存使用: %f kb<br>", (memory_get_usage() - $startMemory) / 1024));
}

