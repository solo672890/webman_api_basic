<?php
/**
 * UserServer: 木鱼
 * Date: 2023-02-19 10:47
 * Ps:
 */

namespace app\exception;


use app\exception\src\Exception\BaseException;

class ForbiddenHttpException extends BaseException
{
    /**
     * @var int
     */
    public int $statusCode = 200;

    /**
     * @var string
     */
    public string $errorMessage = '账号被禁用';

    public int $errorCode=10010;


}
