<?php

namespace app\extends\rpc\src;

use app\logic\utils\ReportLog;
use Workerman\Connection\TcpConnection;

/**
 * @notes
 * @author ruby
 * 2025/3/27 03:18
 */
class RpcProtocol {
    private int $ErrorCode=0;
    public function onMessage(TcpConnection $connection, string $string): ?bool
    {

        try {
            static $instances = [];

            $data = json_decode($string, true);
            $error = json_last_error();
            if ($error != JSON_ERROR_NONE) {
                return self::encode($connection, $this->ErrorCode, sprintf('Data(%s) is not json format!', $string));
            }

            $config = config('server.rpc');
            $class = $config['namespace'] . $data['class']??'';
            if (!class_exists($class)) {
                return self::encode($connection, $this->ErrorCode, sprintf('%s Class is not exist!', $data['class']));
            }

            $method = $data['method']??'';
            if (!method_exists($class, (string) $method)) {
                return self::encode($connection, $this->ErrorCode, sprintf('%s method is not exist!', $method));
            }
            $args = $data['args'] ?? [];
            if (!isset($instances[$class])) {
                $instances[$class] = new $class();
            }
            return $connection->send(call_user_func_array([$instances[$class], $method], $args));
        } catch (\Throwable $th) {
            return self::encode($connection, $this->ErrorCode, $th->getMessage());
        }
    }


    private static function encode(TcpConnection $connection,int $code,string $msg,array $data = []): ?bool
    {
        return $connection->send(json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ],JSON_UNESCAPED_UNICODE));
    }

}