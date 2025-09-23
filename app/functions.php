<?php
/**
 * Here is your custom functions.
 */



if (!function_exists('writeLog')) {
    function writeLog(string $msg = '', string $channel = 'default', array $extend = [], $level = 100,  ?Throwable $exception = null,bool $append=false): bool {
        $requestParams = [];
        $line = "\n-------------------------------------------------------------------\n";
        if (empty(request())) {
            $logInfo = $line;
        } else {
            $logInfo = '  [request_IP]:' . request()->getRealIp() .'  [visit_URL]:'. ltrim(request()->fullUrl(), '/'). $line;
            $requestParams = request()->all();
        }
        $logInfo.="Tips : ".$msg."\n";
        $tempArr = array_filter([
            'extend' => $extend,
            'request_params' => $requestParams,
            'exception' => $exception ? ['error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'error_trace' => array_slice(explode("\n", $exception->getTraceAsString()), 0, 4),
            ]:''
        ]);

        $logInfo .= $tempArr ? json_encode($tempArr, JSON_UNESCAPED_UNICODE) . "\n" : '';
        if($append){ //需要额外注意的日志,可加入巡检区
            \support\Log::channel('dailyCheck')->addRecord($level, $logInfo);
        }

        return \support\Log::channel($channel)->addRecord($level, $logInfo);
    }
}
