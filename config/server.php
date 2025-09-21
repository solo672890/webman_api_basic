<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

return [
    'event_loop' => '',
    'stop_timeout' => 30,
    'pid_file' => runtime_path() . '/webman.pid',
    'status_file' => runtime_path() . '/webman.status',
    'stdout_file' => runtime_path() . '/logs/stdout.log',
    'log_file' => runtime_path() . '/logs/workerman.log',
    'max_package_size' => 10 * 1024 * 1024,
    'rpc'=>[
        //服务端调用地址
        'remote_rpc_address'=>getenv('APP_REMOTE_RPC_ADDRESS',''),
        //客户端监听地址
        'local_rpc_address'=>'text://'.getenv('APP_LOCAL_RPC_ADDRESS').':12345',
        //服务端处理类namespace
        'namespace'=>'\\app\\extends\\rpc\\',
    ],
];
