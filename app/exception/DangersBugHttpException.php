<?php
/**
 * UserServer: 木鱼
 * Date: 2023-02-17 22:12
 * Ps:
 */

namespace app\exception;


use app\exception\src\Exception\BaseException;

class DangersBugHttpException extends BaseException
{
    /**
     * @var int
     */
    public int $statusCode = 507;

    /**
     * @var string
     */
    public string $errorMessage = '网络故障!';

    public int $errorCode=10000;


}
