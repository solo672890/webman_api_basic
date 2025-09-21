<?php

namespace app\extends\rpc\src;

use app\extends\rpc\src\exception\RpcResponseException;
use app\extends\rpc\src\exception\RpcUnexpectedValueException;
use Throwable;

$GLOBALS['socket_resource'] = null;

class RpcClient {


    protected static ?RpcClient $instance = null;
    public int $timeout = 3; //读写超时
    private $address;
    private $connectTimeout = 3;
    private string $class;
    private string $method;

    private $resource;

    public static function instance(string $class = '', string $method = '', string $address = '') {
        if (static::$instance === null) {
            static::$instance = new static();
            static::$instance->address = $address ?: config('server.rpc.remote_rpc_address');
        }
        if (!$class) {
            throw new RpcUnexpectedValueException('Remote calling class is missing ');
        }
        if (!$method) {
            throw new RpcUnexpectedValueException('Remote calling method is missing ');
        }
        static::$instance->setRemoteFn($class, $method);
        if (!static::$instance->address) {
            throw new RpcUnexpectedValueException('Remote address is missing ');
        }
        return static::$instance;
    }

    protected function setRemoteFn(string $class = '', string $method = ''): void {
        static::$instance->class = $class;
        static::$instance->method = $method;
    }



    public function request(array $data = []) {

        try {
            if (!$GLOBALS['socket_resource']) {
                $GLOBALS['socket_resource'] = stream_socket_client($this->address, $errno, $errorMessage, $this->connectTimeout);
                if (!is_resource($GLOBALS['socket_resource'])) {
                    throw new RpcUnexpectedValueException('rpc request failed: ' . $errorMessage);
                }
                // 读写超时
                stream_set_timeout($GLOBALS['socket_resource'], $this->timeout);
            }


            $param = array_filter(['class' => static::$instance->class, 'method' => static::$instance->method, 'args' => $data,]);
            //
            // 发送请求
            fwrite($GLOBALS['socket_resource'], json_encode($param) . "\n");

            // 实时检测超时
            $info = stream_get_meta_data($GLOBALS['socket_resource']);
            if ($info['timed_out']) {
                throw new RpcResponseException(Error::make(408, 'rpc request timeout'));
            }

            $result = fgets($GLOBALS['socket_resource'], 10240000);
            if ($result) {
                return json_decode(trim($result), true);
            }
            return [];
        } catch (Throwable $e) {


            throw new RpcUnexpectedValueException('rpc request failed: ' . $e->getMessage());

        } finally {
            if ($GLOBALS['socket_resource'] && is_resource($GLOBALS['socket_resource'])) {
                fclose($GLOBALS['socket_resource']);
                $GLOBALS['socket_resource'] = null;
            }
        }


    }
}