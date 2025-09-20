<?php
/**
 * UserServer: 木鱼
 * Date: 2023-02-19 6:33
 * Ps:
 */

namespace app\exception;


use app\exception\src\Exception\BaseException;

class ConfigErrorException extends BaseException
{
    /**
     * @var int
     */
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = 'config error';

    public int $errorCode=20000;


}
