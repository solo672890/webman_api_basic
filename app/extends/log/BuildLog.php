<?php

namespace app\extends\log;

use http\Exception\InvalidArgumentException;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use support\Log;
use support\MonologExtendHandler;
use Throwable;
use function array_values;
use function config;
use function is_array;

/**
 * @notes
 * @author ruby
 * 2025/4/3 01:55

 *
 * @method static void|bool log(int $level, $message='', array $appendData = [])
 * @method static void|bool debug(string $message='', array $appendData = [])
 * @method static void|bool info(string $message='', array $appendData = [])
 * @method static void|bool notice(string $message='', array $appendData = [])
 * @method static void|bool warning(string $message='', array $appendData = [])
 * @method static void|bool error(string $message='', array $appendData = [])
 * @method static void|bool critical(string $message='', array $appendData = [])
 * @method static void|bool alert(string $message='', array $appendData = [])
 * @method static void|bool emergency(string $message='', array $appendData = [])
 */
class BuildLog  {
    protected static $instance = null;

    private Throwable|null $e = null;
    private array $appendData = [];

    private string $channel;
    private bool $appendLog;



    public static function channel(string $name = 'default') :BuildLog{

        if(static::$instance ===null){
            static::$instance = new static();
        }

        static::$instance->clear();
        static::$instance->channel = $name;
        return static::$instance;
    }

    private function clear() {
        static::$instance->e=null;
        static::$instance->appendData=[];
        static::$instance->appendLog=false;
    }


    /**
     * @notes 记录异常
     * @param Throwable|null $e
     * @return BuildLog
     * @author ruby
     * 2025/9/23 07:11
     */
    public function addException(Throwable|null $e) :BuildLog{
        static::$instance->e=$e;
        return static::$instance;
    }



    /**
     * @notes 追加日志到巡检区
     * @param array $data
     * @return BuildLog
     * @author ruby
     * 2025/9/23 07:18
     */
    public function appendLog(bool $appendLog=false) :BuildLog{
        static::$instance->appendLog=$appendLog;
        return static::$instance;
    }





    public  function hook(string $name='',array $data=[]) {
        $msg=$data[0]??'';
        $appendData=$data[1]??[];

        $line = "\n-------------------------------------------------------------------\n";
        if (empty(request())) {
            $logInfo = $line;
            $requestParams = [];
        } else {
            $logInfo = '  [request_IP]:' . request()->getRealIp() .'  [visit_URL]:'. ltrim(request()->fullUrl(), '/'). $line;
            $requestParams = request()->all();
        }
        $logInfo.=$msg?"record msg: ".$msg."\n":'';
        $exception=static::$instance->e;
        $writeData=array_filter([
            'appendData'=>$appendData,
            'request_data'=>$requestParams,
            'exception' => $exception ?['error_message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => array_slice(explode("\n", $exception->getTraceAsString()), 0, 4)]:[]
        ]);

        $logInfo .= $writeData ? json_encode($writeData, JSON_UNESCAPED_UNICODE) . "\n" : "\n";

        if(static::$instance->appendLog && static::$instance->channel!=='dailyCheck'){
            CustomLog::channel('dailyCheck')->$name($logInfo);
        }
        CustomLog::channel(static::$instance->channel)->$name($logInfo);

    }



    public function __call(string $name, array $data=[]){
        $this->hook($name,$data);
    }

}