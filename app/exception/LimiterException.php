<?php
/**
 * UserServer: 木鱼
 * Date: 2023-02-19 4:22
 * Ps:
 */

namespace app\exception;


use app\exception\src\Exception\BaseException;

class LimiterException extends BaseException
{
    /**
     * @var int
     */
    public int $statusCode = 429;

    /**
     * @var string
     */
    public string $errorMessage = 'Too Many Requests';

    public int $errorCode=10101;


}
