<?php

namespace app\extends\log;


use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use support\Log;
use support\MonologExtendHandler;

/**
 * @notes
 * @author ruby
 * 2025/9/23 07:02
 *
 * @method static void log($level, $message, array $context = [])
 * @method static void debug($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void emergency($message, array $context = [])
 */

class CustomLog extends Log{


    private static array $append_channel=['systemException','dailyCheck'];
    public static function channel(string $name = 'default') :Logger{
        if (!isset(static::$instance[$name])) {
            $config =self::buildLogConfig($name);
            $handlers = self::handlers($config);
            $processors = self::processors($config);
            static::$instance[$name] = new Logger($name, $handlers, $processors);
        }
        return static::$instance[$name];
    }

    protected static function buildLogConfig(string $name): array{
        $config=config('log', [])[$name]??[];
        if($config){
            return $config;
        }
        if(!in_array($name,self::$append_channel)){
            throw new \InvalidArgumentException("Channel not found in log config");
        }

        $config['handlers']=[
            [
                'class' => MonologExtendHandler::class,
                'constructor' => [$name, 10000000,
                    Logger::DEBUG,
                ],
                'formatter' => [
                    'class' => LineFormatter::class,
                    'constructor' => [null, 'Y-m-d H:i:s', true,true],
                ],
            ]
        ];
        return $config;
    }
}