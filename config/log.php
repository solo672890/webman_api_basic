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
    'default' => [  //默认channel 只处理项目系统级别的异常
        'handlers' => [
            [
                'class' => \support\MonologExtendHandler::class,
                'constructor' => ['systemException', 10000000, //$maxFileSize
                    Monolog\Logger::DEBUG,true, 0755
                ],
                'formatter' => [
                    'class' => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [null, 'Y-m-d H:i:s', true,true],
                ],
            ]
        ],
    ],
    'dailyCheck' => [  //巡检区日志
        'handlers' => [
            [
                'class' => \support\MonologExtendHandler::class,
                'constructor' => ['dailyCheck', 10000000, //$maxFileSize
                    Monolog\Logger::DEBUG,true, 0755
                ],
                'formatter' => [
                    'class' => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [null, 'Y-m-d H:i:s', true,true],
                ],
            ]
        ],
    ],

];
