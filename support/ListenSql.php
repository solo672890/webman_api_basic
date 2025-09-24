<?php

namespace support;

use think\facade\Db;
use Webman\Bootstrap;

/**
 * @notes
 * @author ruby
 * 2025/9/25 03:20
 */
class ListenSql implements Bootstrap {

    public static function start($worker)
    {
        $config = config('think-orm.connections.mysql');

        if (!$config['trigger_sql']) {
            return;
        }
        // 进行监听处理
        Db::listen(function($sql, $runtime) use ($config) {
            if ($sql === 'select 1') {
                // 心跳
                return;
            }
            $log = $sql." [{$runtime}s]";
            // 打印到控制台
            echo "[".date("Y-m-d H:i:s")."]"."\033[32m".$log."\033[0m".PHP_EOL;

        });
    }


    //如果是larval orm
    public static function ifLarval($worker){
        $config = config('laravelorm-log.app');
        if (!$config['trigger_sql']) {
            return;
        }
        // 进行监听处理
        Db::listen(function($query) use ($config) {
            $sql = $query->sql;
            $time = $query->time;
            if ($sql === 'select 1') {
                // 心跳
                return;
            }
            $bindings = [];
            if ($query->bindings) {
                foreach ($query->bindings as $v) {
                    if (is_numeric($v)) {
                        $bindings[] = $v;
                    } else {
                        $bindings[] = '"' . strval($v) . '"';
                    }
                }
            }
            $sql = self::replacePlaceholders($sql, $bindings);
            $log = $sql." [{$time}ms]";
            // 打印到控制台
            if ($config['console']) {
                echo "[".date("Y-m-d H:i:s")."]"."\033[32m".$log."\033[0m".PHP_EOL;
            }
        });
    }
    /**
     * 字符串处理
     * @param $sql
     * @param $params
     * @return mixed|string
     */
    public static function replacePlaceholders($sql, $params) {
        if (empty($params)) {
            return $sql;
        }
        $parts = explode('?', $sql);
        $result = $parts[0];
        $paramCount = count($params);
        for ($i = 0; $i < $paramCount && $i < count($parts) - 1; $i++) {
            $value = $params[$i];
            $result .= $value . $parts[$i + 1];
        }
        if (count($parts) - 1 > $paramCount) {
            $result .= implode('?', array_slice($parts, $paramCount + 1));
        }
        return $result;
    }
}